<?php

require_once 'models/admin_models/PermissionModel.php';

class PermissionController
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
            case 'POST':
                $this->postPermission();
                break;
            case 'PUT':
                $this->updatePermission();
                break;
            case 'DELETE':
                $this->deletePermission();
                break;
            default:
                echo json_encode(['status' => 'error', 'message' => 'Metoda niedozwolona']);
        }
    }

    private function getAllPermissions()
    {
        $result = $this->PermissionModel->getPermissions();

        echo json_encode(['status' => 'success', 'data' => $result]);
    }

    private function postPermission()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $permission_name = $input['permission_name'];
        $permission_level = $input['permission_level'];

        $this->PermissionModel->postPermission($permission_name, $permission_level);

        echo json_encode(['status' => 'success', 'message' => 'Uprawnienie zostało dodane']);
    }

    private function updatePermission()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $permission_id = $input['permission_id'];
        $permission_name = $input['permission_name'];
        $permission_level = $input['permission_level'];

        $this->PermissionModel->updatePermission($permission_id, $permission_name, $permission_level);

        echo json_encode(['status' => 'success', 'message' => 'Uprawnienie zostało zaktualizowane']);
    }

    private function deletePermission()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $permission_id = $input['permission_id'];

        $this->PermissionModel->deletePermission($permission_id);

        echo json_encode(['status' => 'success', 'message' => 'Uprawnienie zostało usunięte']);
    }
}
