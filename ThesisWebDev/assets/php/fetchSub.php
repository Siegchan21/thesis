<?php
    // Include database connection code
    include('connection.php');

    // Fetch data from the database
    $sql = "SELECT s.subjectName AS subject, s.subjectType AS subjectType, i.instructorName AS instructor, c.courseName AS course, s.subjectLevel AS level, s.subjectSem AS semester
            FROM tblsubjects AS s 
            INNER JOIN tblinstructors AS i ON s.instructorID = i.instructorID 
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
