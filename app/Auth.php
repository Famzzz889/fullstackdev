<?php
/**
 * @var Database
 */
class Auth {
    public static function login($email, $password) {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start([
                    'cookie_httponly' => true,
                    'cookie_samesite' => 'Lax',
                    'use_only_cookies' => true,
                ]);
            }
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['nama'] = $user['nama'];
            return true;
        }
        return false;
    }

    public static function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
    }

    public static function check() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['user_id']);
    }

    public static function user() {
        if (!self::check()) return null;
        return [
            'id' => $_SESSION['user_id'],
            'email' => $_SESSION['email'],
            'nama' => $_SESSION['nama']
        ];
    }
}
