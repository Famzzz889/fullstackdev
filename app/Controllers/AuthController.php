<?php

class AuthController {
    public function showLoginForm() {
        if (Auth::check()) {
            redirect('dashboard');
        }
        $config = require __DIR__ . '/../../app/config.php';
        $baseUrl = $config['app']['base_url'];
        require_once __DIR__ . '/../../views/auth/login.php';
    }

    public function login() {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $csrfToken = $_POST['csrf_token'] ?? '';

        if (!Csrf::validateToken($csrfToken)) {
            Flash::set('login_error', 'Invalid CSRF token', 'error');
            redirect('login');
        }

        if (Auth::login($email, $password)) {
            redirect('dashboard');
        } else {
            Flash::set('login_error', 'Email atau password salah', 'error');
            redirect('login');
        }
    }

    public function logout() {
        Auth::logout();
        redirect('login');
    }
}
