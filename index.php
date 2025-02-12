<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Management System</title>
</head>
<body>
    <h2>Project Management System</h2>
    
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
        // Function to fetch and display existing projects from the database
        function fetchProjects() {
            fetch('fetch_project.php')
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.getElementById('projectTableBody');
                    tableBody.innerHTML = '';  // Clear existing rows in the table

                    // Loop through the projects and create table rows dynamically
                    data.forEach(project => {
                        let row = document.createElement('tr');
                        
                        // Check if the project is already marked as 'Done'
                        const markAsDoneButton = project.status === 'Ongoing' ? `<button onclick="markAsDone(${project.id})">Mark as Done</button>` : '';
                        
                        row.innerHTML = `
                            <td>${project.project_name}</td>
                            <td>${project.start_date}</td>
                            <td>${project.end_date}</td>
                            <td>${project.scope}</td>
                            <td>${project.status}</td>
                            <td>
                                <button onclick="window.location.href='edit.php?id=${project.id}'">Edit</button>
                                ${markAsDoneButton}
                            </td>
                        `;
                        tableBody.appendChild(row);
                    });
                })
                .catch(error => {
                    console.error("Error fetching projects:", error);
                });
        }

        // Mark the project as done (Update its status to "Done")
        function markAsDone(projectId) {
            fetch('update_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ projectId: projectId, status: 'Done' })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Project marked as Done!');
                    fetchProjects();  // Refresh the list of projects
                } else {
                    alert('Error updating project status.');
                }
            })
            .catch(error => {
                console.error("Error marking project as done:", error);
            });
        }

        // Call the fetchProjects function when the page is loaded
        document.addEventListener('DOMContentLoaded', fetchProjects);
    </script>

</body>
</html>
