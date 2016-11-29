<?
$callback=$_GET["callback"];

$connect = mysqli_connect("localhost","root","autoset","adgage_data");
if(!$connect){ //연결체크
	echo "error" . mysqli_error($connect);
	}
	mysqli_select_db($connect,"adgage");
	
	$sql = "select distinct CP_category from adgage order by CP_category asc";
	$result = mysqli_query($connect, $sql); //객체로, 쿼리 결과 정보만 담아옴
	if (mysqli_num_rows($result) > 0) {
		$i=0;		
		while($row = mysqli_fetch_row($result)) {
			$final[0][$i]=$row[0];
			$i++;
		}
	} else {
		echo "0 results";
	}
	
	$sql2 = "select distinct Media_name from adgage order by Media_name asc";
	$result2 = mysqli_query($connect, $sql2); //객체로, 쿼리 결과 정보만 담아옴
	if (mysqli_num_rows($result2) > 0) {
		$i=0;		
		while($row2 = mysqli_fetch_row($result2)) {
			$final[1][$i]=$row2[0];
			$i++;
		}
	} else {
		echo "0 results";
	}
	
	$sql3 = "select distinct Prod_namebymedia from adgage order by Prod_namebymedia asc";
	$result3 = mysqli_query($connect, $sql3); //객체로, 쿼리 결과 정보만 담아옴
	if (mysqli_num_rows($result3) > 0) {
		$i=0;		
		while($row3 = mysqli_fetch_row($result3)) {
			$final[2][$i]=$row3[0];
			$i++;
		}
	} else {
		echo "0 results";
	}
	echo $callback."(".json_encode($final).")";
mysqli_close($connect);
?>