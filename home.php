<?php
include 'db_config.php';
include 'calculate_metrics.php';
checkLogin();
if($_SESSION['role'] != 'employee') header("Location: login.php");

$uid = $_SESSION['user_id'];
$emp = $conn->query("SELECT id, fullname, department FROM employees WHERE user_id=$uid")->fetch_assoc();
$eid = $emp['id'];

// Metrics for this employee
$metrics = getEmployeeMetrics($eid);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Employee Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
:root{
--bg-dark:#1e1d1c;--bg-panel:#272624;--bg-card:#343331;
--accent-teal:#7ed1c5;--accent-gold:#eac68e;--accent-orange:#d88954;
--text-light:#ffffff;--text-muted:#c5c1ba;
}
body{margin:0;background:var(--bg-dark);color:var(--text-light);font-family:"Poppins",sans-serif;display:flex;flex-direction:column;min-height:100vh;}
.sidebar{width:260px;background:var(--bg-panel);height:100vh;padding:25px;display:flex;flex-direction:column;box-shadow:4px 0 15px rgba(0,0,0,0.4);position:fixed;left:0;top:0;}
.sidebar .logo{width:100px;margin:0 auto 15px auto;display:block;border-radius:50%;}
.sidebar h2{text-align:center;color:var(--accent-teal);font-size:24px;margin-bottom:35px;}
.sidebar .nav-link{color:var(--text-light);font-size:15px;padding:12px 15px;border-radius:12px;margin-bottom:8px;transition:0.25s;}
.sidebar .nav-link:hover,.sidebar .nav-link.active{background:var(--accent-teal);color:#000;}
.main-content{margin-left:260px;padding:30px 40px;width:calc(100%-260px);flex:1;}
.card{background:var(--bg-card);border-radius:20px;padding:25px;box-shadow:0 6px 20px rgba(0,0,0,0.3);border:none;margin-bottom:25px;}
.card h5{color:var(--accent-gold);font-weight:600;}
.table{color:var(--text-light);}
.table th{background:var(--accent-teal);color:#000;border:none;}
.table td{background:var(--bg-card);border-color:#444;color:#ffffff;}
.btn-primary{background:var(--accent-teal);color:black;border:none;font-weight:600;border-radius:12px;padding:10px 16px;}
.btn-primary:hover{background:var(--accent-gold);}
.footer{background:var(--bg-panel);color:#ffffff;text-align:center;padding:15px;font-size:14px;width:100%;margin-top:auto;}
.form-control{background:var(--bg-panel);border:1px solid var(--accent-teal);color:#fff;border-radius:8px;padding:5px;}
label{color:var(--accent-gold);font-weight:600;}
.greeting{font-size:20px;margin-bottom:20px;color:var(--accent-teal);}
</style>
<script>
function validateTime(event) {
    const dateInput = document.getElementById('att-date').value;
    const timeInInput = document.getElementById('time-in').value;
    const timeOutInput = document.getElementById('time-out').value;

    const now = new Date();
    const selectedDate = new Date(dateInput + 'T00:00');
    if(selectedDate > now) {
        alert("Date cannot be in the future.");
        event.preventDefault();
        return false;
    }

    if(timeInInput) {
        const inTime = new Date(dateInput + 'T' + timeInInput);
        if(inTime > now) {
            alert("Time In cannot be in the future.");
            event.preventDefault();
            return false;
        }
    }

    if(timeOutInput) {
        const outTime = new Date(dateInput + 'T' + timeOutInput);
        if(outTime > now) {
            alert("Time Out cannot be in the future.");
            event.preventDefault();
            return false;
        }
    }

    return true;
}
</script>
</head>
<body>
<div class="sidebar">
<img src="assets/logo.png" class="logo" alt="Logo">
<h2>EMPLOYEE DASHBOARD</h2>
<ul class="nav flex-column">
<li><a class="nav-link active" href="home.php">Home</a></li>
<li><a class="nav-link" href="logout.php">Logout</a></li>
</ul>
</div>

<div class="main-content">
<div class="greeting">Welcome Back, <?php echo htmlspecialchars($emp['fullname']); ?>!</div>

<div class="row mb-4">
<div class="col-md-4">
<div class="card text-center">
<h5>Attendance %</h5>
<h2><?php echo $metrics['attendance_pct']; ?>%</h2>
</div>
</div>
<div class="col-md-4">
<div class="card text-center">
<h5>Performance Score</h5>
<h2><?php echo $metrics['performance_score']; ?></h2>
</div>
</div>
<div class="col-md-4">
<div class="card text-center">
<h5>Overtime Hours</h5>
<h2><?php echo $metrics['overtime_hours']; ?></h2>
</div>
</div>
</div>

<div class="card p-3 mb-4">
<h5>Record Attendance</h5>
<form method="POST" action="attendance_action.php" onsubmit="return validateTime(event);">
<label>Date:</label>
<input type="date" id="att-date" name="date" class="form-control mb-2" max="<?= date('Y-m-d'); ?>" required>

<label>Time In:</label>
<input type="time" id="time-in" name="time_in" class="form-control mb-2">

<label>Time Out:</label>
<input type="time" id="time-out" name="time_out" class="form-control mb-2">

<button type="submit" class="btn btn-primary">Submit</button>
</form>
</div>

<div class="card p-3 mb-4">
<h5>Your Attendance Records</h5>
<table class="table table-striped">
<thead>
<tr><th>Date</th><th>Time In</th><th>Time Out</th><th>Regular Hours</th><th>Overtime Hours</th></tr>
</thead>
<tbody>
<?php
$att = $conn->query("SELECT * FROM attendance WHERE employee_id=$eid ORDER BY date DESC");
while($row=$att->fetch_assoc()){
    echo "<tr><td>{$row['date']}</td><td>{$row['time_in']}</td><td>{$row['time_out']}</td><td>{$row['regular_hours']}</td><td>{$row['overtime_hours']}</td></tr>";
}
?>
</tbody>
</table>
</div>

<div class="footer">
<p>&copy; 2025 RooseMetrics Employee Tracker.</p>
</div>

</div>
</body>
</html>
