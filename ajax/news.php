<?php

function getNews($term) {
    $url = "https://newsapi.org/v2/everything?q=$term&sortBy=popularity&apiKey=c939c288f98045d7a57360fef3f39d25";
                
    $cSession = curl_init(); 

    curl_setopt($cSession,CURLOPT_URL,$url);
    curl_setopt($cSession,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($cSession,CURLOPT_HEADER, false); 

    $result=curl_exec($cSession);

    curl_close($cSession);

    $result = json_decode($result);
    //print_r($result);
    for ($i=0; $i < 10; $i++) {
        echo $result->articles[$i]->source->name . ' ' . 
            $result->articles[$i]->author . '<br>';
    }
}
?>