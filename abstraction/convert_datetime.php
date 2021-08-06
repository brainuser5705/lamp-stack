<?php

    function convert_datetime($datetime){

        // making into datetime object
        $datetime = new DateTime($datetime);

        //changing timezone
        $datetime->setTimezone(new DateTimeZone('America/New_York'));

		//formatting datetime (https://www.php.net/manual/en/datetime.format.php)
        $new_datetime = $datetime->format('g:i a - F j, Y');
		
        return $new_datetime;

    }
	
?>