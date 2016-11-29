$(function() {
  $('.chosen-select').chosen();
  $('.chosen-select-deselect').chosen({allow_single_deselect: true });
});

function show(v){
  if(v == "search_media"){
    document.getElementById('search_media').style.display = "";
    document.getElementById('search_product').style.display = "none";
    document.getElementById('row').style.display = "none";
  }else{
    document.getElementById('search_media').style.display = "none";
    document.getElementById('search_product').style.display = "";
    document.getElementById('row').style.display = "";
  }
}

function insert_row(){
  var table=document.getElementById("product_search");
  var row=table.insertRow();
  var x= table.rows.length;

  row.insertCell(0).innerHTML="<select data-placeholder='매체를 선택하세요' class='chosen-select-deselect' onChange='selectMedia(this,this.value)'><option value=''></option>"
  <?
    $connect = mysqli_connect('localhost','root','autoset','adgage_data');
    $query  = 'select distinct trim(Media_name) from adgage order by Media_name asc';
    $result = mysqli_query($connect, $query);
    while($data = mysqli_fetch_row($result)){
  ?>+"<option><?=$data[0]?>"
  <?}?>+"</select>";
  row.insertCell(1).innerHTML="<select class='chosen-select' data-placeholder='상품을 선택하세요' multiple tabindex='4' id='product"+x+"'><option value=''></option></select>"
  row.insertCell(2).innerHTML="<span class='glyphicon glyphicon-minus-sign' style='color:red' onclick='delete_row(this)'></span>";
  $('.chosen-select').chosen();
  $('.chosen-select-deselect').chosen({
    allow_single_deselect: true
  });
}

function delete_row(r) {
  var i = r.parentNode.parentNode.rowIndex;
  document.getElementById("product_search").deleteRow(i);
}

function delete_all(){
  $('option').prop('selected', false);
  $('select').trigger('chosen:updated');
  var table=document.getElementById("product_search");
  var row=table.rows.length;
  for(var i=row-1;i>2;i--){
    table.deleteRow(i);
  }
}

function entiredate(){ //날짜 전체기간 눌렀을때 자동으로 value채우기
  <?
    $connect = mysqli_connect("localhost","root","autoset","adgage_data");
    $query  = "select distinct CP_startdate from adgage order by CP_startdate asc limit 1;";
    $result = mysqli_query($connect, $query);
    while($data = mysqli_fetch_row($result)){?>
    document.getElementById("start").value=<?=json_encode($data[0])?>;
  <?}?>
  <?
    $connect = mysqli_connect("localhost","root","autoset","adgage_data");
    $query  = "select distinct CP_enddate from adgage order by CP_enddate desc limit 1;";
    $result = mysqli_query($connect, $query);
    while($data = mysqli_fetch_row($result)){?>
    document.getElementById("end").value=<?=json_encode($data[0])?>;
  <?}?>
}

function selectMedia(h,v){
  var i = h.parentNode.parentNode.rowIndex+1;
  var loc="product"+i;

  $.ajax({
    url:"Adgage.php",
    type:"GET",
    data:"x="+v,
    dataType:"json",
    success:function(data){
      var str;
      for(var i=0; i<data.length; i++){//오름차순이라 맨 앞은 공백, 인덱스 1부터 시작
        str += "<option value='"+data[i]+"'>"+data[i];
      }
       document.getElementById(loc).innerHTML=str;
       $('#'+loc).trigger("chosen:updated");
    }
  })
 }

function check(sd,ed){
  if(sd>ed){
    alert("기간을 다시 설정해주세요");
  }
}

function getResult(startday,endday,category,device,indicator,search){

  if(startday==''||endday==''||category==''||device==''||indicator==''||search==''){
    alert("조회조건을 모두 채워주세요");
    return false;
  }

  if(search=='search_media'){
    var media = $('#mediasearch').val();
    var product=0;
  }
  else{
    var media=[];
    var obj = $("select[name='p_media']");
    $(obj).each(function(i){
      if($(this).val()!=''){
        media[i] = $(this).val();
      }
    });

    var product=[];
    var obj = $("select[name='product']");
    $(obj).each(function(i){
        product[i] = $(this).val();
    });
  }

  if(indicator=='CTR'){
    $('#result_form').show();
    $('#result_form2').hide();
    $('#result_table').bootstrapTable('removeAll');

    $.ajax({
      url:"search.php",
      type:"POST",
      data : {"startday":startday,"endday":endday,"category":category,"device":device,"indicator":indicator,"search":search,"mediasearch":media, "product": product},
      success:function(data){
        $('#chart1_space').show();
        $('#chart2_space').hide();
        $('#chart3_space').hide();

        var newArr = eval(JSON.parse(data));
        var chartdata=new Array();
        for(var a=1;a<newArr.length;a++){
          chartdata[a-1]=newArr[a];
        }
        var sumavg="업종의 합산평균 "+newArr[0][0];
        var camavg="캠페인 합산평균 "+newArr[0][1];

        if(search=="search_product"){
          var p_label=new Array();
          var k=0;
          for(var i=0;i<media.length;i++){
            for(var j=0;j<product[i].length;j++){
              p_label[k]="["+media[i]+"]"+product[i][j]+"";
              k++;
            }
          }
          var lineChart = new Chartist.Bar('#chart1', {
            labels: p_label,
            series: chartdata
            }, {
            reverseData: true,
            seriesBarDistance: 30,
            horizontalBars: true,
            chartPadding: {
              right : 100
            },
            axisY: {
              offset: 50,
              showGrid: true,
            },
            axisX:{
              showGrid: true,
              showLabel : false
            },
            targetLine: {
              value: newArr[0][0],
              class: 'ct-target-line'
            },
            targetLine2: {
              value: newArr[0][1],
              class: 'ct-target-line2'
            },
            plugins: [
              Chartist.plugins.ctBarLabels(),
              Chartist.plugins.legend({
                legendNames: ['합산평균','캠페인평균',sumavg,camavg]
              })
            ]
          });

          $('#result_table').bootstrapTable('showColumn', 'product');
          var k=0;
          for(var i=0;i<media.length;i++){
            for(var j=0;j<product[i].length;j++){
              var appendData = [{
                "media": media[i],
                "product" : product[i][j],
                "ctr_sum": chartdata[0][k],
                "ctr_avg": chartdata[1][k]
              }];
              $('#result_table').bootstrapTable("append", appendData);
              k++;
            }
          }
        }

        else{
          var lineChart = new Chartist.Bar('#chart1', {
            labels: media,
            series: chartdata
            }, {
            reverseData: true,
            seriesBarDistance: 30,
            horizontalBars: true,
            chartPadding: {
              right: 100
            },
            axisY: {
              offset: 50,
              showGrid: true,
              showLabel : true
            },
            axisX:{
              showGrid: true,
              showLabel : false,
            },

            targetLine: {
              value: newArr[0][0],
              class: 'ct-target-line'
            },
            targetLine2: {
              value: newArr[0][1],
              class: 'ct-target-line2'
            },
            plugins: [
              Chartist.plugins.ctBarLabels(),
              Chartist.plugins.legend({
                legendNames: ['합산평균','캠페인평균',sumavg,camavg]
              })
            ]
          });

          $('#result_table').bootstrapTable('hideColumn', 'product');
          for(var i=0;i<media.length;i++){
            var appendData = [{
              "media": media[i],
              "ctr_sum": chartdata[0][i],
              "ctr_avg": chartdata[1][i]
            }];
            $('#result_table').bootstrapTable("append", appendData);
          }
        }

        $('p').empty();
        if(category=='all'){
          category="전체";
        }
        if(device=='all'){
          device="구분 안함";
        }

        $('p').append(category+' 업종 매체별 '+indicator+' - '+device);

        lineChart.on('draw', function(data) {
          if(data.type === 'grid' && data.index !== 0) {
            data.element.remove();
          }
        });

        function projectX(chartRect, bounds, value) {
          return chartRect.x1 + (chartRect.width() / bounds.max * value); //코드알기
        }

        lineChart.on('created', function(context) {
          var targetLineX = projectX(context.chartRect, context.bounds, context.options.targetLine.value);
          var targetLineX2 = projectX(context.chartRect, context.bounds, context.options.targetLine2.value);

          context.svg.elem('line', {
            y1: context.chartRect.y1,
            y2: context.chartRect.y2,
            x1: targetLineX,
            x2: targetLineX
          }, context.options.targetLine.class);

          context.svg.elem('line', {
            y1: context.chartRect.y1,
            y2: context.chartRect.y2,
            x1: targetLineX2,
            x2: targetLineX2
          }, context.options.targetLine2.class);
        });
      },
      error:function(status,error){
        console.log('Something went wrong', status, error);
      }
    })
  }

  else { //cpc,cpm부분
    $('#result_table2').bootstrapTable('removeAll');
    $('#result_form').hide();
    $('#result_form2').show();

    $('#chart1_space').hide();
    $('#chart2_space').show();
    $('#chart3_space').show();

    $.ajax({
      url:"search.php",
      type:"POST",
      data : {"startday":startday,"endday":endday,"category":category,"device":device,"indicator":indicator,"search":search,"mediasearch":media,"product":product},
      success:function(data){
        var newArr = eval(JSON.parse(data));
        var chartdata=new Array();
        for(var a=1;a<newArr.length+1;a++){
          chartdata[a-1]=newArr[a];
        }
        var sumavg_c="업종의 합산평균 "+newArr[0][0];
        var sumavg_m="업종의 합산평균 "+newArr[0][1];
        var camavg_c="캠페인 합산평균 "+newArr[0][2];
        var camavg_m="캠페인 합산평균 "+newArr[0][3];

        if(search=="search_product"){
          var p_label=new Array();
          var k=0;
          for(var i=0;i<media.length;i++){
            for(var j=0;j<product[i].length;j++){
              p_label[k]="["+media[i]+"]"+product[i][j]+"";
              k++;
            }
          }

          var lineChart = new Chartist.Bar('#chart2', {
            labels: p_label,
            series: [chartdata[0], chartdata[2]]
            }, {
            seriesBarDistance: 100,
            chartPadding: {
              top : 40
            },
            axisY: {
              offset: 50,
              showGrid: true,
              showLabel : false
            },
            axisX:{
              showGrid: false,
              showLabel : true,
            },
            targetLine: {
              value: newArr[0][0],
              class: 'ct-target-line'
            },
            targetLine2: {
              value: newArr[0][2],
              class: 'ct-target-line2'
            },
            plugins: [
              Chartist.plugins.ctBarLabels(),
              Chartist.plugins.legend({
                legendNames: ['합산평균','캠페인평균',sumavg_c,camavg_c]
              })
            ]
          });

          var lineChart2 = new Chartist.Bar('#chart3', {
            labels: p_label,
            series: [chartdata[1], chartdata[3]]
            }, {
            seriesBarDistance: 100,
            chartPadding: {
              top : 40
            },
            axisY: {
              offset: 50,
              showGrid: true,
              showLabel : false
            },
            axisX:{
              showGrid: false,
              showLabel : true
            },
            targetLine: {
              value: newArr[0][1],
              class: 'ct-target-line'
            },
            targetLine2: {
              value: newArr[0][3],
              class: 'ct-target-line2'
            },
            plugins: [
              Chartist.plugins.ctBarLabels(),
              Chartist.plugins.legend({
                legendNames: ['합산평균','캠페인평균',sumavg_m,camavg_m]
              })
            ]
          });

          $('#result_table2').bootstrapTable('showColumn', 'product');
          var k=0;
          for(var i=0;i<media.length;i++){
            for(var j=0;j<product[i].length;j++){
              var appendData = [{
                "media": media[i],
                "product": product[i][j],
                "cpc_sum": chartdata[0][k],
                "cpm_sum": chartdata[1][k],
                "cpc_avg": chartdata[2][k],
                "cpm_avg": chartdata[3][k]
              }];
              $('#result_table2').bootstrapTable("append", appendData);
              k++;
            }
          }
        }
        else{
          var lineChart = new Chartist.Bar('#chart2', {
            labels: media,
            series: [chartdata[0], chartdata[2]]
            }, {
            seriesBarDistance: 100,
            chartPadding: {
              top : 40
            },
            axisY: {
              offset: 50,
              showGrid: true,
              showLabel : false
            },
            axisX:{
              showGrid: false,
              showLabel : true,
            },
            targetLine: {
              value: newArr[0][0],
              class: 'ct-target-line'
            },
            targetLine2: {
              value: newArr[0][2],
              class: 'ct-target-line2'
            },
            plugins: [
              Chartist.plugins.ctBarLabels(),
              Chartist.plugins.legend({
                legendNames: ['합산평균','캠페인평균',sumavg_c,camavg_c]
              })
            ]
          });

          var lineChart2 = new Chartist.Bar('#chart3', {
            labels: media,
            series: [chartdata[1], chartdata[3]]
            }, {
            seriesBarDistance: 100,
            chartPadding: {
              top : 40
            },
            axisY: {
              offset: 50,
              showGrid: true,
              showLabel : false
            },
            axisX:{
              showGrid: false,
              showLabel : true
            },
            targetLine: {
              value: newArr[0][1],
              class: 'ct-target-line'
            },
            targetLine2: {
              value: newArr[0][3],
              class: 'ct-target-line2'
            },
            plugins: [
              Chartist.plugins.ctBarLabels(),
              Chartist.plugins.legend({
                legendNames: ['합산평균','캠페인평균',sumavg_m,camavg_m]
              })
            ]
          });
          $('#result_table2').bootstrapTable('hideColumn', 'product');
          for(var i=0;i<media.length;i++){
            var appendData = [{
                "media": media[i],
                "cpc_sum": chartdata[0][i],
                "cpm_sum": chartdata[1][i],
                "cpc_avg": chartdata[2][i],
                "cpm_avg": chartdata[3][i]
            }];
            $('#result_table2').bootstrapTable("append", appendData);
          }
        }

        $('#cpc').empty();
        $('#cpm').empty();
        if(category=='all'){
          category="전체";
        }
        if(device=='all'){
          device="구분 안함";
        }
        $('#cpc').append(category+' 업종 매체별 CPC - '+device);
        $('#cpm').append(category+' 업종 매체별 CPM - '+device);

        lineChart.on('draw', function(data) {
          if(data.type === 'grid' && data.index !== 0) {
            data.element.remove();
          }
        });

        lineChart2.on('draw', function(data) {
          if(data.type === 'grid' && data.index !== 0) {
            data.element.remove();
          }
        });

        function projectY(chartRect, bounds, value) {
          return chartRect.y1 - (chartRect.height() / bounds.max * value)
        }

        lineChart.on('created', function(context) {
          var targetLineY = projectY(context.chartRect, context.bounds, context.options.targetLine.value);
          var targetLineY2 = projectY(context.chartRect, context.bounds, context.options.targetLine2.value);

          context.svg.elem('line', {
            x1: context.chartRect.x1,
            x2: context.chartRect.x2,
            y1: targetLineY,
            y2: targetLineY
          }, context.options.targetLine.class);

          context.svg.elem('line', {
            x1: context.chartRect.x1,
            x2: context.chartRect.x2,
            y1: targetLineY2,
            y2: targetLineY2
          }, context.options.targetLine2.class);
        });

        lineChart2.on('created', function(context) {
          var targetLineY = projectY(context.chartRect, context.bounds, context.options.targetLine.value);
          var targetLineY2 = projectY(context.chartRect, context.bounds, context.options.targetLine2.value);

          context.svg.elem('line', {
            x1: context.chartRect.x1,
            x2: context.chartRect.x2,
            y1: targetLineY,
            y2: targetLineY
          }, context.options.targetLine.class);

          context.svg.elem('line', {
            x1: context.chartRect.x1,
            x2: context.chartRect.x2,
            y1: targetLineY2,
            y2: targetLineY2
          }, context.options.targetLine2.class);
        });
      },
      error:function(status,error){
        console.log('Something went wrong', status, error);
      }
    })
  }
}
