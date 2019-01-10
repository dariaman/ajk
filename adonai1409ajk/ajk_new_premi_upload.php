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
case "viewPremi":
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%" enctype="multipart/form-data">
	  <tr><th align="left">Modul Upload Data New Rate Premi</th><th align="left" width="1%"><a href="ajk_new_premi_upload.php?r=premiupload"><img src="image/Backward-64.png" width="20"></a></th></tr>
	  </table>';
	$data = new Spreadsheet_Excel_Reader($_FILES['filepembayaran']['tmp_name']);
	$metPaidFile = $datetime_.'_'.$q['nm_user'].'_'.$_FILES['filepembayaran']['name'];		//Nama File
	$metPaidFile_temp = $datetime_.'_'.$q['nm_user'].'_'.$_FILES['filepembayaran']['temp_name'];		//Nama File
	$hasildata = $data->rowcount($sheet_index = 0);
echo '<table class="table table-condensed table-striped table-bordered table-hover no-margin" width="100%">
		<tr><th>NO</th>
			<th width="10%">ID Peserta</th>
			<th width="10%">Produk</th>
			<th width="12%">Nama</th>
			<th width="8%">Tgl Lahir</th>
			<th>Usia</th>
			<th width="10%">Plafond</th>
			<th>Tenor</th>

			<th width="4%">Rate Asuransi</th>
			<th width="8%">Premi Asuransi</th>
			<th width="8%">Ext.Premi</th>
			<th width="8%">Total Premi</th>

			<th width="4%">Rate Asuransi (File)</th>
			<th width="8%">Premi Asuransi (File)</th>
			<th width="8%">Ext.Premi (File)</th>
			<th width="8%">Total Premi (File)</th>

		</tr></thead><tbody>';

		for ($i = 0; $i <= $hasildata-2; $i++) 
		{
			if(trim($data->val($i+2, 1))==''){ break; }

			$cekIDPeserta = mysql_fetch_array($database->doQuery('
	SELECT fp.`id_peserta`,fpl.`nmproduk`,fp.`nama`,fp.`tgl_lahir`,fp.`usia`,fp.`kredit_jumlah`,fp.`kredit_tenor`,fas.`rateasuransi`,fas.`b_premi`,fas.`b_extpremi`,fas.`nettpremi`
FROM `fu_ajk_peserta` fp 
LEFT JOIN `fu_ajk_polis` fpl ON fpl.`id`=fp.`id_polis`
LEFT JOIN `fu_ajk_peserta_as` fas ON fas.`id_peserta`=fp.`id_peserta`
WHERE COALESCE(fp.`mppbln`,fp.`rateasuransi`,0) >0 AND fp.`rateasuransi` IS NOT NULL 
AND fp.`id_peserta`=\''.substr('0000000000'.$data->val($i+2, 1),-10).'\';'));

$error="";
if ($cekIDPeserta["id_peserta"]=="") {
	$error = '<font color=red><b><br>ID Peserta tidak ditemukan</b></font>';
	$is_error="true";
}

$rate = floatval(preg_replace("/[^-0-9\.]/","",$data->val($i+2, 2)));
$premi = floatval(preg_replace("/[^-0-9\.]/","",$data->val($i+2, 3)));
$extpremi = floatval(preg_replace("/[^-0-9\.]/","",$data->val($i+2, 4)));
$nettpremi = floatval(preg_replace("/[^-0-9\.]/","",$data->val($i+2, 5)));

$errorrate="";
if(!is_float($rate)){
	$errorrate= '<font color=red><b><br>Nilai harus angka</b></font>';
	$is_error="true";
}
$errorpremi="";
if(!is_numeric($premi)){
	$errorpremi= '<font color=red><b><br>Nilai harus angka</b></font>';
	$is_error="true";
}
$errorexpremi="";
if(!is_numeric($extpremi)){
	$errorexpremi= '<font color=red><b><br>Nilai harus angka</b></font>';
	$is_error="true";
}
$errornetpremi="";
if(!is_numeric($nettpremi)){
	$errornetpremi= '<font color=red><b><br>Nilai harus angka</b></font>';
	$is_error="true";
}

if (($i % 2) == 1) $objlass = 'tbl-odd';	else $objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	<td align="center">'.($i+1).'</td>
	<td align="center">'.substr('0000000000'.$data->val($i+2, 1),-10).$error.'</td>
	<td align="center">'.$cekIDPeserta["nmproduk"].'</td>
	<td align="Left">'.$cekIDPeserta["nama"].'</td>
	<td align="center">'.$cekIDPeserta["tgl_lahir"].'</td>
	<td align="right">'.$cekIDPeserta["usia"].'</td>
	<td align="right">'.$cekIDPeserta["kredit_jumlah"].'</td>
	<td align="right">'.$cekIDPeserta["kredit_tenor"].'</td>

	<td align="right">'.$cekIDPeserta["rateasuransi"].'</td>
	<td align="right">'.$cekIDPeserta["b_premi"].'</td>
	<td align="right">'.$cekIDPeserta["b_extpremi"].'</td>
	<td align="right">'.$cekIDPeserta["nettpremi"].'</td>

	<td align="center">'.number_format($rate,5).$errorrate.'</td>
	<td align="right">'.number_format($premi,2).$errorpremi.'</td>
	<td align="center">'.number_format($extpremi,2).$errorexpremi.'</td>
	<td align="right">'.number_format($nettpremi,2).$errornetpremi.'</td>

	<td align="center">'.$status.'</td>
	</tr>';
	// echo var_dump(floatval(preg_replace("/[^-0-9\.]/","",$data->val($i+2, 5))));
}
// end FOR

if ($is_error) {
echo '<tr><td colspan="20" align="center"><font color="red"><strong><blink>Silahkan lengkapi kolom yang error !!!</blink></strong></font><br />
	  <a href="ajk_new_premi_upload.php?r=premiupload"><button type="button" class="btn btn-lg btn-danger">Kembali</button></a></font>
	  </td></tr>';
}else{
move_uploaded_file($_FILES['filepembayaran']['tmp_name'], $metpathFIlePaid . $metPaidFile);


echo '<tr><td colspan="29" align="center"><a title="membatalkan update pembayaran" href="ajk_new_premi_upload.php?r=cclpaidupl&nmfile='.$metPaidFile.'" onClick="if(confirm(\'Apakah anda yakin akan membatalkan data pembayaran ini ?\')){return true;}{return false;}"><img src="image/deleted.png" width="35"></a>
	<a title="update pembayaran" href="ajk_new_premi_upload.php?r=approvepaid&nmfile='.$metPaidFile.'&nmr='.$_REQUEST['nomorbukti'].'" onClick="if(confirm(\'Apakah anda sudah yakin dengan semua data pembayaran ini ?\')){return true;}{return false;}"><img src="image/save.png" width="35"></a>&nbsp;  &nbsp;</td></tr>';
}
	;
	break;

case "cclpaidupl":
unlink($metpathFIlePaid . $_REQUEST['nmfile']);
header('location:ajk_new_premi_upload.php?r=premiupload');
break;

case "approvepaid":
$opDirFile = $metpathFIlePaid.''.$_REQUEST['nmfile'].'';
$data = new Spreadsheet_Excel_Reader($opDirFile);
$hasildata = $data->rowcount($sheet_index=0);
for ($i=2; $i<=$hasildata; $i++){

	$data2 = $data->val($i, 1); //id peserta

	$rate = floatval(preg_replace("/[^-0-9\.]/","",$data->val($i, 2)));
	$premi = floatval(preg_replace("/[^-0-9\.]/","",$data->val($i, 3)));
	$extpremi = floatval(preg_replace("/[^-0-9\.]/","",$data->val($i, 4)));
	$nettpremi = floatval(preg_replace("/[^-0-9\.]/","",$data->val($i, 5)));

	$database->doQuery('
		UPDATE `fu_ajk_peserta_as` fas
		SET fas.`rateasuransi_old`=fas.`rateasuransi`,
		fas.`b_premi_old`=fas.`b_premi`,
		fas.`b_extpremi_old`=fas.`b_extpremi`,
		fas.`nettpremi_old`=fas.`nettpremi`,
			fas.`rateasuransi`='.$rate.',
			fas.`b_premi`=\''.$premi.'\',
			fas.`b_extpremi`='.$extpremi.',
			fas.`nettpremi`=\''.$nettpremi.'\',
			fas.`update_by`=\''.$_SESSION['nm_user'].'\',
			fas.`update_date`=NOW()
		WHERE fas.`id_peserta`=\''.substr('0000000000'.$data2,-10).'\';
		');
}
echo '<center><div class="alert alert-success"><strong>Upload data pembayaran telah selesai diupdate.</strong>.</div></center>
	  <meta http-equiv="refresh" content="2; url=ajk_new_premi_upload.php?r=premiupload">';
	;
	break;


	case "premiupload":
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
	  <tr><th align="left">Modul Upload Premi Rate Baru</th>
	  <th align="left" width="1%"></th></tr></table>';
echo '<table border="0" width="100%" cellpadding="3" cellspacing="1" style="border: solid 1px #DEDEDE" enctype="multipart/form-data">
	<form method="post" action="" enctype="multipart/form-data">';


echo '<tr><td width="20%">File Premi (.xls)<font color="red">*</font></td><td><input name="filepembayaran" type="file" accept="application/vnd.ms-excel" required></td></tr>
	<tr><td>
		<input type="hidden" name="r" value="viewPremi" class="button">
		<input type="submit" name="button" value="Upload Premi" class="button"></td></tr>
	</form>
	</table>';
		;
		break;

	default:
		;
} // switch


?>