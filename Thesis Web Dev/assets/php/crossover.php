<?php

function crossover($A, $B) {
    // Combine the arrays
    $combined = array_merge($A, $B);

    // Perform up to 3 random swaps between the combined array
    $lenCombined = count($combined);
    for ($i = 0; $i < min(3, $lenCombined); $i++) {
        $randomIndexA = rand(0, $lenCombined - 1);
        $randomIndexB = rand(0, $lenCombined - 1);

        // Swap elements within the combined array
        $temp = $combined[$randomIndexA];
        $combined[$randomIndexA] = $combined[$randomIndexB];
        $combined[$randomIndexB] = $temp;
    }

    // Select the first 5 items from the combined array
    $result = array_slice($combined, 0, 5);

    return $result;
}

?>