<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ADONAI - AJK Online 2014
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("../includes/functions.php");
connect();
if (session_is_registered('nm_user')) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));	}
switch ($_REQUEST['r']) {
case "vdeb":
echo '<table border="0" cellpadding="3" cellspacing="1" width="100%"><tr><th width="100%" align="left">Modul Peserta - View</th><th><a href="ajk_uploader_fu.php"><img src="../image/Backward-64.png" width="20" border="0"></a></th></tr></table>';

$met = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id="'.$_REQUEST['vid'].'"'));
if ($_REQUEST['ope']=="Simpan") {
	$metEM = $_REQUEST['rpremi'] * $_REQUEST['rext_premi'] / 100;
if ($met['type_data']=="SPK") {
	$metUpdate = $database->doQuery('UPDATE fu_ajk_spak SET ext_premi="'.$_REQUEST['rext_premi'].'" WHERE id_cost="'.$met['id_cost'].'" AND id_polis="'.$met['id_polis'].'" AND spak="'.$met['spaj'].'"');
}
	$metnettpremi = $_REQUEST['rpremi'] + $_REQUEST['rbunga'] + $_REQUEST['rbiaya_adm'] + $metEM;
$el = $database->doQuery('UPDATE fu_ajk_peserta SET spaj="'.$_REQUEST['rspaj'].'",
													nama="'.$_REQUEST['rnama'].'",
													gender="'.$_REQUEST['rgender'].'",
													tgl_lahir="'.$_REQUEST['rdob'].'",
													kredit_tgl="'.$_REQUEST['rkredit_tgl'].'",
													kredit_jumlah="'.$_REQUEST['rkredit_jumlah'].'",
													kredit_tenor="'.$_REQUEST['rkredit_tenor'].'",
													premi="'.$_REQUEST['rpremi'].'",
													bunga="'.$_REQUEST['rbunga'].'",
													biaya_adm="'.$_REQUEST['rbiaya_adm'].'",
													ext_premi="'.$metEM.'",
													totalpremi="'.$metnettpremi.'",
													badant="'.$_REQUEST['rbadant'].'",
													badanb="'.$_REQUEST['rbadanb'].'",
													regional="'.$_REQUEST['rreg'].'",
													area="'.$_REQUEST['rarea'].'",
													cabang="'.$_REQUEST['rcabang'].'",
													update_by="'.$q['nm_lengkap'].'",
													update_time="'.$futgl.'"
													WHERE id="'.$_REQUEST['id'].'"');
	$mametganteng = mysql_fetch_array($database->doQuery('SELECT id, id_cost, id_dn, SUM(totalpremi) AS jNettPremi FROM fu_ajk_peserta WHERE id_dn="'.$met['id_dn'].'" GROUP BY id_dn'));
	$mametkasep = $database->doQuery('UPDATE fu_ajk_dn SET totalpremi="'.$mametganteng['jNettPremi'].'" WHERE id="'.$mametganteng['id_dn'].'"');
	echo '<center><h2>Data telah di edit oleh '.$q['nm_lengkap'].'.</h2></center><meta http-equiv="refresh" content="2;URL=ajk_uploader_fu.php">';
}
$metmedik = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id="'.$_REQUEST['vid'].'"'));
$metcost = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$metmedik['id_cost'].'"'));
$metpolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$metmedik['id_polis'].'"'));
echo '<center>
		<table border="0" cellpadding="0" cellspacing="2" width="100%" class="input-list style-1 smart-green">
		<tr><td width="10%">Nama Perusahaan</td><td width="1%">:</td><td><b>'.$metcost['name'].'</b></td></tr>
		<tr><td>Nama Produk</td><td width="1%">:</td><td><b>'.$metpolis['nmproduk'].'</b></td></tr>
		<tr><td colspan="3" width="50%" class="judulhead1"><b>DATA PESERTA</b></td>
			<td colspan="2" width="50%" class="judulhead1"><b>DATA KREDIT</b></td>
		</tr>
		<tr><td colspan="3" valign="top">
			<table width="100%" cellpadding="3" celsspacing="1">
				<tr><td>SPAJ</td><td width="1%">:</td><td><input type="text" name="rspaj" value="'.$metmedik['spaj'].'" size="5"disabled></td></tr>
				<tr><td width="20%">Nama</td><td width="1%">:</td><td><input type="text" name="rnama" value="'.$metmedik['nama'].'" size="50" disabled></td></tr>
				<tr><td>Jenis Kelamin</td><td width="1%">:</td><td><input type=radio '.pilih($metmedik["gender"], "P").'  name="rgender" value="P" disabled>P &nbsp; <input type=radio '.pilih($metmedik["gender"], "W").'  name="rgender" value="W" disabled>W</td></tr>
				<tr><td>Tanggal Lahir</td><td width="1%">:</td><td><input type="text" name="rdob" id="tanggal2" class="tanggal" value="'.$metmedik['tgl_lahir'].'"disabled>';
echo '</td></tr>
		  <tr><td>Tinggi Badan</td><td width="1%">:</td><td><input type="text" name="rbadant" value="'.$metmedik['badant'].'" size="1" disabled></td></tr>
		  <tr><td>Berat Badan</td><td width="1%">:</td><td><input type="text" name="rbadanb" value="'.$metmedik['badanb'].'" size="1" disabled></td></tr>
<tr><td width="10%">Regional</td><td width="1%">:</td><td><select id="rreg" name="rreg" disabled>';
	$rreg=$database->doQuery('SELECT * FROM fu_ajk_regional WHERE id_cost="'.$met['id_cost'].'" AND del IS NULL ORDER BY name ASC');
while($freg = mysql_fetch_array($rreg)) {
	$rreg2=mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE id_cost="'.$met['id_cost'].'" AND name="'.$met['regional'].'" AND del IS NULL'));
	echo  '<option value="'.$freg['name'].'" '._selected($freg['name'], $rreg2['name']).'>'.$freg['name'].'</option>';}
echo '</select></td></tr>
			<tr><td>Area</td><td width="1%">:</td><td><select id="rarea" name="rarea" disabled>';
	$rcab=$database->doQuery('SELECT * FROM fu_ajk_area WHERE id_cost="'.$met['id_cost'].'" AND del IS NULL ORDER BY name ASC');
while($fcab = mysql_fetch_array($rcab)) {
	$rcab2=mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_area WHERE id_cost="'.$met['id_cost'].'" AND name="'.$met['area'].'" AND del IS NULL'));
	echo  '<option value="'.$fcab['name'].'"'._selected($fcab["name"], $rcab2["name"]).'>'.$fcab['name'].'</option>';}
echo '</select></td></tr>
			<tr><td>Cabang</td><td width="1%">:</td><td><select id="rcabang" name="rcabang" disabled>';
	$rarea=$database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id_cost="'.$met['id_cost'].'" AND del IS NULL GROUP BY name ORDER BY name ASC');
while($farea = mysql_fetch_array($rarea)) {
	$rarea2=mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id_cost="'.$met['id_cost'].'" AND name="'.$met['cabang'].'" AND del IS NULL GROUP BY cabang'));
	echo  '<option value="'.$farea['name'].'"'._selected($farea["name"], $rarea2["name"]).'>'.$farea['name'].'</option>';}
echo '</select></td></tr>
		  </table>
		</td>
			<td colspan="2" valign="top">
			<table width="100%" cellpadding="3" celsspacing="1">
			<tr><td width="30%">Tanggal Kredit</td><td width="1%">:</td><td><input type="text" name="rkredit_tgl" id="tanggal1" class="tanggal" value="'.$metmedik['kredit_tgl'].'" disabled></td></tr>
			<tr><td>Kredit Jumlah</td><td width="1%">:</td><td><input type="text" name="rkredit_jumlah" value="'.duit($metmedik['kredit_jumlah']).'" onkeypress="return isNumberKey(event)" disabled></td></tr>
			<tr><td>Tenor</td><td width="1%">:</td><td><input type="text" name="rkredit_tenor" value="'.$metmedik['kredit_tenor'].'" size="3" onkeypress="return isNumberKey(event)" disabled></td></tr>
			<tr><td>Premi</td><td width="1%">:</td><td><input type="text" name="rpremi" value="'.duit($metmedik['premi']).'" size="10" onkeypress="return isNumberKey(event)" disabled></td></tr>
			<tr><td>Bunga</td><td width="1%">:</td><td><input type="text" name="rbunga" value="'.$metmedik['bunga'].'" size="3" onkeypress="return isNumberKey(event)" disabled></td></tr>
			<tr><td>Biaya Administrasi</td><td width="1%">:</td><td><input type="text" name="rbiaya_adm" value="'.duit($metmedik['biaya_adm']).'" size="3" onkeypress="return isNumberKey(event)" disabled></td></tr>
			<tr><td>Extra Premi (%)</td><td width="1%">:</td><td><input type="text" name="rext_premi" value="'.duit($metmedik['ext_premi']).'" size="3" maxlength="3" onkeypress="return isNumberKey(event)" disabled></td></tr>
			<tr><td>Total Premi</td><td width="1%">:</td><td><input type="text" name="rtotalpremi" value="'.duit($metmedik['totalpremi']).'" size="15" onkeypress="return isNumberKey(event)" disabled></td></tr>
			</td></tr>
			</table>
		<tr><td colspan="4" width="50%" class="judulhead1"><b>DATA PHOTO PESERTA</b></td></tr>';
		$met_photo = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_photo WHERE id_cost="'.$metmedik['id_cost'].'" AND id_peserta="'.$metmedik['id_peserta'].'" AND del IS NULL'));
		if (!$met_photo) {	
			$photonya1 = '<img src="../image/non-user.png" width="100">';
			$photonya2 = '<img src="../image/non-user.png" width="100">';
			$met_photo_baru = '<a href="ajk_photo.php?q=p_upload&idp='.$data['id'].'"><img src="image/save.png" width="30"></a>';
		}	else	{
			$photonya1 = '<a href="'.$metpath_file.''.$met_photo['photo_dekl_1'].'" rel="lightbox" ><img src="'.$metpath_file.'/'.$met_photo['photo_dekl_1'].'" width="200"></a>';
			$photonya2 = '<a href="'.$metpath_file.''.$met_photo['photo_dekl_2'].'" rel="lightbox" ><img src="'.$metpath_file.'/'.$met_photo['photo_dekl_2'].'" width="200"></a>';
		}
		echo '<td colspan="3" align="center">'.$photonya1.'</td><td align="center">'.$photonya2.'</td>';

		echo '<tr><td colspan="4" width="50%" class="judulhead1"><b>DATA PHOTO PESERTA TAB</b></td></tr>';
		$met_photo = mysql_fetch_array($database->doQuery('SELECT filefotodebitursatu,filefotoskpensiun
																												FROM fu_ajk_spak 
																												INNER JOIN fu_ajk_spak_form
																												ON fu_ajk_spak_form.idspk = fu_ajk_spak.id
																												WHERE spak = "'.$metmedik['spaj'].'"'));
		//if (!$met_photo) {	
			// $photonya1 = '<img src="../image/non-user.png" width="100">';
			// $photonya2 = '<img src="../image/non-user.png" width="100">';
			// $met_photo_baru = '<a href="ajk_photo.php?q=p_upload&idp='.$data['id'].'"><img src="image/save.png" width="30"></a>';
		//}		
		$photospk = '<img src="../../ajkmobilescript/'.$met_photo['filefotodebitursatu'].'" width="100">';
		$photosk = '<img src="../../ajkmobilescript/'.$met_photo['filefotoskpensiun'].'" width="100">';
		echo '<td colspan="3" align="center">'.$photospk.'</td><td align="center">'.$photosk.'</td>';	
		echo '</table>';
	;
	break;

case "editp":
	echo '<table border="0" cellpadding="3" cellspacing="1" width="100%"><tr><th width="100%" align="left">Modul Peserta - Edit</th><th><a href="ajk_uploader_fu.php"><img src="../image/Backward-64.png" width="20" border="0"></a></th></tr></table>';
	$met = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id="'.$_REQUEST['id'].'"'));
	if ($_REQUEST['ope']=="Simpan") {
		$updatepeserta = $database->doQuery('UPDATE fu_ajk_peserta set tgl_laporan = "'.$_REQUEST['tgl_laporan'].'" WHERE id = '.$_REQUEST['id']);
		$updatecms = $database->doQuery('UPDATE CMS_ArAp_Referensi set fArAp_AssDate = "'.$_REQUEST['tgl_laporan'].'" WHERE fArAp_RefMemberID = '.$met['id_peserta']);
		$updatecmsBaru = $database->doQuery('UPDATE CMS_ArAp_Transaction set fArAp_AssDate = "'.$_REQUEST['tgl_laporan'].'",fArAp_Status = "A" WHERE fArAp_RefMemberID = '.$met['id_peserta']);

		$berkas = fopen("historyedit.txt", "a") or die ("File history tidak ada.");
		$asli__ = "Update Tgl Lapor Asuransi dari ".$met['tgl_laporan']." menjadi ".$_REQUEST['tgl_laporan']." oleh ".$q['nm_lengkap'];
		fwrite($berkas, $asli__ . "\r\n");		
		fclose($berkas);
	echo '<center><h2> '.$_REQUEST['tgl_laporan'].' Data telah di edit oleh '.$q['nm_lengkap'].'.</h2></center><meta http-equiv="refresh" content="2;URL=ajk_uploader_fu.php">';		
	}
	echo '<form method="POST" action="">
					<table border="0" cellpadding="0" cellspacing="2" width="100%">
						<input type="hidden" name="id" value="'.$met['id'].'">
						<tr>
							<td width="10%">Tanggal Lapor Asuransi</td><td width="1%">:</td>
							<td>'; print initCalendar();	print calendarBox('tgl_laporan', 'triger', $met['tgl_laporan']);
							echo '</td>
						</tr>	
						<tr><td colspan="2"></td><td align="left"><input type="submit" name="ope" value="Simpan"></td></tr>
					</table>
				</form>';
	/*
	echo '<table border="0" cellpadding="3" cellspacing="1" width="100%"><tr><th width="100%" align="left">Modul Peserta - Edit</th><th><a href="ajk_uploader_fu.php"><img src="../image/Backward-64.png" width="20" border="0"></a></th></tr></table>';
	$met = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id="'.$_REQUEST['id'].'"'));	
	if ($_REQUEST['ope']=="Simpan") {
		$metEM = $_REQUEST['rpremi'] * $_REQUEST['rext_premi'] / 100;
		if ($met['type_data']=="SPK") {
			$metUpdate = $database->doQuery('UPDATE fu_ajk_spak SET ext_premi="'.$_REQUEST['rext_premi'].'" WHERE id_cost="'.$met['id_cost'].'" AND id_polis="'.$met['id_polis'].'" AND spak="'.$met['spaj'].'"');
		}
		$metnettpremi = $_REQUEST['rpremi'] + $_REQUEST['rbunga'] + $_REQUEST['rbiaya_adm'] + $metEM;
		$el = $database->doQuery('UPDATE fu_ajk_peserta SET spaj="'.$_REQUEST['rspaj'].'",
															nama="'.$_REQUEST['rnama'].'",
															gender="'.$_REQUEST['rgender'].'",
															tgl_lahir="'.$_REQUEST['rdob'].'",
															kredit_tgl="'.$_REQUEST['rkredit_tgl'].'",
															kredit_jumlah="'.$_REQUEST['rkredit_jumlah'].'",
															kredit_tenor="'.$_REQUEST['rkredit_tenor'].'",
															premi="'.$_REQUEST['rpremi'].'",
															bunga="'.$_REQUEST['rbunga'].'",
															biaya_adm="'.$_REQUEST['rbiaya_adm'].'",
															ext_premi="'.$metEM.'",
															totalpremi="'.$metnettpremi.'",
															badant="'.$_REQUEST['rbadant'].'",
															badanb="'.$_REQUEST['rbadanb'].'",
															regional="'.$_REQUEST['rreg'].'",
															area="'.$_REQUEST['rarea'].'",
															cabang="'.$_REQUEST['rcabang'].'",
															update_by="'.$q['nm_lengkap'].'",
															update_time="'.$futgl.'"
															WHERE id="'.$_REQUEST['id'].'"');
		$mametganteng = mysql_fetch_array($database->doQuery('SELECT id, id_cost, id_dn, SUM(totalpremi) AS jNettPremi FROM fu_ajk_peserta WHERE id_dn="'.$met['id_dn'].'" GROUP BY id_dn'));
		$mametkasep = $database->doQuery('UPDATE fu_ajk_dn SET totalpremi="'.$mametganteng['jNettPremi'].'" WHERE id="'.$mametganteng['id_dn'].'"');
		echo '<center><h2>Data telah di edit oleh '.$q['nm_lengkap'].'.</h2></center><meta http-equiv="refresh" content="2;URL=ajk_uploader_fu.php">';
	}
		$metmedik = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id="'.$_REQUEST['id'].'"'));
		$metcost = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$metmedik['id_cost'].'"'));
		$metpolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$metmedik['id_polis'].'"'));
		echo '<center><form method="POST" action="" class="input-list style-1 smart-green">
		<table border="0" cellpadding="0" cellspacing="2" width="100%">
		<input type="hidden" name="id" value="'.$_REQUEST['id'].'">
		<tr><td width="10%">Nama Perusahaan</td><td width="1%">:</td><td><b>'.$metcost['name'].'</b></td></tr>
		<tr><td>Nama Produk</td><td width="1%">:</td><td><b>'.$metpolis['nmproduk'].'</b></td></tr>
		</table>
		<table border="0" cellpadding="0" cellspacing="2" width="100%">
		<tr><td colspan="2" width="50%" class="judulhead1"><b>DATA PESERTA</b></td>
			<td colspan="2" width="50%" class="judulhead1"><b>DATA KREDIT</b></td>
		</tr>
		<tr><td colspan="2" valign="top">
			<table width="100%" cellpadding="3" celsspacing="1">
				<tr><td>SPAJ</td><td width="1%">:</td><td><input type="text" name="rspaj" value="'.$metmedik['spaj'].'" size="5"></td></tr>
				<tr><td width="20%">Nama</td><td width="1%">:</td><td><input type="text" name="rnama" value="'.$metmedik['nama'].'" size="50"></td></tr>
				<tr><td>Jenis Kelamin</td><td width="1%">:</td><td><input type=radio '.pilih($metmedik["gender"], "P").'  name="rgender" value="P">P &nbsp; <input type=radio '.pilih($metmedik["gender"], "W").'  name="rgender" value="W">W</td></tr>
				<tr><td>Tanggal Lahir</td><td width="1%">:</td><td><input type="text" name="rdob" id="tanggal2" class="tanggal" value="'.$metmedik['tgl_lahir'].'">';
	echo '</td></tr>
		  <tr><td>Tinggi Badan</td><td width="1%">:</td><td><input type="text" name="rbadant" value="'.$metmedik['badant'].'" size="1"></td></tr>
		  <tr><td>Berat Badan</td><td width="1%">:</td><td><input type="text" name="rbadanb" value="'.$metmedik['badanb'].'" size="1"></td></tr>
	<tr><td width="10%">Regional</td><td width="1%">:</td><td><select id="rreg" name="rreg">';
		$rreg=$database->doQuery('SELECT * FROM fu_ajk_regional WHERE id_cost="'.$met['id_cost'].'" AND del IS NULL ORDER BY name ASC');
	while($freg = mysql_fetch_array($rreg)) {
		$rreg2=mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE id_cost="'.$met['id_cost'].'" AND name="'.$met['regional'].'" AND del IS NULL'));
		echo  '<option value="'.$freg['name'].'" '._selected($freg['name'], $rreg2['name']).'>'.$freg['name'].'</option>';}
	echo '</select></td></tr>
				<tr><td>Area</td><td width="1%">:</td><td><select id="rarea" name="rarea">';
		$rcab=$database->doQuery('SELECT * FROM fu_ajk_area WHERE id_cost="'.$met['id_cost'].'" AND del IS NULL ORDER BY name ASC');
	while($fcab = mysql_fetch_array($rcab)) {
		$rcab2=mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_area WHERE id_cost="'.$met['id_cost'].'" AND name="'.$met['area'].'" AND del IS NULL'));
		echo  '<option value="'.$fcab['name'].'"'._selected($fcab["name"], $rcab2["name"]).'>'.$fcab['name'].'</option>';}
	echo '</select></td></tr>
				<tr><td>Cabang</td><td width="1%">:</td><td><select id="rcabang" name="rcabang">';
		$rarea=$database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id_cost="'.$met['id_cost'].'" AND del IS NULL GROUP BY name ORDER BY name ASC');
	while($farea = mysql_fetch_array($rarea)) {
		$rarea2=mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id_cost="'.$met['id_cost'].'" AND name="'.$met['cabang'].'" AND del IS NULL GROUP BY cabang'));
		echo  '<option value="'.$farea['name'].'"'._selected($farea["name"], $rarea2["name"]).'>'.$farea['name'].'</option>';}
	echo '</select></td></tr>
		  </table>
		</td>
			<td colspan="2" valign="top">
			<table width="100%" cellpadding="3" celsspacing="1">
			<tr><td width="30%">Tanggal Kredit</td><td width="1%">:</td><td><input type="text" name="rkredit_tgl" id="tanggal1" class="tanggal" value="'.$metmedik['kredit_tgl'].'"></td></tr>
			<tr><td>Kredit Jumlah</td><td width="1%">:</td><td><input type="text" name="rkredit_jumlah" value="'.$metmedik['kredit_jumlah'].'" onkeypress="return isNumberKey(event)"></td></tr>
			<tr><td>Tenor</td><td width="1%">:</td><td><input type="text" name="rkredit_tenor" value="'.$metmedik['kredit_tenor'].'" size="3" onkeypress="return isNumberKey(event)"></td></tr>
			<tr><td>Premi</td><td width="1%">:</td><td><input type="text" name="rpremi" value="'.$metmedik['premi'].'" size="10" onkeypress="return isNumberKey(event)"></td></tr>
			<tr><td>Bunga</td><td width="1%">:</td><td><input type="text" name="rbunga" value="'.$metmedik['bunga'].'" size="3" onkeypress="return isNumberKey(event)"></td></tr>
			<tr><td>Biaya Administrasi</td><td width="1%">:</td><td><input type="text" name="rbiaya_adm" value="'.$metmedik['biaya_adm'].'" size="3" onkeypress="return isNumberKey(event)"></td></tr>
			<tr><td>Extra Premi (%)</td><td width="1%">:</td><td><input type="text" name="rext_premi" value="'.$metmedik['ext_premi'].'" size="3" maxlength="3" onkeypress="return isNumberKey(event)"></td></tr>
			<tr><td>Total Premi</td><td width="1%">:</td><td><input type="text" name="rtotalpremi" value="'.$metmedik['totalpremi'].'" size="15" onkeypress="return isNumberKey(event)" disabled></td></tr>
			</td></tr>
				</table>
			<tr><td colspan="4" align="center"><input type="submit" name="ope" value="Simpan"></td></tr>
			</form>
		</table>';*/
		;
break;
	default:
		$cat=$_GET['cat']; // Use this line or below line if register_global is off
if(strlen($cat) > 0 and !is_numeric($cat)){ // to check if $cat is numeric data or not.
	echo "Data Error";
	exit;
}
$metcost = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name DESC');
echo '<fieldset style="padding: 2">
	<legend align="center">S e a r c h</legend>
	<table border="0" width="100%" cellpadding="1" cellspacing="1">
		<form method="post" action="">
<tr><td width="40%" align="right">Nama Perusahaan :</td><td><select name="id_cost" id="id_cost">
		  	<option value="">---Pilih Perusahaan---</option>';
		while($metcost_ = mysql_fetch_array($metcost)) {
			echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['id_cost'], $metcost_['id']).'>'.$metcost_['name'].'</option>';
		}
echo '</select></td></tr>
		<tr><td align="right">Group Produk :</td><td><select name="grupprod" id="grupprod">
			  	<option value="">---Pilih Group Produk---</option>
			  	<option value="BUKOPIN"'._selected($_REQUEST["grupprod"], "BUKOPIN").'>BUKOPIN</option>
			  	<option value="BPR"'._selected($_REQUEST["grupprod"], "BPR").'>BPR</option>
			  	<option value="KNS"'._selected($_REQUEST["grupprod"], "KNS").'>KNS</option>
			  	<option value="KOSSPI"'._selected($_REQUEST["grupprod"], "KOSSPI").'>KOSSPI</option>
			  	<option value="MEKARSARI"'._selected($_REQUEST["grupprod"], "MEKARSARI").'>MEKARSARI</option>
		</select></td></tr>
		<tr><td align="right">Nama Produk :</td>
			<td id="polis_rate"><select name="id_polis" id="id_polis">
			<option value="">-- Pilih Produk --</option>
			</select></td></tr>';
/*
	<tr><td align="right">Regional :</td>
		<td><select size="1" name="cregional">
  			<option value="">- - - Pilih Regional - - -</option>';
		$reg = $database->doQuery('SELECT * FROM fu_ajk_regional WHERE id_cost="'.$cat.'" AND del IS NULL ORDER BY name ASC');
while ($creg = mysql_fetch_array($reg)) {	echo '<option value="'.$creg['name'].'">'.$creg['name'].'</option>';	}
echo '</select></td></tr>
	<tr><td align="right">Cabang :</td>
		<td><select size="1" name="ccabang">
  			<option value="">- - - Pilih Cabang - - -</option>';
		$cab = $database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id_cost="'.$cat.'" AND del IS NULL GROUP BY name ORDER BY name ASC');
while ($ccab = mysql_fetch_array($cab)) {	echo '<option value="'.$ccab['name'].'">'.$ccab['name'].'</option>';	}
echo '</select></td></tr>
*/
echo '<tr><td align="right">Regional :</td>
	<td id="polis_rate"><select name="id_reg" id="id_reg">
	<option value="">-- Pilih Regional --</option>
	</select></td></tr>
	<tr><td align="right">Cabang :</td>
	<td id="polis_rate"><select name="id_cab" id="id_cab">
	<option value="">-- Pilih Cabang --</option>
	</select></td></tr>
	<tr><td align="right">Nomor DN :</td><td><input type="text" name="metdn" value="'.$_REQUEST['metdn'].'"></td></tr>
		<tr><td align="right">Tanggal Mulai Kredit :</td><td>';
		print initCalendar();	print calendarBox('tgl', 'triger', $_REQUEST['tgl']);	echo 's/d';
		print initCalendar();	print calendarBox('tgl2', 'triger2', $_REQUEST['tgl2']);
		echo '</td></tr>
		<tr><td align="right">Nama :</td><td><input type="text" name="snama" value="'.$_REQUEST['snama'].'"></td></tr>
		<tr><td align="right">Id Peserta :</td><td><input type="text" name="idpes" value="'.$_REQUEST['idpes'].'"></td></tr>
		<tr><td align="right">Tanggal Lahir :</td><td>';
		print initCalendar();	print calendarBox('sdob', 'triger3', $_REQUEST['sdob']);	echo '</td></tr>
		<tr><td align="center" colspan="2"><input type="submit" name="button" value="Cari" class="button"></td></tr>
		</form></table></fieldset>';

		if ($_REQUEST['tgl']!='' AND $_REQUEST['tgl2']!='')	{	$satu='AND kredit_tgl BETWEEN \''.$_REQUEST['tgl'].'\' AND \''.$_REQUEST['tgl2'].'\'';	}
		if ($_REQUEST['id_reg'])		{
			$cekReg = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_regional WHERE id="'.$_REQUEST['id_reg'].'"'));
			$dua = 'AND regional = "'.$cekReg['name'].'"';
		}
		if ($_REQUEST['id_cab'])		{
			$cekCab = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_cabang WHERE id="'.$_REQUEST['id_cab'].'"'));
			$delapan = 'AND cabang = "'.$cekCab['name'].'"';
		}
		if ($_REQUEST['snama'])			{	$tiga = 'AND nama LIKE "%' . $_REQUEST['snama'] . '%"';		}
		if ($_REQUEST['sdob'])			{	$empat = 'AND tgl_lahir LIKE "%' . $_REQUEST['sdob'] . '%"';		}
		if ($_REQUEST['id_cost'])		{	$lima = 'AND id_cost LIKE "%' . $_REQUEST['id_cost'] . '%"';		}
		if ($_REQUEST['id_polis'])		{	$enam = 'AND id_polis LIKE "%' . $_REQUEST['id_polis'] . '%"';		}
		if ($_REQUEST['idpes'])		{	$sembilan = 'AND id_peserta = "' . $_REQUEST['idpes'] . '"';		}
		if ($_REQUEST['metdn'])			{
			$metcekdn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn WHERE dn_kode LIKE "%' . $_REQUEST['metdn'] . '%"'));
			$tujuh = 'AND id_dn = "' . $metcekdn['id'] . '"';
		}

echo '<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="#E2E2E2">
	<tr><th width="1%" rowspan="2">Option</th>
		<th width="1%" rowspan="2">No</th>
		<th width="1%" rowspan="2">Asuransi</th>
		<th width="5%" rowspan="2">SPAJ</th>
		<th width="5%" rowspan="2">ID Peserta</th>
		<th width="5%" rowspan="2">ID Utama</th>
		<th width="5%" rowspan="2">Produk</th>
		<th width="5%" rowspan="2">DN</th>
		<th width="5%" rowspan="2">Tgl DN</th>
		<th width="5%" rowspan="2">Tgl Paid DN</th>
		<th rowspan="2">Nama Debitur<br /><font size="1"><i>(sesuai KTP/SIM)</i></font></th>
		<th width="1%" rowspan="2">P/W</th>
		<th rowspan="2">Tgl Lahir</th>
		<th rowspan="2">Usia</th>
		<th rowspan="2">GP</th>		
		<th colspan="4">Status Kredit</th>
		<th width="1%" rowspan="2">Rate Premi</th>
		<th width="1%" rowspan="2">Premi</th>
		<th rowspan="2">Ext. Premi</th>
		<th width="1%" rowspan="2">Total Premi</th>
		<th rowspan="2">Medical</th>
		<th colspan="3">Status</th>
		<th rowspan="2">Cabang</th>
		<th rowspan="2">Regional</th>
	</tr>
	<tr><th>Kredit Awal</th>
		<th>Tenor</th>
		<th>Kredit Akhir</th>
		<th>U P</th>
		<th>Pembayaran</th>
		<th>Status</th>
		<th>Data</th>
	</tr>';

define('_NEXT', '<img src="imags/right_arrow.gif" border="0">');
define('_PREV', '<img src="image/left_arrow.gif" border="0">');
if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 50;	}	else {	$m = 0;		}

if ($q['level']=="99") {
	$data = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id!= "" AND del is null and type_data="SPK" '.$satu.' '.$dua.' '.$delapan.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' '.$tujuh.' '.$sembilan.' ORDER BY input_time DESC, cabang ASC LIMIT ' . $m . ' , 50');
	$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_peserta WHERE id != "" AND type_data="SPK AND del is null " ' . $satu . ' '.$dua.' '.$delapan.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' '.$tujuh.' '.$sembilan.''));
}else{
	$data = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE del is null and nama!= "" '.$satu.' '.$dua.' '.$delapan.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' '.$tujuh.' '.$sembilan.' ORDER BY input_time DESC, cabang ASC LIMIT ' . $m . ' , 50');
	$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_peserta WHERE id != "" and del is null ' . $satu . ' '.$delapan.' '.$dua.' '.$delapan.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' '.$tujuh.' '.$sembilan.''));
}
$totalRows = $totalRows[0];
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while ($fudata = mysql_fetch_array($data)) {
$settgl = explode ("/", $fudata['kredit_tgl']);
$settingtgl = $settgl[2].'-'.$settgl[1].'-'.$settgl[0];


$metpolis = mysql_fetch_array($database->doQuery('SELECT id, nmproduk, nopol FROM fu_ajk_polis WHERE id="'.$fudata['id_polis'].'"'));
$met_dn = mysql_fetch_array($database->doQuery('SELECT id, id_cost, id_nopol, id_as, dn_kode, tgl_createdn FROM fu_ajk_dn WHERE id="'.$fudata['id_dn'].'"'));
$cekdataAS = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_asuransi WHERE id="'.$met_dn['id_as'].'"'));
$nmrdn = substr($met_dn['dn_kode'], 3); //PERSINGKAT NOMOR DN
if ($fudata['status_bayar']==0) {	$statusnya = '<font color="red">Unpaid</font>';	}else{	$statusnya = '<font color="blue">Paid</font>';	}
if ($fudata['status_aktif']=="aktif") {	$statusdatanya = '<font color="red">Inforce</font>';	}else{	$statusdatanya = '<font color="blue">'.$fudata['status_aktif'].'</font>';	}

if ($q['status']=="" OR $q['status']=="UNDERWRITING" OR $q['level']==99) {	$meteditpeserta='<a href="ajk_uploader_fu.php?r=editp&id='.$fudata['id'].'"><img src="../image/edit3.png" border="0"></a>';	}
else{	$meteditpeserta='<a href="#" title="Anda tidak berhak edit data."><img src="../image/edit3.png" border="0"></a>';	}

if ($fudata['memousia']==NULL) {
	$metmemo =$fudata['status_medik'];
}else{
	$metmemo = '<a title="Lihat data memo usia" href="'.$metpath_file.''.$fudata['memousia'].'" target="_blank">'.$fudata['status_medik'].'</a>';
}
$mettgldn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn WHERE dn_kode="'.$fudata['id_dn'].'"'));

if($fudata['id_polis']==19){
	$query = "SELECT id_peserta
						FROM fu_ajk_peserta 
						WHERE spaj = (SELECT spak 
													FROM fu_ajk_spak 
													WHERE id = (SELECT nolink
																			FROM fu_ajk_spak
																					 INNER JOIN fu_ajk_spak_form
																					 ON fu_ajk_spak_form.idspk = fu_ajk_spak.id
																			WHERE fu_ajk_spak.spak = '".$fudata['spaj']."'
																			LIMIT 1))";
	$qpasangan = mysql_fetch_array(mysql_query($query));
	$pasangan = $qpasangan['id_peserta'];
}else{
	$pasangan = ' - ';
}

if (($no % 2) == 1)	$objlass = 'tbl-odd'; else				$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		  <td align="center">'.$meteditpeserta.'</td>
		  <td align="center">'.(++$no + ($pageNow-1) * 50).'</td>
		  <td>'.$cekdataAS['name'].'</td>
		  <td>'.$fudata['spaj'].'</td>
		  <td align="center">'.$fudata['id_peserta'].'</td>
		  <td align="center">'.$pasangan.'</td>
		  <td align="center">'.$metpolis['nmproduk'].'</td>
		  <td align="center">'.$nmrdn.'</td>
		  <td align="center">'.$met_dn['tgl_createdn'].'</td>
		  <td align="center">'._convertDate($mettgldn['tgl_dn_paid']).'</td>
		  <td><a href="ajk_uploader_fu.php?r=vdeb&vid='.$fudata['id'].'" title="view data peserta">'.$fudata['nama'].'</a></td>
		  <td align="center">'.$fudata['gender'].'</td>
		  <td align="center">'.$fudata['tgl_lahir'].'</td>
		  <td align="center">'.$fudata['usia'].'</td>
		  <td align="center">'.$fudata['mppbln'].'</td>
		  <td align="center">'._convertDate($fudata['kredit_tgl']).'</td>
		  <td align="center">'.$fudata['kredit_tenor'].'</td>
		  <td align="center">'._convertDate($fudata['kredit_akhir']).'</td>
		  <td align="right">'.duit($fudata['kredit_jumlah']).'</td>
		  <td align="right">'.$fudata['ratebank'].'</td>
		  <td align="right">'.duit($fudata['premi']).'</td>
		  <td align="right">'.duit($fudata['ext_premi']).'</td>
		  <td align="right">'.duit($fudata['totalpremi']).'</td>
		  <td align="center">'.$metmemo.'</td>
		  <td align="center">'.$statusnya.'</td>
		  <td align="center">'.$statusdatanya.'</td>
		  <td align="center">'.$fudata['status_peserta'].'</td>
		  <td align="center">'.$fudata['cabang'].'</td>
		  <td align="center">'.$fudata['regional'].'</td>
		  </tr>';
}
		echo '<tr><td colspan="22">';
		echo createPageNavigations($file = 'ajk_uploader_fu.php?id_cost='.$_REQUEST['id_cost'].'&id_polis='.$_REQUEST['id_polis'].'&id_reg='.$_REQUEST['id_reg'].'&id_cab='.$_REQUEST['id_cab'].'&snama='.$_REQUEST['snama'].'&tgl='.$_REQUEST['tgl'].'&tgl2='.$_REQUEST['tgl2'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 50);
		echo '<b>Total Data Peserta: <u>' . $totalRows . '</u></b></td></tr>';
		echo '</table>';
		;
} // switch
echo"<script type='text/javascript' src='js/jquery/jquery.min-1.11.1.js'></script>
<script type='text/javascript'>//<![CDATA[
 	$(window).load(function(){
 		$(document).ready(function () {
 			(function ($) {
 				$('#cari').keyup(function () {
 					var rex = new RegExp($(this).val(), 'i');
 					$('.caritable tr').hide();
 					$('.caritable tr').filter(function () {
 						return rex.test($(this).text());
 					}).show();

 				})

 			}(jQuery));

			$('#id_cost').change(function(){
			var idcost = document.getElementById('id_cost').value;
				$.ajax({
					type:'post',
					url:'javascript/metcombo/data_peserta.php',
					data:{functionname: 'setpoliscostumer',cost:idcost},
					cache:false,
					success:function(returndata) {
						$('#id_polis').html(returndata);
					}
				});

				$.ajax({
					type:'post',
					url:'javascript/metcombo/data_peserta.php',
					data:{functionname: 'setpoliscostumerregional',cost:idcost},
					cache:false,
					success:function(returndata) {
						$('#id_reg').html(returndata);
					}
				});

			});
			$('#id_reg').change(function(){
			var noreg = document.getElementById('id_reg').value;
			var idcost = document.getElementById('id_cost').value;
				$.ajax({
					type:'post',
					url:'javascript/metcombo/data_peserta.php',
					data:{functionname: 'setpoliscostumercabang',cost:idcost,regi:noreg},
					cache:false,
					success:function(returndata) {
						$('#id_cab').html(returndata);
					}
				});

			});

 		});
 			var idcost = document.getElementById('id_cost').value;
			$.ajax({
				type:'post',
				url:'javascript/metcombo/data_peserta.php',
				data:{functionname: 'setpoliscostumer',cost:idcost},
				cache:false,
				success:function(returndata) {
					$('#id_polis').html(returndata);
				}
			});

			$.ajax({
				type:'post',
				url:'javascript/metcombo/data_peserta.php',
				data:{functionname: 'setpoliscostumerregional',cost:idcost},
				cache:false,
				success:function(returndata) {
					$('#id_reg').html(returndata);
				}
			});

			$.ajax({
				type:'post',
				url:'javascript/metcombo/data_peserta.php',
				data:{functionname: 'setpoliscostumercabang',cost:idcost,regi:noreg},
				cache:false,
				success:function(returndata) {
					$('#id_cab').html(returndata);
				}
			});
 	});


</script>";
?>
<script type="text/javascript" language="javascript">
function checkfile(sender) {
	var validExts = new Array(".xlsx", ".xls", ".csv");
	var fileExt = sender.value;
	fileExt = fileExt.substring(fileExt.lastIndexOf('.'));
	if (validExts.indexOf(fileExt) < 0) {
	alert("Invalid file selected, valid files are of " +
	validExts.toString() + " types.");
	return false;
	}
	else return true;
}
</script>
<SCRIPT language=JavaScript>
function reload(form)
{
	var val=form.cat.options[form.cat.options.selectedIndex].value;
	self.location='ajk_uploader_fu.php?cat=' + val;
}
</script>
