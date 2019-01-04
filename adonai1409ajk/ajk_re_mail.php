<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("../includes/functions.php");
connect();
if (isset($_SESSION['nm_user'])) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));}
if ($q['status'] !="" AND $q['supervisor']=="1") {	$metnewuser = '';	}else{	$metnewuser ='<th><a href="ajk_re_mail.php?m=tambah"><img border="0" src="../image/new.png" width="25"></a></th>';	}
switch ($_REQUEST['m']) {
	case "a":

		;
		break;

	case "mailactive":
$metActive = $database->doQuery('UPDATE fu_ajk_mail SET status="Aktif", update_by="'.$q['id'].'", update_date="'.$futgl.'" WHERE id="'.$_REQUEST['idm'].'"');
header("location:ajk_re_mail.php");
		;
		break;

	case "maildel":
$metDel = $database->doQuery('UPDATE fu_ajk_mail SET status="Tidak Aktif", update_by="'.$q['id'].'", update_date="'.$futgl.'" WHERE id="'.$_REQUEST['idm'].'"');
header("location:ajk_re_mail.php");
		;
		break;

	case "tambah":
if ($_REQUEST['ope']=="Simpan") {
	if (!$_REQUEST['typenotif'])  $error1 .='<blink><font color=red>Silahkan pilih type notifikasi</font></blink><br>';
	if (!$_REQUEST['emailto'])  $error2 .='<blink><font color=red>Silahkan tentukan alamat email</font></blink><br>';
	if (!preg_match ('/^\S+@[\w\d.-]{2,}\.[\w]{2,6}$/iU', $_REQUEST['emailto'])) $error4 .='<blink><font color=red>Alamat email tidak valid</font></blink><br>';
	if ($_REQUEST['typenotif'] !="" AND $_REQUEST['emailto'] !="") {
		$cekmail =mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_mail WHERE type="'.$_REQUEST['typenotif'].'" AND  emailto="'.$_REQUEST['emailto'].'" AND status="Aktif"'));
		if ($cekmail['id'])	$error3 .='<blink><font color=red>Alamat email pada notifikasi '.$_REQUEST['typenotif'].' sudah digunakan.</font></blink><br>';
	}
	if ($error1 OR $error2 OR $error3 OR $error4)
	{	}
	else
	{
		$metspak = $database->doQuery('INSERT INTO fu_ajk_mail SET type="'.$_REQUEST['typenotif'].'",
																   emailto="'.$_REQUEST['emailto'].'",
																   status="Aktif",
															  	   input_by="'.$q['id'].'",
															  	   input_date="'.$futgl.'"');
		header("location:ajk_re_mail.php");
	}
}
echo '<table border="0" width="50%" align="center">
	  <tr><td>
	  	<form name="f1" method="post" enctype="multipart/form-data" action="" class="input-list style-1 smart-green">
		<h1>Notifikasi Email</h1>
		<label><span>Type <font color="red">*</font> '.$error1.'</span>
			<select size="1" name="typenotif"><option value="">Pilih Type Notifikasi</option>
				<option value="Produksi">Produksi</option>
				<option value="Batal">Batal</option>
				<option value="Tolak">Tolak</option>
				<option value="Klaim">Klaim</option>
				<option value="Refund">Refund</option>
			</select>
		</label>
		<label><span>Email To <font color="red">*</font> '.$error2.' '.$error3.' '.$error4.'</span><input type="text" name="emailto" value="'.$_REQUEST['emailto'].'" size="30" placeholder="Email Tujuan"></label>
		<label><span>&nbsp;</span><input type="submit" name="ope" value="Simpan" class="button" /></label>
		</form>
	  </td></tr>
	  </table>';
		;
		break;
	default:
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%"><tr><th width="95%" align="left">Modul Notifikasi Email</font></th>'.$metnewuser.'</tr></table>';
$t=$database->doQuery('SELECT * FROM fu_ajk_mail ORDER BY id ASC');
		echo '<table border="0" cellpadding="3" cellspacing="1" width="100%" bgcolor="#bde0e6">
		     <tr align="center">
			     <th width="3%">No</th>
			     <th width="20%">Notifikasi</th>
			     <th>Email</th>
			     <th width="10%">Status</th>
			     <th width="5%">Pilih</th>
			 </tr>';
while($tt=mysql_fetch_array($t)){
if (($no % 2) == 1)	$objlass = 'tbl-odd'; else				$objlass = 'tbl-even';
if ($tt['status']=="Aktif") {
	$metmailstatus = '<a href="ajk_re_mail.php?m=maildel&idm='.$tt['id'].'" onClick="if(confirm(\'Anda yakin akan menghapus alamat notifikasi email ini?\')){return true;}{return false;}"><img src="../image/delete1.png"></a>';
}else{
	$metmailstatus = '<a href="ajk_re_mail.php?m=mailactive&idm='.$tt['id'].'" onClick="if(confirm(\'Anda yakin akan mengaktifkan alamat notifikasi email ini?\')){return true;}{return false;}"><img src="../image/save.png" width="20"></a>';
}
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		<td align="center">'.++$no.'</td>
		<td>'.$tt['type'].'</td>
		<td>'.$tt['emailto'].'</td>
		<td align="center">'.$tt['status'].'</td>
		<td align="center">'.$metmailstatus.'</td>
		</tr>';
}
echo '</table>';
	;
} // switch

?>