<?php
// Include database connection code
include('connection.php');

// Include the PHP files that fetch data
include_once('./filterInstructor.php');
include_once('./filterRoom.php');
include_once('./filterSection.php');
include_once('./filterSubject.php');

// Define the process_data function
function process_data($instructors, $rooms, $sections, $subjects) {
    // Process data received from the included PHP files
    // Example: return the received data
    return array(
        'instructors' => $instructors,
        'rooms' => $rooms,
        'sections' => $sections,
        'subjects' => $subjects
    );
}

// Process data from the included PHP files
$result = process_data($instructors, $rooms, $sections, $subjects);

// Send response back to Python as JSON
echo json_encode($result);
exit(); // Ensure no additional content is sent after JSON data
?>