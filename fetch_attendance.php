<?php
include 'db_config.php';
include 'calculate_metrics.php';
checkLogin();
if($_SESSION['role']!='admin') exit;

$month = $_GET['month'] ?? '';
$department = $_GET['department'] ?? '';

$sql = "SELECT * FROM employees WHERE 1";
if($department) $sql .= " AND department='$department'";
$res = $conn->query($sql);

$data = [];
while($emp = $res->fetch_assoc()){
    $metrics = getEmployeeMetrics($emp['id'], $month); // month param used
    $data[] = [
        'fullname'=>$emp['fullname'],
        'department'=>$emp['department'],
        'attendance_pct'=>$metrics['attendance_pct'],
        'regular_hours'=>$metrics['regular_hours'],
        'overtime_hours'=>$metrics['overtime_hours']
    ];
}

header('Content-Type: application/json');
echo json_encode($data);
