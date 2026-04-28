<?php
session_start();
include("../config/db.php");

$from=$_SESSION['user_id'];
$to=$_POST['to'];
$msg=$_POST['msg'];

$type="text";
$file="";

if(isset($_FILES['file']) && $_FILES['file']['name']!=""){

$file=time()."_".$_FILES['file']['name'];

$target=__DIR__."/uploads/".$file;

move_uploaded_file($_FILES['file']['tmp_name'],$target);

$type="file";
}

mysqli_query($conn,"
INSERT INTO messages(sender_id,receiver_id,message,file,type)
VALUES('$from','$to','$msg','$file','$type')
");
?>