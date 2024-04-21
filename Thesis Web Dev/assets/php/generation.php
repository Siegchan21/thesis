<?php

include('./connection.php');

function generateClassTimes($teacherTimePreferences, $classDuration, $count) {

    $results = [];

    // define a hash map for days of the week
    $daysOfWeek = [
        0 => 'Mon',
        1 => 'Tue',
        2 => 'Wed',
        3 => 'Thu',
        4 => 'Fri',
        5 => 'Sat'
    ];

    // define default time ranges
    $amRangeStart = 7;
    $amRangeEnd = 11;
    $pmRangeStart = 13;
    $pmRangeEnd = 19; // set to 1900 so no class will gen. starting at 8pm onwards

    for ($i = 0; $i < $count; $i++) {
        do {
            // randomly select the class schedule pattern using the hash map
            $patternIndex = mt_rand(0, 2); // Reduced to 2 to only select from the Mon/Sat, Tue/Thu, Wed/Fri patterns
            $patternIndices = [
                [0, 5], // Mon/Sat
                [1, 3], // Tue/Thu
                [2, 4], // Wed/Fri
            ];
            $pattern = $patternIndices[$patternIndex];
            
            // generate class days based on the pattern
            $classDays = $pattern;
            
            // Format class days
            $formattedClassDays = [];
            foreach ($classDays as $dayIndex) {
                $formattedClassDays[] = $daysOfWeek[$dayIndex];
            }
            $formattedClassDays = implode("/", $formattedClassDays);

            // set start and end hour based on teacher preference
            if ($teacherTimePreferences === 'AM') {
                $startHour = $amRangeStart;
                $endHour = $amRangeEnd;
                $startPeriod = 'AM';
                $endPeriod = 'AM';
            } elseif ($teacherTimePreferences === 'PM') {
                $startHour = $pmRangeStart;
                $endHour = $pmRangeEnd;
                $startPeriod = 'PM';
                $endPeriod = 'PM';
            } else {
                // default if n/a is set
                $startHour = $amRangeStart;
                $endHour = $amRangeEnd;
                $startPeriod = 'AM';
                $endPeriod = 'AM';
            }

            // gen. rand hour
            $hour = mt_rand($startHour, $endHour);

            // gen. minute (0 or 30)
            $minute = mt_rand(0, 1) == 0 ? 0 : 30;

            // calculate end time based on class duration
            if ($classDuration == 90) {
                // for 1.5 hour classes
                $endHour = $hour + 1;
                $endMinute = $minute + 30;
                if ($endMinute >= 60) {
                    $endHour++;
                    $endMinute -= 60;
                }
            } else {
                // for 2 hour classes adjust end time for classes starting at 7:30 PM
                if ($hour === 19 && $minute === 30) {
                    $endHour = 21;
                    $endMinute = 0;
                } else {
                    $endHour = $hour + 2;
                    $endMinute = $minute;
                }
            }

            // adjust end hour and period
            if ($endHour >= 24) {
                $endHour -= 24;
                $endPeriod = 'AM';
            } elseif ($endHour >= 12) {
                $endPeriod = 'PM';
                if ($endHour > 12) {
                    $endHour -= 12;
                }
            }

            // adjust start period
            if ($hour >= 12) {
                $startPeriod = 'PM';
                if ($hour > 12) {
                    $hour -= 12;
                }
            }

            // format start/end time
            $startTime = sprintf('%02d:%02d %s', $hour, $minute, $startPeriod);
            $endTime = sprintf('%02d:%02d %s', $endHour, $endMinute, $endPeriod);

            // check for conflicts with existing class schedules
            $conflict = false;
            foreach ($results as $result) {
                list($existingDays, $existingTimeRange) = $result;
                // Check if class days overlap
                if (array_intersect($classDays, explode("/", $existingDays))) {
                    // Check if class times overlap
                    list($existingStartTime, $existingEndTime) = explode(" - ", $existingTimeRange);
                    list($existingStartHour, $existingStartMinute, $existingStartPeriod) = explode(":", explode(" ", $existingStartTime)[0]);
                    list($existingEndHour, $existingEndMinute, $existingEndPeriod) = explode(":", explode(" ", $existingEndTime)[0]);
                    $existingStartTimeMinutes = $existingStartHour * 60 + $existingStartMinute + ($existingStartPeriod === 'PM' ? 12 * 60 : 0);
                    $existingEndTimeMinutes = $existingEndHour * 60 + $existingEndMinute + ($existingEndPeriod === 'PM' ? 12 * 60 : 0);
                    $newStartTimeMinutes = $hour * 60 + $minute + ($startPeriod === 'PM' ? 12 * 60 : 0);
                    $newEndTimeMinutes = $endHour * 60 + $endMinute + ($endPeriod === 'PM' ? 12 * 60 : 0);
                    if (($newStartTimeMinutes >= $existingStartTimeMinutes && $newStartTimeMinutes < $existingEndTimeMinutes) ||
                        ($newEndTimeMinutes > $existingStartTimeMinutes && $newEndTimeMinutes <= $existingEndTimeMinutes) ||
                        ($newStartTimeMinutes <= $existingStartTimeMinutes && $newEndTimeMinutes >= $existingEndTimeMinutes)) {
                        $conflict = true;
                        break;
                    }
                }
            }

            if (!$conflict) {
                // No conflict found, add the class time range with randomly generated days to results array
                $results[] = [$formattedClassDays, "$startTime - $endTime"];
            }
            
        } while (false);
    }

    return $results;
}

// Function to retrieve data from the database
function fetchDataFromDatabase($tableName) {
    // Replace this with your actual database connection code
    global $conn; // Assuming $conn is your database connection object from connection.php

    // Fetch data from the specified table
    $sql = "SELECT * FROM $tableName";
    $result = $conn->query($sql);

    // Store fetched data in an array
    $data = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }

    return $data;
}

// Function to generate schedule using fetched data
function generateSchedule($teacherTimePreferences, $classDuration, $count) {
    // Fetch necessary data from the database
    $courses = fetchDataFromDatabase('tblcourses');
    $sections = fetchDataFromDatabase('tblsections');
    $subjects = fetchDataFromDatabase('tblsubjects');
    $instructors = fetchDataFromDatabase('tblinstructors');
    $rooms = fetchDataFromDatabase('tblrooms');

    // Generate class times using the provided function
    $schedules = generateClassTimes($teacherTimePreferences, $classDuration, $count);

    // Output the generated schedules
    $scheduleData = [];
    foreach ($schedules as $index => $schedule) {
        $course = $courses[array_rand($courses)]; // Random course
        $section = $sections[array_rand($sections)]; // Random section
        $subject = $subjects[array_rand($subjects)]; // Random subject
        $instructor = $instructors[array_rand($instructors)]; // Random instructor
        $room = $rooms[array_rand($rooms)]; // Random room

        $scheduleData[] = array(
            'Course' => $course['courseName'],
            'Section' => $section['sectionName'],
            'Subject' => $subject['subjectName'],
            'Instructor' => $instructor['instructorName'],
            'Room' => $room['roomName'],
            'Days' => $schedule[0],
            'Time' => $schedule[1]
        );
    }

    return $scheduleData;
}

// Function to display schedule data in a table
function displayScheduleTable($scheduleData) {
    echo "<table border='1'>";
    echo "<tr><th>Course</th><th>Section</th><th>Subject</th><th>Instructor</th><th>Room</th><th>Days</th><th>Time</th></tr>";
    foreach ($scheduleData as $schedule) {
        echo "<tr>";
        echo "<td>{$schedule['Course']}</td>";
        echo "<td>{$schedule['Section']}</td>";
        echo "<td>{$schedule['Subject']}</td>";
        echo "<td>{$schedule['Instructor']}</td>";
        echo "<td>{$schedule['Room']}</td>";
        echo "<td>{$schedule['Days']}</td>";
        echo "<td>{$schedule['Time']}</td>";
        echo "</tr>";
    }
    echo "</table>";
}

// Example usage
$teacherTimePreferences = 'AM'; // or 'PM', or 'N/A'
$classDuration = 90; // Class duration in minutes (90 for 1.5 hours, 120 for 2 hours)
$count = 5; // Number of schedules to generate

$scheduleData = generateSchedule($teacherTimePreferences, $classDuration, $count);

// Display the schedule data in a table
displayScheduleTable($scheduleData);

?>

?>
