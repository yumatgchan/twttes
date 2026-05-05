<?php
require_once '../config/database.php';

$db = new Database();
$conn = $db->connect();

$id = $_GET['id'];
$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'GET') {
    $stmt = $conn->prepare("SELECT * FROM rooms WHERE id = ?");
    $stmt->execute([$id]);
    echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
}

if ($method == 'PUT') {
    $data = json_decode(file_get_contents("php://input"), true);

    $stmt = $conn->prepare("
        UPDATE rooms
        SET name=?, price=?, status=?
        WHERE id=?
    ");

    $stmt->execute([
        $data['name'],
        $data['price'],
        $data['status'],
        $id
    ]);

    echo json_encode(["success" => true]);
}

if ($method == 'DELETE') {
    $stmt = $conn->prepare("DELETE FROM rooms WHERE id=?");
    $stmt->execute([$id]);

    echo json_encode(["success" => true]);
}