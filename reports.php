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

// Get logged-in user's info
$username = $_SESSION['username'];
$userQuery = $conn->prepare("SELECT user_id, fullname FROM users WHERE username=?");
if (!$userQuery) die("Prepare failed: " . $conn->error);
$userQuery->bind_param("s", $username);
$userQuery->execute();
$userResult = $userQuery->get_result();
$user = $userResult->fetch_assoc();
$user_id = $user['user_id'];
$fullname = $user['fullname'];
$userQuery->close();

// Order Summary
$orderSummaryQuery = $conn->prepare("SELECT COUNT(*) as total_orders FROM orders WHERE user_id=?");
if (!$orderSummaryQuery) die("Prepare failed: " . $conn->error);
$orderSummaryQuery->bind_param("i", $user_id);
$orderSummaryQuery->execute();
$orderSummary = $orderSummaryQuery->get_result()->fetch_assoc();
$orderSummaryQuery->close();

// Ticket Summary
$ticketSummaryQuery = $conn->prepare("
    SELECT 
        COUNT(*) as total_tickets,
        SUM(CASE WHEN status='Open' THEN 1 ELSE 0 END) as open,
        SUM(CASE WHEN status='Closed' THEN 1 ELSE 0 END) as closed
    FROM tickets
    WHERE user_id=?
");
if (!$ticketSummaryQuery) die("Prepare failed: " . $conn->error);
$ticketSummaryQuery->bind_param("i", $user_id);
$ticketSummaryQuery->execute();
$ticketSummary = $ticketSummaryQuery->get_result()->fetch_assoc();
$ticketSummaryQuery->close();

// Recent Orders
$recentOrdersQuery = $conn->prepare("SELECT * FROM orders WHERE user_id=? ORDER BY order_date DESC LIMIT 5");
if (!$recentOrdersQuery) die("Prepare failed: " . $conn->error);
$recentOrdersQuery->bind_param("i", $user_id);
$recentOrdersQuery->execute();
$recentOrders = $recentOrdersQuery->get_result();
$recentOrdersQuery->close();

// Recent Tickets
$recentTicketsQuery = $conn->prepare("SELECT * FROM tickets WHERE user_id=? ORDER BY ticket_date DESC LIMIT 5");
if (!$recentTicketsQuery) die("Prepare failed: " . $conn->error);
$recentTicketsQuery->bind_param("i", $user_id);
$recentTicketsQuery->execute();
$recentTickets = $recentTicketsQuery->get_result();
$recentTicketsQuery->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Reports - Smart CRM</title>
<style>
body { font-family: 'Segoe UI', sans-serif; background:#f4f6f9; margin:0; padding:20px; }
header { background:#764ba2; color:white; padding:20px; text-align:center; font-size:24px; font-weight:bold; position:relative;}
.logout { position:absolute; right:20px; top:20px; background:#e74c3c; border:none; padding:10px 15px; border-radius:6px; color:white; cursor:pointer; }
.logout:hover { background:#c0392b; }
h2 { color:#185a9d; margin-top:30px; }
.summary { display:flex; gap:20px; margin-top:20px; flex-wrap:wrap; }
.card { background:white; padding:20px; border-radius:12px; box-shadow:0 5px 15px rgba(0,0,0,0.1); flex:1; min-width:200px; text-align:center; }
.card h3 { margin-bottom:10px; color:#764ba2; }
table { width:100%; border-collapse:collapse; margin-top:20px; }
table, th, td { border:1px solid #ccc; }
th, td { padding:10px; text-align:left; }
th { background:#185a9d; color:white; }
tr:hover { background:#f1f1f1; }
.back-btn { display:inline-block; margin-top:20px; padding:10px 15px; background:#185a9d; color:white; text-decoration:none; border-radius:6px; }
.back-btn:hover { background:#116d8a; }
</style>
</head>
<body>

<header>
Reports for <?php echo htmlspecialchars($fullname); ?> 
<form method="post" action="logout.php" style="display:inline;">
    <button class="logout" type="submit">Logout</button>
</form>
</header>

<h2>Summary</h2>
<div class="summary">
    <div class="card">
        <h3>Total Orders</h3>
        <p><?php echo $orderSummary['total_orders']; ?></p>
    </div>
    <div class="card">
        <h3>Total Tickets</h3>
        <p><?php echo $ticketSummary['total_tickets']; ?></p>
    </div>
    <div class="card">
        <h3>Open Tickets</h3>
        <p><?php echo $ticketSummary['open']; ?></p>
    </div>
    <div class="card">
        <h3>Closed Tickets</h3>
        <p><?php echo $ticketSummary['closed']; ?></p>
    </div>
</div>

<h2>Recent Orders</h2>
<?php if ($recentOrders->num_rows > 0): ?>
<table>
    <tr>
        <th>Order ID</th>
        <th>Product</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Date</th>
        <th>Supplier ID</th>
    </tr>
    <?php while($order = $recentOrders->fetch_assoc()): ?>
    <tr>
        <td><?php echo $order['id']; ?></td>
        <td><?php echo htmlspecialchars($order['product_name']); ?></td>
        <td><?php echo $order['quantity']; ?></td>
        <td><?php echo $order['price']; ?></td>
        <td><?php echo $order['order_date']; ?></td>
        <td><?php echo $order['sup_id']; ?></td>
    </tr>
    <?php endwhile; ?>
</table>
<?php else: ?>
<p>No recent orders found.</p>
<?php endif; ?>

<h2>Recent Tickets</h2>
<?php if ($recentTickets->num_rows > 0): ?>
<table>
    <tr>
        <th>Ticket ID</th>
        <th>Subject</th>
        <th>Status</th>
        <th>Date</th>
        <th>Order ID</th>
    </tr>
    <?php while($ticket = $recentTickets->fetch_assoc()): ?>
    <tr>
        <td><?php echo $ticket['t_id']; ?></td>
        <td><?php echo htmlspecialchars($ticket['subject']); ?></td>
        <td><?php echo $ticket['status']; ?></td>
        <td><?php echo $ticket['ticket_date']; ?></td>
        <td><?php echo $ticket['id']; ?></td>
    </tr>
    <?php endwhile; ?>
</table>
<?php else: ?>
<p>No recent tickets found.</p>
<?php endif; ?>

<a href="home.php" class="back-btn">⬅ Back to Home</a>

</body>
</html>
