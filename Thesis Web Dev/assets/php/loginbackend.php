<?php
    include('./connection.php');
    
    if (isset($_POST['submit'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM accounts WHERE `username` = '$username' AND `password` = '$password'";  
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $count = mysqli_num_rows($result); 
        $id = $row["id"];
        $position = $row["position"];
        
        if($count == 1){  
            if($position == "Admin"){
                header("Location: /Thesis Web Dev/index.html?id=$id");
            }
            elseif($position == "Teacher"){
                header("Location: /Thesis Web Dev/facultyindex.html?id=$id"); 
            }
            elseif($position == "Student"){
                header("Location: /Thesis Web Dev/studentindex.html?id=$id");
            }
        }
        else{  
            echo  '<script>
                        window.location.href = "index.php";
                        alert("Login failed. Invalid username or password!!")
                    </script>';
        }     
    }
?>