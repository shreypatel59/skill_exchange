<?php
session_start();
include("../config/db.php");

$me=$_SESSION['user_id'];
$you=$_GET['user'];

mysqli_query($conn,"
UPDATE messages 
SET seen=1 
WHERE sender_id=$you 
AND receiver_id=$me
AND seen=0
");
?>