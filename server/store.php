<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/style.css" type="text/css" />
    <link rel="icon" type="image/png" href="/images/favicon.png">
    <title>FumoFumo Учёт</title>
    <style>
        #hoverable_id {
            cursor: pointer;
            transition: background-color 0.3s;
        }

        #hoverable_id:hover {
            background-color: #e0e0e0;
        }

        .edit-form {
            margin: 20px;
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


                    echo '<tr>
                    <td colspan="4">
                    <form method="get" action="/store.php/' . $store_id . '">
                        <input type="hidden" name="action" value="add">
                        <button type="submit">Добавить</button>
                    </form>
                    </td>
                    </tr>';
                    $result = $mysqli->query("SELECT i.item_id, i.item_name, i.item_cost, ia.item_count
                                    FROM itemstore_association as ia
                                    JOIN item as i ON i.item_id = ia.item_id
                                    WHERE store_id = $store_id");

                    foreach ($result as $row) {
                        $itemId = $row['item_id'];
                        $itemName = $row['item_name'];
                        $itemCost = $row['item_cost'];
                        $itemCount = $row['item_count'];
                        echo "<tr>
                            <td id='hoverable_id' onclick=\"window.location='/item.php/{$itemId}'\">{$itemId}</td>
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