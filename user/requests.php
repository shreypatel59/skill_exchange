<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("../config/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

/* HANDLE ACTION */
if (isset($_GET['action'])) {

    $id = $_GET['id'];
    $action = $_GET['action'];

    if ($action == "accept") {
        mysqli_query($conn, "UPDATE exchange_requests SET status='accepted' WHERE request_id=$id");
    }

    if ($action == "reject") {
        mysqli_query($conn, "UPDATE exchange_requests SET status='rejected' WHERE request_id=$id");
    }

    header("Location: requests.php");
    exit;
}

/* FETCH REQUESTS */
$requests = mysqli_query($conn,"
SELECT er.*, 
       u1.name AS sender_name, 
       u2.name AS receiver_name,
       s1.skill_name AS offered_skill,
       s2.skill_name AS wanted_skill
FROM exchange_requests er
JOIN users u1 ON er.sender_id = u1.user_id
JOIN users u2 ON er.receiver_id = u2.user_id
JOIN skills s1 ON er.offered_skill_id = s1.skill_id
JOIN skills s2 ON er.wanted_skill_id = s2.skill_id
WHERE er.sender_id = $user_id OR er.receiver_id = $user_id
ORDER BY er.request_id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Requests</title>

<style>

/* ===== BODY ===== */
body{
    margin:0;
    font-family:'Segoe UI', sans-serif;
    min-height:100vh;

    background:
        radial-gradient(circle at top left, rgba(76,175,80,0.4), transparent 40%),
        radial-gradient(circle at bottom right, rgba(12,44,69,0.5), transparent 50%),
        linear-gradient(135deg,#0f2027,#203a43,#2c5364);

    background-attachment: fixed;
    background-repeat: no-repeat;
    color:white;
}

/* ===== CONTAINER ===== */
.container{
    width:85%;
    margin:40px auto;
}

/* ===== TITLE ===== */
h1{
    text-align:center;
    margin-bottom:30px;
}

/* ===== CARD ===== */
.card{
    background:rgba(255,255,255,0.12);
    padding:20px;
    margin-bottom:20px;
    border-radius:12px;
    backdrop-filter:blur(10px);
}

/* ===== STATUS ===== */
.status{
    padding:4px 10px;
    border-radius:8px;
    font-size:12px;
}

.pending{background:orange;}
.accepted{background:#4CAF50;}
.rejected{background:#ff4d4d;}

/* ===== BUTTONS ===== */
.btn{
    display:inline-block;
    padding:6px 12px;
    margin-top:10px;
    border-radius:6px;
    text-decoration:none;
    color:white;
    font-size:13px;
}

.accept{background:#4CAF50;}
.reject{background:#ff4d4d;}

/* ===== BACK ===== */
.back{
    display:block;
    text-align:center;
    margin-top:20px;
    color:#7CFF9A;
    text-decoration:none;
}

</style>
</head>

<body>

<div class="container">

<h1>🔔 Exchange Requests</h1>

<?php if(mysqli_num_rows($requests) == 0){ ?>
    <p style="text-align:center;">No requests found.</p>
<?php } ?>

<?php while($r = mysqli_fetch_assoc($requests)) { ?>

<div class="card">

    <p><strong>From:</strong> <?php echo $r['sender_name']; ?></p>
    <p><strong>To:</strong> <?php echo $r['receiver_name']; ?></p>

    <p><strong>Offers:</strong> <?php echo $r['offered_skill']; ?></p>
    <p><strong>Wants:</strong> <?php echo $r['wanted_skill']; ?></p>

    <p>
        <strong>Status:</strong> 
        <span class="status <?php echo $r['status']; ?>">
            <?php echo ucfirst($r['status']); ?>
        </span>
    </p>

    <?php if($r['receiver_id'] == $user_id && $r['status'] == 'pending') { ?>
        <a class="btn accept" href="?action=accept&id=<?php echo $r['request_id']; ?>">Accept</a>
        <a class="btn reject" href="?action=reject&id=<?php echo $r['request_id']; ?>">Reject</a>
    <?php } ?>

</div>

<?php } ?>

<a class="back" href="dashboard.php">⬅ Back to Dashboard</a>

</div>

</body>
</html>