<?php
// Include database connection code
include('connection.php');

// Get the subject name from the request parameters
$subjectName = $_GET['courseName']; // Assuming the subject name is sent as a GET parameter

// Initialize array to store fetched level names
$level = array();

// Prepare and execute SQL query to fetch level names based on the subject name
$sql_level = "SELECT i.gradeLevel 
                    FROM tbllevel AS i
                    INNER JOIN tblsubjects AS s ON i.levelID = s.levelID
                    WHERE s.subjectName = ?";
$stmt_level = $conn->prepare($sql_level);
if ($stmt_level) {
    $stmt_level->bind_param('s', $subjectName); // Assuming the subject name is a string
    $stmt_level->execute();
    $result_level = $stmt_level->get_result();

    // Fetch level names and store them in the array
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

// Return the fetched level names as JSON
echo json_encode($level);
?>
