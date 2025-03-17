<?php
session_start();
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        echo "<script>alert('Username and Password cannot be empty!');</script>";
        exit;
    }

    if (!$conn) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT id, username, password FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $db_username, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $db_username;
            header("Location: welcome.php");
            exit;
        } else {
            echo "<script>alert('Invalid password!');</script>";
        }
    } else {
        echo "<script>alert('No account found with that username!');</script>";
    }

    $stmt->close();
    $conn->close();
}

if (isset($_SESSION['username'])) {
    header("Location: welcome.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles/login.css">
</head>
<body>
    <div id="container">
        <form action="" method="POST">
            <h1>Login</h1>
            <input type="text" name="username" placeholder="Enter Your Username" required><br>
            <input type="password" name="password" placeholder="Enter Your Password" required><br>
            <input type="submit" name="login" value="Login"><br>
            <label>Don't have an account?</label> 
            <a href="register.php">Sign Up</a>
        </form>
    </div>
</body>
</html>