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

        if(isset($_POST['submit'])){
            $firstname = $_POST['fname'];
            $lastname = $_POST['lname'];
            $department = $_POST['department'];
            $salary = $_POST['salary'];

            if(empty($firstname) || empty($lastname) || empty($department) || empty($salary)){
                echo "Empty Fields!";
            }else{
                $insert_sql = "INSERT INTO employee (first_name, last_name, department, salary) VALUES ('$firstname', '$lastname', '$department', '$salary')";
                $conn->query($insert_sql);
                echo "New Employee Added!<br>";
                header("location: ". $_SERVER['PHP_SELF']);
                exit();
            }

        }
        
        if(isset($_POST['delete'])){
            $employeeID = $_POST['id'];
            $delete_sql = "DELETE FROM employee WHERE id=$employeeID";
            $conn->query($delete_sql);
            echo "Employee deleted!<br>";
            header("location: ". $_SERVER['PHP_SELF']);
            exit();
        }
        
        if(isset($_POST['update'])){
            $employeeID = $_POST['id'];
            $newSalary = $_POST['salary'];
            $update_sql = "UPDATE employee SET salary='$newSalary'WHERE id=$employeeID";
            $conn->query($update_sql);
            echo "Employee Salary Updaed!<br>";
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
                                <form method='POST' style='display:inline;'>
                                    <input type='hidden' name='id' value='{$row['id']}'>
                                    <button type='submit' class='deleteButton' name='delete'>Delete</button>
                                </form>
                                <form method='POST' action='editUser.php'>
                                <button type='submit' name='update' class='updateButton'>Update</button>
                                </form>
                            </td>
                                
                        </tr>";
                    }

                }
        ?>
    </table>
    <a id="logout" href="logout.php">Logout</a>
</body>
</html>






