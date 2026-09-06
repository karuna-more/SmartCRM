<?php
session_start();

// Redirect if not logged in
if(!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>About Us - Smart CRM</title>
<style>
body {
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(135deg, #f5f7fa, #dfe6f3);
    margin: 0;
    padding: 0;
}
header {
    background: linear-gradient(90deg,#667eea,#764ba2);
    color: white;
    padding: 25px;
    text-align: center;
    font-size: 24px;
    font-weight: bold;
    position: relative;
}
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
    max-width: 900px;
    margin: 40px auto;
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.15);
}
h1 {
    color: #764ba2;
    text-align: center;
    margin-bottom: 20px;
}
p {
    font-size: 16px;
    line-height: 1.6;
    color: #333;
    margin-bottom: 15px;
}
img {
    width: 50%;
    max-width: 250px;
    height: auto;
    display: block;
    margin: 0 auto 20px auto; 
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}
.back-btn {
    display:inline-block;
    margin-top:20px;
    padding:10px 15px;
    background:#185a9d;
    color:white;
    text-decoration:none;
    border-radius:6px;
    transition: 0.3s;
}
.back-btn:hover { background:#116d8a; }
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
    About Smart CRM
</header>

<nav>
  <a href="home.php">Home</a>
  <a href="my_orders.php">My Orders</a>
  <a href="about_us.php">About Us</a>
</nav>

<div class="container">
	
<img src="images/smart_crm.jpg" alt="Smart CRM Banner">
    <h1>Welcome to Smart CRM</h1>
    <p>Smart CRM is a modern customer relationship management platform designed to help businesses manage their clients, orders, and tickets efficiently. With our system, you can easily track your orders, communicate with support, and stay updated with your account activities.</p>
    <p>Our mission is to simplify CRM for small and medium businesses, providing a user-friendly interface and real-time insights. Smart CRM ensures your data is secure and accessible anytime, anywhere.</p>
    <p>With Smart CRM, you can focus on growing your business while we take care of your client management needs. Our platform is intuitive, reliable, and designed to save you time and effort.</p>

    <a href="home.php" class="back-btn">⬅ Back to Home</a>
</div>

<footer>
    &copy; 2025 Smart CRM. All Rights Reserved.
</footer>

</body>
</html>
