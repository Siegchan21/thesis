<?php
    include('./connection.php');

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        foreach ($_POST['subjectName'] as $index => $subjectName) {
            // Get the values from the form
            $courseName = $_POST['courseName'][$index];
            $subjectType = $_POST['subjectType'][$index];
            $instructorName = $_POST['instructorName'][$index];
            
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

            // Retrieve the instructorID based on the instructorName
            $instructorID = 0; // Default value
            $sql = "SELECT instructorID FROM tblinstructors WHERE instructorName = '$instructorName'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $instructorID = $row['instructorID'];
            } else {
                // Handle case where instructorName doesn't exist in tblinstructors
                echo "Error: Instructor '$instructorName' not found.<br>";
                continue;
            }

            // Insert the data into tblsubjects table
            $sql = "INSERT INTO tblsubjects (subjectName, subjectType, courseID, instructorID)
                    VALUES ('$subjectName', '$subjectType', '$courseID', '$instructorID')";

            if ($conn->query($sql) !== TRUE) {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }

        // Redirect after saving
        header("Location: subject.php");
    }
?>
