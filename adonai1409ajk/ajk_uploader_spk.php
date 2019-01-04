<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("../includes/functions.php");
connect();

if (session_is_registered('nm_user')) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));
	$qsescost=mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$q['id_cost'].'"'));
}
switch ($_REQUEST['r']) {
case "approve":
if (!$_REQUEST['nama']) {
echo '<center><font color=red><blink>Tidak ada data yang di pilih, silahkan ceklist data yang akan di Approve. !</blink></font><br/>
	  <a href="ajk_uploader_spk.php?r=viewall">Kembali Ke Halaman Approve Peserta</a></center>';
}else{
foreach($_REQUEST['nama'] as $k => $val){
	$vall = explode("-met-", $val);		//EXPLODE DATA BERDASARKAN CHEKLIST//
	$r = $database->doQuery('SELECT * FROM fu_ajk_peserta_tempf WHERE id_cost="'.$vall[0].'" AND id_polis="'.$vall[1].'" AND nama="'.$vall[2].'" AND tgl_lahir="'.$vall[3].'" AND kredit_jumlah="'.$vall[4].'" AND status_aktif="Upload"');
while ($rr = mysql_fetch_array($r)) {
	//BIAYA POLIS ADMIN
	$admpolis = mysql_fetch_array($database->doQuery('SELECT id_cost, adminfee, day_kredit, discount, singlerate FROM fu_ajk_polis WHERE id_cost="'.$rr['id_cost'].'" AND id="'.$rr['id_polis'].'"'));

	$tgl_akhir_kredit = date('Y-m-d',strtotime($rr['kredit_tgl']."+".$rr['kredit_tenor']." Month"."-".$admpolis['day_kredit']." day"));	//KREDIT AKHIR
	$umur = ceil(((strtotime($rr['kredit_tgl']) - strtotime($rr['tgl_lahir']."+6 month")) / (60*60*24*365.2425)));	// FORMULA USIA

	//MENENTUKAN PERHITUNGAN PREMI BERDASARKAN SINGLE RATE PADA POLIS
	if ($admpolis['singlerate']=="T") {
		$cekrate = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$rr['id_cost'].'" AND id_polis="'.$rr['id_polis'].'" AND tenor="'.$rr['kredit_tenor'].'"'));		// RATE PREMI
	}else{
		$mettenornya = $rr['kredit_tenor'] / 12;
		$cekrate = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$rr['id_cost'].'" AND id_polis="'.$rr['id_polis'].'" AND usia="'.$umur.'" AND tenor="'.$mettenornya.'"'));		// RATE PREMI
	}
	//MENENTUKAN PERHITUNGAN PREMI BERDASARKAN SINGLE RATE PADA POLIS
	$premi = $rr['kredit_jumlah'] * $cekrate['rate'] / 1000;
	$diskonpremi = $premi * $admpolis['discount'] /100;			//diskon premi
	$tpremi = $premi - $diskonpremi;								//totalpremi

	$cek_extpremi = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak WHERE id_cost="'.$rr['id_cost'].'" AND spak="'.$rr['spaj'].'"'));
	$extrapremi = $tpremi * $cek_extpremi['ext_premi'] / 100;

	$mettotal = $tpremi + $extrapremi + $admpolis['adminfee'];															//TOTAL

	$formattgl = explode("/", $rr['kredit_tgl']);		$formattgl1 = $formattgl[2].'-'.$formattgl[1].'-'.$formattgl[0];		// SET FORMAT TANGGAL
	$cekpesertaID = mysql_fetch_array($database->doQuery('SELECT id FROM fu_ajk_peserta ORDER BY id DESC'));					// SET ID PESERTA
	$idnya = 100000000 + $cekpesertaID['id'] + 1; $idnya2 = substr($idnya, 1);													// SET ID PESERTA
$mameto = $database->doQuery('INSERT INTO fu_ajk_peserta SET id_cost="'.$rr['id_cost'].'",
															 id_polis="'.$rr['id_polis'].'",
															 namafile="'.$rr['namafile'].'",
															 no_urut="'.$rr['no_urut'].'",
															 spaj="'.$rr['spaj'].'",
															 type_data="'.$rr['type_data'].'",
															 id_peserta="'.$idnya.'",
															 nama_mitra="'.$rr['nama_mitra'].'",
															 nama="'.$rr['nama'].'",
															 gender="'.$rr['gender'].'",
															 tgl_lahir="'.$rr['tgl_lahir'].'",
															 usia="'.$umur.'",
															 kredit_tgl="'.$rr['kredit_tgl'].'",
															 kredit_jumlah="'.$rr['kredit_jumlah'].'",
															 kredit_tenor="'.$rr['kredit_tenor'].'",
															 kredit_akhir="'.$tgl_akhir_kredit.'",
															 premi="'.$premi.'",
															 disc_premi="'.$diskonpremi.'",
															 bunga="",
															 biaya_adm="'.$admpolis['adminfee'].'",
															 ext_premi="'.$extrapremi.'",
															 totalpremi="'.$mettotal.'",
															 badant="",
															 badanb="",
															 status_medik="NM",
															 status_bayar="0",
															 status_aktif="Approve",
															 regional="'.$rr['regional'].'",
															 area="'.$rr['area'].'",
															 cabang="'.$rr['cabang'].'",
															 input_by ="'.$rr['input_by'].'- '.$q['nm_user'].'",
															 input_time ="'.$rr['input_time'].'"');

}
	$metdel = $database->doQuery('DELETE FROM fu_ajk_peserta_tempf WHERE id_cost="'.$vall[0].'" AND id_polis="'.$vall[1].'" AND nama="'.$vall[2].'" AND tgl_lahir="'.$vall[3].'" AND kredit_jumlah="'.$vall[4].'" AND status_aktif ="Upload"');
}


	$pecahtgl = explode(" ", $futgl);	$pecahlagi = explode("-", $pecahtgl[0]);	$tglnya = $pecahlagi[2].'-'.$pecahlagi[1].'-'.$pecahlagi[0].' '.$pecahtgl[1];				//PECAH TANGGAL
	//$Rmail = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="'.$vall[0].'" AND  wilayah="'.$q['wilayah'].'" AND email !=""');
	//echo('SELECT * FROM pengguna WHERE id_cost="'.$vall[0].'" AND  wilayah="'.$q['wilayah'].'" AND email !=""');
	//while ($eRmail = mysql_fetch_array($Rmail)) {	$metMail .=$eRmail['email'].', ';	}
	//echo $metMail.'<br /><br />';

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
	//$mail->Username = SMTP_UNAME; //Username for SMTP authentication any valid email created in your domain
	//$mail->Password = SMTP_PWORD; //Password for SMTP authentication
	//$mail->AddReplyTo = SMTP_REPLYMAIL; //reply-to address
	$mail->SetFrom ($q['email'], $q['nm_lengkap']); //From address of the mail
	$mail->Subject = "AJKOnline - APPROVE PESERTA SPK AJK ONLINE"; //Subject od your mail
	//EMAIL PENERIMA KANTOR U/W
	$mailsupervisorajk = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="" AND status="UNDERWRITING"');
while ($_mailsupervisorajk = mysql_fetch_array($mailsupervisorajk)) {
	$mail->AddAddress($_mailsupervisorajk['email'], $_mailsupervisorajk['nm_lengkap']); //To address who will receive this email
}
	//EMAIL PENERIMA KANTOR U/W
	//EMAIL PENERIMA CLIENT

	$mailsupervisorclient = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="'.$vall[0].'" AND  wilayah="'.$q['wilayah'].'" AND email !=""');
while ($_mailsupervisorclient = mysql_fetch_array($mailsupervisorclient)) {
	$mail->AddAddress($_mailsupervisorclient['email'], $_mailsupervisorclient['nm_lengkap']); //To address who will receive this email
}
	//EMAIL PENERIMA CLIENT

	$mail->AddCC("rahmad@yahoo.co.id");
	$mail->MsgHTML('<table><tr><th>Data Peserta SPK telah di approve oleh <b>'.$_SESSION['nm_user'].' selaku Supervisor AJK-Online pada tanggal '.$futgl.'</tr></table>'.$message); //Put your body of the message you can place html code here
	$send = $mail->Send(); //Send the mails

	echo '<center>Approve oleh <b>'.$_SESSION['nm_user'].'</b> telah berhasil, segera dibuat pencetakan nomor DN.<br /> <a href="ajk_dn.php">Kembali Ke Halaman Utama</a></center>';

}
	;
	break;

case "deldata":
$met = $database->doQuery('DELETE FROM fu_ajk_peserta_tempf WHERE id_cost="'.$_REQUEST['id_cost'].'" AND
																  id_polis="'.$_REQUEST['id_polis'].'" AND
																  namafile="'.$_REQUEST['namafile'].'" AND
																  no_urut="'.$_REQUEST['no_urut'].'" AND
																  spaj="'.$_REQUEST['spaj'].'" AND
																  nama="'.$_REQUEST['nama'].'" AND
																  kredit_jumlah="'.$_REQUEST['kredit_jumlah'].'"');
if ($_REQUEST['cl']=="claim") {	header("location:ajk_uploader_spak.php?r=viewallclaim");	}
else	{	header("location:ajk_uploader_spk.php?r=viewallspk");	}

	case "viewallspk":
if ($_REQUEST['rx']=="pending") {
	$metpending = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta_tempf WHERE no_urut="'.$_REQUEST['no_urut'].'" AND nama="'.$_REQUEST['nama'].'" AND tgl_lahir="'.$_REQUEST['tgl_lahir'].'" AND kredit_tgl="'.$_REQUEST['kredit_tgl'].'"'));
	$riweuhkreditawal = explode("/", $metpending['kredit_tgl']);	$cektglkreditawal = $riweuhkreditawal[0].'-'.$riweuhkreditawal[1].'-'.$riweuhkreditawal[2];				//KREDIT AWAL EXPLODE

	$riweuhkredit = explode("/", $metpending['kredit_tgl']);	$cektglkredit = $riweuhkredit[0].'-'.$riweuhkredit[1].'-'.$riweuhkredit[2];									//KREDIT AKHIR
	$endkredit2=date('d/m/Y',strtotime($cektglkredit."+".$metpending['kredit_tenor']." Month". - "1"."Day"));																//KREDIT AKHIR
	$vendkredit2=date('Y-m-d',strtotime($cektglkredit."+".$metpending['kredit_tenor']." Month". - "1"."Day"));																//VKREDIT AKHIR

	$cekpolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$metpending['id_polis'].'"'));
if ($cekpolis['typeRate']=="T") {
	$RTenor = $metpending['kredit_tenor'] / 12;		$tenortunggal = ceil($RTenor);
	$cekrate = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi_tunggal WHERE id_cost="'.$metpending['id_cost'].'" AND id_polis="'.$metpending['id_polis'].'" AND usia="'.$umur.'" AND tenorthn="'.$tenortunggal.'"'));		// RATE PREMI
}else{
	$cekrate = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$metpending['id_cost'].'" AND id_polis="'.$metpending['id_polis'].'" AND tenor="'.$metpending['kredit_tenor'].'"'));		// RATE PREMI
}
	$premi = $metpending['kredit_jumlah'] * $cekrate['rate'] / 1000;																		// RATE PREMI
	$diskonpremi = $premi * ($cekpolis['discount'] /100);			//diskon premi
	$tpremi = $premi - $diskonpremi;								//totalpremi


	//$tb = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_badan WHERE sex = "'.$metpending['gender'].'" AND w_from <= "'.$metpending['badanb'].'" AND w_to >= "'.$metpending['badanb'].'" AND h_from <= "'.$metpending['badant'].'" AND h_to >= "'.$metpending['badant'].'"'));
	//$extrapremi = ($premi * $tb['extrapremi']) / 100;
	$mettotal = $tpremi + $extrapremi + $metpending['biaya_adm'] + $metpending['biaya_refund'];

$mameto = $database->doQuery('INSERT INTO fu_ajk_peserta SET no_urut="'.$metpending['no_urut'].'",
														  id_dn="",
														  id_cost="'.$metpending['id_cost'].'",
														  id_polis="'.$metpending['id_polis'].'",
														  namafile="'.$metpending['namafile'].'",
														  spaj="'.$metpending['spaj'].'",
														  nama="'.$metpending['nama'].'",
														  gender="'.$metpending['gender'].'",
														  kartu_type="'.$metpending['kartu_type'].'",
														  kartu_no="'.$metpending['kartu_no'].'",
														  kartu_period="'.$metpending['kartu_period'].'",
														  tgl_lahir="'.$metpending['tgl_lahir'].'",
														  usia="'.$_REQUEST['u'].'",
														  kredit_tgl="'.$metpending['kredit_tgl'].'",
														  vkredit_tgl="'.$cektglkreditawal.'",
														  thn="'.$riweuhkreditawal[2].'",
														  bln="'.$riweuhkreditawal[1].'",
														  kredit_jumlah="'.$metpending['kredit_jumlah'].'",
														  kredit_tenor="'.$metpending['kredit_tenor'].'",
														  kredit_akhir="'.$endkredit2.'",
														  vkredit_akhir="'.$vendkredit2.'",
														  premi="'.$premi.'",
														  bunga="'.$metpending['bunga'].'",
														  disc_premi="'.$diskonpremi.'",
														  biaya_adm="'.$metpending['biaya_adm'].'",
														  biaya_refund="'.$metpending['biaya_refund'].'",
														  ext_premi="'.$extrapremi.'",
														  totalpremi="'.$mettotal.'",
														  badant="'.$metpending['badant'].'",
														  badanb="'.$metpending['badanb'].'",
														  ket="'.$metpending['ket'].'",
														  status_medik="'.$_REQUEST['m'].'",
														  status_bayar="0",
														  status_aktif="pending",
														  status_peserta="'.$metpending['status_peserta'].'",
														  regional ="'.$metpending['regional'].'",
														  area ="'.$metpending['area'].'",
														  cabang ="'.$metpending['cabang'].'",
														  input_by ="'.$metpending['input_by'].'",
														  input_time ="'.$metpending['input_time'].'"');

	$metpendingdel = $database->doQuery('DELETE FROM fu_ajk_peserta_tempf WHERE no_urut="'.$_REQUEST['no_urut'].'" AND nama="'.$_REQUEST['nama'].'" AND tgl_lahir="'.$_REQUEST['tgl_lahir'].'" AND kredit_tgl="'.$_REQUEST['kredit_tgl'].'"');
	header("location:ajk_uploader_spk.php?r=viewall");
}
$cat=$_GET['cat']; // Use this line or below line if register_global is off
if(strlen($cat) > 0 and !is_numeric($cat)){ // to check if $cat is numeric data or not.
	echo "Data Error";
	exit;
}
echo '<fieldset style="padding: 1">
	<legend align="center">S e a r c h</legend>
	<table border="0" width="40%" cellpadding="0" cellspacing="0">
		<form name="f2" method="post" action="">
		<tr><td width="15%" align="right">Company</td>
	  <td width="30%">: <select id="cat" name="cat" onchange="reload(this.form)">
	  	<option value="">---Select Company---</option>';
		$quer2=$database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name ASC');
while($noticia2 = mysql_fetch_array($quer2)) {
if($noticia2['id']==$cat){echo '<option selected value="'.$noticia2['id'].'">'.$noticia2['name'].'</option><BR>';}
else{echo  '<option value="'.$noticia2['id'].'">'.$noticia2['name'].'</option>';}
}
echo '</select></td></tr>
	<tr><td width="10%" align="right">Product</td>
		<td width="20%">: ';
if(isset($cat) and strlen($cat) > 0){
	$quer=$database->doQuery('SELECT * FROM fu_ajk_polis where id_cost="'.$cat.'" ORDER BY id ASC');
}else{$quer=$database->doQuery('SELECT * FROM fu_ajk_polis where id_cost="'.$cat.'" ORDER BY id ASC'); }
		echo '<select id="subcat"  name="subcat"><option value="">---Product---</option>';
while($noticia = mysql_fetch_array($quer)) {

	echo  '<option value='.$noticia['id'].'>'.$noticia['nmproduk'].'</option>';
}
echo '</select></td></tr>
		<tr><td colspan="1" align="right"><input type="submit" name="met" value="Searching" class="button"></td></tr>
			</form></table></fieldset>';

echo '<form method="post" action="ajk_uploader_spk.php?r=approve" onload ="onbeforeunload">
	  <table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#bde0e6">
	<tr><th width="1%"></th>
		<th width="1%"><input type="checkbox" id="selectall"/></th>
		<th width="1%">No</th>
		<th width="5%">No SPK</th>
		<th>Nama Tertanggung</th>
		<th width="5%">Tanggal Lahir</th>
		<th width="5%">Usia</th>
		<th width="5%">Uang Asuransi</th>
		<th width="5%">Mulai Asuransi</th>
		<th width="5%">Tenor</th>
		<th width="5%">Ext.Premi (%)</th>
		<th width="5%">Regional</th>
		<th width="5%">Area</th>
		<th width="5%">Cabang</th>
		<th width="5%">User</th>
		</tr>';
if ($_REQUEST['met']=="Searching") {
if ($_REQUEST['cat'])		{	$satu = 'AND id_cost LIKE "%' . $_REQUEST['cat'] . '%"';		}
if ($_REQUEST['subcat'])	{	$dua = 'AND id_polis LIKE "%' . $_REQUEST['subcat'] . '%"';		}
	//if ($q['status']=="10" AND $q['supervisor']=="1" OR $q['status']=="") {	$cekinputby = 'AND input_by="'.$q['nm_user'].'"';	}	DISABLED 16062014
	if ($q['status']==1)	{
		$data = $database->doQuery('SELECT * FROM fu_ajk_peserta_tempf WHERE nama!="" AND type_data="SPK" '.$satu.' '.$dua.' '.$cekinputby.' ORDER BY input_by ASC');	}
	else	{
		$data = $database->doQuery('SELECT * FROM fu_ajk_peserta_tempf WHERE nama!="" AND type_data="SPK" '.$satu.' '.$dua.' '.$cekinputby.' ORDER BY input_by ASC');
	}
	$approvedatanya = '<tr><td colspan="27" align="center"><a href="ajk_uploader_spk.php?r=approve" onClick="if(confirm(\'Apakah anda sudah yakin dengan semua data peserta ini ?\')){return true;}{return false;}"><input type="submit" name="ApproveClaim" Value="Approve"></a></td></tr>';
}else{
	$data = $database->doQuery('SELECT * FROM fu_ajk_peserta_tempf WHERE nama !="" AND type_data="SPK" '.$cekinputby.'  ORDER BY input_by ASC');
}
while ($fudata = mysql_fetch_array($data)) {
	$umur = ceil(((strtotime($fudata['kredit_tgl']) - strtotime($fudata['tgl_lahir']."+6 month")) / (60*60*24*365.2425)));	// FORMULA USIA
	$dataceklist = '<input type="checkbox" class="case" name="nama[]" value="'.$fudata['id_cost'].'-met-'.$fudata['id_polis'].'-met-'.$fudata['nama'].'-met-'.$fudata['tgl_lahir'].'-met-'.$fudata['kredit_jumlah'].'">';
if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
$metcekdata = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak WHERE id_cost="'.$fudata['id_cost'].'" AND spak="'.$fudata['spaj'].'"'));
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		  <td align="center"><a href="ajk_uploader_spk.php?r=deldata&id_cost='.$fudata['id_cost'].'&id_polis='.$fudata['id_polis'].'&namafile='.$fudata['namafile'].'&no_urut='.$fudata['no_urut'].'&spaj='.$fudata['spaj'].'&nama='.$fudata['nama'].'&kredit_jumlah='.$fudata['kredit_jumlah'].'" onClick="if(confirm(\'Apakah anda yakin untuk menghapus data peserta ini ?\')){return true;}{return false;}"><img src="image/delete.gif"></a></td>
		  <td align="center">'.$dataceklist.'
		  </td>
		  <td align="center">'.++$no.'</td>
		  <td align="center">'.$fudata['spaj'].'</td>
		  <td>'.$fudata['nama'].'</td>
		  <td align="center">'._convertDate($fudata['tgl_lahir']).'</td>
		  <td align="center">'.$umur.'</td>
		  <td align="right">'.duit($fudata['kredit_jumlah']).'</td>
		  <td align="center">'._convertDate($fudata['kredit_tgl']).'</td>
		  <td align="center">'.$fudata['kredit_tenor'].'</td>
		  <td align="center">'.$metcekdata['ext_premi'].'</td>
		  <td align="center">'.$fudata['cabang'].'</td>
		  <td align="center">'.$fudata['area'].'</td>
		  <td align="center">'.$fudata['regional'].'</td>
		  <td align="center"><font color="blue"><b>'.$fudata['input_by'].'</b></font></td>
		  </tr>';
}

if ($_REQUEST['subcat'] !="" AND $q['status']=="UNDERWRITING" OR $q['status']=="" ) {
	$el = $database->doQuery('SELECT * FROM fu_ajk_peserta_tempf WHERE id_cost="'.$_REQUEST['cat'].'" ');
	$met = mysql_num_rows($el);
	//if ($met > 0) {	echo '<tr><td colspan="27" align="center"><a href="ajk_uploader_peserta.php?r=approve&val=pclaim&id_cost='.$_REQUEST['cat'].'&id_polis='.$_REQUEST['subcat'].'" onClick="if(confirm(\'Apakah anda sudah yakin dengan semua data peserta ini ?\')){return true;}{return false;}">Approve</a></td></tr>';
	if ($met > 0) {	echo $approvedatanya;
		//}else{	echo '<tr><th colspan="27" align="center"><blink><b><font color="blue">Tidak ada data peserta restruktur, topup atau refund yang harus di validasi. !!!</font></b></blink></th></tr>';
	}else{	echo '';	}
}else{
	//echo '<tr><td colspan="27" align="center">'.$q['status'].'</td></tr>';
}

		echo '</table>';

		;
		break;
	default:
		;
} // switch

?>
<SCRIPT language=JavaScript>
function reload(form)
{
	var val=form.cat.options[form.cat.options.selectedIndex].value;
	self.location='ajk_uploader_spk.php?r=viewallspk&cat=' + val;
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