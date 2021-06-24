<?php

    // security sanitiation for form inputs
    function sanitize($value){
        $value = trim($value);
        $value = stripslashes($value);
        $value = htmlspecialchars($value);
        return $value;
    }

?>