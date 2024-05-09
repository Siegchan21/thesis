<?php
// Include database connection code
include('connection.php');
// Check if sectionID is provided in the request
if (isset($_POST['sectionID'])) {
    // Get sectionID from the request
    $sectionID = $_POST['sectionID'];
    // Prepare and execute the SQL statement to delete the record
    $sql = "DELETE FROM tblsections WHERE sectionID = $sectionID";

    if ($conn->query($sql) === TRUE) {
        // Return success message
        echo json_encode(array("message" => "Record deleted successfully"));
    } else {
        // Return error message
        echo json_encode(array("message" => "Error deleting record: " . $conn->error));
    }
} else {
    // Return error message if sectionID is not provided
    echo json_encode(array("message" => "No sectionID provided"));
}

// Close database connection
$conn->close();
?>
