<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("../includes/functions.php");
connect();

switch ($_REQUEST['app']) {
	case "reass":
echo '<table border="0" cellpadding="0" cellspacing="0" width="100%">
      <tr><th width="100%" align="left">Modul AJK to REASS</font></th></tr>
      </table>
	  <fieldset style="padding: 2">
		<legend align="center">S e a r c h</legend>
		<table border="0" width="100%" cellpadding="3" cellspacing="1">
		<form method="post" action="">
		<tr><td>Tanggal :';
print initCalendar();	print calendarBox('tgl', 'triger', $_REQUEST['tgl']);	echo 's/d';	print initCalendar();	print calendarBox('tgl2', 'triger2', $_REQUEST['tgl2']);
if ($_REQUEST['tgl']!="" AND $_REQUEST['tgl2']!="") {	$metReasDL = '<a href="ajk_report_fu.php?fu=downloadreass&tgl='.$_REQUEST['tgl'].'&tgl2='.$_REQUEST['tgl2'].'&acc='.$_SESSION['nm_user'].'"><img src="image/excel.png" border="0" width="30"><a/>';	}else{	$metReasDL = '';	}

echo '&nbsp; <input type="hidden" name="carireas" value="Cari" class="button"><input type="submit" name="button" value="Cari" class="button"> &nbsp; '.$metReasDL.'</td></tr>
		</form></table></fieldset>';
if ($_REQUEST['carireas']=="Cari") {
echo '<table border="0" width="120%" cellpadding="2" cellspacing="1" bgcolor="#bde0e6">
	<tr><th width="1%">No</th>
	<th width="4%" title="Tanggal Entry/Produksi Data Polis">prd_dt</th>
	<th width="1%" title="Kode Produk: Menurun Atau Tetap(tiap client berbeda)">prd_id</th>
	<th width="1%" title="Nomor Polis">pol_no</th>
	<th width="1%" title="Nomor SPAJ/ID Peserta">pol_no_x</th>
	<th width="12%" title="Nama Tertanggung">ph_name</th>
	<th width="15%" title="Nama Peserta">pi_name</th>
	<th width="1%" title="Jenis Kelamin (M/F)">pi_sex</th>
	<th width="4%" title="Tanggal Lahir">pi_dob</th>
	<th width="1%" title="Pekerjaan/Okupasi">occup</th>
	<th width="1%" title="Negara Tujuan">cty_dest</th>
	<th width="1%" title="Kategori PA = 1">pa_cat</th>
	<th width="1%" title="Kategori Medikal = N">med_cat</th>
	<th width="1%" title="Tanggal Berlakunya Polis">eff_cat</th>
	<th width="1%" title="Informasi Masa Pembayaran Polis (thn)">mpp</th>
	<th width="1%" title="Informasi Masa Tahapan (thn)">mth</th>
	<th width="1%" title="Masa Berlaku Polis (thn)">mas</th>
	<th width="1%" title="Masa Berlaku Polis (bln)">mas_mt</th>
	<th width="1%" title="Status Polis (Inforce:01, Lapse=11, Cancel=13, Maturity=13)">pol_st</th>
	<th width="1%" title="tanggal status polis">pst_dt</th>
	<th width="1%" title="tanggal status polis">jprd_dt</th>
	<th width="1%" title="Nomor Perubahan (isi:0)">end_no</th>
	<th width="1%" title="Tanggal Berlakunya Cover (tgl awal kredit)">JEFF_DT</th>
	<th width="1%" title="Tanggal Berlakunya Cover (tgl awal kredit)">JRC_type</th>
	<th width="1%" title="Kode Cover (isi:0)">JRC_ID</th>
	<th width="1%" title="Kode Cover PLan(Menurun = 11, Tetap=12)">JRCP_ID</th>
	<th width="1%" title="Kode Mata Uang">cur_id</th>
	<th width="1%" title="Uang Pertanggungan">pol_up</th>
	<th width="1%" title="Status Cover (Inforce = 01, Lapse = 11)">COV_ST</th>
	<th width="1%" title="Tanggal Status Cover">CST_ST</th>
	<th width="1%" title="Rate Premi">RM_Rate</th>
	<th width="1%" title="Rate Premi">Status</th>
</th>
	</tr>';

//$tgl1 = explode("-", $_REQUEST['tgl']); 	$rtgl = $tgl1[2].'/'.$tgl1[1].'/'.$tgl1[0];
//$tgl2 = explode("-", $_REQUEST['tgl2']); 	$rtgl2 = $tgl2[2].'/'.$tgl2[1].'/'.$tgl2[0];
//if ($_REQUEST['tgl']!='' AND $_REQUEST['tgl2']!='')	{	$satu='AND vkredit_tgl BETWEEN \''.$rtgl.'\' AND \''.$rtgl2.'\' ';	}
if ($_REQUEST['tgl']!='' AND $_REQUEST['tgl2']!='')	{	$satu='AND vkredit_tgl BETWEEN \''.$_REQUEST['tgl'].'\' AND \''.$_REQUEST['tgl2'].'\' ';	}
if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 100;	}
else {	$m = 0;		}

$ajk_reas = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id!="" ' . $satu . ' AND status_aktif="aktif" OR status_aktif="Lapse" ORDER BY kredit_tgl ASC LIMIT ' . $m . ' , 100');
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_peserta WHERE id != "" ' . $satu . ''));
$totalRows = $totalRows[0];
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while ($fudata = mysql_fetch_array($ajk_reas)) {
$ajk_polis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$fudata['id_polis'].'"'));
$ajk_costumer = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$fudata['id_cost'].'"'));

$kredit = explode("/", $fudata['kredit_tgl']);		//TGL KREDIT

$input_date = explode(" ", $fudata['input_time']);	$input_date1 = explode("-", $input_date[0]);	$pst_dt =$input_date1[1].'/'.$input_date1[2].'/'.$input_date1[0];	//PST_DT
if ($fudata['status_peserta']=="") {	$cov = "Aktif";	}	else	{	$cov = $fudata['status_peserta'];	}

//MASA BERLAKU POLIS//
$bpolis = explode("-", $ajk_polis['start_date']);
//MASA BERLAKU POLIS//

//STATUS POLIS//
if ($fudata['status_aktif']=="aktif") { $statuspolis="01";	}
elseif ($fudata['status_aktif']=="Lapse") { $statuspolis="11";	}
elseif ($fudata['status_aktif']=="Cancel") { $statuspolis="13";	}
elseif ($fudata['status_aktif']=="Maturity") { $statuspolis="16";	}
else	{	$statuspolis=""; }
//STATUS POLIS//

//KODE COVER PLAN//
if ($ajk_polis['typeRate']=="Tetap") { $kodecover = "12";	}
elseif ($ajk_polis['typeRate']=="Menurun") { $kodecover = "11";	}
else	{ $kodecover = "10";	}
//KODE COVER PLAN//


//RATE PESERTA//
//CEK UMUR UNTUK PERHITUNGAN RATE//
	$findmet="/";
	$fpos = stripos($fudata['tgl_lahir'], $findmet);
if ($fpos === false) {
	$riweuhkredit = explode("-", $fudata['kredit_tgl']);	$cektglkredit = $riweuhkredit[0].'-'.$riweuhkredit[1].'-'.$riweuhkredit[2];		//KREDIT AKHIR
	$endkredit2=date('d-m-Y',strtotime($cektglkredit."+".$fudata['kredit_tenor']." Month"));												//KREDIT AKHIR

	$riweuh = explode("-", $fudata['tgl_lahir']);			$cektglnya = $riweuh[0].'-'.$riweuh[1].'-'.$riweuh[2];							// FORMULA USIA
	$riweuh2 = explode("-", $fudata['kredit_tgl']);			$cektglnya2 = $riweuh2[0].'-'.$riweuh2[1].'-'.$riweuh2[2];							// FORMULA USIA
	$umur = ceil(((strtotime($cektglnya2) - strtotime($cektglnya)) / (60*60*24*365.2425)));												// FORMULA USIA
}	else	{
	$riweuhkredit = explode("/", $fudata['kredit_tgl']);	$cektglkredit = $riweuhkredit[0].'-'.$riweuhkredit[1].'-'.$riweuhkredit[2];		//KREDIT AKHIR
	$endkredit2=date('d-m-Y',strtotime($cektglkredit."+".$fudata['kredit_tenor']." Month"));												//KREDIT AKHIR

	$riweuh = explode("/", $fudata['tgl_lahir']);			$cektglnya = $riweuh[0].'-'.$riweuh[1].'-'.$riweuh[2];							// FORMULA USIA
	$riweuh2 = explode("/", $fudata['kredit_tgl']);			$cektglnya2 = $riweuh2[0].'-'.$riweuh2[1].'-'.$riweuh2[2];							// FORMULA USIA
	$umur = ceil(((strtotime($cektglnya2) - strtotime($cektglnya)) / (60*60*24*365.2425)));												// FORMULA USIA
}	//CEK FORMAT UMUR
//CEK UMUR UNTUK PERHITUNGAN RATE//
if ($fudata['id_cost']=="14") {
$cekrate = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$fudata['id_cost'].'" AND id_polis="'.$fudata['id_polis'].'" AND tenor="'.$fudata['kredit_tenor'].'"'));		// RATE PREMI
}elseif ($fudata['id_cost']=="14"){
$RTenor = $fudata['kredit_tenor'] / 12;		$tenortunggal = ceil($RTenor);
$cekrate = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi_tunggal WHERE id_cost="'.$fudata['id_cost'].'" AND id_polis="'.$fudata['id_polis'].'" AND usia="'.$umur.'" AND tenorthn="'.$tenortunggal.'"'));		// RATE PREMI
}
//RATE PESERTA//

	$findmet="/";
	$fpos = stripos($fudata['tgl_lahir'], $findmet);
if ($fpos === false) {
	$riweuh = explode("-", $fudata['tgl_lahir']);
	$tgldob = $riweuh[2].'-'.$riweuh[1].'-'.$riweuh[0];
}	else	{
	$riweuh = explode("/", $fudata['tgl_lahir']);
	$tgldob = $riweuh[2].'-'.$riweuh[1].'-'.$riweuh[0];
}

if ($fudata['spaj']=="") {	$pol_no_x = $fudata['id_peserta'];	}else{	$pol_no_x = $fudata['spaj'];	}

if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	  <td align="center">'.(++$no + ($pageNow-1) * 100).'</td>
	  <td align="center">'.$ajk_polis['start_date'].'</td>
	  <td align="center">'.$ajk_polis['reas_code'].'</td>
	  <td align="center">'.$ajk_polis['nopol'].'</td>
	  <td align="center">'.$pol_no_x.'</td>
	  <td>'.$ajk_costumer['name'].'</td>
	  <td>'.$fudata['nama'].'</td>
	  <td align="center">'.$fudata['gender'].'</td>
	  <td align="center">'.$fudata['tgl_lahir'].'</td>
	  <td align="center">0</td>
	  <td align="center">0</td>
	  <td align="center">1</td>
	  <td align="center">N</td>
	  <td align="center">'.$ajk_polis['start_date'].'</td>
	  <td align="center">0</td>
	  <td align="center">0</td>
	  <td align="center">'.$bpolis[0].'</td>
	  <td align="center">'.$bpolis[1].'</td>
	  <td align="center">'.$statuspolis.'</td>
	  <td align="center">'.$ajk_polis['start_date'].'</td>
	  <td align="center">'.$ajk_polis['start_date'].'</td>
	  <td align="center">0</td>
	  <td align="center">'.$fudata['vkredit_tgl'].'</td>
	  <td align="center">B</td>
	  <td align="center">11</td>
	  <td align="center">'.$kodecover.'</td>
	  <td align="center">00</td>
	  <td align="right">'.duit($fudata['kredit_jumlah']).'</td>
	  <td align="center">'.$statuspolis.'</td>
	  <td align="center">'.$fudata['vkredit_tgl'].'</td>
	  <td align="center">'.$cekrate['rate'].'</td>
	  <td align="center">'.$fudata['status_aktif'].'</td>
	  </tr>';
}
echo '<tr><td colspan="22">';
echo createPageNavigations($file = 'app_out.php?app=reass&tgl='.$_REQUEST['tgl'].'&tgl2='.$_REQUEST['tgl2'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 100);
echo '<b>Total Data Peserta: <u>' . $totalRows . '</u></b></td></tr>';
echo '</table>';
}else{

}
		;
		break;
	case "gl":
echo '<table border="0" cellpadding="0" cellspacing="0" width="100%">
      <tr><th width="100%" align="left">Modul AJK to GL</font></th></tr>
      </table>
	  <fieldset style="padding: 2">
		<legend align="center">S e a r c h</legend>
		<table border="0" width="100%" cellpadding="3" cellspacing="1">
		<form method="post" action="">
		<tr><td>Tanggal :';
print initCalendar();	print calendarBox('tgl', 'triger', $_REQUEST['tgl']);	echo 's/d';
print initCalendar();	print calendarBox('tgl2', 'triger2', $_REQUEST['tgl2']);
echo '&nbsp; <input type="submit" name="button" value="Cari" class="button"></td>
	  <td width="5%"><a href="ajk_report_fu.php?fu=downloadreass&tgl='.$_REQUEST['tgl'].'&tgl2='.$_REQUEST['tgl2'].'">excel<a/></td></tr>
</form></table></fieldset>';


echo '<table border="0" width="100%" cellpadding="2" cellspacing="1" bgcolor="#bde0e6">
<tr><th width="1%">No</th>
<th width="4%">fARAP_No</th>
<th width="8%">fARAP_Tgl</th>
<th width="1%">fARAP_KdTrans</th>
<th width="1%">farap_Amount</th>
<th width="12%">farap_CostumerNm</th>
<th width="15%">farap_Keterangan</th>
<th width="1%">farap_SLATgIA</th>
<th width="4%">farap_rekBank</th>
<th width="1%">farap_RekCabang</th>
<th width="1%">farap_RekNoRekening</th>
<th width="1%">fRcd_EntryDate</th>
<th width="4%">fRcd_EntryUserID</th>
<th width="4%">fDTL_MtscFCY</th>
<th width="4%">fDTL_KetTrans</th>
<th width="1%">fBGT_KdUnitK</th>
<th width="1%">fBGT_KdCabang</th>
<th width="1%">fBGT_NoPolis</th>
<th width="1%">fBGT_NoAgent</th>
</th>
</tr>';

echo '</table>';
		;
		break;
	case "mis":
		;
		break;
	default:
		;
} // switch
?>