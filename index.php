<?php
session_start();
require_once 'config.php';

if (isset($_SESSION['voter_id'])) {
    header("Location: vote.php");
    exit();
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $voter_id = mysqli_real_escape_string($conn, $_POST['voter_id']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    $sql = "SELECT * FROM voters WHERE voter_id = '$voter_id'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password'])) {
            $_SESSION['voter_id'] = $row['voter_id'];
            $_SESSION['voter_name'] = $row['name'];
            header("Location: vote.php");
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "Voter ID not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Voting System - Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="login-box">
            <h2>Voter Login</h2>
            <?php if($error) echo "<div class='error'>$error</div>"; ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label>Voter ID:</label>
                    <input type="text" name="voter_id" required>
                </div>
                <div class="form-group">
                    <label>Password:</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit">Login</button>
            </form>
            <div class="admin-link">
                <a href="admin/index.php">Admin Login</a>
            </div>
        </div>
    </div>
</body>
</html>