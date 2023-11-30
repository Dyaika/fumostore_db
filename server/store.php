<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/style.css" type="text/css" />
    <link rel="icon" type="image/png" href="/images/favicon.png">
    <title>FumoFumo Учёт</title>
    <style>
        form {
            padding: 3px;
        }

        a {
            text-decoration: none;
        }

        .edit-form {
            margin: 20px;
            display: flex;
            flex-direction: column;
        }

        .edit-form label {
            display: block;
            margin-bottom: 10px;
        }

        input {
            padding: 8px;
            margin: 10px;
        }
        .searchbar input{
            
            display: block;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            max-width: fit-content;
        }

        .large-btn {
            max-width: none;
        }

        .delete-col {
            text-align: center;
        }

        a.delete-btn img {
            width: 48px;
            height: 48px;
        }
        .count0 {
            background-color: red;
        }
    </style>
</head>

<body>
    <header>
        <div class="home-button">
            <a href="/index.php">
                <img id="home_btn" src="/images/home.svg" alt="Home">
            </a>
        </div>
        <h1>Мягкие игрушки</h1>
    </header>

    <div class="table-container">

        <?php
        $mysqli = new mysqli("mysql", "user", "1111", "fumo_plush_store");

        // Извлекаем путь из запроса
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Используем регулярное выражение для извлечения store_id из пути
        preg_match('/\/(\d+)/', $path, $matches);

        if (!empty($matches)) {
            $store_id = $matches[1];

            // Проверка наличия store_id
            $result = $mysqli->query("SELECT COUNT(*) as count FROM store WHERE store_id = $store_id");
            $row = $result->fetch_assoc();
            $storeExists = $row['count'] > 0;

            if ($storeExists) {
                if (isset($_GET['action']) && $_GET['action'] === 'add') {
                    // Форма для добавления товара в магазин
                    echo '<div class="edit-form">';
                    echo '<form method="post" action="/create_isa.php">';

                    // Выбор товара из доступных товаров
                    $availableItemsQuery = "SELECT i.item_id, i.item_name
                    FROM item i
                    LEFT JOIN itemstore_association ia ON i.item_id = ia.item_id AND ia.store_id = $store_id
                    WHERE ia.item_id IS NULL";
                    $availableItemsResult = $mysqli->query($availableItemsQuery);

                    echo '<label for="item_id">Выберите товар:</label>';
                    echo '<select name="item_id" required>';
                    while ($item = $availableItemsResult->fetch_assoc()) {
                        echo '<option value="' . $item['item_id'] . '">' . $item['item_name'] . '</option>';
                    }
                    echo '</select>';

                    // Поле для указания количества
                    echo '<label for="item_count">Количество:</label>';
                    echo '<input type="number" name="item_count" required>';

                    // Скрытое поле с ID магазина
                    echo '<input type="hidden" name="store_id" value="' . $store_id . '">';

                    echo '<button type="submit">Добавить</button>';
                    echo '</form>';
                    echo '</div>';
                } else {

                    // Добавьте проверку наличия параметров поиска
                    $searchName = $_GET['search_name'] ?? '';
                    $searchId = $_GET['search_id'] ?? '';
                    $minCost = $_GET['min_cost'] ?? '';
                    $maxCost = $_GET['max_cost'] ?? '';
                    $sort_cost = $_GET['sort_cost'] ?? '';
                    $sort_name = $_GET['sort_name'] ?? '';
                    $sort_count = $_GET['sort_count'] ?? '';
                    $queryLink = "&search_name=$searchName&search_id=$searchId&min_cost=$minCost&max_cost=$maxCost";
                    echo "
            <form class='searchbar' method='get' action='/items.php'>
                <label for='search_name'>Поиск по названию:</label>
                <input type='text' name='search_name' id='search_name' value='$searchName'>
    
                <label for='search_id'>Поиск по артикулу:</label>
                <input type='text' name='search_id' id='search_id' value='$searchId'>
                
                <label for='min_cost'>Минимальная цена:</label>
                <input type='numeric' name='min_cost' id='min_cost' value='$minCost'>
                
                <label for='max_cost'>Максимальная цена:</label>
                <input type='numeric' name='max_cost' id='max_cost' value='$maxCost'>
                <input type='hidden' name='sort_cost' value='$sort_cost'>
                <input type='hidden' name='sort_name' value='$sort_name'>
                <input type='hidden' name='sort_count' value='$sort_count'>

                <button type='submit'>Найти</button>
            </form>
            <a href='?'>Сброс</a>";
                    $orderByClauses = [];

                    if ($sort_cost === 'asc') {
                        $orderByClauses[] = 'item_cost ASC';
                    } elseif ($sort_cost === 'desc') {
                        $orderByClauses[] = 'item_cost DESC';
                    }

                    if ($sort_name === 'asc') {
                        $orderByClauses[] = 'item_name ASC';
                    } elseif ($sort_name === 'desc') {
                        $orderByClauses[] = 'item_name DESC';
                    }

                    if ($sort_count === 'asc') {
                        $orderByClauses[] = 'item_count ASC';
                    } elseif ($sort_count === 'desc') {
                        $orderByClauses[] = 'item_count DESC';
                    }

                    $orderBy = !empty($orderByClauses) ? 'ORDER BY ' . implode(', ', $orderByClauses) : '';


                    echo '<table>
                    <thead>
                        <tr>
                            <th>Артикул</th>
                            <th>
                        <a href="?sort_name=' . (($sort_name === 'asc') ? 'desc' : 'asc') . '&sort_cost=' . $sort_cost . $queryLink . '">Название' . (($sort_name === 'asc') ? '&#9650;' : '&#9660;') . '</a>
                        </th>
                        <th>
                        <a href="?sort_cost=' . (($sort_cost === 'asc') ? 'desc' : 'asc') . '&sort_name=' . $sort_name . $queryLink . '">Цена' . (($sort_cost === 'asc') ? '&#9650;' : '&#9660;') . '</a>
                        </th>
                        <th>
                        <a href="?sort_count=' . (($sort_count === 'asc') ? 'desc' : 'asc') . $queryLink . '">Количество' . (($sort_count === 'asc') ? '&#9650;' : '&#9660;') . '</a>
                        </th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>';


                    echo '<tr>
                    <td colspan="5">
                    <form method="get" action="/store.php/' . $store_id . '">
                        <input type="hidden" name="action" value="add">
                        <button class="large-btn" type="submit">Добавить</button>
                    </form>
                    </td>
                    </tr>';

                    $query = "SELECT i.item_id, i.item_name, i.item_cost, ia.item_count
                    FROM itemstore_association as ia
                    JOIN item as i ON i.item_id = ia.item_id
                    WHERE store_id = $store_id";

                    if (!empty($searchName)) {
                        $query .= " AND item_name LIKE '%$searchName%'";
                    }

                    if (!empty($searchId)) {
                        $query .= " AND item_id = '$searchId'";
                    }
                    if (!empty($minCost)) {
                        $query .= " AND item_cost >= '$minCost'";
                    }
                    if (!empty($maxCost)) {
                        $query .= " AND item_cost <= '$maxCost'";
                    }
                    $query .= " $orderBy";
                    $result = $mysqli->query($query);

                    foreach ($result as $row) {
                        $itemId = $row['item_id'];
                        $itemName = $row['item_name'];
                        $itemCost = $row['item_cost'];
                        $itemCount = $row['item_count'];
                        echo "<tr>
                            <td class='hoverable' onclick=\"window.location='/item.php/{$itemId}'\">{$itemId}</td>
                            <td>$itemName</td>
                            <td>$itemCost</td>
                            <td>
                                <form method='post' action='/update_isa.php'>
                                    <input class='count$itemCount' type='number' name='item_count' value='$itemCount' required>
                                    <input type='hidden' name='item_id' value='$itemId'>
                                    <input type='hidden' name='store_id' value='$store_id'>
                                    <button type='submit'>Обновить</button>
                                </form>                                
                            </td>
                            <td class='delete-col'>
                            <a class='delete-btn' href='/delete_isa.php?item_id={$itemId}&store_id={$store_id}'>
                            <img src='/images/delete.svg'></a>
                            </td>
                            </tr>";
                    }


                    echo "</tbody>
                    </table>";

                }
            } else {
                // Выводим сообщение об ошибке и кнопку "Назад к списку магазинов"
                echo '<div style="text-align: center; margin-top: 50px;">
                        <p style="color: red; font-size: 18px;">Ошибка: Магазин не найден.</p>
                        <a href="/index.php" style="display: inline-block; padding: 10px 20px; background-color: #4CAF50; color: white; text-decoration: none; font-size: 16px; margin-top: 20px;">Назад к списку магазинов</a>
                      </div>';
            }
        } else {
            // Выводим сообщение об ошибке, если store_id не найден в URL
            echo '<div style="text-align: center; margin-top: 50px;">
                    <p style="color: red; font-size: 18px;">Ошибка: Не указан store_id в URL.</p>
                    <a href="/index.php" style="display: inline-block; padding: 10px 20px; background-color: #4CAF50; color: white; text-decoration: none; font-size: 16px; margin-top: 20px;">Назад к списку магазинов</a>
                  </div>';
        }

        ?>

    </div>
</body>

</html>