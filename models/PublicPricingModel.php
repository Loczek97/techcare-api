<?php

class PublicPricingModel
{
    private $db;

    public function __construct()
    {
        $this->db = new DatabaseController();
    }

    public function getPricing()
    {
        $result = $this->db->fetchAll("SELECT * FROM services");

        $normalizedResult = array_map(function ($item) {
            $item['price'] = isset($item['price']) ? (float) $item['price'] : 0;
            return $item;
        }, $result);

        echo json_encode($normalizedResult);
    }
}
