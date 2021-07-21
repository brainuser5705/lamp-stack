<?php

    function convert_datetime($datetime){

        // making into datetime object
        $new_datetime = new Datetime($datetime);

        //formatting datetime 
        $new_datetime->format("m/d/y (h:i a)");
    
        //changing timezone
        $new_datetime->setTimezone(new DateTimeZone('America/New_York'));

        return $new_datetime;

    }

?>