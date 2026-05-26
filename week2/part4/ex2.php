<?php
$students = [
    ['name' => 'Long', 'grade' => 95],
    ['name' => 'Giang', 'grade' => 88],
    ['name' => 'An', 'grade' => 75]
];

echo "<table border='1'>";
echo "<tr><th>Name</th><th>Grade</th></tr>";

foreach ($students as $student) {
    echo "<tr>";
    echo "<td>" . $student['name'] . "</td>";
    echo "<td>" . $student['grade'] . "</td>";
    echo "</tr>";
}

echo "</table>";
?>