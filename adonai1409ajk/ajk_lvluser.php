<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("../includes/functions.php");
connect();
if (session_is_registered('nm_user')) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));}
if ($q['status'] !="") {	$metnewuser = '';	}else{	$metnewuser ='<th><a href="ajk_lvluser.php?el=ntambah"><img border="0" src="../image/new.png" width="25"></a></th>';	}
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%"><tr><th width="95%" align="left">Level User</font></th>'.$metnewuser.'</tr></table>';

switch ($_REQUEST['el']) {
	case "dlevel":
$met = $database->doQuery('DELETE FROM fu_ajk_level WHERE id="'.$_REQUEST['idl'].'"');
header("location:ajk_lvluser.php");
		;
		break;

	case "elevel":
$met = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_level WHERE id="'.$_REQUEST['idl'].'"'));
if ($_REQUEST['ope']=="Simpan") {
	if (!$_REQUEST['fname'])  $error1 .='<blink><font color=red>Level user tidak bleh kosong</font></blink><br>';
	if ($error1)
	{		}
	else
	{
	$s=$database->doQuery('UPDATE fu_ajk_level SET level="'.strtoupper($_REQUEST['fname']).'", update_by="'.$_SESSION['nm_user'].'", update_date="'.$futgl.'" WHERE id="'.$_REQUEST['idl'].'"');
	header("location:ajk_lvluser.php");
	}
}
echo '<table border="0" width="50%" align="center">
	  <tr><td>
	  	<form method="POST" action="" class="input-list style-1 smart-green">
		<h1>Edit Level User</h1>
		<label><span>Level User <font color="red">*</font> '.$error1.'</span><input type="text" name="fname" value="'.$met['level'].'" size="30" placeholder="Level User"></label>
		<label><span>&nbsp;</span><input type="submit" name="ope" value="Simpan" class="button" /></label>
		</form>
	  </td></tr>
	  </table>';
		;
		break;

	case "ntambah":
if ($_REQUEST['ope']=="Simpan") {
	if (!$_REQUEST['fname'])  $error1 .='<blink><font color=red>Level user tidak bleh kosong</font></blink><br>';
	if ($error1)
	{		}
	else
	{
	$s=$database->doQuery('INSERT INTO fu_ajk_level SET level="'.strtoupper($_REQUEST['fname']).'", input_by="'.$_SESSION['nm_user'].'", input_date="'.$futgl.'"');
	header("location:ajk_lvluser.php");
	}
}
echo '<table border="0" width="50%" align="center">
	  <tr><td>
	  	<form method="POST" action="" class="input-list style-1 smart-green">
		<h1>Level User</h1>
		<label><span>Level User <font color="red">*</font> '.$error1.'</span><input type="text" name="fname" value="'.$_REQUEST['fname'].'" size="30" placeholder="Level User"></label>
		<label><span>&nbsp;</span><input type="submit" name="ope" value="Simpan" class="button" /></label>
		</form>
	  </td></tr>
	  </table>';
		;
		break;

	default:
$t=$database->doQuery('SELECT * FROM fu_ajk_level ORDER BY id ASC');
echo '<table border="0" cellpadding="3" cellspacing="1" width="100%" bgcolor="#bde0e6">
     <tr align="center">
     <th width="3%">No</th>
     <th>Level</th>
     <th width="5%">Pilih</th>
	 </tr>';
while($tt=mysql_fetch_array($t)){
if (($no % 2) == 1)	$objlass = 'tbl-odd'; else				$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		<td align="center">'.++$no.'</td>
		<td>'.$tt['level'].'</td>
		<td align="center"><a href="ajk_lvluser.php?el=elevel&idl='.$tt['id'].'"><img src="../image/edit3.png"></a>
		<a href="ajk_lvluser.php?el=dlevel&idl='.$tt['id'].'" onClick="if(confirm(\'Anda yakin akan menghapus data level ini?\')){return true;}{return false;}"><img src="../image/delete1.png"></a>
		</td>
	</tr>';
}
echo '</table>';
} // switch


?>