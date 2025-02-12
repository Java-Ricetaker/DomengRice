<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Management System</title>
    <link rel="nofollow" href=''>
</head>
<body>
 
    <h2>Project Management System</h2>
 
    <form id="projectForm">
        <label>Project Name:</label>
        <input type="text" id="projectName" required>
 
        <label>Start Date:</label>
        <input type="date" id="startDate" required>
 
        <label>End Date:</label>
        <input type="date" id="endDate" required>
 
        <label>Scope:</label>
        <select id="scope" required>
            <option value="Small">Small</option>
            <option value="Medium">Medium</option>
            <option value="Large">Large</option>
        </select>
 
        <button type="submit">Add Project</button>
    </form>
 
    <table>
        <thead>
            <tr>
                <th>Project Name</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Scope</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="projectTableBody">
            <!-- Projects will be dynamically inserted here -->
        </tbody>
    </table>
 
    <script> 

document.getElementById("projectForm").addEventListener("submit", function(event) {
    event.preventDefault();
 
    let projectName = document.getElementById("projectName").value;
    let startDate = document.getElementById("startDate").value;
    let endDate = document.getElementById("endDate").value;
    let scope = document.getElementById("scope").value;
 
    if (new Date(endDate) <= new Date(startDate)) {
        alert("End date must be greater than start date!");
        return;
    }
 
    let projectData = {
        project_name: projectName,
        start_date: startDate,
        end_date: endDate,
        scope: scope,
        status: "Ongoing"
    };
 
    fetch("add_project.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(projectData)
    }).then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("Project added successfully!");
            location.reload();
        } else {
            alert("Error adding project.");
        }
    });
});
 
</script>
</body>
</html>
 


 