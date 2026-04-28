<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$name = $_SESSION['name'];

/* Greeting */
$hour = date("H");
if($hour < 12){
    $greeting = "Good Morning";
}elseif($hour < 17){
    $greeting = "Good Afternoon";
}else{
    $greeting = "Good Evening";
}

/* STATS */
$total_skills = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT COUNT(*) AS total 
                        FROM user_skills 
                        WHERE user_id = $user_id")
)['total'] ?? 0;

$pending = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT COUNT(*) AS total 
                        FROM exchange_requests 
                        WHERE receiver_id = $user_id 
                        AND status='pending'")
)['total'] ?? 0;

$accepted = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT COUNT(*) AS total 
                        FROM exchange_requests 
                        WHERE (sender_id=$user_id 
                        OR receiver_id=$user_id)
                        AND status='accepted'")
)['total'] ?? 0;

/* RECENT */
$recent_query = mysqli_query($conn,"
    SELECT er.status,
           s1.skill_name AS offered_skill,
           s2.skill_name AS wanted_skill,
           u1.name AS sender_name
    FROM exchange_requests er
    JOIN skills s1 ON er.offered_skill_id = s1.skill_id
    JOIN skills s2 ON er.wanted_skill_id = s2.skill_id
    JOIN users u1 ON er.sender_id = u1.user_id
    WHERE er.sender_id = $user_id 
       OR er.receiver_id = $user_id
    ORDER BY er.request_id DESC
    LIMIT 5
");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Dashboard</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Segoe UI';}

body{
height:100vh;
overflow:hidden;
display:flex;
background:linear-gradient(135deg,#0f2027,#203a43,#2c5364);
color:white;
}

/* SIDEBAR */
.sidebar{
width:250px;
height:100vh;
background:rgba(255,255,255,0.08);
backdrop-filter:blur(15px);
padding:30px 20px;
display:flex;
flex-direction:column;
position:fixed;
}

.sidebar h2{
margin-bottom:40px;
color:#7CFF9A;
text-align:center;
}

.sidebar a{
color:white;
text-decoration:none;
padding:12px 15px;
margin-bottom:10px;
border-radius:10px;
transition:0.3s;
display:flex;
gap:10px;
}

.sidebar a:hover{
background:rgba(255,255,255,0.2);
}

.logout{
margin-top:auto;
background:#ff4d4d !important;
}

/* MAIN */
.main{
margin-left:250px;
height:100vh;
overflow-y:auto;
padding:40px;
width:100%;
}

/* TOP */
.topbar{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:40px;
}

.profile-circle{
width:45px;
height:45px;
border-radius:50%;
background:linear-gradient(135deg,#43e97b,#38f9d7);
display:flex;
justify-content:center;
align-items:center;
color:black;
font-weight:bold;
text-decoration:none;
cursor:pointer;
transition:0.3s;
}

.profile-circle:hover{
transform:scale(1.1);
box-shadow:0 0 15px rgba(67,233,123,0.6);
}

/* STATS */
.stats{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
gap:30px;
}

.stat-card{
background:rgba(255,255,255,0.1);
padding:35px;
border-radius:20px;
text-align:center;
transition:0.3s;
}

.stat-card:hover{transform:translateY(-8px);}

.stat-card i{
font-size:28px;
margin-bottom:10px;
color:#7CFF9A;
display:block;
}

.section{margin-top:60px;}

.activity-box{
background:rgba(255,255,255,0.1);
padding:25px;
border-radius:20px;
}

/* CATEGORY */
.category-grid{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(180px,1fr));
gap:25px;
margin-top:20px;
}

.category-card{
position:relative;
background:rgba(255,255,255,0.1);
padding:25px;
border-radius:18px;
text-align:center;
transition:0.3s;
}

.category-card:hover{
transform:translateY(-8px);
}

.cat-icon{
width:60px;
height:60px;
margin:auto;
margin-bottom:10px;
border-radius:50%;
background:linear-gradient(135deg,#43e97b,#38f9d7);
display:flex;
align-items:center;
justify-content:center;
color:black;
font-size:22px;
}

.skill-popup{
position:absolute;
bottom:110%;
left:50%;
transform:translateX(-50%);
background:#0f2027;
padding:10px;
border-radius:10px;
min-width:150px;
display:none;
box-shadow:0 10px 25px rgba(0,0,0,0.5);
}

.category-card:hover .skill-popup{
display:block;
}

.skill-item{
padding:5px;
font-size:13px;
border-bottom:1px solid rgba(255,255,255,0.1);
}

</style>
</head>

<body>

<div class="sidebar">
<h2>Skill Exchange</h2>

<a href="dashboard.php"><i class="fa fa-home"></i> Dashboard</a>
<a href="add_skill.php"><i class="fa fa-plus"></i> Add Skill</a>
<a href="search.php"><i class="fa fa-search"></i> Explore</a>
<a href="requests.php"><i class="fa fa-bell"></i> Requests</a>
<a href="chat.php"><i class="fa fa-comments"></i> Chat</a>
<a href="profile.php"><i class="fa fa-user"></i> Profile</a>

<a class="logout" href="../auth/logout.php">
<i class="fa fa-sign-out-alt"></i> Logout
</a>

</div>

<div class="main">

<div class="topbar">
<h1><?php echo $greeting.", ".$name; ?> 👋</h1>

<a href="profile.php" class="profile-circle">
<?php echo strtoupper(substr($name,0,1)); ?>
</a>

</div>

<div class="stats">
<div class="stat-card">
<i class="fa fa-book"></i>
<h3><?php echo $total_skills; ?></h3>
<p>Total Skills</p>
</div>

<div class="stat-card">
<i class="fa fa-clock"></i>
<h3><?php echo $pending; ?></h3>
<p>Pending Requests</p>
</div>

<div class="stat-card">
<i class="fa fa-check-circle"></i>
<h3><?php echo $accepted; ?></h3>
<p>Successful Exchanges</p>
</div>
</div>

<div class="section">
<h2>Recent Activity</h2>
<div class="activity-box">

<?php while($row=mysqli_fetch_assoc($recent_query)){ ?>

<div style="margin-bottom:15px;">
<strong><?php echo $row['sender_name']; ?></strong>
offered <b><?php echo $row['offered_skill']; ?></b>
for <b><?php echo $row['wanted_skill']; ?></b>
<br>
Status:
<span style="color:
<?php 
if($row['status']=='pending') echo 'orange';
elseif($row['status']=='accepted') echo '#7CFF9A';
else echo 'red';
?>">
<?php echo ucfirst($row['status']); ?>
</span>
</div>

<?php } ?>

</div>
</div>

<div class="section">
<h2>Skill Categories</h2>

<div class="category-grid">

<?php
$cat_query = mysqli_query($conn,"
SELECT s.category, COUNT(*) as total
FROM user_skills us
JOIN skills s ON us.skill_id = s.skill_id
WHERE us.user_id = $user_id
GROUP BY s.category
");

while($c = mysqli_fetch_assoc($cat_query)){

$cat = $c['category'];

$skill_list = mysqli_query($conn,"
SELECT s.skill_name
FROM user_skills us
JOIN skills s ON us.skill_id = s.skill_id
WHERE us.user_id=$user_id
AND s.category='$cat'
");
?>

<div class="category-card">

<div class="cat-icon">
<i class="fa fa-layer-group"></i>
</div>

<h3><?php echo $c['category']; ?></h3>
<p><?php echo $c['total']; ?> skills</p>

<div class="skill-popup">
<?php while($sk=mysqli_fetch_assoc($skill_list)){ ?>
<div class="skill-item"><?php echo $sk['skill_name']; ?></div>
<?php } ?>
</div>

</div>

<?php } ?>

</div>
</div>

</div>

</body>
</html>