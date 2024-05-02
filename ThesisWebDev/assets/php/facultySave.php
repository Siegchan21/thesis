<?php 
include('./connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $instructorNames = $_POST['instructorName'];
    $instructorRoles = $_POST['instructorRole'];
    $courseNames = $_POST['courseName'];

    // Iterate through the submitted data
    foreach ($instructorNames as $index => $instructorName) {
        $instructorRole = $instructorRoles[$index];
        $courseName = $courseNames[$index];

        // Fetch the courseID based on the provided courseName
        $courseIDQuery = "SELECT courseID FROM tblcourses WHERE courseName = '$courseName'";
        $courseIDResult = $conn->query($courseIDQuery);

        if ($courseIDResult->num_rows > 0) {
            $row = $courseIDResult->fetch_assoc();
            $courseID = $row['courseID'];

            // SQL statement to insert data into tblInstructors
            $sql = "INSERT INTO tblInstructors (instructorName, instructorRole, courseID) 
                    VALUES ('$instructorName', '$instructorRole', '$courseID')";

            // Execute the SQL query
            if ($conn->query($sql) === TRUE) {
                echo "New record created successfully for $instructorName in $courseName<br>";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "No course found for courseName: $courseName<br>";
        }
    }

    header("Location: faculty.php");
}

?>
