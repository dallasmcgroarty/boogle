<?php

function getNews($term,$page) {
    $url = "https://newsapi.org/v2/everything?q=$term&page=$page&sortBy=popularity&apiKey=c939c288f98045d7a57360fef3f39d25";
                
    $cSession = curl_init(); 

    curl_setopt($cSession,CURLOPT_URL,$url);
    curl_setopt($cSession,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($cSession,CURLOPT_HEADER, false); 

    $result=curl_exec($cSession);

    curl_close($cSession);

    $result = json_decode($result);
    
    //printNews($result);

    return $result;
}

function printNews($data, $size) {
    //print_r($data);
    if($size > 20) {
        $size = 20;
    }
    for ($i=0; $i < $size; $i++) {
        echo $data->articles[$i]->source->name . ' ' . 
            $data->articles[$i]->author . '<br>';
    }
}

function getTotalResults($term) {

}
?>