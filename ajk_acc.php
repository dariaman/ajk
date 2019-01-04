<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("../includes/functions.php");
connect();

if (isset($_SESSION['nm_user'])) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));	}
switch ($_REQUEST['r']) {
	case "profile":
echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="100%" align="left">Modul User</font></th></tr></table>';
$_profile = mysql_fetch_array($database->doQuery('SELECT
pengguna.id,
fu_ajk_costumer.`name`,
pengguna.nm_user,
pengguna.`password`,
pengguna.nm_lengkap,
pengguna.gender,
pengguna.dob,
pengguna.wilayah,
pengguna.cabang,
pengguna.email,
pengguna.`status`,
pengguna.aktif,
fu_ajk_level.`level`
FROM pengguna
INNER JOIN fu_ajk_costumer ON pengguna.id_cost = fu_ajk_costumer.id
INNER JOIN fu_ajk_level ON pengguna.`level` = fu_ajk_level.id
WHERE pengguna.id = "'.$q['id'].'"'));
echo '<table border="0" width="80%" cellpadding="1" cellspacing="1" align="center">
	  <tr><td width="20%">Nama Perusahaan</td><td>: <b>'.strtoupper($_profile['name']).'</b></td></tr>
	  <tr><td>Username</td><td>: <b>'.strtoupper($_profile['nm_user']).'</b></td></tr>
	  <tr><td>Password</td><td>: <b><a href="ajk_acc.php?r=edPassw">Edit</a></b></td></tr>
	  <tr><td>Nama </td><td>: '.$_profile['nm_lengkap'].'</td></tr>
	  <tr><td>Cabang </td><td>: '.$_profile['cabang'].'</td></tr>
	  <tr><td>Email</td><td>: '.$_profile['email'].'</td></tr>
	  <tr><td>Level</td><td>: '.$_profile['level'].'</td></tr>
	</table>';
		;
		break;

case "edPassw":
echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="100%" align="left">Modul Edit Password</font></th></tr></table>';
$_profile = mysql_fetch_array($database->doQuery('SELECT id, rahmad, nm_user FROM pengguna WHERE id = "'.$q['id'].'"'));

if ($_REQUEST['opp']=="edPassw") {
	if ($_REQUEST['uname']=="") 	{	$error1 = "<font color=red>Silahkan masukan password lama anda.";	}
	if ($_REQUEST['uname'] != $_profile['rahmad']){	$error1 = "<font color=red>Password anda tidak cocok";	}
	if ($_REQUEST['unameNew1']=="") {	$error2 = "<font color=red>Silahkan masukan password baru anda.";	}
	if ($_REQUEST['unameNew2']=="") {	$error3 = "<font color=red>Silahkan ulangi password baru anda.";	}
	if ($_REQUEST['unameNew1'] != $_REQUEST['unameNew2']) {	$error4 = "<font color=red>Password baru anda tidak sama.";	}

	if ($error1 OR $error2 OR $error3 OR $error4) {

	}
	else{
	$metProfile = $database->doQuery('UPDATE pengguna SET password="'.md5($_REQUEST['unameNew1']).'", rahmad="'.$_REQUEST['unameNew1'].'" WHERE id="'.$q['id'].'"');
//LOG HISTORY
$berkas = fopen("adonai1409ajk/historyedit.txt", "a") or die ("File history tidak ada.");
$asli__ = "(PASSWLAMA)\t" . $_profile['nm_user'] . " - " . $_profile['nm_user']. "";
fwrite($berkas, $asli__ . "\r\n");
$revisi__ = "(PASSWBARU)\t" . $_profile['nm_user'] . " - " . $_REQUEST['unameNew1'] . " - " . $futgl . "";
fwrite($berkas, $revisi__ . "\r\n");
fclose($berkas);
//LOG HISTORY
	//header("location:ajk_acc.php?r=profile");
echo '<div class="title2" align="center">Password anda telah dirubah, silahkan login kembali menggunakan password yang baru.</div><meta http-equiv="refresh" content="3;URL=login.php?op=logout">';
	}
}
echo '<form method="post" action="">
	  <table border="0" width="80%" cellpadding="1" cellspacing="1" align="center">
	  <tr><td width="20%">Username</td><td>: '.$_profile['nm_user'].'</td></tr>
	  <tr><td>Password Lama</td><td>: <input type="password" name="uname" value="'.$_REQUEST['uname'].'"> '.$error1.'</td></tr>
	  <tr><td>Password Baru</td><td>: <input type="password" name="unameNew1" value="'.$_REQUEST['unameNew1'].'"> '.$error2.'</td></tr>
	  <tr><td>Ulangi Password Baru</td><td>: <input type="password" name="unameNew2" value="'.$_REQUEST['unameNew2'].'"> '.$error3.' '.$error4.'</td></tr>
	  <tr><td colspan="2"><input type="hidden" name="opp" value="edPassw"><input type="submit" name="button" value="Simpan" class="button"></td></tr>
	</table></form>';
	;
	break;

	case "faq":
echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="100%" align="left">Modul FAQ</font></th></tr></table>';
if ($_REQUEST['n']=="1") {
echo '<table border="0" width="100%" cellpadding="5" cellspacing="1">
	  <tr bgcolor="#bde0e6"><th colspan="2">Cara Upload File Excel Non Medical</th></tr>
	  <tr><td width="1%">1.</td><td>Pilih menu <b>Master Upload Deklarasi</b> kemudian klik <b>Upload data Peserta SPAJ/SPD</b></td></tr>
	  <tr><td width="1%">2.</td><td>Pilih Produk pada kolom Nama Produk, kemudian pilih file excel (format data upload) pada data lokal komputer anda.</td></tr>
	  </table>';
}
elseif ($_REQUEST['n']=="2"){
echo '2';
}
elseif ($_REQUEST['n']=="3"){
echo '3';
}
elseif ($_REQUEST['n']=="4"){
echo '4';
}
elseif ($_REQUEST['n']=="5"){
echo '5';
}
else{
echo '<table border="0" width="100%" cellpadding="5" cellspacing="1" bgcolor="#bde0e6">
	  <tr><th width="1%">No</th>
	  	  <th>Pertanyaan - Pertanyaan</th>
	  </tr>';
if (($no % 2) == 1) $objlass = 'tbl-odd';	else $objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '"><td align="center">1</td>
		<td><a href="ajk_acc.php?r=faq&n=1">Cara Upload File Excel Non Medical</a></td></tr>
	  <tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '"><td align="center">2</td>
		<td><a href="ajk_acc.php?r=faq&n=2">Cara Upload File Excel Medical</a></td></tr>
	  <tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '"><td align="center">3</td>
		<td><a href="ajk_acc.php?r=faq&n=3">Validasi data file excel yang telah diupload (data deklarasi)</a></td></tr>
	  <tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '"><td align="center">4</td>
		<td><a href="ajk_acc.php?r=faq&n=4">Data Pengajuan Klaim (Refund, Batal dan Klaim meninggal)</a></td></tr>
	  <tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '"><td align="center">5</td>
		<td><a href="ajk_acc.php?r=faq&n=5">Validasi data pengajuan klaim (data pembatalan, refund dan klaim meninggal)</a></td></tr>
	 </table>';
}
		;
		break;

case "dlFLDK":
echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="100%" align="left">Download File Deklarasi</font></th></tr></table>';
echo '<table border="0" width="100%" cellpadding="5" cellspacing="1" bgcolor="#bde0e6">
	  <tr><th width="1%">No</th>
	  	  <th>Nama Produk</th>
	  	  <th width="10%">File Deklarasi</th>
	  </tr>';
$metProd = $database->doQuery('SELECT * FROM fu_ajk_polis WHERE del IS NULL AND id_cost="'.$q['id_cost'].'" ORDER BY nmproduk ASC');
while ($metProd_ = mysql_fetch_array($metProd)) {
if ($metProd_['deklarasifile']=="") {	$metDKFile = '';	}
else{	$metDKFile = '<a href="ajk_file/_ttd/'.$metProd_['deklarasifile'].'"><img src="image/pdftoexl.png" width="30"></a>';	}

if (($no % 2) == 1) $objlass = 'tbl-odd';	else $objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		<td align="center">'.++$no.'</td>
		<td>'.$metProd_['nmproduk'].'</td>
		<td align="center">'.$metDKFile.'</td>
	  </tr>';
}
echo '</table>';
	;
	break;

	default:
		;
} // switch


?>