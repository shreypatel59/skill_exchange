<?php
session_start();
include("../config/db.php");

// Simple admin login check (for demo)
if (!isset($_SESSION['is_admin'])) {
    $_SESSION['is_admin'] = true; // Remove for real login system
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<header>
    <h1>Admin Panel</h1>
</header>

<div class="container">
    <a class="btn" href="manage_requests.php">Manage Exchange Requests</a>
</div>

</body>
</html>
