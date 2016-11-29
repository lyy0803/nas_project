<?
$name=$_POST["name"];

$connect = mysqli_connect("localhost","root","autoset","adgage_data");
if(!$connect){ //연결체크
	echo "error" . mysqli_error($connect);
	}
	
	mysqli_select_db($connect,"adgage");
		$sql = "select distinct"+$name+"from adgage order by CP_category asc";
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
	echo $final;
	
	


?>