<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/style.css" type="text/css" />
    <link rel="icon" type="image/png" href="/images/favicon.png">
    <title>FumoFumo Учёт</title>
    <style>
        tbody tr {
            cursor: pointer;
            transition: background-color 0.3s;
        }

        tbody tr:hover {
            background-color: #e0e0e0;
        }

        .edit-form {
            margin: 20px;
        }

        .edit-form label {
            display: block;
            margin-bottom: 10px;
        }

        .edit-form input {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
        }

        .edit-form button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
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
            // Обычная таблица товаров
            echo '<h2>Общий учёт</h2>';
            echo '<table>';
            echo '<thead>
                    <tr>
                        <th>Артикул</th>
                        <th>Название</th>
                        <th>Цена</th>
                        <th>Количество</th>
                    </tr>
                </thead>
                <tbody>';

            $result = $mysqli->query("SELECT * FROM stock");
            foreach ($result as $row) {
                $itemId = $row['item_id'];
                $itemName = $row['item_name'];
                $itemCost = $row['item_cost'];
                $itemCount = $row['item_count'];

                echo "<tr onclick=\"window.location='/item.php/{$itemId}'\">
                        <td>{$itemId}</td>
                        <td>{$itemName}</td>
                        <td>{$itemCost}</td>
                        <td>{$itemCount}</td>
                    </tr>";
            }

            echo '</tbody></table>';

            // Кнопка "Добавить" при обычном просмотре
            echo '<div style="text-align: center; margin-top: 20px;">
                    <a href="/items.php?action=add"
                        style="display: inline-block; padding: 10px 20px; background-color: #4CAF50; color: white; text-decoration: none; font-size: 16px;">
                        Добавить товар
                    </a>
                </div>';
        }
        ?>
    </div>
</body>

</html>