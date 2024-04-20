<?php
    // Include database connection code
    include('connection.php');

    // Fetch data from the database
    $sql = "SELECT courseName FROM tblcourses";
    $result = $conn->query($sql);

    $data = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Add each row to the $data array
            $data[] = $row;
        }
    }

    // Close database connection
    $conn->close();

    // Return data as JSON
    echo json_encode($data);
?>
    