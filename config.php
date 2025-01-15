<?php

define('DB_SERVER', $_ENV['AZURE_MYSQL_HOST']);
define('DB_USERNAME', $_ENV['AZURE_MYSQL_USERNAME']);
define('DB_PASSWORD', $_ENV['AZURE_MYSQL_PASSWORD']);
define('DB_NAME', $_ENV['AZURE_MYSQL_DBNAME']);

try 
{
    $dsn = "mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME;
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false, 
    ];
    $pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD, $options);
    echo "Connected successfully to the database!";
}
catch (PDOException $e) 
{
    die("ERROR: Could not connect. " . $e->getMessage());
}
?>
