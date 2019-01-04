<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// @copyright 2014
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("../includes/functions.php");
connect();
if (session_is_registered('nm_user')) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));}
switch ($_REQUEST['er']) {
case "approve_rate":
echo '<table border="0" cellpadding="1" cellspacing="0" width="100%">
     <tr><th width="100%" align="left">Modul Import Rate Premi</font></th><th><a href="ajk_reg_cost.php"><img src="image/back.png" width="20"></a></th></tr>
     </table>';
$met_rate = $database->doQuery('INSERT INTO fu_ajk_ratepremi(id_cost, id_polis, type, usia, tenor, mpp_s, mpp_e, rate, filename, eff_from, eff_to, input_by, input_date)
								SELECT id_cost, id_polis, type, usia, tenor, mpp_s, mpp_e, rate, filename, eff_from, eff_to, input_by, input_date
								FROM fu_ajk_ratepremi_temp
								WHERE id_cost="'.$_REQUEST['idc'].'" AND
									  id_polis="'.$_REQUEST['idp'].'" AND
									  filename="'.$_REQUEST['idf'].'"');
$del_rate = $database->doQuery('DELETE  FROM fu_ajk_ratepremi_temp WHERE id_cost="'.$_REQUEST['idc'].'" AND id_polis="'.$_REQUEST['idp'].'" AND filename="'.$_REQUEST['idf'].'"');
echo '<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border: solid 1px blue">
	  <tr><td><table width="100%" class="bgcolor1">
	  <tr><td align="left"><img src="image/warning.gif" border="0"></td>
		  <td align="center"><font class="option"><blink>Data Rate Telah Berasil Di Buat</blink></td>
		  <td align="right"><img src="image/warning.gif" border="0"></td>
	  </tr>
	  </table></td></tr>
	  </table>
	  <meta http-equiv="refresh" content="3; url=ajk_setrate.php?er=setpremi">';
	;
	break;
case "batal_rate":
	$el = $database->doQuery('DELETE FROM fu_ajk_ratepremi_temp WHERE id_cost="'.$_REQUEST['id'].'" AND filename="'.$_REQUEST['fileclient'].'"');
	header('location:ajk_setrate.php?er=setpremi');
	;
	break;
case "setpremi":
echo '<table border="0" cellpadding="1" cellspacing="0" width="100%">
     <tr><th width="100%" align="left">Modul Import Rate Premi</font></th><th><a href="ajk_reg_cost.php"><img src="image/back.png" width="20"></a></th></tr>
     </table>';
$metcost = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name DESC');
echo '<form name="f1" method="post" enctype="multipart/form-data" action="">
	  <input type="hidden" name="id_cost" value="'.$metcost['id'].'">
	  <table border="0" cellpadding="1" cellspacing="0" width="100%">
      <tr><td width="40%" align="right">Nama Perusahaan</td><td> : <select name="id_cost" id="id_cost">
	  	<option value="">---Pilih Perusahaan---</option>';
		while($metcost_ = mysql_fetch_array($metcost)) {
			echo  '<option value="'.$metcost_['id'].'">'.$metcost_['name'].'</option>';
		}
		echo '</select></td></tr>
	  <tr><td align="right">Nama Produk</td>
	<td id="polis_rate">: <select name="id_polis" id="id_polis">
	<option value="">-- Pilih Produk --</option>
</select></td></tr>
	  <tr><td align="right">Silakan Pilih File Excel </td><td>: '.$bataskolom.' <input name="userfile" type="file" size="50" onchange="checkfile(this);" ></td></tr>
	  <tr><td align="right">Batas Akhir Baris </td><td>: <input type="text" name="bataskolom" value="'.$bataskolom.'" size="3" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"></td></tr>
	  <tr><td align="center"colspan="2"><input type="hidden" name="re" value="upl_rate"><input type="submit" name="upload_rate" value="Import"></td></tr>
	  </table>
	  </form>';
if ($_REQUEST['re']=="upl_rate") {
$_REQUEST['id_polis'] = $_POST['id_polis'];		if (!$_REQUEST['id_polis'])  $error .='<blink><font color=red>Silahkan pilih Nomor Polis. !</font></blink><br>';
$_REQUEST['bataskolom'] = $_POST['bataskolom'];	if (!$_REQUEST['bataskolom'])  $error .='<blink><font color=red>Silahkan tentukan batas kolom pada data yang di Upload. !</font></blink><br>';
if (!$_FILES['userfile']['tmp_name'])  $error .='<blink><font color=red>Silahkan upload file excel anda</font></blink>.';
$allowedExtensions = array("xls","xlsx","csv");
	foreach ($_FILES as $file) {
	if ($file['tmp_name'] > '') {
		if (!in_array(end(explode(".",	strtolower($file['name']))),
			$allowedExtensions)) {	die('<center><font color=red>'.$file['name'].' <br /><blink><font color=red>File extension tidak diperbolehkan selain excel!</blink></font></center>');	}
			}
		}
$cekratepreminya = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$_REQUEST['id_cost'].'" AND id_polis="'.$_REQUEST['id_polis'].'" AND status="baru"'));
if ($cekratepreminya['id_cost']==$_REQUEST['id_cost'] AND $cekratepreminya['id_polis']==$_REQUEST['id_polis'] AND $cekratepreminya['status']=="baru") {
	$error .='<font color=red>Rate premi sudah ada, rate tidak bisa di upload.!</font>';
}
	if ($error)
	{	echo '<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border: solid 1px red">
			  <tr><td><table width="100%" class="bgcolor1">
			  <tr><td align="left"><img src="image/warning.gif" border="0"></td>
				  <td align="center"><font class="option"><blink>'.$error.'</blink></td>
				  <td align="right"><img src="image/warning.gif" border="0"></td>
			  </tr>
			  </table></td></tr>
			  </table><meta http-equiv="refresh" content="4; url=ajk_setrate.php?er=setpremi">';
	}
	else
	{
	echo '<hr>';
	$mametset = mysql_fetch_array($database->doQuery('SELECT fu_ajk_costumer.id AS idCostumer,
															 fu_ajk_polis.id AS idPolis,
															 fu_ajk_costumer.`name` AS namaC,
															 fu_ajk_polis.nopol AS nomorP,
															 fu_ajk_polis.benefit AS benefitnya,
															 fu_ajk_polis.mpptype AS mpptypenya,
															 fu_ajk_polis.singlerate AS singleratenya
								FROM fu_ajk_polis
								INNER JOIN fu_ajk_costumer ON fu_ajk_costumer.id = fu_ajk_polis.id_cost
								WHERE fu_ajk_costumer.id="'.$_REQUEST['id_cost'].'" AND fu_ajk_polis.id="'.$_REQUEST['id_polis'].'"'));
	if ($mametset['benefit']=="D") 			{	$typeratenya = "Decreasing";	}else{	$typeratenya = "Flat/Level";	}
	if ($mametset['singleratenya']=="Y")	{	$singleratenya = "Ya (Usia, Tenor dan Rate)";	}else{	$singleratenya = "Tidak (Tenor dan Rate)";	}

	echo '<form name="f1" method="post" enctype="multipart/form-data" action="">
		  <table width="100%" border="0" cellspacing="3" cellpadding="1">
		  <tr><td width="10%">Nama Perusahaan</td><td>: <b>'.$mametset['namaC'].'</b></td></tr>
		  <tr><td>Nomor Polis</td><td>: <b>'.$mametset['nomorP'].'</b></td></tr>
		  <tr><td>Type Rate</td><td>: <b>'.$typeratenya.'</b></td></tr>
		  <tr><td>Single Rate By Usia</td><td>: <b>'.$singleratenya.'</b></td></tr>
		  <tr><td>Nama File</td><td>: <b>'.$_FILES['userfile']['name'].'</b></td></tr>
		  <tr><td>Batas Kolom</td><td>: <b>'.$_REQUEST['bataskolom'].'</b></td></tr>
		  </table>';
	echo '<table width="30%" border="0" cellspacing="1" cellpadding="1" align="center">';
	$data = new Spreadsheet_Excel_Reader($_FILES['userfile']['tmp_name']);
	$baris = $data->rowcount($sheet_index=0);											//MEMBACA JUMLAH BARIS DATA EXCEL
	//KONDISI RATE TETAP ATAU MENURUN
if ($mametset['mpptypenya']=="Y") {
echo '<tr><th width="1%">No</th><th>Tenor(bln)</th><th>MPP_mulai(bln)</th><th>MPP_akhir(bln)</th><th>Rate</th></tr>';
for ($i=2; $i<=$_REQUEST['bataskolom']; $i++)
{
	$data1=$data->val($i, 1);		//TENOR BLN/THN
	$data2=$data->val($i, 2);		//MPP MULAI
	$data3=$data->val($i, 3);		//MPP AKHIR
	$data4=$data->val($i, 4);		//RATE

	//VALIDASI KOLOM RATE
	if ($data1==""){ $error1 ='<font color="red">error</font>'; $dataexcel1=$error1;}else{ $dataexcel1=$data1;}
	if ($data2==""){ $error2 ='<font color="red">error</font>'; $dataexcel2=$error2;}else{ $dataexcel2=$data2;}
	if ($data3==""){ $error3 ='<font color="red">error</font>'; $dataexcel3=$error3;}else{ $dataexcel3=$data3;}
	if ($data4==""){ $error4 ='<font color="red">error</font>'; $dataexcel4=$error4;}else{ $dataexcel4=$data4;}
	//VALIDASI KOLOM RATE

if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		<td align="center">'.++$no.'</td>
		<td align="center">'.$dataexcel1.'</td>
		<td align="center">'.$dataexcel2.'</td>
		<td align="center">'.$dataexcel3.'</td>
		<td align="center">'.$dataexcel4.'</td>
	  </tr>';
$met_rate = $database->doQuery('INSERT IGNORE fu_ajk_ratepremi_temp SET id_cost="'.$_REQUEST['id_cost'].'",
																		id_polis="'.$_REQUEST['id_polis'].'",
																		tenor="'.$data1.'",
																		mpp_s="'.$data2.'",
																		mpp_e="'.$data3.'",
																		rate="'.$data4.'",
																		type="'.$mametset['benefitnya'].'",
																		filename="'.$_FILES['userfile']['name'].'",
																		eff_from="'.$datelog.'",
																		eff_to="2500-12-30",
																		input_by="'.$q['nm_lengkap'].'",
																		input_date="'.$futgl.'" ');
}
}else{
if ($mametset['singleratenya']=="T") {
echo '<tr><th width="1%">No</th><th>Tenor(bln)</th><th>Rate</th></tr>';
for ($i=2; $i<=$_REQUEST['bataskolom']; $i++)
{
	$data1=$data->val($i, 1);		//TENOR BLN/THN
	$data2=$data->val($i, 2);		//RATE

	//VALIDASI KOLOM RATE
	if ($data1==""){ $error1 ='<font color="red">error</font>'; $dataexcel1=$error1;}else{ $dataexcel1=$data1;}
	if ($data2==""){ $error2 ='<font color="red">error</font>'; $dataexcel2=$error2;}else{ $dataexcel2=$data2;}
	//VALIDASI KOLOM RATE

if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		<td align="center">'.++$no.'</td>
		<td align="center">'.$dataexcel1.'</td>
		<td align="center">'.$dataexcel2.'</td>
	  </tr>';
$met_rate = $database->doQuery('INSERT IGNORE fu_ajk_ratepremi_temp SET id_cost="'.$_REQUEST['id_cost'].'",
																		id_polis="'.$_REQUEST['id_polis'].'",
																		tenor="'.$data1.'",
																		rate="'.$data2.'",
																		type="'.$mametset['benefitnya'].'",
																		filename="'.$_FILES['userfile']['name'].'",
																		input_by="'.$q['nm_lengkap'].'",
																		input_date="'.$futgl.'" ');
}
}else{
echo '<tr><th width="1%">No</th><th>Usia</th><th>Tenor(thn)</th><th>Rate</th></tr>';
for ($i=2; $i<=$_REQUEST['bataskolom']; $i++)
{
	$data1=$data->val($i, 1);		//USIA
	$data2=$data->val($i, 2);		//TENOR
	$data3=$data->val($i, 3);		//RATE THN

	//VALIDASI KOLOM RATE
	if ($data1==""){ $error1 ='<font color="red">error</font>'; $dataexcel1=$error1;}else{ $dataexcel1=$data1;}
	if ($data2==""){ $error2 ='<font color="red">error</font>'; $dataexcel2=$error2;}else{ $dataexcel2=$data2;}
	if ($data3==""){ $error3 ='<font color="red">error</font>'; $dataexcel3=$error3;}else{ $dataexcel3=$data3;}
	//VALIDASI KOLOM RATE

if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	<td align="center">'.++$no.'</td>
	<td align="center">'.$dataexcel1.'</td>
	<td align="center">'.$dataexcel2.'</td>
	<td align="center">'.$dataexcel3.'</td>
	</tr>';
$met_rate = $database->doQuery('INSERT IGNORE fu_ajk_ratepremi_temp SET id_cost="'.$_REQUEST['id_cost'].'",
																		id_polis="'.$_REQUEST['id_polis'].'",
																		type="'.$mametset['benefitnya'].'",
																		usia="'.$data1.'",
																		tenor="'.$data2.'",
																		rate="'.$data3.'",
																		filename="'.$_FILES['userfile']['name'].'",
																		input_by="'.$q['nm_lengkap'].'",
																		input_date="'.$futgl.'" ');
	}
}
}
//KONDISI RATE TETAP ATAU MENURUN
if ($error1 OR $error2 OR $error3) {
//echo '<tr><td colspan="6" align="center"><a href="ajk_reg_cost_rate.php?re=upl_rate_btl&id='.$_REQUEST['id_cost'].'&fileclient='.$_FILES['userfile']['name'].'"><img src="image/deleted.png" width="32"><br />[Batal]</a></td></tr>';
echo '<tr><td colspan="6" align="center"><a href="ajk_setrate.php?er=batal_rate&id='.$_REQUEST['id_cost'].'&fileclient='.$_FILES['userfile']['name'].'"><img src="image/deleted.png" width="32"><br />[Batal]</a></td></tr>';
}else{
echo '<tr><td colspan="6" align="center"><a href="ajk_setrate.php?er=batal_rate&id='.$_REQUEST['id_cost'].'&fileclient='.$_FILES['userfile']['name'].'"><img src="image/deleted.png" width="32"></a> &nbsp;
									    <a href="ajk_setrate.php?er=approve_rate&idc='.$_REQUEST['id_cost'].'&idp='.$_REQUEST['id_polis'].'&idf='.$_FILES['userfile']['name'].'"><img src="image/save.png" width="32"></a></td>
	</tr>
	<tr><td colspan="6" align="center">Batal &nbsp Approve</td></tr>';
	}
echo '</table>
</form>';
	}
}
		;
		break;

	case "appr_medical":
$met_medic = $database->doQuery('INSERT INTO fu_ajk_medical(id_cost, id_polis, type_medical, age_from, age_to, si_from, si_to, filename, input_by, input_date)
								SELECT id_cost, id_polis, type_medical, age_from, age_to, si_from, si_to, filename, input_by, input_date
								FROM fu_ajk_medical_tempf WHERE id_cost="'.$_REQUEST['idc'].'" AND id_polis="'.$_REQUEST['idp'].'" AND filename="'.$_REQUEST['idf'].'"');
	$del_rate = $database->doQuery('DELETE FROM fu_ajk_medical_tempf WHERE id_cost="'.$_REQUEST['idc'].'" AND id_polis="'.$_REQUEST['idp'].'" AND filename="'.$_REQUEST['idf'].'"');
		echo '<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border: solid 1px blue">
		  <tr><td><table width="100%" class="bgcolor1">
		  <tr><td align="left"><img src="image/warning.gif" border="0"></td>
			  <td align="center"><font class="option"><blink>Data Table Medical Telah Berasil Di Buat</blink></td>
			  <td align="right"><img src="image/warning.gif" border="0"></td>
		  </tr>
		  </table></td></tr>
		  </table><meta http-equiv="refresh" content="3; url=ajk_setrate.php?er=setmedical">';
	;
	break;
	case "setmedical":
echo '<table border="0" cellpadding="1" cellspacing="0" width="100%"><tr><th width="100%" align="left">Modul Import Rate Medical</font></th></tr></table>';
$metcost = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name DESC');
echo '<form name="f1" method="post" enctype="multipart/form-data" action="">
	  <input type="hidden" name="id_cost" value="'.$metcost['id'].'">
	  <table border="0" cellpadding="1" cellspacing="0" width="100%">
      <tr><td width="40%" align="right">Company Name</td><td> : <select name="id_cost" id="id_cost">
		  <option value="">---Select Company---</option>';
while($metcost_ = mysql_fetch_array($metcost)) {	echo  '<option value="'.$metcost_['id'].'">'.$metcost_['name'].'</option>';	}
echo '</select></td></tr>
	  <tr><td align="right">Policy</td>
		  <td id="polis_rate">: <select name="id_polis" id="id_polis">
		  <option value="">-- Select Policy --</option>
	  </select></td></tr>
	  <tr><td align="right">Silakan Pilih File Excel </td><td>: <input name="userfile" type="file" size="50" onchange="checkfile(this);" ></td></tr>
	  <tr><td align="right">Batas Akhir Baris </td><td>: <input type="text" name="bataskolom" value="'.$_REQUEST['bataskolom'].'" size="1" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"></td></tr>
	  <tr><td align="center"colspan="2"><input type="hidden" name="re" value="upl_ratemedik"><input type="submit" name="upload_rate" value="Import"></td></tr>
	  </table>
	  </form>';
if ($_REQUEST['re']=="upl_ratemedik") {
	$_REQUEST['id_cost'] = $_POST['id_cost'];		if (!$_REQUEST['id_cost'])  $error .='<blink><font color=red>Silahkan pilih nama perushaan. !</font></blink><br>';
	$_REQUEST['id_polis'] = $_POST['id_polis'];		if (!$_REQUEST['id_polis'])  $error .='<blink><font color=red>Silahkan pilih Nomor Polis. !</font></blink><br>';
	$_REQUEST['bataskolom'] = $_POST['bataskolom'];	if (!$_REQUEST['bataskolom'])  $error .='<blink><font color=red>Silahkan tentukan batas kolom pada data yang di Upload. !</font></blink><br>';
	if (!$_FILES['userfile']['tmp_name'])  $error .='<blink><font color=red>Silahkan upload file excel anda</font></blink>.';
	$allowedExtensions = array("xls","xlsx","csv");
	foreach ($_FILES as $file) {
		if ($file['tmp_name'] > '') {
			if (!in_array(end(explode(".",	strtolower($file['name']))),
			$allowedExtensions)) {	die('<center><font color=red>'.$file['name'].' <br /><blink><font color=red>File extension tidak diperbolehkan selain excel!</blink></font></center>');	}
		}
	}
	if ($error)
	{	echo '<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border: solid 1px red">
			  <tr><td><table width="100%" class="bgcolor1">
			  <tr><td align="left"><img src="image/warning.gif" border="0"></td>
				  <td align="center"><font class="option"><blink>'.$error.'</blink></td>
				  <td align="right"><img src="image/warning.gif" border="0"></td>
			  </tr>
			  </table></td></tr>
			  </table><meta http-equiv="refresh" content="3; url=ajk_setrate.php?er=setmedical">';
	}
	else
	{
$metmdeical = mysql_fetch_array($database->doQuery('SELECT fu_ajk_costumer.id, fu_ajk_costumer.`name`, fu_ajk_polis.id, fu_ajk_polis.nopol
													FROM fu_ajk_costumer
													INNER JOIN fu_ajk_polis ON fu_ajk_costumer.id = fu_ajk_polis.id_cost
													WHERE fu_ajk_costumer.id="'.$_REQUEST['id_cost'].'" AND fu_ajk_polis.id="'.$_REQUEST['id_polis'].'"'));
echo '<hr><form name="f1" method="post" enctype="multipart/form-data" action="">
	  <table width="100%" border="0" cellspacing="3" cellpadding="1">
	  <tr><td width="10%">Nama Perusahaan</td><td>: <b>'.$metmdeical['name'].'</b></td></tr>
	  <tr><td>Nomor Polis</td><td>: <b>'.$metmdeical['nopol'].'</b></td></tr>
	  <tr><td>Nama File</td><td>: <b>'.$_FILES['userfile']['name'].'</b></td></tr>
	  <tr><td>Batas Kolom</td><td>: <b>'.$_REQUEST['bataskolom'].'</b></td></tr>
	  </table>';
echo '<table width="30%" border="0" cellspacing="1" cellpadding="1" align="center">';
$data = new Spreadsheet_Excel_Reader($_FILES['userfile']['tmp_name']);
$baris = $data->rowcount($sheet_index=0);											//MEMBACA JUMLAH BARIS DATA EXCEL
echo '<tr><th width="1%">No</th><th>Type Medical</th><th>Age From</th><th>Age To</th><th>UP From</th><th>UP To</th></tr>';
for ($i=2; $i<=$_REQUEST['bataskolom']; $i++)
{
	$data1=$data->val($i, 1);		//Type Medical
	$data2=$data->val($i, 2);		//Age From
	$data3=$data->val($i, 3);		//Age To
	$data4=$data->val($i, 4);		//UP From
	$data5=$data->val($i, 5);		//UP To

	//VALIDASI KOLOM RATE
	if ($data1=="" OR $data1!="SPD" AND $data1!="FCL" AND $data1!="NM" AND $data1!="SKKT" AND $data1!="SPK" AND $data1!="A" AND $data1!="B" AND $data1!="C" AND $data1!="D" AND $data1!="E" AND $data1!="F" AND $data1!="Pending"){ $error1 ='<font color="red">error</font>'; $dataexcel1=$error1;}else{ $dataexcel1=$data1;}
	if ($data2==""){ $error2 ='<font color="red">error</font>'; $dataexcel2=$error2;}else{ $dataexcel2=$data2;}
	if ($data3==""){ $error3 ='<font color="red">error</font>'; $dataexcel3=$error3;}else{ $dataexcel3=$data3;}
	if ($data4==""){ $error4 ='<font color="red">error</font>'; $dataexcel4=$error4;}else{ $dataexcel4=$data4;}
	if ($data5==""){ $error5 ='<font color="red">error</font>'; $dataexcel5=$error5;}else{ $dataexcel5=$data5;}
	//VALIDASI KOLOM RATE


	if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
	echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
			<td align="center">'.++$no.'</td>
			<td align="center">'.$dataexcel1.'</td>
			<td align="center">'.$dataexcel2.'</td>
			<td align="center">'.$dataexcel3.'</td>
			<td align="center">'.$dataexcel4.'</td>
			<td align="center">'.$dataexcel5.'</td>
		  </tr>';
	$met_medical = $database->doQuery('INSERT IGNORE fu_ajk_medical_tempf SET id_cost="'.$_REQUEST['id_cost'].'",
																			  id_polis="'.$_REQUEST['id_polis'].'",
																			  type_medical="'.$data1.'",
																			  age_from="'.$data2.'",
																			  age_to="'.$data3.'",
																			  si_from="'.$data4.'",
																			  si_to="'.$data5.'",
																			  filename="'.$_FILES['userfile']['name'].'",
																			  input_by="'.$q['nm_lengkap'].'",
																			  input_date="'.$futgl.'" ');

}
if ($error1 OR $error2 OR $error3 OR $error4 OR $error5) {
//echo '<tr><td colspan="6" align="center"><a href="ajk_setrate.php?er=batal_medic&id='.$_REQUEST['id_cost'].'&fileclient='.$_FILES['userfile']['name'].'"><img src="image/deleted.png" width="32"><br />[Batal]</a></td></tr>';
echo '<tr><td colspan="6" align="center"><a href="ajk_setrate.php?er=setmedical"><img src="image/deleted.png" width="32"><br />[Batal]</a></td></tr>';
	}else{
//echo '<tr><td colspan="3" align="left"><a href="ajk_setrate.php?er=batal_medic&id='.$_REQUEST['id_cost'].'&fileclient='.$_FILES['userfile']['name'].'"><img src="image/deleted.png" width="32"><br />[Batal]</a></td>
echo '<tr><td colspan="3" align="left"><a href="ajk_setrate.php?er=setmedical"><img src="image/deleted.png" width="32"><br />[Batal]</a></td>
		  <td colspan="3" align="right"><a href="ajk_setrate.php?er=appr_medical&idc='.$_REQUEST['id_cost'].'&idp='.$_REQUEST['id_polis'].'&idf='.$_FILES['userfile']['name'].'"><img src="image/save.png" width="32"><br />[Aprrove]</a></td>
	  </tr>';
	}
	}
}
	;
	break;

case "setrefund":
		;
		break;

case "setklaim":
		;
		break;

case "upload_rmf":
echo '<table border="0" cellpadding="1" cellspacing="0" width="100%">
     <tr><th width="100%" align="left">Upload Rate RMF</font></th><th><a href="ajk_reg_cost.php?r=view&id='.$_REQUEST['id'].'"><img src="../image/Backward-64.png" width="25"></a></th></tr>
     </table>';
	$metcost = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$_REQUEST['id'].'"'));
	$metpolis = mysql_fetch_array($database->doQuery('SELECT id, id_cost, nmproduk FROM fu_ajk_polis WHERE id="'.$_REQUEST['idp'].'"'));
echo '<form name="f1" method="post" enctype="multipart/form-data" action="">
		  <input type="hidden" name="id_cost" value="'.$metcost['id'].'">
		  <table border="0" cellpadding="1" cellspacing="0" width="100%">
	      <tr><td width="40%" align="right">Nama Perusahaan</td><td> : '.$metcost['name'].'</td></tr>
		  <tr><td align="right">Nama Produk</td>
		<td id="polis_rate">: '.$metpolis['nmproduk'].'</td></tr>
		  <tr><td align="right">Silakan Pilih File Excel </td><td>: '.$bataskolom.' <input name="userfile" type="file" size="50" onchange="checkfile(this);" ></td></tr>
		  <tr><td align="right">Batas Akhir Baris </td><td>: <input type="text" name="bataskolom" value="'.$bataskolom.'" size="3" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"></td></tr>
		  <tr><td align="center"colspan="2"><input type="hidden" name="re" value="upload_rateRMF"><input type="submit" name="upload_rateRMF" value="Import"></td></tr>
		  </table>
		  </form>';
if ($_REQUEST['re']=="upload_rateRMF") {
	$_REQUEST['bataskolom'] = $_POST['bataskolom'];	if (!$_REQUEST['bataskolom'])  $error .='<blink><font color=red>Silahkan tentukan batas kolom pada data yang di Upload. !</font></blink><br>';
if (!$_FILES['userfile']['tmp_name'])  $error .='<blink><font color=red>Silahkan upload file excel RMF anda</font></blink>.';
	$allowedExtensions = array("xls","xlsx","csv");
	foreach ($_FILES as $file) {
		if ($file['tmp_name'] > '') {
			if (!in_array(end(explode(".",	strtolower($file['name']))),
			$allowedExtensions)) {	die('<center><font color=red>'.$file['name'].' <br /><blink><font color=red>File extension tidak diperbolehkan selain excel!</blink></font></center>');	}
		}
	}
	if ($error)
	{	echo '<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border: solid 1px red">
					  <tr><td><table width="100%" class="bgcolor1">
					  <tr><td align="left"><img src="image/warning.gif" border="0"></td>
						  <td align="center"><font class="option"><blink>'.$error.'</blink></td>
						  <td align="right"><img src="image/warning.gif" border="0"></td>
					  </tr>
					  </table></td></tr>
					  </table><meta http-equiv="refresh" content="4; url=ajk_reg_cost.php?r=view&id='.$_REQUEST['id'].'&r=upload_rmf&idp='.$_REQUEST['idp'].'">';
	}
	else
	{
		echo '<hr>';
		$mametset = mysql_fetch_array($database->doQuery('SELECT fu_ajk_costumer.id AS idCostumer,
															 fu_ajk_polis.id AS idPolis,
															 fu_ajk_costumer.`name` AS namaC,
															 fu_ajk_polis.nopol AS nomorP,
															 fu_ajk_polis.nmproduk AS namaproduk,
															 fu_ajk_polis.benefit AS benefitnya,
															 fu_ajk_polis.singlerate AS singleratenya
								FROM fu_ajk_polis
								INNER JOIN fu_ajk_costumer ON fu_ajk_costumer.id = fu_ajk_polis.id_cost
								WHERE fu_ajk_costumer.id="'.$_REQUEST['id'].'" AND fu_ajk_polis.id="'.$_REQUEST['idp'].'"'));
		echo '<form name="f1" method="post" enctype="multipart/form-data" action="">
			  <table width="100%" border="0" cellspacing="3" cellpadding="1">
			  <tr><td width="10%">Nama Perusahaan</td><td>: <b>'.$mametset['namaC'].'</b></td></tr>
			  <tr><td>Nama Produk</td><td>: <b>'.$mametset['namaproduk'].'</b></td></tr>
			  <tr><td>Type Rate</td><td>: <b>Risk Management Fund (RMF)</b></td></tr>
			  <tr><td>Nama File</td><td>: <b>'.$_FILES['userfile']['name'].'</b></td></tr>
			  <tr><td>Batas Kolom</td><td>: <b>'.$_REQUEST['bataskolom'].'</b></td></tr>
			  </table>';
		echo '<table width="30%" border="0" cellspacing="1" cellpadding="1" align="center">';
		$data = new Spreadsheet_Excel_Reader($_FILES['userfile']['tmp_name']);
		$baris = $data->rowcount($sheet_index=0);											//MEMBACA JUMLAH BARIS DATA EXCEL
		echo '<tr><th width="1%">No</th><th>Tenor(bln)</th><th>Rate (RMF)</th></tr>';
for ($i=2; $i<=$_REQUEST['bataskolom']; $i++)
{
	$data1=$data->val($i, 1);		//TENOR BLN/THN
	$data2=$data->val($i, 2);		//RATE

	//VALIDASI KOLOM RATE
	if ($data1==""){ $error1 ='<font color="red">error</font>'; $dataexcel1=$error1;}else{ $dataexcel1=$data1;}
	if ($data2==""){ $error2 ='<font color="red">error</font>'; $dataexcel2=$error2;}else{ $dataexcel2=$data2;}
	//VALIDASI KOLOM RATE

if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		<td align="center">'.++$no.'</td>
		<td align="center">'.$dataexcel1.'</td>
		<td align="center">'.$dataexcel2.'</td>
	  </tr>';
$met_rate = $database->doQuery('INSERT IGNORE fu_ajk_ratepremi_rmf_temp SET id_cost="'.$_REQUEST['id'].'",
																			id_polis="'.$_REQUEST['idp'].'",
																			tenor="'.$data1.'",
																			rate="'.$data2.'",
																			filename="'.$_FILES['userfile']['name'].'",
																			input_by="'.$q['nm_lengkap'].'",
																			input_date="'.$futgl.'" ');
}
if ($error1 OR $error2 OR $error3) {
	echo '<tr><td colspan="6" align="center"><a href="ajk_setrate.php?er=batal_ratermf&id='.$_REQUEST['id'].'&idp='.$_REQUEST['idp'].'&fileclient='.$_FILES['userfile']['name'].'"><img src="image/deleted.png" width="32"><br />[Batal]</a></td></tr>';
}else{
echo '<tr><td colspan="6" align="center"><a href="ajk_setrate.php?er=batal_ratermf&id='.$_REQUEST['id'].'&idp='.$_REQUEST['idp'].'&fileclient='.$_FILES['userfile']['name'].'"><img src="image/deleted.png" width="32"></a> &nbsp;
										   <a href="ajk_setrate.php?er=approve_ratermf&id='.$_REQUEST['id'].'&idp='.$_REQUEST['idp'].'&idf='.$_FILES['userfile']['name'].'"><img src="image/save.png" width="32"></a></td>
		</tr>
		<tr><td colspan="6" align="center">Batal &nbsp Approve</td></tr>';
}
echo '</table>
</form>';
	}
}
	;
	break;

case "batal_ratermf":
	$el = $database->doQuery('DELETE FROM fu_ajk_ratepremi_rmf_temp WHERE id_cost="'.$_REQUEST['id'].'" AND filename="'.$_REQUEST['fileclient'].'"');
	header('location:ajk_setrate.php?er=upload_rmf&id='.$_REQUEST['id'].'&idp='.$_REQUEST['idp'].'');
	;
	break;

case "approve_ratermf":
echo '<table border="0" cellpadding="1" cellspacing="0" width="100%">
     <tr><th width="100%" align="left">Modul Import Rate Premi Risk Management Fund (RMF)</font></th><th><a href="ajk_reg_cost.php"><img src="image/back.png" width="20"></a></th></tr>
     </table>';
$met_rate = $database->doQuery('INSERT INTO fu_ajk_ratepremi_rmf(id_cost, id_polis, tenor, rate, filename, input_by, input_date)
								SELECT id_cost, id_polis, tenor, rate, filename, input_by, input_date
								FROM fu_ajk_ratepremi_rmf_temp WHERE id_cost="'.$_REQUEST['id'].'" AND id_polis="'.$_REQUEST['idp'].'" AND filename="'.$_REQUEST['idf'].'"');
	$del_rate = $database->doQuery('DELETE  FROM fu_ajk_ratepremi_rmf_temp WHERE id_cost="'.$_REQUEST['id'].'" AND id_polis="'.$_REQUEST['idp'].'" AND filename="'.$_REQUEST['idf'].'"');
echo '<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border: solid 1px blue">
		  <tr><td><table width="100%" class="bgcolor1">
		  <tr><td align="left"><img src="image/warning.gif" border="0"></td>
			  <td align="center"><font class="option"><blink>Data Rate Risk Management Fund (RMF) Telah Berasil Di Buat</blink></td>
			  <td align="right"><img src="image/warning.gif" border="0"></td>
		  </tr>
		  </table></td></tr>
		  </table>
		  <meta http-equiv="refresh" content="3; url=ajk_reg_cost.php?r=view&id='.$_REQUEST['id'].'">';
	;
	break;

case "preview_rmf":
echo '<table border="0" cellpadding="1" cellspacing="0" width="100%">
     <tr><th width="100%" align="left">Preview Rate RMF</font></th><th><a href="ajk_reg_cost.php?r=view&id='.$_REQUEST['id'].'"><img src="../image/Backward-64.png" width="25"></a></th></tr>
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
		loadingImage:\'loader1.gif\',
		loadingText:\'Loading...\',
		debug:0
	} )
});
</script>';


?>
