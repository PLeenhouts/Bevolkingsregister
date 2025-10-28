<?php
// api/save_burger.php
header('Content-Type: application/json; charset=utf-8');

session_start();
if (!isset($_SESSION['user_id'])) {
  http_response_code(403);
  echo json_encode(['success'=>false,'error'=>'Niet ingelogd']);
  exit;
}

require __DIR__ . '/db.php';

// lees JSON body
$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

if (!$data) {
  http_response_code(400);
  echo json_encode(['success'=>false,'error'=>'Geen payload ontvangen']);
  exit;
}

$mode          = $data['mode']          ?? null; // "edit" of "new"
$Burger_id     = $data['Burger_id']     ?? null;
$Voornaam      = $data['Voornaam']      ?? null;
$Achternaam    = $data['Achternaam']    ?? null;
$Geboortedatum = $data['Geboortedatum'] ?? null;
$DNA_code      = $data['DNA_code']      ?? null;
$Familie_code  = $data['Familie_code']  ?? null;

try {
  if ($mode === 'new') {
    // Nieuwe burger invoegen
    $stmt = $pdo->prepare("
      INSERT INTO burgers (Voornaam, Achternaam, Geboortedatum, DNA_code, Familie_code)
      VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([
      $Voornaam,
      $Achternaam,
      $Geboortedatum,
      $DNA_code,
      $Familie_code
    ]);

    $nieuweId = $pdo->lastInsertId();

    echo json_encode([
      'success'    => true,
      'action'     => 'insert',
      'nieuwe_id'  => $nieuweId,
      'debug'      => [
        'Voornaam'      => $Voornaam,
        'Achternaam'    => $Achternaam,
        'Geboortedatum' => $Geboortedatum,
        'DNA_code'      => $DNA_code,
        'Familie_code'  => $Familie_code
      ]
    ]);
    exit;
  }

  if ($mode === 'edit') {
    // Bestaande burger bijwerken
    if (!$Burger_id) {
      http_response_code(400);
      echo json_encode(['success'=>false,'error'=>'Burger_id ontbreekt voor edit']);
      exit;
    }

    $stmt = $pdo->prepare("
      UPDATE burgers
      SET Voornaam = ?,
          Achternaam = ?,
          Geboortedatum = ?,
          DNA_code = ?,
          Familie_code = ?
      WHERE Burger_id = ?
    ");
    $stmt->execute([
      $Voornaam,
      $Achternaam,
      $Geboortedatum,
      $DNA_code,
      $Familie_code,
      $Burger_id
    ]);

    $aantalRijen = $stmt->rowCount(); // hoeveel rijen MySQL zegt aangepast

    echo json_encode([
      'success'     => true,
      'action'      => 'update',
      'rowCount'    => $aantalRijen,
      'debug'       => [
        'Burger_id'     => $Burger_id,
        'Voornaam'      => $Voornaam,
        'Achternaam'    => $Achternaam,
        'Geboortedatum' => $Geboortedatum,
        'DNA_code'      => $DNA_code,
        'Familie_code'  => $Familie_code
      ]
    ]);
    exit;
  }

  http_response_code(400);
  echo json_encode(['success'=>false,'error'=>'Onbekende mode (verwacht "new" of "edit")']);
  exit;

} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode([
    'success'=>false,
    'error'=>'Serverfout bij opslaan',
    'details'=>$e->getMessage()
  ]);
  exit;
}
