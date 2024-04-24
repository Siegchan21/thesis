<?php

    function crossover($A, $B) {
        // Randomly select a subset of elements from each parent
        $subsetA = array_rand($A, 3); // Select 3 random keys from array $A
        $subsetB = array_rand($B, 2); // Select 2 random keys from array $B

        // Combine selected elements from both parents
        $offspring = [];
        foreach ($subsetA as $key) {
            $offspring[$key] = $A[$key];
        }
        foreach ($subsetB as $key) {
            $offspring[$key] = $B[$key];
        }

        // Ensure offspring has exactly 5 elements
        $offspring = array_slice($offspring, 0, 5);

        return $offspring;
    }

?>