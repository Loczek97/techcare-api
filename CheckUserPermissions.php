<?php

function CheckUserPermission($user_id)
{
    $db = new DatabaseController();

    $sql = "
        SELECT p.permission_name 
        FROM permissions p
        LEFT JOIN user_permissions up ON up.permission_id = p.permission_id
        WHERE up.user_id = :user_id
    ";

    $result = $db->fetch($sql, [":user_id" => $user_id]);


    if ($result['permission_name'] != 'technik' && $result['permission_name'] != 'administrator') {
        http_response_code(403);
        echo json_encode(['status' => 'error', 'message' => 'Access denied']);;
        die();
    }
}
