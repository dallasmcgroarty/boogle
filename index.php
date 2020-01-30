<?php
    session_start();
    $_SESSION['prev_location'] = 'home'
?>
<!DOCTYPE html>
<html>
<head>
    <title>Boogle</title>
    <meta name="description" content="Search the web for sites and images">
    <meta name="keywords" content="Search engine, boogle, websites">
    <meta name="author" content="Dallas McGroarty">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="assets/css/styles.css">
</head>
<body>
    <div class="wrapper index">
        <div class="main">
            <div class="logo">
                <img src="assets/images/boogle.png" alt="">
            </div>
            <div class="search">
                <form action="search.php" method="GET">
                    <input class="searchInput" type="text" name="term">
                    <input class="searchBtn" type="submit" value="Search">
                </form>
            </div>
        </div>
    </div>
</body>
</html>