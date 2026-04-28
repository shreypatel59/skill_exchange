<?php
$conn = mysqli_connect("localhost","root","","skill_exchange");
$msg = "";

if(isset($_POST['register'])){
    $name  = mysqli_real_escape_string($conn,$_POST['name']);
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $pass  = $_POST['password'];
    $cpass = $_POST['confirm_password'];

    if($pass !== $cpass){
        $msg = "Passwords do not match ❌";
    } else {

        $hash = password_hash($pass, PASSWORD_DEFAULT);

        $check = mysqli_query($conn,"SELECT * FROM users WHERE email='$email'");

        if(mysqli_num_rows($check) > 0){
            $msg = "Email already registered ❌";
        } else {

            mysqli_query($conn,"INSERT INTO users(name,email,password) 
                                VALUES('$name','$email','$hash')");

            header("Location: login.php");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Register | Skill Exchange</title>
<link rel="stylesheet" href="../assets/auth-style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<div class="box">
    <h2>Create Account ✨</h2>
    <p>Join Skill Exchange and grow together 🌱</p>

    <form method="post">
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email Address" required>

        <div class="password-box">
            <input type="password" id="password" name="password" placeholder="Password" required>
            <span class="toggle" onclick="togglePassword('password', this)">
                <i class="fa-solid fa-eye"></i>
            </span>
        </div>

        <div class="password-box">
            <input type="password" id="cpassword" name="confirm_password" placeholder="Confirm Password" required>
            <span class="toggle" onclick="togglePassword('cpassword', this)">
                <i class="fa-solid fa-eye"></i>
            </span>
        </div>

        <button name="register">Register</button>
    </form>

    <span class="msg"><?php echo $msg; ?></span>

    <div class="link">
        Already have an account? <a href="login.php">Login 🔑</a>
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
