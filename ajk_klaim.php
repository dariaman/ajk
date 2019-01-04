<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ----------------------------------------------------------------------------------
include_once("ui.php");
include_once("../includes/functions.php");
connect();

if (isset($_SESSION['nm_user'])) {
    $q = mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="' . $_SESSION['nm_user'] . '"'));
}
if ($_REQUEST['r']=="tp") {
    $tiperefund = "Topup";
    $_typeRefund = "Topup";
} elseif ($_REQUEST['r']=="lp") {
    $tiperefund = "Pelunasan Dipercepat";
    $_typeRefund = "Lunas";
} else {
    $tiperefund = "";
}
switch ($_REQUEST['er']) {
    case "reqbatal":
$met_tempCN = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cn_tempf WHERE id="' . $_REQUEST['idref'] . '"'));
$met_cn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cn_tempf WHERE id="' . $_REQUEST['idref'] . '"'));
$met_dn = mysql_fetch_array($database->doQuery('SELECT id, dn_kode FROM fu_ajk_dn WHERE id="' . $met_cn['id_dn'] . '"'));
$met_peserta = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_cost="' . $met_cn['id_cost'] . '" AND id_polis="' . $met_cn['id_nopol'] . '" AND id_peserta="' . $met_cn['id_peserta'] . '"'));

echo '<form method="post" action="">
	<table border="0" width="100%" cellpadding="3" cellspacing="1">
	<tr>
	<th width="10%">Nomor DN</th>
	<th width="8%">ID Peserta</th>
	<th>Nama Tertanggung</th>
	<th width="1%">Tanggal Lahir</th>
	<th width="1%">Usia</th>
	<th width="1%">Tgl Akad</th>
	<th width="1%">Tenor</th>
	<th width="1%">Tgl Akhir</th>
	<th width="1%">Plafond</th>
	<th width="1%">Premi</th>
	<th width="1%">Tgl Refund</th>
	<th width="1%">Nilai Refund</th>
	<th width="1%">Status</th>
	<th width="5%">Cabang</th>
	<th width="1%">User</th>
	</tr>';
echo '<input type="hidden" name="idref" value="'.$_REQUEST['idref'].'">
	<tr>
	<td align="center"><a href="aajk_report.php?er=_kwipeserta&idn=' . $met_cn_['id_dn'] . '" target="_blank">' . $met_dn['dn_kode'] . '</a></td>
	<td align="center">' . $met_cn['id_peserta'] . '</td>
	<td>'.$met_peserta['nama'].'</td>
	<td align="center">' . _convertDate($met_peserta['tgl_lahir']) . '</td>
	<td align="center">' . $met_peserta['usia'] . '</td>
	<td align="center">' . _convertDate($met_peserta['kredit_tgl']) . '</td>
	<td align="center">' . $met_peserta['kredit_tenor'] . '</td>
	<td align="center">' . _convertDate($met_peserta['kredit_akhir']) . '</td>
	<td align="right">' . duit($met_peserta['kredit_jumlah']) . '</td>
	<td align="right"><b>' . duit($met_peserta['totalpremi']) . '<b></td>
	<td align="center">' . _convertDate($met_cn['tgl_claim']) . '</td>
	<td align="right"><font color="red">' . duit($met_cn['total_claim']) . '</font></td>
	<td align="center">' . $met_cn['confirm_claim'] . '</td>
	<td align="center">' . $met_cn['id_cabang'] . '</td>
	<td align="center">' . $met_cn['input_by'] . '</td>
	 </tr>';
echo '<tr><td valign="top">Alasan pengajuan ditolak/dibatalkan<font color="red"> *</font></td>
		  <td><textarea name="keterangantolakbatal" type="text" cols="30" rows="2"placeholder="Keterangan data pengajuan di batalkan/ditolak">'.$_REQUEST['keterangantolakbatal'].'</textarea></td>
	</tr>
	<tr><td>&nbsp;</td><td><input type="hidden" name="opp" value="savecn"><input type="submit" name="button" value="Simpan" class="button"></td></tr>
	</table>';

if ($_REQUEST['opp']=="savecn") {
    if (!$_REQUEST['keterangantolakbatal']) {
        $errorketerangan .='<blink><font color=red>Silahkan isi Alasan pengajuan ditolak/dibatalkan</font></blink><br>';
    }
    if ($errorketerangan) {
        echo '<center>'.$errorketerangan.'</center>';
    } else {
        $metRef = $database->doQuery('UPDATE fu_ajk_cn_tempf SET confirm_claim="Rejected", update_by="' . $q['nm_lengkap'] . '", keterangan="'.$_REQUEST['keterangantolakbatal'].'", update_time="' . $futgl . '", del="1" WHERE id="' . $_REQUEST['idref'] . '"');
        $metRefPeserta = $database->doQuery('UPDATE fu_ajk_peserta SET status_aktif="Inforce", status_peserta = NULL, id_klaim = "" WHERE id_cost="' . $met_tempCN['id_cost'] . '" AND id_polis="' . $met_tempCN['id_nopol'] . '" AND id_peserta="' . $met_tempCN['id_peserta'] . '" ');
        $metKlaim = $database->doQuery('UPDATE fu_ajk_klaim SET del = 1 WHERE id_peserta="' . $met_tempCN['id_peserta'] . '" ');
        unlink($metpath . $met_tempCN['fname']);

        $metRefPeserta_batal = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_cost="' . $met_tempCN['id_cost'] . '" AND id_polis="' . $met_tempCN['id_nopol'] . '" AND id_peserta="' . $met_tempCN['id_peserta'] . '" '));
        if ($q['id_cost'] == $met_tempCN['id_cost'] and $q['id_polis'] == $met_tempCN['id_nopol'] and $q['nm_user'] == $met_tempCN['input_by']) {
            $mail_batal_spv = mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE id_cost="' . $met_tempCN['id_cost'] . '" AND id_polis="" AND level="6" AND del IS NULL'));
            $message .= 'To ' . $mail_batal_spv['nm_lengkap'] . ',<br /><br />Data Refund peserta atas nama ' . $metRefPeserta_batal['nama'] . ' telah dibatalkan pengajuan Refundnya oleh ' . $met_tempCN['input_by'] . ' pada tanggal ' . $futgldn . '.<br /><br />Terimakasih, <br />' . $met_tempCN['input_by'] . '.';

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

            $mail->SetFrom($q['email'], $q['nm_lengkap']); //From address of the mail

            while ($mail_batal_spv_ = mysql_fetch_array($mail_batal_spv)) {
                $mail->AddAddress($mail_batal_spv_['email'], $mail_batal_spv_['nm_lengkap']); //To address who will receive this email
            }

            $mailclient = $database->doQuery('SELECT * FROM fu_ajk_mail WHERE type="Refund" AND status="Aktif"');
            while ($_mailclient = mysql_fetch_array($mailclient)) {
                $mail->AddAddress($_mailclient['emailto'], $_mailclient['emailnama']); //To address who will receive this email
            }

            $mail->Subject = "AJKOnline - BATAL DATA REFUND"; //Subject od your mail
            $mail->AddBCC("adn.info.notif@gmail.com");
            // $mail->AddCC("rahmad@adonaits.co.id");

            $mail->MsgHTML($message); //Put your body of the message you can place html code here
            $send = $mail->Send(); //Send the mails
        // echo $message;
        } elseif ($q['id_cost'] == $met_tempCN['id_cost'] and $q['id_polis'] == "" and $q['level'] == "6") {
            $mail_batal_staff = mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE id_cost="' . $met_tempCN['id_cost'] . '" AND nm_user="' . $met_tempCN['input_by'] . '" AND del IS NULL'));
            $message .= 'To ' . $mail_batal_staff['nm_lengkap'] . ',<br /><br />Data Refund peserta atas nama ' . $metRefPeserta_batal['nama'] . ' telah dibatalkan pengajuan Refundnya oleh ' . $q['nm_user'] . ' pada tanggal ' . $futgldn . '.<br /><br />Terimakasih, <br />' . $q['nm_user'] . '.';

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

            while ($mail_batal_staff_ = mysql_fetch_array($mail_batal_staff)) {
                $mail->AddAddress($mail_batal_staff_['email'], $mail_batal_staff_['nm_lengkap']); //To address who will receive this email
            }

            $mailclient = $database->doQuery('SELECT * FROM fu_ajk_mail WHERE type="Refund" AND status="Aktif"');
            while ($_mailclient = mysql_fetch_array($mailclient)) {
                $mail->AddAddress($_mailclient['emailto'], $_mailclient['emailnama']); //To address who will receive this email
            }

            $mail->SetFrom($q['email'], $q['nm_lengkap']); //From address of the mail
        $mail->Subject = "AJKOnline - BATAL DATA REFUND"; //Subject od your mail
        $mail->AddBCC("adn.info.notif@gmail.com");
            // $mail->AddCC("rahmad@adonaits.co.id");

            $mail->MsgHTML($message); //Put your body of the message you can place html code here
        $send = $mail->Send(); //Send the mails
        // echo $message;
        } else {
            header("location:ajk_klaim.php?er=valRefund");
        }
        header("location:ajk_klaim.php?er=valRefund");
        ;
    }
}
        ;
        break;

case "reqbatalklaim":
    $met_cn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cn WHERE id="' . $_REQUEST['idref'] . '"'));
    $met_dn = mysql_fetch_array($database->doQuery('SELECT id, dn_kode FROM fu_ajk_dn WHERE id="' . $met_cn['id_dn'] . '"'));
    $met_peserta = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_cost="' . $met_cn['id_cost'] . '" AND id_polis="' . $met_cn['id_nopol'] . '" AND id_peserta="' . $met_cn['id_peserta'] . '"'));

echo '<form method="post" action="">
   		<table border="0" width="100%" cellpadding="3" cellspacing="1">
   		<tr><th width="10%">Nomor DN</th>
    		<th width="8%">ID Peserta</th>
    		<th>Nama Tertanggung</th>
    		<th width="1%">Tanggal Lahir</th>
    		<th width="1%">Usia</th>
    		<th width="1%">Tgl Akad</th>
    		<th width="1%">Tenor</th>
    		<th width="1%">Tgl Akhir</th>
    		<th width="1%">Plafond</th>
    		<th width="1%">Premi</th>
    		<th width="1%">Tgl Refund</th>
    		<th width="1%">Nilai Refund</th>
    		<th width="1%">Status</th>
    		<th width="5%">Cabang</th>
    		<th width="1%">User</th>
    		</tr>';
        echo '<input type="hidden" name="idref" value="'.$_REQUEST['idref'].'">
    		<tr>
    		<td align="center"><a href="aajk_report.php?er=_kwipeserta&idn=' . $met_cn_['id_dn'] . '" target="_blank">' . $met_dn['dn_kode'] . '</a></td>
    		<td align="center">' . $met_cn['id_peserta'] . '</td>
    		<td>'.$met_peserta['nama'].'</td>
    		<td align="center">' . _convertDate($met_peserta['tgl_lahir']) . '</td>
    		<td align="center">' . $met_peserta['usia'] . '</td>
    		<td align="center">' . _convertDate($met_peserta['kredit_tgl']) . '</td>
    		<td align="center">' . $met_peserta['kredit_tenor'] . '</td>
    		<td align="center">' . _convertDate($met_peserta['kredit_akhir']) . '</td>
    		<td align="right">' . duit($met_peserta['kredit_jumlah']) . '</td>
    		<td align="right"><b>' . duit($met_peserta['totalpremi']) . '<b></td>
    		<td align="center">' . _convertDate($met_cn['tgl_claim']) . '</td>
    		<td align="right"><font color="red">' . duit($met_cn['total_claim']) . '</font></td>
    		<td align="center">' . $met_cn['confirm_claim'] . '</td>
    		<td align="center">' . $met_cn['id_cabang'] . '</td>
    		<td align="center">' . $met_cn['input_by'] . '</td>
    		 </tr>';
        echo '<tr><td valign="top">Alasan pengajuan klaim meninggal ditolak/dibatalkan<font color="red"> *</font></td>
    			  <td><textarea name="keterangantolakbatal" type="text" cols="30" rows="2"placeholder="Keterangan data pengajuan klaim di batalkan/ditolak">'.$_REQUEST['keterangantolakbatal'].'</textarea></td>
    		</tr>
    		<tr><td>&nbsp;</td><td><input type="hidden" name="opp" value="delregklaim"><input type="submit" name="button" value="Simpan" class="button"></td></tr>
    		</table>';
        if ($_REQUEST['opp']=="delregklaim") {
            if (!$_REQUEST['keterangantolakbatal']) {
                $errorketerangan .='<blink><font color=red>Silahkan isi Alasan pengajuan klaim ditolak/dibatalkan</font></blink><br>';
            }
            if ($errorketerangan) {
                echo '<center>'.$errorketerangan.'</center>';
            } else {
                $metRef = $database->doQuery('UPDATE fu_ajk_cn SET confirm_claim="Rejected", update_by="' . $q['nm_lengkap'] . '", keterangan="'.$_REQUEST['keterangantolakbatal'].'", update_time="' . $futgl . '", del="1" WHERE id="' . $_REQUEST['idref'] . '"');
                $metRefPeserta = $database->doQuery('UPDATE fu_ajk_peserta SET status_aktif="Inforce", status_peserta = NULL WHERE id_cost="' . $met_cn['id_cost'] . '" AND id_polis="' . $met_cn['id_nopol'] . '" AND id_peserta="' . $met_cn['id_peserta'] . '" ');
                unlink($metpath . $met_cn['fname']);

                $metRefPeserta_batal = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_cost="' . $met_cn['id_cost'] . '" AND id_polis="' . $met_cn['id_nopol'] . '" AND id_peserta="' . $met_cn['id_peserta'] . '" '));
                if ($q['id_cost'] == $met_cn['id_cost'] and $q['id_polis'] == $met_cn['id_nopol'] and $q['nm_user'] == $met_cn['input_by']) {
                    $mail_batal_spv = mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE id_cost="' . $met_cn['id_cost'] . '" AND id_polis="" AND level="6" AND del IS NULL'));
                    $message .= 'To ' . $mail_batal_spv['nm_lengkap'] . ',<br /><br />Data Klaim meninggal atas nama ' . $metRefPeserta_batal['nama'] . ' telah dibatalkan pengajuan klaimnya oleh ' . $met_cn['input_by'] . ' pada tanggal ' . $futgldn . '.<br /><br />Terimakasih, <br />' . $met_cn['input_by'] . '.';

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

                    while ($mail_batal_spv_ = mysql_fetch_array($mail_batal_spv)) {
                        $mail->AddAddress($mail_batal_spv_['email'], $mail_batal_spv_['nm_lengkap']); //To address who will receive this email
                    }

                    $mailclient = $database->doQuery('SELECT * FROM fu_ajk_mail WHERE type="Klaim" AND status="Aktif"');
                    while ($_mailclient = mysql_fetch_array($mailclient)) {
                        $mail->AddAddress($_mailclient['emailto'], $_mailclient['emailnama']); //To address who will receive this email
                    }

                    $mail->SetFrom($q['email'], $q['nm_lengkap']); //From address of the mail
                    $mail->Subject = "AJKOnline - BATAL DATA KLAIM MENINGGAL"; //Subject od your mail

                    $mail->AddBCC("adn.info.notif@gmail.com");
                    // $mail->AddCC("rahmad@adonaits.co.id");
                    $mail->MsgHTML($message); //Put your body of the message you can place html code here
                    $send = $mail->Send(); //Send the mails
                    // echo $message;
                } elseif ($q['id_cost'] == $met_cn['id_cost'] and $q['id_polis'] == "" and $q['level'] == "6") {
                    $mail_batal_staff = mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE id_cost="' . $met_cn['id_cost'] . '" AND id_polis="' . $met_cn['id_nopol'] . '" AND nm_user="' . $met_cn['input_by'] . '" AND del IS NULL'));
                    $message .= 'To ' . $mail_batal_staff['nm_lengkap'] . ',<br /><br />Data Klaim meninggal atas nama ' . $metRefPeserta_batal['nama'] . ' telah dibatalkan pengajuan klaimnya oleh ' . $q['nm_user'] . ' pada tanggal ' . $futgldn . '.<br /><br />Terimakasih, <br />' . $q['nm_user'] . '.';

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

                    while ($mail_batal_staff_ = mysql_fetch_array($mail_batal_staff)) {
                        $mail->AddAddress($mail_batal_staff_['email'], $mail_batal_staff_['nm_lengkap']); //To address who will receive this email
                    }

                    $mailclient = $database->doQuery('SELECT * FROM fu_ajk_mail WHERE type="Klaim" AND status="Aktif"');
                    while ($_mailclient = mysql_fetch_array($mailclient)) {
                        $mail->AddAddress($_mailclient['emailto'], $_mailclient['emailnama']); //To address who will receive this email
                    }

                    $mail->SetFrom($q['email'], $q['nm_lengkap']); //From address of the mail
                    $mail->Subject = "AJKOnline - BATAL DATA KLAIM MENINGGAL"; //Subject od your mail

                    $mail->AddBCC("adn.info.notif@gmail.com");
                    // $mail->AddCC("rahmad@adonaits.co.id");
                    $mail->MsgHTML($message); //Put your body of the message you can place html code here
                    $send = $mail->Send(); //Send the mails
                    // echo $message;
                } else {
                    header("location:ajk_klaim.php?er=valKlaim");
                }
                header("location:ajk_klaim.php?er=valKlaim");
                ;
            }
        }
    ;
    break;


    case "valRefund":
echo '<table border="0" cellpadding="5" cellspacing="1" width="100%"><tr><th width="100%" align="left">Modul Validasi Refund</font></th></tr></table>';
$fu1 = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="' . $q['id_cost'] . '"'));
$fu2 = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="' . $q['id_polis'] . '"'));

//UPLOAD DOKUMEN FORM BATAL ATAU REFUND
if ($_REQUEST['ref']=="UploadFile") {
    $cekTempRefund = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cn_tempf WHERE id="'.$_REQUEST['deb'].'"'));
    if ($_REQUEST['el'] == "upload_refund") {
        if ($_FILES['userfile']['name'] == "") {
            $error2 .= '<tr><td colspan="2" align="center"><font color="red">Silahkan upload dokumen Refund.</font></td></tr>';
        } elseif (!in_array($_FILES['userfile']['type'], $allowedExts)) {
            $error3 .= '<tr><td colspan="2" align="center"><font color="red">File harus Format PDF atau JPG!.</font></td></tr>';
        } elseif ($_FILES['userfile']['size'] / 1024 > $met_spaksize) {
            $error4 .= '<tr><td colspan="2" align="center"><font color=red>File tidak boleh lebih dari 2Mb !.</font></td></tr>';
        } else {
        }
        if ($error1 or $error2 or $error3 or $error4) {
        } else {
            move_uploaded_file($_FILES['userfile']['tmp_name'], $metpath.'REFUND_'.$cekTempRefund['id_peserta'].'_'.$_FILES["userfile"]["name"]);
            $metrefundcn = $database->doQuery('UPDATE fu_ajk_cn_tempf SET fname="REFUND_'.$cekTempRefund['id_peserta'].'_'.$_FILES["userfile"]["name"].'" WHERE id="'.$_REQUEST['deb'].'"');
            header("location:ajk_klaim.php?er=valRefund");
        }
    }
    echo '<form method="post" action="" enctype="multipart/form-data">
		<input type="hidden" name="deb" value="'.$_REQUEST['deb'].'">
		<table border="0" width="100%" cellpadding="1" cellspacing="1">
		'.$error2.''.$error3.''.$error4.'
    	<tr><td width="50%" align="right">File Refund (<font size="1">PDF</font>)<font color="red">*</font></td>
    		<td valign="top">:<input name="userfile" type="file" size="50" onchange="checkfile(this);">
    						  <input type="hidden" name="el" value="upload_refund"><input type="submit" name="upload" value="Proses">
    		</td>
    	</tr>
		</table>
	  </form>';
}
//UPLOAD DOKUMEN FORM BATAL ATAU REFUND

//HAPUS DOKUMEN//
if ($_REQUEST['dL']=="delDok") {
    $delDokRef = $database->doQuery('UPDATE fu_ajk_cn_tempf SET fname = null WHERE id="'.$_REQUEST['deb'].'"');
    header("location:ajk_klaim.php?er=valRefund");
}
//HAPUS DOKUMEN//
// APPROVE DATA OLEH SPV PUSAT
if ($q['status'] == "SUPERVISOR-ADMIN") {
    echo '<form method="post" action="">
	  	<table border="0" width="100%" cellpadding="3" cellspacing="1" bgcolor="#bde0e6">
		<tr><th width="1%">Hapus</th>
			<th width="5%" align="center">ALL<br /><input type="checkbox" id="selectall"/></th>
			<th width="1%">No</th>
			<th width="10%">Nomor DN</th>
			<th width="8%">ID Peserta</th>
			<th>Nama Tertanggung</th>
			<th width="7%">Tanggal Lahir</th>
			<th width="1%">Usia</th>
			<th width="6%">Tgl Akad</th>
			<th width="1%">Tenor</th>
			<th width="6%">Tgl Akhir</th>
			<th width="7%">Plafond</th>
			<th width="7%">Premi</th>
			<th width="6%">Tgl Refund</th>
			<th width="6%">Nilai Refund</th>
			<th width="5%">Status</th>
			<th width="5%">Cabang</th>
			<th width="5%">User</th>
			<th width="5%">Dokumen</th>
			</tr>';
    $met_cn = $database->doQuery('SELECT * FROM fu_ajk_cn_tempf WHERE id_cost=' . $q['id_cost'] . ' ' . $_produk . ' AND confirm_claim="Processing" AND type_claim="Refund" AND validasi_cn_uw="ya" ' . $_userRef . ' AND del IS NULL ORDER BY id DESC');
    $totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_cn_tempf WHERE id !="" AND id_cost="' . $q['id_cost'] . '" ' . $_produk . ' AND confirm_claim="Processing" AND type_claim="Refund" ' . $_userRef . ' AND del IS NULL'));
    $totalRows = $totalRows[0];
    $_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
    while ($met_cn_ = mysql_fetch_array($met_cn)) {
        $met_dn = mysql_fetch_array($database->doQuery('SELECT id, dn_kode FROM fu_ajk_dn WHERE id="' . $met_cn_['id_dn'] . '"'));
        $met_peserta = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_cost="' . $met_cn_['id_cost'] . '" AND id_polis="' . $met_cn_['id_nopol'] . '" AND id_peserta="' . $met_cn_['id_peserta'] . '"'));

        if ($q['id_cost'] == $met_cn_['id_cost'] and $q['id_polis'] == "") {
            $dataceklist = '<input type="checkbox" class="case" name="nameRef[]" value="' . $met_cn_['id'] . '">';
        } else {
            $dataceklist = '';
        }

        if (($no % 2) == 1) {
            $objlass = 'tbl-odd';
        } else {
            $objlass = 'tbl-even';
        }
        echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
			<td align="center"><a title="Batalkan data refund" href="ajk_klaim.php?er=reqbatal&idref=' . $met_cn_['id'] . '" onClick="if(confirm(\'Apakah anda yakin untuk membatalkan data peserta refund ini ?\')){return true;}{return false;}"><img src="image/delete.gif"></a></td>
		    <td align="center">' . $dataceklist . '</td>
			<td align="center">' . (++$no + ($pageNow - 1) * 25) . '</td>
			<td align="center"><a href="aajk_report.php?er=_kwipeserta&idn=' . $met_cn_['id_dn'] . '" target="_blank">' . $met_dn['dn_kode'] . '</a></td>
			<td align="center">' . $met_cn_['id_peserta'] . '</td>
			<td>' . $met_peserta['nama'] . '</td>
			<td align="center">' . _convertDate($met_peserta['tgl_lahir']) . '</td>
			<td align="center">' . $met_peserta['usia'] . '</td>
			<td align="center">' . _convertDate($met_peserta['kredit_tgl']) . '</td>
			<td align="center">' . $met_peserta['kredit_tenor'] . '</td>
			<td align="center">' . _convertDate($met_peserta['kredit_akhir']) . '</td>
			<td align="right">' . duit($met_peserta['kredit_jumlah']) . '</td>
			<td align="right"><b>' . duit($met_peserta['totalpremi']) . '<b></td>
			<td align="center">' . _convertDate($met_cn_['tgl_claim']) . '</td>
			<td align="right"><font color="red">' . duit($met_cn_['total_claim']) . '</font></td>
			<td align="center">' . $met_cn_['type_claim'] . '</td>
			<td align="center">' . $met_cn_['id_cabang'] . '</td>
			<td align="center">' . $met_cn_['input_by'] . '</td>
			<td align="center"><a title="lihat dokumen refund" href="ajk_file/_spak/' . $met_cn_['fname'] . '" target="_blank">View</a></td>
		  </tr>';
    }
    echo '<tr><td colspan="20" align="center"><a href="#" onClick="if(confirm(\'Apakah anda sudah yakin dengan semua data peserta refund ini ?\')){return true;}{return false;}"><input type="hidden" name="er" Value="val_app_refSPV"><input type="submit" name="ve" Value="Approve">
	  </td></tr>
	  </table>';
} //APPROVE DATA OLEH SPV PUSAT
else {
    echo '<fieldset style="padding: 1">
	<legend align="center">Data Refund</legend>
	<form name="f2" method="post" action="">
	<table border="0" width="100%" cellpadding="1" cellspacing="1">
	<tr><td width="15%">Nama Perusahaan</td><td>: <input type="hidden" name="idcost" value="' . $q['id_cost'] . '">' . $fu1['name'] . '</td></tr>';
    if ($q['id_polis'] == "" and $q['level'] == "6") {
        $met_polis = $database->doQuery('SELECT * FROM fu_ajk_polis WHERE id_cost="' . $q['id_cost'] . '"');
        echo '<tr><td>Nama Produk <font color="red">*</font></td>
	  <td>: <select name="id_polis">
			<option value="">---Pilih Produk---</option>';
        while ($met_polis_ = mysql_fetch_array($met_polis)) {
            echo '<option value="' . $met_polis_['id'] . '"' . _selected($_REQUEST['id_polis'], $met_polis_['id']) . '>' . $met_polis_['nmproduk'] . '</option>';
        }
        echo '</select></td></tr>
	  <tr><td>Staff <font color="red">*</font></td><td>';
        $jData_User = $database->doQuery('SELECT id_cn, id_dn, id_cost, id_nopol, COUNT(id_peserta) AS jData, input_by, del FROM fu_ajk_cn_tempf WHERE confirm_claim = "Pending" AND validasi_cn_uw="tdk" AND del IS NULL GROUP BY id_cost, id_nopol, input_by');
        echo ': <select name="user_input"><option value="">---Pilih Staff---</option>';
        while ($jData_User_ = mysql_fetch_array($jData_User)) {
            echo '<option value="' . $jData_User_['input_by'] . '"' . _selected($_REQUEST['user_input'], $jData_User_['input_by']) . '>' . $jData_User_['input_by'] . '</option>';
        }
        echo '</td></tr>
	<tr><td><input name="Submit" type="submit" value="Pilih"></td></tr>';
        $data = $database->doQuery('SELECT * FROM fu_ajk_peserta_tempf WHERE nama !="" AND id_cost="' . $q['id_cost'] . '" AND id_polis="' . $_REQUEST['id_polis'] . '" AND status_aktif="Upload" AND del IS NULL ORDER BY input_time DESC, namafile ASC, no_urut ASC');
    } else {
        // echo '<tr><td>Nama Produk</td><td>: '.$fu2['nmproduk'].'</td></tr>';
        $data = $database->doQuery('SELECT * FROM fu_ajk_peserta_tempf WHERE nama !="" AND id_cost="' . $q['id_cost'] . '" AND id_polis="' . $q['id_polis'] . '" AND input_by="' . $q['nm_user'] . '" AND status_aktif="Upload" AND del IS NULL ORDER BY input_time DESC, namafile ASC, no_urut ASC');
    }
    echo '<table></form></fieldset>';

    if ($q['id_cost'] != "" and $q['level'] == "6") {
        if ($q['wilayah']=="PUSAT" and $q['cabang']=="PUSAT") {
            $met_checked = '<th width="5%">&nbsp; &nbsp; &nbsp; &nbsp; <input type="checkbox" id="selectall"/></th>';
        } elseif ($q['wilayah']!="PUSAT" and $q['cabang']=="PUSAT") {
            $cekCentral = $database->doQuery('SELECT fu_ajk_regional.`name` AS regional, fu_ajk_cabang.`name` AS cabang
									  FROM fu_ajk_regional
									  INNER JOIN fu_ajk_cabang ON fu_ajk_regional.id = fu_ajk_cabang.id_reg
									  WHERE fu_ajk_regional.`name` = "'.$q['wilayah'].'" AND fu_ajk_cabang.del IS NULL');
            while ($cekCentral__ = mysql_fetch_array($cekCentral)) {
                $metCabangCentral .= 'OR id_cabang ="'.$cekCentral__['cabang'].'"';
            }
            $metCabangCentral = '(id_cabang ="'.$q['cabang'].'" '.$metCabangCentral.')';
            $_userRef = 'AND ' . $metCabangCentral . '';
        } else {
            $cekCentral = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE name="' . $q['cabang'] . '"'));
            $cekCentral_ = $database->doQuery('SELECT * FROM fu_ajk_cabang WHERE centralcbg="' . $cekCentral['id'] . '"');
            while ($cekCentral__ = mysql_fetch_array($cekCentral_)) {
                $metCentralCabang .= ' OR id_cabang ="' . $cekCentral__['name'] . '"';
            }
            // CEK DATA CABANG CENTRAL;
            if ($metCentralCabang == "") {
                $metCabangCentral = 'id_cabang ="' . $q['cabang'] . '"';
            } else {
                $metCabangCentral = '(id_cabang ="' . $q['cabang'] . '" ' . $metCentralCabang . ')';
            }
            // $_produk = 'AND id_nopol="'.$_REQUEST['id_polis'].'"';
            $_userRef = 'AND ' . $metCabangCentral . '';
            $met_checked = '<th width="5%">Check<input type="checkbox" id="selectall"/></th>';
        }
    } else {
        // $_produk = 'AND id_nopol="'.$q['id_polis'].'"';
        $_userRef = 'AND input_by="' . $q['nm_user'] . '"';
        $met_checked = '<th width="5%">Option</th>';
    }

    if ($_REQUEST['Submit'] == "Pilih") {
        if (!$_REQUEST['id_polis']) {
            $error1 .= '<font color="red">Silahkan pilih produk !.</font><br />';
        }
        if (!$_REQUEST['user_input']) {
            $error2 .= '<font color="red">Silahkan pilih staff !.</font>';
        }
        if ($error1 or $error2) {
            echo '<center>' . $error1 . '' . $error2 . '<meta http-equiv="refresh" content="2;URL=ajk_klaim.php?er=valRefund"></center>';
        } else {
            echo '<form method="post" action="">
	  	<table border="0" width="100%" cellpadding="3" cellspacing="1" bgcolor="#bde0e6">
		<tr>' . $met_checked . '
			<th width="1%">No</th>
			<th width="10%">Nomor DN</th>
			<th width="8%">ID Peserta</th>
			<th>Nama Tertanggung</th>
			<th width="1%">Tanggal Lahir</th>
			<th width="1%">Usia</th>
			<th width="1%">Tgl Akad</th>
			<th width="1%">Tenor</th>
			<th width="1%">Tgl Akhir</th>
			<th width="1%">Plafond</th>
			<th width="1%">Premi</th>
			<th width="1%">Tgl Refund</th>
			<th width="1%">Nilai Refund</th>
			<th width="1%">Status</th>
			<th width="5%">Cabang</th>
			<th width="5%">Dokumen</th>
			<th width="1%">User</th>
			</tr>';

            if ($_REQUEST['x']) {
                $m = ($_REQUEST['x'] - 1) * 25;
            } else {
                $m = 0;
            }
            $met_cn = $database->doQuery('SELECT * FROM fu_ajk_cn_tempf WHERE id_cost=' . $q['id_cost'] . ' ' . $_produk . ' AND confirm_claim="Pending" AND type_claim="Refund" AND validasi_cn_uw="tdk" ' . $_userRef . ' AND del IS NULL ORDER BY id DESC');
            $totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_cn_tempf WHERE id !="" AND id_cost="' . $q['id_cost'] . '" ' . $_produk . ' AND confirm_claim="Pending" AND type_claim="Refund" ' . $_userRef . ' AND del IS NULL'));
            $totalRows = $totalRows[0];
            $_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
            while ($met_cn_ = mysql_fetch_array($met_cn)) {
                $met_dn = mysql_fetch_array($database->doQuery('SELECT id, dn_kode FROM fu_ajk_dn WHERE id="' . $met_cn_['id_dn'] . '"'));
                $met_peserta = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_cost="' . $met_cn_['id_cost'] . '" AND id_polis="' . $met_cn_['id_nopol'] . '" AND id_peserta="' . $met_cn_['id_peserta'] . '"'));
                // if ($q['id_cost']==$met_cn_['id_cost'] AND $q['id_polis']=="") {
                if ($q['id_cost'] == $met_cn_['id_cost'] and $q['level'] == "6" and $q['status'] == "SUPERVISOR") {
                    $dataceklist = '<input type="checkbox" class="case" name="nameRef[]" value="' . $met_cn_['id'] . '">';
                } else {
                    $dataceklist = '';
                }

                //DOKUMEN APABILA BELUM DIUPLOAD//
                if ($met_cn_['fname'] == null) {
                    $formDokumen = '<a href="aajk_report.php?er=formBtlRef&r='.$met_cn_['type_refund'].'&deb='.$_REQUEST['id'].'" target="blank">Download</a>';
                } else {
                    $formDokumen = '<a href="aajk_report.php?er=formBtlRef&r='.$met_cn_['type_refund'].'&deb='.$_REQUEST['id'].'" target="blank">View</a> | <a href="#">Hapus</a>';
                }
                //DOKUMEN APABILA BELUM DIUPLOAD//
                if (($no % 2) == 1) {
                    $objlass = 'tbl-odd';
                } else {
                    $objlass = 'tbl-even';
                }
                echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		<td align="center"><a title="Batalkan data refund" href="ajk_klaim.php?er=reqbatal&idref=' . $met_cn_['id'] . '" onClick="if(confirm(\'Apakah anda yakin untuk membatalkan data peserta refund ini ?\')){return true;}{return false;}"><img src="image/delete.gif"></a>&nbsp;' . $dataceklist . '</td>
		<td align="center">' . (++$no + ($pageNow - 1) * 25) . '</td>
		<td align="center"><a href="aajk_report.php?er=_kwipeserta&idn=' . $met_cn_['id_dn'] . '" target="_blank">' . $met_dn['dn_kode'] . '</a></td>
		<td align="center">' . $met_cn_['id_peserta'] . '</td>
		<td><a title="lihat dokumen refund" href="ajk_file/_spak/' . $met_cn_['fname'] . '" target="_blank">' . $met_peserta['nama'] . '</a></td>
		<td align="center">' . _convertDate($met_peserta['tgl_lahir']) . '</td>
		<td align="center">' . $met_peserta['usia'] . '</td>
		<td align="center">' . _convertDate($met_peserta['kredit_tgl']) . '</td>
		<td align="center">' . $met_peserta['kredit_tenor'] . '</td>
		<td align="center">' . _convertDate($met_peserta['kredit_akhir']) . '</td>
		<td align="right">' . duit($met_peserta['kredit_jumlah']) . '</td>
		<td align="right"><b>' . duit($met_peserta['totalpremi']) . '<b></td>
		<td align="center">' . _convertDate($met_cn_['tgl_claim']) . '</td>
		<td align="right"><font color="red">' . duit($met_cn_['total_claim']) . '</font></td>
		<td align="center">' . $met_cn_['type_claim'] . '</td>
		<td align="center">' . $met_cn_['id_cabang'] . '</td>
		<td align="center">' . $formDokumen . '</td>
		<td align="center">' . $met_cn_['input_by'] . '</td>
	  </tr>';
            }
            // if ($q['id_cost'] !="" AND $q['id_polis']=="" AND $q['level']=="6" AND $q['status']=="") {
            if ($q['id_cost'] != "" and $q['level'] == "6" and $q['status'] == "SUPERVISOR") {
                echo '<tr><td colspan="20" align="center"><a href="#" onClick="if(confirm(\'Apakah anda sudah yakin dengan semua data peserta refund ini ?\')){return true;}{return false;}">
		<input type="hidden" name="er" Value="val_app_ref"><input type="submit" name="ve" Value="Approve"></td></tr>';
            } else {
            }
            echo '</table>';
        }
    } else {
        if ($q['id_cost'] != "" and $q['id_polis'] == "") {
            echo '<table border="0" width="100%" cellpadding="3" cellspacing="1" bgcolor="#bde0e6">
		  <tr><th width="1%">No</th>
	  		  <th>Nama Produk</th>
	  	  	  <th width="20%">Jumlah Data Refund</th>
	  	  	  <th width="15%">User</th>
	  	</tr>';
            $jData = $database->doQuery('SELECT id_cn, id_dn, id_cost, id_nopol, COUNT(id_peserta) AS jData, input_by, del
													   FROM fu_ajk_cn_tempf
													   WHERE confirm_claim = "Pending" AND validasi_cn_uw="tdk" AND del IS NULL
													   GROUP BY id_cost, id_nopol, input_by');
            while ($jData_ = mysql_fetch_array($jData)) {
                $met_produk = mysql_fetch_array($database->doQuery('SELECT id, nmproduk FROM fu_ajk_polis WHERE id="' . $jData_['id_nopol'] . '"'));
                if (($no % 2) == 1) {
                    $objlass = 'tbl-odd';
                } else {
                    $objlass = 'tbl-even';
                }
                echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		  	<td align="center">' . ++$no . '</td>
		  	<td>' . $met_produk['nmproduk'] . '</td>
		  	<td align="center">' . $jData_['jData'] . ' Data Refund</td>
		  	<td align="center">' . $jData_['input_by'] . '</td>
		  </tr>';
            }
            echo '</table>';
        } else {
            echo '<form method="post" action="">
	  	<table border="0" width="100%" cellpadding="3" cellspacing="1" bgcolor="#bde0e6">
		<tr>' . $met_checked . '
			<th width="1%">No</th>
			<th width="10%">Nomor DN</th>
			<th width="8%">ID Peserta</th>
			<th>Nama Tertanggung</th>
			<th width="1%">Tanggal Lahir</th>
			<th width="1%">Usia</th>
			<th width="1%">Tgl Akad</th>
			<th width="1%">Tenor</th>
			<th width="1%">Tgl Akhir</th>
			<th width="1%">Plafond</th>
			<th width="1%">Premi</th>
			<th width="1%">Tgl Refund</th>
			<!--<th width="1%">Nilai Refund</th>-->
			<th width="1%">Status</th>
			<th width="5%">Cabang</th>
			<th width="1%">User</th>
			<th width="5%">Download</th>
			<th width="5%">Upload</th>
			</tr>';
            if ($_REQUEST['x']) {
                $m = ($_REQUEST['x'] - 1) * 25;
            } else {
                $m = 0;
            }

            $met_cn = $database->doQuery('SELECT * FROM fu_ajk_cn_tempf WHERE id_cost=' . $q['id_cost'] . ' ' . $_produk . ' AND confirm_claim="Pending" AND type_claim="Refund" ' . $_userRef . ' AND del IS NULL ORDER BY id DESC');
            $totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_cn_tempf WHERE id !="" AND id_cost="' . $q['id_cost'] . '" ' . $_produk . ' AND confirm_claim="Pending" AND type_claim="Refund" ' . $_userRef . ' AND del IS NULL'));
            $totalRows = $totalRows[0];
            $_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
            while ($met_cn_ = mysql_fetch_array($met_cn)) {
                $met_dn = mysql_fetch_array($database->doQuery('SELECT id, dn_kode FROM fu_ajk_dn WHERE id="' . $met_cn_['id_dn'] . '"'));
                $met_peserta = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_cost="' . $met_cn_['id_cost'] . '" AND id_polis="' . $met_cn_['id_nopol'] . '" AND id_peserta="' . $met_cn_['id_peserta'] . '"'));

                if ($q['id_cost'] == $met_cn_['id_cost'] and $q['level'] == "6" and $met_cn_['fname'] !="") {
                    $dataceklist = '<input type="checkbox" class="case" name="nameRef[]" value="' . $met_cn_['id'] . '">';
                } else {
                    $dataceklist = '';
                }

                if ($met_cn_['validasi_cn_uw'] == "ya") {
                    $dalvaldata = '';
                } else {
                    $dalvaldata = '<a href="ajk_klaim.php?er=EdDataRefund&idref=' . $met_cn_['id'] . '" title="Edit tanggal refund"><img src="image/edit3.png" width="15"></a>
				<a title="Batalkan data refund" href="ajk_klaim.php?er=reqbatal&idref=' . $met_cn_['id'] . '" onClick="if(confirm(\'Apakah anda yakin untuk membatalkan data peserta refund ini ?\')){return true;}{return false;}"><img src="image/delete.gif" width="15"></a>';
                }

                //DOKUMEN APABILA BELUM DIUPLOAD//
                if ($met_cn_['type_refund']=="Topup") {
                    $formnya = "tp";
                } elseif ($met_cn_['type_refund']=="Lunas") {
                    $formnya = "lp";
                } else {
                    $formnya = "";
                }

                if ($met_cn_['fname'] == null) {
                    $formDokumenDL = '<a href="aajk_report.php?er=formBtlRef&r='.$formnya.'&deb='.$met_peserta['id'].'" target="blank">Download</a>';
                    $formDokumenUL = '<a href="ajk_klaim.php?er=valRefund&ref=UploadFile&deb='.$met_cn_['id'].'">Upload</a>';
                } else {
                    $formDokumenDL = '<a href="ajk_klaim.php?er=valRefund&dL=delDok&deb='.$met_cn_['id'].'" onClick="if(confirm(\'Apakah anda yakin akan menghapus dokumen ini ?\')){return true;}{return false;}">Hapus</a>';
                    $formDokumenUL = '<a title="lihat dokumen refund" href="ajk_file/_spak/' . $met_cn_['fname'] . '" target="_blank">View</a>';
                }
                //DOKUMEN APABILA BELUM DIUPLOAD//

                $inputRef_ = explode(" ", $met_cn_['input_date']);
                $mets = datediff($inputRef_[0], $met_cn_['tgl_claim']);
                $pecahtglrefund = explode(",", $mets);

                //echo $pecahtglrefund[0].'-'.$pecahtglrefund[1].'-'.$pecahtglrefund[2];
                if ($pecahtglrefund[0] <= 0 and $pecahtglrefund[1] <= 0 and $pecahtglrefund[2] <= 30) {
                    $metsTanggal = datediff($met_peserta['kredit_tgl'], $met_cn_['tgl_claim']);
                    $pecahtglrefund_ = explode(",", $metsTanggal);
                    $ketTglRef_ = '';
                } else {
                    //JIKA TGL SISTEM KE TGL REFUND > 30 HARI GUNAKAN TANGGAL SISTEM
                    $metsTanggal = datediff($met_peserta['kredit_tgl'], $inputRef_[0]);
                    $pecahtglrefund_ = explode(",", $metsTanggal);
                    $ketTglRef = $pecahtglrefund_[0] * 12 + $pecahtglrefund_[1];
                    $ketTglRef_ = 'Tanggal pengajuan sudah lebih dari 30 hari dari tanggal pelunasan (refund)';
                }

                if (($no % 2) == 1) {
                    $objlass = 'tbl-odd';
                } else {
                    $objlass = 'tbl-even';
                }
                echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		<td align="center">' . $dalvaldata . '&nbsp;' . $dataceklist . '</td>
		<td align="center">' . (++$no + ($pageNow - 1) * 25) . '</td>
		<td align="center"><a href="aajk_report.php?er=_kwipeserta&idn=' . $met_cn_['id_dn'] . '" target="_blank">' . $met_dn['dn_kode'] . '</a></td>
		<td align="center">' . $met_cn_['id_peserta'] . '</td>
		<td>'.$met_peserta['nama'].'</td>
		<td align="center">' . _convertDate($met_peserta['tgl_lahir']) . '</td>
		<td align="center">' . $met_peserta['usia'] . '</td>
		<td align="center">' . _convertDate($met_peserta['kredit_tgl']) . '</td>
		<td align="center">' . $met_peserta['kredit_tenor'] . '</td>
		<td align="center">' . _convertDate($met_peserta['kredit_akhir']) . '</td>
		<td align="right">' . duit($met_peserta['kredit_jumlah']) . '</td>
		<td align="right"><b>' . duit($met_peserta['totalpremi']) . '<b></td>
		<td align="center">' . _convertDate($met_cn_['tgl_claim']) . '</td>
		<!--<td align="right"><a title="'.$ketTglRef_.'"><font color="red">' . duit($met_cn_['total_claim']) . '</font></a></td>-->
		<td align="center">' . $met_cn_['confirm_claim'] . '</td>
		<td align="center">' . $met_cn_['id_cabang'] . '</td>
		<td align="center">' . $met_cn_['input_by'] . '</td>
		<td align="center">' . $formDokumenDL . '</td>
		<td align="center">' . $formDokumenUL . '</td>
	  </tr>';
            }
            if ($q['id_cost'] != "" and $q['id_polis'] != "" and $q['level'] == "6") {
                echo '<tr><td colspan="20" align="center"><a href="#" onClick="if(confirm(\'Apakah anda sudah yakin dengan semua data peserta refund ini ?\')){return true;}{return false;}">
		  <input type="hidden" name="er" Value="val_app_ref"><input type="submit" name="ve" Value="Approve"></td></tr>';
            } else {
            }
            echo '</table>';
        }
    }
}
    ;
    break;

    case "val_app_ref":
        if (!$_REQUEST['nameRef']) {
            echo '<center><font color=red><blink>Tidak ada data Refund peserta yang di pilih, silahkan ceklist data yang akan di Refund. !</blink></font><br/>
	  <a href="ajk_klaim.php?er=valRefund">Kembali Ke Halaman Validasi Refund</a></center>';
        } else {
            foreach ($_REQUEST['nameRef'] as $r => $Ref_) {
                $metcn_temp = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cn_tempf WHERE id ="'.$Ref_.'"'));
                //$metRefnya = $database->doQuery('UPDATE fu_ajk_cn_tempf SET confirm_claim="Processing", validasi_cn_uw="ya" WHERE id="' . $Ref_ . '"');
                if ($metcn_temp['input_by'] == "staff_mekarsari") {
                    $metRefnya = $database->doQuery('UPDATE fu_ajk_cn_tempf SET confirm_claim="Approve", validasi_cn_uw="ya" WHERE id="' . $Ref_ . '"');
                } else {
                    $metRefnya = $database->doQuery('UPDATE fu_ajk_cn_tempf SET confirm_claim="Processing", validasi_cn_uw="ya" WHERE id="' . $Ref_ . '"');
                }
                // INSERT KE TABLE CN
                //$metRefnya = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cn_tempf WHERE id="' . $Ref_ . '"'));
                /*
$met_CN = $database->doQuery('INSERT INTO fu_ajk_cn SET id_dn="'.$metRefnya['id_dn'].'",
                                                            id_cost="'.$metRefnya['id_cost'].'",
                                                            id_nopol="'.$metRefnya['id_nopol'].'",
                                                            id_peserta="'.$metRefnya['id_peserta'].'",
                                                            id_regional="'.$metRefnya['id_regional'].'",
                                                            id_cabang="'.$metRefnya['id_cabang'].'",
                                                            tgl_claim="'.$metRefnya['tgl_claim'].'",
                                                            type_claim="'.$metRefnya['type_claim'].'",
                                                            premi="'.$metRefnya['premi'].'",
                                                            total_claim="'.$metRefnya['total_claim'].'",
                                                            confirm_claim="Processing",
                                                            validasi_cn_uw="ya",
                                                            validasi_cn_arm="'.$metRefnya['validasi_cn_arm'].'",
                                                            fname="'.$metRefnya['fname'].'",
                                                            input_by="'.$metRefnya['input_by'].'",
                                                            input_date="'.$metRefnya['input_date'].'"');
*/
            }
            foreach ($_REQUEST['nameRef'] as $r1 => $Ref1_) {
                $metRefnya = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cn_tempf WHERE id="' . $Ref1_ . '"'));
                $met_mail_dn = mysql_fetch_array($database->doQuery('SELECT id, dn_kode FROM fu_ajk_dn WHERE id="' . $metRefnya['id_dn'] . '"'));
                $met_mail_peserta = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_cost="' . $metRefnya['id_cost'] . '" AND id_polis="' . $metRefnya['id_nopol'] . '" AND id_peserta="' . $metRefnya['id_peserta'] . '"'));
                $nettrefund = $met_mail_peserta['totalpremi'] - $metRefnya['total_claim'];

                if (($no % 2) == 1) {
                    $objlass = 'tbl-odd';
                } else {
                    $objlass = 'tbl-even';
                }
                $metRefnya_send .= '	<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
						<td width="1%" align="center">' . ++$no . '</td>
						<td width="10%" align="center">' . $met_mail_dn['dn_kode'] . '</td>
						<td width="8%" align="center">' . $metRefnya['id_peserta'] . '</td>
						<td>' . $met_mail_peserta['nama'] . '</td>
						<td width="7%" align="center">' . _convertDate($met_mail_peserta['tgl_lahir']) . '</td>
						<td width="1%" align="center">' . $met_mail_peserta['usia'] . '</td>
						<td width="6%" align="center">' . _convertDate($met_mail_peserta['kredit_tgl']) . '</td>
						<td width="1%" align="center">' . $met_mail_peserta['kredit_tenor'] . '</td>
						<td width="6%" align="center">' . _convertDate($met_mail_peserta['kredit_akhir']) . '</td>
						<td width="7%" align="right">' . duit($met_mail_peserta['kredit_jumlah']) . '</td>
						<td width="7%" align="right">' . duit($met_mail_peserta['totalpremi']) . '</td>
						<td width="6%" align="center">' . _convertDate($metRefnya['tgl_claim']) . '</td>
						<td width="6%" align="right">' . duit($metRefnya['total_claim']) . '</td>
						<td width="5%" align="center">' . $metRefnya['type_claim'] . '</td>
						<td width="5%" align="center">' . $metRefnya['id_cabang'] . '</td>
						</tr>';
                // $met_del_ref = mysql_fetch_array($database->doQuery('DELETE FROM fu_ajk_cn_tempf WHERE id="'.$Ref1_.'"'));	//PADA SAAT INDERT KE CN TABLE CNTEMPF DIHAPUS
            }
            // $message .='To Underwriting,<br />Terlampir data-data Refund Peserta pada table di bawah ini :	 revisi (18-11-2015)
            $message .= 'Kepada SPV PUSAT,<br />Terlampir data-data Refund Peserta pada table di bawah ini :
				<table border="1" width="100%">
				<tr bgcolor="#7CFC00"><th width="1%">No</th>
					<th width="10%">Nomor DN</th>
					<th width="8%">ID Peserta</th>
					<th>Nama</th>
					<th width="7%">Tanggal Lahir</th>
					<th width="1%">Usia</th>
					<th width="6%">Tgl Akad</th>
					<th width="1%">Tenor</th>
					<th width="6%">Tgl Akhir</th>
					<th width="7%">Plafond</th>
					<th width="7%">Premi</th>
					<th width="6%">Tgl Refund</th>
					<th width="6%">Nilai Refund</th>
					<th width="5%">Status</th>
					<th width="5%">Cabang</th>
			</tr>';
            $message .= $metRefnya_send;
            $message .= '</table>';
            $message .= 'Data peserta Refund telah di Approve oleh ' . $q['nm_lengkap'] . ' pada tanggal ' . $futgldn . ' ' . $timelog . '<br /><br />Salam,<br />' . $q['nm_lengkap'] . '';
            // echo $message;
            // SMTP UNDERWRITING
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

            $mail->SetFrom($q['email'], $q['nm_lengkap']); //From address of the mail
            /*
            $mail_SPV = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="' . $q['id_cost'] . '" AND id_polis="" AND level="6" AND status="SUPERVISOR-ADMIN" AND del IS NULL');
            while ($mail_SPV_ = mysql_fetch_array($mail_SPV)) {
                $mail->AddAddress($mail_SPV_['email'], $mail_SPV_['nm_lengkap']); //To address who will receive this email
                // echo $mail_SPV_['email'].'<br />';
            }

            $mail_staff = $database->doQuery('SELECT * FROM pengguna WHERE nm_user="' . $metRefnya['input_by'] . '" AND del IS NULL');
            while ($mail_staff_ = mysql_fetch_array($mail_staff)) {
                $mail->AddAddress($mail_staff_['email'], $mail_staff_['nm_lengkap']); //To address who will receive this email
                // echo $mail_staff_['email'].'<br />';
            }*/
            if ($metcn_temp['input_by'] == "staff_mekarsari") {
                $mail_UW = $database->doQuery('SELECT * FROM pengguna WHERE status="UNDERWRITING" AND del IS NULL');
                while ($mail_UW_ = mysql_fetch_array($mail_UW)) {
                    $mail->AddAddress($mail_UW_['email'], $mail_UW_['nm_lengkap']); //To address who will receive this email
                    // echo $mail_UW_['email'].'<br />';
                }

                $mail_staff = $database->doQuery('SELECT * FROM pengguna WHERE nm_user="' . $metRefnya['input_by'] . '" AND del IS NULL');
                while ($mail_staff_ = mysql_fetch_array($mail_staff)) {
                    $mail->AddAddress($mail_staff_['email'], $mail_staff_['nm_lengkap']); //To address who will receive this email
                    // echo $mail_staff_['email'].'<br />';
                }
            } else {
                $mail_SPV = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="' . $q['id_cost'] . '" AND id_polis="" AND level="6" AND status="SUPERVISOR-ADMIN" AND del IS NULL');
                while ($mail_SPV_ = mysql_fetch_array($mail_SPV)) {
                    $mail->AddAddress($mail_SPV_['email'], $mail_SPV_['nm_lengkap']); //To address who will receive this email
                    // echo $mail_SPV_['email'].'<br />';
                }

                $mail_staff = $database->doQuery('SELECT * FROM pengguna WHERE nm_user="' . $metRefnya['input_by'] . '" AND del IS NULL');
                while ($mail_staff_ = mysql_fetch_array($mail_staff)) {
                    $mail->AddAddress($mail_staff_['email'], $mail_staff_['nm_lengkap']); //To address who will receive this email
                    // echo $mail_staff_['email'].'<br />';
                }
            }
            $mail->Subject = "AJKOnline - APPROVE DATA REFUND"; //Subject od your mail

            $mailclient = $database->doQuery('SELECT * FROM fu_ajk_mail WHERE type="Refund" AND status="Aktif"');
            while ($_mailclient = mysql_fetch_array($mailclient)) {
                $mail->AddAddress($_mailclient['emailto'], $_mailclient['emailnama']); //To address who will receive this email
            }
            $mail->AddBCC("adn.info.notif@gmail.com");
            // $mail->AddCC("rahmad@adonaits.co.id");

            $mail->MsgHTML($message); //Put your body of the message you can place html code here
            $send = $mail->Send(); //Send the mails
            // SMTP UNDERWRITING
            // echo $message.'<br />';
echo '<div class="title2" align="center">Data Refund telah di Approve oleh ' . $q['nm_lengkap'] . ' pada tanggal ' . $futgldn . ' ' . $timelog . '.</div>
	  <meta http-equiv="refresh" content="3;URL=ajk_klaim.php?er=valRefund">';
        } ;
        break;

    case "val_app_refSPV":
        if (!$_REQUEST['nameRef']) {
            echo '<center><font color=red><blink>Tidak ada data Refund peserta yang di pilih, silahkan ceklist data yang akan di Refund. !</blink></font><br/>
	  <a href="ajk_klaim.php?er=valRefund">Kembali Ke Halaman Validasi Refund</a></center>';
        } else {
            foreach ($_REQUEST['nameRef'] as $r => $Ref_) {
                $metRefnya = $database->doQuery('UPDATE fu_ajk_cn_tempf SET confirm_claim="Approve", validasi_cn_uw="ya" WHERE id="' . $Ref_ . '"');
                // INSERT KE TABLE CN
                $metRefnya = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cn_tempf WHERE id="' . $Ref_ . '"'));
                /*
   $met_CN = $database->doQuery('INSERT INTO fu_ajk_cn SET id_dn="'.$metRefnya['id_dn'].'",
   id_cost="'.$metRefnya['id_cost'].'",
   id_nopol="'.$metRefnya['id_nopol'].'",
   id_peserta="'.$metRefnya['id_peserta'].'",
   id_regional="'.$metRefnya['id_regional'].'",
   id_cabang="'.$metRefnya['id_cabang'].'",
   tgl_claim="'.$metRefnya['tgl_claim'].'",
   type_claim="'.$metRefnya['type_claim'].'",
   premi="'.$metRefnya['premi'].'",
   total_claim="'.$metRefnya['total_claim'].'",
   confirm_claim="Processing",
   validasi_cn_uw="ya",
   validasi_cn_arm="'.$metRefnya['validasi_cn_arm'].'",
   fname="'.$metRefnya['fname'].'",
   input_by="'.$metRefnya['input_by'].'",
   input_date="'.$metRefnya['input_date'].'"');
*/
            }
            foreach ($_REQUEST['nameRef'] as $r1 => $Ref1_) {
                $metRefnya = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cn_tempf WHERE id="' . $Ref1_ . '"'));
                $met_mail_dn = mysql_fetch_array($database->doQuery('SELECT id, dn_kode FROM fu_ajk_dn WHERE id="' . $metRefnya['id_dn'] . '"'));
                $met_mail_peserta = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_cost="' . $metRefnya['id_cost'] . '" AND id_polis="' . $metRefnya['id_nopol'] . '" AND id_peserta="' . $metRefnya['id_peserta'] . '"'));
                $nettrefund = $met_mail_peserta['totalpremi'] - $metRefnya['total_claim'];

                if (($no % 2) == 1) {
                    $objlass = 'tbl-odd';
                } else {
                    $objlass = 'tbl-even';
                }
                $metRefnya_send .= '	<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
							<td width="1%" align="center">' . ++$no . '</td>
							<td width="10%" align="center">' . $met_mail_dn['dn_kode'] . '</td>
							<td width="8%" align="center">' . $metRefnya['id_peserta'] . '</td>
							<td>' . $met_mail_peserta['nama'] . '</td>
							<td width="7%" align="center">' . _convertDate($met_mail_peserta['tgl_lahir']) . '</td>
							<td width="1%" align="center">' . $met_mail_peserta['usia'] . '</td>
							<td width="6%" align="center">' . _convertDate($met_mail_peserta['kredit_tgl']) . '</td>
							<td width="1%" align="center">' . $met_mail_peserta['kredit_tenor'] . '</td>
							<td width="6%" align="center">' . _convertDate($met_mail_peserta['kredit_akhir']) . '</td>
							<td width="7%" align="right">' . duit($met_mail_peserta['kredit_jumlah']) . '</td>
							<td width="7%" align="right">' . duit($met_mail_peserta['totalpremi']) . '</td>
							<td width="6%" align="center">' . _convertDate($metRefnya['tgl_claim']) . '</td>
							<td width="6%" align="right">' . duit($metRefnya['total_claim']) . '</td>
							<td width="5%" align="center">' . $metRefnya['type_claim'] . '</td>
							<td width="5%" align="center">' . $metRefnya['id_cabang'] . '</td>
							</tr>';
                // $met_del_ref = mysql_fetch_array($database->doQuery('DELETE FROM fu_ajk_cn_tempf WHERE id="'.$Ref1_.'"'));	//PADA SAAT INDERT KE CN TABLE CNTEMPF DIHAPUS
            }
            // $message .='To Underwriting,<br />Terlampir data-data Refund Peserta pada table di bawah ini :	 revisi (18-11-2015)
            $message .= 'Kepada Underwriting,<br />Terlampir data-data Refund Peserta pada table di bawah ini :
				<table border="0" width="100%" bgcolor="#CDECDE">
				<tr><th width="1%">No</th>
					<th width="10%">Nomor DN</th>
					<th width="8%">ID Peserta</th>
					<th>Nama</th>
					<th width="7%">Tanggal Lahir</th>
					<th width="1%">Usia</th>
					<th width="6%">Tgl Akad</th>
					<th width="1%">Tenor</th>
					<th width="6%">Tgl Akhir</th>
					<th width="7%">Plafond</th>
					<th width="7%">Premi</th>
					<th width="6%">Tgl Refund</th>
					<th width="6%">Nilai Refund</th>
					<th width="5%">Status</th>
					<th width="5%">Cabang</th>
			</tr>';
            $message .= $metRefnya_send;
            $message .= '</table>';
            $message .= 'Data peserta Refund telah di Approve oleh ' . $q['nm_lengkap'] . ' pada tanggal ' . $futgldn . ' ' . $timelog . '<br /><br />Salam,<br />' . $q['nm_lengkap'] . '';
            // echo $message;
            // SMTP UNDERWRITING
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

            $mail_UW = $database->doQuery('SELECT * FROM pengguna WHERE status="UNDERWRITING" AND del IS NULL');
            while ($mail_UW_ = mysql_fetch_array($mail_UW)) {
                $mail->AddAddress($mail_UW_['email'], $mail_UW_['nm_lengkap']); //To address who will receive this email
                // echo $mail_UW_['email'].'<br />';
            }

            $mail_staff = $database->doQuery('SELECT * FROM pengguna WHERE nm_user="' . $metRefnya['input_by'] . '" AND del IS NULL');
            while ($mail_staff_ = mysql_fetch_array($mail_staff)) {
                $mail->AddAddress($mail_staff_['email'], $mail_staff_['nm_lengkap']); //To address who will receive this email
                // echo $mail_staff_['email'].'<br />';
            }

            $mailclient = $database->doQuery('SELECT * FROM fu_ajk_mail WHERE type="Refund" AND status="Aktif"');
            while ($_mailclient = mysql_fetch_array($mailclient)) {
                $mail->AddAddress($_mailclient['emailto'], $_mailclient['emailnama']); //To address who will receive this email
            }

            $mail->SetFrom($q['email'], $q['nm_lengkap']); //From address of the mail
            $mail->Subject = "AJKOnline - APPROVE DATA REFUND"; //Subject od your mail
            $mail->AddBCC("adn.info.notif@gmail.com");
            // $mail->AddCC("rahmad@adonaits.co.id");

            $mail->MsgHTML($message); //Put your body of the message you can place html code here
            $send = $mail->Send(); //Send the mails
            // SMTP UNDERWRITING
            // echo $message.'<br />';
echo '<div class="title2" align="center">Data Refund telah di Approve oleh ' . $q['nm_lengkap'] . ' pada tanggal ' . $futgldn . ' ' . $timelog . '.</div>
	  <meta http-equiv="refresh" content="3;URL=ajk_klaim.php?er=valRefund">';
        } ;
        break;

    case "settRefund":
echo '<table border="0" cellpadding="5" cellspacing="1" width="100%">
	  <tr><th width="100%" align="left">Modul Refund '.$tiperefund.'</font></th>
	  	  <th width="5%"><a href="ajk_klaim.php?er=reqrefund&r='.$_REQUEST['r'].'"><img src="image/Backward-64.png" width="20"></a></th>
	  </tr>
	  </table>';
if ($_REQUEST['el'] == "upload_refund") {
    if (!$_REQUEST['tgl_refund']) {
        $error1 .= '<font color="red">Silahkan input tanggal Refund !.</font>';
    }
    /*	BERUBAH ALUR DARI BUKOPIN 20160315
    if ($_FILES['userfile']['name'] == "") {	$error2 .= '<font color="red">Silahkan upload data Refund.</font><br />';	}
    if (!in_array($_FILES['userfile']['type'], $allowedExts)) {	$error3 .= '<font color="red">File harus Format PDF atau JPG !.</font><br />';	}
    if ($_FILES['userfile']['size'] / 1024 > $met_spaksize) {	$error4 .= '<font color=red>File tidak boleh lebih dari 2Mb !.</font>';	}
    */
    if ($error1 or $error2 or $error3 or $error4) {
    } else {
        move_uploaded_file($_FILES['userfile']['tmp_name'], $metpath . 'REFUND_' . $_REQUEST['id_peserta'] . '_' . $_FILES["userfile"]["name"]);
        $mamet = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id="' . $_REQUEST['id'] . '"'));
        $mamet_as = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta_as WHERE id_bank="'.$mamet['id_cost'].'" AND id_polis="'.$mamet['id_polis'].'" AND id_dn="'.$mamet['id_dn'].'" AND id_peserta="'.$mamet['id_peserta'].'"'));
        // CEK PERHITUNGAN REFUND PREMI
        //$metbulanplus = date('Y-m-d', strtotime('+0 month', strtotime($mamet['kredit_tgl'])));
        //if ($_REQUEST['tgl_refund'] <= $metbulanplus) {
        //echo $mamet['kredit_tgl'] .' - '. $_REQUEST['tgl_refund'].' - '.date('Y-m-d').'<br />';
        $metsRefund = datediff($mamet['kredit_tgl'], $_REQUEST['tgl_refund']);
        $metsRefund_ = explode(",", $metsRefund);
        //echo $metsRefund_[0].'-'.$metsRefund_[1].'-'.$metsRefund_[2];
        //JIKA TGL AKAD KE TGL REFUND <= 30 HARI NILAI FULL
        if ($metsRefund_[0] <= 0 and $metsRefund_[1] <= 0 and $metsRefund_[2] <= 30) {
            //echo $metsRefund.'<br />';
            //echo 'tgl akad ke tgl lunas : '.$metsRefund.'<br />';
            $premirefund = $mamet['totalpremi'];
            $premirefund_as = $mamet_as['nettpremi'];
        } else {
            // $m = ceil(abs( strtotime($mamet['kredit_akhir']) - strtotime($_REQUEST['tgl_refund']) ) / 86400);
            // $r = floor($m / 30.4375);
            //JIKA TGL AKAD KE TGL REFUND > 30 HARI NILAI FULL
            $mets = datediff(date('Y-m-d'), $_REQUEST['tgl_refund']);
            $pecahtglrefund = explode(",", $mets);
            //echo 'tgl sistem ke tgl lunas : '.$mets.'<br />';
            //JIKA TGL SISTEM KE TGL REFUND <= 30 HARI GUNAKAN TANGGAL LUNAS
            if ($pecahtglrefund[0] <= 0 and $pecahtglrefund[1] <= 0 and $pecahtglrefund[2] <= 30) {
                $metsTanggal = datediff($mamet['kredit_tgl'], $_REQUEST['tgl_refund']);
                $pecahtglrefund_ = explode(",", $metsTanggal);
            } else {
                //JIKA TGL SISTEM KE TGL REFUND > 30 HARI GUNAKAN TANGGAL SISTEM
                $metsTanggal = datediff($mamet['kredit_tgl'], date('Y-m-d'));
                $pecahtglrefund_ = explode(",", $metsTanggal);
            }
            $movementrefund = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_rasio_claim WHERE id_cost="' . $mamet['id_cost'] . '" AND id_polis="' . $mamet['id_polis'] . '"'));
            // $biayapenutupan = $mamet['totalpremi'] *  $movementrefund['refund'];
            // $premirefund = ceil(($r / $mamet['kredit_tenor']) * ($mamet['totalpremi'] - $biayapenutupan));
            if ($mamet['type_data'] == "SPK") {
                $metTenornya = $mamet['kredit_tenor'] * 12 ;
            } else {
                $metTenornya = $mamet['kredit_tenor'];
            }
            $jumbulan = $metTenornya - ($pecahtglrefund_[0] * 12 + $pecahtglrefund_[1]);
            $biayapenutupan = $mamet['totalpremi'] * $movementrefund['refund'];
            $premirefund = $jumbulan / $metTenornya * ($mamet['totalpremi'] - $biayapenutupan);

            $biayapenutupan_as = $mamet_as['nettpremi'] * $movementrefund['refund'];
            $premirefund_as = $jumbulan / $metTenornya * ($mamet_as['nettpremi'] - $biayapenutupan_as);
            // echo $premirefund;
        }
        // CEK PERHITUNGAN REFUND PREMI
        // CEK PERHITUNGAN REFUND PREMI 24082016
        $mets = datediff(date('Y-m-d'), $_REQUEST['tgl_refund']);
        $pecahtglrefund = explode(",", $mets);
        //echo 'tgl sistem ke tgl lunas : '.$mets.'<br />';
        //JIKA TGL SISTEM KE TGL REFUND <= 30 HARI GUNAKAN TANGGAL LUNAS
        if ($pecahtglrefund[0] <= 0 and $pecahtglrefund[1] <= 0 and $pecahtglrefund[2] <= 30) {
            $kondisi = ' <= 30';
            if ($metsRefund_[0] <= 0 and $metsRefund_[1] <= 0 and $metsRefund_[2] <= 30) {
                //echo $metsRefund.'<br />';
                //echo 'tgl akad ke tgl lunas : '.$metsRefund.'<br />';
                $premirefund = $mamet['totalpremi'];
                $premirefund_as = $mamet_as['nettpremi'];
            } else {
                // $m = ceil(abs( strtotime($mamet['kredit_akhir']) - strtotime($_REQUEST['tgl_refund']) ) / 86400);
                // $r = floor($m / 30.4375);
                //JIKA TGL AKAD KE TGL REFUND > 30 HARI NILAI FULL
                $mets = datediff(date('Y-m-d'), $_REQUEST['tgl_refund']);
                $pecahtglrefund = explode(",", $mets);
                //echo 'tgl sistem ke tgl lunas : '.$mets.'<br />';
                //JIKA TGL SISTEM KE TGL REFUND <= 30 HARI GUNAKAN TANGGAL LUNAS
                if ($pecahtglrefund[0] <= 0 and $pecahtglrefund[1] <= 0 and $pecahtglrefund[2] <= 30) {
                    $metsTanggal = datediff($mamet['kredit_tgl'], $_REQUEST['tgl_refund']);
                    $pecahtglrefund_ = explode(",", $metsTanggal);
                } else {
                    //JIKA TGL SISTEM KE TGL REFUND > 30 HARI GUNAKAN TANGGAL SISTEM
                    $metsTanggal = datediff($mamet['kredit_tgl'], date('Y-m-d'));
                    $pecahtglrefund_ = explode(",", $metsTanggal);
                }
                //$movementrefund = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_rasio_claim WHERE id_cost="' . $mamet['id_cost'] . '" AND id_polis="' . $mamet['id_polis'] . '"'));
                $movementrefund = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_rasio_claim WHERE id_cost="' . $mamet['id_cost'] . '" AND id_polis="' . $mamet['id_polis'] . '" and "'.$mamet['kredit_tgl'].'" BETWEEN eff_from and eff_to'));
                // $biayapenutupan = $mamet['totalpremi'] *  $movementrefund['refund'];
                // $premirefund = ceil(($r / $mamet['kredit_tenor']) * ($mamet['totalpremi'] - $biayapenutupan));
                if ($mamet['type_data'] == "SPK") {
                    $metTenornya = $mamet['kredit_tenor'] * 12 ;
                } else {
                    $metTenornya = $mamet['kredit_tenor'];
                }
                $jumbulan = $metTenornya - ($pecahtglrefund_[0] * 12 + $pecahtglrefund_[1]);
                $biayapenutupan = $mamet['totalpremi'] * $movementrefund['topup'];
                $premirefund = $jumbulan / $metTenornya * ($mamet['totalpremi'] - $biayapenutupan);

                $mailadonai = 'Jum Bulan : '.$jumbulan.'Movement :'.$movementrefund['topup'].' biaya penutupan :'.$biayapenutupan.' premi refund : '.$premirefund.' hasilnya :'.$premirefund;

                $biayapenutupan_as = $mamet_as['nettpremi'] * $movementrefund['topup'];
                $premirefund_as = $jumbulan / $metTenornya * ($mamet_as['nettpremi'] - $biayapenutupan_as);
                // echo $premirefund;
            }
        } else {
            $kondisi = 'else';
            if ($pecahtglrefund[0] <= 0 and $pecahtglrefund[1] <= 0 and $pecahtglrefund[2] <= 30) {
                $metsTanggal = datediff($mamet['kredit_tgl'], $_REQUEST['tgl_refund']);
                $pecahtglrefund_ = explode(",", $metsTanggal);
            } else {
                //JIKA TGL SISTEM KE TGL REFUND > 30 HARI GUNAKAN TANGGAL SISTEM
                $metsTanggal = datediff($mamet['kredit_tgl'], date('Y-m-d'));
                $pecahtglrefund_ = explode(",", $metsTanggal);
            }
            $movementrefund = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_rasio_claim WHERE id_cost="' . $mamet['id_cost'] . '" AND id_polis="' . $mamet['id_polis'] . '" and "'.$mamet['kredit_tgl'].'" BETWEEN eff_from and eff_to'));
            // $biayapenutupan = $mamet['totalpremi'] *  $movementrefund['refund'];
            // $premirefund = ceil(($r / $mamet['kredit_tenor']) * ($mamet['totalpremi'] - $biayapenutupan));
            if ($mamet['type_data'] == "SPK") {
                $metTenornya = $mamet['kredit_tenor'] * 12 ;
            } else {
                $metTenornya = $mamet['kredit_tenor'];
            }
            $jumbulan = $metTenornya - ($pecahtglrefund_[0] * 12 + $pecahtglrefund_[1]);
            $biayapenutupan = $mamet['totalpremi'] * $movementrefund['topup'];
            $premirefund = $jumbulan / $metTenornya * ($mamet['totalpremi'] - $biayapenutupan);

            //$mailadonai = 'Jum Bulan : '.$metTenornya;

            $mailadonai = 'Jum Bulan : '.$jumbulan.'Movement :'.$movementrefund['topup'].' biaya penutupan :'.$biayapenutupan.' premi refund : '.$premirefund.' hasilnya :'.$premirefund;

            // $mailadonai = 'Jum Bulan '.$jumbulan.' dibagi tenor '.$metTenornya.' dikali ('.$mamet['totalpremi'].' - biaya penutupan '.$biayapenutupan.' ) hasilnya '.$premirefund;
            $biayapenutupan_as = $mamet_as['nettpremi'] * $movementrefund['topup'];
            $premirefund_as = $jumbulan / $metTenornya * ($mamet_as['nettpremi'] - $biayapenutupan_as);
        }
        // CEK PERHITUNGAN REFUND PREMI 24082016
        /*
           FILE REFUND SEBELUM DIRUBAH SESUAI PERMINTAAN BUKOPIN
           fname="REFUND_' . $_REQUEST['id_peserta'] . '_' . $_FILES["userfile"]["name"] . '",
        */
        $metrefundcn = $database->doQuery('INSERT INTO fu_ajk_cn_tempf SET id_cost="' . $mamet['id_cost'] . '",
																	id_dn="' . $mamet['id_dn'] . '",
												  					id_nopol="' . $mamet['id_polis'] . '",
												  					id_peserta="' . $mamet['id_peserta'] . '",
												  					id_regional="' . $mamet['regional'] . '",
												  					id_cabang="' . $mamet['cabang'] . '",
												  					premi="' . $mamet['totalpremi'] . '",
												  					total_claim="' . $premirefund . '",
												  					total_claim_as="' . $premirefund_as . '",
												  					tgl_claim="' . $_REQUEST['tgl_refund'] . '",
												  					type_claim="Refund",
												  					type_refund="'.$_typeRefund.'",
												  					tgl_createcn="' . $futoday . '",
												  					tgl_byr_claim="",
												  					confirm_claim="Pending",
												  					input_by="' . $_SESSION['nm_user'] . '",
												  					input_date="' . $futgl . '"');

        $metrefunddn = $database->doQuery('UPDATE fu_ajk_peserta SET status_peserta="Refund", status_aktif="Pending" WHERE id="' . $_REQUEST['id'] . '"');
        // SEND SMTPMAIL//
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

        $mail->SetFrom($q['email'], $q['nm_lengkap']); //From address of the mail
        $mail->Subject = "AJKOnline - UPLOAD DATA REFUND"; //Subject od your mail

                        //MAIL 2 CEK PERHITUNGAN REFUND
                           $mail2	= new PHPMailer; // call the class
                                $mail2->IsSMTP();
        $mail2->Host = SMTP_HOST; //Hostname of the mail server
                                $mail2->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
                                $mail2->Username = SMTP_UNAME; //Username for SMTP authentication any valid email created in your domain
                                $mail2->Password = SMTP_PWORD; //Password for SMTP authentication
                                $mail2->AddReplyTo = SMTP_REPLYMAIL; //reply-to address
                                $mail2->debug = 1;
        $mail2->SMTPSecure = "ssl";
        $mail2->IsHTML(true);
        $mail2->SetFrom($q['email'], $q['nm_lengkap']); //From address of the mail
                        $mail2->Subject = "AJKOnline - PERHITUNGAN REFUND SYSTEM - ".$mamet['id_peserta']; //Subject od your mail
                                $mail2->AddAddress("hansen@adonai.co.id");
        //$mail2->AddAddress("eka@adonai.co.id");
        $mail2->MsgHTML('<h1>message : kondisi '.$kondisi.' -  hasilnya '.$mailadonai.'</h1>');
        //$send = $mail2->Send(); //Send the mails

        //END MAIL 2

        $met_mail = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cn_tempf ORDER BY id DESC'));
        $met_mail_dn = mysql_fetch_array($database->doQuery('SELECT id, dn_kode FROM fu_ajk_dn WHERE id="' . $met_mail['id_dn'] . '"'));
        $met_mail_peserta = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_cost="' . $met_mail['id_cost'] . '" AND id_polis="' . $met_mail['id_nopol'] . '" AND id_peserta="' . $met_mail['id_peserta'] . '"'));
        // $nettrefund = $met_mail_peserta['totalpremi'] - $met_mail['total_claim'];
        $message .= '<table border="0" width="100%">
				<tr><td width="10%">Nomor DN</td><td>: ' . $met_mail_dn['dn_kode'] . '</td></tr>
					<td width="8%">ID Peserta</td><td>: ' . $met_mail_peserta['nama'] . '</td></tr>
					<td>Nama</td><td>: ' . $met_mail_peserta['id_peserta'] . '</td></tr>
					<td width="7%">Tanggal Lahir</td><td>: ' . _convertDate($met_mail_peserta['tgl_lahir']) . '</td></tr>
					<td width="1%">Usia</td><td>: ' . $met_mail_peserta['usia'] . ' tahun</td></tr>
					<td width="6%">Tgl Akad</td><td>: ' . _convertDate($met_mail_peserta['kredit_tgl']) . '</td></tr>
					<td width="1%">Tenor</td><td>: ' . $met_mail_peserta['kredit_tenor'] . ' bulan</td></tr>
					<td width="6%">Tgl Akhir</td><td>: ' . _convertDate($met_mail_peserta['kredit_akhir']) . '</td></tr>
					<td width="7%">Plafond</td><td>: ' . duit($met_mail_peserta['kredit_jumlah']) . '</td></tr>
					<td width="7%">Premi</td><td>: ' . duit($met_mail_peserta['totalpremi']) . '</td></tr>
					<td width="6%">Tgl Refund</td><td>: ' . _convertDate($met_mail['tgl_claim']) . '</td></tr>
					<td width="6%">Nilai Refund</td><td>: ' . duit($met_mail['total_claim']) . '</td></tr>
					<td width="5%">Status</td><td>: ' . $met_mail['type_claim'] . '</td></tr>
					<td width="5%">Cabang</td><td>: ' . $met_mail['id_cabang'] . '</td></tr>
			</table>';
        // EMAIL PENERIMA  SPV CLIENT
        $mailsupervisorajk = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="' . $q['id_cost'] . '" AND id_polis="" AND status="SUPERVISOR-ADMIN" AND del IS NULL');
        while ($_mailsupervisorajk = mysql_fetch_array($mailsupervisorajk)) {
            $mail->AddAddress($_mailsupervisorajk['email'], $_mailsupervisorajk['nm_lengkap']); //To address who will receive this email
        }
        // EMAIL PENERIMA  SPV CLIENT

        $mailclient = $database->doQuery('SELECT * FROM fu_ajk_mail WHERE type="Refund" AND status="Aktif"');
        while ($_mailclient = mysql_fetch_array($mailclient)) {
            $mail->AddAddress($_mailclient['emailto'], $_mailclient['emailnama']); //To address who will receive this email
        }

        $mail->AddBCC("adn.info.notif@gmail.com");

        $mail->MsgHTML('<table width="100%"><tr><th>To ' . $_mailsupervisorajk['nm_lengkap'] . ' <br /> Data Refund Peserta telah diinput oleh <b>' . $_SESSION['nm_user'] . ' pada tanggal ' . $futgl . '</tr></table>' . $message); //Put your body of the message you can place html code here
                $send = $mail->Send(); //Send the mails
                // SEND SMTPMAIL//
                //echo $message.'<br />';
                echo '<center><b>Data Refund telah diinput oleh ' . $q['nm_lengkap'] . '.<br /><meta http-equiv="refresh" content="3;URL=ajk_klaim.php?er=valRefund"></b></center>';
    }
}
        $met_refund = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id="' . $_REQUEST['id'] . '"'));
        $met_dn = mysql_fetch_array($database->doQuery('SELECT id, dn_kode FROM fu_ajk_dn WHERE id="' . $met_refund['id_dn'] . '"'));
echo '<form method="post" action="" enctype="multipart/form-data">
	<table border="1" width="100%" cellpadding="3" cellspacing="1">
	<tr bgcolor="#bde0e6"><th width="10%">Nomor DN</th>
		<th width="10%">ID Peserta</th>
		<th>Nama Tertanggung</th>
		<th width="8%">Tanggal Lahir</th>
		<th width="1%">Usia</th>
		<th width="10%">Uang Asuransi</th>
		<th width="8%">Mulai Asuransi</th>
		<th width="1%">Tenor</th>
		<th width="8%">Akhir Asuransi</th>
		<th width="1%">Premi</th>
		<th width="1%">EM</th>
		<th width="5%">T.Premi</th>
		<!--<th width="5%">Status</th>-->
		<th width="5%">Cabang</th>
		</tr>
	<tr>
    	<td align="center">' . $met_dn['dn_kode'] . '</td>
    	<td align="center">' . $met_refund['id_peserta'] . '</td>
    	<td>' . $met_refund['nama'] . '</td>
    	<td align="center">' . _convertDate($met_refund['tgl_lahir']) . '</td>
    	<td align="center">' . $met_refund['usia'] . '</td>
    	<td align="right">' . duit($met_refund['kredit_jumlah']) . '</td>
    	<td align="center">' . _convertDate($met_refund['kredit_tgl']) . '</td>
    	<td align="center">' . $met_refund['kredit_tenor'] . '</td>
    	<td align="center">' . _convertDate($met_refund['kredit_akhir']) . '</td>
    	<td align="right">' . duit($met_refund['premi']) . '</td>
    	<td align="center">' . $met_refund['ext_premi'] . '</td>
    	<td align="right">' . duit($met_refund['totalpremi']) . '</td>
    	<!--<td align="center">' . $metstatusrefund . '</td>-->
    	<td align="center">' . $met_refund['cabang'] . '</td>
   	</tr>
	</table>
   	<table border="0" cellpadding="3" cellspacing="1" width="100%">
   	<tr><td colspan="2"><a href="aajk_report.php?er=formBtlRef&r='.$_REQUEST['r'].'&deb='.$_REQUEST['id'].'" target="blank">Download File Refund '.$tiperefund.'</a></td></tr>
	<tr><td width="10%">Tanggal Pelunasan</td><td>: ';	print initCalendar(); print calendarBox('tgl_refund', 'triger', $datelog);	echo '<br />' . $error1 . '</td></tr>
   	<tr><td><input type="hidden" name="el" value="upload_refund"><input type="submit" name="upload" value="Proses"></td></tr>

	  <!--  <tr><td width="45%" align="right">ID Peserta</td><td><input type="hidden" name="id_peserta" value=' . $met_refund['id_peserta'] . '>: ' . $met_refund['id_peserta'] . '</td></tr>
	  <tr><td align="right">Nomor DN</td><td>: ' . $met_dn['dn_kode'] . '</td></tr>
	  <tr><td align="right">Nama Peserta</td><td>: ' . $met_refund['nama'] . '</td></tr>
	  <tr><td align="right" valign="top">Tanggal Pengajuan Refund <font color="red">*</font></td><td>: -->';
//    	print initCalendar(); print calendarBox('tgl_refund', 'triger', $_REQUEST['tgl_refund']);
//echo '<br />' . $error1 . '</td></tr>
/*
echo '<tr><td align="right" valign="top">Tanggal Pelunasan <font color="red">*</font></td><td>: ';
        print initCalendar(); print calendarBox('tgl_refund', 'triger', $datelog);
echo '<br />' . $error1 . '</td></tr>
      <!-- <tr><td align="right" valign="top">File Refund <font color="red">*</font><br /><font size="2">Ext. : PDF atau JPG (2Mb)</font></td><td valign="top">:<input name="userfile" type="file" size="50" onchange="checkfile(this);" ><br />' . $error2 . ' ' . $error3 . ' ' . $error4 . '</td></tr> DIREVISI SESUAI PERMINTAAN BUKOPIN TGL 14032016-->
      <tr><td align="right" valign="top">File Refund '.$tiperefund.'</td><td valign="top">download</td></tr>
   <tr><td align="right" valign="top">Tanggal Pengajuan Refund <font color="red">*</font></td><td>: -->';
*/

      echo '</table></form>';
    ;
    break;

    case "EdDataRefund":
echo '<table border="0" cellpadding="5" cellspacing="1" width="100%">
	  <tr><th width="100%" align="left">Modul Refund</font></th>
	  	  <th width="5%"><a href="ajk_klaim.php?er=valRefund"><img src="image/Backward-64.png" width="20"></a></th>
	  </tr>
	  </table>';
$met_refund = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cn_tempf WHERE id="' . $_REQUEST['idref'] . '"'));
$met_refundDeb = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_cost="'.$met_refund['id_cost'].'" AND id_polis="'.$met_refund['id_nopol'].'" AND id_peserta="'.$met_refund['id_peserta'].'"'));
$met_dn = mysql_fetch_array($database->doQuery('SELECT id, dn_kode FROM fu_ajk_dn WHERE id="' . $met_refund['id_dn'] . '"'));

if ($_REQUEST['el'] == "upload_refund") {
    if (!$_REQUEST['tgl_refund']) {
        $error1 .= '<font color="red">Silahkan input tanggal Refund !.</font>';
    }
    if ($error1) {
    } else {
        $mamet_as = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta_as WHERE id_bank="'.$met_refundDeb['id_cost'].'" AND id_polis="'.$met_refundDeb['id_polis'].'" AND id_dn="'.$met_refundDeb['id_dn'].'" AND id_peserta="'.$met_refundDeb['id_peserta'].'"'));
        // CEK PERHITUNGAN REFUND PREMI
        //$metbulanplus = date('Y-m-d', strtotime('+0 month', strtotime($mamet['kredit_tgl'])));
        //if ($_REQUEST['tgl_refund'] <= $metbulanplus) {
        //echo $mamet['kredit_tgl'] .' - '. $_REQUEST['tgl_refund'].' - '.date('Y-m-d').'<br />';
        $metsRefund = datediff($met_refundDeb['kredit_tgl'], $_REQUEST['tgl_refund']);
        $metsRefund_ = explode(",", $metsRefund);
        //echo $metsRefund_[0].'-'.$metsRefund_[1].'-'.$metsRefund_[2];
        //JIKA TGL AKAD KE TGL REFUND <= 30 HARI NILAI FULL
        if ($metsRefund_[0] <= 0 and $metsRefund_[1] <= 0 and $metsRefund_[2] <= 30) {
            //echo $metsRefund.'<br />';
            //echo 'tgl akad ke tgl lunas : '.$metsRefund.'<br />';
            $premirefund = $met_refundDeb['totalpremi'];
            $premirefund_as = $mamet_as['nettpremi'];
        } else {
            // $m = ceil(abs( strtotime($mamet['kredit_akhir']) - strtotime($_REQUEST['tgl_refund']) ) / 86400);
            // $r = floor($m / 30.4375);
            //JIKA TGL AKAD KE TGL REFUND > 30 HARI NILAI FULL
            $mets = datediff(date('Y-m-d'), $_REQUEST['tgl_refund']);
            $pecahtglrefund = explode(",", $mets);
            //echo 'tgl sistem ke tgl lunas : '.$mets.'<br />';
            //JIKA TGL SISTEM KE TGL REFUND <= 30 HARI GUNAKAN TANGGAL LUNAS
            if ($pecahtglrefund[0] <= 0 and $pecahtglrefund[1] <= 0 and $pecahtglrefund[2] <= 30) {
                $metsTanggal = datediff($met_refundDeb['kredit_tgl'], $_REQUEST['tgl_refund']);
                $pecahtglrefund_ = explode(",", $metsTanggal);
            } else {
                //JIKA TGL SISTEM KE TGL REFUND > 30 HARI GUNAKAN TANGGAL SISTEM
                $metsTanggal = datediff($met_refundDeb['kredit_tgl'], date('Y-m-d'));
                $pecahtglrefund_ = explode(",", $metsTanggal);
            }
            $movementrefund = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_rasio_claim WHERE id_cost="' . $met_refundDeb['id_cost'] . '" AND id_polis="' . $met_refundDeb['id_polis'] . '"'));
            // $biayapenutupan = $mamet['totalpremi'] *  $movementrefund['refund'];
            // $premirefund = ceil(($r / $mamet['kredit_tenor']) * ($mamet['totalpremi'] - $biayapenutupan));
            if ($met_refundDeb['type_data'] == "SPK") {
                $metTenornya = $met_refundDeb['kredit_tenor'] * 12 ;
            } else {
                $metTenornya = $met_refundDeb['kredit_tenor'];
            }
            $jumbulan = $metTenornya - ($pecahtglrefund_[0] * 12 + $pecahtglrefund_[1]);
            $biayapenutupan = $met_refundDeb['totalpremi'] * $movementrefund['refund'];
            $premirefund = $jumbulan / $metTenornya * ($met_refundDeb['totalpremi'] - $biayapenutupan);

            $biayapenutupan_as = $mamet_as['nettpremi'] * $movementrefund['refund'];
            $premirefund_as = $jumbulan / $metTenornya * ($mamet_as['nettpremi'] - $biayapenutupan_as);
            // echo $premirefund;
        }
        // CEK PERHITUNGAN REFUND PREMI
        $metrefundcn = $database->doQuery('UPDATE fu_ajk_cn_tempf SET total_claim="' . $premirefund . '", total_claim_as="' . $premirefund_as . '", tgl_claim="' . $_REQUEST['tgl_refund'] . '" WHERE id="'.$_REQUEST['idref'].'"');

        echo '<center><b>Tanggal Refund telah diedit oleh ' . $q['nm_lengkap'] . '.<br /><meta http-equiv="refresh" content="3;URL=ajk_klaim.php?er=valRefund"></b></center>';
    }
}
echo '<form method="post" action="" enctype="multipart/form-data">
<input type="hidden" name="id_peserta" value=' . $met_refund['id_peserta'] . '>
<input type="hidden" name="id_cost" value=' . $met_refund['id_cost'] . '>
<input type="hidden" name="id_polis" value=' . $met_refund['id_nopol'] . '>
	  <table border="0" cellpadding="5" cellspacing="1" width="100%">
	  <tr><td width="45%" align="right">ID Peserta</td><td>: ' . $met_refund['id_peserta'] . '</td></tr>
	  <tr><td align="right">Nomor DN</td><td>: ' . $met_dn['dn_kode'] . '</td></tr>
	  <tr><td align="right">Nama Peserta</td><td>: ' . $met_refundDeb['nama'] . '</td></tr>
	  <tr><td align="right">Nilai Refund</td><td>: ' . duit($met_refund['total_claim']) . '</td></tr>
	  <tr><td align="right" valign="top">Edit Tanggal Refund <font color="red">*</font></td><td>: ';
        print initCalendar();
        print calendarBox('tgl_refund', 'triger', $met_refund['tgl_claim']);
        echo '<br />' . $error1 . '</td></tr>
	  <tr><td align="center"colspan="2"><input type="hidden" name="el" value="upload_refund"><input type="submit" name="upload" value="Proses"></td></tr>
	  </table></form>'; ;
        break;

    case "reqrefund":

echo '<table border="0" cellpadding="5" cellspacing="1" width="100%"><tr><th width="100%" align="left">Modul Refund '.$tiperefund.'</font></th></tr></table>';
echo '<fieldset style="padding: 2">
		<legend align="center">S e a r c h</legend>
		<table border="0" width="100%" cellpadding="1" cellspacing="1">
		<form method="post" action="">
		<tr><td width="30%" align="right">Nama Perusahaan :</td>
			  <td width="30%">';
        $quer2 = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="' . $q['id_cost'] . '"'));
        echo $quer2['name'];
//        echo '</td></tr><tr><td align="right">Nama Produk :</td><td> ';
//        $quer1 = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="' . $q['id_polis'] . '"'));
 //       echo $quer1['nmproduk'] . ' (' . $quer1['nopol'] . ')';
//        echo '</td></tr>';
        echo '<tr><td align="right">Nomor Peserta :</td><td><input type="text" name="id_er" value="' . $_REQUEST['id_er'] . '"></td></tr>
			  <tr><td align="right">Nama Peserta :</td><td><input type="text" name="nama_er" value="' . $_REQUEST['nama_er'] . '"></td></tr>
			  <tr><td align="center" colspan="2"><input type="hidden" name="re" value="datapeserta"><input type="submit" name="button" value="Cari" class="button"></td></tr>
			  </form></table></fieldset>';
if ($_REQUEST['re'] == "datapeserta") {
    echo '<table border="0" width="100%" cellpadding="3" cellspacing="1" bgcolor="#bde0e6">
		<tr><th width="1%"></th>
			<th width="1%">No</th>
			<th width="10%">Nomor DN</th>
			<th width="10%">ID Peserta</th>
			<th>Nama Tertanggung</th>
			<th width="8%">Tanggal Lahir</th>
			<th width="1%">Usia</th>
			<th width="10%">Uang Asuransi</th>
			<th width="8%">Mulai Asuransi</th>
			<th width="1%">Tenor</th>
			<th width="8%">Akhir Asuransi</th>
			<th width="1%">Premi</th>
			<th width="1%">EM</th>
			<th width="5%">T.Premi</th>
			<th width="5%">Status</th>
			<th width="5%">Cabang</th>
			</tr>';
    if ($_REQUEST['nama_er']) {
        $satu = 'AND nama LIKE "%' . $_REQUEST['nama_er'] . '%"';
    }
    if ($_REQUEST['id_er']) {
        $dua = 'AND id_peserta LIKE "%' . $_REQUEST['id_er'] . '%"';
    }
    if ($_REQUEST['x']) {
        $m = ($_REQUEST['x'] - 1) * 25;
    } else {
        $m = 0;
    }
    // $data = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE nama !="" AND id_cost="'.$q['id_cost'].'" AND id_polis="'.$q['id_polis'].'" ' . $satu . ' '.$dua.' AND status_aktif="Inforce" AND del IS NULL ORDER BY status_bayar ASC, id_dn DESC LIMIT '.$m.', 25');
    //$data = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_polis not in (11,12) and nama !="" AND id_cost="' . $q['id_cost'] . '" AND input_by="' . $q['nm_user'] . '" ' . $satu . ' ' . $dua . ' AND status_aktif="Inforce" AND status_peserta IS NULL AND del IS NULL ORDER BY status_bayar ASC, id_dn DESC LIMIT ' . $m . ', 25');
    $data = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE nama !="" AND id_cost="' . $q['id_cost'] . '" AND input_by="' . $q['nm_user'] . '" ' . $satu . ' ' . $dua . ' AND status_aktif="Inforce" AND status_peserta IS NULL AND del IS NULL ORDER BY status_bayar ASC, id_dn DESC LIMIT ' . $m . ', 25');
    $totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_peserta WHERE nama !="" AND id_cost="' . $q['id_cost'] . '" AND input_by="' . $q['nm_user'] . '" ' . $satu . ' ' . $dua . ' AND status_aktif="Inforce" AND status_peserta IS NULL AND del IS NULL'));
    $totalRows = $totalRows[0];
    $_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
    // echo 'SELECT * FROM fu_ajk_peserta WHERE nama !="" AND id_cost="'.$q['id_cost'].'" AND id_polis="'.$q['id_polis'].'" ' . $satu . ' '.$dua.' '.$tiga.' '.$empat.'  AND del IS NULL ORDER BY input_by ASC LIMIT '.$m.', 25';exit;
    while ($fudata = mysql_fetch_array($data)) {
        $met_dn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn WHERE id="' . $fudata['id_dn'] . '"'));
        $met_dnas = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_asuransi WHERE id="' . $met_dn['id_as'] . '"'));
        $met_polis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="' . $fudata['id_polis'] . '"'));
        if ($met_dnas['id']==5 and $_REQUEST['r']=="lp") {
            $Rrefund = '';
        } elseif ($met_polis['refundable']=="N") {
            $Rrefund = '';
            if ($fudata['status_bayar'] == 0) {
                $metstatusrefund = "<font color=red>UNPAID</font>";
            } else {
                $metstatusrefund = "<font color=blue>PAID</font>";
            }
        } else {
            if ($fudata['status_bayar'] == 0) {
                $metstatusrefund = "<font color=red>UNPAID</font>";
                $Rrefund = '';
            } else {
                $metstatusrefund = "<font color=blue>PAID</font>";
                $Rrefund = '<a href="ajk_klaim.php?er=settRefund&r='.$_REQUEST['r'].'&id=' . $fudata['id'] . '" onClick="if(confirm(\'Apakah anda yakin untuk melakukan Refund peserta ini ?\')){return true;}{return false;}"><img src="image/save.png" width="20"></a>';
                //$Rrefund= '<a href="#">test</a>';
            }
        }
        if (($no % 2) == 1) {
            $objlass = 'tbl-odd';
        } else {
            $objlass = 'tbl-even';
        }
        echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		<td align="center">' . $Rrefund . '</td>
		<td align="center">' . (++$no + ($pageNow - 1) * 25) . '</td>
		<td align="center">' . $met_dn['dn_kode'] . '</td>
		<td align="center">' . $fudata['id_peserta'] . '</td>
		<td>' . $fudata['nama'] . '</td>
		<td align="center">' . _convertDate($fudata['tgl_lahir']) . '</td>
		<td align="center">' . $fudata['usia'] . '</td>
		<td align="right">' . duit($fudata['kredit_jumlah']) . '</td>
		<td align="center">' . _convertDate($fudata['kredit_tgl']) . '</td>
		<td align="center">' . $fudata['kredit_tenor'] . '</td>
		<td align="center">' . _convertDate($fudata['kredit_akhir']) . '</td>
		<td align="right">' . duit($fudata['premi']) . '</td>
		<td align="center">' . $fudata['ext_premi'] . '</td>
		<td align="right">' . duit($fudata['totalpremi']) . '</td>
		<td align="center">' . $metstatusrefund . '</td>
		<td align="center">' . $fudata['cabang'] . '</td>
	</tr>';
    }
    echo '<tr><td colspan="23">';
    echo createPageNavigations($file = 'ajk_klaim.php?er=reqrefund&re=' . $_REQUEST['re'] . '&nama_er=' . $_REQUEST['nama_er'] . '&id_er=' . $_REQUEST['id_er'] . '', $total = $totalRows, $psDeh = 10, $anchor = '', $perPage = 25);
    echo '<b>Total Data Peserta: <u>' . $totalRows . '</u></b></td></tr>';
    echo '</table>';
} ;
        break;

    case "settKlaim":

            function _datediff($date1, $date2)
            {
                $query="SELECT DATEDIFF('".$date1."','".$date2."') as date_res";
                $result=mysql_query($query);
                $data_=mysql_fetch_array($result);
                return $data_['date_res'];
            }

            echo '
			<table border="0" cellpadding="5" cellspacing="1" width="100%">
				<tr><th width="100%" align="left">Modul Klaim</font></th>
				  <th width="5%"><a href="ajk_klaim.php?er=reqklaim"><img src="image/Backward-64.png" width="20"></a></th>
				</tr>
			</table>';

          $peserta = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id="' . $_REQUEST['id'] . '"'));
          $datadn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn WHERE dn_kode="' . $peserta['id_dn'] . '" AND id_cost="' . $peserta['id_cost'] . '"'));
          $ppolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="' . $peserta['id_polis'] . '"'));
          $pcostumer = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="' . $peserta['id_cost'] . '"'));

        if ($_REQUEST['oop'] == "Simpan") {
            $tuntutanklaim = $_REQUEST['tuntutan_klaim'];
            $tuntutanklaim = str_replace(",", "", $tuntutanklaim);
            $tuntutanklaim = str_replace(".", "", $tuntutanklaim);
            if (!$_REQUEST['tglklaim']) {
                $error1 .= '<font color="red" size="2">Tanggal meninggal tidak boleh kosong</font>.';
            }
            if (!$_REQUEST['noperdit']) {
                $error2 .= '<font color="red" size="2">Silahkan isi nomor perjanjian kredit</font>.';
            }
            if (!$_REQUEST['lokasinya']) {
                $error3 .='<font color="red">Silahkan pilih lokasi meninggal</font>.';
            }
            if (!$_REQUEST['tuntutan_klaim']) {
                $error4 .= '<font color="red" size="2">Isi Tuntutan Klaim</font>.';
            }
            if (!$_REQUEST['nmpenyakit']) {
                $error5 .= '<font color="red" size="2">Pilih Penyebab Meninggal</font>.';
            }
            if ($_REQUEST['tglklaim'] >= $futoday) {
                $error1 .= '<font color="red" size="2">Tanggal meninggal tidak boleh lebih besar dari hari ini</font>.';
            }
            if ($tuntutanklaim > $peserta['kredit_jumlah']) {
                $error4 .= '<font color="red" size="2">Tuntutan Klaim Tidak Boleh lebih besar dari Plafond</font>.';
            }

            if ($error1 or $error2 or $error3 or $error4 or $error5) {
            } else {
                $kadaluarsa=_datediff(date("Y-m-d"), $_REQUEST['tglklaim']);

                $cn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cn ORDER BY id DESC'));
                $metidcn = explode(" ", $cn['input_date']);
                $metidthncn = explode("-", $metidcn[0]);
                if ($metidthncn[0] < $dateY) {
                    $metautocn = 1;
                } else {
                    /*
                               $cntglpecah = explode("-", $cn['id_cn']);
                                     $metautocn = substr($cn['id_cn'], 7) + 1;
                               */
                    $metautocn = $cn['idC'] + 1;
                }
                $idcnnya = 10000000000 + $metautocn;
                $idcn = substr($idcnnya, 1);
                $cntgl = explode("/", $futgldn);
                $cnthn = substr($cntgl[2], 2);
                $cn_kode = '';//ACN' . $cnthn . '' . $cntgl[1] . '' . $idcn;
                // JUMLAH KLAIM BILA TIDAK DI INPUT MANUAL
                if ($_REQUEST['jklaim'] == "") {
                    $nilaiklaimnya = $peserta['kredit_jumlah'];
                } else {
                    $nilaiklaimnya = $_REQUEST['jklaim'];
                }

                //$tuntutan_klaim = $_REQUEST['tuntutan_klaim'];

                // RUMUSAN PERHITUNGAN RATE MENINGGAL
                // INSERT DATA CN//
                $confirmnya='';
                if ($kadaluarsa>90) {
                    $confirmnya='Pending';
                }

                $rklaim = $database->doQuery('INSERT INTO fu_ajk_cn SET id_dn="' . $peserta['id_dn'] . '",
													 idC="' . $metautocn . '",
													 id_cn="' . $cn_kode . '",
													 id_cost="' . $peserta['id_cost'] . '",
													 id_nopol="' . $peserta['id_polis'] . '",
													 id_peserta="' . $peserta['id_peserta'] . '",
													 premi="' . $peserta['totalpremi'] . '",
													 id_regional="' . $peserta['regional'] . '",
													 id_cabang="' . $peserta['cabang'] . '",
													 tgl_claim="' . $_REQUEST['tglklaim'] . '",
													 /*tgl_createcn="' . $futoday . '",*/
													 tgl_byr_claim="' . $_REQUEST['tglbyrklaim'] . '",
													 type_claim="Death",
													 nmpenyakit="' . $_REQUEST['nmpenyakit'] . '",
													 noperkredit="' . $_REQUEST['noperdit'] . '",
													 validasi_cn_uw="ya",
													 validasi_cn_arm="ya",
													 confirm_claim="Pending",
	          											 tuntutan_klaim="' . $tuntutanklaim . '",
													 /*total_claim="' . $nilaiklaimnya . '",*/
													 keterangan="' . $_REQUEST['ket'] . '",
													 input_by="' . $q['nm_user'] . '",
													 input_date="' . $futgl . '" ');

                $met_data_cn = mysql_fetch_array($database->doQuery('SELECT id FROM fu_ajk_cn ORDER BY ID DESC'));
                // DOKUMEN MENINGGAL
                if (!$_REQUEST['dokklaim']) {
                    echo '<center>Tidak ada dokumen yang dipilih.</center>';
                } else {
                    foreach ($_REQUEST['dokklaim'] as $doc => $docya) {
                        $metdoknya = $database->doQuery('INSERT INTO fu_ajk_klaim_doc SET id_pes="' . $peserta['id_peserta'] . '",
														  id_cost="' . $peserta['id_cost'] . '",
														  id_dn="' . $peserta['id_dn'] . '",
														  id_klaim="' . $met_data_cn['id'] . '",
														  dokumen="' . $docya . '",
														  input_by="' . $q['nm_user'] . '",
														  input_date="' . $futgl . '"');
                    }
                }



                //DATA INSERT//
                $metklaim = $database->doQuery('INSERT INTO fu_ajk_klaim SET id_cost="'.$peserta['id_cost'].'",
															 id_dn="'.$peserta['id_dn'].'",
															 id_cn="'.$met_data_cn['id'].'",
															 id_peserta="'.$peserta['id_peserta'].'",
															 id_klaim_status=2,
															 tgl_klaim="'.$_REQUEST['tglklaim'].'",
															 type_klaim="Death",
															 tgl_document="' . $futgl . '",
															 confirm_klaim="'.$confirmnya.'",
			          											 tuntutan_klaim="' . $tuntutan_klaim . '",
															 tempat_meninggal="'.$_REQUEST['lokasinya'].'",
															 sebab_meninggal="'.$_REQUEST['nmpenyakit'].'",
															 input_by="'.$q['nm_lengkap'].'",
															 input_date="'.$futgl.'"');
                $updatepeserta = $database->doQuery('UPDATE fu_ajk_peserta SET id_klaim="' . $met_data_cn['id'] . '", status_aktif="Lapse", status_peserta="Death" WHERE id="' . $_REQUEST['id'] . '"');


                if ($kadaluarsa<=90) {
                    echo '<center><b>Pengajuan laporan klaim telah dibuat oleh ' . $q['nm_lengkap'] . ' pada tanggal ' . $futgl . ' Silahkan upload dokumen yang dipilih pada table klaim.</b></center>
						  <meta http-equiv="refresh" content="3;URL=ajk_nota.php?er=vKlaim&idk=' . $met_data_cn['id'] . '">';
                } else {
                    echo '<h4 style="text-align: center;">KLAIM TELAH KADALUARSA</h4>
	                <p style="text-align: center; ">Klaim ini telah melebihi 90 hari dari tanggal meninggal sampai dengan tanggal lapor klaim. Silahkan melampirkan surat keterangan dari supervisor untuk proses klaim selanjutnya</p>';

                    echo '<center><b>Pengajuan laporan klaim telah dibuat oleh ' . $q['nm_lengkap'] . ' pada tanggal ' . $futgl . ' Silahkan upload dokumen yang dipilih pada table klaim.</b></center>
			 				 <meta http-equiv="refresh" content="5;URL=ajk_nota.php?er=vKlaim&idk=' . $met_data_cn['id'] . '">';
                }
            }
        }


            echo '<form method="post" action="">
			<table border="0" cellpadding="3" cellspacing="0" width="80%" align="center" style="border: solid 1px #DEDEDE" align="center">
			<input type="hidden" name="id" value="' . $_REQUEST['id'] . '">
			<tr><th colspan="4">Form Pengisian Data Klaim Meninggal</th></tr>
			<tr><td width="20%">Nama Perusahaan</td><td>: <b>' . $pcostumer['name'] . '</b></td>
			<td width="25%" align="right">ID Peserta</td><td>: ' . $peserta['id_peserta'] . '</td>
			</tr>
			<tr><td width="20%" coslpan="3">Nama Produk</td><td>: <b>' . $ppolis['nmproduk'] . '</b></td></tr>
			<tr><td>Nama</td><td colspan="3">: <b>' . $peserta['nama'] . '</b></td></tr>
			<tr><td>Plafond</td><td colspan="3">: ' . duit($peserta['kredit_jumlah']) . '</td></tr>
			<tr><td>Nilai Tuntutan Klaim <font color="red"><b>*</b></font><br /> ' . $error4 . '</td><td colspan="3">: <input type="text" name="tuntutan_klaim"></td></tr>
			<tr><td>Tanggal Asuransi</td><td>: ' . _convertDate($peserta['kredit_tgl']) . ' s/d ' . _convertDate($peserta['kredit_akhir']) . '</td>
			<td align="right">Jangka Waktu</td><td>: ' . $peserta['kredit_tenor'] . ' Bulan</td>
			</tr>
			<tr><td>Tanggal Meninggal <font color="red"><b>*</b></font><br />' . $error1 . '</td><td valign="top">:
			<input type="text" name="tglklaim" id="tglklaim" class="tanggal" value="'.$_REQUEST['tglklaim'].'" size="10"/>';
            //print initCalendar();	print calendarBox('tglklaim', 'triger', $_REQUEST['tglklaim']);
            echo '</td>
			<td align="right">No. Perjanjian Kredit <font color="red"><b>*</b></font><br /> ' . $error2 . '</td><td valign="top">: <input type="text" name="noperdit" value="' . $_REQUEST['noperdit'] . '"></td>
			</tr>
			<tr><td>Lokasi Meninggal <font color="red"><b>*</b></font></td><td>: <select size="1" name="lokasinya">
			<option value="">--Pilih Lokasi--</option>
			<option value="Rumah"'._selected($_REQUEST["lokasinya"], "Rumah").'>Rumah</option>
			<option value="Rumah Sakit"'._selected($_REQUEST["lokasinya"], "Rumah Sakit").'>Rumah Sakit</option>
			<option value="Lain-Lain"'._selected($_REQUEST["lokasinya"], "Lain-Lain").'>Lain-Lain</option>
			</select></td></tr>
			<tr><td>Penyebab Meninggal <font color="red"><b>*</b></font><br />' . $error5 . '</td><td valign="top">:
			<select size="1" name="nmpenyakit">
				<option value="">---Penyebab Meninggal---</option>';
            $nmPenyakit = $database->doQuery('SELECT * FROM fu_ajk_namapenyakit ORDER BY id ASC');
            while ($nmPenyakit_ = mysql_fetch_array($nmPenyakit)) {
                echo '<option value="'.$nmPenyakit_['id'].'"'._selected($_REQUEST['nmpenyakit'], $nmPenyakit_['id']).'>'.$nmPenyakit_['namapenyakit'].'</option>';
            }

            echo '</select>
			</td></tr>';

          if ($datadn['ket'] != "") {
              echo' <tr><td valign="top"><b>Keterangan Underwriting</b></td><td colspan="3">: ' . $datadn['ket'] . '</td></tr>';
          }
            echo '<tr><th colspan="5">Kelengkapan Dokumen</th></tr>
			<tr><td colspan="5">';

            echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
			<th width="1%">No</th><th>Form Klaim</th><th width="5%">Option</th></tr>';
            $met_dok = $database->doQuery('SELECT fu_ajk_dokumenklaim_bank.id,
									  fu_ajk_dokumenklaim_bank.id_bank,
									  fu_ajk_dokumenklaim_bank.id_dok,
									  fu_ajk_dokumenklaim.nama_dok,
									  fu_ajk_dokumenklaim.view
								FROM fu_ajk_dokumenklaim_bank
								INNER JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim_bank.id_dok = fu_ajk_dokumenklaim.id
								WHERE fu_ajk_dokumenklaim_bank.id_bank="' . $peserta['id_cost'] . '" AND
									  fu_ajk_dokumenklaim_bank.id_produk="' . $peserta['id_polis'] . '" AND
									  fu_ajk_dokumenklaim.view IS NULL
								ORDER BY nama_dok ASC');
            while ($met_dok_ = mysql_fetch_array($met_dok)) {
                if (($no % 2) == 1) {
                    $objlass = 'tbl-odd';
                } else {
                    $objlass = 'tbl-even';
                }
                echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
				<td align="center">' . ++$no . '</td>
				<td>' . $met_dok_['nama_dok'] . '</td>
				<td align="center"><input type="checkbox" name="dokklaim[]" value="' . $met_dok_['id'] . '"></td>
				</tr>';
            }
            echo '</table>
			</td></tr>
			<tr><td colspan="4" align="center"><input type="submit" name="oop" value="Simpan"></td></tr>
			</table></form>';

            ;
    break;

    case "eKlaim":
if ($_REQUEST['sett']=="fixdoc") {
    $backFixdoc='<a href="ajk_nota.php?er=vKlaim&sett='.$_REQUEST['sett'].'&idk=' . $_REQUEST['idc'] . '"><img src="image/Backward-64.png" width="20"></a>';
    $_backFixDoc='<meta http-equiv="refresh" content="1;URL=ajk_nota.php?er=vKlaim&sett='.$_REQUEST['sett'].'&idk=' . $_REQUEST['idc'] . '">';
} else {
    $backFixdoc='<a href="ajk_nota.php?er=vKlaim&idk=' . $_REQUEST['idc'] . '"><img src="image/Backward-64.png" width="20"></a>';
    $_backFixDoc='<meta http-equiv="refresh" content="1;URL=ajk_nota.php?er=vKlaim&idk=' . $_REQUEST['idc'] . '">';
}
echo '<table border="0" cellpadding="5" cellspacing="1" width="100%">
	  <tr><th width="100%" align="left">Modul Klaim</font></th>
	  	  <th width="5%">'.$backFixdoc.'</th>
	  </tr>
	  </table>';
$metEcn = mysql_fetch_array($database->doQuery('SELECT
fu_ajk_costumer.name,
fu_ajk_polis.nmproduk,
fu_ajk_peserta.nama,
fu_ajk_peserta.id_peserta,
fu_ajk_peserta.id_dn,
fu_ajk_peserta.id_klaim,
fu_ajk_peserta.kredit_jumlah,
IF(fu_ajk_peserta.type_data = "SPK", fu_ajk_peserta.kredit_tenor * 12, fu_ajk_peserta.kredit_tenor) AS tenor,
fu_ajk_peserta.kredit_tgl,
fu_ajk_peserta.kredit_akhir,
fu_ajk_cn.id_cost,
fu_ajk_cn.id_nopol,
fu_ajk_cn.tgl_claim,
fu_ajk_cn.nmpenyakit,
fu_ajk_cn.noperkredit,
fu_ajk_cn.tuntutan_klaim
FROM
fu_ajk_cn
Inner Join fu_ajk_peserta ON fu_ajk_cn.id = fu_ajk_peserta.id_klaim
Inner Join fu_ajk_costumer ON fu_ajk_cn.id_cost = fu_ajk_costumer.id
Inner Join fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
WHERE  fu_ajk_cn.id ="' . $_REQUEST['idc'] . '"'));
$tuntutan_klaim = $metEcn['tuntutan_klaim'];
$metKlaimEdit = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_klaim WHERE id_peserta="'.$metEcn['id_peserta'].'"'));
if ($_REQUEST['oop'] == "Simpan") {
    if (!$_REQUEST['tglklaim']) {
        $error1 .= '<font color="red" size="2">Tanggal meninggal tidak boleh kosong</font>.';
    }
    if (!$_REQUEST['noperdit']) {
        $error2 .= '<font color="red" size="2">Silahkan isi nomor perjanjian kredit</font>.';
    }
    if (!$_REQUEST['tuntutan_klaim']) {
        $error3 .= '<font color="red" size="2">Isi Tuntutan Klaim</font>.';
    }
    if ($error1 or $error2 or $error3) {
    } else {
        $metCNnya = $database->doQuery('UPDATE fu_ajk_cn SET tuntutan_klaim = "'.$_REQUEST['tuntutan_klaim'].'", tgl_claim="' . $_REQUEST['tglklaim'] . '", nmpenyakit="'.$_REQUEST['nmpenyakit'].'", noperkredit="' . $_REQUEST['noperdit'] . '", update_by="' . $q['id'] . '", update_time="' . $futgl . '" WHERE id="' . $_REQUEST['idc'] . '"');
        $metCNKlaimnya = $database->doQuery('UPDATE fu_ajk_klaim SET tuntutan_klaim = "'.$_REQUEST['tuntutan_klaim'].'", tgl_klaim="' . $_REQUEST['tglklaim'] . '", tempat_meninggal="'.$_REQUEST['lokasinya'].'", sebab_meninggal="' . $_REQUEST['nmpenyakit'] . '", update_by="' . $q['id'] . '", update_time="' . $futgl . '" WHERE id="' . $_REQUEST['idklaim'] . '"');
        if (!$_REQUEST['dokklaim']) {
            echo '<center>Tidak ada dokumen yang dipilih.</center>';
        } else {
            foreach ($_REQUEST['dokklaim'] as $doc => $docya) {
                $metdoknya = $database->doQuery('INSERT INTO fu_ajk_klaim_doc SET id_pes="' . $metEcn['id_peserta'] . '",
																				  id_cost="' . $metEcn['id_cost'] . '",
																				  id_dn="' . $metEcn['id_dn'] . '",
																				  id_klaim="' . $metEcn['id_klaim'] . '",
																				  dokumen="' . $docya . '",
																  				  input_by="' . $q['nm_user'] . '",
																  				  input_date="' . $futgl . '"');
            }
        }
        $tuntutan_klaim = $_REQUEST['tuntutan_klaim'];
        echo '<center><h2>Pengajuan laporan klaim telah diedt oleh ' . $q['nm_lengkap'] . ' pada tanggal ' . $futgl . '</h2></center>'.$_backFixDoc.'';
    }
}
echo '<form method="post" action="">
	<input type="hidden" name="sett" value="'.$_REQUEST['sett'].'">
	<table border="0" cellpadding="3" cellspacing="0" width="80%" align="center" style="border: solid 1px #DEDEDE" align="center">
	<input type="hidden" name="id" value="' . $_REQUEST['id'] . '">
	<input type="hidden" name="idklaim" value="' . $metKlaimEdit['id'] . '">
	<tr><th colspan="4">Form Pengisian Data Klaim Meninggal</th></tr>
	<tr><td width="20%">Nama Perusahaan</td><td>: <b>' . $metEcn['name'] . '</b></td>
		<td width="25%" align="right">ID Peserta</td><td>: ' . $metEcn['id_peserta'] . '</td>
	</tr>
	<tr><td width="20%" coslpan="3">Nama Produk</td><td>: <b>' . $metEcn['nmproduk'] . '</b></td></tr>
	<tr><td>Nama</td><td colspan="3">: <b>' . $metEcn['nama'] . '</b></td></tr>
	<tr><td>Plafond</td><td colspan="3">: ' . duit($metEcn['kredit_jumlah']) . '</td></tr>
	<tr><td>Nilai Tuntutan Klaim <font color="red"><b>*</b></font><br /> ' . $error4 . '</td><td colspan="3">: <input type="text" name="tuntutan_klaim" value="'.$tuntutan_klaim.'"></td></tr>
	<tr><td>Tanggal Asuransi</td><td>: ' . _convertDate($metEcn['kredit_tgl']) . ' s/d ' . _convertDate($metEcn['kredit_akhir']) . '</td>
		<td align="right">Jangka Waktu</td><td>: ' . $metEcn['tenor'] . ' Bulan</td>
	</tr>
	<tr><td>Tanggal Meninggal <font color="red"><b>*</b></font><br />' . $error1 . '</td><td valign="top">: ';
    print initCalendar();	print calendarBox('tglklaim', 'triger', $metEcn['tgl_claim']);
echo '</td>
	<td align="right">No. Perjanjian Kredit <font color="red"><b>*</b></font><br /> ' . $error2 . '</td><td valign="top">: <input type="text" name="noperdit" value="' . $metEcn['noperkredit'] . '"></td>
	</tr>
	<tr><td>Penyebab Meninggal <font color="red"><b>*</b></font><br />' . $error1 . '</td><td valign="top">:
	    <select size="1" name="nmpenyakit">
	   	<option value="">---Penyebab Meninggal---</option>';
        $nmPenyakit = $database->doQuery('SELECT * FROM fu_ajk_namapenyakit ORDER BY id ASC');
while ($nmPenyakit_ = mysql_fetch_array($nmPenyakit)) {
    echo '<option value="'.$nmPenyakit_['id'].'"'._selected($metEcn['nmpenyakit'], $nmPenyakit_['id']).'>'.$nmPenyakit_['namapenyakit'].'</option>';
}
echo '</select>
    		</td></tr>
    <tr><td>Lokasi Meninggal <font color="red"><b>*</b></font></td><td>: <select size="1" name="lokasinya">
		<option value="">--Pilih Lokasi--</option>
		<option value="Rumah"'._selected($metKlaimEdit["tempat_meninggal"], "Rumah").'>Rumah</option>
		<option value="Rumah Sakit"'._selected($metKlaimEdit["tempat_meninggal"], "Rumah Sakit").'>Rumah Sakit</option>
		<option value="Lain-Lain"'._selected($metKlaimEdit["tempat_meninggal"], "Lain-Lain").'>Lain-Lain</option>
		</select>'.$error4.'</td></tr>';
        if ($datadn['ket'] != "") {
            echo' <tr><td valign="top"><b>Keterangan Underwriting</b></td><td colspan="3">: ' . $datadn['ket'] . '</td></tr>';
        }
        echo '<tr><th colspan="5">Kelengkapan Dokumen</th></tr>
	<tr><td colspan="5">';

        echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
	  <th width="1%">No</th><th>Form Klaim</th><th width="5%">Option</th></tr>';
$met_dok = $database->doQuery('SELECT fu_ajk_dokumenklaim_bank.id,
									  fu_ajk_dokumenklaim_bank.id_bank,
									  fu_ajk_dokumenklaim_bank.id_dok,
									  fu_ajk_dokumenklaim.nama_dok,
									  fu_ajk_dokumenklaim.id AS idDOK,
									  fu_ajk_dokumenklaim.view
									  FROM fu_ajk_dokumenklaim_bank
									  INNER JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim_bank.id_dok = fu_ajk_dokumenklaim.id
									  WHERE fu_ajk_dokumenklaim_bank.id_bank="' . $metEcn['id_cost'] . '" AND
									  		fu_ajk_dokumenklaim_bank.id_produk="' . $metEcn['id_nopol'] . '" AND
									  		fu_ajk_dokumenklaim.view IS NULL
									  ORDER BY fu_ajk_dokumenklaim.nama_dok ASC');
while ($met_dok_ = mysql_fetch_array($met_dok)) {
    $cekDokumenKlaim = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_klaim_doc WHERE id_pes="' . $metEcn['id_peserta'] . '" AND id_cost="' . $metEcn['id_cost'] . '" AND dokumen="' . $met_dok_['id'] . '" AND del IS NULL'));
    if ($cekDokumenKlaim) {
        $cekDataDok = '<input title="dokumen tidak bisa diedit" type="checkbox" name="dokklaim[]" value="' . $met_dok_['id'] . '" checked disabled>';
    } else {
        $cekDataDok = '<input type="checkbox" name="dokklaim[]" value="' . $met_dok_['id'] . '">';
    }
    if (($no % 2) == 1) {
        $objlass = 'tbl-odd';
    } else {
        $objlass = 'tbl-even';
    }
    echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	  <td align="center">' . ++$no . '</td>
	  <td>' . $met_dok_['nama_dok'] . '</td>
	  <td align="center">' . $cekDataDok . '</td>
	  </tr>';
}
echo '</table>
	</td></tr>
	<tr><td colspan="4" align="center"><input type="submit" name="oop" value="Simpan"></td></tr>
	</table></form>';
    ;
   break;

    case "valKlaim":
echo '<table border="0" cellpadding="5" cellspacing="1" width="100%"><tr><th width="100%" align="left">Modul Validasi Data Klaim</font></th></tr></table>';
/*
        $fu1 = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="' . $q['id_cost'] . '"'));
        $fu2 = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="' . $q['id_polis'] . '"'));
        echo '<fieldset style="padding: 1">
    <legend align="center">Data Klaim</legend>
    <form name="f2" method="post" action="">
    <table border="0" width="100%" cellpadding="1" cellspacing="1">
    <tr><td width="15%">Nama Perusahaan</td>
        <td>: <input type="hidden" name="idcost" value="' . $q['id_cost'] . '">' . $fu1['name'] . '</td>
    </tr>';
if ($q['id_polis'] == "" AND $q['level'] == "6") {
$met_polis = $database->doQuery('SELECT * FROM fu_ajk_polis WHERE id_cost="' . $q['id_cost'] . '" AND del IS NULL ORDER BY nmproduk ASC');
echo '<tr><td>Nama Produk <font color="red">*</font></td>
          <td>: <select name="id_polis"><option value="">---Pilih Produk---</option>';
while ($met_polis_ = mysql_fetch_array($met_polis)) {
echo '<option value="' . $met_polis_['id'] . '"' . _selected($_REQUEST['id_polis'], $met_polis_['id']) . '>' . $met_polis_['nmproduk'] . '</option>';
}
echo '</select></td></tr>
    <tr><td>Staff <font color="red">*</font></td><td>';
$jData_User = $database->doQuery('SELECT id_cn, id_dn, id_cost, id_nopol, COUNT(id_peserta) AS jData, input_by, del FROM fu_ajk_cn WHERE confirm_claim = "Processing" AND type_claim="Death" AND del IS NULL GROUP BY id_cost, id_nopol, input_by');
echo ': <select name="user_input"><option value="">---Pilih Staff---</option>';
while ($jData_User_ = mysql_fetch_array($jData_User)) {
echo '<option value="' . $jData_User_['input_by'] . '"' . _selected($_REQUEST['user_input'], $jData_User_['input_by']) . '>' . $jData_User_['input_by'] . '</option>';
}
echo '</td></tr>
    <tr><td><input name="Submit" type="submit" value="Pilih"></td></tr>';
$data = $database->doQuery('SELECT * FROM fu_ajk_peserta_tempf WHERE nama !="" AND id_cost="' . $q['id_cost'] . '" AND id_polis="' . $_REQUEST['id_polis'] . '" AND status_aktif="Upload" AND del IS NULL ORDER BY input_time DESC, namafile ASC, no_urut ASC');
}
else {
//echo '<tr><td>Nama Produk</td><td>: ' . $fu2['nmproduk'] . '</td></tr>';
    $data = $database->doQuery('SELECT * FROM fu_ajk_peserta_tempf WHERE nama !="" AND id_cost="' . $q['id_cost'] . '" AND id_polis="' . $q['id_polis'] . '" AND input_by="' . $q['nm_user'] . '" AND status_aktif="Upload" AND del IS NULL ORDER BY input_time DESC, namafile ASC, no_urut ASC');
}
echo '<table></form></fieldset>';
if ($q['id_cost'] != "" AND $q['id_polis'] == "") {
    $_produk = 'AND id_nopol="' . $_REQUEST['id_polis'] . '"';
    $_userRef = 'AND input_by="' . $_REQUEST['user_input'] . '"';
    $met_checked = '<th width="5%">Email Klaim</th>';
} else {
    $_produk = 'AND id_nopol="' . $q['id_polis'] . '"';
    $_userRef = 'AND input_by="' . $q['nm_user'] . '"';
    $met_checked = '<th width="1%"> </th>';
}
if ($_REQUEST['Submit'] == "Pilih") {
if (!$_REQUEST['id_polis']) {	$error1 .= '<font color="red">Silahkan pilih produk !.</font><br />';	}
if (!$_REQUEST['user_input']) {	$error2 .= '<font color="red">Silahkan pilih staff !.</font>';	}
if ($error1 OR $error2) {	echo '<center>' . $error1 . '' . $error2 . '<meta http-equiv="refresh" content="2;URL=ajk_klaim.php?er=valKlaim"></center>';	} else {
echo '<form method="post" action="">
    <table border="0" width="100%" cellpadding="3" cellspacing="1" bgcolor="#bde0e6">
    <tr>' . $met_checked . '
        <th width="1%">No</th>
        <th width="10%">Nomor DN</th>
        <th width="8%">ID Peserta</th>
        <th>Nama Tertanggung</th>
        <th width="7%">Tanggal Lahir</th>
        <th width="1%">Usia</th>
        <th width="6%">Tgl Akad</th>
        <th width="1%">Tenor</th>
        <th width="6%">Tgl Akhir</th>
        <th width="7%">Plafond</th>
        <th width="7%">Premi</th>
        <th width="6%">Tgl Klaim</th>
        <th width="6%">Nilai Klaim</th>
        <th width="5%">Status</th>
        <th width="5%">Cabang</th>
        <th width="5%">User</th>
    </tr>';

if ($_REQUEST['x']) {	$m = ($_REQUEST['x'] - 1) * 25;	} else {	$m = 0;	}
$met_cn = $database->doQuery('SELECT * FROM fu_ajk_cn WHERE id_cost=' . $q['id_cost'] . ' ' . $_produk . ' AND confirm_claim="Pending" AND type_claim="Death" ' . $_userRef . ' AND del IS NULL ORDER BY id DESC');
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_cn WHERE id !="" AND id_cost="' . $q['id_cost'] . '" ' . $_produk . ' AND confirm_claim="Pending" AND type_claim="Death" ' . $_userRef . ' AND del IS NULL'));
$totalRows = $totalRows[0];
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while ($met_cn_ = mysql_fetch_array($met_cn)) {
$met_dn = mysql_fetch_array($database->doQuery('SELECT id, dn_kode FROM fu_ajk_dn WHERE id="' . $met_cn_['id_dn'] . '"'));
$met_peserta = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_cost="' . $met_cn_['id_cost'] . '" AND id_polis="' . $met_cn_['id_nopol'] . '" AND id_peserta="' . $met_cn_['id_peserta'] . '"'));

if ($q['id_cost'] == $met_cn_['id_cost'] AND $q['id_polis'] == "") {
    $dataceklist = '<a title="Konfrimasi data klaim" href="ajk_klaim.php?er=NotifKlaim&idk=' . $met_cn_['id'] . '" onClick="if(confirm(\'Kirim notifikasi persetujuan pangajuan klaim ke bagian Klaim Adonai ?\')){return true;}{return false;}"><img src="image/mailKlaim.png" width="30">';
} else {
$dataceklist = '';
}

if (($no % 2) == 1) $objlass = 'tbl-odd';	else $objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
        <td align="center">' . $dataceklist . '</td>
        <td align="center">' . (++$no + ($pageNow - 1) * 25) . '</td>
        <td align="center"><a href="aajk_report.php?er=_kwipeserta&idn=' . $met_cn_['id_dn'] . '" target="_blank">' . $met_dn['dn_kode'] . '</a></td>
        <td align="center">' . $met_cn_['id_peserta'] . '</td>
        <td><a title="preview data klaim" href="ajk_nota.php?er=vKlaim&idk=' . $met_cn_['id'] . '">' . $met_peserta['nama'] . '</a></td>
        <td align="center">' . _convertDate($met_peserta['tgl_lahir']) . '</td>
        <td align="center">' . $met_peserta['usia'] . '</td>
        <td align="center">' . _convertDate($met_peserta['kredit_tgl']) . '</td>
        <td align="center">' . $met_peserta['kredit_tenor'] . '</td>
        <td align="center">' . _convertDate($met_peserta['kredit_akhir']) . '</td>
        <td align="right">' . duit($met_peserta['kredit_jumlah']) . '</td>
        <td align="right"><b>' . duit($met_peserta['totalpremi']) . '<b></td>
        <td align="center">' . _convertDate($met_cn_['tgl_claim']) . '</td>
        <td align="right"><font color="red">' . duit($met_cn_['total_claim']) . '</font></td>
        <td align="center">' . $met_cn_['type_claim'] . '</td>
        <td align="center">' . $met_cn_['id_cabang'] . '</td>
        <td align="center">' . $met_cn_['input_by'] . '</td>
      </tr>';
    }
echo '</table>';
    }
}
else {
if ($q['id_cost'] != "" AND $q['id_polis'] == "") {
echo '<table border="0" width="100%" cellpadding="3" cellspacing="1" bgcolor="#bde0e6">
        <tr><th width="1%">No</th>
            <th>Nama Produk</th>
              <th width="20%">Jumlah Data Klaim</th>
              <th width="15%">User</th>
        </tr>';
$jData = $database->doQuery('SELECT id_cn, id_dn, id_cost, id_nopol, COUNT(id_peserta) AS jData, input_by, del
                           FROM fu_ajk_cn
                           WHERE type_claim="Death" AND confirm_claim = "Pending" AND del IS NULL
                           GROUP BY id_cost, id_nopol, input_by');
while ($jData_ = mysql_fetch_array($jData)) {
$met_produk = mysql_fetch_array($database->doQuery('SELECT id, nmproduk FROM fu_ajk_polis WHERE id="' . $jData_['id_nopol'] . '"'));
if (($no % 2) == 1) $objlass = 'tbl-odd';	else $objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
    <td align="center">' . ++$no . '</td>
    <td>' . $met_produk['nmproduk'] . '</td>
    <td align="center">' . $jData_['jData'] . ' Data Klaim</td>
    <td align="center">' . $jData_['input_by'] . '</td>
    </tr>';
    }
echo '</table>';
}
else {
echo '<form method="post" action="">
          <table border="0" width="100%" cellpadding="3" cellspacing="1" bgcolor="#bde0e6">
        <tr>' . $met_checked . '
            <th width="1%">No</th>
            <th width="10%">Nomor DN</th>
            <th width="8%">ID Peserta</th>
            <th>Nama Tertanggung</th>
            <th width="7%">Tanggal Lahir</th>
            <th width="1%">Usia</th>
            <th width="6%">Tgl Akad</th>
            <th width="1%">Tenor</th>
            <th width="6%">Tgl Akhir</th>
            <th width="7%">Plafond</th>
            <th width="7%">Premi</th>
            <th width="6%">Tgl Klaim</th>
            <th width="6%">Nilai Klaim</th>
            <th width="5%">Status</th>
            <th width="5%">Cabang</th>
            <th width="5%">User</th>
            </tr>';
if ($_REQUEST['x']) {	$m = ($_REQUEST['x'] - 1) * 25;	} else {	$m = 0;	}

//$met_cn = $database->doQuery('SELECT * FROM fu_ajk_cn WHERE id_cost=' . $q['id_cost'] . ' ' . $_produk . ' AND confirm_claim="Pending" AND type_claim="Death" ' . $_userRef . ' AND del IS NULL ORDER BY id DESC');
$met_cn = $database->doQuery('SELECT * FROM fu_ajk_cn WHERE id_cost=' . $q['id_cost'] . ' AND confirm_claim="Pending" AND type_claim="Death" ' . $_userRef . ' AND del IS NULL ORDER BY id DESC');
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_cn WHERE id !="" AND id_cost="' . $q['id_cost'] . '" ' . $_produk . ' AND confirm_claim="Pending" AND type_claim="Death" ' . $_userRef . ' AND del IS NULL'));
$totalRows = $totalRows[0];
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while ($met_cn_ = mysql_fetch_array($met_cn)) {
$met_dn = mysql_fetch_array($database->doQuery('SELECT id, dn_kode FROM fu_ajk_dn WHERE id="' . $met_cn_['id_dn'] . '"'));
$met_peserta = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_cost="' . $met_cn_['id_cost'] . '" AND id_polis="' . $met_cn_['id_nopol'] . '" AND id_peserta="' . $met_cn_['id_peserta'] . '"'));

if ($q['id_cost'] == $met_cn_['id_cost'] AND $q['id_polis'] == "") {
    $dataceklist = '<input type="checkbox" class="case" name="nameRef[]" value="' . $met_cn_['id'] . '">';
} else {
    $dataceklist = '';
}

if ($met_cn_['validasi_cn_uw'] == "ya") {
$dalvaldata = '';
} else {
$dalvaldata = '<a href="ajk_klaim.php?er=reqbatal&idref=' . $met_cn_['id'] . '" onClick="if(confirm(\'Apakah anda yakin untuk membatalkan data peserta refund ini ?\')){return true;}{return false;}"><img src="image/delete.gif"></a>';
}
if (($no % 2) == 1) $objlass = 'tbl-odd';
else $objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
        <td align="center">' . $dalvaldata . '&nbsp;' . $dataceklist . '</td>
        <td align="center">' . (++$no + ($pageNow - 1) * 25) . '</td>
        <td align="center"><a href="aajk_report.php?er=_kwipeserta&idn=' . $met_cn_['id_dn'] . '" target="_blank">' . $met_dn['dn_kode'] . '</a></td>
        <td align="center">' . $met_cn_['id_peserta'] . '</td>
        <td><a title="preview data klaim" href="ajk_nota.php?er=vKlaim&idk=' . $met_cn_['id'] . '">' . $met_peserta['nama'] . '</a></td>
        <td align="center">' . _convertDate($met_peserta['tgl_lahir']) . '</td>
        <td align="center">' . $met_peserta['usia'] . '</td>
        <td align="center">' . _convertDate($met_peserta['kredit_tgl']) . '</td>
        <td align="center">' . $met_peserta['kredit_tenor'] . '</td>
        <td align="center">' . _convertDate($met_peserta['kredit_akhir']) . '</td>
        <td align="right">' . duit($met_peserta['kredit_jumlah']) . '</td>
        <td align="right"><b>' . duit($met_peserta['totalpremi']) . '<b></td>
        <td align="center">' . _convertDate($met_cn_['tgl_claim']) . '</td>
        <td align="right"><font color="red">' . duit($met_cn_['total_claim']) . '</font></td>
        <td align="center">' . $met_cn_['confirm_claim'] . '</td>
        <td align="center">' . $met_cn_['id_cabang'] . '</td>
        <td align="center">' . $met_cn_['input_by'] . '</td>
    </tr>';
    }
if ($q['id_cost'] != "" AND $q['id_polis'] == "" AND $q['level'] == "6" AND $q['status'] == "") {
echo '<tr><td colspan="20" align="center"><a href="#" onClick="if(confirm(\'Apakah anda sudah yakin dengan semua data peserta klaims ini ?\')){return true;}{return false;}">
                                          <input type="hidden" name="er" Value="val_app_klaim"><input type="submit" name="ve" Value="Approve"></td></tr>';
} else {	}
    echo '</table>';
    }
}
*/

if ($q['level']=="6" and $q['status']=="SUPERVISOR-ADMIN") {
    $_produk = 'AND id_nopol="' . $_REQUEST['id_polis'] . '"';
    $_userRef = 'AND input_by="' . $_REQUEST['user_input'] . '"';
    $met_checked = '<th width="5%">Konfirmasi</th>';
} elseif ($q['level']=="6" and $q['status']=="SUPERVISOR") {
    $_produk = 'AND id_nopol="' . $_REQUEST['id_polis'] . '"';
    $_userRef = 'AND input_by="' . $_REQUEST['user_input'] . '"';
    $met_checked = '<th width="5%">Konfirmasi<br />&nbsp; &nbsp; &nbsp; <input type="checkbox" id="selectall"/></th>';
} else {
    $_produk = 'AND id_nopol="' . $q['id_polis'] . '"';
    $_userRef = 'AND input_by="' . $q['nm_user'] . '"';
    $met_checked = '<th width="1%">Hapus</th>';
}
echo '<form method="post" action="">
	<table border="0" width="100%" cellpadding="3" cellspacing="1" bgcolor="#bde0e6">
	<tr>' . $met_checked . '
		<th width="1%">No</th>
		<th width="10%">Nomor DN</th>
		<th width="1%">ID Peserta</th>
		<th>Nama Tertanggung</th>
		<th width="5%">Tanggal Lahir</th>
		<th width="1%">Usia</th>
		<th width="5%">Tgl Akad</th>
		<th width="1%">Tenor</th>
		<th width="5%">Tgl Akhir</th>
		<th width="7%">Plafond</th>
		<th width="7%">Premi</th>
		<th width="5%">Tgl Klaim</th>
		<th width="1%">Nilai Klaim</th>
		<th width="5%">Status</th>
		<th width="5%">Cabang</th>
		<th width="5%">User</th>
	</tr>';
if ($_REQUEST['x']) {
    $m = ($_REQUEST['x'] - 1) * 25;
} else {
    $m = 0;
}

//$met_cn = $database->doQuery('SELECT * FROM fu_ajk_cn WHERE id_cost=' . $q['id_cost'] . ' ' . $_produk . ' AND confirm_claim="Pending" AND type_claim="Death" ' . $_userRef . ' AND del IS NULL ORDER BY id DESC');
if ($q['level']=="7" and $q['status']=="") {
    $met_cn = $database->doQuery('SELECT * FROM fu_ajk_cn WHERE id_cost=' . $q['id_cost'] . ' AND confirm_claim="Pending" AND type_claim="Death" ' . $_userRef . ' AND del IS NULL ORDER BY id DESC');
} elseif ($q['level']=="6" and $q['status']=="SUPERVISOR") {
    $cekdatacab = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id_cost="'.$q['id_cost'].'" AND name="'.$q['cabang'].'" and del is null'));			//VALIDASI CABANG
    //echo $cekdatacab['name'];
    $cekCentral = $database->doQuery('SELECT * FROM fu_ajk_cabang WHERE centralcbg="'.$cekdatacab['id'].'"');
    while ($cekCentral_ = mysql_fetch_array($cekCentral)) {
        $metCentralCabang .= ' OR id_cabang ="' . $cekCentral_['name'] . '"';
    }	// CEK DATA CABANG CENTRAL;
    if ($metCentralCabang == "") {
        $metCabangCentral = 'id_cabang ="' . $q['cabang'] . '"';
    } else {
        $metCabangCentral = '(id_cabang ="' . $q['cabang'] . '" ' . $metCentralCabang . ')';
    }
    // CEK DATA CABANG CENTRAL;
    if ($q['setklaimcabang']=="Ya") {
        $met_cn = $database->doQuery('SELECT * FROM fu_ajk_cn WHERE id_cost=' . $q['id_cost'] . ' AND confirm_claim="Pending" AND type_claim="Death" AND del IS NULL ORDER BY id DESC');
    } else {
        $met_cn = $database->doQuery('SELECT * FROM fu_ajk_cn WHERE id_cost=' . $q['id_cost'] . ' AND confirm_claim="Pending" AND type_claim="Death" AND ' . $metCabangCentral . ' AND del IS NULL ORDER BY id DESC');
    }
} elseif ($q['level']=="6" and $q['status']=="SUPERVISOR-ADMIN") {
    $met_cn = $database->doQuery('SELECT * FROM fu_ajk_cn WHERE id_cost=' . $q['id_cost'] . ' AND confirm_claim="Approve" AND type_claim="Death" AND del IS NULL ORDER BY id DESC');
} else {
}
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_cn WHERE id !="" AND id_cost="' . $q['id_cost'] . '" ' . $_produk . ' AND confirm_claim="Pending" AND type_claim="Death" ' . $_userRef . ' AND del IS NULL'));
$totalRows = $totalRows[0];
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while ($met_cn_ = mysql_fetch_array($met_cn)) {
    $met_dn = mysql_fetch_array($database->doQuery('SELECT id, dn_kode FROM fu_ajk_dn WHERE id="' . $met_cn_['id_dn'] . '"'));
    $met_peserta = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_cost="' . $met_cn_['id_cost'] . '" AND id_polis="' . $met_cn_['id_nopol'] . '" AND id_peserta="' . $met_cn_['id_peserta'] . '"'));

    if ($q['level']=="7" and $q['status']=="" and  $met_cn_['confirm_claim']=="Pending") {
        //$dalvaldata = '<a title="Membatalkan data klaim" href="ajk_klaim.php?er=reqbatal&idref=' . $met_cn_['id'] . '" onClick="if(confirm(\'Apakah anda yakin untuk membatalkan data peserta klaim ini ?\')){return true;}{return false;}"><img src="image/delete.gif" width="15"></a>';
        $dalvaldata = '<a title="Membatalkan data klaim" href="ajk_klaim.php?er=reqbatalklaim&idref=' . $met_cn_['id'] . '" onClick="if(confirm(\'Apakah anda yakin untuk membatalkan data peserta klaim ini ?\')){return true;}{return false;}"><img src="image/delete.gif" width="15"></a>';
        $dataceklist = '';
    } elseif ($q['level']=="6" and $q['status']=="SUPERVISOR" and  $met_cn_['confirm_claim']=="Pending") {
        //$dalvaldata = '<a title="Membatalkan data klaim" href="ajk_klaim.php?er=reqbatal&idref=' . $met_cn_['id'] . '" onClick="if(confirm(\'Apakah anda yakin untuk membatalkan data peserta klaim ini ?\')){return true;}{return false;}"><img src="image/delete.gif" width="15"></a>';
        $dalvaldata = '<a title="Membatalkan data klaim" href="ajk_klaim.php?er=reqbatalklaim&idref=' . $met_cn_['id'] . '" onClick="if(confirm(\'Apakah anda yakin untuk membatalkan data peserta klaim ini ?\')){return true;}{return false;}"><img src="image/delete.gif" width="15"></a>';
        $dataceklist = '<input type="checkbox" class="case" name="nameRef[]" value="' . $met_cn_['id'] . '">';
    } elseif ($q['level']=="6" and $q['status']=="SUPERVISOR-ADMIN" and  $met_cn_['confirm_claim']=="Approve") {
        //$dalvaldata = '<a title="Membatalkan data klaim" href="ajk_klaim.php?er=reqbatal&idref=' . $met_cn_['id'] . '" onClick="if(confirm(\'Apakah anda yakin untuk membatalkan data peserta klaim ini ?\')){return true;}{return false;}"><img src="image/delete.gif" width="15"></a>';
        $dalvaldata = '<a title="Membatalkan data klaim" href="ajk_klaim.php?er=reqbatalklaim&idref=' . $met_cn_['id'] . '" onClick="if(confirm(\'Apakah anda yakin untuk membatalkan data peserta klaim ini ?\')){return true;}{return false;}"><img src="image/delete.gif" width="15"></a>';
        $dataceklist = '<a title="Konfrimasi data klaim" href="ajk_klaim.php?er=NotifKlaim&idk=' . $met_cn_['id'] . '" onClick="if(confirm(\'Kirim notifikasi persetujuan pangajuan klaim ke bagian Klaim Adonai ?\')){return true;}{return false;}"><img src="image/mailKlaim.png" width="15">';
    //$dataceklist = '<input type="checkbox" class="case" name="nameRef[]" value="' . $met_cn_['id'] . '">';
    } else {
    }

    /*
    if ($q['id_cost'] == $met_cn_['id_cost'] AND $q['id_polis'] == "") {
        $dataceklist = '<input type="checkbox" class="case" name="nameRef[]" value="' . $met_cn_['id'] . '">';
    } else {
        $dataceklist = '';
    }
    if ($met_cn_['validasi_cn_uw'] == "ya") {
        $dalvaldata = '';
    } else {
        $dalvaldata = '<a href="ajk_klaim.php?er=reqbatal&idref=' . $met_cn_['id'] . '" onClick="if(confirm(\'Apakah anda yakin untuk membatalkan data peserta refund ini ?\')){return true;}{return false;}"><img src="image/delete.gif"></a>';
    }
    */
    if (($no % 2) == 1) {
        $objlass = 'tbl-odd';
    } else {
        $objlass = 'tbl-even';
    }
    echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
			<td align="center">' . $dalvaldata . '&nbsp;' . $dataceklist . '</td>
			<td align="center">' . (++$no + ($pageNow - 1) * 25) . '</td>
			<td align="center"><a href="aajk_report.php?er=_kwipeserta&idn=' . $met_cn_['id_dn'] . '" target="_blank">' . $met_dn['dn_kode'] . '</a></td>
			<td align="center">' . $met_cn_['id_peserta'] . '</td>
			<td><a title="preview data klaim" href="ajk_nota.php?er=vKlaim&idk=' . $met_cn_['id'] . '">' . $met_peserta['nama'] . '</a></td>
			<td align="center">' . _convertDate($met_peserta['tgl_lahir']) . '</td>
			<td align="center">' . $met_peserta['usia'] . '</td>
			<td align="center">' . _convertDate($met_peserta['kredit_tgl']) . '</td>
			<td align="center">' . $met_peserta['kredit_tenor'] . '</td>
			<td align="center">' . _convertDate($met_peserta['kredit_akhir']) . '</td>
			<td align="right">' . duit($met_peserta['kredit_jumlah']) . '</td>
			<td align="right"><b>' . duit($met_peserta['totalpremi']) . '<b></td>
			<td align="center">' . _convertDate($met_cn_['tgl_claim']) . '</td>
			<td align="right"><font color="red">' . duit($met_cn_['total_claim']) . '</font></td>
			<td align="center">' . $met_cn_['confirm_claim'] . '</td>
			<td align="center">' . $met_cn_['id_cabang'] . '</td>
			<td align="center">' . $met_cn_['input_by'] . '</td>
		</tr>';
}
if ($q['id_cost'] != "" and $q['level'] == "6" and $q['status'] == "SUPERVISOR") {
    echo '<tr><td colspan="20" align="center"><a href="#" onClick="if(confirm(\'Apakah anda sudah yakin dengan semua data peserta klaim ini ?\')){return true;}{return false;}">
    	  <input type="hidden" name="er" Value="val_app_klaim"><input type="submit" name="ve" Value="Approve"></td></tr>';
} else {
}
echo '</table>';

        ;
    break;

    case "val_app_klaim":
        if (!$_REQUEST['nameRef']) {
            echo '<center><font color=red><blink>Tidak ada data klaim yang di pilih, silahkan ceklist data yang akan disetujui untuk pengajuan klaim. !</blink></font><br/>
	  <a href="ajk_klaim.php?er=valKlaim">Kembali Ke Halaman Validasi Klaim</a></center>';
        } else {
            foreach ($_REQUEST['nameRef'] as $r => $Klaim_) {
                //$metKlaim = $database->doQuery('UPDATE fu_ajk_cn SET confirm_claim="Approve", approve_by="'.$q['nm_user'].'", approve_date="'.$futgl.'" WHERE id="'.$Klaim_.'"');		170316 DATA SEBELUMNYA
                $metKlaim = $database->doQuery('UPDATE fu_ajk_cn SET confirm_claim="Processing", approve_by="'.$q['nm_user'].'", approve_date="'.$futgl.'" WHERE id="'.$Klaim_.'"');

                ///tanggal 21 Februari 2018
                //$dataku=mysql_fetch_array($database->doQuery('select MAX(CAST(no_urut_klaim AS SIGNED))+1 as no_urut from fu_ajk_klaim where del is null and type_klaim="Death"'));
                //$dataku=$database->doQuery('update fu_ajk_klaim set no_urut_klaim="'.$dataku['no_urut'].'" where id_cn="'.$Klaim_.'"');
                ////////////////////////////////////

                $metUpdateOrder = $database->doQuery('UPDATE ajk_order_klaim SET  status="Approve", approve_by="' . $q['nm_user'] . '", approve_date="' . $futgl . '" WHERE idcn="' . $Klaim_ . '"');

                $_metklaim_ = $database->doQuery('SELECT
fu_ajk_polis.nmproduk,
fu_ajk_peserta.nama_mitra,
fu_ajk_peserta.nama,
fu_ajk_peserta.spaj,
fu_ajk_peserta.tgl_lahir,
fu_ajk_peserta.usia,
fu_ajk_peserta.kredit_tgl,
IF(fu_ajk_peserta.type_data="SPK", fu_ajk_peserta.kredit_tenor * 12, fu_ajk_peserta.kredit_tenor) AS kredit_tenor,
fu_ajk_peserta.kredit_akhir,
fu_ajk_peserta.kredit_jumlah,
fu_ajk_peserta.totalpremi,
fu_ajk_cn.id_peserta,
fu_ajk_cn.tgl_claim,
IF(fu_ajk_cn.type_claim = "Death", "Meninggal",fu_ajk_cn.type_claim) AS type_claim,
fu_ajk_cn.confirm_claim,
fu_ajk_cn.input_by,
fu_ajk_cn.input_date,
fu_ajk_cn.approve_by,
fu_ajk_cn.approve_date,
fu_ajk_cn.id_cabang
FROM fu_ajk_cn
INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_dn = fu_ajk_peserta.id_dn AND fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
WHERE fu_ajk_cn.id = "'.$Klaim_.'"');
                while ($_metklaim__ = mysql_fetch_array($_metklaim_)) {
                    if (($no % 2) == 1) {
                        $objlass = 'tbl-odd';
                    } else {
                        $objlass = 'tbl-even';
                    }
                    $message .= '<tr><td align="center">'.++$no.'</td>
					 <td align="center">'.$_metklaim__['nmproduk'].'</td>
					 <td align="center">'.$_metklaim__['nama_mitra'].'</td>
					 <td align="center">'.$_metklaim__['spaj'].'</td>
					 <td align="center">'.$_metklaim__['id_peserta'].'</td>
					 <td>'.$_metklaim__['nama'].'</td>
					 <td align="center">'._convertDate($_metklaim__['tgl_lahir']).'</td>
					 <td align="center">'.$_metklaim__['usia'].'</td>
					 <td align="center">'._convertDate($_metklaim__['kredit_tgl']).'</td>
					 <td align="center">'.$_metklaim__['kredit_tenor'].'</td>
					 <td align="center">'._convertDate($_metklaim__['kredit_akhir']).'</td>
					 <td align="right">'.duit($_metklaim__['kredit_jumlah']).'</td>
					 <td align="right">'.duit($_metklaim__['totalpremi']).'</td>
					 <td align="center">'._convertDate($_metklaim__['tgl_claim']).'</td>
					 <td align="center">'.$_metklaim__['type_claim'].'</td>
					 <td align="center">'.$_metklaim__['confirm_claim'].'</td>
					 <td align="center">'.$_metklaim__['input_by'].'</td>
					 <td align="center">'.$_metklaim__['input_date'].'</td>
					 <td align="center">'.$_metklaim__['approve_by'].'</td>
					 <td align="center">'.$_metklaim__['approve_date'].'</td>
					 <td align="center">'.$_metklaim__['id_cabang'].'</td>
				 </tr>';
                }
            }
            $metPusat = mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE id_cost="'.$q['id_cost'].'" AND id_polis="" AND cabang="PUSAT" AND level="6" AND status="SUPERVISOR-ADMIN" AND del IS NULL'));
            echo $metPusat['email'].'<br />';
            $message = 'Kepada '.strtoupper($metPusat['nm_lengkap']).',<br />
			Terlampir data persetujuan pengajuan klaim dari Supervisor cabang '.$q['cabang'].'.
			<table border="0" width="100%" cellpadding="1" cellspacing="1" style="border: solid 2px #DEDEDE">
			<tr align="center" bgcolor="#bde0e6">
			  <td width="1%">No</td>
		  	  <td width="1%">Produk</td>
		  	  <td width="5%">Nama Mitra</td>
		 	  <td width="1%">SPK</td>
		 	  <td width="5%">ID Peserta</td>
		  	  <td>Nama</td>
		  	  <td width="8%">Tgl Lahir</td>
		  	  <td width="1%">Usia</td>
		  	  <td width="8%">Tanggal Mulai</td>
		  	  <td width="1%">Tenor</td>
		  	  <td width="8%">Tanggal Akhir</td>
		  	  <td width="1%">Plafond</td>
		  	  <td width="1%">Total Premi</td>
		  	  <td width="8%">Tgl Klaim</td>
		  	  <td width="1%">Type Klaim</td>
		  	  <td width="1%">Status</td>
		  	  <td width="1%">Input</td>
		  	  <td width="8%">Tgl Input</td>
		  	  <td width="1%">Approve</td>
		  	  <td width="8%">Tgl Approve</td>
		  	  <td width="1%">Cabang</td>
		  </tr>'.$message.'
		  </table>
		  <br />Mohon melakukan approval oleh Supervisor '.$metPusat['cabang'].' untuk segera diproses ke bagian klaim PT. Adonai.<br />
		  Terimakasih,<br />'.$q['nm_lengkap'].'';
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

            $mail->AddAddress($metPusat['email'], $metPusat['nm_lengkap']); //EMAIL SPV
            $mail->SetFrom($q['email'], $q['nm_lengkap']); //From address of the mail
            $mail->Subject = "AJKOnline - APPROVE DATA KLAIM"; //Subject od your mail

            $mailclient = $database->doQuery('SELECT * FROM fu_ajk_mail WHERE type="Klaim" AND status="Aktif"');
            while ($_mailclient = mysql_fetch_array($mailclient)) {
                $mail->AddAddress($_mailclient['emailto'], $_mailclient['emailnama']); //To address who will receive this email
            }
            $mail->AddAddress("klaim@adonai.co.id"); //To address who will receive this email
            $mail->AddBCC("adn.info.notif@gmail.com");
            //$mail->AddCC("rahmad@adonaits.co.id");
            $mail->MsgHTML($message); //Put your body of the message you can place html code here
            $send = $mail->Send(); //Send the mails
//echo $message;
echo '<div class="title2" align="center">Data pengajuan persetujuan klaim telah di Approve oleh ' . $q['nm_lengkap'] . ' pada tanggal ' . $futgldn . ' ' . $timelog . '.</div>
	  <meta http-equiv="refresh" content="3;URL=ajk_klaim.php?er=valRefund">';
        }
    ;
    break;

    case "NotifKlaim":
        $met_klaim = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cn WHERE id="' . $_REQUEST['idk'] . '"'));
        $met_klaim_order = mysql_fetch_array($database->doQuery('SELECT * FROM ajk_order_klaim WHERE idcn="' . $_REQUEST['idk'] . '"'));
        $met_klaim_cost = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_costumer WHERE id="' . $met_klaim['id_cost'] . '"'));
        $met_klaim_polis = mysql_fetch_array($database->doQuery('SELECT id, id_cost, nopol, nmproduk FROM fu_ajk_polis WHERE id="' . $met_klaim['id_nopol'] . '" AND id_cost="' . $met_klaim['id_cost'] . '"'));
        $met_klaim_peserta = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_cost="' . $met_klaim['id_cost'] . '" AND id_polis="' . $met_klaim['id_nopol'] . '" AND id_peserta="' . $met_klaim['id_peserta'] . '" AND id_dn="' . $met_klaim['id_dn'] . '" AND id_klaim="' . $met_klaim['id'] . '" AND status_peserta="Death"'));
        $met_klaim_dn = mysql_fetch_array($database->doQuery('SELECT id, id_cost, id_nopol, dn_kode, dn_status, tgl_dn_paid FROM fu_ajk_dn WHERE id_cost="' . $met_klaim['id_cost'] . '" AND id_nopol="' . $met_klaim['id_nopol'] . '" AND id="' . $met_klaim['id_dn'] . '"'));
        $met_penyakit = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_namapenyakit WHERE id="'.$met_klaim['nmpenyakit'].'"'));
        echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
		<tr><th colspan="8" width="100%" align="left" colspan="2">Modul Klaim Meninggal</font></th>
			<th width="5%" colspan="2"><a title="kembali ke halaman sebelumnya" href="ajk_nota.php?er=vKlaim&idk=' . $_REQUEST['idk'] . '"><img src="image/Backward-64.png" width="20"></a></th>
		</tr>
		</table><br />';

        $metUpdateOrder = $database->doQuery('UPDATE ajk_order_klaim SET status="Approve", approve_by="' . $q['nm_user'] . '", approve_date="' . $futgl . '" WHERE idcn="' . $_REQUEST['idk'] . '"');
        $metUpdateCN = $database->doQuery('UPDATE fu_ajk_cn SET confirm_claim="Processing", update_by="' . $q['nm_user'] . '", update_time="' . $futgl . '" WHERE id="' . $_REQUEST['idk'] . '"');

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

        $mail_klaim = mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE id_cost="" AND id_polis="" AND status="UNDERWRITING" AND del IS NULL'));
        $mail_staff = mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE id_cost="' . $met_klaim['id_cost'] . '" AND id_polis="' . $met_klaim['id_nopol'] . '" AND nm_user="' . $met_klaim['input_by'] . '" AND del IS NULL'));
        $mail->AddAddress($mail_klaim['email'], $mail_klaim['nm_lengkap']); //To address who will receive this email
        $mail->AddAddress($mail_staff['email'], $mail_staff['nm_lengkap']); //To address who will receive this email

        $mailclient = $database->doQuery('SELECT * FROM fu_ajk_mail WHERE type="Klaim" AND status="Aktif"');
        while ($_mailclient = mysql_fetch_array($mailclient)) {
            $mail->AddAddress($_mailclient['emailto'], $_mailclient['emailnama']); //To address who will receive this email
        }

        $mail->SetFrom($q['email'], $q['nm_lengkap']); //From address of the mail
        $mail->Subject = "AJKOnline - PERSETUJUAN PENGAJUAN KLAIM"; //Subject od your mail

        $mail->AddBCC("adn.info.notif@gmail.com");
        // $mail->AddCC("rahmad@adonaits.co.id");
        $send = $mail->Send();

$message .= 'To ' . $mail_klaim['nm_lengkap'] . ',<br />Pengajuan Klaim oleh ' . $met_klaim['input_by'] . ' telah disetujui oleh ' . $q['nm_lengkap'] . ' berdasarkan data sebagai berikut:
	  <table border="0" cellpadding="3" cellspacing="0" width="100%">
	  <tr><td width="20%">Nama Perusahaan</td><td colspan="3">: ' . $met_klaim_cost['name'] . '</td></tr>
	  <tr><td>Nama</td><td width="50%">: ' . $met_klaim_peserta['nama'] . '</td><td width="15%">Nama Produk</td><td>: ' . $met_klaim_polis['nmproduk'] . '</td></tr>
	  <tr><td>Id Peserta</td><td>: ' . $met_klaim_peserta['id_peserta'] . '</td><td>Debit Note</td><td>: ' . $met_klaim_dn['dn_kode'] . '</td></tr>
	  <tr><td>Tanggal Lahir</td><td>: ' . _convertDate($met_klaim_peserta['tgl_lahir']) . '</td><td>Credit Note</td><td>: ' . $met_klaim['id_cn'] . '</td></tr>
	  <tr><td>Usia</td><td>: ' . $met_klaim_peserta['usia'] . ' tahun</td><td>Tanggal Bayar DN</td><td>: ' . _convertDate($met_klaim_peserta['tgl_bayar']) . '</td></tr>
	  <tr><td>Uang Pertanggungan</td><td>: ' . duit($met_klaim_peserta['kredit_jumlah']) . '</td><td>Nilai Outstanding</td><td>: ' . duit($met_klaim['total_claim']) . '</td></tr>
	  <tr><td>Tanggal Mulai Asuransi</td><td>: ' . _convertDate($met_klaim_peserta['kredit_tgl']) . '</td><td>Tanggal Meninggal</td><td>: <b>' . _convertDate($met_klaim['tgl_claim']) . '</b></td></tr>
	  <tr><td>Tanggal Akhir Asuransi</td><td>: ' . _convertDate($met_klaim_peserta['kredit_akhir']) . '</td><td>Usia Polis</td><td>: ' . $usiapolis[0] . ' Tahun ' . $usiapolis[1] . ' Bulan ' . $usiapolis[2] . ' Hari</td></tr>
	  <tr><td>Tenor</td><td>: ' . $met_klaim_peserta['kredit_tenor'] . ' Bulan</td><td>Nomor Perjanjian Kredit</td><td>: <b>' . $met_klaim['noperkredit'] . '</b></tr>
	  <tr><td>Penyebab Meninggal</td><td colspan="3">: <b>'.$met_penyakit['namapenyakit'].'</b></tr>
	  </table><br />
	  <table border="0" cellpadding="3" cellspacing="0" width="100%">
	  <tr><th width="1%">No</th>
	  	  <th>Nama Dokumen</th>
	  	  <th width="10%">Dokumen</th>
	  </tr>';
$mamet_dokumen = $database->doQuery('SELECT * FROM fu_ajk_klaim_doc WHERE id_cost="' . $met_klaim['id_cost'] . '" AND id_pes="' . $met_klaim['id_peserta'] . '" AND id_dn="' . $met_klaim['id_dn'] . '" AND id_klaim="' . $met_klaim['id'] . '"');
while ($er_dokumen = mysql_fetch_array($mamet_dokumen)) {
    $_DokKlaim = mysql_fetch_array($database->doQuery('SELECT
fu_ajk_dokumenklaim_bank.id,
fu_ajk_dokumenklaim_bank.id_bank,
fu_ajk_dokumenklaim_bank.id_produk,
fu_ajk_dokumenklaim_bank.id_dok,
fu_ajk_dokumenklaim.id,
fu_ajk_dokumenklaim.nama_dok
FROM
fu_ajk_dokumenklaim_bank
INNER JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim_bank.id_dok = fu_ajk_dokumenklaim.id
WHERE
fu_ajk_dokumenklaim_bank.id = "'.$er_dokumen['dokumen'].'"'));
    if ($er_dokumen['nama_dokumen'] !="") {
        $kelengkapanDok = 'Ada';
    } else {
        $kelengkapanDok = 'Tidak Ada';
    }
    if (($no1 % 2) == 1) {
        $objlass = 'tbl-odd';
    } else {
        $objlass = 'tbl-even';
    }
    $message .='<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		  <td align="center">'.++$no1.'</td>
		  <td>'.$_DokKlaim['nama_dok'].'</td>
		  <td align="center">'.$kelengkapanDok.'</td>
		  </tr>';
}
        $message .= '<tr><td colspan="3">Alasan dokumen tidak lengkap</td></tr>
			<tr><td colspan="3">' . $met_klaim_order['note'] . '<br /><br /></td></tr>
			</table>';
        $mail->MsgHTML($message); //Put your body of the message you can place html code here
        $send = $mail->Send(); //Send the mails
        //echo $message;
        echo '<center>Persetujuan pengajuan klaim telah dikirim melalui email ke bagian Klaim Adonai oleh sistem secara otomatis.</center><meta http-equiv="refresh" content="2;URL=ajk_klaim.php?er=valKlaim">'; ;
        break;

    case "reqklaim":
        echo '<table border="0" cellpadding="5" cellspacing="1" width="100%"><tr><th width="100%" align="left">Modul Klaim</font></th></tr></table>';
        echo '<fieldset style="padding: 2">
			<legend align="center">S e a r c h</legend>
			<table border="0" width="100%" cellpadding="1" cellspacing="1">
			<form method="post" action="">
			<tr><td width="30%" align="right">Nama Perusahaan :</td>
				  <td width="30%">';
        $quer2 = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="' . $q['id_cost'] . '"'));
        echo $quer2['name'];
        echo '</td></tr>
				<tr><td align="right">Nomor Peserta :</td><td><input type="text" name="id_er" value="' . $_REQUEST['id_er'] . '"></td></tr>
				<tr><td align="right">Nama Peserta :</td><td><input type="text" name="nama_er" value="' . $_REQUEST['nama_er'] . '"></td></tr>
				<tr><td align="center" colspan="2"><input type="hidden" name="re" value="datapeserta"><input type="submit" name="button" value="Cari" class="button"></td></tr>
				</form></table></fieldset>';
        if ($_REQUEST['re'] == "datapeserta") {
            echo '<table border="0" width="100%" cellpadding="3" cellspacing="1" bgcolor="#bde0e6">
			<tr><th width="1%"></th>
				<th width="1%">No</th>
				<th width="10%">Nomor DN</th>
				<th width="10%">ID Peserta</th>
				<th>Nama Tertanggung</th>
				<th width="8%">Tanggal Lahir</th>
				<th width="1%">Usia</th>
				<th width="10%">Uang Asuransi</th>
				<th width="8%">Mulai Asuransi</th>
				<th width="1%">Tenor</th>
				<th width="8%">Akhir Asuransi</th>
				<th width="1%">EM</th>
				<th width="5%">Premi</th>
				<th width="5%">Status</th>
				<th width="5%">Cabang</th>
				</tr>';
            if ($_REQUEST['nama_er']) {
                $satu = 'AND nama LIKE "%' . $_REQUEST['nama_er'] . '%"';
            }
            if ($_REQUEST['id_er']) {
                $dua = 'AND id_peserta LIKE "%' . $_REQUEST['id_er'] . '%"';
            }
            if ($_REQUEST['x']) {
                $m = ($_REQUEST['x'] - 1) * 25;
            } else {
                $m = 0;
            }
            if ($q['setklaimcabang']=="Ya") {
                // $data = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE nama !="" AND id_cost="'.$q['id_cost'].'" AND id_polis="'.$q['id_polis'].'" ' . $satu . ' '.$dua.' AND status_aktif="Inforce" AND del IS NULL ORDER BY status_bayar ASC, id_dn DESC LIMIT '.$m.', 25');
                $data = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE nama !="" AND id_cost="' . $q['id_cost'] . '" ' . $satu . ' ' . $dua . ' AND status_aktif="Inforce" AND del IS NULL ORDER BY status_bayar ASC, id_dn DESC LIMIT ' . $m . ', 25');
                // $totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_peserta WHERE id_klaim="" and nama !="" AND id_cost="'.$q['id_cost'].'" AND id_polis="'.$q['id_polis'].'" ' . $satu . ' '.$dua.' AND del IS NULL'));
                $totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_peserta WHERE nama !="" AND id_cost="' . $q['id_cost'] . '" ' . $satu . ' ' . $dua . ' AND status_aktif="Inforce" AND del IS NULL'));
            } else {
                $data = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_klaim="" and nama !="" AND id_cost="' . $q['id_cost'] . '"  AND input_by="' . $q['nm_user'] . '" ' . $satu . ' ' . $dua . ' AND status_aktif="Inforce" AND del IS NULL ORDER BY status_bayar ASC, id_dn DESC LIMIT ' . $m . ', 25');
                $totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_peserta WHERE nama !="" AND id_cost="' . $q['id_cost'] . '" AND input_by="' . $q['nm_user'] . '" ' . $satu . ' ' . $dua . ' AND status_aktif="Inforce" AND del IS NULL'));
            }
            $totalRows = $totalRows[0];
            $_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
            // echo 'SELECT * FROM fu_ajk_peserta WHERE nama !="" AND id_cost="'.$q['id_cost'].'" AND id_polis="'.$q['id_polis'].'" ' . $satu . ' '.$dua.' '.$tiga.' '.$empat.'  AND del IS NULL ORDER BY input_by ASC LIMIT '.$m.', 25';exit;
            while ($fudata = mysql_fetch_array($data)) {
                $met_dn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn WHERE id="' . $fudata['id_dn'] . '"'));
                if ($fudata['status_bayar'] == 0) {
                    $metstatusrefund = "<font color=red>UNPAID</font>";
                    $Rrefund = '';
                } else {
                    $metstatusrefund = "<font color=blue>PAID</font>";
                    $Rrefund = '<a href="ajk_klaim.php?er=settKlaim&id=' . $fudata['id'] . '" onClick="if(confirm(\'Apakah anda yakin untuk melakukan pengajuan data klaim pada peserta ini ?\')){return true;}{return false;}"><img src="image/save.png" width="20"></a>';
                }
                if (($no % 2) == 1) {
                    $objlass = 'tbl-odd';
                } else {
                    $objlass = 'tbl-even';
                }
                echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
			<td align="center">' . $Rrefund . '</td>
			<td align="center">' . (++$no + ($pageNow - 1) * 25) . '</td>
			<td align="center">' . $met_dn['dn_kode'] . '</td>
			<td align="center">' . $fudata['id_peserta'] . '</td>
			<td>' . $fudata['nama'] . '</td>
			<td align="center">' . _convertDate($fudata['tgl_lahir']) . '</td>
			<td align="center">' . $fudata['usia'] . '</td>
			<td align="right">' . duit($fudata['kredit_jumlah']) . '</td>
			<td align="center">' . _convertDate($fudata['kredit_tgl']) . '</td>
			<td align="center">' . $fudata['kredit_tenor'] . '</td>
			<td align="center">' . _convertDate($fudata['kredit_akhir']) . '</td>
			<td align="center">' . $fudata['ext_premi'] . '</td>
			<td align="right">' . duit($fudata['totalpremi']) . '</td>
			<td align="center">' . $metstatusrefund . '</td>
			<td align="center">' . $fudata['cabang'] . '</td>
		</tr>';
            }
            echo '<tr><td colspan="22">';
            echo createPageNavigations($file = 'ajk_klaim.php?er=reqklaim&re=' . $_REQUEST['re'] . '&nama_er=' . $_REQUEST['nama_er'] . '&id_er=' . $_REQUEST['id_er'] . '', $total = $totalRows, $psDeh = 10, $anchor = '', $perPage = 25);
            echo '<b>Total Data Peserta: <u>' . $totalRows . '</u></b></td></tr>';
            echo '</table>';
        } ;
        break;

    default:
        echo '<table border="0" cellpadding="5" cellspacing="1" width="100%"><tr><th width="100%" align="left">Modul Klaim</font></th></tr></table>';
        echo '<fieldset style="padding: 2">
	<legend align="center">S e a r c h</legend>
	<table border="0" width="100%" cellpadding="1" cellspacing="1">
	<form method="post" action="">
	<tr><td width="25%" align="right">Nama Perusahaan</td>
	    <td width="30%">: ';
        $quer2 = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="' . $q['id_cost'] . '"'));
        echo $quer2['name'];
        echo '</td></tr>
			<tr><td align="right">Nama Produk</td>
				<td>: ';
        if ($q['id_polis'] != "") {
            $quer1 = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="' . $q['id_polis'] . '"'));
            echo $quer1['nmproduk'] . ' (' . $quer1['nopol'] . ')';
            $met_reg = $database->doQuery('SELECT * FROM fu_ajk_regional WHERE id_cost="' . $q['id_cost'] . '"');
            $kolomregional .= '<tr><td align="right">Regional</td>
						  <td>: <select id="id_cost" name="cat" onchange="reload(this.form)">
							  <option value="">--- Pilih ---</option>';
            $met_cost = $database->doQuery('SELECT * FROM fu_ajk_regional WHERE id_cost="' . $q['id_cost'] . '" ORDER BY name ASC');
            while ($met_cost_ = mysql_fetch_array($met_cost)) {
                $kolomregional .= '<option value="' . $met_cost_['name'] . '"' . _selected($_REQUEST['cat'], $met_cost_['name']) . '>' . $met_cost_['name'] . '</option>';
            }
            $kolomregional .= '</select></td></tr>';
        } else {
            $quer1 = $database->doQuery('SELECT * FROM fu_ajk_polis WHERE id_cost="' . $q['id_cost'] . '"');
            echo '<select id="id_cost" name="cat">
		<option value="">--- Pilih ---</option>';
            while ($quer1_ = mysql_fetch_array($quer1)) {
                echo '<option value="' . $quer1_['id'] . '"' . _selected($_REQUEST['cat'], $quer1_['id']) . '>' . $quer1_['nmproduk'] . '</option>';
            }
            echo '</select>';
            $kolomregional .= '<tr><td align="right">Regional</td>
						  <td>: ' . $q['wilayah'] . '</td></tr>';
        }

        echo '</td></tr>';
        echo $kolomregional;
        echo '<tr><td align="right">Cabang</td>
				<td>: <select id="subcat" name="subcat">
				<option value="">--- Pilih ---</option>';
        if ($q['id_polis'] != "") {
            $cek_regionalnya = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE name="' . $_REQUEST['cat'] . '"'));
            $rreg = $database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id_cost="' . $q['id_cost'] . '" AND id_reg="' . $cek_regionalnya['id'] . '" ORDER BY name ASC');
        } elseif ($q['id_polis'] == "" and $q['level'] != "10") {
            $rreg = $database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id_cost="' . $q['id_cost'] . '" ORDER BY name ASC');
        } else {
            $cek_regionalnya = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE name="' . $q['wilayah'] . '"'));
            $rreg = $database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id_cost="' . $q['id_cost'] . '" AND id_reg="' . $cek_regionalnya['id'] . '" ORDER BY name ASC');
        } while ($freg = mysql_fetch_array($rreg)) {
            echo '<option value="' . $freg['name'] . '"' . _selected($_REQUEST['subcat'], $freg['name']) . '>' . $freg['name'] . '</option>';
        }
        echo '</select></td></tr>
	<tr><td align="right">Nomor DN</td><td>: <input type="text" name="metdn" value="' . $_REQUEST['metdn'] . '"></td></tr>
	<tr><td align="right">Nomor CN</td><td>: <input type="text" name="metcn" value="' . $_REQUEST['metcn'] . '"></td></tr>
	<tr><td align="right">Nama</td><td>: <input type="text" name="snama" value="' . $_REQUEST['snama'] . '"></td></tr>
	</td></tr>
	<tr><td width="10%" align="right">Type Claim</td>
		<td width="20%">: <select name="typeclaim"><option value="">---Select Claim---</option>
			<option name="typeclaim" value="Refund">Refund</option>
			<option name="typeclaim" value="Death">Meninggal</option>
			<option name="typeclaim" value="Batal">Batal</option>
			</select></td></tr>
	<tr><td align="center" colspan="2"><input type="submit" name="button" value="Cari" class="button"></td></tr>
	</form></table></fieldset>'; ;
        echo '<table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#E2E2E2">
	<tr><th width="1%" rowspan="2">No</th>
		<th width="3%" rowspan="2">SPAJ</th>
		<th width="5%" rowspan="2">No. DN</th>
		<th width="5%" rowspan="2">No. CN</th>
		<th rowspan="2">Nama</th>
		<th rowspan="2">Tgl Lahir</th>
		<th rowspan="2">Usia</th>
		<th width="20%" colspan="4">Status Kredit</th>
		<th width="1%" rowspan="2">Premi</th>
		<th colspan="2" width="10%" >Biaya</th>
		<th width="1%" rowspan="2">Total Premi</th>
		<th colspan="7">Klaim</th>
		<th rowspan="2">Cabang</th>
		<th rowspan="2">Regional</th>
	</tr>
	<tr><th>Kredit Awal</th>
		<th>Jumlah</th>
		<th>Tenor</th>
		<th>Kredit Akhir</th>
		<th>Adm</th>
		<th>Ext. Premi</th>
		<th>Claim</th>
		<th>MA-j</th>
		<th>MA-s</th>
		<th>Jumlah</th>
		<th>Tanggal</th>
		<th>Status</th>
		<th>Tgl Bayar</th>
	</tr>';
        if ($_REQUEST['eRcab']) {
            $dua = 'AND id_cabang LIKE "%' . $_REQUEST['eRcab'] . '%"';
        }
        if ($_REQUEST['eRreg']) {
            $tiga = 'AND id_regional LIKE "%' . $_REQUEST['eRreg'] . '%"';
        }
        if ($_REQUEST['typeclaim']) {
            $lima = 'AND type_claim LIKE "%' . $_REQUEST['typeclaim'] . '%"';
        }
        if ($_REQUEST['id_cn']) {
            $enam = 'AND id_cn LIKE "%' . $_REQUEST['id_cn'] . '%"';
        }
        if ($_REQUEST['id_dn']) {
            $tujuh = 'AND id_dn LIKE "%' . $_REQUEST['id_dn'] . '%"';
        }

        if ($_REQUEST['x']) {
            $m = ($_REQUEST['x'] - 1) * 25;
        } else {
            $m = 0;
        }
        $data = $database->doQuery('SELECT * FROM fu_ajk_cn WHERE id!="" AND id_cn!="" AND id_cost="' . $q['id_cost'] . '" AND id_nopol="' . $q['id_polis'] . '" ' . $satu . ' ' . $dua . ' ' . $tiga . ' ' . $empat . ' ' . $lima . ' ' . $enam . ' ' . $tujuh . ' AND del is null ORDER BY id DESC, input_by DESC LIMIT ' . $m . ' , 25');
        $totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_cn WHERE id != "" AND id_cn!="" AND id_cost="' . $q['id_cost'] . '" AND id_nopol="' . $q['id_polis'] . '" ' . $satu . ' ' . $dua . ' ' . $tiga . ' ' . $empat . ' ' . $lima . ' ' . $enam . ' ' . $tujuh . ' AND del is null '));
        $totalRows = $totalRows[0];
        $_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
        while ($fudata = mysql_fetch_array($data)) {
            $met_dn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn WHERE id="' . $fudata['id_dn'] . '"'));
            $met_peserta = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_cost="' . $fudata['id_cost'] . '" AND id_polis="' . $fudata['id_nopol'] . '" AND id_klaim="' . $fudata['id'] . '"'));
            // MA-J//
            $awal = explode("-", $met_peserta['kredit_tgl']);
            $hari = $awal[2];
            $bulan = $awal[1];
            $tahun = $awal[0];
            $akhir = explode("-", $fudata['tgl_claim']);
            $hari2 = $akhir[2];
            $bulan2 = $akhir[1];
            $tahun2 = $akhir[0];
            $jhari = (mktime(0, 0, 0, $bulan2, $hari2, $tahun2) - mktime(0, 0, 0, $bulan, $hari, $tahun)) / 86400;
            $sisahr = floor($jhari);
            $sisabulan = ceil($sisahr / 30.4375);
            // MA-J//
            // MA-S//
            $masisa = $met_peserta['kredit_tenor'] - $sisabulan;
            // MA-S//
            if ($fudata['type_claim'] == "Death") {
                $met_cn = '<a href="aajk_report.php?er=_erKlaim&idC=' . $fudata['id'] . '" target="_blank">' . substr($fudata['id_cn'], 3) . '</a>';
            } else {
                $met_cn = '<a href="aajk_report.php?er=_erBatal&idC=' . $fudata['id'] . '" target="_blank">' . substr($fudata['id_cn'], 3) . '</a>';
            }

            if ($fudata['type_claim'] == "Death") {
                $type_klaimnya = "Meninggal";
            } else {
                $type_klaimnya = $fudata['type_claim'];
            }
            if (($no % 2) == 1) {
                $objlass = 'tbl-odd';
            } else {
                $objlass = 'tbl-even';
            }
            echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		  <td align="center" valign="top">' . (++$no + ($pageNow - 1) * 25) . '</td>
		  <td>' . $peserta['spaj'] . '</td>
		  <td><a href="aajk_report.php?er=_kwipeserta&idn=' . $fudata['id_dn'] . '" target="_blank">' . substr($met_dn['dn_kode'], 3) . '</a></td>
		  <td>' . $met_cn . '</td>
		  <td>' . $met_peserta['nama'] . '</td>
		  <td align="center">' . $met_peserta['tgl_lahir'] . '</td>
		  <td align="center">' . $met_peserta['usia'] . '</td>
		  <td align="center">' . _convertDate($met_peserta['kredit_tgl']) . '</td>
		  <td align="right">' . duit($met_peserta['kredit_jumlah']) . '</td>
		  <td align="center">' . $met_peserta['kredit_tenor'] . '</td>
		  <td align="center">' . $met_peserta['kredit_akhir'] . '</td>
		  <td align="right">' . duit($met_peserta['premi']) . '</td>
		  <td align="right">' . duit($met_peserta['biaya_adm']) . '</td>
		  <td align="right">' . duit($met_peserta['ext_premi']) . '</td>
		  <td align="right">' . duit($met_peserta['totalpremi']) . '</td>
		  <td align="center">' . $type_klaimnya . '</td>
		  <td align="center">' . $sisabulan . '</td>
		  <td align="center">' . $masisa . '</td>
		  <td align="right"><b>' . duit($fudata['total_claim']) . '</b></td>
		  <td align="center"><b>' . _convertDate($fudata['tgl_claim']) . '</b></td>
		  <td align="center"><b>' . $fudata['confirm_claim'] . '</b></td>
		  <td align="center">' . _convertDate($fudata['tgl_byr_claim']) . '</td>
		  <td align="center">' . $fudata['id_cabang'] . '</td>
		  <td align="center">' . $fudata['id_regional'] . '</td>
		  </tr>';
        }
        echo '<tr><td colspan="22">';
        echo createPageNavigations($file = 'ajk_klaim.php?subcat=' . $_REQUEST['subcat'] . '&eRcab=' . $_REQUEST['eRcab'] . '&eRreg=' . $_REQUEST['eRreg'] . '&cat=' . $_REQUEST['cat'] . '&typeclaim=' . $_REQUEST['typeclaim'] . '&', $total = $totalRows, $psDeh = 10, $anchor = '', $perPage = 25);
        echo '<b>Total Data CN: <u>' . $totalRows . '</u></b></td></tr>';
        echo '</table>';
} // switch

?>
<SCRIPT language=JavaScript>
function reload(form)
{
	var val=form.cat.options[form.cat.options.selectedIndex].value;
	self.location='ajk_klaim.php?cat=' + val;
}
</script>

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
