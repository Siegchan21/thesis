<?php
// Include database connection code
include('connection.php');

// Get the courseID from the request parameters
$courseID = 3;

// Initialize array to store fetched room names
$rooms = array();

// Prepare and execute SQL query to fetch room names based on the courseID
$sql_rooms = "SELECT roomName FROM tblrooms WHERE courseID = ?";
$stmt_rooms = $conn->prepare($sql_rooms);
$stmt_rooms->bind_param('i', $courseID);
$stmt_rooms->execute();
$result_rooms = $stmt_rooms->get_result();

// Fetch room names and store them in the array
while ($row = $result_rooms->fetch_assoc()) {
    $rooms[] = $row['roomName']; // Store only the roomName column
}

// Close the statement and database connection
$stmt_rooms->close();
$conn->close();

// Return the fetched room names as JSON
echo json_encode($rooms);
?>