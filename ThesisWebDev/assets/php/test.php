<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Schedule</title>
    <style>
        table {
            width: 50%;
            border-collapse: collapse;
            margin: 20px auto;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<?php
require_once('generation.php');

// Generate class times
$classTimes = generateClassTimes('AM', 90, 10);

// Display class times in table
echo "<table>";
echo "<tr><th>Class Days</th><th>Class Time</th></tr>";
foreach ($classTimes as $classTime) {
    echo "<tr>";
    echo "<td>{$classTime[0]}</td>";
    echo "<td>{$classTime[1]}</td>";
    echo "</tr>";
}
echo "</table>";
?>

</body>
</html>
