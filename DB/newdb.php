<?
$connect = mysqli_connect("localhost","root","autoset");

if(!$connect){
	echo "error";
	}

if(mysqli_query($connect,"CREATE DATABASE Adgage_data")){
	echo "Database created";
	}
	
else{
	echo "error   " . mysqli_error($connect);
}

mysqli_close($connect);
?>