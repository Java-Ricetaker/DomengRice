<?php
// Get the project ID from the URL
$projectId = $_GET['id'];

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

// Retrieve the project data based on the ID
$sql = "SELECT * FROM projects WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $projectId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $project = $result->fetch_assoc();
} else {
    echo "Project not found!";
    exit;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Project</title>
</head>
<body>
    <h2>Edit Project</h2>
    <form id="editForm">
        <label for="projectName">Project Name:</label>
        <input type="text" id="projectName" value="<?= $project['project_name'] ?>" required><br>

        <label for="startDate">Start Date:</label>
        <input type="date" id="startDate" value="<?= $project['start_date'] ?>" required><br>

        <label for="endDate">End Date:</label>
        <input type="date" id="endDate" value="<?= $project['end_date'] ?>" required><br>

        <label for="scope">Scope:</label>
        <select id="scope" required>
            <option value="Small" <?= $project['scope'] == 'Small' ? 'selected' : '' ?>>Small</option>
            <option value="Medium" <?= $project['scope'] == 'Medium' ? 'selected' : '' ?>>Medium</option>
            <option value="Large" <?= $project['scope'] == 'Large' ? 'selected' : '' ?>>Large</option>
        </select><br>

        <label for="status">Status:</label>
        <select id="status" required>
            <option value="Ongoing" <?= $project['status'] == 'Ongoing' ? 'selected' : '' ?>>Ongoing</option>
            <option value="Done" <?= $project['status'] == 'Done' ? 'selected' : '' ?>>Done</option>
        </select><br>

        <button type="submit">Save Changes</button>
    </form>

    <script>
        // Handle form submission
        document.getElementById("editForm").addEventListener("submit", function(event) {
            event.preventDefault(); // Prevent default form submission

            // Get form values
            let projectName = document.getElementById("projectName").value;
            let startDate = document.getElementById("startDate").value;
            let endDate = document.getElementById("endDate").value;
            let scope = document.getElementById("scope").value;
            let status = document.getElementById("status").value;

            // Check if the end date is greater than start date
            if (new Date(endDate) <= new Date(startDate)) {
                alert("End date must be greater than start date!");
                return;
            }

            // Prepare data to send to the server
            let projectData = {
                id: "<?= $projectId ?>",  // Send the project ID to be updated
                project_name: projectName,
                start_date: startDate,
                end_date: endDate,
                scope: scope,
                status: status
            };

            // Send the data to the backend using fetch
            fetch("update_project.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(projectData)
            })
            .then(response => response.json())  // Parse JSON response from server
            .then(data => {
                if (data.success) {
                    alert("Project updated successfully!");
                    window.location.href = "index.php";  // Redirect to main page after saving
                } else {
                    alert("Error updating project.");
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("There was an error processing your request.");
            });
        });
    </script>
</body>
</html>
