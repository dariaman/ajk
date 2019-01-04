<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("../includes/functions.php");
connect();

if (session_is_registered('nm_user')) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));	}
echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="100%" align="left">Modul Validasi Upload Data SPK</font></th></tr></table>';
switch ($_REQUEST['v']) {
case "spk_del":
	$cekdata_spak = $database->doQuery('SELECT * FROM fu_ajk_spak_temp WHERE id_cost="'.$_REQUEST['id_cost'].'" AND spak="'.$_REQUEST['spak'].'" AND fname="'.$_REQUEST['namafile'].'"');
		while ($cekdata_spak_ = mysql_fetch_array($cekdata_spak)) {
			unlink ($metpath .$cekdata_spak_['fname']);
		}

	$met = $database->doQuery('DELETE FROM fu_ajk_spak_temp WHERE id_cost="'.$_REQUEST['id_cost'].'" AND spak="'.$_REQUEST['spak'].'" AND fname="'.$_REQUEST['namafile'].'"');
	header("location:ajk_val_upl.php?v=spk");
	;
	break;

	case "spk":
if ($_REQUEST['exp']=="tambah_expremi") {
$metspk__ = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_temp WHERE spak="'.$_REQUEST['spk'].'" AND status="Pending"'));
	if ($_REQUEST['save_expremi']=="Save") {
	if ($_REQUEST['expremi']=="") 	{	$error_1 = '<tr><td colspan="3"><font color="red"><blink>Silahkan input data extra premi.<br /></font></td></tr>';	}
	if ($_REQUEST['ext_ket']=="") 	{	$error_2 = '<tr><td colspan="3"><font color="red"><blink>Silahkan isi keterangan extra premi.<br /></font></td></tr>';	}
	if ($error_1 OR $error_2){		}
	else{
	$mametspk = $database->doQuery('UPDATE fu_ajk_spak_temp SET ext_premi="'.$_REQUEST['expremi'].'", ket_ext="'.$_REQUEST['ext_ket'].'" WHERE spak="'.$_REQUEST['spkid'].'"');
	header('location:ajk_val_upl.php?v=spk');
		}
	}

echo '<form method="post" action="">
	  <table border="0" width="100%" cellpadding="1" cellspacing="1">
	  <tr class="tr1"><th colspan="3">Tambah Data Extra Premi</th></tr>
	  '.$error_1.'
	  <tr><td width="10%"><input type="hidden" name="spkid" value="'.$_REQUEST['spk'].'">Extra Premi <font color="red">*</font></td>
		  <td width="1%">: </td><td><input type="text" name="expremi" value="'.$metspk__['ext_premi'].'" maxlength="2" size="5" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')">%</td>
	  </tr>
	  '.$error_2.'
	  <tr><td valign="top">Keterangan <font color="red">*</font></td><td valign="top">: </td><td><textarea name="ext_ket"cols="30" rows="1">'.$metspk__['ket_ext'].'</textarea></td></tr>
	  <tr><td><input type="submit" name="save_expremi" Value="Save"> &nbsp; <a href="ajk_val_upl.php?v=spk">Batal</a></td></tr>
	  </table></form>';
}
echo '<form method="post" action="ajk_val_upl.php?v=spk_appr" onload ="onbeforeunload">
	  <table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#bde0e6">
	  <tr><th width="1%"></th>
	  	  <th width="1%"><input type="checkbox" id="selectall"/></th>
	  	  <th width="1%">No</th>
	  	  <th width="1%">Status</th>
		  <th width="10%">SPAK</th>
		  <th width="5%">Ex.Premi(%)</th>
		  <th>Nama File</th>
		  <th>User</th>
	  	  <th width="15%">Tgl Upload</th>
	  	  <!-- <th width="5%">Ext.Premi</th> DISABLED (141017)-->
	  </tr>';
$metspk = $database->doQuery('SELECT * FROM fu_ajk_spak_temp WHERE status="Pending"');
while ($metspk_ = mysql_fetch_array($metspk)) {

if ($q['id_cost']==$q['id_cost'] AND $q['level']==99 AND $q['status']=="") {
$metdokterappr = '<tr><td colspan="9" align="center"><a href="ajk_val_upl.php?v=spk_appr" onClick="if(confirm(\'Apakah anda sudah yakin dengan semua data SPK ini ?\')){return true;}{return false;}"><input type="submit" name="ApproveClaim" Value="Approve"></a></td></tr>';
//$metdokterext_ = '<a href="ajk_val_upl.php?v=spk&exp=tambah_expremi&spk='.$metspk_['spak'].'" title="tambah extra premi"><img src="image/plus.png" width="15"></a>'; DISABLED (141017)
}else{	}

if (($no % 2) == 1)	$objlass = 'tbl-odd'; else				$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	  <td align="center"><a href="ajk_val_upl.php?v=spk_del&id_cost='.$metspk_['id_cost'].'&spak='.$metspk_['spak'].'&namafile='.$metspk_['fname'].'" onClick="if(confirm(\'Apakah anda yakin untuk menghapus data SPK ini ?\')){return true;}{return false;}"><img src="image/delete.gif"></a></td>
	  <td align="center"><input type="checkbox" class="case" name="nama[]" value="'.$metspk_['id_cost'].'-met-'.$metspk_['spak'].'-met-'.$metspk_['fname'].'"></td>
	  <td align="center">'.++$no.'</td>
	  <td align="center">'.$metspk_['status'].'</td>
	  <td align="center">'.$metspk_['spak'].'</td>
	  <td align="center">'.$metspk_['ext_premi'].'</td>
	  <td><a href="ajk_file/_spak/'.$metspk_['fname'].'" target="_blank">'.$metspk_['fname'].'</a></td>
	  <td align="center">'.$metspk_['input_by'].'</td>
	  <td align="center">'.$metspk_['input_date'].'</td>
	  <!--<td align="center">'.$metdokterext_.'</td> DISABLED (141017)-->
	  </tr>';
	}
echo $metdokterappr;

echo '</table></form>';
		;
		break;

	case "spk_appr":
if (!$_REQUEST['nama']) {
echo '<center><font color=red><blink>Tidak ada data SPK yang di pilih, silahkan ceklist data yang akan di Approve. !</blink></font><br/>
	  <a href="ajk_val_upl.php?v=spk">Kembali Ke Halaman Approve SPK</a></center>';
}else{
	$message .= '<table border="0" width="100%" cellpadding="1" cellspacing="3">
				 <tr><td width="1%">No</td>
				 	 <td width="10%">SPK</td>
				 	 <td width="10%">Keterangan</td>
				 	 <td>Nama File</td>
				 	 <td>User Upload</td>
				 	 <td>Tgl Upload</td>
				 	 <td>User Approve</td>
				 	 <td>Tgl Approve</td>
				 </tr>';
foreach($_REQUEST['nama'] as $k => $val){
	$vall = explode("-met-", $val);		//EXPLODE DATA BERDASARKAN CHEKLIST//
	$cekspk = $database->doQuery('SELECT * FROM fu_ajk_spak_temp WHERE id_cost="'.$vall[0].'" AND spak="'.$vall[1].'" AND fname="'.$vall[2].'"');
	while ($cekspk_ = mysql_fetch_array($cekspk)) {
	$mameto = $database->doQuery('INSERT INTO fu_ajk_spak SET id_cost="'.$cekspk_['id_cost'].'",
															  id_polis="'.$cekspk_['id_polis'].'",
															  spak="'.$cekspk_['spak'].'",
															  ext_premi="'.$cekspk_['ext_premi'].'",
															  ket_ext="'.$cekspk_['ket_ext'].'",
															  fname="'.$cekspk_['fname'].'",
															  status="Proses",
															  input_by="'.$cekspk_['input_by'].'",
															  input_date="'.$cekspk_['input_date'].'",
															  update_by="'.$q['nm_lengkap'].'",
															  update_date="'.$futgl.'"');

	$message .='<tr><td>'.++$no.'</td>
					<td>'.$cekspk_['spak'].'</td>
					<td>'.$cekspk_['ket_ext'].'</td>
					<td>'.$cekspk_['fname'].'</td>
					<td>'.$cekspk_['input_by'].'</td>
					<td>'.$cekspk_['input_date'].'</td>
					<td>'.$q['nm_lengkap'].'</td>
					<td>'.$futgl.'</td>
				</tr>';

	}
	$metdel__ = $database->doQuery('DELETE FROM fu_ajk_spak_temp WHERE id_cost="'.$vall[0].'" AND spak="'.$vall[1].'" AND fname="'.$vall[2].'"');
	}
	$message .='</table>';
	/* SMTP MAIL */
	$mail = new PHPMailer; // call the class
	$mail->IsSMTP();
	$mail->Host = SMTP_HOST; //Hostname of the mail server
	$mail->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
	//$mail->Username = SMTP_UNAME; //Username for SMTP authentication any valid email created in your domain
	//$mail->Password = SMTP_PWORD; //Password for SMTP authentication
	//$mail->AddReplyTo = SMTP_REPLYMAIL; //reply-to address
	$mail->SetFrom ($q['email'], $q['nm_lengkap']); //From address of the mail
	$mail->Subject = "AJKOnline - APPROVE DATA SPK"; //Subject od your mail
	//EMAIL STAFF SPK
	$mailstaff = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="'.$q['id_cost'].'" AND level="'.$q['level'].'" AND status!=""');
while ($mailstaff_ = mysql_fetch_array($mailstaff)) {
	$mail->AddAddress($mailstaff_['email'], $mailstaff_['nm_lengkap']); //To address who will receive this email
}
	$maildokter = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="" AND level="'.$q['level'].'"');
while ($maildokter_ = mysql_fetch_array($maildokter)) {
	$mail->AddAddress($maildokter_['email'], $maildokter_['nm_lengkap']); //To address who will receive this email
}
	//EMAIL STAFF SPK

	/*EMAIL ADONAI PUSAT	 DISABLED 141017
	$mailkonfirmasi = $database->doQuery('SELECT * FROM pengguna WHERE id_cost=""');
while ($mailkonfirmasi_ = mysql_fetch_array($mailkonfirmasi)) {
	$mail->AddAddress($mailkonfirmasi_['email'], $mailkonfirmasi_['nm_lengkap']); //To address who will receive this email
}
	//EMAIL ADONAI PUSAT*/

	$mail->AddCC("rahmad@relife.co.id");
	$mail->MsgHTML('<table><tr><th>Data SPK telah di approve oleh <b>'.$_SESSION['nm_user'].' selaku Supervisor AJK-Online pada tanggal '.$futgl.'</tr></table>'.$message); //Put your body of the message you can place html code here
	$send = $mail->Send(); //Send the mails

	echo '<center>Approve data SPK oleh <b>'.$_SESSION['nm_user'].'</b> telah berhasil.<br /> <a href="ajk_val_upl.php?v=spk">Kembali ke Modul SPK.</a></center>';
}
	;
	break;

	case "spaj":
$fu1 = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$q['id_cost'].'"'));
$fu2 = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$q['id_polis'].'"'));
echo '<fieldset style="padding: 1">
	<legend align="center">S e a r c h</legend>
	<form name="f2" method="post" action="">
	<table border="0" width="75%" cellpadding="0" cellspacing="0">
	<tr><td width="15%">Nama Perusahaan</td>
		<td>: ';
echo '<input type="hidden" name="idcost" value="'.$q['id_cost'].'">'.$fu1['name'].'</td></tr>
	<tr><td>Nama Produk</td><td>: '.$fu2['nmproduk'].' ('.$fu2['nopol'].')</td></tr>
	</table></form></fieldset>';
echo '<form method="post" action="" onload ="onbeforeunload">
	  <table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#bde0e6">
	<tr><th width="1%"></th>
		<th width="1%"><input type="checkbox" id="selectall"/></th>
		<th width="1%">No</th>
		<th width="5%">Nama Mitra</th>
		<th>Nama Tertanggung</th>
		<th width="5%">Tanggal Lahir</th>
		<th width="5%">Usia</th>
		<th width="5%">Uang Asuransi</th>
		<th width="5%">Mulai Asuransi</th>
		<th width="5%">Tenor</th>
		<th width="5%">Ext.Premi</th>
		<th width="5%">Cabang</th>
		<th width="5%">Area</th>
		<th width="5%">Regional</th>
		<th width="5%">User</th>
		</tr>';
$data = $database->doQuery('SELECT * FROM fu_ajk_peserta_tempf WHERE nama !="" AND id_cost="'.$q['id_cost'].'" AND id_polis="'.$q['id_polis'].'" AND status_aktif="Upload" AND del IS NULL ORDER BY input_by ASC');
while ($fudata = mysql_fetch_array($data)) {
//	$umur = ceil(((strtotime($fudata['kredit_tgl']) - strtotime($fudata['tgl_lahir']."+6 month")) / (60*60*24*365.2425)));	// FORMULA USIA
	$umur = ceil(((strtotime($fudata['kredit_tgl']) - strtotime($fudata['tgl_lahir'])) / (60*60*24*365.2425)));	// FORMULA USIA
	$dataceklist = '<input type="checkbox" class="case" name="nama[]" value="'.$fudata['id_cost'].'-met-'.$fudata['id_polis'].'-met-'.$fudata['nama'].'-met-'.$fudata['tgl_lahir'].'-met-'.$fudata['kredit_jumlah'].'">';
	if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	  <td align="center"><a href="ajk_val_upl.php?v=deldata&type=spaj&id_cost='.$fudata['id_cost'].'&id_polis='.$fudata['id_polis'].'&namafile='.$fudata['namafile'].'&no_urut='.$fudata['no_urut'].'&spaj='.$fudata['spaj'].'&nama='.$fudata['nama'].'&kredit_jumlah='.$fudata['kredit_jumlah'].'" onClick="if(confirm(\'Apakah anda yakin untuk menghapus data peserta ini ?\')){return true;}{return false;}"><img src="image/delete.gif"></a></td>
	  <td align="center">'.$dataceklist.' </td>
	  <td align="center">'.++$no.'</td>
	  <td align="center">'.$fudata['spaj'].'</td>
	  <td>'.$fudata['nama'].'</td>
	  <td align="center">'._convertDate($fudata['tgl_lahir']).'</td>
	  <td align="center">'.$umur.'</td>
	  <td align="right">'.duit($fudata['kredit_jumlah']).'</td>
	  <td align="center">'._convertDate($fudata['kredit_tgl']).'</td>
	  <td align="center">'.$fudata['kredit_tenor'].'</td>
	  <td align="center">'.$fudata['status_peserta'].'</td>
	  <td align="center">'.$fudata['cabang'].'</td>
	  <td align="center">'.$fudata['area'].'</td>
	  <td align="center">'.$fudata['regional'].'</td>
	  <td align="center"><font color="blue"><b>'.$fudata['input_by'].'</b></font></td>
	  </tr>';
}
if ($q['id_cost'] !="" AND $q['id_polis']!="" AND $q['level']=="5" AND $q['status']=="") {
echo '<tr><td colspan="20" align="center"><a href="ajk_val_upl.php?v=spaj_appr" onClick="if(confirm(\'Apakah anda sudah yakin dengan semua data peserta ini ?\')){return true;}{return false;}">
										  <input type="hidden" name="v" Value="spaj_appr"><input type="submit" name="ve" Value="Approve"></td></tr>';
}else{	}
		;
		break;

case "spaj_appr":
if (!$_REQUEST['nama']) {
echo '<center><font color=red><blink>Tidak ada data yang di pilih, silahkan ceklist data yang akan di Approve. !</blink></font><br/>
	  <a href="ajk_val_upl.php?v=spaj">Kembali Ke Halaman Approve Peserta</a></center>';
}else{
foreach($_REQUEST['nama'] as $k => $val){
	$vall = explode("-met-", $val);		//EXPLODE DATA BERDASARKAN CHEKLIST//
	$r = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta_tempf WHERE id_cost="'.$vall[0].'" AND id_polis="'.$vall[1].'" AND nama="'.$vall[2].'" AND tgl_lahir="'.$vall[3].'" AND kredit_jumlah="'.$vall[4].'" AND status_aktif="Upload"'));
	$admpolis = mysql_fetch_array($database->doQuery('SELECT id_cost, adminfee, day_kredit, discount, singlerate FROM fu_ajk_polis WHERE id_cost="'.$r['id_cost'].'" AND id="'.$r['id_polis'].'"'));

	$tgl_akhir_kredit = date('Y-m-d',strtotime($r['kredit_tgl']."+".$r['kredit_tenor']." Month"."-".$admpolis['day_kredit']." day"));	//KREDIT AKHIR
	//$umur = ceil(((strtotime($rr['kredit_tgl']) - strtotime($rr['tgl_lahir']."+6 month")) / (60*60*24*365.2425)));	// FORMULA USIA DI TAMBAH 6 BULAN
	$umur = ceil(((strtotime($r['kredit_tgl']) - strtotime($r['tgl_lahir'])) / (60*60*24*365.2425)));	// FORMULA USIA

	//MENENTUKAN PERHITUNGAN PREMI BERDASARKAN SINGLE RATE PADA POLIS
	if ($admpolis['singlerate']=="T") {
		$cekrate = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$r['id_cost'].'" AND id_polis="'.$r['id_polis'].'" AND tenor="'.$r['kredit_tenor'].'" AND status="baru"'));		// RATE PREMI
	}else{
		$mettenornya = $r['kredit_tenor'] / 12;
		$cekrate = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$r['id_cost'].'" AND id_polis="'.$r['id_polis'].'" AND usia="'.$umur.'" AND tenor="'.$mettenornya.'" AND status="baru"'));		// RATE PREMI
	}
	//MENENTUKAN PERHITUNGAN PREMI BERDASARKAN SINGLE RATE PADA POLIS
	$premi = $r['kredit_jumlah'] * $cekrate['rate'] / 1000;
	$diskonpremi = $premi * $admpolis['discount'] /100;			//diskon premi
	$tpremi = $premi - $diskonpremi;								//totalpremi

	$mettotal = $tpremi + $extrapremi + $admpolis['adminfee'];															//TOTAL

	//VALIDASI TABEL MEDICAL STATUS MEDIK
	$medik = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_medical WHERE id_cost="'.$r['id_cost'].'" AND  id_polis="'.$r['id_polis'].'" AND '.$umur.' BETWEEN age_from AND age_to AND '.$r['kredit_jumlah'].' BETWEEN si_from AND si_to'));
	$status_medik =$medik['type_medical'];
	//VALIDASI TABEL MEDICAL STATUS MEDIK

	$formattgl = explode("/", $r['kredit_tgl']);		$formattgl1 = $formattgl[2].'-'.$formattgl[1].'-'.$formattgl[0];		// SET FORMAT TANGGAL
	$cekpesertaID = mysql_fetch_array($database->doQuery('SELECT id FROM fu_ajk_peserta ORDER BY id DESC'));					// SET ID PESERTA
	$idnya = 10000000000 + $cekpesertaID['id'] + 1; $idnya2 = substr($idnya, 1);													// SET ID PESERTA
	$mameto = $database->doQuery('INSERT INTO fu_ajk_peserta SET id_cost="'.$r['id_cost'].'",
															 id_polis="'.$r['id_polis'].'",
															 namafile="'.$r['namafile'].'",
															 no_urut="'.$r['no_urut'].'",
															 spaj="'.$r['spaj'].'",
															 type_data="'.$r['type_data'].'",
															 id_peserta="'.$idnya2.'",
															 nama_mitra="'.$r['nama_mitra'].'",
															 nama="'.$r['nama'].'",
															 gender="'.$r['gender'].'",
															 tgl_lahir="'.$r['tgl_lahir'].'",
															 usia="'.$umur.'",
															 kredit_tgl="'.$r['kredit_tgl'].'",
															 kredit_jumlah="'.$r['kredit_jumlah'].'",
															 kredit_tenor="'.$r['kredit_tenor'].'",
															 kredit_akhir="'.$tgl_akhir_kredit.'",
															 premi="'.$premi.'",
															 disc_premi="'.$diskonpremi.'",
															 bunga="",
															 biaya_adm="'.$admpolis['adminfee'].'",
															 ext_premi="'.$data12.'",
															 totalpremi="'.$mettotal.'",
															 badant="",
															 badanb="",
															 status_medik="'.$status_medik.'",
															 status_bayar="0",
															 status_aktif="Approve",
															 regional="'.$r['regional'].'",
															 area="'.$r['area'].'",
															 cabang="'.$r['cabang'].'",
															 input_by ="'.$r['input_by'].'",
															 approve_by ="'.$q['nm_user'].'",
															 approve_time ="'.$futgl.'",
															 input_time ="'.$r['input_time'].'"');

$metdel = $database->doQuery('DELETE FROM fu_ajk_peserta_tempf WHERE id_cost="'.$vall[0].'" AND id_polis="'.$vall[1].'" AND nama="'.$vall[2].'" AND tgl_lahir="'.$vall[3].'" AND kredit_jumlah="'.$vall[4].'" AND status_aktif ="Upload"');
	}

	$mail = new PHPMailer; // call the class
	$mail->IsSMTP();
	$mail->Host = SMTP_HOST; //Hostname of the mail server
	$mail->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
	//$mail->Username = SMTP_UNAME; //Username for SMTP authentication any valid email created in your domain
	//$mail->Password = SMTP_PWORD; //Password for SMTP authentication
	//$mail->AddReplyTo = SMTP_REPLYMAIL; //reply-to address
	$mail->SetFrom ($q['email'], $q['nm_lengkap']); //From address of the mail
	$mail->Subject = "AJKOnline - APPROVE PESERTA BARU SPAJ AJK ONLINE"; //Subject od your mail
	//EMAIL PENERIMA KANTOR U/W
	$mailsupervisorajk = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="" AND status="UW"');
	while ($_mailsupervisorajk = mysql_fetch_array($mailsupervisorajk)) {
		$mail->AddAddress($_mailsupervisorajk['email'], $_mailsupervisorajk['nm_lengkap']); //To address who will receive this email
	}
	//EMAIL PENERIMA KANTOR U/W
	//EMAIL PENERIMA CLIENT

	$mailsupervisorclient = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="'.$vall[0].'" AND id_polis="'.$vall[1].'" AND wilayah="'.$q['wilayah'].'" AND email !=""');
	while ($_mailsupervisorclient = mysql_fetch_array($mailsupervisorclient)) {
		$mail->AddAddress($_mailsupervisorclient['email'], $_mailsupervisorclient['nm_lengkap']); //To address who will receive this email
	}
	//EMAIL PENERIMA CLIENT

	$mail->AddCC("rahmad@yahoo.co.id");
	$mail->MsgHTML('<table><tr><th>Data peserta baru SPAJ telah di approve oleh <b>'.$_SESSION['nm_user'].' selaku Supervisor AJK-Online pada tanggal '.$futgl.'</tr></table>'.$message); //Put your body of the message you can place html code here
	$send = $mail->Send(); //Send the mails

	echo '<center>Approve oleh <b>'.$_SESSION['nm_user'].'</b> telah berhasil.<br /> <a href="ajk_val_upl.php?v=spaj">Kembali Ke Halaman Utama</a></center>';

}
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
		if ($_REQUEST['type']=="spaj") {	header("location:ajk_val_upl.php?v=spaj");	}	else	{	header("location:ajk_val_upl.php?v=fl_spk");	}
	;
	break;

case "fl_spk":
	$metcost = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$q['id_cost'].'"'));
	$metprod = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$q['id_polis'].'"'));
echo '<fieldset style="padding: 1">
		<legend align="center">S e a r c h</legend>
		<table border="0" width="40%" cellpadding="0" cellspacing="0">
		<tr><td width="15%">Nama Perusahaan</td><td width="30%">: '.$metcost['name'].'</td></tr>
		<tr><td width="10%">Nama Produk</td><td width="20%">: '.$metprod['nmproduk'].'</td></tr>
		</table></fieldset>';

echo '<form method="post" action="ajk_val_upl.php?v=approvespk" onload ="onbeforeunload">
		  <table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#bde0e6">
		<tr><th width="1%"></th>
			<th width="1%"><input type="checkbox" id="selectall"/></th>
			<th width="1%">No</th>
			<th width="5%">SPK</th>
			<th>Nama Tertanggung</th>
			<th width="8%">Tanggal Lahir</th>
			<th width="5%">Usia</th>
			<th width="5%">Uang Asuransi</th>
			<th width="8%">Mulai Asuransi</th>
			<th width="5%">Tenor</th>
			<th width="5%">Ext.Premi</th>
			<th width="8%">Cabang</th>
			<th width="8%">Area</th>
			<th width="8%">Regional</th>
			<th width="5%">User</th>
			</tr>';
$data = $database->doQuery('SELECT * FROM fu_ajk_peserta_tempf WHERE nama !="" AND id_cost="'.$q['id_cost'].'" AND id_polis="'.$q['id_polis'].'" AND type_data="SPK" AND status_aktif="Upload" ORDER BY input_time ASC');
while ($fudata = mysql_fetch_array($data)) {
$umur = ceil(((strtotime($fudata['kredit_tgl']) - strtotime($fudata['tgl_lahir']."+6 month")) / (60*60*24*365.2425)));	// FORMULA USIA
$cekextpremi = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak WHERE id_cost="'.$fudata['id_cost'].'" AND spak="'.$fudata['spaj'].'"'));		//CEK DATA SPK
$dataceklist = '<input type="checkbox" class="case" name="nama[]" value="'.$fudata['id_cost'].'-met-'.$fudata['id_polis'].'-met-'.$fudata['nama'].'-met-'.$fudata['tgl_lahir'].'-met-'.$fudata['kredit_jumlah'].'">';
if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		  <td align="center"><a href="ajk_val_upl.php?v=deldata&id_cost='.$fudata['id_cost'].'&id_polis='.$fudata['id_polis'].'&namafile='.$fudata['namafile'].'&no_urut='.$fudata['no_urut'].'&spaj='.$fudata['spaj'].'&nama='.$fudata['nama'].'&kredit_jumlah='.$fudata['kredit_jumlah'].'" onClick="if(confirm(\'Apakah anda yakin untuk menghapus data peserta ini ?\')){return true;}{return false;}"><img src="image/delete.gif"></a></td>
		  <td align="center">'.$dataceklist.'
		  </td>
		  <td align="center">'.++$no.'</td>
		  <td align="center">'.$fudata['spaj'].'</td>
		  <td>'.$fudata['nama'].'</td>
		  <td align="center">'._convertDate($fudata['tgl_lahir']).'</td>
		  <td align="center">'.$umur.'</td>
		  <td align="right">'.duit($fudata['kredit_jumlah']).'</td>
		  <td align="center">'._convertDate($fudata['kredit_tgl']).'</td>
		  <td align="center">'.$fudata['kredit_tenor'].'</td>
		  <td align="center">'.duit($cekextpremi['ext_premi']).'%</td>
		  <td align="center">'.$fudata['cabang'].'</td>
		  <td align="center">'.$fudata['area'].'</td>
		  <td align="center">'.$fudata['regional'].'</td>
		  <td align="center"><font color="blue"><b>'.$fudata['input_by'].'</b></font></td>
		  </tr>';
}

if ($q['level']=="99" AND $q['status']=="") {
	$el = $database->doQuery('SELECT * FROM fu_ajk_peserta_tempf WHERE id_cost="'.$q['id_cost'].'" AND id_polis="'.$q['id_polis'].'" AND type_data="SPK" AND status_aktif="Upload" ');
	$met = mysql_num_rows($el);
	//if ($met > 0) {	echo '<tr><td colspan="27" align="center"><a href="ajk_uploader_peserta.php?r=approve&val=pclaim&id_cost='.$_REQUEST['cat'].'&id_polis='.$_REQUEST['subcat'].'" onClick="if(confirm(\'Apakah anda sudah yakin dengan semua data peserta ini ?\')){return true;}{return false;}">Approve</a></td></tr>';
	if ($met > 0) {	echo '<tr><td colspan="27" align="center"><a href="ajk_val_upl.php?v=approvespk" onClick="if(confirm(\'Apakah anda sudah yakin dengan semua data peserta SPK ini ?\')){return true;}{return false;}"><input type="submit" name="ApproveClaim" Value="Approve"></a></td></tr>';
		//}else{	echo '<tr><th colspan="27" align="center"><blink><b><font color="blue">Tidak ada data peserta restruktur, topup atau refund yang harus di validasi. !!!</font></b></blink></th></tr>';
	}else{	echo '';	}
}else{	}
echo '</table>';
	;
	break;

case "approvespk":
if (!$_REQUEST['nama']) {
echo '<center><font color=red><blink>Tidak ada data yang di pilih, silahkan ceklist data yang akan di Approve. !</blink></font><br/>
	  <a href="ajk_val_upl.php?v=fl_spk">Kembali Ke Halaman Approve Peserta</a></center>';
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
	//CEK EXTRA PREMI
	$cekextpremi = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak WHERE id_cost="'.$rr['id_cost'].'" AND spak="'.$rr['spaj'].'"'));		//CEK DATA SPK
	if ($cekextpremi['ext_premi']=="") {	$expremi = '';	}else{	$expremi = $cekextpremi['ext_premi'];	}

	$extrapremi = $premi * ($expremi / 100);
	$diskonpremi = $premi * $admpolis['discount'] /100;			//DISKON PREMI
	$tpremi = $premi - $diskonpremi;							//TOTAL PREMI

	$mettotal = $tpremi + $extrapremi + $admpolis['adminfee'];															//TOTAL

	$formattgl = explode("/", $rr['kredit_tgl']);		$formattgl1 = $formattgl[2].'-'.$formattgl[1].'-'.$formattgl[0];		// SET FORMAT TANGGAL
	$cekpesertaID = mysql_fetch_array($database->doQuery('SELECT id FROM fu_ajk_peserta ORDER BY id DESC'));					// SET ID PESERTA
	$idnya = 100000000 + $cekpesertaID['id'] + 1; $idnya2 = substr($idnya, 1);													// SET ID PESERTA
$mameto = $database->doQuery('INSERT INTO fu_ajk_peserta SET id_cost="'.$rr['id_cost'].'",
															 id_polis="'.$rr['id_polis'].'",
															 namafile="'.$rr['namafile'].'",
															 no_urut="'.$rr['no_urut'].'",
															 spaj="'.$rr['spaj'].'",
															 type_data="'.$rr['type_data'].'",
															 id_peserta="'.$idnya2.'",
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
															 ext_premi="'.$extrapremi.'",
															 totalpremi="'.$mettotal.'",
															 badant="",
															 badanb="",
															 status_medik="NM",
															 status_bayar="0",
															 status_aktif="Approve",
															 regional="'.$rr['regional'].'",
															 area="'.$rr['area'].'",
															 cabang="'.$rr['cabang'].'",
															 input_by ="'.$rr['input_by'].'",
															 input_time ="'.$rr['input_time'].'",
															 approve_by ="'.$q['nm_user'].'",
															 approve_time ="'.$futgl.'"');

}
	$metdel = $database->doQuery('DELETE FROM fu_ajk_peserta_tempf WHERE id_cost="'.$vall[0].'" AND id_polis="'.$vall[1].'" AND nama="'.$vall[2].'" AND tgl_lahir="'.$vall[3].'" AND kredit_jumlah="'.$vall[4].'" AND status_aktif ="Upload"');
}


	$pecahtgl = explode(" ", $futgl);	$pecahlagi = explode("-", $pecahtgl[0]);	$tglnya = $pecahlagi[2].'-'.$pecahlagi[1].'-'.$pecahlagi[0].' '.$pecahtgl[1];				//PECAH TANGGAL


/* SMTP MAIL */
	$mail = new PHPMailer;
	$mail->IsSMTP();
	$mail->Host = SMTP_HOST;
	$mail->Port = SMTP_PORT;
	$mail->SetFrom ($q['email'], $q['nm_lengkap']);
	$mail->Subject = "AJKOnline - APPROVE PESERTA BARU SPK AJK ONLINE";
	//EMAIL PENERIMA KANTOR U/W
	$mailsupervisorajk = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="" AND status="UNDERWRITING"');
while ($_mailsupervisorajk = mysql_fetch_array($mailsupervisorajk)) {
	$mail->AddAddress($_mailsupervisorajk['email'], $_mailsupervisorajk['nm_lengkap']); //To address who will receive this email
}
	//EMAIL PENERIMA KANTOR U/W
	//EMAIL PENERIMA CLIENT

	$mailsupervisorclient = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="'.$vall[0].'" AND level="'.$q['level'].'" AND wilayah="'.$q['wilayah'].'" AND email !=""');
while ($_mailsupervisorclient = mysql_fetch_array($mailsupervisorclient)) {
	$mail->AddAddress($_mailsupervisorclient['email'], $_mailsupervisorclient['nm_lengkap']); //To address who will receive this email
}
	//EMAIL PENERIMA CLIENT

	$mail->AddCC("penting_kaga@yahoo.co.id");
	$mail->MsgHTML('<table><tr><th>Data peserta baru SPK telah di approve oleh <b>'.$_SESSION['nm_user'].' selaku Supervisor AJK-Online pada tanggal '.$futgl.'</tr></table>'.$message); //Put your body of the message you can place html code here
	$send = $mail->Send(); //Send the mails

	echo '<center>Approve data SPK oleh <b>'.$_SESSION['nm_user'].'</b> telah berhasil, segera dibuat pencetakan nomor DN.<br /> <a href="ajk_val_upl.php?v=fl_spk">Kembali Ke Halaman Utama</a></center>';

}
		;
		break;

	default:
		;
} // switch
/*
echo '<!--WILAYAH COMBOBOX-->
<script src="javascript/metcombo/prototype.js"></script>
<script src="javascript/metcombo/dynamicombo.js"></script>
<!--WILAYAH COMBOBOX-->
<script>
document.observe("dom:loaded",function(){
	new DynamiCombo( "id_cost" , {
		elements:{
			"id_polis":		{url:\'javascript/metcombo/data.php?req=setpoliscostumer\', value:\'id\', label:\'name\', init:\'<?php echo $_POST["id_polis"] ?>\'},
		},
		loadingImage:\'loader1.gif\',
		loadingText:\'Loading...\',
		debug:0
	} )
});
</script>';
*/
?>
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