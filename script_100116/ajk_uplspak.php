<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("../includes/functions.php");
connect();

if (session_is_registered('nm_user')) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));	}

switch ($_REQUEST['el']) {
case "appr_spak":
//$met = $database->doQuery('UPDATE fu_ajk_spak_temp SET status="Proses" WHERE input_by="'.$_REQUEST['iu'].'" AND input_date="'.$_REQUEST['dt'].'"');
echo '<table border="0" cellpadding="1" cellspacing="0" width="100%"><tr><th width="100%" align="center">Data SPAK telah selesai di Upload.</font></th></tr></table>';
$met_comp = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$_REQUEST['idc'].'"'));
//echo 'Nama Perusahaan : '.$met_comp['name'].'<br />';
$message .= '<html><head><title>UPLOAD SPAK</title></head><body>
			<table border="0" width="75%">
			<tr><td width="1%">No</td>
				<td width="10%">SPAK</td>
				<td>Nama File</td>
				<td width="15%">Tgl Upload</td>
			</tr>';

$uplspak = $database->doQuery('SELECT * FROM fu_ajk_spak_temp WHERE input_by="'.$_REQUEST['iu'].'" AND input_date="'.$_REQUEST['dt'].'" AND status="Pending"');
while ($uplspak_ = mysql_fetch_array($uplspak)) {
$message .= '<tr><td>'.++$no.'</td><td>'.$uplspak_['spak'].'</td><td>'.$uplspak_['fname'].'</td><td>'.$uplspak_['input_date'].'</td></tr>';
	}
$message .='</table></body></html>';
	$mail = new PHPMailer;
	$mail->IsSMTP();
	$mail->Host = SMTP_HOST; //Hostname of the mail server
	$mail->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
	$mail->Username = SMTP_UNAME; //Username for SMTP authentication any valid email created in your domain
	$mail->Password = SMTP_PWORD; //Password for SMTP authentication
	$mail->AddReplyTo = SMTP_REPLYMAIL; //reply-to address
	$mail->debug = 1;
	$mail->SMTPSecure = "ssl";
	$mail->SetFrom ($q['email'], $q['nm_lengkap']); //From address of the mail
	//$mail->Subject = "AJKOnline - DN BARU DARI U/W (".$q['nm_lengkap'].")"; //Subject od your mail
	$mail->Subject = "AJKOnline - DATA SPAK"; //Subject od your mail
	//EMAIL KE SUPERVISOR / ATASAN STAFF
	$mailsupervisorajk = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="'.$q['id_cost'].'" AND level="'.$q['level'].'" AND status=""');
	while ($_mailsupervisorajk = mysql_fetch_array($mailsupervisorajk)) {
		$mail->AddAddress($_mailsupervisorajk['email'], $_mailsupervisorajk['nm_lengkap']);
	}
	//EMAIL KE SUPERVISOR / ATASAN STAFF

	//EMAIL DIKIRIM KE STAFF (USERNYA SENDIRI)
	$mail->AddAddress($q['email'], $q['nm_lengkap']);
	//EMAIL DIKIRIM KE STAFF (USERNYA SENDIRI)

	$mail->AddCC("penting_ga@hotmail.com, sysdev@kode.web.id, arief@arief.kurniawan.com, gunarso@adonai.co.id");
	$mail->MsgHTML('<table><tr><th>Data SPAK telah di upload oleh <b>'.$_SESSION['nm_user'].' pada tanggal '.$futgl.'</tr></table>'.$message); //Put your body of the message you can place html code here
	$send = $mail->Send(); //Send the mails
echo '<meta http-equiv="refresh" content="2; url=ajk_uplspak.php">';
	;
	break;

case "ccl_spak":
$cekdata_spak = $database->doQuery('SELECT * FROM fu_ajk_spak_temp WHERE input_by="'.$_REQUEST['iu'].'" AND input_date="'.$_REQUEST['dt'].'"');
while ($cekdata_spak_ = mysql_fetch_array($cekdata_spak)) {
unlink ($metpath .$cekdata_spak_['fname']);
unlink ($metpath .$cekdata_spak_['photo_spk']);
}
$cancel_met = $database->doQuery('DELETE FROM fu_ajk_spak_temp WHERE input_by="'.$_REQUEST['iu'].'" AND input_date="'.$_REQUEST['dt'].'"');
echo '<table border="0" cellpadding="1" cellspacing="0" width="100%"><tr><th width="100%" align="center">Data SPAK telah dibatalkan !</font></th></tr></table>
	  <meta http-equiv="refresh" content="2; url=ajk_uplspak.php">';
		;
		break;

case "upload_spk":
echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="100%" align="left">Modul Upload Data SPK</font></th></tr></table>';
if ($_REQUEST["userProduk"]=="") {
	echo '<blink><center><font color=red>Silahkan pilih nama produk. !</font></blink><a href="ajk_uplspak.php">'.'&lt;&lt Go Back</a></center>';
}else{
$metroduk_ = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$_REQUEST['userProduk'].'"'));
echo '<table border="0" cellpadding="1" cellspacing="1" width="80%" bgcolor="#bde0e6" align="center">
		<tr><th>Nama Produk</th><th colspan="5" align="left">'.$metroduk_['nmproduk'].'</th></tr>
		<tr><th width="10%">Nomor SPAK</th>
		<th>Filename</th>
			<th width="10%">Filesize</th>
			<th width="10%">Filetype</th>
			<th width="20%">Filephoto</th>
		</tr>';
for($i=0;$i<count($_POST["no_spk"]);$i++)
{

/* Validasi dengan Photo
if ($_POST["no_spk"][$i] !="" AND $_FILES['userfile']['name'][$i] =="" AND $_FILES['photospk']['name'][$i] !="") {	$errno[$i] = "Silahkan upload file !<br />";	}
	if ($_POST["no_spk"][$i] =="" AND $_FILES['userfile']['name'][$i] !="" AND $_FILES['photospk']['name'][$i] !="") {	$errno[$i] = "Silahkan input nomor SPAK<br />";	}
	if ($_POST["no_spk"][$i] !="" AND $_FILES['userfile']['name'][$i] !="" AND $_FILES['photospk']['name'][$i] =="") {	$errno[$i] = "Silahkan Upload photo peserta<br />";	}
	if ($_POST["no_spk"][$i] !="" AND $_FILES['userfile']['name'][$i] !="" AND $_FILES['photospk']['name'][$i] !="") {
*/

	if ($_POST["no_spk"][$i] !="" AND $_FILES['userfile']['name'][$i] =="") {	$errno[$i] = "Silahkan upload file !<br />";	}
	if ($_POST["no_spk"][$i] =="" AND $_FILES['userfile']['name'][$i] !="") {	$errno[$i] = "Silahkan input nomor SPAK<br />";	}
	if ($_POST["no_spk"][$i] !="" AND $_FILES['userfile']['name'][$i] !="") {
		//CEK DATA SPK
		$cekspk = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak WHERE id_cost="'.$q['id_cost'].'" AND spak="'.$_POST["no_spk"][$i].'" AND status !="Batal" '));
		if ($cekspk['spak']==$_POST["no_spk"][$i]) {	$errno[$i] = 'Nomor SPK '.$_POST["no_spk"][$i].' sudah ada dan telah di approve !<br />';	}

		$cekspk_temp = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_temp WHERE id_cost="'.$q['id_cost'].'" AND spak="'.$_POST["no_spk"][$i].'"'));
		if ($cekspk_temp['spak']==$_POST["no_spk"][$i]) {	$errno[$i] = 'Nomor SPK '.$_POST["no_spk"][$i].' sudah ada, silahkan upload data SPK yang lain !<br />';	}

		$cekspk_file = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak WHERE id_cost="'.$q['id_cost'].'" AND fname="'.$_FILES['userfile']['name'][$i].'" AND status !="Batal" '));
		if ($cekspk_file['fname']==$_FILES['userfile']['name'][$i]) {	$errno[$i] = 'File SPK '.$_FILES['userfile']['name'][$i].' sudah pernah di upload !<br />';	}

		$cekspk_filetemp = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_temp WHERE id_cost="'.$q['id_cost'].'" AND fname="'.$_FILES['userfile']['name'][$i].'"'));
		if ($cekspk_filetemp['fname']==$_FILES['userfile']['name'][$i]) {	$errno[$i] = 'File SPK '.$_FILES['userfile']['name'][$i].' sudah pernah di upload !<br />';	}
		//CEK DATA SPK

	}
	if ($errno[$i]) {	echo '<tr><td colspan="4">'.$errno[$i].'</td></tr>';

	}else{
	//echo $_FILES['userfile']['type'][$i].'<br />';
	if ($_FILES['userfile']['name'][$i] !="" AND
		$_FILES['userfile']['type'][$i] !="application/pdf" AND
		$_FILES['userfile']['type'][$i] !="image/jpeg" AND
		$_FILES['userfile']['type'][$i] !="image/JPG")
		{	$errno[$i] ="File harus Format PDF !<br />";	}
	if ($_FILES['userfile']['size'][$i] / 1024 > $met_spaksize)	{	$errno[$i] ="File tidak boleh lebih dari 1Mb !<br />";	}
	//if ($_FILES['photospk']['name'][$i] !="" AND $_FILES['photospk']['type'][$i] !="image/jpeg" AND $_FILES['photospk']['type'][$i] !="image/JPG")	{	$errno[$i] ="File harus Format JPG !<br />";	}
	if ($_FILES['photospk']['size'][$i] / 1024 > $met_spaksize)	{	$errno[$i] ="File photo tidak boleh lebih dari 1Mb !<br />";	}
	if ($errno[$i]) {	echo '<tr><td colspan="5" align="center"><font color="red">'.$errno[$i].'</font></td></tr>';	}
	else{
		if ($_POST["no_spk"][$i] =="" OR $_FILES['userfile']['name'][$i] ==""){	}else{
		move_uploaded_file($_FILES['userfile']['tmp_name'][$i], $metpath . $_FILES["userfile"]["name"][$i]);
		move_uploaded_file($_FILES['photospk']['tmp_name'][$i], $metpath . $_FILES["photospk"]["name"][$i]);
		$metspak = $database->doQuery('INSERT INTO fu_ajk_spak_temp SET id_cost="'.$q['id_cost'].'",
																		id_polis="'.$_REQUEST['userProduk'].'",
																		spak="'.$_POST["no_spk"][$i].'",
																		fname="'.$_FILES['userfile']['name'][$i].'",
																		photo_spk="'.$_FILES['photospk']['name'][$i].'",
																		input_by="'.$q['nm_user'].'",
																		input_date="'.$futgl.'"');

		if (($no % 2) == 1)	$objlass = 'tbl-odd'; else $objlass = 'tbl-even';
		$metView = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_temp WHERE spak="'.$_POST["no_spk"][$i].'"'));
		if (!$metView['photo_spk']) {	$v_photo = '<img src="image/non-user.png" width="50">';

		}else{
			$v_photo = '<img src="'.$metpath.''.$metView['photo_spk'].'" width="50">';
		}
		echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
				<td align="center">'.$_POST["no_spk"][$i].'</td>
				'.$error_spk.'
				<td>'.$_FILES['userfile']['name'][$i].' <input type="hidden" name="nametemp" value="'.$_FILES['userfile']['tmp_name'][$i].'"></td>
				<td align="center">'.ceil($_FILES['userfile']['size'][$i] / 1024).' kb</td>
				<td align="center">'.$_FILES['userfile']['type'][$i].'</td>
				<td align="center">'.$v_photo.'</td>
			  </tr>';
			$mail = new PHPMailer;
			$mail->IsSMTP();
			$mail->Host = SMTP_HOST; //Hostname of the mail server
			$mail->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
			$mail->Username = SMTP_UNAME; //Username for SMTP authentication any valid email created in your domain
			$mail->Password = SMTP_PWORD; //Password for SMTP authentication
			$mail->AddReplyTo = SMTP_REPLYMAIL; //reply-to address
			$mail->debug = 1;
			$mail->SMTPSecure = "ssl";
		$mail->SetFrom ($q['email'], $q['nm_lengkap']); //From address of the mail
		$mail->Subject = "AJKOnline - UPLOAD DATA SPK"; //Subject od your mail
		//EMAIL PENERIMA  SPV CLIENT
		$mailsupervisorajk = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="'.$q['id_cost'].'" AND id_polis="'.$q['id_polis'].'" AND level="'.$q['level'].'" AND status=""');
		while ($_mailsupervisorajk = mysql_fetch_array($mailsupervisorajk)) {
			$mail->AddAddress($_mailsupervisorajk['email'], $_mailsupervisorajk['nm_lengkap']); //To address who will receive this email
		}
		//EMAIL PENERIMA  SPV CLIENT
		$mail->AddCC("penting_ga@hotmail.com, sysdev@kode.web.id, arief@arief.kurniawan.com, gunarso@adonai.co.id");
		$mail->MsgHTML('<table><tr><th>Data SPK telah diupload oleh <b>'.$_SESSION['nm_user'].' selaku staff AJK-Online pada tanggal '.$futgl.'</tr></table>'.$message); //Put your body of the message you can place html code here
		$send = $mail->Send(); //Send the mails
		}
	}
	}
}
if ($errno) {	echo '<tr><td colspan="5" align="center"><a href="ajk_uplspak.php?el=ccl_spak&iu='.$q['nm_user'].'&dt='.$futgl.'"><img src="image/deleted.png" width="30"></a></td></tr>';
}else{
	echo '<tr><td colspan="5" align="center"><a href="ajk_uplspak.php?el=ccl_spak&iu='.$q['nm_user'].'&dt='.$futgl.'"><img src="image/deleted.png" width="30"></a> &nbsp;
											 <a href="ajk_uplspak.php?el=appr_spak&idc='.$q['id_cost'].'&iu='.$q['nm_user'].'&dt='.$futgl.'"><img src="image/save.png" width="30"></a></td></tr>';
}
echo '</table>';
}
		;
		break;

case "parsingspk":
$fu = mysql_fetch_array($database->doQuery('SELECT fu_ajk_costumer.name, fu_ajk_costumer.id FROM fu_ajk_costumer WHERE id="'.$q['id_cost'].'"'));
//$fufile = mysql_fetch_array($database->doQuery('SELECT fu_ajk_peserta.namafile FROM fu_ajk_peserta WHERE id_cost="'.$_REQUEST['cat'].'"'));
$fupolis = mysql_fetch_array($database->doQuery('SELECT id, nmproduk, age_deviasi, mpptype, mppbln FROM fu_ajk_polis WHERE id="'.$q['id_polis'].'"'));
$_REQUEST['bataskolom'] = $_POST['bataskolom'];		if (!$_REQUEST['bataskolom'])  $error .='Silahkan tentukan batas kolom file<br />.';
if (!$_FILES['userfile']['tmp_name'])  $error .='Silahkan upload file excel anda<br />.';
	$allowedExtensions = array("xls","xlsx","csv");
	foreach ($_FILES as $file) {
		if ($file['tmp_name'] > '') {
			if (!in_array(end(explode(".",	strtolower($file['name']))), $allowedExtensions)) {
				die('<center><font color=red>'.$file['name'].' <br /><blink>File extension tidak diperbolehkan selain excel!</blink></font><br/>'.'<a href="ajk_uplspak.php?el=fl_spk">'.'&lt;&lt Go Back</a></center>');
			}
		}
	}
if ($error)
{	echo '<blink><center><font color=red>'.$error.'</font></blink><a href="ajk_uplspak.php?el=fl_spk">'.'&lt;&lt Go Back</a></center>';	}

else
{
echo '<form method="post" action="" onload ="onbeforeunload">
	  <table border="0" width="100%" cellpadding="1" cellspacing="1" style="border: solid 3px #DEDEDE"  bgcolor="#bde0e6">
		<tr><td colspan="2"><input type="hidden" name="idcostumer" value="'.$fu['id'].'">Nama Perusahaan</td><td colspan="24">: <b>'.$fu['name'].'</b></td></tr>
		<tr><td colspan="2"><input type="hidden" name="idpolis" value="'.$fupolis['id'].'">Nama Produk</td><td colspan="24">: <b>'.$fupolis['nmproduk'].'</b></td></tr>
		<tr><td colspan="2"><input type="hidden" name="bataskolom" value="'.$_REQUEST['bataskolom'].'"><input type="hidden" name="namafileexl" value="'.$_FILES['userfile']['name'].'">File Name</td><td colspan="24">: <b>'.$_FILES['userfile']['name'].'</b></td></tr>
		<tr><th width="1%" rowspan="2">No</th>
			<th width="8%" rowspan="2">No SPK</th>
			<th rowspan="2">Nama Tertanggung</th>
			<th width="10%" colspan="3">Tanggal Lahir</th>
			<th rowspan="2" width="1%">Usia</th>
			<th width="5%" rowspan="2">Uang Asuransi</th>
			<th width="10%" colspan="3">Mulai Asuransi</th>
			<th width="1%" rowspan="2">Tenor<br />(thn)</th>
			<th width="1%" rowspan="2">EM(%)</th>
			<th width="1%" rowspan="2">MPP (bln)</th>
			<th width="10%" rowspan="2">Cabang</th>
			<th width="10%" rowspan="2">Keterangan</th>
		</tr>
	<tr><th>Hari</th><th>Bulan</th><th>Tahun</th><th>Hari</th><th>Bulan</th><th>Tahun</th></tr>';
	$data = new Spreadsheet_Excel_Reader($_FILES['userfile']['tmp_name']);
	$baris = $data->rowcount($sheet_index=0);											//MEMBACA JUMLAH BARIS DATA EXCEL

for ($i=10; $i<=$_REQUEST['bataskolom']; $i++)
{
	$data1=$data->val($i, 1);		//no
	$data2=$data->val($i, 2);		//S P K
	$data3=$data->val($i, 3);		//NAMA TERTANGGUNG
	$data4=$data->val($i, 4);		//TANGGAL LAHIR (TGL)
	$data5=$data->val($i, 5);		//TANGGAL LAHIR (BLN)
	$data6=$data->val($i, 6);		//TANGGAL LAHIR (THN)
	$data7=$data->val($i, 7);		//UANG ASURANSI
	$data8=$data->val($i, 8);		//MULAI ASURANSI (TGL)
	$data9=$data->val($i, 9);		//MULAI ASURANSI (BLN)
	$data10=$data->val($i, 10);		//MULAI ASURANSI (THN)
	$data11=$data->val($i, 11);		//MASA ASURANSI
	$data12=$data->val($i, 12);		//EXT. PREMI
/* FORMAT SEBELUMNYA REGIONAL, AREA DAN CABANG
	$data13=$data->val($i, 13);		//REGIOALL
	$data14=$data->val($i, 14);		//AREA
	$data15=$data->val($i, 15);		//CABANG
*/
	$data13=$data->val($i, 13);		//CABANG
	$data14=$data->val($i, 14);		//PRODUK
	$data15=$data->val($i, 15);		//KETERANGAN
	$data16=$data->val($i, 16);		//MPP

	//VALIDASI DATA UPLOAD//
	if ($data2==""){ $error ='<font color="red">error</font>'; $dataexcel2=$error;}else{ $dataexcel2=$data2;}
	if ($data3==""){ $error ='<font color="red">error</font>'; $dataexcel3=$error;}else{ $dataexcel3=$data3;}
	if ($data4==""){ $error ='<font color="red">error</font>'; $dataexcel4=$error;}else{ $dataexcel4=$data4;}
	if ($data5==""){ $error ='<font color="red">error</font>'; $dataexcel5=$error;}else{ $dataexcel5=$data5;}
	if ($data6==""){ $error ='<font color="red">error</font>'; $dataexcel6=$error;}else{ $dataexcel6=$data6;}

	//$titikpos = strpos($data7, ".");
	//$komapos = strpos($data7, ",");
	if ($data7==""){ $error ='<font color="red">error</font>'; $dataexcel7=$error;}
	//elseif (strpos($data7, ".")) { $error ='<font color="red">error</font>'; $dataexcel7=$error;}	//VALIDASI NILAI ASURANSI DENGAN TITIK
	//elseif (strpos($data7, ",")) { $error ='<font color="red">error</font>'; $dataexcel7=$error;}	//VALIDASI NILAI ASURANSI DENGAN KOMA
	//elseif (strpos($data7, "*")) { $error ='<font color="red">error</font>'; $dataexcel7=$error;}	//VALIDASI NILAI ASURANSI DENGAN KOMA
	//else{ $dataexcel7=duit($data7);	$dataexcel7med = $data7;	}
	else{
		$asting = array(" ", ",", ".", "*");
		$replace = array('', '', '', '');

		$malestr = str_replace($asting, $replace, $data7);
		//echo $malestr;
		$dataexcel7=duit($malestr);	$dataexcel7med = $malestr;
	}


	if ($data8==""){ $error ='<font color="red">error</font>'; $dataexcel8=$error;}else{ $dataexcel8=$data8;}
	if ($data9==""){ $error ='<font color="red">error</font>'; $dataexcel9=$error;}else{ $dataexcel9=$data9;}
	if ($data10==""){ $error ='<font color="red">error</font>'; $dataexcel10=$error;}else{ $dataexcel10=$data10;}
	if ($data11==""){ $error ='<font color="red">error</font>'; $dataexcel11=$error;}else{ $dataexcel11=$data11;}
	if ($data13==""){ $error ='<font color="red">error</font>'; $dataexcel13=$error;}else{ $dataexcel13=$data13;}
	if ($data14==""){ $error ='<font color="red">error</font>'; $dataexcel14=$error;}else{ $dataexcel14=$data14;}
	//if ($data15==""){ $error ='<font color="red">error</font>'; $dataexcel15=$error;}else{ $dataexcel15=$data15;}

	if(!is_numeric($data4)){$error ='<font color="red">error</font>'; $dataexcel4=$error;}else{ $dataexcel4=$data4;}		//VALIDASI HARI
	if(strlen($data4 > 31 )){$error ='<font color="red">error</font>'; $dataexcel4=$error;}else{ $dataexcel4=$data4;}		//VALIDASI HARI

	if(!is_numeric($data5)){$error ='<font color="red">error</font>'; $dataexcel5=$error;}else{ $dataexcel5=$data5;}		//VALIDASI BULAN
	if(strlen($data5 > 12 )){$error ='<font color="red">error</font>'; $dataexcel5=$error;}else{ $dataexcel5=$data5;}		//VALIDASI BULAN

	if(!is_numeric($data6)){$error ='<font color="red">error</font>'; $dataexcel6=$error;}else{ $dataexcel6=$data6;}		//VALIDASI TAHUN
	if(strlen($data6 > $dateY )){$error ='<font color="red">error</font>'; $dataexcel6=$error;}else{ $dataexcel6=$data6;}		//VALIDASI TAHUN

	if(!is_numeric($data8)){$error ='<font color="red">error</font>'; $dataexcel8=$error;}else{ $dataexcel8=$data8;}		//VALIDASI HARI
	if(strlen($data8 > 31 )){$error ='<font color="red">error</font>'; $dataexcel8=$error;}else{ $dataexcel8=$data8;}		//VALIDASI HARI

	if(!is_numeric($data9)){$error ='<font color="red">error</font>'; $dataexcel9=$error;}else{ $dataexcel9=$data9;}		//VALIDASI BULAN
	if(strlen($data9 > 12 )){$error ='<font color="red">error</font>'; $dataexcel9=$error;}else{ $dataexcel9=$data9;}		//VALIDASI BULAN

	if(!is_numeric($data10)){$error ='<font color="red">error</font>'; $dataexcel10=$error;}else{ $dataexcel10=$data10;}		//VALIDASI TAHUN
	if(strlen($data10 > $dateY )){$error ='<font color="red">error</font>'; $dataexcel10=$error;}else{ $dataexcel10=$data10;}		//VALIDASI TAHUN


	//FORMAT TERNOR DLM BULAN DIBAGI 12
	//$_mettenor = $data11 * 12;	TENOR BULAN
	$_mettenor = $data11;
/*
	$cekdatareg = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE id_cost="'.$q['id_cost'].'" AND name="'.$data13.'"'));			//VALIDASI REGIONAL
	if ($data13 != $cekdatareg['name']) {$error ='<font color="red">error</font>';	$dataexcel13=$error;	}else{	$dataexcel13=$data13;	}			//VALIDASI REGIONAL

	$cekdataarea = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_area WHERE id_cost="'.$q['id_cost'].'" AND name="'.$data14.'"'));				//VALIDASI AREA
	if ($data14 != $cekdataarea['name']) {$error ='<font color="red">error</font>';	$dataexcel14=$error;	}else{	$dataexcel14=$data14;	}			//VALIDASI AREA

	$cekdatacab = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id_cost="'.$q['id_cost'].'" AND name="'.$data15.'"'));			//VALIDASI CABANG
	if ($data15 != $cekdatacab['name']) {$error ='<font color="red">error</font>';	$dataexcel15=$error;	}else{	$dataexcel15=$data15;	}			//VALIDASI CABANG
*/
	$cekdatacab = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id_cost="'.$q['id_cost'].'" AND name="'.$data13.'"'));			//VALIDASI CABANG
	if ($data13 != $cekdatacab['name']) {$error ='<font color="red" title="Data cabang belum terdaftar">error</font>';	$dataexcel13=$error;	}else{	$dataexcel13=$data13;	}			//VALIDASI CABANG
	//VALIDASI DATA UPLOAD//

	$cekdataspk = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak WHERE id_cost="'.$q['id_cost'].'" AND id_polis="'.$q['id_polis'].'" AND spak="'.$data2.'" AND status="Aktif"'));

	//$cekdataspknya = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak WHERE id_cost="'.$q['id_cost'].'" AND spak="'.$data2.'" AND status="Aktif"'));
	if ($cekdataspk['spak'] != $data2) {$error ='<font color="red">error</font>'; $dataexcel2=$error;}else{ $dataexcel2=$data2;}

	//$exl = $database->doQuery('INSERT IGNORE fu_ajk_peserta_tempf SET id_cost="'.$fu['id'].'",
if ($data4 <= 9) { $data4_ = '0'.$data4;	}else{	$data4_ = $data4;}
if ($data5 <= 9) { $data5_ = '0'.$data5;	}else{	$data5_ = $data5;}
	$datatgllahirnya = $data6.'-'.$data5_.'-'.$data4_;

if ($data8 <= 9) { $data8_ = '0'.$data8;	}else{	$data8_ = $data8;}
if ($data9 <= 9) { $data9_ = '0'.$data9;	}else{	$data9_ = $data9;}
	$datatglkreditnya = $data10.'-'.$data9_.'-'.$data8_;

	$cekDeklarasiSPK = mysql_fetch_array($database->doQuery('SELECT id_cost, id_polis, spaj, nama FROM fu_ajk_peserta WHERE id_cost="'.$q['id_cost'].'" AND id_polis="'.$q['id_polis'].'" AND spaj="'.$cekdataspk['spak'].'" AND status_aktif ="Inforce" AND status_peserta IS NULL AND del IS NULL'));
	//echo $cekDeklarasiSPK['nama'].'<br />';
	if ($cekDeklarasiSPK['nama']) {
		$error ='<font color="red"><a title="data sudah pernah diupload">error</a></font>'; $dataexcel3=$error;
	}else{
	$cekdataspknama = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_form WHERE idcost="'.$q['id_cost'].'" AND idspk="'.$cekdataspk['id'].'" AND del IS NULL'));
	if ($cekdataspknama['nama'] != $data3) {$error ='<font color="red">error nama</font>'; $dataexcel3=$error;}
	elseif ($cekdataspknama['nama'] == $data3 AND $cekdataspknama['dob']!=$datatgllahirnya) {$error ='<font color="red" title="Tanggal lahir tidak sama dengan tanggal lahir form SPK">error tgl lahir</font>'; $dataexcel3=$error;}
	elseif ($cekdataspknama['nama'] == $data3 AND $cekdataspknama['dob']==$datatgllahirnya AND $cekdataspknama['idspk']!=$cekdataspk['id']) {$error ='<font color="red" title="Nomor SPK tidak sama dengan nomor form SPK">error nomor SPK</font>'; $dataexcel3=$error;}
	else{ $dataexcel3=$data3;}
	}

	//CEK RELASI WILAYAH
	//echo $q['cabang'].'<br />';
if(is_numeric($cekdataspknama['cabang'])){
	$TabCabang_ = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id="'.$cekdataspknama['cabang'].'"'));
	$TabCabang__ = $TabCabang_['name'];
}else{
	$TabCabang__ = $data13;
}
	$cekdatawilayah = mysql_fetch_array($database->doQuery('SELECT * FROM v_wilayah WHERE id_cost="'.$q['id_cost'].'" AND cabang="'.$TabCabang__.'"'));
	if ($data13=="") {	$error ='<font color="red" title="Data cabang tidak boleh kosong">error</font>'; $dataexcel13=$error;
	}else{ if ($cekdatawilayah['cabang']!=$data13) {$error ='<font color="red" title="Data cabang belum terdaftar">error</font>'; $dataexcel13=$error;}else{ $dataexcel13=$data13;}
	}

/* KOLOM SEBELUMNYA REGIONAL, AREA DAN CABANG 281215
	if ($data14=="") {	$error ='<font color="red">error</font>'; $dataexcel14=$error;
	}else{ if ($cekdatawilayah['area']!=$data14) {$error ='<font color="red">error</font>'; $dataexcel14=$error;}else{ $dataexcel14=$data14;}
	}
	if ($data15=="") {	$error ='<font color="red">error</font>'; $dataexcel15=$error;
	}else{ if ($cekdatawilayah['cabang']!=$data15) {$error ='<font color="red">error</font>'; $dataexcel15=$error;}else{ $dataexcel15=$data15;}
	}
*/
	//CEK RELASI WILAYAH

	$mets = datediff($datatglkreditnya, $datatgllahirnya);
	$metTgl = explode(",",$mets);
	//	echo $mets['years'].' Tahun '.$mets['months'].' Bulan '.$mets['days'].' Hari';
	if ($metTgl[1] >= 6 ) {	$umur = $metTgl[0] + 1;	}else{	$umur = $metTgl[0];	}
	//echo $umur;
	if ($metTgl[1] == 5) {	$sisahari = 30 - $metTgl[2];	$sisathn = $metTgl[0] + 1; $blnnnya ='Dalam '.$sisahari.' hari usia akan bertambah menjadi '.$sisathn.'';	}else{	$blnnnya ='';	}
	//echo $mets['months'].' '.$mets['days'].' '.$umur.' | '.$blnnnya.'<br />';

	//VALIDASI TABEL MEDICAL STATUS MEDIK
	if ($fupolis['age_deviasi']=="Y") {
		$medik = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_medical WHERE id_cost="'.$q['id_cost'].'" AND  id_polis="'.$fupolis['id'].'" AND '.$umur.' BETWEEN age_from AND age_to AND '.$dataexcel7med.' BETWEEN si_from AND si_to'));
		$status_medik =$medik['type_medical'];
		if ($status_medik=="NM" OR $status_medik=="FCL" OR $status_medik=="SPD" OR $status_medik=="SPK" OR $status_medik=="SKKT")
		{	$status_pesertanya = "Approve";	}else{	$status_pesertanya = "Pending";	}
		$cekratepolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$q['id_cost'].'" AND id_polis="'.$q['id_polis'].'" AND usia="'.$umur.'" AND type="F" AND tenor="'.$_mettenor.'"'));	//VALIDASI CEK TENOR DENGAN SETTING RATE POLIS
		if ($_mettenor != $cekratepolis['tenor']) {$error ='<font color="red" title="Tenor plafond tidak sama dengan tenor form SPK">error</font>';	$dataexcel11=$error;	}else{	$dataexcel11=$data11;	}			//VALIDASI CEK TENOR DENGAN SETTING RATE POLIS

	}else{
		$medik = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_medical WHERE id_cost="'.$q['id_cost'].'" AND  id_polis="'.$fupolis['id'].'" AND '.$umur.' BETWEEN age_from AND age_to AND '.$dataexcel7med.' BETWEEN si_from AND si_to'));
		$status_medik =$medik['type_medical'];
		if ($status_medik=="NM" OR $status_medik=="FCL" OR $status_medik=="SPD" OR $status_medik=="SPK" OR $status_medik=="SKKT")
		{	$status_pesertanya = "Approve";	}else{	$status_pesertanya = "Pending";	}
		$cekratepolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$q['id_cost'].'" AND id_polis="'.$q['id_polis'].'" AND type="F" AND tenor="'.$_mettenor.'"'));	//VALIDASI CEK TENOR DENGAN SETTING RATE POLIS
		if ($_mettenor != $cekratepolis['tenor']) {$error ='<font color="red" title="Tenor plafond tidak sama dengan tenor form SPK">error</font>';	$dataexcel11=$error;	}else{	$dataexcel11=$data11;	}			//VALIDASI CEK TENOR DENGAN SETTING RATE POLIS
	}
	if (!$medik) {$error ='<font color="red"><a title="usia melewati batas maksimum polis">error</a></font>'; $dataexcel16=$error;}else{ $dataexcel16=$umur;}
	//VALIDASI TABEL MEDICAL STATUS MEDIK

	//CEK PRODUK MPP
	if ($fupolis['mpptype']=="Y") {
		if ($data16==""){ $error ='<font color="red" title="Masukan jumlah bulan MPP">error</font>'; $dataexcel18=$error;}
		else{
			if ($data16 > $fupolis['mppbln']) {	$error ='<font color="red" title="Jumlah bulan MPP melewati batas bulan setup produk">error</font>'; $dataexcel18=$error;	}
			else{	$dataexcel18=$data16;	}
		}
	}else{
		if ($data16!=""){ $error ='<font color="red" title="Data debitur bukan Masa Pra Pensiun">error</font>'; $dataexcel18=$error;}
		else{
		$dataexcel18=$data16;
		}
	}
	//CEK PRODUK MPP

if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		<td align="center">'.$data1.'</td>
		<td align="center">'.$dataexcel2.'</td>
		<td>'.strtoupper($dataexcel3).'<br /><font color="blue">'.$blnnnya.'</font></td>
		<td align="center">'.$dataexcel4.'</td>
		<td align="center">'.$dataexcel5.'</td>
		<td align="center">'.$dataexcel6.'</td>
		<td align="center">'.$dataexcel16.'</td>
		<td align="right">'.$dataexcel7.'</td>
		<td align="center">'.$dataexcel8.'</td>
		<td align="center">'.$dataexcel9.'</td>
		<td align="center">'.$dataexcel10.'</td>
		<td align="center">'.$dataexcel11.'</td>
		<td align="center">'.$cekdataspk['ext_premi'].'</td>
		<td align="center">'.$dataexcel18.'</td>
		<td align="right">'.$dataexcel13.'</td>
		<td align="right">'.$data15.'</td>
	</tr>';
$met = $database->doQuery('INSERT INTO fu_ajk_peserta_tempf SET id_cost="'.$fu['id'].'",
																  id_polis="'.$fupolis['id'].'",
																  namafile="'.$_FILES['userfile']['name'].'",
																  no_urut="'.$data1.'",
																  spaj="'.$data2.'",
																  type_data="SPK",
																  nama_mitra="",
																  nama="'.$data3.'",
																  gender="",
																  tgl_lahir="'.$datatgllahirnya.'",
																  usia="'.$umur.'",
																  kredit_tgl="'.$datatglkreditnya.'",
																  kredit_jumlah="'.$dataexcel7med.'",
																  kredit_tenor="'.$data11.'",
																  kredit_akhir="",
																  premi="",
																  disc_premi="",
																  bunga="",
																  biaya_adm="",
																  ext_premi="'.$data12.'",
																  totalpremi="",
																  badant="",
																  badanb="",
																  status_medik="",
																  status_bayar="0",
																  status_aktif="Upload",
																  ket="'.$data15.'",
																  mppbln="'.$data16.'",
																  regional="'.$cekdatawilayah['regional'].'",
																  area="'.$cekdatawilayah['area'].'",
																  cabang="'.$data13.'",
																  input_by ="'.$_SESSION['nm_user'].'",
															      input_time ="'.$futgl.'"');
}
if ($error) {	echo '<tr><td colspan="27" align="center"><font color="red"><blink>Silahkan lengkapi kolom yang error !!!</blink><br /><a href="ajk_uplspak.php?el=cancelspk&fileclient='.$_FILES['userfile']['name'].'">Back</a></font></td></tr>';	}
else		{	echo '<tr><td colspan="27" align="center"><a title="Approve data upload peserta SPK" href="ajk_uplspak.php?el=approveflspk&nmfile='.$_FILES['userfile']['name'].'&dateupl='.$futgl.'&idc='.$fu['id'].'" onClick="if(confirm(\'Apakah anda sudah yakin dengan semua data peserta SPK ini ?\')){return true;}{return false;}"><img src="image/save.png" border="0" width="50"></a>
	 					   &nbsp; &nbsp; <a title="Batalkan data upload peserta SPK" href="ajk_uplspak.php?el=cancelspk&fileclient='.$_FILES['userfile']['name'].'"><img src="image/deleted.png" border="0" width="50"></a></td></tr>';	}
	echo '</table></form>';
}
	;
	break;

case "cancelspk":
	$cha = $database->doQuery('DELETE FROM fu_ajk_peserta_tempf WHERE namafile="'.$_REQUEST['fileclient'].'"');
	header("location:ajk_uplspak.php?el=fl_spk");
		;
		break;

case "approveflspk":
$met_appr = $database->doQuery('SELECT * FROM fu_ajk_peserta_tempf WHERE id_cost="'.$_REQUEST['idc'].'" AND namafile="'.$_REQUEST['nmfile'].'" AND input_time="'.$_REQUEST['dateupl'].'"');

$message .= '<table width="100%" broder="0" cellpadding="3" cellspacing="1">
				 <tr bgcolor="#add8e6"><td width="1%">NO</td>
			 	 <td align="center" width="5%">SPAJ</td>
			 	 <td align="center">NAMA</td>
			 	 <td align="center" width="5%">D O B</td>
			 	 <td align="center" width="8%">TGL KREDIT</td>
			 	 <td align="center" width="10%">U P</td>
			 	 <td align="center" width="5%">TENOR</td>
			 	 <td align="center" width="10%">CABANG</td>
				 </tr>';
while ($mamet_appr = mysql_fetch_array($met_appr)) {
if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
$message .='<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
				<td align="center">'.++$no.'</td>
				<td align="center">'.$mamet_appr['spaj'].'</td>
				<td>'.$mamet_appr['nama'].'</td>
				<td align="center">'._convertDate($mamet_appr['tgl_lahir']).'</td>
				<td align="center">'._convertDate($mamet_appr['kredit_tgl']).'</td>
				<td align="right">'.duit($mamet_appr['kredit_jumlah']).'</td>
				<td align="center">'.$mamet_appr['kredit_tenor'].'</td>
				<td>'.$mamet_appr['cabang'].'</td>
		  		</tr>';
}
	$message .='</table>';
	echo $message;

	$mail = new PHPMailer;
	$mail->IsSMTP();
	$mail->Host = SMTP_HOST; //Hostname of the mail server
	$mail->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
	$mail->Username = SMTP_UNAME; //Username for SMTP authentication any valid email created in your domain
	$mail->Password = SMTP_PWORD; //Password for SMTP authentication
	$mail->AddReplyTo = SMTP_REPLYMAIL; //reply-to address
	$mail->debug = 1;
	$mail->SMTPSecure = "ssl";
	$mail->SetFrom ($q['email'], $q['nm_lengkap']); //From address of the mail
	$mail->Subject = "AJK-Online - UPLOAD DATA PSERTA BARU SPK"; //Subject od your mail
	//EMAIL PENERIMA  SPV CLIENT
	$mailsupervisorajk = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="'.$q['id_cost'].'" AND id_polis="'.$q['id_polis'].'" AND level="'.$q['level'].'" AND status=""');
	while ($_mailsupervisorajk = mysql_fetch_array($mailsupervisorajk)) {
		$mail->AddAddress($_mailsupervisorajk['email'], $_mailsupervisorajk['nm_lengkap']); //To address who will receive this email
	}
	//EMAIL PENERIMA  SPV CLIENT

	$mail->AddCC("penting_ga@hotmail.com, sysdev@kode.web.id, arief@arief.kurniawan.com, gunarso@adonai.co.id");
	//$mail->AddAddress($mailsupervisorajk['email'], $mailsupervisorajk['nm_lengkap']); //To address who will receive this email
	//$mail->AddCC($approvemail);
	$mail->MsgHTML('<table><tr><th>Data peserta baru SPK telah diinput oleh <b>'.$_SESSION['nm_user'].' selaku staff AJK-Online pada tanggal '.$futgl.'</tr></table>'.$message); //Put your body of the message you can place html code here
	$send = $mail->Send(); //Send the mails
	//echo $mail;
echo '<center>Data Peserta Baru SPK telah di upload oleh <b>'.$_SESSION['nm_user'].'</b>, tunggu konfirmasi selanjutnya dari Supervisor untuk Approve data yang telah di upload.<br />
		  <a href="ajk_uplspak.php?el=fl_spk">Kembali Ke Halaman Utama</a></center>';
		;
		break;

case "fl_spk":
echo '<table border="0" cellpadding="1" cellspacing="0" width="100%"><tr><th width="100%" align="left">Modul Upload Data Kepesertaan SPK</font></th></tr></table>';
	$metcost = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$q['id_cost'].'"'));
	$metprod = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$q['id_polis'].'"'));
	echo '<form name="f1" method="post" enctype="multipart/form-data" action="">
		  <input type="hidden" name="id_cost" value="'.$metcost['id'].'">
		  <table border="0" cellpadding="1" cellspacing="0" width="50%" align="center">
	      <tr><td width="40%">Nama Perusahaan</td><td> : '.$metcost['name'].'</td></tr>
		  <tr><td>Produk</td><td>: '.$metprod['nmproduk'].'</td></tr>
		  <tr><td>Silakan Pilih File Excel </td><td>: '.$bataskolom.' <input name="userfile" type="file" size="50" onchange="checkfile(this);" ></td></tr>
	  	  <tr><td>Batas Akhir Baris </td><td>: <input type="text" name="bataskolom" value="'.$bataskolom.'" size="1"></td></tr>
	  	  <tr><td align="center"colspan="2"><input type="hidden" name="el" value="parsingspk"><input name="upload" type="submit" value="Import"></td></tr>
	  	  </table></form>';
	;
	break;

	default:
echo '<table border="0" cellpadding="1" cellspacing="0" width="100%"><tr><th width="100%" align="left">Modul Upload Data SPK</font></th></tr></table>';
		$userProduk = $database->doQuery('SELECT DISTINCT * FROM fu_ajk_polis WHERE id_cost="'.$q['id_cost'].'" AND typeproduk="SPK" AND del IS NULL ORDER BY nmproduk ASC');
		$_userCabang .= '<select id="id_cost" name="userProduk"> <option value="">--- Pilih ---</option>';
while($userProduk_ = mysql_fetch_array($userProduk)) {
	$_userCabang .= '<option value="'.$userProduk_['id'].'"'._selected($_REQUEST['userProduk'], $userProduk_['id']).'>'.$userProduk_['nmproduk'].'</option>';
}
		$_userCabang .= '</select>';
		$metcost = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$q['id_cost'].'"'));
		$metprod = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$q['id_polis'].'"'));
		echo '<form name="f1" method="post" enctype="multipart/form-data" action="">
			  <input type="hidden" name="id_cost" value="'.$metcost['id'].'">
			  <table border="0" cellpadding="1" cellspacing="0" width="100%" align="center">
		      <tr><td width="35%" align="right">Nama Perusahaan</td><td colspan="4"> : '.$metcost['name'].'</td></tr>
			  <!--<tr><td align="right">Produk</td><td colspan="4">: '.$metprod['nmproduk'].'</td></tr>-->
			  <tr><td align="right">Produk SPK</td><td colspan="4">: '.$_userCabang.'</td></tr>
			  <tr><td valign="top" align="right">Nomor SPK</td><td width="30%" valign="top">: <input type="text" name="no_spk[]" value="'.$_REQUEST['no_spk'].'" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')" size="10"> <input name="userfile[]" type="file" size="50" onchange="checkfile(this);" ></td><td align="right">Photo</td><td><input name="photospk[]" type="file" size="50" onchange="checkfile(this);"></td></tr>
			  <tr><td valign="top" align="right">Nomor SPK</td><td valign="top">: <input type="text" name="no_spk[]" value="'.$_REQUEST['no_spk'].'" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')" size="10"> <input name="userfile[]" type="file" size="50" onchange="checkfile(this);" ></td><td align="right">Photo</td><td><input name="photospk[]" type="file" size="50" onchange="checkfile(this);"></td></tr>
			  <tr><td valign="top" align="right">Nomor SPK</td><td valign="top">: <input type="text" name="no_spk[]" value="'.$_REQUEST['no_spk'].'" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')" size="10"> <input name="userfile[]" type="file" size="50" onchange="checkfile(this);" ></td><td align="right">Photo</td><td><input name="photospk[]" type="file" size="50" onchange="checkfile(this);"></td></tr>
			  <tr><td valign="top" align="right">Nomor SPK</td><td valign="top">: <input type="text" name="no_spk[]" value="'.$_REQUEST['no_spk'].'" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')" size="10"> <input name="userfile[]" type="file" size="50" onchange="checkfile(this);" ></td><td align="right">Photo</td><td><input name="photospk[]" type="file" size="50" onchange="checkfile(this);"></td></tr>
			  <tr><td valign="top" align="right">Nomor SPK</td><td valign="top">: <input type="text" name="no_spk[]" value="'.$_REQUEST['no_spk'].'" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')" size="10"> <input name="userfile[]" type="file" size="50" onchange="checkfile(this);" ></td><td align="right">Photo</td><td><input name="photospk[]" type="file" size="50" onchange="checkfile(this);"></td></tr>
			  <tr><td align="center" colspan="4"><input type="hidden" name="el" value="upload_spk"><input type="submit" name="upload_rate" value="Upload"></td></tr>
			  </table>
			  </form>';
		;
} // switch
?>
