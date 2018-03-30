<?php

    function cartesian($input) {
        // filter out empty values
        $input = array_filter($input);

        $result = array(array());

        foreach ($input as $key => $values) {
            $append = array();

            foreach($result as $product) {
                foreach($values as $item) {
                    $product[$key] = $item;
                    $append[] = $product;
                }
            }

            $result = $append;
        }

        return $result;
    }

?>