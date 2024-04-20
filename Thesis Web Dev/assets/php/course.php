<?php 
    include('./connection.php');

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $courseNames = $_POST['courseName'];

        // Iterate through the submitted data
        foreach ($courseNames as $index => $courseName) {

            // SQL statement to insert data into tblInstructors
            $sql = "INSERT INTO tblcourses (courseName) 
                    VALUES ('$courseName')";

            // Execute the SQL query
            if ($conn->query($sql) === TRUE) {
                echo "New record created successfully for $courseName<br>";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }

        header("Location: /Thesis%20Web%20Dev/course.html");
    }
?>
