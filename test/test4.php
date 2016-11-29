<?

$connect = mysqli_connect("localhost","root","autoset","adgage_data");
if(!$connect){ //연결체크
	echo "error" . mysqli_error($connect);
	}

	mysqli_select_db($connect,"adgage");
		$sql = "select distinct CP_startdate from adgage order by CP_startdate asc limit 1";
		$result = mysql<?
$callback=$_GET["callback"];
$check[0]=$_GET["x"];


$connect = mysqli_connect("localhost","root","autoset","adgage_data");
if(!$connect){ //연결체크
	echo "error" . mysqli_error($connect);
	}

switch($check[0]){
	case "advertiser" :
		mysqli_select_db($connect,"adgage");
		$sql = "select distinct CP_category from adgage order by CP_category asc";
		$result = mysqli_query($connect, $sql); //객체로, 쿼리 결과 정보만 담아옴
		if (mysqli_num_rows($result) > 0) {
			$i=0;		
			while($row = mysqli_fetch_row($result)) {
				$final[$i]=$row[0];
				$i++;
			}
		} else {
			echo "0 results";
		}
	echo $callback."(".json_encode($final).")";
	break;
	
	
	case "media": 
		mysqli_select_db($connect,"adgage");
			$sql = "select distinct Media_name from adgage order by Media_name asc";
			$result = mysqli_query($connect, $sql); //객체로, 쿼리 결과 정보만 담아옴
			if (mysqli_num_rows($result) > 0) {
				$i=0;		
				while($row = mysqli_fetch_row($result)) {
					$final[$i]=$row[0];
					$i++;
				}
			} else {
				echo "0 results";
			}
		echo $callback."(".json_encode($final).")";
		break;
		
	case "product": 
		$str=$_GET["media_name"];
		mysqli_select_db($connect,"adgage");
			$sql = "select distinct Prod_namebymedia from adgage where Media_name='"+$str+"' order by Prod_namebymedia asc";
			$result = mysqli_query($connect, $sql); //객체로, 쿼리 결과 정보만 담아옴
			if (mysqli_num_rows($result) > 0) {
				$i=0;		
				while($row = mysqli_fetch_row($result)) {
					$final[$i]=$row[0];
					$i++;
				}
			} else {
				echo "0 results";
			}
		echo $callback."(".json_encode($final).")";
		break;
				
		
	default : echo $callback."(fail)";
}


mysqli_close($connect);
?>i_query($connect, $sql); //객체로, 쿼리 결과 정보만 담아옴
	
		while($row = mysqli_fetch_row($result)) {
			echo $row[0];
		}
	
	//echo json_encode($row);

mysqli_close($connect);
?>