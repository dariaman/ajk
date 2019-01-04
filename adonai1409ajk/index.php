<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :penting_kaga@yahoo.com
// ----------------------------------------------------------------------------------
include_once('ui.php');
include_once ("../includes/functions.php");
connect();

if (session_is_registered('nm_user')) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));
if ($q['id_cost']!="") {	echo '<script language="Javascript">window.location="login.php?op=logout"</script>';	}
}
echo '</br>';
echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
	<tr><td align="center"><font color="#ed2124" size="7"><img src="../image/logo_adonai_1.gif" width="50"> A D O N A I </font> <font size="7">| Pialang Asuransi</font></td></tr>
	<tr><td align="center"><font color="#ffa800" size="5">Aplikasi Asuransi Jiwa Kredit dan Pensiunan</font><br /><br /><td></tr></table>';
echo '</br>';
echo '<table border="0" width="50%" cellpadding="5" cellspacing="1" align="center">
	<form method="post" action="index.php">
	<tr><td width="15%" align="right">Company Name</td>
	  <td width="30%">: <select id="cat" name="cat" onchange="reload(this.form)">
	  	<option value="">---Select Company---</option>';
$quer2=$database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name ASC');
while($noticia2 = mysql_fetch_array($quer2)) {
if($noticia2['id']==$cat){echo '<option selected value="'.$noticia2['id'].'">'.$noticia2['name'].'</option><BR>';}
else{echo  '<option value="'.$noticia2['id'].'">'.$noticia2['name'].'</option>';}
}
echo '</select><input type="submit" name="cari" value="click" class="button"></td></tr>
		</form>
		</table>';
//echo $futoday;
$peserta = $database->doQuery('UPDATE fu_ajk_peserta SET status_aktif="Maturity" WHERE kredit_akhir < "'.$futoday.'" AND  status_aktif="Inforce" AND del IS NULL');
$no=0;
$menu=array();
$menu2=array();
if ($_REQUEST['cat'])		{	$satu = 'AND id_cost LIKE "%' . $_REQUEST['cat'] . '%"';		}
$metsatistik = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_dn!="" '.$satu.' AND del IS NULL GROUP BY MONTH(kredit_tgl)');
while($Rsatistik=mysql_fetch_array($metsatistik)){
	$menu[] = $Rsatistik['totalpremi'];
	$menu2[] = $Rsatistik['kredit_jumlah'];
	$menu3[] = $Rsatistik['id_cost'];
}

//jurus rahmad...hahahahah
//$aray="'".join("','",$menu)."'";
$aray=join(" ,",$menu);
$aray2=join(" ,",$menu2);
$aray3=join(" ,",$menu3);
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<script type="text/javascript" src="js/modules/rahmadcharts.js"></script>
		<script type="text/javascript" src="js/highcharts.js"></script>
		<script type="text/javascript" src="js/modules/exporting.js"></script>
		<script type="text/javascript">
			var chart;
			$(document).ready(function() {
				chart = new Highcharts.Chart({
					chart: {
						renderTo: 'container',
						zoomType: 'xy'
					},
					title: {
						text: 'Statistik Peserta'
					},
					subtitle: {
						text: 'Source: ADONAI | Pialang Asuransi'
					},
					xAxis: [{
						categories: ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12']
					}],
					yAxis: [{ // Primary yAxis
						labels: {
							formatter: function() {
								return this.value +'';
							},
							style: {
								color: '#89A54E'
							}
						},
						title: {
							text: 'UP',
							style: {
								color: '#89A54E'
							}
						}
					}, { // Secondary yAxis
						title: {
							text: 'Premium',
							style: {
								color: '#4572A7'
							}
						},
						labels: {
							formatter: function() {
								return this.value +'';
							},
							style: {
								color: '#4572A7'
							}
						},
						opposite: true
					}],
					tooltip: {
						formatter: function() {
							return ''+
								this.x +': '+ this.y +
								(this.series.name == 'ADONAI | Pialang Asuransi' ? '' : '');
						}
					},
					legend: {
						layout: 'vertical',
						align: 'left',
						x: 100,
						verticalAlign: 'top',
						y: 50,
						floating: true,
						backgroundColor: '#FFFFFF'
					},
					series: [{
						name: 'UP',
						color: '#4572A7',
						type: 'column',
						yAxis: 1,
						data: [<?php echo $aray;?>]

					}, {
						name: 'Premium',
						color: '#89A54E',
						type: 'spline',
						data: [<?php echo $aray2;?>]
					}]
				});


			});

		</script>

	</head>
	<body>

		<!-- 3. Add the container -->
		<div id="container" style="width: 800px; height: 400px; margin: 0 auto"></div>


	</body>
</html>