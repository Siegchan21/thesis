<?php
// Include database connection code
include('connection.php');

// Specify the course name to filter by
$courseName = "CCS";

// Prepare and execute SQL query to fetch the courseID based on the courseName
$sql_course = "SELECT courseID FROM tblcourses WHERE courseName = ?";
$stmt_course = $conn->prepare($sql_course);
$stmt_course->bind_param('s', $courseName);
$stmt_course->execute();
$result_course = $stmt_course->get_result();

// Check if a row is returned
if ($result_course->num_rows > 0) {
    // Fetch the courseID
    $row_course = $result_course->fetch_assoc();
    $courseID = $row_course['courseID'];

    // Prepare and execute SQL query to fetch subject names based on the courseID
    $sql_subjects = "SELECT subjectName FROM tblsubjects WHERE courseID = ?";
    $stmt_subjects = $conn->prepare($sql_subjects);
    $stmt_subjects->bind_param('i', $courseID);
    $stmt_subjects->execute();
    $result_subjects = $stmt_subjects->get_result();

    // Check if there are any rows returned
    if ($result_subjects->num_rows > 0) {
        // Initialize an array to store subject names
        $subjectNames = array();

        // Fetch each row and store the subject name in the array
        while ($row_subject = $result_subjects->fetch_assoc()) {
            $subjectNames[] = $row_subject['subjectName'];
        }

        // Return the subject names as JSON
        echo json_encode($subjectNames);
    } else {
        // No subjects found for the specified course
        echo json_encode(array('message' => 'No subjects found for the specified course'));
    }
} else {
    // Course not found
    echo json_encode(array('message' => 'Course not found'));
}

// Close the statements and database connection
$stmt_course->close();
$stmt_subjects->close();
$conn->close();
?>
