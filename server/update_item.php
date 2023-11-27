<?php
$mysqli = new mysqli("mysql", "user", "1111", "fumo_plush_store");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получаем данные из формы
    $item_id = $_POST["item_id"];
    $item_name = $_POST["item_name"];
    $item_cost = $_POST["item_cost"];
    $item_description = $_POST["item_description"];
    $item_release_year = $_POST["item_release_year"];
    $item_width = $_POST["item_width"];
    $item_height = $_POST["item_height"];

    // Подготавливаем SQL-запрос
    $sql = "UPDATE item SET
            item_name = '$item_name',
            item_cost = '$item_cost',
            item_description = '$item_description',
            item_release_year = '$item_release_year',
            item_width = '$item_width',
            item_height = '$item_height'
            WHERE item_id = $item_id";

    // Выполняем запрос
    if ($mysqli->query($sql) === TRUE) {
        // Перенаправляем пользователя на страницу информации об игрушке
        header("Location: /item.php/$item_id");
        exit();
    } else {
        // Выводим сообщение об ошибке
        echo "Ошибка при обновлении записи: " . $mysqli->error;
    }

    // Закрываем соединение с базой данных
    $mysqli->close();
} else {
    // Если запрос не является POST-запросом, перенаправляем пользователя на главную страницу
    header("Location: /index.php");
    exit();
}
?>
