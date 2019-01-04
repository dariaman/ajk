<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("../includes/functions.php");
connect();

if (isset($_SESSION['nm_user'])) {
    $q = mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="' . $_SESSION['nm_user'] . '"'));
    $qsescost = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="' . $q['id_cost'] . '"'));
}
switch ($_REQUEST['r']) {
    case "cancell":
        $cha = $database->doQuery('DELETE FROM fu_ajk_peserta_tempf WHERE namafile="' . $_REQUEST['fileclient'] . '"');
        header("location:ajk_uploader_spak.php"); ;
        break;

    case "deldata":
        $met = $database->doQuery('DELETE FROM fu_ajk_peserta_tempf WHERE id_cost="' . $_REQUEST['id_cost'] . '" AND
																  id_polis="' . $_REQUEST['id_polis'] . '" AND
																  namafile="' . $_REQUEST['namafile'] . '" AND
																  no_urut="' . $_REQUEST['no_urut'] . '" AND
																  spaj="' . $_REQUEST['spaj'] . '" AND
																  nama="' . $_REQUEST['nama'] . '" AND
																  kredit_jumlah="' . $_REQUEST['kredit_jumlah'] . '"');
        if ($_REQUEST['cl'] == "claim") {
            header("location:ajk_uploader_spak.php?r=viewallclaim");
        }else {
            header("location:ajk_uploader_spak.php?r=viewall");
        } ;
        break;

    case "approve":
        // echo('SELECT * FROM fu_ajk_costumer WHERE name="'.$qsescost['name'].'"');
        // if ($_REQUEST['val']=="pclaim")
        if (!$_REQUEST['nama']) {
            echo '<center><font color=red><blink>Tidak ada data yang di pilih, silahkan ceklist data yang akan di Approve. !</blink></font><br/>
	  <a href="ajk_uploader_spak.php?r=viewall">Kembali Ke Halaman Approve Peserta</a></center>';
        } else {
            foreach($_REQUEST['nama'] as $k => $val) {
                $vall = explode("-met-", $val); //EXPLODE DATA BERDASARKAN CHEKLIST//
                $r = $database->doQuery('SELECT * FROM fu_ajk_peserta_tempf WHERE id_cost="' . $vall[0] . '" AND id_polis="' . $vall[1] . '" AND nama="' . $vall[2] . '" AND tgl_lahir="' . $vall[3] . '" AND kredit_jumlah="' . $vall[4] . '" AND status_aktif="Upload"');
                while ($rr = mysql_fetch_array($r)) {
                    // BIAYA POLIS ADMIN
                    $admpolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id_cost="' . $rr['id_cost'] . '"'));

                    $tgl_akhir_kredit = date('Y-m-d', strtotime($rr['kredit_tgl'] . "+" . $rr['kredit_tenor'] . " Month" . "-" . $admpolis['day_kredit'] . " day")); //KREDIT AKHIR
                    $umur = ceil(((strtotime($rr['kredit_tgl']) - strtotime($rr['tgl_lahir'])) / (60 * 60 * 24 * 365.2425))); // FORMULA USIA

                    if ($admpolis['typeproduk']=="SPK") {
                    	if ($admpolis['mpptype']=="Y") {
                    		$spkRate = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$rr['id_cost'].'" AND id_polis="'.$rr['id'].'" AND tenor="'.$rr['kredit_tenor'].'" AND "'.$rr['mppbln'].'" BETWEEN mpp_s AND mpp_e AND status="baru" AND del IS NULL'));
                    	}else{
                    		$mettenornya = $rr['kredit_tenor'];
                    		$cekrate = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$rr['id_cost'].'" AND id_polis="'.$rr['id'].'" AND usia="' . $umur . '" AND tenor="' . $mettenornya . '" AND status="baru" AND del IS NULL')); // RATE PREMI
                    	}
                    }

                    $premi = $rr['kredit_jumlah'] * $cekrate['rate'] / 1000;
                    $diskonpremi = $premi * $admpolis['discount'] / 100; //diskon premi
                    $tpremi = $premi - $diskonpremi; //totalpremi

                    $mettotal = $tpremi + $extrapremi + $admpolis['adminfee']; //TOTAL

                    $formattgl = explode("/", $rr['kredit_tgl']);
                    $formattgl1 = $formattgl[2] . '-' . $formattgl[1] . '-' . $formattgl[0]; // SET FORMAT TANGGAL
                    $cekpesertaID = mysql_fetch_array($database->doQuery('SELECT id FROM fu_ajk_peserta ORDER BY id DESC')); // SET ID PESERTA
                    $idnya = 100000000 + $cekpesertaID['id'] + 1;
                    $idnya2 = substr($idnya, 1); // SET ID PESERTA
                    $mameto = $database->doQuery('INSERT INTO fu_ajk_peserta SET id_cost="' . $rr['id_cost'] . '",
															 id_polis="' . $rr['id_polis'] . '",
															 namafile="' . $rr['namafile'] . '",
															 no_urut="' . $rr['no_urut'] . '",
															 spaj="' . $rr['spaj'] . '",
															 id_peserta="' . $idnya . '",
															 nama_mitra="' . $rr['nama_mitra'] . '",
															 nama="' . $rr['nama'] . '",
															 gender="' . $rr['gender'] . '",
															 tgl_lahir="' . $rr['tgl_lahir'] . '",
															 usia="' . $umur . '",
															 kredit_tgl="' . $rr['kredit_tgl'] . '",
															 kredit_jumlah="' . $rr['kredit_jumlah'] . '",
															 kredit_tenor="' . $rr['kredit_tenor'] . '",
															 kredit_akhir="' . $tgl_akhir_kredit . '",
															 ratebank="'.$cekrate['rate'].'",
															 premi="' . $premi . '",
															 disc_premi="' . $diskonpremi . '",
															 bunga="",
															 biaya_adm="' . $admpolis['adminfee'] . '",
															 ext_premi="' . $data12 . '",
															 totalpremi="' . $mettotal . '",
															 badant="",
															 badanb="",
															 status_medik="NM",
															 status_bayar="0",
															 status_aktif="Approve",
															 regional="' . $rr['regional'] . '",
															 area="' . $rr['area'] . '",
															 cabang="' . $rr['cabang'] . '",
															 input_by ="' . $rr['input_by'] . '- ' . $q['nm_user'] . '",
															 input_time ="' . $rr['input_time'] . '"');
                }
                $metdel = $database->doQuery('DELETE FROM fu_ajk_peserta_tempf WHERE id_cost="' . $vall[0] . '" AND id_polis="' . $vall[1] . '" AND nama="' . $vall[2] . '" AND tgl_lahir="' . $vall[3] . '" AND kredit_jumlah="' . $vall[4] . '" AND status_aktif ="Upload"');
            }

            $pecahtgl = explode(" ", $futgl);
            $pecahlagi = explode("-", $pecahtgl[0]);
            $tglnya = $pecahlagi[2] . '-' . $pecahlagi[1] . '-' . $pecahlagi[0] . ' ' . $pecahtgl[1]; //PECAH TANGGAL
            // $Rmail = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="'.$vall[0].'" AND  wilayah="'.$q['wilayah'].'" AND email !=""');
            // echo('SELECT * FROM pengguna WHERE id_cost="'.$vall[0].'" AND  wilayah="'.$q['wilayah'].'" AND email !=""');
            // while ($eRmail = mysql_fetch_array($Rmail)) {	$metMail .=$eRmail['email'].', ';	}
            // echo $metMail.'<br /><br />';
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
        	$mail->debug = 1;
        	$mail->SMTPSecure = "ssl";
            $mail->SetFrom ($q['email'], $q['nm_lengkap']); //From address of the mail
            $mail->Subject = "AJKOnline - APPROVE PESERTA BARU AJK ONLINE"; //Subject od your mail
            // EMAIL PENERIMA KANTOR U/W
            $mailsupervisorajk = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="" AND status="10"');
            while ($_mailsupervisorajk = mysql_fetch_array($mailsupervisorajk)) {
                $mail->AddAddress($_mailsupervisorajk['email'], $_mailsupervisorajk['nm_lengkap']); //To address who will receive this email
            }
            // EMAIL PENERIMA KANTOR U/W
            // EMAIL PENERIMA CLIENT
            $mailsupervisorclient = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="' . $vall[0] . '" AND  wilayah="' . $q['wilayah'] . '" AND email !="" AND del IS NULL');
            while ($_mailsupervisorclient = mysql_fetch_array($mailsupervisorclient)) {
                $mail->AddAddress($_mailsupervisorclient['email'], $_mailsupervisorclient['nm_lengkap']); //To address who will receive this email
            }
            // EMAIL PENERIMA CLIENT
            //$mail->AddBCC("adonai.notif@gmail.com");
            $mail->AddBCC("rahmad@adonaits.co.id");
            $mail->MsgHTML('<table><tr><th>Data peserta baru telah di approve oleh <b>' . $_SESSION['nm_user'] . ' selaku Supervisor AJK-Online pada tanggal ' . $futgl . '</tr></table>' . $message); //Put your body of the message you can place html code here
            $send = $mail->Send(); //Send the mails

            echo '<center>Approve oleh <b>' . $_SESSION['nm_user'] . '</b> telah berhasil, segera dibuat pencetakan nomor DN.<br /> <a href="ajk_uploader_spak.php">Kembali Ke Halaman Utama</a></center>';
        } ;
        break;

    case "approveuser":
        $met_appr = $database->doQuery('SELECT * FROM fu_ajk_peserta_tempf WHERE id_cost="' . $_REQUEST['idc'] . '" AND namafile="' . $_REQUEST['nmfile'] . '" AND input_time="' . $_REQUEST['dateupl'] . '"');

        $message .= '<table width="100%" broder="0" cellpadding="3" cellspacing="1">
			 <tr bgcolor="#add8e6"><td width="1%">NO</td>
			 	 <td align="center" width="5%">SPK</td>
			 	 <td align="center">NAMA</td>
			 	 <td align="center" width="1%">P/W</td>
			 	 <td align="center" width="5%">D O B</td>
			 	 <td align="center" width="8%">TGL KREDIT</td>
			 	 <td align="center" width="10%">U P</td>
			 	 <td align="center" width="5%">TENOR</td>
			 	 <td align="center" width="10%">REGIONAL</td>
			 	 <td align="center" width="10%">AREA</td>
			 	 <td align="center" width="10%">CABANG</td>
			 </tr>';
        while ($mamet_appr = mysql_fetch_array($met_appr)) {
            if (($no % 2) == 1) $objlass = 'tbl-odd';
            else $objlass = 'tbl-even';
            $message .= '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
				<td align="center">' . ++$no . '</td>
				<td align="center">' . $mamet_appr['spaj'] . '</td>
				<td>' . $mamet_appr['nama'] . '</td>
				<td align="center">' . $mamet_appr['gender'] . '</td>
				<td align="center">' . _convertDate($mamet_appr['tgl_lahir']) . '</td>
				<td align="center">' . _convertDate($mamet_appr['kredit_tgl']) . '</td>
				<td align="right">' . duit($mamet_appr['kredit_jumlah']) . '</td>
				<td align="center">' . $mamet_appr['kredit_tenor'] . '</td>
				<td>' . $mamet_appr['regional'] . '</td>
				<td>' . $mamet_appr['area'] . '</td>
				<td>' . $mamet_appr['cabang'] . '</td>
	  		</tr>';
        }
        $message .= '</table>';
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
    	$mail->debug = 1;
    	$mail->SMTPSecure = "ssl";
        $mail->SetFrom ($q['email'], $q['nm_lengkap']); //From address of the mail
        $mail->Subject = "AJKOnline - UPLOAD DATA PESERTA SPK"; //Subject od your mail
        // EMAIL PENERIMA  KANTOR U/W
        $mailsupervisorajk = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="" AND status="10" AND supervisor="1" AND del IS NULL');
        while ($_mailsupervisorajk = mysql_fetch_array($mailsupervisorajk)) {
            $mail->AddAddress($_mailsupervisorajk['email'], $_mailsupervisorajk['nm_lengkap']); //To address who will receive this email
        }
        // EMAIL PENERIMA  KANTOR U/W
        // EMAIL PENERIMA  KANTOR U/W
        $mailclient = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="' . $_REQUEST['idc'] . '" AND status="" AND level="2" AND del IS NULL');
        while ($_mailclient = mysql_fetch_array($mailclient)) {
            $mail->AddAddress($_mailclient['email'], $_mailclient['nm_lengkap']); //To address who will receive this email
        }
        // EMAIL PENERIMA  KANTOR U/W
        //$mail->AddBCC("adonai.notif@gmail.com");
        $mail->AddBCC("rahmad@adonaits.co.id");
        // $mail->AddAddress($mailsupervisorajk['email'], $mailsupervisorajk['nm_lengkap']); //To address who will receive this email
        // $mail->AddCC($approvemail);
        $mail->MsgHTML('<table><tr><th>Data peserta SPK telah diinput oleh <b>' . $_SESSION['nm_user'] . ' selaku staff AJK-Online pada tanggal ' . $futgl . '</tr></table>' . $message); //Put your body of the message you can place html code here
        $send = $mail->Send(); //Send the mails
        // echo $mail;
        echo '<center>Data Peserta SPK sudah diinput oleh <b>' . $_SESSION['nm_user'] . '</b>, tunggu konfirmasi selanjutnya dari Supervisor untuk pencetakan nomor DN.<br />
	  <a href="ajk_uploader_spak.php">Kembali Ke Halaman Utama</a></center>'; ;
        break;

    case "fuparsing":
        $fu = mysql_fetch_array($database->doQuery('SELECT fu_ajk_costumer.name, fu_ajk_costumer.id FROM fu_ajk_costumer WHERE id="' . $_REQUEST['cat'] . '"'));
        $fufile = mysql_fetch_array($database->doQuery('SELECT fu_ajk_peserta.namafile FROM fu_ajk_peserta WHERE id_cost="' . $_REQUEST['cat'] . '"'));
        $fupolis = mysql_fetch_array($database->doQuery('SELECT fu_ajk_polis.nopol, fu_ajk_polis.id FROM fu_ajk_polis WHERE id="' . $_REQUEST['subcat'] . '"'));

        $_REQUEST['subcat'] = $_POST['subcat'];
        if (!$_REQUEST['subcat']) $error .= 'Silahkan pilih nomor polis<br />.';
        $_REQUEST['bataskolom'] = $_POST['bataskolom'];
        if (!$_REQUEST['bataskolom']) $error .= 'Silahkan tentukan batas kolom file<br />.';
        if (!$_FILES['userfile']['tmp_name']) $error .= 'Silahkan upload file excel anda<br />.';
        $allowedExtensions = array("xls", "xlsx", "csv");
        foreach ($_FILES as $file) {
            if ($file['tmp_name'] > '') {
                if (!in_array(end(explode(".", strtolower($file['name']))), $allowedExtensions)) {
                    die('<center><font color=red>' . $file['name'] . ' <br /><blink>File extension tidak diperbolehkan selain excel!</blink></font><br/>' . '<a href="ajk_uploader_spak.php">' . '&lt;&lt Go Back</a></center>');
                }
            }
        }
        if ($error) {
            echo '<blink><center><font color=red>' . $error . '</font></blink><a href="ajk_uploader_spak.php">' . '&lt;&lt Go Back</a></center>';
        }else {
            echo '<form method="post" action="ajk_uploader.php?r=approveuser" onload ="onbeforeunload">
	  <table border="0" width="100%" cellpadding="1" cellspacing="1" style="border: solid 3px #DEDEDE"  bgcolor="#bde0e6">
		<tr><td colspan="2"><input type="hidden" name="idcostumer" value="' . $fu['id'] . '">Costumer</td><td colspan="24">: <b>' . $fu['name'] . '</b></td></tr>
		<tr><td colspan="2"><input type="hidden" name="idpolis" value="' . $fupolis['id'] . '">Policy Number</td><td colspan="24">: <b>' . $fupolis['nopol'] . '</b></td></tr>
		<tr><td colspan="2"><input type="hidden" name="bataskolom" value="' . $_REQUEST['bataskolom'] . '"><input type="hidden" name="namafileexl" value="' . $_FILES['userfile']['name'] . '">File Name</td><td colspan="24">: <b>' . $_FILES['userfile']['name'] . '</b></td></tr>
		<tr><th width="1%" rowspan="2">No</th>
			<th width="5%" rowspan="2">No SPK</th>
			<th rowspan="2">Nama Tertanggung</th>
			<th width="5%" colspan="3">Tanggal Lahir</th>
			<th width="5%" rowspan="2">Uang Asuransi</th>
			<th width="5%" colspan="3">Mulai Asuransi</th>
			<th width="5%" rowspan="2">Tenor</th>
			<th width="5%" rowspan="2">Ext.Premi</th>
			<th width="5%" rowspan="2">Regional</th>
			<th width="5%" rowspan="2">Area</th>
			<th width="5%" rowspan="2">Cabang</th>
		</tr>

		<tr><th>Hari</th><th>Bulan</th><th>Tahun</th><th>Hari</th><th>Bulan</th><th>Tahun</th></tr>';
            $data = new Spreadsheet_Excel_Reader($_FILES['userfile']['tmp_name']);
            $baris = $data->rowcount($sheet_index = 0); //MEMBACA JUMLAH BARIS DATA EXCEL

            for ($i = 10; $i <= $_REQUEST['bataskolom']; $i++) {
                $data1 = $data->val($i, 1); //no
                $data2 = $data->val($i, 2); //S P K
                $data3 = $data->val($i, 3); //NAMA TERTANGGUNG
                $data4 = $data->val($i, 4); //TANGGAL LAHIR (TGL)
                $data5 = $data->val($i, 5); //TANGGAL LAHIR (BLN)
                $data6 = $data->val($i, 6); //TANGGAL LAHIR (THN)
                $data7 = $data->val($i, 7); //UANG ASURANSI
                $data8 = $data->val($i, 8); //MULAI ASURANSI (TGL)
                $data9 = $data->val($i, 9); //MULAI ASURANSI (BLN)
                $data10 = $data->val($i, 10); //MULAI ASURANSI (THN)
                $data11 = $data->val($i, 11); //MASA ASURANSI
                $data12 = $data->val($i, 12); //EXT. PREMI
                $data13 = $data->val($i, 13); //REGIOALLL
                $data14 = $data->val($i, 14); //AREA
                $data15 = $data->val($i, 15); //CABANG

                // VALIDASI DATA UPLOAD//
                if ($data2 == "") {
                    $error = '<font color="red">error</font>';
                    $dataexcel2 = $error;
                } else {
                    $dataexcel2 = $data2;
                }
                if ($data3 == "") {
                    $error = '<font color="red">error</font>';
                    $dataexcel3 = $error;
                } else {
                    $dataexcel3 = $data3;
                }
                if ($data4 == "") {
                    $error = '<font color="red">error</font>';
                    $dataexcel4 = $error;
                } else {
                    $dataexcel4 = $data4;
                }
                if ($data5 == "") {
                    $error = '<font color="red">error</font>';
                    $dataexcel5 = $error;
                } else {
                    $dataexcel5 = $data5;
                }
                if ($data6 == "") {
                    $error = '<font color="red">error</font>';
                    $dataexcel6 = $error;
                } else {
                    $dataexcel6 = $data6;
                }
                if ($data7 == "") {
                    $error = '<font color="red">error</font>';
                    $dataexcel7 = $error;
                } else {
                    $dataexcel7 = $data7;
                }
                if ($data8 == "") {
                    $error = '<font color="red">error</font>';
                    $dataexcel8 = $error;
                } else {
                    $dataexcel8 = $data8;
                }
                if ($data9 == "") {
                    $error = '<font color="red">error</font>';
                    $dataexcel9 = $error;
                } else {
                    $dataexcel9 = $data9;
                }
                if ($data10 == "") {
                    $error = '<font color="red">error</font>';
                    $dataexcel10 = $error;
                } else {
                    $dataexcel10 = $data10;
                }
                if ($data11 == "") {
                    $error = '<font color="red">error</font>';
                    $dataexcel11 = $error;
                } else {
                    $dataexcel11 = $data11;
                }
                if ($data13 == "") {
                    $error = '<font color="red">error</font>';
                    $dataexcel13 = $error;
                } else {
                    $dataexcel13 = $data13;
                }
                if ($data14 == "") {
                    $error = '<font color="red">error</font>';
                    $dataexcel14 = $error;
                } else {
                    $dataexcel14 = $data14;
                }
                if ($data15 == "") {
                    $error = '<font color="red">error</font>';
                    $dataexcel15 = $error;
                } else {
                    $dataexcel15 = $data15;
                }

                if (!is_numeric($data4)) {
                    $error = '<font color="red">error</font>';
                    $dataexcel4 = $error;
                } else {
                    $dataexcel4 = $data4;
                } //VALIDASI HARI
                if (strlen($data4 > 31)) {
                    $error = '<font color="red">error</font>';
                    $dataexcel4 = $error;
                } else {
                    $dataexcel4 = $data4;
                } //VALIDASI HARI
                if (!is_numeric($data5)) {
                    $error = '<font color="red">error</font>';
                    $dataexcel5 = $error;
                } else {
                    $dataexcel5 = $data5;
                } //VALIDASI BULAN
                if (strlen($data5 > 12)) {
                    $error = '<font color="red">error</font>';
                    $dataexcel5 = $error;
                } else {
                    $dataexcel5 = $data5;
                } //VALIDASI BULAN
                if (!is_numeric($data6)) {
                    $error = '<font color="red">error</font>';
                    $dataexcel6 = $error;
                } else {
                    $dataexcel6 = $data6;
                } //VALIDASI TAHUN
                if (strlen($data6 > $dateY)) {
                    $error = '<font color="red">error</font>';
                    $dataexcel6 = $error;
                } else {
                    $dataexcel6 = $data6;
                } //VALIDASI TAHUN
                $titikpos = strpos($data7, ".");
                if ($titikpos) {
                    $error = '<font color="red">error</font>';
                    $dataexcel7 = $error;
                } else {
                    $dataexcel7 = $data7;
                }

                $titikpos = strpos($data7, ",");
                if ($titikpos) {
                    $error = '<font color="red">error</font>';
                    $dataexcel7 = $error;
                } else {
                    $dataexcel7 = $data7;
                }

                if (!is_numeric($data8)) {
                    $error = '<font color="red">error</font>';
                    $dataexcel8 = $error;
                } else {
                    $dataexcel8 = $data8;
                } //VALIDASI HARI
                if (strlen($data8 > 31)) {
                    $error = '<font color="red">error</font>';
                    $dataexcel8 = $error;
                } else {
                    $dataexcel8 = $data8;
                } //VALIDASI HARI
                if (!is_numeric($data9)) {
                    $error = '<font color="red">error</font>';
                    $dataexcel9 = $error;
                } else {
                    $dataexcel9 = $data9;
                } //VALIDASI BULAN
                if (strlen($data9 > 12)) {
                    $error = '<font color="red">error</font>';
                    $dataexcel9 = $error;
                } else {
                    $dataexcel9 = $data9;
                } //VALIDASI BULAN
                if (!is_numeric($data10)) {
                    $error = '<font color="red">error</font>';
                    $dataexcel10 = $error;
                } else {
                    $dataexcel10 = $data10;
                } //VALIDASI TAHUN
                if (strlen($data10 > $dateY)) {
                    $error = '<font color="red">error</font>';
                    $dataexcel10 = $error;
                } else {
                    $dataexcel10 = $data10;
                } //VALIDASI TAHUN
                // FORMAT TERNOR DLM BULAN DIBAGI 12
                $_mettenor = $data11 / 12;
                $cekratepolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="' . $_REQUEST['cat'] . '" AND id_polis="' . $fupolis['id'] . '" AND tenor="' . $_mettenor . '" AND status="baru" AND del IS NULL')); //VALIDASI CEK TENOR DENGAN SETTING RATE POLIS
                if ($_mettenor != $cekratepolis['tenor']) {
                    $error = '<font color="red">error</font>';
                    $dataexcel11 = $error;
                } else {
                    $dataexcel11 = $data11;
                } //VALIDASI CEK TENOR DENGAN SETTING RATE POLIS
                $cekdatareg = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE id_cost="' . $fu['id'] . '" AND name="' . $data13 . '"')); //VALIDASI REGIONAL
                if ($data13 != $cekdatareg['name']) {
                    $error = '<font color="red">error</font>';
                    $dataexcel13 = $error;
                } else {
                    $dataexcel13 = $data13;
                } //VALIDASI REGIONAL
                $cekdataarea = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_area WHERE id_cost="' . $fu['id'] . '" AND name="' . $data14 . '"')); //VALIDASI AREA
                if ($data14 != $cekdataarea['name']) {
                    $error = '<font color="red">error</font>';
                    $dataexcel14 = $error;
                } else {
                    $dataexcel14 = $data14;
                } //VALIDASI AREA
                $cekdatacab = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id_cost="' . $fu['id'] . '" AND name="' . $data15 . '"')); //VALIDASI CABANG
                if ($data15 != $cekdatacab['name']) {
                    $error = '<font color="red">error</font>';
                    $dataexcel15 = $error;
                } else {
                    $dataexcel15 = $data15;
                } //VALIDASI CABANG
                // VALIDASI DATA UPLOAD//
                /*
	$cekdataspk = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak WHERE id_cost="'.$fu['id'].'" AND spak="'.$data2.'" AND status="Aktif"'));
	if ($cekdataspk['spak'] != $data2) {$error ='<font color="red">error</font>'; $dataexcel2=$error;}else{ $dataexcel2=$data2;}
	$cekdataspknama = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_form WHERE idcost="'.$fu['id'].'" AND idspk="'.$cekdataspk['id'].'"'));
	if ($cekdataspknama['nama'] != $data3) {$error ='<font color="red">error</font>'; $dataexcel3=$error;}else{ $dataexcel3=$data3;}

	$cekdataspk = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak WHERE id_cost="'.$_REQUEST['cat'].'" AND spak="'.$data2.'" AND status="Proses"'));
	if ($cekdataspk['spak'] != $data2) {$error ='<font color="red">error</font>'; $dataexcel2=$error;}else{ $dataexcel2=$data2;}
*/
                $cekdataspk = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak WHERE id_cost="' . $fu['id'] . '" AND spak="' . $data2 . '" AND status="Aktif"'));
                // $cekdataspknya = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak WHERE id_cost="'.$q['id_cost'].'" AND spak="'.$data2.'" AND status="Aktif"'));
                if ($cekdataspk['spak'] != $data2) {
                    $error = '<font color="red">error</font>';
                    $dataexcel2 = $error;
                } else {
                    $dataexcel2 = $data2;
                }

                $cekdataspknama = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_form WHERE idcost="' . $fu['id'] . '" AND idspk="' . $cekdataspk['id'] . '"'));
                if ($cekdataspknama['nama'] != $data3) {
                    $error = '<font color="red">error</font>';
                    $dataexcel3 = $error;
                } else {
                    $dataexcel3 = $data3;
                }
                // CEK RELASI WILAYAH
                $cekdatawilayah = mysql_fetch_array($database->doQuery('SELECT * FROM v_wilayah WHERE id_cost="' . $fu['id'] . '" AND regional="' . $data13 . '" AND area="' . $data14 . '" AND cabang="' . $data15 . '"'));
                if ($cekdatawilayah['regional'] != $data13) {
                    $error = '<font color="red">error</font>';
                    $dataexcel13 = $error;
                } else {
                    $dataexcel13 = $data13;
                }
                if ($cekdatawilayah['area'] != $data14) {
                    $error = '<font color="red">error</font>';
                    $dataexcel14 = $error;
                } else {
                    $dataexcel14 = $data14;
                }
                if ($cekdatawilayah['cabang'] != $data15) {
                    $error = '<font color="red">error</font>';
                    $dataexcel15 = $error;
                } else {
                    $dataexcel15 = $data15;
                }

                if (($no % 2) == 1) $objlass = 'tbl-odd';
                else $objlass = 'tbl-even';
                echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		<td align="center">' . $data1 . '</td>
		<td align="center">' . $dataexcel2 . '</td>
		<td>' . strtoupper($dataexcel3) . '</td>
		<td align="center">' . $dataexcel4 . '</td>
		<td align="center">' . $dataexcel5 . '</td>
		<td align="center">' . $dataexcel6 . '</td>
		<td>' . $dataexcel7 . '</td>
		<td align="center">' . $dataexcel8 . '</td>
		<td align="center">' . $dataexcel9 . '</td>
		<td align="center">' . $dataexcel10 . '</td>
		<td align="center">' . $dataexcel11 . '</td>
		<td align="center">' . $dataexcel12 . '</td>
		<td align="right">' . $dataexcel13 . '</td>
		<td align="right">' . $dataexcel14 . '</td>
		<td align="center">' . $dataexcel15 . '</td>
	</tr>';
                // $exl = $database->doQuery('INSERT IGNORE fu_ajk_peserta_tempf SET id_cost="'.$fu['id'].'",
                if ($data4 < 9) {
                    $data4_ = '0' . $data4;
                } else {
                    $data4_ = $data4;
                }
                if ($data5 < 9) {
                    $data5_ = '0' . $data5;
                } else {
                    $data5_ = $data5;
                }
                $datatgllahirnya = $data6 . '-' . $data5_ . '-' . $data4_;

                if ($data8 < 9) {
                    $data8_ = '0' . $data8;
                } else {
                    $data8_ = $data8;
                }
                if ($data9 < 9) {
                    $data9_ = '0' . $data9;
                } else {
                    $data9_ = $data9;
                }
                $datatglkreditnya = $data10 . '-' . $data9_ . '-' . $data8_;

                $met = $database->doQuery('INSERT IGNORE fu_ajk_peserta_tempf SET id_cost="' . $fu['id'] . '",
																  id_polis="' . $fupolis['id'] . '",
																  namafile="' . $_FILES['userfile']['name'] . '",
																  no_urut="' . $data1 . '",
																  spaj="' . $data2 . '",
																  type_data="SPK",
																  nama_mitra="",
																  nama="' . $data3 . '",
																  gender="",
																  tgl_lahir="' . $datatgllahirnya . '",
																  usia="",
																  kredit_tgl="' . $datatglkreditnya . '",
																  kredit_jumlah="' . $data7 . '",
																  kredit_tenor="' . $data11 . '",
																  kredit_akhir="",
																  premi="",
																  disc_premi="",
																  bunga="",
																  biaya_adm="",
																  ext_premi="' . $data12 . '",
																  totalpremi="",
																  badant="",
																  badanb="",
																  status_medik="",
																  status_bayar="0",
																  status_aktif="Upload",
																  regional="' . $data13 . '",
																  area="' . $data14 . '",
																  cabang="' . $data15 . '",
																  input_by ="' . $_SESSION['nm_user'] . '",
															      input_time ="' . $futgl . '"');
            }
            if ($error) {
                echo '<tr><td colspan="27" align="center"><font color="red"><blink>Silahkan lengkapi kolom yang error !!!</blink><br /><a href="ajk_uploader_spak.php?r=cancell&fileclient=' . $_FILES['userfile']['name'] . '">Back</a></font></td></tr>';
            }else {
                echo '<tr><td colspan="27" align="center"><a title="Approve data upload" href="ajk_uploader_spak.php?r=approveuser&nmfile=' . $_FILES['userfile']['name'] . '&dateupl=' . $futgl . '&idc=' . $fu['id'] . '" onClick="if(confirm(\'Apakah anda sudah yakin dengan semua data peserta ini ?\')){return true;}{return false;}"><img src="image/save.png" border="0" width="50"></a>
	 					   &nbsp; &nbsp; <a title="Batalkan data upload" href="ajk_uploader_spak.php?r=cancell&fileclient=' . $_FILES['userfile']['name'] . '"><img src="image/deleted.png" border="0" width="50"></a></td></tr>';
            }
            echo '</table></form>';
        } ;
        break;

    case "viewall":
        if ($_REQUEST['rx'] == "pending") {
            $metpending = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta_tempf WHERE no_urut="' . $_REQUEST['no_urut'] . '" AND nama="' . $_REQUEST['nama'] . '" AND tgl_lahir="' . $_REQUEST['tgl_lahir'] . '" AND kredit_tgl="' . $_REQUEST['kredit_tgl'] . '"'));
            $riweuhkreditawal = explode("/", $metpending['kredit_tgl']);
            $cektglkreditawal = $riweuhkreditawal[0] . '-' . $riweuhkreditawal[1] . '-' . $riweuhkreditawal[2]; //KREDIT AWAL EXPLODE

            $riweuhkredit = explode("/", $metpending['kredit_tgl']);
            $cektglkredit = $riweuhkredit[0] . '-' . $riweuhkredit[1] . '-' . $riweuhkredit[2]; //KREDIT AKHIR
            $endkredit2 = date('d/m/Y', strtotime($cektglkredit . "+" . $metpending['kredit_tenor'] . " Month" . - "1" . "Day")); //KREDIT AKHIR
            $vendkredit2 = date('Y-m-d', strtotime($cektglkredit . "+" . $metpending['kredit_tenor'] . " Month" . - "1" . "Day")); //VKREDIT AKHIR

            $cekpolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="' . $metpending['id_polis'] . '"'));
            if ($cekpolis['typeRate'] == "Tunggal") {
                $RTenor = $metpending['kredit_tenor'] / 12;
                $tenortunggal = ceil($RTenor);
                $cekrate = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi_tunggal WHERE id_cost="' . $metpending['id_cost'] . '" AND id_polis="' . $metpending['id_polis'] . '" AND usia="' . $umur . '" AND tenorthn="' . $tenortunggal . '"')); // RATE PREMI
            } else {
                $cekrate = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="' . $metpending['id_cost'] . '" AND id_polis="' . $metpending['id_polis'] . '" AND tenor="' . $metpending['kredit_tenor'] . '" AND status="baru" AND del IS NULL')); // RATE PREMI
            }
            $premi = $metpending['kredit_jumlah'] * $cekrate['rate'] / 1000; // RATE PREMI
            $diskonpremi = $premi * ($cekpolis['discount'] / 100); //diskon premi
            $tpremi = $premi - $diskonpremi; //totalpremi

            $tb = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_badan WHERE sex = "' . $metpending['gender'] . '" AND w_from <= "' . $metpending['badanb'] . '" AND w_to >= "' . $metpending['badanb'] . '" AND h_from <= "' . $metpending['badant'] . '" AND h_to >= "' . $metpending['badant'] . '"'));
            $extrapremi = ($premi * $tb['extrapremi']) / 100;

            $mettotal = $tpremi + $extrapremi + $metpending['biaya_adm'] + $metpending['biaya_refund'];

            $mameto = $database->doQuery('INSERT INTO fu_ajk_peserta SET no_urut="' . $metpending['no_urut'] . '",
														  id_dn="",
														  id_cost="' . $metpending['id_cost'] . '",
														  id_polis="' . $metpending['id_polis'] . '",
														  namafile="' . $metpending['namafile'] . '",
														  spaj="' . $metpending['spaj'] . '",
														  nama="' . $metpending['nama'] . '",
														  gender="' . $metpending['gender'] . '",
														  kartu_type="' . $metpending['kartu_type'] . '",
														  kartu_no="' . $metpending['kartu_no'] . '",
														  kartu_period="' . $metpending['kartu_period'] . '",
														  tgl_lahir="' . $metpending['tgl_lahir'] . '",
														  usia="' . $_REQUEST['u'] . '",
														  kredit_tgl="' . $metpending['kredit_tgl'] . '",
														  vkredit_tgl="' . $cektglkreditawal . '",
														  thn="' . $riweuhkreditawal[2] . '",
														  bln="' . $riweuhkreditawal[1] . '",
														  kredit_jumlah="' . $metpending['kredit_jumlah'] . '",
														  kredit_tenor="' . $metpending['kredit_tenor'] . '",
														  kredit_akhir="' . $endkredit2 . '",
														  vkredit_akhir="' . $vendkredit2 . '",
														  premi="' . $premi . '",
														  bunga="' . $metpending['bunga'] . '",
														  disc_premi="' . $diskonpremi . '",
														  biaya_adm="' . $metpending['biaya_adm'] . '",
														  biaya_refund="' . $metpending['biaya_refund'] . '",
														  ext_premi="' . $extrapremi . '",
														  totalpremi="' . $mettotal . '",
														  badant="' . $metpending['badant'] . '",
														  badanb="' . $metpending['badanb'] . '",
														  statement1="' . $metpending['statement1'] . '",
														  p1_ket="' . $metpending['p1_ket'] . '",
														  statement2="' . $metpending['statement2'] . '",
														  p2_ket="' . $metpending['p2_ket'] . '",
														  statement3="' . $metpending['statement3'] . '",
														  p3_ket="' . $metpending['p3_ket'] . '",
														  statement4="' . $metpending['statement4'] . '",
														  p4_ket="' . $metpending['p4_ket'] . '",
														  ket="' . $metpending['ket'] . '",
														  status_medik="' . $_REQUEST['m'] . '",
														  status_bayar="0",
														  status_aktif="pending",
														  status_peserta="' . $metpending['status_peserta'] . '",
														  regional ="' . $metpending['regional'] . '",
														  area ="' . $metpending['area'] . '",
														  cabang ="' . $metpending['cabang'] . '",
														  input_by ="' . $metpending['input_by'] . '",
														  input_time ="' . $metpending['input_time'] . '"');

            $metpendingdel = $database->doQuery('DELETE FROM fu_ajk_peserta_tempf WHERE no_urut="' . $_REQUEST['no_urut'] . '" AND nama="' . $_REQUEST['nama'] . '" AND tgl_lahir="' . $_REQUEST['tgl_lahir'] . '" AND kredit_tgl="' . $_REQUEST['kredit_tgl'] . '"');
            header("location:ajk_uploader_spak.php?r=viewall");
        }
        $cat = $_GET['cat']; // Use this line or below line if register_global is off
        if (strlen($cat) > 0 and !is_numeric($cat)) { // to check if $cat is numeric data or not.
            echo "Data Error";
            exit;
        }
        echo '<fieldset style="padding: 1">
	<legend align="center">S e a r c h</legend>
	<table border="0" width="40%" cellpadding="0" cellspacing="0">
		<form name="f2" method="post" action="">
		<tr><td width="15%" align="right">Company Name</td>
	  <td width="30%">: <select id="cat" name="cat" onchange="reload2(this.form)">
	  	<option value="">---Select Company---</option>';
        $quer2 = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name ASC');
        while ($noticia2 = mysql_fetch_array($quer2)) {
            if ($noticia2['id'] == $cat) {
                echo '<option selected value="' . $noticia2['id'] . '">' . $noticia2['name'] . '</option><BR>';
            }else {
                echo '<option value="' . $noticia2['id'] . '">' . $noticia2['name'] . '</option>';
            }
        }
        echo '</select></td></tr>
	<tr><td width="10%" align="right">Policy Number</td>
		<td width="20%">: ';
        if (isset($cat) and strlen($cat) > 0) {
            $quer = $database->doQuery('SELECT * FROM fu_ajk_polis where id_cost="' . $cat . '" ORDER BY id ASC');
        } else {
            $quer = $database->doQuery("SELECT * FROM fu_ajk_polis ORDER BY id ASC");
        }
        echo '<select id="subcat"  name="subcat"><option value="">---Select Policy---</option>';
        while ($noticia = mysql_fetch_array($quer)) {
            echo '<option value=' . $noticia['id'] . '>' . $noticia['nopol'] . '</option>';
        }
        echo '</select></td></tr>
		<tr><td colspan="1" align="right"><input type="submit" name="met" value="Searching" class="button"></td></tr>
			</form></table></fieldset>';

        echo '<form method="post" action="ajk_uploader_spak.php?r=approve" onload ="onbeforeunload">
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
		<th width="5%">Regional</th>
		<th width="5%">Area</th>
		<th width="5%">Cabang</th>
		<th width="5%">User</th>
		</tr>';
        if ($_REQUEST['met'] == "Searching") {
            if ($_REQUEST['cat']) {
                $satu = 'AND id_cost LIKE "%' . $_REQUEST['cat'] . '%"';
            }
            if ($_REQUEST['subcat']) {
                $dua = 'AND id_polis LIKE "%' . $_REQUEST['subcat'] . '%"';
            }
            // if ($q['status']=="10" AND $q['supervisor']=="1" OR $q['status']=="") {	$cekinputby = 'AND input_by="'.$q['nm_user'].'"';	}	DISABLED 16062014
            if ($q['status'] == 1) {
                $data = $database->doQuery('SELECT * FROM fu_ajk_peserta_tempf WHERE nama!="" ' . $satu . ' ' . $dua . ' ' . $cekinputby . ' ORDER BY input_by ASC');
            }else {
                $data = $database->doQuery('SELECT * FROM fu_ajk_peserta_tempf WHERE nama!="" ' . $satu . ' ' . $dua . ' ' . $cekinputby . ' ORDER BY input_by ASC');
            }
        } else {
            $data = $database->doQuery('SELECT * FROM fu_ajk_peserta_tempf WHERE nama !="" ' . $cekinputby . '  ORDER BY input_by ASC');
        }while ($fudata = mysql_fetch_array($data)) {
            $umur = ceil(((strtotime($fudata['kredit_tgl']) - strtotime($fudata['tgl_lahir'])) / (60 * 60 * 24 * 365.2425))); // FORMULA USIA
            $dataceklist = '<input type="checkbox" class="case" name="nama[]" value="' . $fudata['id_cost'] . '-met-' . $fudata['id_polis'] . '-met-' . $fudata['nama'] . '-met-' . $fudata['tgl_lahir'] . '-met-' . $fudata['kredit_jumlah'] . '">';
            if (($no % 2) == 1) $objlass = 'tbl-odd';
            else $objlass = 'tbl-even';
            echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	  <td align="center"><a href="ajk_uploader_spak.php?r=deldata&id_cost=' . $fudata['id_cost'] . '&id_polis=' . $fudata['id_polis'] . '&namafile=' . $fudata['namafile'] . '&no_urut=' . $fudata['no_urut'] . '&spaj=' . $fudata['spaj'] . '&nama=' . $fudata['nama'] . '&kredit_jumlah=' . $fudata['kredit_jumlah'] . '" onClick="if(confirm(\'Apakah anda yakin untuk menghapus data peserta ini ?\')){return true;}{return false;}"><img src="image/delete.gif"></a></td>
	  <td align="center">' . $dataceklist . '
	  </td>
	  <td align="center">' . ++$no . '</td>
	  <td align="center">' . $fudata['spaj'] . '</td>
	  <td>' . $fudata['nama'] . '</td>
	  <td align="center">' . $fudata['tgl_lahir'] . '</td>
	  <td align="center">' . $umur . '</td>
	  <td align="right">' . duit($fudata['kredit_jumlah']) . '</td>
	  <td align="center">' . $fudata['kredit_tgl'] . '</td>
	  <td align="center">' . $fudata['kredit_tenor'] . '</td>
	  <td align="center">' . $fudata['status_peserta'] . '</td>
	  <td align="center">' . $fudata['cabang'] . '</td>
	  <td align="center">' . $fudata['area'] . '</td>
	  <td align="center">' . $fudata['regional'] . '</td>
	  <td align="center"><font color="blue"><b>' . $fudata['input_by'] . '</b></font></td>
	  </tr>';
        }

        if ($_REQUEST['subcat'] != "" AND $q['status'] == "10" AND $q['supervisor'] == "1" OR $q['status'] == "") {
            $el = $database->doQuery('SELECT * FROM fu_ajk_peserta_tempf WHERE id_cost="' . $_REQUEST['cat'] . '"');
            $met = mysql_num_rows($el);
            // if ($met > 0) {	echo '<tr><td colspan="27" align="center"><a href="ajk_uploader_spak.php?r=approve&val=pclaim&id_cost='.$_REQUEST['cat'].'&id_polis='.$_REQUEST['subcat'].'" onClick="if(confirm(\'Apakah anda sudah yakin dengan semua data peserta ini ?\')){return true;}{return false;}">Approve</a></td></tr>';
            if ($met > 0) {
                echo '<tr><td colspan="27" align="center"><a href="ajk_uploader_spak.php?r=approve" onClick="if(confirm(\'Apakah anda sudah yakin dengan semua data peserta ini ?\')){return true;}{return false;}"><input type="submit" name="ApproveClaim" Value="Approve"></a></td></tr>';
                // }else{	echo '<tr><th colspan="27" align="center"><blink><b><font color="blue">Tidak ada data peserta restruktur, topup atau refund yang harus di validasi. !!!</font></b></blink></th></tr>';
            } else {
                echo '';
            }
        } else {
            // echo '<tr><td colspan="27" align="center">'.$q['status'].'</td></tr>';
        }

        echo '</table>'; ;
        break;

case "set_spak":
	echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="95%" align="left">Modul Data SPK</font></th></tr></table>';
	echo '<table border="0" width="100%" cellpadding="1" cellspacing="1">
	  <form method="post" action="">
	  <tr><td align="right" width="10%">Nomor SPK :</td><td><input type="text" name="nospk" value="' . $_REQUEST['nospk'] . '"></td></tr>
	  <tr><td align="right" width="10%">Nama Debitur :</td><td><input type="text" name="namaspk" value="' . $_REQUEST['namaspk'] . '">
	  <input type="submit" name="button" value="Cari" class="button"></td></tr>

	 <tr><td></td><td><a href="ajk_uploader_spak.php?r=set_spak_mp"><img src="image/new.png" width="20"> Data SPK Mobile Percepatan</a></td></tr>
	  </form></table>';
	echo '<form method="post" action="ajk_uploader_spak.php?r=approve_spak_dokter" onload ="onbeforeunload">
	 <table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#bde0e6">
	 <tr><th width="1%">No</th>
	 	 <th width="1%">Option <input type="checkbox" id="selectall"/></th>
	 	 <th>Perusahaan</th>
	 	 <th width="1%">Produk</th>
	 	 <th width="1%">No.Permohonan</th>
	 	 <th width="1%">SPK</th>
	 	 <th width="1%">No.Identitas</th>
	 	 <th>Nama</th>
	 	 <th width="1%">Tgl Lahir</th>
	 	 <th width="1%">Tgl Akad</th>
	 	 <th width="1%">Tenor</th>
	 	 <th width="1%">Tgl Akhir</th>
	 	 <th width="1%">Grace Period</th>
	 	 <th width="20%">Keterangan</th>
	 	 <th width="1%">Plafond</th>
	 	 <th width="1%">Rate</th>
	 	 <th width="1%">Premi (x)</th>
	 	 <!--<th width="1%">Premi (Plafond*rate/mil)</th>-->
	 	 <th width="1%">Usia (x)</th>
	 	 <th width="1%">Ex.Premi<br />(%)</th>
	 	 <th width="5%">File SPK</th>
	 	 <th width="5%">Cabang</th>
	 	 <th width="1%">User Input</th>
	 	 <th width="8%">Tgl Input</th>
	 	 <th width="1%">User Approve</th>
	 	 <th width="8%">Tgl Approve</th>
	 	 <th width="1%">Status</th>
	 	 <th width="1%">Photo</th>
	 	 <th width="5%">Option</th>
	 </tr>';
        if ($_REQUEST['nospk']) {	$satu = 'AND spak LIKE "%' . $_REQUEST['nospk'] . '%"';	}
        if ($_REQUEST['namaspk']) {
        	$ceknama = mysql_fetch_array($database->doQuery('SELECT fu_ajk_spak_form.id, fu_ajk_spak_form.idspk, fu_ajk_spak_form.nama, fu_ajk_spak.spak
    														 FROM fu_ajk_spak_form
    														 left Join fu_ajk_spak ON fu_ajk_spak_form.idspk = fu_ajk_spak.id
															 WHERE fu_ajk_spak_form.nama="'.$_REQUEST['namaspk'].'" AND fu_ajk_spak.status="Approve"'));
       	$dua = 'AND id = "' . $ceknama['idspk'] . '"';
		}

if ($_REQUEST['x']) {	$m = ($_REQUEST['x'] - 1) * 25;	} else {	$m = 0;	}
if ($q['level'] == "99" AND $q['status'] == "STAFF") {
		 $met = $database->doQuery('SELECT * FROM fu_ajk_spak WHERE SUBSTR(spak,1,2)<>"MP" and status!="Aktif" ' . $satu . ' ' . $dua . ' AND status!="Batal" AND status!="Tolak" AND status!="Pending" AND status!="Kadaluarsa"  AND del IS NULL ORDER BY id DESC LIMIT ' . $m . ' , 25');
    $totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_spak WHERE SUBSTR(spak,1,2)<>"MP" and status!="Aktif" ' . $satu . ' ' . $dua . ' AND status!="Batal" AND status!="Kadaluarsa" AND status!="Tolak" AND del IS NULL'));
    $totalRows = $totalRows[0];
}elseif ($q['level'] == "99" AND $q['status'] == "SUPERVISOR" AND $q['supervisor'] == "0") {
    	 $met = $database->doQuery('SELECT * FROM fu_ajk_spak WHERE SUBSTR(spak,1,2)<>"MP" and status!="Aktif" ' . $satu . ' ' . $dua . ' AND status!="Batal" AND status!="Tolak" AND status!="Pending" AND status!="Kadaluarsa" AND del IS NULL ORDER BY id DESC LIMIT ' . $m . ' , 25');
    $totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_spak WHERE SUBSTR(spak,1,2)<>"MP" and status!="Aktif" ' . $satu . ' ' . $dua . ' AND status!="Batal" AND status!="Kadaluarsa" AND status!="Tolak" AND del IS NULL'));
    $totalRows = $totalRows[0];
}else {
    	 $met = $database->doQuery('SELECT * FROM fu_ajk_spak WHERE SUBSTR(spak,1,2)<>"MP" and status="Approve" ' . $satu . ' ' . $dua . ' AND status!="Batal" AND status!="Tolak" AND status!="Pending" AND status!="Kadaluarsa" AND del IS NULL ORDER BY id DESC LIMIT ' . $m . ' , 25');
    $totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_spak WHERE SUBSTR(spak,1,2)<>"MP" and status="Approve" ' . $satu . ' ' . $dua . ' AND status!="Batal" AND status!="Kadaluarsa" AND status!="Tolak" AND del IS NULL'));
    $totalRows = $totalRows[0];
}
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while ($met_ = mysql_fetch_array($met)) {
	$met_company = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="' . $met_['id_cost'] . '"'));
    $metdata = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE type_data="SPK" AND spaj="' . $met_['spak'] . '" AND del IS NULL'));
    $met_formspk = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_form WHERE idspk="' . $met_['id'] . '" AND del IS NULL'));
    $metCabang = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id="' . $met_formspk['cabang'] . '" AND del IS NULL'));
	$met_produk = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="' . $met_['id_polis'] . '" AND del IS NULL'));
    // CEK STATUS DATA SPK
            if ($met_['status'] == "Batal" OR $met_['status']=="Tolak") {	$statusspknya = '<font color="red">' . $met_['status'] . '</font>';	}
			else {	$statusspknya = '<font color="blue">' . $met_['status'] . '</font>';	}
            // CEK STATUS DATA SPK
            if ($metdata['spaj'] == $met_['spak']) {	$_datamet = $metdata['nama'];	}
			else {	$_datamet = $met_formspk['nama'];	}

            if ($q['status'] == "STAFF" OR $q['status'] == "SUPERVISOR" OR $q['status'] == "" OR $q['status'] == "UNDERWRITING") {
                $cekformspak = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_form WHERE idspk="' . $met_['id'] . '" AND del IS NULL'));
                if ($cekformspak['idspk'] == $met_['id']) {
                    $setting_fspak = '<a href="ajk_uploader_spak.php?r=edit_spak&ids=' . $met_['id'] . '&idform=' . $met_formspk['id'] . '"><img src="image/edit3.png" width="20"></a>';
                    if ($q['status'] == "SUPERVISOR") {
                        $setting_fspak = '<a href="ajk_uploader_spak.php?r=vsett_spak&ids=' . $met_['id'] . '"><img src="image/new.png" width="20"></a>';
                        if ($met_['status'] == "Batal") {
                            $approve_spk = '';
                        } else {
                            if ($met_['status'] == "Approve") {	$metikonapprove = '<img src="image/ya2.png" width="15">';	}
							else {	$metikonapprove = '<img src="image/ya.png" width="15">';	}
                            $approve_spk = '<a href="ajk_uploader_spak.php?r=vdelsett_spak&ids=' . $met_['id'] . '" onClick="if(confirm(\'Apakah anda yakin membatalkan atau revisi data SPK ini ?\')){return true;}{return false;}"><img src="image/delete.gif" width="15"></a> &nbsp;
							<a href="ajk_uploader_spak.php?r=approve_spak&ids=' . $met_['id'] . '" onClick="if(confirm(\'Apakah anda yakin data SPK telah sesuai ?\')){return true;}{return false;}">' . $metikonapprove . '</a>';
                        }
                        $setting_fspak = '<a href="ajk_uploader_spak.php?r=edit_spak&ids=' . $met_['id'] . '&idform=' . $met_formspk['id'] . '"><img src="image/edit3.png" width="20"></a>';
                    }elseif ($q['status'] == "" OR $q['status'] == "UNDERWRITING" AND $q['supervisor'] == "0") {
                        $approve_spk = '<a href="ajk_uploader_spak.php?r=extpremi_spk&ids=' . $met_['id'] . '"><img src="image/plus.png" width="15"></a> &nbsp;';
                        if(isset($met_['ext_premi']) != "" ){
                            $dataceklist = '<input type="checkbox" class="case" name="namaspk[]" value="' . $met_['id'] . '">';    
                        }else{
                            $dataceklist = '';
                        }                        
                       	$setting_fspak = '<a title="Preview Data SPK" href="ajk_uploader_spak.php?r=vsett_spak&ids=' . $met_['id'] . '"><img src="image/new.png" width="20"></a> &nbsp;
							 			  <a title="Tolak Data SPK" href="ajk_uploader_spak.php?r=tolak_spak&ids=' . $met_['id'] . '" onClick="if(confirm(\'Anda yakin untuk membatalkan data SPK ini ?\')){return true;}{return false;}"><img src="image/deleted.png" width="20"></a> &nbsp;';
                    }else {	}
                } else {
                    $setting_fspak = '<a href="ajk_uploader_spak.php?r=sett_spak&ids=' . $met_['id'] . '"><img src="image/new.png" width="20"></a> &nbsp;
							<a href="ajk_uploader_spak.php?r=dell_spak&ids=' . $met_['id'] . '" onClick="if(confirm(\'Apakah anda yakin membatalkan data SPK ini ?\')){return true;}{return false;}" title="Batal SPK"><img src="image/delete.gif" width="15"></a> &nbsp;
							';
                }
            } else {	}

            $x_file = str_replace(' ', '%20', $met_['fname']);

            /*
if ($met_['status']!="Aktif") {
	$approve_spk__ = $approve_spk;
}else{
	$approve_spk__ = '';
}
*/
if (!is_numeric($met_['input_by'])) {
	if ($met_['photo_spk'] == "") {
    $v_photo = '<img src="../image/non-user.png" width="50">';
    } else {
    $v_photo = '<a href="' . $metpath_file . '' . $met_['photo_spk'] . '" rel="lightbox" target="_blank"><img src="' . $metpath_file . '' . $met_['photo_spk'] . '" width="50"></a>';
    }
}else{
	$v_photo = '<a href="../../ajkmobilescript/'.$met_formspk['filefotodebiturdua'].'" rel="lightbox" target="_blank"><img src="../../ajkmobilescript/'.$met_formspk['filefotodebiturdua'].'"  width="50">';
}

if (($no % 2) == 1) $objlass = 'tbl-odd';	else $objlass = 'tbl-even';
if (is_numeric($met_['input_by'])) {
$met_User = mysql_fetch_array($database->doQuery('SELECT id, nama, namalengkap FROM user_mobile WHERE id="'.$met_['input_by'].'"'));
$inputby_met = $met_User['namalengkap'];
}else{
$inputby_met = $met_['input_by'];
}

if (is_numeric($met_['update_by'])) {
	$met_UserSPV = mysql_fetch_array($database->doQuery('SELECT id, nama, namalengkap FROM user_mobile WHERE id="'.$met_['update_by'].'"'));
	$updateby_met = $met_UserSPV['namalengkap'];
}else{
	$updateby_met = $met_['update_by'];
}

//$cekNilaiPremi = $met_formspk['plafond'] * $met_formspk['ratebank'] / 1000;	dihide 17102016
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	  <td align="center">' . (++$no + ($pageNow - 1) * 25) . '</td>
			<td align="center">' . $approve_spk . ' ' . $dataceklist . '</td>
			<td>' . strtoupper($met_company['name']) . '</td>
			<td>' . strtoupper($met_produk['nmproduk']) . '</td>
			<td align="center"><font color="blue">' . $met_formspk['nopermohonan'] . '</font></td>
			<td align="center">' . $met_['spak'] . '</td>
			<td align="center">' . $met_formspk['noidentitas'] . '</td>
			<td>' . strtoupper($_datamet) . '</td>
			<td align="center">' . _convertDate($met_formspk['dob']) . '</td>
			<td align="center">' . _convertDate($met_formspk['tgl_asuransi']) . '</td>
			<td align="center">' . $met_formspk['tenor'] . '</td>
			<td align="center">' . _convertDate($met_formspk['tgl_akhir_asuransi']) . '</td>
			<td align="center">' . $met_formspk['mpp'] . '</td>
			<td><a title="'.$met_['ket_ext'].'">' . substr($met_['ket_ext'], 0, 30) . '</a></td>
			<td align="right">' . duit($met_formspk['plafond']) . '</td>
			<td align="center"><b>' . $met_formspk['ratebank'] . '</b></td>
			<td align="right">' . duit($met_formspk['x_premi']) . '</td>
			<!--<td align="right">' . duit($cekNilaiPremi) . '</td>-->
			<td align="center">' . $met_formspk['x_usia'] . '</td>
			<td align="center">' . $met_['ext_premi'] . '</td>
		    <td align="center"><a href=' . $metpath_file . '' . $x_file . ' target="_blank">'.$met_['fname'].'</a></td>
		    <td align="center">' . $metCabang['name'] . '</td>
		    <td align="center">' . $inputby_met . '</td>
		    <td align="center">' . $met_['input_date'] . '</td>
				<td align="center">' . $updateby_met . '</td>
		    <td align="center">' . $met_['update_date'] . '</td>
		    <td align="center">' . $statusspknya . '</td>
		    <td align="center">' . $v_photo . '</td>
		    <td align="center">' . $setting_fspak . '</td>
		  </tr>';
        }
		if ($q['status'] == "UNDERWRITING" AND $q['supervisor'] == "0" AND $q['level'] == "99" OR $q['level'] == "1" AND $q['supervisor'] == "0") {
		$el = $database->doQuery('SELECT * FROM fu_ajk_spak WHERE status="Approve"');
		$met = mysql_num_rows($el);
		if ($met > 0) {
		echo '<tr><td colspan="28" align="center"><a href="ajk_uploader_spak.php?r=approve_spak_dokter" onClick="if(confirm(\'Apakah anda sudah yakin dengan semua data SPK ini ?\')){return true;}{return false;}"><input type="submit" name="ApproveClaim" Value="Approve"></a></td></tr>';
            } else {
                echo '';
            }
        } else {
            // echo '<tr><td colspan="27" align="center">'.$q['status'].'</td></tr>';
        }
        echo '<tr><td colspan="22">';
        echo createPageNavigations($file = 'ajk_uploader_spak.php?r=set_spak&nospk=' . $_REQUEST['nospk'] . '', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
        echo '<b>Total Data SPK: <u>' . $totalRows . '</u></b></td></tr>';
        echo '</table><br>';



        if(!empty($_REQUEST['nospk']) || !empty($_REQUEST['namaspk'])){
        	echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="95%" align="left" style="background-color: #666666;">Data SPK Kadaluarsa</font></th></tr></table>';

        echo '<form method="post" action="ajk_uploader_spak.php?r=approve_spak_dokter" onload ="onbeforeunload">
	 <table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#bde0e6">
	 <tr><th width="1%">No</th>
	 	 <th>Perusahaan</th>
	 	 <th width="1%">Produk</th>
	 	 <th width="1%">No.Permohonan</th>
	 	 <th width="1%">SPK</th>
	 	 <th width="1%">No.Identitas</th>
	 	 <th>Nama</th>
	 	 <th width="1%">Tgl Lahir</th>
	 	 <th width="1%">Tgl Akad</th>
	 	 <th width="1%">Tenor</th>
	 	 <th width="1%">Tgl Akhir</th>
	 	 <th width="1%">Grace Period</th>
	 	 <th width="20%">Keterangan</th>
	 	 <th width="1%">Plafond</th>
	 	 <th width="1%">Rate</th>
	 	 <th width="1%">Premi (x)</th>
	 	 <!--<th width="1%">Premi (Plafond*rate/mil)</th>-->
	 	 <th width="1%">Usia (x)</th>
	 	 <th width="1%">Ex.Premi<br />(%)</th>
	 	 <th width="5%">File SPK</th>
	 	 <th width="5%">Cabang</th>
	 	 <th width="1%">User Upload</th>
	 	 <th width="8%">Tgl Upload</th>
	 	 <th width="1%">User Approve</th>
	 	 <th width="8%">Tgl Approve</th>
	 	 <th width="1%">Status</th>
	 	 <th width="1%">Photo</th>
	 	 <th width="5%">Option</th>
	 </tr>';


        if ($_REQUEST['x']) {	$m = ($_REQUEST['x'] - 1) * 25;	} else {	$m = 0;	}
        if ($q['level'] == "99" AND $q['status'] == "STAFF") {
        	$met = $database->doQuery('SELECT
				fu_ajk_spak.*
				FROM
				fu_ajk_spak
				INNER JOIN fu_ajk_spak_form ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
				where (fu_ajk_spak_form.nama like "%'.$_REQUEST['namaspk'].'%" and fu_ajk_spak.spak like "%'.$_REQUEST['nospk'].'%") and  SUBSTR(spak,1,2)<>"MP" AND status="Kadaluarsa"  AND fu_ajk_spak.del IS NULL ORDER BY fu_ajk_spak.id DESC LIMIT ' . $m . ' , 25');
        	$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(fu_ajk_spak.id) FROM
				fu_ajk_spak
				INNER JOIN fu_ajk_spak_form ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
				where (fu_ajk_spak_form.nama like "%'.$_REQUEST['namaspk'].'%" and fu_ajk_spak.spak like "%'.$_REQUEST['nospk'].'%") and  SUBSTR(spak,1,2)<>"MP" AND status="Kadaluarsa"  AND fu_ajk_spak.del IS NULL ORDER BY fu_ajk_spak.id DESC'));
        	$totalRows = $totalRows[0];
        }elseif ($q['level'] == "99" AND $q['status'] == "SUPERVISOR" AND $q['supervisor'] == "0") {
        	$met = $database->doQuery('SELECT
				fu_ajk_spak.*
				FROM
				fu_ajk_spak
				INNER JOIN fu_ajk_spak_form ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
				where (fu_ajk_spak_form.nama like "%'.$_REQUEST['namaspk'].'%" and fu_ajk_spak.spak like "%'.$_REQUEST['nospk'].'%") and  SUBSTR(spak,1,2)<>"MP" AND status="Kadaluarsa"  AND fu_ajk_spak.del IS NULL ORDER BY fu_ajk_spak.id DESC LIMIT ' . $m . ' , 25');
        	$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(fu_ajk_spak.id) FROM
				fu_ajk_spak
				INNER JOIN fu_ajk_spak_form ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
				where (fu_ajk_spak_form.nama like "%'.$_REQUEST['namaspk'].'%" and fu_ajk_spak.spak like "%'.$_REQUEST['nospk'].'%") and  SUBSTR(spak,1,2)<>"MP" AND status="Kadaluarsa"  AND fu_ajk_spak.del IS NULL ORDER BY fu_ajk_spak.id DESC'));
        	$totalRows = $totalRows[0];
        }else {
        	$met = $database->doQuery('SELECT
				fu_ajk_spak.*
				FROM
				fu_ajk_spak
				INNER JOIN fu_ajk_spak_form ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
				where (fu_ajk_spak_form.nama like "%'.$_REQUEST['namaspk'].'%" and fu_ajk_spak.spak like "%'.$_REQUEST['nospk'].'%") and  SUBSTR(spak,1,2)<>"MP" AND status="Kadaluarsa"  AND fu_ajk_spak.del IS NULL ORDER BY fu_ajk_spak.id DESC LIMIT ' . $m . ' , 25');
        	$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(fu_ajk_spak.id) FROM
				fu_ajk_spak
				INNER JOIN fu_ajk_spak_form ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
				where (fu_ajk_spak_form.nama like "%'.$_REQUEST['namaspk'].'%" and fu_ajk_spak.spak like "%'.$_REQUEST['nospk'].'%") and  SUBSTR(spak,1,2)<>"MP" AND status="Kadaluarsa"  AND fu_ajk_spak.del IS NULL ORDER BY fu_ajk_spak.id DESC'));
        	$totalRows = $totalRows[0];
        }
        $_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
        while ($met_ = mysql_fetch_array($met)) {
        	$met_company = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="' . $met_['id_cost'] . '"'));
        	$metdata = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE type_data="SPK" AND spaj="' . $met_['spak'] . '" AND del IS NULL'));
        	$met_formspk = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_form WHERE idspk="' . $met_['id'] . '" AND del IS NULL'));
        	$metCabang = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id="' . $met_formspk['cabang'] . '" AND del IS NULL'));
        	$met_produk = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="' . $met_['id_polis'] . '" AND del IS NULL'));
        	// CEK STATUS DATA SPK
        	if ($met_['status'] == "Batal" OR $met_['status']=="Tolak") {	$statusspknya = '<font color="red">' . $met_['status'] . '</font>';	}
        	else {	$statusspknya = '<font color="blue">' . $met_['status'] . '</font>';	}
        	// CEK STATUS DATA SPK
        	if ($metdata['spaj'] == $met_['spak']) {	$_datamet = $metdata['nama'];	}
        	else {	$_datamet = $met_formspk['nama'];	}

        	if ($q['status'] == "STAFF" OR $q['status'] == "SUPERVISOR" OR $q['status'] == "" OR $q['status'] == "UNDERWRITING") {
        		$cekformspak = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_form WHERE idspk="' . $met_['id'] . '" AND del IS NULL'));
        		if ($cekformspak['idspk'] == $met_['id']) {
        			$setting_fspak = '<a href="ajk_uploader_spak.php?r=edit_spak&ids=' . $met_['id'] . '&idform=' . $met_formspk['id'] . '"><img src="image/edit3.png" width="20"></a>';
        			if ($q['status'] == "SUPERVISOR") {
        				$setting_fspak = '<a href="ajk_uploader_spak.php?r=vsett_spak&ids=' . $met_['id'] . '"><img src="image/new.png" width="20"></a>';
        				if ($met_['status'] == "Batal") {
        					$approve_spk = '';
        				} else {
        					if ($met_['status'] == "Approve") {	$metikonapprove = '<img src="image/ya2.png" width="15">';	}
        					else {	$metikonapprove = '<img src="image/ya.png" width="15">';	}
        					$approve_spk = '<a href="ajk_uploader_spak.php?r=vdelsett_spak&ids=' . $met_['id'] . '" onClick="if(confirm(\'Apakah anda yakin membatalkan atau revisi data SPK ini ?\')){return true;}{return false;}"><img src="image/delete.gif" width="15"></a> &nbsp;
							<a href="ajk_uploader_spak.php?r=approve_spak&ids=' . $met_['id'] . '" onClick="if(confirm(\'Apakah anda yakin data SPK telah sesuai ?\')){return true;}{return false;}">' . $metikonapprove . '</a>';
        				}
        				$setting_fspak = '<a href="ajk_uploader_spak.php?r=edit_spak&ids=' . $met_['id'] . '&idform=' . $met_formspk['id'] . '"><img src="image/edit3.png" width="20"></a>';
        			}elseif ($q['status'] == "" OR $q['status'] == "UNDERWRITING" AND $q['supervisor'] == "0") {
        				$approve_spk = '<a href="ajk_uploader_spak.php?r=extpremi_spk&ids=' . $met_['id'] . '"><img src="image/plus.png" width="15"></a> &nbsp;';
        				$dataceklist = '<input type="checkbox" class="case" name="namaspk[]" value="' . $met_['id'] . '">';
        				$setting_fspak = '<a title="Preview Data SPK" href="ajk_uploader_spak.php?r=vsett_spak&ids=' . $met_['id'] . '"><img src="image/new.png" width="20"></a> &nbsp;
							 			   &nbsp;';
        			}else {	}
        		} else {
        			$setting_fspak = '<a href="ajk_uploader_spak.php?r=sett_spak&ids=' . $met_['id'] . '"><img src="image/new.png" width="20"></a> &nbsp;
							<a href="ajk_uploader_spak.php?r=dell_spak&ids=' . $met_['id'] . '" onClick="if(confirm(\'Apakah anda yakin membatalkan data SPK ini ?\')){return true;}{return false;}"><img src="image/delete.gif" width="15"></a> &nbsp;
							';
        		}
        	} else {	}

        	$x_file = str_replace(' ', '%20', $met_['fname']);

        	/*
        	 if ($met_['status']!="Aktif") {
        	 $approve_spk__ = $approve_spk;
        	 }else{
        	 $approve_spk__ = '';
        	 }
        	 */
        	if (!is_numeric($met_['input_by'])) {
        		if ($met_['photo_spk'] == "") {
        			$v_photo = '<img src="../image/non-user.png" width="50">';
        		} else {
        			$v_photo = '<a href="' . $metpath_file . '' . $met_['photo_spk'] . '" rel="lightbox" target="_blank"><img src="' . $metpath_file . '' . $met_['photo_spk'] . '" width="50"></a>';
        		}
        	}else{
        		$v_photo = '<a href="../../ajkmobilescript/'.$met_formspk['filefotodebiturdua'].'" rel="lightbox" target="_blank"><img src="../../ajkmobilescript/'.$met_formspk['filefotodebiturdua'].'"  width="50">';
        	}

        	if (($no % 2) == 1) $objlass = 'tbl-odd';	else $objlass = 'tbl-even';
        	if (is_numeric($met_['input_by'])) {
        		$met_User = mysql_fetch_array($database->doQuery('SELECT id, nama, namalengkap FROM user_mobile WHERE id="'.$met_['input_by'].'"'));
        		$inputby_met = $met_User['namalengkap'];
        	}else{
        		$inputby_met = $met_['input_by'];
        	}

        	if (is_numeric($met_['update_by'])) {
        		$met_UserSPV = mysql_fetch_array($database->doQuery('SELECT id, nama, namalengkap FROM user_mobile WHERE id="'.$met_['update_by'].'"'));
        		$updateby_met = $met_UserSPV['namalengkap'];
        	}else{
        		$updateby_met = $met_['update_by'];
        	}

        	//$cekNilaiPremi = $met_formspk['plafond'] * $met_formspk['ratebank'] / 1000;	dihide 17102016
        	echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	  <td align="center">' . (++$no + ($pageNow - 1) * 25) . '</td>
			<td>' . strtoupper($met_company['name']) . '</td>
			<td>' . strtoupper($met_produk['nmproduk']) . '</td>
			<td align="center"><font color="blue">' . $met_formspk['nopermohonan'] . '</font></td>
			<td align="center">' . $met_['spak'] . '</td>
			<td align="center">' . $met_formspk['noidentitas'] . '</td>
			<td>' . strtoupper($_datamet) . '</td>
			<td align="center">' . _convertDate($met_formspk['dob']) . '</td>
			<td align="center">' . _convertDate($met_formspk['tgl_asuransi']) . '</td>
			<td align="center">' . $met_formspk['tenor'] . '</td>
			<td align="center">' . _convertDate($met_formspk['tgl_akhir_asuransi']) . '</td>
			<td align="center">' . $met_formspk['mpp'] . '</td>
			<td><a title="'.$met_['ket_ext'].'">' . substr($met_['ket_ext'], 0, 30) . '</a></td>
			<td align="right">' . duit($met_formspk['plafond']) . '</td>
			<td align="center"><b>' . $met_formspk['ratebank'] . '</b></td>
			<td align="right">' . duit($met_formspk['x_premi']) . '</td>
			<!--<td align="right">' . duit($cekNilaiPremi) . '</td>-->
			<td align="center">' . $met_formspk['x_usia'] . '</td>
			<td align="center">' . $met_['ext_premi'] . '</td>
		    <td align="center"><a href=' . $metpath_file . '' . $x_file . ' target="_blank">'.$met_['fname'].'</a></td>
		    <td align="center">' . $metCabang['name'] . '</td>
		    <td align="center">' . $inputby_met . '</td>
		    <td align="center">' . $met_['input_date'] . '</td>
			<td align="center">' . $updateby_met . '</td>
		    <td align="center">' . $met_['update_date'] . '</td>
		    <td align="center">' . $statusspknya . '</td>
		    <td align="center">' . $v_photo . '</td>
		    <td align="center">' . $setting_fspak . '</td>
		  </tr>';
        }

        echo '<tr><td colspan="22">';
        echo createPageNavigations($file = 'ajk_uploader_spak.php?r=set_spak&nospk=' . $_REQUEST['nospk'] . '', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
        echo '<b>Total Data SPK Kadaluarsa: <u>' . $totalRows . '</u></b></td></tr>';
        echo '</table>';
        }


        break;

    case "set_spak":
      echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="95%" align="left">Modul Data SPK</font></th></tr></table>
            <table border="0" width="100%" cellpadding="1" cellspacing="1">
            <form method="post" action="">
            <tr><td align="right" width="10%">Nomor SPK :</td><td><input type="text" name="nospk" value="' . $_REQUEST['nospk'] . '"></td></tr>
            <tr><td align="right" width="10%">Nama Debitur :</td><td><input type="text" name="namaspk" value="' . $_REQUEST['namaspk'] . '">
            <input type="submit" name="button" value="Cari" class="button"></td></tr>
            <tr><td></td><td><a href="ajk_uploader_spak.php?r=set_spak_mp"><img src="image/new.png" width="20"> Data SPK Mobile Percepatan</a></td></tr>
            </form></table>
            <form method="post" action="ajk_uploader_spak.php?r=approve_spak_dokter" onload ="onbeforeunload">
            <table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#bde0e6">
            <tr><th width="1%">No</th>
            <th width="1%">Option <input type="checkbox" id="selectall"/></th>
            <th>Perusahaan</th>
            <th width="1%">Produk</th>
            <th width="1%">No.Permohonan</th>
            <th width="1%">SPK</th>
            <th width="1%">No.Identitas</th>
            <th>Nama</th>
            <th width="1%">Tgl Lahir</th>
            <th width="1%">Tgl Akad</th>
            <th width="1%">Tenor</th>
            <th width="1%">Tgl Akhir</th>
            <th width="1%">Grace Period</th>
            <th width="20%">Keterangan</th>
            <th width="1%">Plafond</th>
            <th width="1%">Rate</th>
            <th width="1%">Premi (x)</th>
            <!--<th width="1%">Premi (Plafond*rate/mil)</th>-->
            <th width="1%">Usia (x)</th>
            <th width="1%">Ex.Premi<br />(%)</th>
            <th width="5%">File SPK</th>
            <th width="5%">Cabang</th>
            <th width="1%">User Input</th>
            <th width="8%">Tgl Input</th>
            <th width="1%">User Approve</th>
            <th width="8%">Tgl Approve</th>
            <th width="1%">Status</th>
            <th width="1%">Photo</th>
            <th width="5%">Option</th>
            </tr>';
        if ($_REQUEST['nospk']) {   $satu = 'AND spak LIKE "%' . $_REQUEST['nospk'] . '%"'; }
        if ($_REQUEST['namaspk']) {
            $ceknama = mysql_fetch_array($database->doQuery('SELECT fu_ajk_spak_form.id, fu_ajk_spak_form.idspk, fu_ajk_spak_form.nama, fu_ajk_spak.spak
                                                                                 FROM fu_ajk_spak_form
                                                                                 left Join fu_ajk_spak ON fu_ajk_spak_form.idspk = fu_ajk_spak.id
                                                                                 WHERE fu_ajk_spak_form.nama="'.$_REQUEST['namaspk'].'" AND fu_ajk_spak.status="Approve"'));
            $dua = 'AND id = "' . $ceknama['idspk'] . '"';
        }

        if ($_REQUEST['x']) {   $m = ($_REQUEST['x'] - 1) * 25; } else {    $m = 0; }

        if ($q['level'] == "99" AND $q['status'] == "STAFF") {
            //$met = $database->doQuery('SELECT * FROM fu_ajk_spak WHERE SUBSTR(spak,1,2)<>"MP" and status!="Aktif" ' . $satu . ' ' . $dua . ' AND status!="Batal" AND status!="Tolak" AND status!="Pending" AND status!="Kadaluarsa"  AND del IS NULL ORDER BY id DESC LIMIT ' . $m . ' , 25');
          $met = $database->doQuery('SELECT * FROM fu_ajk_spak WHERE SUBSTR(spak,1,2)<>"MP" ' . $satu . ' ' . $dua . ' AND status="Approve" ORDER BY id DESC LIMIT ' . $m . ' , 25');
          //$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_spak WHERE SUBSTR(spak,1,2)<>"MP" and status!="Aktif" ' . $satu . ' ' . $dua . ' AND status!="Batal" AND status!="Kadaluarsa" AND status!="Tolak" AND del IS NULL'));
          $totalRows = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak WHERE SUBSTR(spak,1,2)<>"MP" ' . $satu . ' ' . $dua . ' AND status="Approve" ORDER BY id DESC LIMIT ' . $m . ' , 25'));
          $totalRows = $totalRows[0];
        }elseif ($q['level'] == "99" AND $q['status'] == "SUPERVISOR" AND $q['supervisor'] == "0") {
          $met = $database->doQuery('SELECT * FROM fu_ajk_spak WHERE SUBSTR(spak,1,2)<>"MP" and status!="Aktif" ' . $satu . ' ' . $dua . ' AND status!="Batal" AND status!="Tolak" AND status!="Pending" AND status!="Kadaluarsa" AND del IS NULL ORDER BY id DESC LIMIT ' . $m . ' , 25');
          $totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_spak WHERE SUBSTR(spak,1,2)<>"MP" and status!="Aktif" ' . $satu . ' ' . $dua . ' AND status!="Batal" AND status!="Kadaluarsa" AND status!="Tolak" AND del IS NULL'));
          $totalRows = $totalRows[0];
        }else {
          $met = $database->doQuery('SELECT * FROM fu_ajk_spak WHERE SUBSTR(spak,1,2)<>"MP" and status="Approve" ' . $satu . ' ' . $dua . ' AND status!="Batal" AND status!="Tolak" AND status!="Pending" AND status!="Kadaluarsa" AND del IS NULL ORDER BY id DESC LIMIT ' . $m . ' , 25');
          $totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_spak WHERE SUBSTR(spak,1,2)<>"MP" and status="Approve" ' . $satu . ' ' . $dua . ' AND status!="Batal" AND status!="Kadaluarsa" AND status!="Tolak" AND del IS NULL'));
          $totalRows = $totalRows[0];
        }

        $_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;

        while ($met_ = mysql_fetch_array($met)) {
            $met_company = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="' . $met_['id_cost'] . '"'));
          $metdata = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE type_data="SPK" AND spaj="' . $met_['spak'] . '" AND del IS NULL'));
          $met_formspk = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_form WHERE idspk="' . $met_['id'] . '" AND del IS NULL'));
          $metCabang = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id="' . $met_formspk['cabang'] . '" AND del IS NULL'));
            $met_produk = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="' . $met_['id_polis'] . '" AND del IS NULL'));
            // CEK STATUS DATA SPK
          if ($met_['status'] == "Batal" OR $met_['status']=="Tolak") { 
            $statusspknya = '<font color="red">' . $met_['status'] . '</font>'; 
          }else{    
            $statusspknya = '<font color="blue">' . $met_['status'] . '</font>';    
          }
          // CEK STATUS DATA SPK
          if ($metdata['spaj'] == $met_['spak']) {  
            $_datamet = $metdata['nama'];   
          }else{    
            $_datamet = $met_formspk['nama'];   
          }

          if ($q['status'] == "STAFF" OR $q['status'] == "SUPERVISOR" OR $q['status'] == "" OR $q['status'] == "UNDERWRITING") {
            $cekformspak = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_form WHERE idspk="' . $met_['id'] . '" AND del IS NULL'));
            if ($cekformspak['idspk'] == $met_['id']) {
              $setting_fspak = '<a href="ajk_uploader_spak.php?r=edit_spak&ids=' . $met_['id'] . '&idform=' . $met_formspk['id'] . '"><img src="image/edit3.png" width="20"></a>';
              if ($q['status'] == "SUPERVISOR") {
                $setting_fspak = '<a href="ajk_uploader_spak.php?r=vsett_spak&ids=' . $met_['id'] . '"><img src="image/new.png" width="20"></a>';
                if ($met_['status'] == "Batal") {
                  $approve_spk = '';
                }else {
                  if ($met_['status'] == "Approve") {   
                    $metikonapprove = '<img src="image/ya2.png" width="15">';   
                  }else {   
                    $metikonapprove = '<img src="image/ya.png" width="15">';    
                  }
                  $approve_spk = '<a href="ajk_uploader_spak.php?r=vdelsett_spak&ids=' . $met_['id'] . '" onClick="if(confirm(\'Apakah anda yakin membatalkan atau revisi data SPK ini ?\')){return true;}{return false;}"><img src="image/delete.gif" width="15"></a> &nbsp;
                                                <a href="ajk_uploader_spak.php?r=approve_spak&ids=' . $met_['id'] . '" onClick="if(confirm(\'Apakah anda yakin data SPK telah sesuai ?\')){return true;}{return false;}">' . $metikonapprove . '</a>';
                }
                $setting_fspak = '<a href="ajk_uploader_spak.php?r=edit_spak&ids=' . $met_['id'] . '&idform=' . $met_formspk['id'] . '"><img src="image/edit3.png" width="20"></a>';
              }elseif ($q['status'] == "" OR $q['status'] == "UNDERWRITING" AND $q['supervisor'] == "0") {
                $approve_spk = '<a href="ajk_uploader_spak.php?r=extpremi_spk&ids=' . $met_['id'] . '"><img src="image/plus.png" width="15"></a> &nbsp;';
                if(isset($met_['ext_premi']) != "" ){
                  $dataceklist = '<input type="checkbox" class="case" name="namaspk[]" value="' . $met_['id'] . '">';    
                }else{
                  $dataceklist = '';
                }                        
                $setting_fspak = '<a title="Preview Data SPK" href="ajk_uploader_spak.php?r=vsett_spak&ids=' . $met_['id'] . '"><img src="image/new.png" width="20"></a> &nbsp;
                                  <a title="Tolak Data SPK" href="ajk_uploader_spak.php?r=tolak_spak&ids=' . $met_['id'] . '" onClick="if(confirm(\'Anda yakin untuk membatalkan data SPK ini ?\')){return true;}{return false;}"><img src="image/deleted.png" width="20"></a> &nbsp;';
              }elseif($q['level'] == "99" AND $q['status'] == "STAFF"){
                $dataceklist =  '<a title="Preview Data SPK" href="ajk_uploader_spak.php?r=vsett_spak&ids=' . $met_['id'] . '"><img src="image/new.png" width="20"></a> &nbsp;';
              }
            }else {
              $setting_fspak = '<a href="ajk_uploader_spak.php?r=sett_spak&ids=' . $met_['id'] . '"><img src="image/new.png" width="20"></a> &nbsp;
                                <a href="ajk_uploader_spak.php?r=dell_spak&ids=' . $met_['id'] . '" onClick="if(confirm(\'Apakah anda yakin membatalkan data SPK ini ?\')){return true;}{return false;}"><img src="image/delete.gif" width="15"></a> &nbsp;';
            }
          }else {}
          $x_file = str_replace(' ', '%20', $met_['fname']);

          if (!is_numeric($met_['input_by'])) {
            if ($met_['photo_spk'] == "") {
              $v_photo = '<img src="../image/non-user.png" width="50">';
              } else {
              $v_photo = '<a href="' . $metpath_file . '' . $met_['photo_spk'] . '" rel="lightbox" target="_blank"><img src="' . $metpath_file . '' . $met_['photo_spk'] . '" width="50"></a>';
              }
          }else{
            $v_photo = '<a href="../../ajkmobilescript/'.$met_formspk['filefotodebiturdua'].'" rel="lightbox" target="_blank"><img src="../../ajkmobilescript/'.$met_formspk['filefotodebiturdua'].'"  width="50">';
          }

          if (($no % 2) == 1) $objlass = 'tbl-odd'; else $objlass = 'tbl-even';
          if (is_numeric($met_['input_by'])) {
            $met_User = mysql_fetch_array($database->doQuery('SELECT id, nama, namalengkap FROM user_mobile WHERE id="'.$met_['input_by'].'"'));
            $inputby_met = $met_User['namalengkap'];
          }else{
            $inputby_met = $met_['input_by'];
          }

          if (is_numeric($met_['update_by'])) {
            $met_UserSPV = mysql_fetch_array($database->doQuery('SELECT id, nama, namalengkap FROM user_mobile WHERE id="'.$met_['update_by'].'"'));
            $updateby_met = $met_UserSPV['namalengkap'];
          }else{
            $updateby_met = $met_['update_by'];
          }

          echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
                <td align="center">' . (++$no + ($pageNow - 1) * 25) . '</td>
                <td align="center">' . $approve_spk . ' ' . $dataceklist . '</td>
                <td>' . strtoupper($met_company['name']) . '</td>
                <td>' . strtoupper($met_produk['nmproduk']) . '</td>
                <td align="center"><font color="blue">' . $met_formspk['nopermohonan'] . '</font></td>
                <td align="center">' . $met_['spak'] . '</td>
                <td align="center">' . $met_formspk['noidentitas'] . '</td>
                <td>' . strtoupper($_datamet) . '</td>
                <td align="center">' . _convertDate($met_formspk['dob']) . '</td>
                <td align="center">' . _convertDate($met_formspk['tgl_asuransi']) . '</td>
                <td align="center">' . $met_formspk['tenor'] . '</td>
                <td align="center">' . _convertDate($met_formspk['tgl_akhir_asuransi']) . '</td>
                <td align="center">' . $met_formspk['mpp'] . '</td>
                <td><a title="'.$met_['ket_ext'].'">' . substr($met_['ket_ext'], 0, 30) . '</a></td>
                <td align="right">' . duit($met_formspk['plafond']) . '</td>
                <td align="center"><b>' . $met_formspk['ratebank'] . '</b></td>
                <td align="right">' . duit($met_formspk['x_premi']) . '</td>
                <!--<td align="right">' . duit($cekNilaiPremi) . '</td>-->
                <td align="center">' . $met_formspk['x_usia'] . '</td>
                <td align="center">' . $met_['ext_premi'] . '</td>
                <td align="center"><a href=' . $metpath_file . '' . $x_file . ' target="_blank">'.$met_['fname'].'</a></td>
                <td align="center">' . $metCabang['name'] . '</td>
                <td align="center">' . $inputby_met . '</td>
                <td align="center">' . $met_['input_date'] . '</td>
                <td align="center">' . $updateby_met . '</td>
                <td align="center">' . $met_['update_date'] . '</td>
                <td align="center">' . $statusspknya . '</td>
                <td align="center">' . $v_photo . '</td>
                <td align="center">' . $setting_fspak . '</td>
                    </tr>';
        }

        if ($q['status'] == "UNDERWRITING" AND $q['supervisor'] == "0" AND $q['level'] == "99" OR $q['level'] == "1" AND $q['supervisor'] == "0") {
          $el = $database->doQuery('SELECT * FROM fu_ajk_spak WHERE status="Approve"');
          $met = mysql_num_rows($el);

          if ($met > 0) {
            echo '<tr><td colspan="28" align="center"><a href="ajk_uploader_spak.php?r=approve_spak_dokter" onClick="if(confirm(\'Apakah anda sudah yakin dengan semua data SPK ini ?\')){return true;}{return false;}"><input type="submit" name="ApproveClaim" Value="Approve"></a></td></tr>';
          } else {
            echo '';
          }
        } else {}
        echo '<tr><td colspan="22">';
        echo createPageNavigations($file = 'ajk_uploader_spak.php?r=set_spak&nospk=' . $_REQUEST['nospk'] . '', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
        echo '<b>Total Data SPK: <u>' . $totalRows . '</u></b></td></tr>';
        echo '</table><br>';

        if(!empty($_REQUEST['nospk']) || !empty($_REQUEST['namaspk'])){
          echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="95%" align="left" style="background-color: #666666;">Data SPK Kadaluarsa</font></th></tr></table>';
          echo '<form method="post" action="ajk_uploader_spak.php?r=approve_spak_dokter" onload ="onbeforeunload">
                <table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#bde0e6">
                <tr><th width="1%">No</th>
                <th>Perusahaan</th>
                <th width="1%">Produk</th>
                <th width="1%">No.Permohonan</th>
                <th width="1%">SPK</th>
                <th width="1%">No.Identitas</th>
                <th>Nama</th>
                <th width="1%">Tgl Lahir</th>
                <th width="1%">Tgl Akad</th>
                <th width="1%">Tenor</th>
                <th width="1%">Tgl Akhir</th>
                <th width="1%">Grace Period</th>
                <th width="20%">Keterangan</th>
                <th width="1%">Plafond</th>
                <th width="1%">Rate</th>
                <th width="1%">Premi (x)</th>
                <!--<th width="1%">Premi (Plafond*rate/mil)</th>-->
                <th width="1%">Usia (x)</th>
                <th width="1%">Ex.Premi<br />(%)</th>
                <th width="5%">File SPK</th>
                <th width="5%">Cabang</th>
                <th width="1%">User Upload</th>
                <th width="8%">Tgl Upload</th>
                <th width="1%">User Approve</th>
                <th width="8%">Tgl Approve</th>
                <th width="1%">Status</th>
                <th width="1%">Photo</th>
                <th width="5%">Option</th>
                </tr>';


          if ($_REQUEST['x']) { $m = ($_REQUEST['x'] - 1) * 25; } else {    $m = 0; }
          if ($q['level'] == "99" AND $q['status'] == "STAFF") {
            $met = $database->doQuery('SELECT fu_ajk_spak.*
                                        FROM fu_ajk_spak
                                        INNER JOIN fu_ajk_spak_form 
                                        ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
                                        where (fu_ajk_spak_form.nama like "%'.$_REQUEST['namaspk'].'%" and 
                                               fu_ajk_spak.spak like "%'.$_REQUEST['nospk'].'%") and  
                                               SUBSTR(spak,1,2)<>"MP" AND 
                                               status="Kadaluarsa"  AND 
                                               fu_ajk_spak.del IS NULL 
                                        ORDER BY fu_ajk_spak.id DESC 
                                        LIMIT ' . $m . ' , 25');
            $totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(fu_ajk_spak.id) 
                                                               FROM fu_ajk_spak
                                                               INNER JOIN fu_ajk_spak_form 
                                                               ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
                                                               WHERE (fu_ajk_spak_form.nama like "%'.$_REQUEST['namaspk'].'%" and 
                                                                      fu_ajk_spak.spak like "%'.$_REQUEST['nospk'].'%") and  
                                                                      SUBSTR(spak,1,2)<>"MP" AND 
                                                                      status="Kadaluarsa"  AND 
                                                                      fu_ajk_spak.del IS NULL 
                                                                ORDER BY fu_ajk_spak.id DESC'));
            $totalRows = $totalRows[0];
          }elseif ($q['level'] == "99" AND $q['status'] == "SUPERVISOR" AND $q['supervisor'] == "0") {
            $met = $database->doQuery('SELECT fu_ajk_spak.*
                                      FROM fu_ajk_spak
                                      INNER JOIN fu_ajk_spak_form 
                                      ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
                                      WHERE (fu_ajk_spak_form.nama like "%'.$_REQUEST['namaspk'].'%" and 
                                             fu_ajk_spak.spak like "%'.$_REQUEST['nospk'].'%") and  
                                             SUBSTR(spak,1,2)<>"MP" AND 
                                             status="Kadaluarsa"  AND 
                                             fu_ajk_spak.del IS NULL 
                                      ORDER BY fu_ajk_spak.id DESC LIMIT ' . $m . ' , 25');
            $totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(fu_ajk_spak.id) 
                                                               FROM fu_ajk_spak
                                                               INNER JOIN fu_ajk_spak_form 
                                                               ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
                                                               WHERE (fu_ajk_spak_form.nama like "%'.$_REQUEST['namaspk'].'%" and 
                                                                      fu_ajk_spak.spak like "%'.$_REQUEST['nospk'].'%") and  
                                                                      SUBSTR(spak,1,2)<>"MP" AND 
                                                                      status="Kadaluarsa"  AND 
                                                                      fu_ajk_spak.del IS NULL 
                                                                ORDER BY fu_ajk_spak.id DESC'));
            $totalRows = $totalRows[0];
          }else {
            $met = $database->doQuery('SELECT fu_ajk_spak.*
                                       FROM fu_ajk_spak
                                       INNER JOIN fu_ajk_spak_form ON 
                                                  fu_ajk_spak.id = fu_ajk_spak_form.idspk
                                       WHERE (fu_ajk_spak_form.nama like "%'.$_REQUEST['namaspk'].'%" and 
                                              fu_ajk_spak.spak like "%'.$_REQUEST['nospk'].'%") and  
                                              SUBSTR(spak,1,2)<>"MP" AND 
                                              status="Kadaluarsa"  AND 
                                              fu_ajk_spak.del IS NULL 
                                        ORDER BY fu_ajk_spak.id DESC LIMIT ' . $m . ' , 25');
            $totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(fu_ajk_spak.id) 
                                                               FROM fu_ajk_spak
                                                                INNER JOIN fu_ajk_spak_form ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
                                                                WHERE (fu_ajk_spak_form.nama like "%'.$_REQUEST['namaspk'].'%" and 
                                                                       fu_ajk_spak.spak like "%'.$_REQUEST['nospk'].'%") and  
                                                                       SUBSTR(spak,1,2)<>"MP" AND 
                                                                       status="Kadaluarsa"  AND 
                                                                       fu_ajk_spak.del IS NULL 
                                                                ORDER BY fu_ajk_spak.id DESC'));
            $totalRows = $totalRows[0];
          }

          $_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
          while ($met_ = mysql_fetch_array($met)) {
            $met_company = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="' . $met_['id_cost'] . '"'));
            $metdata = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE type_data="SPK" AND spaj="' . $met_['spak'] . '" AND del IS NULL'));
            $met_formspk = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_form WHERE idspk="' . $met_['id'] . '" AND del IS NULL'));
            $metCabang = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id="' . $met_formspk['cabang'] . '" AND del IS NULL'));
            $met_produk = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="' . $met_['id_polis'] . '" AND del IS NULL'));
            // CEK STATUS DATA SPK
            if ($met_['status'] == "Batal" OR $met_['status']=="Tolak") {   
              $statusspknya = '<font color="red">' . $met_['status'] . '</font>';   
            }else { 
              $statusspknya = '<font color="blue">' . $met_['status'] . '</font>';  
            }
            // CEK STATUS DATA SPK
            if ($metdata['spaj'] == $met_['spak']) {    
              $_datamet = $metdata['nama']; 
            }else { 
              $_datamet = $met_formspk['nama']; 
            }

            if ($q['status'] == "STAFF" OR $q['status'] == "SUPERVISOR" OR $q['status'] == "" OR $q['status'] == "UNDERWRITING") {
                $cekformspak = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_form WHERE idspk="' . $met_['id'] . '" AND del IS NULL'));
                if ($cekformspak['idspk'] == $met_['id']) {
                    $setting_fspak = '<a href="ajk_uploader_spak.php?r=edit_spak&ids=' . $met_['id'] . '&idform=' . $met_formspk['id'] . '"><img src="image/edit3.png" width="20"></a>';
                    if ($q['status'] == "SUPERVISOR") {
                        $setting_fspak = '<a href="ajk_uploader_spak.php?r=vsett_spak&ids=' . $met_['id'] . '"><img src="image/new.png" width="20"></a>';
                        if ($met_['status'] == "Batal") {
                            $approve_spk = '';
                        } else {
                            if ($met_['status'] == "Approve") { $metikonapprove = '<img src="image/ya2.png" width="15">';   }
                            else {  $metikonapprove = '<img src="image/ya.png" width="15">';    }
                            $approve_spk = '<a href="ajk_uploader_spak.php?r=vdelsett_spak&ids=' . $met_['id'] . '" onClick="if(confirm(\'Apakah anda yakin membatalkan atau revisi data SPK ini ?\')){return true;}{return false;}"><img src="image/delete.gif" width="15"></a> &nbsp;
                    <a href="ajk_uploader_spak.php?r=approve_spak&ids=' . $met_['id'] . '" onClick="if(confirm(\'Apakah anda yakin data SPK telah sesuai ?\')){return true;}{return false;}">' . $metikonapprove . '</a>';
                        }
                        $setting_fspak = '<a href="ajk_uploader_spak.php?r=edit_spak&ids=' . $met_['id'] . '&idform=' . $met_formspk['id'] . '"><img src="image/edit3.png" width="20"></a>';
                    }elseif ($q['status'] == "" OR $q['status'] == "UNDERWRITING" AND $q['supervisor'] == "0") {
                        $approve_spk = '<a href="ajk_uploader_spak.php?r=extpremi_spk&ids=' . $met_['id'] . '"><img src="image/plus.png" width="15"></a> &nbsp;';
                        $dataceklist = '<input type="checkbox" class="case" name="namaspk[]" value="' . $met_['id'] . '">';
                        $setting_fspak = '<a title="Preview Data SPK" href="ajk_uploader_spak.php?r=vsett_spak&ids=' . $met_['id'] . '"><img src="image/new.png" width="20"></a> &nbsp;
                                   &nbsp;';
                    }else { }
                } else {
                    $setting_fspak = '<a href="ajk_uploader_spak.php?r=sett_spak&ids=' . $met_['id'] . '"><img src="image/new.png" width="20"></a> &nbsp;
                    <a href="ajk_uploader_spak.php?r=dell_spak&ids=' . $met_['id'] . '" onClick="if(confirm(\'Apakah anda yakin membatalkan data SPK ini ?\')){return true;}{return false;}"><img src="image/delete.gif" width="15"></a> &nbsp;
                    ';
                }
            } else {    }

            $x_file = str_replace(' ', '%20', $met_['fname']);

            if (!is_numeric($met_['input_by'])) {
                if ($met_['photo_spk'] == "") {
                    $v_photo = '<img src="../image/non-user.png" width="50">';
                } else {
                    $v_photo = '<a href="' . $metpath_file . '' . $met_['photo_spk'] . '" rel="lightbox" target="_blank"><img src="' . $metpath_file . '' . $met_['photo_spk'] . '" width="50"></a>';
                }
            }else{
                $v_photo = '<a href="../../ajkmobilescript/'.$met_formspk['filefotodebiturdua'].'" rel="lightbox" target="_blank"><img src="../../ajkmobilescript/'.$met_formspk['filefotodebiturdua'].'"  width="50">';
            }

            if (($no % 2) == 1) $objlass = 'tbl-odd';   else $objlass = 'tbl-even';
            if (is_numeric($met_['input_by'])) {
                $met_User = mysql_fetch_array($database->doQuery('SELECT id, nama, namalengkap FROM user_mobile WHERE id="'.$met_['input_by'].'"'));
                $inputby_met = $met_User['namalengkap'];
            }else{
                $inputby_met = $met_['input_by'];
            }

            if (is_numeric($met_['update_by'])) {
                $met_UserSPV = mysql_fetch_array($database->doQuery('SELECT id, nama, namalengkap FROM user_mobile WHERE id="'.$met_['update_by'].'"'));
                $updateby_met = $met_UserSPV['namalengkap'];
            }else{
                $updateby_met = $met_['update_by'];
            }

            echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
                  <td align="center">' . (++$no + ($pageNow - 1) * 25) . '</td>
                  <td>' . strtoupper($met_company['name']) . '</td>
                  <td>' . strtoupper($met_produk['nmproduk']) . '</td>
                  <td align="center"><font color="blue">' . $met_formspk['nopermohonan'] . '</font></td>
                  <td align="center">' . $met_['spak'] . '</td>
                  <td align="center">' . $met_formspk['noidentitas'] . '</td>
                  <td>' . strtoupper($_datamet) . '</td>
                  <td align="center">' . _convertDate($met_formspk['dob']) . '</td>
                  <td align="center">' . _convertDate($met_formspk['tgl_asuransi']) . '</td>
                  <td align="center">' . $met_formspk['tenor'] . '</td>
                  <td align="center">' . _convertDate($met_formspk['tgl_akhir_asuransi']) . '</td>
                  <td align="center">' . $met_formspk['mpp'] . '</td>
                  <td><a title="'.$met_['ket_ext'].'">' . substr($met_['ket_ext'], 0, 30) . '</a></td>
                  <td align="right">' . duit($met_formspk['plafond']) . '</td>
                  <td align="center"><b>' . $met_formspk['ratebank'] . '</b></td>
                  <td align="right">' . duit($met_formspk['x_premi']) . '</td>
                  <!--<td align="right">' . duit($cekNilaiPremi) . '</td>-->
                  <td align="center">' . $met_formspk['x_usia'] . '</td>
                  <td align="center">' . $met_['ext_premi'] . '</td>
                  <td align="center"><a href=' . $metpath_file . '' . $x_file . ' target="_blank">'.$met_['fname'].'</a></td>
                  <td align="center">' . $metCabang['name'] . '</td>
                  <td align="center">' . $inputby_met . '</td>
                  <td align="center">' . $met_['input_date'] . '</td>
                  <td align="center">' . $updateby_met . '</td>
                  <td align="center">' . $met_['update_date'] . '</td>
                  <td align="center">' . $statusspknya . '</td>
                  <td align="center">' . $v_photo . '</td>
                  <td align="center">' . $setting_fspak . '</td>
                  </tr>';
          }

          echo '<tr><td colspan="22">';
          echo createPageNavigations($file = 'ajk_uploader_spak.php?r=set_spak&nospk=' . $_REQUEST['nospk'] . '', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
          echo '<b>Total Data SPK Kadaluarsa: <u>' . $totalRows . '</u></b></td></tr>';
          echo '</table>';
        }
    break;

    case "set_spaknew":
      echo '<table border="0" cellpadding="0" cellspacing="0" width="100%">
              <tr>
                <th width="95%" align="left">Modul Data SPK</font></th>
              </tr>
            </table>
            <table border="0" width="100%" cellpadding="1" cellspacing="1">
              <form method="post" action="">
                <tr>
                  <td align="right" width="10%">Nomor SPK :</td>
                  <td><input type="text" name="nospk" value="' . $_REQUEST['nospk'] . '"></td>
                </tr>
                <tr>
                  <td align="right" width="10%">Nama Debitur :</td>
                  <td><input type="text" name="namaspk" value="' . $_REQUEST['namaspk'] . '"><input type="submit" name="button" value="Cari" class="button"></td>
                </tr>
              </form>
            </table>';   
      //
      if ($_REQUEST['x']) { $m = ($_REQUEST['x'] - 1) * 25; } else {  $m = 0; }
      if ($q['level'] == "99" AND $q['status'] == "STAFF") {
      	$query = 'SELECT *
									FROM(
									SELECT fu_ajk_spak.*
									FROM fu_ajk_spak
									LEFT JOIN fu_ajk_spak_form 
									ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
                  where (fu_ajk_spak_form.nama like "%'.$_REQUEST['namaspk'].'%" and 
                         fu_ajk_spak.spak like "%'.$_REQUEST['nospk'].'%") and  
												 SUBSTR(spak,1,2)<>"MP" AND 
												 status = "Approve"  AND 
												 fu_ajk_spak.del IS NULL and
                                                 fu_ajk_spak_form.del is null

									UNION ALL

									select * 
									from fu_ajk_spak 
									where fu_ajk_spak.del IS NULL and 
                                          status= "Proses" and 
                                          SUBSTR(spak,1,1) <> "M"
									)as temp1
									ORDER BY temp1.update_date DESC';
				/*					
        $query = 'SELECT fu_ajk_spak.*
                  FROM fu_ajk_spak
                  INNER JOIN fu_ajk_spak_form 
                  ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
                  where (fu_ajk_spak_form.nama like "%'.$_REQUEST['namaspk'].'%" and 
                         fu_ajk_spak.spak like "%'.$_REQUEST['nospk'].'%") and  
                         SUBSTR(spak,1,2)<>"MP" AND 
                         status="Approve"  AND 
                         fu_ajk_spak.del IS NULL 
                  ORDER BY fu_ajk_spak.update_date DESC';*/
      }elseif ($q['level'] == "99" AND $q['status'] == "SUPERVISOR") {
        $query = 'SELECT fu_ajk_spak.*
                  FROM fu_ajk_spak
                  INNER JOIN fu_ajk_spak_form 
                  ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
                  where (fu_ajk_spak_form.nama like "%'.$_REQUEST['namaspk'].'%" and 
                         fu_ajk_spak.spak like "%'.$_REQUEST['nospk'].'%") and  
                         SUBSTR(spak,1,2)<>"MP" AND 
                         status="Approve"  AND 
                         fu_ajk_spak.del IS NULL and
                         fu_ajk_spak_form.del is null
                  ORDER BY fu_ajk_spak.update_date DESC';
      }elseif ($q['level'] == "99" AND $q['status'] == "UNDERWRITING"){
        $query = 'SELECT fu_ajk_spak.*
                  FROM fu_ajk_spak
                  INNER JOIN fu_ajk_spak_form 
                  ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
                  where (fu_ajk_spak_form.nama like "%'.$_REQUEST['namaspk'].'%" and 
                         fu_ajk_spak.spak like "%'.$_REQUEST['nospk'].'%") and  
                         SUBSTR(spak,1,2)<>"MP" AND 
                         status in ("Assign","Redirect","Propose")  AND 
                         fu_ajk_spak.del IS NULL and
                         fu_ajk_spak_form.del is null
                  ORDER BY fu_ajk_spak.id DESC';
        $extpremi_nm = '<th width="1%">Ex.Premi<br />(%)</th>';
        $assign_h = '<th width="20%">Keterangan Assign</th>';
      }elseif ($q['level'] == "99" AND $q['status'] == "DOKTER"){
       $query = 'SELECT fu_ajk_spak.*
                  FROM fu_ajk_spak
                  INNER JOIN fu_ajk_spak_form 
                  ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
                  where (fu_ajk_spak_form.nama like "%'.$_REQUEST['namaspk'].'%" and 
                         fu_ajk_spak.spak like "%'.$_REQUEST['nospk'].'%") and  
                         SUBSTR(spak,1,2)<>"MP" AND 
                         status="Redirect" AND 
                         fu_ajk_spak.del IS NULL and
                         fu_ajk_spak_form.del is null 
                  ORDER BY fu_ajk_spak.id DESC';
        $extpremi_nm = '<th width="1%">Ex.Premi<br />(%)</th>';
        $assign_h = '<th width="20%">Keterangan Assign</th>';
        $redirect_h = '<th width="20%">Keterangan Redirect</th>';
      }
      
      echo '<form method="post" action="ajk_uploader_spak.php?r=approve_spak_dokter" onload ="onbeforeunload">
              <table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#bde0e6">
                <tr>
                  <th width="1%">No</th>
                  <th width="5%">Option <input type="checkbox" id="selectall"/></th>
                  <th>Perusahaan</th>
                  <th width="1%">Produk</th>
                  <th width="1%">No.Permohonan</th>
                  <th width="5%">SPK</th>
                  <th width="1%">No.Identitas</th>
                  <th>Nama</th>
                  <th width="1%">Tgl Lahir</th>
                  <th width="1%">Tgl Akad</th>
                  <th width="1%">Tenor</th>
                  <th width="1%">Tgl Akhir</th>
                  <th width="1%">GP</th>
                  <th width="20%">Keterangan</th>
                  '.$assign_h.'
                  '.$redirect_h.'
                  <th width="1%">Plafond</th>
                  <th width="1%">Rate</th>
                  <th width="1%">Premi (x)</th>            
                  <th width="1%">Usia (x)</th>                  
                  '.$extpremi_nm.'
                  <th width="5%">File SPK</th>
                  <th width="5%">Cabang</th>
                  <th width="1%">User Input</th>
                  <th width="8%">Tgl Input</th>
                  <th width="1%">User Approve</th>
                  <th width="8%">Tgl Approve</th>
                  <th width="1%">Status</th>
                  <th width="1%">Photo</th>
                  <th width="5%">Option</th>
                </tr>'; 

      $_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
      $han = mysql_query($query);
      $totalRows = mysql_num_rows($han);
      while($han_ = mysql_fetch_array($han)){
        $han_company = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="' . $han_['id_cost'] . '"'));
        $handata = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE type_data="SPK" AND spaj="' . $han_['spak'] . '" AND del IS NULL'));
        $han_formspk = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_form WHERE idspk="' . $han_['id'] . '" AND del IS NULL'));
        $hanCabang = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id="' . $han_formspk['cabang'] . '" AND del IS NULL'));
        $han_produk = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="' . $han_['id_polis'] . '" AND del IS NULL'));

        // CEK STATUS DATA SPK
        if ($han_['status'] == "Batal" OR $han_['status']=="Tolak") { 
          $statusspknya = '<font color="red">' . $han_['status'] . '</font>'; 
        }else { 
          $statusspknya = '<font color="blue">' . $han_['status'] . '</font>';  
        }
        // CEK STATUS DATA SPK
        if ($handata['spaj'] == $han_['spak']) {  
          $namapeserta = $handata['nama']; 
        }else { 
          $namapeserta = $han_formspk['nama']; 
        }

        //AKSES ACTION
        if ($q['level'] == "99" AND $q['status'] == "STAFF"){
        	if($han_['status']=="Approve"){
        		$assign = '<a href="ajk_uploader_spak.php?r=spak_assign&ids=' . $han_['id'] . '" title="Assign"><img src="image/assign.png" width="15"></a> &nbsp;';          
          	$setting_fspak = '<a href="ajk_uploader_spak.php?r=vsett_spak&ids=' . $han_['id'] . '"><img src="image/new.png" width="20"></a> &nbsp;';
        	}elseif($han_['status']=="Proses"){
        		$assign='';
            $setting_fspak = '<a href="ajk_uploader_spak.php?r=sett_spak&ids=' . $han_['id'] . '"><img src="image/edit3.png" width="20"></a> &nbsp;
                              <a href="ajk_uploader_spak.php?r=dell_spak&ids=' . $han_['id'] . '" onClick="if(confirm(\'Apakah anda yakin membatalkan data SPK ini ?\')){return true;}{return false;}"><img src="image/delete.gif" width="15"></a> &nbsp;';

        	}
        }elseif ($q['level'] == "99" AND $q['status'] == "SUPERVISOR"){
          $assign = '<a href="ajk_uploader_spak.php?r=spak_assign&ids=' . $han_['id'] . '" title="Assign"><img src="image/assign.png" width="15"></a> &nbsp;';
          $setting_fspak = '<a href="ajk_uploader_spak.php?r=vsett_spak&ids=' . $han_['id'] . '"><img src="image/new.png" width="20"></a> &nbsp;';          
        }elseif ($q['level'] == "99" AND $q['status'] == "UNDERWRITING"){
          $extpremi = '<td align="center">' . $han_['ext_premi'] . '</td>';
          $setting_fspak = '<a href="ajk_uploader_spak.php?r=vsett_spak&ids=' . $han_['id'] . '"><img src="image/new.png" width="20"></a> &nbsp;';
          if($han_['status']=="Assign"){
            $approve_spk = '<a href="ajk_uploader_spak.php?r=extpremi_spk&ids=' . $han_['id'] . '"><img src="image/plus.png" width="15"></a> &nbsp;';  
          }else{
            $approve_spk = '';
          }          
          $redirect = '<a href="ajk_uploader_spak.php?r=spak_redirect&ids=' . $han_['id'] . '"><img src="image/redirect.png" width="15"></a> &nbsp;';          
          if(isset($han_['ext_premi']) and $han_['ext_premi'] != "" ){
            $dataceklist = '<input type="checkbox" class="case" name="namaspk[]" value="' . $han_['id'] . '">';    
          }else{
            $dataceklist = '';
          }          
          $btn_approve = '<tr><td colspan="28" align="center"><a href="ajk_uploader_spak.php?r=approve_spak_dokter" onClick="if(confirm(\'Apakah anda sudah yakin dengan semua data SPK ini ?\')){return true;}{return false;}"><input type="submit" name="ApproveClaim" Value="Approve"></a></td></tr>';
          $assign_d = '<td><a title="'.$han_['assign_note'].'">' . substr($han_['assign_note'], 0, 30) . '</a></td>';
          $rollback = '<a href="ajk_uploader_spak.php?r=rollback&ids=' . $han_['id'] . '"><img src="image/rollback.png" width="15"></a> &nbsp;';            
        }elseif ($q['level'] == "99" AND $q['status'] == "DOKTER"){
          $extpremi = '<td align="center">' . $han_['ext_premi'] . '</td>';
          $approve_spk = '<a href="ajk_uploader_spak.php?r=extpremi_spknew&ids=' . $han_['id'] . '"><img src="image/plus.png" width="15"></a> &nbsp;';  
          $assign_d = '<td><a title="'.$han_['assign_note'].'">' . substr($han_['assign_note'], 0, 30) . '</a></td>';
          $redirect_d = '<td><a title="'.$han_['redirect_note'].'">' . substr($han_['redirect_note'], 0, 30) . '</a></td>';
          $setting_fspak = '<a href="ajk_uploader_spak.php?r=vsett_spak&ids=' . $han_['id'] . '"><img src="image/new.png" width="20"></a> &nbsp;
                            <a title="Tolak Data SPK" href="ajk_uploader_spak.php?r=tolak_spak&ids=' . $han_['id'] . '" onClick="if(confirm(\'Anda yakin untuk membatalkan data SPK ini ?\')){return true;}{return false;}"><img src="image/deleted.png" width="20"></a> &nbsp;';
          $rollback = '<a href="ajk_uploader_spak.php?r=rollback&ids=' . $han_['id'] . '"><img src="image/rollback.png" width="15"></a> &nbsp;';  
        }

        $x_file = str_replace(' ', '%20', $han_['fname']);

        if (!is_numeric($han_['input_by'])) {
          if ($han_['photo_spk'] == "") {
            $v_photo = '<img src="../image/non-user.png" width="50">';
          }else{
            $v_photo = '<a href="' . $metpath_file . '' . $han_['photo_spk'] . '" rel="lightbox" target="_blank"><img src="' . $metpath_file . '' . $han_['photo_spk'] . '" width="50"></a>';
          }
        }else{
          $v_photo = '<a href="../../ajkmobilescript/'.$han_formspk['filefotodebiturdua'].'" rel="lightbox" target="_blank"><img src="../../ajkmobilescript/'.$han_formspk['filefotodebiturdua'].'"  width="50">';
        }
          
        if (is_numeric($han_['input_by'])) {
          $met_User = mysql_fetch_array($database->doQuery('SELECT id, nama, namalengkap FROM user_mobile WHERE id="'.$han_['input_by'].'"'));
          $inputby_met = $met_User['namalengkap'];
        }else{
          $inputby_met = $han_['input_by'];
        }

        if (is_numeric($han_['update_by'])) {
          $han_UserSPV = mysql_fetch_array($database->doQuery('SELECT id, nama, namalengkap FROM user_mobile WHERE id="'.$han_['update_by'].'"'));
          $updateby_met = $han_UserSPV['namalengkap'];
        }else{
          $updateby_met = $han_['update_by'];
        }

        if (($no % 2) == 1) $objlass = 'tbl-odd'; else $objlass = 'tbl-even';
        echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
                <td align="center">' . (++$no + ($pageNow - 1) * 25) . '</td>
                <td align="center">' . $approve_spk.$redirect.$dataceklist.$assign.'</td>
                <td>' . strtoupper($han_company['name']) . '</td>
                <td>' . strtoupper($han_produk['nmproduk']) . '</td>
                <td align="center"><font color="blue">' . $han_formspk['nopermohonan'] . '</font></td>
                <td align="center">' . $han_['spak'] . '</td>
                <td align="center">' . $han_formspk['noidentitas'] . '</td>
                <td>' . strtoupper($namapeserta) . '</td>
                <td align="center">' . _convertDate($han_formspk['dob']) . '</td>
                <td align="center">' . _convertDate($han_formspk['tgl_asuransi']) . '</td>
                <td align="center">' . $han_formspk['tenor'] . '</td>
                <td align="center">' . _convertDate($han_formspk['tgl_akhir_asuransi']) . '</td>
                <td align="center">' . $han_formspk['mpp'] . '</td>
                <td><a title="'.$han_['ket_ext'].'">' . substr($han_['ket_ext'], 0, 30) . '</a></td>
                '.$assign_d.'
                '.$redirect_d.'
                <td align="right">' . duit($han_formspk['plafond']) . '</td>
                <td align="center"><b>' . $han_formspk['ratebank'] . '</b></td>
                <td align="right">' . duit($han_formspk['x_premi']) . '</td>                
                <td align="center">' . $han_formspk['x_usia'] . '</td>                
                '.$extpremi.'
                <td align="center"><a href=' . $metpath_file . '' . $x_file . ' target="_blank">'.$han_['fname'].'</a></td>
                <td align="center">' . $hanCabang['name'] . '</td>
                <td align="center">' . $inputby_met . '</td>
                <td align="center">' . $han_['input_date'] . '</td>
                <td align="center">' . $updateby_met . '</td>
                <td align="center">' . $han_['update_date'] . '</td>
                <td align="center">' . $statusspknya . '</td>
                <td align="center">' . $v_photo . '</td>
                <td align="center">' . $setting_fspak . '</td>
              </tr>';
      }
      echo $btn_approve;
      echo '<tr><td colspan="22">';
      //echo createPageNavigations($file = 'ajk_uploader_spak.php?r=set_spaknew&nospk=' . $_REQUEST['nospk'] . '', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
      echo '<b>Total Data SPK: <u>' . $totalRows . '</u></b></td></tr>';
      echo '</table>';      
    break;

    case "spak_assign":
      $met_extpremi = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_form WHERE idspk="' . $_REQUEST['ids'] . '"'));
      $met_extpremispk = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak WHERE id="' . $met_extpremi['idspk'] . '"'));
      echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="95%" align="left">Modul Data Assign SPK</font></th>
          <th><a href="ajk_uploader_spak.php?r=set_spaknew"><img src="image/Backward-64.png" width="20"></tr></table>';
      if ($_REQUEST['ope'] == "Simpan") {                      
        $s = $database->doQuery('UPDATE fu_ajk_spak 
                                 SET  status = "Assign",
                                      assign_by="' . $q['nm_user'] . '",
                                      assign_date="' . $futgl . '",
                                      assign_note="' . $_REQUEST['assign_note'] . '"
                                 WHERE id="' . $met_extpremispk['id'] . '"');                      
        header("location:ajk_uploader_spak.php?r=set_spaknew");
      }
      echo '<table border="0" width="50%" align="center"><tr><td>
            <form method="POST" action="" class="input-list style-1 smart-green">
            <h1>Assign</h1>
            <label><span>Nomor SPK </span><input type="text" name="fname" value="' . $met_extpremispk['spak'] . '" size="30" DISABLED></label>
            <label><span>Nama Nasabah </span><input type="text" name="fname" value="' . strtoupper($met_extpremi['nama']) . '" size="30" DISABLED></label>            
            <label><span>Keterangan Staff Adonai</span><textarea name="assign_note" placeholder="Keterangan Assign" rows="10" cols="50" required>' . $met_extpremispk['assign_note'] . '</textarea><br />            
            </label>
            <label><span>&nbsp;</span><input type="submit" name="ope" value="Simpan" class="button" /></label>
            </form></td></tr></table>'; ;
    break;

    case "spak_redirect":
      $met_extpremi = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_form WHERE idspk="' . $_REQUEST['ids'] . '"'));
      $met_extpremispk = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak WHERE id="' . $met_extpremi['idspk'] . '"'));
      echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="95%" align="left">Modul Data Redirect SPK</font></th>
          <th><a href="ajk_uploader_spak.php?r=set_spaknew"><img src="image/Backward-64.png" width="20"></tr></table>';
      if ($_REQUEST['ope'] == "Simpan") {                      
        $s = $database->doQuery('UPDATE fu_ajk_spak 
                                 SET  status = "Redirect",
                                      redirect_by="' . $q['nm_user'] . '",
                                      redirect_date="' . $futgl . '",
                                      redirect_note="' . $_REQUEST['redirect_note'] . '"
                                 WHERE id="' . $met_extpremispk['id'] . '"');                      
        header("location:ajk_uploader_spak.php?r=set_spaknew");
      }
      echo '<table border="0" width="50%" align="center"><tr><td>
            <form method="POST" action="" class="input-list style-1 smart-green">
            <h1>Redirect</h1>
            <label><span>Nomor SPK </span><input type="text" name="fname" value="' . $met_extpremispk['spak'] . '" size="30" DISABLED></label>
            <label><span>Nama Nasabah </span><input type="text" name="fname" value="' . strtoupper($met_extpremi['nama']) . '" size="30" DISABLED></label>            
            <label><span>Keterangan '.$met_extpremispk['assign_by'].' </span><textarea name="assign_note" placeholder="Keterangan Redirect" rows="10" cols="50" DISABLED>' . $met_extpremispk['assign_note'] . '</textarea><br />            
            </label>
            <label><span>Keterangan Underwriting Adonai</span><textarea name="redirect_note" placeholder="Keterangan Redirect" rows="10" cols="50" required>' . $met_extpremispk['redirect_note'] . '</textarea><br />            
            </label>
            <label><span>&nbsp;</span><input type="submit" name="ope" value="Simpan" class="button" /></label>
            </form></td></tr></table>'; ;
    break;

    case "extpremi_spknew":
      $met_extpremi = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_form WHERE idspk="' . $_REQUEST['ids'] . '"'));
        $met_extpremispk = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak WHERE id="' . $met_extpremi['idspk'] . '"'));

      echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="95%" align="left">Modul Data Extra Premi SPK</font></th>
          <th><a href="'.$header.'"><img src="image/Backward-64.png" width="20"></tr></table>';
       if ($_REQUEST['ope'] == "Simpan") {
            if ($_REQUEST['ext_premi']=="") $error1 .= '<blink><font color=red>Silahkan masukan nilai extra premi</font></blink><br>';
            if ($_REQUEST['ket_ext_premi'] == "") $error2 .= '<blink><font color=red>Silahkan masukan keterangan extra premi</font></blink><br>';

            if ($error1 OR $error2) {
            }else {
              $s = $database->doQuery('UPDATE fu_ajk_spak 
                                       SET  status = "Propose",
                                            ext_premi="' . $_REQUEST['ext_premi'] . '",
                                            ket_ext="' . $_REQUEST['ket_ext_premi'] . '",
                                            approve_em_by="' . $q['nm_lengkap'] . '",
                                            approve_em_date="' . $futgl . '"
                                            WHERE id="' . $met_extpremispk['id'] . '"');

              header("location:ajk_uploader_spak.php?r=set_spaknew");             
            }
        }
        echo '<table border="0" width="50%" align="center"><tr><td>
              <form method="POST" action="" class="input-list style-1 smart-green">
              <h1>Table Extra Premi</h1>
              <label><span>Nomor SPK </span><input type="text" name="fname" value="' . $met_extpremispk['spak'] . '" size="30" DISABLED></label>
              <label><span>Nama Nasabah </span><input type="text" name="fname" value="' . strtoupper($met_extpremi['nama']) . '" size="30" DISABLED></label>
              <label><span>Extra Premi (%) <font color="red">*</font> ' . $error1. ' </span><input type="text" name="ext_premi" value="' . $met_extpremispk['ext_premi'] . '" placeholder="Extra Premi (%)" maxlength="3" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"></label>              
              <label><span>Keterangan Staff Adonai</span><textarea name="redirect_note" placeholder="Keterangan Redirect"  disabled>' . $met_extpremispk['redirect_note'] . '</textarea><br />
              <label><span>Keterangan Dokter Adonai<font color="red"> *</font>' .$error2. '</span><textarea name="ket_ext_premi" rows="10" cols="50">' . $met_extpremispk['ket_ext'] . '</textarea><br />              
              </label>
              <label><span>&nbsp;</span><input type="submit" name="ope" value="Simpan" class="button" /></label>
              </form></td></tr></table>'; ;
    break;

    case "dell_spak":
        echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="95%" align="left">Modul keterangan Pembatalan SPK Peserta</font></th>
	  <th><a href="ajk_uploader_spak.php?r=set_spak"><img src="image/back.png" width="20"></a></th></tr></table>';
        $cek_batal_spk = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak WHERE id="' . $_REQUEST['ids'] . '"'));
        $cek_batal_spk_nm = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_form WHERE idspk="' . $cek_batal_spk['id'] . '"'));
        if ($_REQUEST['ope'] == "Simpan") {
            $_REQUEST['batalspk'] = $_POST['batalspk'];
            if (!$_REQUEST['batalspk']) $error .= '<font color="red"><br />Silahkan isi alasan keterangan data pembatalan peserta!</font>';
            if ($error) {
            }else {
                $batal_spk = mysql_fetch_array($database->doQuery('UPDATE fu_ajk_spak SET status="Batal", keterangan="' . $_REQUEST['batalspk'] . '" WHERE id="' . $_REQUEST['ids'] . '"'));
                $mail = new PHPMailer; // call the class
                $mail->IsSMTP();
                $mail->Host = SMTP_HOST; //Hostname of the mail server
                $mail->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
                $mail->Username = SMTP_UNAME; //Username for SMTP authentication any valid email created in your domain
                $mail->Password = SMTP_PWORD; //Password for SMTP authentication
                $mail->AddReplyTo = SMTP_REPLYMAIL; //reply-to address
            	$mail->debug = 1;
            	$mail->SMTPSecure = "ssl";
                $mail->SetFrom ($q['email'], $q['nm_lengkap']); //From address of the mail
                $mail->Subject = "AJKOnline - Data Batal SPK"; //Subject od your mail
                $message .= '<table border="0" width="100% cellpadding="1" cellspacing="1">
			<tr><td>SPK nomor ' . $cek_batal_spk['spak'] . ' telah dibatalkan oleh <b>' . $_SESSION['nm_user'] . ' pada tanggal ' . $futgl . '</td></tr>
			<tr><td><b>Keterangan Pembatalan</b><br />' . $_REQUEST['batalspk'] . '</td></tr>
			</table>';
                // EMAIL PENERIMA  KANTOR U/W
                $mailsupervisorajk = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="" AND level="' . $q['level'] . '" AND status="SUPERVISOR" AND del IS NULL');
                while ($_mailsupervisorajk = mysql_fetch_array($mailsupervisorajk)) {
                    $mail->AddAddress($_mailsupervisorajk['email'], $_mailsupervisorajk['nm_lengkap']); //To address who will receive this email
                }
                // EMAIL PENERIMA  KANTOR U/W
                // EMAIL PENERIMA  KANTOR U/W
                $mailclient = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="' . $cek_batal_spk['id_cost'] . '" AND id_polis="' . $cek_batal_spk['id_polis'] . '" AND nm_user="' . $cek_batal_spk['input_by'] . '" AND del IS NULL');
                while ($_mailclient = mysql_fetch_array($mailclient)) {
                    $mail->AddAddress($_mailclient['email'], $_mailclient['nm_lengkap']); //To address who will receive this email
                }
                // EMAIL PENERIMA  KANTOR U/W
                //$mail->AddBCC("adonai.notif@gmail.com");
            	$mail->AddBCC("rahmad@adonaits.co.id");
                // $mail->AddAddress($mailsupervisorajk['email'], $mailsupervisorajk['nm_lengkap']); //To address who will receive this email
                // $mail->AddCC($approvemail);
                $mail->MsgHTML($message); //Put your body of the message you can place html code here
                $send = $mail->Send(); //Send the mails
                // echo $mail;
                header("location:ajk_uploader_spak.php?r=set_spak");
            }
        }
        echo '<table border="0" width="50%" align="center">
	  <tr><td>
	  	<form name="f1" method="post" class="input-list style-1 smart-green">
		<h1>Pembatalan SPK nomor  ' . $cek_batal_spk['spak'] . ' a/n ' . $cek_batal_spk_nm['nama'] . '</h1>
		<label><span>Keterangan<font color="red">*</font> ' . $error . '</span><textarea name="batalspk">' . $_REQUEST['batalspk'] . '</textarea></label>
		<label><span>&nbsp;</span><input type="submit" name="ope" value="Simpan" class="button" /></label>
		</form>
	  </td></tr>
	  </table>'; ;
        break;

    case "tolak_spak":
    	$cek_batal_spk = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak WHERE id="' . $_REQUEST['ids'] . '"'));
    	$cek_batal_spk_nm = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_form WHERE idspk="' . $cek_batal_spk['id'] . '"'));
    	if(substr($cek_batal_spk['spak'], 0,2)=='MP'){
    		$header='ajk_uploader_spak.php?r=set_spak_mp';
    	}else{

    		$header='ajk_uploader_spak.php?r=set_spaknew';
    	}

        echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="95%" align="left">Modul keterangan Penolakan SPK Peserta</font></th>
	  <th><a href="'.$header.'"><img src="image/back.png" width="20"></a></th></tr></table>';
        if ($_REQUEST['ope'] == "Simpan") {
            $_REQUEST['batalspk'] = $_POST['batalspk'];
            if (!$_REQUEST['batalspk']) $error .= '<font color="red"><br />Silahkan isi alasan keterangan data ditolak peserta!</font>';
            if ($error) {
            }else {
                $batal_spk = mysql_fetch_array($database->doQuery('UPDATE fu_ajk_spak SET status="Tolak", keterangan="' . $_REQUEST['batalspk'] . '" WHERE id="' . $_REQUEST['ids'] . '"'));
                $mail = new PHPMailer; // call the class
                $mail->IsSMTP();
                $mail->Host = SMTP_HOST; //Hostname of the mail server
                $mail->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
                $mail->Username = SMTP_UNAME; //Username for SMTP authentication any valid email created in your domain
                $mail->Password = SMTP_PWORD; //Password for SMTP authentication
                $mail->AddReplyTo = SMTP_REPLYMAIL; //reply-to address
            	$mail->debug = 1;
            	$mail->SMTPSecure = "ssl";
                $mail->SetFrom ($q['email'], $q['nm_lengkap']); //From address of the mail
                $mail->Subject = "AJKOnline - Data Batal SPK"; //Subject od your mail
                $message .= '<table border="0" width="100% cellpadding="1" cellspacing="1">
				<tr><td>SPK nomor ' . $cek_batal_spk['spak'] . ' telah ditolak oleh <b>' . $_SESSION['nm_user'] . ' pada tanggal ' . $futgl . '</td></tr>
				<tr><td><b>Keterangan Penolakan</b><br />' . $_REQUEST['batalspk'] . '</td></tr>
				</table>';
                // EMAIL PENERIMA  KANTOR U/W
                $mailsupervisorajk = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="" AND level="' . $q['level'] . '" AND status="SUPERVISOR" AND del IS NULL');
                while ($_mailsupervisorajk = mysql_fetch_array($mailsupervisorajk)) {
                    $mail->AddAddress($_mailsupervisorajk['email'], $_mailsupervisorajk['nm_lengkap']); //To address who will receive this email
                }
                // EMAIL PENERIMA  KANTOR U/W
                // EMAIL PENERIMA  KANTOR U/W
                $mailclient = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="' . $cek_batal_spk['id_cost'] . '" AND id_polis="' . $cek_batal_spk['id_polis'] . '" AND nm_user="' . $cek_batal_spk['input_by'] . '" ADN del IS NULL');
                while ($_mailclient = mysql_fetch_array($mailclient)) {
                    $mail->AddAddress($_mailclient['email'], $_mailclient['nm_lengkap']); //To address who will receive this email
                }
                // EMAIL PENERIMA  KANTOR U/W
                //$mail->AddBCC("adonai.notif@gmail.com");
            	$mail->AddBCC("rahmad@adonaits.co.id");
                // $mail->AddAddress($mailsupervisorajk['email'], $mailsupervisorajk['nm_lengkap']); //To address who will receive this email
                // $mail->AddCC($approvemail);
                $mail->MsgHTML($message); //Put your body of the message you can place html code here
                $send = $mail->Send(); //Send the mails
                // echo $mail;
                header("location:".$header."");
            }
        }
        echo '<table border="0" width="50%" align="center">
			  <tr><td>
			  	<form name="f1" method="post" class="input-list style-1 smart-green">
				<h1>SPK ditolak nomor  ' . $cek_batal_spk['spak'] . ' a/n ' . $cek_batal_spk_nm['nama'] . '</h1>
				<label><span>Keterangan<font color="red">*</font> ' . $error . '</span><textarea name="batalspk">' . $_REQUEST['batalspk'] . '</textarea></label>
				<label><span>&nbsp;</span><input type="submit" name="ope" value="Simpan" class="button" /></label>
				</form>
			  </td></tr>
			  </table>'; ;
        break;

    case "approve_spak_dokter":
        if (!$_REQUEST['namaspk']) {
            echo '<center><font color=red><blink>Tidak ada data SPK yang di pilih, silahkan ceklist data yang akan di Approve. !</blink></font><br/>
	  <a href="ajk_uploader_spak.php?r=set_spak">Kembali Ke Halaman Approve Data SPK</a></center>';
        } else {
            $mail = new PHPMailer; // call the class
            $mail->IsSMTP();
            $mail->Host = SMTP_HOST; //Hostname of the mail server
            $mail->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
            $mail->Username = SMTP_UNAME; //Username for SMTP authentication any valid email created in your domain
            $mail->Password = SMTP_PWORD; //Password for SMTP authentication
            $mail->AddReplyTo = SMTP_REPLYMAIL; //reply-to address
        	$mail->debug = 1;
        	$mail->SMTPSecure = "ssl";
			$mail->SetFrom ($q['email'], $q['nm_lengkap']); //From address of the mail
            $mail->Subject = "AJKOnline - APPROVE DATA SPK"; //Subject od your mail

            $cekbatch = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_batch ORDER BY id DESC'));
            $fakbatch = $cekbatch['id'] + 1;
            $idkode = 100000000 + $fakbatch;
            $idkode2 = substr($idkode, 1); // ID PESERTA //
            $kodebatch = 'B.' . date("ymd") . '.' . $idkode2;
            $batchadonai_met = $database->doQuery('INSERT INTO fu_ajk_batch SET idb="' . $idkode2 . '", no_batch="' . $kodebatch . '", input_by="' . $q['nm_user'] . '", input_time="' . $futgl . '"');
            $cekbatch_met = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_batch ORDER BY id DESC'));

            $message .= '<table border="0" width="100%" cellpadding="1" cellspacing="3">
			<tr><td colspan="2">Nomor Batch</td><td colspan="6"><b>' . $kodebatch . '</b></td></tr>
			<tr bgcolor="aqua">
				<td width="1%" align="center">NO</td>
				<td align="center" width="10%">NOMOR SPK</td>
				<td align="center">NAMA</td>
				<td width="10%" align="center">USIA</td>
				<td width="10%" align="center">PREMI</td>
				<td align="center">EXT.PREMI (%)</td>
				<td align="center">EXT.PREMI</td>
				<td align="center">TOTAL PREMI</td>
			</tr>';

foreach($_REQUEST['namaspk'] as $k => $val) {
$_met_data_ = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak WHERE id="' . $val . '"'));
$_met_data_spk = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_form WHERE idcost="' . $_met_data_['id_cost'] . '" AND idspk="' . $_met_data_['id'] . '"'));
// echo $_met_data_spk['tgl_asuransi'].'<br />';

//20160927 tidak dilakukan perhitungan kembali 04102016
//$xpremi = $_met_data_spk['plafond'] * $_met_data_spk['ratebank'] / 1000;
//x_premi , x_premi = "'.$xpremi.'"
//20160927
if ($_met_data_spk['tgl_asuransi'] == "0000-00-00") {
	$met_tgl_akhir = date('Y-m-d', strtotime('+' . $_met_data_spk['tenor'] . ' year', strtotime($futoday))); //operasi penjumlahan tanggal
    $metUpdatetglAkad = $database->doQuery('UPDATE fu_ajk_spak_form SET tgl_asuransi="'.$futoday.'",
    																	tgl_akhir_asuransi="'.$met_tgl_akhir.'"
                    						WHERE idcost="' . $_met_data_['id_cost'] . '" AND
                    							  idspk="' . $_met_data_['id'] . '"');
} else {	}
$nilai_premi_spk = $_met_data_spk['x_premi'] * $_met_data_['ext_premi'] / 100;
$nett_premi_spk = $_met_data_spk['x_premi'] + $nilai_premi_spk;
$met_spk = $database->doQuery('UPDATE fu_ajk_spak SET status="Aktif",
													  id_batch="'.$cekbatch_met['id'].'",
													  approve_by="'.$q['nm_user'].'",
													  approve_date="'.$futgl.'"
								WHERE id="'.$val.'"');
$message .= '<tr><td align="center">' . ++$no . '</td>
				<td align="center">' . $_met_data_['spak'] . '</td>
				<td>' . $_met_data_spk['nama'] . '</td>
				<td align="center">' . $_met_data_spk['x_usia'] . '</td>
				<td align="right">' . duit($_met_data_spk['x_premi']) . '</td>
				<td align="center">' . duit($_met_data_['ext_premi']) . '%</td>
				<td align="center">' . duit($nilai_premi_spk) . '</td>
				<td align="right">' . duit($nett_premi_spk) . '</td>
			</tr>';
}
$message .= '</table>';

            // EMAIL STAFF SPK

/*
$mailstaff = $database->doQuery('SELECT * FROM user_mobile WHERE id="'.$_met_data_['input_by'].'"');
            while ($mailstaff_ = mysql_fetch_array($mailstaff)) {
                $mail->AddAddress($mailstaff_['email'], $mailstaff_['namalengkap']); //To address who will receive this email
			}
*/
        	$_mailstaff = mysql_fetch_array($database->doQuery('SELECT * FROM user_mobile WHERE id="'.$_met_data_['input_by'].'"'));
            $mail->AddAddress($_mailstaff['email'], $_mailstaff['namalengkap']); //To address who will recei
            //echo $_mailstaff['email'].'<br />';
			// EMAIL STAFF SPK
        	// EMAIL SPV SPK

/*
      	$cekuser = mysql_fetch_array($database->doQuery('SELECT id FROM user_mobile WHERE id="'.$_met_data_['input_by'].'"'));
        	$mailspv = $database->doQuery('SELECT * FROM user_mobile WHERE id="'.$cekuser['supervisor'].'"');
        	while ($mailspv_ = mysql_fetch_array($mailspv)) {
        		$mail->AddAddress($mailspv_['email'], $mailspv_['namalengkap']); //To address who will receive this email
        	}
*/
        	$_mailspv = mysql_fetch_array($database->doQuery('SELECT * FROM user_mobile WHERE id="'.$_mailstaff['supervisor'].'"'));
        	$mail->AddAddress($_mailspv['email'], $_mailspv['namalengkap']); //To address who will recei
        	//echo $_mailspv['email'].'<br />';

        	// EMAIL SPV SPK

            //$mail->AddBCC("adonai.notif@gmail.com");
            $mail->AddBCC("rahmad@adonaits.co.id");
            $mail->MsgHTML('<center>Data SPK telah di approve oleh <b>' . $_SESSION['nm_user'] . ' selaku Dokter PT Adonai AJK-Online pada tanggal ' . $futgl . '</center>' . $message);
            $send = $mail->Send(); //Send the mails

            //echo $message;
            //echo '<center>Approve data SPK dengan nomor Batch '.$kodebatch.' oleh <b>'.$_SESSION['nm_user'].'</b> telah berhasil.<br /> <a href="ajk_uploader_spak.php?r=set_spak">Kembali ke Modul SPK.</a></center>';
	    echo '<center>Approve data SPK oleh <b>'.$_SESSION['nm_user'].'</b> telah berhasil.<br /> <a href="ajk_uploader_spak.php?r=set_spak">Kembali ke Modul SPK.</a></center>';
        } ;
        break;

    case "extpremi_spk":
    	$met_extpremi = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_form WHERE idspk="' . $_REQUEST['ids'] . '"'));
        $met_extpremispk = mysql_fetch_array($database->doQuery('SELECT *,f_autoem_ket(id)as autoem_ket FROM fu_ajk_spak WHERE id="' . $met_extpremi['idspk'] . '"'));

    	if(substr($met_extpremispk['spak'],0,2)!=='MP'){
    		$header="ajk_uploader_spak.php?r=set_spaknew";
    	}else{
    		$header="ajk_uploader_spak.php?r=set_spak_mp";
    	}
        echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="95%" align="left">Modul Data Extra Premi SPK</font></th>
		<th><a href="'.$header.'"><img src="image/Backward-64.png" width="20"></tr></table>';
       if ($_REQUEST['ope'] == "Simpan") {
            if ($_REQUEST['ext_premi']=="") $error1 .= '<blink><font color=red>Silahkan masukan nilai extra premi</font></blink><br>';
            if ($_REQUEST['ket_ext_premi'] == "") {
                $met_ket = 'Premi Standar';
            } else {
                $met_ket = $_REQUEST['ket_ext_premi'];
            }

            if($q['status'] == "UNDERWRITING" and $_REQUEST['ext_premi'] > "50"){
              $error2 .= '<blink><font color=red>Nilai extra premi harus di bawah 50%</font></blink><br>';
            }

            // if (!$_REQUEST['ket_ext_premi'])  $error2 .='<blink><font color=red>Silahkan masukan keterangan nilai extra premi</font></blink><br>';
            if ($error1 OR $error2) {
            }else {
                $s = $database->doQuery('UPDATE fu_ajk_spak SET ext_premi="' . $_REQUEST['ext_premi'] . '",
													  ket_ext="' . $met_ket . '",
									  				  approve_em_by="' . $q['nm_lengkap'] . '",
									  				  approve_em_date="' . $futgl . '"
									  				  WHERE id="' . $met_extpremispk['id'] . '"');
              /*
            	$berkas = fopen("historyedit.txt", "a") or die ("File history tidak ada.");
            	$asli__ = "(EM)\t" . $met_extpremispk['id_cost'] . " - " . $met_extpremispk['id_polis'] . " - " . $met_extpremispk['spak'] . " - " . $met_extpremispk['ext_premi'] . " - " . $met_extpremispk['approve_em_by'] . " - " . $met_extpremispk['approve_em_date'] . "";
            	fwrite($berkas, $asli__ . "\r\n");
            	$revisi__ = "(EM)\t" . $met_extpremispk['id_cost'] . " - " . $met_extpremispk['id_polis'] . " - " . $met_extpremispk['spak'] . " - " . $_REQUEST['ext_premi'] . " - " . $q['nm_lengkap'] . " - " . $futgl . "";
            	fwrite($berkas, $revisi__ . "\r\n");
            	fclose($berkas);
							*/
				$mail = new PHPMailer; // call the class
                $mail->IsSMTP();
                $mail->Host = SMTP_HOST; //Hostname of the mail server
                $mail->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
                $mail->Username = SMTP_UNAME; //Username for SMTP authentication any valid email created in your domain
                $mail->Password = SMTP_PWORD; //Password for SMTP authentication
                $mail->AddReplyTo = SMTP_REPLYMAIL; //reply-to address
            	$mail->debug = 1;
            	$mail->SMTPSecure = "ssl";

                $mail->SetFrom ($q['email'], $q['nm_lengkap']); //From address of the mail
                $mail->Subject = "AJKOnline - APPROVE PESERTA EXTRA PREMI AJK ONLINE"; //Subject od your mail
                // EMAIL PENERIMA BANK
                $mailsupervisorajk = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="' . $met_extpremispk['id_cost'] . '" AND id_polis="' . $met_extpremispk['id_polis'] . '" AND level="' . $q['level'] . '"');
                while ($_mailsupervisorajk = mysql_fetch_array($mailsupervisorajk)) {
                    $mail->AddAddress($_mailsupervisorajk['email'], $_mailsupervisorajk['nm_lengkap']); //To address who will receive this email
                }
                // EMAIL PENERIMA BANK
                // EMAIL PENERIMA BANK
                $mailadonaispk = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="" AND id_polis="" AND level="' . $q['level'] . '" AND status="SUPERVISOR" AND del IS NULL');
                while ($mailadonaispk_ = mysql_fetch_array($mailadonaispk)) {
                    $mail->AddAddress($mailadonaispk_['email'], $mailadonaispk_['nm_lengkap']); //To address who will receive this email
                }
                // EMAIL PENERIMA BANK
                //$mail->AddBCC("adonai.notif@gmail.com");
            	$mail->AddBCC("rahmad@adonaits.co.id");
                $mail->MsgHTML('<table><tr><th>Data peserta extra premi dengan nomor SPK ' . $met_extpremispk['spak'] . ' telah di input oleh <b>' . $_SESSION['nm_user'] . ' selaku Dokter PT Adonai AJK-Online pada tanggal ' . $futgl . '</tr></table>' . $message); //Put your body of the message you can place html code here
                $send = $mail->Send(); //Send the mails
                //echo $message;
                if(substr($met_extpremispk['spak'],0,2)!=='MP'){
                	header("location:ajk_uploader_spak.php?r=set_spaknew");
                }else{
                	header("location:ajk_uploader_spak.php?r=set_spak_mp");
                }
            }
        }

        //$ket_system = $met_extpremispk['autoem_ket'];
        $ket_system = str_replace(",", '&#13;&#10;', $met_extpremispk['autoem_ket']);

        echo '<table border="0" width="50%" align="center"><tr><td>
		<form method="POST" action="" class="input-list style-1 smart-green">
		<h1>Table Extra Premi</h1>
		<label><span>Nomor SPK </span><input type="text" name="fname" value="' . $met_extpremispk['spak'] . '" size="30" DISABLED></label>
		<label><span>Nama Nasabah </span><input type="text" name="fname" value="' . strtoupper($met_extpremi['nama']) . '" size="30" DISABLED></label>
		<label><span>Extra Premi (%) <font color="red">*</font> ' . $error1 . '</span><input type="text" name="ext_premi" value="' . $met_extpremispk['ext_premi'] . '" placeholder="Extra Premi (%)" maxlength="3" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"></label>
        <label>
            <span>Keterangan '.$met_extpremispk['assign_by'].' </span>
            <textarea name="assign_note" placeholder="Keterangan Redirect" rows="10" cols="50" DISABLED>' . $met_extpremispk['assign_note'] . '</textarea><br />
        </label>
        <label>
            <span>Keterangan System</span>
            <textarea name="note_system" placeholder="Keterangan System" rows="10" cols="50" DISABLED>' . $ket_system . '</textarea><br />
        </label>        
		<label><span>Keterangan Dokter Adonai</span><textarea name="ket_ext_premi" placeholder="Keterangan Extra Premi" rows="10" cols="50">' . $met_extpremispk['ket_ext'] . '</textarea><br />
		<strong>Default keterangan Premi Standar apabila kolom keterangan tidak diisi.<strong>
		</label>
		<label><span>&nbsp;</span><input type="submit" name="ope" value="Simpan" class="button" /></label>
		</form></td></tr></table>'; ;
        break;

    case "vsett_spak":
    	$metFormSPK = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_form WHERE idspk="' . $_REQUEST['ids'] . '" AND del IS NULL'));
    	if ($metFormSPK['jns_kelamin'] == "M") {	$gender = "Laki-Laki";	} else {	$gender = "Perempaun";	}

    	$metDataSPK = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak WHERE id="' . $metFormSPK['idspk'] . '" AND del IS NULL'));

    	if(substr($metDataSPK['spak'],0,2)!=='MP'){
    		$header="ajk_uploader_spak.php?r=set_spaknew";
    	}else{
    		$header="ajk_uploader_spak.php?r=set_spak_mp";
    	}
 echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="95%" align="left">Modul Priview Data SPK</font></th>
	  <th><a href="'.$header.'"><img src="image/back.png" width="20"></a></th></tr></table>';
// echo $_REQUEST['ids'];

$cekdokter = mysql_fetch_array($database->doQuery('SELECT id, namalengkap FROM user_mobile WHERE id="'.$metFormSPK['dokter_pemeriksa'].'"'));
$cekstaff = mysql_fetch_array($database->doQuery('SELECT id, namalengkap FROM user_mobile WHERE id="'.$metFormSPK['input_by'].'"'));

if (is_numeric($metFormSPK['input_by'])) {
	$met_User = mysql_fetch_array($database->doQuery('SELECT id, nama, namalengkap FROM user_mobile WHERE id="'.$metFormSPK['input_by'].'"'));
	$inputby_met = $met_User['namalengkap'];
}else{
	$inputby_met = $metFormSPK['dokter'];
}

if (is_numeric($metFormSPK['cabang'])) {
	$met_Cabang = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_cabang WHERE id="'.$metFormSPK['cabang'].'"'));
	$inputcabang = $met_Cabang['name'];
}else{
	$inputcabang = $metFormSPK['cabang'];
}

echo '<table border="0" width="80%" cellpadding="3" cellspacing="1" align="center">
	  <tr><td width="20%">Nomor SPK SPK</td><td>: <b>'.$metDataSPK['spak'].'</b></td></tr>
	  <tr><td width="20%">User Upload/Input SPK</td><td>: ' . strtoupper($inputby_met) . '</td></tr>
	  <tr><td>Nama Nasabah</td><td>: <b>' . strtoupper($metFormSPK['nama']) . '</b></td></tr>
	  <tr><td>Jenis kelamin</td><td>: ' . $gender . '</td></tr>
	  <tr><td>Tanggal Lahir</td><td>: ' . _convertDate($metFormSPK['dob']) . '</td></tr>
	  <tr><td>Alamat</td><td>: ' . nl2br($metFormSPK['alamat']) . '</td></tr>
	  <tr><td>Pekerjaan</td><td>: ' . $metFormSPK['pekerjaan'] . '</td></tr>
	  <tr><th colspan="2" class="judulhead1">Questioner Kesehatan</th></tr>
	  <tr><td colspan="2">Apakah anda dalam jangka 5 tahun terakhir ini pernah atau sedang menderita penyakit, Asma, Cacat, Tumor/Kanker, TBC, Kencing manis, Paru-paru, Hati, Ginjal, Jantung, Stroke, Tekanan Darah Tinggi, Epilepsi, Gangguan Jiwa, Penyakit Autoimun, Keterbelakngan Mental atau Idiot. Jika "Ya", Jelaskan.</td></tr>
	  <tr><td valign="top" colspan="2">Jawaban : ' . $metFormSPK['pertanyaan1'] . '<br />' . $metFormSPK['ket1'] . '</td></tr>
	  <tr><td colspan="2">Apakah Berat badan Anda berubah dalam 12 bulan terakhir ini, Jika "Ya", Jelaskan.</td></tr>
	  <tr><td valign="top" colspan="2">Jawaban : ' . $metFormSPK['pertanyaan2'] . '<br />' . $metFormSPK['ket2'] . '</td></tr>
	  <tr><td colspan="2">Apakah anda menderita HIV/AIDS?</td></tr>
	  <tr><td valign="top" colspan="2">Jawaban : ' . $metFormSPK['pertanyaan3'] . '<br />' . $metFormSPK['ket3'] . '</td></tr>
	  <tr><td colspan="2">Apakah anda mengkonsumsi rutin (ktergantungan) pada Narkoba? Jika "Ya", Sebutkan jenisnya?</td></tr>
	  <tr><td valign="top" colspan="2">Jawaban : ' . $metFormSPK['pertanyaan4'] . '<br />' . $metFormSPK['ket4'] . '</td></tr>
	  <tr><td colspan="2"><b>Khusus untuk Wanita</b>, apakah anda sedang hamil ? Jika "Ya" adalah komplikasi / penyakit kehamilan ? jelaskan? Usia Kandungan?</td></tr>
	  <tr><td valign="top" colspan="2">Jawaban : ' . $metFormSPK['pertanyaan5'] . '<br />' . $metFormSPK['ket5'] . '</td></tr>
	  <tr><td colspan="2">Apakah anda seorang perokok? Jika "Ya" berapa batang perhari?</td></tr>
	  <tr><td valign="top" colspan="2">Jawaban : ' . $metFormSPK['pertanyaan6'] . '<br />' . $metFormSPK['ket6'] . '</td></tr>
	  <tr><th colspan="2" class="judulhead1">Pemeriksaan Kesehatan</th></tr>
	  <tr><td>Nama Dokter Pemeriksa</td><td>: ' . strtoupper($cekdokter['namalengkap']) . '</td></tr>
	  <tr><td>Tinggi Badan</td><td>: ' . $metFormSPK['tinggibadan'] . '</td></tr>
	  <tr><td>Berat Badan</td><td>: ' . $metFormSPK['beratbadan'] . '</td></tr>
	  <tr><td>Tekanan Darah</td><td>: ' . $metFormSPK['tekanandarah'] . '</td></tr>
	  <tr><td>Nadi</td><td>: ' . $metFormSPK['nadi'] . '</td></tr>
	  <tr><td>Pernafasan</td><td>: ' . $metFormSPK['pernafasan'] . '</td></tr>
	  <tr><td>Gula Darah</td><td>: ' . $metFormSPK['guladarah'] . '</td></tr>
	  <tr><td>Kesimpulan</td><td>: ' . nl2br($metFormSPK['kesimpulan']) . '</td></tr>
	  <tr><td>Catatan</td><td>: ' . $metFormSPK['catatan'] . '</td></tr>
	  <tr><td>Tanggal Periksa</td><td>: ' . _convertDate($metFormSPK['tgl_periksa']) . '</td></tr>
	  <tr><th colspan="2" class="judulhead1">Asurani Kredit</th></tr>
	  <tr><td>Plafond</td><td>: ' . duit($metFormSPK['plafond']) . '</td></tr>
	  <tr><td>Tanggal Asuransi</td><td>: ' . _convertDate($metFormSPK['tgl_asuransi']) . '</td></tr>
	  <tr><td>Tenor</td><td>: ' . $metFormSPK['tenor'] . ' tahun</td></tr>
	  <tr><td>Tanggal Akhir Asuransi</td><td>: ' . _convertDate($metFormSPK['tgl_akhir_asuransi']) . '</td></tr>
	  <tr><td>Premi</td><td>: ' . duit($metFormSPK['x_premi']) . '</td></tr>
	  <tr><td>Usia</td><td>: ' . duit($metFormSPK['x_usia']) . ' tahun</td></tr>
	  <tr><td>Cabang</td><td>: ' . $inputcabang . '</td></tr>
	  <tr><td>Input Data</td><td>: ' . strtoupper($cekstaff['namalengkap']) . '</td></tr>
	  <tr><td>Tanggal Input Data</td><td>: ' . $metFormSPK['input_date'] . '</td></tr>
	  <tr><td>Nilai EM</td><td>: ' . $metDataSPK['ext_premi'] . '%</td></tr>
	  <tr><td valign="top">Keterangan EM</td><td>: ' . $metDataSPK['ket_ext'] . '</td></tr>
	  </table><hr size="1">';
if ($metFormSPK['filefotodebitursatu']!="") {
echo '<table border="0" width="80%" cellpadding="3" cellspacing="1" align="center">
	  <tr><td width="20%">Foto Debitur<br />
	  		<a href="../../ajkmobilescript/'.$metFormSPK['filefotodebitursatu'].'" rel="thumbnail">
			<img src="../../ajkmobilescript/'.$metFormSPK['filefotodebitursatu'].'" style="width: 75px; height: 75px"> &nbsp;
			<a href="../../ajkmobilescript/'.$metFormSPK['filefotodebiturdua'].'" rel="thumbnail">
			<img src="../../ajkmobilescript/'.$metFormSPK['filefotodebiturdua'].'" style="width: 75px; height: 75px">
		  </td>
	  	  <td width="20%">Foto KTP<br />
	  	    <a href="../../ajkmobilescript/'.$metFormSPK['filefotoktp'].'" rel="thumbnail">
	  	  	<img src="../../ajkmobilescript/'.$metFormSPK['filefotoktp'].'" style="width: 75px; height: 75px">
		  </td>
		  <td width="20%">SK Pensiun<br />
	  	    <a href="../../ajkmobilescript/'.$metFormSPK['filefotoskpensiun'].'" rel="thumbnail">
	  	  	<img src="../../ajkmobilescript/'.$metFormSPK['filefotoskpensiun'].'" style="width: 75px; height: 75px">
		  </td>
	  	  <td width="15%">TTD Debitur<br />
	  	    <a href="../../ajkmobilescript/'.$metFormSPK['filettddebitur'].'" rel="thumbnail">
	  	  	<img src="../../ajkmobilescript/'.$metFormSPK['filettddebitur'].'" style="width: 75px; height: 75px">
		  </td>
	  	  <td width="15%">TTD Marketing<br />
	  	    <a href="../../ajkmobilescript/'.$metFormSPK['filettdmarketing'].'" rel="thumbnail">
	  	  	<img src="../../ajkmobilescript/'.$metFormSPK['filettdmarketing'].'" style="width: 75px; height: 75px">
		  </td>
	  	  <td width="20%">TTD Dokter Cabang<br />
	  	    <a href="../../ajkmobilescript/'.$metFormSPK['filettddokter'].'" rel="thumbnail">
	  	  	<img src="../../ajkmobilescript/'.$metFormSPK['filettddokter'].'" style="width: 75px; height: 75px">
		  </td>
	  </tr>
	  </table>';
}else{
echo '<Center><b>Data photo tidak ada, data diinput melalui aplikasi web.</b></center>';
}

    	;
        break;

    case "approve_spak":
        echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="95%" align="left">Modul Approve Data SPK</font></th></tr></table>';
        $spkdokter = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak WHERE id=' . $_REQUEST['ids'] . ''));
        $mail = new PHPMailer; // call the class
        $mail->IsSMTP();
        $mail->Host = SMTP_HOST; //Hostname of the mail server
        $mail->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
        $mail->Username = SMTP_UNAME; //Username for SMTP authentication any valid email created in your domain
        $mail->Password = SMTP_PWORD; //Password for SMTP authentication
        $mail->AddReplyTo = SMTP_REPLYMAIL; //reply-to address
    	$mail->debug = 1;
    	$mail->SMTPSecure = "ssl";

        $mail->SetFrom ($q['email'], $q['nm_lengkap']); //From address of the mail
        $mail->Subject = "AJKOnline - APPROVE DATA SPK"; //Subject od your mail
        // EMAIL SPV SPK
        $mailstaff = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="" AND level="' . $q['level'] . '" AND status="SUPERVISOR"');
        while ($mailstaff_ = mysql_fetch_array($mailstaff)) {
            $mail->AddAddress($mailstaff_['email'], $mailstaff_['nm_lengkap']); //To address who will receive this email
        }
        // EMAIL SPV SPK
        //$mail->AddBCC("adonai.notif@gmail.com");
        $mail->AddBCC("rahmad@adonaits.cp.id");
        $mail->MsgHTML('<table><tr><th>Nomor SPK ' . $spkdokter['spak'] . ' telah di Approved oleh <b>' . $_SESSION['nm_user'] . ' selaku Supervisor AJK-Online pada tanggal ' . $futgl . '</tr></table>' . $message); //Put your body of the message you can place html code here
        $send = $mail->Send(); //Send the mails
        $_met_spak = $database->doQuery('UPDATE fu_ajk_spak SET status="Approve" WHERE id="' . $_REQUEST['ids'] . '"');
        header("location:ajk_uploader_spak.php?r=set_spak"); ;
        break;

    case "vdelsett_spak":
        $spkdokter = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak WHERE id=' . $_REQUEST['ids'] . ''));
        $mail = new PHPMailer; // call the class
        $mail->IsSMTP();
        $mail->Host = SMTP_HOST; //Hostname of the mail server
        $mail->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
        $mail->Username = SMTP_UNAME; //Username for SMTP authentication any valid email created in your domain
        $mail->Password = SMTP_PWORD; //Password for SMTP authentication
        $mail->AddReplyTo = SMTP_REPLYMAIL; //reply-to address
    	$mail->debug = 1;
    	$mail->SMTPSecure = "ssl";

        $mail->SetFrom ($q['email'], $q['nm_lengkap']); //From address of the mail
        $mail->Subject = "AJKOnline - DISAPPROVED DATA SPK '" . $spkdokter['spak'] . "'"; //Subject od your mail
        // EMAIL SPV SPK
        $mailstaff = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="" AND level="' . $q['level'] . '" AND status="SUPERVISOR" AND del IS NULL');
        while ($mailstaff_ = mysql_fetch_array($mailstaff)) {
            $mail->AddAddress($mailstaff_['email'], $mailstaff_['nm_lengkap']); //To address who will receive this email
        }
        // EMAIL SPV SPK
        //$mail->AddBCC("adonai.notif@gmail.com");
        $mail->AddBCC("rahmad@adonaits.co.id");
        $mail->MsgHTML('<table><tr><th>Nomor SPK ' . $spkdokter['spak'] . ' telah DISAPPROVED oleh <b>' . $_SESSION['nm_user'] . ' selaku Supervisor AJK-Online pada tanggal ' . $futgl . '</tr></table>' . $message); //Put your body of the message you can place html code here
        $send = $mail->Send(); //Send the mails
        $_met_spak = $database->doQuery('UPDATE fu_ajk_spak SET status="Pending" WHERE id="' . $_REQUEST['ids'] . '"');
        header("location:ajk_uploader_spak.php?r=set_spak"); ;
        break;

    case "sett_spak":
echo '<table border="0" cellpadding="0" cellspacing="0" width="100%">
      <tr><th width="95%" align="left">Modul Data spk</font></th><th><a href="ajk_uploader_spak.php?r=set_spaknew"><img src="image/back.png" width="20"></a></th></tr>
      </table>';
$spkdokter = mysql_fetch_array($database->doQuery('SELECT *, DATE_FORMAT(input_date,"%Y-%m-%d") AS tglinput FROM fu_ajk_spak WHERE id=' . $_REQUEST['ids'] . ''));
if (is_numeric($spkdokter['input_by'])) {
	$met_User = mysql_fetch_array($database->doQuery('SELECT id, nama, namalengkap FROM user_mobile WHERE id="'.$spkdokter['input_by'].'"'));
	$inputby_met = $met_User['namalengkap'];
}else{
	$inputby_met = $spkdokter['input_by'];
}
if ($_REQUEST['ope'] == "Simpan") {
            if ($_REQUEST['spk_nama'] == "") {	$error_1 = '<font color="red"><blink>Silahkan input nama debitur.</font>';	}
            if ($_REQUEST['spk_dob'] == "") { $error_2 = '<font color="red"><blink>Silahkan isi tanggal lahir debitur.<br /></font>';	}
            if ($_REQUEST['spk_alamat'] == "") {	$error_3 = '<font color="red"><blink>Silahkan isi alamat debitur.<br /></font>';	}
            if ($_REQUEST['qk_1'] == "") {	$error_4 = '<font color="red"><blink>Silahkan pilih pertanyaan "ya" atau "tidak".<br /></font>';	}
            if ($_REQUEST['qk_2'] == "") {	$error_5 = '<font color="red"><blink>Silahkan pilih pertanyaan "ya" atau "tidak".<br /></font>';	}
            if ($_REQUEST['qk_3'] == "") {	$error_6 = '<font color="red"><blink>Silahkan pilih pertanyaan "ya" atau "tidak".<br /></font>';	}
            if ($_REQUEST['qk_4'] == "") {	$error_7 = '<font color="red"><blink>Silahkan pilih pertanyaan "ya" atau "tidak".<br /></font>';	}
            if ($_REQUEST['qk_5'] == "") {	$error_8 = '<font color="red"><blink>Silahkan pilih pertanyaan "ya" atau "tidak".<br /></font>';	}
            if ($_REQUEST['qk_6'] == "") {	$error_9 = '<font color="red"><blink>Silahkan pilih pertanyaan "ya" atau "tidak".<br /></font>';	}
            if ($_REQUEST['spk_tglcheck'] == "") {	$error_10 = '<font color="red"><blink>Silahkan isi tanggal pemeriksaan.<br /></font>';	}
            if ($_REQUEST['spk_plafond'] == "") {	$error_12 = '<font color="red"><blink>Silahkan isi jumlah pinjaman.<br /></font>';	}
            if ($_REQUEST['spk_tglakad'] == "") {	$error_13 = '<font color="red"><blink>Silahkan isi tanggal awal asuransi.<br /></font>';	}
            if ($_REQUEST['spk_nmcabbank'] == "") {	$error_14 = '<font color="red"><blink>Silahkan isi cabang debitur.<br /></font>';	}
            if ($_REQUEST['dokter_pemeriksa'] == "") {	$error_18 = '<font color="red"><blink>Silahkan pilih nama dokter pemeriksa.<br /></font>';	}

            $admpolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id_cost="' . $spkdokter['id_cost'] . '" AND id="' . $spkdokter['id_polis'] . '"'));
            // $umur = ceil(((strtotime($_REQUEST['spk_tglakad']) - strtotime($_REQUEST['spk_dob'])) / (60*60*24*365.2425)));	// FORMULA USIA
            $met_Date = datediff($_REQUEST['spk_tglakad'], $_REQUEST['spk_dob']);
            // $mets = datediff($_REQUEST['spk_tglakad'], $_REQUEST['spk_dob']);	// 16 februari 2015
            // if ($mets['months'] >= 5 ) {	$umur = $mets['years'] + 1;	}else{	$umur = $mets['years'];	}	// 16 februari 2015
            $met_Date_ = explode(",", $met_Date);
            // echo $met_Date_[0].'<br />';
            // echo $met_Date_[1].'<br />';
            // echo $met_Date_[2].'<br />';
            // echo $_REQUEST['spk_dob'].'<br />';
            // echo $_REQUEST['spk_tglakad'].' <br />';
            if ($met_Date_[1] >= 6) {	$umur = $met_Date_[0] + 1;	} else {	$umur = $met_Date_[0];	}
            // FORMULA USIA

        	//CEK PLAFOND UMUT YG SEKARANG TGL AKAD
			$cekplafond = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_medical WHERE id_cost="' . $spkdokter['id_cost'] . '" AND id_polis="' . $spkdokter['id_polis'] . '"'));
            if ($umur > $cekplafond['age_from'] AND str_replace(".", "", $_REQUEST['spk_plafond']) > $cekplafond['si_to']) {
                $error_15 = '<font color="red"><blink>Nilai tenor melewati batas maksimum table underwriting.<br /></font>';
            }
			//CEK PLAFOND UMUT YG SEKARANG TGL AKAD

            if ($_REQUEST['spk_jwaktu'] == "") {	$error_11 = '<font color="red"><blink>Jangka Waktu tidak boleh kosong.<br /></font>';	}
			else {
                //$mettenornya = $_REQUEST['spk_jwaktu'] / 12;
				if ($admpolis['mpptype']=="Y") {
					$cekrate_tenor = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="' . $spkdokter['id_cost'] . '" AND id_polis="' . $spkdokter['id_polis'] . '" AND tenor="' .$_REQUEST['spk_jwaktu']. '" AND '.$_REQUEST['mppbln'].' BETWEEN mpp_s AND mpp_e AND status="baru" AND "'.$spkdokter['tglinput'].'" BETWEEN eff_from AND eff_to AND del IS NULL')); // RATE PREMI
					if (!$cekrate_tenor['rate']) {
						$error_11 = '<font color="red"><blink>Jumlah jangka waktu tidak sesuai rate produk MPP.<br /></font>';
					}
				}else{
                	$cekrate_tenor = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="' . $spkdokter['id_cost'] . '" AND id_polis="' . $spkdokter['id_polis'] . '" AND usia="' . $umur . '" AND tenor="' .$_REQUEST['spk_jwaktu']. '" AND status="baru" AND "'.$spkdokter['tglinput'].'" BETWEEN eff_from AND eff_to AND del IS NULL')); // RATE PREMI
                	if ($cekrate_tenor['tenor'] != $_REQUEST['spk_jwaktu']) {
                    	$error_11 = '<font color="red"><blink>Jumlah jangka waktu tidak sesuai rate produk.<br /></font>';
	                }
				}
				$met_tgl_akhir = date('Y-m-d', strtotime('+' . $_REQUEST['spk_jwaktu'] . ' year', strtotime($_REQUEST['spk_tglakad']))); //tanggal akhir asuransi
				$met_Date_Akhir = datediff($met_tgl_akhir, $_REQUEST['spk_dob']);
				$metUsiaAkhirKredit = explode(",", $met_Date_Akhir);
				//if ($metUsiaAkhirKredit[0] > $admpolis['age_max'] + 1 AND $metUsiaAkhirKredit[1] <= 5) {
				if (($umur + $_REQUEST['spk_jwaktu']) > $admpolis['age_max'] + 1) {
					$error_11 = '<font color="red"><blink>Usia '.$metUsiaAkhirKredit[0].'thn melebihi batas masksimum usia, data ditolak.!!!</font>';
				}
			}

			//CEK PLAFOND UMUR PADA TABLE MEDICAL
        	$plafondnya__ = str_replace(".", "", $_REQUEST['spk_plafond']);
			if ($plafondnya__=="" OR $plafondnya__ <= 0) {
				$plafondnya__= 0;
			}else{
				$plafondnya__ = $plafondnya__;
			}
        	$cekplafondakhir = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_medical WHERE id_cost="' . $spkdokter['id_cost'] . '" AND
        																								id_polis="' . $spkdokter['id_polis'] . '" AND
        																								'.$umur.' BETWEEN age_from AND age_to AND
        																								'.$plafondnya__.' BETWEEN si_from AND si_to
        																								'));
        	if (!$cekplafondakhir) {	$error_16 = '<font color="red"><blink>Nilai Plafond melewati batas maksimum table underwriting.<br /></font>';	}
        	//CEK PLAFOND UMUR PADA TABLE MEDICAL

			if ($admpolis['mpptype']=="Y" AND $_REQUEST['mppbln']=="") {	$error_17 = '<font color="red"><blink>Silahkan isi jumlah masa pra pensiun (mpp).<br /></font>';	}

//CEK DATA IDENTIK DEBITUR (NAMA, DOB, PLAFOND, CABANG) BISA DENGAN SPK YANG SAMA ATAU SPK YANG BERBEDA
$cekIdentik = mysql_fetch_array($database->doQuery('SELECT fu_ajk_spak_form.id,
															fu_ajk_spak_form.nama,
															fu_ajk_spak_form.dob,
															fu_ajk_spak_form.plafond,
															fu_ajk_spak_form.cabang,
															fu_ajk_spak.spak,
															fu_ajk_spak.`status`
															FROM fu_ajk_spak_form
															INNER JOIN fu_ajk_spak ON fu_ajk_spak_form.idspk = fu_ajk_spak.id
															WHERE fu_ajk_spak_form.nama ="'.$_REQUEST['spk_nama'].'" AND
																	fu_ajk_spak_form.dob="'.$_REQUEST['spk_dob'].'" AND
																	fu_ajk_spak_form.plafond="'.$plafondnya__.'" AND
																	fu_ajk_spak_form.cabang="'.$_REQUEST['spk_nmcabbank'].'" AND
																	fu_ajk_spak_form.del IS NULL'));
//if ($cekIdentik['status']!="Batal" OR $cekIdentik['status']!="Tolak") { direvisi validasi tgl 01112016
if ($cekIdentik['status']=="Tolak") {
	if ($cekIdentik['id']) {	$error_19 = '<font color="red"><blink>Data debitur identik (nama debitur, tanggal lahir, plafond dan cabang) data tidak bisa di proses.</blink></font>';	}
}else{	}
//CEK DATA IDENTIK DEBITUR (NAMA, DOB, PLAFOND, CABANG) BISA DENGAN SPK YANG SAMA ATAU SPK YANG BERBEDA

if ($error_1 OR $error_2 OR $error_3 OR $error_4 OR $error_5 OR $error_6 OR $error_7 OR $error_8 OR $error_9 OR $error_10 OR $error_11 OR $error_12 OR $error_13 OR $error_14 OR $error_15 OR $error_16 OR $error_17 OR $error_18 OR $error_19) {
}else {
                // MENENTUKAN PERHITUNGAN PREMI BERDASARKAN SINGLE RATE PADA POLIS
            	//$cekrate = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="' . $spkdokter['id_cost'] . '" AND id_polis="' . $spkdokter['id_polis'] . '" AND usia="' . $umur . '" AND tenor="' . $_REQUEST['spk_jwaktu'] . '"')); // RATE PREMI
            	if ($admpolis['singlerate'] == "T") {
            		if ($admpolis['mpptype']=="Y") {
            			$cekrate = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="' . $spkdokter['id_cost'] . '" AND id_polis="' . $spkdokter['id_polis'] . '" AND tenor="' .$_REQUEST['spk_jwaktu']. '" AND '.$_REQUEST['mppbln'].' BETWEEN mpp_s AND mpp_e AND status="baru" AND "'.$spkdokter['tglinput'].'" BETWEEN eff_from AND eff_to AND del IS NULL')); // RATE PREMI
            		}else{
                    	$cekrate = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="' . $spkdokter['id_cost'] . '" AND id_polis="' . $spkdokter['id_polis'] . '" AND tenor="' . $_REQUEST['spk_jwaktu'] . '" AND status="baru" AND "'.$spkdokter['tglinput'].'" BETWEEN eff_from AND eff_to AND del IS NULL')); // RATE PREMI
                	}
				} else {
                	if ($admpolis['mpptype']=="Y") {
                		$tenorSPKMPP = $_REQUEST['spk_jwaktu'];
                		$cekrate = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="' . $spkdokter['id_cost'] . '" AND id_polis="' . $spkdokter['id_polis'] . '" AND '.$_REQUEST['mppbln'].' BETWEEN mpp_s AND mpp_e AND tenor="' . $tenorSPKMPP . '" AND status="baru" AND "'.$spkdokter['tglinput'].'" BETWEEN eff_from AND eff_to AND del IS NULL')); // RATE PREMI
/*
                		if ($metFormSPK['mpp'] < $admpolis['mppbln_min']) {
                			$cekrate = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="' . $spkdokter['id_cost'] . '" AND id_polis="' . $spkdokter['id_polis'] . '" AND usia="' . $umur . '" AND tenor="' . $_REQUEST['spk_jwaktu'] . '" AND status="lama"')); // RATE PREMI
                		}else{
                			$tenorSPKMPP = $_REQUEST['spk_jwaktu'] * 12;
                			$cekrate = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="' . $spkdokter['id_cost'] . '" AND id_polis="' . $spkdokter['id_polis'] . '" AND '.$metFormSPK['mpp'].' BETWEEN mpp_s AND mpp_e AND tenor="' . $tenorSPKMPP . '" AND status="baru" AND del IS NULL')); // RATE PREMI
                		}
*/
                	}else{
                		$cekrate = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="' . $spkdokter['id_cost'] . '" AND id_polis="' . $spkdokter['id_polis'] . '" AND usia="' . $umur . '" AND tenor="' . $_REQUEST['spk_jwaktu'] . '" AND status="baru" AND "'.$spkdokter['tglinput'].'" BETWEEN eff_from AND eff_to AND del IS NULL')); // RATE PREMI
                	}
                }
                // MENENTUKAN PERHITUNGAN PREMI BERDASARKAN SINGLE RATE PADA POLIS
                $plafondnya = str_replace(".", "", $_REQUEST['spk_plafond']);
                $premi = $plafondnya * $cekrate['rate'] / 1000;

				$metExtPremi = $premi * $spkdokter['ext_premi'] / 100;	//HITUNG EXTRA PREMI
            	$mettotal_ = ROUND($premi + $metExtPremi);
                if ($mettotal_ < $admpolis['min_premium']) {
                	//$premi_x = $admpolis['min_premium'];	revisi 12 oktober 2016 minimum premi dihitung dari premi asli + em < minimumpremi di pdf
                	$premi_x = $mettotal_;
                } else {
                    $premi_x = $mettotal_;
                }
                $met_tgl_akhir = date('Y-m-d', strtotime('+' . $_REQUEST['spk_jwaktu'] . ' year', strtotime($_REQUEST['spk_tglakad']))); //tanggal akhir asuransi
                //echo 'dob '.$_REQUEST['spk_dob'].'<br />';
                //echo 'awal kredit '.$_REQUEST['spk_tglakad'].'<br />';
                //echo 'thn '.$met_Date.'<br />';
            	//echo 'akhir kredit '.$met_tgl_akhir.'<br />';
            	$met_Date_Akhir = datediff($met_tgl_akhir, $_REQUEST['spk_dob']);
            	//echo 'akhir usia kreditnya '.$met_Date_Akhir.'<br />';
            	$metUsiaAkhirKredit = explode(",", $met_Date_Akhir);
				if ($metUsiaAkhirKredit[0] >= $admpolis['age_max'] + 1 AND $metUsiaAkhirKredit[1] <= 5) {
					$met_tgl_akhir_Explode = date('Y-m-d',strtotime('- '.$metUsiaAkhirKredit[1].' month',strtotime($met_tgl_akhir)));
					$met_tgl_akhir_ = explode("-", $met_tgl_akhir_Explode);
					$met_tgl_dob_ = explode("-", $_REQUEST['spk_dob']);
					//echo 'thn '.$met_tgl_akhir_[0].'<br />';
					//echo 'bln '.$met_tgl_akhir_[1].'<br />';
					//echo 'hri '.$met_tgl_akhir_[2].'<br />';
					$met_tgl_akhir = $met_tgl_akhir_[0].'-'.$met_tgl_akhir_[1].'-'.$met_tgl_dob_[2];
				}else{
					$met_tgl_akhir = $met_tgl_akhir;
				}
				//$tgl_pinjam=$met_tgl_akhir;
				//$tgl_kembali_bulanan=date('Y-m-d',strtotime('-3 month',strtotime($tgl_pinjam)));	FORMAT KURANG TANGGAL PADA BULAN

$metrefundcn = $database->doQuery('INSERT INTO fu_ajk_spak_form SET idcost="' . $spkdokter['id_cost'] . '",
												   					dokter="' . $spkdokter['input_by'] . '",
												   					idspk="' . $spkdokter['id'] . '",
												   					nama="' . strtoupper($_REQUEST['spk_nama']) . '",
												   					jns_kelamin="' . $_REQUEST['spk_sex'] . '",
												   					dob="' . $_REQUEST['spk_dob'] . '",
												   					alamat="' . $_REQUEST['spk_alamat'] . '",
												   					pekerjaan="' . strtoupper($_REQUEST['spk_pekerjaan']) . '",
												   					pertanyaan1="' . $_REQUEST['qk_1'] . '",
												   					ket1="' . $_REQUEST['spk_ket_qk1'] . '",
												   					pertanyaan2="' . $_REQUEST['qk_2'] . '",
												   					ket2="' . $_REQUEST['spk_ket_qk2'] . '",
												   					pertanyaan3="' . $_REQUEST['qk_3'] . '",
												   					ket3="' . $_REQUEST['spk_ket_qk3'] . '",
												   					pertanyaan4="' . $_REQUEST['qk_4'] . '",
												   					ket4="' . $_REQUEST['spk_ket_qk4'] . '",
												   					pertanyaan5="' . $_REQUEST['qk_5'] . '",
												   					ket5="' . $_REQUEST['spk_ket_qk5'] . '",
												   					pertanyaan6="' . $_REQUEST['qk_6'] . '",
												   					ket6="' . $_REQUEST['spk_ket_qk6'] . '",
												   					dokter_pemeriksa="' . $_REQUEST['dokter_pemeriksa'] . '",
												   					tinggibadan="' . $_REQUEST['spk_tbadan'] . '",
												   					beratbadan="' . $_REQUEST['spk_bbadan'] . '",
												   					tekanandarah="' . $_REQUEST['spk_tdarah'] . '",
												   					nadi="' . $_REQUEST['spk_nadi'] . '",
												   					pernafasan="' . $_REQUEST['spk_nafas'] . '",
												   					guladarah="' . $_REQUEST['spk_guladarah'] . '",
												   					kesimpulan="' . $_REQUEST['periksa_kesehatan'] . '",
												   					catatan="' . $_REQUEST['catatan'] . '",
												   					tgl_periksa="' . $_REQUEST['spk_tglcheck'] . '",
												   					plafond="' . $plafondnya . '",
												   					tgl_asuransi="' . $_REQUEST['spk_tglakad'] . '",
												   					tenor="' . $_REQUEST['spk_jwaktu'] . '",
												   					mpp="' . $_REQUEST['mppbln'] . '",
												   					tgl_akhir_asuransi="' . $met_tgl_akhir . '",
												   					ratebank="' . $cekrate['rate'] . '",
												   					x_premi="' . $premi_x . '",
												   					x_usia="' . $umur . '",
												   					cabang="' . $_REQUEST['spk_nmcabbank'] . '",
												   					input_by="' . $_SESSION['nm_user'] . '",
												   					input_date="' . $futgl . '"');

                $mail = new PHPMailer; // call the class
                $mail->IsSMTP();
                $mail->Host = SMTP_HOST; //Hostname of the mail server
                $mail->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
                $mail->Username = SMTP_UNAME; //Username for SMTP authentication any valid email created in your domain
                $mail->Password = SMTP_PWORD; //Password for SMTP authentication
                $mail->AddReplyTo = SMTP_REPLYMAIL; //reply-to address
            	$mail->debug = 1;
            	$mail->SMTPSecure = "ssl";

                $mail->SetFrom ($q['email'], $q['nm_lengkap']); //From address of the mail
                $mail->Subject = "AJKOnline - APPROVE DATA SPK"; //Subject od your mail
                // EMAIL SPV SPK
                $mailstaff = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="" AND level="' . $q['level'] . '" AND status="SUPERVISOR" AND del IS NULL');
                while ($mailstaff_ = mysql_fetch_array($mailstaff)) {
                    $mail->AddAddress($mailstaff_['email'], $mailstaff_['nm_lengkap']); //To address who will receive this email
                }
                // EMAIL SPV SPK
                //$mail->AddBCC("adonai.notif@gmail.com");
            	//$mail->AddBCC("rahmad@adonaits.co.id");
                $mail->MsgHTML('<table><tr><th>Nomor SPK ' . $spkdokter['spak'] . ' telah diinput oleh <b>' . $_SESSION['nm_user'] . ' selaku Staff AJK-Online pada tanggal ' . $futgl . '</tr></table>' . $message); //Put your body of the message you can place html code here
				$send = $mail->Send(); //Send the mails
echo '<center><h2>Data SPK telah diinput oleh ' . $q['nm_lengkap'] . ' pada tanggal ' . $futgl . '.</h2></center><meta http-equiv="refresh" content="3;URL=ajk_uploader_spak.php?r=set_spaknew">';
            }
        }
$checkDokter = $database->doQuery('SELECT * FROM user_mobile WHERE type="Dokter" AND del IS NULL ORDER by namalengkap ASC');
echo '<form name="f1" method="post" action="">
	  <table border="0" cellpadding="1" cellspacing="1" width="85%" align="center">
	  <tr><td colspan="2" class="judulhead">Surat Pemeriksaan Kesehatan "SPK"<br />Nomor : ' . $spkdokter['spak'] . '</td></tr>
	  <tr><td>Upload SPK</td><td>: ' . strtoupper($inputby_met) . '</td></tr>
	  <tr><td colspan="2">'.$error_19.'</td></tr>
	  <tr><td>Nama<font color="red">*</font> ' . $error_1 . '</td>
		  <td>: <input name="spk_nama" type="text" size="50" value="' . $_REQUEST['spk_nama'] . '" placeholder="Nama Peserta">
	  </tr>
	  <tr><td>Jenis Kelamin</td>
	  	  <td>: <input type="radio" name="spk_sex" value="M"' . pilih($_REQUEST["spk_sex"], "M") . '>Laki-Laki
				<input type="radio" name="spk_sex" value="F"' . pilih($_REQUEST["spk_sex"], "F") . '>Perempuan</td>
	  </tr>
	  <tr><td>Tanggal Lahir <font color="red">*</font> ' . $error_2 . '</td>
		  <td>: <input type="text" name="spk_dob" id="rdob" class="tanggal" value="' . $_REQUEST['spk_dob'] . '" size="10"/></td>
	  </tr>
	  <tr><td>Alamat <font color="red">*</font> ' . $error_3 . '</td>
	  	  <td>: <textarea name="spk_alamat" type="text"rows="1" cols="45" placeholder="Alamat">' . $_REQUEST['spk_alamat'] . '</textarea></td>
	  </tr>
	  <tr><td>Pekerjaan</td>
	  	  <td>: <input name="spk_pekerjaan" type="text" size="50" placeholder="Pekerjaan" value="' . $_REQUEST['spk_pekerjaan'] . '"></td>
	  </tr>
	  <tr><td colspan="2" class="judulhead1">Questioner Kesehatan</td></tr>
	  <tr><td colspan="2">
	  	  <table border="0" cellpadding="1" cellspacing="2" width="100%">
	  	  <tr><td width="1%" align="center" valign="top">1. </td>
	  	  	  <td>Apakah anda dalam jangka 5 tahun terakhir ini pernah atau sedang menderita penyakit, Asma, Cacat, Tumor/Kanker, TBC, Paru-paru, Kencing manis , Hati, Ginjal, Jantung, Stroke, Tekanan Darah Tinggi, Epilepsi, Gangguan Jiwa, Penyakit Autoimun, Keterbelakngan Mental atau Idiot. Jika "Ya", Jelaskan. <font color="red">*</font> ' . $error_4 . '
				  <input type="radio" name="qk_1" value="Y"' . pilih($_REQUEST["qk_1"], "Y") . '>Ya &nbsp; <input type="radio" name="qk_1" value="T"' . pilih($_REQUEST["qk_1"], "T") . '>Tidak
			  </td>
		  </tr>
		  <tr><td>&nbsp;</td><td><textarea name="spk_ket_qk1" rows="1" cols="70" placeholder="Keterangan Pertanyaan 1">' . $_REQUEST['spk_ket_qk1'] . '</textarea></td></tr>
		  <tr><td width="1%" align="center" valign="top">2. </td>
	  	  	  <td>Apakah Berat badan Anda berubah dalam 12 bulan terakhir ini, Jika "Ya", Jelaskan. <font color="red">*</font> ' . $error_5 . '
				  <input type="radio" name="qk_2" value="Y"' . pilih($_REQUEST["qk_2"], "Y") . '>Ya &nbsp; <input type="radio" name="qk_2" value="T"' . pilih($_REQUEST["qk_2"], "T") . '>Tidak
			   </td>
		  </tr>
		  <tr><td>&nbsp;</td><td><textarea name="spk_ket_qk2" rows="1" cols="70" placeholder="Keterangan Pertanyaan 2">' . $_REQUEST['spk_ket_qk2'] . '</textarea></td></tr>
		  <tr><td width="1%" align="center" valign="top">3. </td>
		  	  <td>Apakah anda menderita HIV/AIDS? <font color="red">*</font> ' . $error_6 . '
				  <input type="radio" name="qk_3" value="Y"' . pilih($_REQUEST["qk_3"], "Y") . '>Ya &nbsp; <input type="radio" name="qk_3" value="T"' . pilih($_REQUEST["qk_3"], "T") . '>Tidak
			  </td>
		  </tr>
	  	  <tr><td>&nbsp;</td><td><textarea name="spk_ket_qk3" rows="1" cols="70" placeholder="Keterangan Pertanyaan 3">' . $_REQUEST['spk_ket_qk3'] . '</textarea></td></tr>
		  <tr><td width="1%" align="center" valign="top">4. </td>
		  	  <td>Apakah anda mengkonsumsi rutin (ketergantungan) pada Narkoba? Jika "Ya", Sebutkan jenisnya? <font color="red">*</font> ' . $error_7 . '
				  <input type="radio" name="qk_4" value="Y"' . pilih($_REQUEST["qk_4"], "Y") . '>Ya &nbsp; <input type="radio" name="qk_4" value="T"' . pilih($_REQUEST["qk_4"], "T") . '>Tidak
		  	  </td>
		  <tr><td>&nbsp;</td><td><textarea name="spk_ket_qk4" rows="1" cols="70" placeholder="Keterangan Pertanyaan 4">' . $_REQUEST['spk_ket_qk4'] . '</textarea></td></tr>
		  </tr>
		  <tr><td width="1%" align="center" valign="top">5. </td>
		  	  <td><b>Khusus untuk Wanita</b>, apakah anda sedang hamil ? Jika "Ya" adalah komplikasi / penyakit kehamilan ? jelaskan? Usia Kandungan? <font color="red">*</font> ' . $error_8 . '
				  <input type="radio" name="qk_5" value="Y"' . pilih($_REQUEST["qk_5"], "Y") . '>Ya &nbsp; <input type="radio" name="qk_5" value="T"' . pilih($_REQUEST["qk_5"], "T") . '>Tidak
		  	  </td>
		  <tr><td>&nbsp;</td><td><textarea name="spk_ket_qk5" rows="1" cols="70" placeholder="Keterangan Pertanyaan 5">' . $_REQUEST['spk_ket_qk5'] . '</textarea></td></tr>
		  </tr>
		  <tr><td width="1%" align="center" valign="top">6. </td>
		  	  <td>Apakah anda seorang perokok? Jika "Ya" berapa batang perhari? <font color="red">*</font> ' . $error_9 . '
				  <input type="radio" name="qk_6" value="Y"' . pilih($_REQUEST["qk_6"], "Y") . '>Ya &nbsp; <input type="radio" name="qk_6" value="T"' . pilih($_REQUEST["qk_6"], "T") . '>Tidak
		  	  </td>
		  <tr><td>&nbsp;</td><td><textarea name="spk_ket_qk6" rows="1" cols="70" placeholder="Batang Rokok PerHari">' . $_REQUEST['spk_ket_qk6'] . '</textarea></td></tr>
		  </tr>
		  </table>
	  </td></tr>
	  <tr><td colspan="2" class="judulhead1">Pemeriksaan Kesehatan</td></tr>
	  <tr><td colspan="2">
	  	  <table border="0" cellpadding="1" cellspacing="2" width="100%">
	  	  <tr><td width="20%">Nama Dokter Pemeriksa<font color="red">*</font></td>
	  	  	  <!--<td>: <input name="dokter_pemeriksa" type="text" size="30" value="' . $_REQUEST['dokter_pemeriksa'] . '" placeholder="Nama Dokter Pemeriksa"></td>-->
		  	  <td>: <select size="1" name="dokter_pemeriksa">
		<option value="">---Pilih Dokter Pemeriksa---</option>';
    	while ($checkDokter_ = mysql_fetch_array($checkDokter)) {	echo '<option value="'.$checkDokter_['id'].'"'._selected($_REQUEST['dokter_pemeriksa'], $checkDokter_['id']).'>'.$checkDokter_['namalengkap'].'</option>';	}
    	echo '</select> ' . $error_18 . '</td>
		  </tr>
	  	  <tr><td width="20%">Tinggi Badan</td><td>: <input name="spk_tbadan" type="text" size="15" value="' . $_REQUEST['spk_tbadan'] . '" placeholder="Tinggi Badan" onkeyup="this.value=this.value.replace(/[^0-9/,]/g,\'\')"></td></tr>
	  	  <tr><td width="20%">Berat Badan</td><td>: <input name="spk_bbadan" type="text" size="15" value="' . $_REQUEST['spk_bbadan'] . '" placeholder="Berat Badan" onkeyup="this.value=this.value.replace(/[^0-9/,]/g,\'\')"></td></tr>
	  	  <tr><td width="20%">Tekanan Darah</td><td>: <input name="spk_tdarah" type="text" size="15" value="' . $_REQUEST['spk_tdarah'] . '" placeholder="Tekanan darah" onkeyup="this.value=this.value.replace(/[^0-9//]/g,\'\')"></td></tr>
	  	  <tr><td width="20%">Nadi</td><td>: <input name="spk_nadi" type="text" size="15" value="' . $_REQUEST['spk_nadi'] . '" placeholder="Nadi" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"></td></tr>
	  	  <tr><td width="20%">Pernafasan</td><td>: <input name="spk_nafas" type="text" size="15" value="' . $_REQUEST['spk_nafas'] . '" placeholder="Pernafasan" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"></td></tr>
	  	  <tr><td width="20%">Gula Darah</td><td>: <input name="spk_guladarah" type="text" size="15" value="' . $_REQUEST['spk_guladarah'] . '" placeholder="Gula darah" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"></td></tr>
		  <tr><td width="20%">Tanggal Pemeriksaan <font color="red">*</font></td><td>: <input type="text" name="spk_tglcheck" id="spk_tglcheck" class="tanggal" value="' . $_REQUEST['spk_tglcheck'] . '" size="10"/> ' . $error_10 . '</td></tr>
		  <tr><td colspan="2">Dari pemeriksaan dan keterangan kesehatan diatas saya simpulkan bahwa saat ini calon Debitur dalam keadaan :</td></tr>
		  <tr><td colspan="2"><textarea name="periksa_kesehatan" rows="1" cols="70" placeholder="Pemeriksaan Kesehatan">' . $_REQUEST['periksa_kesehatan'] . '</textarea></td></tr>
		  <tr><td colspan="2">Catatan :</td></tr>
		  <tr><td colspan="2"><textarea name="catatan" rows="1" cols="70" placeholder="Catatan">' . $_REQUEST['catatan'] . '</textarea></td></tr>
	  </table>
	  </td></tr>
	  <tr><td colspan="2" class="judulhead1">Asurani Kredit</td></tr>
	  <tr><td colspan="2">
	  	  <table border="0" cellpadding="1" cellspacing="2" width="100%">
	  	  <tr><td width="20%">Jumlah Pinjaman/Kredit <font color="red">*</font> ' . $error_12 . ' ' . $error_15 . ' '.$error_16.'</td>
	  	  	  <td>: <input type="text" name="spk_plafond" value="' . $_REQUEST['spk_plafond'] . '" size="30" id="inputku" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);"/ placeholder="Plafond"></td>
		  </tr>
		  <tr><td>Tanggal Akad/Kredit <font color="red">*</font> ' . $error_13 . '</td>
	  	  	  <td>: <input type="text" name="spk_tglakad" id="spk_tglakad" class="tanggal" value="' . $_REQUEST['spk_tglakad'] . '" maxlength="10" size="15"/ placeholder="Tangal Akad"></td>
		  </tr>
		  <tr><td>Jangka Waktu <font color="blue">(Jumlah Tahun)</font> <font color="red">*</font> ' . $error_11 . '</td>
	  	  	  <td>: <input type="text" name="spk_jwaktu" value="' . $_REQUEST['spk_jwaktu'] . '" size="5" maxlength="2" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"/ placeholder="Tenor"></td>
		  </tr>
		  <tr><td>Masa Pra Pensiun (MPP) <font color="blue">(Jumlah Bulan)' . $error_17 . '</td>
	  	  	  <td valign="top">: <input type="text" name="mppbln" value="' . $_REQUEST['mppbln'] . '" size="5" maxlength="2" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"/ placeholder="MPP"></td>
		  </tr>
		  <tr><td>Nama Cabang Bank / Koperasi <font color="red">*</font> ' . $error_14 . '</td>
	  	  	  <!--<td>: <input type="text" name="spk_nmcabbank" value="' . $_REQUEST['spk_nmcabbank'] . '" size="50"/ placeholder="Nama Cabang Bank / Koperasi"></td>-->
		  	  <td>: <select size="1" name="spk_nmcabbank">
		<option value="">---Pilih Nama Cabang---</option>';
    	$metCabangnya = $database->doQuery('SELECT * FROM fu_ajk_cabang WHERE del IS NULL GROUP BY name ORDER BY name ASC');
    	while ($metCabangnya_ = mysql_fetch_array($metCabangnya)) {	echo '<option value="'.$metCabangnya_['id'].'"'._selected($_REQUEST['spk_nmcabbank'], $metCabangnya_['id']).'>'.$metCabangnya_['name'].'</option>';	}
    	echo '</select></td>
		  </tr>
		  </table>
	  </td></tr>
	  <tr><td colspan="2" align="center"><input type="submit" name="ope" value="Simpan" class="button" /></td>
	  </table></form>';
        break;

    case "edit_spak":
echo '<table border="0" cellpadding="0" cellspacing="0" width="100%">
      <tr><th width="95%" align="left">Modul Data spk</font></th><th><a href="ajk_uploader_spak.php?r=spk_app"><img src="image/back.png" width="20"></a></th></tr>
      </table>';
        $spkdokter = mysql_fetch_array($database->doQuery('SELECT *, DATE_FORMAT(input_date,"%Y-%m-%d") AS tglinput FROM fu_ajk_spak WHERE id=' . $_REQUEST['ids'] . ''));
        $spkdokter_form = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_form WHERE idspk=' . $_REQUEST['ids'] . ' AND id=' . $_REQUEST['idform'] . ' '));
if (is_numeric($spkdokter['input_by'])) {
	$met_User = mysql_fetch_array($database->doQuery('SELECT id, nama, namalengkap FROM user_mobile WHERE id="'.$spkdokter['input_by'].'"'));
	$inputby_met = $met_User['namalengkap'];
}else{
	$inputby_met = $spkdokter['input_by'];
}
        if ($_REQUEST['ope'] == "Simpan") {
            if ($_REQUEST['spk_nama'] == "")	{	$error_1 = '<font color="red"><blink>Silahkan input nama debitur.</font>';	}
            if ($_REQUEST['spk_dob'] == "") 	{	$error_2 = '<font color="red"><blink>Silahkan isi tanggal lahir debitur.<br /></font>';	}
            if ($_REQUEST['spk_alamat'] == "") 	{	$error_3 = '<font color="red"><blink>Silahkan isi alamat debitur.<br /></font>';	}
            if ($_REQUEST['qk_1'] == "") 		{	$error_4 = '<font color="red"><blink>Silahkan pilih pertanyaan "ya" atau "tidak".<br /></font>';	}
            if ($_REQUEST['qk_2'] == "") 		{	$error_5 = '<font color="red"><blink>Silahkan pilih pertanyaan "ya" atau "tidak".<br /></font>';	}
            if ($_REQUEST['qk_3'] == "") 		{	$error_6 = '<font color="red"><blink>Silahkan pilih pertanyaan "ya" atau "tidak".<br /></font>';	}
            if ($_REQUEST['qk_4'] == "") 		{	$error_7 = '<font color="red"><blink>Silahkan pilih pertanyaan "ya" atau "tidak".<br /></font>';	}
            if ($_REQUEST['qk_5'] == "") 		{	$error_8 = '<font color="red"><blink>Silahkan pilih pertanyaan "ya" atau "tidak".<br /></font>';	}
            if ($_REQUEST['qk_6'] == "") 		{	$error_9 = '<font color="red"><blink>Silahkan pilih pertanyaan "ya" atau "tidak".<br /></font>';	}
            if ($_REQUEST['spk_tglcheck']=="")	{	$error_10 = '<font color="red"><blink>Silahkan isi tanggal pemeriksaan.<br /></font>';	}
            if ($_REQUEST['spk_plafond'] == "") {	$error_12 = '<font color="red"><blink>Silahkan isi jumlah pinjaman.<br /></font>';	}
            if ($_REQUEST['spk_tglakad'] == "") {	$error_13 = '<font color="red"><blink>Silahkan isi tanggal awal asuransi.<br /></font>';	}
            if ($_REQUEST['spk_nmcabbank']=="") {	$error_14 = '<font color="red"><blink>Silahkan isi cabang debitur.<br /></font>';	}
            if ($_REQUEST['dokter_pemeriksa']=="") {	$error_nmdokter = '<font color="red"><blink>Silahkan pilih nama dokter pemeriksa.<br /></font>';	}

            $cekplafond = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_medical WHERE id_cost="' . $spkdokter['id_cost'] . '" AND id_polis="' . $spkdokter['id_polis'] . '"'));
            if ($_REQUEST['plafond'] >= $cekplafond['si_to']) {
                $error_15 = '<font color="red"><blink>Nilai Plafond melewati batas maksimum table underwriting.<br /></font>';
            }

            $admpolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id_cost="' . $spkdokter['id_cost'] . '" AND id="' . $spkdokter['id_polis'] . '"'));
            // $umur = ceil(((strtotime($_REQUEST['spk_tglakad']) - strtotime($_REQUEST['spk_dob'])) / (60*60*24*365.2425)));	// FORMULA USIA
            if ($_REQUEST['spk_tglakad']=="0000-00-00") {
            	$tglakadnya = $futoday;
            }else{
            	$tglakadnya = $_REQUEST['spk_tglakad'];
            }
			//$met_Date = dateDiff($_REQUEST['spk_tglakad'], $_REQUEST['spk_dob']); 30092016 direvisi karena apabila ada tgl akadnya yg masih kosong
			$met_Date = dateDiff($tglakadnya, $_REQUEST['spk_dob']);
            $met_Date_ = explode(",", $met_Date);
            // echo $met_Date_[0].'<br />';
            // echo $met_Date_[1].'<br />';
            // echo $met_Date_[2].'<br />';
            // echo $_REQUEST['spk_dob'].'<br />';
            // echo $_REQUEST['spk_tglakad'].' <br />';
            if ($met_Date_[1] >= 6) {
                $umur = $met_Date_[0] + 1;
            } else {
                $umur = $met_Date_[0];
            }

			// FORMULA USIA
            $cekplafond = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_medical WHERE id_cost="' . $spkdokter['id_cost'] . '" AND id_polis="' . $spkdokter['id_polis'] . '"'));
            if ($umur >= $cekplafond['age_from'] AND str_replace(".", "", $_REQUEST['spk_plafond']) > $cekplafond['si_to']) {
                $error_15 = '<font color="red"><blink>Nilai Plafond melewati batas maksimum table underwriting.<br /></font>';
            }
            // $mettenornya = $_REQUEST['spk_jwaktu'] / 12;
            $mettenornya = $_REQUEST['spk_jwaktu'];
/*
            $cekrate_tenor = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="' . $spkdokter['id_cost'] . '" AND id_polis="' . $spkdokter['id_polis'] . '" AND usia="' . $umur . '" AND tenor="' . $mettenornya . '" AND status="baru" AND del IS NULL')); // RATE PREMI
            if ($cekrate_tenor['tenor'] != $mettenornya) {
                $error_11 = '<font color="red"><blink>Jumlah jangka waktu tidak sesuai rate produk.<br /></font>';
            }
*/

        		if ($_REQUEST['spk_jwaktu'] == "") {	$error_11 = '<font color="red"><blink>Jangka Waktu tidak boleh kosong.<br /></font>';	}
        		else {
        			if ($admpolis['mpptype']=="Y") {
        				$datamppcektenor = $database->doQuery('SELECT * FROM fu_ajk_spak_form WHERE nopermohonan = "'.$spkdokter_form['nopermohonan'].'"');
        				while ($datamppcektenor_ = mysql_fetch_array($datamppcektenor)) {
        					//echo $spkdokter_form['idspk'] .' - '.$spkdokter_form['plafond'] .' - '.$datampp_['idspk'] .' - '.$datampp_['nopermohonan'].' - '.$datampp_['plafond'].'<br />';
        					if ($spkdokter_form['idspk'] == $datamppcektenor_['idspk']) {
        						if ($_REQUEST['spk_jwaktu'] <= 12) {
        							$datamppcektenor__ = 1;
        						}elseif ($_REQUEST['spk_jwaktu'] >= 13 && $_REQUEST['spk_jwaktu'] <= 24) {
        							$datamppcektenor__ = 2;
        						}else{
        							$datamppcektenor__ = 3;
        						}
        					}else{
        						$datamppcektenor__ =  $_REQUEST['spk_jwaktu'];
        					}
        				}
        				$cekrate_tenor = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="' . $spkdokter['id_cost'] . '" AND id_polis="' . $spkdokter['id_polis'] . '" AND tenor="' .$datamppcektenor__. '" AND '.$_REQUEST['mppbln'].' BETWEEN mpp_s AND mpp_e AND status="baru" AND "'.$spkdokter['tglinput'].'" BETWEEN eff_from AND eff_to AND del IS NULL')); // RATE PREMI
        				if (!$cekrate_tenor['rate']) {
        					$error_11 = '<font color="red"><blink>Jumlah jangka waktu tidak sesuai rate produk MPP.<br /></font>';
        				}
        			}else{
						//$mettenornya = $_REQUEST['spk_jwaktu'] / 12;
        				//$cekrate_tenor = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="' . $spkdokter['id_cost'] . '" AND id_polis="' . $spkdokter['id_polis'] . '" AND usia="' . $umur . '" AND tenor="' .$_REQUEST['spk_jwaktu']. '" AND status="baru" AND "'.$spkdokter['tglinput'].'" BETWEEN eff_from AND eff_to AND del IS NULL')); // RATE PREMI 27092016 revisi tanpa melihat status rate
        				$cekrate_tenor = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="' . $spkdokter['id_cost'] . '" AND id_polis="' . $spkdokter['id_polis'] . '" AND usia="' . $umur . '" AND tenor="' .$_REQUEST['spk_jwaktu']. '" AND "'.$spkdokter['tglinput'].'" BETWEEN eff_from AND eff_to AND del IS NULL')); // RATE PREMI
        				if ($cekrate_tenor['tenor'] != $_REQUEST['spk_jwaktu']) {
        					$error_11 = '<font color="red"><blink>Jumlah jangka waktu tidak sesuai rate produk.<br /></font>';
        				}
        			}
        			//CEK TAMBAHAN DATA MPP TALANGAN
        			if ($admpolis['mpptype']=="Y") {
        				$datampp = $database->doQuery('SELECT * FROM fu_ajk_spak_form WHERE nopermohonan = "'.$spkdokter_form['nopermohonan'].'"');
					while ($datampp_ = mysql_fetch_array($datampp)) {
						//echo $spkdokter_form['idspk'] .' - '.$spkdokter_form['plafond'] .' - '.$datampp_['idspk'] .' - '.$datampp_['nopermohonan'].' - '.$datampp_['plafond'].'<br />';
						if ($spkdokter_form['idspk'] == $datampp_['idspk']) {
							$datampptenor =  'month';
						}else{
							$datampptenor =  'year';
						}
					}
        			}else{
        				$datampptenor =  'year';
        			}
        			//CEK TAMBAHAN DATA MPP TALANGAN

					$met_tgl_akhir = date('Y-m-d', strtotime('+' . $_REQUEST['spk_jwaktu'] . ' '.$datampptenor.'', strtotime($_REQUEST['spk_tglakad']))); //tanggal akhir asuransi
        			$met_Date_Akhir = datediff($met_tgl_akhir, $_REQUEST['spk_dob']);
        			$metUsiaAkhirKredit = explode(",", $met_Date_Akhir);

        			//if ($metUsiaAkhirKredit[0] > $admpolis['age_max'] + 1 AND $metUsiaAkhirKredit[1] <= 5) {
        			if ($datampptenor =="month") {

        			}else{
						if (($umur + $_REQUEST['spk_jwaktu']) > $admpolis['age_max'] + 1) {
        					$error_11 = '<font color="red"><blink>Usia '.$umur.'thn melebihi batas masksimum usia, data ditolak.!!!</font>';
        				}
        			}
        		}
        	//CEK PLAFOND UMUR PADA TABLE MEDICAL
        	$plafondnya__ = str_replace(".", "", $_REQUEST['spk_plafond']);
        	$cekplafondakhir = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_medical WHERE id_cost="' . $spkdokter['id_cost'] . '" AND
        																								id_polis="' . $spkdokter['id_polis'] . '" AND
        																								'.$umur.' BETWEEN age_from AND age_to AND
        																								'.$plafondnya__.' BETWEEN si_from AND si_to
        																								'));
        	if (!$cekplafondakhir) {	$error_16 = '<font color="red"><blink>Nilai Plafond melewati batas maksimum table underwriting.<br /></font>';	}
        	//CEK PLAFOND UMUR PADA TABLE MEDICAL

			if ($admpolis['mpptype']=="Y" AND $_REQUEST['mppbln']=="") {	$error_17 = '<font color="red"><blink>Silahkan isi jumlah masa pra pensiun (mpp).<br /></font>';	}
            if ($error_1 OR $error_2 OR $error_3 OR $error_4 OR $error_5 OR $error_6 OR $error_7 OR $error_8 OR $error_9 OR $error_10 OR $error_11 OR $error_12 OR $error_13 OR $error_14 OR $error_15 OR $error_16 OR $error_17) {
            }else {
                $plafondnya = str_replace(".", "", $_REQUEST['spk_plafond']);

				//$cekrate = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="' . $spkdokter['id_cost'] . '" AND id_polis="' . $spkdokter['id_polis'] . '" AND usia="' . $umur . '" AND tenor="' . $mettenornya . '"')); // RATE PREMI
            	if ($admpolis['singlerate'] == "T") {
            		$cekrate = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="' . $spkdokter['id_cost'] . '" AND id_polis="' . $spkdokter['id_polis'] . '" AND tenor="' . $_REQUEST['spk_jwaktu'] . '" AND status="baru" AND "'.$spkdokter['tglinput'].'" BETWEEN eff_from AND eff_to AND del IS NULL')); // RATE PREMI
            	} else {
            		//$cekrate = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="' . $spkdokter['id_cost'] . '" AND id_polis="' . $spkdokter['id_polis'] . '" AND usia="' . $umur . '" AND tenor="' . $_REQUEST['spk_jwaktu'] . '"')); // RATE PREMI
            		if ($admpolis['mpptype']=="Y") {
            			//$tenorSPKMPP = $_REQUEST['spk_jwaktu'];
						$datamppcektenor = $database->doQuery('SELECT * FROM fu_ajk_spak_form WHERE nopermohonan = "'.$spkdokter_form['nopermohonan'].'"');
            			while ($datamppcektenor_ = mysql_fetch_array($datamppcektenor)) {
            				//echo $spkdokter_form['idspk'] .' - '.$spkdokter_form['plafond'] .' - '.$datampp_['idspk'] .' - '.$datampp_['nopermohonan'].' - '.$datampp_['plafond'].'<br />';
            				if ($spkdokter_form['idspk'] == $datamppcektenor_['idspk']) {
            					if ($_REQUEST['spk_jwaktu'] <= 12) {
            						$datamppcektenor__ = 1;
            					}elseif ($_REQUEST['spk_jwaktu'] >= 13 && $_REQUEST['spk_jwaktu'] <= 24) {
            						$datamppcektenor__ = 2;
            					}else{
            						$datamppcektenor__ = 3;
            					}
            				}else{
            					$datamppcektenor__ =  $_REQUEST['spk_jwaktu'];
            				}
            			}


            			$cekrate = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="' . $spkdokter['id_cost'] . '" AND id_polis="' . $spkdokter['id_polis'] . '" AND '.$_REQUEST['mppbln'].' BETWEEN mpp_s AND mpp_e AND tenor="' . $datamppcektenor__ . '" AND status="baru" AND "'.$spkdokter['tglinput'].'" BETWEEN eff_from AND eff_to AND del IS NULL')); // RATE PREMI
            			//$cekrate = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="' . $spkdokter['id_cost'] . '" AND id_polis="' . $spkdokter['id_polis'] . '" AND '.$_REQUEST['mppbln'].' BETWEEN mpp_s AND mpp_e AND tenor="' . $tenorSPKMPP . '" AND status="baru" AND "'.$spkdokter['tglinput'].'" BETWEEN eff_from AND eff_to AND del IS NULL')); // RATE PREMI
/*
            			if ($metFormSPK['mpp'] < $admpolis['mppbln_min']) {
            				$cekrate = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="' . $spkdokter['id_cost'] . '" AND id_polis="' . $spkdokter['id_polis'] . '" AND usia="' . $umur . '" AND tenor="' . $_REQUEST['spk_jwaktu'] . '" AND status="lama" AND del IS NULL')); // RATE PREMI
            			}else{
            				//$tenorSPKMPP = $_REQUEST['spk_jwaktu'] * 12;
            				$tenorSPKMPP = $_REQUEST['spk_jwaktu'];
            				$cekrate = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="' . $spkdokter['id_cost'] . '" AND id_polis="' . $spkdokter['id_polis'] . '" AND '.$metFormSPK['mpp'].' BETWEEN mpp_s AND mpp_e AND tenor="' . $_REQUEST['spk_jwaktu'] . '" AND status="baru" AND del IS NULL')); // RATE PREMI
            			}
*/
            		}else{
            			//$cekrate = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="' . $spkdokter['id_cost'] . '" AND id_polis="' . $spkdokter['id_polis'] . '" AND usia="' . $umur . '" AND tenor="' . $_REQUEST['spk_jwaktu'] . '" AND status="baru" AND "'.$spkdokter['tglinput'].'" BETWEEN eff_from AND eff_to AND del IS NULL')); // RATE PREMI 27092016 revisi tanpa mengecek status rate
            			$cekrate = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="' . $spkdokter['id_cost'] . '" AND id_polis="' . $spkdokter['id_polis'] . '" AND usia="' . $umur . '" AND tenor="' . $_REQUEST['spk_jwaktu'] . '" AND "'.$spkdokter['tglinput'].'" BETWEEN eff_from AND eff_to AND del IS NULL')); // RATE PREMI
            		}
            	}
                // MENENTUKAN PERHITUNGAN PREMI BERDASARKAN SINGLE RATE PADA POLIS
                $premi = ROUND($plafondnya * $cekrate['rate'] / 1000);
                if ($premi < $admpolis['min_premium']) {
                    //$premi_x = $admpolis['min_premium'];	revisi 12 oktober 2016 minimum premi dihitung dari premi asli + em < minimumpremi di pdf
                    $premi_x = $premi;
                } else {
                    $premi_x = $premi;
                }

            	if ($admpolis['mpptype']=="Y") {
				$datampp = $database->doQuery('SELECT * FROM fu_ajk_spak_form WHERE nopermohonan = "'.$spkdokter_form['nopermohonan'].'"');
            	while ($datampp_ = mysql_fetch_array($datampp)) {
            		//echo $spkdokter_form['idspk'] .' - '.$spkdokter_form['plafond'] .' - '.$datampp_['idspk'] .' - '.$datampp_['nopermohonan'].' - '.$datampp_['plafond'].'<br />';
            		if ($spkdokter_form['idspk'] == $datampp_['idspk']) {
            			$datampptenor =  'month';
            		}else{
            			$datampptenor =  'year';
            		}
            	}
            	}else{
            		$datampptenor =  'year';
            	}

                $met_tgl_akhir = date('Y-m-d', strtotime('+' . $mettenornya . ' '.$datampptenor.'', strtotime($_REQUEST['spk_tglakad']))); //operasi penjumlahan tanggal
                //$met_tgl_akhir = date('Y-m-d', strtotime('+' . $mettenornya . ' year', strtotime($_REQUEST['spk_tglakad']))); //operasi penjumlahan tanggal	revisi data hitung tanggal akhir apabila danatalangan atau tidak

                // echo $_REQUEST['spk_jwaktu'].'<br />';
                $metrefundcn = $database->doQuery('UPDATE fu_ajk_spak_form SET idcost="' . $spkdokter['id_cost'] . '",
                								   					dokter="' . $spkdokter['input_by'] . '",
												   					idspk="' . $spkdokter['id'] . '",
												   					nama="' . $_REQUEST['spk_nama'] . '",
												   					noidentitas="' . $_REQUEST['noidentitas'] . '",
												   					jns_kelamin="' . $_REQUEST['jns_kelamin'] . '",
												   					dob="' . $_REQUEST['spk_dob'] . '",
												   					alamat="' . $_REQUEST['spk_alamat'] . '",
												   					pekerjaan="' . $_REQUEST['spk_pekerjaan'] . '",
												   					pertanyaan1="' . $_REQUEST['qk_1'] . '",
												   					ket1="' . $_REQUEST['spk_ket_qk1'] . '",
												   					pertanyaan2="' . $_REQUEST['qk_2'] . '",
												   					ket2="' . $_REQUEST['spk_ket_qk2'] . '",
												   					pertanyaan3="' . $_REQUEST['qk_3'] . '",
												   					ket3="' . $_REQUEST['spk_ket_qk3'] . '",
												   					pertanyaan4="' . $_REQUEST['qk_4'] . '",
												   					ket4="' . $_REQUEST['spk_ket_qk4'] . '",
												   					pertanyaan5="' . $_REQUEST['qk_5'] . '",
												   					ket5="' . $_REQUEST['spk_ket_qk5'] . '",
												   					pertanyaan6="' . $_REQUEST['qk_6'] . '",
												   					ket6="' . $_REQUEST['spk_ket_qk6'] . '",
												   					dokter_pemeriksa="' . $_REQUEST['dokter_pemeriksa'] . '",
												   					tinggibadan="' . $_REQUEST['spk_tbadan'] . '",
												   					beratbadan="' . $_REQUEST['spk_bbadan'] . '",
												   					tekanandarah="' . $_REQUEST['spk_tdarah'] . '",
												   					nadi="' . $_REQUEST['spk_nadi'] . '",
												   					pernafasan="' . $_REQUEST['spk_nafas'] . '",
												   					guladarah="' . $_REQUEST['spk_guladarah'] . '",
												   					kesimpulan="' . $_REQUEST['periksa_kesehatan'] . '",
												   					catatan="' . $_REQUEST['catatan'] . '",
												   					tgl_periksa="' . $_REQUEST['spk_tglcheck'] . '",
												   					plafond="' . $plafondnya . '",
												   					tgl_asuransi="' . $_REQUEST['spk_tglakad'] . '",
												   					tenor="' . $_REQUEST['spk_jwaktu'] . '",
												   					mpp="' . $_REQUEST['mppbln'] . '",
												   					tgl_akhir_asuransi="' . $met_tgl_akhir . '",
												   					ratebank="' . $cekrate['rate'] . '",
												   					x_premi="' . $premi_x . '",
												   					x_usia="' . $umur . '",
												   					cabang="' . $_REQUEST['spk_nmcabbank'] . '",
												   					update_by="' . $_SESSION['nm_user'] . '",
												   					update_date="' . $futgl . '"
															  WHERE id="' . $spkdokter_form['id'] . '"');

                $met_Espak = mysql_fetch_array($database->doQuery('SELECT id, status FROM fu_ajk_spak WHERE id="' . $_REQUEST['ids'] . '"'));
                if ($met_Espak['status'] == "Aktif") {
                    $met_updatestatus = "Aktif";
                } else {
                    $met_updatestatus = "Proses";
                }
                //$met__spak = $database->doQuery('UPDATE fu_ajk_spak SET status="' . $met_updatestatus . '" WHERE id="' . $_REQUEST['ids'] . '"');	DINONAKTIFKAN KARENA DATA SUDAH AKTIF APABILA DI EDIT LAGI AKAN KEMBALI KE STATUS PROSES

                $mail = new PHPMailer; // call the class
                $mail->IsSMTP();
                $mail->Host = SMTP_HOST; //Hostname of the mail server
                $mail->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
                $mail->Username = SMTP_UNAME; //Username for SMTP authentication any valid email created in your domain
                $mail->Password = SMTP_PWORD; //Password for SMTP authentication
                $mail->AddReplyTo = SMTP_REPLYMAIL; //reply-to address
            	$mail->debug = 1;
            	$mail->SMTPSecure = "ssl";

                $mail->SetFrom ($q['email'], $q['nm_lengkap']); //From address of the mail
                $mail->Subject = "AJK Online - REVISI DATA SPK"; //Subject od your mail
                // EMAIL SPV SPK
                $mailstaff = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="" AND level="' . $q['level'] . '" AND status="SUPERVISOR" AND del IS NULL');
                while ($mailstaff_ = mysql_fetch_array($mailstaff)) {
                    $mail->AddAddress($mailstaff_['email'], $mailstaff_['nm_lengkap']); //To address who will receive this email
                }
                // EMAIL SPV SPK
                //$mail->AddBCC("adonai.notif@gmail.com");
            	//$mail->AddBCC("rahmad@adonaits.co.id");
                $mail->MsgHTML('<table><tr><th>Nomor SPK ' . $spkdokter['spak'] . ' telah direvisi oleh <b>' . $_SESSION['nm_user'] . ' selaku Staff AJK-Online pada tanggal ' . $futgl . '</tr></table>' . $message); //Put your body of the message you can place html code here
                $send = $mail->Send(); //Send the mails
                echo '<center><h2>Data SPK telah direvisi oleh ' . $q['nm_lengkap'] . ' pada tanggal ' . $futgl . '.</h2></center><meta http-equiv="refresh" content="3;URL=ajk_uploader_spak.php?r=set_spaknew">';
            }
        }
        echo '<form name="f1" method="post" action="">
	  <table border="0" cellpadding="1" cellspacing="1" width="85%" align="center">
	  <tr><td colspan="2" class="judulhead">Surat Pemeriksaan Kesehatan "SPK"<br />Nomor : ' . $spkdokter['spak'] . '</td></tr>
	  <tr><td>Upload SPK</td><td>: ' . strtoupper($inputby_met) . '</td></tr>
	  <tr><td>Nama<font color="red">*</font> ' . $error_1 . '</td>
		  <td>: <input name="spk_nama" type="text" size="50" value="' . $spkdokter_form['nama'] . '" placeholder="Nama Peserta">
	  </tr>
	  <tr><td>No Identitas</td>
		  <td>: <input name="noidentitas" type="text" size="50" value="' . $spkdokter_form['noidentitas'] . '" placeholder="Nomor Identitas">
	  </tr>
	  <tr><td>Jenis Kelamin</td>
	  	  <td>: <input type="radio" name="jns_kelamin" value="M"' . pilih($spkdokter_form["jns_kelamin"], "M") . '>Laki-Laki
				<input type="radio" name="jns_kelamin" value="F"' . pilih($spkdokter_form["jns_kelamin"], "F") . '>Perempuan</td>
	  </tr>
	  <tr><td>Tanggal Lahir <font color="red">*</font> ' . $error_2 . '</td>
		  <td>: <input type="text" name="spk_dob" id="rdob" class="tanggal" value="' . $spkdokter_form['dob'] . '" size="10"/></td>
	  </tr>
	  <tr><td>Alamat <font color="red">*</font> ' . $error_3 . '</td>
	  	  <td>: <textarea name="spk_alamat" type="text"rows="1" cols="45" placeholder="Alamat">' . $spkdokter_form['alamat'] . '</textarea></td>
	  </tr>
	  <tr><td>Pekerjaan</td>
	  	  <td>: <input name="spk_pekerjaan" type="text" size="50" placeholder="Pekerjaan" value="' . $spkdokter_form['pekerjaan'] . '"></td>
	  </tr>
	  <tr><td colspan="2" class="judulhead1">Questioner Kesehatan</td></tr>
	  <tr><td colspan="2">
	  	  <table border="0" cellpadding="1" cellspacing="2" width="100%">
	  	  <tr><td width="1%" align="center" valign="top">1. </td>
	  	  	  <td>Apakah anda dalam jangka 5 tahun terakhir ini pernah atau sedang menderita penyakit, Asma, Cacat, Tumor/Kanker, TBC, Kencing manis , Hati, Ginjal, Jantung, Stroke, Tekanan Darah Tinggi, Epilepsi, Gangguan Jiwa, Penyakit Autoimun, Keterbelakngan Mental atau Idiot. Jika "Ya", Jelaskan. <font color="red">*</font> ' . $error_4 . '
				  <input type="radio" name="qk_1" value="Y"' . pilih($spkdokter_form["pertanyaan1"], "Y") . '>Ya &nbsp; <input type="radio" name="qk_1" value="T"' . pilih($spkdokter_form["pertanyaan1"], "T") . '>Tidak
			  </td>
		  </tr>
		  <tr><td>&nbsp;</td><td><textarea name="spk_ket_qk1" rows="1" cols="100" placeholder="Keterangan Pertanyaan 1">' . $spkdokter_form['ket1'] . '</textarea></td></tr>
		  <tr><td width="1%" align="center" valign="top">2. </td>
	  	  	  <td>Apakah Berat badan Anda berubah dalam 12 bulan terakhir ini, Jika "Ya", Jelaskan. <font color="red">*</font> ' . $error_5 . '
				  <input type="radio" name="qk_2" value="Y"' . pilih($spkdokter_form["pertanyaan2"], "Y") . '>Ya &nbsp; <input type="radio" name="qk_2" value="T"' . pilih($spkdokter_form["pertanyaan2"], "T") . '>Tidak
			   </td>
		  </tr>
		  <tr><td>&nbsp;</td><td><textarea name="spk_ket_qk2" rows="1" cols="100" placeholder="Keterangan Pertanyaan 2">' . $spkdokter_form['ket2'] . '</textarea></td></tr>
		  <tr><td width="1%" align="center" valign="top">3. </td>
		  	  <td>Apakah anda menderita HIV/AIDS? <font color="red">*</font> ' . $error_6 . '
				  <input type="radio" name="qk_3" value="Y"' . pilih($spkdokter_form["pertanyaan3"], "Y") . '>Ya &nbsp; <input type="radio" name="qk_3" value="T"' . pilih($spkdokter_form["pertanyaan3"], "T") . '>Tidak
			  </td>
		  </tr>
	  	  <tr><td>&nbsp;</td><td><textarea name="spk_ket_qk3" rows="1" cols="100" placeholder="Keterangan Pertanyaan 3">' . $spkdokter_form['ket3'] . '</textarea></td></tr>
		  <tr><td width="1%" align="center" valign="top">4. </td>
		  	  <td>Apakah anda mengkonsumsi rutin (ketergantungan) pada Narkoba? Jika "Ya", Sebutkan jenisnya? <font color="red">*</font> ' . $error_7 . '
				  <input type="radio" name="qk_4" value="Y"' . pilih($spkdokter_form["pertanyaan4"], "Y") . '>Ya &nbsp; <input type="radio" name="qk_4" value="T"' . pilih($spkdokter_form["pertanyaan4"], "T") . '>Tidak
		  	  </td>
		  <tr><td>&nbsp;</td><td><textarea name="spk_ket_qk4" rows="1" cols="100" placeholder="Keterangan Pertanyaan 4">' . $spkdokter_form['ket4'] . '</textarea></td></tr>
		  </tr>
		  <tr><td width="1%" align="center" valign="top">5. </td>
		  	  <td><b>Khusus untuk Wanita</b>, apakah anda sedang hamil ? Jika "Ya" adalah komplikasi / penyakit kehamilan ? jelaskan? Usia Kandungan? <font color="red">*</font> ' . $error_8 . '
				  <input type="radio" name="qk_5" value="Y"' . pilih($spkdokter_form["pertanyaan5"], "Y") . '>Ya &nbsp; <input type="radio" name="qk_5" value="T"' . pilih($spkdokter_form["pertanyaan5"], "T") . '>Tidak
		  	  </td>
		  <tr><td>&nbsp;</td><td><textarea name="spk_ket_qk5" rows="1" cols="100" placeholder="Keterangan Pertanyaan 5">' . $spkdokter_form['ket5'] . '</textarea></td></tr>
		  </tr>
		  <tr><td width="1%" align="center" valign="top">6. </td>
		  	  <td>Apakah anda seorang perokok? Jika "Ya" berapa batang perhari? <font color="red">*</font> ' . $error_9 . '
				  <input type="radio" name="qk_6" value="Y"' . pilih($spkdokter_form["pertanyaan6"], "Y") . '>Ya &nbsp; <input type="radio" name="qk_6" value="T"' . pilih($spkdokter_form["pertanyaan6"], "T") . '>Tidak
		  	  </td>
		  <tr><td>&nbsp;</td><td><textarea name="spk_ket_qk6" rows="1" cols="100" placeholder="Batang Rokok PerHari">' . $spkdokter_form['ket6'] . '</textarea></td></tr>
		  </tr>
		  </table>
	  </td></tr>
	  <tr><td colspan="2" class="judulhead1">Pemeriksaan Kesehatan</td></tr>
	  <tr><td colspan="2">
	  	  <table border="0" cellpadding="1" cellspacing="2" width="100%">
	  	  <tr><td width="20%">Nama Dokter Pemeriksa<font color="red">*</font></td>';
		if (is_numeric($spkdokter_form['dokter_pemeriksa'])) {
			echo '<td>: <select size="1" name="dokter_pemeriksa">
				<option value="">---Pilih Dokter Pemeriksa---</option>';
			$checkDokter = $database->doQuery('SELECT * FROM user_mobile WHERE type="Dokter" AND del IS NULL ORDER BY namalengkap ASC');
			while ($checkDokter_ = mysql_fetch_array($checkDokter)) {
				echo '<option value="'.$checkDokter_['id'].'"'._selected($spkdokter_form['dokter_pemeriksa'], $checkDokter_['id']).'>'.$checkDokter_['namalengkap'].'</option>';
			}
			echo '</select> '.$error_nmdokter.'</td>';
		}else{
		echo '<td>: <input name="dokter_pemeriksa" type="text" size="30" value="' . $spkdokter_form['dokter_pemeriksa'] . '" placeholder="Nama Dokter Pemeriksa"> '.$error_nmdokter.'</td>';
		}
		  echo '</tr>
	  	  <tr><td width="20%">Tinggi Badan</td>
	  	  	  <td>: <input name="spk_tbadan" type="text" size="15" value="' . $spkdokter_form['tinggibadan'] . '" placeholder="Tinggi Badan" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"></td>
		  </tr>
	  	  <tr><td width="20%">Berat Badan</td>
	  	  	  <td>: <input name="spk_bbadan" type="text" size="15" value="' . $spkdokter_form['beratbadan'] . '" placeholder="Berat Badan" onkeyup="this.value=this.value.replace(/[^0-9/,]/g,\'\')"></td>
		  </tr>
	  	  <tr><td width="20%">Tekanan Darah</td>
	  	  	  <td>: <input name="spk_tdarah" type="text" size="15" value="' . $spkdokter_form['tekanandarah'] . '" placeholder="Tekanan darah" onkeyup="this.value=this.value.replace(/[^0-9//]/g,\'\')"></td>
	  	  </tr>
	  	  <tr><td width="20%">Nadi</td>
	  	  	  <td>: <input name="spk_nadi" type="text" size="15" value="' . $spkdokter_form['nadi'] . '" placeholder="Nadi" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"></td>
		  </tr>
	  	  <tr><td width="20%">Pernafasan</td>
	  	  	  <td>: <input name="spk_nafas" type="text" size="15" value="' . $spkdokter_form['pernafasan'] . '" placeholder="Pernafasan" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"></td>
		  </tr>
	  	  <tr><td width="20%">Gula Darah</td>
	  	  	  <td>: <input name="spk_guladarah" type="text" size="15" value="' . $spkdokter_form['guladarah'] . '" placeholder="Gula darah" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"></td>
		  </tr>
		  <tr><td width="20%">Tanggal Pemeriksaan <font color="red">*</font> ' . $error_10 . '</td>
	  	  	  <td>: <input type="text" name="spk_tglcheck" id="spk_tglcheck" class="tanggal" value="' . $spkdokter_form['tgl_periksa'] . '" size="10"/></td>
		  </tr>
		  <tr><td colspan="2">Dari pemeriksaan dan keterangan kesehatan diatas saya simpulkan bahwa saat ini calon Debitur dalam keadaan :</td></tr>
		  <tr><td colspan="2"><textarea name="periksa_kesehatan" rows="1" cols="80" placeholder="Pemeriksaan Kesehatan">' . $spkdokter_form['kesimpulan'] . '</textarea></td></tr>
		  <tr><td colspan="2">Catatan :</td></tr>
		  <tr><td colspan="2"><textarea name="catatan" rows="1" cols="80" placeholder="Catatan">' . $spkdokter_form['catatan'] . '</textarea></td></tr>
	  </table>
	  </td></tr>
	  <tr><td colspan="2" class="judulhead1">Asurani Kredit</td></tr>
	  <tr><td colspan="2">
	  	  <table border="0" cellpadding="1" cellspacing="2" width="100%">
	  	  <tr><td width="20%">Jumlah Pinjaman/Kredit <font color="red">*</font> ' . $error_12 . ' ' . $error_15 . ' ' . $error_16 . '</td>
	  	  	  <td>: <input type="text" name="spk_plafond" value="' . $spkdokter_form['plafond'] . '" size="30" id="inputku" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);"/ placeholder="Plafond"></td>
		  </tr>
		  <tr><td>Tanggal Akad/Kredit <font color="red">*</font> ' . $error_13 . '</td>
	  	  	  <td>: <input type="text" name="spk_tglakad" id="spk_tglakad" class="tanggal" value="' . $spkdokter_form['tgl_asuransi'] . '" maxlength="10" size="15"/ placeholder="Tangal Akad"></td>
		  </tr>';
$datampp = $database->doQuery('SELECT * FROM fu_ajk_spak_form WHERE nopermohonan = "'.$spkdokter_form['nopermohonan'].'"');
while ($datampp_ = mysql_fetch_array($datampp)) {
	//echo $spkdokter_form['idspk'] .' - '.$spkdokter_form['plafond'] .' - '.$datampp_['idspk'] .' - '.$datampp_['nopermohonan'].' - '.$datampp_['plafond'].'<br />';
	if ($spkdokter_form['idspk'] == $datampp_['idspk']) {
		$datampptenor =  '<tr><td>Jangka Waktu <font color="blue">(Jumlah Bulan)</font> <font color="red">*</font> ' . $error_11 . '</td>
	  	  	  				  <td>: <input type="text" name="spk_jwaktu" value="' . $spkdokter_form['tenor'] . '" size="5" maxlength="2" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"/ placeholder="Tenor"></td>
		  				</tr>';
	}else{
		$datampptenor =  '<tr><td>Jangka Waktu <font color="blue">(Jumlah Tahun)</font> <font color="red">*</font> ' . $error_11 . '</td>
	  	  	  				  <td>: <input type="text" name="spk_jwaktu" value="' . $spkdokter_form['tenor'] . '" size="5" maxlength="2" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"/ placeholder="Tenor"></td>
		  				</tr>';
	}
}
echo $datampptenor;
echo '	  <tr><td>Masa Pra Pensiun (MPP) <font color="blue">(Jumlah Bulan)' . $error_17 . '</td>
	  	  	  <td valign="top">: <input type="text" name="mppbln" value="' . $spkdokter_form['mpp'] . '" size="5" maxlength="2" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"/ placeholder="MPP"></td>
		  </tr>';
echo '	  <tr><td>Nama Cabang Bank / Koperasi <font color="red">*</font> ' . $error_14 . '</td>';
	if (is_numeric($spkdokter_form['cabang'])) {
		echo '<td>: <select size="1" name="spk_nmcabbank">
			<option value="">---Pilih Nama Cabang---</option>';
		$metCabangnya = $database->doQuery('SELECT * FROM fu_ajk_cabang WHERE del IS NULL GROUP BY name ORDER BY name ASC');
		while ($metCabangnya_ = mysql_fetch_array($metCabangnya)) {
			echo '<option value="'.$metCabangnya_['id'].'"'._selected($spkdokter_form['cabang'], $metCabangnya_['id']).'>'.$metCabangnya_['name'].'</option>';
		}
		echo '</select></td>';
	}else{
		echo '<td>: <input type="text" name="spk_nmcabbank" value="' . $spkdokter_form['cabang'] . '" size="50"/ placeholder="Nama Cabang Bank / Koperasi"></td>';
	}

		echo '</tr>
		  </table>
	  </td></tr>
	  <tr><td colspan="2" align="center"><input type="submit" name="ope" value="Simpan" class="button" /></td>
	  </table></form>'; ;
        break;

    case "spk_app":
echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="95%" align="left">Modul Data SPK</font></th><th align="center"><a href="ajk_uploader_spak.php?r=spkaktif" title="spk reguler kadaluarsa"><img src="image/spk_form.png" width="25"></a></th></tr></table>';
echo '<table border="0" width="100%" cellpadding="1" cellspacing="1">
	  <form method="post" action="">
	  <tr><td align="right" width="10%">Nomor SPK :</td><td><input type="text" name="nospk" value="' . $_REQUEST['nospk'] . '"> </td></tr>
	  <tr><td align="right" width="10%">Nama Debitur :</td><td><input type="text" name="namaspk" value="' . $_REQUEST['namaspk'] . '"> <input type="submit" name="button" value="Cari" class="button"></td></tr>
	  </form></table>';
echo '<table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#bde0e6">
	 <tr><th width="1%">No</th>
	 	 <th>Produk</th>
	 	 <th width="1%">SPK</th>
	 	 <th>No.Identitas</th>
	 	 <th>Nama</th>
	 	 <th>Tgl Lahir</th>
	 	 <th>Tgl Akad</th>
	 	 <th>Tenor</th>
	 	 <th>Tgl Akhir</th>
	 	 <th width="1%">Grace Period</th>
	 	 <th>Plafond</th>
	 	 <th width="1%">Premi (x)</th>
	 	 <th width="1%">Usia (x)</th>
	 	 <th width="1%">Ex.Premi(%)</th>
	 	 <th width="5%">User Upload</th>
	 	 <th width="5%">Tgl Upload</th>
	 	 <th width="5%">User Approve</th>
	 	 <th width="5%">Tgl Approve</th>
		 <th width="1%">Nama File</th>
	 	 <th width="5%">Status</th>
	 	 <th width="5%">Option</th>
	 </tr>';
        if ($_REQUEST['x']) {	$m = ($_REQUEST['x'] - 1) * 25;	} else {	$m = 0;	}

        if ($_REQUEST['nospk']) 	{	$satu = 'AND fu_ajk_spak.spak LIKE "%' . $_REQUEST['nospk'] . '%"';	}
        if ($_REQUEST['namaspk']) 	{	$dua = 'AND fu_ajk_spak_form.nama LIKE "%' . $_REQUEST['namaspk'] . '%"';	}

/*
if ($_REQUEST['namaspk']) {
    		$ceknama = mysql_fetch_array($database->doQuery('SELECT fu_ajk_spak_form.id, fu_ajk_spak_form.idspk, fu_ajk_spak_form.nama, fu_ajk_spak.spak
    														 FROM fu_ajk_spak_form
    														 left Join fu_ajk_spak ON fu_ajk_spak_form.idspk = fu_ajk_spak.id
															 WHERE fu_ajk_spak_form.nama="'.$_REQUEST['namaspk'].'"'));
    		$dua = 'AND id = "' . $ceknama['idspk'] . '"';
    	}
*/

//$met = $database->doQuery('SELECT * FROM fu_ajk_spak WHERE (status="Aktif" OR status="Tolak" OR status="Realisasi") ' . $satu . ' ' . $dua . ' AND del IS NULL ORDER BY id DESC LIMIT ' . $m . ', 25');
$met = $database->doQuery('SELECT
fu_ajk_spak.id_cost,
fu_ajk_costumer.`name`,
fu_ajk_spak.id_polis,
fu_ajk_polis.nmproduk,
fu_ajk_spak.id,
IF(fu_ajk_spak.id_mitra = "",1,fu_ajk_spak.id_mitra) AS idmitra,
fu_ajk_grupproduk.nmproduk AS namamitra,
fu_ajk_spak.fname,
fu_ajk_spak.spak,
fu_ajk_spak.ket_ext,
fu_ajk_spak.`status`,
fu_ajk_spak_form.nama,
fu_ajk_spak_form.dob,
fu_ajk_spak_form.tgl_asuransi,
fu_ajk_spak_form.tenor,
fu_ajk_spak_form.tgl_akhir_asuransi,
fu_ajk_spak_form.plafond,
fu_ajk_spak_form.x_premi,
fu_ajk_spak_form.x_usia,
fu_ajk_spak.ext_premi,
fu_ajk_spak_form.mpp,
fu_ajk_spak.input_by,
fu_ajk_spak.input_date,
fu_ajk_spak.update_by,
fu_ajk_spak.update_date
FROM fu_ajk_spak
INNER JOIN fu_ajk_spak_form ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
INNER JOIN fu_ajk_polis ON fu_ajk_spak.id_polis = fu_ajk_polis.id
LEFT JOIN fu_ajk_grupproduk ON fu_ajk_spak.id_mitra = fu_ajk_grupproduk.id
INNER JOIN fu_ajk_costumer ON fu_ajk_spak.id_cost = fu_ajk_costumer.id
WHERE (fu_ajk_spak.`status` = "Aktif" OR fu_ajk_spak.`status` = "Tolak" OR fu_ajk_spak.`status` = "Realisasi" OR  fu_ajk_spak.`status` = "Kadaluarsa" ) AND
	   fu_ajk_spak.del IS NULL AND
	   fu_ajk_polis.typeproduk ="SPK" AND
	   fu_ajk_spak_form.del IS NULL ' . $satu . ' ' . $dua . '
ORDER BY fu_ajk_spak.id DESC
LIMIT ' . $m . ', 25');
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(fu_ajk_spak.id)
FROM fu_ajk_spak
INNER JOIN fu_ajk_spak_form ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
INNER JOIN fu_ajk_polis ON fu_ajk_spak.id_polis = fu_ajk_polis.id
INNER JOIN fu_ajk_grupproduk ON fu_ajk_spak.id_mitra = fu_ajk_grupproduk.id
INNER JOIN fu_ajk_costumer ON fu_ajk_spak.id_cost = fu_ajk_costumer.id
WHERE (fu_ajk_spak.`status` = "Aktif" OR fu_ajk_spak.`status` = "Tolak" OR fu_ajk_spak.`status` = "Realisasi" OR  fu_ajk_spak.`status` = "Kadaluarsa" ) AND
	   fu_ajk_spak.del IS NULL AND
	   fu_ajk_polis.typeproduk ="SPK" AND
	   fu_ajk_spak_form.del IS NULL ' . $satu . ' ' . $dua . ''));
$totalRows = $totalRows[0];
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while ($met_ = mysql_fetch_array($met)) {
	$met_company = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="' . $met_['id_cost'] . '"'));
    $metdata = mysql_fetch_array($database->doQuery('SELECT id, id_cost, spaj, type_data, nama FROM fu_ajk_peserta WHERE type_data="SPK" AND spaj="' . $met_['spak'] . '"'));
    $met_formspk = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_form WHERE idspk="' . $met_['id'] . '"'));
    $x_file = str_replace(' ', '%20', $met_['fname']);

    $tgl_inputnya = explode(" ", $met_['input_date']);
    $tgl_approvenya = explode(" ", $met_['update_date']);
if (($no % 2) == 1) $objlass = 'tbl-odd'; else $objlass = 'tbl-even';

if (is_numeric($met_['input_by'])) {
	$met_User = mysql_fetch_array($database->doQuery('SELECT id, nama, namalengkap FROM user_mobile WHERE id="'.$met_['input_by'].'"'));
	$inputby_met = $met_User['namalengkap'];
}else{
	$inputby_met = $met_['input_by'];
}

if (is_numeric($met_['update_by'])) {
	$met_UserSPV = mysql_fetch_array($database->doQuery('SELECT id, nama, namalengkap FROM user_mobile WHERE id="'.$met_['update_by'].'"'));
	$updateby_met = $met_UserSPV['namalengkap'];
}else{
	$updateby_met = $met_['update_by'];
}

if ($q['status'] == "UNDERWRITING" or $q['status'] == "SUPERVISOR") {
$approveEdit ='<a title="Edit data SPK" href="ajk_uploader_spak.php?r=edit_spak&ids=' . $met_['id'] . '&idform=' . $met_formspk['id'] . '"><img src="image/edit3.png" width="20"></a> &nbsp;
	<a title="Batal data SPK" href="ajk_uploader_spak.php?r=dell_spak&ids=' . $met_['id'] . '&idform=' . $met_formspk['id'] . '"><img src="image/deleted.png" width="20"></a>';
}

if ($met_['status']=="Realisasi") {
$metSPK_ = '<a href="../aajk_report.php?er=_spk&ids='.$met_['id'].'&fo=as" title="SPK untuk asuransi" target="_blank">' . $met_['spak'] . '</a>';
}else{
$metSPK_ = '' . $met_['spak'] . '';
}

if ($met_['fname']=="") {
	$metfname = '';
}else{
	$metfname = '<a href=' . $metpath_file . '' . $x_file . ' title="Lihat data SPK manual" target="_blank"><img src="image/dninvoice.png" width="20"></a>';
}
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	  <td align="center">' . (++$no + ($pageNow - 1) * 25) . '</td>
	  <td>'.strtoupper($met_['nmproduk']).'</td>
	  <td align="center">'.$metSPK_.'</td>
	  <td align="center">' . $met_formspk['noidentitas'] . '</td>
	  <td><a href="../aajk_report.php?er=_spk&ids='.$met_['id'].'" title="SPK untuk klient" target="_blank">'.strtoupper($met_formspk['nama']).'</a></td>
	  <td align="center">' . _convertDate($met_formspk['dob']) . '</td>
	  <td align="center">' . _convertDate($met_formspk['tgl_asuransi']) . '</td>
	  <td align="center">' . $met_formspk['tenor'] . '</td>
	  <td align="center">' . _convertDate($met_formspk['tgl_akhir_asuransi']) . '</td>
	  <td align="center">' . $met_formspk['mpp'] . '</td>
	  <td align="right">' . duit($met_formspk['plafond']) . '</td>
	  <td align="right">' . duit($met_formspk['x_premi']) . '</td>
	  <td align="center">' . $met_formspk['x_usia'] . '</td>
	  <td align="center"><a title="'.nl2br($met_['ket_ext']).'">'.$met_['ext_premi'].'</a></td>
	  <td align="center">' . $inputby_met . '</td>
	  <td align="center">' . $tgl_inputnya[0] . '</td>
	  <td align="center">' . $updateby_met . '</td>
	  <td align="center">' . $tgl_approvenya[0] . '</td>
	  <td align="center">'.$metfname.'</td>
	  <td align="center">' . $met_['status'] . '</td>
	  <td align="center">'.$approveEdit.'</td>
	</tr>';
        }
        echo '<tr><td colspan="17">';
        echo createPageNavigations($file = 'ajk_uploader_spak.php?r=spk_app&nospk='.$_REQUEST['nospk'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
        echo '<b>Total Data SPK: <u>' . $totalRows . '</u></b></td></tr>';
        echo '</table>';
    	echo '<tr><td colspan="16"><font color="1"> &nbsp;Note: Arahkan kursor pada kolom nilai EM untuk melihat keterangan extra premi.</font></td></tr>';
		;
        break;

case "spk_unapp":
        	echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="95%" align="left">Modul Data SPK</font></th></tr></table>';
        	echo '<table border="0" width="100%" cellpadding="1" cellspacing="1">
	  <form method="post" action="">
	  <tr><td align="right" width="10%">Nomor SPK :</td><td><input type="text" name="nospk" value="' . $_REQUEST['nospk'] . '"> </td></tr>
	  <tr><td align="right" width="10%">Nama Debitur :</td><td><input type="text" name="namaspk" value="' . $_REQUEST['namaspk'] . '"> <input type="submit" name="button" value="Cari" class="button"></td></tr>
	  </form></table>';
        	echo '<table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#bde0e6">
	 <tr><th width="1%">No</th>
	 	 <th>Produk</th>
	 	 <th width="1%">SPK</th>
	 	 <th>No.Identitas</th>
	 	 <th>Nama</th>
	 	 <th>Tgl Lahir</th>
	 	 <th>Tgl Akad</th>
	 	 <th>Tenor</th>
	 	 <th>Tgl Akhir</th>
	 	 <th>Plafond</th>
	 	 <th width="1%">Premi (x)</th>
	 	 <th width="1%">Usia (x)</th>
	 	 <th width="1%">Ex.Premi(%)</th>
<!-- 	 <th width="10%">Nama File</th>-->
	 	 <th width="5%">User Upload</th>
	 	 <th width="5%">Tgl Upload</th>
	 	 <th width="5%">User Approve</th>
	 	 <th width="5%">Tgl Approve</th>
	 	 <th width="5%">Status</th>
	 	 <th width="5%">Option</th>
	 </tr>';
        	if ($_REQUEST['x']) {	$m = ($_REQUEST['x'] - 1) * 25;	} else {	$m = 0;	}

        	if ($_REQUEST['nospk']) 	{	$satu = 'AND fu_ajk_spak.spak LIKE "%' . $_REQUEST['nospk'] . '%"';	}
        	if ($_REQUEST['namaspk']) 	{	$dua = 'AND fu_ajk_spak_form.nama LIKE "%' . $_REQUEST['namaspk'] . '%"';	}

        	/*
        	 if ($_REQUEST['namaspk']) {
        	 $ceknama = mysql_fetch_array($database->doQuery('SELECT fu_ajk_spak_form.id, fu_ajk_spak_form.idspk, fu_ajk_spak_form.nama, fu_ajk_spak.spak
        	 FROM fu_ajk_spak_form
        	 left Join fu_ajk_spak ON fu_ajk_spak_form.idspk = fu_ajk_spak.id
        	 WHERE fu_ajk_spak_form.nama="'.$_REQUEST['namaspk'].'"'));
        	 $dua = 'AND id = "' . $ceknama['idspk'] . '"';
        	 }
        	 */

        	//$met = $database->doQuery('SELECT * FROM fu_ajk_spak WHERE (status="Aktif" OR status="Tolak" OR status="Realisasi") ' . $satu . ' ' . $dua . ' AND del IS NULL ORDER BY id DESC LIMIT ' . $m . ', 25');
        	$met = $database->doQuery('SELECT
		fu_ajk_spak.id_cost,
		fu_ajk_costumer.`name`,
		fu_ajk_spak.id_polis,
		fu_ajk_polis.nmproduk,
		fu_ajk_spak.id,
		IF(fu_ajk_spak.id_mitra = "",1,fu_ajk_spak.id_mitra) AS idmitra,
		fu_ajk_grupproduk.nmproduk AS namamitra,
		fu_ajk_spak.spak,
		fu_ajk_spak.ket_ext,
		fu_ajk_spak.`status`,
		fu_ajk_spak_form.nama,
		fu_ajk_spak_form.dob,
		fu_ajk_spak_form.tgl_asuransi,
		fu_ajk_spak_form.tenor,
		fu_ajk_spak_form.tgl_akhir_asuransi,
		fu_ajk_spak_form.plafond,
		fu_ajk_spak_form.x_premi,
		fu_ajk_spak_form.x_usia,
		fu_ajk_spak.ext_premi,
		fu_ajk_spak.input_by,
		fu_ajk_spak.input_date,
		fu_ajk_spak.update_by,
		fu_ajk_spak.update_date
		FROM fu_ajk_spak
		INNER JOIN fu_ajk_spak_form ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
		INNER JOIN fu_ajk_polis ON fu_ajk_spak.id_polis = fu_ajk_polis.id
		LEFT JOIN fu_ajk_grupproduk ON fu_ajk_spak.id_mitra = fu_ajk_grupproduk.id
		INNER JOIN fu_ajk_costumer ON fu_ajk_spak.id_cost = fu_ajk_costumer.id
		WHERE fu_ajk_spak.`status`="Preapproval" and
	   fu_ajk_spak.del IS NULL AND
	   fu_ajk_spak_form.del IS NULL ' . $satu . ' ' . $dua . '
		ORDER BY fu_ajk_spak.id DESC
		LIMIT ' . $m . ', 25');

        	$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(fu_ajk_spak.id)

		FROM fu_ajk_spak
		INNER JOIN fu_ajk_spak_form ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
		INNER JOIN fu_ajk_polis ON fu_ajk_spak.id_polis = fu_ajk_polis.id
		LEFT JOIN fu_ajk_grupproduk ON fu_ajk_spak.id_mitra = fu_ajk_grupproduk.id
		INNER JOIN fu_ajk_costumer ON fu_ajk_spak.id_cost = fu_ajk_costumer.id
		WHERE fu_ajk_spak.`status`="Preapproval" and

        			/*fu_ajk_spak.`status` <> "Aktif" and fu_ajk_spak.`status` <> "Tolak" and fu_ajk_spak.`status` <> "Realisasi" and*/
	   fu_ajk_spak.del IS NULL AND
	   fu_ajk_spak_form.del IS NULL ' . $satu . ' ' . $dua . ''));
        	$totalRows = $totalRows[0];
        	$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
        	while ($met_ = mysql_fetch_array($met)) {
        		$met_company = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="' . $met_['id_cost'] . '"'));
        		$metdata = mysql_fetch_array($database->doQuery('SELECT id, id_cost, spaj, type_data, nama FROM fu_ajk_peserta WHERE type_data="SPK" AND spaj="' . $met_['spak'] . '"'));
        		$met_formspk = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_form WHERE idspk="' . $met_['id'] . '"'));
        		$x_file = str_replace(' ', '%20', $met_['fname']);

        		$tgl_inputnya = explode(" ", $met_['input_date']);
        		$tgl_approvenya = explode(" ", $met_['update_date']);
        		if (($no % 2) == 1) $objlass = 'tbl-odd'; else $objlass = 'tbl-even';

        		if (is_numeric($met_['input_by'])) {
        			$met_User = mysql_fetch_array($database->doQuery('SELECT id, nama, namalengkap FROM user_mobile WHERE id="'.$met_['input_by'].'"'));
        			$inputby_met = $met_User['namalengkap'];
        		}else{
        			$inputby_met = $met_['input_by'];
        		}

        		if (is_numeric($met_['update_by'])) {
        			$met_UserSPV = mysql_fetch_array($database->doQuery('SELECT id, nama, namalengkap FROM user_mobile WHERE id="'.$met_['update_by'].'"'));
        			$updateby_met = $met_UserSPV['namalengkap'];
        		}else{
        			$updateby_met = $met_['update_by'];
        		}

        		if ($q['status'] == "UNDERWRITING" OR $q['status'] == "") {
        			$approveEdit ='<a href="ajk_uploader_spak.php?r=edit_emspak&ids='.$met_['id'].'" title="Tambah nilai EM"><img src="image/edit3.png" width="20"></a> &nbsp;
        						   <a href="ajk_uploader_spak.php?r=tolak_spak1&ids='.$met_['id'].'" title="Tolak data preapproval"><img src="image/deleted.png" width="20"></a>';
        		}

        		if ($met_['status']=="Realisasi") {
        			$metSPK_ = '<a href="../aajk_report.php?er=_spk&ids='.$met_['id'].'&fo=as" title="SPK untuk asuransi" target="_blank">' . $met_['spak'] . '</a>';
        		}else{
        			$metSPK_ = '' . $met_['spak'] . '';
        		}
        		echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	  <td align="center">' . (++$no + ($pageNow - 1) * 25) . '</td>
	  <td>'.strtoupper($met_['nmproduk']).'</td>
	  <td align="center"><a href="../aajk_report.php?er=_spk&ids='.$met_['id'].'&mod=adn" title="SPK untuk asuransi" target="_blank">'.$metSPK_.'</a></td>
	  <td align="center">' . $met_formspk['noidentitas'] . '</td>
	  <td><a href="../aajk_report.php?er=_spk&ids='.$met_['id'].'" title="SPK untuk klient" target="_blank">'.strtoupper($met_formspk['nama']).'</a></td>
	  <td align="center">' . _convertDate($met_formspk['dob']) . '</td>
	  <td align="center">' . _convertDate($met_formspk['tgl_asuransi']) . '</td>
	  <td align="center">' . $met_formspk['tenor'] . '</td>
	  <td align="center">' . _convertDate($met_formspk['tgl_akhir_asuransi']) . '</td>
	  <td align="right">' . duit($met_formspk['plafond']) . '</td>
	  <td align="right">' . duit($met_formspk['x_premi']) . '</td>
	  <td align="center">' . $met_formspk['x_usia'] . '</td>
	  <td align="center"><a title="'.nl2br($met_['ket_ext']).'">'.$met_['ext_premi'].'</a></td>
	  <!--<td><a href=' . $metpath_file . '' . $x_file . ' target="_blank">' . $met_['fname'] . '</a></td>-->
	  <td align="center">' . $inputby_met . '</td>
	  <td align="center">' . $tgl_inputnya[0] . '</td>
	  <td align="center">' . $updateby_met . '</td>
	  <td align="center">' . $tgl_approvenya[0] . '</td>
	  <td align="center">' . $met_['status'] . '</td>
	  <td align="center">'.$approveEdit.'</td>
	</tr>';
        	}
        	echo '<tr><td colspan="17">';
        	echo createPageNavigations($file = 'ajk_uploader_spak.php?r=spk_unapp&nospk='.$_REQUEST['nospk'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
        	echo '<b>Total Data SPK: <u>' . $totalRows . '</u></b></td></tr>';
        	echo '</table>';
        	echo '<tr><td colspan="16"><font color="1"> &nbsp;Note: Arahkan kursor pada kolom nilai EM untuk melihat keterangan extra premi.</font></td></tr>';
        	;
        	break;

case "pcp":
echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="95%" align="left">Modul Data Percepatan</font></th></tr></table>';
		echo '<table border="0" width="100%" cellpadding="1" cellspacing="1">
		  <form method="post" action="">
		  <tr><td align="right" width="10%">Nomor SPK :</td><td><input type="text" name="nospk" value="' . $_REQUEST['nospk'] . '"> </td></tr>
		  <tr><td align="right" width="10%">Nama Debitur :</td><td><input type="text" name="namaspk" value="' . $_REQUEST['namaspk'] . '"> <input type="submit" name="button" value="Cari" class="button"></td></tr>
		  </form></table>';
		echo '<table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#bde0e6">
		 <tr><th width="1%">No</th>
		 	 <th>Produk</th>
		 	 <th width="1%">SPK</th>
		 	 <th>No.Identitas</th>
		 	 <th>Nama</th>
		 	 <th>Tgl Lahir</th>
		 	 <th>Tgl Akad</th>
		 	 <th>Tenor</th>
		 	 <th>Tgl Akhir</th>
		 	 <th>Plafond</th>
		 	 <th width="1%">Premi (x)</th>
		 	 <th width="1%">Usia (x)</th>
		 	 <th width="1%">Ex.Premi(%)</th>
		 	 <th width="5%">User Upload</th>
		 	 <th width="5%">Tgl Upload</th>
		 	 <th width="5%">User Approve</th>
		 	 <th width="5%">Tgl Approve</th>
		 	 <th width="5%">Status</th>
             <th width="5%">Option</th>
		 </tr>';
	if ($_REQUEST['x']) {	$m = ($_REQUEST['x'] - 1) * 25;	} else {	$m = 0;	}

	if ($_REQUEST['nospk']) 	{	$satu = 'AND fu_ajk_spak.spak LIKE "%' . $_REQUEST['nospk'] . '%"';	}
	if ($_REQUEST['namaspk']) 	{	$dua = 'AND fu_ajk_spak_form.nama LIKE "%' . $_REQUEST['namaspk'] . '%"';	}

//$met = $database->doQuery('SELECT * FROM fu_ajk_spak WHERE (status="Aktif" OR status="Tolak" OR status="Realisasi") ' . $satu . ' ' . $dua . ' AND del IS NULL ORDER BY id DESC LIMIT ' . $m . ', 25');
$met = $database->doQuery('SELECT
fu_ajk_spak.id_cost,
fu_ajk_costumer.`name`,
fu_ajk_spak.id_polis,
fu_ajk_polis.nmproduk,
fu_ajk_spak.id,
IF(fu_ajk_spak.id_mitra = "",1,fu_ajk_spak.id_mitra) AS idmitra,
fu_ajk_grupproduk.nmproduk AS namamitra,
fu_ajk_spak.fname,
fu_ajk_spak.spak,
fu_ajk_spak.ket_ext,
fu_ajk_spak.`status`,
fu_ajk_spak_form.nama,
fu_ajk_spak_form.dob,
fu_ajk_spak_form.tgl_asuransi,
(fu_ajk_spak_form.tenor * 12) AS tenor,
fu_ajk_spak_form.tgl_akhir_asuransi,
fu_ajk_spak_form.plafond,
fu_ajk_spak_form.x_premi,
fu_ajk_spak_form.x_usia,
fu_ajk_spak.ext_premi,
fu_ajk_spak.input_by,
fu_ajk_spak.input_date,
fu_ajk_spak.update_by,
fu_ajk_spak.update_date
FROM fu_ajk_spak
INNER JOIN fu_ajk_spak_form ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
INNER JOIN fu_ajk_polis ON fu_ajk_spak.id_polis = fu_ajk_polis.id
LEFT JOIN fu_ajk_grupproduk ON fu_ajk_spak.id_mitra = fu_ajk_grupproduk.id
INNER JOIN fu_ajk_costumer ON fu_ajk_spak.id_cost = fu_ajk_costumer.id
WHERE (fu_ajk_spak.`status` = "Aktif" OR fu_ajk_spak.`status` = "Tolak" OR fu_ajk_spak.`status` = "Realisasi" OR fu_ajk_spak.`status` = "Kadaluarsa") AND
	   fu_ajk_spak.del IS NULL AND
	   fu_ajk_polis.typeproduk ="NON SPK" AND
	   fu_ajk_spak_form.del IS NULL ' . $satu . ' ' . $dua . '
ORDER BY fu_ajk_spak.id DESC
LIMIT ' . $m . ', 25');
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(fu_ajk_spak.id)
FROM fu_ajk_spak
INNER JOIN fu_ajk_spak_form ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
INNER JOIN fu_ajk_polis ON fu_ajk_spak.id_polis = fu_ajk_polis.id
LEFT JOIN fu_ajk_grupproduk ON fu_ajk_spak.id_mitra = fu_ajk_grupproduk.id
INNER JOIN fu_ajk_costumer ON fu_ajk_spak.id_cost = fu_ajk_costumer.id
WHERE (fu_ajk_spak.`status` = "Aktif" OR fu_ajk_spak.`status` = "Tolak" OR fu_ajk_spak.`status` = "Realisasi"  OR fu_ajk_spak.`status` = "Kadaluarsa") AND
	   fu_ajk_spak.del IS NULL AND
	   fu_ajk_polis.typeproduk ="NON SPK" AND
	   fu_ajk_spak_form.del IS NULL ' . $satu . ' ' . $dua . ''));
	$totalRows = $totalRows[0];
	$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
		while ($met_ = mysql_fetch_array($met)) {
			$met_company = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="' . $met_['id_cost'] . '"'));
			$metdata = mysql_fetch_array($database->doQuery('SELECT id, id_cost, spaj, type_data, nama FROM fu_ajk_peserta WHERE type_data="SPK" AND spaj="' . $met_['spak'] . '"'));
			$met_formspk = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_form WHERE idspk="' . $met_['id'] . '"'));
			$x_file = str_replace(' ', '%20', $met_['fname']);

			$tgl_inputnya = explode(" ", $met_['input_date']);
			$tgl_approvenya = explode(" ", $met_['update_date']);
			if (($no % 2) == 1) $objlass = 'tbl-odd'; else $objlass = 'tbl-even';

			if (is_numeric($met_['input_by'])) {
				$met_User = mysql_fetch_array($database->doQuery('SELECT id, nama, namalengkap FROM user_mobile WHERE id="'.$met_['input_by'].'"'));
				$inputby_met = $met_User['namalengkap'];
			}else{
				$inputby_met = $met_['input_by'];
			}

			if (is_numeric($met_['update_by'])) {
				$met_UserSPV = mysql_fetch_array($database->doQuery('SELECT id, nama, namalengkap FROM user_mobile WHERE id="'.$met_['update_by'].'"'));
				$updateby_met = $met_UserSPV['namalengkap'];
			}else{
				$updateby_met = $met_['update_by'];
			}

			if ($q['status'] == "UNDERWRITING") {
				$approveEdit ='<a title="Edit data SPK" href="ajk_uploader_spak.php?r=edit_percepatan&ids=' . $met_['id'] . '&idform=' . $met_formspk['id'] . '"><img src="image/edit3.png" width="20"></a>';
			}

			if ($met_['status']=="Realisasi") {
				$metSPK_ = '<a href="../aajk_report.php?er=_spk&ids='.$met_['id'].'&mod=adn&fo=as" title="SPK untuk asuransi" target="_blank">' . $met_['spak'] . '</a>';
			}else{
				$metSPK_ = '' . $met_['spak'] . '';
			}

			if ($met_['fname']=="") {
				$metfname = '';
			}else{
				$metfname = '<a href=' . $metpath_file . '' . $x_file . ' title="Lihat data SPK manual" target="_blank"><img src="image/dninvoice.png" width="20"></a>';
			}
			echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
			  <td align="center">' . (++$no + ($pageNow - 1) * 25) . '</td>
			  <td>'.strtoupper($met_['nmproduk']).'</td>
			  <td align="center">'.$metSPK_.'</td>
			  <td align="center">' . $met_formspk['noidentitas'] . '</td>
			  <td><a href="../aajk_report.php?er=_spk&ids='.$met_['id'].'&mod=adn" title="SPK untuk klient" target="_blank">'.strtoupper($met_formspk['nama']).'</a></td>
			  <td align="center">' . _convertDate($met_formspk['dob']) . '</td>
			  <td align="center">' . _convertDate($met_formspk['tgl_asuransi']) . '</td>
			  <td align="center">' . $met_formspk['tenor'] . '</td>
			  <td align="center">' . _convertDate($met_formspk['tgl_akhir_asuransi']) . '</td>
			  <td align="right">' . duit($met_formspk['plafond']) . '</td>
			  <td align="right">' . duit($met_formspk['x_premi']) . '</td>
			  <td align="center">' . $met_formspk['x_usia'] . '</td>
			  <td align="center"><a title="'.nl2br($met_['ket_ext']).'">'.$met_['ext_premi'].'</a></td>
			  <td align="center">' . $inputby_met . '</td>
			  <td align="center">' . $tgl_inputnya[0] . '</td>
			  <td align="center">' . $updateby_met . '</td>
			  <td align="center">' . $tgl_approvenya[0] . '</td>
			  <td align="center">' . $met_['status'] . '</td>
              <td align="center">' . $approveEdit . '</td>
			</tr>';
		}
	echo '<tr><td colspan="17">';
	echo createPageNavigations($file = 'ajk_uploader_spak.php?r=pcp&nospk='.$_REQUEST['nospk'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
	echo '<b>Total Data Percepatan (melalui tab): <u>' . $totalRows . '</u></b></td></tr>';
	echo '</table>';
	;
	break;


case "spkaktif": //HANSEN 20161003 perubahan sesuai keinginan user
echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="95%" align="left">Modul Data SPK</font></th><th align="center"><a href="ajk_uploader_spak.php?r=spkaktifview&jmlhr='.$_REQUEST['jumlahhari'].'" title="SPK kadaluarsa PerCabang"><img src="image/spk_form.png" width="25"></a></th></tr></table>';
$metprod = $database->doQuery('SELECT * FROM fu_ajk_polis WHERE typeproduk="SPK" AND id_cost="1" AND del IS NULL ORDER BY nmproduk DESC');
$metCabang = $database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id_cost="1" AND del IS NULL ORDER BY name ASC');
echo '<table border="0" width="100%" cellpadding="1" cellspacing="1">
	  <form method="post" action="" name="postform">
	  <tr><td align="right" width="10%">Cabang</td><td> : <select name="idcabang" id="idcabang">
				 <option value="">---Pilih Cabang---</option>';
	while($metCabang_ = mysql_fetch_array($metCabang)) {
		echo  '<option value="'.$metCabang_['id'].'"'._selected($metCabang_['idcabang'], $metCabang_['id']).'>'.$metCabang_['name'].'</option>';
	}
		echo '</select></td>
		</tr>
		<tr><td align="right">Selisih Hari</td><td>: <input type="text" name="jumlahhari" value="' . $_REQUEST['jumlahhari'] . '"/></td></tr>
		<tr><td align="right"><input type="submit" name="button" value="Cari" class="button"></td></tr>
	  </form></table>';
echo '<table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#bde0e6">
	 <tr><th width="1%">No</th>
	 	 <th width="10%">Produk</th>
	 	 <th width="10%">No. SPK</th>
	 	 <th width="20%">Nama</th>
	 	 <th width="10%">Plafond</th>
	 	 <th width="5%">Tenor</th>
	 	 <th width="15%">Cabang</th>
	 	 <th width="15%">Tgl Input</th>
	 	 <th width="15%">Tgl Kadaluarsa</th>
	 	 <th width="15%">Selisih</th>
	 </tr>';
if ($_REQUEST['idcabang'])			{	$dua = 'AND cabang = "'.$_REQUEST['idcabang'].'"';	}
if ($_REQUEST['jumlahhari'])			{	$tiga = 'AND selisih <= '.$_REQUEST['jumlahhari'].'';	}

$cekMetSPK = $database->doQuery('SELECT nmproduk,
																				spak,
																				nama,
																				plafond,
																				tenor,
																				name,
																				DATE_FORMAT(input_date,"%Y-%m-%d") as input_date,
																				DATE_FORMAT(tgl_kadaluarsa,"%Y-%m-%d") as tgl_kadaluarsa,
																				selisih
from(
select fu_ajk_polis.nmproduk,
				fu_ajk_spak.spak,
				fu_ajk_spak_form.nama,
				fu_ajk_spak_form.plafond,
				fu_ajk_spak_form.tenor,
				fu_ajk_spak_form.cabang,
				fu_ajk_cabang.name,
				fu_ajk_spak.input_date,
				fu_ajk_spak.id_polis,
				DATE_ADD(fu_ajk_spak.input_date,INTERVAL 3 MONTH)as tgl_kadaluarsa,
				CASE WHEN DATEDIFF(DATE_ADD(fu_ajk_spak.input_date,INTERVAL 3 MONTH),CURDATE()) < 1 THEN
					"EXPIRED"
				else
					DATEDIFF(DATE_ADD(fu_ajk_spak.input_date,INTERVAL 3 MONTH),CURDATE())
				end as selisih
from fu_ajk_spak
		 inner join fu_ajk_spak_form
		 on fu_ajk_spak_form.idspk = fu_ajk_spak.id
		 left JOIN fu_ajk_polis
		 on fu_ajk_polis.id = fu_ajk_spak.id_polis
		 left JOIN fu_ajk_cabang
		 on fu_ajk_cabang.id = fu_ajk_spak_form.cabang
where fu_ajk_spak.del is null and
			fu_ajk_spak_form.del is NULL and
			fu_ajk_spak.STATUS = "aktif" and
			fu_ajk_spak.id_polis in (1,12,15) and
			fu_ajk_spak.input_date !="0000-00-00 00:00:00"
)as temp
where selisih != "EXPIRED" '.$dua.' '.$tiga.'
GROUP BY input_date');



while ($cekMetSPK_ = mysql_fetch_array($cekMetSPK)) {
if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		  <td align="center">' . ++$no . '</td>
	  	  <td>'.$cekMetSPK_['nmproduk'].'</td>
	  	  <td align="center">'.$cekMetSPK_['spak'].'</td>
	  	  <td>'.$cekMetSPK_['nama'].'</td>
	  	  <td align="center">'.duit($cekMetSPK_['plafond']).'</td>
	  	  <td align="center">'.$cekMetSPK_['tenor'].'</td>
	  	  <td>'.$cekMetSPK_['name'].'</td>
	  	  <td align="center">'._convertDate($cekMetSPK_['input_date']).'</td>
	  	  <td align="center">'._convertDate($cekMetSPK_['tgl_kadaluarsa']).'</td>
	  	  <td align="center">'.$cekMetSPK_['selisih'].'</td>
	  </tr>';
$jDataSPK += $cekMetSPK_['spak'];
}
echo '<tr><td colspan="2">';
echo createPageNavigations($file = 'ajk_uploader_spak.php?r=spk_app&nospk='.$_REQUEST['nospk'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
echo '<b>Total Data SPK: <u>' . $totalRows . '</u></b></td>
	  <td align="center">'.$no.'</td></tr>';
echo '</table>';
	;
	break;

case "spkaktifview":
echo '<table border="0" cellpadding="0" cellspacing=	"0" width="100%"><tr><th width="95%" align="left">Modul Data SPK Kadaluarsa</font></th><th align="center"><a href="ajk_uploader_spak.php?r=spkaktif" title="spk reguler kadaluarsa"><img src="image/back.png" width="25"></a></th></tr></table>';
echo '<a href="ajk_uploader_spak.php?r=spkaktifmail&slsh='.$_REQUEST['slsh'].'&cab='.$_REQUEST['cab'].'&dt='.$_REQUEST['dt'].'"><img src="../image/upload_data.png" width="40" title="email data spk"></a>
	 <table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#bde0e6">
	 <tr><th width="1%">No</th>
	 	 <th>Nama Cabang</th>
	 	 <th width="10%">Jumlah SPK</th>
		 <th width="5%">Option</th>
	 </tr>';
//if ($_REQUEST['slsh'])		{	$satu = 'AND datediff(current_date(), DATE_FORMAT(fu_ajk_spak.input_date + INTERVAL "3" MONTH,"%Y-%m-%d")) = "'.$_REQUEST['slsh'].'"';	}
if ($_REQUEST['cab'])		{	$dua = 'AND fu_ajk_spak_form.cabang = "'.$_REQUEST['cab'].'"';	}
if ($_REQUEST['dt'])	{	$empat = 'AND (datediff("'.$_REQUEST['dt'].'", DATE_FORMAT(fu_ajk_spak.input_date + INTERVAL "3" MONTH,"%Y-%m-%d")) BETWEEN '.$_REQUEST['slsh'].' AND '.$_REQUEST['slsh'].')';	}

if($_REQUEST['jmlhr'] == ""){$jml_hari = 90;}else{$jml_hari = $_REQUEST['jmlhr'];}

$cekMetSPK = $database->doQuery('SELECT name,count(spak)as Jspak
from(
select fu_ajk_polis.nmproduk,
				fu_ajk_spak.spak,
				fu_ajk_spak_form.nama,
				fu_ajk_spak_form.plafond,
				fu_ajk_spak_form.tenor,
				fu_ajk_cabang.name,
				fu_ajk_spak.input_date,
				DATE_ADD(fu_ajk_spak.input_date,INTERVAL 3 MONTH)as tgl_kadaluarsa,
				CASE WHEN DATEDIFF(DATE_ADD(fu_ajk_spak.input_date,INTERVAL 3 MONTH),CURDATE()) < 1 THEN
					"EXPIRED"
				else
					DATEDIFF(DATE_ADD(fu_ajk_spak.input_date,INTERVAL 3 MONTH),CURDATE())
				end as selisih
from fu_ajk_spak
		 inner join fu_ajk_spak_form
		 on fu_ajk_spak_form.idspk = fu_ajk_spak.id
		 left JOIN fu_ajk_polis
		 on fu_ajk_polis.id = fu_ajk_spak.id_polis
		 left JOIN fu_ajk_cabang
		 on fu_ajk_cabang.id = fu_ajk_spak_form.cabang
where fu_ajk_spak.del is null and
			fu_ajk_spak_form.del is NULL and
			fu_ajk_spak.STATUS = "aktif" and
			fu_ajk_spak.id_polis in (1,12,15) and
			fu_ajk_spak.input_date !="0000-00-00 00:00:00"
)as temp
where selisih != "EXPIRED" and selisih <= '.$jml_hari.'
GROUP BY name');
		$sumspk = 0;
		while ($cekMetSPK_ = mysql_fetch_array($cekMetSPK)) {
			if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
			echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
			  <td align="center">' . ++$no . '</td>
		  	  <td>'.$cekMetSPK_['name'].'</td>
		  	  <td align="center">'.$cekMetSPK_['Jspak'].'</td>
					<td align="center"><a href="e_report.php?er=spkkadaluarsa&jmlhr='.$_REQUEST['jmlhr'].'&nmcabang='.$cekMetSPK_['name'].'" title="Email Cabang">Email</a> &nbsp; '.$setemail.'</td>
		  </tr>';
		  $sumspk = $sumspk + $cekMetSPK_['Jspak'];
		}
echo '<tr><td colspan="2">';
echo '<b>Total Data SPK: <u>' . $totalRows . '</u></b></td>
	  <td align="center">'.$sumspk.'</td></tr>';
	echo '</table>';
	;
	break;
	// END HANSEN

case "spkaktif_backup20161003":
echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="95%" align="left">Modul Data SPK</font></th><th align="center"><a href="ajk_uploader_spak.php?r=spk_app" title="spk reguler kadaluarsa"><img src="image/back.png" width="25"></a></th></tr></table>';
$metprod = $database->doQuery('SELECT * FROM fu_ajk_polis WHERE typeproduk="SPK" AND id_cost="1" AND del IS NULL ORDER BY nmproduk DESC');
$metCabang = $database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id_cost="1" AND del IS NULL ORDER BY name ASC');
echo '<table border="0" width="100%" cellpadding="1" cellspacing="1">
	  <form method="post" action="" name="postform">
	  <tr><td align="right" width="10%">Nama Produk</td>
		  <td> : <select name="idproduk" id="idproduk">
				 <option value="">---Pilih Produk---</option>';
while($metprod_ = mysql_fetch_array($metprod)) {
echo  '<option value="'.$metprod_['id'].'"'._selected($_REQUEST['idproduk'], $metprod_['id']).'>'.$metprod_['nmproduk'].'</option>';
	}
echo '</select></td>
	  </tr>
	  <tr><td align="right" width="10%">Cabang</td><td> : <select name="idcabang" id="idcabang">
				 <option value="">---Pilih Cabang---</option>';
	while($metCabang_ = mysql_fetch_array($metCabang)) {
		echo  '<option value="'.$metCabang_['id'].'"'._selected($metCabang_['idcabang'], $metCabang_['id']).'>'.$metCabang_['name'].'</option>';
	}
		echo '</select></td>
		</tr>
		<!--<tr><td align="right">Jumlah Hari</td><td>: <input type="text" name="jumlahhari" value="' . $_REQUEST['jumlahhari'] . '"/></td></tr>-->
		<tr><td align="right">Pengecekan Tanggal</td><td>:
				 <input type="text" id="from" name="tanggalpengecekan" value="'.$_REQUEST['tanggalpengecekan'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.from);return false;"/>
				 <a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.from);return false;"></a></div>
				 <iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
				 </td></tr>
		<tr><td align="right"><input type="submit" name="button" value="Cari" class="button"></td></tr>
	  </form></table>';
echo '<table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#bde0e6">
	 <tr><th width="1%">No</th>
	 	 <th>Produk</th>
	 	 <th width="10%">Jumlah SPK</th>
	 	 <th width="10%">Tanggal Input</th>
	 	 <th width="10%">Tanggal Batas SPK<br />(3 bln)</th>
	 	 <!--<th width="10%">Tanggal Sekarang</th>-->
	 	 <th width="10%">Selisih Hari</th>
	 	 <th width="15%">Cabang</th>
	 	 <th width="5%">Option</th>
	 </tr>';
if ($_REQUEST['idproduk'])			{	$satu = 'AND fu_ajk_spak.id_polis = "'.$_REQUEST['idproduk'].'"';	}
if ($_REQUEST['idcabang'])			{	$dua = 'AND fu_ajk_spak_form.cabang = "'.$_REQUEST['idcabang'].'"';	}
if ($_REQUEST['jumlahhari'])		{	$tiga = 'AND datediff("'.$_REQUEST['tanggalpengecekan'].'", DATE_FORMAT(fu_ajk_spak.input_date + INTERVAL "3" MONTH,"%Y-%m-%d")) = "'.$_REQUEST['jumlahhari'].'"';	}
if ($_REQUEST['tanggalpengecekan'])	{	$empat = 'AND (datediff("'.$_REQUEST['tanggalpengecekan'].'", DATE_FORMAT(fu_ajk_spak.input_date + INTERVAL "3" MONTH,"%Y-%m-%d")) BETWEEN 75 AND 90)';	}

$cekMetSPK = $database->doQuery('SELECT
fu_ajk_spak.id,
fu_ajk_spak.id_cost,
fu_ajk_spak.id_polis,
fu_ajk_polis.nmproduk,
COUNT(fu_ajk_spak.spak) as jSPK,
fu_ajk_spak.`status`,
fu_ajk_spak_form.nama,
fu_ajk_spak_form.cabang,
fu_ajk_cabang.`name` AS nmcabang,
fu_ajk_spak.input_date,
DATE_FORMAT(fu_ajk_spak.input_date, "%Y-%m-%d") AS tglinput,
DATE_FORMAT(fu_ajk_spak.input_date + INTERVAL "3" MONTH,"%Y-%m-%d") AS 3bln,
date_format("'.$_REQUEST['tanggalpengecekan'].'", "%Y-%m-%d") AS tglskrg,
datediff("'.$_REQUEST['tanggalpengecekan'].'", DATE_FORMAT(fu_ajk_spak.input_date + INTERVAL "3" MONTH,"%Y-%m-%d")) as selisih
FROM fu_ajk_spak
INNER JOIN fu_ajk_spak_form ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
INNER JOIN fu_ajk_polis ON fu_ajk_spak.id_polis = fu_ajk_polis.id
INNER JOIN fu_ajk_cabang ON fu_ajk_spak_form.cabang = fu_ajk_cabang.id
WHERE
fu_ajk_spak.`status` = "aktif" AND
fu_ajk_spak.del IS NULL AND
fu_ajk_spak_form.del IS NULL AND
fu_ajk_spak.input_date !="0000-00-00 00:00:00"
'.$satu.' '.$dua.' '.$tiga.' '.$empat.'
GROUP BY
datediff("'.$_REQUEST['tanggalpengecekan'].'", DATE_FORMAT(fu_ajk_spak.input_date + INTERVAL "3" MONTH,"%Y-%m-%d")),
fu_ajk_spak_form.cabang
ORDER BY
fu_ajk_cabang.`name` ASC,
datediff("'.$_REQUEST['tanggalpengecekan'].'", DATE_FORMAT(fu_ajk_spak.input_date + INTERVAL "3" MONTH,"%Y-%m-%d")) DESC');
while ($cekMetSPK_ = mysql_fetch_array($cekMetSPK)) {
if ($cekMetSPK_['selisih'] >= 75 AND $cekMetSPK_['selisih'] <= 90) {
	$setselisih = '<font color="orange"><b>'.$cekMetSPK_['selisih'].'</b></font>';
	$setemail = '<a href="ajk_uploader_spak.php?r=spkaktifmail&slsh='.$cekMetSPK_['selisih'].'&cab='.$cekMetSPK_['cabang'].'&dt='.$_REQUEST['tanggalpengecekan'].'" title="email data spk cabang">Email</a>';
}elseif ($cekMetSPK_['selisih'] >= 90) {
	$setselisih = '<font color="red">'.$cekMetSPK_['selisih'].'</font>';
	$setemail = '<a href="ajk_uploader_spak.php?r=spkaktifmail&slsh='.$cekMetSPK_['selisih'].'&cab='.$cekMetSPK_['cabang'].'&dt='.$_REQUEST['tanggalpengecekan'].'" title="email data spk cabang">Email</a>';
}else{
	$setselisih = '<font color="#"><b>'.$cekMetSPK_['selisih'].'</b></font>';
	$setemail ='';
}
if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		  <td align="center">' . ++$no . '</td>
	  	  <td>'.$cekMetSPK_['nmproduk'].'</td>
	  	  <td align="center">'.$cekMetSPK_['jSPK'].'</td>
	  	  <td align="center">'._convertDate($cekMetSPK_['tglinput']).'</td>
	  	  <td align="center">'._convertDate($cekMetSPK_['3bln']).'</td>
	  	  <!--<td align="center">'._convertDate($cekMetSPK_['tglskrg']).'</td>-->
	  	  <td align="center">'.$setselisih.'</td>
	  	  <td>'.$cekMetSPK_['nmcabang'].'</td>
	  	  <td align="center"><a href="ajk_uploader_spak.php?r=spkaktifview&slsh='.$cekMetSPK_['selisih'].'&cab='.$cekMetSPK_['cabang'].'&dt='.$_REQUEST['tanggalpengecekan'].'" title="lihat data spk">View</a> &nbsp; '.$setemail.'</td>
	  </tr>';
$jDataSPK += $cekMetSPK_['jSPK'];
}
echo '<tr><td colspan="2">';
echo createPageNavigations($file = 'ajk_uploader_spak.php?r=spk_app&nospk='.$_REQUEST['nospk'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
echo '<b>Total Data SPK: <u>' . $totalRows . '</u></b></td>
	  <td align="center">'.$jDataSPK.'</td></tr>';
echo '</table>';
	;
	break;

case "spkaktifview_backup20161003":
echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="95%" align="left">Modul Data SPK</font></th><th align="center"><a href="ajk_uploader_spak.php?r=spkaktif" title="spk reguler kadaluarsa"><img src="image/back.png" width="25"></a></th></tr></table>';
echo '<a href="ajk_uploader_spak.php?r=spkaktifmail&slsh='.$_REQUEST['slsh'].'&cab='.$_REQUEST['cab'].'&dt='.$_REQUEST['dt'].'"><img src="../image/upload_data.png" width="40" title="email data spk"></a>
	 <table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#bde0e6">
	 <tr><th width="1%">No</th>
	 	 <th>Produk</th>
	 	 <th width="10%">Nomor SPK</th>
	 	 <th width="10%">Tanggal Input</th>
	 	 <th width="10%">Tanggal Batas SPK<br />(3 bln)</th>
	 	 <th width="10%">Tanggal Sekarang</th>
	 	 <th width="10%">Selisih Hari</th>
	 	 <th width="15%">Cabang</th>
	 </tr>';
//if ($_REQUEST['slsh'])		{	$satu = 'AND datediff(current_date(), DATE_FORMAT(fu_ajk_spak.input_date + INTERVAL "3" MONTH,"%Y-%m-%d")) = "'.$_REQUEST['slsh'].'"';	}
if ($_REQUEST['cab'])		{	$dua = 'AND fu_ajk_spak_form.cabang = "'.$_REQUEST['cab'].'"';	}
if ($_REQUEST['dt'])	{	$empat = 'AND (datediff("'.$_REQUEST['dt'].'", DATE_FORMAT(fu_ajk_spak.input_date + INTERVAL "3" MONTH,"%Y-%m-%d")) BETWEEN '.$_REQUEST['slsh'].' AND '.$_REQUEST['slsh'].')';	}

$cekMetSPK = $database->doQuery('SELECT
fu_ajk_spak.id,
fu_ajk_spak.id_cost,
fu_ajk_spak.id_polis,
fu_ajk_polis.nmproduk,
fu_ajk_spak.spak,
fu_ajk_spak.`status`,
fu_ajk_spak_form.nama,
fu_ajk_spak_form.cabang,
fu_ajk_cabang.`name` AS nmcabang,
fu_ajk_spak.input_date,
DATE_FORMAT(fu_ajk_spak.input_date, "%Y-%m-%d") AS tglinput,
DATE_FORMAT(fu_ajk_spak.input_date + INTERVAL "3" MONTH,"%Y-%m-%d") AS 3bln,
date_format("'.$_REQUEST['dt'].'", "%Y-%m-%d") AS tglskrg,
datediff("'.$_REQUEST['dt'].'", DATE_FORMAT(fu_ajk_spak.input_date + INTERVAL "3" MONTH,"%Y-%m-%d")) as selisih
FROM fu_ajk_spak
INNER JOIN fu_ajk_spak_form ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
INNER JOIN fu_ajk_polis ON fu_ajk_spak.id_polis = fu_ajk_polis.id
INNER JOIN fu_ajk_cabang ON fu_ajk_spak_form.cabang = fu_ajk_cabang.id
WHERE
fu_ajk_spak.`status` = "aktif" AND
fu_ajk_spak.del IS NULL AND
fu_ajk_spak_form.del IS NULL AND
fu_ajk_spak.input_date !="0000-00-00 00:00:00"
'.$satu.' '.$dua.' '.$tiga.' '.$empat.'
ORDER BY
fu_ajk_cabang.`name` ASC,
datediff("'.$_REQUEST['dt'].'", DATE_FORMAT(fu_ajk_spak.input_date + INTERVAL "3" MONTH,"%Y-%m-%d")) DESC');
		while ($cekMetSPK_ = mysql_fetch_array($cekMetSPK)) {
			if ($cekMetSPK_['selisih'] >= 75 AND $cekMetSPK_['selisih'] <= 90) {
				$setemail = '<font color="orange"><b>'.$cekMetSPK_['selisih'].'</b></font>';
			}elseif ($cekMetSPK_['selisih'] >= 90) {
				$setemail = '<font color="red">'.$cekMetSPK_['selisih'].'</font>';
			}else{
				$setemail = '<font color="#"><b>'.$cekMetSPK_['selisih'].'</b></font>';
			}
			if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
			echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
			  <td align="center">' . ++$no . '</td>
		  	  <td>'.$cekMetSPK_['nmproduk'].'</td>
		  	  <td align="center">'.$cekMetSPK_['spak'].'</td>
		  	  <td align="center">'._convertDate($cekMetSPK_['tglinput']).'</td>
		  	  <td align="center">'._convertDate($cekMetSPK_['3bln']).'</td>
		  	  <td align="center">'._convertDate($cekMetSPK_['tglskrg']).'</td>
		  	  <td align="center">'.$setemail.'</td>
		  	  <td>'.$cekMetSPK_['nmcabang'].'</td>
		  </tr>';
		}
	echo '</table>';
	;
	break;

case "spkaktifmail":
include_once('../includes/smtp_classes/PHPMailerAutoload.php'); // include the class smtp
//echo $_REQUEST['slsh'].'<br />';
//echo $_REQUEST['cab'].'<br />';
	$mail = new PHPMailer;
	$mail->IsSMTP();
	$mail->Host = SMTP_HOST; //Hostname of the mail server
	$mail->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
	$mail->Username = SMTP_UNAME; //Username for SMTP authentication any valid email created in your domain
	$mail->Password = SMTP_PWORD; //Password for SMTP authentication
	$mail->AddReplyTo = SMTP_REPLYMAIL; //reply-to address
	$mail->debug = 1;
	$mail->SMTPAuth   = true;
	$mail->SMTPSecure = "ssl";
	$mail->SetFrom ($q['email'], $q['namalengkap']);
	$mail->Subject = "AJKOnline - Data SPK";

$message .= '<table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#bde0e6">
	 <tr><th width="1%">No</th>
	 	 <th>Produk</th>
	 	 <th width="10%">Nomor SPK</th>
	 	 <th width="10%">Tanggal Input</th>
	 	 <th width="10%">Tanggal Batas SPK<br />(3 bln)</th>
	 	 <th width="10%">Tanggal Sekarang</th>
	 	 <th width="10%">Selisih Hari</th>
	 	 <th width="15%">Cabang</th>
	 </tr>';
//if ($_REQUEST['slsh'])		{	$satu = 'AND datediff(current_date(), DATE_FORMAT(fu_ajk_spak.input_date + INTERVAL "3" MONTH,"%Y-%m-%d")) = "'.$_REQUEST['slsh'].'"';	}
if ($_REQUEST['cab'])	{	$dua = 'AND fu_ajk_spak_form.cabang = "'.$_REQUEST['cab'].'"';	}
if ($_REQUEST['dt'])	{	$empat = 'AND (datediff("'.$_REQUEST['dt'].'", DATE_FORMAT(fu_ajk_spak.input_date + INTERVAL "3" MONTH,"%Y-%m-%d")) BETWEEN '.$_REQUEST['slsh'].' AND '.$_REQUEST['slsh'].')';	}

$cekMetSPK = $database->doQuery('SELECT
fu_ajk_spak.id,
fu_ajk_spak.id_cost,
fu_ajk_spak.id_polis,
fu_ajk_polis.nmproduk,
fu_ajk_spak.spak,
fu_ajk_spak.`status`,
fu_ajk_spak_form.nama,
fu_ajk_spak_form.cabang,
fu_ajk_cabang.`name` AS nmcabang,
fu_ajk_spak.input_date,
DATE_FORMAT(fu_ajk_spak.input_date, "%Y-%m-%d") AS tglinput,
DATE_FORMAT(fu_ajk_spak.input_date + INTERVAL "3" MONTH,"%Y-%m-%d") AS 3bln,
date_format("'.$_REQUEST['dt'].'", "%Y-%m-%d") AS tglskrg,
datediff("'.$_REQUEST['dt'].'", DATE_FORMAT(fu_ajk_spak.input_date + INTERVAL "3" MONTH,"%Y-%m-%d")) as selisih
FROM fu_ajk_spak
INNER JOIN fu_ajk_spak_form ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
INNER JOIN fu_ajk_polis ON fu_ajk_spak.id_polis = fu_ajk_polis.id
INNER JOIN fu_ajk_cabang ON fu_ajk_spak_form.cabang = fu_ajk_cabang.id
WHERE
fu_ajk_spak.`status` = "aktif" AND
fu_ajk_spak.del IS NULL AND
fu_ajk_spak_form.del IS NULL AND
fu_ajk_spak.input_date !="0000-00-00 00:00:00"
'.$satu.' '.$dua.' '.$tiga.' '.$empat.'
ORDER BY
fu_ajk_cabang.`name` ASC,
datediff("'.$_REQUEST['dt'].'", DATE_FORMAT(fu_ajk_spak.input_date + INTERVAL "3" MONTH,"%Y-%m-%d")) DESC');
while ($cekMetSPK_ = mysql_fetch_array($cekMetSPK)) {
if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
$message .= '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		  <td align="center">' . ++$no . '</td>
		  	  <td>'.$cekMetSPK_['nmproduk'].'</td>
		  	  <td align="center">'.$cekMetSPK_['spak'].'</td>
		  	  <td align="center">'._convertDate($cekMetSPK_['tglinput']).'</td>
		  	  <td align="center">'._convertDate($cekMetSPK_['3bln']).'</td>
		  	  <td align="center">'._convertDate($cekMetSPK_['tglskrg']).'</td>
		  	  <td align="center">'.$cekMetSPK_['selisih'].'</td>
		  	  <td>'.$cekMetSPK_['nmcabang'].'</td>
		  </tr>';
	}
$message .= '</table>';
	//$mail->AddAddress($mailStaff['email'], $mailStaff['namalengkap']); //To address who will receive this email
	$mail->AddAddress("hansen@adonai.co.id", "Hansen"); //To address who will receive this email
	$mail->AddCC("rahmad@adonaits.co.id");
	$mail->MsgHTML($message); //Put your body of the message you can place html code here
	$send = $mail->Send(); //Send the mails
//echo $message;
echo '<center>Data SPK telah diemail.<br /><meta http-equiv="refresh" content="3;URL=ajk_uploader_spak.php?r=spkaktif"></center>';
	;
	break;

case "edit_emspak":
	if(isset($_REQUEST['save_em'])){

		$futgl = date("Ymd His");
		if(isset($_FILES['file_att'])){
			$errors= array();
			$file_name = $_FILES['file_att']['name'];
			$file_size =$_FILES['file_att']['size'];
			$file_tmp =$_FILES['file_att']['tmp_name'];
			$file_type=$_FILES['file_att']['type'];
			$file_ext=strtolower(end(explode('.',$_FILES['file_att']['name'])));

			$expensions= array("jpeg","jpg","png","pdf");

			if(in_array($file_ext,$expensions)=== false){
				$errors[]="extension not allowed, please choose a JPEG, PNG or PDF file.";
			}

			if($file_size > 2097152){
				$errors[]='File size must be excately 2 MB';
			}

			if(empty($errors)==true){
				move_uploaded_file($file_tmp,"../ajk_file/medical/SPK_MP_".$futgl."-".$file_name);

				$query="update fu_ajk_spak set
				ext_premi='".$_REQUEST['nilai_em']."',
				status='Approve',
				fname='SPK_MP_".$futgl."-".$file_name."',
				update_by='" . $_SESSION['nm_user'] . "',
   				update_date='" . $futgl . "'
   				where id=".$_REQUEST['ids'];
				mysql_query($query);
				echo '<center>SPK telah berhasil di tambahkan nilai EM.<br /><meta http-equiv="refresh" content="3;URL=ajk_uploader_spak.php?r=spk_unapp"></center>';

			}else{
				print_r($errors);
			}
		}else{
			$query="update fu_ajk_spak set
				ext_premi='".$_REQUEST['nilai_em']."',
				status='Approve',
				update_by='" . $_SESSION['nm_user'] . "',
   				update_date='" . $futgl . "'
   				where id=".$_REQUEST['ids'];
			mysql_query($query);
			echo '<center>SPK telah berhasil di tambahkan nilai EM.<br /><meta http-equiv="refresh" content="3;URL=ajk_uploader_spak.php?r=spk_unapp"></center>';

		}


	}

		$sql="SELECT
		    `fu_ajk_costumer`.`name`
		    , `fu_ajk_polis`.`nmproduk`
		    , `fu_ajk_spak_form`.`nama`
		    , `fu_ajk_spak_form`.`jns_kelamin`
		    , `fu_ajk_spak_form`.`dob`
		    , `fu_ajk_spak_form`.`alamat`
		    , `fu_ajk_spak_form`.`pekerjaan`
		    , `fu_ajk_spak_form`.`plafond`
		    , `fu_ajk_spak_form`.`tenor`
		    , `fu_ajk_spak_form`.`tgl_periksa`
		    , `fu_ajk_spak_form`.`tgl_asuransi`
		    , `fu_ajk_spak_form`.`tgl_akhir_asuransi`
		    , `fu_ajk_spak_form`.`catatan`
			FROM
		    `fu_ajk_spak`
		    INNER JOIN `fu_ajk_spak_form`
		        ON (`fu_ajk_spak`.`id` = `fu_ajk_spak_form`.`idspk`) AND `fu_ajk_spak_form`.`del` IS NULL
		    INNER JOIN `fu_ajk_costumer`
		        ON (`fu_ajk_spak`.`id_cost` = `fu_ajk_costumer`.`id`) AND `fu_ajk_costumer`.`del` IS NULL
		    INNER JOIN `fu_ajk_polis`
		        ON (`fu_ajk_spak`.`id_polis` = `fu_ajk_polis`.`id`) AND `fu_ajk_polis`.`del` IS NULL
			WHERE `fu_ajk_spak`.`del` IS NULL and `fu_ajk_spak`.id=".$_REQUEST['ids'];
		$result=mysql_query($sql);
		$data_=mysql_fetch_array($result);

		echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="95%" align="left">Modul Data SPK</font></th></tr></table>';
		echo '<table border="0" width="100%" cellpadding="1" cellspacing="1" style="padding-left: 120px; padding-right: 120px;">
	  <form method="post" action="">
	  <tr><th colspan="2" align="left" style="background-color: #dcdcdc; border: none;">Data Perusahaan</th></tr>
	  <tr><td align="right" width="10%">Nama Perusahaan :</td><td>'.$data_['name'].'</td></tr>
	  <tr><td align="right" width="10%">Nama Produk :</td><td>'.$data_['nmproduk'].'</td></tr>
	  <tr><th colspan="2" align="left" style="background-color: #dcdcdc; border: none;">Data Nasabah</th></tr>
	  <tr><td align="right" width="10%">Nama Nasabah :</td><td>'.$data_['nama'].'</td></tr>
	  <tr><td align="right" width="10%">Jenis Kelamin :</td><td>'.$data_['jns_kelamin'].'</td></tr>
	  <tr><td align="right" width="10%">Tanggal Lahir :</td><td>'.$data_['dob'].'</td></tr>
	  <tr><td align="right" width="10%">Alamat :</td><td>'.$data_['alamat'].'</td></tr>
	  <tr><td align="right" width="10%">Pekerjaan :</td><td>'.$data_['pekerjaan'].'</td></tr>
	  <tr><td align="right" width="10%">Plafond :</td><td>'.duit($data_['plafond']).'</td></tr>
	  <tr><td align="right" width="10%">Tenor :</td><td>'.$data_['nama'].'</td></tr>
	  <tr><td align="right" width="10%">Tanggal Akad :</td><td>'.$data_['tgl_asuransi'].'</td></tr>
	  <tr><td align="right" width="10%">Tanggal Akhir :</td><td>'.$data_['tgl_akhir_asuransi'].'</td></tr>
	  <tr><th colspan="2" align="left" style="background-color: #fff; border: none;"></th></tr>
	  <tr><th colspan="2" align="left" style="background-color: #dcdcdc; border: none;">Ubah Nilai EM</th></tr>
	  <tr><td align="right" width="10%">Nilai EM :</td><td><input type="text" name="nilai_em"> %</td></tr>
	  <tr><td align="right" width="10%">Attach File :</td><td><input type="file" name="file_att"></td></tr>
	  <tr><th colspan="2" align="left" style="background-color: #fff; border: none;"></th></tr>
	  <tr><td align="right" width="10%"></td><td><button type="submit" name="save_em" class="submit">Simpan</button></td></tr>

	  </form></table>';
		;
		break;


		case "tolak_spak1":
			echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="95%" align="left">Modul keterangan Penolakan SPK Peserta</font></th>
					<th><a href="ajk_uploader_spak.php?r=spk_unapp"><img src="image/back.png" width="20"></a></th></tr></table>';
			$cek_batal_spk = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak WHERE id="' . $_REQUEST['ids'] . '"'));
			$cek_batal_spk_nm = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_form WHERE idspk="' . $cek_batal_spk['id'] . '"'));
			if ($_REQUEST['ope'] == "Simpan") {
				$_REQUEST['batalspk'] = $_POST['batalspk'];
				if (!$_REQUEST['batalspk']) $error .= '<font color="red"><br />Silahkan isi alasan keterangan data ditolak peserta!</font>';
				if ($error) {
				}else {
					$batal_spk = mysql_fetch_array($database->doQuery('UPDATE fu_ajk_spak SET status="Tolak", keterangan="' . $_REQUEST['batalspk'] . '",update_by="' . $_SESSION['nm_user'] . '",update_date="' . $futgl . '" WHERE id="' . $_REQUEST['ids'] . '"'));
					header("location:ajk_uploader_spak.php?r=spk_unapp");
				}
			}
			echo '<table border="0" width="50%" align="center">
			  <tr><td>
			  	<form name="f1" method="post" class="input-list style-1 smart-green">
				<h1>SPK ditolak nomor  ' . $cek_batal_spk['spak'] . ' a/n ' . $cek_batal_spk_nm['nama'] . '</h1>
				<label><span>Keterangan<font color="red">*</font> ' . $error . '</span><textarea name="batalspk" style="background :#fff;">' . $_REQUEST['batalspk'] . '</textarea></label>
				<label><span>&nbsp;</span><input type="submit" name="ope" value="Simpan" class="button" /></label>
				</form>
			  </td></tr>
			  </table>'; ;
			break;

case "set_spak_mp":
				echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="95%" align="left">Modul Data SPK</font></th></tr></table>';
				echo '<table border="0" width="100%" cellpadding="1" cellspacing="1">
	  <form method="post" action="">
	  <tr><td align="right" width="10%">Nomor SPK :</td><td><input type="text" name="nospk" value="' . $_REQUEST['nospk'] . '"></td></tr>
	  <tr><td align="right" width="10%">Nama Debitur :</td><td><input type="text" name="namaspk" value="' . $_REQUEST['namaspk'] . '"> <input type="submit" name="button" value="Cari" class="button"></td></tr>
	  </form></table>';
				echo '<form method="post" action="ajk_uploader_spak.php?r=approve_spak_dokter" onload ="onbeforeunload">
	 <table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#bde0e6">
	 <tr><th width="1%">No</th>
	 	 <th width="1%">Option <input type="checkbox" id="selectall"/></th>
	 	 <th>Perusahaan</th>
	 	 <th width="1%">Produk</th>
	 	 <th width="1%">SPK</th>
	 	 <th width="1%">No.Identitas</th>
	 	 <th>Nama</th>
	 	 <th width="1%">Tgl Lahir</th>
	 	 <th width="1%">Tgl Akad</th>
	 	 <th width="1%">Tenor</th>
	 	 <th width="1%">Tgl Akhir</th>
	 	 <th width="1%">Grace Period</th>
	 	 <th width="20%">Keterangan</th>
	 	 <th width="1%">Plafond</th>
	 	 <th width="1%">Rate</th>
	 	 <th width="1%">Premi (x)</th>
	 	 <th width="1%">Premi (Plafond*rate/mil)</th>
	 	 <th width="1%">Usia (x)</th>
	 	 <th width="1%">Ex.Premi<br />(%)</th>
	 	 <th width="5%">File SPK</th>
	 	 <th width="5%">Cabang</th>
	 	 <th width="1%">User Upload</th>
	 	 <th width="8%">Tgl Upload</th>
	 	 <th width="1%">User Approve</th>
	 	 <th width="8%">Tgl Approve</th>
	 	 <th width="1%">Status</th>
	 	 <th width="1%">Photo</th>
	 	 <th width="5%">Option</th>
	 </tr>';
				if ($_REQUEST['nospk']) {	$satu = 'AND spak LIKE "%' . $_REQUEST['nospk'] . '%"';	}
				if ($_REQUEST['namaspk']) {
					$ceknama = mysql_fetch_array($database->doQuery('SELECT fu_ajk_spak_form.id, fu_ajk_spak_form.idspk, fu_ajk_spak_form.nama, fu_ajk_spak.spak
    														 FROM fu_ajk_spak_form
    														 left Join fu_ajk_spak ON fu_ajk_spak_form.idspk = fu_ajk_spak.id
															 WHERE fu_ajk_spak_form.nama="'.$_REQUEST['namaspk'].'" AND fu_ajk_spak.status="Approve"'));
					$dua = 'AND id = "' . $ceknama['idspk'] . '"';
				}

				if ($_REQUEST['x']) {	$m = ($_REQUEST['x'] - 1) * 25;	} else {	$m = 0;	}
				if ($q['level'] == "99" AND $q['status'] == "STAFF") {
					$met = $database->doQuery('SELECT * FROM fu_ajk_spak WHERE SUBSTR(spak,1,2)="MP" and status!="Aktif" ' . $satu . ' ' . $dua . ' AND status!="Batal" AND status!="Tolak" AND del IS NULL ORDER BY id DESC LIMIT ' . $m . ' , 25');
					$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_spak WHERE SUBSTR(spak,1,2)="MP" AND status!="Aktif" AND status!="Batal" AND status!="Tolak" AND del IS NULL ' . $satu . ' ' . $dua . ''));
					$totalRows = $totalRows[0];
				}elseif ($q['level'] == "99" AND $q['status'] == "SUPERVISOR" AND $q['supervisor'] == "0") {
					$met = $database->doQuery('SELECT * FROM fu_ajk_spak WHERE SUBSTR(spak,1,2)="MP" and status!="Aktif" ' . $satu . ' ' . $dua . ' AND status!="Batal" AND status!="Tolak" AND del IS NULL ORDER BY id DESC LIMIT ' . $m . ' , 25');
					$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_spak WHERE SUBSTR(spak,1,2)="MP" AND status!="Aktif" AND status!="Batal" AND status!="Tolak" AND del IS NULL ' . $satu . ' ' . $dua . ''));
					$totalRows = $totalRows[0];
				}else {
					$met = $database->doQuery('SELECT * FROM fu_ajk_spak WHERE SUBSTR(spak,1,2)="MP" and status="Approve" ' . $satu . ' ' . $dua . ' AND status!="Batal" AND status!="Tolak" AND del IS NULL ORDER BY id DESC LIMIT ' . $m . ' , 25');
					$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_spak WHERE SUBSTR(spak,1,2)="MP" AND status="Approve" AND status!="Batal" AND status!="Tolak" AND del IS NULL ' . $satu . ' ' . $dua . ''));
					$totalRows = $totalRows[0];
				}
				$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
				while ($met_ = mysql_fetch_array($met)) {
					$met_company = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="' . $met_['id_cost'] . '"'));
					$metdata = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE type_data="SPK" AND spaj="' . $met_['spak'] . '" AND del IS NULL'));
					$met_formspk = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_form WHERE idspk="' . $met_['id'] . '" AND del IS NULL'));
					$metCabang = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id="' . $met_formspk['cabang'] . '" AND del IS NULL'));
					$met_produk = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="' . $met_['id_polis'] . '" AND del IS NULL'));
					// CEK STATUS DATA SPK
					if ($met_['status'] == "Batal" OR $met_['status']=="Tolak") {	$statusspknya = '<font color="red">' . $met_['status'] . '</font>';	}
					else {	$statusspknya = '<font color="blue">' . $met_['status'] . '</font>';	}
					// CEK STATUS DATA SPK
					if ($metdata['spaj'] == $met_['spak']) {	$_datamet = $metdata['nama'];	}
					else {	$_datamet = $met_formspk['nama'];	}

					if ($q['status'] == "STAFF" OR $q['status'] == "SUPERVISOR" OR $q['status'] == "" OR $q['status'] == "UNDERWRITING") {
						$cekformspak = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_form WHERE idspk="' . $met_['id'] . '" AND del IS NULL'));
						if ($cekformspak['idspk'] == $met_['id']) {
							$setting_fspak = '<a href="ajk_uploader_spak.php?r=edit_spak&ids=' . $met_['id'] . '&idform=' . $met_formspk['id'] . '"><img src="image/edit3.png" width="20"></a>';
							if ($q['status'] == "SUPERVISOR") {
								$setting_fspak = '<a href="ajk_uploader_spak.php?r=vsett_spak&ids=' . $met_['id'] . '"><img src="image/new.png" width="20"></a>';
								if ($met_['status'] == "Batal") {
									$approve_spk = '';
								} else {
									if ($met_['status'] == "Approve") {	$metikonapprove = '<img src="image/ya2.png" width="15">';	}
									else {	$metikonapprove = '<img src="image/ya.png" width="15">';	}
									$approve_spk = '<a href="ajk_uploader_spak.php?r=vdelsett_spak&ids=' . $met_['id'] . '" onClick="if(confirm(\'Apakah anda yakin membatalkan atau revisi data SPK ini ?\')){return true;}{return false;}"><img src="image/delete.gif" width="15"></a> &nbsp;
							<a href="ajk_uploader_spak.php?r=approve_spak&ids=' . $met_['id'] . '" onClick="if(confirm(\'Apakah anda yakin data SPK telah sesuai ?\')){return true;}{return false;}">' . $metikonapprove . '</a>';
								}
								$setting_fspak = '<a href="ajk_uploader_spak.php?r=edit_spak&ids=' . $met_['id'] . '&idform=' . $met_formspk['id'] . '"><img src="image/edit3.png" width="20"></a>';
							}elseif ($q['status'] == "" OR $q['status'] == "UNDERWRITING" AND $q['supervisor'] == "0") {
								$approve_spk = '<a href="ajk_uploader_spak.php?r=extpremi_spk&ids=' . $met_['id'] . '"><img src="image/editdis.png" width="15"></a> &nbsp;';
								$dataceklist = '<input type="checkbox" class="case" name="namaspk[]" value="' . $met_['id'] . '">';
								$setting_fspak = '<a title="Preview Data SPK" href="ajk_uploader_spak.php?r=vsett_spak&ids=' . $met_['id'] . '"><img src="image/new.png" width="20"></a> &nbsp;
							 			  <a title="Tolak Data SPK" href="ajk_uploader_spak.php?r=tolak_spak&ids=' . $met_['id'] . '" onClick="if(confirm(\'Anda yakin untuk membatalkan data SPK ini ?\')){return true;}{return false;}"><img src="image/deleted.png" width="20"></a> &nbsp;';
							}else {	}
						} else {
							$setting_fspak = '<a href="ajk_uploader_spak.php?r=sett_spak&ids=' . $met_['id'] . '"><img src="image/new.png" width="20"></a> &nbsp;
							<a href="ajk_uploader_spak.php?r=dell_spak&ids=' . $met_['id'] . '" onClick="if(confirm(\'Apakah anda yakin membatalkan data SPK ini ?\')){return true;}{return false;}"><img src="image/delete.gif" width="15"></a> &nbsp;
							';
						}
					} else {	}

					$x_file = str_replace(' ', '%20', $met_['fname']);

					/*
					 if ($met_['status']!="Aktif") {
					 $approve_spk__ = $approve_spk;
					 }else{
					 $approve_spk__ = '';
					 }
					 */

					if ($met_['photo_spk'] == "") {
						$v_photo = '<img src="../image/non-user.png" width="50">';
					} else {
						$v_photo = '<a href="' . $metpath_file . '' . $met_['photo_spk'] . '" rel="lightbox" ><img src="' . $metpath_file . '' . $met_['photo_spk'] . '" width="50"></a>';
					}

					if (($no % 2) == 1) $objlass = 'tbl-odd';	else $objlass = 'tbl-even';


					if (is_numeric($met_['input_by'])) {
						$met_User = mysql_fetch_array($database->doQuery('SELECT id, nama, namalengkap FROM user_mobile WHERE id="'.$met_['input_by'].'"'));
						$inputby_met = $met_User['namalengkap'];
					}else{
						$inputby_met = $met_['input_by'];
					}

					if (is_numeric($met_['update_by'])) {
						$met_UserSPV = mysql_fetch_array($database->doQuery('SELECT id, nama, namalengkap FROM user_mobile WHERE id="'.$met_['update_by'].'"'));
						$updateby_met = $met_UserSPV['namalengkap'];
					}else{
						$updateby_met = $met_['update_by'];
					}

					$cekNilaiPremi = $met_formspk['plafond'] * $met_formspk['ratebank'] / 1000;
					echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	  <td align="center">' . (++$no + ($pageNow - 1) * 25) . '</td>
			<td align="center">' . $approve_spk . ' ' . $dataceklist . '</td>
			<td>' . strtoupper($met_company['name']) . '</td>
			<td>' . strtoupper($met_produk['nmproduk']) . '</td>
			<td align="center">' . $met_['spak'] . '</td>
			<td align="center">' . $met_formspk['noidentitas'] . '</td>
			<td>' . strtoupper($_datamet) . '</td>
			<td align="center">' . _convertDate($met_formspk['dob']) . '</td>
			<td align="center">' . _convertDate($met_formspk['tgl_asuransi']) . '</td>
			<td align="center">' . $met_formspk['tenor'] . '</td>
			<td align="center">' . _convertDate($met_formspk['tgl_akhir_asuransi']) . '</td>
			<td align="center">' . $met_formspk['mpp'] . '</td>
			<td><a title="'.$met_['ket_ext'].'">' . substr($met_['ket_ext'], 0, 30) . '</a></td>
			<td align="right">' . duit($met_formspk['plafond']) . '</td>
			<td align="center"><b>' . $met_formspk['ratebank'] . '</b></td>
			<td align="right">' . duit($met_formspk['x_premi']) . '</td>
			<td align="right">' . duit($cekNilaiPremi) . '</td>
			<td align="center">' . $met_formspk['x_usia'] . '</td>
			<td align="center">' . $met_['ext_premi'] . '</td>
		    <td align="center"><a href=' . $metpath_file . '' . $x_file . ' target="_blank">'.$met_['fname'].'</a></td>
		    <td align="center">' . $metCabang['name'] . '</td>
		    <td align="center">' . $inputby_met . '</td>
		    <td align="center">' . $met_['input_date'] . '</td>
			<td align="center">' . $updateby_met . '</td>
		    <td align="center">' . $met_['update_date'] . '</td>
		    <td align="center">' . $statusspknya . '</td>
		    <td align="center">' . $v_photo . '</td>
		    <td align="center">' . $setting_fspak . '</td>
		  </tr>';
				}
				if ($q['status'] == "UNDERWRITING" AND $q['supervisor'] == "0" AND $q['level'] == "99" OR $q['level'] == "1" AND $q['supervisor'] == "0") {
					$el = $database->doQuery('SELECT * FROM fu_ajk_spak WHERE status="Approve"');
					$met = mysql_num_rows($el);
					if ($met > 0) {
						echo '<tr><td colspan="28" align="center"><a href="ajk_uploader_spak.php?r=approve_spak_dokter" onClick="if(confirm(\'Apakah anda sudah yakin dengan semua data SPK ini ?\')){return true;}{return false;}"><input type="submit" name="ApproveClaim" Value="Approve"></a></td></tr>';
					} else {
						echo '';
					}
				} else {
					// echo '<tr><td colspan="27" align="center">'.$q['status'].'</td></tr>';
				}
				echo '<tr><td colspan="22">';
				echo createPageNavigations($file = 'ajk_uploader_spak.php?r=set_spak&nospk=' . $_REQUEST['nospk'] . '', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
				echo '<b>Total Data SPK: <u>' . $totalRows . '</u></b></td></tr>';
				echo '</table>';;
				break;

    case "edit_percepatan":
        echo '<table border="0" cellpadding="0" cellspacing="0" width="100%">
              <tr><th width="95%" align="left">Modul Data Percepatan</font></th><th><a href="ajk_uploader_spak.php?r=pcp"><img src="image/back.png" width="20"></a></th></tr>
              </table>';
        $spkdokter = mysql_fetch_array($database->doQuery('SELECT *, DATE_FORMAT(input_date,"%Y-%m-%d") AS tglinput FROM fu_ajk_spak WHERE id=' . $_REQUEST['ids'] . ''));
        $spkdokter_form = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_form WHERE idspk=' . $_REQUEST['ids'] . ' AND id=' . $_REQUEST['idform'] . ' '));

        if (is_numeric($spkdokter['input_by'])) {
            $met_User = mysql_fetch_array($database->doQuery('SELECT id, nama, namalengkap FROM user_mobile WHERE id="'.$spkdokter['input_by'].'"'));
            $inputby_met = $met_User['namalengkap'];
        }else{
            $inputby_met = $spkdokter['input_by'];
        }

        if ($_REQUEST['ope'] == "Simpan") {
          if ($_REQUEST['spk_nama'] == "")    {   $error_1 = '<font color="red"><blink>Silahkan input nama debitur.</font>';  }          
          if ($_REQUEST['spk_alamat'] == "")  {   $error_2 = '<font color="red"><blink>Silahkan isi alamat debitur.<br /></font>';    }
          if ($_REQUEST['noidentitas'] == "") {   $error_3 = '<font color="red"><blink>Silahkan No Identitas <br /></font>';  }
          if ($_REQUEST['spk_pekerjaan'] == "") {   $error_4 = '<font color="red"><blink>Silahkan isi Pekerjaan <br/></font>';    }
          if ($_REQUEST['spk_plafond'] == "") {   $error_12 = '<font color="red"><blink>Silahkan isi jumlah pinjaman.<br /></font>';  }
          if($spkdokter_form['ratebank'] != ""){
            $premi = (($_REQUEST['spk_plafond']/1000) * $spkdokter_form['ratebank']);
          }else{
            $premi = $spkdokter_form['x_premi'];
          }

          $query_update = ' UPDATE fu_ajk_spak_form 
                            SET nama="' . $_REQUEST['spk_nama'] . '",
                               noidentitas="' . $_REQUEST['noidentitas'] . '",
                               jns_kelamin="' . $_REQUEST['jns_kelamin'] . '",
                               alamat="' . $_REQUEST['spk_alamat'] . '",
                               pekerjaan="' . $_REQUEST['spk_pekerjaan'] . '",
                               plafond= "'.$_REQUEST['spk_plafond'].'",
                               x_premi = "'.$premi.'",
                               update_by="' . $_SESSION['nm_user'] . '",
                               update_date="' . $futgl . '"
                            WHERE id="' . $spkdokter_form['id'] . '"';
          
          $metrefundcn = $database->doQuery($query_update);

          //Log Query
          $berkas = fopen("logquery.txt", "a") or die ("File history tidak ada.");                 
          fwrite($berkas, "Date Update : ".date("Y-m-d G:i:s")." By : ".$_SESSION['nm_user']." Query : ".$query_update. "\r\n");
          fclose($berkas);

          echo '<center><h2>Data Percepatan telah direvisi oleh ' . $q['nm_lengkap'] . ' pada tanggal ' . $futgl . '.</h2></center><meta http-equiv="refresh" content="3;URL=ajk_uploader_spak.php?r=pcp">';
        }elseif($_REQUEST['ope'] == "SimpanAll") {
           if ($_REQUEST['spk_nama'] == "")    {   $error_1 = '<font color="red"><blink>Silahkan input nama debitur.</font>';  }
            if ($_REQUEST['spk_dob'] == "")     {   $error_2 = '<font color="red"><blink>Silahkan isi tanggal lahir debitur.<br /></font>'; }
            if ($_REQUEST['spk_alamat'] == "")  {   $error_3 = '<font color="red"><blink>Silahkan isi alamat debitur.<br /></font>';    }
            if ($_REQUEST['spk_plafond'] == "") {   $error_12 = '<font color="red"><blink>Silahkan isi jumlah pinjaman.<br /></font>';  }
            if ($_REQUEST['spk_tglakad'] == "") {   $error_13 = '<font color="red"><blink>Silahkan isi tanggal awal asuransi.<br /></font>';    }
            if ($_REQUEST['spk_nmcabbank']=="") {   $error_14 = '<font color="red"><blink>Silahkan isi cabang debitur.<br /></font>';   }
            

            $cekplafond = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_medical WHERE id_cost="' . $spkdokter['id_cost'] . '" AND id_polis="' . $spkdokter['id_polis'] . '"'));
            if ($_REQUEST['plafond'] >= $cekplafond['si_to']) {
                $error_15 = '<font color="red"><blink>Nilai Plafond melewati batas maksimum table underwriting.<br /></font>';
            }

            $admpolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id_cost="' . $spkdokter['id_cost'] . '" AND id="' . $spkdokter['id_polis'] . '"'));
            
            if ($_REQUEST['spk_tglakad']=="0000-00-00") {
                $tglakadnya = $futoday;
            }else{
                $tglakadnya = $_REQUEST['spk_tglakad'];
            }
            
            $met_Date = dateDiff($tglakadnya, $_REQUEST['spk_dob']);
            $met_Date_ = explode(",", $met_Date);
            if ($met_Date_[1] >= 6) {
                $umur = $met_Date_[0] + 1;
            } else {
                $umur = $met_Date_[0];
            }

            // FORMULA USIA
            $cekplafond = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_medical WHERE id_cost="' . $spkdokter['id_cost'] . '" AND id_polis="' . $spkdokter['id_polis'] . '"'));
            if ($umur >= $cekplafond['age_from'] AND str_replace(".", "", $_REQUEST['spk_plafond']) > $cekplafond['si_to']) {
                $error_15 = '<font color="red"><blink>Nilai Plafond melewati batas maksimum table underwriting.<br /></font>';
            }                

            $mettenornya = $_REQUEST['spk_jwaktu'];

            if ($_REQUEST['spk_jwaktu'] == "") {    
                $error_11 = '<font color="red"><blink>Jangka Waktu tidak boleh kosong.<br /></font>';   
            }else{
                if ($admpolis['mpptype']=="Y") {
                  if($spkdokter['danatalangan']==1){
                    if ($_REQUEST['spk_jwaktu'] <= 12) {
                      $datamppcektenor__ = 1;
                    }elseif ($_REQUEST['spk_jwaktu'] >= 13 && $_REQUEST['spk_jwaktu'] <= 24) {                        
                      $datamppcektenor__ = 2;
                    }else{
                       $datamppcektenor__ = 3;
                    }
                  }else{
                    $datamppcektenor__ =  $_REQUEST['spk_jwaktu'];
                  }
                  $cekrate_tenor = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="' . $spkdokter['id_cost'] . '" AND id_polis="' . $spkdokter['id_polis'] . '" AND tenor="' .$datamppcektenor__. '" AND '.$_REQUEST['mppbln'].' BETWEEN mpp_s AND mpp_e AND status="baru" AND "'.$spkdokter['tglinput'].'" BETWEEN eff_from AND eff_to AND del IS NULL')); // RATE PREMI
                  if (!$cekrate_tenor['rate']) {
                      $error_11 = '<font color="red"><blink>Jumlah jangka waktu tidak sesuai rate produk MPP.<br /></font>';
                  }
                }else{
                  $tenor = $_REQUEST['spk_jwaktu']*12; //TENOR PERCEPATAN DI X 12                
                  $cekrate_tenor = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="' . $spkdokter['id_cost'] . '" AND id_polis="' . $spkdokter['id_polis'] . '" AND tenor="' .$tenor. '" AND "'.$spkdokter['tglinput'].'" BETWEEN eff_from AND eff_to AND del IS NULL')); // RATE PREMI
                  if ($cekrate_tenor['tenor'] != $tenor) {
                      $error_11 = '<font color="red"><blink>Jumlah jangka waktu tidak sesuai rate produk. '.$query.'<br /></font>';
                  }
                }
                //CEK TAMBAHAN DATA MPP TALANGAN
                if ($admpolis['mpptype']=="Y") {
                  $datampp = $database->doQuery('SELECT * FROM fu_ajk_spak_form WHERE nopermohonan = "'.$spkdokter_form['nopermohonan'].'"');
                  while ($datampp_ = mysql_fetch_array($datampp)) {                  
                      if ($spkdokter_form['idspk'] == $datampp_['idspk']) {
                          $datampptenor =  'month';
                      }else{
                          $datampptenor =  'year';
                      }
                  }
                }else{
                  $datampptenor =  'year';
                }
                //CEK TAMBAHAN DATA MPP TALANGAN
                $met_tgl_akhir = date('Y-m-d', strtotime('+' . $_REQUEST['spk_jwaktu'] . ' '.$datampptenor.'', strtotime($_REQUEST['spk_tglakad']))); //tanggal akhir asuransi
                $met_Date_Akhir = datediff($met_tgl_akhir, $_REQUEST['spk_dob']);
                $metUsiaAkhirKredit = explode(",", $met_Date_Akhir);
                
                if ($datampptenor =="month") {

                }else{
                    if (($umur + $_REQUEST['spk_jwaktu']) > $admpolis['age_max'] + 1) {
                        $error_11 = '<font color="red"><blink>Usia '.$umur.'thn melebihi batas masksimum usia, data ditolak.!!!</font>';
                    }
                }
            }
            //CEK PLAFOND UMUR PADA TABLE MEDICAL
            $plafondnya__ = str_replace(".", "", $_REQUEST['spk_plafond']);
            $cekplafondakhir = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_medical WHERE id_cost="' . $spkdokter['id_cost'] . '" AND
                                                                                                        id_polis="' . $spkdokter['id_polis'] . '" AND
                                                                                                        '.$umur.' BETWEEN age_from AND age_to AND
                                                                                                        '.$plafondnya__.' BETWEEN si_from AND si_to
                                                                                                        '));
            if (!$cekplafondakhir) {    $error_16 = '<font color="red"><blink>Nilai Plafond melewati batas maksimum table underwriting.<br /></font>';  }
            //CEK PLAFOND UMUR PADA TABLE MEDICAL

            if ($admpolis['mpptype']=="Y" AND $_REQUEST['mppbln']=="") {    $error_17 = '<font color="red"><blink>Silahkan isi jumlah masa pra pensiun (mpp).<br /></font>';    }
            if ($error_1 OR $error_2 OR $error_3 OR $error_4 OR $error_5 OR $error_6 OR $error_7 OR $error_8 OR $error_9 OR $error_10 OR $error_11 OR $error_12 OR $error_13 OR $error_14 OR $error_15 OR $error_16 OR $error_17) {
            }else {
                $plafondnya = str_replace(".", "", $_REQUEST['spk_plafond']);            

                if ($admpolis['mpptype']=="Y") {               
                    if($spkdokter['danatalangan']==1){
                      if ($_REQUEST['spk_jwaktu'] <= 12) {
                        $datamppcektenor__ = 1;
                      }elseif ($_REQUEST['spk_jwaktu'] >= 13 && $_REQUEST['spk_jwaktu'] <= 24) {                        
                        $datamppcektenor__ = 2;
                      }else{
                         $datamppcektenor__ = 3;
                      }
                    }else{
                      $datamppcektenor__ =  $_REQUEST['spk_jwaktu'];
                    }                   
                    $cekrate = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="' . $spkdokter['id_cost'] . '" AND id_polis="' . $spkdokter['id_polis'] . '" AND '.$_REQUEST['mppbln'].' BETWEEN mpp_s AND mpp_e AND tenor="' . $datamppcektenor__ . '" AND status="baru" AND "'.$spkdokter['tglinput'].'" BETWEEN eff_from AND eff_to AND del IS NULL')); // RATE PREMI
                }else{                           
                    $tenor = $_REQUEST['spk_jwaktu'] * 12; // PERCEPATAN MPP di x 12                  
                    $cekrate = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="' . $spkdokter['id_cost'] . '" AND id_polis="' . $spkdokter['id_polis'] . '" AND tenor="' . $tenor . '" AND "'.$spkdokter['tglinput'].'" BETWEEN eff_from AND eff_to AND del IS NULL')); // RATE PREMI
                }
                // MENENTUKAN PERHITUNGAN PREMI BERDASARKAN SINGLE RATE PADA POLIS
                $premi = ROUND($plafondnya * $cekrate['rate'] / 1000);
                if ($premi < $admpolis['min_premium']) {                        
                    $premi_x = $premi;
                } else {
                    $premi_x = $premi;
                }

                if ($admpolis['mpptype']=="Y") {
                $datampp = $database->doQuery('SELECT * FROM fu_ajk_spak_form WHERE nopermohonan = "'.$spkdokter_form['nopermohonan'].'"');
                while ($datampp_ = mysql_fetch_array($datampp)) {                
                    if ($spkdokter_form['idspk'] == $datampp_['idspk']) {
                        $datampptenor =  'month';
                    }else{
                        $datampptenor =  'year';
                    }
                }
                }else{
                    $datampptenor =  'year';
                }

                $met_tgl_akhir = date('Y-m-d', strtotime('+' . $mettenornya . ' '.$datampptenor.'', strtotime($_REQUEST['spk_tglakad']))); //operasi penjumlahan tanggal
                $query_update = 'UPDATE fu_ajk_spak_form 
                                 SET idcost="' . $spkdokter['id_cost'] . '",dokter="' . $spkdokter['input_by'] . '",idspk="' . $spkdokter['id'] . '",nama="' . $_REQUEST['spk_nama'] . '",noidentitas="' . $_REQUEST['noidentitas'] . '",jns_kelamin="' . $_REQUEST['jns_kelamin'] . '",dob="' . $_REQUEST['spk_dob'] . '",alamat="' . $_REQUEST['spk_alamat'] . '",pekerjaan="' . $_REQUEST['spk_pekerjaan'] . '",plafond="' . $plafondnya . '",tgl_asuransi="' . $_REQUEST['spk_tglakad'] . '",tenor="' . $_REQUEST['spk_jwaktu'] . '",mpp="' . $_REQUEST['mppbln'] . '",tgl_akhir_asuransi="' . $met_tgl_akhir . '",ratebank="' . $cekrate['rate'] . '",x_premi="' . $premi_x . '",x_usia="' . $umur . '",cabang="' . $_REQUEST['spk_nmcabbank'] . '",update_by="' . $_SESSION['nm_user'] . '",update_date="' . $futgl . '"
                                 WHERE id="' . $spkdokter_form['id'] . '"';
                
                $metrefundcn = $database->doQuery($query_update);

                //Log Query
                $berkas = fopen("logquery.txt", "a") or die ("File history tidak ada.");            
                fwrite($berkas, "Date Update : ".date("Y-m-d G:i:s")." By : ".$_SESSION['nm_user']." Query : ".$query_update. "\r\n");
                fclose($berkas);

                echo '<center><h2>Data Percepatan telah direvisi oleh ' . $q['nm_lengkap'] . ' pada tanggal ' . $futgl . '.</h2></center><meta http-equiv="refresh" content="3;URL=ajk_uploader_spak.php?r=pcp">';
            }
        }else{

         echo '<form name="f1" method="post" action="">
                <table border="0" cellpadding="1" cellspacing="1" width="85%" align="center">
                  <tr><td colspan="2" class="judulhead">Form PERCEPATAN<br />Nomor : ' . $spkdokter['spak'] . '</td></tr>
                  <tr><td>Upload Percepatan</td><td>: ' . strtoupper($inputby_met) . '</td></tr>
                  <tr><td>Nama<font color="red">*</font> ' . $error_1 . '</td>
                      <td>: <input name="spk_nama" type="text" size="50" value="' . $spkdokter_form['nama'] . '" placeholder="Nama Peserta">
                  </tr>
                  <tr><td>No Identitas</td>
                      <td>: <input name="noidentitas" type="text" size="50" value="' . $spkdokter_form['noidentitas'] . '" placeholder="Nomor Identitas">
                  </tr>
                  <tr><td>Jenis Kelamin</td>
                      <td>: <input type="radio" name="jns_kelamin" value="M"' . pilih($spkdokter_form["jns_kelamin"], "M") . '>Laki-Laki
                            <input type="radio" name="jns_kelamin" value="F"' . pilih($spkdokter_form["jns_kelamin"], "F") . '>Perempuan</td>
                  </tr>
                  <!--
                  <tr><td>Tanggal Lahir <font color="red">*</font> ' . $error_2 . '</td>
                      <td>: <input type="text" name="spk_dob" id="rdob" class="tanggal" value="' . $spkdokter_form['dob'] . '" size="10"/></td>
                  </tr>-->
                  <tr><td>Alamat <font color="red">*</font> ' . $error_3 . '</td>
                      <td>: <textarea name="spk_alamat" type="text"rows="1" cols="45" placeholder="Alamat">' . $spkdokter_form['alamat'] . '</textarea></td>
                  </tr>
                  <tr><td>Pekerjaan</td>
                      <td>: <input name="spk_pekerjaan" type="text" size="50" placeholder="Pekerjaan" value="' . $spkdokter_form['pekerjaan'] . '"></td>
                  </tr>
                  <tr><td width="20%">Jumlah Pinjaman/Kredit <font color="red">*</font> ' . $error_12 . ' ' . $error_15 . ' ' . $error_16 . '</td>
                      <td>: <input type="text" name="spk_plafond" value="' . $spkdokter_form['plafond'] . '" size="30" id="inputku"  placeholder="Plafond" ></td>                  
                  <!--
                  <tr><td colspan="2" class="judulhead1">Asuransi Kredit</td></tr>
                  <tr><td colspan="2">
                  <table border="0" cellpadding="1" cellspacing="2" width="100%">
                    <tr><td width="20%">Jumlah Pinjaman/Kredit <font color="red">*</font> ' . $error_12 . ' ' . $error_15 . ' ' . $error_16 . '</td>
                        <td>: <input type="text" name="spk_plafond" value="' . $spkdokter_form['plafond'] . '" size="30" id="inputku" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);"/ placeholder="Plafond" ></td>
                    </tr>
                    <tr><td>Tanggal Akad/Kredit <font color="red">*</font> ' . $error_13 . '</td>
                        <td>: <input type="text" name="spk_tglakad" id="spk_tglakad" class="tanggal" value="' . $spkdokter_form['tgl_asuransi'] . '" maxlength="10" size="15"/ placeholder="Tangal Akad" ></td>
                    </tr>';

                    //JIKA DANA TALANGAN BULAN ELSE TAHUN
                    if($spkdokter['danatalangan']==1){
                      $datampptenor =  '<tr><td>Jangka Waktu <font color="blue">(Jumlah Bulan)</font> <font color="red">*</font> ' . $error_11 . '</td>
                                                  <td>: <input type="text" name="spk_jwaktu" value="' . $spkdokter_form['tenor'] . '" size="5" maxlength="2" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"/ placeholder="Tenor" ></td>
                                            </tr>';                                        
                    }else{
                      $datampptenor =  '<tr><td>Jangka Waktu <font color="blue">(Jumlah Tahun)</font> <font color="red">*</font> ' . $error_11 . '</td>
                                            <td>: <input type="text" name="spk_jwaktu" value="' . $spkdokter_form['tenor'] . '" size="5" maxlength="2" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"/ placeholder="Tenor" ></td>
                                      </tr>';                  
                    }
                   
                    echo $datampptenor;

                    if($spkdokter['id_polis']==11 or $spkdokter['id_polis']==12){
                      $polismpp = '<tr>
                                      <td>Masa Pra Pensiun (MPP) <font color="blue">(Jumlah Bulan)' . $error_17 . '</td>
                                      <td valign="top">: <input type="text" name="mppbln" value="' . $spkdokter_form['mpp'] . '" size="5" maxlength="2" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"/ placeholder="MPP" ></td>
                                   </tr>';
                    }
                    echo $polismpp;
          echo '<tr><td>Nama Cabang Bank / Koperasi <font color="red">*</font> ' . $error_14 . '</td>';
            if (is_numeric($spkdokter_form['cabang'])) {
                echo '<td>: <select size="1" name="spk_nmcabbank">
                    <option value="">---Pilih Nama Cabang---</option>';
                $metCabangnya = $database->doQuery('SELECT * FROM fu_ajk_cabang WHERE del IS NULL GROUP BY name ORDER BY name ASC');
                while ($metCabangnya_ = mysql_fetch_array($metCabangnya)) {
                    echo '<option value="'.$metCabangnya_['id'].'"'._selected($spkdokter_form['cabang'], $metCabangnya_['id']).'>'.$metCabangnya_['name'].'</option>';
                }
                echo '</select></td>';
            }else{
                echo '<td>: <input type="text" name="spk_nmcabbank" value="' . $spkdokter_form['cabang'] . '" size="50"/ placeholder="Nama Cabang Bank / Koperasi"></td>';
            }

                echo '</tr>
                  </table>
              </td></tr>--><tr>';
              if($spkdokter['status']!="Realisasi"){              
                echo '<td colspan="2" align="center"><input type="submit" name="ope" value="Simpan" class="button" /></td>';
              }
              echo '</table></form>'; 
          };
    break; 

    default:

/*
       $cat = $_GET['cat']; // Use this line or below line if register_global is off
        if (strlen($cat) > 0 and !is_numeric($cat)) { // to check if $cat is numeric data or not.
            echo "Data Error";
            exit;
        }
        echo '<table border="0" cellpadding="0" cellspacing="0" width="100%">
      <tr><th width="95%" align="left">Modul Upload Data Peserta</font></th><th><a href="ajk_uploader_spak.php?r=set_spak"><img src="image/new.png" width="20"></a></th></tr>
      </table>';
        $fu = mysql_fetch_array($database->doQuery('SELECT fu_ajk_costumer.name, fu_ajk_costumer.id FROM fu_ajk_costumer WHERE name="' . $qsescost['name'] . '"'));
        echo '<form name="f1" method="post" enctype="multipart/form-data" action="ajk_uploader_spak.php?r=fuparsing">
	<table border="0" width="60%" align="center">
<tr><td width="15%" align="right">Nama Perusahaan</td>
	  <td width="30%">: <select id="cat" name="cat" onchange="reload(this.form)">
	  	<option value="">---Perusahaan---</option>';
        $quer2 = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name ASC');
        while ($noticia2 = mysql_fetch_array($quer2)) {
            if ($noticia2['id'] == $cat) {
                echo '<option selected value="' . $noticia2['id'] . '">' . $noticia2['name'] . '</option><BR>';
            }else {
                echo '<option value="' . $noticia2['id'] . '">' . $noticia2['name'] . '</option>';
            }
        }
        echo '</select></td></tr>
	<tr><td width="10%" align="right">Nama Produk</td>
		<td width="20%">: ';
        if (isset($cat) and strlen($cat) > 0) {
            $quer = $database->doQuery('SELECT * FROM fu_ajk_polis where id_cost="' . $cat . '" ORDER BY id ASC');
        } else {
            $quer = $database->doQuery("SELECT * FROM fu_ajk_polis ORDER BY id ASC");
        }
        echo '<select name="subcat"><option value="">---Produk---</option>';
        while ($noticia = mysql_fetch_array($quer)) {
            if ($noticia['nmproduk'] == "") {
                $metproduknya = $noticia['nopol'];
            } else {
                $metproduknya = $noticia['nmproduk'];
            }
            echo '<option value=' . $noticia['id'] . '>' . $metproduknya . '</option>';
        }
        echo '</select></td></tr>
	  <tr><td align="right">Silakan Pilih File Excel </td><td>: ' . $bataskolom . ' <input name="userfile" type="file" size="50" onchange="checkfile(this);" ></td></tr>
	  <tr><td align="right">Batas Akhir Baris </td><td>: <input type="text" name="bataskolom" value="' . $bataskolom . '" size="1"></td></tr>
	  <tr><td align="center"colspan="2"><input name="upload" type="submit" value="Import"></td></tr>
	  </table></form>'; ;
*/
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
	self.location='ajk_uploader_spak.php?cat=' + val;
}
</script>

<SCRIPT language=JavaScript>
function reload2(form)
{
	var val=form.cat.options[form.cat.options.selectedIndex].value;
	self.location='ajk_uploader_spak.php?r=viewall&cat=' + val;
}
</script>

<SCRIPT language=JavaScript>
function reload3(form)
{
	var val=form.cat.options[form.cat.options.selectedIndex].value;
	self.location='ajk_uploader_spak.php?r=viewallclaim&cat=' + val;
}
</script>

<SCRIPT language=JavaScript>
function reload4(form)
{
	var val=form.cat.options[form.cat.options.selectedIndex].value;
	self.location='ajk_uploader_spak.php?r=fuparsingclaim&cat=' + val;
}
</script>

<SCRIPT language=JavaScript>
function reloadmultiplay(form)
{
	var val=form.cat.options[form.cat.options.selectedIndex].value;
	self.location='ajk_uploader_spak.php?r=datamultiplay&cat=' + val;
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
