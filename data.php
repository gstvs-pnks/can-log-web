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
        // Preserve case exactly as in table name
        $make = $matches[1];
        $model = $matches[2];
        $makes[$make] = true;
        $modelsPerMake[$make][] = $model;
    }
}
$makes = array_keys($makes);
sort($makes);

// Get selected make and model from GET, or use defaults
// Use original case from available makes/models, but match ignoring case from GET params
$selected_make = '';
$selected_model = '';

// Normalize GET params to lowercase for matching
$get_make = isset($_GET['make']) ? strtolower($_GET['make']) : '';
$get_model = isset($_GET['model']) ? strtolower($_GET['model']) : '';

// Find make matching GET param case-insensitively, or default to first
foreach ($makes as $make) {
    if (strtolower($make) === $get_make) {
        $selected_make = $make;
        break;
    }
}
if ($selected_make === '') {
    $selected_make = count($makes) > 0 ? $makes[0] : '';
}

// Find model matching GET param case-insensitively, or default to first model of selected make
if (isset($modelsPerMake[$selected_make])) {
    foreach ($modelsPerMake[$selected_make] as $model) {
        if (strtolower($model) === $get_model) {
            $selected_model = $model;
            break;
        }
    }
    if ($selected_model === '') {
        $selected_model = $modelsPerMake[$selected_make][0];
    }
} else {
    $selected_model = '';
}

// Compose table name preserving case
$table_name = $selected_make . '_' . $selected_model;

// Validate that table exists (case-insensitive)
$found_table = null;
foreach ($tables as $tbl) {
    if (strcasecmp($tbl, $table_name) === 0) {
        $found_table = $tbl;
        break;
    }
}
if ($found_table === null) {
    $found_table = count($tables) > 0 ? $tables[0] : '';
}
$table_name = $found_table;

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
