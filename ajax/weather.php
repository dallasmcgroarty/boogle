<?php
    session_start();
    if(!empty($_POST['weather'])) {
        $_SESSION['weather'] = $_POST['weather'];
        $weather = $_SESSION['weather'];
    }

    // output weather data in html
    function outputWeather () {
        @$weather = json_decode($_SESSION['weather']);
        @$icon = $weather->weather[0]->icon;
        @$temp = substr(strval($weather->main->temp),0,2);
        @$skies = $weather->weather[0]->main;
        echo "
        <div class='weather-wrapper'>
            <div class='report'>
                <p class='city'>$weather->name</p>
                <p class='skies'>$skies</p>
            </div>
            <div class='weather'>
                <img src='http://openweathermap.org/img/w/$icon.png'>
                <span class='temp'>$temp °F</span>
            </div>
        </div>";
    
    }
?>