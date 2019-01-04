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
echo '<table border="0" cellpadding="1" cellspacing="0" width="100%"><tr><th width="100%" align="left">Modul Level Wilayah</font></th></tr></table>';
switch ($_REQUEST['r']) {
case "setwilayah":
$met = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$_REQUEST['idr'].'"'));
	if ($_REQUEST['er']=="tRegional") {
		if ($_REQUEST['savereg']=="Simpan") {
			if (!$_REQUEST['reg'])  $error1 .='<blink><font color=red>Silahkan input data regional</font></blink><br>';
			if (!$_REQUEST['area'])  $error2 .='<blink><font color=red>Silahkan input data area</font></blink><br>';
			if (!$_REQUEST['cabang'])  $error3 .='<blink><font color=red>Silahkan input data cabang</font></blink><br>';
			if ($error1 OR $error2 OR $error3)
			{	}
			else
			{
				$met_reg = $database->doQuery('INSERT INTO fu_ajk_regional SET id_cost="'.$_REQUEST['idr'].'", name="'.strtoupper($_REQUEST['reg']).'", input_by="'.$q['nm_lengkap'].'", input_time="'.$futgl.'"');
				$met_reg_nm = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional ORDER by id DESC'));

				$met_area = $database->doQuery('INSERT INTO fu_ajk_area SET id_cost="'.$_REQUEST['idr'].'", id_reg="'.$met_reg_nm['id'].'", name="'.strtoupper($_REQUEST['area']).'", input_by="'.$q['nm_lengkap'].'", input_time="'.$futgl.'"');
				$met_area_nm = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_area ORDER by id DESC'));

				$met_cab = $database->doQuery('INSERT INTO fu_ajk_cabang SET id_cost="'.$_REQUEST['idr'].'", id_reg="'.$met_reg_nm['id'].'", id_area="'.$met_area_nm['id'].'", name="'.strtoupper($_REQUEST['cabang']).'", input_by="'.$q['nm_lengkap'].'", input_time="'.$futgl.'"');
				echo '<center>Data wilayah telah ditambah oleh '.$q['nm_lengkap'].'.</center><meta http-equiv="refresh" content="2; url=ajk_lvlcabang.php">';
			}
		}elseif ($_REQUEST['savereg']=="Batal") {
			header('location:ajk_lvlcabang.php');
		}

echo '<table border="0" width="40%" align="center"><tr><td>
	<form method="POST" action="" class="input-list style-1 smart-green">
	<h1>Tambah Data Wilayah '.$met['name'].'</h1>
	<input type="hidden" name="idcost" value="'.$_REQUEST['idR'].'">
	<label><span>Regional <font color="red">*</font> '.$error1.'</span><input type="text" name="reg" value="'.$_REQUEST['reg'].'" size="30" placeholder="Regional"></label>
	<label><span>Area <font color="red">*</font> '.$error2.'</span><input type="text" name="area" value="'.$_REQUEST['area'].'" size="30" placeholder="Area"></label>
	<label><span>Cabang <font color="red">*</font> '.$error3.'</span><input type="text" name="cabang" value="'.$_REQUEST['cabang'].'" size="30" placeholder="Cabang"></label>
	<label><span>&nbsp;</span><input type="submit" name="savereg" value="Batal" class="button" />
		   <span>&nbsp;</span><input type="submit" name="savereg" value="Simpan" class="button" />
	</label>
  </form></td></tr></table>';
	}
	;
	break;

case "setreg":
if ($_REQUEST['savereg']=="Simpan") {
	if (!$_REQUEST['reg'])  $error1 .='<blink><font color=red>Silahkan input data regional</font></blink><br>';
	if ($_REQUEST['reg']){
		$cekDataReg = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE id_cost="'.$_REQUEST['idr'].'" AND name="'.$_REQUEST['reg'].'" AND del IS NULL'));
		if ($cekDataReg['id']) {	  $error2 .='<blink><font color=red>Data regional sudah ada</font></blink><br>';	}
	}
	if ($error1 OR $error2)
	{	}
	else
	{
	$met_reg = $database->doQuery('INSERT INTO fu_ajk_regional SET id_cost="'.$_REQUEST['idr'].'", name="'.strtoupper($_REQUEST['reg']).'", input_by="'.$q['nm_lengkap'].'", input_time="'.$futgl.'"');
	echo '<center>Data Regional telah ditambah oleh '.$q['nm_lengkap'].'.</center><meta http-equiv="refresh" content="2; url=ajk_lvlcabang.php">';
	}
	}elseif ($_REQUEST['savereg']=="Batal") {	header('location:ajk_lvlcabang.php');	}

$met = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$_REQUEST['idr'].'"'));
echo '<table border="0" width="40%" align="center"><tr><td>
	<form method="POST" action="" class="input-list style-1 smart-green">
	<h1>Tambah Data Wilayah (Regional) '.$met['name'].'</h1>
	<input type="hidden" name="idcost" value="'.$_REQUEST['idR'].'">
	<label><span>Regional <font color="red">*</font> '.$error1.' '.$error2.'</span><input type="text" name="reg" value="'.$_REQUEST['reg'].'" size="30" placeholder="Regional"></label>
	<label><span>&nbsp;</span><input type="submit" name="savereg" value="Batal" class="button" />
		   <span>&nbsp;</span><input type="submit" name="savereg" value="Simpan" class="button" />
	</label>
  </form></td></tr></table>';
	;
	break;

case "setarea":
$met = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$_REQUEST['idr'].'"'));
if ($_REQUEST['savearea']=="Simpan") {
	if (!$_REQUEST['area'])  $error1 .='<blink><font color=red>Silahkan input data area</font></blink><br>';
	if ($error1)
	{	}
	else
	{
	$met_reg = $database->doQuery('INSERT INTO fu_ajk_area SET id_cost="'.$_REQUEST['idcost'].'", id_reg="'.$_REQUEST['regnya'].'", name="'.strtoupper($_REQUEST['area']).'", input_by="'.$q['nm_lengkap'].'", input_time="'.$futgl.'"');
	echo '<center>Data Area telah ditambah oleh '.$q['nm_lengkap'].'.</center><meta http-equiv="refresh" content="2; url=ajk_lvlcabang.php">';
	}
	}elseif ($_REQUEST['savearea']=="Batal") {	header('location:ajk_lvlcabang.php');	}

echo '<table border="0" width="40%" align="center"><tr><td>
		<form method="POST" action="" class="input-list style-1 smart-green">
		<h1>Tambah Data Wilayah (Area) '.$met['name'].'</h1>
		<input type="hidden" name="idcost" value="'.$_REQUEST['idr'].'">
		<label><span>Regional <font color="red">*</font> '.$error5.'</span>
		 	<select id="regnya" name="regnya">
		  	<option value="">-----Pilih Regional-----</option>';
	$metreg = $database->doQuery('SELECT * FROM fu_ajk_regional WHERE id_cost="'.$_REQUEST['idr'].'" AND del IS NULL ORDER BY name');
	while ($metreg_ = mysql_fetch_array($metreg)) {	echo '<option value="'.$metreg_['id'].'">'.$metreg_['name'].'</option>';	}
	echo '</select></label>
		<label><span>Area <font color="red">*</font> '.$error1.'</span><input type="text" name="area" value="'.$_REQUEST['area'].'" size="30" placeholder="Area"></label>
		<label><span>&nbsp;</span><input type="submit" name="savearea" value="Batal" class="button" />
			   <span>&nbsp;</span><input type="submit" name="savearea" value="Simpan" class="button" />
		</label>
	  </form></td></tr></table>';
	;
	break;

case "setcab":
$met = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$_REQUEST['idr'].'"'));
if ($_REQUEST['savecab']=="Simpan") {
	if (!$_REQUEST['cabang'])  $error1 .='<blink><font color=red>Silahkan input data cabang</font></blink><br>';
	if ($_REQUEST['cabang']){
		$cekDataCab = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id_cost="'.$_REQUEST['idcost'].'" AND id_reg="'.$_REQUEST['id_reg'].'" AND name="'.$_REQUEST['cabang'].'" AND del IS NULL'));
		if ($cekDataCab['id']) {	  $error2 .='<blink><font color=red>Data cabang sudah ada</font></blink><br>';	}
	}
	if ($error1 OR $error2)
	{	}
	else
	{
		$met_reg = $database->doQuery('INSERT INTO fu_ajk_cabang SET id_cost="'.$_REQUEST['idcost'].'", id_reg="'.$_REQUEST['id_reg'].'", id_area="'.$_REQUEST['areanya'].'", name="'.strtoupper($_REQUEST['cabang']).'", input_by="'.$q['nm_lengkap'].'", input_time="'.$futgl.'"');
		echo '<center>Data Cabang telah ditambah oleh '.$q['nm_lengkap'].'.</center><meta http-equiv="refresh" content="2; url=ajk_lvlcabang.php">';
	}
	}elseif ($_REQUEST['savecab']=="Batal") {	header('location:ajk_lvlcabang.php');	}

echo '<table border="0" width="40%" align="center"><tr><td>
	<form method="POST" action="" class="input-list style-1 smart-green">
	<h1>Tambah Data Wilayah (Cabang) '.$met['name'].'</h1>
	<input type="hidden" name="idcost" value="'.$_REQUEST['idr'].'">
	<label><span>Regional <font color="red">*</font> '.$error5.'</span>
		 	<select id="id_reg" name="id_reg">
		  	<option value="">-----Pilih Regional-----</option>';
$metreg = $database->doQuery('SELECT * FROM fu_ajk_regional WHERE id_cost="'.$_REQUEST['idr'].'" AND del IS NULL ORDER BY name');
while ($metreg_ = mysql_fetch_array($metreg)) {	echo '<option value="'.$metreg_['id'].'">'.$metreg_['name'].'</option>';	}
echo '</select></label>
		<label><span>Area <font color="red">*</font> '.$error1.'</span>
			<select name="areanya" id="areanya">
			<option value="">-- Pilih Area --</option>
			</select>
		</label>
		<label><span>Cabang <font color="red">*</font> '.$error1.' '.$error2.'</span><input type="text" name="cabang" value="'.$_REQUEST['cabang'].'" size="30" placeholder="Cabang"></label>
		<label><span>&nbsp;</span><input type="submit" name="savecab" value="Batal" class="button" />
			   <span>&nbsp;</span><input type="submit" name="savecab" value="Simpan" class="button" />
		</label>
	  </form></td></tr></table>';
	;
	break;

case "editcab":
/*
$metCab = mysql_fetch_array($database->doQuery('SELECT fu_ajk_cabang.id_cost, fu_ajk_cabang.`name` AS cab, fu_ajk_area.`name` AS area, fu_ajk_regional.`name` AS reg, fu_ajk_cabang.centralcbg
FROM fu_ajk_cabang
INNER JOIN fu_ajk_area ON fu_ajk_cabang.id_cost = fu_ajk_area.id_cost AND fu_ajk_cabang.id_area = fu_ajk_area.id
INNER JOIN fu_ajk_regional ON fu_ajk_cabang.id_cost = fu_ajk_regional.id_cost AND fu_ajk_cabang.id_reg = fu_ajk_regional.id
WHERE fu_ajk_cabang.id_cost = "'.$_REQUEST['idr'].'" AND fu_ajk_cabang.id = "'.$_REQUEST['idcab'].'"'));
*/
$metDataCbg = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id="'.$_REQUEST['idcab'].'"'));
if ($_REQUEST['savecab']=="Simpan") {
	if (!$_REQUEST['idreg'])  $error1 .='<blink><font color=red>Silahkan input data regional</font></blink><br>';
	if (!$_REQUEST['idarea'])  $error1 .='<blink><font color=red>Silahkan input data area</font></blink><br>';
	if (!$_REQUEST['cab'])  $error1 .='<blink><font color=red>Silahkan input data cabang</font></blink><br>';
//	if (!$_REQUEST['idsentral'])  $error2 .='<blink><font color=red>Silahkan pilih sentralisasi cabang</font></blink><br>';
/*
	if (!$_REQUEST['idsentral']=="") {

	}else{
	$_EdCab = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id_cost="'.$metCab['id_cost'].'" AND name="'.$_REQUEST['cab'].'"'));
	if ($_EdCab['name']) {
		$error2 ='<blink><font color=red>Nama cabang sudah ada.</font></blink><br>';
	}
	}
*/
	if ($error1 OR $error2) {

	}else{
	$metSentral = $database->doQuery('UPDATE fu_ajk_cabang SET id_reg="'.$_REQUEST['idreg'].'", id_area="'.$_REQUEST['idarea'].'", name="'.$_REQUEST['cab'].'", centralcbg="'.$_REQUEST['idsentral'].'" WHERE id="'.$_REQUEST['idcab'].'"');
	//echo '<center>Data Cabang telah diedit oleh '.$q['nm_lengkap'].'.</center><meta http-equiv="refresh" content="2; url=ajk_lvlcabang.php?r=vwilayah&idr='.$_REQUEST['idcost'].'">';
	header("location:ajk_lvlcabang.php?r=vwilayah&idr=".$_REQUEST['idcost']."");
	}
}
echo '<table border="0" width="40%" align="center"><tr><td>
	<form method="POST" action="" class="input-list style-1 smart-green">
	<h1>Edit Data Wilayah (Cabang) '.$met['name'].'</h1>
	<input type="hidden" name="idr" value="'.$_REQUEST['idr'].'">
	<input type="hidden" name="ida" value="'.$_REQUEST['ida'].'">
	<input type="hidden" name="idcab" value="'.$_REQUEST['idcab'].'">
	<input type="hidden" name="idcost" value="'.$_REQUEST['idcost'].'">
	<label><span>Regional <font color="red">*</font> '.$error5.'</span>
		 	<select id="idreg" name="idreg">';
$metReg = $database->doQuery('SELECT * FROM fu_ajk_regional WHERE id_cost="'.$_REQUEST['idcost'].'" AND del IS NULL');
while ($metReg_ = mysql_fetch_array($metReg)) {
echo '<option value="'.$metReg_['id'].'"'._selected($metReg_['id'], $_REQUEST['idr']).'>'.$metReg_['name'].'</option>';
}

echo '</select>
	</label>
		<label><span>Area <font color="red">*</font></span>
			<select id="idarea" name="idarea">';
$metArea = $database->doQuery('SELECT * FROM fu_ajk_area WHERE id_cost="'.$_REQUEST['idcost'].'" AND del IS NULL');
while ($metArea_ = mysql_fetch_array($metArea)) {
echo '<option value="'.$metArea_['id'].'"'._selected($metArea_['id'], $_REQUEST['ida']).'>'.$metArea_['name'].'</option>';
}
echo '	</select>
		</label>
		<label><span>Cabang <font color="red">*</font> '.$error1.' '.$error2.'</span>
				<input type="text" name="cab" value="'.$metDataCbg['name'].'" size="30" placeholder="Cabang">
		</label>
		<label><span>Sentralisai</span>
<select id="idsentral" name="idsentral">
<option value="">--Pilih Sentralisasi--</option>';
$metCabang = $database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id_cost="'.$_REQUEST['idcost'].'" AND del IS NULL GROUP BY name ORDER BY name ASC');
while ($metCabang_ = mysql_fetch_array($metCabang)) {
	echo '<option value="'.$metCabang_['id'].'">'.$metCabang_['name'].'</option>';
}
echo '</select><label><span>&nbsp;</span><input type="submit" name="savecab" value="Batal" class="button" />
			   <span>&nbsp;</span><input type="submit" name="savecab" value="Simpan" class="button" />
		</label>
	  </form></td></tr></table>';
	;
	break;

case "vwilayah":
$met = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$_REQUEST['idr'].'"'));
echo '<br /><table border="0" width="100%" cellpadding="3" cellspacing="1" style="border: solid 1px #DEDEDE">
	  <tr><td width="15%">Company Name</td><td width="1%">:</td><td>'.$met['name'].'</td></tr>
	  <tr><td colspan="3">
	  	<table border="0" width="100%" cellpadding="3" cellspacing="1" style="border: solid 1px #DEDEDE">
	  	<tr><td class="title2" align="center" width="3%">No</td>
	  		<td class="title2" align="center">Regional</td>
			<td class="title2" align="center">Area</td>
			<td class="title2" align="center">Cabang</td></tr>';
$metwilayah = $database->doQuery('SELECT fu_ajk_costumer.id,
										fu_ajk_costumer.`name`,
										fu_ajk_regional.id AS idReg,
										fu_ajk_regional.`name` AS regional,
										fu_ajk_area.id AS idArea,
										fu_ajk_area.`name` AS area,
										fu_ajk_cabang.id AS idcab,
										fu_ajk_cabang.`name` AS cabang,
										fu_ajk_cabang.centralcbg
FROM fu_ajk_cabang
INNER JOIN fu_ajk_costumer ON fu_ajk_cabang.id_cost = fu_ajk_costumer.id
INNER JOIN fu_ajk_regional ON fu_ajk_cabang.id_reg = fu_ajk_regional.id
INNER JOIN fu_ajk_area ON fu_ajk_cabang.id_area = fu_ajk_area.id
WHERE fu_ajk_costumer.id='.$_REQUEST['idr'].' AND
	  fu_ajk_regional.del IS NULL AND
	  fu_ajk_area.del IS NULL AND
	  fu_ajk_cabang.del IS NULL
ORDER BY fu_ajk_regional.`name` ASC,
		 fu_ajk_area.`name` ASC,
		 fu_ajk_cabang.`name` ASC');
while ($metwilayah_ = mysql_fetch_array($metwilayah)) {
if ($metwilayah_['centralcbg']!="") {
$metCabangnya = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_cabang WHERE id="'.$metwilayah_['centralcbg'].'"'));
$metCentralCab = '<b>('.$metCabangnya['name'].')</b>';
}else{
$metCentralCab = '';
}
if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		<td align="center">'.++$no.'</td>
		<td> <a href=""></a> '.$metwilayah_['regional'].'</td>
		<td> <a href=""></a> '.$metwilayah_['area'].'</td>
		<td> <a href="ajk_lvlcabang.php?r=editcab&idcost='.$metwilayah_['id'].'&idr='.$metwilayah_['idReg'].'&ida='.$metwilayah_['idArea'].'&idcab='.$metwilayah_['idcab'].'" title="edit cabang '.$metwilayah_['cabang'].'"><img src="image/edit3.png"> </a> '.$metwilayah_['cabang'].' '.$metCentralCab.'</td>
	  </tr>';
}
echo '</table>
	  </td></tr>
	  </table>';
	;
	break;


default:
echo '<table border="0" cellpadding="3" cellspacing="1" width="100%" bgcolor="#bde0e6">
	  <tr><th width="1%">No</th>
	  	  <th>Perusahaan</th>
	  	  <th width="10%">Regional</th>
	  	  <th width="10%">Area</th>
	  	  <th width="10%">Cabang</th>
	  	  <th width="5%">Option</th>
	  </tr>';
$wilayah = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name ASC');
while ($metwilayah = mysql_fetch_array($wilayah)) {
$jReg = mysql_fetch_array($database->doQuery('SELECT id, id_cost, COUNT(name) AS jReg FROM fu_ajk_regional WHERE id_cost="'.$metwilayah['id'].'" AND del IS NULL GROUP BY id_cost'));
if ($jReg > 0) {	$tambahdatareg = '<a href="ajk_lvlcabang.php?r=setreg&idr='.$metwilayah['id'].'"><img src="image/plus.png" width="12" border="0"></a>';	}else{	$tambahdatareg = '';	}

$jAre = mysql_fetch_array($database->doQuery('SELECT id, id_cost, id_reg, COUNT(name) AS jAre FROM fu_ajk_area WHERE id_cost="'.$metwilayah['id'].'" AND del IS NULL GROUP BY id_cost'));
if ($jAre > 0) {	$tambahdataarea = '<a href="ajk_lvlcabang.php?r=setarea&idr='.$metwilayah['id'].'"><img src="image/plus.png" width="12" border="0"></a>';	}else{	$tambahdataarea = '';	}

$jCab = mysql_fetch_array($database->doQuery('SELECT id, id_cost, id_reg, id_area, COUNT(name) AS jCab FROM fu_ajk_cabang WHERE id_cost="'.$metwilayah['id'].'" AND del IS NULL GROUP BY id_cost'));
if ($jCab > 0) {	$tambahdatacbg = '<a href="ajk_lvlcabang.php?r=setcab&idr='.$metwilayah['id'].'"><img src="image/plus.png" width="12" border="0"></a>';	}else{	$tambahdatacbg = '';	}

if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		  <td align="center">'.++$no.'</td>
		  <td><a href="ajk_lvlcabang.php?r=vwilayah&idr='.$metwilayah['id'].'">'.$metwilayah['name'].'</a></td>
		  <td align="center">'.$jReg['jReg'].' '.$tambahdatareg.'</td>
		  <td align="center">'.$jAre['jAre'].' '.$tambahdataarea.'</td>
		  <td align="center">'.$jCab['jCab'].' '.$tambahdatacbg.'</td>
		  <td align="center"><a href="ajk_lvlcabang.php?r=setwilayah&er=tRegional&idr='.$metwilayah['id'].'"><img src="image/plus.png" width="23" border="0"></a></td>
	  </tr>';
}
echo '</table>';
;
} // switch
echo '<!--WILAYAH COMBOBOX-->
<script src="javascript/metcombo/prototype.js"></script>
<script src="javascript/metcombo/dynamicombo.js"></script>
<!--WILAYAH COMBOBOX-->
<script>
document.observe("dom:loaded",function(){
	new DynamiCombo( "id_reg" , {
		elements:{
			"areanya":		{url:\'javascript/metcombo/data.php?req=setwilarea\', value:\'id\', label:\'name\', init:\'<?php echo $_POST["areanya"] ?>\'},
		},
		loadingImage:\'loader1.gif\',
		loadingText:\'Loading...\',
		debug:0
	} )
});
</script>';
?>