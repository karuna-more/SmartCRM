<?php
session_start();

$conn = new mysqli("localhost","root","","smart_crm");
if($conn->connect_error){
    die("Connection failed: ".$conn->connect_error);
}

if($_SERVER['REQUEST_METHOD']==='POST'){
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM admins WHERE username=?");
    $stmt->bind_param("s",$username);
    $stmt->execute();
    $result = $stmt->get_result();

    if($admin = $result->fetch_assoc()){
        // Using password_verify if hashed, else plain comparison
        if(password_verify($password,$admin['password']) || $password==$admin['password']){
            $_SESSION['admin_username'] = $username;
            header("Location: admin_home.php");
            exit;
        }else{
            echo "<script>alert('Invalid password!'); window.location.href='admin_login.html';</script>";
        }
    }else{
        echo "<script>alert('Admin not found!'); window.location.href='admin_login.html';</script>";
    }
    $stmt->close();
}
$conn->close();
?>
