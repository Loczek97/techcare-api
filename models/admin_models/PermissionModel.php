<?php

class PermissionModel
{
    private $db;

    public function __construct()
    {
        $this->db = new DatabaseController();
    }

    public function getAllPermissions()
    {
        $query = "SELECT * FROM permissions";
        $result = $this->db->fetchAll($query);

        return $result;
    }
}
