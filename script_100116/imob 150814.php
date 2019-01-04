<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :penting_kaga@yahoo.com
// ----------------------------------------------------------------------------------
include_once('ui.php');
include_once ("includes/functions.php");
connect();
$mb=mysql_fetch_array($database->doQuery('SELECT * FROM user_mobile WHERE nama="'.$_SESSION['nm_user'].'"'));
if ($mb['type']=="Dokter") {
echo "<div id='cssmenu'>
		  <ul><li><a href='imob.php'><span>Home</span></a></li>
			  <li><a href='imob.php?ob=tabgen'><span>Tab Generator</span></a></li>
			  <li><a href='login.php?op=logout'><span>Logout</span></a></li>
			  <li class='displayname'>".$mb['namalengkap']."</li>
		</ul>
	</div><br /><br />";
}elseif ($mb['type']=="Marketing"){
echo "<div id='cssmenu'>
		  <ul><li><a href='imob.php'><span>Home</span></a></li>
			  <li><a href='imob.php?ob=appspk'><span>Approve SPK</span></a></li>
			  <li><a href='imob.php?ob=listspk'><span>List Debitur SPK</span></a></li>
			  <li><a href='imob.php?ob=rSPK'><span>Report</span></a></li>
			  <li><a href='login.php?op=logout'><span>Logout</span></a></li>
			  <li class='displayname'>".$mb['namalengkap']."</li>
		</li>
	</ul>
	</div><br /><br />";
}else{
	header('Location: login.php?op=logout');
}
switch ($_REQUEST['ob']) {
	case "appspk":
echo '<form method="post" action="imob.php?ob=appspkspv">
	  <table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#bde0e6">
	  <tr><th width="5%">Option</th>
	  	  <th width="1%"><input type="checkbox" id="selectall"/></th>
	  	  <th width="1%">No</th>
	  	  <th width="1%">Status</th>
		  <th width="5%">SPAK</th>
		  <th>Nama</th>
		  <th width="5%">Tgl Lahir</th>
		  <th width="1%">Usia</th>
		  <th width="5%">Awal Asuransi</th>
		  <th width="1%">Tenor</th>
		  <th width="5%">Akhir Asuransi</th>
		  <th width="5%">Plafond</th>
		  <th width="5%">Premi</th>
		  <th width="10%">Cabang</th>
		  <th width="15%">Staff</th>
	  	  <th width="5%">Tgl Input</th>
	  </tr>';
$mobUserSPK = $database->doQuery('SELECT
user_mobile.id,
user_mobile.idbank,
user_mobile.idproduk,
user_mobile.`type`,
user_mobile.level,
user_mobile.supervisor,
user_mobile.namalengkap AS unama,
user_mobile.cabang AS usercabang,
fu_ajk_spak_form_temp.id AS idspaktemp,
fu_ajk_spak_form_temp.idcost,
fu_ajk_spak_form_temp.idspk,
fu_ajk_spak_form_temp.nama,
fu_ajk_spak_form_temp.jns_kelamin,
fu_ajk_spak_form_temp.dob,
fu_ajk_spak_form_temp.tgl_periksa,
fu_ajk_spak_form_temp.plafond,
fu_ajk_spak_form_temp.tgl_asuransi,
fu_ajk_spak_form_temp.tenor,
fu_ajk_spak_form_temp.tgl_akhir_asuransi,
fu_ajk_spak_form_temp.x_premi,
fu_ajk_spak_form_temp.x_usia,
fu_ajk_spak_form_temp.cabang,
DATE_FORMAT(fu_ajk_spak_form_temp.input_date, "%Y-%m-%d") AS tglInput,
fu_ajk_spak.spak,
fu_ajk_spak.ext_premi,
fu_ajk_spak.`status`
FROM
user_mobile
Inner Join fu_ajk_spak_form_temp ON user_mobile.id = fu_ajk_spak_form_temp.input_by AND user_mobile.idbank = fu_ajk_spak_form_temp.idcost
LEFT Join fu_ajk_spak ON fu_ajk_spak_form_temp.idspk = fu_ajk_spak.id
WHERE fu_ajk_spak.`status` ="Pending" AND user_mobile.supervisor ="'.$mb['id'].'" AND fu_ajk_spak_form_temp.del IS NULL');
while ($mobUserSPK_ = mysql_fetch_array($mobUserSPK)) {
$metCab = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_cabang WHERE id="'.$mobUserSPK_['usercabang'].'"'));
if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	  <td align="center"><a href="imob.php?ob=btlspk&id='.$mobUserSPK_['idspaktemp'].'&x_spk=mobdel" onClick="if(confirm(\'Apakah anda yakin untuk menghapus data SPK ini ?\')){return true;}{return false;}"><img src="image/delete.gif"></a>&nbsp;
	  					 <a href="imob.php?ob=edtspk&id='.$mobUserSPK_['idspaktemp'].'"><img src="image/edit3.png"></a>
	  </td>
	  <td align="center"><input type="checkbox" class="case" name="nama[]" value="'.$mobUserSPK_['idspaktemp'].'"></td>
	  <td align="center">'.++$no.'</td>
	  <td align="center">'.$mobUserSPK_['status'].'</td>
	  <td align="center">'.$mobUserSPK_['spak'].'</td>
	  <td><a title="preview photo" href="imob.php?ob=vphoto&idp='.$mobUserSPK_['idspaktemp'].'" target="_blank">'.$mobUserSPK_['nama'].'</a></td>
	  <td align="center">'._convertDate($mobUserSPK_['dob']).'</td>
	  <td align="center">'.$mobUserSPK_['x_usia'].'</td>
	  <td align="center">'._convertDate($mobUserSPK_['tgl_asuransi']).'</td>
	  <td align="center">'.$mobUserSPK_['tenor'].'</td>
	  <td align="center">'._convertDate($mobUserSPK_['tgl_akhir_asuransi']).'</td>
	  <td align="right">'.duit($mobUserSPK_['plafond']).'</td>
	  <td align="right">'.duit($mobUserSPK_['x_premi']).'</td>
	  <td>'.$metCab['name'].'</td>
	  <td>'.$mobUserSPK_['unama'].'</td>
	  <td align="center">'._convertDate($mobUserSPK_['tglInput']).'</td>
	  </tr>';
}
echo '<tr><td colspan="15" align="center"><a href="imob.php?ob=appspkspv" onClick="if(confirm(\'Apakah anda sudah yakin dengan semua data SPK ini ?\')){return true;}{return false;}"><input type="submit" name="ApproveClaim" Value="Approve"></a></td></tr>';
echo '</table></form>';
		;
		break;

case "btlspk":
$met = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_form_temp WHERE id="'.$_REQUEST['id'].'"'));
$metspk = mysql_fetch_array($database->doQuery('SELECT id, spak FROM fu_ajk_spak WHERE id="'.$met['idspk'].'"'));
echo '<form method="post" action="ajk_val_upl.php?v=spk_del&id='.$_REQUEST['id'].'&x_spk=mobdel">
	  <table border="0" width="100%" cellpadding="1" cellspacing="1">
	  <tr><td width="20%">Nomor SPK</td><td><b>'.$metspk['spak'].'</b></td></tr>
	  <tr><td>Nama Nasabah</td><td>'.$met['nama'].'</td></tr>
	  <tr><td valign="top">Alasan Pembatalan</td><td><textarea name="pembatalan" cols="50" rows="2">'.$_REQUEST['pembatalan'].'</textarea></td></tr>
	  <tr><td colspan="2"><input type="submit" name="exx" Value="Batal"></td></tr>
	  </table>
	  </form>';
	;
	break;

case "edtspk":
$met = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_form_temp WHERE id="'.$_REQUEST['id'].'"'));
$metSPK = mysql_fetch_array($database->doQuery('SELECT id, id_cost, id_polis, spak FROM fu_ajk_spak WHERE id="'.$met['idspk'].'"'));
echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="100%" align="left">Modul Edit Data SPK</font></th><th><a href="imob.php?ob=appspk"><img src="image/Backward-64.png" width="20"></a></th></tr></table>';
if ($_REQUEST['ed_metspk']=="Simpan") {
if ($_REQUEST['metnama'] == "") {	$error_1 = '<font color="red"><blink>Nama debitur tidak boleh kosong.</font>';	}
if ($_REQUEST['metktp'] == "") {	$error_2 = '<font color="red"><blink>Nomor identitas debitur tidak boleh kosong.</font>';	}
if ($_REQUEST['metsex'] == "") {	$error_3 = '<font color="red"><blink>Silahkan pilih jenis kelamin.</font>';	}
if ($_REQUEST['metdob'] == "") {	$error_4 = '<font color="red"><blink>Tanggal lahir debitur tidak boleh kosong.</font>';	}
if ($_REQUEST['metalamat'] == "") {	$error_5 = '<font color="red"><blink>Alamat debitur tidak boleh kosong.</font>';	}
if ($_REQUEST['metpekerjaan'] == "") {	$error_6 = '<font color="red"><blink>Pekerjaan debitur tidak boleh kosong.</font>';	}
if ($_REQUEST['metplafond'] == "") {	$error_7 = '<font color="red"><blink>Nilai pinjaman debitur tidak boleh kosong.</font>';	}
if ($_REQUEST['mettenor'] == "") {	$error_8 = '<font color="red"><blink>Tenor pinjaman debitur tidak boleh kosong.</font>';	}
if ($error_1 OR $error_2 OR $error_3 OR $error_4 OR $error_5 OR $error_6 OR $error_7 OR $error_8) {

}else{
	$METuP = $database->doQuery('UPDATE fu_ajk_spak_form_temp SET nama="'.$_REQUEST['metnama'].'",
																  noidentitas="'.$_REQUEST['metktp'].'",
																  jns_kelamin="'.$_REQUEST['metsex'].'",
																  dob="'.$_REQUEST['metdob'].'",
																  alamat="'.$_REQUEST['metalamat'].'",
																  noidentitas="'.$_REQUEST['metktp'].'",
																  pekerjaan="'.$_REQUEST['metpekerjaan'].'",
																  plafond="'.$_REQUEST['metplafond'].'",
																  tenor="'.$_REQUEST['mettenor'].'"
								 WHERE id="'.$_REQUEST['id'].'"');
	$mail = new PHPMailer;
	$mail->IsSMTP();
	$mail->Host = SMTP_HOST; //Hostname of the mail server
	$mail->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
	$mail->Username = SMTP_UNAME; //Username for SMTP authentication any valid email created in your domain
	$mail->Password = SMTP_PWORD; //Password for SMTP authentication
	$mail->AddReplyTo = SMTP_REPLYMAIL; //reply-to address
	$mail->debug = 1;
	$mail->SMTPSecure = "ssl";
	$mail->SetFrom ($mb['email'], $mb['namalengkap']);
	$mail->Subject = "AJKOnline - EDIT DATA SPK";
	//EMAIL PENERIMA KANTOR U/W

$mailStaff = mysql_fetch_array($database->doQuery('SELECT * FROM user_mobile WHERE idbank="'.$metSPK['id_cost'].'" AND type="Marketing" AND level="Staff" AND id="'.$met['input_by'].'"'));
$message .= '<table border="0" width="100%">
			 <tr><td colspan="2">To '.$mailStaff['namalengkap'].',<br />
				 Telah dilakukan refisi data debitur oleh '.$mb['namalengkap'].' pada tanggal '._convertDate($futoday).'.</td></tr>
			 <tr><td width="10%">No. SPK</td>
			 	 <td>Nama</td>
			 </tr>
			 <tr><td>'.$metSPK['spak'].'</td>
				<td>'.$met['nama'].'</td>
			</tr>
			</table>';

	//EMAIL STAFF INPUT
	$mail->AddAddress($mailStaff['email'], $mailStaff['namalengkap']); //To address who will receive this email
	//echo $mailStaff['email'].'<br />';
	//echo $mailStaff['namalengkap'].'<br />';
	//EMAIL STAFF INPUT
	$mail->AddCC("adonai.notif@gmail.com");
	$mail->MsgHTML($message); //Put your body of the message you can place html code here
	$send = $mail->Send(); //Send the mails
	echo '<center>Data SPK telah direvisi oleh '.$mb['namalengkap'].' pada tanggal '._convertDate($futoday).'.<br /><meta http-equiv="refresh" content="3;URL=imob.php?ob=appspk"></center>';
	}
}
echo '<form name="f1" method="post" enctype="multipart/form-data" action="">
	  <input type="hidden" name="id" value="'.$_REQUEST['id'].'">
	  <table border="0" cellpadding="1" cellspacing="0" width="100%">
      <tr><td width="20%">Nomor SPK</td><td> : <b>'.$metSPK['spak'].'</b></td></tr>
      <tr><td>Nama <font color="red">*</font></td><td> : <input name="metnama" type="text" size="50" value="' . $met['nama'] . '"> '.$error_1.'</td></tr>
      <tr><td>Nomor KTP <font color="red">*</font></td><td> : <input name="metktp" type="text" size="50" value="' . $met['noidentitas'] . '"> '.$error_2.'</td></tr>
      <tr><td>Jenis Kelamin <font color="red">*</font></td><td> : <input type="radio" name="metsex" value="M"' . pilih($met["jns_kelamin"], "M") . '>Laki-Laki
				<input type="radio" name="metsex" value="F"' . pilih($met["jns_kelamin"], "F") . '>Perempuan '.$error_3.'</td></tr>
      <tr><td>Tanggal Lahir <font color="red">*</font></td><td> : <input type="text" name="metdob" id="metdob" class="tanggal" value="' . $met['dob'] . '" size="10"/> '.$error_4.'</td></tr>
      <tr><td valign="top">Alamat <font color="red">*</font></td><td> : <textarea name="metalamat" value="'.$met['alamat'].'">'.$met['alamat'].'</textarea> '.$error_5.'</td></tr>
      <tr><td>Pekerjaan <font color="red">*</font></td><td> : <input type="text" name="metpekerjaan" value="'.$met['pekerjaan'].'"> '.$error_6.'</td></tr>
      <tr><td>Jumlah Pinjaman <font color="red">*</font></td><td> : <input type="text" name="metplafond" value="'.$met['plafond'].'" onkeyup="this.value=this.value.replace(/[^0-9/,]/g,\'\')"> '.$error_7.'</td></tr>
      <tr><td>Jangka Waktu Pinjaman <font color="red">*</font></td><td> : <input type="text" name="mettenor" value="'.$met['tenor'].'" onkeyup="this.value=this.value.replace(/[^0-9/,]/g,\'\')" maxlength="2" size="1"> tahun '.$error_8.'</td></tr>
  	  <tr><td colspan="2"><input type="hidden" name="el" value="parsingspk"><input name="ed_metspk" type="submit" value="Simpan"></td></tr>
  	  </table></form>';
	;
	break;

case "vphoto":
echo '<link rel="stylesheet" href="javascript/jscssmobile/css/lightbox.css" type="text/css" media="screen" />
	  <script src="javascript/jscssmobile/js/prototype.js" type="text/javascript"></script>
	  <script src="javascript/jscssmobile/js/scriptaculous.js?load=effects" type="text/javascript"></script>
	  <script src="javascript/jscssmobile/js/lightbox.js" type="text/javascript"></script>';
/*
if ($_REQUEST['ev']=="vwdata") {
$metVPhoto = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_form_temp WHERE id="'.$_REQUEST['vid'].'"'));
echo '<img src="ajkmobilescript/'.$metVPhoto['filefotodebitursatu'].'" width="175">';
}else{
*/
	$metVPhoto = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_form_temp WHERE id="'.$_REQUEST['idp'].'"'));
	$metVPhotoSPK = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak WHERE id="'.$metVPhoto['idspk'].'"'));
	//echo '<a href="ajkmobilescript/'.$metVPhoto['filefotodebitursatu'].'" rel="lightbox"><img src=ajkmobilescript/'.$metVPhoto['filefotodebitursatu'].'" width="100" height="40" alt="" /></a>';
	if ($metVPhoto['jns_kelamin'] =="M"){	$gender = "Laki-Laki";	}else	{	$gender = "Perempuan";	}
echo '<table border="0" width="100%">
		  <tr><td colspan="4">Nama Debitur : '.$metVPhoto['nama'].'</td></tr>
		  <tr><td colspan="4">Nomor SPK : '.$metVPhotoSPK['spak'].'</td></tr>
		  <tr><td colspan="4">Jenis Kelamin : '.$gender.'</td></tr>
		  <tr><td colspan="4">Tanggal Lahir : '._convertDate($metVPhoto['dob']).'</td></tr>
		  <tr><td colspan="4">Alamat : '.$metVPhoto['alamat'].'</td></tr>
		  <tr><td colspan="4">Pekerjaan : '.$metVPhoto['pekerjaan'].'</td></tr>
		  <tr><td colspan="4">Jumlah Penjaminan : '.duit($metVPhoto['plafond']).'</td></tr>
		  <tr><td colspan="4">Jangka Waktu Penjaminan : '.$metVPhoto['tenor'].' tahun</td></tr>
		  <tr>
		  	<td width="25%" align="center"><a href="../ajkmobilescript/'.$metVPhoto['filefotodebitursatu'].'" rel="lightbox"><img src="../ajkmobilescript/'.$metVPhoto['filefotodebitursatu'].'" width="175"><br>Foto Debitur</a></td>
		  	<td width="25%" align="center"><a href="../ajkmobilescript/'.$metVPhoto['filefotoktp'].'" rel="lightbox"><img src="../ajkmobilescript/'.$metVPhoto['filefotoktp'].'" width="175"><br>Foto KTP</a></td>
		  	<td width="25%" align="center"><a href="../ajkmobilescript/'.$metVPhoto['filettddebitur'].'" rel="lightbox"><img src="../ajkmobilescript/'.$metVPhoto['filettddebitur'].'" width="175"><br>TTD Debitur</a></td>
		  	<td width="25%" align="center"><a href="../ajkmobilescript/'.$metVPhoto['filettdmarketing'].'" rel="lightbox"><img src="../ajkmobilescript/'.$metVPhoto['filettdmarketing'].'" width="175"><br>TTD Marketing</a></td>
			<td width="25%" align="center"><a href="../ajkmobilescript/'.$metVPhoto['filefotoskpensiun'].'" rel="lightbox"><img src="../ajkmobilescript/'.$metVPhoto['filefotoskpensiun'].'" width="175"><br>Foto SK</a></td>
		  </tr>
		  </table>';
//}
	;
	break;

case "appspkspv":
if (!$_REQUEST['nama']) {
echo '<center><font color=red><blink>Tidak ada data yang di pilih, silahkan ceklist data yang akan di Approve. !</blink></font><br/>
	  <a href="imob.php?ob=appspk">Kembali Ke Halaman Approve SPK</a></center>';
}else{
foreach($_REQUEST['nama'] as $k => $val){
$metFormSPK = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_form_temp WHERE id="'.$val.'"'));
$metSPKnmMobile = mysql_fetch_array($database->doQuery('SELECT id, nama FROM user_mobile WHERE id="'.$metFormSPK['input_by'].'"'));
$metForm = $database->doQuery('INSERT INTO fu_ajk_spak_form SET idcost="'.$metFormSPK['idcost'].'",
																dokter="'.$metFormSPK['dokter'].'",
																idspk="'.$metFormSPK['idspk'].'",
																noidentitas="'.$metFormSPK['noidentitas'].'",
																nama="'.$metFormSPK['nama'].'",
																jns_kelamin="'.$metFormSPK['jns_kelamin'].'",
																dob="'.$metFormSPK['dob'].'",
																alamat="'.$metFormSPK['alamat'].'",
																pekerjaan="'.$metFormSPK['pekerjaan'].'",
																pertanyaan1="'.$metFormSPK['pertanyaan1'].'",
																ket1="'.$metFormSPK['ket1'].'",
																pertanyaan2="'.$metFormSPK['pertanyaan2'].'",
																ket2="'.$metFormSPK['ket2'].'",
																pertanyaan3="'.$metFormSPK['pertanyaan3'].'",
																ket3="'.$metFormSPK['ket3'].'",
																pertanyaan4="'.$metFormSPK['pertanyaan4'].'",
																ket4="'.$metFormSPK['ket4'].'",
																pertanyaan5="'.$metFormSPK['pertanyaan5'].'",
																ket5="'.$metFormSPK['ket5'].'",
																pertanyaan6="'.$metFormSPK['pertanyaan6'].'",
																ket6="'.$metFormSPK['ket6'].'",
																tgl_periksa="'.$metFormSPK['tgl_periksa'].'",
																plafond="'.$metFormSPK['plafond'].'",
																tgl_asuransi="'.$metFormSPK['tgl_asuransi'].'",
																tenor="'.$metFormSPK['tenor'].'",
																tgl_akhir_asuransi="'.$metFormSPK['tgl_akhir_asuransi'].'",
																tinggibadan="'.$metFormSPK['tinggibadan'].'",
																beratbadan="'.$metFormSPK['beratbadan'].'",
																tekanandarah="'.$metFormSPK['tekanandarah'].'",
																nadi="'.$metFormSPK['nadi'].'",
																pernafasan="'.$metFormSPK['pernafasan'].'",
																guladarah="'.$metFormSPK['guladarah'].'",
																x_premi="'.$metFormSPK['x_premi'].'",
																x_usia="'.$metFormSPK['x_usia'].'",
																cabang="'.$metFormSPK['cabang'].'",
																filefotodebitursatu="'.$metFormSPK['filefotodebitursatu'].'",
																filefotodebiturdua="'.$metFormSPK['filefotodebiturdua'].'",
																filefotoktp="'.$metFormSPK['filefotoktp'].'",
																filettddebitur="'.$metFormSPK['filettddebitur'].'",
																filettdmarketing="'.$metFormSPK['filettdmarketing'].'",
																filefotoskpensiun="'.$metFormSPK['filefotoskpensiun'].'",
																input_by="'.$metSPKnmMobile['id'].'",
																input_date="'.$metFormSPK['input_date'].'",
																update_by="'.$mb['id'].'",
																update_date="'.$futgl.'"

');
$metUpdSPK = mysql_fetch_array($database->doQuery('UPDATE fu_ajk_spak SET status="Proses", update_by="'.$mb['id'].'", update_date="'.$futgl.'" WHERE id="'.$metFormSPK['idspk'].'"'));
//$metFormSPK_ = mysql_fetch_array($database->doQuery('DELETE FROM fu_ajk_spak_form_temp WHERE id="'.$val.'"'));
//echo '<br />';
}
$message .= '<table border="0" width="100%">
			 <tr><td width="1%">No.</td>
			 	 <td width="10%">No. SPK</td>
			 	 <td>Nama</td>
			 </tr>';
foreach($_REQUEST['nama'] as $k_mail => $val_mail){
$metFormSPK_mail = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_form_temp WHERE id="'.$val_mail.'" AND del IS NULL'));
$metSPKnmMobile_mail = mysql_fetch_array($database->doQuery('SELECT id, nama,cabang FROM user_mobile WHERE id="'.$metFormSPK_mail['input_by'].'"'));
$message .='<tr><td align="center">'.++$no.'</td>
				<td>'.$metFormSPK_mail['idspk'].'</td>
				<td>'.$metFormSPK_mail['nama'].'</td>
			</tr>';
//$metHapusSPK = mysql_fetch_array($database->doQuery('UPDATE fu_ajk_spak_form_temp SET del ="1" WHERE id="'.$val_mail.'"'));
}
$message .='</table>';
	$mail = new PHPMailer;
	$mail->IsSMTP();
	$mail->Host = SMTP_HOST; //Hostname of the mail server
	$mail->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
	$mail->Username = SMTP_UNAME; //Username for SMTP authentication any valid email created in your domain
	$mail->Password = SMTP_PWORD; //Password for SMTP authentication
	$mail->AddReplyTo = SMTP_REPLYMAIL; //reply-to address
	$mail->debug = 1;
	$mail->SMTPSecure = "ssl";
	$mail->SetFrom ($mb['email'], $mb['namalengkap']);
	$mail->Subject = "AJKOnline - APPROVE PESERTA BARU SPK AJK ONLINE";
	//EMAIL PENERIMA KANTOR U/W

	//EMAIL DOKTER
	$mailDokter = $database->doQuery('SELECT * FROM user_mobile WHERE idbank="'.$metFormSPK_mail['idcost'].'" AND type="Dokter" AND cabang="'.$metSPKnmMobile_mail['cabang'].'"');
while ($mailDokter_ = mysql_fetch_array($mailDokter)) {
	$mail->AddAddress($mailDokter_['email'], $mailDokter_['nama']); //To address who will receive this email
}
	//EMAIL DOKTER

	//EMAIL STAFF INPUT
	$mailStaff = $database->doQuery('SELECT * FROM user_mobile WHERE idbank="'.$metFormSPK_mail['idcost'].'" AND type="Marketing" AND level="Staff" AND supervisor="'.$mb['id'].'"');
while ($mailStaff_ = mysql_fetch_array($mailStaff)) {
	$mail->AddAddress($mailStaff_['email'], $mailStaff_['nama']); //To address who will receive this email
}
	//EMAIL STAFF INPUT
	$mail->AddCC("adonai.notif@gmail.com");
	$mail->MsgHTML($message); //Put your body of the message you can place html code here
	$send = $mail->Send(); //Send the mails
	//echo $message;
	echo '<center>Approve data SPK telah berhasil.<br /> <a href="imob.php?ob=appspk">Kembali Ke Halaman Approve SPK</a></center>';
}
		;
		break;

case "listspk":
echo '<table border="0" width="100%" cellpadding="3" cellspacing="1" style="border: solid 1px #DEDEDE">
	<form method="post" action="" name="frmcust" onSubmit="return valcust()">
	<tr><td width="10%">Status Data</td>
		<td><select name="statusdata"><option value="">Pilih Status</option>
									 <option value="Aktif"'._selected($_REQUEST['statusdata'], "Aktif").'>Aktif</option>
									 <option value="Approve"'._selected($_REQUEST['statusdata'], "Approve").'>Approve</option>
									 <option value="Batal"'._selected($_REQUEST['statusdata'], "Batal").'>Batal</option>
									 <option value="Proses"'._selected($_REQUEST['statusdata'], "Proses").'>Proses</option>
									 <option value="Tolak"'._selected($_REQUEST['statusdata'], "Tolak").'>Tolak</option>
		</select></td>
	</tr>
	<tr><td>Nomor SPK</td>
		<td><input type="text" name="spaknya" value="' . $_REQUEST['spaknya'] . '"/></td>
	</tr>
	<tr><td><input type="hidden" name="mametdn" value="createme" class="button"><input type="submit" name="button" value="Cari" class="button"></td></tr>
	</form>
	</table>';
echo '<table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#bde0e6">
	  <tr><th width="1%">No</th>
	  	  <th width="1%">Status</th>
		  <th width="5%">SPAK</th>
		  <th>Nama</th>
		  <th width="5%">Tgl Lahir</th>
		  <th width="5%">Usia</th>
		  <th width="5%">Awal Asuransi</th>
		  <th width="5%">Tenor</th>
		  <th width="5%">Akhir Asuransi</th>
		  <th width="5%">Plafond</th>
		  <th width="5%">Premi</th>
		  <th width="10%">Cabang</th>
		  <th width="15%">Staff</th>
	  	  <th width="5%">Tgl Input</th>
	  	  <th width="5%">Tgl Approve</th>
	  </tr>';
if ($_REQUEST['spaknya'])		{	$satu = 'AND fu_ajk_spak.spak LIKE "%' . $_REQUEST['spaknya'] . '%"';	}
if ($_REQUEST['statusdata'])	{	$dua = 'AND fu_ajk_spak.status LIKE "%' . $_REQUEST['statusdata'] . '%"';	}
if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}
$mobUserSPK = $database->doQuery('SELECT
fu_ajk_spak.id,
fu_ajk_spak.id_cost,
fu_ajk_spak.id_polis,
fu_ajk_spak.spak,
fu_ajk_spak.`status`,
fu_ajk_spak.input_by AS inputstaff,
DATE_FORMAT(fu_ajk_spak.input_date,"%Y-%m-%d") AS tglinputstaff,
fu_ajk_spak.update_by AS approvespv,
DATE_FORMAT(fu_ajk_spak.update_date,"%Y-%m-%d") AS tglapprovespv,
fu_ajk_spak_form.noidentitas,
fu_ajk_spak_form.nama,
fu_ajk_spak_form.dob,
fu_ajk_spak_form.tgl_asuransi,
fu_ajk_spak_form.tenor,
fu_ajk_spak_form.tgl_akhir_asuransi,
fu_ajk_spak_form.x_usia,
fu_ajk_spak_form.x_premi,
fu_ajk_spak_form.cabang,
user_mobile.namalengkap,
user_mobile.cabang AS usercabang
FROM
fu_ajk_spak
INNER JOIN fu_ajk_spak_form ON fu_ajk_spak.id_cost = fu_ajk_spak_form.idcost AND fu_ajk_spak.id = fu_ajk_spak_form.idspk
INNER JOIN user_mobile ON fu_ajk_spak.input_by = user_mobile.id
WHERE fu_ajk_spak.update_by = "'.$mb['id'].'" AND fu_ajk_spak_form.del IS NULL '.$satu.' '.$dua.'
ORDER BY tglapprovespv DESC LIMIT ' . $m . ' , 25');

$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(fu_ajk_spak.id) FROM fu_ajk_spak
INNER JOIN fu_ajk_spak_form ON fu_ajk_spak.id_cost = fu_ajk_spak_form.idcost AND fu_ajk_spak.id = fu_ajk_spak_form.idspk
WHERE fu_ajk_spak.id!="" AND fu_ajk_spak.update_by = "'.$mb['id'].'" '.$satu.' '.$dua.''));
$totalRows = $totalRows[0];

$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;

while ($mobUserSPK_ = mysql_fetch_array($mobUserSPK)) {
	$metCab = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_cabang WHERE id="'.$mobUserSPK_['usercabang'].'"'));
if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		  <td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
		  <td align="center">'.strtoupper($mobUserSPK_['status']).'</td>
		  <td align="center">'.$mobUserSPK_['spak'].'</td>
		  <td>'.$mobUserSPK_['nama'].'</td>
		  <td align="center">'._convertDate($mobUserSPK_['dob']).'</td>
		  <td align="center">'.$mobUserSPK_['x_usia'].'</td>
		  <td align="center">'._convertDate($mobUserSPK_['tgl_asuransi']).'</td>
		  <td align="center">'.$mobUserSPK_['tenor'].'</td>
		  <td align="center">'._convertDate($mobUserSPK_['tgl_akhir_asuransi']).'</td>
		  <td align="right">'.duit($mobUserSPK_['plafond']).'</td>
		  <td align="right">'.duit($mobUserSPK_['x_premi']).'</td>
		  <td>'.$metCab['name'].'</td>
		  <td>'.$mobUserSPK_['namalengkap'].'</td>
		  <td align="center">'._convertDate($mobUserSPK_['tglinputstaff']).'</td>
		  <td align="center">'._convertDate($mobUserSPK_['tglapprovespv']).'</td>
		  </tr>';
}
	echo '<tr><td colspan="15">';
	echo createPageNavigations($file = 'imob.php?ob=listspk&spaknya='.$_REQUEST['spaknya'].'&statusdata='.$_REQUEST['statusdata'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
	echo 'Total Data SPK: <strong>' . duit($totalRows) . '</strong></td></tr>';
	echo '</table>';
	;
	break;

case "rSPK":
	echo '<table border="0" cellpadding="5" cellspacing="0" width="100%"><tr><th width="95%" align="left">Modul Laporan SPK</font></th></tr></table>';
	$metcost = mysql_fetch_array($database->doQuery('SELECT fu_ajk_costumer.`name`, fu_ajk_polis.nmproduk
													 FROM user_mobile
													 INNER JOIN fu_ajk_costumer ON user_mobile.idbank = fu_ajk_costumer.id
													 INNER JOIN fu_ajk_polis ON user_mobile.idproduk = fu_ajk_polis.id
													 WHERE user_mobile.id="'.$mb['idbank'].'"'));
echo '<form method="post" action="">
		  <input type="hidden" name="id_cost" value="'.$metcost['id'].'">
		  <table border="0" cellpadding="1" cellspacing="0" width="100%">
	      <tr><td width="40%" align="right">Nama Perusahaan</td><td> : '.$metcost['name'].'</td></tr>
	      <tr><td width="40%" align="right">Nama Produk</td><td> : '.$metcost['nmproduk'].'</td></tr>
		  <tr><td align="right">Tanggal Input SPK <font color="red">*</font></td>
		 	  <td> :';print initCalendar();	print calendarBox('tglcheck1', 'triger1', $_REQUEST['tglcheck1']);	echo 's/d';
	print initCalendar();	print calendarBox('tglcheck2', 'triger2', $_REQUEST['tglcheck2']);
echo '</td></tr>
		<tr><td width="40%" align="right">Status</td><td> : <select name="statusdata"><option value="">Pilih Status</option>
									 <option value="Aktif"'._selected($_REQUEST['statusdata'], "Aktif").'>Aktif</option>
									 <option value="Approve"'._selected($_REQUEST['statusdata'], "Approve").'>Approve</option>
									 <option value="Batal"'._selected($_REQUEST['statusdata'], "Batal").'>Batal</option>
									 <option value="Proses"'._selected($_REQUEST['statusdata'], "Proses").'>Proses</option>
									 <option value="Tolak"'._selected($_REQUEST['statusdata'], "Tolak").'>Tolak</option>
									 </select>
		</td></tr>
		<tr><td align="center"colspan="2"><input type="hidden" name="re" value="rListSPK"><input type="submit" name="ere" value="Cari"></td></tr>
		</table>
		</form>';
if ($_REQUEST['re']=="rListSPK") {
if ($_REQUEST['tglcheck1']=="" OR $_REQUEST['tglcheck2']=="") 	{	$error1 = '<div align="center"><font color="red"><blink>Tanggal input SPK tidak boleh kosong<br /></div></font></blink>';	}
if ($error1) {	echo $error1;	}
else{
echo '<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="#E2E2E2">
			<tr><td bgcolor="#FFF"colspan="23"><a href="aajk_report.php?er=eL_ListSPK&idB='.$mb['idbank'].'&ispv='.$mb['id'].'&tgl1='.$_REQUEST['tglcheck1'].'&tgl2='.$_REQUEST['tglcheck2'].'&st='.$_REQUEST['statusdata'].'"><img src="image/excel.png" width="25" border="0"><br />Excel</a></td></tr>
			<th width="1%">No</th>
			<th>Nama Debitur</th>
			<th>Cabang</th>
			<th>No. SPK</th>
			<th>Tgl Pemeriksaan</th>
			<th>Tgl Terima SPK</th>
			<th>Tgl Lahir</th>
			<th>Usia</th>
			<th>Plafond</th>
			<th>EM(%)</th>
			<th>Tenor</th>
			<th>TB</th>
			<th>BB</th>
			<th>SISTOLIK</th>
			<th>DIASTOLIK</th>
			<th>NADI</th>
			<th>PERNAFASAN</th>
			<th>GULA DARAH</th>
			<th>MEROKOK</th>
			<th>JML ROKOK</th>
			<th>CATATAN SKS</th>
			<th>STATUS</th>
			</tr>';
if ($_REQUEST['tglcheck1'])		{
	if ($_REQUEST['tglcheck1'] == $_REQUEST['tglcheck2']) {
		$PenambahanTgl = strtotime ( '+1 day' , strtotime ( $_REQUEST['tglcheck2'] ) ) ;;
		$newdate = date ( 'Y-m-d' , $PenambahanTgl );
		$satu = 'AND fu_ajk_spak.input_date BETWEEN "'.$_REQUEST['tglcheck1'].'" AND "'.$newdate.'" ';
	}else{
	$satu = 'AND fu_ajk_spak.input_date BETWEEN "'.$_REQUEST['tglcheck1'].'" AND "'.$_REQUEST['tglcheck2'].'" ';
	}
	}

if ($_REQUEST['statusdata'])	{	$dua = 'AND fu_ajk_spak.status = "'.$_REQUEST['statusdata'].'"';	}
if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 1;	}	else {	$m = 0;		}
$met = $database->doQuery('SELECT
fu_ajk_spak.id,
fu_ajk_spak.id_cost,
fu_ajk_spak.id_polis,
fu_ajk_spak.spak,
DATE_FORMAT(fu_ajk_spak.input_date, "%Y-%m-%d") AS tglInput,
DATE_FORMAT(fu_ajk_spak.update_date, "%Y-%m-%d") AS tglApproveSPV,
fu_ajk_spak.ext_premi,
fu_ajk_spak.`status`,
fu_ajk_spak.keterangan,
fu_ajk_spak_form.nama,
fu_ajk_spak_form.cabang,
fu_ajk_spak_form.tgl_periksa,
fu_ajk_spak_form.dob,
fu_ajk_spak_form.x_usia,
fu_ajk_spak_form.plafond,
fu_ajk_spak_form.tenor,
fu_ajk_spak_form.tinggibadan,
fu_ajk_spak_form.beratbadan,
fu_ajk_spak_form.tekanandarah,
fu_ajk_spak_form.nadi,
fu_ajk_spak_form.pernafasan,
fu_ajk_spak_form.guladarah,
fu_ajk_spak_form.catatan,
fu_ajk_spak_form.pertanyaan6,
fu_ajk_spak_form.ket6,
fu_ajk_spak_form.kesimpulan
FROM
fu_ajk_spak
INNER JOIN fu_ajk_spak_form ON fu_ajk_spak.id_cost = fu_ajk_spak_form.idcost AND fu_ajk_spak.id = fu_ajk_spak_form.idspk
WHERE
fu_ajk_spak.id_cost = '.$mb['idbank'].' AND fu_ajk_spak.update_by = "'.$mb['id'].'" '.$satu.' '.$dua.'
ORDER BY fu_ajk_spak_form.tgl_periksa DESC LIMIT '.$m.', 1');

$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(fu_ajk_spak_form.id)
												   FROM fu_ajk_spak_form
												   INNER JOIN fu_ajk_spak ON fu_ajk_spak_form.idspk = fu_ajk_spak.id
												   LEFT JOIN fu_ajk_peserta ON fu_ajk_spak_form.idcost = fu_ajk_peserta.id_cost AND fu_ajk_spak.id_polis = fu_ajk_peserta.id_polis AND fu_ajk_spak.spak = fu_ajk_peserta.spaj
												   WHERE fu_ajk_spak.id_cost = '.$mb['idbank'].' AND fu_ajk_spak.update_by = "'.$mb['id'].'" '.$satu.' '.$dua.''));
	$totalRows = $totalRows[0];
	$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while ($met_ = mysql_fetch_array($met)) {
	$tgl_terima_spak = explode(" ", $met_['input_date']);
	$tolik = explode("/", $met_['tekanandarah']);

if ($met_['pertanyaan6']=="T") {	$pertanyaan6 = "Tidak";	}else{	$pertanyaan6 = "Iya";	}
if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
			  <td align="center">'.(++$no + ($pageNow-1) * 1).'</td>
			  <td>'.$met_['nama'].'</td>
			  <td>'.$met_['cabang'].'</td>
			  <td align="center">'.$met_['spak'].'</td>
			  <td align="center">'._convertDate($met_['tgl_periksa']).'</td>
			  <td align="center">'._convertDate($met_['tglApproveSPV']).'</td>
			  <td align="center">'._convertDate($met_['dob']).'</td>
			  <td align="center">'.$met_['x_usia'].'</td>
			  <td align="center">'.duit($met_['plafond']).'</td>
			  <td align="center">'.$met_['ext_premi'].'</td>
			  <td align="center">'.$met_['tenor'].'</td>
			  <td align="center">'.$met_['tinggibadan'].'</td>
			  <td align="center">'.$met_['beratbadan'].'</td>
			  <td align="center">'.$tolik[0].'</td>
			  <td align="center">'.$tolik[1].'</td>
			  <td align="center">'.$met_['nadi'].'</td>
			  <td align="center">'.$met_['pernafasan'].'</td>
			  <td align="center">'.$met_['guladarah'].'</td>
			  <td align="center">'.$pertanyaan6.'</td>
			  <td align="center">'.$met_['ket6'].'</td>
			  <td>'.$met_['catatan'].'</td>
			  <td>'.$met_['status'].'</td>
			  </tr>';
}
	echo '<tr><td colspan="22">';
	echo createPageNavigations($file = 'imob.php?ob=rSPK&re='.$_REQUEST['re'].'&id_cost='.$mb['idbank'].'&update_by='.$mb['id'].'&tglcheck1='.$_REQUEST['tglcheck1'].'&tglcheck2='.$_REQUEST['tglcheck2'].'&statusdata='.$_REQUEST['statusdata'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 1);
	echo '<b>Total Data SPK: <u>' . $totalRows . '</u></b></td></tr>';
	echo '</table>';
}
}
		;
		break;


case "tabgen":
echo '<script>
		function generator(){
			var time =
		}
	</script>';
echo '<center><form action="" method="POST">
	<input name="action" value="generate-code"
	<input type="submit" value="Generate">
	</form></center>
	<h1 id="output" style="margin-left: auto;margin-right:auto;text-align: center;border:1px solid black; width: 200px">';
$hour = date("H");
$month= date("m");
$token="ADONAI";
if(isset($_POST['action'])){
	if($_POST['action']=="generate-code"){
		$string = $hour.$month.$token;
		$code= md5($string);
		$code = substr($code,0,6);
		echo $code;
	}
}
echo '</h1>';
	;
	break;

	default:
echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
	<tr><td align="center" colspan="2"><font color="#ed2124" size="7"><img src="image/logo_adonai_1.gif" width="50"> A D O N A I </font> <font size="7">| Pialang Asuransi</font></td></tr>
	<tr><td align="center" colspan="2"><font color="#ffa800" size="5">Aplikasi Asuransi Jiwa Kredit dan Pensiunan</font><br /><br /><td></tr>
	</table>';
		;
} // switch

function IPnya() {
	$ipaddress = '';
	if (getenv('HTTP_CLIENT_IP'))
		$ipaddress = getenv('HTTP_CLIENT_IP');
	else if(getenv('HTTP_X_FORWARDED_FOR'))
		$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
	else if(getenv('HTTP_X_FORWARDED'))
		$ipaddress = getenv('HTTP_X_FORWARDED');
	else if(getenv('HTTP_FORWARDED_FOR'))
		$ipaddress = getenv('HTTP_FORWARDED_FOR');
	else if(getenv('HTTP_FORWARDED'))
		$ipaddress = getenv('HTTP_FORWARDED');
	else if(getenv('REMOTE_ADDR'))
		$ipaddress = getenv('REMOTE_ADDR');
	else
		$ipaddress = 'IP Tidak Dikenali';

	return $ipaddress;
}
$ipaddress = $_SERVER['REMOTE_ADDR'];
//$ipaddress = "150.107.149.27";
//echo "IP anda adalah : ";
//echo IPnya();
//echo "<br>Browser ";
//echo $_SERVER['HTTP_USER_AGENT'];
//echo "<br> Sistem Operasi :";
//echo php_uname();
//echo '<br />';
$mycountry = file('http://api.hostip.info/country.php?ip='.$ipaddress);
//echo $mycountry[0];
$metHistory = $database->doQuery('INSERT INTO fu_ajk_user_history SET iduser="'.$mb['id'].'",loginuser="'.$futgl.'",ipuser="'.IPnya().'",opuser="'.php_uname().'",browseruser="'.$_SERVER['HTTP_USER_AGENT'].'",countryuser="'.$mycountry[0].'"');
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
