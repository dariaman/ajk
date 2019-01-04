<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("includes/functions.php");
echo "<script language=\"JavaScript\" src=\"javascript/js/form_validation.js\"></script>";
connect();
$futgl = date("Y-m-d H:i:s");
if (isset($_SESSION['nm_user'])) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));	}
if ($q['level']=="99") {	$typedata = 'AND type_data="SPK"';	}else{		}
switch ($_REQUEST['er']) {
	case "_spk":
echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="100%" align="left">Modul Data SPK</font></th></tr></table>';
echo '<table border="0" width="100%" cellpadding="1" cellspacing="1">
	  <form method="post" action="">
	  <tr><td align="right" width="10%">Nomor SPK :</td><td><input type="text" name="nospk" value="' . $_REQUEST['nospk'] . '"></td></tr>
	  <tr><td align="right" width="10%">Nama Debitur :</td><td><input type="text" name="namaspk" value="' . $_REQUEST['namaspk'] . '"> <input type="submit" name="button" value="Cari" class="button"></td></tr>
	  </form></table>';
echo '<form method="post" action="ajk_val_upl.php?v=_spkApprove" onload ="onbeforeunload">
	 <table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#bde0e6">
	 <tr><th width="1%">No</th>
	 	 <th>Perusahaan</th>
	 	 <th width="1%">SPK</th>
	 	 <th width="1%">No.Identitas</th>
	 	 <th>Nama</th>
	 	 <th>Tgl Akad</th>
	 	 <th>Tenor</th>
	 	 <th>Tgl Akhir</th>
	 	 <th width="15%">Keterangan</th>
	 	 <th width="1%">Premi (x)</th>
	 	 <th width="1%">Usia (x)</th>
	 	 <th width="1%">Ex.Premi(%)</th>
	 	 <th width="10%">Nama File</th>
	 	 <th width="5%">User Upload</th>
	 	 <th width="5%">Tgl Upload</th>
	 	 <th width="5%">User Approve</th>
	 	 <th width="5%">Tgl Approve</th>
	 	 <th width="5%">Status</th>
	 	 <th width="5%">Photo</th>
	 </tr>';
		if ($q['status'] == "STAFF") {	$aksesSPK = 'AND input_by="'.$q['nm_user'].'"';	}	else{ $aksesSPK = 'AND update_by="'.$q['nm_lengkap'].'"';	}
		if ($q['status'] == "STAFF") {	$aksesSPK = 'AND fu_ajk_spak.input_by="'.$q['nm_user'].'"';	}	else{ $aksesSPK_ = 'AND fu_ajk_spak.update_by="'.$q['nm_lengkap'].'"';	}

		if ($_REQUEST['nospk']) {	$satu = 'AND spak LIKE "%' . $_REQUEST['nospk'] . '%"';	}
		if ($_REQUEST['namaspk']) {
			$ceknama = mysql_fetch_array($database->doQuery('SELECT fu_ajk_spak_form.id, fu_ajk_spak_form.idspk, fu_ajk_spak_form.nama, fu_ajk_spak.spak
    														 FROM fu_ajk_spak_form
    														 left Join fu_ajk_spak ON fu_ajk_spak_form.idspk = fu_ajk_spak.id
															 WHERE fu_ajk_spak_form.nama="'.$_REQUEST['namaspk'].'" AND fu_ajk_spak.status="Approve" OR fu_ajk_spak.status="Proses" '.$aksesSPK_.''));
			$dua = 'AND id = "' . $ceknama['idspk'] . '"';
		}

if ($_REQUEST['x']) {	$m = ($_REQUEST['x'] - 1) * 25;	} else {	$m = 0;	}
if ($q['level'] == "99" AND $q['status'] == "STAFF") {
	$met = $database->doQuery('SELECT * FROM fu_ajk_spak WHERE status="Aktif" ' . $satu . ' ' . $dua . ' AND status!="Batal" AND status!="Tolak" '.$aksesSPK.' ORDER BY id DESC LIMIT ' . $m . ' , 25');
	$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_spak WHERE status!="Aktif" AND status!="Batal" AND status!="Tolak" '.$aksesSPK.' ' . $satu . ' ' . $dua . ''));
	$totalRows = $totalRows[0];
}elseif ($q['level'] == "99" AND $q['status'] == "" AND $q['supervisor'] == "0") {
	$met = $database->doQuery('SELECT * FROM fu_ajk_spak WHERE status="Aktif" ' . $satu . ' ' . $dua . ' AND status!="Batal" AND status!="Tolak" '.$aksesSPK.' ORDER BY id DESC LIMIT ' . $m . ' , 25');
	$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_spak WHERE status!="Aktif" AND status!="Batal" AND status!="Tolak" '.$aksesSPK.' ' . $satu . ' ' . $dua . ''));
	$totalRows = $totalRows[0];
}else {
	$met = $database->doQuery('SELECT * FROM fu_ajk_spak WHERE status="Aktif" ' . $satu . ' ' . $dua . ' AND status!="Batal" AND status!="Tolak" '.$aksesSPK.' ORDER BY id DESC LIMIT ' . $m . ' , 25');
	$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_spak WHERE status="Approve" AND status!="Batal" AND status!="Tolak" '.$aksesSPK.' ' . $satu . ' ' . $dua . ''));
	$totalRows = $totalRows[0];
}
		$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
		while ($met_ = mysql_fetch_array($met)) {
			$met_company = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="' . $met_['id_cost'] . '"'));
			$metdata = mysql_fetch_array($database->doQuery('SELECT id, id_cost, spaj, type_data, nama FROM fu_ajk_peserta WHERE type_data="SPK" AND spaj="' . $met_['spak'] . '" AND del IS NULL'));
			$met_formspk = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_form WHERE idspk="' . $met_['id'] . '" AND del IS NULL'));
			// CEK STATUS DATA SPK
			if ($met_['status'] == "Batal" OR $met_['status']=="Tolak") {	$statusspknya = '<font color="red">' . $met_['status'] . '</font>';	}
			else {	$statusspknya = '<font color="blue">' . $met_['status'] . '</font>';	}
			// CEK STATUS DATA SPK
			if ($metdata['spaj'] == $met_['spak']) {	$_datamet = strtoupper($metdata['nama']);	}
			else {	$_datamet = '<a href="aajk_report.php?er=_spk&ids='.$met_['id'].'" target="_blank">'.strtoupper($met_formspk['nama']).'</a>';	}

			if ($q['status'] == "STAFF" OR $q['status'] == "SUPERVISOR" OR $q['status'] == "" OR $q['status'] == "UNDERWRITING") {
				$cekformspak = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_form WHERE idspk="' . $met_['id'] . '" AND del IS NULL'));
				if ($cekformspak['idspk'] == $met_['id']) {
					//$setting_fspak = '<a href="ajk_val_upl.php?v=spkFormEdit&ids=' . $met_['id'] . '&idform=' . $met_formspk['id'] . '"><img src="image/edit3.png" width="20"></a>';
					if ($q['status'] == "SUPERVISOR") {
						//$setting_fspak = '<a href="ajk_val_upl.php?v=spkForm&r=vsett_spak&ids=' . $met_['id'] . '"><img src="image/new.png" width="20"></a>';
						if ($met_['status'] == "Batal") {
							$approve_spk = '';
						} else {
							if ($met_['status'] == "Approve") {	$metikonapprove = '<img src="image/ya2.png" width="15">';	}
							else {	$metikonapprove = '<img src="image/ya.png" width="15">';	}
							$approve_spk = '<a href="ajk_uploader_spak.php?r=vdelsett_spak&ids=' . $met_['id'] . '" onClick="if(confirm(\'Apakah anda yakin membatalkan atau revisi data SPK ini ?\')){return true;}{return false;}"><img src="image/delete.gif" width="15"></a> &nbsp;
							<a href="ajk_uploader_spak.php?r=approve_spak&ids=' . $met_['id'] . '" onClick="if(confirm(\'Apakah anda yakin data SPK telah sesuai ?\')){return true;}{return false;}">' . $metikonapprove . '</a>';
						}
						$setting_fspak = '<a href="ajk_val_upl.php?v=spkFormEdit&ids=' . $met_['id'] . '&idform=' . $met_formspk['id'] . '"><img src="image/edit3.png" width="20"></a>';
					}elseif ($q['status'] == "" AND $q['supervisor'] == "0") {
						$dataceklist = '<input type="checkbox" class="case" name="namaspk[]" value="' . $met_['id'] . '">';
						//$setting_fspak = '<a title="Preview Data SPK" href="ajk_val_upl.php?v=spkForm&r=vsett_spak&ids=' . $met_['id'] . '"><img src="image/new.png" width="20"></a> &nbsp;<a title="Tolak Data SPK" href="ajk_uploader_spak.php?r=tolak_spak&ids=' . $met_['id'] . '" onClick="if(confirm(\'Anda yakin untuk membatalkan data SPK ini ?\')){return true;}{return false;}"><img src="image/deleted.png" width="20"></a> &nbsp;';
					}else {	}
				} else {
					$setting_fspak = '<a href="ajk_val_upl.php?v=_spkForm&ids=' . $met_['id'] . '"><img src="image/new.png" width="20"></a> &nbsp;<a href="ajk_val_upl.php?v=spkFormdell&ids=' . $met_['id'] . '" onClick="if(confirm(\'Apakah anda yakin membatalkan data SPK ini ?\')){return true;}{return false;}"><img src="image/delete.gif" width="15"></a> &nbsp;';
				}
			} else {	}

$x_file = str_replace(' ', '%20', $met_['fname']);

if ($met_['photo_spk'] == "") {	$v_photo = '<img src="image/non-user.png" width="50">';}
else {	$v_photo = '<a href="' . $metpath . '' . $met_['photo_spk'] . '" rel="lightbox" ><img src="' . $metpath_file . '' . $met_['photo_spk'] . '" width="50"></a>';	}

if (($no % 2) == 1) $objlass = 'tbl-odd';	else $objlass = 'tbl-even';

if (is_numeric($met_['input_by'])) {
	$met_User = mysql_fetch_array($database->doQuery('SELECT id, nama, namalengkap FROM user_mobile WHERE id="'.$met_['input_by'].'"'));
	$inputby_met = $met_User['namalengkap'];
}else{
	$inputby_met = $met_['input_by'];
}

if (is_numeric($met_['update_by'])) {
	$met_UserSPV = mysql_fetch_array($database->doQuery('SELECT id, nama, namalengkap FROM user_mobile WHERE id="'.$met_['update_by'].'"'));
	$updateby_met = $met_UserSPV['namalengkap'];
}else{
	$updateby_met = $met_['update_by'];
}

echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	  <td align="center">' . (++$no + ($pageNow - 1) * 25) . '</td>
			<td>' . strtoupper($met_company['name']) . '</td>
			<td align="center">' . $met_['spak'] . '</td>
			<td align="center">' . $met_formspk['noidentitas'] . '</td>
			<td>' . $_datamet . '</td>
			<td align="center">' . _convertDate($met_formspk['tgl_asuransi']) . '</td>
			<td align="center">' . $met_formspk['tenor'] . '</td>
			<td align="center">' . _convertDate($met_formspk['tgl_akhir_asuransi']) . '</td>
			<td>' . nl2br($met_['ket_ext']) . '</td>
			<td align="right">' . duit($met_formspk['x_premi']) . '</td>
			<td align="center">' . $met_formspk['x_usia'] . '</td>
			<td align="center">' . $met_['ext_premi'] . '</td>
		    <td><a href=' . $metpath . '' . $x_file . ' target="_blank">' . $met_['fname'] . '</a></td>
		    <td align="center">' . $inputby_met . '</td>
		    <td align="center">' . $met_['input_date'] . '</td>
			<td align="center">' . $updateby_met . '</td>
		    <td align="center">' . $met_['update_date'] . '</td>
		    <td align="center">' . $statusspknya . '</td>
		    <td align="center">' . $v_photo . '</td>
		  </tr>';
		}
echo '<tr><td colspan="22">';
echo createPageNavigations($file = 'ajk_peserta.php?er=_spk', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
echo '<b>Total Data SPK: <u>' . $totalRows . '</u></b></td></tr>';
echo '</table>';
		;
		break;

case "pending":
echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="100%" align="left">Modul Data Peserta Pending / Cek Medical (SKKT)</font></th></tr></table>';
echo '<fieldset style="padding: 2">
	<legend align="center">S e a r c h</legend>
	<table border="0" width="100%" cellpadding="1" cellspacing="1">
<form method="post" action="">
<tr><td width="20%" align="right">Nama Perusahaan :</td>
    <td width="30%">';
$quer2=mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$q['id_cost'].'"'));
echo  $quer2['name'];
echo '</td></tr>';
/*
echo '<tr><td align="right">Nama Produk :</td>
		<td> ';
if ($q['id_polis']!="") {
	$quer1=mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$q['id_polis'].'"'));
	echo $quer1['nmproduk'].' ('.$quer1['nopol'].')';
	$met_reg = $database->doQuery('SELECT * FROM fu_ajk_regional WHERE id_cost="'.$q['id_cost'].'"');
	$kolomregional .= '<tr><td align="right">Regional :</td>
						  <td><select id="id_cost" name="cat" onchange="reload(this.form)">
							  <option value="">--- Pilih ---</option>';
	$met_cost=$database->doQuery('SELECT * FROM fu_ajk_regional WHERE id_cost="'.$q['id_cost'].'" ORDER BY name ASC');
	while($met_cost_ = mysql_fetch_array($met_cost)) {
		$kolomregional .= '<option value="'.$met_cost_['name'].'"'._selected($_REQUEST['cat'], $met_cost_['name']).'>'.$met_cost_['name'].'</option>';
	}
	$kolomregional .= '</select></td></tr>';
$namaproduknya = 'AND id_polis="'.$q['id_polis'].'"';
}else{
	$quer1=$database->doQuery('SELECT * FROM fu_ajk_polis WHERE id_cost="'.$q['id_cost'].'"');
echo '<select id="id_cost" name="cat">
		<option value="">--- Pilih ---</option>';
	while($quer1_ = mysql_fetch_array($quer1)) {
		echo  '<option value="'.$quer1_['id'].'"'._selected($_REQUEST['cat'], $quer1_['id']).'>'.$quer1_['nmproduk'].'</option>';
	}
	echo '</select>';
$kolomregional .= '<tr><td align="right">Regional :</td>
						  <td>'.$q['wilayah'].'</td></tr>';
$namaproduknya = '';
}


		echo '</td></tr>';
		echo $kolomregional;
$met_reg = $database->doQuery('SELECT * FROM fu_ajk_regional WHERE id_cost="'.$q['id_cost'].'"');
echo '<tr><td align="right">Regional :</td>
		  <td><select id="id_cost" name="cat" onchange="reloadpending(this.form)">
		  <option value="">--- Pilih ---</option>';
$met_cost=$database->doQuery('SELECT * FROM fu_ajk_regional WHERE id_cost="'.$q['id_cost'].'" ORDER BY name ASC');
while($met_cost_ = mysql_fetch_array($met_cost)) {
echo  '<option value="'.$met_cost_['name'].'"'._selected($_REQUEST['cat'], $met_cost_['name']).'>'.$met_cost_['name'].'</option>';
}
echo '</select></td></tr>
	  <tr><td align="right">Cabang :</td>
		  <td><select id="subcat" name="subcat">
		  <option value="">--- Pilih ---</option>';
$cek_regionalnya = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE name="'.$_REQUEST['cat'].'"'));
$rreg=$database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id_cost="'.$q['id_cost'].'" AND id_reg="'.$cek_regionalnya['id'].'" ORDER BY name ASC');
while($freg = mysql_fetch_array($rreg)) {
	echo  '<option value="'.$freg['name'].'"'._selected($_REQUEST['subcat'], $freg['name']).'>'.$freg['name'].'</option>';
}
echo '</select></td></tr>
*/
echo '<tr><td align="right">Nomor DN :</td><td><input type="text" name="metdn" value="'.$_REQUEST['metdn'].'"></td></tr>
	<tr><td align="right">Tanggal Mulai Kredit :</td>
		<td>';print initCalendar();	print calendarBox('tgl', 'triger1', $_REQUEST['tgl']);
echo 's/d ';print initCalendar();	print calendarBox('tgl2', 'triger2', $_REQUEST['tgl2']);
echo '</td></tr>
	<tr><td align="right">Nama :</td><td><input type="text" name="snama" value="'.$_REQUEST['snama'].'"></td></tr>
		</td></tr>
	<tr><td align="center" colspan="2"><input type="submit" name="button" value="Cari" class="button"></td></tr>
	</form></table></fieldset>';

if ($_REQUEST['tgl']!='' AND $_REQUEST['tgl2']!='')	{	$satu='AND kredit_tgl BETWEEN \''.$_REQUEST['tgl'].'\' AND \''.$_REQUEST['tgl2'].'\'';	}
if ($_REQUEST['cat'])								{	$dua = 'AND regional LIKE "%' . $_REQUEST['cat'] . '%"';		}
if ($_REQUEST['snama'])								{	$tiga = 'AND nama LIKE "%' . $_REQUEST['snama'] . '%"';		}
//if ($_REQUEST['sdob'])		{	$empat = 'AND tgl_lahir LIKE "%' . $_REQUEST['sdob'] . '%"';		}
if ($_REQUEST['metdn'])								{
$metcekdn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn WHERE dn_kode LIKE "%' . $_REQUEST['metdn'] . '%"'));
$lima = 'AND id_dn = "' . $metcekdn['id'] . '"';
}
if ($_REQUEST['subcat'])							{	$enam = 'AND cabang LIKE "%' . $_REQUEST['subcat'] . '%"';		}

echo '<form method="post" action="ajk_peserta.php?r=approve">
		<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="#E2E2E2">
			<tr><!--<th width="1%" rowspan="2">Approve</th>-->
				<th width="1%" rowspan="2">File Memo</th>
				<th width="1%" rowspan="2">No</th>
				<th width="1%" rowspan="2">SPK</th>
				<th width="1%" rowspan="2">ID Peserta</th>
				<th width="1%" rowspan="2">Produk</th>
				<th rowspan="2">Nama Debitur</th>
				<th rowspan="2" width="5%">Tgl Lahir</th>
				<th rowspan="2" width="1%">Usia</th>
				<th colspan="4">Status Kredit</th>
				<th width="1%" rowspan="2">Premi</th>
				<th rowspan="2">EM</th>
				<th width="1%" rowspan="2">Total Premi</th>
				<th rowspan="2" width="5%">Medical</th>
				<th rowspan="2" width="5%">Status</th>
				<th rowspan="2" width="10%">Cabang</th>
			</tr>
			<tr><th width="1%">Kredit Awal</th>
				<th width="1%">Tenor</th>
				<th width="1%">Kredit Akhir</th>
				<th width="1%">Plafond</th>
			</tr>';

/*
if ($q['wilayah']=="PUSAT") {}else{
	$cekCentral = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE name="'.$q['cabang'].'"'));
	$cekCentral_ = $database->doQuery('SELECT * FROM fu_ajk_cabang WHERE centralcbg="'.$cekCentral['id'].'"');
	while ($cekCentral__ = mysql_fetch_array($cekCentral_)) {
		$metCentralCabang .= ' OR cabang ="'.$cekCentral__['name'].'"';
	}
	//CEK DATA CABANG CENTRAL;
	if ($metCentralCabang=="") {
	$metCabangCentral = ' AND cabang ="'.$q['cabang'].'"';
	}else{
	$metCabangCentral = ' AND (cabang ="'.$q['cabang'].'" '.$metCentralCabang.')';
	}
}
*/

//CEK DATA CABANG CENTRAL;
$cekCentral = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE name="'.$q['cabang'].'"'));
$cekCentral_ = $database->doQuery('SELECT * FROM fu_ajk_cabang WHERE centralcbg="'.$cekCentral['id'].'"');
while ($cekCentral__ = mysql_fetch_array($cekCentral_)) {
	$metCentralCabang .= ' OR cabang ="'.$cekCentral__['name'].'"';
}
//CEK DATA CABANG CENTRAL;
if ($q['wilayah']=="PUSAT" AND $q['cabang']=="PUSAT") {

}elseif ($q['wilayah']!="PUSAT" AND $q['cabang']=="PUSAT") {
	$cekCentral = $database->doQuery('SELECT fu_ajk_regional.`name` AS regional, fu_ajk_cabang.`name` AS cabang
									  FROM fu_ajk_regional
									  INNER JOIN fu_ajk_cabang ON fu_ajk_regional.id = fu_ajk_cabang.id_reg
									  WHERE fu_ajk_regional.`name` = "'.$q['wilayah'].'" AND fu_ajk_cabang.del IS NULL');
	while ($cekCentral__ = mysql_fetch_array($cekCentral)) {
		$metCabangCentral .= 'OR (cabang ="'.$cekCentral__['cabang'].'")';
	}
	$metCabangCentral = 'AND (cabang ="'.$q['cabang'].'" '.$metCabangCentral.')';
}else{
	if ($metCentralCabang=="") {
		$metCabangCentral = 'AND cabang ="'.$q['cabang'].'"';
	}else{
		$metCabangCentral = 'AND (cabang ="'.$q['cabang'].'" '.$metCentralCabang.')';
	}
}

if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 50;	}	else {	$m = 0;		}
//$data = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE nama!= "" AND id_cost="'.$q['id_cost'].'" '.$namaproduknya.' AND status_aktif="Pending" AND status_peserta IS NULL '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' AND del IS NULL ORDER BY kredit_tgl DESC, cabang ASC LIMIT ' . $m . ' , 50');
$data = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE nama!= "" AND id_cost="'.$q['id_cost'].'" '.$metCabangCentral.' AND (status_aktif="Pending" OR status_aktif="Reject") AND status_peserta IS NULL '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' AND del IS NULL ORDER BY id DESC, cabang ASC LIMIT ' . $m . ' , 50');
//$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_peserta WHERE id != "" AND id_cost="'.$q['id_cost'].'" '.$namaproduknya.' AND status_aktif="Pending" AND status_peserta IS NULL ' . $satu . ' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' AND del IS NULL '));
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_peserta WHERE id != "" AND id_cost="'.$q['id_cost'].'" '.$metCabangCentral.' AND (status_aktif="Pending" OR status_aktif="Reject") AND status_peserta IS NULL ' . $satu . ' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' AND del IS NULL '));
$totalRows = $totalRows[0];
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while ($fudata = mysql_fetch_array($data)) {
$metpolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$fudata['id_polis'].'" AND del IS NULL'));
$metdn = mysql_fetch_array($database->doQuery('SELECT id, dn_kode FROM fu_ajk_dn WHERE id="'.$fudata['id_dn'].'"'));

if ($fudata['status_bayar']==0) {	$statusnya = '<font color="red">Unpaid</font>';	}else{	$statusnya = '<font color="blue">Paid</font>';	}
if ($fudata['status_aktif']=="aktif") {	$statusdatanya = '<font color="red">Inforce</font>';	}else{	$statusdatanya = '<font color="blue">'.$fudata['status_aktif'].'</font>';	}

if ($fudata['usia'] < $metpolis['age_memo']) {
	if ($fudata['memousia'] == NULL) {
		$memousia = '<a title="upload memo usia" href="ajk_peserta.php?er=uplMemo&idm='.$fudata['id'].'">Upload</a>';
	}else{
		$memousia = '<a title="view memo usia" href="'.$metpath.''.$fudata['memousia'].'" target="_blank">View</a>';
	}
}else{
	$memousia = '';
}


if ($q['id_cost']==$fudata['id_cost'] AND $q['status']=="" AND $fudata['memousia'] !="") {
	$approvememousia = '<a title="approve data memo usia" href="ajk_peserta.php?er=approvememousia&idp='.$fudata['id'].'">Approve</a>';
}else{
$approvememousia = '';
}

if ($fudata['type_data']=="SPK") {	$metTenor = $fudata['kredit_tenor'] * 12;	}else{	$metTenor = $fudata['kredit_tenor'];	}
if ($fudata['status_aktif']=="Reject") {
	$_status = '<a title="'.$fudata['ketreject'].'">'.$fudata['status_aktif'].'</a>';
}else{
	$_status = $fudata['status_aktif'];
}

if (($no % 2) == 1)	$objlass = 'tbl-odd'; else				$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		  <!--<td align="center">'.$approvememousia.'</td>-->
		  <td align="center">'.$memousia.'</td>
		  <td align="center">'.(++$no + ($pageNow-1) * 50).'</td>
		  <td align="center">'.$fudata['spaj'].'</td>
		  <td align="center">'.$fudata['id_peserta'].'</td>
		  <td align="center">'.$metpolis['nmproduk'].'</td>
		  <td>'.$fudata['nama'].'</td>
		  <td align="center">'._convertDate($fudata['tgl_lahir']).'</td>
		  <td align="center">'.$fudata['usia'].'</td>
		  <td align="center">'._convertDate($fudata['kredit_tgl']).'</td>
		  <td align="center">'.$metTenor.'</td>
		  <td align="center">'._convertDate($fudata['kredit_akhir']).'</td>
		  <td align="right">'.duit($fudata['kredit_jumlah']).'</td>
		  <td align="right">'.duit($fudata['premi']).'</td>
		  <td align="right">'.duit($fudata['ext_premi']).'</td>
		  <td align="right">'.duit($fudata['totalpremi']).'</td>
		  <td align="center">'.$fudata['status_medik'].'</td>
		  <td align="center">'.$_status.'</td>
		  <td>'.$fudata['cabang'].'</td>
		  </tr>';
}
echo '<tr><td colspan="22">';
echo createPageNavigations($file = 'ajk_peserta.php?er=pending&cat='.$_REQUEST['cat'].'&subcat='.$_REQUEST['subcat'].'&cregional='.$_REQUEST['cregional'].'&snama='.$_REQUEST['snama'].'&tgl='.$_REQUEST['tgl'].'&tgl2='.$_REQUEST['tgl2'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 50);
echo '<b>Total Data Peserta Pending : <u>' . $totalRows . '</u></b></td></tr>';
echo '</table>';
		;
		break;

case "approvememousia":
$metMU = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id="'.$_REQUEST['idp'].'"'));
if ($metMU['id_polis']==1) {	$typeproduk="SPK";	}else{	$typeproduk="SPAJ";	}

$metMUUpd = $database->doQuery('UPDATE fu_ajk_peserta SET status_aktif="Approve", status_medik="'.$typeproduk.'", update_by="'.$q['nm_user'].'",update_time="'.$futgl.'" WHERE id="'.$_REQUEST['idp'].'"');

	$mail = new PHPMailer;
	$mail->IsSMTP();
	$mail->Host = SMTP_HOST; //Hostname of the mail server
	$mail->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
	$mail->Username = SMTP_UNAME; //Username for SMTP authentication any valid email created in your domain
	$mail->Password = SMTP_PWORD; //Password for SMTP authentication
	$mail->AddReplyTo = SMTP_REPLYMAIL; //reply-to address
	$mail->debug = 1;
	$mail->SMTPSecure = "ssl";
	$mail->SetFrom ($q['email'], $q['nm_lengkap']); //From address of the mail
	$mail->Subject = "AJKOnline - Approve Memo Usia"; //Subject od your mail

	//EMAIL PENERIMA KANTOR U/W
	$mailsupervisorajk = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="" AND status="UNDERWRITING" AND del IS NULL');
	while ($_mailsupervisorajk = mysql_fetch_array($mailsupervisorajk)) {
		$mail->AddAddress($_mailsupervisorajk['email'], $_mailsupervisorajk['nm_lengkap']); //To address who will receive this email
	$emilUW = $_mailsupervisorajk['nm_lengkap'];
	}
	//EMAIL PENERIMA KANTOR U/W

	//EMAIL PENERIMA CLIENT
	$mailclient = mysql_fetch_array($database->doQuery('SELECT id_cost, status, id_polis, nm_lengkap, email, level FROM pengguna WHERE id_cost="'.$q['id_cost'].'" AND id_polis="'.$metMU['id_polis'].'" AND nm_user="'.$metMU['input_by'].'" AND level="STAFF" AND del IS NULL'));
	//$mailsupervisorclient = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="'.$vall[0].'" AND id_polis="'.$vall[1].'" AND wilayah="'.$q['wilayah'].'" AND email !=""');
	while ($mailclient_ = mysql_fetch_array($mailclient)) {
		$mail->AddAddress($mailclient_['email'], $mailclient_['nm_lengkap']); //To address who will receive this email
	//echo $mailclient_['nm_lengkap'].'<br />';
	}
	//EMAIL PENERIMA CLIENT

	$mailOffice = $database->doQuery('SELECT * FROM fu_ajk_mail WHERE type="Produksi" AND status="Aktif"');
	while ($mailOffice_ = mysql_fetch_array($mailOffice)) {
		$mail->AddAddress($mailOffice_['emailto'], $mailOffice_['emailnama']); //To address who will receive this email
	}

	$message .='<table width="100%"><tr><td>To '.$emilUW.', <br />
						Telah diApprove file MEMO USIA atas nama :<br />
						Nama Debitur '.$metMU['nama'].'<br />
						Nomor SPK '.$metMU['spaj'].'<br /><br />
	 					Mohon segera dicek untuk di buatkan data Debitnote.<br /><br />
	 					Terimakasih,<br />
	 					'.$q['nm_lengkap'].'</td></tr></table>';
	$mail->AddBCC("adn.info.notif@gmail.com");
	$mail->AddCC("rahmad@adonaits.co.id");
	$mail->MsgHTML($message); //Put your body of the message you can place html code here
	//echo $message;
	$send = $mail->Send(); //Send the mails
	echo '<div class="title2" align="center">Memo usia peserta telah berhasil di upload oleh <b>'.$q['nm_lengkap'].'</b> pada tanggal '.$futgldn.' !</div><meta http-equiv="refresh" content="5; url=ajk_peserta.php?er=pending">';

	;
	break;

case "uplMemo":
echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="100%" align="left">Modul Data Upload Memo Usia</font></th></tr></table>';
//$metMemo = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id="'.$_REQUEST['idm'].'"'));
$metMemo = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta_tempf WHERE id_temp="'.$_REQUEST['idm'].'"'));
if (!$_REQUEST['nomormemosk'])  $error1 .='<blink><font color=red>Silahkan masukan nomor Memo atau nomor S.K</font></blink><br>';
if ($_REQUEST['el']=="_photo") {
	if ($_FILES['photofile1']['name'] =="") 		{	$errno = "Silahkan upload data memo usia peserta!<br />";	}
	if ($_FILES['photofile1']['type'] !="image/jpeg" AND $_FILES['photofile1']['type'] !="image/JPG" AND $_FILES['photofile1']['type'] !="image/jpg" AND $_FILES['photofile1']['type'] !="application/pdf")	{	$errno ="File memo harus Format JPG atau PDF !<br />";	}
	if ($_FILES['photofile1']['size'] / 1024 > $met_spaksize)	{	$errno ="File tidak boleh lebih dari 2Mb !<br />";	}

	if(file_exists($metpath.'MU_'.$metMemo['id_peserta'].'_'.$_FILES['photofile1']['name'])){
		$errno = '<div align="center"><font color="red">Nama file memo sudah ada, file memo tidak bisa diupload !</div><meta http-equiv="refresh" content="5; url=ajk_peserta.php?er=uplMemo&idm='.$_REQUEST['idp'].'">';
	}

if ($errno OR $error1) {	echo '<tr><td colspan="4" align="center"><font color="red">'.$errno.''.$error1.'</font></td></tr>';	}
	else{
		move_uploaded_file($_FILES['photofile1']['tmp_name'], $metpath . 'MU_'.$metMemo['nama'].'_'.$_FILES["photofile1"]["name"]);
//		$metphoto = $database->doQuery('UPDATE fu_ajk_peserta SET memousia="MU_'.$metMemo['id_peserta'].'_'.$_FILES['photofile1']['name'].'", update_by="'.$q['nm_user'].'",update_time="'.$futgl.'" WHERE id="'.$_REQUEST['idp'].'"');
		$metphoto = $database->doQuery('UPDATE fu_ajk_peserta_tempf SET memousia="MU_'.$metMemo['nama'].'_'.$_FILES['photofile1']['name'].'", nomemosk="'.$_REQUEST['nomormemosk'].'" WHERE id_temp="'.$_REQUEST['idm'].'"');

		$mail = new PHPMailer;
		$mail->IsSMTP();
		$mail->Host = SMTP_HOST; //Hostname of the mail server
		$mail->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
		$mail->Username = SMTP_UNAME; //Username for SMTP authentication any valid email created in your domain
		$mail->Password = SMTP_PWORD; //Password for SMTP authentication
		$mail->AddReplyTo = SMTP_REPLYMAIL; //reply-to address
		$mail->debug = 1;
		$mail->SMTPSecure = "ssl";
		$mail->SetFrom ($q['email'], $q['nm_lengkap']); //From address of the mail
		$mail->Subject = "AJKOnline - Upload Memo Usia"; //Subject od your mail
		$_mailsupervisorajk = mysql_fetch_array($database->doQuery('SELECT id_cost, status, id_polis, nm_lengkap, email, level, cabang FROM pengguna WHERE id_cost="'.$q['id_cost'].'" AND status="SUPERVISOR" AND level=6 AND cabang="'.$q['cabang'].'" AND del IS NULL'));
		$mail->AddAddress($_mailsupervisorajk['email'], $_mailsupervisorajk['nm_lengkap']);

		$mailOffice = $database->doQuery('SELECT * FROM fu_ajk_mail WHERE type="Produksi" AND status="Aktif"');
		while ($mailOffice_ = mysql_fetch_array($mailOffice)) {
			$mail->AddAddress($mailOffice_['emailto'], $mailOffice_['emailnama']); //To address who will receive this email
		}

		$message .='<table width="100%"><tr><td>To '.$mailOffice_['emailnama'].', <br />
					Telah diupload file MEMO USIA atas nama :<br />
					Nama Debitur '.$metMemo['nama'].'<br />
					Nomor SPK '.$metMemo['spaj'].'<br /><br />
 					Silahkan approve data debitur pada modul data pending.<br /><br />
 					Terimakasih,<br />
 					'.$q['nm_lengkap'].'</td></tr></table>';
		//$mail->AddCC("penting_ga@hotmail.com, sysdev@kode.web.id, arief@arief.kurniawan.com, gunarso@adonai.co.id");
		$mail->AddBCC("adn.info.notif@gmail.com");
		$mail->AddCC("rahmad@adonaits.co.id");
		$mail->MsgHTML($message); //Put your body of the message you can place html code here
		$send = $mail->Send(); //Send the mails
		echo '<div class="title2" align="center">Memo usia peserta telah berhasil di upload oleh <b>'.$q['nm_lengkap'].'</b> pada tanggal '.$futgldn.' !</div><meta http-equiv="refresh" content="3; url=ajk_val_upl.php?v=fl_spk">';
	}
}
echo '<form name="f1" method="post" enctype="multipart/form-data" action="">
	  <input type="hidden" name="idp" value="'.$_REQUEST['idm'].'">
	  <table border="0" cellpadding="1" cellspacing="0" width="100%">
<!--  <tr><td width="50%" align="right">ID Peserta</td><td> : '.$metMemo['id_peserta'].'</td></tr>	 SEBELUMNYA DIARAHKAN DI TABLE PESERTA -->
      <tr><td width="50%" align="right">Nama Debitur</td><td> : '.$metMemo['nama'].'</td></tr>
      <tr><td align="right">Memo Usia</td><td> : '.$metMemo['usia'].' thn</td></tr>
      <tr><td align="right">No. S.K / No. Memo <font color="red"> *</font></td><td> : <input type="text" name="nomormemosk" value="' . $_REQUEST['nomormemosk'] . '"></td></tr>
	  <tr><td align="right">Upload Memo Usia<font color="red"> *<br /><font size="1">File harus PDF / JPG, Maks. 2MB</font></td><td valign="top">: <input name="photofile1" type="file"></td></tr>
	  <tr><td align="center"colspan="2"><input type="hidden" name="el" value="_photo"><input type="submit" name="upload_rate" value="Upload"></td></tr>
	  </table>
	  </form>';
	;
	break;


case "uplMedical":
echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="100%" align="left">Modul Data Upload File Medical</font></th></tr></table>';
//$metMemo = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id="'.$_REQUEST['idm'].'"')); SEBELUMNYA DIARAHKAN DI TABLE PESERTA
$metMemo = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta_tempf WHERE id_temp="'.$_REQUEST['idm'].'"'));
if ($_REQUEST['el']=="_photo") {
	if ($_FILES['photofile1']['name'] =="") 					{	$errno = "Silahkan upload data file medical peserta!<br />";	}
//	if ($_FILES['photofile1']['type'] !="application/pdf")		{	$errno ="File medical harus Format PDF !<br />";	}	30 08 2016
	if ($_FILES['photofile1']['size'] / 1024 > $met_spaksize)	{	$errno ="File tidak boleh lebih dari 2Mb !<br />";	}

	if(file_exists($metpath.'MDC_'.$metMemo['nama'].'_'.$_FILES['photofile1']['name'])){
		$errno = '<div align="center"><font color="red">Nama file medical sudah ada, file medical tidak bisa diupload !</div><meta http-equiv="refresh" content="5; url=ajk_peserta.php?er=uplMedical&idm='.$_REQUEST['idp'].'">';
	}
	if ($errno) {	echo '<tr><td colspan="4" align="center"><font color="red">'.$errno.'</font></td></tr>';	}
	else{
		move_uploaded_file($_FILES['photofile1']['tmp_name'], $metpath . 'MDC_'.$metMemo['nama'].'_'.$_FILES["photofile1"]["name"]);
//		$metphoto = $database->doQuery('UPDATE fu_ajk_peserta SET medicalfile="MDC_'.$metMemo['id_peserta'].'_'.$_FILES['photofile1']['name'].'", medicalfile_status="Pending", medicalfile_updateby="'.$q['id'].'",medicalfile_updatedate="'.$futgl.'" WHERE id="'.$_REQUEST['idp'].'"');	SEBELUMNYA DIARAHKAN DI TABLE PESERTA
		$metphoto = $database->doQuery('UPDATE fu_ajk_peserta_tempf SET medicalfile="MDC_'.$metMemo['nama'].'_'.$_FILES['photofile1']['name'].'" WHERE id_temp="'.$_REQUEST['idm'].'"');

		$mail = new PHPMailer;
		$mail->IsSMTP();
		$mail->Host = SMTP_HOST; //Hostname of the mail server
		$mail->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
		$mail->Username = SMTP_UNAME; //Username for SMTP authentication any valid email created in your domain
		$mail->Password = SMTP_PWORD; //Password for SMTP authentication
		$mail->AddReplyTo = SMTP_REPLYMAIL; //reply-to address
		$mail->debug = 1;
		$mail->SMTPSecure = "ssl";
		$mail->SetFrom ($q['email'], $q['nm_lengkap']); //From address of the mail
		$mail->Subject = "AJKOnline - Upload File Medical"; //Subject od your mail
		$_mailsupervisorajk = mysql_fetch_array($database->doQuery('SELECT id_cost, status, id_polis, nm_lengkap, email, level, cabang FROM pengguna WHERE id_cost="'.$q['id_cost'].'" AND status="SUPERVISOR" AND level=6 AND cabang="'.$q['cabang'].'" AND del IS NULL'));
		$mail->AddAddress($_mailsupervisorajk['email'], $_mailsupervisorajk['nm_lengkap']);

		$mailOffice = $database->doQuery('SELECT * FROM fu_ajk_mail WHERE type="Produksi" AND status="Aktif"');
		while ($mailOffice_ = mysql_fetch_array($mailOffice)) {
			$mail->AddAddress($mailOffice_['emailto'], $mailOffice_['emailnama']); //To address who will receive this email
			$namaemailmedical = $mailOffice_['emailnama'];
		}

		$message .='To '.$namaemailmedical.', <br />
					Telah diupload file Medical atas nama :<br />
					<table width="100%">
					<tr><td width="10%">Nama Debitur</td><td>'.$metMemo['nama'].'</td></tr>
					<tr><td width="10%">Tanggal Lahir</td><td>'._convertDate($metMemo['tgl_lahir']).'</td></tr>
					<tr><td width="10%">Usia</td><td>'.$metMemo['usia'].'</td></tr>
					<tr><td width="10%">Cabang</td><td>'.$metMemo['cabang'].'</td></tr>
					</table><br />
 					Terimakasih,<br />
 					'.$q['nm_lengkap'].'';

		$mail->AddBCC("adn.info.notif@gmail.com");
		$mail->AddCC("rahmad@adonaits.co.id");

		$mail->MsgHTML($message); //Put your body of the message you can place html code here
		$send = $mail->Send(); //Send the mails
//		echo '<div class="title2" align="center">File medical peserta telah berhasil di upload oleh <b>'.$q['nm_lengkap'].'</b> pada tanggal '.$futgldn.' !</div><meta http-equiv="refresh" content="5; url=ajk_peserta.php?er=pending">';	SEBELUMNYA DIARAHKAN DI TABLE PESERTA
		echo '<div class="title2" align="center">File medical peserta telah berhasil di upload oleh <b>'.$q['nm_lengkap'].'</b> pada tanggal '.$futgldn.' !</div><meta http-equiv="refresh" content="3; url=ajk_val_upl.php?v=fl_spk">';
	}
}
echo '<form name="f1" method="post" enctype="multipart/form-data" action="">
	  <input type="hidden" name="idm" value="'.$_REQUEST['idm'].'">
	  <table border="0" cellpadding="1" cellspacing="0" width="100%">
<!--  <tr><td width="50%" align="right">ID Peserta</td><td> : '.$metMemo['id_peserta'].'</td></tr>	 SEBELUMNYA DIARAHKAN DI TABLE PESERTA -->
      <tr><td width="50%" align="right">Nama Debutir</td><td> : '.$metMemo['nama'].'</td></tr>
      <tr><td width="50%" align="right">Underwriting</td><td> : '.$metMemo['status_medik'].'</td></tr>
	  <tr><td align="right">Upload File Medical<font color="red">*<br /><font size="1">Maksimal ukuran File 2MB<br />Extension File (.pdf)</font></td><td valign="top">: <input name="photofile1" type="file" accept="application/pdf"></td></tr>
	  <tr><td align="center"colspan="2"><input type="hidden" name="el" value="_photo"><input type="submit" name="upload_rate" value="Upload"></td></tr>
	  </table>
	  </form>';
;
break;

case "rejectdata":
	$cekCentral = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE name="'.$q['cabang'].'"'));
	$cekCentral_ = $database->doQuery('SELECT * FROM fu_ajk_cabang WHERE centralcbg="'.$cekCentral['id'].'"');
while ($cekCentral__ = mysql_fetch_array($cekCentral_)) {
	$metCentralCabang .= ' OR fu_ajk_cn_tempf.id_cabang ="'.$cekCentral__['name'].'"';
}
	//CEK DATA CABANG CENTRAL;
if ($metCentralCabang=="") {
	$metCabangCentral = 'fu_ajk_cn_tempf.id_cabang ="'.$q['cabang'].'"';
}else{
	$metCabangCentral = '(fu_ajk_cn_tempf.id_cabang ="'.$q['cabang'].'" '.$metCentralCabang.')';
}
//$metReject = $database->doQuery('SELECT * FROM fu_ajk_cn_tempf WHERE confirm_claim="Rejected" AND '.$metCabangCentral.' AND input_by="'.$q['nm_user'].'"');
if ($_REQUEST['ccl']=="klaim") {
	if ($metCentralCabang=="") {	$metCabangCentralCN = 'fu_ajk_cn.id_cabang ="'.$q['cabang'].'"';	}
	else{	$metCabangCentralCN = '(fu_ajk_cn.id_cabang ="'.$q['cabang'].'" '.$metCentralCabang.')';	}
$metReject = $database->doQuery('SELECT fu_ajk_dn.dn_kode,
fu_ajk_cn.id_peserta,
fu_ajk_peserta.nama,
fu_ajk_peserta.tgl_lahir,
fu_ajk_peserta.usia,
fu_ajk_peserta.kredit_jumlah,
fu_ajk_peserta.kredit_tgl,
fu_ajk_peserta.kredit_tenor,
fu_ajk_peserta.kredit_akhir,
fu_ajk_peserta.totalpremi,
fu_ajk_peserta.cabang,
fu_ajk_cn.keterangan,
fu_ajk_polis.nmproduk,
fu_ajk_cn.type_claim,
fu_ajk_cn.update_by,
fu_ajk_cn.update_time
FROM fu_ajk_cn
INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_dn = fu_ajk_peserta.id_dn AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
WHERE fu_ajk_cn.confirm_claim="Rejected" AND '.$metCabangCentralCN.' AND fu_ajk_cn.input_by="'.$q['nm_user'].'" AND fu_ajk_peserta.del IS NULL AND fu_ajk_cn.del IS NULL');
$metBackReject = '<tr><th width="95%" align="left">Modul Data Penolakanp oleh Supervisor</font></th>
					  <th width="5%" align="center"><a href="ajk_peserta.php?er=rejectdata"><img src="image/back.png" width=20></a></font></th>
				  </tr>';
}else{
$metReject = $database->doQuery('SELECT fu_ajk_dn.dn_kode,
fu_ajk_cn_tempf.id_peserta,
fu_ajk_peserta.nama,
fu_ajk_peserta.tgl_lahir,
fu_ajk_peserta.usia,
fu_ajk_peserta.kredit_jumlah,
fu_ajk_peserta.kredit_tgl,
fu_ajk_peserta.kredit_tenor,
fu_ajk_peserta.kredit_akhir,
fu_ajk_peserta.totalpremi,
fu_ajk_peserta.cabang,
fu_ajk_cn_tempf.keterangan,
fu_ajk_polis.nmproduk,
fu_ajk_cn_tempf.type_claim,
fu_ajk_cn_tempf.update_by,
fu_ajk_cn_tempf.update_time
FROM
fu_ajk_cn_tempf
INNER JOIN fu_ajk_dn ON fu_ajk_cn_tempf.id_dn = fu_ajk_dn.id
INNER JOIN fu_ajk_peserta ON fu_ajk_cn_tempf.id_dn = fu_ajk_peserta.id_dn AND fu_ajk_cn_tempf.id_peserta = fu_ajk_peserta.id_peserta
INNER JOIN fu_ajk_polis ON fu_ajk_cn_tempf.id_nopol = fu_ajk_polis.id
WHERE fu_ajk_cn_tempf.confirm_claim="Rejected" AND '.$metCabangCentral.' AND fu_ajk_cn_tempf.input_by="'.$q['nm_user'].'" AND fu_ajk_peserta.del IS NULL AND fu_ajk_cn_tempf.keterangan NOT LIKE "%del=1%"');
$metBackReject = '<tr><th width="80%" align="left">Modul Data Penolakan oleh Supervisor</font></th>
					  <!--<th width="20%" align="center"><a href="ajk_peserta.php?er=rejectdata&ccl=klaim">Penolakan Data Klaim</a></font></th>-->
				  </tr>';
}
echo '<table border="0" cellpadding="5" cellspacing="1" width="100%">'.$metBackReject.'</table>
<table border="0" width="100%" bgcolor="#CDECDE">
<tr><th width="1%">No</th>
	<th width="5%">Produk</th>
	<th width="5%">Type Klaim</th>
	<th width="10%">Nomor DN</th>
	<th width="5%">ID Peserta</th>
	<th width="10%">Nama Tertanggung</th>
	<th width="5%">Tanggal Lahir</th>
	<th width="1%">Usia</th>
	<th width="5%">Uang Asuransi</th>
	<th width="5%">Mulai Asuransi</th>
	<th width="1%">Tenor</th>
	<th width="5%">Akhir Asuransi</th>
	<th width="5%">T.Premi</th>
	<th width="8%">Cabang</th>
	<th width="8%">User Tolak</th>
	<th>Keterangan</th>
</tr>';
while ($metReject_ = mysql_fetch_array($metReject)) {
//$met_refund = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_peserta="' . $metReject_['id_peserta'] . '" AND id_dn="' . $metReject_['id_dn'] . '"'));
//$met_dn = mysql_fetch_array($database->doQuery('SELECT id, dn_kode FROM fu_ajk_dn WHERE id="' . $metReject_['id_dn'] . '"'));
if (($no % 2) == 1) $objlass = 'tbl-odd';	else $objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	<td width="1%" align="center">' . ++$no . '</td>
	<td align="center">' . $metReject_['nmproduk'] . '</td>
	<td align="center">' . $metReject_['type_claim'] . '</td>
	<td align="center">' . $metReject_['dn_kode'] . '</td>
    <td align="center">' . $metReject_['id_peserta'] . '</td>
    <td>' . $metReject_['nama'] . '</td>
    <td align="center">' . _convertDate($metReject_['tgl_lahir']) . '</td>
    <td align="center">' . $metReject_['usia'] . '</td>
    <td align="right">' . duit($metReject_['kredit_jumlah']) . '</td>
    <td align="center">' . _convertDate($metReject_['kredit_tgl']) . '</td>
    <td align="center">' . $metReject_['kredit_tenor'] . '</td>
    <td align="center">' . _convertDate($metReject_['kredit_akhir']) . '</td>
    <td align="right">' . duit($metReject_['totalpremi']) . '</td>
		<td align="center">' . $metReject_['cabang'] . '</td>
		<td align="center">' . $metReject_['update_by'] . '</td>
    <td>' . $metReject_['keterangan'] . '</td>';
}
echo '</table>';

	;
	break;


	default:
echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="100%" align="left">Modul Data Kepesertaan</font></th></tr></table>';
$userPerusahaan = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$q['id_cost'].'"'));
if ($q['level']=="6") {
	$userProduk = $database->doQuery('SELECT * FROM fu_ajk_polis WHERE id_cost="'.$q['id_cost'].'" AND del IS NULL');
	$_ProdukUser .= '<select id="id_cost" name="cat"> <option value="">--- Pilih ---</option>';
	while($userProduk_ = mysql_fetch_array($userProduk)) {
	$_ProdukUser .= '<option value="'.$userProduk_['id'].'"'._selected($_REQUEST['cat'], $userProduk_['id']).'>'.$userProduk_['nmproduk'].'</option>';
	}
	$_ProdukUser .= '</select>';
	$QueryProduk = 'AND id_polis !=""';
}else{
	$userProduk = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$q['id_polis'].'" AND del IS NULL'));
	$_ProdukUser = $userProduk['nmproduk'].' ('.$userProduk['nopol'].')';
	$QueryProduk = 'AND id_polis="'.$q['id_polis'].'"';
}

if ($q['cabang']=="PUSAT" OR $q['level']=="6") {
	$userCabang = $database->doQuery('SELECT DISTINCT * FROM fu_ajk_cabang WHERE id_cost="'.$q['id_cost'].'" AND del IS NULL GROUP BY name ORDER BY name ASC');
	$_userCabang .= '<select id="id_cost" name="subcat"> <option value="">--- Pilih ---</option>';
	while($userCabang_ = mysql_fetch_array($userCabang)) {
		$_userCabang .= '<option value="'.$userCabang_['name'].'"'._selected($_REQUEST['subcat'], $userCabang_['name']).'>'.$userCabang_['name'].'</option>';
	}
	$_userCabang .= '</select>';
	$QueryCabang = 'AND cabang !=""';
	//$QueryInput = 'AND input_by !="" AND input_by IS NULL';
}else{
$_userCabang = $q['cabang'];
$QueryCabang = 'AND cabang ="'.$q['cabang'].'"';
$QueryInput = 'AND input_by ="'.$q['nm_user'].'"';
}

if ($q['id_polis']=="") {
$met_polis = $database->doQuery('SELECT * FROM fu_ajk_polis WHERE id !="" AND del IS NULL ORDER BY nmproduk ASC');
}else{
$met_polis = $database->doQuery('SELECT * FROM fu_ajk_polis WHERE id !="" AND del IS NULL AND ('.$mametProdukUser.') ORDER BY nmproduk ASC');
}
$met_Mitra = $database->doQuery('SELECT * FROM fu_ajk_grupproduk WHERE id_cost ="'.$q['id_cost'].'" ORDER BY nmproduk ASC');
echo '<fieldset style="padding: 2">
	<legend align="center">S e a r c h</legend>
	<table border="0" width="100%" cellpadding="1" cellspacing="1">
	<form method="post" action="">
	<tr><td width="10%">Nama Perusahaan</td><td>: '.$userPerusahaan['name'].'</td></tr>
	<tr><td width="10%">Cabang</td><td><b>: '.$_userCabang.'</b></td></tr>
	<!--<tr><td width="10%">Produk</td><td>: '.$_ProdukUser.'</td></tr>-->
	<tr><td>Nama Mitra</td><td>: <select name="idmitra">
		<option value="">---Pilih Mitra---</option>';
		while($met_Mitra_ = mysql_fetch_array($met_Mitra)) {
			echo '<option value="'.$met_Mitra_['id'].'"'._selected($_REQUEST['idmitra'], $met_Mitra_['id']).'>'.$met_Mitra_['nmproduk'].'</option>';
		}
echo '</select></td></tr>
	<tr><td>Nama Produk</td><td>: <select name="idpolis">
		<option value="">---Pilih Produk---</option>';
while($met_polis_ = mysql_fetch_array($met_polis)) {
	echo '<option value="'.$met_polis_['id'].'"'._selected($_REQUEST['idpolis'], $met_polis_['id']).'>'.$met_polis_['nmproduk'].'</option>';
}
echo '</select></td></tr>
	<!--<tr><td width="10%">Nomor DN</td><td>: <input type="text" name="metdn" value="'.$_REQUEST['metdn'].'"></td></tr>-->
	<tr><td width="10%">Nama</td><td>: <input type="text" name="snama" value="'.$_REQUEST['snama'].'"></td></tr>
	<tr><td width="10%">Tanggal Akad</td><td>: ';print initCalendar();	print calendarBox('tgl', 'triger1', $_REQUEST['tgl']);
echo 's/d ';print initCalendar();	print calendarBox('tgl2', 'triger2', $_REQUEST['tgl2']);
echo '</td></tr>
	<tr><td colspan="2"><input type="submit" name="button" value="Cari" class="button"></td></tr>
	</form>
	</table></fieldset>';

if ($_REQUEST['tgl']!='' AND $_REQUEST['tgl2']!='')	{	$satu='AND kredit_tgl BETWEEN \''.$_REQUEST['tgl'].'\' AND \''.$_REQUEST['tgl2'].'\'';	}
if ($_REQUEST['idpolis'])							{	$dua = 'AND id_polis = "' . $_REQUEST['idpolis'] . '"';		}
if ($_REQUEST['snama'])								{	$tiga = 'AND nama LIKE "%' . $_REQUEST['snama'] . '%"';		}
//if ($_REQUEST['sdob'])		{	$empat = 'AND tgl_lahir LIKE "%' . $_REQUEST['sdob'] . '%"';		}
if ($_REQUEST['metdn'])								{
$metcekdn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn WHERE dn_kode LIKE "%' . $_REQUEST['metdn'] . '%"'));
	$lima = 'AND id_dn = "' . $metcekdn['id'] . '"';
}
if ($_REQUEST['subcat'])							{	$enam = 'AND cabang = "' . $_REQUEST['subcat'] . '"';		}
if ($_REQUEST['idmitra'])							{	$tujuh = 'AND nama_mitra = "' . $_REQUEST['idmitra'] . '"';		}
/*
echo $_REQUEST['tgl'].'<br />';
echo $_REQUEST['tgl2'].'<br />';
echo $_REQUEST['cat'].'<br />';
echo $_REQUEST['snama'].'<br />';
echo $_REQUEST['metdn'].'<br />';
echo $_REQUEST['subcat'].'<br />';
*/
echo '<form method="post" action="ajk_peserta.php?r=approve">
<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="#E2E2E2">
	<tr><th width="1%" rowspan="2">No</th>
		<th width="15%" rowspan="2">Mitra</th>
		<th width="1%" rowspan="2">No. SPK</th>
		<th width="5%" rowspan="2">ID Peserta</th>
		<th width="5%" rowspan="2">Nama Utama</th>
		<th width="25%" rowspan="2">Nama Produk</th>
		<th width="5%" rowspan="2">Debitnote</th>
		<th width="5%" rowspan="2">Tanggal Lunas</th>
		<th rowspan="2">Nama Debitur</th>
		<th rowspan="2" width="1%">D.O.B</th>
		<th rowspan="2" width="1%">Usia</th>
		<th colspan="4">Status Kredit</th>
		<th width="1%" rowspan="2">Premi</th>
		<th rowspan="2">Ext. Premi</th>
		<th width="1%" rowspan="2">Total Premi</th>
		<th rowspan="2" width="5%">Medical</th>
		<th colspan="3" width="1%">Status</th>
		<th rowspan="2" width="1%">Cabang</th>
		<th rowspan="2" width="1%">Tgl Input</th>
	</tr>
	<tr><th>Kredit Awal</th>
		<th>Tenor</th>
		<th>Kredit Akhir</th>
		<th>Plafond</th>
		<th>Pembayaran</th>
		<th>Status</th>
		<th>Data</th>
	</tr>';


if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 50;	}	else {	$m = 0;		}
/*
if ($q['id_polis']=="" AND $q['wilayah']!="PUSAT") {
$data = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE nama!= "" AND id_cost="'.$q['id_cost'].'" AND regional="'.$q['wilayah'].'" '.$satu.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' '.$typedata.' AND input_by="'.$q['nm_user'].'" ORDER BY input_time DESC LIMIT ' . $m . ' , 50');
}
elseif ($q['id_polis']=="" AND $q['wilayah']=="PUSAT") {
$data = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE nama!= "" AND id_cost="'.$q['id_cost'].'" '.$satu.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' '.$typedata.' AND input_by="'.$q['nm_user'].'" ORDER BY input_time DESC LIMIT ' . $m . ' , 50');
}
else{
$data = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE nama!= "" AND id_cost="'.$q['id_cost'].'" AND id_polis="'.$q['id_polis'].'" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' '.$typedata.' AND input_by="'.$q['nm_user'].'" ORDER BY input_time DESC LIMIT ' . $m . ' , 50');
}
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_peserta WHERE id != "" AND id_cost="'.$q['id_cost'].'" AND id_polis="'.$q['id_polis'].'" ' . $satu . ' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' '.$typedata.' AND input_by="'.$q['nm_user'].'"'));
$totalRows = $totalRows[0];
*/


//$data = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_cost="'.$q['id_cost'].'" '.$QueryCabang.' '.$QueryInput.' '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' '.$typedata.' AND del IS NULL ORDER BY input_time DESC LIMIT ' . $m . ' , 50');
$cekCentral = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE name="'.$q['cabang'].'" and del is null'));
$cekCentral_ = $database->doQuery('SELECT * FROM fu_ajk_cabang WHERE centralcbg="'.$cekCentral['id'].'"');
while ($cekCentral__ = mysql_fetch_array($cekCentral_)) {
	$metCentralCabang .= ' OR cabang ="'.$cekCentral__['name'].'"';
}
		//CEK DATA CABANG CENTRAL;

//$data = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_cost="'.$q['id_cost'].'" '.$QueryInput.' AND '.$metCabangCentral.' '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' '.$typedata.' AND del IS NULL ORDER BY input_time DESC LIMIT ' . $m . ' , 50');
if ($q['wilayah']=="PUSAT" AND $q['cabang']=="PUSAT") {
$data = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_cost="'.$q['id_cost'].'" AND status_aktif !="Pending" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' '.$tujuh.' '.$typedata.' AND del IS NULL ORDER BY input_time DESC LIMIT ' . $m . ' , 50');
}elseif ($q['wilayah']!="PUSAT" AND $q['cabang']=="PUSAT") {
	$cekCentral = $database->doQuery('SELECT fu_ajk_regional.`name` AS regional, fu_ajk_cabang.`name` AS cabang
									  FROM fu_ajk_regional
									  INNER JOIN fu_ajk_cabang ON fu_ajk_regional.id = fu_ajk_cabang.id_reg
									  WHERE fu_ajk_regional.`name` = "'.$q['wilayah'].'" AND fu_ajk_cabang.del IS NULL');
	while ($cekCentral__ = mysql_fetch_array($cekCentral)) {
		$metCabangCentral .= 'OR (cabang ="'.$cekCentral__['cabang'].'")';
	}
	$metCabangCentral = 'AND (cabang ="'.$q['cabang'].'" '.$metCabangCentral.')';
$data = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_cost="'.$q['id_cost'].'" AND status_aktif !="Pending" '.$metCabangCentral.' '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' '.$tujuh.' '.$typedata.' AND del IS NULL ORDER BY input_time DESC LIMIT ' . $m . ' , 50');
}else{
	if ($metCentralCabang=="") {
		$metCabangCentral = 'AND cabang ="'.$q['cabang'].'"';
	}else{
		$metCabangCentral = 'AND (cabang ="'.$q['cabang'].'" '.$metCentralCabang.')';
	}
$data = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_cost="'.$q['id_cost'].'" AND status_aktif !="Pending" '.$metCabangCentral.' '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' '.$tujuh.' '.$typedata.' AND del IS NULL ORDER BY input_time DESC LIMIT ' . $m . ' , 50');
}

$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;

//$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_peserta WHERE id_cost="'.$q['id_cost'].'" '.$QueryInput.' AND '.$metCabangCentral.' '.$QueryInput.' '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' '.$typedata.' AND del IS NULL'));
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_peserta WHERE id_cost="'.$q['id_cost'].'" AND status_aktif !="Pending" '.$metCabangCentral.' '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' '.$tujuh.' '.$typedata.' AND del IS NULL'));
$totalRows = $totalRows[0];
while ($fudata = mysql_fetch_array($data)) {
if ($fudata['type_data']=="SPK") {
	if($fudata['mppbln'] > 0){ //jika mpp
		$dana_talangan = mysql_fetch_array($database->doQuery("SELECT IFNULL(CASE WHEN (S.id_polis in (select id from fu_ajk_polis where mpptype = 'Y')) AND
																									F.idspk = (SELECT idspk FROM fu_ajk_spak_form WHERE nopermohonan = F.nopermohonan ORDER BY idspk DESC LIMIT 1)
																				THEN 'mpp' END,'')AS datampp
																FROM fu_ajk_spak_form AS F, fu_ajk_spak AS S,fu_ajk_polis AS P
																WHERE S.spak='".$fudata['spaj']."' AND F.idspk=S.id
																AND P.id = S.id_polis"));
		if($dana_talangan['datampp']=="mpp"){ //jika dana talangan
			$tenor_ = $fudata['kredit_tenor'];
		}else{
			$tenor_ = $fudata['kredit_tenor']*12;
		}
	}else{
		$tenor_ = $fudata['kredit_tenor'] * 12;
	}
}else{
	$tenor_ = $fudata['kredit_tenor'];
}
$metpolis = mysql_fetch_array($database->doQuery('SELECT id, nopol, nmproduk FROM fu_ajk_polis WHERE id="'.$fudata['id_polis'].'"'));
$metdn = mysql_fetch_array($database->doQuery('SELECT id, dn_kode FROM fu_ajk_dn WHERE id="'.$fudata['id_dn'].'"'));
$metgproduk = mysql_fetch_array($database->doQuery('SELECT id, nmproduk FROM fu_ajk_grupproduk WHERE id="'.$fudata['nama_mitra'].'"'));
if ($fudata['nama_mitra']=="") {
	$groupProduk = "BUKOPIN";
}else{
	$groupProduk = $metgproduk['nmproduk'];
}

if($fudata['cabangreal'] != "" and $fudata['cabang'] != "JAKARTA"){
	$nmrdn = '<font size="2">'.substr($metdn['dn_kode'], 3).'</font>';
	$cabang = '<a href="javascript:;" title="Di deklarasi Pusat"><font color="red">'.$fudata['cabang'].'</font></a>';
}else{
	$nmrdn = '<a href="aajk_report.php?er=_kwipeserta&idn=' . $metdn['id'] . '&s=' . $q['id'] . '" target="_blank">'.substr($metdn['dn_kode'], 3).'</a>'; //PERSINGKAT NOMOR DN	
	$cabang = $fudata['cabang'];
}

if ($fudata['status_bayar']==0) {	$statusnya = '<font color="red">Unpaid</font>';	}else{	$statusnya = '<font color="blue">Paid</font>';	}
if ($fudata['status_aktif']=="Inforce") {	
	$statusdatanya = '<font color="blue">Inforce</font>';	
}elseif($fudata['status_aktif']=="Pindah"){
	$statusdatanya = '<font color="blue">Pindah ke '.$fudata['transfer_to'].'</font>';	
}else{	
	$statusdatanya = '<font color="red">'.$fudata['status_aktif'].'</font>';	
}

$mettgldn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn WHERE dn_kode="'.$fudata['id_dn'].'"'));

if($fudata['id_polis']==19){
	$query1 = "SELECT id_peserta
						FROM fu_ajk_peserta 
						WHERE spaj = (SELECT spak 
													FROM fu_ajk_spak 
													WHERE id = (SELECT nolink
																			FROM fu_ajk_spak
																					 INNER JOIN fu_ajk_spak_form
																					 ON fu_ajk_spak_form.idspk = fu_ajk_spak.id
																			WHERE fu_ajk_spak.spak = '".$fudata['spaj']."'
																			LIMIT 1))";
	$query = "SELECT nama 
						FROM fu_ajk_spak_form 
						WHERE idspk = (SELECT nolink
												FROM fu_ajk_spak
														 INNER JOIN fu_ajk_spak_form
														 ON fu_ajk_spak_form.idspk = fu_ajk_spak.id
												WHERE fu_ajk_spak.spak = '".$fudata['spaj']."'
												LIMIT 1)";
	$qpasangan = mysql_fetch_array(mysql_query($query));
	$pasangan = $qpasangan['nama'];
}else{
	$pasangan = ' - ';
}

$mettglInput = explode(" ", $fudata['input_time']);
if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	  <td align="center">'.(++$no + ($pageNow-1) * 50).'</td>
	  <td>'.$groupProduk.'</td>
	  <td>'.$fudata['spaj'].'</td>
	  <td>'.$fudata['id_peserta'].'</td>
	  <td align="center">'.$pasangan.'</td>
	  <td align="center">'.$metpolis['nmproduk'].'</td>
	  <td>'.$nmrdn.'</td>
	  <td align="center">'._convertDate($metdn['tgl_dn_paid']).'</td>
	  <td>'.$fudata['nama'].'</td>
	  <td align="center">'._convertDate($fudata['tgl_lahir']).'</td>
	  <td align="center">'.$fudata['usia'].'</td>
	  <td align="center">'._convertDate($fudata['kredit_tgl']).'</td>
	  <td align="center">'.$tenor_.'</td>
	  <td align="center">'._convertDate($fudata['kredit_akhir']).'</td>
	  <td align="right">'.duit($fudata['kredit_jumlah']).'</td>
	  <td align="right">'.duit($fudata['premi']).'</td>
	  <td align="right">'.duit($fudata['ext_premi']).'</td>
	  <td align="right">'.duit($fudata['totalpremi']).'</td>
	  <td align="center">'.$fudata['status_medik'].'</td>
	  <td align="center">'.$statusnya.'</td>
	  <td align="center">'.$statusdatanya.'</td>
	  <td align="center">'.$fudata['status_peserta'].'</td>
	  <td align="center">'.$cabang.'</td>
	  <td align="center">'._convertDate($mettglInput[0]).'</td>
	  </tr>';
	}
echo '<tr><td colspan="22">';
echo createPageNavigations($file = 'ajk_peserta.php?cat='.$_REQUEST['cat'].'&subcat='.$_REQUEST['subcat'].'&metdn='.$_REQUEST['metdn'].'&snama='.$_REQUEST['snama'].'&tgl='.$_REQUEST['tgl'].'&tgl2='.$_REQUEST['tgl2'].'&idmitra='.$_REQUEST['idmitra'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 50);
echo '<b>Total Data Peserta: <u>' . $totalRows . '</u></b></td></tr>';
echo '</table>';
	;
} // switch
?>
<script type="text/javascript" language="javascript">
function checkfile(sender) {
	var validExts = new Array(".xlsx", ".xls", ".csv");
	var fileExt = sender.value;
	fileExt = fileExt.substring(fileExt.lastIndexOf('.'));
	if (validExts.indexOf(fileExt) < 0) {
	alert("Invalid file selected, valid files are of " +
	validExts.toString() + " types.");
	return false;
	}
	else return true;
}
</script>
<SCRIPT language=JavaScript>
function reload(form)
{
	var val=form.cat.options[form.cat.options.selectedIndex].value;
	self.location='ajk_peserta.php?cat=' + val;
}
</script>

<SCRIPT language=JavaScript>
function reloadpending(form)
{
	var val=form.cat.options[form.cat.options.selectedIndex].value;
	self.location='ajk_peserta.php?er=pending&cat=' + val;
}
</script>
