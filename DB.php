<?php

class DB {
    private $host = "localhost";
    private $username = "root"; // Change as needed
    private $password = ""; // Change as needed
    private $database = "location_vehicule";

    public function connect() {
        $conn = new mysqli($this->host, $this->username, $this->password, $this->database);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        return $conn;
    }
}
?> 