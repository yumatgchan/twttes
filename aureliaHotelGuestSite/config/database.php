/*

//session_start();
$conn = new mysqli("localhost", "root", "", "hotel_system");
class Database {
    private $host = "localhost";
    private $db_name = "hotel_system";
    private $username = "root";
    private $password = "";
    public $conn;
 
    public function connect() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            error_log("Connection Error: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(["error" => "Database connection failed"]);
            exit();
        }
        return $this->conn;
    }
}



//--------------------------php for rooms--------------------------

$data = $_SESSION['pending_booking'];

$stmt = $conn->prepare("
INSERT INTO bookings (guest_id, room_id, check_in, check_out)
VALUES (?, ?, ?, ?)
");

$stmt->bind_param(
    "iiss",
    $_SESSION['user_id'],
    $data['room_id'],
    $data['check_in'],
    $data['check_out']
);

$stmt->execute();

*/


<?php
class Database {
    private $host = "localhost";
    private $db_name = "hotel_system";
    private $username = "root";
    private $password = "";
    public $conn;

    public function connect() {
        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name}",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->conn;
        } catch(PDOException $e) {
            die("DB Error: " . $e->getMessage());
        }
    }
}
