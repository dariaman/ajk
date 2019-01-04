<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("../includes/functions.php");
connect();
if (session_is_registered('nm_user')) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));	}
switch ($_REQUEST['r']) {
	case "cojcn":
echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
		<tr><th colspan="8" width="100%" align="left" colspan="2">Modul Count of Job Credit Note</font></th></tr>
		</table><br />';
echo '<fieldset style="padding: 2">
	<legend align="center">S e a r c h - Report Count of Job ( CN )</legend>
	<table border="0" width="100%" cellpadding="3" cellspacing="1">
	<form method="post" action="coj.php?r=cojcn&met=view">
		<tr><td width="45%" align="right">Tanggal Proses :</td><td>
			<input type="text" name="tanggal1" id="tanggal1" class="tanggal" value="'.$_REQUEST['tanggal1'].'" size="10"/> s/d
			<input type="text" name="tanggal2" id="tanggal2" class="tanggal" value="'.$_REQUEST['tanggal2'].'" size="10"/></td>
			<td align="left" width="40%"><input type="submit" name="metreportcn" value="Cari" class="button"></td></tr>
		</form></table></fieldset>';
if ($_REQUEST['met']=="view") {
	$tgl1 = explode("/", $_REQUEST['tanggal1']);	$tglawal = $tgl1[2].'-'.$tgl1[1].'-'.$tgl1[0];
	$tgl2 = explode("/", $_REQUEST['tanggal2']);	$tglakhir = $tgl2[2].'-'.$tgl2[1].'-'.$tgl2[0];
if ($_REQUEST['tanggal1']!='' AND $_REQUEST['tanggal2']!='')	{	$satu='AND DATE_FORMAT(fu_ajk_cn.input_date,"%Y-%m-%d") BETWEEN "'.$tglawal.'" AND "'.$tglakhir.'"';	}

$totalcn = $database->doQuery('SELECT *, SUM(total_claim) AS totalcn FROM fu_ajk_cn WHERE id !="" AND id_cn!="" '.$satu.' AND del IS NULL GROUP BY id');
$jumcnnya = mysql_num_rows($totalcn);
while ($jumtotalcn = mysql_fetch_array($totalcn)){
	$e += $jumtotalcn['totalcn'];
}
echo '<table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#C6DEF7">
		  <tr><td width="10%"><b>Total Premi</b></td><td><b>: Rp. '.duit($e).'</b></td></tr>
		  <tr><td><b>Jumlah CN</b></td><td><b>: '.duit($jumcnnya).' Debit Note</td></tr>
		  </table>';

echo '<table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#bde0e6">
		<tr><th width="1%">No</th>
			<th>Client</th>
			<th width="10%">Credit Note</th>
			<th width="10%">Tanggal CN</th>
			<th width="2%">Peserta</th>
			<th width="8%">Premi</th>
			<th width="12%">Regional</th>
			<th width="12%">Cabang</th>
			<th width="10%">Input Time</th>
			<th width="5%">User</th>
		</tr>';

if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 50;	}	else	{	$m = 0;		}
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
$mamet = $database->doQuery('SELECT * FROM fu_ajk_cn WHERE fu_ajk_cn.id!="" AND id_cn!="" '.$satu.' AND fu_ajk_cn.del IS NULL ORDER BY id_cost ASC, fu_ajk_cn.input_date, fu_ajk_cn.tgl_createcn ASC, fu_ajk_cn.id_cn ASC LIMIT ' . $m . ' , 50');
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(fu_ajk_cn.id) FROM fu_ajk_cn WHERE id != "" AND id_cn!="" '.$satu.' AND del IS NULL '));
$totalRows = $totalRows[0];
while ($met = mysql_fetch_array($mamet)) {
$metcn = mysql_num_rows($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_klaim = "'.$met['id_cn'].'" '));
$cost = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$met['id_cost'].'"'));
if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
				<td align="center">'.(++$no + ($pageNow-1) * 50).'</td>
				<td align="center">'.$cost['name'].'</td>
				<td align="center">'.$met['id_cn'].'</td>
				<td align="center">'._convertDate($met['tgl_createcn']).'</td>
				<td align="center">'.duit($metcn).'</td>
				<td align="right">'.duit($met['total_claim']).'</td>
				<td align="center">'.$met['id_regional'].'</td>
				<td align="center">'.$met['id_cabang'].'</td>
				<td align="center">'.$met['input_date'].'</td>
				<td align="center">'.$met['input_by'].'</td>
			  </tr>';
	$subtotal += $met['total_claim'];
	$subtotalpeserta += $metcn;
}
echo '<tr bgcolor="white"><td colspan="4" align="right"><b>Sub Total :</b></td>
							  <td align="center"><b>'.duit($subtotalpeserta).'</b></td>
							  <td align="right"><b>'.duit($subtotal).'</b></td>
							  <td colspan="5">&nbsp;</td>
		  </tr>';
	echo '<tr><td colspan="11">';
	echo createPageNavigations($file = 'coj.php?r=cojcn&met=view&tanggal1='.$_REQUEST['tanggal1'].'&tanggal2='.$_REQUEST['tanggal2'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 50);
	echo '<b>Total Data CN: <u>' . $totalRows . '</u></b></td></tr>';
	echo '</table>';
}
		;
		break;
	case "cojdn":
echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
		<tr><th colspan="8" width="100%" align="left" colspan="2">Modul Count of Job Debit Note</font></th></tr>
		</table><br />';
echo '<fieldset style="padding: 2">
	<legend align="center">S e a r c h - Report Count of Job ( DN )</legend>
	<table border="0" width="100%" cellpadding="3" cellspacing="1">
	<form method="post" action="coj.php?r=cojdn&met=view">
		<tr><td width="45%" align="right">Tanggal Proses :</td><td>
			<input type="text" name="tanggal1" id="tanggal1" class="tanggal" value="'.$_REQUEST['tanggal1'].'" size="10"/> s/d
			<input type="text" name="tanggal2" id="tanggal2" class="tanggal" value="'.$_REQUEST['tanggal2'].'" size="10"/></td>
		</td></tr>
		<tr><td width="45%" align="right">User :</td><td>';
$metuser = $database->doQuery('SELECT * FROM pengguna WHERE status="10"');
echo '<select name="metuser"><option value="">Pilih Nama</option>';
while($metganteng = mysql_fetch_array($metuser)) {
	echo  '<option value="'.$metganteng['nm_user'].'">'.$metganteng['nm_user'].'</option>';
}
echo '</select></td></tr>
	  <tr><td align="center" colspan="2"><input type="submit" name="metreportcn" value="Cari" class="button"></td></tr>';
echo '	</td></tr>
		</form></table></fieldset>';
if ($_REQUEST['met']=="view") {
	if ($_REQUEST['tanggal1']=="" OR $_REQUEST['tanggal2']=="") {
	echo '<table width="100%"><tr><td width="25%">&nbsp;</td><td class="kolomerrorARM">Kolom tidak boleh ada yang kosong</td></tr></table>';
	}else{
	$tgl1 = explode("/", $_REQUEST['tanggal1']);	$tglawal = $tgl1[2].'-'.$tgl1[1].'-'.$tgl1[0];
	$tgl2 = explode("/", $_REQUEST['tanggal2']);	$tglakhir = $tgl2[2].'-'.$tgl2[1].'-'.$tgl2[0];

if ($_REQUEST['tanggal1']!='' AND $_REQUEST['tanggal2']!='')	{	$satu='AND DATE_FORMAT(input_time,"%Y-%m-%d") BETWEEN "'.$tglawal.'" AND "'.$tglakhir.'"';	}
if ($_REQUEST['metuser'])										{	$dua = 'AND input_by LIKE "%' . $_REQUEST['metuser'] . '%"';			}

$totaldn = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id !="" '.$satu.' '.$dua.' AND del IS NULL');
$jumdnnya = mysql_num_rows($totaldn);
while ($jumtotaldn = mysql_fetch_array($totaldn)){
	$e += $jumtotaldn['totalpremi'];
}
echo '<table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#C6DEF7">
		  <tr><td width="10%"><b>Total Premi</b></td><td><b>: Rp. '.duit($e).'</b></td></tr>
		  <tr><td><b>Jumlah DN</b></td><td><b>: '.duit($jumdnnya).' Debit Note</td></tr>
		  </table>';

echo '<table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#bde0e6">
		<tr><th width="1%">No</th>
			<th>Client</th>
			<th width="15%">Nama </th>
			<th width="10%">Debit Note</th>
			<th width="5%">Tanggal DN</th>
			<th width="5%">Premi</th>
			<th width="5%">Status</th>
			<th width="5%">Movement</th>
			<th width="5%">Regional</th>
			<th width="10%">Area</th>
			<th width="10%">Cabang</th>
			<th width="10%">Input Time</th>
			<th width="5%">User</th>
		</tr>';

if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 50;	}	else	{	$m = 0;		}
	$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
//	$mamet = $database->doQuery('SELECT * FROM fu_ajk_dn WHERE fu_ajk_dn.id!="" '.$satu.' '.$dua.' AND fu_ajk_dn.del IS NULL ORDER BY id_cost ASC, fu_ajk_dn.input_time, fu_ajk_dn.tgl_createdn ASC, fu_ajk_dn.dn_kode ASC LIMIT ' . $m . ' , 50');
	$mamet = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id !="" '.$satu.' '.$dua.' AND del IS NULL ORDER BY id_cost ASC, input_time, id_dn ASC LIMIT ' . $m . ' , 50');
	$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(fu_ajk_peserta.id) FROM fu_ajk_peserta WHERE id != "" '.$satu.' '.$dua.' AND del IS NULL '));
	$totalRows = $totalRows[0];
while ($met = mysql_fetch_array($mamet)) {
	$user = explode("- ", $met['input_by']);	$usernya = $user[0];
	$metdn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn WHERE dn_kode = "'.$met['id_dn'].'" '));
	$cost = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$met['id_cost'].'"'));
if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
if ($met['status_peserta']=="") {	$type="NB";	}else{	$type=$met['status_peserta'];	}
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
				<td align="center">'.(++$no + ($pageNow-1) * 50).'</td>
				<td align="center">'.$cost['name'].'</td>
				<td>'.$met['nama'].'</td>
				<td align="center">'.$met['id_dn'].'</td>
				<td align="center">'._convertDate($metdn['tgl_createdn']).'</td>
				<td align="right">'.duit($met['totalpremi']).'</td>
				<td align="center">'.$met['status_aktif'].'</td>
				<td align="center">'.$type.'</td>
				<td align="center">'.$met['regional'].'</td>
				<td align="center">'.$met['area'].'</td>
				<td align="center">'.$met['cabang'].'</td>
				<td align="center">'.$met['input_time'].'</td>
				<td align="center">'.$usernya.'</td>
			  </tr>';
	$subtotal += $met['totalpremi'];
}
echo '<tr bgcolor="white"><td colspan="5" align="right"><b>Sub Total :</b></td>
							  <td align="right"><b>'.duit($subtotal).'</b></td>
							  <td colspan="7">&nbsp;</td>
		  </tr>';
	echo '<tr><td colspan="13">';
	echo createPageNavigations($file = 'coj.php?r=cojdn&met=view&metuser='.$_REQUEST['metuser'].'&tanggal1='.$_REQUEST['tanggal1'].'&tanggal2='.$_REQUEST['tanggal2'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 50);
	echo '<b>Total Data DN: <u>' . $totalRows . '</u></b></td></tr>';
	echo '</table>';
}
}
		;
		break;
	default:

		;
} // switch


?>