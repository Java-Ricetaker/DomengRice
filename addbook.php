<?php
header('Content-Type: application/json');

// Database connection
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = "";     // Replace with your database password
$dbname = "library_management_system"; // Database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

$action = $_GET['action'] ?? '';

if ($action === 'getCatalog') {
    // Fetch catalog from the database
    $sql = "SELECT title, copies FROM books";
    $result = $conn->query($sql);

    $catalog = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $catalog[] = $row;
        }
    }

    echo json_encode($catalog);
    $conn->close();
    exit;
}

if ($action === 'addBook') {
    $title = trim($_POST['bookTitle'] ?? '');
    $copies = intval($_POST['bookCopies'] ?? 0);

    // Validation
    if (empty($title)) {
        echo json_encode(['success' => false, 'message' => 'Book title cannot be empty.']);
        exit;
    }

    if ($copies <= 0) {
        echo json_encode(['success' => false, 'message' => 'Number of copies must be greater than zero.']);
        exit;
    }

    // Check if book already exists
    $stmt = $conn->prepare("SELECT COUNT(*) FROM books WHERE title = ?");
    $stmt->bind_param("s", $title);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        echo json_encode(['success' => false, 'message' => 'Book title already exists.']);
        exit;
    }

    // Insert book into the database
    $stmt = $conn->prepare("INSERT INTO books (title, copies) VALUES (?, ?)");
    $stmt->bind_param("si", $title, $copies);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add book.']);
    }

    $stmt->close();
    $conn->close();
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid action.']);
$conn->close();
