<?php

    function convert_datetime($datetime){

        $new_datetime = date("m/d/y (h:i a)", strtotime($datetime));
        $new_datetime->setTimezone('America/New_York');
        return $new_datetime;

    }

?>