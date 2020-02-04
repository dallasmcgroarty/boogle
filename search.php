<?php
include("config.php");
include("classes/DomDocumentParser.php");
include("classes/SiteResultsProvider.php");

    session_start();
    $check = $_SESSION['prev_location'];
    
    if(empty($_GET["term"]) && $check == 'home') {
        header('Location: index.php');
    }

    if(!empty($_GET["term"])) {
        $term = $_GET["term"];
        $_SESSION['prev_location'] = 'search';
    }
    else{
        $term = "";
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
    <link rel="stylesheet" type="text/css" href="assets/css/styles.css">
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
                            <input class="searchInput" type="text" name="term">
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
                </ul>
            </div>
        </div>
        <div class=mainResults>
            <?php
            $results = new SiteResultsProvider($conn);

            if(!empty($term)) {
                $numResults = $results->getNumResults($term);
                echo "<p class='resultsCount'>$numResults results found</p>";
                echo $results->getResultsHtml(1,20,$term);
            }
            ?>
        </div>
    </div>
</body>
</html>