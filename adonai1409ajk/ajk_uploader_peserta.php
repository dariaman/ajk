<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("../includes/functions.php");
connect();

if (session_is_registered('nm_user')) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));
	$qsescost=mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$q['id_cost'].'"'));
}
switch ($_REQUEST['r']) {
	case "cancell":
$cha = $database->doQuery('DELETE FROM fu_ajk_peserta_tempf WHERE namafile="'.$_REQUEST['fileclient'].'"');
header("location:ajk_uploader_peserta.php");
		;
		break;

	case "deldata":
$met = $database->doQuery('DELETE FROM fu_ajk_peserta_tempf WHERE id_cost="'.$_REQUEST['id_cost'].'" AND
																  id_polis="'.$_REQUEST['id_polis'].'" AND
																  namafile="'.$_REQUEST['namafile'].'" AND
																  no_urut="'.$_REQUEST['no_urut'].'" AND
																  spaj="'.$_REQUEST['spaj'].'" AND
																  nama="'.$_REQUEST['nama'].'" AND
																  kredit_jumlah="'.$_REQUEST['kredit_jumlah'].'"');
if ($_REQUEST['cl']=="claim") {	header("location:ajk_uploader_peserta.php?r=viewallclaim");	}
else	{	header("location:ajk_uploader_peserta.php?r=viewall");	}
	;
	break;

	case "approve":
//echo('SELECT * FROM fu_ajk_costumer WHERE name="'.$qsescost['name'].'"');
//if ($_REQUEST['val']=="pclaim")
if (!$_REQUEST['nama']) {
echo '<center><font color=red><blink>Tidak ada data yang di pilih, silahkan ceklist data yang akan di Approve. !</blink></font><br/>
	  <a href="ajk_uploader_peserta.php?r=viewall">Kembali Ke Halaman Approve Peserta</a></center>';
}else{
foreach($_REQUEST['nama'] as $k => $val){
	$vall = explode("-met-", $val);		//EXPLODE DATA BERDASARKAN CHEKLIST//
$r = $database->doQuery('SELECT * FROM fu_ajk_peserta_tempf WHERE id_cost="'.$vall[0].'" AND id_polis="'.$vall[1].'" AND nama="'.$vall[2].'" AND tgl_lahir="'.$vall[3].'" AND kredit_jumlah="'.$vall[4].'" AND status_aktif="Upload"');
while ($rr = mysql_fetch_array($r)) {
//BIAYA POLIS ADMIN
$admpolis = mysql_fetch_array($database->doQuery('SELECT id_cost, adminfee, day_kredit, discount, singlerate FROM fu_ajk_polis WHERE id_cost="'.$rr['id_cost'].'" AND id="'.$rr['id_polis'].'"'));

	$tgl_akhir_kredit = date('Y-m-d',strtotime($rr['kredit_tgl']."+".$rr['kredit_tenor']." Month"."-".$admpolis['day_kredit']." day"));	//KREDIT AKHIR
	$umur = ceil(((strtotime($rr['kredit_tgl']) - strtotime($rr['tgl_lahir']."+6 month")) / (60*60*24*365.2425)));	// FORMULA USIA

//MENENTUKAN PERHITUNGAN PREMI BERDASARKAN SINGLE RATE PADA POLIS
	if ($admpolis['singlerate']=="T") {
		$cekrate = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$rr['id_cost'].'" AND id_polis="'.$rr['id_polis'].'" AND tenor="'.$rr['kredit_tenor'].'" AND status="baru"'));		// RATE PREMI
	}else{
		$mettenornya = $rr['kredit_tenor'] / 12;
		$cekrate = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$rr['id_cost'].'" AND id_polis="'.$rr['id_polis'].'" AND usia="'.$umur.'" AND tenor="'.$mettenornya.'" AND status="baru"'));		// RATE PREMI
	}
	//MENENTUKAN PERHITUNGAN PREMI BERDASARKAN SINGLE RATE PADA POLIS
	$premi = $rr['kredit_jumlah'] * $cekrate['rate'] / 1000;
	$diskonpremi = $premi * $admpolis['discount'] /100;			//diskon premi
	$tpremi = $premi - $diskonpremi;							//totalpremi

	$mettotal = $tpremi + $extrapremi + $admpolis['adminfee'];															//TOTAL

$formattgl = explode("/", $rr['kredit_tgl']);		$formattgl1 = $formattgl[2].'-'.$formattgl[1].'-'.$formattgl[0];		// SET FORMAT TANGGAL
$cekpesertaID = mysql_fetch_array($database->doQuery('SELECT id FROM fu_ajk_peserta ORDER BY id DESC'));					// SET ID PESERTA
$idnya = 10000000000 + $cekpesertaID['id'] + 1; $idnya2 = substr($idnya, 1);													// SET ID PESERTA
$mameto = $database->doQuery('INSERT INTO fu_ajk_peserta SET id_cost="'.$rr['id_cost'].'",
															 id_polis="'.$rr['id_polis'].'",
															 namafile="'.$rr['namafile'].'",
															 no_urut="'.$rr['no_urut'].'",
															 spaj="'.$rr['spaj'].'",
															 type_data="'.$rr['type_data'].'",
															 id_peserta="'.$idnya.'",
															 nama_mitra="'.$rr['nama_mitra'].'",
															 nama="'.$rr['nama'].'",
															 gender="'.$rr['gender'].'",
															 tgl_lahir="'.$rr['tgl_lahir'].'",
															 usia="'.$umur.'",
															 kredit_tgl="'.$rr['kredit_tgl'].'",
															 kredit_jumlah="'.$rr['kredit_jumlah'].'",
															 kredit_tenor="'.$rr['kredit_tenor'].'",
															 kredit_akhir="'.$tgl_akhir_kredit.'",
															 premi="'.$premi.'",
															 disc_premi="'.$diskonpremi.'",
															 bunga="",
															 biaya_adm="'.$admpolis['adminfee'].'",
															 ext_premi="'.$rr['ext_premi'].'",
															 totalpremi="'.$mettotal.'",
															 badant="",
															 badanb="",
															 status_medik="'.$rr['status_medik'].'",
															 status_bayar="0",
															 status_aktif="Approve",
															 regional="'.$rr['regional'].'",
															 area="'.$rr['area'].'",
															 cabang="'.$rr['cabang'].'",
															 input_by ="'.$rr['input_by'].'- '.$q['nm_user'].'",
															 input_time ="'.$rr['input_time'].'"');

}
$metdel = $database->doQuery('DELETE FROM fu_ajk_peserta_tempf WHERE id_cost="'.$vall[0].'" AND id_polis="'.$vall[1].'" AND nama="'.$vall[2].'" AND tgl_lahir="'.$vall[3].'" AND kredit_jumlah="'.$vall[4].'" AND status_aktif ="Upload"');
}
$pecahtgl = explode(" ", $futgl);	$pecahlagi = explode("-", $pecahtgl[0]);	$tglnya = $pecahlagi[2].'-'.$pecahlagi[1].'-'.$pecahlagi[0].' '.$pecahtgl[1];				//PECAH TANGGAL
//$Rmail = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="'.$vall[0].'" AND  wilayah="'.$q['wilayah'].'" AND email !=""');
//echo('SELECT * FROM pengguna WHERE id_cost="'.$vall[0].'" AND  wilayah="'.$q['wilayah'].'" AND email !=""');
//while ($eRmail = mysql_fetch_array($Rmail)) {	$metMail .=$eRmail['email'].', ';	}
//echo $metMail.'<br /><br />';

/* EMAIL MODEL PHPMAILER
$to = $metMail.''.$q['email'].', '."sumiyanto@relife.co.id, pajar@relife.co.id, arief.kurniawan@relife.co.id" ;
$subject = 'AJKOnline - APPROVE PESERTA BARU RELIFE AJK ONLINE';
$message = '<html><head><title>Data peserta baru sudah di Approve oleh '.$q['nm_lengkap'].'</title></head>
			<body>
			<table><tr><th>Data peserta baru sudah di Approve oleh <b>'.$_SESSION['nm_user'].'</b> pada tanggal '.$tglnya.'</tr></table>
			</body></html>';
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
// Additional headers
$headers .= 'From: '.$q['email'].'' . "\r\n";
$headers .= 'Cc:  rahmad@relife.co.id' . "\r\n";
//	$headers .= 'Bcc: k@example.com' . "\r\n";
mail($to, $subject, $message, $headers);
*/

/* SMTP MAIL */
$mail = new PHPMailer; // call the class
$mail->IsSMTP();
$mail->Host = SMTP_HOST; //Hostname of the mail server
$mail->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
$mail->Username = SMTP_UNAME; //Username for SMTP authentication any valid email created in your domain
$mail->Password = SMTP_PWORD; //Password for SMTP authentication
$mail->AddReplyTo = SMTP_REPLYMAIL; //reply-to address
$mail->SetFrom ($q['email'], $q['nm_lengkap']); //From address of the mail
$mail->Subject = "AJKOnline - APPROVE PESERTA BARU AJK ONLINE"; //Subject od your mail
//EMAIL PENERIMA KANTOR U/W
$mailsupervisorajk = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="" AND status="UNDERWRITING"');
while ($_mailsupervisorajk = mysql_fetch_array($mailsupervisorajk)) {
$mail->AddAddress($_mailsupervisorajk['email'], $_mailsupervisorajk['nm_lengkap']); //To address who will receive this email
}
//EMAIL PENERIMA KANTOR U/W
//EMAIL PENERIMA CLIENT

$mailsupervisorclient = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="'.$vall[0].'" AND id_polis="'.$vall[1].'" AND email !=""');
while ($_mailsupervisorclient = mysql_fetch_array($mailsupervisorclient)) {
$mail->AddAddress($_mailsupervisorclient['email'], $_mailsupervisorclient['nm_lengkap']); //To address who will receive this email
}
//EMAIL PENERIMA CLIENT

$mail->AddCC("kepodank@gmail.com");
$mail->MsgHTML('<table><tr><th>Data peserta baru telah di approve oleh <b>'.$_SESSION['nm_user'].' selaku Supervisor AJK-Online pada tanggal '.$futgl.'</tr></table>'.$message); //Put your body of the message you can place html code here
$send = $mail->Send(); //Send the mails

echo '<center>Approve oleh <b>'.$_SESSION['nm_user'].'</b> telah berhasil, segera dibuat pencetakan nomor DN.<br /> <a href="ajk_dn.php">Kembali Ke Halaman Utama</a></center>';

}
			;
		break;

	case "approveuser":
$met_appr = $database->doQuery('SELECT * FROM fu_ajk_peserta_tempf WHERE id_cost="'.$_REQUEST['idc'].'" AND id_polis="'.$_REQUEST['idp'].'" AND namafile="'.$_REQUEST['nmfile'].'" AND input_time="'.$_REQUEST['dateupl'].'"');
$message .= '<table width="100%" broder="0" cellpadding="3" cellspacing="1">
			 <tr bgcolor="#add8e6">
			 <th width="1%">No</th>
			 <th width="5%">Nama Mitra</th>
			 <th width="8%">Regional</th>
			 <th width="8%">Area</th>
			 <th width="8%">Cabang</th>
			 <th>Nama Tertanggung</th>
			 <th width="5%">Tanggal Lahir</th>
			 <th width="5%">Uang Asuransi</th>
			 <th width="5%">Mulai Asuransi</th>
			 <th width="1%">Tenor<br />(jumlah bulan)</th>
			 <th width="1%">Usia</th>
			 <th width="5%">Tarif Premi</th>
			 <th width="5%">Premi Standar</th>
			 <th width="5%">Ext.Premi</th>
			 <th width="5%">Premi Sekaligus</th>
			 <th width="5%">Medical</th>
			 </tr>';
while ($mamet_appr = mysql_fetch_array($met_appr)) {
if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
	$cekratepolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$mamet_appr['id_cost'].'" AND id_polis="'.$mamet_appr['id_polis'].'" AND tenor="'.$mamet_appr['kredit_tenor'].'" AND status="baru"'));	//VALIDASI CEK TENOR DENGAN SETTING RATE POLIS
	$premistandar = $mamet_appr['kredit_jumlah'] * $cekratepolis['rate'] / 1000;
if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
$message .='<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
				<td align="center">'.++$no.'</td>
				<td align="center">'.$mamet_appr['spaj'].'</td>
				<td align="center">'.$mamet_appr['regional'].'</td>
				<td align="center">'.$mamet_appr['area'].'</td>
				<td align="center">'.$mamet_appr['cabang'].'</td>
				<td>'.$mamet_appr['nama'].'</td>
				<td align="center">'._convertDate($mamet_appr['tgl_lahir']).'</td>
				<td align="right">'.duit($mamet_appr['kredit_jumlah']).'</td>
				<td align="center">'._convertDate($mamet_appr['kredit_tgl']).'</td>
				<td align="center">'.duit($mamet_appr['kredit_tenor']).'</td>
				<td align="center">'.$mamet_appr['usia'].'</td>
				<td align="center">'.$cekratepolis['rate'].'</td>
				<td align="right">'.duit($premistandar).'</td>
				<td align="center">'.$mamet_appr['ext_premi'].'</td>
				<td align="right">'.duit($premistandar).'</td>
				<td align="center">'.$mamet_appr['status_medik'].'</td>
		  		</tr>';
}
$message .='</table>';
echo $message;
/* EMAIL MODEL PHPMAILER
$pecahtgl = explode(" ", $futgl);	$pecahlagi = explode("-", $pecahtgl[0]);	$tglnya = $pecahlagi[2].'-'.$pecahlagi[1].'-'.$pecahlagi[0].' '.$pecahtgl[1];				//PECAH TANGGAL

$fu = mysql_fetch_array($database->doQuery('SELECT fu_ajk_costumer.name, fu_ajk_costumer.id FROM fu_ajk_costumer WHERE name="'.$qsescost['name'].'"'));
$met = $database->doQuery('UPDATE v_fu_ajk_peserta_tempf SET status_data = "Approve By User" WHERE status_data = ""');

$to = "pajar@relife.co.id, sumiyanto@relife.co.id, arief.kurniawan@relife.co.id, arief@ariefkurniawan.com";
$subject = 'AJKOnline - PESERTA BARU RELIFE AJK ONLINE';
$message = '<html><head><title>Data peserta baru sudah di input oleh '.$q['nm_lengkap'].' selaku staff Relife AJK-Online </title></head>
			<body>
			 <table><tr><th>Data peserta sudah di input oleh <b>'.$_SESSION['nm_user'].' selaku staff Relife AJK-Online pada tanggal '.$tglnya.'</tr></table>
			</body>
			</html>';
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
// Additional headers
$headers .= 'From: '.$q['email'].'' . "\r\n";
$headers .= 'Cc:  rahmad@relife.co.id' . "\r\n";
//	$headers .= 'Bcc: k@example.com' . "\r\n";
mail($to, $subject, $message, $headers);
*/

$mail = new PHPMailer; // call the class
$mail->IsSMTP();
$mail->Host = SMTP_HOST; //Hostname of the mail server
$mail->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
$mail->Username = SMTP_UNAME; //Username for SMTP authentication any valid email created in your domain
$mail->Password = SMTP_PWORD; //Password for SMTP authentication
$mail->AddReplyTo = SMTP_REPLYMAIL; //reply-to address
$mail->SetFrom ($q['email'], $q['nm_lengkap']); //From address of the mail
$mail->Subject = "AJKOnline - UPLOAD DATA PSERTA BARU AJK ONLINE"; //Subject od your mail
//EMAIL PENERIMA  KANTOR U/W
$mailsupervisorajk = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="" AND status="UNDERWRITING"');
while ($_mailsupervisorajk = mysql_fetch_array($mailsupervisorajk)) {
$mail->AddAddress($_mailsupervisorajk['email'], $_mailsupervisorajk['nm_lengkap']); //To address who will receive this email
}
//EMAIL PENERIMA  KANTOR U/W

//EMAIL PENERIMA  KANTOR U/W
$mailclient = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="'.$_REQUEST['idc'].'" AND id_polis="'.$_REQUEST['idp'].'" AND status!="" AND aktif="Y"');
while ($_mailclient = mysql_fetch_array($mailclient)) {
$mail->AddAddress($_mailclient['email'], $_mailclient['nm_lengkap']); //To address who will receive this email
}
//EMAIL PENERIMA  KANTOR U/W

$mail->AddCC("kepodank@gmail.com");
//$mail->AddAddress($mailsupervisorajk['email'], $mailsupervisorajk['nm_lengkap']); //To address who will receive this email
//$mail->AddCC($approvemail);
$mail->MsgHTML('<table><tr><th>Data peserta baru telah diinput oleh <b>'.$_SESSION['nm_user'].' selaku staff AJK-Online pada tanggal '.$futgl.'</tr></table>'.$message); //Put your body of the message you can place html code here
$send = $mail->Send(); //Send the mails
//echo $mail;
echo '<center>Data Peserta sudah diinput oleh <b>'.$_SESSION['nm_user'].'</b>, tunggu konfirmasi selanjutnya dari Supervisor untuk pencetakan nomor DN.<br />
	  <a href="ajk_uploader_peserta.php">Kembali Ke Halaman Utama</a></center>';
		;
		break;

	case "fuparsing":
$fu = mysql_fetch_array($database->doQuery('SELECT fu_ajk_costumer.name, fu_ajk_costumer.id FROM fu_ajk_costumer WHERE id="'.$_REQUEST['cat'].'"'));
$fufile = mysql_fetch_array($database->doQuery('SELECT fu_ajk_peserta.namafile FROM fu_ajk_peserta WHERE id_cost="'.$_REQUEST['cat'].'"'));
$fupolis = mysql_fetch_array($database->doQuery('SELECT fu_ajk_polis.nopol, fu_ajk_polis.nmproduk, fu_ajk_polis.id FROM fu_ajk_polis WHERE id="'.$_REQUEST['subcat'].'"'));

$_REQUEST['subcat'] = $_POST['subcat'];				if (!$_REQUEST['subcat'])  $error .='Silahkan pilih nomor polis<br />.';
$_REQUEST['bataskolom'] = $_POST['bataskolom'];		if (!$_REQUEST['bataskolom'])  $error .='Silahkan tentukan batas kolom file<br />.';
if (!$_FILES['userfile']['tmp_name'])  $error .='Silahkan upload file excel anda<br />.';
		$allowedExtensions = array("xls","xlsx","csv");
		foreach ($_FILES as $file) {
			if ($file['tmp_name'] > '') {
				if (!in_array(end(explode(".",	strtolower($file['name']))), $allowedExtensions)) {
					die('<center><font color=red>'.$file['name'].' <br /><blink>File extension tidak diperbolehkan selain excel!</blink></font><br/>'.'<a href="ajk_uploader_peserta.php">'.'&lt;&lt Go Back</a></center>');
					}
				}
			}
if ($error)
{	echo '<blink><center><font color=red>'.$error.'</font></blink><a href="ajk_uploader_peserta.php">'.'&lt;&lt Go Back</a></center>';	}

else
{
echo '<form method="post" action="ajk_uploader.php?r=approveuser" onload ="onbeforeunload">
	  <table border="0" width="100%" cellpadding="1" cellspacing="1" style="border: solid 3px #DEDEDE"  bgcolor="#bde0e6">
		<tr><td colspan="3"><input type="hidden" name="idcostumer" value="'.$fu['id'].'">Nama Perusahaan</td><td colspan="24">: <b>'.$fu['name'].'</b></td></tr>
		<tr><td colspan="3"><input type="hidden" name="idpolis" value="'.$fupolis['id'].'">Nama Produk</td><td colspan="24">: <b>'.$fupolis['nmproduk'].'</b></td></tr>
		<tr><td colspan="3"><input type="hidden" name="bataskolom" value="'.$_REQUEST['bataskolom'].'"><input type="hidden" name="namafileexl" value="'.$_FILES['userfile']['name'].'">Nama File Upload</td><td colspan="24">: <b>'.$_FILES['userfile']['name'].'</b></td></tr>
		<tr><th width="1%" rowspan="2">No</th>
			<th width="5%" rowspan="2">Nama Mitra</th>
			<th width="8%" rowspan="2">Regional</th>
			<th width="8%" rowspan="2">Area</th>
			<th width="10%" rowspan="2">Cabang</th>
			<th rowspan="2">Nama Tertanggung</th>
			<th width="5%" colspan="3">Tanggal Lahir</th>
			<th width="5%" rowspan="2">Uang Asuransi</th>
			<th width="5%" colspan="3">Mulai Asuransi</th>
			<th width="1%" rowspan="2">Tenor<br />(jumlah bulan)</th>
			<th width="1%" rowspan="2">Usia</th>
			<th width="5%" rowspan="2">Tarif Premi</th>
			<th width="5%" rowspan="2">Premi Standar</th>
			<th width="5%" rowspan="2">Ext.Premi</th>
			<th width="5%" rowspan="2">Premi Sekaligus</th>
			<th width="8%" rowspan="2">Medical</th>
		</tr>
		<tr><th>Hari</th><th>Bulan</th><th>Tahun</th><th>Hari</th><th>Bulan</th><th>Tahun</th></tr>';
	$data = new Spreadsheet_Excel_Reader($_FILES['userfile']['tmp_name']);
	$baris = $data->rowcount($sheet_index=0);											//MEMBACA JUMLAH BARIS DATA EXCEL

for ($i=10; $i<=$_REQUEST['bataskolom']; $i++)
{
	$data1=$data->val($i, 1);		//NO
	$data2=$data->val($i, 2);		//NAMA MITRA
	$data3=$data->val($i, 3);		//CABANG
	$data4=$data->val($i, 4);		//NAMA TERTANGGUNG
	$data5=$data->val($i, 5);		//TANGGAL LAHIR (HARI)
	$data6=$data->val($i, 6);		//TANGGAL LAHIR (BLN)
	$data7=$data->val($i, 7);		//TANGGAL LAHIR (THN)
	$data8=$data->val($i, 8);		//UANG ASURANSI
	$data9=$data->val($i, 9);		//MULAI ASURANSI (HARI)
	$data10=$data->val($i, 10);		//MULAI ASURANSI (BLN)
	$data11=$data->val($i, 11);		//MULAI ASURANSI (THN)
	$data12=$data->val($i, 12);		//MA THN
	$data13=$data->val($i, 13);		//MA BLN
	$data14=$data->val($i, 14);		//USIA
	$data15=$data->val($i, 15);		//RATE (TARIF PREMI)
	$data16=$data->val($i, 16);		//PREMI
	$data17=$data->val($i, 17);		//EXTRA PREMI
	$data18=$data->val($i, 18);		//PREMI SEKALIGUS
	$data19=$data->val($i, 19);		//MEDICAL

	//VALIDASI DATA UPLOAD//
	if ($data3==""){ $error ='<font color="red">error</font>'; $dataexcel3=$error;}else{ $dataexcel3=$data3;}
	if ($data4==""){ $error ='<font color="red">error</font>'; $dataexcel4=$error;}else{ $dataexcel4=$data4;}

	if ($data5==""){ $error ='<font color="red">error</font>'; $dataexcel5=$error;}
	else{
		if(!is_numeric($data5)){$error ='<font color="red">error</font>'; $dataexcel5=$error;}else{ $dataexcel5=$data5;}		//VALIDASI HARI
		if(strlen($data5 > 31 )){$error ='<font color="red">error</font>'; $dataexcel5=$error;}else{ $dataexcel5=$data5;}		//VALIDASI HARI
		//	$dataexcel5=$data5;
	}

	if ($data6==""){ $error ='<font color="red">error</font>'; $dataexcel6=$error;}
	else{
		if(!is_numeric($data6)){$error ='<font color="red">error</font>'; $dataexcel6=$error;}else{ $dataexcel6=$data6;}		//VALIDASI BULAN
		if(strlen($data6 > 12 )){$error ='<font color="red">error</font>'; $dataexcel6=$error;}else{ $dataexcel6=$data6;}		//VALIDASI BULAN
		//	$dataexcel6=$data6;
	}
	if ($data7==""){ $error ='<font color="red">error</font>'; $dataexcel7=$error;}
	else{
		if(!is_numeric($data7)){$error ='<font color="red">error</font>'; $dataexcel7=$error;}else{ $dataexcel7=$data7;}		//VALIDASI TAHUN
		if($data7 < 1900 OR $data7 >= $dateY){$error ='<font color="red">error</font>'; $dataexcel7=$error;}else{ $dataexcel7=$data7;}	//VALIDASI TAHUN LAHIR
		//	$dataexcel7=$data7;
	}

	if ($data8==""){ $error ='<font color="red">error</font>'; $dataexcel8=$error;}
	else{
		$titikpos = strpos($data8, ".");	if ($titikpos) { $titikposnya = str_replace(".", "", $data8); $dataexcel8=$titikposnya;}else{ $dataexcel8=$data8;}
		$komapos = strpos($data8, ",");		if ($komapos) { $komaposnya = str_replace(",", "", $data8); $dataexcel8=$komaposnya;}else{ $dataexcel8=$data8;}
		//	$dataexcel8=$data8;
	}

	if ($data9==""){ $error ='<font color="red">error</font>'; $dataexcel9=$error;}
	else{
		if(!is_numeric($data9)){$error ='<font color="red">error</font>'; $dataexcel9=$error;}else{ $dataexcel9=$data9;}		//VALIDASI HARI
		if(strlen($data9 > 31 )){$error ='<font color="red">error</font>'; $dataexcel9=$error;}else{ $dataexcel9=$data9;}		//VALIDASI HARI
		//	$dataexcel9=$data9;
	}
	if ($data10==""){ $error ='<font color="red">error</font>'; $dataexcel10=$error;}
	else{
		if(!is_numeric($data10)){$error ='<font color="red">error</font>'; $dataexcel10=$error;}else{ $dataexcel10=$data10;}		//VALIDASI BULAN
		if(strlen($data10 > 12 )){$error ='<font color="red">error</font>'; $dataexcel10=$error;}else{ $dataexcel10=$data10;}		//VALIDASI BULAN
		//	$dataexcel10=$data10;
	}

	if ($data11==""){ $error ='<font color="red">error</font>'; $dataexcel11=$error;}
	else{
		if(!is_numeric($data11)){$error ='<font color="red">error</font>'; $dataexcel11=$error;}else{ $dataexcel11=$data11;}					//VALIDASI TAHUN
		if($data11 < 2010 OR $data11 > $dateY){$error ='<font color="red">error</font>'; $dataexcel11=$error;}else{ $dataexcel11=$data11;}		//VALIDASI TAHUN KREDIT
		//	$dataexcel11=$data11;
	}

	if ($data12==""){ $error ='<font color="red">error</font>'; $dataexcel12=$error;}
	else{
		$met_tenor = $data12 * 12 + $data13;
		$cekratepolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_polis="'.$fupolis['id'].'" AND tenor="'.$met_tenor.'" AND status="baru"'));	//VALIDASI CEK TENOR DENGAN SETTING RATE POLIS
		if ($met_tenor != $cekratepolis['tenor']) {$error ='<font color="red">error</font>';	$dataexcel12=$error;	}else{	$dataexcel12=$met_tenor;	}			//VALIDASI CEK TENOR DENGAN SETTING RATE POLIS

		//	$dataexcel12=$data12;
	}

	//if ($data13==""){ $error ='<font color="red">error</font>'; $dataexcel13=$error;}else{ $dataexcel13=$data13;}
	//if ($data14==""){ $error ='<font color="red">error</font>'; $dataexcel14=$error;}else{ $dataexcel14=$data14;}
	if ($data15==""){ $error ='<font color="red">error</font>'; $dataexcel15=$error;}else{	$dataexcel15=$data15;	}
	if ($data16==""){ $error ='<font color="red">error</font>'; $dataexcel16=$error;}
	else{
		$titikpos_ = strpos($data16, ".");	if ($titikpos_) { $titikposnya_ = str_replace(".", "", $data16); $dataexcel16=$titikposnya_;}else{ $dataexcel16=$data16;}
		$komapos_ = strpos($data16, ",");	if ($komapos_) { $komaposnya_ = str_replace(",", "", $data16); $dataexcel16=$komaposnya_;}else{ $dataexcel16=$data16;}
		$bintang_ = strpos($data16, ",");	if ($bintang_) { $bintangnya_ = str_replace("*", "", $data16); $dataexcel16=$bintangnya_;}else{ $dataexcel16=$data16;}
		//
		//	$dataexcel16=$data16;
	}
	//if ($data17==""){ $error ='<font color="red">error</font>'; $dataexcel17=$error;}else{ $dataexcel17=$data17;}
	if ($data18==""){ $error ='<font color="red">error</font>'; $dataexcel18=$error;}
	else{
		$titikpos__ = strpos($data18, ".");	if ($titikpos__) { $titikposnya__ = str_replace(".", "", $data18); $dataexcel18=$titikposnya__;}else{ $dataexcel18=$data18;}
		$komapos__ = strpos($data18, ",");	if ($komapos__) { $komaposnya__ = str_replace(",", "", $data18); $dataexcel18=$komaposnya__;}else{ $dataexcel18=$data18;}
		$bintang__ = strpos($data18, ",");	if ($bintang__) { $bintangnya__ = str_replace("*", "", $data18); $dataexcel18=$bintangnya__;}else{ $dataexcel18=$data18;}
		//	$dataexcel18=$data18;
	}
	if ($data19==""){ $error ='<font color="red">error</font>'; $dataexcel19=$error;}else{ $dataexcel19=$data19;}
	/*
	   $cekdatareg = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE id_cost="'.$fu['id'].'" AND name="'.$data13.'"'));			//VALIDASI REGIONAL
	   if ($data13 != $cekdatareg['name']) {$error ='<font color="red">error</font>';	$dataexcel13=$error;	}else{	$dataexcel13=$data13;	}			//VALIDASI REGIONAL

	   $cekdataarea = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_area WHERE id_cost="'.$fu['id'].'" AND name="'.$data14.'"'));				//VALIDASI AREA
	   if ($data14 != $cekdataarea['name']) {$error ='<font color="red">error</font>';	$dataexcel14=$error;	}else{	$dataexcel14=$data14;	}			//VALIDASI AREA

	   //VALIDASI DATA UPLOAD//
	*/

	if ($data5 < 9) { $data5_ = '0'.$data5;	}else{	$data5_ = $data5;}
	if ($data6 < 9) { $data6_ = '0'.$data6;	}else{	$data6_ = $data6;}
	$datatgllahirnya = $data7.'-'.$data6_.'-'.$data5_;

	if ($data9 < 9) { $data9_ = '0'.$data9;	}else{	$data9_ = $data9;}
	if ($data10 < 9) { $data10_ = '0'.$data10;	}else{	$data10_ = $data10;}
	$datatglkreditnya = $data11.'-'.$data10_.'-'.$data9_;

	//CEK RELASI WILAYAH
	$cekdatacab = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id_cost="'.$fu['id'].'" AND name="'.$data3.'"'));			//VALIDASI CABANG
	if ($data3 != $cekdatacab['name']) {$error ='<font color="red">error</font>';	$dataexcel3=$error;	}else{	$dataexcel3=$data3;	}				//VALIDASI CABANG

	$cekdatawilayah = mysql_fetch_array($database->doQuery('SELECT * FROM v_wilayah WHERE id_cost="'.$fu['id'].'" AND cabang="'.$data3.'"'));
	//if ($cekdatawilayah['regional']!=$data13) {$error ='<font color="red">error</font>'; $dataexcel13=$error;}else{ $dataexcel13=$data13;}
	//if ($cekdatawilayah['area']!=$data14) {$error ='<font color="red">error</font>'; $dataexcel14=$error;}else{ $dataexcel14=$data14;}
	//if ($cekdatawilayah['cabang']!=$data15) {$error ='<font color="red">error</font>'; $dataexcel15=$error;}else{ $dataexcel15=$data15;}
	//CEK RELASI WILAYAH

	//CEK DATA DOUBLE UPLOAD DAN STATUS PEMABAYARAN
	$metdouble = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE nama="'.$data4.'" AND tgl_lahir="'.$datatgllahirnya.'" AND kredit_tgl="'.$datatglkreditnya.'" AND kredit_jumlah="'.$dataexcel8.'" AND kredit_tenor="'.$met_tenor.'" AND del IS NULL'));
	if ($metdouble['id_dn']!="" AND $metdouble['status_bayar']=="1") {
		$ceknomor_dn = mysql_fetch_array($database->doQuery('SELECT id, dn_kode FROM fu_ajk_dn WHERE id="'.$metdouble['id_dn'].'"'));
		$no_error ='<font color="red">Data sudah pernah di upload ('.$ceknomor_dn['dn_kode'].')</font>'; $dataexcel4=$data4.'<br />'.$no_error;
	}
	elseif ($metdouble['id_dn']!="" AND $metdouble['status_bayar']=="0") {
		$error ='<font color="red">Data Unpaid ('.$metdouble['nama'].' - '._convertDate($metdouble['tgl_lahir']).')</font>'; $dataexcel4=$error;
	}
	else	{	$dataexcel4=$data4;	}
	//CEK DATA DOUBLE UPLOAD DAN STATUS PEMABAYARAN

if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		<td align="center">'.$data1.'</td>
		<td align="center">'.$data2.'</td>
		<td align="center">'.$cekdatawilayah['regional'].'</td>
		<td align="center">'.$cekdatawilayah['area'].'</td>
		<td>'.$dataexcel3.'</td>
		<td>'.$dataexcel4.'</td>
		<td align="center">'.$dataexcel5.'</td>
		<td align="center">'.$dataexcel6.'</td>
		<td align="center">'.$dataexcel7.'</td>
		<td align="right">'.duit($dataexcel8).'</td>
		<td align="center">'.$dataexcel9.'</td>
		<td align="center">'.$dataexcel10.'</td>
		<td align="center">'.$dataexcel11.'</td>
		<td align="center">'.$dataexcel12.'</td>
	<!--<td>'.$data13.'</td>-->
		<td align="center">'.$data14.'</td>
		<td align="center">'.$data15.'</td>
		<td align="right">'.$dataexcel16.'</td>
		<td>'.$data17.'</td>
		<td align="right">'.$dataexcel18.'</td>
		<td align="center">'.$data19.'</td>
	</tr>';

$met = $database->doQuery('INSERT IGNORE fu_ajk_peserta_tempf SET id_cost="'.$fu['id'].'",
																  id_polis="'.$fupolis['id'].'",
																  namafile="'.$_FILES['userfile']['name'].'",
																  no_urut="'.$data1.'",
																  type_data="SPAJ",
																  spaj="",
																  nama_mitra="'.$data2.'",
																  nama="'.$data4.'",
																  gender="",
																  tgl_lahir="'.$datatgllahirnya.'",
																  usia="'.$data14.'",
																  kredit_tgl="'.$datatglkreditnya.'",
																  kredit_jumlah="'.$dataexcel8.'",
																  kredit_tenor="'.$met_tenor.'",
																  kredit_akhir="",
																  premi="",
																  disc_premi="",
																  bunga="",
																  biaya_adm="",
																  ext_premi="'.$data17.'",
																  totalpremi="",
																  badant="",
																  badanb="",
																  status_medik="'.$data19.'",
																  status_bayar="0",
																  status_aktif="Upload",
																  regional="'.$cekdatawilayah['regional'].'",
																  area="'.$cekdatawilayah['area'].'",
																  cabang="'.$data3.'",
																  input_by ="'.$_SESSION['nm_user'].'",
															      input_time ="'.$futgl.'"');
}
if ($error) {	echo '<tr><td colspan="27" align="center"><font color="red"><blink>Silahkan lengkapi kolom yang error !!!</blink><br /><a href="ajk_uploader_peserta.php?r=cancell&fileclient='.$_FILES['userfile']['name'].'">Back</a></font></td></tr>';	}
else		{	echo '<tr><td colspan="27" align="center"><a title="Approve data upload" href="ajk_uploader_peserta.php?r=approveuser&nmfile='.$_FILES['userfile']['name'].'&dateupl='.$futgl.'&idc='.$_REQUEST['cat'].'&idp='.$_REQUEST['subcat'].'" onClick="if(confirm(\'Apakah anda sudah yakin dengan semua data peserta ini ?\')){return true;}{return false;}"><img src="image/save.png" border="0" width="50"></a>
	 					   &nbsp; &nbsp; <a title="Batalkan data upload" href="ajk_uploader_peserta.php?r=cancell&fileclient='.$_FILES['userfile']['name'].'"><img src="image/deleted.png" border="0" width="50"></a></td></tr>';	}
				echo '</table></form>';
}
		;
		break;

	case "viewall":
if ($_REQUEST['rx']=="pending") {
$metpending = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta_tempf WHERE no_urut="'.$_REQUEST['no_urut'].'" AND nama="'.$_REQUEST['nama'].'" AND tgl_lahir="'.$_REQUEST['tgl_lahir'].'" AND kredit_tgl="'.$_REQUEST['kredit_tgl'].'"'));
$riweuhkreditawal = explode("/", $metpending['kredit_tgl']);	$cektglkreditawal = $riweuhkreditawal[0].'-'.$riweuhkreditawal[1].'-'.$riweuhkreditawal[2];				//KREDIT AWAL EXPLODE

$riweuhkredit = explode("/", $metpending['kredit_tgl']);	$cektglkredit = $riweuhkredit[0].'-'.$riweuhkredit[1].'-'.$riweuhkredit[2];									//KREDIT AKHIR
$endkredit2=date('d/m/Y',strtotime($cektglkredit."+".$metpending['kredit_tenor']." Month". - "1"."Day"));																//KREDIT AKHIR
$vendkredit2=date('Y-m-d',strtotime($cektglkredit."+".$metpending['kredit_tenor']." Month". - "1"."Day"));																//VKREDIT AKHIR

$cekpolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$metpending['id_polis'].'"'));
if ($cekpolis['typeRate']=="T") {
	$RTenor = $metpending['kredit_tenor'] / 12;		$tenortunggal = ceil($RTenor);
	$cekrate = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi_tunggal WHERE id_cost="'.$metpending['id_cost'].'" AND id_polis="'.$metpending['id_polis'].'" AND usia="'.$umur.'" AND tenorthn="'.$tenortunggal.'"'));		// RATE PREMI
}else{
	$cekrate = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$metpending['id_cost'].'" AND id_polis="'.$metpending['id_polis'].'" AND tenor="'.$metpending['kredit_tenor'].'" AND status="baru"'));		// RATE PREMI
}
	$premi = $metpending['kredit_jumlah'] * $cekrate['rate'] / 1000;																		// RATE PREMI
	$diskonpremi = $premi * ($cekpolis['discount'] /100);			//diskon premi
	$tpremi = $premi - $diskonpremi;								//totalpremi

	$tb = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_badan WHERE sex = "'.$metpending['gender'].'" AND w_from <= "'.$metpending['badanb'].'" AND w_to >= "'.$metpending['badanb'].'" AND h_from <= "'.$metpending['badant'].'" AND h_to >= "'.$metpending['badant'].'"'));
	$extrapremi = ($premi * $tb['extrapremi']) / 100;

	$mettotal = $tpremi + $extrapremi + $metpending['biaya_adm'] + $metpending['biaya_refund'];

$mameto = $database->doQuery('INSERT INTO fu_ajk_peserta SET no_urut="'.$metpending['no_urut'].'",
														  id_dn="",
														  id_cost="'.$metpending['id_cost'].'",
														  id_polis="'.$metpending['id_polis'].'",
														  namafile="'.$metpending['namafile'].'",
														  spaj="'.$metpending['spaj'].'",
														  nama="'.$metpending['nama'].'",
														  gender="'.$metpending['gender'].'",
														  kartu_type="'.$metpending['kartu_type'].'",
														  kartu_no="'.$metpending['kartu_no'].'",
														  kartu_period="'.$metpending['kartu_period'].'",
														  tgl_lahir="'.$metpending['tgl_lahir'].'",
														  usia="'.$_REQUEST['u'].'",
														  kredit_tgl="'.$metpending['kredit_tgl'].'",
														  vkredit_tgl="'.$cektglkreditawal.'",
														  thn="'.$riweuhkreditawal[2].'",
														  bln="'.$riweuhkreditawal[1].'",
														  kredit_jumlah="'.$metpending['kredit_jumlah'].'",
														  kredit_tenor="'.$metpending['kredit_tenor'].'",
														  kredit_akhir="'.$endkredit2.'",
														  vkredit_akhir="'.$vendkredit2.'",
														  premi="'.$premi.'",
														  bunga="'.$metpending['bunga'].'",
														  disc_premi="'.$diskonpremi.'",
														  biaya_adm="'.$metpending['biaya_adm'].'",
														  biaya_refund="'.$metpending['biaya_refund'].'",
														  ext_premi="'.$extrapremi.'",
														  totalpremi="'.$mettotal.'",
														  badant="'.$metpending['badant'].'",
														  badanb="'.$metpending['badanb'].'",
														  statement1="'.$metpending['statement1'].'",
														  p1_ket="'.$metpending['p1_ket'].'",
														  statement2="'.$metpending['statement2'].'",
														  p2_ket="'.$metpending['p2_ket'].'",
														  statement3="'.$metpending['statement3'].'",
														  p3_ket="'.$metpending['p3_ket'].'",
														  statement4="'.$metpending['statement4'].'",
														  p4_ket="'.$metpending['p4_ket'].'",
														  ket="'.$metpending['ket'].'",
														  status_medik="'.$_REQUEST['m'].'",
														  status_bayar="0",
														  status_aktif="pending",
														  status_peserta="'.$metpending['status_peserta'].'",
														  regional ="'.$metpending['regional'].'",
														  area ="'.$metpending['area'].'",
														  cabang ="'.$metpending['cabang'].'",
														  input_by ="'.$metpending['input_by'].'",
														  input_time ="'.$metpending['input_time'].'"');

$metpendingdel = $database->doQuery('DELETE FROM fu_ajk_peserta_tempf WHERE no_urut="'.$_REQUEST['no_urut'].'" AND nama="'.$_REQUEST['nama'].'" AND tgl_lahir="'.$_REQUEST['tgl_lahir'].'" AND kredit_tgl="'.$_REQUEST['kredit_tgl'].'"');
header("location:ajk_uploader_peserta.php?r=viewall");
}
$cat=$_GET['cat']; // Use this line or below line if register_global is off
if(strlen($cat) > 0 and !is_numeric($cat)){ // to check if $cat is numeric data or not.
	echo "Data Error";
	exit;
}
echo '<fieldset style="padding: 1">
	<legend align="center">S e a r c h</legend>
	<table border="0" width="40%" cellpadding="0" cellspacing="0">
		<form name="f2" method="post" action="">
		<tr><td width="15%" align="right">Company</td>
	  <td width="30%">: <select id="cat" name="cat" onchange="reload2(this.form)">
	  	<option value="">---Select Company---</option>';
$quer2=$database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name ASC');
while($noticia2 = mysql_fetch_array($quer2)) {
if($noticia2['id']==$cat){echo '<option selected value="'.$noticia2['id'].'">'.$noticia2['name'].'</option><BR>';}
else{echo  '<option value="'.$noticia2['id'].'">'.$noticia2['name'].'</option>';}
}
echo '</select></td></tr>
	<tr><td width="10%" align="right">Product</td>
		<td width="20%">: ';
if(isset($cat) and strlen($cat) > 0){
	$quer=$database->doQuery('SELECT * FROM fu_ajk_polis where id_cost="'.$cat.'" ORDER BY id ASC');
}else{$quer=$database->doQuery("SELECT * FROM fu_ajk_polis ORDER BY id ASC"); }
		echo '<select id="subcat"  name="subcat"><option value="">---Product---</option>';
while($noticia = mysql_fetch_array($quer)) {

	echo  '<option value='.$noticia['id'].'>'.$noticia['nmproduk'].'</option>';
}
echo '</select></td></tr>
		<tr><td colspan="1" align="right"><input type="submit" name="met" value="Searching" class="button"></td></tr>
			</form></table></fieldset>';

echo '<form method="post" action="ajk_uploader_peserta.php?r=approve" onload ="onbeforeunload">
	  <table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#bde0e6">
	<tr><th width="1%"></th>
		<th width="1%"><input type="checkbox" id="selectall"/></th>
		<th width="1%">No</th>
		<th width="5%">Nama Mitra</th>
			<th width="8%">Regional</th>
			<th width="8%">Area</th>
			<th width="8%">Cabang</th>
			<th>Nama Tertanggung</th>
			<th width="5%">Tanggal Lahir</th>
			<th width="5%">Uang Asuransi</th>
			<th width="5%">Mulai Asuransi</th>
			<th width="1%">Tenor<br />(jumlah bulan)</th>
			<th width="1%">Usia</th>
			<th width="5%">Tarif Premi</th>
			<th width="5%">Premi Standar</th>
			<th width="5%">Ext.Premi</th>
			<th width="5%">Premi Sekaligus</th>
			<th width="5%">Medical</th>
			<th width="5%">User</th>
		</tr>';
if ($_REQUEST['met']=="Searching") {
if ($_REQUEST['cat'])		{	$satu = 'AND id_cost LIKE "%' . $_REQUEST['cat'] . '%"';		}
if ($_REQUEST['subcat'])	{	$dua = 'AND id_polis LIKE "%' . $_REQUEST['subcat'] . '%"';		}
//if ($q['status']=="10" AND $q['supervisor']=="1" OR $q['status']=="") {	$cekinputby = 'AND input_by="'.$q['nm_user'].'"';	}	DISABLED 16062014
if ($q['status']==1) {
	$data = $database->doQuery('SELECT * FROM fu_ajk_peserta_tempf WHERE nama!="" AND type_data="SPAJ" '.$satu.' '.$dua.' '.$cekinputby.' ORDER BY input_by, namafile, no_urut ASC');	}
else {
	$data = $database->doQuery('SELECT * FROM fu_ajk_peserta_tempf WHERE nama!="" AND type_data="SPAJ" '.$satu.' '.$dua.' '.$cekinputby.' ORDER BY input_by, namafile, no_urut ASC');	}
	$approvedatanya = '<tr><td colspan="27" align="center"><a href="ajk_uploader_peserta.php?r=approve" onClick="if(confirm(\'Apakah anda sudah yakin dengan semua data peserta ini ?\')){return true;}{return false;}"><input type="submit" name="ApproveClaim" Value="Approve"></a></td></tr>';
}else{
	$data = $database->doQuery('SELECT * FROM fu_ajk_peserta_tempf WHERE nama !="" AND type_data="SPAJ" '.$cekinputby.'  ORDER BY input_by, namafile, no_urut ASC');
}
while ($fudata = mysql_fetch_array($data)) {
//$umur = ceil(((strtotime($fudata['kredit_tgl']) - strtotime($fudata['tgl_lahir']."+6 month")) / (60*60*24*365.2425)));	// FORMULA USIA
$dataceklist = '<input type="checkbox" class="case" name="nama[]" value="'.$fudata['id_cost'].'-met-'.$fudata['id_polis'].'-met-'.$fudata['nama'].'-met-'.$fudata['tgl_lahir'].'-met-'.$fudata['kredit_jumlah'].'">';

$cekratepolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$fudata['id_cost'].'" AND id_polis="'.$fudata['id_polis'].'" AND tenor="'.$fudata['kredit_tenor'].'" AND status="baru"'));	//VALIDASI CEK TENOR DENGAN SETTING RATE POLIS
$premistandar = $fudata['kredit_jumlah'] * $cekratepolis['rate'] / 1000;

$met_produk = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$fudata['id_polis'].'"'));
if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	  <td align="center"><a href="ajk_uploader_peserta.php?r=deldata&id_cost='.$fudata['id_cost'].'&id_polis='.$fudata['id_polis'].'&namafile='.$fudata['namafile'].'&no_urut='.$fudata['no_urut'].'&spaj='.$fudata['spaj'].'&nama='.$fudata['nama'].'&kredit_jumlah='.$fudata['kredit_jumlah'].'" onClick="if(confirm(\'Apakah anda yakin untuk menghapus data peserta ini ?\')){return true;}{return false;}"><img src="image/delete.gif"></a></td>
	  <td align="center">'.$dataceklist.'</td>
	  <td align="center">'.++$no.'</td>
	  <td align="center">'.$fudata['spaj'].'</td>
	  <td align="center">'.$fudata['regional'].'</td>
	  <td align="center">'.$fudata['area'].'</td>
	  <td align="center">'.$fudata['cabang'].'</td>
	  <td>'.$fudata['nama'].'</td>
	  <td align="center">'._convertDate($fudata['tgl_lahir']).'</td>
	  <td align="right">'.duit($fudata['kredit_jumlah']).'</td>
	  <td align="right">'._convertDate($fudata['kredit_tgl']).'</td>
	  <td align="center">'.$fudata['kredit_tenor'].'</td>
	  <td align="center">'.$fudata['usia'].'</td>
	  <td align="right">'.duit($premistandar).'</td>
	  <td align="center">'.$cekratepolis['rate'].'</td>
	  <td align="right">'.$fudata['ext_premi'].'</td>
	  <td align="right">'.duit($premistandar).'</td>
	  <td align="center">'.$fudata['status_medik'].'</td>
	  <td align="center"><font color="blue"><b>'.$fudata['input_by'].'</b></font></td>
	  </tr>';
}

if ($q['status']=="UNDERWRITING" OR $q['status']=="") {
	$el = $database->doQuery('SELECT * FROM fu_ajk_peserta_tempf WHERE id_cost="'.$_REQUEST['cat'].'" ');
	$met = mysql_num_rows($el);
	//if ($met > 0) {	echo '<tr><td colspan="27" align="center"><a href="ajk_uploader_peserta.php?r=approve&val=pclaim&id_cost='.$_REQUEST['cat'].'&id_polis='.$_REQUEST['subcat'].'" onClick="if(confirm(\'Apakah anda sudah yakin dengan semua data peserta ini ?\')){return true;}{return false;}">Approve</a></td></tr>';
	if ($met > 0) {	echo $approvedatanya;
	//}else{	echo '<tr><th colspan="27" align="center"><blink><b><font color="blue">Tidak ada data peserta restruktur, topup atau refund yang harus di validasi. !!!</font></b></blink></th></tr>';
	}else{	echo '';	}
}else{
//echo '<tr><td colspan="27" align="center">'.$q['status'].'</td></tr>';
}

		echo '</table>';
	;
	break;

	default:
$cat=$_GET['cat']; // Use this line or below line if register_global is off
if(strlen($cat) > 0 and !is_numeric($cat)){ // to check if $cat is numeric data or not.
	echo "Data Error";
	exit;
}
echo '<table border="0" cellpadding="0" cellspacing="0" width="100%">
      <tr><th width="100%" align="left">Modul Upload Data Peserta</font></th></tr>
      </table>';
$fu = mysql_fetch_array($database->doQuery('SELECT fu_ajk_costumer.name, fu_ajk_costumer.id FROM fu_ajk_costumer WHERE name="'.$qsescost['name'].'"'));
echo '<form name="f1" method="post" enctype="multipart/form-data" action="ajk_uploader_peserta.php?r=fuparsing">
	<table border="0" width="60%" align="center">
<tr><td width="15%" align="right">Nama Perusahaan <font color="red">*</font></td>
	  <td width="30%">: <select id="cat" name="cat" onchange="reload(this.form)">
	  	<option value="">---Perusahaan---</option>';
$quer2=$database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name ASC');
while($noticia2 = mysql_fetch_array($quer2)) {
if($noticia2['id']==$cat){echo '<option selected value="'.$noticia2['id'].'">'.$noticia2['name'].'</option><BR>';}
else{echo  '<option value="'.$noticia2['id'].'">'.$noticia2['name'].'</option>';}
}
echo '</select></td></tr>
	<tr><td width="10%" align="right">Nama Produk <font color="red">*</font></td>
		<td width="20%">: ';
if(isset($cat) and strlen($cat) > 0){
$quer=$database->doQuery('SELECT * FROM fu_ajk_polis where id_cost="'.$cat.'" ORDER BY id ASC');
}else{$quer=$database->doQuery("SELECT * FROM fu_ajk_polis ORDER BY id ASC"); }
echo '<select name="subcat"><option value="">---Pilih---</option>';
while($noticia = mysql_fetch_array($quer)) {
if ($noticia['nmproduk']=="") {	$metproduknya = $noticia['nopol'];	}else{	$metproduknya = $noticia['nmproduk'];	}
echo  '<option value='.$noticia['id'].'>'.$metproduknya.'</option>';
}
echo '</select></td></tr>
	  <tr><td align="right">Silakan Pilih File Excel <font color="red">*</font></td><td>: '.$bataskolom.' <input name="userfile" type="file" size="50" onchange="checkfile(this);" ></td></tr>
	  <tr><td align="right">Batas Akhir Baris <font color="red">*</font></td><td>: <input type="text" name="bataskolom" value="'.$bataskolom.'" size="1"></td></tr>
	  <tr><td align="center"colspan="2"><input name="upload" type="submit" value="Import"></td></tr>
	  </table></form>';
	;
} // switch

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
	self.location='ajk_uploader_peserta.php?cat=' + val;
}
</script>

<SCRIPT language=JavaScript>
function reload2(form)
{
	var val=form.cat.options[form.cat.options.selectedIndex].value;
	self.location='ajk_uploader_peserta.php?r=viewall&cat=' + val;
}
</script>

<SCRIPT language=JavaScript>
function reload3(form)
{
	var val=form.cat.options[form.cat.options.selectedIndex].value;
	self.location='ajk_uploader_peserta.php?r=viewallclaim&cat=' + val;
}
</script>

<SCRIPT language=JavaScript>
function reload4(form)
{
	var val=form.cat.options[form.cat.options.selectedIndex].value;
	self.location='ajk_uploader_peserta.php?r=fuparsingclaim&cat=' + val;
}
</script>

<SCRIPT language=JavaScript>
function reloadmultiplay(form)
{
	var val=form.cat.options[form.cat.options.selectedIndex].value;
	self.location='ajk_uploader_peserta.php?r=datamultiplay&cat=' + val;
}
</script>

<!--CHECKE ALL-->
<SCRIPT language="javascript">
$(function(){
    $("#selectall").click(function () {	$('.case').attr('checked', this.checked);	});			    // add multiple select / deselect functionality
    $(".case").click(function(){																	// if all checkbox are selected, check the selectall checkbox	// and viceversa
        if($(".case").length == $(".case:checked").length) {
            $("#selectall").attr("checked", "checked");
        } else {
            $("#selectall").removeAttr("checked");
        }

    });
});
</SCRIPT>