<?php
// Include database connection code
include('connection.php');

// Get the courseID from the request parameters
$courseID = $_GET['courseID'] ?? null;

// Initialize array to store fetched sections
$sections = array();

if ($courseID !== null) {
    // Prepare and execute SQL query to fetch sections based on the courseID
    $sql_sections = "SELECT tblsections.*, tblcourses.courseName 
                     FROM tblsections 
                     INNER JOIN tblcourses ON tblsections.courseID = tblcourses.courseID 
                     WHERE tblsections.courseID = ?";
    $stmt_sections = $conn->prepare($sql_sections);
    $stmt_sections->bind_param('i', $courseID);
    $stmt_sections->execute();
    $result_sections = $stmt_sections->get_result();

    // Fetch sections and store them in the array
    while ($row = $result_sections->fetch_assoc()) {
        $sections[] = $row;
    }

    // Close the statement
    $stmt_sections->close();
}

// Close the database connection
$conn->close();

// Return the fetched sections as JSON
echo json_encode($sections);
?>
