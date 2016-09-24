<?php
	error_reporting(E_ALL & ~E_NOTICE);
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>カレンダー</title>
<style type="text/css">
table{
	border-collapse:collapse;
}
	
td{
	border:solid 1px #ccc;
	padding:2px 5px;
	text-align:center;	
}

td div{padding:2px;}

td div.sun{
	background:#FF5555;
}

td div.sat{
	background:#93CEFF;
}

td div.today{
	color:#178600;
}
td div.after_day{
	color:#ccc;
}

</style>
</head>
<?php
if(empty($_GET["day"]) || $_GET["day"] == ""){
	$date_time = time();
}else if(!preg_match("/^[0-9]+$/", $_GET["day"]) || $_GET["day"] >= mktime(0,0,0,$month,$d,2020) || $_GET["day"] <= mktime(0,0,0,$month,$d,2000)){
	$date_time = time();
}else{
	$date_time = $_GET["day"];
}
$year = date("Y",$date_time);
$month = date("n",$date_time);
$now_day = date("j",$date_time);
$first_day = mktime(0,0,0,$month,1,$year);
$start_m = date("w",$first_day);
$n_day = date("t",$first_day);
#曜日の取得
for($d = 1; $d <= $n_day; $d++){
	$day_week[$d] = date("w",mktime(0,0,0,$month,$d,$year));
}
#カレンダーの日数出力用変数
$day_count = 1;
#年月日出力用
$out_YM = date("Y年n月",mktime(0,0,0,$month,1,$year));
#本日の日付を取得する用
$now_Ynj = mktime(0,0,0,date("n"),date("j"),date("Y"));
/*
	echo date("Y-m-d H-i-s");
 	echo date("Y-m-d",mktime(0,0,0,$month,$now_day,$year));
*/
	#月のスタート曜日+月日数(カレンダーの表示日数)
	$month_day = $n_day + $start_m;
	if($month_day == 28){
		$month_day = 28;
	}else if($month_day <= 35){
		$month_day = 35;
	}else{
		$month_day = 42;
	}
	for($i=1; $i<=$month_day; $i++){
		$trs = ($i ==1)?"<tr>\n":"";
		if($i == $month_day){
			$tre ="</tr>\n";
		}else if($i % 7 ==0 && $i != 1){
			$tre ="</tr>\n<tr>\n";
		}else{
			$tre ="";
		}
		
		if($i <= $start_m || $i > ($n_day + $start_m)){
			$body .= $trs."<td>&nbsp;</td>\n".$tre;
		}else{
			#本日の日かどうかの判定用
			$show_Ynj =mktime(0,0,0,$month,$day_count,$year);
			if($now_Ynj == $show_Ynj){
				if($day_week[$day_count] == 0){
					$now_class = " class='sun today'";
				}else if($day_week[$day_count] == 6){
					$now_class = " class='sat today'";
				}else{
					$now_class = " class='today'";
				}
			}else if($now_Ynj > $show_Ynj){
				$now_class = " class='after_day'";
			}else{
				if($day_week[$day_count] == 0){
					$now_class = " class='sun'";
				}else if($day_week[$day_count] == 6){
					$now_class = " class='sat'";
				}else{
					$now_class = "";
				}
				
			}
			//=======================
			$body .= $trs."<td id='".$show_Ynj."'><div".$now_class.">{$day_count}</div></td>\n".$tre;
			$day_count++;
		}
	}
?>
<body>
	<div id="calendar">
  	<table>
    	<caption><?php echo $out_YM; ?></caption>
      <tr>
      	<th>日</th>
      	<th>月</th>
        <th>火</th>
        <th>水</th>
        <th>木</th>
        <th>金</th>
        <th>土</th>
       </tr>
      <?php echo $body; ?>
    </table>
    <div>
    <p><a href="./?day=<?php echo strtotime("+1 month",$first_day); ?>">次月</a></p>
    <p><a href="./?day=<?php echo strtotime("-1 month",$first_day); ?>">前月</a></p>
    </div>
  </div>
</body>
</html>