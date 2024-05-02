<?php
// Include database connection code
include('connection.php');

// Get the courseID from the request parameters
$courseID = 3;

// Initialize array to store fetched instructor names
$instructors = array();

// Prepare and execute SQL query to fetch instructor names based on the courseID
$sql_instructors = "SELECT instructorName FROM tblinstructors WHERE courseID = ?";
$stmt_instructors = $conn->prepare($sql_instructors);
if ($stmt_instructors) {
    $stmt_instructors->bind_param('i', $courseID);
    $stmt_instructors->execute();
    $result_instructors = $stmt_instructors->get_result();

    // Fetch instructor names and store them in the array
    while ($row = $result_instructors->fetch_assoc()) {
        $instructors[] = $row['instructorName']; // Store only the instructorName column
    }

    // Close the statement
    $stmt_instructors->close();
} else {
    // Handle SQL statement preparation error
    die("Prepare failed: " . $conn->error);
}

// Close the database connection
$conn->close();

// Return the fetched instructor names as JSON
echo json_encode($instructors);
?>
