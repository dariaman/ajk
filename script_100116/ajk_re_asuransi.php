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
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%"><tr><th width="95%" align="left">Modul Laporan Rekapitulasi Asuransi</font></th>'.$metnewuser.'</tr></table>';
switch ($_REQUEST['r']) {
	case "s":
		;
		break;
	case "d":
		;
		break;
	default:
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
	  <tr><td align="right">Nama Produk</td>
		<td id="polis_rate">: <select name="id_polis" id="id_polis">
		<option value="">-- Semua Produk --</option>
		</select></td></tr>
	  <tr><td align="right">Pilih Asuransi</td>
		<td id="polis_rate">: <select name="id_as" id="id_as">
		<option value="">-- Semua Asuransi --</option>
		</select></td></tr>
	  <tr><td align="right">Tanggal Akad <font color="red">*</font></td>
	  <td> : <input type="text" id="fromdn1" name="tglakad1" value="'.$_REQUEST['tglakad1'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromdn1);return false;"/>
			<a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromdn1);return false;">
			<img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
			<iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
	  		s/d
	  		<input type="text" id="fromdn2" name="tglakad2" value="'.$_REQUEST['tglakad2'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromdn2);return false;"/>
			<a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromdn2);return false;">
			<img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
			<iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
	  </td>
	  </tr>
	  <tr><td align="right">Status Pembayaran </td><td> :
			  <select size="1" name="paiddata"><option value="">--- Status ---</option>
			  								  <option value="1"'._selected($_REQUEST['paiddata'], "1").'>Paid</option>
											  <option value="0"'._selected($_REQUEST['paiddata'], "0").'>Unpaid</option>
			  </select>
	  </td></tr>
	  <tr><td align="right">Status Peserta </td><td> :
			  <select size="1" name="statpeserta"><option value="">--- Status Peserta---</option>
			  								  <option value="Inforce"'._selected($_REQUEST['statpeserta'], "Inforce").'>Aktif</option>
											  <option value="Lapse"'._selected($_REQUEST['statpeserta'], "Lapse").'>Lapse</option>
											  <option value="Maturity"'._selected($_REQUEST['statpeserta'], "Maturity").'>Maturity</option>
											  <option value="Batal"'._selected($_REQUEST['statpeserta'], "Batal").'>Batal</option>
			  </select>
	  </td></tr>
	  <tr><td align="center"colspan="2"><input type="hidden" name="re" value="dataasuransi"><input type="submit" name="ere" value="Cari"></td></tr>
	  </table>
	  </form>';
if ($_REQUEST['re']=="dataasuransi") {
if ($_REQUEST['id_cost']=="") 	{	$error_1 = '<div align="center"><font color="red"><blink>Silahkan pilih nama Costumer...!!<br /></div></font></blink>';	}
//if ($_REQUEST['id_polis']=="") 	{	$error_2 = '<div align="center"><font color="red"><blink>Silahkan pilih produk...!!<br /></div></font></blink>';	}
//if ($_REQUEST['id_as']=="") 	{	$error_3 = '<div align="center"><font color="red"><blink>Silahkan pilih asuransi...!!<br /></div></font></blink>';	}
if ($_REQUEST['tglakad1']=="") 	{	$error_4 = '<div align="center"><font color="red"><blink>Tanggal mulai asuransi tidak boleh kosong<br /></div></font></blink>';	}
if ($_REQUEST['tglakad2']=="") 	{	$error_5 = '<div align="center"><font color="red"><blink>Tanggal mulai asuransi tidak boleh kosong</div></font></blink>';	}
if ($error_1 OR $error_2 OR $error_3 OR $error_4 OR $error_5) {	echo $error_1 .''.$error_2.''.$error_3.''.$error_4.''.$error_5;	}
else{
if ($_REQUEST['id_cost'])		{	$satu = 'AND fu_ajk_peserta_as.id_bank = "'.$_REQUEST['id_cost'].'"';	}
if ($_REQUEST['id_polis'])		{	$duaa = 'AND fu_ajk_peserta_as.id_polis = "'.$_REQUEST['id_polis'].'"';	}
if ($_REQUEST['id_as'])			{	$tiga = 'AND fu_ajk_peserta_as.id_asuransi = "'.$_REQUEST['id_as'].'"';	}
//if ($_REQUEST['tglakad1'])		{	$empt = 'AND fu_ajk_peserta.kredit_tgl BETWEEN "'.$_REQUEST['tglakad1'].'" AND "'.$_REQUEST['tglakad2'].'" ';	}
if ($_REQUEST['tglakad1'])		{	$empt = 'AND IF(tgl_laporan is NULL, kredit_tgl BETWEEN "'.$_REQUEST['tglakad1'].'" AND "'.$_REQUEST['tglakad2'].'", tgl_laporan BETWEEN "'.$_REQUEST['tglakad1'].'" AND "'.$_REQUEST['tglakad2'].'")';	}
if ($_REQUEST['paiddata'])		{	$lima = 'AND fu_ajk_peserta.status_bayar = "'.$_REQUEST['paiddata'].'"';	}
if ($_REQUEST['statpeserta'])	{	$enam = 'AND fu_ajk_peserta.status_aktif = "'.$_REQUEST['statpeserta'].'"';	}

$searchbank = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$_REQUEST['id_cost'].'"'));
$searchproduk = mysql_fetch_array($database->doQuery('SELECT id, nmproduk FROM fu_ajk_polis WHERE id="'.$_REQUEST['id_polis'].'"'));
if ($searchproduk['nmproduk']=="") {	$searchproduk_ ="SEMUA PRODUK";	}else{	$searchproduk_ = $searchproduk['nmproduk'];	}

$searchasuransi = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_asuransi WHERE id="'.$_REQUEST['id_as'].'"'));
if ($searchasuransi['name']=="") {	$searchasuransi_ ="SEMUA ASURANSI";	$searchpolis_ ="SEMUA POLIS";	}else{	$searchasuransi_ = $searchasuransi['name'];		$searchpolis_ =$searchpolis['nopol'];}

$searchpolis = mysql_fetch_array($database->doQuery('SELECT id, nopol, id_cost, id_as, nmproduk FROM fu_ajk_polis_as WHERE id_cost="'.$_REQUEST['id_cost'].'" AND id_as="'.$_REQUEST['id_as'].'" AND nmproduk="'.$_REQUEST['id_polis'].'"'));
if ($_REQUEST['paiddata']=="") 		{	$searchpaid = "SEMUA STATUS PEMBAYARAN";	}else{
	if ($_REQUEST['paiddata']=="1") {	$searchpaid = "PAID";	}else{	$searchpaid = "UNPAID";	}
}
if ($_REQUEST['statpeserta']=="") 	{	$statuspeserta_ ="SEMUA STATUS PESERTA";	}else{	$statuspeserta_ = $_REQUEST['statpeserta'];	}

echo '<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="#E2E2E2">
	<tr><td bgcolor="#FFF"colspan="21"><a href="e_report.php?er=eL_asuransi&cat='.$_REQUEST['id_cost'].'&subcat='.$_REQUEST['id_polis'].'&idas='.$_REQUEST['id_as'].'&tgl1='.$_REQUEST['tglakad1'].'&tgl2='.$_REQUEST['tglakad2'].'&paid='.$_REQUEST['paiddata'].'&status='.$_REQUEST['statpeserta'].'"><img src="image/excel.png" width="25" border="0"><br />Excel</a></td></tr>
	<tr><td colspan="2">Nama Perusahaan</td><td colspan="14">: '.$searchbank['name'].'</td></tr>
	<tr><td colspan="2">Nama Produk</td><td colspan="14">: '.$searchproduk_.'</td></tr>
	<tr><td colspan="2">Nama Asuransi</td><td colspan="14">: '.$searchasuransi_.'</td></tr>
	<tr><td colspan="2">Polis Asuransi</td><td colspan="14">: '.$searchpolis_.'</td></tr>
	<tr><td colspan="2">Tanggal Akad</td><td colspan="14">: '._convertDate($_REQUEST['tglakad1']).' s/d '._convertDate($_REQUEST['tglakad2']).'</td></tr>
	<tr><td colspan="2">Status Pembayaran</td><td colspan="14">: '.$searchpaid.'</td></tr>
	<tr><td colspan="2">Status Peserta</td><td colspan="14">: '.strtoupper($statuspeserta_).'</td></tr>
	<th width="1%">No</th>
	<th width="10%">Produk</th>
	<th width="1%">Debit Note</th>
	<th width="1%">No. Reg</th>
	<th>Nama Debitur</th>
	<th>Cabang</th>
	<th>Tgl Lahir</th>
	<th width="1%">Usia</th>
	<th>Plafond</th>
	<th>Mulai Asuransi</th>
	<th width="1%">JK.W</th>
	<th>Akhir Asuransi</th>
	<th width="1%">Premi</th>
	<!--<th width="1%">Admin</th>
	<th width="1%">Disc</th>-->
	<th width="1%">ExtPremi</th>
	<!--<th width="1%">PPN</th>
	<th width="1%">PPH</th>-->
	<th width="1%">Total Premi</th>
	<th width="1%">Pembayaran</th>
	<th>Status</th>
	</tr>';
if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}
$met = $database->doQuery('SELECT fu_ajk_asuransi.`name` AS nmasuransi,
								  fu_ajk_peserta_as.id_polis,
								  fu_ajk_peserta.id_dn AS debitnote,
								  fu_ajk_peserta_as.id_peserta AS nopeserta,
								  fu_ajk_peserta.nama AS nama,
								  fu_ajk_peserta.tgl_lahir AS dob,
								  fu_ajk_peserta.usia AS usianya,
								  fu_ajk_peserta.kredit_tgl AS tglmulai,
								  fu_ajk_peserta.kredit_tenor AS tenor,
								  fu_ajk_peserta.kredit_akhir AS tglakhir,
								  fu_ajk_peserta.kredit_jumlah AS plafond,
								  fu_ajk_peserta_as.b_premi AS bpremi,
								  fu_ajk_peserta_as.b_admin AS badmin,
								  fu_ajk_peserta_as.b_disc AS bdisc,
								  fu_ajk_peserta_as.b_extpremi AS bextpremi,
								  fu_ajk_peserta_as.b_ppn AS ppn,
								  fu_ajk_peserta_as.b_pph AS pph,
								  fu_ajk_peserta_as.nettpremi AS nettpremi,
								  IF (fu_ajk_peserta.id_polis="1", fu_ajk_peserta.kredit_tenor * 12 ,fu_ajk_peserta.kredit_tenor) AS tenorbln,
								  IF (fu_ajk_peserta.status_bayar="0", "Unpaid","Paid") AS statusbayar,
								  fu_ajk_peserta.status_aktif AS statusaktif,
								  fu_ajk_peserta.cabang AS cabang
								  FROM fu_ajk_peserta_as
								  INNER JOIN fu_ajk_peserta ON fu_ajk_peserta_as.id_bank = fu_ajk_peserta.id_cost AND fu_ajk_peserta_as.id_polis = fu_ajk_peserta.id_polis AND fu_ajk_peserta_as.id_dn = fu_ajk_peserta.id_dn AND fu_ajk_peserta_as.id_peserta = fu_ajk_peserta.id_peserta
								  INNER JOIN fu_ajk_asuransi ON fu_ajk_peserta_as.id_asuransi = fu_ajk_asuransi.id
								  WHERE fu_ajk_peserta_as.id !="" '.$satu.' '.$duaa.' '.$tiga.' '.$empt.' '.$lima.' '.$enam.'
								  ORDER BY fu_ajk_peserta.kredit_tgl ASC LIMIT '.$m.', 25');
//echo('SELECT * FROM fu_ajk_peserta_as WHERE id !="" AND del is NULL '.$satu.' '.$duaa.' '.$tiga.' '.$empt.' '.$lima.' '.$enam.' ORDER BY kredit_tgl ASC LIMIT '.$m.', 25');
//$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_peserta_as WHERE id !="" AND del is NULL '.$satu.' '.$duaa.' '.$tiga.' '.$empt.' '.$lima.' '.$enam.''));
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(fu_ajk_peserta_as.id_peserta)
												   FROM fu_ajk_peserta_as
												   INNER JOIN fu_ajk_peserta ON fu_ajk_peserta_as.id_bank = fu_ajk_peserta.id_cost AND fu_ajk_peserta_as.id_polis = fu_ajk_peserta.id_polis AND fu_ajk_peserta_as.id_dn = fu_ajk_peserta.id_dn AND fu_ajk_peserta_as.id_peserta = fu_ajk_peserta.id_peserta
												   INNER JOIN fu_ajk_asuransi ON fu_ajk_peserta_as.id_asuransi = fu_ajk_asuransi.id
												   WHERE fu_ajk_peserta_as.id !="" '.$satu.' '.$duaa.' '.$tiga.' '.$empt.' '.$lima.' '.$enam.'
												   GROUP BY fu_ajk_peserta_as.id_bank'));
$totalRows = $totalRows[0];
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while ($met_ = mysql_fetch_array($met)) {
$met_dn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn WHERE id="'.$met_['debitnote'].'"'));
$met_polis = mysql_fetch_array($database->doQuery('SELECT id, nmproduk FROM fu_ajk_polis WHERE id="'.$met_['id_polis'].'"'));
if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	  <td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
	  <td align="center">'.$met_polis['nmproduk'].'</td>
	  <td align="center">'.$met_dn['dn_kode'].'</td>
	  <td>'.$met_['nopeserta'].'</td>
	  <td>'.$met_['nama'].'</td>
	  <td>'.$met_['cabang'].'</td>
	  <td align="center">'._convertDate($met_['dob']).'</td>
	  <td align="center">'.$met_['usianya'].'</td>
	  <td align="right">'.duit($met_['plafond']).'</td>
	  <td align="center">'._convertDate($met_['tglmulai']).'</td>
	  <td align="center">'.$met_['tenorbln'].'</td>
	  <td align="center">'._convertDate($met_['tglakhir']).'</td>
	  <td align="right">'.duit($met_['bpremi']).'</td>
	  <!--<td align="right">'.duit($met_['badmin']).'</td>
	  <td align="right">'.duit($met_['bdisc']).'</td>-->
	  <td align="right">'.duit($met_['bextpremi']).'</td>
	  <!--<td align="right">'.duit($met_['ppn']).'</td>
	  <td align="right">'.duit($met_['pph']).'</td>-->
	  <td align="right">'.duit($met_['nettpremi']).'</td>
	  <td align="center">'.$met_['statusbayar'].'</td>
	  <td align="center">'.$met_['statusaktif'].'</td>
	  </tr>';
	$jumUP += $met_['plafond'];
	$jumPremi += ROUND($met_['bpremi']);
	//$jumAdmin += ROUND($met_['badmin']);
	//$jumDisc += ROUND($met_['bdisc']);
	$jumExtPremi += ROUND($met_['bextpremi']);
	//$jumPpn += ROUND($met_['ppn']);
	//$jumPph += ROUND($met_['pph']);
	$jumNettPremi += ROUND($met_['nettpremi']);
}
$met__ = mysql_fetch_array($database->doQuery('SELECT
fu_ajk_peserta.id_dn AS debitnote,
fu_ajk_asuransi.`name` AS nmasuransi,
fu_ajk_peserta.kredit_tgl AS tglmulai,
fu_ajk_peserta.kredit_tenor AS tenor,
fu_ajk_peserta.kredit_akhir AS tglakhir,
SUM(fu_ajk_peserta.kredit_jumlah) AS plafond,
SUM(fu_ajk_peserta_as.b_premi) AS bpremi,
SUM(fu_ajk_peserta_as.b_admin) AS badmin,
SUM(fu_ajk_peserta_as.b_disc) AS bdisc,
SUM(fu_ajk_peserta_as.b_extpremi) AS bextpremi,
SUM(fu_ajk_peserta_as.b_ppn) AS ppn,
SUM(fu_ajk_peserta_as.b_pph) AS pph,
SUM(fu_ajk_peserta_as.nettpremi) AS nettpremi,
fu_ajk_peserta.status_bayar AS statusbayar,
fu_ajk_peserta.status_aktif AS statusaktif,
fu_ajk_peserta.cabang AS cabang
FROM fu_ajk_peserta_as INNER JOIN fu_ajk_peserta ON fu_ajk_peserta_as.id_bank = fu_ajk_peserta.id_cost AND fu_ajk_peserta_as.id_polis = fu_ajk_peserta.id_polis AND fu_ajk_peserta_as.id_peserta = fu_ajk_peserta.id_peserta INNER JOIN fu_ajk_asuransi ON fu_ajk_peserta_as.id_asuransi = fu_ajk_asuransi.id
WHERE fu_ajk_peserta_as.id !="" '.$satu.' '.$duaa.' '.$tiga.' '.$empt.' '.$lima.' '.$enam.'
GROUP BY fu_ajk_peserta_as.id_bank
ORDER BY fu_ajk_peserta.kredit_tgl ASC
'));
echo '<tr class="tr1"><td colspan="8" align="center"><b>TOTAL</b></td>
					  <td align="right"><b>'.duit($jumUP).'</td>
					  <td colspan="3"></td>
					  <td align="right"><b>'.duit($jumPremi).'</td>
					  <!--<td align="right"><b>'.duit($jumAdmin).'</td>
					  <td align="right"><b>'.duit($jumDisc).'</td>-->
					  <td align="right"><b>'.duit($jumExtPremi).'</td>
					  <!--<td align="right"><b>'.duit($jumPpn).'</td>
					  <td align="right"><b>'.duit($jumPph).'</td>-->
					  <td align="right"><b>'.duit($jumNettPremi).'</td>
	  </tr>
	  <tr bgcolor="#FFFEEE"><td colspan="8" align="center"><b>GRAND TOTAL</b></td>
					  <td align="right"><b>'.duit($met__['plafond']).'</td>
					  <td colspan="3"></td>
					  <td align="right"><b>'.duit($met__['bpremi']).'</td>
					  <!--<td align="right"><b>'.duit($met__['badmin']).'</td>
					  <td align="right"><b>'.duit($met__['bdisc']).'</td>-->
					  <td align="right"><b>'.duit($met__['bextpremi']).'</td>
					  <!--<td align="right"><b>'.duit($met__['ppn']).'</td>
					  <td align="right"><b>'.duit($met__['pph']).'</td>-->
					  <td align="right"><b>'.duit($met__['nettpremi']).'</td>
					  <td colspan="2"> </td>
	  </tr>';
echo '<tr><td colspan="22">';
echo createPageNavigations($file = 'ajk_re_asuransi.php?re=dataasuransi&id_cost='.$_REQUEST['id_cost'].'&id_polis='.$_REQUEST['id_polis'].'&id_as='.$_REQUEST['id_as'].'&tglakad1='.$_REQUEST['tglakad1'].'&tglakad2='.$_REQUEST['tglakad2'].'&paiddata='.$_REQUEST['paiddata'].'&statpeserta='.$_REQUEST['statpeserta'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
echo '<b>Total Data Peserta Asuransi: <u>' . $totalRows . '</u></b></td></tr>';
echo '</table>';
}
}		;
} // switch
echo '<!--WILAYAH COMBOBOX-->
<script src="javascript/metcombo/prototype.js"></script>
<script src="javascript/metcombo/dynamicombo.js"></script>
<!--WILAYAH COMBOBOX-->
<script>
document.observe("dom:loaded",function(){
	new DynamiCombo( "id_cost" , {
		elements:{
			"id_polis":		{url:\'javascript/metcombo/data.php?req=setpoliscostumer\', value:\'id\', label:\'name\', init:\'<?php echo $_POST["id_polis"] ?>\'},
			"id_as":		{url:\'javascript/metcombo/data.php?req=setpolisasuransi\', value:\'id\', label:\'name\', init:\'<?php echo $_POST["id_as"] ?>\'},
		},
		loadingImage:\'loader1.gif\',
		loadingText:\'Loading...\',
		debug:0
	} )
});
</script>';
?>