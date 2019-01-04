<?php
include_once ("../../includes/fu6106.php");
echo "<script language=\"JavaScript\" src=\"js/form_validation.js\"></script>";

echo "<select name='cabang'  class='cmb' onChange='Dinamiscabang(this);'>";
echo "<option value=\"\">- Pilih Cabang -</option>";
$query = mysql_query('SELECT * FROM fu_ajk_cabang WHERE id_area="'.$_GET['kode'].'"');
while ($cabang=mysql_fetch_array($query))
    {
        echo '<option value="'.$cabang['id'].'">'.$cabang['name'].'</option>';
    }
echo "</select>";
?>
