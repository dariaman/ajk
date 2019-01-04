<?php
include_once ("../../includes/fu6106.php");
echo "<script language=\"JavaScript\" src=\"js/form_validation.js\"></script>";

echo "<select name='area' onChange='DinamisArea(this);' class='cmb'>";
echo "<option value=\"\">- Pilih Area -</option>";
$query = mysql_query('SELECT * FROM fu_ajk_area WHERE id_reg="'.$_GET['kode'].'"');
while ($area=mysql_fetch_array($query))
	{
		echo "<option value=".$area['id'].">".$area['name']."</option>";
	}
echo "</select>";
?>
