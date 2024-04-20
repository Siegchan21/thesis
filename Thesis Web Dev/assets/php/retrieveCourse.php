<?php
include('connection.php');

// Query to retrieve instructor names from the database
$sql = "SELECT courseName FROM tblcourses";

// Execute the query
$result = $conn->query($sql);

// Check if there are any results
if ($result->num_rows > 0) {
    // Loop through each row and output instructor names as options
    while ($row = $result->fetch_assoc()) {
        echo '<option value="' . $row['courseName'] . '">' . $row['courseName'] . '</option>';
    }
} else {
    // If no results are found, display a default option
    echo '<option value="">No instructors found</option>';
}

// Close the database connection
$conn->close();
?>
