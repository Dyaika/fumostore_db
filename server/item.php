<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/style.css" type="text/css" />
    <link rel="icon" type="image/png" href="/images/favicon.png">
    <title>FumoFumo Учёт</title>

    <style>
        /* Добавленные стили для информации о товаре */
        .item-info {
            padding: 20px;
            background-color: #f9f9f9;
            margin: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .item-info img {
            max-width: 100%;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .item-info p {
            margin-bottom: 10px;
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
        <h1>Информация об игрушке</h1>
    </header>

    <?php
    $mysqli = new mysqli("mysql", "user", "1111", "fumo_plush_store");

    // Извлекаем путь из запроса
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    // Используем регулярное выражение для извлечения item_id из пути
    preg_match('/\/(\d+)/', $path, $matches);

    if (!empty($matches)) {
        $item_id = $matches[1];

        // Проверка наличия item_id
        $result = $mysqli->query("SELECT COUNT(*) as count FROM item WHERE item_id = $item_id");
        $row = $result->fetch_assoc();
        $itemExists = $row['count'] > 0;

        if ($itemExists) {
            $result = $mysqli->query("SELECT
                        item_id,
                        item_name,
                        item_cost,
                        item_description,
                        item_release_year,
                        item_width,
                        item_height,
                        image_url
                    FROM item
                    WHERE item_id = $item_id");

            $item = $result->fetch_assoc();

            // Проверяем параметр "action"
            $action = isset($_GET['action']) ? $_GET['action'] : '';

            if ($action === 'edit') {
                // Форма для редактирования
                echo '<div class="edit-form">';
                echo '<form method="post" action="/update_item.php">';
                echo '<label for="item_name">Название:</label>';
                echo '<input type="text" name="item_name" value="' . $item['item_name'] . '" required>';
                echo '<label for="item_cost">Цена:</label>';
                echo '<input type="text" name="item_cost" value="' . $item['item_cost'] . '" required>';
                echo '<label for="item_description">Описание:</label>';
                echo '<textarea name="item_description">' . $item['item_description'] . '</textarea>';
                echo '<label for="item_release_year">Год выпуска:</label>';
                echo '<input type="text" name="item_release_year" value="' . $item['item_release_year'] . '">';
                echo '<label for="item_width">Ширина:</label>';
                echo '<input type="text" name="item_width" value="' . $item['item_width'] . '">';
                echo '<label for="item_height">Высота:</label>';
                echo '<input type="text" name="item_height" value="' . $item['item_height'] . '">';
                echo '<button type="submit">Сохранить</button>';
                echo '<input type="hidden" name="item_id" value="' . $item['item_id'] . '">';
                echo '</form>';
                echo '</div>';
            }
             else {
                // Отображаем обычную информацию о товаре с кнопкой "Редактировать"
                echo '<div class="item-info">';
                echo '<h2>' . $item['item_name'] . '</h2>';
                echo '<img src="' . $item['image_url'] . '" alt="' . $item['item_name'] . '">';
                echo '<p><strong>Цена:</strong> ' . $item['item_cost'] . '</p>';
                echo '<p><strong>Год выпуска:</strong> ' . $item['item_release_year'] . '</p>';
                echo '<p><strong>Ширина:</strong> ' . $item['item_width'] . ' см</p>';
                echo '<p><strong>Высота:</strong> ' . $item['item_height'] . ' см</p>';
                echo '<p><strong>Описание:</strong> ' . $item['item_description'] . '</p>';

                // Кнопка "Редактировать"
                echo '<a href="?action=edit" class="edit-button">Редактировать</a>';
                echo '</div>';
            }
        } else {
            // Выводим сообщение об ошибке и кнопку "Назад к списку магазинов"
            echo '<div style="text-align: center; margin-top: 50px;">
                        <p style="color: red; font-size: 18px;">Ошибка: Игрушка не найдена.</p>
                        <a href="/index.php" style="display: inline-block; padding: 10px 20px; background-color: #4CAF50; color: white; text-decoration: none; font-size: 16px; margin-top: 20px;">Назад к списку магазинов</a>
                      </div>';
        }
    } else {
        // Выводим сообщение об ошибке, если item_id не найден в URL
        echo '<div style="text-align: center; margin-top: 50px;">
                    <p style="color: red; font-size: 18px;">Ошибка: Не указан item_id в URL.</p>
                    <a href="/index.php" style="display: inline-block; padding: 10px 20px; background-color: #4CAF50; color: white; text-decoration: none; font-size: 16px; margin-top: 20px;">Назад к списку магазинов</a>
                  </div>';
    }
    ?>
</body>

</html>
