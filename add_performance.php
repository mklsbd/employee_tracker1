<?php
include 'db_config.php';
include 'calculate_metrics.php';
checkLogin();
if($_SESSION['role']!='admin') header("Location: login.php");

$employees=$conn->query("SELECT * FROM employees");

if($_SERVER['REQUEST_METHOD']=='POST'){
    $emp_id=$_POST['employee_id'];
    $month=$_POST['month'];
    $score=$_POST['score'];
    $conn->query("INSERT INTO performance (employee_id,month,score) VALUES ($emp_id,'$month',$score)");
    $success="Performance added!";
}
?>
<!DOCTYPE html>
<html>
<head><title>Add Performance</title></head>
<body>
<h2>Add Performance</h2>
<?php if(isset($success)) echo "<p style='color:green;'>$success</p>"; ?>
<form method="POST">
<select name="employee_id" required>
<option value="">Select Employee</option>
<?php while($e=$employees->fetch_assoc()){ echo "<option value='{$e['id']}'>{$e['fullname']}</option>"; } ?>
</select><br>
<input type="text" name="month" placeholder="YYYY-MM" required><br>
<input type="number" name="score" placeholder="Score" required><br>
<button type="submit">Add Performance</button>
</form>
<a href="dashboard.php">Back</a>
</body>
</html>
