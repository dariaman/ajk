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
			<th width="12%">Nama</th>
			<th width="8%">Tgl Lahir</th>
			<th width="4%">Usia</th>
			<th width="10%">Plafond</th>
			<th width="3%">Tenor</th>
			<th width="3%">MPP</th>
			<th width="4%">Rate Asuransi</th>
			<th width="8%">Premi Asuransi</th>
			<th width="10%">Tgl Bayar</th>
			<th width="8%">Nilai Bayar</th>
			<th width="10%">Status</th>
		</tr></thead><tbody>';

		for ($i = 0; $i <= $hasildata-2; $i++) 
		{
$cekIDPeserta = mysql_fetch_array($database->doQuery('SELECT fp.`id_peserta`,fpl.`nmproduk`,fp.`nama`,fp.`tgl_lahir`,fp.`usia`,fp.`kredit_jumlah`,fp.`kredit_tenor`,
fp.`mppbln`,fp.`rateasuransi`,fp.`premi`,fas.`b_tgl_bayar`,fas.`b_nilai_bayar`
FROM `fu_ajk_peserta` fp 
LEFT JOIN `fu_ajk_polis` fpl ON fpl.`id`=fp.`id_polis`
LEFT JOIN `fu_ajk_peserta_as` fas ON fas.`id_peserta`=fp.`id_peserta`
WHERE COALESCE(fp.`mppbln`,fp.`rateasuransi`,0) >0 AND fp.`rateasuransi` IS NOT NULL 
AND fp.`id_peserta`=\''.substr('0000000000'.$data->val($i+2, 2),-10).'\';'));

$error="";
if ($cekIDPeserta["id_peserta"]=="") {
	$error = '<font color=red><b><br>ID Peserta tidak ditemukan</b></font>';
	$is_error="true";
}

$errortgl="";
if(!date_create($data->val($i+2, 3))){
		$errortgl= '<font color=red><b><br>Format tanggal salah (yyyy-mm-dd)</b></font>';
		$is_error="true";
}else{
	$tglbyr = date_create($data->val($i+2, 3));
	// echo var_dump(date_format($tglbyr,'Y')>'2018');

	if(date_format($tglbyr,'Y')<'2018'){
		$errortgl= '<font color=red><b><br>Format tanggal salah (yyyy-mm-dd)</b></font>';
		$is_error="true";
	}
}

$errornilai="";
if(!is_numeric($data->val($i+2, 4))){
	$errornilai= '<font color=red><b><br>Nilai harus angka</b></font>';
	$is_error="true";
}

$status="";
if ($cekIDPeserta["b_tgl_bayar"]==$data->val($i+2, 3) && 
	$cekIDPeserta["b_nilai_bayar"]==$data->val($i+2, 4)) {
	$status = '<font color=red><b>Paid</b></font>';
	$is_error="true";
}else if(date_create($cekIDPeserta["b_tgl_bayar"])>=date_create($data->val($i+2, 3))){
	$status = '<font color=red><b>Paid</b></font>';
	$is_error="true";
}else { $status = 'Unpaid' ;}

if (($i % 2) == 1) $objlass = 'tbl-odd';	else $objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	<td align="center">'.($i+1).'</td>
	<td align="center">'.$cekIDPeserta["id_peserta"].$error.'</td>
	<td align="center">'.$cekIDPeserta["nmproduk"].'</td>
	<td align="Left">'.$cekIDPeserta["nama"].'</td>
	<td align="center">'.$cekIDPeserta["tgl_lahir"].'</td>
	<td align="right">'.$cekIDPeserta["usia"].'</td>
	<td align="right">'.$cekIDPeserta["kredit_jumlah"].'</td>
	<td align="right">'.$cekIDPeserta["kredit_tenor"].'</td>
	<td align="right">'.$cekIDPeserta["mppbln"].'</td>
	<td align="right">'.$cekIDPeserta["rateasuransi"].'</td>
	<td align="right">'.$cekIDPeserta["premi"].'</td>
	<td align="center">'.$data->val($i+2, 3).$errortgl.'</td>
	<td align="right">'.$data->val($i+2, 4).$errornilai.'</td>
	<td align="center">'.$status.'</td>
	</tr>';
}
// end FOR

if ($is_error) {
echo '<tr><td colspan="20" align="center"><font color="red"><strong><blink>Silahkan lengkapi kolom yang error !!!</blink></strong></font><br />
	  <a href="ajk_paid_upl.php?r=paidupload"><button type="button" class="btn btn-lg btn-danger">Kembali</button></a></font>
	  </td></tr>';
}else{
move_uploaded_file($_FILES['filepembayaran']['tmp_name'], $metpathFIlePaid . $metPaidFile);


echo '<tr><td colspan="29" align="center"><a title="membatalkan update pembayaran" href="ajk_paid_upl.php?r=cclpaidupl&nmfile='.$metPaidFile.'" onClick="if(confirm(\'Apakah anda yakin akan membatalkan data pembayaran ini ?\')){return true;}{return false;}"><img src="image/deleted.png" width="35"></a>
	<a title="update pembayaran" href="ajk_paid_upl.php?r=approvepaid&nmfile='.$metPaidFile.'&nmr='.$_REQUEST['nomorbukti'].'" onClick="if(confirm(\'Apakah anda sudah yakin dengan semua data pembayaran ini ?\')){return true;}{return false;}"><img src="image/save.png" width="35"></a>&nbsp;  &nbsp;</td></tr>';
}
	;
	break;

case "cclpaidupl":
unlink($metpathFIlePaid . $_REQUEST['nmfile']);
header('location:ajk_paid_upl.php?r=paidupload');
break;

case "approvepaid":
$opDirFile = $metpathFIlePaid.''.$_REQUEST['nmfile'].'';
$data = new Spreadsheet_Excel_Reader($opDirFile);
$hasildata = $data->rowcount($sheet_index=0);
for ($i=2; $i<=$hasildata; $i++){
	$data2 = $data->val($i, 2); //id peserta
	$data3 = $data->val($i, 3); //tgl bayar
	$data4 = $data->val($i, 4); //amount bayar

	$database->doQuery('
		UPDATE `fu_ajk_peserta_as` fas
		SET fas.`b_nilai_bayar`='.$data4.',
			fas.`b_tgl_bayar`=\''.$data3.'\',
			fas.`update_by`=\''.$_SESSION['nm_user'].'\',
			fas.`update_date`=NOW()
		WHERE fas.`id_peserta`=\''.substr('0000000000'.$data2,-10).'\';
		');
}
echo '<center><div class="alert alert-success"><strong>Upload data pembayaran telah selesai diupdate.</strong>.</div></center>
	  <meta http-equiv="refresh" content="2; url=ajk_paid_upl.php?r=peserta">';
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
	default:
		;
} // switch


?>