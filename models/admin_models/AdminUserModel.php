<?php

class AdminUserModel
{
    private $db;
    public function __construct()
    {
        $this->db = new DatabaseController();
    }

    public function getAllUsers()
    {
        $sql = "SELECT u.*, p.permission_name, p.permission_level from users u left join user_permissions up on u.user_id=up.user_id left JOIN permissions p ON up.permission_id=p.permission_id;";

        $result = $this->db->fetchAll($sql);

        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

    public function updateUser($user_id, $email, $permission_ids)
    {
        $sql = "SELECT * FROM users WHERE user_id = :user_id";
        $user = $this->db->fetch($sql, [':user_id' => $user_id]);

        if (!$user) {
            return false;
        }

        $sql = "UPDATE users SET = , email = :email WHERE user_id = :user_id";
        $params = [
            ':user_id' => $user_id,
            ':email' => $email
        ];

        $result = $this->db->execute($sql, $params);

        if (!$result) {
            return false;
        }

        $sql = "DELETE FROM user_permissions WHERE user_id = :user_id";
        $this->db->execute($sql, [':user_id' => $user_id]);

        foreach ($permission_ids as $permission_id) {
            $sql = "INSERT INTO user_permissions (user_id, permission_id) VALUES (:user_id, :permission_id)";
            $params = [
                ':user_id' => $user_id,
                ':permission_id' => $permission_id
            ];
            $this->db->execute($sql, $params);
        }

        return true;
    }
}
