<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :penting_kaga@yahoo.com
// ----------------------------------------------------------------------------------
include_once('ui.php');
include_once ("includes/functions.php");
connect();
echo '<br /><br /><br /><table border="0" cellpadding="3" cellspacing="0" width="100%">
	<tr><td align="center" colspan="2"><font color="#ed2124" size="7"><img src="image/logo_adonai_1.gif" width="50"> A D O N A I </font> <font size="7">| Pialang Asuransi</font></td></tr>
	<tr><td align="center" colspan="2"><font color="#ffa800" size="5">Aplikasi Asuransi Jiwa Kredit dan Pensiunan</font><br /><br /><td></tr>';
if (session_is_registered('nm_user')) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));	}
if ($_REQUEST['sett_client']=="Ok") {
$met = $database->doQuery('UPDATE pengguna SET id_cost="'.$_REQUEST['cat'].'",id_polis="'.$_REQUEST['subcat'].'", level="'.$_REQUEST['rlevel'].'", wilayah="Pusat" WHERE id="'.$q['id'].'"');
header('Location: index.php');
}
if ($q['id_cost']=="") {
	$cat=$_GET['cat']; // Use this line or below line if register_global is off
if(strlen($cat) > 0 and !is_numeric($cat)){ // to check if $cat is numeric data or not.
	echo "Data Error";
	exit;
}
echo '<form method="post" action="">
		<tr><td align="right" width="50%">Pilih Client yang akan di gunakan</td>
		<td>:<select id="cat" name="cat" onchange="reload(this.form)">
			<option value="">-- Select Client --</option>';
$rows = mysql_query('select * from fu_ajk_costumer ORDER BY name ASC');
while($row = mysql_fetch_array($rows)) {
if($row['id']==$cat){echo '<option selected value="'.$row['id'].'">'.$row['name'].'</option><BR>';}
else{echo  '<option value="'.$row['id'].'">'.$row['name'].'</option>';}
}
echo '</select></td></tr>
<tr><td align="right">Pilih Produk</td>
		<td>: ';
if(isset($cat) and strlen($cat) > 0){	$quer=$database->doQuery('SELECT * FROM fu_ajk_polis where id_cost="'.$cat.'" ORDER BY id ASC');	}
echo '<select name="subcat"><option value="">---Pilih---</option>';
while($noticia = mysql_fetch_array($quer)) {
if ($noticia['nmproduk']=="") {	$metproduknya = $noticia['nopol'];	}else{	$metproduknya = $noticia['nmproduk'];	}
	echo  '<option value='.$noticia['id'].'>'.$metproduknya.'</option>';
}
echo '</select></td></tr>
		<tr><td align="right">Level Akses</td><td>: <select size="1" name="rlevel">
		<option value="">Select Level</option>';
	$metlevel = $database->doQuery('SELECT * FROM fu_ajk_level ORDER BY id ASC');
	while ($clevel = mysql_fetch_array($metlevel)) {	echo '<option value="'.$clevel['id'].'"'._selected($clevel['level'], $clevel['id']).'>'.$clevel['level'].'</option>';	}
	echo '</select>
		</td></tr>
		<tr><td align="center" colspan="2"><input type="submit" name="sett_client" value="Ok" class="button"></td></tr>
		</form>';
echo '<SCRIPT language=JavaScript>
function reload(form)
{	var val=form.cat.options[form.cat.options.selectedIndex].value;
	self.location=\'index.php?cat=\' + val;
}
</script>';
}else{

}

echo '</table>';

echo '</br></br>';
?>
