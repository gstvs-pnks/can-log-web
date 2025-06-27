<?php
include 'db_connect.php';

// ✅ Get all table names
$tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);

// ✅ Parse makes and models from table names
$makes = [];
$models = [];
$tableMap = [];

foreach ($tables as $table) {
    if (preg_match('/^([a-zA-Z]+)_([a-zA-Z0-9]+)$/', $table, $matches)) {
        $make = strtolower($matches[1]);
        $model = strtolower($matches[2]);

        $makes[$make] = $make;
        $models[$model] = $model;
        $tableMap["{$make}_{$model}"] = $table;
    }
}

// ✅ Get selected make and model (with fallback)
$selected_make = isset($_GET['make']) ? strtolower($_GET['make']) : array_key_first($makes);
$selected_model = isset($_GET['model']) ? strtolower($_GET['model']) : array_key_first($models);

// ✅ Build table name
$selected_table = "{$selected_make}_{$selected_model}";

if (!in_array($selected_table, $tables)) {
    die("Invalid table selection.");
}

// ✅ Filters for columns
$filter_can_id = $_GET['filter_can_id'] ?? '';
$filter_pgn_name = $_GET['filter_pgn_name'] ?? '';

// ✅ Main data query
$sql = "SELECT * FROM `$selected_table` WHERE 1=1";
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

// ✅ Populate dropdowns for can_id and pgn_name
$canIds = $pdo->query("SELECT DISTINCT can_id FROM `$selected_table`")->fetchAll(PDO::FETCH_COLUMN);
$pgnNames = $pdo->query("SELECT DISTINCT pgn_name FROM `$selected_table`")->fetchAll(PDO::FETCH_COLUMN);
?>
