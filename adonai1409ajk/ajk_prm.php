<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("../includes/functions.php");
connect();
if (session_is_registered('nm_user')) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));	}
$futgl = date("Y-m-d g:i:a");
$futglidprm = date("Y");
$futglprm = date("y-m-d");
switch ($_REQUEST['op']) {
case "dnassign":
$cat=$_GET['cat']; // Use this line or below line if register_global is off
if(strlen($cat) > 0 and !is_numeric($cat)){	echo "Data Error";	exit;	}
echo '<form method="post" action="">
	  <table border="0" cellpadding="3" cellspacing="0" width="100%">
	  <tr><th width="80%" align="left" colspan="8">Modul Status DN</font></th></tr>
	  <tr><td width="10%">Cleint</td>
	  	 <td width="10%">: <select id="cat" name="cat" onchange="reload(this.form)">
	<option value="">---Select Company---</option>';
	$quer2=$database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name ASC');
	while($noticia2 = mysql_fetch_array($quer2)) {
	if($noticia2['id']==$cat){echo '<option selected value="'.$noticia2['id'].'">'.$noticia2['name'].'</option><BR>';}
	else{echo  '<option value="'.$noticia2['id'].'">'.$noticia2['name'].'</option>';}
	}
echo '</select></td></tr>
	<tr><td width="10%">Regional</td>
		<td width="20%">: ';
if(isset($cat) and strlen($cat) > 0){
$quer=$database->doQuery('SELECT * FROM fu_ajk_regional where id_cost="'.$cat.'" ORDER BY id ASC');
}else{$quer=$database->doQuery("SELECT * FROM fu_ajk_regional ORDER BY id ASC"); }
echo '<select id="subcat" name="subcat" onchange="reload2(this.form)"><option value="">---Select Regional---</option>';
while($noticia = mysql_fetch_array($quer)) {
echo  '<option value="'.$noticia['name'].'">'.$noticia['name'].'</option>';
}
echo '</select></td></tr>
	  <tr><td width="10%">DN Number</td>
	  	 <td width="10%">: <input type="text" name="payreg" value='.$_REQUEST['payreg'].'></td></tr>
 	  <tr><td width="3%">Status</td>
 		 <td width="10%">: <input type="radio" name="armstatus" value="paid">Paid &nbsp; <input type="radio" name="armstatus" value="unpaid">Unpaid</td></tr>
	  <tr><td><input type="submit" name="button" value="Search" class="button"> &nbsp; <a href="ajk_prm.php?op=dnassign">Default</a></td></tr>
	  </form>';
if ($_REQUEST['oppe']=="editdatepayment") {
if ($_REQUEST['r']=="updatedn") {
	$r= $database->doQuery('UPDATE fu_ajk_dn SET tgl_dn_paid="'.$_REQUEST['rdns'].'", update_by="'.$q['nm_user'].'", update_time="'.$futgl.'" WHERE id="'.$_REQUEST['id'].'"');
	header("location:ajk_prm.php?op=dnassign");
}
$met = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn WHERE id="'.$_REQUEST['id'].'"'));
echo '<tr><td colspan="4" align="center"><form method="post" action="ajk_prm.php?op=dnassign&oppe=editdatepayment&r=updatedn&id='.$_REQUEST['id'].'">
	<input type="hidden" name="id" value="'.$_REQUEST['id'].'">
	Edit tanggal DN Payment <b>'.$met['dn_kode'].'</b>';print initCalendar();	print calendarBox('rdns', 'triger', $met['tgl_dn_paid']);
echo ' &nbsp; <input type="submit" name="ope" value="Paid"> &nbsp;  &nbsp; <a href="ajk_prm.php?op=dnassign">cancel</a></td></tr>';
}
if ($_REQUEST['oppe']=="deldatepayment") {
$met = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn WHERE id="'.$_REQUEST['id'].'"'));
$er= $database->doQuery('UPDATE fu_ajk_dn SET dn_status="unpaid", dn_total="", tgl_dn_paid="", update_by="'.$q['nm_user'].'", update_time="'.$futgl.'" WHERE id="'.$_REQUEST['id'].'"');
$erpeserta = $database->doQuery('UPDATE fu_ajk_peserta SET status_bayar="0", update_by="'.$q['nm_user'].'", update_time="'.$futgl.'" WHERE id_dn="'.$met['dn_kode'].'"');
header("location:ajk_prm.php?op=dnassign");
}
echo '</table>';
echo '<table border="0" cellpadding="1" cellspacing="1" width="100%" bgcolor="#bde0e6">
	  <tr><th width="1%">No</th>
	  	<th>Perusahaan</th>
	  	<th>Asuransi</th>
	  	<th width="8%">Debitnote</th>
	  	<th width="5%">Date DN</th>
	  	<th width="5%">Premi DN</th>
	  	<th width="5%">Paid DN</th>
	  	<th width="1%">Status</th>
	  	<th width="8%">Creditnote</th>
	  	<th width="5%">Date CN</th>
	  	<th width="5%">Premi CN</th>
	  	<th width="5%">Paid CN</th>
	  	<th width="5%">Nett Premi</th>
	  	<th width="10%">Cabang</th>
	  	<th width="4%">Option</th>
	</tr>';
if ($_REQUEST['payreg'])			{	$satu = 'AND dn_kode LIKE "%' . $_REQUEST['payreg'] . '%"';		}
if ($_REQUEST['armstatus'])			{
	if ($_REQUEST['armstatus']=="paid") {
		$dua = 'AND dn_status = "' . $_REQUEST['armstatus'] . '" OR  dn_status = "paid"';
	}else{
		$dua = 'AND dn_status = "' . $_REQUEST['armstatus'] . '"';
	}
}
if ($_REQUEST['cat'])				{	$tiga = 'AND id_cost = "' . $_REQUEST['cat'] . '"';		}
if ($_REQUEST['subcat'])				{	$empat = 'AND id_regional = "' . $_REQUEST['subcat'] . '"';		}
if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}
else {	$m = 0;		}

$fdn = $database->doQuery('SELECT * FROM fu_ajk_dn WHERE id !="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' AND del IS NULL ORDER BY tgl_dn_paid DESC, tgl_createdn DESC LIMIT ' . $m . ' , 25');
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_dn WHERE id != "" ' . $satu . ' '.$dua.' '.$tiga.' '.$empat.' AND del IS NULL '));
$totalRows = $totalRows[0];
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;

while ($rdn = mysql_fetch_array($fdn)) {
$metasuransi = mysql_fetch_array($database->doQuery('SELECT id,name FROM fu_ajk_asuransi WHERE id="'.$rdn['id_as'].'"'));
$metarm = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_prm WHERE id="'.$rdn['id_prm'].'"'));
if ($metarm['id_prm']=="" AND $rdn !="") {	$nomorARM = '<i>None</i>';	}	else {	$nomorARM = $metarm['id_prm'];	}

$metcompany = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$rdn['id_cost'].'"'));
if ($rdn['dn_status']=="unpaid") {	$statusdn = '<blink><font color="red">Unpaid</font></blink>';	}	else	{	$statusdn = '<font color="blue">Paid</font>';	}

$fee = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id_cost="'.$rdn['id_cost'].'"'));
if (($no % 2) == 1)	$objlass = 'tbl-odd'; else $objlass = 'tbl-even';

if ($rdn['dn_status']=="paid" OR $rdn['dn_status']=="paid(*)" OR $rdn['dn_status']=="Lunas") {	$mametoptArm = '<a href="ajk_prm.php?op=dnassign&oppe=editdatepayment&id='.$rdn['id'].'"><img src="image/edit3.png" width="20"></a> &nbsp;
													<a href="ajk_prm.php?op=dnassign&oppe=deldatepayment&id='.$rdn['id'].'" onClick="if(confirm(\'Anda yakin untuk membatalkan pembayaran DN : '.$rdn['dn_kode'].' ?\')){return true;}{return false;}"><img src="image/deleted.png" width="20"></a> &nbsp';	}

else{	$mametoptArm ='';	}

//DATA CN PENDAMPING DN//
$metcn = mysql_fetch_array($database->doQuery('SELECT
fu_ajk_cn.id_cn,
fu_ajk_cn.id_dn,
fu_ajk_cn.tgl_createcn,
SUM(fu_ajk_cn.total_claim) AS tclaim,
fu_ajk_cn.type_claim
FROM fu_ajk_cn
WHERE fu_ajk_cn.id_dn = "'.$rdn['dn_kode'].'" AND id_cost="'.$rdn['id_cost'].'" AND fu_ajk_cn.type_claim !="Death" AND fu_ajk_cn.type_claim !="Refund" AND fu_ajk_cn.del IS NULL
GROUP BY fu_ajk_cn.id_dn'));
$mametnett = $rdn['totalpremi'] - $metcn['tclaim'];
//DATA CN PENDAMPING DN//
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	<td align="center">'.(++$no + ($pageNow-1) * 25).'</td>	<td>'.$metcompany['name'].'</td>
	<td>'.$metasuransi['name'].'</td>
	<td align="center">'.substr($rdn['dn_kode'],3).'</td>
	<td align="center">'._convertDate($rdn['tgl_createdn']).'</td>
	<td align="right">'.duit($rdn['totalpremi']).'</td>
	<td align="center">'._convertDate($rdn['tgl_dn_paid']).'</td>
	<td align="center">'.$statusdn.'</td>
	<td align="center"><b>'.substr($metcn['id_cn'],3).'</b></td>
	<td align="center">'._convertDate($metcn['tgl_createcn']).'</td>
	<td align="right">'.duit($metcn['tclaim']).'</td>
	<td align="center">'._convertDate($metcn['tgl_byr_claim']).'</td>
	<td align="right"><b>'.duit($mametnett).'</b></td>
	<td>'.$rdn['id_cabang'].'</td>
	<td align="center">'.$mametoptArm.'</td>
	</tr>';
}
$met = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn WHERE id="'.$_REQUEST['dn'].'"'));
$totalnya = $met['totalpremi'] + $fee['bpolis'] + $fee['bmaterai'];
echo '</table>
	  </td></tr>';
	echo '<tr><td colspan="22">';
	echo createPageNavigations($file = 'ajk_prm.php?op=dnassign&armstatus='.$_REQUEST['armstatus'].'&cat='.$_REQUEST['cat'].'&subcat='.$_REQUEST['subcat'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
	echo '<b>Total Data DN: <u>' . $totalRows . '</u></b></td></tr>';
echo '</table>';
	;
	break;

case "cnassign":
$cat=$_GET['cat']; // Use this line or below line if register_global is off
if(strlen($cat) > 0 and !is_numeric($cat)){ // to check if $cat is numeric data or not.
	echo "Data Error";	exit;	}

echo '<form method="post" action="">
	  <table border="0" cellpadding="3" cellspacing="0" width="100%">
	  <tr><th width="80%" align="left" colspan="8">Modul CN Assignment</font></th></tr>
	  <tr><td width="10%">Nama Perusahaan</td>
	  	 <td width="10%">: <select id="cat" name="cat" onchange="reloadcn(this.form)">
		<option value="">---Select Company---</option>';
	$quer2=$database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name ASC');
	while($noticia2 = mysql_fetch_array($quer2)) {
	if($noticia2['id']==$cat){echo '<option selected value="'.$noticia2['id'].'">'.$noticia2['name'].'</option><BR>';}
else{echo  '<option value="'.$noticia2['id'].'">'.$noticia2['name'].'</option>';}
	}
echo '</select></td></tr>
		<tr><td width="10%">Regional</td>
			<td width="20%">: ';
if(isset($cat) and strlen($cat) > 0){
	$quer=$database->doQuery('SELECT * FROM fu_ajk_regional where id_cost="'.$cat.'" ORDER BY id ASC');
}else{$quer=$database->doQuery("SELECT * FROM fu_ajk_regional ORDER BY id ASC"); }
	echo '<select id="subcat" name="subcat" onchange="reload2(this.form)"><option value="">---Select Regional---</option>';
while($noticia = mysql_fetch_array($quer)) {
	echo  '<option value="'.$noticia['name'].'">'.$noticia['name'].'</option>';
}
echo '</select></td></tr>
		  <tr><td width="10%">Nomor Creditnote</td>
		  	 <td width="10%">: <input type="text" name="payreg" value='.$_REQUEST['payreg'].'></td></tr>
	 	  <tr><td width="3%">Status</td>
	 		 <td width="10%">: <input type="radio" name="armstatus" value="paid">Paid &nbsp; <input type="radio" name="armstatus" value="unpaid">Unpaid</td></tr>
		  <tr><td><input type="submit" name="button" value="Search" class="button"> &nbsp; <a href="ajk_prm.php?op=cnassign">Default</a></td></tr>
		  </form></table>';
echo '<table border="0" cellpadding="5" cellspacing="1" width="100%" bgcolor="#bde0e6">
	  <tr><th width="1%">No</th>
	  	<th>Perusahaan</th>
	  	<th>Asuransi</th>
	  	<th width="8%">Creditnote</th>
	  	<th width="5%">Tgl CN</th>
	  	<th width="5%">Nilai CN</th>
	  	<!-- <th width="14%">No. ARM</th> -->
	  	<th width="5%">Due Date</th>
	  	<th>Status</th>
	  	<th width="8%">Debitnote</th>
	  	<th width="5%">Tgl DN</th>
	  	<th width="5%">Premi</th>
	  	<th width="5%">Nett Premi</th>
	  	<th width="5%">Status</th>
		<th width="10%">Regional</th>
	  	<th width="15%">Cabang</th>
	</tr>';
if ($_REQUEST['payreg'])			{	$satu = 'AND fu_ajk_cn.id_cn LIKE "%' . $_REQUEST['payreg'] . '%"';		}
if ($_REQUEST['armstatus']=="paid")	{	$dua = 'AND fu_ajk_cn.tgl_byr_claim != ""';		}
if ($_REQUEST['armstatus']=="unpaid"){	$duaa = 'AND fu_ajk_cn.tgl_byr_claim = ""';		}
if ($_REQUEST['cat'])				{	$tiga = 'AND fu_ajk_cn.id_cost = "' . $_REQUEST['cat'] . '"';		}
if ($_REQUEST['subcat'])				{	$empat = 'AND fu_ajk_cn.id_regional = "' . $_REQUEST['subcat'] . '"';		}

if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}
$fdn = $database->doQuery('SELECT
fu_ajk_costumer.name AS nmperusahaan,
fu_ajk_polis.nmproduk,
fu_ajk_cn.id,
fu_ajk_cn.id_cn,
fu_ajk_cn.id_dn,
fu_ajk_cn.id_peserta,
fu_ajk_cn.id_regional,
fu_ajk_cn.id_cabang,
fu_ajk_cn.tgl_createcn,
fu_ajk_cn.tgl_byr_claim,
fu_ajk_cn.type_claim,
fu_ajk_cn.total_claim,
fu_ajk_dn.dn_kode,
fu_ajk_dn.tgl_createdn,
fu_ajk_peserta.id_peserta,
fu_ajk_peserta.nama,
fu_ajk_peserta.totalpremi,
fu_ajk_asuransi.name AS nmasuransi
FROM
fu_ajk_cn
Inner Join fu_ajk_costumer ON fu_ajk_cn.id_cost = fu_ajk_costumer.id
Inner Join fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
Inner Join fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
Left Join fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_dn = fu_ajk_peserta.id_dn AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
Left Join fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
WHERE
fu_ajk_cn.del IS NULL AND
fu_ajk_cn.id !="" '.$satu.' '.$dua.' '.$duaa.' '.$tiga.' '.$empat.' ORDER BY fu_ajk_cn.id_cn DESC LIMIT ' . $m . ' , 25');
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(fu_ajk_cn.id) FROM fu_ajk_cn
												   Inner Join fu_ajk_costumer ON fu_ajk_cn.id_cost = fu_ajk_costumer.id
Inner Join fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
Inner Join fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
Left Join fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_dn = fu_ajk_peserta.id_dn AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
Left Join fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
WHERE fu_ajk_cn.id != "" ' . $satu . ' '.$dua.' '.$duaa.' '.$tiga.' '.$empat.' AND fu_ajk_cn.del IS NULL '));
$totalRows = $totalRows[0];
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while ($rdn = mysql_fetch_array($fdn)) {
$metarm = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_prm WHERE id="'.$rdn['id_prm'].'"'));

if ($rdn['tgl_byr_claim']=="") {	$statusdn = '<blink><font color="red">Unpaid</font></blink>';	}
else	{	$statusdn = '<font color="blue">Paid</font>';	}
	$fee = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id_cost="'.$rdn['id_cost'].'"'));
if (($no % 2) == 1)	$objlass = 'tbl-odd'; else				$objlass = 'tbl-even';

//CN PENDAMPING NOMOR CN//
$mametdn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn WHERE dn_kode="'.$rdn['id_dn'].'"'));
$mametnett = $mametdn['totalpremi'] - $rdn['total_claim'];
//CN PENDAMPING NOMOR CN//

echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	<td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
	<td>'.$rdn['nmperusahaan'].'</td>
	<td>'.$rdn['nmasuransi'].'</td>
	<td align="center"><a href="ajk_prm.php?op=cnassign&id='.$_REQUEST['id'].'&cn='.$rdn['id'].'">'.substr($rdn['id_cn'],3).'</a></td>
	<td align="center">'._convertDate($rdn['tgl_createcn']).'</td>
	<td align="right">'.duit($rdn['total_claim']).'</td>
	<!-- <td align="center">'.$metarm['id_prm'].'</td> -->
	<td align="center">'._convertDate($rdn['tgl_byr_claim']).'</td>
	<td align="center">'.$statusdn.'</td>
	<td>'.substr($rdn['dn_kode'],3).'</td>
	<td align="center">'._convertDate($rdn['tgl_createdn']).'</td>
	<td align="right">'.duit($rdn['totalpremi']).'</td>
	<td align="right"><b>'.duit($rdn['total_claim']).'</b></td>
	<td>'.$rdn['type_claim'].'</td>
	<td>'.$rdn['id_regional'].'</td>
	<td>'.$rdn['id_cabang'].'</td>
	</tr>';
}
	$met = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn WHERE id="'.$_REQUEST['dn'].'"'));
	$totalnya = $met['totalpremi'] + $fee['bpolis'] + $fee['bmaterai'];
echo '</table>
		  </td></tr>';
	echo '<tr><td colspan="22">';
	echo createPageNavigations($file = 'ajk_prm.php?op=cnassign&armstatus='.$_REQUEST['armstatus'].'&cat='.$_REQUEST['cat'].'&subcat='.$_REQUEST['subcat'].'&payreg='.$_REQUEST['payreg'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
	echo '<b>Total Data CN: <u>' . $totalRows . '</u></b></td></tr>';
echo '</table>';
	;
	break;

	case "saveprm":
$_REQUEST['name'] = $_POST['name'];						if (!$_REQUEST['name'])  $error .='<blink><font color=red>PIlih nama perusahaan</font></blink><br>';
$_REQUEST['norek'] = $_POST['norek'];					if (!$_REQUEST['norek'])  $error .='<blink><font color=red>Nomor Rekening tidak boleh kosong</font></blink><br>';
//$_REQUEST['bukti_pembyran'] = $_POST['bukti_pembyran'];	if (!$_REQUEST['bukti_pembyran'])  $error .='<blink><font color=red>Nomor bukti Pembayaran boleh kosong</font></blink><br>';
$_REQUEST['tglbayar'] = $_POST['tglbayar'];				if (!$_REQUEST['tglbayar'])  $error .='<blink><font color=red>Tanggal pembayaran boleh kosong</font></blink><br>';
$_REQUEST['jbayar'] = $_POST['jbayar'];					if (!$_REQUEST['jbayar'])  $error .='<blink><font color=red>Jumlah pembayaran tidak boleh kosong</font></blink><br>';

$ceknoBukti = mysql_fetch_array($database->doQuery('SELECT no_pem, input_by FROM fu_ajk_prm WHERE no_pem="'.$_REQUEST['bukti_pembyran'].'"'));
if ($_REQUEST['bukti_pembyran'] == $ceknoBukti['no_pem']) {	$error .='<blink><font color=red>Nomor '.$_REQUEST['bukti_pembyran'].' sudah pernah di input oleh '.$ceknoBukti['input_by'].' </font></blink>';	}

if ($error)
{	echo '<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border: solid 1px red">
		  <tr><td><table width="100%" class="bgcolor1">
		  <tr><td align="left"><img src="image/warning.gif" border="0"></td>
			  <td align="center"><font class="option"><blink>'.$error.'</blink></td>
			  <td align="right"><img src="image/warning.gif" border="0"></td>
		  </tr>
		  </table></td></tr>
		  </table><meta http-equiv="refresh" content="2; url=ajk_prm.php?op=tambah">';
}
else
{
$prm = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_prm ORDER BY id DESC'));
$metid = explode(" ",$prm['input_date']);	$metidthn = explode("-",$metid[0]);
if ($metidthn[0] < $futglidprm) {	$metauto = 1;	} else{	$metauto = $prm['id_auto'] + 1;	}

$idnya = 100000000 + $metauto;
$idprm2 = substr($idnya,1);
$prmtgl = explode("-", $futglprm);
$idprm = 'PRM-AJK-'.$prmtgl[0].'-'.$prmtgl[1].'-'.$idprm2;

$a = $database->doQuery('INSERT INTO fu_ajk_prm SET id_prm = "'.$idprm.'",
									 id_auto = "'.$metauto.'",
									 id_cost = "'.$_REQUEST['name'].'",
									 norek = "'.$_REQUEST['norek'].'",
									 jumlah = "'.$_REQUEST['jbayar'].'",
									 no_pem = "'.$_REQUEST['bukti_pembyran'].'",
									 tgl_pem = "'.$_REQUEST['tglbayar'].'",
									 input_by = "'.$_SESSION['nm_user'].'",
									 input_date = "'.$futgl.'"');
echo '<table border="0" width="55%" cellpadding="3" cellspacing="1" style="border: solid 1px #DEDEDE" align="center">
	  <tr><td align="center">Nomor PRM <b>'.$idprm.'</b> telah berhasil di buat</td></tr></table>
	  <meta http-equiv="refresh" content="3;URL=ajk_prm.php">';
}
	;
	break;

 	case "tambah":
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
		<tr><th width="100%" align="left">Modul PRM - Tambah Data PRM</font></th><th width="5%"><a href="ajk_prm.php"><img src="image/Backward-64.png" width="20"></a></th></tr>
		</table><br />';
echo '<form method="post" id="formCheck" action="ajk_prm.php?op=saveprm">
		<table border="0" width="55%" cellpadding="3" cellspacing="1" style="border: solid 1px #DEDEDE" align="center">
		<tr><td width="25%" bgcolor="#DEDEDE">Nama Perusahaan</td><td colspan="3">: ';
$quer2=$database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name');
echo '<select name="name"><option value="">Pilih Perusahaan</option>';
while($noticia2 = mysql_fetch_array($quer2)) {
echo  '<option value="'.$noticia2['id'].'"'._selected($_REQUEST['name'], $noticia2['name']).'>'.$noticia2['name'].'</option>';
}
echo '</select><font color="red"> *</font></td></tr>
	  <tr><td bgcolor="#DEDEDE">No. Rekening</td><td>: <input type="text" name="norek" value="'.$_REQUEST['norek'].'"><font color="red"> *</font></td></tr>
	  <tr><td bgcolor="#DEDEDE">Tanggal Pembayaran</td><td>: ';print initCalendar();	print calendarBox('tglbayar', 'triger', $_REQUEST['tglbayar']);
echo '<font color="red"> *</font></td></tr>
	  <tr><td bgcolor="#DEDEDE">No. Bukti Pembayaran</td><td>: <input type="text" name="bukti_pembyran" value="'.$_REQUEST['bukti_pembyran'].'"></td></tr>
	  <tr><td bgcolor="#DEDEDE">Jumlah Pembayaran</td><td>: <input type="text" name="jbayar" value="'.$_REQUEST['jbayar'].'"><font color="red"> *</font></td>
	  </tr>
	  <tr><td colspan="5" align="center"><input type="submit" id="tombolCheck" name="oop" value="Save"></td></tr></table></form>';
		;
		break;

	case "setdn":
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
<tr><th width="100%" align="left">Modul Payment Register - DN Assignment</font></th><th width="5%"><a href="ajk_prm.php"><img src="image/Backward-64.png" width="20"></a></th></tr>
</table>';

$prm = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_prm WHERE id="'.$_REQUEST['id'].'"'));
$metdn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn WHERE id="'.$_REQUEST['iddn'].'"'));
$cost = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$prm['id_cost'].'"'));

if ($_REQUEST['ope']=="Paid") 	{
$cekdnnya = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn WHERE id="'.$_REQUEST['iddn'].'"'));
if ($_REQUEST['ttlDN'] < $cekdnnya['totalpremi']) {
	$statusdnbaru = 'paid(*)';
	$dnbayar = $cekdnnya['totalpremi'] - $_REQUEST['ttlDN'];	$dndibayar = $_REQUEST['ttlDN'];
	}else{
	$statusdnbaru = 'paid';
	$dndibayar = $cekdnnya['totalpremi'];
	}
	$r = $database->doQuery('UPDATE fu_ajk_dn SET id_prm="'.$_REQUEST['id'].'",
												  dn_status="'.$statusdnbaru.'",
												  dn_total="'.$dndibayar.'",
												  tgl_dn_paid="'.$prm['tgl_pem'].'",
												  update_by="'.$q['nm_lengkap'].'",
												  update_time="'.$futgl.'"
												  WHERE id="'.$_REQUEST['iddn'].'" ');
$p = $database->doQuery('UPDATE fu_ajk_peserta SET status_bayar=1 WHERE id_dn="'.$metdn['dn_kode'].'"');
header("location:ajk_prm.php?op=setdn&id=".$_REQUEST['id']."");
}

if ($_REQUEST['ope']=="Paid2") 	{
$starpaid = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn WHERE id="'.$_REQUEST['iddn'].'"'));
$dnsisanya = $starpaid['totalpremi'] - $starpaid['dn_total'];
$dnstarnya =$starpaid['dn_total'] + $dnsisanya;
$r = $database->doQuery('UPDATE fu_ajk_dn SET id_prm="'.$starpaid['id_prm'].', '.$_REQUEST['id'].'",
												  dn_status="paid",
												  dn_total="'.$dnstarnya.'",
												  tgl_dn_paid="'.$prm['tgl_pem'].'",
												  update_by="'.$q['nm_lengkap'].'",
												  update_time="'.$futgl.'"
												  WHERE id="'.$_REQUEST['iddn'].'" ');
header("location:ajk_prm.php?op=setdn&id=".$_REQUEST['id']."");
}

if ($_REQUEST['ope']=="UnPaid") {
	$unr = $database->doQuery('UPDATE fu_ajk_dn SET id_prm="", dn_status="unpaid", dn_total="", tgl_dn_paid="", update_by="'.$q['nm_lengkap'].'", update_time="'.$futgl.'" WHERE id="'.$_REQUEST['iddn'].'" ');
	$p = $database->doQuery('UPDATE fu_ajk_peserta SET status_bayar=0 WHERE id_dn="'.$metdn['dn_kode'].'"');
	header("location:ajk_prm.php?op=setdn&id=".$_REQUEST['id']."");
}

//PENGURANGAN TOTAL DN//
$metdn = $database->doQuery('SELECT * FROM fu_ajk_dn WHERE id_prm="'.$_REQUEST['id'].'"');
while ($mDN = mysql_fetch_array($metdn))	{	$jdnPRM += $mDN['totalpremi'];	}	$aDN =$jdnPRM;
//PENGURANGAN TOTAL DN//

//PENGURANGAN TOTAL CN
$metcn = $database->doQuery('SELECT * FROM fu_ajk_cn WHERE id_prm="'.$_REQUEST['id'].'"');
while ($mCN = mysql_fetch_array($metcn))	{	$jcnPRM += $mCN['total_claim'];	}	$aCN =$jcnPRM;
//PENGURANGAN TOTAL CN
$totalPRMnya =$prm['jumlah'] - $aDN + $aCN;		//TOTAL JUMLAH PRM

echo '<form method="post" id="formCheck" action="ajk_prm.php?op=setdn&id='.$_REQUEST['id'].'">
	  <table border="0" width="100%" cellpadding="5" cellspacing="1">
	  <inputt type="text" name="id_prm" value='.$_REQUEST['id'].'>
	  <tr bgcolor="#bde0e6"><td width="15%">Company Name</td><td>: <b>'.$cost['name'].'</b></td></tr>
	  <tr bgcolor="#bde0e6"><td>Reg. PRM</td><td>: <b>'.$prm['id_prm'].'</b></td></tr>
	  <tr bgcolor="#bde0e6"><td>Amount</td><td>: '.duit($prm['jumlah']).'</td></tr>
	  <tr bgcolor="#bde0e6"><td>Used Payment Debit Note</td><td>: '.duit($aDN).'</td></tr>
	  <tr bgcolor="#bde0e6"><td>Used Payment Credit Note</td><td>: '.duit($aCN).'</td></tr>
	  <tr bgcolor="#bde0e6"><td>Remaining Payment</td><td>: <font color="red">'.duit($totalPRMnya).'</font></td></tr>
	  </table>';
echo '<table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#bde0e6">
	  <tr><th width="45%">Unpaid</th>
	  	  <th width="5%"><a href="ajk_confirmed.php?r=setcnprm&id='.$_REQUEST['id'].'" onclick="NewWindow(this.href,\'name\',\'1024\',\'500\',\'no\');return false">CN</a></th>
		  <th width="50%" colspan="6">Paid</th></tr>
	  <tr><td colspan="2" align="center" bgcolor="#666"><font color="#fff">Pencarian DN Unpaid : <input type="text" name="caridnunpaid" value="'.$_REQUEST['caridnunpaid'].'">&nbsp; &nbsp;
									   Cabang : </font><input type="text" name="caricabang" value="'.$_REQUEST['caricabang'].'">
									<input type="submit" name="button" value="Cari" class="button"></td>
		  <td align="center" bgcolor="#666"><font color="#fff">Pencarian DN Paid :  </font><input type="text" name="caridnpaid" value="'.$_REQUEST['caridnpaid'].'">&nbsp;
									<input type="submit" name="button" value="Cari" class="button"></td>
	  </tr>
	  <tr><td valign="top" colspan="2">
	  		<table border="0" width="100%" cellpadding="5" cellspacing="1" bgcolor="#bde0e6">
	  		<tr><th width="2%">No</th>
	  			<th>DN Number</th>
	  			<th width="15%">Date Create</th>
	  			<th width="10%">Total</th>
	  			<th width="5%">Status</th>
	  			<th width="15%">Branch</th>
	  			<th width="10%">Regional</th>
	  			<th width="10%">PRM</th>
	  		</tr>';
if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}
else {	$m = 0;		}

if ($_REQUEST['caridnunpaid'])		{	$dnunpaid = 'AND dn_kode LIKE "%' . $_REQUEST['caridnunpaid'] . '%"';		}
if ($_REQUEST['caricabang'])		{	$cbunpaid = 'AND id_cabang LIKE "%' . $_REQUEST['caricabang'] . '%"';		}
$rdn = $database->doQuery('SELECT * FROM fu_ajk_dn WHERE id_cost="'.$prm['id_cost'].'" AND dn_status != "paid" '.$dnunpaid.' '.$cbunpaid.' ORDER BY input_time DESC LIMIT ' . $m . ' , 25');
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_dn WHERE id != "" AND id_cost="'.$prm['id_cost'].'" AND dn_status != "paid" '.$dnunpaid.' '.$cbunpaid.' '));
$totalRows = $totalRows[0];
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;

while ($fdn = mysql_fetch_array($rdn)) {
if (duit($totalPRMnya) == 0) {	$setprmnya = '<b>Closed</b>';	}
elseif($fdn['dn_status']=="paid(*)")	{	$setprmnya = '<a href="ajk_prm.php?op=setdn&ope=Paid2&id='.$_REQUEST['id'].'&iddn='.$fdn['id'].'&ttlDN='.$totalPRMnya.'">set prm(*)</a>';	}
else	{	$setprmnya = '<a href="ajk_prm.php?op=setdn&ope=Paid&id='.$_REQUEST['id'].'&iddn='.$fdn['id'].'&ttlDN='.$totalPRMnya.'">set prm</a>';	}

	//CEK FORMAT TANGGAL
	$findmet="/";
	$fpos = stripos($fdn['tgl_createdn'], $findmet);
if ($fpos === false) {	$riweuh = explode("-", $fdn['tgl_createdn']);	$cektglnya = $riweuh[0].'/'.$riweuh[1].'/'.$riweuh[2];							// FORMULA TANGGAL
}	else	{	$riweuh = explode("/", $fdn['tgl_createdn']);			$cektglnya = $riweuh[0].'/'.$riweuh[1].'/'.$riweuh[2];							// FORMULA TANGGAL
}
	//CEK FORMAT TANGGAL

if ($fdn['id_cabang']=="") {	$metprmcabang = $fdn['id_cabang_old'];	}	else	{	$metprmcabang = $fdn['id_cabang'];	}

if ($fdn['dn_status']=="") {	$statusdnnya = "unpaid";	}
else	{	$statusdnnya = '<a href="ajk_prm.php?op=setdn&ope=UnPaid&id='.$_REQUEST['id'].'&iddn='.$fdn['id'].'">'.$fdn['dn_status'].'</a>';	}
if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		<td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
		<td align="center">'.$fdn['dn_kode'].'</td>
		<td align="center">'.$cektglnya.'</td>
		<td align="right">'.duit($fdn['totalpremi']).'</td>
		<td align="center"><b><font color="red">'.$fdn['dn_status'].'</font></b></td>
		<td>'.$metprmcabang.'</td>
		<td>'.$fdn['id_regional'].'</td>
		<td align="center">'.$setprmnya.'</td>
		</tr>';
}
$totalRowsDNunpaid = $totalRows;		//jumlah nomor DN UNPAID
echo '<tr><td colspan="22">';
echo createPageNavigations($file = 'ajk_prm.php?op=setdn&id='.$_REQUEST['id'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
echo '<b>Total Data DN Unpaid: <u>' . $totalRows . '</u></b></td></tr>';
echo '</table>
	  </td>
	  <td valign="top">
  	  		<table border="0" width="100%" cellpadding="5" cellspacing="1" bgcolor="#bde0e6">
	  		<tr><th width="2%">No</th>
	  			<th>DN Number</th>
	  			<th width="10%">Date Paid</th>
	  			<th width="10%">Total</th>
	  			<th width="15%">Branch</th>
	  			<th width="15%">Regional</th>
	  			<th width="5%">Opt</th>
	  		</tr>';
if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}
else {	$m = 0;		}
if ($_REQUEST['caridnpaid'])		{	$dnpaid = 'AND dn_kode LIKE "%' . $_REQUEST['caridnpaid'] . '%"';		}
$rdnpaid = $database->doQuery('SELECT * FROM fu_ajk_dn WHERE id_cost="'.$prm['id_cost'].'" AND id_prm = "'.$prm['id'].'" AND dn_status="paid" '.$dnpaid.' ORDER BY tgl_createdn DESC LIMIT ' . $m . ' , 25');
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_dn WHERE id != "" AND id_cost="'.$prm['id_cost'].'" AND id_prm = "'.$prm['id'].'" AND dn_status="paid" '.$dnpaid.' '));
$totalRows = $totalRows[0];
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;

$totalRowsDNpaid = $totalRows;																//jumlah nomor DN PAID
if ($totalRowsDNunpaid >= 25) { $nourutan = 25;	}else{$nourutan = $totalRowsDNunpaid;}		//jumlah nomor DN PAID

while ($fdnpaid = mysql_fetch_array($rdnpaid)) {
if ($fdnpaid['id_cabang']=="") {	$branchnya = $fdnpaid['id_cabang_old'];	}
else	{	$branchnya = $fdnpaid['id_cabang'];	}

if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		<td align="center">'.(++$no + ($pageNow-1) * 25 - $nourutan).'</td>
		<td align="center">'.$fdnpaid['dn_kode'].'</td>
		<td align="center">'.$fdnpaid['tgl_dn_paid'].'</td>
		<td align="right">'.duit($fdnpaid['totalpremi']).'</td>
		<td>'.$branchnya.'</td>
		<td>'.$fdnpaid['id_regional'].'</td>
		<td align="center"><a href="ajk_prm.php?op=setdn&ope=UnPaid&id='.$_REQUEST['id'].'&iddn='.$fdnpaid['id'].'">Cancel</a></td>
		</tr>';
}
$totalRowsDNpaid = $totalRows;		//jumlah nomor DN PAID
		echo '<tr><td colspan="22">';
		echo createPageNavigations($file = 'ajk_prm.php?op=setdn&id='.$_REQUEST['id'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
		echo '<b>Total Data DN Paid: <u>' . $totalRows . '</u></b></td></tr>';
//CANCEL DN//
echo '	<tr><td colspan="6">';
if ($_REQUEST['ope']=="cncancel") {
$met = $database->doQuery('UPDATE fu_ajk_cn SET id_prm="", tgl_byr_claim="" WHERE id="'.$_REQUEST['idcn'].'"');
header("location:ajk_prm.php?op=setdn&id=".$_REQUEST['id']."");
}
echo '</td></tr>';
//CANCEL DN//

echo '<tr><td align="center" colspan="7" bgcolor="#666"><font color="#fff">Pencarian CN Paid :  </font><input type="text" name="caricnpaid" value="'.$_REQUEST['caricnpaid'].'">&nbsp;
									<input type="submit" name="button" value="Cari" class="button"></td>
		</tr>
		<tr><th width="2%">No</th>
		  <th>CN Number</th>
		  <th width="15%">Date Paid</th>
		  <th width="10%">Total</th>
		  <th width="15%">Branch</th>
		  <th width="10%">Regional</th>
		  <th width="2%">Opt</th>
	  </tr>';
if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}
else {	$m = 0;		}

if ($_REQUEST['caricnpaid'])		{	$cnunpaid = 'AND id_cn LIKE "%' . $_REQUEST['caricnpaid'] . '%"';		}

$rCNpaid = $database->doQuery('SELECT * FROM fu_ajk_cn WHERE id_cost="'.$prm['id_cost'].'" AND id_prm = "'.$prm['id'].'" '.$cnunpaid.' ORDER BY tgl_createcn DESC LIMIT ' . $m . ' , 25');
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_cn WHERE id != "" AND id_cost="'.$prm['id_cost'].'" AND id_prm = "'.$prm['id'].'" '.$cnunpaid.' '));
$totalRows = $totalRows[0];
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;

$totRowsDNpaid = $totalRowsDNpaid;																//jumlah nomor DN PAID
if ($totRowsDNpaid >= 25) { $nourutancn = 25;	}else{$nourutancn = 25 + $totalRowsDNpaid;}		//jumlah nomor DN PAID

while ($fCNpaid = mysql_fetch_array($rCNpaid)) {
if ($fCNpaid['id_cabang']=="") {	$branchnya = $fCNpaid['id_cabang_old'];	}
else	{	$branchnya = $fCNpaid['id_cabang'];	}

if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		<td align="center">'.(++$no + ($pageNow-1) * 25 - $nourutancn).'</td>
		<td align="center">'.$fCNpaid['id_cn'].'</td>
		<td align="center">'._convertDate($fCNpaid['tgl_byr_claim']).'</td>
		<td align="right">'.duit($fCNpaid['total_claim']).'</td>
		<td>'.$branchnya.'</td>
		<td>'.$fCNpaid['id_regional'].'</td>
		<td><a href="ajk_prm.php?op=setdn&ope=cncancel&id='.$_REQUEST['id'].'&idcn='.$fCNpaid['id'].'">Cancel</a></td>
		</tr>';
}
		echo '<tr><td colspan="22">';
		echo createPageNavigations($file = 'ajk_prm.php?op=setdn&id='.$_REQUEST['id'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
		echo '<b>Total Data CN Paid: <u>' . $totalRows . '</u></b></td></tr>';
echo '</table>
	  </td></tr>';
echo '</form></table>';

	;
	break;

	case "paid":
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
		<tr><th width="100%" align="left">Modul PRM - Paid PRM</font></th><th width="5%"><a href="ajk_prm.php"><img src="image/Backward-64.png" width="20"></a></th></tr>
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
	  <tr><td colspan="2" width="10%">Nomor Polis</td><td colspan="10">: <b>'.$polis['nopol'].'</b></td></tr>
	  <tr><td colspan="2" width="10%">PRM Number</td><td>: <b>'.$rpm['id_prm'].'</b></td>
			<td width="5%">Amount : </td><td width="10%"><b>'.duit($rpm['jumlah']).'</b></td>
			<td width="4%">Paid : </td><td width="10%"><b>'.duit($rpm['terbayar']).'</b></td>
			<td width="11%">Remaining Payment : </td><td width="10%"><b>'.$remainpayed.'</b></td>
			</tr>
	  </table>';
$cekdn = $database->doQuery('SELECT * FROM fu_ajk_dn WHERE id_prm="'.$_REQUEST['id'].'"');
echo '<table border="0" cellpadding="5" cellspacing="1" width="100%" bgcolor="#bde0e6">
	  <tr><th width="1%">No</th>
	  	  <th>Debit Note Number</th>
	  	  <th width="10%">Total Premi</th>
	  	  <th width="10%">Create Date DN</th>
	  	  <th width="10%">DN Paid</th>
	  	  <th width="10%">Paid Date DN</th>
  	  	  <th width="10%">Regional</th>
  	  	  <th width="10%">Cabang</th>';
while ($metdn = mysql_fetch_array($cekdn)) {
if (($no % 2) == 1)	$objlass = 'tbl-odd'; else				$objlass = 'tbl-even';
if ($metdn['dn_status']=="") {	$statdn = '<blink><font Color="red">Unpiad</font></blink>';	}
else	{	$statdn = '<font Color="blue">Paid</font>';	}
echo '<tr class="'.rowClass(++$i).'">
	  <td align="center">'.++$no.'</td>
	  <td align="center">'.$metdn['dn_kode'].'</td>
	  <td align="right">'.duit($metdn['totalpremi']).'</td>
	  <td align="center">'._convertDate($metdn['tgl_createdn']).'</td>
	  <td align="center">'.$statdn.'</td>
	  <td align="center">'._convertDate($metdn['tgl_dn_paid']).'</td>
	  <td>'.$metdn['id_regional'].'</td>
	  <td>'.$metdn['id_cabang'].'</td>
	  </tr>';
		}
echo '</table>';
$cekcn = $database->doQuery('SELECT * FROM fu_ajk_cn WHERE id_prm="'.$_REQUEST['id'].'"');
$cekcn2 = mysql_num_rows($database->doQuery('SELECT * FROM fu_ajk_cn WHERE id_prm="'.$_REQUEST['id'].'"'));
if ($cekcn2 > 0) {
echo '<table border="0" cellpadding="5" cellspacing="1" width="100%" bgcolor="#bde0e6">
	  <tr><th width="1%">No</th>
	  	  <th>Credit Note Number</th>
	  	  <th width="10%">Total</th>
	  	  <th width="10%">Create Date CN</th>
	  	  <th width="10%">CN Paid</th>
	  	  <th width="10%">Paid Date CN</th>
  	  	  <th width="10%">Regional</th>
  	  	  <th width="10%">Cabang</th>';
while ($metcn = mysql_fetch_array($cekcn)) {
if (($no % 2) == 1)	$objlass = 'tbl-odd'; else				$objlass = 'tbl-even';
if ($metcn['tgl_byr_claim']=="") {	$statcn = '<blink><font Color="red">Unpiad</font></blink>';	}
else	{	$statcn = '<font Color="blue">Paid</font>';	}
echo '<tr class="'.rowClass(++$i).'">
	  <td align="center">'.++$no2.'</td>
	  <td align="center">'.$metcn['id_cn'].'</td>
	  <td align="right">'.duit($metcn['total_claim']).'</td>
	  <td align="center">'._convertDate($metcn['tgl_createcn']).'</td>
	  <td align="center">'.$statcn.'</td>
	  <td align="center">'._convertDate($metcn['tgl_byr_claim']).'</td>
	  <td>'.$metcn['id_regional'].'</td>
	  <td>'.$metcn['id_cabang'].'</td>
	  </tr>';
	}
echo '</table>';
}else{	echo '';	}
	;
	break;

	case "editprm":
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
		<tr><th width="100%" align="left">Modul PRM - Edit Data PRM</font></th><th width="5%"><a href="ajk_prm.php"><img src="image/Backward-64.png" width="20"></a></th></tr>
		</table>';
if ($_REQUEST['ope']=="Save") {
$metprm = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_prm WHERE id="'.$_REQUEST['id'].'"'));
$u=$database->doQuery('UPDATE fu_ajk_prm  SET id_cost="'.$_REQUEST['name'].'",
	  								  		  norek="'.$_REQUEST['norek'].'",
	  								  		  jumlah="'.$_REQUEST['jumlah'].'",
									  		  tgl_pem="'.$_REQUEST['tgl_pem'].'",
									  		  no_pem="'.$_REQUEST['no_pem'].'"
									  		  WHERE id="'.$_REQUEST['id'].'"');
echo '<div align="center">Data PRM <b>'.$metprm['id_prm'].'</b> telah selesai di edit.</div>
	  <meta http-equiv="refresh" content="2; url=ajk_prm.php">';
}
echo '<form method="post" action="ajk_prm.php?op=editprm">
		<input type="hidden" name="id" value="'.$_REQUEST['id'].'">
		<table border="0" width="55%" cellpadding="3" cellspacing="1" style="border: solid 1px #DEDEDE" align="center">
		<tr><td width="25%">Nama Perusahaan</td><td colspan="3">: ';
$metprm = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_prm WHERE id="'.$_REQUEST['id'].'"'));
$quer2=$database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name ASC');
echo '<select name="name"><option value="">Pilih Perusahaan</option>';
while($noticia2 = mysql_fetch_array($quer2)) {
	echo  '<option value="'.$noticia2['id'].'"'._selected($noticia2['name'], $noticia2['name']).'>'.$noticia2['name'].'</option>';
}
echo '</select></td></tr>
	  <tr><td>No. Rekening</td><td>: <input type="text" name="norek" value="'.$metprm['norek'].'"</td></tr>
	  <tr><td>Tanggal Pembayaran</td><td>: ';print initCalendar();	print calendarBox('tgl_pem', 'triger', $metprm['tgl_pem']);
echo '</td></tr>
	  <tr><td>No. Bukti Pembayaran</td><td>: <input type="text" name="no_pem" value="'.$metprm['no_pem'].'"</td></tr>
	  <tr><td>Jumlah Pembayaran</td><td>: <input type="text" name="jumlah" value="'.$metprm['jumlah'].'"></td></tr>
	  <tr><td colspan="5" align="center"><input type="submit" name="ope" value="Save"></td></tr></table></form>';
			;
			break;

	case "hapusprm":
$u=$database->doQuery('UPDATE fu_ajk_prm SET del="1",
	  								  		 update_by="'.$q['nm_user'].'",
									  		 update_time="'.$futgl.'"
									  		 WHERE id="'.$_REQUEST['id'].'"');
echo '<center>Data nomor PRM telah di hapus</center><meta http-equiv="refresh" content="2; url=ajk_prm.php">';
		;
		break;

	default:
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
      <tr><th width="100%" align="left">Modul Payment Register</font></th><th width="5%"><a href="ajk_prm.php?op=tambah">Tambah</a></th></tr>
      </table>
 	<form method="post" action="">
	<table border="0" cellpadding="3" cellspacing="0" width="100%">
	<tr><td width="10%">Client</td><td colspan="4">: ';
$met = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name ASC');
echo '<select name="metclient"><option value="">---Select Client---</option>';
while($mamet = mysql_fetch_array($met)) {	echo  '<option value='.$mamet['id'].'>'.$mamet['name'].'</option>';	}
echo '</td></tr>
	<tr><td>Payment Number</td><td width="12%">: <input type="text" name="cprm" value='.$_REQUEST['cprm'].'></td>
		<td width="10%" align="right">Alocation Payment</td><td width="3%">: <input type="checkbox" name="alo"></td>
		<td><input type="submit" name="button" value="Search" class="button"> &nbsp; <a href="ajk_prm.php">Default</a></td>
	</tr>
		</form></table>
      <table border="0" width="100%" cellpadding="3" cellspacing="1" bgcolor="#81BCF0">
	  <tr><th width="1%">No</th>
		  <th width="12%">No.PRM</th>
		  <th>Nama Perusahaan</th>
		  <th width="9%">No. Rekening</th>
		  <th width="8%">Tanggal Pembayaran</th>
		  <th width="5%">No. BUkti Pembayaran</th>
		  <th width="8%">Amount</th>
		  <th width="8%">Used Payment</th>
		  <th width="8%">Remaining Payment</th>
		  <th width="5%">Jumlah DN</th>
		  <th width="5%">Jumlah CN</th>
		  <th width="8%">Option</th>
	  </tr>';
if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 50;	}
else {	$m = 0;		}
if ($_REQUEST['cprm'])		{	$satu = 'AND id_prm LIKE "%' . $_REQUEST['cprm'] . '%"';		}
if ($_REQUEST['metclient'])	{	$dua = 'AND id_cost LIKE "%' . $_REQUEST['metclient'] . '%"';	}

if ($_REQUEST['alo'])		{	$tiga = 'AND terbayar <=0';	}

$prm = $database->doQuery('SELECT * FROM fu_ajk_prm WHERE id!="" '.$satu.' '.$dua.' '.$tiga.' AND del IS NULL ORDER BY id_prm DESC LIMIT ' . $m . ' , 50');
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_prm WHERE id!="" '.$satu.' '.$dua.' '.$tiga.' AND del IS NULL '));
$totalRows = $totalRows[0];
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;

while ($rprm = mysql_fetch_array($prm)) {
$comp = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$rprm['id_cost'].'"'));

// JUMLAH DATA DN
$metdn = $database->doQuery('SELECT * FROM fu_ajk_dn WHERE id_prm = "'.$rprm['id'].'" ');
$fmet = mysql_num_rows($metdn);
while ($mDN = mysql_fetch_array($metdn))	{	$jdnPRM += $mDN['totalpremi'];	}	$aDN =$jdnPRM;

// JUMLAH DATA CN (BILA ADA)
$metcn = $database->doQuery('SELECT * FROM fu_ajk_cn WHERE id_prm="'.$rprm['id'].'"');
$fmetca = mysql_num_rows($metcn);
while ($mCN = mysql_fetch_array($metcn))	{	$jcnPRM += $mCN['total_claim'];	}	$aCN =$jcnPRM;

// CEK TERBAYAR DAN SELISIH
$remainpay = $rprm['jumlah'] - $rprm['terbayar'];
if ($rprm['jumlah']!=$remainpay) {	$selisih = '<font color="blue"></blink>'.duit($remainpay).'</blink></font>';	}
else	{	$selisih = '<font color="blue">'.duit($remainpay).'</font>';	}

if ($rprm['terbayar']<0) {$usedpayed = '<font color="red"></blink>'.duit($rprm['terbayar']).'</blink></font>';	}
else	{	$usedpayed = ''.duit($rprm['terbayar']).'';	}

if (($no % 2) == 1)	$objlass = 'tbl-odd'; else				$objlass = 'tbl-even';

if ($rprm['terbayar']==0) {	$delprm = '<a href="ajk_prm.php?op=hapusprm&id='.$rprm['id'].'" title="hapus '.$rprm['id_prm'].'" onClick="if(confirm(\'Anda yakin akan menghapus data nomor PRM ini ?\')){return true;}{return false;}"><img src="image/deleted.png" width="21">';	}
else	{	$delprm ='';	}
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	  <td align="center">'.(++$no + ($pageNow-1) * 50).'</td>
	  <td align="center"><a href="ajk_prm.php?op=setdn&id='.$rprm['id'].'">'.$rprm['id_prm'].'</a></td>
	  <td>'.$comp['name'].'</td>
	  <td align="center">'.$rprm['norek'].'</td>
	  <td align="center">'._convertDate($rprm['tgl_pem']).'</td>
	  <td align="center">'.$rprm['no_pem'].'</td>
	  <td align="right">'.duit($rprm['jumlah']).'</td>
	  <td align="right">'.$usedpayed.'</td>
	  <td align="right">'.$selisih.'</td>
	  <td align="center"><b><a href="ajk_prm.php?op=paid&id='.$rprm['id'].'">'.$fmet.' Data</a></b></td>
	  <td align="center"><b><a href="ajk_prm.php?op=paid&id='.$rprm['id'].'">'.$fmetca.' Data</a></b></td>
	  <td> &nbsp; <a href="ajk_prm.php?op=editprm&id='.$rprm['id'].'"title="edit '.$rprm['id_prm'].'"><img src="image/edit3.png" width="21"></a> &nbsp;
	  					 <a href="ajk_report_fu.php?fu=pri&id='.$rprm['id'].'" title="print '.$rprm['id_prm'].'"  target="_blank"><img src="image/print.png" width="21"> &nbsp;
	  					 '.$delprm.'</td>
	  </tr>';
}
echo '<tr><td colspan="22">';
echo createPageNavigations($file = 'ajk_prm.php?metclient='.$_REQUEST['metclient'].'&alo='.$_REQUEST['alo'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 50);
echo '<b>Total Data Peserta: <u>' . $totalRows . '</u></b></td></tr>';
echo '</table>';
	;
} // switch
?>
<SCRIPT language=JavaScript>
function reload(form)
{
	var val=form.cat.options[form.cat.options.selectedIndex].value;
	self.location='ajk_prm.php?op=dnassign&cat=' + val;
}
</script>

<SCRIPT language=JavaScript>
function reloadcn(form)
{
	var val=form.cat.options[form.cat.options.selectedIndex].value;
	self.location='ajk_prm.php?op=cnassign&cat=' + val;
}
</script>