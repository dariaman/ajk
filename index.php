<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :penting_kaga@yahoo.com
// ----------------------------------------------------------------------------------
include_once('ui.php');
include_once ("includes/functions.php");
connect();
echo '<br /><br /><br /><table border="0" cellpadding="3" cellspacing="0" width="100%">
	<tr><td align="center" colspan="2"><font color="#ed2124" size="7"><img src="image/logo_adonai_1.gif" width="50"> A D O N A I </font> <font size="7">| Pialang Asuransi</font></td></tr>
	<tr><td align="center" colspan="2"><font color="#ffa800" size="5">Aplikasi Asuransi Jiwa Kredit dan Pensiunan</font><br /><br /><td></tr>';
if (session_is_registered('nm_user')) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));	}
if ($_REQUEST['sett_client']=="Ok") {
$met = $database->doQuery('UPDATE pengguna SET id_cost="'.$_REQUEST['cat'].'",id_polis="'.$_REQUEST['subcat'].'", level="'.$_REQUEST['rlevel'].'", wilayah="Pusat" WHERE id="'.$q['id'].'"');
header('Location: index.php');
}
if ($q['id_cost']=="") {
	if ($q['id_cost']=="" AND $q['id']=="1") {

$cat=$_GET['cat']; // Use this line or below line if register_global is off
if(strlen($cat) > 0 and !is_numeric($cat)){	echo "Data Error";	exit;	}
echo '<form method="post" action="">
		<tr><td align="right" width="50%">Pilih Client yang akan di gunakan</td>
		<td>:<select id="cat" name="cat" onchange="reload(this.form)">
			<option value="">-- Select Client --</option>';
$rows = mysql_query('select * from fu_ajk_costumer ORDER BY name ASC');
while($row = mysql_fetch_array($rows)) {
if($row['id']==$cat){echo '<option selected value="'.$row['id'].'">'.$row['name'].'</option><BR>';}
else{echo  '<option value="'.$row['id'].'">'.$row['name'].'</option>';}
}
echo '</select></td></tr>
<tr><td align="right">Pilih Produk</td>
		<td>: ';
if(isset($cat) and strlen($cat) > 0){	$quer=$database->doQuery('SELECT * FROM fu_ajk_polis where id_cost="'.$cat.'" ORDER BY id ASC');	}
echo '<select name="subcat"><option value="">---Pilih---</option>';
while($noticia = mysql_fetch_array($quer)) {
if ($noticia['nmproduk']=="") {	$metproduknya = $noticia['nopol'];	}else{	$metproduknya = $noticia['nmproduk'];	}
	echo  '<option value='.$noticia['id'].'>'.$metproduknya.'</option>';
}
echo '</select></td></tr>
		<tr><td align="right">Level Akses</td><td>: <select size="1" name="rlevel">
		<option value="">Select Level</option>';
	$metlevel = $database->doQuery('SELECT * FROM fu_ajk_level ORDER BY id ASC');
	while ($clevel = mysql_fetch_array($metlevel)) {	echo '<option value="'.$clevel['id'].'"'._selected($clevel['level'], $clevel['id']).'>'.$clevel['level'].'</option>';	}
	echo '</select>
		</td></tr>
		<tr><td align="center" colspan="2"><input type="submit" name="sett_client" value="Ok" class="button"></td></tr>
		</form>';
echo '<SCRIPT language=JavaScript>
function reload(form)
{	var val=form.cat.options[form.cat.options.selectedIndex].value;
	self.location=\'index.php?cat=\' + val;
}
</script>';
	}else{	header('Location: login.php?op=logout');	}
}
else{	}

echo '</table>';
echo '</br></br>';
$res_passw = mysql_fetch_array($database->doQuery('SELECT reset_passw_user FROM fu_ajk_master_setting WHERE idsett=1'));
$met_all_user = $database->doQuery('SELECT * FROM pengguna WHERE id="'.$q['id'].'"');
while ($met_all_user_ = mysql_fetch_array($met_all_user)) {
$tanggalplus=date('Y-m-d',strtotime($met_all_user_['update_passw']."+ ".$res_passw['reset_passw_user']." day"));
$date_1 = date('Y-m-d',strtotime($tanggalplus));
$date_2 = date('Y-m-d',strtotime($datelog));
	if ($tanggalplus < $date_2) {
	$password_mamet = createRandomPassword ();
	$mail = new PHPMailer; // call the class
	$mail->IsSMTP();
	$mail->Host = SMTP_HOST; //Hostname of the mail server
	$mail->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
	$mail->Username = SMTP_UNAME; //Username for SMTP authentication any valid email created in your domain
	$mail->Password = SMTP_PWORD; //Password for SMTP authentication
	$mail->AddReplyTo = SMTP_REPLYMAIL; //reply-to address
	$mail->SetFrom ("administrator_ajk@adonai.co.id", "Administrator"); //From address of the mail
	$mail->Subject = "AJK Online - RESET PASSWORD USER"; //Subject od your mail
		//EMAIL SPV SPK
	$upd_reset_mail = $database->doQuery('UPDATE pengguna SET password="'.md5($password_mamet).'", rahmad="'.$password_mamet.'", update_passw="'.$datelog.'" WHERE id="'.$met_all_user_['id'].'"');
	$res_mail = mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE id="'.$met_all_user_['id'].'"'));
	$mail->AddAddress($res_mail['email'], $res_mail['nm_lengkap']); //To address who will receive this email

	$message ='Dear '.$res_mail['nm_lengkap'].'<br /><br />Password anda telah kadaluarsa dan telah direset oleh sistem.<br />Password anda sekarang '.$password_mamet.'<br /><br />Terimakasih.<br />Administrator';
	$mail->AddCC("kepodank@gmail.com");
	$mail->MsgHTML($message); //Put your body of the message you can place html code here
	//$send = $mail->Send(); //Send the mails	DISABLED SEMENTARA 150903
	//echo $message.'<br />';

	//$upd_reset_mail = $database->doQuery('UPDATE pengguna SET password="'.md5($password_mamet).'", rahmad="'.$password_mamet.'", update_passw="'.$datelog.'" WHERE id="'.$met_all_user_['id'].'"');
	//echo('UPDATE pengguna SET password="'.md5($password_mamet).'", rahmad="'.$password_mamet.'", update_passw="'.$datelog.'" WHERE id="'.$met_all_user_['id'].'"');
	//$res_mail = mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE id="'.$met_all_user_['id'].'"'));
	//echo $res_mail['email'];
	//echo '<br />';


/*email MAILER
$to = $res_mail['email'];
	$subject = 'AJK Online - RESET PASSWORD USER ('.$res_mail['nm_lengkap'].')';
	$message = '<html><head><title>RESET PASSWORD USER</title></head><body>
				<table border="0" width="100%" cellpadding="1" cellspacing="3">
				<tr><td>Dear '.$res_mail['nm_lengkap'].',</td></tr>
				<tr><td><br />Password anda telah kadaluarsa dan telah direset oleh sistem.<br />Password anda sekarang '.$password_mamet.'<br /><br />Terimakasih.<br />Administrator</td></tr>
				</body></html></table>';
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= 'From: administrator@adonai.co.id' . "\r\n";
	$headers .= 'Cc:  rahmad@relife.co.id' . "\r\n";
	mail($to, $subject, $message, $headers);
	echo $to.'<br />';
	echo $subject.'<br />';
	echo $message.'<br />';
	echo $headers.'<br />';
*/
	}	else{	}
}
?>
