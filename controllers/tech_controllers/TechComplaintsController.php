<?php

require_once './models/tech_models/TechComplaintsModel.php';

class TechComplaintsController
{
    private $TechComplaintsModel;

    public function __construct()
    {
        $this->TechComplaintsModel = new TechComplaintsModel();
    }

    public function handleRequest()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET':
                $this->getComplaints();
                break;
            case 'PUT':
                $this->updateComplaint();
                break;
            case 'DELETE':
                $this->deleteComplaint();
                break;
            default:
                http_response_code(404);
                echo json_encode(["status" => "error", "message" => "Nieprawidłowa metoda"]);
                break;
        }
    }

    private function getComplaints()
    {

        $result = $this->TechComplaintsModel->getComplaints();

        http_response_code(200);
        echo json_encode(["status" => "success", "data" => $result]);
    }

    private function updateComplaint()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        $complaint_id = $input['complaint_id'];
        $data = [
            'complaint_status' => $input['complaint_status'] ?? null,
            'complaints_return_message' => $input['complaints_return_message'] ?? null,
            'technician_id' => $input['technician_id'] ?? null
        ];

        $result = $this->TechComplaintsModel->updateComplaint($complaint_id, $data);

        if ($result) {
            http_response_code(200);
            echo json_encode(["status" => "success", "message" => "Reklamacja została zaktualizowana"]);
        } else {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Nie udało się zaktualizować reklamacji"]);
        }
    }

    private function deleteComplaint()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        $complaint_id = $input['complaint_id'];

        $result = $this->TechComplaintsModel->deleteComplaint($complaint_id);

        if ($result) {
            http_response_code(200);
            echo json_encode(["status" => "success", "message" => "Reklamacja została usunięta"]);
        } else {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Nie udało się usunąć reklamacji"]);
        }
    }
}
