<?php
include 'db_config.php';
include 'calculate_metrics.php';
checkLogin();
if($_SESSION['role']!='admin') header("Location: login.php");

$isAdmin = true;

// Get departments for filter
$deptResult = $conn->query("SELECT DISTINCT department FROM employees");
$departments = [];
while($d = $deptResult->fetch_assoc()) $departments[] = $d['department'];

// Total employees
$totalEmployees = $conn->query("SELECT COUNT(*) as count FROM employees")->fetch_assoc()['count'];
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
:root{
--bg-dark:#1e1d1c;--bg-panel:#272624;--bg-card:#343331;
--accent-teal:#7ed1c5;--accent-gold:#eac68e;--accent-orange:#d88954;
--text-light:#ffffff;--text-muted:#c5c1ba;
}
body{margin:0;background:var(--bg-dark);color:var(--text-light);font-family:"Poppins",sans-serif;display:flex;flex-direction:column;min-height:100vh;}
.sidebar{width:260px;background:var(--bg-panel);height:100vh;padding:25px;display:flex;flex-direction:column;box-shadow:4px 0 15px rgba(0,0,0,0.4);position:fixed;left:0;top:0;}
.sidebar .logo{width:120px;margin:0 auto 10px auto;display:block;border-radius:50%;}
.sidebar h2{text-align:center;color:var(--accent-teal);font-size:22px;margin-bottom:35px;}
.sidebar .nav-link{color:var(--text-light);font-size:15px;padding:12px 15px;border-radius:12px;margin-bottom:8px;transition:0.25s;}
.sidebar .nav-link:hover,.sidebar .nav-link.active{background:var(--accent-teal);color:#000;}
.main-content{margin-left:260px;padding:30px 40px;width:calc(100%-260px);flex:1;}
.card{background:var(--bg-card);border-radius:20px;padding:25px;box-shadow:0 6px 20px rgba(0,0,0,0.3);border:none;margin-bottom:25px;color:var(--text-light);}
.card h5{color:var(--accent-gold);font-weight:600;}
.table{color:var(--text-light);width:100%;}
.table th{background:var(--accent-teal);color:#000;border:none;}
.table td{background:var(--bg-card);border-color:#444;color:#ffffff;}
.low-performance{background-color:var(--accent-orange)!important;color:#000!important;}
.btn-primary{background:var(--accent-teal);color:#000;border:none;font-weight:600;border-radius:12px;padding:10px 16px;}
.btn-primary:hover{background:var(--accent-gold);}
.footer{background:var(--bg-panel);color:#ffffff;text-align:center;padding:15px;font-size:14px;width:100%;margin-top:auto;}
.alert-custom{background-color:#d9534f;color:#ffffff;font-weight:600;padding:10px;border-radius:12px;}
.form-select,.form-control{background:var(--bg-panel);border:1px solid var(--accent-teal);color:#ffffff;}
label{color:var(--text-light);font-weight:500;}
</style>
</head>
<body>
<div class="sidebar">
<img src="assets/logo.png" class="logo" alt="Logo">
<h2>DASHBOARD</h2>
<ul class="nav flex-column">
<li><a class="nav-link active" href="dashboard.php">Overview</a></li>
<li><a class="nav-link" href="add_employee.php">Add Employee</a></li>
<li><a class="nav-link" href="logout.php">Logout</a></li>
</ul>
</div>
<div class="main-content">

<!-- Alerts -->
<div id="alerts-container"></div>

<div class="row mb-4">
<div class="col-md-4">
<div class="card text-center">
<h5>Total Employees</h5>
<h2><?php echo $totalEmployees;?></h2>
</div>
</div>
</div>

<!-- Filters -->
<div class="card p-3 mb-4 d-flex align-items-center">
<h5 class="mb-2">Filters</h5>
<div class="d-flex align-items-center">
<label class="me-3">Department:
<select id="dept-filter" class="form-select d-inline w-auto">
    <option value="">All</option>
    <?php foreach($departments as $d) echo "<option>$d</option>"; ?>
</select>
</label>
<label>Month:
<input type="month" id="month-filter" class="form-control d-inline w-auto ms-3">
</label>
</div>
</div>

<div class="card p-3 mb-4">
<h5>Employee Performance</h5>
<table class="table table-striped">
<thead><tr><th>Name</th><th>Department</th><th>Attendance %</th><th>Performance Score</th><th>Regular Hours</th><th>Overtime Hours</th></tr></thead>
<tbody id="performance-body"></tbody>
</table>
</div>

<div class="card p-3 mb-4">
<h5>Attendance Trends (Average)</h5>
<canvas id="attendanceChart"></canvas>
</div>

<div class="footer">
<p>&copy; 2025 RooseMetrics Employee Tracker.</p>
</div>
</div>

<script>
const deptFilter = document.getElementById('dept-filter');
const monthFilter = document.getElementById('month-filter');
const performanceBody = document.getElementById('performance-body');
const alertsContainer = document.getElementById('alerts-container');

// Apply filters
function applyFilters(){
    const dept = deptFilter.value;
    const rows = performanceBody.querySelectorAll('tr');
    rows.forEach(row=>{
        let show = true;
        if(dept && row.getAttribute('data-dept') !== dept) show=false;
        row.style.display = show?'':'none';
    });
}
deptFilter.addEventListener('change', applyFilters);
monthFilter.addEventListener('input', applyFilters);

// Chart
let ctx = document.getElementById('attendanceChart').getContext('2d');
let attendanceChart = new Chart(ctx, {
    type: 'line',
    data: { labels: [], datasets: [{ label:'Average Attendance %', data:[], borderColor:'#7ed1c5', backgroundColor:'rgba(126,209,197,0.2)', tension:0.4 }] },
    options: {
        responsive:true,
        plugins:{ legend:{ labels:{ color:'#ffffff' } } },
        scales:{ y:{ beginAtZero:true, max:100, ticks:{color:'#ffffff'} }, x:{ ticks:{color:'#ffffff'} } }
    }
});

// Reload chart
function reloadChart(){
    $.getJSON('attendance_chart_data.php', function(data){
        attendanceChart.data.labels = data.labels;
        attendanceChart.data.datasets[0].data = data.trendData;
        attendanceChart.update();
    });
}

// Reload performance table
function reloadTable(){
    $.getJSON('performance_data.php', function(data){
        let html = '';
        data.forEach(emp=>{
            html += `<tr data-dept="${emp.department}" class="${emp.lowClass}">
                <td>${emp.fullname}</td>
                <td>${emp.department}</td>
                <td>${emp.attendance_pct}%</td>
                <td>${emp.performance_score}</td>
                <td>${emp.regular_hours}</td>
                <td>${emp.overtime_hours}</td>
            </tr>`;
        });
        performanceBody.innerHTML = html;
        applyFilters();
    });
}

// Reload alerts
function reloadAlerts(){
    $.getJSON('alerts_data.php', function(data){
        if(data.length>0){
            alertsContainer.innerHTML = `<div class="alert alert-custom mb-4">⚠ Low Attendance/Performance: ${data.join(', ')}</div>`;
        } else {
            alertsContainer.innerHTML = '';
        }
    });
}

// Initial load
reloadChart();
reloadTable();
reloadAlerts();

// Refresh every 5 seconds
setInterval(()=>{
    reloadChart();
    reloadTable();
    reloadAlerts();
}, 5000);
</script>
</body>
</html>
