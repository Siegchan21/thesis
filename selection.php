<?php
// Define the selection function
function selection($array) {
    // Randomly select one gene from the input array
    $selectedGene = $array[array_rand($array)];

    // Check if the selected gene is not null and is an array
    if ($selectedGene !== null && is_array($selectedGene) && count($selectedGene) === 2) {
        // Extract time range and days from the selected gene
        $timeRange = $selectedGene[0];
        $days = $selectedGene[1];

        // Split the time range into start and end times
        list($startTime, $endTime) = explode(' - ', $timeRange);

        // Return an array containing the start time, end time, and days
        return [trim($startTime), trim($endTime), $days];
    } else {
        // If the selected gene is null or not in the expected format, return null
        return null;
    }
}
?>
