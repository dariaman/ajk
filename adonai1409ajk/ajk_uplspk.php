<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("../includes/functions.php");
connect();

if (session_is_registered('nm_user')) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));
	$qsescost=mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$q['id_cost'].'"'));
}

switch ($_REQUEST['el']) {
	case "a":
		;
		break;
	case "upload_spk":
		for($i=0;$i<count($_POST["no_spk"]);$i++)
		{
			echo "Data $i = ".$_POST["no_spk"][$i]."<br>";
		}

		;
		break;
	default:
echo '<table border="0" cellpadding="1" cellspacing="0" width="100%">
     <tr><th width="100%" align="left">Modul Upload Data SPK</font></th></tr>
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
	  <tr><td align="right">Polis</td>
		  <td id="polis_rate">: <select name="id_polis" id="id_polis">
		  <option value="">-- Pilih Polis --</option>
	  </select></td></tr>
	  <tr><td align="right">Nomor SPK</td><td>: <input type="text" name="no_spk[]" value="'.$_REQUEST['no_spk'].'" size="10"> <input name="userfile[]" type="file" size="50" onchange="checkfile(this);" ></td></tr>
	  <tr><td align="right">Nomor SPK</td><td>: <input type="text" name="no_spk[]" value="'.$_REQUEST['no_spk'].'" size="10"> <input name="userfile[]" type="file" size="50" onchange="checkfile(this);" ></td></tr>
	  <tr><td align="right">Nomor SPK</td><td>: <input type="text" name="no_spk[]" value="'.$_REQUEST['no_spk'].'" size="10"> <input name="userfile[]" type="file" size="50" onchange="checkfile(this);" ></td></tr>
	  <tr><td align="center"colspan="2"><input type="hidden" name="el" value="upload_spk"><input type="submit" name="upload_rate" value="Import"></td></tr>
	  </table>
	  </form>';
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