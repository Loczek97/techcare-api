<?php

require_once __DIR__ . './models/admin_models/PermissionModel.php';

class PermissionContorller
{
    private $PermissionModel;

    public function __construct()
    {
        $this->PermissionModel = new PermissionModel();
    }

    public function handleRequest()
    {
        $method = $_SERVER['REQUEST_METHOD'];

        switch ($method) {
            case 'GET':
                $this->getAllPermissions();
                break;
            default:
                echo json_encode(['status' => 'error', 'message' => 'Metoda niedozwolona']);
        }
    }

    private function getAllPermissions()
    {
        $result = $this->PermissionModel->getAllPermissions();

        if (!$result) {
            echo json_encode(['status' => 'error', 'message' => 'Nie znaleziono uprawnień']);
            return;
        }

        echo json_encode(['status' => 'success', 'data' => $result]);
    }
}
