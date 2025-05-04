<?php
header("Content-Type: application/json");

require_once './config/Database.php';
require_once './controllers/ContactController.php';

$db = (new Database())->connect();
$controller = new ContactController($db);

$method = $_SERVER['REQUEST_METHOD'];
$uri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
$resource = $uri[1] ?? null;
$id = $uri[2] ?? null;
if ($resource !== 'contacts') {
    http_response_code(404);
    echo json_encode(['message' => 'Resource not found']);
    exit;
}


switch ($method) {
    case 'GET':
        $id ? $controller->show($id) : $controller->index();
        break;
    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $controller->store($data);
        break;
    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);
        $controller->update($id, $data);
        break;
    case 'DELETE':
        $controller->delete($id);
        break;
    default:
        http_response_code(405);
        echo json_encode(['message' => 'Method not allowed']);
}
