<?php

require "db_connect.php";

$sql_cekperiode = "SELECT periode FROM log_jambulan WHERE periode='".$_GET['periode']."'";
$query_cekperiode = mysqli_query($connection, $sql_cekperiode);
$cekperiode = mysqli_num_rows($query_cekperiode);

if($cekperiode > 0) {
    $sql = "UPDATE log_jambulan SET jam='".$_GET['jam']."' WHERE periode='".$_GET['periode']."'";
    if (mysqli_query($connection, $sql)) {
        echo "success";
    } else {
        echo "error";
    }
} else {
    $sql = "INSERT INTO log_jambulan SET jam='".$_GET['jam']."', periode='".$_GET['periode']."'";
    if (mysqli_query($connection, $sql)) {
        echo "success";
    } else {
        echo "error";
    }
}

mysqli_close($connection);
?>
