<?php
include 'db_config.php';
include 'calculate_metrics.php';
checkLogin();
if($_SESSION['role']!='admin') header("Location: login.php");

$employees=$conn->query("SELECT * FROM employees");

if($_SERVER['REQUEST_METHOD']=='POST'){
    $emp_id=$_POST['employee_id'];
    $date=$_POST['date'];
    $time_in=$_POST['time_in'];
    $time_out=$_POST['time_out'];
    $hours=(strtotime($time_out)-strtotime($time_in))/3600;
    $regular=min(8,$hours);
    $overtime=max(0,$hours-8);
    $conn->query("INSERT INTO attendance (employee_id,date,time_in,time_out,regular_hours,overtime_hours)
    VALUES ($emp_id,'$date','$time_in','$time_out',$regular,$overtime)");
    $success="Attendance added!";
}
?>
<!DOCTYPE html>
<html>
<head><title>Add Attendance</title></head>
<body>
<h2>Add Attendance</h2>
<?php if(isset($success)) echo "<p style='color:green;'>$success</p>"; ?>
<form method="POST">
<select name="employee_id" required>
<option value="">Select Employee</option>
<?php while($e=$employees->fetch_assoc()){ echo "<option value='{$e['id']}'>{$e['fullname']}</option>"; } ?>
</select><br>
<input type="date" name="date" required><br>
<input type="time" name="time_in" required><br>
<input type="time" name="time_out" required><br>
<button type="submit">Add Attendance</button>
</form>
<a href="dashboard.php">Back</a>
</body>
</html>
