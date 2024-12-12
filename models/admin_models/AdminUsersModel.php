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

    // Edycja uprawnień użytkownika - zmiana ogólnego poziomu uprawnień
    public function editPermissionsForUser($user_id, $current_permission_id, $new_permission_id)
    {
        try {
            // Rozpoczynamy transakcję
            $this->db->beginTransaction();

            // Sprawdzamy, czy użytkownik już ma to uprawnienie
            $sqlCheck = "SELECT * FROM user_permissions WHERE user_id = :user_id AND permission_id = :current_permission_id";
            $existingPermission = $this->db->fetch($sqlCheck, ['user_id' => $user_id, 'current_permission_id' => $current_permission_id]);

            // Jeśli użytkownik nie ma tego uprawnienia, zgłaszamy błąd
            if (!$existingPermission) {
                throw new Exception('Nie znaleziono uprawnienia, które chcesz edytować.');
            }

            // Zaktualizowanie uprawnienia w tabeli `user_permissions`
            $sqlUpdate = "UPDATE user_permissions SET permission_id = :new_permission_id WHERE user_id = :user_id AND permission_id = :current_permission_id";
            $update = $this->db->execute($sqlUpdate, [
                'user_id' => $user_id,
                'current_permission_id' => $current_permission_id,
                'new_permission_id' => $new_permission_id
            ]);

            if (!$update) {
                throw new Exception('Nie udało się zaktualizować uprawnienia w tabeli user_permissions.');
            }

            // Aktualizowanie głównego uprawnienia użytkownika w tabeli `users`
            $sqlUpdateUser = "UPDATE users SET permission_id = :new_permission_id WHERE user_id = :user_id";
            $updateUser = $this->db->execute($sqlUpdateUser, [
                'user_id' => $user_id,
                'new_permission_id' => $new_permission_id
            ]);

            if (!$updateUser) {
                throw new Exception('Nie udało się zaktualizować uprawnień użytkownika.');
            }

            // Zatwierdzamy transakcję
            $this->db->commit();

            return ['status' => 'success', 'message' => 'Uprawnienia zostały zaktualizowane.'];
        } catch (Exception $e) {
            // Jeśli coś poszło nie tak, wycofujemy transakcję
            $this->db->rollBack();
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }



    // usunięcie uprawnienia użytkownikowi - na przykład usunięcie dostępu do panelu inwentaryzacji
    public function deletePermissionFromUser($user_id, $permission_id)
    {
        $sql = "DELETE FROM user_permissions WHERE user_id = :user_id AND permission_id = :permission_id";

        $result = $this->db->execute($sql, ['user_id' => $user_id, 'permission_id' => $permission_id]);

        return $result;
    }
}
