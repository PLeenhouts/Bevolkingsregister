<?php
// api/db.php
$DB_HOST = '127.0.0.1';
$DB_NAME = 'bevolkingsregister'; // jouw database
$DB_USER = 'root';
$DB_PASS = ''; // bij XAMPP meestal leeg

$dsn = "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4";

$pdo = new PDO($dsn, $DB_USER, $DB_PASS, [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES => false,
]);
