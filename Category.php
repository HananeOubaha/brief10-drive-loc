<?php
require_once 'DB.php';

class Category {
    private $conn;
    private $table_name = 'categories';

    public function __construct() {
        $database = new DB();
        $this->conn = $database->connect();
    }

    public function getCategories() {
        $query = 'SELECT * FROM ' . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>