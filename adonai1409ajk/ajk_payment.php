<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("includes/functions.php");
echo "<script language=\"JavaScript\" src=\"javascript/js/form_validation.js\"></script>";
connect();
if (session_is_registered('nm_user')) {
	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));
	$met = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$q['id_cost'].'"'));
	$cmet = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id_cost="'.$met['id'].'"'));
}

switch ($_REQUEST['fu']) {
	case "a":

		;
	case "fuedit":
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
<tr><th width="95%" align="left">Modul Members - Edit Data Peserta</font></th><th><a href="ajk_payment.php?fu=detailpay"><img src="image/Backward-64.png" width="20"></a></th></table>';
//echo $_REQUEST['id'];
//echo $q['nm_lengkap'];
//echo $met['name'];
if ($_REQUEST['ope']=="Simpan") {
	$_REQUEST['rspaj'] = $_POST['rspaj'];
	$_REQUEST['rnama'] = $_POST['rnama'];
	$_REQUEST['rcardno'] = $_POST['rcardno'];
	$_REQUEST['rcardtenor'] = $_POST['rcardtenor'];
	$_REQUEST['rkreditjum'] = $_POST['rkreditjum'];
	$_REQUEST['rbunga'] = $_POST['rbunga'];
	$_REQUEST['rbadant'] = $_POST['rbadant'];
	$_REQUEST['rbadanb'] = $_POST['rbadanb'];
	if (!$_REQUEST['rspaj'])  $error .='<blink><font color=red>SPAJ tidak boleh kosong</font></blink><br>';
	if (!$_REQUEST['rnama'])  $error .='<blink><font color=red>Nama tidak boleh kosong</font></blink><br>';
	if (!$_REQUEST['rcardno'])  $error .='<blink><font color=red>Nomor identitas kartu tidak boleh kosong</font></blink><br>';
	if (!$_REQUEST['rcardtenor'])  $error .='<blink><font color=red>Tenor tidak boleh kosong</font></blink><br>';
	if (!$_REQUEST['rkreditjum'])  $error .='<blink><font color=red>Jumlah kredit tidak boleh kosong</font></blink><br>';
	if (!$_REQUEST['rbunga'])  $error .='<blink><font color=red>Bunga tidak boleh kosong</font></blink><br>';
	if (!$_REQUEST['rbadant'] OR !$_REQUEST['rbadanb'])  $error .='<blink><font color=red>Tinggi berat badan tidak boleh kosong</font></blink><br>';
	if ($error)
	{	echo '<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border: solid 1px red">
					  <tr><td><table width="100%" class="bgcolor1">
							  <tr><td align="left"><img src="image/warning.gif" border="0"></td>
								  <td align="center"><font class="option"><blink>'.$error.'</blink></font></td>
								  <td align="right"><img src="image/warning.gif" border="0"></td>
							  </tr>
				  </table></td></tr>
				  </table>';
	}
	else
	{
		$umur = ceil(((time() - strtotime($_REQUEST['rdob'])) / (60*60*24*365.2425)));

		$cekrate = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE tenor="'.$_REQUEST['rcardtenor'].'"'));		// RATE PREMI
		$premi = $_REQUEST['rkreditjum'] * $cekrate['rate'] / 1000;

		$tb = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_badan WHERE sex = "'.$_REQUEST['rgender'].'" AND w_from <= "'.$_REQUEST['rbadanb'].'" AND w_to >= "'.$rr['badanb'].'" AND "'.$_REQUEST['rbadant'].'" BETWEEN h_from AND h_to'));
		$extrapremi = ($_REQUEST['rkreditjum'] * $tb['extrapremi']) / 1000;

		$mettotal = $premi + $extrapremi;															//TOTAL															// RATE PREMI
		$el = $database->doQuery ('UPDATE v_fu_ajk_peserta SET spaj="'.$_REQUEST['rspaj'].'",
												   			 nama="'.$_REQUEST['rnama'].'",
												   			 gender="'.$_REQUEST['rgender'].'",
												   			 tgl_lahir="'.$_REQUEST['rdob'].'",
												   			 usia="'.$umur.'",
												   			 kartu_type="'.$_REQUEST['rcardtype'].'",
												   			 kartu_no="'.$_REQUEST['rcardno'].'",
												   			 kartu_period="'.$_REQUEST['rcardperiod'].'",
												   			 kredit_tgl="'.$_REQUEST['rkredittgl'].'",
												   			 kredit_tenor="'.$_REQUEST['rcardtenor'].'",
												   			 kredit_jumlah="'.$_REQUEST['rkreditjum'].'",
												   			 premi="'.$premi.'",
												   			 ext_premi="'.$extrapremi.'",
												   			 totalpremi="'.$mettotal.'",
												   			 bunga="'.$_REQUEST['rbunga'].'",
												   			 badant="'.$_REQUEST['rbadant'].'",
												   			 badanb="'.$_REQUEST['rbadanb'].'"
												   			 WHERE id='.$_REQUEST['id'].'');
echo '<div align="center">Data Peserta dengan nama <b>'.$_REQUEST['rnama'].'</b> telah selesai di edit.</div><meta http-equiv="refresh" content="2; url=ajk_payment.php?fu=detailpay">';
	}
}
$met = mysql_fetch_array($database->doQuery('SELECT * FROM v_fu_ajk_peserta WHERE id="'.$_REQUEST['id'].'"'));
$metdob = explode("-", $met['tgl_lahir']);	$metdobfu = $metdob[2].'-'.$metdob[1].'-'.$metdob[0];									//EXPLODE DOB
$mettglkartu = explode("-", $met['kartu_period']);	$mettglkartufu = $mettglkartu[2].'-'.$mettglkartu[1].'-'.$mettglkartu[0];		//EXPLODE TGL KARTU
$mettglkredit = explode("-", $met['kredit_tgl']);	$mettglkreditfu = $mettglkredit[2].'-'.$mettglkredit[1].'-'.$mettglkredit[0];	//EXPLODE TGL KARTU
echo '<form name="f1"  method="post" action="">
	  <table border="0" width="80%" cellpadding="3" cellspacing="1" align="center">
	  <input type="hidden" name="id" value="'.$_REQUEST['id'].'">
	  <tr><td width="20%">SPAJ</td><td colspan="6">: <input type="text" name="rspaj" value="'.$met['spaj'].'"></td></tr>
	  <tr><td>Nama</td><td colspan="6">: <input type="text" name="rnama" value="'.$met['nama'].'"></td></tr>
	  <tr><td>Jenis Kelamin</td><td colspan="6">: <select size="1" name="rgender">
   			<option value="P"'._selected($met["gender"], "P").'>Pria</option>
   			<option value="W"'._selected($met["gender"], "W").'>Wanita</option>
   			</select></td></tr>
	  <tr><td>Tanggal Lahir</td><td colspan="6">: ';	echo initCalendar();	echo calendarBox('rdob', 'triger1', $met['tgl_lahir']);
echo '</td></tr>
	  <tr><td>Kartu Identitas</td><td> Type </td><td>: <select size="1" name="rcardtype">
   			<option value="KTP"'._selected($met["kartu_type"], "KTP").'>KTP</option>
   			<option value="SIM"'._selected($met["kartu_type"], "SIM").'>SIM</option>
   			<option value="Pasport"'._selected($met["kartu_type"], "Pasport").'>Pasport</option>
   			</select></td>
   		 <td width="10%">Nomor Kartu </td><td>: <input type="text" name="rcardno" value="'.$met['kartu_no'].'"></td>
   		 <td>Masa Berlaku Kartu </td><td>: ';	echo initCalendar();	echo calendarBox('rcardperiod', 'triger2', $met['kartu_period']);
echo '</td></tr>
	  <tr><td>Status Kredit</td><td> Mulai Kredit </td><td>: ';	echo initCalendar();	echo calendarBox('rkredittgl', 'triger3', $met['kredit_tgl']);
echo '<td>Tenor</td><td>: <input type="text" name="rcardtenor" value="'.$met['kredit_tenor'].'" size="3" maxlength="2"></td>
	  <td>Jumlah Kredit </td><td>: <input type="text" name="rkreditjum" value="'.$met['kredit_jumlah'].'" size="14"></td></tr>
	  <tr><td>Bunga</td><td colspan="6">: <input type="text" name="rbunga" value="'.$met['bunga'].'" size="3"> %</td></tr>
	  <tr><td>Tinggi Berat Badan</td><td colspan="6">T : <input type="text" name="rbadant" value="'.$met['badant'].'" size="3"> &nbsp; B : <input type="text" name="rbadanb" value="'.$met['badanb'].'" size="3"></td></tr>
	  <tr><td align="center" colspan="7"><input type="submit" name="ope" value="Simpan"></td></tr>
	  </table></form>';
	;
	break;
	case "medical":
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
      <tr><th width="100%" align="left">Modul Medical</font></th></tr>
      </table>';
echo '<fieldset style="padding: 2">
	<legend align="center">S e a r c h</legend>
	<table border="0" width="20%" cellpadding="1" cellspacing="1" align="center">
		<form method="post" action="">
		<tr><td align="right">Nomor SPAJ :</td>
      		<td><select size="1" name="sspaj">
	   		<option value="">- Pilih SPAJ -</option>';
$reg = $database->doQuery('SELECT * FROM v_fu_ajk_peserta WHERE id!="" AND status_aktif="pending"  ORDER BY spaj ASC');
while ($creg = mysql_fetch_array($reg)) {	echo '<option value="'.$creg['spaj'].'">'.$creg['spaj'].'</option>';	}
echo '</select></td>
   		</tr>
		<tr><td align="right">Nama :</td><td><input type="text" name="snama" value="'.$_REQUEST['snama'].'"></td></tr>
		<tr><td align="center" colspan="2"><input type="submit" name="button" value="Cari" class="button"></td>
		</tr>
		</form></table></fieldset>';
if ($_REQUEST['snama'])		{	$satu = 'AND nama LIKE "%' . $_REQUEST['snama'] . '%"';		}
if ($_REQUEST['sspaj'])		{	$dua = 'AND spaj LIKE "%' . $_REQUEST['sspaj'] . '%"';		}
echo '<form method="post" action="ajk_uploader_fu.php?r=approve">
	  <table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="#E2E2E2">
	<tr><th width="1%" rowspan="2">No</th>
		<th width="5%" rowspan="2">SPAJ</th>
		<th width="5%" rowspan="2">No. Reg</th>
		<th rowspan="2">Nama Debitur<br /><font size="1"><i>(sesuai KTP/SIM)</i></font></th>
		<th width="1%" rowspan="2">P/W</th>
		<th colspan="2">Kartu Identitas</th>
		<th rowspan="2">Tgl Lahir</th>
		<th rowspan="2">Usia</th>
		<th colspan="4">Status Kredit</th>
		<th width="1%" rowspan="2">Bunga<br>%</th>
		<th width="1%" rowspan="2">Premi</th>
		<th colspan="3">Biaya</th>
		<th width="1%" rowspan="2">Total Premi</th>
		<th rowspan="2">Pernyataan</th>
		<th rowspan="2">Medical</th>
		<th rowspan="2">Regional</th>
		<th rowspan="2">Area</th>
		<th rowspan="2">Cabang</th>
	</tr>
	<tr><th width="5%">Type</th>
		<th width="5%">No</th>
		<th>Tgl Mulai</th>
		<th>Tenor</th>
		<th>Tgl Berakhir</th>
		<th>Jumlah</th>
		<th>Adm</th>
		<th>Refund</th>
		<th>Ext. Premi</th>

	</tr>';
$data = $database->doQuery('SELECT * FROM v_fu_ajk_peserta WHERE id!="" AND status_aktif="pending" '.$satu.' '.$dua.' ORDER BY input_time DESC, cabang ASC');
while ($fudata = mysql_fetch_array($data)) {
	$idp1 = 100000000 + $fudata['id'];		$idp2 = substr($idp1,1);	// ID PESERTA //

	//CEK FORMAT UMUR
	$findmet="/";
	$fpos = stripos($fudata['tgl_lahir'], $findmet);
if ($fpos === false) {
	$riweuhkredit = explode("-", $fudata['kredit_tgl']);	$cektglkredit = $riweuhkredit[0].'-'.$riweuhkredit[1].'-'.$riweuhkredit[2];
	$endkredit2=date('d-m-Y',strtotime($cektglkredit."+".$fudata['kredit_tenor']." Month"));												//KREDIT AKHIR

	$riweuh = explode("-", $fudata['tgl_lahir']);			$cektglnya = $riweuh[2].'-'.$riweuh[1].'-'.$riweuh[0];
	$umur = ceil(((strtotime($endkredit2) - strtotime($cektglnya)) / (60*60*24*365.2425)));													// FORMULA USIA
}	else	{
	$riweuhkredit = explode("/", $fudata['kredit_tgl']);	$cektglkredit = $riweuhkredit[0].'-'.$riweuhkredit[1].'-'.$riweuhkredit[2];
	$endkredit2=date('d-m-Y',strtotime($cektglkredit."+".$fudata['kredit_tenor']." Month"));												//KREDIT AKHIR

	$riweuh = explode("/", $fudata['tgl_lahir']);			$cektglnya = $riweuh[2].'-'.$riweuh[1].'-'.$riweuh[0];
	$umur = ceil(((strtotime($endkredit2) - strtotime($cektglnya)) / (60*60*24*365.2425)));													// FORMULA USIA
}
	//CEK FORMAT UMUR
	if ($handle = opendir('ajk_file/medical/')) {
		while (false !== ($file = readdir($handle))) {
			$cekdata = $file;
		}
		if ($cekdata == $idp2.'-'.$fudata['nama'].'.docx') {
			$docmedik = '<a href="ajk_file/medical/'.$cekdata.'">Download</a>';	}else{
				$docmedik = '<blink><b>'.$fudata['status_medik'].'</b></blink>';
			}
	}

	$fmedic = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_medical_form WHERE id_peserta="'.$fudata['id'].'"'));
	if ($fmedic['id_peserta'] == $fudata['id'])
	{	$docmedik = '<a href="ajk_file/medical/'.$fmedic['file_medical'].'">Download</a>';	}	else
	{	$docmedik = $fudata['status_medik'];	}

echo '<tr class="'.rowClass(++$i).'">
		  <td align="center">'.++$no.'</td>
		  <td>'.$fudata['spaj'].'</td>
		  <td>'.$idp2.'</td>
		  <td>'.$fudata['nama'].'</td>
		  <td align="center">'.$fudata['gender'].'</td>
		  <td width="1%" align="center">'.$fudata['kartu_type'].'</td>
		  <td>'.$fudata['kartu_no'].'</td>
		  <td align="center">'.$fudata['tgl_lahir'].'</td>
		  <td align="center">'.$umur.'</td>
		  <td align="center">'.$fudata['kredit_tgl'].'</td>
		  <td align="center">'.$fudata['kredit_tenor'].'</td>
		  <td align="center"><b>'.$endkredit2.'</b></td>
		  <td align="right">'.duit($fudata['kredit_jumlah']).'</td>
		  <td align="center">'.$fudata['bunga'].'</td>
		  <td align="right">'.duit($fudata['premi']).'</td>
		  <td align="right">'.duit($fudata['biaya_adm']).'</td>
		  <td align="right">'.duit($fudata['biaya_refund']).'</td>
		  <td align="right">'.duit($fudata['ext_premi']).'</td>
		  <td align="right">'.duit($fudata['totalpremi']).'</td>
		  <td align="center">'.$fudata['statement1'].' | '.$fudata['statement2'].' | '.$fudata['statement3'].' | '.$fudata['statement4'].'</td>
		  <td align="center">'.$docmedik.'</td>
		  <td align="center">'.$fudata['regional'].'</td>
		  <td align="center">'.$fudata['area'].'</td>
		  <td align="center">'.$fudata['cabang'].'</td>
		  </tr>';
}
		echo '</table>';
		;
		break;
	case "rpending":
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
      <tr><th width="100%" align="left">Modul Members Pending</font></th></tr>
      </table>';
echo '<fieldset style="padding: 2">
	<legend align="center">S e a r c h</legend>
	<table border="0" width="20%" cellpadding="1" cellspacing="1" align="center">
		<form method="post" action="">
		<tr><td align="right">Nomor SPAJ :</td>
      		<td><select size="1" name="sspaj">
	   		<option value="">- Pilih SPAJ -</option>';
$reg = $database->doQuery('SELECT * FROM v_fu_ajk_peserta WHERE status_aktif="pending" AND status_medik="NM" OR status_medik="M" ORDER BY spaj ASC');
while ($creg = mysql_fetch_array($reg)) {	echo '<option value="'.$creg['spaj'].'">'.$creg['spaj'].'</option>';	}
echo '</select></td></tr>
		<tr><td align="right">Nama :</td><td><input type="text" name="snama" value="'.$_REQUEST['snama'].'"></td></tr>
		<tr><td align="center" colspan="2"><input type="submit" name="button" value="Cari" class="button"></td></tr>
		</form></table></fieldset>';
if ($_REQUEST['snama'])		{	$satu = 'AND nama LIKE "%' . $_REQUEST['snama'] . '%"';		}
if ($_REQUEST['sspaj'])		{	$dua = 'AND spaj LIKE "%' . $_REQUEST['sspaj'] . '%"';		}
echo '<form method="post" action="ajk_uploader_fu.php?r=approve">
	  <table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="#E2E2E2">
		<tr><th width="1%" rowspan="2">No</th>
			<th width="5%" rowspan="2">SPAJ</th>
			<th width="5%" rowspan="2">No. Reg</th>
			<th rowspan="2">Nama Debitur<br /><font size="1"><i>(sesuai KTP/SIM)</i></font></th>
			<th width="1%" rowspan="2">P/W</th>
			<th colspan="2">Kartu Identitas</th>
			<th rowspan="2">Tgl Lahir</th>
			<th rowspan="2">Usia</th>
			<th colspan="2">Status Kredit</th>
			<th width="1%" rowspan="2">Bunga<br>%</th>
			<th width="1%" rowspan="2">Premi</th>
			<th colspan="3">Biaya</th>
			<th width="1%" rowspan="2">Total Premi</th>
			<th rowspan="2">Pernyataan</th>
			<th rowspan="2">Medical</th>
			<th rowspan="2">Regional</th>
			<th rowspan="2">Area</th>
			<th rowspan="2">Cabang</th>
		</tr>
		<tr><th width="5%">Type</th>
			<th width="5%">No</th>
			<th>Jumlah</th>
			<th>Tenor</th>
			<th>Adm</th>
			<th>Refund</th>
			<th>Ext. Premi</th>
		</tr>';
$data = $database->doQuery('SELECT * FROM v_fu_ajk_peserta WHERE id!="" AND status_aktif="pending" AND status_medik="NM" '.$satu.' '.$dua.' ORDER BY cabang ASC');
while ($fudata = mysql_fetch_array($data)) {
echo '<tr class="'.rowClass(++$i).'">
		  <td align="center">'.++$no.'</td>
		  <td>'.$fudata['spaj'].'</td>
		  <td>'.$fudata['id_peserta'].'</td>
		  <td>'.$fudata['nama'].'</td>
		  <td align="center">'.$fudata['gender'].'</td>
		  <td width="1%" align="center">'.$fudata['kartu_type'].'</td>
		  <td>'.$fudata['kartu_no'].'</td>
		  <td align="center">'.$fudata['tgl_lahir'].'</td>
		  <td align="center">'.$fudata['usia'].'</td>
		  <td align="right">'.duit($fudata['kredit_jumlah']).'</td>
		  <td align="center">'.$fudata['kredit_tenor'].'</td>
		  <td align="center">'.$fudata['bunga'].'</td>
		  <td align="right">'.duit($fudata['premi']).'</td>
		  <td align="right">'.duit($fudata['biaya_adm']).'</td>
		  <td align="right">'.duit($fudata['biaya_refund']).'</td>
		  <td align="right">'.duit($fudata['ext_premi']).'</td>
		  <td align="right">'.duit($fudata['totalpremi']).'</td>
		  <td align="center">'.$fudata['statement1'].' | '.$fudata['statement2'].' | '.$fudata['statement3'].' | '.$fudata['statement4'].'</td>
		  <td align="center"><b>'.$fudata['status_medik'].'</b></td>
		  <td align="center">'.$fudata['regional'].'</td>
		  <td align="center">'.$fudata['area'].'</td>
		  <td align="center">'.$fudata['cabang'].'</td>
		  </tr>';
}
		echo '</table>';
		;
		break;

	case "detailpay":
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
<tr><th width="90%" align="left">Modul Members</font></th><th colspan="30"><a href="ajk_payment.php?fu=detailpay&ope=paid">Paid</a> &nbsp; | &nbsp;
																		   <a href="ajk_payment.php?fu=detailpay&ope=unpaid"">Unpaid</a></th></tr>
</table>
<fieldset>
<legend>Searching</legend>
<table border="0" width="100%" cellpadding="1" cellspacing="1">
<form method="post" action="" name="frmcust" onSubmit="return valcust()">
<tr><td width="5%">Regional</td>
	<td width="10%">: <select id="regional" name="regional" onChange="DinamisRegional(this);" class="cmb">
		<option value="">- Pilih Regional -</option>';
		$quer2=$database->doQuery('SELECT * FROM fu_ajk_regional WHERE id_cost="'.$met['id'].'" AND name LIKE "%'.$q['wilayah'].'%" ORDER BY name ASC');
		while($noticia2 = mysql_fetch_array($quer2)) {
		echo  '<option value="'.$noticia2['id'].'">'.$noticia2['name'].'</option>';}
		echo '</select></td>
	<td width="5%">Nomor SPAJ </td><td>: <input type="text" name="rspaj" value="'.$_REQUEST['rspaj'].'">
</tr>
<tr><td>Pilih Area</td>
	<td id="area">: <select name="area" class="cmb"><option value="">- Pilih Area -</option></select></td>
		<td>Nama</td><td>: <input type="text" name="rnama" value="'.$_REQUEST['rnama'].'"></td>';
echo '</td></tr>
	  <tr><td>Pilih Cabang</td>
	  	  <td id="cabang">: <select name="cabang" class="cmb"><option value="">- Pilih Cabang -</option></select></td>
	  	  <td>DOB</td><td>: ';print initCalendar();	print calendarBox('rdob', 'triger', $_REQUEST['rdob']);
echo '</tr>
	  <tr><td width="5%" align="center" colspan="3"><input type="submit" name="button" value="C a r i" class="button"></td></tr>
		</td></tr>
		</form>
		</table></fieldset>
	  <table border="0" width="100%" cellpadding="3" cellspacing="1"  bgcolor="#bde0e6">
	<tr><th width="1%" rowspan="2">Edit</th>
		<th width="1%" rowspan="2">No</th>
		<th width="5%" rowspan="2">SPAJ</th>
		<th rowspan="2">Nama Debitur<br /><font size="1"><i>(sesuai KTP/SIM)</i></font></th>
		<th width="1%" rowspan="2">P/W</th>
		<th colspan="3">Kartu Identitas</th>
		<th rowspan="2">Tgl Lahir</th>
		<th rowspan="2">Usia</th>
		<th colspan="4">Status Kredit</th>
		<th width="1%" rowspan="2">Premi</th>
		<th width="1%" rowspan="2">Tinggi/<br />Berat Badan</th>
		<th width="1%" rowspan="2">Status DN</th>
		<th width="10%" rowspan="2">No. DN</th>
		<th width="1%" rowspan="2">Status Peserta</th>
		<th colspan="4">Pernyataan (Ya/Tdk)</th>
		<th rowspan="2">Keterangan</th>
		<th rowspan="2">Cabang</th>
		<th rowspan="2">Cabang<br />(Lama)</th>
		<th rowspan="2">Area</th>
		<th rowspan="2">Area<br />(Lama)</th>
		<th rowspan="2">Regional</th>
		<th rowspan="2">Regional<br />(Lama)</th>
		<th rowspan="2">Input BY</th>
	</tr>
	<tr><th width="5%">Type</th>
		<th width="5%">No</th>
		<th width="5%">Periode</th>
		<th>Tgl Mulai</th>
		<th>Tenor</th>
		<th>Tgl Berakhir</th>
		<th>Jumlah</th>
		<th>P1</th>
		<th>P2</th>
		<th>P3</th>
		<th>P4</th>
	</tr>';

if ($_REQUEST['rspaj'])		{	$satu = 'AND fu_ajk_peserta.spaj LIKE "%' . $_REQUEST['rspaj'] . '%"';	}
if ($_REQUEST['rnama'])		{	$dua = 'AND fu_ajk_peserta.nama LIKE "%' . $_REQUEST['rnama'] . '%"';	}
if ($_REQUEST['rdob'])		{	$tiga = 'AND fu_ajk_peserta.tgl_lahir LIKE "%' . _convertDate($_REQUEST['rdob']) . '%"';	}
$rcari = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['regional'].'"'));
if ($_REQUEST['regional'])	{	$empat = 'AND fu_ajk_peserta.regional LIKE "%' . $rcari['name'] . '%"';	}


if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}
else {	$m = 0;		}
if ($_REQUEST['ope']=="paid") {	$met = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id != "" AND regional LIKE "%'.$q['wilayah'].'%" AND id_cost="'.$q['id_cost'].'" AND status_bayar="1" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' ORDER BY input_time DESC, cabang ASC, id DESC LIMIT ' . $m . ' , 25');
	$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_peserta WHERE id != "" AND regional LIKE "%'.$q['wilayah'].'%" AND id_cost="'.$q['id_cost'].'" AND status_bayar="1" ' . $satu . ' '.$dua.' '.$tiga.' '.$empat.' ORDER BY input_time DESC, cabang ASC, id DESC'));
	$totalRows = $totalRows[0];
}
elseif ($_REQUEST['ope']=="unpaid") {	$met = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id != "" AND regional LIKE "%'.$q['wilayah'].'%" AND id_cost="'.$q['id_cost'].'" AND status_bayar="0" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' ORDER BY input_time DESC, cabang ASC, id DESC LIMIT ' . $m . ' , 25');
	$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_peserta WHERE id != "" AND regional LIKE "%'.$q['wilayah'].'%" AND id_cost="'.$q['id_cost'].'" AND status_bayar="0" ' . $satu . ' '.$dua.' '.$tiga.' '.$empat.' ORDER BY input_time DESC, cabang ASC, id DESC'));
	$totalRows = $totalRows[0];
}else{
	$met = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id != "" AND regional LIKE "%'.$q['wilayah'].'%" AND id_cost="'.$q['id_cost'].'" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' ORDER BY input_time DESC, cabang ASC, id DESC LIMIT ' . $m . ' , 25');
	$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_peserta WHERE id != "" AND regional LIKE "%'.$q['wilayah'].'%" AND id_cost="'.$q['id_cost'].'" ' . $satu . ' '.$dua.' '.$tiga.' '.$empat.' ORDER BY input_time DESC, cabang ASC, id DESC'));
	$totalRows = $totalRows[0];
}
$metz = $met;
		$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while ($fu = mysql_fetch_array($met)) {
	$tanggalawal=$fu['kredit_tgl'];
	$tanggalakhir=date('d-m-Y',strtotime($tanggalawal."+".$fu['kredit_tenor']." Month"));

	$idcabang = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE name="'.$fu['cabang'].'"'));

	//CEK FORMAT UMUR DAN KREDIT AKHIR(MANUAL)
	$findmet="/";
	$fpos = stripos($fu['tgl_lahir'], $findmet);
if ($fpos === false) {
	$riweuhkredit = explode("-", $fu['kredit_tgl']);	$cektglkredit = $riweuhkredit[0].'-'.$riweuhkredit[1].'-'.$riweuhkredit[2];		//KREDIT AKHIR
	$endkredit2=date('d-m-Y',strtotime($cektglkredit."+".$fu['kredit_tenor']." Month"));												//KREDIT AKHIR

	$riweuh = explode("-", $fu['tgl_lahir']);			$cektglnya = $riweuh[2].'-'.$riweuh[1].'-'.$riweuh[0];							// FORMULA USIA
	$riweuh2 = explode("-", $fu['kredit_tgl']);			$cektglnya2 = $riweuh2[2].'-'.$riweuh2[1].'-'.$riweuh2[0];							// FORMULA USIA
	$umur = ceil(((strtotime($cektglnya2) - strtotime($cektglnya)) / (60*60*24*365.2425)));												// FORMULA USIA
}	else	{
	$riweuhkredit = explode("/", $fu['kredit_tgl']);	$cektglkredit = $riweuhkredit[0].'-'.$riweuhkredit[1].'-'.$riweuhkredit[2];		//KREDIT AKHIR
	$endkredit2=date('d-m-Y',strtotime($cektglkredit."+".$fu['kredit_tenor']." Month"));												//KREDIT AKHIR

	$riweuh = explode("/", $fu['tgl_lahir']);			$cektglnya = $riweuh[2].'-'.$riweuh[1].'-'.$riweuh[0];							// FORMULA USIA
	$riweuh2 = explode("/", $fu['kredit_tgl']);			$cektglnya2 = $riweuh2[2].'-'.$riweuh2[1].'-'.$riweuh2[0];							// FORMULA USIA
	$umur = ceil(((strtotime($cektglnya2) - strtotime($cektglnya)) / (60*60*24*365.2425)));												// FORMULA USIA
}

if ($fu['kredit_akhir']=="") {	$endkredit = $endkredit2;		}
else	{	$endkredit = $fu['kredit_akhir'];	}

	//CEK FORMAT UMUR DAN KREDIT AKHIR(MANUAL)

if ($fu['status_peserta']=="") {	$metstatuspeserta = 'Aktif';	}
else {	$metstatuspeserta = $fu['status_peserta'];	}

if ($fu['status_bayar']==0) {	$statusnya = '<font color="red"><blink>Unpaid</b></font>';	}
else {	$statusnya = '<font color="blue">Paid</font>';	}

if ($fu['id_dn']=="") {	$metedit = '<a title="edit data" href="ajk_payment.php?fu=fuedit&id='.$fu['id'].'"><img src="image/edit3.png" width="20"></a>';	}
else	{ $metedit= '';	}

if (($no % 2) == 1)	$objlass = 'tbl-odd'; else				$objlass = 'tbl-even';

$inputby = explode("-", $fu['input_by']);

echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	  	  <td align="center">'.$metedit.'</td>
	  	  <td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
		  <td align="center">'.$fu['spaj'].'</td>
		  <td>'.$fu['nama'].'</td>
		  <td align="center">'.$fu['gender'].'</td>
		  <td align="center">'.$fu['kartu_type'].'</td>
		  <td>'.$fu['kartu_no'].'</td>
		  <td align="center">'.$fu['kartu_period'].'</td>
		  <td align="center">'.$fu['tgl_lahir'].'</td>
		  <td align="center">'.$fu['usia'].'</td>
		  <td align="center">'.$fu['kredit_tgl'].'</td>
		  <td align="center">'.$fu['kredit_tenor'].'</td>
		  <td align="right">'.$endkredit.'</td>
		  <td align="right">'.duit($fu['kredit_jumlah']).'</td>
		  <td align="right">'.duit($fu['premi']).'</td>
		  <td align="center">'.$fu['badant'].'/'.$fu['badanb'].'</td>
		  <td align="center">'.$statusnya.'</td>
		  <td align="center">'.$fu['id_dn'].'</td>
		  <td align="center">'.$metstatuspeserta.'</td>
		  <td align="center">'.$fu['statement1'].'</td>
		  <td align="center">'.$fu['statement2'].'</td>
		  <td align="center">'.$fu['statement3'].'</td>
		  <td align="center">'.$fu['statement4'].'</td>
		  <td align="center">'.$fu['ket'].'</td>
		  <td><b>('.$idcabang['nmr_cbg'].')</b> - '.$fu['cabang'].'</td>
		  <td>'.$fu['cabang_lama'].'</td>
 		  <td>'.$fu['area'].'</td>
 		  <td>'.$fu['area_lama'].'</td>
		  <td>'.$fu['regional'].'</td>
		  <td>'.$fu['regional_lama'].'</td>
		  <td>'.$inputby[0].'</td>
 	  </tr>';
		}
echo '<tr><td colspan="27">';
echo createPageNavigations($file = 'ajk_payment.php?fu=detailpay&ope='.$_REQUEST['ope'].'&', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
echo '<b>Total Data Peserta: <u>' . $totalRows . '</u></b></td></tr>';
echo '</table>';
		;
		break;
	case "paid":
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
		<tr><th width="100%" align="left">Modul PRM - Paid PRM</font></th><th width="5%"><a href="ajk_payment.php">Back</a></th></tr>
		</table>';
		$rpm = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_prm WHERE id="'.$_REQUEST['id'].'"'));
		$cost = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$rpm['id_cost'].'"'));
		$dn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn WHERE id_prm="'.$rpm['id'].'"'));
		$polis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$dn['id_nopol'].'"'));
		$remainpay = $rpm['jumlah'] - $rpm['terbayar'];
if ($rpm['jumlah'] != $rpm['terbayar']) {	$remainpayed = '<blink><font color="red">'.duit($remainpay).'</font></blink>';	}
else	{	$remainpayed = '<font color="blue">'.duit($remainpay).'</font>';	}
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
			  <tr><td colspan="2" width="10%">Company Name</td><td colspan="10">: <b>'.$cost['name'].'</b></td></tr>
			  <tr><td colspan="2" width="10%">Company Name</td><td colspan="10">: <b>'.$polis['nopol'].'</b></td></tr>
			  <tr><td colspan="2" width="10%">PRM Number</td><td>: <b>'.$rpm['id_prm'].'</b></td>
					<td width="5%">Amount : </td><td width="10%"><b>'.duit($rpm['jumlah']).'</b></td>
					<td width="4%">Paid : </td><td width="10%"><b>'.duit($rpm['terbayar']).'</b></td>
					<td width="11%">Remaining Payment : </td><td width="10%"><b>'.$remainpayed.'</b></td>
					</tr>
			  </table>';
		$cekdn = $database->doQuery('SELECT * FROM fu_ajk_dn WHERE id_prm="'.$_REQUEST['id'].'"');
echo '<table border="0" cellpadding="5" cellspacing="1" width="100%" bgcolor="#bde0e6">
			  <tr><th width="3%">No</th>
			  	  <th>Code DN</th>
			  	  <th width="10%">Total Premi</th>
			  	  <th width="10%">Date DN</th>
			  	  <th width="10%">DN Paid</th>
			  	  <th width="10%">Date DN</th>
			  	  <th width="10%">Members</th>';
while ($metdn = mysql_fetch_array($cekdn)) {
if (($no % 2) == 1)	$objlass = 'tbl-odd'; else				$objlass = 'tbl-even';
	$metpeserta = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_dn="'.$metdn['dn_kode'].'"');
	$rpeserta = mysql_num_rows($metpeserta);
if ($metdn['dn_paid']=="") {	$statdn = '<blink><font Color="red">Unpiad</font></blink>';	}
else	{	$statdn = '<font Color="blue">Paid</font>';	}
echo '<tr class="'.rowClass(++$i).'">
		  <td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
		  <td align="center">'.$metdn['dn_kode'].'</td>
		  <td align="right">'.duit($metdn['totalpremi']).'</td>
		  <td align="center">'._convertDate($metdn['tgl_dn']).'</td>
		  <td align="center">'.$statdn.'</td>
		  <td align="center">'._convertDate($metdn['tgl_dn_paid']).'</td>
		  <td align="center">'.$rpeserta.' Members</td>
	<!--	  <td align="center"><a href="ajk_prm_confr.php?r=dnpaid&id='.$_REQUEST['id'].'&iddn='.$metdn['id'].'" onclick="NewWindow(this.href,\'name\',\'350\',\'250\',\'no\');return false">SetPaid</a></td>-->
		  </tr>';
}
		echo '</table>';
		;
		break;
	case "vmember":
echo '<table border="1" cellpadding="3" cellspacing="0" width="100%">
	  <tr><th width="95%" align="left" colspan="2">Modul DN Members </font></th><th><a href="ajk_payment.php">back</a></th></tr>
	  </table>
	  <form method="post" action="">';
$fusdn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn WHERE id="'.$_REQUEST['id'].'"'));
$datamet = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_dn="'.$fusdn['dn_kode'].'"'));
$dataclient = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$fusdn['id_cost'].'"'));
$dataclientpol = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$fusdn['id_nopol'].'"'));

		//CEK DATA WILAYAH LAMA DENGAN YANG BARU
if ($fusdn['id_regional']=="") { $cregional = $fusdn['id_regional_old'];	}	else	{	$cregional = $fusdn['id_regional'];	}
if ($fusdn['id_area']=="") { $carea = $fusdn['id_area_old'];	}				else	{	$carea = $fusdn['id_area'];	}
if ($fusdn['id_cabang']=="") { $ccabang = $fusdn['id_cabang_old'];	}			else	{	$ccabang = $fusdn['id_cabang'];	}
		//CEK DATA WILAYAH LAMA DENGAN YANG BARU
echo '<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="#E2E2E2">';
if ($fusdn['id_regional']!="") {
echo '<tr><td colspan="2">Nama CLient </td><td colspan="5"> :<b> '.$dataclient['name'].'</b></td></tr>
	  <tr><td colspan="2">Nomor Polis</td><td colspan="5"> :<b> '.$dataclientpol['nopol'].'</b></td></tr>
	  <tr><td colspan="2">Regional </td><td colspan="5"> : '.$cregional.'</td></tr>
	  <tr><td colspan="2">Area</td><td colspan="5"> : '.$carea.'</td></tr>
	  <tr><td colspan="2">Cabang</td><td colspan="5"> : '.$ccabang.'</td></tr>';
}else{
echo '<tr><td colspan="2">Nama CLient </td><td colspan="5"> :<b> '.$dataclient['name'].'</b></td></tr>
	  <tr><td colspan="2">Nomor Polis</td><td colspan="5"> :<b> '.$dataclientpol['nopol'].'</b></td></tr>';
}
echo '	<tr><th width="1%" rowspan="2">No</th>
			<th width="5%" rowspan="2">SPAJ</th>
			<th width="5%" rowspan="2">No. Reg</th>
			<th rowspan="2">Nama Debitur<br /><font size="1"><i>(sesuai KTP/SIM)</i></font></th>
			<th width="1%" rowspan="2">P/W</th>
			<th colspan="2">Kartu Identitas</th>
			<th rowspan="2">Tgl Lahir</th>
			<th rowspan="2">Usia</th>
			<th colspan="4">Status Kredit</th>
			<th width="1%" rowspan="2">Bunga<br>%</th>
			<th width="1%" rowspan="2">Premi</th>
			<th colspan="3">Biaya</th>
			<th width="1%" rowspan="2">Total Premi</th>
			<th rowspan="2">Medical</th>
		</tr>
		<tr><th width="5%">Type</th>
			<th width="5%">No</th>
		<th>Kredit Awal</th>
		<th>Tenor</th>
		<th>Kredit Akhir</th>
		<th>Jumlah</th>
			<th>Adm</th>
			<th>Refund</th>
			<th>Ext. Premi</th>
		</tr>';
//if ($_REQUEST['cspaj'])		{	$satu = 'AND spaj LIKE "%' . $_REQUEST['cspaj'] . '%"';		}
//if ($_REQUEST['cnama'])		{	$dua = 'AND nama LIKE "%' . $_REQUEST['cnama'] . '%"';		}
$data = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id!="" AND id_dn="'.$fusdn['dn_kode'].'" ORDER BY cabang ASC');
while ($fudata = mysql_fetch_array($data)) {
$idmet = 1000000000 + $fudata['id'];	$idmet2 = substr($idmet, 1);

	$endkredit2=date('d-m-Y',strtotime($fudata['kredit_tgl']."+".$fudata['kredit_tenor']." Month"));//KREDIT AKHIR

	//AKHIR ASURANSI
	$findmet="/";
	$fpos = stripos($fudata['kredit_tgl'], $findmet);
if ($fpos === false) {	$cektglnya = $fudata['kredit_tgl'];	}
else	{	$riweuh = explode("/", $fudata['kredit_tgl']);
	$cektglnya = $riweuh[2].'-'.$riweuh[1].'-'.$riweuh[0];
}
	$tanggalawal=$cektglnya;
	$tanggalplus=date('d-m-Y',strtotime($tanggalawal."+".$fudata['kredit_tenor']." Month")-1);
	//AKHIR ASURANSI

	//CEK FORMAT UMUR
	$findmet="/";
	$fpos = stripos($fudata['tgl_lahir'], $findmet);
if ($fpos === false) {
	$riweuhkredit = explode("-", $fudata['kredit_tgl']);	$cektglkredit = $riweuhkredit[0].'-'.$riweuhkredit[1].'-'.$riweuhkredit[2];		//KREDIT AKHIR
	$endkredit2=date('d-m-Y',strtotime($cektglkredit."+".$fudata['kredit_tenor']." Month"));												//KREDIT AKHIR

	$riweuh = explode("-", $fudata['tgl_lahir']);			$cektglnya = $riweuh[2].'-'.$riweuh[1].'-'.$riweuh[0];							// FORMULA USIA
	$riweuh2 = explode("-", $fudata['kredit_tgl']);			$cektglnya2 = $riweuh2[2].'-'.$riweuh2[1].'-'.$riweuh2[0];							// FORMULA USIA
	$umur = ceil(((strtotime($cektglnya2) - strtotime($cektglnya)) / (60*60*24*365.2425)));												// FORMULA USIA
}	else	{
	$riweuhkredit = explode("/", $fudata['kredit_tgl']);	$cektglkredit = $riweuhkredit[0].'-'.$riweuhkredit[1].'-'.$riweuhkredit[2];		//KREDIT AKHIR
	$endkredit2=date('d-m-Y',strtotime($cektglkredit."+".$fudata['kredit_tenor']." Month"));												//KREDIT AKHIR

	$riweuh = explode("/", $fudata['tgl_lahir']);			$cektglnya = $riweuh[2].'-'.$riweuh[1].'-'.$riweuh[0];							// FORMULA USIA
	$riweuh2 = explode("/", $fudata['kredit_tgl']);			$cektglnya2 = $riweuh2[2].'-'.$riweuh2[1].'-'.$riweuh2[0];							// FORMULA USIA
	$umur = ceil(((strtotime($cektglnya2) - strtotime($cektglnya)) / (60*60*24*365.2425)));												// FORMULA USIA
}
	//CEK FORMAT UMUR

echo '<tr class="'.rowClass(++$i).'">
				<td align="center">'.++$no.'</td>
				<td align="center">'.$fudata['spaj'].'</td>
				<td>'.$idmet2.'</td>
				<td>'.$fudata['nama'].'</td>
				<td align="center">'.$fudata['gender'].'</td>
				<td width="1%" align="center">'.$fudata['kartu_type'].'</td>
				<td>'.$fudata['kartu_no'].'</td>
				<td align="center">'.$fudata['tgl_lahir'].'</td>
				<td align="center">'.$umur.'</td>
		  <td align="center">'.$fudata['kredit_tgl'].'</td>
		  <td align="center">'.$fudata['kredit_tenor'].'</td>
		  <td align="center">'.$endkredit2.'</td>
		  <td align="right">'.duit($fudata['kredit_jumlah']).'</td>
				<td align="center">'.$fudata['bunga'].'</td>
				<td align="right">'.duit($fudata['premi']).'</td>
				<td align="right">'.duit($fudata['biaya_adm']).'</td>
				<td align="right">'.duit($fudata['biaya_refund']).'</td>
				<td align="right">'.duit($fudata['ext_premi']).'</td>
				<td align="right">'.duit($fudata['totalpremi']).'</td>
				<td align="center">'.$fudata['status_medik'].'</td>
		</tr>';
	$jkredit +=$fudata['kredit_jumlah'];
	$jpremi +=$fudata['premi'];
	$jtpremi +=$fudata['totalpremi'];

}
echo '<tr><th colspan="12">Total</th>
			  	  <th>'.duit($jkredit).'</th><th>&nbsp;</th>
			  	  <th>'.duit($jpremi).'</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th>
			  	  <th>'.duit($jtpremi).'</th><th>&nbsp;</th>
			  </tr></table>';
		;
		break;
	default:
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
      <tr><th width="100%" align="left">Modul Payment</font></th></tr>
      </table>';
echo '<form method="POST" action="">
<fieldset style="padding: 2">
<legend>Searching</legend>
<table border="0" width="100%" align="center">
	<tr><td>Regional</td>
		<td>: <select id="cat" name="cat" onchange="reload(this.form)">
		<option value="">--- Select Regional ---</option>';
$excat = explode("-", $_GET['cat']);
$cat=$excat[0]; // Use this line or below line if register_global is off
if(strlen($excat[0]) > 0 and !is_numeric($excat[0])){ echo "Data Error"; exit;	}

$rreg=$database->doQuery('SELECT * FROM fu_ajk_regional WHERE id_cost="'.$met['id'].'" AND name LIKE "%'.$q['wilayah'].'%" ORDER BY name ASC');
while($Erreg = mysql_fetch_array($rreg)) {
if($Erreg['id'].'-'.$Erreg['name']==$cat){echo '<option selected value="'.$Erreg['id'].'-'.$Erreg['name'].'">'.$Erreg['name'].'</option><BR>';}
else{	echo  '<option value="'.$Erreg['id'].'-'.$Erreg['name'].'">'.$Erreg['name'].'</option>';	}
}
echo '</select></td>
			<td width="10%">DN Date </td>
			<td>: ';print initCalendar();	print calendarBox('rdns', 'triger', $_REQUEST['rdns']);echo ' s/d ';
					print initCalendar();	print calendarBox('rdne', 'triger1', $_REQUEST['rdne']); echo '</td>

			<td width="11%">Nomor DN</td><td>: <input type="text" name="dns" value="'.$_REQUEST['dns'].'"> s/d <input type="text" name="dne" value="'.$_REQUEST['dne'].'">';
/*
echo '		<option value="">--- Select DN ---</option>';
$fusdn = $database->doQuery('SELECT fu_ajk_dn.dn_kode FROM fu_ajk_dn WHERE id_cost="'.$met['id'].'" GROUP BY dn_kode ORDER BY dn_kode DESC, input_time DESC');	//ERROR DATA TIDAK TAMPIL KARENA DATA TERLAU BANYA
$fusdn = $database->doQuery('SELECT fu_ajk_dn.dn_kode FROM fu_ajk_dn WHERE id_cost="'.$met['id'].'" GROUP BY dn_kode ORDER BY dn_kode DESC, input_time DESC LIMIT 20');	// DI BUAT LIMIT UNTUK COMBOBOXNYA
while ($metsdn = mysql_fetch_array($fusdn)) {
	echo '	<option value="'.$metsdn['dn_kode'].'"'._selected($_REQUEST['dn_kode'], $metsdn['dn_kode']).'>'.$metsdn['dn_kode'].'</option>';
}
		echo '</select> s/d <select id="dne" name="dne"><option value="">--- Select DN---</option>';
$fusdn2 = $database->doQuery('SELECT fu_ajk_dn.dn_kode FROM fu_ajk_dn WHERE id_cost="'.$met['id'].'" GROUP BY dn_kode ORDER BY dn_kode DESC, input_time DESC');
$fusdn2 = $database->doQuery('SELECT fu_ajk_dn.dn_kode FROM fu_ajk_dn WHERE id_cost="'.$met['id'].'" GROUP BY dn_kode ORDER BY dn_kode DESC, input_time DESC LIMIT 20');
while ($metsdn2 = mysql_fetch_array($fusdn2)) {
	echo '	<option value="'.$metsdn2['dn_kode'].'"'._selected($_REQUEST['dn_kode'], $metsdn2['dn_kode']).'>'.$metsdn2['dn_kode'].'</option> ';
}
*/
echo '</td>
		</tr>
		<tr><td>Branch</td>
			<td>: ';
if(isset($cat) and strlen($cat) > 0){
$quer=$database->doQuery('SELECT * FROM fu_ajk_cabang where id_reg="'.$excat[0].'" ORDER BY id ASC');
}else{$quer=$database->doQuery("SELECT * FROM fu_ajk_cabang ORDER BY id ASC"); }
echo '<select name="subcat"><option value="">---Select Policy---</option>';
while($noticia = mysql_fetch_array($quer)) {
echo  '<option value='.$noticia['name'].'>'.$noticia['name'].'</option>';
}
echo '</select></td><td>Payment Date</td>
			<td>: ';print initCalendar();	print calendarBox('rpays', 'triger2', $_REQUEST['rpays']); echo ' s/d ';
					print initCalendar();	print calendarBox('rpaye', 'triger3', $_REQUEST['rpaye']); echo '</td>

			<td>Payment Status</td>
			<td>: <select id="rstat" name="rstat">
					<option value="">--- Pilih Status ---</option>
					<option value="Paid">Paid</option>
					<option value="UnPaid">UnPaid</option>
					</select></td>
		</tr>
		<tr><td colspan="6" align="center"><input type="submit" name="button" value="Cari" class="button"></td></tr>';
/*PENDING UNTUK PRINT ALL
if ($_REQUEST['dns'] > $_REQUEST['dne'])
{	$valcari = '<font color="red"><center>Data DN <b>'.$_REQUEST['dns'].'</b> lebih besar dari data <b>'.$_REQUEST['dne'].'</b>, Pencarian ditolak.!</center></font>';	}
elseif ($_REQUEST['dns'] < $_REQUEST['dne']){
	$valcari = '<input type="submit" name="formSubmit" value="Download" ><a href="ajk_report.php?fu=ajkpdfAll&rdns='.$_REQUEST['rdns'].'&rdne='.$_REQUEST['rdne'].'&rpays='.$_REQUEST['rpays'].'&rpaye='.$_REQUEST['rpaye'].'&rreg='.$_REQUEST['rreg'].'&rcabang='.$_REQUEST['rcabang'].'&rdnno='.$_REQUEST['rdnno'].'&rstat='.$_REQUEST['rstat'].'&dns='.$_REQUEST['dns'].'&dne='.$_REQUEST['dne'].'&" target="_blank"><img src="image/print.png" width="30"><br />Print</a>';
}else{	$valcari ='';	}
PENDING UNTUK PRINT ALL*/
if ($_REQUEST['dns'] > $_REQUEST['dne'])
{	$valcari = '<font color="red"><center>Data DN <b>'.$_REQUEST['dns'].'</b> lebih besar dari data <b>'.$_REQUEST['dne'].'</b>, Pencarian ditolak.!</center></font>';	}
elseif ($_REQUEST['dns'] < $_REQUEST['dne']){
//PENDING DULU !!!!!	$valcari = '<input type="submit" name="formSubmit" value="Download" ><a href="ajk_report.php?fu=ajkpdfAll&rdns='.$_REQUEST['rdns'].'&rdne='.$_REQUEST['rdne'].'&rpays='.$_REQUEST['rpays'].'&rpaye='.$_REQUEST['rpaye'].'&rreg='.$_REQUEST['rreg'].'&rcabang='.$_REQUEST['rcabang'].'&rdnno='.$_REQUEST['rdnno'].'&rstat='.$_REQUEST['rstat'].'&dns='.$_REQUEST['dns'].'&dne='.$_REQUEST['dne'].'&" target="_blank"><img src="image/print.png" width="30"><br />Print</a>';
}else{	$valcari ='';	}
echo '<tr><td colspan="6" align="right">'.$valcari.'</td></tr>
	</table>
	</fieldset></form>';

echo '<table border="0" width="100%" cellpadding="5" cellspacing="1" bgcolor="#bde0e6">
	  <tr><th width="1%">No</th>
		  <th>Number DN</th>
		  <th width="12%">Peserta</th>
		  <th width="12%">DN Date</th>
		  <th width="10%">Status</th>
		  <th width="12%">Payment Date</th>
		  <th width="10%">Branch</th>
		  <th width="10%">Regional</th>
		  <th width="5%">Opt</th>
	  </tr>';
/*
echo $excat[0].'<br />';
echo $excat[1].'<br />';
*/
if ($_REQUEST['rdns']!='' AND $_REQUEST['rdne']!='')	{	$satu= 'AND tgl_createdn BETWEEN \''.$_REQUEST['rdns'].'\' AND \''.$_REQUEST['rdne'].'\'';	}
if ($_REQUEST['rpays']!='' AND $_REQUEST['rpaye']!='')	{	$dua= 'AND tgl_dn_paid BETWEEN \''.$_REQUEST['rpays'].'\' AND \''.$_REQUEST['rpaye'].'\'';	}
if ($_REQUEST['rstat'])									{	$tiga = 'AND dn_status LIKE "%' .$_REQUEST['rstat'] . '%"';	}
if ($excat[1])											{	$empat = 'AND id_regional LIKE "%' .  $excat[1] . '%"';		}
if ($_REQUEST['subcat'])								{	$lima = 'AND id_cabang LIKE "%' . $_REQUEST['subcat'] . '%"';		}
if ($_REQUEST['rdnno'])									{	$enam = 'AND dn_kode LIKE "%' . $_REQUEST['rdnno'] . '%"';		}
if ($_REQUEST['dns']!='' AND $_REQUEST['dne']!='')		{	$tujuh = 'AND dn_kode BETWEEN "'.$_REQUEST['dns'].'" AND "'.$_REQUEST['dne'].'"';		}

define('_NEXT', '<img src="imags/right_arrow.gif" border="0">');
define('_PREV', '<img src="image/left_arrow.gif" border="0">');
if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}

$metdn = $database->doQuery('SELECT * FROM fu_ajk_dn WHERE id!="" AND id_regional LIKE "%'.$q['wilayah'].'%" AND id_cost="'.$q['id_cost'].'" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' '.$tujuh.' AND del !=null ORDER BY input_time DESC, id_cabang ASC, id DESC LIMIT ' . $m . ' , 25');
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_dn WHERE id != "" AND id_regional LIKE "%'.$q['wilayah'].'%" AND id_cost="'.$q['id_cost'].'" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' '.$tujuh.''));
$totalRows = $totalRows[0];

$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while ($rmetdn = mysql_fetch_array($metdn)) {
	$idcabang = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE name="'.$rmetdn['id_cabang'].'"'));
if ($rmetdn['tgl_dn_paid']=="") {	$dnpaid = '<font color="red"><blink>Unpaid</blink></font>';	}
else	{	$dnpaid = '<font color="blue">Paid</font>';	}

$metcbg = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_dn="'.$rmetdn['dn_kode'].'" AND id_cost="'.$q['id_cost'].'"'));
$jpeserta = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_dn="'.$rmetdn['dn_kode'].'" AND id_cost="'.$q['id_cost'].'"');
$metjpeserta = mysql_num_rows($jpeserta);

	//CEK FORMAT TGL DN
	$findmet="/";
	$fpos = stripos($rmetdn['tgl_createdn'], $findmet);
if ($fpos === false) {
	$riweuh = explode("-", $rmetdn['tgl_createdn']);
	$cektgldn = $riweuh[2].'-'.$riweuh[1].'-'.$riweuh[0];

}	else	{
	$riweuh = explode("/", $rmetdn['tgl_createdn']);
	$cektgldn = $riweuh[0].'-'.$riweuh[1].'-'.$riweuh[2];

}
	//CEK FORMAT TGL DN

//CEK DATA WILAYAH LAMA DENGAN YANG BARU
if ($rmetdn['id_regional']=="") { $cregional = $rmetdn['id_regional_old'];	}
else	{	$cregional = $rmetdn['id_regional'];	}

if ($rmetdn['id_cabang']=="") { $ccabang = $rmetdn['id_cabang_old'];	}
else	{	$ccabang = $rmetdn['id_cabang'];	}
//CEK DATA WILAYAH LAMA DENGAN YANG BARU

echo '<tr class="'.rowClass(++$i).'">
		  <td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
		  <td><input name="ajkdn[]" type="hidden" id="checkbox[]" value="'.$rmetdn['id'].'" checked><b><a href="ajk_payment.php?fu=vmember&id='.$rmetdn['id'].'">'.$rmetdn['dn_kode'].'</a></b></td>
		  <td align="center">'.$metjpeserta.' Data</td>
		  <td align="center">'.$cektgldn.'</td>
		  <td align="center">'.$dnpaid.'</td>
		  <td align="center">'.$rmetdn['tgl_dn_paid'].'</td>
		  <td><b>('.$idcabang['nmr_cbg'].')</b> - '.$ccabang.'</td>
		  <td>'.$cregional.'</td>
		  <td align="center"><a href="ajk_report.php?fu=ajkpdfinvdn&id='.$rmetdn['id'].'" title="DN Member '.$rmetdn['dn_kode'].'" target="_blank"><img src="image/dninvoice.png" width="21"></a></td>
		  </tr>';
}
echo '<tr><td colspan="8">';
echo createPageNavigations($file = 'ajk_payment.php?rdns='.$_REQUEST['rdns'].'&rdne='.$_REQUEST['rdne'].'&rpays='.$_REQUEST['rpays'].'&rpaye='.$_REQUEST['rpaye'].'&cat='.$_REQUEST['cat'].'&subcat='.$_REQUEST['subcat'].'&rdnno='.$_REQUEST['rdnno'].'&rstat='.$_REQUEST['rstat'].'&dns='.$_REQUEST['rdns'].'&dne='.$_REQUEST['rdne'].'&', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
echo '<b>Total Data DN: <u>' . $totalRows . '</u></b></td></tr>';
echo '</table>';
		;
} // switch
?>
<SCRIPT language=JavaScript>
function reload(form)
{
	var val=form.cat.options[form.cat.options.selectedIndex].value;
	self.location='ajk_payment.php?cat=' + val;
}
</script>