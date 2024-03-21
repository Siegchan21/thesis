<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Login Page</title>
    <link rel="stylesheet" href="./styleindex.css?v=<?php echo time(); ?>">
</head>

<body>
    <div class="login-form">
        <h1></h1>
        <div class="container">
            <div class="main">
                <div class="content">
                    <h2>Login</h2>
                    <form action="loginbackend.php" method="POST">
                            <input type="text" name="username" placeholder="Username" required autofocus="">
                            <input type="text" name="password" placeholder="Password" required autofocus="">
                                <button class="btn" type="submit" name="submit">
                                    Login
                                </button>
                    </form>
                    <p class="account">Don't have an account? <a href="register.php">Register</a></p>
                </div>
                    <div class="form-img">
                        <img src="images/ccsbackground.jpg" alt="">
                    </div>
            </div>
        </div>
    </div>
</body>

</html>