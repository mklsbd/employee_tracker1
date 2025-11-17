<?php
include 'db_config.php';
include 'calculate_metrics.php';
checkLogin();
if($_SESSION['role']!='admin') exit;

$employeeResult = $conn->query("SELECT * FROM employees");
$data = [];

while($emp=$employeeResult->fetch_assoc()){
    $metrics=getEmployeeMetrics($emp['id']);
    $lowClass = ($metrics['attendance_pct']<80 || $metrics['performance_score']<80)?'low-performance':'';
    $data[] = [
        'fullname'=>$emp['fullname'],
        'department'=>$emp['department'],
        'attendance_pct'=>$metrics['attendance_pct'],
        'performance_score'=>$metrics['performance_score'],
        'regular_hours'=>$metrics['regular_hours'] ?? 0,
        'overtime_hours'=>$metrics['overtime_hours'] ?? 0,
        'lowClass'=>$lowClass
    ];
}

echo json_encode($data);
