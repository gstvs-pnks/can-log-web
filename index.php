<?php
include 'db_connect.php';

// Get filter values from GET params, default to empty string (no filter)
$filter_can_id = isset($_GET['filter_can_id']) ? $_GET['filter_can_id'] : '';
$filter_pgn_name = isset($_GET['filter_pgn_name']) ? $_GET['filter_pgn_name'] : '';

// Prepare base query with optional WHERE conditions
$sql = "SELECT * FROM valtra_t214v WHERE 1=1 ";
$params = [];

if ($filter_can_id !== '' && $filter_can_id !== 'all') {
    $sql .= " AND can_id = ? ";
    $params[] = $filter_can_id;
}

if ($filter_pgn_name !== '' && $filter_pgn_name !== 'all') {
    $sql .= " AND pgn_name = ? ";
    $params[] = $filter_pgn_name;
}

$sql .= " LIMIT 5";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

// Fetch distinct can_id and pgn_name values for dropdowns
$canIds = $pdo->query("SELECT DISTINCT can_id FROM valtra_t214v")->fetchAll(PDO::FETCH_COLUMN);
$pgnNames = $pdo->query("SELECT DISTINCT pgn_name FROM valtra_t214v")->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CAN-LOG Database</title>
    <style>
        body {
            font-family: 'Segoe UI', 'Roboto', sans-serif;
            background-color: #f4f7fa;
            margin: 20px;
            color: #333;
        }
        h1 {
            text-align: center;
            color: #2c3e50;
        }
        table {
            width: 90%;
            margin: 0 auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }
        th, td {
            padding: 12px 15px;
            text-align: center;
            border-bottom: 1px solid #e0e0e0;
            vertical-align: middle;
        }
        th {
            background-color: #2980b9;
            color: white;
            position: relative;
        }
        tr:hover {
            background-color: #f1f9ff;
        }
        select {
            margin-top: 5px;
            width: 90%;
            padding: 2px 4px;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <h1>CAN-LOG Database Records</h1>
    
    <form method="GET" id="filterForm">
        <table>
            <tr>
                <th>ID</th>
                <th>Can Bus</th>
                <th>
                    Can ID<br>
                    <select name="filter_can_id" onchange="document.getElementById('filterForm').submit()">
                        <option value="all">All</option>
                        <?php foreach ($canIds as $canIdOption): ?>
                            <option value="<?= htmlspecialchars($canIdOption) ?>" <?= ($filter_can_id === $canIdOption) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($canIdOption) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </th>
                <th>B1</th>
                <th>B2</th>
                <th>B3</th>
                <th>B4</th>
                <th>B5</th>
                <th>B6</th>
                <th>B7</th>
                <th>B8</th>
                <th>PGN</th>
                <th>
                    PGN Name<br>
                    <select name="filter_pgn_name" onchange="document.getElementById('filterForm').submit()">
                        <option value="all">All</option>
                        <?php foreach ($pgnNames as $pgnNameOption): ?>
                            <option value="<?= htmlspecialchars($pgnNameOption) ?>" <?= ($filter_pgn_name === $pgnNameOption) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($pgnNameOption) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </th>
            </tr>
            <?php while ($row = $stmt->fetch()): ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['can_bus']) ?></td>
                <td><?= htmlspecialchars($row['can_id']) ?></td>
                <td><?= htmlspecialchars($row['b1']) ?></td>
                <td><?= htmlspecialchars($row['b2']) ?></td>
                <td><?= htmlspecialchars($row['b3']) ?></td>
                <td><?= htmlspecialchars($row['b4']) ?></td>
                <td><?= htmlspecialchars($row['b5']) ?></td>
                <td><?= htmlspecialchars($row['b6']) ?></td>
                <td><?= htmlspecialchars($row['b7']) ?></td>
                <td><?= htmlspecialchars($row['b8']) ?></td>
                <td><?= htmlspecialchars($row['pgn']) ?></td>
                <td><?= htmlspecialchars($row['pgn_name']) ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </form>
</body>
</html>
