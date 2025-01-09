<?php
require_once 'DB.php';

class C_themes {
    private $db;

    public function __construct() {
        $this->db = (new DB())->connect();
    }

    public function getThemes() {
        $sql = "SELECT * FROM themes";
        $result = $this->db->query($sql);

        if ($result->num_rows > 0) {
            $themes = [];
            while($row = $result->fetch_assoc()) {
                $themes[] = $row;
            }
            return $themes;
        } else {
            return [];
        }
    }
}
?>