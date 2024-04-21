<?php

    function selection($array) {
        $keys = array_keys($array);
        return $keys[array_rand($keys)];
    }

?>