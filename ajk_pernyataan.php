<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("includes/functions.php");
$futgl = date("Y-m-d g:i:a");
echo "<script language=\"JavaScript\" src=\"javascript/js/form_validation.js\"></script>";
connect();
if (session_is_registered('nm_user')) {
	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));
	$met = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$q['id_cost'].'"'));
	$cmet = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id_cost="'.$met['id'].'"'));
}

switch ($_REQUEST['r']) {
	case "a":
		;
		break;
	case "tambahdok":
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
      <tr><th width="100%" align="left">Modul Pernyataan Peserta - Upload Data</font></th><th width="5%"><a href="ajk_pernyataan.php"><img src="image/Backward-64.png" width="21" border="0"></a></th></tr>
      </table>';
if ($_REQUEST['ope']=="Kirim") {
	$_REQUEST['rspaj'] = $_POST['rspaj'];
	if (!$_REQUEST['rspaj'])  $error .='Silahkan pilih nomor SPAJ peserta<br />.';
	if (!$_FILES['filepernyataan']['tmp_name'])  $error .='Silahkan upload file zip, rar atau pdf anda.';
	$allowedExtensions = array("zip","rar", "pdf");
	foreach ($_FILES as $file) {
		if ($file['tmp_name'] > '') {
			if (!in_array(end(explode(".",	strtolower($file['name']))),
			$allowedExtensions)) {	$error .='<center><font color=red>'.$file['name'].' File extension tidak diperbolehkan selain zip, rar atau pdf!<br />';	}
		}
	}
	if ($error)
	{	echo '<center><font color=red>'.$error.'</center>';	}
	else
	{	$uploaddir = 'ajk_file/medical/';       // upload directory
		$file_name = $_REQUEST['rspaj'].'-'.$_FILES['filepernyataan']['name'];            // UPLOADED FILE
		$max_file_size='10000000';														  // MAX DATA
		$metfile =$_FILES['filepernyataan']['name'];         							  // FILE
		$metpesertanya = mysql_fetch_array($database->doQuery('SELECT id, id_cost, spaj FROM fu_ajk_peserta WHERE spaj="'.$_REQUEST['rspaj'].'"'));
		if (file_exists($uploaddir.$file_name)){
		echo '<center><font color=red>File dengan nama <b>'.$file_name.'</b> sudah pernah di upload, Data di tolak !</font></center>';
		}elseif($_FILES['filepernyataan']['size'] >= $max_file_size){
		echo '<center><font color=red>Kapasitas file dengan nama <b>'.$file_name.'</b> terlalu besar, Data di tolak !</font></center>';
		}else{
			if (move_uploaded_file($_FILES['filepernyataan']['tmp_name'], $uploaddir.str_replace(' ', '_', $file_name)))
			{	$metpeserta = mysql_fetch_array($database->doQuery('SELECT id, spaj, nama, id_cost, id_peserta FROM fu_ajk_peserta WHERE spaj="'.$_REQUEST['rspaj'].'" AND id_cost="'.$q['id_cost'].'"'));
				$metfile = $database->doQuery('INSERT INTO fu_ajk_medical_form SET id_cost="'.$metpeserta['id_cost'].'",
																				   idp="'.$metpeserta['id'].'",
																				   file_status="Proses",
																				   file_medical="'.str_replace(' ', '_', $file_name).'",
																				   file_type="form_pmedic",
																				   date_form="'.$datelog.'",
																				   input_by="'.$q['nm_user'].'",
																				   input_time="'.$futgl.'"');
				$metfilepeserta = $database->doQuery('UPDATE fu_ajk_peserta SET file_p="'.str_replace(' ', '_', $file_name).'" WHERE id="'.$metpesertanya['id'].'"');

				//SENDMAIL
				$mail = new PHPMailer; // call the class
				$mail->IsSMTP();
				$mail->Host = SMTP_HOST; //Hostname of the mail server
				$mail->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
				//$mail->Username = SMTP_UNAME; //Username for SMTP authentication any valid email created in your domain
				//$mail->Password = SMTP_PWORD; //Password for SMTP authentication
				//$mail->AddReplyTo = SMTP_REPLYMAIL; //reply-to address
				$mail->SetFrom ($q['email'], $q['nm_lengkap']); //From address of the mail
				$mail->Subject = "AJKOnline - BERKAS FILE PERNYATAAN MEDICAL ATAS NAMA ".$metpeserta['nama'].""; //Subject od your mail
				//EMAIL PENERIMA CLIENT
				$Rmail = $database->doQuery('SELECT * FROM pengguna WHERE wilayah="'.$q['wilayah'].'" AND id_cost="'.$q['id_cost'].'"');
						while ($eRmail = mysql_fetch_array($Rmail)) {
							$mail->AddAddress($eRmail['email'], $eRmail['nm_lengkap']); //To address who will receive this email
						}
				//EMAIL PENERIMA CLIENT

				//EMAIL PENERIMA  KANTOR U/W
				$mailsupervisorajk = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="" AND status="10"');
						while ($_mailsupervisorajk = mysql_fetch_array($mailsupervisorajk)) {
							$mail->AddAddress($_mailsupervisorajk['email'], $_mailsupervisorajk['nm_lengkap']); //To address who will receive this email
						}
				//EMAIL PENERIMA  KANTOR U/W
				$mail->AddCC("relife-ajk@relife.co.id");
				//$mail->AddAddress($mailsupervisorajk['email'], $mailsupervisorajk['nm_lengkap']); //To address who will receive this email
				//$mail->AddCC($approvemail);
				$mail->MsgHTML('<table><tr><th>Berkas file pernyataan medical '.$file_name.' telah diupload oleh <b>'.$_SESSION['nm_user'].' pada tanggal '.$tglnya.'</tr></table>'.$message); //Put your body of the message you can place html code here
				$send = $mail->Send(); //Send the mails
				//SENDMAIL
				echo '<center><font color="blue">File dengan nama <strong>"'.$file_name.'"</strong> telah  berhasil di Upload.<br /><meta http-equiv="refresh" content="2; url=ajk_pernyataan.php">';
			}
		}
	}
}
$spaj = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_cost="'.$met['id'].'" AND spaj !="" AND status_aktif="pending" AND del IS NULL ORDER BY spaj DESC');
echo '<table border="0" width="50%" cellpadding="1" cellspacing="1" align="center">
	  <form action="ajk_pernyataan.php?r=tambahdok" method="post" enctype="multipart/form-data">
	  <tr><td width="30%">No. SPAJ<td>: <select name="rspaj"><option value="">-Pilih SPAJ-</option>';
while ($metspaj=mysql_fetch_array($spaj)) {
	echo  '<option value="'.$metspaj['spaj'].'">'.$metspaj['spaj'].'</option>';
}
echo '</select></td></tr>
	  <tr><td valign="top">Upload Data Pernyataan<br /><font color="blue" size="2">Max File Upload : 10Mb<br />File ext (zip, rar dan pdf)</font></td>
	  	  <td valign="top">: <input name="filepernyataan" type="file" size="30" onchange="checkfile(this);" ></td>
	  </tr>
	  <tr><td colspan="2" align="center"><input name="ope" type="submit" value="Kirim"></td></tr>
	  </table></form>';
		;
		break;
	default:
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
      <tr><th width="100%" align="left">Modul Pernyataan Peserta</font></th><th width="5%"><a href="ajk_pernyataan.php?r=tambahdok">Tambah</a></th></tr>
      </table>';
echo '<table border="0" width="100%" cellpadding="5" cellspacing="1" bgcolor="#bde0e6">
	  <tr bgcolor="#FFF"><td colspan="3">Nama Perusahaan</td><td colspan="10">: '.$met['name'].'</td></tr>
	  <tr><th rowspan="2" width="1%">No</th>
		  <th rowspan="2" width="5%">SPAJ</th>
		  <th rowspan="2" width="5%">Polis</th>
		  <th rowspan="2">Nama Peserta</th>
		  <th rowspan="2" width="5%">DOB</th>
		  <th rowspan="2" width="8%">Jumlah Kredit</th>
		  <th rowspan="2" width="5%">Tgl Kredit</th>
		  <th width="10%" colspan="4">Pernyataan</th>
		  <th rowspan="2" width="25%">Nama File</th>
		  <th rowspan="2" width="5%">Status File</th>
		  <th rowspan="2" width="5%">Tgl Upload</th>
	  </tr>
	  <tr><th>1</th><th>2</th><th>3</th><th>4</th></tr>';
$fupeserta = $database->doQuery('SELECT * FROM fu_ajk_medical_form  ORDER BY id DESC');
while ($fup = mysql_fetch_array($fupeserta)) {
$metpesertanya = mysql_fetch_array($database->doQuery('SELECT id, id_cost, id_polis, id_peserta, spaj, nama, kredit_jumlah, kredit_tgl, tgl_lahir, statement1, statement2, statement3, statement4 FROM fu_ajk_peserta WHERE id="'.$fup['idp'].'"'));
$metpolisnya = mysql_fetch_array($database->doQuery('SELECT id, id_cost, nopol FROM fu_ajk_polis WHERE id="'.$metpesertanya['id_polis'].'" AND id_cost="'.$metpesertanya['id_cost'].'"'));

$uploaddir = 'ajk_file/medical/';
if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		  <td align="center">'.++$no.'</td>
		  <td align="center">'.$metpesertanya['spaj'].'</td>
		  <td>'.$metpolisnya['nopol'].'</td>
		  <td>'.$metpesertanya['nama'].'</td>
		  <td align="center">'.$metpesertanya['tgl_lahir'].'</td>
		  <td align="right">'.duit($metpesertanya['kredit_jumlah']).'</td>
		  <td align="center">'.$metpesertanya['kredit_tgl'].'</td>
		  <td align="center">'.$metpesertanya['statement1'].'</td>
		  <td align="center">'.$metpesertanya['statement2'].'</td>
		  <td align="center">'.$metpesertanya['statement3'].'</td>
		  <td align="center">'.$metpesertanya['statement4'].'</td>
		  <td><a title="download dokumen" href="'.$uploaddir.''.$fup['file_medical'].'">'.$fup['file_medical'].'</a></td>
		  <td align="center">'.$fup['file_status'].'</td>
		  <td align="center">'._convertDate($fup['date_form']).'</td>
	  </tr>';
		}
echo '</table>';
		;
} // switch
?>
<script type="text/javascript" language="javascript">
function checkfile(sender) {
	var validExts = new Array(".zip", ".rar", ".pdf");
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