<?php 
    include('./connection.php');

    $courseName = "";
    $subjectName = "";
    $instrutorName = "";
    $subjectType = "";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        foreach ($_POST['courseName'] as $index => $courseName) {
            $subjectName = $_POST['subjectName'][$index];
            $instructorName = $_POST['instructorName'][$index];
            $subjectType = $_POST['subjectType'][$index];
    
            // Insert the data into your database table
            $sql = "INSERT INTO tblsubjects (subjectName, subjectType)
                    VALUES ('$subjectName', '$subjectType')";
            $sql = "INSERT INTO tblcourses (courseName)
            VALUES ('$courseName')";
            $sql = "INSERT INTO tblInstructors (instructorName)
            VALUES ('$instructor')";
    
            if ($conn->query($sql) !== TRUE) {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }

        header("Location: /Thesis%20Web%20Dev/subject.html");
    }

?>