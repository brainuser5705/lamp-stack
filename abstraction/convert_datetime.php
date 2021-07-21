<?php

    function convert_datetime($datetime){

        // making into datetime object
        $new_datetime = new Datetime($datetime);
    
        //changing timezone
        $new_datetime->setTimezone('America/New_York');

        //formatting datetime 
        $new_datetime = date("m/d/y (h:i a)", strtotime($new_datetime));
        
        return $new_datetime;

    }

?>