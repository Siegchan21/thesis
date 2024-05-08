<?php
// Include database connection code
include('connection.php');

// Get the courseID from the request parameters
$courseID = 5;

// Initialize array to store fetched section names
$level = array();

// Prepare and execute SQL query to fetch section names based on the courseID
$sql_level = "SELECT gradeLevel FROM tbllevel WHERE courseID = ?";
$stmt_level = $conn->prepare($sql_level);
if ($stmt_level) {
    $stmt_level->bind_param('i', $courseID);
    $stmt_level->execute();
    $result_level = $stmt_level->get_result();

    // Fetch section names and store them in the array
    while ($row = $result_level->fetch_assoc()) {
        $level[] = $row['gradeLevel']; // Store only the gradeLevel column
    }

    // Close the statement
    $stmt_level->close();
} else {
    // Handle SQL statement preparation error
    die("Prepare failed: " . $conn->error);
}

// Close the database connection
$conn->close();

// Return the fetched section names as JSON
echo json_encode($level);
?>
