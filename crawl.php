<?php
// web crawler to get url and site info
include("config.php");
include("classes/DomDocumentParser.php");

$alreadyCrawled = array();
$crawling = array();
$alreadyFoundImages = array();

function insertLink($url, $title, $description, $keywords) {
    global $conn;

    $query = $conn->prepare("INSERT INTO sites(url, title, description, keywords)
                            VALUES(:url,:title,:description,:keywords)");
    
    $query->bindParam(":url", $url);
    $query->bindParam(":title", $title);
    $query->bindParam(":description", $description);
    $query->bindParam(":keywords", $keywords);

    return $query->execute();
}

function insertImage($url, $src, $alt, $title) {
    global $conn;

    $query = $conn->prepare("INSERT INTO images(siteUrl, imageUrl, alt, title)
                            VALUES(:siteUrl,:imageUrl,:alt,:title)");
    
    $query->bindParam(":siteUrl", $url);
    $query->bindParam(":imageUrl", $src);
    $query->bindParam(":alt", $alt);
    $query->bindParam(":title", $title);

    $query->execute();
}

function imageExists($url) {
    global $conn;

    $query = $conn->prepare("SELECT * FROM images WHERE siteUrl = :url");
    
    $query->bindParam(":url", $url);
    $query->execute();

    return $query->rowCount() != 0;
}

function linkExists($url) {
    global $conn;

    $query = $conn->prepare("SELECT * FROM sites WHERE url = :url");
    
    $query->bindParam(":url", $url);
    $query->execute();

    return $query->rowCount() != 0;
}

function createLink($src, $url) {

    // PHP functions to parse urls
    $scheme = parse_url($url)["scheme"]; // http
    $host = parse_url($url)["host"]; // www.hostname.com

    // handle links starting with '//' 
    if(substr($src,0,2) == "//") {
        $src = $scheme . ":" . $src;
    }
    // handle relative links startign with '/'
    else if(substr($src,0,1) == "/") {
        $src = $scheme . "://" . $host . $src;
    }
    else if(substr($src,0,2) == "./") {
        $src = $scheme . "://" . $host . dirname(parse_url($url)["path"]) . substr($src,1);
    }
    else if(substr($src,0,3) == "../") {
        $src = $scheme . "://" . $host . "/" . $src;
    }
    // if links dont start with https or http
    else if(substr($src,0,5) != "https" && substr($src,0,4) != "http") {
        $src = $scheme . "://" . $host . "/" . $src;
    }
    return $src;
}

function getDetails($url) {

    global $alreadyFoundImages;

    $parser = new DomDocumentParser($url);

    $titleArray = $parser->getTitleTags();

    if(sizeof($titleArray) == 0 || $titleArray->item(0) == NULL) {
        return;
    }

    $title = $titleArray->item(0)->nodeValue;

    if($title == "") {
        return;
    }

    $description = "";
    $keywords = "";

    $metasArray = $parser->getMetaTags();

    foreach($metasArray as $meta) {
        if($meta->getAttribute("name") == "description") {
            $description = $meta->getAttribute("content");
        }
        if($meta->getAttribute("name") == "keywords") {
            $keywords = $meta->getAttribute("content");
        }
    }

    $description = str_replace("\n","",$description);
    $keywords = str_replace("\n","",$keywords);

    if(linkExists($url)){
        echo "$url already exists<br>";
    }
    else if(insertLink($url, $title, $description, $keywords)){
        echo "SUCCESS: $url<br>";
    }
    else {
        echo "ERROR: Failed to insert $url<br>";
    }

    $imageArray = $parser->getImages();

    foreach($imageArray as $image) {
        $src = $image->getAttribute("src");
        $alt = $image->getAttribute("alt");
        $title = $image->getAttribute("title");

        if(!$title && !$alt){
            continue;
        }

        $src = createLink($src, $url);

        if(!in_array($src, $alreadyFoundImages)) {
            $alreadyFoundImages[] = $src;
            
            if(imageExists($src)){
                echo "$src image already exists<br>";
            }
            else{
                insertImage($url, $src, $alt, $title);
            }
        }
    }
}

function followLinks($url) {

    global $alreadyCrawled;
    global $crawling;

    $parser = new DomDocumentParser($url);

    $linkList = $parser->getlinks();

    // loop through each link taken from the parser object
    // grab the href attribute of each link and call createLink function
    // to put the links together, because most will be relative or broken up
    foreach($linkList as $link) {
        $href = $link->getAttribute("href");

        if(strpos($href, "#") !== false) {
            continue;
        }
        else if(substr($href,0,11) == "javascript:") {
            continue;
        }

        $href = createLink($href, $url);

        // add url to already crawled if not in there
        // also add to crawling
        if(!in_array($href, $alreadyCrawled)) {
            $alreadyCrawled[] = $href;
            $crawling[] = $href;

            getDetails($href);
        }
        //echo $href . "<br>";
    }

    // delete the last url crawled
    array_shift($crawling);

    //call followlinks recursively on each url found
    foreach($crawling as $site) {
        followLinks($site);
    }
}

$startUrl = "http://www.travelandleisure.com/trip-ideas/best-places-to-travel-in-2020";

followLinks($startUrl);

?>