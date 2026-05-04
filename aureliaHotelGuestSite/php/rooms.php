<?php 
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hotel_system";

$conn= new mysqli($servername,$username,$password,$dbname);

if ($conn-> connect_error){
    die("connection failed and my fingers hurt bro:".$conn->connect_error);

}

$sql="SELECT * FROM rooms";
$result = $conn->query($sql);

while ($row= $result-> fetch_assoc()){
    echo "ID: ".$row["ID"] ." || Name: ". $row["name"]." || Type: ". $row["type"]." || Status: ".$row["status"]." || Price: ". $row["price"];    
    echo "<br>";
}
$conn->close();

//---------------------------------------------------------------------------------------------------------------------------------------------

// rooms.php
require 'database.php';

$result = $conn->query("SELECT * FROM rooms");
echo json_encode($result->fetch_all(MYSQLI_ASSOC));

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM rooms WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

echo json_encode($stmt->get_result()->fetch_assoc());
$data = json_decode(file_get_contents("php://input"), true);

$stmt = $conn->prepare("INSERT INTO rooms (number, type, price, status) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssds", $data['number'], $data['type'], $data['price'], $data['status']);
$stmt->execute();

echo json_encode(["message" => "Room created"]);

$data = json_decode(file_get_contents("php://input"), true);
$id = $_GET['id'];

$stmt = $conn->prepare("UPDATE rooms SET number=?, type=?, price=?, status=? WHERE id=?");
$stmt->bind_param("ssdsi", $data['number'], $data['type'], $data['price'], $data['status'], $id);
$stmt->execute();

echo json_encode(["message" => "Room updated"]);
$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM rooms WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

echo json_encode(["message" => "Room deleted"]);
$from = $_GET['from'];
$to   = $_GET['to'];

$query = "
SELECT * FROM room r
WHERE r.id NOT IN (
    SELECT room_id FROM bookings
    WHERE NOT (check_out <= ? OR check_in >= ?)
)
AND status = 'available'
";

$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $from, $to);
$stmt->execute();

echo json_encode($stmt->get_result()->fetch_all(MYSQLI_ASSOC));


session_start();

$_SESSION['pending_booking'] = [
    "room_id" => $_POST['room_id'],
    "check_in" => $_POST['check_in'],
    "check_out" => $_POST['check_out']
];

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['role'] !== 'staff') {
    die("Unauthorized");
}
?>