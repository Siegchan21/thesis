<?php
include('./connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($_POST['subjectName'] as $index => $subjectName) {
        // Get the values from the form
        $courseName = $_POST['courseName'][$index];
        $subjectType = $_POST['subjectType'][$index];
        $instructorName = $_POST['instructorName'][$index];
        $gradeLevel = $_POST['gradeLevel'][$index]; 
        $subjectSem = $_POST['subjectSem'][$index]; 
        
        // Retrieve the courseID based on the courseName
        $courseID = 0; // Default value
        $sql = "SELECT courseID FROM tblcourses WHERE courseName = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $courseName);
        $stmt->execute();
        $result = $stmt->get_result();
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
        $sql = "SELECT instructorID FROM tblinstructors WHERE instructorName = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $instructorName);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $instructorID = $row['instructorID'];
        } else {
            // Handle case where instructorName doesn't exist in tblinstructors
            echo "Error: Instructor '$instructorName' not found.<br>";
            continue;
        }

        // Retrieve the levelID based on the gradeLevel
        $levelID = 0; // Default value
        $sql = "SELECT levelID FROM tbllevel WHERE gradeLevel = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $gradeLevel);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $levelID = $row['levelID'];
        } else {
            // Handle case where gradeLevel doesn't exist in tbllevel
            echo "Error: Grade level '$gradeLevel' not found.<br>";
            continue;
        }

        // Insert the data into tblsubjects table using prepared statement
        $sql = "INSERT INTO tblsubjects (subjectName, subjectType, courseID, instructorID, levelID, subjectSem) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssiiis', $subjectName, $subjectType, $courseID, $instructorID, $levelID, $subjectSem);
        if ($stmt->execute() !== TRUE) {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    // Redirect after saving
    header("Location: subject.php");
}
?>
