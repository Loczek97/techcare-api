<?php
// Konfiguracja ciasteczek sesji
@ini_set('session.cookie_secure', 0); // Ustaw 1, jeśli używasz HTTPS
@ini_set('session.cookie_httponly', 1); // Zabezpieczenie przed XSS
@ini_set('session.cookie_samesite', 'Lax'); // Lub 'None', jeśli działasz w środowisku CORS

session_set_cookie_params([
    'lifetime' => 3600,          // Czas życia ciasteczka w sekundach
    'path' => '/',               // Dostępne na całym serwerze
    'secure' => false,           // Zmień na true w przypadku HTTPS
    'httponly' => true,          // Ciasteczko tylko przez HTTP, chroni przed XSS
    'samesite' => 'Lax',         // 'Lax' dla większości przypadków, 'None' dla CORS i HTTPS
]);

// Uruchomienie sesji
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


error_reporting(E_ALL); // Wyświetlaj wszystkie błędy
ini_set('display_errors', 1); // Włącz wyświetlanie błędów

// Konfiguracja nagłówków CORS
header("Access-Control-Allow-Origin: http://localhost:5173"); // Adres Twojego frontendu
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true");

// Obsługa preflight dla CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204); // Zwracamy status 204 (brak treści)
    exit();
}
