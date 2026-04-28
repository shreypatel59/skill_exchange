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
$msg = "";

/* UPDATE PROFILE */
if (isset($_POST['update'])) {

    $name  = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $city  = mysqli_real_escape_string($conn, $_POST['city']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);

    mysqli_query($conn,"
        UPDATE users 
        SET name='$name', email='$email', city='$city', phone='$phone'
        WHERE user_id=$user_id
    ");

    $_SESSION['name'] = $name;
    $msg = "Profile updated successfully ✅";
}

/* FETCH USER */
$user = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT * FROM users WHERE user_id=$user_id
"));

/* FETCH USER SKILLS */
$skills = mysqli_query($conn,"
SELECT s.skill_name, us.type
FROM user_skills us
JOIN skills s ON us.skill_id = s.skill_id
WHERE us.user_id = $user_id
");
?>

<!DOCTYPE html>
<html>
<head>
<title>My Profile</title>

<style>

/* ===== BODY ===== */
body{
    margin:0;
    font-family:'Segoe UI';
    background:
        radial-gradient(circle at top left, rgba(76,175,80,0.4), transparent 40%),
        radial-gradient(circle at bottom right, rgba(12,44,69,0.5), transparent 50%),
        linear-gradient(135deg,#0f2027,#203a43,#2c5364);
    background-attachment:fixed;
    color:white;
}

/* ===== CONTAINER ===== */
.container{
    width:85%;
    margin:40px auto;
}

/* ===== PROFILE BOX ===== */
.profile-box{
    background:rgba(255,255,255,0.12);
    padding:30px;
    border-radius:15px;
    backdrop-filter:blur(12px);
}

/* ===== TITLE ===== */
h1{
    text-align:center;
}

/* ===== FORM ===== */
form{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:15px;
    margin-top:20px;
}

input{
    padding:12px;
    border:none;
    border-radius:10px;
    outline:none;
}

/* FULL WIDTH */
.full{
    grid-column:span 2;
}

/* BUTTON */
button{
    grid-column:span 2;
    padding:12px;
    border:none;
    border-radius:25px;
    background:#4CAF50;
    color:white;
    cursor:pointer;
}

/* MESSAGE */
.msg{
    text-align:center;
    margin-top:10px;
    color:#7CFF9A;
}

/* ===== SKILLS ===== */
.skills{
    margin-top:30px;
}

.skill-card{
    display:inline-block;
    background:rgba(255,255,255,0.15);
    padding:8px 15px;
    margin:5px;
    border-radius:20px;
    font-size:13px;
}

.offer{background:#4CAF50;}
.want{background:#ff9800;}

/* BACK */
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

<div class="profile-box">

<h1>👤 My Profile</h1>

<?php if($msg != "") echo "<p class='msg'>$msg</p>"; ?>

<form method="POST">

<input type="text" name="name" value="<?php echo $user['name']; ?>" required>

<input type="email" name="email" value="<?php echo $user['email']; ?>" required>

<input type="text" name="city" placeholder="City" value="<?php echo $user['city']; ?>">

<input type="text" name="phone" placeholder="Phone" value="<?php echo $user['phone']; ?>">

<button name="update">Update Profile</button>

</form>

<!-- SKILLS -->
<div class="skills">
<h3>My Skills</h3>

<?php while($s = mysqli_fetch_assoc($skills)) { ?>
    <span class="skill-card <?php echo $s['type']; ?>">
        <?php echo $s['skill_name']; ?> (<?php echo $s['type']; ?>)
    </span>
<?php } ?>

</div>

<a class="back" href="dashboard.php">⬅ Back to Dashboard</a>

</div>

</div>

</body>
</html>