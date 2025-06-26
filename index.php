<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db_connect.php';

echo "Connected successfully.<br>";

$canIds = $pdo->query("SELECT DISTINCT can_id FROM valtra_t214v")->fetchAll(PDO::FETCH_COLUMN);
$pgnNames = $pdo->query("SELECT DISTINCT pgn_name FROM valtra_t214v")->fetchAll(PDO::FETCH_COLUMN);

echo "Can IDs: " . implode(', ', $canIds) . "<br>";
echo "PGN Names: " . implode(', ', $pgnNames) . "<br>";
?>
