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

        switch ($method) {
            case 'POST':
                $this->saveInvoice();
                break;
            default:
                echo json_encode([
                    "status" => "error",
                    "message" => "Nieprawidłowa metoda żądania."
                ]);
        }
    }

    public function saveInvoice()
    {
        // Sprawdzanie metody żądania
        if (isset($_FILES['invoice']) && $_FILES['invoice']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['invoice']['tmp_name'];
            $fileName = $_FILES['invoice']['name'];

            $uploadDir = __DIR__ . '/../../invoices/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $destPath = $uploadDir . $fileName;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $orderId = $this->extractOrderId($fileName);

                // Zapis ścieżki do bazy danych
                $result = $this->InvoicesModel->saveInvoicePath($orderId, $destPath);

                if ($result) {
                    echo json_encode([
                        "status" => "success",
                        "message" => "Faktura została zapisana.",
                        "file_path" => $destPath
                    ]);
                } else {
                    echo json_encode([
                        "status" => "error",
                        "message" => "Nie udało się zapisać ścieżki faktury w bazie danych."
                    ]);
                }
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "Nie udało się przenieść pliku."
                ]);
            }
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Nie przesłano pliku lub wystąpił błąd."
            ]);
        }
    }

    private function extractOrderId($fileName)
    {
        // Przykład wyciągania order_id z nazwy pliku (np. faktura_14.pdf)
        $parts = explode('_', $fileName);
        return isset($parts[1]) ? intval($parts[1]) : null;
    }
}
