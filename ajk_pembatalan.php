<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ----------------------------------------------------------------------------------
include_once("ui.php");
include_once("includes/functions.php");
echo "<script language=\"JavaScript\" src=\"javascript/js/form_validation.js\"></script>";
connect();
$futgl = date("Y-m-d H:i:s");
if (session_is_registered('nm_user')) {
    $q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));
}
if ($q['level']=="99") {
    $typedata = 'AND type_data="SPK"';
} else {
    $typedata = 'AND type_data!="SPK"';
}
switch ($_REQUEST['er']) {
    case "reqbatal":
echo '<table border="0" cellpadding="5" cellspacing="1" width="100%">
	  <tr><th width="100%" align="left">Modul Pembatalan Peserta</font></th>
	  	  <th width="5%"><a href="ajk_pembatalan.php"><img src="image/Backward-64.png" width="20"></a></th>
	  </tr>
	  </table>';
$metBatal = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id="'.$_REQUEST['id'].'"'));
$metBatalDN = mysql_fetch_array($database->doQuery('SELECT id, dn_kode FROM fu_ajk_dn WHERE id="' . $metBatal['id_dn'] . '"'));
if ($_REQUEST['setbatal']=="Simpan") {
    if ($_REQUEST['tglbatalnya']=="") {
        $error1 = '<font color="red">Tanggal pembatalan tidak boleh kosong</font>';
    }
    if ($_REQUEST['alasanbatal']=="") {
        $error2 = '<font color="red">Silahkan isi alasan pembatalan</font>';
    }
    if ($error1 or $error2) {
    } else {
        $ketBatal = $_REQUEST['tglbatalnya'].'#'.$_REQUEST['alasanbatal'];
        $met = $database->doQuery('UPDATE fu_ajk_peserta SET status_peserta="Req_Batal", ket="'.$ketBatal.'", update_by="'.$q['nm_user'].'", update_time="'.$futgl.'" WHERE id="'.$_REQUEST['id'].'"');
        $met_tempCN = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id="'.$_REQUEST['id'].'"'));

        $mail_batal_spv = mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE id_cost="'.$met_tempCN['id_cost'].'" AND id_polis="" AND level="6" AND del IS NULL'));
        $message .='To '.$mail_batal_spv['nm_lengkap'].',<br /><br />Data pembatalan debitur peserta atas nama '.$met_tempCN['nama'].' telah dibatalkan pengajuan data kepesertaannya oleh '.$met_tempCN['input_by'].' pada tanggal '.$futgldn.'.<br /><br />Terimakasih, <br />'.$met_tempCN['input_by'].'.';
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

        $mailclient = $database->doQuery('SELECT * FROM fu_ajk_mail WHERE type="Batal" AND status="Aktif"');
        while ($_mailclient = mysql_fetch_array($mailclient)) {
            $mail->AddAddress($_mailclient['emailto'], $_mailclient['emailnama']); //To address who will receive this email
        }

        $mail->SetFrom($q['email'], $q['nm_lengkap']); //From address of the mail
    $mail->Subject = "AJKOnline - BATAL DATA PESERTA"; //Subject od your mail

    $mail->AddBCC("adn.info.notif@gmail.com");
        // $mail->AddCC("rahmad@adonaits.co.id");

        $mail->MsgHTML($message); //Put your body of the message you can place html code here
    $send = $mail->Send(); //Send the mails
    //echo $message.'<br />';
echo '<div align="center">Request data pembatalan telah di buat oleh '.$q['nm_user'].' pada tanggal '.$futgldn.'.</div><meta http-equiv="refresh" content="2; url=ajk_pembatalan.php">';
    }
}
echo '<form method="post" action="">
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
		<th width="1%">EM(%)</th>
		<th width="1%">Nilai EM</th>
		<th width="5%">T.Premi</th>
		<th width="5%">Cabang</th>
		</tr>
	<tr>
    	<td align="center">' . $metBatalDN['dn_kode'] . '</td>
    	<td align="center">' . $metBatal['id_peserta'] . '</td>
    	<td>' . $metBatal['nama'] . '</td>
    	<td align="center">' . _convertDate($metBatal['tgl_lahir']) . '</td>
    	<td align="center">' . $metBatal['usia'] . '</td>
    	<td align="right">' . duit($metBatal['kredit_jumlah']) . '</td>
    	<td align="center">' . _convertDate($metBatal['kredit_tgl']) . '</td>
    	<td align="center">' . $metBatal['kredit_tenor'] . '</td>
    	<td align="center">' . _convertDate($metBatal['kredit_akhir']) . '</td>
    	<td align="right">' . duit($metBatal['premi']) . '</td>
    	<td align="center">' . $metBatal['ext_premi'] . '</td>
    	<td align="right">' . duit($metBatal['ext_premi']) . '</td>
    	<td align="right">' . duit($metBatal['totalpremi']) . '</td>
    	<td align="center">' . $metBatal['cabang'] . '</td>
   	</tr>
	</table>
	  <table border="0" width="100%">
	  <!--<tr><td width="50%" align="right">Nama Debitur :</td><td> '.$metBatal['nama'].'</td></tr>-->
	  <tr><td colspan="2" align="center"><a href="aajk_report.php?er=formBtlRef&r='.$_REQUEST['r'].'&deb='.$_REQUEST['id'].'" target="blank">Download File Pembatalan</a></td></tr>
	  <tr><td width="10%">Tanggal Pembatalan :</td><td> ';print initCalendar();	print calendarBox('tglbatalnya', 'triger1', $futoday); echo ' '.$error1.'</td></tr>
	  <tr><td width="10%" valign="top">Alasan Pembatalan <font color="red">*</font>:</td><td><textarea name="alasanbatal" rows="2" cols="50">'.$_REQUEST['alasanbatal'].'</textarea>'.$error2.'
	  <tr><td colspan="2"><input type="submit" name="setbatal" value="Simpan" class="button" /></td></tr>
	  </table></form>';
    ;
    break;

default:
echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="100%" align="left">Modul Pembatalan Peserta</font></th></tr></table>';
echo '<fieldset style="padding: 2">
		<legend align="center">S e a r c h</legend>
		<table border="0" width="100%" cellpadding="1" cellspacing="1">
			<form method="post" action="">
		<tr><td width="20%" align="right">Nama Perusahaan :</td>
			  <td width="30%">';
$quer2=mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$q['id_cost'].'"'));
echo  $quer2['name'];
echo '</td></tr>
<!--<tr><td align="right">Nama Produk :</td>
		<td> --> ';
//$quer1=mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$q['id_polis'].'"'));
//echo $quer1['nmproduk'].' ('.$quer1['nopol'].')';
//echo '</td></tr>';
echo '<tr><td align="right">Nomor DN :</td><td><input type="text" name="metdn" value="'.$_REQUEST['metdn'].'"></td></tr>
	  <tr><td align="right">Nomor Peserta :</td><td><input type="text" name="no_peserta" value="'.$_REQUEST['no_peserta'].'"></td></tr>
	  <tr><td align="right">Nama Peserta :</td><td><input type="text" name="nama_peserta" value="'.$_REQUEST['nama_peserta'].'"></td></tr>
	  <tr><td align="center" colspan="2"><input type="hidden" name="re" value="datapeserta"><input type="submit" name="button" value="Cari" class="button"></td></tr>
	  </form></table></fieldset>';

if ($_REQUEST['tgl']!='' and $_REQUEST['tgl2']!='') {
    $satu='AND kredit_tgl BETWEEN \''.$_REQUEST['tgl'].'\' AND \''.$_REQUEST['tgl2'].'\'';
}
if ($_REQUEST['nama_peserta']) {
    $dua = 'AND nama LIKE "%' . $_REQUEST['nama_peserta'] . '%"';
}
if ($_REQUEST['no_peserta']) {
    $tiga = 'AND id_peserta LIKE "%' . $_REQUEST['no_peserta'] . '%"';
}
//if ($_REQUEST['sdob'])		{	$empat = 'AND tgl_lahir LIKE "%' . $_REQUEST['sdob'] . '%"';		}
if ($_REQUEST['metdn']) {
    $metcekdn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn WHERE dn_kode LIKE "%' . $_REQUEST['metdn'] . '%"'));
    $empat = 'AND id_dn = "' . $metcekdn['id'] . '"';
}

if ($_REQUEST['re']=="datapeserta") {
    echo '<form method="post" action="ajk_pembatalan.php">
		<table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#bde0e6">
		<tr><th width="1%"></th>
			<th width="1%">No</th>
			<th width="5%">Mitra</th>
			<th width="5%">Produk</th>
			<th width="5%">Debitnote</th>
			<th width="5%">ID Pesertta</th>
			<th>Debitur</th>
			<th>Pasangan</th>
			<th width="8%">Tanggal Lahir</th>
			<th width="1%">Usia</th>
			<th width="8%">Plafond</th>
			<th width="8%">Tanggal Akad</th>
			<th width="1%">Tenor</th>
			<th width="8%">Tanggal Akhir</th>
			<th width="1%">Total Premi</th>
			<th width="5%">Cabang</th>
			<th width="5%">User</th>
			</tr>';
    //$data = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id !="" AND id_cost="'.$q['id_cost'].'" AND id_polis="'.$q['id_polis'].'" ' . $satu . ' '.$dua.' '.$tiga.' '.$empat.' AND status_aktif="Inforce" AND del IS NULL ORDER BY input_by ASC LIMIT '.$m.', 25');
    if ($_REQUEST['x']) {
        $m = ($_REQUEST['x']-1) * 25;
    } else {
        $m = 0;
    }
    $data = $database->doQuery('SELECT *, IF(type_data="SPK", kredit_tenor * 12, kredit_tenor) AS kTenor FROM fu_ajk_peserta WHERE id !="" AND id_cost="'.$q['id_cost'].'" AND input_by="'.$q['nm_user'].'" ' . $satu . ' '.$dua.' '.$tiga.' '.$empat.' AND status_aktif="Inforce" AND ifnull(status_bayar,0)="0" AND del IS NULL ORDER BY input_by ASC LIMIT '.$m.', 25');
    #	echo 'SELECT * FROM fu_ajk_peserta WHERE nama !="" AND id_cost="'.$q['id_cost'].'" AND id_polis="'.$q['id_polis'].'" ' . $satu . ' '.$dua.' '.$tiga.' '.$empat.'  AND del IS NULL ORDER BY input_by ASC LIMIT '.$m.', 25';exit;
    $totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_peserta WHERE id !="" AND id_cost="'.$q['id_cost'].'" AND input_by="'.$q['nm_user'].'" AND status_aktif="Inforce" ' . $satu . ' '.$dua.' '.$tiga.' '.$empat.' AND status_bayar="0" AND del IS NULL'));
    $totalRows = $totalRows[0];
    $_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
    while ($fudata = mysql_fetch_array($data)) {
        if ($fudata['id_polis']!=19) {
            $querypasangan = "SELECT id_peserta,status_aktif
							FROM fu_ajk_peserta
							WHERE spaj = (SELECT spak
														FROM fu_ajk_spak_form
																 INNER JOIN fu_ajk_spak
																 ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
														WHERE nolink = (SELECT id
																						FROM fu_ajk_spak
																						WHERE spak = '".$fudata['spaj']."') and
																	id_polis = 19 and
																	fu_ajk_spak_form.del is NULL and
																	fu_ajk_spak.del is NULL)";
            $qpasangan = mysql_fetch_array(mysql_query($querypasangan));
            $status = $qpasangan['status_aktif'];
            if ($status == "Inforce") {
                $pasangan = '<a target="_blank" href="ajk_pembatalan.php?re=datapeserta&no_peserta='.$qpasangan['id_peserta'].'">'.$qpasangan['id_peserta'].'</a>';
            } else {
                $pasangan = $qpasangan['id_peserta'];
            }
        } else {
            $query = "SELECT id_peserta
							FROM fu_ajk_peserta
							WHERE spaj = (SELECT spak
														FROM fu_ajk_spak
														WHERE id = (SELECT nolink
																				FROM fu_ajk_spak
																						 INNER JOIN fu_ajk_spak_form
																						 ON fu_ajk_spak_form.idspk = fu_ajk_spak.id
																				WHERE fu_ajk_spak.spak = '".$fudata['spaj']."'
																				LIMIT 1))";
            $qpasangan = mysql_fetch_array(mysql_query($query));
            $pasangan = $qpasangan['id_peserta'];
        }
        $queryproduk = "SELECT * FROM fu_ajk_polis WHERE id = '".$fudata['id_polis']."'";
        $produk = mysql_fetch_array(mysql_query($queryproduk));
        //	$umur = ceil(((strtotime($fudata['kredit_tgl']) - strtotime($fudata['tgl_lahir']."+6 month")) / (60*60*24*365.2425)));	// FORMULA USIA
        //	$umur = ceil(((strtotime($fudata['kredit_tgl']) - strtotime($fudata['tgl_lahir'])) / (60*60*24*365.2425)));	// FORMULA USIA
        $quer3=mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_grupproduk WHERE id="'.$fudata['nama_mitra'].'"'));
        if ($fudata['status_peserta'] == "") {
            //if ($status != "Inforce") {
                $datacn = '<a href="ajk_pembatalan.php?er=reqbatal&id='.$fudata['id'].'" onClick="if(confirm(\'Apakah anda yakin untuk meminta pembatalan peserta ini ?\')){return true;}{return false;}"><img src="image/delete.gif"></a>';
            // } else {
            //     $datacn = "";
            // }
        } else {
            $metCnPeserta = mysql_fetch_array($database->doQuery('SELECT id, id_cn FROM fu_ajk_cn WHERE id="'.$fudata['id_klaim'].'"'));
            $datacn = '';
        }
        $metDN = mysql_fetch_array($database->doQuery('SELECT id, dn_kode FROM fu_ajk_dn WHERE id ="'.$fudata['id_dn'].'"'));
        $dataceklist = '<input type="checkbox" class="case" name="nama[]" value="'.$fudata['id'].'">';
        if (($no % 2) == 1) {
            $objlass = 'tbl-odd';
        } else {
            $objlass = 'tbl-even';
        }
        echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		  <td align="center">'.$datacn.'</td>
		  <td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
		  <td>'.$quer3['nmproduk'].'</td>
		  <td>'.$produk['nmproduk'].'</td>
		  <td align="center">'.$metDN['dn_kode'].'</td>
		  <td>'.$fudata['id_peserta'].'</td>
		  <td>'.$fudata['nama'].' '.$metCnPeserta['id_cn'].'</td>
		  <td align="center">'.$pasangan.'</td>
		  <td align="center">'._convertDate($fudata['tgl_lahir']).'</td>
		  <td align="center">'.$fudata['usia'].'</td>
		  <td align="right">'.duit($fudata['kredit_jumlah']).'</td>
		  <td align="center">'._convertDate($fudata['kredit_tgl']).'</td>
		  <td align="center">'.$fudata['kTenor'].'</td>
		  <td align="center">'._convertDate($fudata['kredit_akhir']).'</td>
		  <td align="right">'.duit($fudata['totalpremi']).'</td>
		  <td align="center">'.$fudata['cabang'].'</td>
		  <td align="center"><font color="blue"><b>'.$fudata['input_by'].'</b></font></td>
	</tr>';
    }
    echo '<tr><td colspan="22">';
    echo createPageNavigations($file = 'ajk_pembatalan.php?', $total = $totalRows, $psDeh = 10, $anchor = '', $perPage = 25);
    echo '<b>Total Data Peserta: <u>' . $totalRows . '</u></b></td></tr>';
    echo '</table>';
}
    ;
} // switch

?>
<SCRIPT language=JavaScript>
function reload(form)
{
	var val=form.cat.options[form.cat.options.selectedIndex].value;
	self.location='ajk_nota.php?er=dn&cat=' + val;
}
</script>
