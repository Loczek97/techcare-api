<?php

class AdminUsersModel
{
    private $db;

    public function __construct()
    {
        $this->db = new DatabaseController();
    }

    // Pobranie wszystkich użytkowników
    public function getAllUsers()
    {
        $sql = "SELECT u.*, p.permission_name, p.permission_level
                FROM users u
                LEFT JOIN user_permissions up ON u.user_id = up.user_id
                LEFT JOIN permissions p ON up.permission_id = p.permission_id";

        $result = $this->db->fetchAll($sql);

        return $result ? $result : false;
    }

    // Dodanie uprawnienia użytkownikowi - na przykład dodanie dostępu do panelu inwentaryzacji
    public function addPermissionToUser($user_id, $permission_id)
    {
        $sql = "INSERT INTO user_permissions (user_id, permission_id) VALUES (:user_id, :permission_id)";

        $result = $this->db->execute($sql, ['user_id' => $user_id, 'permission_id' => $permission_id]);

        return $result;
    }

    public function editUser($user_id, $current_permission_id, $new_permission_id, $email = null, $phone = null, $address = null, $password = null)
    {
        try {
            $this->db->beginTransaction();

            $updateUserSql = "UPDATE users 
                          SET permission_id = :new_permission_id, 
                              email = COALESCE(:email, email), 
                              phone = COALESCE(:phone, phone), 
                              address = COALESCE(:address, address)";


            if ($password) {
                $updateUserSql .= ", password = :password";
            }

            $updateUserSql .= " WHERE user_id = :user_id";

            $params = [
                'new_permission_id' => $new_permission_id,
                'email' => $email,
                'phone' => $phone,
                'address' => $address,
                'user_id' => $user_id
            ];

            if ($password) {
                $params['password'] = password_hash($password, PASSWORD_BCRYPT);
            }

            $this->db->execute($updateUserSql, $params);

            $updatePermissionsSql = "UPDATE user_permissions 
                                 SET permission_id = :new_permission_id 
                                 WHERE permission_id = :current_permission_id 
                                 AND user_id = :user_id";

            $this->db->execute($updatePermissionsSql, [
                'user_id' => $user_id,
                'current_permission_id' => $current_permission_id,
                'new_permission_id' => $new_permission_id
            ]);

            $this->db->commit();

            $getEditedUserSql = "SELECT u.*, p.permission_name, p.permission_level
                             FROM users u
                             LEFT JOIN user_permissions up ON u.user_id = up.user_id
                             LEFT JOIN permissions p ON up.permission_id = p.permission_id
                             WHERE u.user_id = :user_id";

            $editedUser = $this->db->fetch($getEditedUserSql, ['user_id' => $user_id]);

            return $editedUser ? $editedUser : false;
        } catch (Exception $e) {
            $this->db->rollBack();
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }


    public function deletePermissionFromUser($user_id, $permission_id)
    {
        $sql = "DELETE FROM user_permissions WHERE user_id = :user_id AND permission_id = :permission_id";

        $result = $this->db->execute($sql, ['user_id' => $user_id, 'permission_id' => $permission_id]);

        return $result;
    }

    public function deleteUser($user_id)
    {
        $sql = "DELETE FROM users WHERE user_id = :user_id";

        $result = $this->db->execute($sql, ['user_id' => $user_id]);

        return $result;
    }
}
