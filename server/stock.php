<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css" type="text/css"/>
    <title>Мягкие игрушки</title>
</head>
<body>
    <header>
        <h1>Мягкие игрушки</h1>
    </header>

    <div class="table-container">
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
                
                if (isset($_GET['store_id'])) {
                    $store_id = $_GET['store_id'];
                    $result = $mysqli->query("SELECT i.item_id, i.item_name, i.item_cost, ia.item_count
                        FROM itemstore_association as ia
                        JOIN item as i ON i.item_id = ia.item_id
                        WHERE store_id = $store_id");
                } else {
                    $result = $mysqli->query("SELECT * FROM stock");
                }

                foreach ($result as $row) {
                    echo "<tr><td>{$row['item_id']}</td><td>{$row['item_name']}</td><td>{$row['item_cost']}</td><td>{$row['item_count']}</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
