<?php

function getNews($term,$page, $pageSize) {
    // if term is empty, get top news headlines instead
    if(empty($term)) {
        $url = "https://newsapi.org/v2/top-headlines?country=us&page=$page&pageSize=$pageSize&apiKey=c939c288f98045d7a57360fef3f39d25";
    }
    else {
        $term = str_replace(' ', '+', $term);
    $url = "https://newsapi.org/v2/everything?q=$term&page=$page&pageSize=$pageSize&sortBy=relevancy&apiKey=c939c288f98045d7a57360fef3f39d25";
    }
    // set up curl and make a request            
    $cSession = curl_init(); 

    curl_setopt($cSession,CURLOPT_URL,$url);
    curl_setopt($cSession,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($cSession, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:17.0) Gecko/20100101 Firefox/17.0');
    curl_setopt($cSession,CURLOPT_HEADER, false); 

    $result=curl_exec($cSession);

    curl_close($cSession);

    $result = json_decode($result);
    
    if($result->status != 'ok') {
        echo 'Error connecting to News API!';
        return;
    }

    return $result;
}

/**
 * given the json data returned from the news api call,
 * format the data and output in html
 */
function printNews($data, $size) {
    if(!$data) {
        return;
    }
    if($size > 10) {
        $size = 10;
    }
    for ($i=0; $i < $size; $i++) {
        $img = $data->articles[$i]->urlToImage;
        $time = setTime($data->articles[$i]->publishedAt);
        $source = $data->articles[$i]->source->name;
        $url = $data->articles[$i]->url;
        $title = $data->articles[$i]->title;
        $title = trimField($title, 81);
        $desc = $data->articles[$i]->description;
        $desc = trimField($desc, 130);
        
        echo "<div class='newsCard'>
                <a href=$url class='newsUrl'>
                    <div class='newsWrap'>
                        <div class='news-info'>
                            <span class='news-source'>$source</span>
                            <span class='news-title'>$title</span>
                            <span class='newsDesc'>$desc</span>
                            <span class='published'>$time</span>
                        </div>
                        <img src=$img class='newsImage'></img>
                    </div>
                </a>
            </div>";
    }
}

function setTime($dateTime) {
    $timeZone = new DateTimeZone('America/Los_Angeles');
    $time = new DateTime($dateTime);
    $time->setTimezone($timeZone);
    $time = $time->format('D, d M Y');
    return $time;
}

function trimField($string, $charLimit) {
    $dots = strlen($string) > $charLimit ? "..." : "";
    return substr($string, 0, $charLimit) . $dots;
}
?>
