<?php
    include "connection.php";

    $select_sql="SELECT * FROM employee";
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
    <style>
        button{
            border: 1px solid black;
            border-radius: 3px;
        }

        .updateButton {
            background-color: LawnGreen;
        }
        .deleteButton {
            background-color: LightCoral;
        }
        .insertButton {
            background-color: Aquamarine;
        }
    </style>
</head>
<body>
    <h2>Insert Employee</h2>
    <form action="" method="POST">
        <input type="text" name="fname" placeholder="First Name"><br>
        <input type="text" name="lname" placeholder="Last Name"><br>
        <input type="text" name="department" placeholder="Department"><br>
        <input type="text" name="salary" placeholder="Salary"><br><br>
        <button type="submit" name="submit" class="insertButton">Insert</button>
    </form>
    <h2>Employee</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Department</th>
            <th>Salary</th>
            <th>Action</th>
        </tr>

        <?php 
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['first_name']}</td>
                            <td>{$row['last_name']}</td>
                            <td>{$row['department']}</td>
                            <td>{$row['salary']}</td>
                            <td>
                                <form method='POST' style='display:inline;'>
                                    <input type='hidden' name='id' value='{$row['id']}'>
                                    <button type='submit' class='deleteButton' name='delete'>Delete</button>
                                </form>
                                <form method='POST'>
                                <button type='submit' name='update' class='updateButton'>Update</button>
                                    <input type='hidden' name='id' value='{$row['id']}'>
                                    <input type='number' name='salary' value='{$row['salary']}'>
                                    
                                </form>
                            </td>
                                
                        </tr>";
                    }

                }
        ?>
    </table>
    
</body>
</html>