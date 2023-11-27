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
        <div class="home-button">
            <a href="/index.php">
                <img id="home_btn" src="/images/home.svg" alt="Home">
            </a>
        </div>
        <h1>Товары</h1>
    </header>
    <div class="table-container">
        <h2>Общий учёт</h2>
        <table>
            <thead>
                <tr>
                    <th>Артикул</th>
                    <th>Название</th>
                    <th>Цена</th>
                    <th>Количество</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $mysqli = new mysqli("mysql", "user", "1111", "fumo_plush_store");
                $result = $mysqli->query("SELECT * FROM stock");
                foreach ($result as $row) {

                    $itemId = $row['item_id'];
                    $itemName = $row['item_name'];
                    $itemCost = $row['item_cost'];
                    $itemCount = $row['item_count'];

                    // Делаем store_id кликабельным и перенаправляем на /store.php/{store_id}
                    echo "<tr onclick=\"window.location='/item.php/{$itemId}'\">
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
</body>

</html>