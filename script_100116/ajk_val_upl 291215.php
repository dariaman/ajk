<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("../includes/functions.php");
connect();

if (session_is_registered('nm_user')) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));	}
switch ($_REQUEST['v']) {
case "spkForm":
echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="100%" align="left">Modul Form Data SPK</font></th></tr></table>';
echo '<table border="0" width="100%" cellpadding="1" cellspacing="1">
	  <form method="post" action="">
	  <tr><td align="right" width="10%">Nomor SPK :</td><td><input type="text" name="nospk" value="' . $_REQUEST['nospk'] . '"></td></tr>
	  <tr><td align="right" width="10%">Nama Debitur :</td><td><input type="text" name="namaspk" value="' . $_REQUEST['namaspk'] . '"> <input type="submit" name="button" value="Cari" class="button"></td></tr>
	  </form></table>';
echo '<form method="post" action="ajk_val_upl.php?v=_spkApprove" onload ="onbeforeunload">
	 <table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#bde0e6">
	 <tr><th width="1%">No</th>
	 	 <th width="1%">Option <input type="checkbox" id="selectall"/></th>
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
	 	 <th width="5%">Option</th>
	 </tr>';
	if ($q['status'] == "STAFF") {	$aksesSPK = 'AND input_by="'.$q['nm_user'].'"';	}	else{ $aksesSPK = 'AND update_by="'.$q['nm_lengkap'].'"';	}
	if ($q['status'] == "STAFF") {	$aksesSPK_ = 'AND fu_ajk_spak.input_by="'.$q['nm_user'].'"';	}	else{ $aksesSPK_ = 'AND fu_ajk_spak.update_by="'.$q['nm_lengkap'].'"';	}

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
	$met = $database->doQuery('SELECT * FROM fu_ajk_spak WHERE status!="Aktif" ' . $satu . ' ' . $dua . ' AND status!="Batal" AND status!="Tolak" AND status!="Realisasi" '.$aksesSPK.' ORDER BY id DESC LIMIT ' . $m . ' , 25');
    $totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_spak WHERE status!="Aktif" AND status!="Batal" AND status!="Tolak" AND status!="Realisasi" '.$aksesSPK.' ' . $satu . ' ' . $dua . ''));
    $totalRows = $totalRows[0];
}elseif ($q['level'] == "99" AND $q['status'] == "" AND $q['supervisor'] == "0") {
    $met = $database->doQuery('SELECT * FROM fu_ajk_spak WHERE status!="Aktif" ' . $satu . ' ' . $dua . ' AND status!="Batal" AND status!="Tolak" AND status!="Realisasi" '.$aksesSPK.' ORDER BY id DESC LIMIT ' . $m . ' , 25');
    $totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_spak WHERE status!="Aktif" AND status!="Batal" AND status!="Tolak" AND status!="Realisasi" '.$aksesSPK.' ' . $satu . ' ' . $dua . ''));
    $totalRows = $totalRows[0];
}else {
    $met = $database->doQuery('SELECT * FROM fu_ajk_spak WHERE status="Approve" ' . $satu . ' ' . $dua . ' AND status!="Batal" AND status!="Tolak" AND status!="Realisasi" '.$aksesSPK.' ORDER BY id DESC LIMIT ' . $m . ' , 25');
    $totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_spak WHERE status="Approve" AND status!="Batal" AND status!="Tolak" AND status!="Realisasi" '.$aksesSPK.' ' . $satu . ' ' . $dua . ''));
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
            if ($metdata['spaj'] == $met_['spak']) {	$_datamet = $metdata['nama'];	}
			else {	$_datamet = $met_formspk['nama'];	}

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

            /*
if ($met_['status']!="Aktif") {
	$approve_spk__ = $approve_spk;
}else{
	$approve_spk__ = '';
}
*/

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
			<td align="center">' . $approve_spk . ' ' . $dataceklist . '</td>
			<td>' . strtoupper($met_company['name']) . '</td>
			<td align="center">' . $met_['spak'] . '</td>
			<td align="center">' . $met_formspk['noidentitas'] . '</td>
			<td>' . strtoupper($_datamet) . '</td>
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
		    <td align="center">' . $setting_fspak . '</td>
		  </tr>';
        }
        if ($q['status'] == "" AND $q['supervisor'] == "0" AND $q['level'] == "99" OR $q['level'] == "1" AND $q['supervisor'] == "0") {
            $el = $database->doQuery('SELECT * FROM fu_ajk_spak WHERE status="Approve"');
            $met = mysql_num_rows($el);
            if ($met > 0) {
                echo '<tr><td colspan="27" align="center"><a href="ajk_val_upl.php?v=_spkApprove" onClick="if(confirm(\'Apakah anda sudah yakin dengan semua data SPK ini ?\')){return true;}{return false;}"><input type="submit" name="ApproveClaim" Value="Approve"></a></td></tr>';
            } else {
                echo '';
            }
        } else {
            // echo '<tr><td colspan="27" align="center">'.$q['status'].'</td></tr>';
        }
        echo '<tr><td colspan="22">';
        echo createPageNavigations($file = 'ajk_val_upl.php?v=spkForm', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
        echo '<b>Total Data SPK: <u>' . $totalRows . '</u></b></td></tr>';
        echo '</table>';
	;
	break;

case "_spkForm":
echo '<link rel="stylesheet" href="templates/{template_name}/css/bootstrap.css" />
	<link rel="stylesheet" href="templates/{template_name}/css/bootstrap-responsive.css" />';
echo '<table border="0" cellpadding="0" cellspacing="0" width="100%">
      <tr><th width="95%" align="left">Modul Data spk</font></th><th><a href="ajk_val_upl.php?v=spkForm"><img src="image/Backward-64.png" width="20"></a></th></tr>
      </table>';
	$spkdokter = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak WHERE id=' . $_REQUEST['ids'] . ''));
	if ($_REQUEST['ope'] == "Simpan") {
		if ($_REQUEST['spk_nama'] == "") {	$error_1 = '<font color="red"><blink>Silahkan input nama debitur.</font>';	}
		if ($_REQUEST['spk_dob'] == "") { $error_2 = '<font color="red"><blink>Silahkan isi tanggal lahir debitur.<br /></font>';	}
		if ($_REQUEST['spk_alamat'] == "") {	$error_3 = '<font color="red"><blink>Silahkan isi alamat debitur.<br /></font>';	}
		if ($_REQUEST['qk_1'] == "") {	$error_4 = '<font color="red"><blink>Silahkan pilih pertanyaan "ya" atau "tidak".<br /></font>';	}
		if ($_REQUEST['qk_2'] == "") {	$error_5 = '<font color="red"><blink>Silahkan pilih pertanyaan "ya" atau "tidak".<br /></font>';	}
		if ($_REQUEST['qk_3'] == "") {	$error_6 = '<font color="red"><blink>Silahkan pilih pertanyaan "ya" atau "tidak".<br /></font>';	}
		if ($_REQUEST['qk_4'] == "") {	$error_7 = '<font color="red"><blink>Silahkan pilih pertanyaan "ya" atau "tidak".<br /></font>';	}
		if ($_REQUEST['qk_5'] == "") {	$error_8 = '<font color="red"><blink>Silahkan pilih pertanyaan "ya" atau "tidak".<br /></font>';	}
		if ($_REQUEST['qk_6'] == "") {	$error_9 = '<font color="red"><blink>Silahkan pilih pertanyaan "ya" atau "tidak".<br /></font>';	}
		if ($_REQUEST['spk_tglcheck'] == "") {	$error_10 = '<font color="red"><blink>Silahkan isi tanggal pemeriksaan.<br /></font>';	}
		if ($_REQUEST['spk_plafond'] == "") {	$error_12 = '<font color="red"><blink>Silahkan isi jumlah pinjaman.<br /></font>';	}
		if ($_REQUEST['spk_tglakad'] == "") {	$error_13 = '<font color="red"><blink>Silahkan isi tanggal awal asuransi.<br /></font>';	}
		if ($_REQUEST['spk_nmcabbank'] == "") {	$error_14 = '<font color="red"><blink>Silahkan isi cabang debitur.<br /></font>';	}

		$admpolis = mysql_fetch_array($database->doQuery('SELECT id_cost, adminfee, day_kredit, discount, singlerate, min_premium, age_max FROM fu_ajk_polis WHERE id_cost="' . $spkdokter['id_cost'] . '" AND id="' . $spkdokter['id_polis'] . '"'));
		// $umur = ceil(((strtotime($_REQUEST['spk_tglakad']) - strtotime($_REQUEST['spk_dob'])) / (60*60*24*365.2425)));	// FORMULA USIA
		$met_Date = datediff($_REQUEST['spk_tglakad'], $_REQUEST['spk_dob']);
		// $mets = datediff($_REQUEST['spk_tglakad'], $_REQUEST['spk_dob']);	// 16 februari 2015
		// if ($mets['months'] >= 5 ) {	$umur = $mets['years'] + 1;	}else{	$umur = $mets['years'];	}	// 16 februari 2015
		$met_Date_ = explode(",", $met_Date);
		// echo $met_Date_[0].'<br />';
		// echo $met_Date_[1].'<br />';
		// echo $met_Date_[2].'<br />';
		// echo $_REQUEST['spk_dob'].'<br />';
		// echo $_REQUEST['spk_tglakad'].' <br />';
		if ($met_Date_[1] >= 6) {	$umur = $met_Date_[0] + 1;	} else {	$umur = $met_Date_[0];	}
		// FORMULA USIA

		//CEK PLAFOND UMUR YG SEKARANG TGL AKAD
		$cekplafond = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_medical WHERE id_cost="' . $spkdokter['id_cost'] . '" AND id_polis="' . $spkdokter['id_polis'] . '"'));
		if ($umur > $cekplafond['age_from'] AND str_replace(".", "", $_REQUEST['spk_plafond']) > $cekplafond['si_to']) {
			$error_15 = '<font color="red"><blink>Nilai tenor melewati batas maksimum table underwriting.<br /></font>';
		}
		//CEK PLAFOND UMUT YG SEKARANG TGL AKAD

		if ($_REQUEST['spk_jwaktu'] == "") {	$error_11 = '<font color="red"><blink>Jangka Waktu tidak boleh kosong.<br /></font>';	}
		else {
			//$mettenornya = $_REQUEST['spk_jwaktu'] / 12;
			$cekrate_tenor = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="' . $spkdokter['id_cost'] . '" AND id_polis="' . $spkdokter['id_polis'] . '" AND usia="' . $umur . '" AND tenor="' .$_REQUEST['spk_jwaktu']. '"')); // RATE PREMI
			if ($cekrate_tenor['tenor'] != $_REQUEST['spk_jwaktu']) {
				$error_11 = '<font color="red"><blink>Jumlah jangka waktu tidak sesuai rate produk.<br /></font>';
			}
			$met_tgl_akhir = date('Y-m-d', strtotime('+' . $_REQUEST['spk_jwaktu'] . ' year', strtotime($_REQUEST['spk_tglakad']))); //tanggal akhir asuransi
			$met_Date_Akhir = datediff($met_tgl_akhir, $_REQUEST['spk_dob']);
			$metUsiaAkhirKredit = explode(",", $met_Date_Akhir);
			//if ($metUsiaAkhirKredit[0] > $admpolis['age_max'] + 1 AND $metUsiaAkhirKredit[1] <= 5) {
			if (($umur + $_REQUEST['spk_jwaktu']) > $admpolis['age_max'] + 1) {
				$error_11 = '<font color="red"><blink>Usia '.$metUsiaAkhirKredit[0].'thn melebihi batas masksimum usia, data ditolak.!!!</font>';
			}
		}

		//CEK PLAFOND UMUR PADA TABLE MEDICAL
		$plafondnya__ = str_replace(".", "", $_REQUEST['spk_plafond']);


		//CEK PLAFOND UMUR PADA TABLE MEDICAL
		if ($_REQUEST['spk_dob']!="") {
			$cekplafondakhir = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_medical WHERE id_cost="' . $spkdokter['id_cost'] . '" AND
        																								id_polis="' . $spkdokter['id_polis'] . '" AND
        																								'.$umur.' BETWEEN age_from AND age_to AND
        																								'.$plafondnya__.' BETWEEN si_from AND si_to'));
		}
		//CEK PLAFOND UMUR PADA TABLE MEDICAL
		if (!$cekplafondakhir) {	$error_16 = '<font color="red"><blink>Nilai Plafond melewati batas maksimum table underwriting.<br /></font>';	}
		//CEK PLAFOND UMUR PADA TABLE MEDICAL

		if ($error_1 OR $error_2 OR $error_3 OR $error_4 OR $error_5 OR $error_6 OR $error_7 OR $error_8 OR $error_9 OR $error_10 OR $error_11 OR $error_12 OR $error_13 OR $error_14 OR $error_15 OR $error_16) {
		}else {
			// MENENTUKAN PERHITUNGAN PREMI BERDASARKAN SINGLE RATE PADA POLIS
			if ($admpolis['singlerate'] == "T") {
				$cekrate = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="' . $spkdokter['id_cost'] . '" AND id_polis="' . $spkdokter['id_polis'] . '" AND tenor="' . $rr['kredit_tenor'] . '"')); // RATE PREMI
			} else {
				$cekrate = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="' . $spkdokter['id_cost'] . '" AND id_polis="' . $spkdokter['id_polis'] . '" AND usia="' . $umur . '" AND tenor="' . $_REQUEST['spk_jwaktu'] . '"')); // RATE PREMI
			}

			// MENENTUKAN PERHITUNGAN PREMI BERDASARKAN SINGLE RATE PADA POLIS
			$plafondnya = str_replace(".", "", $_REQUEST['spk_plafond']);
			$premi = $plafondnya * $cekrate['rate'] / 1000;

			$metExtPremi = $premi * $spkdokter['ext_premi'] / 100;	//HITUNG EXTRA PREMI
			$mettotal_ = $premi + $metExtPremi;
			if ($mettotal_ < $admpolis['min_premium']) {
				$premi_x = $admpolis['min_premium'];
			} else {
				$premi_x = $mettotal_;
			}
			$met_tgl_akhir = date('Y-m-d', strtotime('+' . $_REQUEST['spk_jwaktu'] . ' year', strtotime($_REQUEST['spk_tglakad']))); //tanggal akhir asuransi
			//echo 'dob '.$_REQUEST['spk_dob'].'<br />';
			//echo 'awal kredit '.$_REQUEST['spk_tglakad'].'<br />';
			//echo 'thn '.$met_Date.'<br />';
			//echo 'akhir kredit '.$met_tgl_akhir.'<br />';
			$met_Date_Akhir = datediff($met_tgl_akhir, $_REQUEST['spk_dob']);
			//echo 'akhir usia kreditnya '.$met_Date_Akhir.'<br />';
			$metUsiaAkhirKredit = explode(",", $met_Date_Akhir);
			if ($metUsiaAkhirKredit[0] >= $admpolis['age_max'] + 1 AND $metUsiaAkhirKredit[1] <= 5) {
				$met_tgl_akhir_Explode = date('Y-m-d',strtotime('- '.$metUsiaAkhirKredit[1].' month',strtotime($met_tgl_akhir)));
				$met_tgl_akhir_ = explode("-", $met_tgl_akhir_Explode);
				$met_tgl_dob_ = explode("-", $_REQUEST['spk_dob']);
				//echo 'thn '.$met_tgl_akhir_[0].'<br />';
				//echo 'bln '.$met_tgl_akhir_[1].'<br />';
				//echo 'hri '.$met_tgl_akhir_[2].'<br />';
				$met_tgl_akhir = $met_tgl_akhir_[0].'-'.$met_tgl_akhir_[1].'-'.$met_tgl_dob_[2];
			}else{
				$met_tgl_akhir = $met_tgl_akhir;
			}
			//$tgl_pinjam=$met_tgl_akhir;
			//$tgl_kembali_bulanan=date('Y-m-d',strtotime('-3 month',strtotime($tgl_pinjam)));	FORMAT KURANG TANGGAL PADA BULAN

$metrefundcn = $database->doQuery('INSERT INTO fu_ajk_spak_form SET idcost="' . $spkdokter['id_cost'] . '",
												   					dokter="' . $spkdokter['input_by'] . '",
												   					idspk="' . $spkdokter['id'] . '",
												   					nama="' . $_REQUEST['spk_nama'] . '",
												   					jns_kelamin="' . $_REQUEST['spk_sex'] . '",
												   					dob="' . $_REQUEST['spk_dob'] . '",
												   					alamat="' . $_REQUEST['spk_alamat'] . '",
												   					pekerjaan="' . $_REQUEST['spk_pekerjaan'] . '",
												   					pertanyaan1="' . $_REQUEST['qk_1'] . '",
												   					ket1="' . $_REQUEST['spk_ket_qk1'] . '",
												   					pertanyaan2="' . $_REQUEST['qk_2'] . '",
												   					ket2="' . $_REQUEST['spk_ket_qk2'] . '",
												   					pertanyaan3="' . $_REQUEST['qk_3'] . '",
												   					ket3="' . $_REQUEST['spk_ket_qk3'] . '",
												   					pertanyaan4="' . $_REQUEST['qk_4'] . '",
												   					ket4="' . $_REQUEST['spk_ket_qk4'] . '",
												   					pertanyaan5="' . $_REQUEST['qk_5'] . '",
												   					ket5="' . $_REQUEST['spk_ket_qk5'] . '",
												   					pertanyaan6="' . $_REQUEST['qk_6'] . '",
												   					ket6="' . $_REQUEST['spk_ket_qk6'] . '",
												   					dokter_pemeriksa="' . $_REQUEST['dokter_pemeriksa'] . '",
												   					tinggibadan="' . $_REQUEST['spk_tbadan'] . '",
												   					beratbadan="' . $_REQUEST['spk_bbadan'] . '",
												   					tekanandarah="' . $_REQUEST['spk_tdarah'] . '",
												   					nadi="' . $_REQUEST['spk_nadi'] . '",
												   					pernafasan="' . $_REQUEST['spk_nafas'] . '",
												   					guladarah="' . $_REQUEST['spk_guladarah'] . '",
												   					kesimpulan="' . $_REQUEST['periksa_kesehatan'] . '",
												   					catatan="' . $_REQUEST['catatan'] . '",
												   					tgl_periksa="' . $_REQUEST['spk_tglcheck'] . '",
												   					plafond="' . $plafondnya . '",
												   					tgl_asuransi="' . $_REQUEST['spk_tglakad'] . '",
												   					tenor="' . $_REQUEST['spk_jwaktu'] . '",
												   					tgl_akhir_asuransi="' . $met_tgl_akhir . '",
												   					x_premi="' . $premi_x . '",
												   					x_usia="' . $umur . '",
												   					cabang="' . $_REQUEST['spk_nmcabbank'] . '",
												   					input_by="' . $_SESSION['nm_user'] . '",
												   					input_date="' . $futgl . '"');

			$mail = new PHPMailer; // call the class
			$mail->IsSMTP();
			$mail->Host = SMTP_HOST; //Hostname of the mail server
			$mail->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
			$mail->Username = SMTP_UNAME; //Username for SMTP authentication any valid email created in your domain
			$mail->Password = SMTP_PWORD; //Password for SMTP authentication
			$mail->AddReplyTo = SMTP_REPLYMAIL; //reply-to address
			$mail->debug = 1;
			$mail->SMTPSecure = "ssl";

			$mail->SetFrom ($q['email'], $q['nm_lengkap']); //From address of the mail
			$mail->Subject = "AJKOnline - APPROVE DATA SPK"; //Subject od your mail
			// EMAIL SPV SPK
			$mailstaff = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="" AND level="' . $q['level'] . '" AND status="" AND supervisor="1"');
			while ($mailstaff_ = mysql_fetch_array($mailstaff)) {
				$mail->AddAddress($mailstaff_['email'], $mailstaff_['nm_lengkap']); //To address who will receive this email
			}
			// EMAIL SPV SPK
			$mail->AddBCC("adonai.notif@gmail.com");
			$mail->AddBCC("sysdev@kode.web.id");
			$mail->MsgHTML('<table><tr><th>Nomor SPK ' . $spkdokter['spak'] . ' telah diinput oleh <b>' . $_SESSION['nm_user'] . ' selaku Staff AJK-Online pada tanggal ' . $futgl . '</tr></table>' . $message); //Put your body of the message you can place html code here
			$send = $mail->Send(); //Send the mails
			echo '<center><h2>Data SPK telah diinput oleh ' . $q['nm_lengkap'] . ' pada tanggal ' . $futgl . '.</h2></center><meta http-equiv="refresh" content="3;URL=ajk_val_upl.php?v=spkForm">';
		}
	}

echo '<div class="container">
<div class="row">
	<div class="span12"><h1 style="text-align:center;">Surat Pemeriksaan Kesehatan "SPK"<br />Nomor : ' . $spkdokter['spak'] . '</h1></div>
</div><!-- .row -->
<hr>
	<div class="row">
    <div class="span12"><h3>Upload SPK : ' . strtoupper($spkdokter['input_by']) . '</h3></div>
	<form method="post" action="">
    <div class="span6">
    	<fieldset><legend>Data Debitur</legend></fieldset>
		<div class="span2"><label class="control-label" for="inputNama">Nama <font color="red">*</font></label></div>
		<div class="span10"><input name="spk_nama" type="text" value="' . $_REQUEST['spk_nama'] . '" placeholder="Nama Peserta"> ' . $error_1 . '</div>
        <div class="span2"><label class="control-label" for="inputjnsKelamin">Jenis Kelamin</label></div>
		<div class="span10"><label class="radio"><input type="radio" name="spk_sex" value="M"' . pilih($_REQUEST["spk_sex"], "M") . '>Pria</label>
							<label class="radio"><input type="radio" name="spk_sex" value="F"' . pilih($_REQUEST["spk_sex"], "F") . '>Wanita</label>
		</div>
        <div class="span2"><label class="control-label" for="inputjnsKelamin">Tanggal Lahir  <font color="red">*</font></label></div>
        <div class="span10"><input type="text" name="spk_dob" id="rdob" class="tanggal" value="' . $_REQUEST['spk_dob'] . '" placeholder="Tanggal Lahir"> ' . $error_2 . '</div>
        <div class="span2"><label class="control-label" for="inputjnsKelamin">Alamat <font color="red">*</font></label></div>
        <div class="span10"><textarea name="spk_alamat" type="text" rows="1" placeholder="Alamat">' . $_REQUEST['spk_alamat'] . '</textarea> ' . $error_3 . '</div>
        <div class="span2"><label class="control-label" for="inputPekerjaan">Pekerjaan</label></div>
		<div class="span10"><input name="spk_pekerjaan" type="text" size="50" placeholder="Pekerjaan" value="' . $_REQUEST['spk_pekerjaan'] . '"></div>

	</div>
	<div class="span6">
		<fieldset><legend>Asurani Kredit</legend></fieldset>
		<div class="span2"><label class="control-label" for="inputPinjaman">Jumlah Pinjaman/Kredit <font color="red">*</font> ' . $error_12 . ' ' . $error_15 . ' '.$error_16.'</label></div>
		<div class="span10"><input type="text" name="spk_plafond" value="' . $_REQUEST['spk_plafond'] . '" size="30" id="inputku" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);"/ placeholder="Plafond"></div>
        <div class="span2"><label class="control-label" for="inputTanggalAkad">Tanggal Akad/Kredit <font color="red">*</font> ' . $error_13 . '</label></div>
		<div class="span10"><input type="text" name="spk_tglakad" id="spk_tglakad" class="tanggal" value="' . $_REQUEST['spk_tglakad'] . '" placeholder="Tangal Akad"></div>
        <div class="span2"><label class="control-label" for="inputjnsKelamin">Jangka Waktu <font color="blue">(Tahun)</font> <font color="red">*</font> ' . $error_11 . '</label></div>
        <div class="span10"><input type="text" name="spk_jwaktu" value="' . $_REQUEST['spk_jwaktu'] . '" size="5" maxlength="2" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"/ placeholder="Tenor (Tahun)"></div>
        <div class="span2"><label class="control-label" for="inputCabang">Cabang<font color="red">*</font> ' . $error_14 . '</label></div>
        <div class="span10"><input type="text" name="spk_nmcabbank" value="' . $_REQUEST['spk_nmcabbank'] . '" size="50"/ placeholder="Nama Cabang Bank / Koperasi"></div>
	</div>
	<div class="span12">
		<fieldset><legend>Questioner Kesehatan</legend></fieldset>
		<div class="span12"><label class="control-label" for="inputCabang">1. Apakah anda dalam jangka 5 tahun terakhir ini pernah atau sedang menderita penyakit, Asma, Cacat, Tumor/Kanker, TBC, Kencing manis , Hati, Ginjal, Jantung, Stroke, Tekanan Darah Tinggi, Epilepsi, Gangguan Jiwa, Penyakit Autoimun, Keterbelakngan Mental atau Idiot. Jika "Ya", Jelaskan. <font color="red">*</font> ' . $error_4 . '</label></div>
        <div class="span1"><label class="radio"><input type="radio" name="qk_1" value="Y"' . pilih($_REQUEST["qk_1"], "Y") . '>Ya</label>
							<label class="radio"><input type="radio" name="qk_1" value="T"' . pilih($_REQUEST["qk_1"], "T") . '>Tidak</label>
		</div>
		<div class="span11"><textarea name="spk_ket_qk1" rows="2" placeholder="Keterangan Pertanyaan 1">' . $_REQUEST['spk_ket_qk1'] . '</textarea></div>

		<div class="span12"><label class="control-label" for="inputCabang">2. Apakah Berat badan Anda berubah dalam 12 bulan terakhir ini, Jika "Ya", Jelaskan. <font color="red">*</font> ' . $error_5 . '</label></div>
        <div class="span1"><label class="radio"><input type="radio" name="qk_2" value="Y"' . pilih($_REQUEST["qk_2"], "Y") . '>Ya</label>
							<label class="radio"><input type="radio" name="qk_2" value="T"' . pilih($_REQUEST["qk_2"], "T") . '>Tidak</label>
		</div>
		<div class="span11"><textarea name="spk_ket_qk2" rows="2" placeholder="Keterangan Pertanyaan 2">' . $_REQUEST['spk_ket_qk2'] . '</textarea></div>

		<div class="span12"><label class="control-label" for="inputCabang">3. Apakah anda menderita HIV/AIDS? <font color="red">*</font> ' . $error_6 . '</label></div>
        <div class="span1"><label class="radio"><input type="radio" name="qk_3" value="Y"' . pilih($_REQUEST["qk_3"], "Y") . '>Ya</label>
							<label class="radio"><input type="radio" name="qk_3" value="T"' . pilih($_REQUEST["qk_3"], "T") . '>Tidak</label>
		</div>
		<div class="span11"><textarea name="spk_ket_qk3" rows="2" placeholder="Keterangan Pertanyaan 3">' . $_REQUEST['spk_ket_qk3'] . '</textarea></div>

		<div class="span12"><label class="control-label" for="inputCabang">4. Apakah anda mengkonsumsi rutin (ketergantungan) pada Narkoba? Jika "Ya", Sebutkan jenisnya? <font color="red">*</font> ' . $error_7 . '</label></div>
        <div class="span1"><label class="radio"><input type="radio" name="qk_4" value="Y"' . pilih($_REQUEST["qk_4"], "Y") . '>Ya</label>
							<label class="radio"><input type="radio" name="qk_4" value="T"' . pilih($_REQUEST["qk_4"], "T") . '>Tidak</label>
		</div>
		<div class="span11"><textarea name="spk_ket_qk4" rows="2" placeholder="Keterangan Pertanyaan 4">' . $_REQUEST['spk_ket_qk4'] . '</textarea></div>

		<div class="span12"><label class="control-label" for="inputCabang">5. <b>Khusus untuk Wanita</b>, apakah anda sedang hamil ? Jika "Ya" adalah komplikasi / penyakit kehamilan ? jelaskan? Usia Kandungan? <font color="red">*</font> ' . $error_8 . '</label></div>
        <div class="span1"><label class="radio"><input type="radio" name="qk_5" value="Y"' . pilih($_REQUEST["qk_5"], "Y") . '>Ya</label>
							<label class="radio"><input type="radio" name="qk_5" value="T"' . pilih($_REQUEST["qk_5"], "T") . '>Tidak</label>
		</div>
		<div class="span11"><textarea name="spk_ket_qk5" rows="2" placeholder="Keterangan Pertanyaan 5">' . $_REQUEST['spk_ket_qk5'] . '</textarea></div>

		<div class="span12"><label class="control-label" for="inputCabang">6. Apakah anda seorang perokok? Jika "Ya" berapa batang perhari? <font color="red">*</font> ' . $error_9 . '</label></div>
        <div class="span1"><label class="radio"><input type="radio" name="qk_6" value="Y"' . pilih($_REQUEST["qk_6"], "Y") . '>Ya</label>
							<label class="radio"><input type="radio" name="qk_6" value="T"' . pilih($_REQUEST["qk_6"], "T") . '>Tidak</label>
		</div>
		<div class="span11"><textarea name="spk_ket_qk6" rows="2" placeholder="Keterangan Pertanyaan 6">' . $_REQUEST['spk_ket_qk6'] . '</textarea></div>
	</div>

	<div class="span12">
		<fieldset><legend>Pemeriksaan Kesehatan</legend></fieldset>
		<div class="span2"><label class="control-label" for="inputPinjaman">Nama Dokter Pemeriksa</label></div>
		<div class="span9"><input name="dokter_pemeriksa" type="text" size="30" value="' . $_REQUEST['dokter_pemeriksa'] . '" placeholder="Nama Dokter Pemeriksa"></div>
		<div class="span2"><label class="control-label" for="inputPinjaman">Tinggi Berat Badan</label></div>
		<div class="span9"><input name="spk_tbadan" type="text" size="15" value="' . $_REQUEST['spk_tbadan'] . '" placeholder="Tinggi Badan" onkeyup="this.value=this.value.replace(/[^0-9/,]/g,\'\')"> <input name="spk_bbadan" type="text" size="15" value="' . $_REQUEST['spk_bbadan'] . '" placeholder="Berat Badan" onkeyup="this.value=this.value.replace(/[^0-9/,]/g,\'\')"></div>
		<div class="span2"><label class="control-label" for="inputPinjaman">Tekanan Darah</label></div>
		<div class="span9"><input name="spk_tdarah" type="text" size="15" value="' . $_REQUEST['spk_tdarah'] . '" placeholder="Tekanan darah" onkeyup="this.value=this.value.replace(/[^0-9//]/g,\'\')"></div>
		<div class="span2"><label class="control-label" for="inputPinjaman">Nadi</label></div>
		<div class="span9"><input name="spk_nadi" type="text" size="15" value="' . $_REQUEST['spk_nadi'] . '" placeholder="Nadi" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"></div>
		<div class="span2"><label class="control-label" for="inputPinjaman">Pernafasan</label></div>
		<div class="span9"><input name="spk_nafas" type="text" size="15" value="' . $_REQUEST['spk_nafas'] . '" placeholder="Pernafasan" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"></div>
		<div class="span2"><label class="control-label" for="inputPinjaman">Gula Darah</label></div>
		<div class="span9"><input name="spk_guladarah" type="text" size="15" value="' . $_REQUEST['spk_guladarah'] . '" placeholder="Gula darah" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"></div>
		<div class="span2"><label class="control-label" for="inputPinjaman">Tanggal Pemeriksaan <font color="red">*</font></label></div>
		<div class="span9"><input type="text" name="spk_tglcheck" id="spk_tglcheck" class="tanggal" value="' . $_REQUEST['spk_tglcheck'] . '" size="10"/> ' . $error_10 . '</div>
		<div class="span2"><label class="control-label" for="inputPinjaman">Catatan <font color="red">*</font></label></div>
		<div class="span9"><textarea name="catatan" rows="1" cols="70" placeholder="Catatan">' . $_REQUEST['catatan'] . '</textarea> ' . $error_10 . '</div>
		<div class="span12"><label class="control-label" for="inputPinjaman">Dari pemeriksaan dan keterangan kesehatan diatas saya simpulkan bahwa saat ini calon Debitur dalam keadaan</label></div>
		<div class="span12"><textarea name="periksa_kesehatan" rows="1" cols="70" placeholder="Pemeriksaan Kesehatan">' . $_REQUEST['periksa_kesehatan'] . '</textarea></div>

	</div>
	<div class="span12" align="center"><div class="controls"><button type="submit" class="btn-success" name="ope" value="Simpan">Sign in</button></div></div>
	</form>
</div>';
	;
	break;

case "_spkApprove":
	if (!$_REQUEST['namaspk']) {
		echo '<center><font color=red><blink>Tidak ada data SPK yang di pilih, silahkan ceklist data yang akan di Approve. !</blink></font><br/>
	  <a href="ajk_uploader_spak.php?r=set_spak">Kembali Ke Halaman Approve Data SPK</a></center>';
	} else {
		$mail = new PHPMailer; // call the class
		$mail->IsSMTP();
		$mail->Host = SMTP_HOST; //Hostname of the mail server
		$mail->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
		$mail->Username = SMTP_UNAME; //Username for SMTP authentication any valid email created in your domain
		$mail->Password = SMTP_PWORD; //Password for SMTP authentication
		$mail->AddReplyTo = SMTP_REPLYMAIL; //reply-to address
		$mail->debug = 1;
		$mail->SMTPSecure = "ssl";
		$mail->SetFrom ($q['email'], $q['nm_lengkap']); //From address of the mail
		$mail->Subject = "AJKOnline - APPROVE DATA SPK"; //Subject od your mail

		$cekbatch = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_batch ORDER BY id DESC'));
		$fakbatch = $cekbatch['id'] + 1;
		$idkode = 100000000 + $fakbatch;
		$idkode2 = substr($idkode, 1); // ID PESERTA //
		$kodebatch = 'B.' . date("ymd") . '.' . $idkode2;
		$batchadonai_met = $database->doQuery('INSERT INTO fu_ajk_batch SET idb="' . $idkode2 . '", no_batch="' . $kodebatch . '", input_by="' . $q['nm_user'] . '", input_time="' . $futgl . '"');
		$cekbatch_met = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_batch ORDER BY id DESC'));

		$message .= '<table border="0" width="100%" cellpadding="1" cellspacing="3">
			<tr><td colspan="2">Nomor Batch</td><td colspan="6"><b>' . $kodebatch . '</b></td></tr>
			<tr bgcolor="aqua">
				<td width="1%" align="center">NO</td>
				<td align="center" width="10%">NOMOR SPK</td>
				<td align="center">NAMA</td>
				<td width="10%" align="center">USIA</td>
				<td width="10%" align="center">PREMI</td>
				<td align="center">EXT.PREMI (%)</td>
				<td align="center">EXT.PREMI</td>
				<td align="center">TOTAL PREMI</td>
			</tr>';

		foreach($_REQUEST['namaspk'] as $k => $val) {
			$_met_data_ = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak WHERE id="' . $val . '"'));
			$_met_data_spk = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_form WHERE idcost="' . $_met_data_['id_cost'] . '" AND idspk="' . $_met_data_['id'] . '"'));
			// echo $_met_data_spk['tgl_asuransi'].'<br />';
			if ($_met_data_spk['tgl_asuransi'] == "0000-00-00") {
				$met_tgl_akhir = date('Y-m-d', strtotime('+' . $_met_data_spk['tenor'] . ' year', strtotime($futoday))); //operasi penjumlahan tanggal
				$metUpdatetglAkad = $database->doQuery('UPDATE fu_ajk_spak_form SET tgl_asuransi="'.$futoday.'", tgl_akhir_asuransi="'.$met_tgl_akhir.'" WHERE idcost="' . $_met_data_['id_cost'] . '" AND idspk="' . $_met_data_['id'] . '"');
			} else {	}
			$nilai_premi_spk = $_met_data_spk['x_premi'] * $_met_data_['ext_premi'] / 100;
			$nett_premi_spk = $_met_data_spk['x_premi'] + $nilai_premi_spk;
			$met_spk = $database->doQuery('UPDATE fu_ajk_spak SET status="Aktif", id_batch="'.$cekbatch_met['id'].'", approve_by="'.$q['nm_user'].'", approve_date="'.$futgl.'" WHERE id="'.$val.'"');
			$message .= '<tr><td align="center">' . ++$no . '</td>
				<td align="center">' . $_met_data_['spak'] . '</td>
				<td>' . $_met_data_spk['nama'] . '</td>
				<td align="center">' . $_met_data_spk['x_usia'] . '</td>
				<td align="right">' . duit($_met_data_spk['x_premi']) . '</td>
				<td align="center">' . duit($_met_data_['ext_premi']) . '%</td>
				<td align="center">' . duit($nilai_premi_spk) . '</td>
				<td align="right">' . duit($nett_premi_spk) . '</td>
			</tr>';
		}
		$message .= '</table>';

		// EMAIL STAFF SPK

/*
   $mailstaff = $database->doQuery('SELECT * FROM user_mobile WHERE id="'.$_met_data_['input_by'].'"');
   while ($mailstaff_ = mysql_fetch_array($mailstaff)) {
   $mail->AddAddress($mailstaff_['email'], $mailstaff_['namalengkap']); //To address who will receive this email
   }
*/
		$_mailstaff = mysql_fetch_array($database->doQuery('SELECT * FROM user_mobile WHERE id="'.$_met_data_['input_by'].'"'));
		$mail->AddAddress($_mailstaff['email'], $_mailstaff['namalengkap']); //To address who will recei
		//echo $_mailstaff['email'].'<br />';
		// EMAIL STAFF SPK
		// EMAIL SPV SPK

/*
   $cekuser = mysql_fetch_array($database->doQuery('SELECT id FROM user_mobile WHERE id="'.$_met_data_['input_by'].'"'));
   $mailspv = $database->doQuery('SELECT * FROM user_mobile WHERE id="'.$cekuser['supervisor'].'"');
   while ($mailspv_ = mysql_fetch_array($mailspv)) {
   $mail->AddAddress($mailspv_['email'], $mailspv_['namalengkap']); //To address who will receive this email
   }
*/
		$_mailspv = mysql_fetch_array($database->doQuery('SELECT * FROM user_mobile WHERE id="'.$_mailstaff['supervisor'].'"'));
		$mail->AddAddress($_mailspv['email'], $_mailspv['namalengkap']); //To address who will recei
		//echo $_mailspv['email'].'<br />';

		// EMAIL SPV SPK

		$mail->AddBCC("adonai.notif@gmail.com");
		$mail->AddBCC("sysdev@kode.web.id");
		$mail->MsgHTML('<center>Data SPK telah di approve oleh <b>' . $_SESSION['nm_user'] . ' selaku Dokter PT Adonai AJK-Online pada tanggal ' . $futgl . '</center>' . $message);
		$send = $mail->Send(); //Send the mails

		//echo $message;
		//echo '<center>Approve data SPK dengan nomor Batch '.$kodebatch.' oleh <b>'.$_SESSION['nm_user'].'</b> telah berhasil.<br /> <a href="ajk_uploader_spak.php?r=set_spak">Kembali ke Modul SPK.</a></center>';
		echo '<center>Approve data SPK oleh <b>'.$_SESSION['nm_user'].'</b> telah berhasil.<br /> <a href="ajk_val_upl.php?v=spkForm">Kembali ke Modul SPK.</a></center>';
	} ;
		;
		break;



case "spk_del":
	if ($_REQUEST['x_spk']=="mobdel") {
	$mobform1 = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_form_temp WHERE id="'.$_REQUEST['id'].'"'));
	$metspk = mysql_fetch_array($database->doQuery('SELECT id, spak FROM fu_ajk_spak WHERE id="'.$mobform1['idspk'].'"'));
	$mobspk = $database->doQuery('UPDATE fu_ajk_spak SET status="Batal", keterangan="'.$_REQUEST['pembatalan'].'" WHERE id="'.$mobform1['idspk'].'"');
	//echo('UPDATE fu_ajk_spak SET status="Batal", keterangan="'.$_REQUEST['pembatalan'].'" WHERE id="'.$mobform1['idspk'].'"');
	$metmailstaff = mysql_fetch_array($database->doQuery('SELECT id, namalengkap, email,supervisor FROM user_mobile WHERE id="'.$mobform1['input_by'].'"'));
	$metmailspv = mysql_fetch_array($database->doQuery('SELECT id, namalengkap, email FROM user_mobile WHERE id="'.$metmailstaff['supervisor'].'"'));
	//echo $metmailstaff['email'].'<br />';

	$mail = new PHPMailer;
		$mail->IsSMTP();
		$mail->Host = SMTP_HOST; //Hostname of the mail server
		$mail->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
		$mail->Username = SMTP_UNAME; //Username for SMTP authentication any valid email created in your domain
		$mail->Password = SMTP_PWORD; //Password for SMTP authentication
		$mail->AddReplyTo = SMTP_REPLYMAIL; //reply-to address
		$mail->debug = 1;
		$mail->SMTPSecure = "ssl";
		$mail->SetFrom ($metmailspv['email'], $metmailspv['namalengkap']);
		$mail->Subject = "AJKOnline - PEMBATALAN DATA SPK";

	$message .= 'Kepada '.$metmailstaff['namalengkap'].',<br />
				 Data pembatalan oleh Supervisor :<br />
				 <table border="0" width="100%">
				 <tr><td width="20%">NOMOR SPK</td><td><b>'.$metspk['spak'].'</b></td></tr>
				 <tr><td>Nama Nasabah</td><td><b>'.$mobform1['nama'].'</b></td></tr>
				 <tr><td colspan="2">Alasan Pembatalan</td></tr>
				 <tr><td colspan="2">'.$_REQUEST['pembatalan'].'</td></tr>';
	$message .='</table><br />Terimakasih, ';
	//EMAIL STAFF MOBILE
	echo $metmailstaff['email'];
	echo $metmailstaff['email'];
	$mail->AddAddress($metmailstaff['email'], $metmailstaff['namalengkap']); //To address who will receive this email
	//EMAIL STAFF MOBILE

	$mail->AddCC("penting_ga@hotmail.com, sysdev@kode.web.id, arief@arief.kurniawan.com, gunarso@adonai.co.id");
	$mail->MsgHTML($message); //Put your body of the message you can place html code here
	$send = $mail->Send(); //Send the mails
	//echo $message.'<br />';


/*
		$headers = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$to = $metmailstaff['email'];
		$subject = "AJK";
		$headers .= 'From: '.$metmailspv['email'].'' . "\r\n";
		$headers .= 'Cc: sysdev@kode.web.id' . "\r\n";
		echo '<br />';
		echo $to.'<br />';
		echo $metmailspv['email'].'<br />';
		echo $subject.'<br />';
		echo $message.'<br />';
		echo $headers.'<br />';
		mail($to, $subject, $message, $headers) or die("Error!");
*/
	header("location:imob.php?ob=appspk");
	}else{
	$cekdata_spak = $database->doQuery('SELECT * FROM fu_ajk_spak_temp WHERE id_cost="'.$_REQUEST['id_cost'].'" AND spak="'.$_REQUEST['spak'].'" AND fname="'.$_REQUEST['namafile'].'"');
		while ($cekdata_spak_ = mysql_fetch_array($cekdata_spak)) {
			unlink ($metpath .$cekdata_spak_['fname']);
		}
	$met = $database->doQuery('DELETE FROM fu_ajk_spak_temp WHERE id_cost="'.$_REQUEST['id_cost'].'" AND spak="'.$_REQUEST['spak'].'" AND fname="'.$_REQUEST['namafile'].'"');
	//header("location:ajk_val_upl.php?v=spk");
	}
	;
	break;

	case "spk":
echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="100%" align="left">Modul Validasi Upload Data SPK</font></th></tr></table>';
if ($_REQUEST['exp']=="tambah_expremi") {
$metspk__ = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_temp WHERE spak="'.$_REQUEST['spk'].'" AND status="Pending"'));
	if ($_REQUEST['save_expremi']=="Save") {
	if ($_REQUEST['expremi']=="") 	{	$error_1 = '<tr><td colspan="3"><font color="red"><blink>Silahkan input data extra premi.<br /></font></td></tr>';	}
	if ($_REQUEST['ext_ket']=="") 	{	$error_2 = '<tr><td colspan="3"><font color="red"><blink>Silahkan isi keterangan extra premi.<br /></font></td></tr>';	}
	if ($error_1 OR $error_2){		}
	else{
	$mametspk = $database->doQuery('UPDATE fu_ajk_spak_temp SET ext_premi="'.$_REQUEST['expremi'].'", ket_ext="'.$_REQUEST['ext_ket'].'" WHERE spak="'.$_REQUEST['spkid'].'"');
	header('location:ajk_val_upl.php?v=spk');
		}
	}

echo '<form method="post" action="">
	  <table border="0" width="100%" cellpadding="1" cellspacing="1">
	  <tr class="tr1"><th colspan="3">Tambah Data Extra Premi</th></tr>
	  '.$error_1.'
	  <tr><td width="10%"><input type="hidden" name="spkid" value="'.$_REQUEST['spk'].'">Extra Premi <font color="red">*</font></td>
		  <td width="1%">: </td><td><input type="text" name="expremi" value="'.$metspk__['ext_premi'].'" maxlength="2" size="5" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')">%</td>
	  </tr>
	  '.$error_2.'
	  <tr><td valign="top">Keterangan <font color="red">*</font></td><td valign="top">: </td><td><textarea name="ext_ket"cols="30" rows="1">'.$metspk__['ket_ext'].'</textarea></td></tr>
	  <tr><td><input type="submit" name="save_expremi" Value="Save"> &nbsp; <a href="ajk_val_upl.php?v=spk">Batal</a></td></tr>
	  </table></form>';
}
echo '<form method="post" action="ajk_val_upl.php?v=spk_appr" onload ="onbeforeunload">
	  <table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#bde0e6">
	  <tr><th width="1%"></th>
	  	  <th width="1%"><input type="checkbox" id="selectall"/></th>
	  	  <th width="1%">No</th>
	  	  <th width="1%">Status</th>
		  <th width="10%">SPAK</th>
		  <th width="5%">Ex.Premi(%)</th>
		  <th>Nama File</th>
		  <th>User</th>
	  	  <th width="15%">Tgl Upload</th>
	  	  <th width="5%">Photo</th>
	  </tr>';
$metspk = $database->doQuery('SELECT * FROM fu_ajk_spak_temp WHERE status="Pending" ORDER BY input_date DESC');
while ($metspk_ = mysql_fetch_array($metspk)) {

if ($q['id_cost']==$q['id_cost'] AND $q['level']==99 AND $q['status']=="") {
$metdokterappr = '<tr><td colspan="9" align="center"><a href="ajk_val_upl.php?v=spk_appr" onClick="if(confirm(\'Apakah anda sudah yakin dengan semua data SPK ini ?\')){return true;}{return false;}"><input type="submit" name="ApproveClaim" Value="Approve"></a></td></tr>';
//$metdokterext_ = '<a href="ajk_val_upl.php?v=spk&exp=tambah_expremi&spk='.$metspk_['spak'].'" title="tambah extra premi"><img src="image/plus.png" width="15"></a>'; DISABLED (141017)
}else{	}

if ($metspk_['photo_spk']=="") {	$v_photo = '<img src="image/non-user.png" width="50">';
}else{
	$v_photo = '<a href="'.$metpath.''.$metspk_['photo_spk'].'" rel="lightbox" ><img src="'.$metpath.''.$metspk_['photo_spk'].'" width="50"></a>';
}
if (($no % 2) == 1)	$objlass = 'tbl-odd'; else				$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	  <td align="center"><a href="ajk_val_upl.php?v=spk_del&id_cost='.$metspk_['id_cost'].'&spak='.$metspk_['spak'].'&namafile='.$metspk_['fname'].'" onClick="if(confirm(\'Apakah anda yakin untuk menghapus data SPK ini ?\')){return true;}{return false;}"><img src="image/delete.gif"></a></td>
	  <td align="center"><input type="checkbox" class="case" name="nama[]" value="'.$metspk_['id_cost'].'-met-'.$metspk_['spak'].'-met-'.$metspk_['fname'].'"></td>
	  <td align="center">'.++$no.'</td>
	  <td align="center">'.$metspk_['status'].'</td>
	  <td align="center">'.$metspk_['spak'].'</td>
	  <td align="center">'.$metspk_['ext_premi'].'</td>
	  <td><a href="ajk_file/_spak/'.$metspk_['fname'].'" target="_blank">'.$metspk_['fname'].'</a></td>
	  <td align="center">'.$metspk_['input_by'].'</td>
	  <td align="center">'.$metspk_['input_date'].'</td>
	  <td align="center">'.$v_photo.'</td>
	  </tr>';
	}
echo $metdokterappr;

echo '</table></form>';
		;
		break;

	case "spk_appr":
echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="100%" align="left">Modul Validasi Upload Data SPK</font></th></tr></table>';
if (!$_REQUEST['nama']) {
echo '<center><font color=red><blink>Tidak ada data SPK yang di pilih, silahkan ceklist data yang akan di Approve. !</blink></font><br/>
	  <a href="ajk_val_upl.php?v=spk">Kembali Ke Halaman Approve SPK</a></center>';
}else{
	$message .= '<table border="0" width="100%" cellpadding="1" cellspacing="3">
				 <tr><td width="1%">No</td>
				 	 <td width="10%">SPK</td>
				 	 <td width="10%">Keterangan</td>
				 	 <td>Nama File</td>
				 	 <td>User Upload</td>
				 	 <td>Tgl Upload</td>
				 	 <td>User Approve</td>
				 	 <td>Tgl Approve</td>
				 </tr>';
foreach($_REQUEST['nama'] as $k => $val){
	$vall = explode("-met-", $val);		//EXPLODE DATA BERDASARKAN CHEKLIST//
	$cekspk = $database->doQuery('SELECT * FROM fu_ajk_spak_temp WHERE id_cost="'.$vall[0].'" AND spak="'.$vall[1].'" AND fname="'.$vall[2].'"');
	while ($cekspk_ = mysql_fetch_array($cekspk)) {
	$mameto = $database->doQuery('INSERT INTO fu_ajk_spak SET id_cost="'.$cekspk_['id_cost'].'",
															  id_polis="'.$cekspk_['id_polis'].'",
															  spak="'.$cekspk_['spak'].'",
															  ext_premi="'.$cekspk_['ext_premi'].'",
															  ket_ext="'.$cekspk_['ket_ext'].'",
															  fname="'.$cekspk_['fname'].'",
															  photo_spk="'.$cekspk_['photo_spk'].'",
															  status="Proses",
															  input_by="'.$cekspk_['input_by'].'",
															  input_date="'.$cekspk_['input_date'].'",
															  update_by="'.$q['nm_lengkap'].'",
															  update_date="'.$futgl.'"');

	$message .='<tr><td>'.++$no.'</td>
					<td>'.$cekspk_['spak'].'</td>
					<td>'.$cekspk_['ket_ext'].'</td>
					<td>'.$cekspk_['fname'].'</td>
					<td>'.$cekspk_['input_by'].'</td>
					<td>'.$cekspk_['input_date'].'</td>
					<td>'.$q['nm_lengkap'].'</td>
					<td>'.$futgl.'</td>
				</tr>';

	}
	$metdel__ = $database->doQuery('DELETE FROM fu_ajk_spak_temp WHERE id_cost="'.$vall[0].'" AND spak="'.$vall[1].'" AND fname="'.$vall[2].'"');
	}
	$message .='</table>';
	/* SMTP MAIL */
	$mail = new PHPMailer; // call the class
	$mail->IsSMTP();
	$mail->Host = SMTP_HOST; //Hostname of the mail server
	$mail->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
	$mail->Username = SMTP_UNAME; //Username for SMTP authentication any valid email created in your domain
	$mail->Password = SMTP_PWORD; //Password for SMTP authentication
	$mail->AddReplyTo = SMTP_REPLYMAIL; //reply-to address
	$mail->debug = 1;
	$mail->SMTPSecure = "ssl";
	$mail->SetFrom ($q['email'], $q['nm_lengkap']); //From address of the mail
	$mail->Subject = "AJKOnline - APPROVE DATA SPK"; //Subject od your mail
	//EMAIL STAFF SPK
	$mailstaff = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="'.$q['id_cost'].'" AND level="'.$q['level'].'" AND status!=""');
while ($mailstaff_ = mysql_fetch_array($mailstaff)) {
	$mail->AddAddress($mailstaff_['email'], $mailstaff_['nm_lengkap']); //To address who will receive this email
}
	$maildokter = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="" AND level="'.$q['level'].'"');
while ($maildokter_ = mysql_fetch_array($maildokter)) {
	$mail->AddAddress($maildokter_['email'], $maildokter_['nm_lengkap']); //To address who will receive this email
}
	//EMAIL STAFF SPK

	/*EMAIL ADONAI PUSAT	 DISABLED 141017
	$mailkonfirmasi = $database->doQuery('SELECT * FROM pengguna WHERE id_cost=""');
while ($mailkonfirmasi_ = mysql_fetch_array($mailkonfirmasi)) {
	$mail->AddAddress($mailkonfirmasi_['email'], $mailkonfirmasi_['nm_lengkap']); //To address who will receive this email
}
	//EMAIL ADONAI PUSAT*/

	$mail->AddCC("penting_ga@hotmail.com, sysdev@kode.web.id, arief@arief.kurniawan.com, gunarso@adonai.co.id");
	$mail->MsgHTML('<table><tr><th>Data SPK telah di approve oleh <b>'.$_SESSION['nm_user'].' selaku Supervisor AJK-Online pada tanggal '.$futgl.'</tr></table>'.$message); //Put your body of the message you can place html code here
	$send = $mail->Send(); //Send the mails

	echo '<center>Approve data SPK oleh <b>'.$_SESSION['nm_user'].'</b> telah berhasil.<br /> <a href="ajk_val_upl.php?v=spk">Kembali ke Modul SPK.</a></center>';
}
	;
	break;

	case "spaj":
$fu1 = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$q['id_cost'].'"'));
$fu2 = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$q['id_polis'].'"'));
echo '<fieldset style="padding: 1">
	<legend align="center">S e a r c h</legend>
	<form name="f2" method="post" action="">
	<table border="0" width="75%" cellpadding="0" cellspacing="0">
	<tr><td width="15%">Nama Perusahaan</td>
		<td>: <input type="hidden" name="idcost" value="'.$q['id_cost'].'">'.$fu1['name'].'</td>
	</tr>';
if ($q['id_polis']=="" AND $q['level']=="6") {
$met_polis = $database->doQuery('SELECT * FROM fu_ajk_polis WHERE id_cost="'.$q['id_cost'].'" AND del IS NULL ORDER BY nmproduk ASC');
echo '<tr><td>Nama Produk</td>
		  <td>: <select name="id_polis">
				<option value="">---Pilih Produk---</option>';
while($met_polis_ = mysql_fetch_array($met_polis)) {
echo '<option value="'.$met_polis_['id'].'"'._selected($_REQUEST['id_polis'], $met_polis_['id']).'>'.$met_polis_['nmproduk'].'</option>';
}
echo '</select> <input name="Submit" type="submit" value="Pilih"></td></tr>';
$data = $database->doQuery('SELECT * FROM fu_ajk_peserta_tempf WHERE nama !="" AND id_cost="'.$q['id_cost'].'" AND id_polis="'.$_REQUEST['id_polis'].'" AND status_aktif="Upload" AND del IS NULL ORDER BY input_time DESC, namafile ASC, no_urut ASC');
}else{
echo '<tr><td>Nama Produk</td><td>: '.$fu2['nmproduk'].' ('.$fu2['nopol'].')</td></tr>';
$data = $database->doQuery('SELECT * FROM fu_ajk_peserta_tempf WHERE nama !="" AND id_cost="'.$q['id_cost'].'" AND id_polis="'.$q['id_polis'].'" AND input_by="'.$q['nm_user'].'" AND status_aktif="Upload" AND del IS NULL ORDER BY input_time DESC, namafile ASC, no_urut ASC');
}
echo '</table></form></fieldset>';

if ($q['id_cost']!="" AND $q['id_polis']=="") {
if ($_REQUEST['Submit']!="Pilih") {
echo '<table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#bde0e6">
	  <tr><th width="1%">No</th>
		  <th>Nama Produk</th>
		  <th>Jumlah Data</th>
	  </tr>';
	$met_val_data = $database->doQuery('SELECT id_cost, id_polis, count(nama) AS jNAMA FROM fu_ajk_peserta_tempf WHERE nama!="" AND id_cost="'.$q['id_cost'].'" AND status_aktif="Upload" AND del IS NULL GROUP BY id_polis');
	while ($met_val_data_ = mysql_fetch_array($met_val_data)) {
		$metval_polis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id_cost="'.$q['id_cost'].'" AND id="'.$met_val_data_['id_polis'].'"'));
		if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
		echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
				<td align="center">'.++$no.'</td>
				<td>'.$metval_polis['nmproduk'].'</td>
				<td align="center">'.$met_val_data_['jNAMA'].' Peserta</td>
			  </tr>';
	}
echo '</table><br />';
}
}

echo '<form method="post" action="" onload ="onbeforeunload">
	  <table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#bde0e6">
	<tr><th width="1%"></th>
		<th width="1%"><input type="checkbox" id="selectall"/></th>
		<th width="1%">No</th>
		<th width="5%">Nama Mitra</th>
			<th>Nama Tertanggung</th>
			<th width="5%">Tanggal Lahir</th>
			<th width="5%">Uang Asuransi</th>
			<th width="5%">Mulai Asuransi</th>
			<th width="1%">Tenor</th>
			<th width="1%">Usia</th>
			<th width="5%">Rate Premi Standar</th>
			<th width="5%">Tarif Premi</th>
			<th width="1%">EM(%)</th>
			<th width="5%">Premi Sekaligus</th>
			<th width="5%">Medical</th>
			<th width="8%">Regional</th>
			<th width="8%">Cabang</th>
			<th width="8%">Tgl Upload</th>
			<th width="5%">User</th>
		</tr>';
while ($fudata = mysql_fetch_array($data)) {
//	$umur = ceil(((strtotime($fudata['kredit_tgl']) - strtotime($fudata['tgl_lahir']."+6 month")) / (60*60*24*365.2425)));	// FORMULA USIA
if ($_REQUEST['id_polis']==1) {
	//$tenornya = $fudata['kredit_tenor'] / 12 ;	//RATE USIA SEBELUMNYA 151202
	$cekratepolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$fudata['id_cost'].'" AND id_polis="'.$fudata['id_polis'].'" AND tenor="'.$fudata['kredit_tenor'].'" AND usia="'.$fudata['usia'].'" AND status="baru"'));	//VALIDASI CEK TENOR DENGAN SETTING RATE POLIS
	$ppremistandar = $fudata['kredit_jumlah'] * $cekratepolis['rate'] / 1000;

	$metpolisminimum = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$fudata['id_polis'].'" AND id_cost="'.$fudata['id_cost'].'"'));
	if ($ppremistandar <= $metpolisminimum['min_premium']) {
	$premistandar = $metpolisminimum['min_premium'];
	}else{
	$premistandar = $ppremistandar;
	}

	$spak_extpremi = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak WHERE id_cost="'.$fudata['id_cost'].'" AND spak="'.$fudata['spaj'].'" AND status="Aktif"'));
	$extrapreminya = $spak_extpremi['ext_premi'];
	$extrapreminya_ = $premistandar * $spak_extpremi['ext_premi'] / 100;
	$premistandarnya = $premistandar - $extrapreminya_;
}else{
//	$mets = datediff($fudata['kredit_tgl'], $fudata['tgl_lahir']);		//	echo $mets['years'].' Tahun '.$mets['months'].' Bulan '.$mets['days'].' Hari';
//	if ($mets['months'] >= 6 ) {	$umurnya = $mets['years'] + 1;	}else{	$umurnya = $mets['years'];	}

	$cekratepolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$fudata['id_cost'].'" AND id_polis="'.$fudata['id_polis'].'" AND tenor="'.$fudata['kredit_tenor'].'" AND status="baru"'));	//VALIDASI CEK TENOR DENGAN SETTING RATE POLIS
	$ppremistandar = $fudata['kredit_jumlah'] * $cekratepolis['rate'] / 1000;
	$extrapreminya = $fudata['ext_premi'];
	//$premistandarnya = $ppremistandar;

	$metpolisminimum = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$fudata['id_polis'].'" AND id_cost="'.$fudata['id_cost'].'"'));
	if ($ppremistandar <= $metpolisminimum['min_premium']) {
		$premistandar = $metpolisminimum['min_premium'];
	}else{
		$premistandar = $ppremistandar;
	}
}


	$dataceklist = '<input type="checkbox" class="case" name="nama[]" value="'.$fudata['id_cost'].'-met-'.$fudata['id_polis'].'-met-'.$fudata['nama'].'-met-'.$fudata['tgl_lahir'].'-met-'.$fudata['kredit_jumlah'].'">';

$tgl_inputnya = explode(" ",$fudata['input_time']);
if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	  <td align="center"><a href="ajk_val_upl.php?v=deldata&type=spaj&id_cost='.$fudata['id_cost'].'&id_polis='.$fudata['id_polis'].'&namafile='.$fudata['namafile'].'&no_urut='.$fudata['no_urut'].'&spaj='.$fudata['spaj'].'&nama='.$fudata['nama'].'&kredit_jumlah='.$fudata['kredit_jumlah'].'" onClick="if(confirm(\'Apakah anda yakin untuk menghapus data peserta ini ?\')){return true;}{return false;}"><img src="image/delete.gif"></a></td>
	  <td align="center">'.$dataceklist.' </td>
	  <td align="center">'.++$no.'</td>
	  <td align="center">'.$fudata['spaj'].'</td>
	  <td>'.$fudata['nama'].'</td>
	  <td align="center">'._convertDate($fudata['tgl_lahir']).'</td>
	  <td align="right">'.duit($fudata['kredit_jumlah']).'</td>
	  <td align="right">'._convertDate($fudata['kredit_tgl']).'</td>
	  <td align="center">'.$fudata['kredit_tenor'].'</td>
	  <td align="center">'.$fudata['usia'].'</td>
	  <td align="center">'.$cekratepolis['rate'].'</td>
	  <td align="right">'.duit($premistandar).'</td>
	  <td align="center">'.$extrapreminya.'</td>
	  <td align="right">'.duit($premistandar).'</td>
	  <td align="center">'.$fudata['status_medik'].'</td>
	  <td align="center">'.$fudata['regional'].'</td>
	  <td align="center">'.$fudata['cabang'].'</td>
	  <td align="center">'._convertDate($tgl_inputnya[0]).'</td>
	  <td align="center"><font color="blue"><b>'.$fudata['input_by'].'</b></font></td>
	  </tr>';
}
//if ($q['id_cost'] !="" AND $q['id_polis']!="" AND $q['level']=="5" AND $q['status']=="") {
if ($q['id_cost'] !="" AND $q['id_polis']=="" AND $q['level']=="6" AND $q['status']=="") {
echo '<tr><td colspan="20" align="center"><a href="ajk_val_upl.php?v=spaj_appr" onClick="if(confirm(\'Apakah anda sudah yakin dengan semua data peserta ini ?\')){return true;}{return false;}">
										  <input type="hidden" name="v" Value="spaj_appr"><input type="submit" name="ve" Value="Approve"></td></tr>';
}else{	}
		;
		break;

case "spaj_appr":
if (!$_REQUEST['nama']) {
echo '<center><font color=red><blink>Tidak ada data yang di pilih, silahkan ceklist data yang akan di Approve. !</blink></font><br/>
	  <a href="ajk_val_upl.php?v=spaj">Kembali Ke Halaman Approve Peserta</a></center>';
}else{
foreach($_REQUEST['nama'] as $k => $val){
	$vall = explode("-met-", $val);		//EXPLODE DATA BERDASARKAN CHEKLIST//
	$r = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta_tempf WHERE id_cost="'.$vall[0].'" AND id_polis="'.$vall[1].'" AND nama="'.$vall[2].'" AND tgl_lahir="'.$vall[3].'" AND kredit_jumlah="'.$vall[4].'" AND status_aktif="Upload"'));
	$admpolis = mysql_fetch_array($database->doQuery('SELECT id_cost, adminfee, day_kredit, discount, singlerate, min_premium FROM fu_ajk_polis WHERE id_cost="'.$r['id_cost'].'" AND id="'.$r['id_polis'].'"'));

	//$umur = ceil(((strtotime($rr['kredit_tgl']) - strtotime($rr['tgl_lahir']."+6 month")) / (60*60*24*365.2425)));	// FORMULA USIA DI TAMBAH 6 BULAN
	//$umur = ceil(((strtotime($r['kredit_tgl']) - strtotime($r['tgl_lahir'])) / (60*60*24*365.2425)));	// FORMULA USIA	150126

	//MENENTUKAN PERHITUNGAN PREMI BERDASARKAN SINGLE RATE PADA POLIS
	if ($admpolis['singlerate']=="T") {
		$cekrate = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$r['id_cost'].'" AND id_polis="'.$r['id_polis'].'" AND tenor="'.$r['kredit_tenor'].'" AND status="baru"'));		// RATE PREMI
		$cek_extrapremi = $r['ext_premi'];
		$tgl_akhir_kredit = date('Y-m-d',strtotime($r['kredit_tgl']."+".$r['kredit_tenor'] ." Month"."-".$admpolis['day_kredit']." day"));	//KREDIT AKHIR
	}else{
		$mettenornya = $r['kredit_tenor'];
		$cekrate = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$r['id_cost'].'" AND id_polis="'.$r['id_polis'].'" AND usia="'.$r['usia'].'" AND tenor="'.$mettenornya.'" AND status="baru"'));		// RATE PREMI

		$cek_extrapreminya = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak WHERE id_cost="'.$r['id_cost'].'" AND spak="'.$r['spaj'].'" AND status="Aktif"'));
		$cek_extrapremi = $cek_extrapreminya['ext_premi'];
		$metAkhirBulan = $r['kredit_tenor'] * 12;
		$tgl_akhir_kredit = date('Y-m-d',strtotime($r['kredit_tgl']."+".$metAkhirBulan ." Month"."-".$admpolis['day_kredit']." day"));	//KREDIT AKHIR
	}
	//MENENTUKAN PERHITUNGAN PREMI BERDASARKAN SINGLE RATE PADA POLIS
	//$cekminimumpremi = $r['kredit_jumlah'] * $cekrate['rate'] / 1000;		//HITUNG PREMI	150421
	$premi = ROUND(($cekrate['rate'] / 1000) * $r['kredit_jumlah']);		//HITUNG PREMI

	//CEK STATUS MINIMUM PREMI SEMUA PRODUK

	$premiextra = ROUND($premi * $cek_extrapremi / 100);				//HITUNG EXTRA PREMI
	$diskonpremi = $premi * $admpolis['discount'] /100;			//HITUNG DISKON
	$tpremi = $premi - $diskonpremi;							//HITUNG PREMI DENGAN DISKON

	$mettotal_ = $tpremi + $premiextra + $admpolis['adminfee'];	//HITUNG TOTAL



	//CEK STATUS MINIMUM PREMI SEMUA PRODUK
	if ($mettotal_ < $admpolis['min_premium']) {
		$mettotal = $admpolis['min_premium'];
		$cmp = $admpolis['min_premium'] - $mettotal_;
	}else{
		$mettotal = $mettotal_;
		$cmp = 0;
	}
	//CEK STATUS MINIMUM PREMI SEMUA PRODUK

	//VALIDASI TABEL MEDICAL STATUS MEDIK
	$medik = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_medical WHERE id_cost="'.$r['id_cost'].'" AND  id_polis="'.$r['id_polis'].'" AND '.$r['usia'].' BETWEEN age_from AND age_to AND '.$r['kredit_jumlah'].' BETWEEN si_from AND si_to'));
	$status_medik =$medik['type_medical'];
	if ($status_medik=="NM" OR $status_medik=="FCL" OR $status_medik=="SPD" OR $status_medik=="SPK")
	{	$status_pesertanya = "Approve";	}else{	$status_pesertanya = "Pending";	}
	//VALIDASI TABEL MEDICAL STATUS MEDIK

	$formattgl = explode("/", $r['kredit_tgl']);		$formattgl1 = $formattgl[2].'-'.$formattgl[1].'-'.$formattgl[0];		// SET FORMAT TANGGAL
	$cekpesertaID = mysql_fetch_array($database->doQuery('SELECT id FROM fu_ajk_peserta ORDER BY id DESC'));					// SET ID PESERTA
	$idnya = 10000000000 + $cekpesertaID['id'] + 1; $idnya2 = substr($idnya, 1);													// SET ID PESERTA
	$mameto = $database->doQuery('INSERT INTO fu_ajk_peserta SET id_cost="'.$r['id_cost'].'",
															 id_polis="'.$r['id_polis'].'",
															 namafile="'.$r['namafile'].'",
															 no_urut="'.$r['no_urut'].'",
															 spaj="'.$r['spaj'].'",
															 type_data="'.$r['type_data'].'",
															 id_peserta="'.$idnya2.'",
															 nama_mitra="'.$r['nama_mitra'].'",
															 nama="'.$r['nama'].'",
															 gender="'.$r['gender'].'",
															 tgl_lahir="'.$r['tgl_lahir'].'",
															 usia="'.$r['usia'].'",
															 kredit_tgl="'.$r['kredit_tgl'].'",
															 kredit_jumlah="'.$r['kredit_jumlah'].'",
															 kredit_tenor="'.$r['kredit_tenor'].'",
															 kredit_akhir="'.$tgl_akhir_kredit.'",
															 premi="'.$premi.'",
															 disc_premi="'.$diskonpremi.'",
															 bunga="",
															 biaya_adm="'.$admpolis['adminfee'].'",
															 ext_premi="'.$premiextra.'",
															 cmp="'.$cmp.'",
															 totalpremi="'.$mettotal.'",
															 badant="",
															 badanb="",
															 status_medik="'.$status_medik.'",
															 status_bayar="0",
															 status_aktif="'.$status_pesertanya.'",
															 regional="'.$r['regional'].'",
															 area="'.$r['area'].'",
															 cabang="'.$r['cabang'].'",
															 input_by ="'.$r['input_by'].'",
															 input_time ="'.$r['input_time'].'",
															 approve_by ="'.$q['nm_user'].'",
															 approve_time ="'.$futgl.'"');

$metdel = $database->doQuery('DELETE FROM fu_ajk_peserta_tempf WHERE id_cost="'.$vall[0].'" AND id_polis="'.$vall[1].'" AND nama="'.$vall[2].'" AND tgl_lahir="'.$vall[3].'" AND kredit_jumlah="'.$vall[4].'" AND status_aktif ="Upload"');

	}

	$mail = new PHPMailer; // call the class
	$mail->IsSMTP();
	$mail->Host = SMTP_HOST; //Hostname of the mail server
	$mail->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
	$mail->Username = SMTP_UNAME; //Username for SMTP authentication any valid email created in your domain
	$mail->Password = SMTP_PWORD; //Password for SMTP authentication
	$mail->AddReplyTo = SMTP_REPLYMAIL; //reply-to address
	$mail->debug = 1;
	$mail->SMTPSecure = "ssl";
	$mail->SetFrom ($q['email'], $q['nm_lengkap']); //From address of the mail
	$mail->Subject = "AJKOnline - APPROVE PESERTA BARU SPK AJK ONLINE"; //Subject od your mail
	//EMAIL PENERIMA KANTOR U/W
	$mailsupervisorajk = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="" AND status="UNDERWRITING"');
	while ($_mailsupervisorajk = mysql_fetch_array($mailsupervisorajk)) {
		$mail->AddAddress($_mailsupervisorajk['email'], $_mailsupervisorajk['nm_lengkap']); //To address who will receive this email
	}
	//EMAIL PENERIMA KANTOR U/W
	//EMAIL PENERIMA CLIENT

	$mailsupervisorclient = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="'.$vall[0].'" AND id_polis="'.$vall[1].'" AND wilayah="'.$q['wilayah'].'" AND email !=""');
	while ($_mailsupervisorclient = mysql_fetch_array($mailsupervisorclient)) {
		$mail->AddAddress($_mailsupervisorclient['email'], $_mailsupervisorclient['nm_lengkap']); //To address who will receive this email
	}
	//EMAIL PENERIMA CLIENT

$message .='<table border="1" width="100%">
		   <tr bgcolor="gray"><td width="1%" align="center">No</td>
		   	   <td align="center" width="10%">SPK</td>
		   	   <td align="center" width="10%">IDPeserta</td>
		   	   <td align="center">Nama</td>
		   	   <td align="center" width="10%">Tgl Lahir</td>
		   	   <td align="center" width="1%">Usia</td>
		   	   <td align="center" width="10%">Awal Kredit</td>
		   	   <td align="center" width="1%">Tenor</td>
		   	   <td align="center" width="10%">Akhir Kredit</td>
		   	   <td align="center" width="10%">Plafond</td>
		   	   <td align="center" width="10%">Status</td>
		   	   <td align="center" width="15%">Cabang</td>
		   </tr>';
$met_appSPV = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE approve_by="'.$q['nm_user'].'" AND approve_time="'.$futgl.'"');
while ($met_appSPV_ = mysql_fetch_array($met_appSPV)) {
$message .='<tr><th>'.++$no.'</th>
		   	   <th align="center">'.$met_appSPV_['spaj'].'</th>
		   	   <th align="center">'.$met_appSPV_['id_peserta'].'</th>
		   	   <th>'.$met_appSPV_['nama'].'</th>
		   	   <th align="center">'.$met_appSPV_['tgl_lahir'].'</th>
		   	   <th align="center">'.$met_appSPV_['usia'].'</th>
		   	   <th align="center">'.$met_appSPV_['kredit_tgl'].'</th>
		   	   <th align="center">'.$met_appSPV_['kredit_tenor'].'</th>
		   	   <th align="center">'.$met_appSPV_['kredit_akhir'].'</th>
		  	   <th align="right">'.duit($met_appSPV_['kredit_jumlah']).'</th>
		  	   <th align="center">'.$met_appSPV_['status_aktif'].'</th>
		  	   <th>'.$met_appSPV_['cabang'].'</td>
		   </tr>';
}
$message .='</table>';
//echo $message;
	$mail->AddCC("penting_ga@hotmail.com, sysdev@kode.web.id, arief@arief.kurniawan.com, gunarso@adonai.co.id");
	$mail->MsgHTML('<table><tr><th>Data peserta baru SPAJ telah di approve oleh <b>'.$_SESSION['nm_user'].' selaku Supervisor AJK-Online pada tanggal '.$futgl.'</tr></table>'.$message); //Put your body of the message you can place html code here
	$send = $mail->Send(); //Send the mails

	echo '<center>Approve oleh <b>'.$_SESSION['nm_user'].'</b> telah berhasil.<br /> <a href="ajk_val_upl.php?v=spaj">Kembali Ke Halaman Utama</a></center>';
}
	;
	break;

case "deldata":
		$met = $database->doQuery('DELETE FROM fu_ajk_peserta_tempf WHERE id_cost="'.$_REQUEST['id_cost'].'" AND
																  	 	  id_polis="'.$_REQUEST['id_polis'].'" AND
																		  namafile="'.$_REQUEST['namafile'].'" AND
																		  no_urut="'.$_REQUEST['no_urut'].'" AND
																  		  spaj="'.$_REQUEST['spaj'].'" AND
																  		  nama="'.$_REQUEST['nama'].'" AND
																  		  kredit_jumlah="'.$_REQUEST['kredit_jumlah'].'"');
		if ($_REQUEST['type']=="spaj") {	header("location:ajk_val_upl.php?v=spaj");	}	else	{	header("location:ajk_val_upl.php?v=fl_spk");	}
	;
	break;

case "fl_spk":
echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="100%" align="left">Modul Validasi Upload Data SPK</font></th></tr></table>';
	$metcost = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$q['id_cost'].'"'));
	$metprod = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$q['id_polis'].'"'));
echo '<fieldset style="padding: 1">
		<legend align="center">S e a r c h</legend>
		<table border="0" width="40%" cellpadding="0" cellspacing="0">
		<tr><td width="15%">Nama Perusahaan</td><td width="30%">: '.$metcost['name'].'</td></tr>
		<tr><td width="10%">Nama Produk</td><td width="20%">: '.$metprod['nmproduk'].'</td></tr>
		</table></fieldset>';

echo '<form method="post" action="ajk_val_upl.php?v=approvespk" onload ="onbeforeunload">
		  <table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#bde0e6">
		<tr><th width="1%"></th>
			<th width="1%"><input type="checkbox" id="selectall"/></th>
			<th width="1%">No</th>
			<th width="5%">SPK</th>
			<th>Nama Tertanggung</th>
			<th width="8%">Tanggal Lahir</th>
			<th width="5%">Usia</th>
			<th width="5%">Uang Asuransi</th>
			<th width="8%">Mulai Asuransi</th>
			<th width="5%">Tenor</th>
			<th width="5%">Ext.Premi</th>
			<th width="8%">Cabang</th>
			<th width="8%">Area</th>
			<th width="8%">Regional</th>
			<th width="5%">User</th>
			</tr>';
$data = $database->doQuery('SELECT * FROM fu_ajk_peserta_tempf WHERE nama !="" AND id_cost="'.$q['id_cost'].'" AND id_polis="'.$q['id_polis'].'" AND type_data="SPK" AND status_aktif="Upload" AND input_by="'.$q['nm_user'].'" ORDER BY input_time ASC');
while ($fudata = mysql_fetch_array($data)) {//echo $umur;

//FORMAT BARU USIA
$mets = datediff($fudata['kredit_tgl'], $fudata['tgl_lahir']);
$metTgl = explode(",",$mets);

if ($metTgl[1] >= 6 ) {	$umur = $metTgl[0] + 1;	}else{	$umur = $metTgl[0];	}
if ($metTgl[1] == 5) {	$sisahari = 30 - $metTgl[2];	$sisathn = $metTgl[0] + 1; $blnnnya ='Dalam '.$sisahari.' hari usia akan bertambah menjadi '.$sisathn.'';	}else{	$blnnnya ='';	}
//echo $mets['months'].' '.$mets['days'].' | '.$blnnnya.'<br />';


$cekextpremi = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak WHERE id_cost="'.$fudata['id_cost'].'" AND spak="'.$fudata['spaj'].'" AND status="Aktif"'));		//CEK DATA SPK
$dataceklist = '<input type="checkbox" class="case" name="nama[]" value="'.$fudata['id_cost'].'-met-'.$fudata['id_polis'].'-met-'.$fudata['nama'].'-met-'.$fudata['tgl_lahir'].'-met-'.$fudata['kredit_jumlah'].'">';
if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		  <td align="center"><a href="ajk_val_upl.php?v=deldata&id_cost='.$fudata['id_cost'].'&id_polis='.$fudata['id_polis'].'&namafile='.$fudata['namafile'].'&no_urut='.$fudata['no_urut'].'&spaj='.$fudata['spaj'].'&nama='.$fudata['nama'].'&kredit_jumlah='.$fudata['kredit_jumlah'].'" onClick="if(confirm(\'Apakah anda yakin untuk menghapus data peserta ini ?\')){return true;}{return false;}"><img src="image/delete.gif"></a></td>
		  <td align="center">'.$dataceklist.'
		  </td>
		  <td align="center">'.++$no.'</td>
		  <td align="center">'.$fudata['spaj'].'</td>
		  <td>'.$fudata['nama'].'<br /><font color="blue">'.$blnnnya.'</font></td>
		  <td align="center">'._convertDate($fudata['tgl_lahir']).'</td>
		  <td align="center">'.$umur.'</td>
		  <td align="right">'.duit($fudata['kredit_jumlah']).'</td>
		  <td align="center">'._convertDate($fudata['kredit_tgl']).'</td>
		  <td align="center">'.$fudata['kredit_tenor'].'</td>
		  <td align="center">'.duit($cekextpremi['ext_premi']).'%</td>
		  <td align="center">'.$fudata['cabang'].'</td>
		  <td align="center">'.$fudata['area'].'</td>
		  <td align="center">'.$fudata['regional'].'</td>
		  <td align="center"><font color="blue"><b>'.$fudata['input_by'].'</b></font></td>
		  </tr>';
}

if ($q['level']=="99" AND $q['status']=="") {
	$el = $database->doQuery('SELECT * FROM fu_ajk_peserta_tempf WHERE id_cost="'.$q['id_cost'].'" AND id_polis="'.$q['id_polis'].'" AND type_data="SPK" AND status_aktif="Upload" ');
	$met = mysql_num_rows($el);
	//if ($met > 0) {	echo '<tr><td colspan="27" align="center"><a href="ajk_uploader_peserta.php?r=approve&val=pclaim&id_cost='.$_REQUEST['cat'].'&id_polis='.$_REQUEST['subcat'].'" onClick="if(confirm(\'Apakah anda sudah yakin dengan semua data peserta ini ?\')){return true;}{return false;}">Approve</a></td></tr>';
	if ($met > 0) {	echo '<tr><td colspan="27" align="center"><a href="ajk_val_upl.php?v=approvespk" onClick="if(confirm(\'Apakah anda sudah yakin dengan semua data peserta SPK ini ?\')){return true;}{return false;}"><input type="submit" name="ApproveClaim" Value="Approve"></a></td></tr>';
		//}else{	echo '<tr><th colspan="27" align="center"><blink><b><font color="blue">Tidak ada data peserta restruktur, topup atau refund yang harus di validasi. !!!</font></b></blink></th></tr>';
	}else{	echo '';	}
}else{	}
echo '</table>';
	;
	break;

case "approvespk":
echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="100%" align="left">Modul Validasi Upload Data SPK</font></th></tr></table>';
if (!$_REQUEST['nama']) {
echo '<center><font color=red><blink>Tidak ada data yang di pilih, silahkan ceklist data yang akan di Approve. !</blink></font><br/>
	  <a href="ajk_val_upl.php?v=fl_spk">Kembali Ke Halaman Approve Peserta</a></center>';
}else{
foreach($_REQUEST['nama'] as $k => $val){
	$vall = explode("-met-", $val);		//EXPLODE DATA BERDASARKAN CHEKLIST//
	$r = $database->doQuery('SELECT * FROM fu_ajk_peserta_tempf WHERE id_cost="'.$vall[0].'" AND id_polis="'.$vall[1].'" AND nama="'.$vall[2].'" AND tgl_lahir="'.$vall[3].'" AND kredit_jumlah="'.$vall[4].'" AND status_aktif="Upload"');
while ($rr = mysql_fetch_array($r)) {
	//BIAYA POLIS ADMIN
	$admpolis = mysql_fetch_array($database->doQuery('SELECT id_cost, adminfee, day_kredit, discount, singlerate FROM fu_ajk_polis WHERE id_cost="'.$rr['id_cost'].'" AND id="'.$rr['id_polis'].'"'));

	//$umur = ceil(((strtotime($rr['kredit_tgl']) - strtotime($rr['tgl_lahir'])) / (60*60*24*365.2425)));	// FORMULA USIA
	// FORMULA USIA
	$mets = datediff($rr['kredit_tgl'], $rr['tgl_lahir']);
	$metTgl = explode(",",$mets);
//	echo $mets['years'].' Tahun '.$mets['months'].' Bulan '.$mets['days'].' Hari';
//	if ($mets['months'] >= 5 ) {	$umur = $mets['years'] + 1;	}else{	$umur = $mets['years'];	}	DIRUBAH KEMBALI KE ENAM BULAN 150213
	if ($metTgl[1] >= 6 ) {	$umur = $metTgl[0] + 1;	}else{	$umur = $metTgl[0];	}
	// FORMULA USIA

	//MENENTUKAN PERHITUNGAN PREMI BERDASARKAN SINGLE RATE PADA POLIS
	if ($admpolis['singlerate']=="T") {
		$tgl_akhir_kredit = date('Y-m-d',strtotime($rr['kredit_tgl']."+".$rr['kredit_tenor']." Month"."-".$admpolis['day_kredit']." day"));	//KREDIT AKHIR
		$cekrate = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$rr['id_cost'].'" AND id_polis="'.$rr['id_polis'].'" AND tenor="'.$rr['kredit_tenor'].'" AND status="baru"'));		// RATE PREMI
	}else{
		$metAkhirBulan = $rr['kredit_tenor'] * 12;
		$tgl_akhir_kredit = date('Y-m-d',strtotime($rr['kredit_tgl']."+".$metAkhirBulan ." Month"."-".$admpolis['day_kredit']." day"));	//KREDIT AKHIR

		$mettenornya = $rr['kredit_tenor'];
		$cekrate = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$rr['id_cost'].'" AND id_polis="'.$rr['id_polis'].'" AND usia="'.$umur.'" AND tenor="'.$mettenornya.'" AND status="baru"'));		// RATE PREMI
	}
	//MENENTUKAN PERHITUNGAN PREMI BERDASARKAN SINGLE RATE PADA POLIS
	$premi = ROUND(($cekrate['rate'] / 1000) * $rr['kredit_jumlah']);
	//CEK EXTRA PREMI
	$cekextpremi = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak WHERE id_cost="'.$rr['id_cost'].'" AND id_polis="'.$rr['	id_polis'].'" AND spak="'.$rr['spaj'].'" AND status!="Batal"'));		//CEK DATA SPK
	if ($cekextpremi['ext_premi']=="") {	$expremi = '';	}else{	$expremi = $cekextpremi['ext_premi'];	}

	$extrapremi = ROUND($premi * ($expremi / 100));
	$diskonpremi = $premi * $admpolis['discount'] /100;			//DISKON PREMI
	$tpremi = $premi - $diskonpremi;							//TOTAL PREMI

	$mettotal_ = $tpremi + $extrapremi + $admpolis['adminfee'];															//TOTAL

	//CEK STATUS MINIMUM PREMI SEMUA PRODUK
	if ($mettotal_ < $admpolis['min_premium']) {
		$mettotal = $admpolis['min_premium'];
		$cmp = $admpolis['min_premium'] - $mettotal_;
	}else{
		$mettotal = $mettotal_;
		$cmp = 0;
	}
	//CEK STATUS MINIMUM PREMI SEMUA PRODUK

	$formattgl = explode("/", $rr['kredit_tgl']);		$formattgl1 = $formattgl[2].'-'.$formattgl[1].'-'.$formattgl[0];		// SET FORMAT TANGGAL
	$cekpesertaID = mysql_fetch_array($database->doQuery('SELECT id FROM fu_ajk_peserta ORDER BY id DESC'));					// SET ID PESERTA
	$idnya = 100000000 + $cekpesertaID['id'] + 1; $idnya2 = substr($idnya, 1);													// SET ID PESERTA
$mameto = $database->doQuery('INSERT INTO fu_ajk_peserta SET id_cost="'.$rr['id_cost'].'",
															 id_polis="'.$rr['id_polis'].'",
															 namafile="'.$rr['namafile'].'",
															 no_urut="'.$rr['no_urut'].'",
															 spaj="'.$rr['spaj'].'",
															 type_data="'.$rr['type_data'].'",
															 id_peserta="'.$idnya2.'",
															 nama_mitra="'.$rr['nama_mitra'].'",
															 nama="'.$rr['nama'].'",
															 gender="'.$rr['gender'].'",
															 tgl_lahir="'.$rr['tgl_lahir'].'",
															 usia="'.$umur.'",
															 kredit_tgl="'.$rr['kredit_tgl'].'",
															 kredit_jumlah="'.$rr['kredit_jumlah'].'",
															 kredit_tenor="'.$rr['kredit_tenor'].'",
															 kredit_akhir="'.$tgl_akhir_kredit.'",
															 premi="'.$premi.'",
															 disc_premi="'.$diskonpremi.'",
															 bunga="",
															 biaya_adm="'.$admpolis['adminfee'].'",
															 ext_premi="'.$extrapremi.'",
															 totalpremi="'.$mettotal.'",
															 badant="",
															 badanb="",
															 status_medik="NM",
															 status_bayar="0",
															 status_aktif="Approve",
															 regional="'.$rr['regional'].'",
															 area="'.$rr['area'].'",
															 cabang="'.$rr['cabang'].'",
															 input_by ="'.$rr['input_by'].'",
															 input_time ="'.$rr['input_time'].'",
															 approve_by ="'.$q['nm_user'].'",
															 approve_time ="'.$futgl.'"');

}
	$metdel = $database->doQuery('DELETE FROM fu_ajk_peserta_tempf WHERE id_cost="'.$vall[0].'" AND id_polis="'.$vall[1].'" AND nama="'.$vall[2].'" AND tgl_lahir="'.$vall[3].'" AND kredit_jumlah="'.$vall[4].'" AND status_aktif ="Upload"');
}
	$pecahtgl = explode(" ", $futgl);	$pecahlagi = explode("-", $pecahtgl[0]);	$tglnya = $pecahlagi[2].'-'.$pecahlagi[1].'-'.$pecahlagi[0].' '.$pecahtgl[1];				//PECAH TANGGAL
/* SMTP MAIL */
	$mail = new PHPMailer;
	$mail->IsSMTP();
	$mail->Host = SMTP_HOST; //Hostname of the mail server
	$mail->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
	$mail->Username = SMTP_UNAME; //Username for SMTP authentication any valid email created in your domain
	$mail->Password = SMTP_PWORD; //Password for SMTP authentication
	$mail->AddReplyTo = SMTP_REPLYMAIL; //reply-to address
	$mail->debug = 1;
	$mail->SMTPSecure = "ssl";
	$mail->SetFrom ($q['email'], $q['nm_lengkap']);
	$mail->Subject = "AJKOnline - APPROVE PESERTA BARU SPK AJK ONLINE";
	//EMAIL PENERIMA KANTOR U/W
	$mailsupervisorajk = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="" AND status="UNDERWRITING"');
while ($_mailsupervisorajk = mysql_fetch_array($mailsupervisorajk)) {
	$mail->AddAddress($_mailsupervisorajk['email'], $_mailsupervisorajk['nm_lengkap']); //To address who will receive this email
}
	//EMAIL PENERIMA KANTOR U/W
	//EMAIL PENERIMA CLIENT

	$mailsupervisorclient = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="'.$vall[0].'" AND level="'.$q['level'].'" AND wilayah="'.$q['wilayah'].'" AND email !=""');
while ($_mailsupervisorclient = mysql_fetch_array($mailsupervisorclient)) {
	$mail->AddAddress($_mailsupervisorclient['email'], $_mailsupervisorclient['nm_lengkap']); //To address who will receive this email
}
	//EMAIL PENERIMA CLIENT

	$mail->AddCC("penting_ga@hotmail.com, sysdev@kode.web.id, arief@arief.kurniawan.com, gunarso@adonai.co.id");
	$mail->MsgHTML('<table><tr><th>Data peserta baru SPK telah di approve oleh <b>'.$_SESSION['nm_user'].' selaku Supervisor AJK-Online pada tanggal '.$futgl.'</tr></table>'.$message); //Put your body of the message you can place html code here
	$send = $mail->Send(); //Send the mails

	echo '<center>Approve data SPK oleh <b>'.$_SESSION['nm_user'].'</b> telah berhasil, segera dibuat pencetakan nomor DN.<br /> <a href="ajk_val_upl.php?v=fl_spk">Kembali Ke Halaman Utama</a></center>';

}
		;
		break;

	default:
		;
} // switch
/*
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
		loadingImage:\'loader1.gif\',
		loadingText:\'Loading...\',
		debug:0
	} )
});
</script>';
*/
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
