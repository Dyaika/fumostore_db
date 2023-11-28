<?php
// Подключение к базе данных
$mysqli = new mysqli("mysql", "user", "1111", "fumo_plush_store");

// Проверка подключения
if ($mysqli->connect_error) {
    die("Ошибка подключения: " . $mysqli->connect_error);
}

// Обработка данных из формы
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Защита от SQL-инъекций (используйте подготовленные запросы в продакшене)
    $item_name = $mysqli->real_escape_string($_POST["item_name"]);
    $item_cost = $mysqli->real_escape_string($_POST["item_cost"]);
    $item_description = $mysqli->real_escape_string($_POST["item_description"]);
    $item_release_year = $mysqli->real_escape_string($_POST["item_release_year"]);
    $item_width = $mysqli->real_escape_string($_POST["item_width"]);
    $item_height = $mysqli->real_escape_string($_POST["item_height"]);
    $image_url = $mysqli->real_escape_string($_POST["image_url"]);

    // SQL-запрос для вставки новой записи в таблицу item
    $sql = "INSERT INTO item (item_name, item_cost, item_description, item_release_year, item_width, item_height, image_url)
            VALUES ('$item_name', '$item_cost', '$item_description', '$item_release_year', '$item_width', '$item_height', '$image_url')";

    // Выполнение запроса
    if ($mysqli->query($sql) === TRUE) {
        // Перенаправляем обратно на страницу
        header("Location: /items.php");
    } else {
        echo "Ошибка: " . $sql . "<br>" . $mysqli->error;
    }

    // Закрытие соединения с базой данных
    $mysqli->close();
    exit;
}
?>