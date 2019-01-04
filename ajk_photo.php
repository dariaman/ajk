<?php

// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("../includes/functions.php");
connect();

if (session_is_registered('nm_user')) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));	}
switch ($_REQUEST['q']) {
	case "a":
		;
		break;
	case "p_upload":
$met_peserta = mysql_fetch_array($database->doQuery('SELECT id, id_cost, id_polis, nama, id_peserta, spaj FROM fu_ajk_peserta WHERE id="'.$_REQUEST['idp'].'"'));
if ($_REQUEST['el']=="_photo") {
	if ($_FILES['photofile1']['name'] =="") 		{	$errno = "Silahkan upload photo pertama peserta!<br />";	}
	if ($_FILES['photofile1']['type'] !="image/jpeg" AND $_FILES['photofile1']['type'] !="image/JPG" AND $_FILES['photofile1']['type'] !="image/jpg")	{	$errno ="Photo pertama harus Format JPG !<br />";	}
	if ($_FILES['photofile1']['size'] / 1024 > $met_spaksize)	{	$errno ="File tidak boleh lebih dari 2Mb !<br />";	}
	if(file_exists($metpath.'/'.$_FILES['photofile1']['name'])){
	$errno = '<div align="center"><font color="red">Nama photo pertama sudah ada, photo pertama tidak bisa diupload !</div><meta http-equiv="refresh" content="5; url=ajk_photo.php?q=p_upload&idp='.$_REQUEST['idp'].'">';
	}

	if ($_FILES['photofile2']['name'] =="") 		{	$errno = "Silahkan upload photo kedua peserta!<br />";	}
	if ($_FILES['photofile2']['type'] !="image/jpeg" AND $_FILES['photofile2']['type'] !="image/JPG" AND $_FILES['photofile2']['type'] !="image/jpg")	{	$errno ="Photo kedua harus Format JPG !<br />";	}
	if ($_FILES['photofile2']['size'] / 1024 > $met_spaksize)	{	$errno ="File tidak boleh lebih dari 2Mb !<br />";	}
	if(file_exists($metpath.'/'.$_FILES['photofile1']['name'])){
		$errno = '<div align="center"><font color="red">Nama photo kedua sudah ada, photo kedua tidak bisa diupload !</div><meta http-equiv="refresh" content="5; url=ajk_photo.php?q=p_upload&idp='.$_REQUEST['idp'].'">';
	}
if ($errno) {	echo '<tr><td colspan="4" align="center"><font color="red">'.$errno.'</font></td></tr>';	}
	else{
	$photomet1 = $met_peserta['nama'].'_'.$_FILES["photofile1"]["name"];
	$photomet2 = $met_peserta['nama'].'_'.$_FILES["photofile2"]["name"];
	move_uploaded_file($_FILES['photofile1']['tmp_name'], $metpath . $photomet1);
	move_uploaded_file($_FILES['photofile2']['tmp_name'], $metpath . $photomet2);
	$metphoto = $database->doQuery('INSERT INTO fu_ajk_photo SET id_cost="'.$met_peserta['id_cost'].'",
																 id_produk="'.$met_peserta['id_polis'].'",
																 id_peserta="'.$met_peserta['id_peserta'].'",
																 nomor_spk="'.$met_peserta['spaj'].'",
																 photo_dekl_1="'.$photomet1.'",
																 photo_dekl_2="'.$photomet2.'",
																 input_by="'.$q['nm_user'].'",
																 input_time="'.$futgl.'"');

	echo '<div class="title2" align="center">Photo peserta telah berhasil di upload oleh <b>'.$q['nm_lengkap'].'</b> pada tanggal '.$futgldn.' !</div><meta http-equiv="refresh" content="5; url=ajk_photo.php">';
	}
}

echo '<form name="f1" method="post" enctype="multipart/form-data" action="">
	  <input type="hidden" name="idp" value="'.$met_peserta['id'].'">
	  <table border="0" cellpadding="1" cellspacing="0" width="100%">
      <tr><td width="50%" align="right">ID Peserta</td><td> : '.$met_peserta['id_peserta'].'</td></tr>
      <tr><td width="50%" align="right">Nama Peserta</td><td> : '.$met_peserta['nama'].'</td></tr>
	  <tr><td align="right">Photo Peserta 1<font color="red">*<br /><font size="1">Maksimal ukuran Photo 2MB</font></td><td valign="top">: <input name="photofile1" type="file" size="50" onchange="checkfile(this);"></td></tr>
	  <tr><td align="right">Photo Peserta 2<font color="red">*<br /><font size="1">Maksimal ukuran Photo 2MB</font></td><td valign="top">: <input name="photofile2" type="file" size="50" onchange="checkfile(this);"></td></tr>
	  <tr><td align="center"colspan="2"><input type="hidden" name="el" value="_photo"><input type="submit" name="upload_rate" value="Upload"></td></tr>
	  </table>
	  </form>';
		;
		break;
	default:
echo '<table border="0" cellpadding="5" cellspacing="1" width="100%"><tr><th width="100%" align="left">Modul Upload Photo Peserta</font></th></tr></table>';
echo '<fieldset style="padding: 2">
	<legend align="center">S e a r c h</legend>
	<table border="0" width="100%" cellpadding="1" cellspacing="1">
	<form method="post" action="">
	<tr><td width="30%" align="right">Nama Perusahaan :</td>
		<td width="30%">';
$quer2=mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$q['id_cost'].'"'));
echo  $quer2['name'];
echo '</td></tr>';
/*
	<tr><td align="right">Nama Produk :</td>
		<td> ';
$quer1=mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$q['id_polis'].'"'));
echo $quer1['nmproduk'].' ('.$quer1['nopol'].')';
echo '</td></tr>';
*/
echo '<tr><td align="right">Nomor Peserta :</td><td><input type="text" name="id_er" value="'.$_REQUEST['id_er'].'"></td></tr>
	  <tr><td align="right">Nama Lengkap Peserta :</td><td><input type="text" name="nama_er" value="'.$_REQUEST['nama_er'].'"></td></tr>
	  <tr><td align="center" colspan="2"><input type="hidden" name="re" value="datapeserta"><input type="submit" name="button" value="Cari" class="button"></td></tr>
	  </form></table></fieldset>';
if ($_REQUEST['re']=="datapeserta") {
if (!$_REQUEST['id_er'] AND !$_REQUEST['nama_er'])	{	echo '<center><font color="red">Silahkan input id peserta atau nama peserta</font></center>';	}
else{
if ($_REQUEST['nama_er'])	{	$satu = 'AND nama = "' . $_REQUEST['nama_er'] . '"';		}
if ($_REQUEST['id_er'])		{	$dua = 'AND id_peserta = "' . $_REQUEST['id_er'] . '"';		}
$data = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE nama !="" AND id_cost="'.$q['id_cost'].'" AND input_by="'.$q['nm_user'].'" ' . $satu . ' '.$dua.' AND status_aktif!="Batal" AND del IS NULL'));
$dataProduk = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$data['id_polis'].'"'));
$data_dn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn WHERE id="'.$data['id_dn'].'"'));
$caridata = mysql_num_rows($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE nama !="" AND id_cost="'.$q['id_cost'].'" AND input_by="'.$q['nm_user'].'" ' . $satu . ' '.$dua.' AND status_aktif!="Batal" AND del IS NULL'));
if ($caridata <= 0) {	echo '<center><font color="red">Maaf, data yang anda cari tidak ada. !</font></center>';	}else{
$met_photo = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_photo WHERE id_cost="'.$data['id_cost'].'" AND id_peserta="'.$data['id_peserta'].'" AND del IS NULL'));

if (!$met_photo) {	$photonya1 = '<img src="image/non-user.png" width="100">';
					$photonya2 = '<img src="image/non-user.png" width="100">';
					$met_photo_baru = '<a href="ajk_photo.php?q=p_upload&idp='.$data['id'].'"><img src="image/save.png" width="30"></a>';
	}	else	{
	$photonya1 = '<a href="'.$metpath.''.$met_photo['photo_dekl_1'].'" rel="lightbox" ><img src="'.$metpath.'/'.$met_photo['photo_dekl_1'].'" width="200"></a>';
	$photonya2 = '<a href="'.$metpath.''.$met_photo['photo_dekl_2'].'" rel="lightbox" ><img src="'.$metpath.'/'.$met_photo['photo_dekl_2'].'" width="200"></a>';
	//$met_edit_photo1 = '<td width="20%" align="center" valign="top"><a href=""><img src="image/edit_photo.png"></a></td>';		untuk edit photo peserta
	//$met_edit_photo2 = '<td width="20%" align="center" valign="top"><a href=""><img src="image/edit_photo.png"></a></td>';		untuk edit photo peserta
}

if ($data['status_bayar']=="1") {	$pembayaran = '<b>Lunas</b>';	}else{	$pembayaran = '<b>Belum Lunas</b>';	}
if ($data['type_data']=="SPK") {
	$metTenor = $data['kredit_tenor'] * 12;
}else{
	$metTenor = $data['kredit_tenor'];
}
echo '<table border="0" width="90%" cellpadding="3" cellspacing="1" align="center" style="border: solid 1px #DEDEDE">
		<tr><td colspan="4" class="title2">Upload Photo Data Peserta</td></tr>
		<tr><td bgcolor="DEDEDE">Produk</td><td>: <b>'.$dataProduk['nmproduk'].'</b></td>'.$met_edit_photo1.''.$met_edit_photo2.'</tr>
		<tr><td bgcolor="DEDEDE" width="20%">Nomor DN</td><td>: '.$data_dn['dn_kode'].'</td><td rowspan="12" width="20%" align="center">'.$photonya1.'</td><td rowspan="12" width="20%" align="center">'.$photonya2.'</td></tr>
		<tr><td bgcolor="DEDEDE">ID Peserta</td><td>: '.$data['id_peserta'].'</td></tr>
		<tr><td bgcolor="DEDEDE">Nama Tertanggung</td><td>: '.$data['nama'].'</td></tr>
		<tr><td bgcolor="DEDEDE">Tanggal Lahir</td><td>: '._convertDate($data['tgl_lahir']).'</td></tr>
		<tr><td bgcolor="DEDEDE">Usia</td><td>: '.$data['usia'].' tahun</td></tr>
		<tr><td bgcolor="DEDEDE">Plafond</td><td>: '.duit($data['kredit_jumlah']).'</td></tr>
		<tr><td bgcolor="DEDEDE">Tenor</td><td>: '.$metTenor.' bulan</td></tr>
		<tr><td bgcolor="DEDEDE">Tanggal Asuransi</td><td>: '._convertDate($data['kredit_tgl']).' s/d '._convertDate($data['kredit_akhir']).'</td></tr>
		<tr><td bgcolor="DEDEDE">Premi</td><td>: '.duit($data['premi']).'</td></tr>
		<tr><td bgcolor="DEDEDE">Extra Premi</td><td>: '.duit($data['ext_premi']).'</td></tr>
		<tr><td bgcolor="DEDEDE">Premi</td><td>: <b>'.duit($data['totalpremi']).'</b></td></tr><tr>
		<tr><td bgcolor="DEDEDE">Status</td><td>: '.$pembayaran.'</td></tr><tr>
		<tr><td bgcolor="DEDEDE">Cabang</td><td>: '.$data['cabang'].'</td></tr><tr>
		<tr><td colspan="4" align="center">'.$met_photo_baru.'</td></tr>';
echo '</table>';
		}
	}
}
	;
} // switch
echo "<script type=\"text/javascript\" language=\"javascript\">
function checkfile(sender) {
	var validExts = new Array(\".jpeg\", \".jpg\", \".gif\", \".png\");
	var fileExt = sender.value;
	fileExt = fileExt.substring(fileExt.lastIndexOf('.'));
	if (validExts.indexOf(fileExt) < 0) {	alert(\"Invalid file selected, valid files are of \" + validExts.toString() + \" types.\");	return false;	}
	else return true;
}
</script>";
?>