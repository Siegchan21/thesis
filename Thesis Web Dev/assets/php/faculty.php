<?php 
    include('./connection.php');

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $instructorNames = $_POST['instructorName'];
        $instructorRoles = $_POST['instructorRole'];

    // Iterate through the submitted data
        foreach ($instructorNames as $index => $instructorName) {
        $instructorRole = $instructorRoles[$index];

        // SQL statement to insert data into tblInstructors
        $sql = "INSERT INTO tblInstructors (instructorName, instructorRole) 
        VALUES ('$instructorName', '$instructorRole')";

        // Execute the SQL query
        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully for $instructorName<br>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }


        header("Location: /Thesis%20Web%20Dev/faculty.html");
    }

?>