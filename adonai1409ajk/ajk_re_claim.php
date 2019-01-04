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
switch ($_REQUEST['c']) {
	case "batal":	
	echo '<table border="0" cellpadding="5" cellspacing="0" width="100%"><tr><th width="95%" align="left">Modul Laporan Rekapitulasi Pembatalan</font></th></tr></table>';
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
		<option value="">-- Pilih Produk --</option>
		</select></td></tr>
	  <tr><td align="right">Tanggal Cetak CN</td>
	  	  <td> :<input type="text" id="fromdn1" name="tglcheck3" value="'.$_REQUEST['tglcheck3'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromdn1);return false;"/>
			<a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromdn1);return false;">
			<img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
			<iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
			s/d
			<input type="text" id="fromdn2" name="tglcheck4" value="'.$_REQUEST['tglcheck4'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromdn2);return false;"/>
			<a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromdn2);return false;">
			<img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
			<iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>';
echo '</td></tr>
	  <tr><td align="center"colspan="2"><input type="hidden" name="re" value="dtRefund"><input type="submit" name="ere" value="Cari"></td></tr>
	  </table>
	  </form>';
if ($_REQUEST['re']=="dtRefund") {
if ($_REQUEST['id_cost']=="") 		{	$error_1 = '<div align="center"><font color="red"><blink>Silahkan pilih nama perusahaan...!!<br /></div></font></blink>';	}
	//if ($_REQUEST['tglcheck1']=="") 	{	$error_2 = '<div align="center"><font color="red"><blink>Tanggal mulai refund tidak boleh kosong<br /></div></font></blink>';	}
	//if ($_REQUEST['tglcheck2']=="") 	{	$error_3 = '<div align="center"><font color="red"><blink>Tanggal akhir mulai tidak boleh kosong</div></font></blink>';	}
if ($error_1 OR $error_2 OR $error_3) {	echo $error_1 .''.$error_2.''.$error_3;	}
else{
echo '<a href="e_report.php?er=eL_Batal&cat='.$_REQUEST['id_cost'].'&subcat='.$_REQUEST['id_polis'].'&tgl1='.$_REQUEST['tglcheck3'].'&tgl2='.$_REQUEST['tglcheck4'].'"><img src="image/excel.png" width="25" border="0"><br />Excel</a>';
echo '<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="#E2E2E2">
	<tr><th width="1%">No</th>
		<th>Asuransi</th>
		<th>ID Peserta</th>
		<th>Nama Debitur</th>
		<th>Cabang</th>
		<th>Tgl Lahir</th>
		<th>Usia</th>
		<th>Plafond</th>
		<th>JK.W</th>
		<th>Grace Period</th>
		<th>Tgl Akad</th>
		<th>Tgl Akhir</th>
		<th>Rate</th>
		<th>Premi</th>
		<th>EM</th>
		<th>Total Premi</th>
		<th>Premi Refund</th>
		<th>Produk</th>
		<th>Tgl Batal</th>
		<th>Alasan</th>
		<th>Debit Note</th>
		<th>Credit Note</th>
	</tr>';
if ($_REQUEST['id_cost'])		{	$satu = 'AND id_cost = "'.$_REQUEST['id_cost'].'"';	}
if ($_REQUEST['id_polis'])		{	$duaa = 'AND id_nopol = "'.$_REQUEST['id_polis'].'"';	}
//if ($_REQUEST['tglcheck1'])		{	$tiga = 'AND tgl_claim BETWEEN "'.$_REQUEST['tglcheck1'].'" AND "'.$_REQUEST['tglcheck2'].'" ';	}
if ($_REQUEST['tglcheck3'])		{	$empt = 'AND tgl_createcn BETWEEN "'.$_REQUEST['tglcheck3'].'" AND "'.$_REQUEST['tglcheck4'].'" ';	}

if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}
$met = $database->doQuery('SELECT * FROM fu_ajk_cn WHERE id_cn !="" AND type_claim="Batal" AND del is NULL '.$satu.' '.$duaa.' '.$tiga.' '.$empt.' ORDER BY tgl_createcn DESC LIMIT '. $m .' , 25');
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_cn WHERE id !="" AND type_claim="Batal" AND del is NULL '.$satu.' '.$duaa.' '.$tiga.' '.$empt.''));
$totalRows = $totalRows[0];
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while ($met_ = mysql_fetch_array($met)) {
$met_produk = mysql_fetch_array($database->doQuery('SELECT id, nmproduk, singlerate FROM fu_ajk_polis WHERE id="'.$met_['id_nopol'].'"'));
	$met_peserta = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_cost="'.$met_['id_cost'].'" AND id_polis="'.$met_['id_nopol'].'" AND id_peserta="'.$met_['id_peserta'].'"'));
	$tglbatal = explode("#", $met_peserta['ket']);
	if ($met_produk['singlerate']=="Y") {	$ratetenornya = $met_peserta['kredit_tenor'] * 12;	}else{	$ratetenornya = $met_peserta['kredit_tenor'];	}

$cekdataret = mysql_fetch_array($database->doQuery('SELECT id_cost, id_polis, tenor, rate FROM fu_ajk_ratepremi WHERE id !="" AND id_cost="'.$met_['id_cost'].'" AND id_polis="'.$met_['id_nopol'].'" AND tenor="'.$met_peserta['kredit_tenor'].'" AND status="Baru"'));
$met_dn = mysql_fetch_array($database->doQuery('SELECT id, dn_kode, id_as FROM fu_ajk_dn WHERE id="'.$met_['id_dn'].'"'));
$met_dn_Asuransi = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_asuransi WHERE id="'.$met_dn['id_as'].'"'));
if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
			<td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
			<td>'.$met_dn_Asuransi['name'].'</td>
			<td align="center">'.$met_['id_peserta'].'</td>
			<td>'.$met_peserta['nama'].'</td>
			<td align="center">'.$met_['id_cabang'].'</td>
			<td>'._convertDate($met_peserta['tgl_lahir']).'</td>
			<td align="center">'.$met_peserta['usia'].'</td>
			<td align="right">'.duit($met_peserta['kredit_jumlah']).'</td>
			<td align="center">'.$ratetenornya.'</td>
			<td align="center">'.$met_peserta['mppbln'].'</td> 
			<td align="center">'._convertDate($met_peserta['kredit_tgl']).'</td>
			<td align="center">'._convertDate($met_peserta['kredit_akhir']).'</td>
			<td align="right">'.$cekdataret['rate'].'</td>
			<td align="right">'.duit($met_peserta['premi']).'</td>
			<td align="right">'.duit($met_peserta['ext_premi']).'</td>
			<td align="right">'.duit($met_peserta['totalpremi']).'</td>
			<td align="right">'.duit($met_['total_claim']).'</td>
			<td>'.$met_produk['nmproduk'].'</td>
			<td>'.$tglbatal[0].'</td>
			<td>'.$tglbatal[1].'</td>
			<td>'.$met_dn['dn_kode'].'</td>
			<td>'.$met_['id_cn'].'</td>
		 </tr>';
}
	echo '<tr><td colspan="22">';
	echo createPageNavigations($file = 'ajk_re_claim.php?x=refund&id_cost='.$_REQUEST['id_cost'].'&id_polis='.$_REQUEST['id_polis'].'&tglakad1='.$_REQUEST['tglakad1'].'&tglakad2='.$_REQUEST['tglakad2'].'&paiddata='.$_REQUEST['paiddata'].'&statpeserta='.$_REQUEST['statpeserta'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
	echo '<b>Total Data Refund: <u>' . $totalRows . '</u></b></td></tr>';
	echo '</table>';
}
}
	;
	break;

	case "refund":
		echo '
		<table border="0" cellpadding="5" cellspacing="0" width="100%"><tr><th width="95%" align="left">Modul Laporan Rekapitulasi Refund</font></th></tr></table>';
		$metcost = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name DESC');
		echo '
		<form method="post" action="" name="postform">
			<input type="hidden" name="id_cost" value="'.$metcost['id'].'">
			<table border="0" cellpadding="1" cellspacing="0" width="100%">
				<tr>
					<td width="40%" align="right">Nama Perusahaan <font color="red">*</font></td>
					<td> : <select name="id_cost" id="id_cost">
						<option value="">---Pilih Perusahaan---</option>';
							while($metcost_ = mysql_fetch_array($metcost)) {
								echo '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['id_cost'], $metcost_['id']).'>'.$metcost_['name'].'</option>';
							}
							echo '
							</select>
					</td>
				</tr>
				<tr>
					<td align="right">Nama Produk</td>
					<td id="polis_rate">: 
						<select name="id_polis" id="id_polis">
							<option value="">-- Pilih Produk --</option>
						</select>
					</td>
				</tr>
				<tr>
					<td align="right">Tanggal Refund</td>
						<td> : <input type="text" id="from" name="tglcheck1" value="'.$_REQUEST['tglcheck1'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.from);return false;"/>
						<a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.from);return false;">
						<img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
						<iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
						s/d
						<input type="text" id="from1" name="tglcheck2" value="'.$_REQUEST['tglcheck2'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.from1);return false;"/>
						<a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.from1);return false;">
						<img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
						<iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>';
						//print initCalendar();	print calendarBox('tglcheck1', 'triger1', $_REQUEST['tglcheck1']);	echo 's/d';
						//print initCalendar();	print calendarBox('tglcheck2', 'triger2', $_REQUEST['tglcheck2']);
						echo '
					</td>
				</tr>
				<tr>
					<td align="right">Tanggal Cetak CN</td>
					<td> : <input type="text" id="from2" name="tglcheck3" value="'.$_REQUEST['tglcheck3'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.from2);return false;"/>
						<a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.from2);return false;">
						<img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
						<iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
						s/d
						<input type="text" id="from3" name="tglcheck4" value="'.$_REQUEST['tglcheck4'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.from3);return false;"/>
						<a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.from3);return false;">
						<img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
						<iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>';
						//print initCalendar();	print calendarBox('tglcheck3', 'triger3', $_REQUEST['tglcheck3']);	echo 's/d';
						//print initCalendar();	print calendarBox('tglcheck4', 'triger4', $_REQUEST['tglcheck4']);
						echo '
					</td>
				</tr>
				<tr>
					<td align="center"colspan="2"><input type="hidden" name="re" value="dtRefund"><input type="submit" name="ere" value="Cari"></td>
				</tr>
			</table>
		</form>';
		if ($_REQUEST['re']=="dtRefund") {
		if ($_REQUEST['id_cost']=="") 		{	$error_1 = '<div align="center"><font color="red"><blink>Silahkan pilih nama perusahaan...!!<br /></div></font></blink>';	}
		//if ($_REQUEST['tglcheck1']=="") 	{	$error_2 = '<div align="center"><font color="red"><blink>Tanggal mulai refund tidak boleh kosong<br /></div></font></blink>';	}
		//if ($_REQUEST['tglcheck2']=="") 	{	$error_3 = '<div align="center"><font color="red"><blink>Tanggal akhir mulai tidak boleh kosong</div></font></blink>';	}
		if ($error_1 OR $error_2 OR $error_3) {	echo $error_1 .''.$error_2.''.$error_3;	}
		else{
		echo '<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="#E2E2E2">
			<tr><td bgcolor="#FFF"colspan="23"><a href="e_report.php?er=eL_refund&cat='.$_REQUEST['id_cost'].'&subcat='.$_REQUEST['id_polis'].'&tgl1='.$_REQUEST['tglcheck1'].'&tgl2='.$_REQUEST['tglcheck2'].'&tgl3='.$_REQUEST['tglcheck3'].'&tgl4='.$_REQUEST['tglcheck4'].'"><img src="image/excel.png" width="25" border="0"><br />Excel</a></td></tr>
				<th width="1%">No</th>
				<th>Asuransi</th>
				<th>ID Peserta</th>
				<th>Nama Debitur</th>
				<th>Cabang</th>
				<th>Tgl Lahir</th>
				<th>Usia</th>
				<th>Plafond</th>
				<th>JK.W</th>
				<th>Grace Period</th>
				<th>Tgl Akad</th>
				<th>Tgl Akhir</th>
				<th>Rate</th>
				<th>Premi</th>
				<th>EM</th>
				<th>Total Premi</th>
				<th>Premi Refund</th>
				<th>Type Data</th>
				<th>Keterangan</th>
				<th>Produk</th>
				<th>Tgl Refund</th>
				<th>Tgl Proses</th>
				<th>Tgl Cetak CN</th>
				<th>Debit Note</th>
				<th>Credit Note</th>
			</tr>';
		if ($_REQUEST['id_cost'])		{	$satu = 'AND id_cost = "'.$_REQUEST['id_cost'].'"';	}
		if ($_REQUEST['id_polis'])		{	$duaa = 'AND id_nopol = "'.$_REQUEST['id_polis'].'"';	}
		if ($_REQUEST['tglcheck1'])		{	$tiga = 'AND tgl_claim BETWEEN "'.$_REQUEST['tglcheck1'].'" AND "'.$_REQUEST['tglcheck2'].'" ';	}
		if ($_REQUEST['tglcheck3'])		{	$empt = 'AND tgl_createcn BETWEEN "'.$_REQUEST['tglcheck3'].'" AND "'.$_REQUEST['tglcheck4'].'" ';	}

		if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}
		$met = $database->doQuery('SELECT *,DATE_FORMAT(input_date,"%Y-%m-%d")as tglproses FROM fu_ajk_cn WHERE id_cn !="" AND (type_claim="Refund" OR type_claim="Top Up") AND del is NULL '.$satu.' '.$duaa.' '.$tiga.' '.$empt.' ORDER BY tgl_createcn ASC LIMIT '. $m .' , 25');
		$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_cn WHERE id !="" AND (type_claim="Refund" OR type_claim="Top Up") AND del is NULL '.$satu.' '.$duaa.' '.$tiga.' '.$empt.''));
		$totalRows = $totalRows[0];
		$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
		while ($met_ = mysql_fetch_array($met)) {
		$met_peserta = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_cost="'.$met_['id_cost'].'" AND id_polis="'.$met_['id_nopol'].'" AND id_peserta="'.$met_['id_peserta'].'"'));
		$met_dn = mysql_fetch_array($database->doQuery('SELECT id, dn_kode, id_as FROM fu_ajk_dn WHERE id="'.$met_['id_dn'].'"'));
		$met_dn_Asuransi = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_asuransi WHERE id="'.$met_dn['id_as'].'"'));

		$met_produk = mysql_fetch_array($database->doQuery('SELECT id, nmproduk, singlerate FROM fu_ajk_polis WHERE id="'.$met_['id_nopol'].'"'));

		if ($met_produk['singlerate']=="Y") {	$ratetenornya = $met_peserta['kredit_tenor'] / 12;	}else{	$ratetenornya = $met_peserta['kredit_tenor'];	}
		$cekdataret = mysql_fetch_array($database->doQuery('SELECT id_cost, id_polis, tenor, rate FROM fu_ajk_ratepremi WHERE id !="" AND id_cost="'.$met_['id_cost'].'" AND id_polis="'.$met_['id_nopol'].'" AND tenor="'.$ratetenornya.'" AND status="Baru"'));

		if ($met_['type_refund']=="Lunas") {	$ketRefund = "Lunas Dipercepat";	}else{	$ketRefund = $met_['type_refund'];	}
		if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
		echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
				<td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
				<td>'.$met_dn_Asuransi['name'].'</td>
				<td align="center">'.$met_['id_peserta'].'</td>
				<td>'.$met_peserta['nama'].'</td>
				<td>'.$met_['id_cabang'].'</td>
				<td align="center">'._convertDate($met_peserta['tgl_lahir']).'</td>
				<td align="center">'.$met_peserta['usia'].'</td>
				<td align="right">'.duit($met_peserta['kredit_jumlah']).'</td>
				<td align="center">'.$met_peserta['kredit_tenor'].'</td>
				<td align="center">'.$met_peserta['mppbln'].'</td>
				<td align="center">'._convertDate($met_peserta['kredit_tgl']).'</td>
				<td align="center">'._convertDate($met_peserta['kredit_akhir']).'</td>
				<td align="right">'.$cekdataret['rate'].'</td>
				<td align="right">'.duit($met_peserta['premi']).'</td>
				<td align="right">'.duit($met_peserta['ext_premi']).'</td>
				<td align="right">'.duit($met_peserta['totalpremi']).'</td>
				<td align="right">'.duit($met_['total_claim']).'</td>
				<td align="center">'.$met_['type_claim'].'</td>
				<td align="center"><a href="'.$metpath_file.''.$met_['fname'].'" target="_blank">'.$ketRefund.'</a></td>
				<td align="center">'.$met_produk['nmproduk'].'</td>
				<td align="center">'._convertDate($met_['tgl_claim']).'</td>
				<td align="center">'._convertDate($met_['tglproses']).'</td>
				<td align="center">'._convertDate($met_['tgl_createcn']).'</td>
				<td align="center">'.$met_dn['dn_kode'].'</td>
				<td align="center">'.$met_['id_cn'].'</td>
			</tr>';
		}
			echo '<tr><td colspan="22">';
			echo createPageNavigations($file = 'ajk_re_claim.php?c=refund&re='.$_REQUEST['re'].'&id_cost='.$_REQUEST['id_cost'].'&id_polis='.$_REQUEST['id_polis'].'&tglcheck1='.$_REQUEST['tglcheck1'].'&tglcheck2='.$_REQUEST['tglcheck2'].'&tglcheck3='.$_REQUEST['tglcheck3'].'&tglcheck4='.$_REQUEST['tglcheck4'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
			echo '<b>Total Data Refund: <u>' . $totalRows . '</u></b></td></tr>';
			echo '</table>';
		}
		}
			;
	break;

	case "klaim":
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%"><tr><th width="95%" align="left">Modul Laporan Rekapitulasi Klaim</font></th></tr></table>';
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
		<option value="">-- Pilih Produk --</option>
		</select></td></tr>
		<!--<tr><td align="right">Penyebab Meninggal <font color="red">*</font></td><td> : <select name="sebebmeninggal" id="sebebmeninggal">
			  	<option value="">---Pilih Penyebab Meninggal---</option>
		</select></td></tr>-->
		<tr><td align="right">Penyebab Meninggal</td><td> : <select name="sebebmeninggal" id="sebebmeninggal">
			  	<option value="">---Pilih Penyebab Meninggal---</option>';
$metCoz = $database->doQuery('SELECT * FROM fu_ajk_namapenyakit ORDER BY namapenyakit ASC');
while($metCoz_ = mysql_fetch_array($metCoz)) {
	echo  '<option value="'.$metCoz_['id'].'"'._selected($_REQUEST['sebebmeninggal'], $metCoz_['id']).'>'.$metCoz_['namapenyakit'].'</option>';
}
echo '</select></td></tr>
	  <tr><td align="right">Tanggal Klaim</td>
	  	  <td> :';
echo '<input type="text" id="from" name="tglcheck1" value="'.$_REQUEST['tglcheck1'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.from);return false;"/>
	  <a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.from);return false;">
	  <img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
	  <iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
	  s/d
	  <input type="text" id="from1" name="tglcheck2" value="'.$_REQUEST['tglcheck2'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.from1);return false;"/>
	  <a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.from1);return false;">
	  <img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
	  <iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>';
//print initCalendar();	print calendarBox('tglcheck1', 'triger1', $_REQUEST['tglcheck1']);	echo 's/d';
//print initCalendar();	print calendarBox('tglcheck2', 'triger2', $_REQUEST['tglcheck2']);
echo '</td></tr>
	  <tr><td align="right">Tanggal Input</td>
	  	  <td> :';
echo '<input type="text" id="from2" name="tglcheck3" value="'.$_REQUEST['tglcheck3'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.from2);return false;"/>
	  <a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.from2);return false;">
	  <img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
	  <iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
	  s/d
	  <input type="text" id="from3" name="tglcheck4" value="'.$_REQUEST['tglcheck4'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.from3);return false;"/>
	  <a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.from3);return false;">
	  <img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
	  <iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>';
//print initCalendar();	print calendarBox('tglcheck3', 'triger3', $_REQUEST['tglcheck3']);	echo 's/d';
//print initCalendar();	print calendarBox('tglcheck4', 'triger4', $_REQUEST['tglcheck4']);
echo '</td></tr>
	  <!--<tr><td align="center"colspan="2"><input type="hidden" name="re" value="dtKlaim"><input type="submit" name="ere" value="Cari"></td></tr>-->
	  <tr><td align="center"colspan="2"><input type="hidden" name="re" value="dtAllKlaim"><input type="submit" name="ere" value="Cari"></td></tr>
	  </table>
	  </form>';
if ($_REQUEST['re']=="dtKlaim") {
if ($_REQUEST['id_cost']=="") 	{	$error_1 = '<div align="center"><font color="red"><blink>Silahkan pilih nama perusahaan...!!<br /></div></font></blink>';	}
//if ($_REQUEST['tglcheck1']=="") 	{	$error_2 = '<div align="center"><font color="red"><blink>Tanggal mulai mulai tidak boleh kosong<br /></div></font></blink>';	}
//if ($_REQUEST['tglcheck2']=="") 	{	$error_3 = '<div align="center"><font color="red"><blink>Tanggal akhir mulai tidak boleh kosong</div></font></blink>';	}
if ($error_1 OR $error_2 OR $error_3) {	echo $error_1 .''.$error_2.''.$error_3;	}
else{
echo '<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="#E2E2E2">
	<tr>
		<!-- <td bgcolor="#FFF"colspan="1"><a href="e_report.php?er=eL_klaim&cat='.$_REQUEST['id_cost'].'&subcat='.$_REQUEST['id_polis'].'&tgl1='.$_REQUEST['tglcheck1'].'&tgl2='.$_REQUEST['tglcheck2'].'&tgl3='.$_REQUEST['tglcheck3'].'&tgl4='.$_REQUEST['tglcheck4'].'"><img src="image/excel.png" width="25" border="0"><br />Excel</a></td> -->
		<td bgcolor="#FFF"colspan="23"><a href="e_report.php?er=eL_klaim&cat='.$_REQUEST['id_cost'].'&subcat='.$_REQUEST['id_polis'].'&tgl1='.$_REQUEST['tglcheck1'].'&tgl2='.$_REQUEST['tglcheck2'].'&tgl3='.$_REQUEST['tglcheck3'].'&tgl4='.$_REQUEST['tglcheck4'].'"><img src="image/excel.png" width="25" border="0"><br />All Claim</a></td>
	</tr>
		<th width="1%">No</th>
		<th>Produk</th>
		<th>No.Peserta</th>
		<th>Nama Debitur</th>
		<th>Debit Note</th>
		<th>Credit Note</th>
		<th>Tgl Credit Note</th>
		<th>Tgl Refund</th>
		<th>Premi</th>
		<th>Nilai Credit Note</th>
		<th>Cabang</th>
	</tr>';
		if ($_REQUEST['id_cost'])		{	$satu = 'AND id_cost = "'.$_REQUEST['id_cost'].'"';	}
		if ($_REQUEST['id_polis'])		{	$duaa = 'AND id_nopol = "'.$_REQUEST['id_polis'].'"';	}
		if ($_REQUEST['tglcheck1'])		{	$tiga = 'AND tgl_claim BETWEEN "'.$_REQUEST['tglcheck1'].'" AND "'.$_REQUEST['tglcheck2'].'" ';	}
		if ($_REQUEST['tglcheck3'])		{	$empt = 'AND DATE_FORMAT(input_date,"%Y-%m-%d") BETWEEN "'.$_REQUEST['tglcheck3'].'" AND "'.$_REQUEST['tglcheck4'].'" ';	}
		if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}
			$met = $database->doQuery('SELECT * FROM fu_ajk_cn WHERE id_cn !="" AND type_claim="Death" AND del is NULL '.$satu.' '.$duaa.' '.$tiga.' '.$empt.' ORDER BY tgl_createcn ASC LIMIT '.$m.', 25');
			$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_cn WHERE id !="" AND type_claim="Death" AND del is NULL '.$satu.' '.$duaa.' '.$tiga.' '.$empt.''));
			$totalRows = $totalRows[0];
			$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
		while ($met_ = mysql_fetch_array($met)) {
			$met_peserta = mysql_fetch_array($database->doQuery('SELECT id, id_cost, id_polis, id_peserta, nama FROM fu_ajk_peserta WHERE id_cost="'.$met_['id_cost'].'" AND id_polis="'.$met_['id_nopol'].'" AND id_peserta="'.$met_['id_peserta'].'"'));
			$met_dn = mysql_fetch_array($database->doQuery('SELECT id, dn_kode FROM fu_ajk_dn WHERE id="'.$met_['id_dn'].'"'));
			$met_produk = mysql_fetch_array($database->doQuery('SELECT id, nmproduk FROM fu_ajk_polis WHERE id="'.$met_['id_nopol'].'"'));
		if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
		echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
			<td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
			<td align="center">'.$met_produk['nmproduk'].'</td>
			<td align="center">'.$met_['id_peserta'].'</td>
			<td>'.$met_peserta['nama'].'</td>
			<td align="center">'.$met_dn['dn_kode'].'</td>
			<td align="center">'.$met_['id_cn'].'</td>
			<td align="center">'._convertDate($met_['tgl_createcn']).'</td>
			<td align="center">'._convertDate($met_['tgl_claim']).'</td>
			<td align="right">'.duit($met_['premi']).'</td>
			<td align="right">'.duit($met_['total_claim']).'</td>
			<td>'.$met_['id_cabang'].'</td>
		 </tr>';
}
	echo '<tr><td colspan="22">';
	echo createPageNavigations($file = 'ajk_re_claim.php?x=klaim&id_cost='.$_REQUEST['id_cost'].'&id_polis='.$_REQUEST['id_polis'].'&tglakad1='.$_REQUEST['tglakad1'].'&tglakad2='.$_REQUEST['tglakad2'].'&paiddata='.$_REQUEST['paiddata'].'&statpeserta='.$_REQUEST['statpeserta'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
	echo '<b>Total Data Klaim: <u>' . $totalRows . '</u></b></td></tr>';
	echo '</table>';
}
}

if ($_REQUEST['re']=="dtAllKlaim") {
if ($_REQUEST['id_cost']=="") 	{	$error_1 = '<div align="center"><font color="red"><blink>Silahkan pilih nama perusahaan...!!<br /></div></font></blink>';	}
//if ($_REQUEST['tglcheck3']=="" OR $_REQUEST['tglcheck4']=="") 	{	$error_2 = '<div align="center"><font color="red"><blink>Tanggal creditnote tidak boleh kosong<br /></div></font></blink>';	}
//if ($_REQUEST['tglcheck4']=="") 	{	$error_3 = '<div align="center"><font color="red"><blink>Tanggal akhir tidak boleh kosong</div></font></blink>';	}
if ($error_1 OR $error_2) {	echo $error_1 .''.$error_2;	}
else{
echo '<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="#E2E2E2">
		<tr>
			<!-- <td bgcolor="#FFF"colspan="1"><a href="e_report.php?er=eL_klaim&cat='.$_REQUEST['id_cost'].'&subcat='.$_REQUEST['id_polis'].'&tgl1='.$_REQUEST['tglcheck1'].'&tgl2='.$_REQUEST['tglcheck2'].'&tgl3='.$_REQUEST['tglcheck3'].'&tgl4='.$_REQUEST['tglcheck4'].'"><img src="image/excel.png" width="25" border="0"><br />Excel</a></td> -->
			<td bgcolor="#FFF"colspan="23">
				<a href="e_report.php?er=eL_klaim&cat='.$_REQUEST['id_cost'].'&subcat='.$_REQUEST['id_polis'].'&kat='.$_REQUEST['sebebmeninggal'].'&tgl1='.$_REQUEST['tglcheck1'].'&tgl2='.$_REQUEST['tglcheck2'].'&tgl3='.$_REQUEST['tglcheck3'].'&tgl4='.$_REQUEST['tglcheck4'].'"><img src="image/excel.png" width="25" border="0">Download Klaim</a>
				<a href="e_report.php?er=eL_klaimtotal&cat='.$_REQUEST['id_cost'].'"><img src="../image/dninvoice1.jpg" width="25" border="0">Klaim Total</a>
			</td>			
		</tr>
		<tr>
			<th width="1%">No</th>
			<th>Cabang</th>
			<th>Cover Asuransi</th>
			<th>Nama Debitur</th>
			<th>Tgl Lahir</th>
			<th>Usia</th>
			<th>Jml Kredit</th>
			<th>Tuntutan Klaim</th>
			<th>Tgl Akad</th>
			<th>J.Wkt</th>
			<th>DOL</th>
			<th>Akad s/d DOL (hari)</th>
			<th>Tgl. Lapor Asuransi</th>
			<th>Kelengkapan Dok Klaim</th>
			<th>Tgl Status Lengkap</th>
			<th>Status Dokumen</th>
			<th>Status Klaim</th>
			<th>Keterangan</th>
			<th>Produk</th>
		</tr>';
if ($_REQUEST['id_cost'])		{	$satu = 'AND fu_ajk_cn.id_cost = "'.$_REQUEST['id_cost'].'"';	}
if ($_REQUEST['id_polis'])		{	$duaa = 'AND fu_ajk_cn.id_nopol = "'.$_REQUEST['id_polis'].'"';	}
if ($_REQUEST['tglcheck1'])		{	$tiga = 'AND fu_ajk_cn.tgl_claim BETWEEN "'.$_REQUEST['tglcheck1'].'" AND "'.$_REQUEST['tglcheck2'].'" ';	}
if ($_REQUEST['tglcheck3'])		{	$empt = 'AND DATE_FORMAT(fu_ajk_cn.input_date,"%Y-%m-%d") BETWEEN "'.$_REQUEST['tglcheck3'].'" AND "'.$_REQUEST['tglcheck4'].'" ';	}
if ($_REQUEST['sebebmeninggal'])	{	$lima = 'AND fu_ajk_cn.nmpenyakit ="'.$_REQUEST['sebebmeninggal'].'"';	}
if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}
//$met = $database->doQuery('SELECT * FROM fu_ajk_cn WHERE id_cn !="" AND type_claim="Death" AND del is NULL '.$satu.' '.$duaa.' '.$tiga.' '.$empt.' ORDER BY tgl_createcn ASC LIMIT '.$m.', 25');
	$met = $database->doQuery('SELECT
	fu_ajk_costumer.`name`,
	fu_ajk_polis.nmproduk,
	fu_ajk_cn.id,
	fu_ajk_cn.id_cabang,
	fu_ajk_cn.id_cost,
	fu_ajk_cn.id_cn,
	fu_ajk_cn.id_dn,
	fu_ajk_dn.dn_kode,
	fu_ajk_peserta.nama,
	fu_ajk_peserta.tgl_lahir,
	fu_ajk_peserta.usia,
	fu_ajk_peserta.totalpremi,
	fu_ajk_cn.total_claim,
	fu_ajk_peserta.kredit_tgl,
	fu_ajk_peserta.kredit_tenor,
	fu_ajk_peserta.kredit_akhir,
	fu_ajk_peserta.kredit_jumlah,
	DATEDIFF(fu_ajk_cn.tgl_claim,fu_ajk_peserta.kredit_tgl) AS jHari,
	fu_ajk_cn.tgl_claim,
	fu_ajk_cn.keterangan,
	fu_ajk_cn.tgl_document,
	fu_ajk_cn.tgl_document_lengkap,
	fu_ajk_cn.confirm_claim,
	fu_ajk_cn.tgl_byr_claim,
	fu_ajk_cn.nmpenyakit,
	fu_ajk_asuransi.`name` AS nmAsuransi,
	fu_ajk_klaim_status.status_klaim
	FROM
			
	fu_ajk_cn
	INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
	INNER JOIN fu_ajk_costumer ON fu_ajk_cn.id_cost = fu_ajk_costumer.id
	INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
	INNER JOIN fu_ajk_dn ON fu_ajk_cn.id = fu_ajk_dn.id
	INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
	INNER JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
	LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim.id_klaim_status = fu_ajk_klaim_status.id
	LEFT JOIN fu_ajk_grupproduk ON fu_ajk_grupproduk.id=fu_ajk_peserta.nama_mitra
	LEFT JOIN fu_ajk_namapenyakit ON fu_ajk_namapenyakit.id=fu_ajk_klaim.sebab_meninggal
	WHERE fu_ajk_cn.type_claim = "Death" AND fu_ajk_cn.del IS NULL 
	and confirm_claim !="Pending" '.$satu.' '.$duaa.' '.$tiga.' '.$empt.' '.$lima.' LIMIT '.$m.', 25');
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(fu_ajk_cn.id) FROM 
	fu_ajk_cn
	INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
	INNER JOIN fu_ajk_costumer ON fu_ajk_cn.id_cost = fu_ajk_costumer.id
	INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
	INNER JOIN fu_ajk_dn ON fu_ajk_cn.id = fu_ajk_dn.id
	INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
	INNER JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
	LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim.id_klaim_status = fu_ajk_klaim_status.id
	LEFT JOIN fu_ajk_grupproduk ON fu_ajk_grupproduk.id=fu_ajk_peserta.nama_mitra
	LEFT JOIN fu_ajk_namapenyakit ON fu_ajk_namapenyakit.id=fu_ajk_klaim.sebab_meninggal
	WHERE fu_ajk_cn.type_claim = "Death" AND fu_ajk_cn.del IS NULL 
	and confirm_claim !="Pending" '.$satu.' '.$duaa.' '.$tiga.' '.$empt.' '.$lima.''));
$totalRows = $totalRows[0];
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while ($met_ = mysql_fetch_array($met)) {
/*
$met_dn = mysql_fetch_array($database->doQuery('SELECT id, dn_kode FROM fu_ajk_dn WHERE id="'.$met_['id_dn'].'"'));
$met_produk = mysql_fetch_array($database->doQuery('SELECT id, nmproduk FROM fu_ajk_polis WHERE id="'.$met_['id_nopol'].'"'));
$allklaim = mysql_fetch_array($database->doQuery('SELECT fu_ajk_cn.id_peserta, fu_ajk_cn.id,
					fu_ajk_cn.id_cn,
					fu_ajk_cn.id_prm,
					fu_ajk_cn.id_cost,
					fu_ajk_cn.id_nopol,
					fu_ajk_cn.id_regional,
					fu_ajk_cn.id_cabang,
					fu_ajk_cn.tgl_createcn,
					fu_ajk_cn.tgl_claim,
					fu_ajk_cn.tgl_byr_claim,
					fu_ajk_cn.type_claim,
					fu_ajk_cn.premi,
					fu_ajk_cn.total_claim,
					fu_ajk_cn.confirm_claim,
					fu_ajk_cn.tgl_document,
					fu_ajk_cn.tgl_document_lengkap,
					fu_ajk_cn.del,
					fu_ajk_cn.tgl_investigasi,
					fu_ajk_cn.keterangan,
					fu_ajk_dn.id_polis_as,
					fu_ajk_dn.id_as,
					fu_ajk_asuransi.`name`
					FROM fu_ajk_cn
					INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
					INNER JOIN fu_ajk_peserta_as ON fu_ajk_cn.id_cost = fu_ajk_peserta_as.id_bank AND fu_ajk_cn.id_nopol = fu_ajk_peserta_as.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta_as.id_peserta
					INNER JOIN fu_ajk_asuransi ON fu_ajk_peserta_as.id_asuransi = fu_ajk_asuransi.id
					WHERE fu_ajk_cn.type_claim = "Death"'));
*/
if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
$met_peserta = mysql_fetch_array($database->doQuery('SELECT id, id_cost, id_polis, id_peserta,tgl_lahir, nama, usia, IF(type_data="SPK", kredit_tenor,kredit_tenor / 12) as kredit_tenor, kredit_tgl FROM fu_ajk_peserta WHERE id_cost="'.$met_['id_cost'].'" AND id_polis="'.$met_['id_nopol'].'" AND id_peserta="'.$met_['id_peserta'].'"'));
$diffday = (strtotime($allklaim['tgl_claim']) - strtotime($met_peserta['kredit_tgl']))/  ( 60 * 60 * 24 );

$cekDok = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_klaim_doc WHERE id_klaim="'.$met_['id'].'"'));
$cekTglKlaim = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_klaim WHERE id_cn="'.$met_['id'].'"'));
//if ($cekDok['nama_dokumen'] == NULL) {	$statusDokumen = '<font color="red">Tidak lengkap</font>';	}else{	$statusDokumen = '<font color="blue"><strong>Lengkap</strong></font>';	} 15102015
if ($cekTglKlaim['tgl_document_lengkap'] != "" AND $cekTglKlaim['tgl_document_lengkap'] > "0000-00-00") {	$statusDokumen = '<font color="blue"><strong>Lengkap</strong></font>';	}else{	$statusDokumen = '<font color="red">Tidak lengkap</font>';	}

$cekDok = mysql_num_rows($database->doQuery('SELECT * FROM fu_ajk_klaim_doc WHERE id_klaim="'.$met_['id'].'"'));
$jumDok = mysql_num_rows($database->doQuery('SELECT * FROM fu_ajk_dokumenklaim_bank WHERE id_bank="'.$met_['id_cost'].'"'));

echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	<td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
	<td>'.$met_['id_cabang'].'</td>
	<td>'.$met_['nmAsuransi'].'</td>
	<td>'.$met_['nama'].'</td>
	<td align="center">'._convertDate($met_['tgl_lahir']).'</td>
	<td align="center">'.$met_['usia'].'</td>
	<td align="right">'.duit($met_['kredit_jumlah']).'</td>
	<td align="right">'.duit($met_['total_claim']).'</td>
	<td align="center">'._convertDate($met_['kredit_tgl']).'</td>
	<td align="center">'.$met_['kredit_tenor'].'</td>
	<td align="center">'._convertDate($met_['tgl_claim']).'</td>
	<td align="center">'.$met_['jHari'].'</td>
	<td>'._convertDate($cekTglKlaim['tgl_lapor_klaim']).'</td>
	<td align="center" title="'.$jumDok.' kelengkapan dokumen klaim, hanya '.$cekDok.' dokumen yang dilengkapi.">'.$jumDok.' ('.$cekDok.') </td>
	<td>'._convertDate($cekTglKlaim['tgl_document_lengkap']).'</td>
	<td align="center">'.$statusDokumen.'</td>
	<td align="center" width="5%">'.$met_['status_klaim'].'</td>
	<td>'.$met_['keterangan'].'</td>
	<td>'.$met_['nmproduk'].'</td>
	</tr>';
	}
echo '<tr><td colspan="22">';
echo createPageNavigations($file = 'ajk_re_claim.php?c=klaim&re=dtAllKlaim&id_cost='.$_REQUEST['id_cost'].'&id_polis='.$_REQUEST['id_polis'].'&tglcheck1='.$_REQUEST['tglcheck1'].'&tglcheck2='.$_REQUEST['tglcheck2'].'&tglcheck3='.$_REQUEST['tglcheck3'].'&tglcheck4='.$_REQUEST['tglcheck4'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
echo '<b>Total Data Klaim: <u>' . $totalRows . '</u></b></td></tr>';
echo '</table>';
	}
}
		;
		break;

	case "cn":
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%"><tr><th width="95%" align="left">Modul Laporan Creditnote</font></th></tr></table>';
$metcost = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name DESC');
echo '<form method="post" action="">
		  <table border="0" cellpadding="1" cellspacing="0" width="100%">
			<tr><td width="40%" align="right">Nama Perusahaan <font color="red">*</font></td><td> : <select name="id_cost" id="id_cost">
			<option value="">---Pilih Perusahaan---</option>';
while($metcost_ = mysql_fetch_array($metcost)) {
	echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['id_cost'], $metcost_['id']).'>'.$metcost_['name'].'</option>';
}
echo '</select></td></tr>
			<tr><td align="right">Nama Produk</td>
				<td>: <select name="id_polis" id="id_polis">
					<option value="">-- Nama Produk --</option>
					</select></td></tr>
			<tr><td align="right">Tanggal Creditnote <font color="red">*</font></td>
				<td> :';print initCalendar();	print calendarBox('tglcek1', 'triger1', $_REQUEST['tglcek1']);	echo 's/d';
		print initCalendar();	print calendarBox('tglcek2', 'triger2', $_REQUEST['tglcek2']);
echo '</td></tr>
			<tr><td align="center"colspan="2"><input type="hidden" name="re" value="dataCN"><input type="submit" name="ere" value="Cari"></td></tr>
			</table>
			</form>';
if ($_REQUEST['re']=="dataCN") {
if ($_REQUEST['id_cost']=="") 	{	$error_1 = '<div align="center"><font color="red"><blink>Silahkan pilih nama perusahaan...!!<br /></div></font></blink>';	}
if ($_REQUEST['tglcek1']=="") 	{	$error_2 = '<div align="center"><font color="red"><blink>Tanggal mulai creditnote tidak boleh kosong<br /></div></font></blink>';	}
if ($_REQUEST['tglcek2']=="") 	{	$error_3 = '<div align="center"><font color="red"><blink>Tanggal akhir creditnote tidak boleh kosong</div></font></blink>';	}
if ($error_1 OR $error_2 OR $error_3) {	echo $error_1 .''.$error_2.''.$error_3;	}
else{
if ($_REQUEST['id_cost'])		{	$satu = 'AND fu_ajk_cn.id_cost = "'.$_REQUEST['id_cost'].'"';	}
if ($_REQUEST['id_polis'])		{	$tiga = 'AND fu_ajk_cn.id_nopol = "'.$_REQUEST['id_polis'].'"';	}
if ($_REQUEST['tglcek1'])		{	$duaa = 'AND fu_ajk_cn.tgl_createcn BETWEEN "'.$_REQUEST['tglcek1'].'" AND "'.$_REQUEST['tglcek2'].'" ';	}

$metCabang = $database->doQuery('SELECT
fu_ajk_cn.id,
fu_ajk_cn.id_cost,
fu_ajk_costumer.`name`,
fu_ajk_cn.id_nopol,
fu_ajk_polis.nmproduk,
fu_ajk_cn.id_cabang,
COUNT(fu_ajk_peserta.nama) AS jNama,
fu_ajk_peserta.status_aktif,
fu_ajk_peserta.status_peserta,
fu_ajk_peserta.gender,
fu_ajk_peserta.tgl_lahir,
fu_ajk_peserta.usia,
fu_ajk_peserta.kredit_jumlah,
fu_ajk_peserta.kredit_tgl,
IF (fu_ajk_peserta.type_data="SPK", fu_ajk_peserta.kredit_tenor * 12, fu_ajk_peserta.kredit_tenor) kredittenor,
fu_ajk_peserta.kredit_akhir,
fu_ajk_peserta.type_data,
fu_ajk_cn.premi,
fu_ajk_cn.total_claim,
fu_ajk_cn.input_by,
fu_ajk_cn.input_date,
fu_ajk_cn.type_claim,
fu_ajk_cn.tgl_claim,
fu_ajk_cn.tgl_createcn
FROM fu_ajk_cn
LEFT JOIN fu_ajk_costumer ON fu_ajk_cn.id_cost = fu_ajk_costumer.id
LEFT JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id = fu_ajk_peserta.id_klaim
WHERE fu_ajk_cn.del IS NULL AND fu_ajk_peserta.del IS NULL AND fu_ajk_cn.id != "" '.$satu.' '.$duaa.' '.$tiga.'
GROUP BY fu_ajk_cn.id_cabang ASC');

echo '<table border="0" width="100%" cellpadding="3" cellspacing="1">
		<tr><th width="1%">No</td>
			<th>Cabang</td>
			<th width="20%">Produk</td>
			<th width="10%">Jumlah Debitnote</td>
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
}
	echo '</table>';
}
}
	;
	break;

case "memocn":
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%"><tr><th width="95%" align="left">Modul Laporan Memorandum Creditnote</font></th></tr></table>';
$metmemocab = $database->doQuery('SELECT * FROM fu_ajk_cabang WHERE del IS NULL GROUP BY name ORDER BY name ASC');
echo '<form method="post" action="" name="frmcust">
	  <table border="0" cellpadding="1" cellspacing="1" width="100%">
	  <tr><td width="10%">Tanggal Creditnote </td>
	  	  <td width="15%">: ';print initCalendar();	print calendarBox('tglmemo', 'triger', $_REQUEST['tglmemo']); echo '</td>
	  	  <td><input type="hidden" name="ere" value="Memo"><input type="submit" name="metmome" value="Memo"></td>
	  </tr>
<!--	  <tr><td>Cabang </td><td>:
<select name="idcabang">
<option value="'.$_REQUEST['idclient'].'">-- Pilih Cabang --</option>';
while ($metmemocab_ = mysql_fetch_array($metmemocab)) {
echo '<option value="'.$metmemocab_['name'].'">'.$metmemocab_['name'].'</option>';
};
echo '</select> '.$cabCN.'</td></tr>-->
	  <!--<tr><td colspan="2"><input type="hidden" name="c" value="dataMemoCN"><input type="submit" name="ere" value="Memo"></td></tr>
	  <tr><td colspan="2"><input type="hidden" name="ere" value="Memo"><input type="submit" name="metmome" value="Memo"></td></tr>-->
	  </table></form>';
if ($_REQUEST['ere']=="Memo") {
if ($_REQUEST['tglmemo']=="") 		{	$tglCN1 = '<font color="red">Silahkan pilih tanggal Creditnote</font>';	}
else{	}
//if ($_REQUEST['idcabang']=="") 	{	$cabCN = '<font color="red">Silahkan pilih cabang Creditnote</font>';	}
if ($tglCN1) {
echo $tglCN1;
}
else{
$cekTglMemo = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cnmemo WHERE creatememo="'.$_REQUEST['tglmemo'].'"'));
if ($cekTglMemo['creatememo']) {
echo '<meta http-equiv="refresh" content="1;URL=ajk_re_claim.php?c=dataMemoCN&dtMemo='.$_REQUEST['tglmemo'].'">';
}else{
$metTabCN = $database->doQuery('SELECT fu_ajk_costumer.`name`, fu_ajk_polis.nmproduk, fu_ajk_cn.id_cost, fu_ajk_cn.id_cn, COUNT(fu_ajk_cn.id_peserta) AS tData, SUM(fu_ajk_cn.total_claim) AS tClaim, fu_ajk_cn.id_cabang
FROM fu_ajk_cn
INNER JOIN fu_ajk_costumer ON fu_ajk_cn.id_cost = fu_ajk_costumer.id
INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
WHERE fu_ajk_cn.tgl_createcn = "'.$_REQUEST['tglmemo'].'" '.$satu.'
GROUP BY fu_ajk_cn.id_cost, fu_ajk_cn.id_cabang ');

	$tglMemo = explode("-", $_REQUEST['tglmemo']);

while ($metTabCN_Nomor = mysql_fetch_array($metTabCN)) {
	$metIDmemo = mysql_fetch_array(mysql_query('SELECT id FROM fu_ajk_cnmemo ORDER BY id DESC'));
	$idmemonya = $metIDmemo['id'] + 1; $idmemonya = '10000'. + $idmemonya;
	$metKodeMemo =substr($idmemonya,1).'/MEMO/ASR-SPA/'.KonDecRomawi($tglMemo[1]).'/'.substr($tglMemo[0],2);
	$memomamet = mysql_query('INSERT INTO fu_ajk_cnmemo SET idcost="'.$metTabCN_Nomor['id_cost'].'",
															kodememo="'.$metKodeMemo.'",
															creatememo = "'.$_REQUEST['tglmemo'].'",
															cabangmemo = "'.$metTabCN_Nomor['id_cabang'].'",
															input_by = "'.$q['id'].'",
															input_date = "'.$futgl.'"');
	}
}
echo '<meta http-equiv="refresh" content="1;URL=ajk_re_claim.php?c=dataMemoCN&dtMemo='.$_REQUEST['tglmemo'].'">';
}

}


	;
	break;

case "dataMemoCN":
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
	  <tr><th colspan="2" align="left">Modul Laporan Memorandum Creditnote</font></th></tr>
	  <tr><td width="15%"><b>Tanggal Memo Creditnote</b></td><td><b>: '._convertDate($_REQUEST['dtMemo']).'</b></td></tr>
	  </table>';
$cekMetMemo = mysql_fetch_array($database->doQuery('SELECT creatememo FROM fu_ajk_cnmemo WHERE creatememo="'.$_REQUEST['dtMemo'].'"'));
if ($cekMetMemo['creatememo']) {
echo '<table border="0" width="100%" cellpadding="3" cellspacing="1" style="border: solid 1px #DEDEDE">
		<tr><th width="1%">No</th>
			<th width="20%">Nomor Memo</th>
			<th>Perusahaan</th>
			<th width="20%">Cabang</th>
			<th width="10%">Input By</th>
			<th width="20%">Input Date</th>
		</tr>';
$metMemo = $database->doQuery('SELECT fu_ajk_costumer.`name`,
									  fu_ajk_cnmemo.id,
									  fu_ajk_cnmemo.kodememo,
									  fu_ajk_cnmemo.creatememo,
									  fu_ajk_cnmemo.cabangmemo,
									  fu_ajk_cnmemo.input_by,
									  fu_ajk_cnmemo.input_date,
									  pengguna.nm_lengkap
									  FROM fu_ajk_cnmemo
									  INNER JOIN fu_ajk_costumer ON fu_ajk_cnmemo.idcost = fu_ajk_costumer.id
									  INNER JOIN pengguna ON fu_ajk_cnmemo.input_by = pengguna.id
									  WHERE fu_ajk_cnmemo.creatememo ="'.$_REQUEST['dtMemo'].'"');
while ($metMemo_ = mysql_fetch_array($metMemo)) {
if (($no % 2) == 1) $objlass = 'tbl-odd';else $objlass = 'tbl-even';
$_metOpsi = '<a target="_blank" href="ajk_re_claim.php?c=viewMemo&tglCN='.$_REQUEST['tglmemo'].'&cabCN='.$metTabCN_Nomor['id_cabang'].'&jData='.$metTabCN_Nomor['tData'].'&idp='.$metTabCN_Nomor['id_cost'].'">Memo</a>';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	   <td>'.++$no.'</td>
	   <td><a href="e_report.php?er=memocn&idMemo='.$metMemo_['id'].'">'.$metMemo_['kodememo'].'</a></td>
	   <td>'.$metMemo_['name'].'</td>
	   <td>'.$metMemo_['cabangmemo'].'</td>
	   <td align="center">'.$metMemo_['nm_lengkap'].'</td>
	   <td align="center">'.$metMemo_['input_date'].'</td>
	   </tr>';
}
}else{
	echo '<center><a href="ajk_re_claim.php?c=memocn"><font color="red">Data memo tidak ada, Kembali <<<</font></a></center>';
}
	;
	break;


case "viewMemo":
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
	  <tr><th width="95%" align="left">Modul Laporan Memorandum Creditnote</font></th></tr>
	  <tr><td colspan="2" align="right"><a href="e_report.php?er=memocn&tglCN='.$_REQUEST['tglCN'].'&cabCN='.$_REQUEST['cabCN'].'&jData='.$_REQUEST['jData'].'&idp='.$_REQUEST['idp'].'&u='.$q['id'].'" title="print memorandum CN" onClick="if(confirm(\'Data memo akan tersimpan ke dalam database sesuai tanggal dan cabang yang dipilih ?\')){return true;}{return false;}"><img src="image/dninvoice.png" width="30"></a></td></tr>
	  </table>';
/*echo $_REQUEST['tglCN'].'<br />';
echo $_REQUEST['cabCN'].'<br />';
echo $_REQUEST['jData'].'<br />';
echo $_REQUEST['idp'].'<br />';*/
//$dateMemo = date('Y-m-d', strtotime('-1 days', strtotime($_REQUEST['tglmemo'])));
$metIDmemo = mysql_fetch_array($database->doQuery('SELECT (id + 1) as idmemo FROM fu_ajk_cnmemo ORDER BY id DESC'));
$idmemonya = $metIDmemo['id'] + 1;
$metMemoData = $database->doQuery('SELECT fu_ajk_costumer.`name`,
										  fu_ajk_polis.nmproduk,
										  fu_ajk_polis.rek_1,
										  fu_ajk_cn.id_peserta,
										  fu_ajk_peserta.nama,
										  fu_ajk_dn.dn_kode,
										  fu_ajk_cn.tgl_claim,
										  fu_ajk_cn.type_claim,
										  fu_ajk_peserta.totalpremi,
										  fu_ajk_cn.total_claim
										  FROM fu_ajk_cn
										  LEFT JOIN fu_ajk_costumer ON fu_ajk_cn.id_cost = fu_ajk_costumer.id
										  LEFT JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
										  LEFT JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
										  LEFT JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
										  WHERE fu_ajk_cn.del IS NULL AND fu_ajk_cn.tgl_createcn = "'.$_REQUEST['tglCN'].'" AND fu_ajk_cn.id_cabang = "'.$_REQUEST['cabCN'].'" ');
$tglMemo = explode("-", $_REQUEST['tglCN']);
echo '<table border="0" cellpadding="1" cellspacing="0" width="70%" align="center">
	<tr><td align="center" colspan="4"><b>MEMORANDUM</b></td></tr>
	<tr><td align="center" colspan="4"><b>NO.'.$idmemonya.'/MEMO/ASR-SPA/'.KonDecRomawi($tglMemo[1]).'/'.substr($tglMemo[0],2).'</b></td></tr>
	<tr><td align="right" width="35%">&nbsp;</td><td width="10%">Kepada</td><td colspan="2">: Bag. Adm Kredit/Sundries</td></tr>
	<tr><td align="right" width="35%">&nbsp;</td><td width="10%">Dari</td><td colspan="2">: Bag. Asuransi</td></tr>
	<tr><td align="right" width="35%">&nbsp;</td><td width="10%">Perihal</td><td colspan="2">: Refund Asuransi ADONAI &nbsp; '.$_REQUEST['jData'].' DEB '.$_REQUEST['cabCN'].'</td></tr>
	<tr><td align="right" width="35%">&nbsp;</td><td width="10%">Tanggal</td><td colspan="2">: '.$tglMemo[2].' '.bulan($tglMemo[1]).' '.$tglMemo[0].'</td></tr>
	<tr><td align="center" colspan="3"><hr></td></tr>
	<tr><td colspan="4" valign="justify">Sehubungan dengan telah disetujuinya Refund Asuransi dari PT. ADONAI untuk debitur atas nama '.$_REQUEST['jData'].' DEB '.$_REQUEST['cabCN'].' maka dengan ini kami mohon bantuannya untuk transaksi sbb:</td></tr>
	</table>
	<table border="0" cellspacing="0" width="70%" align="center">';
while ($metMemoData_ = mysql_fetch_array($metMemoData)) {
echo '<tr><td width="40%">'.$metMemoData_['nama'].'</td>
		  <td width="20%" align="right">'.duit($metMemoData_['total_claim']).'</td>
		  <td width="40%">&nbsp;</td>
	  </tr>';
$tJumlahCN +=$metMemoData_['total_claim'];
}
$metRekCabang = mysql_fetch_array($database->doQuery('SELECT fu_ajk_rekening.id,
															 fu_ajk_rekening.rek_dn_cabang,
															 fu_ajk_rekening.rek_dn_cabang_name,
															 fu_ajk_rekening.rek_dn_nomor,
															 fu_ajk_rekening.rek_cn_cabang,
															 fu_ajk_rekening.rek_cn_cabang_name,
															 fu_ajk_rekening.rek_cn_nomor,
															 fu_ajk_rekening.pic_cab,
															 fu_ajk_rekening.pic_cab_jabatan,
															 fu_ajk_cabang.`name`,
															 fu_ajk_costumer.rekdebet,
															 fu_ajk_costumer.rekdebet_an,
															 fu_ajk_costumer.rekcredit,
															 fu_ajk_costumer.rekcredit_an,
															 fu_ajk_costumer.pic,
															 fu_ajk_costumer.picjabatan
															 FROM fu_ajk_rekening
															 LEFT JOIN fu_ajk_costumer ON fu_ajk_rekening.id_cost = fu_ajk_costumer.id
															 LEFT JOIN fu_ajk_cabang ON fu_ajk_rekening.id_cost = fu_ajk_cabang.id_cost AND fu_ajk_rekening.cabang = fu_ajk_cabang.id
															 WHERE fu_ajk_rekening.id != "" AND fu_ajk_rekening.id_cost="'.$_REQUEST['idp'].'" AND fu_ajk_cabang.name="'.$_REQUEST['cabCN'].'" AND fu_ajk_cabang.del IS NULL'));
echo '<tr><td>&nbsp;</td><td align="right"><b>'.duit($tJumlahCN).'</b></td><td>&nbsp;</td></tr>
	  <tr><td colspan="3"><hr></td></tr></table>
	  <table border="0" cellspacing="0" cellpadding="5" width="70%" align="center">
	  <tr><td width="10%" valign="top">Debet</td>
	  	  <td width="60%">REK NO. 1000660431<br />PT. ADONAI PIALANG ASURANSI</td>
	  	  <td width="1%" valign="top">Rp. </td>
	  	  <td align="right" valign="top"><b>'.duit($tJumlahCN).'</b></td>
	  </tr>
	  <tr><td width="10%" valign="top">Kredit</td>
	  	  <td width="60%">REK KS NO. '.$metRekCabang['rekcredit'].'<br />('.$metRekCabang['rekcredit_an'].')</td>
	  	  <td width="1%" valign="top">Rp. </td>
	  	  <td align="right" valign="top"><b>'.duit($tJumlahCN).'</b></td>
	  </tr>
	  <tr><td colspan="4"><hr></td</tr>
	  <tr><td width="10%" valign="top">Debet</td>
	  	  <td width="60%">REK NO. '.$metRekCabang['rekdebet'].'<br />'.$metRekCabang['rekdebet_an'].'</td>
	  	  <td width="1%" valign="top">Rp. </td>
	  	  <td align="right" valign="top"><b>'.duit($tJumlahCN).'</b></td>
	  </tr>
	  <tr><td width="10%" valign="top">Kredit</td>
	  	  <td width="60%">REK NO. '.$metRekCabang['rek_cn_nomor'].'<br />('.$metRekCabang['rek_cn_cabang_name'].')</td>
	  	  <td width="1%" valign="top">Rp. </td>
	  	  <td align="right" valign="top"><b>'.duit($tJumlahCN).'</b></td>
	  </tr>
	  <tr><td colspan="4"><hr></td</tr>
	  <tr><td colspan="4">Demikian hal ini disampaikan, atas perhatian dan kerjasamanya diucapkan terima kasih.</td></tr>
	  </table>
	  <table border="0" cellspacing="0" cellpadding="5" width="70%" align="center">
	  <tr><td width="45%"><br /><br /><br /><br />'.$metRekCabang['pic'].'<br />'.$metRekCabang['picjabatan'].'</td>
	  	  <td width="20%"></td>
	  	  <td width="25%"><br /><br /><br /><br />'.$metRekCabang['pic_cab'].'<br />'.$metRekCabang['pic_cab_jabatan'].'</td>
	  </tr>
	  </table>';
	;
	break;


	default:

		;
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
		},
		loadingImage:\'../loader1.gif\',
		loadingText:\'Loading...\',
		debug:0
	} )
});
</script>';
?>
