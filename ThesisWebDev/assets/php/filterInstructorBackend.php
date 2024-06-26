<?php
// Include database connection code
include('connection.php');

// Get the subject name from the request parameters
$subjectName = $_GET['courseName']; // Assuming the subject name is sent as a GET parameter

// Initialize array to store fetched instructor names
$instructors = array();

// Prepare and execute SQL query to fetch instructor names based on the subject name
$sql_instructors = "SELECT i.instructorName 
                    FROM tblinstructors AS i
                    INNER JOIN tblsubjects AS s ON i.instructorID = s.instructorID
                    WHERE s.subjectName = ?";
$stmt_instructors = $conn->prepare($sql_instructors);
if ($stmt_instructors) {
    $stmt_instructors->bind_param('s', $subjectName); // Assuming the subject name is a string
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
