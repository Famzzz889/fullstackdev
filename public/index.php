<?php

// Autoload (manual because no composer)
require_once __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/helpers.php';
require_once __DIR__ . '/../app/Database.php';
require_once __DIR__ . '/../app/Auth.php';
require_once __DIR__ . '/../app/Csrf.php';
require_once __DIR__ . '/../app/Flash.php';
require_once __DIR__ . '/../app/Services/StockService.php';

// Start Session with security settings
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_httponly' => true,
        'cookie_samesite' => 'Lax',
        'use_only_cookies' => true,
    ]);
}

// Simple Router
$request = $_SERVER['REQUEST_URI'];
$scriptName = $_SERVER['SCRIPT_NAME'];
$basePath = str_replace('/index.php', '', $scriptName);
$path = str_replace($basePath, '', $request);
$path = parse_url($path, PHP_URL_PATH);
$path = trim($path, '/');

// Default route
if ($path === '') {
    $path = 'dashboard';
}

// Authentication Check
$publicRoutes = ['login'];
if (!Auth::check() && !in_array($path, $publicRoutes)) {
    redirect('login');
}

// Route Mapping
switch ($path) {
    case 'login':
        require_once __DIR__ . '/../app/Controllers/AuthController.php';
        $controller = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->login();
        } else {
            $controller->showLoginForm();
        }
        break;

    case 'logout':
        require_once __DIR__ . '/../app/Controllers/AuthController.php';
        $controller = new AuthController();
        $controller->logout();
        break;

    case 'dashboard':
        require_once __DIR__ . '/../app/Controllers/DashboardController.php';
        $controller = new DashboardController();
        $controller->index();
        break;

    case 'items':
        require_once __DIR__ . '/../app/Controllers/ItemController.php';
        $controller = new ItemController();
        $controller->index();
        break;

    case 'items/create':
        require_once __DIR__ . '/../app/Controllers/ItemController.php';
        $controller = new ItemController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->store();
        } else {
            $controller->create();
        }
        break;

    case 'items/history':
        require_once __DIR__ . '/../app/Controllers/ItemController.php';
        $controller = new ItemController();
        $controller->history();
        break;

    case 'items/edit':
        require_once __DIR__ . '/../app/Controllers/ItemController.php';
        $controller = new ItemController();
        $controller->edit();
        break;

    case 'items/update':
        require_once __DIR__ . '/../app/Controllers/ItemController.php';
        $controller = new ItemController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->update();
        }
        break;

    case 'items/delete':
        require_once __DIR__ . '/../app/Controllers/ItemController.php';
        $controller = new ItemController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->delete();
        }
        break;

    case 'items/opname':
        require_once __DIR__ . '/../app/Controllers/ItemController.php';
        $controller = new ItemController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->processOpname();
        } else {
            $controller->opname();
        }
        break;

    case 'stock/in':
        require_once __DIR__ . '/../app/Controllers/StockController.php';
        $controller = new StockController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->storeIn();
        } else {
            $controller->showInForm();
        }
        break;

    case 'stock/out':
        require_once __DIR__ . '/../app/Controllers/StockController.php';
        $controller = new StockController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->storeOut();
        } else {
            $controller->showOutForm();
        }
        break;

    case 'stock/correct':
        require_once __DIR__ . '/../app/Controllers/StockController.php';
        $controller = new StockController();
        $controller->correct();
        break;

    case 'reports':
        require_once __DIR__ . '/../app/Controllers/ReportController.php';
        $controller = new ReportController();
        $controller->index();
        break;

    case 'reports/consistency':
        require_once __DIR__ . '/../app/Controllers/ReportController.php';
        $controller = new ReportController();
        $controller->consistency();
        break;

    default:
        http_response_code(404);
        echo "404 - Halaman Tidak Ditemukan";
        break;
}
