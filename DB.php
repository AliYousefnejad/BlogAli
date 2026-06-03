<?php

    $db = new mysqli('localhost','Raein', 'Mypass', 'BlogAli');
    if($db->connect_error){
        die("Connection failed: ". $db->connect_error);
    } 
    try {
    } catch (Exception $e) {
        
    }
?>
