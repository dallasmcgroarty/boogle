<?php
// get post data from script.js and update clicks for clicked link
include("../config.php");

if(!empty($_POST["imageUrl"])) {
    $query = $conn->prepare("UPDATE images SET clicks = clicks + 1 WHERE imageUrl=:imageUrl");
    $query->bindParam(":imageUrl", $_POST["imageUrl"]);

    $query->execute();
}
else {
    echo "No imageUrl passed to page";
}
?>