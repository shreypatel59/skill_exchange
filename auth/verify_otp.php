<?php
session_start();
$msg = "";

/* If OTP session not set → go back to login */
if (!isset($_SESSION['otp']) || !isset($_SESSION['temp_user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['verify'])) {

    $entered_otp = trim($_POST['otp']);

    if ($entered_otp == $_SESSION['otp']) {

        // ✅ SET PERMANENT LOGIN SESSION
        $_SESSION['user_id'] = $_SESSION['temp_user_id'];
        $_SESSION['name'] = $_SESSION['temp_name'];

        // ✅ REMOVE TEMP SESSIONS
        unset($_SESSION['otp']);
        unset($_SESSION['temp_user_id']);
        unset($_SESSION['temp_name']);
        unset($_SESSION['otp_email']);

        header("Location: ../user/dashboard.php");
        exit();

    } else {
        $msg = "Invalid OTP ❌";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Verify OTP</title>
<link rel="stylesheet" href="../assets/auth-style.css">
</head>
<body>

<div class="box">
    <h2>Enter OTP 🔐</h2>
    <p>We sent OTP to your email</p>

    <form method="post">
        <input type="number" name="otp" placeholder="Enter 6-digit OTP" required>
        <button name="verify">Verify OTP</button>
    </form>

    <span class="msg"><?= $msg ?></span>
</div>

</body>
</html>
