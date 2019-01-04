<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :penting_kaga@yahoo.com
// ----------------------------------------------------------------------------------
include_once('ui.php');
include_once ("../includes/functions.php");
connect();
if (isset($_SESSION['nm_user'])) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));	}
switch ($_REQUEST['er']) {
	case "qSet_passw":
$met_reset = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_master_setting WHERE idsett = 1 '));
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%"><tr><th width="95%" align="left">Modul Reset Password (Kata Kunci User)</th></tr></table>';
if ($_REQUEST['ope']=="Simpan") {
if (!$_REQUEST['jhari'])  $error1 .='<blink><font color=red>Silahkan tentukan jumlah hari untuk mereset password user !</font></blink><br>';
if ($error1)
{	}
else
{
$met_reset_passw = $database->doQuery('UPDATE fu_ajk_master_setting SET reset_passw_user="'.$_REQUEST['jhari'].'", update_by="'.$q['nm_user'].'", update_date="'.$futgl.'" WHERE idsett = 1 ');
header("location:master_setting.php?er=qSet_passw");
}
}
echo '<table border="0" width="50%" align="center">
	  <tr><td>
	  	<form name="f1" method="post" class="input-list style-1 smart-green">
		<h1>Reset Password User setiap '.duit($met_reset['reset_passw_user']).' hari</h1>
		<label><span><font color="red">Catatan : <br />Password user akan direset secara otomatis oleh sistem berdasarkan jumlah hari yang di input oleh Administrator, pada saat sistem mereset password user, sistem akan memberikan notifikasi ke user melalui email dari masing-masing user yang passwordnya telah direset.</font></span></label>
		<label><span>Jumlah Hari<font color="red">*</font> '.$error1.'</span><input type="text" name="jhari" value="'.$met_reset['reset_passw_user'].'" size="30" maxlength="2" placeholder="Jumlah Hari" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"></label>
		<label><span>&nbsp;</span><input type="submit" name="ope" value="Simpan" class="button" /></label>
		</form>
	  </td></tr>
	  </table>';
		;
		break;
	case "tabgen":
echo '<script>
		function generator(){
			var time =
		}
	</script>';
echo '<center><form action="" method="POST">
	<input name="action" value="generate-code"
	<input type="submit" value="Generate">
	</form></center>
	<h1 id="output" style="margin-left: auto;margin-right:auto;text-align: center;border:1px solid black; width: 200px">';
		$hour = date("H");
		$month= date("m");
		$token="ADONAI";
if(isset($_POST['action'])){
	if($_POST['action']=="generate-code"){
		$string = $hour.$month.$token;
		$code= md5($string);
		$code = substr($code,0,6);
		echo $code;
	}
}
		;
		break;
	case "tabgps":
		echo '<!-- PAGE RELATED SCRIPTS -->


<script type="text/javascript" src="javascript/google-maps/google-maps-default.js"></script>
<script type="text/javascript" src="javascript/jquery.js"></script>
<style>
.map-canvas {
position:relative;
width:100%;
height:400px;
}

.map-canvas .info-window-content h2 {
font-size:18px;
font-weight:600;
margin-bottom:8px;
}

.map-canvas .info-window-content p {
margin-top:20px;
text-align:center;
font-size:12px;
color:#999;
text-shadow:none;
}

.map-canvas-square {
height:200px;
}
</style>
';
echo '<center><div id="mapCanvas" class="map-canvas"></div>


<table id="table-gps" border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#bde0e6">
<thead>
	<tr><th width="1%">No</th>
		<th width="5%">USER</th>
		<th width="10%">Full Name</th>
		<th width="10%">Cabang</th>
		<th width="5%">Longitude</th>
		<th width="5%">Latitude</th>
		<th width="10%">Nomor Imei</th>
		<th width="8%">Tanggal</th>
		<th width="5%">Opt</th>
	</tr>
</thead><tbody>';
		$querygps = mysql_query("SELECT * FROM fu_ajk_gps
		LEFT JOIN user_mobile ON username = user_mobile.nip_primary WHERE longitude !='' ORDER BY datettime DESC");
		$li_row = 1;
		while($rowgps = mysql_fetch_array($querygps)){
			if (($li_row % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
			$username = $rowgps['username'];
			$namalengkap = $rowgps['namalengkap'];
			$longitude = $rowgps['longitude'];
			$latitude = $rowgps['latitude'];
			$imei = $rowgps['imei'];
			$datetime = $rowgps['datettime'];
			$idcab = $rowgps['cabang'];
			$qcab = mysql_query("SELECT * FROM fu_ajk_cabang WHERE id = '".$idcab."'");
			$rcba = mysql_fetch_array($qcab);
			$namacabang = $rcba['name'];
			//$datetime = date('d-m-Y h:m:s', strtotime($datetime));
			$dttime = explode(" ", $rowgps['datettime']);

			echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
					<td><center>'.$li_row.'</center></td>
					<td>'.$username.'</td>
					<td>'.$namalengkap.'</td>
					<td>'.$namacabang.'</td>
					<td><center>'.$longitude.' </center></td>
					<td><center>'.$latitude.' </center></td>
					<td><center>'.$imei.' </center></td>
					<td><center>'._convertDate($dttime[0]).' '.$dttime[1].' </center></td>
					<td><center> <button onclick="mygps('.$longitude.','.$latitude.')">View Maps</button> </center></td>
				</tr>';
			$li_row++;
		}
	echo '</tbody></table>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
<link rel="stylesheet" type="text/css" href="javascript/datatables/dataTables.bootstrap.css">
<script type="text/javascript" language="javascript" src="javascript/datatables/jquery.dataTables.min.js"></script>
<script type="text/javascript">
      $(document).ready(function(){
      	$("#table-gps").DataTable();

      });

    </script>
';
		;
		break;
	default:
		;
} // switch

?>