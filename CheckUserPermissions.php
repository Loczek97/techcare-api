<?php

function CheckUserPermission($permission_level)
{
    $db = new DatabaseController();

    $user_id = $_SESSION['user']['user_id'];

    $sql = "SELECT MAX(p.permission_level) AS max_permission_level 
            FROM permissions p 
            LEFT JOIN user_permissions up ON p.permission_id = up.permission_id 
            WHERE up.user_id = :user_id";

    $result = $db->fetch($sql, [":user_id" => $user_id]);


    $has_permission = $permission_level <= $result['max_permission_level'];

    return $has_permission;
}
