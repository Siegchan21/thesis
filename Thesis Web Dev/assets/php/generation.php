<?php

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
                $patternIndex = mt_rand(0, 5);
                if ($patternIndex < 5) {
                    // generate class days based on the pattern
                    $pattern = array_keys(array_slice($daysOfWeek, $patternIndex, null, true));
                    $classDays = [];
                    foreach ($pattern as $dayIndex) {
                        $classDays[] = $daysOfWeek[$dayIndex];
                    }
                    // Format class days
                    $formattedClassDays = implode("/", $classDays);
                } else {
                    // For single-day classes, select a random day of the week
                    $randomDayIndex = mt_rand(0, 5);
                    $formattedClassDays = $daysOfWeek[$randomDayIndex];
                }

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

                // check constraints
                $classTimeRange = "$startTime - $endTime";

            } while (false);

            // Add class time range with randomly generated days to results array
            $results[] = "$classTimeRange, $formattedClassDays";
        }

        return $results;
    }

?>
