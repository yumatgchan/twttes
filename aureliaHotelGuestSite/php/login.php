<?php 
session_start();

// after verifying user
$_SESSION['user_id'] = $user['id'];

// check if booking exists
if (isset($_SESSION['pending_booking'])) {
    header("Location: complete_booking.php");
    exit();
} ?>