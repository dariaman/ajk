<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("../includes/functions.php");
connect();

if (isset($_SESSION['nm_user'])) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));	}
$accmedical = mysql_fetch_array($database->doQuery('SELECT id_user, option_medical FROM fu_ajk_optionmenus WHERE id_user="'.$q['id'].'"'));
$futgl = date("Y-m-d g:i:a");
switch ($_REQUEST['r']) {
	case "approve":
$met = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id ="'.$_REQUEST['id'].'"'));
$metupd = $database->doQuery('UPDATE fu_ajk_peserta SET status_aktif="Approve", status_medik="SPD", update_by="'.$q['nm_lengkap'].'", update_time="'.$futgl.'" WHERE id ="'.$_REQUEST['id'].'"');
echo '<center>Data peserta atas nama '.$met['nama'].' telah di update dan status peserta menjadi AKTIF, klik link di bawah ini untuk membuat DN.<br /><a href="ajk_dn.php">Create DN</a></center>';
		;
		break;
	case "fedit":
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
		<tr><th width="100%" align="left">Modul cek medik - Edit Data</font></th><th width="5%"><a href="ajk_cekmedik_fu.php">Back</a></th></tr>
		</table>';
if ($_REQUEST['ope']=="Simpan") {
if ($_REQUEST['rstatus'] != "Medical A") {
$met_statusnya = "Approve";
}else{
$met_statusnya = "Pending";
}
$empremi = $_REQUEST['rpremi'] * $_REQUEST['rextpremi'] / 100;
$tPremi = $_REQUEST['rpremi'] + $_REQUEST['radm'] + $_REQUEST['rrefund'] + $empremi;
$el = $database->doQuery('UPDATE fu_ajk_peserta SET spaj="'.$_REQUEST['rspaj'].'",
													nama="'.$_REQUEST['rnama'].'",
													usia="'.$_REQUEST['rumur'].'",
													gender="'.$_REQUEST['rgender'].'",
													tgl_lahir="'.$_REQUEST['rdob'].'",
													kredit_jumlah="'.$_REQUEST['rkjumlah'].'",
													kredit_tenor="'.$_REQUEST['rktenor'].'",
													premi="'.$_REQUEST['rpremi'].'",
													disc_premi="'.$_REQUEST['discpremi'].'",
													biaya_adm="'.$_REQUEST['radm'].'",
													ext_premi="'.$empremi.'",
													totalpremi="'.$tPremi.'",
													status_medik="'.$_REQUEST['rstatus'].'",
													status_aktif="'.$met_statusnya.'",
													update_by="'.$q['nm_lengkap'].'",
													update_time="'.$futgl.'"
													WHERE id="'.$_REQUEST['id'].'"');
echo '<br />';
$uwmail = $database->doQuery('SELECT email, status FROM pengguna WHERE status=10');
while ($uwmailmet = mysql_fetch_array($uwmail)) {
$alluwmail .=$uwmailmet['email'].'; ';
}
$updmedical = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id="'.$_REQUEST['id'].'"'));
$updmedical_client = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$updmedical['id_cost'].'"'));
//echo '<br />'.$alluwmail;
/*
	$to = $alluwmail;
	//$subject = 'Data DN telah di buat oleh '.$q['nm_lengkap'].'';		DISABLED (071013)
	$subject = 'APPROVE DATA MEDICAL';
	$message = '<html><head><title>DN CREATE</title></head><body>
					<table border="0" width="50%" cellpadding="1" cellspacing="3">
					<tr><td colspan="2">To Underwriting</td></tr>
					<tr><td colspan="2"><br />Telah di update status Medikal menjadi Aktif oleh : <b>'.$q['nm_lengkap'].' </b> pada tanggal <b>'.$futgl.'</b></td></tr>
					<tr><td width="20%">Nama Perusahaan</td><td>: '.$updmedical_client['name'].' </td></tr>
					<tr><td>Nama peserta</td><td>: '.$_REQUEST['rnama'].' </td></tr>
					<tr><td>DOB</td><td>: '.$_REQUEST['rdob'].' </td></tr>
					<tr><td>U P</td><td>: '.$_REQUEST['rkjumlah'].' </td></tr>
					<tr><td>Status Data</td><td>: '.$updmedical['status_peserta'].' </td></tr>
					<tr><td>Regional</td><td>: '.$updmedical['regional'].' </td></tr>
					<tr><td>Cabang</td><td>: '.$updmedical['cabang'].' </td></tr>
				</table>
				</body>
				</html>';
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= 'From: '.$q['email'].'' . "\r\n";
	$headers .= 'Cc:  relife-ajk@relife.co.id' . "\r\n";
	mail($to, $subject, $message, $headers);
*/

	$message = '<html><head><title>DN CREATE</title></head><body>
					<table border="0" width="50%" cellpadding="1" cellspacing="3">
					<tr><td colspan="2">To Underwriting</td></tr>
					<tr><td colspan="2"><br />Telah di update status Medikal menjadi Aktif oleh : <b>'.$q['nm_lengkap'].' </b> pada tanggal <b>'.$futgl.'</b></td></tr>
					<tr><td width="20%">Nama Perusahaan</td><td>: '.$updmedical_client['name'].' </td></tr>
					<tr><td>Nama peserta</td><td>: '.$_REQUEST['rnama'].' </td></tr>
					<tr><td>DOB</td><td>: '.$_REQUEST['rdob'].' </td></tr>
					<tr><td>U P</td><td>: '.$_REQUEST['rkjumlah'].' </td></tr>
					<tr><td>Status Data</td><td>: '.$updmedical['status_peserta'].' </td></tr>
					<tr><td>Regional</td><td>: '.$updmedical['regional'].' </td></tr>
					<tr><td>Cabang</td><td>: '.$updmedical['cabang'].' </td></tr>
				</table>
				</body>
				</html>';
	$mail = new PHPMailer; // call the class
	$mail->IsSMTP();
	$mail->Host = SMTP_HOST; //Hostname of the mail server
	$mail->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
	//$mail->Username = SMTP_UNAME; //Username for SMTP authentication any valid email created in your domain
	//$mail->Password = SMTP_PWORD; //Password for SMTP authentication
	//$mail->AddReplyTo = SMTP_REPLYMAIL; //reply-to address
	$mail->SetFrom ($q['email'], $q['nm_lengkap']); //From address of the mail
	$mail->Subject = "AJKOnline - PESERTA MEDICAL ADONAI AJK ONLINE"; //Subject od your mail
	//EMAIL PENERIMA  KANTOR U/W
	$mailsupervisorajk = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="" AND status="10"');
	while ($_mailsupervisorajk = mysql_fetch_array($mailsupervisorajk)) {
	$mail->AddAddress($_mailsupervisorajk['email'], $_mailsupervisorajk['nm_lengkap']); //To address who will receive this email
	}
	//EMAIL PENERIMA  KANTOR U/W
	$mail->AddCC("id@adonai.co.id");
	//$mail->AddAddress($mailsupervisorajk['email'], $mailsupervisorajk['nm_lengkap']); //To address who will receive this email
	//$mail->AddCC($approvemail);
	$mail->MsgHTML('<table><tr><th>Data peserta medical telah disetujui oleh <b>'.$_SESSION['nm_user'].' selaku staff Adonai AJK-Online pada tanggal '.$futgl.'</tr></table>'.$message); //Put your body of the message you can place html code here
	$send = $mail->Send(); //Send the mails
echo '<center>Data telah di edit.</center><meta http-equiv="refresh" content="2;URL=ajk_cekmedik_fu.php">';
}

$metmedik = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id="'.$_REQUEST['id'].'"'));
$metcost = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$metmedik['id_cost'].'"'));
$metpolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$metmedik['id_polis'].'"'));

/*DISABLED UNTUK EDIT KESELURUHAN U/W
//CEK UMUR
	$findmet="/";
	$fpos = stripos($metmedik['tgl_lahir'], $findmet);
if ($fpos === false) {
	$riweuhkredit = explode("-", $metmedik['kredit_tgl']);	$cektglkredit = $riweuhkredit[0].'-'.$riweuhkredit[1].'-'.$riweuhkredit[2];		//KREDIT AKHIR
	$endkredit2=date('d/m/Y',strtotime($cektglkredit."+".$metmedik['kredit_tenor']." Month"));												//KREDIT AKHIR

	$riweuh = explode("-", $metmedik['tgl_lahir']);			$cektglnya = $riweuh[0].'-'.$riweuh[1].'-'.$riweuh[2];							// FORMULA USIA
	$riweuh2 = explode("-", $metmedik['kredit_tgl']);		$cektglnya2 = $riweuh2[0].'-'.$riweuh2[1].'-'.$riweuh2[2];							// FORMULA USIA
	$umur = ceil(((strtotime($cektglnya2) - strtotime($cektglnya)) / (60*60*24*365.2425)));												// FORMULA USIA
}	else	{
	$riweuhkredit = explode("/", $metmedik['kredit_tgl']);	$cektglkredit = $riweuhkredit[0].'-'.$riweuhkredit[1].'-'.$riweuhkredit[2];		//KREDIT AKHIR
	$endkredit2=date('d/m/Y',strtotime($cektglkredit."+".$metmedik['kredit_tenor']." Month"));												//KREDIT AKHIR

	$riweuh = explode("/", $metmedik['tgl_lahir']);			$cektglnya = $riweuh[0].'-'.$riweuh[1].'-'.$riweuh[2];							// FORMULA USIA
	$riweuh2 = explode("/", $metmedik['kredit_tgl']);		$cektglnya2 = $riweuh2[0].'-'.$riweuh2[1].'-'.$riweuh2[2];							// FORMULA USIA
	$umur = ceil(((strtotime($cektglnya2) - strtotime($cektglnya)) / (60*60*24*365.2425)));												// FORMULA USIA
}
//CEK UMUR
*/

echo '<form method="POST" action="">
	  <table border="0" cellpadding="3" cellspacing="0" width="100%">
	  <input type="hidden" name="id" value="'.$_REQUEST['id'].'">
	  <tr><td width="10%">Costumer</td><td>: <b>'.$metcost['name'].'</b></td></tr>
	  <tr><td>No. Polis</td><td>: <b>'.$metpolis['nopol'].'</b></td></tr>
	  <tr><td>SPAJ</td><td>: <input type="text" name="rspaj" value="'.$metmedik['spaj'].'"></td></tr>
	  <tr><td>Nama</td><td>: <input type="text" name="rnama" value="'.$metmedik['nama'].'"></td></tr>
	  <tr><td>Usia</td><td>: <input type="text" name="rumur" value="'.$metmedik['usia'].'" size="1"></td></tr>
	  <tr><td>Jenis Kelamin</td><td>: <input type=radio '.pilih($metmedik["gender"], "P").'  name="rgender" value="P">P &nbsp; <input type=radio '.pilih($metmedik["gender"], "W").'  name="rgender" value="W">W</td></tr>
	  <tr><td>Tanggal Lahir</td><td>: ';
		echo initCalendar();
		echo calendarBox('rdob', 'triger1', $metmedik['tgl_lahir']);
$metmedical = $database->doQuery('SELECT * FROM fu_ajk_medical WHERE id_cost="'.$metmedik['id_cost'].'" GROUP BY type_medical ASC');
		echo '</td></tr>
	  <tr><td>Status Data</td><td>: <select size="1" name="rstatus">
	  	<option value="">Select Status</option>';
while ($metmedical_ = mysql_fetch_array($metmedical)) {
echo '<option value="'.$metmedical_['type_medical'].'"'._selected($metmedik["status_medik"], $metmedical_['type_medical']).'>'.$metmedical_['type_medical'].'</option>';
	}
	echo '</select>
	  </td></tr>
	  <tr><td>Kredit Jumlah</td><td>: <input type="text" name="rkjumlah" value="'.$metmedik['kredit_jumlah'].'"></td></tr>
	  <tr><td>Tenor</td><td>: <input type="text" name="rktenor" value="'.$metmedik['kredit_tenor'].'" size="3"></td></tr>
	  <tr><td>Premi</td><td>: <input type="text" name="rpremi" value="'.$metmedik['premi'].'"></td></tr>
	  <tr><td>Disc Premi</td><td>: <input type="text" name="discpremi" value="'.$metmedik['disc_premi'].'"onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"/></td></tr>
	  <tr><td>Biaya Administrasi</td><td>: <input type="text" name="radm" value="'.$metmedik['biaya_adm'].'"onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"/></td></tr>
	  <tr><td>Extra Premi %</td><td>: <input type="text" name="rextpremi" value="'.$metmedik['ext_premi'].'" maxlength="3" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"/ size="3">%</td></tr>
	  <tr><td>Total Premi</td><td>: <b>'.duit($metmedik['totalpremi']).'</b></td></tr>
	  <tr><td colspan="3" align="center"><input type="submit" name="ope" value="Simpan"></td></tr>
	  </form>
	  </table>';
		;
		break;
	case "dmedic":
echo $_REQUEST['id'];
		;
		break;
	case "deldata":
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
		<tr><th width="100%" align="left">Modul Medical Data Pending</font></th><th width="5%"><a href="ajk_cekmedik_fu.php">Back</a></th></tr>
		</table>';
$metmedik = mysql_fetch_array($database->doQuery('SELECT *, IF(type_data="SPK", kredit_tenor * 12, kredit_tenor) AS tenor FROM fu_ajk_peserta WHERE id="'.$_REQUEST['id'].'"'));
$metcost = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$metmedik['id_cost'].'"'));
$metpolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$metmedik['id_polis'].'"'));

echo '<form method="POST" action="">
	  <table border="0" cellpadding="3" cellspacing="0" width="100%">
	  <input type="hidden" name="id" value="'.$_REQUEST['id'].'">
	  <tr><td width="10%">Costumer</td><td>: <b>'.$metcost['name'].'</b></td></tr>
	  <tr><td>No. Polis</td><td>: <b>'.$metpolis['nopol'].'</b></td></tr>
	  <tr><td>SPAJ</td><td>: '.$metmedik['spaj'].'</td></tr>
	  <tr><td>Nama</td><td>: '.$metmedik['nama'].'</td></tr>
	  <tr><td>Usia</td><td>: '.$metmedik['usia'].' tahun</td></tr>
	  <tr><td>Tanggal Lahir</td><td>:  '._convertDate($metmedik['tgl_lahir']).'</td></tr>
	  <tr><td>Status Data</td><td>: '.$metmedik["status_medik"].'</td></tr>
	  <tr><td>Plafond</td><td>: '.duit($metmedik['kredit_jumlah']).'</td></tr>
	  <tr><td>Tenor</td><td>: '.$metmedik['tenor'].' bulan</td></tr>
	  <tr><td>Total Premi</td><td>: <b>'.duit($metmedik['totalpremi']).'</b></td></tr>
	  <tr><td valign="top">Alasan data ditolak</td><td> <textarea name="kettolak" rows="5" cols="50">'.$_REQUEST['kettolak'].'</textarea></td></tr>
	  <tr><td colspan="3"><input type="submit" name="oppe" value="Simpan"></td></tr>
	  </form>
	  </table>';
if ($_REQUEST['oppe']=="Simpan") {
$el = $database->doQuery('UPDATE fu_ajk_peserta SET status_aktif="Reject", ketreject="'.$_REQUEST['kettolak'].'", update_by="'.$q['nm_lengkap'].'", update_time="'.$futgl.'" WHERE id="'.$_REQUEST['id'].'"');
header('Location: ajk_cekmedik_fu.php');
}
	;
	break;

	default:
$cat=$_GET['cat']; // Use this line or below line if register_global is off
if(strlen($cat) > 0 and !is_numeric($cat)){ // to check if $cat is numeric data or not.
	echo "Data Error";	exit;
}
echo '<fieldset style="padding: 2">
	<legend align="center">S e a r c h</legend>
	<table border="0" width="100%" cellpadding="1" cellspacing="1">
		<form method="post" action="">
<tr><td width="20%" align="right">Nama Perusahaan :</td>
	  <td width="30%"> <select id="cat" name="cat" onchange="reload(this.form)">
	  	<option value="">- - - Pilih Perusahaan- - - </option>';
		$quer2=$database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name ASC');
while($noticia2 = mysql_fetch_array($quer2)) {
if($noticia2['id']==$cat){echo '<option selected value="'.$noticia2['id'].'">'.$noticia2['name'].'</option><BR>';}
else{echo  '<option value="'.$noticia2['id'].'">'.$noticia2['name'].'</option>';}
}
echo '</select></td></tr>
	<tr><td align="right">Nama Produk :</td>
		<td>';
if(isset($cat) and strlen($cat) > 0){
	$quer=$database->doQuery('SELECT * FROM fu_ajk_polis where id_cost="'.$cat.'" ORDER BY id ASC');
}else{$quer=$database->doQuery("SELECT * FROM fu_ajk_polis ORDER BY id ASC"); }
		echo '<select name="subcat"><option value="">- - - Pilih Produk- - - </option>';
while($noticia = mysql_fetch_array($quer)) {
	echo  '<option value='.$noticia['id'].'>'.$noticia['nmproduk'].'</option>';
}
echo '</select></td></tr>
	<tr><td align="right">Regional :</td>
		<td><select size="1" name="cregional">
  			<option value="">- - - Pilih Regional - - -</option>';
		$reg = $database->doQuery('SELECT * FROM fu_ajk_regional WHERE id_cost="'.$cat.'" ORDER BY name ASC');
while ($creg = mysql_fetch_array($reg)) {	echo '<option value="'.$creg['name'].'">'.$creg['name'].'</option>';	}
echo '</select></td></tr>
	<tr><td align="right">Cabang :</td>
		<td><select size="1" name="ccabang">
  			<option value="">- - - Pilih Cabang - - -</option>';
		$cab = $database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id_cost="'.$cat.'" ORDER BY name ASC');
while ($ccab = mysql_fetch_array($cab)) {	echo '<option value="'.$ccab['name'].'">'.$ccab['name'].'</option>';	}
echo '</select></td></tr>
		<tr><td align="right">Nomor DN :</td><td><input type="text" name="metdn" value="'.$_REQUEST['metdn'].'"></td></tr>
		<tr><td align="right">Tanggal Mulai Kredit :</td><td>';
		print initCalendar();	print calendarBox('tgl', 'triger', $_REQUEST['tgl']);	echo 's/d';
		print initCalendar();	print calendarBox('tgl2', 'triger2', $_REQUEST['tgl2']);
		echo '</td></tr>
		<tr><td align="right">Nama :</td><td><input type="text" name="snama" value="'.$_REQUEST['snama'].'"></td></tr>
		<tr><td align="right">Tanggal Lahir :</td><td>';
		print initCalendar();	print calendarBox('sdob', 'triger3', $_REQUEST['sdob']);	echo '</td></tr>
		<tr><td align="center" colspan="2"><input type="submit" name="button" value="Cari" class="button"></td></tr>
		</form></table></fieldset>';

		if ($_REQUEST['tgl']!='' AND $_REQUEST['tgl2']!='')	{	$satu='AND kredit_tgl BETWEEN \''.$_REQUEST['tgl'].'\' AND \''.$_REQUEST['tgl2'].'\'';	}
		if ($_REQUEST['cregional'])		{	$dua = 'AND regional LIKE "%' . $_REQUEST['cregional'] . '%"';		}
		if ($_REQUEST['ccabang'])		{	$delapan = 'AND cabang LIKE "%' . $_REQUEST['ccabang'] . '%"';		}
		if ($_REQUEST['snama'])			{	$tiga = 'AND nama LIKE "%' . $_REQUEST['snama'] . '%"';		}
		if ($_REQUEST['sdob'])			{	$empat = 'AND tgl_lahir LIKE "%' . $_REQUEST['sdob'] . '%"';		}
		if ($_REQUEST['cat'])			{	$lima = 'AND id_cost LIKE "%' . $_REQUEST['cat'] . '%"';		}
		if ($_REQUEST['subcat'])		{	$enam = 'AND id_polis LIKE "%' . $_REQUEST['subcat'] . '%"';		}
		if ($_REQUEST['metdn'])			{
			$metcekdn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn WHERE dn_kode LIKE "%' . $_REQUEST['metdn'] . '%"'));
			$tujuh = 'AND id_dn = "' . $metcekdn['id'] . '"';
		}

define('_NEXT', '<img src="imags/right_arrow.gif" border="0">');
define('_PREV', '<img src="image/left_arrow.gif" border="0">');
if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}

echo '<form method="post" action="">
	  <table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="#E2E2E2">
	<tr><th width="5%" rowspan="2">Opt</th>
		<th width="1%" rowspan="2">No</th>
		<th width="15%" rowspan="2">Client</th>
		<th width="5%" rowspan="2">Produk</th>
		<th width="5%" rowspan="2">ID Peserta</th>
		<th rowspan="2">Nama Debitur<br /><font size="1"><i>(sesuai KTP/SIM)</i></font></th>
		<th rowspan="2">Tgl Lahir</th>
		<th rowspan="2">Usia</th>
		<th colspan="3">Status Kredit</th>
		<th width="1%" rowspan="2">Premi</th>
		<th rowspan="2">Ext. Premi</th>
		<th width="1%" rowspan="2">Total Premi</th>
		<th width="1%" rowspan="2">Status</th>
		<th rowspan="2">Cabang</th>
		<th rowspan="2">Input Date</th>
	</tr>
	<tr><th>Tgl Kredit</th>
		<th>Jumlah</th>
		<th>Tenor</th>

	</tr>';
$data = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_dn="" AND id_cost!="" AND del is null AND status_aktif="pending" '.$satu.' '.$dua.' '.$delapan.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' '.$tujuh.' ORDER BY id_cost ASC, input_time DESC, cabang ASC LIMIT ' . $m . ' , 25');
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_peserta WHERE id_dn="" AND id_cost!="" AND del is null AND status_aktif="pending" '.$satu.' '.$dua.' '.$delapan.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' '.$tujuh.''));
$totalRows = $totalRows[0];
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;

while ($fudata = mysql_fetch_array($data)) {
$fmedic = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_medical_form WHERE idp="'.$fudata['id'].'" AND file_type="form_pmedic"'));
$medicalclient = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$fudata['id_cost'].'"'));
$medicalpolis = mysql_fetch_array($database->doQuery('SELECT id, nmproduk, nopol FROM fu_ajk_polis WHERE id="'.$fudata['id_polis'].'"'));

if ($q['status']=="UNDERWRITING" OR $q['status']=="" AND $q['level']=="" ) {
$accusermedical = '<a href="ajk_cekmedik_fu.php?r=fedit&id='.$fudata['id'].'" title="edit data"><img src="../image/edit3.png" width="15"></a>&nbsp;
				   <a href="ajk_cekmedik_fu.php?r=approve&id='.$fudata['id'].'" title="approve" onClick="if(confirm(\'Anda yakin akan merubah status peserta atas nama '.$fudata['nama'].' menjadi AKTIF ?\')){return true;}{return false;}"><img src="image/save.png" width="15"></a>&nbsp;
				   <a href="ajk_cekmedik_fu.php?r=deldata&id='.$fudata['id'].'" title="tolak" onClick="if(confirm(\'Anda yakin akan menghapus data peserta atas nama '.$fudata['nama'].' ?\')){return true;}{return false;}"><img src="image/deleted.png" width="15"></a>';
}
//CEK FILE SKKT
if ($fudata['medicalfile']!= NULL) {
	$_metSKKT = '<a title="lihat file medical" href="'.$metpath_file.'/'.$fudata['medicalfile'].'" target="_blank">'.$fudata['status_medik'].'</a>';
}else{
	$_metSKKT = '<a title="file medical tidak ada">'.$fudata['status_medik'].'</a>';
}
//CEK FILE SKKT
if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		  <td align="center">'.$accusermedical.'</td>
		  <td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
		  <td>'.$medicalclient['name'].'</td>
		  <td align="center">'.$medicalpolis['nmproduk'].'</td>
		  <td align="center">'.$fudata['id_peserta'].'</td>
		  <!--<td><a href="ajk_cekmedik_fu.php?r=vmedik&id='.$fudata['id'].'">'.$fudata['nama'].'</a></td>-->
		  <td>'.$fudata['nama'].'</td>
		  <td align="center">'._convertDate($fudata['tgl_lahir']).'</td>
		  <td align="center">'.$fudata['usia'].'</td>
		  <td align="right">'._convertDate($fudata['kredit_tgl']).'</td>
		  <td align="right">'.duit($fudata['kredit_jumlah']).'</td>
		  <td align="center">'.$fudata['kredit_tenor'].'</td>
		  <td align="right">'.duit($fudata['premi']).'</td>
		  <td align="right">'.duit($fudata['ext_premi']).'</td>
		  <td align="right">'.duit($fudata['totalpremi']).'</td>
		  <td align="center">'.$_metSKKT.'</td>
		  <td align="center">'.$fudata['cabang'].'</td>
		  <td align="center">'.$fudata['input_time'].'</td>
		  </tr>';
}
echo '<tr><td colspan="23">';
echo createPageNavigations($file = 'ajk_cekmedik_fu.php?rdns='.$_REQUEST['rdns'].'&rdne='.$_REQUEST['rdne'].'&rpays='.$_REQUEST['rpays'].'&rpaye='.$_REQUEST['rpaye'].'&cat='.$_REQUEST['cat'].'&subcat='.$_REQUEST['subcat'].'&rdnno='.$_REQUEST['rdnno'].'&rstat='.$_REQUEST['rstat'].'&', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
echo '<b>Total Data Medical: <u>' . $totalRows . '</u></b></td></tr>';
echo '</table>';
		;
} // switch
?>
<SCRIPT language=JavaScript>
function reload(form)
{
	var val=form.cat.options[form.cat.options.selectedIndex].value;
	self.location='ajk_cekmedik_fu.php?cat=' + val;
}
</script>
