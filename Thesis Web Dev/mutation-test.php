<?php

// Function to perform crossover and mutation
function mutate($setAC, $setBD, $mutationRate) {
    // Initialize offspring set
    $offspring = [];

    // Select elements from set A/C and set B/D for crossover
    $crossoverKeys = array_rand($setAC, 3);
    $crossoverValues = array_rand($setBD, 2);

    // Add selected elements from set A/C to offspring
    foreach ($crossoverKeys as $key) {
        $offspring[] = $setAC[$key];
    }

    // Add selected elements from set B/D to offspring
    foreach ($crossoverValues as $value) {
        $offspring[] = $setBD[$value];
    }

    // Mutate some elements in the offspring based on the mutation rate
    foreach ($offspring as &$slot) {
        if (mt_rand() / mt_getrandmax() < $mutationRate) {
            // Add ** at the end of the time range string
            $slot[0] .= '**';
        }
    }

    return $offspring;
}

?>