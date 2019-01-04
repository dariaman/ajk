<?php
include_once ("ui.php");
connect();
if (session_is_registered('nm_user')) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));	}
$futgl = date("Y-m-d g:i:a");
$futglidprm = date("Y");
$futglprm = date("Y-m-d");
switch ($_REQUEST['r']) {
	case "approve_paid":
$metcostumer = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$_REQUEST['idc'].'"'));
$met_uplaodPaid = $database->doQuery('INSERT INTO fu_ajk_paidfile SET id_cost="'.$_REQUEST['idc'].'",
																	  nmfile="'.$_REQUEST['FLpaid'].'",
																	  input_by="'.$q['nm_user'].'",
																	  input_date="'.$futgl.'"');

	$mail = new PHPMailer; // call the class
	$mail->IsSMTP();
	$mail->Host = SMTP_HOST; //Hostname of the mail server
	$mail->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
	$mail->Username = SMTP_UNAME; //Username for SMTP authentication any valid email created in your domain
	$mail->Password = SMTP_PWORD; //Password for SMTP authentication
	$mail->AddReplyTo = SMTP_REPLYMAIL; //reply-to address
	$mail->SetFrom ($q['email'], $q['nm_lengkap']); //From address of the mail
	$mail->Subject = "AJKOnline - UPLOAD DATA PEMBAYARAN"; //Subject od your mail
	//EMAIL SPV ARM
	$mailSPVarm = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="" AND level="6" AND status="ARM"');
while ($mailSPVarm_ = mysql_fetch_array($mailSPVarm)) {
	$mail->AddAddress($mailSPVarm_['email'], $mailSPVarm_['nm_lengkap']); //To address who will receive this email
	$spvARM = $mailSPVarm_['nm_lengkap'];
}
	$message .='<table width="100%">
				<tr><td>To '.$spvARM.', </td></tr>
				<tr><td>Data pembayaran premi '.$metcostumer['name'].' telah diupload oleh <b>'.$_SESSION['nm_user'].'</b> pada tanggal '.$futgl.'</td></tr>
				<tr><td>Segera dilakukan pengecekan data untuk mengupdate pembayaran premi.</td></tr>
				<tr><td>Salam, <br />'.$q['nm_lengkap'].'</td></tr>
				</table>';
	//EMAIL SPV SPK
	$mail->AddCC("rahmad@relife.co.id");
	$mail->MsgHTML($message); //Put your body of the message you can place html code here
	$send = $mail->Send(); //Send the mails
	//echo $message;
echo '<center><h2>Data pembayaran premi telah berhasil diupload oleh '.$q['nm_lengkap'].' pada tanggal '.$futgl.'.</h2></center><meta http-equiv="refresh" content="3;URL=ajk_prm_payment_dn.php?r=paidData">';
	;
	break;

	case "cancell_paid":
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%"><tr><th width="100%" align="left">Modul Upload Data Pembayaran</font></th></tr></table>';
unlink($metpath_file.$_REQUEST['FLpaid']);
header("location:ajk_prm_payment_dn.php?r=upl_paid");
		;
		break;

	case "Upload":
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%"><tr><th width="100%" align="left">Modul Upload Data Pembayaran</font></th></tr></table>';
$_REQUEST['cat'] = $_POST['cat'];					if (!$_REQUEST['cat'])  		$error1 .='<font color="red" size="2">Silahkan pilih nama perusahaan</font><br />.';
$_REQUEST['bataskolom'] = $_POST['bataskolom'];		if (!$_REQUEST['bataskolom'])	$error2 .='<font color="red" size="2">Silahkan tentukan batas kolom file</font><br />.';
if (!$_FILES['userfile']['tmp_name'])  $error3 .='<font color="red" size="2">Silahkan upload file excel pembayaran</font>.';
	$allowedExtensions = array("xls","xlsx","csv");
	foreach ($_FILES as $file) {
		if ($file['tmp_name'] > '') {
			if (!in_array(end(explode(".",	strtolower($file['name']))), $allowedExtensions)) {
				die('<center><font color=red>'.$file['name'].' <br /><blink>File extension tidak diperbolehkan selain excel!</blink></font><br/>'.'<a href="ajk_prm_payment_dn.php?r=upl_paid">'.'&lt;&lt Go Back</a></center>');
			}
		}
	}
if ($error1 OR $error2 OR $error3 ){
echo '<center>'.$error1.''.$error2.''.$error3.'<br /><a href="ajk_prm_payment_dn.php?r=upl_paid">'.'&lt;&lt Go Back</a></center>';
}
else
{
$rep_nameFile = str_replace(" ", "_", $_FILES['userfile']['name']);
$met_file_paid = 'Data_Pembayaran_'.$futoday.'_'.$rep_nameFile;

if(file_exists($metpath_file.$met_file_paid)){	die('<center><font color="red">File sudah pernah diupload</font><br/>'.'<a href="ajk_prm_payment_dn.php?r=upl_paid">'.'&lt;&lt Go Back</a></center>');	}

$fu = mysql_fetch_array($database->doQuery('SELECT fu_ajk_costumer.name, fu_ajk_costumer.id FROM fu_ajk_costumer WHERE id="'.$_REQUEST['cat'].'"'));
echo '<form method="post" action="" enctype="multipart/form-data">
	<table border="0" width="100%" cellpadding="1" cellspacing="1">
	<tr><td colspan="2"><input type="hidden" name="cat" value="'.$_REQUEST['cat'].'">Nama Perusahaan</td><td colspan="24">: <b>'.$fu['name'].'</b></td></tr>
	<tr><td colspan="2"><input type="hidden" name="bataskolom" value="'.$_REQUEST['bataskolom'].'">
	<tr><td colspan="2"><input type="hidden" name="namafileexl" value="'.$_FILES['userfile']['name'].'">Nama File Upload</td><td colspan="24">: <b>'.$_FILES['userfile']['name'].'</b></td></tr>
	<tr><th width="1%">No</th>
		<th width="15%">ID Peserta</th>
		<th>Nama</th>
		<th width="15%">Premi</th>
		<th width="15%">Tanggal Pembayaran</th>
	</tr>';
	$data = new Spreadsheet_Excel_Reader($_FILES['userfile']['tmp_name']);
	$baris = $data->rowcount($sheet_index=0);											//MEMBACA JUMLAH BARIS DATA EXCEL

for ($i=7; $i<=$_REQUEST['bataskolom']; $i++)
{
	$data1=$data->val($i, 1);		//NO
	$data2=$data->val($i, 2);		//ID PESERTA
	$data3=$data->val($i, 3);		//NAMA
	$data4=$data->val($i, 4);		//TOTAL PREMI
	$data5=$data->val($i, 5);		//TANGGAL BAYAR

$met_premi = str_replace(",","", $data4);

$met_rekonpaid = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_cost="'.$_REQUEST['cat'].'" AND id_peserta="'.$data2.'"'));
if ($data2=="") {	$error1 = '<font color="red">Error</font>';		$erkolom1 = $error1;	}
else{
	if ($met_rekonpaid['id_peserta']!=$data2) {	$error = '<font color="red">ID Peserta tidak ada</font>';	$erkolom1 = $error1;	}	else	{	$erkolom1 = $data2;	}
}

if ($data3=="") {	$error2 = '<font color="red">Error</font>';	$erkolom2 = $error2;	}
else{
if ($met_rekonpaid['id_peserta']==$data2 AND $met_rekonpaid['nama'] != $data3) {	$error = '<font color="red">Nama peserta tidak sama</font>';	$erkolom2 = $error2;		}	else	{	$erkolom2 = $data3;	}
}

if ($data4=="") {	$error3 = '<font color="red">Error</font>';	$erkolom3 = $error3;}
else{
if ($met_rekonpaid['id_peserta']==$data2 AND $met_rekonpaid['nama'] == $data3 AND $met_rekonpaid['totalpremi'] != $met_premi) {	$error = '<font color="red">Premi pembayaran tidak sama</font>';	$erkolom3 = $error3;		}	else	{	$erkolom3 = $data4;	}
}

if ($data5=="") {	$error4 = '<font color="red">Error</font>';	$erkolom4 = $error4;	}else{	$erkolom4 = $data5;	}
if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		<td align="center">'.++$no.'</td>
		<td align="center">'.$erkolom1.'</td>
		<td>'.$erkolom2.'</td>
		<td align="center">'.$erkolom3.'</td>
		<td align="center">'.$erkolom4.'</td>
	  </tr>';
}
if ($error) {	echo '<tr><td colspan="5" align="center"><font color="red"><blink>Silahkan lengkapi kolom yang error !!!</blink><br /><a href="ajk_prm_payment_dn.php?r=cancell_paid&FLpaid='.$met_file_paid.'">Back</a></font></td></tr>';	}
else		{	echo '<tr><td colspan="5" align="center"><a title="Approve data upload pembayaran" href="ajk_prm_payment_dn.php?r=approve_paid&FLpaid='.$met_file_paid.'&dateupl='.$futgl.'&idc='.$_REQUEST['cat'].'" onClick="if(confirm(\'Apakah anda sudah yakin dengan semua data pembayaran ini ?\')){return true;}{return false;}"><img src="image/save.png" border="0" width="30"></a>
	 					   &nbsp; &nbsp; <a title="Batal data upload pembayaran" href="ajk_prm_payment_dn.php?r=cancell_paid&FLpaid='.$met_file_paid.'"><img src="image/deleted.png" border="0" width="30"></a></td></tr>';	}
echo '</table></form>';

move_uploaded_file($_FILES['userfile']['tmp_name'], $metpath_file. $met_file_paid);
}
		;
		break;

	case "upl_paid":
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
	  <tr><th width="100%" align="left">Modul Upload Data Pembayaran</font></th>
	  	  <th width="1%" align="center"><a href="ajk_prm_payment_dn.php?r=paidData"><img src="image/back.png" width="25"></a></th>
	  </tr></table>';
$fu = mysql_fetch_array($database->doQuery('SELECT fu_ajk_costumer.name, fu_ajk_costumer.id FROM fu_ajk_costumer WHERE name="'.$qsescost['name'].'"'));
echo '<form name="f1" method="post" enctype="multipart/form-data" action="">
		<table border="0" width="60%" align="center">
		<tr><td width="15%" align="right">Nama Perusahaan <font color="red">*</font></td>
			<td width="30%">: <select name="cat">
			<option value="">---Perusahaan---</option>';
$quer2=$database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name ASC');
while($noticia2 = mysql_fetch_array($quer2)) {
echo  '<option value="'.$noticia2['id'].'">'.$noticia2['name'].'</option>';
}
echo '</select></td></tr>
	  <tr><td align="right">Silakan Pilih File Excel <font color="red">*</font></td><td>: <input name="userfile" type="file" size="50" onchange="checkfile(this);" ></td></tr>
	  <tr><td align="right">Batas Akhir Baris <font color="red">*</font></td><td>: <input type="text" name="bataskolom" value="'.$_REQUEST['bataskolom'].'" size="1"></td></tr>
	  <tr><td align="center"colspan="2"><input name="r" type="submit" value="Upload"></td></tr>
	  </table></form>';
;
		;
		break;

	case "paidData":
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
	  <tr><th width="99%" align="left">Modul Upload Data Pembayaran</th>
	  	  <th width="1%" align="center"><a href="ajk_prm_payment_dn.php?r=upl_paid"><img src="image/rmf_2.png" width="25"></a></th>
	  </tr>
	  </table>';
echo '<table cellspacing="2" cellpadding="5">';
echo '<tr>';
echo '</tr>';
echo '</table>';
echo '<table border="0" width="100%" cellpadding="5" cellspacing="1" bgcolor="#bde0e6">
	  <tr><th width="2%">No</th>
	  	  <th>Nama Perusahaan</th>
	  	  <th width="20%">Nama File</th>
	  	  <th width="10%">Jumlah Data</th>
	  	  <th width="10%">Total Premi</th>
	  	  <th width="1%">User</th>
	  	  <th width="10%">Tanggal Upload</th>
	  	  <th width="1%">Option</th>
	</tr>';
$met_filePaid = $database->doQuery('SELECT * FROM fu_ajk_paidfile ORDER BY input_date DESC');
while ($met_filePaid_ = mysql_fetch_array($met_filePaid)) {
$data = new Spreadsheet_Excel_Reader($metpath_file.$met_filePaid_['nmfile']);
$baris = $data->rowcount($sheet_index=0);
for ($i=7; $i<=$baris; $i++)
{
	$data1=$data->val($i, 1);		//NO
	$data2=$data->val($i, 2);		//ID PESERTA
	$data3=$data->val($i, 3);		//NAMA
	$data4=$data->val($i, 4);		//TOTAL PREMI
	$data5=$data->val($i, 5);		//TANGGAL BAYAR
$jumlahData += COUNT($data2);
$met_premi = str_replace(array("*",",",""),"", $data4);
$jumlahPremi += $met_premi;
}
$met_paidCost = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$met_filePaid_['id_cost'].'"'));

if ($met_filePaid_['approve_by']==NULL) {
$viewDataExl = '<a href="ajk_prm_payment_dn.php?r=vpaidSPV&fname='.$met_filePaid_['id'].'">'.$met_filePaid_['nmfile'].'</a>';
$viewDataExl_Paid ='';
}else{
$viewDataExl =$met_filePaid_['nmfile'];
$viewDataExl_Paid = '<a title="Priview Data Paid" href=""><img src="image/pdftoexl.png" width="50%"></a>';
}
if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		<td align="center">'.++$no.'</td>
		<td>'.$met_paidCost['name'].'</td>
		<td>'.$viewDataExl.'</td>
		<td align="center">'.$jumlahData.' Data Pembayaran</td>
		<td align="right">'.duit($jumlahPremi).'</td>
		<td align="center">'.$met_filePaid_['input_by'].'</td>
		<td align="center">'._convertDate($met_filePaid_['input_date']).'</td>
		<td align="center">'.$viewDataExl_Paid.'</td>
	  </tr>';
}
echo '</table>';
	;
	break;

	case "vpaidSPV":
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
	  <tr><th width="100%" align="left">Modul Upload Data Pembayaran</font></th>
	  	  <th width="1%" align="center"><a href="ajk_prm_payment_dn.php?r=paidData"><img src="image/back.png" width="25"></a></th>
	  </tr></table>';
$met_paidCost = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_paidfile WHERE id="'.$_REQUEST['fname'].'"'));
$data = new Spreadsheet_Excel_Reader($metpath_file.$met_paidCost['nmfile']);
$baris = $data->rowcount($sheet_index=0);											//MEMBACA JUMLAH BARIS DATA EXCEL
echo '<form method="post" action="">
	<table border="0" width="100%" cellpadding="1" cellspacing="1">
	<tr><th width="1%">No</th>
		<th width="5%">ID Peserta</th>
		<th>Nama</th>
		<th width="10%">Tgl Lahir</th>
		<th width="10%">Plafond</th>
		<th width="1%">Tenor</th>
		<th width="10%">Tanggak Akad</th>
		<th width="10%">Tanggak Akhir</th>
		<th width="8%">Premi</th>
		<th width="10%">Tanggal Pembayaran</th>
	</tr>';
for ($i=7; $i<=$baris; $i++)
{
	$data1=$data->val($i, 1);		//NO
	$data2=$data->val($i, 2);		//ID PESERTA
	$data3=$data->val($i, 3);		//NAMA
	$data4=$data->val($i, 4);		//TOTAL PREMI
	$data5=$data->val($i, 5);		//TANGGAL BAYAR
$met_premi = str_replace(array("*",",",""),"", $data4);

$metPaidPeserta = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_cost="'.$met_paidCost['id_cost'].'" AND id_peserta="'.$data2.'" AND nama="'.$data3.'" AND totalpremi="'.$met_premi.'"'));
if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		<td align="center">'.++$no.'</td>
		<td align="center">'.$data2.'</td>
		<td>'.$data3.'</td>
		<td align="center">'._convertDate($metPaidPeserta['tgl_lahir']).'</td>
		<td align="right">'.duit($metPaidPeserta['kredit_jumlah']).'</td>
		<td align="center">'.$metPaidPeserta['kredit_tenor'].'</td>
		<td align="center">'._convertDate($metPaidPeserta['kredit_tgl']).'</td>
		<td align="center">'._convertDate($metPaidPeserta['kredit_akhir']).'</td>
		<td align="right"><font color="blue"><b>'.duit($met_premi).'</b></font></td>
		<td align="center">'._convertDate($data5).'</td>
	  </tr>';
}
if ($q['level']=="6" OR $q['level']==""  AND $q['status']="ARM" OR $q['status']="") {
echo '<tr><td colspan="10" align="center"><a title="Approve data upload pembayaran" href="ajk_prm_payment_dn.php?r=approve_paidS&FLpaidS='.$met_paidCost['id'].'" onClick="if(confirm(\'Apakah anda sudah yakin untuk mengupdate semua data pembayaran ini ?\')){return true;}{return false;}"><img src="image/save.png" border="0" width="30"></a></td></tr>';
}else{

}
echo '</table></form>';
	;
	break;

case "approve_paidS":
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%"><tr><th width="100%" align="left">Modul Upload Data Pembayaran</th></tr></table>';
$met_ = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_paidfile WHERE id="'.$_REQUEST['FLpaidS'].'"'));
$mets_ = mysql_fetch_array($database->doQuery('UPDATE fu_ajk_paidfile SET approve_by="'.$q['nm_lengkap'].'", approve_date="'.$futoday.'" WHERE id="'.$_REQUEST['FLpaidS'].'"'));
$data = new Spreadsheet_Excel_Reader($metpath_file.$met_['nmfile']);
$baris = $data->rowcount($sheet_index=0);
for ($i=7; $i<=$baris; $i++)
{
	$data1=$data->val($i, 1);		//NO
	$data2=$data->val($i, 2);		//ID PESERTA
	$data3=$data->val($i, 3);		//NAMA
	$data4=$data->val($i, 4);		//TOTAL PREMI
	$data5=$data->val($i, 5);		//TANGGAL BAYAR
$metPaidPeserta = $database->doQuery('UPDATE fu_ajk_peserta SET status_bayar="1" AND paid_fname="'.$met_['id'].'", paid_fdate="'.$data5.'" WHERE id_cost="'.$met_['id_cost'].'" AND id_peserta="'.$data2.'" AND nama="'.$data3.'"');

}

//NOTIFIKASI EMAIL KE SEMUA USER DAN UNDERWRITING
$metPaid_eMail = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE paid_fname="'.$met_['id'].'" GROUP BY input_by');
while ($metPaid_eMail_ = mysql_fetch_array($metPaid_eMail)) {
$user_eMail = mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$metPaid_eMail_['input_by'].'"'));
$metcostumer = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$metPaid_eMail_['id_cost'].'"'));
//$met_eMailnya .= $user_eMail['email'].', ';
	$mail = new PHPMailer; // call the class
	$mail->IsSMTP();
	$mail->Host = SMTP_HOST; //Hostname of the mail server
	$mail->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
	$mail->Username = SMTP_UNAME; //Username for SMTP authentication any valid email created in your domain
	$mail->Password = SMTP_PWORD; //Password for SMTP authentication
	$mail->AddReplyTo = SMTP_REPLYMAIL; //reply-to address
	$mail->SetFrom ($q['email'], $q['nm_lengkap']); //From address of the mail
	$mail->Subject = "AJKOnline - UPDATE DATA PEMBAYARAN"; //Subject od your mail


	//EMAIL STAFF CLIENT
	$mail_PaidStaff = mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE id_cost="'.$metPaid_eMail_['id_cost'].'" AND nm_user="'.$metPaid_eMail_['input_by'].'"'));
	$mail->AddAddress($mail_PaidStaff['email'], $mail_PaidStaff['nm_lengkap']); //To address who will receive this email

	$mail_PaidPeserta = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_cost="'.$metPaid_eMail_['id_cost'].'" AND paid_fname="'.$metPaid_eMail_['paid_fname'].'" AND input_by="'.$metPaid_eMail_['input_by'].'"');


	$message ="<table width=100%>";
	$message .="<tr><td>To ".$user_eMail['nm_lengkap'].", </td></tr>";
	$message .="<tr><td>Data pembayaran premi ".$metcostumer['name']." telah disetujui dan diupdate oleh <b>".strtoupper($_SESSION['nm_user'])."</b> pada tanggal ".$futgl."</td></tr>";
	$message .="<tr><td>";
	while ($mail_PaidPeserta_ = mysql_fetch_array($mail_PaidPeserta)) {
	$message .="$mail_PaidPeserta_[id_peserta]"."<br />";
	}
	$message .="</td></tr>";
	$message .="<tr><td>Salam, <br />".$q['nm_lengkap']."</td></tr>";
	$message .="</table>";
	//EMAIL SPV SPK
	$mail->AddCC("rahmad@relife.co.id");
	$mail->MsgHTML($message); //Put your body of the message you can place html code here
	//$send = $mail->Send(); //Send the mails
	echo $message.'<br />';
}
//NOTIFIKASI EMAIL KE SEMUA USER DAN UNDERWRITING
//echo '<center><h2>Data pembayaran premi telah berhasil diupdate oleh '.$q['nm_lengkap'].' pada tanggal '.$futgl.'.</h2></center><meta http-equiv="refresh" content="3;URL=ajk_prm_payment_dn.php?r=paidData">';
			;
			break;

	default:
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
<tr><th width="100%" align="left">Modul Payment Register - DN Assignment</font></th>
	<th width="5%"><a title="CN Assignment" href="ajk_confirmed.php?r=setcnprm&idp='.$q['id'].'" onclick="NewWindow(this.href,\'name\',\'1024\',\'500\',\'no\');return false"><img src="image/createDN_M.png" width="30"></a></th>
	<!--<th width="5%"><a href="ajk_prm.php"><img src="image/Backward-64.png" width="20"></a></th> DISABLED KARENA MODUL YANG DI TUJU TIDAK DI AKTIFKAN-->
	</tr>
</table>';

		$prm = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_prm WHERE id="'.$_REQUEST['id'].'"'));
		$metdn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn WHERE id="'.$_REQUEST['iddn'].'"'));
		$cost = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$prm['id_cost'].'"'));

if ($_REQUEST['ope']=="Paid") 	{
	$cekdnnya = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn WHERE id="'.$_REQUEST['iddn'].'" AND del IS NULL '));

if ($_REQUEST['jumnominal']!="" AND $_REQUEST['jumnominal'] < $cekdnnya['totalpremi']) {
	$statusdnbaru = 'paid(*)';
	$dnbayar = $cekdnnya['totalpremi'] - $_REQUEST['jumnominal'];	$dndibayar = $_REQUEST['jumnominal'];
}else{
	$statusdnbaru = 'paid';
	$dndibayar = $cekdnnya['totalpremi'];
}

//Data awal pada saat paid* pertanggal 26 juli 2013
//Query awal "dn_status="'.$statusdnbaru.'","//
//Data awal pada saat paid* pertanggal 26 juli 2013
if ($_REQUEST['jumnominal'] > $metdn['totalpremi']) {
echo '<center><blink><font color="red">Jumlah nominal yang di input lebih besar dari nilai totalpremi, data tidak dapat di update.<a href="ajk_prm_payment_dn.php?ope=updatedn&iddn='.$_REQUEST['iddn'].'">[back]</a></font></blink></center>';
}else{
$r = $database->doQuery('UPDATE fu_ajk_dn SET id_prm="'.$_REQUEST['id'].'",
											  dn_status="'.$statusdnbaru.'",
											  dn_total="'.$dndibayar.'",
											  tgl_dn_paid="'.$_REQUEST['rdns'].'",
											  update_by="'.$q['nm_lengkap'].'",
											  update_time="'.$futgl.'"
											  WHERE id="'.$_REQUEST['iddn'].'" ');
echo '<br /><br />';
$met_cek_dn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn WHERE id="'.$_REQUEST['iddn'].'"'));
//$cekpolisrmf = mysql_fetch_array($database->doQuery('SELECT id, id_cost, rmf FROM fu_ajk_polis WHERE id="'.$met_cek_dn['id_nopol'].'" AND id_cost="'.$met_cek_dn['id_cost'].'"'));
	$met_rmf_peserta = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_dn="'.$_REQUEST['iddn'].'"');
	while ($met_rmf_peserta_ = mysql_fetch_array($met_rmf_peserta)) {
	//$mamet_RMF = $met_rmf_peserta_['totalpremi'] * $cekpolisrmf['rmf'] / 100;		RMF dengan nilai PERSEN yg ADA PADA POLIS
	$cek_rate_RMF = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi_rmf WHERE id_cost="'.$met_rmf_peserta_['id_cost'].'" AND id_polis="'.$met_rmf_peserta_['id_polis'].'" AND tenor="'.$met_rmf_peserta_['kredit_tenor'].'"'));
	$mametRMF = $met_rmf_peserta_['kredit_jumlah'] * $cek_rate_RMF['rate'] / 1000;
	$p = $database->doQuery('UPDATE fu_ajk_peserta SET status_bayar=1, RMF="'.$mametRMF.'" WHERE id="'.$met_rmf_peserta_['id'].'" AND id_dn="'.$_REQUEST['iddn'].'"');
	}
//header("location:ajk_prm_payment_dn.php");
}
}

if ($_REQUEST['ope']=="Paid2") 	{
	$starpaid = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn WHERE id="'.$_REQUEST['iddn'].'"'));
	$dnsisanya = $starpaid['totalpremi'] - $starpaid['dn_total'];
	$dnstarnya =$starpaid['dn_total'] + $dnsisanya;
$r = $database->doQuery('UPDATE fu_ajk_dn SET id_prm="'.$starpaid['id_prm'].', '.$_REQUEST['id'].'",
												  dn_status="paid",
												  dn_total="'.$dnstarnya.'",
												  tgl_dn_paid="'.$prm['tgl_pem'].'",
												  update_by="'.$q['nm_lengkap'].'",
												  update_time="'.$futgl.'"
												  WHERE id="'.$_REQUEST['iddn'].'" ');
	header("location:ajk_prm.php?op=setdn&id=".$_REQUEST['id']."");
}

if ($_REQUEST['ope']=="UnPaid") {
	$unr = $database->doQuery('UPDATE fu_ajk_dn SET id_prm="", dn_status="unpaid", dn_total="", tgl_dn_paid="", update_by="'.$q['nm_lengkap'].'", update_time="'.$futgl.'" WHERE id="'.$_REQUEST['iddn'].'" ');
	$p = $database->doQuery('UPDATE fu_ajk_peserta SET status_bayar=0 WHERE id_dn="'.$metdn['dn_kode'].'"');
	header("location:ajk_prm.php?op=setdn&id=".$_REQUEST['id']."");
}

//PENGURANGAN TOTAL DN//
$metdn = $database->doQuery('SELECT * FROM fu_ajk_dn WHERE id_prm="'.$_REQUEST['id'].'" AND del IS NULL ');
while ($mDN = mysql_fetch_array($metdn))	{	$jdnPRM += $mDN['totalpremi'];	}	$aDN =$jdnPRM;
//PENGURANGAN TOTAL DN//

//PENGURANGAN TOTAL CN
$metcn = $database->doQuery('SELECT * FROM fu_ajk_cn WHERE id_prm="'.$_REQUEST['id'].'" AND del IS NULL ');
while ($mCN = mysql_fetch_array($metcn))	{	$jcnPRM += $mCN['total_claim'];	}	$aCN =$jcnPRM;
//PENGURANGAN TOTAL CN
$totalPRMnya =$prm['jumlah'] - $aDN + $aCN;		//TOTAL JUMLAH PRM

echo '<form method="post" id="formCheck" action="">
	  <table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#bde0e6">
	  <tr><td colspan="2" align="center" bgcolor="#666"><font color="#fff">Pencarian DN Unpaid : <input type="text" name="caridnunpaid" value="'.$_REQUEST['caridnunpaid'].'">&nbsp; &nbsp;
		   Cabang : </font><input type="text" name="caricabang" value="'.$_REQUEST['caricabang'].'">
					<input type="submit" name="button" value="Cari" class="button"></td></form>
	  </tr>
	  <tr><td valign="top" colspan="2">
	<table border="0" width="100%" cellpadding="5" cellspacing="1" bgcolor="#bde0e6">
	<tr><th width="2%">No</th>
		<th>Perusahaan</th>
		<th>Asuransi</th>
		<th width="6%">DN Number</th>
		<th width="5%">Date Create</th>
		<th width="6%">Total DN</th>
		<th width="1%">Status</th>
		<th width="6%">CN Number</th>
		<th width="5%">Date Create CN</th>
		<th width="6%">Nilai CN</th>
		<th width="8%">Net Premi</th>
		<th width="5%">Balance</th>
		<th width="5%">Type</th>
		<th width="10%">Branch</th>
		<th width="13%">Regional</th>
		<th width="1%">Paid</th>
	</tr>
	<tr bgcolor="#FFF"><td colspan="14" align="center">';
if ($_REQUEST['ope']=="updatedn") {
$cekdnnya = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn WHERE id="'.$_REQUEST['iddn'].'" AND del IS NULL '));
	echo '<form method="post" action="ajk_prm_payment_dn.php?ope=Paid">
		  <table border="0" width="30%">
		  <input type="hidden" name="iddn" value="'.$_REQUEST['iddn'].'">
		  <tr bgcolor="gray"><td colspan="2" align="center">Update status DN <b>'.$cekdnnya['dn_kode'].'</b> menjadi <b>Paid</b></td></tr>
		  <tr><td width="30%">Tanggal Pembayaran</td><td>: ';print initCalendar();	print calendarBox('rdns', 'triger', $futglprm).'</td></tr>
		  <tr><td>Jumlah Pembayaran</td><td>: <input type="text" name="jumnominal" value="'.$_REQUEST['jumnominal'].'" onkeypress="return isNumberKey(event)"></td></tr>
		  <tr><td colspan="2" align="center"><input type="submit" name="ope" value="Paid"> &nbsp;  &nbsp; <a href="ajk_prm_payment_dn.php">cancel</a>
		  </table>';
}
echo '</td></tr>';
if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}

if ($_REQUEST['caridnunpaid'])		{	$dnunpaid = 'AND dn_kode LIKE "%' . $_REQUEST['caridnunpaid'] . '%"';		}
if ($_REQUEST['caricabang'])		{	$cnunpaid = 'AND id_cabang LIKE "%' . $_REQUEST['caricabang'] . '%"';		}
$rdn = $database->doQuery('SELECT * FROM fu_ajk_dn WHERE id != "" '.$dnunpaid.' '.$cnunpaid.' AND del IS NULL ORDER BY id DESC LIMIT ' . $m . ' , 25');
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_dn WHERE id != "" '.$dnunpaid.' '.$cnunpaid.' AND del IS NULL '));
$totalRows = $totalRows[0];
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;

while ($fdn = mysql_fetch_array($rdn)) {
if (duit($totalPRMnya) == 0) {	$setprmnya = '<b>Closed</b>';	}
//elseif($fdn['dn_status']=="paid(*)")	{	$setprmnya = '<a href="ajk_prm_payment_dn.php?ope=Paid2&iddn='.$fdn['id'].'" onClick="if(confirm(\'Update status PAID pada nomor DN : '.$fdn['dn_kode'].' ?\')){return true;}{return false;}">set prm(*)</a>';	}
elseif($fdn['dn_status']=="paid(*)")	{	$setprmnya = '<a href="ajk_prm_payment_dn.php?ope=updatedn&iddn='.$fdn['id'].'">set prm(*)</a>';	}
//else	{	$setprmnya = '<a href="ajk_prm_payment_dn.php?ope=Paid&iddn='.$fdn['id'].'" onClick="if(confirm(\'Update status PAID pada nomor DN : '.$fdn['dn_kode'].' ?\')){return true;}{return false;}"><img src="image/check.png" width="30"></a>';	}
elseif($fdn['dn_status']=="paid")	{	$setprmnya = '';	}
else {	$setprmnya = '<a href="ajk_prm_payment_dn.php?ope=updatedn&iddn='.$fdn['id'].'"><img src="image/check.png" width="25"></a>';	}

	//CEK FORMAT TANGGAL
	$findmet="/";
	$fpos = stripos($fdn['tgl_createdn'], $findmet);
if ($fpos === false) {	$riweuh = explode("-", $fdn['tgl_createdn']);	$cektglnya = $riweuh[0].'/'.$riweuh[1].'/'.$riweuh[2];							// FORMULA TANGGAL
}	else	{	$riweuh = explode("/", $fdn['tgl_createdn']);			$cektglnya = $riweuh[0].'/'.$riweuh[1].'/'.$riweuh[2];							// FORMULA TANGGAL
}
	//CEK FORMAT TANGGAL

if ($fdn['id_cabang']=="") {	$metprmcabang = $fdn['id_cabang_old'];	}	else	{	$metprmcabang = $fdn['id_cabang'];	}

if ($fdn['dn_status']=="") {	$statusdnnya = "unpaid";	}
else	{	$statusdnnya = '<a href="ajk_prm.php?op=setdn&ope=UnPaid&id='.$_REQUEST['id'].'&iddn='.$fdn['id'].'">'.$fdn['dn_status'].'</a>';	}

// TAMPILKAN CN BILA ADA//
$dncnnya = mysql_fetch_array($database->doQuery('SELECT
fu_ajk_cn.id_cn,
fu_ajk_cn.id_dn,
fu_ajk_cn.tgl_createcn,
SUM(if(fu_ajk_cn.total_claim < 0,0, fu_ajk_cn.total_claim) ) AS tclaim,
fu_ajk_cn.type_claim,
fu_ajk_cn.del
FROM fu_ajk_cn
WHERE id_dn = "'.$fdn['dn_kode'].'" AND id_cost="'.$fdn['id_cost'].'" AND type_claim !="Death" AND type_claim !="Refund" AND del IS NULL
GROUP BY fu_ajk_cn.id_dn'));
if ($dncnnya['id_dn']==$fdn['dn_kode']) {
	$cnnomor = '<a href="ajk_report_fu.php?fu=ajkpdfcn&id_cn='.$dncnnya['id_cn'].'">'.substr($dncnnya['id_cn'],6).'</a>';
	$cntanggal = _convertDate($dncnnya['tgl_createcn']);
	$cnpremi = $dncnnya['tclaim'];
	$statuscn = '<font color="red">'.$dncnnya['type_claim'].'</font>';
}else{
	$cnnomor = '-';
	$cntanggal = '-';
	$cnpremi = '-';
	$statuscn = '-';
}
// TAMPILKAN CN BILA ADA//
//if ($cnpremi <= 0) {	$nilaicnnya = 0;	}else{	$nilaicnnya=$cnpremi;	}	//BUAT NILAI 0 BILA NILAINYA MINUS
$netpremi = $fdn['totalpremi'] - $cnpremi;
if ($netpremi < 0) {	$netpremiwarna = '<font color="red">'.duit($netpremi).'</font>';	}else{	$netpremiwarna=duit($netpremi);	}	//

$metperusahaan = mysql_fetch_array($database->doQuery('SELECT id,name FROM fu_ajk_costumer WHERE id="'.$fdn['id_cost'].'"'));
$metasuransi = mysql_fetch_array($database->doQuery('SELECT id,name FROM fu_ajk_asuransi WHERE id="'.$fdn['id_as'].'"'));

$netbalance = $netpremi - $fdn['dn_total'];
if ($netbalance < 0) {	$netbalancewarna = '<font color="red">'.duit($netbalance).'</font>';	}else{	$netbalancewarna=duit($netbalance);	}	//
if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';

echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		<td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
		<td>'.$metperusahaan['name'].'</td>
		<td>'.$metasuransi['name'].'</td>
		<td><a href="ajk_report_fu.php?fu=ajkpdfinvdn&id='.$fdn['id'].'" title="DN Pdf '.$fdn['dn_kode'].'" target="_blank">'.substr($fdn['dn_kode'],3).'</a></td>
		<td align="center">'.$cektglnya.'</td>
		<td align="right">'.duit($fdn['totalpremi']).'</td>
		<td align="center"><b><font color="red">'.$fdn['dn_status'].'</font></b></td>
		<td align="center">'.$cnnomor.'</td>
		<td align="center">'.$cntanggal.'</td>
		<td align="right">'.duit($cnpremi).'</td>
		<td align="right">'.$netpremiwarna.'</td>
		<td align="right">'.$netbalancewarna.'</td>
		<td align="center">'.$statuscn.'</td>
		<td>'.$metprmcabang.'</td>
		<td>'.$fdn['id_regional'].'</td>
		<td align="center">'.$setprmnya.'</td>
		</tr>';
}
		$totalRowsDNunpaid = $totalRows;		//jumlah nomor DN UNPAID
		echo '<tr><td colspan="22">';
		echo createPageNavigations($file = 'ajk_prm_payment_dn.php?caricabang='.$_REQUEST['caricabang'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
		echo '<b>Total Data DN Unpaid: <u>' . $totalRows . '</u></b></td></tr>';
echo '</form></table>';
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
	self.location='ajk_prm_payment_dn.php?r=upl_paid&cat=' + val;
}
</script>