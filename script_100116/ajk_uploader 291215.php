<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("includes/functions.php");
connect();
if (session_is_registered('nm_user')) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));}
switch ($_REQUEST['er']) {
case "parse_spaj":
	$fu = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$_REQUEST['idcost'].'"'));
	$fupolis = mysql_fetch_array($database->doQuery('SELECT id,nopol,nmproduk FROM fu_ajk_polis WHERE id="'.$_REQUEST['idpolis'].'"'));

	$_REQUEST['idcost'] = $_POST['idcost'];				if (!$_REQUEST['idcost'])  $error .='Silahkan pilih nomor polis<br />.';
	$_REQUEST['idpolis'] = $_POST['idpolis'];			if (!$_REQUEST['idpolis'])  $error .='Silahkan pilih nomor polis<br />.';
	$_REQUEST['bataskolom'] = $_POST['bataskolom'];		if (!$_REQUEST['bataskolom'])  $error .='Silahkan tentukan batas kolom file<br />.';
		if (!$_FILES['userfile']['tmp_name'])  $error .='Silahkan upload file excel anda<br />.';
	$allowedExtensions = array("xls","xlsx","csv");
	foreach ($_FILES as $file) {
		if ($file['tmp_name'] > '') {
			if (!in_array(end(explode(".",	strtolower($file['name']))), $allowedExtensions)) {
				die('<center><font color=red>'.$file['name'].' <br /><blink>File extension tidak diperbolehkan selain excel!</blink></font><br/>'.'<a href="ajk_uploader.php?er=spaj">'.'&lt;&lt Go Back</a></center>');
			}
		}
	}
		if ($error)
		{	echo '<blink><center><font color=red>'.$error.'</font></blink><a href="ajk_uploader.php?er=spaj">'.'&lt;&lt Go Back</a></center>';	}

		else
		{
echo '<form method="post" action="ajk_uploader.php?r=approveuser" onload ="onbeforeunload">
	  <table border="0" width="100%" cellpadding="1" cellspacing="1" style="border: solid 3px #DEDEDE"  bgcolor="#bde0e6">
		<tr><td colspan="3"><input type="hidden" name="idcostumer" value="'.$fu['id'].'">Nama Perusahaan</td><td colspan="24">: <b>'.$fu['name'].'</b></td></tr>
		<tr><td colspan="3"><input type="hidden" name="idpolis" value="'.$fupolis['id'].'">Nama Produk</td><td colspan="24">: <b>'.$fupolis['nmproduk'].' ('.$fupolis['nopol'].')</b></td></tr>
		<tr><td colspan="3"><input type="hidden" name="bataskolom" value="'.$_REQUEST['bataskolom'].'"><input type="hidden" name="namafileexl" value="'.$_FILES['userfile']['name'].'">Nama File</td><td colspan="24">: <b>'.$_FILES['userfile']['name'].'</b></td></tr>
		<tr><th width="1%" rowspan="2">No</th>
			<th width="5%" rowspan="2">Nama Mitra</th>
			<th width="5%" rowspan="2">Regional</th>
			<th width="5%" rowspan="2">Area</th>
			<th width="5%" rowspan="2">Cabang</th>
			<th rowspan="2">Nama Tertanggung</th>
			<th width="5%" colspan="3">Tanggal Lahir</th>
			<th width="5%" rowspan="2">Uang Asuransi</th>
			<th width="5%" colspan="3">Mulai Asuransi</th>
			<th width="1%" rowspan="2">Tenor<br />(thn)</th>
			<th width="1%" rowspan="2">Usia</th>
			<th width="5%" rowspan="2">Tarif Premi</th>
			<th width="5%" rowspan="2">Premi Standar</th>
			<th width="5%" rowspan="2">Ext.Premi</th>
			<th width="5%" rowspan="2">Premi Sekaligus</th>
			<th width="8%" rowspan="2">Medical</th>
		</tr>
		<tr><th>Hari</th><th>Bulan</th><th>Tahun</th><th>Hari</th><th>Bulan</th><th>Tahun</th></tr>';
			$data = new Spreadsheet_Excel_Reader($_FILES['userfile']['tmp_name']);
			$baris = $data->rowcount($sheet_index=0);											//MEMBACA JUMLAH BARIS DATA EXCEL

			for ($i=10; $i<=$_REQUEST['bataskolom']; $i++)
			{
				$data1=$data->val($i, 1);		//NO
				$data2=$data->val($i, 2);		//NAMA MITRA
				$data3=$data->val($i, 3);		//CABANG
				$data4=$data->val($i, 4);		//NAMA TERTANGGUNG
				$data5=$data->val($i, 5);		//TANGGAL LAHIR (HARI)
				$data6=$data->val($i, 6);		//TANGGAL LAHIR (BLN)
				$data7=$data->val($i, 7);		//TANGGAL LAHIR (THN)
				$data8=$data->val($i, 8);		//UANG ASURANSI
				$data9=$data->val($i, 9);		//MULAI ASURANSI (HARI)
				$data10=$data->val($i, 10);		//MULAI ASURANSI (BLN)
				$data11=$data->val($i, 11);		//MULAI ASURANSI (THN)
				$data12=$data->val($i, 12);		//MA THN
				$data13=$data->val($i, 13);		//MA BLN
				$data14=$data->val($i, 14);		//USIA
				$data15=$data->val($i, 15);		//RATE (TARIF PREMI)
				$data16=$data->val($i, 16);		//PREMI
				$data17=$data->val($i, 17);		//EXTRA PREMI
				$data18=$data->val($i, 18);		//PREMI SEKALIGUS
				$data19=$data->val($i, 19);		//MEDICAL

if ($q['cabang']!="PUSAT") {	$cekUserCabang = ' AND name="'.$q['cabang'].'"';	}else{	$cekUserCabang = ' AND name="'.$data3.'"';	}

				//VALIDASI DATA UPLOAD//
				if ($data3==""){ $error ='<font color="red">error</font>'; $dataexcel3=$error;}else{ $dataexcel3=$data3;}
				if ($data4==""){ $error ='<font color="red">error</font>'; $dataexcel4=$error;}else{ $dataexcel4=$data4;}

				if ($data5==""){ $error ='<font color="red">error</font>'; $dataexcel5=$error;}
				else{
					if(!is_numeric($data5)){$error ='<font color="red">error</font>'; $dataexcel5=$error;}else{ $dataexcel5=$data5;}		//VALIDASI HARI
					if(strlen($data5 > 31 )){$error ='<font color="red">error</font>'; $dataexcel5=$error;}else{ $dataexcel5=$data5;}		//VALIDASI HARI
				//	$dataexcel5=$data5;
				}

				if ($data6==""){ $error ='<font color="red">error</font>'; $dataexcel6=$error;}
				else{
					if(!is_numeric($data6)){$error ='<font color="red">error</font>'; $dataexcel6=$error;}else{ $dataexcel6=$data6;}		//VALIDASI BULAN
					if(strlen($data6 > 12 )){$error ='<font color="red">error</font>'; $dataexcel6=$error;}else{ $dataexcel6=$data6;}		//VALIDASI BULAN
				//	$dataexcel6=$data6;
				}
				if ($data7==""){ $error ='<font color="red">error</font>'; $dataexcel7=$error;}
				else{
					if(!is_numeric($data7)){$error ='<font color="red">error</font>'; $dataexcel7=$error;}else{ $dataexcel7=$data7;}		//VALIDASI TAHUN
					if($data7 < 1900 OR $data7 >= $dateY){$error ='<font color="red">error</font>'; $dataexcel7=$error;}else{ $dataexcel7=$data7;}	//VALIDASI TAHUN LAHIR
				//	$dataexcel7=$data7;
				}

				if ($data8==""){ $error ='error'; $dataexcel8=$error;}
				else{
					$map = array("," => "", "." => "", "*" => "", ".00" => "", " " => "");
					$dataexcel8=strtr($data8,$map);
					//$_titikpos_ = strpos($data8, ".");if ($_titikpos) { $_titikposnya = str_replace(".", "", $data8); $dataexcel8=$_titikposnya;}else{ $dataexcel8=$data8;}
					//$_komapos = strpos($data8, ",");	if ($_komapos)  { $_komaposnya = str_replace(",", "", $data8); $dataexcel8=$_komaposnya;}else{ $dataexcel8=$data8;}
					//$_bintang = strpos($data8, ",");	if ($_bintang)  { $_bintangnya = str_replace("*", "", $data8); $dataexcel8=$_bintangnya;}else{ $dataexcel8=$data8;}
				//	$dataexcel8=$data8;
				}

				if ($data9==""){ $error ='<font color="red">error</font>'; $dataexcel9=$error;}
				else{
					if(!is_numeric($data9)){$error ='<font color="red">error</font>'; $dataexcel9=$error;}else{ $dataexcel9=$data9;}		//VALIDASI HARI
					if(strlen($data9 > 31 )){$error ='<font color="red">error</font>'; $dataexcel9=$error;}else{ $dataexcel9=$data9;}		//VALIDASI HARI
				//	$dataexcel9=$data9;
				}
				if ($data10==""){ $error ='<font color="red">error</font>'; $dataexcel10=$error;}
				else{
					if(!is_numeric($data10)){$error ='<font color="red">error</font>'; $dataexcel10=$error;}else{ $dataexcel10=$data10;}		//VALIDASI BULAN
					if(strlen($data10 > 12 )){$error ='<font color="red">error</font>'; $dataexcel10=$error;}else{ $dataexcel10=$data10;}		//VALIDASI BULAN
				//	$dataexcel10=$data10;
				}

				if ($data11==""){ $error ='<font color="red">error</font>'; $dataexcel11=$error;}
				else{
					if(!is_numeric($data11)){$error ='<font color="red">error</font>'; $dataexcel11=$error;}else{ $dataexcel11=$data11;}					//VALIDASI TAHUN
					if($data11 < 2010 OR $data11 > $dateY){$error ='<font color="red">error</font>'; $dataexcel11=$error;}else{ $dataexcel11=$data11;}		//VALIDASI TAHUN KREDIT
				//	$dataexcel11=$data11;
				}

				if ($data12==""){ $error ='<font color="red">error</font>'; $dataexcel12=$error;}
				else{
					$met_tenor = $data12 * 12 + $data13;
					$cekratepolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_polis="'.$fupolis['id'].'" AND tenor="'.$met_tenor.'" AND status="baru"'));	//VALIDASI CEK TENOR DENGAN SETTING RATE POLIS
					if ($met_tenor != $cekratepolis['tenor']) {$error ='<font color="red">error</font>';	$dataexcel12=$error;	}else{	$dataexcel12=$data12;	}			//VALIDASI CEK TENOR DENGAN SETTING RATE POLIS

				//	$dataexcel12=$data12;
				}

				//if ($data13==""){ $error ='<font color="red">error</font>'; $dataexcel13=$error;}else{ $dataexcel13=$data13;}
				//if ($data14==""){ $error ='<font color="red">error</font>'; $dataexcel14=$error;}else{ $dataexcel14=$data14;}
				if ($data15==""){ $error ='<font color="red">error</font>'; $dataexcel15=$error;}else{	$dataexcel15=$data15;	}
				if ($data16==""){ $error ='<font color="red">error</font>'; $dataexcel16=$error;}
				else{
					$titikpos_ = strpos($data16, ".");	if ($titikpos_) { $titikposnya_ = str_replace(".", "", $data16); $dataexcel16=$titikposnya_;}else{ $dataexcel16=$data16;}
					$komapos_ = strpos($data16, ",");	if ($komapos_) { $komaposnya_ = str_replace(",", "", $data16); $dataexcel16=$komaposnya_;}else{ $dataexcel16=$data16;}
					$bintang_ = strpos($data16, ",");	if ($bintang_) { $bintangnya_ = str_replace("*", "", $data16); $dataexcel16=$bintangnya_;}else{ $dataexcel16=$data16;}
					//
				//	$dataexcel16=$data16;
				}
				//if ($data17==""){ $error ='<font color="red">error</font>'; $dataexcel17=$error;}else{ $dataexcel17=$data17;}
				if ($data18==""){ $error ='<font color="red">error</font>'; $dataexcel18=$error;}
				else{
					$titikpos__ = strpos($data18, ".");	if ($titikpos__) { $titikposnya__ = str_replace(".", "", $data18); $dataexcel18=$titikposnya__;}else{ $dataexcel18=$data18;}
					$komapos__ = strpos($data18, ",");	if ($komapos__) { $komaposnya__ = str_replace(",", "", $data18); $dataexcel18=$komaposnya__;}else{ $dataexcel18=$data18;}
					$bintang__ = strpos($data18, ",");	if ($bintang__) { $bintangnya__ = str_replace("*", "", $data18); $dataexcel18=$bintangnya__;}else{ $dataexcel18=$data18;}
				//	$dataexcel18=$data18;
				}
				if ($data19==""){ $error ='<font color="red">error</font>'; $dataexcel19=$error;}else{ $dataexcel19=$data19;}
				/*
				$cekdatareg = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE id_cost="'.$fu['id'].'" AND name="'.$data13.'"'));			//VALIDASI REGIONAL
				if ($data13 != $cekdatareg['name']) {$error ='<font color="red">error</font>';	$dataexcel13=$error;	}else{	$dataexcel13=$data13;	}			//VALIDASI REGIONAL

				$cekdataarea = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_area WHERE id_cost="'.$fu['id'].'" AND name="'.$data14.'"'));				//VALIDASI AREA
				if ($data14 != $cekdataarea['name']) {$error ='<font color="red">error</font>';	$dataexcel14=$error;	}else{	$dataexcel14=$data14;	}			//VALIDASI AREA

				//VALIDASI DATA UPLOAD//
				*/

				if ($data5 < 9) { $data5_ = '0'.$data5;	}else{	$data5_ = $data5;}
				if ($data6 < 9) { $data6_ = '0'.$data6;	}else{	$data6_ = $data6;}
				$datatgllahirnya = $data7.'-'.$data6_.'-'.$data5_;

				if ($data9 < 9) { $data9_ = '0'.$data9;	}else{	$data9_ = $data9;}
				if ($data10 < 9) { $data10_ = '0'.$data10;	}else{	$data10_ = $data10;}
				$datatglkreditnya = $data11.'-'.$data10_.'-'.$data9_;

	//CEK RELASI WILAYAH
	$cekdatacab = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id_cost="'.$fu['id'].'" '.$cekUserCabang.''));			//VALIDASI CABANG
	if ($data3 != $cekdatacab['name']) {$error ='<font color="red">error</font>';	$dataexcel3=$error;	}else{	$dataexcel3=$data3;	}				//VALIDASI CABANG

	$cekdatawilayah = mysql_fetch_array($database->doQuery('SELECT * FROM v_wilayah WHERE id_cost="'.$_REQUEST['idcost'].'" AND cabang="'.$data3.'"'));
	//if ($cekdatawilayah['regional']!=$data13) {$error ='<font color="red">error</font>'; $dataexcel13=$error;}else{ $dataexcel13=$data13;}
	//if ($cekdatawilayah['area']!=$data14) {$error ='<font color="red">error</font>'; $dataexcel14=$error;}else{ $dataexcel14=$data14;}
	//if ($cekdatawilayah['cabang']!=$data15) {$error ='<font color="red">error</font>'; $dataexcel15=$error;}else{ $dataexcel15=$data15;}
	//CEK RELASI WILAYAH

	//CEK DATA DOUBLE UPLOAD DAN STATUS PEMABAYARAN
	$metdouble = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE nama="'.$data4.'" AND tgl_lahir="'.$datatgllahirnya.'" AND kredit_tgl="'.$datatglkreditnya.'" AND kredit_jumlah="'.$dataexcel8.'" AND kredit_tenor="'.$met_tenor.'" AND status_peserta IS NULL AND del IS NULL'));
//	echo('SELECT * FROM fu_ajk_peserta WHERE nama="'.$data4.'" AND tgl_lahir="'.$datatgllahirnya.'" AND kredit_tgl="'.$datatglkreditnya.'" AND kredit_jumlah="'.$dataexcel8.'" AND kredit_tenor="'.$met_tenor.'" AND del IS NULL');
	if ($metdouble['id_dn']!="" AND $metdouble['status_bayar']=="1") {
		$ceknomor_dn = mysql_fetch_array($database->doQuery('SELECT id, dn_kode FROM fu_ajk_dn WHERE id="'.$metdouble['id_dn'].'"'));
		$no_error ='<font color="red">Data sudah pernah di upload ('.$ceknomor_dn['dn_kode'].')</font>'; $dataexcel4=$data4.'<br />'.$no_error;
	}
	elseif ($metdouble['id_dn']!="" AND $metdouble['status_bayar']=="0") {
		$error ='<font color="red">Data Unpaid ('.$metdouble['nama'].' - '._convertDate($metdouble['tgl_lahir']).')</font>'; $dataexcel4=$error;
	}
	else	{	$dataexcel4=$data4;	}
	//CEK DATA DOUBLE UPLOAD DAN STATUS PEMABAYARAN

				if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
				echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
					<td align="center">'.$data1.'</td>
					<td align="center">'.$data2.'</td>
					<td align="center">'.$cekdatawilayah['regional'].'</td>
					<td align="center">'.$cekdatawilayah['area'].'</td>
					<td>'.$dataexcel3.'</td>
					<td>'.$dataexcel4.'</td>
					<td align="center">'.$dataexcel5.'</td>
					<td align="center">'.$dataexcel6.'</td>
					<td align="center">'.$dataexcel7.'</td>
					<td align="right">'.duit($dataexcel8).'</td>
					<td align="center">'.$dataexcel9.'</td>
					<td align="center">'.$dataexcel10.'</td>
					<td align="center">'.$dataexcel11.'</td>
					<td align="center">'.$dataexcel12.'</td>
					<!--<td>'.$data13.'</td>-->
					<td align="center">'.$data14.'</td>
					<td align="center">'.$data15.'</td>
					<td align="right">'.$dataexcel16.'</td>
					<td>'.$data17.'</td>
					<td align="right">'.$dataexcel18.'</td>
					<td align="center">'.$data19.'</td>
				</tr>';

				//$exl = $database->doQuery('INSERT IGNORE fu_ajk_peserta_tempf SET id_cost="'.$fu['id'].'",
				//$cekdatadbl = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_cost="'.$fu['id'].'", nama="'.$data3.'", tgl_lahir="'.$datatgllahirnya.'", kredit_tgl="'.$datatglkreditnya.'", kredit_jumlah="'.$data7.'", kredit_tenor="'.$data11.'", cabang="'.$data15.'"'));
$met = $database->doQuery('INSERT IGNORE fu_ajk_peserta_tempf SET id_cost="'.$fu['id'].'",
																  id_polis="'.$fupolis['id'].'",
																  namafile="'.$_FILES['userfile']['name'].'",
																  no_urut="'.$data1.'",
																  type_data="SPAJ",
																  spaj="",
																  nama_mitra="'.$data2.'",
																  nama="'.$data4.'",
																  gender="",
																  tgl_lahir="'.$datatgllahirnya.'",
																  usia="'.$data14.'",
																  kredit_tgl="'.$datatglkreditnya.'",
																  kredit_jumlah="'.$dataexcel8.'",
																  kredit_tenor="'.$met_tenor.'",
																  kredit_akhir="",
																  premi="",
																  disc_premi="",
																  bunga="",
																  biaya_adm="",
																  ext_premi="'.$data17.'",
																  totalpremi="",
																  badant="",
																  badanb="",
																  status_medik="'.$data19.'",
																  status_bayar="0",
																  status_aktif="Upload",
																  regional="'.$cekdatawilayah['regional'].'",
																  area="'.$cekdatawilayah['area'].'",
																  cabang="'.$data3.'",
																  input_by ="'.$_SESSION['nm_user'].'",
															      input_time ="'.$futgl.'"');
			}
			if ($error) {	echo '<tr><td colspan="27" align="center"><font color="red"><blink>Silahkan lengkapi kolom yang error !!!</blink><br /><a href="ajk_uploader.php?er=cancelspaj&fileclient='.$_FILES['userfile']['name'].'">Back</a></font></td></tr>';	}
			else		{	echo '<tr><td colspan="27" align="center"><a title="Approve data upload" href="ajk_uploader.php?er=approveuser&nmfile='.$_FILES['userfile']['name'].'&dateupl='.$futgl.'&idc='.$fu['id'].'" onClick="if(confirm(\'Apakah anda sudah yakin dengan semua data peserta ini ?\')){return true;}{return false;}"><img src="image/save.png" border="0" width="50"></a>
	 					   &nbsp; &nbsp; <a title="Batalkan data upload" href="ajk_uploader.php?er=cancelspaj&fileclient='.$_FILES['userfile']['name'].'"><img src="image/deleted.png" border="0" width="50"></a></td></tr>';	}
			echo '</table></form>';
		}
	;
	break;

case "cancelspaj":
	$cha = $database->doQuery('DELETE FROM fu_ajk_peserta_tempf WHERE namafile="'.$_REQUEST['fileclient'].'"');
	header("location:ajk_uploader.php?er=spaj");
	;
	break;

case "approveuser":
$met_appr = $database->doQuery('SELECT * FROM fu_ajk_peserta_tempf WHERE id_cost="'.$_REQUEST['idc'].'" AND namafile="'.$_REQUEST['nmfile'].'" AND input_time="'.$_REQUEST['dateupl'].'"');

$message .= '<table width="100%" broder="0" cellpadding="3" cellspacing="1">
			<tr><th width="1%">No</th>
			<th width="5%">Nama Mitra</th>
			<th width="8%">Regional</th>
			<th width="8%">Area</th>
			<th width="8%">Cabang</th>
			<th>Nama Tertanggung</th>
			<th width="5%">Tanggal Lahir</th>
			<th width="5%">Uang Asuransi</th>
			<th width="5%">Mulai Asuransi</th>
			<th width="1%">Tenor<br />(jumlah bulan)</th>
			<th width="1%">Usia</th>
			<th width="5%">Tarif Premi</th>
			<th width="5%">Premi Standar</th>
			<th width="5%">Ext.Premi</th>
			<th width="5%">Premi Sekaligus</th>
			<th width="5%">Medical</th>
		</tr>';
while ($mamet_appr = mysql_fetch_array($met_appr)) {
$cekratepolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$mamet_appr['id_cost'].'" AND id_polis="'.$mamet_appr['id_polis'].'" AND tenor="'.$mamet_appr['kredit_tenor'].'" AND status="baru"'));	//VALIDASI CEK TENOR DENGAN SETTING RATE POLIS
$premistandar = $mamet_appr['kredit_jumlah'] * $cekratepolis['rate'] / 1000;
if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
$message .='<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
			<td align="center">'.++$no.'</td>
			<td align="center">'.$mamet_appr['spaj'].'</td>
			<td align="center">'.$mamet_appr['regional'].'</td>
			<td align="center">'.$mamet_appr['area'].'</td>
			<td align="center">'.$mamet_appr['cabang'].'</td>
			<td>'.$mamet_appr['nama'].'</td>
			<td align="center">'._convertDate($mamet_appr['tgl_lahir']).'</td>
			<td align="right">'.duit($mamet_appr['kredit_jumlah']).'</td>
			<td align="center">'._convertDate($mamet_appr['kredit_tgl']).'</td>
			<td align="center">'.duit($mamet_appr['kredit_tenor']).'</td>
			<td align="center">'.$mamet_appr['usia'].'</td>
			<td align="center">'.$cekratepolis['rate'].'</td>
			<td align="right">'.duit($premistandar).'</td>
			<td align="center">'.$mamet_appr['ext_premi'].'</td>
			<td align="right">'.duit($premistandar).'</td>
			<td align="center">'.$mamet_appr['status_medik'].'</td>
	  		</tr>';
}
$message .='</table>';
echo $message;

	$mail = new PHPMailer; // call the class
	$mail->IsSMTP();
	$mail->Host = SMTP_HOST; //Hostname of the mail server
	$mail->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
	//$mail->Username = SMTP_UNAME; //Username for SMTP authentication any valid email created in your domain
	//$mail->Password = SMTP_PWORD; //Password for SMTP authentication
	//$mail->AddReplyTo = SMTP_REPLYMAIL; //reply-to address
	$mail->SetFrom ($q['email'], $q['nm_lengkap']); //From address of the mail
	$mail->Subject = "AJKOnline - UPLOAD DATA PSERTA BARU SPAJ"; //Subject od your mail
	//EMAIL PENERIMA  SPV CLIENT
	$mailsupervisorajk = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="'.$q['id_cost'].'" AND id_polis="'.$q['id_polis'].'" AND level="3" AND status=""');
	while ($_mailsupervisorajk = mysql_fetch_array($mailsupervisorajk)) {
	$mail->AddAddress($_mailsupervisorajk['email'], $_mailsupervisorajk['nm_lengkap']); //To address who will receive this email
	}
	//EMAIL PENERIMA  SPV CLIENT

	//EMAIL PENERIMA  KANTOR U/W
	$mailclient = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="" AND status="" AND level="1"');
	while ($_mailclient = mysql_fetch_array($mailclient)) {
	$mail->AddAddress($_mailclient['email'], $_mailclient['nm_lengkap']); //To address who will receive this email
	}
	//EMAIL PENERIMA  KANTOR U/W

	$mail->AddCC("penting_kaga@yahoo.com");
	//$mail->AddAddress($mailsupervisorajk['email'], $mailsupervisorajk['nm_lengkap']); //To address who will receive this email
	//$mail->AddCC($approvemail);
	$mail->MsgHTML('<table><tr><th>Data peserta baru SPAJ telah diinput oleh <b>'.$_SESSION['nm_user'].' selaku staff AJK-Online pada tanggal '.$futgl.'</tr></table>'.$message); //Put your body of the message you can place html code here
	$send = $mail->Send(); //Send the mails
	//echo $mail;
echo '<center>Data Peserta Baru SPAJ sudah diinput oleh <b>'.$_SESSION['nm_user'].'</b>, tunggu konfirmasi selanjutnya dari Supervisor untuk pencetakan nomor DN.<br /><a href="ajk_uploader.php?er=spaj">Kembali Ke Halaman Utama</a></center>';
	;
	break;

case "spaj":
echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="100%" align="left">Modul Upload Data Kepesertaan (SPAJ)</font></th></tr></table>';
$fu1 = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$q['id_cost'].'"'));
$fu2 = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$q['id_polis'].'"'));
echo '<form name="f1" method="post" enctype="multipart/form-data" action="ajk_uploader.php?er=parse_spaj">
	<table border="0" width="60%" align="center">
	<tr><td width="15%" align="right">Nama Perusahaan</td>
		  <td width="30%">: <input type="hidden" name="idcost" value="'.$q['id_cost'].'">'.$fu1['name'].'</td></tr>
		<tr><td width="10%" align="right">Produk</td>
			<td width="20%">: <input type="hidden" name="idpolis" value="'.$q['id_polis'].'"> '.$fu2['nmproduk'].' ('.$fu2['nopol'].')</td></tr>
	  <tr><td align="right">Silakan Pilih File Excel<br /><font color="red" size="2">Format excel <b>.xls</b> </td><td valign="top">: <input name="userfile" type="file" size="50" onchange="checkfile(this);" ></td></tr>
	  <tr><td align="right">Batas Akhir Baris </td><td>: <input type="text" name="bataskolom" value="'.$_REQUEST['bataskolom'].'" size="5" maxlength="3" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"></td></tr>
	  <tr><td align="center"colspan="2"><input name="upload" type="submit" value="Import"></td></tr>
	  </table></form>';
		;
		break;

case "spk":
		;
		break;
	default:
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