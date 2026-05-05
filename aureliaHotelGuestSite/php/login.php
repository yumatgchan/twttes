<?php 
session_start();
require_once '../config/database.php';

$conn = new mysqli("localhost", "root", "", "hotel_system");
// after verifying user
$_SESSION['user_id'] = $user['id'];

// check if booking exists
if (isset($_SESSION['pending_booking'])) {
    header("Location: complete_booking.php");
    exit();

} ?>