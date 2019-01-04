<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// @copyright 2014
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("../includes/functions.php");
connect();
// if (session_is_registered('nm_user')) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));}
switch ($_REQUEST['er']) {
case "upload_file":
echo '<table border="0" cellpadding="1" cellspacing="0" width="100%">
     <tr><th width="100%" align="left">Modul Pembayaran Asuransi</font></th><th><a href="ajk_reg_cost.php"><img src="image/back.png" width="20"></a></th></tr>
     </table>';
echo '<form name="f1" method="post" enctype="multipart/form-data" action="">
<input type="hidden" name="re" value="upl_file">
	  <table border="0" cellpadding="1" cellspacing="0" width="100%">
      
<tr><td width="40%" align="right"></td><td><br></td></tr>
	  <tr><td width="40%" align="right">File Upload </td><td>: <input name="userfile" type="file" size="50" text="Browse" onchange="checkfile(this);" ></td></tr>
<tr><td width="40%" align="right"></td><td><br></td></tr>

<tr><td width="40%" align="right"></td><td><input type="submit" name="upload_rate" value="submit"></td></tr>

	  </table>
	  </form>';


if ($_REQUEST['re']=="upl_file") {
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
