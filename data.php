<?php
include 'db_connect.php';

//filter the can_id and pgn_name fields
$filter_can_id = isset($_GET['filter_can_id']) ? $_GET['filter_can_id'] : '';
$filter_pgn_name = isset($_GET['filter_pgn_name']) ? $_GET['filter_pgn_name'] : '';

//build query
$sql = "SELECT * FROM valtra_t214v WHERE 1=1";
$params = [];

if ($filter_can_id !== '' && $filter_can_id !== 'all') {
    $sql .= " AND can_id = ?";
    $params[] = $filter_can_id;
}

if ($filter_pgn_name !== '' && $filter_pgn_name !== 'all') {
    $sql .= " AND pgn_name = ?";
    $params[] = $filter_pgn_name;
}

$sql .= " LIMIT 10";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);


$canIds = $pdo->query("SELECT DISTINCT can_id FROM valtra_t214v")->fetchAll(PDO::FETCH_COLUMN);
$pgnNames = $pdo->query("SELECT DISTINCT pgn_name FROM valtra_t214v")->fetchAll(PDO::FETCH_COLUMN);

?>