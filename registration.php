<?php
session_start();

// Connect to MySQL
$conn = new mysqli("localhost", "root", "", "smart_crm");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Fullname validation (letters and spaces only)
    if (!preg_match("/^[A-Za-z\s]+$/", $fullname)) {
        echo "<script>alert('Full name should only contain letters and spaces'); window.location.href='registration.html';</script>";
        exit;
    }

    // Username validation (letters and numbers)
    if (!preg_match("/^[A-Za-z0-9]+$/", $username)) {
        echo "<script>alert('Username can contain only letters and numbers'); window.location.href='registration.html';</script>";
        exit;
    }

    // Password match check
    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!'); window.location.href='registration.html';</script>";
        exit;
    }

    // Hash password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Check if username or email exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE username=? OR email=?");
    if (!$stmt) { die("Prepare failed: " . $conn->error); }
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Username or Email already exists!'); window.location.href='registration.html';</script>";
        exit;
    }
    $stmt->close();

    // Insert user into database
    $stmt = $conn->prepare("INSERT INTO users (fullname, username, email, phone, password, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    if (!$stmt) { die("Prepare failed: " . $conn->error); }
    $stmt->bind_param("sssss", $fullname, $username, $email, $phone, $password_hash);

    if ($stmt->execute()) {
        $_SESSION['username'] = $username;
        $_SESSION['fullname'] = $fullname;
        echo "<script>alert('Registration successful!'); window.location.href='home.php';</script>";
        exit;
    } else {
        echo "<script>alert('Registration failed. Try again!'); window.location.href='registration.html';</script>";
    }

    $stmt->close();
}

$conn->close();
?>
