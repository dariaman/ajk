<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// 2013
// ----------------------------------------------------------------------------------
include "../includes/fu6106.php";
include_once ("../includes/functions.php");
if (session_is_registered('nm_user')) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));	}
$futgl = date("Y-m-d g:i:a");
$futglcn = date("Y-m-d");
switch ($_REQUEST['r']) {
	case "deathclaim":
echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
		<tr><th>Konfirmasi Kelengkapan Data Claim Meninggal</th></tr>
		</table>';
if ($_REQUEST['ope']=="Simpan") {
	$_REQUEST['tgl_klaimdoc'] = $_POST['tgl_klaimdoc'];
	if (!$_REQUEST['tgl_klaimdoc'])  $error .='<blink><font color=red>Input Tanggal Dokumen</font></blink><br>';
	if ($error)
	{	echo '<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border: solid 1px red">
				  <tr><td><table width="100%" class="bgcolor1">
						  <tr><td align="left"><img src="image/warning.gif" border="0"></td>
							  <td align="center"><font class="option"><blink>'.$error.'</blink></font></td>
							  <td align="right"><img src="image/warning.gif" border="0"></td>
						  </tr>
				  </table></td></tr>
				  </table>';
	}
	else
	{
/*
$klaim = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_klaim WHERE id="'.$_REQUEST['id'].'"'));
$klaimpsrt = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_peserta WHERE id="'.$klaim['id'].'"'));
$q=mysql_fetch_array(mysql_query('SELECT * FROM pengguna WHERE id="'.$_REQUEST['adsess'].'"'));
if ($_REQUEST['cek1']=="Y" AND
	$_REQUEST['cek2']=="Y" AND
	$_REQUEST['cek3']=="Y" AND
	$_REQUEST['cek4']=="Y" AND
	$_REQUEST['cek5']=="Y" AND
	$_REQUEST['cek6']=="Y" AND
	$_REQUEST['cek7']=="Y") {
$cekdoc = "App-Unpaid";	}else{	$cekdoc = "Pending";	}

$metklaim = mysql_query ('INSERT INTO fu_ajk_klaim_doc SET id_pes="'.$klaim['id_peserta'].'",
														  id_cost = "'.$klaimpsrt['id_cost'].'",
														  id_dn = "'.$klaim['id_dn'].'",
														  id_klaim = "'. $_REQUEST['id'].'",
														  doc1 = "'.$_REQUEST['cek1'].'",
														  doc2 = "'.$_REQUEST['cek2'].'",
														  doc3 = "'.$_REQUEST['cek3'].'",
														  doc4 = "'.$_REQUEST['cek4'].'",
														  doc5 = "'.$_REQUEST['cek5'].'",
														  doc6 = "'.$_REQUEST['cek6'].'",
														  doc7 = "'.$_REQUEST['cek7'].'",
														  tgl_doc = "'.$_REQUEST['tgl_klaimdoc'].'",
														  approve = "'.$cekdoc.'",
														  ket = "'.$_REQUEST['mnote'].'",
														  input_by = "'.$q['nm_lengkap'].'",
														  input_date = "'.$futgl.'"');
$metupd = mysql_query('UPDATE fu_ajk_klaim SET confirm_klaim="'.$cekdoc.'" WHERE id="'.$_REQUEST['id'].'"');
echo '<center>Dokumen klaim telah selesai di input.<hr></center><meta http-equiv="refresh" content="2;URL=ajk_confirmed.php?r=closeah">'; //SET TO CLOSE WINDOW
*/
foreach($_REQUEST['ya'] as $doc => $docya)	{
$jumdoc .=$docya;
if ($docya==1) { $xxdoc .= 'doc1'.$docya.',';}
if ($docya==2) { $xxdoc .= 'doc2'.$docya.',';}
if ($docya==3) { $xxdoc .= 'doc3'.$docya.',';}
if ($docya==4) { $xxdoc .= 'doc4'.$docya.',';}
if ($docya==5) { $xxdoc .= 'doc5'.$docya.',';}
if ($docya==6) { $xxdoc .= 'doc6'.$docya.',';}
if ($docya==7) { $xxdoc .= 'doc7'.$docya.',';}
if ($docya==8) { $xxdoc .= 'doc8'.$docya.',';}
if ($docya==9) { $xxdoc .= 'doc9'.$docya.',';}
if ($docya==10) { $xxdoc .= 'doc10'.$docya.',';}
if ($docya==11) { $xxdoc .= 'doc11'.$docya.',';}
}
echo $xxdoc.'<br />';
$dataklaim = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_klaim WHERE id="'.$_REQUEST['id'].'"'));
$datauser = mysql_fetch_array(mysql_query('SELECT * FROM pengguna WHERE id="'.$_REQUEST['adsess'].'"'));
$metdocnya = mysql_query('INSERT INTO fu_ajk_klaim_doc SET id_pes="'.$dataklaim['id_peserta'].'",
														   id_cost="'.$dataklaim['id_cost'].'",
									  					   id_dn="'.$dataklaim['id_dn'].'",
									  					   id_klaim="'.$dataklaim['id_cn'].'",
									  					   '.$xxdoc.'
														   tgl_doc="'.$_REQUEST['tgl_klaimdoc'].'",
														   tgl_doc_lengkap="'.$_REQUEST['tgl_klaimdoc_lengkap'].'",
														   tgl_investigasi="'.$_REQUEST['tgl_investigasi'].'",
														   ket="'.$_REQUEST['mnote'].'",
														   input_by="'.$datauser['nm_lengkap'].'",
														   input_date="'.$futgl.'"');
//echo $jumdoc;
$metupd = mysql_query('UPDATE fu_ajk_klaim SET confirm_klaim="'.$cekdoc.'" WHERE id="'.$_REQUEST['id'].'"');
echo '<center>Dokumen klaim telah selesai di input.<hr></center><meta http-equiv="refresh" content="2;URL=ajk_confirmed.php?r=closeah">'; //SET TO CLOSE WINDOW
	}
}
$met = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_klaim WHERE id="'.$_REQUEST['id'].'"'));
$metnm = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_peserta WHERE id="'.$met['id_peserta'].'"'));
echo '<form name="f1"  method="post" action="">
	<table border="0" width="100%" cellpadding="1" cellspacing="1">
	<input type="hidden" name="id" value="'.$_REQUEST['id'].'">
	<input type="hidden" name="adsess" value="'.$_REQUEST['adsess'].'">
	<tr><td width="20%">No. DN</td><td width="1%">:</td><td><b>'.$met['id_dn'].'</b></td></tr>
	<tr><td>Nama</td><td width="1%">:</td><td><b>'.$metnm['nama'].'</b></td></tr>
	<tr><td>Tanggal Meninggal</td><td width="1%">:</td><td><b>'._convertDate($met['tgl_klaim']).'</b></td></tr>
	</table>
	<table border="0" width="100%" cellpadding="1" cellspacing="1">
	<tr bgcolor="#B60"><th width="1%">No</th><th>Kelengkapan Dokumen</th><th width="20%">Konfirmasi</th></tr>
	<!--
	<tr bgcolor="#CDE"><td width="1%" align="center">1.</td><td>Photo Copy tanda bukti diri Peserta/Tertanggung yang masih berlaku (misal : KTP/SIM/Passport)</td><td align="center"><input type=radio '.pilih($_REQUEST["cek1"], "Y").'  name="cek1" value="Y">Y &nbsp; <input type=radio '.pilih($_REQUEST["cek1"], "T").' name="cek1" value="T">T </td></tr>
	<tr bgcolor="#DDE"><td align="center">2.</td><td>Sertifikat asli</td><td align="center"><input type=radio '.pilih($_REQUEST["cek2"], "Y").'  name="cek2" value="Y">Y &nbsp; <input type=radio '.pilih($_REQUEST["cek2"], "T").'  name="cek2" value="T">T </td></tr>
	<tr bgcolor="#DDE"><td align="center">3.</td><td>Kuitansi pembayaran Premi asli</td><td align="center"><input type=radio '.pilih($_REQUEST["cek3"], "Y").'  name="cek3" value="Y">Y &nbsp; <input type=radio '.pilih($_REQUEST["cek3"], "T").'  name="cek3" value="T">T </td></tr>
	<tr bgcolor="#DDE"><td align="center">4.</td><td>Formulir klaim</td><td align="center"><input type=radio '.pilih($_REQUEST["cek4"], "Y").'  name="cek4" value="Y">Y &nbsp; <input type=radio '.pilih($_REQUEST["cek4"], "T").'  name="cek4" value="T">T </td></tr>
	<tr bgcolor="#DDE"><td align="center">5.</td><td>Surat keterangan dokter mengenai sebab meninggal dunia (nomenklatur)</td><td align="center"><input type=radio '.pilih($_REQUEST["cek5"], "Y").'  name="cek5" value="Y">Y &nbsp; <input type=radio '.pilih($_REQUEST["cek5"], "T").'  name="cek5" value="T">T </td></tr>
	<tr bgcolor="#DDE"><td align="center">6.</td><td>Surat keterangan kematian dari instansi yang berwenang</td><td align="center"><input type=radio '.pilih($_REQUEST["cek6"], "Y").'  name="cek6" value="Y">Y &nbsp; <input type=radio '.pilih($_REQUEST["cek6"], "T").'  name="cek6" value="T">T </td></tr>
	<tr bgcolor="#DDE"><td align="center">7.</td><td>Photo Copy jadwal pembayaran kredit</td><td align="center"><input type=radio '.pilih($_REQUEST["cek7"], "Y").'  name="cek7" value="Y">Y &nbsp; <input type=radio '.pilih($_REQUEST["cek7"], "T").'  name="cek7" value="T">T </td></tr>
	-->';
$docmet = mysql_query('SELECT * FROM fu_ajk_klaim_dokumen WHERE id_cost="'.$metnm['id_cost'].'"');
while ($rdocmet = mysql_fetch_array($docmet)) {
if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">';
echo '<tr><td class="w" width="1%" align="center">'.++$no.'</td>
	 	  <td class="w">'.$rdocmet['dokumen'].'</td>
		  <td class="w" align="center"><input type="checkbox" name="ya[]" value="'.$rdocmet['id'].'"></td>
	  </tr>';
}
echo '</table>
	<table border="0" width="100%" cellpadding="1" cellspacing="1">
	<tr><td>Tanggal Dokumen <font color="Red">*</font></td><td width="1%">:</td><td>';
		echo initCalendar();	echo calendarBox('tgl_klaimdoc', 'triger', $_REQUEST['tgl_klaimdoc']);
echo '</td></tr>
	<tr><td>Tanggal Kelengkapan Dokumen</td><td width="1%">:</td><td>';
		echo initCalendar();	echo calendarBox('tgl_klaimdoc_lengkap', 'triger1', $_REQUEST['tgl_klaimdoc_lengkap']);
echo '</td></tr>
	<tr><td>Tanggal Investigasi</td><td width="1%">:</td><td>';
		echo initCalendar();	echo calendarBox('tgl_investigasi', 'triger2', $_REQUEST['tgl_investigasi']);
echo '</td></tr>
	<tr><td valign="top">Catatan/Keterangan</td><td valign="top">:</td><td><textarea name="mnote" cols="70" rows="2">'.htmlspecialchars($_REQUEST['mnote']).'</textarea></td></tr>
	<tr><td align="center" colspan="3"><input type="submit" name="ope" value="Simpan"></td></tr>
	</table></form>';
		;
		break;

	case "eddeathclaim":
echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
		<tr><th>Edit Konfirmasi Kelengkapan Data Claim Meninggal</th></tr>
		</table>';
if ($_REQUEST['ope']=="Simpan") {
	$_REQUEST['tgl_doc'] = $_POST['tgl_doc'];
	if (!$_REQUEST['tgl_doc'])  $error .='<blink><font color=red>Input Tanggal Dokumen</font></blink><br>';
	if ($error)
	{	echo '<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border: solid 1px red">
					  <tr><td><table width="100%" class="bgcolor1">
							  <tr><td align="left"><img src="image/warning.gif" border="0"></td>
								  <td align="center"><font class="option"><blink>'.$error.'</blink></font></td>
								  <td align="right"><img src="image/warning.gif" border="0"></td>
							  </tr>
					  </table></td></tr>
					  </table>';
	}
	else
	{
/*
$q=mysql_fetch_array(mysql_query('SELECT * FROM pengguna WHERE id="'.$_REQUEST['adsess'].'"'));
if ($_REQUEST['doc1']=="Y" AND
	$_REQUEST['doc2']=="Y" AND
	$_REQUEST['doc3']=="Y" AND
	$_REQUEST['doc4']=="Y" AND
	$_REQUEST['doc5']=="Y" AND
	$_REQUEST['doc6']=="Y" AND
	$_REQUEST['doc7']=="Y") {	$cekdoc = 'App-Unpaid';	}	else	{	$cekdoc = 'Pending';	}

$metupd = mysql_query('UPDATE fu_ajk_klaim_doc SET doc1 = "'.$_REQUEST['doc1'].'",
												   doc2 = "'.$_REQUEST['doc2'].'",
												   doc3 = "'.$_REQUEST['doc3'].'",
												   doc4 = "'.$_REQUEST['doc4'].'",
												   doc5 = "'.$_REQUEST['doc5'].'",
												   doc6 = "'.$_REQUEST['doc6'].'",
												   doc7 = "'.$_REQUEST['doc7'].'",
												   approve = "'.$cekdoc.'",
												   tgl_doc = "'.$_REQUEST['tgl_doc'].'",
												   ket = "'.$_REQUEST['ket'].'",
												   update_by="'.$q['nm_lengkap'].'",
												   update_date="'.$futgl.'"
												   WHERE id="'.$_REQUEST['idkdoc'].'"');
echo '<center>Dokumen klaim telah selesai di update.<hr></center><meta http-equiv="refresh" content="2;URL=ajk_confirmed.php?r=closeah">'; //SET TO CLOSE WINDOW
*/

	}

}
$metklaim = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_klaim WHERE id="'.$_REQUEST['id'].'"'));
$metklaimdoc = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_klaim_doc WHERE id_klaim="'.$metklaim['id_cn'].'" AND id_cost="'.$metklaim['id_cost'].'"'));
$metpeserta = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_peserta WHERE id_klaim="'.$metklaim['id_klaim'].'" AND del IS NULL'));
echo '<form name="f1"  method="post" action="">
	<table border="0" width="100%" cellpadding="1" cellspacing="1">
	<input type="hidden" name="id" value="'.$_REQUEST['id'].'">
	<input type="hidden" name="idkdoc" value="'.$met['id'].'">
	<input type="hidden" name="adsess" value="'.$_REQUEST['adsess'].'">
	<tr><td width="50%">DN Number</td><td width="1%">:</td><td><b>'.$metklaim['id_dn'].'</b></td></tr>
	<tr><td>Name</td><td width="1%">:</td><td><b>'.$metpeserta['nama'].'</b></td></tr>
	<tr><td>Date Claim</td><td width="1%">:</td><td><b>'._convertDate($metklaim['tgl_klaim']).'</b></td></tr>
	<tr><td colspan="3">
	<table border="0" width="100%" cellpadding="1" cellspacing="1">
	<tr bgcolor="#B60"><th width="1%">No</th><th>Document</th><th width="20%">Confirm</th></tr>';
$docmet = mysql_query('SELECT * FROM fu_ajk_klaim_dokumen WHERE id_cost="'.$metpeserta['id_cost'].'"');
while ($rdocmet = mysql_fetch_array($docmet)) {
if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">';
echo '<tr><td class="w" width="1%" align="center">'.++$no.'</td>
		 	  <td class="w">'.$rdocmet['dokumen'].'</td>
			  <td class="w" align="center"><input type="checkbox" name="ya[]" value="'.$metklaimdoc['id'].'"></td>
		  </tr>';
}
echo '</table>
	<table border="0" width="100%" cellpadding="1" cellspacing="1">
	<tr><td>Tanggal Dokumen <font color="Red">*</font></td><td width="1%">:</td><td>';
echo initCalendar();	echo calendarBox('tgl_klaimdoc', 'triger', $metklaimdoc['tgl_doc']);
echo '</td></tr>
	<tr><td>Tanggal Kelengkapan Dokumen</td><td width="1%">:</td><td>';
echo initCalendar();	echo calendarBox('tgl_klaimdoc_lengkap', 'triger1', $metklaimdoc['tgl_doc_lengkap']);
echo '</td></tr>
	<tr><td>Tanggal Investigasi</td><td width="1%">:</td><td>';
echo initCalendar();	echo calendarBox('tgl_investigasi', 'triger2', $metklaimdoc['tgl_investigasi']);
echo '</td></tr>
	<tr><td valign="top">Catatan/Keterangan</td><td valign="top">:</td><td><textarea name="mnote" cols="70" rows="2">'.htmlspecialchars($_REQUEST['ket']).'</textarea></td></tr>
	<tr><td align="center" colspan="3"><input type="submit" name="ope" value="Simpan"></td></tr>
  </table></form>';
	;
	break;

	case "paiddeath":
echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
		<tr><th>Pembayaran Klaim Meninggal</th></tr>
		</table>';
if ($_REQUEST['ope']=="Simpan") {
	$_REQUEST['tglbyrcn'] = $_POST['tglbyrcn'];
	if (!$_REQUEST['tglbyrcn'])  $error .='<blink><font color=red>Tentukan Tanggal Pembayaran CN</font></blink><br>';
	if ($error)
	{	echo '<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border: solid 1px red">
					  <tr><td><table width="100%" class="bgcolor1">
							  <tr><td align="left"><img src="image/warning.gif" border="0"></td>
								  <td align="center"><font class="option"><blink>'.$error.'</blink></font></td>
								  <td align="right"><img src="image/warning.gif" border="0"></td>
							  </tr>
					  </table></td></tr>
					  </table>';
	}
	else
	{
$metklaim = mysql_query ('INSERT INTO fu_ajk_cn SET id_claim="'.$_REQUEST['id'].'",
													id_cost="'.$_REQUEST['id_cost'].'",
													id_nopol = "'.$_REQUEST['id_polis'].'",
													id_peserta = "'.$_REQUEST['id_peserta'].'",
													id_dn = "'. $_REQUEST['id_dn'].'",
													id_cabang = "'.$_REQUEST['cabang'].'",
													tgl_byr_claim = "'.$_REQUEST['tglbyrcn'].'",
													input_by = "'.$q['nm_lengkap'].'",
													input_date = "'.$futgl.'"');
$metcn = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_cn ORDER BY id DESC'));
$metpaidcn = mysql_query('UPDATE fu_ajk_klaim SET id_cn="'.$metcn['id'].'", confirm_klaim="App - Paid" WHERE id_peserta="'.$_REQUEST['id_peserta'].'"');
$metpaidcndok = mysql_query('UPDATE fu_ajk_klaim_doc SET approve="App - Paid" WHERE id_pes="'.$_REQUEST['id_peserta'].'"');
echo '<center>Dokumen klaim telah selesai di input.<hr></center><meta http-equiv="refresh" content="2;URL=ajk_confirmed.php?r=closeah">'; //SET TO CLOSE WINDOW
	}}
$met = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_klaim WHERE id="'.$_REQUEST['id'].'"'));
$metnm = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_peserta WHERE id="'.$met['id_peserta'].'" AND del IS NULL'));
echo'<form name="f1"  method="post" action="">
	<table border="0" width="100%" cellpadding="1" cellspacing="1">
	<input type="hidden" name="id" value="'.$_REQUEST['id'].'">
	<input type="hidden" name="id_cost" value="'.$metnm['id_cost'].'">
	<input type="hidden" name="id_polis" value="'.$metnm['id_polis'].'">
	<input type="hidden" name="id_peserta" value="'.$met['id_peserta'].'">
	<input type="hidden" name="id_dn" value="'.$met['id_dn'].'">
	<input type="hidden" name="cabang" value="'.$metnm['cabang'].'">
	<tr><td width="30%">DN Number</td><td width="1%">:</td><td><b>'.$met['id_dn'].'</b></td></tr>
	<tr><td>Name</td><td width="1%">:</td><td><b>'.$metnm['nama'].'</b></td></tr>
	<tr><td>Date Claim</td><td width="1%">:</td><td><b>'._convertDate($met['tgl_klaim']).'</b></td></tr>
	<tr><td>Status Klaim</td><td width="1%">:</td><td><b>'.$met['type_klaim'].'</b></td></tr>
	<tr><td>Total Klaim</td><td width="1%">:</td><td><b>'.duit($met['jumlah']).'</b></td></tr>
	<tr><td>Tanggal Pembayaran CN</td><td width="1%">:</td><td>';print initCalendar();	print calendarBox('tglbyrcn', 'triger', $_REQUEST['tglbyrcn']); echo '</td></tr>
	<tr><td align="center" colspan="3"><input type="submit" name="ope" value="Simpan"></td></tr>
	</table></form>';
	;
	break;

	case "setcnprm":
echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
	<form method="post" action="ajk_confirmed.php?r=setcnprm&id='.$_REQUEST['id'].'">
	<tr><td colspan="7" class="R_kolomjudul">Data CN</td></tr>
	<tr><td width="4%">Nama</td><td width="20%">: <input type="text" name="namacn" value="'.$_REQUEST['namacn'].'"> &nbsp;
		<td width="7%">Nomor CN</td><td width="23%">: AJKCN-<input type="text" name="metcn" value="'.$_REQUEST['metcn'].'"></td>
		<td width="7%">Nomor DN</td><td width="23%">: AJKDN-<input type="text" name="metdn" value="'.$_REQUEST['metdn'].'"></td>
		<td><input type="submit" name="Cari" value="Searching" class="button"></td>
	</form></table>';
if ($_REQUEST['met']=="metupdate") {
$mamet=mysql_query('UPDATE fu_ajk_cn SET tgl_byr_claim="'.$futglcn.'", id_prm="'.$_REQUEST['idcnprm'].'", update_by="'.$q['nm_user'].'", update_time="'.$futgl.'" WHERE id="'.$_REQUEST['idcn'].'"');
echo '<center>Data CN telah di update</center><meta http-equiv="refresh" content="2; url=ajk_confirmed.php?r=setcnprm&id='.$_REQUEST['idcnprm'].'">';
}
echo '<table border="0" width="100%" cellpadding="3" cellspacing="1">
	<tr class="top"><th width="1%" rowspan="2">No</th>
		<th width="5%" rowspan="2">SPAJ</th>
		<th rowspan="2">Nama Debitur<br /><font size="1"><i>(sesuai KTP/SIM)</i></font></th>
		<th width="5%" rowspan="2">Premi</th>
		<th colspan="2" width="30%">Debit Note</th>
		<th colspan="4" width="30%">Movement</th>
		<th rowspan="2" width="10%">Cabang</th>
		<th rowspan="2" width="6%">Set PRM</th>
	</tr>
	<tr class="top"><th width="11%">Nomor</th>
					<th width="5%">Premi</th>
					<th>No. CN</th>
					<th>Premi CN</th>
					<th>Tanggal</th>
					<th>Status</th>
	</tr>';
if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 10;	}
else {	$m = 0;		}
if ($_REQUEST['metcn'])		{	$satu = 'AND id_cn LIKE "%' . $_REQUEST['metcn'] . '%"';		}
if ($_REQUEST['metdn'])		{	$tiga = 'AND id_dn LIKE "%' . $_REQUEST['metdn'] . '%"';		}
$ceknamanya = mysql_fetch_array(mysql_query('SELECT id_klaim, nama from fu_ajk_peserta WHERE nama="'.$_REQUEST['namacn'].'"'));
if ($_REQUEST['namacn'])	{	$dua = 'AND id_cn LIKE "' . $ceknamanya['id_klaim'] . '"';		}

// QUERY LAMA : $metCN = mysql_query('SELECT *, fu_ajk_cn.id, fu_ajk_cn.id_cn, fu_ajk_cn.id_dn, fu_ajk_peserta.nama FROM fu_ajk_cn INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cn = fu_ajk_peserta.id_klaim AND fu_ajk_cn.tgl_byr_claim = "" '.$satu.' '.$dua.' '.$tiga.' ORDER BY fu_ajk_cn.id_cn DESC LIMIT ' . $m . ' , 10');
$metCN = mysql_query('SELECT * FROM fu_ajk_cn WHERE id!="" '.$satu.' '.$dua.' '.$tiga.' AND del IS NULL ORDER BY id_prm ASC, id_cn DESC LIMIT ' . $m . ' , 10');
$totalRows = mysql_fetch_array(mysql_query('SELECT COUNT(id_cn) FROM fu_ajk_cn WHERE id !="" '.$satu.' '.$dua.' '.$tiga.' AND del IS NULL '));
$totalRows = $totalRows[0];

//if ($totalRows < 1) {	echo '<tr bgcolor="#FDF" class="'.rowClass(++$i).'"><td colspan="12" align="center">Data <b>'.$_REQUEST['metcn'].' '.$_REQUEST['namacn'].'</b> tidak ditemukan.</td></tr>';	}
if ($totalRows < 1) {	echo '<tr bgcolor="#DFF" class="'.rowClass(++$i).'"><td colspan="12" align="center">Data <b>'.$_REQUEST['metcn'].' '.$_REQUEST['namacn'].'</b> tidak ditemukan.</td></tr>';	}
else	{
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;

while ($fuCN = mysql_fetch_array($metCN)) {
//$peserta = mysql_fetch_array(mysql_query('SELECT * FROM v_fu_ajk_peserta WHERE id_klaim = "'.$fuCN['id_cn'].'"'));
$peserta = mysql_fetch_array(mysql_query('SELECT id, nama, id_klaim FROM fu_ajk_peserta WHERE id_klaim = "'.$fuCN['id_cn'].'"'));
//$cekDN = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_dn WHERE dn_kode = "'.$fuCN['id_dn'].'"'));
$cekDN = mysql_fetch_array(mysql_query('SELECT dn_kode, totalpremi FROM fu_ajk_dn WHERE dn_kode = "'.$fuCN['id_dn'].'"'));
$cekprm = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_prm WHERE id = "'.$fuCN['id_prm'].'"'));

if ($fuCN['id_prm']=="") { $cnstatusnya ='<a href="ajk_confirmed.php?r=setcnprm&met=metupdate&idcn='.$fuCN['id'].'&idcnprm='.$_REQUEST['id'].'" onClick="if(confirm(\'Anda yakin untuk mengupdate data pembayaran ini ?\')){return true;}{return false;}"><img src="image/save.png" width="15"></a>';	}
else	{ $cnstatusnya= '<blink><b>'.$cekprm['id_prm'].'<br />'._convertDate($fuCN['tgl_byr_claim']).'</b></blink>';	}
if ($fuCN['total_claim'] < 0) { $totalclaimnya = 0;	}else{ $totalclaimnya =	$fuCN['total_claim'];}
echo '<tr bgcolor="#F5FAFA" class="'.rowClass(++$i).'">
	<td class="w" align="center">'.(++$no + ($pageNow-1) * 10).'</td>
	<td class="w" align="center">'.$peserta['spaj'].'</td>
	<td class="w">'.$peserta['nama'].'</td>
	<td class="w" align="right">'.duit($fuCN['premi']).'</td>
	<td class="w" align="center">'.substr($fuCN['id_dn'],6).'</td>
	<td class="w" align="right">'.duit($cekDN['totalpremi']).'</td>
	<td class="w" align="center"><b>'.substr($fuCN['id_cn'],6).'</b></td>
	<td class="w" align="right">'.duit($totalclaimnya).'</td>
	<td class="w" align="center">'.$fuCN['tgl_claim'].'</td>
	<td class="w">'.$fuCN['confirm_claim'].'</td>
	<td class="w">'.$fuCN['id_cabang'].'</td>
	<td class="w" align="center">'.$cnstatusnya.'</td>
	</tr>';
}
echo '<tr><td colspan="22">';
echo createPageNavigations($file = 'ajk_confirmed.php?r=setcnprm&id='.$_REQUEST['id'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 10);
echo '<b>Total Data CN Unpaid: <u>' . $totalRows . '</u></b></td></tr>';
}
echo '</table>';
	;
	break;

	case "closeah":
		echo '<center><a href="javascript:window.close();">Close Window</a></center>';
		;
		break;
	default:
		;
} // switch
?>
<style type="text/css">
table, textarea, select, option, input {
	font-family: Times New Roman, Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
}

td.w {
		border-right : 1px solid #DED;
		border-bottom : 1px solid #DED;
		border-left : 1px solid #DED;
		padding : 3px 3px 3px 3px;
		font-size: 11px;
}
tr:hover {
	background-color: #CAE8EA;
	color: blue;
	font-weight: bold;
}
.R_kolomjudul
{	font-size: 24px;
	font-weight: bold;
	color: #000000;
	background-color: #cccc99;
	padding: 0.2em 1.5em;
	border:1px solid green;
	text-align: center;
	text-transform: uppercase;
}
</style>