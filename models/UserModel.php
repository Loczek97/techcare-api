<?php
require_once __DIR__ . '/../DatabaseController.php';

class UserModel
{
    private $db;

    public function __construct()
    {
        $this->db = new DatabaseController();
    }

    public function getUser($user_id)
    {
        $query = "SELECT 
            u.user_id, 
            u.email, 
            u.first_name, 
            u.last_name, 
            u.phone, 
            u.address, 
            u.permission_id, 
            u.created_at, 
            u.updated_at,
            p.permission_name, 
            p.permission_level
        FROM users u
        LEFT JOIN permissions p ON u.permission_id = p.permission_id
        WHERE u.user_id = :user_id LIMIT 1";

        $result = $this->db->fetch($query, [':user_id' => $user_id]);

        if (!$result) {
            return ['status' => 'error', 'message' => 'Nie znaleziono użytkownika'];
        }

        $this->updateSession($result);

        return $result;
    }

    public function updateUser($user_id, $email, $phone, $password, $first_name, $last_name, $address)
    {
        $fieldsToUpdate = [];
        $params = [':user_id' => $user_id, ':updated_at' => date('Y-m-d H:i:s')];

        if ($email !== null) {
            $fieldsToUpdate[] = "email = :email";
            $params[':email'] = $email;
        }

        if ($phone !== null) {
            $fieldsToUpdate[] = "phone = :phone";
            $params[':phone'] = $phone;
        }

        if ($password !== null) {
            $fieldsToUpdate[] = "password = :password";
            $params[':password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        if ($first_name !== null) {
            $fieldsToUpdate[] = "first_name = :first_name";
            $params[':first_name'] = $first_name;
        }

        if ($last_name !== null) {
            $fieldsToUpdate[] = "last_name = :last_name";
            $params[':last_name'] = $last_name;
        }

        if ($address !== null) {
            $fieldsToUpdate[] = "address = :address";
            $params[':address'] = $address;
        }

        $fieldsToUpdateString = implode(', ', $fieldsToUpdate);

        $query = "UPDATE users SET $fieldsToUpdateString, updated_at = :updated_at WHERE user_id = :user_id";
        $result = $this->db->execute($query, $params);

        return $result ? ['status' => 'success', 'message' => 'Użytkownik zaktualizowany'] : ['status' => 'error', 'message' => 'Aktualizacja użytkownika nie powiodła się'];
    }

    public function deleteUser($user_id)
    {
        $query = "DELETE FROM users WHERE user_id = :user_id";
        $result = $this->db->execute($query, [':user_id' => $user_id]);

        if ($result) {
            unset($_SESSION['user']);
        }

        return $result ? ['status' => 'success', 'message' => 'Użytkownik został usunięty'] : ['status' => 'error', 'message' => 'Usunięcie użytkownika nie powiodło się'];
    }

    private function updateSession($userData)
    {
        $_SESSION['user'] = [
            'user_id' => $userData['user_id'],
            'email' => $userData['email'],
            'first_name' => $userData['first_name'],
            'last_name' => $userData['last_name'],
            'phone' => $userData['phone'],
            'address' => $userData['address'],
            'permission_id' => $userData['permission_id'],
            'permission_name' => $userData['permission_name'],
            'permission_level' => $userData['permission_level']
        ];
    }
}
