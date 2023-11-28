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

        a.delete-btn img{
            width: 48px;
            height: 48px;
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

                    echo "
                    <form class='edit-form' method='get' action='/store.php/{$store_id}'>
                        <label for='search_name'>Поиск по названию:</label>
                        <input type='text' name='search_name' id='search_name'>
            
                        <label for='search_id'>Поиск по артикулу:</label>
                        <input type='text' name='search_id' id='search_id'>
        
                        <button type='submit'>Найти</button>
                    </form>";
                    $sort = $_GET['sort'] ?? '';
                    $orderBy = '';
                    if ($sort === 'asc') {
                        $orderBy = 'ORDER BY item_cost ASC';
                    } elseif ($sort === 'desc') {
                        $orderBy = 'ORDER BY item_cost DESC';
                    }

                    echo '<table>
                    <thead>
                        <tr>
                            <th>Артикул</th>
                            <th>Название</th>
                            <th>
                            <a href="?sort=' . (($sort === 'asc') ? 'desc' : 'asc') . '">Цена' . (($sort === 'asc') ? '&#9650;' : '&#9660;') . '</a>

                            </th>
                            <th>Количество</th>
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

                    // Добавьте проверку наличия параметров поиска
                    $searchName = $_GET['search_name'] ?? '';
                    $searchId = $_GET['search_id'] ?? '';
                    $query = "SELECT i.item_id, i.item_name, i.item_cost, ia.item_count
                    FROM itemstore_association as ia
                    JOIN item as i ON i.item_id = ia.item_id
                    WHERE store_id = $store_id";

                    if (!empty($searchName)) {
                        $query .= " AND i.item_name LIKE '%$searchName%'";
                    }

                    if (!empty($searchId)) {
                        $query .= " AND i.item_id = '$searchId'";
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
                                    <input type='number' name='item_count' value='$itemCount' required>
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