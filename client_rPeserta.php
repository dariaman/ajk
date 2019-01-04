<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :penting_kaga@yahoo.com
// @copyright Januari 2014
// ----------------------------------------------------------------------------------
include_once('ui.php');
connect();
$futgl = date("Y-m-d g:i:a");
if (session_is_registered('nm_user')) {
	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));
	$met = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$q['id_cost'].'"'));
	$cmet = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id_cost="'.$met['id'].'"'));
}
if ($q['wilayah']=="Pusat")
{	$querydata = '';
	$querydata1 = '';
	$querydata2 = '';
}else
{	$querydata = 'AND name LIKE "%'.$q['wilayah'].'%"';
	$querydata1 = 'AND regional LIKE "%'.$q['wilayah'].'%"';
	$querydata2 = 'AND id_regional LIKE "%'.$q['wilayah'].'%"';
}
switch ($_REQUEST['r']) {
	case "rDN":
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
	  <tr><th align="left">Laporan Data Peserta - Debit Note (DN)</font></th></tr>
	  </table>
<table border="0" width="50%" cellpadding="1" cellspacing="3" align="center">
<form method="post" action="" name="postform">
<tr><td width="5%">Nomor Polis</td>
	<td width="10%">: <select name="nopol" id="nopol">
	<option value="">-- Pilih Nomor Polis --</option>';
		$rpolis = mysql_query('select * from fu_ajk_polis WHERE id!="" AND id_cost="'.$q['id_cost'].'"');
		while($polisnya = mysql_fetch_array($rpolis)) {
			$sel = ""; if ($_POST["nopol"] == $row["id"]) $sel = ' selected="selected"';
			echo '<option value="'.$polisnya["id"].'">'.$polisnya['nopol'].'</option>';
		}
		echo '</select></td></tr>
		<tr><td width="5%">Regional <font color="red">*</font></td>
			<td width="10%">: <select name="id_reg" id="id_reg">
			<option value="">-- Select Regional --</option>';
		$rows = mysql_query('select * from fu_ajk_regional WHERE id!="" AND id_cost="'.$q['id_cost'].'" '.$querydata.'');
		while($row = mysql_fetch_array($rows)) {
			$sel = ""; if ($_POST["id_reg"] == $row["id"]) $sel = ' selected="selected"';
			echo '<option value="'.$row["id"].'"'.$sel.'>'.$row["name"].'</option>';
		}
		echo '</select></td></tr>
		<tr><td>Area</td><td id="area">: <select name="id_area" id="id_area"><option value="">-- Select Area --</option></select></td></tr>
		<tr><td>Cabang</td><td id="cabang">: <select name="id_cabang" id="id_cabang"><option value="">-- Select Cabang --</option></select></td></tr>
		<tr><td>Status Pembayaran <input type="radio"'.pilih($_REQUEST['Rpembayaran'], "3").' name="Rpembayaran" value="3" checked>All</td></td><td> :
			<input type="radio"'.pilih($_REQUEST['Rpembayaran'], "paid").' name="Rpembayaran" value="paid">Paid &nbsp;
			<input type="radio"'.pilih($_REQUEST['Rpembayaran'], "unpaid").' name="Rpembayaran" value="unpaid">Unpaid &nbsp;
		</td></tr>
		<tr><td>Tanggal DN di buat <font color="red">*</font></td><td>: ';
echo '<input type="text" id="from" name="createdn1" value="'.$_REQUEST['createdn1'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.from);return false;"/><a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.from);return false;"><img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a> s/d
	  <input type="text" id="to" name="createdn2" value="'.$_REQUEST['createdn2'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.to);return false;"/><a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.to);return false;" ><img name="popcal" align="absmiddle" style="border:none" src="./calender/calender.jpeg" width="30" height="25" border="0" alt=""></a>
	  <iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
</td></tr>
		<tr><td width="5%" align="center" colspan="3"><input type="submit" name="laporan_peserta" value="Laporan" class="button"></td></tr>
		</form>
		</table>';
if ($_REQUEST['laporan_peserta']=="Laporan") {
if (!$_REQUEST['id_reg']){	echo '<font color="red"><center>Silahkan pilih Regional.</center></font>';	}
elseif (!$_REQUEST['createdn1'] OR !$_REQUEST['createdn2']){	echo '<font color="red"><center>Silahkan tentukan tanggal DN di buat.</center></font>';	}
else	{
echo '<a href="clients_report.php?r=pesertadn&id_cost='.$q['id_cost'].'&nopol='.$_REQUEST['nopol'].'&id_reg='.$_REQUEST['id_reg'].'&id_area='.$_REQUEST['id_area'].'&id_cabang='.$_REQUEST['id_cabang'].'&Rpembayaran='.$_REQUEST['Rpembayaran'].'&createdn1='.$_REQUEST['createdn1'].'&createdn2='.$_REQUEST['createdn2'].'"><img src="image/excel.png" width="30" border="0"> &nbsp; <br />Excel</a>';
//TGL DN DI BUAT
$tgl1 = explode("-", $_REQUEST['createdn1']);	$tglawal = $tgl1[0].'-'.$tgl1[1].'-'.$tgl1[2];
$tgl2 = explode("-", $_REQUEST['createdn2']);	$tglakhir = $tgl2[0].'-'.$tgl2[1].'-'.$tgl2[2];

//REGIONAL AREA DAN CABANG
$metreg = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_regional WHERE id="'.$_REQUEST['id_reg'].'"'));
$metarea = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_area WHERE id="'.$_REQUEST['id_area'].'"'));
$metcabang = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_cabang WHERE id="'.$_REQUEST['id_cabang'].'"'));
if ($_REQUEST['nopol'])				{	$satu = 'AND id_nopol = "' . $_REQUEST['nopol'] . '"';	}
if ($_REQUEST['id_reg'])			{	$dua = 'AND id_regional = "' . $metreg['name'] . '"';	}
if ($_REQUEST['id_area'])			{	$tiga = 'AND id_area = "' . $metarea['name'] . '"';	}
if ($_REQUEST['id_cabang'])			{	$empat = 'AND id_cabang = "' . $metcabang['name'] . '"';	}
if ($_REQUEST['createdn1'])			{	$lima = 'AND tgl_createdn BETWEEN "'.$tglawal.'" AND "'.$tglakhir.'"';	}
if ($_REQUEST['Rpembayaran']=="3")	{	$enam = 'AND dn_status != "2"';	}else{	$enam = 'AND dn_status = "'.$_REQUEST['Rpembayaran'].'"';	}
define('_NEXT', '<img src="imags/right_arrow.gif" border="0">');	define('_PREV', '<img src="image/left_arrow.gif" border="0">');
if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 30;	}	else {	$m = 0;		}
echo '<table border="0" width="100%" cellpadding="3" cellspacing="1" bgcolor="#bde0e6">
	  <tr><th width="1%">No</th>
	  	  <th width="1%">Polis</th>
	  	  <th>D N</th>
	  	  <th width="8%">Premi</th>
	  	  <th width="8%">Tgl DN</th>
	  	  <th width="8%">Status</th>
	  	  <th width="8%">Tgl Pembayaran</th>
	  	  <th width="15%">Regional</th>
	  	  <th width="15%">Cabang</th>
	  </tr>';
$mamet = $database->doQuery('SELECT * FROM fu_ajk_dn WHERE id !="" AND id_cost="'.$q['id_cost'].'" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' AND del IS NULL ORDER BY tgl_createdn DESC LIMIT ' . $m . ' , 30');
$jummamet = $database->doQuery('SELECT * FROM fu_ajk_dn WHERE id !="" AND id_cost="'.$q['id_cost'].'" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' AND del IS NULL');
$totalRows = mysql_num_rows($jummamet);
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while ($rahmad = mysql_fetch_array($mamet)) {
	$metpolis = mysql_fetch_array($database->doQuery('SELECT id, nopol FROM fu_ajk_polis WHERE id="'.$rahmad['id_nopol'].'"'));
	if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
echo '<tr class="'.rowClass(++$i).'">
	  <td align="center">'.(++$no + ($pageNow-1) * 30).'</td>
      <td align="center">'.$metpolis['nopol'].'</td>
      <td align="center">'.$rahmad['dn_kode'].'</td>
      <td align="right">'.duit($rahmad['totalpremi']).'</td>
      <td align="center">'._convertDate($rahmad['tgl_createdn']).'</td>
      <td align="center">'.$rahmad['dn_status'].'</td>
      <td align="center">'._convertDate($rahmad['tgl_dn_paid']).'</td>
      <td>'.$rahmad['id_regional'].'</td>
      <td>'.$rahmad['id_cabang'].'</td>
  </tr>';
}
echo '<tr><td colspan="17">';
echo createPageNavigations($file = 'client_rPeserta.php?r=rDN&laporan_peserta='.$_REQUEST['laporan_peserta'].'&id_cost='.$q['id_cost'].'&nopol='.$_REQUEST['nopol'].'&id_reg='.$_REQUEST['id_reg'].'&id_area='.$_REQUEST['id_area'].'&id_cabang='.$_REQUEST['id_cabang'].'&Rpembayaran='.$_REQUEST['Rpembayaran'].'&createdn1='.$_REQUEST['createdn1'].'&createdn2='.$_REQUEST['createdn2'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 30);
echo '<b>Total Data Debit Note (DN) : <u>' . $totalRows . '</u></b></td></tr>';
echo '</table>';
		}
}
		;
		break;
	case "rCN":
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
	  <tr><th align="left">Laporan Data Peserta - Credit Note (CN)</font></th></tr>
</table>
<table border="0" width="50%" cellpadding="1" cellspacing="3" align="center">
<form method="post" action="" name="postform">
<tr><td width="5%">Nomor Polis</td>
	<td width="10%">: <select name="nopol" id="nopol">
	<option value="">-- Pilih Nomor Polis --</option>';
		$rpolis = mysql_query('select * from fu_ajk_polis WHERE id!="" AND id_cost="'.$q['id_cost'].'"');
		while($polisnya = mysql_fetch_array($rpolis)) {
			$sel = ""; if ($_POST["nopol"] == $row["id"]) $sel = ' selected="selected"';
			echo '<option value="'.$polisnya["id"].'">'.$polisnya['nopol'].'</option>';
		}
		echo '</select></td></tr>
		<tr><td width="5%">Regional <font color="red">*</font></td>
			<td width="10%">: <select name="id_reg" id="id_reg">
			<option value="">-- Select Regional --</option>';
		$rows = mysql_query('select * from fu_ajk_regional WHERE id!="" AND id_cost="'.$q['id_cost'].'" '.$querydata.'');
		while($row = mysql_fetch_array($rows)) {
			$sel = ""; if ($_POST["id_reg"] == $row["id"]) $sel = ' selected="selected"';
			echo '<option value="'.$row["id"].'"'.$sel.'>'.$row["name"].'</option>';
		}
		echo '</select></td></tr>
		<tr><td>Area</td><td id="area">: <select name="id_area" id="id_area"><option value="">-- Select Area --</option></select></td></tr>
		<tr><td>Cabang</td><td id="cabang">: <select name="id_cabang" id="id_cabang"><option value="">-- Select Cabang --</option></select></td></tr>
		<tr><td width="5%">Status Data</td>
			<td width="10%">: <select name="sdata" id="sdata">
			<option value="">-- Pilih Data --</option>';
		$r_sdata = mysql_query('SELECT DISTINCT type_claim FROM fu_ajk_cn WHERE id_cost="'.$q['id_cost'].'" ORDER BY type_claim ASC');
		while($re_sdata = mysql_fetch_array($r_sdata)) {
			echo '<option value="'.$re_sdata["type_claim"].'">'.$re_sdata['type_claim'].'</option>';
		}
		echo '</select></td></tr>
		<tr><td>Tanggal CN di buat <font color="red">*</font></td><td>: ';
echo '<input type="text" id="from" name="createcn1" value="'.$_REQUEST['createcn1'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.from);return false;"/><a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.from);return false;"><img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a> s/d
	  <input type="text" id="to" name="createcn2" value="'.$_REQUEST['createcn2'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.to);return false;"/><a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.to);return false;" ><img name="popcal" align="absmiddle" style="border:none" src="./calender/calender.jpeg" width="30" height="25" border="0" alt=""></a>
	  <iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
</td></tr>
		<tr><td width="5%" align="center" colspan="3"><input type="submit" name="laporan_peserta" value="Laporan" class="button"></td></tr>
		</form>
		</table>';
if ($_REQUEST['laporan_peserta']=="Laporan") {
if (!$_REQUEST['id_reg']){	echo '<font color="red"><center>Silahkan pilih Regional.</center></font>';	}
elseif (!$_REQUEST['createcn1'] OR !$_REQUEST['createcn2']){	echo '<font color="red"><center>Silahkan tentukan tanggal CN di buat.</center></font>';	}
else	{
echo '<a href="clients_report.php?r=pesertacn&id_cost='.$q['id_cost'].'&nopol='.$_REQUEST['nopol'].'&id_reg='.$_REQUEST['id_reg'].'&id_area='.$_REQUEST['id_area'].'&id_cabang='.$_REQUEST['id_cabang'].'&sdata='.$_REQUEST['sdata'].'&createcn1='.$_REQUEST['createcn1'].'&createcn2='.$_REQUEST['createcn2'].'"><img src="image/excel.png" width="30" border="0"> &nbsp; <br />Excel</a>';
//TGL CN DI BUAT
$tgl1 = explode("-", $_REQUEST['createcn1']);	$tglawal = $tgl1[0].'-'.$tgl1[1].'-'.$tgl1[2];
$tgl2 = explode("-", $_REQUEST['createcn2']);		$tglakhir = $tgl2[0].'-'.$tgl2[1].'-'.$tgl2[2];

//REGIONAL AREA DAN CABANG
$metreg = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_regional WHERE id="'.$_REQUEST['id_reg'].'"'));
$metarea = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_area WHERE id="'.$_REQUEST['id_area'].'"'));
$metcabang = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_cabang WHERE id="'.$_REQUEST['id_cabang'].'"'));
if ($_REQUEST['nopol'])				{	$satu = 'AND id_nopol = "' . $_REQUEST['nopol'] . '"';	}
if ($_REQUEST['id_reg'])			{	$dua = 'AND id_regional = "' . $metreg['name'] . '"';	}
if ($_REQUEST['id_area'])			{	$tiga = 'AND id_area = "' . $metarea['name'] . '"';	}
if ($_REQUEST['id_cabang'])			{	$empat = 'AND id_cabang = "' . $metcabang['name'] . '"';	}
if ($_REQUEST['sdata'])				{	$lima = 'AND type_claim = "' . $_REQUEST['sdata'] . '"';	}
if ($_REQUEST['createcn1'])			{	$enam = 'AND tgl_createcn BETWEEN "'.$tglawal.'" AND "'.$tglakhir.'"';	}

define('_NEXT', '<img src="imags/right_arrow.gif" border="0">');	define('_PREV', '<img src="image/left_arrow.gif" border="0">');
if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 30;	}	else {	$m = 0;		}
echo '<table border="0" width="100%" cellpadding="3" cellspacing="1" bgcolor="#bde0e6">
	  <tr><th width="1%">No</th>
	  	  <th width="1%">Polis</th>
	  	  <th width="5%">ID (Lama)</th>
	  	  <th>Nama</th>
		  <th width="5%">D.O.B</th>
	  	  <th width="5%">U P</th>
		  <th width="11%">D N (Lama)</th>
	  	  <th width="11%">C N</th>
	  	  <th width="5%">Nilai</th>
	  	  <th width="5%">Type</th>
	  	  <th width="5%">ID (Baru)</th>
	  	  <th width="11%">D N (Baru)</th>
	  	  <th width="5%">U P (Baru)</th>
	  	  <th width="8%">Regional</th>
	  	  <th width="8%">Cabang</th>
	  </tr>';
$mamet = $database->doQuery('SELECT * FROM fu_ajk_cn WHERE id !="" AND id_cost="'.$q['id_cost'].'" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' AND del IS NULL ORDER BY tgl_createcn DESC LIMIT ' . $m . ' , 30');
$jummamet = $database->doQuery('SELECT * FROM fu_ajk_cn WHERE id !="" AND id_cost="'.$q['id_cost'].'" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' AND del IS NULL');
$totalRows = mysql_num_rows($jummamet);
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while ($rahmad = mysql_fetch_array($mamet)) {
$metpolis = mysql_fetch_array($database->doQuery('SELECT id, nopol FROM fu_ajk_polis WHERE id="'.$rahmad['id_nopol'].'"'));
$metpeserta = mysql_fetch_array($database->doQuery('SELECT id_dn, id_klaim, id_peserta, nama, tgl_lahir, kredit_jumlah, totalpremi FROM fu_ajk_peserta WHERE id_cost="'.$rahmad['id_cost'].'" AND id_klaim="'.$rahmad['id_cn'].'" AND id_polis="'.$rahmad['id_nopol'].'"'));
$metpeserta_new = mysql_fetch_array($database->doQuery('SELECT id_dn, id_peserta, kredit_jumlah FROM fu_ajk_peserta WHERE id_cost="'.$rahmad['id_cost'].'" AND id_dn="'.$rahmad['id_dn'].'" AND id_peserta="'.$rahmad['id_peserta'].'"'));

if ($rahmad['total_claim'] < 0) {		$nilaicnnya = 0;	}else{	$nilaicnnya=$rahmad['total_claim'];	}
if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
echo '<tr class="'.rowClass(++$i).'">
		  <td align="center">'.(++$no + ($pageNow-1) * 30).'</td>
	      <td align="center">'.$metpolis['nopol'].'</td>
	      <td align="center">'.$metpeserta['id_peserta'].'</td>
	      <td>'.$metpeserta['nama'].'</td>
	      <td align="center">'.$metpeserta['tgl_lahir'].'</td>
	      <td align="right">'.duit($metpeserta['kredit_jumlah']).'</td>
	      <td align="center">'.$metpeserta['id_dn'].'</td>
	      <td align="center">'.$rahmad['id_cn'].'</td>
	      <td align="right">'.duit($nilaicnnya).'</td>
	      <td align="center">'.$rahmad['type_claim'].'</td>
	      <td align="center">'.$metpeserta_new['id_peserta'].'</td>
	      <td align="center">'.$rahmad['id_dn'].'</td>
	      <td align="center">'.duit($metpeserta_new['kredit_jumlah']).'</td>
	      <td>'.$rahmad['id_regional'].'</td>
	      <td>'.$rahmad['id_cabang'].'</td>
	  </tr>';
}
echo '<tr><td colspan="17">';
echo createPageNavigations($file = 'client_rPeserta.php?r=rCN&laporan_peserta='.$_REQUEST['laporan_peserta'].'&id_cost='.$q['id_cost'].'&nopol='.$_REQUEST['nopol'].'&id_reg='.$_REQUEST['id_reg'].'&id_area='.$_REQUEST['id_area'].'&id_cabang='.$_REQUEST['id_cabang'].'&sdata='.$_REQUEST['sdata'].'&createcn1='.$_REQUEST['createcn1'].'&createcn2='.$_REQUEST['createcn2'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 30);
echo '<b>Total Data Credit Note (CN) : <u>' . $totalRows . '</u></b></td></tr>';
echo '</table>';
	}
}
		;
		break;
	default:
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
	  <tr><th align="left">Laporan Data Peserta</font></th></tr>
</table>
<table border="0" width="50%" cellpadding="1" cellspacing="3" align="center">
<form method="post" action="" name="postform">
<tr><td width="5%">Nomor Polis</td>
	<td width="10%">: <select name="nopol" id="nopol">
	<option value="">-- Pilih Nomor Polis --</option>';
$rpolis = mysql_query('select * from fu_ajk_polis WHERE id!="" AND id_cost="'.$q['id_cost'].'"');
while($polisnya = mysql_fetch_array($rpolis)) {
echo '<option value="'.$polisnya["id"].'">'.$polisnya['nopol'].'</option>';
}
echo '</select></td></tr>
<tr><td width="5%">Wilayah</td>
	<td width="10%">: <select name="id_reg" id="id_reg">
	<option value="">-- Select Regional --</option>';
$rows = mysql_query('select * from fu_ajk_regional WHERE id!="" AND id_cost="'.$q['id_cost'].'" '.$querydata.'');
while($row = mysql_fetch_array($rows)) {
	$sel = ""; if ($_POST["id_reg"] == $row["id"]) $sel = ' selected="selected"';
	echo '<option value="'.$row["id"].'"'.$sel.'>'.$row["name"].'</option>';
}
echo '</select> &nbsp; <select name="id_area" id="id_area"><option value="">-- Select Area --</option></select> &nbsp; <select name="id_cabang" id="id_cabang"><option value="">-- Select Cabang --</option></select></td></tr>
<tr><td width="5%">Status Peserta</td>
	<td width="10%">: <select name="speserta" id="speserta">
	<option value="">-- Pilih Status --</option>';
		$r_speserta = mysql_query('SELECT DISTINCT status_aktif FROM fu_ajk_peserta WHERE id_cost="'.$q['id_cost'].'" ORDER BY status_aktif ASC');
while($re_speserta = mysql_fetch_array($r_speserta)) {
	echo '<option value="'.$re_speserta["status_aktif"].'">'.$re_speserta['status_aktif'].'</option>';
}
echo '</select></td></tr>
<tr><td width="5%">Status Data</td>
	<td width="10%">: <select name="sdata" id="sdata">
	<option value="">-- Pilih Data --</option>';
		$r_sdata = mysql_query('SELECT DISTINCT status_peserta FROM fu_ajk_peserta WHERE id_cost="'.$q['id_cost'].'" ORDER BY status_peserta ASC');
while($re_sdata = mysql_fetch_array($r_sdata)) {
	echo '<option value="'.$re_sdata["status_peserta"].'">'.$re_sdata['status_peserta'].'</option>';
}
echo '</select></td></tr>
<tr><td>Status Pembayaran </td><td> :
		<input type="radio"'.pilih($_REQUEST['Rpembayaran'], "3").' name="Rpembayaran" value="3" checked>All &nbsp;
		<input type="radio"'.pilih($_REQUEST['Rpembayaran'], "1").' name="Rpembayaran" value="1">Paid &nbsp;
		<input type="radio"'.pilih($_REQUEST['Rpembayaran'], "0").' name="Rpembayaran" value="0">Unpaid &nbsp;
	</td></tr>
<tr><td>Tanggal Mulai Asuransi <font color="red">*</font></td><td>: ';
echo '<input type="text" id="from" name="start_ins" value="'.$_REQUEST['start_ins'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.from);return false;"/><a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.from);return false;"><img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a> s/d
	  <input type="text" id="to" name="end_ins" value="'.$_REQUEST['end_ins'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.to);return false;"/><a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.to);return false;" ><img name="popcal" align="absmiddle" style="border:none" src="./calender/calender.jpeg" width="30" height="25" border="0" alt=""></a>
	  <iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
</td></tr>
<tr><td width="5%" align="center" colspan="3"><input type="submit" name="laporan_peserta" value="Laporan" class="button"></td></tr>
</form>
</table>';
if ($_REQUEST['laporan_peserta']=="Laporan") {
//if (!$_REQUEST['id_reg']){	echo '<font color="red"><center>Silahkan pilih Regional.</center></font>';	}
//else
if (!$_REQUEST['start_ins'] OR !$_REQUEST['end_ins']){	echo '<font color="red"><center>Silahkan tentukan tanggal mulai asuransi.</center></font>';	}
else	{
echo '<a href="clients_report.php?r=peserta&id_cost='.$q['id_cost'].'&nopol='.$_REQUEST['nopol'].'&id_reg='.$_REQUEST['id_reg'].'&id_area='.$_REQUEST['id_area'].'&id_cabang='.$_REQUEST['id_cabang'].'&speserta='.$_REQUEST['speserta'].'&sdata='.$_REQUEST['sdata'].'&Rpembayaran='.$_REQUEST['Rpembayaran'].'&start_ins='.$_REQUEST['start_ins'].'&end_ins='.$_REQUEST['end_ins'].'"><img src="image/excel.png" width="30" border="0"> &nbsp; <br />Excel</a>';
//TGL MULAI KREDIT
$tgl1 = explode("-", $_REQUEST['start_ins']);	$tglawal = $tgl1[0].'-'.$tgl1[1].'-'.$tgl1[2];
$tgl2 = explode("-", $_REQUEST['end_ins']);		$tglakhir = $tgl2[0].'-'.$tgl2[1].'-'.$tgl2[2];

//REGIONAL AREA DAN CABANG
$metreg = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_regional WHERE id="'.$_REQUEST['id_reg'].'"'));
$metarea = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_area WHERE id="'.$_REQUEST['id_area'].'"'));
$metcabang = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_cabang WHERE id="'.$_REQUEST['id_cabang'].'"'));
if ($_REQUEST['nopol'])				{	$satu = 'AND id_polis = "' . $_REQUEST['nopol'] . '"';	}
if ($_REQUEST['id_reg'])			{	$dua = 'AND regional = "' . $metreg['name'] . '"';	}
if ($_REQUEST['id_area'])			{	$tiga = 'AND area = "' . $metarea['name'] . '"';	}
if ($_REQUEST['id_cabang'])			{	$empat = 'AND cabang = "' . $metcabang['name'] . '"';	}
if ($_REQUEST['speserta'])			{	$lima = 'AND status_aktif = "' . $_REQUEST['speserta'] . '"';	}
if ($_REQUEST['sdata'])				{	$enam = 'AND status_peserta = "' . $_REQUEST['sdata'] . '"';	}
if ($_REQUEST['Rpembayaran']=="3")	{	$tujuh = 'AND status_bayar != "2"';	}else{	$tujuh = 'AND status_bayar = "'.$_REQUEST['Rpembayaran'].'"';	}
if ($_REQUEST['start_ins'])			{	$delapan = 'AND vkredit_tgl BETWEEN "'.$tglawal.'" AND "'.$tglakhir.'"';	}

	define('_NEXT', '<img src="imags/right_arrow.gif" border="0">');
	define('_PREV', '<img src="image/left_arrow.gif" border="0">');
if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 30;	}	else {	$m = 0;		}
echo '<table border="0" width="100%" cellpadding="3" cellspacing="1" bgcolor="#bde0e6">
	  <tr><th width="1%">No</th>
	  	  <th width="1%">Polis</th>
	  	  <th width="5%">SPAJ</th>
	  	  <th width="5%">ID Peserta</th>
	  	  <th>Nama</th>
		  <th width="5%">Gender</th>
		  <th width="5%">DOB</th>
		  <th width="5%">Usia</th>
		  <th width="5%">Start.Ins</th>
		  <th width="5%">End.Ins</th>
	  	  <th width="5%">U P</th>
	  	  <th width="5%">Premi</th>
		  <th width="12%">D N</th>
	  	  <th width="12%">C N</th>
	  	  <th width="5%">Nilai</th>
	  	  <th width="5%">Type</th>
	  	  <th width="5%">Pembayaran</th>
	  	  <th width="5%">Tgl Bayar</th>
	  	  <th width="5%">Status</th>
	  	  <th width="8%">Regional</th>
	  	  <th width="8%">Area</th>
	  	  <th width="8%">Cabang</th>
	  </tr>';
$mamet = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id !="" AND id_dn!="" AND id_cost="'.$q['id_cost'].'" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' '.$tujuh.' '.$delapan.' AND del IS NULL ORDER BY vkredit_tgl DESC LIMIT ' . $m . ' , 30');
$jummamet = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id !="" AND id_dn!="" AND id_cost="'.$q['id_cost'].'" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' '.$tujuh.' '.$delapan.' AND del IS NULL');
$totalRows = mysql_num_rows($jummamet);
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while ($rahmad = mysql_fetch_array($mamet)) {
$metpolis = mysql_fetch_array($database->doQuery('SELECT id, nopol FROM fu_ajk_polis WHERE id="'.$rahmad['id_polis'].'"'));

$cekdn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn WHERE dn_kode="'.$rahmad['id_dn'].'" AND id_cost="'.$rahmad['id_cost'].'" AND del IS NULL'));
$cekdncn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cn WHERE id_dn="'.$rahmad['id_dn'].'" AND id_cost="'.$rahmad['id_cost'].'" AND id_peserta="'.$rahmad['id_peserta'].'" AND del IS NULL'));
if ($cekdncn['total_claim'] <= 0) {	$nilaicnnya = 0;	}	else	{	$nilaicnnya = duit($cekdncn['total_claim']);	}
$cekcnpeserta = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_klaim="'.$cekdncn['id_cn'].'"'));
/*
if ($rahmad['id_klaim']=="") {
	$rahmadcn = '';
	$rahmadcnnilai = '';
	$rahmadcntype = '';
}
else{
$metdatacn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cn WHERE id_cost="'.$rahmad['id_cost'].'" AND id_cn="'.$rahmad['id_klaim'].'" AND id_peserta="'.$rahmad['id_peserta'].'"'));
echo ('SELECT * FROM fu_ajk_peserta WHERE id_cost="'.$metdatacn['id_cost'].'" AND id_klaim="'.$metdatacn['id_cn'].'"');
echo('SELECT * FROM fu_ajk_cn WHERE id_cost="'.$rahmad['id_cost'].'" AND id_cn="'.$rahmad['id_klaim'].'" AND id_peserta="'.$rahmad['id_peserta'].'"');
	$rahmadcn = $metdatacn['id_cn'];
	$rahmadcnnilai = duit($metdatacn['total_claim']);
	$rahmadcntype = $metdatacn['type_claim'];
}
if ($rahmadcnnilai < 0) {		$nilaicnnya = 0;	}else{	$nilaicnnya=$rahmadcnnilai;	}
*/
if ($rahmad['status_bayar'] == 0) {		$statusbayar = "Unpaid";	}else{	$statusbayar = "Paid";	}
if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
echo '<tr class="'.rowClass(++$i).'">
		  <td align="center">'.(++$no + ($pageNow-1) * 30).'</td>
	      <td align="center">'.$metpolis['nopol'].'</td>
	      <td align="center">'.$rahmad['spaj'].'</td>
	      <td align="center">'.$rahmad['id_peserta'].'</td>
	      <td>'.$rahmad['nama'].'</td>
	      <td align="center">'.$rahmad['gender'].'</td>
	      <td align="center">'.$rahmad['tgl_lahir'].'</td>
	      <td align="center">'.$rahmad['usia'].'</td>
	      <td align="center">'.$rahmad['kredit_tgl'].'</td>
	      <td align="center">'.$rahmad['kredit_akhir'].'</td>
	      <td align="right">'.duit($rahmad['kredit_jumlah']).'</td>
	      <td align="right">'.duit($rahmad['totalpremi']).'</td>
	      <td align="center">'.$rahmad['id_dn'].'</td>
	      <td align="center">'.$cekcnpeserta['id_klaim'].'</td>
	      <td align="right">'.$nilaicnnya.'</td>
	      <td align="center">'.$rahmad['status_peserta'].'</td>
	      <td align="center">'.$statusbayar.'</td>
	      <td align="center">'.$cekdn['tgl_dn_paid'].'</td>
	      <td align="center">'.$rahmad['status_aktif'].'</td>
	      <td>'.$rahmad['regional'].'</td>
	      <td>'.$rahmad['area'].'</td>
	      <td>'.$rahmad['cabang'].'</td>
	  </tr>';
}
echo '<tr><td colspan="17">';
echo createPageNavigations($file = 'client_rPeserta.php?laporan_peserta='.$_REQUEST['laporan_peserta'].'&id_cost='.$q['id_cost'].'&nopol='.$_REQUEST['nopol'].'&id_reg='.$_REQUEST['id_reg'].'&id_area='.$_REQUEST['id_area'].'&id_cabang='.$_REQUEST['id_cabang'].'&speserta='.$_REQUEST['speserta'].'&sdata='.$_REQUEST['sdata'].'&Rpembayaran='.$_REQUEST['Rpembayaran'].'&start_ins='.$_REQUEST['start_ins'].'&end_ins='.$_REQUEST['end_ins'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 30);
echo '<b>Total Data Peserta : <u>' . $totalRows . '</u></b></td></tr>';
echo '</table>';
	}
}
		;
} // switch
?>
<!--WILAYAH COMBOBOX-->
<script src="javascript/metcombo/prototype.js"></script>
<script src="javascript/metcombo/dynamicombo.js"></script>
<!--WILAYAH COMBOBOX-->
<script>
document.observe("dom:loaded",function(){
	new DynamiCombo( "id_reg" , {
		elements:{
			"id_area":		{url:'javascript/metcombo/data.php?req=c', value:'id', label:'name', init:'<?php echo $_POST["id_area"] ?>'},
			"id_cabang":	{url:'javascript/metcombo/data.php?req=d', value:'id', label:'name', init:'<?php echo $_POST["id_cabang"] ?>'}
		},
		loadingImage:'loader1.gif',
		loadingText:'Loading...',
		debug:0
	} )
});
</script>