<?php

class AdminMiddleware{
    public static function handle(){
    session_start();

    if (!isset($_SESSION['users'])){
        header("Location: /login.php");
        exit;
    }

    if ($_SESSION['users']['role'] !== 'admin'){
        http_response_code(403);
        echo "Access denied";
        exit();
        }
    }
}

