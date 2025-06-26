<?php include 'db_connect.php'; ?>

<!DOCTYPE html>
<html>

<head>
    <title>CAN-LOG Database</title>
</head>

<body>
    <h1>Data from MySQL</h1>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Can Bus</th>
            <th>Can ID</th>
            <th>B1</th>
            <th>B2</th>
            <th>B3</th>
            <th>B4</th>
            <th>B5</th>
            <th>B6</th>
            <th>B7</th>
            <th>B8</th>
            <th>PGN</th>
            <th>PGN Name</th>
        </tr>
        <?php
        $stmt = $pdo->query("SELECT * FROM valtra_t214v LIMIT 5");
        while ($row = $stmt->fetch()) {
            echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['can_bus']}</td>
                <td>{$row['can_id']}</td>
                <td>{$row['b1']}</td>
                <td>{$row['b2']}</td>
                <td>{$row['b3']}</td>
                <td>{$row['b4']}</td>
                <td>{$row['b5']}</td>
                <td>{$row['b6']}</td>
                <td>{$row['b7']}</td>
                <td>{$row['b8']}</td>
                <td>{$row['pgn']}</td>
                <td>{$row['pgn_name']}</td>
            </tr>";
        }
        ?>
    </table>
</body>
</html>
