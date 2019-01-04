<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("../includes/functions.php");
connect();

if (session_is_registered('nm_user')) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));	}
if ($q['status']=="") {
	$view_user = '';
	$viewProduk = '';
}else{
	$viewProduk = ' AND id_polis="'.$q['id_polis'].'"';
	if ($q['status']=="SUPERVISOR") {
		$cekStaff = mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE supervisor="'.$q['id'].'"'));
	$view_user = 'AND input_by="'.$cekStaff['nm_user'].'"';
	}else{
	$view_user = 'AND input_by="'.$q['nm_user'].'"';
	}
}
switch ($_REQUEST['er']) {
case "newupload_spk":
if ($_FILES['fnew_spk']['name']!="" AND
	$_FILES['fnew_spk']['type']!="application/pdf" AND
	$_FILES['fnew_spk']['type']!="image/jpeg" AND
	$_FILES['fnew_spk']['type']!="image/JPG")
	{	$errno ="File harus Format PDF !<br />";	}
	if ($_FILES['fnew_spk']['size'] / 1024 > $met_spaksize)	{	$errno ="File tidak boleh lebih dari 1Mb !<br />";	}
	if ($errno) {	echo '<tr><td colspan="5" align="center"><font color="red">'.$errno.'</font><meta http-equiv="refresh" content="2; url=ajk_md_spk.php?er=e_spk"></td></tr>';	}
	else{
	//echo $_REQUEST['newspk'].'<br />';
	//echo $_FILES['fnew_spk']['name'].'<br />';
	move_uploaded_file($_FILES['fnew_spk']['tmp_name'], $metpath . $_FILES["fnew_spk"]["name"]);
	//fu_ajk_spak_history
	$metoldspk = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak WHERE id="'.$_REQUEST['newspk'].'"'));
	$metnewspk = $database->doQuery('INSERT INTO fu_ajk_spak_history SET idspk="'.$_REQUEST['newspk'].'",
																 			 nomorspk="'.$metoldspk['spak'].'",
																			 fname="'.$metoldspk['fname'].'",
																 			 input_by="'.$q['nm_user'].'",
																 			 input_time="'.$futgl.'"');
	$metnewspk = $database->doQuery('UPDATE fu_ajk_spak SET fname="'.$_FILES['fnew_spk']['name'].'" WHERE id="'.$_REQUEST['newspk'].'"');

	$mail = new PHPMailer; // call the class
	$mail->IsSMTP();
	$mail->Host = SMTP_HOST; //Hostname of the mail server
	$mail->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
	$mail->SetFrom ($q['email'], $q['nm_lengkap']); //From address of the mail
	$mail->Subject = "AJKOnline - REVISI FORM DATA SPK"; //Subject od your mail
	$met_uw = mysql_fetch_array($database->doQuery('SELECT nm_lengkap, email FROM pengguna WHERE id_cost="" AND status="UNDERWRITING"'));
	$message .='<table width="100%"><tr><td>To '.$met_uw['nm_lengkap'].', <br /> Nomor SPK '.$metoldspk['spak'].' telah direvisi oleh <b>'.$_SESSION['nm_user'].' pada tanggal '.$futgl.'</td></tr></table>';
	$mail->AddCC("kepodank@gmail.com");
	$mail->MsgHTML($message); //Put your body of the message you can place html code here
	$send = $mail->Send(); //Send the mails
	//echo $message;
	//SEND SMTPMAIL//
	echo '<div class="title2" align="center">Revisi data SPK telah berhasil diupload oleh <b>'.$q['nm_lengkap'].'</b> pada tanggal '.$futgldn.' !</div><meta http-equiv="refresh" content="3; url=ajk_md_spk.php">';
	}

	;
	break;


	case "e_spk":
echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="95%" align="left">Modul Edit File Data SPK</font></th><th><a title="Kembali ke Modul SPK" href="ajk_md_spk.php"><img src="image/Backward-64.png" width="25"></a></th></tr></table>';
echo '<fieldset style="padding: 2">
		<legend align="center">S e a r c h</legend>
		<table border="0" width="100%" cellpadding="1" cellspacing="1">
		<form method="post" action="">
		<tr><td width="30%" align="right">Nama Perusahaan :</td>
		<td width="30%">';
		$quer2=mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$q['id_cost'].'"'));
		echo  $quer2['name'];
echo '</td></tr>
				<tr><td align="right">Nama Produk :</td>
				<td> ';
		if ($q['id_polis']!="") {
			$quer1=mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$q['id_polis'].'"'));
			echo $quer1['nmproduk'].' ('.$quer1['nopol'].')';
			$met_reg = $database->doQuery('SELECT * FROM fu_ajk_regional WHERE id_cost="'.$q['id_cost'].'"');
			$kolomregional .= '<tr><td align="right">Regional :</td>
			<td><select id="id_cost" name="cat" onchange="reload(this.form)">
			<option value="">--- Pilih ---</option>';
			$met_cost=$database->doQuery('SELECT * FROM fu_ajk_regional WHERE id_cost="'.$q['id_cost'].'" ORDER BY name ASC');
			while($met_cost_ = mysql_fetch_array($met_cost)) {
				$kolomregional .= '<option value="'.$met_cost_['name'].'"'._selected($_REQUEST['cat'], $met_cost_['name']).'>'.$met_cost_['name'].'</option>';
			}
			$kolomregional .= '</select></td></tr>';
		}else{
			$quer1=$database->doQuery('SELECT * FROM fu_ajk_polis WHERE id_cost="'.$q['id_cost'].'"');
echo '<select id="id_cost" name="cat">
			<option value="">--- Pilih ---</option>';
			while($quer1_ = mysql_fetch_array($quer1)) {
				echo  '<option value="'.$quer1_['id'].'"'._selected($_REQUEST['cat'], $quer1_['id']).'>'.$quer1_['nmproduk'].'</option>';
			}
			echo '</select>';
			$kolomregional .= '<tr><td align="right">Regional :</td>
			<td>'.$q['wilayah'].'</td></tr>';
		}
		echo '</td></tr>';
$status_spk = $database->doQuery('SELECT DISTINCT status FROM fu_ajk_spak ');
echo '</select></td></tr>
	<tr><td align="right">Nomor SPK <font color="red">*</font>:</td><td><input type="text" name="nospk" value="'.$_REQUEST['nospk'].'"></td></tr>
	<tr><td align="center" colspan="2"><input type="submit" name="cariSPK" value="Cari" class="button"></td></tr>
	</form></table></fieldset>';
if ($_REQUEST['cariSPK']=="Cari") {
	$_REQUEST['nospk'] = $_POST['nospk'];
	if (!$_REQUEST['nospk'])  $error .='<blink><font color=red>Nomor SPK tidak boleh kosong</font></blink><br>';
if ($error){
	echo '<center>'.$error.'<center>';
}else{
if ($_REQUEST['nospk'])								{	$satu = 'AND spak = "' . $_REQUEST['nospk'] . '"';		}
$met = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak WHERE id_cost="'.$q['id_cost'].'" AND id_polis="'.$q['id_polis'].'" '.$satu.' '.$view_user.' AND status="Aktif"'));
if (!$met)	{	echo '<font color="red"><center>Data yang anda cari tidak ada !</center></font>';	}
else	{
$met_spk = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_form WHERE idcost="'.$q['id_cost'].'" AND idspk="'.$met['id'].'"'));
echo '<form name="f1" method="post" enctype="multipart/form-data" action="">
	  <input type="hidden" name="newspk" value="'.$met_spk['id'].'">
	  <table border="0" cellpadding="0" cellspacing="0" width="100%">
	  <tr><td align="center" colspan="2"><embed src="'.$metpath.''.$met['fname'].'" width="850" height="550"/></td></tr>
	  <tr><td colspan="2"><input type="hidden" name="idspk" value="'.$met['id'].'"></td></tr>
	  <tr><td width="50%" align="right">Nomor SPK</td><td>: '.$met['spak'].'</td></tr>
	  <tr><td align="right">Nama</td><td>: '.$met_spk['nama'].'</td></tr>
	  <tr><td align="right">Tanggal Lahir</td><td>: '._convertDate($met_spk['dob']).'</td></tr>
	  <tr><td align="right">File Baru SPK <font color="red">*</font></td><td>: <input name="fnew_spk" type="file" size="50"></td></tr>
	  <tr><td colspan="2" align="center"><input type="hidden" name="er" value="newupload_spk"><input type="submit" name="upload_spk" value="Upload"></td></tr>
	  </table></form>';
	}
}
}
		;
		break;

	case "v_spk":
$met = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak WHERE id="'.$_REQUEST['id'].'"'));
$metFormSPK = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_form WHERE idspk="'.$met['id'].'" AND del IS NULL'));
if ($metFormSPK['jns_kelamin']=="M") {	$gender = "Laki-Laki";	}else{	$gender = "Perempaun";	}
$met_costumer = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$met['id_cost'].'"'));
$met_polis = mysql_fetch_array($database->doQuery('SELECT id, nmproduk FROM fu_ajk_polis WHERE id="'.$met['id_polis'].'"'));

//$metdata_form = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_form WHERE idspk="'.$met['id'].'" AND del IS NULL'));
		//CEK STATUS DATA SPK
if ($met['status']=="Batal" OR $met['status']=="Tolak") {
	$statusspknya = '<font color="red">'.$met['status'].'</font>';
	$metStatusPremi = 0;
	$mettotalpremibayangan = 0;
}	else	{
    $cekrate = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="' . $q['id_cost'] . '" AND id_polis="' . $q['id_polis'] . '" AND usia="' . $metFormSPK['x_usia'] . '" AND tenor="' . $metFormSPK['tenor'] . '"')); // RATE PREMI
	$premi = $metFormSPK['plafond'] * $cekrate['rate'] / 1000;

	$metStatusPremi = $premi;
	$metExtPremi = $premi * $met['ext_premi'] / 100;	//HITUNG EXTRA PREMI

	$statusspknya = '<font color="blue">'.$met['status'].'</font>';
	$mettotalpremibayangan = $metFormSPK['x_premi'];
}
		//CEK STATUS DATA SPK

//INPUT USER
if (is_numeric($metFormSPK['input_by'])) {
	$met_User = mysql_fetch_array(mysql_query('SELECT id, nama, namalengkap FROM user_mobile WHERE id="'.$metFormSPK['input_by'].'"'));
	$inputby_met = $met_User['namalengkap'];

	$met_Cabang = mysql_fetch_array(mysql_query('SELECT id, name FROM fu_ajk_cabang WHERE id="'.$metFormSPK['cabang'].'"'));
	$metCabangnya = $met_Cabang['name'];
}else{
	$inputby_met = $metFormSPK['input_by'];
	$metCabangnya = $metFormSPK['cabang'];
}
//INPUT USER
echo '<table border="0" width="80%" cellpadding="3" cellspacing="1" align="center">
	  <tr><td width="20%">Nama Perusahaan</td><td width="75%">: <b>'.$met_costumer['name'].'</b></td>
	  	  <td align="center"><a title="Kembali ke halaman sebelumnya" href="ajk_md_spk.php"><img src="image/Backward-64.png" width="20"></a></td>
	  	  <td align="center"><a title="print data SPK" href="aajk_report.php?er=_spk&ids='.$met['id'].'" target="_blank"><img src="image/print.png" width="20"></a></td>
	  </tr>
	  <tr><td>Nama Produk</td><td colspan="3">: <b>'.$met_polis['nmproduk'].'</b></td></tr>
	  <tr><td>Nomor SPK</td><td colspan="3">: <b>'.$met['spak'].' ('.$statusspknya.')</b></td></tr>
	  </table>';

if ($metFormSPK['filefotodebiturdua']!="") {
	$mamet_photonya = '<a href="../ajkmobilescript/'.$metFormSPK['filefotodebiturdua'].'" rel="lightbox" ><img src="../ajkmobilescript/'.$metFormSPK['filefotodebiturdua'].'" width="100"></a>';
}
else{
	$mamet_photonya = '<img src="image/non-user.png" width="175">';
}
echo '<table border="0" width="80%" cellpadding="1" cellspacing="1" align="center">
	  <tr><td width="20%">Nama Upload SPK</td><td colspan="2">: '.strtoupper($inputby_met).'</td></tr>
	  <tr><th colspan="3" bgcolor="#DEDEDE">Data Nasabah</td></tr>
	  <tr><td>Nama Nasabah</td><td>: <b>'.strtoupper($metFormSPK['nama']).'</b></td><td width="15%" rowspan="6" valign="top" align="center">'.$mamet_photonya.'</td></tr>
	  <tr><td>Jenis kelamin</td><td>: '.$gender.'</td></tr>
	  <tr><td>Tanggal Lahir</td><td>: '._convertDate($metFormSPK['dob']).'</td></tr>
	  <tr><td>Usia</td><td>: '.duit($metFormSPK['x_usia']).' tahun</td></tr>
	  <tr><td>Alamat</td><td>: '.nl2br($metFormSPK['alamat']).'</td></tr>
	  <tr><td>Pekerjaan</td><td>: '.$metFormSPK['pekerjaan'].'</td></tr>';
//start 150908 modify by satrya
//end
if($q['id_cost']!="" AND $q['status']=="ASURANSI"){
echo '<tr><th colspan="3" bgcolor="#DEDEDE">Data Pernyataan</th><t/tr>
	  <tr><td colspan="3">Apakah anda dalam jangka 5 tahun terakhir ini pernah atau sedang menderita penyakit, Asma, Cacat, Tumor/Kanker, TBC, Kencing manis , Hati, Ginjal, Jantung, Stroke, Tekanan Darah Tinggi, Epilepsi, Gangguan Jiwa, Penyakit Autoimun, Keterbelakngan Mental atau Idiot. Jika "Ya", Jelaskan.</td></tr>
	  <tr><td valign="top" colspan="3">Jawaban : '.$metFormSPK['pertanyaan1'].'<br />'.$metFormSPK['ket1'].'</td></tr>
	  <tr><td colspan="3">Apakah Berat badan Anda berubah dalam 12 bulan terakhir ini, Jika "Ya", Jelaskan.</td></tr>
	  <tr><td valign="top" colspan="3">Jawaban : '.$metFormSPK['pertanyaan2'].'<br />'.$metFormSPK['ket2'].'</td></tr>
	  <tr><td colspan="3">Apakah anda menderita HIV/AIDS?</td></tr>
	  <tr><td valign="top" colspan="3">Jawaban : '.$metFormSPK['pertanyaan3'].'<br />'.$metFormSPK['ket3'].'</td></tr>
	  <tr><td colspan="3">Apakah anda mengkonsumsi rutin (ketergantungan) pada Narkoba? Jika "Ya", Sebutkan jenisnya?</td></tr>
	  <tr><td valign="top" colspan="3">Jawaban : '.$metFormSPK['pertanyaan4'].'<br />'.$metFormSPK['ket4'].'</td></tr>
	  <tr><td colspan="3"><b>Khusus untuk Wanita</b>, apakah anda sedang hamil ? Jika "Ya" adalah komplikasi / penyakit kehamilan ? jelaskan? Usia Kandungan?</td></tr>
	  <tr><td valign="top" colspan="3">Jawaban : '.$metFormSPK['pertanyaan5'].'<br />'.$metFormSPK['ket5'].'</td></tr>
	  <tr><td colspan="3">Apakah anda seorang perokok? Jika "Ya" berapa batang perhari?</td></tr>
	  <tr><td valign="top" colspan="3">Jawaban : '.$metFormSPK['pertanyaan6'].'<br />'.$metFormSPK['ket6'].'</td></tr>
	  <tr><th colspan="3" bgcolor="#DEDEDE">Data Cek Medical</th><t/tr>
	  <tr><td>Tinggi Badan</td><td colspan="2">: '.$metFormSPK['tinggibadan'].'</td></tr>
	  <tr><td>Berat Badan</td><td colspan="2">: '.$metFormSPK['beratbadan'].'</td></tr>
	  <tr><td>Tekanan Darah</td><td colspan="2">: '.$metFormSPK['tekanandarah'].'</td></tr>
	  <tr><td>Nadi</td><td colspan="2">: '.$metFormSPK['nadi'].'</td></tr>
	  <tr><td>Pernafasan</td><td colspan="2">: '.$metFormSPK['pernafasan'].'</td></tr>
	  <tr><td>Gula Darah</td><td colspan="2">: '.$metFormSPK['guladarah'].'</td></tr>
	  <tr><td>Kesimpulan</td><td colspan="2">: '.nl2br($metFormSPK['kesimpulan']).'</td></tr>
	  <tr><td>Catatan</td><td colspan="2">: '.$metFormSPK['catatan'].'</td></tr>
	  <tr><td>Tanggal Periksa</td><td colspan="2">: '._convertDate($metFormSPK['tgl_periksa']).'</td></tr>';
}
if($q['id_cost']!="" AND $q['status']=="STAFF" OR $q['status']=="SUPERVISOR"  OR $q['status']==""){
echo '<tr><th colspan="3" bgcolor="#DEDEDE">Data Peminjaman Asuransi</th><t/tr>
	  <tr><td>Plafond</td><td>: '.duit($metFormSPK['plafond']).'</td></tr>
	  <tr><td>Tanggal Asuransi</td><td colspan="2">: '._convertDate($metFormSPK['tgl_asuransi']).'</td></tr>
	  <tr><td>Tenor</td><td colspan="2">: '.$metFormSPK['tenor'].' tahun</td></tr>
	  <tr><td>Tanggal Akhir Asuransi</td><td colspan="2">: '._convertDate($metFormSPK['tgl_akhir_asuransi']).'</td></tr>
	  <tr><td>Premi</td><td colspan="2">: '.duit($metStatusPremi).'</td></tr>
	  <tr><td>Ext. Premi (%)</td><td colspan="2">: '.duit($met['ext_premi']).'%</td></tr>
	  <tr><td>Keterangan EM</td><td colspan="2">: '.$met['ket_ext'].'</td></tr>
	  <tr><td>Total Premi</td><td colspan="2">: '.duit($mettotalpremibayangan).'</td></tr>
	  <tr><td>Cabang</td><td colspan="2">: '.$metCabangnya.'</td></tr>
	  <tr><td>Input User</td><td colspan="2">: '.strtoupper($inputby_met).'</td></tr>
	  <tr><td>Tanggal Input Data</td><td colspan="2">: '.$metFormSPK['input_date'].'</td></tr>';
}
echo '</table>';
		;
		break;

	default:
echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="95%" align="left">Modul File Data SPK</font></th><th><a title="Edit file SPK" href="ajk_md_spk.php?er=e_spk"><img src="image/book-edit.png"></a></th></tr></table>';
echo '<fieldset style="padding: 2">
		<legend align="center">S e a r c h</legend>
		<table border="0" width="100%" cellpadding="1" cellspacing="1">
		<form method="post" action="">
		<tr><td width="30%" align="right">Nama Perusahaan :</td>
		<td width="30%">';
$quer2=mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$q['id_cost'].'"'));
echo  $quer2['name'];
echo '</td></tr>
		<tr><td align="right">Nama Produk :</td>
		<td> ';
		if ($q['id_polis']!="") {
		$quer1=mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$q['id_polis'].'"'));
echo $quer1['nmproduk'].' ('.$quer1['nopol'].')';
		$met_reg = $database->doQuery('SELECT * FROM fu_ajk_regional WHERE id_cost="'.$q['id_cost'].'"');
		$kolomregional .= '<tr><td align="right">Regional :</td>
		<td><select id="id_cost" name="cat" onchange="reload(this.form)">
		<option value="">--- Pilih ---</option>';
		$met_cost=$database->doQuery('SELECT * FROM fu_ajk_regional WHERE id_cost="'.$q['id_cost'].'" ORDER BY name ASC');
		while($met_cost_ = mysql_fetch_array($met_cost)) {
		$kolomregional .= '<option value="'.$met_cost_['name'].'"'._selected($_REQUEST['cat'], $met_cost_['name']).'>'.$met_cost_['name'].'</option>';
		}
		$kolomregional .= '</select></td></tr>';
		}else{
		$quer1=$database->doQuery('SELECT * FROM fu_ajk_polis WHERE id_cost="'.$q['id_cost'].'"');
echo '<select id="id_cost" name="cat">
		<option value="">--- Pilih ---</option>';
		while($quer1_ = mysql_fetch_array($quer1)) {
echo  '<option value="'.$quer1_['id'].'"'._selected($_REQUEST['cat'], $quer1_['id']).'>'.$quer1_['nmproduk'].'</option>';
		}
echo '</select>';
		$kolomregional .= '<tr><td align="right">Regional :</td>
		<td>'.$q['wilayah'].'</td></tr>';
		}
echo '</td></tr>';
//echo $kolomregional;
/*
echo '<tr><td align="right">Cabang :</td>
		<td><select id="subcat" name="subcat">
		<option value="">--- Pilih ---</option>';
		if ($q['id_polis']!="") {
		$cek_regionalnya = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE name="'.$_REQUEST['cat'].'"'));
		$rreg=$database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id_cost="'.$q['id_cost'].'" AND id_reg="'.$cek_regionalnya['id'].'" ORDER BY name ASC');
		}
		elseif ($q['id_polis']=="" AND $q['level']!="10") {
		$rreg=$database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id_cost="'.$q['id_cost'].'" ORDER BY name ASC');
		}
		else{
		$cek_regionalnya = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE name="'.$q['wilayah'].'"'));
		$rreg=$database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id_cost="'.$q['id_cost'].'" AND id_reg="'.$cek_regionalnya['id'].'" ORDER BY name ASC');
		}
		while($freg = mysql_fetch_array($rreg)) {
		echo  '<option value="'.$freg['name'].'"'._selected($_REQUEST['subcat'], $freg['name']).'>'.$freg['name'].'</option>';
		}
*/
$status_spk = $database->doQuery('SELECT DISTINCT status FROM fu_ajk_spak WHERE status="Aktif" OR status="Batal"');
echo '</select></td></tr>
		<tr><td align="right">Nomor SPK :</td><td><input type="text" name="nospk" value="'.$_REQUEST['nospk'].'"></td></tr>
		<tr><td align="right">Status Data :</td><td><select id="subcat" name="statusnya">
													<option value="">--- Pilih ---</option>';
while ($status_spk_ = mysql_fetch_array($status_spk)) {
echo  '<option value="'.$status_spk_['status'].'"'._selected($_REQUEST['statusnya'], $status_spk_['status']).'>'.$status_spk_['status'].'</option>';
}
echo '</select>
		</td></tr>
		<tr><td align="center" colspan="2"><input type="submit" name="button" value="Cari" class="button"></td></tr>
		</form></table></fieldset>';

echo '<table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#bde0e6">
	 <tr><th width="1%">No</th>
	 	 <th width="15%">Nama Peserta</th>
	 	 <th width="5%">SPK</th>
	 	 <th width="5%">Plafond</th>
	 	 <th width="5%">Premi</th>
	 	 <th width="1%">Ex.Premi(%)</th>
	 	 <th width="5%">Total Premi</th>
	 	 <th>Nama File</th>
	 	 <th width="15%">Keterangan</th>
	 	 <th>User Upload</th>
	 	 <th width="1%">Tgl Upload</th>
	 	 <th>User Approve</th>
	 	 <th width="1%">Tgl Approve</th>
	 	 <th width="5%">Status</th>
	 </tr>';
if ($_REQUEST['nospk'])								{	$satu = 'AND spak LIKE "%' . $_REQUEST['nospk'] . '%"';		}
if ($_REQUEST['statusnya'])							{	$dua = 'AND status LIKE "%' . $_REQUEST['statusnya'] . '%"';		}

if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}
$met = $database->doQuery('SELECT * FROM fu_ajk_spak WHERE id_cost="'.$q['id_cost'].'" '.$viewProduk.' '.$view_user.' '.$satu.' '.$dua.' AND  status!="Proses" AND status!="Approve" ORDER BY id DESC LIMIT ' . $m . ' , 25');
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_spak WHERE id !="" AND  id_cost="'.$q['id_cost'].'"  '.$viewProduk.' '.$view_user.' '.$satu.' '.$dua.' AND  status!="Proses" AND status!="Approve"'));
$totalRows = $totalRows[0];
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while ($met_ = mysql_fetch_array($met)) {
$metdata = mysql_fetch_array($database->doQuery('SELECT id, id_cost, spaj, type_data, nama FROM fu_ajk_peserta WHERE type_data="SPK" AND spaj="'.$met_['spak'].'" AND del IS NULL'));
$metdata_form = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_form WHERE idspk="'.$met_['id'].'" AND del IS NULL'));
if ($metdata['spaj']==$met_['spak']) {	$_datamet = $metdata['nama'];	}else{	$_datamet = '';	}

$mettotalpremibayangan = $metdata_form['x_premi'] + ($metdata_form['x_premi'] * $met_['ext_premi'] / 100);

$met_userupload = explode(" ", $met_['input_date']);
$met_userapprove = explode(" ", $met_['update_date']);

	//CEK STATUS DATA SPK
if ($met_['status']=="Batal" OR $met_['status']=="Tolak") {
	$statusspknya = '<font color="red">'.$met_['status'].'</font>';
	$metStatusPremi = 0;
}	else	{
	$metStatusPremi = $metdata_form['x_premi'];
	$statusspknya = '<font color="blue">'.$met_['status'].'</font>';
}
	//CEK STATUS DATA SPK

if (($no % 2) == 1)	$objlass = 'tbl-odd'; else				$objlass = 'tbl-even';

if (is_numeric($met_['input_by'])) {
	$met_User = mysql_fetch_array(mysql_query('SELECT id, nama, namalengkap FROM user_mobile WHERE id="'.$met_['input_by'].'"'));
	$inputby_met = $met_User['namalengkap'];
}else{
	$inputby_met = $met_['input_by'];
}

if (is_numeric($met_['update_by'])) {
	$met_UserUpd = mysql_fetch_array(mysql_query('SELECT id, nama, namalengkap FROM user_mobile WHERE id="'.$met_['update_by'].'"'));
	$Updateby_met = $met_UserUpd['namalengkap'];
}else{
	$Updateby_met = $met_['update_by'];
}
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	  <td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
		<td>'.$_datamet.'</td>
		<td align="center"><a title="Preview data SPK" href="ajk_md_spk.php?er=v_spk&id='.$met_['id'].'">'.$met_['spak'].'</a></td>
		<td align="right">'.duit($metdata_form['plafond']).'</td>
		<td align="right">'.duit($metStatusPremi).'</td>
		<td align="center">'.$met_['ext_premi'].'</td>
		<td align="right">'.duit($mettotalpremibayangan).'</td>
	    <td><a href="ajk_file/_spak/'.$met_['fname'].'" target="_blank">'.$met_['fname'].'</a></td>
		<td>'.nl2br($met_['ket_ext']).'</td>
	    <td>'.$inputby_met.'</td>
	    <td align="center">'._convertDate($met_userupload[0]).'</td>
		<td>'.$Updateby_met.'</td>
	    <td align="center">'._convertDate($met_userapprove[0]).'</td>
	    <td align="center">'.$statusspknya.'</td>
	  </tr>';
}
echo '<tr><td colspan="22">';
echo createPageNavigations($file = 'ajk_md_spk.php?nospk='.$_REQUEST['nospk'].'&statusnya='.$_REQUEST['statusnya'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
echo '<b>Total Data SPK: <u>' . $totalRows . '</u></b></td></tr>';
echo '</table>';
		;
} // switch
?>
<SCRIPT language=JavaScript>
function reload(form)
{
	var val=form.cat.options[form.cat.options.selectedIndex].value;
	self.location='ajk_md_spk.php?cat=' + val;
}
</script>