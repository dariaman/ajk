<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("../includes/functions.php");

if (session_is_registered('nm_user')) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));	}
$futgl = date("Y-m-d g:i:a");

connect();
switch ($_REQUEST['arm']) {
	case "dnpayment":
$cat=$_GET['cat']; // Use this line or below line if register_global is off
if(strlen($cat) > 0 and !is_numeric($cat)){ // to check if $cat is numeric data or not.
	echo "Data Error";
	exit;
}
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%"><tr><th width="90%" align="left">Modul Report ARM Payment</font></th></th></tr></table>';
echo '<fieldset style="padding: 2">
	<legend align="center">S e a r c h</legend>
	<table border="0" width="100%" cellpadding="1" cellspacing="1">
		<form method="post" action="">
	  <tr><td width="20%" align="right">Company</td>
	  <td width="30%">: <select id="cat" name="cat" onchange="reloadpayment(this.form)">
	  	<option value="">---Select Company---</option>';
$quer2=$database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name ASC');
while($noticia2 = mysql_fetch_array($quer2)) {
if($noticia2['id']==$cat){echo '<option selected value="'.$noticia2['id'].'">'.$noticia2['name'].'</option><BR>';}
else{echo  '<option value="'.$noticia2['id'].'">'.$noticia2['name'].'</option>';}
}
echo '</select></td></tr>
			<tr><td align="right">Regional</td><td>: ';
if(isset($cat) and strlen($cat) > 0){
	$quer=$database->doQuery('SELECT * FROM fu_ajk_regional where id_cost="'.$cat.'" ORDER BY id ASC');
}else{$quer=$database->doQuery("SELECT * FROM fu_ajk_regional ORDER BY id ASC"); }
		echo '<select name="subcat"><option value="">---Select Regional---</option>';
while($noticia = mysql_fetch_array($quer)) {
	echo  '<option value='.$noticia['id'].'>'.$noticia['name'].'</option>';
}
echo '<tr><td align="right" title="Data DN yang telah di bayar berdasarkan tanggal pembayaran">Date Payment</td><td>:
		<input type="text" name="tanggal1" id="tanggal1" class="tanggal" value="'.$_REQUEST['tanggal1'].'" size="10"/> s/d
		<input type="text" name="tanggal2" id="tanggal2" class="tanggal" value="'.$_REQUEST['tanggal2'].'" size="10"/>';
echo '</td></tr>
	<tr><td width="5%" align="center" colspan="3"><input type="submit" name="button" value="Search" class="button"></td></tr>
	</form></table></fieldset>';
if ($_REQUEST['button']=="Search") {
	if (!$_REQUEST['cat']) 						{	$errorARM .= 'Silahkan pilih nama perusahaan...!!<br />';	}
	//	if (!$_REQUEST['tgl'] OR !$_REQUEST['tgl2'])	{	$errorARM .= 'Tanggal tidak boleh kosong...!!';	}
	if ($errorARM){	echo '<center><div class="kolomerrorARM"><blink>'.$errorARM.'<blink></div></center>';	}
	else{
		$cekcostumer = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$_REQUEST['cat'].'"'));
		$cekregional = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['regional'].'"'));
if ($_REQUEST['regional']=="") {	$regnya = "All Regional";	}else{ $regnya = $cekregional['name'];	}

if ($_REQUEST['cat'])			{	$satu = 'AND id_cost = "'.$_REQUEST['cat'].'"';	}
if ($_REQUEST['regional'])		{
	$cekregional = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['regional'].'"'));
	$dua = 'AND id_regional = "'.$cekregional['name'].'"';
}

$tgl1 = explode("/", $_REQUEST['tanggal1']);	$tglawal = $tgl1[2].'-'.$tgl1[1].'-'.$tgl1[0];
$tgl2 = explode("/", $_REQUEST['tanggal2']);	$tglakhir = $tgl2[2].'-'.$tgl2[1].'-'.$tgl2[0];
if ($_REQUEST['tanggal1']!='' AND $_REQUEST['tanggal2']!='')	{	$tiga='AND tgl_dn_paid BETWEEN \''.$tglawal.'\' AND \''.$tglakhir.'\'';	}


$tgl3 = explode("/", $_REQUEST['tanggal3']);	$tglawal1 = $tgl3[2].'-'.$tgl3[1].'-'.$tgl3[0];
$tgl4 = explode("/", $_REQUEST['tanggal4']);	$tglakhir1 = $tgl4[2].'-'.$tgl4[1].'-'.$tgl4[0];
if ($_REQUEST['tanggal3']!='' AND $_REQUEST['tanggal4']!='')	{	$empat='AND DATE_FORMAT(update_time,"%Y-%m-%d") BETWEEN "'.$tglawal1.'" AND "'.$tglakhir1.'"';	}

$met1 = $database->doQuery('SELECT *, SUM(totalpremi) AS premipaid FROM fu_ajk_dn WHERE id !="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' AND dn_status="paid" AND del IS NULL GROUP BY id');
$tpaiddn = mysql_num_rows($met1);
while ($tpaid = mysql_fetch_array($met1)){
	$e += $tpaid['totalpremi'];
}

echo '<table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#C6DEF7">
			  <tr><td align="left" width="10%"><b>Perusahaan</b></td>
			  	  <td align="left" colspan="8"><b>: '.$cekcostumer['name'].'</b></td>
				  <td align="right" rowspan="2" colspan="5"><a href="ajk_armreportPrint.php?cat='.$_REQUEST['cat'].'&regional='.$_REQUEST['regional'].'&tgl1='.$tglawal.'&tgl2='.$tglakhir.'&armreport=datepayment"><img src="image/excel.png" width="32"></a></td>
			  </tr>
		 	  <tr><td align="left" width="10%"><b>Regional</b></td>
		 	  	  <td align="left" colspan="2"><b>: '.$regnya.'</b></td></tr>
		 	  <tr><td align="left"><b>Paid</b></td>
		 	  	  <td width="10%"><b>: '.duit($tpaiddn).' DN</td><td><b>Rp. '.duit($e).'</b></td></tr>
			  </table>';
echo '<table border="0" width="100%" cellpadding="3" cellspacing="1" bgcolor="#bde0e6">
		<tr><th width="1%">No</th>
			<th width="10%">Policy</th>
			<th width="10%">DN Number</th>
			<th width="5%">Date Payment</th>
			<th width="5%">Status</th>
			<th width="5%">Date Process</th>
			<th width="1%">Jumlah Peserta</th>
			<th width="5%">Premium</th>
			<th width="10%">CN Number</th>
			<th width="5%">Nilai CN</th>
			<th width="1%">Status</th>
			<th width="5%">Nett Premi</th>
			<th>Regional</th>
			<th>Area</th>
			<th>Cabang</th>
			<th>time</th>
			<th>User</th>
		</tr>';
		//$metregional = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['regional'].'"'));
if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 50;	}	else	{	$m = 0;		}
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;

$met = $database->doQuery('SELECT * FROM fu_ajk_dn WHERE id !="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' AND del IS NULL ORDER BY update_time, tgl_dn_paid ASC, dn_kode ASC LIMIT ' . $m . ' , 50');
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_dn WHERE id != "" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' AND del IS NULL '));
$totalRows = $totalRows[0];

while ($mamet = mysql_fetch_array($met)) {
$metpolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$mamet['id_nopol'].'"'));
$metcostumer = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$mamet['id_cost'].'"'));

$metdn = mysql_num_rows($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_dn = "'.$mamet['dn_kode'].'" '));

//cek wilayah//
if ($mamet['id_cabang']=="") {	$metcabangnya = $mamet['id_cabang_old'];	}else{	$metcabangnya = $mamet['id_cabang'];	}
if ($mamet['id_area']=="") {	$metareanya = $mamet['id_area_old'];	}else{	$metareanya = $mamet['id_area'];	}
if ($mamet['id_regional']=="") {	$metregionalnya = $mamet['id_regional_old'];	}else{	$metregionalnya = $mamet['id_regional'];	}
//cek wilayah//

	// TAMPILKAN CN BILA ADA//
$dncnnya = mysql_fetch_array($database->doQuery('SELECT
fu_ajk_cn.id_cn,
fu_ajk_cn.id_dn,
fu_ajk_cn.tgl_createcn,
SUM(fu_ajk_cn.total_claim) AS tclaim,
fu_ajk_cn.type_claim
FROM
fu_ajk_cn
WHERE
fu_ajk_cn.id_dn = "'.$mamet['dn_kode'].'" AND id_cost="'.$mamet['id_cost'].'"
GROUP BY
fu_ajk_cn.id_dn'));
if ($dncnnya['id_dn']==$mamet['dn_kode']) {
	$cnnomor = $dncnnya['id_cn'];
	$cnpremi = duit($dncnnya['tclaim']);
	$statuscn = '<font color="red">'.$dncnnya['type_claim'].'</font>';
}else{
	$cnnomor = '-';
	$cnpremi = '-';
	$statuscn = 'Inforce';
}
	// TAMPILKAN CN BILA ADA//
	$netpremi = $mamet['totalpremi'] - $dncnnya['tclaim'];
if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
			<td align="center">'.(++$no + ($pageNow-1) * 50).'</td>
			<td align="center">'.$metpolis['nopol'].'</td>
			<td align="center">'.$mamet['dn_kode'].'</td>
			<td align="center">'._convertDate($mamet['tgl_dn_paid']).'</td>
			<td align="center">'.strtoupper($mamet['dn_status']).'</td>
			<td align="center">'._convertDate($mamet['tgl_createdn']).'</td>
			<td align="center">'.$metdn.'</td>
			<td align="right">'.duit($mamet['totalpremi']).'</td>
			<td align="center">'.$cnnomor.'</td>
			<td align="center">'.$cnpremi.'</td>
			<td align="center">'.$statuscn.'</td>
			<td align="right"><b>'.duit($netpremi).'</b></td>
			<td>'.$metregionalnya.'</tdh>
			<td>'.$metareanya.'</tdh>
			<td>'.$metcabangnya.'</tdh>
			<td align="center">'.$mamet['update_time'].'</td>
			<td align="center">'.$mamet['update_by'].'</td>
		  </tr>';
	$jumpeserta += $metdn;
	$jumdnnya += $mamet['totalpremi'];
}
		echo '<tr bgcolor="white"><td colspan="6" align="right"><b>Sub Total :</b></td><td align="center"><b>'.duit($jumpeserta).'</b></td><td align="right"><b>'.duit($jumdnnya).'</b></td><td colspan="5">&nbsp;</td></tr>';
		echo '<tr><td colspan="11">';
		echo createPageNavigations($file = 'ajk_ARMreport.php?arm=dnpayment&button=Search&cat='.$_REQUEST['cat'].'&regional='.$_REQUEST['regional'].'&tanggal1='.$_REQUEST['tanggal1'].'&tanggal2='.$_REQUEST['tanggal2'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 50);
		echo '<b>Total Data DN: <u>' . $totalRows . '</u></b></td></tr>';
	}
	echo '</table>';
}
		;
		break;
	case "dndateprocess":
		$cat=$_GET['cat']; // Use this line or below line if register_global is off
if(strlen($cat) > 0 and !is_numeric($cat)){ // to check if $cat is numeric data or not.
	echo "Data Error";
	exit;
}
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
		<tr><th width="90%" align="left">Modul Report ARM Payment</font></th></th></tr>
		</table>';
echo '<fieldset style="padding: 2">
			<legend align="center">S e a r c h</legend>
			<table border="0" width="100%" cellpadding="1" cellspacing="1">
				<form method="post" action="">
		<tr><td width="20%" align="right">Company</td>
			  <td width="30%">: <select id="cat" name="cat" onchange="reloaddatepayment(this.form)">
			  	<option value="">---Select Company---</option>';
		$quer2=$database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name ASC');
while($noticia2 = mysql_fetch_array($quer2)) {
if($noticia2['id']==$cat){echo '<option selected value="'.$noticia2['id'].'">'.$noticia2['name'].'</option><BR>';}
else{echo  '<option value="'.$noticia2['id'].'">'.$noticia2['name'].'</option>';}
}
echo '</select></td></tr>
					<tr><td align="right">Regional</td><td>: ';
if(isset($cat) and strlen($cat) > 0){
	$quer=$database->doQuery('SELECT * FROM fu_ajk_regional where id_cost="'.$cat.'" ORDER BY id ASC');
}else{$quer=$database->doQuery("SELECT * FROM fu_ajk_regional ORDER BY id ASC"); }
		echo '<select name="subcat"><option value="">---Select Regional---</option>';
while($noticia = mysql_fetch_array($quer)) {
	echo  '<option value='.$noticia['id'].'>'.$noticia['name'].'</option>';
}
echo '</td></tr>
			  <tr><td align="right" title="Data DN yang telah di proses oleh user">Date Process</td><td>:
				<input type="text" name="tanggal3" id="tanggal3" class="tanggal" value="'.$_REQUEST['tanggal3'].'" size="10"/> s/d
				<input type="text" name="tanggal4" id="tanggal4" class="tanggal" value="'.$_REQUEST['tanggal4'].'" size="10"/>';
echo '</td></tr>
			<tr><td width="5%" align="center" colspan="3"><input type="submit" name="button" value="Search" class="button"></td></tr>
			</form></table></fieldset>';
if ($_REQUEST['button']=="Search") {
	if (!$_REQUEST['cat']) 						{	$errorARM .= 'Silahkan pilih nama perusahaan...!!<br />';	}
	//	if (!$_REQUEST['tgl'] OR !$_REQUEST['tgl2'])	{	$errorARM .= 'Tanggal tidak boleh kosong...!!';	}
	if ($errorARM){	echo '<center><div class="kolomerrorARM"><blink>'.$errorARM.'<blink></div></center>';	}
	else{
		$cekcostumer = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$_REQUEST['cat'].'"'));
		$cekregional = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['regional'].'"'));
if ($_REQUEST['regional']=="") {	$regnya = "All Regional";	}else{ $regnya = $cekregional['name'];	}

if ($_REQUEST['cat'])			{	$satu = 'AND id_cost = "'.$_REQUEST['cat'].'"';	}
if ($_REQUEST['regional'])		{
	$cekregional = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['regional'].'"'));
	$dua = 'AND id_regional = "'.$cekregional['name'].'"';
}

$tgl3 = explode("/", $_REQUEST['tanggal3']);	$tglawal1 = $tgl3[2].'-'.$tgl3[1].'-'.$tgl3[0];
$tgl4 = explode("/", $_REQUEST['tanggal4']);	$tglakhir1 = $tgl4[2].'-'.$tgl4[1].'-'.$tgl4[0];
if ($_REQUEST['tanggal3']!='' AND $_REQUEST['tanggal4']!='')	{	$empat='AND DATE_FORMAT(update_time,"%Y-%m-%d") BETWEEN "'.$tglawal1.'" AND "'.$tglakhir1.'"';	}

		$met1 = $database->doQuery('SELECT *, SUM(totalpremi) AS premipaid FROM fu_ajk_dn WHERE id !="" '.$satu.' '.$dua.' '.$empat.' AND dn_status="paid" AND del IS NULL GROUP BY id');
		$tpaiddn = mysql_num_rows($met1);
while ($tpaid = mysql_fetch_array($met1)){
	$e += $tpaid['totalpremi'];
}

echo '<table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#C6DEF7">
			  <tr><td align="left" width="10%"><b>Perusahaan</b></td>
			  	  <td align="left" colspan="8"><b>: '.$cekcostumer['name'].'</b></td>
				  <td align="right" rowspan="2" colspan="5"><a href="ajk_armreportPrint.php?cat='.$_REQUEST['cat'].'&regional='.$_REQUEST['regional'].'&tgl3='.$tglawal1.'&tgl4='.$tglakhir1.'&armreport=dateprocess"><img src="image/excel.png" width="32"></a></td>
			  </tr>
		 	  <tr><td align="left" width="10%"><b>Regional</b></td>
		 	  	  <td align="left" colspan="2"><b>: '.$regnya.'</b></td></tr>
		 	  <tr><td align="left"><b>Paid</b></td>
		 	  	  <td width="10%"><b>: '.duit($tpaiddn).' DN</td><td><b>Rp. '.duit($e).'</b></td></tr>
			  </table>';
echo '<table border="0" width="100%" cellpadding="3" cellspacing="1" bgcolor="#bde0e6">
		<tr><th width="1%">No</th>
			<th width="10%">Policy</th>
			<th>DN Number</th>
			<th width="8%">Date Payment</th>
			<th width="5%">Status</th>
			<th width="8%">Date Process</th>
			<th width="5%">Jumlah Peserta</th>
			<th width="8%">Premium</th>
			<th>Regional</th>
			<th>Area</th>
			<th>Cabang</th>
			<th>time</th>
			<th>User</th>
		</tr>';
		//$metregional = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['regional'].'"'));
if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 50;	}	else	{	$m = 0;		}
		$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;

		$met = $database->doQuery('SELECT * FROM fu_ajk_dn WHERE id !="" '.$satu.' '.$dua.' '.$empat.' AND dn_status="paid" AND del IS NULL ORDER BY update_time, tgl_dn_paid ASC, dn_kode ASC LIMIT ' . $m . ' , 50');
		$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_dn WHERE id != "" '.$satu.' '.$dua.' '.$empat.' AND dn_status="paid" AND del IS NULL '));
		$totalRows = $totalRows[0];

while ($mamet = mysql_fetch_array($met)) {
	$metpolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$mamet['id_nopol'].'"'));
	$metcostumer = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$mamet['id_cost'].'"'));

	$metdn = mysql_num_rows($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_dn = "'.$mamet['dn_kode'].'" '));

	//cek wilayah//
if ($mamet['id_cabang']=="") {	$metcabangnya = $mamet['id_cabang_old'];	}else{	$metcabangnya = $mamet['id_cabang'];	}
if ($mamet['id_area']=="") {	$metareanya = $mamet['id_area_old'];	}else{	$metareanya = $mamet['id_area'];	}
if ($mamet['id_regional']=="") {	$metregionalnya = $mamet['id_regional_old'];	}else{	$metregionalnya = $mamet['id_regional'];	}
	//cek wilayah//
if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
				<td align="center">'.(++$no + ($pageNow-1) * 50).'</td>
				<td align="center">'.$metpolis['nopol'].'</td>
				<td align="center">'.$mamet['dn_kode'].'</td>
				<td align="center">'._convertDate($mamet['tgl_dn_paid']).'</td>
				<td align="center">'.strtoupper($mamet['dn_status']).'</td>
				<td align="center">'._convertDate($mamet['tgl_createdn']).'</td>
				<td align="center">'.$metdn.'</td>
				<td align="right">'.duit($mamet['totalpremi']).'</td>
				<td>'.$metregionalnya.'</tdh>
				<td>'.$metareanya.'</tdh>
				<td>'.$metcabangnya.'</tdh>
				<td align="center">'.$mamet['update_time'].'</td>
				<td align="center">'.$mamet['update_by'].'</td>
			  </tr>';
	$jumpeserta += $metdn;
	$jumdnnya += $mamet['totalpremi'];
}
		echo '<tr bgcolor="white"><td colspan="6" align="right"><b>Sub Total :</b></td><td align="center"><b>'.duit($jumpeserta).'</b></td><td align="right"><b>'.duit($jumdnnya).'</b></td><td colspan="5">&nbsp;</td></tr>';
		echo '<tr><td colspan="11">';
		echo createPageNavigations($file = 'ajk_ARMreport.php?arm=dndateprocess&button=Search&cat='.$_REQUEST['cat'].'&regional='.$_REQUEST['regional'].'&tanggal3='.$_REQUEST['tanggal3'].'&tanggal4='.$_REQUEST['tanggal4'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 50);
		echo '<b>Total Data DN: <u>' . $totalRows . '</u></b></td></tr>';
	}
	echo '</table>';
}
	;
	break;
	case "dnprocess":
$cat=$_GET['cat']; // Use this line or below line if register_global is off
if(strlen($cat) > 0 and !is_numeric($cat)){ // to check if $cat is numeric data or not.
echo "Data Error";
exit;
}
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
<tr><th width="90%" align="left">Modul Report DN process</font></th></th></tr>
</table>';
echo '<fieldset style="padding: 2">
	<legend align="center">S e a r c h</legend>
	<table border="0" width="100%" cellpadding="1" cellspacing="1">
		<form method="post" action="">
<tr><td width="20%" align="right">Company</td>
	  <td width="30%">: <select id="cat" name="cat" onchange="reload(this.form)">
	  	<option value="">---Select Company---</option>';
$quer2=$database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name ASC');
while($noticia2 = mysql_fetch_array($quer2)) {
if($noticia2['id']==$cat){echo '<option selected value="'.$noticia2['id'].'">'.$noticia2['name'].'</option><BR>';}
else{echo  '<option value="'.$noticia2['id'].'">'.$noticia2['name'].'</option>';}
}
echo '</select></td></tr>
	<tr><td align="right">Regional</td><td>: ';
if(isset($cat) and strlen($cat) > 0){
$quer=$database->doQuery('SELECT * FROM fu_ajk_regional where id_cost="'.$cat.'" ORDER BY name ASC');
}else{$quer=$database->doQuery("SELECT * FROM fu_ajk_regional ORDER BY id ASC"); }
echo '<select name="subcat"><option value="">---Select Regional---</option>';
while($noticia = mysql_fetch_array($quer)) {
echo  '<option value='.$noticia['id'].'>'.$noticia['name'].'</option>';
}
echo '<tr><td align="right">Date DN </td><td>:
			<input type="text" name="tanggal1" id="tanggal1" class="tanggal" value="'.$_REQUEST['tanggal1'].'" size="10"/> s/d
			<input type="text" name="tanggal2" id="tanggal2" class="tanggal" value="'.$_REQUEST['tanggal2'].'" size="10"/>';
echo '</td></tr>
	  <tr><td align="right" width="3%">Status</td>
 		 <td width="10%">: <input type="radio"'.pilih($_REQUEST['armstatus'], "paid").' name="armstatus" value="paid">Paid &nbsp;
 		 				   <input type="radio"'.pilih($_REQUEST['armstatus'], "unpaid").' name="armstatus" value="unpaid">Unpaid &nbsp;
 		 				   <input type="radio"'.pilih($_REQUEST['armstatus'], "paid").' name="armstatus" value="">All</td></tr>
		<tr><td width="5%" align="center" colspan="3"><input type="submit" name="button" value="Search" class="button"></td></tr>
		</form></table></fieldset>';
if ($_REQUEST['button']=="Search") {
	if ($_REQUEST['cat']=="") {
		echo '<div align="center"><font color="red"><blink>Silahkan pilih nama Costumer...!!</div></font></blink>';
	}elseif ($_REQUEST['tanggal1']=="" OR $_REQUEST['tanggal2']==""){
		echo '<div align="center"><font color="red"><blink>Tanggal tidak boleh kosong...!!</div></font></blink>';
	}else{
		$tgl1 = explode("/", $_REQUEST['tanggal1']);	$tglawal = $tgl1[2].'-'.$tgl1[1].'-'.$tgl1[0];
		$tgl2 = explode("/", $_REQUEST['tanggal2']);	$tglakhir = $tgl2[2].'-'.$tgl2[1].'-'.$tgl2[0];
		if ($_REQUEST['cat'])			{	$satu = 'AND id_cost ="' . $cat . '"';			}
		if ($_REQUEST['subcat'])		{
			$cekregionalnya = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['subcat'].'"'));
			$dua = 'AND id_regional ="' . $cekregionalnya['name'] . '"';			}
		if ($_REQUEST['tanggal1'])		{	$tiga = 'AND tgl_createdn BETWEEN "'.$tglawal.'" AND "'.$tglakhir.'" ';	}
		if ($_REQUEST['armstatus'])		{	$empat = 'AND dn_status = "'.$_REQUEST['armstatus'].'"';	}

		$cekcostumer = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$_REQUEST['cat'].'"'));
		$cekregional = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['subcat'].'"'));
if ($_REQUEST['subcat']=="") {	$regnya = "All Regional";	}else{ $regnya = $cekregional['name'];	}

		$met1 = $database->doQuery('SELECT *, SUM(totalpremi) AS premipaid FROM fu_ajk_dn WHERE id !="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' AND dn_status="paid" AND del IS NULL GROUP BY id');
		$tpaiddn = mysql_num_rows($met1);
while ($tpaid = mysql_fetch_array($met1)){	$e += $tpaid['totalpremi'];	};

		$met2 = $database->doQuery('SELECT *, SUM(totalpremi) AS premiunpaid FROM fu_ajk_dn WHERE id !="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' AND dn_status="unpaid" AND del IS NULL GROUP BY id');
		$tunpaiddn = mysql_num_rows($met2);
while ($tunpaid = mysql_fetch_array($met2)){	$er += $tunpaid['totalpremi'];	};
echo '<table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#C6DEF7">
			  <tr><td align="left" width="10%"><b>Perusahaan</b></td>
			  	  <td align="left" colspan="8"><b>: '.$cekcostumer['name'].'</b></td>
				  <td align="right" rowspan="2" colspan="5"><a href="ajk_armreportPrint.php?cat='.$_REQUEST['cat'].'&subcat='.$_REQUEST['subcat'].'&tgl='.$tglawal.'&tgl2='.$tglakhir.'&armstatus='.$_REQUEST['armstatus'].'"><img src="image/excel.png" width="32"></a></td>
			  </tr>
		 	  <tr><td align="left" width="10%"><b>Regional</b></td>
		 	  	  <td align="left" colspan="2"><b>: '.$regnya.'</b></td></tr>
		 	  <tr><td align="left"><b>Paid</b></td>
		 	  	  <td width="10%"><b>: '.$tpaiddn.' DN</td><td><b>Rp. '.duit($e).'</b></td></tr>
		 	  <tr><td align="left"><b>Unpaid</b></td>
		 	  	  <td><b>: '.$tunpaiddn.' DN</td><td><b>Rp.  '.duit($er).'</b></td></tr>
		 	  <tr><td align="left"><b>Grand Total</b></td>
		 	  	  <td><b>: '.duit($tpaiddn + $tunpaiddn).' DN</td><td><b>Rp. '.duit($er + $e).'</b></td></tr>
			  </table>';
echo '<table border="0" width="100%" cellpadding="3" cellspacing="1" bgcolor="#bde0e6">
				<tr><th width="1%">No</th>
					<th width="10%">Policy</th>
					<th>DN Number</th>
					<th width="8%">Date Process</th>
					<th width="5%">Status</th>
					<th width="8%">Date Payment</th>
					<th width="5%">Jumlah Peserta</th>
					<th width="8%">Premi</th>
					<th width="10%">Credit Note</th>
					<th width="5%">Nilai CN</th>
					<th width="5%">Nett Premi</th>
					<th>Area</th>
					<th>Cabang</th>
				</tr>';
		//$metregional = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['regional'].'"'));
if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 50;	}	else	{	$m = 0;		}
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;

$met = $database->doQuery('SELECT * FROM fu_ajk_dn WHERE id !="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' AND del IS NULL ORDER BY dn_status ASC, dn_kode ASC, id_cabang DESC LIMIT ' . $m . ' , 50');
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_dn WHERE id != "" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' AND del IS NULL '));
$totalRows = $totalRows[0];

while ($mamet = mysql_fetch_array($met)) {
$metpolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$mamet['id_nopol'].'"'));
$metcostumer = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$mamet['id_cost'].'"'));
$metdn = mysql_num_rows($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_dn = "'.$mamet['dn_kode'].'" '));
if ($mamet['dn_status']=="unpaid") {	$statusnya = '<blink><font color="red">Unpaid</font></blink>';	}else{	$statusnya = '<font color="blue">Paid</font>';	}

	//DATA CN PENDAMPING DN//
$dncnnya = mysql_fetch_array($database->doQuery('SELECT
fu_ajk_cn.id_cn,
fu_ajk_cn.id_dn,
fu_ajk_cn.tgl_createcn,
SUM(fu_ajk_cn.total_claim) AS tclaim,
fu_ajk_cn.type_claim
FROM
fu_ajk_cn
WHERE
fu_ajk_cn.id_dn = "'.$mamet['dn_kode'].'"
GROUP BY
fu_ajk_cn.id_cn'));
if ($dncnnya['id_dn']==$mamet['dn_kode']) {
	$cnnomor = $dncnnya['id_cn'];
	$cnpremi = duit($dncnnya['tclaim']);
	$statuscn = '<font color="red">'.$dncnnya['type_claim'].'</font>';
}else{
	$cnnomor = '-';
	$cnpremi = '-';
	$statuscn = 'Inforce';
}
	$netpremi = $mamet['totalpremi'] - $dncnnya['tclaim'];
	//DATA CN PENDAMPING DN//

if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
			<td align="center">'.(++$no + ($pageNow-1) * 50).'</td>
			<td align="center">'.$metpolis['nopol'].'</td>
			<td align="center">'.$mamet['dn_kode'].'</td>
			<td align="center">'._convertDate($mamet['tgl_createdn']).'</td>
			<td align="center">'.$statusnya.'</td>
			<td align="center">'._convertDate($mamet['tgl_dn_paid']).'</td>
			<td align="center">'.$metdn.'</td>
			<td align="right">'.duit($mamet['totalpremi']).'</td>
			<td align="center">'.$cnnomor.'</td>
			<td align="right">'.$cnpremi.'</td>
			<td align="right">'.duit($netpremi).'</td>
			<td>'.$mamet['id_area'].'</tdh>
			<td>'.$mamet['id_cabang'].'</tdh>
		  </tr>';
	$jumpeserta += $metdn;
	$jumdnnya += $mamet['totalpremi'];
	$jumdcnya += $dncnnya['tclaim'];
	$jumnettnya += $netpremi;
}
		echo '<tr bgcolor="white"><td colspan="6" align="right"><b>Total Per Halaman :</b></td><td align="center"><b>'.duit($jumpeserta).'</b></td>
								  <td align="right"><b>'.duit($jumdnnya).'</b></td><td>&nbsp;</td>
								  <td align="right"><b>'.duit($jumdcnya).'</b></td>
								  <td align="right"><b>'.duit($jumnettnya).'</b></td>
								  </td><td>&nbsp;</td></td><td>&nbsp;</td></tr>';
		echo '<tr><td colspan="13">';
		echo createPageNavigations($file = 'ajk_ARMreport.php?arm=dnprocess&button=Search&cat='.$_REQUEST['cat'].'&subcat='.$_REQUEST['subcat'].'&tanggal1='.$_REQUEST['tanggal1'].'&tanggal2='.$_REQUEST['tanggal2'].'&armstatus='.$_REQUEST['armstatus'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 50);
		echo '<b>Total Data DN: <u>' . $totalRows . '</u></b></td></tr>';
	}
	echo '</table>';
}		;
		break;
	case "updatednpayment":
$cat=$_GET['cat']; // Use this line or below line if register_global is off
if(strlen($cat) > 0 and !is_numeric($cat)){ // to check if $cat is numeric data or not.
	echo "Data Error";	exit;
}
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%"><tr><th width="90%" align="left">Modul Report Summery DN Payment</th></tr></table>';
echo '<fieldset style="padding: 2">
	<legend align="center">Pencarian Tanggal Pembayaran Debit Note</legend>
	<table border="0" width="100%" cellpadding="1" cellspacing="1">
	<form method="post" action="">
<tr><td width="20%" align="right">Company</td>
	  <td>: <select id="cat" name="cat" onchange="reloadupdatednpayment(this.form)">
	  	<option value="">---Select Company---</option>';
	$quer2=$database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name ASC');
while($noticia2 = mysql_fetch_array($quer2)) {
if($noticia2['id']==$cat){echo '<option selected value="'.$noticia2['id'].'">'.$noticia2['name'].'</option><BR>';}
else{echo  '<option value="'.$noticia2['id'].'">'.$noticia2['name'].'</option>';}
}
echo '</select></td></tr>
				<tr><td align="right">Regional</td><td>: ';
if(isset($cat) and strlen($cat) > 0){
	$quer=$database->doQuery('SELECT * FROM fu_ajk_regional where id_cost="'.$cat.'" ORDER BY id ASC');
}else{$quer=$database->doQuery("SELECT * FROM fu_ajk_regional ORDER BY id ASC"); }
	echo '<select name="subcat"><option value="">---Select Regional---</option>';
while($noticia = mysql_fetch_array($quer)) {
	echo  '<option value="'.$noticia['name'].'">'.$noticia['name'].'</option>';
}
echo '</td></tr>
	<tr><td align="right" width="42%">Date DN Payment</td><td>:
		<input type="text" name="dnpay" id="tanggal1" class="tanggal" value="'.$_REQUEST['dnpay'].'" size="10"/> s/d
		<input type="text" name="dnpay1" id="tanggal2" class="tanggal" value="'.$_REQUEST['dnpay1'].'" size="10"/></td>
	<tr><td align="center" colspan="2"><input type="submit" name="tanggalsoa" value="Cari" class="button"></td></tr>
	</form></table></fieldset>';

if ($_REQUEST['tanggalsoa']=="Cari") {
if ($_REQUEST['cat']=="") {	echo '<div align="center"><font color="red"><blink>Silahkan pilih nama Costumer...!!</div></font></blink>';	}
elseif ($_REQUEST['dnpay']=="" OR $_REQUEST['dnpay1']==""){	echo '<div align="center"><font color="red"><blink>Tanggal DN payment tidak boleh kosong...!!</div></font></blink>';	}
else{
$tgl1 = explode("/", $_REQUEST['dnpay']);	$tglawal = $tgl1[2].'-'.$tgl1[1].'-'.$tgl1[0];	if ($_REQUEST['dnpay'])
$tgl2 = explode("/", $_REQUEST['dnpay1']);	$tglawal1 = $tgl2[2].'-'.$tgl2[1].'-'.$tgl2[0];	if ($_REQUEST['dnpay1'])
if ($_REQUEST['cat'])										{	$satu= 'AND fu_ajk_dn.id_cost = "'.$_REQUEST['cat'].'"';	}
if ($_REQUEST['subcat'])									{	$tiga= 'AND fu_ajk_dn.id_regional = "'.$_REQUEST['subcat'].'"';	}
if ($_REQUEST['dnpay']!='' AND $_REQUEST['dnpay1']!='')		{	$dua= 'AND fu_ajk_dn.tgl_dn_paid BETWEEN "'.$tglawal.'" AND "'.$tglawal1.'"';	}
echo '<a href="ajk_report_fu.php?fu=metpdf&cost='.$_REQUEST['cat'].'&dnpay='.$_REQUEST['dnpay'].'&dnpay1='.$_REQUEST['dnpay1'].'&reg='.$_REQUEST['subcat'].'" target="_blank"><img src="image/dninvoice.png" width="21"></a> &nbsp;
	  <a href="ajk_report_fu.php?fu=metexcel&cost='.$_REQUEST['cat'].'&dnpay='.$_REQUEST['dnpay'].'&dnpay1='.$_REQUEST['dnpay1'].'&reg='.$_REQUEST['subcat'].'" title="Laporan DN Payment ke Excel"><img src="image/excel.png" width="21"></a></center>';
echo '<table border="0" width="100%" cellpadding="3" cellspacing="1">
	  <tr><td colspan="50%" valign="top">
		<table border="0" width="100%" cellpadding="3" cellspacing="1" bgcolor="#bde0e6">
		<tr><th rowspan="2" width="1%">No</th>
			<th rowspan="2">Tanggal Pembayaran</th>
			<th colspan="2" width="30%">Debit Note</th>
		</tr>
		<tr><th width="15%">Jumlah DN</th><th>Premi</th></tr>';

if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 50;	}	else	{	$m = 0;		}
$arm = $database->doQuery('SELECT
fu_ajk_dn.id_cost AS costid,
fu_ajk_dn.tgl_dn_paid AS tglbayar,
COUNT(fu_ajk_dn.dn_kode) AS jDN,
SUM(fu_ajk_dn.totalpremi) AS jPremi
FROM fu_ajk_dn
WHERE fu_ajk_dn.id!="" '.$satu.' '.$dua.' '.$tiga.' AND fu_ajk_dn.del IS NULL
GROUP BY fu_ajk_dn.id_cost, fu_ajk_dn.tgl_dn_paid
ORDER BY fu_ajk_dn.tgl_dn_paid ASC LIMIT ' . $m . ' , 50');

$totalRows = $database->doQuery('SELECT count(tgl_dn_paid)AS jumlah FROM fu_ajk_dn WHERE id != "" '.$satu.' '.$tiga.' '.$dua.' AND del IS NULL GROUP BY tgl_dn_paid');
while ($a = mysql_fetch_array($totalRows)) {	$b = ++$no2;	}	$totalRows = $b;		//BARIS PAGENATION

$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while ($met = mysql_fetch_array($arm)) {
if ($_REQUEST['subcat']=="") {
	$print_arm = '<a href="ajk_report_fu.php?fu=updatednarm&tglpaid='.$met['tglbayar'].'&cost='.$met['costid'].'" title="print '._convertDate($met['tglbayar']).'" target="_blank"><img src="image/print.png" width="21"></a>';
}else{
$print_arm = '<a href="ajk_report_fu.php?fu=updatednarm&tglpaid='.$met['tglbayar'].'&cost='.$met['costid'].'&reg='.$met['regnya'].'" title="print '._convertDate($met['tglbayar']).'" target="_blank"><img src="image/print.png" width="21"></a>';
}
if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	<td align="center">'.(++$no + ($pageNow-1) * 50).'</td>
	<td><a href="ajk_ARMreport.php?arm=mametview&date='.$met['tglbayar'].'&cost='.$met['costid'].'">'._convertDate($met['tglbayar']).'</a></td>
	<td align="center">'.duit($met['jDN']).'</td>
	<td align="right"><font color="blue"><a href="ajk_report_fu.php?fu=updatednarm&cost='.$_REQUEST['cat'].'&reg='.$_REQUEST['reg'].'&tglpaid='.$met['tglbayar'].'" target="_blank">'.duit($met['jPremi']).'</a></font></td>
	</tr>';
}
echo '</td></tr>
	  </table>
	  </td>
	  <td width="50%" valign="top">';
// KOLOM TABLE CN
echo '<table border="0" width="100%" cellpadding="3" cellspacing="1" bgcolor="#bde0e6">
	<tr><th rowspan="2" width="1%">No</th>
		<th rowspan="2">Tanggal Pembayaran</th>
		<th colspan="2" width="30%">Credit Note</th>
	</tr>
	<tr><th width="15%">Jumlah CN</th><th>Nilai</th></tr>';
$arm_cn = $database->doQuery('SELECT fu_ajk_dn.id_cost AS costid,
									 fu_ajk_dn.tgl_dn_paid AS tglbayar,
									 COUNT(fu_ajk_cn.id_cn) AS jCN,
									 SUM( IF( fu_ajk_cn.total_claim <0, 0, fu_ajk_cn.total_claim ) ) AS jNilai
									 FROM fu_ajk_cn
									 LEFT JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_dn = fu_ajk_dn.dn_kode
									 WHERE fu_ajk_dn.id!="" '.$satu.' '.$dua.' '.$tiga.' AND fu_ajk_dn.del IS NULL AND fu_ajk_cn.type_claim != "Death"
									 GROUP BY fu_ajk_dn.tgl_dn_paid
									 ORDER BY fu_ajk_dn.tgl_dn_paid ASC');
while ($met_cn = mysql_fetch_array($arm_cn)) {
if (($no1 % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	<td align="center">'.(++$no1 + ($pageNow-1) * 50).'</td>
	<td><a href="ajk_ARMreport.php?arm=mametview&date='.$met_cn['tglbayar'].'&cost='.$met_cn['costid'].'">'._convertDate($met_cn['tglbayar']).'</a></td>
	<td align="center">'.duit($met_cn['jCN']).'</td>
	<td align="right"><font color="blue"><a href="ajk_report_fu.php?fu=updatecnarm&cost='.$met_cn['costid'].'&reg='.$_REQUEST['reg'].'&tglpaid='.$met_cn['tglbayar'].'" target="_blank">'.duit($met_cn['jNilai']).'</a></font></td>
	</tr>';
}
echo '</table>';
// KOLOM TABLE CN
echo '</td></tr>';
echo '<tr><td colspan="10">';
echo createPageNavigations($file = 'ajk_ARMreport.php?arm=updatednpayment', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 50);
echo '<b>Total Date Payment: <u>' . $totalRows . '</u></b></td></tr>';
echo '</table>';
	}
}
	;
	break;
	case "mametview":
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
<tr><th width="95%" align="left">Modul View Report Summery DN Payment</font></th><th><a href="ajk_ARMreport.php?arm=updatednpayment"><img src="image/Backward-64.png" width="20"></th></tr>
</table>';
$met = mysql_fetch_array($database->doQuery('SELECT *, SUM(totalpremi) AS tpremi, COUNT(dn_kode) AS tkodedn FROM fu_ajk_dn WHERE tgl_dn_paid="'.$_REQUEST['date'].'" AND id_cost="'.$_REQUEST['cost'].'" AND del IS NULL GROUP BY tgl_dn_paid'));
echo '<table border="0" cellpadding="1" cellspacing="0" width="100%">
	  <tr><td width="10%"><b>Date Payment </td><td><b>: '._convertDate($_REQUEST['date']).'</b></td></tr>
	  <tr><td><b>Total Premi </td><td><b>: '.duit($met['tpremi']).'</b></td></tr>
	  <tr><td><b>Total DN </td><td><b>: '.duit($met['tkodedn']).'</b></td></tr>
	  </table>';
echo '<table border="0" width="100%" cellpadding="3" cellspacing="1" bgcolor="#bde0e6">
	<tr><th width="1%">No</th>
		<th>Company</th>
		<th width="10%">Debit Note</th>
		<th width="5%">Premi</th>
		<th width="5%">Peserta</th>
		<th width="10%">Credit Note</th>
		<th width="5%">Nilai CN</th>
		<th width="5%">Nett Premi</th>
		<th width="10%">Regional</th>
		<th width="10%">Area</th>
		<th width="12%">Cabang</th>
		<th width="6%">Tanggal DN Proses</th>
		<th width="8%">User</th>
	</tr>';
if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 100;	}	else {	$m = 0;		}
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
$mets = $database->doQuery('SELECT * FROM fu_ajk_dn WHERE tgl_dn_paid="'.$_REQUEST['date'].'" AND id_cost="'.$_REQUEST['cost'].'" AND del IS NULL ORDER BY update_time DESC LIMIT ' . $m . ' , 100');
while ($mamet = mysql_fetch_array($mets)) {
$metcostumer = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$mamet['id_cost'].'"'));
$metpeserta = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_dn="'.$mamet['dn_kode'].'" AND del IS NULL');
$rpeserta = mysql_num_rows($metpeserta);

//DATA CN PENDAMPING DN//
$dncnnya = mysql_fetch_array($database->doQuery('SELECT
fu_ajk_cn.id_cn,
fu_ajk_cn.id_dn,
fu_ajk_cn.tgl_createcn,
SUM(fu_ajk_cn.total_claim) AS tclaim,
fu_ajk_cn.type_claim
FROM
fu_ajk_cn
WHERE
fu_ajk_cn.id_dn = "'.$mamet['dn_kode'].'"
GROUP BY
fu_ajk_cn.id_cn'));
if ($dncnnya['id_dn']==$mamet['dn_kode']) {
	$cnnomor = $dncnnya['id_cn'];
	$cnpremi = duit($dncnnya['tclaim']);
	$statuscn = '<font color="red">'.$dncnnya['type_claim'].'</font>';
}else{
	$cnnomor = '-';
	$cnpremi = '-';
	$statuscn = 'Inforce';
}
	$netpremi = $mamet['totalpremi'] - $dncnnya['tclaim'];
//DATA CN PENDAMPING DN//
if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		<td width="1%" align="center">'.++$no.'</td>
		<td>'.$metcostumer['name'].'</td>
		<td align="center">'.$mamet['dn_kode'].'</td>
		<td align="right">'.duit($mamet['totalpremi']).' &nbsp; </td>
		<td align="center">'.duit($rpeserta).' &nbsp; </td>
		<td align="center">'.$cnnomor.'</td>
		<td align="right">'.$cnpremi.'</td>
		<td align="right">'.duit($netpremi).'</td>
		<td align="center">'.$mamet['id_regional'].'</td>
		<td align="center">'.$mamet['id_area'].'</td>
		<td align="center">'.$mamet['id_cabang'].'</td>
		<td align="center">'.$mamet['update_time'].'</td>
		<td align="center">'.$mamet['update_by'].'</td>
	</tr>';
}
echo '</table>';
		;
		break;
	case "soa":
$cat=$_GET['cat'];	if(strlen($cat) > 0 and !is_numeric($cat)){ echo "Data Error";	exit;	}

echo '<table border="0" cellpadding="5" cellspacing="0" width="100%"><tr><th width="90%" align="left">Modul Report State of Account</th></tr></table>';
echo '<fieldset style="padding: 2">
	<legend align="center">S e a r c h</legend>
	<table border="0" width="100%" cellpadding="1" cellspacing="1">
	<form method="post" action="">
	<tr><td width="40%" align="right">Company</td>
		<td>: <select id="cat" name="cat" onchange="reloadsoa(this.form)">
	  	<option value="">---Select Company---</option>';
$quer2=$database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name ASC');
while($noticia2 = mysql_fetch_array($quer2)) {
if($noticia2['id']==$cat){echo '<option selected value="'.$noticia2['id'].'">'.$noticia2['name'].'</option><BR>';}
else{echo  '<option value="'.$noticia2['id'].'">'.$noticia2['name'].'</option>';}
}
echo '</select></td></tr>
	<tr><td align="right">Regional</td>
		<td>: ';
if(isset($cat) and strlen($cat) > 0){	$quer=$database->doQuery('SELECT * FROM fu_ajk_regional where id_cost="'.$cat.'" ORDER BY name ASC');	}else{ }
echo '<select name="subcatreg"><option value="">---Pilih Regional---</option>';
while($regquer = mysql_fetch_array($quer)) {
if($regquer['name']==$_REQUEST['subcatreg']){echo '<option selected value="'.$regquer['name'].'">'.$regquer['name'].'</option><BR>';}
else{	echo  '<option value="'.$regquer['name'].'">'.$regquer['name'].'</option>';	}
}
echo '</td></tr>
	<tr><td align="right">Tanggal DN</td><td>: <input type="text" name="dncreate" id="tanggal1" class="tanggal" value="'.$_REQUEST['dncreate'].'" size="10"/> s/d
										    <input type="text" name="dncreate1" id="tanggal2" class="tanggal" value="'.$_REQUEST['dncreate1'].'" size="10"/>
	</td></tr>
	<tr><td align="right">Tanggal Pembayaran DN</td><td>: <input type="text" name="dnpaid" id="tanggal3" class="tanggal" value="'.$_REQUEST['dnpaid'].'" size="10"/> s/d
										    <input type="text" name="dnpaid1" id="tanggal4" class="tanggal" value="'.$_REQUEST['dnpaid1'].'" size="10"/>
	</td></tr>
	<tr><td align="right">Status </td>
 		<td>: <input type="radio"'.pilih($_REQUEST['armstatus'], "paid").' name="armstatus" value="paid">Paid &nbsp;
			  <input type="radio"'.pilih($_REQUEST['armstatus'], "unpaid").' name="armstatus" value="unpaid">Unpaid &nbsp;
			  <input type="radio"'.pilih($_REQUEST['armstatus'], "").' name="armstatus" value="">All
		</td>
	 </tr>
	 <tr><td colspan="2" align="center"><input type="submit" name="soasearch" value="Cari" class="button"></td></tr>
		</form></table></fieldset>';
$tgl1 = explode("/", $_REQUEST['dncreate']);	$tglawal = $tgl1[2].'-'.$tgl1[1].'-'.$tgl1[0];
$tgl2 = explode("/", $_REQUEST['dncreate1']);	$tglawal1 = $tgl2[2].'-'.$tgl2[1].'-'.$tgl2[0];
$tgl3 = explode("/", $_REQUEST['dnpaid']);		$tglawal2 = $tgl3[2].'-'.$tgl3[1].'-'.$tgl3[0];
$tgl4 = explode("/", $_REQUEST['dnpaid1']);		$tglawal3 = $tgl4[2].'-'.$tgl4[1].'-'.$tgl4[0];
if ($_REQUEST['dncreate']!='' AND $_REQUEST['dncreate1']!='')		{	$satu= 'AND fu_ajk_dn.tgl_createdn BETWEEN "'.$tglawal.'" AND "'.$tglawal1.'"';	}
if ($_REQUEST['cat']!='')											{	$dua= 'AND fu_ajk_dn.id_cost="'.$_REQUEST['cat'].'"';	}
if ($_REQUEST['subcatreg']!='')										{	$tiga= 'AND fu_ajk_dn.id_regional="'.$_REQUEST['subcatreg'].'"';	}
if ($_REQUEST['armstatus']!='')										{	$empat = 'AND fu_ajk_dn.dn_status = "'.$_REQUEST['armstatus'].'"';	}
if ($_REQUEST['dnpaid']!='' AND $_REQUEST['dnpaid1']!='')			{	$lima= 'AND fu_ajk_dn.tgl_dn_paid BETWEEN "'.$tglawal2.'" AND "'.$tglawal3.'"';	}

if ($_REQUEST['soasearch']=="Cari") {
	if ($_REQUEST['cat']=="") {	echo '<font color="red"><center><blink>Silahkan pilih nama client...!</blink></font></center>';	}
	elseif ($_REQUEST['dncreate']=="" OR $_REQUEST['dncreate1']=="") {	echo '<font color="red"><center><blink>Silahkan pilih tanggal DN...!</blink></font></center>';	}
	else{
echo '<a href="ajk_report_fu.php?fu=metsoapdf&cost='.$_REQUEST['cat'].'&regional='.$_REQUEST['subcatreg'].'&dncreate='.$_REQUEST['dncreate'].'&dncreate1='.$_REQUEST['dncreate1'].'&dnpaid='.$_REQUEST['dnpaid'].'&dnpaid1='.$_REQUEST['dnpaid1'].'&dnstatus='.$_REQUEST['armstatus'].'" target="_blank" title="Report SAO ke PDF"><img src="image/dninvoice.png" width="21"></a></center> &nbsp;
	  <a href="ajk_report_fu.php?fu=metsoa&cost='.$_REQUEST['cat'].'&regional='.$_REQUEST['subcatreg'].'&dncreate='.$_REQUEST['dncreate'].'&dncreate1='.$_REQUEST['dncreate1'].'&dnpaid='.$_REQUEST['dnpaid'].'&dnpaid1='.$_REQUEST['dnpaid1'].'&dnstatus='.$_REQUEST['armstatus'].'" target="_blank" title="Report SAO ke Excel"><img src="image/excel.png" width="21"></a></center>';

if ($_REQUEST['armstatus']=="paid") {	$statussoanya = "PAID";	}
elseif ($_REQUEST['armstatus']=="unpaid") {	$statussoanya = "UNPAID";	}
else {	$statussoanya = "PAID AND UNPAID";	}

$metclient = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$_REQUEST['cat'].'"'));
if ($_REQUEST['armstatus']=="paid") {
$totsoapaiddn_ = mysql_fetch_array($database->doQuery('SELECT fu_ajk_dn.id_cost, fu_ajk_dn.id_nopol, fu_ajk_dn.id_regional, fu_ajk_dn.dn_kode, SUM(fu_ajk_dn.totalpremi) AS tPremiDN, fu_ajk_dn.dn_status, fu_ajk_dn.tgl_dn_paid, fu_ajk_dn.tgl_createdn, fu_ajk_dn.del FROM fu_ajk_dn
WHERE fu_ajk_dn.dn_kode !="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' AND fu_ajk_dn.del IS NULL
GROUP BY fu_ajk_dn.id_cost'));

$totsoapaidcn_ = mysql_fetch_array($database->doQuery('SELECT fu_ajk_dn.id_cost, fu_ajk_dn.id_nopol, fu_ajk_dn.id_regional, fu_ajk_dn.dn_status, fu_ajk_dn.tgl_dn_paid, fu_ajk_dn.tgl_createdn, fu_ajk_dn.del, SUM(fu_ajk_cn.total_claim) AS tNilai FROM fu_ajk_dn
LEFT JOIN fu_ajk_cn ON fu_ajk_dn.id_cost = fu_ajk_cn.id_cost AND fu_ajk_dn.dn_kode = fu_ajk_cn.id_dn
WHERE fu_ajk_dn.dn_kode !="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' AND fu_ajk_dn.del IS NULL
GROUP BY fu_ajk_dn.id_cost'));

$_totsoapaiddn = $totsoapaiddn_['tPremiDN'];
$_totsoapaidcn = $totsoapaidcn_['tNilai'];
$_totsoapaiddncn = $totsoapaiddn_['tPremiDN'] - $totsoapaidcn_['tNilai'];
$totalnya = $_totsoapaiddncn;
/*
$paidnya = "Paid";
	$totsoa1 = $database->doQuery('SELECT * FROM fu_ajk_dn WHERE dn_kode !="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' AND del IS NULL ');
	while ($arraysoadn = mysql_fetch_array($totsoa1)) {
		$totsoadnpaid += $arraysoadn['totalpremi'];
		$soacn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cn WHERE id_dn="'.$arraysoadn['dn_kode'].'" AND id_cost="'.$arraysoadn['id_cost'].'"'));
		$totsoacnpaid += $soacn['total_claim'];
	}
	$totsoapaid = $totsoadnpaid - $totsoacnpaid;
*/
}
elseif ($_REQUEST['armstatus']=="unpaid") {
$totsoaunpaiddn_ = mysql_fetch_array($database->doQuery('SELECT fu_ajk_dn.id_cost, fu_ajk_dn.id_nopol, fu_ajk_dn.id_regional, fu_ajk_dn.dn_kode, SUM(fu_ajk_dn.totalpremi) AS tPremiDN, fu_ajk_dn.dn_status, fu_ajk_dn.tgl_dn_paid, fu_ajk_dn.tgl_createdn, fu_ajk_dn.del FROM fu_ajk_dn
WHERE fu_ajk_dn.dn_kode !="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' AND fu_ajk_dn.del IS NULL
GROUP BY fu_ajk_dn.id_cost'));

$totsoaunpaidcn_ = mysql_fetch_array($database->doQuery('SELECT fu_ajk_dn.id_cost, fu_ajk_dn.id_nopol, fu_ajk_dn.id_regional, fu_ajk_dn.dn_status, fu_ajk_dn.tgl_dn_paid, fu_ajk_dn.tgl_createdn, fu_ajk_dn.del, SUM(fu_ajk_cn.total_claim) AS tNilai FROM fu_ajk_dn
LEFT JOIN fu_ajk_cn ON fu_ajk_dn.id_cost = fu_ajk_cn.id_cost AND fu_ajk_dn.dn_kode = fu_ajk_cn.id_dn
WHERE fu_ajk_dn.dn_kode !="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' AND fu_ajk_dn.del IS NULL
GROUP BY fu_ajk_dn.id_cost'));

	$_totsoaunpaiddn = $totsoaunpaiddn_['tPremiDN'];
	$_totsoaunpaidcn = $totsoaunpaidcn_['tNilai'];
	$_totsoaunpaiddncn = $totsoaunpaiddn_['tPremiDN'] - $totsoaunpaidcn_['tNilai'];
	$totalnya = $_totsoaunpaiddncn;
}
else {
/*
  $totsoa1 = mysql_fetch_array($database->doQuery('SELECT SUM(totalpremi) AS tpremi FROM fu_ajk_dn WHERE dn_kode !="" '.$satu.' '.$dua.' '.$tiga.' AND dn_status="paid" AND del IS NULL '));
  $totsoapaid=$totsoa1['tpremi'];
  $totsoa2 = mysql_fetch_array($database->doQuery('SELECT SUM(totalpremi) AS tpremi FROM fu_ajk_dn WHERE dn_kode !="" '.$satu.' '.$dua.' '.$tiga.' AND dn_status="unpaid" AND del IS NULL '));
  $totsoaunpaid=$totsoa2['tpremi'];
*/
/*
$totsoa1 = $database->doQuery('SELECT * FROM fu_ajk_dn WHERE dn_kode !="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' AND dn_status !="unpaid" AND del IS NULL ');
while ($arraysoadn = mysql_fetch_array($totsoa1)) {
	$totsoadnpaid += $arraysoadn['totalpremi'];
	$soacn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cn WHERE id_dn="'.$arraysoadn['dn_kode'].'" AND id_cost="'.$arraysoadn['id_cost'].'"'));
	$totsoacnpaid += $soacn['total_claim'];
}
$_totsoapaid = $totsoadnpaid - $totsoacnpaid;

$totsoa2 = $database->doQuery('SELECT * FROM fu_ajk_dn WHERE dn_kode !=""  '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' AND dn_status="unpaid" AND del IS NULL ');
while ($arraysoadn2 = mysql_fetch_array($totsoa2)) {
	$totsoadnunpaid += $arraysoadn2['totalpremi'];
	$soacn2 = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cn WHERE id_dn="'.$arraysoadn2['dn_kode'].'" AND id_cost="'.$arraysoadn2['id_cost'].'"'));
	$totsoacnunpaid += $soacn2['total_claim'];
}
$_totsoaunpaid = $totsoadnunpaid - $totsoacnunpaid;
*/

//$totalnya = $_totsoapaid + $_totsoaunpaid;

/*
$totsoaunpaiddn_ = mysql_fetch_array($database->doQuery('SELECT fu_ajk_dn.id_cost, fu_ajk_dn.id_nopol, fu_ajk_dn.id_regional, fu_ajk_dn.dn_status, fu_ajk_dn.tgl_dn_paid, fu_ajk_dn.tgl_createdn, fu_ajk_dn.del, SUM(fu_ajk_cn.total_claim) AS tNilai, SUM(fu_ajk_dn.totalpremi) AS tPremi
FROM fu_ajk_dn LEFT JOIN fu_ajk_cn ON fu_ajk_dn.id_cost = fu_ajk_cn.id_cost AND fu_ajk_dn.dn_kode = fu_ajk_cn.id_dn
WHERE fu_ajk_dn.dn_kode !="" '.$satu.' '.$dua.' '.$tiga.' '.$lima.' AND fu_ajk_dn.del IS NULL
GROUP BY fu_ajk_dn.dn_status '));
*/

$_totsoapaiddn_ = $database->doQuery('SELECT fu_ajk_dn.id_cost,
fu_ajk_dn.id_nopol,
fu_ajk_dn.id_regional,
fu_ajk_dn.dn_kode,
fu_ajk_dn.dn_status,
fu_ajk_dn.totalpremi AS tPremi,
fu_ajk_dn.tgl_createdn,
fu_ajk_dn.del,
fu_ajk_dn.tgl_dn_paid,
SUM(IF(fu_ajk_cn.type_claim ="Death", 0, fu_ajk_cn.total_claim)) AS tNilai,
fu_ajk_cn.type_claim
FROM
fu_ajk_dn
LEFT JOIN fu_ajk_cn ON fu_ajk_dn.id_cost = fu_ajk_cn.id_cost AND fu_ajk_dn.dn_kode = fu_ajk_cn.id_dn
WHERE fu_ajk_dn.dn_kode !="" '.$satu.' '.$dua.' '.$tiga.' '.$lima.' AND fu_ajk_dn.del IS NULL
GROUP BY fu_ajk_dn.dn_status,fu_ajk_dn.dn_kode');
while ($_totsoapaiddn__ = mysql_fetch_array($_totsoapaiddn_)) {
if ($_totsoapaiddn__['dn_status']=="paid") {
$totsoapaiddn_1 += $_totsoapaiddn__['tPremi'] - $_totsoapaiddn__['tNilai'];
}else{
$totsoapaiddn_2 += $_totsoapaiddn__['tPremi'] - $_totsoapaiddn__['tNilai'];
}

}

	$_totsoapaiddncn = $totsoapaiddn_1;
	$_totsoaunpaiddncn = $totsoapaiddn_2;
	$totalnya = $_totsoapaiddncn + $_totsoaunpaiddncn;
}
//$totalnya = $_totsoapaiddn;

if ($_REQUEST['subcatreg']=="") { $soaregional = "PUSAT";	}else{	$soaregional = $_REQUEST['subcatreg'];	}
echo '<table border="0" width="100%" cellpadding="3" cellspacing="1" bgcolor="#DEDEDE">
	  <tr><td width="10%">Nama Perusahaan</td><td>: <b>'.$metclient['name'].'</b></td></tr>
	  <tr><td width="10%">Regional</td><td>: <b>'.$soaregional.'</b></td></tr>
	  <tr><td>Tanggal DN</td><td>: '._convertDate($tglawal).' s/d '._convertDate($tglawal1).'</td></tr>
	  <tr><td>Status DN</td><td>: '.$statussoanya.'</td></tr>
	  <!--<tr><td>Paid DN</td><td>: '.duit($totsoapaid).'</td></tr>-->
	  <tr><td>Nett Paid DN</td><td>: '.duit($_totsoapaiddncn).'</td></tr>
	  <tr><td>Nett unPaid DN</td><td>: '.duit($_totsoaunpaiddncn).'</td></tr>
	  <tr><td>Total DN</td><td>: <b>'.duit($totalnya).'</b></td></tr>
	  </table>';
echo '<table border="0" width="100%" cellpadding="3" cellspacing="1">
	<tr>
		<th rowspan="2" align="center" width="1%">No</th>
		<th rowspan="2" align="center" width="1%">Polis</th>
		<th colspan="4" align="center">DEBIT NOTE</th>
		<th colspan="4" align="center">CREDIT NOTE</th>
		<th rowspan="2" align="center" width="8%">Nett Premi</th>
		<th rowspan="2" align="center" width="8%">Payment</th>
		<th rowspan="2" align="center" width="1%">Date Payment</th>
		<th rowspan="2" align="center" width="8%">Balance</th>
		<th rowspan="2" align="center" width="1%">Status</th>
		<th rowspan="2" align="center" width="1%">Type</th>
		<th rowspan="2" align="center" width="10%">Regional</th>
		<th rowspan="2" align="center" width="10%">Cabang</th>
	</tr>
	<tr>
		<th align="center" width="10%">Nomor</th>
		<th align="center" width="1%">Tanggal</th>
		<th align="center" width="1%">Peserta</th>
		<th align="center" width="5%">Premi</th>
		<th align="center" width="10%">Nomor</th>
		<th align="center" width="1%">Tanggal</th>
		<th align="center" width="1%">Peserta</th>
		<th align="center" width="8%">Nilai</th>
	</tr>';

$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 30;	}	else {	$m = 0;		}
$metsoa = $database->doQuery('SELECT * FROM fu_ajk_dn WHERE dn_kode !="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' AND del IS NULL ORDER BY tgl_createdn ASC, dn_kode ASC LIMIT ' . $m . ' , 30');
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_dn WHERE id != "" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' AND del IS NULL '));
$totalRows = $totalRows[0];

while ($soa = mysql_fetch_array($metsoa)) {
$jumpeserta = mysql_num_rows($database->doQuery('SELECT id_dn, id_cost, del FROM fu_ajk_peserta WHERE id_cost="'.$soa['id_cost'].'" AND id_dn="'.$soa['dn_kode'].'" AND del IS NULL'));

//DATA CN//
$soacn = mysql_fetch_array($database->doQuery('SELECT fu_ajk_cn.id_cn,
													  fu_ajk_cn.id_dn,
													  fu_ajk_cn.id_cost,
													  fu_ajk_cn.tgl_createcn,
													  fu_ajk_cn.type_claim,
													  SUM(IF(fu_ajk_cn.type_claim ="Death", 0, fu_ajk_cn.total_claim)) AS total_claim
											   FROM fu_ajk_cn
											   WHERE id_cost="'.$soa['id_cost'].'" AND id_dn="'.$soa['dn_kode'].'"
											   GROUP BY fu_ajk_cn.id_dn'));
if ($soacn['id_dn']==$soa['dn_kode']) {
$jumpesertacn = mysql_num_rows($database->doQuery('SELECT id_dn, id_klaim, del FROM fu_ajk_peserta WHERE id_klaim="'.$soacn['id_cn'].'" AND del IS NULL'));
if ($soacn['total_claim'] < 0 ) {	$totalcn = 0;	}else{	$totalcn = $soacn['total_claim'];	}
$netnya = $soa['totalpremi'] - $totalcn;
}else{
$jumpesertacn ='';
$totalcn = '';
$netnya = $soa['totalpremi'];
}
//DATA CN//
if ($soa['dn_total']=="") {	$paymentnya = $soa['totalpremi'] - $totalcn; }	else	{	$paymentnya = $soa['dn_total'] - $totalcn;	}			//NILAI PAYMENT
$balancenya = $netnya - $paymentnya;													//NILAI BALANCE

//TAMPIL POLISNYA
$metdnpolis = mysql_fetch_array($database->doQuery('SELECT id, nopol FROM fu_ajk_polis WHERE id="'.$soa['id_nopol'].'"'));

if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		<td align="center">'.(++$no + ($pageNow-1) * 30).'</td>
		<td align="center">'.$metdnpolis['nopol'].'</td>
		<td align="center">'.$soa['dn_kode'].'</td>
		<td align="center">'._convertDate($soa['tgl_createdn']).'</td>
		<td align="center">'.$jumpeserta.'</td>
		<td align="right">'.duit($soa['totalpremi']).'</td>
		<td>'.$soacn['id_cn'].'</td>
		<td align="center">'._convertDate($soacn['tgl_createcn']).'</td>
		<td align="center">'.$jumpesertacn.'</td>
		<td align="right">'.duit($totalcn).'</td>
		<td align="right">'.duit($netnya).'</td>
		<td align="right">'.duit($paymentnya).'</td>
		<td align="center">'._convertDate($soa['tgl_dn_paid']).'</td>
		<td align="right">'.duit($balancenya).'</td>
		<td align="center">'.$soa['dn_status'].'</td>
		<td align="center">'.$soacn['type_claim'].'</td>
		<td>'.$soa['id_regional'].'</td>
		<td>'.$soa['id_cabang'].'</td>
	</tr>';
		}
echo '<tr><td colspan="22">';
echo createPageNavigations($file = 'ajk_ARMreport.php?arm=soa&cat='.$_REQUEST['cat'].'&dncreate='.$_REQUEST['dncreate'].'&dncreate1='.$_REQUEST['dncreate1'].'&armstatus='.$_REQUEST['armstatus'].'&', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 30);
echo '<b>Total Data DN Unpaid: <u>' . $totalRows . '</u></b></td></tr>';
echo '</table>';
}}
	;
	break;

	default:
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
<tr><th width="90%" align="left">Modul Report ARM</font></th></th></tr>
</table>';
echo '<fieldset style="padding: 2">
	<legend align="center">S e a r c h</legend>
	<table border="0" width="100%" cellpadding="1" cellspacing="1">
		<form method="post" action="" name="frmcust" onSubmit="return valcust()">
<tr><td width="20%" align="right">By Co. </td>
	  <td width="30%">: <select id="cat" name="cat" onchange="reload(this.form)">
	  	<option value="">---Select Company---</option>';
		$rows = mysql_query('select * from fu_ajk_costumer ORDER BY name ASC');
		while($row = mysql_fetch_array($rows)) {
			$sel = ""; if ($_POST["cat"] == $row["id"]) $sel = ' selected="selected"';
			echo '<option value="'.$row["id"].'"'.$sel.'>'.$row["name"].'</option>';
		}
echo '</select></td></tr>
		<tr><td align="right">By Branch</td>
		<td>: <select name="regional" id="regional">
			<option value="'.$_REQUEST['regional'].'">-- Select Regional --</option>
		</select></td></tr>
		<tr><td align="right">By Date Payment </td><td>:';
		print initCalendar();	print calendarBox('tgl3', 'triger3', $_REQUEST['tgl3']);	echo 's/d';
		print initCalendar();	print calendarBox('tgl4', 'triger4', $_REQUEST['tgl4']);
echo '</td></tr>
		<tr><td align="right">By Date Process </td><td>:';
		print initCalendar();	print calendarBox('tgl', 'triger', $_REQUEST['tgl']);	echo 's/d';
		print initCalendar();	print calendarBox('tgl2', 'triger2', $_REQUEST['tgl2']);
echo '</td></tr>
	  <tr><td align="right" width="3%">Status</td>
 		 <td width="10%">: <input type="radio" name="armstatus" value="paid">Paid &nbsp; <input type="radio" name="armstatus" value="unpaid">Unpaid</td></tr>
		<tr><td width="5%" align="center" colspan="3"><input type="submit" name="button" value="Search" class="button"></td></tr>
		</form></table></fieldset>';
if ($_REQUEST['button']=="Search") {
	if (!$_REQUEST['cat']) 						{	$errorARM .= 'Silahkan pilih nama client...!!<br />';	}
//	if (!$_REQUEST['tgl'] OR !$_REQUEST['tgl2'])	{	$errorARM .= 'Tanggal tidak boleh kosong...!!';	}
	if ($errorARM){	echo '<center><div class="kolomerrorARM"><blink>'.$errorARM.'<blink></div></center>';	}
	else{
$cekcostumer = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$_REQUEST['cat'].'"'));
$cekregional = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['regional'].'"'));
if ($_REQUEST['regional']=="") {	$regnya = "All Regional";	}else{ $regnya = $cekregional['name'];	}

if ($_REQUEST['cat'])								{	$satu = 'AND id_cost = "'.$_REQUEST['cat'].'"';	}
if ($_REQUEST['regional'])		{
	$cekregional = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['regional'].'"'));
	$dua = 'AND id_regional = "'.$cekregional['name'].'"';
}
if ($_REQUEST['tgl']!='' AND $_REQUEST['tgl2']!='')		{	$tiga= 'AND tgl_createdn BETWEEN \''.$_REQUEST['tgl'].'\' AND \''.$_REQUEST['tgl2'].'\'';	}
if ($_REQUEST['tgl3']!='' AND $_REQUEST['tgl4']!='')	{	$lima='AND tgl_dn_paid BETWEEN \''.$_REQUEST['tgl3'].'\' AND \''.$_REQUEST['tgl4'].'\'';	}
if ($_REQUEST['armstatus'])			{	$empat = 'AND dn_status = "' . $_REQUEST['armstatus'] . '"';		}

$met1 = $database->doQuery('SELECT *, SUM(totalpremi) AS premipaid FROM fu_ajk_dn WHERE id !="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' AND dn_status="paid" AND del IS NULL GROUP BY id');
$tpaiddn = mysql_num_rows($met1);
while ($tpaid = mysql_fetch_array($met1)){
$e += $tpaid['totalpremi'];
};

$met2 = $database->doQuery('SELECT *, SUM(totalpremi) AS premiunpaid FROM fu_ajk_dn WHERE id !="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' AND dn_status="unpaid" AND del IS NULL GROUP BY id');
$tunpaiddn = mysql_num_rows($met2);
while ($tunpaid = mysql_fetch_array($met2)){
$er += $tunpaid['totalpremi'];
};
echo '<table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#C6DEF7">
	  <tr><td align="left" width="10%"><b>Perusahaan</b></td>
	  	  <td align="left" colspan="8"><b>: '.$cekcostumer['name'].'</b></td>
		  <td align="right" rowspan="2" colspan="5"><a href="ajk_armreportPrint.php?cat='.$_REQUEST['cat'].'&regional='.$_REQUEST['regional'].'&tgl='.$_REQUEST['tgl'].'&tgl2='.$_REQUEST['tgl2'].'&tgl3='.$_REQUEST['tgl3'].'&tgl4='.$_REQUEST['tgl4'].'&armstatus='.$_REQUEST['armstatus'].'"><img src="image/excel.png" width="32"></a></td>
	  </tr>
 	  <tr><td align="left" width="10%"><b>Regional</b></td>
 	  	  <td align="left" colspan="2"><b>: '.$regnya.'</b></td></tr>
 	  <tr><td align="left"><b>Paid</b></td>
 	  	  <td width="10%"><b>: '.$tpaiddn.' DN</td><td><b>Rp. '.duit($e).'</b></td></tr>
 	  <tr><td align="left"><b>Unpaid</b></td>
 	  	  <td><b>: '.$tunpaiddn.' DN</td><td><b>Rp.  '.duit($er).'</b></td></tr>
 	  <tr><td align="left"><b>Grand Total</b></td>
 	  	  <td><b>: '.duit($tpaiddn + $tunpaiddn).' DN</td><td><b>Rp. '.duit($er + $e).'</b></td></tr>
	  </table>';
echo '<table border="0" width="100%" cellpadding="3" cellspacing="1" bgcolor="#bde0e6">
		<tr><th width="1%">No</th>
			<th width="10%">Policy</th>
			<th>DN Number</th>
			<th width="8%">Date Process</th>
			<th width="5%">Status</th>
			<th width="8%">Date Payment</th>
			<th width="5%">Jumlah Peserta</th>
			<th width="8%">Premium</th>
			<th>Area</th>
			<th>Cabang</th>
		</tr>';
//$metregional = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['regional'].'"'));
if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 50;	}	else	{	$m = 0;		}
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;

$met = $database->doQuery('SELECT * FROM fu_ajk_dn WHERE id !="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' AND del IS NULL ORDER BY dn_status ASC, dn_kode ASC, id_cabang DESC LIMIT ' . $m . ' , 50');
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_dn WHERE id != "" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' AND del IS NULL '));
$totalRows = $totalRows[0];

while ($mamet = mysql_fetch_array($met)) {
$metpolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$mamet['id_nopol'].'"'));
$metcostumer = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$mamet['id_cost'].'"'));

$metdn = mysql_num_rows($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_dn = "'.$mamet['dn_kode'].'" '));

if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		<td align="center">'.(++$no + ($pageNow-1) * 50).'</td>
		<td align="center">'.$metpolis['nopol'].'</td>
		<td align="center">'.$mamet['dn_kode'].'</td>
		<td align="center">'.$mamet['tgl_createdn'].'</td>
		<td align="center">'.strtoupper($mamet['dn_status']).'</td>
		<td align="center">'._convertDate($mamet['tgl_dn_paid']).'</td>
		<td align="center">'.$metdn.'</td>
		<td align="right">'.duit($mamet['totalpremi']).'</td>
		<td>'.$mamet['id_area'].'</tdh>
		<td>'.$mamet['id_cabang'].'</tdh>
	  </tr>';
$jumpeserta += $metdn;
$jumdnnya += $mamet['totalpremi'];
}
echo '<tr bgcolor="white"><td colspan="6" align="right"><b>Total Per Halaman :</b></td><td align="center"><b>'.duit($jumpeserta).'</b></td><td align="right"><b>'.duit($jumdnnya).'</b></td><td colspan="2">&nbsp;</td></tr>';
echo '<tr><td colspan="10">';
echo createPageNavigations($file = 'ajk_ARMreport.php?button=Search&cat='.$_REQUEST['cat'].'&regional='.$_REQUEST['regional'].'&tgl='.$_REQUEST['tgl'].'&tgl2='.$_REQUEST['tgl2'].'&tgl3='.$_REQUEST['tgl3'].'&tgl4='.$_REQUEST['tgl4'].'&armstatus='.$_REQUEST['armstatus'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 50);
echo '<b>Total Data DN: <u>' . $totalRows . '</u></b></td></tr>';
}
echo '</table>';
}
		;
} // switch
?>
<SCRIPT language=JavaScript>
function reloadupdatednpayment(form)
{
	var val=form.cat.options[form.cat.options.selectedIndex].value;
	self.location='ajk_ARMreport.php?arm=updatednpayment&cat=' + val;
}
</script>

<SCRIPT language=JavaScript>
function reloadpayment(form)
{
	var val=form.cat.options[form.cat.options.selectedIndex].value;
	self.location='ajk_ARMreport.php?arm=dnpayment&cat=' + val;
}
</script>

<SCRIPT language=JavaScript>
function reloaddatepayment(form)
{
	var val=form.cat.options[form.cat.options.selectedIndex].value;
	self.location='ajk_ARMreport.php?arm=dndateprocess&cat=' + val;
}
</script>

<SCRIPT language=JavaScript>
function reload(form)
{
	var val=form.cat.options[form.cat.options.selectedIndex].value;
	self.location='ajk_ARMreport.php?arm=dnprocess&cat=' + val;
}
</script>

<SCRIPT language=JavaScript>
function reloadsoa(form)
{
	var val=form.cat.options[form.cat.options.selectedIndex].value;
	self.location='ajk_ARMreport.php?arm=soa&cat=' + val;
}
</script>

<!--WILAYAH COMBOBOX  PENDING SCRIPT 30-07-13
<script src="../javascript/metcombo/prototype.js"></script>
<script src="../javascript/metcombo/dynamicombo.js"></script>
ILAYAH COMBOBOX
<script>
document.observe("dom:loaded",function(){
	new DynamiCombo( "company" , {
		elements:{
			"regional":		{url:'../javascript/metcombo/data.php?req=armregional', value:'id', label:'name', init:'<?php echo $_POST["regional"] ?>'}
		},
		loadingImage:'../image/loader1.gif',
		loadingText:'Loading...',
		debug:0
	} )
});
</script>
-->