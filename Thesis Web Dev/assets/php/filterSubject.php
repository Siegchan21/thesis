<?php
// Include database connection code
include('connection.php');

// Get the courseID from the request parameters
$courseID = $_GET['courseID'] ?? null;

// Initialize array to store fetched subjects
$subjects = array();

// Prepare and execute SQL query to fetch subjects based on the courseID
$sql_subjects = "SELECT * FROM tblsubjects WHERE courseID = ?";
$stmt_subjects = $conn->prepare($sql_subjects);
$stmt_subjects->bind_param('i', $courseID);
$stmt_subjects->execute();
$result_subjects = $stmt_subjects->get_result();

// Fetch subjects and store them in the array
while ($row = $result_subjects->fetch_assoc()) {
    $subjects[] = $row;
}

// Close the statement and database connection
$stmt_subjects->close();
$conn->close();

// Return the fetched subjects as JSON
echo json_encode($subjects);
?>
