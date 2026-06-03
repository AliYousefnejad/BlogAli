<?php
require_once 'classes.php';
require 'DB.php';

session_start();
$userID = $_SESSION['user_id'] ?? '';
$userName = $_SESSION['user_name'] ?? '';

$auth = new Auth();
$currentUserName = $auth->userName();

if(!$currentUserName) {
    header("Location: Authorization.php");
    exit();
}
$message = "Welcome " . htmlspecialchars($currentUserName);
$cleanUserID = 0;
$stmt = $db->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param('s', $currentUserName);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $cleanUserID = (int)$row['id'];
}
$stmt->close();

if ($cleanUserID <= 0) {
    header("Location: Authorization.php");
    exit();
}

$showContent = false;
$errMessage = false;
$content = "";
$allMsg = [];
    

if(isset($_POST['mUpbtn'])){
    try {
            $messageHandler = new MessageHandler();
            $MessageData = $messageHandler->getMessageContent($_POST['UpMsg'] ?? '');

            if ($MessageData !== null) {
                $cleanUserID = 0;
                $stmt = $db->prepare("SELECT id FROM users WHERE username = ?");
                $stmt->bind_param('s', $currentUserName);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($row = $result->fetch_assoc()) {
                    $cleanUserID = (int)$row['id'];
                }
                $stmt->close();

                if($cleanUserID > 0){

                    $content = $MessageData;
                    $showContent = true;
                    
                    $title = "User Message";
                    $file_name = "";
                    $file_path = "";
                    $body = $MessageData;
                    
                    $stmt = $db->prepare("INSERT INTO posts (user_id, title, body, file_name, file_path) VALUES (?, ?, ?, ?, ?)");
                    $stmt->bind_param('issss', $cleanUserID, $title, $body, $file_name, $file_path);

                    if(!$stmt->execute()){
                        throw new Exception("Database error: ". $stmt->error);
                    }
                    $stmt->close();
                    } else {
                        throw new Exception("Invalid User ID. Please login again");
                    }
            } else {
                throw new Exception("Message is invalid.");
            }

        } catch (Exception $e) {
            $errMessage = $e->getMessage();
        }
}
if(isset($_POST['fUpbtn'])){
    try {
        if(!isset($_FILES['UpFile']) || $_FILES['UpFile']['error'] === UPLOAD_ERR_NO_FILE){
            throw new Exception("No file selected.");
        }

        if($_FILES['UpFile']['error'] !== UPLOAD_ERR_OK){
            throw new Exception("File upload error code: " . $_FILES['UpFile']['error']);
        }

        $uploadDir = __DIR__ . "/uploads/";
        if(!is_dir($uploadDir)){
            mkdir($uploadDir, 0755, true);
        }

        $originalName = basename($_FILES['UpFile']['name']);
        $safeName = preg_replace('/[^A-Za-z0-9._-]/', '_', $originalName);
        $finalName = time() . "_" . $safeName;

        $diskPath = $uploadDir . $finalName;
        $dbPath = "uploads/" . $finalName;

        if(!move_uploaded_file($_FILES['UpFile']['tmp_name'], $diskPath)){
            throw new Exception("Failed to move uploaded file.");
        }

        $title = "Uploaded File";
        $body = "";
        $file_name = $originalName;
        $file_path = $dbPath;

        $stmt = $db->prepare("INSERT INTO posts (user_id, title, body, file_name, file_path) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('issss', $cleanUserID, $title, $body, $file_name, $file_path);

        if(!$stmt->execute()){
            throw new Exception("Database error: " . $stmt->error);
        }
        $stmt->close();

        $content = "File uploaded successfully: " . $originalName;
        $showContent = true;
        $errMessage = false;

    } catch(Exception $e){
        $errMessage = $e->getMessage();
        $showContent = false;
    }
}

/* Show Last Posts */
$stm1 = $db->prepare("SELECT title, body, file_name, file_path FROM posts WHERE user_id = ? ORDER BY id DESC");
$stm1->bind_param('i', $cleanUserID);
$stm1->execute();
$result = $stm1->get_result();

$allMsg = [];
while($row = $result->fetch_assoc()){
    $allMsg[] = $row;
}
$stm1->close();



if(isset($_POST['logout'])){
    Auth::logout();
    header("Location: Authorization.php");
    exit();
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
            <button type="submit" name="mUpbtn">Upload Message</button>
    </form>
    <!-- FileUploader -->
    <form action="" method="POST" enctype="multipart/form-data">
            <input type="file" class="input-flag" name="UpFile">
            <button type="submit" name="fUpbtn">Upload File</button>
    </form>
    <form method="POST">
        <button type="submit" name="logout" class="logout-btn" >Logout</button>
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

       <h3>Your Story History:</h3>
        <?php if (!empty($allMsg)): ?>
            <?php foreach ($allMsg as $postItem): ?>
                <div class="message-box" style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
                    <?php if (!empty($postItem['file_path'])): ?>
                        <!--File-->
                        <p>File: <a href="<?= htmlspecialchars($postItem['file_path']) ?>" target="_blank">Download <?= htmlspecialchars($postItem['file_name']) ?></a></p>
                    <?php else: ?>
                        <!--Message-->
                        <h4><?= htmlspecialchars($postItem['title'] ?? 'Message') ?></h4>
                        <p><?= nl2br(htmlspecialchars($postItem['body'])) ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No history found.</p>
        <?php endif; ?>

    </div>


    <!-- Style requirement -->
    <script src="script.js"></script>
    
</body>
</html>
