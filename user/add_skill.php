<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// SESSION FIX
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("../config/db.php");

// LOGIN CHECK
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$msg = "";

// ADD SKILL LOGIC
if (isset($_POST['add'])) {

    $skill_name = mysqli_real_escape_string($conn, $_POST['skill']);
    $type = $_POST['type'];
    $user_id = $_SESSION['user_id'];

    // Check skill exists
    $check = mysqli_query($conn,"SELECT skill_id FROM skills WHERE skill_name='$skill_name'");

    if(mysqli_num_rows($check) > 0){
        $row = mysqli_fetch_assoc($check);
        $skill_id = $row['skill_id'];
    } else {
        mysqli_query($conn,"INSERT INTO skills(skill_name, category) VALUES('$skill_name','General')");
        $skill_id = mysqli_insert_id($conn);
    }

    // Prevent duplicate
    $dup = mysqli_query($conn,"SELECT * FROM user_skills 
                              WHERE user_id=$user_id AND skill_id=$skill_id");

    if(mysqli_num_rows($dup) > 0){
        $msg = "⚠ Skill already added!";
    } else {
        mysqli_query($conn,"INSERT INTO user_skills(user_id, skill_id, type)
                            VALUES($user_id, $skill_id, '$type')");
        $msg = "✅ Skill added successfully!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Skill | Skill Exchange</title>

<style>

/* ===== BACKGROUND (MATCH YOUR SITE) ===== */
body{
    margin:0;
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    font-family:'Segoe UI';

    background:
        radial-gradient(circle at top left,#4caf50 0%,transparent 40%),
        radial-gradient(circle at bottom right,#0c2c45 0%,transparent 45%),
        linear-gradient(135deg,#0f2027,#203a43,#2c5364);

    color:#fff;
}

/* ===== BOX ===== */
.box{
    width:380px;
    padding:40px;
    border-radius:25px;
    background:rgba(255,255,255,0.12);
    backdrop-filter:blur(18px);
    box-shadow:0 20px 60px rgba(0,0,0,0.5);
    text-align:center;
}

/* ===== TITLE ===== */
h2{
    margin-bottom:20px;
}

/* ===== INPUT ===== */
input{
    width:100%;
    padding:14px;
    margin-bottom:15px;
    border:none;
    border-radius:10px;
    background:rgba(255,255,255,0.2);
    color:white;
    outline:none;
}

/* PLACEHOLDER FIX */
input::placeholder{
    color:#e0e0e0;
    opacity:1;
}

/* ===== SELECT FIX ===== */
select{
    width:100%;
    padding:14px;
    margin-bottom:15px;
    border:none;
    border-radius:10px;
    background:rgba(255,255,255,0.2);
    color:white;
    appearance:none;
    outline:none;

    background-image:url("data:image/svg+xml;utf8,<svg fill='white' height='20' viewBox='0 0 24 24' width='20'><path d='M7 10l5 5 5-5z'/></svg>");
    background-repeat:no-repeat;
    background-position:right 12px center;
}

/* DROPDOWN OPTIONS FIX */
select option{
    background:#203a43;
    color:white;
}

/* ===== BUTTON ===== */
button{
    width:100%;
    padding:14px;
    border:none;
    border-radius:30px;
    background:linear-gradient(135deg,#43e97b,#38f9d7);
    font-weight:bold;
    cursor:pointer;
    transition:0.3s;
}

button:hover{
    transform:translateY(-3px);
}

/* ===== MESSAGE ===== */
.msg{
    margin-top:10px;
    font-size:14px;
    color:#7CFF9A;
}

/* BACK LINK */
.back{
    display:block;
    margin-top:15px;
    color:#7CFF9A;
    text-decoration:none;
}

</style>
</head>

<body>

<div class="box">

    <h2>⚡ Add Skill</h2>

    <form method="POST">
        <input type="text" name="skill" placeholder="Enter skill..." required>

        <!-- FIXED SELECT -->
        <select name="type" required>
            <option value="" disabled selected>Select type</option>
            <option value="offer">I can teach</option>
            <option value="want">I want to learn</option>
        </select>

        <button name="add">Add Skill 🚀</button>
    </form>

    <div class="msg"><?php echo $msg; ?></div>

    <a class="back" href="dashboard.php">⬅ Back to Dashboard</a>

</div>

</body>
</html>