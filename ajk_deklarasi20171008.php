<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Hansen
// E-mail :hansen@adonai.co.id
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("../includes/functions.php");
connect();
if (isset($_SESSION['nm_user'])) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));	}
$today = date("Y-m-d G:i:s");
switch ($_REQUEST['hn']) {
	case "settRefund":
		if ($_REQUEST['r']=="tp") {	
			$tiperefund = "Topup";
			$_typeRefund = "Topup";	
		}else {	
			$tiperefund = "";	
		}	
		echo '<table border="0" cellpadding="5" cellspacing="1" width="100%">
					  <tr><th width="100%" align="left">Modul Refund '.$tiperefund.'</font></th>
					  	  <th width="5%"><a href="ajk_deklarasi.php"><img src="image/Backward-64.png" width="20"></a></th>
					  </tr>
				  </table>';
		if ($_REQUEST['el'] == "upload_refund") {
			if (!$_REQUEST['tgl_refund']) {	
				$error1 .= '<font color="red">Silahkan input tanggal Refund !.</font>';	
			}
	
			if ($error1 OR $error2 OR $error3 OR $error4) {	
			}else{
				move_uploaded_file($_FILES['userfile']['tmp_name'], $metpath . 'REFUND_' . $_REQUEST['id_peserta'] . '_' . $_FILES["userfile"]["name"]);
        $mamet = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id="' . $_REQUEST['id'] . '"'));
        $mamet_as = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta_as WHERE id_bank="'.$mamet['id_cost'].'" AND id_polis="'.$mamet['id_polis'].'" AND id_dn="'.$mamet['id_dn'].'" AND id_peserta="'.$mamet['id_peserta'].'"'));
        $metsRefund = datediff($mamet['kredit_tgl'], $_REQUEST['tgl_refund']);
				$metsRefund_ = explode(",", $metsRefund);

				if ($metsRefund_[0] <= 0 AND $metsRefund_[1] <= 0 AND $metsRefund_[2] <= 30) {
    	    $premirefund = $mamet['totalpremi'];
		      $premirefund_as = $mamet_as['nettpremi'];
				}else {
					//JIKA TGL AKAD KE TGL REFUND > 30 HARI NILAI FULL
					$mets = datediff(date('Y-m-d'), $_REQUEST['tgl_refund']);
					$pecahtglrefund = explode(",", $mets);
		
						//JIKA TGL SISTEM KE TGL REFUND <= 30 HARI GUNAKAN TANGGAL LUNAS
					if ($pecahtglrefund[0] <= 0 AND $pecahtglrefund[1] <= 0 AND $pecahtglrefund[2] <= 30) {
						$metsTanggal = datediff($mamet['kredit_tgl'], $_REQUEST['tgl_refund']);
						$pecahtglrefund_ = explode(",", $metsTanggal);
					}else{
						//JIKA TGL SISTEM KE TGL REFUND > 30 HARI GUNAKAN TANGGAL SISTEM
						$metsTanggal = datediff($mamet['kredit_tgl'], date('Y-m-d'));
						$pecahtglrefund_ = explode(",", $metsTanggal);
					}

					$movementrefund = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_rasio_claim WHERE id_cost="' . $mamet['id_cost'] . '" AND id_polis="' . $mamet['id_polis'] . '"'));
          
          if ($mamet['type_data'] == "SPK") {	
          	$metTenornya = $mamet['kredit_tenor'] * 12 ;	
        	}else{	
        		$metTenornya = $mamet['kredit_tenor'];	
        	}

					$jumbulan = $metTenornya - ($pecahtglrefund_[0] * 12 + $pecahtglrefund_[1]);
        	$biayapenutupan = $mamet['totalpremi'] * $movementrefund['refund'];
        	$premirefund = $jumbulan / $metTenornya * ($mamet['totalpremi'] - $biayapenutupan);
					$biayapenutupan_as = $mamet_as['nettpremi'] * $movementrefund['refund'];
        	$premirefund_as = $jumbulan / $metTenornya * ($mamet_as['nettpremi'] - $biayapenutupan_as);            
				}

				// CEK PERHITUNGAN REFUND PREMI	
				$mets = datediff(date('Y-m-d'), $_REQUEST['tgl_refund']);
				$pecahtglrefund = explode(",", $mets);
				//JIKA TGL SISTEM KE TGL REFUND <= 30 HARI GUNAKAN TANGGAL LUNAS
				if ($pecahtglrefund[0] <= 0 AND $pecahtglrefund[1] <= 0 AND $pecahtglrefund[2] <= 30) {
					if ($metsRefund_[0] <= 0 AND $metsRefund_[1] <= 0 AND $metsRefund_[2] <= 30) {							
						$premirefund = $mamet['totalpremi'];
						$premirefund_as = $mamet_as['nettpremi'];
					} else {
						//JIKA TGL AKAD KE TGL REFUND > 30 HARI NILAI FULL
						$mets = datediff(date('Y-m-d'), $_REQUEST['tgl_refund']);
						$pecahtglrefund = explode(",", $mets);							
						//JIKA TGL SISTEM KE TGL REFUND <= 30 HARI GUNAKAN TANGGAL LUNAS
						if ($pecahtglrefund[0] <= 0 AND $pecahtglrefund[1] <= 0 AND $pecahtglrefund[2] <= 30) {
							$metsTanggal = datediff($mamet['kredit_tgl'], $_REQUEST['tgl_refund']);
							$pecahtglrefund_ = explode(",", $metsTanggal);
						}else{
							//JIKA TGL SISTEM KE TGL REFUND > 30 HARI GUNAKAN TANGGAL SISTEM
							$metsTanggal = datediff($mamet['kredit_tgl'], date('Y-m-d'));
							$pecahtglrefund_ = explode(",", $metsTanggal);
						}
						$movementrefund = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_rasio_claim WHERE id_cost="' . $mamet['id_cost'] . '" AND id_polis="' . $mamet['id_polis'] . '"'));
						if ($mamet['type_data'] == "SPK") {	$metTenornya = $mamet['kredit_tenor'] * 12 ;	
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
				}else{
					if ($pecahtglrefund[0] <= 0 AND $pecahtglrefund[1] <= 0 AND $pecahtglrefund[2] <= 30) {
						$metsTanggal = datediff($mamet['kredit_tgl'], $_REQUEST['tgl_refund']);
						$pecahtglrefund_ = explode(",", $metsTanggal);
					}else{
						//JIKA TGL SISTEM KE TGL REFUND > 30 HARI GUNAKAN TANGGAL SISTEM
						$metsTanggal = datediff($mamet['kredit_tgl'], date('Y-m-d'));
						$pecahtglrefund_ = explode(",", $metsTanggal);
					}
					$movementrefund = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_rasio_claim WHERE id_cost="' . $mamet['id_cost'] . '" AND id_polis="' . $mamet['id_polis'] . '"'));
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
				}

				$metrefundcn = $database->doQuery('INSERT INTO fu_ajk_cn_tempf 
																					 SET id_cost="' . $mamet['id_cost'] . '",
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

        $metrefunddn = $database->doQuery('UPDATE fu_ajk_peserta 
        																	 SET status_peserta="Refund", status_aktif="Pending" 
        																	 WHERE id="' . $_REQUEST['id'] . '"');
       
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

        $mail->SetFrom ($q['email'], $q['nm_lengkap']); //From address of the mail
        $mail->Subject = "AJKOnline - UPLOAD DATA REFUND"; //Subject od your mail

        $met_mail = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cn_tempf ORDER BY id DESC'));
        $met_mail_dn = mysql_fetch_array($database->doQuery('SELECT id, dn_kode FROM fu_ajk_dn WHERE id="' . $met_mail['id_dn'] . '"'));
        $met_mail_peserta = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_cost="' . $met_mail['id_cost'] . '" AND id_polis="' . $met_mail['id_nopol'] . '" AND id_peserta="' . $met_mail['id_peserta'] . '"'));
        
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
				$mail->AddCC("rahmad@adonaits.co.id");
        $mail->MsgHTML('<table width="100%"><tr><th>To ' . $_mailsupervisorajk['nm_lengkap'] . ' <br /> Data Refund Peserta telah diinput oleh <b>' . $_SESSION['nm_user'] . ' pada tanggal ' . $futgl . '</tr></table>' . $message); //Put your body of the message you can place html code here
        $send = $mail->Send(); //Send the mails
        // SEND SMTPMAIL//   
			
        echo '<center><b>Data Refund telah diinput oleh ' . $q['nm_lengkap'] . '.<br /><meta http-equiv="refresh" content="3;URL=ajk_klaim.php?er=valRefund"></b></center>';
      }
	  }
	  $met_refund = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_peserta="' . $_REQUEST['id'] . '"'));
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
								<td align="center">' . $met_refund['cabang'] . '</td>
							</tr>
						</table>
					  <table border="0" cellpadding="3" cellspacing="1" width="100%">					   	
							<tr><td width="10%">Tanggal Pelunasan</td><td>: ';	print initCalendar(); print calendarBox('tgl_refund', 'triger', $datelog);	echo '<br />' . $error1 . '</td></tr>
					   	<tr><td><input type="hidden" name="el" value="upload_refund"><input type="submit" name="upload" value="Proses"></td></tr>
					  </table>
					</form>';
	break;

	case "confirm_deklarasi": //EXECUTE DEKLARASI STAFF
		if (!$_REQUEST['nameRef']) {
			echo '<br><center><font color=red><blink>Tidak ada data peserta yang di pilih, silahkan ceklist data yang akan di Deklarasi. !</blink></font><br/>
	  				<a href="ajk_deklarasi.php?">Kembali Ke Halaman Deklarasi</a></center>';
    }else{
    	$no=0;
    	$nmfile='';
    	foreach($_REQUEST['nameRef'] as $r => $id) {
    		$no++;    		
    		
				$query = mysql_fetch_array(mysql_query("SELECT 	fu_ajk_spak.id_cost,
																												fu_ajk_spak.id_polis,
																												fu_ajk_spak.spak,
																												(CASE WHEN fu_ajk_spak.id_mitra in ('',0) THEN
																												1 
																												ELSE
																												fu_ajk_spak.id_mitra
																												END)as id_mitra,
																												fu_ajk_spak.ext_premi,
																												fu_ajk_spak_form.nama,
																												fu_ajk_spak_form.jns_kelamin,
																												fu_ajk_spak_form.dob,
																												fu_ajk_spak_form.x_usia,
																												fu_ajk_spak_form.tgl_asuransi,
																												fu_ajk_spak_form.plafond,
																												fu_ajk_spak_form.tenor,
																												fu_ajk_spak_form.tgl_akhir_asuransi,
																												fu_ajk_spak_form.x_premi,
																												fu_ajk_spak_form.mpp,
																												fu_ajk_regional.name as nm_regional,
																												fu_ajk_area.name as nm_area,
																												fu_ajk_cabang.name as nm_cabang,
																												fu_ajk_spak_form.filefotodebitursatu,
																												fu_ajk_spak_form.filefotoktp,
																												fu_ajk_spak.note,
																												fu_ajk_spak_form.nopermohonan,
																												fu_ajk_spak_form.nopinjaman,
																												fu_ajk_spak.danatalangan
																								FROM fu_ajk_spak 
																										 INNER JOIN fu_ajk_spak_form	
																										 ON fu_ajk_spak_form.idspk = fu_ajk_spak.id
																										 INNER JOIN fu_ajk_cabang
																										 ON fu_ajk_cabang.id = fu_ajk_spak_form.cabang
																										 INNER JOIN fu_ajk_area
																										 ON fu_ajk_area.id = fu_ajk_cabang.id_area 				
																										 INNER JOIN fu_ajk_regional
																										 ON fu_ajk_regional.id = fu_ajk_cabang.id_reg 
																								WHERE fu_ajk_spak.id = ".$id));    
				
				$qpolis = mysql_fetch_array(mysql_query("SELECT * FROM fu_ajk_polis WHERE id = '".$query['id_polis']."'"));
				
				if($query['nopinjaman'] == ""){
					$error = "Harap Lengkapi No Pinjaman Yang masih kosong";
				}

				if($query['x_usia'] < 45){
					$status_medik="DEVIASI USIA";	
				}else{
					if($qpolis['typeproduk']=="SPK"){
						$type_data = "SPK";
						$status_medik="SPK";	
					}else{
						$type_data = "SPAJ";
						$status_medik="SKKT";	
					}
				}

				if($query['jns_kelamin']=="F"){
					$jenis_kelamin = 	"P";
				}elseif($query['jns_kelamin']=="M"){
					$jenis_kelamin = 	"L";
				}
				
				if($query['danatalangan']==1){
					$tenor = $query['tenor'];				
				}else{
					if($query['id_polis'] == 1 or $query['id_polis'] == 12 or $query['id_polis'] == 19){
						$tenor = $query['tenor'];
					}else{
						$tenor = $query['tenor']*12;
					}
				}
				if($nmfile==''){
					$nmfile = $query['nm_cabang'].date('YmdGis');					
				}
				
				
				$ext_premi = ($query['x_premi'] * ($query['ext_premi'] / 100));
				$totalpremi = ROUND($query['x_premi'] + $ext_premi);

				$nama = str_replace("'", "\\'", $query['nama']);

				$query_insert = "	INSERT INTO fu_ajk_peserta_tempf SET
																			id_cost= '".$query['id_cost']."',
																			id_polis= '".$query['id_polis']."',
																			namafile= '".$nmfile."',
																			no_urut= '".$no."',
																			spaj= '".$query['spak']."' ,	
																			type_data= '".$type_data."',
																			nama_mitra='".$query['id_mitra']."',
																			nama='".$nama."',
																			gender='".$jenis_kelamin."',
																			tgl_lahir='".$query['dob']."',
																			usia='".$query['x_usia']."',	
																			kredit_tgl='".$query['tgl_asuransi']."',
																			kredit_jumlah='".$query['plafond']."',
																			kredit_tenor='".$tenor."',
																			kredit_akhir='".$query['tgl_akhir_asuransi']."',
																			premi='".$query['x_premi']."',
																			ext_premi='".$ext_premi."',
																			totalpremi='".$totalpremi."',
																			status_medik='".$status_medik."',
																			status_aktif='Upload',
																			mppbln='".$query['mpp']."',
																			regional='".$query['nm_regional']."',
																			area='".$query['nm_area']."',
																			cabang='".$query['nm_cabang']."',
																			photodebitur='".$query['filefotodebitursatu']."',
																			photoktp='".$query['filefotoktp']."',
																			ket='".$query['note']."',
																			nopermohonan='".$query['nopermohonan']."',
																			nopinjaman='".$query['nopinjaman']."',
																			danatalangan='".$query['danatalangan']."',
																			input_by='".$_SESSION['nm_user']."',
																			input_time='".$today."'";

				mysql_query($query_insert) or die("<br><br>Error message = ".mysql_error().$query_insert);				
    	} 
    	//if($error!=""){
    		//echo '<br><center>'.$error.'<br /><a href="ajk_deklarasi.php?re=datadeklarasi&idpolis='.$_REQUEST['idpolis'].'">Kembali Ke Halaman Utama</a></center>';   		
    	//}else{
    		echo '<br><center>Data Peserta Baru sudah diinput oleh <b>'.$_SESSION['nm_user'].'</b>, tunggu konfirmasi selanjutnya dari Supervisor untuk pencetakan nomor DN.<br /><a href="ajk_deklarasi.php?re=datadeklarasi&idpolis='.$_REQUEST['idpolis'].'">Kembali Ke Halaman Utama</a></center>';   		
    	//}    	
    }
	break;

	case "edit": //EXECUTE EDIT 
		$id_spk = $_REQUEST['id'];
		$tgl_asuransi = $_REQUEST['tgl_asuransi'];
		$nopinjaman = $_REQUEST['nopinjaman'];

		$spk = mysql_fetch_array(mysql_query("SELECT fu_ajk_spak_form.dob,
																								 fu_ajk_spak_form.x_usia,
																								 fu_ajk_spak_form.x_premi,
																								 fu_ajk_spak_form.tenor,
																								 fu_ajk_spak_form.mpp,
																								 fu_ajk_spak_form.plafond,
																								 fu_ajk_spak.id_polis,
																								 fu_ajk_spak.danatalangan,
																								 fu_ajk_spak.input_date
																					FROM fu_ajk_spak
																							 INNER JOIN fu_ajk_spak_form 
																							 ON fu_ajk_spak_form.idspk = fu_ajk_spak.id
																					WHERE idspk =".$id_spk));

		$polis = mysql_fetch_array(mysql_query("SELECT * FROM fu_ajk_polis WHERE id = ".$spk['id_polis']));

		//cek tgl akhir asuransi	
		if($spk['danatalangan']==1){
			$tgl_akhir_asuransi = date('Y-m-d',strtotime('+'.$spk['tenor'].' month',strtotime($tgl_asuransi)));
		}else{			
			$tgl_akhir_asuransi = date('Y-m-d',strtotime('+'.$spk['tenor'].' year',strtotime($tgl_asuransi)));			
		}

		//cek usia
		$mets = datediff($tgl_asuransi, $spk['dob']);
		$metTgl = explode(",",$mets);
		if ($metTgl[1] >= 6 ) {	
			$umur = $metTgl[0] + 1;				
		}else{	
			$umur = $metTgl[0];				
		}
		if($spk['x_usia'] != $umur){
			$qumur = ", x_usia='".$umur."'";
		}else{
			$qumur = "";
		}

		//cek rate
		if($spk['danatalangan']==1){
			$tenor = ceil($spk['tenor']/12);
		}else{
			if($spk['id_polis']==2 OR $spk['id_polis']==3 OR $spk['id_polis']==16 OR $spk['id_polis']==18 OR $spk['id_polis']==17){
				$tenor = $spk['tenor']*12;
			}else{
				$tenor = $spk['tenor'];
			}
			
		}

		if($polis['mpptype']=="Y"){
			$mpptype = 'AND '.$spk['mpp'].' BETWEEN mpp_s and mpp_e';
		}else{
			$mpptype = '';
		}		
		if($polis['mpptype']!="Y" and $polis['typeproduk']=="SPK"){
			$qpumur = "and usia='".$umur."'";
		}
		
		
		$ratebank = mysql_fetch_array(mysql_query("SELECT * FROM fu_ajk_ratepremi WHERE id_polis = '".$spk['id_polis']."' and tenor = '".$tenor."' ".$mpptype." ".$qpumur." AND '".$spk['input_date']."' BETWEEN eff_from and eff_to"));

		//cek premi
		$premi = $spk['plafond'] * $ratebank['rate'] / 1000;
		
		if($spk['ratebank'] != $ratebank){
			$qrate = ", ratebank='".$ratebank['rate']."', x_premi = '".$premi."'";
		}else{
			$qrate = "";
		}
		
		$query = "UPDATE fu_ajk_spak_form set nopinjaman='".$nopinjaman."',tgl_asuransi = '".$tgl_asuransi."',tgl_akhir_asuransi='".$tgl_akhir_asuransi."' ".$qumur." ".$qrate." WHERE idspk = ".$id_spk;

		mysql_query($query) or die("<br><br>Error message = ".mysql_error().$query);
		echo 		'<br><br><center>Data Telah Diubah</center><meta http-equiv="refresh" content="5; url=ajk_deklarasi.php">';			
	break;

	case "editspk":	//FORM EDIT
		$qspk = mysql_fetch_array(mysql_query("SELECT * FROM fu_ajk_spak_form WHERE idspk = ".$_REQUEST['id']));
		echo'	<br><table border="0" cellpadding="5" cellspacing="0" width="100%">
								<tr>
									<th width="95%" align="left">Edit</font></th>					
								</tr>				
							</table>';
		echo '<br><table border="0" width="100%" cellpadding="1" cellspacing="1">
								<form method="post" name="frm_edit_deklarasi" action="ajk_deklarasi.php?hn=edit&id='.$_REQUEST['id'].'" enctype="multipart/form-data">
									<tr>
										<td align="right" width="7%">Tgl Akad : </td>
										<td>';				
											print initCalendar();	print calendarBox('tgl_asuransi', 'triger1', $qspk['tgl_asuransi']);										
		echo 					 '</td>
									</tr>															
									<tr>
										<td align="right" width="7%">No Pinjaman : </td>										
										<!--<td><input type="text" name="nopinjaman" maxlength="10" value="'.$qspk['nopinjaman'].'"></td>-->
										<td><input type="number" name="nopinjaman" value="'.$qdeklarasi_row['nopinjaman'].'" oninput="maxLengthCheck(this)" maxlength = "10" min = "1000000000" max = "9999999999"></td>
									</tr>									
									<tr>
										<td colspan="3"></td>
									</tr>
									<td colspan="3" align="center">
										<button type="submit" class="button" style="text-align:center">Submit</button>						
									</td>										
								</form>				
							</table>';	
	break;

	case "spajView": //DEKLARASI SPV FORM
		$idcost = $_REQUEST['idc'];
		$idpolis = $_REQUEST['idp'];
		$inputby = $_REQUEST['iby'];

		$qperserta = mysql_query("SELECT *, 
																			DATE_FORMAT(input_time,'%Y-%m-%d') AS tglinput 
															FROM fu_ajk_peserta_tempf 
															WHERE id_cost='".$_REQUEST['idc']."' AND 
																		id_polis='".$_REQUEST['idp']."' AND 
																		input_by='".$_REQUEST['iby']."' AND 
																		cabang !='' AND 
																		del IS NULL and 
																		spaj not in (select spaj from fu_ajk_peserta where del is null and status_aktif = 'Approve' and spaj != '')
															ORDER BY spaj asc,id_temp DESC, id_polis ASC");
		echo '<br />
					<form method="post" action="" onload ="onbeforeunload">
						<table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#bde0e6">
							<tr><th width="1%">No</th>
								<th width="1%">Hapus</th>
								<th width="1%"><input type="checkbox" id="selectall"/></th>
								<th width="20%">Produk</th>
								<th width="1%">SPK</th>
								<th width="15%">Nama</th>
								<th width="8%">Tgl Lahir</th>
								<th width="8%">Jenis Kelamin</th>
								<th width="1%">Usia</th>
								<th width="8%">Tanggal Akad</th>
								<th width="1%">Tenor</th>
								<th width="5%">Plafond</th>
								<th width="1%">Rate</th>
								<th width="5%">Tarif Premi</th>
								<th width="1%">EM(%)</th>
								<th width="1%">Nilai EM</th>
								<th width="5%">Premi Sekaligus</th>
								<th width="1%">MPP<br />(thn)</th>
								<th width="5%">Photo Debitur</th>
								<th width="5%">Photo KTP</th>
								<th width="1%">Underwriting</th>
								<th width="1%">No.SK/Memo</th>
								<th width="1%">Hapus Memo/SK</th>
								<th width="1%">Cabang</th>
								<th width="5%">User</th>
								<th width="1%">Tgl Upload</th>
								<th width="10%">Keterangan</th>
							</tr>';
		while ($fudata = mysql_fetch_array($qperserta)) {
			$qpesertaspk = mysql_fetch_array(mysql_query("SELECT *
																										FROM fu_ajk_spak
																												 INNER JOIN fu_ajk_spak_form
																												 ON fu_ajk_spak_form.idspk = fu_ajk_spak.id 
																										WHERE fu_ajk_spak.spak = '".$fudata['spaj']."'"));

			if($fudata['premi']==0){
				$premi = $qpesertaspk['x_premi'];
				$premiextra = $premi * ($qpesertaspk['ext_premi'] /100);
			}else{
				$premi = $fudata['premi'];
				$premiextra = $fudata['ext_premi'];
			}


			if ($fudata['gender']=="M") {	
				$gender_ = "Laki-laki";	
			}elseif ($fudata['gender']=="F") {	
				$gender_ = "Perempuan";	
			}else{	
				$gender_ = '';	
			}

			$tgl_inputnya = explode(" ",$fudata['input_time']);

			$polis = mysql_fetch_array(mysql_query('SELECT * 
																							FROM fu_ajk_polis 
																							WHERE id="'.$fudata['id_polis'].'" AND 
																										id_cost="'.$fudata['id_cost'].'"'));

			$diskonpremi = $premi * $polis['discount'] /100;			//HITUNG DISKON
			$tpremi = $premi - $diskonpremi;							//HITUNG PREMI DENGAN DISKON

			$mettotal_ = ROUND($tpremi + $premiextra + $polis['adminfee']);	//HITUNG TOTAL

			if ($mettotal_ <= $polis['min_premium']) {
				$premistandar = $polis['min_premium'];
			}else{
				$premistandar = $mettotal_;
			}

			$photodeb = '<a href="../ajkmobilescript/'.$qpesertaspk['filefotodebitursatu'].'" rel="lightbox" title="view photo debitur '.$fudata['nama'].'"> <img src="../ajkmobilescript/'.$qpesertaspk['filefotodebitursatu'].'" width="30"></a> &nbsp;';
			$ktpdeb = '<a href="../ajkmobilescript/'.$qpesertaspk['filefotoktp'].'" rel="lightbox" ><img src="../ajkmobilescript/'.$qpesertaspk['filefotoktp'].'" width="35"></a>';
			$dataceklist = '<input type="checkbox" class="case" name="idtemp[]" value="'.$fudata['id_temp'].'">';
			
			if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
				echo'	<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
							  <td align="center">'.++$no.'</td>						  
							  <td align="center"><a href="ajk_deklarasi.php?hn=deldata&idt='.$fudata['id_temp'].'" onClick="if(confirm(\'Apakah anda yakin untuk menghapus data peserta ini ?\')){return true;}{return false;}"><img src="image/delete.gif"></a></td>
							  <td align="center">'.$dataceklist.'</td>
							  <td>'.$polis['nmproduk'].'</td>
							  <td align="center">'.$fudata['spaj'].'</td>
							  <td>'.$fudata['nama'].''.$status_pesertanya.'</td>
							  <td align="center">'._convertDate($fudata['tgl_lahir']).'</td>
							  <td align="center">'.$gender_.'</td>
							  <td align="center">'.$fudata['usia'].'</td>
							  <td align="right">'._convertDate($fudata['kredit_tgl']).'</td>
							  <td align="center">'.$fudata['kredit_tenor'].'</td>
							  <td align="right">'.duit($fudata['kredit_jumlah']).'</td>
							  <td align="center">'.$qpesertaspk['ratebank'].'</td>
							  <td align="right">'.duit($premi).'</td>
							  <td align="center">'.$qpesertaspk['ext_premi'].'</td>
							  <td align="right">'.duit($premiextra).'</td>
							  <td align="right">'.duit($premistandar).'</td>
							  <td align="center">'.$fudata['mppbln'].'</td>
					  	  <td align="center">'.$photodeb.'</td>
					  	  <td align="center">'.$ktpdeb.'</td>
							  <td align="center">'.$filemedisdeb.'</td>
							  <td align="center">'.$fudata['nomemosk'].'</td>
							  <td align="center">'.$filemedisdebnmrdel.'</td>
							  <td align="center">'.$fudata['cabang'].'</td>
							  <td align="center"><font color="blue"><b>'.$fudata['input_by'].'</b></font></td>
							  <td align="center">'._convertDate($tgl_inputnya[0]).'</td>
							  <td>'.$fudata['ket'].'</td>
						  </tr>';
		}

		if ($q['level']=="6") {
			echo '<tr><td colspan="25" align="center"><a href="#" onClick="if(confirm(\'Apakah anda sudah yakin dengan semua data peserta ini ?\')){return true;}{return false;}">
						<input type="hidden" name="hn" Value="spaj_appr"><input type="submit" name="ve" Value="Approve"></td></tr>';
		}
	break;

	case "deldata": //MENGEMBALIKAN KE PESERTA 
		$database->doQuery('delete from fu_ajk_peserta_tempf where id_temp = "'.$_REQUEST['idt'].'"');		
		echo '<br><center><b>Data sudah di hapus<br /><meta http-equiv="refresh" content="3;URL=ajk_deklarasi.php?hn=spajView"></b></center>';
	break;

	case "spaj_appr": //EXECUTE DEKLARASI SPV 		
		if (!$_REQUEST['idtemp']) {
			echo '<center><font color=red><blink><br /><br />Tidak ada data yang di pilih, silahkan ceklist data yang akan di Approve. !</blink></font><br/>
			  		<a href="ajk_val_upl.php?v=spaj">Kembali Ke Halaman Approve Peserta</a></center>';
		}else{
			foreach($_REQUEST['idtemp'] as $k => $val){
				$qpeserta = mysql_fetch_array(mysql_query("SELECT * 
																									 FROM fu_ajk_peserta_tempf 
																									 WHERE id_temp = '".$val."'"));

				$qspk = mysql_fetch_array(mysql_query("SELECT *
																							 FROM fu_ajk_spak
																							 			INNER JOIN fu_ajk_spak_form
																							 			ON fu_ajk_spak_form.idspk = fu_ajk_spak.id
																							 WHERE spak = '".$qpeserta['spaj']."'"));

				$qpolis = mysql_fetch_array(mysql_query("SELECT * FROM fu_ajk_polis WHERE id = '".$qpeserta['id_polis']."' and del is null"));

				if($qpeserta['kredit_akhir']=="0000-00-00"){
					$kredit_akhir = $qspk['tgl_akhir_asuransi'];
				}else{
					$kredit_akhir = $qpeserta['kredit_akhir'];
				}

				if($qpeserta['premi']==0){
					$premi  = $qspk['x_premi'];
					$premiextra = $premi * ($qspk['ext_premi'] /100);
				}else{
					$premi  = $qpeserta['premi'];
					$premiextra = $qpeserta['ext_premi'];
				}
								
				$diskonpremi = $premi * $qpolis['discount'] /100;			//HITUNG DISKON
				$tpremi = $premi - $diskonpremi;							//HITUNG PREMI DENGAN DISKON

				$mettotal_ = ROUND($tpremi + $premiextra + $qpolis['adminfee']);	//HITUNG TOTAL

				if($mettotal_ <= $qpolis['min_premium']){
					$cmp = $qpolis['min_premium'] - $mettotal_;
					$totalpremi = $qpolis['min_premium'];
				}else{
					$cmp = 0;
					$totalpremi = $mettotal_;
				}
				
				//VALIDASI TABEL MEDICAL STATUS MEDIK
				if ($qpolis['freecover']=="T") {
					$medik = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_medical WHERE id_cost="'.$qpeserta['id_cost'].'" AND  id_polis="'.$qpeserta['id_polis'].'" AND '.$qpeserta['usia'].' BETWEEN age_from AND age_to AND '.$qpeserta['kredit_jumlah'].' BETWEEN si_from AND si_to  AND del IS NULL'));
					$status_medik =$medik['type_medical'];
					if ($status_medik=="NM" OR $status_medik=="FCL" OR $status_medik=="SPD" OR $status_medik=="SPK" OR $status_medik=="SKKT")
					{	$status_pesertanya = "Approve";	}else{	$status_pesertanya = "Pending";	}
					//CEK KETERANGAN UPLOAD FILE KOLOM KETERANGAN APABILA ADA KATA SAKIT DATA MASUK KE PENDING UNTUK PRODUK YG SKKT = Y
					if ($admpolis['skkt']=="Y") {
						if (strpos($r['ket'],'SAKIT')) {	$status_pesertanya = "Pending";
						}	else	{
							$status_pesertanya = "Approve";
						}
					}else{
						$status_pesertanya = "Approve";
					}
					//CEK KETERANGAN UPLOAD FILE KOLOM KETERANGAN APABILA ADA KATA SAKIT DATA MASUK KE PENDING UNTUK PRODUK YG SKKT = Y
				}else{
					//CEK KETERANGAN UPLOAD FILE KOLOM KETERANGAN APABILA ADA KATA SAKIT DATA MASUK KE PENDING UNTUK PRODUK YG SKKT = Y
					$status_medik ="FCL";
					if ($admpolis['skkt']=="Y") {
						if (strpos($r['ket'],'SAKIT')) {	$status_pesertanya = "Pending";
						}	else	{
							$status_pesertanya = "Approve";
						}
					}else{
						$status_pesertanya = "Approve";
					}
					//CEK KETERANGAN UPLOAD FILE KOLOM KETERANGAN APABILA ADA KATA SAKIT DATA MASUK KE PENDING UNTUK PRODUK YG SKKT = Y
				}
				//END VALIDASI TABEL MEDICAL STATUS MEDIK

				$formattgl = explode("/", $qpeserta['kredit_tgl']);		
				$formattgl1 = $formattgl[2].'-'.$formattgl[1].'-'.$formattgl[0];		// SET FORMAT TANGGAL
				$cekpesertaID = mysql_fetch_array($database->doQuery('SELECT id FROM fu_ajk_peserta ORDER BY id DESC'));					// SET ID PESERTA
				$idnya = 10000000000 + $cekpesertaID['id'] + 1; 
				$idnya2 = substr($idnya, 1);													

				if ($qpeserta['medicalfile']!="") {	
					$datamedical = "Process";	
				}else{	
					$datamedical = NULL;	
				}

				$nama = str_replace("'", "\\'", $qpeserta['nama']);

				$mameto = $database->doQuery('INSERT INTO fu_ajk_peserta 
																			SET 	id_cost="'.$qpeserta['id_cost'].'",
																						id_polis="'.$qpeserta['id_polis'].'",
																						namafile="'.$qpeserta['namafile'].'",
																						no_urut="'.$qpeserta['no_urut'].'",
																						spaj="'.$qpeserta['spaj'].'",
																						type_data="'.$qpeserta['type_data'].'",
																						id_peserta="'.$idnya2.'",
																						nama_mitra="'.$qpeserta['nama_mitra'].'",
																						nama="'.$nama.'",
																						gender="'.$qpeserta['gender'].'",
																						tgl_lahir="'.$qpeserta['tgl_lahir'].'",
																						usia="'.$qpeserta['usia'].'",
																						kredit_tgl="'.$qpeserta['kredit_tgl'].'",
																						kredit_jumlah="'.$qpeserta['kredit_jumlah'].'",
																						kredit_tenor="'.$qpeserta['kredit_tenor'].'",
																						kredit_akhir="'.$kredit_akhir.'",
																						ratebank="'.$qspk['ratebank'].'",
																						premi="'.$premi.'",
																						disc_premi="'.$qpeserta['disc_premi'].'",
																						bunga="'.$qpeserta['bunga'].'",
																						biaya_adm="'.$qpeserta['biaya_adm'].'",																						
																						ext_premi="'.$premiextra.'",
																						cmp="'.$cmp.'",
																						totalpremi="'.$totalpremi.'",
																						badant="",
																						badanb="",
																						ketupload="'.$qpeserta['ket'].'",
																						status_medik="'.$qpeserta['status_medik'].'",
																						status_bayar="0",
																						status_aktif="'.$status_pesertanya.'",
																						mppbln="'.$qpeserta['mppbln'].'",
																						regional="'.$qpeserta['regional'].'",
																						area="'.$qpeserta['area'].'",
																						cabang="'.$qpeserta['cabang'].'",
																						memousia="'.$qpeserta['memousia'].'",
																						nomemosk="'.$qpeserta['nomemosk'].'",
																						medicalfile="'.$qpeserta['medicalfile'].'",
																						medicalfile_status="'.$datamedical.'",
																						danatalangan="'.$qpeserta['danatalangan'].'",
																						nopinjaman="'.$qpeserta['nopinjaman'].'",
																						input_by ="'.$qpeserta['input_by'].'",
																						input_time ="'.$qpeserta['input_time'].'",
																						approve_by ="'.$_SESSION['nm_user'].'",
																						approve_time ="'.$today.'"'); 				
			}
			echo '<br><center>Approve oleh <b>'.$_SESSION['nm_user'].'</b> telah berhasil.<br /><meta http-equiv="refresh" content="3;URL=ajk_deklarasi.php?hn=spaj">';
		}
	break;

	case "spaj": //SPV LIST PRODUK
		$cust = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$q['id_cost'].'"'));
		echo '<br /><table border="0" width="100%" cellpadding="0" cellspacing="0">
					  			<tr>
					  				<td width="15%">Nama Perusahaan</td><td>: <input type="hidden" name="idcost" value="'.$q['id_cost'].'">'.$cust['name'].'</td>
					  			</tr>';
		if ($q['level']=="6") {		
			$fieldData = '<th width="5%">Approve</th>';	
		}
		echo '				<tr>
										<td colspan="2">';
		$cekCentral = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE name="'.$q['cabang'].'" and del is null'));
		$cekCentral_ = $database->doQuery('SELECT * FROM fu_ajk_cabang WHERE centralcbg="'.$cekCentral['id'].'"');
		
		while ($cekCentral__ = mysql_fetch_array($cekCentral_)) {
			$metCentralCabang .= ' OR fu_ajk_peserta_tempf.cabang ="'.$cekCentral__['name'].'"';
		}

		//CEK DATA CABANG CENTRAL ATAU PUSAT;
		if ($q['wilayah']=="PUSAT" AND $q['cabang']=="PUSAT") {
			$metCabangCentral = '';
		}elseif ($q['wilayah']!="PUSAT" AND $q['cabang']=="PUSAT") {
			$cekCentral = $database->doQuery('SELECT fu_ajk_regional.`name` AS regional, fu_ajk_cabang.`name` AS cabang
																			  FROM fu_ajk_regional
																			  INNER JOIN fu_ajk_cabang ON fu_ajk_regional.id = fu_ajk_cabang.id_reg
																			  WHERE fu_ajk_regional.`name` = "'.$q['wilayah'].'" AND fu_ajk_cabang.del IS NULL');
			while ($cekCentral__ = mysql_fetch_array($cekCentral)) {
				$metCabangCentral .= 'OR (fu_ajk_peserta_tempf.cabang ="'.$cekCentral__['cabang'].'")';
			}
			$metCabangCentral = 'AND fu_ajk_peserta_tempf.cabang ="'.$q['cabang'].'" '.$metCabangCentral.'';
		}else{
			if ($metCentralCabang=="") {
				$metCabangCentral = 'AND fu_ajk_peserta_tempf.cabang ="'.$q['cabang'].'"';
			}else{
				$metCabangCentral = 'AND (fu_ajk_peserta_tempf.cabang ="'.$q['cabang'].'" '.$metCentralCabang.')';
			}
		}
		
		//CEK DATA CABANG CENTRAL ATAU PUSAT;
		$metData = $database->doQuery('	SELECT  fu_ajk_peserta_tempf.id_temp, 
																						fu_ajk_peserta_tempf.id_cost, 
																						fu_ajk_peserta_tempf.id_polis, 
																						fu_ajk_polis.nmproduk, 
																						COUNT(fu_ajk_peserta_tempf.nama) AS jData, 
																						fu_ajk_peserta_tempf.cabang, 
																						fu_ajk_peserta_tempf.input_by
																		FROM fu_ajk_peserta_tempf
																		LEFT JOIN fu_ajk_polis ON fu_ajk_peserta_tempf.id_polis = fu_ajk_polis.id
																		WHERE fu_ajk_peserta_tempf.id_cost="'.$q['id_cost'].'" AND 
																					fu_ajk_peserta_tempf.nama !="" AND 
																					fu_ajk_peserta_tempf.status_aktif = "Upload" AND
																					fu_ajk_peserta_tempf.spaj not in (select spaj from fu_ajk_peserta where del is null and status_aktif = "Approve" and spaj != "") and
																					fu_ajk_peserta_tempf.del IS NULL '.$userInput.' '.$metCabangCentral.'
																		GROUP BY fu_ajk_peserta_tempf.input_by,  fu_ajk_peserta_tempf.id_polis ');

		echo '<table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#bde0e6">
							<tr><th width="1%">No</th>
								<th>Produk</th>
								<th width="15%">Data</th>
								'.$fieldData.'
							</tr>';
		while ($metData_ = mysql_fetch_array($metData)) {
			if ($q['level']=="6") {
				$fieldData__ = '<td align="center"><a href="ajk_deklarasi.php?hn=spajView&idc='.$metData_['id_cost'].'&idp='.$metData_['id_polis'].'&iby='.$metData_['input_by'].'"><img src="image/save.png" width="15"</a></td>';
			}
			if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
			echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
								  <td align="center">'.++$no.'</td>
								  <td>'.$metData_['nmproduk'].'</td>
								  <td align="center">'.$metData_['jData'].' Debitur</td>
								  '.$fieldData__.'
								  </tr>';
		}
				echo '</table></td></tr></table>';				
	break;

	default : //DEKLARASI STAFF FORM
		echo '<table border="0" cellpadding="1" cellspacing="0" width="100%"><tr><th width="100%" align="left">Modul Deklarasi</font></th></tr></table>';
		$metcost = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$q['id_cost'].'"'));
		$met_polis = $database->doQuery('SELECT * FROM fu_ajk_polis WHERE ('.$mametProdukUser.') AND del IS NULL ORDER BY nmproduk ASC');
				echo '<form name="f1" method="post" enctype="multipart/form-data" action="">
						  <input type="hidden" name="id_cost" value="'.$metcost['id'].'">
						  <table border="0" cellpadding="1" cellspacing="0" width="50%" align="center">
					    <tr>
					    	<td width="40%">Nama Perusahaan</td><td> : '.$metcost['name'].'</td>
					    </tr>					  
						  <tr>
						  	<td>Nama Produk</td>
						  	<td>: <select name="idpolis">
						  		<option value="">---Pilih Produk---</option>';
				while($met_polis_ = mysql_fetch_array($met_polis)) {
					echo '<option value="'.$met_polis_['id'].'"'._selected($_REQUEST['idpolis'], $met_polis_['id']).'>'.$met_polis_['nmproduk'].'</option>';
				}
					echo '</select></td></tr>
								<tr>
						  		<td>Nama Debitur</td>
						  		<td>: <input type="text" name="nama" value="'.$_REQUEST['nama'].'"></input></td>
						  	</tr>
						  	<tr>
						  		<td>No SPK / No Formulir</td>
						  		<td>: <input type="text" name="spak" value="'.$_REQUEST['spak'].'"></input></td>
						  	</tr>						  	
					  	  <tr><td align="center"colspan="2"><input type="hidden" name="re" value="datadeklarasi"><input name="Cari" type="submit" value="Cari"></td></tr>
					  	  </table></form><br>';
		if($_REQUEST['re']=="datadeklarasi"){
			$idpolis = $_REQUEST['idpolis'];
			$metpolis = mysql_fetch_array(mysql_query("SELECT * FROM fu_ajk_polis WHERE id = '".$idpolis."' and del is null"));
			$qcabang = mysql_fetch_array(mysql_query("SELECT * FROM fu_ajk_cabang WHERE name = '".$q['cabang']."' and del is NULL and id_cost = 1"));
			$qcentral = mysql_fetch_array(mysql_query("SELECT count(*)as jumlah FROM fu_ajk_cabang WHERE centralcbg =".$qcabang['id']));

			if($_REQUEST['nama'] != ""){
				//$nama_pes = str_replace("'", "\\'", $_REQUEST['nama']);
				$nama = " fu_ajk_spak_form.nama like '%".$_REQUEST['nama']."%' AND" ;
			}
			
			if($_REQUEST['spak']!=""){
				$spak = " fu_ajk_spak.spak ='".$_REQUEST['spak']."' AND" ;
			}

			if($qcentral['jumlah'] > 0){
				$cabang = "(fu_ajk_cabang.centralcbg = ".$qcabang['id']." OR fu_ajk_cabang.id = '".$qcabang['id']."')	AND ";
			}else{
				if($q['cabang'] == 'PUSAT' AND $q['wilayah'] == 'MEKARSARI'){
					$cabang = "fu_ajk_cabang.id_reg = 18 AND ";	
				}else{
					$cabang = "fu_ajk_cabang.name = '".$q['cabang']."' AND ";	
				}
			}		

			$qdeklarasi = mysql_query("SELECT fu_ajk_grupproduk.nmproduk as nm_mitra,
																				fu_ajk_spak.spak,
																				fu_ajk_spak_form.nama,
																				fu_ajk_spak_form.dob,
																				fu_ajk_spak_form.plafond,
																				fu_ajk_spak_form.tgl_asuransi,
																				fu_ajk_spak_form.tgl_akhir_asuransi,
																				fu_ajk_spak_form.tenor,
																				fu_ajk_spak.ext_premi,
																				fu_ajk_cabang.name as nm_cabang,
																				fu_ajk_polis.nmproduk,
																				fu_ajk_spak_form.mpp,
																				fu_ajk_spak.danatalangan,
																				fu_ajk_spak.id as id_spak,
																				fu_ajk_spak_form.x_premi,
																				fu_ajk_spak_form.x_usia,
																				fu_ajk_spak_form.nopinjaman,
																				fu_ajk_spak_form.ratebank,
																				fu_ajk_spak.id_peserta
																FROM fu_ajk_spak 
																		 INNER JOIN fu_ajk_spak_form	
																		 on fu_ajk_spak_form.idspk = fu_ajk_spak.id
																		 INNER JOIN fu_ajk_cabang
																		 on fu_ajk_cabang.id = fu_ajk_spak_form.cabang
																		 INNER JOIN fu_ajk_grupproduk
																		 ON fu_ajk_grupproduk.id = (case when fu_ajk_spak.id_mitra = 0 then 1 else  IFNULL(fu_ajk_spak.id_mitra,1) end)
																		 INNER JOIN fu_ajk_polis	
																		 ON fu_ajk_polis.id = fu_ajk_spak.id_polis
																WHERE fu_ajk_spak.del is null AND
																			fu_ajk_spak_form.del is null AND
																			fu_ajk_cabang.del is null AND
																			fu_ajk_spak.status = 'Aktif' AND
																			".$cabang."
																			".$nama."
																			".$spak."																			
																			fu_ajk_spak.id_polis = ".$idpolis." AND
																			fu_ajk_spak.spak not in (SELECT spaj FROM fu_ajk_peserta_tempf where del is null)");

			$qtopup = mysql_fetch_array(mysql_query("SELECT count(*)as topup
																							FROM fu_ajk_spak 
																									 INNER JOIN fu_ajk_spak_form	
																									 on fu_ajk_spak_form.idspk = fu_ajk_spak.id
																									 INNER JOIN fu_ajk_cabang
																									 on fu_ajk_cabang.id = fu_ajk_spak_form.cabang
																									 INNER JOIN fu_ajk_grupproduk
																									 ON fu_ajk_grupproduk.id = (case when fu_ajk_spak.id_mitra = 0 then 1 else  IFNULL(fu_ajk_spak.id_mitra,1) end)
																									 INNER JOIN fu_ajk_polis	
																									 ON fu_ajk_polis.id = fu_ajk_spak.id_polis
																							WHERE fu_ajk_spak.del is null AND
																										fu_ajk_spak_form.del is null AND
																										fu_ajk_cabang.del is null AND
																										fu_ajk_spak.status = 'Aktif' AND
																										".$cabang."
																										".$nama."
																										".$spak."																										
																										fu_ajk_spak.id_polis = ".$idpolis." AND
																										fu_ajk_spak.note = 'Topup' AND
																										fu_ajk_spak.spak not in (SELECT spaj FROM fu_ajk_peserta_tempf where del is null)"));

			if($qtopup > 0){
				$topup = '<th width="5%">Refund</th>';
			}else{
				$topup = '';
			}
			if($metpolis['typeproduk']=="SPK"){
				$produk = 'No. SPK';				
				$em='<th width="5%">Ext. Premi</th>';			
			}else{
				$produk = 'No Formulir';
				$em='';				
			}
			if($metpolis['mpptype']=="Y"){
				$mpp = '<th width="5%">MPP</th>';
			}else{
				$mpp = '';
			}

			echo '<form method="post" action="">
					  	<table border="0" width="100%" cellpadding="3" cellspacing="1" bgcolor="#bde0e6">
								<tr>
									<th width="5%">Check<input type="checkbox" id="selectall"/></th>
									<th width="1%">No</th>
									<th width="5%">Nama Mitra</th>
									<th width="7%">Produk</th>
									<th width="5%">'.$produk.'</th>
									<th>Nama Tertanggung</th>
									<th width="5%">Plafond</th>
									<th width="5%">Tanggal Lahir</th>																
									<th width="5%">Tgl Akad</th>
									<th width="2%">Usia</th>
									<th width="5%">Tenor</th>
									<th width="5%">Tgl Akhir Akad</th>
									<th width="2%">Rate</th>
									<th width="5%">Premi</th>
									'.$em.'
									<th width="5%">Total Premi</th>
									<th width="5%">Cabang</th>								
									'.$mpp.'
									'.$topup.'
									<th width="5%">No Pinjaman</th>
								</tr>';
			$no = 0;
			while($qdeklarasi_row = mysql_fetch_array($qdeklarasi)){
				if($metpolis['typeproduk']=="SPK"){					
					$em='<td align="center">'.$qdeklarasi_row['ext_premi'].'%</td>';					
				}else{						
					$em = '';
				}

				if($qdeklarasi_row['id_peserta'] != ""){
					$topupopt = '<td align="center"><a href="ajk_deklarasi.php?hn=settRefund&id='.$qdeklarasi_row['id_peserta'].'">Process</a></td>';
				}else{
					$topupopt = '<td align="center">-</td>';
				}

				if($metpolis['mpptype']=="Y"){
					if($qdeklarasi_row['danatalangan']==1){
						$tenor = '<td align="center">'.$qdeklarasi_row['tenor'].' Bln</td>';
					}else{
						$tenor = '<td align="center">'.$qdeklarasi_row['tenor'].'</td>';
					}	
					$mpp = '<td align="center">'.$qdeklarasi_row['mpp'].'</td>';				
				}else{
					$mpp = '';
					$tenor = '<td align="center">'.$qdeklarasi_row['tenor'].'</td>';
				}

				$totalpremi = ROUND($qdeklarasi_row['x_premi'] + ($qdeklarasi_row['x_premi'] * ($qdeklarasi_row['ext_premi'] / 100)));
				if($totalpremi < $metpolis['min_premium']){
					$totalpremi = $metpolis['min_premium'];
				}else{
					$totalpremi = $totalpremi;
				}

				$no++;

				if (($no % 2) == 1) $objlass = 'tbl-odd';	else $objlass = 'tbl-even';
					echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
									<td align="center">
									<a href="ajk_deklarasi.php?hn=editspk&id='. $qdeklarasi_row['id_spak'].'" title="Edit"><img src="image/edit3.png" width="15"></a>
									<input type="checkbox" class="case" name="nameRef[]" value="'.$qdeklarasi_row['id_spak'].'"></td>
									<td align="center">'.$no.'</td>
									<td>'.$qdeklarasi_row['nm_mitra'].'</td>
									<td>'.$qdeklarasi_row['nmproduk'].'</td>
									<td align="center">'.$qdeklarasi_row['spak'].'</td>
									<td>'.$qdeklarasi_row['nama'].'</td>
									<td align="center">'.duit($qdeklarasi_row['plafond']).'</td>
									<td align="center">'._convertdate($qdeklarasi_row['dob']).'</td>
									<td align="center">'._convertdate($qdeklarasi_row['tgl_asuransi']).'</td>
									<td align="center">'.$qdeklarasi_row['x_usia'].'</td>
									'.$tenor.'									
									<td align="center">'._convertdate($qdeklarasi_row['tgl_akhir_asuransi']).'</td>
									<td align="center">'.$qdeklarasi_row['ratebank'].'</td>
									<td align="center">'.duit($qdeklarasi_row['x_premi']).'</td>
									'.$em.'
									<td align="center">'.duit($totalpremi).'</td>
								  <td align="center">'.$qdeklarasi_row['nm_cabang'].'</td>
									'.$mpp.'
									'.$topupopt.'
									<td align="center">'.$qdeklarasi_row['nopinjaman'].'</td>
								</tr>';			
				}
					echo '<tr>
									<td colspan="20" align="center"><a href="#" onClick="if(confirm(\'Apakah tanggal Akad pada peserta yang dipilih sudah sesuai dengan PK? Pilih Ok jika sudah sesuai, atau Cancel untuk merubah tanggal akad\')){return true;}{return false;}">
							  	<input type="hidden" name="hn" Value="confirm_deklarasi"><input type="submit" name="ve" Value="Confirm"></td>
							  </tr>
							</table>
							<input type="hidden" name=idpolis value="'.$idpolis.'"></input>
						</form>';				
	}
}				
?>

<SCRIPT language="javascript">
	function maxLengthCheck(object){
	  if (object.value.length > object.maxLength)
	    object.value = object.value.slice(0, object.maxLength)
	}
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