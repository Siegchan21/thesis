<?php
// Include database connection code
include('connection.php');

// Get the courseID from the request parameters
$courseID = 3;

// Initialize array to store fetched section names
$sections = array();

if ($courseID !== null) {
    // Prepare and execute SQL query to fetch section names based on the courseID
    $sql_sections = "SELECT sectionName FROM tblsections WHERE courseID = ?";
    $stmt_sections = $conn->prepare($sql_sections);
    $stmt_sections->bind_param('i', $courseID);
    $stmt_sections->execute();
    $result_sections = $stmt_sections->get_result();

    // Fetch section names and store them in the array
    while ($row = $result_sections->fetch_assoc()) {
        $sections[] = $row['sectionName']; // Store only the sectionName column
    }

    // Close the statement
    $stmt_sections->close();
}

// Close the database connection
$conn->close();

// Return the fetched section names as JSON
echo json_encode($sections);
?>