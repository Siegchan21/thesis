<?php
// Include database connection code
include('connection.php');
// Check if roomID is provided in the request
if (isset($_POST['roomID'])) {
    // Get roomID from the request
    $roomID = $_POST['roomID'];
    // Prepare and execute the SQL statement to delete the record
    $sql = "DELETE FROM tblrooms WHERE roomID = $roomID";

    if ($conn->query($sql) === TRUE) {
        // Return success message
        echo json_encode(array("message" => "Record deleted successfully"));
    } else {
        // Return error message
        echo json_encode(array("message" => "Error deleting record: " . $conn->error));
    }
} else {
    // Return error message if roomID is not provided
    echo json_encode(array("message" => "No roomID provided"));
}

// Close database connection
$conn->close();
?>
