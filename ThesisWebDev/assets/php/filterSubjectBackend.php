<?php
// Include database connection code
include('connection.php');

// Get the courseID from the request parameters
$courseID = 5;

// Initialize array to store fetched subject names
$subjects = array();

// Prepare and execute SQL query to fetch subject names based on the courseID
$sql_subjects = "SELECT subjectName FROM tblsubjects WHERE courseID = ?";
$stmt_subjects = $conn->prepare($sql_subjects);
if ($stmt_subjects) {
    $stmt_subjects->bind_param('i', $courseID);
    $stmt_subjects->execute();
    $result_subjects = $stmt_subjects->get_result();

    // Fetch subject names and store them in the array
    while ($row = $result_subjects->fetch_assoc()) {
        $subjects[] = $row['subjectName']; // Store only the subjectName column
    }

    // Close the statement
    $stmt_subjects->close();
} else {
    // Handle SQL statement preparation error
    die("Prepare failed: " . $conn->error);
}

// Close the database connection
$conn->close();

// Return the fetched subject names as JSON
echo json_encode($subjects);
?>