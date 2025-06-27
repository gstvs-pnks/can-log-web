<?php include 'data.php'; ?>

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
        .filter-container {
            width: 90%;
            margin: 0 auto 20px auto;
            background-color: #2980b9;
            padding: 15px;
            border-radius: 8px;
            color: white;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            justify-content: center;
        }
        .filter-box {
            display: flex;
            flex-direction: column;
            min-width: 150px;
        }
        .filter-box label {
            margin-bottom: 5px;
            font-weight: bold;
        }
        .filter-box select {
            padding: 5px;
            font-size: 1em;
        }
        .filter-actions {
            display: flex;
            align-items: flex-end;
            gap: 10px;
        }
        .filter-actions button {
            padding: 6px 12px;
            background-color: white;
            border: none;
            border-radius: 4px;
            color: #2980b9;
            cursor: pointer;
            font-weight: bold;
        }
        .filter-actions button:hover {
            background-color: #ecf0f1;
        }
        table {
            width: 90%;
            margin: 0 auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }
        th, td {
            padding: 10px 12px;
            text-align: center;
            border-bottom: 1px solid #e0e0e0;
        }
        th {
            background-color: #2980b9;
            color: white;
        }
        tr:hover {
            background-color: #f1f9ff;
        }
        .pagination {
            width: 90%;
            margin: 15px auto;
            text-align: center;
        }
        .pagination button {
            padding: 6px 12px;
            margin: 0 5px;
            background-color: #2980b9;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .pagination button:disabled {
            background-color: #bdc3c7;
            cursor: not-allowed;
        }
    </style>
    <script>
        function resetFilters() {
            const form = document.getElementById('filterForm');
            form.reset();
            window.location = window.location.pathname;
        }
        function updateModelDropdown() {
            const make = document.getElementById('make').value;
            const models = <?= json_encode($modelsPerMake) ?>;
            const modelSelect = document.getElementById('model');
            modelSelect.innerHTML = "";

            if (models[make]) {
                models[make].forEach(model => {
                    const option = document.createElement("option");
                    option.value = model;
                    option.text = model.toUpperCase();
