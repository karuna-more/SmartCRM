<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit;
}

$conn = new mysqli("localhost", "root", "", "smart_crm");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get logged-in user's ID
$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT user_id FROM users WHERE username=?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$user_id = $user['user_id'];

// Fetch orders for this user
$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id=? ORDER BY order_date DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Orders - Smart CRM</title>
<style>
body { font-family:'Segoe UI'; background:#f4f6f9; margin:0; padding:20px; }
h2 { color:#185a9d; text-align:center; }
table { width:100%; border-collapse: collapse; margin-top:20px; }
th, td { padding:12px; border:1px solid #ccc; text-align:left; }
th { background:#185a9d; color:white; }
tr:hover { background:#f1f1f1; }
.help-btn {
    padding:6px 12px;
    background:#764ba2;
    color:white;
    border:none;
    border-radius:6px;
    cursor:pointer;
    transition:0.3s;
    text-decoration:none;
}
.help-btn:hover { background:#667eea; }
.back-btn {
    display:inline-block;
    margin-top:20px;
    padding:10px 15px;
    background:#185a9d;
    color:white;
    text-decoration:none;
    border-radius:6px;
}
.back-btn:hover { background:#116d8a; }
</style>
</head>
<body>

<h2>My Orders</h2>

<?php if ($orders->num_rows > 0): ?>
<table>
    <tr>
        <th>Order ID</th>
        <th>Product</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Date</th>
        <th>Help</th>
    </tr>
    <?php while($order = $orders->fetch_assoc()): ?>
    <tr>
        <td><?php echo $order['id']; ?></td>
        <td><?php echo htmlspecialchars($order['product_name']); ?></td>
        <td><?php echo $order['quantity']; ?></td>
        <td><?php echo $order['price']; ?></td>
        <td><?php echo $order['order_date']; ?></td>
        <td>
            <a class="help-btn" href="raise_ticket.php?order_id=<?php echo $order['id']; ?>">Help</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
<?php else: ?>
<p style="text-align:center;">No orders found.</p>
<?php endif; ?>

<a href="home.php" class="back-btn">⬅ Back to Home</a>

</body>
</html>
