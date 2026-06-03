<?php
class User {
    function __construct(
        private string $name,
        private int $age)
    {}
    function getName() : string {
        return $this->name;
    }
    function getAge() : int {
        return $this->age;
    }
}

class UserModel {
    private mysqli $db;
    function __construct(mysqli $db) {
        $this->db = $db;
    }

    public function findByUserName(string $username) {
        $sql = "SELECT id, username, pass_hash FROM users WHERE username = ? LIMIT 1";
        $state = $this->db->prepare($sql);

        if(!$state){
            return null;
        } 

        $state->bind_param('s', $username);
        $state->execute();

        $result = $state->get_result();
        $user = $result->fetch_assoc();


        $state->close();

        return $user ?: null;
    }

    public function createNewAccount (string $username, string $pass) : bool {
        if($this->findByUserName($username)){
            return false;
        }

        $passHash = password_hash($pass, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (username, pass_hash) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);

        if(!$stmt){
            return false;
        }
        $stmt->bind_param('ss', $username, $passHash);
        $done = $stmt->execute();

        $stmt->close();

        return $done;
    }


    public function verifyLogin(string $username, string $pass) : bool {
        $user = $this->findByUserName($username);
        if(!$user){
            return false;
        }
        return password_verify($pass, $user['pass_hash']);
    }
   
}



class Auth {
    public static function login(User $user) : void {
        if(session_status() !== PHP_SESSION_ACTIVE){
            session_start();
        }
        $_SESSION['user_name'] = $user->getName();
    }

    public static function logout() : void {
        if(session_status() !== PHP_SESSION_ACTIVE){
            session_start();
        }
        $_SESSION = [];
        session_unset();
        session_destroy();
    }

    public static function userName() : ?string {
        if(session_status() !== PHP_SESSION_ACTIVE){
            session_start();
        }
        return $_SESSION['user_name'] ?? null;
    }
}

class MessageHandler {
    public function getMessageContent(?string $Message): ?string {
        try{
            if(!isset($Message) || empty(trim($Message))){
                throw new Exception("Empty Message Box !");
            } 
            return $Message;
        } catch(Exception $e) {
            throw $e;
        }   
    }
}

class FileHandler {
    public function getFileContent(array $file): string {
        try {
            if (!isset($file['tmp_name']) || $file['error'] !== 0) {
                throw new Exception("File upload failed or no file selected.");
            }

            $content = file_get_contents($file['tmp_name']);
            if ($content === false) {
                throw new Exception("The file is unreadable.");
            }

            return $content;
        } catch (Exception $e) {
            throw $e; 
        }
    }
}
?>
