<?php

function connectToDatabase()
{
    $host = getenv('DB_HOST');
    $db = getenv('DB_NAME');
    $username = getenv('DB_USER');
    $dbPassword = getenv('DB_PASSWORD');
    $connection = new mysqli($host, $username, $dbPassword, $db);
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }
    return $connection;
}
