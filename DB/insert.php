<?
$connect = mysqli_connect("localhost","root","autoset");
if(!$connect){ //연결체크
	echo "error" . mysqli_error($connect);
	}

	mysqli_select_db($connect,"newdatabase");
	$sql = "INSERT INTO Persons (Firstname, Lastname, Age) VALUES ('Yoonyoung3', 'Lim3', '24')";
	
	if (mysqli_query($connect, $sql)) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($connect);
?>