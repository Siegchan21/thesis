<?php
// Include database connection code
include('connection.php');

// Get the courseID from the request parameters
$courseID = $_GET['courseID'] ?? null;

// Initialize array to store fetched rooms
$rooms = array();

// Prepare and execute SQL query to fetch rooms based on the courseID
$sql_rooms = "SELECT * FROM tblrooms WHERE courseID = ?";
$stmt_rooms = $conn->prepare($sql_rooms);
$stmt_rooms->bind_param('i', $courseID);
$stmt_rooms->execute();
$result_rooms = $stmt_rooms->get_result();

// Fetch rooms and store them in the array
while ($row = $result_rooms->fetch_assoc()) {
    $rooms[] = $row;
}

// Close the statement and database connection
$stmt_rooms->close();
$conn->close();

// Return the fetched rooms as JSON
echo json_encode($rooms);
?>
