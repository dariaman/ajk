<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("../includes/functions.php");
connect();
if (session_is_registered('nm_user')) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));}
if ($q['status'] !="" AND $q['supervisor']=="1") {	$metnewuser = '';	}else{	$metnewuser ='<th><a href="set_ttd.php?r=tambah"><img border="0" src="../image/new.png" width="25"></a></th>';	}
switch ($_REQUEST['r']) {
case "logoheader":
if ($_REQUEST['lg']=="dnY")			{	$metlogo = $database->doQuery('UPDATE fu_ajk_logoheader SET debitnote="Y" WHERE id="'.$_REQUEST['id'].'"');	}
elseif ($_REQUEST['lg']=="dnT")		{	$metlogo = $database->doQuery('UPDATE fu_ajk_logoheader SET debitnote="T" WHERE id="'.$_REQUEST['id'].'"');	}
elseif ($_REQUEST['lg']=="cnY")		{	$metlogo = $database->doQuery('UPDATE fu_ajk_logoheader SET creditnote="Y" WHERE id="'.$_REQUEST['id'].'"');	}
elseif ($_REQUEST['lg']=="cnT")		{	$metlogo = $database->doQuery('UPDATE fu_ajk_logoheader SET creditnote="T" WHERE id="'.$_REQUEST['id'].'"');	}
elseif ($_REQUEST['lg']=="kuiY")	{	$metlogo = $database->doQuery('UPDATE fu_ajk_logoheader SET kuitansi="Y" WHERE id="'.$_REQUEST['id'].'"');	}
elseif ($_REQUEST['lg']=="kuiT")	{	$metlogo = $database->doQuery('UPDATE fu_ajk_logoheader SET kuitansi="T" WHERE id="'.$_REQUEST['id'].'"');	}
else{	}

$tt=mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_logoheader WHERE id="1"'));
echo '<table border="0" cellpadding="3" cellspacing="1" width="100%" bgcolor="#bde0e6">
     <tr align="center">
	     <th>Keterangan Logo Header</th>
	     <th width="5%">Aktif</th>
	 </tr>';

if ($tt['debitnote']=="T") {
	$logoheaddn = '<a href="set_ttd.php?r=logoheader&lg=dnY&id='.$tt['id'].'"><img src="../image/deleted.png" width="25"></a>';
}else{
	$logoheaddn = '<a href="set_ttd.php?r=logoheader&lg=dnT&id='.$tt['id'].'"><img src="../image/save.png" width="25"></a>';
}

if ($tt['creditnote']=="T") {
	$logoheadcn = '<a href="set_ttd.php?r=logoheader&lg=cnY&id='.$tt['id'].'"><img src="../image/deleted.png" width="25"></a>';
}else{
	$logoheadcn = '<a href="set_ttd.php?r=logoheader&lg=cnT&id='.$tt['id'].'"><img src="../image/save.png" width="25"></a>';
}

if ($tt['kuitansi']=="T") {
	$ttdkuitansi = '<a href="set_ttd.php?r=logoheader&lg=kuiY&id='.$tt['id'].'"><img src="../image/deleted.png" width="25"></a>';
}else{
	$ttdkuitansi = '<a href="set_ttd.php?r=logoheader&lg=kuiT&id='.$tt['id'].'"><img src="../image/save.png" width="25"></a>';
}
if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	     <td>Tampilkan Logo Header Debitnote</td>
	     <td align="center">'.$logoheaddn.'</td>
	  </tr>
	  <tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '"><td>Tampilkan Logo Header Creditnote</td>
		  <td align="center">'.$logoheadcn.'</td>
	  </tr>
	  <tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '"><td>Tampilkan Tanda Tangan Kuitansi</td>
		  <td align="center">'.$ttdkuitansi.'</td>
	  </tr>';
echo '</table>';
	;
	break;

	case "ttd_del":
$cekdata_ttd = $database->doQuery('SELECT * FROM fu_ajk_ttd WHERE id="'.$_REQUEST['idttd'].'"');
while ($cekdata_ttd_ = mysql_fetch_array($cekdata_ttd)) {
	unlink ($metpath_ttd .$cekdata_ttd_['img_ttd']);
}
$del_met = $database->doQuery('DELETE FROM fu_ajk_ttd WHERE id="'.$_REQUEST['idttd'].'"');
echo '<table border="0" cellpadding="1" cellspacing="0" width="100%"><tr><th width="100%" align="center">Data Tanda Tangan telah dihapus. !</font></th></tr></table>
	  <meta http-equiv="refresh" content="2; url=set_ttd.php">';
		;
		break;
	case "tambah":
if ($_REQUEST['ope']=="Simpan") {
if (!$_REQUEST['fname'])  $error1 .='<blink><font color=red>Silahkan pilih nama perusahaan</font></blink><br>';
if (!$_REQUEST['erperusahaan'])  $error4 .='<blink><font color=red>Silahkan pilih nama perusahaan</font></blink><br>';
if (!$_REQUEST['erasuransi'])  $error5 .='<blink><font color=red>Silahkan pilih nama asuransi</font></blink><br>';
if ($_FILES['userfile']['size'] / 1024 > $met_ttdsize)	{	$error2 .="<blink><font color=red>File tidak boleh lebih dari 500Kb !</font></blink><br />";	}
else{
if (!$_FILES['userfile']['tmp_name'])  $error3 .='<blink><font color=red>Silahkan upload file image tanda tangan.</font></blink><br>';
	$allowedExtensions = array("jpg","jpeg","gif", "png");
	foreach ($_FILES as $file) {
		if ($file['tmp_name'] > '') {
			if (!in_array(end(explode(".",	strtolower($file['name']))), $allowedExtensions)) {
				die('<center><font color=red>'.$file['name'].' <br /><blink>File extension tidak diperbolehkan selain image!</blink></font><br/>'.'<a href="set_ttd.php?r=tambah">'.'&lt;&lt Go Back</a></center>');
			}
		}
	}
}
	if ($error1 OR $error2 OR $error3)
	{	}
	else
	{
	move_uploaded_file($_FILES['userfile']['tmp_name'], $metpath_ttd . $_FILES["userfile"]["name"]);
	$metspak = $database->doQuery('INSERT INTO fu_ajk_ttd SET nama="'.$_REQUEST['fname'].'",
															  id_cost="'.$_REQUEST['erperusahaan'].'",
															  id_as="'.$_REQUEST['erasuransi'].'",
															  rpr_dn="'.$_REQUEST['lpr_dn'].'",
															  rpr_peserta="'.$_REQUEST['lpr_peserta'].'",
															  img_ttd="'.$_FILES['userfile']['name'].'",
															  input_by="'.$q['nm_user'].'",
															  input_date="'.$futgl.'"');
	header("location:set_ttd.php");
	}
}
$perusahaan = $database->doQuery('SELECT * FROM fu_ajk_costumer WHERE del IS NULL ORDER BY id DESC');
$asuransi = $database->doQuery('SELECT * FROM fu_ajk_asuransi WHERE del IS NULL ORDER BY id DESC');
echo '<table border="0" width="50%" align="center">
	  <tr><td>
	  	<form name="f1" method="post" enctype="multipart/form-data" action="" class="input-list style-1 smart-green">
		<h1>Nama Tanda Tangan</h1>
		<label><span>Nama Perusahaan <font color="red">*</font> '.$error4.'</span>
			<select size="1" name="erperusahaan"><option value="">Pilih Perusahaan</option>';
while ($perusahaan_ = mysql_fetch_array($perusahaan)) {
	echo '<option value="'. $perusahaan_['id'].'"'._selected($_REQUEST["erperusahaan"], $perusahaan_['name']).'>'. $perusahaan_['name'].'</option>';
}

echo '</select></label>
	<label><span>Nama Asuransi <font color="red">*</font> '.$error5.'</span>
			<select size="1" name="erasuransi"><option value="">Pilih Asuransi</option>';
while ($asuransi_ = mysql_fetch_array($asuransi)) {
	echo '<option value="'. $asuransi_['id'].'"'._selected($_REQUEST["erasuransi"], $asuransi_['name']).'>'. $asuransi_['name'].'</option>';
}

echo '</select></label>
		<label><span>Nama Tanda Tangan <font color="red">*</font> '.$error1.'</span><input type="text" name="fname" value="'.$_REQUEST['fname'].'" size="30" placeholder="Nama Tanda Tangan"></label>
		<label><span>Upload Image Tanda Tangan (max size 500Kb)<font color="red">*</font> '.$error2.''.$error3.'</span><input name="userfile" type="file" size="50" placeholder="Image Tanda Tangan" onchange="checkfile(this);"></label>
		<label><span>POSISI TANDA TANGAN</span></label><br /><br />
		<label><span>Laporan PDF DN : </span><input type="hidden">
											 <input type="radio" name="lpr_dn" value="Y"'.pilih($_REQUEST["lpr_dn"], "Y").'>Ya
											 <input type="radio" name="lpr_dn" value="T"'.pilih($_REQUEST["lpr_dn"], "T").'>Tidak
		</label>
		<label><span>Laporan PDF Peserta : </span><input type="hidden">
												  <input type="radio" name="lpr_peserta" value="Y"'.pilih($_REQUEST["lpr_peserta"], "Y").'>Ya
												  <input type="radio" name="lpr_peserta" value="T"'.pilih($_REQUEST["lpr_peserta"], "T").'>Tidak
		</label>
		<label><span>&nbsp;</span><input type="submit" name="ope" value="Simpan" class="button" /></label>
		</form>
	  </td></tr>
	  </table>';
		;
		break;

case "tmark_dn":
$met = $database->doQuery('UPDATE fu_ajk_ttd SET rpr_dn="T", update_by="'.$q['nm_user'].'", update_date="'.$futgl.'" WHERE id="'.$_REQUEST['id'].'"');
header("location:set_ttd.php");
	;
	break;

case "ymark_dn":
$met = $database->doQuery('UPDATE fu_ajk_ttd SET rpr_dn="Y", update_by="'.$q['nm_user'].'", update_date="'.$futgl.'" WHERE id="'.$_REQUEST['id'].'"');
header("location:set_ttd.php");
	;
	break;

case "tmark_psrt":
$met = $database->doQuery('UPDATE fu_ajk_ttd SET rpr_peserta="T", update_by="'.$q['nm_user'].'", update_date="'.$futgl.'" WHERE id="'.$_REQUEST['id'].'"');
header("location:set_ttd.php");
	;
	break;

case "ymark_psrt":
$met = $database->doQuery('UPDATE fu_ajk_ttd SET rpr_peserta="Y", update_by="'.$q['nm_user'].'", update_date="'.$futgl.'" WHERE id="'.$_REQUEST['id'].'"');
header("location:set_ttd.php");
	;
	break;

	default:
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%"><tr><th width="95%" align="left">Modul Tanda Tangan</font></th>'.$metnewuser.'</tr></table>';
$t=$database->doQuery('SELECT * FROM fu_ajk_ttd ORDER BY id ASC');
echo '<table border="0" cellpadding="3" cellspacing="1" width="100%" bgcolor="#bde0e6">
     <tr align="center">
	     <th width="3%">No</th>
	     <th width="20%">Perusahaan</th>
	     <th width="20%">Asuransi</th>
	     <th>Nama</th>
	     <th width="5%">Img TTD</th>
	     <th width="5%">PDF<br />Debit Note</th>
	     <th width="5%">PDF Peserta</th>
	     <th width="5%">Pilih</th>
	 </tr>';
while($tt=mysql_fetch_array($t)){
$metCustomer = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$tt['id_cost'].'"'));
$metAsuransi = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_asuransi WHERE id="'.$tt['id_as'].'"'));
if ($tt['rpr_dn']=="T") {	$met_ttd_dn = '<a href="set_ttd.php?r=ymark_dn&id='.$tt['id'].'"><img src="../image/deleted.png" width="25"></a>';	}
else	{	$met_ttd_dn = '<a href="set_ttd.php?r=tmark_dn&id='.$tt['id'].'"><img src="../image/save.png" width="25"></a>';	}

if ($tt['rpr_peserta']=="T") {	$met_ttd_psrt = '<a href="set_ttd.php?r=ymark_psrt&id='.$tt['id'].'"><img src="../image/deleted.png" width="25"></a>';	}
else	{	$met_ttd_psrt = '<a href="set_ttd.php?r=tmark_psrt&id='.$tt['id'].'"><img src="../image/save.png" width="25"></a>';	}

if (($no % 2) == 1)	$objlass = 'tbl-odd'; else				$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		<td align="center">'.++$no.'</td>
		<td>'.$metCustomer['name'].'</td>
		<td>'.$metAsuransi['name'].'</td>
		<td>'.$tt['nama'].'</td>
		<td align="center"><img src="../ajk_file/_ttd/'.$tt['img_ttd'].'" width="100"></td>
		<td align="center">'.$met_ttd_dn.'</td>
		<td align="center">'.$met_ttd_psrt.'</td>
		<td align="center"><a href="set_ttd.php?r=ttd_del&idttd='.$tt['id'].'" onClick="if(confirm(\'Anda yakin akan menghapus data tanda tangan ini?\')){return true;}{return false;}"><img src="../image/delete1.png"></a>
		</td>
	</tr>';
}
echo '</table>';
		;
} // switch
?>
<script type="text/javascript" language="javascript">
function checkfile(sender) {
	var validExts = new Array(".jpeg", ".jpg", ".gif", ".png");
	var fileExt = sender.value;
	fileExt = fileExt.substring(fileExt.lastIndexOf('.'));
	if (validExts.indexOf(fileExt) < 0) {	alert("Invalid file selected, valid files are of " + validExts.toString() + " types.");	return false;	}
	else return true;
}
</script>