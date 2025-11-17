<?php
include 'db_config.php';
include 'calculate_metrics.php'; // Required for checkLogin()

checkLogin();
if($_SESSION['role'] != 'employee') exit;

$uid = $_SESSION['user_id'];
$emp = $conn->query("SELECT id FROM employees WHERE user_id=$uid")->fetch_assoc();
$eid = $emp['id'];

$date = $_POST['date'];
$time_in = !empty($_POST['time_in']) ? $_POST['time_in'] : null;
$time_out = !empty($_POST['time_out']) ? $_POST['time_out'] : null;

// Prevent future date
if ($date > date('Y-m-d')) {
    die("Date cannot be in the future.");
}

// Prevent future time only if date is today
if ($date == date('Y-m-d')) {
    $current_time = date('H:i');
    if ($time_in && $time_in > $current_time) {
        die("Time In cannot be in the future.");
    }
    if ($time_out && $time_out > $current_time) {
        die("Time Out cannot be in the future.");
    }
}

// Calculate regular hours and overtime if both times are provided
$regular_hours = $overtime_hours = 0;
if ($time_in && $time_out) {
    $start = strtotime($time_in);
    $end = strtotime($time_out);
    $hours = ($end - $start) / 3600;
    $regular_hours = max(min($hours, 8), 0);
    $overtime_hours = max($hours - 8, 0);
}

// Check if attendance for this date already exists
$check = $conn->query("SELECT id FROM attendance WHERE employee_id=$eid AND date='$date'")->fetch_assoc();

if ($check) {
    // Update existing record
    $stmt = $conn->prepare("UPDATE attendance SET time_in=?, time_out=?, regular_hours=?, overtime_hours=? WHERE id=?");
    $stmt->bind_param("ssddi", $time_in, $time_out, $regular_hours, $overtime_hours, $check['id']);
} else {
    // Insert new record
    $stmt = $conn->prepare("INSERT INTO attendance (employee_id, date, time_in, time_out, regular_hours, overtime_hours) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssdd", $eid, $date, $time_in, $time_out, $regular_hours, $overtime_hours);
}

$stmt->execute();
$stmt->close();

// Redirect back to employee dashboard
header("Location: home.php");
exit;
?>
