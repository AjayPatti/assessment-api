<?php
class Contact {
    private $conn;
    private $table = "contacts";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $sql = "SELECT * FROM $this->table";
        return $this->conn->query($sql);
    }

    public function getById($id) {
        $sql = "SELECT * FROM $this->table WHERE id = $id";
        return $this->conn->query($sql);
    }

    public function create($name, $email, $phone) {
        $sql = "INSERT INTO $this->table (name, email, phone) VALUES ('$name', '$email', '$phone')";
        return $this->conn->query($sql);
    }

    public function update($id, $name, $email, $phone) {
        $sql = "UPDATE $this->table SET name='$name', email='$email', phone='$phone' WHERE id = $id";
        return $this->conn->query($sql);
    }

    public function delete($id) {
        $sql = "DELETE FROM $this->table WHERE id = $id";
        return $this->conn->query($sql);
    }
}
