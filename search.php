<?php
include("config.php");
include("classes/DomDocumentParser.php");
include("classes/SiteResultsProvider.php");
include("classes/ImageResultsProvider.php");
include('ajax/weather.php');
include('ajax/news.php');

    //session_start();
    $check = $_SESSION['prev_location'];
    
    if(empty($_GET["term"]) && $check == 'home') {
        header('Location: index.php');
    }

    if(!empty($_GET["term"])) {
        $term = trim($_GET["term"]);
        $_SESSION['prev_location'] = 'search';
    }
    else{
        $term = "";
    }

    if(!empty($_GET["page"])){
        $page = $_GET["page"];
    }
    else {
        $page = 1;
    }

    if(!empty($_GET["type"])) {
        $type = $_GET["type"];
    }
    else {
        $type = "sites";
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Boogle</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
    <link rel="stylesheet" type="text/css" href="assets/css/styles.css?ts=<?=time()?>">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script type="text/javascript" src="assets/js/script.js"></script>
    <script type="text/javascript" src="assets/js/weather.js"></script>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <div class="headerContent">
                <div class="logo">
                    <a href="index.php">
                        <img src="assets/images/boogle.png" alt="">
                    </a>
                </div>
                <div class="search">
                    <form action="search.php" method="GET">
                        <div class="searchBar">
                            <input type="hidden" name="type" value=<?php echo$type ?>>
                            <input class="searchInput" type="text" name="term" value="<?php echo $term ?>">
                            <button class="searchBtn">
                                <img src="assets/images/search_icon.png" alt="search icon from icons8.com https://icons8.com/icons/set/search">
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="tabs">
                <ul class="tabList">
                    <li class="<?php echo $type == 'sites' ? 'active': '' ?>"> 
                        <a href='<?php echo "search.php?term=$term&type=sites"; ?>'>
                            Sites
                        </a>
                    </li>
                    <li class="<?php echo $type == 'images' ? 'active': '' ?>"> 
                        <a href='<?php echo "search.php?term=$term&type=images"; ?>'>
                            Images
                        </a>
                    </li>
                    <li class="<?php echo $type == 'news' ? 'active': '' ?>"> 
                        <a href='<?php echo "search.php?term=$term&type=news"; ?>'>
                            News
                        </a>
                    </li>
                    <li class="<?php echo $type == 'maps' ? 'active': '' ?>"> 
                        <a href='<?php echo "search.php?term=$term&type=maps"; ?>'>
                            Maps
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <?php
            // if search type is maps dont run main results section
            if($type == 'maps') {
                exit();
            }
        ?>
        <div class=mainResults>
            <?php
            
            if($type == "sites") {
                $results = new SiteResultsProvider($conn);
                $pageSize = 20;
            }
            else if($type == 'news') {
                $pageSize = 10;
            }
            else {
                $results = new ImageResultsProvider($conn);
                $pageSize = 30;
            }
            
            if(!empty($term) && $type != 'news') {
                $numResults = $results->getNumResults($term);
                
                echo "<p class='resultsCount'>$numResults results found</p>";

                if(($term == 'weather' || $term == 'Weather') && $type=='sites') {
                    echo "<script type='text/javascript'>
                            getLocation();
                        </script>";
                    outputWeather();
                }

                echo $results->getResultsHtml($page,$pageSize,$term);
            }
            else if(!empty($term) && $type == 'news') {
                $result = getNews($term,$page,$pageSize);
                if(!$result) {
                    exit();
                }
                if($result->totalResults > 100) {
                    $numResults = 100;
                }
                else{
                    $numResults = $result->totalResults;
                }

                echo "<p class='resultsCount'>$numResults results found</p>";
                echo "<div class='newsApiWrap'>
                <a class='newsAtt' href='https://newsapi.org/'>News by News API</a>
                    </div>";

                printNews($result, min($pageSize,$numResults));
            }
            else {
                $numResults = 0;
                echo "<p class='resultsCount'>$numResults results found</p>";
            }
            ?>
        </div>

        <div class="pagination">
            <div class="pageBtns">
                <div class="pageNum">
                    <img src="assets/images/pageStart.png">
                </div>
                <?php

                $pagesToShow = 10;
                $numPages = ceil($numResults / $pageSize);
                $pagesLeft = min($pagesToShow, $numPages);
                $currentPage = $page - floor($pagesToShow / 2);
                if($currentPage < 1) {
                    $currentPage = 1;
                }

                if($currentPage + $pagesLeft > $numPages + 1) {
                    $currentPage = $numPages + 1 - $pagesLeft;
                }
            
                while ($pagesLeft != 0 && $currentPage <= $numPages) {

                    if($currentPage == $page) {
                        echo "<div class='pageNum'>
                                <img src='assets/images/pageSelected.png'>
                                <span class='number'>$currentPage</span>
                            </div>";
                    }
                    else {
                        echo "<div class='pageNum'>
                                <a href='search.php?term=$term&type=$type&page=$currentPage'>
                                    <img src='assets/images/page.png'>
                                    <span class='number'>$currentPage</span>
                                </a>
                            </div>";
                    }
                    $currentPage ++;
                    $pagesLeft--;
                }

                ?>
                <div class="pageNum">
                    <img src="assets/images/pageEnd.png">
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
    <script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
</body>
</html>