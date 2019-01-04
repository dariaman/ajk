<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("../includes/functions.php");
connect();

if (session_is_registered('nm_user')) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));	}
switch ($_REQUEST['gen']) {
	case "cancell":
		$cha = $database->doQuery('DELETE FROM fu_ajk_peserta_tempf WHERE namafile="'.$_REQUEST['fileclient'].'"');
		header("location:ajk_uploader_general.php");
		;
		break;
	case "fuparsing":
$fu = mysql_fetch_array($database->doQuery('SELECT fu_ajk_costumer.name, fu_ajk_costumer.id FROM fu_ajk_costumer WHERE id="'.$_REQUEST['cat'].'"'));
$fufile = mysql_fetch_array($database->doQuery('SELECT fu_ajk_peserta.namafile FROM fu_ajk_peserta WHERE id_cost="'.$_REQUEST['cat'].'"'));
$fupolis = mysql_fetch_array($database->doQuery('SELECT fu_ajk_polis.nopol, fu_ajk_polis.id FROM fu_ajk_polis WHERE id="'.$_REQUEST['subcat'].'"'));

$_REQUEST['cat'] = $_POST['cat'];					if (!$_REQUEST['cat']) 		   $error .='Silahkan pilih nama perusahaan<br />.';
$_REQUEST['bataskolom'] = $_POST['bataskolom'];		if (!$_REQUEST['bataskolom'])  $error .='Silahkan tentukan batas kolom file<br />.';
if (!$_FILES['userfile']['tmp_name'])  $error .='Silahkan upload file excel anda<br />.';
		$allowedExtensions = array("xls","xlsx","csv");
		foreach ($_FILES as $file) {
			if ($file['tmp_name'] > '') {
				if (!in_array(end(explode(".",	strtolower($file['name']))), $allowedExtensions)) {
					die('<center><font color=red>'.$file['name'].' <br /><blink>File extension tidak diperbolehkan selain excel!</blink></font><br/>'.'<a href="ajk_uploader_general.php">'.'&lt;&lt Go Back</a></center>');
				}
			}
		}
if ($error)
{	echo '<blink><center><font color=red>'.$error.'</font></blink><a href="ajk_uploader_general.php">'.'&lt;&lt Go Back</a></center>';	}

else
{
	echo '<form method="post" action="ajk_uploader.php?r=approveuser" onload ="onbeforeunload">
	  <table border="0" width="100%" cellpadding="1" cellspacing="1" style="border: solid 3px #DEDEDE"  bgcolor="#bde0e6">
		<tr><td colspan="2"><input type="hidden" name="idcostumer" value="'.$fu['id'].'">Nama Perusahaan</td><td colspan="24">: <b>'.$fu['name'].'</b></td></tr>
		<tr><td colspan="2"><input type="hidden" name="bataskolom" value="'.$_REQUEST['bataskolom'].'"><input type="hidden" name="namafileexl" value="'.$_FILES['userfile']['name'].'">Nama File</td><td colspan="24">: <b>'.$_FILES['userfile']['name'].'</b></td></tr>
    <tr>
        <th rowspan="2"width="1%">NO</th>
        <th rowspan="2" width="8%">NAMA MITRA</th>
        <th rowspan="2">NAMA TERTANGGUNG</th>
        <th colspan="3">TANGGAL LAHIR</th>
        <th rowspan="2">UANG ASURANSI</th>
        <th colspan="3">MULAI ASURANSI</th>
        <th rowspan="2">TENOR</th>
        <th rowspan="2">EXT. PREMI</th>
        <th rowspan="2">JENIS KREDIT (KI/KMK)</th>
        <th rowspan="2">TENAGA KERJA SAAT PENGAJUAN</th>
        <th rowspan="2">SEKTOR USAHA</th>
        <th colspan="4">AGUNAN TAMBAHAN (bila ada)</th>
        <th rowspan="2">JASA PENJAMINAN</th>
        <th rowspan="2">REGIONAL</th>
        <th rowspan="2">AREA</th>
        <th rowspan="2">CABANG</th>
    </tr>
    <tr>
        <th>TGL</th>
        <th>BLN</th>
        <th>THN</th>
        <th>TGL</th>
        <th>BLN</th>
        <th>THN</th>
        <th>MACAM AGUNAN</th>
        <th>RESIKO AGUNAN</th>
        <th>CARA PENGIKATA</th>
        <th>NILAI PASAR WAJAR</th>
    </tr>';
	$data = new Spreadsheet_Excel_Reader($_FILES['userfile']['tmp_name']);
	$baris = $data->rowcount($sheet_index=0);											//MEMBACA JUMLAH BARIS DATA EXCEL

for ($i=10; $i<=$_REQUEST['bataskolom']; $i++)
{
	$data1=$data->val($i, 1);		//no
	$data2=$data->val($i, 2);		//NAMA MITRA
	$data3=$data->val($i, 3);		//NAMA TERTANGGUNG
	$data4=$data->val($i, 4);		//TANGGAL LAHIR (TGL)
	$data5=$data->val($i, 5);		//TANGGAL LAHIR (BLN)
	$data6=$data->val($i, 6);		//TANGGAL LAHIR (THN)
	$data7=$data->val($i, 7);		//UANG ASURANSI
	$data8=$data->val($i, 8);		//MULAI ASURANSI (TGL)
	$data9=$data->val($i, 9);		//MULAI ASURANSI (BLN)
	$data10=$data->val($i, 10);		//MULAI ASURANSI (THN)
	$data11=$data->val($i, 11);		//MASA ASURANSI
	$data12=$data->val($i, 12);		//EXT. PREMI
	$data13=$data->val($i, 13);		//TENAGA KERJA SAAT PENGAJUAN
	$data14=$data->val($i, 14);		//SEKTOR USAHA
	$data15=$data->val($i, 15);		//MACAM AGUNAN
	$data16=$data->val($i, 16);		//RESIKO AGUNAN
	$data17=$data->val($i, 17);		//CARA PENGIKATAN
	$data18=$data->val($i, 18);		//CARA PENGIKATAN
	$data19=$data->val($i, 19);		//NILAI PASAR WAJAR
	$data20=$data->val($i, 20);		//JASA PENJAMINAN
	$data21=$data->val($i, 21);		//REGIOALLL
	$data22=$data->val($i, 22);		//AREA
	$data23=$data->val($i, 23);		//CABANG

	//VALIDASI DATA UPLOAD//
	if ($data3==""){ $error ='<font color="red">error</font>'; $dataexcel3=$error;}else{ $dataexcel3=$data3;}
	if ($data4==""){ $error ='<font color="red">error</font>'; $dataexcel4=$error;}
	elseif(!is_numeric($data4)){$error ='<font color="red">error</font>'; $dataexcel4=$error;}
	elseif(strlen($data4 > 31)){$error ='<font color="red">error</font>'; $dataexcel4=$error;}
	else{ $dataexcel4=$data4;}

	if ($data5==""){ $error ='<font color="red">error</font>'; $dataexcel5=$error;}
	elseif(!is_numeric($data5)){$error ='<font color="red">error</font>'; $dataexcel5=$error;}
	elseif(strlen($data5 > 12)){$error ='<font color="red">error</font>'; $dataexcel5=$error;}
	else{ $dataexcel5=$data5;}

	if ($data6==""){ $error ='<font color="red">error</font>'; $dataexcel6=$error;}
	elseif(!is_numeric($data6)){$error ='<font color="red">error</font>'; $dataexcel6=$error;}
	elseif(strlen($data6 >= $dateY )){$error ='<font color="red">error</font>'; $dataexcel6=$error;}
	else{ $dataexcel6=$data6;}

	if ($data7==""){ $error ='<font color="red">error</font>'; $dataexcel7=$error;}else{ $dataexcel7=$data7;}
	if ($data8==""){ $error ='<font color="red">error</font>'; $dataexcel8=$error;}
	elseif(!is_numeric($data8)){$error ='<font color="red">error</font>'; $dataexcel8=$error;}
	elseif(strlen($data8 > 31)){$error ='<font color="red">error</font>'; $dataexcel8=$error;}
	else{ $dataexcel8=$data8;}

	if ($data9==""){ $error ='<font color="red">error</font>'; $dataexcel9=$error;}
	elseif(!is_numeric($data9)){$error ='<font color="red">error</font>'; $dataexcel9=$error;}
	elseif(strlen($data9 > 12)){$error ='<font color="red">error</font>'; $dataexcel9=$error;}
	else{ $dataexcel9=$data9;}

	if ($data10==""){ $error ='<font color="red">error</font>'; $dataexcel10=$error;}
	elseif(!is_numeric($data10)){$error ='<font color="red">error</font>'; $dataexcel10=$error;}
	elseif(strlen($data10 > $dateY)){$error ='<font color="red">error</font>'; $dataexcel10=$error;}
	else{ $dataexcel10=$data10;}

	if ($data11==""){ $error ='<font color="red">error</font>'; $dataexcel11=$error;}else{ $dataexcel11=$data11;}

	if ($data13==""){ $error ='<font color="red">error</font>'; $dataexcel13=$error;}
	elseif ($data13 != "Menurun" AND $data13 != "Tetap"){ $error ='<font color="red">error</font>'; $dataexcel13=$error;}
	else{ $dataexcel13=$data13;}

	//if ($data14==""){ $error ='<font color="red">error</font>'; $dataexcel14=$error;}else{ $dataexcel14=$data14;}
	//if ($data15==""){ $error ='<font color="red">error</font>'; $dataexcel15=$error;}else{ $dataexcel15=$data15;}

	if ($data16==""){ $error ='<font color="red">error</font>'; $dataexcel16=$error;}
	elseif ($data16 != "MV" AND $data16 != "FA"){ $error ='<font color="red">error</font>'; $dataexcel16=$error;}
	else{ $dataexcel16=$data16;}

	if ($data17==""){ $error ='<font color="red">error</font>'; $dataexcel17=$error;}
	elseif ($data16=="MV"){
		if ($data17!="ALL RISK" AND $data17!="TLO") {	$error ='<font color="red">error</font>'; $dataexcel17=$error;		}
		else{ $dataexcel17=$data17;}
		}
	elseif ($data16=="FA"){
		if ($data17!="FA") {	$error ='<font color="red">error</font>'; $dataexcel17=$error;		}
		else{ $dataexcel17=$data17;}
	}
	else{ $dataexcel17=$data17;}

	//if ($data18==""){ $error ='<font color="red">error</font>'; $dataexcel18=$error;}else{ $dataexcel18=$data18;}
	//if ($data19==""){ $error ='<font color="red">error</font>'; $dataexcel19=$error;}else{ $dataexcel19=$data19;}
	//if ($data20==""){ $error ='<font color="red">error</font>'; $dataexcel20=$error;}else{ $dataexcel20=$data20;}
	$cekdatareg = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE id_cost="'.$fu['id'].'" AND name="'.$data21.'"'));			//VALIDASI REGIONAL
	if ($data21 != $cekdatareg['name']) {$error ='<font color="red">error</font>';	$dataexcel21=$error;	}else{	$dataexcel21=$data21;	}			//VALIDASI REGIONAL

	$cekdataarea = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_area WHERE id_cost="'.$fu['id'].'" AND name="'.$data22.'"'));				//VALIDASI AREA
	if ($data22 != $cekdataarea['name']) {$error ='<font color="red">error</font>';	$dataexcel22=$error;	}else{	$dataexcel22=$data22;	}			//VALIDASI AREA

	$cekdatacab = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id_cost="'.$fu['id'].'" AND name="'.$data23.'"'));			//VALIDASI CABANG
	if ($data23 != $cekdatacab['name']) {$error ='<font color="red">error</font>';	$dataexcel23=$error;	}else{	$dataexcel23=$data23;	}			//VALIDASI CABANG
	//VALIDASI DATA UPLOAD//


if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
        <td align="center">'.$data1.'</td>
        <td>'.$data2.'</td>
        <td>'.$dataexcel3.'</td>
        <td align="center">'.$dataexcel4.'</td>
        <td align="center">'.$dataexcel5.'</td>
        <td align="center">'.$dataexcel6.'</td>
        <td>'.$dataexcel7.'</td>
        <td align="center">'.$dataexcel8.'</td>
        <td align="center">'.$dataexcel9.'</td>
        <td align="center">'.$dataexcel10.'</td>
        <td align="center">'.$dataexcel11.'</td>
        <td align="center">'.$dataexcel12.'</td>
        <td align="center">'.$dataexcel13.'</td>
        <td>'.$dataexcel14.'</td>
        <td>'.$dataexcel15.'</td>
        <td align="center">'.$dataexcel16.'</td>
        <td align="center">'.$dataexcel17.'</td>
        <td>'.$dataexcel18.'</td>
        <td>'.$dataexcel19.'</td>
        <td>'.$dataexcel20.'</td>
        <td>'.$dataexcel21.'</td>
        <td>'.$dataexcel22.'</td>
        <td>'.$dataexcel23.'</td>
    </tr>';
	//$exl = $database->doQuery('INSERT IGNORE fu_ajk_peserta_tempf SET id_cost="'.$fu['id'].'",
if ($data4 < 9) { $data4_ = '0'.$data4;	}else{	$data4_ = $data4;}
if ($data5 < 9) { $data5_ = '0'.$data5;	}else{	$data5_ = $data5;}
	$datatgllahirnya = $data6.'-'.$data5_.'-'.$data4_;

if ($data8 < 9) { $data8_ = '0'.$data8;	}else{	$data8_ = $data8;}
if ($data9 < 9) { $data9_ = '0'.$data9;	}else{	$data9_ = $data9;}
	$datatglkreditnya = $data10.'-'.$data9_.'-'.$data8_;

	//$cekdatadbl = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_cost="'.$fu['id'].'", nama="'.$data3.'", tgl_lahir="'.$datatgllahirnya.'", kredit_tgl="'.$datatglkreditnya.'", kredit_jumlah="'.$data7.'", kredit_tenor="'.$data11.'", cabang="'.$data15.'"'));

$met = $database->doQuery('INSERT IGNORE fu_ajk_peserta_tempf SET id_cost="'.$fu['id'].'",
																  id_polis="'.$fupolis['id'].'",
																  namafile="'.$_FILES['userfile']['name'].'",
																  no_urut="'.$data1.'",
																  spaj="",
																  type_data="GENERAL",
																  nama_mitra="'.$data2.'",
																  nama="'.$data3.'",
																  gender="",
																  tgl_lahir="'.$datatgllahirnya.'",
																  usia="",
																  kredit_tgl="'.$datatglkreditnya.'",
																  kredit_jumlah="'.$data7.'",
																  kredit_tenor="'.$data11.'",
																  kredit_akhir="",
																  premi="",
																  disc_premi="",
																  bunga="",
																  biaya_adm="",
																  ext_premi="'.$data12.'",
																  totalpremi="",
																  badant="",
																  badanb="",
																  status_medik="",
																  status_bayar="0",
																  status_aktif="Upload",
																  regional="'.$data13.'",
																  area="'.$data14.'",
																  cabang="'.$data15.'",
																  input_by ="'.$_SESSION['nm_user'].'",
															      input_time ="'.$futgl.'"');
}
if ($error) {	echo '<tr><td colspan="27" align="center"><font color="red"><blink>Silahkan lengkapi kolom yang error !!!</blink><br /><a href="ajk_uploader_general.php?r=cancell&fileclient='.$_FILES['userfile']['name'].'">Back</a></font></td></tr>';	}
else		{	echo '<tr><td colspan="27" align="center"><a title="Approve data upload" href="ajk_uploader_general.php?r=approveuser&nmfile='.$_FILES['userfile']['name'].'&dateupl='.$futgl.'&idc='.$fu['id'].'" onClick="if(confirm(\'Apakah anda sudah yakin dengan semua data peserta ini ?\')){return true;}{return false;}"><img src="image/save.png" border="0" width="50"></a>
	 					   &nbsp; &nbsp; <a title="Batalkan data upload" href="ajk_uploader_general.php?r=cancell&fileclient='.$_FILES['userfile']['name'].'"><img src="image/deleted.png" border="0" width="50"></a></td></tr>';	}
	echo '</table></form>';
}
		;
		break;
	default:
echo '<table border="0" cellpadding="0" cellspacing="0" width="100%">
	  <tr><th width="100%" align="left">Modul Upload Data Peserta</font></th></tr>
	  </table>';
echo '<form name="upl_general" method="post" enctype="multipart/form-data" action="ajk_uploader_general.php?gen=fuparsing">
	  <table border="0" width="60%" align="center">
	  <tr><td width="15%" align="right">Nama Perusahaan <font color="red">*</font></td>
	  	  <td width="30%">: <select name="cat">
		  <option value="">--- Perusahaan ---</option>';
$quer2=$database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name ASC');
while($noticia2 = mysql_fetch_array($quer2)){
	echo '<option value="'.$noticia2['id'].'">'.$noticia2['name'].'</option>';
}
echo '</select></td></tr>
	  <tr><td align="right">Silakan Pilih File Excel <font color="red">*</font></td><td>: '.$bataskolom.' <input name="userfile" type="file" size="50" onchange="checkfile(this);" ></td></tr>
	  <tr><td align="right">Batas Akhir Baris <font color="red">*</font></td><td>: <input type="text" name="bataskolom" value="'.$bataskolom.'" size="1"></td></tr>
	  <tr><td align="center"colspan="2"><input name="upload" type="submit" value="Import"></td></tr>
	  </table></form>';
		;
} // switch
?>