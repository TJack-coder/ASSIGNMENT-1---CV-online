<?php

class AdminMiddleware {
    public static function handle() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $bypassAdminAuth = true; // đổi thành false khi test xong

        if ($bypassAdminAuth) {
            if (!isset($_SESSION['users'])) {
                $_SESSION['users'] = [
                    'name' => 'Admin Test',
                    'role' => 'admin'
                ];
            }
            return;
        }

        if (!isset($_SESSION['users'])) {
            header("Location: /GROUP/public/index.php?route=login");
            exit;
        }

        if (!isset($_SESSION['users']['role']) || $_SESSION['users']['role'] !== 'admin') {
            http_response_code(403);
            echo "Access denied";
            exit;
        }
    }
}