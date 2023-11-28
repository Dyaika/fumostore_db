<?php
$mysqli = new mysqli("mysql", "user", "1111", "fumo_plush_store");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_id = $_POST['item_id'];
    $store_id = $_POST['store_id'];
    $item_count = $_POST['item_count'];

    // Обновляем количество в itemstore_association
    $mysqli->query("UPDATE itemstore_association SET item_count = $item_count WHERE item_id = $item_id AND store_id = $store_id");
}

// Перенаправляем обратно на страницу
header("Location: /store.php/{$store_id}");
exit;
?>
