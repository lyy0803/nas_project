<?
$startday=$_POST["startday"];
$endday=$_POST["endday"];
$category=$_POST["category"];
$device=$_POST["device"];
$indicator=$_POST["indicator"];
$search=$_POST["search"];
$mediasearch=$_POST["mediasearch"];//배열
$product=$_POST["product"]; //배열

$cnt = count($mediasearch); //선택개수
$connect = mysqli_connect("localhost","root","autoset","adgage_data");

if($category=="all"){
  $category=0;
}
else{
  $category="'".$category."'";
}

if($device=="all"){
  $device=0;
}
else{
  $device="'".$device."'";
}

switch ($search) {
  case 'search_media':

    if($indicator=='CTR'){
      $query  = "select round(sum(OC_click)/sum(OC_effect)*100,2) AS CTR_S, round(sum(OC_click/OC_effect)/count(oc_click/oc_effect)*100,2) AS CTR_C
        from adgage
        where CP_startdate>='$startday' and CP_enddate<='$endday' and CP_category=$category and Media_code=$device";
      $result = mysqli_query($connect, $query);
      while($data = mysqli_fetch_row($result)){
        $CTR[0][0]=$data[0];
        $CTR[0][1]=$data[1];
      }

      for ($i=0; $i<$cnt; $i++){
        $query  = "select round(sum(OC_click)/sum(OC_effect)*100,2) AS CTR_S, round(sum(OC_click/OC_effect)/count(oc_click/oc_effect)*100,2) AS CTR_C
            from adgage
            where CP_startdate>='$startday' and CP_enddate<='$endday' and CP_category=$category and Media_code=$device and Media_name='$mediasearch[$i]'";
        $result = mysqli_query($connect, $query);
        while($data = mysqli_fetch_row($result)){
          $CTR[1][$i]=$data[0];
          $CTR[2][$i]=$data[1];
        }
      }
      echo json_encode($CTR);//string으로 반환
    }

    else{
      $query  = "select round(sum(oc_cost)/sum(oc_click)), round(sum(oc_cost)/sum(oc_effect)*1000)
        from adgage
        where CP_startdate>='$startday' and CP_enddate<='$endday' and CP_category=$category and Media_code=$device";
      $result = mysqli_query($connect, $query);
      while($data = mysqli_fetch_row($result)){
        $CPC_M[0][0]=$data[0];
        $CPC_M[0][1]=$data[1];
      }

      $query  = "select round(avg(oc_cpc)), round(avg(oc_cpm))
         from adgage
         where CP_startdate>='$startday' and CP_enddate<='$endday' and CP_category=$category and Media_code=$device and oc_cpc!='' and oc_cpc!='-' and oc_cpm!='' and oc_cpm!='-'";
      $result = mysqli_query($connect, $query);
      while($data = mysqli_fetch_row($result)){
        $CPC_M[0][2]=$data[0];
        $CPC_M[0][3]=$data[1];
      }

      for ($i=0; $i<$cnt; $i++){
        $query  = "select round(sum(oc_cost)/sum(oc_click)) , round(sum(oc_cost)/sum(oc_effect)*1000)
           from adgage
           where CP_startdate>='$startday' and CP_enddate<='$endday' and CP_category=$category and Media_code=$device and Media_name='$mediasearch[$i]'";
         $result = mysqli_query($connect, $query);
         while($data = mysqli_fetch_row($result)){
           $CPC_M[1][$i]=$data[0];
           $CPC_M[2][$i]=$data[1];
         }
       }

      for ($i=0; $i<$cnt; $i++){
        $query  = "select round(avg(oc_cpc)), round(avg(oc_cpm))
           from adgage
           where CP_startdate>='$startday' and CP_enddate<='$endday' and CP_category=$category and Media_code=$device and Media_name='$mediasearch[$i]' and oc_cpc!='' and oc_cpc!='0'";
        $result = mysqli_query($connect, $query);
        while($data = mysqli_fetch_row($result)){
          $CPC_M[3][$i]=$data[0];
          $CPC_M[4][$i]=$data[1];
        }
      }
      echo json_encode($CPC_M);
    }
  break;

  default:
    if($indicator=='CTR'){
      $query  = "select round(sum(OC_click)/sum(OC_effect)*100,2) AS CTR_S, round(sum(OC_click/OC_effect)/count(oc_click/oc_effect)*100,2) AS CTR_C
        from adgage
        where CP_startdate>='$startday' and CP_enddate<='$endday' and CP_category=$category and Media_code=$device";
      $result = mysqli_query($connect, $query);
      while($data = mysqli_fetch_row($result)){
        $CTR[0][0]=$data[0];
        $CTR[0][1]=$data[1];
      }

      $newstr=" ";
      $k=0;
      for ($i=0; $i<$cnt; $i++){
        for($j=0; $j<count($product[$i]); $j++){
          $newstr=$product[$i][$j];
          $query  = "select round(sum(OC_click)/sum(OC_effect)*100,2) AS CTR_S, round(sum(OC_click/OC_effect)/count(oc_click/oc_effect)*100,2) AS CTR_C
            from adgage
            where CP_startdate>='$startday' and CP_enddate<='$endday' and CP_category=$category and Media_code=$device and Media_name='$mediasearch[$i]' and prod_namebymedia='$newstr'";
          $result = mysqli_query($connect, $query);
          while($data = mysqli_fetch_row($result)){
            $CTR[1][$k]=$data[0];
            $CTR[2][$k]=$data[1];
            $k++;
          }
        }
      }
      echo json_encode($CTR);//string으로 반환
    }
    else{
      $query  = "select round(sum(oc_cost)/sum(oc_click)), round(sum(oc_cost)/sum(oc_effect)*1000)
        from adgage
        where CP_startdate>='$startday' and CP_enddate<='$endday' and CP_category=$category and Media_code=$device";
      $result = mysqli_query($connect, $query);
      while($data = mysqli_fetch_row($result)){
        $CPC_M[0][0]=$data[0];
        $CPC_M[0][1]=$data[1];
      }

      $query  = "select round(avg(oc_cpc)), round(avg(oc_cpm))
         from adgage
         where CP_startdate>='$startday' and CP_enddate<='$endday' and CP_category=$category and Media_code=$device and oc_cpc!='' and oc_cpc!='-' and oc_cpm!='' and oc_cpm!='-'";
      $result = mysqli_query($connect, $query);
      while($data = mysqli_fetch_row($result)){
        $CPC_M[0][2]=$data[0];
        $CPC_M[0][3]=$data[1];
      }

      $newstr=" ";
      $k=0;
      for ($i=0; $i<$cnt; $i++){
        for($j=0; $j<count($product[$i]); $j++){
          $newstr=$product[$i][$j];
          $query  = "
            select round(sum(oc_cost)/sum(oc_click)),round(sum(oc_cost)/sum(oc_effect)*1000)
            from adgage
            where CP_startdate>='$startday' and CP_enddate<='$endday' and CP_category=$category and Media_code=$device and Media_name='$mediasearch[$i]' and cp_campaignname in
                (select cp_campaignname
                from adgage
                where CP_startdate>='$startday' and CP_enddate<='$endday' and CP_category=$category and Media_code=$device and Media_name='$mediasearch[$i]' and Prod_namebymedia='$newstr')";
          $result = mysqli_query($connect, $query);
          while($data = mysqli_fetch_row($result)){
             $CPC_M[1][$k]=$data[0];
             $CPC_M[2][$k]=$data[1];
             $k++;
           }
         }
       }
     $newstr=" ";
     $k=0;
     for ($i=0; $i<$cnt; $i++){
       for($j=0; $j<count($product[$i]); $j++){
         $newstr=$product[$i][$j];
          $query  = "
            select round(avg(oc_cpc)), round(avg(oc_cpm))
            from adgage
            where CP_startdate>='$startday' and CP_enddate<='$endday' and CP_category=$category and Media_code=$device and Media_name='$mediasearch[$i]' and cp_campaignname in
                (select cp_campaignname
                from adgage
                where CP_startdate>='$startday' and CP_enddate<='$endday' and CP_category=$category and Media_code=$device and Media_name='$mediasearch[$i]' and Prod_namebymedia='$newstr')";

          $result = mysqli_query($connect, $query);
          while($data = mysqli_fetch_row($result)){
            $CPC_M[3][$k]=$data[0];
            $CPC_M[4][$k]=$data[1];
            $k++;
          }
          }
        }
      echo json_encode($CPC_M);
    }
    break;
  }
mysqli_close($connect);
?>
