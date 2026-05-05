<?php
require_once '../config/database.php';

$db = new Database();
$conn = $db->connect();

header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'GET') {

    $stmt = $conn->prepare("SELECT * FROM rooms");
    $stmt->execute();

    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

if ($method == 'POST') {

    $data = json_decode(file_get_contents("php://input"), true);

    $stmt = $conn->prepare("
        INSERT INTO rooms (name, price, status)
        VALUES (:name, :price, :status)
    ");

    $stmt->execute([
        ':name' => $data['name'],
        ':price' => $data['price'],
        ':status' => $data['status']
    ]);

    echo json_encode(["success" => true]);
}