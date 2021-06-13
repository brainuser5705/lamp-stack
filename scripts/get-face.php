<?php

    function getFace(){
        $month = date("m");
        $day = date("d");
        $day_name = date("l");

        if ($month == "12" && $day="25"){
            $faceImg = "winter-face.jpg";
            $greeting = "Merry Christmas!";
        }elseif ($month == "10"){
            $faceImg = "halloween-face.jpg";
            $greeting = "Spooky Month";
        }elseif(in_array($month, ["12", "1", "2"])){
            $faceImg = "winter-face.jpg";
            $greeting = "Brr..it's winter season now";
        }elseif ($day_name == "Friday"){
            $faceImg = "friday-face.jpg";
            $greeting = "It's Friday!!!";
        }else{
            $faceImg = "face.jpg";
            $greeting = "Welcome to my site!";
        }

        return ["faceImg"=>$faceImg, "greeting"=>$greeting];
    }
    
?>