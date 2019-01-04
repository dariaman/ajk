<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("includes/functions.php");
connect();

if (session_is_registered('nm_user')) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));
	$qsescost=mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$q['id_cost'].'"'));
	$qsespolis=mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id_cost="'.$qsescost['id'].'"'));
}
$futgl = date("Y-m-d g:i:a");
switch ($_REQUEST['r']) {
	case "viewall":
echo '<fieldset style="padding: 1">
	<legend align="center">S e a r c h</legend>
	<table border="0" width="40%" cellpadding="0" cellspacing="0">
		<form method="post" action="">
		<tr>';
		$reg = $database->doQuery('SELECT * FROM fu_ajk_regional WHERE id_cost="'.$q['id_cost'].'" GROUP BY name ORDER BY name ASC');
		echo '<td align="right">Regional :</td>
      				<td><select size="1" name="regional">
	   			<option value="">- - - Pilih Regional - - -</option>';
while ($creg = mysql_fetch_array($reg)) {	echo '<option value="'.$creg['name'].'">'.$creg['name'].'</option>';	}
		echo '</select></td>
      		<td align="right">Nama :</td><td><input type="text" name="snama" value="'.$_REQUEST['snama'].'"></td>
			<td align="center" colspan="2"><input type="submit" name="button" value="Cari" class="button"></td>
			</tr>
			</form></table></fieldset>';

echo '<form method="post" action="multiply_peserta.php?r=approve" onload ="onbeforeunload">
	  <table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#bde0e6">
	<tr><th width="1%" rowspan="2"></th>
		<th width="1%" rowspan="2"><input type="checkbox" id="selectall"/></th>
		<th width="1%" rowspan="2">No</th>
		<th width="5%" rowspan="2">SPAJ</th>
		<th rowspan="2">Nama Debitur<br /><font size="1"><i>(sesuai KTP/SIM)</i></font></th>
		<th width="1%" rowspan="2">P/W</th>
		<th colspan="2">Kartu Identitas</th>
		<th rowspan="2">Tgl Lahir</th>
		<th colspan="3">Status Kredit</th>
		<th width="1%" rowspan="2">Bunga<br>%</th>
		<th colspan="2">Biaya</th>
		<th width="1%" rowspan="2">Tinggi Badan</th>
		<th width="1%" rowspan="2">Berat Badan</th>
		<th colspan="4">Pernyataan</th>
		<th rowspan="2">Keterangan</th>
		<th rowspan="2">Cabang</th>
		<th rowspan="2">Area</th>
		<th rowspan="2">Regional</th>
		<th rowspan="2">Confirm By</th>
	</tr>
	<tr><th width="5%">Type</th>
		<th width="5%">No</th>
		<th>Tgl Kredit</th>
		<th>Jumlah</th>
		<th>Tenor</th>
		<th>Adm</th>
		<th>Refund</th>
		<th>P1</th>
		<th>P2</th>
		<th>P3</th>
		<th>P4</th>
	</tr>';

if ($_REQUEST['regional'])		{	$dua = 'AND regional LIKE "%' . $_REQUEST['regional'] . '%"';		}
if ($_REQUEST['snama'])		{	$tiga = 'AND nama LIKE "%' . $_REQUEST['snama'] . '%"';		}

if ($q['status']==1)
//$data = $database->doQuery('SELECT * FROM v_fu_ajk_peserta_tempf WHERE id_dn="" AND id_cost="'.$q['id_cost'].'" AND status_peserta IS NULL '.$dua.' '.$tiga.' ORDER BY input_by, input_time ASC');	}
{	$data = $database->doQuery('SELECT * FROM v_fu_ajk_peserta_tempf WHERE id_dn="" AND id_cost="'.$q['id_cost'].'" AND status_peserta IS NULL '.$dua.' '.$tiga.' ORDER BY input_by, input_time ASC');	}
else
{	$data = $database->doQuery('SELECT * FROM v_fu_ajk_peserta_tempf WHERE input_by="'.$_SESSION['nm_user'].'" AND id_dn="" AND id_cost="'.$q['id_cost'].'" AND status_peserta IS NULL '.$dua.' '.$tiga.' ORDER BY input_by ASC');	}
while ($fudata = mysql_fetch_array($data)) {
if (($no % 2) == 1)	$objlass = 'tbl-odd'; else $objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	  <td><a title="hapus data peserta" href="multiply_peserta.php?r=hapuspeserta&IDp='.$fudata['nama'].'-met-'.$fudata['tgl_lahir'].'-met-'.$fudata['kredit_jumlah'].'-met-'.$fudata['kartu_no'].'" onClick="if(confirm(\'Apakah anda yakin menghapus data peserta ini ?\')){return true;}{return false;}"><img src="image/delete.gif" border="0"></a></td>
	  <td align="center"><input type="checkbox" class="case" name="nama[]" value="'.$fudata['nama'].'-met-'.$fudata['tgl_lahir'].'-met-'.$fudata['kredit_jumlah'].'-met-'.$fudata['kartu_no'].'"></td>
	  <td align="center">'.++$no.'</td>
	  <td align="center">'.$fudata['spaj'].'</td>
	  <td>'.$fudata['nama'].'</td>
	  <td align="center">'.$fudata['gender'].'</td>
	  <td width="1%" align="center">'.$fudata['kartu_type'].'</td>
	  <td>'.$fudata['kartu_no'].'</td>
	  <td align="center">'.$fudata['tgl_lahir'].'</td>
	  <td align="center">'.$fudata['kredit_tgl'].'</td>
	  <td align="right">'.duit($fudata['kredit_jumlah']).'</td>
	  <td align="center">'.$fudata['kredit_tenor'].'</td>
	  <td align="center">'.$fudata['bunga'].'</td>
	  <td align="right">'.duit($fudata['biaya_adm']).'</td>
	  <td align="right">'.duit($fudata['biaya_refund']).'</td>
	  <td align="center">'.$fudata['badant'].'</td>
	  <td align="center">'.$fudata['badanb'].'</td>
	  <td align="center">'.$fudata['statement1'].'</td>
	  <td align="center">'.$fudata['statement2'].'</td>
	  <td align="center">'.$fudata['statement3'].'</td>
	  <td align="center">'.$fudata['statement4'].'</td>
	  <td align="center">'.$fudata['ket'].'</td>
	  <td align="center">'.$fudata['cabang'].'</td>
	  <td align="center">'.$fudata['area'].'</td>
	  <td align="center">'.$fudata['regional'].'</td>
	  <td align="center"><font color="blue"><b>'.$fudata['input_by'].'</b></font></td>
	  </tr>';
}
if ($q['status']=="2") {	echo '<tr><td colspan="27" align="center">&nbsp;</td></tr>';	}
else{
	$el = $database->doQuery('SELECT * FROM v_fu_ajk_peserta_tempf WHERE id_cost="'.$q['id_cost'].'" AND id_dn=""');
	$met = mysql_num_rows($el);
	if ($met > 0) {
		echo '<tr><td colspan="27" align="center"><a title="approve data peserta" href="multiply_peserta.php?r=approve" onClick="if(confirm(\'Apakah anda sudah yakin dengan semua data peserta ini ?\')){return true;}{return false;}"><input type="submit" name="Approve" Value="Approve"></a>
											  <a href="index.php">Cancel</a></td></tr>';
	}else{
		echo '<tr><th colspan="27" align="center"><blink><b><font color="blue">Tidak ada data peserta baru yang harus di validasi. !!!</font></b></blink></th></tr>';
	}
}
		echo '</table>';
		;
		break;
	case "viewallclaim":
echo '<fieldset style="padding: 1">
	<legend align="center">S e a r c h</legend>
	<table border="0" width="40%" cellpadding="0" cellspacing="0">
		<form method="post" action="">
		<tr>';
		$reg = $database->doQuery('SELECT * FROM fu_ajk_regional GROUP BY name ORDER BY name ASC');
		echo '<td align="right">Regional :</td>
      				<td><select size="1" name="regional">
	   			<option value="">- - - Pilih Regional - - -</option>';
while ($creg = mysql_fetch_array($reg)) {	echo '<option value="'.$creg['name'].'">'.$creg['name'].'</option>';	}
		echo '</select></td>
      		<td align="right">Nama :</td><td><input type="text" name="snama" value="'.$_REQUEST['snama'].'"></td>
			<td align="center" colspan="2"><input type="submit" name="button" value="Cari" class="button"></td>
			</tr>
			</form></table></fieldset>';

if ($_REQUEST['regional'])		{	$dua = 'AND regional LIKE "%' . $_REQUEST['regional'] . '%"';		}
if ($_REQUEST['snama'])		{	$tiga = 'AND nama LIKE "%' . $_REQUEST['snama'] . '%"';		}

echo '<form method="post" action="multiply_peserta.php?r=approve">
	  <table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#bde0e6">
	<tr><th width="1%" rowspan="2">No</th>
		<th width="5%" rowspan="2">SPAJ</th>
		<th rowspan="2">Nama Debitur<br /><font size="1"><i>(sesuai KTP/SIM)</i></font></th>
		<th width="1%" rowspan="2">P/W</th>
		<th colspan="2">Kartu Identitas</th>
		<th rowspan="2">Tgl Lahir</th>
		<th colspan="3">Status Kredit</th>
		<th width="1%" rowspan="2">Bunga<br>%</th>
		<th colspan="2">Biaya</th>
		<th width="1%" rowspan="2">Tinggi Badan</th>
		<th width="1%" rowspan="2">Berat Badan</th>
		<th colspan="4">Pernyataan</th>
		<th rowspan="2">Keterangan</th>
		<th rowspan="2">Type Claim</th>
		<th rowspan="2">Cabang</th>
		<th rowspan="2">Area</th>
		<th rowspan="2">Regional</th>
		<th rowspan="2">Confirm By</th>
	</tr>
	<tr><th width="5%">Type</th>
		<th width="5%">No</th>
		<th>Tgl Kredit</th>
		<th>Jumlah</th>
		<th>Tenor</th>
		<th>Adm</th>
		<th>Refund</th>
		<th>P1</th>
		<th>P2</th>
		<th>P3</th>
		<th>P4</th>
	</tr>';
if ($q['status']==1)	{	$data = $database->doQuery('SELECT * FROM v_fu_ajk_peserta_tempf WHERE id_dn="" AND id_cost="'.$q['id_cost'].'" AND status_peserta!="" '.$satu.' '.$dua.' '.$tiga.' ORDER BY input_by ASC');	}
else	{	$data = $database->doQuery('SELECT * FROM v_fu_ajk_peserta_tempf WHERE input_by="'.$_SESSION['nm_user'].'" AND id_dn="" AND id_cost="'.$q['id_cost'].'" AND status_peserta!="" '.$satu.' '.$dua.' '.$tiga.' ORDER BY input_by ASC');	}
while ($fudata = mysql_fetch_array($data)) {
if (($no % 2) == 1)	$objlass = 'tbl-odd'; else				$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	  <td align="center">'.++$no.'</td>
	  <td align="center">'.$fudata['spaj'].'</td>
	  <td>'.$fudata['nama'].'</td>
	  <td align="center">'.$fudata['gender'].'</td>
	  <td width="1%" align="center">'.$fudata['kartu_type'].'</td>
	  <td>'.$fudata['kartu_no'].'</td>
	  <td align="center">'.$fudata['tgl_lahir'].'</td>
	  <td align="center">'.$fudata['kredit_tgl'].'</td>
	  <td align="right">'.duit($fudata['kredit_jumlah']).'</td>
	  <td align="center">'.$fudata['kredit_tenor'].'</td>
	  <td align="center">'.$fudata['bunga'].'</td>
	  <td align="right">'.duit($fudata['biaya_adm']).'</td>
	  <td align="right">'.duit($fudata['biaya_refund']).'</td>
	  <td align="center">'.$fudata['badant'].'</td>
	  <td align="center">'.$fudata['badanb'].'</td>
	  <td align="center">'.$fudata['statement1'].'</td>
	  <td align="center">'.$fudata['statement2'].'</td>
	  <td align="center">'.$fudata['statement3'].'</td>
	  <td align="center">'.$fudata['statement4'].'</td>
	  <td align="center">'.$fudata['ket'].'</td>
	  <td align="center">'.$fudata['status_peserta'].'</td>
	  <td align="center">'.$fudata['cabang'].'</td>
	  <td align="center">'.$fudata['area'].'</td>
	  <td align="center">'.$fudata['regional'].'</td>
	  <td align="center"><font color="blue"><b>'.$fudata['input_by'].'</b></font></td>
	  </tr>';
}
if ($q['status']=="2") {
	echo '<tr><td colspan="27" align="center">&nbsp;</td></tr>';
}else{
	$el = $database->doQuery('SELECT * FROM v_fu_ajk_peserta_tempf WHERE id_cost="'.$q['id_cost'].'" AND status_peserta!=""');
	$met = mysql_num_rows($el);
if ($met > 0) {
	echo '<tr><td colspan="27" align="center"><a href="multiply_peserta.php?r=approve&val=pclaim" onClick="if(confirm(\'Apakah anda sudah yakin dengan semua data peserta ini ?\')){return true;}{return false;}">Approve</a></td></tr>';
}else{
	echo '<tr><th colspan="27" align="center"><blink><b><font color="blue">Tidak ada data peserta restruktur, topup atau refund yang harus di validasi. !!!</font></b></blink></th></tr>';
}
}
		echo '</table>';
		;
		break;

	case "cancell":
		$met = $database->doQuery('DELETE FROM fu_ajk_peserta_tempf WHERE namafile="'.$_REQUEST['fileclient'].'"');
		header("location:multiply_peserta.php");
		;
		break;

	case "approve":
if (!$_REQUEST['nama']) {
echo '<center><font color=red><blink>Tidak ada data yang di pilih, silahkan ceklist data yang akan di Approve. !</blink></font><br/>
	  <a href="multiply_peserta.php?r=viewall">Kembali Ke Halaman Approve Peserta</a></center>';
}else{
$c = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE name="'.$qsescost['name'].'"'));
foreach($_REQUEST['nama'] as $k => $val){
$vall = explode("-met-", $val);		//EXPLODE DATA BERDASARKAN CHEKLIST//

if ($_REQUEST['val']=="pclaim") {	$r = $database->doQuery('SELECT * FROM v_fu_ajk_peserta_tempf WHERE id_cost="'.$c['id'].'" AND status_peserta!="" AND nama="'.$vall[0].'" AND tgl_lahir="'.$vall[1].'" AND kredit_jumlah="'.$vall[2].'" AND kartu_no="'.$vall[3].'"');	}
else	{	$r = $database->doQuery('SELECT * FROM v_fu_ajk_peserta_tempf WHERE id_cost="'.$c['id'].'" AND status_peserta IS NULL AND nama="'.$vall[0].'" AND tgl_lahir="'.$vall[1].'" AND kredit_jumlah="'.$vall[2].'" AND kartu_no="'.$vall[3].'"');	}
while ($rr = mysql_fetch_array($r)) {
	//CEK FORMAT TGL KREDIT
	$findmets="/";
	$fpos = stripos($rr['kredit_tgl'], $findmets);
if ($fpos === false) {
	$riweuhkredit = explode("-", $rr['kredit_tgl']);	$cektglkredit = $riweuhkredit[0].'/'.$riweuhkredit[1].'/'.$riweuhkredit[2];		//KREDIT AKHIR
	$endkredit2=date('d/m/Y',strtotime($cektglkredit."+".$rr['kredit_tenor']." Month"));												//KREDIT AKHIR
}	else	{
	$endkredit2=date('d/m/Y',strtotime($rr['kredit_tgl']."+".$rr['kredit_tenor']." Month"));											//KREDIT AKHIR
}

	//CEK FORMAT UMUR
	$findmet="/";
	$fpos = stripos($rr['tgl_lahir'], $findmet);
if ($fpos === false) {
	$riweuhkredit = explode("-", $rr['kredit_tgl']);	$cektglkredit = $riweuhkredit[0].'-'.$riweuhkredit[1].'-'.$riweuhkredit[2];		//KREDIT AKHIR
	$endkredit2=date('d-m-Y',strtotime($cektglkredit."+".$rr['kredit_tenor']." Month"));												//KREDIT AKHIR

	$riweuh = explode("-", $rr['tgl_lahir']);			$cektglnya = $riweuh[0].'-'.$riweuh[1].'-'.$riweuh[2];							// FORMULA USIA
	$riweuh2 = explode("-", $rr['kredit_tgl']);			$cektglnya2 = $riweuh2[0].'-'.$riweuh2[1].'-'.$riweuh2[2];							// FORMULA USIA
	$umur = ceil(((strtotime($cektglnya2) - strtotime($cektglnya)) / (60*60*24*365.2425)));												// FORMULA USIA
}	else	{
	$riweuhkredit = explode("/", $rr['kredit_tgl']);	$cektglkredit = $riweuhkredit[0].'-'.$riweuhkredit[1].'-'.$riweuhkredit[2];		//KREDIT AKHIR
	$endkredit2=date('d-m-Y',strtotime($cektglkredit."+".$rr['kredit_tenor']." Month"));												//KREDIT AKHIR

	$riweuh = explode("/", $rr['tgl_lahir']);			$cektglnya = $riweuh[0].'-'.$riweuh[1].'-'.$riweuh[2];							// FORMULA USIA
	$riweuh2 = explode("/", $rr['kredit_tgl']);			$cektglnya2 = $riweuh2[0].'-'.$riweuh2[1].'-'.$riweuh2[2];							// FORMULA USIA
	$umur = ceil(((strtotime($cektglnya2) - strtotime($cektglnya)) / (60*60*24*365.2425)));												// FORMULA USIA
}
	//CEK FORMAT UMUR

//DISCOUNT PREMI
$cekpolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$rr['id_polis'].'"'));
if ($cekpolis['typeRate']=="Menurun") {
$RTenor = $rr['kredit_tenor'] / 12;		$tenortunggal = ceil($RTenor);
$cekrate = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi_tunggal WHERE id_cost="'.$rr['id_cost'].'" AND id_polis="'.$rr['id_polis'].'" AND usia="'.$umur.'" AND tenorthn="'.$tenortunggal.'"'));		// RATE PREMI
}else{
$cekrate = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$rr['id_cost'].'" AND id_polis="'.$rr['id_polis'].'" AND tenor="'.$rr['kredit_tenor'].'"'));		// RATE PREMI
}
//DISCOUNT PREMI
	$premi = $rr['kredit_jumlah'] * $cekrate['rate'] / 1000;
	$diskonpremi = $premi * ($cekpolis['discount'] /100);			//diskon premi
	$tpremi = $premi - $diskonpremi;								//totalpremi														// RATE PREMI

	$tb = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_badan WHERE sex = "'.$rr['gender'].'" AND w_from <= "'.$rr['badanb'].'" AND w_to >= "'.$rr['badanb'].'" AND h_from <= "'.$rr['badant'].'" AND h_to >= "'.$rr['badant'].'"'));
	$extrapremi = ($premi * $tb['extrapremi']) / 100;

	$mettotal = $tpremi + $extrapremi + $rr['biaya_adm'] + $rr['biaya_refund'];														//TOTAL


	// CEK STATUS MEDICAL//
if ($rr['statement1']=="") {	$p1 = "Y";	}else{	$p1 = $rr['statement1'];	}
if ($rr['statement2']=="") {	$p2 = "T";	}else{	$p2 = $rr['statement2'];	}
if ($rr['statement3']=="") {	$p3 = "T";	}else{	$p3 = $rr['statement3'];	}
if ($rr['statement4']=="") {	$p4 = "T";	}else{	$p4 = $rr['statement4'];	}

if ($umur<=55 AND $rr['kredit_jumlah'] <=10000000) {
	$status_medik ='NM';
	$status_aktif ='aktif';
}
else
{	$medik = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_medical WHERE '.$umur.' BETWEEN age_from AND age_to AND '.$rr['kredit_jumlah'].' BETWEEN si_from AND si_to'));
	$status_medik =$medik['type_medical'];
	$status_aktif ='pending';
}
	// CEK STATUS MEDICAL//
$tgldef = explode("/", $rr['kredit_tgl']);	$tgldb = $tgldef[2].'-'.$tgldef[1].'-'.$tgldef[0];			//set tgl dlm format sql
$tgldef2 = explode("-", $endkredit2);	$tgldb2 = $tgldef[2].'-'.$tgldef[1].'-'.$tgldef[0];			//set tgl dlm format sql

$met = $database->doQuery('INSERT INTO fu_ajk_peserta SET no_urut="'.$rr['no_urut'].'",
														  id_dn="",
														  id_cost="'.$rr['id_cost'].'",
														  id_polis="'.$rr['id_polis'].'",
														  namafile="'.$rr['namafile'].'",
														  spaj="'.$rr['spaj'].'",
														  nama="'.$rr['nama'].'",
														  gender="'.$rr['gender'].'",
														  kartu_type="'.$rr['kartu_type'].'",
														  kartu_no="'.$rr['kartu_no'].'",
														  kartu_period="'.$rr['kartu_period'].'",
														  tgl_lahir="'.$rr['tgl_lahir'].'",
														  usia="'.$umur.'",
														  kredit_tgl="'.$rr['kredit_tgl'].'",
														  vkredit_tgl="'.$tgldb.'",
														  bln="'.$tgldef[1].'",
														  thn="'.$tgldef[2].'",
														  kredit_jumlah="'.$rr['kredit_jumlah'].'",
														  kredit_tenor="'.$rr['kredit_tenor'].'",
														  kredit_akhir="'.$endkredit2.'",
														  vkredit_akhir="'.$tgldb2.'",
														  premi="'.$premi.'",
														  disc_premi="'.$diskonpremi.'",
														  bunga="'.$rr['bunga'].'",
														  biaya_adm="'.$rr['biaya_adm'].'",
														  biaya_refund="'.$rr['biaya_refund'].'",
														  ext_premi="'.$extrapremi.'",
														  totalpremi="'.$mettotal.'",
														  badant="'.$rr['badant'].'",
														  badanb="'.$rr['badanb'].'",
														  statement1="'.$rr['statement1'].'",
														  p1_ket="'.$rr['p1_ket'].'",
														  statement2="'.$rr['statement2'].'",
														  p2_ket="'.$rr['p2_ket'].'",
														  statement3="'.$rr['statement3'].'",
														  p3_ket="'.$rr['p3_ket'].'",
														  statement4="'.$rr['statement4'].'",
														  p4_ket="'.$rr['p4_ket'].'",
														  ket="'.$rr['ket'].'",
														  status_medik="'.$status_medik.'",
														  status_bayar="'.$rr['status_bayar'].'",
														  status_aktif="'.$status_aktif.'",
														  status_peserta="'.$rr['status_peserta'].'",
														  regional ="'.$rr['regional'].'",
														  area ="'.$rr['area'].'",
														  cabang ="'.$rr['cabang'].'",
														  input_by ="'.$rr['input_by'].'- '.$q['nm_user'].'",
														  input_time ="'.$rr['input_time'].'"');
}
if ($_REQUEST['val']=="pclaim") {
	$cha = $database->doQuery('DELETE FROM fu_ajk_peserta_tempf WHERE id_cost="'.$c['id'].'" AND status_peserta!="" AND nama="'.$vall[0].'" AND tgl_lahir="'.$vall[1].'" AND kredit_jumlah="'.$vall[2].'" AND kartu_no="'.$vall[3].'"');
}	else	{
	$cha = $database->doQuery('DELETE FROM fu_ajk_peserta_tempf WHERE id_cost="'.$c['id'].'" AND status_peserta IS NULL AND nama="'.$vall[0].'" AND tgl_lahir="'.$vall[1].'" AND kredit_jumlah="'.$vall[2].'" AND kartu_no="'.$vall[3].'"');
}

}
$pecahtgl = explode(" ", $futgl);	$pecahlagi = explode("-", $pecahtgl[0]);	$tglnya = $pecahlagi[2].'-'.$pecahlagi[1].'-'.$pecahlagi[0].' '.$pecahtgl[1];				//PECAH TANGGAL

$Rmail = $database->doQuery('SELECT * FROM pengguna WHERE wilayah="'.$q['wilayah'].'" AND status=1');
while ($eRmail = mysql_fetch_array($Rmail)) {	$metMail .=$eRmail['email'].', ';	}

$to = $metMail.''.$q['email'].','."sumiyanto@relife.co.id, arief.kurniawan@relife.co.id";
$subject = 'Data telah di Approve By Supervisor';
$message = '<html><head><title>Data Peserta Sudah di Approve oleh '.$q['nm_lengkap'].'</title></head>
			<body><table><tr><th>Data Peserta sudah di Approve oleh <b>'.$_SESSION['nm_user'].'</b> pada tanggal '.$tglnya.'</tr></table></body>
			</html>';
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
// Additional headers
$headers .= 'From: '.$q['email'].'' . "\r\n";
$headers .= 'Cc:  relife-ajk@relife.co.id ' . "\r\n";
//	$headers .= 'Bcc: k@example.com' . "\r\n";
mail($to, $subject, $message, $headers);

//echo '<center>Approve oleh <b>'.$_SESSION['nm_user'].'</b> telah berhasil, Data segera kami proses oleh Admin Recapital<br /><a href="multiply_peserta.php?r=viewall">Kembali Ke Halaman Utama</a></center>';
}
		;
		break;

	case "hapuspeserta":
$vall = explode("-met-", $_REQUEST['IDp']);		//EXPLODE DATA BERDASARKAN CHEKLIST//
if ($_REQUEST['val']=="pclaim") {	$r = $database->doQuery('SELECT * FROM v_fu_ajk_peserta_tempf WHERE id_cost="'.$qsescost['id'].'" AND status_peserta!="" AND nama="'.$vall[0].'" AND tgl_lahir="'.$vall[1].'" AND kredit_jumlah="'.$vall[2].'" AND kartu_no="'.$vall[3].'"');	}
else	{	$r = $database->doQuery('SELECT * FROM v_fu_ajk_peserta_tempf WHERE id_cost="'.$qsescost['id'].'" AND status_peserta IS NULL AND nama="'.$vall[0].'" AND tgl_lahir="'.$vall[1].'" AND kredit_jumlah="'.$vall[2].'" AND kartu_no="'.$vall[3].'"');	}
while ($rr = mysql_fetch_array($r)) {
$met = $database->doQuery('INSERT INTO fu_ajk_peserta_tempf_del SET no_urut="'.$rr['no_urut'].'",
														  			id_dn="",
														  			id_cost="'.$rr['id_cost'].'",
														  			id_polis="'.$rr['id_polis'].'",
														  			namafile="'.$rr['namafile'].'",
														  			spaj="'.$rr['spaj'].'",
														  			nama="'.$rr['nama'].'",
														  			gender="'.$rr['gender'].'",
														  			kartu_type="'.$rr['kartu_type'].'",
														  			kartu_no="'.$rr['kartu_no'].'",
														  			kartu_period="'.$rr['kartu_period'].'",
														  			tgl_lahir="'.$rr['tgl_lahir'].'",
														  			usia="'.$umur.'",
														  			kredit_tgl="'.$rr['kredit_tgl'].'",
														  			kredit_jumlah="'.$rr['kredit_jumlah'].'",
														  			kredit_tenor="'.$rr['kredit_tenor'].'",
														  			premi="'.$premi.'",
														  			bunga="'.$rr['bunga'].'",
														  			biaya_adm="'.$rr['biaya_adm'].'",
														  			biaya_refund="'.$rr['biaya_refund'].'",
														  			ext_premi="'.$extrapremi.'",
														  			totalpremi="'.$mettotal.'",
														  			badant="'.$rr['badant'].'",
														  			badanb="'.$rr['badanb'].'",
														  			statement1="'.$rr['statement1'].'",
														  			p1_ket="'.$rr['p1_ket'].'",
														  			statement2="'.$rr['statement2'].'",
														  			p2_ket="'.$rr['p2_ket'].'",
														  			statement3="'.$rr['statement3'].'",
														  			p3_ket="'.$rr['p3_ket'].'",
														  			statement4="'.$rr['statement4'].'",
														  			p4_ket="'.$rr['p4_ket'].'",
														  			ket="'.$rr['ket'].'",
														  			status_medik="'.$status_medik.'",
														  			status_bayar="'.$rr['status_bayar'].'",
														  			status_aktif="'.$status_aktif.'",
														  			status_peserta="'.$rr['status_peserta'].'",
														  			regional ="'.$rr['regional'].'",
														  			area ="'.$rr['area'].'",
														  			cabang ="'.$rr['cabang'].'",
														  			input_by ="'.$rr['input_by'].'",
														  			input_time ="'.$rr['input_time'].'",
														  			update_by="'.$q['nm_user'].'",
														  			update_time ="'.$futgl.'"');
}
if ($_REQUEST['val']=="pclaim") {	$rr = $database->doQuery('DELETE FROM fu_ajk_peserta_tempf WHERE id_cost="'.$qsescost['id'].'" AND nama="'.$vall[0].'" AND tgl_lahir="'.$vall[1].'" AND kredit_jumlah="'.$vall[2].'" AND kartu_no="'.$vall[3].'"');	}
else	{	$rr = $database->doQuery('DELETE FROM fu_ajk_peserta_tempf WHERE id_cost="'.$qsescost['id'].'" AND nama="'.$vall[0].'" AND tgl_lahir="'.$vall[1].'" AND kredit_jumlah="'.$vall[2].'" AND kartu_no="'.$vall[3].'"');	}
		header("location:multiply_peserta.php?r=viewall");

	case "approveuser":
$pecahtgl = explode(" ", $futgl);	$pecahlagi = explode("-", $pecahtgl[0]);	$tglnya = $pecahlagi[2].'-'.$pecahlagi[1].'-'.$pecahlagi[0].' '.$pecahtgl[1];				//PECAH TANGGAL
echo $_REQUEST['tanggal[]'];
$items = array($_REQUEST['tanggal[]']);
foreach($items as $k => $sd){
echo $sd;
}


$fu = mysql_fetch_array($database->doQuery('SELECT fu_ajk_costumer.name, fu_ajk_costumer.id FROM fu_ajk_costumer WHERE name="'.$qsescost['name'].'"'));
$met = $database->doQuery('UPDATE fu_ajk_peserta_tempf SET status_data = "Approve By User" WHERE status_data = ""');

$to = $q['email'].", sumiyanto@relife.co.id, arief.kurniawan@relife.co.id";

$subject = 'Data telah di Diinput oleh Staff';
$message = '<html><head><title>Data Peserta Sudah Diinput oleh '.$q['nm_lengkap'].'</title></head>
			<body><table><tr><th>Data Peserta sudah diinput dan dikonfirmasi oleh <b>'.$_SESSION['nm_user'].' pada tanggal '.$tglnya.'</tr></table></body>
			</html>';
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
// Additional headers
$headers .= 'From: '.$q['email'].'' . "\r\n";
$headers .= 'Cc: relife-it@relife.co.id' . "\r\n";
//	$headers .= 'Bcc: k@example.com' . "\r\n";
mail($to, $subject, $message, $headers);

echo '<center>Data telah di input oleh <b>'.$_SESSION['nm_user'].'</b>, tunggu konfirmasi selanjutnya dari Supervisor untuk segera di proses oleh Admin AJK<br />
	  <a href="multiply_peserta.php">Kembali Ke Halaman Utama</a></center>';
	;
	break;

	case "fuparsing":
		$fu = mysql_fetch_array($database->doQuery('SELECT fu_ajk_costumer.name, fu_ajk_costumer.id FROM fu_ajk_costumer WHERE id="'.$q['id_cost'].'"'));
		$fufile = mysql_fetch_array($database->doQuery('SELECT fu_ajk_peserta.namafile FROM fu_ajk_peserta WHERE id_cost="'.$q['id_cost'].'"'));
		$fupolis = mysql_fetch_array($database->doQuery('SELECT fu_ajk_polis.nopol, fu_ajk_polis.id FROM fu_ajk_polis WHERE id="'.$_REQUEST['idpolis'].'"'));
		$_REQUEST['idpolis'] = $_POST['idpolis'];
//		$_REQUEST['bataskolom'] = $_POST['bataskolom'];
if (!$_REQUEST['idpolis'])  $error .='Silahkan pilih nomor polis<br />.';
//if (!$_REQUEST['bataskolom'])  $error .='Silahkan tentukan batas kolom file<br />.';
if (!$_FILES['userfile']['tmp_name'])  $error .='Silahkan upload file excel anda<br />.';
		$allowedExtensions = array("xls","xlsx","csv");
		foreach ($_FILES as $file) {
			if ($file['tmp_name'] > '') {
				if (!in_array(end(explode(".",	strtolower($file['name']))),
				$allowedExtensions)) {	die('<center><font color=red>'.$file['name'].' <br /><blink>File extension tidak diperbolehkan selain excel!</blink></font><br/>'.'
											<a href="multiply_peserta.php">'.'&lt;&lt Go Back</a></center>');	}
			}
		}
		//if ($_FILES['userfile']['name']=$fufile['namafile']) $error .='Nama file '.$_FILES['userfile']['name'].' sudah pernah di input<br />';

if ($error)
{	echo '<blink><center><font color=red>'.$error.'</font></blink><a href="multiply_peserta.php">'.'&lt;&lt Go Back</a></center>';	}

else
{
echo '<form method="post" action="multiply_peserta.php?r=approveuser" onload ="onbeforeunload">
	<table border="0" width="100%" cellpadding="1" cellspacing="1" style="border: solid 3px #DEDEDE"  bgcolor="#bde0e6">
	<tr><td colspan="2"><input type="hidden" name="idcostumer" value="'.$fu['id'].'">Costumer</td><td colspan="24">: <b>'.$fu['name'].'</b></td></tr>
	<tr><td colspan="2"><input type="hidden" name="idpolis" value="'.$fupolis['id'].'">Policy Number</td><td colspan="24">: <b>'.$fupolis['nopol'].'</b></td></tr>
	<tr><td colspan="2"><input type="hidden" name="bataskolom" value="'.$_REQUEST['bataskolom'].'"><input type="hidden" name="namafileexl" value="'.$_FILES['userfile']['name'].'">File Name</td><td colspan="24">: <b>'.$_FILES['userfile']['name'].'</b></td></tr>
	<tr><th width="1%" rowspan="2">Opt</th>
		<th width="1%" rowspan="2">No</th>
		<th width="5%" rowspan="2">No. PK</th>
		<th width="25%" rowspan="2">Nama Lengkap Debitur<br />(sesuai KTP/SIM)</th>
		<th width="1%" rowspan="2">L/P</th>
		<th colspan="3">Kartu Identitas</th>
		<th width="7%" rowspan="2">Tgl Lahir</th>
		<th colspan="3">Tgl Kredit</th>
		<th rowspan="2">Ket</th>
	</tr>
	<tr><th width="5%">Type</th>
		<th width="5%">No</th>
		<th width="7%">Periode</th>
		<th width="7%">Tanggal</th>
		<th width="7%">Jumlah</th>
		<th width="3%">Tenor</th>
	</tr>';
$data = new Spreadsheet_Excel_Reader($_FILES['userfile']['tmp_name']);
$baris = $data->rowcount($sheet_index=0);											//MEMBACA JUMLAH BARIS DATA EXCEL

for ($i=7; $i<=$_REQUEST['bataskolom']; $i++)
{
	$data1=$data->val($i, 1);		//no
	$data2=$data->val($i, 2);		//no pk
	$data3=$data->val($i, 3);		//nama
	$data4=$data->val($i, 4);		//p/w
	$data5=$data->val($i, 5);		//type kartu
	$data6=$data->val($i, 6);		//nomor kartu
	$data7=$data->val($i, 7);		//periode kartu
	$data8=$data->val($i, 8);		//tgl lahir
	$data9=$data->val($i, 9);		//tgl kredit
	$data10=$data->val($i, 10);		//jumlah kredit
	$data11=$data->val($i, 11);		//masa kredit
	$data12=$data->val($i, 12);		//ket

if ($data1==""){ $error ='<font color="red">error</font>'; $dataexcel1=$error;}else{ $dataexcel1=$data1;}	//no
if ($data2==""){ $error ='<font color="red">error</font>'; $dataexcel3=$error;}else{ $dataexcel3=$data2;}	//nama
if ($data3==""){ $error ='<font color="red">error</font>'; $dataexcel3=$error;}else{ $dataexcel3=$data3;}	//pk
if ($data4==""){ $error ='<font color="red">error</font>'; $dataexcel4=$error;}else{ $dataexcel4=$data4;}	//l/p
if ($data5==""){ $error ='<font color="red">error</font>'; $dataexcel5=$error;}else{ $dataexcel5=$data5;}	//type
if ($data6==""){ $error ='<font color="red">error</font>'; $dataexcel6=$error;}else{ $dataexcel6=$data6;}	//no
if ($data7=="" OR strlen($data7) >10 OR strlen($data7) <10 ){ $error ='<font color="red">Format error</font>'; $dataexcel7=$error;}else{ $dataexcel7=$data7;}	//tgl
if ($data8=="" OR strlen($data8) >10 OR strlen($data8) <10 ){ $error ='<font color="red">Format error</font>'; $dataexcel8=$error;}else{ $dataexcel8=$data8;}	//dob
if ($data9=="" OR strlen($data9) >10 OR strlen($data9) <10 ){ $error ='<font color="red">Format error</font>'; $dataexcel9=$error;}else{ $dataexcel9=$data9;}	//tgl kredit
if ($data10==""){ $error ='<font color="red">error</font>'; $dataexcel10=$error;}else{ $dataexcel10=duit($data10);}	//jumlah
if ($data11==""){ $error ='<font color="red">error</font>'; $dataexcel11=$error;}else{ $dataexcel11=$data11;}	//tenor

$databpr = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE nama="'.$data2.'" AND tgl_lahir="'.$data8.'" AND status_aktif="aktif" AND status_bayar="0"');
while ($metdatabpr = mysql_fetch_array($databpr)) {
echo '<tr bgcolor="orange">
	<td width="10%">
		<table border="0" cellpadding="0" cellspacing="0">
		<tr><td><input type="checkbox" id="checkbox" name="id[]" value="'.$metdatabpr['id'].'" onclick="showMe('.$metdatabpr['id'].', this)" />'.$metdatabpr['id'].'</td>
			<td class="row" id="'.$metdatabpr['id'].'" style="display:none">
				<input type="text" name="tanggal[]" id="tanggal['.$metdatabpr['id'].']" class="tanggal" value="'.$_REQUEST['tanggal[]'].'" size="6"/>
				<input type="file" name = "docmamet[]" class="multi max-2"/>';
echo '</td>
		</tr>
		</table>';
echo '</td>
	<td align="center"> </td>
	<td>'.$metdatabpr['nama'].'</td>
	<td align="center">'.$metdatabpr['spaj'].'</td>
	<td align="center">'.$metdatabpr['gender'].'</td>
	<td align="center">'.$metdatabpr['kartu_type'].'</td>
	<td align="center">'.$metdatabpr['kartu_no'].'</td>
	<td align="center">'.$metdatabpr['kartu_period'].'</td>
	<td align="center">'.$metdatabpr['tgl_lahir'].'</td>
	<td align="center">'.$metdatabpr['kredit_tgl'].'</td>
	<td align="right">'.duit($metdatabpr['kredit_jumlah']).'</td>
	<td align="center">'.$metdatabpr['kredit_tenor'].'</td>
	<td align="center">'.$metdatabpr['ket'].'</td>
</tr>';
}
if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		<td align="center"> </td>
		<td align="center">'.$dataexcel1.'</td>
		<td align="center">'.$data2.'</td>
		<td>'.$dataexcel3.'</td>
		<td align="center">'.$dataexcel4.'</td>
		<td align="center">'.$dataexcel5.'</td>
		<td align="center">'.$dataexcel6.'</td>
		<td align="center">'.$dataexcel7.'</td>
		<td align="center">'.$dataexcel8.'</td>
		<td align="center">'.$dataexcel9.'</td>
		<td align="right">'.$dataexcel10.'</td>
		<td align="center">'.$dataexcel11.'</td>
		<td align="center">'.$dataexcel12.'</td>
	</tr>';
	//$datadob8 = explode("/", $data8);	$data8dob = $datadob8[0].'-'.$datadob8[1].'-'.$datadob8[2];
$exl = $database->doQuery('INSERT IGNORE v_fu_ajk_peserta_tempf SET no_urut="'.$data1.'",
															     id_dn="",
															     id_cost="'.$qsescost['id'].'",
															     id_polis="'.$fupolis['id'].'",
															     namafile="'.$_FILES['userfile']['name'].'",
															     spaj="'.$data2.'",
															     nama="'.$data3.'",
															     gender="'.$data4.'",
															     kartu_type="'.$data5.'",
															     kartu_no="'.$data6.'",
															     kartu_period="'.$data7.'",
															     tgl_lahir="'.$data8.'",
															     kredit_tgl="'.$data9.'",
															     kredit_jumlah="'.$data10.'",
															     kredit_tenor="'.$data11.'",
															     ket="'.$data12.'",
															     status_medik ="",
															     status_bayar ="0",
															     status_aktif ="aktif",
															     input_by ="'.$_SESSION['nm_user'].'",
															     input_time ="'.$futgl.'"
															     ');

}
if ($error) {	echo '<tr><td colspan="27" align="center"><font color="red"><blink>Silahkan lengkapi kolom yang error !!!</blink><br /><a href="multiply_peserta.php?r=cancell&fileclient='.$_FILES['userfile']['name'].'">Back</a></font></td></tr>';	}
else		{	echo '<tr><td colspan="27" align="center"><a href="multiply_peserta.php?r=approveuser" onClick="if(confirm(\'Apakah anda sudah yakin dengan semua data peserta ini ?\')){return true;}{return false;}">Confirm</a>
	 					   &nbsp; | &nbsp; <a href="multiply_peserta.php?r=cancell&fileclient='.$_FILES['userfile']['name'].'">Cancel</a></td></tr>';	}

	echo '</table></form>';
}

		//MAMET DISABLED TOMBOL BACK//
echo '<script type = "text/javascript" >
	function changeHashOnLoad() {
	     window.location.href += "#";
	     setTimeout("changeHashAgain()", "50");
	}
		function changeHashAgain() {
	  window.location.href += "1";
	}
		var storedHash = window.location.hash;
	window.setInterval(function () {
	    if (window.location.hash != storedHash) {
	         window.location.hash = storedHash;
	    }
	}, 50);


	</script>
	<script language="JavaScript" src="includes/js/backfix.min.js" type="text/javascript"></script>
	<script type="text/javascript">
		bajb_backdetect.OnBack = function()
		{
			alert("Silahkan klik tombol back atau cancel yang ada di bagian bawah tabel!");
		}
		</script>';
	//MAMET DISABLED TOMBOL BACK//
	;
	break;
	default:
echo '<table border="0" cellpadding="0" cellspacing="0" width="100%">
      <tr><th width="100%" align="left">Modul Upload Data Peserta</font></th></tr>
      </table>';
		$fu = mysql_fetch_array($database->doQuery('SELECT fu_ajk_costumer.name, fu_ajk_costumer.id FROM fu_ajk_costumer WHERE name="'.$qsescost['name'].'"'));
echo '<form name="f1" method="post" enctype="multipart/form-data" action="multiply_peserta.php?r=fuparsing">
			<table border="0" width="60%" align="center">
			<tr><td width="23%">Nama Perusahaan</td><td colspan="3">: ';
echo '<input type="hidden" name="'.$fu['id'].'" value="'.$fu['id'].'">'.$fu['name'].'
			</td></tr>
			<tr><td>Nomor Polis</td>
				<td>: <select id="idpolis" name="idpolis">
			<option value="">-----Pilih Polis-----</option>';
		$pol = $database->doQuery('SELECT fu_ajk_polis.nopol, fu_ajk_polis.id FROM fu_ajk_polis WHERE id_cost="'.$fu['id'].'" ORDER BY nopol ASC');
while ($policy = mysql_fetch_array($pol)) {
	echo '	<option value="'.$policy['id'].'">'.$policy['nopol'].'</option>';
}
echo '</select></td>
			</tr>
		  <tr><td>Silakan Pilih File Excel </td><td>: '.$bataskolom.' <input name="userfile" type="file" size="50" onchange="checkfile(this);" ></td></tr>
		  <tr><td>Batas Akhir Baris </td><td>: <input type="text" name="bataskolom" value="'.$bataskolom.'" size="1"></td></tr>
		  <tr><td align="center"colspan="2"><input name="upload" type="submit" value="Import"></td></tr>
		  </table></form>';
	;
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

<!--CHECKE SHOW-->
<script type="text/javascript">
	<!--
	function showMe (it, box) {
	  var vis = (box.checked) ? "block" : "none";
	  document.getElementById(it).style.display = vis;
	}
	//-->
</script>
<!--<script src='includes/js/metUpl_jquery.js' type="text/javascript"></script>-->
<script src='includes/js/metUpl_jquery.MultiFile.js' type="text/javascript" language="javascript"></script>

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