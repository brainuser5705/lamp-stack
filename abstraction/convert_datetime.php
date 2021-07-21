<?php

    function convert_datetime($datetime){

        //formatting datetime 
        $datetime = date("m/d/y (h:i a)", strtotime($datetime));

        // making into datetime object
        $new_datetime = new Datetime($datetime);
    
        //changing timezone
        $new_datetime->setTimezone('America/New_York');
        
        return $new_datetime;

    }

?>