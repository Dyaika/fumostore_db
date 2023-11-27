<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/style.css" type="text/css" />
    <title>FumoFumo Учёт</title>
    <style>
        tbody tr {
            cursor: pointer;
            transition: background-color 0.3s;
        }

        tbody tr:hover {
            background-color: #e0e0e0;
        }
    </style>
</head>

<body>
    <header>
        <h1>Главная</h1>
    </header>

    <div class="table-container">
        <h2>Адреса магазинов</h2>
        <table>
            <thead>
                <tr>
                    <th>ID магазина</th>
                    <th>Адрес</th>
                    <th>Время открытия</th>
                    <th>Время закрытия</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $mysqli = new mysqli("mysql", "user", "1111", "fumo_plush_store");

                $addressQuery = "
                    SELECT
                        s.store_id,
                        CONCAT(s2.street_name, ' ', a.building_number, ', ', c.city_name) AS full_address,
                        s.open_time,
                        s.close_time
                    FROM
                        store s
                    JOIN
                        address a ON a.address_id = s.address_id
                    JOIN
                        city c ON c.city_id = a.city_id
                    JOIN
                        street s2 ON s2.street_id = a.street_id
                ";

                $result = $mysqli->query($addressQuery);
                foreach ($result as $row) {
                    $itemId = $row['store_id'];
                    $itemName = $row['full_address'];
                    $itemCost = $row['open_time'];
                    $itemCount = $row['close_time'];

                    // Делаем store_id кликабельным и перенаправляем на /store.php/{store_id}
                    echo "<tr onclick=\"window.location='/store.php/{$itemId}'\">
                            <td>{$itemId}</td>
                            <td>{$itemName}</td>
                            <td>{$itemCost}</td>
                            <td>{$itemCount}</td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <div style="text-align: center; margin-top: 50px;">
        <a href="/items.php"
            style="display: inline-block; padding: 10px 20px; background-color: grey; color: white; text-decoration: none; font-size: 16px; margin-top: 20px;">
            К общему учёту
        </a>
    </div>
</body>

</html>