<?php
include 'db_config.php';
include 'calculate_metrics.php';
checkLogin();
if($_SESSION['role']!='admin') exit;

$department = $_GET['department'] ?? '';
$month = $_GET['month'] ?? '';

$labels = [];
$trendData = [];

for($i=5;$i>=0;$i--){
    $m = date('Y-m', strtotime("-$i month"));
    $labels[] = date('M Y', strtotime($m.'-01'));

    // Get all employees (or filter by department)
    $empQuery = "SELECT id FROM employees";
    if($department) $empQuery .= " WHERE department='$department'";
    $res = $conn->query($empQuery);

    $total_pct = 0;
    $count = 0;
    while($row = $res->fetch_assoc()){
        $emp_id = $row['id'];

        // If a month filter is applied, only include that month
        $filterMonth = $month ?: $m;

        $att_res = $conn->query("
            SELECT COUNT(id) as days_present 
            FROM attendance 
            WHERE employee_id=$emp_id AND DATE_FORMAT(date,'%Y-%m')='$filterMonth'
        ")->fetch_assoc();

        $days_present = $att_res['days_present'] ?: 0;
        $pct = min(100, ($days_present/22)*100);
        $total_pct += $pct;
        $count++;
    }
    $trendData[] = $count>0 ? round($total_pct/$count,2) : 0;
}

echo json_encode([
    'labels'=>$labels,
    'data'=>$trendData
]);
