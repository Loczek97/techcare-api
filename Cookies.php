<?php

function checkSessionId()
{
    // if (!isset($_SESSION['user'])) {
    //     error_log("Sesja użytkownika nie istnieje.");
    //     http_response_code(401);
    //     echo json_encode(['status' => 'error', 'message' => 'Unauthorized access']);
    //     exit();
    // }

    if (isset($_COOKIE['PHPSESSID'])) {
        setcookie('PHPSESSID', $_COOKIE['PHPSESSID'], time() + 1800, "/");
    }

    if (isset($_COOKIE['session_cookie'])) {
        setcookie('session_cookie', $_COOKIE['session_cookie'], time() + 1800, "/");
    }
}

function refreshCookie($cookieName, $cookieValue, $expiryTimeInMinutes = 30, $path = "/", $secure = false, $httpOnly = true)
{
    $expiryTime = time() + ($expiryTimeInMinutes * 60);
    setcookie($cookieName, $cookieValue, $expiryTime, $path, "", $secure, $httpOnly);
}
