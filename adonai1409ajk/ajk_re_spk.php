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
switch ($_REQUEST['er']) {
	case "a":;
	break;

	case "sSPK":
		echo '<link rel="stylesheet" href="../javascript/jscssmobile/css/lightbox.css" type="text/css" media="screen" />
			  <script src="../javascript/jscssmobile/js/prototype.js" type="text/javascript"></script>
			  <script src="../javascript/jscssmobile/js/scriptaculous.js?load=effects" type="text/javascript"></script>
			  <script src="../javascript/jscssmobile/js/lightbox.js" type="text/javascript"></script>';
		echo '<table border="0" cellpadding="5" cellspacing="0" width="100%"><tr><th width="95%" align="left">Modul Laporan Rekapitulasi Status SPK</font></th></tr></table>';

		$metcost = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name DESC');
		$mepolis = $database->doQuery('SELECT * FROM fu_ajk_polis where del is null ORDER BY nmproduk');

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
						<option value="">-- Pilih Produk --</option>';
		
		while($metpolis_ = mysql_fetch_array($mepolis)) {
			echo  '<option value="'.$metpolis_['id'].'"'._selected($_REQUEST['id_polis'], $metpolis_['id']).'>'.$metpolis_['nmproduk'].'</option>';
		}
						echo '</select></td></tr>
					  <tr><td align="right">Tanggal Input SPK <font color="red">*</font></td>
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
					  <tr><td width="40%" align="right">Status</td><td> : <select name="statusnya">
						<option value="">---Pilih Status---</option>
						<option value="Realisasi"'.pilih($_REQUEST["statusnya"], "Realisasi").'>Realisasi</option>
						<option value="Aktif"'.pilih($_REQUEST["statusnya"], "Aktif").'>Aktif</option>
						<option value="Approve"'.pilih($_REQUEST["statusnya"], "Approve").'>Approve</option>
						<option value="Batal"'.pilih($_REQUEST["statusnya"], "Batal").'>Batal</option>
						<option value="Proses"'.pilih($_REQUEST["statusnya"], "Proses").'>Proses</option>
						<option value="Tolak"'.pilih($_REQUEST["statusnya"], "Tolak").'>Tolak</option>
					</select></td></tr>
					  <tr><td align="center"colspan="2"><input type="hidden" name="re" value="datapeserta"><input type="submit" name="ere" value="Cari"></td></tr>
					  </table>
					  </form>';
		if ($_REQUEST['re']=="datapeserta") {
		if ($_REQUEST['id_cost']=="") 	{	$error_1 = '<div align="center"><font color="red"><blink>Silahkan pilih nama perusahaan...!!<br /></div></font></blink>';	}
		if ($_REQUEST['tglcheck1']=="") 	{	$error_2 = '<div align="center"><font color="red"><blink>Tanggal input SPK tidak boleh kosong<br /></div></font></blink>';	}
		if ($_REQUEST['tglcheck2']=="") 	{	$error_3 = '<div align="center"><font color="red"><blink>Tanggal input SPK tidak boleh kosong</div></font></blink>';	}
		if ($error_1 OR $error_2 OR $error_3) {	echo $error_1 .''.$error_2.''.$error_3;	}
		else{
		echo '<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="#E2E2E2">
					<tr><td bgcolor="#FFF"colspan="24"><a href="e_report.php?er=eL_RekapSPK&idCost='.$_REQUEST['id_cost'].'&idPolis='.$_REQUEST['id_polis'].'&tgl1='.$_REQUEST['tglcheck1'].'&tgl2='.$_REQUEST['tglcheck2'].'&st='.$_REQUEST['statusnya'].'"><img src="image/excel.png" width="25" border="0"><br />Excel</a></td></tr>
					<th width="1%">No</th>
					<th>Nama Perusahaan</th>
					<th width="10%">Produk</th>
					<th width="10%">STATUS</th>
					<th width="10%">Nama Debitur</th>
					<th width="10%">Nomor SPK</th>
					<th width="1%">Cabang</th>
					<th width="1%">Tanggal SPK</th>
					<th width="1%">Tanggal Approve SPV</th>
					<th width="1%">Tanggal periksa dokter cabang</th>
					<th width="1%">Tanggal Assign</th>
					<th width="1%">User Assign</th>
					<th width="1%">Tanggal Em</th>
					<th width="1%">User EM</th>
					<th width="1%">Tanggal approve dokter Adonai</th>
					<th width="1%">Foto Debitur</th>
					<th width="1%">TTD Debitur</th>
					<th width="1%">Foto SK</th>
					<th width="1%">TTD Dokter</th>
					<th width="1%">Input SPK</th>
					</tr>';
		if ($_REQUEST['id_cost'])		{	$satu = 'AND fu_ajk_spak.id_cost = "'.$_REQUEST['id_cost'].'"';	}
		//if ($_REQUEST['tglcheck1'])		{	$duaa = 'AND fu_ajk_spak.input_date BETWEEN "'.$_REQUEST['tglcheck1'].'" AND "'.$_REQUEST['tglcheck2'].'" ';	}
		if ($_REQUEST['tglcheck1'])		{
			if ($_REQUEST['tglcheck1'] == $_REQUEST['tglcheck2']) {
				$PenambahanTgl = strtotime ( '+1 day' , strtotime ( $_REQUEST['tglcheck2'] ) ) ;;
				$newdate = date ( 'Y-m-d' , $PenambahanTgl );
				$duaa = 'AND fu_ajk_spak.input_date BETWEEN "'.$_REQUEST['tglcheck1'].'" AND "'.$newdate.'" ';
			}else{
				$PenambahanTgl = strtotime ( '+1 day' , strtotime ( $_REQUEST['tglcheck2'] ) ) ;;
				$newdate2 = date ( 'Y-m-d' , $PenambahanTgl );
				$duaa = 'AND fu_ajk_spak.input_date BETWEEN "'.$_REQUEST['tglcheck1'].'" AND "'.$newdate2.'" ';
			}
		}
		if ($_REQUEST['statusnya'])		{ $tiga = 'AND fu_ajk_spak.status = "'.$_REQUEST['statusnya'].'"';
			//if ($_REQUEST['statusnya']=="Realisasi") {
			//$tiga = 'AND fu_ajk_spak.status = "Aktif" AND fu_ajk_peserta.id_dn !=""';	}
			//else{
			//$tiga = 'AND fu_ajk_spak.status = "'.$_REQUEST['statusnya'].'"';
			}

		if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 10;	}	else {	$m = 0;		}
		$met = $database->doQuery('SELECT

		fu_ajk_costumer.`name`,
		fu_ajk_polis.nmproduk,
		fu_ajk_spak.id,
		fu_ajk_spak.id_cost,
		fu_ajk_spak.id_polis,
		fu_ajk_spak.spak,
		fu_ajk_spak.`status`,
		fu_ajk_spak.input_by,
		fu_ajk_spak.assign_by,
		DATE_FORMAT(fu_ajk_spak.assign_date,"%Y-%m-%d") AS assign_date,
		fu_ajk_spak.approve_em_by,
		DATE_FORMAT(fu_ajk_spak.approve_em_date,"%Y-%m-%d") AS approve_em_date,
		DATE_FORMAT(fu_ajk_spak.input_date,"%Y-%m-%d") AS tglInput,
		DATE_FORMAT(fu_ajk_spak.update_date,"%Y-%m-%d") AS tglApprove,
		fu_ajk_spak_form.nama,
		fu_ajk_spak_form.input_by AS ApproveDokterCabang,
		DATE_FORMAT(fu_ajk_spak_form.input_date,"%Y-%m-%d") AS ApproveTglDokterCabang,
		fu_ajk_spak.approve_by AS ApproveDokterUW,
		DATE_FORMAT(fu_ajk_spak.approve_date,"%Y-%m-%d") AS ApproveTglDokterUW,
		fu_ajk_spak_form.filefotodebitursatu,
		fu_ajk_spak_form.filefotodebiturdua,
		fu_ajk_spak_form.filefotoktp,
		fu_ajk_spak_form.filettddebitur,
		fu_ajk_spak_form.filettdmarketing,
		fu_ajk_spak_form.filettddokter,
		fu_ajk_spak_form.filefotoskpensiun,
		fu_ajk_spak_form.cabang
		FROM fu_ajk_spak
		INNER JOIN fu_ajk_spak_form ON fu_ajk_spak.id_cost = fu_ajk_spak_form.idcost AND fu_ajk_spak.id = fu_ajk_spak_form.idspk
		INNER JOIN fu_ajk_costumer ON fu_ajk_spak.id_cost = fu_ajk_costumer.id
		INNER JOIN fu_ajk_polis ON fu_ajk_spak.id_polis = fu_ajk_polis.id
		WHERE  fu_ajk_spak.id !="" '.$satu.' '.$duaa.' '.$tiga.' AND fu_ajk_spak_form.del IS NULL
		ORDER BY fu_ajk_spak.input_date DESC LIMIT '.$m.', 10');

		$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(fu_ajk_spak.id)
		FROM fu_ajk_spak
		INNER JOIN fu_ajk_spak_form ON fu_ajk_spak.id_cost = fu_ajk_spak_form.idcost AND fu_ajk_spak.id = fu_ajk_spak_form.idspk
		INNER JOIN fu_ajk_costumer ON fu_ajk_spak.id_cost = fu_ajk_costumer.id
		INNER JOIN fu_ajk_polis ON fu_ajk_spak.id_polis = fu_ajk_polis.id
		WHERE  fu_ajk_spak.id !="" '.$satu.' '.$duaa.' '.$tiga.' AND fu_ajk_spak_form.del IS NULL'));
			$totalRows = $totalRows[0];
			$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
		while ($met_ = mysql_fetch_array($met)) {
		if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
		if (is_numeric($met_['cabang'])) {
			$met_Cabang = mysql_fetch_array(mysql_query('SELECT id, name FROM fu_ajk_cabang WHERE id="'.$met_['cabang'].'"'));
			$inputcabang = $met_Cabang['name'];
		}else{
			$inputcabang = $met_['cabang'];
		}
		if ($met_['filefotodebitursatu']=="") { $photo1 ='<a href="../image/non-user.png" rel="lightbox"><img src="../image/non-user.png" width="50">';
		}else{ $photo1 ='<a href="../../ajkmobilescript/'.$met_['filefotodebitursatu'].'" rel="lightbox"><img src="../../ajkmobilescript/'.$met_['filefotodebitursatu'].'" width="50">'; }
		if ($met_['filettddebitur']=="") { $photo2 ='<a href="../image/non-user.png" rel="lightbox"><img src="../image/non-user.png" width="50">';
		}else{ $photo2 ='<a href="../../ajkmobilescript/'.$met_['filettddebitur'].'" rel="lightbox"><img src="../../ajkmobilescript/'.$met_['filettddebitur'].'" width="50">'; }
		if ($met_['filefotoskpensiun']=="") { $photo3 ='<a href="../image/non-user.png" rel="lightbox"><img src="../image/non-user.png" width="50">';
		}else{ $photo3 ='<a href="../../ajkmobilescript/'.$met_['filefotoskpensiun'].'" rel="lightbox"><img src="../../ajkmobilescript/'.$met_['filefotoskpensiun'].'" width="50">'; }
		if ($met_['filettddokter']=="") { $photo4 ='<a href="../image/non-user.png" rel="lightbox"><img src="../image/non-user.png" width="50">';
		}else{ $photo4 ='<a href="../../ajkmobilescript/'.$met_['filettddokter'].'" rel="lightbox"><img src="../../ajkmobilescript/'.$met_['filettddokter'].'" width="50">'; }
		//;
		if (is_numeric($met_['input_by'])) {
		$nmUser = mysql_fetch_array($database->doQuery('SELECT id, namalengkap FROM user_mobile WHERE id="'.$met_['input_by'].'"'));
		$_nmMarketing = $nmUser['namalengkap'];
		}else{	$_nmMarketing = $met_['input_by'];	}
		echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
				  <td align="center">'.(++$no + ($pageNow-1) * 10).'</td>
				  <td>'.$met_['name'].'</td>
				  <td>'.$met_['nmproduk'].'</td>
				  <td align="center">'.$met_['status'].'</td>
				  <td>'.$met_['nama'].'</td>
				  <td align="center">'.$met_['spak'].'</td>
				  <td align="center">'.$inputcabang.'</td>
				  <td align="center">'._ConvertDate($met_['tglInput']).'</td>
				  <td align="center">'._ConvertDate($met_['tglApprove']).'</td>
				  <td align="center">'._ConvertDate($met_['ApproveTglDokterCabang']).'</td>
				  <td align="center">'._ConvertDate($met_['assign_date']).'</td>
				  <td align="center">'.$met_['assign_by'].'</td>
				  <td align="center">'._ConvertDate($met_['approve_em_date']).'</td>
				  <td align="center">'.$met_['approve_em_by'].'</td>
				  <td align="center">'._ConvertDate($met_['ApproveTglDokterUW']).'</td>
				  <td align="center">'.$photo1.'</td>
				  <td align="center">'.$photo2.'</td>
				  <td align="center">'.$photo3.'</td>
				  <td align="center">'.$photo4.'</td>
				  <td>'.$_nmMarketing.'</td>
				  </tr>';
		}
			echo '<tr><td colspan="24">';
			echo createPageNavigations($file = 'ajk_re_spk.php?er=sSPK&re=datapeserta&id_cost='.$_REQUEST['id_cost'].'&id_polis='.$_REQUEST['id_polis'].'&tglcheck1='.$_REQUEST['tglcheck1'].'&tglcheck2='.$_REQUEST['tglcheck2'].'&statusnya='.$_REQUEST['statusnya'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 10);
			echo '<b>Total Data SPK: <u>' . $totalRows . '</u></b></td></tr>';
			echo '</table>';
		}
		}
	break;

	case "hisSPK":
		echo '<link rel="stylesheet" href="../javascript/jscssmobile/css/lightbox.css" type="text/css" media="screen" />
	  <script src="../javascript/jscssmobile/js/prototype.js" type="text/javascript"></script>
	  <script src="../javascript/jscssmobile/js/scriptaculous.js?load=effects" type="text/javascript"></script>
	  <script src="../javascript/jscssmobile/js/lightbox.js" type="text/javascript"></script>';
		echo '<table border="0" cellpadding="5" cellspacing="0" width="100%"><tr><th width="95%" align="left">Modul Laporan Historical SPK</font></th></tr></table>';
		$metcost = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name DESC');
		echo '<form method="post" action="">
					  <input type="hidden" name="id_cost" value="'.$metcost['id'].'">
					  <table border="0" cellpadding="1" cellspacing="0" width="100%">
				      <tr><td width="40%" align="right">Nama Perusahaan <font color="red">*</font></td><td> : <select name="id_cost" id="id_cost">
					  	<option value="">---Pilih Perusahaan---</option>';
		while($metcost_ = mysql_fetch_array($metcost)) {
			echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['id_cost'], $metcost_['id']).'>'.$metcost_['name'].'</option>';
		}
		echo '</select></td></tr>
					  <tr><td align="right">Tanggal Input SPK <font color="red">*</font></td>
					 	  <td> :';print initCalendar();	print calendarBox('tglcheck1', 'triger1', $_REQUEST['tglcheck1']);	echo 's/d';
		print initCalendar();	print calendarBox('tglcheck2', 'triger2', $_REQUEST['tglcheck2']);
		echo '</td></tr>
					  <tr><td align="center"colspan="2"><input type="hidden" name="re" value="datapeserta"><input type="submit" name="ere" value="Cari"></td></tr>
					  </table>
					  </form>';
		if ($_REQUEST['re']=="datapeserta") {
			if ($_REQUEST['id_cost']=="") 	{	$error_1 = '<div align="center"><font color="red"><blink>Silahkan pilih nama perusahaan...!!<br /></div></font></blink>';	}
			if ($_REQUEST['tglcheck1']=="") 	{	$error_2 = '<div align="center"><font color="red"><blink>Tanggal input SPK tidak boleh kosong<br /></div></font></blink>';	}
			if ($_REQUEST['tglcheck2']=="") 	{	$error_3 = '<div align="center"><font color="red"><blink>Tanggal input SPK tidak boleh kosong</div></font></blink>';	}
			if ($error_1 OR $error_2 OR $error_3) {	echo $error_1 .''.$error_2.''.$error_3;	}
			else{
				echo '<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="#E2E2E2">
					<tr><td bgcolor="#FFF"colspan="23"><a href="e_report.php?er=eL_HistSPK&idCost='.$_REQUEST['id_cost'].'&idPolis='.$_REQUEST['id_polis'].'&tgl1='.$_REQUEST['tglcheck1'].'&tgl2='.$_REQUEST['tglcheck2'].'&st='.$_REQUEST['statusnya'].'"><img src="image/excel.png" width="25" border="0"><br />Excel</a></td></tr>
					<th width="1%">No</th>
					<th>No.SPK</th>
					<th>ID CN</th>
					<th>ID Peserta</th>
					<th>Nama Peserta</th>
					<th>Nama Marketing</th>
					<th>Tanggal SPK</th>
					<th>Plafon</th>
					<th>Premi</th>
					<th>Nama Dokter Periksa</th>
					<th>Tanggal Dokter Periksa</th>
					<th>Cabang</th>
					</tr>';
				if ($_REQUEST['id_cost'])		{	$satu = 'AND fu_ajk_spak.id_cost = "'.$_REQUEST['id_cost'].'"';	}
				//if ($_REQUEST['tglcheck1'])		{	$duaa = 'AND fu_ajk_spak.input_date BETWEEN "'.$_REQUEST['tglcheck1'].'" AND "'.$_REQUEST['tglcheck2'].'" ';	}
				if ($_REQUEST['tglcheck1'])		{
					if ($_REQUEST['tglcheck1'] == $_REQUEST['tglcheck2']) {
						$PenambahanTgl = strtotime ( '+1 day' , strtotime ( $_REQUEST['tglcheck2'] ) ) ;;
						$newdate = date ( 'Y-m-d' , $PenambahanTgl );
						$duaa = 'AND fu_ajk_spak.input_date BETWEEN "'.$_REQUEST['tglcheck1'].'" AND "'.$newdate.'" ';
					}else{
						$PenambahanTgl = strtotime ( '+1 day' , strtotime ( $_REQUEST['tglcheck2'] ) ) ;;
						$newdate2 = date ( 'Y-m-d' , $PenambahanTgl );
						$duaa = 'AND fu_ajk_spak.input_date BETWEEN "'.$_REQUEST['tglcheck1'].'" AND "'.$newdate2.'" ';
					}
				}
				if ($_REQUEST['statusnya'])		{ $tiga = 'AND fu_ajk_spak.status = "'.$_REQUEST['statusnya'].'"';
					//if ($_REQUEST['statusnya']=="Realisasi") {
					//$tiga = 'AND fu_ajk_spak.status = "Aktif" AND fu_ajk_peserta.id_dn !=""';	}
					//else{
					//$tiga = 'AND fu_ajk_spak.status = "'.$_REQUEST['statusnya'].'"';
				}

				if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 10;	}	else {	$m = 0;		}
				$met = $database->doQuery('SELECT
fu_ajk_cn.id_cn,
fu_ajk_cn.id_peserta,
fu_ajk_peserta.nama,
fu_ajk_peserta.spaj,
fu_ajk_spak.spak,
fu_ajk_spak.input_by,
fu_ajk_spak_form.dokter_pemeriksa,
fu_ajk_spak_form.tgl_periksa,
date_format(fu_ajk_spak.input_date,"%Y-%m-%d") as input_date,
fu_ajk_peserta.kredit_jumlah AS plafond,
fu_ajk_peserta.totalpremi AS premi,
fu_ajk_peserta.cabang
FROM fu_ajk_cn
LEFT JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id = fu_ajk_peserta.id_klaim
LEFT JOIN fu_ajk_spak ON fu_ajk_peserta.id_cost = fu_ajk_spak.id_cost AND fu_ajk_peserta.id_polis = fu_ajk_spak.id_polis AND fu_ajk_peserta.spaj = fu_ajk_spak.spak
LEFT JOIN fu_ajk_spak_form ON fu_ajk_spak.id_cost = fu_ajk_spak_form.idcost AND fu_ajk_spak.id = fu_ajk_spak_form.idspk
WHERE fu_ajk_cn.type_claim = "Death" AND fu_ajk_cn.id_nopol = "1" AND date_format(fu_ajk_spak.input_date,"%Y-%m-%d") BETWEEN "'.$_REQUEST['tglcheck1'].'" AND "'.$_REQUEST['tglcheck2'].'"');

				$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(*)
				FROM fu_ajk_cn
				LEFT JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id = fu_ajk_peserta.id_klaim
				LEFT JOIN fu_ajk_spak ON fu_ajk_peserta.id_cost = fu_ajk_spak.id_cost AND fu_ajk_peserta.id_polis = fu_ajk_spak.id_polis AND fu_ajk_peserta.spaj = fu_ajk_spak.spak
				LEFT JOIN fu_ajk_spak_form ON fu_ajk_spak.id_cost = fu_ajk_spak_form.idcost AND fu_ajk_spak.id = fu_ajk_spak_form.idspk
				WHERE fu_ajk_cn.type_claim = "Death" AND fu_ajk_cn.id_nopol = "1" AND date_format(fu_ajk_spak.input_date,"%Y-%m-%d") BETWEEN "'.$_REQUEST['tglcheck1'].'" AND "'.$_REQUEST['tglcheck2'].'"'));
				$totalRows = $totalRows[0];
				$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
				while ($met_ = mysql_fetch_array($met)) {
					if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
					if (is_numeric($met_['cabang'])) {
						$met_Cabang = mysql_fetch_array(mysql_query('SELECT id, name FROM fu_ajk_cabang WHERE id="'.$met_['cabang'].'"'));
						$inputcabang = $met_Cabang['name'];
					}else{
						$inputcabang = $met_['cabang'];
					}
					if ($met_['filefotodebitursatu']=="") { $photo1 ='<a href="../image/non-user.png" rel="lightbox"><img src="../image/non-user.png" width="50">';
					}else{ $photo1 ='<a href="../../ajkmobilescript/'.$met_['filefotodebitursatu'].'" rel="lightbox"><img src="../../ajkmobilescript/'.$met_['filefotodebitursatu'].'" width="50">'; }
					if ($met_['filettddebitur']=="") { $photo2 ='<a href="../image/non-user.png" rel="lightbox"><img src="../image/non-user.png" width="50">';
					}else{ $photo2 ='<a href="../../ajkmobilescript/'.$met_['filettddebitur'].'" rel="lightbox"><img src="../../ajkmobilescript/'.$met_['filettddebitur'].'" width="50">'; }
					if ($met_['filefotoskpensiun']=="") { $photo3 ='<a href="../image/non-user.png" rel="lightbox"><img src="../image/non-user.png" width="50">';
					}else{ $photo3 ='<a href="../../ajkmobilescript/'.$met_['filefotoskpensiun'].'" rel="lightbox"><img src="../../ajkmobilescript/'.$met_['filefotoskpensiun'].'" width="50">'; }
					//;
if (is_numeric($met_['input_by'])) {
	$nmUser = mysql_fetch_array($database->doQuery('SELECT id, namalengkap FROM user_mobile WHERE id="'.$met_['input_by'].'"'));
	$_nmMarketing = $nmUser['namalengkap'];
}else{	$_nmMarketing = $met_['input_by'];	}
					echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
							  <td align="center">'.(++$no + ($pageNow-1) * 10).'</td>
							  <td align="center">'.$met_['spak'].'</td>
							  <td align="center">'.$met_['id_cn'].'</td>
							  <td align="center">'.$met_['id_peserta'].'</td>
							  <td>'.$met_['nama'].'</td>
							  <td align="center">'.$_nmMarketing.'</td>
							  <td align="center">'._ConvertDate($met_['input_date']).'</td>
							  <td align="right">'.duit($met_['plafond']).'</td>
							  <td align="right">'.duit($met_['premi']).'</td>
							  <td>'.$met_['dokter_pemeriksa'].'</td>
							  <td align="center">'._ConvertDate($met_['tgl_periksa']).'</td>
							  <td align="center">'.$met_['cabang'].'</td>
							  </tr>';
				}
				echo '<tr><td colspan="22">';
				echo '<b>Total Data SPK: <u>' . $totalRows . '</u></b></td></tr>';
				echo '</table>';
			}
		}
		;
		break;
	case "hisSPKCab":
		echo '<link rel="stylesheet" href="../javascript/jscssmobile/css/lightbox.css" type="text/css" media="screen" />
	  <script src="../javascript/jscssmobile/js/prototype.js" type="text/javascript"></script>
	  <script src="../javascript/jscssmobile/js/scriptaculous.js?load=effects" type="text/javascript"></script>
	  <script src="../javascript/jscssmobile/js/lightbox.js" type="text/javascript"></script>';
		echo '<table border="0" cellpadding="5" cellspacing="0" width="100%"><tr><th width="95%" align="left">Modul Laporan Historical SPK</font></th></tr></table>';
		$metcost = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name DESC');
		echo '<form method="post" action="">
					  <input type="hidden" name="id_cost" value="'.$metcost['id'].'">
					  <table border="0" cellpadding="1" cellspacing="0" width="100%">
				      <tr><td width="40%" align="right">Nama Perusahaan <font color="red">*</font></td><td> : <select name="id_cost" id="id_cost">
					  	<option value="">---Pilih Perusahaan---</option>';
		while($metcost_ = mysql_fetch_array($metcost)) {
			echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['id_cost'], $metcost_['id']).'>'.$metcost_['name'].'</option>';
		}
		echo '</select></td></tr>
					  <tr><td align="right">Tanggal Input SPK <font color="red">*</font></td>
					 	  <td> :';print initCalendar();	print calendarBox('tglcheck1', 'triger1', $_REQUEST['tglcheck1']);	echo 's/d';
		print initCalendar();	print calendarBox('tglcheck2', 'triger2', $_REQUEST['tglcheck2']);
		echo '</td></tr>
					  <tr><td align="center"colspan="2"><input type="hidden" name="re" value="datapeserta"><input type="submit" name="ere" value="Cari"></td></tr>
					  </table>
					  </form>';
		if ($_REQUEST['re']=="datapeserta") {
			if ($_REQUEST['id_cost']=="") 	{	$error_1 = '<div align="center"><font color="red"><blink>Silahkan pilih nama perusahaan...!!<br /></div></font></blink>';	}
			if ($_REQUEST['tglcheck1']=="") 	{	$error_2 = '<div align="center"><font color="red"><blink>Tanggal input SPK tidak boleh kosong<br /></div></font></blink>';	}
			if ($_REQUEST['tglcheck2']=="") 	{	$error_3 = '<div align="center"><font color="red"><blink>Tanggal input SPK tidak boleh kosong</div></font></blink>';	}
			if ($error_1 OR $error_2 OR $error_3) {	echo $error_1 .''.$error_2.''.$error_3;	}
			else{
				echo '<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="#E2E2E2">
					<tr><td bgcolor="#FFF"colspan="23"><a href="e_report.php?er=eL_HistSPK&idCost='.$_REQUEST['id_cost'].'&idPolis='.$_REQUEST['id_polis'].'&tgl1='.$_REQUEST['tglcheck1'].'&tgl2='.$_REQUEST['tglcheck2'].'&st='.$_REQUEST['statusnya'].'"><img src="image/excel.png" width="25" border="0"><br />Excel</a></td></tr>
					<th width="1%">No</th>
					<th>ID CN</th>
					<th>ID Peserta</th>
					<th>Nama Pesert</th>
					<th>Nama Dokter Periksa</th>
					<th>Nama Marketing</th>
					<th>Tanggal SPK</th>
					<th>Tanggal Dokter Periksa</th>
					<th>Plafon</th>
					<th>Premi</th>
					<th>Cabang</th>
					</tr>';
				if ($_REQUEST['id_cost'])		{	$satu = 'AND fu_ajk_spak.id_cost = "'.$_REQUEST['id_cost'].'"';	}
				//if ($_REQUEST['tglcheck1'])		{	$duaa = 'AND fu_ajk_spak.input_date BETWEEN "'.$_REQUEST['tglcheck1'].'" AND "'.$_REQUEST['tglcheck2'].'" ';	}
				if ($_REQUEST['tglcheck1'])		{
					if ($_REQUEST['tglcheck1'] == $_REQUEST['tglcheck2']) {
						$PenambahanTgl = strtotime ( '+1 day' , strtotime ( $_REQUEST['tglcheck2'] ) ) ;;
						$newdate = date ( 'Y-m-d' , $PenambahanTgl );
						$duaa = 'AND fu_ajk_spak.input_date BETWEEN "'.$_REQUEST['tglcheck1'].'" AND "'.$newdate.'" ';
					}else{
						$PenambahanTgl = strtotime ( '+1 day' , strtotime ( $_REQUEST['tglcheck2'] ) ) ;;
						$newdate2 = date ( 'Y-m-d' , $PenambahanTgl );
						$duaa = 'AND fu_ajk_spak.input_date BETWEEN "'.$_REQUEST['tglcheck1'].'" AND "'.$newdate2.'" ';
					}
				}
				if ($_REQUEST['statusnya'])		{ $tiga = 'AND fu_ajk_spak.status = "'.$_REQUEST['statusnya'].'"';
					//if ($_REQUEST['statusnya']=="Realisasi") {
					//$tiga = 'AND fu_ajk_spak.status = "Aktif" AND fu_ajk_peserta.id_dn !=""';	}
					//else{
					//$tiga = 'AND fu_ajk_spak.status = "'.$_REQUEST['statusnya'].'"';
				}

				if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 10;	}	else {	$m = 0;		}
				$met = $database->doQuery('SELECT
fu_ajk_cn.id_cn,
fu_ajk_cn.id_peserta,
fu_ajk_peserta.nama,
fu_ajk_peserta.spaj,
fu_ajk_spak.spak,
fu_ajk_spak_form.dokter_pemeriksa,
fu_ajk_spak_form.tgl_periksa,
date_format(fu_ajk_spak.input_date,"%Y-%m-%d") as input_date,
fu_ajk_peserta.kredit_jumlah AS plafond,
fu_ajk_peserta.totalpremi AS premi,
fu_ajk_peserta.cabang
FROM
fu_ajk_cn
LEFT JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id = fu_ajk_peserta.id_klaim
LEFT JOIN fu_ajk_spak ON fu_ajk_peserta.id_cost = fu_ajk_spak.id_cost AND fu_ajk_peserta.id_polis = fu_ajk_spak.id_polis AND fu_ajk_peserta.spaj = fu_ajk_spak.spak
LEFT JOIN fu_ajk_spak_form ON fu_ajk_spak.id_cost = fu_ajk_spak_form.idcost AND fu_ajk_spak.id = fu_ajk_spak_form.idspk
WHERE
fu_ajk_cn.type_claim = "Death" AND
fu_ajk_cn.id_nopol = "1" AND
date_format(fu_ajk_spak.input_date,"%Y-%m-%d") BETWEEN "'.$_REQUEST['tglcheck1'].'" AND "'.$_REQUEST['tglcheck2'].'"');

				$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(*)
				FROM
				fu_ajk_cn
				LEFT JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id = fu_ajk_peserta.id_klaim
				LEFT JOIN fu_ajk_spak ON fu_ajk_peserta.id_cost = fu_ajk_spak.id_cost AND fu_ajk_peserta.id_polis = fu_ajk_spak.id_polis AND fu_ajk_peserta.spaj = fu_ajk_spak.spak
				LEFT JOIN fu_ajk_spak_form ON fu_ajk_spak.id_cost = fu_ajk_spak_form.idcost AND fu_ajk_spak.id = fu_ajk_spak_form.idspk
				WHERE
				fu_ajk_cn.type_claim = "Death" AND
				fu_ajk_cn.id_nopol = "1" AND
				date_format(fu_ajk_spak.input_date,"%Y-%m-%d") BETWEEN "'.$_REQUEST['tglcheck1'].'" AND "'.$_REQUEST['tglcheck2'].'"'));
				$totalRows = $totalRows[0];
				$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
				while ($met_ = mysql_fetch_array($met)) {
					if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
					if (is_numeric($met_['cabang'])) {
						$met_Cabang = mysql_fetch_array(mysql_query('SELECT id, name FROM fu_ajk_cabang WHERE id="'.$met_['cabang'].'"'));
						$inputcabang = $met_Cabang['name'];
					}else{
						$inputcabang = $met_['cabang'];
					}
					if ($met_['filefotodebitursatu']=="") { $photo1 ='<a href="../image/non-user.png" rel="lightbox"><img src="../image/non-user.png" width="50">';
					}else{ $photo1 ='<a href="../../ajkmobilescript/'.$met_['filefotodebitursatu'].'" rel="lightbox"><img src="../../ajkmobilescript/'.$met_['filefotodebitursatu'].'" width="50">'; }
					if ($met_['filettddebitur']=="") { $photo2 ='<a href="../image/non-user.png" rel="lightbox"><img src="../image/non-user.png" width="50">';
					}else{ $photo2 ='<a href="../../ajkmobilescript/'.$met_['filettddebitur'].'" rel="lightbox"><img src="../../ajkmobilescript/'.$met_['filettddebitur'].'" width="50">'; }
					if ($met_['filefotoskpensiun']=="") { $photo3 ='<a href="../image/non-user.png" rel="lightbox"><img src="../image/non-user.png" width="50">';
					}else{ $photo3 ='<a href="../../ajkmobilescript/'.$met_['filefotoskpensiun'].'" rel="lightbox"><img src="../../ajkmobilescript/'.$met_['filefotoskpensiun'].'" width="50">'; }
					//;
					echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
							  <td align="center">'.(++$no + ($pageNow-1) * 10).'</td>
							  <td>'.$met_['id_cn'].'</td>
							  <td>'.$met_['id_peserta'].'</td>
							  <td>'.$met_['nama'].'</td>
							  <td>'.$met_['dokter_pemeriksa'].'</td>
							  <td align="center">'.$met_['status'].'</td>
							  <td>'._ConvertDate($met_['input_date']).'</td>
							  <td align="center">'._ConvertDate($met_['tgl_periksa']).'</td>
							  <td align="right">'.$met_['plafond'].'</td>
							  <td align="right">'.$met_['premi'].'</td>
							  <td align="center">'.$met_['cabang'].'</td>
							  </tr>';
				}
				echo '<tr><td colspan="22">';
				echo '<b>Total Data SPK: <u>' . $totalRows . '</u></b></td></tr>';
				echo '</table>';
			}
		}
		;
		break;

	default:
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%"><tr><th width="95%" align="left">Modul Laporan Rekapitulasi SPK</font></th></tr></table>';
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
	  <tr><td align="right">Nama Mitra</td>
		<td id="polis_rate">: <select name="id_mitra" id="id_mitra">
		<option value="">-- Pilih Mitra --</option>
		</select></td></tr>
	 	<tr><td align="right">Tanggal Input SPK <font color="red">*</font></td>
	 	  <td> : <input type="text" id="from" name="tglcheck1" value="'.$_REQUEST['tglcheck1'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.from);return false;"/>
	  <a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.from);return false;">
	  <img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
	  <iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
	  s/d
	  <input type="text" id="from1" name="tglcheck2" value="'.$_REQUEST['tglcheck2'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.from1);return false;"/>
	  <a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.from1);return false;">
	  <img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
	  <iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
	  <tr><td align="right">Tanggal EM<font color="red">*</font></td>
	 	  <td> : <input type="text" id="tglem1" name="tglem1" value="'.$_REQUEST['tglem1'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.tglem1);return false;"/>
	  <a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.tglem1);return false;">
	  <img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
	  <iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
	  s/d
	  <input type="text" id="tglem2" name="tglem2" value="'.$_REQUEST['tglem2'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.tglem2);return false;"/>
	  <a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.tglem2);return false;">
	  <img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
	  <iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>';

//print initCalendar();	print calendarBox('tglcheck1', 'triger1', $_REQUEST['tglcheck1']);	echo 's/d';
//print initCalendar();	print calendarBox('tglcheck2', 'triger2', $_REQUEST['tglcheck2']);
echo '</td></tr>
	  <tr><td width="40%" align="right">Status</td><td> : <select name="statusnya">
		<option value="">---Pilih Status---</option>
		<option value="Pending"'.pilih($_REQUEST["statusnya"], "Pending").'>Pending</option>
		<option value="Proses"'.pilih($_REQUEST["statusnya"], "Proses").'>Proses</option>
		<option value="Approve"'.pilih($_REQUEST["statusnya"], "Approve").'>Approve</option>
		<option value="Aktif"'.pilih($_REQUEST["statusnya"], "Aktif").'>Aktif</option>
		<option value="Realisasi"'.pilih($_REQUEST["statusnya"], "Realisasi").'>Realisasi</option>
		<option value="Batal"'.pilih($_REQUEST["statusnya"], "Batal").'>Batal</option>
		<option value="Tolak"'.pilih($_REQUEST["statusnya"], "Tolak").'>Tolak</option>
	</select></td></tr>
	  <tr><td width="40%" align="right">Extra Premi</td><td> : <input type="radio" name="em" value="Y">Ya &nbsp;<input type="radio" name="em" value="T">Tidak</td></tr>
	  <tr><td align="center"colspan="2"><input type="hidden" name="re" value="datapeserta"><input type="submit" name="ere" value="Cari"></td></tr>
	  </table>
	  </form>';
if ($_REQUEST['re']=="datapeserta") {
if ($_REQUEST['id_cost']=="") 		{	$error_1 = '<div align="center"><font color="red"><blink>Silahkan pilih nama perusahaan...!!<br /></div></font></blink>';	}
// if ($_REQUEST['tglcheck1']=="") 	{	$error_2 = '<div align="center"><font color="red"><blink>Tanggal input SPK tidak boleh kosong<br /></div></font></blink>';	}
// if ($_REQUEST['tglcheck2']=="") 	{	$error_3 = '<div align="center"><font color="red"><blink>Tanggal input SPK tidak boleh kosong</div></font></blink>';	}
if ($error_1 OR $error_2 OR $error_3) {	echo $error_1 .''.$error_2.''.$error_3;	}
else{
echo '<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="#E2E2E2">
			<tr><td bgcolor="#FFF" colspan="26"><a href="e_report.php?er=eL_spk&cat='.$_REQUEST['id_cost'].'&subcat='.$_REQUEST['id_polis'].'&nmitra='.$_REQUEST['id_mitra'].'&tgl1='.$_REQUEST['tglcheck1'].'&tgl2='.$_REQUEST['tglcheck2'].'&st='.$_REQUEST['statusnya'].'&em='.$_REQUEST['em'].'&tglem1='.$_REQUEST['tglem1'].'&tglem2='.$_REQUEST['tglem2'].'"><img src="image/excel.png" width="25" border="0"><br />Excel</a></td>
			<td bgcolor="#FFF" align="center"><a href="../aajk_report.php?er=er_allSPK&cat='.$_REQUEST['id_cost'].'&subcat='.$_REQUEST['id_polis'].'&nmitra='.$_REQUEST['id_mitra'].'&tgl1='.$_REQUEST['tglcheck1'].'&tgl2='.$_REQUEST['tglcheck2'].'&st='.$_REQUEST['statusnya'].'&em='.$_REQUEST['em'].'"><img src="image/dninvoice.png" width="25" border="0"><br />Excel</a></td></tr>
			<th width="1%">No</th>
			<th>Produk</th>
			<th>Nama Debitur</th>
			<th>Cabang</th>
			<th>No. SPK</th>
			<th>Tgl Input SPK</th>
			<th>INPUT SPK</th>
			<th>Tgl Approve SPK</th>
			<th width="1%">Tanggal Assign</th>
			<th width="1%">User Assign</th>
			<th width="1%">Tanggal Em</th>
			<th width="1%">User EM</th>			
			<th>Tgl Lahir</th>
			<th>Tgl Asuransi</th>
			<th>Usia Awal</th>
			<th>Usia Akhir</th>
			<th>Plafond</th>
			<th>EM</th>
			<th>Tenor</th>
			<th>TB</th>
			<th>BB</th>
			<th>SISTOLIK</th>
			<th>DIASTOLIK</th>
			<th>NADI</th>
			<th>PERNAFASAN</th>
			<th>GULA DARAH</th>
			<th>MEROKOK</th>
			<th>JML ROKOK</th>
			<th>CATATAN SKS</th>
			<th>STATUS</th>			
			<th>Debitnote</th>
			</tr>';
if ($_REQUEST['id_cost'])		{	$satu = 'AND fu_ajk_spak.id_cost = "'.$_REQUEST['id_cost'].'"';	}
if ($_REQUEST['id_polis'])		{	$dua = 'AND fu_ajk_spak.id_polis = "'.$_REQUEST['id_polis'].'"';	}
if ($_REQUEST['id_mitra'])		{	$tiga = 'AND fu_ajk_spak.id_mitra = "'.$_REQUEST['id_mitra'].'"';	}
//if ($_REQUEST['tglcheck1'])		{	$duaa = 'AND fu_ajk_spak_form.input_date BETWEEN "'.$_REQUEST['tglcheck1'].'" AND "'.$_REQUEST['tglcheck2'].'" ';	}
if ($_REQUEST['tglcheck1'])		{
	if ($_REQUEST['tglcheck1'] == $_REQUEST['tglcheck2']) {
		$PenambahanTgl = strtotime ( '+1 day' , strtotime ( $_REQUEST['tglcheck2'] ) ) ;;
		$newdate = date ( 'Y-m-d' , $PenambahanTgl );
		//$empat = 'AND fu_ajk_spak.input_date BETWEEN "'.$_REQUEST['tglcheck1'].'" AND "'.$newdate.'" ';
		$empat = 'AND date(fu_ajk_spak.input_date) BETWEEN "'.$_REQUEST['tglcheck1'].'" AND "'.$_REQUEST['tglcheck2'].'" ';
	}else{
		$PenambahanTgl = strtotime ( '+1 day' , strtotime ( $_REQUEST['tglcheck2'] ) ) ;;
		$newdate2 = date ( 'Y-m-d' , $PenambahanTgl );
		//$empat = 'AND fu_ajk_spak.input_date BETWEEN "'.$_REQUEST['tglcheck1'].'" AND "'.$newdate2.'" ';
		$empat = 'AND date(fu_ajk_spak.input_date) BETWEEN "'.$_REQUEST['tglcheck1'].'" AND "'.$_REQUEST['tglcheck2'].'" ';
	}
}
if ($_REQUEST['tglem1']){
	$tujuh = 'AND date(fu_ajk_spak.approve_em_date) BETWEEN "'.$_REQUEST['tglem1'].'" AND "'.$_REQUEST['tglem2'].'" ';
}

/*
if ($_REQUEST['statusnya']) {
	if ($_REQUEST['statusnya']=="Realisasi") {
		$tiga = 'AND fu_ajk_spak.status = "Aktif" AND fu_ajk_peserta.id_dn !=""';
		$realisasi1 = ', fu_ajk_peserta.id_dn, fu_ajk_peserta.id_cost, fu_ajk_peserta.id_polis';
		$realisasi2 = 'LEFT JOIN fu_ajk_peserta ON fu_ajk_spak.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_spak.spak = fu_ajk_peserta.spaj AND fu_ajk_spak.id_polis = fu_ajk_peserta.id_polis';
		//$datastatus= 'Realisasi';
	}else{
		$tiga = 'AND fu_ajk_spak.status = "'.$_REQUEST['statusnya'].'"';
		//$datastatus= $met_['status'];
	}
}
*/
if ($_REQUEST['statusnya'])		{	$lima = 'AND fu_ajk_spak.status = "'.$_REQUEST['statusnya'].'"';	}

if ($_REQUEST['em'])	{	if ($_REQUEST['em']=="Y") {	$enam = 'AND fu_ajk_spak.ext_premi != ""';	}else{	$enam = 'AND fu_ajk_spak.ext_premi = ""';	}	}

if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}
/*
$met = $database->doQuery('SELECT
fu_ajk_spak.spak,
fu_ajk_spak.ext_premi,
fu_ajk_spak.keterangan,
fu_ajk_spak.`status`,
fu_ajk_spak.input_by,
fu_ajk_spak_form.nama,
fu_ajk_spak_form.cabang,
fu_ajk_spak_form.tgl_periksa,
fu_ajk_spak_form.tgl_asuransi,
DATE_FORMAT(fu_ajk_spak.input_date, "%Y-%m-%d") AS tglInput,
DATE_FORMAT(fu_ajk_spak.update_date, "%Y-%m-%d") AS tglApproveSPV,
fu_ajk_spak_form.dob,
fu_ajk_spak_form.x_usia,
(fu_ajk_spak_form.x_usia + fu_ajk_spak_form.tenor) AS usiaakhir,
fu_ajk_spak_form.plafond,
fu_ajk_spak_form.tenor,
fu_ajk_spak_form.tinggibadan,
fu_ajk_spak_form.beratbadan,
fu_ajk_spak_form.tekanandarah,
fu_ajk_spak_form.nadi,
fu_ajk_spak_form.pernafasan,
fu_ajk_spak_form.guladarah,
fu_ajk_spak_form.catatan,
fu_ajk_spak_form.pertanyaan6,
fu_ajk_spak_form.ket6,
fu_ajk_spak_form.kesimpulan,
fu_ajk_peserta.id_dn
FROM
fu_ajk_spak_form
INNER JOIN fu_ajk_spak ON fu_ajk_spak_form.idspk = fu_ajk_spak.id
LEFT JOIN fu_ajk_peserta ON fu_ajk_spak_form.idcost = fu_ajk_peserta.id_cost AND fu_ajk_spak.id_polis = fu_ajk_peserta.id_polis AND fu_ajk_spak.spak = fu_ajk_peserta.spaj
WHERE fu_ajk_spak_form.id !="" AND fu_ajk_spak_form.del is NULL '.$satu.' '.$duaa.' '.$tiga.' '.$empat.' ORDER BY fu_ajk_spak.input_date DESC LIMIT '.$m.', 25');

$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(fu_ajk_spak_form.id)
												   FROM fu_ajk_spak_form
												   INNER JOIN fu_ajk_spak ON fu_ajk_spak_form.idspk = fu_ajk_spak.id
												   LEFT JOIN fu_ajk_peserta ON fu_ajk_spak_form.idcost = fu_ajk_peserta.id_cost AND fu_ajk_spak.id_polis = fu_ajk_peserta.id_polis AND fu_ajk_spak.spak = fu_ajk_peserta.spaj
												   WHERE fu_ajk_spak_form.id !="" AND fu_ajk_spak_form.del is NULL '.$satu.' '.$duaa.' '.$tiga.' '.$empat.''));
*/

/*
$met = $database->doQuery('SELECT
fu_ajk_spak.spak,
fu_ajk_spak.ext_premi,
fu_ajk_spak.keterangan,
fu_ajk_spak.`status`,
fu_ajk_spak.input_by,
fu_ajk_spak_form.nama,
fu_ajk_spak_form.cabang,
DATE_FORMAT(fu_ajk_spak.input_date, "%Y-%m-%d") AS tglInput,
DATE_FORMAT(fu_ajk_spak.update_date, "%Y-%m-%d") AS tglApproveSPV,
fu_ajk_spak_form.tgl_periksa,
fu_ajk_spak_form.tgl_asuransi,
fu_ajk_spak_form.dob,
fu_ajk_spak_form.x_usia,
(fu_ajk_spak_form.x_usia + fu_ajk_spak_form.tenor) AS usiaakhir,
fu_ajk_spak_form.tenor,
fu_ajk_spak_form.plafond,
fu_ajk_spak_form.tinggibadan,
fu_ajk_spak_form.beratbadan,
fu_ajk_spak_form.tekanandarah,
fu_ajk_spak_form.nadi,
fu_ajk_spak_form.pernafasan,
fu_ajk_spak_form.guladarah,
fu_ajk_spak_form.catatan,
fu_ajk_spak_form.pertanyaan6,
fu_ajk_spak_form.ket6,
fu_ajk_spak_form.kesimpulan,
fu_ajk_polis.nmproduk,
fu_ajk_spak_form.nama,
fu_ajk_spak_form.dob
'.$realisasi1.'
FROM fu_ajk_spak
LEFT JOIN fu_ajk_spak_form ON fu_ajk_spak.id_cost = fu_ajk_spak_form.idcost AND fu_ajk_spak.id = fu_ajk_spak_form.idspk
LEFT JOIN fu_ajk_polis ON fu_ajk_spak.id_polis = fu_ajk_polis.id
'.$realisasi2.'
WHERE fu_ajk_spak.del IS NULL AND fu_ajk_spak_form.del IS NULL '.$satu.' '.$duaa.' '.$tiga.' '.$empat.'
ORDER BY fu_ajk_spak.input_date DESC LIMIT '.$m.', 25');

$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(fu_ajk_spak.id)
												   FROM fu_ajk_spak
												   LEFT JOIN fu_ajk_spak_form ON fu_ajk_spak.id_cost = fu_ajk_spak_form.idcost AND fu_ajk_spak.id = fu_ajk_spak_form.idspk
												   '.$realisasi2.'
												   WHERE fu_ajk_spak.del IS NULL AND fu_ajk_spak_form.del IS NULL '.$satu.' '.$duaa.' '.$tiga.' '.$empat.''));
*/

$met = $database->doQuery('SELECT fu_ajk_spak.id,
fu_ajk_spak.id_cost,
fu_ajk_spak.id_polis,
fu_ajk_spak.id_mitra,
fu_ajk_spak.spak,
fu_ajk_spak.ext_premi,
fu_ajk_spak.`status`,
fu_ajk_spak.input_by,
fu_ajk_spak.assign_by,
DATE_FORMAT(fu_ajk_spak.assign_date,"%Y-%m-%d") AS assign_date,
fu_ajk_spak.approve_em_by,
DATE_FORMAT(fu_ajk_spak.approve_em_date,"%Y-%m-%d") AS approve_em_date,
DATE_FORMAT(fu_ajk_spak.input_date, "%Y-%m-%d") AS tglInput,
DATE_FORMAT(fu_ajk_spak.update_date, "%Y-%m-%d") AS tglApproveSPV,
fu_ajk_costumer.`name`,
fu_ajk_polis.nmproduk,
fu_ajk_spak_form.nama,
fu_ajk_spak_form.dob,
fu_ajk_spak_form.cabang,
fu_ajk_spak_form.tgl_asuransi,
fu_ajk_spak_form.x_usia,
(fu_ajk_spak_form.x_usia + fu_ajk_spak_form.tenor) AS usiaakhir,
fu_ajk_spak_form.tenor,
fu_ajk_spak_form.plafond,
fu_ajk_spak_form.tinggibadan,
fu_ajk_spak_form.beratbadan,
fu_ajk_spak_form.tekanandarah,
fu_ajk_spak_form.nadi,
fu_ajk_spak_form.pernafasan,
fu_ajk_spak_form.guladarah,
fu_ajk_spak_form.pertanyaan6,
fu_ajk_spak_form.ket6,
fu_ajk_spak_form.catatan,
fu_ajk_spak_form.kesimpulan
FROM fu_ajk_spak
INNER JOIN fu_ajk_costumer ON fu_ajk_spak.id_cost = fu_ajk_costumer.id
INNER JOIN fu_ajk_polis ON fu_ajk_spak.id_polis = fu_ajk_polis.id
INNER JOIN fu_ajk_spak_form ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
WHERE fu_ajk_spak.del IS NULL AND fu_ajk_spak_form.del IS NULL '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' '.$tujuh.'
ORDER BY fu_ajk_spak.input_date ASC LIMIT '.$m.', 25');

$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(fu_ajk_spak.id)
FROM fu_ajk_spak
INNER JOIN fu_ajk_costumer ON fu_ajk_spak.id_cost = fu_ajk_costumer.id
INNER JOIN fu_ajk_polis ON fu_ajk_spak.id_polis = fu_ajk_polis.id
INNER JOIN fu_ajk_spak_form ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
WHERE fu_ajk_spak.del IS NULL AND fu_ajk_spak_form.del IS NULL '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' '.$tujuh.' '));
$totalRows = $totalRows[0];
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while ($met_ = mysql_fetch_array($met)) {
$tgl_terima_spak = explode(" ", $met_['input_date']);
$tolik = explode("/", $met_['tekanandarah']);

if ($met_['pertanyaan6']=="T") {	$pertanyaan6 = "Tidak";	}else{	$pertanyaan6 = "Iya";	}
if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';

if (is_numeric($met_['cabang'])) {
	$met_Cabang = mysql_fetch_array(mysql_query('SELECT id, name FROM fu_ajk_cabang WHERE id="'.$met_['cabang'].'"'));
	$inputcabang = $met_Cabang['name'];
}else{
	$inputcabang = $met_['cabang'];
}

if (is_numeric($met_['input_by'])) {
$nmUser = mysql_fetch_array($database->doQuery('SELECT id, namalengkap FROM user_mobile WHERE id="'.$met_['input_by'].'"'));
$_nmMarketing = $nmUser['namalengkap'];
}else{	$_nmMarketing = $met_['input_by'];	}

//$metDN = mysql_fetch_array($database->doQuery('SELECT id, dn_kode FROM fu_ajk_dn WHERE id="'.$met_['id_dn'].'"'));
$metDN = mysql_fetch_array($database->doQuery('SELECT fu_ajk_peserta.id,
fu_ajk_peserta.id_dn,
fu_ajk_peserta.spaj,
fu_ajk_dn.dn_kode,
fu_ajk_dn.tgl_createdn
FROM fu_ajk_peserta
INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id
WHERE fu_ajk_peserta.spaj="'.$met_['spak'].'"'));
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		  <td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
		  <td>'.$met_['nmproduk'].'</td>
		  <td>'.$met_['nama'].'</td>
		  <td>'.$inputcabang.'</td>
		  <td align="center">'.$met_['spak'].'</td>
		  <td align="center">'._convertDate($met_['tglInput']).'</td>
		  <td>'.$_nmMarketing.'</td>
		  <td align="center">'._convertDate($met_['tglApproveSPV']).'</td>
		  <td align="center">'._ConvertDate($met_['assign_date']).'</td>
		  <td align="center">'.$met_['assign_by'].'</td>
		  <td align="center">'._ConvertDate($met_['approve_em_date']).'</td>
		  <td align="center">'.$met_['approve_em_by'].'</td>
		  <td align="center">'._convertDate($met_['dob']).'</td>
		  <td align="center">'._convertDate($met_['tgl_asuransi']).'</td>
		  <td align="center">'.$met_['x_usia'].'</td>
		  <td align="center">'.$met_['usiaakhir'].'</td>
		  <td align="center">'.duit($met_['plafond']).'</td>
		  <td align="center">'.$met_['ext_premi'].'</td>
		  <td align="center">'.$met_['tenor'].'</td>
		  <td align="center">'.$met_['tinggibadan'].'</td>
		  <td align="center">'.$met_['beratbadan'].'</td>
		  <td align="center">'.$tolik[0].'</td>
		  <td align="center">'.$tolik[1].'</td>
		  <td align="center">'.$met_['nadi'].'</td>
		  <td align="center">'.$met_['pernafasan'].'</td>
		  <td align="center">'.$met_['guladarah'].'</td>
		  <td align="center">'.$pertanyaan6.'</td>
		  <td align="center">'.$met_['ket6'].'</td>
		  <td>'.$met_['catatan'].'</td>
		  <td>'.$met_['status'].'</td>		  
		  <td>'.$metDN['dn_kode'].'</td>
		  </tr>';
}
	echo '<tr><td colspan="27">';
	echo createPageNavigations($file = 'ajk_re_spk.php?re=datapeserta&id_cost='.$_REQUEST['id_cost'].'&id_polis='.$_REQUEST['id_polis'].'&tglcheck1='.$_REQUEST['tglcheck1'].'&tglcheck2='.$_REQUEST['tglcheck2'].'&statusnya='.$_REQUEST['statusnya'].'&em='.$_REQUEST['em'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
	echo '<b>Total Data SPK: <u>' . $totalRows . '</u></b></td></tr>';
	echo '</table>';
}
}
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
			"id_mitra":		{url:\'javascript/metcombo/data.php?req=setmitra\', value:\'id\', label:\'name\', init:\'<?php echo $_POST["id_mitra"] ?>\'},

		},
		loadingImage:\'../loader1.gif\',
		loadingText:\'Loading...\',
		debug:0
	} )
});
</script>';
?>