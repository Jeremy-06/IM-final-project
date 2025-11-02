<?php
require_once 'src/config/Config.php';

$conn = mysqli_connect(Config::DB_HOST, Config::DB_USER, Config::DB_PASS, Config::DB_NAME);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "Order 28 details:\n";
$result = mysqli_query($conn, 'SELECT * FROM orders WHERE id = 28');
$order = mysqli_fetch_assoc($result);
print_r($order);

echo "\nOrder 28 items:\n";
$result2 = mysqli_query($conn, 'SELECT * FROM order_items WHERE order_id = 28');
$items = mysqli_fetch_all($result2, MYSQLI_ASSOC);
print_r($items);

echo "\nOrder history for order 28:\n";
$result3 = mysqli_query($conn, 'SELECT * FROM order_history WHERE order_id = 28');
$history = mysqli_fetch_assoc($result3);
print_r($history);

mysqli_close($conn);
?>