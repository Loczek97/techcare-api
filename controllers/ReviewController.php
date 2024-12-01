<?php
require_once __DIR__ . '/../models/ReviewModel.php';

class ReviewController
{
    private $ReviewModel;

    public function __construct()
    {
        $this->ReviewModel = new ReviewModel();
    }

    public function handleRequest()
    {
        $method = $_SERVER['REQUEST_METHOD'];

        switch ($method) {
            case 'POST':
                $this->addRating();
                break;
            case 'GET':
                if (isset($_GET['rating_id'])) {
                    $this->getRating();
                } else {
                    $this->getUserRatings();
                }
                break;
            case 'PUT':
                $this->updateRating();
                break;
            case 'DELETE':
                $this->deleteRating();
                break;
            default:
                http_response_code(405);
                echo json_encode(['status' => 'error', 'message' => 'Metoda niedozwolona']);
        }
    }

    private function addRating()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!is_array($input) || !isset($input['user_id'], $input['order_id'], $input['rating_text'], $input['rating_score'])) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Nieprawidłowe dane wejściowe']);
            return;
        }

        $user_id = $input['user_id'];
        $order_id = $input['order_id'];
        $rating_text = $input['rating_text'];
        $rating_score = (int)$input['rating_score'];

        if ($rating_score < 1 || $rating_score > 5) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Ocena musi być w skali od 1 do 5']);
            return;
        }

        if (!$this->ReviewModel->isOrderCompleted($order_id)) {
            http_response_code(403);
            echo json_encode(['status' => 'error', 'message' => 'Można dodawać opinie tylko do zakończonych zleceń']);
            return;
        }

        if ($this->ReviewModel->hasOrderRating($order_id)) {
            http_response_code(409);
            echo json_encode(['status' => 'error', 'message' => 'Zamówienie już posiada opinię']);
            return;
        }

        $result = $this->ReviewModel->addRating($user_id, $order_id, $rating_text, $rating_score);

        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Ocena została dodana', 'data' => $result]);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Błąd podczas dodawania oceny']);
        }
    }

    private function getRating()
    {
        $rating_id = $_GET['rating_id'];
        $result = $this->ReviewModel->getRating($rating_id);

        if ($result) {
            echo json_encode(['status' => 'success', 'rating' => $result]);
        } else {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'Ocena nie została znaleziona']);
        }
    }

    private function getUserRatings()
    {
        if (!isset($_SESSION['user']['user_id'])) {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'Użytkownik nie jest zalogowany']);
            return;
        }

        $user_id = $_SESSION['user']['user_id'];
        $ratings = $this->ReviewModel->getRatingsByUser($user_id);

        if ($ratings) {
            echo json_encode(['status' => 'success', 'ratings' => $ratings]);
        } else {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'Brak ocen']);
        }
    }

    private function updateRating()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!is_array($input) || !isset($input['rating_id'], $input['rating_text'], $input['rating_score'])) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Nieprawidłowe dane wejściowe']);
            return;
        }

        $rating_id = $input['rating_id'];
        $rating_text = $input['rating_text'];
        $rating_score = (int)$input['rating_score'];

        if ($rating_score < 1 || $rating_score > 5) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Ocena musi być w skali od 1 do 5']);
            return;
        }

        $result = $this->ReviewModel->updateRating($rating_id, $rating_text, $rating_score);

        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Ocena została zaktualizowana']);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Błąd podczas aktualizacji oceny']);
        }
    }

    private function deleteRating()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!is_array($input) || !isset($input['rating_id'])) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Nieprawidłowe dane wejściowe']);
            return;
        }

        $rating_id = $input['rating_id'];
        $result = $this->ReviewModel->deleteRating($rating_id);

        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Ocena została usunięta']);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Błąd podczas usuwania oceny']);
        }
    }
}
