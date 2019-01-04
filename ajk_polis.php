<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("includes/functions.php");
connect();
if (session_is_registered('nm_user')) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));	}
switch ($_REQUEST['r']) {
	case "a":
		;
		break;
	case "pview":
$cost = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$q['id_cost'].'"'));
if ($cost['city']=="" AND $cost['postcode']) {	$costpost = '';	}
elseif ($cost['postcode']=="")				 {	$costpost = $cost['city']; }

$cpolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id_cost="'.$q['id_cost'].'"'));
$metmspolisbenefit = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_master WHERE fu_ajk_master.mscode ="'.$cpolis['benefit_type'].'"'));
$metmspolisclaim = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_master WHERE fu_ajk_master.mscode ="'.$cpolis['claimrule'].'"'));
echo '<table border="0" cellpadding="3" cellspacing="1" width="100%">
	  <tr><th width="95%" align="left">Modul Polis</th><th align="center">'.back().'</a></th></tr>
   	  </table><br />';
echo '<table border="0" width="100%" cellpadding="3" cellspacing="1">
	  <tr><td width="20%">Nama Perusahaan</td><td width="1%">:</td><td><b>'.$cost['name'].'</b></td></tr>
	  <tr><td valign="top">Alamat</td><td width="1%" valign="top">:</td><td>'.htmlentities($cost['address']).'<br />'.$costpost.'</td></tr>
	  <tr><td coslpan="3"><b>Data Polis</b></td></tr>
	  <tr><td>Nomor Polis</td><td>:</td><td><b>'.$cpolis['nopol'].'</b></td></tr>
	  <tr><td>Tanggal Efektif</td><td>:</td><td>'._convertDate($cpolis['start_date']).'</td></tr>
	  <tr><td>Admin Fee</td><td>:</td><td>'.duit($cpolis['adm_fee']).'</td></tr>
	  <tr><td>Discount</td><td>:</td><td>'.duit($cpolis['discount']).'</td></tr>
	  <tr><td>Benefit</td><td>:</td><td>'.$metmspolisbenefit['msname'].' ('.$metmspolisbenefit['msdesc'].')</td></tr>
	  <tr><td>Claim Rule</td><td>:</td><td>'.$metmspolisclaim['msname'].'</td></tr>
	  <tr><td>Max Age</td><td>:</td><td>'.$cpolis['age_min'].' s/d '.$cpolis['age_max'].'</td></tr>
	  <tr><td>Limit Financial</td><td>:</td><td>'.duit($cpolis['limit_fs']).'</td></tr>
	  <tr><td>Maximum Sum Insured</td><td>:</td><td>'.duit($cpolis['limit_sa']).'</td></tr>
	  <tr><td coslpan="3"><b>Data Bank</b></td></tr>
	  <tr><td>Nama Bank</td><td>:</td><td>'.$cpolis['bank_name'].'</td></tr>
	  <tr><td>Cabang</td><td>:</td><td>'.$cpolis['bank_branch'].'</td></tr>
	  <tr><td>Nomor Rekening</td><td>:</td><td>'.$cpolis['bank_accNo'].'</td></tr>
	  </table>';

		;
		break;
	default:
echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
	  <tr><th width="95%" align="left">Modul Polis</th></tr>
   	  </table><br />';
echo '<table border="0" width="100%" cellpadding="5" cellspacing="1" bgcolor="#bde0e6">
	<tr>
	<th width="3%">No</th>
	<th width="15%">Polis</th>
	<th>Costumer</th>
	<th width="5%">Start Date</th>
	<th width="8%">Admin Fee</th>
	<th width="5%">Disc</th>
	<th width="8%">Type Benefit</th>
	<th width="10%">Limit Financial</th>
	<th width="5%">Max Age</th>
	<th width="10%">Max Insured</th>
	</tr>';

$rpolis = $database->doQuery('SELECT * FROM fu_ajk_polis WHERE id_cost="'.$q['id_cost'].'" AND id="'.$q['id_polis'].'"');
while ($met = mysql_fetch_array($rpolis)) {
	$cekcost = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id= "'.$met['id_cost'].'"'));
if (($no % 2) == 1)	$objlass = 'tbl-odd'; else				$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		<td align="center">'.++$no.'</td>
		<td align="center"><a href="ajk_polis.php?r=pview&id='.$met['id'].'">'.$met['nopol'].'</a></td>
		<td>'.$cekcost['name'].'</td>
		<td align="center">'._convertDate($met['polis_start']).'</td>
		<td align="right">'.duit($met['adminfee']).'</td>
		<td align="right">'.$met['discount'].'%</td>
		<td align="center">'.$met['benefit'].'</td>
		<td align="right">'.duit($met['limitfinancial']).'</td>
		<td align="center">'.$met['age_max'].'</td>
		<td align="right">'.duit($met['up_max']).'</td>
		</tr>';
}
		echo '</table>';
		;
} // switch
?>