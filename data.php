<?php
include 'db_connect.php';

// ✅ Fetch all table names
$tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);

// ✅ Parse makes and models
$makes = [];
$modelsPerMake = [];

foreach ($tables as $table) {
    if (preg_match('/^([a-zA-Z]+)_([a-zA-Z0-9]+)$/', $table, $matches)) {
        $make = strtolower($matches[1]);
        $model = strtolower($matches[2]);

        $makes[$make] = $make;
        $modelsPerMake[$make][] = $model;
    }
}

// ✅ Get selected make/model (default to first available)
$selected_make = isset($_GET['make']) ? strtolower($_GET['make']) : array_key_first($makes);
$available_models = $modelsPerMake[$selected_make] ?? [];
$selected_model = isset($_GET['model']) ? strtolower($_GET['model']) : ($available_models[0] ?? null);

// ✅ Verify table exists
$selected_table = "{$selected_make}_{$selected_model}";

if (!in_array($selected_table, $tables)) {
    die("Invalid table selection.");
}

// ✅ Handle filters
$filter_can_id = $_GET['filter_can_id'] ?? '';
$filter_pgn_name = $_GET['filter_pgn_name'] ?? '';

// ✅ Pagination
$page = max(1, intval($_GET['page'] ?? 1));
$limit = 10;
$offset = ($page - 1) * $limit;

// ✅ Query data
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

$sql .= " LIMIT $limit OFFSET
