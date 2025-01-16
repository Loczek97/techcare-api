<?php

class AdminUsersModel
{
    private $db;

    public function __construct()
    {
        $this->db = new DatabaseController();
    }

    public function getAllUsers()
    {
        $sql = "SELECT u.*, p.permission_name, p.permission_level
                FROM users u
                LEFT JOIN user_permissions up ON u.user_id = up.user_id
                LEFT JOIN permissions p ON up.permission_id = p.permission_id";

        $result = $this->db->fetchAll($sql);

        return $result ? $result : false;
    }

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
                          SET email = COALESCE(:email, email), 
                              phone = COALESCE(:phone, phone), 
                              address = COALESCE(:address, address)";

            if ($password) {
                $updateUserSql .= ", password = :password";
            }
            $updateUserSql .= " WHERE user_id = :user_id";

            $params = [
                'email' => $email,
                'phone' => $phone,
                'address' => $address,
                'user_id' => $user_id,
            ];
            if ($password) {
                $params['password'] = password_hash($password, PASSWORD_BCRYPT);
            }

            $updatePermissionSql = "UPDATE users set permission_id=:new_permission_id WHERE user_id=:user_id;";

            $this->db->execute($updatePermissionSql, [
                'new_permission_id' => $new_permission_id,
                'user_id' => $user_id
            ]);

            $this->db->execute($updateUserSql, $params);

            $checkSql = "SELECT COUNT(*) AS count 
                     FROM user_permissions 
                     WHERE user_id = :user_id AND permission_id = :current_permission_id";
            $exists = $this->db->fetch($checkSql, [
                'user_id' => $user_id,
                'current_permission_id' => $current_permission_id
            ]);

            if ($exists['count'] == 0) {
                throw new Exception('Nie znaleziono odpowiedniego rekordu w tabeli user_permissions do aktualizacji.');
            }

            $updatePermissionsSql = "UPDATE user_permissions 
                                 SET permission_id = :new_permission_id 
                                 WHERE permission_id = :current_permission_id 
                                 AND user_id = :user_id";

            $this->db->execute($updatePermissionsSql, [
                'user_id' => $user_id,
                'current_permission_id' => $current_permission_id,
                'new_permission_id' => $new_permission_id
            ]);

            $getEditedUserSql = "SELECT u.*, p.permission_name, p.permission_level
                             FROM users u
                             LEFT JOIN user_permissions up ON u.user_id = up.user_id
                             LEFT JOIN permissions p ON up.permission_id = p.permission_id
                             WHERE u.user_id = :user_id";

            $editedUser = $this->db->fetch($getEditedUserSql, ['user_id' => $user_id]);

            $this->db->commit();

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
