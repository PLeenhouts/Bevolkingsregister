<?php
require __DIR__ . '/api/db.php';
$email = 'ik@springfield.gov';
$naam = 'DaBoss';
$rol = 'admin';
$wachtwoord_hash = password_hash('geheim123', PASSWORD_DEFAULT);

$stmt = $pdo->prepare('INSERT INTO users (email, wachtwoord_hash, naam, rol) VALUES (?, ?, ?, ?)');
$stmt->execute([$email, $wachtwoord_hash, $naam, $rol]);

echo "klaar";
