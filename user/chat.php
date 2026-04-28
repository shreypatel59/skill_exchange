<?php
session_start();
include("../config/db.php");

$user_id = $_SESSION['user_id'];

mysqli_query($conn,"UPDATE users SET last_seen=NOW() WHERE user_id=$user_id");
?>

<!DOCTYPE html>
<html>
<head>
<title>Chat</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
body{
margin:0;
font-family:Segoe UI;
background:linear-gradient(135deg,#0f2027,#203a43,#2c5364);
color:white;
display:flex;
height:100vh;
}

.users{
width:280px;
background:rgba(255,255,255,0.05);
padding:20px;
overflow-y:auto;
}

.user{
padding:12px;
border-radius:10px;
margin-bottom:10px;
cursor:pointer;
background:rgba(255,255,255,0.08);
display:flex;
justify-content:space-between;
align-items:center;
}

.online{
width:8px;
height:8px;
background:#43e97b;
border-radius:50%;
}

.badge{
background:red;
padding:2px 6px;
border-radius:20px;
font-size:12px;
}

.chat{
flex:1;
display:flex;
flex-direction:column;
}

.header{
padding:15px;
background:rgba(255,255,255,0.08);
display:none;
}

.messages{
flex:1;
padding:20px;
overflow-y:auto;
}

.msg{
padding:10px;
border-radius:12px;
margin:8px 0;
max-width:60%;
}

.me{
background:#43e97b;
color:black;
margin-left:auto;
}

.other{
background:rgba(255,255,255,0.2);
}

.tick{
font-size:11px;
text-align:right;
margin-top:3px;
}

.send{
display:none;
padding:15px;
background:rgba(255,255,255,0.08);
gap:10px;
}

.send input{
flex:1;
padding:12px;
border:none;
border-radius:20px;
}

.send button{
padding:10px;
border:none;
border-radius:50%;
background:#43e97b;
cursor:pointer;
}

.empty{
flex:1;
display:flex;
justify-content:center;
align-items:center;
opacity:.6;
}
</style>
</head>

<body>

<div class="users">
<h3>Chats</h3>

<?php
$res = mysqli_query($conn,"
SELECT DISTINCT u.user_id,u.name,
TIMESTAMPDIFF(SECOND,u.last_seen,NOW()) as online,
(SELECT COUNT(*) FROM messages 
 WHERE sender_id=u.user_id 
 AND receiver_id=$user_id 
 AND seen=0) as unread
FROM users u
JOIN exchange_requests er 
ON (
(er.sender_id=$user_id AND er.receiver_id=u.user_id)
OR
(er.receiver_id=$user_id AND er.sender_id=u.user_id)
)
WHERE er.status='accepted'
");

while($u=mysqli_fetch_assoc($res)){
?>

<div class="user" onclick="openChat(<?php echo $u['user_id']?>,'<?php echo $u['name']?>')">

<div>
<?php echo $u['name']; ?>
<?php if($u['online'] < 10){ ?>
<span class="online"></span>
<?php } ?>
</div>

<?php if($u['unread']>0){ ?>
<div class="badge"><?php echo $u['unread']?></div>
<?php } ?>

</div>

<?php } ?>

</div>

<div class="chat">

<div class="header" id="header">
<h3 id="chatName"></h3>
</div>

<div class="messages" id="messages">
<div class="empty">Select user to chat</div>
</div>

<div class="send" id="sendBox">

<input type="file" id="file" hidden onchange="sendFile()">

<button onclick="document.getElementById('file').click()">
<i class="fa fa-paperclip"></i>
</button>

<input id="msg" placeholder="Type message..." onkeypress="enter(event)">

<button onclick="sendText()">
<i class="fa fa-paper-plane"></i>
</button>

</div>

</div>

<script>

let receiver=0;

function openChat(id,name){
receiver=id;

header.style.display="block";
sendBox.style.display="flex";

chatName.innerText=name;

load();
}

function load(){

if(receiver==0) return;

let box = document.getElementById("messages");

/* check scroll position */
let isNearBottom =
box.scrollTop + box.clientHeight >= box.scrollHeight - 50;

fetch("load_messages.php?user="+receiver)
.then(r=>r.text())
.then(d=>{

box.innerHTML = d;

/* only scroll if already bottom */
if(isNearBottom){
box.scrollTop = box.scrollHeight;
}

});

/* seen update */
fetch("seen.php?user="+receiver);
}

/* text send */
function sendText(){

let input = document.getElementById("msg");
let msg = input.value;

if(msg.trim()=="") return;

let form=new FormData();
form.append("msg",msg);
form.append("to",receiver);

fetch("send_message.php",{method:"POST",body:form})
.then(()=>{
input.value="";        // ✅ CLEAR INPUT
input.focus();         // ✅ keep cursor
load();                // reload chat
});

}

/* file send */
function sendFile(){

let file=document.getElementById("file").files[0];

let form=new FormData();
form.append("file",file);
form.append("msg","");
form.append("to",receiver);

fetch("send_message.php",{method:"POST",body:form})
.then(()=>{
document.getElementById("file").value="";
load();
});
}

function enter(e){
if(e.key==="Enter"){
e.preventDefault();   // stop new line
sendText();
}
}

setInterval(load,1000);

</script>

</body>
</html>