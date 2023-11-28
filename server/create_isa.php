<?php
$mysqli = new mysqli("mysql", "user", "1111", "fumo_plush_store");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_id = $_POST["item_id"];
    $store_id = $_POST["store_id"];
    $item_count = $_POST["item_count"];

    $sql = "INSERT INTO itemstore_association (item_id, store_id, item_count) 
            VALUES ('$item_id', '$store_id', '$item_count')";

    if ($mysqli->query($sql) === TRUE) {
        // Успешное добавление
        header("Location: /store.php/$store_id");
        exit();
    } else {
        // Обработка ошибки добавления
        echo "Error: " . $sql . "<br>" . $mysqli->error;
    }
}
?>
