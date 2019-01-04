<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ADONAI - AJK Online 2014
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("../includes/functions.php");
connect();
if (session_is_registered('nm_user')) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));	}
switch ($_REQUEST['dok']) {
	case "s":
		;
		break;
	case "cl":
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%"><tr><th width="95%" align="left">Modul Laporan Covering Letter</font></th></tr></table>';
		$metcost = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name DESC');
echo '<form method="post" action="" name="postform">
	  <table border="0" cellpadding="1" cellspacing="0" width="100%">
		<tr><td width="40%" align="right">Nama Perusahaan <font color="red">*</font></td><td> : <select name="id_cost" id="id_cost">
		<option value="">---Pilih Perusahaan---</option>';
while($metcost_ = mysql_fetch_array($metcost)) {
	echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['id_cost'], $metcost_['id']).'>'.$metcost_['name'].'</option>';
}
echo '</select></td></tr>
		<tr><td align="right">Nama Produk</td>
			<td>: <select name="nmProd" id="nmProd">
				<option value="">-- Nama Produk --</option>
				</select></td></tr>
		<tr><td align="right">Tanggal Debitnote <font color="red">*</font></td>
			<td> :';
echo '<input type="text" id="from" name="tglcek1" value="'.$_REQUEST['tglcek1'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.from);return false;"/>
	  <a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.from);return false;">
	  <img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
	  <iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
	  s/d
	  <input type="text" id="from1" name="tglcek2" value="'.$_REQUEST['tglcek2'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.from1);return false;"/>
	  <a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.from1);return false;">
	  <img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
	  <iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>';
//print initCalendar();	print calendarBox('tglcek1', 'triger1', $_REQUEST['tglcek1']);	echo 's/d';
//print initCalendar();	print calendarBox('tglcek2', 'triger2', $_REQUEST['tglcek2']);
echo '</td></tr>
		<tr><td align="center"colspan="2"><input type="hidden" name="re" value="dataCL"><input type="submit" name="ere" value="Cari"></td></tr>
		</table>
		</form>';
if ($_REQUEST['re']=="dataCL") {
if ($_REQUEST['id_cost']=="") 	{	$error_1 = '<div align="center"><font color="red"><blink>Silahkan pilih nama perusahaan...!!<br /></div></font></blink>';	}
if ($_REQUEST['tglcek1']=="") 	{	$error_2 = '<div align="center"><font color="red"><blink>Tanggal mulai debitnote tidak boleh kosong<br /></div></font></blink>';	}
if ($_REQUEST['tglcek2']=="") 	{	$error_3 = '<div align="center"><font color="red"><blink>Tanggal akhir debitnote tidak boleh kosong</div></font></blink>';	}
if ($error_1 OR $error_2 OR $error_3) {	echo $error_1 .''.$error_2.''.$error_3;	}
else{
if ($_REQUEST['id_cost'])		{	$satu = 'AND fu_ajk_dn.id_cost = "'.$_REQUEST['id_cost'].'"';	}
if ($_REQUEST['nmProd'])		{	$tiga = 'AND fu_ajk_dn.id_nopol = "'.$_REQUEST['nmProd'].'"';	}
if ($_REQUEST['tglcek1'])		{	$duaa = 'AND fu_ajk_dn.tgl_createdn BETWEEN "'.$_REQUEST['tglcek1'].'" AND "'.$_REQUEST['tglcek2'].'" ';	}

$metCabang = $database->doQuery('SELECT
fu_ajk_polis.nmproduk,
fu_ajk_dn.id_cabang,
COUNT(fu_ajk_dn.dn_kode) AS jDN,
fu_ajk_dn.tgl_createdn,
fu_ajk_peserta.spaj,
fu_ajk_peserta.nama,
SUM(fu_ajk_peserta.premi) AS tPremi,
SUM(fu_ajk_peserta.ext_premi) AS tEM,
SUM(fu_ajk_peserta.totalpremi) AS tTotalpremi

FROM fu_ajk_peserta
 LEFT JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id
 LEFT JOIN fu_ajk_polis ON fu_ajk_polis.id = fu_ajk_dn.id_nopol
/*
fu_ajk_dn
INNER JOIN fu_ajk_peserta ON fu_ajk_dn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_dn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_dn.id = fu_ajk_peserta.id_dn
LEFT JOIN fu_ajk_polis ON fu_ajk_peserta.id_polis = fu_ajk_polis.id
*/
WHERE fu_ajk_dn.id != "" AND fu_ajk_peserta.id IS NOT NULL AND fu_ajk_dn.del IS NULL AND fu_ajk_peserta.del IS NULL '.$satu.' '.$duaa.' '.$tiga.'
GROUP BY fu_ajk_dn.id_cabang
ORDER BY fu_ajk_dn.id_cabang ASC, fu_ajk_dn.dn_kode ASC');

echo '<table border="0" width="100%" cellpadding="3" cellspacing="1">
	<tr><td colspan="8"><a title="print covering letter semua cabang '.strtolower($metCabang_['id_cabang']).'" href="e_report.php?er=eL_PrintCoveringLetterALLCab&id_cost='.$_REQUEST['id_cost'].'&nmProd='.$_REQUEST['nmProd'].'&cbg='.$metCabang_['id_cabang'].'&tgl1='.$_REQUEST['tglcek1'].'&tgl2='.$_REQUEST['tglcek2'].'" target="_blank"><img src="image/print.png" width="22" border="0"> All Cabang</a></td></tr>
	<tr><th width="1%">No</td>
		<th>Cabang</td>
		<th width="20%">Produk</td>
		<th width="10%">Jumlah Peserta</td>
		<th width="10%">Premi</td>
		<th width="10%">Extre Premi</td>
		<th width="10%">Total Premi</td>
		<th width="10%">Option</td>
	</tr>';
while ($metCabang_ = mysql_fetch_array($metCabang)) {
if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
if ($_REQUEST['nmProd']=="") {	$produknya = 'SEMUA PRODUK';	}else{	$produknya = $metCabang_['nmproduk'];	}
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		  <td align="center" width="5%">'.++$no.'</td>
		  <td>'.$metCabang_['id_cabang'].'</td>
		  <td align="center">'.$produknya.'</td>
		  <td align="center">'.duit($metCabang_['jDN']).'</td>
		  <td align="right">'.duit($metCabang_['tPremi']).'</td>
		  <td align="right">'.duit($metCabang_['tEM']).'</td>
		  <td align="right">'.duit($metCabang_['tTotalpremi']).'</td>
		  <!--<td align="center"><a href="e_report.php?er=eL_CoveringLetter&id_cost='.$_REQUEST['id_cost'].'&nmProd='.$_REQUEST['nmProd'].'&cbg='.$metCabang_['id_cabang'].'&tgl1='.$_REQUEST['tglcek1'].'&tgl2='.$_REQUEST['tglcek2'].'"><img src="image/excel.png" width="25" border="0"></a></td>-->
		  <td align="center"><a title="print covering letter peserta cabang '.strtolower($metCabang_['id_cabang']).'" href="e_report.php?er=eL_PrintCoveringLetter&id_cost='.$_REQUEST['id_cost'].'&nmProd='.$_REQUEST['nmProd'].'&cbg='.$metCabang_['id_cabang'].'&tgl1='.$_REQUEST['tglcek1'].'&tgl2='.$_REQUEST['tglcek2'].'" target="_blank"><img src="image/print.png" width="22" border="0"></a> &nbsp;
							 <a title="print covering letter peserta cabang '.strtolower($metCabang_['id_cabang']).'" href="e_report.php?er=eL_PrintCoveringLetterPDF&id_cost='.$_REQUEST['id_cost'].'&nmProd='.$_REQUEST['nmProd'].'&cbg='.$metCabang_['id_cabang'].'&tgl1='.$_REQUEST['tglcek1'].'&tgl2='.$_REQUEST['tglcek2'].'" target="_blank"><img src="image/dninvoice1.jpg" width="22" border="0"></a></td>
	  </tr>';
	$jNama_ += $metCabang_['jDN'];
	$jPremi_ += $metCabang_['tPremi'];
	$jNilaiEM_ += $metCabang_['tEM'];
	$jTPremi_ += $metCabang_['tTotalpremi'];
}
echo '<tr><td colspan="3"><b>TOTAL</b></td>
	  <td align="center"><b>'.duit($jNama_).' Peserta</b></td>
	  <td align="right"><b>'.duit($jPremi_).'</b></td>
	  <td align="right"><b>'.duit($jNilaiEM_).'</b></td>
	  <td align="right"><b>'.duit($jTPremi_).'</b></td>
	</tr>
	</table>';
}
}
echo '<!--WILAYAH COMBOBOX-->
<script src="javascript/metcombo/prototype.js"></script>
<script src="javascript/metcombo/dynamicombo.js"></script>
<!--WILAYAH COMBOBOX-->
<script>
document.observe("dom:loaded",function(){
	new DynamiCombo( "id_cost" , {
		elements:{
			"nmProd":{url:\'javascript/metcombo/data.php?req=setpoliscostumer\', value:\'id\', label:\'name\', init:\'<?php echo $_POST["nmProd"] ?>\'},
		},
		loadingImage:\'../loader1.gif\',
		loadingText:\'Loading...\',
		debug:0
	} )
});
</script>';
		;
		break;
	default:
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%"><tr><th width="95%" align="left">Modul Laporan Rekapitulasi Dokter</font></th></tr></table>';
$metcost = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name DESC');
echo '<form method="post" action="" name="postform">
	  <input type="hidden" name="id_cost" value="'.$metcost['id'].'">
	  <table border="0" cellpadding="1" cellspacing="0" width="100%">
      <tr><td width="40%" align="right">Nama Perusahaan <font color="red">*</font></td><td> : <select name="id_cost" id="id_cost">
		  	<option value="">---Pilih Perusahaan---</option>';
while($metcost_ = mysql_fetch_array($metcost)) {
echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['id_cost'], $metcost_['id']).'>'.$metcost_['name'].'</option>';
	}
echo '</select></td></tr>
	  <tr><td align="right">Nama Dokter</td>
		<td>: <select name="nm_dok" id="nm_dok">
		<option value="">-- Nama Dokter --</option>
		</select></td></tr>
	  <tr><td align="right">Tanggal Input SPK <font color="red">*</font></td>
	  	  <td> :';
echo '<input type="text" id="from" name="tglcek1" value="'.$_REQUEST['tglcek1'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.from);return false;"/>
	  <a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.from);return false;">
	  <img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
	  <iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
	  s/d
	  <input type="text" id="from1" name="tglcek2" value="'.$_REQUEST['tglcek2'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.from1);return false;"/>
	  <a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.from1);return false;">
	  <img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
	  <iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>';
//print initCalendar();	print calendarBox('tglcek1', 'triger1', $_REQUEST['tglcek1']);	echo 's/d';
//print initCalendar();	print calendarBox('tglcek2', 'triger2', $_REQUEST['tglcek2']);
echo '</td></tr>
	  <tr><td align="center"colspan="2"><input type="hidden" name="re" value="datadokter"><input type="submit" name="ere" value="Cari"></td></tr>
	  </table>
	  </form>';
if ($_REQUEST['re']=="datadokter") {
if ($_REQUEST['id_cost']=="") 	{	$error_1 = '<div align="center"><font color="red"><blink>Silahkan pilih nama perusahaan...!!<br /></div></font></blink>';	}
if ($_REQUEST['tglcek1']=="") 	{	$error_2 = '<div align="center"><font color="red"><blink>Tanggal mulai periksa tidak boleh kosong<br /></div></font></blink>';	}
if ($_REQUEST['tglcek2']=="") 	{	$error_3 = '<div align="center"><font color="red"><blink>Tanggal akhir periksa tidak boleh kosong</div></font></blink>';	}
if ($error_1 OR $error_2 OR $error_3) {	echo $error_1 .''.$error_2.''.$error_3;	}
else{
echo '<table border="0" width="100%" cellpadding="3" cellspacing="1"  bgcolor="#E2E2E2">
			<tr><td bgcolor="#FFF"colspan="23"><a href="e_report.php?er=eL_dok&cat='.$_REQUEST['id_cost'].'&nm_dok='.$_REQUEST['nm_dok'].'&tgl1='.$_REQUEST['tglcek1'].'&tgl2='.$_REQUEST['tglcek2'].'"><img src="image/excel.png" width="25" border="0"><br />Excel</a></td></tr>
			<th width="1%">No</th>
			<th>Nama Dokter</th>
			<th>Nama Debitur</th>
			<th width="10%">No. SPK</th>
			<th width="10%">Tanggal Pemeriksaan</th>
			<th width="10%">Premi</th>
			<th width="1%">EM(%)</th>
			<th width="10%">Nilai EM</th>
			<th width="10%">Total Premi</th>
			<th width="1%">Status</th>
			<th>Cabang</th>
			</tr>';
if ($_REQUEST['id_cost'])		{	$satu = 'AND fu_ajk_spak.id_cost = "'.$_REQUEST['id_cost'].'"';	}
if ($_REQUEST['nm_dok'])		{	$tiga = 'AND fu_ajk_spak_form.dokter_pemeriksa = "'.$_REQUEST['nm_dok'].'"';	}
if ($_REQUEST['tglcek1'])		{	$duaa = 'AND DATE_FORMAT(fu_ajk_spak.input_date, "%Y-%m-%d") BETWEEN "'.$_REQUEST['tglcek1'].'" AND "'.$_REQUEST['tglcek2'].'" ';	}
if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}
	//$met = $database->doQuery('SELECT * FROM fu_ajk_spak_form WHERE id !="" AND del is NULL '.$satu.' '.$tiga.' '.$duaa.' ORDER BY dokter_pemeriksa ASC, cabang ASC, tgl_periksa ASC LIMIT '.$m.', 25');
$met = $database->doQuery('SELECT
fu_ajk_spak.id,
fu_ajk_spak.id_cost,
fu_ajk_spak.id_polis,
fu_ajk_spak.spak,
fu_ajk_spak.input_by,
fu_ajk_spak.input_date,
fu_ajk_spak.`status`,
fu_ajk_spak.ext_premi,
fu_ajk_spak_form.nama,
fu_ajk_spak_form.dob,
fu_ajk_spak_form.x_premi,
IF(fu_ajk_spak.ext_premi!="", ((fu_ajk_spak_form.x_premi * fu_ajk_spak.ext_premi) / 100), "") AS nilai_EM,
IF(fu_ajk_spak.ext_premi!="", ((fu_ajk_spak_form.x_premi * fu_ajk_spak.ext_premi) / 100) + fu_ajk_spak_form.x_premi, fu_ajk_spak_form.x_premi) AS TotalPremi,
fu_ajk_spak_form.tgl_periksa,
fu_ajk_spak_form.dokter_pemeriksa,
fu_ajk_spak_form.cabang
FROM fu_ajk_spak
INNER JOIN fu_ajk_spak_form ON fu_ajk_spak.id = fu_ajk_spak_form.idspk AND fu_ajk_spak.id_cost = fu_ajk_spak_form.idcost
WHERE fu_ajk_spak.id !="" AND fu_ajk_spak.del is NULL AND fu_ajk_spak_form.del is NULL '.$satu.' '.$tiga.' '.$duaa.' AND fu_ajk_spak.`status` != "Pending" AND fu_ajk_spak.`status` != "Proses"
ORDER BY fu_ajk_spak.input_date ASC, fu_ajk_spak_form.dokter_pemeriksa ASC, fu_ajk_spak_form.cabang ASC, fu_ajk_spak_form.tgl_periksa ASC LIMIT '.$m.', 25');

$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(fu_ajk_spak.id) FROM fu_ajk_spak
	INNER JOIN fu_ajk_spak_form ON fu_ajk_spak.id = fu_ajk_spak_form.idspk AND fu_ajk_spak.id_cost = fu_ajk_spak_form.idcost
	WHERE fu_ajk_spak.id !="" AND fu_ajk_spak.del is NULL AND fu_ajk_spak_form.del is NULL '.$satu.' '.$tiga.' '.$duaa.' AND fu_ajk_spak.`status` != "Pending" AND fu_ajk_spak.`status` != "Proses"'));
	$totalRows = $totalRows[0];
	$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while ($met_ = mysql_fetch_array($met)) {
if (is_numeric($met_['dokter_pemeriksa'])) {
$metUserDok = mysql_fetch_array($database->doQuery('SELECT * FROM user_mobile WHERE id="'.$met_['dokter_pemeriksa'].'" '));
$metUserDok_ = $metUserDok['namalengkap']; }else{	$metUserDok_ = $met_['dokter_pemeriksa'];	}

if (is_numeric($met_['cabang'])) {
	$metUserCab = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id="'.$met_['cabang'].'" '));
	$metUserCab_ = $metUserCab['name'];	}else{	$metUserCab_ = $met_['cabang'];	}

if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	  <td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
	  <td>'.$metUserDok_.'</td>
	  <td>'.$met_['nama'].'</td>
	  <td align="center">'.$met_['spak'].'</td>
	  <td align="center">'._convertDate($met_['tgl_periksa']).'</td>
	  <td align="right">'.duit($met_['x_premi']).'</td>
	  <td align="center">'.$met_['ext_premi'].'</td>
	  <td align="right">'.$met_['nilai_EM'].'</td>
	  <td align="right">'.duit($met_['TotalPremi']).'</td>
	  <td align="right">'.$met_['status'].'</td>
	  <td>'.$metUserCab_.'</td>
	  </tr>';
}
	echo '<tr><td colspan="22">';
	echo createPageNavigations($file = 'ajk_re_dok.php?re=datadokter&id_cost='.$_REQUEST['id_cost'].'&nm_dok='.$_REQUEST['nm_dok'].'&tglcek1='.$_REQUEST['tglcek1'].'&tglcek2='.$_REQUEST['tglcek2'].'&', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
	echo '<b>Total Data Dokter: <u>' . $totalRows . '</u></b></td></tr>';
	echo '</table>';
}
}
echo '<!--WILAYAH COMBOBOX-->
<script src="javascript/metcombo/prototype.js"></script>
<script src="javascript/metcombo/dynamicombo.js"></script>
<!--WILAYAH COMBOBOX-->
<script>
document.observe("dom:loaded",function(){
	new DynamiCombo( "id_cost" , {
		elements:{
			"nm_dok":{url:\'javascript/metcombo/data.php?req=setdokter\', value:\'id\', label:\'name\', init:\'<?php echo $_POST["nm_dok"] ?>\'},
		},
		loadingImage:\'../loader1.gif\',
		loadingText:\'Loading...\',
		debug:0
	} )
});
</script>';
		;
} // switch

?>