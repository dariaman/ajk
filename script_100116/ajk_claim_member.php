<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("includes/functions.php");
connect();
if (session_is_registered('nm_user')) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));	}
if ($q['wilayah']=="Pusat") {	$querydata = '';
	$querydata1 = '';
	$querydata2 = '';
}
else{	$querydata = 'AND name LIKE "%'.$q['wilayah'].'%"';
	$querydata1 = 'AND regional LIKE "%'.$q['wilayah'].'%"';
	$querydata2 = 'AND id_regional LIKE "%'.$q['wilayah'].'%"';
}
switch ($_REQUEST['fu']) {
	case "a":
		;
		break;
	case "s":
		;
		break;
	default:
echo '<table border="0" cellpadding="5" cellspacing="1" width="100%">
<tr><th width="100%" align="left">Modul Credit Note</font></th></tr>
</table>';
$cat=$_GET['cat']; // Use this line or below line if register_global is off
echo '<fieldset>
	<legend align="center">Search - Data Claim</legend>
	<table border="0" width="50%" cellpadding="3" cellspacing="1">
	<form method="post" action="ajk_claim_member.php">
	<tr><td width="10%">Regional</td>
	  <td>: <select id="cat" name="cat" onchange="reload(this.form)">
	  	<option value="">---Select Regional---</option>';
$quer2=$database->doQuery('SELECT * FROM fu_ajk_regional WHERE id_cost="'.$q['id_cost'].'" '.$querydata.' ORDER BY name ASC');
while($noticia2 = mysql_fetch_array($quer2)) {
if($noticia2['id']==$cat){echo '<option selected value="'.$noticia2['id'].'">'.$noticia2['name'].'</option><BR>';}
else{echo  '<option value="'.$noticia2['id'].'"'._selected($_REQUEST['cat'], $noticia2['id']).'>'.$noticia2['name'].'</option>';}
}
echo '</select></td></tr>
		<tr><td>Branch</td>
			<td>: ';
if(isset($cat) and strlen($cat) > 0){
	$quer=$database->doQuery('SELECT * FROM fu_ajk_cabang where id_cost="'.$q['id_cost'].'" AND id_reg="'.$cat.'" ORDER BY id ASC');
}else{$quer=$database->doQuery("SELECT * FROM fu_ajk_cabang ORDER BY id ASC"); }
echo '<select name="subcat"><option value="">---Select Branch---</option>';
while($noticia = mysql_fetch_array($quer)) {
echo  '<option value="'.$noticia['name'].'"'._selected($_REQUEST['subcat'], $noticia['name']).'>'.$noticia['name'].'</option>';
}
echo '<tr><td>Nama</td><td>: <input type="text" name="cnama" value="'.$_REQUEST['cnama'].'"></td></tr>
	<tr><td>CN Number</td><td>: <input type="text" name="ccn" value="'.$_REQUEST['ccn'].'"> &nbsp; <input type="submit" name="button" value="Search" class="button"></td></tr>
	</form>
		</table></fieldset>';
echo '<table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#E2E2E2">
	<tr><th width="1%" rowspan="2">No</th>
		<th width="3%" rowspan="2">SPAJ</th>
		<th width="5%" rowspan="2">No. DN</th>
		<th width="5%" rowspan="2">No. CN</th>
		<th rowspan="2">Nama Debitur<br /><font size="1"><i>(sesuai KTP/SIM)</i></font></th>
		<th width="1%" rowspan="2">P/W</th>
		<th rowspan="2">Tgl Lahir</th>
		<th rowspan="2">Usia</th>
		<th width="20%" colspan="6">Status Kredit</th>
		<th width="1%" rowspan="2">Premi</th>
		<th colspan="3" width="10%" >Biaya</th>
		<th width="1%" rowspan="2">Total Premi</th>
		<th colspan="7">Klaim</th>
		<th rowspan="2">Cabang</th>
		<th rowspan="2">Area</th>
		<th rowspan="2">Regional</th>
	</tr>
	<tr><th>Kredit Awal</th>
		<th>Jumlah</th>
		<th>Tenor</th>
		<th>Kredit Akhir</th>
		<th>Movement Date</th>
		<th>Movement Tenor</th>
		<th>Adm</th>
		<th>Refund</th>
		<th>Ext. Premi</th>
		<th>Claim</th>
		<th>MA-j</th>
		<th>MA-s</th>
		<th>Jumlah</th>
		<th>Tanggal</th>
		<th>Status</th>
		<th>Tgl Bayar</th>
	</tr>';

if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}
else {	$m = 0;		}

//echo $_REQUEST['cat'].'<br />';
//echo $_REQUEST['subcat'].'<br />';

$cekreg = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['cat'].'"'));
if ($_REQUEST['cat'])		{	$empat = 'AND id_regional = "' . $cekreg['name'] . '"';		}

if ($_REQUEST['subcat'])	{	$tiga = 'AND id_cabang LIKE "%' . $_REQUEST['subcat'] . '%"';		}
if ($_REQUEST['ccn'])		{	$satu = 'AND id_cn LIKE "%' . $_REQUEST['ccn'] . '%"';		}
if ($_REQUEST['cnama'])		{	$dua = 'AND nama LIKE "%' . $_REQUEST['cnama'] . '%"';		}
//echo $empat;
$ccnnama = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id != "" AND id_klaim !="" '.$querydata1.' '.$dua.''));		//PESERTA LAMA
if ($_REQUEST['cnama'])		{	$duaS = 'AND id_cn LIKE "%' . $ccnnama['id_klaim'] . '%"';		}

$data = $database->doQuery('SELECT * FROM fu_ajk_cn WHERE id!="" AND id_cost="'.$q['id_cost'].'" AND del is null '.$querydata2.' '.$satu.' '.$duaS.' '.$tiga.' '.$empat.' ORDER BY id DESC, input_by DESC LIMIT ' . $m . ' , 25');
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_cn WHERE id != "" AND del IS NULL  AND id_cost="'.$q['id_cost'].'"  '.$querydata2.' '.$satu.' '.$duaS.' '.$tiga.' '.$empat.''));
$totalRows = $totalRows[0];
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while ($fudata = mysql_fetch_array($data)) {

	$pesertadn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn WHERE dn_kode = "'.$fudata['id_dn'].'"'));
	if ($fudata['type_claim']=="Refund") {
	$peserta = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_klaim = "'.$fudata['id_cn'].'" AND id_peserta="'.$fudata['id_peserta'].'"'));		//PESERTA LAMA
	}else{
	$peserta = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_klaim = "'.$fudata['id_cn'].'"'));		//PESERTA LAMA
	}

	$cekpeserta = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_dn="'.$fudata['id_dn'].'" AND nama="'.$peserta['nama'].'" AND status_peserta!="1"'));	//PESERTA BARU
	$metcnklaim = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_klaim WHERE id_cn = "'.$fudata['id_cn'].'"'));

if ($peserta['cabang']=="") {	$bbupd = $peserta['cabang_lama'];	}	else	{	$bbupd = $peserta['cabang'];	}
$bb = $database->doQuery('UPDATE fu_ajk_cn SET id_cabang="'.$bbupd.'" WHERE id_cn="'.$peserta['id_klaim'].'" ');

if ($peserta['cabang']=="") 	{	$rcabang = $peserta['cabang_lama'];	}	else	{	$rcabang = $peserta['cabang'];	}
if ($peserta['area']=="") 		{	$rarea = $peserta['area_lama'];	}	else	{	$rarea = $peserta['area'];	}
if ($peserta['regional']=="") 	{	$rregional = $peserta['regional_lama'];	}	else	{	$rregional = $peserta['regional'];	}

	if ($fudata['confirm_claim']=="Approve(paid)") {	$statklaim = '<a href="ajk_report_fu.php?fu=ajkpdfcn&id='.$fudata['id'].'"><font color="green">'. $fudata['confirm_claim'].'</font> </a>';	}
	elseif ($fudata['confirm_claim']=="Approve(unpaid)") {	$statklaim = '<a href="ajk_cn.php?fu=unapp&id='.$fudata['id'].'"><font color="red">'.$fudata['confirm_claim'].'</font> </a>';	}
	elseif ($fudata['confirm_claim']=="Processing") {	$statklaim = '<a href="ajk_cn.php?fu=proses&id='.$fudata['id'].'"><font color="red">'.$fudata['confirm_claim'].'</font> </a>';	}
	else{	$statklaim = '<a href="ajk_cn.php?fu=reject&id='.$fudata['id'].'"><font color="red">'.$fudata['confirm_claim'].'</font> </a>';	}


	//	$z = $database->doQuery('UPDATE fu_ajk_peserta SET id_klaim="'.$fudata['id_cn'].'", status_aktif="Lapse", status_peserta="Death" WHERE id_peserta="'.$fudata['id_peserta'].'" AND id_dn="'.$fudata['id_dn'].'"');
/*	$c = $database->doQuery('UPDATE fu_ajk_klaim SET id_cn="'.$fudata['id_cn'].'" WHERE id_dn="'.$fudata['id_dn'].'"');
   if ($fudata['premi']=="") {
   $x = $database->doQuery('UPDATE fu_ajk_cn SET premi="'.$peserta['totalpremi'].'", type_claim="Death" WHERE id_peserta="'.$fudata['id_peserta'].'" AND id_dn="'.$fudata['id_dn'].'"');
   }else{

   }*/
if ($fudata['type_claim']=="Death")
{	$kredit  = explode("/", $peserta['kredit_tgl']);
	$kredithr = $kredit[0];	$kreditbl = $kredit[1];	$kreditth = $kredit[2];
	$movementdate = $fudata['tgl_claim'];
	$tenorbaru='';

	$kredit2  = explode("/", $fudata['tgl_claim']);	$kredithr2 = $kredit2[0];	$kreditbl2 = $kredit2[1];	$kreditth2 = $kredit2[2];
}
else
{		$movementdate = $cekpeserta['kredit_tgl'];

	$awal = explode ("/", $peserta['kredit_tgl']);		$hari = $awal[0];	$bulan = $awal[1];		$tahun = $awal[2];
	$akhir = explode ("/", $cekpeserta['kredit_tgl']);	$hari2 = $akhir[0];	$bulan2 = $akhir[1];	$tahun2 = $akhir[2];
	$jhari=(mktime (0,0,0,$bulan2,$hari2,$tahun2) - mktime (0,0,0,$bulan,$hari,$tahun))/86400;
	$sisahr=floor($jhari);
	$sisabulan =ceil($sisahr / 30.25);
}
	$masisa = $peserta['kredit_tenor'] - $sisabulan;

	if ($fudata['type_claim']=="Death" OR $fudata['type_claim']=="Refund") {
		$dnclaim ='<a href="ajk_report.php?fu=ajkpdfinvdn&invmove=movemant&id='.$peserta['id'].'" target="_blank">'.substr($peserta['id_dn'], 6).'</a>';
		$jumlahnya = $fudata['total_claim'];
	}else{
		//total premi cn
/*UPDATE manual perhitungan nilai cn
   $hitungcn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_rasio_claim WHERE id_cost="'.$peserta['id_cost'].'" AND id_polis="'.$peserta['id_polis'].'"'));
   if ($fudata['type_claim']=="Top Up") {
   $jumlahnya = ((($peserta['kredit_tenor'] - $majalan) / $peserta['kredit_tenor'])* $hitungcn['topup'])* $peserta['premi'];
   $t = $database->doQuery('UPDATE fu_ajk_cn SET total_claim="'.$jumlahnya.'" WHERE type_claim="Top Up" AND id="'.$fudata['id'].'"');
   }else{
   $jumlahnya = ((($peserta['kredit_tenor'] - $majalan) / $peserta['kredit_tenor'])* $hitungcn['restruktur'])* $peserta['premi'];
   $t = $database->doQuery('UPDATE fu_ajk_cn SET total_claim="'.$jumlahnya.'" WHERE type_claim="Restruktur" AND id="'.$fudata['id'].'"');
   }
*/
		//total premi cn
		$dnclaim ='<a href="ajk_report.php?fu=ajkpdfinvdn&invmove=movemant&id='.$cekpeserta['id'].'" target="_blank">'.substr($cekpeserta['id_dn'], 6).'</a>';
	}
if ($fudata['total_claim'] < 0) { $totalclaimnya = 0;	}else{ $totalclaimnya =	$fudata['total_claim'];}
echo '<tr class="'.rowClass(++$i).'">
		  <td align="center" valign="top">'.(++$no + ($pageNow-1) * 25).'</td>
		  <td>'.$peserta['spaj'].'</td>
		  <td>'.$dnclaim.'</td>
		  <td><a href="ajk_report.php?fu=ajkpdfcn&id='.$fudata['id'].'" target="_blank">'.substr($fudata['id_cn'], 6).'</a></td>
		  <td>'.$peserta['nama'].'</td>
		  <td align="center">'.$peserta['gender'].'</td>
		  <td align="center">'.$peserta['tgl_lahir'].'</td>
		  <td align="center">'.$peserta['usia'].'</td>
		  <td align="center">'.$peserta['kredit_tgl'].'</td>
		  <td align="right">'.duit($peserta['kredit_jumlah']).'</td>
		  <td align="center">'.$peserta['kredit_tenor'].'</td>
		  <td align="center">'.$peserta['kredit_akhir'].'</td>
		  <td align="center">'.$movementdate.'</td>
		  <td align="center">'.$cekpeserta['kredit_tenor'].'</td>
		  <td align="right">'.duit($peserta['premi']).'</td>
		  <td align="right">'.duit($peserta['biaya_adm_lost']).'</td>		<!-- Biaya ADM tidak di tampilkan field seharusnya $peserta[biaya_adm] 30-04-2013 -->
		  <td align="right">'.duit($peserta['biaya_refund']).'</td>
		  <td align="right">'.duit($peserta['ext_premi']).'</td>
		  <td align="right">'.duit($peserta['totalpremi'] - $peserta['biaya_adm']).'</td>
		  <td align="right">'.$fudata['type_claim'].'</td>
		  <td align="center">'.$sisabulan.'</td>
		  <td align="center">'.$masisa.'</td>
		  <td align="right"><b>'.duit(ceil($totalclaimnya)).'</b></td>
		  <td align="center"><b>'.$fudata['tgl_claim'].'</b></td>
		  <td align="center"><b>'.$statklaim.'</b></td>
		  <td align="center">'._convertDate($fudata['tgl_byr_claim']).'</td>
		  <td align="center">'.$rcabang.'</td>
		  <td align="center">'.$rarea.'</td>
		  <td align="center">'.$rregional.'</td>
		  </tr>';
}
		echo '<tr><td colspan="22">';
		echo createPageNavigations($file = 'ajk_claim_member.php?cat='.$_REQUEST['cat'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
		echo '<b>Total Data Peserta: <u>' . $totalRows . '</u></b></td></tr>';
		echo '</table>';
		;
} // switch
?>
<SCRIPT language=JavaScript>
function reload(form)
{
	var val=form.cat.options[form.cat.options.selectedIndex].value;
	self.location='ajk_claim_member.php?cat=' + val;
}
</script>