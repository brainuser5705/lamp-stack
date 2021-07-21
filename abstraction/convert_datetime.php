<?php

    function convert_datetime($datetime){

        // making into datetime object
        $datetime = new DateTime($datetime);

        //changing timezone
        $datetime->setTimezone(new DateTimeZone('America/New_York'));

		//formatting datetime 
        $new_datetime = $datetime->format('m/d/y (h:i a)');
		
        return $new_datetime;

    }
	
?>