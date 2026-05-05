<?php
require_once '../config/database.php';

$db = new Database();
$conn = $db->connect();

$from = $_GET['from'];
$to = $_GET['to'];

$stmt = $conn->prepare("
    SELECT * FROM rooms
    WHERE id NOT IN (
        SELECT room_id FROM bookings
        WHERE check_in < :to AND check_out > :from
    )
");

$stmt->execute([
    ':from' => $from,
    ':to' => $to
]);

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));