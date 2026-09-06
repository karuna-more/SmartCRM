<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit;
}

$conn = new mysqli("localhost", "root", "", "smart_crm");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get logged-in user's info
$username = $_SESSION['username'];
$userQuery = $conn->prepare("SELECT user_id FROM users WHERE username=?");
$userQuery->bind_param("s", $username);
$userQuery->execute();
$userResult = $userQuery->get_result();
$user = $userResult->fetch_assoc();
$user_id = $user['user_id'];
$userQuery->close();

// Get order_id (id from orders table)
$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Handle ticket submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = $_POST['subject'];
    $message = !empty($_POST['message']) ? $_POST['message'] : '';

    $stmt = $conn->prepare("INSERT INTO tickets (user_id, subject, message, status, ticket_date, id) 
                            VALUES (?, ?, ?, 'Open', NOW(), ?)");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("issi", $user_id, $subject, $message, $order_id);

    if ($stmt->execute()) {
        echo "<script>alert('Your ticket has been raised successfully!'); window.location.href='my_orders.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Raise Ticket - Smart CRM</title>
<style>
body {
    font-family: 'Segoe UI', sans-serif;
    background: #f4f6f9;
    margin: 0;
    padding: 20px;
}
h2 { color: #185a9d; }
form {
    background: white;
    padding: 25px;
    border-radius: 12px;
    max-width: 500px;
    margin: auto;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}
label { display:block; margin:15px 0 5px; }
select, textarea, button {
    width: 100%;
    padding: 10px;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 14px;
}
button {
    margin-top: 20px;
    background: #185a9d;
    color: white;
    font-weight: bold;
    border: none;
    cursor: pointer;
}
button:hover { background: #116d8a; }
</style>
</head>
<body>

<h2>Raise a Ticket</h2>

<form method="POST">
    <label for="subject">Select your query</label>
    <select name="subject" required>
        <option value="">-- Choose a query --</option>
        <option>Product not delivered</option>
        <option>Wrong item received</option>
        <option>Order delayed</option>
        <option>Payment issue</option>
        <option>Need invoice copy</option>
        <option>Request for refund</option>
        <option>Request for replacement</option>
        <option>Technical issue</option>
        <option>Account related query</option>
        <option>Other</option>
    </select>

    <label for="message">Additional Details (optional)</label>
    <textarea name="message" rows="4"></textarea>

    <button type="submit">Submit Ticket</button>
</form>

</body>
</html>
