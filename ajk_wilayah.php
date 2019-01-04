<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("includes/functions.php");
connect();
if (session_is_registered('nm_user')) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));	}
$futgl = date("Y-m-d g:i:a");

switch ($_REQUEST['r']) {
	case "a":
		;
		break;
	case "rview":
$met = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE id_cost="'.$q['id_cost'].'" AND id="'.$_REQUEST['id'].'"'));
echo '<table border="0" cellpadding="3" cellspacing="1" width="100%" bgcolor="#bde0e6">
	  <tr><th colspan="5" width="95%" align="left">Modul Regional - '.$met['name'].'</th>
	  	  <th>'.back().'</th></tr></table>';
if ($_REQUEST['ope']=="editarea") {
if ($_REQUEST['op']=="Updated") {
$u=$database->doQuery('UPDATE fu_ajk_area SET name="'.$_REQUEST['areana'].'", update_by="'.$_SESSION['nm_user'].'", update_time="'.$futgl.'" WHERE id="'.$_REQUEST['id_area'].'"');
echo '<center>Data area telah di edit</center><meta http-equiv="refresh" content="2; url=ajk_wilayah.php?r=rview&id='.$_REQUEST['id'].'">';
}

$fuarea = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_area WHERE id="'.$_REQUEST['id_area'].'"'));
echo '<form method="post" action=""><center>Area : <input type="text" name="areana" value="'.$fuarea['name'].'">
	  <input type="hidden" name="id" value="'.$_REQUEST['id'].'">
	  <input type="hidden" name="id_area" value="'.$_REQUEST['id_area'].'">
	  <input type="Submit" name="op" value="Updated"> &nbsp; <a href="ajk_wilayah.php?r=rview&id='.$_REQUEST['id'].'">Cancel</a></center>
	  </form>';
}
if ($_REQUEST['ope']=="editcabang") {
if ($_REQUEST['op']=="Updated") {
	$u=$database->doQuery('UPDATE fu_ajk_cabang SET name="'.$_REQUEST['cabangna'].'", update_by="'.$_SESSION['nm_user'].'", update_time="'.$futgl.'" WHERE id="'.$_REQUEST['idcbg'].'"');
	echo '<center>Data cabang telah di edit</center><meta http-equiv="refresh" content="2; url=ajk_wilayah.php?r=rview&id='.$_REQUEST['id'].'">';
}

	$fuarea = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id="'.$_REQUEST['idcbg'].'"'));
echo '<form method="post" action=""><center>Area : <input type="text" name="cabangna" value="'.$fuarea['name'].'">
		  <input type="hidden" name="id" value="'.$_REQUEST['id'].'">
		  <input type="hidden" name="id_area" value="'.$_REQUEST['id_area'].'">
		  <input type="hidden" name="idcbg" value="'.$_REQUEST['idcbg'].'">
		  <input type="Submit" name="op" value="Updated"> &nbsp; <a href="ajk_wilayah.php?r=rview&id='.$_REQUEST['id'].'">Cancel</a></center>
		  </form>';
}
$rarea = $database->doQuery('SELECT * FROM fu_ajk_area WHERE id_reg="'.$_REQUEST['id'].'"');
echo '<table border="0" cellpadding="3" cellspacing="1" width="100%">';
while ($metarea = mysql_fetch_array($rarea)) {
echo '<tr><th width="3%">'.++$no.'</th><th align="left">'.$metarea['name'].'</th><th width="5%"><a href="ajk_wilayah.php?r=rview&ope=editarea&id='.$_REQUEST['id'].'&id_area='.$metarea['id'].'"><img src="image/edit3.png" border="0"></a></th></tr>';
$rcabang = $database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id_area="'.$metarea['id'].'"');
while ($metcabang = mysql_fetch_array($rcabang)) {
echo '<tr class="'.rowClass(++$i).'"><td width="3%">&nbsp;</td><td align="left"><li>'.$metcabang['name'].'</td><td width="5%"><a href="ajk_wilayah.php?r=rview&ope=editcabang&id='.$_REQUEST['id'].'&id_area='.$metarea['id'].'&idcbg='.$metcabang['id'].'"><img src="image/edit1.png" border="0"></a></td></tr>';
}
}
echo '</table>';
		;
		break;
	default:
echo '<table border="0" cellpadding="3" cellspacing="1" width="100%" bgcolor="#bde0e6"><tr><th colspan="5" width="100%" align="left">Modul Regional</th></tr></table>';
if ($_REQUEST['ope']=="ewilayah") {
if ($_REQUEST['op']=="Updated") {
	$u=$database->doQuery('UPDATE fu_ajk_regional SET name="'.$_REQUEST['regna'].'", update_by="'.$_SESSION['nm_user'].'", update_time="'.$futgl.'" WHERE id="'.$_REQUEST['id'].'"');
	echo '<center>Data Regional telah di edit</center><meta http-equiv="refresh" content="2; url=ajk_wilayah.php">';
}
$fureg = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['id'].'"'));
echo '<form method="post" action=""><center>Regional : <input type="text" name="regna" value="'.$fureg['name'].'">
		  <input type="hidden" name="id" value="'.$_REQUEST['id'].'">
		  <input type="Submit" name="op" value="Updated"> &nbsp; <a href="ajk_wilayah.php">Cancel</a></center>
		  </form>';
}
$reg = $database->doQuery('SELECT * FROM fu_ajk_regional WHERE id_cost="'.$q['id_cost'].'" ORDER BY name ASC');
echo '<table border="0" cellpadding="3" cellspacing="1" width="100%" bgcolor="#bde0e6">';
echo '<tr><th width="3%">No.</th><th>Regional</th><th width="20%">area</th><th width="20%">Cabang</th><th width="8%">Option</th></tr>';
while ($creg = mysql_fetch_array($reg)) {
if (($no % 2) == 1)	$objlass = 'tbl-odd'; else				$objlass = 'tbl-even';
$area = $database->doQuery('SELECT * FROM fu_ajk_area WHERE id_reg="'.$creg['id'].'"');	$jarea =mysql_num_rows($area);
$cbg = $database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id_reg="'.$creg['id'].'"');	$jcbg =mysql_num_rows($cbg);
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		<td align="center" width="3%">'.++$no.'</td>
		<td><a href="ajk_wilayah.php?r=rview&id='.$creg['id'].'">'.$creg['name'].'</a></td>
		<td align="center">'.$jarea.' data</td>
		<td align="center">'.$jcbg.' data</td>
		<td width="8%" align="center"><a href="ajk_wilayah.php?ope=ewilayah&id='.$creg['id'].'"><img src="image/edit3.png" border="0"></a></td>
	  </tr>';
}
echo '</table>';

	;
} // switch
?>