<?php
    include('./connection.php');

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        foreach ($_POST['roomName'] as $index => $roomName) {
            // Get the values from the form
            $courseName = $_POST['courseName'][$index];
            $roomType = $_POST['roomType'][$index];
            
            // Retrieve the courseID based on the courseName
            $courseID = 0; // Default value
            $sql = "SELECT courseID FROM tblcourses WHERE courseName = '$courseName'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $courseID = $row['courseID'];
            } else {
                // Handle case where courseName doesn't exist in tblcourses
                echo "Error: Course '$courseName' not found.<br>";
                continue;
            }

            // Insert the data into tblsubjects table
            $sql = "INSERT INTO tblrooms (roomName, roomType, courseID)
                    VALUES ('$roomName', '$roomType', '$courseID')";

            if ($conn->query($sql) !== TRUE) {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }

        // Redirect after saving
        header("Location: room.php");
    }
?>
