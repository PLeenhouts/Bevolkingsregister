<?php
// login.php
session_start();
require __DIR__ . '/api/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$email = $_POST['email'] ?? '';
$wachtwoord = $_POST['wachtwoord'] ?? '';

// Zoek user
$stmt = $pdo->prepare('SELECT id, email, wachtwoord_hash, naam, rol FROM users WHERE email = ? LIMIT 1');
$stmt->execute([$email]);
$gebruiker = $stmt->fetch();

// Check bestaan + wachtwoord
if (!$gebruiker || !password_verify($wachtwoord, $gebruiker['wachtwoord_hash'])) {
    // mislukte login → gewoon terug naar index zonder sessie
    header('Location: index.php');
    exit;
}

// Login gelukt → sessie vullen
$_SESSION['user_id']    = $gebruiker['id'];
$_SESSION['user_email'] = $gebruiker['email'];
$_SESSION['user_naam']  = $gebruiker['naam'];
$_SESSION['user_rol']   = $gebruiker['rol'];

header('Location: index.php');
exit;
