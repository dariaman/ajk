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
$futgl = date("Y-m-d g:i:a");
switch ($_REQUEST['re']) {
	case "upl_rate_mdc_ins":
$met_medic = $database->doQuery('INSERT INTO fu_ajk_medical(id_cost, id_polis, type_medical, age_from, age_to, si_from, si_to, filename, input_by, input_date)
								SELECT id_cost, id_polis, type_medical, age_from, age_to, si_from, si_to, filename, input_by, input_date
								FROM fu_ajk_medical_tempf WHERE id_cost="'.$_REQUEST['idc'].'" AND id_polis="'.$_REQUEST['idp'].'" AND filename="'.$_REQUEST['idf'].'"');
$del_medic = $database->doQuery('DELETE  FROM fu_ajk_medical_tempf WHERE id_cost="'.$_REQUEST['idc'].'" AND id_polis="'.$_REQUEST['idp'].'" AND filename="'.$_REQUEST['idf'].'"');
		echo '<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border: solid 1px blue">
				  <tr><td><table width="100%" class="bgcolor1">
				  <tr><td align="left"><img src="image/warning.gif" border="0"></td>
					  <td align="center"><font class="option"><blink>Data Medical Telah Berasil Di Buat</blink></td>
					  <td align="right"><img src="image/warning.gif" border="0"></td>
				  </tr>
				  </table></td></tr>
				  </table><meta http-equiv="refresh" content="3; url=ajk_reg_cost.php">';
		;
		break;


	case "upl_rate_mdc_btl":
		$els = $database->doQuery('DELETE FROM fu_ajk_medical_tempf WHERE id_cost="'.$_REQUEST['id'].'" AND filename="'.$_REQUEST['fileclient'].'"');
		header('location:ajk_reg_cost_rate.php?re=rate_medical&id='.$_REQUEST['id'].'');
		;
		break;

	case "upl_ratemedik":
echo '<table border="0" cellpadding="1" cellspacing="0" width="100%">
     <tr><th width="100%" align="left">Modul Import Data Medical</font></th></tr>
     </table>';
$_REQUEST['polis_rate'] = $_POST['polis_rate'];	if (!$_REQUEST['polis_rate'])  $error .='<blink><font color=red>Silahkan pilih Nomor Polis. !</font></blink><br>';
$_REQUEST['bataskolom'] = $_POST['bataskolom'];	if (!$_REQUEST['bataskolom'])  $error .='<blink><font color=red>Silahkan tentukan batas kolom pada data yang di Upload. !</font></blink><br>';
if (!$_FILES['userfile']['tmp_name'])  $error .='<blink><font color=red>Silahkan upload file excel anda</font></blink>.';
$allowedExtensions = array("xls","xlsx","csv");
foreach ($_FILES as $file) {
	if ($file['tmp_name'] > '') {
		if (!in_array(end(explode(".",	strtolower($file['name']))),
		$allowedExtensions)) {	die('<center><font color=red>'.$file['name'].' <br /><blink><font color=red>File extension tidak diperbolehkan selain excel!</blink></font><meta http-equiv="refresh" content="3; url=ajk_reg_cost_rate.php?re=rate_medical&id='.$_REQUEST['id_cost'].'"></center>');	}
		}
	}
	if ($error)
	{	echo '<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border: solid 1px red">
			  <tr><td><table width="100%" class="bgcolor1">
			  <tr><td align="left"><img src="image/warning.gif" border="0"></td>
				  <td align="center"><font class="option"><blink>'.$error.'</blink></td>
				  <td align="right"><img src="image/warning.gif" border="0"></td>
			  </tr>
			  </table><meta http-equiv="refresh" content="3; url=ajk_reg_cost_rate.php?re=rate_medical&id='.$_REQUEST['id_cost'].'">';
	}
	else
	{
			$mametset = mysql_fetch_array($database->doQuery('SELECT fu_ajk_costumer.id AS idCostumer, fu_ajk_polis.id AS idPolis, fu_ajk_costumer.`name` AS namaC, fu_ajk_polis.nopol AS nomorP
								FROM fu_ajk_polis
								INNER JOIN fu_ajk_costumer ON fu_ajk_costumer.id = fu_ajk_polis.id_cost
								WHERE fu_ajk_costumer.id="'.$_REQUEST['id_cost'].'" AND fu_ajk_polis.id="'.$_REQUEST['polis_rate'].'"'));
echo '<form name="f1" method="post" enctype="multipart/form-data" action="">
	  <table width="100%" border="0" cellspacing="3" cellpadding="1">
	  <tr><td width="10%">Nama Perusahaan</td><td>: <b>'.$mametset['namaC'].'</b></td></tr>
	  <tr><td>Nomor Polis</td><td>: <b>'.$mametset['nomorP'].'</b></td></tr>
	  <tr><td>Nama File</td><td>: <b>'.$_FILES['userfile']['name'].'</b></td></tr>
	  <tr><td>Batas Kolom</td><td>: <b>'.$_REQUEST['bataskolom'].'</b></td></tr>
	  </table>';
echo '<table width="30%" border="0" cellspacing="1" cellpadding="1" align="center">';
		$data = new Spreadsheet_Excel_Reader($_FILES['userfile']['tmp_name']);
		$baris = $data->rowcount($sheet_index=0);											//MEMBACA JUMLAH BARIS DATA EXCEL
		//UPLOAD DATA MEDICAL
echo '<tr><th width="1%">No</th>
		  <th>Type</th>
		  <th>Usia From</th>
		  <th>Usia To</th>
		  <th>U P From</th>
		  <th>U P To</th>
	  </tr>';
	for ($i=2; $i<=$_REQUEST['bataskolom']; $i++)
	{
		$data1=$data->val($i, 1);		//TYPE MEDIC
		$data2=$data->val($i, 2);		//AGE FROM
		$data3=$data->val($i, 3);		//AGE TO
		$data4=$data->val($i, 4);		//UP FROM
		$data5=$data->val($i, 5);		//UP TO

		//VALIDASI KOLOM RATE
		if ($data1==""){ $error1 ='<font color="red">error</font>'; $dataexcel1=$error1;}else{ $dataexcel1=$data1;}
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
 $met_rate = $database->doQuery('INSERT IGNORE fu_ajk_medical_tempf SET id_cost="'.$_REQUEST['id_cost'].'",
																		id_polis="'.$_REQUEST['polis_rate'].'",
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
echo '<tr><td colspan="6" align="center"><a href="ajk_reg_cost_rate.php?re=upl_rate_mdc_btl&id='.$_REQUEST['id_cost'].'&fileclient='.$_FILES['userfile']['name'].'"><img src="image/deleted.png" width="32"><br />[Batal]</a></td></tr>';
}else{
echo '<tr><td colspan="3" align="left"><a href="ajk_reg_cost_rate.php?re=upl_rate_mdc_btl&id='.$_REQUEST['id_cost'].'&fileclient='.$_FILES['userfile']['name'].'"><img src="image/deleted.png" width="32"><br />[Batal]</a></td>
		  <td colspan="3" align="right"><a href="ajk_reg_cost_rate.php?re=upl_rate_mdc_ins&idc='.$_REQUEST['id_cost'].'&idp='.$_REQUEST['polis_rate'].'&idf='.$_FILES['userfile']['name'].'"><img src="image/save.png" width="32"><br />[Aprrove]</a></td>
	  </tr>';
	}
echo '</table>
	  </form>';
}
		;
		break;

	case "rate_medical":
$metcost = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$_REQUEST['id'].'"'));
echo '<table border="0" cellpadding="1" cellspacing="0" width="100%">
     <tr><th width="100%" align="left">Modul Import Rate Medical</font></th><th><a href="ajk_reg_cost.php"><img src="image/back.png" width="20"></a></th></tr>
	 </table>';
$pol=$database->doQuery('SELECT * FROM fu_ajk_polis WHERE id_cost="'.$metcost['id'].'" ORDER BY nopol ASC');
echo '<form name="f1" method="post" enctype="multipart/form-data" action="">
	  <input type="hidden" name="id_cost" value="'.$metcost['id'].'">
	  <table border="0" cellpadding="1" cellspacing="0" width="30%" align="center">
		<tr><td width="40%">Nama Perusahaan</td><td> : <b>'.$metcost['name'].'</b></td></tr>
		<tr><td>Pilih Polis</td>
			<td>: <select name="polis_rate">
			<option value="">---Pilih Polis---</option>';
		while($polrate = mysql_fetch_array($pol)) {
			echo  '<option value="'.$polrate['id'].'">'.$polrate['nopol'].'</option>';
		}
echo '</select></td></tr>
		<tr><td>Silakan Pilih File Excel </td><td>: '.$bataskolom.' <input name="userfile" type="file" size="50" onchange="checkfile(this);" ></td></tr>
		<tr><td>Batas Akhir Baris </td><td>: <input type="text" name="bataskolom" value="'.$bataskolom.'" size="1"></td></tr>
		<tr><td align="center"colspan="2"><input type="hidden" name="re" value="upl_ratemedik"><input type="submit" name="upload_rate" value="Import"></td></tr>
	  </table>
	  </form>';

$_ratecostumer = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_medical WHERE id_cost = "'.$_REQUEST['id'].'"'));
if ($_ratecostumer['id_cost'] == $metcost['id']) {
$_rate = $database->doQuery('SELECT * FROM fu_ajk_medical WHERE id_cost="'.$_REQUEST['id'].'"');
echo '<table width="30%" align="center" cellpadding="0" cellspacing="1" bgcolor="#bde0e6">
	  <tr><th width="1%">No</th>
		  <th>Polis</th>
		  <th>Usia</th>
		  <th>Uang Pertanggungan (UP)</th>
	  </tr>';
while ($_rate_ = mysql_fetch_array($_rate)) {
$ratepolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$_rate_['id_polis'].'"'));
if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		<td align="center">'.++$no.'</td>
		<td align="center">'.$ratepolis['nopol'].'</td>
		<td align="center">'.duit($_rate_['age_from']).' - '.duit($_rate_['age_to']).' &nbsp;</td>
		<td align="right">'.duit($_rate_['si_from']).' - '.duit($_rate_['si_to']).' &nbsp;</td>
	  </tr>';
	}
}else{	echo '<center><div class="kolomerror">Rate medical tidak ada.!</div></center>';	}

	;
	break;


	case "upl_rate_ins":
if ($_REQUEST['idt']=="Tetap") {
$met_rate = $database->doQuery('INSERT INTO fu_ajk_ratepremi(id_cost, id_polis, type, usia, tenor, rate_x, tenorpertiga, rate_y, filename, input_by, input_date)
								SELECT id_cost, id_polis, type, usia, tenor, rate_x, tenorpertiga, rate_y, filename, input_by, input_date
								FROM fu_ajk_ratepremi_temp WHERE id_cost="'.$_REQUEST['idc'].'" AND id_polis="'.$_REQUEST['idp'].'" AND filename="'.$_REQUEST['idf'].'"');
$del_rate = $database->doQuery('DELETE  FROM fu_ajk_ratepremi_temp WHERE id_cost="'.$_REQUEST['idc'].'" AND id_polis="'.$_REQUEST['idp'].'" AND filename="'.$_REQUEST['idf'].'"');
echo '<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border: solid 1px blue">
		  <tr><td><table width="100%" class="bgcolor1">
		  <tr><td align="left"><img src="image/warning.gif" border="0"></td>
			  <td align="center"><font class="option"><blink>Data Rate Telah Berasil Di Buat</blink></td>
			  <td align="right"><img src="image/warning.gif" border="0"></td>
		  </tr>
		  </table></td></tr>
		  </table><meta http-equiv="refresh" content="3; url=ajk_reg_cost.php">';
}else{
$met_rate = $database->doQuery('INSERT INTO fu_ajk_ratepremi_tunggal(id_cost, id_polis, usia, rate, tenorthn) SELECT id_cost, id_polis, usia, rate, tenor FROM fu_ajk_ratepremi_temp WHERE id_cost="'.$_REQUEST['idc'].'" AND id_polis="'.$_REQUEST['idp'].'" AND filename="'.$_REQUEST['idf'].'"');
$del_rate = $database->doQuery('DELETE  FROM fu_ajk_ratepremi_temp WHERE id_cost="'.$_REQUEST['idc'].'" AND id_polis="'.$_REQUEST['idp'].'" AND filename="'.$_REQUEST['idf'].'"');
echo '<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border: solid 1px blue">
			  <tr><td><table width="100%" class="bgcolor1">
			  <tr><td align="left"><img src="image/warning.gif" border="0"></td>
				  <td align="center"><font class="option"><blink>Data Rate Telah Berasil Di Buat</blink></td>
				  <td align="right"><img src="image/warning.gif" border="0"></td>
			  </tr>
			  </table></td></tr>
			  </table><meta http-equiv="refresh" content="3; url=ajk_reg_cost.php">';
}

		;
		break;

	case "upl_rate_btl":
$cha = $database->doQuery('DELETE FROM fu_ajk_ratepremi_temp WHERE id_cost="'.$_REQUEST['id'].'" AND filename="'.$_REQUEST['fileclient'].'"');
header('location:ajk_reg_cost_rate.php?id='.$_REQUEST['id'].'');
		;
		break;

	case "upl_rate":
echo '<table border="0" cellpadding="1" cellspacing="0" width="100%">
     <tr><th width="100%" align="left">Modul Import Rate Client</font></th></tr>
     </table>';
$_REQUEST['polis_rate'] = $_POST['polis_rate'];	if (!$_REQUEST['polis_rate'])  $error .='<blink><font color=red>Silahkan pilih Nomor Polis. !</font></blink><br>';
$_REQUEST['rateclient'] = $_POST['rateclient'];	if (!$_REQUEST['rateclient'])  $error .='<blink><font color=red>Silahkan pilih Type Rate. !</font></blink><br>';
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
		  </table><meta http-equiv="refresh" content="3; url=ajk_reg_cost_rate.php?id='.$_REQUEST['id_cost'].'">';
}
else
{
$mametset = mysql_fetch_array($database->doQuery('SELECT fu_ajk_costumer.id AS idCostumer, fu_ajk_polis.id AS idPolis, fu_ajk_costumer.`name` AS namaC, fu_ajk_polis.nopol AS nomorP
								FROM fu_ajk_polis
								INNER JOIN fu_ajk_costumer ON fu_ajk_costumer.id = fu_ajk_polis.id_cost
								WHERE fu_ajk_costumer.id="'.$_REQUEST['id_cost'].'" AND fu_ajk_polis.id="'.$_REQUEST['polis_rate'].'"'));
echo '<form name="f1" method="post" enctype="multipart/form-data" action="">
	  <table width="100%" border="0" cellspacing="3" cellpadding="1">
	  <tr><td width="10%">Nama Perusahaan</td><td>: <b>'.$mametset['namaC'].'</b></td></tr>
	  <tr><td>Nomor Polis</td><td>: <b>'.$mametset['nomorP'].'</b></td></tr>
	  <tr><td>Type Rate</td><td>: <b>'.$_REQUEST['rateclient'].'</b></td></tr>
	  <tr><td>Nama File</td><td>: <b>'.$_FILES['userfile']['name'].'</b></td></tr>
	  <tr><td>Batas Kolom</td><td>: <b>'.$_REQUEST['bataskolom'].'</b></td></tr>
	  </table>';
echo '<table width="30%" border="0" cellspacing="1" cellpadding="1" align="center">';
	$data = new Spreadsheet_Excel_Reader($_FILES['userfile']['tmp_name']);
	$baris = $data->rowcount($sheet_index=0);											//MEMBACA JUMLAH BARIS DATA EXCEL
//KONDISI RATE TETAP ATAU MENURUN
if ($_REQUEST['rateclient']=="Tetap") {
	echo '<tr><th width="1%">No</th>
			  <th>Usia</th>
			  <th>Tenor<br />(thn)</th>
			  <th>Rate<br />(thn)</th>
			  <th>Tenor<br />(1/3 thn)</th>
			  <th>Rate<br />(1/3 thn)</th>
		  </tr>';
	for ($i=2; $i<=$_REQUEST['bataskolom']; $i++)
	{
	$data1=$data->val($i, 1);		//USIA
	$data2=$data->val($i, 2);		//TENOR THN
	$data3=$data->val($i, 3);		//RATE THN
	$data4=$data->val($i, 4);		//TENOR 1/3 THN
	$data5=$data->val($i, 5);		//RATE 1/3 THN

	//VALIDASI KOLOM RATE
	if ($data1==""){ $error1 ='<font color="red">error</font>'; $dataexcel1=$error1;}else{ $dataexcel1=$data1;}
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
	$met_rate = $database->doQuery('INSERT IGNORE fu_ajk_ratepremi_temp SET id_cost="'.$_REQUEST['id_cost'].'",
																			id_polis="'.$_REQUEST['polis_rate'].'",
																			usia="'.$data1.'",
																			tenor="'.$data2.'",
																			rate_x="'.$data3.'",
																			tenorpertiga="'.$data4.'",
																			rate_y="'.$data5.'",
																			type="'.$_REQUEST['rateclient'].'",
																			filename="'.$_FILES['userfile']['name'].'",
																			input_by="'.$q['nm_lengkap'].'",
																			input_date="'.$futgl.'" ');
	}
}else{
	echo '<tr><th width="1%">No</th><th>Usia</th><th>Tenor</th><th>Rate</th></tr>';
	for ($i=2; $i<=$_REQUEST['bataskolom']; $i++)
	{
		$data1=$data->val($i, 1);		//USIA
		$data2=$data->val($i, 2);		//RATE
		$data3=$data->val($i, 3);		//TENOR THN

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
																			id_polis="'.$_REQUEST['polis_rate'].'",
																			type="'.$_REQUEST['rateclient'].'",
																			tenor="'.$data3.'",
																			rate_x="'.$data2.'",
																			usia="'.$data1.'",
																			filename="'.$_FILES['userfile']['name'].'",
																			input_by="'.$q['nm_lengkap'].'",
																			input_date="'.$futgl.'" ');
	}
}
//KONDISI RATE TETAP ATAU MENURUN
if ($error1 OR $error2 OR $error3 OR $error4 OR $error5) {
	echo '<tr><td colspan="6" align="center"><a href="ajk_reg_cost_rate.php?re=upl_rate_btl&id='.$_REQUEST['id_cost'].'&fileclient='.$_FILES['userfile']['name'].'"><img src="image/deleted.png" width="32"><br />[Batal]</a></td></tr>';
}else{
echo '<tr><td colspan="3" align="left"><a href="ajk_reg_cost_rate.php?re=upl_rate_btl&id='.$_REQUEST['id_cost'].'&fileclient='.$_FILES['userfile']['name'].'"><img src="image/deleted.png" width="32"><br />[Batal]</a></td>
	  	  <td colspan="3" align="right"><a href="ajk_reg_cost_rate.php?re=upl_rate_ins&idc='.$_REQUEST['id_cost'].'&idp='.$_REQUEST['polis_rate'].'&idf='.$_FILES['userfile']['name'].'&idt='.$_REQUEST['rateclient'].'"><img src="image/save.png" width="32"><br />[Aprrove]</a></td>
	  </tr>';
}
echo '</table>
	  </form>';
}
		;
		break;

	default:
$metcost = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$_REQUEST['id'].'"'));
echo '<table border="0" cellpadding="1" cellspacing="0" width="100%">
     <tr><th width="100%" align="left">Modul Import Rate Premi</font></th><th><a href="ajk_reg_cost.php"><img src="image/back.png" width="20"></a></th></tr>
     </table>';
$pol=$database->doQuery('SELECT * FROM fu_ajk_polis WHERE id_cost="'.$metcost['id'].'" ORDER BY nopol ASC');
echo '<form name="f1" method="post" enctype="multipart/form-data" action="">
	  <input type="hidden" name="id_cost" value="'.$metcost['id'].'">
	  <table border="0" cellpadding="1" cellspacing="0" width="100%">
      <tr><td width="20%" align="right">Nama Perusahaan</td><td> : <b>'.$metcost['name'].'</b></td></tr>
	  <tr><td width="15%" align="right">Pilih Polis</td>
	  	  <td width="30%">: <select name="polis_rate">
	  	<option value="">---Pilih Polis---</option>';
	while($polrate = mysql_fetch_array($pol)) {
	echo  '<option value="'.$polrate['id'].'">'.$polrate['nopol'].'</option>';
	}
echo '</select></td></tr>
      <tr><td align="right">Type Rate</td><td> : <input type="radio" name="rateclient" value="Menurun">Menurun &nbsp; <input type="radio" name="rateclient" value="Tetap">Tetap</td></tr>
	  <tr><td align="right">Silakan Pilih File Excel </td><td>: '.$bataskolom.' <input name="userfile" type="file" size="50" onchange="checkfile(this);" ></td></tr>
	  <tr><td align="right">Batas Akhir Baris </td><td>: <input type="text" name="bataskolom" value="'.$bataskolom.'" size="1"></td></tr>
	  <tr><td align="center"colspan="2"><input type="hidden" name="re" value="upl_rate"><input type="submit" name="upload_rate" value="Import"></td></tr>
	  </table>
	  </form>';

$_ratecostumer = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost = "'.$_REQUEST['id'].'"'));
if ($_ratecostumer['id_cost'] == $metcost['id']) {
	$_rate = $database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$_REQUEST['id'].'"');
	echo '<table width="30%" align="center" cellpadding="0" cellspacing="1" bgcolor="#bde0e6">
		  <tr><th width="1%">No</th>
			  <th>Polis</th>
			  <th>Usia</th>
			  <th>Tenor<br />(thn)</th>
			  <th>Rate<br />(thn)</th>
			  <th>Tenor<br />(1/3 thn)</th>
			  <th>Rate<br />(1/3 thn)</th>
		  </tr>';
	while ($_rate_ = mysql_fetch_array($_rate)) {
	$ratepolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$_rate_['id_polis'].'"'));
	if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
	echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		  <td align="center">'.++$no.'</td>
		  <td align="center">'.$ratepolis['nopol'].'</td>
		  <td align="center">'.$_rate_['usia'].'</td>
		  <td align="center">'.$_rate_['tenor'].'</td>
		  <td align="center">'.$_rate_['rate_x'].'</td>
		  <td align="center">'.$_rate_['tenorpertiga'].'</td>
		  <td align="center">'.$_rate_['rate_y'].'</td>
		  </tr>';
	}
}else{
	$_rate = $database->doQuery('SELECT * FROM fu_ajk_ratepremi_tunggal WHERE id_cost="'.$_REQUEST['id'].'"');
	echo '<table width="20%" align="center" cellpadding="0" cellspacing="1" bgcolor="#bde0e6">
		  <tr><th>No</th>
		  	  <th>Polis</th>
		  	  <th>usia</th>
		  	  <th>rate</th>
		  	  <th>tahun</th>
		  </tr>';
	while ($_rate_ = mysql_fetch_array($_rate)) {
	$ratepolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$_rate_['id_polis'].'"'));
	if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
	echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		  <td>'.++$no.'</td><td align="center">'.$ratepolis['nopol'].'</td>
		  <td align="center">'.$_rate_['usia'].'</td>
		  <td align="right">'.$_rate_['rate'].'</td>
		  <td align="center">'.$_rate_['tenorthn'].'</td>
		  </tr>';
	}
}
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