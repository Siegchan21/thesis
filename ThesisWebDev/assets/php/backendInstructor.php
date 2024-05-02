<?php
// Include database connection code
include('connection.php');

// Include the PHP files that fetch data
include_once('./filterInstructorBackend.php');

// Define the process_data function
function process_data($instructors) {
    // Process data received from the included PHP files
    // Example: return the received data
    return array(
        $instructors
    );
}

// Process data from the included PHP files
$result = process_data($instructors);

// Send response back to Python as JSON
echo json_encode($result);
exit(); // Ensure no additional content is sent after JSON data
?>