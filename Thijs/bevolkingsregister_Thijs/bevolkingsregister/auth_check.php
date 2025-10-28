<?php
// auth_check.php
session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo "🔒 Geen toegang. Log eerst in.";
    exit;
}
