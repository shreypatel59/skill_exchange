<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$my_id = $_SESSION['user_id'];

$search = "";
if(isset($_GET['search'])){
    $search = $_GET['search'];
}

$query = mysqli_query($conn,"
SELECT us.*, u.name, u.user_id, s.skill_name, s.category
FROM user_skills us
JOIN users u ON us.user_id = u.user_id
JOIN skills s ON us.skill_id = s.skill_id
WHERE us.user_id != '$my_id'
AND s.skill_name LIKE '%$search%'
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Explore Skills</title>

<style>
body{
font-family:Segoe UI;
background:linear-gradient(135deg,#0f2027,#203a43,#2c5364);
color:white;
margin:0;
}

.container{
padding:40px;
}

.search input{
padding:12px;
width:300px;
border:none;
border-radius:10px;
}

.cards{
display:grid;
grid-template-columns:repeat(auto-fill,minmax(250px,1fr));
gap:20px;
margin-top:20px;
}

.card{
background:rgba(255,255,255,0.08);
padding:20px;
border-radius:15px;
}

button{
padding:8px 14px;
border:none;
border-radius:8px;
margin-top:10px;
cursor:pointer;
}

.request{background:#43e97b;color:black;}
.pending{background:orange;color:white;}
.accepted{background:#00c853;color:white;}
.rejected{background:red;color:white;}

.tag{
display:inline-block;
padding:4px 8px;
border-radius:6px;
font-size:12px;
margin-top:5px;
background:#38f9d7;
color:black;
}
</style>
</head>

<body>

<div class="container">

<h2>Explore Skills</h2>

<form class="search">
<input name="search" placeholder="Search skills..." value="<?php echo $search ?>">
</form>

<div class="cards">

<?php while($row=mysqli_fetch_assoc($query)){ ?>

<div class="card">

<h3><?php echo $row['name']; ?></h3>

<p><?php echo $row['skill_name']; ?></p>

<div class="tag">
<?php echo ucfirst($row['type']); ?>
</div>

<?php

$user_id = $row['user_id'];

$check = mysqli_query($conn,"
SELECT status FROM exchange_requests 
WHERE 
(sender_id='$my_id' AND receiver_id='$user_id')
OR
(sender_id='$user_id' AND receiver_id='$my_id')
LIMIT 1
");

if(mysqli_num_rows($check)>0){

$data = mysqli_fetch_assoc($check);

if($data['status']=="pending"){
echo "<button class='pending'>Pending</button>";
}
elseif($data['status']=="accepted"){
echo "<button class='accepted'>Accepted</button>";
}
else{
echo "<button class='rejected'>Rejected</button>";
}

}else{

echo "
<a href='send_request.php?user=".$user_id."'>
<button class='request'>Request Exchange</button>
</a>
";

}

?>

</div>

<?php } ?>

</div>
</div>

</body>
</html>