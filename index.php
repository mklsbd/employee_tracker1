<?php
include_once 'db_config.php';
include_once 'calculate_metrics.php';

checkLogin();
$isAdmin = $_SESSION['role'] === 'admin';

// Get total employees
$totalEmployees = $conn->query("SELECT COUNT(*) as count FROM employees")->fetch_assoc()['count'];

// Get low attendance/performance alerts
$alerts = $conn->query("
SELECT e.name FROM employees e
LEFT JOIN attendance a ON e.id=a.employee_id
LEFT JOIN performance p ON e.id=p.employee_id
GROUP BY e.id
HAVING (COUNT(CASE WHEN a.status='present' THEN 1 END)/22*100)<80
OR AVG(p.score)<80
");

// Department average performance
$deptPerformance = [];
$deptResult = $conn->query("SELECT department FROM employees GROUP BY department");
while($dept=$deptResult->fetch_assoc()){
    $avgPerf=$conn->query("SELECT AVG(score) as avg_score FROM performance p
    JOIN employees e ON p.employee_id=e.id WHERE e.department='{$dept['department']}'")->fetch_assoc()['avg_score'];
    $deptPerformance[$dept['department']]=round($avgPerf,2);
}

// Employee data
$employeeQuery = $isAdmin ? "SELECT * FROM employees" : "SELECT * FROM employees WHERE id={$_SESSION['user_id']}";
$employeeResult = $conn->query($employeeQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Employee Tracker Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
:root{
--bg-dark:#1e1d1c;
--bg-panel:#272624;
--bg-card:#343331;
--accent-teal:#7ed1c5;
--accent-gold:#eac68e;
--accent-orange:#d88954;
--text-light:#ffffff;
--text-muted:#c5c1ba;
}
body{margin:0;background:var(--bg-dark);color:var(--text-light);font-family:"Poppins",sans-serif;display:flex;flex-direction:column;min-height:100vh;}
.sidebar{width:260px;background:var(--bg-panel);height:100vh;padding:25px;display:flex;flex-direction:column;box-shadow:4px 0 15px rgba(0,0,0,0.4);position:fixed;left:0;top:0;}
.sidebar .logo{width:70px;margin:0 auto 10px auto;display:block;border-radius:50%;}
.sidebar h2{text-align:center;color:var(--accent-teal);font-size:22px;margin-bottom:35px;}
.sidebar .nav-link{color:var(--text-light);font-size:15px;padding:12px 15px;border-radius:12px;margin-bottom:8px;transition:0.25s;}
.sidebar .nav-link:hover,.sidebar .nav-link.active{background:var(--accent-teal);color:#000;}
.main-content{margin-left:260px;padding:30px 40px;width:calc(100%-260px);flex:1;}
.card{background:var(--bg-card);border-radius:20px;padding:25px;box-shadow:0 6px 20px rgba(0,0,0,0.3);border:none;margin-bottom:25px;}
.card h5{color:var(--accent-gold);font-weight:600;}
.table{color:var(--text-light);}
.table th{background:var(--accent-teal);color:#000;border:none;}
.table td{background:var(--bg-card);border-color:#444;color:#ffffff;}
.low-performance{background-color:var(--accent-orange)!important;color:#000!important;}
.btn-primary{background:var(--accent-teal);color:black;border:none;font-weight:600;border-radius:12px;padding:10px 16px;}
.btn-primary:hover{background:var(--accent-gold);}
.footer{background:var(--bg-panel);color:#ffffff;text-align:center;padding:15px;font-size:14px;width:100%;margin-top:auto;}
.alert-custom{background-color:#d9534f;color:#ffffff;font-weight:600;padding:10px;border-radius:12px;}
.form-select,.form-control{background:var(--bg-panel);border:1px solid var(--accent-teal);color:#ffffff;}
</style>
</head>
<body>

<div class="sidebar">
<img src="assets/logo.png" class="logo" alt="Logo">
<h2>DASHBOARD</h2>
<ul class="nav flex-column">
    <li><a class="nav-link active" href="index.php">Overview</a></li>
    <?php if($isAdmin): ?>
    <li><a class="nav-link" href="add_employee.php">Add Employee</a></li>
    <li><a class="nav-link" href="add_attendance.php">Add Attendance</a></li>
    <li><a class="nav-link" href="add_performance.php">Add Performance</a></li>
    <?php endif; ?>
    <!-- Logout button -->
    <li><a class="nav-link" href="logout.php">Logout</a></li>
</ul>
</div>

<div class="main-content">

<?php if($alerts->num_rows>0): ?>
<div class="alert alert-custom mb-4">⚠ Low Attendance/Performance: <?php while($row=$alerts->fetch_assoc()){echo $row['name'].' ';} ?></div>
<?php endif; ?>

<div class="row mb-4">
<div class="col-md-4">
<div class="card text-center">
<h5>Total Employees</h5>
<h2><?php echo $totalEmployees;?></h2>
</div>
</div>
</div>

<div class="card p-3 mb-4">
<h5>Filters</h5>
<label>Department:
<select id="department-filter" class="form-select d-inline w-auto">
    <option value="">All</option>
    <?php
    $deptResult = $conn->query("SELECT DISTINCT department FROM employees");
    while($dept=$deptResult->fetch_assoc()){
        echo "<option>{$dept['department']}</option>";
    }
    ?>
</select>
</label>
<label class="ms-3">Month:
<input type="text" id="month-filter" placeholder="YYYYMM" class="form-control d-inline w-auto">
</label>
</div>

<div class="card p-3 mb-4">
<h5>Attendance Trends</h5>
<canvas id="attendance-chart"></canvas>
</div>

<div class="card p-3">
<h5>Employee Performance</h5>
<table class="table table-striped" id="performance-table">
<thead><tr><th>Name</th><th>Department</th><th>Attendance %</th><th>Performance Score</th><th>Overtime Hours</th></tr></thead>
<tbody>
<?php while($emp=$employeeResult->fetch_assoc()):
$metrics=getEmployeeMetrics($emp['id']);
$lowClass=($metrics['attendance_pct']<80||$metrics['performance_score']<80)?'low-performance':'';
?>
<tr data-dept="<?php echo $emp['department'];?>" data-month="202310" class="<?php echo $lowClass;?>">
<td><?php echo $emp['name'];?></td>
<td><?php echo $emp['department'];?></td>
<td><?php echo $metrics['attendance_pct'];?>%</td>
<td><?php echo $metrics['performance_score'];?></td>
<td><?php echo $metrics['overtime_hours'];?></td>
</tr>
<?php endwhile;?>
</tbody>
</table>
</div>
</div>

<div class="footer">
    <p>&copy; 2023 RooseMetrics Employee Tracker. Powered by PHP & MySQL.</p>
    <p>Analytics and Insights by <strong>RooseMetrics</strong> – helping you monitor employee performance effectively.</p>
</div>
<script>
// Filters
document.getElementById('department-filter').addEventListener('change', filterTable);
document.getElementById('month-filter').addEventListener('input', filterTable);
function filterTable(){
    const dept=document.getElementById('department-filter').value;
    const month=document.getElementById('month-filter').value;
    const rows=document.querySelectorAll('#performance-table tbody tr');
    rows.forEach(row=>{
        const rowDept=row.getAttribute('data-dept');
        const rowMonth=row.getAttribute('data-month');
        row.style.display=( (dept===''||rowDept===dept) && (month===''||rowMonth===month) )?'':'none';
    });
}

// Attendance Chart (example)
const ctx=document.getElementById('attendance-chart').getContext('2d');
new Chart(ctx,{
    type:'line',
    data:{
        labels:['Jan','Feb','Mar','Apr','May','Jun'],
        datasets:[{label:'Attendance %',data:[90,85,95,88,92,91],borderColor:'#7ed1c5',backgroundColor:'rgba(126,209,197,0.2)',tension:0.4}]
    }
});
</script>

</body>
</html>
