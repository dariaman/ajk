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
switch ($_REQUEST['v']) {
	case "a":
		;
		break;

	case "viewdn":
echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
	  <tr><th width="80%" align="left" colspan="2">Modul Debit Note / Credit Note</font></th>
	  <tr><td>
	  <tr><td colspan="2" align="center"><fieldset style="padding: 2">
<form method="POST" action="">
	<fieldset style="padding: 2">
	<legend>Searching</legend>
	<table border="0" width="30%" align="center">
		<tr><td width="20%">Nomor DN</td><td>: <input type="text" name="dns" value="'.$_REQUEST['dns'].'"> s/d <input type="text" name="dne" value="'.$_REQUEST['dne'].'">';
echo '</td></tr>
			<tr><td>Payment Status</td>
				<td>: <select id="rstat" name="rstat">
						<option value="">--- Pilih Status ---</option>
						<option value="paid">Paid</option>
						<option value="unpaid">Unpaid</option>
				</select></td>
						</tr>
						<tr><td colspan="6" align="center"><input type="submit" name="button" name="carieuy" value="Cari" class="button"></td></tr>';

if ($_REQUEST['dns'] > $_REQUEST['dne'] OR $_REQUEST['rdns'] > $_REQUEST['rdne'])
{	$valcari = '<font color="red"><center>Data DN <b>'.$_REQUEST['dns'].'</b> lebih besar dari data <b>'.$_REQUEST['dne'].'</b>, Pencarian ditolak.!</center></font>';	}
elseif ($_REQUEST['dns'] < $_REQUEST['dne'] OR $_REQUEST['rdns'] < $_REQUEST['rdne']){
	$valcari = '<a href="ajk_report_fu.php?fu=exlAll&rdns='.$_REQUEST['rdns'].'&rdne='.$_REQUEST['rdne'].'&rpays='.$_REQUEST['rpays'].'&rpaye='.$_REQUEST['rpaye'].'&rreg='.$_REQUEST['rreg'].'&rcabang='.$_REQUEST['rcabang'].'&rdnno='.$_REQUEST['rdnno'].'&rstat='.$_REQUEST['rstat'].'&dns='.$_REQUEST['dns'].'&dne='.$_REQUEST['dne'].'&" target="_blank"><img src="image/excel.png" width="30"></a> &nbsp; | &nbsp;
				<a href="ajk_report_fu.php?fu=ajkpdfAll&rdns='.$_REQUEST['rdns'].'&rdne='.$_REQUEST['rdne'].'&rpays='.$_REQUEST['rpays'].'&rpaye='.$_REQUEST['rpaye'].'&rreg='.$_REQUEST['rreg'].'&rcabang='.$_REQUEST['rcabang'].'&rdnno='.$_REQUEST['rdnno'].'&rstat='.$_REQUEST['rstat'].'&dns='.$_REQUEST['dns'].'&dne='.$_REQUEST['dne'].'&"><img src="image/print.png" width="30"></a>';
}
else{	$valcari ='';	}
echo '<tr><td colspan="6" align="right">'.$valcari.'</td></tr>
					</table>
					</fieldset></form>';
echo '<table border="0" width="100%" cellpadding="5" cellspacing="1" bgcolor="#bde0e6">
			  <tr><th width="3%">No</th>
			  	  <th>No. DN</th>
			  	  <th width="5%">Members</th>
			  	  <th width="5%">Total</th>
			  	  <th width="5%">Tanggal Create DN</th>
			  	  <th width="5%">Tanggal WPC DN</th>
			  	  <th width="1%">Status</th>
			  	  <th width="5%">Due Date</th>
			  	  <th width="10%">Branch</th>
			  	  <th width="9%">Regional</th>
			  	  <th width="12%">No. CN</th>
			  	  <th width="5%">Premi CN</th>
			  	  <th width="5%">Net Premi</th>
			  	  <th width="1%">Option</th>
			  </tr>';

//if ($_REQUEST['rdns']!='' AND $_REQUEST['rdne']!='')	{	$satu= 'AND tgl_createdn BETWEEN \''.$_REQUEST['rdns'].'\' AND \''.$_REQUEST['rdne'].'\'';	}
//if ($_REQUEST['rpays']!='' AND $_REQUEST['rpaye']!='')	{	$dua= 'AND tgl_dn_paid BETWEEN \''.$_REQUEST['rpays'].'\' AND \''.$_REQUEST['rpaye'].'\'';	}

if ($_REQUEST['rstat'])									{	$tiga = 'AND dn_status = "'.$_REQUEST['rstat'].'"';	}
//if ($_REQUEST['rreg'])									{	$empat = 'AND id_regional LIKE "%' .  $_REQUEST['rreg'] . '%"';		}
//if ($_REQUEST['rcabang'])								{	$lima = 'AND id_cabang LIKE "%' . $_REQUEST['rcabang'] . '%"';		}
if ($_REQUEST['rdnno'])									{	$enam = 'AND dn_kode LIKE "%' . $_REQUEST['rdnno'] . '%"';		}
if ($_REQUEST['dns']!='' AND $_REQUEST['dne']!='')		{	$tujuh = 'AND dn_kode BETWEEN \''.$_REQUEST['dns'].'\' AND \''.$_REQUEST['dne'].'\'';		}

if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}
else {	$m = 0;		}

		$met = $database->doQuery('SELECT * FROM fu_ajk_dn WHERE id!="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' '.$tujuh.' AND validasi_uw="ya" AND validasi_arm="ya" AND del IS NULL ORDER BY tgl_createdn DESC, id_dn DESC LIMIT ' . $m . ' , 25');
		$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_dn WHERE id != "" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' '.$tujuh.' AND validasi_uw="ya" AND validasi_arm="ya" AND del IS NULL '));
		$totalRows = $totalRows[0];
		$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while ($metdn = mysql_fetch_array($met)) {
if ($metdn['id_cabang']=="") {	$Rcabang = $metdn['id_cabang_old'];	}	else {	$Rcabang = $metdn['id_cabang'];	}
if ($metdn['id_regional']=="") {	$Rregional = $metdn['id_regional_old'];	}	else {	$Rregional = $metdn['id_regional'];	}
if ($metdn['tgl_dn_paid']=="") {	$statusdn = '<blink><font color="red">Unpaid</font></blink>';	}
else	{	$statusdn = '<font color="blue">Paid</font>';	}

	$metpeserta = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_dn="'.$metdn['dn_kode'].'" AND del IS NULL');
	$fupeserta = mysql_num_rows($metpeserta);
while ($idcek = mysql_fetch_array($metpeserta)) {
	if ($idcek['id_peserta']=="") {
		$x = 100000000 + $idcek['id'];	$xx = substr($x, 1);
		$metx = $database->doQuery('UPDATE fu_ajk_peserta SET id_peserta="'.$xx.'" WHERE id_peserta="" AND id="'.$idcek['id'].'"');
	}
}
	//CEK FORMAT TGL DN
	$findmet="/";
	$fpos = stripos($metdn['tgl_createdn'], $findmet);
if ($fpos === false) {
	$riweuh = explode("-", $metdn['tgl_createdn']);
	$cektgldn = $riweuh[2].'-'.$riweuh[1].'-'.$riweuh[0];
}	else	{
	$riweuh = explode("/", $metdn['tgl_createdn']);
	$cektgldn = $riweuh[0].'-'.$riweuh[1].'-'.$riweuh[2];
}

	$tglskrg = explode (" ", $futgl);					$tglsekarang = $tglskrg[0];
	$tglinpt = explode (" ", $metdn['input_time']);		$tglinput	 = $tglinpt[0];
if ($tglinput==$tglsekarang) { $datenow = '<img src="../image/newdn.gif" width="25">';	}else{	$datenow = '';	}

	$tanggalplus=date('Y-m-d',strtotime($cektgldn."+14 day"));

	$dncnnya = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cn WHERE id_dn="'.$metdn['dn_kode'].'"'));
if ($dncnnya['id_dn']==$metdn['dn_kode']) {
	$cnnomor = '<a href="ajk_report_fu.php?fu=ajkpdfcn&id='.$dncnnya['id'].'">'.$dncnnya['id_cn'].'</a>';
	$cnpremi = duit($dncnnya['total_claim']);
}else{
	$cnnomor = '-';
	$cnpremi = '-';
}
	$netpremi = $metdn['totalpremi'] - $dncnnya['total_claim'];
if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		    <td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
		    <td align="center">'.$datenow.' <a href="ajk_dn.php?r=viewmember&id='.$metdn['id'].'">'.$metdn['dn_kode'].'</a></td>
		    <td align="center"><b>'.$fupeserta.' Data</b></td>
		    <td align="right"><b>'.duit($metdn['totalpremi']).'</b></td>
			<td align="center">'.$cektgldn.'</td>
		    <td align="center">'._convertDate($tanggalplus).'</td>
		    <td align="center">'.$statusdn.'</td>
		    <td align="center">'.$metdn['tgl_dn_paid'].'</td>
		    <td>'.$Rcabang.'</td>
		    <td>'.$Rregional.'</td>
		    <td>'.$cnnomor.'</td>
		    <td align="right">'.$cnpremi.'</td>
		    <td align="right"><b>'.duit($netpremi).'</b></td>
		    <td align="center">
		    	<a href="ajk_report_fu.php?fu=ajkpdfinvdn&id='.$metdn['id'].'" title="DN Pdf '.$metdn['dn_kode'].'" target="_blank"><img src="image/dninvoice.png" width="21"></a> &nbsp;
			</td>';
}
		echo '<tr><td colspan="22">';
		echo createPageNavigations($file = 'ajk_dn.php?r=viewdn&rdns='.$_REQUEST['rdns'].'&rdne='.$_REQUEST['rdne'].'&rpays='.$_REQUEST['rpays'].'&rpaye='.$_REQUEST['rpaye'].'&rreg='.$_REQUEST['rreg'].'&rcabang='.$_REQUEST['rcabang'].'&rdnno='.$_REQUEST['rdnno'].'&rstat='.$_REQUEST['rstat'].'&', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
		//echo createPageNavigations($file = 'ajk_dn.php?r=viewdn&rpays='.$_REQUEST['rpays'].'&rpaye='.$_REQUEST['rpaye'].'&rreg='.$_REQUEST['rreg'].'&rcabang='.$_REQUEST['rcabang'].'&rdnno='.$_REQUEST['rdnno'].'&rstat='.$_REQUEST['rstat'].'&dns='.$_REQUEST['rdns'].'&dne='.$_REQUEST['rdne'].'&', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
		echo '<b>Total Data Peserta: <u>' . $totalRows . '</u></b></td></tr>';
		echo '</table>';
		;
		break;

	case "valbatcharm":
echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
	  <tr><th width="80%" align="left" colspan="2">Modul Debit Note - BATCH</font></th><th width="5%"><a href="ajk_ARMvalidasi.php"><img src="image/back.gif"></a></th></tr></table>';
echo '	  <table border="0" width="100%" cellpadding="5" cellspacing="1" bgcolor="#bde0e6">
	  <tr><th width="1%">No</th>
	  	  <th>No Batch</th>
	  	  <th width="10%">Jumlah DN</th>
	  	  <th width="10%">Jumlah CN</th>
	  	  <th width="10%">User</th>
	  	  <th width="10%">Tanggal Batch</th>
<!--  	  <th width="1%">Print</th> -->
	  </tr>';
if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}
$met = $database->doQuery('SELECT * FROM fu_ajk_batch WHERE update_by !="" ORDER BY id DESC LIMIT ' . $m . ' , 25');
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_batch WHERE id != ""'));
$totalRows = $totalRows[0];
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while ($metbatch = mysql_fetch_array($met)) {
	$jumdn = mysql_num_rows($database->doQuery('SELECT * FROM fu_ajk_dn WHERE validasi_batch="'.$metbatch['idb'].'" AND validasi_uw="ya" AND validasi_arm="ya"'));
	$jumcn = mysql_num_rows($database->doQuery('SELECT * FROM fu_ajk_cn WHERE validasi_batchcn="'.$metbatch['idb'].'" AND validasi_cn_uw="ya" AND validasi_cn_arm="ya"'));

	$tglbatch = explode(" ", $metbatch['update_time']);
if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	    <td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
		<td>'.$metbatch['no_batch'].'</td>
		<td align="center">'.$jumdn.'</td>
		<td align="center">'.$jumcn.'</td>
		<td align="center">'.$metbatch['update_by'].'</td>
		<td align="center">'._convertDate($tglbatch[0]).'</td>
<!--	<td align="center"><a href="ajk_report_fu.php?fu=printbatch&userna='.$q['nm_lengkap'].'&id='.$metbatch['id'].'"><img src="image/print1.png" width="25"></a></td> -->
	  </tr>';
}
		echo '</table>';
		;
		break;

	case "okmet":
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
<tr><th width="90%" align="left">Modul Validasi DN</font></th></th></tr>
</table>';
if (!$_REQUEST['validasidn']) {
echo '<center><font color=red><blink>Tidak ada data DN/CN yang di pilih, silahkan ceklist data DN/CN yang akan di validasi. !</blink></font><br/>
	  <a href="ajk_ARMvalidasi.php">Kembali Ke Halaman Validasi DN/CN</a></center>';
}else{
	foreach($_REQUEST['validasidn'] as $k => $val){
	$met = $database->doQuery('UPDATE fu_ajk_dn SET validasi_arm="ya", update_by="'.$q['nm_user'].'", update_time="'.$futgl.'" WHERE id="'.$val.'"');	//UPDATE VALIDASI DN DARI ARM
	$valdn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn WHERE id="'.$val.'"'));				//PILIH DN YANG TELAH DI VALIDASI

	$metbatchpupdate = $database->doQuery('UPDATE fu_ajk_batch SET update_by="'.$q['nm_user'].'", update_time="'.$futgl.'" WHERE idb="'.$valdn['validasi_batch'].'"');	//UPDATE VALIDASI BATCH ARM

	//INFOKAN NOMOR CN BILA TERDAPAT PASANGAN DARI NOMOR DN
	$valdncnnya = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cn WHERE id_dn="'.$valdn['dn_kode'].'"'));
	if ($valdncnnya['id_dn']==$valdn['dn_kode']) {
	$valdncnnyaupdate = $database->doQuery('UPDATE fu_ajk_cn SET validasi_cn_arm="ya", update_by="'.$q['nm_user'].'", update_time="'.$futgl.'" WHERE id_dn="'.$valdn['dn_kode'].'"'); //UPDATE VALIDASI CN DARI ARM
		$fieldcn ='<td width="20%" align="center"><b>Nomor CN</b></td>';
		$fieldisi ='<td>'.$valdncnnya['id_cn'].'</td>';
	}else{
		$fieldcn ='';
		$fieldisi ='';
	}
	//INFOKAN NOMOR CN BILA TERDAPAT PASANGAN DARI NOMOR DN

	$vpeserta = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_dn="'.$valdn['dn_kode'].'" AND id_cost="'.$valdn['id_cost'].'" GROUP BY id_dn');
	$message ='<html><head><title>DN APPROVE</title></head><body>
			 <table border="0" width="70%" cellpadding="1" cellspacing="3">';
	while ($valpeserta = mysql_fetch_array($vpeserta)){
		$setmail = explode("-", $valpeserta['input_by']);
		$accmail = mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$setmail[0].'"'));
		//echo $valpeserta['id_dn'].'<br />';
		//echo $accmail['email'].'<br />';
		$to = "sumiyanto@relife.co.id, arief.kurniawan@relife.co.id, ".$accmail['email']." ";
		$subject = 'Nomor DN '.$valdn['dn_kode'].' telah di Approve oleh ('.$q['nm_lengkap'].')';
		$message .= '<tr><td>To '.$setmail[0].'</td></tr>
					 <tr><td colspan="5"><br />Telah di buat Data DN oleh : <b>'.$q['nm_lengkap'].' </b> pada tanggal <b>'.$futgl.'</b></td></tr>
					 <tr bgcolor="#00BFFF"><td width="20%" align="center"><b>Nomor DN</b></td>'.$fieldcn.'
					 					   <td width="15%" align="center"><b>Cabang</b></td>
					 					   <td width="15%"><b>Regional</b></td>
					 </tr>';
		$message .= '<tr><td>'.$valdn['dn_kode'].'</td>'.$fieldisi.'
						 <td>'.$valdn['id_cabang'].'</td>
						 <td>'.$valdn['id_regional'].'</td>
					 </tr>';
	};
	$message .= '</body></html></table>';

	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= 'From: '.$q['email'].'' . "\r\n";
	$headers .= 'Cc:  relife-ajk@relife.co.id' . "\r\n";
	mail($to, $subject, $message, $headers);
//	echo $to .'<br />';
//	echo $subject .'<br />';
//	echo $message .'<br />';
//	echo $headers .'<br />';

//	$mailmya = mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$metmail[0].'" AND id_cost="'.$x['id_cost'].'"'));
//	$to .= "sumiyanto@relife.co.id, arief.kurniawan@relife.co.id, ".$ARMemail." ";
	}
	echo '<center>Data telah dikirim melalui email ke masing-masing User berdasarkan User Upload.</center>
		  <meta http-equiv="refresh" content="3;URL=ajk_ARMvalidasi.php">';
	//echo $metvalidasi;
}
		;
		break;

	default:
echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
	  <tr><th width="80%" align="left" colspan="2">Modul Validasi DN/CN</font></th><th width="5%"><a href="ajk_ARMvalidasi.php?v=valbatcharm">Batch</a></th></tr></table>';

echo '<form method="post" action="ajk_ARMvalidasi.php?v=okmet" onload="onbeforeunload">
	  <table border="0" width="100%" cellpadding="5" cellspacing="1" bgcolor="#bde0e6">
	  <tr><th rowspan="2" width="3%">No</th>
	  	  <th colspan="5">Debit Note (DN)</th>
	  	  <th colspan="5">Credit Note (CN)</th>
	  	  <th rowspan="2" width="5%">Net DN-CN</th>
	  	  <th rowspan="2" width="5%">Tgl Input</th>
	  	  <th rowspan="2" width="1%"><input type="checkbox" id="selectall"/>ALL</th>
	  </tr>
	  <tr><th width="11%">Nomor</th>
		  <th width="5%">Total Premi</th>
		  <th width="5%">Jumlah Peserta</th>
		  <th width="10%">Cabang</th>
		  <th width="5%">Tipe</th>
	  	  <th width="11%">Nomor</th>
		  <th width="5%">Total Klaim</th>
		  <th width="5%">Jumlah Peserta</th>
		  <th width="10%">Cabang</th>
		  <th width="5%">Tipe</th>
	  </tr>';
if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 50;	}	else {	$m = 0;		}
$met = $database->doQuery('SELECT * FROM fu_ajk_dn WHERE id!="" AND validasi_uw="ya" AND validasi_arm="tdk" AND del IS NULL ORDER BY tgl_createdn DESC, id_dn DESC LIMIT ' . $m . ' , 50');
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_dn WHERE id != "" AND validasi_uw="ya" AND validasi_arm="tdk" AND del IS NULL '));
$totalRows = $totalRows[0];
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while ($metdn = mysql_fetch_array($met)) {
	$metpeserta = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_dn="'.$metdn['dn_kode'].'" AND del IS NULL');
	$fupeserta = mysql_num_rows($metpeserta);
	while ($idcek = mysql_fetch_array($metpeserta)) {
		if ($idcek['id_peserta']=="") {
			$x = 100000000 + $idcek['id'];	$xx = substr($x, 1);
			$metx = $database->doQuery('UPDATE fu_ajk_peserta SET id_peserta="'.$xx.'" WHERE id_peserta="" AND id="'.$idcek['id'].'"');
		}
		if ($idcek['status_peserta']=="") {	$tipedn = "Inforce";	}	else{	$tipedn = $idcek['status_peserta'];	}
	}
	$valdncn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cn WHERE id_dn="'.$metdn['dn_kode'].'"'));
	if ($valdncn['id_dn']==$metdn['dn_kode']) {
		$metValcn = $valdncn['id_cn'];
		$metValcnclaim = duit($valdncn['total_claim']);
		$metValcnpeserta = '<b>1</b> Peserta';
		$metValcbg = $valdncn['id_cabang'];
		$metValtype = $valdncn['type_claim'];
	}else{
		$metValcn = '-';
		$metValcnclaim = '-';
		$metValcnpeserta = '-';
		$metValcbg = '-';
		$metValtype = '-';
	}

	$valnetnya = $metdn['totalpremi'] - $valdncn['total_claim'];
if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	    <td align="center">'.(++$no + ($pageNow-1) * 50).'</td>
	    <td align="center">'.$metdn['dn_kode'].'</td>
	    <td align="right"><b>'.duit($metdn['totalpremi']).'</b></td>
	    <td align="center"><b>'.$fupeserta.'</b> Peserta</td>
	    <td>'.$metdn['id_cabang'].'</td>
	    <td align="center">'.$tipedn.'</td>
		<td align="center">'.$metValcn.'</td>
	    <td align="center"><b>'.$metValcnclaim.'</b></td>
	    <td align="center">'.$metValcnpeserta.'</td>
	    <td>'.$metValcbg.'</td>
	    <td>'.$metValtype.'</td>
	    <td align="right"><b>'.duit($valnetnya).'<b></td>
	    <td align="right">'.$metdn['tgl_createdn'].'</td>
		<td align="center"><input type="checkbox" class="case" name="validasidn[]" value="'.$metdn['id'].'"></td>';
}
echo '<tr bgcolor="#FFF"><td colspan="13">&nbsp;</td>
	<td align="center"><a title="Validasi data DN/CN" href="ajk_ARMvalidasi.php?v=okmet" onClick="if(confirm(\'Anda yakin untuk validasi data DN/CN ini ?\')){return true;}{return false;}"><input type="submit" name="Ok" Value="Ok"></td></tr>
	<tr><td colspan="22">';
echo createPageNavigations($file = 'ajk_dn.php?r=validasidnuw&', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 50);
//echo createPageNavigations($file = 'ajk_dn.php?r=viewdn&rpays='.$_REQUEST['rpays'].'&rpaye='.$_REQUEST['rpaye'].'&rreg='.$_REQUEST['rreg'].'&rcabang='.$_REQUEST['rcabang'].'&rdnno='.$_REQUEST['rdnno'].'&rstat='.$_REQUEST['rstat'].'&dns='.$_REQUEST['rdns'].'&dne='.$_REQUEST['rdne'].'&', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
echo '<b>Total Data Validasi DN/CN: <u>' . $totalRows . '</u></b></td></tr>';
echo '</table></form>';

/*
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
<tr><th width="90%" align="left">Modul Validasi DN</font></th></th></tr>
</table>
<table border="0" width="100%" cellpadding="3" cellspacing="1" bgcolor="#bde0e6">
<form method="post" action="ajk_ARMvalidasi.php?v=okmet" onload ="onbeforeunload">
	<tr><th width="1%"><input type="checkbox" id="selectall"/></th>
	<th width="1%">No</th>
	<th width="10%">Nomor Polis</th>
	<th width="12%">Debit note</th>
	<th width="8%">Tgl DN</th>
	<th width="5%">Jumlah Peserta</th>
	<th width="8%">Premi</th>
	<th width="12%">Credit note</th>
	<th width="8%">Tgl CN</th>
	<th>Regional</th>
	<th>Area</th>
	<th>Cabang</th>
	<th>Tanggal</th>
	<th>User</th>
	</tr>';
if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 100;	}	else	{	$m = 0;		}
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;

$met = $database->doQuery('SELECT * FROM fu_ajk_dn WHERE id !="" AND validasi_uw="ya" AND validasi_arm="tdk" AND del IS NULL ORDER BY tgl_createdn DESC LIMIT ' . $m . ' , 100');
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_dn WHERE id != "" AND validasi_uw="ya" AND validasi_arm="tdk" AND del IS NULL '));
$totalRows = $totalRows[0];

while ($mamet = mysql_fetch_array($met)) {
	$metpolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$mamet['id_nopol'].'"'));
	$metcostumer = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$mamet['id_cost'].'"'));

	$metdn = mysql_num_rows($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_dn = "'.$mamet['dn_kode'].'" '));

	//VALIDASI CN
	$valdncn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cn WHERE id_dn="'.$mamet['dn_kode'].'"'));


	//cek wilayah//
if ($mamet['id_cabang']=="") {	$metcabangnya = $mamet['id_cabang_old'];	}else{	$metcabangnya = $mamet['id_cabang'];	}
if ($mamet['id_area']=="") {	$metareanya = $mamet['id_area_old'];	}else{	$metareanya = $mamet['id_area'];	}
if ($mamet['id_regional']=="") {	$metregionalnya = $mamet['id_regional_old'];	}else{	$metregionalnya = $mamet['id_regional'];	}
	//cek wilayah//
if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		<td align="center"><input type="checkbox" class="case" name="validasidn[]" value="'.$mamet['id'].'"></td>
		<td align="center">'.(++$no + ($pageNow-1) * 100).'</td>
		<td align="center">'.$metpolis['nopol'].'</td>
		<td align="center"><font color="#3333FF">'.$mamet['dn_kode'].'</font></td>
		<td align="center">'._convertDate($mamet['tgl_createdn']).'</td>
		<td align="center">'.$metdn.'</td>
		<td align="right">'.duit($mamet['totalpremi']).'</td>
		<td></td>
		<td></td>
		<td>'.$metregionalnya.'</tdh>
		<td>'.$metareanya.'</tdh>
		<td>'.$metcabangnya.'</tdh>
		<td align="center">'.$mamet['tgl_createdn'].'</td>
		<td align="center">'.$mamet['input_by'].'</td>
	 </tr>';
	$jumpeserta += $metdn;
	$jumdnnya += $mamet['totalpremi'];
}
echo '<tr bgcolor="white">
		<td><a title="Validasi data DN/CN" href="ajk_ARMvalidasi.php?v=okmet" onClick="if(confirm(\'Anda yakin untuk validasi semua data DN/CN ini ?\')){return true;}{return false;}"><input type="submit" name="Ok" Value="Ok"></td>
		<td colspan="4" align="right"><b>Sub Total :</b></td><td align="center"><b>'.duit($jumpeserta).'</b></td><td align="right"><b>'.duit($jumdnnya).'</b></td><td colspan="5">&nbsp;</td></tr>';
echo '<tr><td colspan="11">';
echo createPageNavigations($file = 'ajk_ARMvalidasi.php?', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 100);
echo '<b>Total Data Validasi: <u>' . $totalRows . '</u></b></td></tr>';
echo '</form></table>';
*/
		;
} // switch
?>
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