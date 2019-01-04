<?php
include_once ("ui.php");
connect();
$datetime_ = date("YmdGis");
function _convertDate2($date)
{
	$thnbyr = substr(date(Y),0,2);
	if (empty($date))	return null;
	$date = explode("/", $date);	return
	$thnbyr.$date[2] . '-' . $date[1] . '-' . $date[0];
}
if (isset($_SESSION['nm_user'])) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));	}
switch ($_REQUEST['r']) {
case "viewPaid":
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%" enctype="multipart/form-data">
	  <tr><th align="left">Modul Upload Data Pembayaran Asuransi</th><th align="left" width="1%"><a href="ajk_paid_upl.php?r=paidupload"><img src="image/Backward-64.png" width="20"></a></th></tr>
	  </table>';
	$data = new Spreadsheet_Excel_Reader($_FILES['filepembayaran']['tmp_name']);
	$metPaidFile = $datetime_.'_'.$q['nm_user'].'_'.$_FILES['filepembayaran']['name'];		//Nama File
	$metPaidFile_temp = $datetime_.'_'.$q['nm_user'].'_'.$_FILES['filepembayaran']['temp_name'];		//Nama File
	$hasildata = $data->rowcount($sheet_index = 0);
echo '<table class="table table-condensed table-striped table-bordered table-hover no-margin" width="100%">
		<tr><th width="1%">NO</th>
			<th width="10%">ID Peserta</th>
			<th width="10%">Produk</th>
			<th width="10%">Nama</th>
			<th>Tgl Lahir</th>
			<th width="10%">Usia</th>
			<th width="10%">Plafond</th>
			<th width="10%">Tenor</th>
			<th width="10%">MPP</th>
			<th width="10%">Rate Asuransi</th>
			<th width="10%">Premi Asuransi</th>
			<th width="10%">Tgl Bayar</th>
			<th width="10%">Nilai Bayar</th>
			<th width="10%">Status</th>
		</tr></thead><tbody>';

		$temp_data="";
		for ($i = 2; $i <= $hasildata; $i++) {
			$temp_data = $temp_data . "('".$data->val($i, 2)."','".$data->val($i, 3)."','".$data->val($i, 4)."')";

			
		}
		echo var_dump($temp_data);
		die();

		$cekIDPeserta = mysql_fetch_array($database->doQuery('
			CREATE TEMPORARY TABLE IF NOT EXISTS temp(
				idpeserta VARCHAR(50),
				tgl_bayar VARCHAR(20),
				amount VARCHAR(50)
			);

			INSERT INTO temp VALUES $temp_data;

		'));


		for ($i = 2; $i <= $hasildata; $i++) {

			$data1 = $data->val($i, 1); //NO
			$data2 = $data->val($i, 2); //POSD
			if ($data2=="") {		$error = '<font color=red><b><a title="Tanggal bayar tidak boleh kosong">Error</a></b></font>';	$tglBayar = $error;	}
			elseif ( _convertDate2($data2) > $futoday) {		$error = '<font color=red><b><a title="Tanggal bayar melewati tanggal sekarang">Error</a></b></font>';	$tglBayar = $error;	}
			else{	$tglBayar = _convertDate2($data2);	}

			$data3 = $data->val($i, 3); //EFD
			$data4 = $data->val($i, 4); //Desc
			$metID = explode(" - ", $data4);
			$jumlahID = strlen($metID[0]);
			if ($jumlahID==5)		{	$jumlahID_ = "00000".$metID[0];	}
			elseif ($jumlahID==6)	{	$jumlahID_ = "0000".$metID[0];	}
			elseif ($jumlahID==7)	{	$jumlahID_ = "000".$metID[0];	}
			elseif ($jumlahID==8)	{	$jumlahID_ = "00".$metID[0];	}
			elseif ($jumlahID==9)	{	$jumlahID_ = "0".$metID[0];	}
			elseif ($jumlahID==10)	{	$jumlahID_ = $metID[0];	}
			else{	$error = '<font color=red><b><a title="ID Peserta tidak sesuai">Error</a></b></font>';	$jumlahID_ = $error;	}
			if (!is_numeric($jumlahID_)) {

			}else{
			$cekIDPeserta = mysql_fetch_array($database->doQuery('
				SELECT fu_ajk_peserta.id,
					fu_ajk_polis.nmproduk,
					fu_ajk_peserta.id_peserta,
					fu_ajk_peserta.nama,
					fu_ajk_peserta.tgl_bayar,
					fu_ajk_peserta.status_bayar,
					fu_ajk_peserta.nama,
					fu_ajk_peserta.totalpremi,
					fu_ajk_dn.dn_kode,
					fu_ajk_dn.tgl_createdn
				FROM fu_ajk_peserta
				INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id
				INNER JOIN fu_ajk_polis ON fu_ajk_peserta.id_polis = fu_ajk_polis.id
				WHERE fu_ajk_peserta.id_peserta = "'.$jumlahID_.'"'));
			}

$data5 = $data->val($i, 5); //D
if (strtoupper($cekIDPeserta['nama']) == strtoupper($metID[1])) {
	$cekIDNama = $cekIDPeserta['nama'];
}else{
	$error = '<font color=red><b><a title="Nama tidak sesuai dengan ID Peserta">Error</a></b></font>';	$cekIDNama = $error;
}

$data6 = $data->val($i, 6); //C
$map = array("," => "", "." => "", "*" => "", ".00" => "", " " => "");
if ($cekDN['totaldncn'] == strtr($data6, $map)) {	$metCekIJP = $data6;	}

if ($cekIDPeserta['totalpremi'] == strtr($data6, $map)) {
	$cekIDTotalpremi = $data6;
}else{
	$error = '<font color=red><b><a title="Nilai premi tidak sesuai ID Peserta">Error</a></b></font>';	$cekIDTotalpremi = $error;
}

if ($cekIDPeserta['tgl_bayar'] != NULL) {	$error ='<font color=red><b><a title="Status pembayaran telah lunas">Error</a></b></font>';	$statusbyr = $error;	}else{	$statusbyr ="Unpaid";	}

if (($no % 2) == 1) $objlass = 'tbl-odd';	else $objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	<td align="center">'.++$no.'</td>
	<td align="center">'.$tglBayar.'</td>
	<td align="center">'.$data3.'</td>
	<td align="center">'.$jumlahID_.'</td>
	<td>'.$cekIDNama.'</td>
	<td align="right">'.$cekIDTotalpremi.'</td>
	<td align="center">'.$cekIDPeserta['dn_kode'].'</td>
	<td align="center">'.$cekIDPeserta['nmproduk'].'</td>
	<td align="center">'.$statusbyr.'</td>
	</tr>';
}
// end FOR

if ($error) {
echo '<tr><td colspan="20" align="center"><font color="red"><strong><blink>Silahkan lengkapi kolom yang error !!!</blink></strong></font><br />
	  <a href="ajk_paid.php?r=paidupload"><button type="button" class="btn btn-lg btn-danger">Kembali</button></a></font>
	  </td></tr>';
}else{
move_uploaded_file($_FILES['filepembayaran']['tmp_name'], $metpathFIlePaid . $metPaidFile);

for ($i = 2; $i <= $hasildata; $i++) {
	$data1 = $data->val($i, 1); //NO
	$data2 = $data->val($i, 2); //POSD
	$data3 = $data->val($i, 3); //EFD
	$data4 = $data->val($i, 4); //Desc
	$metID = explode(" - ", $data4);
	$jumlahID = strlen($metID[0]);
	if ($jumlahID==5)		{	$jumlahID_ = "00000".$metID[0];	}
	elseif ($jumlahID==6)	{	$jumlahID_ = "0000".$metID[0];	}
	elseif ($jumlahID==7)	{	$jumlahID_ = "000".$metID[0];	}
	elseif ($jumlahID==8)	{	$jumlahID_ = "00".$metID[0];	}
	elseif ($jumlahID==9)	{	$jumlahID_ = "0".$metID[0];	}
	elseif ($jumlahID==10)	{	$jumlahID_ = $metID[0];	}

	$data5 = $data->val($i, 5); //D
	$data6 = $data->val($i, 6); //C
	$map = array("," => "", "." => "", "*" => "", ".00" => "", " " => "");
	$cekIDTotalpremi = strtr($data6, $map);
//echo $data1.'-'._convertDate2($data2).'-'.$data3.'-'.$jumlahID_.'-'.$data5.'-'.$data6.'<br />';
}
echo '<tr><td colspan="29" align="center"><a title="membatalkan update pembayaran" href="ajk_paid.php?r=cclpaidupl&nmfile='.$metPaidFile.'" onClick="if(confirm(\'Apakah anda yakin akan membatalkan data pembayaran ini ?\')){return true;}{return false;}"><img src="image/deleted.png" width="35"></a>
	<a title="update pembayaran" href="ajk_paid.php?r=approvepaid&nmfile='.$metPaidFile.'&nmr='.$_REQUEST['nomorbukti'].'" onClick="if(confirm(\'Apakah anda sudah yakin dengan semua data pembayaran ini ?\')){return true;}{return false;}"><img src="image/save.png" width="35"></a>&nbsp;  &nbsp;</td></tr>';
}
	;
	break;

case "viewPaid_as":

		echo '<table border="0" cellpadding="5" cellspacing="0" width="100%" enctype="multipart/form-data">
	  <tr><th align="left">Modul Upload Data Pembayaran Perperserta</th><th align="left" width="1%"><a href="ajk_paid.php?r=peserta"><img src="image/Backward-64.png" width="20"></a></th></tr>
	  </table>';
		$data = new Spreadsheet_Excel_Reader($_FILES['filepembayaran']['tmp_name']);
		$metPaidFile = $datetime_.'_'.$q['nm_user'].'_'.$_FILES['filepembayaran']['name'];		//Nama File
		$metPaidFile_temp = $datetime_.'_'.$q['nm_user'].'_'.$_FILES['filepembayaran']['temp_name'];		//Nama File
		$hasildata = $data->rowcount($sheet_index = 0);
		echo '<table class="table table-condensed table-striped table-bordered table-hover no-margin" width="100%">
		<tr><th width="1%">NO</th>
			<th width="10%">ID Peserta</th>
			<th>Nama Debitur</th>
			<th width="10%">Tanggal Bayar</th>
			<th width="10%">Jumlah Bayar</th>
			<th width="10%">No Referensi</th>
			<th width="10%">Status</th>
			<th width="10%">Keterangan</th>
		</tr></thead><tbody>';
		for ($i = 2; $i <= $hasildata; $i++) {
			$data1 = $data->val($i, 1); //NO
			$data2 = $data->val($i, 2); //POSD
			$data3 = $data->val($i, 3); //EFD
			$data4 = $data->val($i, 4); //Desc
			$data5 = $data->val($i, 5); //Desc
			$data6 = $data->val($i, 6); //Desc

			//$data2=explode("/", $data2);


			$cekIDPeserta = mysql_fetch_array($database->doQuery('SELECT fu_ajk_peserta.id,
																		fu_ajk_polis.nmproduk,
																		fu_ajk_peserta.id_peserta,
																		fu_ajk_peserta.nama,
																		fu_ajk_peserta.tgl_bayar,
																		fu_ajk_peserta.status_bayar,
																		fu_ajk_peserta.nama,
																		fu_ajk_peserta.totalpremi,
																		fu_ajk_dn.dn_kode,
																		fu_ajk_dn.tgl_createdn
																		FROM fu_ajk_peserta
																		INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id
																		INNER JOIN fu_ajk_polis ON fu_ajk_peserta.id_polis = fu_ajk_polis.id
																		INNER JOIN fu_ajk_cn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
																		LEFT join fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
																		WHERE  (fu_ajk_klaim.id_klaim_status=7 or fu_ajk_klaim.id_klaim_status=6 or fu_ajk_klaim.id_klaim_status=1 or fu_ajk_klaim.id_klaim_status=4 or fu_ajk_klaim.id_klaim_status=5) and fu_ajk_peserta.id_peserta = "'.$data1.'" '));




			$xstatus = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_pembayaran_status WHERE pembayaran_status="' . $data5 . '"'));
			$status='Y';
			if ($data2=="") {
					$status='N';
					$error = '<font color=red><i><a title="Tanggal bayar tidak boleh kosong">Error</a></i></font>';	$tglBayar = $error;
			}elseif ( _convertDate2($data2) > $futoday) {
					$status='N';
					$error = '<font color=red><i><a title="Tanggal bayar melewati tanggal sekarang">Error</a></i></font>';	$tglBayar = $error;
			}else{
				$tglBayar = _convertDate2($data2);
			}

			$nama_peserta=$cekIDPeserta['nama'];
			if($cekIDPeserta['id_peserta']!==$data1){
				$status='N';
				$nama_peserta='<font color="red"><i>ID Peserta tidak ada dalam database</i></font>';
			}
			
			if(is_null($xstatus['id'])){
				$status='N';
				$error = '<font color=red><i>Status Tidak ditemukan dalam database</i></font>';	$data_status = $error;
			}else{
				$data_status=$xstatus['pembayaran_status'];
			}

			if (($no % 2) == 1) $objlass = 'tbl-odd';	else $objlass = 'tbl-even';
			echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
					<td align="center">'.++$no.'</td>
					<td align="center">'.$data1.'</td>
					<td align="center">'.$nama_peserta.'</td>
					<td align="center">'.$tglBayar.'</td>
					<td align="center">'.$data3.'</td>
					<td align="center">'.$data4.'</td>
					<td align="center">'.$data_status.'</td>
					<td align="center">'.$data6.'</td>

			</tr>';

			$cekIDPeserta = mysql_fetch_array($database->doQuery('
					insert into fu_ajk_asuransi_bayar_temp set ref_id="'.$data1.'"
					, ref_date="'.$tglBayar.'"
					, ref_total="'.$data3.'"
					, ref_no="'.$data4.'"
					, ref_status="'.$data5.'"
					, ref_desc="'.$data6.'"
					, ref_type="DNC"
					, status="0"
					, valid="'.$status.'"
					, input_by="'.$q['nm_lengkap'].'"
					, input_date=current_timestamp
					'));

		}

		if($status=='Y'){
			echo '<tr></tr><tr><td align="center" colspan="8"><a href="ajk_paid.php?r=viewPaid_as2">Approve Pembayaran</a></td></tr>';
		}else{
			echo '<tr></tr><tr><td align="center" colspan="8"><font color=red><i>Terdapat kesalahan dalam file upload, silahkan check kembali data yang anda upload.</i></font></td></tr>
				<tr><td align="center" colspan="8"><a href="ajk_paid.php?r=paidupload_as1" title="Batalkan update pembayaran"><img src="../image/deleted.png" width="18"></a></td></tr>';
		}
		;
		break;
case "viewPaid_as2":
	mysql_query('
						Update `fu_ajk_note_as`
				    INNER JOIN `fu_ajk_asuransi_bayar_temp`
				        ON (`fu_ajk_note_as`.`id_peserta` = `fu_ajk_asuransi_bayar_temp`.`ref_id`)
				    INNER JOIN `fu_ajk_pembayaran_status`
				        ON (`fu_ajk_asuransi_bayar_temp`.`ref_status_id` = `fu_ajk_pembayaran_status`.`id`)
						set `fu_ajk_note_as`.`note_paid_date`=`fu_ajk_asuransi_bayar_temp`.`ref_date`
				    , `fu_ajk_note_as`.`note_paid_total`=`fu_ajk_asuransi_bayar_temp`.`ref_total`
				    , `fu_ajk_note_as`.`note_reference`=`fu_ajk_asuransi_bayar_temp`.`ref_no`
				    , `fu_ajk_note_as`.`note_status`=`fu_ajk_pembayaran_status`.`pembayaran_status`
				    , `fu_ajk_note_as`.`note_desc`=`fu_ajk_asuransi_bayar_temp`.`ref_desc`
						WHERE `fu_ajk_asuransi_bayar_temp`.`status`="0" AND `fu_ajk_asuransi_bayar_temp`.`valid`="Y" AND `fu_ajk_asuransi_bayar_temp`.`input_by`="'.$q['nm_lengkap'].'"
					');
	

	mysql_query('UPDATE
					`fu_ajk_asuransi_bayar_temp`
					inner join fu_ajk_cn on fu_ajk_asuransi_bayar_temp.ref_id=fu_ajk_cn.id_peserta and fu_ajk_cn.type_claim="Death" 		
					SET
					`fu_ajk_cn`.`tgl_bayar_asuransi`=`fu_ajk_asuransi_bayar_temp`.`ref_date`
				    , `fu_ajk_cn`.`total_bayar_asuransi`=`fu_ajk_asuransi_bayar_temp`.`ref_total`
					WHERE (`fu_ajk_cn`.`tgl_bayar_asuransi` IS NULL or `fu_ajk_cn`.`tgl_bayar_asuransi`="0000-00-00") 
					AND `fu_ajk_asuransi_bayar_temp`.`status`="0" AND `fu_ajk_asuransi_bayar_temp`.`valid`="Y" 
					AND `fu_ajk_asuransi_bayar_temp`.`input_by`="'.$q['nm_lengkap'].'"
					');
	mysql_query('UPDATE
					`fu_ajk_asuransi_bayar_temp`
					inner join fu_ajk_klaim on fu_ajk_asuransi_bayar_temp.ref_id=fu_ajk_klaim.id_peserta and fu_ajk_klaim.type_klaim="Death" 		
					SET
				    fu_ajk_klaim.status_bayar=fu_ajk_asuransi_bayar_temp.ref_status
					WHERE `fu_ajk_asuransi_bayar_temp`.`status`="0" AND `fu_ajk_asuransi_bayar_temp`.`valid`="Y"
					AND `fu_ajk_asuransi_bayar_temp`.`input_by`="'.$q['nm_lengkap'].'"
					');

	
	mysql_query('update
					fu_ajk_asuransi_bayar_temp
					INNER JOIN fu_ajk_klaim ON fu_ajk_asuransi_bayar_temp.ref_id = fu_ajk_klaim.id_peserta
					INNER JOIN fu_ajk_cn ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
					set fu_ajk_klaim.id_klaim_status=1
					where fu_ajk_cn.type_claim = "Death" AND fu_ajk_cn.del IS NULL and
					`fu_ajk_asuransi_bayar_temp`.`status`="0" AND `fu_ajk_asuransi_bayar_temp`.`valid`="Y"
					and (fu_ajk_cn.tgl_byr_claim is not null or fu_ajk_cn.tgl_byr_claim!="0000-00-00")
					AND `fu_ajk_asuransi_bayar_temp`.`input_by`="'.$q['nm_lengkap'].'"
					');

	mysql_query('update
					fu_ajk_asuransi_bayar_temp
					INNER JOIN fu_ajk_klaim ON fu_ajk_asuransi_bayar_temp.ref_id = fu_ajk_klaim.id_peserta
					INNER JOIN fu_ajk_cn ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
					set fu_ajk_klaim.id_klaim_status=4
					where fu_ajk_cn.type_claim = "Death" AND fu_ajk_cn.del IS NULL and
					`fu_ajk_asuransi_bayar_temp`.`status`="0" AND `fu_ajk_asuransi_bayar_temp`.`valid`="Y"
					and (fu_ajk_cn.tgl_byr_claim is null or fu_ajk_cn.tgl_byr_claim="0000-00-00")
					AND `fu_ajk_asuransi_bayar_temp`.`input_by`="'.$q['nm_lengkap'].'"
					');
	mysql_query('update fu_ajk_asuransi_bayar_temp 
					set `fu_ajk_asuransi_bayar_temp`.`status`="1" 
					WHERE `fu_ajk_asuransi_bayar_temp`.`input_by`="'.$q['nm_lengkap'].'"
					');
	
	
	
	header("location:ajk_paid.php?r=paidupload_as1");
	exit();
;
break;

case "cclpaidupl":
unlink($metpathFIlePaid . $_REQUEST['nmfile']);
header('location:ajk_paid.php?r=paidupload');
	;
	break;

case "approvepaid":
//echo $_REQUEST['nmfile'].'<br />';
$opDirFile = $metpathFIlePaid.''.$_REQUEST['nmfile'].'';
$data = new Spreadsheet_Excel_Reader($opDirFile);
$hasildata = $data->rowcount($sheet_index=0);
for ($i=2; $i<=$hasildata; $i++){
	$data1 = $data->val($i, 1); //NO
	$data2 = $data->val($i, 2); //POSD
	$data3 = $data->val($i, 3); //EFD
	$data4 = $data->val($i, 4); //Desc
	$data5 = $data->val($i, 5); //Desc
	$data6 = $data->val($i, 6); //Desc

	$metID = explode(" - ", $data4);
	$jumlahID = strlen($metID[0]);
	if ($jumlahID==5)		{	$jumlahID_ = "00000".$metID[0];	}
	elseif ($jumlahID==6)	{	$jumlahID_ = "0000".$metID[0];	}
	elseif ($jumlahID==7)	{	$jumlahID_ = "000".$metID[0];	}
	elseif ($jumlahID==8)	{	$jumlahID_ = "00".$metID[0];	}
	elseif ($jumlahID==9)	{	$jumlahID_ = "0".$metID[0];	}
	elseif ($jumlahID==10)	{	$jumlahID_ = $metID[0];	}

	$map = array("," => "", "." => "", "*" => "", ".00" => "", " " => "");
	$cekIDTotalpremi = strtr($data6, $map);

	$metPaidDebitnote = $database->doQuery('INSERT INTO fu_ajk_dn_paid SET fname="'.$_REQUEST['nmfile'].'", nomorpembayaran="'.$_REQUEST['nmr'].'", input_by="'.$q['id'].'", input_date="'.$futgl.'"');
	$metPaidDebitnote_ = mysql_fetch_array($database->doQuery('SELECT id FROM fu_ajk_dn_paid ORDER BY id DESC'));

	$met_ = $database->doQuery('UPDATE fu_ajk_peserta SET id_bayar="'.$metPaidDebitnote_['id'].'", status_bayar="1", tgl_bayar="'._convertDate2($data2).'" WHERE id_peserta="'.$jumlahID_.'" AND totalpremi="'.$cekIDTotalpremi.'"');

	$metDebitur_ = mysql_fetch_array($database->doQuery('SELECT id_dn FROM fu_ajk_peserta WHERE id_peserta="'.$jumlahID_.'" AND totalpremi="'.$cekIDTotalpremi.'"'));
	//echo $metDebitur_['id_dn'].'<br />';

	$metDebitur__ = mysql_fetch_array($database->doQuery('SELECT SUM(totalpremi) AS tpremidebitur, tgl_bayar FROM fu_ajk_peserta WHERE id_dn="'.$metDebitur_['id_dn'].'" AND status_bayar="1" AND del IS NULL ORDER BY tgl_bayar DESC'));
	//echo $metDebitur__['tpremidebitur'].'<br />';
	$metDN_ = mysql_fetch_array($database->doQuery('SELECT SUM(totalpremi) AS tpremidn FROM fu_ajk_dn WHERE id="'.$metDebitur_['id_dn'].'" AND del IS NULL'));
	//echo $metDN_['tpremidn'].'<br />';

			if (duit($metDebitur__['tpremidebitur']) == duit($metDN_['tpremidn'])) {
				//	echo('UPDATE fu_ajk_dn SET tgl_dn_paid="'.$metDebitur__['tgl_bayar'].'", dn_total="'.$metDN_['tpremidn'].'" , dn_status="Lunas" WHERE id="'.$metDebitur_['id_dn'].'" AND del IS NULL');
				$metUpdateDN_ = $database->doQuery('UPDATE fu_ajk_dn SET tgl_dn_paid="'.$metDebitur__['tgl_bayar'].'", dn_total="'.$metDN_['tpremidn'].'" , dn_status="paid" WHERE id="'.$metDebitur_['id_dn'].'" AND del IS NULL');
			}else{
				//	echo 'abaikan';
			}
}
echo '<center><div class="alert alert-success"><strong>Upload data pembayaran telah selesai diupdate.</strong>.</div></center>
	  <meta http-equiv="refresh" content="2; url=ajk_paid.php?r=peserta">';
	;
	break;


	case "paidupload":
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
	  <tr><th align="left">Modul Upload Data Pembayaran Asuransi</th>
	  <th align="left" width="1%"></th></tr></table>';
echo '<table border="0" width="100%" cellpadding="3" cellspacing="1" style="border: solid 1px #DEDEDE" enctype="multipart/form-data">
	<form method="post" action="" enctype="multipart/form-data">';


echo '<tr><td width="20%">File Pembayaran (.xls)<font color="red">*</font></td><td><input name="filepembayaran" type="file" accept="application/vnd.ms-excel" required></td></tr>
	<tr><td>
		<input type="hidden" name="r" value="viewPaid" class="button">
		<input type="submit" name="button" value="Upload Pembayaran" class="button"></td></tr>
	</form>
	</table>';
		;
		break;
case "paidupload_as1":
	include_once ("../includes/functions.php");
			echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
			  <tr><th align="left">Modul Upload Data Pembayaran dari</th><th align="left" width="1%"><a href="ajk_claim.php"><img src="image/Backward-64.png" width="20"></a></th></tr>
			  </table>';
					echo '<table border="0" width="100%" cellpadding="3" cellspacing="1" style="border: solid 1px #DEDEDE" enctype="multipart/form-data">
			<form method="post" action="" enctype="multipart/form-data">

				<!--
				<tr><td width="10%">Nama Perusahaan</td>
						<td><select size="1" name="metCompany">
							<option value="">--Pilih Lembaga--</option>-->
							';

							echo '<tr><td width="10%">File Pembayaran</td><td><input name="filepembayaran" type="file" accept="application/vnd.ms-excel"></td></tr>
					<tr><td><input type="hidden" name="r" value="viewPaid_as" class="button"><input type="submit" name="button" value="Upload Pembayaran" class="button"></td><td><a href="temp_upload.xls">Contoh File Upload</a></tr>
					</form>
					</table>';
			;
			break;
	case "peserta":
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
	  <tr><th align="left">Modul Upload Data Pembayaran perPeserta dara</th><th align="left" width="1%"><a href="ajk_paid.php?r=paidupload" title="upload data pembayaran perpeserta"><img src="image/rmf_2.png" width="25"></a></th></tr>
	  </table>';
if ($_REQUEST['e']=="paid") {
$metpaidpeserta = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id="'.$_REQUEST['idp'].'"'));
if ($_REQUEST['pd']=="updatebyr") {
$met_update = mysql_fetch_array($database->doQUery('UPDATE fu_ajk_peserta SET status_bayar="1", tgl_bayar="'.$_REQUEST['tgl_byr'].'" WHERE id="'.$_REQUEST['idpaid'].'"'));
$cek_peserta = mysql_fetch_array($database->doQuery('SELECT SUM(totalpremi) AS jPremi FROM fu_ajk_peserta WHERE id_dn="'.$metpaidpeserta['id_dn'].'" AND status_bayar="1"'));

//UPDATE STATUS DN
$cek_statusDN = $database->doQuery('UPDATE fu_ajk_dn SET dn_total="'.$cek_peserta['jPremi'].'", dn_status = IF(totalpremi > '.$cek_peserta['jPremi'].', "paid(*)", "paid"), update_by="'.$q['nm_lengkap'].'", update_time="'.$futgl.'" WHERE id="'.$metpaidpeserta['id_dn'].'"');
//UPDATE STATUS DN
echo '<center>Pembayaran data peserta telah diupdate oleh '.$q['nm_lengkap'].' pada tanggal '.$futgl.'.</center><meta http-equiv="refresh" content="3;URL=ajk_paid.php?r=peserta">';
}
echo '<table border="0" width="100%" cellpadding="1" cellspacing="1">
	  <form method="post" action="">
	  <input type="hidden" name="idpaid" value="'.$metpaidpeserta['id'].'">
	  <tr><td width="50%" align="right">ID Peserta</td><td>: <b>'.$metpaidpeserta['id_peserta'].'</b></td></tr>
	  <tr><td width="50%" align="right">Nama Peserta</td><td>: <b>'.$metpaidpeserta['nama'].'</b></td></tr>
	  <tr><td width="50%" align="right">Total Premi</td><td>: <b>'.duit($metpaidpeserta['totalpremi']).'</b></td></tr>
	  <tr><td width="50%" align="right">Tanggal Pembayaran</td><td>: ';print initCalendar();	print calendarBox('tgl_byr', 'triger', $_REQUEST['tgl_byr']).'</td></tr>
	  <tr><td colspan="2" align="center"><input type="hidden" name="pd" value="updatebyr" class="button"><input type="submit" name="button" value="Update" class="button"> &nbsp; <a href="ajk_paid.php?r=peserta" title="Batalkan pembayaran"><img src="../image/deleted.png" width="18"></a></td></tr>
	  </form></table>';
}else{
echo '<table border="0" width="100%" cellpadding="1" cellspacing="1">
	  <form method="post" action="">
	  <tr><td width="10%">ID Peserta</td><td>: <input type="text" name="idnya" value="'.$_REQUEST['idnya'].'"></td></tr>
	  <tr><td>Nama Peserta</td><td>: <input type="text" name="namanya" value="'.$_REQUEST['namanya'].'"></td></tr>
	  <!--<tr><td>Status Pembayaran</td><td>:
	  <select id="statusbayar" name="statusbayar">
	  	<option value="">-----Status-----</option>
	  	<option value="paid"'._selected($_REQUEST['statusbayar'], "paid").'>Lunas</option>
	  	<option value="unpaid"'._selected($_REQUEST['statusbayar'], "unpaid").'>Belum dibayar</option>
	  	<option value="paid(*)"'._selected($_REQUEST['statusbayar'], "paid(*)").'>Kurang bayar</option>
	  </select>
	  </td></tr>-->
	  <tr><td colspan="2"><input type="submit" name="button" value="Cari" class="button"></td></tr>
	  </form></table>';
}
echo '<table border="0" width="100%" cellpadding="5" cellspacing="1" bgcolor="#bde0e6">
	<tr><th width="2%">No</th>
		<th width="15%">Nama Perusahaan</th>
		<th>Asuransi</th>
		<th width="5%">Produk</th>
		<th width="1%">DN Number</th>
		<th width="1%">Tgl Debitnote</th>
		<th width="1%">ID Peserta</th>
		<th>Nama</th>
		<th width="1%">Tgl Lahir</th>
		<th width="1%">Status</th>
		<th width="8%">T.Premi</th>
		<th width="8%">Cabang</th>
		<th width="1%">Option</th>
	</tr>';

if ($_REQUEST['idnya'])			{	$satu = 'AND fu_ajk_peserta.id_peserta = "' . $_REQUEST['idnya'] . '"';		}
if ($_REQUEST['namanya'])		{	$dua = 'AND fu_ajk_peserta.nama LIKE "%' . $_REQUEST['namanya'] . '%"';		}
//if ($_REQUEST['statusbayar'])	{	$tiga = 'AND fu_ajk_dn.dn_status = "' . $_REQUEST['statusbayar'] . '"';		}else{	$tiga = "";	}

if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 50;	}	else {	$m = 0;		}
/*
$paidpeserta = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id != "" AND id_dn!="" AND status_bayar="0" AND tgl_bayar IS NULL '.$satu.' '.$dua.' AND del IS NULL ORDER BY input_time DESC LIMIT ' . $m . ' , 50');
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_peserta WHERE id != "" AND id_dn!="" AND status_bayar="0" AND tgl_bayar IS NULL '.$satu.' '.$dua.' AND del IS NULL '));
$totalRows = $totalRows[0];
*/
$paidpeserta = $database->doQuery('SELECT fu_ajk_peserta.id,
										  fu_ajk_costumer.`name` AS perusahaan,
										  fu_ajk_asuransi.`name` AS asuransi,
										  fu_ajk_polis.nmproduk,
										  fu_ajk_dn.dn_kode,
										  fu_ajk_dn.tgl_createdn,
										  fu_ajk_peserta.id_peserta,
										  fu_ajk_peserta.nama,
										  fu_ajk_peserta.tgl_lahir,
										  fu_ajk_peserta.status_aktif,
										  fu_ajk_peserta.totalpremi,
										  fu_ajk_peserta.cabang
										  FROM fu_ajk_dn
										  INNER JOIN fu_ajk_peserta ON fu_ajk_dn.id = fu_ajk_peserta.id_dn
										  INNER JOIN fu_ajk_costumer ON fu_ajk_dn.id_cost = fu_ajk_costumer.id
										  INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
										  INNER JOIN fu_ajk_polis ON fu_ajk_dn.id_nopol = fu_ajk_polis.id
										  WHERE fu_ajk_peserta.del IS NULL AND fu_ajk_peserta.id_dn != "" AND status_bayar="0" AND tgl_bayar IS NULL '.$satu.' '.$dua.'
										  ORDER BY fu_ajk_peserta.input_time DESC LIMIT ' . $m . ' , 50');
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(fu_ajk_peserta.id) FROM fu_ajk_dn
										  		   INNER JOIN fu_ajk_peserta ON fu_ajk_dn.id = fu_ajk_peserta.id_dn
										  		   INNER JOIN fu_ajk_costumer ON fu_ajk_dn.id_cost = fu_ajk_costumer.id
										  		   INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
										  		   INNER JOIN fu_ajk_polis ON fu_ajk_dn.id_nopol = fu_ajk_polis.id
										  		   WHERE fu_ajk_peserta.del IS NULL AND fu_ajk_peserta.id_dn != "" AND status_bayar="0" AND tgl_bayar IS NULL '.$satu.' '.$dua.' '));
$totalRows = $totalRows[0];
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while ($paidpeserta_ = mysql_fetch_array($paidpeserta)) {

if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		<td align="center">'.(++$no + ($pageNow-1) * 50).'</td>
		<td>'.$paidpeserta_['perusahaan'].'</td>
		<td>'.$paidpeserta_['asuransi'].'</td>
		<td align="center">'.$paidpeserta_['nmproduk'].'</td>
		<td align="right">'.$paidpeserta_['dn_kode'].'</td>
		<td align="center">'._convertDate($paidpeserta_['tgl_createdn']).'</td>
		<td align="center">'.$paidpeserta_['id_peserta'].'</td>
		<td>'.$paidpeserta_['nama'].'</td>
		<td align="right">'._convertDate($paidpeserta_['tgl_lahir']).'</td>
		<td>'.$paidpeserta_['status_aktif'].'</td>
		<td align="right">'.duit($paidpeserta_['totalpremi']).'</td>
		<td>'.$paidpeserta_['cabang'].'</td>
		<td align="center"><a href="ajk_paid.php?r=peserta&e=paid&idp='.$paidpeserta_['id'].'"><img src="image/check.png" width="25"></a></td>
		</tr>';
}
echo '<tr><td colspan="22">';
echo createPageNavigations($file = 'ajk_paid.php?r=peserta&namanya='.$_REQUEST['namanya'].'&idnya='.$_REQUEST['idnya'].'&statusbayar='.$_REQUEST['statusbayar'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 50);
echo '<b>Total Data Peserta Unpaid: <u>' . duittanpakoma($totalRows) . '</u></b></td></tr>';
echo '</table>';
		;
		break;
	default:
		;
} // switch


?>