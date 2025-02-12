<?php
// Get the data from the request (JSON format)
$data = json_decode(file_get_contents("php://input"), true);

// Check if required fields are present
if (isset($data['id'], $data['project_name'], $data['start_date'], $data['end_date'], $data['scope'], $data['status'])) {
    $id = $data['id'];
    $projectName = $data['project_name'];
    $startDate = $data['start_date'];
    $endDate = $data['end_date'];
    $scope = $data['scope'];
    $status = $data['status'];

    // Database connection details
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "project_management";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL statement for updating the project details
    $sql = "UPDATE projects SET project_name = ?, start_date = ?, end_date = ?, scope = ?, status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $projectName, $startDate, $endDate, $scope, $status, $id);

    // Execute the query and send a response back
    if ($stmt->execute()) {
        // Return a success response
        echo json_encode(['success' => true]);
    } else {
        // Return an error response if the query fails
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }

    // Close the statement and the connection
    $stmt->close();
    $conn->close();
} else {
    // If required data is missing, return an error response
    echo json_encode(['success' => false, 'error' => 'Invalid data provided']);
}
?>
