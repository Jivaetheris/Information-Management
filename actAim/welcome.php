<?php 
session_start();
include 'connection.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}


$username = $_SESSION['username'];
// $firstName = "SELECT firstName from user WHERE $username = $_SESSION['username'];";

$query = $conn->prepare("SELECT firstName FROM user WHERE username = ?");
$query->bind_param("s", $username);
$query->execute();
$query->bind_result($firstName);
$query->fetch();
$query->close();


    $select_sql="SELECT id, firstName, lastName, contactNumber FROM user";
    $result = $conn->query($select_sql);

    if($_SERVER["REQUEST_METHOD"] == 'POST'){
        
        if(isset($_POST['delete'])){
            $userID = $_POST['id'];
            $delete_sql = "DELETE FROM user WHERE id=$userID";
            $conn->query($delete_sql);
            echo "User deleted!<br>";
            header("location: ". $_SERVER['PHP_SELF']);
            exit();
        }
        
        if(isset($_POST['update'])){
            $userID = $_POST['id'];
            $newFirstName = $_POST['fname'];
            $newLastName = $_POST['lname'];
            $newContact = $_POST['contactNumber'];
            $update_sql = "UPDATE user SET firstName='$newFirstName', lastName='$newLastName', contactNumber='$newContact' WHERE id=$userID";
            $conn->query($update_sql);
            echo "User Updaed!<br>";
            header("location: ". $_SERVER['PHP_SELF']);
            exit();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles/welcome.css">
</head>
<body>
    <h1>Welcome <?php echo htmlspecialchars($firstName)?> !</h1>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Contact Number</th>
            <th>Action</th>
        </tr>

        <?php 
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['firstName']}</td>
                            <td>{$row['lastName']}</td>
                            <td>{$row['contactNumber']}</td>
                            <td>
                                <form method='POST' style='display:inline;' action='welcome.php'>
                                    <input type='hidden' name='id' value='{$row['id']}'>
                                    <button type='submit' class='deleteButton' name='delete'>Delete</button>
                                </form>
                                <button type='button' 
                                    onclick='showUpdateForm(this)' 
                                    class='updateButton'
                                    data-id='{$row['id']}' 
                                    data-fname='{$row['firstName']}'
                                    data-lname='{$row['lastName']}'
                                    data-contact='{$row['contactNumber']}'>
                                    Update
                                </button>
                            </td>
                                
                        </tr>";
                    }

                }
        ?>
    </table>

    <div id="overlay" style="display:none;">
        <div id="editForm">
            <form action="" method="POST">
                <input type="hidden" name="id" id="edit-id">
                <input type="text" name="fname" id="edit-fname" placeholder="First Name" required><br>
                <input type="text" name="lname" id="edit-lname" placeholder="Last Name" required><br>
                <input type="number" name="contactNumber" id="edit-contact" placeholder="Contact Number" required><br>
                <button type="submit" name="update" class="insertButton">Update</button>
                <button style='padding: 8px 12px; background-color: pink; margin: 2px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; transition: background-color 0.3s ease;' type="button" onclick="hideUpdateForm()">Cancel</button>
            </form>
        </div>
        <script>
            function showUpdateForm(button) {
                document.getElementById('overlay').style.display = 'block';
                document.getElementById('edit-id').value = button.getAttribute('data-id');
                document.getElementById('edit-fname').value = button.getAttribute('data-fname');
                document.getElementById('edit-lname').value = button.getAttribute('data-lname');
                document.getElementById('edit-contact').value = button.getAttribute('data-contact');
            }

            function hideUpdateForm() {
                document.getElementById('overlay').style.display = 'none';
            }
        </script>
    </div>
    <a id="logout" href="logout.php">Logout</a>
</body>
</html>






