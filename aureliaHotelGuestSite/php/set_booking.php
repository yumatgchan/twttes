<?php
require_once '../config/database.php';

session_start();

$data = json_decode(file_get_contents("php://input"), true);
$_SESSION['pending_booking'] = [
    'room_id' => $data['room_id'],
    'check_in' => $data['check_in'],
    'check_out' => $data['check_out']
];

echo json_encode(["success" => true]);
echo json_encode(["status" => "saved"]);

session_start();

if (!isset($_SESSION['guest_id'])) {
    echo json_encode(["error" => "Not logged in"]);
    exit;
}

$guest_id = $_SESSION['guest_id'];


$data = json_decode(file_get_contents("php://input"), true);

$room_id = $data['room_id'];
$check_in = $data['check_in'];
$check_out = $data['check_out'];

$conn = new mysqli("localhost", "root", "", "hotel");

$stmt = $conn->prepare("
  INSERT INTO reservations (guest_id, room_id, check_in, check_out)
  VALUES (?, ?, ?, ?)
");

$stmt->bind_param("iiss", $guest_id, $room_id, $check_in, $check_out);
$stmt->execute();

echo json_encode(["success" => true]);