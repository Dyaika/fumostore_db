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
                    echo "<tr><td>{$row['item_id']}</td><td>{$row['item_name']}</td><td>{$row['item_cost']}</td><td>{$row['item_count']}</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>
