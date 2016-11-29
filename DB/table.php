<?
$connect = mysqli_connect("localhost","root","autoset");
if(!$connect){ //연결체크
	echo "error" . mysqli_error($connect);
	}

	mysqli_select_db($connect,"Adgage_data");
	$sql = "CREATE TABLE Adgage(FirstNabe varchar(15), LastName varchar(15), Age int)";
	mysqli_query($connect,$sql);

mysqli_close($connect);
?>