<?php
session_start();

// Redirect to login if not logged in
if(!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Smart CRM - Home</title>
<style>
body {
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(135deg, #f5f7fa, #dfe6f3);
    margin: 0;
}
header {
    background: linear-gradient(90deg,#667eea,#764ba2);
    color: white;
    padding: 25px;
    text-align: center;
    position: relative;
    font-size: 24px;
    font-weight: bold;
}
.logout {
    position: absolute;
    right: 20px;
    top: 20px;
    background: #e74c3c;
    border: none;
    padding: 10px 15px;
    border-radius: 6px;
    color: white;
    cursor: pointer;
}
.logout:hover { background: #c0392b; }
nav {
    background: #764ba2;
    display: flex;
    justify-content: center;
    padding: 15px 0;
    gap: 20px;
    flex-wrap: wrap;
}
nav a {
    color: white;
    text-decoration: none;
    font-weight: bold;
    padding: 8px 15px;
    border-radius: 8px;
    transition: 0.3s;
}
nav a:hover { background: #667eea; }
.container {
    max-width: 1200px;
    margin: 30px auto;
    padding: 0 20px;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px,1fr));
    gap: 20px;
}
.card {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.15);
    transition: transform 0.3s, box-shadow 0.3s;
    cursor: pointer;
}
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}
.card h3 {
    color: #764ba2;
    margin-bottom: 15px;
}
.card p {
    font-size: 14px;
    color: #333;
    line-height: 1.5;
}
footer {
    text-align: center;
    padding: 20px;
    background: #764ba2;
    color: white;
    margin-top: 40px;
}
</style>
</head>
<body>

<header>
  Welcome, <?php echo htmlspecialchars($_SESSION['fullname']); ?>!
  <form method="post" action="logout.php" style="display:inline;">
    <button class="logout" type="submit">Logout</button>
  </form>
</header>

<nav>
  <a href="#">Dashboard</a>
  <a href="my_orders.php">My Orders</a>
  <a href="about_us.php">About Us</a>
  <a href="reports.php">Reports</a>
</nav>

<div class="container">
    <div class="card">
        <h3>Dashboard</h3>
        <p>Overview of your CRM activities, latest updates, and performance metrics.</p>
    </div>
    <div class="card">
        <h3>My Orders</h3>
        <p>View your ordered products, order status, and history here.</p>
    </div>
    <div class="card">
        <h3>About Us</h3>
        <p>Learn more about Smart CRM, our mission, and services we provide.</p>
    </div>
    <div class="card">
        <h3>Reports</h3>
        <p>Generate and view detailed reports on your orders and account activity.</p>
    </div>
</div>

<footer>
    &copy; 2025 Smart CRM. All Rights Reserved.
</footer>

</body>
</html>
