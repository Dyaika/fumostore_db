<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/style.css" type="text/css" />
    <title>FumoFumo Учёт</title>
</head>

<body>
<header>
        <div class="home-button">
            <a href="/index.php">
                <img id="home_btn" src="/home.svg" alt="Home">
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
                echo "<table>
                    <thead>
                        <tr>
                            <th>Артикул</th>
                            <th>Название</th>
                            <th>Цена</th>
                            <th>Количество</th>
                        </tr>
                    </thead>
                    <tbody>";

                $result = $mysqli->query("SELECT i.item_id, i.item_name, i.item_cost, ia.item_count
                                    FROM itemstore_association as ia
                                    JOIN item as i ON i.item_id = ia.item_id
                                    WHERE store_id = $store_id");

                foreach ($result as $row) {
                    echo "<tr><td>{$row['item_id']}</td><td>{$row['item_name']}</td><td>{$row['item_cost']}</td><td>{$row['item_count']}</td></tr>";
                }

                echo "</tbody>
                    </table>";
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