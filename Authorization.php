<?php
require_once 'classes.php';
require_once 'DB.php';
session_start();

$message = "";
$color = "green";
$icon = "\u{2705}";

if (isset($_POST['submit_btn'])) {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $userModel = new UserModel($db);
    if (empty($username) || empty($password) || strlen($password) <= 7) {
        $message = "Invalid username or password!";
        $color = "red";
        $icon = "\u{274c}";
    } else {
        if ($userModel->findByUserName($username)) {

            if ($userModel->verifyLogin($username, $password)) {
                $user = new User($username, 0);
                Auth::login($user);
                header("Location: main.php");
                exit();
            } else {
                $message = "Wrong username or password!";
                $color = "red";
                $icon = "\u{274c}";
            }
        } else {
            if ($userModel->createNewAccount($username, $password)) {
                $user = new User($username, 0);
                Auth::login($user);
                header("Location: main.php");
                exit();
            } else {
                $message = "Could not create account!";
                $color = "red";
                $icon = "\u{274c}";
            }

        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authorization</title>
    <link rel="stylesheet" href="Style.css?v=1.4">
</head>
<body class="Auth-page">
    <canvas class="matrix-bg" id="matrixCanvas"></canvas>


    <div class="main-wrapper">
        <h2 class="brand-title" style="color: #4CAF50;">BlogAli</h2>
        <div class="form-container">
            <form class="auth-form" action="Authorization.php" method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="submit_btn">Register</button>
            </form>
            <?php if($message != ""):?>
             <div class="alert-box" style="color: <?php echo $color; ?>; border: 1px solid <?php echo $color; ?>;">
                <?php echo $icon . " " . $message; ?>
            </div>
            <?php endif;?>
        </div>
    </div>



    <!-- Style requirement -->
    <script src="script.js"></script>

</body>
</html>
