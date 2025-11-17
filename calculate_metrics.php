<?php
include 'db_config.php';

function checkLogin(){
    session_start();
    if(!isset($_SESSION['user_id'])){
        header("Location: login.php");
        exit;
    }
}

function getEmployeeMetrics($emp_id){
    global $conn;

    // Total regular hours and overtime hours
    $att = $conn->query("SELECT SUM(regular_hours) as reg_hours, SUM(overtime_hours) as ot_hours, COUNT(id) as total_days 
                         FROM attendance WHERE employee_id=$emp_id");
    $att_res = $att->fetch_assoc();
    $total_days = $att_res['total_days'] ?: 0;
    $reg_hours = $att_res['reg_hours'] ?: 0;
    $ot_hours = $att_res['ot_hours'] ?: 0;

    // Attendance %
    $attendance_pct = min(100, ($total_days/22)*100);

    // Performance score
    $perf = $conn->query("SELECT AVG(score) as avg_score FROM performance WHERE employee_id=$emp_id");
    $score = $perf->fetch_assoc()['avg_score'] ?: 0;

    return [
        'attendance_pct' => round($attendance_pct,2),
        'performance_score' => round($score,2),
        'regular_hours' => round($reg_hours,2),
        'overtime_hours' => round($ot_hours,2)
    ];
}
?>
