<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// Relife - AJK Online 2013
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("../includes/functions.php");
connect();
include_once '../includes/smtp_classes/library.php'; // include the library file
include_once '../includes/smtp_classes/class.phpmailer.php'; // include the class name
$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));

switch ($_REQUEST['x']) {
	case "cekterbilang":
if ($_REQUEST['z']) {	$m = ($_REQUEST['z']-1) * 25;	}	else {	$m = 0;		}
$terbilangDN = $database->doQUery('SELECT id, dn_kode, ROUND(totalpremi) AS tpremi FROM fu_ajk_dn WHERE del IS NULL ORDER BY id DESC LIMIT ' . $m . ' , 25');
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_dn WHERE id != "" AND del IS NULL '));
$totalRows = $totalRows[0];
$_REQUEST['z'] ? $pageNow = $_REQUEST['z'] : $pageNow = 1;

echo '<table border="0" width="100%" cellpadding="5" cellspacing="1" bgcolor="#bde0e6">
	  <tr><td bgcolor="#FFF" colspan="4" align="left"><a href="../aajk_report.php?er=cekbilang"><img src="image/pdftoexl.png" width="30"></a></td></tr>
	  <tr><th width="3%">No</th>
	  	  <th width="15%">Debitnote</th>
	  	  <th width="15%">Nilai</th>
	  	  <th>Terbilang</th>
	  </tr>';
while ($terbilangDN_ = mysql_fetch_array($terbilangDN)) {
if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	    <td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
	  	  <td>'.$terbilangDN_['dn_kode'].'</td>
	  	  <td align="right">'.duit($terbilangDN_['tpremi']).'</td>
	  	  <td>'.mametbilang($terbilangDN_['tpremi']).'</td>
	  </tr>';
}
echo '<tr><td colspan="4">';
echo createPageNavigations($file = 'testmail.php?x=cekterbilang&', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
echo '<b>Total Data Debit Note (DN): <u>' . $totalRows . '</u></b></td></tr>';
echo '</table>';
		;
		break;
	case "w":
		;
		break;
	default:
if($_REQUEST['send']=="Send via SMTP"){

//echo $_REQUEST['email'];
	$email = $_POST["email"];
	$mail	= new PHPMailer; // call the class
	$mail->IsSMTP();
	$mail->Host = SMTP_HOST; //Hostname of the mail server
	$mail->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
	$mail->Username = SMTP_UNAME; //Username for SMTP authentication any valid email created in your domain
	$mail->Password = SMTP_PWORD; //Password for SMTP authentication
	$mail->AddReplyTo = SMTP_REPLYMAIL; //reply-to address
	$mail->debug = 1;
	$mail->SMTPSecure = "ssl";
	$mail->IsHTML(true);

	//$mail->SetFrom ("administrator_ajk@adonai.co.id", "Administrator"); //From address of the mail
	//$mail->Subject = "AJK Online - RESET PASSWORD USER"; //Subject od your mail
	$mail->SetFrom ($q['email'], $q['nm_lengkap']);
	$mail->AddReplyTo($_REQUEST['email']); //reply-to address
	//$mail->SetFrom("arief.kurniawan@relife.co.id", "Rahmad SMTP Mailer"); //From address of the mail
	// put your while loop here like below,
	$mail->Subject = "AJK SMTP Mail ADONAI !"; //Subject od your mail
	$mail->AddAddress($_REQUEST['email']); //To address who will receive this email
	$mail->MsgHTML("<b>Test SMTP MAIL ADONAI !.. <br/><br/>by <a href='#'>Adonai</a></b>"); //Put your body of the message you can place html code here
	//$mail->AddCC("IT@adonai.co.id");
	//$mail->AddCC("rahmad@adonaits.co.id");
	//$mail->AddAttachment("images/logo.png"); //Attach a file here if any or comment this line,
	$send = $mail->Send(); //Send the mails
	if($send){
		echo '<center><h3 style="color:#009933;">Mail sent successfully</h3></center>';
	}
	else{
		echo '<center><h3 style="color:#FF3300;">Mail error: </h3></center>'.$mail->ErrorInfo;
	}

/*
	$to = $_REQUEST['email'].', '.$q['email'].', '."sumiyanto@relife.co.id, pajar@relife.co.id, arief.kurniawan@relife.co.id" ;
	$subject = 'AJKOnline - APPROVE PESERTA BARU RELIFE AJK ONLINE';
	$message = '<html><head><title>Test Approve oleh '.$q['nm_lengkap'].'</title></head>
				<body>
				<table><tr><th>Test Approve <b>'.$_SESSION['nm_user'].'</b> pada tanggal '.$tglnya.'</tr></table>
				</body></html>';
	// Additional headers
	$headers .= "X-Mailer: PHP5\n";
	//$headers .= 'X-Mailer: PHP/' . phpversion();
	$headers .= 'MIME-Version: 1.0' . "\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

	$headers .= 'From: '.$q['email'].'' . "\r\n";
	$headers .= 'Cc:  ade@relife.co.id' . "\r\n";
	//$headers .= 'Bcc: xxx@xxxx.com' . "\r\n";
	mail($to, $subject, $message, $headers);
*/
}
/* SCRIPT EMAIL 2
   $contacts = array(
   "arief.kurniawan@relife.co.id",
   "saya@ariefkurniawan.com",
   "kepodank@gmail.com",
   $_REQUEST['email'],
   "rahmad@relif.co.id",
   );

   foreach($contacts as $contact) {

   $to = $contact;
   $subject = 'AJKOnline - Test Email';
   $message = '<html><head><title>Test Email oleh '.$q['nm_lengkap'].'</title></head>
   <body>
   <table><tr><th>Test Email <b>'.$_SESSION['nm_user'].'</b> pada tanggal '.$tglnya.'</tr></table>
   </body></html>';
   //$headers  = 'MIME-Version: 1.0' . "\r\n"; 	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

   $headers = 'From: '.$q['email']."\r\n".  'Reply-To: '.$q['email']."\r\n" . 'X-Mailer: PHP/' . phpversion();
   $headers .= "X-Mailer: PHP5\n";
   $headers .= 'MIME-Version: 1.0' . "\n";
   $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
   // Additional headers
   $headers .= 'From: '.$q['email'].'' . "\r\n";
   $headers .= 'Cc:  relife-ajk@relife.co.id' . "\r\n";
   //	$headers .= 'Bcc: k@example.com' . "\r\n";
   mail($to, $subject, $message, $headers);
   echo $to.'<br />';
   echo $subject.'<br />';
   echo $message.'<br />';
   echo $headers.'<br />';
   }
*/
/*	SCRIPT EMAIL 3
$alamat_tujuan = "rahmad@relife.co.id";
$subject_mail = "Test mail";
// ubah isi message menjadi format html
$isi_message = "google<br />";
$isi_message .= "yahoo<br />";
$isi_message .= "hotmail<br />";

// penambahan header agar email dibaca sebagai format html
$header_mail .= 'MIME-Version: 1.0' . "\r\n";
$header_mail .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
// alamat untuk reply
$header_mail .= "From: Rahmad <rahmad@relife.co.id>";
$header_mail .= "Reply-to:rahmad@relife.co.id";

$laporan_kirim = mail($alamat_tujuan, $subject_mail, $isi_message, $header_mail);
// pesan email terkirim
if($laporan_kirim) {
	echo "email pemberitahuan terkirim";}
else {
	echo "Error, kegagalan pengiriman email";
}
*/

echo '<div class="as_wrapper">
	<h1>Test PHP MAILER</h1>
    <form action="" method="post">
    <table class="mytable">
    <tr><td><input type="email" placeholder="Email" name="email" /></td></tr>
    <tr><td><input type="submit" name="send" value="Send via SMTP" /></td></tr>
    </table>
    </form>
</div>';


if (PHP_OS=="Linux") {
	function getMacLinux() {
		exec('netstat -ie', $result);
		if(is_array($result)) {
			$iface = array();
			foreach($result as $key => $line) {
				if($key > 0) {
					$tmp = str_replace(" ", "", substr($line, 0, 10));
					if($tmp <> "") {
						$macpos = strpos($line, "HWaddr");
						if($macpos !== false) {
							$iface[] = array('iface' => $tmp, 'mac' => strtolower(substr($line, $macpos+7, 17)));
						}
					}
				}
			}
			return $iface[0]['mac'];
		} else {
			return "notfound";
		}
	}
	$myMetMac = getMacLinux();
	//echo $myMetMac.'<br />';
	if ($myMetMac == "2c:44:fd:82:d8:c0") {
	echo '';
	}else{
	echo 'fn dslkfndslkf ndslkfsd';

	}
}elseif (strtoupper(substr(PHP_OS, 0, 3))=="WIN") {
	ob_start(); // Turn on output buffering
	system('ipconfig /all'); //Execute external program to display output
	$mycom=ob_get_contents(); // Capture the output into a variable
	ob_clean(); // Clean (erase) the output buffer

	$findme = "Physical";
	$pmac = strpos($mycom, $findme); // Find the position of Physical text
	$mac=substr($mycom,($pmac+36),17); // Get Physical Address
	echo $mac.'<br />';
}else{
	echo 'lainnya';
}
/*
		if ($myMetMac == "2c:44:fd:82:d8:c0") {
			echo '';
		}else{
			echo 'fn dslkfndslkf ndslkfsd';
		}
		exec("/sbin/ifconfig | grep HWaddr", $output);
		print_r( $output);

		echo '<br /><br />';
*/


	;
} // switch

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Test PHP Mailer</title>
<style>
.as_wrapper{
	font-family:Arial;
	color:#333;
	font-size:14px;
}
.mytable{
	padding:20px;
	border:2px dashed #17A3F7;
	width:100%;
}
</style>
<body>

</body>
</html>
