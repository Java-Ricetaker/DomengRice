<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project_management";

// Get the POST data
$data = json_decode(file_get_contents("php://input"), true);
$projectId = $data['projectId'];
$status = $data['status'];

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Update project status
$sql = "UPDATE projects SET status = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $status, $projectId);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}

$stmt->close();
$conn->close();
?>
