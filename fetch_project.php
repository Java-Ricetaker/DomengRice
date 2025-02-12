<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project_management";
 
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed");
}
 
$sql = "SELECT * FROM projects";
$result = $conn->query($sql);
 
$projects = [];
while ($row = $result->fetch_assoc()) {
    $projects[] = $row;
}
 ##1
echo json_encode($projects);
$conn->close();
?>

