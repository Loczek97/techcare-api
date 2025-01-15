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


        $result = $this->PermissionModel->postPermission($permission_name, $permission_level);

        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Uprawnienie zostało dodane']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Wystąpił błąd podczas dodawania uprawnienia']);
        }
    }

    private function updatePermission()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $permission_id = $input['permission_id'];
        $permission_name = $input['permission_name'];
        $permission_level = $input['permission_level'];

        $result = $this->PermissionModel->updatePermission($permission_id, $permission_name, $permission_level);

        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Uprawnienie zostało zaktualizowane']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Wystąpił błąd podczas aktualizacji uprawnienia']);
        }
    }


    private function deletePermission()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $permission_id = $input['permission_id'];

        $result = $this->PermissionModel->deletePermission($permission_id);

        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Uprawnienie zostało usunięte']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Wystąpił błąd podczas usuwania uprawnienia']);
        }
    }
}
