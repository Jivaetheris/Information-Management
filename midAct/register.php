<?php 
include 'connection.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $firstName = trim($_POST['firstname']);
    $lastName = trim($_POST['lastname']);
    $contact = trim($_POST['contact']);

    if (empty($username) || empty($password) || empty($firstName) || empty($lastName) || empty($contact)) {
        $message = "All fields are required!";
    } else {
        $checkUsername = $conn->prepare("SELECT username FROM user WHERE username = ?");
        $checkUsername->bind_param("s", $username);
        $checkUsername->execute();
        $checkUsername->store_result();

        if ($checkUsername->num_rows > 0) {
            $message = "Username already exists!";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO user (username, password, firstName, lastName, contactNumber) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssi", $username, $hashedPassword, $firstName, $lastName, $contact);

            if ($stmt->execute()) {
                echo "<script>alert('Registration Successful'); window.location='login.php';</script>";
                exit;
            } else {
                $message = "Error: " . htmlspecialchars($stmt->error);
            }

            $stmt->close();
        }

        $checkUsername->close();
    }
    
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="styles/register.css">
</head>
<body>
    <div id="container">
        <h1>Register</h1>
        <form action="" method="POST">
            <input type="text" name="username" placeholder="Enter Your Username" required><br><br>
            <input type="text" name="firstname" placeholder="First Name" required><br><br>
            <input type="text" name="lastname" placeholder="Last Name" required><br><br>
            <input type="number" class="contact" name="contact" placeholder="Contact Number" required><br><br>
            <input type="password" name="password" placeholder="Enter Your Password" required><br><br>
            <input type="submit" name="registration" value="Register"><br>
            <label>Already Have an Account?</label> <a href="login.php">Login</a>
        </form>
        <?php if (!empty($message)): ?>
            <p><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
    </div>
</body>
</html>