<?php
// get post data from script.js and update clicks for clicked link
include("../config.php");

if(!empty($_POST["linkId"])) {
    $query = $conn->prepare("UPDATE sites SET clicks = clicks + 1 WHERE id=:id");
    $query->bindParam(":id", $_POST["linkId"]);

    $query->execute();
}
else {
    echo "No link passed to page";
}
?>