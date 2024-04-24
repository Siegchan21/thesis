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
            // Swap the indexes
            $offspring[] = [$setAC[$key][1], $setAC[$key][0]];
        }

        // Add selected elements from set B/D to offspring
        foreach ($crossoverValues as $value) {
            // Swap the indexes
            $offspring[] = [$setBD[$value][1], $setBD[$value][0]];
        }

        // Mutate some elements in the offspring based on the mutation rate
        foreach ($offspring as &$slot) {
            if (mt_rand() / mt_getrandmax() < $mutationRate) {
                // Add ** at the end of the time range string
                $slot[1] .= '**';
            }
        }

        return $offspring;
    }

?>