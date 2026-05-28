<?php
require_once 'classes.php';
$auth = new Auth();

$message = "";
$content = "";
$showContent = false;
$errMessage = false;

$currentUserName = $auth->userName();

if($currentUserName){
    $message = "Welcome " . htmlspecialchars($currentUserName);
} else {
    header("Location: Authorization.php");
    exit();
}

if(isset($_POST['Upbtn'])){
    try {
        // Message
        if(isset($_POST['UpMsg']) && !empty(trim($_POST['UpMsg']))){
            $messageHandler = new MessageHandler();
            $MessageData = $messageHandler->getMessageContent($_POST['UpMsg']);
            if($MessageData != null){
                $content = $MessageData;
                $showContent = true;
            }   
        }
    } catch (Exception $e) {
        $errMessage = $e->getMessage();
    }

    if(isset($_FILES['UpFile']) && $_FILES['UpFile']['error'] !== UPLOAD_ERR_NO_FILE){
        try {   
            //Files
            $fileHandler = new FileHandler();
            $fileData = $fileHandler->getFileContent($_FILES['UpFile']);
            if($fileData !== null){
                $content = $fileData;
                $showContent = true;
                $errMessage = false;
                }
        } catch(Exception $e) {
            $errMessage = $e->getMessage();
            if(empty($content)){
                $showContent = false;
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
    <title>BlogAli</title>
    <link rel="stylesheet" href="Style.css?v=1.2">
</head>
<body>
    <canvas class="matrix-bg" id="matrixCanvas"></canvas>


    <?php if($message != ""): ?>
        <div class="welcome-msg">
            <?php echo $message; ?>
        </div>
    <?php endif;?>
    <!-- Message -->
    <form action="" method="POST" enctype="multipart/form-data">    
            <input type="text" placeholder="Tell your story... " class="input-flag" name="UpMsg">
    <!-- FileUploader -->
            <input type="file" class="input-flag" name="UpFile">
            <button type="submit" name="Upbtn">Upload All</button>
    </form>
    

    <div class="container">
        <?php if ($errMessage): ?>
            <div class="alert-box" style="color: #ff3e3e; border: 1px solid #ff3e3e; padding: 15px; margin-bottom: 20px; background: rgba(255,0,0,0.1);">
                <span style="font-weight: bold;">[!] ERROR:</span> <?= htmlspecialchars($errMessage) ?>
            </div>
        <?php endif; ?>
    
        <?php if($showContent): ?>
            <div class="file-display">
                <h3>The uploaded content:</h3>
                <div class="content-box">
                    <?= nl2br(htmlspecialchars($content)) ?>
                </div>
            </div>
        <?php endif; ?>
    </div>


    <!-- Style requirment -->
    <script src="script.js"></script>
    
</body>
</html>
