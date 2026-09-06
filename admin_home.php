<?php
session_start();

// Redirect to admin login if not logged in
if (!isset($_SESSION['admin_username'])) {
    header("Location: admin_login.html");
    exit;
}

// Connect to MySQL
$conn = new mysqli("localhost", "root", "", "smart_crm");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch users
$users = $conn->query("SELECT * FROM users ORDER BY user_id DESC");
if (!$users) {
    die("Users query failed: " . $conn->error);
}

// Fetch orders
$orders = $conn->query("SELECT orders.*, users.fullname FROM orders JOIN users ON orders.user_id = users.user_id ORDER BY orders.order_date DESC");
if (!$orders) {
    die("Orders query failed: " . $conn->error);
}

// Fetch tickets
$tickets = $conn->query("SELECT tickets.*, users.fullname FROM tickets JOIN users ON tickets.user_id = users.user_id ORDER BY tickets.ticket_date DESC");
if (!$tickets) {
    die("Tickets query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard - Smart CRM</title>
<style>
body { font-family:'Segoe UI'; background:#f5f7fa; margin:0; }
header { background:#f2994a; color:white; padding:20px; text-align:center; position:relative; font-size:24px; font-weight:bold; }
.logout { position:absolute; right:20px; top:20px; background:#e74c3c; border:none; padding:10px 15px; border-radius:6px; color:white; cursor:pointer; }
.logout:hover { background:#c0392b; }
.container { max-width:1200px; margin:30px auto; padding:0 20px; }
h2 { color:#f2994a; margin-bottom:15px; }
table { width:100%; border-collapse: collapse; margin-bottom:30px; }
table, th, td { border:1px solid #ccc; }
th, td { padding:10px; text-align:left; }
th { background:#f2c94c; color:white; }
tr:hover { background:#f1f1f1; }
</style>
</head>
<body>

<header>
Admin Dashboard
<form method="post" action="logout.php" style="display:inline;">
<button class="logout" type="submit">Logout</button>
</form>
</header>

<div class="container">
    <h2>Users</h2>
    <table>
        <tr>
            <th>ID</th><th>Full Name</th><th>Username</th><th>Email</th><th>Phone</th>
        </tr>
        <?php while($user = $users->fetch_assoc()): ?>
        <tr>
            <td><?php echo $user['user_id']; ?></td>
            <td><?php echo htmlspecialchars($user['fullname']); ?></td>
            <td><?php echo htmlspecialchars($user['username']); ?></td>
            <td><?php echo htmlspecialchars($user['email']); ?></td>
            <td><?php echo htmlspecialchars($user['phone']); ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <h2>Orders</h2>
    <table>
        <tr>
            <th>Order ID</th><th>User</th><th>Product</th><th>Quantity</th><th>Price</th><th>Date</th>
        </tr>
        <?php while($order = $orders->fetch_assoc()): ?>
        <tr>
            <td><?php echo $order['id']; ?></td>
            <td><?php echo htmlspecialchars($order['fullname']); ?></td>
            <td><?php echo htmlspecialchars($order['product_name']); ?></td>
            <td><?php echo $order['quantity']; ?></td>
            <td><?php echo $order['price']; ?></td>
            <td><?php echo $order['order_date']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <h2>Tickets</h2>
    <table>
        <tr>
            <th>Ticket ID</th><th>User</th><th>Subject</th><th>Message</th><th>Status</th><th>Date</th>
        </tr>
        <?php while($ticket = $tickets->fetch_assoc()): ?>
        <tr>
            <td><?php echo $ticket['id']; ?></td>
            <td><?php echo htmlspecialchars($ticket['fullname']); ?></td>
            <td><?php echo htmlspecialchars($ticket['subject']); ?></td>
            <td><?php echo htmlspecialchars($ticket['message']); ?></td>
            <td><?php echo htmlspecialchars($ticket['status']); ?></td>
            <td><?php echo $ticket['ticket_date']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>
