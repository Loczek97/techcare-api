<?php
require_once './controllers/AuthController.php';
require_once './subrouters/UserRouter.php';
require_once './controllers/PublicController.php';
require_once './subrouters/TechRouter.php';
require_once './subrouters/AdminRouter.php';
require_once './CheckUserPermissions.php';
require_once 'Cookies.php';
require_once 'config.php';

header('Content-Type: application/json');

$url_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$url_parts = explode('/', trim($url_path, '/'));
$action = isset($url_parts[2]) ? $url_parts[2] : null;



if (isset($_COOKIE['session_cookie'])) {
    refreshCookie('session_cookie', $_COOKIE['session_cookie']);
}

if ($action !== 'login' && $action !== 'register' && $action !== 'logout' && $action !== 'public') {
    checkSessionId();
}

if ($action == 'tech' || $action == 'adm') {
    CheckUserPermission();
}



$AuthController = new AuthController();
$PublicController = new PublicController();
$AdminRouter = new AdminRouter();
$UserRouter = new UserRouter();
$TechRouter = new TechRouter();

switch ($action) {
    case 'login':
        $AuthController->handleLogin();
        break;

    case 'register':
        $AuthController->handleRegister();
        break;

    case 'logout':
        $AuthController->handleLogout();
        break;

    case 'usr':
        $UserRouter->handleRequest($url_parts[3]);
        break;
    case 'tech':
        $TechRouter->handleRequest($url_parts[3]);
        break;
    case 'admin':
        $AdminRouter->handleRequest($url_parts[3]);
        break;
    case 'public':
        $PublicController->handleRequest();
        break;

    default:
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Endpoint nie znaleziony']);
        break;
}
