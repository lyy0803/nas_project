<?
$connect = mysqli_connect("localhost","root","autoset","adgage_data");
if(!$connect){ //연결체크
	echo "error" . mysqli_error($connect);
	}

	mysqli_select_db($connect,"adgage");
	$sql = "SELECT CP_team from adgage";
	
	$result = mysqli_query($connect, $sql);

	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			echo "Firstname: " . $row["CP_team"]. "<br>";
		}
	} else {
		echo "0 results";
	}
mysqli_close($connect);

?>
