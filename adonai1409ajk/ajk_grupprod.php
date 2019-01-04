<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("../includes/functions.php");
connect();
if (session_is_registered('nm_user')) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));}
switch ($_REQUEST['gp']) {
	case "edit":
$metgProduk = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_grupproduk WHERE id="'.$_REQUEST['id'].'"'));
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
     <tr><th width="95%" align="left">Daftar Group Produk</font></th><th><a href="ajk_grupprod.php"><img border="0" src="../image/Backward-64.png" width="25"></a></th></tr>
     </table>';
if ($_REQUEST['ope']=="Simpan") {
if (!$_REQUEST['id_cost'])  $error1 .='<blink><font color=red>Nama perusahaan tidak boleh kosong</font></blink><br>';
if (!$_REQUEST['gproduk'])  $error2 .='<blink><font color=red>Nama grup produk tidak boleh kosong</font></blink><br>';
if ($error1 OR $error2)
{		}
else
{
$s=$database->doQuery('UPDATE fu_ajk_grupproduk SET id_cost="'.$_REQUEST['id_cost'].'",
									  					 nmproduk="'.strtoupper($_REQUEST['gproduk']).'",
									  					 update_by="' . $q['id'] . '",
								   						 update_date="' . $futgl . '"
								   						 WHERE id="'.$_REQUEST['id'].'"');
	header("location:ajk_grupprod.php");
}
}
echo '<table border="0" width="75%" align="center"><tr><td>
	<form method="POST" action="" class="input-list style-1 smart-green">
	<h1>Group Produk - Edit</h1>
	<label><span>Nama Perusahaan <font color="red">*</font> '.$error1.'</span><select name="id_cost" id="id_cost">
				<option value="">Pilih Perusahaan</option>';
		$metcost = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name ASC');
		while ($ccost = mysql_fetch_array($metcost)) {
			echo '<option value="'.$ccost['id'].'"'._selected($metgProduk['id_cost'], $ccost['id']).'>'.$ccost['name'].'</option>';
		}
echo '</select>
		</label>
		<label><span>Grup Produk <font color="red">*</font> '.$error2.'</span><input type="text" name="gproduk" value="'.$metgProduk['nmproduk'].'" placeholder="Grup Produk"></label>
		  <label><span>&nbsp;</span><input type="submit" name="ope" value="Simpan" class="button" /></label>
		  </form></td></tr></table>';
		;
		break;

	case "tambah":
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
     <tr><th width="95%" align="left">Daftar Group Produk</font></th><th><a href="ajk_grupprod.php"><img border="0" src="../image/Backward-64.png" width="25"></a></th></tr>
     </table>';
if ($_REQUEST['ope']=="Simpan") {
if (!$_REQUEST['id_cost'])  $error1 .='<blink><font color=red>Nama perusahaan tidak boleh kosong</font></blink><br>';
if (!$_REQUEST['gproduk'])  $error2 .='<blink><font color=red>Nama grup produk tidak boleh kosong</font></blink><br>';
if ($error1 OR $error2)
{		}
else
{
$s=$database->doQuery('INSERT INTO fu_ajk_grupproduk SET id_cost="'.$_REQUEST['id_cost'].'",
									  					 nmproduk="'.strtoupper($_REQUEST['gproduk']).'",
									  					 input_by="' . $q['id'] . '",
								   						 input_date="' . $futgl . '"');
header("location:ajk_grupprod.php");
}
}
echo '<table border="0" width="75%" align="center"><tr><td>
		<form method="POST" action="" class="input-list style-1 smart-green">
		<h1>Group Produk</h1>
		<label><span>Nama Perusahaan <font color="red">*</font> '.$error1.'</span><select name="id_cost" id="id_cost">
				<option value="">Pilih Perusahaan</option>';
		$metcost = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name ASC');
		while ($ccost = mysql_fetch_array($metcost)) {
			echo '<option value="'.$ccost['id'].'"'._selected($_REQUEST['id_cost'], $ccost['id']).'>'.$ccost['name'].'</option>';
		}
		echo '</select>
		</label>
		<label><span>Grup Produk <font color="red">*</font> '.$error2.'</span><input type="text" name="gproduk" value="'.$_REQUEST['gproduk'].'" placeholder="Grup Produk"></label>
		  <label><span>&nbsp;</span><input type="submit" name="ope" value="Simpan" class="button" /></label>
		  </form></td></tr></table>';
		;
		break;

	default:
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
     <tr><th width="95%" align="left">Daftar Grup Produk</font></th><th><a href="ajk_grupprod.php?gp=tambah"><img border="0" src="../image/new.png" width="25"></a></th></tr>
     </table>';
echo '<table border="0" width="100%" cellpadding="1" cellspacing="1">
	<form method="post" action="">
	<tr><td width="10%">Grup Produk :</td><td><input type="text" name="grupproduk_" value="'.$_REQUEST['grupproduk_'].'"> <input type="submit" name="button" value="Cari" class="button"></td></tr>
	</form></table>';
if ($_REQUEST['grupproduk_'])		{	$satu = 'AND fu_ajk_grupproduk.nmproduk LIKE "%' . $_REQUEST['grupproduk_'] . '%"';		}
if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}
$t=$database->doQuery('SELECT fu_ajk_grupproduk.id, fu_ajk_grupproduk.nmproduk, fu_ajk_costumer.`name`
FROM fu_ajk_grupproduk
INNER JOIN fu_ajk_costumer ON fu_ajk_grupproduk.id_cost = fu_ajk_costumer.id WHERE fu_ajk_grupproduk.id !="" '.$satu.' ORDER BY nmproduk ASC LIMIT '.$m.' , 25');
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_grupproduk WHERE id !="" '.$satu.''));
$totalRows = $totalRows[0];
echo '<table border="0" cellpadding="3" cellspacing="1" width="100%" bgcolor="#bde0e6">
		     <tr align="center">
		     <th width="3%">No</th>
		     <th>Costumer</th>
		     <th width="40%">Nama Produk</th>
		     <th width="5%">Pilih</th>
			 </tr>';
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while($tt=mysql_fetch_array($t)){
if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	      <td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
		  <td>'.$tt['name'].'</td>
		  <td>'.$tt['nmproduk'].'</td>
		  <td align="center"><a href="ajk_grupprod.php?id='.$tt['id'].'&gp=edit"><img border="0" src="../image/editaja.png" width="20"></td>
	</tr>';
}// while
echo '<tr><td colspan="22">';
echo createPageNavigations($file = 'user.php?', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
echo '<b>Total Data Grup Produk : <u>' . $totalRows . '</u></b></td></tr>
	</table>';
		;
} // switch


?>