<?php
require_once '../config/database.php';

session_start();
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "hotel_system");

$room_id = $_POST['room_id'] ?? null;
$check_in = $_POST['check_in'] ?? null;
$check_out = $_POST['check_out'] ?? null;

if (!$room_id || !$check_in || !$check_out) {
    echo json_encode(["error" => "Missing data"]);
    exit;
}

// store booking temporarily
$_SESSION['pending_booking'] = [
    "room_id" => $room_id,
    "check_in" => $check_in,
    "check_out" => $check_out
];

// check login
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["redirect" => "login"]);
    exit;
}

// save booking
$stmt = $conn->prepare("
    INSERT INTO bookings (guest_id, room_id, check_in, check_out)
    VALUES (?, ?, ?, ?)
");

$stmt->bind_param("iiss", $_SESSION['user_id'], $room_id, $check_in, $check_out);

if ($stmt->execute()) {
    unset($_SESSION['pending_booking']);
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["error" => $stmt->error]);
}