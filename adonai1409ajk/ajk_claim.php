<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("../includes/functions.php");
connect();
if (isset($_SESSION['nm_user'])) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));	}
$futgl = date("Y-m-d G:i:s");
$futgliddn = date("Y");
$futgldn = date("Y-m-d");
switch ($_REQUEST['d']) {
	case "del_dok":
		$met_cek_dokumen = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_klaim_doc WHERE id="' . $_REQUEST['id_dok'] . '"'));
		unlink($dok_klaim_ajk . $met_cek_dokumen['nama_dokumen']);
		$met_del_dokumen = $database->doQuery('UPDATE fu_ajk_klaim_doc SET nama_dokumen=NULL, update_by="' . $q['nm_lengkap'] . '", update_date="' . $futgl . '" WHERE id="' . $_REQUEST['id_dok'] . '"');
		echo '<center><h2>Data Klaim meninggal telah dihapus oleh ' . $q['nm_lengkap'] . ' pada tanggal ' . $futgl . '.</h2></center><meta http-equiv="refresh" content="2;URL=ajk_claim.php?d=wklaim&id=' . $_REQUEST['idp'] . '">';;
	break;

	case "upl_dok":
		echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
				<tr><th colspan="8" width="100%" align="left" colspan="2">Modul Klaim - Upload Dokumen Klaim Meninggal</font></th><th width="5%" colspan="2"><a href="ajk_claim.php"><img src="image/back.png" width="20"></a></th></tr>
				</table><br />';
		$metDokumen = mysql_fetch_array($database->doQuery('SELECT
		fu_ajk_cn.id,
		fu_ajk_cn.id_cn,
		fu_ajk_cn.id_dn,
		fu_ajk_cn.id_cost,
		fu_ajk_cn.id_nopol,
		fu_ajk_cn.id_peserta,
		fu_ajk_cn.id_regional,
		fu_ajk_cn.id_cabang,
		fu_ajk_cn.tgl_createcn,
		fu_ajk_cn.total_claim,
		fu_ajk_cn.nmpenyakit,
		fu_ajk_peserta.nama,
		fu_ajk_costumer.`name`,
		fu_ajk_polis.nmproduk,
		fu_ajk_dn.dn_kode
		FROM fu_ajk_cn
		INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
		INNER JOIN fu_ajk_costumer ON fu_ajk_cn.id_cost = fu_ajk_costumer.id
		INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
		INNER JOIN fu_ajk_dn ON fu_ajk_cn.id = fu_ajk_dn.id
		WHERE fu_ajk_cn.id="' . $_REQUEST['id'] . '"'));
		$met_penyakit = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_namapenyakit WHERE id="' . $metDokumen['nmpenyakit'] . '"'));
		echo '<table border="0" cellpadding="1" cellspacing="1" width="100%">
			  <tr><td width="15%">Nama Perusahaan</td><td>: ' . $metDokumen['name'] . '</td></tr>
			  <tr><td>Produk</td><td>: ' . $metDokumen['nmproduk'] . '</td></tr>
			  <tr><td>ID Peserta</td><td>: ' . $metDokumen['id_peserta'] . '</td></tr>
			  <tr><td>Nama</td><td>: ' . $metDokumen['nama'] . '</td></tr>
			  <tr><td>Debitnote</td><td>: ' . $metDokumen['dn_kode'] . '</td></tr>
			  <tr><td>Creditnote</td><td>: ' . $metDokumen['id_cn'] . '</td></tr>
			  <tr><td>Tempat Meninggal</td><td>: ' . $metDokumen['tempat_meninggal'] . '</td></tr>
			  <tr><td>Penyebab Meninggal</td><td>: ' . $met_penyakit['namapenyakit'] . '</td></tr>
			  </table>';

				$cek_dokumen = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_klaim_doc WHERE id_cost="' . $metDokumen['id_cost'] . '" AND id_pes="' . $metDokumen['id_peserta'] . '" AND id_klaim="' . $metDokumen['id'] . '"'));
		//TEMPAT MENINGGAL

				if ($metDokumen['tempat_meninggal'] != "Rumah Sakit") {
					$dok_Defult = $database->doQuery('SELECT * FROM fu_ajk_dokumenklaim WHERE nama_dok LIKE "%Kematian dari Rumah Sakit%"');
					while ($dok_Defult_ = mysql_fetch_array($dok_Defult)) {
						$cekDokRS = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_klaim_doc WHERE id_pes="' . $metDokumen['id_peserta'] . '" AND
																							   id_dn="' . $metDokumen['id_dn'] . '" AND
																							   id_klaim="' . $metDokumen['id'] . '" AND
																							   dokumen="' . $dok_Defult_['id'] . '" AND
																							   nama_dokumen="Non-Dokumen"'));
						if ($cekDokRS['dokumen']) {
						} else {
							$tambahdokRS = $database->doQuery('INSERT INTO fu_ajk_klaim_doc SET id_cost="' . $metDokumen['id_cost'] . '",
																				  id_pes="' . $metDokumen['id_peserta'] . '",
																				  id_dn="' . $metDokumen['id_dn'] . '",
																				  id_klaim="' . $metDokumen['id'] . '",
																				  dokumen="' . $dok_Defult_['id'] . '",
																				  nama_dokumen="Non-Dokumen"');
							//echo $dok_Defult['id'].'<br /><br />';
						}
					}
				}
		//TEMPAT MENINGGAL

		//TEMPAT MENINGGAL
				if ($metDokumen['sebab_meninggal'] != "Kecelakaan") {
					$dok_DefultPA = $database->doQuery('SELECT * FROM fu_ajk_dokumenklaim WHERE nama_dok LIKE "%karena Kecelakaan%"');
					while ($dok_DefultPA_ = mysql_fetch_array($dok_DefultPA)) {
						$cekDokAccident = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_klaim_doc WHERE id_pes="' . $metDokumen['id_peserta'] . '" AND id_dn="' . $metDokumen['id_dn'] . '" AND id_klaim="' . $metDokumen['id'] . '" AND dokumen="' . $dok_DefultPA_['id'] . '" AND nama_dokumen="Non-Dokumen"'));
						if ($cekDokAccident['dokumen']) {
						} else {
							$tambahdokAC = $database->doQuery('INSERT INTO fu_ajk_klaim_doc SET id_cost="' . $metDokumen['id_cost'] . '",
																				  id_pes="' . $metDokumen['id_peserta'] . '",
																				  id_dn="' . $metDokumen['id_dn'] . '",
																				  id_klaim="' . $metDokumen['id'] . '",
																				  dokumen="' . $dok_DefultPA_['id'] . '",
																				  nama_dokumen="Non-Dokumen"');
							//echo $dok_DefultPA_['id'].'<br /><br />';
						}
					}
				}
		//TEMPAT MENINGGAL


		//INSERT DOKUMEN KLAIM
				if ($_REQUEST['el'] == "upload_spk") {
					for ($i = 0; $i < count($_POST["no_dok"]); $i++) {
						if ($_FILES['userfile']['name'][$i] == "") {
							$errno[$i] = "Silahkan upload file dokumen klaim <font color=red><b>" . $metdoknya['dokumen'] . "!</b></font><br />";
						}
						if ($_FILES['userfile']['name'][$i] != "" AND $_FILES['userfile']['type'][$i] != "application/pdf") {
							$errno[$i] = "<font color=red>File " . $metdoknya['dokumen'] . " harus Format PDF !</font><br />";
						}
						if ($_FILES['userfile']['size'][$i] / 1024 > $met_ttdsize) {
							$errno[$i] = "<font color=red>File " . $metdoknya['dokumen'] . " tidak boleh lebih dari 1Mb !</font><br />";
						}
						if ($errno[$i]) {
							echo '' . $errno[$i] . '';
						} else {
							$_cekDok = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_klaim_doc WHERE id_cost="' . $metDokumen['id_cost'] . '" AND id_pes="' . $metDokumen['id_peserta'] . '" AND dokumen="' . $_POST["no_dok"][$i] . '"'));
							//echo $_POST["no_dok"][$i].' - '.$_FILES['userfile']['name'][$i].'<br />';

							$met_upload_data = $database->doQUery('UPDATE fu_ajk_klaim_doc SET nama_dokumen="' . $metDokumen['id_peserta'] . '_' . $metDokumen['nama'] . '_' . $_FILES['userfile']['name'][$i] . '", update_by="' . $q['nm_lengkap'] . '", update_date="' . $futgl . '" WHERE id="' . $_cekDok['id'] . '"');
							move_uploaded_file($_FILES['userfile']['tmp_name'][$i], $dok_klaim_ajk . $metDokumen['id_peserta'] . '_' . $metDokumen['nama'] . '_' . $_FILES["userfile"]["name"][$i]);
						}
					}
					echo '<center><h2>Data dokumen klaim meninggal telah di proses.</h2></center><meta http-equiv="refresh" content="3;URL=ajk_claim.php?d=upl_dok&id=' . $_REQUEST['id'] . '">';
				}
		//INSERT DOKUMEN KLAIM
		if ($_REQUEST['delc']=="delcekdok") {
			$metUncDoc = $database->doQuery('UPDATE fu_ajk_klaim_doc SET del ="1" WHERE id="'.$_REQUEST['idcdok'].'"');
			header("location:ajk_claim.php?d=upl_dok&id=".$_REQUEST['id']."");
		}
		echo '<form name="f1" method="post" enctype="multipart/form-data" action="">
			  <table border="0" cellpadding="1" cellspacing="1" width="100%">
			  <tr><th width="1%">No</th><th>Nama Dokumen</th><th width="30%">Upload File</th><th width="1%">Hapus</th></tr>';
				$metdokumen__ = $database->doQuery('SELECT * FROM fu_ajk_dokumenklaim_bank WHERE id_bank="' . $metDokumen['id_cost'] . '" AND id_produk="' . $metDokumen['id_nopol'] . '" AND del IS NULL ORDER BY id ASC');
		while ($rdok = mysql_fetch_array($metdokumen__)) {
		$metdokumenklaim = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dokumenklaim WHERE id="' . $rdok['id_dok'] . '" AND del IS NULL'));
		$CekDokumen = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_klaim_doc WHERE id_pes="' . $metDokumen['id_peserta'] . '" AND id_cost="' . $rdok['id_bank'] . '" AND dokumen="' . $rdok['id'] . '" AND del IS NULL'));
		if (($no % 2) == 1) $objlass = 'tbl-odd'; else    $objlass = 'tbl-even';
		echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
			  <td align="center">' . ++$no . '</td>';
			if (!$CekDokumen['dokumen']) {
			echo '<td>' . $metdokumenklaim['nama_dok'] . '</td>
			  	<td align="right"><font color="red">Dokumen tidak dipilih</font></td>';
			} elseif ($CekDokumen['dokumen'] AND $CekDokumen['nama_dokumen'] != "") {
			echo '<td>' . $metdokumenklaim['nama_dok'] . '</td>
				  <td>' . $CekDokumen['nama_dokumen'] . '</td>';
			} else {
			echo '<td>' . $metdokumenklaim['nama_dok'] . ' <font color="red">*</font>' . $errno[$i] . '</td>
				  <td><input type="hidden" name="no_dok[]" value="' . $metdokumenklaim['id'] . '" size="10"> <input name="userfile[]" type="file" size="50" onchange="checkfile(this);" ></td>
			  	<td align="center"><a href="ajk_claim.php?d=upl_dok&delc=delcekdok&id='.$_REQUEST['id'].'&idcdok='.$CekDokumen['id'].'" title="hapus cheklist dokumen klaim"><img src="image/deleted.png" width="20"></a></td>';
			}
			echo '</tr>';
		}
		echo '<tr><td colspan="3" align="center"><input type="hidden" name="el" value="upload_spk"><input name="upload" type="submit" value="Upload File"></td></tr>
			  </table></form>';
				;
	break;

	case "deldoc":

		echo '<table border="0" cellpadding="3" cellspacing="0" width="100%"><tr><th colspan="8" width="100%" align="left" colspan="2">Modul Klaim - Hapus Dokumen Claim</font></th><th width="5%" colspan="2"><a href="ajk_claim.php"><img src="image/Backward-64.png" width="20"></a></th></tr></table><br />';
		$metdoc = mysql_fetch_array($database->doQUery('SELECT * FROM fu_ajk_cn WHERE id="' . $_REQUEST['id'] . '"'));
		$metcost = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="' . $metdoc['id_cost'] . '"'));
		$metpol = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id_cost="' . $metdoc['id_cost'] . '" AND id="' . $metdoc['id_nopol'] . '"'));
		$metpeserta = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_cost="' . $metdoc['id_cost'] . '" AND id_peserta="' . $metdoc['id_peserta'] . '"'));
		echo '<form method="post" action="">
	  <table border="0" cellpadding="3" cellspacing="1" width="70%" align="center" style="border: solid 1px #DEDEDE" align="center">
	  <tr><td width="15%">Costumer</td><td>: ' . $metcost['name'] . '</td></tr>
	  <tr><td>Polis</td><td>: ' . $metpol['nopol'] . '</td></tr>
	  <tr><td>Nama</td><td>: ' . $metpeserta['nama'] . '</td></tr>
	  </table>
	  <table border="0" cellpadding="3" cellspacing="1" width="70%" align="center" style="border: solid 1px #DEDEDE" align="center">
	  <tr><th>No</th><th>Dokumen</th><th>Option</th></tr>';
		$metdokumen = $database->doQuery('SELECT * FROM fu_ajk_klaim_dokumen WHERE id_cost="' . $metdoc['id_cost'] . '" ORDER BY id ASC');
		while ($rdok = mysql_fetch_array($metdokumen)) {
			$cekdoknya = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_klaim_doc WHERE id_cost="' . $metdoc['id_cost'] . '" AND dokumen="' . $rdok['id'] . '" AND id_pes="' . $metdoc['id_peserta'] . '"'));
			if (($no % 2) == 1) $objlass = 'tbl-odd'; else    $objlass = 'tbl-even';
			echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		<td align="center">' . ++$no . '</td>
		<td>' . $rdok['dokumen'] . '</td>
		<td align="center"><a href=""><img src="image/DelFile.png"></a></td></tr>';
		}
		echo '<tr><td colspan="4" align="center"><input type="submit" name="del_doc" value="Hapus"></td></tr>
	  </table></form>';;
	break;

	case "editklaim":
		echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
		<tr><th colspan="8" width="100%" align="left" colspan="2">Modul Klaim - Edit Data Meninggal</font></th><th width="5%" colspan="2"><a href="ajk_claim.php"><img src="image/back.png" width="20"></a></th></tr>
		</table><br />';
		/*
        $edklaim = mysql_fetch_array($database->doQuery('SELECT
        fu_ajk_cn.id AS id,
        fu_ajk_cn.id_peserta AS idp,
        fu_ajk_cn.id_dn AS dnnya,
        fu_ajk_cn.id_cn AS cnnya,
        fu_ajk_cn.tgl_byr_claim AS tglbyrklaim,
        fu_ajk_polis.id AS idpolis,
        fu_ajk_polis.id_cost AS id_cost,
        fu_ajk_polis.nopol AS polis,
        fu_ajk_polis.nmproduk AS namaproduk,
        fu_ajk_peserta.nama AS nama,
        fu_ajk_peserta.kredit_tgl AS startins,
        fu_ajk_peserta.kredit_tenor AS tenor,
        fu_ajk_peserta.kredit_akhir AS endins,
        fu_ajk_peserta.kredit_jumlah AS up,
        fu_ajk_klaim.tgl_klaim AS tglklaim,
        fu_ajk_klaim.tgl_document AS tglklaimdoc,
        fu_ajk_klaim.tgl_document_lengkap AS tglklaimdoc2,
        fu_ajk_klaim.tgl_investigasi AS tglinvestigasi,
        fu_ajk_klaim.tgl_lapor_klaim AS tgllaporklaim,
        fu_ajk_klaim.jumlah AS totalklaim,
        fu_ajk_klaim.tempat_meninggal AS tempat_meninggal,
        fu_ajk_klaim.diagnosa AS diagnosa,
        fu_ajk_klaim.sebab_meninggal AS sebab_meninggal,
        fu_ajk_klaim.ket AS ketklaim,
        fu_ajk_klaim.ket_dokter AS ketDokter
        FROM
        fu_ajk_cn
        INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
        INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta AND fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost
        INNER JOIN fu_ajk_klaim ON fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta AND fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost
        WHERE fu_ajk_cn.id = "'.$_REQUEST['id'].'"'));
        */
		$edklaim = mysql_fetch_array($database->doQuery('SELECT
				fu_ajk_asuransi.`name` AS asuransi,
				fu_ajk_cn.id,
				fu_ajk_cn.id_cost,
				fu_ajk_cn.id_cn,
				fu_ajk_cn.id_dn,
				fu_ajk_dn.dn_kode,
				fu_ajk_dn.id_as,
				fu_ajk_peserta.id_polis,
				fu_ajk_peserta.id_peserta,
				fu_ajk_peserta.nama,
				fu_ajk_peserta.tgl_lahir,
				fu_ajk_peserta.usia,
				fu_ajk_peserta.kredit_tgl,
				IF(fu_ajk_peserta.type_data="SPK", fu_ajk_peserta.kredit_tenor * 12, fu_ajk_peserta.kredit_tenor) AS tenor,
				fu_ajk_peserta.kredit_akhir,
				fu_ajk_peserta.kredit_jumlah,
				fu_ajk_cn.tgl_claim,
				fu_ajk_cn.premi,
				fu_ajk_cn.confirm_claim,
				fu_ajk_cn.total_claim,
				fu_ajk_cn.tuntutan_klaim,
				fu_ajk_cn.tgl_byr_claim,
				fu_ajk_cn.nmpenyakit,
				fu_ajk_cn.keterangan,
				fu_ajk_polis.nmproduk,
				fu_ajk_klaim.id_klaim_status,
				fu_ajk_klaim.tgl_klaim AS tglklaim,
				fu_ajk_klaim.tgl_document AS tglklaimdoc,
				fu_ajk_klaim.tgl_document_lengkap AS tglklaimdoc2,
				fu_ajk_klaim.tgl_investigasi AS tglinvestigasi,
				fu_ajk_klaim.tgl_lapor_klaim AS tgllaporklaim,
				fu_ajk_klaim.jumlah AS totalklaim,
				fu_ajk_klaim.tempat_meninggal AS tempat_meninggal,
				fu_ajk_klaim.diagnosa AS diagnosa,
				fu_ajk_klaim.sebab_meninggal AS sebab_meninggal,
				fu_ajk_klaim.ket AS ketklaim,
				fu_ajk_klaim.sumber_dana,
				fu_ajk_klaim.no_urut_klaim,
				fu_ajk_klaim.no_polis,
				fu_ajk_klaim.ket_dokter AS ketDokter,
				fu_ajk_klaim.tgl_kirim_dokumen AS tglkirimdoc,
				fu_ajk_namapenyakit.id AS idpenyakit,
				fu_ajk_namapenyakit.namapenyakit,
				fu_ajk_cn.policy_liability
				FROM fu_ajk_cn
				LEFT JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
				LEFT JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
				LEFT JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
				LEFT JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
				LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta AND fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost
				LEFT JOIN fu_ajk_namapenyakit ON fu_ajk_cn.nmpenyakit = fu_ajk_namapenyakit.id
				WHERE fu_ajk_cn.id = "' . $_REQUEST['id'] . '" AND fu_ajk_cn.del IS NULL'));

		if($_REQUEST['opt']=="uncheck"){
			$query = "DELETE FROM fu_ajk_klaim_doc where id = ".$_REQUEST['id'];
			mysql_query($query);
			echo '<meta http-equiv="refresh" content="1;URL=ajk_claim.php?d=editklaim&id='.$_REQUEST['idC'].'">';
		}

		$adcostumer = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="' . $edklaim['id_cost'] . '"'));

		if ($_REQUEST['ed_oop'] == "Save") {
			//if (!$_REQUEST['jklaim']) $error1 .= '<font color="red">Total Claim tidak boleh kosong</font>.';
			if (!$_REQUEST['tglklaim']) $error2 .= '<font color="red">Tanggal meninggal tidak boleh kosong</font>.';
			//if ($_REQUEST['jklaim'] > $edklaim['kredit_jumlah']) $error3 .= '<font color="red"><blink>Klaim tidak bisa di proses, nilai klaim lebih besar dari maximum klaim !</blink></font>';
			if (!$_REQUEST['lokasinya']) $error4 .= '<font color="red">Silahkan pilih lokasi meninggal</font>.';
			if (!$_REQUEST['nmpenyakit']) $error5 .= '<font color="red">Silahkan pilih nama penyakit</font>.';
			if (!$_REQUEST['diagnosa']) $error6 .= '<font color="red">Masukan keterangan diagnosa</font>.';
			if (!$_REQUEST['aklaim']) $error7 .= '<font color="red">Total Tunutan Klaim tidak boleh kosong</font>.';
			if (str_replace('.','',$_REQUEST['aklaim']) > $edklaim['kredit_jumlah']) $error8 .= '<font color="red"><blink>Nilai Tuntutan Klaim tidak bisa di proses, nilai tuntutan klaim lebih besar dari maximum klaim !</blink></font>';
			if (!$_REQUEST['policy_liability']) $error9 .= '<font color="red"><blink>Silahkan isi policy liability!</blink></font>';

			if ($error7 OR $error2 OR $error8 OR $error4 OR $error4 & $error5 & $error6) {
			} else {
				//DOKUMEN MENINGGAL
				if (!$_REQUEST['dokklaim']) {
					echo '<center>Tidak ada dokumen yang di input.</center>';
					$confirmnya = "Processing";
				} else {

					//$x = $database->doQuery('DELETE FROM fu_ajk_klaim_doc WHERE id_pes="'.$edklaim['id_peserta'].'" AND id_cost="'.$edklaim['id_cost'].'" AND nama_dokumen IS NULL');
					//echo '<br /><br />';
					foreach ($_REQUEST['dokklaim'] as $doc => $docya) {
						$confirmnya = "Approve(Unpaid)";
						$cekdata = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_klaim_doc WHERE id_cost="' . $edklaim['id_cost'] . '" AND id_pes="' . $edklaim['id_peserta'] . '" AND id_dn="' . $edklaim['id_dn'] . '" AND id_klaim="' . $edklaim['id'] . '" AND dokumen="' . $docya . '"'));
						$tambahdok = $database->doQuery('INSERT INTO fu_ajk_klaim_doc SET id_cost="' . $edklaim['id_cost'] . '",
																		  id_pes="' . $edklaim['id_peserta'] . '",
																		  id_dn="' . $edklaim['id_dn'] . '",
																		  id_klaim="' . $edklaim['id'] . '",
																		  dokumen="' . $docya . '",
																		  input_by="' . $q['nm_lengkap'] . '",
																		  input_date="' . $futgl . '"');

					}
				}
				//DOKUMEN MENINGGAL
				//echo '<br /><br />';
				/*if ($_REQUEST['tglbyrklaim'] == "") {*/
					$updatestatusklaim = "Approve(unpaid)";
				/*} else {
					$updatestatusklaim = "Approve(paid)";
				}*/
				if($_REQUEST['policy_liability']=='LIABLE'){
					$klaim_status='Normal';
				}else{
					$klaim_status='Tiering';
				}


				$setupdatecn = $database->doQuery('UPDATE fu_ajk_cn SET total_claim="' . $_REQUEST['jklaim'] . '",
														  tgl_claim="' . $_REQUEST['tglklaim'] . '",
														  tuntutan_klaim="' . $_REQUEST['aklaim'] . '",
														  /*total_claim="' . $_REQUEST['jklaim'] . '",*/
														  /*tgl_byr_claim="' . $_REQUEST['tglbyrklaim'] . '",*/
														  confirm_claim="' . $updatestatusklaim . '",
														  policy_liability="'.$_REQUEST['policy_liability'].'",
														  status_bayar="'.$klaim_status.'",
														  nmpenyakit="' . $_REQUEST['nmpenyakit'] . '",
														  keterangan="' . $_REQUEST['ket'] . '",
														  update_by="' . $q['nm_lengkap'] . '",
														  update_time="' . $futgl . '" WHERE id="' . $_REQUEST['id'] . '"');
				//echo '<br /><br />';
				$_mametKlaim = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_klaim WHERE id_dn="' . $edklaim['id_dn'] . '" AND id_cn="' . $edklaim['id'] . '" AND id_peserta="' . $edklaim['id_peserta'] . '"'));
				if (!$_mametKlaim['id_peserta']) {
					if($edklaim['id_klaim_status']==1 || $edklaim['id_klaim_status']==4 || $edklaim['id_klaim_status']==5 || $edklaim['id_klaim_status']==6 || $edklaim['id_klaim_status']==7){
						$idstatus='';
					}else{
						$idstatus='id_klaim_status="' . $_REQUEST['status_klaim'] . '",';
					}
					$metklaim = $database->doQuery('INSERT INTO fu_ajk_klaim SET id_cost="' . $edklaim['id_cost'] . '",
															 id_dn="' . $edklaim['id_dn'] . '",
															 id_cn="' . $edklaim['id'] . '",
															 id_peserta="' . $edklaim['id_peserta'] . '",
															 '.$idstatus.'
															 tgl_klaim="' . $_REQUEST['tglklaim'] . '",
															 tgl_document="' . $_REQUEST['tglklaimdoc'] . '",
															 tgl_document_lengkap="' . $_REQUEST['tglklaimdoc2'] . '",
															 tgl_lapor_klaim="' . $_REQUEST['tgllaporklaim'] . '",
															 tgl_kirim_dokumen="' . $_REQUEST['tglkirimklaim'] . '",
															 /*tgl_investigasi="' . $_REQUEST['tglinvestigasi'] . '",*/
															 type_klaim="Death",
															 /*jumlah="' . $_REQUEST['jklaim'] . '",*/
															 tuntutan_klaim="' . $_REQUEST['aklaim'] . '",
															 confirm_klaim="' . $confirmnya . '",
															 /*ket="' . $_REQUEST['ket'] . '",*/
															 sumber_dana="' . $_REQUEST['sumber_dana'] . '",
															 /*ket_dokter="' . $_REQUEST['ketDokter'] . '",*/
															 tempat_meninggal="' . $_REQUEST['lokasinya'] . '",
															 sebab_meninggal="' . $_REQUEST['nmpenyakit'] . '",
															 diagnosa="' . $_REQUEST['diagnosa'] . '",
															 input_by="' . $q['nm_lengkap'] . '",
															 input_date="' . $futgl . '"');

				} else {
					if($edklaim['id_klaim_status']==1 || $edklaim['id_klaim_status']==4 || $edklaim['id_klaim_status']==5 || $edklaim['id_klaim_status']==6 || $edklaim['id_klaim_status']==7){
						$idstatus='';
					}else{
						$idstatus='id_klaim_status="' . $_REQUEST['status_klaim'] . '",';
					}
					$setupdateklaim = $database->doQuery('UPDATE fu_ajk_klaim SET
															 	 '.$idstatus.'
																 /*jumlah="' . $_REQUEST['jklaim'] . '",*/
																 tgl_klaim="' . $_REQUEST['tglklaim'] . '",
																 no_urut_klaim = "'.$_REQUEST['no_urut_klaim'].'",
																 tgl_document="' . $_REQUEST['tglklaimdoc'] . '",
																 tgl_document_lengkap="' . $_REQUEST['tglklaimdoc2'] . '",
																 tgl_lapor_klaim="' . $_REQUEST['tgllaporklaim'] . '",
																 tgl_kirim_dokumen="' . $_REQUEST['tglkirimklaim'] . '",
																 tgl_investigasi="' . $_REQUEST['tglinvestigasi'] . '",
																 /*ket="' . $_REQUEST['ket'] . '",*/
																 tempat_meninggal="' . $_REQUEST['lokasinya'] . '",
																 sebab_meninggal="' . $_REQUEST['nmpenyakit'] . '",
																 /*diagnosa="' . $_REQUEST['diagnosa'] . '",*/
																 update_by="' . $q['nm_lengkap'] . '",
															 	 no_polis="' . $_REQUEST['no_polis'] . '",
															 	 sumber_dana="' . $_REQUEST['sumber_dana'] . '",
																 update_time="' . $futgl . '"
																 WHERE id_dn="' . $edklaim['id_dn'] . '" AND
															 	   id_cn="' . $edklaim['id'] . '" AND
															 	   id_peserta="' . $edklaim['id_peserta'] . '"');
				}
				echo '<center><h2>Data Klaim meninggal telah di update oleh ' . $q['nm_lengkap'] . ' pada tanggal ' . $futgl . '.</h2></center><meta http-equiv="refresh" content="2;URL=ajk_claim.php">';
			}
		}
		//RUMUSAN PERHITUNGAN RATE MENINGGAL
		/*$now2 = explode("/", $edklaim['startins']);
        $now3 = $now2[2].'-'.$now2[1].'-'.$now2[0];
        $now = new T10DateCalc($now3);
        $periodbulan = $now->compareDate($edklaim['tglklaim']) / 30.4375;
        $maj = floor($periodbulan);*/
		// PERHITUNGAN BULAN MASA ASURANSI SAMPAI MASA ASURANSI BERJALAN

		/*
        $now = new T10DateCalc($edklaim['startins']);
        $periodbulan = $now->compareDate($edklaim['tglklaim']) / 30.4375;
        $maj = ceil($periodbulan);
        */

		$mets = datediff($edklaim['kredit_tgl'], $edklaim['tgl_claim']);
		$metTgl = explode(",", $mets);
		//echo $metTgl[0].'-'.$metTgl[1].'-'.$metTgl[2].'<br />';
		if ($metTgl[2] > 0) {
			$jumBulan = $metTgl[1] + 1;
		} else {
			$jumBulan = $metTgl[1];
		}    //AKUMULASI BULAN THD JUMLAH HARI
		$maj = ($metTgl[0] * 12) + $jumBulan;

		//$met = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_rateklaimdie WHERE masa_asuransi >= "'.$edklaim['tenor'].'" AND bulan_ke="'.$maj.'" AND id_cost="'.$edklaim['id_cost'].'"'));
		//if ($edklaim['idpolis']=="11") {	$jum = $edklaim['up'];	}
		//else	{	$jum = $met['rate'] / 1000 * $edklaim['up'];	}


		echo '<form method="post" action="">
			  <table border="0" cellpadding="3" cellspacing="0" width="80%" align="center" style="border: solid 1px #DEDEDE" align="center">
			  <input type="hidden" name="id" value="' . $_REQUEST['id'] . '">
			  <input type="hidden" name="kredit_tenor" value="' . $edklaim['tenor'] . '">
			<tr><th colspan="4">Edit Form Pengisian Data Klaim Meninggal</th></tr>
			<tr><td width="25%">Nama Perusahaan</td><td>: <b>' . $adcostumer['name'] . '</b></td>
			<tr>
				<td>Nama Produk</td><td>: <b>' . $edklaim['nmproduk'] . '</b></td>
				<td width="25%" align="right">ID Debitur</td><td>: <b>' . $edklaim['id_peserta'] . '</b></td>
			</tr>
			<tr><td>Name</td><td>: <b>' . $edklaim['nama'] . '</b></td>
					<td width="25%" align="right">No Urut Klaim </td><td>: <input type="text" name="no_urut_klaim" value="' . $edklaim['no_urut_klaim'] . '"></td>
			</tr>
			<tr><td>Plafond</td><td colspan="3">: <b>' . duit($edklaim['kredit_jumlah']) . '</b></td></tr>
			<tr><td>Tanggal Asuransi</td><td>: <b>' . _convertDate($edklaim['kredit_tgl']) . '</b> s/d <b>' . _convertDate($edklaim['kredit_akhir']) . '</b></td>
				<td align="right">Tenor</td><td>: <b>' . $edklaim['tenor'] . '</b> Bulan</td>
			</tr>
			<tr><td>Tanggal Meninggal <font color="red"><b>*</b></font></td><td>: ';
				print initCalendar();
				print calendarBox('tglklaim', 'triger', $edklaim['tgl_claim']);
				echo $error2 . '</td>
				<td align="right">Maksimum Klaim</td><td>: <font color="blue"><b>' . duit($edklaim['kredit_jumlah']) . '</b></font></td>
			</tr>
			<tr><td>Tanggal Terima Laporan</td><td>: ';
				print initCalendar();
				print calendarBox('tglklaimdoc', 'triger1', $edklaim['tglklaimdoc']);
				echo '</td>
			<td align="right">Ms Asuransi Berjalan</td><td>: <b>' . $maj . '</b> Bulan</td></tr>
			<tr><!--<td>Tanggal Investigasi</td><td>: ';
				print initCalendar();
				print calendarBox('tglinvestigasi', 'triger3', $edklaim['tglinvestigasi']);
				echo '</td>--><td align="left">Nilai Tuntutan Klaim<font color="red"><b>*</b></font></td>
						<td>: <input type="text" name="aklaim" value="' . $edklaim['tuntutan_klaim'] . '" onkeypress="return isNumberKey(event)">' . $error7 . ' ' . $error8 . ' </td>
			<tr><td>Lokasi Meninggal <font color="red"><b>*</b></font></td><td>: <select size="1" name="lokasinya">
				<option value="">--Pilih Lokasi--</option>
				<option value="Rumah"' . _selected($edklaim['tempat_meninggal'], "Rumah") . '>Rumah</option>
				<option value="Rumah Sakit"' . _selected($edklaim['tempat_meninggal'], "Rumah Sakit") . '>Rumah Sakit</option>
				<option value="Lain-Lain"' . _selected($edklaim['tempat_meninggal'], "Lain-Lain") . '>Lain-Lain</option>
				</select>' . $error4 . '</td>
				<td align="right">Total Klaim Disetujui<font color="red"><b>*</b></font></td>
						<td>: <input type="text" name="jklaim" value="' . $edklaim['total_claim'] . '" disabled> </td>
		</tr>
		<tr><td>Penyabab Meninggal <font color="red"><b>*</b></font></td><td>:
				<select size="1" name="nmpenyakit">
			   	<option value="">---Penyebab Meninggal---</option>';
				$nmPenyakit = $database->doQuery('SELECT * FROM fu_ajk_namapenyakit ORDER BY id ASC');
				while ($nmPenyakit_ = mysql_fetch_array($nmPenyakit)) {
					echo '<option value="' . $nmPenyakit_['id'] . '"' . _selected($edklaim['nmpenyakit'], $nmPenyakit_['id']) . '>' . $nmPenyakit_['namapenyakit'] . '</option>';
				}
				echo '</select>' . $error5 . '</td>
				<td align="right">Tanggal Kelengkapan Dokumen</td><td>: ';
				print initCalendar();
				print calendarBox('tglklaimdoc2', 'triger2', $edklaim['tglklaimdoc2']);
				echo '</td>
		</tr>

		<tr>

		<td valign="top">Status Klaim</td><td>:
		<select size="1" name="status_klaim">
			   	<option value="">---Status Klaim---</option>';
				$nmPenyakit = $database->doQuery('SELECT * FROM fu_ajk_klaim_status where view_list=0 ORDER BY order_list ASC');
				while ($nmPenyakit_ = mysql_fetch_array($nmPenyakit)) {
					echo '<option value="' . $nmPenyakit_['id'] . '"' . _selected($edklaim['id_klaim_status'], $nmPenyakit_['id']) . '>' . $nmPenyakit_['status_klaim'] . '</option>';
				}
				echo '</select>' . $error6 . '
		</td><td align="right">Tanggal Informasi Ke Asuransi</td><td>: ';
				print initCalendar();
				print calendarBox('tgllaporklaim', 'triger5', $edklaim['tgllaporklaim']);
				echo '</td><td valign="top"></td>
		</tr>
		<tr>
		<td valign="top">Status Liability</td><td>:
		<select size="1" name="policy_liability">
			   	<option value="">---Policy Liability---</option>
			   	<option value="LIABLE"' . _selected("LIABLE", $edklaim['policy_liability']) . '>LIABLE</option>
			   	<option value="NONLIABLE"' . _selected("NONLIABLE", $edklaim['policy_liability']) . '>NONLIABLE</option>
				</select>' . $error9 . '
		</td>
						<td align="right">Tanggal Kirim Dokumen Ke Asuransi</td><td>: ';
				print initCalendar();
				print calendarBox('tglkirimklaim', 'triger6', $edklaim['tglkirimdoc']);
				echo '</td>
		</tr>
		<tr>
		<td valign="top">Sumber Dana</td>
		<td>: <textarea rows="2" name="sumber_dana" value="' . $_REQUEST['sumber_dana'] . '" cols="40" maxlength="250">' . $edklaim['sumber_dana'] . '</textarea></td>
		</td>
		</tr>';
		if($edklaim['id_as']=='5'){
			echo '<td valign="top">Nomor Polis</td>
						<td>: <input type="text" name="no_polis" value="' . $edklaim['no_polis'] . '" </td>';
		}
		echo '<tr>
		</tr>
			<tr><th colspan="5">Kelengkapan Dokumen</th></tr>
			<tr><td colspan="5">
			<table border="0" cellpadding="3" cellspacing="1" width="100%" align="center" style="border: solid 1px #DEDEDE" align="center">
			<tr><th>No</th><th>Option</th><th>Dokumen</th></tr>';
				/*
		        $metdokumen = $database->doQuery('SELECT * FROM fu_ajk_dokumenklaim_bank WHERE id_bank="'.$edklaim['id_cost'].'" AND id_produk="'.$edklaim['idpolis'].'" ORDER BY id ASC');
		        while ($rdok = mysql_fetch_array($metdokumen)) {
		        $metdokumenklaim = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dokumenklaim WHERE id="'.$rdok['id_dok'].'"'));
		        $cekdoknya = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_klaim_doc WHERE id_cost="'.$edklaim['id_cost'].'" AND dokumen="'.$rdok['id'].'" AND id_pes="'.$edklaim['idp'].'"'));
		        if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
		        echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		                <td align="center">'.++$no.'</td>
		                <td>'.$metdokumenklaim['nama_dok'].'</td>
		                <td align="center">';
		        if ($cekdoknya['dokumen']==$rdok['id'] AND $cekdoknya['nama_dokumen']!="") {	echo '<input type="checkbox" name="ya[]" value="'.$rdok['id'].'" checked disabled>';	}
		        elseif ($cekdoknya['dokumen']==$rdok['id']) {	echo '<input type="checkbox" name="ya[]" value="'.$rdok['id'].'" checked>';	}
		        else	{	echo '<input type="checkbox" name="ya[]" value="'.$rdok['id'].'">';	}
		        echo '</td></tr>';
		        }
		        */
				/*
		        $metdokumen = $database->doQuery('SELECT * FROM fu_ajk_klaim_dokumen WHERE id_cost="'.$edklaim['id_cost'].'" ORDER BY id ASC');
		        while ($rdok = mysql_fetch_array($metdokumen)) {
		        $cekdoknya = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_klaim_doc WHERE id_cost="'.$edklaim['id_cost'].'" AND dokumen="'.$rdok['id'].'" AND id_pes="'.$edklaim['idp'].'"'));
		        if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
		        echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		                      <td align="center">'.++$no.'</td>
		                          <td>'.$rdok['dokumen'].'</td>
		                          <td align="center">';
		        if ($cekdoknya['dokumen']==$rdok['id']) {
		            echo '<input type="checkbox" name="ya[]" value="'.$rdok['id'].'" checked>';
		        }else{
		        echo '<input type="checkbox" name="ya[]" value="'.$rdok['id'].'">';
		        }
		        echo '</td>
		                      </tr>';
		        }
		        */
				$met_dok = $database->doQuery('SELECT
						fu_ajk_dokumenklaim_bank.id,
						fu_ajk_dokumenklaim_bank.id_bank,
						fu_ajk_dokumenklaim_bank.id_dok,
						fu_ajk_dokumenklaim.nama_dok
						FROM
						fu_ajk_dokumenklaim_bank
						INNER JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim_bank.id_dok = fu_ajk_dokumenklaim.id
						WHERE id_bank="' . $edklaim['id_cost'] . '" AND id_produk="' . $edklaim['id_polis'] . '" ORDER BY urut ASC');
				while ($met_dok_ = mysql_fetch_array($met_dok)) {
					$cekDokumenKlaim = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_klaim_doc WHERE id_pes="' . $edklaim['id_peserta'] . '" AND id_cost="' . $edklaim['id_cost'] . '" AND dokumen="' . $met_dok_['id'] . '" AND del IS NULL'));
					if ($cekDokumenKlaim) {
						if($cekDokumenKlaim['nama_dokumen']!=""){
							$cekDataDok = '<input title="dokumen tidak bisa diedit" type="checkbox" name="dokklaim[]" value="' . $met_dok_['id'] . '" checked disabled>';
						}else{
							$cekDataDok = '<input title="dokumen tidak bisa diedit" type="checkbox" name="dokklaim[]" value="' . $met_dok_['id'] . '" checked disabled>
														 <a title="Uncheck Dokumen" href="ajk_claim.php?d=editklaim&opt=uncheck&id='.$cekDokumenKlaim['id'].'&idC='.$cekDokumenKlaim['id_klaim'].'"><img src="image/deleted.png" width="15"></a>';
						}
					} else {
						$cekDataDok = '<input type="checkbox" name="dokklaim[]" value="' . $met_dok_['id'] . '">';
					}
					if (($no % 2) == 1) $objlass = 'tbl-odd'; else $objlass = 'tbl-even';
					echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
				  <td align="center">' . ++$no . '</td>
				  <td align="center">' . $cekDataDok . '</td>
				  <td>' . $met_dok_['nama_dok'] . '</td>
				  </tr>';
				}
				echo '</table></td></tr>';
				echo '<tr><td valign="top">Keterangan Dokumen Klaim</td><td colspan="3"><textarea rows="2" name="ket" value="' . $edklaim['keterangan'] . '" cols="100">' . $edklaim['keterangan'] . '</textarea></td></tr>
					<tr><td colspan="4" align="center"><input type="submit" name="ed_oop" value="Save"></td></tr>
				</table></form>';;
	break;

	case "dproses":
		echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
		<tr><th colspan="8" width="100%" align="left" colspan="2">Modul Klaim - Proses Klaim</font></th><th width="5%" colspan="2"><a href="ajk_claim.php?d=dx"><img src="image/Backward-64.png" width="20"></a></th></tr>
		</table><br />';
		$peserta = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id="' . $_REQUEST['id'] . '"'));
		$datadn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn WHERE dn_kode="' . $peserta['id_dn'] . '" AND id_cost="' . $peserta['id_cost'] . '"'));
		$ppolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="' . $peserta['id_polis'] . '"'));
		$pcostumer = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="' . $peserta['id_cost'] . '"'));

		if ($_REQUEST['oop'] == "Save") {
			//if (!$_REQUEST['jklaim']) $error1 .= '<font color="red">Total Claim tidak boleh kosong</font>.';
			if (!$_REQUEST['tglklaim']) $error2 .= '<font color="red">Tanggal meninggal tidak boleh kosong</font>.';
			//if ($_REQUEST['jklaim'] > $peserta['kredit_jumlah']) $error3 .= '<font color="red"><blink>Klaim tidak bisa di proses, nilai klaim lebih besar dari maximum klaim !</blink></font>';
			if (!$_REQUEST['lokasinya']) $error4 .= '<font color="red">Silahkan pilih lokasi meninggal</font>.';
			if (!$_REQUEST['penyebabnya']) $error5 .= '<font color="red">Silahkan pilih penyebab meninggal</font>.';
			if (!$_REQUEST['status_klaim']) $error6 .= '<font color="red">Silahkan pilih status klaim</font>.';
			if (!$_REQUEST['aklaim']) $error7 .= '<font color="red">Total Klaim sisetujui tidak boleh kosong</font>.';
			if ($_REQUEST['aklaim'] > $peserta['kredit_jumlah']) $error8 .= '<font color="red"><blink>Klaim tidak bisa di proses, nilai klaim disetujui lebih besar dari maximum klaim !</blink></font>';

			if ($error7 OR $error2 OR $error8 OR $error4 OR $error5 OR $error6) {

			} else {
				$cn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cn ORDER BY id DESC'));
				$metidcn = explode(" ", $cn['input_date']);
				$metidthncn = explode("-", $metidcn[0]);
				if ($metidthncn[0] < $futgliddn) {
					$metautocn = 1;
				} else {
					$metautocn = $cn['idC'] + 1;
				}        //set nomor cn bila tahun baru
				$idcnnya = 10000000000 + $metautocn;
				$idcn = substr($idcnnya, 1);
				$cntgl = explode("-", $futgldn);
				$cnthn = substr($cntgl[0], 2);
				$cn_kode = 'ACN' . $cnthn . '' . $cntgl[1] . '' . $idcn;
				//JUMLAH KLAIM BILA TIDAK DI INPUT MANUAL
				if ($_REQUEST['jklaim'] == "") {
					$nilaiklaimnya = $jum;
				} else {
					$nilaiklaimnya = $_REQUEST['jklaim'];
				}
				if ($_REQUEST['aklaim'] == "") {
					$tuntutanklaim = $nilaiklaimnya;
				} else {
					$tuntutanklaim = $_REQUEST['aklaim'];
				}



				//RUMUSAN PERHITUNGAN RATE MENINGGAL

				//INSERT DATA CN//
				$rklaim = $database->doQuery('INSERT INTO fu_ajk_cn SET id_dn="' . $peserta['id_dn'] . '",
															 idC="' . $metautocn . '",
															 id_cn="' . $cn_kode . '",
															 id_cost="' . $peserta['id_cost'] . '",
															 id_nopol="' . $peserta['id_polis'] . '",
															 id_peserta="' . $peserta['id_peserta'] . '",
															 premi="' . $peserta['totalpremi'] . '",
															 id_regional="' . $peserta['regional'] . '",
															 id_cabang="' . $peserta['cabang'] . '",
															 tgl_claim="' . $_REQUEST['tglklaim'] . '",
															 tgl_createcn="' . $futgldn . '",
															 tgl_byr_claim="' . $_REQUEST['tglbyrklaim'] . '",
															 type_claim="Death",
															 validasi_cn_uw="ya",
															 validasi_cn_arm="ya",
															 confirm_claim="' . $confirmnya . '",
															 tuntutan_klaim="' . $tuntutanklaim . '",
															 total_claim="' . $nilaiklaimnya . '",
															 keterangan="' . $_REQUEST['ket'] . '",
															 input_by="' . $q['nm_lengkap'] . '",
															 input_date="' . $futgl . '" ');
				//INSERT DATA CN//
				$met_data_cn = mysql_fetch_array($database->doQuery('SELECT id, idC FROM fu_ajk_cn ORDER BY ID DESC'));

				/*$rklaim = $database->doQuery('INSERT INTO fu_ajk_note_as SET id_dn="' . $peserta['id_dn'] . '",
															 id_cn="' . $met_data_cn['id'] . '",
															 id_peserta="' . $peserta['id_peserta'] . '",
															 note_type="DNC",
	 														 note_date="' . $futoday . '",
	 														 note_desc="Tagihan Klaim a/n '.$peserta['nama'].' dengan Nilai Klaim '.$nilaiklaimnya.'",
                											 note_curr="IDR",
                											 note_subtotal="'.$nilaiklaimnya.'",
                											 note_other_fee="0",
                											 note_total="'.$nilaiklaimnya.'",
															 entry_by="' . $q['nm_lengkap'] . '",
															 entry_time="' . $futgl . '" ');
				*/
				//DOKUMEN MENINGGAL
				if (!$_REQUEST['ya']) {
					echo '<center>Tidak ada dokumen yang di input.</center>';
					//$confirmnya = "Processing"; 26082014 (data awal)
					$confirmnya = "Approve(Unpaid)";
				} else {
					foreach ($_REQUEST['ya'] as $doc => $docya) {
						$confirmnya = "Approve(Unpaid)";
						$metdoknya = $database->doQuery('INSERT INTO fu_ajk_klaim_doc SET id_pes="' . $peserta['id_peserta'] . '",
																  id_cost="' . $peserta['id_cost'] . '",
																  id_dn="' . $peserta['id_dn'] . '",
																  id_klaim="' . $met_data_cn['id'] . '",
																  dokumen="' . $docya . '",
																  input_by="' . $q['nm_lengkap'] . '",
																  input_date="' . $futgl . '"');
					}
				}
				//DOKUMEN MENINGGAL
				//DATA INSERT//
				$metklaim = $database->doQuery('INSERT INTO fu_ajk_klaim SET id_cost="' . $peserta['id_cost'] . '",
															 id_dn="' . $peserta['id_dn'] . '",
															 id_cn="' . $met_data_cn['id'] . '",
															 id_peserta="' . $peserta['id_peserta'] . '",
															 id_klaim_status="' . $_REQUEST['status_klaim'] . '",
															 tgl_klaim="' . $_REQUEST['tglklaim'] . '",
															 tgl_document="' . $_REQUEST['tglklaimdoc'] . '",
															 tgl_document_lengkap="' . $_REQUEST['tglklaimdoc2'] . '",
															 tgl_lapor_klaim="' . $_REQUEST['tgllaporklaim'] . '",
															 tgl_kirim_dokumen="' . $_REQUEST['tglkirimklaim'] . '",
															 tgl_investigasi="' . $_REQUEST['tglinvestigasi'] . '",
															 type_klaim="Death",
															 tuntutan_klaim="'. $nilaiklaimnya .'",
															 jumlah="' . $nilaiklaimnya . '",
															 confirm_klaim="' . $confirmnya . '",
															 ket="' . $_REQUEST['ket'] . '",
															 sumber_dana="' . $_REQUEST['sumber_dana'] . '",
															 ket_dokter="' . $_REQUEST['ketDokter'] . '",
															 tempat_meninggal="' . $_REQUEST['lokasinya'] . '",
															 sebab_meninggal="' . $_REQUEST['penyebabnya'] . '",
															 diagnosa="' . $_REQUEST['diagnosa'] . '",
															 input_by="' . $q['nm_lengkap'] . '",
															 input_date="' . $futgl . '"');

				$updatepeserta = $database->doQuery('UPDATE fu_ajk_peserta SET id_klaim="' . $met_data_cn['id'] . '", status_aktif="Lapse", status_peserta="Death" WHERE id="' . $_REQUEST['id'] . '"');

			//DATA INSERT//
				echo '<center><h2>Data Klaim meninggal telah di proses.</h2></center><meta http-equiv="refresh" content="3;URL=ajk_claim.php">';
			}
		}

		echo '<form method="post" action="">
		<table border="0" cellpadding="3" cellspacing="0" width="80%" align="center" style="border: solid 1px #DEDEDE" align="center">
		<input type="hidden" name="id" value="' . $_REQUEST['id'] . '">
		<input type="hidden" name="kredit_tenor" value="' . $peserta['kredit_tenor'] . '">
		<tr><th colspan="4">Form Pengisian Data Klaim Meninggal</th></tr>
		<tr><td width="20%">Nama Perusahaan</td><td>: <b>' . $pcostumer['name'] . '</b></td>
			<td width="25%" align="right">Reg. ID</td><td>: <b>' . $peserta['id_peserta'] . '</b></td>
		</tr>
		<tr><td width="20%" coslpan="3">Nama Produk</td><td>: <b>' . $ppolis['nmproduk'] . '</b></td></tr>
		<tr><td>Name</td><td colspan="3">: <b>' . $peserta['nama'] . '</b></td></tr>
		<tr><td>Sum Insured</td><td colspan="3">: <b>' . duit($peserta['kredit_jumlah']) . '</b></td></tr>
		<tr><td>Period</td><td>: from <b>' . _convertDate($peserta['kredit_tgl']) . '</b> &nbsp; to : <b>' . _convertDate($peserta['kredit_akhir']) . '</b></td>
			<td align="right">Tenor</td><td>: <b>' . $peserta['kredit_tenor'] . '</b> Bulan</td>
		</tr>
		<tr><td>Tanggal Meninggal <font color="red"><b>*</b></font></td><td>: ';
		print initCalendar();
		print calendarBox('tglklaim', 'triger', $_REQUEST['tglklaim']);
		echo $error2 . '</td>
			<td align="right">Max Claim</td><td>: <font color="blue"><b>' . duit($peserta['kredit_jumlah']) . '</b></font></td>
		</tr>
		<tr><td>Tanggal Terima Laporan</td><td>: ';
		print initCalendar();
		print calendarBox('tglklaimdoc', 'triger1', $_REQUEST['tglklaimdoc']);
		echo '</td>
		<td align="right">Ms Asuransi Berjalan</td><td>: <b>' . $maj . '</b> Bulan</td></tr>
		<tr><td>Tanggal Investigasi</td><td>: ';
		print initCalendar();
		print calendarBox('tglinvestigasi', 'triger3', $_REQUEST['tglinvestigasi']);
		echo '</td>

		<td align="right">Nilai Tuntutan Klaim <font color="red"><b>*</b></font></td><td>:
				<input type="text" name="jklaim" value="' . $_REQUEST['jklaim'] . '" onkeypress="return isNumberKey(event)"> ' . $error7 . ' ' . $error8 . '</td>

		<tr><td>Lokasi Meninggal <font color="red"><b>*</b></font></td><td>: <select size="1" name="lokasinya">
		<option value="">--Pilih Lokasi--</option>
		<option value="Rumah"' . _selected($_REQUEST["lokasinya"], "Rumah") . '>Rumah</option>
		<option value="Rumah Sakit"' . _selected($_REQUEST["lokasinya"], "Rumah Sakit") . '>Rumah Sakit</option>
		<option value="Lain-Lain"' . _selected($_REQUEST["lokasinya"], "Lain-Lain") . '>Lain-Lain</option>
		</select>' . $error4 . '</td>
		<td align="right">Nilai Klaim Disetujui <font color="red"><b>*</b></font></td>
				<td>: <input type="text" name="aklaim" value="' . $_REQUEST['aklaim'] . '"></td>

		</tr>
		<tr><td>Penyebab Meninggal <font color="red"><b>*</b></font><br />' . $error1 . '</td><td valign="top">:
		    <select size="1" name="penyebabnya">
		   	<option value="">---Penyebab Meninggal---</option>';
			$nmPenyakit = $database->doQuery('SELECT * FROM fu_ajk_namapenyakit ORDER BY id ASC');
			while ($nmPenyakit_ = mysql_fetch_array($nmPenyakit)) {
				echo '<option value="' . $nmPenyakit_['id'] . '"' . _selected($_REQUEST['penyebabnya'], $nmPenyakit_['id']) . '>' . $nmPenyakit_['namapenyakit'] . '</option>';
			}
			echo '</select>' . $error5 . '</td><td align="right">Tanggal Kelengkapan Dokumen</td><td>: ';
		print initCalendar();
		print calendarBox('tglklaimdoc2', 'triger2', $_REQUEST['tglklaimdoc2']);
		echo '</td>
		</tr>
		<!--
		<tr><td>Penyabab Meninggal <font color="red"><b>*</b></font></td><td>: <select size="1" name="penyebabnya">
				<option value="">--Penyebab Meninggal--</option>
				<option value="Alami"' . _selected($_REQUEST["penyebabnya"], "Alami") . '>Alami</option>
				<option value="Kecelakaan"' . _selected($_REQUEST["penyebabnya"], "Kecelakaan") . '>Kecelakaan</option>
				<option value="Sakit"' . _selected($_REQUEST["penyebabnya"], "Sakit") . '>Sakit</option>
				</select>' . $error5 . '</td></tr>
		-->
		<tr><td valign="top">Keterangan Diagnosa</td>
		<td><textarea rows="2" name="diagnosa" value="' . $_REQUEST['diagnosa'] . '" cols="40">' . $_REQUEST['diagnosa'] . '</textarea></td>
				<td align="right"><b>Tanggal Bayar Klaim Ke Bank</b></td><td>: ';
		print initCalendar();
		print calendarBox('tglbyrklaim', 'triger4', $_REQUEST['tglbyrklaim']);
		echo '</td></tr>
		<tr>
		</tr>
		<tr><!--<td valign="top">Keterangan Dokter Adonai</td>
						<td><textarea rows="2" name="ketDokter" value="' . $_REQUEST['ketDokter'] . '" cols="40" maxlength="250">' . $_REQUEST['ketDokter'] . '</textarea></td>-->
		<td align="right">Tanggal Informasi Ke Asuransi</td><td>: ';
		print initCalendar();
		print calendarBox('tgllaporklaim', 'triger5', $_REQUEST['tgllaporklaim']);
		echo '</td>
				</tr>
		<tr><td valign="top">Status Klaim</td><td>
		<select size="1" name="status_klaim">
	   	<option value="">---Status Klaim---</option>';
		$nmPenyakit = $database->doQuery('SELECT * FROM fu_ajk_klaim_status where view_list=0 ORDER BY order_list ASC');
		while ($nmPenyakit_ = mysql_fetch_array($nmPenyakit)) {
			echo '<option value="' . $nmPenyakit_['id'] . '"' . _selected($_REQUEST['status_klaim'], $nmPenyakit_['id']) . '>' . $nmPenyakit_['status_klaim'] . '</option>';
		}
		echo '</select>' . $error6 . '
		</td><td align="right">Tanggal Kirim Dokument Ke Asuransi</td><td>: ';
		print initCalendar();
		print calendarBox('tglkirimklaim', 'triger6', $_REQUEST['tglkirimklaim']);
		echo '</td>
		</tr>

		<tr>
		<td valign="top">Sumber Dana</td>
		<td><textarea rows="2" name="sumber_dana" value="' . $_REQUEST['sumber_dana'] . '" cols="40" maxlength="250">' . $_REQUEST['sumber_dana'] . '</textarea></td>
		</td>
		</tr>
				';



		if ($datadn['ket'] != "") {
			echo ' <tr><td valign="top"><b>Keterangan Underwriting</b></td><td colspan="3">: ' . $datadn['ket'] . '</td></tr>';
		}
		echo '<tr><th colspan="5">Kelengkapan Dokumen</th></tr>
		<tr><td colspan="5">
		<table border="0" cellpadding="3" cellspacing="1" width="100%" align="center" style="border: solid 1px #DEDEDE" align="center">
		<tr><th>No</th><th>Dokumen</th><th>Option</th></tr>';
		$metdokumen = $database->doQuery('SELECT * FROM fu_ajk_dokumenklaim_bank WHERE id_bank="' . $peserta['id_cost'] . '" AND id_produk="' . $peserta['id_polis'] . '" ORDER BY id ASC');
		while ($rdok = mysql_fetch_array($metdokumen)) {
			$metdokumenklaim = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dokumenklaim WHERE id="' . $rdok['id_dok'] . '"'));
			if (($no % 2) == 1) $objlass = 'tbl-odd'; else    $objlass = 'tbl-even';
			echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		<td align="center">' . ++$no . '</td>
		  <td>' . $metdokumenklaim['nama_dok'] . '</td>
		  <td align="center"><input type="checkbox" name="ya[]" value="' . $rdok['id'] . '"></td>
		</tr>';
		}
		echo '</table></td></tr>';
		echo '<tr><td valign="top">Keterangan</td><td colspan="3">&nbsp;<textarea rows="2" name="ket" value="' . $_REQUEST['ket'] . '" cols="50">' . $_REQUEST['ket'] . '</textarea></td></tr>
			<tr><td colspan="4" align="center"><input type="submit" name="oop" value="Save"></td></tr>
		</table></form>';;
	break;

	case "dx":
		echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
		<tr><th colspan="8" width="100%" align="left" colspan="2">Modul Klaim - New Claim</font></th><th width="5%" colspan="2"><a href="ajk_claim.php"><img src="image/Backward-64.png" width="20"></a></th></tr>
		</table><br />';
		echo '<fieldset>
		<legend>Searching</legend>
		<table border="0" width="50%" cellpadding="1" cellspacing="1" align="center">
	  <form method="post" action="">
	  <tr><td width="25%" align="right">Nama Produk</td>
		  <td width="30%">: <select id="cat" name="cat">
		  	<option value="">---Pilih Produk---</option>';
		$quer2 = $database->doQuery('SELECT * FROM fu_ajk_polis ORDER BY nopol ASC');
		while ($noticia2 = mysql_fetch_array($quer2)) {
			if ($noticia2['id'] == $cat) {
				echo '<option selected value="' . $noticia2['id'] . '">' . $noticia2['nmproduk'] . '</option><BR>';
			} else {
				echo '<option value="' . $noticia2['id'] . '">' . $noticia2['nmproduk'] . '</option>';
			}
		}
		echo '</select></td><td width="5%" align="right">Nama</td><td>: <input type="text" name="rnama" value="' . $_REQUEST['rnama'] . '"></td></tr>
				<tr><td width="10%" align="right">Nomor DN</td><td width="20%">: <input type="text" name="nodn" value="' . $_REQUEST['nodn'] . '"></td>
			<td width="5%" align="right">DOB</td><td>: ';
		print initCalendar();
		print calendarBox('rdob', 'triger', $_REQUEST['rdob']);
		echo '</td></tr>
			<tr><td colspan="4" align="center"><input type="submit" name="dm" value="Searching" class="button"></td></tr>
			</form>
			</table></fieldset>';
		if ($_REQUEST['dm'] == "Searching") {
			if ($_REQUEST['cat']) {
				$satu = 'AND id_polis = "' . $_REQUEST['cat'] . '"';
			}
		//if ($_REQUEST['nodn'])			{	$dua = 'AND id_dn = "' . $_REQUEST['nodn'] . '"';		} //before edit by hansen 27-06-2016
			if ($_REQUEST['nodn']) {
				$dua = 'AND id_dn = (SELECT id FROM fu_ajk_dn where dn_kode = "' . $_REQUEST['nodn'] . '" ) ';
			}// update by hansen 27-06-2016
			if ($_REQUEST['rnama']) {
				$tiga = 'AND nama LIKE "%' . $_REQUEST['rnama'] . '%"';
			}
			$dob = explode("-", $_REQUEST['rdob']);
			$dobpeserta = $dob[2] . '/' . $dob[1] . '/' . $dob[0];
			if ($_REQUEST['rdob']) {
				$empat = 'AND tgl_lahir LIKE "%' . $dobpeserta . '%"';
			}

			$cekpolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="' . $_REQUEST['cat'] . '"'));

			echo '<table border="0" width="100%" cellpadding="3" cellspacing="1">
	  	<tr><th width="1%">No</th>
	  	  <th>Nama Perusahaan</th>
	  	  <th>Asuransi</th>
	  	  <th width="1%">Nomor Polis</th>
	  	  <th width="10%">Nomor DN</th>
	  	  <th width="1%">ID Peserta</th>
	  	  <th width="15%">Nama</th>
	  	  <th width="1%">DOB</th>
	  	  <th width="1%">Usia</th>
	  	  <th width="1%">Kredit Awal</th>
	  	  <th width="1%">Tenor</th>
	  	  <th width="1%">Kredit Akhir</th>
	  	  <th width="1%">U P</th>
	  	  <th width="5%">Premi</th>
	  	  <th width="5%">Cabang</th>
	  	  <th width="1%">Proses</th>
	  	</tr>';
			if ($_REQUEST['x']) {
				$m = ($_REQUEST['x'] - 1) * 25;
			} else {
				$m = 0;
			}
			$cekpeserta = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id !="" ' . $satu . ' ' . $dua . ' ' . $tiga . ' ' . $empat . ' AND status_aktif="Inforce" ORDER BY id_cost ASC, nama ASC, id_dn ASC LIMIT ' . $m . ' , 25');
			$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_peserta WHERE id !="" ' . $satu . ' ' . $dua . ' ' . $tiga . ' ' . $empat . ' AND status_aktif="Inforce"'));
			$totalRows = $totalRows[0];
			$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
			while ($xpeserta = mysql_fetch_array($cekpeserta)) {
				$xperusahaan = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="' . $xpeserta['id_cost'] . '"'));
				$xpolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="' . $xpeserta['id_polis'] . '"'));
				$xdn = mysql_fetch_array($database->doQuery('SELECT id, dn_kode FROM fu_ajk_dn WHERE id="' . $xpeserta['id_dn'] . '"'));
				$xAsuransi = mysql_fetch_array($database->doQuery('SELECT id, id_bank, id_polis, id_asuransi, id_polis_as, id_dn, id_peserta FROM fu_ajk_peserta_as WHERE id_bank="' . $xpeserta['id_cost'] . '" AND id_polis="' . $xpeserta['id_polis'] . '" AND id_peserta="' . $xpeserta['id_peserta'] . '"'));
				$xAsuransi_ = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_asuransi WHERE id="' . $xAsuransi['id_asuransi'] . '"'));
				if (($no % 2) == 1) $objlass = 'tbl-odd'; else                $objlass = 'tbl-even';
				echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
			  <td align="center">' . (++$no + ($pageNow - 1) * 25) . '</td>
			  <td>' . $xperusahaan['name'] . '</td>
			  <td>' . $xAsuransi_['name'] . '</td>
			  <td align="center">' . $xpolis['nopol'] . '</td>
			  <td>' . $xdn['dn_kode'] . '</td>
			  <td align="center">' . $xpeserta['id_peserta'] . '</td>
			  <td>' . $xpeserta['nama'] . '</td>
			  <td align="center">' . $xpeserta['tgl_lahir'] . '</td>
			  <td align="center">' . $xpeserta['usia'] . '</td>
			  <td align="center">' . $xpeserta['kredit_tgl'] . '</td>
			  <td align="center">' . $xpeserta['kredit_tenor'] . '</td>
			  <td align="center">' . $xpeserta['kredit_akhir'] . '</td>
			  <td align="right">' . duit($xpeserta['kredit_jumlah']) . '</td>
			  <td align="right">' . duit($xpeserta['totalpremi']) . '</td>
			  <td>' . $xpeserta['cabang'] . '</td>
			  <td align="center"><a href="ajk_claim.php?d=dproses&id=' . $xpeserta['id'] . '"><img src="image/doc_death.png" width="20"></a></td>
			  </tr>';
			}
			echo '<tr><td colspan="22">';
			echo createPageNavigations($file = 'ajk_claim.php?d=dx&dm=Searching&cat=' . $_REQUEST['cat'] . '&rnama=' . $_REQUEST['rnama'] . '&nodn=' . $_REQUEST['nodn'] . '&rdob=' . $_REQUEST['rdob'] . '', $total = $totalRows, $psDeh = 10, $anchor = '', $perPage = 25);
			echo '<b>Total Data Peserta: <u>' . $totalRows . '</u></b></td></tr>';
			echo '</table>';
		};
	break;

	case "wklaim":
		echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
		<tr><th colspan="8" width="100%" align="left" colspan="2">Modul Klaim Meninggal Worksheet</font></th>
			<th width="5%" colspan="2"><a title="kembali ke halaman sebelumnya" href="ajk_claim.php"><img src="image/back.png" width="20"></a></th>
			<!--<th width="5%" colspan="2"><a title="print form klaim" href="ajk_report_fu.php?fu=formkliam&idp=' . $_REQUEST['id'] . '" target="_blank"><img src="image/print.png" width="20"></a>-->
		</th></tr>
		</table><br />';
		$met_klaim = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cn WHERE id="' . $_REQUEST['id'] . '"'));
		$met_klaim_ = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_klaim WHERE id_cost="' . $met_klaim['id_cost'] . '" AND id_dn="' . $met_klaim['id_dn'] . '" AND id_cn="' . $met_klaim['id'] . '" AND id_peserta="' . $met_klaim['id_peserta'] . '"'));
		$met_klaim_cost = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_costumer WHERE id="' . $met_klaim['id_cost'] . '"'));
		$met_klaim_polis = mysql_fetch_array($database->doQuery('SELECT id, id_cost, nopol, nmproduk FROM fu_ajk_polis WHERE id="' . $met_klaim['id_nopol'] . '" AND id_cost="' . $met_klaim['id_cost'] . '"'));
		$met_klaim_peserta = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_cost="' . $met_klaim['id_cost'] . '" AND id_polis="' . $met_klaim['id_nopol'] . '" AND id_peserta="' . $met_klaim['id_peserta'] . '" AND id_dn="' . $met_klaim['id_dn'] . '" AND id_klaim="' . $met_klaim['id'] . '" AND status_peserta="Death"'));
		$met_klaim_dn = mysql_fetch_array($database->doQuery('SELECT id, id_cost, id_nopol, dn_kode, dn_status, tgl_createdn FROM fu_ajk_dn WHERE id_cost="' . $met_klaim['id_cost'] . '" AND id_nopol="' . $met_klaim['id_nopol'] . '" AND id="' . $met_klaim['id_dn'] . '"'));
		$met_penyakit = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_namapenyakit WHERE id="' . $met_klaim['nmpenyakit'] . '"'));

		$mets = datediff($met_klaim_peserta['kredit_tgl'], $met_klaim['tgl_claim']);
		$usiapolis = explode(",", $mets);
		echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
	  <tr><td width="20%">Nama Perusahaan</td><td colspan="3">: ' . $met_klaim_cost['name'] . '</td></tr>
	  <tr><td>Nama</td><td width="50%">: ' . $met_klaim_peserta['nama'] . '</td><td width="15%">Nama Produk</td><td>: ' . $met_klaim_polis['nmproduk'] . ' '.$met_klaim_peserta['spaj'].'</td></tr>
	  <tr><td>Id Peserta</td><td>: ' . $met_klaim_peserta['id_peserta'] . '</td><td>Debit Note</td><td>: ' . $met_klaim_dn['dn_kode'] . '</td></tr>
	  <tr><td>Tanggal Lahir</td><td>: ' . _convertDate($met_klaim_peserta['tgl_lahir']) . '</td><td>Credit Note</td><td>: ' . $met_klaim['id_cn'] . '</td></tr>
	  <tr><td>Usia</td><td>: ' . $met_klaim_peserta['usia'] . ' tahun</td><td>Status Pembayaran</td><td>: ' . strtoupper($met_klaim_dn['dn_status']) . '</td></tr>
	  <tr><td>Uang Pertanggungan</td><td>: ' . duit($met_klaim_peserta['kredit_jumlah']) . '</td><td>Tanggal Pembayaran DN</td><td>: ' . _convertDate($met_klaim_dn['tgl_createdn']) . '</td></tr>
	  <tr><td>Tanggal Mulai Asuransi</td><td>: ' . _convertDate($met_klaim_peserta['kredit_tgl']) . '</td><td>Nilai Outstanding</td><td>: ' . duit($met_klaim['tuntutan_klaim']) . '</td></tr>
	  <tr><td>Tanggal Akhir Asuransi</td><td>: ' . _convertDate($met_klaim_peserta['kredit_akhir']) . '</td><td>Tanggal Meninggal</td><td>: ' . _convertDate($met_klaim['tgl_claim']) . '</td></tr>
	  <tr><td>Tenor</td><td>: ' . $met_klaim_peserta['kredit_tenor'] . ' Bulan</td><td>Usia Polis</td><td>: ' . $usiapolis[0] . ' Tahun ' . $usiapolis[1] . ' Bulan ' . $usiapolis[2] . ' Hari</td></tr>
	  <tr><td>Lokasi Meninggal</td><td>: ' . $met_klaim_['tempat_meninggal'] . '</td></tr>
	  <tr><td>Penyebab Meninggal</td><td colspan="3">: ' . $met_penyakit['namapenyakit'] . '</td></tr>
	  <!--<tr><td>Keterangan Diagnosa</td><td colspan="3">: ' . $met_klaim_['diagnosa'] . '</td></tr>
	  <tr><td>Keterangan Dokter Adonai</td><td colspan="3">: ' . $met_klaim_['ket_dokter'] . '</td></tr> -->
		<!-- <tr><td>Status Liability</td><td colspan="3">: ' . $met_klaim['policy_liability'] . '</td></tr> -->
	  </table><br />';
	  	/*
		echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
	  <th width="1%">No</th><th width="5%">Option</th><th>Form Klaim</th></tr>';
		$met_dok = $database->doQuery('SELECT
		fu_ajk_dokumenklaim_bank.id,
		fu_ajk_dokumenklaim_bank.id_bank,
		fu_ajk_dokumenklaim_bank.id_produk,
		fu_ajk_dokumenklaim_bank.id_dok,
		fu_ajk_dokumenklaim.nama_dok
		FROM
		fu_ajk_dokumenklaim_bank
		INNER JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim_bank.id_dok = fu_ajk_dokumenklaim.id WHERE fu_ajk_dokumenklaim_bank.id_bank="' . $met_klaim['id_cost'] . '" AND fu_ajk_dokumenklaim_bank.id_produk="' . $met_klaim['id_nopol'] . '" ORDER BY fu_ajk_dokumenklaim.urut ASC');
		while ($met_dok_ = mysql_fetch_array($met_dok)) {
			$met_dokumen = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_klaim_doc WHERE id_cost="' . $met_klaim['id_cost'] . '" AND id_pes="' . $met_klaim['id_peserta'] . '" AND dokumen="' . $met_dok_['id'] . '"'));

			if ($met_dokumen['dokumen']) {
				$chekdokumen = '<input type="checkbox" value="' . $met_dokumen['dokumen'] . '" checked disabled>';
			} else {
				$chekdokumen = '<input type="checkbox" disabled>';
			}
			if (($no % 2) == 1) $objlass = 'tbl-odd'; else                $objlass = 'tbl-even';
			echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
				  <td align="center">' . ++$no . '</td>
				  <td align="center">' . $chekdokumen . '</td>
				  <td>' . $met_dok_['nama_dok'] . '</td>
				  </tr>';
		}
		echo '</table>';
		*/
		if ($_REQUEST['notew']=="notewklaim") {
			echo '<hr>';
			$docklaim = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_klaim_doc WHERE id="'.$_REQUEST['iddok'].'"'));
			if ($_REQUEST['ope']=="Simpan") {
				//if ($_REQUEST['_tgldok']=="")  $error_1 ='<blink><font color=red>Tanggal dokumen tidak boleh kosong</font></blink><br>';
				if ($_REQUEST['_ketdok']=="")  $error_2 ='<blink><font color=red>Keterangan dokumen tidak boleh kosong</font></blink>';
				if ($error_1 OR $error_2) {
				}else{
					if($_REQUEST['statusdokumenasuransi']!=""){
						$statusupdate = $_REQUEST['statusdokumenasuransi'];
						$tglupdateas = $futoday;
					}

					$jangkrikbos = $database->doQuery('UPDATE fu_ajk_klaim_doc
																						 SET tgl_dokumen="'.$_REQUEST['_tgldok'].'",
																						 		ket_dokumen="'.$_REQUEST['_ketdok'].'",
																						 		statusdokumenasuransi="'.$statusupdate.'",
																						 		tglstatus = "'.$tglupdateas.'"
																						 WHERE id="'.$_REQUEST['iddok'].'"');
					header('location:ajk_claim.php?d=wklaim&id='.$_REQUEST['id'].'');
				}
			}
			echo '<form method="post" action="">
				  <table border="0" cellpadding="3" cellspacing="0" width="100%">
				  <tr><td width=20%">Tanggal Dokumen (Hardcopy)</td><td>';
			print initCalendar();
			print calendarBox('_tgldok', 'triger', $docklaim['tgl_dokumen']);
			echo  ''.$error_1.'</td></tr>
				  <tr>
					  <td width=20%" valign="top">Keterangan Dokumen <font color="red">*<font></td>
					  <td><textarea name="_ketdok" type="text"rows="3" cols="60" placeholder="Keterangan Dokumen">' . $docklaim['ket_dokumen'] . '</textarea>'.$error_2.'</td>
				  </tr>
				  <tr>
				  	<td width=20%" valign="top">View Asuransi</td>
				  	<td>
				  		<select id="statusdokumenasuransi" name="statusdokumenasuransi">
				  			<option value="">- Pilih -</option>
				  			<option value="Verifikasi">Verifikasi</option>
				  			<option value="Release">Release</option>
				  		</select>
				  	</td>
				  </tr>
				  <tr><td colspan="2"><input type="submit" name="ope" value="Simpan" class="button" /></td></tr></table>
				  </form>';
		}

		if ($_REQUEST['upddok']=="appdok") {
			$metApp = $database->doQuery('UPDATE fu_ajk_klaim_doc SET status="close" WHERE id="'.$_REQUEST['idok'].'"');
			header('location:ajk_claim.php?d=wklaim&id='.$_REQUEST['id'].'');
		}
		echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
	  <tr><th width="1%">No</th>
	  	  <th width="5%">Option</th>
	  	  <th width="10%">User Input</th>
	  	  <th>Nama File Upload</th>
	  	  <th width="20%">Keterangan Dokumen</th>
	  	  <th width="8%">Tgl Keterangan Dokumen</th>
	  	  <th width="13%">Tanggal Input Dokumen</th>
	  	  <th width="1%">Approve</th>
	  	  <th width="1%">View Asuransi</th>
	  </tr>';
		$mamet_dokumen = $database->doQuery('SELECT fu_ajk_klaim_doc.*,DATE_FORMAT(fu_ajk_klaim_doc.update_date, "%d %M %Y %H:%i:%s") AS update_date, DATE_FORMAT(fu_ajk_klaim_doc.tgl_dokumen, "%d %M %Y %H:%i:%s") AS tglketdokumen FROM fu_ajk_klaim_doc INNER JOIN fu_ajk_dokumenklaim_bank ON fu_ajk_dokumenklaim_bank.id = fu_ajk_klaim_doc.dokumen INNER JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim.id = fu_ajk_dokumenklaim_bank.id_dok WHERE id_cost="' . $met_klaim['id_cost'] . '" AND id_pes="' . $met_klaim['id_peserta'] . '" AND id_dn="' . $met_klaim['id_dn'] . '" AND id_klaim="' . $met_klaim['id'] . '" AND nama_dokumen !="" ORDER BY fu_ajk_dokumenklaim.urut ASC');
		while ($er_dokumen = mysql_fetch_array($mamet_dokumen)) {
			if (($no1 % 2) == 1) $objlass = 'tbl-odd'; else    $objlass = 'tbl-even';
			if (!is_numeric($jumlahID_)) {
				$jangkrik = mysql_fetch_array($database->doQuery('SELECT id, nm_user FROM pengguna WHERE id="'.$er_dokumen['update_by'].'"'));
				$jangkrik_ = $jangkrik['nm_user'];
			}else{
				$jangkrik_ = $er_dokumen['update_by'];
			}

			if ($er_dokumen['nama_dokumen'] !="" AND ($er_dokumen['tgl_dokumen'] !="" AND $er_dokumen['tgl_dokumen'] !="0000-00-00") ) {
				if ($er_dokumen['status']=="open") {
					$metApprove = '<a href="ajk_claim.php?d=wklaim&id='.$_REQUEST['id'].'&upddok=appdok&idok='.$er_dokumen['id'].'" title="validasi data dokumen klaim" onClick="if(confirm(\'Data dokumen yang sudah di setujui tidak bisa di edit atau di hapus, apakah anda sudah yakin ?\')){return true;}{return false;}"><img src="image/save.png" width="25"></a>';
					$updateDok = '<a title="keterangan dokumen klaim" href="ajk_claim.php?d=wklaim&notew=notewklaim&id='.$_REQUEST['id'].'&iddok='.$er_dokumen['id'].'"><img src="image/edit.png" width="25"></a>';
				}else{
					$metApprove = '<img src="image/nonsave.png" width="25">';
					$delDok = '<img src="image/deletedoff.png" width="25"></a>';
				}

			}else{
				$metApprove = '';
				$delDok = '<a title="hapus dokumen klaim" href="ajk_claim.php?d=del_dok&idp=' . $met_klaim['id'] . '&id_dok=' . $er_dokumen['id'] . '" onClick="if(confirm(\'Apakah anda yakin akan menghapus data dokumen ini ?\')){return true;}{return false;}"><img src="image/deleted.png" width="25"></a>';
				$updateDok = '<a title="keterangan dokumen klaim" href="ajk_claim.php?d=wklaim&notew=notewklaim&id='.$_REQUEST['id'].'&iddok='.$er_dokumen['id'].'"><img src="image/edit.png" width="25"></a>';
			}

		if ($er_dokumen['status']=="close") {	$_metstatusdok = "<b>Close File</b>";	}else {	$_metstatusdok = "";	}
			echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
				  <td align="center" valign="top">' . ++$no1 . '</td>
				  <td align="center" valign="top">'.$updateDok.' &nbsp; '.$delDok.'</td>
				  <td align="center" valign="top">' . $jangkrik_ . '</td>
				  <!--<td><a title="Lihat dokumen klaim" href="' . $dok_klaim_ajk . $er_dokumen['nama_dokumen'] . '" target="_blank">' . $er_dokumen['nama_dokumen'] . '<br /><embed src="' . $dok_klaim_ajk . $er_dokumen['nama_dokumen'] . '" type="application/pdf" width="500"/></td>-->
				  <td><a title="Lihat dokumen klaim" href="' . $dok_klaim_ajk . $er_dokumen['nama_dokumen'] . '" target="_blank">' . $er_dokumen['nama_dokumen'] . '</a></td>
				  <td valign="top"><b>'.$_metstatusdok.' ' . $er_dokumen['ket_dokumen'] . '</b></td>
				  <td align="center" valign="top">' . $er_dokumen['tglketdokumen'] . '</td>
				  <td align="center" valign="top">' . $er_dokumen['update_date'] . '</td>
				  <td align="center" valign="top">' . $metApprove . '</td>
				  <td align="center" valign="top">' . $er_dokumen['statusdokumenasuransi'] . '</td>
				  </tr>';

		}
		echo '</table>';
		;
	break;

	case "InvKlaim":
		echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
		<tr><th colspan="8" width="100%" align="left" colspan="2">Modul Klaim Investigasi Order</font></th>
			<th width="5%" colspan="2"><a title="kembali ke halaman sebelumnya" href="ajk_claim.php"><img src="image/back.png" width="20"></a></th>
		</tr>
		</table><br />';
		$met_klaim = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cn WHERE id="' . $_REQUEST['id'] . '"'));
		$met_klaim_ = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_klaim WHERE id_cost="' . $met_klaim['id_cost'] . '" AND id_dn="' . $met_klaim['id_dn'] . '" AND id_cn="' . $met_klaim['id_cn'] . '" AND id_peserta="' . $met_klaim['id_peserta'] . '"'));
		$met_klaim_cost = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_costumer WHERE id="' . $met_klaim['id_cost'] . '"'));
		$met_klaim_polis = mysql_fetch_array($database->doQuery('SELECT id, id_cost, nopol, nmproduk FROM fu_ajk_polis WHERE id="' . $met_klaim['id_nopol'] . '" AND id_cost="' . $met_klaim['id_cost'] . '"'));
		$met_klaim_peserta = mysql_fetch_array($database->doQuery('SELECT *, IF(type_data="SPK", kredit_tenor * 12, kredit_tenor) AS tenor FROM fu_ajk_peserta WHERE id_cost="' . $met_klaim['id_cost'] . '" AND id_polis="' . $met_klaim['id_nopol'] . '" AND id_peserta="' . $met_klaim['id_peserta'] . '" AND id_dn="' . $met_klaim['id_dn'] . '" AND id_klaim="' . $met_klaim['id'] . '" AND status_peserta="Death"'));
		$met_klaim_dn = mysql_fetch_array($database->doQuery('SELECT id, id_cost, id_nopol, dn_kode, dn_status, tgl_dn_paid FROM fu_ajk_dn WHERE id_cost="' . $met_klaim['id_cost'] . '" AND id_nopol="' . $met_klaim['id_nopol'] . '" AND id="' . $met_klaim['id_dn'] . '"'));
		$met_penyakit = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_namapenyakit WHERE id="' . $met_klaim['nmpenyakit'] . '"'));
		if ($met_klaim['confirm_claim'] == "Pending") {
			$sendtoadonai = '<th width="5%" colspan="2"><a title="Edit Klaim" href="ajk_klaim.php?er=eKlaim&idc=' . $met_klaim['id'] . '"><img src="image/book-edit.png" width="20"></a></th>';
		} else {
			$sendtoadonai = '';
		}

		$mets = datediff($met_klaim_peserta['kredit_tgl'], $met_klaim['tgl_claim']);
		$usiapolis = explode(",", $mets);
		echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
	  <tr><td width="20%">Nama Perusahaan</td><td colspan="3">: ' . $met_klaim_cost['name'] . '</td></tr>
	  <tr><td>Nama</td><td width="50%">: ' . $met_klaim_peserta['nama'] . '</td>
		  <td width="15%">Nama Produk</td><td>: ' . $met_klaim_polis['nmproduk'] . '</td>
	  </tr>
	  <tr><td>Id Peserta</td><td>: ' . $met_klaim_peserta['id_peserta'] . '</td>
		  <td>Debit Note</td><td>: ' . $met_klaim_dn['dn_kode'] . '</td>
	  </tr>
	  <tr><td>Tanggal Lahir</td><td>: ' . _convertDate($met_klaim_peserta['tgl_lahir']) . '</td>
		  <td>Tanggal Bayar Debitnote</td><td>: ' . _convertDate($met_klaim_dn['tgl_dn_paid']) . '</td>
	  </tr>
	  <tr><td>Usia</td><td>: ' . $met_klaim_peserta['usia'] . ' tahun</td>
		  <td>Credit Note</td><td>: ' . $met_klaim['id_cn'] . '</td>
	  </tr>
	  <tr><td>Uang Pertanggungan</td><td>: ' . duit($met_klaim_peserta['kredit_jumlah']) . '</td>
		  <td>Tanggal Bayar Creditnote</td><td>: ' . _convertDate($met_klaim['tgl_byr_claim']) . '</td>
	  </tr>
	  <tr><td>Tanggal Mulai Asuransi</td><td>: ' . _convertDate($met_klaim_peserta['kredit_tgl']) . '</td>
		  <td>Nilai Outstanding</td><td>: ' . duit($met_klaim['total_claim']) . '</td>
	  </tr>
	  <tr><td>Tanggal Akhir Asuransi</td><td>: ' . _convertDate($met_klaim_peserta['kredit_akhir']) . '</td>
		  <td>Tanggal Meninggal</td><td>: ' . _convertDate($met_klaim['tgl_claim']) . '</td>
	  </tr>
	  <tr><td>Tenor</td><td>: ' . $met_klaim_peserta['tenor'] . ' Bulan</td>
		  <td>Usia Polis</td><td>: ' . $usiapolis[0] . ' Tahun ' . $usiapolis[1] . ' Bulan ' . $usiapolis[2] . ' Hari</td>
	  </tr>
	  <tr><td>Penyabab Meninggal </td><td>: ' . $met_penyakit['namapenyakit'] . '</td>
		  <td>Nomor Perjanjian Kredit</td><td>: <b>' . $met_klaim['noperkredit'] . '</b>
	  </tr>
	  </table><br />';
		if ($_REQUEST['opp'] == "KirimInvestigasi") {
			if ($_REQUEST['alamatahliwaris'] == "") {
				$error1 = "<font color=red>Alamat ahli wris tidak boleh kosong.</font><br />";
			}
			if ($_REQUEST['tlpahliwaris'] == "") {
				$error2 = "<font color=red>Telpon ahli wris tidak boleh kosong.</font><br />";
			}
			if ($_REQUEST['namadokternya'] == "") {
				$error3 = "<font color=red>Silahkan pilih nama dokter.</font><br />";
			}
			if ($_REQUEST['namaahliwaris'] == "") {
				$error4 = "<font color=red>Nama ahli waris tidak boleh kosong.</font><br />";
			}
			//if ($_REQUEST['nomorverbal'] == "") 		{	$error4 = "<font color=red>Nomor keterangan verbal autopsi tidak boleh kosong.</font><br />";	}
			//if ($_FILES['fileverbal']['name'] == "") 	{	$error5 = "<font color=red>Silahkan upload dokumen verbal autopsi.</font><br />";	}
			//if(!in_array($_FILES['fileverbal']['type'], $allowedExts))	{	$error6 .='<font color="red">File harus Format PDF atau JPG !.</font><br />';	}
			//if ($_FILES['fileverbal']['size'] / 1024 > $met_spaksize)		{	$error7 .='<font color=red>File tidak boleh lebih dari 2Mb !.</font>';	}
			if ($error1 OR $error2 OR $error3) {
			} else {
				$metCN_ = mysql_fetch_array($database->doQuery('SELECT id, id_cn FROM fu_ajk_cn WHERE id="' . $_REQUEST['idv'] . '"'));
				$metCNOrder_ = mysql_fetch_array($database->doQuery('SELECT id, idcn FROM ajk_order_klaim WHERE idcn="' . $_REQUEST['idv'] . '"'));
				$nomorOrder = 100000 + $metCNOrder_['id'];
				$datelog = date("Y-m-d");
				$datelog_ = explode("-", $datelog);
				$nomorOrder_ = substr($datelog_[0], 2) . '' . $datelog_[1] . '' . substr($nomorOrder, 1);

				$metUpdateInvestigasi = $database->doQuery('UPDATE ajk_order_klaim SET iddokter="' . $_REQUEST['namadokternya'] . '",
																	   ahliwarisalamat="' . $_REQUEST['alamatahliwaris'] . '",
																	   ahliwaristelp="' . $_REQUEST['tlpahliwaris'] . '",
																	   namaahliwaris="' . strtoupper($_REQUEST['namaahliwaris']) . '",
																	   nmrorder="' . $nomorOrder_ . '",
																	   status="Investigasi",
																	   update_by="' . $q['nm_user'] . '",
																	   update_date="' . $futgl . '"
											WHERE idcn="' . $_REQUEST['idv'] . '"');

				$met_updateCN = $database->doQuery('UPDATE fu_ajk_cn SET confirm_claim="Investigasi" WHERE id="' . $_REQUEST['idv'] . '"');
				//move_uploaded_file(filename, destination)loaded_file($_FILES['fileverbal']['tmp_name'], $dok_klaim_ajk . $nomorOrder_.'_'.$_FILES["fileverbal"]["name"]);
				$mail = new PHPMailer; // call the class
				$mail->IsSMTP();
				$mail->Host = SMTP_HOST; //Hostname of the mail server
				$mail->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
				$mail_dokter = mysql_fetch_array($database->doQuery('SELECT * FROM user_mobile WHERE id="' . $_REQUEST['namadokternya'] . '"'));
				$mail->AddAddress($mail_dokter['email'], $mail_dokter['nm_lengkap']); //To address who will receive this email
				$mail->SetFrom($q['email'], $q['nm_lengkap']); //From address of the mail
				$mail->Subject = "AJKOnline - PENGAJUAN INVESTIGASI KLAIM"; //Subject od your mail
				$mail->AddCC("kepodank@gmail.com");

				$message .= 'To ' . $mail_dokter['nama'] . ',<br />Pengajuan Investigasi Klaim oleh ' . $q['nm_lengkap'] . ' dengan nomor Order Klaim <b>' . $nomorOrder_ . '</b> berdasarkan data peserta klaim sebagai berikut:<br />
				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				<tr><td width="20%">Nama Perusahaan</td><td colspan="3">: ' . $met_klaim_cost['name'] . '</td></tr>
				<tr><td>Nama</td><td width="50%">: ' . $met_klaim_peserta['nama'] . '</td><td width="15%">Nama Produk</td><td>: ' . $met_klaim_polis['nmproduk'] . '</td></tr>
				<tr><td>Id Peserta</td><td>: ' . $met_klaim_peserta['id_peserta'] . '</td><td>Debit Note</td><td>: ' . $met_klaim_dn['dn_kode'] . '</td></tr>
				<tr><td>Tanggal Lahir</td><td>: ' . _convertDate($met_klaim_peserta['tgl_lahir']) . '</td><td>Credit Note</td><td>: ' . $met_klaim['id_cn'] . '</td></tr>
				<tr><td>Usia</td><td>: ' . $met_klaim_peserta['usia'] . ' tahun</td><td>Tanggal Bayar DN</td><td>: ' . _convertDate($met_klaim_peserta['tgl_bayar']) . '</td></tr>
				<tr><td>Uang Pertanggungan</td><td>: ' . duit($met_klaim_peserta['kredit_jumlah']) . '</td><td>Nilai Outstanding</td><td>: ' . duit($met_klaim['total_claim']) . '</td></tr>
				<tr><td>Tanggal Mulai Asuransi</td><td>: ' . _convertDate($met_klaim_peserta['kredit_tgl']) . '</td><td>Tanggal Meninggal</td><td>: <b>' . _convertDate($met_klaim['tgl_claim']) . '</b></td></tr>
				<tr><td>Tanggal Akhir Asuransi</td><td>: ' . _convertDate($met_klaim_peserta['kredit_akhir']) . '</td><td>Usia Polis</td><td>: ' . $usiapolis[0] . ' Tahun ' . $usiapolis[1] . ' Bulan ' . $usiapolis[2] . ' Hari</td></tr>
				<tr><td>Tenor</td><td>: ' . $met_klaim_peserta['kredit_tenor'] . ' Bulan</td><td>Nomor Perjanjian Kredit</td><td>: <b>' . $met_klaim['noperkredit'] . '</b></tr>
				</table><br />';

				$mail->MsgHTML($message); //Put your body of the message you can place html code here
				$send = $mail->Send(); //Send the mails
				//echo $message;
				echo '<center>Pengajuan klaim investigasi telah diemail ke bagian Dokter oleh sistem secara otomatis.</center><meta http-equiv="refresh" content="2;URL=ajk_claim.php">';
			}
		}
		echo '<form method="post" action="">
	  <input type="hidden" name="idv" value="' . $_REQUEST['id'] . '">
	  <table border="0" cellpadding="3" cellspacing="0" width="100%">
	  <tr><td width="20%">Nama Ahli Waris <font color="red">*</font></td><td><input type="text" name="namaahliwaris" value="' . $_REQUEST['namaahliwaris'] . '" placeholder="Nama Ahli Waris"> ' . $error4 . '</td></tr>
	  <tr><td valign="top">Alamat Ahli Waris <font color="red">*</font></td><td><textarea name="alamatahliwaris" type="text"rows="3" cols="60" placeholder="Alamat Ahli Waris">' . $_REQUEST['alamatahliwaris'] . '</textarea> ' . $error1 . '</td></tr>
	  <tr><td>Nomor Telepon Ahli Waris <font color="red">*</font></td><td><input type="text" name="tlpahliwaris" value="' . $_REQUEST['tlpahliwaris'] . '" placeholder="Telp Ahli Waris" maxlength="14" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"> ' . $error2 . '</td></tr>
	  <tr><td>Dokter Investigasi <font color="red">*</font></td><td>';
		$metDokter = $database->doQuery('SELECT * FROM user_mobile WHERE type="Dokter" AND idbank="' . $met_klaim['id_cost'] . '" and del is null');
		echo '<select id="cat" name="namadokternya">
		<option value="">---Pilih Dokter---</option>';
		while ($metDokter_ = mysql_fetch_array($metDokter)) {
			echo '<option value="' . $metDokter_['id'] . '"' . _selected($_REQUEST['namadokternya'], $metDokter_['id']) . '>' . $metDokter_['nama'] . '</option>';
		}
		echo '</select> ' . $error3 . '</td></tr>
		<!--  <tr><td>Nomor Keterangan Verbal Autopsi <font color="red">*</font></td><td><input type="text" name="nomorverbal" value="' . $_REQUEST['nomorverbal'] . '" placeholder="Nomor Verbal Autopsi"> ' . $error4 . '</td></tr>
	  <tr><td>Dokumen Verbal Autopsi <font color="red">*</font><br /><font size="2">Format file : .pdf/.jpg max 2Mb</font></td><td><input name="fileverbal" type="file" onchange="checkfile(this);" > ' . $error5 . ' ' . $error6 . ' ' . $error7 . '</td></tr> -->
	  <tr><td align="center"colspan="2"><input name="opp" type="hidden" value="KirimInvestigasi"><input name="upload" type="submit" value="Kirim Investigasi" tooltips="Kirim Email Ke Dokter untuk investigasi"></td></tr>
	  </table></form>';;
	break;

	case "eInvKlaim":
		echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
		<tr><th colspan="8" width="100%" align="left" colspan="2">Modul Klaim Investigasi Order - Edit</font></th>
			<th width="5%" colspan="2"><a title="kembali ke halaman sebelumnya" href="ajk_claim.php"><img src="image/back.png" width="20"></a></th>
		</tr>
		</table><br />';
		$met_klaim = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cn WHERE id="' . $_REQUEST['ido'] . '"'));
		$met_klaim_ = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_klaim WHERE id_cost="' . $met_klaim['id_cost'] . '" AND id_dn="' . $met_klaim['id_dn'] . '" AND id_cn="' . $met_klaim['id_cn'] . '" AND id_peserta="' . $met_klaim['id_peserta'] . '"'));
		$met_klaim_cost = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_costumer WHERE id="' . $met_klaim['id_cost'] . '"'));
		$met_klaim_polis = mysql_fetch_array($database->doQuery('SELECT id, id_cost, nopol, nmproduk FROM fu_ajk_polis WHERE id="' . $met_klaim['id_nopol'] . '" AND id_cost="' . $met_klaim['id_cost'] . '"'));
		$met_klaim_peserta = mysql_fetch_array($database->doQuery('SELECT *, IF(type_data="SPK", kredit_tenor * 12, kredit_tenor) AS tenor FROM fu_ajk_peserta WHERE id_cost="' . $met_klaim['id_cost'] . '" AND id_polis="' . $met_klaim['id_nopol'] . '" AND id_peserta="' . $met_klaim['id_peserta'] . '" AND id_dn="' . $met_klaim['id_dn'] . '" AND id_klaim="' . $met_klaim['id'] . '" AND status_peserta="Death"'));
		$met_klaim_dn = mysql_fetch_array($database->doQuery('SELECT id, id_cost, id_nopol, dn_kode, dn_status, tgl_dn_paid FROM fu_ajk_dn WHERE id_cost="' . $met_klaim['id_cost'] . '" AND id_nopol="' . $met_klaim['id_nopol'] . '" AND id="' . $met_klaim['id_dn'] . '"'));
		if ($met_klaim['confirm_claim'] == "Pending") {
			$sendtoadonai = '<th width="5%" colspan="2"><a title="Edit Klaim" href="ajk_klaim.php?er=eKlaim&idc=' . $met_klaim['id'] . '"><img src="image/book-edit.png" width="20"></a></th>';
		} else {
			$sendtoadonai = '';
		}

		$mets = datediff($met_klaim_peserta['kredit_tgl'], $met_klaim['tgl_claim']);
		$usiapolis = explode(",", $mets);
		echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
	  <tr><td width="20%">Nama Perusahaan</td><td colspan="3">: ' . $met_klaim_cost['name'] . '</td></tr>
	  <tr><td>Nama</td><td width="50%">: ' . $met_klaim_peserta['nama'] . '</td><td width="15%">Nama Produk</td><td>: ' . $met_klaim_polis['nmproduk'] . '</td></tr>
	  <tr><td>Id Peserta</td><td>: ' . $met_klaim_peserta['id_peserta'] . '</td><td>Debit Note</td><td>: ' . $met_klaim_dn['dn_kode'] . '</td></tr>
	  <tr><td>Tanggal Lahir</td><td>: ' . _convertDate($met_klaim_peserta['tgl_lahir']) . '</td><td>Credit Note</td><td>: ' . $met_klaim['id_cn'] . '</td></tr>
	  <tr><td>Usia</td><td>: ' . $met_klaim_peserta['usia'] . ' tahun</td><td>Tanggal Bayar DN</td><td>: ' . _convertDate($met_klaim_peserta['tgl_bayar']) . '</td></tr>
	  <tr><td>Uang Pertanggungan</td><td>: ' . duit($met_klaim_peserta['kredit_jumlah']) . '</td><td>Nilai Outstanding</td><td>: ' . duit($met_klaim['total_claim']) . '</td></tr>
	  <tr><td>Tanggal Mulai Asuransi</td><td>: ' . _convertDate($met_klaim_peserta['kredit_tgl']) . '</td><td>Tanggal Meninggal</td><td>: ' . _convertDate($met_klaim['tgl_claim']) . '</td></tr>
	  <tr><td>Tanggal Akhir Asuransi</td><td>: ' . _convertDate($met_klaim_peserta['kredit_akhir']) . '</td><td>Usia Polis</td><td>: ' . $usiapolis[0] . ' Tahun ' . $usiapolis[1] . ' Bulan ' . $usiapolis[2] . ' Hari</td></tr>
	  <tr><td>Tenor</td><td>: ' . $met_klaim_peserta['tenor'] . 'a Bulan</td><td>Nomor Perjanjian Kredit</td><td>: <b>' . $met_klaim['noperkredit'] . '</b></tr>
	  </table><br />';
		if ($_REQUEST['opp'] == "Simpan") {
			if ($_REQUEST['namaahliwaris'] == "") {
				$error4 = "<font color=red>Nama ahli waris tidak boleh kosong.</font><br />";
			}
			if ($_REQUEST['alamatahliwaris'] == "") {
				$error1 = "<font color=red>Alamat ahli waris tidak boleh kosong.</font><br />";
			}
			if ($_REQUEST['tlpahliwaris'] == "") {
				$error2 = "<font color=red>nomor telpon ahli waris tidak boleh kosong.</font><br />";
			}
			if ($_REQUEST['namadokternya'] == "") {
				$error3 = "<font color=red>Silahkan ppilih nama dokter.</font><br />";
			}
			if ($error1 OR $error2 OR $error3 OR $error4) {
			} else {
				$metUpd = $database->doQuery('UPDATE ajk_order_klaim SET namaahliwaris="' . strtoupper($_REQUEST['namaahliwaris']) . '",
															 ahliwarisalamat="' . $_REQUEST['alamatahliwaris'] . '",
															 ahliwaristelp="' . $_REQUEST['tlpahliwaris'] . '",
															 iddokter="' . $_REQUEST['namadokternya'] . '",
															 update_by="' . $q['nm_user'] . '",
															 update_date="' . $futgl . '"
									WHERE idcn="' . $_REQUEST['ido'] . '"');
				echo '<center>Data pengajuan klaim ahli waris telah diedit oleh ' . $q['nm_lengkap'] . ' pada tanggal ' . $futgl . '.</center><meta http-equiv="refresh" content="2;URL=ajk_claim.php">';
			}
		}
		$metOrderKlaim = mysql_fetch_array($database->doQuery('SELECT * FROM ajk_order_klaim WHERE idcn="' . $_REQUEST['ido'] . '"'));
		echo '<form method="post" action="">
	  <input type="hidden" name="idv" value="' . $_REQUEST['id'] . '">
	  <table border="0" cellpadding="3" cellspacing="0" width="100%">
	  <tr><td width="20%">Nama Ahli Waris <font color="red">*</font></td><td><input type="text" name="namaahliwaris" value="' . $metOrderKlaim['namaahliwaris'] . '" placeholder="Nama Ahli Waris"> ' . $error4 . '</td></tr>
	  <tr><td valign="top">Alamat Ahli Waris <font color="red">*</font></td><td><textarea name="alamatahliwaris" type="text"rows="3" cols="60" placeholder="Alamat Ahli Waris">' . $metOrderKlaim['ahliwarisalamat'] . '</textarea> ' . $error1 . '</td></tr>
	  <tr><td>Nomor Telepon Ahli Waris <font color="red">*</font></td><td><input type="text" name="tlpahliwaris" value="' . $metOrderKlaim['ahliwaristelp'] . '" placeholder="Telp Ahli Waris" maxlength="14" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"> ' . $error2 . '</td></tr>
	  <tr><td>Dokter Investigasi <font color="red">*</font></td><td>';
		$metDokter = $database->doQuery('SELECT * FROM user_mobile WHERE type="Dokter" AND idbank="' . $met_klaim['id_cost'] . '"');
		echo '<select id="cat" name="namadokternya">
		<option value="">---Pilih Dokter---</option>';
		while ($metDokter_ = mysql_fetch_array($metDokter)) {
			echo '<option value="' . $metDokter_['id'] . '"' . _selected($metOrderKlaim['iddokter'], $metDokter_['id']) . '>' . $metDokter_['nama'] . '</option>';
		}
		echo '</select> ' . $error3 . '</td></tr>
		<!--  <tr><td>Nomor Keterangan Verbal Autopsi <font color="red">*</font></td><td><input type="text" name="nomorverbal" value="' . $_REQUEST['nomorverbal'] . '" placeholder="Nomor Verbal Autopsi"> ' . $error4 . '</td></tr>
			  <tr><td>Dokumen Verbal Autopsi <font color="red">*</font><br /><font size="2">Format file : .pdf/.jpg max 2Mb</font></td><td><input name="fileverbal" type="file" onchange="checkfile(this);" > ' . $error5 . ' ' . $error6 . ' ' . $error7 . '</td></tr> -->
			  <tr><td align="center"colspan="2"><input name="opp" type="hidden" value="Simpan"><input name="upload" type="submit" value="Simpan"></td></tr>
			  </table></form>';;
	break;

	case "pembas" :
		echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
		<tr><th colspan="8" width="100%" align="left" colspan="2">Modul Klaim - Update Pembayaran Dari Asuransi</font></th><th width="5%" colspan="2"><a href="ajk_claim.php"><img src="image/Backward-64.png" width="20"></a></th></tr>
		</table><br />';
		echo '<fieldset>
		<legend>Searching</legend>
		<table border="0" width="50%" cellpadding="1" cellspacing="1" align="center">
	  <form method="post" action="">
	  <tr><td width="25%" align="right">Nama Produk</td>
	  <td width="30%">: <select id="cat" name="cat">
	  	<option value="">---Pilih Produk---</option>';
		$quer2 = $database->doQuery('SELECT * FROM fu_ajk_polis ORDER BY nopol ASC');
		while ($noticia2 = mysql_fetch_array($quer2)) {
			if ($noticia2['id'] == $cat) {
				echo '<option selected value="' . $noticia2['id'] . '">' . $noticia2['nmproduk'] . '</option><BR>';
			} else {
				echo '<option value="' . $noticia2['id'] . '">' . $noticia2['nmproduk'] . '</option>';
			}
		}
		echo '</select></td><td width="5%" align="right">Nama</td><td>: <input type="text" name="rnama" value="' . $_REQUEST['rnama'] . '"></td>
				<td rowspan="3" align="center">
					<a href="ajk_paid.php?r=paidupload_as1" title="upload data pembayaran perpeserta">
						<img src="image/rmf_2.png" width="25">
					</a>
				</td>
				</tr>
				<tr><td width="10%" align="right">ID Peserta</td><td width="20%">: <input type="text" name="id_peserta" value="' . $_REQUEST['id_peserta'] . '"></td>
			<td width="5%" align="right">DOB</td><td>: ';
		print initCalendar();
		print calendarBox('rdob', 'triger', $_REQUEST['rdob']);
		echo '</td></tr>
			<tr><td colspan="4" align="center"><input type="submit" name="dm" value="Searching" class="button"></td></tr>
			</form>
			</table></fieldset>';
		if ($_REQUEST['dm'] == "Searching") {
			if ($_REQUEST['cat']) {
				$satu = 'AND fu_ajk_peserta.id_polis = "' . $_REQUEST['cat'] . '"';
			}
			//if ($_REQUEST['nodn'])			{	$dua = 'AND id_dn = "' . $_REQUEST['nodn'] . '"';		} //before edit by hansen 27-06-2016
			if ($_REQUEST['id_peserta']) {
				$dua = 'AND fu_ajk_peserta.id_peserta = "' . $_REQUEST['id_peserta'] . '"';
			}// update by hansen 27-06-2016
			if ($_REQUEST['rnama']) {
				$tiga = 'AND fu_ajk_peserta.nama LIKE "%' . $_REQUEST['rnama'] . '%"';
			}
			$dob = explode("-", $_REQUEST['rdob']);
			$dobpeserta = $dob[2] . '/' . $dob[1] . '/' . $dob[0];
			if ($_REQUEST['rdob']) {
				$empat = 'AND fu_ajk_peserta.tgl_lahir LIKE "%' . $dobpeserta . '%"';
			}

			$cekpolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="' . $_REQUEST['cat'] . '"'));

			echo '<table border="0" width="100%" cellpadding="3" cellspacing="1">
	  	<tr><th width="1%">No</th>
	  	  <th>No Urut</th>
	  	  <th>Nama Perusahaan</th>
	  	  <th>Asuransi</th>
	  	  <th width="1%">Nomor Polis</th>
	  	  <th width="10%">Nomor DN</th>
	  	  <th width="1%">ID Peserta</th>
	  	  <th width="15%">Nama</th>
	  	  <th width="1%">DOB</th>
	  	  <th width="1%">Usia</th>
	  	  <th width="1%">Kredit Awal</th>
	  	  <th width="1%">Tenor</th>
	  	  <th width="1%">Kredit Akhir</th>
	  	  <th width="1%">Usia Polis</th>
	  	  <th width="1%">U P</th>
	  	  <th width="5%">Total Klaim Disetujui</th>
	  	  <th width="5%">Cabang</th>
	  	  <th width="5%">Status</th>
	  	  <th width="1%">Proses</th>
	  	</tr>';
			if ($_REQUEST['x']) {
				$m = ($_REQUEST['x'] - 1) * 25;
			} else {
				$m = 0;
			}
			$cekpeserta = $database->doQuery('SELECT fu_ajk_peserta.*,fu_ajk_klaim.id_cn,fu_ajk_klaim.no_urut_klaim,fu_ajk_klaim.id_klaim_status,fu_ajk_klaim.tuntutan_klaim,datediff(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl) as dol FROM fu_ajk_peserta INNER JOIN fu_ajk_klaim ON fu_ajk_peserta.id_klaim=fu_ajk_klaim.id_cn WHERE (fu_ajk_klaim.id_klaim_status=7 or fu_ajk_klaim.id_klaim_status=6 or fu_ajk_klaim.id_klaim_status=1 or fu_ajk_klaim.id_klaim_status=4 or fu_ajk_klaim.id_klaim_status=5) and fu_ajk_peserta.id !="" ' . $satu . ' ' . $dua . ' ' . $tiga . ' ' . $empat . ' AND fu_ajk_peserta.status_aktif="Lapse" ORDER BY fu_ajk_peserta.id_cost ASC, fu_ajk_peserta.nama ASC, fu_ajk_peserta.id_dn ASC LIMIT ' . $m . ' , 25');
			$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(fu_ajk_peserta.id) FROM fu_ajk_peserta INNER JOIN fu_ajk_klaim ON fu_ajk_peserta.id_klaim=fu_ajk_klaim.id_cn WHERE (fu_ajk_klaim.id_klaim_status=7 or fu_ajk_klaim.id_klaim_status=6 or fu_ajk_klaim.id_klaim_status=1 or fu_ajk_klaim.id_klaim_status=4 or fu_ajk_klaim.id_klaim_status=5) and fu_ajk_peserta.id !="" ' . $satu . ' ' . $dua . ' ' . $tiga . ' ' . $empat . ' AND fu_ajk_peserta.status_aktif="Lapse"'));
			$totalRows = $totalRows[0];
			$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
			while ($xpeserta = mysql_fetch_array($cekpeserta)) {
				$xperusahaan = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="' . $xpeserta['id_cost'] . '"'));
				$xpolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="' . $xpeserta['id_polis'] . '"'));
				$xdn = mysql_fetch_array($database->doQuery('SELECT id, dn_kode FROM fu_ajk_dn WHERE id="' . $xpeserta['id_dn'] . '"'));
				$xAsuransi = mysql_fetch_array($database->doQuery('SELECT id, id_bank, id_polis, id_asuransi, id_polis_as, id_dn, id_peserta FROM fu_ajk_peserta_as WHERE id_bank="' . $xpeserta['id_cost'] . '" AND id_polis="' . $xpeserta['id_polis'] . '" AND id_peserta="' . $xpeserta['id_peserta'] . '"'));
				$xAsuransi_ = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_asuransi WHERE id="' . $xAsuransi['id_asuransi'] . '"'));
				$xstatus = mysql_fetch_array($database->doQuery('SELECT status_klaim FROM fu_ajk_klaim_status WHERE id="' . $xpeserta['id_klaim_status'] . '"'));



			if (($no % 2) == 1) $objlass = 'tbl-odd'; else                $objlass = 'tbl-even';
			echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		  <td align="center">' . (++$no + ($pageNow - 1) * 25) . '</td>
		  <td>' . $xpeserta['no_urut_klaim'] . '</td>
		  <td>' . $xperusahaan['name'] . '</td>
		  <td>' . $xAsuransi_['name'] . '</td>
		  <td align="center">' . $xpolis['nopol'] . '</td>
		  <td>' . $xdn['dn_kode'] . '</td>
		  <td align="center">' . $xpeserta['id_peserta'] . '</td>
		  <td>' . $xpeserta['nama'] . '</td>
		  <td align="center">' . $xpeserta['tgl_lahir'] . '</td>
		  <td align="center">' . $xpeserta['usia'] . '</td>
		  <td align="center">' . $xpeserta['kredit_tgl'] . '</td>
		  <td align="center">' . $xpeserta['kredit_tenor'] . '</td>
		  <td align="center">' . $xpeserta['kredit_akhir'] . '</td>
		  <td align="center">' . $xpeserta['dol'] . '</td>
		  <td align="right">' . duit($xpeserta['kredit_jumlah']) . '</td>
		  <td align="right">' . duit($xpeserta['tuntutan_klaim']) . '</td>
		  <td>' . $xpeserta['cabang'] . '</td>
		  <td>'.$xstatus['status_klaim'].'</td>
		  <td align="center"><a href="ajk_claim.php?d=dpembas&id=' . $xpeserta['id'] . '"><img src="image/doc_death.png" width="20"></a></td>
		  </tr>';
			}
			echo '<tr><td colspan="22">';
			echo createPageNavigations($file = 'ajk_claim.php?d=pembas&dm=Searching&cat=' . $_REQUEST['cat'] . '&rnama=' . $_REQUEST['rnama'] . '&nodn=' . $_REQUEST['nodn'] . '&rdob=' . $_REQUEST['rdob'] . '', $total = $totalRows, $psDeh = 10, $anchor = '', $perPage = 25);
			echo '<b>Total Data Peserta: <u>' . $totalRows . '</u></b></td></tr>';
			echo '</table>';
		};
	break;

	case "dpembas" :

		$peserta = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id="' . $_REQUEST['id'] . '"'));
		$datadn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn WHERE dn_kode="' . $peserta['id_dn'] . '" AND id_cost="' . $peserta['id_cost'] . '"'));
		$ppolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="' . $peserta['id_polis'] . '"'));
		$pcostumer = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="' . $peserta['id_cost'] . '"'));
		$dataklaim = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_klaim WHERE id_dn="' . $peserta['id_dn'] . '"'));
		$dataklaim_ = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_klaim WHERE id_peserta="' . $peserta['id_peserta'] . '"'));
		$datacn = mysql_fetch_array($database->doQuery("SELECT ifnull(tgl_byr_claim,'') as tgl_byr_claim1,fu_ajk_cn.* FROM fu_ajk_cn WHERE id='" . $peserta['id_klaim'] . "'  and type_claim='Death' and del is null"));

		$tgl_usia_polis = datediff($peserta['kredit_tgl'], $datacn['tgl_claim']);
		$usiapolis = explode(",", $tgl_usia_polis);

		if(isset($_REQUEST['oop']) && $_REQUEST['oop']=='Save'){
			if (!$_REQUEST['tgl_bayar']) $error1 .= '<font color="red">Tanggal bayar tidak boleh kosong</font>.';
			if (!$_REQUEST['total_bayar']) $error2 .= '<font color="red"><blink>Klaim tidak bisa di proses, nilai klaim tidak boleh kosong!</blink></font>';
			if (!$_REQUEST['status_pembayaran']) $error3 .= '<font color="red"><blink>Status Klaim tidak boleh kosong!</blink></font>';

			if ($error1 OR $error2) {
			} else {
					$met_upload_data1 = $database->doQUery("update fu_ajk_cn set tgl_bayar_asuransi='".$_REQUEST['tgl_bayar']."', total_bayar_asuransi='".$_REQUEST['total_bayar']."',status_bayar='".$_REQUEST['status_pembayaran']."' where id_peserta='" . $peserta['id_peserta'] . "'");

					$met_upload_data2 = $database->doQUery("update fu_ajk_note_as set note_paid_date='".$_REQUEST['tgl_bayar']."', note_paid_total='".$_REQUEST['total_bayar']."',note_status='".$_REQUEST['status_pembayaran']."', note_reference='".$_REQUEST['ref']."', note_desc='".$_REQUEST['ket']."',update_by='".$_SESSION['nm_user']."',update_time=current_timestamp where id_peserta='" . $peserta['id_peserta'] . "'  and note_type='DNC'");

					if($datacn['tgl_byr_claim1']==''){
						$met_upload_data1 = $database->doQUery("update fu_ajk_klaim set id_klaim_status='5' where id_peserta='" . $peserta['id_peserta'] . "' and type_klaim='Death' and del is null");

					}else{
						$met_upload_data1 = $database->doQUery("update fu_ajk_klaim set id_klaim_status='1' where id_peserta='" . $peserta['id_peserta'] . "' and type_klaim='Death' and del is null");
					}

					if ($_FILES['userfile']['name'] == "") {
						$errno1 = "Silahkan upload file dokumen klaim <font color=red><b>" . $metdoknya['dokumen'] . "!</b></font><br />";
					}
					if ($_FILES['userfile']['name'] != "" AND $_FILES['userfile']['type'][$i] != "application/pdf") {
						$errno1 = "<font color=red>File " . $metdoknya['dokumen'] . " harus Format PDF !</font><br />";
					}
					if ($_FILES['userfile']['size'] / 1024 > $met_ttdsize) {
						$errno1 = "<font color=red>File " . $metdoknya['dokumen'] . " tidak boleh lebih dari 1Mb !</font><br />";
					}
					if ($errno1) {

						header("location:ajk_claim.php?d=pembas");
					} else {
						move_uploaded_file($_FILES['userfile']['tmp_name'][$i], $dok_klaim_ajk . 'BYR_AS_' . $metDokumen['id_peserta'] . '_' . $metDokumen['nama'] . '_' . $_FILES["userfile"]["name"][$i]);

						header("location:ajk_claim.php?d=pembas");
					}

			}
		}
		echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
		<tr><th colspan="8" width="100%" align="left" colspan="2">Modul Klaim - Proses Pemabayaran Klaim Dari Asuransi</font></th><th width="5%" colspan="2"><a href="ajk_claim.php?d=pembas"><img src="image/Backward-64.png" width="20"></a></th></tr>
		</table><br />';

		echo '<form method="post" action="">
	  <table border="0" cellpadding="3" cellspacing="0" width="80%" align="center" style="border: solid 1px #DEDEDE" align="center">
	  <input type="hidden" name="id" value="' . $_REQUEST['id'] . '">
	  <input type="hidden" name="kredit_tenor" value="' . $peserta['kredit_tenor'] . '">
		<tr><th colspan="4">Infromasi Data Klaim Meninggal</th></tr>
		<tr><td width="20%">Nama Perusahaan</td><td>: <b>' . $pcostumer['name'] . '</b></td>
			<td width="25%" align="right">Reg. ID</td><td>: <b>' . $peserta['id_peserta'] . '</b></td>
		</tr>
		<tr><td width="20%" coslpan="3">Nama Produk</td><td>: <b>' . $ppolis['nmproduk'] . '</b></td></tr>
		<tr><td>Name</td><td colspan="3">: <b>' . $peserta['nama'] . '</b></td></tr>
		<tr><td>Sum Insured</td><td colspan="3">: <b>' . duit($peserta['kredit_jumlah']) . '</b></td></tr>
		<tr><td>Period</td><td>: from <b>' . _convertDate($peserta['kredit_tgl']) . '</b> &nbsp; to : <b>' . _convertDate($peserta['kredit_akhir']) . '</b></td>
			<td align="right">Tenor</td><td>: <b>' . $peserta['kredit_tenor'] . '</b> Bulan</td>
		</tr>
		<tr><td>Total Claim <font color="red"><b>*</b></font></td><td>: ' . duit($dataklaim['jumlah']) . '</td>
			<td align="right">Max Claim</td><td>: <font color="blue"><b>' . duit($peserta['kredit_jumlah']) . '</b></font></td>
		</tr>
		<tr><td>Tanggal Meninggal <font color="red"><b>*</b></font></td><td>: ' . $dataklaim['tgl_klaim'];
			echo '</td>
		<td align="right">Ms Asuransi Berjalan</td><td>: <b>' . $maj . '</b> Bulan</td></tr>
		<tr><td>Tanggal Terima Laporan</td><td>: ' . $dataklaim['tgl_document'];
		echo '</td>
		<td align="right">Tanggal Kelengkapan Dokumen</td><td>: ' . $dataklaim['tgl_document_lengkap'];
		echo '</td>
				<tr><td>Tanggal Investigasi</td><td>: ' . $dataklaim['tgl_investigasi'];
		echo '</td>
				<td align="right"><b>Tanggal Bayar Klaim Ke Bank</b></td><td>: ' . $datacn['tgl_byr_claim'];
		echo '</td>
			</tr>
			<tr><td>Lokasi Meninggal <font color="red"><b>*</b></font></td><td>: ' . $dataklaim['tempat_meninggal'] . '</td>
			<td align="right">Tanggal Informasi Ke Asuransi</td><td>: ' . $dataklaim['tgl_lapor_klaim'];
		echo '</td></tr>
		<tr><td>Penyebab Meninggal <font color="red"><b>*</b></font><br />' . $error1 . '</td><td valign="top">: ';
		$tgl_kirim_dokumen='';
		if(!is_null( $dataklaim['sebab_meninggal'])) {
			$nmPenyakit = $database->doQuery('SELECT * FROM fu_ajk_namapenyakit where ' . $dataklaim['sebab_meninggal'] . ' ORDER BY id ASC');
			$nmPenyakit_ = mysql_fetch_array($nmPenyakit);
			$tgl_kirim_dokumen=$dataklaim['tgl_kirim_dokumen'];
		}
		echo $nmPenyakit_['namapenyakit'] . '</td>
				<td align="right">Tanggal Kirim Dokument Ke Asuransi</td><td>: '.$tgl_kirim_dokumen;

		echo '</td></tr>
		<tr><td valign="top">Keterangan Diagnosa</td>
		<td>: ' . $dataklaim['diagnosa'] . '</td>
		<td align="right"">Keterangan</td><td>: '.$dataklaim['ket'].'</td>
		</tr>
		<tr><!--<td valign="top">Keterangan Dokter Adonai</td><td>: ' . $dataklaim['ket_dokter'] . '</td>-->
		<td align="right"">Usia Polis</td><td>: ' . $usiapolis[0] . ' Tahun ' . $usiapolis[1] . ' Bulan ' . $usiapolis[2] . ' Hari</td>
		</tr>
		<tr><td valign="top">Status Klaim</td><td>: ';
		$status='';
		if (!is_null($dataklaim['id_klaim_status'])) {
			$nmPenyakit_ = mysql_fetch_array($nmPenyakit);
			$nmPenyakit = $database->doQuery('SELECT * FROM fu_ajk_klaim_status where id=' . $dataklaim['id_klaim_status'] . ' ORDER BY order_list ASC');
			$status=$nmPenyakit['status_klaim'];
		}
		echo $status.'</td>
								</tr>';
		if ($datadn['ket']!="") {	echo' <tr><td valign="top"><b>Keterangan Underwriting</b></td><td colspan="3">: '.$datadn['ket'].'</td></tr>';	}
		echo '<tr><th colspan="5">Informasi Pembayaran</th></tr>
			<tr><td>Tanggal Terima Klaim Dari Asuransi</td><td> : ';
			print initCalendar();
			//print calendarBox('tgl_bayar', 'triger6', $_REQUEST['tgl_bayar']); 20170524 HANSEN
			print calendarBox('tgl_bayar', 'triger6', $datacn['tgl_bayar_asuransi']);
			echo $error1.'<br></td></tr>';
		echo '<tr><td>Total Terima Klaim Dari Asuransi</td><td>: <input type="text" name="total_bayar" value="' . $dataklaim_['tuntutan_klaim'] . '" onkeypress="return isNumberKey(event)"></td></tr>
			<tr><td>Status Pembayaran<font color="red"><b>*</b></font><br />' . $error2 . '</td><td valign="top">:
			    <select size="1" name="status_pembayaran">
			   	<option value="">---Status Pembayaran---</option>';
				$nmPenyakit = $database->doQuery('SELECT * FROM fu_ajk_pembayaran_status where `type`="2" ORDER BY id ASC');
				while ($nmPenyakit_ = mysql_fetch_array($nmPenyakit)) {
					echo '<option value="' . $nmPenyakit_['id'] . '"' . _selected($datacn['status_bayar'], $nmPenyakit_['id']) . '>' . $nmPenyakit_['pembayaran_status'] . '</option>';
				}
				echo '</select>' . $error3 . '</td></tr>
			<tr><th colspan="5">Kelengkapan Dokumen</th></tr>
			<tr><td>Lampiran File</td><td><input name="userfile" type="file" size="50" onchange="checkfile(this);" >'.$errno1.'</td></tr>
			<tr><td>Referensi Bayar</td><td><textarea name="ref"  cols="40" rows="3">'.$_REQUEST['ref'].'</textarea></td></tr>
			<tr><td>Keterangan</td><td><textarea name="ket"  cols="40" rows="3">'.$_REQUEST['ket'].'</textarea></td></tr>
			<tr><td colspan="4" align="center"><input type="submit" name="oop" value="Save"></td></tr>

		</table></form>';
		;
	break;

	case "list_pembas" :

		echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
		<tr><th colspan="8" width="100%" align="left" colspan="2">Modul Klaim - Update Pembayaran Dari Asuransi</font></th><th width="5%" colspan="2"><a href="ajk_claim.php"><img src="image/Backward-64.png" width="20"></a></th></tr>
		</table><br />';
		echo '<fieldset>
		<legend>Searching</legend>
		<table border="0" width="50%" cellpadding="1" cellspacing="1" align="center">
	  <form method="post" action="">
	  <tr><td width="25%" align="right">Nama Produk</td>
	  <td width="30%">: <select id="cat" name="cat">
	  	<option value="">---Pilih Produk---</option>';
		$quer2 = $database->doQuery('SELECT * FROM fu_ajk_polis ORDER BY nopol ASC');
		while ($noticia2 = mysql_fetch_array($quer2)) {
			if ($noticia2['id'] == $cat) {
				echo '<option selected value="' . $noticia2['id'] . '">' . $noticia2['nmproduk'] . '</option><BR>';
			} else {
				echo '<option value="' . $noticia2['id'] . '">' . $noticia2['nmproduk'] . '</option>';
			}
		}
		echo '</select></td><td width="5%" align="right">Nama</td><td>: <input type="text" name="rnama" value="' . $_REQUEST['rnama'] . '"></td></tr>
				<tr><td width="10%" align="right">Nomor DN</td><td width="20%">: <input type="text" name="nodn" value="' . $_REQUEST['nodn'] . '"></td>
			<td width="5%" align="right">DOB</td><td>: ';
		print initCalendar();
		print calendarBox('rdob', 'triger', $_REQUEST['rdob']);
		echo '</td></tr>
			<tr><td colspan="4" align="center"><input type="submit" name="dm" value="Searching" class="button"></td></tr>
			</form>
			</table></fieldset>';
		if ($_REQUEST['dm'] == "Searching") {
			if ($_REQUEST['cat']) {
				$satu = 'AND fu_ajk_peserta.id_polis = "' . $_REQUEST['cat'] . '"';
			}
			//if ($_REQUEST['nodn'])			{	$dua = 'AND id_dn = "' . $_REQUEST['nodn'] . '"';		} //before edit by hansen 27-06-2016
			if ($_REQUEST['nodn']) {
				$dua = 'AND fu_ajk_peserta.id_dn = (SELECT id FROM fu_ajk_dn where dn_kode = "' . $_REQUEST['nodn'] . '" ) ';
			}// update by hansen 27-06-2016
			if ($_REQUEST['rnama']) {
				$tiga = 'AND fu_ajk_peserta.nama LIKE "%' . $_REQUEST['rnama'] . '%"';
			}
			$dob = explode("-", $_REQUEST['rdob']);
			$dobpeserta = $dob[2] . '/' . $dob[1] . '/' . $dob[0];
			if ($_REQUEST['rdob']) {
				$empat = 'AND fu_ajk_peserta.tgl_lahir LIKE "%' . $dobpeserta . '%"';
			}

			$cekpolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="' . $_REQUEST['cat'] . '"'));

			echo '<table border="0" width="100%" cellpadding="3" cellspacing="1">
			<tr><td bgcolor="#FFF"colspan="24"><a href="e_report.php?er=list_pembas&cat='.$_REQUEST['cat'].'&nodn='.$_REQUEST['nodn'].'&rnama='.$_REQUEST['rnama'].'&rdob='.$_REQUEST['rdob'].'"><img src="image/excel.png" width="25" border="0"><br />Excel</a></td></tr>
				<tr><th width="1%">No</th>
	  	  <th>Nama Perusahaan</th>
	  	  <th>Asuransi</th>
	  	  <th width="1%">Nomor Polis</th>
	  	  <th width="10%">Nomor DN</th>
	  	  <th width="1%">ID Peserta</th>
	  	  <th width="12%">Nama</th>
	  	  <th width="1%">DOB</th>
	  	  <th width="1%">Usia</th>
	  	  <th width="1%">Kredit Awal</th>
	  	  <th width="1%">Tenor</th>
	  	  <th width="1%">Kredit Akhir</th>
	  	  <th width="1%">U P</th>
	  	  <!-- <th width="5%">Premi</th> --!>
	  	  <th width="5%">Total Klaim</th>
	  	  <th width="5%">Cabang</th>
	  	  <th width="1%">Tgl Bayar Dari Asuransi</th>
	  	  <th width="1%">Total Bayar Dari Asuransi</th>
	  	  <th width="1%">Outstanding Dari Asuransi</th>
	  	</tr>';
			if ($_REQUEST['x']) {
				$m = ($_REQUEST['x'] - 1) * 25;
			} else {
				$m = 0;
			}
			$cekpeserta = $database->doQuery('SELECT fu_ajk_peserta.*,fu_ajk_note_as.note_paid_date,fu_ajk_note_as.note_paid_total
											FROM fu_ajk_peserta
											INNER JOIN fu_ajk_klaim ON fu_ajk_peserta.id_klaim=fu_ajk_klaim.id_cn
											inner join fu_ajk_note_as on fu_ajk_note_as.id_peserta=fu_ajk_peserta.id_peserta
											WHERE fu_ajk_note_as.note_paid_date is not null and fu_ajk_peserta.id !="" ' . $satu . ' ' . $dua . ' ' . $tiga . ' ' . $empat . ' AND fu_ajk_peserta.status_aktif="Lapse" ORDER BY fu_ajk_peserta.id_cost ASC, fu_ajk_peserta.nama ASC, fu_ajk_peserta.id_dn ASC LIMIT ' . $m . ' , 25');
			$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(fu_ajk_peserta.id)
											FROM fu_ajk_peserta
											inner join fu_ajk_klaim on fu_ajk_peserta.id_klaim=fu_ajk_klaim.id_cn
											WHERE fu_ajk_peserta.id !="" ' . $satu . ' ' . $dua . ' ' . $tiga . ' ' . $empat . ' AND fu_ajk_peserta.status_aktif="Lapse"'));
			$totalRows = $totalRows[0];
			$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
			while ($xpeserta = mysql_fetch_array($cekpeserta)) {
				$xperusahaan = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="' . $xpeserta['id_cost'] . '"'));
				$xpolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="' . $xpeserta['id_polis'] . '"'));
				$xdn = mysql_fetch_array($database->doQuery('SELECT id, dn_kode FROM fu_ajk_dn WHERE id="' . $xpeserta['id_dn'] . '"'));
				$xcn = mysql_fetch_array($database->doQuery('SELECT total_claim FROM fu_ajk_cn WHERE id_dn="' . $xpeserta['id_dn'] . '"'));
				$xAsuransi = mysql_fetch_array($database->doQuery('SELECT id, id_bank, id_polis, id_asuransi, id_polis_as, id_dn, id_peserta FROM fu_ajk_peserta_as WHERE id_bank="' . $xpeserta['id_cost'] . '" AND id_polis="' . $xpeserta['id_polis'] . '" AND id_peserta="' . $xpeserta['id_peserta'] . '"'));
				$xAsuransi_ = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_asuransi WHERE id="' . $xAsuransi['id_asuransi'] . '"'));
				if (($no % 2) == 1) $objlass = 'tbl-odd'; else                $objlass = 'tbl-even';
				echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		  <td align="center">' . (++$no + ($pageNow - 1) * 25) . '</td>
		  <td>' . $xperusahaan['name'] . '</td>
		  <td>' . $xAsuransi_['name'] . '</td>
		  <td align="center">' . $xpolis['nopol'] . '</td>
		  <td>' . $xdn['dn_kode'] . '</td>
		  <td align="center">' . $xpeserta['id_peserta'] . '</td>
		  <td>' . $xpeserta['nama'] . '</td>
		  <td align="center">' . $xpeserta['tgl_lahir'] . '</td>
		  <td align="center">' . $xpeserta['usia'] . '</td>
		  <td align="center">' . $xpeserta['kredit_tgl'] . '</td>
		  <td align="center">' . $xpeserta['kredit_tenor'] . '</td>
		  <td align="center">' . $xpeserta['kredit_akhir'] . '</td>
		  <td align="right">' . duit($xpeserta['kredit_jumlah']) . '</td>
		  <!-- <td align="right">' . duit($xpeserta['totalpremi']) . '</td> --!>
		  <td align="right"><b>' . duit($xcn['total_claim']) . '</b></td>
		  <td>' . $xpeserta['cabang'] . '</td>
		  <td align="center">'.$xpeserta['note_paid_date'].'</td>
		  <td align="center"><b>'.duit($xpeserta['note_paid_total']).'</b></td>
		  <td align="center"><b>'.duit($xpeserta['note_paid_total']-$xcn['total_claim']).'</b></td>
		  </tr>';
			}
			echo '<tr><td colspan="22">';
			echo createPageNavigations($file = 'ajk_claim.php?d=list_pembas&dm=Searching&cat=' . $_REQUEST['cat'] . '&rnama=' . $_REQUEST['rnama'] . '&nodn=' . $_REQUEST['nodn'] . '&rdob=' . $_REQUEST['rdob'] . '', $total = $totalRows, $psDeh = 10, $anchor = '', $perPage = 25);
			echo '<b>Total Data Peserta: <u>' . $totalRows . '</u></b></td></tr>';
			echo '</table>';
		};
	break;

	case "approval" :

		echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
		<tr><th colspan="8" width="100%" align="left" colspan="2">Modul Klaim - Meninggal</font></th><th width="5%" colspan="2"><a href="ajk_claim.php?d=dx"><img src="image/new.png" width="20"></a></th></tr>
		</table><br />';
		echo '<fieldset>
		<legend>Searching</legend>
		<form method="post" action="">
		<table border="0" width="30%" cellpadding="1" cellspacing="0" align="center">
	  	<tr>
	  	<td width="10%" align="center">ID Peserta</td>
	  	<td width="10%" align="center">NAMA</td>
			<td width="10%" align="center">DEBIT NOTE</td>
			<td width="10%" align="center">CREDIT NOTE</td>
		</tr>
		<tr>
		<td align="center"><input type="text" name="idp" value="'.$_REQUEST['idp'].'"></td>
		<td align="center"><input type="text" name="rnama" value="'.$_REQUEST['rnama'].'"></td>
			<td align="center"><input type="text" name="nodn" value="'.$_REQUEST['nodn'].'"></td>
		  	<td align="center"><input type="text" name="nocn" value="'.$_REQUEST['nocn'].'"></td></tr>
	  	<tr><td colspan="6" align="center"><input type="submit" name="oppe" value="Searching" class="button"></td></tr>
		</table></form></fieldset>';
		//}
		echo '<table border="0" width="100%" cellpadding="5" cellspacing="1" bgcolor="#bde0e6">
		<tr><th width="3%">No</td>
		<th>No Urut</td>
		<th>Asuransi</td>
		<th width="5%">ID Peserta</td>
		<th width="1%">ID DN</td>
		<th width="1%">ID CN</td>
		<th width="1%">Produk</td>
		<th>Nama Debitur</td>
		<th>Cabang</td>
		<th width="5%">Kredit Awal</td>
		<th width="5%">Kredit Akhir</td>
		<th width="5%">Tgl Klaim</td>
		<th width="1%">Tenor</td>
		<!--<th width="1%">Tenor(s,b)</td>-->
		<th width="1%">Plafond</td>
		<th width="1%">Total Klaim Disetujui</td>
		<th width="1%">Status</td>
		<th width="1%">Tgl Bayar Klaim</td>
		<th width="1%">jDoc</td>
		<th width="5%">Option</td>
		<th width="5%">Approve</td>
		</tr>';
		if ($_REQUEST['nodn'])		{	$satu = 'AND fu_ajk_dn.dn_kode LIKE "%' . $_REQUEST['nodn'] . '%"';		}
		if ($_REQUEST['nocn'])		{	$dua = 'AND fu_ajk_cn.id_cn LIKE "%' . $_REQUEST['nocn'] . '%"';		}
		if ($_REQUEST['idp'])		{	$lima = 'AND fu_ajk_peserta.id_peserta LIKE "%' . $_REQUEST['idp'] . '%"';		}
		if ($_REQUEST['rnama'])		{	$tiga = 'AND fu_ajk_peserta.nama LIKE "%'.$_REQUEST['rnama'].'%"';
			$namanya = mysql_fetch_array($database->doQuery('SELECT id_klaim, status_peserta, id_peserta FROM fu_ajk_peserta WHERE id_klaim !="" AND status_peserta="Death" '.$tiga.' '));
			$ernama = 'AND fu_ajk_peserta.id_peserta = "' . $namanya['id_peserta'] . '"';
		}
		if ($_REQUEST['rdob'])		{	$empat = 'AND tgl_lahir = "' . $_REQUEST['rdob'] . '"';
		$dobnya = mysql_fetch_array($database->doQuery('SELECT id_klaim, status_peserta, id_peserta FROM fu_ajk_peserta WHERE id_klaim !="" AND status_peserta="Death" '.$empat.' '));
		$erdob = 'AND fu_ajk_peserta.tgl_lahir = "' . $dobnya['tgl_lahir'] . '"';
		}

		if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}
		//$metklaim = $database->doQuery('SELECT * FROM fu_ajk_cn WHERE type_claim = "Death" '.$satu.' '.$dua.' '.$ernama.' '.$erdob.' AND del IS NULL ORDER BY id DESC LIMIT ' . $m . ' , 25');
		$metklaim = $database->doQuery('SELECT
				fu_ajk_asuransi.`name` AS asuransi,
				fu_ajk_klaim.no_urut_klaim,
				fu_ajk_cn.id,
				fu_ajk_cn.id_cost,
				fu_ajk_cn.id_cn,
				fu_ajk_dn.dn_kode,
				fu_ajk_peserta.id_peserta,
				fu_ajk_peserta.nama,
				fu_ajk_peserta.tgl_lahir,
				fu_ajk_peserta.usia,
				fu_ajk_peserta.kredit_tgl,
				IF(fu_ajk_peserta.type_data="SPK", fu_ajk_peserta.kredit_tenor * 12, fu_ajk_peserta.kredit_tenor) AS tenor,
				fu_ajk_peserta.kredit_akhir,
				fu_ajk_peserta.kredit_jumlah,
				fu_ajk_cn.tgl_claim,
				fu_ajk_cn.premi,
				fu_ajk_cn.tuntutan_klaim,
				fu_ajk_cn.confirm_claim,
				fu_ajk_cn.total_claim,
				fu_ajk_cn.tgl_byr_claim,
				fu_ajk_polis.nmproduk,
				fu_ajk_peserta.cabang,
				fu_ajk_klaim.tgl_kirim_dokumen,
				fu_ajk_cn.tgl_bayar_asuransi,
				fu_ajk_klaim_status.status_klaim
				FROM
				fu_ajk_cn
				INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
				INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
				INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
				INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
				LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
				LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim.id_klaim_status = fu_ajk_klaim_status.id
				WHERE fu_ajk_klaim_status.order_list in (6,7,8) and fu_ajk_cn.confirm_claim != "Pending" AND
				fu_ajk_cn.type_claim = "Death" AND fu_ajk_cn.del IS NULL  '.$satu.' '.$dua.' '.$tiga.' '.$lima.'
				ORDER BY fu_ajk_cn.id DESC LIMIT ' . $m . ' , 25');
		//$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_cn WHERE id != "" AND type_claim="Death" '.$satu.' '.$dua.' '.$ernama.' '.$erdob.' AND del IS NULL'));
		$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(fu_ajk_cn.id_cn) FROM fu_ajk_cn
		INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
		INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
		INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
						LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
						LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim.id_klaim_status = fu_ajk_klaim_status.id
		WHERE  fu_ajk_klaim_status.order_list < 4 and fu_ajk_cn.confirm_claim != "Pending" AND fu_ajk_cn.type_claim = "Death" AND fu_ajk_cn.del IS NULL  '.$satu.' '.$dua.' '.$tiga.''));
		$totalRows = $totalRows[0];
		$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
		while ($rklaim = mysql_fetch_array($metklaim)) {
			/*
			 $klaim_dn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn WHERE id="'.$rklaim['id_dn'].'"'));
			 $klaimpeserta = mysql_fetch_array($database->doQuery('SELECT id_cost, id_peserta, status_peserta, type_data, del, nama, tgl_lahir, kredit_tgl, kredit_tenor, kredit_akhir FROM fu_ajk_peserta WHERE id_cost="'.$rklaim['id_cost'].'" AND id_peserta="'.$rklaim['id_peserta'].'" AND status_peserta="Death" AND del IS NULL'));
			 $klaimcost = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$rklaim['id_cost'].'"'));
			 $klaimpolis = mysql_fetch_array($database->doQuery('SELECT id, id_cost, nmproduk, nopol FROM fu_ajk_polis WHERE id="'.$rklaim['id_nopol'].'"'));
			 $xAsuransi = mysql_fetch_array($database->doQuery('SELECT id, id_bank, id_polis, id_asuransi, id_polis_as, id_dn, id_peserta FROM fu_ajk_peserta_as WHERE id_bank="'.$rklaim['id_cost'].'" AND id_polis="'.$rklaim['id_nopol'].'" AND id_peserta="'.$rklaim['id_peserta'].'"'));
			 $xAsuransi_ = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_asuransi WHERE id="'.$xAsuransi['id_asuransi'].'"'));

			 $now = new T10DateCalc($klaimpeserta['kredit_tgl']);
			 $periodbulan = $now->compareDate($rklaim['tgl_claim']) / 30.4375;
			 $maj = ceil($periodbulan);

			 if ($klaimpeserta['type_data']=="SPK") {	$tenorSPK = $klaimpeserta['kredit_tenor'] * 12;	}else{	$tenorSPK = $klaimpeserta['kredit_tenor'];	}
			 */
			$jdoc = mysql_num_rows($database->doQuery('SELECT id_pes, id_cost FROM fu_ajk_klaim_doc WHERE id_pes="'.$rklaim['id_peserta'].'" AND id_cost="'.$rklaim['id_cost'].'"'));
			$mets = datediff($rklaim['kredit_tgl'], $rklaim['tgl_claim']);
			$metTgl = explode(",",$mets);
			//echo $metTgl[0].'-'.$metTgl[1].'-'.$metTgl[2].'<br />';
			if ($metTgl[2] > 0) {	$jumBulan = $metTgl[1] + 1;	}else{	$jumBulan = $metTgl[1];	}	//AKUMULASI BULAN THD JUMLAH HARI
			$klaimBlnJ = $metTgl[0] + $jumBulan;
			$klaimBlnS = $rklaim['tenor'] - $klaimBlnJ;
			//SETTING TGL BAYAR KLAIM//
			if ($rklaim['tgl_byr_claim']=="") {	$metbyrklaim = 'Belum dibayar';	}else{	$metbyrklaim = '<font color="blue">'._convertDate($rklaim['tgl_byr_claim']).'</font>';	}
			//SETTING TGL BAYAR KLAIM//

			//SETTING PEMBERITAHUAN INVESTIGASI

			$surat_pem='';
			if(!is_null($rklaim['tgl_kirim_dokumen']) && $rklaim['tgl_kirim_dokumen']!='0000-00-00' && datediff($rklaim['tgl_kirim_dokumen'], $futoday)>=7){
				$surat_pem='<a title="Print Surat Persetujuan Ke asuransi" target="_blank" href="e_report_klaim.php?er=setuju_as&id='.$rklaim['id'].'"><img src="image/print.png" width="30"></a>';
			}

			if(!is_null($rklaim['tgl_kirim_dokumen']) && $rklaim['tgl_kirim_dokumen']!='0000-00-00' && datediff($rklaim['tgl_kirim_dokumen'], $futoday)>=14){
				$surat_pem='<a title="Print Surat Realisasi Ke asuransi '.$rklaim['tgl_bayar_asuransi'].'"  target="_blank" href="e_report_klaim.php?er=realisasi_as&id='.$rklaim['id'].'"><img src="image/print.png" width="30"></a>';
			}


			$status_dok='';
			if($rklaim['kadaluarsa']=="kadaluarsa"){
				$status_dok='Klaim Kadaluarsa';
			}else{
				$status_dok='<a target="_blank" href="ajk_claim.php?d=suratpengajuan&id='.$rklaim['id'].'">'.$rklaim['status_klaim'].'</a>';
			}


			//SETTING PEMBERITAHUAN INVESTIGASI
			if (($no % 2) == 1)	$objlass = 'tbl-odd'; else				$objlass = 'tbl-even';
			echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		<td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
		<td align="center">'.$rklaim['no_urut_klaim'].'</td>
		<td align="center">'.$rklaim['asuransi'].'</td>
		<td align="center"><a href="../aajk_report.php?er=_erKlaim&idC='.$rklaim['id'].'" target="_blank">'.$rklaim['id_peserta'].'</a></td>
		<td align="center">'.substr($rklaim['dn_kode'],3).'</td>
		<td align="center"><a href="../aajk_report.php?er=_cnDeath&idC='.$rklaim['id'].'" target="_blank">'.substr($rklaim['id_cn'],3).'</a></td>
		<td align="center">'.$rklaim['nmproduk'].'</td>
		<td><a title="Worksheet Klaim" href="ajk_claim.php?d=wklaim&id='.$rklaim['id'].'" target="_blank">'.$rklaim['nama'].'</a></td>
		<td align="center">'.$rklaim['cabang'].'</td>
		<td align="center">'._convertDate($rklaim['kredit_tgl']).'</td>
		<td align="center">'._convertDate($rklaim['kredit_akhir']).'</td>
		<td align="center">'._convertDate($rklaim['tgl_claim']).'</td>
		<td align="center">'.$rklaim['tenor'].'</td>
		<!--<td align="center">'.$klaimBlnS.','.$klaimBlnJ.'</td>-->
		<td align="right">'.duit($rklaim['kredit_jumlah']).'</td>
		<td align="right">'.duit($rklaim['tuntutan_klaim']).'</td>
		<td align="center">'.$status_dok.'</td>
		<td align="center">'.$metbyrklaim.'</td>
		<td align="center">'.$jdoc.'</td>
		<td align="center">
		'.$surat_pem.'
		</td>
		<td align="center"><a href="ajk_claim.php?d=approval_p&id='.$rklaim['id'].'"><img src="image/check.png" width="30"></a></td>
		</tr>';
		}
		echo '<tr><td colspan="22">';
		echo createPageNavigations($file = 'ajk_claim.php?d=approval&idp='.$_REQUEST['idp'].'&nodn='.$_REQUEST['nodn'].'&nocn='.$_REQUEST['nocn'].'&rnama='.$_REQUEST['rnama'], $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
		echo '<b>Total Data Meninggal: <u>' . $totalRows . '</u></b></td></tr>';
		echo '</table>';

		;
	break;


	case "approval_p":
		echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
		<tr><th colspan="8" width="100%" align="left" colspan="2">Modul Klaim - Edit Data Meninggal</font></th><th width="5%" colspan="2"><a href="ajk_claim.php"><img src="image/back.png" width="20"></a></th></tr>
		</table><br />';

			$edklaim = mysql_fetch_array($database->doQuery('SELECT
			fu_ajk_asuransi.`name` AS asuransi,
			fu_ajk_cn.id,
			fu_ajk_cn.idC,
			fu_ajk_cn.id_cost,
			fu_ajk_cn.id_cn,
			fu_ajk_cn.id_dn,
			fu_ajk_dn.dn_kode,
			fu_ajk_peserta.id_polis,
			fu_ajk_peserta.id_peserta,
			fu_ajk_peserta.nama,
			fu_ajk_peserta.cabang,
			fu_ajk_peserta.tgl_lahir,
			fu_ajk_peserta.usia,
			fu_ajk_peserta.kredit_tgl,
			IF(fu_ajk_peserta.type_data="SPK", fu_ajk_peserta.kredit_tenor * 12, fu_ajk_peserta.kredit_tenor) AS tenor,
			fu_ajk_peserta.kredit_akhir,
			fu_ajk_peserta.kredit_jumlah,
			fu_ajk_cn.tgl_claim,
			fu_ajk_cn.premi,
			fu_ajk_cn.confirm_claim,
			fu_ajk_cn.total_claim,
			fu_ajk_cn.tuntutan_klaim,
			fu_ajk_cn.tgl_byr_claim,
			fu_ajk_cn.nmpenyakit,
			fu_ajk_cn.keterangan,
			fu_ajk_polis.nmproduk,
			fu_ajk_klaim.id_klaim_status,
			fu_ajk_klaim.tgl_klaim AS tglklaim,
			fu_ajk_klaim.tgl_document AS tglklaimdoc,
			fu_ajk_klaim.tgl_document_lengkap AS tglklaimdoc2,
			fu_ajk_klaim.tgl_investigasi AS tglinvestigasi,
			fu_ajk_klaim.tgl_lapor_klaim AS tgllaporklaim,
			fu_ajk_klaim.jumlah AS totalklaim,
			fu_ajk_klaim.tempat_meninggal AS tempat_meninggal,
			fu_ajk_klaim.diagnosa AS diagnosa,
			fu_ajk_klaim.sebab_meninggal AS sebab_meninggal,
			fu_ajk_klaim.ket AS ketklaim,
			fu_ajk_klaim.sumber_dana,
			ifnull(fu_ajk_klaim.rencana_bayar,fu_ajk_cn.tuntutan_klaim) as rencana_bayar,
			fu_ajk_klaim.tgl_rencana_bayar,
			fu_ajk_klaim.ket_dokter AS ketDokter,
			fu_ajk_klaim.tgl_kirim_dokumen AS tglkirimdoc,
			fu_ajk_namapenyakit.id AS idpenyakit,
			fu_ajk_namapenyakit.namapenyakit
			FROM fu_ajk_cn
			LEFT JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
			LEFT JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
			LEFT JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
			LEFT JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
			LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta AND fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost
			LEFT JOIN fu_ajk_namapenyakit ON fu_ajk_cn.nmpenyakit = fu_ajk_namapenyakit.id
			WHERE fu_ajk_cn.id = "' . $_REQUEST['id'] . '" AND fu_ajk_cn.del IS NULL'));
			$adcostumer = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="' . $edklaim['id_cost'] . '"'));
			if ($_REQUEST['ed_oop'] == "save") {
				//if (!$_REQUEST['jklaim']) $error1 .= '<font color="red">Total Claim tidak boleh kosong</font>.';
				//if ($_REQUEST['jklaim'] > $edklaim['kredit_jumlah']) $error3 .= '<font color="red"><blink>Klaim tidak bisa di proses, nilai klaim lebih besar dari maximum klaim !</blink></font>';

				$idcnnya = 10000000000 + $adcostumer['idC'];
				$idcn = substr($idcnnya, 1);
				$cntgl = explode("/", $futgldn);
				$cnthn = substr($cntgl[2], 2);
				$cn_kode = 'ACN' . $cnthn . '' . $cntgl[1] . '' . $idcn;


				//if ($error1 or $error3) {
				//} else {

					$database->doQuery('UPDATE fu_ajk_cn SET id_cn="' . $cn_kode . '",tgl_createcn="' . $futoday . '" WHERE id="' . $_REQUEST['id'] . '"');

					$database->doQuery('UPDATE fu_ajk_klaim SET
													 	 id_klaim_status="7",
														 tgl_app_klaim="' . $futoday . '",
														 tgl_rencana_bayar="'.$_REQUEST['tgl_rencana_bayar'].'",
														 rencana_bayar="' . $_REQUEST['jklaim'] . '"
														 WHERE id_dn="' . $edklaim['id_dn'] . '" AND
													 	 id_cn="' . $edklaim['id'] . '" AND
													 	 id_peserta="' . $edklaim['id_peserta'] . '"');

					$database->doQuery('INSERT INTO fu_ajk_note_as SET id_dn="' . $edklaim['id_dn'] . '",
															 id_cn="' . $edklaim['id'] . '",
															 id_peserta="' . $edklaim['id_peserta'] . '",
															 note_type="DNC",
	 														 note_date="' . $futoday . '",
	 														 note_desc="Tagihan Klaim a/n '.$edklaim['nama'].' dengan Nilai Klaim '.$edklaim['tuntutan_klaim'].'",
                											 note_curr="IDR",
                											 note_subtotal="'.$_REQUEST['jklaim'].'",
                											 note_other_fee="0",
                											 note_total="'.$_REQUEST['jklaim'].'",
															 entry_by="' . $q['nm_lengkap'] . '",
															 entry_time="' . $futgl . '" ON DUPLICATE KEY UPDATE id_peserta="'.$edklaim['id_peserta'].'", note_type="DNC"');

					$database->doQuery('INSERT INTO fu_ajk_note_as SET id_dn="' . $edklaim['id_dn'] . '",
															 id_cn="' . $edklaim['id'] . '",
															 id_peserta="' . $edklaim['id_peserta'] . '",
															 note_type="CNC",
	 														 note_date="' . $futoday . '",
	 														 note_desc="Pembayaran Klaim a/n '.$edklaim['nama'].' dengan Nilai Klaim '.$edklaim['tuntutan_klaim'].'",
                											 note_curr="IDR",
                											 note_subtotal="'.$_REQUEST['jklaim'].'",
                											 note_other_fee="0",
                											 note_total="'.$_REQUEST['jklaim'].'",
															 entry_by="' . $q['nm_lengkap'] . '",
															 entry_time="' . $futgl . '" ON DUPLICATE KEY UPDATE id_peserta="'.$edklaim['id_peserta'].'", note_type="CNC"');

					$querycn = "INSERT INTO CMS_ArAp_Transaction(fArAp_TransactionCode,
																fArAp_TransactionDate,
																fArAp_Status,
																fArAp_No,
																fArAp_Customer_Id,
																fArAp_Customer_Nm,
																fArAp_Asuransi_Id,
																fArAp_Asuransi_Nm,
																fArAp_Produk_Nm,
																fArAp_StatusPeserta,
																fArAp_DateStatus,
																fArAp_CoreCode,
																fArAp_BMaterialCode,
																fArAp_RefMemberID,
																fArAp_RefMemberNm,
																fArAp_RefCabang,
																fArAp_RefDescription,
																fArAp_RefAmount,
																fArAp_RefAmount2,
																fArAp_RefDOB,
																fArAp_AssDate,
																fArAp_RefTenor,
																fArAp_RefPlafond,
																fArAp_Return_Status,
																fArAp_Return_Date,
																fArAp_Return_Amount,
																fArAp_SourceDB,
																input_by,
																input_date)	
											select 'AP-03' as fArAp_TransactionCode,
											fu_ajk_cn.tgl_createcn as fArAp_TransactionDate,
											'A' as fArAp_Status,
											fu_ajk_cn.id_cn as fArAp_No,
											fu_ajk_grupproduk.nmproduk as fArAp_Customer_Id,
											fu_ajk_grupproduk.nm_mitra as fArAp_Customer_Nm,
											fu_ajk_asuransi.`code` as fArAp_Asuransi_Id,
											fu_ajk_asuransi.`name` as fArAp_Asuransi_Nm,
											fu_ajk_polis.nmproduk as fArAp_Produk_Nm,
											CONCAT(IFNULL(fu_ajk_peserta.status_aktif,''),' ',IFNULL(fu_ajk_peserta.status_peserta,''))as fArAp_StatusPeserta,
											DATE_FORMAT(NOW(),'%Y-%m-%d')as fArAp_DateStatus,
											'KLM' as fArAp_CoreCode,
											'KLM' as fArAp_BMaterialCode,
											fu_ajk_peserta.id_peserta as fArAp_RefMemberID,
											fu_ajk_peserta.nama as fArAp_RefMemberNm,
											fu_ajk_peserta.cabang as fArAp_RefCabang,
											null as fArAp_RefDescription,
											fu_ajk_cn.total_claim as fArAp_RefAmount,
											NULL as fArAp_RefAmount2,
											fu_ajk_peserta.tgl_lahir as fArAp_RefDOB,
											fu_ajk_peserta.tgl_laporan as fArAp_AssDate,
											fu_ajk_peserta.kredit_tenor as fArAp_RefTenor,
											fu_ajk_peserta.kredit_jumlah as fArAp_RefPlafond,
											null as fArAp_Return_Status,
											null as fArAp_Return_Date,
											null as fArAp_Return_Amount,
											'AJK' AS fArAp_SourceDB,
											'Migrasi' as input_by,
											now() as input_date
											from fu_ajk_peserta
											INNER JOIN fu_ajk_peserta_as
											on fu_ajk_peserta_as.id_peserta = fu_ajk_peserta.id_peserta
											INNER JOIN fu_ajk_dn 
											on fu_ajk_dn.id = fu_ajk_peserta.id_dn
											INNER JOIN fu_ajk_asuransi
											on fu_ajk_asuransi.id = fu_ajk_peserta_as.id_asuransi
											INNER JOIN fu_ajk_grupproduk
											on fu_ajk_grupproduk.id = fu_ajk_peserta.nama_mitra
											INNER JOIN fu_ajk_polis
											ON fu_ajk_polis.id = fu_ajk_peserta.id_polis
											INNER JOIN fu_ajk_cn
											ON fu_ajk_cn.id = fu_ajk_peserta.id_klaim
											where fu_ajk_peserta.del is NULL and
											fu_ajk_peserta_as.del is NULL and
											fu_ajk_dn.del is null and
											fu_ajk_cn.del is null and
											fu_ajk_asuransi.del is NULL AND
											fu_ajk_grupproduk.del is NULL and
											fu_ajk_polis.del is NULL and
											fu_ajk_cn.type_claim = 'Death' and
											fu_ajk_peserta.id_peserta = '".$edklaim['id_peserta']."'";

					$querydn = 
					"INSERT INTO CMS_ArAp_Transaction(fArAp_TransactionCode,
										fArAp_TransactionDate,
										fArAp_Status,
										fArAp_No,
										fArAp_Customer_Id,
										fArAp_Customer_Nm,
										fArAp_Asuransi_Id,
										fArAp_Asuransi_Nm,
										fArAp_Produk_Nm,
										fArAp_StatusPeserta,
										fArAp_DateStatus,
										fArAp_CoreCode,
										fArAp_BMaterialCode,
										fArAp_RefMemberID,
										fArAp_RefMemberNm,
										fArAp_RefCabang,
										fArAp_RefDescription,
										fArAp_RefAmount,
										fArAp_RefAmount2,
										fArAp_RefDOB,
										fArAp_AssDate,
										fArAp_RefTenor,
										fArAp_RefPlafond,
										fArAp_Return_Status,
										fArAp_Return_Date,
										fArAp_Return_Amount,
										fArAp_SourceDB,
										input_by,
										input_date)	
					select 'AR-03' as fArAp_TransactionCode,
					fu_ajk_cn.tgl_createcn as fArAp_TransactionDate,
					'A' as fArAp_Status,
					CONCAT('ADNA',MID(fu_ajk_cn.id_cn,4,20))as fArAp_No,
					fu_ajk_grupproduk.nmproduk as fArAp_Customer_Id,
					fu_ajk_grupproduk.nm_mitra as fArAp_Customer_Nm,
					fu_ajk_asuransi.`code` as fArAp_Asuransi_Id,
					fu_ajk_asuransi.`name` as fArAp_Asuransi_Nm,
					fu_ajk_polis.nmproduk as fArAp_Produk_Nm,
					CONCAT(IFNULL(fu_ajk_peserta.status_aktif,''),' ',IFNULL(fu_ajk_peserta.status_peserta,''))as fArAp_StatusPeserta,
					DATE_FORMAT(NOW(),'%Y-%m-%d')as fArAp_DateStatus,
					'KLM' as fArAp_CoreCode,
					'KLM-AS' as fArAp_BMaterialCode,
					fu_ajk_peserta.id_peserta as fArAp_RefMemberID,
					fu_ajk_peserta.nama as fArAp_RefMemberNm,
					fu_ajk_peserta.cabang as fArAp_RefCabang,
					null as fArAp_RefDescription,
					fu_ajk_cn.total_claim_as as fArAp_RefAmount,
					NULL as fArAp_RefAmount2,
					fu_ajk_peserta.tgl_lahir as fArAp_RefDOB,
					fu_ajk_peserta.tgl_laporan as fArAp_AssDate,
					fu_ajk_peserta.kredit_tenor as fArAp_RefTenor,
					fu_ajk_peserta.kredit_jumlah as fArAp_RefPlafond,
					null as fArAp_Return_Status,
					null as fArAp_Return_Date,
					null as fArAp_Return_Amount,
					'AJK' AS fArAp_SourceDB,
					'Migrasi' as input_by,
					now() as input_date
					from fu_ajk_peserta
					INNER JOIN fu_ajk_peserta_as
					on fu_ajk_peserta_as.id_peserta = fu_ajk_peserta.id_peserta
					INNER JOIN fu_ajk_dn 
					on fu_ajk_dn.id = fu_ajk_peserta.id_dn
					INNER JOIN fu_ajk_asuransi
					on fu_ajk_asuransi.id = fu_ajk_peserta_as.id_asuransi
					INNER JOIN fu_ajk_grupproduk
					on fu_ajk_grupproduk.id = fu_ajk_peserta.nama_mitra
					INNER JOIN fu_ajk_polis
					ON fu_ajk_polis.id = fu_ajk_peserta.id_polis
					INNER JOIN fu_ajk_cn
					ON fu_ajk_cn.id = fu_ajk_peserta.id_klaim
					where fu_ajk_peserta.del is NULL and
					fu_ajk_peserta_as.del is NULL and
					fu_ajk_dn.del is null and
					fu_ajk_cn.del is null and
					fu_ajk_asuransi.del is NULL AND
					fu_ajk_grupproduk.del is NULL and
					fu_ajk_polis.del is NULL and
					fu_ajk_cn.type_claim = 'Death' and
					fu_ajk_peserta.id_peserta = '".$edklaim['id_peserta']."'";
					$database->doQuery($querycn);
					$database->doQuery($querydn);
					// $arap_produksi_cn = $database->doQuery('insert into CMS_ArAp_Master(
					// 					fArAp_No
					// 					,fArAp_Status
					// 					,fArAp_TransactionCode
					// 					,fArAp_TransactionDate
					// 					,fArAp_Customer_Id
					// 					,fArAp_Customer_Nm
					// 					,fArAp_ReferenceNo1_1
					// 					,fArAp_ReferenceNo1_2
					// 					,fArAp_ReferenceNo1_3
					// 					,fArAp_Note
					// 					,fArAp_CrrencyCode
					// 					,fArAp_AmmountTotal
					// 					,input_by
					// 					,input_date) SELECT
					// 					REPLACE(fu_ajk_cn.id_cn,"CNA","DNA"),
					// 					"A",
					// 					"AR-02",
					// 					current_date(),
					// 					fu_ajk_asuransi.`code`,
					// 					fu_ajk_asuransi.`name`,
					// 					fu_ajk_costumer.`name`,
					// 					"'.$cn_kode.'",
					// 					"",
					// 					fu_ajk_cn.noperkredit,
					// 					"IDR",
					// 					fu_ajk_cn.tuntutan_klaim,
					// 					"' . $_SESSION['nm_user'] . '",
					// 					current_timestamp()
					// 					FROM
					// 					fu_ajk_cn
					// 					INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
					// 					INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
					// 					INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
					// 					INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta AND fu_ajk_cn.id = fu_ajk_peserta.id_klaim
					// 					INNER JOIN fu_ajk_costumer ON fu_ajk_cn.id_cost = fu_ajk_costumer.id
					// 					LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
					// 					where
					// 					fu_ajk_cn.type_claim = "Death" AND fu_ajk_cn.confirm_claim <> "Pending" AND fu_ajk_cn.del IS NULL
					// 					and fu_ajk_cn.id="' . $edklaim['id'] . '"');


					// $arap_produksi_dn = $database->doQuery('insert into CMS_ArAp_Master(
					// 					fArAp_No
					// 					,fArAp_Status
					// 					,fArAp_TransactionCode
					// 					,fArAp_TransactionDate
					// 					,fArAp_Customer_Id
					// 					,fArAp_Customer_Nm
					// 					,fArAp_ReferenceNo1_1
					// 					,fArAp_ReferenceNo1_2
					// 					,fArAp_ReferenceNo1_3
					// 					,fArAp_Note
					// 					,fArAp_CrrencyCode
					// 					,fArAp_AmmountTotal
					// 					,input_by
					// 					,input_date) SELECT
					// 					fu_ajk_cn.id_cn,
					// 					"A",
					// 					"AP-02",
					// 					current_date(),
					// 					fu_ajk_costumer.kd_databank,
					// 					fu_ajk_costumer.`name`,
					// 					fu_ajk_polis.nmproduk,
					// 					fu_ajk_cn.id_cabang,
					// 					"",
					// 					fu_ajk_cn.noperkredit,
					// 					"IDR",
					// 					fu_ajk_cn.tuntutan_klaim,
					// 					"' . $_SESSION['nm_user'] . '",
					// 					current_timestamp()
					// 					FROM
					// 					fu_ajk_cn
					// 					INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
					// 					INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
					// 					INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
					// 					INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta AND fu_ajk_cn.id = fu_ajk_peserta.id_klaim
					// 					INNER JOIN fu_ajk_costumer ON fu_ajk_cn.id_cost = fu_ajk_costumer.id
					// 					LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
					// 					where
					// 					fu_ajk_cn.type_claim = "Death" AND fu_ajk_cn.confirm_claim <> "Pending" AND fu_ajk_cn.del IS NULL
					// 					and fu_ajk_cn.id="' . $edklaim['id'] . '"');


					// $arap_produksi_detail_dn = $database->doQuery('insert into CMS_ArAp_Detail(
					// 					fArAp_No
					// 					,fArAp_Counter
					// 					,fArAp_BMaretialCode
					// 					,fArAp_Description
					// 					,fArAp_Amount
					// 					,input_by
					// 					,input_date)
					// 					SELECT
					// 					REPLACE(fu_ajk_cn.id_cn,"CNA","DNA"),
					// 					1,
					// 					"KLM",
					// 					"Penerimaan Pembayaran Klaim dari Asuransi",
					// 					fu_ajk_cn.tuntutan_klaim,
					// 					"' . $_SESSION['nm_user'] . '",
					// 					current_timestamp()
					// 					FROM
					// 					fu_ajk_cn
					// 					INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
					// 					INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
					// 					INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
					// 					INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta AND fu_ajk_cn.id = fu_ajk_peserta.id_klaim
					// 					INNER JOIN fu_ajk_costumer ON fu_ajk_cn.id_cost = fu_ajk_costumer.id
					// 					LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
					// 					where
					// 					fu_ajk_cn.type_claim = "Death" AND fu_ajk_cn.confirm_claim <> "Pending" AND fu_ajk_cn.del IS NULL
					// 					and fu_ajk_cn.id="' . $edklaim['id'] . '"');

					// $arap_produksi__detail_cn = $database->doQuery('insert into CMS_ArAp_Detail(
					// 					fArAp_No
					// 					,fArAp_Counter
					// 					,fArAp_BMaretialCode
					// 					,fArAp_Description
					// 					,fArAp_Amount
					// 					,input_by
					// 					,input_date)
					// 					SELECT
					// 					fu_ajk_cn.id_cn,
					// 					1,
					// 					"KLM",
					// 					"Pembayaran Klaim Ke Bank",
					// 					fu_ajk_cn.tuntutan_klaim,
					// 					"' . $_SESSION['nm_user'] . '",
					// 					current_timestamp()
					// 					FROM
					// 					fu_ajk_cn
					// 					INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
					// 					INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
					// 					INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
					// 					INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta AND fu_ajk_cn.id = fu_ajk_peserta.id_klaim
					// 					INNER JOIN fu_ajk_costumer ON fu_ajk_cn.id_cost = fu_ajk_costumer.id
					// 					LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
					// 					where
					// 					fu_ajk_cn.type_claim = "Death" AND fu_ajk_cn.confirm_claim <> "Pending" AND fu_ajk_cn.del IS NULL
					// 					and fu_ajk_cn.id="' . $edklaim['id'] . '"');


					// $arap_detail_dn=$database->doQuery('insert into CMS_ArAp_Referensi(
					// 										fArAp_No
					// 										,fArAp_Counter
					// 										,fArAp_CoreCode
					// 										,fArAp_BMaretialCode
					// 										,fArAp_RefMemberID
					// 										,fArAp_RefMemberNm
					// 										,fArAp_RefDescription
					// 										,fArAp_RefAmount
					// 										, input_by
					// 										, input_date) SELECT
					// 										REPLACE(fu_ajk_cn.id_cn,"CNA","DNA")
					// 										, "1"
					// 										, "KLM"
					// 										, "KLM"
					// 										, fu_ajk_cn.id_peserta
					// 										, fu_ajk_peserta.nama
					// 										, ""
					// 										, fu_ajk_cn.tuntutan_klaim
					// 										, "' . $_SESSION['nm_user'] . '"
					// 										, current_timestamp()
					// 										FROM
					// 										fu_ajk_cn
					// 										INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
					// 										INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
					// 										INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
					// 										INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta AND fu_ajk_cn.id = fu_ajk_peserta.id_klaim
					// 										INNER JOIN fu_ajk_costumer ON fu_ajk_cn.id_cost = fu_ajk_costumer.id
					// 										LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
					// 										where
					// 										fu_ajk_cn.type_claim = "Death" AND fu_ajk_cn.confirm_claim <> "Pending" AND fu_ajk_cn.del IS NULL
					// 										and fu_ajk_cn.id="' . $edklaim['id'] . '"');


					// $arap_detail_cn=$database->doQuery('insert into CMS_ArAp_Referensi(
					// 										fArAp_No
					// 										,fArAp_Counter
					// 										,fArAp_CoreCode
					// 										,fArAp_BMaretialCode
					// 										,fArAp_RefMemberID
					// 										,fArAp_RefMemberNm
					// 										,fArAp_RefDescription
					// 										,fArAp_RefAmount
					// 										, input_by
					// 										, input_date)
					// 										select fu_ajk_cn.id_cn
					// 										, "1"
					// 										, "KLM"
					// 										, "KLM"
					// 										, fu_ajk_cn.id_peserta
					// 										, fu_ajk_peserta.nama
					// 										, ""
					// 										, fu_ajk_cn.tuntutan_klaim
					// 										, "' . $_SESSION['nm_user'] . '"
					// 										, current_timestamp()
					// 										FROM
					// 										fu_ajk_cn
					// 										INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
					// 										INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
					// 										INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
					// 										INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta AND fu_ajk_cn.id = fu_ajk_peserta.id_klaim
					// 										INNER JOIN fu_ajk_costumer ON fu_ajk_cn.id_cost = fu_ajk_costumer.id
					// 										LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
					// 										where
					// 										fu_ajk_cn.type_claim = "Death" AND fu_ajk_cn.confirm_claim <> "Pending" AND fu_ajk_cn.del IS NULL
					// 										and fu_ajk_cn.id="' . $edklaim['id'] . '"');

					echo '<center><h2>Data Klaim meninggal telah di setujui oleh ' . $q['nm_lengkap'] . ' pada tanggal ' . $futgl . '.</h2></center><meta http-equiv="refresh" content="2;URL=ajk_claim.php?d=approval">';
				//}
			}elseif($_REQUEST['ed_oop']=="unsave"){
				//if (!$_REQUEST['jklaim']) $error1 .= '<font color="red">Total Claim tidak boleh kosong</font>.';
				//if ($_REQUEST['jklaim'] > $edklaim['kredit_jumlah']) $error3 .= '<font color="red"><blink>Klaim tidak bisa di proses, nilai klaim lebih besar dari maximum klaim !</blink></font>';

				$database->doQuery('UPDATE fu_ajk_cn SET id_cn="' . $cn_kode . '",tgl_createcn="' . $futoday . '" WHERE id="' . $_REQUEST['id'] . '"');

				$database->doQuery('UPDATE fu_ajk_klaim SET
															 	 id_klaim_status="6",
																 rencana_bayar="' . $_REQUEST['jklaim'] . '"
																 WHERE id_dn="' . $edklaim['id_dn'] . '" AND
															 	 id_cn="' . $edklaim['id'] . '" AND
															 	 id_peserta="' . $edklaim['id_peserta'] . '"');


				$database->doQuery('INSERT INTO fu_ajk_note_as SET id_dn="' . $edklaim['id_dn'] . '",
															 id_cn="' . $edklaim['id'] . '",
															 id_peserta="' . $edklaim['id_peserta'] . '",
															 note_type="DNC",
	 														 note_date="' . $futoday . '",
	 														 note_desc="Tagihan Klaim a/n '.$edklaim['nama'].' dengan Nilai Klaim '.$_REQUEST['jklaim'].'",
                											 note_curr="IDR",
                											 note_subtotal="'.$_REQUEST['jklaim'].'",
                											 note_other_fee="0",
                											 note_total="'.$_REQUEST['jklaim'].'",
															 entry_by="' . $q['nm_lengkap'] . '",
															 entry_time="' . $futgl . '" ON DUPLICATE KEY UPDATE id_peserta="'.$edklaim['id_peserta'].'", note_type="DNC"');

				$database->doQuery('INSERT INTO fu_ajk_note_as SET id_dn="' . $edklaim['id_dn'] . '",
															 id_cn="' . $edklaim['id'] . '",
															 id_peserta="' . $edklaim['id_peserta'] . '",
															 note_type="CNC",
	 														 note_date="' . $futoday . '",
	 														 note_desc="Pembayaran Klaim a/n '.$edklaim['nama'].' dengan Nilai Klaim '.$_REQUEST['jklaim'].'",
                											 note_curr="IDR",
                											 note_subtotal="'.$_REQUEST['jklaim'].'",
                											 note_other_fee="0",
                											 note_total="'.$_REQUEST['jklaim'].'",
															 entry_by="' . $q['nm_lengkap'] . '",
															 entry_time="' . $futgl . '" ON DUPLICATE KEY UPDATE id_peserta="'.$edklaim['id_peserta'].'", note_type="CNC"');

				echo '<center><h2>Data Klaim meninggal telah di tolak oleh ' . $q['nm_lengkap'] . ' pada tanggal ' . $futgl . '.</h2></center><meta http-equiv="refresh" content="2;URL=ajk_claim.php?d=approval">';
			}

			$mets = datediff($edklaim['kredit_tgl'], $edklaim['tgl_claim']);
			$metTgl = explode(",", $mets);
			//echo $metTgl[0].'-'.$metTgl[1].'-'.$metTgl[2].'<br />';
			if ($metTgl[2] > 0) {
				$jumBulan = $metTgl[1] + 1;
			} else {
				$jumBulan = $metTgl[1];
			}    //AKUMULASI BULAN THD JUMLAH HARI
			$maj = ($metTgl[0]*12) + $jumBulan;


			echo '<form method="post" action="">
				<table border="0" cellpadding="3" cellspacing="0" width="80%" align="center" style="border: solid 1px #DEDEDE" align="center">
				<input type="hidden" name="id" value="' . $_REQUEST['id'] . '">
				<input type="hidden" name="kredit_tenor" value="' . $edklaim['tenor'] . '">
				<tr><th colspan="4">Edit Form Pengisian Data Klaim Meninggal</th></tr>
				<tr><td width="25%">Nama Perusahaan</td><td>: <b>' . $adcostumer['name'] . '</b></td>
				<tr><td width="25%">Cabang</td><td>: <b>' . $edklaim['cabang'] . '</b></td>
				<tr><td>Nama Produk</td><td>: <b>' . $edklaim['nmproduk'] . '</b></td>
				<td width="25%" align="right">ID Debitur</td><td>: <b>' . $edklaim['id_peserta'] . '</b></td>
			</tr>
			<tr><td>Name</td><td colspan="3">: <b>' . $edklaim['nama'] . '</b></td></tr>
			<tr><td>Plafond</td><td colspan="3">: <b>' . duit($edklaim['kredit_jumlah']) . '</b></td></tr>
			<tr><td>Tgl Lahir</td><td colspan="3">: <b>' . _convertDate($edklaim['tgl_lahir']) . '</b></td></tr>
			<tr><td>Tanggal Asuransi</td><td>: <b>' . _convertDate($edklaim['kredit_tgl']) . '</b> s/d <b>' . _convertDate($edklaim['kredit_akhir']) . '</b></td>
				<td align="right">Tenor</td><td>: <b>' . $edklaim['tenor'] . '</b> Bulan</td>
			</tr>
			<tr><td>Tanggal Meninggal <font color="red"><b>*</b></font></td><td>: '. $edklaim['tgl_claim'] . '</td>
				<td align="right">Maksimum Klaim</td><td>: <font color="blue"><b>' . duit($edklaim['kredit_jumlah']) . '</b></font></td>
			</tr>
			<tr><td>Tanggal Terima Laporan</td><td>: '.$edklaim['tglklaimdoc'];
					echo '</td>
			<td align="right">Ms Asuransi Berjalan</td><td>: <b>' . $maj . '</b> Bulan</td></tr>
			<tr><td>Tanggal Investigasi</td><td>: '. $edklaim['tglinvestigasi'];
					echo '</td><td align="right">Nilai Tuntutan Klaim<font color="red"><b>*</b></font></td>
						<td>: '.duit($edklaim['tuntutan_klaim']).' </td>
			<tr><td>Lokasi Meninggal <font color="red"><b>*</b></font></td><td>: '. $edklaim['tempat_meninggal']. '</td>

			</tr>';
			$nmPenyakit = $database->doQuery('SELECT * FROM fu_ajk_namapenyakit where id="'.$edklaim['nmpenyakit'].'" ORDER BY id ASC');
			$nmp_=mysql_fetch_array($nmPenyakit);
			echo '<tr><td>Penyabab Meninggal <font color="red"><b>*</b></font></td><td>:'. $nmp_['namapenyakit']. '</td>
					<td align="right">Tanggal Kelengkapan Dokumen</td><td>: '.$edklaim['tglklaimdoc2'];
						echo '</td>
			</tr>
			<tr><td valign="top">Keterangan Diagnosa <font color="red"><b>*</b></font></td>
			<td>: '.$edklaim['diagnosa']. '</td>
							<td align="right">Tanggal Informasi Ke Asuransi</td><td>: '.$edklaim['tgllaporklaim'];
						echo '</td>
					</tr>
			<tr><!--<td valign="top">Keterangan Dokter Adonai</td><td>: '.$edklaim['ketDokter'].'</td>-->
					<td align="right">Tanggal Kirim Dokumen Ke Asuransi</td><td>: '. $edklaim['tglkirimdoc'];
						echo '</td>
			</tr>
			<tr>';
			$nmPenyakit = $database->doQuery('SELECT * FROM fu_ajk_klaim_status where id="'.$edklaim['id_klaim_status'].'" and view_list=0 ORDER BY order_list ASC');
			$nmPenyakit_ = mysql_fetch_array($nmPenyakit);

			echo '<td valign="top">Status Klaim</td><td>: '.$nmPenyakit_['status_klaim']. '
			</td>
			</tr>
			<tr>
			<td valign="top">Sumber Dana</td>
			<td>: '.$edklaim['sumber_dana'].'</td>
			</td>
			</tr>
			<tr><td align="left">Total Klaim Disetujui<font color="red"><b>*</b></font></td>
				<td>: <input type="text" name="jklaim" value="' . $edklaim['tuntutan_klaim'] . '"> '.$error1.$error3.'</td>
			</tr>
			<tr><td align="left">Tanggal Rencana Bayar<font color="red"><b>*</b></font></td>
				<td align="left">: ';
					print initCalendar();
					print calendarBox('tgl_rencana_bayar', 'triger5', $edklaim['tgl_rencana_bayar']);
					echo '</td>
			</tr>
			<!--<tr><td align="left">Total Rencana Bayar<font color="red"><b>*</b></font></td>
				<td>: <input type="text" name="jklaim" value="' . $edklaim['rencana_bayar'] . '"> '.$error1.$error3.'</td>
			</tr>-->

			<tr><td></td></tr>
			<tr><td><td align="center"><button type="submit" name="ed_oop" value="save">Disetujui</button>&nbsp;
					<button type="submit" name="ed_oop" value="unsave">Ditolak</button></td></tr>';
			echo '
		</table></form>';;
	break;

	case "delklaimtolak":
		$id_peserta = $_REQUEST['id'];
		$id = $_REQUEST['er'];
		$qcn = mysql_fetch_array(mysql_query("SELECT * FROM fu_ajk_cn WHERE id_peserta = '".$id_peserta."'"));
		$query = "UPDATE fu_ajk_penolakan
							SET update_by = '".$q['nm_lengkap']."',
							update_time = '".$futgl."',
							del = 1
							WHERE id = '".$id."'";

		mysql_query($query);
							// echo $query;

		echo '<center><b>Data Tolak telah dibatalkan'.'<br /><meta http-equiv="refresh" content="5;URL=ajk_claim.php?d=klaimtolak&id='.$qcn['id'].'"></b></center>';
	break;

	case "klaimtolak":
		echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
						<tr><th colspan="8" width="100%" align="left" colspan="2">Modul Klaim - Data Meninggal diTOLAK</font></th><th width="5%" colspan="2"><a href="ajk_claim.php?d=all_klaim"><img src="image/back.png" width="20"></a></th></tr>
					</table><br />';

		$edklaim = mysql_fetch_array(mysql_query('SELECT
																										fu_ajk_asuransi.`name` AS asuransi,
																										fu_ajk_cn.id,
																										fu_ajk_cn.idC,
																										fu_ajk_cn.id_cost,
																										fu_ajk_cn.id_cn,
																										fu_ajk_cn.id_dn,
																										fu_ajk_dn.dn_kode,
																										fu_ajk_peserta.id_polis,
																										fu_ajk_peserta.id_peserta,
																										fu_ajk_peserta.nama,
																										fu_ajk_peserta.cabang,
																										fu_ajk_peserta.tgl_lahir,
																										fu_ajk_peserta.usia,
																										fu_ajk_peserta.kredit_tgl,
																										IF(fu_ajk_peserta.type_data="SPK", fu_ajk_peserta.kredit_tenor * 12, fu_ajk_peserta.kredit_tenor) AS tenor,
																										fu_ajk_peserta.kredit_akhir,
																										fu_ajk_peserta.kredit_jumlah,
																										fu_ajk_cn.tgl_claim,
																										fu_ajk_cn.premi,
																										fu_ajk_cn.confirm_claim,
																										fu_ajk_cn.total_claim,
																										fu_ajk_cn.tuntutan_klaim,
																										fu_ajk_cn.tgl_byr_claim,
																										fu_ajk_cn.nmpenyakit,
																										fu_ajk_cn.keterangan,
																										fu_ajk_polis.nmproduk,
																										fu_ajk_klaim.id_klaim_status,
																										fu_ajk_klaim.tgl_klaim AS tglklaim,
																										fu_ajk_klaim.tgl_document AS tglklaimdoc,
																										fu_ajk_klaim.tgl_document_lengkap AS tglklaimdoc2,
																										fu_ajk_klaim.tgl_investigasi AS tglinvestigasi,
																										fu_ajk_klaim.tgl_lapor_klaim AS tgllaporklaim,
																										fu_ajk_klaim.jumlah AS totalklaim,
																										fu_ajk_klaim.tempat_meninggal AS tempat_meninggal,
																										fu_ajk_klaim.diagnosa AS diagnosa,
																										fu_ajk_klaim.sebab_meninggal AS sebab_meninggal,
																										fu_ajk_klaim.ket AS ketklaim,
																										fu_ajk_klaim.sumber_dana,
																										ifnull(fu_ajk_klaim.rencana_bayar,fu_ajk_cn.tuntutan_klaim) as rencana_bayar,
																										fu_ajk_klaim.tgl_rencana_bayar,
																										fu_ajk_klaim.ket_dokter AS ketDokter,
																										fu_ajk_klaim.tgl_kirim_dokumen AS tglkirimdoc,
																										fu_ajk_namapenyakit.id AS idpenyakit,
																										fu_ajk_namapenyakit.namapenyakit
																										FROM fu_ajk_cn
																										LEFT JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
																										LEFT JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
																										LEFT JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
																										LEFT JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
																										LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta AND fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost
																										LEFT JOIN fu_ajk_namapenyakit ON fu_ajk_cn.nmpenyakit = fu_ajk_namapenyakit.id
																										WHERE fu_ajk_cn.id = "' . $_REQUEST['id'] . '" AND fu_ajk_cn.del IS NULL'));

		$adcostumer = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="' . $edklaim['id_cost'] . '"'));

		if ($_REQUEST['ed_oop'] == "save") {
			$penolakan_dari = $_REQUEST['penolakan_dari'];
			$penolakan_ke = $_REQUEST['penolakan_ke'];
			$no_penolakan = $_REQUEST['no_penolakan'];
			$tgl_penolakan = $_REQUEST['tgl_penolakan'];
			$tipe_penolakan = $_REQUEST['tipe_penolakan'];
			$alasan_penolakan = $_REQUEST['alasan_penolakan'];
			$id_peserta = $edklaim['id_peserta'];
			$attachment = $_FILES['attachment']['name'];
			$attachment_tmp = $_FILES['attachment']['tmp_name'];

			if($attachment!= ""){
				$attachment_info = pathinfo($attachment);
				$attachment_extension = strtolower($attachment_info["extension"]); //image extension
				$attachment_name_only = strtolower($attachment_info["filename"]);//file name only, no extension
				$num_file = date('YmdHis');

				$attachment_name = $id_peserta.'-'.$attachment_name_only.'-'.$num_file.'.'.$attachment_extension;

				$destination_folder		= '../ajk_file/klaim/tolak/'.$attachment_name;
				move_uploaded_file($attachment_tmp,$destination_folder) or die( "Could not upload file!");
			}

			$query = "INSERT INTO fu_ajk_penolakan
								SET id_peserta = '".$id_peserta."',
										no_penolakan = '".$no_penolakan."',
										tgl_penolakan = '".$tgl_penolakan."',
										tipe_penolakan = '".$tipe_penolakan."',
										alasan_penolakan = '".$alasan_penolakan."',
										penolakan_dari = '".$penolakan_dari."',
										penolakan_ke = '".$penolakan_ke."',
										attachment ='".$attachment_name."',
										input_by = '".$q['nm_lengkap']."',
										input_time = '".$futgl."'";

			mysql_query($query);

			//header("location:ajk_claim.php");
			echo '<center><b>Data Tolak telah ditambahkan'.'<br /><meta http-equiv="refresh" content="5;URL=ajk_claim.php?d=all_klaim"></b></center>';
		}

		$mets = datediff($edklaim['kredit_tgl'], $edklaim['tgl_claim']);
		$metTgl = explode(",", $mets);
		if ($metTgl[2] > 0) {
			$jumBulan = $metTgl[1] + 1;
		} else {
			$jumBulan = $metTgl[1];
		}

		$maj = ($metTgl[0]*12) + $jumBulan;

		$nmPenyakit = $database->doQuery('SELECT *
																			FROM fu_ajk_namapenyakit
																			WHERE id="'.$edklaim['nmpenyakit'].'"
																			ORDER BY id ASC');
		$nmp_=mysql_fetch_array($nmPenyakit);
		$nmPenyakit = $database->doQuery('SELECT *
																			FROM fu_ajk_klaim_status
																			WHERE id="'.$edklaim['id_klaim_status'].'" and
																						view_list=0
																			ORDER BY order_list ASC');
		$nmPenyakit_ = mysql_fetch_array($nmPenyakit);


		echo '<form method="post" action="">
						<table border="0" cellpadding="3" cellspacing="0" width="80%" align="center" style="border: solid 1px #DEDEDE" align="center">
							<input type="hidden" name="id" value="' . $_REQUEST['id'] . '">
							<input type="hidden" name="kredit_tenor" value="' . $edklaim['tenor'] . '">
							<tr>
								<th colspan="4">Info Debitur</th>
							</tr>
							<tr>
								<td width="25%">Nama Perusahaan</td><td>: <b>' . $adcostumer['name'] . '</b></td>
							</tr>
							<tr>
								<td width="25%">Cabang</td><td>: <b>' . $edklaim['cabang'] . '</b></td>
							<tr>
								<td>Nama Produk</td><td>: <b>' . $edklaim['nmproduk'] . '</b></td>
								<td width="25%" align="right">ID Debitur</td><td>: <b>' . $edklaim['id_peserta'] . '</b></td>
							</tr>
							<tr>
								<td>Name</td><td colspan="3">: <b>' . $edklaim['nama'] . '</b></td>
							</tr>
							<tr>
								<td>Plafond</td><td colspan="3">: <b>' . duit($edklaim['kredit_jumlah']) . '</b></td>
							</tr>
							<tr>
								<td>Tgl Lahir</td><td colspan="3">: <b>' . _convertDate($edklaim['tgl_lahir']) . '</b></td>
							</tr>
							<tr>
								<td>Tanggal Asuransi</td><td>: <b>' . _convertDate($edklaim['kredit_tgl']) . '</b> s/d <b>' . _convertDate($edklaim['kredit_akhir']) . '</b></td>
								<td align="right">Tenor</td><td>: <b>' . $edklaim['tenor'] . '</b> Bulan</td>
							</tr>
							<tr>
								<td>Tanggal Meninggal <font color="red"><b>*</b></font></td><td>: '. $edklaim['tgl_claim'] . '</td>
								<td align="right">Maksimum Klaim</td><td>: <font color="blue"><b>' . duit($edklaim['kredit_jumlah']) . '</b></font></td>
							</tr>
							<tr>
								<td>Tanggal Terima Laporan</td><td>: '.$edklaim['tglklaimdoc'].'</td>
								<td align="right">Ms Asuransi Berjalan</td><td>: <b>' . $maj . '</b> Bulan</td>
							</tr>
							<tr>
								<td>Tanggal Investigasi</td><td>: '. $edklaim['tglinvestigasi'].'</td>
								<td align="right">Nilai Tuntutan Klaim<font color="red"><b>*</b></font></td>
								<td>: '.duit($edklaim['tuntutan_klaim']).' </td>
							</tr>
							<tr>
								<td>Lokasi Meninggal <font color="red"><b>*</b></font></td>
								<td>: '. $edklaim['tempat_meninggal']. '</td>
							</tr>
							<tr>
								<td>Penyabab Meninggal <font color="red"><b>*</b></font></td><td>:'. $nmp_['namapenyakit']. '</td>
								<td align="right">Tanggal Kelengkapan Dokumen</td><td>: '.$edklaim['tglklaimdoc2'].'</td>
							</tr>
							<tr>
								<!--<td valign="top">Keterangan Diagnosa <font color="red"><b>*</b></font></td>
								<td>: '.$edklaim['diagnosa']. '</td>-->
								<td>Tanggal Informasi Ke Asuransi</td><td>: '.$edklaim['tgllaporklaim'].'</td>
							</tr>
							<tr>
								<!--<td valign="top">Keterangan Dokter Adonai</td><td>: '.$edklaim['ketDokter'].'</td>-->
								<td>Tanggal Kirim Dokumen Ke Asuransi</td><td>: '. $edklaim['tglkirimdoc'].'</td>
							</tr>
							<tr>
								<td valign="top">Status Klaim</td><td>: '.$nmPenyakit_['status_klaim']. '</td>
							</tr>
							<tr>
								<td valign="top">Sumber Dana</td>
								<td>: '.$edklaim['sumber_dana'].'</td>
							</tr>
						</table>
					</form>';

					echo '
					<form method="post" name="frm_tolak" action="" enctype="multipart/form-data">
						<table border="0" cellpadding="3" cellspacing="0" width="80%" align="center" style="border: solid 1px #DEDEDE" align="center">
							<tr>
								<th colspan="4">Tanggapan Penolakan</th>
							</tr>';

							$query = "SELECT *
												FROM fu_ajk_penolakan
												WHERE id_peserta = '".$edklaim['id_peserta']."' AND del is null
												ORDER BY tgl_penolakan";

							$qtolak = mysql_query($query);

							while($qklaim = mysql_fetch_array($qtolak)){
								echo '<tr>
												<td width="25%" colspan="2"><h1>'.$qklaim['penolakan_dari'].' -> '.$qklaim['penolakan_ke'].'</h1></td>
											</tr>
											<tr>
												<td width="25%">No Surat</td>
												<td>: <input type="text" value="'.$qklaim['no_penolakan'].'" style="width: 300px;" disabled></input></td>
											</tr>
											<tr>
												<td width="25%">Tgl Surat</td>
												<td>: <input type="text" value="'._convertDate($qklaim['tgl_penolakan']).'" disabled></input></td>
											</tr>
											<tr>
												<td width="25%">Tipe Input</td>
												<td>: <input type="text" value="'.$qklaim['tipe_penolakan'].'" style="width: 200px;" disabled></input></td>
											</tr>

											<tr>
												<td width="25%">Keterangan</td>
												<td>: <textarea cols="70" rows="8" disabled>'.$qklaim['alasan_penolakan'].'</textarea></td>
											</tr>
											<tr>
												<td width="25%">Attachment</td>
												<td>: <a href="../ajk_file/klaim/tolak/'.$qklaim['attachment'].'">'.$qklaim['attachment'].'</a></td>
											</tr>
											<tr style="text-align:left">
												<td colspan="2"><a href="ajk_claim.php?d=delklaimtolak&id='.$qklaim['id_peserta'].'&er='.$qklaim['id'].'" ><font size="3" color="red">Delete</font></a></td>
											</tr>
											<tr><td colspan="4"><hr></td></tr>';
							}

							echo '
							<br>
							<tr>
								<th colspan="4">Input</th>
							</tr>
							<!--<tr>
								<td width="25%">Dari</td>
								<td>: <input type="text" name="penolakan_dari" style="width: 300px;"></input></td>
							</tr>-->
							<!--<tr>
								<td width="25%">Ke</td>
								<td>: <input type="text" name="penolakan_ke" style="width: 300px;"></input></td>
							</tr>-->
							<tr>
								<td width="25%">No Surat</td>
								<td>: <input type="text" name="no_penolakan" style="width: 300px;"></input></td>
							</tr>
							<tr>
								<td width="25%">Tipe Input</td>
								<td>: <select id="tipe_penolakan" name="tipe_penolakan">
										<option disabled selected value>- Pilih -</option>
										<option value="Tolakan Asuransi">Tolakan Asuransi</option>
										<option value="Sanggahan">Sanggahan</option>
										<option value="Informasi Klaim">Informasi Klaim</option>
										<option value="Tolak Bukopin">Tolak Bukopin</option>
									</select>
								</td>
							</tr>

							<tr>
								<td width="25%">Tgl Surat</td>
								<td>:';
								print initCalendar();
								print calendarBox('tgl_penolakan', 'triger5', $qklaim['tgl_penolakan']);
								echo '
								</td>
							</tr>

							<tr>
								<td width="25%">Keterangan</td>
								<td>: <textarea cols="70" rows="8" name="alasan_penolakan"></textarea></td>
							</tr>
							<tr>
								<td width="25%">Attachment</td>
								<td><input id="attachment" name="attachment" type="file"></td>
							</tr>
							<tr><td></td></tr>
							<tr>
								<td colspan="4" align="center"><button type="submit" name="ed_oop" value="save">Submit</button>&nbsp;</td>
							</tr>
						</table>
					</form>';
	break;

	case "bayar_bank" :
		echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
		<tr><th colspan="8" width="100%" align="left" colspan="2">Modul Klaim - Update Pembayaran Ke Bank</font></th><th width="5%" colspan="2"><a href="ajk_claim.php"><img src="image/Backward-64.png" width="20"></a></th></tr>
		</table><br />';
		echo '<fieldset>
		<legend>Searching</legend>
		<table border="0" width="50%" cellpadding="1" cellspacing="1" align="center">
	  <form method="post" action="">
	  <tr><td width="25%" align="right">Nama Produk</td>
		  <td width="30%">: <select id="cat" name="cat">
		  	<option value="">---Pilih Produk---</option>';
		$quer2 = $database->doQuery('SELECT * FROM fu_ajk_polis ORDER BY nopol ASC');
		while ($noticia2 = mysql_fetch_array($quer2)) {
			if ($noticia2['id'] == $cat) {
				echo '<option selected value="' . $noticia2['id'] . '">' . $noticia2['nmproduk'] . '</option><BR>';
			} else {
				echo '<option value="' . $noticia2['id'] . '">' . $noticia2['nmproduk'] . '</option>';
			}
		}
		echo '</select></td><td width="5%" align="right">Nama</td><td>: <input type="text" name="rnama" value="' . $_REQUEST['rnama'] . '"></td>
				<td rowspan="3" align="center">
					<!--<a href="ajk_paid.php?r=paidupload_as1" title="upload data pembayaran perpeserta">
						<img src="image/rmf_2.png" width="25">
					</a>--!>
				</td>
				</tr>
				<tr><td width="10%" align="right">Nomor DN</td><td width="20%">: <input type="text" name="nodn" value="' . $_REQUEST['nodn'] . '"></td>
			<td width="5%" align="right">DOB</td><td>: ';
		print initCalendar();
		print calendarBox('rdob', 'triger', $_REQUEST['rdob']);
		echo '</td></tr>
			<tr><td colspan="4" align="center"><input type="submit" name="dm" value="Searching" class="button"></td></tr>
			</form>
			</table></fieldset>';
		if ($_REQUEST['dm'] == "Searching") {
			if ($_REQUEST['cat']) {
				$satu = 'AND fu_ajk_peserta.id_polis = "' . $_REQUEST['cat'] . '"';
			}
			//if ($_REQUEST['nodn'])			{	$dua = 'AND id_dn = "' . $_REQUEST['nodn'] . '"';		} //before edit by hansen 27-06-2016
			if ($_REQUEST['nodn']) {
				$dua = 'AND fu_ajk_peserta.id_dn = (SELECT id FROM fu_ajk_dn where dn_kode = "' . $_REQUEST['nodn'] . '" ) ';
			}// update by hansen 27-06-2016
			if ($_REQUEST['rnama']) {
				$tiga = 'AND fu_ajk_peserta.nama LIKE "%' . $_REQUEST['rnama'] . '%"';
			}
			$dob = explode("-", $_REQUEST['rdob']);
			$dobpeserta = $dob[2] . '/' . $dob[1] . '/' . $dob[0];
			if ($_REQUEST['rdob']) {
				$empat = 'AND fu_ajk_peserta.tgl_lahir LIKE "%' . $dobpeserta . '%"';
			}

			$cekpolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="' . $_REQUEST['cat'] . '"'));

			echo '<table border="0" width="100%" cellpadding="3" cellspacing="1">
	  	<tr><th width="1%">No</th>
	  	  <th>No Urut</th>
	  	  <th>Nama Perusahaan</th>
	  	  <th>Asuransi</th>
	  	  <th width="1%">Nomor Polis</th>
	  	  <th width="10%">Nomor DN</th>
	  	  <th width="1%">ID Peserta</th>
	  	  <th width="15%">Nama</th>
	  	  <th width="1%">DOB</th>
	  	  <th width="1%">Usia</th>
	  	  <th width="1%">Kredit Awal</th>
	  	  <th width="1%">Tenor</th>
	  	  <th width="1%">Kredit Akhir</th>
	  	  <th width="1%">U P</th>
	  	  <th width="5%">Tuntutan Klaim</th>
	  	  <th width="5%">Cabang</th>
	  	  <th width="5%">Status</th>
	  	  <th width="1%">Proses</th>
	  	</tr>';
			if ($_REQUEST['x']) {
				$m = ($_REQUEST['x'] - 1) * 25;
			} else {
				$m = 0;
			}
			$cekpeserta = $database->doQuery('SELECT fu_ajk_peserta.*,fu_ajk_klaim.id_cn,fu_ajk_klaim.no_urut_klaim,fu_ajk_klaim.id_klaim_status,fu_ajk_klaim.tuntutan_klaim FROM fu_ajk_peserta INNER JOIN fu_ajk_klaim ON fu_ajk_peserta.id_klaim=fu_ajk_klaim.id_cn WHERE (fu_ajk_klaim.id_klaim_status=7 or fu_ajk_klaim.id_klaim_status=6 or fu_ajk_klaim.id_klaim_status=1 or fu_ajk_klaim.id_klaim_status=4 or fu_ajk_klaim.id_klaim_status=5) and fu_ajk_peserta.id !="" ' . $satu . ' ' . $dua . ' ' . $tiga . ' ' . $empat . ' AND fu_ajk_peserta.status_aktif="Lapse" ORDER BY fu_ajk_peserta.id_cost ASC, fu_ajk_peserta.nama ASC, fu_ajk_peserta.id_dn ASC LIMIT ' . $m . ' , 25');
			$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(fu_ajk_peserta.id) FROM fu_ajk_peserta inner join fu_ajk_klaim on fu_ajk_peserta.id_peserta=fu_ajk_klaim.id_peserta WHERE (fu_ajk_klaim.id_klaim_status=7 or fu_ajk_klaim.id_klaim_status=6 or fu_ajk_klaim.id_klaim_status=1 or fu_ajk_klaim.id_klaim_status=4 or fu_ajk_klaim.id_klaim_status=5) and fu_ajk_peserta.id !="" ' . $satu . ' ' . $dua . ' ' . $tiga . ' ' . $empat . ' AND fu_ajk_peserta.status_aktif="Lapse"'));
			$totalRows = $totalRows[0];
			$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
			while ($xpeserta = mysql_fetch_array($cekpeserta)) {
				$xperusahaan = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="' . $xpeserta['id_cost'] . '"'));
				$xpolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="' . $xpeserta['id_polis'] . '"'));
				$xdn = mysql_fetch_array($database->doQuery('SELECT id, dn_kode FROM fu_ajk_dn WHERE id="' . $xpeserta['id_dn'] . '"'));
				$xAsuransi = mysql_fetch_array($database->doQuery('SELECT id, id_bank, id_polis, id_asuransi, id_polis_as, id_dn, id_peserta FROM fu_ajk_peserta_as WHERE id_bank="' . $xpeserta['id_cost'] . '" AND id_polis="' . $xpeserta['id_polis'] . '" AND id_peserta="' . $xpeserta['id_peserta'] . '"'));
				$xAsuransi_ = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_asuransi WHERE id="' . $xAsuransi['id_asuransi'] . '"'));
				$xstatus = mysql_fetch_array($database->doQuery('SELECT status_klaim FROM fu_ajk_klaim_status WHERE id="' . $xpeserta['id_klaim_status'] . '"'));

				if (($no % 2) == 1) $objlass = 'tbl-odd'; else                $objlass = 'tbl-even';
				echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
				  <td align="center">' . (++$no + ($pageNow - 1) * 25) . '</td>
				  <td>' . $xpeserta['no_urut_klaim'] . '</td>
				  <td>' . $xperusahaan['name'] . '</td>
				  <td>' . $xAsuransi_['name'] . '</td>
				  <td align="center">' . $xpolis['nopol'] . '</td>
				  <td>' . $xdn['dn_kode'] . '</td>
				  <td align="center">' . $xpeserta['id_peserta'] . '</td>
				  <td>' . $xpeserta['nama'] . '</td>
				  <td align="center">' . $xpeserta['tgl_lahir'] . '</td>
				  <td align="center">' . $xpeserta['usia'] . '</td>
				  <td align="center">' . $xpeserta['kredit_tgl'] . '</td>
				  <td align="center">' . $xpeserta['kredit_tenor'] . '</td>
				  <td align="center">' . $xpeserta['kredit_akhir'] . '</td>
				  <td align="right">' . duit($xpeserta['kredit_jumlah']) . '</td>
				  <td align="right">' . duit($xpeserta['tuntutan_klaim']) . '</td>
				  <td>' . $xpeserta['cabang'] . '</td>
				  <td><a target="_blank" href="ajk_claim.php?d=suratpengajuan&id='.$xpeserta['id_cn'].'">'.$xstatus['status_klaim'].'</a></td>
				  <td align="center"><a href="ajk_claim.php?d=dbayar_bank&id=' . $xpeserta['id'] . '"><img src="image/createDN.png" width="20"></a></td>
				  </tr>';
			}
			echo '<tr><td colspan="22">';
			echo createPageNavigations($file = 'ajk_claim.php?d=pembas&dm=Searching&cat=' . $_REQUEST['cat'] . '&rnama=' . $_REQUEST['rnama'] . '&nodn=' . $_REQUEST['nodn'] . '&rdob=' . $_REQUEST['rdob'] . '', $total = $totalRows, $psDeh = 10, $anchor = '', $perPage = 25);
			echo '<b>Total Data Peserta: <u>' . $totalRows . '</u></b></td></tr>';
			echo '</table>';
		};
	break;

	case "dbayar_bank" :
			$peserta = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id="' . $_REQUEST['id'] . '"'));
			$datadn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn WHERE dn_kode="' . $peserta['id_dn'] . '" AND id_cost="' . $peserta['id_cost'] . '"'));
			$ppolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="' . $peserta['id_polis'] . '"'));
			$pcostumer = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="' . $peserta['id_cost'] . '"'));
			$dataklaim = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_klaim WHERE id_dn="' . $peserta['id_dn'] . '"'));
			$datacn = mysql_fetch_array($database->doQuery("SELECT ifnull(tgl_bayar_asuransi,'') as tgl_bayar_asuransi1,fu_ajk_cn.* FROM fu_ajk_cn WHERE id='" . $peserta['id_klaim'] . "'  and type_claim='Death' and del is null"));
			$datanote = mysql_fetch_array($database->doQuery("SELECT * FROM fu_ajk_note_as WHERE id_peserta='" . $peserta['id_peserta'] . "'  and note_type='CNC'"));

			if(isset($_REQUEST['oop']) && $_REQUEST['oop']=='Save'){
				if (!$_REQUEST['tgl_bayar']) $error1 .= '<font color="red">Tanggal bayar tidak boleh kosong</font>.';
				if (!$_REQUEST['total_bayar']) $error2 .= '<font color="red"><blink>Klaim tidak bisa di proses, nilai klaim tidak boleh kosong!</blink></font>';
				if (!$_REQUEST['status_pembayaran']) $error3 .= '<font color="red"><blink>Status Klaim tidak boleh kosong!</blink></font>';

				if ($error1 OR $error2 OR $error3) {
				} else {
					$database->doQuery("update fu_ajk_cn set total_claim='".$_REQUEST['total_bayar']."', tgl_byr_claim='".$_REQUEST['tgl_bayar']."',status_bayar='".$_REQUEST['status_pembayaran']."',confirm_claim='Approve(paid)',tgl_document_lengkap='".$_REQUEST['tgl_document_lengkap']."' where id_peserta='" . $peserta ['id_peserta'] . "'");
					$database->doQuery("update fu_ajk_note_as set note_paid_date='".$_REQUEST['tgl_bayar']."', note_paid_total='".$_REQUEST['total_bayar']."',note_status='".$_REQUEST['status_pembayaran']."', note_reference='".$_REQUEST['ref']."', note_desc='".$_REQUEST['ket']."' where id_peserta='" . $peserta ['id_peserta'] . "' and note_type='CNC'");

					if($datacn['tgl_bayar_asuransi1']==''){
						if($dataklaim['id_klaim_status']!=='6'){
							$database->doQuery("update fu_ajk_klaim set id_klaim_status='4',tgl_document_lengkap='".$_REQUEST['tgl_document_lengkap']."' where id_peserta='" . $peserta ['id_peserta'] . "' and type_klaim='Death' and del is null");
						}
					}else{
						$database->doQuery("update fu_ajk_klaim set id_klaim_status='1',tgl_document_lengkap='".$_REQUEST['tgl_document_lengkap']."' where id_peserta='" . $peserta ['id_peserta'] . "' and type_klaim='Death' and del is null");
					}

					if ($_FILES['userfile']['name'] == "") {
						$errno1 = "Silahkan upload file dokumen klaim <font color=red><b>" . $metdoknya['dokumen'] . "!</b></font><br />";
					}
					if ($_FILES['userfile']['name'] != "" AND $_FILES['userfile']['type'][$i] != "application/pdf") {
						$errno1 = "<font color=red>File " . $metdoknya['dokumen'] . " harus Format PDF !</font><br />";
					}
					if ($_FILES['userfile']['size'] / 1024 > $met_ttdsize) {
						$errno1 = "<font color=red>File " . $metdoknya['dokumen'] . " tidak boleh lebih dari 1Mb !</font><br />";
					}
					if (!$errno1) {
						move_uploaded_file($_FILES['userfile']['tmp_name'], $dok_klaim_ajk . 'BYR_AS_' . $metDokumen['id_peserta'] . '_' . $metDokumen['nama'] . '_' . $_FILES["userfile"]["name"]);
						$database->doQuery("update fu_ajk_note_as set note_attachment='".$dok_klaim_ajk . 'BYR_AS_' . $metDokumen['id_peserta'] . '_' . $metDokumen['nama'] . '_' . $_FILES["userfile"]["name"]."' where id_peserta='" . $peserta ['id_peserta'] . "' and note_type='CNC'");

						header("location:ajk_claim.php?d=bayar_bank");
					}else{

						header("location:ajk_claim.php?d=bayar_bank");
					}


				}
			}
			echo '
			<table border="0" cellpadding="3" cellspacing="0" width="100%">
			<tr><th colspan="8" width="100%" align="left" colspan="2">Modul Klaim - Proses Pemabayaran Klaim Ke Bank</font></th><th width="5%" colspan="2"><a href="ajk_claim.php?d=bayar_bank"><img src="image/Backward-64.png" width="20"></a></th></tr>
			</table><br />';

			echo '<form method="post" action="">
		  <table border="0" cellpadding="3" cellspacing="0" width="80%" align="center" style="border: solid 1px #DEDEDE" align="center">
		  <input type="hidden" name="id" value="' . $_REQUEST['id'] . '">
		  <input type="hidden" name="kredit_tenor" value="' . $peserta['kredit_tenor'] . '">
			<tr><th colspan="4">Infromasi Data Klaim Meninggal</th></tr>
			<tr><td width="20%">Nama Perusahaan</td><td>: <b>' . $pcostumer['name'] . '</b></td>
			<td width="25%" align="right">Reg. ID</td><td>: <b>' . $peserta['id_peserta'] . '</b></td>
			</tr>
			<tr><td width="20%" coslpan="3">Nama Produk</td><td>: <b>' . $ppolis['nmproduk'] . '</b></td></tr>
			<tr><td>Name</td><td colspan="3">: <b>' . $peserta['nama'] . '</b></td></tr>
			<tr><td>Sum Insured</td><td colspan="3">: <b>' . duit($peserta['kredit_jumlah']) . '</b></td></tr>
			<tr><td>Period</td><td>: from <b>' . _convertDate($peserta['kredit_tgl']) . '</b> &nbsp; to : <b>' . _convertDate($peserta['kredit_akhir']) . '</b></td>
				<td align="right">Tenor</td><td>: <b>' . $peserta['kredit_tenor'] . '</b> Bulan</td>
			</tr>
			<tr><td>Total Claim <font color="red"><b>*</b></font></td><td>: ' . duit($dataklaim['jumlah']) . '</td>
				<td align="right">Max Claim</td><td>: <font color="blue"><b>' . duit($peserta['kredit_jumlah']) . '</b></font></td>
			</tr>
			<tr><td>Tanggal Meninggal <font color="red"><b>*</b></font></td><td>: ' . $dataklaim['tgl_klaim'];
					echo '</td>
			<td align="right">Ms Asuransi Berjalan</td><td>: <b>' . $maj . '</b> Bulan</td></tr>
			<tr><td>Tanggal Terima Laporan</td><td>: ' . $dataklaim['tgl_document'];
					echo '</td>
				<td align="right">Tanggal Kelengkapan Dokumen</td><td>: ' . $dataklaim['tgl_document_lengkap'];
					echo '</td>
						<tr><td>Tanggal Investigasi</td><td>: ' . $dataklaim['tgl_investigasi'];
					echo '</td>
						<td align="right"><b>Tanggal Bayar Klaim Dari Asuransi</b></td><td>: ' . $datacn['tgl_bayar_asuransi'];
					echo '</td>
					</tr>
					<tr><td>Lokasi Meninggal <font color="red"><b>*</b></font></td><td>: ' . $dataklaim['tempat_meninggal'] . '</td>
					<td align="right">Tanggal Informasi Ke Asuransi</td><td>: ' . $dataklaim['tgl_lapor_klaim'];
					echo '</td></tr>
						<tr><td>Penyebab Meninggal <font color="red"><b>*</b></font><br />' . $error1 . '</td><td valign="top">: ';
					$tgl_kirim_dokumen='';
					if(!empty( $dataklaim['sebab_meninggal'])) {
						$nmPenyakit = $database->doQuery('SELECT * FROM fu_ajk_namapenyakit where id=' . $dataklaim['sebab_meninggal'] . ' ORDER BY id ASC');
						$nmPenyakit_ = mysql_fetch_array($nmPenyakit);
						$tgl_kirim_dokumen=$dataklaim['tgl_kirim_dokumen'];
					}
					echo $nmPenyakit_['namapenyakit'] . '</td>
						<td align="right">Tanggal Kirim Dokument Ke Asuransi</td><td>: '.$tgl_kirim_dokumen;

					echo '</td></tr>
				<tr><td valign="top">Keterangan Diagnosa</td>
				<td>: ' . $dataklaim['diagnosa'] . '</td>
				<td align="right"">Keterangan</td><td>: '.$dataklaim['ket'].'</td>
				</tr>
				<!--<tr><td valign="top">Keterangan Dokter Adonai</td><td>: ' . $dataklaim['ket_dokter'] . '</td>
				</tr>-->
				<tr><td valign="top">Status Klaim</td><td>: ';
					$status='';
					if (!is_null($dataklaim['id_klaim_status'])) {
						$nmPenyakit_ = mysql_fetch_array($nmPenyakit);
						$nmPenyakit = $database->doQuery('SELECT * FROM fu_ajk_klaim_status where id=' . $dataklaim['id_klaim_status'] . ' ORDER BY order_list ASC');
						$status=$nmPenyakit_['status_klaim'];
					}


				echo $status.'
				</td>
				</tr>';
				if ($datadn['ket']!="") {	echo' <tr><td valign="top"><b>Keterangan Underwriting</b></td><td colspan="3">: '.$datadn['ket'].'</td></tr>';	}
				echo '<tr><th colspan="5">Informasi Pembayaran</th></tr>

				<tr><td align="left">Tanggal Kelengkapan Dokumen</td><td>: ';
				print initCalendar();
				print calendarBox('tgl_document_lengkap', 'triger2', $dataklaim['tgl_document_lengkap']);
				echo '<br></td></tr>

				';
				echo '<tr><td>Tanggal Bayar Klaim Ke Bank</td><td> : ';
						print initCalendar();
						print calendarBox('tgl_bayar', 'triger6', $datacn['tgl_byr_claim']);
						echo $error1.'<br></td></tr>

				';
				echo '<tr><td>Total Bayar Klaim Ke Bank</td><td>: <input type="text" name="total_bayar" value="' . $datacn['total_claim'] . '" onkeypress="return isNumberKey(event)"></td></tr>
				<tr><td>Status Pembayaran<font color="red"><b>*</b></font><br />' . $error2 . '</td><td valign="top">:
		    <select size="1" name="status_pembayaran" style="visibility: show;">
		   	<option value="">---Status Pembayaran---</option>';
				$nmPenyakit = $database->doQuery('SELECT * FROM fu_ajk_pembayaran_status where `type`="1" ORDER BY id ASC');
				while ($nmPenyakit_ = mysql_fetch_array($nmPenyakit)) {
					echo '<option value="' . $nmPenyakit_['id'] . '"' . _selected($datanote['note_status'], $nmPenyakit_['id']) . '>' . $nmPenyakit_['pembayaran_status'] . '</option>';
				}
				echo '</select>' . $error3 . '</td></tr>
				<tr><th colspan="5">Kelengkapan Dokumen</th></tr>
				<tr><td>Lampiran File</td><td><input name="userfile" type="file" size="50" onchange="checkfile(this);" >'.$datanote['note_attachment'].$errno1.'</td></tr>
				<tr><td>Referensi Bayar</td><td><textarea name="ref"  cols="40" rows="3">'.$datanote['note_reference'].'</textarea></td></tr>
				<tr><td>Keterangan</td><td><textarea name="ket"  cols="40" rows="3">'.$datanote['note_desc'].'</textarea></td></tr>
				<tr><td colspan="4" align="center"><input type="submit" name="oop" value="Save"></td></tr>
				</table></form>';
					;
	break;

	case "listklaimpending":

		echo '<table border="0" cellpadding="5" cellspacing="0" width="100%"><tr><th width="95%" align="left">List Klaim Pending</font></th><th><a href="ajk_claim.php"><img src="image/back.png" width="20"></a></th>'.$metnewuser.'</tr></table>
				  <table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="#E2E2E2">
					<th width="1%">No</th>
					<th width="5%">Input Date</th>
					<th width="5%">ID Peserta</th>
					<th width="15%">Nama</th>
					<th width="7%">Produk</th>
					<th width="5%">Cabang</th>
					<th width="7%">Plafond</th>
					<th width="5%">Tgl Akad</th>
					<th width="5%">Tgl Klaim</th>
					</tr>';
				if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}

				$qklaimpending = $database->doQuery("SELECT fu_ajk_peserta.id_peserta,
																								fu_ajk_peserta.nama,
																								fu_ajk_cn.id_cabang,
																								fu_ajk_peserta.kredit_jumlah,
																								fu_ajk_peserta.kredit_tgl,
																								fu_ajk_cn.tgl_claim,
																								fu_ajk_polis.nmproduk,
																								fu_ajk_cn.input_date
																				FROM fu_ajk_cn
																						 INNER JOIN fu_ajk_peserta
																						 ON fu_ajk_peserta.id_klaim = fu_ajk_cn.id
																						 INNER JOIN fu_ajk_polis
																						 ON fu_ajk_peserta.id_polis = fu_ajk_polis.id
																				WHERE confirm_claim = 'Pending' and
																							fu_ajk_cn.del is null
																				ORDER BY id_cabang
																				LIMIT ". $m ." , 25");


				$qklaimpending1 = $database->doQuery("SELECT fu_ajk_peserta.nama,
																								fu_ajk_cn.id_cabang,
																								fu_ajk_peserta.kredit_jumlah,
																								fu_ajk_peserta.kredit_tgl,
																								fu_ajk_cn.tgl_claim
																				FROM fu_ajk_cn
																						 INNER JOIN fu_ajk_peserta
																						 ON fu_ajk_peserta.id_klaim = fu_ajk_cn.id
																				WHERE confirm_claim = 'Pending' and
																							fu_ajk_cn.del is null ");

				$totalRows=mysql_num_rows($qklaimpending1);
				$no=$m+1;
				$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
				while($datanya_ = mysql_fetch_array($qklaimpending)){
					if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
					echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
					<td width="1%" align="center">'.$no.'</td>
					<td align="center">'.$datanya_['input_date'].'</td>
					<td align="center">'.$datanya_['id_peserta'].'</td>
					<td>'.$datanya_['nama'].'</td>
					<td align="center">'.$datanya_['nmproduk'].'</td>
					<td align="center">'.$datanya_['id_cabang'].'</td>
					<td align="center">'.$datanya_['kredit_jumlah'].'</td>
					<td align="center">'.$datanya_['kredit_tgl'].'</td>
					<td align="center">'.$datanya_['tgl_claim'].'</td>
					</tr>';
					$no++;
				}
				echo createPageNavigations($file = 'ajk_claim.php?d=listklaimpending', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
				echo '<b>Total Data : <u>' . $totalRows . '</u></b></td></tr>';
				echo '</table>';
		;
	break;

	case "logdocument":
		//echo $_REQUEST['filter'];
		//echo $_REQUEST['option'];
		if($_REQUEST['filter']=="Search"){
				$option = $_REQUEST['option'];
				//echo $option;
				if($option == 1){
					$filter=" and fu_ajk_klaim.tgl_kirim_dokumen = '0000-00-00'";
				}elseif($option == 2){
					$filter=" and fu_ajk_klaim.tgl_document = '0000-00-00'";
				}elseif($option == 3){
					$filter=" and fu_ajk_klaim.tgl_kirim_dokumen != '0000-00-00'";
				}else{
					$filter="";
				}
		}
		echo '<table border="0" cellpadding="5" cellspacing="0" width="100%"><tr><th width="95%" align="left">Log Document</font></th><th><a href="ajk_claim.php"><img src="image/back.png" width="20"></a></th>'.$metnewuser.'</tr></table>

				  <table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="#E2E2E2">
				  	<tr>
				  		<form method="post" action="">
				  			<input type="radio" name="option" value="all" ';if($option=="all"){echo 'checked';} echo '>All <br>
				  			<input type="radio" name="option" value="1" ';if($option=="1"){echo 'checked';} echo '>List Belum dikirim ke Asuransi <br>
				  			<input type="radio" name="option" value="2" ';if($option=="2"){echo 'checked';} echo '>List Belum terima / belum lengkap <br>
				  			<input type="radio" name="option" value="3" ';if($option=="3"){echo 'checked';} echo '>List sudah dikirim <br>
				  			<input type="submit" name="filter" class="button" style="text-align:center" value="Search">
				  		</form>
				  	</tr>
				  	<tr>
							<th width="1%">No</th>
							<th width="15%">Nama</th>
							<th width="5%">Cabang</th>
							<th width="7%">Plafond</th>
							<th width="5%">Tgl Lahir</th>
							<th width="12%">Tgl Klaim</th>
							<th width="12%">Tgl Terima Dokumen</th>
							<th width="12%">Tgl Upload</th>
							<th width="12%">Tgl Kirim Dokumen</th>
						</tr>';
				if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}


				$qklaimpending = $database->doQuery("select fu_ajk_peserta.id_peserta,
																										 fu_ajk_peserta.nama,
																										 fu_ajk_peserta.cabang,
																										 fu_ajk_peserta.kredit_jumlah,
																										 fu_ajk_peserta.tgl_lahir,
																										 fu_ajk_klaim.tgl_klaim,
																										 fu_ajk_klaim.tgl_document as tgl_terima_dokumen,
																										 fu_ajk_klaim.tgl_lapor_klaim as tgl_upload,
																										 fu_ajk_klaim.tgl_kirim_dokumen
																							from fu_ajk_peserta
																									 inner join fu_ajk_klaim
																									 on fu_ajk_klaim.id_cn = fu_ajk_peserta.id_klaim
																							where status_aktif = 'Lapse' and status_peserta = 'Death' ".$filter." LIMIT ". $m ." , 25");


				$qklaimpending1 = $database->doQuery("select fu_ajk_peserta.id_peserta,
																										 fu_ajk_peserta.nama,
																										 fu_ajk_peserta.cabang,
																										 fu_ajk_peserta.kredit_jumlah,
																										 fu_ajk_peserta.tgl_lahir,
																										 fu_ajk_klaim.tgl_klaim,
																										 fu_ajk_klaim.tgl_document as tgl_terima_dokumen,
																										 fu_ajk_klaim.tgl_lapor_klaim as tgl_upload,
																										 fu_ajk_klaim.tgl_kirim_dokumen
																							from fu_ajk_peserta
																									 inner join fu_ajk_klaim
																									 on fu_ajk_klaim.id_cn = fu_ajk_peserta.id_klaim
																							where status_aktif = 'Lapse' and status_peserta = 'Death' ".$filter." ");

				$totalRows=mysql_num_rows($qklaimpending1);
				$no=$m+1;
				$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
				while($datanya_ = mysql_fetch_array($qklaimpending)){
					if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
					echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
					<td width="1%" align="center">'.$no.'</td>
					<td>'.$datanya_['nama'].'</td>
					<td align="center">'.$datanya_['cabang'].'</td>
					<td align="center">'.duit($datanya_['kredit_jumlah']).'</td>
					<td align="center">'.$datanya_['tgl_lahir'].'</td>
					<td align="center">'.$datanya_['tgl_klaim'].'</td>
					<td align="center">'.$datanya_['tgl_terima_dokumen'].'</td>
					<td align="center">'.$datanya_['tgl_upload'].'</td>
					<td align="center">'.$datanya_['tgl_kirim_dokumen'].'</td>
					</tr>';
					$no++;
				}
				if($_REQUEST['filter']=="Search"){
					echo createPageNavigations($file = 'ajk_claim.php?d=logdocument&filter='.$_REQUEST['filter'].'&option='.$_REQUEST['option'], $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
				}else{
					echo createPageNavigations($file = 'ajk_claim.php?d=logdocument', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
				}

				echo '<b>Total Data : <u>' . $totalRows . '</u></b></td></tr>';
				echo '</table>';
		;
	break;

	case "suratpengajuan" :
		if ($_REQUEST['up_pengajuan'] == "Create Pengajuan" or $_REQUEST['up_pengajuan'] == "Create Kelengkapan" or $_REQUEST['up_pengajuan'] == "Create Pembayaran" or $_REQUEST['up_pengajuan'] == "Create Informasi Medical (Bank)" or $_REQUEST['up_pengajuan'] == "Create Pengajuan Medical (Asuransi)") {

			//update header
			$tambahdok = $database->doQuery("update fu_ajk_klaim set no_surat_pembayaran = '".$_REQUEST['sp']."', no_surat_pengajuan ='".$_REQUEST['nsk']."', no_surat_bank='".$_REQUEST['sk']."',no_tanda_terima='".$_REQUEST['ntt']."',tgl_surat_bank='".$_REQUEST['tgl']."',no_pengajuan_medical = '".$_REQUEST['pm']."',no_info_medical = '".$_REQUEST['im']."' where id_cn = ".$_REQUEST['id_klaim_pengajuan']);

			$tambahdok = $database->doQuery('update fu_ajk_klaim_doc SET dok_kirim="F" where id_klaim ='.$_REQUEST['id_klaim_pengajuan']);
			//update detail
			foreach ($_REQUEST['ya'] as $doc => $docya) {
				$tambahdok = $database->doQuery('update fu_ajk_klaim_doc SET dok_kirim="T" where id='.$docya);
			}
			if($_REQUEST['up_pengajuan'] == "Create Pengajuan"){
				header('Location: e_surat.php?er=eL_pengajuanklaim&tipe=pengajuan&nsk='.$_REQUEST['nsk'].'&sk='.$_REQUEST['sk'].'&ntt='.$_REQUEST['ntt'].'&tgl='.$_REQUEST['tgl'].'&id='.$_REQUEST['id_klaim_pengajuan'].'&tgltt='.$_REQUEST['tgltt']);
			}elseif($_REQUEST['up_pengajuan'] == "Create Kelengkapan"){
				header('Location: e_surat.php?er=eL_pengajuanklaim&tipe=kelengkapan&nsk='.$_REQUEST['nsk'].'&sk='.$_REQUEST['sk'].'&tgl='.$_REQUEST['tgl'].'&id='.$_REQUEST['id_klaim_pengajuan']);
			}elseif($_REQUEST['up_pengajuan'] == "Create Dokumen Lengkap"){
				header('Location: e_surat.php?er=eL_pengajuanklaim&tipe=doklengkap&nsk='.$_REQUEST['nsk'].'&sk='.$_REQUEST['sk'].'&tgl='.$_REQUEST['tgl'].'&id='.$_REQUEST['id_klaim_pengajuan']);
			}elseif($_REQUEST['up_pengajuan'] == "Create Pengajuan Medical (Asuransi)"){
				header('Location: e_surat.php?er=eL_pengajuanklaim&tipe=pengajuanmedical&pm='.$_REQUEST['pm'].'&id='.$_REQUEST['id_klaim_pengajuan']);
			}elseif($_REQUEST['up_pengajuan'] == "Create Informasi Medical (Bank)"){
				header('Location: e_surat.php?er=eL_pengajuanklaim&tipe=infomedical&im='.$_REQUEST['im'].'&id='.$_REQUEST['id_klaim_pengajuan']);
			}else{
				header('Location: e_surat.php?er=eL_pengajuanklaim&tipe=pembayaran&nsk='.$_REQUEST['nsk'].'&sp='.$_REQUEST['sp'].'&sk='.$_REQUEST['sk'].'&teb='.$_REQUEST['teb'].'&tgl='.$_REQUEST['tgl'].'&id='.$_REQUEST['id_klaim_pengajuan']);
			}

		}

		$qklaim = mysql_fetch_array($database->doQuery('select fu_ajk_klaim.*, fu_ajk_cn.tuntutan_klaim
				from
				fu_ajk_cn
				INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
				INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
				INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
				INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta AND fu_ajk_cn.id = fu_ajk_peserta.id_klaim
				LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
				LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim.id_klaim_status = fu_ajk_klaim_status.id
				where fu_ajk_cn.id = '.$_REQUEST['id']));
		echo '<table border="0" cellpadding="5" cellspacing="0" width="100%"><tr><th width="95%" align="left">Surat Pengajuan Klaim</font></th><th><a href="ajk_claim.php"><img src="image/back.png" width="20"></a></th>'.$metnewuser.'</tr></table>';
		echo '<table border="0" width="100%" cellpadding="1" cellspacing="1">
					<form method="post" name="frm_pengajuan" action="">
						<tr>
							<td align="right" width="15%">Nomor Surat Pengajuan : </td>
							<td><input type="text" size="35" name="nsk" id="nsk" value="'.$qklaim['no_surat_pengajuan'].'"></td>
						</tr>
						<tr>
							<td align="right" width="15%">Nomor Tanda Terima : </td>
							<td><input type="text" size="35" name="ntt" id="ntt" value="'.$qklaim['no_tanda_terima'].'"></td>
						</tr>
						<tr>
							<td align="right" width="15%">Nomor Surat dari Bukopin : </td>
							<td><input type="text" name="sk" id="sk" value="'.$qklaim['no_surat_bank'].'"></td>
						</tr>
						<tr>
							<td align="right" width="15%">Nomor Surat Pembayaran : </td>
							<td><input type="text" size="35" name="sp" id="sp" value="'.$qklaim['no_surat_pembayaran'].'"></td>
						</tr>
						<tr>
							<td align="right" width="15%">Nomor Surat Pengajuan Medical : </td>
							<td><input type="text" size="35" name="pm" id="pm" value="'.$qklaim['no_pengajuan_medical'].'"></td>
						</tr>
						<tr>
							<td align="right" width="15%">Nomor Surat Informasi Medical : </td>
							<td><input type="text" size="35" name="im" id="im" value="'.$qklaim['no_info_medical'].'"></td>
						</tr>
						<tr>
							<td align="right" width="15%">Nilai Tuntutan Klaim : </td>
							<td><input type="text" name="teb" id="teb" value="'.$qklaim['tuntutan_klaim'].'"></td>
						</tr>
						<tr>
							<td><input type="hidden" name="id_klaim_pengajuan" id="id_klaim_pengajuan" value="'.$_REQUEST['id'].'"></td>
						</tr>
						<tr>
							<td align="right" width="15%">Tanggal Surat dari Bukopin :</td>
							<td>';
							print initCalendar();
							print calendarBox('tgl', 'triger1', $qklaim['tgl_surat_bank']);
	  					echo '
	  					</td>
						</tr>
						<tr>
							<td align="right" width="15%">Tanggal Tanda Terima : </td>
							<td>';
							print initCalendar();
							print calendarBox('tgltt', 'triger2', date('Y-m-d'));
	  					echo '
	  					</td>
						</tr>
						<tr>
							<th colspan="5">Kelengkapan Dokumen</th>
						</tr>
						<tr>
							<td colspan="5">
								<table border="0" cellpadding="3" cellspacing="1" width="100%" align="center" style="border: solid 1px #DEDEDE" align="center">
								<tr>
									<th width="5%">No</th>
									<th width="5%">Option</th>
									<th>Dokumen</th>
								</tr>';

								$metdokumen = $database->doQuery("select fu_ajk_klaim_doc.id,
																										 fu_ajk_dokumenklaim.nama_dok,
																										 fu_ajk_klaim_doc.dok_kirim
																							from fu_ajk_klaim_doc
																									 inner join fu_ajk_dokumenklaim_bank
																									 on fu_ajk_dokumenklaim_bank.id = fu_ajk_klaim_doc.dokumen
 																									 inner join fu_ajk_dokumenklaim
																									 on fu_ajk_dokumenklaim.id = fu_ajk_dokumenklaim_bank.id_dok
																							where id_klaim = ".$_REQUEST['id'] ." and fu_ajk_klaim_doc.del is NULL
																							order by fu_ajk_dokumenklaim.urut asc");

								while ($rdok = mysql_fetch_array($metdokumen)) {
									if (($no % 2) == 1) $objlass = 'tbl-odd'; else    $objlass = 'tbl-even';
										echo '
										<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
										<td align="center">' . ++$no . '</td>';

								  if($rdok['dok_kirim'] == 'T'){
										echo '<td align="center"><input type="checkbox" name="ya[]" value="' . $rdok['id'] . '" checked></td>';
									}else{
										echo '<td align="center"><input type="checkbox" name="ya[]" value="' . $rdok['id'] . '"></td>';
									}
									echo '
									<td>' . $rdok['nama_dok'] . '</td>
									</tr>';
								}
								echo '</table>
							</td>
						</tr>
						<tr>
							<td colspan="4" align="center">
								<input type="submit" name="up_pengajuan" class="button" style="text-align:center" value="Create Pengajuan">
								<input type="submit" name="up_pengajuan" class="button" style="text-align:center" value="Create Kelengkapan">
								<input type="submit" name="up_pengajuan" class="button" style="text-align:center" value="Create Pembayaran">
								<input type="submit" name="up_pengajuan" class="button" style="text-align:center" value="Create Dokumen Lengkap">
								<input type="submit" name="up_pengajuan" class="button" style="text-align:center" value="Create Pengajuan Medical (Asuransi)">
								<input type="submit" name="up_pengajuan" class="button" style="text-align:center" value="Create Informasi Medical (Bank)">
							</td>
						</tr>
					</form>
				</table>';
	break;

	case "suratpengajuan1" :
		function auto_number($nilai_default,$panjang_nomor,$nama_db,$nama_tb,$ulangi,$tanggal_tb)
		{
			if($ulangi=="")
			{
				$pjg_nomor=strlen($nilai_default)+1;
				$query="SELECT MAX(SUBSTRING($nama_tb,$pjg_nomor,$panjang_nomor))+1 AS nu FROM $nama_db where del is null";
				$result=mysql_query($query);
				$data=mysql_fetch_array($result);
				$jml_nol=str_repeat("0", $panjang_nomor);
				if(is_null($data['nu']))
				{
					$hasil=$nilai_default.substr($jml_nol."1", -$panjang_nomor);
				}
				else
				{
					$hasil=$nilai_default.substr($jml_nol.$data['nu'], -$panjang_nomor);
				}
				return $hasil;
			}
			else
			{
				$pjg_nomor=strlen($nilai_default)+1;
				if($ulangi=='year'){
					$query="SELECT MAX(SUBSTRING($nama_tb,$pjg_nomor,$panjang_nomor))+1 AS nu FROM $nama_db where $ulangi($tanggal_tb)=$ulangi(curdate()) and del is null";
				}elseif($ulangi=='month'){
					$query="SELECT MAX(SUBSTRING($nama_tb,$pjg_nomor,$panjang_nomor))+1 AS nu FROM $nama_db where $ulangi($tanggal_tb)=$ulangi(curdate()) and year($tanggal_tb)=year(curdate()) and del is null";
				}elseif($ulangi=='day'){
					$query="SELECT MAX(SUBSTRING($nama_tb,$pjg_nomor,$panjang_nomor))+1 AS nu FROM $nama_db where $ulangi($tanggal_tb)=$ulangi(curdate()) and month($tanggal_tb)=month(curdate()) and year($tanggal_tb)=year(curdate()) and del is null";
				}
				//echo $query;
				$result=mysql_query($query);
				$data=mysql_fetch_array($result);
				$jml_nol=str_repeat("0", $panjang_nomor);
				if(is_null($data['nu']))
				{
					$hasil=$nilai_default.substr($jml_nol."1", -$panjang_nomor);
				}
				else
				{
					$hasil=$nilai_default.substr($jml_nol.$data['nu'], -$panjang_nomor);
				}
				return $hasil;
			}
		}
		function romanic_number($integer, $upcase = true)
		{
			$table = array('M'=>1000, 'CM'=>900, 'D'=>500, 'CD'=>400, 'C'=>100, 'XC'=>90, 'L'=>50, 'XL'=>40, 'X'=>10, 'IX'=>9, 'V'=>5, 'IV'=>4, 'I'=>1);
			$return = '';
			while($integer > 0)
			{
				foreach($table as $rom=>$arb)
				{
					if($integer >= $arb)
					{
						$integer -= $arb;
						$return .= $rom;
						break;
					}
				}
			}

			return $return;
		}
		if(isset($_POST['simpan_surat'])){
			$no_suratnya1=auto_number("", "4", "fu_ajk_klaim", "no_surat_reminder1", "year", "tgl_surat_reminder1");
			$no_suratnya2=auto_number("", "4", "fu_ajk_klaim", "no_surat_reminder2", "year", "tgl_surat_reminder2");
			$no_suratnya3=auto_number("", "4", "fu_ajk_klaim", "no_surat_reminder3", "year", "tgl_surat_reminder3");


			$database->doQuery('update fu_ajk_klaim set tgl_surat_reminder'.$_GET['rem'].'=current_date(), no_surat_reminder'.$_GET['rem'].'="'.$_POST['nsk'].'",ket_surat_reminder'.$_GET['rem'].'="'.$_POST['ket'].'" where fu_ajk_klaim.id_cn="'.$_REQUEST['id'].'"');
			header("location:ajk_claim.php?d=suratpengajuan1&rem=".$_GET['rem']."&id=".$_GET['id']);
			exit();
		}

		$no_suratnya1=auto_number("", "4", "fu_ajk_klaim", "no_surat_reminder1", "year", "tgl_surat_reminder1");
		$no_suratnya2=auto_number("", "4", "fu_ajk_klaim", "no_surat_reminder2", "year", "tgl_surat_reminder2");
		$no_suratnya3=auto_number("", "4", "fu_ajk_klaim", "no_surat_reminder3", "year", "tgl_surat_reminder3");


			$qklaim = mysql_fetch_array($database->doQuery('select fu_ajk_klaim.*, fu_ajk_cn.tuntutan_klaim, fu_ajk_cn.keterangan as keterangan_klaim,fu_ajk_peserta.nama
				from
				fu_ajk_cn
				INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
				INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
				INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
				INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta AND fu_ajk_cn.id = fu_ajk_peserta.id_klaim
				LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
				LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim.id_klaim_status = fu_ajk_klaim_status.id
				where fu_ajk_cn.id = '.$_REQUEST['id']));

		if(intval($no_suratnya1) < intval($no_suratnya2)){
			if(intval($no_suratnya2) < intval($no_suratnya3)){
				$no_reminder=$no_suratnya3.'/R.'.$_GET['rem'].'/APA-BUKOPIN/'.romanic_number(date("m")).'/'.date("Y");
			}else{
				$no_reminder=$no_suratnya2.'/R.'.$_GET['rem'].'/APA-BUKOPIN/'.romanic_number(date("m")).'/'.date("Y");
			}
		}else{
			if(intval($no_suratnya1) < intval($no_suratnya3)){
				$no_reminder=$no_suratnya3.'/R.'.$_GET['rem'].'/APA-BUKOPIN/'.romanic_number(date("m")).'/'.date("Y");
			}else{
				$no_reminder=$no_suratnya1.'/R.'.$_GET['rem'].'/APA-BUKOPIN/'.romanic_number(date("m")).'/'.date("Y");
			}
		}

		echo '<table border="0" cellpadding="5" cellspacing="0" width="100%"><tr><th width="95%" align="left">Surat Reminder '.$_GET['rem'].'</font></th><th><a href="ajk_claim.php?d=kadaluarsa"><img src="image/back.png" width="20"></a></th>'.$metnewuser.'</tr></table>';

		if($_GET['rem']=='1'){
		if(empty($qklaim['ket_surat_reminder1'])){
			$ket_klaim=$qklaim['keterangan_klaim'];
			$no_surat=$no_reminder;
		}else{
			$ket_klaim=$qklaim['ket_surat_reminder1'];
			$no_surat=$qklaim['no_surat_reminder1'];
		}
		echo '<table border="0" width="100%" cellpadding="1" cellspacing="1">
						<form method="post" name="frm_pengajuan" action="">
							<tr>
								<td align="right" width="15%">Id Peserta : </td>
								<td>'.$qklaim['id_peserta'].'</td>
							</tr>
							<tr>
								<td align="right" width="15%">Nama : </td>
								<td>'.$qklaim['nama'].'</td>
							</tr>

							<tr>
								<td align="right" width="15%">Nomor Surat : </td>
								<td><input type="text" size="35" name="nsk" id="nsk" value="'.$no_surat.'"></td>
							</tr>
							<tr>
								<td align="right" width="15%">Keterangan : </td>
								<td><textarea name="ket" cols="140" rows="5">'.$ket_klaim.'</textarea></td>
							</tr>
							<tr>
								<td colspan="4" align="center">
									<input type="submit" name="simpan_surat" class="button" style="text-align:center" value="Create Reminder">';
									if(!empty($qklaim['no_surat_reminder1'])){
										echo '&nbsp;&nbsp;<a title="Preview" target="_blank" href="e_surat.php?er=eL_pengajuanklaim&tipe=kadaluarsa&rem=1&id='.$qklaim['id_cn'].'"><img src="image/print.png" width="20"></a>';
										echo '&nbsp;&nbsp;<a title="Kirim Email" href="e_surat.php?er=sendmailkadaluarsapdf&rem=1&id='.$qklaim['id_cn'].'"><img src="image/print.png" width="20"></a>';

									}
								echo '</td>
							</tr>
						</form>
					</table>';
		}elseif($_GET['rem']=='2'){
		if(empty($qklaim['ket_surat_reminder2'])){
			$ket_klaim=$qklaim['keterangan_klaim'];
			$no_surat=$no_reminder;
		}else{
			$ket_klaim=$qklaim['ket_surat_reminder2'];
			$no_surat=$qklaim['no_surat_reminder2'];
		}
			echo '<table border="0" width="100%" cellpadding="1" cellspacing="1">
						<form method="post" name="frm_pengajuan" action="">
							<tr>
								<td align="right" width="15%">Id Peserta : </td>
								<td>'.$qklaim['id_peserta'].'</td>
							</tr>
							<tr>
								<td align="right" width="15%">Nama : </td>
								<td>'.$qklaim['nama'].'</td>
							</tr>
							<tr>
								<td align="right" width="15%">Nomor Surat : </td>
								<td><input type="text" size="35" name="nsk" id="nsk" value="'.$no_surat.'"></td>
							</tr>
							<tr>
								<td align="right" width="15%">Keterangan : </td>
								<td><textarea name="ket" cols="140" rows="5">'.$ket_klaim.'</textarea></td>
							</tr>
							<tr>
								<td colspan="4" align="center">
									<input type="submit" name="simpan_surat" class="button" style="text-align:center" value="Create Reminder">';
									if(!empty($qklaim['no_surat_reminder2'])){
										echo '&nbsp;&nbsp;<a title="Preview" target="_blank" href="e_surat.php?er=eL_pengajuanklaim&tipe=kadaluarsa&rem=2&id='.$qklaim['id_cn'].'"><img src="image/print.png" width="20"></a>';
										echo '&nbsp;&nbsp;<a title="Kirim Email" href="e_surat.php?er=sendmailkadaluarsapdf&rem=2&id='.$qklaim['id_cn'].'"><img src="image/print.png" width="20"></a>';
									}
								echo '
								</td>
							</tr>
						</form>
					</table>';
		}elseif($_GET['rem']=='3'){
		if(empty($qklaim['ket_surat_reminder3'])){
			$ket_klaim=$qklaim['keterangan_klaim'];
			$no_surat=$no_reminder;
		}else{
			$ket_klaim=$qklaim['ket_surat_reminder3'];
			$no_surat=$qklaim['no_surat_reminder3'];
		}
			echo '<table border="0" width="100%" cellpadding="1" cellspacing="1">
						<form method="post" name="frm_pengajuan" action="">
							<tr>
								<td align="right" width="15%">Id Peserta : </td>
								<td>'.$qklaim['id_peserta'].'</td>
							</tr>
							<tr>
								<td align="right" width="15%">Nama : </td>
								<td>'.$qklaim['nama'].'</td>
							</tr>
							<tr>
								<td align="right" width="15%">Nomor Surat : </td>
								<td><input type="text" size="35" name="nsk" id="nsk" value="'.$no_surat.'"></td>
							</tr>
							<tr>
								<td align="right" width="15%">Keterangan : </td>
								<td><textarea name="ket" cols="140" rows="5">'.$ket_klaim.'</textarea></td>
							</tr>
							<tr>
								<td colspan="4" align="center">
									<input type="submit" name="simpan_surat" class="button" style="text-align:center" value="Create Reminder">';
									if(!empty($qklaim['no_surat_reminder3'])){
										echo '&nbsp;&nbsp;<a title="Preview" target="_blank" href="e_surat.php?er=eL_pengajuanklaim&tipe=kadaluarsa&rem=3&id='.$qklaim['id_cn'].'"><img src="image/print.png" width="20"></a>';
										echo '&nbsp;&nbsp;<a title="Kirim Email" href="e_surat.php?er=sendmailkadaluarsapdf&rem=3&id='.$qklaim['id_cn'].'"><img src="image/print.png" width="20"></a>';
									}
								echo '
								</td>
							</tr>
						</form>
					</table>';
		}
	break;

	case "investigasi_klaim_bak" :
		if(!isset($_REQUEST['tglinput'])){
			$dateku=date("Y-m-d");
			$dateku1=date("Y-m-d");
			$tglmu='and date(fu_ajk_cn.approve_date) between "'.$dateku.'" and "'.$dateku1.'"';
		}elseif($_REQUEST['tglinput']==""){
			$dateku='';
			$dateku1='';
			$tglmu='';
		}else{
			$dateku=$_REQUEST['tglinput'];
			$dateku1=$_REQUEST['tglinput1'];
			$tglmu='and date(fu_ajk_cn.approve_date) between "'.$dateku.'" and "'.$dateku1.'"';

		}

		echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
			<tr><th colspan="8" width="100%" align="left" colspan="2">Modul Klaim - Investigasi Klaim</font></th></tr>
			</table><br />';
		echo '<fieldset>
		<legend>Searching</legend>
		<form method="post" action="">
		<table border="0" width="80%" cellpadding="1" cellspacing="0" align="center">
	  	<tr>
	  		<td width="10%" align="center">ID PESERTA</td>
				<td width="10%" align="center">NAMA</td>
				<td width="10%" align="center">STATUS KLAIM</td>
				<td width="10%" align="center">STATUS INVESTIGASI</td>
				<td width="30%" align="center">TANGGAL APPROVE KLAIM</td>
			</tr>
			<tr>
				<td align="center"><input type="text" name="ridp" value="'.$_REQUEST['ridp'].'"></td>
				<td align="center"><input type="text" name="rnama" value="'.$_REQUEST['rnama'].'"></td>
				<td align="center">
					<select name="status">
						<option value="" selected>-Pilih-</option>';
					$qstatusklaim = mysql_query("SELECT * FROM fu_ajk_klaim_status ORDER BY order_list ASC");
					while($qstatusklaimr = mysql_fetch_array($qstatusklaim)){
						echo '<option value="'.$qstatusklaimr['id'].'"'._selected($_REQUEST['status'],$qstatusklaimr['id']).'>'.$qstatusklaimr['status_klaim'].'</option>';
					}
				echo' </select>
				</td>

				<td align="center"><select name="status_k">
						<option value="N" '._selected($_REQUEST['status_k'], "N").'>Unapprove</option>
						<option value="P" '._selected($_REQUEST['status_k'], "P").'>Pending</option>
						</select></td>
			  <td align="center">';
		print initCalendar();
		print calendarBox('tglinput', 'triger1', $dateku);
		print initCalendar();
		print calendarBox('tglinput1', 'triger2', $dateku1);
		echo '</td></tr>
	  	<tr><td colspan="6" align="center"><input type="submit" name="oppe" value="Searching" class="button"></td></tr>
		</table></form></fieldset>';
		//}
		echo '<table border="0" width="100%" cellpadding="5" cellspacing="1" bgcolor="#bde0e6">
		<tr><th width="3%">No</td>
		<th>Input Date</td>
		<th>No. Urut</td>
		<th>Asuransi</td>
		<th width="5%">ID Peserta</td>
		<th width="1%">ID DN</td>
		<th width="1%">Produk</td>
		<th>Nama Debitur</td>
		<th>Cabang</td>
		<th width="5%">Kredit Awal</td>
		<th width="5%">Kredit Akhir</td>
		<th width="5%">Tgl Klaim</td>
		<th width="1%">Tgl Approve</td>
		<th width="1%">Tenor</td>
		<!-- <th width="1%">Tenor (S,B)</td> --!>
		<th width="1%">Jumlah</td>
		<th width="1%">Status</td>
		<th width="1%">Tgl Bayar Klaim</td>
		<th width="1%">jDoc</td>
		<th width="1%">Status</td>
		<th width="5%">Investigasi</td>
		</tr>';
		if ($_REQUEST['ridp'])		{	$empat = 'AND fu_ajk_peserta.id_peserta LIKE "%' . $_REQUEST['ridp'] . '%"';		}
		if ($_REQUEST['nodn'])		{	$satu = 'AND fu_ajk_dn.dn_kode LIKE "%' . $_REQUEST['nodn'] . '%"';		}
		if ($_REQUEST['nocn'])		{	$dua = 'AND fu_ajk_cn.id_cn LIKE "%' . $_REQUEST['nocn'] . '%"';		}
		if (isset($_REQUEST['status_k']) && !empty($_REQUEST['status_k']))	{
			if($_REQUEST['status_k']=='N'){
				$lima = 'AND fu_ajk_klaim.investigasi=""';
			}else{

				$lima = 'AND fu_ajk_klaim.investigasi="'.$_REQUEST['status_k'].'"';
			}

		}

		if ($_REQUEST['status'])		{	$enam = 'AND fu_ajk_klaim_status.id ='.$_REQUEST['status'] ;}

		if ($_REQUEST['rnama'])		{	$tiga = 'AND fu_ajk_peserta.nama LIKE "%'.$_REQUEST['rnama'].'%"';
		$namanya = mysql_fetch_array($database->doQuery('SELECT id_klaim, status_peserta, id_peserta FROM fu_ajk_peserta WHERE id_klaim !="" AND status_peserta="Death" '.$tiga.' '));
		$ernama = 'AND fu_ajk_peserta.id_peserta = "' . $namanya['id_peserta'] . '"';
		}
		if ($_REQUEST['rdob'])		{	$empat = 'AND tgl_lahir = "' . $_REQUEST['rdob'] . '"';
		$dobnya = mysql_fetch_array($database->doQuery('SELECT id_klaim, status_peserta, id_peserta FROM fu_ajk_peserta WHERE id_klaim !="" AND status_peserta="Death" '.$empat.' '));
		$erdob = 'AND fu_ajk_peserta.tgl_lahir = "' . $dobnya['tgl_lahir'] . '"';
		}

		if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}

		//$metklaim = $database->doQuery('SELECT * FROM fu_ajk_cn WHERE type_claim = "Death" '.$satu.' '.$dua.' '.$ernama.' '.$erdob.' AND del IS NULL ORDER BY id DESC LIMIT ' . $m . ' , 25');
		$metklaim = $database->doQuery('SELECT
			fu_ajk_asuransi.`name` AS asuransi,
			fu_ajk_cn.id,
			fu_ajk_klaim.no_urut_klaim,
			fu_ajk_klaim.id as id_klaim,
			fu_ajk_cn.id_cost,
			fu_ajk_cn.id_cn,
			fu_ajk_dn.dn_kode,
			fu_ajk_peserta.id_peserta,
			fu_ajk_peserta.nama,
			fu_ajk_peserta.tgl_lahir,
			fu_ajk_peserta.usia,
			fu_ajk_peserta.kredit_tgl,
			IF(fu_ajk_peserta.type_data="SPK", fu_ajk_peserta.kredit_tenor * 12, fu_ajk_peserta.kredit_tenor) AS tenor,
			fu_ajk_peserta.kredit_akhir,
			fu_ajk_peserta.kredit_jumlah,
			fu_ajk_cn.tgl_claim,
			fu_ajk_cn.premi,
			fu_ajk_cn.confirm_claim,
			fu_ajk_cn.total_claim,
			fu_ajk_cn.tuntutan_klaim,
			fu_ajk_cn.tgl_byr_claim,
			fu_ajk_polis.nmproduk,
			fu_ajk_peserta.cabang,
			fu_ajk_klaim.tgl_kirim_dokumen,
			fu_ajk_klaim.investigasi,
			fu_ajk_klaim.pending_note,
			fu_ajk_cn.tgl_bayar_asuransi,
			fu_ajk_cn.input_date,
			fu_ajk_cn.approve_date,
			fu_ajk_klaim_status.status_klaim
			FROM
			fu_ajk_cn
			INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
			INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
			INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
			INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta AND fu_ajk_cn.id = fu_ajk_peserta.id_klaim
			LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
			LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim.id_klaim_status = fu_ajk_klaim_status.id
			WHERE
			fu_ajk_cn.type_claim = "Death" AND fu_ajk_cn.confirm_claim <> "Pending" AND fu_ajk_cn.del IS NULL
			and (fu_ajk_klaim.investigasi="N" or fu_ajk_klaim.investigasi="" or fu_ajk_klaim.investigasi="P")
			'.$tglmu.'
			'.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.'
			ORDER BY fu_ajk_klaim.investigasi desc, fu_ajk_cn.id DESC LIMIT ' . $m . ' , 25');
		//$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_cn WHERE id != "" AND type_claim="Death" '.$satu.' '.$dua.' '.$ernama.' '.$erdob.' AND del IS NULL'));
		$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(fu_ajk_cn.id_cn) FROM
			fu_ajk_cn
			INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
			INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
			INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
			INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta AND fu_ajk_cn.id = fu_ajk_peserta.id_klaim
			LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
			LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim.id_klaim_status = fu_ajk_klaim_status.id
			WHERE fu_ajk_cn.type_claim = "Death" AND fu_ajk_cn.confirm_claim <> "Pending" AND fu_ajk_cn.del IS NULL '.$tglmu.' '.$satu.' '.$dua.' '.$tiga.'  '.$empat.' '.$lima.' '.$enam));
		$totalRows = $totalRows[0];
		$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
		while ($rklaim = mysql_fetch_array($metklaim)) {
			/*
			 $klaim_dn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn WHERE id="'.$rklaim['id_dn'].'"'));
			 $klaimpeserta = mysql_fetch_array($database->doQuery('SELECT id_cost, id_peserta, status_peserta, type_data, del, nama, tgl_lahir, kredit_tgl, kredit_tenor, kredit_akhir FROM fu_ajk_peserta WHERE id_cost="'.$rklaim['id_cost'].'" AND id_peserta="'.$rklaim['id_peserta'].'" AND status_peserta="Death" AND del IS NULL'));
			 $klaimcost = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$rklaim['id_cost'].'"'));
			 $klaimpolis = mysql_fetch_array($database->doQuery('SELECT id, id_cost, nmproduk, nopol FROM fu_ajk_polis WHERE id="'.$rklaim['id_nopol'].'"'));
			 $xAsuransi = mysql_fetch_array($database->doQuery('SELECT id, id_bank, id_polis, id_asuransi, id_polis_as, id_dn, id_peserta FROM fu_ajk_peserta_as WHERE id_bank="'.$rklaim['id_cost'].'" AND id_polis="'.$rklaim['id_nopol'].'" AND id_peserta="'.$rklaim['id_peserta'].'"'));
			 $xAsuransi_ = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_asuransi WHERE id="'.$xAsuransi['id_asuransi'].'"'));

			 $now = new T10DateCalc($klaimpeserta['kredit_tgl']);
			 $periodbulan = $now->compareDate($rklaim['tgl_claim']) / 30.4375;
			 $maj = ceil($periodbulan);

			 if ($klaimpeserta['type_data']=="SPK") {	$tenorSPK = $klaimpeserta['kredit_tenor'] * 12;	}else{	$tenorSPK = $klaimpeserta['kredit_tenor'];	}
			 */
			//$jdoc = mysql_num_rows($database->doQuery('SELECT id_pes, id_cost FROM fu_ajk_klaim_doc WHERE id_pes="'.$rklaim['id_peserta'].'" AND id_cost="'.$rklaim['id_cost'].'"'));
			$jdoc = mysql_num_rows($database->doQuery('
				SELECT DISTINCT fu_ajk_dokumenklaim_bank.id_bank, fu_ajk_dokumenklaim_bank.id_dok, fu_ajk_dokumenklaim.nama_dok
				FROM fu_ajk_dokumenklaim_bank
				INNER JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim_bank.id_dok = fu_ajk_dokumenklaim.id
				INNER JOIN fu_ajk_klaim_doc ON fu_ajk_klaim_doc.dokumen = fu_ajk_dokumenklaim_bank.id
				where fu_ajk_klaim_doc.id_pes="'.$rklaim['id_peserta'].'" AND fu_ajk_klaim_doc.id_cost="'.$rklaim['id_cost'].'"
				'));

			$mets = datediff($rklaim['kredit_tgl'], $rklaim['tgl_claim']);
			$metTgl = explode(",",$mets);
			//echo $metTgl[0].'-'.$metTgl[1].'-'.$metTgl[2].'<br />';
			if ($metTgl[2] > 0) {	$jumBulan = $metTgl[1] + 1;	}else{	$jumBulan = $metTgl[1];	}	//AKUMULASI BULAN THD JUMLAH HARI
			$klaimBlnJ = $metTgl[0] + $jumBulan;
			$klaimBlnS = $rklaim['tenor'] - $klaimBlnJ;
			//SETTING TGL BAYAR KLAIM//
			if ($rklaim['tgl_byr_claim']=="") {	$metbyrklaim = 'Belum dibayar';	}else{	$metbyrklaim = '<font color="blue">'._convertDate($rklaim['tgl_byr_claim']).'</font>';	}
			//SETTING TGL BAYAR KLAIM//

			//SETTING PEMBERITAHUAN INVESTIGASI
			/*
			if ($rklaim['confirm_claim']=="Investigasi") {
				$setInvestigasi ='<a title="Edit data ahli waris" href="ajk_claim.php?d=eInvKlaim&ido='.$rklaim['id'].'"><img src="image/edit.png" width="30"></a>';
			}else{
				$setInvestigasi ='<a title="Confirm Investigasi Klaim" href="ajk_claim.php?d=InvKlaim&id='.$rklaim['id'].'"><img src="image/doc_death.png" width="30"></a>';
			}
			*/
			if(is_null($metklaim['investigasi'])){
				$setInvestigasi='<a title="approve data klaim" href="ajk_claim.php?d=app_investigasi_klaim&id='.$rklaim['id_klaim'].'" onclick="if(confirm(\'Apakah anda yakin untuk melakukan pengajuan data klaim pada peserta ini ke dokter ?\')){return true;}{return false;}"><img src="image/save.png" width="20"></a>';
			}
			$surat_pem='';
			if(!is_null($rklaim['tgl_kirim_dokumen']) && $rklaim['tgl_kirim_dokumen']!='0000-00-00' && datediff($rklaim['tgl_kirim_dokumen'], $futoday)>=7){
				$surat_pem='<a title="Print Surat Persetujuan Ke asuransi" target="_blank" href="e_report_klaim.php?er=setuju_as&id='.$rklaim['id'].'"><img src="image/print.png" width="30"></a>';
			}

			if(!is_null($rklaim['tgl_kirim_dokumen']) && $rklaim['tgl_kirim_dokumen']!='0000-00-00' && datediff($rklaim['tgl_kirim_dokumen'], $futoday)>=14){
				$surat_pem='<a title="Print Surat Realisasi Ke asuransi '.$rklaim['tgl_bayar_asuransi'].'"  target="_blank" href="e_report_klaim.php?er=realisasi_as&id='.$rklaim['id'].'"><img src="image/print.png" width="30"></a>';
			}

			if($rklaim['investigasi']=='' or $rklaim['investigasi']=='N'){
				$status='Unapprove';
			}elseif($rklaim['investigasi']=='P'){
				$status='<a title="'.$rklaim['pending_note'].'">Pending</a>';
			}

			//SETTING PEMBERITAHUAN INVESTIGASI
			if (($no % 2) == 1)	$objlass = 'tbl-odd'; else				$objlass = 'tbl-even';
			echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
			<td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
			<td>'.$rklaim['input_date'].'</td>
			<td>'.$rklaim['no_urut_klaim'].'</td>
			<td align="center">'.$rklaim['asuransi'].'</td>
			<td align="center"><a href="e_report_klaim.php?er=_invklaim&idC='.$rklaim['id'].'" target="_blank">'.$rklaim['id_peserta'].'</a></td>
			<td align="center">'.substr($rklaim['dn_kode'],3).'</td>
			<td align="center">'.$rklaim['nmproduk'].'</td>
			<td><a title="Worksheet Klaim" href="ajk_claim.php?d=wklaim&id='.$rklaim['id'].'" target="_blank">'.$rklaim['nama'].'</a></td>
			<td align="center">'.$rklaim['cabang'].'</td>
			<td align="center">'._convertDate($rklaim['kredit_tgl']).'</td>
			<td align="center">'._convertDate($rklaim['kredit_akhir']).'</td>
			<td align="center">'._convertDate($rklaim['tgl_claim']).'</td>
			<td align="center">'._convertDate($rklaim['approve_date']).'</td>
			<td align="center">'.$rklaim['tenor'].'</td>
			<!--  <td align="center">'.$klaimBlnS.','.$klaimBlnJ.'</td> --!>
			<td align="right">'.duit($rklaim['tuntutan_klaim']).'</td>
			<td align="center">'.$rklaim['status_klaim'].'</td>
			<td align="center">'.$metbyrklaim.'</td>
			<td align="center">'.$jdoc.'</td>
			<td align="center">'.$status.'</td>
			<td align="center">
					<a title="edit data klaim" href="ajk_claim.php?d=viewklaim&id='.$rklaim['id'].'"><img src="image/edit3.png"></a>

					'.$setInvestigasi.'</td>
			</tr>';
		}
		echo '<tr><td colspan="22">';
		echo createPageNavigations($file = 'ajk_claim.php?d=investigasi_klaim&tglinput='.$dateku.'&tglinput1='.$dateku1.'&status='.$_REQUEST['status'].'&status_k='.$_REQUEST['status_k'], $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
		echo '<b>Total Data Meninggal: <u>' . $totalRows . '</u></b></td></tr>';
		echo '</table>';
		;
	break;

	case "investigasi_klaim" :
		if($q['status']=="LEGAL"){
			$judul = "Legal";
			$qstatus = "sts_analisa = 'Proses Analisa' and type_analisa = 'legal'";
		}else{
			if($q['status']=="DOKTER"){
				$qstatus = "sts_analisa not in ('Proses Analisa','Finish') and type_analisa = 'medical'";
			}else{
				$qstatus = "sts_analisa not in ('Approve Analisa','Finish') and type_analisa = 'medical'";
			}
			$judul = "Medical";
		}
		echo '
		<table border="0" cellpadding="3" cellspacing="0" width="100%">
			<tr><th colspan="8" width="100%" align="left" colspan="2">Modul Klaim - Analisa Klaim '.$judul.'</font></th></tr>
		</table><br />
		<a href="e_report.php?er=invklaimfinish&user='.$judul.'">Excel Sudah Analisa</a>
		<table border="0" width="100%" cellpadding="5" cellspacing="1" bgcolor="#bde0e6">
			<tr>
				<th width="3%">No</td>
				<th width="5%">ID Peserta</td>
				<th width="8%">Dokumen</td>
				<th width="10%">Produk</td>
				<th width="30%">Nama Debitur</td>
				<th width="10%">Cabang</td>
				<th width="20%">Status Analisa</td>
				<th width="20%">Status Klaim</td>
				<th width="10%">Diajukan Oleh</td>
				<th width="10%">Tgl Pengajuan</td>
				<th width="5%">Option</td>
			</tr>';

		$query = "SELECT  fu_ajk_peserta.id_peserta,
											fu_ajk_peserta.nama,
											fu_ajk_polis.nmproduk,
											fu_ajk_peserta.cabang,
											fu_ajk_analisa_klaim.analisa_bank,
											fu_ajk_analisa_klaim.analisa_asuransi,
											fu_ajk_analisa_klaim.sts_analisa,
											fu_ajk_analisa_klaim.input_by,
											fu_ajk_analisa_klaim.input_date,
											fu_ajk_analisa_klaim.approve_by,
											fu_ajk_peserta.id_klaim,
											fu_ajk_klaim_status.status_klaim
							FROM fu_ajk_analisa_klaim
							INNER JOIN fu_ajk_peserta ON fu_ajk_peserta.id_peserta = fu_ajk_analisa_klaim.id_peserta
							INNER JOIN fu_ajk_polis ON fu_ajk_polis.id = fu_ajk_peserta.id_polis
							INNER JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn = fu_ajk_peserta.id_klaim
							LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id = fu_ajk_klaim.id_klaim_status
							WHERE ".$qstatus;
		
		$metklaim = mysql_query($query);

		$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
		while ($rklaim = mysql_fetch_array($metklaim)) {
			if (($no % 2) == 1)	$objlass = 'tbl-odd'; else $objlass = 'tbl-even';

			if($q['status'] == "UNDERWRITING"){
				if($rklaim['analisa_bank'] == "" or $rklaim['analisa_asuransi'] == ""){
					$opt = '<a title="Input Medical" href="ajk_claim.php?d=analisamedical&id='.$rklaim['id_klaim'].'"><img src="image/plus.png" width="15"></a>';
				}elseif($rklaim['sts_analisa']!="Approve Analisa"){
					$opt = '<a title="Edit Medical" href="ajk_claim.php?d=analisamedical&id='.$rklaim['id_klaim'].'"><img src="image/edit.png" width="15"></a>&nbsp
									<a title="Process Approval" href="ajk_claim.php?d=approveanalisamedical&id='.$rklaim['id_klaim'].'" onClick="if(confirm(\'Klaim ini akan ditujukan oleh Dokter AMK, Apakah anda setuju ?\')){return true;}{return false;}"><img src="image/redirect.png" width="15"></a>';
				}else{
					$opt = '<a title="View Medical" href="ajk_claim.php?d=analisamedical&id='.$rklaim['id_klaim'].'"><img src="image/edit.png" width="15"></a>';
				}
			}elseif($q['status'] == "DOKTER"){
				$opt = '<a title="Proces Analisa Medical" href="ajk_claim.php?d=analisamedical&id='.$rklaim['id_klaim'].'"><img src="image/plus.png" width="15"></a>';
			}elseif($q['status']=="LEGAL"){
				if($rklaim['analisa_bank']==""){
					$opt = '<a title="Input Medical" href="ajk_claim.php?d=analisalegal&id='.$rklaim['id_klaim'].'"><img src="image/plus.png" width="15"></a>';
				}else{
					$opt = '<a title="Edit Medical" href="ajk_claim.php?d=analisalegal&id='.$rklaim['id_klaim'].'"><img src="image/edit.png" width="15"></a>&nbsp
									<a title="Process Approval" href="ajk_claim.php?d=approveanalisalegal&id='.$rklaim['id_klaim'].'" onClick="if(confirm(\'Analisa ini sudah Valid, Apakah anda setuju ?\')){return true;}{return false;}"><img src="image/redirect.png" width="15"></a>';
				}
				
			}

			$qhisemail = mysql_fetch_array(mysql_query("select *,DATE_FORMAT(tgl_kirim,'%d-%m-%Y')as tgl_kirim_surat from fu_ajk_his_kirim_surat where keytable = '".$rklaim['id_klaim']."' and surat = 'Pelaporan Klaim Cabang' order by id desc limit 1"));

			echo '
			<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
				<td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
				<td align="center">'.$rklaim['id_peserta'].'</td>
				<td align="center"><a href="../aajk_report.php?er=_erKlaim&idC='.$rklaim['id_klaim'].'" target="_blank">'.$qhisemail['tgl_kirim_surat'].'</a></td>
				<td align="center">'.$rklaim['nmproduk'].'</td>
				<td>'.$rklaim['nama'].'</td>
				<td align="center">'.$rklaim['cabang'].'</td>
				<td align="center">'.$rklaim['sts_analisa'].'</td>
				<td align="center">'.$rklaim['status_klaim'].'</td>
				<td align="center">'.$rklaim['input_by'].'</td>
				<td align="center">'.$rklaim['input_date'].'</td>
				<td align="center">'.$opt.'</td>
			</tr>';
		}
		echo '</table>';
	break;

	case "approveanalisamedical":
		$qpeserta = mysql_fetch_array(mysql_query("SELECT * FROM fu_ajk_peserta where id_klaim = '".$_REQUEST['id']."'"));

		$query = "UPDATE fu_ajk_analisa_klaim
								SET	sts_analisa = 'Approve Analisa',
										assign_by = '".$q['nm_user']."',
										assign_date = now()										
								WHERE id_peserta = '".$qpeserta['id_peserta']."' and type_analisa = 'medical'";
			mysql_query($query);

			echo '<center><h2>Data Klaim meninggal telah di approve oleh ' . $q['nm_lengkap'] . ' pada tanggal ' . $futgl . '.</h2></center>
						<meta http-equiv="refresh" content="2;URL=ajk_claim.php?d=investigasi_klaim">';
	break;

	case "approveanalisalegal":
		$qpeserta = mysql_fetch_array(mysql_query("SELECT * FROM fu_ajk_peserta where id_klaim = '".$_REQUEST['id']."'"));

		$query = "UPDATE fu_ajk_analisa_klaim
								SET	sts_analisa = 'Finish',
										approve_by = '".$q['nm_user']."',
										approve_date = now()
								WHERE id_peserta = '".$qpeserta['id_peserta']."' and type_analisa = 'legal'";
			mysql_query($query);

			echo '<center><h2>Data Klaim meninggal telah di approve oleh ' . $q['nm_lengkap'] . ' pada tanggal ' . $futgl . '.</h2></center>
						<meta http-equiv="refresh" content="2;URL=ajk_claim.php?d=investigasi_klaim">';
	break;


	case "app_investigasi_klaim" :
		$tambahdok = $database->doQuery('update fu_ajk_klaim SET investigasi="A",tgl_app_investigasi=current_timestamp() where id='.$_REQUEST['id']);
		echo '<center><h2>Data Klaim meninggal telah di update oleh ' . $q['nm_lengkap'] . ' pada tanggal ' . $futgl . '.</h2></center><meta http-equiv="refresh" content="2;URL=ajk_claim.php?d=investigasi_klaim">';
	break;

	case "app_dokter_klaim":
		$tambahdok = $database->doQuery('update fu_ajk_klaim SET investigasi="Y",tgl_app_opinimedis=current_timestamp() where id='.$_REQUEST['id']);
		echo '<center><h2>Data Klaim meninggal telah di update oleh ' . $q['nm_lengkap'] . ' pada tanggal ' . $futgl . '.</h2></center><meta http-equiv="refresh" content="2;URL=ajk_claim.php?d=med_opinion">';
	break;

	case "del_dokter_klaim":
		$tambahdok = $database->doQuery('update fu_ajk_klaim SET investigasi="P",tgl_app_opinimedis=current_timestamp(),pending_note="'.$_REQUEST['pending_note'].'" where id='.$_REQUEST['id_klaim']);
		echo '<center><h2>Data Klaim meninggal telah di update oleh ' . $q['nm_lengkap'] . ' pada tanggal ' . $futgl . '.</h2></center><meta http-equiv="refresh" content="2;URL=ajk_claim.php?d=med_opinion">';
	break;

	case "med_opinion" :

		echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
			<tr><th colspan="8" width="100%" align="left" colspan="2">Modul Klaim - Opini Medis</font></th></tr>
			</table><br />';
		echo '<fieldset>
		<legend>Searching</legend>
		<form method="post" action="">
		<table border="0" width="60%" cellpadding="1" cellspacing="0" align="center">
	  	<tr><td width="10%" align="center">ID PESERTA</td>
			<td width="10%" align="center">NAMA</td>
		</tr>
		<tr>
			<td align="center"><input type="text" name="ridp" value="'.$_REQUEST['ridp'].'"></td>
			<td align="center"><input type="text" name="rnama" value="'.$_REQUEST['rnama'].'"></td>
		</tr>
	  	<tr><td colspan="6" align="center"><input type="submit" name="oppe" value="Searching" class="button"></td></tr>
		</table></form></fieldset>';
		//}
		echo '<table border="0" width="100%" cellpadding="5" cellspacing="1" bgcolor="#bde0e6">

		<tr><th width="3%">No</td>
		<th>Input Date</td>
		<th>No. Urut</td>
		<th>Asuransi</td>
		<th width="5%">ID Peserta</td>
		<th width="1%">ID DN</td>
		<th width="1%">ID CN</td>
		<th width="1%">Produk</td>
		<th>Nama Debitur</td>
		<th>Cabang</td>
		<th width="5%">Kredit Awal</td>
		<th width="5%">Kredit Akhir</td>
		<th width="5%">Tgl Klaim</td>
		<th width="1%">Tgl Approve</td>
		<th width="1%">Tenor</td>
		<!-- <th width="1%">Tenor (S,B)</td> --!>
		<th width="1%">Jumlah</td>
		<th width="1%">Status</td>
		<th width="1%">Tgl Bayar Klaim</td>
		<th width="1%">jDoc</td>
		<th width="5%">Opini Medis</td>
		</tr>';
		if ($_REQUEST['ridp'])		{	$empat = 'AND fu_ajk_peserta.id_peserta LIKE "%' . $_REQUEST['ridp'] . '%"';		}
		if ($_REQUEST['rnama'])		{	$tiga = 'AND fu_ajk_peserta.nama LIKE "%'.$_REQUEST['rnama'].'%"';
		$namanya = mysql_fetch_array($database->doQuery('SELECT id_klaim, status_peserta, id_peserta FROM fu_ajk_peserta WHERE id_klaim !="" AND status_peserta="Death" '.$tiga.' '));
		$ernama = 'AND fu_ajk_peserta.id_peserta = "' . $namanya['id_peserta'] . '"';
		}
		if ($_REQUEST['rdob'])		{	$empat = 'AND tgl_lahir = "' . $_REQUEST['rdob'] . '"';
		$dobnya = mysql_fetch_array($database->doQuery('SELECT id_klaim, status_peserta, id_peserta FROM fu_ajk_peserta WHERE id_klaim !="" AND status_peserta="Death" '.$empat.' '));
		$erdob = 'AND fu_ajk_peserta.tgl_lahir = "' . $dobnya['tgl_lahir'] . '"';
		}

		if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}

		//$metklaim = $database->doQuery('SELECT * FROM fu_ajk_cn WHERE type_claim = "Death" '.$satu.' '.$dua.' '.$ernama.' '.$erdob.' AND del IS NULL ORDER BY id DESC LIMIT ' . $m . ' , 25');
		$metklaim = $database->doQuery('SELECT fu_ajk_asuransi.`name` AS asuransi,
												fu_ajk_cn.id,
												fu_ajk_klaim.id as id_klaim,
												fu_ajk_klaim.no_urut_klaim,
												fu_ajk_cn.id_cost,
												fu_ajk_cn.id_cn,
												fu_ajk_dn.dn_kode,
												fu_ajk_peserta.id_peserta,
												fu_ajk_peserta.nama,
												fu_ajk_peserta.tgl_lahir,
												fu_ajk_peserta.usia,
												fu_ajk_peserta.kredit_tgl,
												IF(fu_ajk_peserta.type_data="SPK", fu_ajk_peserta.kredit_tenor * 12, fu_ajk_peserta.kredit_tenor) AS tenor,
												fu_ajk_peserta.kredit_akhir,
												fu_ajk_peserta.kredit_jumlah,
												fu_ajk_cn.tgl_claim,
												fu_ajk_cn.premi,
												fu_ajk_cn.confirm_claim,
												fu_ajk_cn.total_claim,
												fu_ajk_cn.tuntutan_klaim,
												fu_ajk_cn.tgl_byr_claim,
												fu_ajk_polis.nmproduk,
												fu_ajk_peserta.cabang,
												fu_ajk_klaim.tgl_kirim_dokumen,
												fu_ajk_cn.tgl_bayar_asuransi,
												fu_ajk_cn.input_date,
												fu_ajk_cn.approve_date,
												fu_ajk_klaim_status.status_klaim
												FROM
												fu_ajk_cn
												INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
												INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
												INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
												INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta AND fu_ajk_cn.id = fu_ajk_peserta.id_klaim
												LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
												LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim.id_klaim_status = fu_ajk_klaim_status.id
												WHERE
												fu_ajk_cn.type_claim = "Death" AND fu_ajk_cn.confirm_claim <> "Pending" AND fu_ajk_cn.del IS NULL
												and fu_ajk_klaim.investigasi="A"
												 '.$tiga.' '.$empat.'
												ORDER BY fu_ajk_cn.id DESC LIMIT ' . $m . ' , 25');
		//$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_cn WHERE id != "" AND type_claim="Death" '.$satu.' '.$dua.' '.$ernama.' '.$erdob.' AND del IS NULL'));
		$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(fu_ajk_cn.id_cn) FROM fu_ajk_cn
		INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
		INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
		INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta AND fu_ajk_cn.id = fu_ajk_peserta.id_klaim
		LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
		WHERE fu_ajk_cn.type_claim = "Death" AND fu_ajk_cn.confirm_claim <> "Pending" AND fu_ajk_cn.del IS NULL
		and fu_ajk_klaim.investigasi="A"
		'.$tiga.'  '.$empat.''));
		$totalRows = $totalRows[0];
		$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
		while ($rklaim = mysql_fetch_array($metklaim)) {
			/*
			 $klaim_dn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn WHERE id="'.$rklaim['id_dn'].'"'));
			 $klaimpeserta = mysql_fetch_array($database->doQuery('SELECT id_cost, id_peserta, status_peserta, type_data, del, nama, tgl_lahir, kredit_tgl, kredit_tenor, kredit_akhir FROM fu_ajk_peserta WHERE id_cost="'.$rklaim['id_cost'].'" AND id_peserta="'.$rklaim['id_peserta'].'" AND status_peserta="Death" AND del IS NULL'));
			 $klaimcost = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$rklaim['id_cost'].'"'));
			 $klaimpolis = mysql_fetch_array($database->doQuery('SELECT id, id_cost, nmproduk, nopol FROM fu_ajk_polis WHERE id="'.$rklaim['id_nopol'].'"'));
			 $xAsuransi = mysql_fetch_array($database->doQuery('SELECT id, id_bank, id_polis, id_asuransi, id_polis_as, id_dn, id_peserta FROM fu_ajk_peserta_as WHERE id_bank="'.$rklaim['id_cost'].'" AND id_polis="'.$rklaim['id_nopol'].'" AND id_peserta="'.$rklaim['id_peserta'].'"'));
			 $xAsuransi_ = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_asuransi WHERE id="'.$xAsuransi['id_asuransi'].'"'));

			 $now = new T10DateCalc($klaimpeserta['kredit_tgl']);
			 $periodbulan = $now->compareDate($rklaim['tgl_claim']) / 30.4375;
			 $maj = ceil($periodbulan);

			 if ($klaimpeserta['type_data']=="SPK") {	$tenorSPK = $klaimpeserta['kredit_tenor'] * 12;	}else{	$tenorSPK = $klaimpeserta['kredit_tenor'];	}
			 */
			//$jdoc = mysql_num_rows($database->doQuery('SELECT id_pes, id_cost FROM fu_ajk_klaim_doc WHERE id_pes="'.$rklaim['id_peserta'].'" AND id_cost="'.$rklaim['id_cost'].'"'));
			$jdoc = mysql_num_rows($database->doQuery('
				SELECT DISTINCT fu_ajk_dokumenklaim_bank.id_bank, fu_ajk_dokumenklaim_bank.id_dok, fu_ajk_dokumenklaim.nama_dok
				FROM fu_ajk_dokumenklaim_bank
				INNER JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim_bank.id_dok = fu_ajk_dokumenklaim.id
				INNER JOIN fu_ajk_klaim_doc ON fu_ajk_klaim_doc.dokumen = fu_ajk_dokumenklaim_bank.id
				where fu_ajk_klaim_doc.id_pes="'.$rklaim['id_peserta'].'" AND fu_ajk_klaim_doc.id_cost="'.$rklaim['id_cost'].'"
				'));

			$mets = datediff($rklaim['kredit_tgl'], $rklaim['tgl_claim']);
			$metTgl = explode(",",$mets);
			//echo $metTgl[0].'-'.$metTgl[1].'-'.$metTgl[2].'<br />';
			if ($metTgl[2] > 0) {	$jumBulan = $metTgl[1] + 1;	}else{	$jumBulan = $metTgl[1];	}	//AKUMULASI BULAN THD JUMLAH HARI
			$klaimBlnJ = $metTgl[0] + $jumBulan;
			$klaimBlnS = $rklaim['tenor'] - $klaimBlnJ;
			//SETTING TGL BAYAR KLAIM//
			if ($rklaim['tgl_byr_claim']=="") {	$metbyrklaim = 'Belum dibayar';	}else{	$metbyrklaim = '<font color="blue">'._convertDate($rklaim['tgl_byr_claim']).'</font>';	}
			//SETTING TGL BAYAR KLAIM//

			//SETTING PEMBERITAHUAN INVESTIGASI


			if ($rklaim['confirm_claim']=="Investigasi") {
				$setInvestigasi ='<a title="Edit data ahli waris" href="ajk_claim.php?d=inv_klaim&id='.$rklaim['id'].'"><img src="image/edit.png" width="30"></a>
									<a title="approve data klaim" href="ajk_claim.php?d=app_dokter_klaim&id='.$rklaim['id_klaim'].'" onclick="if(confirm(\'Apakah anda yakin untuk melakukan approval data klaim pada peserta ini?\')){return true;}{return false;}"><img src="image/save.png" width="20"></a>
									<a title="Pending data klaim" href="ajk_claim.php?d=del_dokter_klaim&id='.$rklaim['id_klaim'].'" onclick="if(confirm(\'Apakah anda yakin untuk melakukan pembatalan data klaim pada peserta ini?\')){return true;}{return false;}"><img src="image/delete.png" width="20"></a>';
			}else{
				$setInvestigasi ='<a title="Confirm Investigasi Klaim" href="ajk_claim.php?d=inv_klaim&id='.$rklaim['id'].'"><img src="image/doc_death.png" width="30"></a>
									<a title="approve data klaim" href="ajk_claim.php?d=app_dokter_klaim&id='.$rklaim['id_klaim'].'" onclick="if(confirm(\'Apakah anda yakin untuk melakukan approval data klaim pada peserta ini?\')){return true;}{return false;}"><img src="image/save.png" width="20"></a>
									<!--<a title="Pending data klaim" href="ajk_claim.php?d=del_dokter_klaim&id='.$rklaim['id_klaim'].'" onclick="if(confirm(\'Apakah anda yakin untuk melakukan pending data klaim pada peserta ini?\')){return true;}{return false;}"><img src="image/delete.png" width="20"></a>-->
									<a title="Pending data klaim" href="ajk_claim.php?d=pending_note&id='.$rklaim['id_klaim'].'"><img src="image/delete.png" width="20"></a>';
			}
			$surat_pem='';
			if(!is_null($rklaim['tgl_kirim_dokumen']) && $rklaim['tgl_kirim_dokumen']!='0000-00-00' && datediff($rklaim['tgl_kirim_dokumen'], $futoday)>=7){
				$surat_pem='<a title="Print Surat Persetujuan Ke asuransi" target="_blank" href="e_report_klaim.php?er=setuju_as&id='.$rklaim['id'].'"><img src="image/print.png" width="30"></a>';
			}

			if(!is_null($rklaim['tgl_kirim_dokumen']) && $rklaim['tgl_kirim_dokumen']!='0000-00-00' && datediff($rklaim['tgl_kirim_dokumen'], $futoday)>=14){
				$surat_pem='<a title="Print Surat Realisasi Ke asuransi '.$rklaim['tgl_bayar_asuransi'].'"  target="_blank" href="e_report_klaim.php?er=realisasi_as&id='.$rklaim['id'].'"><img src="image/print.png" width="30"></a>';
			}


			//SETTING PEMBERITAHUAN INVESTIGASI
			if (($no % 2) == 1)	$objlass = 'tbl-odd'; else				$objlass = 'tbl-even';
			echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
			<td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
			<td>'.$rklaim['input_date'].'</td>
			<td>'.$rklaim['no_urut_klaim'].'</td>
			<td align="center">'.$rklaim['asuransi'].'</td>
			<td align="center"><a href="e_report_klaim.php?er=_invklaim&idC='.$rklaim['id'].'" target="_blank">'.$rklaim['id_peserta'].'</a></td>
			<td align="center">'.substr($rklaim['dn_kode'],3).'</td>
			<td align="center"><a href="../aajk_report.php?er=_cnDeath&idC='.$rklaim['id'].'" target="_blank">'.substr($rklaim['id_cn'],3).'</a></td>
			<td align="center">'.$rklaim['nmproduk'].'</td>
			<td><a title="Worksheet Klaim" href="ajk_claim.php?d=wklaim&id='.$rklaim['id'].'" target="_blank">'.$rklaim['nama'].'</a></td>
			<td align="center">'.$rklaim['cabang'].'</td>
			<td align="center">'._convertDate($rklaim['kredit_tgl']).'</td>
			<td align="center">'._convertDate($rklaim['kredit_akhir']).'</td>
			<td align="center">'._convertDate($rklaim['tgl_claim']).'</td>
			<td align="center">'._convertDate($rklaim['approve_date']).'</td>
			<td align="center">'.$rklaim['tenor'].'</td>
			<!--  <td align="center">'.$klaimBlnS.','.$klaimBlnJ.'</td> --!>
			<td align="right">'.duit($rklaim['tuntutan_klaim']).'</td>
			<td align="center">'.$rklaim['status_klaim'].'</td>
			<td align="center">'.$metbyrklaim.'</td>
			<td align="center">'.$jdoc.'</td>
			<td align="center">'.$setInvestigasi.'</td>
			</tr>';
		}
		echo '<tr><td colspan="22">';
		echo createPageNavigations($file = 'ajk_claim.php?d=med_opinion&', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
		echo '<b>Total Data Meninggal: <u>' . $totalRows . '</u></b></td></tr>';
		echo '</table>';
		;
	break;

	case "pending_note":
		echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
		<tr><th colspan="8" width="100%" align="left" colspan="2">Note Pending</font></th><th width="5%" colspan="2"><a href="ajk_claim.php?d=med_opinion"><img src="image/Backward-64.png" width="20"></a></th></tr>
					</table>';
		echo '<br>
					<table border="0" width="100%" cellpadding="1" cellspacing="1">
						<form method="post" name="frm_pengajuan" action="ajk_claim.php?d=del_dokter_klaim" enctype="multipart/form-data">
								<input type="hidden" id="id_klaim" name="id_klaim" value="'.$_REQUEST['id'].'"></input>
							<tr>
								<td align="right" width="7%">Note Pending : </td>
								<td><textarea id="pending_note" name="pending_note" required></textarea></td>
							</tr>
							<td></td>
							<td align="left">
								<button type="submit" class="button" style="text-align:center">Submit</button>
							</td>
						</form>
					</table>';
	break;

	case "med_opinion1" :

		if(!isset($_REQUEST['tgl1'])){
			$date1=date("Y-m-d");
			$date2=date("Y-m-d");
			$tgl_inv='and date(fu_ajk_klaim.tgl_app_investigasi) between "'.$date1.'" and "'.$date2.'"';
		}elseif($_REQUEST['tgl1']==""){
			$date1='';
			$date2='';
			$tgl_inv='';
		}else{
			$date1=$_REQUEST['tgl1'];
			$date2=$_REQUEST['tgl2'];
			$tgl_inv='and date(fu_ajk_klaim.tgl_app_investigasi) between "'.$date1.'" and "'.$date2.'"';
		}

		if(!isset($_REQUEST['tgl3'])){
			$date3=date("Y-m-d");
			$date4=date("Y-m-d");
			$tgl_med='and date(fu_ajk_klaim.tgl_app_opinimedis) between "'.$date3.'" and "'.$date4.'"';
		}elseif($_REQUEST['tgl3']==""){
			$date3='';
			$date4='';
			$tgl_med='';
		}else{
			$date3=$_REQUEST['tgl3'];
			$date4=$_REQUEST['tgl4'];
			$tgl_med='and date(fu_ajk_klaim.tgl_app_opinimedis) between "'.$date3.'" and "'.$date4.'"';
		}

		echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
		<tr><th colspan="8" width="100%" align="left" colspan="2">Modul Klaim - Opini Medis</font></th></tr>
		</table><br />';
		echo '<fieldset>
		<legend>Searching</legend>
		<form method="post" action="">
		<table border="0" width="70%" cellpadding="1" cellspacing="0" align="center">
	  	<tr><td width="10%" align="center">ID PESERTA</td>
			<td width="10%" align="center">NAMA</td>
			<td width="25%" align="center">TANGGAL APPROVED INVESTIGASI</td>
			<td width="25%" align="center">TANGGAL APPROVED MEDICAL</td>
		</tr>
		<tr>
			<td align="center"><input type="text" name="ridp" value="'.$_REQUEST['ridp'].'"></td>
			<td align="center"><input type="text" name="rnama" value="'.$_REQUEST['rnama'].'"></td>
		  	<td align="center">';
			print initCalendar();
			print calendarBox('tgl1', 'triger1', $date1);
			print initCalendar();
			print calendarBox('tgl2', 'triger2', $date2);
			echo '</td><td align="center">';
			print initCalendar();
			print calendarBox('tgl3', 'triger3', $date3);
			print initCalendar();
			print calendarBox('tgl4', 'triger4', $date4);
			echo '</td></tr>
					<tr> <td></tr>
	  	<tr><td colspan="6" align="center"><input type="submit" name="oppe" value="Searching" class="button"></td></tr>
		</table></form></fieldset>';
		//}
		echo '<table border="0" width="100%" cellpadding="5" cellspacing="1" bgcolor="#bde0e6">
		<tr><td bgcolor="#FFF"colspan="24"><a href="e_report.php?er=klaim_investigasi
			&ridp=' . $_REQUEST['ridp'] . '
			&rnama='.$_REQUEST['rnama'].'
			&tgl1='.$date1.'
			&tgl2='.$date2.'
			&tgl3='.$date3.'
			&tgl4='.$date4.'">Export to Excel</a></td></tr>
		<tr><th width="3%">No</td>
		<th>Input Date</td>
		<th>No. Urut</td>
		<th>Asuransi</td>
		<th width="5%">ID Peserta</td>
		<th width="1%">ID DN</td>
		<th width="1%">ID CN</td>
		<th width="1%">Produk</td>
		<th>Nama Debitur</td>
		<th>Cabang</td>
		<th width="5%">Kredit Awal</td>
		<th width="5%">Kredit Akhir</td>
		<th width="5%">Tgl Klaim</td>
		<th width="1%">Tgl Approve</td>
		<th width="1%">Tenor</td>
		<!-- <th width="1%">Tenor (S,B)</td> --!>
		<th width="1%">Jumlah</td>
		<th width="1%">Status</td>
		<th width="1%">Tgl Bayar Klaim</td>
		<th width="1%">jDoc</td>
		<th width="5%">Opini Medis</td>
		</tr>';
		if ($_REQUEST['ridp'])		{	$empat = 'AND fu_ajk_peserta.id_peserta LIKE "%' . $_REQUEST['ridp'] . '%"';		}
		if ($_REQUEST['rnama'])		{	$tiga = 'AND fu_ajk_peserta.nama LIKE "%'.$_REQUEST['rnama'].'%"';
		$namanya = mysql_fetch_array($database->doQuery('SELECT id_klaim, status_peserta, id_peserta FROM fu_ajk_peserta WHERE id_klaim !="" AND status_peserta="Death" '.$tiga.' '));
		$ernama = 'AND fu_ajk_peserta.id_peserta = "' . $namanya['id_peserta'] . '"';
		}
		if ($_REQUEST['rdob'])		{	$empat = 'AND tgl_lahir = "' . $_REQUEST['rdob'] . '"';
		$dobnya = mysql_fetch_array($database->doQuery('SELECT id_klaim, status_peserta, id_peserta FROM fu_ajk_peserta WHERE id_klaim !="" AND status_peserta="Death" '.$empat.' '));
		$erdob = 'AND fu_ajk_peserta.tgl_lahir = "' . $dobnya['tgl_lahir'] . '"';
		}

		if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}

		//$metklaim = $database->doQuery('SELECT * FROM fu_ajk_cn WHERE type_claim = "Death" '.$satu.' '.$dua.' '.$ernama.' '.$erdob.' AND del IS NULL ORDER BY id DESC LIMIT ' . $m . ' , 25');
		$metklaim = $database->doQuery('SELECT fu_ajk_asuransi.`name` AS asuransi,
											fu_ajk_cn.id,
											fu_ajk_klaim.id as id_klaim,
											fu_ajk_klaim.no_urut_klaim,
											fu_ajk_cn.id_cost,
											fu_ajk_cn.id_cn,
											fu_ajk_dn.dn_kode,
											fu_ajk_peserta.id_peserta,
											fu_ajk_peserta.nama,
											fu_ajk_peserta.tgl_lahir,
											fu_ajk_peserta.usia,
											fu_ajk_peserta.kredit_tgl,
											IF(fu_ajk_peserta.type_data="SPK", fu_ajk_peserta.kredit_tenor * 12, fu_ajk_peserta.kredit_tenor) AS tenor,
											fu_ajk_peserta.kredit_akhir,
											fu_ajk_peserta.kredit_jumlah,
											fu_ajk_cn.tgl_claim,
											fu_ajk_cn.premi,
											fu_ajk_cn.confirm_claim,
											fu_ajk_cn.total_claim,
											fu_ajk_cn.tuntutan_klaim,
											fu_ajk_cn.tgl_byr_claim,
											fu_ajk_polis.nmproduk,
											fu_ajk_peserta.cabang,
											fu_ajk_klaim.tgl_kirim_dokumen,
											fu_ajk_cn.tgl_bayar_asuransi,
											fu_ajk_cn.input_date,
											fu_ajk_cn.approve_date,
											fu_ajk_klaim_status.status_klaim
											FROM
											fu_ajk_cn
											INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
											INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
											INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
											INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta AND fu_ajk_cn.id = fu_ajk_peserta.id_klaim
											LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
											LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim.id_klaim_status = fu_ajk_klaim_status.id
											WHERE
											fu_ajk_cn.type_claim = "Death" AND fu_ajk_cn.confirm_claim <> "Pending" AND fu_ajk_cn.del IS NULL
											and fu_ajk_klaim.investigasi="Y"
											'.$tgl_inv.' '.$tgl_med.'
											'.$tiga.' '.$empat.'
											ORDER BY fu_ajk_cn.id DESC LIMIT ' . $m . ' , 25');
		//$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_cn WHERE id != "" AND type_claim="Death" '.$satu.' '.$dua.' '.$ernama.' '.$erdob.' AND del IS NULL'));
		$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(fu_ajk_cn.id_cn) FROM fu_ajk_cn
		INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
		INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
		INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta AND fu_ajk_cn.id = fu_ajk_peserta.id_klaim
		LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
		WHERE fu_ajk_cn.type_claim = "Death" AND fu_ajk_cn.confirm_claim <> "Pending" AND fu_ajk_cn.del IS NULL
		and fu_ajk_klaim.investigasi="Y"
		'.$tgl_inv.' '.$tgl_med.' '.$tiga.'  '.$empat.''));
		$totalRows = $totalRows[0];
		$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
		while ($rklaim = mysql_fetch_array($metklaim)) {
			/*
			 $klaim_dn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn WHERE id="'.$rklaim['id_dn'].'"'));
			 $klaimpeserta = mysql_fetch_array($database->doQuery('SELECT id_cost, id_peserta, status_peserta, type_data, del, nama, tgl_lahir, kredit_tgl, kredit_tenor, kredit_akhir FROM fu_ajk_peserta WHERE id_cost="'.$rklaim['id_cost'].'" AND id_peserta="'.$rklaim['id_peserta'].'" AND status_peserta="Death" AND del IS NULL'));
			 $klaimcost = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$rklaim['id_cost'].'"'));
			 $klaimpolis = mysql_fetch_array($database->doQuery('SELECT id, id_cost, nmproduk, nopol FROM fu_ajk_polis WHERE id="'.$rklaim['id_nopol'].'"'));
			 $xAsuransi = mysql_fetch_array($database->doQuery('SELECT id, id_bank, id_polis, id_asuransi, id_polis_as, id_dn, id_peserta FROM fu_ajk_peserta_as WHERE id_bank="'.$rklaim['id_cost'].'" AND id_polis="'.$rklaim['id_nopol'].'" AND id_peserta="'.$rklaim['id_peserta'].'"'));
			 $xAsuransi_ = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_asuransi WHERE id="'.$xAsuransi['id_asuransi'].'"'));

			 $now = new T10DateCalc($klaimpeserta['kredit_tgl']);
			 $periodbulan = $now->compareDate($rklaim['tgl_claim']) / 30.4375;
			 $maj = ceil($periodbulan);

			 if ($klaimpeserta['type_data']=="SPK") {	$tenorSPK = $klaimpeserta['kredit_tenor'] * 12;	}else{	$tenorSPK = $klaimpeserta['kredit_tenor'];	}
			 */
			//$jdoc = mysql_num_rows($database->doQuery('SELECT id_pes, id_cost FROM fu_ajk_klaim_doc WHERE id_pes="'.$rklaim['id_peserta'].'" AND id_cost="'.$rklaim['id_cost'].'"'));
			$jdoc = mysql_num_rows($database->doQuery('
			SELECT DISTINCT fu_ajk_dokumenklaim_bank.id_bank, fu_ajk_dokumenklaim_bank.id_dok, fu_ajk_dokumenklaim.nama_dok
			FROM fu_ajk_dokumenklaim_bank
			INNER JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim_bank.id_dok = fu_ajk_dokumenklaim.id
			INNER JOIN fu_ajk_klaim_doc ON fu_ajk_klaim_doc.dokumen = fu_ajk_dokumenklaim_bank.id
			where fu_ajk_klaim_doc.id_pes="'.$rklaim['id_peserta'].'" AND fu_ajk_klaim_doc.id_cost="'.$rklaim['id_cost'].'"
			'));

			$mets = datediff($rklaim['kredit_tgl'], $rklaim['tgl_claim']);
			$metTgl = explode(",",$mets);
			//echo $metTgl[0].'-'.$metTgl[1].'-'.$metTgl[2].'<br />';
			if ($metTgl[2] > 0) {	$jumBulan = $metTgl[1] + 1;	}else{	$jumBulan = $metTgl[1];	}	//AKUMULASI BULAN THD JUMLAH HARI
			$klaimBlnJ = $metTgl[0] + $jumBulan;
			$klaimBlnS = $rklaim['tenor'] - $klaimBlnJ;
			//SETTING TGL BAYAR KLAIM//
			if ($rklaim['tgl_byr_claim']=="") {	$metbyrklaim = 'Belum dibayar';	}else{	$metbyrklaim = '<font color="blue">'._convertDate($rklaim['tgl_byr_claim']).'</font>';	}
			//SETTING TGL BAYAR KLAIM//

			//SETTING PEMBERITAHUAN INVESTIGASI


			if ($rklaim['confirm_claim']=="Investigasi") {
				$setInvestigasi ='<a title="Edit data ahli waris" href="ajk_claim.php?d=inv_klaim&id='.$rklaim['id'].'"><img src="image/edit.png" width="30"></a>';
			}else{
				$setInvestigasi ='<a title="View Data Klaim" href="ajk_claim.php?d=inv_klaim1&id='.$rklaim['id'].'"><img src="image/doc_death.png" width="30"></a>';
			}
			$surat_pem='';
			if(!is_null($rklaim['tgl_kirim_dokumen']) && $rklaim['tgl_kirim_dokumen']!='0000-00-00' && datediff($rklaim['tgl_kirim_dokumen'], $futoday)>=7){
				$surat_pem='<a title="Print Surat Persetujuan Ke asuransi" target="_blank" href="e_report_klaim.php?er=setuju_as&id='.$rklaim['id'].'"><img src="image/print.png" width="30"></a>';
			}

			if(!is_null($rklaim['tgl_kirim_dokumen']) && $rklaim['tgl_kirim_dokumen']!='0000-00-00' && datediff($rklaim['tgl_kirim_dokumen'], $futoday)>=14){
				$surat_pem='<a title="Print Surat Realisasi Ke asuransi '.$rklaim['tgl_bayar_asuransi'].'"  target="_blank" href="e_report_klaim.php?er=realisasi_as&id='.$rklaim['id'].'"><img src="image/print.png" width="30"></a>';
			}


			//SETTING PEMBERITAHUAN INVESTIGASI
			if (($no % 2) == 1)	$objlass = 'tbl-odd'; else				$objlass = 'tbl-even';
			echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
			<td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
			<td>'.$rklaim['input_date'].'</td>
			<td>'.$rklaim['no_urut_klaim'].'</td>
			<td align="center">'.$rklaim['asuransi'].'</td>
			<td align="center"><a href="e_report_klaim.php?er=_invklaim&idC='.$rklaim['id'].'" target="_blank">'.$rklaim['id_peserta'].'</a></td>
			<td align="center">'.substr($rklaim['dn_kode'],3).'</td>
			<td align="center">'.substr($rklaim['id_cn'],3).'</td>
			<td align="center">'.$rklaim['nmproduk'].'</td>
			<td>'.$rklaim['nama'].'</td>
			<td align="center">'.$rklaim['cabang'].'</td>
			<td align="center">'._convertDate($rklaim['kredit_tgl']).'</td>
			<td align="center">'._convertDate($rklaim['kredit_akhir']).'</td>
			<td align="center">'._convertDate($rklaim['tgl_claim']).'</td>
			<td align="center">'._convertDate($rklaim['approve_date']).'</td>
			<td align="center">'.$rklaim['tenor'].'</td>
			<!--  <td align="center">'.$klaimBlnS.','.$klaimBlnJ.'</td> --!>
			<td align="right">'.duit($rklaim['tuntutan_klaim']).'</td>
			<td align="center">'.$rklaim['status_klaim'].'</td>
			<td align="center">'.$metbyrklaim.'</td>
			<td align="center">'.$jdoc.'</td>
			<td align="center">'.$setInvestigasi.'</td>
			</tr>';
			}
			echo '<tr><td colspan="22">';
			echo createPageNavigations($file = 'ajk_claim.php?d=med_opinion&', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
			echo '<b>Total Data Meninggal: <u>' . $totalRows . '</u></b></td></tr>';
			echo '</table>';
			;
	break;

	case "inv_klaim" :
			echo '<style>
					#country-list{float:left;list-style:none;margin:0;padding:0;}
					#country-list li{padding: 10px; background:#FAFAFA;border-bottom:#F0F0F0 1px solid;}
					#country-list li:hover{background:#F0F0F0;}
				</style>
				<script>
				$(document).ready(function(){
				    $("#search-box").keyup(function(){
				        $.ajax({
				        type: "POST",
				        url: "search.php",
				        data:\'keyword=\'+$(this).val(),
				        beforeSend: function(){
				            $("#search-box").css("background","#FFF url(LoaderIcon.gif) no-repeat 165px");
				        },
				        success: function(data){
				            $("#suggesstion-box").show();
				            $("#suggesstion-box").html(data);
				            $("#search-box").css("background","#FFF");
				        }
				        });
				    });
				});

				function selectCountry(val) {
				$("#search-box").val(val);
				$("#suggesstion-box").hide();
				}
				</script>';
			echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
				<tr><th colspan="8" width="100%" align="left" colspan="2">Modul Klaim - Edit Data Meninggal</font></th><th width="5%" colspan="2"><a href="ajk_claim.php?d=med_opinion"><img src="image/back.png" width="20"></a></th></tr>
				</table><br />';

			if ($_REQUEST['ed_oop'] == "save") {

					$edklaim1 = mysql_fetch_array($database->doQuery('SELECT
						fu_ajk_asuransi.`name` AS asuransi,
						fu_ajk_cn.id,
						fu_ajk_cn.id_cost,
						fu_ajk_cn.id_cn,
						fu_ajk_cn.id_dn,
						fu_ajk_dn.dn_kode,
						fu_ajk_klaim.id as id_klaim,
						fu_ajk_peserta.id_polis,
						fu_ajk_peserta.id_peserta,
						fu_ajk_peserta.nama,
						fu_ajk_peserta.tgl_lahir,
						fu_ajk_peserta.usia,
						fu_ajk_peserta.kredit_tgl,
						IF(fu_ajk_peserta.type_data="SPK", fu_ajk_peserta.kredit_tenor * 12, fu_ajk_peserta.kredit_tenor) AS tenor,
						fu_ajk_peserta.kredit_akhir,
						fu_ajk_peserta.kredit_jumlah,
						fu_ajk_cn.tgl_claim,
						fu_ajk_cn.premi,
						fu_ajk_cn.confirm_claim,
						fu_ajk_cn.total_claim,
						fu_ajk_cn.tuntutan_klaim,
						fu_ajk_cn.tgl_byr_claim,
						fu_ajk_cn.nmpenyakit,
						fu_ajk_cn.keterangan,
						fu_ajk_polis.nmproduk,
						fu_ajk_klaim.id_klaim_status,
						fu_ajk_klaim.tgl_klaim AS tglklaim,
						fu_ajk_klaim.tgl_document AS tglklaimdoc,
						fu_ajk_klaim.tgl_document_lengkap AS tglklaimdoc2,
						fu_ajk_klaim.tgl_investigasi AS tglinvestigasi,
						fu_ajk_klaim.tgl_lapor_klaim AS tgllaporklaim,
						fu_ajk_klaim.jumlah AS totalklaim,
						fu_ajk_klaim.tempat_meninggal AS tempat_meninggal,
						fu_ajk_klaim.diagnosa AS diagnosa,
						fu_ajk_klaim.sebab_meninggal AS sebab_meninggal,
						fu_ajk_klaim.ket AS ketklaim,
						fu_ajk_klaim.sumber_dana,
						fu_ajk_klaim.hasil_investigasi,
						fu_ajk_klaim.ket_dokter AS ketDokter,
						fu_ajk_klaim.tgl_kirim_dokumen AS tglkirimdoc,
						fu_ajk_namapenyakit.id AS idpenyakit,
						fu_ajk_namapenyakit.namapenyakit,
						fu_ajk_cn.policy_liability,

						fu_ajk_klaim.form_aaji,
						fu_ajk_klaim.anamnesis,
						fu_ajk_klaim.pemeriksaan_fisik,
						fu_ajk_klaim.pemeriksaan_penunjang,
						fu_ajk_klaim.terapi,
						fu_ajk_klaim.konfirm_ahliwaris,
						fu_ajk_klaim.extra_mortality,
						fu_ajk_klaim.kategori_klaim,
						fu_ajk_klaim.riwayat_penyakit,
						fu_ajk_klaim.kronologi,
						fu_ajk_klaim.preexisting_cond,
						fu_ajk_klaim.ic_diagnosis
						FROM fu_ajk_cn
						LEFT JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
						LEFT JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
						LEFT JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
						LEFT JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
						LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta AND fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost
						LEFT JOIN fu_ajk_namapenyakit ON fu_ajk_cn.nmpenyakit = fu_ajk_namapenyakit.id
						WHERE fu_ajk_cn.id = "' . $_REQUEST['id'] . '" AND fu_ajk_cn.del IS NULL'));

					if($edklaim['tempat_meninggal']=='Rumah'){

					$tambahdok = $database->doQuery('update fu_ajk_klaim SET

									fu_ajk_klaim.tgl_investigasi="'.$_POST['tglinvestigasi'].'",
									fu_ajk_klaim.kategori_klaim="'.$_POST['kategori'].'",
									fu_ajk_klaim.kronologi="'.$_POST['kronologi'].'",
									fu_ajk_klaim.form_aaji="'.$_POST['form_aaji'].'",
									fu_ajk_klaim.konfirm_ahliwaris="'.$_POST['konfirm_ahliwaris'].'",
									fu_ajk_klaim.extra_mortality="'.$_POST['extra_mortality'].'",
									fu_ajk_klaim.ic_diagnosis="'.$_POST['icd'].'",
									fu_ajk_klaim.ket_dokter="'.$_POST['keterangan_dokter'].'",
									fu_ajk_klaim.preexisting_cond="'.$_POST['preexisting'].'"
									where id='.$edklaim1['id_klaim']);
					}else{

						$tambahdok = $database->doQuery('update fu_ajk_klaim SET

									fu_ajk_klaim.tgl_investigasi="'.$_POST['tglinvestigasi'].'",
									fu_ajk_klaim.kategori_klaim="'.$_POST['kategori'].'",
									fu_ajk_klaim.diagnosa="'.$_POST['diagnosa'].'",
									fu_ajk_klaim.anamnesis="'.$_POST['anamnesis'].'",
									fu_ajk_klaim.pemeriksaan_fisik="'.$_POST['pemeriksaan_fisik'].'",
									fu_ajk_klaim.pemeriksaan_penunjang="'.$_POST['pemeriksaan_penunjang'].'",
									fu_ajk_klaim.terapi="'.$_POST['terapi'].'",
									fu_ajk_klaim.konfirm_ahliwaris="'.$_POST['konfirm_ahliwaris'].'",
									fu_ajk_klaim.extra_mortality="'.$_POST['extra_mortality'].'",
									fu_ajk_klaim.ic_diagnosis="'.$_POST['icd'].'",
									fu_ajk_klaim.ket_dokter="'.$_POST['keterangan_dokter'].'",
									fu_ajk_klaim.preexisting_cond="'.$_POST['preexisting'].'"
									where id='.$edklaim1['id_klaim']);
					}
					echo '<center><h2>Data Klaim meninggal telah di update oleh ' . $q['nm_lengkap'] . ' pada tanggal ' . $futgl . '.</h2></center><meta http-equiv="refresh" content="2;URL=ajk_claim.php?d=med_opinion">';

			}
			$edklaim = mysql_fetch_array($database->doQuery('SELECT
						fu_ajk_asuransi.`name` AS asuransi,
						fu_ajk_cn.id,
						fu_ajk_cn.id_cost,
						fu_ajk_cn.id_cn,
						fu_ajk_cn.id_dn,
						fu_ajk_dn.dn_kode,
						fu_ajk_klaim.id as id_klaim,
						fu_ajk_peserta.id_polis,
						fu_ajk_peserta.id_peserta,
						fu_ajk_peserta.nama,
						fu_ajk_peserta.tgl_lahir,
						fu_ajk_peserta.usia,
						fu_ajk_peserta.kredit_tgl,
						IF(fu_ajk_peserta.type_data="SPK", fu_ajk_peserta.kredit_tenor * 12, fu_ajk_peserta.kredit_tenor) AS tenor,
						fu_ajk_peserta.kredit_akhir,
						fu_ajk_peserta.kredit_jumlah,
						fu_ajk_cn.tgl_claim,
						fu_ajk_cn.premi,
						fu_ajk_cn.confirm_claim,
						fu_ajk_cn.total_claim,
						fu_ajk_cn.tuntutan_klaim,
						fu_ajk_cn.tgl_byr_claim,
						fu_ajk_cn.nmpenyakit,
						fu_ajk_klaim.form_aaji,
						fu_ajk_klaim.anamnesis,
						fu_ajk_klaim.pemeriksaan_fisik,
						fu_ajk_klaim.pemeriksaan_penunjang,
						fu_ajk_klaim.terapi,
						fu_ajk_klaim.konfirm_ahliwaris,
						fu_ajk_klaim.extra_mortality,
						fu_ajk_cn.keterangan,
						fu_ajk_polis.nmproduk,
						fu_ajk_klaim.id_klaim_status,
						fu_ajk_klaim.tgl_klaim AS tglklaim,
						fu_ajk_klaim.tgl_document AS tglklaimdoc,
						fu_ajk_klaim.tgl_document_lengkap AS tglklaimdoc2,
						fu_ajk_klaim.tgl_investigasi AS tglinvestigasi,
						fu_ajk_klaim.tgl_lapor_klaim AS tgllaporklaim,
						fu_ajk_klaim.jumlah AS totalklaim,
						fu_ajk_klaim.tempat_meninggal AS tempat_meninggal,
						fu_ajk_klaim.diagnosa AS diagnosa,
						fu_ajk_klaim.sebab_meninggal AS sebab_meninggal,
						fu_ajk_klaim.ket AS ketklaim,
						fu_ajk_klaim.sumber_dana,
						fu_ajk_klaim.hasil_investigasi,
						fu_ajk_klaim.ket_dokter AS ketDokter,
						fu_ajk_klaim.tgl_kirim_dokumen AS tglkirimdoc,
						fu_ajk_namapenyakit.id AS idpenyakit,
						fu_ajk_namapenyakit.namapenyakit,
						fu_ajk_cn.policy_liability,
						fu_ajk_klaim.kategori_klaim,
						fu_ajk_klaim.riwayat_penyakit,
						fu_ajk_klaim.kronologi,
						fu_ajk_klaim.preexisting_cond,
						fu_ajk_klaim.ic_diagnosis
						FROM fu_ajk_cn
						LEFT JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
						LEFT JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
						LEFT JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
						LEFT JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
						LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta AND fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost
						LEFT JOIN fu_ajk_namapenyakit ON fu_ajk_cn.nmpenyakit = fu_ajk_namapenyakit.id
						WHERE fu_ajk_cn.id = "' . $_REQUEST['id'] . '" AND fu_ajk_cn.del IS NULL'));
			$adcostumer = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="' . $edklaim['id_cost'] . '"'));


			$mets = datediff($edklaim['kredit_tgl'], $edklaim['tgl_claim']);
			$metTgl = explode(",", $mets);
			//echo $metTgl[0].'-'.$metTgl[1].'-'.$metTgl[2].'<br />';
			if ($metTgl[2] > 0) {
				$jumBulan = $metTgl[1] + 1;
			} else {
				$jumBulan = $metTgl[1];
			}    //AKUMULASI BULAN THD JUMLAH HARI
			$maj = ($metTgl[0]*12) + $jumBulan;


			echo '<form method="post" action="">
			  <table border="0" cellpadding="3" cellspacing="0" width="80%" align="center" style="border: solid 1px #DEDEDE" align="center">
			  <input type="hidden" name="id" value="' . $_REQUEST['id'] . '">
			  <input type="hidden" name="kredit_tenor" value="' . $edklaim['tenor'] . '">
			<tr><th colspan="4">Edit Form Pengisian Data Klaim Meninggal</th></tr>
			<tr><td width="25%">Nama Perusahaan</td><td>: <b>' . $adcostumer['name'] . '</b></td>
			<tr><td>Nama Produk</td><td>: <b>' . $edklaim['nmproduk'] . '</b></td>
				<td width="25%" align="right">ID Debitur</td><td>: <b>' . $edklaim['id_peserta'] . '</b></td>
			</tr>
			<tr><td>Name</td><td colspan="3">: <b>' . $edklaim['nama'] . '</b></td></tr>
			<tr><td>Plafond</td><td colspan="3">: <b>' . duit($edklaim['kredit_jumlah']) . '</b></td></tr>
			<tr><td>Tanggal Asuransi</td><td>: <b>' . _convertDate($edklaim['kredit_tgl']) . '</b> s/d <b>' . _convertDate($edklaim['kredit_akhir']) . '</b></td>
				<td align="right">Tenor</td><td>: <b>' . $edklaim['tenor'] . '</b> Bulan</td>
			</tr>
			<tr><td>Tanggal Meninggal <font color="red"><b>*</b></font></td><td>: '.$edklaim['tgl_claim']. '</td>
				<td align="right">Maksimum Klaim</td><td>: <font color="blue"><b>' . duit($edklaim['kredit_jumlah']) . '</b></font></td>
			</tr>
			<tr><td>Tanggal Terima Laporan</td><td>: '.$edklaim['tglklaimdoc'].'</td>
			<td align="right">Ms Asuransi Berjalan</td><td>: <b>' . $maj . '</b> Bulan</td></tr>
			<tr><td>Tanggal Investigasi</td><td>: '.$edklaim['tglinvestigasi'].'</td>
			<td align="right">Nilai Tuntutan Klaim<font color="red"><b>*</b></font></td>
			<td>: '.duit($edklaim['tuntutan_klaim']) . '</td>
			<tr><td>Lokasi Meninggal <font color="red"><b>*</b></font></td><td>: '.$edklaim['tempat_meninggal']. '</td>
				<td align="right">Total Klaim Disetujui<font color="red"><b>*</b></font></td>
				<td>: '.duit($edklaim['total_claim']).' </td>
			</tr>
			<tr><td>Penyabab Meninggal <font color="red"><b>*</b></font></td><td>:
				';
			$nmPenyakit = $database->doQuery('SELECT * FROM fu_ajk_namapenyakit ORDER BY id ASC');
			while ($nmPenyakit_ = mysql_fetch_array($nmPenyakit)) {
				if($edklaim['nmpenyakit']==$nmPenyakit_['id']){
					echo $nmPenyakit_['namapenyakit'];
				}
			}
			echo '</td>
				<td align="right">Tanggal Kelengkapan Dokumen</td><td>: '.$edklaim['tglklaimdoc2'].'</td>
			</tr>
			<tr><td valign="top">Analisa Dokter Adonai</td><td>: '.$_REQUEST['ketDokter'].'</td>
			<td align="right">Tanggal Informasi Ke Asuransi</td><td>: '.$edklaim['tgllaporklaim'].'</td></tr>
			<tr>

			<td valign="top">Status Klaim</td><td>: ';
			$nmPenyakit = $database->doQuery('SELECT * FROM fu_ajk_klaim_status where view_list=0 ORDER BY order_list ASC');
			while ($nmPenyakit_ = mysql_fetch_array($nmPenyakit)) {
				if($edklaim['id_klaim_status']== $nmPenyakit_['id']){
					echo $nmPenyakit_['status_klaim'];
				}
			}
			echo '
			</td><td align="right">Tanggal Kirim Dokumen Ke Asuransi</td><td>: '.$edklaim['tglkirimdoc'].'</td>
			</tr>
			<tr>
			<td valign="top">Status Klaim</td><td>: '.$edklaim['policy_liability']. '
			</td>
			</tr>
			<tr>
			<td valign="top">Sumber Dana</td>
			<td>: '. $_REQUEST['sumber_dana'].'</td>
			</td>
			</tr>
			<tr>
			</tr>
				<tr><th colspan="5">Investigasi Klaim</th></tr>
			<tr><td>Tanggal Investigasi</td><td>: ';
				print initCalendar();
				print calendarBox('tglinvestigasi', 'triger3', $edklaim['tglinvestigasi']);
				echo '</td></tr>
			<tr>
			<td valign="top">Kategori Klaim</td><td>: <select name="kategori" style="width : 80%; visibility: visible;">
					<option value="I" ' . _selected($edklaim['kategori_klaim'], "I") . '>I (Satu)</option>
					<option value="II" ' . _selected($edklaim['kategori_klaim'], "II") . '>II (Dua)</option>
					<option value="III" ' . _selected($edklaim['kategori_klaim'], "III") . '>III (Tiga)</option></select>
			</td>
			</tr>';

			if($edklaim['tempat_meninggal']=='Rumah'){
				echo '
				<tr>
				<td valign="top">Kronologi <font color="red"><b></b></font></td><td>:
					<textarea name="kronologi" cols="2" rows="5" style="width : 80%">'.$edklaim['kronologi'].'</textarea>'.$error2.'
				</td>
				</tr>
				<tr>
				<td valign="top">Data Form AAJI <font color="red"><b></b></font></td><td>:
					<textarea name="form_aaji" cols="2" rows="5" style="width : 80%">'.$edklaim['form_aaji'].'</textarea>'.$error2.'
				</td>
				</tr>
				<tr>
				<td valign="top">Hasil Autopsi Verbal<font color="red"><b></b></font></td><td>:
					<textarea name="konfirm_ahliwaris" cols="2" rows="5" style="width : 80%">'.$edklaim['konfirm_ahliwaris'].'</textarea>'.$error2.'
				</td>
				</tr>
				<tr>
				<td valign="top">Extra Mortality<font color="red"><b></b></font></td><td>:
					<textarea name="extra_mortality" cols="2" rows="5" style="width : 80%">'.$edklaim['extra_mortality'].'</textarea>'.$error2.'
				</td>
				</tr>';
			}else{
				echo '
				<tr>
				<td valign="top">Anamnesis <font color="red"><b></b></font></td><td>:
					<textarea name="anamnesis" cols="2" rows="5" style="width : 80%">'.$edklaim['anamnesis'].'</textarea>'.$error2.'
				</td>
				</tr>
				<tr>
				<td valign="top">Pemeriksaan Fisik<font color="red"><b></b></font></td><td>:
					<textarea name="pemeriksaan_fisik" cols="2" rows="5" style="width : 80%">'.$edklaim['pemeriksaan_fisik'].'</textarea>'.$error2.'
				</td>
				</tr>
				<tr>
				<td valign="top">Pemeriksaan Penunjang<font color="red"><b></b></font></td><td>:
					<textarea name="pemeriksaan_penunjang" cols="2" rows="5" style="width : 80%">'.$edklaim['pemeriksaan_penunjang'].'</textarea>'.$error2.'
				</td>
				</tr>
				<tr><td valign="top">Keterangan Diagnosa <font color="red"><b></b></font></td>
					<td>: <textarea name="diagnosa" cols="2" rows="5" style="width : 80%">'.$edklaim['diagnosa']. '</textarea>'.$error3.'</td>
				</tr>
				<tr><td valign="top">Terapi <font color="red"><b></b></font></td>
					<td>: <textarea name="terapi" cols="2" rows="5" style="width : 80%">'.$edklaim['terapi']. '</textarea>'.$error3.'</td>
				</tr>
				<tr>
				<td valign="top">Hasil Autopsi Verbal<font color="red"><b></b></font></td><td>:
					<textarea name="konfirm_ahliwaris" cols="2" rows="5" style="width : 80%">'.$edklaim['konfirm_ahliwaris'].'</textarea>'.$error2.'
				</td>
				</tr>
				<tr>
				<td valign="top">Extra Mortality<font color="red"><b></b></font></td><td>:
					<textarea name="extra_mortality" cols="2" rows="5" style="width : 80%">'.$edklaim['extra_mortality'].'</textarea>'.$error2.'
				</td>
				</tr>';
			}
			echo '<tr>
			<td valign="top">Preexisting Condition <font color="red"><b></b></font></td><td>: <textarea name="preexisting" cols="2" rows="5" style="width : 80%">'.$edklaim['preexisting_cond'].'</textarea>'.$error6.'
			</td>
			</tr>
			<tr>
			<td valign="top">ICD 10 <font color="red"><b></b></font></td><td>: <!-- <input type="text" id="icd" name="icd"> !-->
				<input type="text" id="search-box" name="icd" placeholder="Icd 10" style="width : 80%" value="'.$edklaim['ic_diagnosis'].'"/>'.$error3.'
			<div id="suggesstion-box">
			</div>
			</td>
			</tr>
			<tr>
			<td valign="top">Analisa Dokter Adonai <font color="red"><b></b></font></td><td>: <textarea name="keterangan_dokter" cols="2" rows="5" style="width : 80%">'.$edklaim['ketDokter'].'</textarea>'.$error4.'
			</td>
			</tr>
			<td valign="top"></td><td>&nbsp;&nbsp;<button type="submit" name="ed_oop" value="save">Simpan Data</button>
			</td>
			</tr>
			<tr><th colspan="5">Kelengkapan Dokumen</th></tr>
			<tr><td colspan="5">
			<table border="0" cellpadding="3" cellspacing="1" width="100%" align="center" style="border: solid 1px #DEDEDE" align="center">
			<tr><th>No</th><th>Dokumen</th><th>Option</th></tr>';
			$met_dok = $database->doQuery('SELECT
						fu_ajk_dokumenklaim_bank.id,
						fu_ajk_dokumenklaim_bank.id_bank,
						fu_ajk_dokumenklaim_bank.id_dok,
						fu_ajk_dokumenklaim.nama_dok
						FROM
						fu_ajk_dokumenklaim_bank
						INNER JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim_bank.id_dok = fu_ajk_dokumenklaim.id
						WHERE id_bank="' . $edklaim['id_cost'] . '" AND id_produk="' . $edklaim['id_polis'] . '" ORDER BY nama_dok ASC');
			while ($met_dok_ = mysql_fetch_array($met_dok)) {
				$cekDokumenKlaim = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_klaim_doc WHERE id_pes="' . $edklaim['id_peserta'] . '" AND id_cost="' . $edklaim['id_cost'] . '" AND dokumen="' . $met_dok_['id'] . '" AND del IS NULL'));
				if ($cekDokumenKlaim) {
					if(!empty($cekDokumenKlaim['nama_dokumen'])){
						$cekDataDok = '<a href="../ajk_file/klaim/'.$cekDokumenKlaim['nama_dokumen'].'" target="_blank">view</a>';
					}else{
						$cekDataDok = '';
					}
				} else {
					$cekDataDok = '';
				}
				if (($no % 2) == 1) $objlass = 'tbl-odd'; else $objlass = 'tbl-even';
				echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
				  <td align="center">' . ++$no . '</td>
				  <td>' . $met_dok_['nama_dok'] . '</td>
				  <td align="center">' . $cekDataDok . '</td>
				  </tr>';
			}
			echo '</table></td></tr>';
			echo '<tr><td valign="top">Keterangan Dokumen Klaim</td><td colspan="3">'.$edklaim['keterangan'] . '</td></tr>
				</table></form>';;
	break;

	case "viewklaim":
		echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
		<tr><th colspan="8" width="100%" align="left" colspan="2">Modul Klaim - Edit Data Meninggal</font></th><th width="5%" colspan="2"><a href="ajk_claim.php?d=investigasi_klaim"><img src="image/back.png" width="20"></a></th></tr>
		</table><br />';
		$edklaim = mysql_fetch_array($database->doQuery('SELECT
				fu_ajk_asuransi.`name` AS asuransi,
				fu_ajk_cn.id,
				fu_ajk_cn.id_cost,
				fu_ajk_cn.id_cn,
				fu_ajk_cn.id_dn,
				fu_ajk_dn.dn_kode,
				fu_ajk_klaim.id as id_klaim,
				fu_ajk_peserta.id_polis,
				fu_ajk_peserta.id_peserta,
				fu_ajk_peserta.nama,
				fu_ajk_peserta.tgl_lahir,
				fu_ajk_peserta.usia,
				fu_ajk_peserta.kredit_tgl,
				IF(fu_ajk_peserta.type_data="SPK", fu_ajk_peserta.kredit_tenor * 12, fu_ajk_peserta.kredit_tenor) AS tenor,
				fu_ajk_peserta.kredit_akhir,
				fu_ajk_peserta.kredit_jumlah,
				fu_ajk_cn.tgl_claim,
				fu_ajk_cn.premi,
				fu_ajk_cn.confirm_claim,
				fu_ajk_cn.total_claim,
				fu_ajk_cn.tuntutan_klaim,
				fu_ajk_cn.tgl_byr_claim,
				fu_ajk_cn.nmpenyakit,
				fu_ajk_cn.keterangan,
				fu_ajk_polis.nmproduk,
				fu_ajk_klaim.id_klaim_status,
				fu_ajk_klaim.tgl_klaim AS tglklaim,
				fu_ajk_klaim.tgl_document AS tglklaimdoc,
				fu_ajk_klaim.tgl_document_lengkap AS tglklaimdoc2,
				fu_ajk_klaim.tgl_investigasi AS tglinvestigasi,
				fu_ajk_klaim.tgl_lapor_klaim AS tgllaporklaim,
				fu_ajk_klaim.jumlah AS totalklaim,
				fu_ajk_klaim.tempat_meninggal AS tempat_meninggal,
				fu_ajk_klaim.diagnosa AS diagnosa,
				fu_ajk_klaim.sebab_meninggal AS sebab_meninggal,
				fu_ajk_klaim.ket AS ketklaim,
				fu_ajk_klaim.sumber_dana,
				fu_ajk_klaim.ket_dokter AS ketDokter,
				fu_ajk_klaim.tgl_kirim_dokumen AS tglkirimdoc,
				fu_ajk_namapenyakit.id AS idpenyakit,
				fu_ajk_namapenyakit.namapenyakit,
				fu_ajk_cn.policy_liability,
				fu_ajk_klaim.kategori_klaim,
				fu_ajk_klaim.riwayat_penyakit,
				fu_ajk_klaim.kronologi,
				fu_ajk_klaim.hasil_investigasi,
				fu_ajk_klaim.form_aaji,
				fu_ajk_klaim.anamnesis,
				fu_ajk_klaim.pemeriksaan_fisik,
				fu_ajk_klaim.pemeriksaan_penunjang,
				fu_ajk_klaim.terapi,
				fu_ajk_klaim.konfirm_ahliwaris,
				fu_ajk_klaim.extra_mortality,
				fu_ajk_klaim.preexisting_cond
				FROM fu_ajk_cn
				LEFT JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
				LEFT JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
				LEFT JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
				LEFT JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
				LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta AND fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost
				LEFT JOIN fu_ajk_namapenyakit ON fu_ajk_cn.nmpenyakit = fu_ajk_namapenyakit.id
				WHERE fu_ajk_cn.id = "' . $_REQUEST['id'] . '" AND fu_ajk_cn.del IS NULL'));
		$adcostumer = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="' . $edklaim['id_cost'] . '"'));
		if ($_REQUEST['ed_oop'] == "save") {

				if($edklaim['tempat_meninggal']=='Rumah'){

					$tambahdok = $database->doQuery('update fu_ajk_klaim SET
							fu_ajk_klaim.tgl_investigasi="'.$_POST['tglinvestigasi'].'",
							fu_ajk_klaim.kategori_klaim="'.$_POST['kategori'].'",
							fu_ajk_klaim.kronologi="'.$_POST['kronologi'].'",
							fu_ajk_klaim.form_aaji="'.$_POST['form_aaji'].'",
							fu_ajk_klaim.konfirm_ahliwaris="'.$_POST['konfirm_ahliwaris'].'",
							fu_ajk_klaim.extra_mortality="'.$_POST['extra_mortality'].'"
							where id='.$edklaim['id_klaim']);

				}else{


					$tambahdok = $database->doQuery('update fu_ajk_klaim SET
							fu_ajk_klaim.tgl_investigasi="'.$_POST['tglinvestigasi'].'",
							fu_ajk_klaim.kategori_klaim="'.$_POST['kategori'].'",
							fu_ajk_klaim.diagnosa="'.$_POST['diagnosa'].'",
							fu_ajk_klaim.anamnesis="'.$_POST['anamnesis'].'",
							fu_ajk_klaim.pemeriksaan_fisik="'.$_POST['pemeriksaan_fisik'].'",
							fu_ajk_klaim.pemeriksaan_penunjang="'.$_POST['pemeriksaan_penunjang'].'",
							fu_ajk_klaim.terapi="'.$_POST['terapi'].'",
							fu_ajk_klaim.konfirm_ahliwaris="'.$_POST['konfirm_ahliwaris'].'",
							fu_ajk_klaim.extra_mortality="'.$_POST['extra_mortality'].'"
							where id='.$edklaim['id_klaim']);
				}
				echo '<center><h2>Data Klaim meninggal telah di update oleh ' . $q['nm_lengkap'] . ' pada tanggal ' . $futgl . '.</h2></center><meta http-equiv="refresh" content="2;URL=ajk_claim.php?d=investigasi_klaim">';

		}

		$mets = datediff($edklaim['kredit_tgl'], $edklaim['tgl_claim']);
		$metTgl = explode(",", $mets);
		//echo $metTgl[0].'-'.$metTgl[1].'-'.$metTgl[2].'<br />';
		if ($metTgl[2] > 0) {
			$jumBulan = $metTgl[1] + 1;
		} else {
			$jumBulan = $metTgl[1];
		}    //AKUMULASI BULAN THD JUMLAH HARI
		$maj = ($metTgl[0]*12) + $jumBulan;


		echo '<form method="post" action="">
	  <table border="0" cellpadding="3" cellspacing="0" width="80%" align="center" style="border: solid 1px #DEDEDE" align="center">
	  <input type="hidden" name="id" value="' . $_REQUEST['id'] . '">
	  <input type="hidden" name="kredit_tenor" value="' . $edklaim['tenor'] . '">
		<tr><th colspan="4">Edit Form Pengisian Data Klaim Meninggal</th></tr>
		<tr><td width="25%">Nama Perusahaan</td><td>: <b>' . $adcostumer['name'] . '</b></td>
		<tr><td>Nama Produk</td><td>: <b>' . $edklaim['nmproduk'] . '</b></td>
			<td width="25%" align="right">ID Debitur</td><td>: <b>' . $edklaim['id_peserta'] . '</b></td>
		</tr>
		<tr><td>Name</td><td colspan="3">: <b>' . $edklaim['nama'] . '</b></td></tr>
		<tr><td>Plafond</td><td colspan="3">: <b>' . duit($edklaim['kredit_jumlah']) . '</b></td></tr>
		<tr><td>Tanggal Asuransi</td><td>: <b>' . _convertDate($edklaim['kredit_tgl']) . '</b> s/d <b>' . _convertDate($edklaim['kredit_akhir']) . '</b></td>
			<td align="right">Tenor</td><td>: <b>' . $edklaim['tenor'] . '</b> Bulan</td>
		</tr>
		<tr><td>Tanggal Meninggal <font color="red"><b>*</b></font></td><td>: '.$edklaim['tgl_claim']. '</td>
			<td align="right">Maksimum Klaim</td><td>: <font color="blue"><b>' . duit($edklaim['kredit_jumlah']) . '</b></font></td>
		</tr>
		<tr><td>Tanggal Terima Laporan</td><td>: '.$edklaim['tglklaimdoc'].'</td>
		<td align="right">Ms Asuransi Berjalan</td><td>: <b>' . $maj . '</b> Bulan</td></tr>
		<tr><td>Tanggal Investigasi</td><td>: '.$edklaim['tglinvestigasi'].'</td>
		<td align="right">Nilai Tuntutan Klaim<font color="red"><b>*</b></font></td>
		<td>: '.duit($edklaim['tuntutan_klaim']) . '</td>
		<tr><td>Lokasi Meninggal <font color="red"><b>*</b></font></td><td>: '.$edklaim['tempat_meninggal']. '</td>
			<td align="right">Total Klaim Disetujui<font color="red"><b>*</b></font></td>
			<td>: '.duit($edklaim['total_claim']).' </td>
		</tr>
		<tr><td>Penyabab Meninggal <font color="red"><b>*</b></font></td><td>:';
		$nmPenyakit = $database->doQuery('SELECT * FROM fu_ajk_namapenyakit ORDER BY id ASC');
		while ($nmPenyakit_ = mysql_fetch_array($nmPenyakit)) {
			if($edklaim['nmpenyakit']==$nmPenyakit_['id']){
				echo $nmPenyakit_['namapenyakit'];
			}
		}
		echo '</td>
		<td align="right">Tanggal Kelengkapan Dokumen</td><td>: '.$edklaim['tglklaimdoc2'].'</td>
		</tr>
		<tr><!--<td valign="top">Keterangan Dokter Adonai</td><td>: '.$_REQUEST['ketDokter'].'</td>-->
		<td align="right">Tanggal Informasi Ke Asuransi</td><td>: '.$edklaim['tgllaporklaim'].'</td></tr>
		<tr>

		<td valign="top">Status Klaim</td><td>: ';
		$nmPenyakit = $database->doQuery('SELECT * FROM fu_ajk_klaim_status where view_list=0 ORDER BY order_list ASC');
		while ($nmPenyakit_ = mysql_fetch_array($nmPenyakit)) {
			if($edklaim['id_klaim_status']== $nmPenyakit_['id']){
				echo $nmPenyakit_['status_klaim'];
			}
		}
		echo '
			</td><td align="right">Tanggal Kirim Dokumen Ke Asuransi</td><td>: '.$edklaim['tglkirimdoc'].'</td>
			</tr>
			<tr>
			<td valign="top">Status Klaim</td><td>: '.$edklaim['policy_liability']. '
			</td>
			</tr>
			<tr>
			<td valign="top">Sumber Dana</td>
			<td>: '. $_REQUEST['sumber_dana'].'</td>
			</td>
			</tr>
			<tr>
			</tr>
				<tr><th colspan="5">Investigasi Klaim</th></tr>
			<tr>
			<td>Tanggal Investigasi</td><td>: ';
				print initCalendar();
				print calendarBox('tglinvestigasi', 'triger3', $edklaim['tglinvestigasi']);
				echo '</td>
			</tr>
			<tr>
			<td valign="top">Kategori Klaim</td><td>: <select name="kategori" style="width: 80%; visibility: visible;">
					<option value="I" ' . _selected($edklaim['kategori_klaim'], "I") . '>I (Satu)</option>
					<option value="II" ' . _selected($edklaim['kategori_klaim'], "II") . '>II (Dua)</option>
					<option value="III" ' . _selected($edklaim['kategori_klaim'], "III") . '>III (Tiga)</option></select>
			</td>
			</tr>';
			if($edklaim['tempat_meninggal']=='Rumah'){
				echo '
					<tr>
					<td valign="top">Kronologi <font color="red"><b></b></font></td><td>:
						<textarea name="kronologi" cols="2" rows="5" style="width : 80%">'.$edklaim['kronologi'].'</textarea>'.$error2.'
					</td>
					</tr>
					<tr>
					<td valign="top">Data Form AAJI <font color="red"><b></b></font></td><td>:
						<textarea name="form_aaji" cols="2" rows="5" style="width : 80%">'.$edklaim['form_aaji'].'</textarea>'.$error2.'
					</td>
					</tr>
					<tr>
					<td valign="top">Hasil Autopsi Verbal<font color="red"><b></b></font></td><td>:
						<textarea name="konfirm_ahliwaris" cols="2" rows="5" style="width : 80%">'.$edklaim['konfirm_ahliwaris'].'</textarea>'.$error2.'
					</td>
					</tr>
					<tr>
					<td valign="top">Extra Mortality<font color="red"><b></b></font></td><td>:
						<textarea name="extra_mortality" cols="2" rows="5" style="width : 80%">'.$edklaim['extra_mortality'].'</textarea>'.$error2.'
					</td>
					</tr>';
			}else{
				echo '
					<tr>
					<td valign="top">Anamnesis <font color="red"><b></b></font></td><td>:
						<textarea name="anamnesis" cols="2" rows="5" style="width : 80%">'.$edklaim['anamnesis'].'</textarea>'.$error2.'
					</td>
					</tr>
					<tr>
					<td valign="top">Pemeriksaan Fisik<font color="red"><b></b></font></td><td>:
						<textarea name="pemeriksaan_fisik" cols="2" rows="5" style="width : 80%">'.$edklaim['pemeriksaan_fisik'].'</textarea>'.$error2.'
					</td>
					</tr>
					<tr>
					<td valign="top">Pemeriksaan Penunjang<font color="red"><b></b></font></td><td>:
						<textarea name="pemeriksaan_penunjang" cols="2" rows="5" style="width : 80%">'.$edklaim['pemeriksaan_penunjang'].'</textarea>'.$error2.'
					</td>
					</tr>
					<tr><td valign="top">Keterangan Diagnosa <font color="red"><b></b></font></td>
						<td>: <textarea name="diagnosa" cols="2" rows="5" style="width : 80%">'.$edklaim['diagnosa']. '</textarea>'.$error3.'</td>
					</tr>
					<tr><td valign="top">Terapi <font color="red"><b></b></font></td>
						<td>: <textarea name="terapi" cols="2" rows="5" style="width : 80%">'.$edklaim['terapi']. '</textarea>'.$error3.'</td>
					</tr>
					<tr>
					<td valign="top">Hasil Autopsi Verbal<font color="red"><b></b></font></td><td>:
						<textarea name="konfirm_ahliwaris" cols="2" rows="5" style="width : 80%">'.$edklaim['konfirm_ahliwaris'].'</textarea>'.$error2.'
					</td>
					</tr>
					<tr>
					<td valign="top">Extra Mortality<font color="red"><b></b></font></td><td>:
						<textarea name="extra_mortality" cols="2" rows="5" style="width : 80%">'.$edklaim['extra_mortality'].'</textarea>'.$error2.'
					</td>
					</tr>';
			}
			echo '<!-- <tr>
			<td valign="top">Riwayat Penyakit <font color="red"><b></b></font></td><td>: <textarea name="riwayat" cols="2" rows="5" style="width : 80%">'.$edklaim['riwayat_penyakit'].'</textarea>'.$error1.'
			</td>
			</tr>
			<tr>
			<td valign="top">Hasil Investigasi <font color="red"><b></b></font></td><td>: <textarea name="hasil_investigasi" cols="2" rows="5" style="width : 80%">'.$edklaim['hasil_investigasi'].'</textarea>'.$error4.'
			</td>
			</tr>--!><tr>
			<td valign="top"></td><td>&nbsp;&nbsp;<button type="submit" name="ed_oop" value="save">Simpan Data</button>
			</td>
			</tr>
			<tr><th colspan="5">Kelengkapan Dokumen</th></tr>
			<tr><td colspan="5">
			<table border="0" cellpadding="3" cellspacing="1" width="100%" align="center" style="border: solid 1px #DEDEDE" align="center">
			<tr><th>No</th><th>Dokumen</th><th>Option</th></tr>';
				$met_dok = $database->doQuery('SELECT
						fu_ajk_dokumenklaim_bank.id,
						fu_ajk_dokumenklaim_bank.id_bank,
						fu_ajk_dokumenklaim_bank.id_dok,
						fu_ajk_dokumenklaim.nama_dok
						FROM
						fu_ajk_dokumenklaim_bank
						INNER JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim_bank.id_dok = fu_ajk_dokumenklaim.id
						WHERE id_bank="' . $edklaim['id_cost'] . '" AND id_produk="' . $edklaim['id_polis'] . '" ORDER BY nama_dok ASC');
				while ($met_dok_ = mysql_fetch_array($met_dok)) {
					$cekDokumenKlaim = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_klaim_doc WHERE id_pes="' . $edklaim['id_peserta'] . '" AND id_cost="' . $edklaim['id_cost'] . '" AND dokumen="' . $met_dok_['id'] . '" AND del IS NULL'));
					if ($cekDokumenKlaim) {
						if(!empty($cekDokumenKlaim['nama_dokumen'])){
							$cekDataDok = '<a href="../ajk_file/klaim/'.$cekDokumenKlaim['nama_dokumen'].'" target="_blank">view</a>';
						}else{
							$cekDataDok = '';
						}
						//$cekDataDok = '<a href="../ajk_file/klaim/'.$cekDokumenKlaim['nama_dokumen'].'" target="_blank">view</a>';
					} else {
						$cekDataDok = '';
					}
					if (($no % 2) == 1) $objlass = 'tbl-odd'; else $objlass = 'tbl-even';
					echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
				  <td align="center">' . ++$no . '</td>
				  <td>' . $met_dok_['nama_dok'] . '</td>
				  <td align="center">' . $cekDataDok . '</td>
				  </tr>';
				}
				echo '</table></td></tr>';
				echo '<tr><td valign="top">Keterangan Dokumen Klaim</td><td colspan="3">'.$edklaim['keterangan'] . '</td></tr>
				</table></form>';;
	break;

	case "inv_klaim1" :
			echo '<style>
			#country-list{float:left;list-style:none;margin:0;padding:0;}
			#country-list li{padding: 10px; background:#FAFAFA;border-bottom:#F0F0F0 1px solid;}
			#country-list li:hover{background:#F0F0F0;}
			</style>
			<script>
			$(document).ready(function(){
			    $("#search-box").keyup(function(){
			        $.ajax({
			        type: "POST",
			        url: "search.php",
			        data:\'keyword=\'+$(this).val(),
			        beforeSend: function(){
			            $("#search-box").css("background","#FFF url(LoaderIcon.gif) no-repeat 165px");
			        },
			        success: function(data){
			            $("#suggesstion-box").show();
			            $("#suggesstion-box").html(data);
			            $("#search-box").css("background","#FFF");
			        }
			        });
			    });
			});

			function selectCountry(val) {
			$("#search-box").val(val);
			$("#suggesstion-box").hide();
			}
			</script>';
				echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
			<tr><th colspan="8" width="100%" align="left" colspan="2">Modul Klaim - Edit Data Meninggal</font></th><th width="5%" colspan="2"><a href="ajk_claim.php?d=med_opinion1"><img src="image/back.png" width="20"></a></th></tr>
			</table><br />';
				$edklaim = mysql_fetch_array($database->doQuery('SELECT
					fu_ajk_asuransi.`name` AS asuransi,
					fu_ajk_cn.id,
					fu_ajk_cn.id_cost,
					fu_ajk_cn.id_cn,
					fu_ajk_cn.id_dn,
					fu_ajk_dn.dn_kode,
					fu_ajk_klaim.id as id_klaim,
					fu_ajk_peserta.id_polis,
					fu_ajk_peserta.id_peserta,
					fu_ajk_peserta.nama,
					fu_ajk_peserta.tgl_lahir,
					fu_ajk_peserta.usia,
					fu_ajk_peserta.kredit_tgl,
					IF(fu_ajk_peserta.type_data="SPK", fu_ajk_peserta.kredit_tenor * 12, fu_ajk_peserta.kredit_tenor) AS tenor,
					fu_ajk_peserta.kredit_akhir,
					fu_ajk_peserta.kredit_jumlah,
					fu_ajk_cn.tgl_claim,
					fu_ajk_cn.premi,
					fu_ajk_cn.confirm_claim,
					fu_ajk_cn.total_claim,
					fu_ajk_cn.tuntutan_klaim,
					fu_ajk_cn.tgl_byr_claim,
					fu_ajk_cn.nmpenyakit,
					fu_ajk_cn.keterangan,
					fu_ajk_polis.nmproduk,
					fu_ajk_klaim.id_klaim_status,
					fu_ajk_klaim.tgl_klaim AS tglklaim,
					fu_ajk_klaim.tgl_document AS tglklaimdoc,
					fu_ajk_klaim.tgl_document_lengkap AS tglklaimdoc2,
					fu_ajk_klaim.tgl_investigasi AS tglinvestigasi,
					fu_ajk_klaim.tgl_lapor_klaim AS tgllaporklaim,
					fu_ajk_klaim.jumlah AS totalklaim,
					fu_ajk_klaim.tempat_meninggal AS tempat_meninggal,
					fu_ajk_klaim.diagnosa AS diagnosa,
					fu_ajk_klaim.sebab_meninggal AS sebab_meninggal,
					fu_ajk_klaim.ket AS ketklaim,
					fu_ajk_klaim.sumber_dana,
					fu_ajk_klaim.ket_dokter AS ketDokter,
					fu_ajk_klaim.tgl_kirim_dokumen AS tglkirimdoc,
					fu_ajk_namapenyakit.id AS idpenyakit,
					fu_ajk_namapenyakit.namapenyakit,
					fu_ajk_cn.policy_liability,
					fu_ajk_klaim.ic_diagnosis,
					fu_ajk_klaim.kategori_klaim,
					fu_ajk_klaim.riwayat_penyakit,
					fu_ajk_klaim.kronologi,
					fu_ajk_klaim.hasil_investigasi,
					fu_ajk_klaim.form_aaji,
					fu_ajk_klaim.anamnesis,
					fu_ajk_klaim.pemeriksaan_fisik,
					fu_ajk_klaim.pemeriksaan_penunjang,
					fu_ajk_klaim.terapi,
					fu_ajk_klaim.konfirm_ahliwaris,
					fu_ajk_klaim.extra_mortality,
					fu_ajk_klaim.preexisting_cond
					FROM fu_ajk_cn
					LEFT JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
					LEFT JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
					LEFT JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
					LEFT JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
					LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta AND fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost
					LEFT JOIN fu_ajk_namapenyakit ON fu_ajk_cn.nmpenyakit = fu_ajk_namapenyakit.id
					WHERE fu_ajk_cn.id = "' . $_REQUEST['id'] . '" AND fu_ajk_cn.del IS NULL'));
				$adcostumer = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="' . $edklaim['id_cost'] . '"'));


				$mets = datediff($edklaim['kredit_tgl'], $edklaim['tgl_claim']);
				$metTgl = explode(",", $mets);
				//echo $metTgl[0].'-'.$metTgl[1].'-'.$metTgl[2].'<br />';
				if ($metTgl[2] > 0) {
					$jumBulan = $metTgl[1] + 1;
				} else {
					$jumBulan = $metTgl[1];
				}    //AKUMULASI BULAN THD JUMLAH HARI
				$maj = ($metTgl[0]*12) + $jumBulan;


				echo '<form method="post" action="">
				  <table border="0" cellpadding="3" cellspacing="0" width="80%" align="center" style="border: solid 1px #DEDEDE" align="center">
				  <input type="hidden" name="id" value="' . $_REQUEST['id'] . '">
				  <input type="hidden" name="kredit_tenor" value="' . $edklaim['tenor'] . '">
				<tr><th colspan="4">Edit Form Pengisian Data Klaim Meninggal</th></tr>
				<tr><td width="25%">Nama Perusahaan</td><td>: <b>' . $adcostumer['name'] . '</b></td>
				<tr><td>Nama Produk</td><td>: <b>' . $edklaim['nmproduk'] . '</b></td>
					<td width="25%" align="right">ID Debitur</td><td>: <b>' . $edklaim['id_peserta'] . '</b></td>
				</tr>
				<tr><td>Name</td><td colspan="3">: <b>' . $edklaim['nama'] . '</b></td></tr>
				<tr><td>Plafond</td><td colspan="3">: <b>' . duit($edklaim['kredit_jumlah']) . '</b></td></tr>
				<tr><td>Tanggal Asuransi</td><td>: <b>' . _convertDate($edklaim['kredit_tgl']) . '</b> s/d <b>' . _convertDate($edklaim['kredit_akhir']) . '</b></td>
					<td align="right">Tenor</td><td>: <b>' . $edklaim['tenor'] . '</b> Bulan</td>
				</tr>
				<tr><td>Tanggal Meninggal <font color="red"><b>*</b></font></td><td>: '.$edklaim['tgl_claim']. '</td>
					<td align="right">Maksimum Klaim</td><td>: <font color="blue"><b>' . duit($edklaim['kredit_jumlah']) . '</b></font></td>
				</tr>
				<tr><td>Tanggal Terima Laporan</td><td>: '.$edklaim['tglklaimdoc'].'</td>
				<td align="right">Ms Asuransi Berjalan</td><td>: <b>' . $maj . '</b> Bulan</td></tr>
				<tr><td>Tanggal Investigasi</td><td>: '.$edklaim['tglinvestigasi'].'</td>
				<td align="right">Nilai Tuntutan Klaim<font color="red"><b>*</b></font></td>
				<td>: '.duit($edklaim['tuntutan_klaim']) . '</td>
				<tr><td>Lokasi Meninggal <font color="red"><b>*</b></font></td><td>: '.$edklaim['tempat_meninggal']. '</td>
					<td align="right">Total Klaim Disetujui<font color="red"><b>*</b></font></td>
					<td>: '.duit($edklaim['total_claim']).' </td>
			</tr>
			<tr><td>Penyabab Meninggal <font color="red"><b>*</b></font></td><td>:
			';
				$nmPenyakit = $database->doQuery('SELECT * FROM fu_ajk_namapenyakit ORDER BY id ASC');
				while ($nmPenyakit_ = mysql_fetch_array($nmPenyakit)) {
					if($edklaim['nmpenyakit']==$nmPenyakit_['id']){
						echo $nmPenyakit_['namapenyakit'];
					}
				}
				echo '</td>
			<td align="right">Tanggal Kelengkapan Dokumen</td><td>: '.$edklaim['tglklaimdoc2'].'</td>
			</tr>
			<tr><td valign="top">Analisa Dokter Adonai</td><td>: '.$_REQUEST['ketDokter'].'</td>
			<td align="right">Tanggal Informasi Ke Asuransi</td><td>: '.$edklaim['tgllaporklaim'].'</td></tr>
			<tr>

			<td valign="top">Status Klaim</td><td>: ';
						$nmPenyakit = $database->doQuery('SELECT * FROM fu_ajk_klaim_status where view_list=0 ORDER BY order_list ASC');
						while ($nmPenyakit_ = mysql_fetch_array($nmPenyakit)) {
							if($edklaim['id_klaim_status']== $nmPenyakit_['id']){
								echo $nmPenyakit_['status_klaim'];
							}
						}
						echo '
			</td><td align="right">Tanggal Kirim Dokumen Ke Asuransi</td><td>: '.$edklaim['tglkirimdoc'].'</td>
			</tr>
			<tr>
			<td valign="top">Status Klaim</td><td>: '.$edklaim['policy_liability']. '
			</td>
			</tr>
			<tr>
			<td valign="top">Sumber Dana</td>
			<td>: '. $_REQUEST['sumber_dana'].'</td>
			</td>
			</tr>
			<tr>
			</tr>
				<tr><th colspan="5">Investigasi Klaim</th></tr>
			<tr><td>Tanggal Investigasi</td><td>: ';
						print initCalendar();
						print calendarBox('tglinvestigasi', 'triger3', $edklaim['tglinvestigasi']);
						echo '</td></tr>
			<tr>
			<td valign="top">Kategori Klaim</td><td>: <select name="kategori" style="width : 80%; visibility: visible;">
					<option value="I" ' . _selected($edklaim['kategori_klaim'], "I") . '>I (Satu)</option>
					<option value="II" ' . _selected($edklaim['kategori_klaim'], "II") . '>II (Dua)</option>
					<option value="III" ' . _selected($edklaim['kategori_klaim'], "III") . '>III (Tiga)</option></select>
			</td>
			</tr>';

				if($edklaim['tempat_meninggal']=='Rumah'){
					echo '
					<tr>
					<td valign="top">Kronologi <font color="red"><b></b></font></td><td>:
						<textarea name="kronologi" cols="2" rows="5" style="width : 80%">'.$edklaim['kronologi'].'</textarea>'.$error2.'
					</td>
					</tr>
					<tr>
					<td valign="top">Data Form AAJI <font color="red"><b></b></font></td><td>:
						<textarea name="form_aaji" cols="2" rows="5" style="width : 80%">'.$edklaim['form_aaji'].'</textarea>'.$error2.'
					</td>
					</tr>
					<tr>
					<td valign="top">Hasil Autopsi Verbal<font color="red"><b></b></font></td><td>:
						<textarea name="konfirm_ahliwaris" cols="2" rows="5" style="width : 80%">'.$edklaim['konfirm_ahliwaris'].'</textarea>'.$error2.'
					</td>
					</tr>
					<tr>
					<td valign="top">Extra Mortality<font color="red"><b></b></font></td><td>:
						<textarea name="extra_mortality" cols="2" rows="5" style="width : 80%">'.$edklaim['extra_mortality'].'</textarea>'.$error2.'
					</td>
					</tr>';
				}else{
					echo '
					<tr>
					<td valign="top">Anamnesis <font color="red"><b></b></font></td><td>:
						<textarea name="anamnesis" cols="2" rows="5" style="width : 80%">'.$edklaim['anamnesis'].'</textarea>'.$error2.'
					</td>
					</tr>
					<tr>
					<td valign="top">Pemeriksaan Fisik<font color="red"><b></b></font></td><td>:
						<textarea name="pemeriksaan_fisik" cols="2" rows="5" style="width : 80%">'.$edklaim['pemeriksaan_fisik'].'</textarea>'.$error2.'
					</td>
					</tr>
					<tr>
					<td valign="top">Pemeriksaan Penunjang<font color="red"><b></b></font></td><td>:
						<textarea name="pemeriksaan_penunjang" cols="2" rows="5" style="width : 80%">'.$edklaim['pemeriksaan_penunjang'].'</textarea>'.$error2.'
					</td>
					</tr>
					<tr><td valign="top">Keterangan Diagnosa <font color="red"><b></b></font></td>
						<td>: <textarea name="diagnosa" cols="2" rows="5" style="width : 80%">'.$edklaim['diagnosa']. '</textarea>'.$error3.'</td>
					</tr>
					<tr><td valign="top">Terapi <font color="red"><b></b></font></td>
						<td>: <textarea name="terapi" cols="2" rows="5" style="width : 80%">'.$edklaim['terapi']. '</textarea>'.$error3.'</td>
					</tr>
					<tr>
					<td valign="top">Hasil Autopsi Verbal<font color="red"><b></b></font></td><td>:
						<textarea name="konfirm_ahliwaris" cols="2" rows="5" style="width : 80%">'.$edklaim['konfirm_ahliwaris'].'</textarea>'.$error2.'
					</td>
					</tr>
					<tr>
					<td valign="top">Extra Mortality<font color="red"><b></b></font></td><td>:
						<textarea name="extra_mortality" cols="2" rows="5" style="width : 80%">'.$edklaim['extra_mortality'].'</textarea>'.$error2.'
					</td>
					</tr>';
				}
				echo '<tr>
				<td valign="top">Preexisting Condition <font color="red"><b></b></font></td><td>: <textarea name="preexisting" cols="2" rows="5" style="width : 80%">'.$edklaim['preexisting_cond'].'</textarea>'.$error6.'
				</td>
				</tr>
				<tr>
				<td valign="top">ICD 10 <font color="red"><b></b></font></td><td>: <!-- <input type="text" id="icd" name="icd"> !-->
					<input type="text" id="search-box" name="icd" placeholder="Icd 10" style="width : 80%" value="'.$edklaim['ic_diagnosis'].'"/>'.$error3.'
				<div id="suggesstion-box">
				</div>
				</td>
				</tr>
				<tr>
				<td valign="top">Analisa Dokter Adonai <font color="red"><b></b></font></td><td>: <textarea name="keterangan_dokter" cols="2" rows="5" style="width : 80%">'.$edklaim['ketDokter'].'</textarea>'.$error4.'
				</td>
				</tr>
				</td>
				</tr>
				<tr><th colspan="5">Kelengkapan Dokumen</th></tr>
				<tr><td colspan="5">
				<table border="0" cellpadding="3" cellspacing="1" width="100%" align="center" style="border: solid 1px #DEDEDE" align="center">
				<tr><th>No</th><th>Dokumen</th><th>Option</th></tr>';
				$met_dok = $database->doQuery('SELECT
					fu_ajk_dokumenklaim_bank.id,
					fu_ajk_dokumenklaim_bank.id_bank,
					fu_ajk_dokumenklaim_bank.id_dok,
					fu_ajk_dokumenklaim.nama_dok
					FROM
					fu_ajk_dokumenklaim_bank
					INNER JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim_bank.id_dok = fu_ajk_dokumenklaim.id
					WHERE id_bank="' . $edklaim['id_cost'] . '" AND id_produk="' . $edklaim['id_polis'] . '" ORDER BY nama_dok ASC');
				while ($met_dok_ = mysql_fetch_array($met_dok)) {
					$cekDokumenKlaim = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_klaim_doc WHERE id_pes="' . $edklaim['id_peserta'] . '" AND id_cost="' . $edklaim['id_cost'] . '" AND dokumen="' . $met_dok_['id'] . '" AND del IS NULL'));
					if ($cekDokumenKlaim) {
						if(!empty($cekDokumenKlaim['nama_dokumen'])){
							$cekDataDok = '<a href="../ajk_file/klaim/'.$cekDokumenKlaim['nama_dokumen'].'" target="_blank">view</a>';
						}else{
							$cekDataDok = '';
						}
					} else {
						$cekDataDok = '';
					}
					if (($no % 2) == 1) $objlass = 'tbl-odd'; else $objlass = 'tbl-even';
					echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
			  <td align="center">' . ++$no . '</td>
			  <td>' . $met_dok_['nama_dok'] . '</td>
			  <td align="center">' . $cekDataDok . '</td>
			  </tr>';
				}
				echo '</table></td></tr>';
				echo '<tr><td valign="top">Keterangan Dokumen Klaim</td><td colspan="3">'.$edklaim['keterangan'] . '</td></tr>
			</table></form>';;
	break;

	case "legal_opinion" :
			if(!isset($_REQUEST['tglinput'])){
				$dateku=date("Y-m-d");
				$dateku1=date("Y-m-d");
				$tglmu='and date(fu_ajk_cn.approve_date) between "'.$dateku.'" and "'.$dateku1.'"';
			}elseif($_REQUEST['tglinput']==""){
				$dateku='';
				$dateku1='';
				$tglmu='';
			}else{
				$dateku=$_REQUEST['tglinput'];
				$dateku1=$_REQUEST['tglinput1'];
				$tglmu='and date(fu_ajk_cn.approve_date) between "'.$dateku.'" and "'.$dateku1.'"';

			}

			echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
			<tr><th colspan="8" width="100%" align="left" colspan="2">Modul Klaim - Legal Opinion</font></th></tr>
			</table><br />';
			echo '<fieldset>
			<legend>Searching</legend>
			<form method="post" action="">
			<table border="0" width="60%" cellpadding="1" cellspacing="0" align="center">
		  	<tr><td width="10%" align="center">ID PESERTA</td>
				<td width="10%" align="center">NAMA</td>
				<td width="20%" align="center">TANGGAL APPROVE KLAIM</td>
			</tr>
			<tr>
				<td align="center"><input type="text" name="ridp" value="'.$_REQUEST['ridp'].'"></td>
				<td align="center"><input type="text" name="rnama" value="'.$_REQUEST['rnama'].'"></td>
			  	<td align="center">';
				print initCalendar();
				print calendarBox('tglinput', 'triger1', $dateku);
				print initCalendar();
				print calendarBox('tglinput1', 'triger2', $dateku1);
				echo '</td></tr>
		  	<tr><td colspan="6" align="center"><input type="submit" name="oppe" value="Searching" class="button"></td></tr>
			</table></form></fieldset>';
				//}
				echo '<table border="0" width="100%" cellpadding="5" cellspacing="1" bgcolor="#bde0e6">

			<tr><th width="3%">No</td>
			<th>Input Date</td>
			<th>No. Urut</td>
			<th>Asuransi</td>
			<th width="5%">ID Peserta</td>
			<th width="1%">ID DN</td>
			<th width="1%">Produk</td>
			<th>Nama Debitur</td>
			<th>Cabang</td>
			<th width="5%">Kredit Awal</td>
			<th width="5%">Kredit Akhir</td>
			<th width="5%">Tgl Klaim</td>
			<th width="1%">Tgl Approve</td>
			<th width="1%">Tenor</td>
			<!-- <th width="1%">Tenor (S,B)</td> --!>
			<th width="1%">Jumlah</td>
			<th width="1%">Status</td>
			<th width="1%">Tgl Bayar Klaim</td>
			<th width="1%">jDoc</td>
			<th width="1%">Status</td>
			<th width="5%">Investigasi</td>
			</tr>';
			if ($_REQUEST['ridp'])		{	$empat = 'AND fu_ajk_peserta.id_peserta LIKE "%' . $_REQUEST['ridp'] . '%"';		}
			if ($_REQUEST['nodn'])		{	$satu = 'AND fu_ajk_dn.dn_kode LIKE "%' . $_REQUEST['nodn'] . '%"';		}
			if ($_REQUEST['nocn'])		{	$dua = 'AND fu_ajk_cn.id_cn LIKE "%' . $_REQUEST['nocn'] . '%"';		}

			if ($_REQUEST['rnama'])		{	$tiga = 'AND fu_ajk_peserta.nama LIKE "%'.$_REQUEST['rnama'].'%"';
			$namanya = mysql_fetch_array($database->doQuery('SELECT id_klaim, status_peserta, id_peserta FROM fu_ajk_peserta WHERE id_klaim !="" AND status_peserta="Death" '.$tiga.' '));
			$ernama = 'AND fu_ajk_peserta.id_peserta = "' . $namanya['id_peserta'] . '"';
			}
			if ($_REQUEST['rdob'])		{	$empat = 'AND tgl_lahir = "' . $_REQUEST['rdob'] . '"';
			$dobnya = mysql_fetch_array($database->doQuery('SELECT id_klaim, status_peserta, id_peserta FROM fu_ajk_peserta WHERE id_klaim !="" AND status_peserta="Death" '.$empat.' '));
			$erdob = 'AND fu_ajk_peserta.tgl_lahir = "' . $dobnya['tgl_lahir'] . '"';
			}

			if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}

			//$metklaim = $database->doQuery('SELECT * FROM fu_ajk_cn WHERE type_claim = "Death" '.$satu.' '.$dua.' '.$ernama.' '.$erdob.' AND del IS NULL ORDER BY id DESC LIMIT ' . $m . ' , 25');
			$metklaim = $database->doQuery('SELECT
			fu_ajk_asuransi.`name` AS asuransi,
			fu_ajk_cn.id,
			fu_ajk_klaim.no_urut_klaim,
			fu_ajk_klaim.id as id_klaim,
			fu_ajk_cn.id_cost,
			fu_ajk_cn.id_cn,
			fu_ajk_dn.dn_kode,
			fu_ajk_peserta.id_peserta,
			fu_ajk_peserta.nama,
			fu_ajk_peserta.tgl_lahir,
			fu_ajk_peserta.usia,
			fu_ajk_peserta.kredit_tgl,
			IF(fu_ajk_peserta.type_data="SPK", fu_ajk_peserta.kredit_tenor * 12, fu_ajk_peserta.kredit_tenor) AS tenor,
			fu_ajk_peserta.kredit_akhir,
			fu_ajk_peserta.kredit_jumlah,
			fu_ajk_cn.tgl_claim,
			fu_ajk_cn.premi,
			fu_ajk_cn.confirm_claim,
			fu_ajk_cn.total_claim,
			fu_ajk_cn.tuntutan_klaim,
			fu_ajk_cn.tgl_byr_claim,
			fu_ajk_polis.nmproduk,
			fu_ajk_peserta.cabang,
			fu_ajk_klaim.tgl_kirim_dokumen,
			fu_ajk_klaim.investigasi,
			fu_ajk_cn.tgl_bayar_asuransi,
			fu_ajk_cn.input_date,
			fu_ajk_cn.approve_date,
			fu_ajk_klaim_status.status_klaim
			FROM
			fu_ajk_cn
			INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
			INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
			INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
			INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta AND fu_ajk_cn.id = fu_ajk_peserta.id_klaim
			LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
			LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim.id_klaim_status = fu_ajk_klaim_status.id
			WHERE
			fu_ajk_cn.type_claim = "Death" AND fu_ajk_cn.confirm_claim <> "Pending" AND fu_ajk_cn.del IS NULL
			and (fu_ajk_klaim.investigasi="N" or fu_ajk_klaim.investigasi="" or fu_ajk_klaim.investigasi="P")
			'.$tglmu.'
			'.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.'
			ORDER BY fu_ajk_klaim.investigasi desc, fu_ajk_cn.id DESC LIMIT ' . $m . ' , 25');
			//$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_cn WHERE id != "" AND type_claim="Death" '.$satu.' '.$dua.' '.$ernama.' '.$erdob.' AND del IS NULL'));
			$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(fu_ajk_cn.id_cn) FROM
			fu_ajk_cn
			INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
			INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
			INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
			INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta AND fu_ajk_cn.id = fu_ajk_peserta.id_klaim
			LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
			LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim.id_klaim_status = fu_ajk_klaim_status.id
			WHERE fu_ajk_cn.type_claim = "Death" AND fu_ajk_cn.confirm_claim <> "Pending" AND fu_ajk_cn.del IS NULL '.$tglmu.' '.$satu.' '.$dua.' '.$tiga.'  '.$empat.' '.$lima.''));
			$totalRows = $totalRows[0];
			$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
			while ($rklaim = mysql_fetch_array($metklaim)) {
				/*
				 $klaim_dn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn WHERE id="'.$rklaim['id_dn'].'"'));
				 $klaimpeserta = mysql_fetch_array($database->doQuery('SELECT id_cost, id_peserta, status_peserta, type_data, del, nama, tgl_lahir, kredit_tgl, kredit_tenor, kredit_akhir FROM fu_ajk_peserta WHERE id_cost="'.$rklaim['id_cost'].'" AND id_peserta="'.$rklaim['id_peserta'].'" AND status_peserta="Death" AND del IS NULL'));
				 $klaimcost = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$rklaim['id_cost'].'"'));
				 $klaimpolis = mysql_fetch_array($database->doQuery('SELECT id, id_cost, nmproduk, nopol FROM fu_ajk_polis WHERE id="'.$rklaim['id_nopol'].'"'));
				 $xAsuransi = mysql_fetch_array($database->doQuery('SELECT id, id_bank, id_polis, id_asuransi, id_polis_as, id_dn, id_peserta FROM fu_ajk_peserta_as WHERE id_bank="'.$rklaim['id_cost'].'" AND id_polis="'.$rklaim['id_nopol'].'" AND id_peserta="'.$rklaim['id_peserta'].'"'));
				 $xAsuransi_ = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_asuransi WHERE id="'.$xAsuransi['id_asuransi'].'"'));

				 $now = new T10DateCalc($klaimpeserta['kredit_tgl']);
				 $periodbulan = $now->compareDate($rklaim['tgl_claim']) / 30.4375;
				 $maj = ceil($periodbulan);

				 if ($klaimpeserta['type_data']=="SPK") {	$tenorSPK = $klaimpeserta['kredit_tenor'] * 12;	}else{	$tenorSPK = $klaimpeserta['kredit_tenor'];	}
				 */
				//$jdoc = mysql_num_rows($database->doQuery('SELECT id_pes, id_cost FROM fu_ajk_klaim_doc WHERE id_pes="'.$rklaim['id_peserta'].'" AND id_cost="'.$rklaim['id_cost'].'"'));
				$jdoc = mysql_num_rows($database->doQuery('
				SELECT DISTINCT fu_ajk_dokumenklaim_bank.id_bank, fu_ajk_dokumenklaim_bank.id_dok, fu_ajk_dokumenklaim.nama_dok
				FROM fu_ajk_dokumenklaim_bank
				INNER JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim_bank.id_dok = fu_ajk_dokumenklaim.id
				INNER JOIN fu_ajk_klaim_doc ON fu_ajk_klaim_doc.dokumen = fu_ajk_dokumenklaim_bank.id
				where fu_ajk_klaim_doc.id_pes="'.$rklaim['id_peserta'].'" AND fu_ajk_klaim_doc.id_cost="'.$rklaim['id_cost'].'"
				'));

				$mets = datediff($rklaim['kredit_tgl'], $rklaim['tgl_claim']);
				$metTgl = explode(",",$mets);
				//echo $metTgl[0].'-'.$metTgl[1].'-'.$metTgl[2].'<br />';
				if ($metTgl[2] > 0) {	$jumBulan = $metTgl[1] + 1;	}else{	$jumBulan = $metTgl[1];	}	//AKUMULASI BULAN THD JUMLAH HARI
				$klaimBlnJ = $metTgl[0] + $jumBulan;
				$klaimBlnS = $rklaim['tenor'] - $klaimBlnJ;
				//SETTING TGL BAYAR KLAIM//
				if ($rklaim['tgl_byr_claim']=="") {	$metbyrklaim = 'Belum dibayar';	}else{	$metbyrklaim = '<font color="blue">'._convertDate($rklaim['tgl_byr_claim']).'</font>';	}
				//SETTING TGL BAYAR KLAIM//

				//SETTING PEMBERITAHUAN INVESTIGASI
				/*
				 if ($rklaim['confirm_claim']=="Investigasi") {
					$setInvestigasi ='<a title="Edit data ahli waris" href="ajk_claim.php?d=eInvKlaim&ido='.$rklaim['id'].'"><img src="image/edit.png" width="30"></a>';
					}else{
					$setInvestigasi ='<a title="Confirm Investigasi Klaim" href="ajk_claim.php?d=InvKlaim&id='.$rklaim['id'].'"><img src="image/doc_death.png" width="30"></a>';
					}
					*/
				if(is_null($metklaim['investigasi'])){
					$setInvestigasi='<a title="approve data klaim" href="ajk_claim.php?d=app_investigasi_klaim&id='.$rklaim['id_klaim'].'" onclick="if(confirm(\'Apakah anda yakin untuk melakukan pengajuan data klaim pada peserta ini ke dokter ?\')){return true;}{return false;}"><img src="image/save.png" width="20"></a>';
				}
				$surat_pem='';
				if(!is_null($rklaim['tgl_kirim_dokumen']) && $rklaim['tgl_kirim_dokumen']!='0000-00-00' && datediff($rklaim['tgl_kirim_dokumen'], $futoday)>=7){
					$surat_pem='<a title="Print Surat Persetujuan Ke asuransi" target="_blank" href="e_report_klaim.php?er=setuju_as&id='.$rklaim['id'].'"><img src="image/print.png" width="30"></a>';
				}

				if(!is_null($rklaim['tgl_kirim_dokumen']) && $rklaim['tgl_kirim_dokumen']!='0000-00-00' && datediff($rklaim['tgl_kirim_dokumen'], $futoday)>=14){
					$surat_pem='<a title="Print Surat Realisasi Ke asuransi '.$rklaim['tgl_bayar_asuransi'].'"  target="_blank" href="e_report_klaim.php?er=realisasi_as&id='.$rklaim['id'].'"><img src="image/print.png" width="30"></a>';
				}

				if($rklaim['investigasi']=='' or $rklaim['investigasi']=='N'){
					$status='Unapprove';
				}elseif($rklaim['investigasi']=='P'){
					$status='Pending';
				}

				//SETTING PEMBERITAHUAN INVESTIGASI
				if (($no % 2) == 1)	$objlass = 'tbl-odd'; else				$objlass = 'tbl-even';
				echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
					<td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
					<td>'.$rklaim['input_date'].'</td>
					<td>'.$rklaim['no_urut_klaim'].'</td>
					<td align="center">'.$rklaim['asuransi'].'</td>
					<td align="center"><a href="e_report_klaim.php?er=_invklaim&idC='.$rklaim['id'].'" target="_blank">'.$rklaim['id_peserta'].'</a></td>
					<td align="center">'.substr($rklaim['dn_kode'],3).'</td>
					<td align="center">'.$rklaim['nmproduk'].'</td>
					<td><a title="Worksheet Klaim" href="ajk_claim.php?d=wklaim&id='.$rklaim['id'].'" target="_blank">'.$rklaim['nama'].'</a></td>
					<td align="center">'.$rklaim['cabang'].'</td>
					<td align="center">'._convertDate($rklaim['kredit_tgl']).'</td>
					<td align="center">'._convertDate($rklaim['kredit_akhir']).'</td>
					<td align="center">'._convertDate($rklaim['tgl_claim']).'</td>
					<td align="center">'._convertDate($rklaim['approve_date']).'</td>
					<td align="center">'.$rklaim['tenor'].'</td>
					<!--  <td align="center">'.$klaimBlnS.','.$klaimBlnJ.'</td> --!>
					<td align="right">'.duit($rklaim['tuntutan_klaim']).'</td>
					<td align="center">'.$rklaim['status_klaim'].'</td>
					<td align="center">'.$metbyrklaim.'</td>
					<td align="center">'.$jdoc.'</td>
					<td align="center">'.$status.'</td>
					<td align="center">
					<a title="edit data klaim" href="ajk_claim.php?d=vlegal_opinion&id='.$rklaim['id'].'"><img src="image/edit3.png"></a>

					'.$setInvestigasi.'</td>
			</tr>';
			}
			echo '<tr><td colspan="22">';
			echo createPageNavigations($file = 'ajk_claim.php?d=legal_opinion', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
			echo '<b>Total Data Meninggal: <u>' . $totalRows . '</u></b></td></tr>';
			echo '</table>';
			;
	break;

	case "vlegal_opinion" :
			echo '<style>
			#country-list{float:left;list-style:none;margin:0;padding:0;}
			#country-list li{padding: 10px; background:#FAFAFA;border-bottom:#F0F0F0 1px solid;}
			#country-list li:hover{background:#F0F0F0;}
			</style>
			<script>
			$(document).ready(function(){
			    $("#search-box").keyup(function(){
			        $.ajax({
			        type: "POST",
			        url: "search.php",
			        data:\'keyword=\'+$(this).val(),
			        beforeSend: function(){
			            $("#search-box").css("background","#FFF url(LoaderIcon.gif) no-repeat 165px");
			        },
			        success: function(data){
			            $("#suggesstion-box").show();
			            $("#suggesstion-box").html(data);
			            $("#search-box").css("background","#FFF");
			        }
			        });
			    });
			});

			function selectCountry(val) {
			$("#search-box").val(val);
			$("#suggesstion-box").hide();
			}
			</script>';
				echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
			<tr><th colspan="8" width="100%" align="left" colspan="2">Modul Klaim - Edit Data Meninggal</font></th><th width="5%" colspan="2"><a href="ajk_claim.php?d=legal_opinion"><img src="image/back.png" width="20"></a></th></tr>
			</table><br />';
				$edklaim = mysql_fetch_array($database->doQuery('SELECT
					fu_ajk_asuransi.`name` AS asuransi,
					fu_ajk_cn.id,
					fu_ajk_cn.id_cost,
					fu_ajk_cn.id_cn,
					fu_ajk_cn.id_dn,
					fu_ajk_dn.dn_kode,
					fu_ajk_klaim.id as id_klaim,
					fu_ajk_peserta.id_polis,
					fu_ajk_peserta.id_peserta,
					fu_ajk_peserta.nama,
					fu_ajk_peserta.tgl_lahir,
					fu_ajk_peserta.usia,
					fu_ajk_peserta.kredit_tgl,
					IF(fu_ajk_peserta.type_data="SPK", fu_ajk_peserta.kredit_tenor * 12, fu_ajk_peserta.kredit_tenor) AS tenor,
					fu_ajk_peserta.kredit_akhir,
					fu_ajk_peserta.kredit_jumlah,
					fu_ajk_cn.tgl_claim,
					fu_ajk_cn.premi,
					fu_ajk_cn.confirm_claim,
					fu_ajk_cn.total_claim,
					fu_ajk_cn.tuntutan_klaim,
					fu_ajk_cn.tgl_byr_claim,
					fu_ajk_cn.nmpenyakit,
					fu_ajk_cn.keterangan,
					fu_ajk_polis.nmproduk,
					fu_ajk_klaim.id_klaim_status,
					fu_ajk_klaim.tgl_klaim AS tglklaim,
					fu_ajk_klaim.tgl_document AS tglklaimdoc,
					fu_ajk_klaim.tgl_document_lengkap AS tglklaimdoc2,
					fu_ajk_klaim.tgl_investigasi AS tglinvestigasi,
					fu_ajk_klaim.tgl_lapor_klaim AS tgllaporklaim,
					fu_ajk_klaim.jumlah AS totalklaim,
					fu_ajk_klaim.tempat_meninggal AS tempat_meninggal,
					fu_ajk_klaim.diagnosa AS diagnosa,
					fu_ajk_klaim.sebab_meninggal AS sebab_meninggal,
					fu_ajk_klaim.ket AS ketklaim,
					fu_ajk_klaim.sumber_dana,
					fu_ajk_klaim.ket_dokter AS ketDokter,
					fu_ajk_klaim.tgl_kirim_dokumen AS tglkirimdoc,
					fu_ajk_namapenyakit.id AS idpenyakit,
					fu_ajk_namapenyakit.namapenyakit,
					fu_ajk_cn.policy_liability,
					fu_ajk_klaim.ic_diagnosis,
					fu_ajk_klaim.kategori_klaim,
					fu_ajk_klaim.riwayat_penyakit,
					fu_ajk_klaim.kronologi,
					fu_ajk_klaim.hasil_investigasi,
					fu_ajk_klaim.form_aaji,
					fu_ajk_klaim.anamnesis,
					fu_ajk_klaim.pemeriksaan_fisik,
					fu_ajk_klaim.pemeriksaan_penunjang,
					fu_ajk_klaim.terapi,
					fu_ajk_klaim.konfirm_ahliwaris,
					fu_ajk_klaim.extra_mortality,
					fu_ajk_klaim.legal_note,
					fu_ajk_klaim.preexisting_cond
					FROM fu_ajk_cn
					LEFT JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
					LEFT JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
					LEFT JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
					LEFT JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
					LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta AND fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost
					LEFT JOIN fu_ajk_namapenyakit ON fu_ajk_cn.nmpenyakit = fu_ajk_namapenyakit.id
					WHERE fu_ajk_cn.id = "' . $_REQUEST['id'] . '" AND fu_ajk_cn.del IS NULL'));
				$adcostumer = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="' . $edklaim['id_cost'] . '"'));
				if ($_REQUEST['ed_oop'] == "save") {


						$tambahdok = $database->doQuery('update fu_ajk_klaim SET
								fu_ajk_klaim.legal_note="'.$_POST['legal_opinion'].'"
								, tgl_app_legal=current_timestamp()
								where id='.$edklaim['id_klaim']);

					echo '<center><h2>Data Klaim meninggal telah di update oleh ' . $q['nm_lengkap'] . ' pada tanggal ' . $futgl . '.</h2></center><meta http-equiv="refresh" content="2;URL=ajk_claim.php?d=legal_opinion">';

				}

				$mets = datediff($edklaim['kredit_tgl'], $edklaim['tgl_claim']);
				$metTgl = explode(",", $mets);
				//echo $metTgl[0].'-'.$metTgl[1].'-'.$metTgl[2].'<br />';
				if ($metTgl[2] > 0) {
					$jumBulan = $metTgl[1] + 1;
				} else {
					$jumBulan = $metTgl[1];
				}    //AKUMULASI BULAN THD JUMLAH HARI
				$maj = ($metTgl[0]*12) + $jumBulan;


				echo '<form method="post" action="">
			  <table border="0" cellpadding="3" cellspacing="0" width="80%" align="center" style="border: solid 1px #DEDEDE" align="center">
			  <input type="hidden" name="id" value="' . $_REQUEST['id'] . '">
			  <input type="hidden" name="kredit_tenor" value="' . $edklaim['tenor'] . '">
				<tr><th colspan="4">Edit Form Pengisian Data Klaim Meninggal</th></tr>
				<tr><td width="25%">Nama Perusahaan</td><td>: <b>' . $adcostumer['name'] . '</b></td>
				<tr><td>Nama Produk</td><td>: <b>' . $edklaim['nmproduk'] . '</b></td>
					<td width="25%" align="right">ID Debitur</td><td>: <b>' . $edklaim['id_peserta'] . '</b></td>
				</tr>
				<tr><td>Name</td><td colspan="3">: <b>' . $edklaim['nama'] . '</b></td></tr>
				<tr><td>Plafond</td><td colspan="3">: <b>' . duit($edklaim['kredit_jumlah']) . '</b></td></tr>
				<tr><td>Tanggal Asuransi</td><td>: <b>' . _convertDate($edklaim['kredit_tgl']) . '</b> s/d <b>' . _convertDate($edklaim['kredit_akhir']) . '</b></td>
					<td align="right">Tenor</td><td>: <b>' . $edklaim['tenor'] . '</b> Bulan</td>
				</tr>
				<tr><td>Tanggal Meninggal <font color="red"><b>*</b></font></td><td>: '.$edklaim['tgl_claim']. '</td>
					<td align="right">Maksimum Klaim</td><td>: <font color="blue"><b>' . duit($edklaim['kredit_jumlah']) . '</b></font></td>
				</tr>
				<tr><td>Tanggal Terima Laporan</td><td>: '.$edklaim['tglklaimdoc'].'</td>
				<td align="right">Ms Asuransi Berjalan</td><td>: <b>' . $maj . '</b> Bulan</td></tr>
				<tr><td>Tanggal Investigasi</td><td>: '.$edklaim['tglinvestigasi'].'</td>
				<td align="right">Nilai Tuntutan Klaim<font color="red"><b>*</b></font></td>
				<td>: '.duit($edklaim['tuntutan_klaim']) . '</td>
				<tr><td>Lokasi Meninggal <font color="red"><b>*</b></font></td><td>: '.$edklaim['tempat_meninggal']. '</td>
					<td align="right">Total Klaim Disetujui<font color="red"><b>*</b></font></td>
					<td>: '.duit($edklaim['total_claim']).' </td>
				</tr>
				<tr><td>Penyabab Meninggal <font color="red"><b>*</b></font></td><td>:';
				$nmPenyakit = $database->doQuery('SELECT * FROM fu_ajk_namapenyakit ORDER BY id ASC');
				while ($nmPenyakit_ = mysql_fetch_array($nmPenyakit)) {
					if($edklaim['nmpenyakit']==$nmPenyakit_['id']){
						echo $nmPenyakit_['namapenyakit'];
					}
				}
				echo '</td>
			<td align="right">Tanggal Kelengkapan Dokumen</td><td>: '.$edklaim['tglklaimdoc2'].'</td>
			</tr>
			<tr><td valign="top">Analisa Dokter Adonai</td><td>: '.$_REQUEST['ketDokter'].'</td>
			<td align="right">Tanggal Informasi Ke Asuransi</td><td>: '.$edklaim['tgllaporklaim'].'</td></tr>
			<tr>

			<td valign="top">Status Klaim</td><td>: ';
				$nmPenyakit = $database->doQuery('SELECT * FROM fu_ajk_klaim_status where view_list=0 ORDER BY order_list ASC');
				while ($nmPenyakit_ = mysql_fetch_array($nmPenyakit)) {
					if($edklaim['id_klaim_status']== $nmPenyakit_['id']){
						echo $nmPenyakit_['status_klaim'];
					}
				}
				echo '
			</td><td align="right">Tanggal Kirim Dokumen Ke Asuransi</td><td>: '.$edklaim['tglkirimdoc'].'</td>
			</tr>
			<tr>
			<td valign="top">Status Klaim</td><td>: '.$edklaim['policy_liability']. '
			</td>
			</tr>
			<tr>
			<td valign="top">Sumber Dana</td>
			<td>: '. $_REQUEST['sumber_dana'].'</td>
			</td>
			</tr>
			<tr>
			</tr>
				<tr><th colspan="5">Investigasi Klaim</th></tr>

			<tr><td>Tanggal Investigasi</td><td>: <input type="text" disabled value="'.$edklaim['tglinvestigasi'].'">
					</td></tr>
			<tr>
			<td valign="top">Kategori Klaim</td><td>: <select name="kategori" disabled>
					<option value="I" ' . _selected($edklaim['kategori_klaim'], "I") . '>I (Satu)</option>
					<option value="II" ' . _selected($edklaim['kategori_klaim'], "II") . '>II (Dua)</option>
					<option value="III" ' . _selected($edklaim['kategori_klaim'], "III") . '>III (Tiga)</option></select>
			</td>
			</tr>';

				if($edklaim['tempat_meninggal']=='Rumah'){
					echo '
					<tr>
					<td valign="top">Kronologi <font color="red"><b></b></font></td><td>:
						<textarea name="kronologi" disabled cols="2" rows="5" style="width : 80%">'.$edklaim['kronologi'].'</textarea>'.$error2.'
					</td>
					</tr>
					<tr>
					<td valign="top">Data Form AAJI <font color="red"><b></b></font></td><td>:
						<textarea name="form_aaji" disabled cols="2" rows="5" style="width : 80%">'.$edklaim['form_aaji'].'</textarea>'.$error2.'
					</td>
					</tr>
					<tr>
					<td valign="top">Hasil Autopsi Verbal<font color="red"><b></b></font></td><td>:
						<textarea name="konfirm_ahliwaris" disabled cols="2" rows="5" style="width : 80%">'.$edklaim['konfirm_ahliwaris'].'</textarea>'.$error2.'
					</td>
					</tr>
					<tr>
					<td valign="top">Extra Mortality<font color="red"><b></b></font></td><td>:
						<textarea name="extra_mortality" disabled cols="2" rows="5" style="width : 80%">'.$edklaim['extra_mortality'].'</textarea>'.$error2.'
					</td>
					</tr>';
				}else{
					echo '
					<tr>
					<td valign="top">Anamnesis <font color="red"><b></b></font></td><td>:
						<textarea name="anamnesis" disabled cols="2" rows="5" style="width : 80%">'.$edklaim['anamnesis'].'</textarea>'.$error2.'
					</td>
					</tr>
					<tr>
					<td valign="top">Pemeriksaan Fisik<font color="red"><b></b></font></td><td>:
						<textarea name="pemeriksaan_fisik" disabled cols="2" rows="5" style="width : 80%">'.$edklaim['pemeriksaan_fisik'].'</textarea>'.$error2.'
					</td>
					</tr>
					<tr>
					<td valign="top">Pemeriksaan Penunjang<font color="red"><b></b></font></td><td>:
						<textarea name="pemeriksaan_penunjang" disabled cols="2" rows="5" style="width : 80%">'.$edklaim['pemeriksaan_penunjang'].'</textarea>'.$error2.'
					</td>
					</tr>
					<tr><td valign="top">Keterangan Diagnosa <font color="red"><b></b></font></td>
						<td>: <textarea name="diagnosa" disabled cols="2" rows="5" style="width : 80%">'.$edklaim['diagnosa']. '</textarea>'.$error3.'</td>
					</tr>
					<tr><td valign="top">Terapi <font color="red"><b></b></font></td>
						<td>: <textarea name="terapi" disabled cols="2" rows="5" style="width : 80%">'.$edklaim['terapi']. '</textarea>'.$error3.'</td>
					</tr>
					<tr>
					<td valign="top">Hasil Autopsi Verbal<font color="red"><b></b></font></td><td>:
						<textarea name="konfirm_ahliwaris" disabled cols="2" rows="5" style="width : 80%">'.$edklaim['konfirm_ahliwaris'].'</textarea>'.$error2.'
					</td>
					</tr>
					<tr>
					<td valign="top">Extra Mortality<font color="red"><b></b></font></td><td>:
						<textarea name="extra_mortality" disabled cols="2" rows="5" style="width : 80%">'.$edklaim['extra_mortality'].'</textarea>'.$error2.'
					</td>
					</tr>';
				}
			echo '<tr>
					<td valign="top">Preexisting Condition <font color="red"><b></b></font></td><td>: <textarea disabled name="preexisting" cols="2" rows="5" style="width : 80%">'.$edklaim['preexisting_cond'].'</textarea>'.$error6.'
					</td>
					</tr>
					<tr>
					<td valign="top">ICD 10 <font color="red"><b></b></font></td><td>: <!-- <input type="text" id="icd" name="icd"> !-->
						<input type="text" disabled name="icd" placeholder="Icd 10" style="width : 80%" value="'.$edklaim['ic_diagnosis'].'"/>'.$error3.'
					<div id="suggesstion-box">
					</div>
					</td>
					</tr>
					<tr>
					<td valign="top">Analisa Dokter Adonai <font color="red"><b></b></font></td><td>: <textarea disabled name="keterangan_dokter" cols="2" rows="5" style="width : 80%">'.$edklaim['ketDokter'].'</textarea>'.$error4.'
					</td>
					</tr>
					<tr>
					<td valign="top">Legal Opinion<font color="red"><b>*</b></font></td><td>: <textarea name="legal_opinion" cols="2" rows="5" style="width : 80%">'.$edklaim['legal_note'].'</textarea>'.$error4.'
					</td>
					</tr><tr>
					<td valign="top"></td><td>&nbsp;&nbsp;<button type="submit" name="ed_oop" value="save">Simpan Data</button>
					</td>
					</tr>
					</td>
					</tr>
						<tr><th colspan="5">Kelengkapan Dokumen</th></tr>
						<tr><td colspan="5">
						<table border="0" cellpadding="3" cellspacing="1" width="100%" align="center" style="border: solid 1px #DEDEDE" align="center">
						<tr><th>No</th><th>Dokumen</th><th>Option</th></tr>';
						$met_dok = $database->doQuery('SELECT
							fu_ajk_dokumenklaim_bank.id,
							fu_ajk_dokumenklaim_bank.id_bank,
							fu_ajk_dokumenklaim_bank.id_dok,
							fu_ajk_dokumenklaim.nama_dok
							FROM
							fu_ajk_dokumenklaim_bank
							INNER JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim_bank.id_dok = fu_ajk_dokumenklaim.id
							WHERE id_bank="' . $edklaim['id_cost'] . '" AND id_produk="' . $edklaim['id_polis'] . '" ORDER BY nama_dok ASC');
						while ($met_dok_ = mysql_fetch_array($met_dok)) {
							$cekDokumenKlaim = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_klaim_doc WHERE id_pes="' . $edklaim['id_peserta'] . '" AND id_cost="' . $edklaim['id_cost'] . '" AND dokumen="' . $met_dok_['id'] . '" AND del IS NULL'));
							if ($cekDokumenKlaim) {
								if(!empty($cekDokumenKlaim['nama_dokumen'])){
									$cekDataDok = '<a href="../ajk_file/klaim/'.$cekDokumenKlaim['nama_dokumen'].'" target="_blank">view</a>';
								}else{
									$cekDataDok = '';
								}
							} else {
								$cekDataDok = '';
							}
							if (($no % 2) == 1) $objlass = 'tbl-odd'; else $objlass = 'tbl-even';
							echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
					  <td align="center">' . ++$no . '</td>
					  <td>' . $met_dok_['nama_dok'] . '</td>
					  <td align="center">' . $cekDataDok . '</td>
					  </tr>';
						}
						echo '</table></td></tr>';
						echo '<tr><td valign="top">Keterangan Dokumen Klaim</td><td colspan="3">'.$edklaim['keterangan'] . '</td></tr>
					</table></form>';;
	break;

	case "monitoring":
			echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="100%" align="left">Data Klaim Outstanding</font></th></tr></table>';
			$metcost = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name DESC');
					echo '<form method="post" action="" name="postform">
				  <input type="hidden" name="id_cost" value="'.$metcost['id'].'">
				  <table border="0" cellpadding="1" cellspacing="0" width="100%">
			      <tr><td width="40%" align="right">Nama Perusahaan <font color="red">*</font></td><td> : <select name="id_cost" id="id_cost">
					  	<option value="">---Pilih Perusahaan---</option>';
					while($metcost_ = mysql_fetch_array($metcost)) {
						echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['id_cost'], $metcost_['id']).'>'.$metcost_['name'].'</option>';
					}
					$metcost1 = $database->doQuery('SELECT * FROM fu_ajk_asuransi ORDER BY name DESC');
					echo '</select></td></tr>
					<tr><td align="right">Nama Produk</td>
					<td id="polis_rate">: <select name="id_polis" id="id_polis">
					<option value="">-- Pilih Produk --</option>
					</select></td></tr>

					<tr><td align="right">Kategori</td>
					<td id="polis_rate">: <select name="tipe_produk">
							<option value="All">-- All Kategori--</option>
							<option value="SPK"'._selected($_REQUEST['tipe_produk'], "SPK").'>Reguler</option>
							<option value="NON SPK"'._selected($_REQUEST['tipe_produk'], "NON SPK").'>Percepatan</option>
					</select></td></tr>
					<tr><td width="40%" align="right">Nama Asuransi <font color="red">*</font></td><td> : <select name="id_asuransi">
					  	<option value="all">---Semua Asuransi---</option>';
					while($metcost_ = mysql_fetch_array($metcost1)) {
						echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['id_asuransi'], $metcost_['id']).'>'.$metcost_['name'].'</option>';
					}
					echo '</select></td></tr>
					<tr><td align="right">Liability</td>
					<td id="polis_rate">: <select name="liability">
						<option value="ALL">-- All Klaim Liability--</option>
						<option value="LIABLE" '._selected($_REQUEST['liability'], "LIABLE").'>LIABLE</option>
						<option value="NONLIABLE" '._selected($_REQUEST['liability'], "NONLIABLE").'>NON LIABLE</option>
					</select></td></tr>
					<tr><td align="right">Status Bayar</td>
					<td id="polis_rate">: <select name="status_bayar">
					<option value="">-- Pilih Status bayar--</option>';
					$metcost1 = $database->doQuery('SELECT * FROM fu_ajk_pembayaran_status where `type`=\'2\' ORDER BY id DESC');
					while($metcost_ = mysql_fetch_array($metcost1)) {
						echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['status_bayar'], $metcost_['id']).'>'.$metcost_['pembayaran_status'].'</option>';
					}
					echo '</select></td></tr>
					<tr><td align="right">Kelengakapan Dokumen</td>
					<td id="polis_rate">: <select name="status_klaim">
						<option value="">-- Pilih Status Klaim--</option>
						<option value="Dokumen Sudah Lengkap" '._selected($_REQUEST['status_klaim'], "Dokumen Sudah Lengkap").'>Dokumen Sudah Lengkap</option>
						<option value="Dokumen Belum Lengkap" '._selected($_REQUEST['status_klaim'], "Dokumen Belum Lengkap").'>Dokumen Belum Lengkap</option>
						<option value="Ditolak" '._selected($_REQUEST['status_klaim'], "Ditolak").'>Ditolak</option>
					</select></td></tr>
					<tr><td align="right">Kol</td>
					<td id="polis_rate">: <select name="kol">
							<option value="">-- Pilih Kol--</option>
							<option value="1"'._selected($_REQUEST['kol'], "1").'>1</option>
							<option value="2"'._selected($_REQUEST['kol'], "2").'>2</option>
							<option value="3"'._selected($_REQUEST['kol'], "3").'>3</option>
							<option value="4"'._selected($_REQUEST['kol'], "4").'>4</option>
							<option value="5"'._selected($_REQUEST['kol'], "5").'>5</option>
					</select></td></tr>

				<tr><td align="right">Tanggal Lapor</td>
				  <td> : <input type="text" id="fromakad1" name="tglcheck1" value="'.$_REQUEST['tglcheck1'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromakad1);return false;"/>
						<a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromakad1);return false;">
						<img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
						<iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
				  		s/d
				  		<input type="text" id="fromakad2" name="tglcheck2" value="'.$_REQUEST['tglcheck2'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromakad2);return false;"/>
						<a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromakad2);return false;">
						<img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
						<iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
				  </td>
				  </tr>
				  <tr><td align="right">DOL</td>
				  <td> : <input type="text" id="fromakad3" name="tglcheck3" value="'.$_REQUEST['tglcheck3'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromakad3);return false;"/>
						<a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromakad3);return false;">
						<img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
						<iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
				  		s/d
				  		<input type="text" id="fromakad4" name="tglcheck4" value="'.$_REQUEST['tglcheck4'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromakad4);return false;"/>
						<a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromakad4);return false;">
						<img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
						<iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
				  </td>
				  </tr>

					<td align="center"colspan="2"><input type="hidden" name="re" value="dataklaim"><input type="submit" name="ere" value="Cari"></td></tr>
				  </table>
				  </form>';

					if($_REQUEST['re']=='dataklaim'){
						if ($_REQUEST['id_cost']==""){
							$error_1 = '<div align="center"><font color="red"><blink>Silahkan pilih bank<br /></div></font></blink>';
						}

						if ($error_1 or $error_2 or $error_3 or $error_4) {
							echo $error_1 . '' . $error_2 . '' .$error_3 . '' . $error_4 ;
						} else {


							if($_REQUEST['id_asuransi']=="all"){
								$asuransi="";
							}else{
								$asuransi="and fu_ajk_asuransi.id=".$_REQUEST['id_asuransi'];
							}

							if(empty($_REQUEST['id_polis'])){
								$polis="";
							}else{
								$polis=" and fu_ajk_polis.id=".$_REQUEST['id_polis'];
							}

							if($_REQUEST['liability']=='ALL'){
								$liability='';
							}else{

								$liability=' and fu_ajk_cn.policy_liability="'.$_REQUEST['liability'].'"';
							}
							$tgl_lapor='';
							if($_REQUEST['tglcheck1']!==""){
								$tgl_lapor=' and fu_ajk_cn.approve_date between "'.$_REQUEST['tglcheck1'].'" and "'.$_REQUEST['tglcheck2'].'"';
							}

							$tipe_produk='';
							if($_REQUEST['tipe_produk']!=="All"){
								$tipe_produk=' and fu_ajk_polis.typeproduk="'.$_REQUEST['tipe_produk'].'"';
							}

							$tgl_dol='';
							if($_REQUEST['tglcheck3']!==""){
								$tgl_lapor=' and fu_ajk_cn.tgl_claim between "'.$_REQUEST['tglcheck1'].'" and "'.$_REQUEST['tglcheck2'].'"';
							}


							if(empty($_REQUEST['status_klaim'])){
								$status_klaim="";
							}else{
								$status_klaim="  and if(`id_klaim_status`=6,'Ditolak',
								if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
								'Dokumen Belum Lengkap')) ='".$_REQUEST['status_klaim']."'";
							}

							if(empty($_REQUEST['status_bayar'])){
								$status_bayar="";
							}else{
								$status_bayar=" and fu_ajk_cn.status_bayar=".$_REQUEST['status_bayar'];
							}

							if(empty($_REQUEST['kol'])){
								$kol="";
							}else{
								$kol=" and
									IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',

									IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
									IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
									IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
									,
									IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
									IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
									IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5'))))=".$_REQUEST['kol'];
							}

							if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}

							$sqlKlaim = $database->doQuery("SELECT
									CONCAT(DATE_FORMAT(fu_ajk_cn.approve_date,'%Y%m'),'/',fu_ajk_klaim.id) AS klaim_id,
									fu_ajk_cn.id_cabang,
									fu_ajk_grupproduk.nmproduk AS mitra,
									fu_ajk_asuransi.`name`,
									fu_ajk_polis.nmproduk,
									IF(fu_ajk_polis.`typeproduk`='NON SPK','Percepatan','Reguler') AS kategori,
									fu_ajk_peserta.id_peserta,
									fu_ajk_peserta.nama,
									fu_ajk_peserta.tgl_lahir,
									fu_ajk_peserta.usia,
									fu_ajk_peserta.kredit_jumlah,
									fu_ajk_cn.id as cnid,
									fu_ajk_cn.total_claim,
									fu_ajk_cn.tuntutan_klaim,
									fu_ajk_peserta.kredit_tgl,
									ROUND(IF(fu_ajk_peserta.type_data='SPK',fu_ajk_peserta.kredit_tenor*12,fu_ajk_peserta.kredit_tenor)/12) AS tenor,
									fu_ajk_klaim.tgl_klaim AS dol,
									DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl) AS akad_dol,
									DATEDIFF(fu_ajk_cn.approve_date,fu_ajk_klaim.tgl_klaim) AS lama_terima_laporan,
									current_date AS tgl_update_klaim,
									fu_ajk_cn.keterangan AS kelengkapan_dokumen,
									IF(fu_ajk_polis.`typeproduk`='NON SPK',DATE_ADD(fu_ajk_klaim.tgl_document_lengkap,INTERVAL 28 DAY),DATE_ADD(fu_ajk_klaim.tgl_document_lengkap,INTERVAL 14 DAY)) as due_date,

									CURRENT_DATE() AS today,
									IF(fu_ajk_klaim.tgl_kirim_dokumen<>'0000-00-00' OR fu_ajk_klaim.tgl_kirim_dokumen IS NOT NULL,DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_kirim_dokumen),'') AS status_release,
									/*fu_ajk_spak.ext_premi*/ '' AS EM,
									/*fu_ajk_spak.ket_ext*/ '' AS keterangan_EM,
									fu_ajk_klaim.diagnosa AS hasil_investigasi,
									fu_ajk_namapenyakit.namapenyakit AS penyebab_meinggal,
									fu_ajk_cn.policy_liability AS polis_liability,
									fu_ajk_pembayaran_status.pembayaran_status,
									fu_ajk_klaim_status.status_klaim,
									if(`id_klaim_status`=6,'Ditolak',
									if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
									'Dokumen Belum Lengkap'))  AS status_dokumen,
									'' AS keterangan_asuransi,
									fu_ajk_cn.total_bayar_asuransi,
									'' AS ref_bayar_asuransi,
									'' AS nilai_pengajuan_keuangan,
									fu_ajk_cn.total_claim  AS bayar_ke_bank,
									'' AS ref_pembayaran_ke_bank,
									fu_ajk_cn.total_bayar_asuransi-fu_ajk_cn.total_claim AS selisih,
									fu_ajk_klaim.rencana_bayar,
									fu_ajk_klaim.total_estimasi_bayar,

									DATE(fu_ajk_cn.approve_date) AS tgl_terima_laporan,
									IF(fu_ajk_klaim.tgl_lapor_klaim='0000-00-00',NULL,fu_ajk_klaim.tgl_lapor_klaim) AS tgl_lapor_asuransi,
									IF(fu_ajk_klaim.tgl_document_lengkap='0000-00-00',NULL,fu_ajk_klaim.tgl_document_lengkap) AS tgl_status_lengkap,
									IF(fu_ajk_klaim.tgl_kirim_dokumen='0000-00-00',NULL,fu_ajk_klaim.tgl_kirim_dokumen) AS tgl_kirim_dokumen,
									IF(fu_ajk_klaim.tgl_investigasi='0000-00-00',NULL,fu_ajk_klaim.tgl_investigasi) AS tgl_investigasi,
									IF(fu_ajk_klaim.tgl_app_opinimedis='0000-00-00 00:00:00','',fu_ajk_klaim.tgl_app_opinimedis) AS tgl_app_opinimedis,
									IF(fu_ajk_klaim.tgl_app_legal='0000-00-00 00:00:00','',fu_ajk_klaim.tgl_app_legal) AS tgl_app_legal,
									IF(fu_ajk_klaim.tgl_estimasi_bayar='0000-00-00','',fu_ajk_klaim.tgl_estimasi_bayar) AS tgl_estimasi_bayar,
									IF(fu_ajk_klaim.tgl_rencana_bayar='0000-00-00','',fu_ajk_klaim.tgl_rencana_bayar) AS tgl_rencana_bayar,
									IF(fu_ajk_klaim.tgl_app_klaim IS NULL,'',fu_ajk_klaim.tgl_app_klaim) AS tgl_app_klaim,
									fu_ajk_cn.tgl_bayar_asuransi,
									fu_ajk_cn.tgl_byr_claim AS tgl_bayar_ke_client,

									IF(fu_ajk_klaim.tgl_lapor_klaim='0000-00-00' OR fu_ajk_klaim.tgl_lapor_klaim IS NULL,'',CONCAT(' (',DATEDIFF(fu_ajk_klaim.tgl_lapor_klaim,fu_ajk_cn.approve_date),' Hari dari tanggal lapor klaim)')) AS tgl_lapor_asuransi1,
									IF(fu_ajk_klaim.tgl_document_lengkap IS NULL,'',CONCAT(' (',DATEDIFF(fu_ajk_klaim.tgl_document_lengkap,fu_ajk_cn.approve_date),' Hari dari tanggal lapor klaim)')) AS tgl_status_lengkap1,
									IF(fu_ajk_klaim.tgl_kirim_dokumen='0000-00-00','',CONCAT(' (',DATEDIFF(fu_ajk_klaim.tgl_kirim_dokumen,fu_ajk_cn.approve_date),' Hari dari tanggal lapor klaim)')) AS tgl_kirim_dokumen1,
									IF(fu_ajk_klaim.tgl_investigasi='0000-00-00','',IF(fu_ajk_klaim.tgl_investigasi='1900-01-00','',CONCAT(' (',DATEDIFF(fu_ajk_klaim.tgl_investigasi,fu_ajk_cn.approve_date),' Hari dari tanggal lapor klaim)'))) AS tgl_investigasi1,
									IF(fu_ajk_klaim.tgl_app_opinimedis='0000-00-00 00:00:00','',CONCAT(' (',DATEDIFF(fu_ajk_klaim.tgl_app_opinimedis,fu_ajk_cn.approve_date),' Hari dari tanggal lapor klaim)')) AS tgl_app_opinimedis1,
									IF(fu_ajk_klaim.tgl_app_legal='0000-00-00 00:00:00','',CONCAT(' (',DATEDIFF(fu_ajk_klaim.tgl_app_legal,fu_ajk_cn.approve_date),' Hari dari tanggal lapor klaim)')) AS tgl_app_legal1,
									IF(fu_ajk_klaim.tgl_estimasi_bayar='0000-00-00','',CONCAT(' (',DATEDIFF(fu_ajk_klaim.tgl_estimasi_bayar,fu_ajk_cn.approve_date),' Hari dari tanggal lapor klaim)')) AS tgl_estimasi_bayar1,
									IF(fu_ajk_klaim.tgl_rencana_bayar='0000-00-00','',CONCAT(' (',DATEDIFF(fu_ajk_klaim.tgl_rencana_bayar,fu_ajk_cn.approve_date),' Hari dari tanggal lapor klaim)')) AS tgl_rencana_bayar1,
									IF(fu_ajk_klaim.tgl_app_klaim IS NULL,'',CONCAT(' (',DATEDIFF(fu_ajk_klaim.tgl_app_klaim,fu_ajk_cn.approve_date),' Hari dari tanggal lapor klaim)')) AS tgl_app_klaim1,
									IF(fu_ajk_cn.tgl_bayar_asuransi IS NULL,'',CONCAT(' (',DATEDIFF(fu_ajk_cn.tgl_bayar_asuransi,fu_ajk_cn.approve_date),' Hari dari tanggal lapor klaim)')) AS tgl_bayar_asuransi1,
									IF(fu_ajk_cn.tgl_byr_claim='0000-00-00' OR fu_ajk_cn.tgl_byr_claim IS NULL,'',CONCAT(' (',DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_cn.approve_date),' Hari dari tanggal lapor klaim)')) AS tgl_bayar_ke_client1,

									IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',
									IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
									IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 AND DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
									IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
									,
									IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
									IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
									IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol


									FROM
									fu_ajk_cn
									INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
									INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
									INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
									INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
									LEFT JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
									LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id=fu_ajk_klaim.id_klaim_status
									LEFT JOIN fu_ajk_grupproduk ON fu_ajk_grupproduk.id=fu_ajk_peserta.nama_mitra
									/*LEFT JOIN fu_ajk_spak ON fu_ajk_peserta.spaj=fu_ajk_spak.spak*/
									LEFT JOIN fu_ajk_namapenyakit ON fu_ajk_namapenyakit.id=fu_ajk_klaim.sebab_meninggal
									LEFT JOIN fu_ajk_pembayaran_status ON fu_ajk_pembayaran_status.id=fu_ajk_cn.status_bayar
									WHERE fu_ajk_cn.type_claim='Death' AND fu_ajk_cn.del IS NULL AND `fu_ajk_cn`.`confirm_claim` <> 'Pending'
									AND fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']." ".$asuransi." ".$tipe_produk." ".$polis." ".$status_bayar." ".$status_klaim." ".$kol." ".$tgl_dol." ".$tgl_lapor." ".$liability."
									ORDER BY fu_ajk_asuransi.id,fu_ajk_polis.`typeproduk`,
									IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',
									IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
									IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 AND DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
									IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
									,
									IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
									IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
									IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))),
									fu_ajk_cn.id DESC


			 						LIMIT ". $m ." , 25");

							$sqlKlaim1 = $database->doQuery("SELECT
									CONCAT(DATE_FORMAT(fu_ajk_cn.approve_date,'%Y%m'),'/',fu_ajk_klaim.id) AS klaim_id,
									fu_ajk_cn.id_cabang,
									fu_ajk_grupproduk.nmproduk AS mitra,
									fu_ajk_asuransi.`name`,
									fu_ajk_polis.nmproduk,
									IF(fu_ajk_polis.`typeproduk`='NON SPK','Percepatan','Reguler') AS kategori,
									fu_ajk_peserta.id_peserta,
									fu_ajk_peserta.nama,
									fu_ajk_peserta.tgl_lahir,
									fu_ajk_peserta.usia,
									fu_ajk_peserta.kredit_jumlah,
									fu_ajk_cn.id as cnid,
									fu_ajk_cn.total_claim,
									fu_ajk_cn.tuntutan_klaim,
									fu_ajk_peserta.kredit_tgl,
									ROUND(IF(fu_ajk_peserta.type_data='SPK',fu_ajk_peserta.kredit_tenor*12,fu_ajk_peserta.kredit_tenor)/12) AS tenor,
									fu_ajk_klaim.tgl_klaim AS dol,
									DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl) AS akad_dol,
									DATEDIFF(fu_ajk_cn.approve_date,fu_ajk_klaim.tgl_klaim) AS lama_terima_laporan,
									current_date AS tgl_update_klaim,
									fu_ajk_cn.keterangan AS kelengkapan_dokumen,
									IF(fu_ajk_polis.`typeproduk`='NON SPK',DATE_ADD(fu_ajk_klaim.tgl_document_lengkap,INTERVAL 28 DAY),DATE_ADD(fu_ajk_klaim.tgl_document_lengkap,INTERVAL 14 DAY)) as due_date,

									CURRENT_DATE() AS today,
									IF(fu_ajk_klaim.tgl_kirim_dokumen<>'0000-00-00' OR fu_ajk_klaim.tgl_kirim_dokumen IS NOT NULL,DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_kirim_dokumen),'') AS status_release,
									/*fu_ajk_spak.ext_premi*/ '' AS EM,
									/*fu_ajk_spak.ket_ext*/ '' AS keterangan_EM,
									fu_ajk_klaim.diagnosa AS hasil_investigasi,
									fu_ajk_namapenyakit.namapenyakit AS penyebab_meinggal,
									fu_ajk_cn.policy_liability AS polis_liability,
									fu_ajk_pembayaran_status.pembayaran_status,
									fu_ajk_klaim_status.status_klaim,
									if(`id_klaim_status`=6,'Ditolak',
									if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
									'Dokumen Belum Lengkap'))  AS status_dokumen,
									'' AS keterangan_asuransi,
									fu_ajk_cn.total_bayar_asuransi,
									'' AS ref_bayar_asuransi,
									'' AS nilai_pengajuan_keuangan,
									fu_ajk_cn.total_claim  AS bayar_ke_bank,
									'' AS ref_pembayaran_ke_bank,
									fu_ajk_cn.total_bayar_asuransi-fu_ajk_cn.total_claim AS selisih,

									DATE(fu_ajk_cn.approve_date) AS tgl_terima_laporan,
									IF(fu_ajk_klaim.tgl_lapor_klaim='0000-00-00',NULL,fu_ajk_klaim.tgl_lapor_klaim) AS tgl_lapor_asuransi,
									IF(fu_ajk_klaim.tgl_document_lengkap='0000-00-00',NULL,fu_ajk_klaim.tgl_document_lengkap) AS tgl_status_lengkap,
									IF(fu_ajk_klaim.tgl_kirim_dokumen='0000-00-00',NULL,fu_ajk_klaim.tgl_kirim_dokumen) AS tgl_kirim_dokumen,
									IF(fu_ajk_klaim.tgl_investigasi='0000-00-00',NULL,fu_ajk_klaim.tgl_investigasi) AS tgl_investigasi,
									IF(fu_ajk_klaim.tgl_app_opinimedis='0000-00-00 00:00:00','',fu_ajk_klaim.tgl_app_opinimedis) AS tgl_app_opinimedis,
									IF(fu_ajk_klaim.tgl_app_legal='0000-00-00 00:00:00','',fu_ajk_klaim.tgl_app_legal) AS tgl_app_legal,
									IF(fu_ajk_klaim.tgl_estimasi_bayar='0000-00-00','',fu_ajk_klaim.tgl_estimasi_bayar) AS tgl_estimasi_bayar,
									IF(fu_ajk_klaim.tgl_rencana_bayar='0000-00-00','',fu_ajk_klaim.tgl_rencana_bayar) AS tgl_rencana_bayar,
									IF(fu_ajk_klaim.tgl_app_klaim IS NULL,'',fu_ajk_klaim.tgl_app_klaim) AS tgl_app_klaim,
									fu_ajk_cn.tgl_bayar_asuransi,
									fu_ajk_cn.tgl_byr_claim AS tgl_bayar_ke_client,

									IF(fu_ajk_klaim.tgl_lapor_klaim='0000-00-00' OR fu_ajk_klaim.tgl_lapor_klaim IS NULL,'',CONCAT(' (',DATEDIFF(fu_ajk_klaim.tgl_lapor_klaim,fu_ajk_cn.approve_date),' Hari dari tanggal lapor klaim)')) AS tgl_lapor_asuransi1,
									IF(fu_ajk_klaim.tgl_document_lengkap IS NULL,'',CONCAT(' (',DATEDIFF(fu_ajk_klaim.tgl_document_lengkap,fu_ajk_cn.approve_date),' Hari dari tanggal lapor klaim)')) AS tgl_status_lengkap1,
									IF(fu_ajk_klaim.tgl_kirim_dokumen='0000-00-00','',CONCAT(' (',DATEDIFF(fu_ajk_klaim.tgl_kirim_dokumen,fu_ajk_cn.approve_date),' Hari dari tanggal lapor klaim)')) AS tgl_kirim_dokumen1,
									IF(fu_ajk_klaim.tgl_investigasi='0000-00-00','',IF(fu_ajk_klaim.tgl_investigasi='1900-01-00','',CONCAT(' (',DATEDIFF(fu_ajk_klaim.tgl_investigasi,fu_ajk_cn.approve_date),' Hari dari tanggal lapor klaim)'))) AS tgl_investigasi1,
									IF(fu_ajk_klaim.tgl_app_opinimedis='0000-00-00 00:00:00','',CONCAT(' (',DATEDIFF(fu_ajk_klaim.tgl_app_opinimedis,fu_ajk_cn.approve_date),' Hari dari tanggal lapor klaim)')) AS tgl_app_opinimedis1,
									IF(fu_ajk_klaim.tgl_app_legal='0000-00-00 00:00:00','',CONCAT(' (',DATEDIFF(fu_ajk_klaim.tgl_app_legal,fu_ajk_cn.approve_date),' Hari dari tanggal lapor klaim)')) AS tgl_app_legal1,
									IF(fu_ajk_klaim.tgl_estimasi_bayar='0000-00-00','',CONCAT(' (',DATEDIFF(fu_ajk_klaim.tgl_estimasi_bayar,fu_ajk_cn.approve_date),' Hari dari tanggal lapor klaim)')) AS tgl_estimasi_bayar1,
									IF(fu_ajk_klaim.tgl_rencana_bayar='0000-00-00','',CONCAT(' (',DATEDIFF(fu_ajk_klaim.tgl_rencana_bayar,fu_ajk_cn.approve_date),' Hari dari tanggal lapor klaim)')) AS tgl_rencana_bayar1,
									IF(fu_ajk_klaim.tgl_app_klaim IS NULL,'',CONCAT(' (',DATEDIFF(fu_ajk_klaim.tgl_app_klaim,fu_ajk_cn.approve_date),' Hari dari tanggal lapor klaim)')) AS tgl_app_klaim1,
									IF(fu_ajk_cn.tgl_bayar_asuransi IS NULL,'',CONCAT(' (',DATEDIFF(fu_ajk_cn.tgl_bayar_asuransi,fu_ajk_cn.approve_date),' Hari dari tanggal lapor klaim)')) AS tgl_bayar_asuransi1,
									IF(fu_ajk_cn.tgl_byr_claim='0000-00-00' OR fu_ajk_cn.tgl_byr_claim IS NULL,'',CONCAT(' (',DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_cn.approve_date),' Hari dari tanggal lapor klaim)')) AS tgl_bayar_ke_client1,

									IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',
									IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
									IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 AND DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
									IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
									,
									IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
									IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
									IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol

									FROM
									fu_ajk_cn
									INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
									INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
									INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
									INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
									LEFT JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
									LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id=fu_ajk_klaim.id_klaim_status
									LEFT JOIN fu_ajk_grupproduk ON fu_ajk_grupproduk.id=fu_ajk_peserta.nama_mitra
									/*LEFT JOIN fu_ajk_spak ON fu_ajk_peserta.spaj=fu_ajk_spak.spak*/
									LEFT JOIN fu_ajk_namapenyakit ON fu_ajk_namapenyakit.id=fu_ajk_klaim.sebab_meninggal
									LEFT JOIN fu_ajk_pembayaran_status ON fu_ajk_pembayaran_status.id=fu_ajk_cn.status_bayar
									WHERE fu_ajk_cn.type_claim='Death' AND fu_ajk_cn.del IS NULL AND `fu_ajk_cn`.`confirm_claim` <> 'Pending'
									AND fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']." ".$asuransi." ".$tipe_produk." ".$polis." ".$status_bayar." ".$status_klaim." ".$kol." ".$tgl_dol." ".$tgl_lapor." ".$liability."
									ORDER BY fu_ajk_asuransi.id,fu_ajk_polis.`typeproduk`,
									IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',
									IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
									IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 AND DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
									IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
									,
									IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
									IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
									IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))),
									fu_ajk_cn.id DESC
										");

							$totalRows=mysql_num_rows($sqlKlaim1);
							$no=$m+1;
							$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;


							echo '<table border="0" width="100%" cellpadding="5" cellspacing="1" bgcolor="#bde0e6" id="myTable">
								<tr>
								<td>No</td>
								<td>Nomor Klaim</td>
								<td>Cabang</td>
								<td>Mitra</td>
								<td>Cover Asuransi</td>
								<td>Kategori</td>
								<td>Produk</td>
								<td>ID Peserta</td>
								<td>Nama Debitur</td>
								<td>Tgl Lahir</td>
								<td>Usia</td>
								<td>Plafond Kredit </td>
								<td>Tuntutan Klaim </td>
								<td>Tgl Akad</td>
								<td>J.Wkt (Th.)</td>
								<td>DOL</td>
								<td>KOL</td>
								<td>Akad s/d DOL (hari)</td>
								<td>Tgl. Terima Laporan</td>
								<td>Tgl Investigasi</td>
								<td>Terima Laporan s.d Investigasi</td>
								<td>Tgl Medical Opinion</td>
								<td>Terima Laporan s.d Medical Opinion</td>
								<td>Tgl Legal Opinion</td>
								<td>Terima Laporan s.d Legal Opinion</td>
								<td>Tgl Dokumen Lengkap</td>
								<td>Terima Laporan s.d Dokumen lengkap</td>
								<td>Tgl Lapor Klaim</td>
								<td>Terima Laporan s.d Lapor Klaim</td>
								<td>Tgl Kirim Dokumen</td>
								<td>Terima Laporan s.d Kirim Dokumen</td>
								<td>Tgl Estimasi Bayar</td>
								<td>Estimasi Bayar</td>
								<td>Terima Laporan s.d Estimasi Bayar</td>
								<td>Tgl Perintah Bayar</td>
								<td>Perintah Bayar</td>
								<td>Terima Laporan s.d Perintah Bayar</td>
								<td>Tgl Approve</td>
								<td>Terima Laporan s.d Approve Klaim</td>
								<td>Tgl Bayar Asuransi</td>
								<td>Bayar Asuransi</td>
								<td>Terima Laporan s.d Bayar Asuransi</td>
								<td>Tgl Bayar Bank</td>
								<td>Bayar Bank</td>
								<td>Terima Laporan s.d Bayar Bank</td>
								</tr>';
								while($datanya_ = mysql_fetch_array($sqlKlaim)){
									if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
									echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
									<td width="1%" align="center">'.$no.'</td>
									<td>'.$datanya_['klaim_id'].'</td>
									<td>'.$datanya_['id_cabang'].'</td>
									<td>'.$datanya_['mitra'].'</td>
									<td>'.$datanya_['name'].'</td>
									<td>'.$datanya_['kategori'].'</td>
									<td>'.$datanya_['nmproduk'].'</td>
									<td><a target="_blank" href="ajk_claim.php?d=dmonitoring&id='.$datanya_['cnid'].'">'.$datanya_['id_peserta'].'</a></td>
									<td>'.$datanya_['nama'].'</td>
									<td>'.$datanya_['tgl_lahir'].'</td>
									<td>'.$datanya_['usia'].'</td>
									<td>'.number_format($datanya_['kredit_jumlah'],2).'</td>
									<td>'.number_format($datanya_['tuntutan_klaim'],2).'</td>
									<td>'.$datanya_['kredit_tgl'].'</td>
									<td>'.$datanya_['tenor'].'</td>
									<td>'.$datanya_['dol'].'</td>
									<td>'.$datanya_['kol'].'</td>
									<td>'.$datanya_['akad_dol'].'</td>
									<td>'.$datanya_['tgl_terima_laporan'].'</td>
									<td>'.$datanya_['tgl_investigasi'].'</td>
									<td>'.$datanya_['tgl_investigasi1'].'</td>
									<td>'.$datanya_['tgl_app_opinimedis'].'</td>
									<td>'.$datanya_['tgl_app_opinimedis1'].'</td>
									<td>'.$datanya_['tgl_app_legal'].'</td>
									<td>'.$datanya_['tgl_app_legal1'].'</td>
									<td>'.$datanya_['tgl_status_lengkap'].'</td>
									<td>'.$datanya_['tgl_status_lengkap1'].'</td>
									<td>'.$datanya_['tgl_lapor_asuransi'].'</td>
									<td>'.$datanya_['tgl_lapor_asuransi1'].'</td>
									<td>'.$datanya_['tgl_kirim_dokumen'].'</td>
									<td>'.$datanya_['tgl_kirim_dokumen1'].'</td>
									<td>'.$datanya_['tgl_estimasi_bayar'].'</td>
									<td>'.duit($datanya_['total_estimasi_bayar']).'</td>
									<td>'.$datanya_['tgl_estimasi_bayar1'].'</td>
									<td>'.$datanya_['tgl_rencana_bayar'].'</td>
									<td>'.$datanya_['rencana_bayar'].'</td>
									<td>'.$datanya_['tgl_rencana_bayar1'].'</td>
									<td>'.$datanya_['tgl_app_klaim'].'</td>
									<td>'.$datanya_['tgl_app_klaim1'].'</td>
									<td>'.$datanya_['tgl_bayar_asuransi'].'</td>
									<td>'.$datanya_['total_bayar_asuransi'].'</td>
									<td>'.$datanya_['tgl_bayar_asuransi1'].'</td>
									<td>'.$datanya_['tgl_bayar_ke_client'].'</td>
									<td>'.$datanya_['bayar_ke_bank'].'</td>
									<td>'.$datanya_['tgl_bayar_ke_client1'].'</td>
									</tr>';
									$no++;
								}
								echo createPageNavigations($file = 'ajk_claim.php?re=dataklaim&d=monitoring&id_cost='.$_REQUEST['id_cost'].'&
										id_asuransi='.$_REQUEST['id_asuransi'].'&
										id_polis='.$_REQUEST['id_polis'].'&
										tipe_produk='.$_REQUEST['tipe_produk'].'&
										liability='.$_REQUEST['liability'].'&
										tglcheck1='.$_REQUEST['tglcheck1'].'&
										tglcheck2='.$_REQUEST['tglcheck2'].'&
										tglcheck3='.$_REQUEST['tglcheck3'].'&
										tglcheck4='.$_REQUEST['tglcheck4'].'&
										status_klaim='.$_REQUEST['status_klaim'].'&
										status_bayar='.$_REQUEST['status_bayar'].'&
										kol='.$_REQUEST['kol'].'&
										format_report='.$_REQUEST['format_report'].'
										', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
								echo '<b>Total Data : <u>' . $totalRows . '</u></b></td></tr>';
								echo '</table>';
							}
						}
	break;

	case "setanalisamedical":
		$query = "INSERT INTO fu_ajk_analisa_klaim
							SET id_peserta = '".$_REQUEST['id']."',
									type_analisa = 'medical',
									sts_analisa = 'Proses Analisa',
									input_by = '".$q['nm_user']."',
									input_date = now()";
		mysql_query($query);

		$querylegal = "INSERT INTO fu_ajk_analisa_klaim
							SET id_peserta = '".$_REQUEST['id']."',
									type_analisa = 'legal',
									sts_analisa = 'Proses Analisa',
									input_by = '".$q['nm_user']."',
									input_date = now()";
		mysql_query($querylegal);

		echo '<center><h2>Data Klaim meninggal telah dikirim ke AMK dan Tim Legal oleh ' . $q['nm_lengkap'] . ' pada tanggal ' . $futgl . '.</h2></center>
					<meta http-equiv="refresh" content="2;URL=ajk_claim.php?d=all_klaim">';
	break;

	case "analisamedical" :

		$queryklaim = "SELECT fu_ajk_peserta.id_peserta,
													fu_ajk_peserta.id_polis,
													fu_ajk_peserta.nama,
													fu_ajk_peserta.tgl_lahir,
													fu_ajk_peserta.usia,
													fu_ajk_peserta.kredit_jumlah,
													fu_ajk_peserta.kredit_tgl,
													fu_ajk_peserta.kredit_akhir,
													fu_ajk_peserta.kredit_tenor,
													fu_ajk_peserta.spaj,
													fu_ajk_peserta.cabang,
													fu_ajk_klaim.no_urut_klaim,
													fu_ajk_klaim.tgl_klaim,
													fu_ajk_klaim.tempat_meninggal,
													fu_ajk_klaim.diagnosa,
													fu_ajk_namapenyakit.namapenyakit,
													fu_ajk_polis.nmproduk,
													fu_ajk_peserta.id_cost,
													fu_ajk_analisa_klaim.analisa_bank,
													fu_ajk_analisa_klaim.analisa_asuransi,
													fu_ajk_analisa_klaim.ext_penyebab,
													fu_ajk_analisa_klaim.sts_analisa,
													fu_ajk_analisa_klaim.kode_diagnosa,
													fu_ajk_cn.keterangan,
													fu_ajk_klaim.sebab_meninggal
									 FROM fu_ajk_klaim
									 INNER JOIN fu_ajk_peserta ON fu_ajk_peserta.id_klaim = fu_ajk_klaim.id_cn
									 INNER JOIN fu_ajk_namapenyakit ON fu_ajk_namapenyakit.id = fu_ajk_klaim.sebab_meninggal
									 INNER JOIN fu_ajk_polis ON fu_ajk_polis.id = fu_ajk_peserta.id_polis
									 INNER JOIN fu_ajk_cn ON fu_ajk_cn.id = fu_ajk_klaim.id_cn
									 LEFT JOIN fu_ajk_analisa_klaim ON fu_ajk_analisa_klaim.id_peserta = fu_ajk_peserta.id_peserta and type_analisa = 'medical'
									 WHERE fu_ajk_klaim.del is null and
									 				fu_ajk_cn.id = '".$_REQUEST['id']."'";

		$klaim = mysql_fetch_array(mysql_query($queryklaim));

		if($klaim['kode_diagnosa'] == 1){
			$selected1 = 'selected';
		}elseif($klaim['kode_diagnosa'] == 2){
			$selected2 = 'selected';
		}elseif($klaim['kode_diagnosa'] == 3){
			$selected3 = 'selected';
		}elseif($klaim['kode_diagnosa'] == 4){
			$selected4 = 'selected';
		}elseif($klaim['kode_diagnosa'] == 5){
			$selected5 = 'selected';
		}

	  $mets = datediff($klaim['kredit_tgl'], $klaim['tgl_klaim']);
	  $usiapolis = explode(",", $mets);

		$analisabank = $klaim['analisa_bank'];
		$analisaasuransi = $klaim['analisa_asuransi'];
		$querydokklaim = "SELECT fu_ajk_dokumenklaim_bank.id,
														fu_ajk_dokumenklaim_bank.id_bank,
														fu_ajk_dokumenklaim_bank.id_dok,
														fu_ajk_dokumenklaim.nama_dok,
														fu_ajk_klaim_doc.nama_dokumen
											FROM fu_ajk_dokumenklaim_bank
											INNER JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim_bank.id_dok = fu_ajk_dokumenklaim.id
											INNER JOIN fu_ajk_klaim_doc ON fu_ajk_klaim_doc.dokumen = fu_ajk_dokumenklaim_bank.id
											WHERE id_pes = '".$klaim['id_peserta']."' and nama_dokumen is not null and fu_ajk_klaim_doc.del is null";

		$qdokklaim = mysql_query($querydokklaim);
		while ($dok_klaim = mysql_fetch_array($qdokklaim)) {
			$cekDataDok = '<a href="../ajk_file/klaim/'.$dok_klaim['nama_dokumen'].'" target="_blank">view</a>';

			if (($no % 2) == 1) $objlass = 'tbl-odd'; else $objlass = 'tbl-even';

			$listdokklaim = $listdokklaim.'<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		  <td align="center">' . ++$no . '</td>
		  <td colspan="3">' . $dok_klaim['nama_dok'] . '</td>
		  <td align="center">' . $cekDataDok . '</td>
		  </tr>';
		}

		if($klaim['sts_analisa'] == "Approve Analisa" and $q['status'] == "DOKTER"){
			$button = '<tr>
									<td colspan="5" align="center"><input type="submit" name="simpan" value="Submit" class="button"></td>
								</tr>';
		}elseif($klaim['sts_analisa'] != "Approve Analisa" and $q['status'] == "UNDERWRITING"){
			$button = '<tr>
									<td colspan="5" align="center"><input type="submit" name="simpan" value="Submit" class="button"></td>
								</tr>';
		}else{
			$button = '<tr>
									<td colspan="5" align="center"><a href="ajk_claim.php?d=investigasi_klaim" class="button">Kembali</a></td>
								</tr>';
		}


		if($_POST['simpan'] == 'Submit'){
			$analisabank = $_POST['analisa_bank'];
			$analisaasuransi = $_POST['analisa_asuransi'];
			$note = $_POST['note'];
			$diagnosa = $_POST['diagnosa'];
			$kddiagnosa = $_POST['kddiagnosa'];

			if($q['status'] == "UNDERWRITING"){
				$query = "UPDATE fu_ajk_analisa_klaim
									SET analisa_bank = '".$analisabank."',
											analisa_asuransi = '".$analisaasuransi."',
											kode_diagnosa = '".$kddiagnosa."',
											ext_penyebab = '".$diagnosa."',
											note='".$note."',
											update_by = '".$q['nm_user']."',
											update_date = now()
									WHERE id_peserta = '".$klaim['id_peserta']."' and
												type_analisa = 'medical' and 
												del is null";
			}elseif($q['status'] == "DOKTER"){
				$query = "UPDATE fu_ajk_analisa_klaim
									SET analisa_bank = '".$analisabank."',
											analisa_asuransi = '".$analisaasuransi."',
											kode_diagnosa = '".$kddiagnosa."',
											note='".$note."',
											ext_penyebab = '".$diagnosa."',
											sts_analisa = 'Finish',
											approve_by = '".$q['nm_user']."',
											approve_date = now()
									WHERE id_peserta = '".$klaim['id_peserta']."' and
												type_analisa = 'medical' and 
												del is null";
			}

			mysql_query($query);
			// echo $query;
			echo '<center><h2>Data Klaim meninggal telah diinput AMK oleh ' . $q['nm_lengkap'] . ' pada tanggal ' . $futgl . '.</h2></center>
						<meta http-equiv="refresh" content="2;URL=ajk_claim.php?d=investigasi_klaim">';;

		}

		echo '
		<table border="0" cellpadding="3" cellspacing="0" width="100%">
			<tr><th colspan="8" width="100%" align="left" colspan="2">Modul Klaim - Analisa Medical Klaim</font></th><th width="5%" colspan="2"><a href="ajk_claim.php?d=investigasi_klaim"><img src="image/back.png" width="20"></a></th></tr>
		</table>
		<br/>
		<form method="post" action="">
		  <table border="0" cellpadding="3" cellspacing="0" width="80%" align="center" style="border: solid 1px #DEDEDE" align="center">
			  <tr>
			    <th colspan="5">Informasi Klaim</th>
			  </tr>
			  <tr>
			    <td width="20%"><b>Nama Produk</b></td>
			    <td><b>: '.$klaim['nmproduk'].'</b></td>
			    <td width="20%"><b>No Formulir</b></td>
			    <td><b>: '.$klaim['spaj'].'</b></td>
			  </tr>
			  <tr>
			    <td><b>ID Peserta</b></td>
			    <td><b>: '.$klaim['id_peserta'].'</b></td>
			    <td><b>Nama Produk</b></td>
			    <td><b>: '.$klaim['nmproduk'].'</b></td>
			  </tr>
			  <tr>
			    <td><b>Nama Peserta</b></td>
			    <td><b>: '.$klaim['nama'].'</b></td>
			    <td><b>Tanggal DOL</b></td>
			    <td><b>: '.$klaim['tgl_klaim'].'</b></td>
			  </tr>
			  <tr>
			    <td><b>Tgl Lahir</b></td>
			    <td><b>: '.$klaim['tgl_lahir'].'</b></td>
			    <td><b>Usia Polis</b></td>
			    <td><b>: '.$usiapolis[0].' Tahun '.$usiapolis[1].' Bulan '.$usiapolis[2].' Hari</b></td>
			  </tr>
			  <tr>
			    <td><b>Usia</b></td>
			    <td><b>: '.$klaim['usia'].'</b></td>
			    <td><b>Cabang</b></td>
			    <td><b>: '.$klaim['cabang'].'</b></td>
			  </tr>
			  <tr>
			    <td><b>Plafond</b></td>
			    <td><b>: '.$klaim['kredit_jumlah'].'</b></td>
			    <td><b>No. Urut</b></td>
			    <td><b>: '.$klaim['no_urut'].'</b></td>

			  </tr>
			  <tr>
			    <td><b>Tgl Akad</b></td>
			    <td><b>: '.$klaim['kredit_tgl'].'</b></td>
			  </tr>
			  <tr>
			    <td><b>Tgl Akhir</b></td>
			    <td><b>: '.$klaim['kredit_akhir'].'</b></td>
			  </tr>
			  <tr>
			    <td><b>Tenor</b></td>
			    <td><b>: '.$klaim['kredit_tenor'].'</b></td>
			  </tr>
		 		<tr>
			    <td>Penyebab Meninggal </td>
			    <td colspan="4">: '.$klaim['namapenyakit'].'</td>
			  </tr>
				<tr>
			    <td>Lokasi Meninggal</td>
			    <td>: '.$klaim['tempat_meninggal'].'</td>
			  </tr>
			</table>

			<table border="0" cellpadding="3" cellspacing="0" width="80%" align="center" style="border: solid 1px #DEDEDE" align="center">
				<tr>
					<th colspan="5">Analisa Medical</th>
				</tr>
				<tr>
					<td width="20%"><b>Diagnosa</b></td>
					<td width="20%"><input type="text" id="diagnosa" name="diagnosa" value="'.$klaim['ext_penyebab'].'" style=\'width:300\' required></td>
			 	</tr>
				<tr>
					<td width="20%"><b>Kode Diagnosa</b></td>
					<td width="20%">
						<select id="kddiagnosa" name="kddiagnosa" required>
							<option value="">-Pilih-</option>
							<option value="1" '.$selected1.'>1</option>
							<option value="2" '.$selected2.'>2</option>
							<option value="3" '.$selected3.'>3</option>
							<option value="4" '.$selected4.'>4</option>
							<option value="5" '.$selected5.'>5</option>
						</select>
					</td>
			 	</tr>
			  <tr>
			    <td width="20%"><b>Analisa Medical<br>(Bank)</b></td>
			    <td><textarea cols="120" rows="5" name="analisa_bank" required>'.$analisabank.'</textarea></td>
			  </tr>
			  <tr>
			    <td width="20%"><b>Analisa Medical<br>(Asuransi)</b></td>
			    <td><textarea cols="120" rows="5" name="analisa_asuransi" required>'.$analisaasuransi.'</textarea></td>
			  </tr>
			  <tr>
			    <td width="20%"><b>Note</b></td>
			    <td><textarea cols="120" rows="5" name="note">'.$note.'</textarea></td>
			  </tr>

				<tr>
					<th colspan="5">Kelengkapan Dokumen</th>
				</tr>
				<tr>
					<th>No</th>
					<th colspan="3">Dokumen</th>
					<th>Option</th>
				</tr>
				'.$listdokklaim.'
				<tr>
					<td></td>
					<td colspan="4"><b>Keterangan Dokumen Klaim : '.$klaim['keterangan'] . '</b></td>
				</tr>
				'.$button.'
			</table>

		</form>';
	break;

	case "analisalegal" :

		$queryklaim = "SELECT fu_ajk_peserta.id_peserta,
													fu_ajk_peserta.id_polis,
													fu_ajk_peserta.nama,
													fu_ajk_peserta.tgl_lahir,
													fu_ajk_peserta.usia,
													fu_ajk_peserta.kredit_jumlah,
													fu_ajk_peserta.kredit_tgl,
													fu_ajk_peserta.kredit_akhir,
													fu_ajk_peserta.kredit_tenor,
													fu_ajk_peserta.spaj,
													fu_ajk_peserta.cabang,
													fu_ajk_klaim.no_urut_klaim,
													fu_ajk_klaim.tgl_klaim,
													fu_ajk_klaim.tempat_meninggal,
													fu_ajk_klaim.diagnosa,
													fu_ajk_namapenyakit.namapenyakit,
													fu_ajk_polis.nmproduk,
													fu_ajk_peserta.id_cost,
													fu_ajk_analisa_klaim.analisa_bank,
													fu_ajk_analisa_klaim.analisa_asuransi,
													fu_ajk_analisa_klaim.ext_penyebab,
													fu_ajk_analisa_klaim.sts_analisa,
													fu_ajk_analisa_klaim.kode_diagnosa,
													fu_ajk_analisa_klaim.note,
													fu_ajk_cn.keterangan,
													fu_ajk_klaim.sebab_meninggal,
													fu_ajk_cn.approve_date
									 FROM fu_ajk_klaim
									 INNER JOIN fu_ajk_peserta ON fu_ajk_peserta.id_klaim = fu_ajk_klaim.id_cn
									 INNER JOIN fu_ajk_namapenyakit ON fu_ajk_namapenyakit.id = fu_ajk_klaim.sebab_meninggal
									 INNER JOIN fu_ajk_polis ON fu_ajk_polis.id = fu_ajk_peserta.id_polis
									 INNER JOIN fu_ajk_cn ON fu_ajk_cn.id = fu_ajk_klaim.id_cn
									 LEFT JOIN fu_ajk_analisa_klaim ON fu_ajk_analisa_klaim.id_peserta = fu_ajk_peserta.id_peserta and type_analisa = 'legal' 
									 WHERE fu_ajk_klaim.del is null and
									 				fu_ajk_cn.id = '".$_REQUEST['id']."'";

		$klaim = mysql_fetch_array(mysql_query($queryklaim));


	  $mets = datediff($klaim['kredit_tgl'], $klaim['tgl_klaim']);
		$usiapolis = explode(",", $mets);
		$meta = datediff($klaim['tgl_klaim'], $klaim['approve_date']);
		$usialaporan = explode(",", $meta);

		$analisabank = $klaim['analisa_bank'];
		$analisaasuransi = $klaim['analisa_asuransi'];
		$note = $klaim['note'];
		$querydokklaim = "SELECT fu_ajk_dokumenklaim_bank.id,
														fu_ajk_dokumenklaim_bank.id_bank,
														fu_ajk_dokumenklaim_bank.id_dok,
														fu_ajk_dokumenklaim.nama_dok,
														fu_ajk_klaim_doc.nama_dokumen
											FROM fu_ajk_dokumenklaim_bank
											INNER JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim_bank.id_dok = fu_ajk_dokumenklaim.id
											INNER JOIN fu_ajk_klaim_doc ON fu_ajk_klaim_doc.dokumen = fu_ajk_dokumenklaim_bank.id
											WHERE id_pes = '".$klaim['id_peserta']."' and nama_dokumen is not null and fu_ajk_klaim_doc.del is null";

		$qdokklaim = mysql_query($querydokklaim);
		while ($dok_klaim = mysql_fetch_array($qdokklaim)) {
			$cekDataDok = '<a href="../ajk_file/klaim/'.$dok_klaim['nama_dokumen'].'" target="_blank">view</a>';

			if (($no % 2) == 1) $objlass = 'tbl-odd'; else $objlass = 'tbl-even';

			$listdokklaim = $listdokklaim.'<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		  <td align="center">' . ++$no . '</td>
		  <td colspan="3">' . $dok_klaim['nama_dok'] . '</td>
		  <td align="center">' . $cekDataDok . '</td>
		  </tr>';
		}


		$button = '<tr>
								<td colspan="5" align="center"><input type="submit" name="simpan" value="Submit" class="button"></td>
							</tr>';


		if($_POST['simpan'] == 'Submit'){
			$analisabank = $_POST['analisa_bank'];
			$analisaasuransi = $_POST['analisa_asuransi'];
			$note = $_POST['note'];

			$query = "UPDATE fu_ajk_analisa_klaim
								SET analisa_bank = '".$analisabank."',
										analisa_asuransi = '".$analisaasuransi."',
										note='".$note."',
										update_by = '".$q['nm_user']."',
										update_date = now()
								WHERE id_peserta = '".$klaim['id_peserta']."' and
											type_analisa = 'legal' and 
											del is null";

			mysql_query($query);
			// echo $query;
			echo '<center><h2>Data Klaim meninggal telah diinput Tim Legal oleh ' . $q['nm_lengkap'] . ' pada tanggal ' . $futgl . '.</h2></center>
						<meta http-equiv="refresh" content="2;URL=ajk_claim.php?d=investigasi_klaim">';;

		}

		echo '
		<table border="0" cellpadding="3" cellspacing="0" width="100%">
			<tr><th colspan="8" width="100%" align="left" colspan="2">Modul Klaim - Analisa Legal Klaim</font></th><th width="5%" colspan="2"><a href="ajk_claim.php?d=investigasi_klaim"><img src="image/back.png" width="20"></a></th></tr>
		</table>
		<br/>
		<form method="post" action="">
		  <table border="0" cellpadding="3" cellspacing="0" width="80%" align="center" style="border: solid 1px #DEDEDE" align="center">
			  <tr>
			    <th colspan="5">Informasi Klaim</th>
			  </tr>
			  <tr>
			    <td width="20%"><b>Nama Produk</b></td>
			    <td><b>: '.$klaim['nmproduk'].'</b></td>
			    <td width="20%"><b>No Formulir</b></td>
			    <td><b>: '.$klaim['spaj'].'</b></td>
			  </tr>
			  <tr>
			    <td><b>ID Peserta</b></td>
			    <td><b>: '.$klaim['id_peserta'].'</b></td>
			    <td><b>Nama Produk</b></td>
			    <td><b>: '.$klaim['nmproduk'].'</b></td>
			  </tr>
			  <tr>
			    <td><b>Nama Peserta</b></td>
			    <td><b>: '.$klaim['nama'].'</b></td>
			    <td><b>Tanggal DOL</b></td>
			    <td><b>: '.$klaim['tgl_klaim'].'</b></td>
			  </tr>
			  <tr>
			    <td><b>Tgl Lahir</b></td>
			    <td><b>: '.$klaim['tgl_lahir'].'</b></td>
			    <td><b>Usia Polis</b></td>
			    <td><b>: '.$usiapolis[0].' Tahun '.$usiapolis[1].' Bulan '.$usiapolis[2].' Hari</b></td>
			  </tr>
			  <tr>
			    <td><b>Usia</b></td>
			    <td><b>: '.$klaim['usia'].'</b></td>
			    <td><b>Tgl Terima Laporan</b></td>
			    <td><b>: '.$klaim['approve_date'].'</b></td>
			  </tr>
			  <tr>
			    <td><b>Plafond</b></td>
					<td><b>: '.$klaim['kredit_jumlah'].'</b></td>
			    <td><b>Usia Laporan</b></td>
			    <td><b>: '.$usialaporan[0].' Tahun '.$usialaporan[1].' Bulan '.$usialaporan[2].' Hari</b></td>
			  </tr>
			  <tr>
			    <td><b>Tgl Akad</b></td>
					<td><b>: '.$klaim['kredit_tgl'].'</b></td>
			    <td><b>Cabang</b></td>
			    <td><b>: '.$klaim['cabang'].'</b></td>			    
			  </tr>
			  <tr>
			    <td><b>Tgl Akhir</b></td>
					<td><b>: '.$klaim['kredit_akhir'].'</b></td>
					<td><b>No. Urut</b></td>
			    <td><b>: '.$klaim['no_urut'].'</b></td>					
			  </tr>
			  <tr>
			    <td><b>Tenor</b></td>
			    <td><b>: '.$klaim['kredit_tenor'].'</b></td>
			  </tr>
		 		<tr>
			    <td>Penyebab Meninggal </td>
			    <td colspan="4">: '.$klaim['namapenyakit'].'</td>
			  </tr>
				<tr>
			    <td>Lokasi Meninggal</td>
			    <td>: '.$klaim['tempat_meninggal'].'</td>
			  </tr>
			</table>

			<table border="0" cellpadding="3" cellspacing="0" width="80%" align="center" style="border: solid 1px #DEDEDE" align="center">
				<tr>
					<th colspan="5">Analisa Legal</th>
				</tr>
			  <tr>
			    <td width="20%"><b>Analisa Legal<br>(Bank)</b></td>
			    <td><textarea cols="120" rows="5" name="analisa_bank" required>'.$analisabank.'</textarea></td>
			  </tr>
			  <tr>
			    <td width="20%"><b>Analisa Legal<br>(Asuransi)</b></td>
			    <td><textarea cols="120" rows="5" name="analisa_asuransi" required>'.$analisaasuransi.'</textarea></td>
			  </tr>
			  <tr>
			    <td width="20%"><b>Note</b></td>
			    <td><textarea cols="120" rows="5" name="note">'.$note.'</textarea></td>
			  </tr>

				<tr>
					<th colspan="5">Kelengkapan Dokumen</th>
				</tr>
				<tr>
					<th>No</th>
					<th colspan="3">Dokumen</th>
					<th>Option</th>
				</tr>
				'.$listdokklaim.'
				<tr>
					<td></td>
					<td colspan="4"><b>Keterangan Dokumen Klaim : '.$klaim['keterangan'] . '</b></td>
				</tr>
				'.$button.'
			</table>

		</form>';
	break;	

	case "dmonitoring" :

			echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
			<tr><th colspan="8" width="100%" align="left" colspan="2">Modul Klaim - Monitoring Klaim</font></th><th width="5%" colspan="2"><a href="ajk_claim.php"><img src="image/back.png" width="20"></a></th></tr>
			</table><br />';

			$edklaim = mysql_fetch_array($database->doQuery('SELECT
					fu_ajk_asuransi.`name` AS asuransi,
					fu_ajk_cn.id,
					fu_ajk_cn.id_cost,
					fu_ajk_cn.id_cn,
					fu_ajk_cn.id_dn,
					fu_ajk_dn.dn_kode,
					fu_ajk_peserta.id_polis,
					fu_ajk_peserta.id_peserta,
					fu_ajk_peserta.nama,
					fu_ajk_peserta.tgl_lahir,
					fu_ajk_peserta.usia,
					fu_ajk_peserta.kredit_tgl,
					IF(fu_ajk_peserta.type_data="SPK", fu_ajk_peserta.kredit_tenor * 12, fu_ajk_peserta.kredit_tenor) AS tenor,
					fu_ajk_peserta.kredit_akhir,
					fu_ajk_peserta.kredit_jumlah,
					fu_ajk_cn.tgl_claim,
					fu_ajk_cn.premi,
					fu_ajk_cn.confirm_claim,
					fu_ajk_cn.total_claim,
					fu_ajk_cn.tuntutan_klaim,
					IF(fu_ajk_cn.tgl_byr_claim="0000-00-00" OR fu_ajk_cn.tgl_byr_claim IS NULL,"",CONCAT(fu_ajk_cn.tgl_byr_claim," (",DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_cn.approve_date)," Hari dari tanggal lapor klaim)")) AS tgl_byr_claim,
					fu_ajk_cn.nmpenyakit,
					fu_ajk_cn.keterangan,
					fu_ajk_cn.id_cabang,
					fu_ajk_klaim.id_klaim_status,
					fu_ajk_cn.id_regional,
					fu_ajk_polis.nmproduk,
					fu_ajk_klaim.id_klaim_status,
					fu_ajk_klaim.tgl_klaim AS tglklaim,
					fu_ajk_klaim.tgl_document AS tglklaimdoc,
					IF(fu_ajk_klaim.tgl_document_lengkap IS NULL,"",CONCAT(fu_ajk_klaim.tgl_document_lengkap," (",DATEDIFF(fu_ajk_klaim.tgl_document_lengkap,fu_ajk_cn.approve_date)," Hari dari tanggal lapor klaim)")) AS tglklaimdoc2,
					IF(fu_ajk_klaim.tgl_investigasi="0000-00-00","",IF(fu_ajk_klaim.tgl_investigasi="1900-01-00","",CONCAT(fu_ajk_klaim.tgl_investigasi," (",DATEDIFF(fu_ajk_klaim.tgl_investigasi,fu_ajk_cn.approve_date)," Hari dari tanggal lapor klaim)"))) AS tglinvestigasi,
					IF(fu_ajk_klaim.tgl_lapor_klaim IS NULL,"",CONCAT(fu_ajk_klaim.tgl_lapor_klaim," (",DATEDIFF(fu_ajk_klaim.tgl_lapor_klaim,fu_ajk_cn.approve_date)," Hari dari tanggal lapor klaim)")) AS tgllaporklaim,
					fu_ajk_klaim.jumlah AS totalklaim,
					fu_ajk_klaim.tempat_meninggal AS tempat_meninggal,
					fu_ajk_klaim.diagnosa AS diagnosa,
					fu_ajk_klaim.sebab_meninggal AS sebab_meninggal,
					fu_ajk_klaim.ket AS ketklaim,
					fu_ajk_klaim.sumber_dana,
					fu_ajk_klaim.ket_dokter AS ketDokter,
					IF(fu_ajk_klaim.tgl_kirim_dokumen="0000-00-00","",CONCAT(fu_ajk_klaim.tgl_kirim_dokumen," (",DATEDIFF(fu_ajk_klaim.tgl_kirim_dokumen,fu_ajk_cn.approve_date)," Hari dari tanggal lapor klaim)")) AS tglkirimdoc,
					fu_ajk_namapenyakit.id AS idpenyakit,
					fu_ajk_namapenyakit.namapenyakit,
					IF(fu_ajk_klaim.tgl_app_opinimedis="0000-00-00 00:00:00","",CONCAT(fu_ajk_klaim.tgl_app_opinimedis," (",DATEDIFF(fu_ajk_klaim.tgl_app_opinimedis,fu_ajk_cn.approve_date)," Hari dari tanggal lapor klaim)")) AS tgl_app_opinimedis,
					IF(fu_ajk_klaim.tgl_app_legal="0000-00-00 00:00:00","",CONCAT(fu_ajk_klaim.tgl_app_legal," (",DATEDIFF(fu_ajk_klaim.tgl_app_legal,fu_ajk_cn.approve_date)," Hari dari tanggal lapor klaim)")) AS tgl_app_legal,
					IF(fu_ajk_klaim.tgl_estimasi_bayar="0000-00-00","",CONCAT(fu_ajk_klaim.tgl_estimasi_bayar," (",DATEDIFF(fu_ajk_klaim.tgl_estimasi_bayar,fu_ajk_cn.approve_date)," Hari dari tanggal lapor klaim)")) AS tgl_estimasi_bayar,
					fu_ajk_klaim.total_estimasi_bayar,
					IF(fu_ajk_klaim.tgl_rencana_bayar="0000-00-00","",CONCAT(fu_ajk_klaim.tgl_rencana_bayar," (",DATEDIFF(fu_ajk_klaim.tgl_rencana_bayar,fu_ajk_cn.approve_date)," Hari dari tanggal lapor klaim)")) AS tgl_rencana_bayar,
					fu_ajk_klaim.rencana_bayar,
					fu_ajk_cn.approve_date,
					IF(fu_ajk_klaim.tgl_app_klaim IS NULL,"",CONCAT(fu_ajk_klaim.tgl_app_klaim," (",DATEDIFF(fu_ajk_klaim.tgl_app_klaim,fu_ajk_cn.approve_date)," Hari dari tanggal lapor klaim)")) AS tgl_app_klaim,
					IF(fu_ajk_cn.tgl_bayar_asuransi IS NULL,"",CONCAT(fu_ajk_cn.tgl_bayar_asuransi," (",DATEDIFF(fu_ajk_cn.tgl_bayar_asuransi,fu_ajk_cn.approve_date)," Hari dari tanggal lapor klaim)")) AS tgl_bayar_asuransi,
					IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL OR fu_ajk_cn.tgl_byr_claim="0000-00-00",
					IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,"2",
					IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 AND DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,"3",
					IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,"4","5")))
					,
					IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,"2",
					IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,"3",
					IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,"4","5")))) AS kol,
					fu_ajk_cn.total_bayar_asuransi,
					fu_ajk_cn.policy_liability
					FROM fu_ajk_cn
					LEFT JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
					LEFT JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
					LEFT JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
					LEFT JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
					LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta AND fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost
					LEFT JOIN fu_ajk_namapenyakit ON fu_ajk_cn.nmpenyakit = fu_ajk_namapenyakit.id
					WHERE fu_ajk_cn.id = "' . $_REQUEST['id'] . '" AND fu_ajk_cn.del IS NULL'));
			$adcostumer = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="' . $edklaim['id_cost'] . '"'));

			$mets = datediff($edklaim['kredit_tgl'], $edklaim['tgl_claim']);
			$metTgl = explode(",", $mets);
			//echo $metTgl[0].'-'.$metTgl[1].'-'.$metTgl[2].'<br />';
			if ($metTgl[2] > 0) {
				$jumBulan = $metTgl[1] + 1;
			} else {
				$jumBulan = $metTgl[1];
			}    //AKUMULASI BULAN THD JUMLAH HARI
			$maj = ($metTgl[0]*12) + $jumBulan;

			//$met = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_rateklaimdie WHERE masa_asuransi >= "'.$edklaim['tenor'].'" AND bulan_ke="'.$maj.'" AND id_cost="'.$edklaim['id_cost'].'"'));
			//if ($edklaim['idpolis']=="11") {	$jum = $edklaim['up'];	}
			//else	{	$jum = $met['rate'] / 1000 * $edklaim['up'];	}


			echo '<form method="post" action="">
				  <table border="0" cellpadding="3" cellspacing="0" width="80%" align="center" style="border: solid 1px #DEDEDE" align="center">
				  <tr>
				    <th colspan="5">Informasi Klaim</th>
				  </tr>
				<tr>
			    <td><b>Nama Perusahaan</b></td>
			    <td><b>: '.$adcostumer['name'].'</b></td>
			    <td></td>
			    <td></td>
			    <td></td>
			  </tr>
				<tr>
			    <td><b>Nama Asuransi</b></td>
			    <td><b>: '.$edklaim['asuransi'].'</b></td>
			    <td></td>
			    <td></td>
			    <td></td>
			  </tr>
			  <tr>
			    <td><b>Nama Produk</b></td>
			    <td><b>: '.$edklaim['nmproduk'].'</b></td>
			    <td></td>
			    <td></td>
			    <td></td>
			  </tr>
			  <tr>
			    <td><b>ID Peserta</b></td>
			    <td><b>: '.$edklaim['id_peserta'].'</b></td>
			    <td></td>
			    <td></td>
			    <td></td>
			  </tr>
			  <tr>
			    <td><b>Nama Peserta</b></td>
			    <td><b>: '.$edklaim['nama'].'</b></td>
			    <td></td>
			    <td></td>
			    <td></td>
			  </tr>
			  <tr>
			    <td><b>Cabang</b></td>
			    <td><b>: '.$edklaim['id_cabang'].'</b></td>
			    <td></td>
			    <td></td>
			    <td></td>
			  </tr>
			  <tr>
			    <td><b>Regional</b></td>
			    <td><b>: '.$edklaim['id_regional'].'</b></td>
			    <td></td>
			    <td></td>
			    <td></td>
			  </tr>
			  <tr>
			    <td><b>Tanggal Asuransi</b></td>
			    <td><b>: '.$edklaim['kredit_tgl'].' s.d '.$edklaim['kredit_akhir'].'</b></td>
			    <td></td>
			    <td>Tenor</td>
			    <td><b>: '.$edklaim['tenor'].' Bulan</b></td>
			  </tr>
			  <tr>
			    <td><b>Plafond</b></td>
			    <td><b>: '.duit($edklaim['kredit_jumlah']).'</b></td>
			    <td></td>
			    <td></td>
			    <td></td>
			  </tr>
			  <tr>
			    <td>Tanggal Meninggal</td>
			    <td><b>: '.$edklaim['tgl_claim'].'</b></td>
			    <td></td>
			    <td><b>Masa Asuransi Berjalan</b></td>
			    <td><b>: '.$maj.' Bulan</b></td>
			  </tr>
			  <tr>
			    <td><b>Tuntutan Klaim</b></td>
			    <td><b>: '.duit($edklaim['tuntutan_klaim']).'</b></td>
			    <td></td>
			    <td></td>
			    <td></td>
			  </tr>
			  <tr>
			    <td><b>Status Klaim</b></td>';
				$nmPenyakit = $database->doQuery('SELECT * FROM fu_ajk_klaim_status where view_list=0 ORDER BY order_list ASC');
				while ($nmPenyakit_ = mysql_fetch_array($nmPenyakit)) {
				if(_selected($edklaim['id_klaim_status'], $nmPenyakit_['id'])=='selected')
					$liability=$nmPenyakit_['status_klaim'];
				}

			    echo '<td><b>: '.$liability.'</b></td>
			    <td></td>
			    <td><b>Status Liability</b></td>
			    <td><b>: '.$edklaim['policy_liability'].'</b></td>
			  </tr>
			  <tr>
			    <td><b>Tanggal Terima Laporan</b></td>
			    <td><b>: '.$edklaim['approve_date'].'</b></td>
			    <td></td>
			    <td><b>KOL</b></td>
			    <td><b>: '.$edklaim['kol'].'</b></td>
			  </tr>
			  <tr>
			    <td><b>Tanggal Investigasi</b></td>
			    <td><b>: '.$edklaim['tglinvestigasi'].'</b></td>
			    <td></td>
			    <td><b>App. Medical Opinion</b></td>
			    <td><b>: '.$edklaim['tgl_app_opinimedis'].'</b></td>
			  </tr>
			  <tr>
			    <td><b>App. Legal Opinion</b></td>
			    <td><b>: '.$edklaim['tgl_app_legal'].'</b></td>
			    <td></td>
			    <td></td>
			    <td></td>
			  </tr>
			  <tr>
			    <td><b>Tgl Dokumen Lengkap</b></td>
			    <td><b>: '.$edklaim['tglklaimdoc2'].'</b></td>
			    <td></td>
			    <td></td>
			    <td></td>
			  </tr>
			  <tr>
			    <td><b>Tanggal Lapor Klaim</b></td>
			    <td><b>: '.$edklaim['tgllaporklaim'].'</b></td>
			    <td></td>
			    <td><b>Tanggal Kirim Dokumen</b></td>
			    <td><b>: '.$edklaim['tglkirimdoc'].'</b></td>
			  </tr>
			  <tr>
			    <td><b>Tanggal Estimasi Bayar Asuransi</b></td>
			    <td><b>: '.$edklaim['tgl_estimasi_bayar'].'</b></td>
			    <td></td>
			    <td><b>Estimasi Bayar Asuransi</b></td>
			    <td><b>: '.duit($edklaim['total_estimasi_bayar']).'</b></td>
			  </tr>
			  <tr>
			    <td><b>Tanggal Approve Klaim</b></td>
			    <td>: '.$edklaim['tgl_app_klaim'].'</td>
			    <td></td>
			    <td><b>Tanggal Perintah Bayar Ke Bank</b></td>
			    <td><b>: '.$edklaim['tgl_rencana_bayar'].'</b></td>
			  </tr>
			  <tr>
			    <td><b>Tanggal Bayar Asuransi</b></td>
			    <td><b>: '.$edklaim['tgl_bayar_asuransi'].'</b></td>
			    <td></td>
			    <td><b>Jumlah Bayar Asuransi</b></td>
			    <td><b>: '.duit($edklaim['total_bayar_asuransi']).'</b></td>
			  </tr>
			  <tr>
			    <td><b>Tanggal Bayar Bank</b></td>
			    <td><b>: '.$edklaim['tgl_byr_claim'].'</b></td>
			    <td></td>
			    <td><b>Jumlah Bayar Bank</b></td>
			    <td><b>: '.duit($edklaim['total_claim']).'</b></td>
			  </tr>
				<table border="0" cellpadding="3" cellspacing="0" width="80%" align="center" style="border: solid 1px #DEDEDE" align="center">
				<tr>
				<th colspan="5">Medical &amp; Legal Opinion</th>
				</tr>
			  <tr>
			    <td>Lokasi Meninggal</td>
			    <td>: '.$edklaim['tempat_meninggal'].'</td>
			    <td></td>
			    <td></td>
			    <td></td>
			  </tr>';
					$penyebab_meninggal=='';
				$nmPenyakit = $database->doQuery('SELECT * FROM fu_ajk_namapenyakit ORDER BY id ASC');
				while ($nmPenyakit_ = mysql_fetch_array($nmPenyakit)) {
					if(_selected($edklaim['nmpenyakit'], $nmPenyakit_['id'])=='selected'){
						$penyebab_meninggal=$nmPenyakit_['namapenyakit'];
					}
				}
			  echo '<tr>
			    <td>Penyebab Meninggal </td>
			    <td colspan="4">: '.$penyebab_meninggal.'</td>

			  </tr>
				<tr>
				<td valign="top">Kategori Klaim</td>
				<td colspan="4">: '.$edklaim['kategori_klaim'].'</td>

			</tr>';

				if($edklaim['tempat_meninggal']=='Rumah'){
					echo '
					<tr>
					<td>Kronologi</td>
					<td colspan="4">: '.$edklaim['kronologi'].'</td>
					</tr>
					<tr>
					<td>Data Form AAJI</td>
					<td colspan="4">: '.$edklaim['form_aaji'].'</td>

					</tr>
					<tr>
					<td>Hasil Autopsi Verbal</td>
					<td colspan="4">: '.$edklaim['konfirm_ahliwaris'].'</td>

					</tr>
					<tr>
					<td>Extra Mortality</td>
					<td colspan="4">:'.$edklaim['extra_mortality'].'</td>

					</tr>';
				}else{
					echo '
					<tr>
					<td>Anamnesis</td>
					<td colspan="4">: '.$edklaim['anamnesis'].'</td>

					</tr>
					<tr>
					<td>Pemeriksaan Fisik</td>
					<td colspan="4">: '.$edklaim['pemeriksaan_fisik'].'</td>

					</tr>
					<tr>
					<td>Pemeriksaan Penunjang</td>
					<td colspan="4">: '.$edklaim['pemeriksaan_penunjang'].'</td>

					</tr>
					<tr><td>Keterangan Diagnosa</td>
					<td colspan="4">: '.$edklaim['diagnosa']. '</td>

					</tr>
					<tr>Terapi</td>
					<td colspan="4">: '.$edklaim['terapi']. '</td>

					</tr>
					<tr>
					<td>Hasil Autopsi Verbal</td>
					<td colspan="4">: '.$edklaim['konfirm_ahliwaris'].'</td>

					</tr>
					<tr>
					<td>Extra Mortality</td>
					<td colspan="4">: '.$edklaim['extra_mortality'].'</td>

					</tr>';
				}
			echo '<tr>
					<td>Preexisting Condition</td>
					<td colspan="4">: '.$edklaim['preexisting_cond'].'</td>

				</tr>
				<tr>
					<td>Legal Opinion</td>
					<td colspan="4">: '.$edklaim['legal_note'].'</td>

				</tr>
				<tr><th colspan="5">Kelengkapan Dokumen</th></tr>
				<tr><th>No</th><th colspan="3">Dokumen</th><th>Option</th></tr>';
						$met_dok = $database->doQuery('SELECT
							fu_ajk_dokumenklaim_bank.id,
							fu_ajk_dokumenklaim_bank.id_bank,
							fu_ajk_dokumenklaim_bank.id_dok,
							fu_ajk_dokumenklaim.nama_dok
							FROM
							fu_ajk_dokumenklaim_bank
							INNER JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim_bank.id_dok = fu_ajk_dokumenklaim.id
							WHERE id_bank="' . $edklaim['id_cost'] . '" AND id_produk="' . $edklaim['id_polis'] . '" ORDER BY nama_dok ASC');
						while ($met_dok_ = mysql_fetch_array($met_dok)) {
							$cekDokumenKlaim = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_klaim_doc WHERE id_pes="' . $edklaim['id_peserta'] . '" AND id_cost="' . $edklaim['id_cost'] . '" AND dokumen="' . $met_dok_['id'] . '" AND del IS NULL'));
							if ($cekDokumenKlaim) {
								if(!empty($cekDokumenKlaim['nama_dokumen'])){
									$cekDataDok = '<a href="../ajk_file/klaim/'.$cekDokumenKlaim['nama_dokumen'].'" target="_blank">view</a>';
								}else{
									$cekDataDok = '';
								}
							} else {
								$cekDataDok = '';
							}
							if (($no % 2) == 1) $objlass = 'tbl-odd'; else $objlass = 'tbl-even';
							echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
					  <td align="center">' . ++$no . '</td>
					  <td colspan="3">' . $met_dok_['nama_dok'] . '</td>
					  <td align="center">' . $cekDataDok . '</td>
					  </tr>';
						}
						echo '</td></tr>';
						echo '<tr><td></td><td colspan="4"><b>Keterangan Dokumen Klaim : '.$edklaim['keterangan'] . '</b></td></tr>
				</table></form>';
	break;

	case "kadaluarsa" :

				$metcost = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name DESC');

				echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
					<tr><th colspan="8" width="100%" align="left" colspan="2">Modul Klaim - Meninggal (120 s.d 150 Hari dari tanggal lapor)</font></th><th width="5%" colspan="2"></th></tr>
					</table><br />';
				echo '<fieldset>
				<legend>Searching</legend>
				<form method="post" action="">
				<table border="0" width="60%" cellpadding="1" cellspacing="0" align="center">

			  	<tr><td width="10%" align="center">NAMA PERUSAHAAN</td>
					<td width="10%" align="center">REGIONAL</td>
					<td width="10%" align="center">CABANG</td>
					<td width="10%" align="center">ID PESERTA</td>
					<td width="10%" align="center">NAMA</td>
					<td width="10%" align="center">DEBIT NOTE</td>
					<td width="10%" align="center">CREDIT NOTE</td>
				</tr>
				<tr>
					<td align="center"><select name="id_cost" id="id_cost">
				  	<option value="">---Pilih Perusahaan---</option>';
					while($metcost_ = mysql_fetch_array($metcost)) {
						echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['id_cost'], $metcost_['id']).'>'.$metcost_['name'].'</option>';
					}
					echo '</select></td>
					<td align="center"><select name="id_reg" id="id_reg">
					<option value="">-- Pilih Regional --</option>
					</select></td>
					<td align="center"><select name="id_cab" id="id_cab">
					<option value="">-- Pilih Cabang --</option>
					</select></td>
					<td align="center"><input type="text" name="ridp" value="'.$_REQUEST['ridp'].'"></td>
					<td align="center"><input type="text" name="rnama" value="'.$_REQUEST['rnama'].'"></td>
					<td align="center"><input type="text" name="nodn" value="'.$_REQUEST['nodn'].'"></td>
				  	<td align="center"><input type="text" name="nocn" value="'.$_REQUEST['nocn'].'"></td>
				  	</tr>
			  	<tr><td colspan="6" align="center"><input type="submit" name="oppe" value="Searching" class="button"></td></tr>
				</table></form></fieldset>';
				//}
				echo '<table border="0" width="100%" cellpadding="5" cellspacing="1" bgcolor="#bde0e6">
				<tr><td bgcolor="#FFF"colspan="4"><a href="ajk_claim.php?d=kadaluarsa_list">List Klaim Kadaluarsa</a></td><td bgcolor="#FFF"colspan="20"><a href="e_report.php?er=kadaluarsa">Download Pra Kadaluarsa</a></td></tr>
				<tr><th width="3%">No</td>
				<th>Input Date</td>
				<th>No. Urut</td>
				<th>Asuransi</td>
				<th width="5%">ID Peserta</td>
				<th width="1%">ID DN</td>
				<th width="1%">Produk</td>
				<th>Nama Debitur</td>
				<th>Cabang</td>
				<th width="5%">Kredit Awal</td>
				<th width="5%">Kredit Akhir</td>
				<th width="5%">Tgl Klaim</td>
				<th width="1%">Tgl Approve</td>
				<th width="1%">Kadaluarsa (Hari)</td>
				<th width="1%">Tenor</td>
				<!-- <th width="1%">Tenor (S,B)</td> --!>
				<th width="1%">Tuntutan Klaim</td>
				<th width="1%">Status</td>
				<th width="1%">Tgl Bayar Klaim</td>
				<th width="1%">jDoc</td>
				<th width="1%">Reminder</td>
				</tr>';
				if ($_REQUEST['ridp'])		{
					$empat = 'AND fu_ajk_peserta.id_peserta LIKE "%' . $_REQUEST['ridp'] . '%"';
				}

				if ($_REQUEST['nodn'])		{
					$satu = 'AND fu_ajk_dn.dn_kode LIKE "%' . $_REQUEST['nodn'] . '%"';
				}

				if ($_REQUEST['nocn'])		{
					$dua = 'AND fu_ajk_cn.id_cn LIKE "%' . $_REQUEST['nocn'] . '%"';
				}

				if ($_REQUEST['rnama'])		{
					$tiga = 'AND fu_ajk_peserta.nama LIKE "%'.$_REQUEST['rnama'].'%"';
				}

				if ($_POST['id_cost'])	{
					$lima = 'AND fu_ajk_peserta.id_cost = "'.$_REQUEST['id_cost'].'"';
				}

				if (isset($_POST['id_reg']) && $_POST['id_reg']!=="") {
					$metreg1__ = mysql_query('SELECT * FROM fu_ajk_regional where id="'.$_POST['id_reg'].'"');
					$metreg__ = mysql_fetch_array($metreg1__);
					$enam = 'AND fu_ajk_peserta.regional="'.$metreg__['name'].'"';
				}

				if (isset($_POST['id_cab']) && $_POST['id_cab']!=="") {
					$metcab1__ = mysql_query('SELECT * FROM fu_ajk_cabang where id="'.$_POST['id_cab'].'"');
					$metcab__ = mysql_fetch_array($metcab1__);
					$tujuh= 'AND fu_ajk_peserta.cabang="'.$metcab__['name'].'"';
				}

				$namanya = mysql_fetch_array($database->doQuery('SELECT id_klaim, status_peserta, id_peserta FROM fu_ajk_peserta WHERE id_klaim !="" AND status_peserta="Death" '.$tiga.' '));
				$ernama = 'AND fu_ajk_peserta.id_peserta = "' . $namanya['id_peserta'] . '"';


				if ($_REQUEST['rdob'])		{	$empat = 'AND tgl_lahir = "' . $_REQUEST['rdob'] . '"';
					$dobnya = mysql_fetch_array($database->doQuery('SELECT id_klaim, status_peserta, id_peserta FROM fu_ajk_peserta WHERE id_klaim !="" AND status_peserta="Death" '.$empat.' '));
					$erdob = 'AND fu_ajk_peserta.tgl_lahir = "' . $dobnya['tgl_lahir'] . '"';
				}

				if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}

				//$metklaim = $database->doQuery('SELECT * FROM fu_ajk_cn WHERE type_claim = "Death" '.$satu.' '.$dua.' '.$ernama.' '.$erdob.' AND del IS NULL ORDER BY id DESC LIMIT ' . $m . ' , 25');
				$metklaim = $database->doQuery('SELECT
					fu_ajk_asuransi.`name` AS asuransi,
					fu_ajk_cn.id,
					fu_ajk_klaim.no_urut_klaim,
					fu_ajk_cn.id_cost,
					fu_ajk_cn.id_cn,
					fu_ajk_dn.dn_kode,
					fu_ajk_peserta.id_peserta,
					fu_ajk_peserta.nama,
					fu_ajk_peserta.tgl_lahir,
					fu_ajk_peserta.usia,
					fu_ajk_peserta.kredit_tgl,
					IF(fu_ajk_peserta.type_data="SPK", fu_ajk_peserta.kredit_tenor * 12, fu_ajk_peserta.kredit_tenor) AS tenor,
					fu_ajk_peserta.kredit_akhir,
					fu_ajk_peserta.kredit_jumlah,
					fu_ajk_cn.tgl_claim,
					fu_ajk_cn.premi,
					fu_ajk_cn.confirm_claim,
					fu_ajk_cn.total_claim,
					fu_ajk_cn.tuntutan_klaim,
					fu_ajk_cn.tgl_byr_claim,
					fu_ajk_polis.nmproduk,
					fu_ajk_peserta.cabang,
					fu_ajk_klaim.tgl_kirim_dokumen,
					fu_ajk_cn.tgl_bayar_asuransi,
					fu_ajk_cn.input_date,
					fu_ajk_cn.approve_date,
					fu_ajk_klaim.no_surat_reminder1,
					fu_ajk_klaim.no_surat_reminder2,
					fu_ajk_klaim.no_surat_reminder3,
					fu_ajk_klaim.tgl_surat_reminder1,
					fu_ajk_klaim.tgl_surat_reminder2,
					fu_ajk_klaim.tgl_surat_reminder3,
					(150)-if(DATEDIFF(fu_ajk_cn.tgl_claim,fu_ajk_klaim.tgl_document_lengkap) is null,
					DATEDIFF(current_date(),fu_ajk_cn.tgl_claim),
					DATEDIFF(fu_ajk_klaim.tgl_document_lengkap,fu_ajk_cn.tgl_claim)) as kadaluarsa1,
					if(DATEDIFF(fu_ajk_cn.tgl_claim,fu_ajk_klaim.tgl_document_lengkap) is null,
					DATEDIFF(current_date(),fu_ajk_cn.tgl_claim),
					DATEDIFF(fu_ajk_klaim.tgl_document_lengkap,fu_ajk_cn.tgl_claim)) as kadaluarsa_hari,
					fu_ajk_klaim_status.status_klaim
					FROM
					fu_ajk_cn
					INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
					INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
					INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
					INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta AND fu_ajk_cn.id = fu_ajk_peserta.id_klaim
					LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
					LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim.id_klaim_status = fu_ajk_klaim_status.id
					WHERE
					fu_ajk_cn.type_claim = "Death" AND fu_ajk_cn.confirm_claim <> "Pending" AND fu_ajk_cn.del IS NULL
					and
					(if(DATEDIFF(fu_ajk_cn.tgl_claim,fu_ajk_klaim.tgl_document_lengkap) is null,
					DATEDIFF(current_date(),fu_ajk_cn.tgl_claim),160)	 between 120 and 150 )
					and if(fu_ajk_cn.tgl_bayar_asuransi is null or fu_ajk_cn.tgl_bayar_asuransi="0000-00-00","UNPAID" ,"PAID")="UNPAID"

					'.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.'  '.$tujuh.'
					ORDER BY if(DATEDIFF(fu_ajk_cn.tgl_claim,fu_ajk_klaim.tgl_document_lengkap) is null,
					DATEDIFF(current_date(),fu_ajk_cn.tgl_claim),
					DATEDIFF(fu_ajk_klaim.tgl_document_lengkap,fu_ajk_cn.tgl_claim)) DESC LIMIT ' . $m . ' , 25');
				//$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_cn WHERE id != "" AND type_claim="Death" '.$satu.' '.$dua.' '.$ernama.' '.$erdob.' AND del IS NULL'));
				$totalRows = mysql_num_rows($database->doQuery('SELECT fu_ajk_cn.id_cn
					FROM
					fu_ajk_cn
					INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
					INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
					INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
					INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta AND fu_ajk_cn.id = fu_ajk_peserta.id_klaim
					LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
					LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim.id_klaim_status = fu_ajk_klaim_status.id
					WHERE
					fu_ajk_cn.type_claim = "Death" AND fu_ajk_cn.confirm_claim <> "Pending" AND fu_ajk_cn.del IS NULL
					and
					(if(DATEDIFF(fu_ajk_cn.tgl_claim,fu_ajk_klaim.tgl_document_lengkap) is null,
					DATEDIFF(current_date(),fu_ajk_cn.tgl_claim),160)	 between 120 and 150 )
					and if(fu_ajk_cn.tgl_bayar_asuransi is null or fu_ajk_cn.tgl_bayar_asuransi="0000-00-00","UNPAID" ,"PAID")="UNPAID"

					'.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.'  '.$tujuh.'
					ORDER BY if(DATEDIFF(fu_ajk_cn.tgl_claim,fu_ajk_klaim.tgl_document_lengkap) is null,
					DATEDIFF(current_date(),fu_ajk_cn.tgl_claim),
					DATEDIFF(fu_ajk_klaim.tgl_document_lengkap,fu_ajk_cn.tgl_claim))'));
					//$totalRows = $totalRows[0];
					$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
					while ($rklaim = mysql_fetch_array($metklaim)) {
					$jdoc = mysql_num_rows($database->doQuery('
						SELECT DISTINCT fu_ajk_dokumenklaim_bank.id_bank, fu_ajk_dokumenklaim_bank.id_dok, fu_ajk_dokumenklaim.nama_dok
						FROM fu_ajk_dokumenklaim_bank
						INNER JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim_bank.id_dok = fu_ajk_dokumenklaim.id
						INNER JOIN fu_ajk_klaim_doc ON fu_ajk_klaim_doc.dokumen = fu_ajk_dokumenklaim_bank.id
						where fu_ajk_klaim_doc.id_pes="'.$rklaim['id_peserta'].'" AND fu_ajk_klaim_doc.id_cost="'.$rklaim['id_cost'].'"
						'));

					$mets = datediff($rklaim['kredit_tgl'], $rklaim['tgl_claim']);
					$metTgl = explode(",",$mets);
					//echo $metTgl[0].'-'.$metTgl[1].'-'.$metTgl[2].'<br />';
					if ($metTgl[2] > 0) {	$jumBulan = $metTgl[1] + 1;	}else{	$jumBulan = $metTgl[1];	}	//AKUMULASI BULAN THD JUMLAH HARI
					$klaimBlnJ = $metTgl[0] + $jumBulan;
					$klaimBlnS = $rklaim['tenor'] - $klaimBlnJ;
					//SETTING TGL BAYAR KLAIM//
					if ($rklaim['tgl_byr_claim']=="") {	$metbyrklaim = 'Belum dibayar';	}else{	$metbyrklaim = '<font color="blue">'._convertDate($rklaim['tgl_byr_claim']).'</font>';	}
					//SETTING TGL BAYAR KLAIM//

					//SETTING PEMBERITAHUAN INVESTIGASI
					if ($rklaim['confirm_claim']=="Investigasi") {
						$setInvestigasi ='<a title="Edit data ahli waris" href="ajk_claim.php?d=eInvKlaim&ido='.$rklaim['id'].'"><img src="image/edit.png" width="30"></a>';
					}else{
						$setInvestigasi ='<a title="Confirm Investigasi Klaim" href="ajk_claim.php?d=InvKlaim&id='.$rklaim['id'].'"><img src="image/doc_death.png" width="30"></a>';
					}
					$surat_pem='';
					if(!is_null($rklaim['tgl_kirim_dokumen']) && $rklaim['tgl_kirim_dokumen']!='0000-00-00' && datediff($rklaim['tgl_kirim_dokumen'], $futoday)>=7){
						$surat_pem='<a title="Print Surat Persetujuan Ke asuransi" target="_blank" href="e_report_klaim.php?er=setuju_as&id='.$rklaim['id'].'"><img src="image/print.png" width="30"></a>';
					}

					if(!is_null($rklaim['tgl_kirim_dokumen']) && $rklaim['tgl_kirim_dokumen']!='0000-00-00' && datediff($rklaim['tgl_kirim_dokumen'], $futoday)>=14){
						$surat_pem='<a title="Print Surat Realisasi Ke asuransi '.$rklaim['tgl_bayar_asuransi'].'"  target="_blank" href="e_report_klaim.php?er=realisasi_as&id='.$rklaim['id'].'"><img src="image/print.png" width="30"></a>';
					}

					$surat_ket='';
					if($rklaim['kadaluarsa_hari']> 150 ){
						//$surat_ket='<a target="_blank" href="e_surat.php?er=eL_pengajuanklaim&tipe=kadaluarsa&rem=1&id='.$rklaim['id'].'"><img src="image/print.png" width="30"></a><a target="_blank" href="e_surat.php?er=eL_pengajuanklaim&tipe=kadaluarsa&rem=2&id='.$rklaim['id'].'"><img src="image/print.png" width="30"></a><a target="_blank" href="e_surat.php?er=eL_pengajuanklaim&tipe=kadaluarsa&rem=3&id='.$rklaim['id'].'"><img src="image/print.png" width="30"></a><a target="_blank" href="e_surat.php?er=eL_pengajuanklaim&tipe=kadaluarsa&rem=1&id='.$rklaim['id'].'"><img src="image/print.png" width="30"></a>';
						//$status_dok='<a target="_blank" href="ajk_claim.php?d=suratpengajuan1&id='.$rklaim['id'].'">'.$rklaim['status_klaim'].'</a>';
						if(!empty($rklaim['no_surat_reminder3'])){
							$status_dok=$rklaim['status_klaim'];
							$reminder = 'Reminder 3 '.$rklaim['tgl_surat_reminder3'];
						}else{
							$status_dok='<a href="ajk_claim.php?d=suratpengajuan1&id='.$rklaim['id'].'">'.$rklaim['status_klaim'].'</a>';
							$reminder = ' - ';
						}

					}elseif($rklaim['kadaluarsa_hari']>= 141 ){
						if(!empty($rklaim['no_surat_reminder3'])){
							$status_dok=$rklaim['status_klaim'];
							$reminder = 'Reminder 3 '.$rklaim['tgl_surat_reminder3'];
						}else{
							$status_dok='<a href="ajk_claim.php?d=suratpengajuan1&rem=3&id='.$rklaim['id'].'">'.$rklaim['status_klaim'].'</a>';
							$reminder = ' - ';
						}

						if(!empty($rklaim['no_surat_reminder3'])){
							$surat_ket='<a target="_blank" href="e_surat.php?er=eL_pengajuanklaim&tipe=kadaluarsa&rem=1&id='.$rklaim['id'].'"><img src="image/print.png" width="30"></a><a target="_blank" href="e_surat.php?er=eL_pengajuanklaim&tipe=kadaluarsa&rem=2&id='.$rklaim['id'].'"><img src="image/print.png" width="30"></a><a target="_blank" href="e_surat.php?er=eL_pengajuanklaim&tipe=kadaluarsa&rem=3&id='.$rklaim['id'].'"><img src="image/print.png" width="30"></a>';
						}

					}elseif($rklaim['kadaluarsa_hari']>= 127 ){
						if(!empty($rklaim['no_surat_reminder2'])){
							$status_dok=$rklaim['status_klaim'];
							$reminder = 'Reminder 2 '.$rklaim['tgl_surat_reminder2'];
						}else{
							$status_dok='<a href="ajk_claim.php?d=suratpengajuan1&rem=2&id='.$rklaim['id'].'">'.$rklaim['status_klaim'].'</a>';
							$reminder = ' - ';
						}
						if(!empty($rklaim['no_surat_reminder2'])){
						$surat_ket='<a target="_blank" href="e_surat.php?er=eL_pengajuanklaim&tipe=kadaluarsa&rem=1&id='.$rklaim['id'].'"><img src="image/print.png" width="30"></a><a target="_blank" href="e_surat.php?er=eL_pengajuanklaim&tipe=kadaluarsa&rem=2&id='.$rklaim['id'].'"><img src="image/print.png" width="30"></a>';
						}

					}elseif($rklaim['kadaluarsa_hari']>= 120 ){
						if(!empty($rklaim['no_surat_reminder1'])){
							$status_dok=$rklaim['status_klaim'];
							$reminder = 'Reminder 1 '.$rklaim['tgl_surat_reminder1'];
						}else{
							$status_dok='<a href="ajk_claim.php?d=suratpengajuan1&rem=1&id='.$rklaim['id'].'">'.$rklaim['status_klaim'].'</a>';
							$reminder = ' - ';
						}

						if(!empty($rklaim['no_surat_reminder1'])){
							$surat_ket='<a target="_blank" href="e_surat.php?er=eL_pengajuanklaim&tipe=kadaluarsa&rem=1&id='.$rklaim['id'].'"><img src="image/print.png" width="30"></a>';
						}
					}

					//SETTING PEMBERITAHUAN INVESTIGASI
					if (($no % 2) == 1)	$objlass = 'tbl-odd'; else				$objlass = 'tbl-even';
					echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
					<td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
					<td>'.$rklaim['input_date'].'</td>
					<td>'.$rklaim['no_urut_klaim'].'</td>
					<td align="center">'.$rklaim['asuransi'].'</td>
					<td align="center"><a href="ajk_claim.php?d=dmonitoring&id='.$rklaim['id'].'" target="_blank">'.$rklaim['id_peserta'].'</a></td>
					<td align="center">'.substr($rklaim['dn_kode'],3).'</td>
					<td align="center">'.$rklaim['nmproduk'].'</td>
					<td><a title="Worksheet Klaim" href="ajk_claim.php?d=wklaim&id='.$rklaim['id'].'" target="_blank">'.$rklaim['nama'].'</a></td>
					<td align="center">'.$rklaim['cabang'].'</td>
					<td align="center">'._convertDate($rklaim['kredit_tgl']).'</td>
					<td align="center">'._convertDate($rklaim['kredit_akhir']).'</td>
					<td align="center">'._convertDate($rklaim['tgl_claim']).'</td>
					<td align="center">'.date_format(date_create($rklaim['approve_date']),"d-m-Y").'</td>
					<td align="center">'.$rklaim['kadaluarsa1'].' hari sebelum kadaluarsa</td>
					<td align="center">'.$rklaim['tenor'].'</td>
					<!--  <td align="center">'.$klaimBlnS.','.$klaimBlnJ.'</td> --!>
					<td align="right">'.duit($rklaim['tuntutan_klaim']).'</td>
					<td align="center">'.$status_dok.'</td>
					<td align="center">'.$metbyrklaim.'</td>
					<td align="center">'.$rklaim['kadaluarsa_hari'].'</td>
					<!--<td align="center">'.$surat_ket.'</td>-->
					<td align="center">'.$reminder.'</td>

					</tr>';
				}
				echo '<tr><td colspan="22">';
				echo createPageNavigations($file = 'ajk_claim.php?d=kadaluarsa', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
				echo '<b>Total Data Meninggal: <u>' . $totalRows . '</u></b></td></tr>';
				echo '</table>';

				echo"<script type='text/javascript' src='js/jquery/jquery.min-1.11.1.js'></script>
				<script type='text/javascript'>//<![CDATA[
			 	$(window).load(function(){
			 		$(document).ready(function () {
			 			(function ($) {
			 				$('#cari').keyup(function () {
			 					var rex = new RegExp($(this).val(), 'i');
			 					$('.caritable tr').hide();
			 					$('.caritable tr').filter(function () {
			 						return rex.test($(this).text());
			 					}).show();

			 				})

			 			}(jQuery));

						$('#id_cost').change(function(){
						var idcost = document.getElementById('id_cost').value;
							$.ajax({
								type:'post',
								url:'javascript/metcombo/data_peserta.php',
								data:{functionname: 'setpoliscostumer',cost:idcost},
								cache:false,
								success:function(returndata) {
									$('#id_polis').html(returndata);
								}
							});

							$.ajax({
								type:'post',
								url:'javascript/metcombo/data_peserta.php',
								data:{functionname: 'setpoliscostumerregional',cost:idcost},
								cache:false,
								success:function(returndata) {
									$('#id_reg').html(returndata);
								}
							});

						});
						$('#id_reg').change(function(){
						var noreg = document.getElementById('id_reg').value;
						var idcost = document.getElementById('id_cost').value;
							$.ajax({
								type:'post',
								url:'javascript/metcombo/data_peserta.php',
								data:{functionname: 'setpoliscostumercabang',cost:idcost,regi:noreg},
								cache:false,
								success:function(returndata) {
									$('#id_cab').html(returndata);
								}
							});

						});

			 		});
			 			var idcost = document.getElementById('id_cost').value;
						$.ajax({
							type:'post',
							url:'javascript/metcombo/data_peserta.php',
							data:{functionname: 'setpoliscostumer',cost:idcost},
							cache:false,
							success:function(returndata) {
								$('#id_polis').html(returndata);
							}
						});

						$.ajax({
							type:'post',
							url:'javascript/metcombo/data_peserta.php',
							data:{functionname: 'setpoliscostumerregional',cost:idcost},
							cache:false,
							success:function(returndata) {
								$('#id_reg').html(returndata);
							}
						});

						$.ajax({
							type:'post',
							url:'javascript/metcombo/data_peserta.php',
							data:{functionname: 'setpoliscostumercabang',cost:idcost,regi:noreg},
							cache:false,
							success:function(returndata) {
								$('#id_cab').html(returndata);
							}
						});
			 	});


				</script>";
				;
	break;

	case "kadaluarsa_list" :
			$metcost = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name DESC');
			echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
				<tr><th colspan="8" width="100%" align="left" colspan="2">Modul Klaim - Meninggal (Klaim Kadaluarsa / Klaim > 90 Hari dari tanggal lapor)</font></th><th width="5%" colspan="2"><a href="ajk_claim.php?d=kadaluarsa"><img src="image/back.png" width="20"></a></th></tr>
				</table><br />';
			echo '<fieldset>
			<legend>Searching</legend>
			<form method="post" action="">
			<table border="0" width="60%" cellpadding="1" cellspacing="0" align="center">

		  	<tr><td width="10%" align="center">NAMA PERUSAHAAN</td>
				<td width="10%" align="center">REGIONAL</td>
				<td width="10%" align="center">CABANG</td>
				<td width="10%" align="center">ID PESERTA</td>
				<td width="10%" align="center">NAMA</td>
				<td width="10%" align="center">DEBIT NOTE</td>
				<td width="10%" align="center">CREDIT NOTE</td>
				<td width="10%" align="center">STATUS BAYAR</td>
			</tr>
			<tr>
				<td align="center"><select name="id_cost" id="id_cost">
			  	<option value="">---Pilih Perusahaan---</option>';
				while($metcost_ = mysql_fetch_array($metcost)) {
					echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['id_cost'], $metcost_['id']).'>'.$metcost_['name'].'</option>';
				}
				echo '</select></td>
				<td align="center"><select name="id_reg" id="id_reg">
				<option value="">-- Pilih Regional --</option>
				</select></td>
				<td align="center"><select name="id_cab" id="id_cab">
				<option value="">-- Pilih Cabang --</option>
				</select></td>
				<td align="center"><input type="text" name="ridp" value="'.$_REQUEST['ridp'].'"></td>
				<td align="center"><input type="text" name="rnama" value="'.$_REQUEST['rnama'].'"></td>
				<td align="center"><input type="text" name="nodn" value="'.$_REQUEST['nodn'].'"></td>
			  	<td align="center"><input type="text" name="nocn" value="'.$_REQUEST['nocn'].'"></td>
			  	<td align="center"> <select name="tgl_byr_claim" id="tgl_byr_claim">
			  												<option value="">-- Pilih Status --</option>
			  												<option value="1">Sudah di Bayar</option>
			  												<option value="0">Belum di Bayar</option>
															</select></td>
			  	</tr>
		  	<tr><td colspan="6" align="center"><input type="submit" name="oppe" value="Searching" class="button"></td></tr>
			</table></form></fieldset>';
			//}
			echo '<table border="0" width="100%" cellpadding="5" cellspacing="1" bgcolor="#bde0e6">
			<tr><td bgcolor="#FFF"colspan="24"><a href="ajk_claim.php?d=kadaluarsa_list">List Klaim Kadaluarsa</a></td></tr>
			<tr><th width="3%">No</td>
			<th>Input Date</td>
			<th>No. Urut</td>
			<th>Asuransi</td>
			<th width="5%">ID Peserta</td>
			<th width="1%">ID DN</td>
			<th width="1%">Produk</td>
			<th>Nama Debitur</td>
			<th>Cabang</td>
			<th width="5%">Kredit Awal</td>
			<th width="5%">Kredit Akhir</td>
			<th width="5%">Tgl Klaim</td>
			<th width="1%">Tgl Approve</td>
			<th width="1%">Kadaluarsa (Hari)</td>
			<th width="1%">Tenor</td>
			<!-- <th width="1%">Tenor (S,B)</td> --!>
			<th width="1%">Tuntutan Klaim</td>
			<th width="1%">Status</td>
			<th width="1%">Tgl Bayar Klaim</td>
			<th width="1%">jDoc</td>
			</tr>';
			if ($_REQUEST['ridp'])		{	$empat = 'AND fu_ajk_peserta.id_peserta LIKE "%' . $_REQUEST['ridp'] . '%"';		}
			if ($_REQUEST['nodn'])		{	$satu = 'AND fu_ajk_dn.dn_kode LIKE "%' . $_REQUEST['nodn'] . '%"';		}
			if ($_REQUEST['nocn'])		{	$dua = 'AND fu_ajk_cn.id_cn LIKE "%' . $_REQUEST['nocn'] . '%"';		}
			if ($_REQUEST['rnama'])		{	$tiga = 'AND fu_ajk_peserta.nama LIKE "%'.$_REQUEST['rnama'].'%"';
			$namanya = mysql_fetch_array($database->doQuery('SELECT id_klaim, status_peserta, id_peserta FROM fu_ajk_peserta WHERE id_klaim !="" AND status_peserta="Death" '.$tiga.' '));
			$ernama = 'AND fu_ajk_peserta.id_peserta = "' . $namanya['id_peserta'] . '"';
			}

			if ($_REQUEST['ridp'])		{
				$empat = 'AND fu_ajk_peserta.id_peserta LIKE "%' . $_REQUEST['ridp'] . '%"';
			}

			if ($_REQUEST['nodn'])		{
				$satu = 'AND fu_ajk_dn.dn_kode LIKE "%' . $_REQUEST['nodn'] . '%"';
			}

			if ($_REQUEST['nocn'])		{
				$dua = 'AND fu_ajk_cn.id_cn LIKE "%' . $_REQUEST['nocn'] . '%"';
			}

			if ($_REQUEST['rnama'])		{
				$tiga = 'AND fu_ajk_peserta.nama LIKE "%'.$_REQUEST['rnama'].'%"';
			}

			if ($_POST['id_cost'])	{
				$lima = 'AND fu_ajk_peserta.id_cost = "'.$_REQUEST['id_cost'].'"';
			}

			if (isset($_POST['id_reg']) && $_POST['id_reg']!=="") {
				$metreg1__ = mysql_query('SELECT * FROM fu_ajk_regional where id="'.$_POST['id_reg'].'"');
				$metreg__ = mysql_fetch_array($metreg1__);
				$enam = 'AND fu_ajk_peserta.regional="'.$metreg__['name'].'"';
			}

			if (isset($_POST['id_cab']) && $_POST['id_cab']!=="") {
				$metcab1__ = mysql_query('SELECT * FROM fu_ajk_cabang where id="'.$_POST['id_cab'].'"');
				$metcab__ = mysql_fetch_array($metcab1__);
				$tujuh= 'AND fu_ajk_peserta.cabang="'.$metcab__['name'].'"';
			}

			if (isset($_POST['tgl_byr_claim']) && $_POST['tgl_byr_claim']!=="") {
				if($_POST['tgl_byr_claim']==1){
					$delapan= 'AND fu_ajk_cn.tgl_byr_claim!=""';
				}else{
					$delapan= 'AND ifnull(fu_ajk_cn.tgl_byr_claim,"")=""';
				}
			}


			if ($_REQUEST['rdob'])		{	$empat = 'AND tgl_lahir = "' . $_REQUEST['rdob'] . '"';
			$dobnya = mysql_fetch_array($database->doQuery('SELECT id_klaim, status_peserta, id_peserta FROM fu_ajk_peserta WHERE id_klaim !="" AND status_peserta="Death" '.$empat.' '));
			$erdob = 'AND fu_ajk_peserta.tgl_lahir = "' . $dobnya['tgl_lahir'] . '"';
			}

			if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}

			//$metklaim = $database->doQuery('SELECT * FROM fu_ajk_cn WHERE type_claim = "Death" '.$satu.' '.$dua.' '.$ernama.' '.$erdob.' AND del IS NULL ORDER BY id DESC LIMIT ' . $m . ' , 25');
			$metklaim = $database->doQuery('SELECT
				fu_ajk_asuransi.`name` AS asuransi,
				fu_ajk_cn.id,
				fu_ajk_klaim.no_urut_klaim,
				fu_ajk_cn.id_cost,
				fu_ajk_cn.id_cn,
				fu_ajk_dn.dn_kode,
				fu_ajk_peserta.id_peserta,
				fu_ajk_peserta.nama,
				fu_ajk_peserta.tgl_lahir,
				fu_ajk_peserta.usia,
				fu_ajk_peserta.kredit_tgl,
				IF(fu_ajk_peserta.type_data="SPK", fu_ajk_peserta.kredit_tenor * 12, fu_ajk_peserta.kredit_tenor) AS tenor,
				fu_ajk_peserta.kredit_akhir,
				fu_ajk_peserta.kredit_jumlah,
				fu_ajk_cn.tgl_claim,
				fu_ajk_cn.premi,
				fu_ajk_cn.confirm_claim,
				fu_ajk_cn.total_claim,
				fu_ajk_cn.tuntutan_klaim,
				fu_ajk_cn.tgl_byr_claim,
				fu_ajk_polis.nmproduk,
				fu_ajk_peserta.cabang,
				fu_ajk_klaim.tgl_kirim_dokumen,
				fu_ajk_cn.tgl_bayar_asuransi,
				fu_ajk_cn.input_date,
				fu_ajk_cn.approve_date,


				IF(DATEDIFF(fu_ajk_cn.approve_date,fu_ajk_cn.tgl_claim) > 90,CONCAT(DATEDIFF(fu_ajk_cn.approve_date,fu_ajk_cn.tgl_claim)," dari tanggal klaim ke tanggal approve"),
				if(if(DATEDIFF(fu_ajk_cn.tgl_claim,fu_ajk_cn.tgl_document_lengkap) is null,
				DATEDIFF(current_date(),fu_ajk_cn.approve_date),
				DATEDIFF(fu_ajk_cn.tgl_document_lengkap,fu_ajk_cn.approve_date)) > 150,CONCAT(DATEDIFF(current_date(),fu_ajk_cn.approve_date)," dari tanggal input sampai hari ini"),"klaim bersih")) as kadaluarsa1,


				IF(DATEDIFF(fu_ajk_cn.approve_date,fu_ajk_cn.tgl_claim) > 90,"kadaluarsa",
				if(if(DATEDIFF(fu_ajk_cn.tgl_claim,fu_ajk_cn.tgl_document_lengkap) is null,
				DATEDIFF(current_date(),fu_ajk_cn.approve_date),
				DATEDIFF(fu_ajk_cn.tgl_document_lengkap,fu_ajk_cn.approve_date)) > 150,"kadaluarsa","ga")) as kadaluarsa,

				fu_ajk_klaim_status.status_klaim
				FROM
				fu_ajk_cn
				INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
				INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
				INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
				INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta AND fu_ajk_cn.id = fu_ajk_peserta.id_klaim
				LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
				LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim.id_klaim_status = fu_ajk_klaim_status.id
				WHERE fu_ajk_cn.type_claim = "Death" AND fu_ajk_cn.confirm_claim <> "Pending" AND fu_ajk_cn.del IS NULL
				and
				IF(DATEDIFF(fu_ajk_cn.approve_date,fu_ajk_cn.tgl_claim) > 90,"kadaluarsa",
				if(if(DATEDIFF(fu_ajk_cn.tgl_claim,fu_ajk_cn.tgl_document_lengkap) is null,
				DATEDIFF(current_date(),fu_ajk_cn.approve_date),
				DATEDIFF(fu_ajk_cn.tgl_document_lengkap,fu_ajk_cn.approve_date)) > 150,"kadaluarsa","ga"))="kadaluarsa"

				and if(fu_ajk_cn.tgl_bayar_asuransi is null or fu_ajk_cn.tgl_bayar_asuransi="0000-00-00","UNPAID" ,"PAID")="UNPAID"
				'.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' '.$tujuh.' '.$delapan.'
				ORDER BY if(DATEDIFF(fu_ajk_cn.tgl_claim,fu_ajk_cn.tgl_document_lengkap) is null,
				DATEDIFF(current_date(),fu_ajk_cn.tgl_claim),
				DATEDIFF(fu_ajk_cn.tgl_document_lengkap,fu_ajk_cn.tgl_claim))  asc LIMIT ' . $m . ' , 25');
			//$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_cn WHERE id != "" AND type_claim="Death" '.$satu.' '.$dua.' '.$ernama.' '.$erdob.' AND del IS NULL'));
			$totalRows = mysql_num_rows($database->doQuery('SELECT fu_ajk_cn.id_cn
				FROM
				fu_ajk_cn
				INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
				INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
				INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
				INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta AND fu_ajk_cn.id = fu_ajk_peserta.id_klaim
				LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
				LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim.id_klaim_status = fu_ajk_klaim_status.id
				WHERE fu_ajk_cn.type_claim = "Death" AND fu_ajk_cn.confirm_claim <> "Pending" AND fu_ajk_cn.del IS NULL
				and
				IF(DATEDIFF(fu_ajk_cn.approve_date,fu_ajk_cn.tgl_claim) > 90,"kadaluarsa",
				if(if(DATEDIFF(fu_ajk_cn.tgl_claim,fu_ajk_cn.tgl_document_lengkap) is null,
				DATEDIFF(current_date(),fu_ajk_cn.approve_date),
				DATEDIFF(fu_ajk_cn.tgl_document_lengkap,fu_ajk_cn.approve_date)) > 150,"kadaluarsa","ga"))="kadaluarsa"

				and if(fu_ajk_cn.tgl_bayar_asuransi is null or fu_ajk_cn.tgl_bayar_asuransi="0000-00-00","UNPAID" ,"PAID")="UNPAID"
				'.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' '.$tujuh.' '.$delapan.'
				ORDER BY if(DATEDIFF(fu_ajk_cn.tgl_claim,fu_ajk_cn.tgl_document_lengkap) is null,
				DATEDIFF(current_date(),fu_ajk_cn.tgl_claim),
				DATEDIFF(fu_ajk_cn.tgl_document_lengkap,fu_ajk_cn.tgl_claim))'));
			//$totalRows = $totalRows[0];
			$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
			while ($rklaim = mysql_fetch_array($metklaim)) {
				$jdoc = mysql_num_rows($database->doQuery('
					SELECT DISTINCT fu_ajk_dokumenklaim_bank.id_bank, fu_ajk_dokumenklaim_bank.id_dok, fu_ajk_dokumenklaim.nama_dok
					FROM fu_ajk_dokumenklaim_bank
					INNER JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim_bank.id_dok = fu_ajk_dokumenklaim.id
					INNER JOIN fu_ajk_klaim_doc ON fu_ajk_klaim_doc.dokumen = fu_ajk_dokumenklaim_bank.id
					where fu_ajk_klaim_doc.id_pes="'.$rklaim['id_peserta'].'" AND fu_ajk_klaim_doc.id_cost="'.$rklaim['id_cost'].'"
					'));

				$mets = datediff($rklaim['kredit_tgl'], $rklaim['tgl_claim']);
				$metTgl = explode(",",$mets);
				//echo $metTgl[0].'-'.$metTgl[1].'-'.$metTgl[2].'<br />';
				if ($metTgl[2] > 0) {	$jumBulan = $metTgl[1] + 1;	}else{	$jumBulan = $metTgl[1];	}	//AKUMULASI BULAN THD JUMLAH HARI
				$klaimBlnJ = $metTgl[0] + $jumBulan;
				$klaimBlnS = $rklaim['tenor'] - $klaimBlnJ;
				//SETTING TGL BAYAR KLAIM//
				if ($rklaim['tgl_byr_claim']=="") {	$metbyrklaim = 'Belum dibayar';	}else{	$metbyrklaim = '<font color="blue">'._convertDate($rklaim['tgl_byr_claim']).'</font>';	}
				//SETTING TGL BAYAR KLAIM//

				//SETTING PEMBERITAHUAN INVESTIGASI
				if ($rklaim['confirm_claim']=="Investigasi") {
					$setInvestigasi ='<a title="Edit data ahli waris" href="ajk_claim.php?d=eInvKlaim&ido='.$rklaim['id'].'"><img src="image/edit.png" width="30"></a>';
				}else{
					$setInvestigasi ='<a title="Confirm Investigasi Klaim" href="ajk_claim.php?d=InvKlaim&id='.$rklaim['id'].'"><img src="image/doc_death.png" width="30"></a>';
				}
				$surat_pem='';
				if(!is_null($rklaim['tgl_kirim_dokumen']) && $rklaim['tgl_kirim_dokumen']!='0000-00-00' && datediff($rklaim['tgl_kirim_dokumen'], $futoday)>=7){
					$surat_pem='<a title="Print Surat Persetujuan Ke asuransi" target="_blank" href="e_report_klaim.php?er=setuju_as&id='.$rklaim['id'].'"><img src="image/print.png" width="30"></a>';
				}

				if(!is_null($rklaim['tgl_kirim_dokumen']) && $rklaim['tgl_kirim_dokumen']!='0000-00-00' && datediff($rklaim['tgl_kirim_dokumen'], $futoday)>=14){
					$surat_pem='<a title="Print Surat Realisasi Ke asuransi '.$rklaim['tgl_bayar_asuransi'].'"  target="_blank" href="e_report_klaim.php?er=realisasi_as&id='.$rklaim['id'].'"><img src="image/print.png" width="30"></a>';
				}


				$status_dok='';
				if($rklaim['kadaluarsa']=="kadaluarsa"){
					$status_dok='Klaim Kadaluarsa';
				}else{
					$status_dok='<a target="_blank" href="ajk_claim.php?d=suratpengajuan&id='.$rklaim['id'].'">'.$rklaim['status_klaim'].'</a>';
				}
				//SETTING PEMBERITAHUAN INVESTIGASI
				if (($no % 2) == 1)	$objlass = 'tbl-odd'; else				$objlass = 'tbl-even';
				echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
				<td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
				<td>'.$rklaim['input_date'].'</td>
				<td>'.$rklaim['no_urut_klaim'].'</td>
				<td align="center">'.$rklaim['asuransi'].'</td>
				<td align="center"><a href="ajk_claim.php?d=dmonitoring&id='.$rklaim['id'].'" target="_blank">'.$rklaim['id_peserta'].'</a></td>
				<td align="center">'.substr($rklaim['dn_kode'],3).'</td>
				<td align="center">'.$rklaim['nmproduk'].'</td>
				<td><a title="Worksheet Klaim" href="ajk_claim.php?d=wklaim&id='.$rklaim['id'].'" target="_blank">'.$rklaim['nama'].'</a></td>
				<td align="center">'.$rklaim['cabang'].'</td>
				<td align="center">'._convertDate($rklaim['kredit_tgl']).'</td>
				<td align="center">'._convertDate($rklaim['kredit_akhir']).'</td>
				<td align="center">'._convertDate($rklaim['tgl_claim']).'</td>
				<td align="center">'.date_format(date_create($rklaim['approve_date']),"d-m-Y").'</td>
				<td align="center">'.$rklaim['kadaluarsa1'].'</td>
				<td align="center">'.$rklaim['tenor'].'</td>
				<!--  <td align="center">'.$klaimBlnS.','.$klaimBlnJ.'</td> --!>
				<td align="right">'.duit($rklaim['tuntutan_klaim']).'</td>
				<td align="center">'.$status_dok.'</td>
				<td align="center">'.$metbyrklaim.'</td>
				<td align="center">'.$jdoc.'</td>
				</tr>';
			}
			echo '<tr><td colspan="22">';
			echo createPageNavigations($file = 'ajk_claim.php?d=kadaluarsa_list', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
			echo '<b>Total Data Meninggal: <u>' . $totalRows . '</u></b></td></tr>';
			echo '</table>';

			echo"<script type='text/javascript' src='js/jquery/jquery.min-1.11.1.js'></script>
			<script type='text/javascript'>//<![CDATA[
			 	$(window).load(function(){
			 		$(document).ready(function () {
			 			(function ($) {
			 				$('#cari').keyup(function () {
			 					var rex = new RegExp($(this).val(), 'i');
			 					$('.caritable tr').hide();
			 					$('.caritable tr').filter(function () {
			 						return rex.test($(this).text());
			 					}).show();

			 				})

			 			}(jQuery));

					$('#id_cost').change(function(){
					var idcost = document.getElementById('id_cost').value;
						$.ajax({
							type:'post',
							url:'javascript/metcombo/data_peserta.php',
							data:{functionname: 'setpoliscostumer',cost:idcost},
							cache:false,
							success:function(returndata) {
								$('#id_polis').html(returndata);
							}
						});

						$.ajax({
							type:'post',
							url:'javascript/metcombo/data_peserta.php',
							data:{functionname: 'setpoliscostumerregional',cost:idcost},
							cache:false,
							success:function(returndata) {
								$('#id_reg').html(returndata);
							}
						});

					});
					$('#id_reg').change(function(){
					var noreg = document.getElementById('id_reg').value;
					var idcost = document.getElementById('id_cost').value;
						$.ajax({
							type:'post',
							url:'javascript/metcombo/data_peserta.php',
							data:{functionname: 'setpoliscostumercabang',cost:idcost,regi:noreg},
							cache:false,
							success:function(returndata) {
								$('#id_cab').html(returndata);
							}
						});

					});

		 		});
		 			var idcost = document.getElementById('id_cost').value;
					$.ajax({
						type:'post',
						url:'javascript/metcombo/data_peserta.php',
						data:{functionname: 'setpoliscostumer',cost:idcost},
						cache:false,
						success:function(returndata) {
							$('#id_polis').html(returndata);
						}
					});

					$.ajax({
						type:'post',
						url:'javascript/metcombo/data_peserta.php',
						data:{functionname: 'setpoliscostumerregional',cost:idcost},
						cache:false,
						success:function(returndata) {
							$('#id_reg').html(returndata);
						}
					});

					$.ajax({
						type:'post',
						url:'javascript/metcombo/data_peserta.php',
						data:{functionname: 'setpoliscostumercabang',cost:idcost,regi:noreg},
						cache:false,
						success:function(returndata) {
							$('#id_cab').html(returndata);
						}
					});
		 	});


		</script>";
			;
	break;

	case "email_notif" :

			echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
			<tr><th colspan="8" width="100%" align="left" colspan="2">Modul Klaim - Email Awal</font></th><th width="5%" colspan="2"><a href="ajk_claim.php?d=email_notif_new"><img src="image/new.png" width="20"></a></th></tr>
			</table><br />';
			echo '<fieldset>
		<legend>Searching</legend>
		<form method="post" action="">
		<table border="0" width="60%" cellpadding="1" cellspacing="0" align="center">
			<tr><td width="10%" align="center">ID PESERTA</td>
			<td width="10%" align="center">NAMA</td>
			<td width="10%" align="center">DEBIT NOTE</td>
			<td width="10%" align="center">CREDIT NOTE</td>
			<td width="20%" align="center">TANGGAL INFORMASI KLAIM (ASURANSI)</td>
		</tr>
		<tr>
			<td align="center"><input type="text" name="ridp" value="'.$_REQUEST['ridp'].'"></td>
			<td align="center"><input type="text" name="rnama" value="'.$_REQUEST['rnama'].'"></td>
			<td align="center"><input type="text" name="nodn" value="'.$_REQUEST['nodn'].'"></td>
				<td align="center"><input type="text" name="nocn" value="'.$_REQUEST['nocn'].'"></td>
				<td align="center">';
			print initCalendar();
			print calendarBox('tglinput', 'triger5', $dateku);
			echo '</td></tr>
			<tr><td colspan="6" align="center"><input type="submit" name="oppe" value="Searching" class="button"></td></tr>
		</table></form></fieldset>';
			//}
			echo '<table border="0" width="100%" cellpadding="5" cellspacing="1" bgcolor="#bde0e6">
		<tr><th width="3%">No</td>
		<th>Tanggal Lapor Klaim</td>
		<th>No Lapor Klaim</td>
		<th>Jumlah Peserta</td>
		<th>Email Tujuan</td>
		</tr>';

			if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}

			//$metklaim = $database->doQuery('SELECT * FROM fu_ajk_cn WHERE type_claim = "Death" '.$satu.' '.$dua.' '.$ernama.' '.$erdob.' AND del IS NULL ORDER BY id DESC LIMIT ' . $m . ' , 25');
			$metklaim = $database->doQuery('
			Select fu_ajk_klaim.no_email, fu_ajk_klaim.tgl_lapor_klaim,fu_ajk_asuransi.name as nama_asuransi,count(fu_ajk_klaim.id) as jml
			FROM fu_ajk_cn
			INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
			INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
			INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta AND fu_ajk_cn.id = fu_ajk_peserta.id_klaim
			LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
			WHERE fu_ajk_cn.type_claim = "Death" AND fu_ajk_cn.confirm_claim <> "Pending" AND fu_ajk_cn.del IS NULL
			and fu_ajk_klaim.no_email<>""
			group by
			fu_ajk_klaim.no_email, fu_ajk_klaim.tgl_lapor_klaim,fu_ajk_asuransi.name
			ORDER BY fu_ajk_klaim.tgl_lapor_klaim DESC LIMIT ' . $m . ' , 25');
			//$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_cn WHERE id != "" AND type_claim="Death" '.$satu.' '.$dua.' '.$ernama.' '.$erdob.' AND del IS NULL'));
			$totalRows = mysql_fetch_array($database->doQuery('select count(no_email) from (SELECT fu_ajk_klaim.no_email, fu_ajk_klaim.tgl_lapor_klaim,fu_ajk_asuransi.name as nama_asuransi,COUNT(fu_ajk_klaim.id)
			FROM fu_ajk_cn
			INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
			INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
			INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta AND fu_ajk_cn.id = fu_ajk_peserta.id_klaim
			LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
			WHERE fu_ajk_cn.type_claim = "Death" AND fu_ajk_cn.confirm_claim <> "Pending"
			and (fu_ajk_klaim.no_email<>"")
			AND fu_ajk_cn.del IS NULL
			group by
			fu_ajk_klaim.no_email, fu_ajk_klaim.tgl_lapor_klaim,fu_ajk_asuransi.name) aa
			'));
			$totalRows = $totalRows[0];
			$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
			while ($rklaim = mysql_fetch_array($metklaim)) {
				if($rklaim['tgl_lapor_klaim']==''){
					$oo='<a href="ajk_claim.php?d=email_notif_new&noe='.$rklaim['no_email'].'" target="_blank">'.$rklaim['no_email'].'</a>';
				}else{
					$oo='<a href="ajk_claim.php?d=email_notif_new1&noe='.$rklaim['no_email'].'" target="_blank">'.$rklaim['no_email'].'</a>';
				}
				if (($no % 2) == 1)	$objlass = 'tbl-odd'; else
					$objlass = 'tbl-even';
					echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
			<td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
			<td align="center">'.$rklaim['tgl_lapor_klaim'].'</td>
			<td align="center">'.$oo.'</td>
			<td align="center">'.$rklaim['jml'].'</td>
			<td>'.$rklaim['nama_asuransi'].'</td>
			</tr>';
			}
			echo '<tr><td colspan="22">';
			echo createPageNavigations($file = 'ajk_claim.php?d=email_notif', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
			echo '<b>Total Data Meninggal: <u>' . $totalRows . '</u></b></td></tr>';
			echo '</table>';
			;
	break;

	case "email_notif_new" :
				function auto_number($nilai_default,$panjang_nomor,$nama_db,$nama_tb,$ulangi,$tanggal_tb)
				{
					if($ulangi=="")
					{
						$pjg_nomor=strlen($nilai_default)+1;
						$query="SELECT MAX(SUBSTRING($nama_tb,$pjg_nomor,$panjang_nomor))+1 AS nu FROM $nama_db";
						$result=mysql_query($query);
						$data=mysql_fetch_array($result);
						$jml_nol=str_repeat("0", $panjang_nomor);
						if(is_null($data['nu']))
						{
							$hasil=$nilai_default.substr($jml_nol."1", -$panjang_nomor);
						}
						else
						{
							$hasil=$nilai_default.substr($jml_nol.$data['nu'], -$panjang_nomor);
						}
						return $hasil;
					}
					else
					{
						$pjg_nomor=strlen($nilai_default)+1;
						if($ulangi=='year'){
							$query="SELECT MAX(SUBSTRING($nama_tb,$pjg_nomor,$panjang_nomor))+1 AS nu FROM $nama_db where $ulangi($tanggal_tb)=$ulangi(curdate())";
						}elseif($ulangi=='month'){
							$query="SELECT MAX(SUBSTRING($nama_tb,$pjg_nomor,$panjang_nomor))+1 AS nu FROM $nama_db where $ulangi($tanggal_tb)=$ulangi(curdate()) and year($tanggal_tb)=year(curdate())";
						}elseif($ulangi=='day'){
							$query="SELECT MAX(SUBSTRING($nama_tb,$pjg_nomor,$panjang_nomor))+1 AS nu FROM $nama_db where $ulangi($tanggal_tb)=$ulangi(curdate()) and month($tanggal_tb)=month(curdate()) and year($tanggal_tb)=year(curdate())";
						}

						$result=mysql_query($query);
						$data=mysql_fetch_array($result);
						$jml_nol=str_repeat("0", $panjang_nomor);
						if(is_null($data['nu']))
						{
							$hasil=$nilai_default.substr($jml_nol."1", -$panjang_nomor);
						}
						else
						{
							$hasil=$nilai_default.substr($jml_nol.$data['nu'], -$panjang_nomor);
						}
						return $hasil;
					}
				}
				function bulanindo($bulan){
					if($bulan=='01'){
						$ls_namabulan =  'Januari';
					}elseif($bulan=='02'){
						$ls_namabulan =  'Februari';
					}elseif($bulan=='03'){
						$ls_namabulan =  'Maret';
					}elseif($bulan=='04'){
						$ls_namabulan =  'April';
					}elseif($bulan=='05'){
						$ls_namabulan =  'Mei';
					}elseif($bulan=='06'){
						$ls_namabulan =  'Juni';
					}elseif($bulan=='07'){
						$ls_namabulan =  'Juli';
					}elseif($bulan=='08'){
						$ls_namabulan =  'Agustus';
					}elseif($bulan=='09'){
						$ls_namabulan =  'September';
					}elseif($bulan=='10'){
						$ls_namabulan =  'Oktober';
					}elseif($bulan=='11'){
						$ls_namabulan =  'November';
					}elseif($bulan=='12'){
						$ls_namabulan =  'Desember';
					}
					return $ls_namabulan;
				}
				function _check_peserta($idpeserta){
					$result=mysql_query('
					SELECT
					fu_ajk_asuransi.`name` as asuransi,
					fu_ajk_klaim.no_email,
					fu_ajk_klaim.tgl_lapor_klaim,
					fu_ajk_cn.id_peserta,
					fu_ajk_peserta.nama,
					fu_ajk_peserta.tgl_lahir,
					fu_ajk_peserta.cabang,
					fu_ajk_peserta.kredit_tgl,
					fu_ajk_peserta.kredit_jumlah,
					fu_ajk_cn.tgl_claim,
					fu_ajk_cn.tuntutan_klaim
					FROM fu_ajk_cn
					INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
					INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
					INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta AND fu_ajk_cn.id = fu_ajk_peserta.id_klaim
					LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
					WHERE fu_ajk_cn.type_claim = "Death" AND fu_ajk_cn.confirm_claim <> "Pending" AND fu_ajk_cn.del IS NULL
					and no_email="" and fu_ajk_klaim.id_peserta="'.$idpeserta.'"
					');
					if(mysql_num_rows($result)>0){
						return false;
					}else{
						return  true;
					}
				}

				if(isset($_POST['oppe']) && $_POST['oppe']=='Tambah'){
					if(_check_peserta($_POST['id_peserta'])==true){
						if(isset($_GET['noe'])){
							$noe=$_GET['noe'];
						}else{
							$noe=auto_number(date('Ymd'), 3, "fu_ajk_klaim", "no_email", "day", "tgl_lapor_klaim");
						}
						$query="update fu_ajk_klaim set no_email='".$noe."' where id_peserta='".$_POST['ridp']."' and type_klaim='Death' and del is null";
						mysql_query($query);

						header("location:ajk_claim.php?d=email_notif_new&noe=".$noe);
					}else{
						echo'Peserta ini telah di buat email notifnya ';
					}
				}elseif(isset($_GET['delid'])){
					$que="update fu_ajk_klaim set no_email='' where id=".$_GET['delid'];
					mysql_query($que);
					header("location:ajk_claim.php?d=email_notif_new&noe=".$_GET['noe']);
					exit();
				}

				echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
				<tr><th colspan="8" width="100%" align="left" colspan="2">Modul Klaim - Email Awal</font></th><th width="5%" colspan="2"><a href="ajk_claim.php?d=email_notif"><img src="image/back.png" width="20"></a></th></tr>
				</table><br />';
					echo '<fieldset>
				<legend>Cari Peserta</legend>
				<form method="post" action="">
				<table border="0" cellpadding="1" cellspacing="0">
			  	<tr><td>ID Peserta</td>
					<td>: <input type="text" name="ridp" value="'.$_REQUEST['ridp'].'"></td>
				</tr>
			  	<tr><td></td>
					<td>&nbsp;&nbsp;<input type="submit" name="oppe" value="Tambah" class="button"></td></tr>
				</table>
				</form></fieldset>';
				//}

				if(isset($_GET['noe'])){

					echo '<table border="0" width="100%" cellpadding="5" cellspacing="1" bgcolor="#bde0e6">
					<tr><th width="3%">No</td>
					<th>Asuransi</td>
					<th>Produk</td>
					<th>No Email</td>
					<th>Tg Lapor Klaim</td>
					<th>ID Peserta</td>
					<th>Nama Peserta</td>
					<th>Tgl Lahir</td>
					<th>Cabang</td>
					<th>Tgl Akad</td>
					<th>Plafond</td>
					<th>Tgl Meninggal</td>
					<th>Tuntutan Klaim</td>
					<th>Option</td>
					</tr>';


					//$metklaim = $database->doQuery('SELECT * FROM fu_ajk_cn WHERE type_claim = "Death" '.$satu.' '.$dua.' '.$ernama.' '.$erdob.' AND del IS NULL ORDER BY id DESC LIMIT ' . $m . ' , 25');
					$metklaim = mysql_query('
					SELECT
					fu_ajk_klaim.id as klaim_id,
					fu_ajk_asuransi.id as id_asuransi,
					fu_ajk_asuransi.`name` as asuransi,
					fu_ajk_klaim.no_email,
					fu_ajk_polis.nmproduk,
					fu_ajk_klaim.tgl_lapor_klaim,
					fu_ajk_cn.id_peserta,
					fu_ajk_peserta.nama,
					fu_ajk_peserta.tgl_lahir,
					fu_ajk_peserta.cabang,
					fu_ajk_peserta.kredit_tgl,
					fu_ajk_peserta.kredit_jumlah,
					fu_ajk_cn.tgl_claim,
					fu_ajk_cn.tuntutan_klaim
					FROM fu_ajk_cn
					INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
					INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
					INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta AND fu_ajk_cn.id = fu_ajk_peserta.id_klaim
					INNER JOIN fu_ajk_polis ON fu_ajk_peserta.id_polis = fu_ajk_polis.id
					LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
					WHERE fu_ajk_cn.type_claim = "Death" AND fu_ajk_cn.confirm_claim <> "Pending" AND fu_ajk_cn.del IS NULL
					/*and (fu_ajk_klaim.tgl_lapor_klaim is null or fu_ajk_klaim.tgl_lapor_klaim="0000-00-00")*/
					and fu_ajk_klaim.no_email="'.$_GET['noe'].'"
					');
					$jml_peserta=mysql_num_rows($metklaim);
					$jml=1;
					while ($rklaim = mysql_fetch_array($metklaim)) {

						if (($no % 2) == 1)	$objlass = 'tbl-odd'; else
							$objlass = 'tbl-even';
							echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
							<td align="center">'.(++$no).'</td>
							<td align="center">'.$rklaim['asuransi'].'</td>
							<td align="center">'.$rklaim['nmproduk'].'</td>
							<td align="center">'.$rklaim['no_email'].'</td>
							<td align="center">'.$rklaim['tgl_lapor_klaim'].'</td>
							<td>'.$rklaim['id_peserta'].'</td>
							<td>'.$rklaim['nama'].'</td>
							<td>'.$rklaim['tgl_lahir'].'</td>
							<td>'.$rklaim['cabang'].'</td>
							<td>'.$rklaim['kredit_tgl'].'</td>
							<td>'.number_format($rklaim['kredit_jumlah'],2).'</td>
							<td>'.$rklaim['tgl_claim'].'</td>
							<td>'.number_format($rklaim['tuntutan_klaim'],2).'</td>
							<td><a title="Hapus data peserta ini dari list"  onClick="if(confirm(\'Data akan dihapus dari list, apakah anda sudah yakin ?\')){return true;}{return false;}" href="ajk_claim.php?d=email_notif_new&noe='.$_GET['noe'].'&delid='.$rklaim['klaim_id'].'"><img src="image/delete.png" width="30"></a></td>
							</tr>';
						if($jml_peserta>1){
							if($jml==$jml_peserta){
								$nama=substr($nama, 0,-2).' dan '.$rklaim['nama'];
							}else{
								$nama.=$rklaim['nama'].', ';
							}
						}else{
							$nama.=$rklaim['nama'].'';
						}

						$email=$email.$rklaim['nama'].' ('.$rklaim['nmproduk'].' '.bulanindo(date_format(date_create($rklaim['kredit_tgl']), 'm')).' '.date_format(date_create($rklaim['kredit_tgl']), 'Y').')<br>';
						$id_asuransi=$rklaim['id_asuransi'];
						$jml++;
					}
					echo '<tr><td colspan="22">';
					echo '</table>';
				}
				if(isset($id_asuransi)){
					$que="select email_klaim from fu_ajk_asuransi where id=".$id_asuransi;
					$res=mysql_query($que);
					$data_klaim=mysql_fetch_array($res);

					$data = json_decode($data_klaim['email_klaim'],TRUE);
					for($x=0;$x<=count($data['email'])-1;$x++){
						$to_email.=$data['email'][$x]['nama'].'('.$data['email'][$x]['email1'].'),';
					}
				}

				if($_POST['send_mail']){

					$cekdnpesertamail = mysql_fetch_array($database->doQuery('SELECT id_dn, id_cost, input_by FROM fu_ajk_peserta WHERE id_dn="'.$_sendmaildn['id'].'"'));
					$emailinput = $cekdnpesertamail['input_by'];
					$cekemailpeserta = mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$emailinput.'"'));
					$cekdnpeserta = $database->doQuery('SELECT id_dn, id_cost, id_polis, id_peserta, spaj, nama, usia, tgl_lahir, kredit_tgl, kredit_tenor, kredit_akhir,kredit_jumlah, totalpremi FROM fu_ajk_peserta WHERE id_dn="'.$_sendmaildn['id'].'" AND id_cost="'.$_sendmaildn['id_cost'].'"');
					$message = '<html><head><title>AJKOnline -  Pengajuan Klaim AJK PT Bank Bukopin, Tbk a.n. '.$nama.'</title></head><body>
								<b>Yth. Bapak Samad</b><br><br>

								Berdasarkan informasi yang kami dapat dari Bank Bukopin Jakarta, bersama ini perlu di sampaikan pengajuan klaim (data terlampir) untuk debitur di bawah ini :<br><br>
								'.$email.'

								<br>
								Adapun kelengkapan dokumen klaim akan segera di sampaikan, setelah kami menerima secara lengkap dan valid dari Bank Bukopin.<br>
								Demikian disampaikan. Atas perhatian dan kerjasamanya, diucapkan terima kasih<br><br>



								Hormat kami,<br><br><br>


								'.$q['nm_lengkap'].'</p>

								</body></html>';

							$query="update fu_ajk_klaim set tgl_lapor_klaim='".$futoday."' where no_email='".$_GET['noe']."' and type_klaim='Death' and del is null";
							mysql_query($query);

							$mail	= new PHPMailer; // call the class
							$mail->IsSMTP();
							$mail->Host = SMTP_HOST; //Hostname of the mail server
							$mail->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
							$mail->Username = SMTP_UNAME; //Username for SMTP authentication any valid email created in your domain
							$mail->Password = SMTP_PWORD; //Password for SMTP authentication
							$mail->AddReplyTo = SMTP_REPLYMAIL; //reply-to address
							$mail->debug = 1;
							$mail->SMTPSecure = "ssl";
							$mail->IsHTML(true);
							$mail->SetFrom ($q['email'], $q['nm_lengkap']); //From address of the mail
							$mail->Subject = "AJKOnline -  Pengajuan Klaim AJK PT Bank Bukopin, Tbk a.n. ".$nama; //Subject od your mail
							$mailsupervisorajk = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="" AND id_polis="" AND status="CLAIM" AND aktif="Y"');

							$que="select email_klaim from fu_ajk_asuransi where id=".$id_asuransi;
							$res=mysql_query($que);
							$data_klaim=mysql_fetch_array($res);
							$data = json_decode($data_klaim['email_klaim'],TRUE);
							$to_email='';
							for($x=0;$x<=count($data['email'])-1;$x++){
								//$to_email.=$data['email'][$x]['nama'].'('.$data['email'][$x]['email1'].'),';
								$mail->AddAddress($data['email'][$x]['nama'], $data['email'][$x]['email1']); //To address who will receive this email
							}

							while ($_mailsupervisorajk = mysql_fetch_array($mailsupervisorajk)) {
								$mail->AddAddress($_mailsupervisorajk['email'], $_mailsupervisorajk['nm_lengkap']); //To address who will receive this email
							}
							$mail->AddBCC($cekemailpeserta['email'], $cekemailpeserta['nm_lengkap']); //To address who will receive this email
							$mail->AddCC("IT@adonai.co.id");
							$mail->MsgHTML($message); //Put your body of the message you can place html code here
							$send = $mail->Send(); //Send the mails

				}
				if(isset($email)){

				echo '<p style="padding-left: 10%;
								padding-right: 10%;">
						To : '.substr($to_email,0,-1).'<br>
						Subject : AJKOnline -  Pengajuan Klaim AJK PT Bank Bukopin, Tbk a.n. '.$nama.'<br><br>
						<b>Yth. '.$data['pic'].'</b><br><br>

						Berdasarkan informasi yang kami dapat dari Bank Bukopin Jakarta, bersama ini perlu di sampaikan pengajuan klaim (data terlampir) untuk debitur di bawah ini :<br><br>
						'.$email.'

						<br>
						Adapun kelengkapan dokumen klaim akan segera di sampaikan, setelah kami menerima secara lengkap dan valid dari Bank Bukopin.<br>
						Demikian disampaikan. Atas perhatian dan kerjasamanya, diucapkan terima kasih<br><br>



						Hormat kami,<br><br><br>


						'.$q['nm_lengkap'].'<br>
								<form method="post" action="">
								<table border="0" width="60%" cellpadding="1" cellspacing="0" align="center">
								<tr>
								<td width="10%" align="center">
									<a target="_blank" href="e_surat.php?er=sendmailklaimawalpdf&noe='.$_REQUEST['noe'].'&user='.$q['nm_lengkap'].'"><img src="image/sendmail.png" width="20"><br>Send Mail</a>
									<!--<input type="submit" name="send_mail" value="Send Email" onClick="if(confirm(\'Data dokumen yang sudah di setujui tidak bisa di edit atau di hapus, apakah anda sudah yakin ?\')){return true;}{return false;}">-->
								</td>
								</tr>
								</table>

						</form></p><br>';
				}
	break;

	case "email_notif_new1" :
				function auto_number($nilai_default,$panjang_nomor,$nama_db,$nama_tb,$ulangi,$tanggal_tb)
				{
					if($ulangi=="")
					{
						$pjg_nomor=strlen($nilai_default)+1;
						$query="SELECT MAX(SUBSTRING($nama_tb,$pjg_nomor,$panjang_nomor))+1 AS nu FROM $nama_db where del='1'";
						$result=mysql_query($query);
						$data=mysql_fetch_array($result);
						$jml_nol=str_repeat("0", $panjang_nomor);
						if(is_null($data['nu']))
						{
							$hasil=$nilai_default.substr($jml_nol."1", -$panjang_nomor);
						}
						else
						{
							$hasil=$nilai_default.substr($jml_nol.$data['nu'], -$panjang_nomor);
						}
						return $hasil;
					}
					else
					{
						$pjg_nomor=strlen($nilai_default)+1;
						if($ulangi=='year'){
							$query="SELECT MAX(SUBSTRING($nama_tb,$pjg_nomor,$panjang_nomor))+1 AS nu FROM $nama_db where $ulangi($tanggal_tb)=$ulangi(curdate()) and del='1'";
						}elseif($ulangi=='month'){
							$query="SELECT MAX(SUBSTRING($nama_tb,$pjg_nomor,$panjang_nomor))+1 AS nu FROM $nama_db where $ulangi($tanggal_tb)=$ulangi(curdate()) and year($tanggal_tb)=year(curdate()) and del='1'";
						}elseif($ulangi=='day'){
							$query="SELECT MAX(SUBSTRING($nama_tb,$pjg_nomor,$panjang_nomor))+1 AS nu FROM $nama_db where $ulangi($tanggal_tb)=$ulangi(curdate()) and month($tanggal_tb)=month(curdate()) and year($tanggal_tb)=year(curdate()) and del='1'";
						}

						$result=mysql_query($query);
						$data=mysql_fetch_array($result);
						$jml_nol=str_repeat("0", $panjang_nomor);
						if(is_null($data['nu']))
						{
							$hasil=$nilai_default.substr($jml_nol."1", -$panjang_nomor);
						}
						else
						{
							$hasil=$nilai_default.substr($jml_nol.$data['nu'], -$panjang_nomor);
						}
						return $hasil;
					}
				}
				function bulanindo($bulan){
					if($bulan=='01'){
						$ls_namabulan =  'Januari';
					}elseif($bulan=='02'){
						$ls_namabulan =  'Februari';
					}elseif($bulan=='03'){
						$ls_namabulan =  'Maret';
					}elseif($bulan=='04'){
						$ls_namabulan =  'April';
					}elseif($bulan=='05'){
						$ls_namabulan =  'Mei';
					}elseif($bulan=='06'){
						$ls_namabulan =  'Juni';
					}elseif($bulan=='07'){
						$ls_namabulan =  'Juli';
					}elseif($bulan=='08'){
						$ls_namabulan =  'Agustus';
					}elseif($bulan=='09'){
						$ls_namabulan =  'September';
					}elseif($bulan=='10'){
						$ls_namabulan =  'Oktober';
					}elseif($bulan=='11'){
						$ls_namabulan =  'November';
					}elseif($bulan=='12'){
						$ls_namabulan =  'Desember';
					}
					return $ls_namabulan;
				}
				function _check_peserta($idpeserta){
					$result=mysql_query('
				SELECT
				fu_ajk_asuransi.`name` as asuransi,
				fu_ajk_klaim.no_email,
				fu_ajk_klaim.tgl_lapor_klaim,
				fu_ajk_cn.id_peserta,
				fu_ajk_peserta.nama,
				fu_ajk_peserta.tgl_lahir,
				fu_ajk_peserta.cabang,
				fu_ajk_peserta.kredit_tgl,
				fu_ajk_peserta.kredit_jumlah,
				fu_ajk_cn.tgl_claim,
				fu_ajk_cn.tuntutan_klaim
				FROM fu_ajk_cn
				INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
				INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
				INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta AND fu_ajk_cn.id = fu_ajk_peserta.id_klaim
				LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
				WHERE fu_ajk_cn.type_claim = "Death" AND fu_ajk_cn.confirm_claim <> "Pending" AND fu_ajk_cn.del IS NULL
				and no_email="" and fu_ajk_klaim.id_peserta="'.$idpeserta.'"
				');
					if(mysql_num_rows($result)>0){
						return false;
					}else{
						return  true;
					}
				}


				echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
		<tr><th colspan="8" width="100%" align="left" colspan="2">Modul Klaim - Email Awal</font></th><th width="5%" colspan="2"><a href="ajk_claim.php"><img src="image/back.png" width="20"></a></th></tr>
		</table><br />';
				//}

				if(isset($_GET['noe'])){

					echo '<table border="0" width="100%" cellpadding="5" cellspacing="1" bgcolor="#bde0e6">
			<tr><th width="3%">No</td>
			<th>Asuransi</td>
			<th>Produk</td>
			<th>No Email</td>
			<th>Tg Lapor Klaim</td>
			<th>ID Peserta</td>
			<th>Nama Peserta</td>
			<th>Tgl Lahir</td>
			<th>Cabang</td>
			<th>Tgl Akad</td>
			<th>Plafond</td>
			<th>Tgl Meninggal</td>
			<th>Tuntutan Klaim</td>
			</tr>';


					//$metklaim = $database->doQuery('SELECT * FROM fu_ajk_cn WHERE type_claim = "Death" '.$satu.' '.$dua.' '.$ernama.' '.$erdob.' AND del IS NULL ORDER BY id DESC LIMIT ' . $m . ' , 25');
					$metklaim = mysql_query('
				SELECT
				fu_ajk_klaim.id as klaim_id,
				fu_ajk_asuransi.id as id_asuransi,
				fu_ajk_asuransi.`name` as asuransi,
				fu_ajk_klaim.no_email,
				fu_ajk_klaim.no_polis,
				fu_ajk_polis.nmproduk,
				fu_ajk_klaim.tgl_lapor_klaim,
				fu_ajk_cn.id_peserta,
				fu_ajk_peserta.nama,
				fu_ajk_peserta.tgl_lahir,
				fu_ajk_peserta.cabang,
				fu_ajk_peserta.kredit_tgl,
				fu_ajk_peserta.kredit_jumlah,
				fu_ajk_cn.tgl_claim,
				fu_ajk_cn.tuntutan_klaim
				FROM fu_ajk_cn
				INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
				INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
				INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta AND fu_ajk_cn.id = fu_ajk_peserta.id_klaim
				INNER JOIN fu_ajk_polis ON fu_ajk_peserta.id_polis = fu_ajk_polis.id
				LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
				WHERE fu_ajk_cn.type_claim = "Death" AND fu_ajk_cn.confirm_claim <> "Pending" AND fu_ajk_cn.del IS NULL
				/*and (fu_ajk_klaim.tgl_lapor_klaim is null or fu_ajk_klaim.tgl_lapor_klaim="0000-00-00")*/
				and fu_ajk_klaim.no_email="'.$_GET['noe'].'"
				');
					$jml_peserta=mysql_num_rows($metklaim);
					$jml=1;
					while ($rklaim = mysql_fetch_array($metklaim)) {

						if(isset($rklaim['tgl_lapor_klaim']) and $rklaim['tgl_lapor_klaim'] != '0000-00-00'){
							$tgl_lapor =$rklaim['tgl_lapor_klaim'];
						}else{
							$tgl_lapor =date("Y-m-d");
						}

						if (($no % 2) == 1)	$objlass = 'tbl-odd'; else
							$objlass = 'tbl-even';
							echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
						<td align="center">'.(++$no).'</td>
						<td align="center">'.$rklaim['asuransi'].'</td>
						<td align="center">'.$rklaim['nmproduk'].'</td>
						<td align="center">'.$rklaim['no_email'].'</td>
						<td align="center">'.$tgl_lapor.'</td>
						<td>'.$rklaim['id_peserta'].'</td>
						<td>'.$rklaim['nama'].'</td>
						<td>'.$rklaim['tgl_lahir'].'</td>
						<td>'.$rklaim['cabang'].'</td>
						<td>'.$rklaim['kredit_tgl'].'</td>
						<td>'.number_format($rklaim['kredit_jumlah'],2).'</td>
						<td>'.$rklaim['tgl_claim'].'</td>
						<td>'.number_format($rklaim['tuntutan_klaim'],2).'</td>
						</tr>';
							if($jml_peserta>1){
								if($jml==$jml_peserta){
									$nama=substr($nama, 0,-2).' dan '.$rklaim['nama'];
								}else{
									$nama.=$rklaim['nama'].', ';
								}
							}else{
								$nama.=$rklaim['nama'].'';
							}
							$nopolis = ' '.$rklaim['no_polis'];
							//$email=$email.$jml.'. '.$rklaim['nama'].' ('.$rklaim['nmproduk'].' '.bulanindo(date_format(date_create($rklaim['kredit_tgl']), 'm')).' '.date_format(date_create($rklaim['kredit_tgl']), 'Y').')<br>';
							$email=$email.$jml.'. '.$rklaim['nama'].' ('.$rklaim['nmproduk'].$nopolis.')<br>';
							$id_asuransi=$rklaim['id_asuransi'];
							$jml++;
					}
					echo '<tr><td colspan="22">';
					echo '</table>';
				}
				if(isset($id_asuransi)){
					$que="select email_klaim from fu_ajk_asuransi where id=".$id_asuransi;
					$res=mysql_query($que);
					$data_klaim=mysql_fetch_array($res);

					$data = json_decode($data_klaim['email_klaim'],TRUE);
					for($x=0;$x<=count($data['email'])-1;$x++){
						$to_email.=$data['email'][$x]['nama'].'('.$data['email'][$x]['email1'].'),';

					}

				}

				if(isset($email)){

					echo '<p style="padding-left: 10%;
							padding-right: 10%;">
					To : '.substr($to_email,0,-1).'<br>
					Subject : AJKOnline -  Pengajuan Klaim AJK PT Bank Bukopin, Tbk a.n. '.$nama.'<br><br>
					<b>Yth. '.$data['pic'].'</b><br><br>

					Berdasarkan informasi yang kami dapat dari Bank Bukopin Jakarta, bersama ini perlu di sampaikan pengajuan klaim (data terlampir) untuk debitur di bawah ini :<br><br>
					'.$email.'

					<br>
					Adapun kelengkapan dokumen klaim akan segera di sampaikan, setelah kami menerima secara lengkap dan valid dari Bank Bukopin.<br>
					Demikian disampaikan. Atas perhatian dan kerjasamanya, diucapkan terima kasih<br><br>



					Hormat kami,<br><br><br>


					'.$q['nm_lengkap'].'<br>
							</p><br>
					';
				}
				echo '<table border="0" width="100%" align="center">
							<tr>
								<td align="center"><a target="_blank" href="e_surat.php?er=sendmailklaimawalpdf&noe='.$_REQUEST['noe'].'&user='.$q['nm_lengkap'].'"><img src="image/sendmail.png" width="20"><br>Send Mail</a></td>
							</tr>
							</table>';
	break;

	case "all_klaim":

		if(!isset($_REQUEST['tglinput'])){
			$dateku=date("Y-m-d");
			$tglmu='and date(fu_ajk_cn.approve_date)="'.$dateku.'"';
		}elseif($_REQUEST['tglinput']==""){
			$dateku='';
			$tglmu='';
		}else{
			$dateku=$_REQUEST['tglinput'];
			$tglmu='and date(fu_ajk_cn.approve_date)="'.$dateku.'"';

		}

		echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
			<tr><th colspan="8" width="100%" align="left" colspan="2">Modul Klaim - Meninggal</font></th><th width="5%" colspan="2"><a href="ajk_claim.php?d=dx"><img src="image/new.png" width="20"></a></th></tr>
			</table><br />';
		echo '<fieldset>
		<legend>Searching</legend>
		<form method="post" action="">
		<table border="0" width="60%" cellpadding="1" cellspacing="0" align="center">
	  	<tr><td width="10%" align="center">ID PESERTA</td>
			<td width="10%" align="center">NAMA</td>
			<td width="10%" align="center">DEBIT NOTE</td>
			<td width="10%" align="center">CREDIT NOTE</td>
			<td width="20%" align="center">TANGGAL APPROVE KLAIM</td>
		</tr>
		<tr>
			<td align="center"><input type="text" name="ridp" value="'.$_REQUEST['ridp'].'"></td>
			<td align="center"><input type="text" name="rnama" value="'.$_REQUEST['rnama'].'"></td>
			<td align="center"><input type="text" name="nodn" value="'.$_REQUEST['nodn'].'"></td>
		  	<td align="center"><input type="text" name="nocn" value="'.$_REQUEST['nocn'].'"></td>
		  	<td align="center">';
		print initCalendar();
		print calendarBox('tglinput', 'triger5', $dateku);
		echo '</td></tr>
	  	<tr><td colspan="6" align="center"><input type="submit" name="oppe" value="Searching" class="button"></td></tr>
		</table></form></fieldset>';
		//}
		echo '<table border="0" width="100%" cellpadding="5" cellspacing="1" bgcolor="#bde0e6">
		<tr><td bgcolor="#FFF"colspan="24"><a href="ajk_claim.php?d=listklaimpending">List Klaim Pending</a> <a href="ajk_claim.php?d=logdocument"> Log Document</a></td></tr>
		<tr><th width="3%">No</td>
		<th>Input Date</td>
		<th>No. Urut</td>
		<th>Asuransi</td>
		<th width="5%">ID Peserta</td>
		<th width="1%">ID DN</td>
		<th width="1%">ID CN</td>
		<th width="1%">Produk</td>
		<th>Nama Debitur</td>
		<th>Cabang</td>
		<th width="5%">Kredit Awal</td>
		<th width="5%">Kredit Akhir</td>
		<th width="5%">Tgl Klaim</td>
		<th width="1%">Tgl Approve</td>
		<th width="1%">Tenor</td>
		<th width="1%">Plafond</td>
		<!-- <th width="1%">Tenor (S,B)</td> --!>
		<th width="1%">Tuntutan Klaim</td>
		<th width="1%">Status</td>
		<th width="1%">Kadaluarsa</td>
		<th width="1%">Analisa Medical</td>
		<th width="1%">Analisa Legal</td>
		<th width="1%">Tgl Bayar Klaim</td>
		<th width="1%">jDoc</td>
		<th width="5%">Option</td>
		</tr>';
		if ($_REQUEST['ridp'])		{	$empat = 'AND fu_ajk_peserta.id_peserta LIKE "%' . $_REQUEST['ridp'] . '%"';		}
		if ($_REQUEST['nodn'])		{	$satu = 'AND fu_ajk_dn.dn_kode LIKE "%' . $_REQUEST['nodn'] . '%"';		}
		if ($_REQUEST['nocn'])		{	$dua = 'AND fu_ajk_cn.id_cn LIKE "%' . $_REQUEST['nocn'] . '%"';		}
		if ($_REQUEST['rnama'])		{	$tiga = 'AND fu_ajk_peserta.nama LIKE "%'.$_REQUEST['rnama'].'%"';
		$namanya = mysql_fetch_array($database->doQuery('SELECT id_klaim, status_peserta, id_peserta FROM fu_ajk_peserta WHERE id_klaim !="" AND status_peserta="Death" '.$tiga.' '));
		$ernama = 'AND fu_ajk_peserta.id_peserta = "' . $namanya['id_peserta'] . '"';
		}
		if ($_REQUEST['rdob'])		{	$empat = 'AND tgl_lahir = "' . $_REQUEST['rdob'] . '"';
		$dobnya = mysql_fetch_array($database->doQuery('SELECT id_klaim, status_peserta, id_peserta FROM fu_ajk_peserta WHERE id_klaim !="" AND status_peserta="Death" '.$empat.' '));
		$erdob = 'AND fu_ajk_peserta.tgl_lahir = "' . $dobnya['tgl_lahir'] . '"';
		}

		if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}

		//$metklaim = $database->doQuery('SELECT * FROM fu_ajk_cn WHERE type_claim = "Death" '.$satu.' '.$dua.' '.$ernama.' '.$erdob.' AND del IS NULL ORDER BY id DESC LIMIT ' . $m . ' , 25');
		$metklaim = $database->doQuery('SELECT
		fu_ajk_asuransi.`name` AS asuransi,
		fu_ajk_cn.id,
		fu_ajk_klaim.no_urut_klaim,
		fu_ajk_cn.id_cost,
		fu_ajk_cn.id_cn,
		fu_ajk_dn.dn_kode,
		fu_ajk_peserta.id_peserta,
		fu_ajk_peserta.nama,
		fu_ajk_peserta.tgl_lahir,
		fu_ajk_peserta.usia,
		fu_ajk_peserta.kredit_tgl,
		IF(fu_ajk_peserta.type_data="SPK", fu_ajk_peserta.kredit_tenor * 12, fu_ajk_peserta.kredit_tenor) AS tenor,
		fu_ajk_peserta.kredit_akhir,
		fu_ajk_peserta.kredit_jumlah,
		fu_ajk_cn.tgl_claim,
		fu_ajk_cn.premi,
		fu_ajk_cn.confirm_claim,
		fu_ajk_cn.total_claim,
		fu_ajk_cn.tuntutan_klaim,
		fu_ajk_cn.tgl_byr_claim,
		fu_ajk_polis.nmproduk,
		fu_ajk_peserta.cabang,
		fu_ajk_klaim.tgl_kirim_dokumen,
		fu_ajk_cn.tgl_bayar_asuransi,
		fu_ajk_cn.input_date,
		fu_ajk_cn.approve_date,
		fu_ajk_klaim.investigasi,
		IF(DATEDIFF(fu_ajk_cn.approve_date,fu_ajk_cn.tgl_claim) > 90,"kadaluarsa",
		if(if(DATEDIFF(fu_ajk_cn.tgl_claim,fu_ajk_cn.tgl_document_lengkap) is null,
				DATEDIFF(current_date(),fu_ajk_cn.approve_date),
				DATEDIFF(fu_ajk_cn.tgl_document_lengkap,fu_ajk_cn.approve_date)) > 150,"kadaluarsa","ga")) as kadaluarsa,
		fu_ajk_klaim_status.status_klaim
		FROM
		fu_ajk_cn
		INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
		INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
		INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
		INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta AND fu_ajk_cn.id = fu_ajk_peserta.id_klaim
		LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
		LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim.id_klaim_status = fu_ajk_klaim_status.id
		WHERE
		fu_ajk_cn.type_claim = "Death" AND fu_ajk_cn.confirm_claim <> "Pending" AND fu_ajk_cn.del IS NULL
		'.$tglmu.'
		'.$satu.' '.$dua.' '.$tiga.' '.$empat.'
		ORDER BY fu_ajk_cn.id DESC LIMIT ' . $m . ' , 25');
			//$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_cn WHERE id != "" AND type_claim="Death" '.$satu.' '.$dua.' '.$ernama.' '.$erdob.' AND del IS NULL'));
			$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(fu_ajk_cn.id_cn) FROM fu_ajk_cn
		INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
		INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
		INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta AND fu_ajk_cn.id = fu_ajk_peserta.id_klaim
		LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta

		WHERE fu_ajk_cn.type_claim = "Death" AND fu_ajk_cn.confirm_claim <> "Pending" AND fu_ajk_cn.del IS NULL

		'.$tglmu.' '.$satu.' '.$dua.' '.$tiga.'  '.$empat.''));
		$totalRows = $totalRows[0];
		$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
		while ($rklaim = mysql_fetch_array($metklaim)) {
			/*
			 $klaim_dn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn WHERE id="'.$rklaim['id_dn'].'"'));
			 $klaimpeserta = mysql_fetch_array($database->doQuery('SELECT id_cost, id_peserta, status_peserta, type_data, del, nama, tgl_lahir, kredit_tgl, kredit_tenor, kredit_akhir FROM fu_ajk_peserta WHERE id_cost="'.$rklaim['id_cost'].'" AND id_peserta="'.$rklaim['id_peserta'].'" AND status_peserta="Death" AND del IS NULL'));
			 $klaimcost = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$rklaim['id_cost'].'"'));
			 $klaimpolis = mysql_fetch_array($database->doQuery('SELECT id, id_cost, nmproduk, nopol FROM fu_ajk_polis WHERE id="'.$rklaim['id_nopol'].'"'));
			 $xAsuransi = mysql_fetch_array($database->doQuery('SELECT id, id_bank, id_polis, id_asuransi, id_polis_as, id_dn, id_peserta FROM fu_ajk_peserta_as WHERE id_bank="'.$rklaim['id_cost'].'" AND id_polis="'.$rklaim['id_nopol'].'" AND id_peserta="'.$rklaim['id_peserta'].'"'));
			 $xAsuransi_ = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_asuransi WHERE id="'.$xAsuransi['id_asuransi'].'"'));

			 $now = new T10DateCalc($klaimpeserta['kredit_tgl']);
			 $periodbulan = $now->compareDate($rklaim['tgl_claim']) / 30.4375;
			 $maj = ceil($periodbulan);

			 if ($klaimpeserta['type_data']=="SPK") {	$tenorSPK = $klaimpeserta['kredit_tenor'] * 12;	}else{	$tenorSPK = $klaimpeserta['kredit_tenor'];	}
			 */
			//$jdoc = mysql_num_rows($database->doQuery('SELECT id_pes, id_cost FROM fu_ajk_klaim_doc WHERE id_pes="'.$rklaim['id_peserta'].'" AND id_cost="'.$rklaim['id_cost'].'"'));
			$jdoc = mysql_num_rows($database->doQuery('
				SELECT DISTINCT fu_ajk_dokumenklaim_bank.id_bank, fu_ajk_dokumenklaim_bank.id_dok, fu_ajk_dokumenklaim.nama_dok
				FROM fu_ajk_dokumenklaim_bank
				INNER JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim_bank.id_dok = fu_ajk_dokumenklaim.id
				INNER JOIN fu_ajk_klaim_doc ON fu_ajk_klaim_doc.dokumen = fu_ajk_dokumenklaim_bank.id
				where fu_ajk_klaim_doc.id_pes="'.$rklaim['id_peserta'].'" AND fu_ajk_klaim_doc.id_cost="'.$rklaim['id_cost'].'"
				'));

			$mets = datediff($rklaim['kredit_tgl'], $rklaim['tgl_claim']);
			$metTgl = explode(",",$mets);
			//echo $metTgl[0].'-'.$metTgl[1].'-'.$metTgl[2].'<br />';
			if ($metTgl[2] > 0) {	$jumBulan = $metTgl[1] + 1;	}else{	$jumBulan = $metTgl[1];	}	//AKUMULASI BULAN THD JUMLAH HARI
			$klaimBlnJ = $metTgl[0] + $jumBulan;
			$klaimBlnS = $rklaim['tenor'] - $klaimBlnJ;
			//SETTING TGL BAYAR KLAIM//
			if ($rklaim['tgl_byr_claim']=="") {	$metbyrklaim = 'Belum dibayar';	}else{	$metbyrklaim = '<font color="blue">'._convertDate($rklaim['tgl_byr_claim']).'</font>';	}
			//SETTING TGL BAYAR KLAIM//

			//SETTING PEMBERITAHUAN INVESTIGASI
			if ($rklaim['confirm_claim']=="Investigasi") {
				$setInvestigasi ='<a title="Edit data ahli waris" href="ajk_claim.php?d=eInvKlaim&ido='.$rklaim['id'].'"><img src="image/edit.png" width="30"></a>';
			}else{
				$setInvestigasi ='<a title="Confirm Investigasi Klaim" href="ajk_claim.php?d=InvKlaim&id='.$rklaim['id'].'"><img src="image/doc_death.png" width="30"></a>';
			}
			$surat_pem='';
			if(!is_null($rklaim['tgl_kirim_dokumen']) && $rklaim['tgl_kirim_dokumen']!='0000-00-00' && datediff($rklaim['tgl_kirim_dokumen'], $futoday)>=7){
				$surat_pem='<a title="Print Surat Persetujuan Ke asuransi" target="_blank" href="e_report_klaim.php?er=setuju_as&id='.$rklaim['id'].'"><img src="image/print.png" width="30"></a>';
			}

			if(!is_null($rklaim['tgl_kirim_dokumen']) && $rklaim['tgl_kirim_dokumen']!='0000-00-00' && datediff($rklaim['tgl_kirim_dokumen'], $futoday)>=14){
				$surat_pem='<a title="Print Surat Realisasi Ke asuransi '.$rklaim['tgl_bayar_asuransi'].'"  target="_blank" href="e_report_klaim.php?er=realisasi_as&id='.$rklaim['id'].'"><img src="image/print.png" width="30"></a>';
			}

			$status_klaim='';

			if($rklaim['kadaluarsa']=="kadaluarsa"){
				$status_klaim='Klaim Kadaluarsa';
			}else{
				$status_klaim=' - ';
			}

			$optditolak = '';
			// if($rklaim['status_klaim'] == "DITOLAK"){
				$optditolak= '<a target="_blank" href="ajk_claim.php?d=klaimtolak&id='.$rklaim['id'].'"><img src="image/plus.png" width="20"></a>';
			//}

			$optinvestigasi = '';
			if($rklaim['investigasi'] == "Y"){
				$optinvestigasi = '<a target="_blank" href="e_report_klaim.php?er=_invklaim&idC='.$rklaim['id'].'"><img src="image/dnmember.png" width="20"></a>';
			}

			$status_dok='<a target="_blank" href="ajk_claim.php?d=suratpengajuan&id='.$rklaim['id'].'">'.$rklaim['status_klaim'].'</a>';

			//CEK ANALISA MEDICAL & LEGAL
			$query = "SELECT *
								FROM fu_ajk_analisa_klaim
								WHERE id_peserta = '".$rklaim['id_peserta']."'";
			$qmedical = mysql_query($query." and type_analisa = 'medical'");
			$qlegal = mysql_query($query." and type_analisa = 'legal'");
			$qmedical_ = mysql_num_rows($qmedical);
			if($qmedical_ == 0 and $q['status']=="CLAIM"){
				$analisa = '<a href="ajk_claim.php?d=setanalisamedical&id='.$rklaim['id_peserta'].'" title="Analisa Medical" onClick="if(confirm(\'Klaim ini akan diperiksa oleh AMK dan Tim Legal, Apakah anda setuju ?\')){return true;}{return false;}"><img src="image/analisamed.png" width="25"></a>';
			}else{
				$analisa = '';
			}
			$qmedicalR = mysql_fetch_array($qmedical);
			$qlegalR = mysql_fetch_array($qlegal);
			$noteM = $qmedicalR['note'];
			$noteL = $qlegalR['note'];
			if($noteM != ""){
				$imageM = "pdf-red.png";
			}elseif($noteM == ""){
				$imageM = "pdf-green.png";
			}
			if($noteL != ""){
				$imageL = "pdf-red.png";
			}elseif($noteL == ""){
				$imageL = "pdf-green.png";
			}
			if($qmedicalR['analisa_bank'] != "" ){
				$a_bank = '<a target="_blank" href="e_report_klaim.php?er=_analisaklaim&idC='.$rklaim['id'].'&tp=bank" title="Analisa Bank&#013;'.$noteM.'"> <img src="image/'.$imageM.'" width="25"></a>';
			}else{
				$a_bank = '';
			}

			if($qmedicalR['analisa_asuransi'] != ""){
				$a_asuransi = '<a target="_blank" href="e_report_klaim.php?er=_analisaklaim&idC='.$rklaim['id'].'&tp=as" title="Analisa Asuransi&#013;'.$noteM.'"> <img src="image/'.$imageM.'" width="25"></a>';
			}else{
				$a_asuransi = '';
			}

			if($qlegalR['analisa_bank'] != "" ){
				$a_bank_l = '<a target="_blank" href="e_report_klaim.php?er=_analisaklaim&tipe=L&idC='.$rklaim['id'].'&tp=bank" title="Analisa Bank&#013;'.$noteL.'"> <img src="image/'.$imageL.'" width="25"></a>';
			}else{
				$a_bank_l = '';
			}

			if($qlegalR['analisa_asuransi'] != ""){
				$a_asuransi_l = '<a target="_blank" href="e_report_klaim.php?er=_analisaklaim&tipe=L&idC='.$rklaim['id'].'&tp=as" title="Analisa Asuransi&#013;'.$noteL.'"> <img src="image/'.$imageL.'" width="25"></a>';
			}else{
				$a_asuransi_l = '';
			}

			//SETTING PEMBERITAHUAN INVESTIGASI
			if (($no % 2) == 1)	$objlass = 'tbl-odd'; else				$objlass = 'tbl-even';
			echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
			<td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
			<td>'.$rklaim['input_date'].'</td>
			<td>'.$rklaim['no_urut_klaim'].'</td>
			<td align="center">'.$rklaim['asuransi'].'</td>
			<td align="center"><a href="../aajk_report.php?er=_erKlaim&idC='.$rklaim['id'].'" target="_blank">'.$rklaim['id_peserta'].'</a></td>
			<td align="center">'.substr($rklaim['dn_kode'],3).'</td>
			<td align="center"><a href="../aajk_report.php?er=_cnDeath&idC='.$rklaim['id'].'" target="_blank">'.substr($rklaim['id_cn'],3).'</a></td>
			<td align="center">'.$rklaim['nmproduk'].'</td>
			<td><a title="Worksheet Klaim" href="ajk_claim.php?d=wklaim&id='.$rklaim['id'].'" target="_blank">'.$rklaim['nama'].'</a></td>
			<td align="center">'.$rklaim['cabang'].'</td>
			<td align="center">'._convertDate($rklaim['kredit_tgl']).'</td>
			<td align="center">'._convertDate($rklaim['kredit_akhir']).'</td>
			<td align="center">'._convertDate($rklaim['tgl_claim']).'</td>
			<td align="center">'._convertDate($rklaim['approve_date']).'</td>
			<td align="center">'.$rklaim['tenor'].'</td>
			<td align="center">'.duit($rklaim['kredit_jumlah']).'</td>
			<!--  <td align="center">'.$klaimBlnS.','.$klaimBlnJ.'</td> --!>
			<td align="right">'.duit($rklaim['tuntutan_klaim']).'</td>
			<td align="center">'.$status_dok.'</td>
			<td align="center">'.$status_klaim.'</td>
			<td align="center">'.$a_bank.' '.$a_asuransi.'</td>
			<td align="center">'.$a_bank_l.' '.$a_asuransi_l.'</td>
			<td align="center">'.$metbyrklaim.'</td>
			<td align="center">'.$jdoc.'</td>
			<td align="center">
					<a target="_blank" href="ajk_claim.php?d=dmonitoring&id='.$rklaim['id'].'"><img src="image/edit3.png"></a>
			'.$analisa.'
			'.$optditolak.'
			'.$surat_pem.'
			'.$optinvestigasi.'
			</td>
			</tr>';
		}
		echo '<tr><td colspan="22">';
		echo createPageNavigations($file = 'ajk_claim.php?d=all_klaim&tglinput='.$_REQUEST['tglinput'], $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
		echo '<b>Total Data Meninggal: <u>' . $totalRows . '</u></b></td></tr>';
		echo '</table>';
		;
	break;

	case "sendmail":
		/*
		function auto_number($nilai_default,$panjang_nomor,$nama_db,$nama_tb,$ulangi,$tanggal_tb){
			if($ulangi=="")
			{
				$pjg_nomor=strlen($nilai_default)+1;
				$query="SELECT MAX(SUBSTRING($nama_tb,$pjg_nomor,$panjang_nomor))+1 AS nu FROM $nama_db";
				$result=mysql_query($query);
				$data=mysql_fetch_array($result);
				$jml_nol=str_repeat("0", $panjang_nomor);
				if(is_null($data['nu']))
				{
					$hasil=$nilai_default.substr($jml_nol."1", -$panjang_nomor);
				}
				else
				{
					$hasil=$nilai_default.substr($jml_nol.$data['nu'], -$panjang_nomor);
				}
				return $hasil;
			}
			else
			{
				$pjg_nomor=strlen($nilai_default)+1;
				if($ulangi=='year'){
					$query="SELECT MAX(SUBSTRING($nama_tb,$pjg_nomor,$panjang_nomor))+1 AS nu FROM $nama_db where $ulangi($tanggal_tb)=$ulangi(curdate())";
				}elseif($ulangi=='month'){
					$query="SELECT MAX(SUBSTRING($nama_tb,$pjg_nomor,$panjang_nomor))+1 AS nu FROM $nama_db where $ulangi($tanggal_tb)=$ulangi(curdate()) and year($tanggal_tb)=year(curdate())";
				}elseif($ulangi=='day'){
					$query="SELECT MAX(SUBSTRING($nama_tb,$pjg_nomor,$panjang_nomor))+1 AS nu FROM $nama_db where $ulangi($tanggal_tb)=$ulangi(curdate()) and month($tanggal_tb)=month(curdate()) and year($tanggal_tb)=year(curdate())";
				}

				$result=mysql_query($query);
				$data=mysql_fetch_array($result);
				$jml_nol=str_repeat("0", $panjang_nomor);
				if(is_null($data['nu']))
				{
					$hasil=$nilai_default.substr($jml_nol."1", -$panjang_nomor);
				}
				else
				{
					$hasil=$nilai_default.substr($jml_nol.$data['nu'], -$panjang_nomor);
				}
				return $hasil;
			}
		}
		$noe=auto_number(date('Ymd'), 3, "fu_ajk_klaim", "no_email", "day", "tgl_lapor_klaim");
		*/

		$qno_email = mysql_query("SELECT no_email,id_asuransi,tgl_lapor_klaim
															FROM fu_ajk_klaim
																	 INNER JOIN fu_ajk_peserta_as
																	 ON fu_ajk_peserta_as.id_peserta = fu_ajk_klaim.id_peserta
															WHERE DATE_FORMAT(CURDATE(),'%Y%m%d') = LEFT(no_email,8) AND
																		id_asuransi = (SELECT id_asuransi
																										FROM fu_ajk_peserta_as
																										WHERE id_peserta = '".$_REQUEST['id_peserta']."') AND
																		(tgl_lapor_klaim = '0000-00-00' or tgl_lapor_klaim is NULL)
															GROUP BY no_email,id_asuransi,tgl_lapor_klaim");

		if(mysql_num_rows($qno_email)>0){
			$qemail = mysql_fetch_array($qno_email);
			$noe = $qemail['no_email'];
		}else{
			$noe = date("Ymdhms");
		}

		$query="update fu_ajk_klaim set no_email='".$noe."' where id_peserta='".$_REQUEST['id_peserta']."' and type_klaim='Death' and del is null";
		mysql_query($query);
		header("location:ajk_claim.php?d=email_notif_new1&noe=".$noe);
	break;

default:

	if(!isset($_REQUEST['tglinput'])){
		$dateku=date("Y-m-d");
		$tglmu='and date(fu_ajk_cn.approve_date)="'.$dateku.'"';
	}elseif($_REQUEST['tglinput']==""){
		$dateku='';
		$tglmu='';
	}else{
		$dateku=$_REQUEST['tglinput'];
		$tglmu='and date(fu_ajk_cn.approve_date)="'.$dateku.'"';

	}

echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
		<tr><th colspan="8" width="100%" align="left" colspan="2">Modul Klaim - Meninggal</font></th><th width="5%" colspan="2"><a href="ajk_claim.php?d=dx"><img src="image/new.png" width="20"></a></th></tr>
		</table><br />';
echo '<fieldset>
	<legend>Searching</legend>
	<form method="post" action="">
	<table border="0" width="60%" cellpadding="1" cellspacing="0" align="center">
  	<tr><td width="10%" align="center">ID PESERTA</td>
		<td width="10%" align="center">NAMA</td>
		<td width="10%" align="center">DEBIT NOTE</td>
		<td width="10%" align="center">CREDIT NOTE</td>
		<td width="20%" align="center">TANGGAL APPROVE KLAIM</td>
	</tr>
	<tr>
		<td align="center"><input type="text" name="ridp" value="'.$_REQUEST['ridp'].'"></td>
		<td align="center"><input type="text" name="rnama" value="'.$_REQUEST['rnama'].'"></td>
		<td align="center"><input type="text" name="nodn" value="'.$_REQUEST['nodn'].'"></td>
	  	<td align="center"><input type="text" name="nocn" value="'.$_REQUEST['nocn'].'"></td>
	  	<td align="center">';
		print initCalendar();
		print calendarBox('tglinput', 'triger5', $dateku);
		echo '</td></tr>
  	<tr><td colspan="6" align="center"><input type="submit" name="oppe" value="Searching" class="button"></td></tr>
	</table></form></fieldset>';
//}
echo '<table border="0" width="100%" cellpadding="5" cellspacing="1" bgcolor="#bde0e6">
	<tr><td bgcolor="#FFF"colspan="24"><a href="ajk_claim.php?d=listklaimpending">List Klaim Pending</a> <a href="ajk_claim.php?d=logdocument"> Log Document</a></td></tr>
	<tr><th width="3%">No</td>
	<th>Input Date</td>
	<th>No. Urut</td>
	<th>Asuransi</td>
	<th width="5%">ID Peserta</td>
	<th width="7%">Kirim Email Cabang</td>
	<th width="1%">ID DN</td>
	<th width="1%">ID CN</td>
	<th width="1%">Produk</td>
	<th>Nama Debitur</td>
	<th>Cabang</td>
	<th width="5%">Kredit Awal</td>
	<th width="5%">Kredit Akhir</td>
	<th width="5%">Tgl Klaim</td>
	<th width="1%">Tgl Approve</td>
	<th width="1%">Tenor</td>
	<!-- <th width="1%">Tenor (S,B)</td> --!>
	<!--<th width="1%">Tuntutan Klaim</td>-->
	<th width="1%">Plafond</td>
	<th width="1%">Status</td>
	<th width="1%">Kadaluarsa</td>
	<th width="1%">Tgl Bayar Klaim</td>
	<th width="1%">jDoc</td>
	<th width="5%">Option</td>
	<th width="5%">Kirim Email</td>
	<!--<th width="5%">Resend Email</td>-->
	<th width="5%">Investigasi</td>
	</tr>';
if ($_REQUEST['ridp'])		{	$empat = 'AND fu_ajk_peserta.id_peserta LIKE "%' . $_REQUEST['ridp'] . '%"';		}
if ($_REQUEST['nodn'])		{	$satu = 'AND fu_ajk_dn.dn_kode LIKE "%' . $_REQUEST['nodn'] . '%"';		}
if ($_REQUEST['nocn'])		{	$dua = 'AND fu_ajk_cn.id_cn LIKE "%' . $_REQUEST['nocn'] . '%"';		}
if ($_REQUEST['rnama'])		{	$tiga = 'AND fu_ajk_peserta.nama LIKE "%'.$_REQUEST['rnama'].'%"';
	$namanya = mysql_fetch_array($database->doQuery('SELECT id_klaim, status_peserta, id_peserta FROM fu_ajk_peserta WHERE id_klaim !="" AND status_peserta="Death" '.$tiga.' '));
	$ernama = 'AND fu_ajk_peserta.id_peserta = "' . $namanya['id_peserta'] . '"';
}
if ($_REQUEST['rdob'])		{	$empat = 'AND tgl_lahir = "' . $_REQUEST['rdob'] . '"';
	$dobnya = mysql_fetch_array($database->doQuery('SELECT id_klaim, status_peserta, id_peserta FROM fu_ajk_peserta WHERE id_klaim !="" AND status_peserta="Death" '.$empat.' '));
	$erdob = 'AND fu_ajk_peserta.tgl_lahir = "' . $dobnya['tgl_lahir'] . '"';
}

if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}

//$metklaim = $database->doQuery('SELECT * FROM fu_ajk_cn WHERE type_claim = "Death" '.$satu.' '.$dua.' '.$ernama.' '.$erdob.' AND del IS NULL ORDER BY id DESC LIMIT ' . $m . ' , 25');
$metklaim = $database->doQuery('SELECT
fu_ajk_asuransi.`name` AS asuransi,
fu_ajk_cn.id,
fu_ajk_klaim.no_urut_klaim,
fu_ajk_cn.id_cost,
fu_ajk_cn.id_cn,
fu_ajk_dn.dn_kode,
fu_ajk_peserta.id_peserta,
fu_ajk_peserta.nama,
fu_ajk_peserta.tgl_lahir,
fu_ajk_peserta.usia,
fu_ajk_peserta.kredit_tgl,
IF(fu_ajk_peserta.type_data="SPK", fu_ajk_peserta.kredit_tenor * 12, fu_ajk_peserta.kredit_tenor) AS tenor,
fu_ajk_peserta.kredit_akhir,
fu_ajk_peserta.kredit_jumlah,
fu_ajk_cn.tgl_claim,
fu_ajk_cn.premi,
fu_ajk_cn.confirm_claim,
fu_ajk_cn.total_claim,
fu_ajk_cn.tuntutan_klaim,
fu_ajk_cn.tgl_byr_claim,
fu_ajk_polis.nmproduk,
fu_ajk_peserta.cabang,
fu_ajk_klaim.tgl_kirim_dokumen,
fu_ajk_klaim.no_email,
fu_ajk_klaim.tgl_lapor_klaim,
fu_ajk_cn.tgl_bayar_asuransi,
fu_ajk_cn.input_date,
fu_ajk_cn.approve_date,
IF(DATEDIFF(fu_ajk_cn.approve_date,fu_ajk_cn.tgl_claim) > 90,"kadaluarsa",
if(if(DATEDIFF(fu_ajk_cn.tgl_claim,fu_ajk_cn.tgl_document_lengkap) is null,
		DATEDIFF(current_date(),fu_ajk_cn.approve_date),
		DATEDIFF(fu_ajk_cn.tgl_document_lengkap,fu_ajk_cn.approve_date)) > 150,"kadaluarsa","ga")) as kadaluarsa,
fu_ajk_klaim_status.status_klaim
FROM
fu_ajk_cn
INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta AND fu_ajk_cn.id = fu_ajk_peserta.id_klaim
LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim.id_klaim_status = fu_ajk_klaim_status.id
WHERE
fu_ajk_cn.type_claim = "Death" AND fu_ajk_cn.confirm_claim <> "Pending" AND fu_ajk_cn.del IS NULL
AND (fu_ajk_klaim.id_klaim_status=2 or fu_ajk_klaim.id_klaim_status=3 or fu_ajk_klaim.id_klaim_status=8)
'.$tglmu.'
'.$satu.' '.$dua.' '.$tiga.' '.$empat.'
ORDER BY fu_ajk_cn.approve_date ASC,fu_ajk_cn.id ASC LIMIT ' . $m . ' , 25');
//$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_cn WHERE id != "" AND type_claim="Death" '.$satu.' '.$dua.' '.$ernama.' '.$erdob.' AND del IS NULL'));
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(fu_ajk_cn.id_cn) FROM fu_ajk_cn
INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta AND fu_ajk_cn.id = fu_ajk_peserta.id_klaim
LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta

WHERE fu_ajk_cn.type_claim = "Death" AND fu_ajk_cn.confirm_claim <> "Pending" AND fu_ajk_cn.del IS NULL
AND (fu_ajk_klaim.id_klaim_status=2 or fu_ajk_klaim.id_klaim_status=3 or fu_ajk_klaim.id_klaim_status=8)

'.$tglmu.' '.$satu.' '.$dua.' '.$tiga.'  '.$empat.''));
$totalRows = $totalRows[0];
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while ($rklaim = mysql_fetch_array($metklaim)) {
/*
$klaim_dn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn WHERE id="'.$rklaim['id_dn'].'"'));
$klaimpeserta = mysql_fetch_array($database->doQuery('SELECT id_cost, id_peserta, status_peserta, type_data, del, nama, tgl_lahir, kredit_tgl, kredit_tenor, kredit_akhir FROM fu_ajk_peserta WHERE id_cost="'.$rklaim['id_cost'].'" AND id_peserta="'.$rklaim['id_peserta'].'" AND status_peserta="Death" AND del IS NULL'));
$klaimcost = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$rklaim['id_cost'].'"'));
$klaimpolis = mysql_fetch_array($database->doQuery('SELECT id, id_cost, nmproduk, nopol FROM fu_ajk_polis WHERE id="'.$rklaim['id_nopol'].'"'));
$xAsuransi = mysql_fetch_array($database->doQuery('SELECT id, id_bank, id_polis, id_asuransi, id_polis_as, id_dn, id_peserta FROM fu_ajk_peserta_as WHERE id_bank="'.$rklaim['id_cost'].'" AND id_polis="'.$rklaim['id_nopol'].'" AND id_peserta="'.$rklaim['id_peserta'].'"'));
$xAsuransi_ = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_asuransi WHERE id="'.$xAsuransi['id_asuransi'].'"'));

$now = new T10DateCalc($klaimpeserta['kredit_tgl']);
$periodbulan = $now->compareDate($rklaim['tgl_claim']) / 30.4375;
$maj = ceil($periodbulan);

if ($klaimpeserta['type_data']=="SPK") {	$tenorSPK = $klaimpeserta['kredit_tenor'] * 12;	}else{	$tenorSPK = $klaimpeserta['kredit_tenor'];	}
*/
//$jdoc = mysql_num_rows($database->doQuery('SELECT id_pes, id_cost FROM fu_ajk_klaim_doc WHERE id_pes="'.$rklaim['id_peserta'].'" AND id_cost="'.$rklaim['id_cost'].'"'));
	$jdoc = mysql_num_rows($database->doQuery('
			SELECT DISTINCT fu_ajk_dokumenklaim_bank.id_bank, fu_ajk_dokumenklaim_bank.id_dok, fu_ajk_dokumenklaim.nama_dok
			FROM fu_ajk_dokumenklaim_bank
			INNER JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim_bank.id_dok = fu_ajk_dokumenklaim.id
			INNER JOIN fu_ajk_klaim_doc ON fu_ajk_klaim_doc.dokumen = fu_ajk_dokumenklaim_bank.id
			where fu_ajk_klaim_doc.id_pes="'.$rklaim['id_peserta'].'" AND fu_ajk_klaim_doc.id_cost="'.$rklaim['id_cost'].'"
			'));

	$mets = datediff($rklaim['kredit_tgl'], $rklaim['tgl_claim']);
	$metTgl = explode(",",$mets);
	//echo $metTgl[0].'-'.$metTgl[1].'-'.$metTgl[2].'<br />';
	if ($metTgl[2] > 0) {	$jumBulan = $metTgl[1] + 1;	}else{	$jumBulan = $metTgl[1];	}	//AKUMULASI BULAN THD JUMLAH HARI
	$klaimBlnJ = $metTgl[0] + $jumBulan;
	$klaimBlnS = $rklaim['tenor'] - $klaimBlnJ;
	//SETTING TGL BAYAR KLAIM//
	if ($rklaim['tgl_byr_claim']=="") {	$metbyrklaim = 'Belum dibayar';	}else{	$metbyrklaim = '<font color="blue">'._convertDate($rklaim['tgl_byr_claim']).'</font>';	}
	//SETTING TGL BAYAR KLAIM//

	//SETTING PEMBERITAHUAN INVESTIGASI
	if ($rklaim['confirm_claim']=="Investigasi") {
	$setInvestigasi ='<a title="Edit data ahli waris" href="ajk_claim.php?d=eInvKlaim&ido='.$rklaim['id'].'"><img src="image/edit.png" width="30"></a>';
	}else{
	$setInvestigasi ='<a title="Confirm Investigasi Klaim" href="ajk_claim.php?d=InvKlaim&id='.$rklaim['id'].'"><img src="image/doc_death.png" width="30"></a>';
	}
	$surat_pem='';
	if(!is_null($rklaim['tgl_kirim_dokumen']) && $rklaim['tgl_kirim_dokumen']!='0000-00-00' && datediff($rklaim['tgl_kirim_dokumen'], $futoday)>=7){
		$surat_pem='<a title="Print Surat Persetujuan Ke asuransi" target="_blank" href="e_report_klaim.php?er=setuju_as&id='.$rklaim['id'].'"><img src="image/print.png" width="30"></a>';
	}

	if(isset($rklaim['tgl_lapor_klaim']) and $rklaim['tgl_lapor_klaim'] != '0000-00-00'){
		$sendemail = $rklaim['tgl_lapor_klaim'];
	}else{
		if($rklaim['no_email'] == ''){
			//$sendemail = '<a href="ajk_claim.php?d=sendmail&id_peserta='.$rklaim['id_peserta'].'"><img src="image/sendmail.png" width="30"></a>';
			$sendemail = '<a href="ajk_claim.php?d=sendmail&id_peserta='.$rklaim['id_peserta'].'" target="_blank">Create</a>';
		}else{
			$sendemail = '<a href="ajk_claim.php?d=email_notif_new1&noe='.$rklaim['no_email'].'" target="_blank"><img src="image/sendmail.png" width="30"></a>';
		}
	}

	if(!is_null($rklaim['tgl_kirim_dokumen']) && $rklaim['tgl_kirim_dokumen']!='0000-00-00' && datediff($rklaim['tgl_kirim_dokumen'], $futoday)>=14){
		$surat_pem='<a title="Print Surat Realisasi Ke asuransi '.$rklaim['tgl_bayar_asuransi'].'"  target="_blank" href="e_report_klaim.php?er=realisasi_as&id='.$rklaim['id'].'"><img src="image/print.png" width="30"></a>';
	}

	$status_kadaluarsa='';
	if($rklaim['kadaluarsa']=="kadaluarsa"){
		$status_kadaluarsa='Klaim Kadaluarsa';
	}else{
		$status_kadaluarsa=' - ';
	}

	$status_dok='<a target="_blank" href="ajk_claim.php?d=suratpengajuan&id='.$rklaim['id'].'">'.$rklaim['status_klaim'].'</a>';
	$qhisemail = mysql_fetch_array(mysql_query("select *,DATE_FORMAT(tgl_kirim,'%d-%m-%Y')as tgl_kirim_surat from fu_ajk_his_kirim_surat where keytable = '".$rklaim['id']."' and surat = 'Pelaporan Klaim Cabang' order by id desc limit 1"));

	if(isset($qhisemail['tgl_kirim'])){
		$emailcabang = $qhisemail['tgl_kirim_surat'];
	}else{
		$emailcabang = '<img src="../image/print.png" width="23">';
	}

	//SETTING PEMBERITAHUAN INVESTIGASI
	if (($no % 2) == 1)	$objlass = 'tbl-odd'; else				$objlass = 'tbl-even';
	echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		<td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
		<td>'.$rklaim['input_date'].'</td>
		<td>'.$rklaim['no_urut_klaim'].'</td>
		<td align="center">'.$rklaim['asuransi'].'</td>
		<td align="center"><a href="../aajk_report.php?er=_erKlaim&idC='.$rklaim['id'].'" target="_blank">'.$rklaim['id_peserta'].'</a></td>
		<td align="center"><a title="Kirim Email Ke Cabang" href="e_surat.php?er=sendmailklaimawalcabangpdf&idC='.$rklaim['id'].'&user='.$q['nm_lengkap'].'" target="_blank">'.$emailcabang.'</a></td>
		<td align="center">'.substr($rklaim['dn_kode'],3).'</td>
		<td align="center"><a href="../aajk_report.php?er=_cnDeath&idC='.$rklaim['id'].'" target="_blank">'.substr($rklaim['id_cn'],3).'</a></td>
		<td align="center">'.$rklaim['nmproduk'].'</td>
		<td><a title="Worksheet Klaim" href="ajk_claim.php?d=wklaim&id='.$rklaim['id'].'" target="_blank">'.$rklaim['nama'].'</a></td>
		<td align="center">'.$rklaim['cabang'].'</td>
		<td align="center">'._convertDate($rklaim['kredit_tgl']).'</td>
		<td align="center">'._convertDate($rklaim['kredit_akhir']).'</td>
		<td align="center">'._convertDate($rklaim['tgl_claim']).'</td>
		<td align="center">'.$rklaim['approve_date'].'</td>
		<td align="center">'.$rklaim['tenor'].'</td>
		<!--  <td align="center">'.$klaimBlnS.','.$klaimBlnJ.'</td> --!>
		<!--<td align="right">'.duit($rklaim['tuntutan_klaim']).'</td>-->
		<td align="right">'.duit($rklaim['kredit_jumlah']).'</td>
		<td align="center">'.$status_dok.'</td>
		<td align="center">'.$status_kadaluarsa.'</td>
		<td align="center">'.$metbyrklaim.'</td>
		<td align="center">'.$jdoc.'</td>
		<td align="center"><a title="edit data klaim" target="_blank" href="ajk_claim.php?d=editklaim&id='.$rklaim['id'].'"><img src="image/edit3.png"></a>&nbsp;
						   <a title="upload dokumen klaim" href="ajk_claim.php?d=upl_dok&id='.$rklaim['id'].'"><img src="../image/upload_movement.png" width="23"></a>
		'.$surat_pem.'
		</td>
		<td align="center">'.$sendemail.'</td>
		<!--<td align="center"><a href="e_surat.php?er=resendmailklaimawalpdf&noe='.$rklaim['no_email'].'&user='.$q['nm_lengkap'].'" target="_blank">Resend</a></td>-->
		<td align="center">'.$setInvestigasi.'</td>
		</tr>';
}
echo '<tr><td colspan="22">';
echo createPageNavigations($file = 'ajk_claim.php?tglinput='.$_REQUEST['tglinput'].'&ridp='.$_REQUEST['ridp'].'&rnama='.$_REQUEST['rnama'].'&nodn='.$_REQUEST['nodn'].'&nocn='.$_REQUEST['nocn'], $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
echo '<b>Total Data Meninggal: <u>' . $totalRows . '</u></b></td></tr>';
echo '</table>';
		;
} // switch

?>
