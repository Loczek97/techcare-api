<?php

class GeneralInformationsModel
{
    private $db;

    public function __construct()
    {
        // Zakładam, że masz już zainicjowany obiekt bazy danych
        $this->db = new DatabaseController();
    }

    public function getGeneralInformations()
    {
        $sql = 'SELECT status, COUNT(*) AS count FROM orders GROUP BY status';
        $result = $this->db->fetchAll($sql);

        if ($result) {
            return $result;
        } else {
            return false;
        }
    }
}
