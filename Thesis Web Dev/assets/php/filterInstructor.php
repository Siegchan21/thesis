<?php
// Include database connection code
include('connection.php');

// Get the courseID from the request parameters
$courseID = $_GET['courseID'] ?? null;

// Initialize array to store fetched instructors
$instructors = array();

// Prepare and execute SQL query to fetch instructors based on the courseID
$sql_instructors = "SELECT * FROM tblinstructors WHERE courseID = ?";
$stmt_instructors = $conn->prepare($sql_instructors);
$stmt_instructors->bind_param('i', $courseID);
$stmt_instructors->execute();
$result_instructors = $stmt_instructors->get_result();

// Fetch instructors and store them in the array
while ($row = $result_instructors->fetch_assoc()) {
    $instructors[] = $row;
}

// Close the statement and database connection
$stmt_instructors->close();
$conn->close();

// Return the fetched instructors as JSON
echo json_encode($instructors);
?>
