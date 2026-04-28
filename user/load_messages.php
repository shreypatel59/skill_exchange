<?php
session_start();
include("../config/db.php");

$me=$_SESSION['user_id'];
$you=$_GET['user'];

$res=mysqli_query($conn,"
SELECT * FROM messages
WHERE (sender_id=$me AND receiver_id=$you)
OR (sender_id=$you AND receiver_id=$me)
ORDER BY id ASC
");

while($row=mysqli_fetch_assoc($res)){

$class=($row['sender_id']==$me)?"me":"other";

echo "<div class='msg $class'>";

/* text */
if($row['type']=="text"){
echo $row['message'];
}

/* file */
if($row['type']=="file"){

$file=$row['file'];
$ext=strtolower(pathinfo($file,PATHINFO_EXTENSION));

if(in_array($ext,['jpg','png','jpeg','gif','webp'])){
echo "<br><img src='uploads/$file' style='max-width:200px;border-radius:10px'>";
}
else{
echo "<br><a href='uploads/$file' download>📄 Download File</a>";
}
}

/* ticks */
if($row['sender_id']==$me){

if($row['seen']==1){
echo "<div class='tick'>✓✓ Seen</div>";
}else{
echo "<div class='tick'>✓ Sent</div>";
}

}

echo "</div>";
}
?>