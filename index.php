<?php include 'data.php'; ?>

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
            margin: 5px;
            padding: 5px;
            font-size: 1em;
        }
    </style>
</head>
<body>

    <h1>CAN-LOG Database Records</h1>

    <!-- 🔥 Make & Model Dropdowns -->
    <form method="GET" id="filterForm">
        <label for="make">Choose Make:</label>
        <select name="make" id="make" onchange="document.getElementById('filterForm').submit()">
            <?php foreach ($makes as $make): ?>
                <option value="<?= htmlspecialchars($make) ?>" <?= ($selected_make === $make) ? 'selected' : '' ?>>
                    <?= ucfirst($make) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="model">Choose Model:</label>
        <select name="model" id="model" onchange="document.getElementById('filterForm').submit()">
            <?php foreach ($models as $model): ?>
                <option value="<?= htmlspecialchars($model) ?>" <?= ($selected_model === $model) ? 'selected' : '' ?>>
                    <?= strtoupper($model) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Existing CAN ID and PGN Name filters -->
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
