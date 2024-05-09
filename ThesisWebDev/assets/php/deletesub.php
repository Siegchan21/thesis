<?php
// Include database connection code
include('connection.php');
// Check if subjectID is provided in the request
if (isset($_POST['subjectID'])) {
    // Get subjectID from the request
    $subjectID = $_POST['subjectID'];
    // Prepare and execute the SQL statement to delete the record
    $sql = "DELETE FROM tblsubjects WHERE subjectID = $subjectID";

    if ($conn->query($sql) === TRUE) {
        // Return success message
        echo json_encode(array("message" => "Record deleted successfully"));
    } else {
        // Return error message
        echo json_encode(array("message" => "Error deleting record: " . $conn->error));
    }
} else {
    // Return error message if subjectID is not provided
    echo json_encode(array("message" => "No subjectID provided"));
}

// Close database connection
$conn->close();
?>
