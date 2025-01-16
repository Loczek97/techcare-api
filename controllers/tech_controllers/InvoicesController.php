<?php

require_once 'models/tech_models/InvoicesModel.php';

class InvoicesController
{
    private $InvoicesModel;

    public function __construct()
    {
        $this->InvoicesModel = new InvoicesModel();
    }

    public function handleRequest()
    {
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'POST') {
            $this->saveInvoice();
        } else {
            $this->sendErrorResponse("Nieprawidłowa metoda żądania.");
        }
    }

    private function saveInvoice()
    {
        if (!isset($_FILES['invoice']) || $_FILES['invoice']['error'] !== UPLOAD_ERR_OK) {
            $this->sendErrorResponse("Nie przesłano pliku lub wystąpił błąd.");
            return;
        }

        $fileTmpPath = $_FILES['invoice']['tmp_name'];
        $fileName = $_FILES['invoice']['name'];

        $uploadDir = __DIR__ . '/../../invoices/';
        $this->createDirectoryIfNotExists($uploadDir);

        $destPath = $uploadDir . basename($fileName);

        if ($this->moveFile($fileTmpPath, $destPath)) {
            $orderId = $this->extractOrderId($fileName);

            if ($orderId === null) {
                $this->sendErrorResponse("Nie udało się wyciągnąć ID zamówienia z nazwy pliku.");
                return;
            }

            $result = $this->InvoicesModel->saveInvoicePath($orderId, $destPath);

            if ($result) {
                $this->sendSuccessResponse("Faktura została zapisana.", ["file_path" => $destPath]);
            } else {
                $this->sendErrorResponse("Nie udało się zapisać ścieżki faktury w bazie danych.");
            }
        } else {
            $this->sendErrorResponse("Nie udało się przenieść pliku.");
        }
    }

    private function createDirectoryIfNotExists($dir)
    {
        if (!is_dir($dir)) {
            if (!mkdir($dir, 0777, true) && !is_dir($dir)) {
                $this->sendErrorResponse("Nie udało się utworzyć katalogu docelowego.");
                exit;
            }
        }
    }

    private function moveFile($tmpPath, $destPath)
    {
        return move_uploaded_file($tmpPath, $destPath);
    }

    private function extractOrderId($fileName)
    {
        $parts = explode('_', $fileName);
        return isset($parts[1]) ? intval($parts[1]) : null;
    }

    private function sendErrorResponse($message, $statusCode = 400)
    {
        http_response_code($statusCode);
        echo json_encode(["status" => "error", "message" => $message]);
    }

    private function sendSuccessResponse($message, $data = [])
    {
        echo json_encode(array_merge(["status" => "success", "message" => $message], $data));
    }
}
