<?php
// api/burgers.php
header('Content-Type: application/json; charset=utf-8');

session_start();
if (!isset($_SESSION['user_id'])) {
  http_response_code(403);
  echo json_encode(['success'=>false,'error'=>'Niet ingelogd']);
  exit;
}

require __DIR__ . '/db.php';

$method = $_SERVER['REQUEST_METHOD'];
$id = $_GET['id'] ?? null; // Burger_id

try {
  if ($method === 'GET') {
    if (!$id) {
      http_response_code(400);
      echo json_encode(['success'=>false,'error'=>'Geen Burger_id meegegeven']);
      exit;
    }

    $stmt = $pdo->prepare("
      SELECT Burger_id, Voornaam, Achternaam, Geboortedatum, DNA_code, Familie_code
      FROM burgers
      WHERE Burger_id = ?
    ");
    $stmt->execute([$id]);
    $row = $stmt->fetch();

    if (!$row) {
      echo json_encode(null);
      exit;
    }

    echo json_encode($row);
    exit;
  }

  http_response_code(405);
  echo json_encode(['success'=>false,'error'=>'Method not allowed']);
  exit;

} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode([
    'success'=>false,
    'error'=>'Serverfout bij ophalen',
    'details'=>$e->getMessage()
  ]);
  exit;
}
