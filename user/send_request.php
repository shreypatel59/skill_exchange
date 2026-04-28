<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['user_id'])){
header("Location: ../auth/login.php");
exit;
}

$sender = $_SESSION['user_id'];
$receiver = $_GET['user_id'];
$skill_id = $_GET['skill_id'];

/* for now we keep wanted same */
mysqli_query($conn,"
INSERT INTO exchange_requests
(sender_id,receiver_id,offered_skill_id,wanted_skill_id,status)
VALUES
($sender,$receiver,$skill_id,$skill_id,'pending')
");

header("Location: search.php");
exit;
?>