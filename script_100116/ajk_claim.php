<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("includes/functions.php");
connect();
error_reporting(0);
if (session_is_registered('nm_user')) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));
	$qsescost=mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$q['id_cost'].'"'));
}
$futgl = date("Y-m-d g:i:a");

switch ($_REQUEST['op']) {
	case "cancell":
$cha = $database->doQuery('DELETE FROM fu_ajk_peserta_tempf WHERE namafile="'.$_REQUEST['fileclient'].'"');
header("location:ajk_claim.php");
		;
		break;

	case "appuser":
$mailsupervisor = mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE id_cost="'.$q['id_cost'].'" AND wilayah="'.$q['wilayah'].'" AND status="1"'));
$fu = mysql_fetch_array($database->doQuery('SELECT fu_ajk_costumer.name, fu_ajk_costumer.id FROM fu_ajk_costumer WHERE name="'.$qsescost['name'].'"'));
$met = $database->doQuery('UPDATE v_fu_ajk_peserta_tempf SET status_data = "Approve By User" WHERE status_data = ""');
$to = $approvemail.' '.$q['email'].', '."sumiyanto@relife.co.id, arief.kurniawan@relife.co.id";
$subject = 'AJKOnline - PESERTA MOVEMENT AJK ONLINE';
$datanya .= '<html><head><title>Approve Data '.$_REQUEST['nmclaim'].' Peserta oleh '.$q['nm_user'].'</title></head>
			<body><table border="0" width="100%" cellpadding="1" cellspacing="1">
			<tr><td colspan="15"><b>Approve Data oleh '.$q['nm_lengkap'].' '.$futgl.'</b></td></tr>
			<tr><td rowspan="2" align="center" bgcolor="DEDEDE"><b>No</b></td>
				<td rowspan="2" align="center" bgcolor="DEDEDE"><b>SPAJ</b></td>
				<td rowspan="2" align="center" bgcolor="DEDEDE"><b>Nama</b></td>
				<td rowspan="2" align="center" bgcolor="DEDEDE"><b>Gender</b></td>
				<td colspan="3" align="center" bgcolor="DEDEDE"><b>ID Card</b></td>
				<td rowspan="2" align="center" bgcolor="DEDEDE"><b>D O B</b></td>
				<td colspan="3" align="center" bgcolor="DEDEDE"><b>Status Kredit</b></td>
				<td rowspan="2" align="center" bgcolor="DEDEDE"><b>T/B</b></td>
				<td rowspan="2" align="center" bgcolor="DEDEDE"><b>Status</b></td>
				<td rowspan="2" align="center" bgcolor="DEDEDE"><b>Regional</b></td>
				<td rowspan="2" align="center" bgcolor="DEDEDE"><b>Area</b></td>
				<td rowspan="2" align="center" bgcolor="DEDEDE"><b>Cabang</b></td>
			</tr>
			<tr><td align="center" bgcolor="DEDEDE"><b>Type</td><td align="center" bgcolor="DEDEDE"><b>Nomor</td><td align="center" bgcolor="DEDEDE"><b>Periode</td>
				<td align="center" bgcolor="DEDEDE"><b>Tgl Kredit</td><td align="center" bgcolor="DEDEDE"><b>Jumlah</td><td align="center" bgcolor="DEDEDE"><b>Tenor</td></tr>';
$datauser = $database->doQuery('SELECT * FROM fu_ajk_peserta_tempf WHERE input_by="'.$_SESSION['nm_user'].'" AND id_cost="'.$q['id_cost'].'" AND status_peserta IS NOT NULL');
while ($metuser = mysql_fetch_array($datauser)) {
$datanya .='<tr><td align="center">'.$metuser['no_urut'].'</td>
				<td align="center">'.$metuser['spaj'].'</td>
				<td>'.$metuser['nama'].'</td>
				<td align="center">'.$metuser['gender'].'</td>
				<td align="center">'.$metuser['kartu_type'].'</td>
				<td align="center">'.$metuser['kartu_no'].'</td>
				<td align="center">'.$metuser['kartu_period'].'</td>
				<td align="center">'.$metuser['tgl_lahir'].'</td>
				<td align="center">'.$metuser['kredit_tgl'].'</td>
				<td align="right">'.$metuser['kredit_jumlah'].'</td>
				<td align="center">'.$metuser['kredit_tenor'].'</td>
				<td align="center">'.$metuser['badant'].'/'.$metuser['badanb'].'</td>
				<td align="center">'.$metuser['status_peserta'].'</td>
				<td align="center">'.$metuser['regional'].'</td>
				<td align="center">'.$metuser['area'].'</td>
				<td align="center">'.$metuser['cabang'].'</td>
				<td align="center">'.$metuser['input_type'].'</td>
			</tr>';
}
$datanya .='</table></body></html>';

/* EMAIL MODEL PHPMAILER
	$message = $datanya;
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	// Additional headers
	$headers .= 'From: '.$q['email'].'' . "\r\n";
	$headers .= 'Cc:  relife-ajk@relife.co.id ' . "\r\n";
	//	$headers .= 'Bcc: k@example.com' . "\r\n";
	mail($to, $subject, $message, $headers);
*/

$mail = new PHPMailer; // call the class
$mail->IsSMTP();
$mail->Host = SMTP_HOST; //Hostname of the mail server
$mail->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
//$mail->Username = SMTP_UNAME; //Username for SMTP authentication any valid email created in your domain
//$mail->Password = SMTP_PWORD; //Password for SMTP authentication
//$mail->AddReplyTo = SMTP_REPLYMAIL; //reply-to address
$mail->SetFrom ($q['email'], $q['nm_lengkap']); //From address of the mail
$mail->Subject = "AJKOnline - PESERTA MOVEMENT AJK ONLINE"; //Subject od your mail
$mail->AddAddress($mailsupervisor['email'], $mailsupervisor['nm_lengkap']); //To address who will receive this email
$mail->AddAddress($q['email'], $q['nm_lengkap']); //To address who will receive this email
//$mail->AddAddress($mailsupervisorajk['email'], $mailsupervisorajk['nm_lengkap']); //To address who will receive this email
//$mail->AddAddress("arief.kurniawan@relife.co.id", "Arief Kurniawan"); //To address who will receive this email
//$mail->AddCC("relife-ajk@relife.co.id");
$mail->MsgHTML('<table><tr><th>Data peserta movement telah diinput oleh <b>'.$_SESSION['nm_user'].' selaku staff klient Relife AJK-Online pada tanggal '.$tglnya.'</tr></table>'. $datanya); //Put your body of the message you can place html code here
$send = $mail->Send(); //Send the mails
echo '<center>Data telah di input oleh user, tunggu konfirmasi selanjutnya dari Supervisor untuk segera di proses oleh Admin AJK<br /> <a href="ajk_claim.php">Kembali Ke Halaman Utama</a></center>';
		;
break;

	case "fuparsingclaim":
$_REQUEST['nmclaim'] = $_POST['nmclaim'];
$_REQUEST['bataskolom'] = $_POST['bataskolom'];
$_REQUEST['idpolis'] = $_POST['idpolis'];
if (!$_REQUEST['nmclaim'])  $error .='Silahkan pilih jenis Klaim<br />.';
if (!$_REQUEST['idpolis'])  $error .='Silahkan tentukan Nomor Polis<br />.';
if (!$_REQUEST['bataskolom'])  $error .='Silahkan tentukan batas kolom file<br />.';
if (!$_FILES['userfile']['tmp_name'])  $error .='Silahkan upload file klaim excel anda<br />.';
		$allowedExtensions = array("xls","xlsx","csv");
		foreach ($_FILES as $file) {
			if ($file['tmp_name'] > '') {
				if (!in_array(end(explode(".",	strtolower($file['name']))),
				$allowedExtensions)) {	die('<center><font color=red>'.$file['name'].' <br /><blink>File extension tidak diperbolehkan selain excel!<br/>'.'
											<a href="ajk_claim.php">'.'&lt;&lt Go Back</a></font></blink></center>');	}
			}
		}
if ($error)
{	echo '<blink><center><font color=red>'.$error.'<a href="ajk_claim.php">'.'&lt;&lt Go Back</a></font></blink></center>';	}

else
{
$fu = mysql_fetch_array($database->doQuery('SELECT fu_ajk_costumer.name, fu_ajk_costumer.id FROM fu_ajk_costumer WHERE id="'.$q['id_cost'].'"'));
$pol = mysql_fetch_array($database->doQuery('SELECT fu_ajk_polis.nopol, fu_ajk_polis.id FROM fu_ajk_polis WHERE id="'.$_REQUEST['idpolis'].'"'));
if ($_REQUEST['nmclaim']=="Baloon") {	$cekpolis = '<input type="hidden" name="idpolis" value="11">Nomor Polis</td><td colspan="24">: <b>031101000011</b>';		}
	else	{	$cekpolis = '<input type="hidden" name="idpolis" value="'.$_REQUEST['idpolis'].'">Nomor Polis</td><td colspan="24">: <b>'.$pol['nopol'].'</b>';		}

echo '<form method="post" action="ajk_claim.php?op=appuser">
	  <table border="0" width="100%" cellpadding="1" cellspacing="1" style="border: solid 3px #DEDEDE">
	<tr><td colspan="3"><input type="hidden" name="idcostumer" value="'.$fu['id'].'">Costumer</td><td colspan="24">: <b>'.$fu['name'].'</b></td></tr>
	<tr><td colspan="3">'.$cekpolis.'</td></tr>
	<tr><td colspan="3"><input type="hidden" name="nmclaim" value="'.$_REQUEST['nmclaim'].'">Type Klaim</td><td colspan="24">: <b>'.$_REQUEST['nmclaim'].'</b></td></tr>
	<tr><td colspan="3"><input type="hidden" name="bataskolom" value="'.$_REQUEST['bataskolom'].'"><input type="hidden" name="namafileexl" value="'.$_FILES['userfile']['name'].'">File Name</td><td colspan="24">: <b>'.$_FILES['userfile']['name'].'</b></td></tr>
	<tr><th width="1%" rowspan="2">No</th>
		<th width="5%" rowspan="2">SPAJ</th>
		<th rowspan="2">Nama Debitur<br /><font size="1"><i>(sesuai KTP/SIM)</i></font></th>
		<th width="1%" rowspan="2">P/W</th>
		<th colspan="3">Kartu Identitas</th>
		<th rowspan="2">Tgl Lahir</th>
		<th colspan="3">Status Kredit</th>
		<th width="1%" rowspan="2">Bunga<br>%</th>
		<th colspan="2">Biaya</th>
		<th width="1%" rowspan="2">Tinggi/<br />Berat Badan</th>
		<th colspan="8">Pernyataan (Ya/thk)</th>
		<th rowspan="2">Ket</th>
		<th rowspan="2">Cabang</th>
		<th rowspan="2">Area</th>
		<th rowspan="2">Regional</th>
	</tr>
	<tr><th width="5%">Type</th>
		<th width="5%">No</th>
		<th width="5%">Periode</th>
		<th>Tanggal</th>
		<th>Jumlah</th>
		<th>Tenor</th>
		<th>Adm</th>
		<th>Refund</th>
		<th>P1</th>
		<th>P1-Ket</th>
		<th>P2</th>
		<th>P2-Ket</th>
		<th>P3</th>
		<th>P3-Ket</th>
		<th>P4</th>
		<th>P4-Ket</th>
	</tr>';
$data = new Spreadsheet_Excel_Reader($_FILES['userfile']['tmp_name']);
$baris = $data->rowcount($sheet_index=0);											//MEMBACA JUMLAH BARIS DATA EXCEL

for ($i=10; $i<=$_REQUEST['bataskolom']; $i++)
{
	$data1=$data->val($i, 1);		//no
	$data2=$data->val($i, 2);		//spaj
	$data3=$data->val($i, 3);		//nama
	$data4=$data->val($i, 4);		//p/w
	$data5=$data->val($i, 5);		//type kartu
	$data6=$data->val($i, 6);		//nomor kartu
	$data7=$data->val($i, 7);		//periode kartu
	$data8=$data->val($i, 8);		//tgl lahir
	$data9=$data->val($i, 9);		//tgl kredit
	$data10=$data->val($i, 10);		//jumlah kredit
	$data11=$data->val($i, 11);		//masa kredit
	$data12=$data->val($i, 12);		//bunga
	$data13=$data->val($i, 13);		//administrasi
	$data14=$data->val($i, 14);		//refund
	$data15=$data->val($i, 15);		//tinggi berat badan
	$data16=$data->val($i, 16);		//p1
	$data17=$data->val($i, 17);		//p1 ket
	$data18=$data->val($i, 18);		//p2
	$data19=$data->val($i, 19);		//p2 ket
	$data20=$data->val($i, 20);		//p3
	$data21=$data->val($i, 21);		//p3 ket
	$data22=$data->val($i, 22);		//p4
	$data23=$data->val($i, 23);		//p4 ket
	$data24=$data->val($i, 24);		//keterangan
	$data25=$data->val($i, 25);		//cabang
	$data26=$data->val($i, 26);		//area
	$data27=$data->val($i, 27);		//reg

if ($data1=="") {	$error = '<font color="red"><blink>error</blink></font>';	$dataexcel1 = '<center>'.$error;	} else {	$dataexcel1 = $data1;	}
/*PENDING 25 03 2013
if ($data2=="") {	$error = '<font color="red"><blink>error</blink></font>';	$dataexcel2 = '<center>'.$error;	} else {	$dataexcel2 = $data2;	}
PENDING 25 03 2013*/
if ($data3=="") {	$error = '<font color="red"><blink>error</blink></font>';	$dataexcel3 = '<center>'.$error;	} else {	$dataexcel3 = $data3;	}
if ($data4=="") {	$error = '<font color="red"><blink>error</blink></font>';	$dataexcel4 = '<center>'.$error;	} else {	$dataexcel4 = $data4;	}
if ($data5=="") {	$error = '<font color="red"><blink>error</blink></font>';	$dataexcel5 = '<center>'.$error;	} else {	$dataexcel5 = $data5;	}
if ($data6=="") {	$error = '<font color="red"><blink>error</blink></font>';	$dataexcel6 = '<center>'.$error;	} else {	$dataexcel6 = $data6;	}
if ($data7=="") {	$error = '<font color="red"><blink>error</blink></font>';	$dataexcel7 = '<center>'.$error;	} else {	$dataexcel7 = $data7;	}

$cekdob = explode("/", $data8);	$hrdob = $cekdob[0];	$bldob = $cekdob[1];	$thdob = $cekdob[2];	//CEK TANGGAL LAHIR
if ($data8=="" OR strlen($data8) >10 OR strlen($data8) <10 OR $bldob > 12 ){ $error ='<font color="red">Format error</font>'; $dataexcel8=$error;}else{ $dataexcel8=$data8;}

$cekval = mysql_fetch_array($database->doQuery('SELECT * FROM v_fu_ajk_peserta WHERE id_cost="'.$q['id_cost'].'" AND nama="'.$data3.'" AND tgl_lahir="'.$data8.'"'));
if ($cekval['nama']!=$data3) {	$error = '<font color="red"><blink>Nama tdk ada</blink></font>';	$dataexcel3 = $error;	} else {	$dataexcel3 = $data3;	}

$cektgl = mysql_fetch_array($database->doQuery('SELECT * FROM v_fu_ajk_peserta WHERE id_cost="'.$q['id_cost'].'" AND nama="'.$data3.'" AND tgl_lahir="'.$data8.'"'));
if ($cektgl['tgl_lahir']!=$data8) {	$error = '<font color="red"><blink>Tgl tdk sama</blink></font>';	$dataexcel8 = $error;	} else {	$dataexcel8 = $data8;	}

$cektgl = explode("/", $data9);	$hr = $cektgl[0];	$bl = $cektgl[1];	$th = $cektgl[2];	//CEK TANGGAL MULAI KREDIT
if ($data9=="" OR strlen($data9) >10 OR strlen($data9) <10 OR $bl > 12){ $error ='<font color="red">Format error</font>'; $dataexcel9=$error;}else{ $dataexcel9=$data9;}
if ($data10=="") {	$error = '<font color="red"><blink>error</blink></font>';	$dataexcel10 = '<center>'.$error;	} else {	$dataexcel10 = $data10;	}
	//CEK VALID NUMERIC//
if ($data10=="" OR !is_numeric($data10)) {	$error = '<font color="red"><blink>error</blink></font>';	$dataexcel10 = '<center>'.$error;	} else {	$dataexcel10 = $data10;	}
if ($data11=="" OR !is_numeric($data11)) {	$error = '<font color="red"><blink>error</blink></font>';	$dataexcel11 = '<center>'.$error;	} else {	$dataexcel11 = $data11;	}
//if ($data12=="" OR !is_numeric($data12)) {	$error = '<font color="red"><blink>error</blink></font>';	$dataexcel12 = '<center>'.$error;	} else {	$dataexcel12 = $data12;	}
	//CEK VALID NUMERIC//
	//if ($data13=="") {	$error = '<font color="red"><blink>error</blink></font>';	$dataexcel13 = $error;	} else {	$dataexcel13 = $data13;	}
	//if ($data14=="") {	$error = '<font color="red"><blink>error</blink></font>';	$dataexcel14 = $error;	} else {	$dataexcel14 = $data14;	}
if ($data15=="" OR strstr($data15, "-") OR strstr($data15, ",")) {	$error = '<font color="red"><blink>error</blink></font>';	$dataexcel15 = '<center>'.$error;	} else {	$dataexcel15 = $data15;	}
if ($data16=="") {	$error = '<font color="red"><blink>error</blink></font>';	$dataexcel16 = '<center>'.$error;	} else {	$dataexcel16 = $data16;	}
if ($data16=="") {	$error = '<font color="red"><blink>error</blink></font>';	$dataexcel16 = '<center>'.$error;	} else {	$dataexcel16 = $data16;	}
if ($data18=="") {	$error = '<font color="red"><blink>error</blink></font>';	$dataexcel18 = '<center>'.$error;	} else {	$dataexcel18 = $data18;	}
if ($data20=="") {	$error = '<font color="red"><blink>error</blink></font>';	$dataexcel20 = '<center>'.$error;	} else {	$dataexcel20 = $data20;	}
if ($data22=="") {	$error = '<font color="red"><blink>error</blink></font>';	$dataexcel22 = '<center>'.$error;	} else {	$dataexcel22 = $data22;	}

	$cekreg = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE id_cost="'.$q['id_cost'].'" AND name="'.$data27.'" '));
if ($cekreg['name'] != $data27)	{	$error = '<font color="red"><blink>Regional Not List</blink></font>';	$dataexcel27 = $error;	}
elseif ($data27=="") 			{	$error = '<font color="red"><blink>error</blink></font>';				$dataexcel27 = $error;	}
else {	$dataexcel27 = $data27;	}

	$cekarea = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_area WHERE id_cost="'.$q['id_cost'].'" AND name="'.$data26.'" '));
if ($cekarea['name'] != $data26)	{	$error = '<font color="red"><blink>Area Not List</blink></font>';	$dataexcel26 = $error;	}
elseif ($data26=="") 				{	$error = '<font color="red"><blink>error</blink></font>';				$dataexcel26 = $error;	}
else {	$dataexcel26 = $data26;	}

	$cekcbg = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id_cost="'.$q['id_cost'].'" AND name="'.$data25.'" '));
if ($cekcbg['name'] != $data25)		{	$error = '<font color="red"><blink>Branch Not List</blink></font>';	$dataexcel25 = $error;	}
elseif ($data25=="") 				{	$error = '<font color="red"><blink>error</blink></font>';			$dataexcel25 = $error;	}
else {	$dataexcel25 = $data25;	}
/* CEK VALIDASI KOLOM EXCEL */

/*	//CEK DATA TANGGAL//
	$dataexcel7 = valid_date($data7);
	$dataexcel8 = valid_date($data8);
	$dataexcel9 = valid_date($data9);
	//CEK DATA TANGGAL//
*/

/* HITUNG FORMULA DI KOLOM EXCEL
	$umur = ceil(((time() - strtotime($data8)) / (60*60*24*365.2425)));													// FORMULA USIA

if (strpos ($data10, ",") OR strpos($data10, ".")) {	$bpremi = str_replace (",", "", $data10);		}	else	{	$bpremi = str_replace (".", "", $data10);		}	// PREMI
if (strpos ($data13, ",") OR strpos($data13, ".")) {	$badm   = str_replace (",", "", $data13);		}	else	{	$badm = str_replace (".", "", $data13);			}	// ADMINISTRASI
if (strpos ($data14, ",") OR strpos($data14, ".")) {	$brefund = str_replace (",", "", $data14);		}	else	{	$brefund = str_replace (".", "", $data14);		}	// REFUND
	$cekrate = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE tenor="'.$data11.'"'));			// RATE PREMI
	$premi = $bpremi * $cekrate['rate'] / 1000;																		// RATE PREMI
*/
	//TINGGI DAN BERAT BADAN
	$tbbadan = explode ("/", $data15);
	$tbadan = $tbbadan[0];
	$bbadan = $tbbadan[1];
	$tb = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_badan WHERE sex = "'.$data4.'" AND w_from <= "'.$tbadan.'" AND w_to >= "'.$tbadan.'" AND "'.$bbadan.'" BETWEEN h_from AND h_to'));
	$extrapremi = ($bpremi * $tb['extrapremi']) / 100;
	//TINGGI DAN BERAT BADAN
/*
//	$idp1 = 100000000 + $data1;		$idp2 = substr($idp1,1);	// ID PESERTA //

	//MEDICAL//
	//$medik = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_medical WHERE sex = "'.$data4.'" AND w_from <= "'.$tbadan.'" AND w_to >= "'.$tbadan.'" AND "'.$bbadan.'" BETWEEN h_from AND h_to'));
	//echo $dataexcel10.'<br />';

/*if ($data16=="YA" AND $data18=="TDK" AND $data20=="TDK" AND $data22=="TDK") {
	$status_medik ="NM";
	$status_peserta = "aktif";	}
else
{	$medik = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_medical WHERE "'.$umur.'" BETWEEN age_from AND age_to AND "'.$bpremi.'" BETWEEN si_from AND si_to'));
	//echo $medik['type_medical'].' '.$umur.' '.$bpremi.' '.$data1.'<br />';
	$status_medik = $medik['type_medical'];
	$status_peserta = "pending";
} // CEK STATUS MEDICAL//
	//MEDICAL//
*/
//$totalpremi = $extrapremi + $premi + $badm + $brefund;
/* HITUNG FORMULA DI KOLOM EXCEL */
//echo $data8.'<br />';
// CEK DATA PESERTA YG BELUM BAYAR BILA TERJADI INPUTAN LEBIH DARI 1 //
$m = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_cost="'.$q['id_cost'].'" AND nama="'.$data3.'" AND tgl_lahir="'.$data8.'" AND status_aktif="aktif"'));
if ($_REQUEST['nmclaim']=="Baloon") {
	$tglnewbaloon = explode("/", $data9);	$tglbaloonnew =$tglnewbaloon[2].'-'.$tglnewbaloon[1].'-'.$tglnewbaloon[0];
	$endbaloonnew =date('Y-m-d',strtotime($tglbaloonnew."+".$data11." Month". - "1"."Day"));																//VKREDIT AKHIR$data11
	if ($endbaloonnew < $m['vkredit_akhir']) {
		$errortenor = '<font color="red">Tanggal kredit akhir debitur tidak boleh lebih kecil dari tanggal akhir debitur lamanya. Data tidak dapat di proses !</font>';
		echo '<tr bgcolor="CACACA"><td colspan="27" align="center">'.$m['nama'].' &nbsp; '.$errortenor.'</td></tr>';
	}
}
if ($m['nama']==$data3 AND $m['status_bayar']==0) {
	$errorpaid = '<font color="red">Status UNPAID...!!!</font>';
	echo '<tr bgcolor="CACACA"><td colspan="27" align="center">'.$data3.' - '.$errorpaid.'</td></tr>';
}elseif ($m['nama']!=$data3 OR $m['tgl_lahir']!=$data8) {
	$errorpaid = '<font color="red">Nama Peserta atau tanggal lahir tidak dikenal !!!</font>';
	echo '<tr bgcolor="CACACA"><td colspan="27" align="center">'.$data3.' - '.$errorpaid.'</td></tr>';
}
else	{
	// CEK DATA PESERTA YG BELUM BAYAR BILA TERJADI INPUTAN LEBIH DARI 1 //
echo '<tr BGCOLOR="#DEDEDE">
			<td align="center">'.$dataexcel1.'</td>
			<td>'.$dataexcel2.'</td>
			<td>'.$dataexcel3.'</td>
			<td align="center">'.$dataexcel4.'</td>
			<td align="center">'.$dataexcel5.'</td>
			<td>'.$dataexcel6.'</td>
			<td>'.$dataexcel7.'</td>
			<td>'.$dataexcel8.'</td>
			<td>'.$dataexcel9.'</td>
			<td>'.$dataexcel10.'</td>
			<td align="center">'.$dataexcel11.'</td>
			<td align="center">'.$dataexcel12.'</td>
			<td>'.$dataexcel13.'</td>
			<td>'.$dataexcel14.'</td>
			<td align="center">'.$dataexcel15.'</td>
			<td align="center">'.$dataexcel16.'</td>
			<td>'.$dataexcel17.'</td>
			<td align="center">'.$dataexcel18.'</td>
			<td>'.$dataexcel19.'</td>
			<td align="center">'.$dataexcel20.'</td>
			<td>'.$dataexcel21.'</td>
			<td align="center">'.$dataexcel22.'</td>
			<td>'.$dataexcel23.'</td>
			<td>'.$dataexcel24.'</td>
			<td>'.$dataexcel25.'</td>
			<td>'.$dataexcel26.'</td>
			<td>'.$dataexcel27.'</td>
		</tr>';
}
// INSERT DATABASE//
$exl = $database->doQuery('INSERT IGNORE fu_ajk_peserta_tempf SET no_urut="'.$data1.'",
															     id_dn="",
															     id_cost="'.$fu['id'].'",
															     id_polis="'.$_REQUEST['idpolis'].'",
															     namafile="'.$_FILES['userfile']['name'].'",
															     spaj="'.$data2.'",
															     nama="'.$data3.'",
															     gender="'.$data4.'",
															     kartu_type="'.$data5.'",
															     kartu_no="'.$data6.'",
															     kartu_period="'.$dataexcel7.'",
															     tgl_lahir="'.$data8.'",
															     usia="'.$umur.'",
															     kredit_tgl="'.$data9.'",
															     kredit_jumlah="'.$data10.'",
															     kredit_tenor="'.$data11.'",
															     premi="'.$premi.'",
															     bunga="'.$data12.'",
															     biaya_adm="'.$badm.'",
															     biaya_refund="'.$brefund.'",
															     ext_premi="'.$extrapremi.'",
															     totalpremi="'.$totalpremi.'",
															     badant="'.$tbadan.'",
															     badanb="'.$bbadan.'",
															     statement1="'.$data16.'",
															     p1_ket="'.$data17.'",
															     statement2="'.$data18.'",
															     p2_ket="'.$data19.'",
															     statement3="'.$data20.'",
															     p3_ket="'.$data21.'",
															     statement4="'.$data22.'",
															     p4_ket="'.$data23.'",
															     ket="'.$data24.'",
															     status_medik ="'.$status_medik.'",
															     status_bayar ="0",
															     status_aktif ="'.$status_peserta.'",
															     status_peserta ="'.$_REQUEST['nmclaim'].'",
															     regional ="'.$data27.'",
															     area ="'.$data26.'",
															     cabang ="'.$data25.'",
															     upload_by ="Client",
															     input_by ="'.$_SESSION['nm_user'].'",
															     input_time ="'.$futgl.'"
															     ');
// INSERT DATABASE//
}
if ($error) {	echo '<tr><td colspan="27" align="center"><font color="red"><blink>Silahkan lengkapi kolom yang error !!!</blink><br /><a href="ajk_claim.php?op=cancell&fileclient='.$_FILES['userfile']['name'].'">Back</a></font></td></tr>';	}
elseif($errorpaid) {
	echo '<tr><td colspan="27" align="center"><a href="ajk_claim.php?op=cancell&fileclient='.$_FILES['userfile']['name'].'">Back</a></td></tr>';
}elseif($errortenor) {
	echo '<tr><td colspan="27" align="center"><a href="ajk_claim.php?op=cancell&fileclient='.$_FILES['userfile']['name'].'">Back</a></td></tr>';
}elseif($errortenor){
	echo '<tr><td colspan="27" align="center"><a href="ajk_claim.php?r=cancell&fileclient='.$_FILES['userfile']['name'].'">Back</a></td></tr>';
}else	{
echo '<tr><td colspan="27" align="center"><a href="ajk_claim.php?op=appuser" onClick="if(confirm(\'Apakah anda sudah yakin dengan semua data peserta ini ?\')){return true;}{return false;}">Confirm</a>
 					   &nbsp; | &nbsp; <a href="ajk_claim.php?op=cancell&fileclient='.$_FILES['userfile']['name'].'">Cancel</a></td></tr>';
}
echo '</table></form>';
}
		;
		break;

	default:
echo '<table border="0" cellpadding="0" cellspacing="0" width="100%">
      <tr><th width="100%" align="left">Modul Claim</font></th></tr>
      </table>';
$fu = mysql_fetch_array($database->doQuery('SELECT fu_ajk_costumer.name, fu_ajk_costumer.id FROM fu_ajk_costumer WHERE name="'.$qsescost['name'].'"'));
echo '<form name="f1" method="post" enctype="multipart/form-data" action="ajk_claim.php?op=fuparsingclaim">
	<table border="0" width="60%" align="center">
	<tr><td width="23%">Nama Perusahaan</td><td colspan="3">: ';
echo '<input type="hidden" name="'.$fu['id'].'" value="'.$fu['id'].'">'.$fu['name'].'</td></tr>
	<tr><td>Type Claim</td>
		<td>: <select id="nmclaim" name="nmclaim">
			<option value="">-----Select Claim-----</option>
			<option value="Refund">Refund</option>
			<option value="Restruktur">Restruktur</option>
			<option value="Baloon">Baloon Payment</option>
			<option value="Top Up">Top Up</option>';
echo '</select></td>
	  </tr>'.$_REQUEST['nmclaim'].'
	<tr><td>Nomor Polis</td>
		<td>: <select id="idpolis" name="idpolis">
		<option value="">-----Pilih Polis-----</option>';
$pol = $database->doQuery('SELECT fu_ajk_polis.nopol, fu_ajk_polis.id FROM fu_ajk_polis WHERE id_cost="'.$fu['id'].'" ORDER BY nopol ASC');
while ($policy = mysql_fetch_array($pol)) {
	echo '	<option value="'.$policy['id'].'">'.$policy['nopol'].'</option>';
}
echo '</select></td></tr>
	  <tr><td>Silakan Pilih File Excel </td><td>: '.$bataskolom.' <input name="userfile" type="file" size="50" onchange="checkfile(this);" ></td></tr>
	  <tr><td>Batas Akhir Kolom </td><td>: <input type="text" name="bataskolom" value="'.$bataskolom.'" size="1"></td></tr>
	  <tr><td align="center"colspan="2"><input name="upload" type="submit" value="Import"></td></tr>
	  </table></form>
	  <table border="0" width="100%" align="center">
  <tr><td></td></tr>
  <tr><td><font color="Red">- Pastikan semua kolom terisi dengan benar.</td></tr>
  <tr><td><font color="Red">- Setiap kolom jangan menggunakan format costum, pastikan format bertipe General.</td></tr>
  <tr><td><font color="Red">- Pastikan pengisian file excel tidak dengan metode copy paste tetapi di input langsung.</td></tr>
  <tr><td><font color="Red">- Pastikan koneksi internet stabil selama proses upload.</td></tr>
  <tr><td><font color="Red">- Jangan menekan tombol F5 atau tombol back pada browser selama proses upload berjalan, karena akan mengakibatkan data double.</td></tr>
  <tr><td><font color="Red">- Sebisa mungkin menggunakan koneksi yang aman (bukan tempat umum seperti warnet, cafe dll.</td></tr>
  <tr><td><font color="Red">- Harap menggunakan browser Mozilla Firefox, link download <a href="http://www.mozilla.org/en-US/products/download.html?product=firefox-stub&os=win&lang=en-US" target="_blank">Mozilla</a>.</td></tr>
  <tr><td>Note : Apabila hal di atas tidak diindahkan akan ada kesalahan data dan hal ini sering terjadi.</td></tr>
  </table>';
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