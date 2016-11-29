<?php

$connect = mysqli_connect("localhost","root","autoset");
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "DELETE FROM newdatabase.Persons WHERE Firstname='Yoonyoung1'"; //DB를 지울때는 DB명.테이블

if (mysqli_query($connect, $sql)) {
    echo "Record deleted successfully";
} else {
    echo "Error deleting record: " . mysqli_error($connect);
}

mysqli_close($connect);
?>