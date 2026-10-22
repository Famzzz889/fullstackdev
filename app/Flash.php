<?php
/**
 * Flash Messaging
 */
class Flash {
    public static function set($key, $message, $type = 'info') {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['flash'][$key] = [
            'message' => $message,
            'type' => $type
        ];
    }

    public static function get($key) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['flash'][$key])) {
            $flash = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $flash;
        }
        return null;
    }

    public static function has($key) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['flash'][$key]);
    }
}
