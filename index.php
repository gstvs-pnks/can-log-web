<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'data.php';

// Mapping from make keys to friendly display names
$makeDisplayNames = [
    'johnDeere' => 'John Deere',
    'valtra' => 'Valtra',
];

// Function to get friendly make name
function makeDisplayName($make, $mapping) {
    if (isset($mapping[$make])) {
        return $mapping[$make];
    }
    // Fallback: insert space before uppercase letters and ucfirst
    $readable = preg_replace('/([a-z])([A-Z])/', '$1 $2', $make);
    return ucfirst($readable);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
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
        .filters-container {
            width: 90%;
            margin: 0 auto 20px auto;
            background-color: #2980b9;
            padding: 10px;
            border-radius: 4px;
            color: white;
            max-width: 700px;
        }
        .filters-container label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
        }
        .filters-container select {
            width: 100%;
            padding: 6px;
            font-size: 1em;
            border-radius: 3px;
            border: none;
            margin-bottom: 12px;
        }
        table {
            width: 90%;
            max-width: 1000px;
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
        }
        tr:hover {
            background-color: #f1f9ff;
        }
    </style>
</head>
<body>
    <h1>CAN-LOG Database Records</h1>

    <form method="GET" id="filterForm">

        <div class="filters-container">
            <label for="makeSelect">Choose make:</label>
            <select id="makeSelect" name="make" onchange="document.getElementById('filterForm').submit()">
                <?php foreach ($makes as $makeOption): ?>
                    <option value="<?= htmlspecialchars($makeOption) ?>" <?= ($selected_make === $makeOption) ? 'selected' : '' ?>>
                        <?= htmlspecialchars(makeDisplayName($makeOption, $makeDisplayNames)) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="modelSelect">Choose model:</label>
            <select id="modelSelect" name="model" onchange="document.getElementById('filterForm').submit()">
                <?php
                if (isset($modelsPerMake[$selected_make])):
                    foreach ($modelsPerMake[$selected_make] as $modelOption):
                ?>
                    <option value="<?= htmlspecialchars($modelOption) ?>" <?= ($selected_model === $modelOption) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($modelOption) ?>
                    </option>
                <?php
                    endforeach;
                endif;
                ?>
            </select>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Can Bus</th>
                    <th>
                        Can ID<br />
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
                        PGN Name<br />
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
            </thead>
            <tbody>
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
            </tbody>
        </table>
    </form>

</body>
</html>
