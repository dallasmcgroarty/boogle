<?php
    session_start();
    $_SESSION['prev_location'] = 'home';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Boogle</title>
    <meta name="description" content="Search the web for sites and images">
    <meta name="keywords" content="Search engine, boogle, websites">
    <meta name="author" content="Dallas McGroarty">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="assets/css/styles.css?ts=<?=time()?>">
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
        <!-- Dad joke request -->
        <div class="dad-joke">
            <form action="" method="POST">
                <p>Would you like to hear a dad joke?</p>
                <?php
                    $opts = array(
                        'http'=>array(
                            'method'=>'GET',
                            'header'=>'Accept: application/json'// or use plain/text
                        )
                    );
                  
                    if(isset($_POST["getJoke"])) {
                        $context = stream_context_create($opts);
                        $res = file_get_contents("https://icanhazdadjoke.com/", false, $context);
                        $res = json_decode($res);
                        echo "<p class='joke'>$res->joke</p>";
                    }
                ?>
                <input type='hidden' name="getJoke" value="submit">
                <input class="dadBtn" type="submit" value="<?php if(isset($_POST["getJoke"])){echo 'Again?';} else{echo 'Sure';}?>">
            </form>
        </div>
    </div>
</body>
</html>