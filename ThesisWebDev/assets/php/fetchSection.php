<?php
    // Include database connection code
    include('connection.php');

    // Fetch data from the database
    $sql = "SELECT s.sectionID AS sectionID, s.sectionName AS section, c.courseName AS course 
            FROM tblsections AS s
            INNER JOIN tblcourses AS c ON s.courseID = c.courseID";
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
