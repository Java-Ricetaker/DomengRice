<?php
$servername = "localhost";
$username = "root"; 
$password = "";
$dbname = "project_management";
 

$conn = new mysqli($servername, $username, $password, $dbname);
 

if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Connection failed"]));
}
 

$data = json_decode(file_get_contents("php://input"), true);
 

$project_name = $data["project_name"];
$start_date = $data["start_date"];
$end_date = $data["end_date"];
$scope = $data["scope"];
 

if (strtotime($end_date) <= strtotime($start_date)) {
    die(json_encode(["success" => false, "message" => "Invalid dates"]));
}
 

$sql = "INSERT INTO projects (project_name, start_date, end_date, scope) VALUES ('$project_name', '$start_date', '$end_date', '$scope')";
if ($conn->query($sql) === TRUE) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false]);
}
 ##1

$conn->close();
?>
 