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
        unset($_SESSION['user_name']);
        session_destroy();
    }

    public function userName() : ?string {
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
