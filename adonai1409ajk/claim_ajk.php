<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// Relife - AJK Online 2013
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("../includes/functions.php");
connect();
if (session_is_registered('nm_user')) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));}
$futgl = date("Y-m-d H:i:s");
$futgldn = date("d/m/Y");
$futgliddn = date("Y");
$futgldnIng = date("Y-m-d");
switch ($_REQUEST['r']) {
	case "refund":
$cat=$_GET['cat']; 	if(strlen($cat) > 0 and !is_numeric($cat)){ echo "Data Error";	exit;	}

echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
		<tr><th width="90%" align="left">Modul Report Refund</font></th></th></tr>
		</table>';
echo '<fieldset style="padding: 2">
	<legend align="center">S e a r c h - Report Refund</legend>
	<table border="0" width="100%" cellpadding="1" cellspacing="1">
	<form method="post" action="">
	<tr><td width="20%" align="right">Company</td>
		<td width="30%">: <select id="cat" name="cat" onchange="reloadref(this.form)">
	  	<option value="">---Select Company---</option>';
$quer2=$database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name ASC');
while($noticia2 = mysql_fetch_array($quer2)) {
if($noticia2['id']==$cat){echo '<option selected value="'.$noticia2['id'].'">'.$noticia2['name'].'</option><BR>';}
else{echo  '<option value="'.$noticia2['id'].'">'.$noticia2['name'].'</option>';}
}
echo '</select></td></tr>
<tr><td align="right">Regional</td>
	<td>: ';
if(isset($cat) and strlen($cat) > 0){
	$quer=$database->doQuery('SELECT * FROM fu_ajk_regional where id_cost="'.$cat.'" ORDER BY name ASC');
}else{$quer=$database->doQuery("SELECT * FROM fu_ajk_regional ORDER BY id ASC"); }
		echo '<select name="subcat"><option value="">---Select Regional---</option>';
while($noticia = mysql_fetch_array($quer)) {
	echo  '<option value="'.$noticia['id'].'">'.$noticia['name'].'</option>';
}
echo '</td></tr>
	<tr><td align="right">Create Refund </td><td>:
		<input type="text" name="tanggal1" id="tanggal1" class="tanggal" value="'.$_REQUEST['tanggal1'].'" size="10"/> s/d
		<input type="text" name="tanggal2" id="tanggal2" class="tanggal" value="'.$_REQUEST['tanggal2'].'" size="10"/>';
echo '</td></tr>
	  <tr><td width="5%" align="center" colspan="3"><input type="submit" name="metreportrefund" value="Cari" class="button"></td></tr>
	  </form></table></fieldset>';
if ($_REQUEST['metreportrefund']=="Cari") {
	if ($_REQUEST['cat']=="") {
		echo '<div align="center"><font color="red"><blink>Silahkan pilih nama Costumer...!!</div></font></blink>';
	}elseif ($_REQUEST['tanggal1']=="" OR $_REQUEST['tanggal2']==""){
		echo '<div align="center"><font color="red"><blink>Tanggal tidak boleh kosong...!!</div></font></blink>';
	}else{
echo '<div align="right"><a href="ajk_report_fu.php?fu=metrefund&tanggal1='.$_REQUEST['tanggal1'].'&tanggal2='.$_REQUEST['tanggal2'].'&cat='.$_REQUEST['cat'].'&subcat='.$_REQUEST['subcat'].'" target="_blank" title="Report Refund ke Excel"><img src="image/excel.png" width="28"><br />excel</a></div>';
echo '<table border="0" width="100%" cellpadding="3" cellspacing="0"  bgcolor="#E2E2E2">
		<tr><td bgcolor="#FFF"colspan="25"></td></tr>
		<th width="1%">No</th>
		<th width="5%">Policy</th>
		<th width="11%">DN</th>
		<th width="5%">Date DN</th>
		<th width="11%">CN</th>
		<th width="5%">Date CN</th>
		<th width="5%">ID</th>
		<th width="20%">Name</th>
		<th width="5%">DOB</th>
		<th width="5%">Start.Ins</th>
		<th width="1%">Tenor</th>
		<th width="5%">End.Ins</th>
		<th width="5%">U P</th>
		<th width="1%">MA-j</th>
		<th width="1%">MA-s</th>
		<th width="5%">Refund Premi</th>
		<th width="5%">Date Refund</th>
		<th width="12%">Branch</th>
		<th width="12%">Regional</th>
		</tr>';
$tgl1 = explode("/", $_REQUEST['tanggal1']);	$tglawal = $tgl1[2].'-'.$tgl1[1].'-'.$tgl1[0];
$tgl2 = explode("/", $_REQUEST['tanggal2']);	$tglakhir = $tgl2[2].'-'.$tgl2[1].'-'.$tgl2[0];
if ($_REQUEST['cat'])			{	$satu = 'AND id_cost ="' . $cat . '"';			}
if ($_REQUEST['subcat'])		{	$cekregionalnya = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['subcat'].'"'));
									$dua = 'AND id_regional ="' . $cekregionalnya['name'] . '"';
								}
if ($_REQUEST['tanggal1'])		{	$tiga = 'AND tgl_createcn BETWEEN "'.$tglawal.'" AND "'.$tglakhir.'" ';	}

if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 50;	}	else {	$m = 0;		}
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
$metrefund = $database->doQuery('SELECT * FROM fu_ajk_cn WHERE id!="" '.$satu.' '.$dua.' '.$tiga.' AND type_claim="Refund" AND del IS NULL ORDER BY tgl_createcn ASC LIMIT ' . $m . ' , 50');
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_cn WHERE id != "" ' . $satu . ' '.$dua.' '.$tiga.' AND type_claim="Refund" AND del IS NULL '));
$totalRows = $totalRows[0];

while ($setrefund = mysql_fetch_array($metrefund)) {
if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
$refpolis = mysql_fetch_array($database->doQuery('SELECT id, nopol FROM fu_ajk_polis WHERE id="'.$setrefund['id_nopol'].'"'));			//POLISNYA
$refdn = mysql_fetch_array($database->doQuery('SELECT dn_kode, tgl_createdn FROM fu_ajk_dn WHERE dn_kode="'.$setrefund['id_dn'].'"'));				//TANGGAL DNNYA

// MASA ASURANSI BERJALAN
$metpeserta = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_dn="'.$setrefund['id_dn'].'" AND id_klaim="'.$setrefund['id_cn'].'" AND id_peserta="'.$setrefund['id_peserta'].'" '));
$kredit = explode("/", $metpeserta['kredit_tgl']);
$nowkredit = $kredit[2].'-'.$kredit[1].'-'.$kredit[0];
$now = new T10DateCalc($nowkredit);
$periodbulan = $now->compareDate($setrefund['tgl_claim']) / 30.4375;
$maj = ceil($periodbulan);
// MASA ASURANSI BERJALAN
// MASA ASURANSI SISA
$r = $metpeserta['kredit_tenor'] - $maj;
if ($r < 0) {	$mas = 0;	}else{	$mas = $r;	}
// MASA ASURANSI SISA
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
			  <td align="center">'.(++$no + ($pageNow-1) * 50).'</td>
			  <td align="center">'.$refpolis['nopol'].'</td>
			  <td align="center">'.$setrefund['id_dn'].'</td>
			  <td align="center">'._convertDate($refdn['tgl_createdn']).'</td>
			  <td align="center">'.$setrefund['id_cn'].'</td>
			  <td align="center">'._convertDate($setrefund['tgl_createcn']).'</td>
			  <td align="center">'.$setrefund['id_peserta'].'</td>
			  <td>'.$metpeserta['nama'].'</td>
			  <td align="center">'.$metpeserta['tgl_lahir'].'</td>
			  <td align="center">'.$metpeserta['kredit_tgl'].'</td>
			  <td align="center">'.$metpeserta['kredit_tenor'].'</td>
			  <td align="center">'.$metpeserta['kredit_akhir'].'</td>
			  <td align="right">'.duit($metpeserta['kredit_jumlah']).'</td>
			  <td align="center">'.$maj.'</td>
			  <td align="center">'.$mas.'</td>
			  <td align="right">'.duit($setrefund['total_claim']).'</td>
			  <td align="center">'._convertDate($setrefund['tgl_claim']).'</td>
			  <td align="center">'.$setrefund['id_cabang'].'</td>
			  <td align="center">'.$setrefund['id_regional'].'</td>
		</tr>';
}
echo '<tr><td colspan="22">';
echo createPageNavigations($file = 'claim_ajk.php?r=refund&metreportrefund=Cari&tanggal1='.$_REQUEST['tanggal1'].'&tanggal2='.$_REQUEST['tanggal2'].'&cat='.$_REQUEST['cat'].'&subcat='.$_REQUEST['subcat'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 50);
echo '<b>Total Data Refund: <u>' . $totalRows . '</u></b></td></tr>';
echo '</table>';
	}
}
		;
		break;
	case "meninggal":
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%"><tr><th width="90%" align="left">Modul Report Refund</font></th></th></tr></table>';
		$cat=$_GET['cat']; 	if(strlen($cat) > 0 and !is_numeric($cat)){ echo "Data Error";	exit;	}

echo '<fieldset style="padding: 2">
	<legend align="center">S e a r c h - Report Refund</legend>
	<table border="0" width="100%" cellpadding="1" cellspacing="1">
	<form method="post" action="">
	<tr><td width="20%" align="right">Company</td>
		<td width="30%">: <select id="cat" name="cat" onchange="reloaddeath(this.form)">
	  	<option value="">---Select Company---</option>';
$quer2=$database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name ASC');
while($noticia2 = mysql_fetch_array($quer2)) {
	if($noticia2['id']==$cat){echo '<option selected value="'.$noticia2['id'].'">'.$noticia2['name'].'</option><BR>';}
	else{echo  '<option value="'.$noticia2['id'].'">'.$noticia2['name'].'</option>';}
}
echo '</select></td></tr>
	<tr><td align="right">Regional</td>
	<td>: ';
if(isset($cat) and strlen($cat) > 0){
$quer=$database->doQuery('SELECT * FROM fu_ajk_regional where id_cost="'.$cat.'" ORDER BY name ASC');
}else{$quer=$database->doQuery("SELECT * FROM fu_ajk_regional ORDER BY id ASC"); }
echo '<select name="subcat"><option value="">---Select Regional---</option>';
while($noticia = mysql_fetch_array($quer)) {
	echo  '<option value="'.$noticia['id'].'">'.$noticia['name'].'</option>';
}
echo '</td></tr>
	<tr><td align="right">Create Tanggal CN </td><td>:
		<input type="text" name="tanggal1" id="tanggal1" class="tanggal" value="'.$_REQUEST['tanggal1'].'" size="10"/> s/d
		<input type="text" name="tanggal2" id="tanggal2" class="tanggal" value="'.$_REQUEST['tanggal2'].'" size="10"/>';
echo '</td></tr>
	  <tr><td width="5%" align="center" colspan="3"><input type="submit" name="metreportdet" value="Cari" class="button"></td></tr>
	  </form></table></fieldset>';
if ($_REQUEST['metreportdet']=="Cari") {
	if ($_REQUEST['cat']=="") {
		echo '<div align="center"><font color="red"><blink>Silahkan pilih nama Costumer...!!</div></font></blink>';
	}elseif ($_REQUEST['tanggal1']=="" OR $_REQUEST['tanggal2']==""){
		echo '<div align="center"><font color="red"><blink>Tanggal tidak boleh kosong...!!</div></font></blink>';
	}else{
echo '<div align="right"><a href="ajk_report_fu.php?fu=metrefund&tanggal1='.$_REQUEST['tanggal1'].'&tanggal2='.$_REQUEST['tanggal2'].'&cat='.$_REQUEST['cat'].'&subcat='.$_REQUEST['subcat'].'" target="_blank" title="Report Refund ke Excel"><img src="image/excel.png" width="28"><br />excel</a></div>';
echo '<table border="0" width="100%" cellpadding="3" cellspacing="0"  bgcolor="#E2E2E2">
		<tr><td bgcolor="#FFF"colspan="25"></td></tr>
			<th width="1%">No</th>
			<th width="5%">Policy</th>
			<th width="11%">DN</th>
			<th width="5%">Date DN</th>
			<th width="11%">CN</th>
			<th width="5%">Date CN</th>
			<th width="5%">ID</th>
			<th width="20%">Name</th>
			<th width="5%">DOB</th>
			<th width="5%">Start.Ins</th>
			<th width="1%">Tenor</th>
			<th width="5%">End.Ins</th>
			<th width="5%">U P</th>
			<th width="1%">MA-j</th>
			<th width="1%">MA-s</th>
			<th width="5%">Refund Premi</th>
			<th width="5%">Date Refund</th>
			<th width="12%">Branch</th>
			<th width="12%">Regional</th>
		</tr>';
$tgl1 = explode("/", $_REQUEST['tanggal1']);	$tglawal = $tgl1[2].'-'.$tgl1[1].'-'.$tgl1[0];
$tgl2 = explode("/", $_REQUEST['tanggal2']);	$tglakhir = $tgl2[2].'-'.$tgl2[1].'-'.$tgl2[0];
if ($_REQUEST['cat'])			{	$satu = 'AND id_cost ="' . $cat . '"';			}
if ($_REQUEST['subcat'])		{	$cekregionalnya = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['subcat'].'"'));
	$dua = 'AND id_regional ="' . $cekregionalnya['name'] . '"';
}
if ($_REQUEST['tanggal1'])		{	$tiga = 'AND tgl_createcn BETWEEN "'.$tglawal.'" AND "'.$tglakhir.'" ';	}

if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 50;	}	else {	$m = 0;		}
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
$metrefund = $database->doQuery('SELECT * FROM fu_ajk_cn WHERE id!="" '.$satu.' '.$dua.' '.$tiga.' AND type_claim="Death" AND del IS NULL ORDER BY tgl_createcn ASC LIMIT ' . $m . ' , 50');
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_cn WHERE id != "" ' . $satu . ' '.$dua.' '.$tiga.' AND type_claim="Death" AND del IS NULL '));
$totalRows = $totalRows[0];

while ($setrefund = mysql_fetch_array($metrefund)) {
if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
	$refpolis = mysql_fetch_array($database->doQuery('SELECT id, nopol FROM fu_ajk_polis WHERE id="'.$setrefund['id_nopol'].'"'));			//POLISNYA
	$refdn = mysql_fetch_array($database->doQuery('SELECT dn_kode, tgl_createdn FROM fu_ajk_dn WHERE dn_kode="'.$setrefund['id_dn'].'"'));				//TANGGAL DNNYA

	// MASA ASURANSI BERJALAN
	$metpeserta = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_dn="'.$setrefund['id_dn'].'" AND id_klaim="'.$setrefund['id_cn'].'" AND id_peserta="'.$setrefund['id_peserta'].'" '));
	$kredit = explode("/", $metpeserta['kredit_tgl']);
	$nowkredit = $kredit[2].'-'.$kredit[1].'-'.$kredit[0];
	$now = new T10DateCalc($nowkredit);
	$periodbulan = $now->compareDate($setrefund['tgl_claim']) / 30.4375;
	$maj = ceil($periodbulan);
	// MASA ASURANSI BERJALAN
	// MASA ASURANSI SISA
	$r = $metpeserta['kredit_tenor'] - $maj;
	if ($r < 0) {	$mas = 0;	}else{	$mas = $r;	}
	// MASA ASURANSI SISA
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		<td align="center">'.(++$no + ($pageNow-1) * 50).'</td>
		<td align="center">'.$refpolis['nopol'].'</td>
		<td align="center">'.$setrefund['id_dn'].'</td>
		<td align="center">'._convertDate($refdn['tgl_createdn']).'</td>
		<td align="center">'.$setrefund['id_cn'].'</td>
		<td align="center">'._convertDate($setrefund['tgl_createcn']).'</td>
		<td align="center">'.$setrefund['id_peserta'].'</td>
		<td>'.$metpeserta['nama'].'</td>
		<td align="center">'.$metpeserta['tgl_lahir'].'</td>
		<td align="center">'.$metpeserta['kredit_tgl'].'</td>
		<td align="center">'.$metpeserta['kredit_tenor'].'</td>
		<td align="center">'.$metpeserta['kredit_akhir'].'</td>
		<td align="right">'.duit($metpeserta['kredit_jumlah']).'</td>
		<td align="center">'.$maj.'</td>
		<td align="center">'.$mas.'</td>
		<td align="right">'.duit($setrefund['total_claim']).'</td>
		<td align="center">'._convertDate($setrefund['tgl_claim']).'</td>
		<td align="center">'.$setrefund['id_cabang'].'</td>
		<td align="center">'.$setrefund['id_regional'].'</td>
	</tr>';
	}
echo '<tr><td colspan="22">';
echo createPageNavigations($file = 'claim_ajk.php?r=meninggal&metreportrefund=Cari&tanggal1='.$_REQUEST['tanggal1'].'&tanggal2='.$_REQUEST['tanggal2'].'&cat='.$_REQUEST['cat'].'&subcat='.$_REQUEST['subcat'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 50);
echo '<b>Total Data Meninggal: <u>' . $totalRows . '</u></b></td></tr>';
echo '</table>';
		}
	}
	;
	break;
	default:
		;
} // switch


?>
<SCRIPT language=JavaScript>
function reloadref(form)
{
	var val=form.cat.options[form.cat.options.selectedIndex].value;
	self.location='claim_ajk.php?r=refund&cat=' + val;
}
</script>
<SCRIPT language=JavaScript>
function reloaddeath(form)
{
	var val=form.cat.options[form.cat.options.selectedIndex].value;
	self.location='claim_ajk.php?r=meninggal&cat=' + val;
}
</script>