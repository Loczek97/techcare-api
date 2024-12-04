<?php

function CheckUserPermission()
{
    // $db = new DatabaseController();

    // $input = json_decode(file_get_contents('php://input'), true);

    // echo json_decode($input['user_id']);

    // $sql = "SELECT p.permission_name 
    //         FROM permissions p 
    //         LEFT JOIN user_permissions up ON p.permission_id = up.permission_id 
    //         WHERE up.user_id = :user_id;";

    // $result = $db->fetchAll($sql, [":user_id" => $input['user_id']]);


    // if (empty($result)) {
    //     http_response_code(401);
    //     echo json_encode(['status' => 'error', 'message' => 'Unauthorized access']);
    //     exit();
    // }
}
