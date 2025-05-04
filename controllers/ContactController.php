<?php
require_once './models/Contact.php';

class ContactController {
    private $model;

    public function __construct($db) {
        $this->model = new Contact($db);
    }

    public function index() {
        $result = $this->model->getAll();
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
    }

    public function show($id) {
        $result = $this->model->getById($id);
        echo json_encode($result->fetch_assoc());
    }
    private function validate($data) {
        $errors = [];

        if (empty($data['name'])) {
            $errors[] = 'Name is required.';
        }

        if (empty($data['email'])) {
            $errors[] = 'Email is required.';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format.';
        }

        if (empty($data['phone'])) {
            $errors[] = 'Phone is required.';
        } elseif (!preg_match('/^[0-9]{10,15}$/', $data['phone'])) {
            $errors[] = 'Phone must be a valid number (10-15 digits).';
        }

        return $errors;
    }

    public function store($data) {
        $errors = $this->validate($data);

        if (!empty($errors)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'errors' => $errors]);
            return;
        }
        try {
            $name = htmlspecialchars(strip_tags($data['name']));
            $email = htmlspecialchars(strip_tags($data['email']));
            $phone = htmlspecialchars(strip_tags($data['phone']));

            $success = $this->model->create($name, $email, $phone);
            echo json_encode(['success' => $success]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Server error.']);
        }
    }

    public function update($id, $data) {
        $errors = $this->validate($data);

        if (!empty($errors)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'errors' => $errors]);
            return;
        }
        try {
            $name = htmlspecialchars(strip_tags($data['name']));
            $email = htmlspecialchars(strip_tags($data['email']));
            $phone = htmlspecialchars(strip_tags($data['phone']));

            $success = $this->model->update($id, $name, $email, $phone);
            echo json_encode(['success' => $success]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Server error.']);
        }
    }

    public function delete($id) {
        $success = $this->model->delete($id);
        echo json_encode(['success' => $success]);
    }
}
