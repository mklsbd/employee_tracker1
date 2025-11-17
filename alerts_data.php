<?php
include 'db_config.php';
include 'calculate_metrics.php';
checkLogin();
if($_SESSION['role']!='admin') exit;

$alerts = $conn->query("
SELECT fullname FROM employees e
LEFT JOIN attendance a ON e.id=a.employee_id
GROUP BY e.id
HAVING (COUNT(a.id)/22*100)<80
");

$names = [];
while($row=$alerts->fetch_assoc()){
    $names[] = $row['fullname'];
}

echo json_encode($names);
