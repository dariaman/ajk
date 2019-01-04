<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("../includes/functions.php");
connect();

if (session_is_registered('nm_user')) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));	}
echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="100%" align="left">Modul Validasi Pembatalan Peserta</font></th></tr></table>';
switch ($_REQUEST['v']) {
	case 'approve':
$met = $database->doQuery('UPDATE fu_ajk_peserta SET status_peserta="App_Batal", approve_by="'.$q['nm_user'].'", approve_time="'.$futgl.'" WHERE id="'.$_REQUEST['id'].'"');
$databatal = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id="'.$_REQUEST['id'].'"'));
$datadnnya = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn WHERE id="'.$databatal['id_dn'].'"'));

//CEK PERHITUNGAN BATAL PREMI
$tglbatal_ = explode("#", $databatal['ket']);
if (daysBetween($databatal['kredit_tgl'], $tglbatal_[0]) <= 30) {
	$premiBatal = $databatal['totalpremi'];
}else{
	$movementrefund=mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_rasio_claim WHERE id_cost="'.$databatal['id_cost'].'" AND id_polis="'.$databatal['id_polis'].'"'));

	$mets = datediff($databatal['kredit_tgl'], $tglbatal_[0]);
	$pecahtglrefund = explode(",", $mets);
	if ($databatal['type_data']=="SPK") {	$metTenornya = $databatal['kredit_tenor'] * 12 ;	}else{	$metTenornya = $databatal['kredit_tenor'];	}
	$jumbulan = $metTenornya - ($pecahtglrefund[0] * 12 + $pecahtglrefund[1]);
	$biayapenutupan = $databatal['totalpremi'] *  $movementrefund['refund'];
	$premiBatal = $jumbulan / $metTenornya * ($databatal['totalpremi'] - $biayapenutupan);
}
//CEK PERHITUNGAN BATAL PREMI

$metbatalcn = $database->doQuery('INSERT INTO fu_ajk_cn_tempf SET id_cost="'.$databatal['id_cost'].'",
																	id_dn="'.$databatal['id_dn'].'",
												  					id_nopol="'.$databatal['id_polis'].'",
												  					id_peserta="'.$databatal['id_peserta'].'",
												  					id_regional="'.$databatal['regional'].'",
												  					id_cabang="'.$databatal['cabang'].'",
												  					premi="'.$databatal['totalpremi'].'",
												  					total_claim="'.$premiBatal.'",
												  					tgl_claim="'.$futoday.'",
												  					type_claim="Batal",
												  					confirm_claim="Processing",
												  					input_by="'.$databatal['update_by'].'",
												  					input_date="'.$databatal['update_time'].'",
												  					update_by="'.$q['nm_user'].'",
												  					update_time="'.$futgl.'"');
//echo '<br /><br />';
	#	$data['name'];
		/* SMTP MAIL */
		$mail = new PHPMailer;
		$mail->IsSMTP();
		$mail->Host = SMTP_HOST; //Hostname of the mail server
		$mail->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
		$mail->Username = SMTP_UNAME; //Username for SMTP authentication any valid email created in your domain
		$mail->Password = SMTP_PWORD; //Password for SMTP authentication
		$mail->AddReplyTo = SMTP_REPLYMAIL; //reply-to address
		$mail->debug = 1;
		$mail->SMTPSecure = "ssl";
		$mail->IsHTML(true);

		$mail->SetFrom ($q['email'], $q['nm_lengkap']);
		$mail->Subject = "AJKOnline - APPROVE PEMBATALAN PESERTA AJK ONLINE";
		//EMAIL SPV PUSAT
/* REVISI QUERY DATA EMAIL TIDAK KE UW TAPI KE SPV PUSAT 18 11 2015
		$mailsupervisorajk = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="" AND status="UNDERWRITING"');
		while ($_mailsupervisorajk = mysql_fetch_array($mailsupervisorajk)) {
			$mail->AddAddress($_mailsupervisorajk['email'], $_mailsupervisorajk['nm_lengkap']); //To address who will receive this email
		}
*/
		$mail_SPV = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="'.$q['id_cost'].'" AND id_polis="" AND level="6" AND status="SUPERVISOR-ADMIN"');
		while ($mail_SPV_ = mysql_fetch_array($mail_SPV)) {
			$mail->AddAddress($mail_SPV_['email'], $mail_SPV_['nm_lengkap']); //To address who will receive this email
			$mailSPVnya =  $mail_SPV_['nm_lengkap'];
			//echo $mail_SPV_['email'].'<br />';
		}
		//EMAIL SPV PUSAT
		//EMAIL STAFF
		$mailsupervisorclient = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="'.$databatal['id_cost'].'" AND nm_user="'.$databatal['input_by'].'"');
		while ($_mailsupervisorclient = mysql_fetch_array($mailsupervisorclient)) {
			$mail->AddAddress($_mailsupervisorclient['email'], $_mailsupervisorclient['nm_lengkap']); //To address who will receive this email
			//echo $_mailsupervisorclient['email'];
		}
//echo '<br /><br />';
		//EMAIL STAFF

	$message = "Kepada ".$mailSPVnya.", <br /> Pengajuan pembatalan data peserta :
				<table border=0 width=100%>
				<tr><td width=20%>ID Peserta</td><td>".$databatal['id_peserta']."</td></tr>
				<tr><td>Nama Peserta</td><td>".$databatal['nama']."</td></tr>
				<tr><td>Nomor Debit Note</td><td>".$datadnnya['dn_kode']."</td></tr>
				<tr><td>Premi</td><td>".duit($databatal['totalpremi'])."</td></tr>
				<tr><td colspan=2>Data pembatalan peserta telah di setujui oleh ".$q['nm_lengkap']." pada tanggal ".$futgldn.".</td></tr>
				</table>";
	$mail->AddCC("penting_ga@hotmail.com");
	$mail->MsgHTML('Pembatalan peserta AJK telah di approve oleh <b>'.$_SESSION['nm_user'].' pada tanggal '.date('Y-m-d').$message); //Put your body of the message you can place html code here
	$send = $mail->Send(); //Send the mails
//echo $message.'<br />';
echo '<center>Approve data pembatalan peserta oleh <b>'.$_SESSION['nm_user'].'</b> telah berhasil.<br /><a href="ajk_val_batal.php">Kembali Ke Halaman Utama</a></center>';
		;
	break;
	case 'cancel':
		$met = $database->doQuery('UPDATE fu_ajk_peserta SET status_peserta = NULL WHERE id="'.$_REQUEST['id'].'"');
		header("location:ajk_val_batal.php");
		;
	break;

	case 'cancelSPV':
		$metTempf_ = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cn_tempf WHERE id="'.$_REQUEST['id'].'"'));
		$metTempf = $database->doQuery('UPDATE fu_ajk_cn_tempf SET confirm_claim="Rejected", update_by="'.$q['nm_lengkap'].'", update_time="'.$futgl.'" WHERE id="'.$_REQUEST['id'].'"');
		$metPeserta = $database->doQuery('UPDATE fu_ajk_peserta SET status_peserta = NULL WHERE id_peserta="'.$metTempf_['id_peserta'].'"');
		header("location:ajk_val_batal.php");
		;
	break;

	case "val_app_refSPV":
if (!$_REQUEST['nameRef']) {
echo '<center><font color=red><blink>Tidak ada data Pembatalan Peserta yang di pilih, silahkan ceklist data yang akan di batalkan. !</blink></font><br/>
	  <a href="ajk_val_batal.php">Kembali Ke Halaman Validasi Pembatalan</a></center>';
}else{
	foreach($_REQUEST['nameRef'] as $r => $Ref_){
		$metRefnya = $database->doQuery('UPDATE fu_ajk_cn_tempf SET confirm_claim="Approve", validasi_cn_uw="ya" WHERE id="'.$Ref_.'"');
		//	INSERT KE TABLE CN
		$metRefnya = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cn_tempf WHERE id="'.$Ref_.'"'));
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
	foreach($_REQUEST['nameRef'] as $r1 => $Ref1_){
		$metRefnya = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cn_tempf WHERE id="'.$Ref1_.'"'));
		$met_mail_dn = mysql_fetch_array($database->doQuery('SELECT id, dn_kode FROM fu_ajk_dn WHERE id="'.$metRefnya['id_dn'].'"'));
		$met_mail_peserta = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_cost="'.$metRefnya['id_cost'].'" AND id_polis="'.$metRefnya['id_nopol'].'" AND id_peserta="'.$metRefnya['id_peserta'].'"'));
		$nettrefund = $met_mail_peserta['totalpremi'] - $metRefnya['total_claim'];

		if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
		$metRefnya_send .='	<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
							<td width="1%" align="center">'.++$no.'</td>
							<td width="10%" align="center">'.$met_mail_dn['dn_kode'].'</td>
							<td width="8%" align="center">'.$metRefnya['id_peserta'].'</td>
							<td>'.$met_mail_peserta['nama'].'</td>
							<td width="7%" align="center">'._convertDate($met_mail_peserta['tgl_lahir']).'</td>
							<td width="1%" align="center">'.$met_mail_peserta['usia'].'</td>
							<td width="6%" align="center">'._convertDate($met_mail_peserta['kredit_tgl']).'</td>
							<td width="1%" align="center">'.$met_mail_peserta['kredit_tenor'].'</td>
							<td width="6%" align="center">'._convertDate($met_mail_peserta['kredit_akhir']).'</td>
							<td width="7%" align="right">'.duit($met_mail_peserta['kredit_jumlah']).'</td>
							<td width="7%" align="right">'.duit($met_mail_peserta['totalpremi']).'</td>
							<td width="6%" align="center">'._convertDate($metRefnya['tgl_claim']).'</td>
							<td width="6%" align="right">'.duit($metRefnya['total_claim']).'</td>
							<td width="5%" align="center">'.$metRefnya['type_claim'].'</td>
							<td width="5%" align="center">'.$metRefnya['id_cabang'].'</td>
							</tr>';
		//$met_del_ref = mysql_fetch_array($database->doQuery('DELETE FROM fu_ajk_cn_tempf WHERE id="'.$Ref1_.'"'));	//PADA SAAT INDERT KE CN TABLE CNTEMPF DIHAPUS
	}
	//$message .='To Underwriting,<br />Terlampir data-data Refund Peserta pada table di bawah ini :	 revisi (18-11-2015)
	$message .='Kepada Underwriting,<br />Terlampir data-data Pembatalan Peserta pada table di bawah ini :
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
	$message .='</table>';
	$message .='Data Pembatalan peserta telah di Approve oleh '.$q['nm_lengkap'].' pada tanggal '.$futgldn.' '.$timelog.'<br /><br />Salam,<br />'.$q['nm_lengkap'].'';
	//echo $message;
	//SMTP UNDERWRITING
	$mail = new PHPMailer; // call the class
	$mail->IsSMTP();
	$mail->Host = SMTP_HOST; //Hostname of the mail server
	$mail->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
/* PROSES SEBELUMNYA SETELAH DI APPROVE SPV EMAIL KE BAGIAN UW DIRUBAH KE SPV PUSAT DULU BARU KE UW (18-11-2015)
   $mail_uw = $database->doQuery('SELECT * FROM pengguna WHERE status="UNDERWRITING"');
   while ($mail_uw_ = mysql_fetch_array($mail_uw)) {
   $mail->AddAddress($mail_uw_['email'], $mail_uw_['nm_lengkap']); //To address who will receive this email
   }
*/
	$mail_UW = $database->doQuery('SELECT * FROM pengguna WHERE status="UNDERWRITING"');
	while ($mail_UW_ = mysql_fetch_array($mail_UW)) {
		$mail->AddAddress($mail_UW_['email'], $mail_UW_['nm_lengkap']); //To address who will receive this email
		//echo $mail_UW_['email'].'<br />';
	}

	$mail_staff = $database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$metRefnya['input_by'].'"');
	while ($mail_staff_ = mysql_fetch_array($mail_staff)) {
		$mail->AddAddress($mail_staff_['email'], $mail_staff_['nm_lengkap']); //To address who will receive this email
		//echo $mail_staff_['email'].'<br />';
	}
	$mail->SetFrom ($q['email'], $q['nm_lengkap']); //From address of the mail
	$mail->Subject = "AJKOnline - APPROVE DATA BATAL"; //Subject od your mail
	$mail->AddCC("kepodank@gmail.com");
	$mail->MsgHTML($message); //Put your body of the message you can place html code here
	$send = $mail->Send(); //Send the mails
	//SMTP UNDERWRITING
	//echo $message.'<br />';
echo '<div class="title2" align="center">Data Pembatalan telah di Approve oleh '.$q['nm_lengkap'].' pada tanggal '.$futgldn.' '.$timelog.'.</div>
	  <meta http-equiv="refresh" content="3;URL=ajk_val_batal.php">';
}
		;
		break;

	default:
// APPROVE DATA OLEH SPV PUSAT
if ($q['status']=="SUPERVISOR-ADMIN") {
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
			<th width="6%">Tgl Batal</th>
			<th width="6%">Nilai Refund</th>
			<th width="5%">Status</th>
			<th width="5%">Cabang</th>
			<th width="5%">User</th>
			</tr>';
	$met_cn = $database->doQuery('SELECT * FROM fu_ajk_cn_tempf WHERE id_cost='.$q['id_cost'].' '.$_produk.' AND confirm_claim="Processing" AND type_claim="Batal" '.$_userRef.' AND del IS NULL ORDER BY id DESC');
	$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_cn_tempf WHERE id !="" AND id_cost="'.$q['id_cost'].'" '.$_produk.' AND confirm_claim="Processing" AND type_claim="Batal" '.$_userRef.' AND del IS NULL'));
	$totalRows = $totalRows[0];
	$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while ($met_cn_ = mysql_fetch_array($met_cn)) {
	$met_dn = mysql_fetch_array($database->doQuery('SELECT id, dn_kode FROM fu_ajk_dn WHERE id="'.$met_cn_['id_dn'].'"'));
	$met_peserta = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_cost="'.$met_cn_['id_cost'].'" AND id_polis="'.$met_cn_['id_nopol'].'" AND id_peserta="'.$met_cn_['id_peserta'].'"'));

if ($q['id_cost']==$met_cn_['id_cost'] AND $q['id_polis']=="") {
	$dataceklist = '<input type="checkbox" class="case" name="nameRef[]" value="'.$met_cn_['id'].'">';
}else{	$dataceklist = '';	}

if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
			<td align="center"><a href="ajk_val_batal.php?v=cancelSPV&id='.$met_cn_['id'].'" onClick="if(confirm(\'Apakah anda yakin untuk membatalkan data peserta refund ini ?\')){return true;}{return false;}"><img src="image/delete.gif"></a></td>
		    <td align="center">'.$dataceklist.'</td>
			<td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
			<td align="center"><a href="aajk_report.php?er=_kwipeserta&idn='.$met_cn_['id_dn'].'" target="_blank">'.$met_dn['dn_kode'].'</a></td>
			<td align="center">'.$met_cn_['id_peserta'].'</td>
			<td><a title="lihat dokumen refund" href="ajk_file/_spak/'.$met_cn_['fname'].'" target="_blank">'.$met_peserta['nama'].'</a></td>
			<td align="center">'._convertDate($met_peserta['tgl_lahir']).'</td>
			<td align="center">'.$met_peserta['usia'].'</td>
			<td align="center">'._convertDate($met_peserta['kredit_tgl']).'</td>
			<td align="center">'.$met_peserta['kredit_tenor'].'</td>
			<td align="center">'._convertDate($met_peserta['kredit_akhir']).'</td>
			<td align="right">'.duit($met_peserta['kredit_jumlah']).'</td>
			<td align="right"><b>'.duit($met_peserta['totalpremi']).'<b></td>
			<td align="center">'._convertDate($met_cn_['tgl_claim']).'</td>
			<td align="right"><font color="red">'.duit($met_cn_['total_claim']).'</font></td>
			<td align="center">'.$met_cn_['type_claim'].'</td>
			<td align="center">'.$met_cn_['id_cabang'].'</td>
			<td align="center">'.$met_cn_['input_by'].'</td>
		  </tr>';
}
echo '<tr><td colspan="20" align="center"><a href="#" onClick="if(confirm(\'Apakah anda sudah yakin dengan semua data peserta refund ini ?\')){return true;}{return false;}"><input type="hidden" name="v" Value="val_app_refSPV"><input type="submit" name="ve" Value="Approve">
	  </td></tr>
	  </table>';
} // APPROVE DATA OLEH SPV PUSAT
else{
echo '<form method="post" action="ajk_val_batal.php" onload ="onbeforeunload">
	<table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#bde0e6">
	<tr><th width="1%">Option</th>
		<th width="1%">No</th>
		<th width="5%">ID Peserta</th>
		<th>Nama Debitur</th>
		<th width="5%">Tanggal Lahir</th>
		<th width="1%">Usia</th>
		<th width="5%">Uang Asuransi</th>
		<th width="5%">Mulai Asuransi</th>
		<th width="1%">Tenor</th>
		<th width="1%">Nett Premi</th>
		<th width="1%">Tgl Batal</th>
		<th width="10%">Cabang</th>
		<th width="5%">User</th>
		<th width="5%">Tanggal</th>
	</tr>';
$data = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE status_peserta="Req_Batal"');
while ($metspk_ = mysql_fetch_array($data)) {
if ($q['id_cost'] !="" AND $q['id_polis']=="" AND $q['level']=="6") {
	$metreqbtl = '<a title="Menyetujui data pembatalan peserta" href="ajk_val_batal.php?v=approve&id='.$metspk_['id'].'" onClick="if(confirm(\'Apakah anda yakin untuk approve pembatalan peserta ini ?\')){return true;}{return false;}"><img src="image/ya.png" width="15"></a>';
}else{
	$metreqbtl ='';
}
$dataceklist = '<input type="checkbox" class="case" name="nama[]" value="'.$fudata['id_cost'].'-met-'.$fudata['id_polis'].'-met-'.$fudata['nama'].'-met-'.$fudata['tgl_lahir'].'-met-'.$fudata['kredit_jumlah'].'">';


if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
$tglRegbtl = explode(" ", $metspk_['update_time']);
$tglDatabtl = explode("#", $metspk_['ket']);
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		<td align="center" width="5%"><a  title="Batalkan data pembatalan peserta" href="ajk_val_batal.php?v=cancel&id='.$metspk_['id'].'" onClick="if(confirm(\'Apakah anda yakin untuk cancel pembatalan peserta ini ?\')){return true;}{return false;}"><img src="image/delete.gif"></a>&nbsp;&nbsp;'.$metreqbtl.'</td>
		<td align="center">'.++$no .'</td>
		<td align="center">'.$metspk_['id_peserta'].'</td>
		<td>'.$metspk_['nama'].'</td>
		<td align="center">'._convertDate($metspk_['tgl_lahir']).'</td>
		<td align="center">'.$metspk_['usia'].'</td>
		<td align="right">'.duit($metspk_['kredit_jumlah']).'</td>
		<td align="center">'._convertDate($metspk_['kredit_tgl']).'</td>
		<td align="center">'.$metspk_['kredit_tenor'].'</td>
		<td align="right">'.duit($metspk_['totalpremi']).'</td>
		<td align="center"><b>'._convertDate($tglDatabtl[0]).'</b></td>
		<td align="center">'.$metspk_['cabang'].'</td>
		<td align="center"><font color="blue"><b>'.$metspk_['update_by'].'</b></font></td>
		<td align="center">'._convertDate($tglRegbtl[0]).'</td>
	</tr>';
}
echo '</table></form>';
}
	;
} // switch
/*
echo '<!--WILAYAH COMBOBOX-->
<script src="javascript/metcombo/prototype.js"></script>
<script src="javascript/metcombo/dynamicombo.js"></script>
<!--WILAYAH COMBOBOX-->
<script>
document.observe("dom:loaded",function(){
	new DynamiCombo( "id_cost" , {
		elements:{
			"id_polis":		{url:\'javascript/metcombo/data.php?req=setpoliscostumer\', value:\'id\', label:\'name\', init:\'<?php echo $_POST["id_polis"] ?>\'},
		},
		loadingImage:\'loader1.gif\',
		loadingText:\'Loading...\',
		debug:0
	} )
});
</script>';
*/
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