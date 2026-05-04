<?php 
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['pending_booking'])) {
    die("No booking found");
}

$data = $_SESSION['pending_booking'];

$stmt = $conn->prepare("
INSERT INTO bookings (guest_id, room_id, check_in, check_out, status)
VALUES (?, ?, ?, ?, 'confirmed')
");

$stmt->bind_param(
    "iiss",
    $_SESSION['user_id'],
    $data['room_id'],
    $data['check_in'],
    $data['check_out']
);

$stmt->execute();

// clear session booking
unset($_SESSION['pending_booking']);

echo "Booking confirmed!";

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
SELECT b.*, r.number, r.type
FROM bookings b
JOIN rooms r ON b.room_id = r.id
WHERE b.guest_id = ?
");

$stmt->bind_param("i", $user_id);
$stmt->execute();

echo json_encode($stmt->get_result()->fetch_all(MYSQLI_ASSOC));
?>