<?php
require_once __DIR__ . '/../models/ComplaintModel.php';

class ComplaintController
{
    private $ComplaintModel;

    public function __construct()
    {
        $this->ComplaintModel = new ComplaintModel();
    }

    public function handleRequest()
    {
        $method = $_SERVER['REQUEST_METHOD'];

        switch ($method) {
            case 'POST':
                $this->addComplaint();
                break;
            case 'GET':
                $this->getUserComplaints();
                break;
            case 'PUT':
                $this->updateComplaintStatus();
                break;
            default:
                http_response_code(405);
                echo json_encode(['status' => 'error', 'message' => 'Metoda niedozwolona']);
        }
    }

    private function addComplaint()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!is_array($input) || !isset($input['order_id'], $input['complaint_description'])) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Nieprawidłowe dane wejściowe']);
            return;
        }

        $order_id = $input['order_id'];
        $complaint_description = $input['complaint_description'];

        if ($this->ComplaintModel->complaintExists($order_id)) {
            http_response_code(409);
            echo json_encode(['status' => 'error', 'message' => 'Reklamacja dla tego zlecenia już istnieje']);
            return;
        }

        $result = $this->ComplaintModel->addComplaint($order_id, $complaint_description);

        if ($result) {
            $complaint = $this->ComplaintModel->getLastInsertedComplaint();
            echo json_encode(['status' => 'success', 'message' => 'Reklamacja została dodana', 'data' => $complaint]);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Błąd podczas dodawania reklamacji']);
        }
    }




    private function getUserComplaints()
    {
        $user_id = $_SESSION["user"]["user_id"];
        $complaints = $this->ComplaintModel->getComplaintsByUser($user_id);


        if ($complaints) {
            echo json_encode(['status' => 'success', 'data' => $complaints]);
        } else {
            http_response_code(200);
            echo json_encode(['success' => 'error', 'message' => 'Brak reklamacji']);
        }
    }

    private function updateComplaintStatus()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!is_array($input) || !isset($input['complaint_id'], $input['complaint_status'])) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Nieprawidłowe dane wejściowe']);
            return;
        }

        $complaint_id = $input['complaint_id'];
        $complaint_status = $input['complaint_status'];

        $result = $this->ComplaintModel->updateComplaintStatus($complaint_id, $complaint_status);

        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Status reklamacji został zaktualizowany']);
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Błąd podczas aktualizacji reklamacji']);
        }
    }
}
