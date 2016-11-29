<?
//$check=$_GET["x"];

$connect = mysqli_connect("localhost","root","autoset","adgage_data");
if(!$connect){ //연결체크
	echo "error" . mysqli_error($connect);
	}
	
	
echo $check;


/*	
	mysqli_select_db($connect,"adgage");
		$sql = "select distinct trim(Prod_namebymedia) from adgage where Prod_namebymedia!=' ' and Media_name='".$check."' order by Prod_namebymedia asc";
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
echo json_encode($final);*/

mysqli_close($connect);
?>