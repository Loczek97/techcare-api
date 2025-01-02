<?php

class PermissionModel
{
    private $db;

    public function __construct()
    {
        $this->db = new DatabaseController();
    }

    public function getPermissions()
    {
        $query = "SELECT * FROM `permissions`";

        $permissions = $this->db->fetchAll($query);
        return $permissions;
    }

    public function postPermission($permission_name, $permission_level)
    {
        $query = "INSERT INTO `permissions` (`permission_name`, `permission_level`) VALUES (:permission_name, :permission_level)";

        $params = [
            ':permission_name' => $permission_name,
            ':permission_level' => $permission_level
        ];

        $this->db->execute($query, $params);
    }

    public function updatePermission($permission_id, $permission_name, $permission_level)
    {
        $query = "UPDATE `permissions` SET `permission_name` = :permission_name, permission_level = :permission_level WHERE `permission_id` = :permission_id";

        $params = [
            ':permission_id' => $permission_id,
            ':permission_name' => $permission_name,
            ':permission_level' => $permission_level
        ];

        $this->db->execute($query, $params);
    }

    public function deletePermission($permission_id)
    {
        $query = "DELETE FROM `permissions` WHERE `permission_id` = :permission_id";

        $params = [
            ':permission_id' => $permission_id
        ];

        $this->db->execute($query, $params);
    }
}
