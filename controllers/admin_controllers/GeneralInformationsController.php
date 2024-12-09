<?php
require_once 'models/admin_models/GeneralInformationsModel.php';

class GeneralInformationsController
{
    private $GeneralInformationsModel;

    public function __construct()
    {
        $this->GeneralInformationsModel = new GeneralInformationsModel();
    }

    public function handleRequest()
    {
        $result = $this->getGeneralInformations();

        if ($result) {
            echo json_encode($result);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Nie udało się pobrać danych']);
        }
    }

    private function getGeneralInformations()
    {
        $result = $this->GeneralInformationsModel->getGeneralInformations();

        if ($result) {
            return [
                'status' => 'success',
                'data' => $result
            ];
        } else {
            return false;
        }
    }
}
