<?php
include_once ("ui.php");
include_once ("../includes/functions.php");
connect();
if (isset($_SESSION['nm_user'])) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));}
switch ($_REQUEST['r']) {
	case "p_dn":
echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
	  <tr><th width="80%" align="left" colspan="2">Modul Invoice Debit Note (DN) - Pembayaran Premi Ke Asuransi </font></th>
	  <tr><td>
	  <tr><td colspan="2" align="center">
<fieldset style="padding: 2">
<legend>Searching</legend>
<table border="0" width="100%" cellpadding="2" cellspacing="1" align="center">
<form method="post" action="">
<tr><td>Nama Persusahaan</td>
	<td>: <select id="id_cost" name="id_cost">
	  	<option value="">-----Perusahaan-----</option>';
	$metreg = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name ASC');
	while ($metreg_ = mysql_fetch_array($metreg)) {	echo '<option value="'.$metreg_['id'].'">'.$metreg_['name'].'</option>';	}
	echo '</select></td>
	<td>Nama Produk</td>
	<td>: <select name="id_polis" id="id_polis"><option value="">--- Produk ---</option></select></td>
	<td>Asuransi</td>
	<td>: <select name="id_asuransi" id="id_asuransi"><option value="">--- Asuransi ---</option></select></td>
</tr>
<tr><td>Regional</td>
	<td>: <select id="rreg" name="rreg">
	<option value="">--- Pilih ---</option>';
		$rreg=$database->doQuery('SELECT * FROM fu_ajk_regional ORDER BY name ASC');
while($freg = mysql_fetch_array($rreg)) {
	echo  '<option value="'.$freg['name'].'"'._selected($_REQUEST['rreg'], $freg['name']).'>'.$freg['name'].'</option>';}
echo '</select></td>
	<td width="10%">DN Date </td>
	<td>: ';print initCalendar();	print calendarBox('rdns', 'triger', $_REQUEST['rdns']);echo ' s/d ';
		print initCalendar();	print calendarBox('rdne', 'triger1', $_REQUEST['rdne']); echo '</td>
	<td width="11%">Nomor DN</td><td>: <input type="text" name="dns" value="'.$_REQUEST['dns'].'"> s/d <input type="text" name="dne" value="'.$_REQUEST['dne'].'">';
echo '</td>
</tr>
<tr><td>Cabang</td>
	<td>: <select id="rcabang" name="rcabang">
		<option value="">--- Pilih ---</option>';
		$rcabang=$database->doQuery('SELECT * FROM fu_ajk_cabang ORDER BY name ASC');
while($farea = mysql_fetch_array($rcabang)) {
	echo '<option value="'.$farea['name'].'"'._selected($_REQUEST['rcabang'], $farea['name']).'>'.$farea['name'].'</option>';}
	echo '</select></td><td>Payment Date</td>
		<td>: ';print initCalendar();	print calendarBox('rpays', 'triger2', $_REQUEST['rpays']); echo ' s/d ';
print initCalendar();	print calendarBox('rpaye', 'triger3', $_REQUEST['rpaye']); echo '</td>
	<td>Status Pembayaran</td>
	<td>: <select id="rstat" name="rstat">
			<option value="">--- Pilih Status ---</option>
			<option value="paid">Paid</option>
			<option value="unpaid">Unpaid</option>
	</select></td>
	</tr>
<tr><td colspan="6" align="center"><input type="submit" name="carieuy" value="Cari" class="button"></td></tr>
</form>
</table>
</fieldset><br />';
if(isset($_REQUEST['carieuy'])){
echo '<table border="0" width="100%" cellpadding="5" cellspacing="1" bgcolor="#bde0e6">
	  <tr><th width="3%">No</th>
	  	  <th width="25%">Perusahaan</th>
	  	  <th width="25%">Asuransi</th>
	  	  <th width="10%">Produk</th>
	  	  <th>Debitnote</th>
	  	  <th width="5%">Peserta</th>
	  	  <th>Premi Bank</th>
	  	  <th>Tanggal Debitnote</th>
	  	  <th>Tanggal WPC</th>
	  	  <th width="5%">Paid Date ke Asuransi</th>
	  	  <th width="12%">Total bayar ke Asuransi</th>
	  	  <th width="5%">Status Bayar Ke Asuransi</th>
	  	  <th width="5%">NettPremi Bank</th>
	  	  <th width="5%">NettPremi Asuransi</th>
	  	  <th width="10%">Cabang</th>
	  	  <th width="9%">Regional</th>
	  	  <th width="10%">Option</th>
	  	  <!--<th width="1%">Hapus DN</th>-->
	  </tr>';

if ($_REQUEST['id_cost'])								{	$satu = 'AND fu_ajk_dn.id_cost = "'.$_REQUEST['id_cost'].'"';	}
if ($_REQUEST['id_polis'])								{	$dua = 'AND fu_ajk_dn.id_nopol	 = "'.$_REQUEST['id_polis'].'"';	}
if ($_REQUEST['id_asuransi'])							{	$tiga = 'AND fu_ajk_dn.id_as = "'.$_REQUEST['id_asuransi'].'"';	}
if ($_REQUEST['rreg'])									{	$empat = 'AND fu_ajk_dn.id_regional LIKE "' .  $_REQUEST['rreg'] . '"';		}
if ($_REQUEST['rcabang'])								{	$lima = 'AND fu_ajk_dn.id_cabang LIKE "' . $_REQUEST['rcabang'] . '"';		}

if ($_REQUEST['rdns']!='' AND $_REQUEST['rdne']!='')	{	$enam= 'AND fu_ajk_dn.tgl_createdn BETWEEN \''.$_REQUEST['rdns'].'\' AND \''.$_REQUEST['rdne'].'\'';	}
if ($_REQUEST['rpays']!='' AND $_REQUEST['rpaye']!='')	{	$tujuh= 'AND fu_ajk_dn.tgl_dn_paid BETWEEN \''.$_REQUEST['rpays'].'\' AND \''.$_REQUEST['rpaye'].'\'';	}

if ($_REQUEST['rstat'])									{	$delapan = 'AND fu_ajk_dn.dn_status = "'.$_REQUEST['rstat'].'"';	}
if ($_REQUEST['rdnno'])									{	$sembilan = 'AND fu_ajk_dn.dn_kode LIKE "%' . $_REQUEST['rdnno'] . '%"';		}
if ($_REQUEST['dns']!='' AND $_REQUEST['dne']!='')		{	$sepuluh = 'AND fu_ajk_dn.dn_kode BETWEEN \''.$_REQUEST['dns'].'\' AND \''.$_REQUEST['dne'].'\'';		}

if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}

$met = $database->doQuery('SELECT fu_ajk_costumer.name AS perusahaan,
								  fu_ajk_polis.nmproduk,
								  fu_ajk_dn.id,
								  fu_ajk_dn.id_cost,
								  fu_ajk_dn.id_nopol,
								  fu_ajk_dn.id_as,
								  fu_ajk_dn.id_polis_as,
								  fu_ajk_dn.id_regional,
								  fu_ajk_dn.id_cabang,
								  fu_ajk_dn.totalpremi,
								  fu_ajk_dn.tgl_createdn,
								  (fu_ajk_dn.tgl_createdn + INTERVAL fu_ajk_polis.jtempo DAY) AS tglWPC,
								  fu_ajk_dn.tgl_dn_paid,
								  fu_ajk_dn.dn_status,
								  ifnull(fu_ajk_note_as.note_paid_total,0) as paid_total,
								  ifnull(fu_ajk_note_as.note_status,"UNPAID") as note_status,
								  fu_ajk_note_as.note_paid_date,
									fu_ajk_dn.dn_kode
						   FROM fu_ajk_dn
						   INNER JOIN fu_ajk_costumer ON fu_ajk_dn.id_cost = fu_ajk_costumer.id
						   INNER JOIN fu_ajk_polis ON fu_ajk_dn.id_nopol = fu_ajk_polis.id
						   LEFT JOIN fu_ajk_note_as ON fu_ajk_note_as.id_dn=fu_ajk_dn.id
						   WHERE fu_ajk_dn.id !="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' '.$tujuh.' '.$delapan.' '.$sembilan.' '.$sepuluh.' AND fu_ajk_dn.del IS NULL
		   				   GROUP BY fu_ajk_dn.dn_kode
		   				   ORDER BY fu_ajk_dn.tgl_createdn DESC, fu_ajk_dn.id DESC LIMIT ' . $m . ' , 25');
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_dn WHERE id != "" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' '.$tujuh.' '.$delapan.' '.$sembilan.' '.$sepuluh.' AND fu_ajk_dn.del IS NULL '));
$totalRows = $totalRows[0];
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while ($metdn = mysql_fetch_array($met)) {
$met_asuransi_ = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_asuransi WHERE id="'.$metdn['id_as'].'"'));
$met_peserta_ = mysql_fetch_array($database->doQuery('SELECT COUNT(nama) AS jData FROM fu_ajk_peserta WHERE id_cost="'.$metdn['id_cost'].'" AND id_polis="'.$metdn['id_nopol'].'" AND id_dn="'.$metdn['id'].'"  AND (status_peserta IS NULL OR status_peserta !="Batal") AND del IS NULL GROUP BY id_dn'));
$met_pesertaAS_ = mysql_fetch_array($database->doQuery('SELECT status_peserta,
															   IF(status_peserta="Batal","",SUM(nettpremi)) AS netpremi
														FROM fu_ajk_peserta_as
														WHERE id_bank="'.$metdn['id_cost'].'" AND
															  id_polis="'.$metdn['id_nopol'].'" AND
															  id_asuransi="'.$metdn['id_as'].'" AND
															  id_polis_as="'.$metdn['id_polis_as'].'" AND
															  id_dn="'.$metdn['id'].'"
														GROUP BY id_dn'));
$met_creditnote_ = mysql_fetch_array($database->doQuery('SELECT id, id_cn, type_claim, SUM(total_claim) AS tCLaim FROM fu_ajk_cn WHERE id_cost="'.$metdn['id_cost'].'" AND id_nopol="'.$metdn['id_nopol'].'" AND id_dn="'.$metdn['id'].'" GROUP BY id_dn'));


if ($met_creditnote_['id_cn']) {
$_nomorcn = $met_creditnote_['id_cn'];
$_typecn = $met_creditnote_['type_claim'];
$_nilaicn = duit($met_creditnote_['tCLaim']);
}else{
$_nomorcn = '';
$_nilaicn = '';
$_typecn = '';
}

if ($met_pesertaAS_['status_peserta']=="Batal") {
$_nilaiAsuransi = '';
}else{
$_nilaiAsuransi = $met_pesertaAS_['netpremi'];
}
$netpremiBank = $metdn['totalpremi'] - $met_creditnote_['tCLaim'];
if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	    <td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
	    <td>'.$metdn['perusahaan'].'</td>
	    <td>'.$met_asuransi_['name'].'</td>
	    <td>'.$metdn['nmproduk'].'</td>
	    <td align="right"><a href="ajk_asuransi.php?r=p_dn_detail&id='.$metdn['id'].'" target="_blank">'.$metdn['dn_kode'].'</a></td>
	    <td align="right">'.duit($met_peserta_['jData']).' Data</td>
	    <td align="center">'.duit($metdn['totalpremi']).'</td>
	    <td align="center">'._convertDate($metdn['tgl_createdn']).'</td>
	    <td align="center">'._convertDate($metdn['tglWPC']).'</td>
	    <td align="center">'.strtoupper($metdn['note_paid_date']).'</td>
	    <td align="center">'.$metdn['paid_total'].'</td>
	    <td align="center">'.$metdn['note_status'].'</td>
	    <td align="right"><b>'.duit($netpremiBank).'</b></td>
	    <td align="right"><b>'.duit($_nilaiAsuransi).'</b></td>
	    <td>'.$metdn['id_cabang'].'</td>
	    <td>'.$metdn['id_regional'].'</td>
	    <td align="center">';
		if(!is_null($metdn['note_paid_date'])){
			echo '<a href="ajk_asuransi.php?r=proses_dn&idn='.$metdn['id'].'" target="_blank"><img src="../image/edit.png" width="15"></a>';
		}else{
	    	echo '<a href="ajk_asuransi.php?r=proses_dn&idn='.$metdn['id'].'" target="_blank"><img src="../image/new.png" width="15"></a>';
		}
		echo '</td>
		<!--<td align="center">'.$statusbaru.'</td>-->';
		}
echo '<tr><td colspan="22">';
echo createPageNavigations($file = 'ajk_asuransi.php?r=p_dn&id_cost='.$_REQUEST['id_cost'].'&id_polis='.$_REQUEST['id_polis'].'&id_asuransi='.$_REQUEST['id_asuransi'].'&rdns='.$_REQUEST['rdns'].'&rdne='.$_REQUEST['rdne'].'&rpays='.$_REQUEST['rpays'].'&rpaye='.$_REQUEST['rpaye'].'&rreg='.$_REQUEST['rreg'].'&rcabang='.$_REQUEST['rcabang'].'&rdnno='.$_REQUEST['rdnno'].'&rstat='.$_REQUEST['rstat'].'&', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
//echo createPageNavigations($file = 'ajk_dn.php?r=viewdn&rpays='.$_REQUEST['rpays'].'&rpaye='.$_REQUEST['rpaye'].'&rreg='.$_REQUEST['rreg'].'&rcabang='.$_REQUEST['rcabang'].'&rdnno='.$_REQUEST['rdnno'].'&rstat='.$_REQUEST['rstat'].'&dns='.$_REQUEST['rdns'].'&dne='.$_REQUEST['rdne'].'&', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
echo '<b>Total Data Debit Note (DN): <u>' . $totalRows . '</u></b></td></tr>';
echo '</table>';
}

echo '<!--WILAYAH COMBOBOX-->
<script src="javascript/metcombo/prototype.js"></script>
<script src="javascript/metcombo/dynamicombo.js"></script>
<!--WILAYAH COMBOBOX-->
<script>
document.observe("dom:loaded",function(){
	new DynamiCombo( "id_cost" , {
		elements:{
			"id_polis":		{url:\'javascript/metcombo/data.php?req=setpoliscostumer\', value:\'id\', label:\'name\', init:\'<?php echo $_POST["id_polis"] ?>\'},
			"id_asuransi":	{url:\'javascript/metcombo/data.php?req=setpolisasuransi\', value:\'id\', label:\'name\', init:\'<?php echo $_POST["id_asuransi"] ?>\'},
		},
		loadingImage:\'loader1.gif\',
		loadingText:\'Loading...\',
		debug:0
	} )
});
</script>';
		;
		break;

case "p_dn_detail":
			echo '<table border="1" cellpadding="3" cellspacing="0" width="100%">
	  <tr><th width="95%" align="left" colspan="2">Modul DN Members </font></th><th><a href="ajk_asuransi.php?r=p_dn"><img src="image/back.png" width="20"></a></th></tr>
	  </table>
	  <form method="post" action="">';
			$fusdn = mysql_fetch_array($database->doQuery('SELECT
fu_ajk_costumer.`name` AS perusahaan,
fu_ajk_polis.nmproduk AS produk,
fu_ajk_asuransi.`name` AS asuransi,
fu_ajk_polis_as.nopol AS polisasuransi,
fu_ajk_dn.id,
fu_ajk_dn.id_cost,
fu_ajk_dn.id_nopol,
fu_ajk_dn.id_regional,
fu_ajk_dn.id_cabang,
fu_ajk_dn.dn_kode
FROM
fu_ajk_dn
LEFT JOIN fu_ajk_costumer ON fu_ajk_dn.id_cost = fu_ajk_costumer.id
LEFT JOIN fu_ajk_polis ON fu_ajk_dn.id_nopol = fu_ajk_polis.id
LEFT JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
LEFT JOIN fu_ajk_polis_as ON fu_ajk_dn.id_polis_as = fu_ajk_polis_as.id
WHERE
fu_ajk_dn.id = "'.$_REQUEST['id'].'"'));
		
			echo '<form method="post" action="#" onload ="onbeforeunload">
	  <table border="0" width="100%" cellpadding="3" cellspacing="1">
	  <tr><td colspan="2">Perusahaan </td><td colspan="5">: '.strtoupper($fusdn['perusahaan']).'</td></tr>
	  <tr><td colspan="2">Produk </td><td colspan="5">: '.strtoupper($fusdn['produk']).'</td></tr>
	  <tr><td colspan="2">Regional </td><td colspan="5">: '.$fusdn['id_regional'].'</td></tr>
	  <tr><td colspan="2">Cabang  </td><td colspan="5">: '.$fusdn['id_cabang'].'</td></tr>
	  <tr><td colspan="2">Asuransi  </td><td colspan="5">: '.$fusdn['asuransi'].'</td></tr>
	  <tr><td colspan="2">Nomor Polis </td><td colspan="5">: '.$fusdn['polisasuransi'].'</td></tr>
	  <tr><td colspan="2">Debit Note</td><td colspan="5">: '.$fusdn['dn_kode'].'</td>
	  	  
	  </tr>
<tr><th width="1%" rowspan="2">No</th>
	<th width="5%" rowspan="2">S P K</th>
	<th width="5%" rowspan="2">IDPeserta</th>
	<th rowspan="2">Nama Debitur<br /><font size="1"><i>(sesuai KTP/SIM)</i></font></th>
	<th width="1%" rowspan="2">P/W</th>
	<th rowspan="2" width="10%">Tgl Lahir</th>
	<th rowspan="2" width="1%">Usia</th>
	<th colspan="4" width="10%">Status Kredit</th>
	<th width="1%" rowspan="2">Premi</th>
	<th colspan="2" width="10%">Biaya</th>
	<th width="1%" rowspan="2">Total Premi</th>
	<th rowspan="2" width="1%">Medical</th>
	<th rowspan="2" width="1%">Status</th>
</tr>
<tr><th width="8%">Kredit Awal</th>
	<th width="1%">Tenor</th>
	<th width="8%">Kredit Akhir</th>
	<th>Jumlah</th>
	<th>Adm</th>
	<th>Ext. Premi</th>
</tr>';
		
			$data = $database->doQuery('SELECT *,ROUND(premi, 2) AS premi, ROUND(totalpremi, 2) AS totalpremi FROM fu_ajk_peserta WHERE id!="" AND id_dn="'.$fusdn['id'].'" AND id_cost="'.$fusdn['id_cost'].'" AND id_polis="'.$fusdn['id_nopol'].'" AND del IS NULL ORDER BY cabang ASC');
			$jumdata = mysql_num_rows($data);
			while ($fudata = mysql_fetch_array($data)) {
				if ($jumdata == 1) {
					// DISABLED (081013) $hapusdata = '<a href="ajk_dn.php?r=delUpddataDN&iddn='.$_REQUEST['id'].'&idP='.$fudata['id'].'" onClick="if(confirm(\'Anda yakin akan menghapus data peserta a/n '.addslashes($fudata['nama']).' (Reg.ID : '.$fudata['id_peserta'].') dan Penghapusan Nomor DN ?\')){return true;}{return false;}"><img src="image/deleted.png" width="20"></a>';	}
					$hapusdata = '<a href="#" title="penghapusan data tidak di ijinkan."><img src="image/deleted.png" width="20"></a>';
				}else{
					// DISABLED (081013) $hapusdata = '<a href="ajk_dn.php?r=delUpddata&iddn='.$_REQUEST['id'].'&idP='.$fudata['id'].'" onClick="if(confirm(\'Anda yakin akan menghapus data peserta a/n '.addslashes($fudata['nama']).' (Reg.ID : '.$fudata['id_peserta'].')?\')){return true;}{return false;}"><img src="image/deleted.png" width="20"></a>';	}
					$hapusdata = '<a href="#" title="penghapusan data tidak di ijinkan."><img src="image/deleted.png" width="20"></a>';
				}
		
				$met_note = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_note_as_detail WHERE id_peserta="'.$fudata['id_peserta'].'"'));
				if(is_null($met_note['status_pembayaran'])){
					$status='UNPAID';
				}else{
					$status='PAID ('.$met_note['status_pembayaran'].')';
				}
				$met_spk = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak WHERE id_cost="'.$fudata['id_cost'].'" AND id_polis="'.$fudata['id_polis'].'" AND spak="'.$fudata['spaj'].'"'));
				//$metTrate = $mametrate['rate'] * (1 - $met_spk['ext_premi'] / 100);
				$em = $fudata['premi'] * $met_spk['ext_premi'] / 100;
				$totalpreminya = $fudata['premi'] + $em;
				if (($no % 2) == 1)	$objlass = 'tbl-odd'; else $objlass = 'tbl-even';
				echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		<td align="center">'.++$no.'</td>
		<td align="center">'.$fudata['spaj'].'</td>
		<td>'.$fudata['id_peserta'].'</td>
		<td>'.$fudata['nama'].'</td>
		<td align="center">'.$fudata['gender'].'</td>
		<td align="center">'._convertDate($fudata['tgl_lahir']).'</td>
		<td align="center">'.$fudata['usia'].'</td>
		  <td align="center">'._convertDate($fudata['kredit_tgl']).'</td>
		  <td align="center">'.$fudata['kredit_tenor'].'</td>
		  <td align="center">'._convertDate($fudata['kredit_akhir']).'</td>
		  <td align="right">'.duit($fudata['kredit_jumlah']).'</td>
		<td align="right">'.duit($fudata['premi']).'</td>
		<td align="right">'.duit($fudata['biaya_adm']).'</td>
		<!--<td align="right">'.duit($fudata['biaya_refund']).'</td>
		<td align="right">'.duit($em).'</td>-->
		<td align="right">'.duit($fudata['ext_premi']).'</td>
		<td align="right">'.duit($fudata['totalpremi']).'</td>
		<td align="center">'.$fudata['status_medik'].'</td>
		<td align="center">'.$status.'</td>
		<!--<td align="center">'.$hapusdata.'</td>-->
		</tr>';
				$jkredit +=$fudata['kredit_jumlah'];
				$jpremi +=$fudata['premi'];
				$exjpremi +=$em;
				$jtpremi +=$fudata['totalpremi'];
		
			}
			echo '<tr><th colspan="10">Total</th>
	  	  <th>'.duit($jkredit).'</th>
	  	  <th>'.duit($jpremi).'</th><th>&nbsp;</th>
		  <th>'.duit($exjpremi).'</th>
	  	  <th>'.duit($jtpremi).'</th><th>&nbsp;</th><th>&nbsp;</th>
	  </tr></table>
	  </form>';
			//$upd = $database->doQuery('UPDATE fu_ajk_dn SET totalpremi="'.$jtpremi.'" WHERE id="'.$_REQUEST['id'].'"');
			;
			break;
case "proses_dn" :
	if(isset($_POST['simpandata']) && $_POST['simpandata']=="Simpan"){
		if (!$_REQUEST['tglbayar']) $error1 .= '<font color="red">Tanggal bayar tidak boleh kosong</font>.';
		if (!$_REQUEST['total']) $error2 .= '<font color="red"><blink>Pembayaran tidak bisa di proses, nilai bayar tidak boleh kosong!</blink></font>';
		if (!$_REQUEST['status_pembayaran']) $error3 .= '<font color="red"><blink>Status pembayaran tidak boleh kosong!</blink></font>';
		if (!$_REQUEST['referensi']) $error4 .= '<font color="red"><blink>Referensi bayar tidak boleh kosong!</blink></font>';
		
		if ($error1 OR $error2 OR $error3 OR $error4) {
		} else {

			$cnpremi_ = mysql_fetch_array($database->doQuery("SELECT id FROM fu_ajk_note_as where id_dn='" . $_REQUEST['idn'] . "' ORDER BY id DESC"));
			
			
			$met_upload_data1 = $database->doQUery("update fu_ajk_dn set tgl_bayar_asuransi='".$_REQUEST['tgl_bayar']."', total_bayar_asuransi='".$_REQUEST['total_bayar']."' where id='" . $_REQUEST['idn'] . "'");
			$met_upload_data2 = $database->doQUery("update fu_ajk_note_as set note_paid_date='".$_REQUEST['tglbayar']."', note_paid_total='".$_REQUEST['total']."',note_status='".$_REQUEST['status_pembayaran']."', note_reference='".$_REQUEST['referensi']."', note_desc='".$_REQUEST['ket']."' where id='" . $cnpremi_['id'] . "'");
			$met_upload_data2 = $database->doQUery("update fu_ajk_note_as_detail set note_paid_date='".$_REQUEST['tglbayar']."', note_paid_total=note_total,note_status='".$_REQUEST['status_pembayaran']."', note_reference='".$_REQUEST['referensi']."', note_desc='".$_REQUEST['ket']."' where id_note='" . $cnpremi_['id'] . "'");
				
			header("location:ajk_asuransi.php?r=p_dn");
			exit();
		}
		
		
	}
	echo '<table border="1" cellpadding="3" cellspacing="0" width="100%">
	  <tr><th width="95%" align="left" colspan="2">Modul Pembayaran Premi Ke Asuransi</font></th><th><a href="ajk_asuransi.php?r=p_dn"><img src="image/back.png" width="20"></a></th></tr>
	  </table>
	  <form method="post" action="">';
	$fusdn = mysql_fetch_array($database->doQuery('SELECT
		fu_ajk_costumer.`name` AS perusahaan,
		fu_ajk_polis.nmproduk AS produk,
		fu_ajk_asuransi.`name` AS asuransi,
		fu_ajk_polis_as.nopol AS polisasuransi,
		fu_ajk_dn.*
		FROM
		fu_ajk_dn
		LEFT JOIN fu_ajk_costumer ON fu_ajk_dn.id_cost = fu_ajk_costumer.id
		LEFT JOIN fu_ajk_polis ON fu_ajk_dn.id_nopol = fu_ajk_polis.id
		LEFT JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
		LEFT JOIN fu_ajk_polis_as ON fu_ajk_dn.id_polis_as = fu_ajk_polis_as.id
		WHERE
		fu_ajk_dn.id = "'.$_REQUEST['idn'].'"'));
	
	$fuscn_as = mysql_fetch_array($database->doQuery('SELECT
		* from
		fu_ajk_note_as
		WHERE
		fu_ajk_note_as.id_dn = "'.$_REQUEST['idn'].'"'));
	
	echo '<form method="post" action="#" onload ="onbeforeunload">
	  <table border="0" width="100%" cellpadding="3" cellspacing="1">
	  <tr><td colspan="2">Perusahaan </td><td colspan="5">: '.strtoupper($fusdn['perusahaan']).'</td></tr>
	  <tr><td colspan="2">Produk </td><td colspan="5">: '.strtoupper($fusdn['produk']).'</td><td align="left" colspan="5" style="background : orange; border: 1px solid #666666; font-size: 11px; font-weight: bold;">UPDATE PEMBAYARAN PREMI KE ASURANSI</font></td></tr>
	  <tr><td colspan="2">Regional </td><td colspan="5">: '.$fusdn['id_regional'].'</td><td colspan="2">Jumlah Bayar Ke Asuransi  </td><td colspan="5">: <input type="text" name="total" value="'.$fuscn_as['note_total'].'">'.$error2.'</td></tr>
	  <tr><td colspan="2">Cabang  </td><td colspan="5">: '.$fusdn['id_cabang'].'</td><td colspan="2">Tgl. Bayar Ke Asuransi </td><td colspan="5">: ';print initCalendar();	print calendarBox('tglbayar', 'triger2', $datelog); echo $error1.'</td></tr>
	  <tr><td colspan="2">Asuransi  </td><td colspan="5">: '.$fusdn['asuransi'].'</td><td colspan="2">Referensi Bayar</td><td colspan="5">: <input type="text" name="referensi" value="'.$fuscn_as['note_reference'].'">'.$error4.'</td></tr>
	  <tr><td colspan="2">Nomor Polis </td><td colspan="5">: '.$fusdn['polisasuransi'].'</td><td colspan="2">Keterangan </td><td colspan="5">: <textarea name="ket" cols="40" rows="2">'.$fuscn_as['note_desc'].'</textarea></td></tr>
	  <tr><td colspan="2">Debit Note</td><td colspan="5">: '.$fusdn['dn_kode'].'</td><td colspan="2">Status Pemabayaran</td><td colspan="5">: 
	  		<select size="1" name="status_pembayaran" style="visibility: visible;">
		   	<option value="">---Status Pembayaran---</option>';
			$nmPenyakit = $database->doQuery('SELECT * FROM fu_ajk_pembayaran_status ORDER BY id ASC');
			while ($nmPenyakit_ = mysql_fetch_array($nmPenyakit)) {
				echo '<option value="' . $nmPenyakit_['id'] . '"' . _selected($_REQUEST['status_pembayaran'], $nmPenyakit_['id']) . '>' . $nmPenyakit_['pembayaran_status'] . '</option>';
			}
			echo '</select>'.$error3.'</td></tr>
	  <tr><td colspan="2">Total</td><td colspan="5">: '.duit($fusdn['totalpremi']).'</td><td colspan="2">Attachment</td><td colspan="5">: <input name="userfile" type="file" size="50" onchange="checkfile(this);" ></td>
	  <tr><td colspan="2">Tgl Debitnote</td><td colspan="5">: '.$fusdn['tgl_createdn'].'</td>
	  		<td colspan="3" align="right"><input type="submit" name="simpandata" value="Simpan" class="button"></td>
	  </tr><tr><td style="margin : 10px;"></td></tr>
<tr><th width="1%" rowspan="2">No</th>
	<th width="5%" rowspan="2">S P K</th>
	<th width="5%" rowspan="2">IDPeserta</th>
	<th rowspan="2">Nama Debitur<br /><font size="1"><i>(sesuai KTP/SIM)</i></font></th>
	<th width="1%" rowspan="2">P/W</th>
	<th rowspan="2" width="10%">Tgl Lahir</th>
	<th rowspan="2" width="1%">Usia</th>
	<th colspan="4" width="10%">Status Kredit</th>
	<th width="1%" rowspan="2">Premi</th>
	<th colspan="2" width="10%">Biaya</th>
	<th width="1%" rowspan="2">Total Premi</th>
	<th rowspan="2" width="1%">Medical</th>
	<th rowspan="2" width="1%">Status</th>
</tr>
<tr><th width="8%">Kredit Awal</th>
	<th width="1%">Tenor</th>
	<th width="8%">Kredit Akhir</th>
	<th>Jumlah</th>
	<th>Adm</th>
	<th>Ext. Premi</th>
</tr>';
	
	$data = $database->doQuery('SELECT *,ROUND(premi, 2) AS premi, ROUND(totalpremi, 2) AS totalpremi FROM fu_ajk_peserta WHERE id!="" AND id_dn="'.$fusdn['id'].'" AND id_cost="'.$fusdn['id_cost'].'" AND id_polis="'.$fusdn['id_nopol'].'" AND del IS NULL ORDER BY cabang ASC');
	$jumdata = mysql_num_rows($data);
	while ($fudata = mysql_fetch_array($data)) {
		if ($jumdata == 1) {
			// DISABLED (081013) $hapusdata = '<a href="ajk_dn.php?r=delUpddataDN&iddn='.$_REQUEST['id'].'&idP='.$fudata['id'].'" onClick="if(confirm(\'Anda yakin akan menghapus data peserta a/n '.addslashes($fudata['nama']).' (Reg.ID : '.$fudata['id_peserta'].') dan Penghapusan Nomor DN ?\')){return true;}{return false;}"><img src="image/deleted.png" width="20"></a>';	}
			$hapusdata = '<a href="#" title="penghapusan data tidak di ijinkan."><img src="image/deleted.png" width="20"></a>';
		}else{
			// DISABLED (081013) $hapusdata = '<a href="ajk_dn.php?r=delUpddata&iddn='.$_REQUEST['id'].'&idP='.$fudata['id'].'" onClick="if(confirm(\'Anda yakin akan menghapus data peserta a/n '.addslashes($fudata['nama']).' (Reg.ID : '.$fudata['id_peserta'].')?\')){return true;}{return false;}"><img src="image/deleted.png" width="20"></a>';	}
			$hapusdata = '<a href="#" title="penghapusan data tidak di ijinkan."><img src="image/deleted.png" width="20"></a>';
		}
		$met_note = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_note_as_detail WHERE id_peserta="'.$fudata['id_peserta'].'"'));
		if(is_null($met_note['status_pembayaran'])){
			$status='UNPAID';
		}else{
			$status='PAID ('.$met_note['status_pembayaran'].')';
		}
	
		$met_spk = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak WHERE id_cost="'.$fudata['id_cost'].'" AND id_polis="'.$fudata['id_polis'].'" AND spak="'.$fudata['spaj'].'"'));
		//$metTrate = $mametrate['rate'] * (1 - $met_spk['ext_premi'] / 100);
		$em = $fudata['premi'] * $met_spk['ext_premi'] / 100;
		$totalpreminya = $fudata['premi'] + $em;
		if (($no % 2) == 1)	$objlass = 'tbl-odd'; else $objlass = 'tbl-even';
		echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		<td align="center">'.++$no.'</td>
		<td align="center">'.$fudata['spaj'].'</td>
		<td>'.$fudata['id_peserta'].'</td>
		<td>'.$fudata['nama'].'</td>
		<td align="center">'.$fudata['gender'].'</td>
		<td align="center">'._convertDate($fudata['tgl_lahir']).'</td>
		<td align="center">'.$fudata['usia'].'</td>
		  <td align="center">'._convertDate($fudata['kredit_tgl']).'</td>
		  <td align="center">'.$fudata['kredit_tenor'].'</td>
		  <td align="center">'._convertDate($fudata['kredit_akhir']).'</td>
		  <td align="right">'.duit($fudata['kredit_jumlah']).'</td>
		<td align="right">'.duit($fudata['premi']).'</td>
		<td align="right">'.duit($fudata['biaya_adm']).'</td>
		<!--<td align="right">'.duit($fudata['biaya_refund']).'</td>
		<td align="right">'.duit($em).'</td>-->
		<td align="right">'.duit($fudata['ext_premi']).'</td>
		<td align="right">'.duit($fudata['totalpremi']).'</td>
		<td align="center">'.$fudata['status_medik'].'</td>
		<td align="center">'.$status.'</td>
		<!--<td align="center">'.$hapusdata.'</td>-->
		</tr>';
		$jkredit +=$fudata['kredit_jumlah'];
		$jpremi +=$fudata['premi'];
		$exjpremi +=$em;
		$jtpremi +=$fudata['totalpremi'];
	
	}
	echo '<tr><th colspan="10">Total</th>
	  	  <th>'.duit($jkredit).'</th>
	  	  <th>'.duit($jpremi).'</th><th>&nbsp;</th>
		  <th>'.duit($exjpremi).'</th>
	  	  <th>'.duit($jtpremi).'</th><th>&nbsp;</th><th>&nbsp;</th>
	  </tr></table>
	  </form>';
	//$upd = $database->doQuery('UPDATE fu_ajk_dn SET totalpremi="'.$jtpremi.'" WHERE id="'.$_REQUEST['id'].'"');
	;
	break;

case "p_peserta" :

	echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
	  <tr><th align="left">Modul Update Pembayaran Premi Ke Asuransi Per Peserta</th></tr>
	  </table>';
	if ($_REQUEST['e']=="paid") {
		$metpaidpeserta = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id="'.$_REQUEST['idp'].'"'));
		if ($_REQUEST['pd']=="updatebyr") {
			if (!$_REQUEST['tglbayar']) $error1 .= '<font color="red">Tanggal bayar tidak boleh kosong</font>.';
			if (!$_REQUEST['total']) $error2 .= '<font color="red"><blink>Pembayaran tidak bisa di proses, nilai bayar tidak boleh kosong!</blink></font>';
			if (!$_REQUEST['status_pembayaran']) $error3 .= '<font color="red"><blink>Status pembayaran tidak boleh kosong!</blink></font>';
			if (!$_REQUEST['referensi']) $error4 .= '<font color="red"><blink>Referensi bayar tidak boleh kosong!</blink></font>';
			
			if ($error1 OR $error2 OR $error3 OR $error4) {
				} else {
			
				$met_upload_data2 = $database->doQUery("update fu_ajk_note_as_detail set note_paid_date='".$_REQUEST['tglbayar']."', note_paid_total=note_total,note_status='".$_REQUEST['status_pembayaran']."', note_reference='".$_REQUEST['referensi']."', note_desc='".$_REQUEST['ket']."' where id_peserta='".$_REQUEST['idp']."'");
				
				$cnpremi_ = mysql_fetch_array($database->doQuery("SELECT fu_ajk_note_as.* 
						FROM
						fu_ajk_note_as
						INNER JOIN fu_ajk_note_as_detail 
						where fu_ajk_note_as_detail.id_peserta='".$_REQUEST['idp']."' ORDER BY fu_ajk_note_as.id DESC"));
				
				//$total=duit($cnpremi_['note_total'])+$_REQUEST['total'];
				//$met_upload_data1 = $database->doQUery("update fu_ajk_dn set tgl_bayar_asuransi='".$_REQUEST['tgl_bayar']."', total_bayar_asuransi='".$_REQUEST['total_bayar']."' where id='" . $_REQUEST['idn'] . "'");
				//$met_upload_data3 = $database->doQUery("update fu_ajk_note_as set note_paid_date='".$_REQUEST['tglbayar']."', note_paid_total='".$total."',note_status='".$_REQUEST['status_pembayaran']."', note_reference='".$_REQUEST['referensi']."', note_desc='".$_REQUEST['ket']."' where id='" . $cnpremi_['id'] . "'");
				$met_upload_data3 = $database->doQUery("
						UPDATE
						fu_ajk_note_as
						SET
						note_paid_date='".$_REQUEST['tglbayar']."', 
						note_total=(SELECT SUM(CAST(note_total AS DECIMAL(10,6))) FROM fu_ajk_note_as_detail WHERE id_note='".$cnpremi_['id']."'),
						note_status='".$_REQUEST['status_pembayaran']."', 
						note_reference='".$_REQUEST['referensi']."', 
						note_desc='".$_REQUEST['ket']."'
						WHERE id='".$cnpremi_['id']."'
						");
				
				
				header("location:ajk_asuransi.php?r=p_peserta");
				exit();
			}
		}
		$supesertaku_=mysql_fetch_array($database->doQuery('
				SELECT
				fu_ajk_peserta.nama,
				fu_ajk_peserta.id_peserta as idpeserta,
				fu_ajk_note_as_detail.*
				FROM
				fu_ajk_peserta
				LEFT JOIN fu_ajk_note_as_detail
				ON (fu_ajk_note_as_detail.id_peserta = fu_ajk_peserta.id_peserta)
				where fu_ajk_peserta.id="'.$_REQUEST['idp'].'"
				'));
		
		
		
		echo '<br><table border="0" width="50%" cellpadding="1" cellspacing="1">
		  <form method="post" action="">
		  <input type="hidden" name="idpaid" value="'.$metpaidpeserta['id'].'">
		  <tr><td></td><td colspan="1">ID Peserta</td><td colspan="5">: <strong>'.$supesertaku_['idpeserta'].'</strong></td></tr>
		  <tr><td></td><td colspan="1">Nama Peserta</td><td colspan="5">: <strong>'.$supesertaku_['nama'].'</strong><br></td></tr>
		  
		  <tr><td></td><td colspan="1">Jumlah Bayar Ke Asuransi  </td><td colspan="5">: <input type="text" name="total" value="'.$supesertaku_['note_total'].'">'.$error2.'</td></tr>
		  <tr><td></td><td colspan="1">Tgl. Bayar Ke Asuransi </td><td colspan="5">: ';print initCalendar();	print calendarBox('tglbayar', 'triger2', $datelog); echo $error1.'</td></tr>
		  <tr><td></td><td colspan="1">Referensi Bayar</td><td colspan="5">: <input type="text" name="referensi" value="'.$supesertaku_['note_reference'].'">'.$error4.'</td></tr>
		  <tr><td></td><td colspan="1">Keterangan </td><td colspan="5">: <textarea name="ket" cols="40" rows="2">'.$supesertaku_['note_desc'].'</textarea></td></tr>
		  <tr><td></td><td colspan="1">Status Pemabayaran</td><td colspan="5">: 
	  		<select size="1" name="status_pembayaran" style="visibility: visible;">
		   	<option value="">---Status Pembayaran---</option>';
			$nmPenyakit = $database->doQuery('SELECT * FROM fu_ajk_pembayaran_status ORDER BY id ASC');
			while ($nmPenyakit_ = mysql_fetch_array($nmPenyakit)) {
				echo '<option value="' . $nmPenyakit_['id'] . '"' . _selected($_REQUEST['status_pembayaran'], $nmPenyakit_['id']) . '>' . $nmPenyakit_['pembayaran_status'] . '</option>';
			}
			echo '</select>'.$error3.'</td></tr>
	  <tr><td></td><td colspan="1">Attachment</td><td colspan="5">: <input name="userfile" type="file" size="50" onchange="checkfile(this);" ></td>
	  <tr></td>
	  <tr><td></td><td colspan="1" align="left"></td><td colspan="1"><input type="hidden" name="pd" value="updatebyr" class="button">
					<input type="submit" name="button" value="Update" class="button"> &nbsp; <a href="ajk_asuransi.php?r=p_peserta" title="Batalkan pembayaran"><img src="../image/deleted.png" width="18"></a></td></tr>
	  </form></table><br>';
	}else{
		echo '<table border="0" width="100%" cellpadding="1" cellspacing="1">
	  <form method="post" action="">
	  <tr><td width="10%">ID Peserta</td><td>: <input type="text" name="idnya" value="'.$_REQUEST['idnya'].'"></td></tr>
	  <tr><td>Nama Peserta</td><td>: <input type="text" name="namanya" value="'.$_REQUEST['namanya'].'"></td></tr>
	  <tr><td colspan="2"> <input type="submit" name="button" value="Cari" class="button"></td></tr>
	  </form></table>';
	}
	echo '<table border="0" width="100%" cellpadding="5" cellspacing="1" bgcolor="#bde0e6">
	<tr><th width="2%">No</th>
		<th>Nama Perusahaan</th>
		<th width="6%">Asuransi</th>
		<th width="6%">Produk</th>
		<th width="5%">DN Number</th>
		<th width="7%">Tgl DN</th>
		<th width="5%">ID Peserta</th>
		<th>Nama</th>
		<th width="7%">Tgl Lahir</th>
		<th width="10%">T.Premi</th>
		<th width="1%">Status Pembayaran</th>
		<th width="1%">Total Bayar Dari Asuransi</th>
		<th width="1%">Tanggal Bayar Dari Asuransi</th>
		<th width="10%">Cabang</th>
		<th width="1%">Option</th>
	</tr>';
	
	if ($_REQUEST['idnya'])		{	$satu = 'AND id_peserta = "' . $_REQUEST['idnya'] . '"';		}
	if ($_REQUEST['namanya'])	{	$dua = 'AND nama LIKE "%' . $_REQUEST['namanya'] . '%"';		}
	
	if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 50;	}	else {	$m = 0;		}
	$paidpeserta = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id != "" AND status_bayar="0" '.$satu.' '.$dua.' AND del IS NULL ORDER BY input_time DESC LIMIT ' . $m . ' , 50');
	$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_peserta WHERE id != "" AND status_bayar="0" '.$satu.' '.$dua.' AND del IS NULL '));
	$totalRows = $totalRows[0];
	$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
	while ($paidpeserta_ = mysql_fetch_array($paidpeserta)) {
		$metperusahaan = mysql_fetch_array($database->doQuery('SELECT id,name FROM fu_ajk_costumer WHERE id="'.$paidpeserta_['id_cost'].'"'));
		$metpolis = mysql_fetch_array($database->doQuery('SELECT id,nmproduk FROM fu_ajk_polis WHERE id="'.$paidpeserta_['id_polis'].'"'));
		$metdebitnote = mysql_fetch_array($database->doQuery('SELECT id,dn_kode, tgl_createdn FROM fu_ajk_dn WHERE id="'.$paidpeserta_['id_dn'].'"'));
		$metAsuransi = mysql_fetch_array($database->doQuery('SELECT id_bank, id_polis, id_asuransi, id_polis_as, id_peserta FROM fu_ajk_peserta_as WHERE id_peserta="'.$paidpeserta_['id_peserta'].'" AND id_dn="'.$paidpeserta_['id_dn'].'" AND id_bank="'.$paidpeserta_['id_cost'].'" AND id_polis="'.$paidpeserta_['id_polis'].'"'));
		$metAsuransi_ = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_asuransi WHERE id="'.$metAsuransi['id_asuransi'].'"'));
		$suNote_=mysql_fetch_array($database->doQuery('select * from fu_ajk_note_as_detail where id_peserta="'.$paidpeserta_['id'].'"'));
		
		if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
		echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		<td align="center">'.(++$no + ($pageNow-1) * 50).'</td>
		<td>'.$metperusahaan['name'].'</td>
		<td>'.$metAsuransi_['name'].'</td>
		<td align="center">'.$metpolis['nmproduk'].'</td>
		<td align="right">'.$metdebitnote['dn_kode'].'</td>
		<td align="center">'._convertDate($metdebitnote['tgl_createdn']).'</td>
		<td align="center">'.$paidpeserta_['id_peserta'].'</td>
		<td>'.$paidpeserta_['nama'].'</td>
		<td align="right">'._convertDate($paidpeserta_['tgl_lahir']).'</td>
		<td align="right">'.duit($paidpeserta_['totalpremi']).'</td>
		<td>';
			if(is_null($suNote_['status_pembayaran'])){
				echo 'UNPAID';
			}else{
				echo $suNote_['status_pembayaran'];
			}
		echo '</td>
		<td>'.$suNote_['note_paid_date'].'</td>
		<td>'.$suNote_['note_paid_total'].'</td>
		<td>'.$paidpeserta_['cabang'].'</td>
		<td align="center">';
		if(is_null($suNote_['status_pembayaran'])){
			echo '<a href="ajk_asuransi.php?r=p_peserta&e=paid&idp='.$paidpeserta_['id'].'"><img src="image/check.png" width="25"></a></td>';
		}
		
		echo '</tr>';
	}
	echo '<tr><td colspan="22">';
	echo createPageNavigations($file = 'ajk_asuransi.php?r=p_peserta&', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 50);
	echo '<b>Total Data Peserta Unpaid: <u>' . duittanpakoma($totalRows) . '</u></b></td></tr>';
	echo '</table>';
	;
	break;
}