

<?php
error_reporting(0);
session_start();
include "../includes/fu6106.php";
$sqlku="SHOW TABLES FROM $db";

$hasilku=mysql_query($sqlku);

echo "<form action=\"\" method=\"post\"> <table><tr><td><label>Nama table</label></td><td> :</td><td> <select name=\"ok\" >";
while ($row = mysql_fetch_row($hasilku)) {
	if($_POST['ok']==$row[0]){
		echo "<option value=\"{$row[0]}\" selected>{$row[0]}</option>";
	}else{
		echo "<option value=\"{$row[0]}\">{$row[0]}</option>";
	}
}

if(isset($_POST['param'])){
	$param=$_POST['param'];
}else{
	$param="input_time";
}
echo '</select></td></tr>';
echo '<tr><td><label>Tanggal Tarik data </label></td><td> :</td><td><input type="text" name="date1" value="'.$_POST['date1'].'">
		s.d
		<input type="text" name="date2" value="'.$_POST['date2'].'"></td></tr>
		<tr><td><label>Parameter table</label></td><td> : </td><td>
		<input type="text" name="param" value="'.$param.'"><i>input_time atau input_date</i></td></tr>
		<tr><td><button type="submit" name="tampil">Tampil</button><td></tr></form>';
if(isset($_POST['tampil'])){


	$date1=$_POST['date1'];
	$date2=$_POST['date2'];
	
	$sqllos="SELECT DATEDIFF('".$date2."','".$date1."') as date_res";
	$hasillos=mysql_query($sqllos);
	$datalos=mysql_fetch_array($hasillos);
	$jmldate=$datalos['date_res'];
	
	echo '<table border="1"><tr><td>Tanggal</td><td align="center">Jumlah Transaksi</td></tr>';
	for($x=0;$x<=$jmldate;$x++){
		//$dateku=date_add(date("Y-m-d"), date_interval_create_from_date_string("1 days"));
		$q="select DATE_FORMAT(".$param.",'%Y-%m-%d') as input_time,count(".$param.") as jml from ".$_POST['ok']." where
		day(".$param.")=DAY(DATE_ADD('".$date1."',INTERVAL ".$x." DAY))
			and month(".$param.")=MONTH(DATE_ADD('".$date1."',INTERVAL ".$x." DAY))
			and year(".$param.")=YEAR(DATE_ADD('".$date1."',INTERVAL ".$x." DAY)) group by DATE_FORMAT(".$param.",'%Y-%m-%d')";
		
		$hasilq=mysql_query($q);
		$jmlku=mysql_num_rows($hasilq);
		
			while($dataq=mysql_fetch_array($hasilq)){
				echo '<tr><td>'.$dataq['input_time'].'</td><td align="center">'.$dataq['jml'].'</td></tr>';
			}
		
	}
		echo '</table>';
}