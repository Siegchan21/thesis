<?php 
    include('./connection.php');

    $firstname = "";
    $middlename = "";
    $lastname = "";
    $birthday = "";
    $position = "";
    $admissionnumber = "";
    $username = "";
    $password = "";
    $successMessage = "";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $firstname = ucwords($_POST["firstname"]);
        $middlename = ucwords($_POST["middlename"]);
        $lastname = ucwords($_POST["lastname"]);
        $birthday = $_POST["birthday"];
        $position = $_POST["position"];
        $admissionnumber = $_POST["admissionnumber"];
        $username = $_POST["username"];
        $password = $_POST["password"];

        $sql = "INSERT INTO tbluser (`firstname`, `middlename`, `lastname`, `birthday`, `position`, `admissionNum`, `username`, `password`)
        VALUES ('$firstname', '$middlename', '$lastname', '$birthday', '$position', '$admissionnumber', '$username', '$password')";
        $result = $conn->query($sql);

        $firstname = "";
        $middlename = "";
        $lastname = "";
        $birthday = "";
        $position = "";
        $admissionnumber = "";
        $username = "";
        $password = "";
        $successMessage = "Account Registered Successfully";
    }

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Register Page</title>
    <link rel="stylesheet" href="../css/stylereg.css?v=<?php echo time(); ?>">
</head>

<body>
    <div class="register-form">
        <h1></h1>
        <div class="container">
            <div class="main">
                <div class="content">
                    <h2>Sign in</h2>
                    <?php
                    if ( !empty($successMessage) ) {
                        echo "
                        <br><br>
                        <div>
                            <h3 style='color: #2364A0; font-size: 20px;'>$successMessage</h3>
                        </div>
                        ";
                    }
                    ?>
                    <form method="POST">
                            <input type="text" name="firstname" placeholder="First Name" required autofocus="">
                            <input type="text" name="middlename" placeholder="Middle Name" required autofocus="">
                            <input type="text" name="lastname" placeholder="Last Name" required autofocus="">
                            <input type="text" name="birthday" placeholder="Birthday" required autofocus="">
                            <select name="position" id=""  required autofocus="">
                                <option value="" selected>Select Position</option>
                                <option value="Admin">Admin</option>
                                <option value="Teacher">Teacher</option>
                                <option value="Student">Student</option>
                            </select>
                            <input type="text" name="admissionnumber" placeholder="Admission Number" required autofocus="">
                            <input type="text" name="username" placeholder="Username" required autofocus="">
                            <input type="text" name="password" placeholder="Password" required autofocus="">
                                <button class="btn" type="submit">
                                    Register
                                </button>
                                <br><br>
                                <button class="btn" onclick="navigateToPage()">
                                    Back to Login
                                </button>
                    </form>
                </div>
                    <div class="form-img">
                        <img src="../images/ccsbackground.jpg" alt="">
                    </div>
                <script>
                        function navigateToPage() {
                        window.location.href = "index.php";
                        }
                </script>
            </div>
        </div>
    </div>
</body>

</html>
