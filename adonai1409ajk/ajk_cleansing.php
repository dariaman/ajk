<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("../includes/functions.php");
echo "<script language=\"JavaScript\" src=\"javascript/js/form_validation.js\"></script>";
connect();
if (session_is_registered('nm_user')) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));	}
$futglInd = date("d-m-Y");
switch ($_REQUEST['er']) {
	case "Updated":
$metreg = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['id_reg'].'"'));
$metare = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_area WHERE id="'.$_REQUEST['id_area'].'"'));
$metcab = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id="'.$_REQUEST['id_cabang'].'"'));

$pesertaclean = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id="'.$_REQUEST['id'].'"'));
$metupdatewilayah = $database->doQuery('UPDATE fu_ajk_peserta SET regional="'.$metreg['name'].'", area="'.$metare['name'].'", cabang="'.$metcab['name'].'" WHERE id="'.$_REQUEST['id'].'"');
$metupdatedn = $database->doQuery('UPDATE fu_ajk_dn SET id_regional="'.$metreg['name'].'", id_area="'.$metare['name'].'", id_cabang="'.$metcab['name'].'" WHERE dn_kode="'.$pesertaclean['id_dn'].'" AND id_cost="'.$pesertaclean['id_cost'].'"');
echo' <center>Data wilayah telah di Update oleh '.$_SESSION['nm_user'].' pada tanggal '.$futglInd.'</center><meta http-equiv="refresh" content="2; url=ajk_cleansing.php">';
		;
		break;
	case "cedit":
echo '<table border="0" cellpadding="5" cellspacing="1" width="100%"><tr><th width="100%" align="left">Modul Cleansing</font></th><th><a href="ajk_cleansing.php"><img src="image/back.gif"></th></tr></table>';
echo '<form method="post" action="" name="frmcust" onSubmit="return valcust()">
	  <table border="0" width="70%" cellpadding="2" cellspacing="2" align="center">
	  <input type="hidden" name="id" value="'.$_REQUEST['id'].'"><tr><th width="15%">Nama</th><th>Kredit Awal</th><th>Tenor</th><th>Kredit Jumlah</th><th>Regional</th><th>Regional Lama</th><th>Area</th><th>Area Lama</th><th>Cabang</th><th>Cabang Lama</th></tr>';
$met = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id="'.$_REQUEST['id'].'"'));
echo '<tr bgcolor="#DED"><td>'.$met['nama'].'</td>
			  <td align="center">'.$met['kredit_tgl'].'</td>
			  <td align="center">'.$met['kredit_tenor'].'</td>
			  <td align="right">'.duit($met['kredit_jumlah']).'</td>
	   		  <td align="center">'.$met['regional'].'</td>
	   		  <td align="center">'.$met['regional_lama'].'</td>
	   		  <td align="center">'.$met['area'].'</td>
			  <td align="center">'.$met['area_lama'].'</td>
	   		  <td align="center">'.$met['cabang'].'</td>
	   		  <td align="center">'.$met['cabang_lama'].'</td>
		  </tr>
		  <tr><td><b><u>Cleansing Data :</b></u></td></tr>
		  <tr><td colspan="6">
		  	<table width="100%" border="0">
		  	<tr><td>Regional</td>
			<td>: <select name="id_reg" id="id_reg">
	<option value="">-- Select Regional --</option>';
		$rows = mysql_query('select * from fu_ajk_regional WHERE id!="'.$met['id_cost'].'"');
		while($row = mysql_fetch_array($rows)) {
			$sel = ""; if ($_POST["id_reg"] == $row["id"]) $sel = ' selected="selected"';
			echo '<option value="'.$row["id"].'"'.$sel.'>'.$row["name"].'</option>';
		}
echo '</select></td>
		<td>Pilih Area</td>
		<td id="area">: <select name="id_area" id="id_area">
	<option value="">-- Select Area --</option>
</select></td>
		<td>Pilih Cabang</td>
	  	  <td id="cabang">:<select name="id_cabang" id="id_cabang">
	<option value="">-- Select Cabang --</option>
</select></td></tr>
		</table>
	  <tr><td colspan="6" align="center"><input type="Submit" name="er" value="Updated"> &nbsp; <a href="ajk_cleansing.php">Cancel</a></td></tr>';
echo '</table></form>';
	;
		break;
	default:
echo '<table border="0" cellpadding="5" cellspacing="1" width="100%"><tr><th width="100%" align="left">Modul Cleansing</font></th></tr></table>';
echo '<fieldset>
	<legend align="center">S e a r c h</legend>
	<table border="0" width="50%" cellpadding="3" cellspacing="1">
	<form method="post" action="">
	<tr><td width="10%">Nama</td><td>: <input type="text" name="cnama" value="'.$_REQUEST['cnama'].'"></td></tr>
	<tr><td>No. DN</td><td>: <input type="text" name="cdn" value="'.$_REQUEST['cdn'].'"></td></tr>
	<tr><td colspan="3"><input type="submit" name="cari" value="Cari"></td></tr>
	</form>
	</table></fieldset>';
if ($_REQUEST['cari']=="Cari") {
echo '<table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#E2E2E2">
	<tr><th width="1%" rowspan="2">Opt</th>
		<th width="1%" rowspan="2">No</th>
		<th width="3%" rowspan="2">SPAJ</th>
		<th width="3%" rowspan="2">DN</th>
		<th rowspan="2">Nama Debitur<br /><font size="1"><i>(sesuai KTP/SIM)</i></font></th>
		<th width="1%" rowspan="2">P/W</th>
		<th rowspan="2">Tgl Lahir</th>
		<th rowspan="2">Usia</th>
		<th width="20%" colspan="4">Status Kredit</th>
		<th width="5%" rowspan="2">Total Premi</th>
		<th width="7%" rowspan="2">Regional</th>
		<th width="7%" rowspan="2">Regional Lama</th>
		<th width="7%" rowspan="2">Area</th>
		<th width="7%" rowspan="2">Area Lama</th>
		<th width="7%" rowspan="2">Cabang</th>
		<th width="7%" rowspan="2">Cabang Lama</th>
	</tr>
	<tr><th>Kredit Awal</th>
		<th>Jumlah</th>
		<th>Tenor</th>
		<th>Kredit Akhir</th>
	</tr>';

if ($_REQUEST['cnama'])		{	$satu = 'AND nama LIKE "%' . $_REQUEST['cnama'] . '%"';		}
if ($_REQUEST['cdn'])		{	$dua = 'AND id_dn LIKE "%' . $_REQUEST['cdn'] . '%"';		}
if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}
else {	$m = 0;		}
$erClean = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id!="" '.$satu.' '.$dua.' ORDER BY nama ASC LIMIT ' . $m . ' , 25');
	$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_peserta WHERE id != "" '.$satu.' '.$dua.' ORDER BY nama ASC'));
	$totalRows = $totalRows[0];

$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while ($rClean = mysql_fetch_array($erClean)) {
if (($no % 2) == 1)	$objlass = 'tbl-odd'; else				$objlass = 'tbl-even';

echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		  <td align="center"><a href="ajk_cleansing.php?er=cedit&id='.$rClean['id'].'"><img src="image/edit3.png" width="15" border="0"></a></td>
		  <td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
		  <td>'.$rClean['spaj'].'</td>
		  <td>'.substr($rClean['id_dn'],6).'</td>
		  <td>'.$rClean['nama'].'</td>
		  <td align="center">'.$rClean['gender'].'</td>
		  <td align="center">'.$rClean['tgl_lahir'].'</td>
		  <td align="center">'.$rClean['usia'].'</td>
		  <td align="center">'.$rClean['kredit_tgl'].'</td>
		  <td align="right">'.duit($rClean['kredit_jumlah']).'</td>
		  <td align="center">'.$rClean['kredit_tenor'].'</td>
		  <td align="center">'.$rClean['kredit_akhir'].'</td>
		  <td align="right">'.duit($rClean['totalpremi']).'</td>
		  <td align="center">'.$rClean['regional'].'</td>
		  <td align="center">'.$rClean['regional_lama'].'</td>
		  <td align="center">'.$rClean['area'].'</td>
		  <td align="center">'.$rClean['area_lama'].'</td>
		  <td align="center">'.$rClean['cabang'].'</td>
		  <td align="center">'.$rClean['cabang_lama'].'</td>
		  </tr>';
}
echo '<tr><td colspan="27">';
echo createPageNavigations($file = 'ajk_cleansing.php?cari=Cari&nama='.$_REQUEST['cnama'].'&', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
echo '<b>Total Data Peserta: <u>' . $totalRows . '</u></b></td></tr>';
echo '</table>';
}
		;
} // switch
?>
<!--WILAYAH COMBOBOX-->
<script src="javascript/metcombo/prototype.js"></script>
<script src="javascript/metcombo/dynamicombo.js"></script>
<!--WILAYAH COMBOBOX-->
<script>
document.observe("dom:loaded",function(){
	new DynamiCombo( "id_reg" , {
		elements:{
			"id_area":		{url:'javascript/metcombo/data.php?req=c', value:'id', label:'name', init:'<?php echo $_POST["id_area"] ?>'},
			"id_cabang":	{url:'javascript/metcombo/data.php?req=d', value:'id', label:'name', init:'<?php echo $_POST["id_cabang"] ?>'}
		},
		loadingImage:'loader1.gif',
		loadingText:'Loading...',
		debug:0
	} )
});
</script>