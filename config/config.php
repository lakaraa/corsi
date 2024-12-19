<?php

$db_host = '127.0.0.1';
$db_dbname = 'corsi';
$db_username = 'corsi';
$db_password = 'password.123';


$pdo = new PDO("mysql:host=$db_host;dbname=$db_dbname", $db_username, $db_password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
