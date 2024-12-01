<?php
require_once __DIR__ . '/../DatabaseController.php';

class AuthModel
{
    private $db;

    public function __construct()
    {
        $this->db = new DatabaseController();
    }

    public function login($email, $password)
    {
        $query = "SELECT 
            u.user_id, 
            u.email, 
            u.first_name, 
            u.last_name, 
            u.phone, 
            u.address, 
            u.password,  
            u.created_at, 
            u.updated_at, 
            u.permission_id, 
            p.permission_level, 
            p.permission_name
        FROM users u
        LEFT JOIN permissions p ON u.permission_id = p.permission_id
        WHERE u.email = :email 
        LIMIT 1";

        $result = $this->db->fetch($query, [':email' => $email]);

        if (!$result || !password_verify($password, $result['password'])) {
            return null;
        }

        return [
            'user_id' => $result['user_id'],
            'email' => $result['email'],
            'first_name' => $result['first_name'],
            'last_name' => $result['last_name'],
            'phone' => $result['phone'],
            'address' => $result['address'],
            'created_at' => $result['created_at'],
            'updated_at' => $result['updated_at'],
            'permission_level' => $result['permission_level'],
            'permission_name' => $result['permission_name']
        ];
    }

    public function register($email, $password, $password2, $first_name, $last_name, $phone = null, $address = null)
    {
        if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($password2)) {
            return ['status' => 'error', 'message' => 'Wszystkie pola są wymagane.'];
        }

        if ($password !== $password2) {
            return ['status' => 'error', 'message' => 'Hasła nie są zgodne.'];
        }

        if ($this->checkIfUserExists($email)) {
            return ['status' => 'error', 'message' => 'Użytkownik z tym adresem e-mail już istnieje.'];
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $params = [
            ':first_name' => $first_name,
            ':last_name' => $last_name,
            ':email' => $email,
            ':password' => $hashedPassword,
            ':created_at' => date('Y-m-d H:i:s'),
            ':updated_at' => date('Y-m-d H:i:s'),
            ':permission_id' => 1
        ];

        $query = "INSERT INTO users (first_name, last_name, email, password, created_at, updated_at, permission_id";
        if ($phone) {
            $query .= ", phone";
            $params[':phone'] = $phone;
        }
        if ($address) {
            $query .= ", address";
            $params[':address'] = $address;
        }
        $query .= ") VALUES (:first_name, :last_name, :email, :password, :created_at, :updated_at, :permission_id";
        if ($phone) {
            $query .= ", :phone";
        }
        if ($address) {
            $query .= ", :address";
        }
        $query .= ")";

        $result = $this->db->execute($query, $params);

        return $result ? ['status' => 'success', 'message' => 'Użytkownik został pomyślnie zarejestrowany.'] : ['status' => 'error', 'message' => 'Rejestracja użytkownika nie powiodła się.'];
    }


    private function checkIfUserExists($email)
    {
        $query = "SELECT email FROM users WHERE email = :email";
        $result = $this->db->fetch($query, [':email' => $email]);
        return !empty($result);
    }
}
