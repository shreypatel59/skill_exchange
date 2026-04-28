<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['is_admin'])) {
    die("Access denied");
}

// Handle accept/reject
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $id     = $_GET['id'];

    if ($action == "accept") {
        mysqli_query($conn, "UPDATE exchange_requests SET status='accepted' WHERE request_id=$id");
    } elseif ($action == "reject") {
        mysqli_query($conn, "UPDATE exchange_requests SET status='rejected' WHERE request_id=$id");
    }
    header("Location: manage_requests.php");
    exit;
}

$reqs = mysqli_query($conn,
    "SELECT exchange_requests.*, users.name AS from_name, u2.name AS to_name, skills.skill_name
     FROM exchange_requests
     JOIN users ON exchange_requests.from_user = users.user_id
     JOIN users AS u2 ON exchange_requests.to_user = u2.user_id
     JOIN skills ON exchange_requests.skill_id = skills.skill_id"
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Requests</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<div class="container">
<h2>All Exchange Requests</h2>

<div class="cards">
<?php while ($r = mysqli_fetch_assoc($reqs)) { ?>
    <div class="card">
        <p><strong>Skill:</strong> <?php echo $r['skill_name']; ?></p>
        <p><strong>From:</strong> <?php echo $r['from_name']; ?></p>
        <p><strong>To:</strong> <?php echo $r['to_name']; ?></p>
        <p><strong>Status:</strong> <?php echo $r['status']; ?></p>

        <?php if ($r['status']=='pending') { ?>
            <a class="btn" href="?action=accept&id=<?php echo $r['request_id']; ?>">Accept</a>
            <a class="btn" href="?action=reject&id=<?php echo $r['request_id']; ?>">Reject</a>
        <?php } ?>
    </div>
<?php } ?>
</div>

</div>
</body>
</html>
