<?php
$mysqli = new mysqli("mysql", "user", "1111", "fumo_plush_store");

$item_id = $_GET['item_id'] ?? '';
$store_id = $_GET['store_id'] ?? '';

if (!empty($item_id) && !empty($store_id)) {
    // Выполните запрос на удаление связи между игрушкой и магазином
    $result = $mysqli->query("DELETE FROM itemstore_association WHERE item_id = $item_id AND store_id = $store_id");

    if ($result) {
        echo "Игрушка успешно удалена из магазина.";
    } else {
        echo "Ошибка при удалении игрушки из магазина: " . $mysqli->error;
    }
} else {
    echo "Не удалось получить данные для удаления.";
}

$mysqli->close();
?>
