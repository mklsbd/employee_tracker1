<?php
session_start();
include 'db_config.php';

if($_SERVER['REQUEST_METHOD']=='POST'){
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    $res = $conn->query("SELECT * FROM users WHERE username='$username' AND password='$password'");
    if($res->num_rows==1){
        $user = $res->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['fullname'] = $user['fullname'];
        $_SESSION['role'] = $user['role'];
        if($user['role']=='admin') header("Location: dashboard.php");
        else header("Location: home.php");
        exit;
    } else {
        $error = "Invalid credentials!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - ROOSEMETRICS</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
:root{
    --bg-dark:#1e1d1c;
    --bg-panel:#272624;
    --bg-card:#343331;
    --accent-teal:#7ed1c5;
    --accent-gold:#eac68e;
    --text-light:#ffffff;
    --text-muted:#c5c1ba;
}
body{
    margin:0;
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:var(--bg-dark);
    font-family:"Poppins",sans-serif;
    color:var(--text-light);
}
.login-container{
    background:var(--bg-card);
    padding:40px 50px;
    border-radius:20px;
    box-shadow:0 6px 20px rgba(0,0,0,0.5);
    text-align:center;
    width:350px;
}
.login-container img{
    width:120px;
    height:120px;
    object-fit:contain;
    margin-bottom:20px;
}
.login-container h1{
    color:var(--accent-gold);
    margin-bottom:30px;
    font-size:28px;
    font-weight:700;
}
.login-container input{
    width:100%;
    padding:10px 15px;
    margin-bottom:15px;
    border-radius:8px;
    border:1px solid var(--accent-teal);
    background:var(--bg-panel);
    color:#fff;
}
.login-container input::placeholder{
    color:var(--text-muted);
}
.login-container button{
    width:100%;
    padding:10px 15px;
    border:none;
    border-radius:12px;
    background:var(--accent-teal);
    color:black;
    font-weight:600;
    font-size:16px;
    cursor:pointer;
}
.login-container button:hover{
    background:var(--accent-gold);
}
.error-msg{
    background:#d9534f;
    padding:10px;
    border-radius:10px;
    margin-bottom:15px;
    color:#fff;
    font-weight:600;
}
</style>
</head>
<body>
<div class="login-container">
    <img src="assets/logo.png" alt="Logo">
    <h1>ROOSEMETRICS</h1>
    <?php if(isset($error)) echo "<div class='error-msg'>$error</div>"; ?>
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
</div>
</body>
</html>
