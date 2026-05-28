<?php
require_once 'classes.php';
session_start();

$message = "";
$color = "green";
$icon = "\u{2705}";

if(isset($_POST['submit_btn'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    if(!empty(trim($username)) && !empty(trim($password)) && strlen($password) > 7){
        $user = new User($username, 0);
        Auth::login($user);
        header("Location: main.php");
        exit();
    } else {    
        $message = "Something went wrong !";
        $color = "red";
        $icon = "\u{274c}";
    }
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authorization</title>
    <link rel="stylesheet" href="Style.css?v=1.3">
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



    <!-- Style requirment -->
    <script src="script.js"></script>

</body>
</html>
