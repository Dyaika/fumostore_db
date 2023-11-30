<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/style.css" type="text/css" />
    <link rel="icon" type="image/png" href="/images/favicon.png">
    <title>FumoFumo Учёт</title>
    <style>
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
            display: block;
            min-width: 30em;
            padding: 8px;
            margin-bottom: 10px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
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
        <h1>Товары</h1>
    </header>
    <div class="table-container">
        <?php
        $mysqli = new mysqli("mysql", "user", "1111", "fumo_plush_store");

        // Проверяем наличие параметра action
        $action = $_GET['action'] ?? '';

        if ($action === 'add') {
            echo '<div class="edit-form">';
            echo '<form method="post" action="/create_item.php">';

            echo '<label for="item_name">Название:</label>';
            echo '<input type="text" name="item_name" required>';

            echo '<label for="item_cost">Цена:</label>';
            echo '<input type="text" name="item_cost" required>';

            echo '<label for="item_description">Описание:</label>';
            echo '<textarea name="item_description" required></textarea>';

            echo '<label for="item_release_year">Год выпуска:</label>';
            echo '<input type="text" name="item_release_year" required>';

            echo '<label for="item_width">Ширина:</label>';
            echo '<input type="text" name="item_width" required>';

            echo '<label for="item_height">Высота:</label>';
            echo '<input type="text" name="item_height" required>';

            echo '<label for="image_url">URL изображения:</label>';
            echo '<input type="text" name="image_url" required>';

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
            <form method='get' action='/items.php'>
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

            // Обычная таблица товаров
            echo '<h2>Общий учёт</h2>';
            echo '<table>';
            echo '<thead>
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
                    </tr>
                </thead>
                <tbody>';
            echo '<tr>
                <td colspan="4">
                <form method="get" action="/items.php">
                    <input type="hidden" name="action" value="add">
                    <button class="large-btn" type="submit">Новый товар</button>
                </form>
                </td>
                </tr>';

            // Измените запрос в базе данных, учитывая параметры поиска
            $query = "SELECT * FROM stock WHERE 1=1";

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

                echo "<tr class='hoverable' onclick=\"window.location='/item.php/{$itemId}'\">
                        <td>{$itemId}</td>
                        <td>{$itemName}</td>
                        <td>{$itemCost}</td>
                        <td class='count$itemCount'>{$itemCount}</td>
                    </tr>";
            }

            echo '</tbody></table>';
        }
        ?>
    </div>
</body>

</html>