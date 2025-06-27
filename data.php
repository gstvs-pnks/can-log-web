<?php
include 'db_connect.php';

// Get all table names
$sql = "SHOW TABLES";
$tables = $pdo->query($sql)->fetchAll(PDO::FETCH_COLUMN);

// Parse makes and models from table names like valtra_t214v
$makes = [];
$modelsPerMake = [];
foreach ($tables as $table) {
    if (preg_match('/^([a-zA-Z]+)_([a-zA-Z0-9]+)$/', $table, $matches)) {
        $make = $matches[1];
        $model = $matches[2];
        $makes[$make] = true;
        $modelsPerMake[$make][] = $model;
    }
}
$makes = array_keys($makes);
sort($makes);

// Get selected make and model from GET, or use defaults
$selected_make = isset($_GET['make']) ? strtolower($_GET['make']) : (count($makes) > 0 ? $makes[0] : '');
$selected_model = isset($_GET['model']) ? strtolower($_GET['model']) : (
    isset($modelsPerMake[$selected_make]) ? $modelsPerMake[$selected_make][0] : ''
);

// Compose table name
$table_name = $selected_make . '_' . $selected_model;

// Validate that table exists
if (!in_array($table_name, $tables)) {
    $table_name = count($tables) > 0 ? $tables[0] : '';
}

// Filters for can_id and pgn_name
$filter_can_id = isset($_GET['filter_can_id']) ? $_GET['filter_can_id'] : '';
$filter_pgn_name = isset($_GET['filter_pgn_name']) ? $_GET['filter_pgn_name'] : '';

// Build SQL query with filters
$sql = "SELECT * FROM `$table_name` WHERE 1=1";
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

// Get distinct can_id and pgn_name for dropdowns from current table
$canIds = $pdo->query("SELECT DISTINCT can_id FROM `$table_name`")->fetchAll(PDO::FETCH_COLUMN);
$pgnNames = $pdo->query("SELECT DISTINCT pgn_name FROM `$table_name`")->fetchAll(PDO::FETCH_COLUMN);
?>
