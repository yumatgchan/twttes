<?php
require_once '../config/database.php';

session_start();

if (!isset($_SESSION['guest_id'])) {
    echo json_encode([]);
    exit;
}

$guest_id = $_SESSION['guest_id'];

$conn = new mysqli("localhost", "root", "", "hotel");

$result = $conn->query("
  SELECT r.check_in, r.check_out, rm.number, rm.type
  FROM reservations r
  JOIN rooms rm ON r.room_id = rm.id
  WHERE r.guest_id = $guest_id
  ORDER BY r.check_in DESC
");

$bookings = [];

while ($row = $result->fetch_assoc()) {
    $bookings[] = $row;
}

echo json_encode($bookings);