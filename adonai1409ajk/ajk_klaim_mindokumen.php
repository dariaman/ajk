<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Hansen
// E-mail :hansendputra@gmail.com
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("../includes/functions.php");
connect();
if (isset($_SESSION['nm_user'])) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));	}
$futoday  = date("d n Y");
$now = date("Y-m-d H:i:s");

$nmBulan = explode(" ", $futoday);
if ($nmBulan=="01") {
    $_nmBulan = "Januari";
} elseif ($nmBulan[1]=="02") {
    $_nmBulan = "Februari";
} elseif ($nmBulan[1]=="03") {
    $_nmBulan = "Maret";
} elseif ($nmBulan[1]=="04") {
    $_nmBulan = "April";
} elseif ($nmBulan[1]=="05") {
    $_nmBulan = "Mei";
} elseif ($nmBulan[1]=="06") {
    $_nmBulan = "Juni";
} elseif ($nmBulan[1]=="07") {
    $_nmBulan = "Juli";
} elseif ($nmBulan[1]=="08") {
    $_nmBulan = "Agustus";
} elseif ($nmBulan[1]=="09") {
    $_nmBulan = "September";
} elseif ($nmBulan[1]=="10") {
    $_nmBulan = "Oktober";
} elseif ($nmBulan[1]=="11") {
    $_nmBulan = "November";
} else {
    $_nmBulan = "Desember";
}
$today =$nmBulan[0].' '.$_nmBulan.' '.$nmBulan[2];

switch ($_REQUEST['d']) {
	case "mindokumen" :
		echo '
		<table border="0" cellpadding="3" cellspacing="0" width="100%">
			<tr><th colspan="8" width="100%" align="left" colspan="2">Modul Klaim - Rekap Kurang Dokumen</font></th></tr>
		</table><br />
		<table border="0" width="100%" cellpadding="5" cellspacing="1" bgcolor="#bde0e6">
			<tr>
				<th width="3%">No</td>
				<th width="5%">Cabang</td>
				<th width="8%">Download</td>
				<th width="10%">Print</td>
				<th width="10%">Tgl Kirim</td>
			</tr>';
		$query = "select count(cabang)as jml,cabang,tgl_kirim
		from(
		select
								case when (fu_ajk_peserta.regional = 'KKB' or 
											 fu_ajk_peserta.regional = 'KNS' or 
											 fu_ajk_peserta.regional = 'BHAKTI ABADI' or
											 fu_ajk_peserta.regional = 'KOSPPI' or 
											 fu_ajk_peserta.regional = 'MEKARSARI' or
											 fu_ajk_peserta.regional = 'KAR' or
											 fu_ajk_peserta.regional = 'GILANG GEMILANG'
					) then fu_ajk_peserta.regional else fu_ajk_peserta.cabang end as cabang,
						fu_ajk_peserta.regional,
						fu_ajk_klaim.id_peserta,
						(SELECT tgl_kirim FROM fu_ajk_his_kirim_surat WHERE fu_ajk_his_kirim_surat.surat = 'Pelaporan Kurang Dokumen' and fu_ajk_his_kirim_surat.keytable = fu_ajk_cabang.id order by tgl_kirim desc limit 1)as tgl_kirim 
		FROM fu_ajk_klaim 
		INNER JOIN fu_ajk_peserta on fu_ajk_peserta.id_peserta = fu_ajk_klaim.id_peserta
		INNER JOIN fu_ajk_cn on fu_ajk_cn.id = fu_ajk_klaim.id_cn and fu_ajk_cn.type_claim = 'Death' and confirm_claim <> 'Pending'
		INNER JOIN fu_ajk_cabang on fu_ajk_cabang.name = fu_ajk_peserta.cabang and fu_ajk_cabang.del is null and fu_ajk_cabang.id_cost = fu_ajk_peserta.id_cost
		WHERE fu_ajk_klaim.del is NULL AND id_klaim_status = 2
		)as temp
		group by cabang";

		$query2 = "
		SELECT fu_ajk_peserta.cabang,count(*)as jml,(SELECT tgl_kirim FROM fu_ajk_his_kirim_surat WHERE fu_ajk_his_kirim_surat.surat = 'Pelaporan Kurang Dokumen' and fu_ajk_his_kirim_surat.keytable = fu_ajk_cabang.id order by tgl_kirim desc limit 1)as tgl_kirim 
		FROM fu_ajk_klaim 
		INNER JOIN fu_ajk_peserta on fu_ajk_peserta.id_peserta = fu_ajk_klaim.id_peserta
		INNER JOIN fu_ajk_cn on fu_ajk_cn.id = fu_ajk_klaim.id_cn and fu_ajk_cn.type_claim = 'Death' and confirm_claim <> 'Pending'
		INNER JOIN fu_ajk_cabang on fu_ajk_cabang.name = fu_ajk_peserta.cabang and fu_ajk_cabang.del is null and fu_ajk_cabang.id_cost = fu_ajk_peserta.id_cost
		WHERE fu_ajk_klaim.del is NULL AND id_klaim_status = 2 
		GROUP BY fu_ajk_peserta.cabang";
		$query3 = 
		"SELECT cabang,regional,sum(jml)as jml, tgl_kirim
		FROM(
		SELECT case when (fu_ajk_peserta.regional = 'KKB' or 
											 fu_ajk_peserta.regional = 'KNS' or 
											 fu_ajk_peserta.regional = 'BHAKTI ABADI' or
											 fu_ajk_peserta.regional = 'KOSPPI' or 
											 fu_ajk_peserta.regional = 'MEKARSARI' or
											 fu_ajk_peserta.regional = 'KAR' or
											 fu_ajk_peserta.regional = 'GILANG GEMILANG'
		) then fu_ajk_peserta.regional else fu_ajk_peserta.cabang end as cabang,
						fu_ajk_peserta.regional,				 
						count(*)as jml,
						(SELECT tgl_kirim FROM fu_ajk_his_kirim_surat WHERE fu_ajk_his_kirim_surat.surat = 'Pelaporan Kurang Dokumen' and fu_ajk_his_kirim_surat.keytable = fu_ajk_cabang.id order by tgl_kirim desc limit 1)as tgl_kirim 
		FROM fu_ajk_klaim 
		INNER JOIN fu_ajk_peserta on fu_ajk_peserta.id_peserta = fu_ajk_klaim.id_peserta
		INNER JOIN fu_ajk_cn on fu_ajk_cn.id = fu_ajk_klaim.id_cn and fu_ajk_cn.type_claim = 'Death' and confirm_claim <> 'Pending'
		INNER JOIN fu_ajk_cabang on fu_ajk_cabang.name = fu_ajk_peserta.cabang and fu_ajk_cabang.del is null and fu_ajk_cabang.id_cost = fu_ajk_peserta.id_cost
		WHERE fu_ajk_klaim.del is NULL AND id_klaim_status = 2 
		GROUP BY cabang)as temp
		group by cabang";

		$metklaim = mysql_query($query);

		$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
		while ($rklaim = mysql_fetch_array($metklaim)) {
			if (($no % 2) == 1)	$objlass = 'tbl-odd'; else $objlass = 'tbl-even';			
			echo '
			<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
				<td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
				<td align="center">'.$rklaim['cabang'].' ['.$rklaim['jml'].']</td>
				<td align="center"><a href="e_report.php?er=klaimkurangdokumen&cab='.$rklaim['cabang'].'" target="_blank"><img src="image/excel.png" width="22" height="22"></a></td>				
				<td align="center"><a href="ajk_klaim_mindokumen.php?d=previewsendklaimkurangdokumen&cab='.$rklaim['cabang'].'" target="_blank"><img src="image/print.png" width="22" height="22"></a></td>
				<td align="center">'.$rklaim['tgl_kirim'].'</td>
			</tr>';
		}
		echo '</table>';
	break;
	
	case "previewsendklaimkurangdokumen":
		$cab = $_REQUEST['cab'];
		//$qcabang = mysql_query("select * from fu_ajk_cabang where name = '".$cab."' and del is null and id_cost=1");

		if($_REQUEST['cab']=='KNS' or $_REQUEST['cab']=='KKB' or $_REQUEST['cab']=='BHAKTI ABADI' or $_REQUEST['cab']=='KOSPPI' or $_REQUEST['cab']=='MEKARSARI' or $_REQUEST['cab']=='KAR' or $_REQUEST['cab']=='GILANG GEMILANG'){
			$where = ' and regional = "'.$cab.'"';
			$querycabang = "select * from fu_ajk_cabang where id_reg in (select id from fu_ajk_regional where name = '".$cab."' and del is null) and del is null";
		}else{
			$where = ' and id_cabang ="'.$cab.'"';
			$querycabang = "select * from fu_ajk_cabang where name = '".$cab."' and del is null and id_cost=1";
		}

		$qcabang = mysql_query($querycabang);
		
		if (mysql_num_rows($qcabang) > 0) {
			$qcabang_row = mysql_fetch_array($qcabang);
			if ($qcabang_row['id_reg'] == 15 or $qcabang_row['id_reg'] == 24 or $qcabang_row['id_reg'] == 17 or $qcabang_row['id_reg'] == 20 or $qcabang_row['id_reg'] == 19 or $qcabang_row['id_reg'] == 18) {
					$qemailto = mysql_query("select * from fu_ajk_email_cabang where id_cabang = (select name from fu_ajk_regional where id = '".$qcabang_row['id_reg']."')");
			} else {
					//cek sentralisasi
					if ($qcabang_row['centralcbg']=="") {							
							$qemailto = mysql_query("select * from fu_ajk_email_cabang where id_cabang = (select id from fu_ajk_cabang where name = '".$cab."' and del is null AND id_cost=1)");
					} else {
							$qemailto = mysql_query("select * from fu_ajk_email_cabang where id_cabang = 4");
							$qemailtoaa = mysql_query("select * from fu_ajk_email_cabang where id_cabang = (select id from fu_ajk_cabang where name = '".$cab."' and del is null AND id_cost=1)");
					}
			}
		} else {
				$qemailto = mysql_query("select * from fu_ajk_email_cabang where id_cabang = 4");
		}

		if (mysql_num_rows($qcabang) > 0) {
			if ($qcabang_row['centralcbg']=="") {
					while ($qemailto_row = mysql_fetch_array($qemailto)) {							
							$tomail.= $qemailto_row['email'].', ';
					}
			} else {
					while ($qemailto_row = mysql_fetch_array($qemailto)) {
							$tomail.= $qemailto_row['email'].', ';
					}
					while ($qemailtoaa_row = mysql_fetch_array($qemailtoaa)) {
							$tomail.= $qemailtoaa_row['email'].', ';
					}
			}
		} else {
				while ($qemailto_row = mysql_fetch_array($qemailto)) {
						$tomail.= $qemailto_row['email'].', ';
				}
		}
		$ccmail ="asuransi.dmom@gmail.com,asriany1508@gmail.com,asri.nasrani@adonai.co.idrohaida@adonai.co.id,mikha@adonai.co.id";
		echo'
		<table border="0" cellpadding="3" cellspacing="0" width="100%">
			<tr>
				<th colspan="8" width="100%" align="left" colspan="2">Modul Klaim - Rekap Kurang Dokumen</font></th>
				<th><a href="ajk_klaim_mindokumen.php?d=mindokumen"><img src="image/back.png" width="20"></a></th>
			</tr>
		</table><br />
			<form action="ajk_klaim_mindokumen.php?d=sendklaimkurangdokumen" method="POST">
				<input type="hidden" name="cab" value="'.$cab.'">
				<table border="0" cellpadding="3" cellspacing="0" width="100%">
					<tr>
						<td width="20%"></td>
						<td>
						To : '.$tomail.'klaim.adonai.co.id<br>
						CC : '.$ccmail.'<br>
						Subject : [App AJK] Kekurangan Dokumen Klaim '.$cab.' Per '.$today.'<br><br>

						Dear '.$cab.', 
						<br><br>Terlampir Rekap Klaim Kurang Dokumen '.$cab.' Per '.$today.'.
						<br>Mohon Melengkapi dokumen klaim sebelum kadaluarsa (120 Hari setelah debitur meninggal dunia).
						<br><br>Atas Perhatian dan kerjasamanya, kami ucapakan terima kasih.
						<br><br><br>Hormat kami,<br><br><br>'.$q['nm_lengkap'].'<br><br>Telp : 021 8690 9090, Fax : 021 8690 8849
						<br><a href="#">klaim@adonai.co.id</a><br>
						</td>
						<td width="20%"></td>
					</tr>				
					<tr>
						<td colspan="3" align="center">
							<table border="1" cellpadding="3" cellspacing="0" width="100%">
								<tr>
									<td>No</td>
									<td>Cabang</td>
									<td>Mitra</td>									
									<td>Id Peserta</td>
									<td>Nama Debitur</td>
									<td>Tgl Lahir</td>
									<td>Usia</td>
									<td>Plafond</td>
									<td>Tuntutan Klaim</td>
									<td>Tgl Akad</td>
									<td>Tenor</td>
									<td>DOL</td>
									<td>Tgl Terima Laporan</td>
									<td>Kelengkapan Dokumen</td>
									<td>Status</td>
									<td>Kol</td>
									<td>EXP</td>
								</tr>
								';
								$query = "
								SELECT id_cabang,
										id_peserta,
										mitra,
										nama,
										tgl_lahir,
										usia,
										kredit_jumlah,
										tuntutan_klaim,
										kredit_tgl,
										tenor,
										dol,
										tgl_terima_laporan,
										kelengkapan_dokumen,
										status_klaim,
										kol,
										kadaluarsa,
										date_exp
								FROM vmasterklaim 
								WHERE id_klaim_status = 2 ".$where;
								// echo $query;
								$no = 1;
								$result = mysql_query($query);
								while($row = mysql_fetch_array($result)){
									echo '
									<tr>
										<td>'.$no.'</td>
										<td>'.$row['id_cabang'].'</td>
										<td>'.$row['mitra'].'</td>										
										<td>'.$row['id_peserta'].'</td>
										<td>'.$row['nama'].'</td>
										<td>'._convertDate($row['tgl_lahir']).'</td>
										<td>'.$row['usia'].'</td>
										<td>'.duit($row['kredit_jumlah']).'</td>
										<td>'.duit($row['tuntutan_klaim']).'</td>
										<td>'._convertDate($row['kredit_tgl']).'</td>
										<td>'.$row['tenor'].'</td>
										<td>'._convertDate($row['dol']).'</td>
										<td>'.date("d-m-Y H:i:s",strtotime($row['tgl_terima_laporan'])).'</td>
										<td>'.$row['kelengkapan_dokumen'].'</td>
										<td>'.$row['status_klaim'].'</td>
										<td>'.$row['kol'].'</td>
										<td>'.$row['date_exp'].'</td>
									</tr>';
									$no++;
								}								
								echo'
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="3" align="center">
							<button type="submit" class="button" style="text-align:center">Send Mail</button>						
						</td>										
					</tr>
				</table>
			</form>';
		
	break;

	case "sendklaimkurangdokumen" :
		
		include "../includes/Spreadsheet/Excel/Writer.php";
		$tomail = "";
		$cab = $_REQUEST['cab'];
		 

		if($cab=='KNS' or $cab=='KKB' or $cab=='BHAKTI ABADI' or $cab=='KOSPPI' or $cab=='MEKARSARI' or $cab=='KAR' or $cab=='GILANG GEMILANG'){
			$where = ' and regional = "'.$cab.'"';
			$querycabang = "select * from fu_ajk_cabang where id_reg in (select id from fu_ajk_regional where name = '".$cab."' and del is null) and del is null";
		}else{
			$querycabang = "select * from fu_ajk_cabang where name = '".$cab."' and del is null";
			$where = ' and id_cabang ="'.$cab.'"';
		}


		//start send email with attachment
		$mail = new PHPMailer; // call the class
		$mail->IsSMTP();
		$mail->Host = SMTP_HOST; //Hostname of the mail server
		$mail->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
		$mail->Username = SMTP_UNAME; //Username for SMTP authentication any valid email created in your domain
		$mail->Password = SMTP_PWORD; //Password for SMTP authentication
		$mail->AddReplyTo = SMTP_REPLYMAIL; //reply-to address
		$mail->debug = 1;
		$mail->SMTPSecure = "ssl";
		$mail->IsHTML(true);
		$mail->SetFrom('klaim@adonai.co.id', 'Adonai Notification [no reply]');
		$mail->Subject = "[App AJK] Kekurangan Dokumen Klaim ".$cab." Per ".$today; //Subject od your mail
		
		$qcabang = mysql_query($querycabang);
		 
		if (mysql_num_rows($qcabang) > 0) {
			$qcabang_row = mysql_fetch_array($qcabang);
			if ($qcabang_row['id_reg'] == 15 or $qcabang_row['id_reg'] == 24 or $qcabang_row['id_reg'] == 17 or $qcabang_row['id_reg'] == 20 or $qcabang_row['id_reg'] == 19 or $qcabang_row['id_reg'] == 18) {
					$qemailto = mysql_query("select * from fu_ajk_email_cabang where id_cabang = (select name from fu_ajk_regional where id = '".$qcabang_row['id_reg']."')");
					
			} else {
					//cek sentralisasi
					if ($qcabang_row['centralcbg']=="") {							
							$qemailto = mysql_query("select * from fu_ajk_email_cabang where id_cabang = (select id from fu_ajk_cabang where name = '".$cab."' and del is null and id_cost='1')");
					} else {
							$qemailto = mysql_query("select * from fu_ajk_email_cabang where id_cabang = 4");
							$qemailtoaa = mysql_query("select * from fu_ajk_email_cabang where id_cabang = (select id from fu_ajk_cabang where name = '".$cab."' and del is null and id_cost='1')");
					}
			}
		} else {
				$qemailto = mysql_query("select * from fu_ajk_email_cabang where id_cabang = 4");
		}

		$idcabang = $qcabang_row['id'];

		if (mysql_num_rows($qcabang) > 0) {
			if ($qcabang_row['centralcbg']=="") {
					while ($qemailto_row = mysql_fetch_array($qemailto)) {							
							$tomail.= $qemailto_row['email'].', ';
							$mail->AddAddress($qemailto_row['email']);
					}
			} else {
					while ($qemailto_row = mysql_fetch_array($qemailto)) {
							$tomail.= $qemailto_row['email'].', ';
							$mail->AddAddress($qemailto_row['email']);
					}
					while ($qemailtoaa_row = mysql_fetch_array($qemailtoaa)) {
							$tomail.= $qemailtoaa_row['email'].', ';
							$mail->AddAddress($qemailtoaa_row['email']);
					}
			}
		} else {
				while ($qemailto_row = mysql_fetch_array($qemailto)) {
						$tomail.= $qemailto_row['email'].', ';
						$mail->AddAddress($qemailtoaa_row['email']);
				}
		}

		$path = "tmp/";
		$filename = rand().".xls";
		
		$workbook = new Spreadsheet_Excel_Writer($path.$filename);
		$worksheet =& $workbook->addWorksheet($cab);
		$workbook->setTempDir('tmp');
		
		// center the text horizontally
		$format_center =& $workbook->addFormat();
		$format_center->setAlign('center');
		
		$format =& $workbook->addFormat();
		$format->setFgColor('orange');
		$format->setPattern(6);
		$format->setBorder(7);
		
		$format2 =& $workbook->addFormat();
		$format2->setFgColor('yellow');
		$format2->setPattern(6);
		$format2->setBorder(7);
		
		$worksheet->setMerge(0, 0, 0, 16);  
	    $worksheet->write(0, 0, "REKAP KURANG DOKUMEN PER ".strtoupper($today), $format_center);
	    $worksheet->write(2, 0, "NO", $format);
	    $worksheet->write(2, 1, "CABANG", $format);
	    $worksheet->write(2, 2, "MITRA", $format);
	    $worksheet->write(2, 3, "ID PESERTA", $format);
	    $worksheet->write(2, 4, "NAMA DEBITUR", $format);
	    $worksheet->write(2, 5, "TGL LAHIR", $format);
	    $worksheet->write(2, 6, "USIA", $format);
	    $worksheet->write(2, 7, "PLAFOND KREDIT", $format);
	    $worksheet->write(2, 8, "TUNTUTAN KLAIM", $format);
	    $worksheet->write(2, 9, "TGL AKAD", $format);
	    $worksheet->write(2, 10, "JK.WKT", $format);
	    $worksheet->write(2, 11, "DOL", $format);
	    $worksheet->write(2, 12, "TGL TERIMA LAPORAN", $format);
	    $worksheet->write(2, 13, "KELENGKAPAN DOKUMEN", $format);
	    $worksheet->write(2, 14, "STATUS", $format);
	    $worksheet->write(2, 15, "KOL", $format);
	    $worksheet->write(2, 16, "EXP", $format);   

		$query = "
		  SELECT id_cabang,
				  mitra,
				  nama,
				  id_peserta,
				  tgl_lahir,
				  usia,
				  kredit_jumlah,
				  tuntutan_klaim,
				  kredit_tgl,
				  tenor,
				  dol,
				  tgl_terima_laporan,
				  kelengkapan_dokumen,
				  status_klaim,
				  kol,
				  kadaluarsa,
				  date_exp
		  FROM vmasterklaim 
		  WHERE id_klaim_status = 2 ".$where;

		  $result = mysql_query($query);

		  $baris = 3;
		  while ($row = mysql_fetch_array($result)) {
			$worksheet->write($baris, 0, ++$no);
			$worksheet->write($baris, 1, $row['id_cabang']);
			$worksheet->write($baris, 2, $row['mitra']);
			$worksheet->writeString($baris, 3, $row['id_peserta']);
			$worksheet->write($baris, 4, $row['nama']);
			$worksheet->write($baris, 5, $row['tgl_lahir']);
			$worksheet->write($baris, 6, $row['usia']);
			$worksheet->write($baris, 7, $row['kredit_jumlah']);
			$worksheet->write($baris, 8, $row['tuntutan_klaim']);
			$worksheet->write($baris, 9, $row['kredit_tgl']);
			$worksheet->write($baris, 10, $row['tenor']);
			$worksheet->write($baris, 11, $row['dol']);
			$worksheet->write($baris, 12, $row['tgl_terima_laporan']);
			$worksheet->write($baris, 13, $row['kelengkapan_dokumen'], $format2);
			$worksheet->write($baris, 14, $row['status_klaim']);
			$worksheet->write($baris, 15, $row['kol']);
			$worksheet->write($baris, 16, $row['date_exp']);        
			$baris++;
		  }
		  
		$workbook->close();
		 
	  $mail->AddCC("asuransi.dmom@gmail.com");
	  $mail->AddCC("asriany1508@gmail.com");
	  $mail->AddCC("asri.nasrani@adonai.co.id");
	  $mail->AddCC("rohaida@adonai.co.id");
	  $mail->AddCC("mikha@adonai.co.id");
	  $mail->AddCC("klaim@adonai.co.id"); 
	  //$mail->AddCC("heru@adonaits.co.id"); 	  
      $mail->MsgHTML('Dear '.$cab.',
			<br><br>Terlampir Rekap Klaim Kurang Dokumen '.$cab.' Per '.$today.'.
			<br>Mohon Melengkapi dokumen klaim sebelum kadaluarsa (120 Hari setelah debitur meninggal dunia).
			<br><br>Atas Perhatian dan kerjasamanya, kami ucapakan terima kasih.
			<br><br><br>Hormat kami,<br><br><br>'.$q['nm_lengkap'].'<br><br>Telp : 021 8690 9090, Fax : 021 8690 8849
			<br><a href="#">klaim@adonai.co.id</a><br>');  
 
	  $mail->addAttachment($path.$filename);
		$send = $mail->Send(); //Send the mails
		unlink($path.$filename);
		// echo $tomail."_".$cab;
		if ($send) {			
			if($cab=='KNS' or $cab=='KKB' or $cab=='BHAKTI ABADI' or $cab=='KOSPPI' or $cab=='MEKARSARI' or $cab=='KAR' or $cab=='GILANG GEMILANG'){				
				$qv = mysql_query("SELECT DISTINCT id_cabang FROM vmasterklaim WHERE regional = '".$cab."' and id_klaim_status = 2");
				while($qrow = mysql_fetch_array($qv)){
					$query = "INSERT INTO fu_ajk_his_kirim_surat SET surat = 'Pelaporan Kurang Dokumen', tgl_kirim = '".$now."',tomail = '".$tomail."',user='".$q['nm_lengkap']."',keytable='".$qrow['id_cabang']."'";
					$result = mysql_query($query);
				}				
			}else{
				$query = "INSERT INTO fu_ajk_his_kirim_surat SET surat = 'Pelaporan Kurang Dokumen', tgl_kirim = '".$now."',tomail = '".$tomail."',user='".$q['nm_lengkap']."',keytable='".$idcabang."'";
				$result = mysql_query($query);
			}
			
			if($result){
				echo '<center><h3 style="color:#009933;">Mail sent successfully</h3></center> ';
				echo '<meta http-equiv="refresh" content="3;URL=ajk_klaim_mindokumen.php?d=mindokumen">';					
			}else{
				echo '<center><h3 style="color:#FF3300;">Mail error: </h3></center>'.$mail->ErrorInfo;	
			}
		} else {
			echo '<center><h3 style="color:#FF3300;">Mail error: </h3></center>'.$mail->ErrorInfo;
		}   
	  
	break;
}