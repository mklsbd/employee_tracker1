<?php
include 'db_config.php';
include 'calculate_metrics.php';
checkLogin();
if($_SESSION['role']!='admin') header("Location: login.php");

$message = '';

// Fetch existing departments for dropdown
$deptResult = $conn->query("SELECT DISTINCT department FROM employees");
$departments = [];
while($d = $deptResult->fetch_assoc()) $departments[] = $d['department'];

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $user_id = intval($_POST['user_id']);
    $fullname = $conn->real_escape_string($_POST['fullname']);
    $department = $conn->real_escape_string($_POST['department']);

    if($user_id && $fullname && $department){
        $stmt = $conn->prepare("INSERT INTO employees (user_id, fullname, department) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id, $fullname, $department);
        if($stmt->execute()){
            $message = "Employee added successfully!";
        } else {
            $message = "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "All fields are required.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Employee</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
:root{
--bg-dark:#1e1d1c;--bg-panel:#272624;--bg-card:#343331;
--accent-teal:#7ed1c5;--accent-gold:#eac68e;--accent-orange:#d88954;
--text-light:#ffffff;--text-muted:#c5c1ba;
}
body{margin:0;background:var(--bg-dark);color:var(--text-light);font-family:"Poppins",sans-serif;display:flex;flex-direction:column;min-height:100vh;}
.sidebar{width:260px;background:var(--bg-panel);height:100vh;padding:25px;display:flex;flex-direction:column;box-shadow:4px 0 15px rgba(0,0,0,0.4);position:fixed;left:0;top:0;}
.sidebar .logo{width:70px;margin:0 auto 10px auto;display:block;border-radius:50%;}
.sidebar h2{text-align:center;color:var(--accent-teal);font-size:22px;margin-bottom:35px;}
.sidebar .nav-link{color:var(--text-light);font-size:16px;padding:12px 15px;border-radius:12px;margin-bottom:8px;transition:0.25s;}
.sidebar .nav-link:hover,.sidebar .nav-link.active{background:var(--accent-teal);color:#000;}
.main-content{margin-left:260px;padding:30px 40px;width:calc(100%-260px);flex:1;}
.card{background:var(--bg-card);border-radius:20px;padding:30px;box-shadow:0 6px 20px rgba(0,0,0,0.3);border:none;margin-bottom:25px;}
.card h5{color:var(--accent-gold);font-weight:600;font-size:20px;}

/* Labels now match the card header color */
label{color:var(--accent-gold); font-weight:500; font-size:16px; margin-bottom:5px; display:block;}

.form-control, .form-select{background:var(--bg-panel);border:1px solid var(--accent-teal);color:#ffffff;font-size:16px;border-radius:8px;padding:8px;margin-bottom:12px;}
.form-control::placeholder{color:var(--text-muted);}
.btn-primary{background:var(--accent-teal);color:black;border:none;font-weight:600;border-radius:12px;padding:10px 20px;font-size:16px;}
.btn-primary:hover{background:var(--accent-gold);}
.footer{background:var(--bg-panel);color:#ffffff;text-align:center;padding:15px;font-size:14px;width:100%;margin-top:auto;}
.alert-custom{background-color:#7ed1c5;color:#000;font-weight:600;padding:10px;border-radius:12px;margin-bottom:15px;text-align:center;}
</style>
</head>
<body>

<div class="sidebar">
<img src="assets/logo.png" class="logo" alt="Logo">
<h2>ADMIN PANEL</h2>
<ul class="nav flex-column">
<li><a class="nav-link" href="dashboard.php">Dashboard</a></li>
<li><a class="nav-link active" href="add_employee.php">Add Employee</a></li>
<li><a class="nav-link" href="logout.php">Logout</a></li>
</ul>
</div>

<div class="main-content">
<div class="card">
<h5>Add New Employee</h5>

<?php if($message): ?>
<div class="alert-custom"><?php echo $message; ?></div>
<?php endif; ?>

<form method="POST">
<label>User ID:</label>
<input type="number" name="user_id" class="form-control" placeholder="Enter user ID" required>

<label>Full Name:</label>
<input type="text" name="fullname" class="form-control" placeholder="Enter full name" required>

<label>Department:</label>
<select name="department" class="form-select" required>
    <option value="">Select Department</option>
    <?php foreach($departments as $d) echo "<option>$d</option>"; ?>
</select>

<button type="submit" class="btn btn-primary mt-2">Add Employee</button>
</form>
</div>

<div class="footer">
<p>&copy; 2025 RooseMetrics Employee Tracker.</p>
</div>
</div>
</body>
</html>
