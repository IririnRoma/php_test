<?php
$mysqli = new mysqli('localhost', 'username', 'password', 'database_name');

if ($mysqli->connect_error) {
    die('Connection Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

$queries = [
    "DELETE FROM `categories` WHERE `id` NOT IN (SELECT DISTINCT `category_id` FROM `products`);",
    "DELETE FROM `products` WHERE `id` NOT IN (SELECT DISTINCT `product_id` FROM `availabilities`);",
    "DELETE FROM `stocks` WHERE `id` NOT IN (SELECT DISTINCT `stock_id` FROM `availabilities`);"
];

foreach ($queries as $query) {
    if ($mysqli->query($query) === TRUE) {
        echo "Query executed successfully: $query\n";
    } else {
        echo "Error: " . $mysqli->error . "\n";
    }
}

$mysqli->close();
?>
