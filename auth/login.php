<?php
session_start();
$conn = mysqli_connect("localhost","root","","skill_exchange");

$msg = "";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

if(isset($_POST['login'])){

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass  = $_POST['password'];

    $res = mysqli_query($conn,"SELECT * FROM users WHERE email='$email'");

    if(mysqli_num_rows($res)==1){

        $row = mysqli_fetch_assoc($res);

        if(password_verify($pass,$row['password'])){

            // ✅ Generate OTP
            $otp = rand(100000,999999);

            // ✅ Store temporary session
            $_SESSION['otp'] = $otp;
            $_SESSION['temp_user_id'] = $row['user_id'];  // IMPORTANT
            $_SESSION['temp_name'] = $row['name'];

            // ✅ Send OTP using PHPMailer
            $mail = new PHPMailer(true);

            try{
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'shreyvasudevpatel@gmail.com';   // YOUR GMAIL
                $mail->Password = 'zabwjwkornqrdhrc'; // APP PASSWORD (NO SPACE)
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('yourgmail@gmail.com','Skill Exchange');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'Your Login OTP - Skill Exchange';
                $mail->Body = "
                    <h2>Your OTP Code</h2>
                    <h1 style='color:#4CAF50;'>$otp</h1>
                    <p>This OTP is valid for login verification.</p>
                ";

                $mail->send();

                header("Location: verify_otp.php");
                exit();

            }catch(Exception $e){
                $msg = "Mailer Error: " . $mail->ErrorInfo;
            }

        }else{
            $msg = "Wrong password ❌";
        }

    }else{
        $msg = "Account not found ❌";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Login | Skill Exchange</title>
<link rel="stylesheet" href="../assets/auth-style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<div class="box">
    <h2>Welcome Back 👋</h2>
    <p>Login to continue 🚀</p>

    <form method="post">
        <input type="email" name="email" placeholder="Email Address" required>

        <div class="password-box">
            <input type="password" id="password" name="password" placeholder="Password" required>
            <span class="toggle" onclick="togglePassword('password', this)">
                <i class="fa-solid fa-eye"></i>
            </span>
        </div>

        <button name="login">Login</button>
    </form>

    <span class="msg"><?= $msg ?></span>

    <div class="link">
        New user? <a href="register.php">Create account ✨</a>
    </div>
</div>

<script>
function togglePassword(id, el){
    const field = document.getElementById(id);
    const icon = el.querySelector("i");

    if(field.type === "password"){
        field.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    }else{
        field.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
}
</script>

</body>
</html>
