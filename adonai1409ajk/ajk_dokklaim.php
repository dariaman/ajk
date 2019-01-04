<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ADONAI - AJK Online 2014
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("../includes/functions.php");
connect();
if (isset($_SESSION['nm_user'])) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));}
switch ($_REQUEST['dok']) {
case "setbankklaim":
echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
	  <tr><th width="95%" align="left">Modul Setting Dokumen Klaim</font></th></tr>
   	  </table><br />';
if ($_REQUEST['ope']=="Simpan") {
	//$_REQUEST['id_cost'] = $_POST['id_cost']; if (!$_REQUEST['id_cost'])  $error1 .='<blink><font color=red>Silahkan pilih nama bank !</font></blink><br>';
	//$_REQUEST['id_polis'] = $_POST['id_polis']; if (!$_REQUEST['id_polis'])  $error3 .='<blink><font color=red>Silahkan pilih produk bank !</font></blink><br>';
	foreach($_REQUEST['namadok'] as $k => $met){
	$met = $met;
	}
	if (!$met)  $error2 .='<blink><font color=red>Tentukan dokumen klaim</font></blink><br>';
	//if ($error1 OR $error2)
	//{	}
	//else
	//{
		foreach($_REQUEST['namadok'] as $k => $met){
		$er = $database->doQuery('INSERT INTO fu_ajk_dokumenklaim_bank SET id_bank="'.$_REQUEST['id_cost'].'",
																		   id_dok="'.$met.'",
																		   id_produk="'.$_REQUEST['id_polis'].'",
																		   input_by="'.$q['nm_lengkap'].'",
																		   input_date="'.$futgl.'"');
		}
	echo '<blink><center>Setting dokumen klaim telah di buat oleh <b>'.$q['nm_lengkap'].'</b></center></blink><meta http-equiv="refresh" content="2; url=ajk_reg_cost.php?r=view&er=pview&id='.$_REQUEST['id_cost'].'&idp='.$_REQUEST['id_polis'].'">';
	//}
}

if ($_REQUEST['opeklaim']=="Proses") {
	$_REQUEST['id_cost'] = $_POST['id_cost']; if (!$_REQUEST['id_cost'])  $error1 .='<blink><font color=red>Silahkan pilih nama bank !</font></blink><br>';
	$_REQUEST['id_polis'] = $_POST['id_polis']; if (!$_REQUEST['id_polis'])  $error3 .='<blink><font color=red>Silahkan pilih produk bank !</font></blink><br>';
	if ($error1 OR $error2)
	{	echo '<meta http-equiv="refresh" content="0; url=ajk_dokklaim.php?dok=setbankklaim">';	}
	else
	{
echo '<table border="0" width="75%" align="center"><tr><td>
	  <form method="POST" action="" class="input-list style-1 smart-green">
	  <input type="hidden" name="id_cost" value="'.$_REQUEST['id_cost'].'">
	  <input type="hidden" name="id_polis" value="'.$_REQUEST['id_polis'].'">
	  <h1>Tambah Dokumen Klaim</h1>
   		<label><span>Nama Bank <font color="red">*</font> '.$error1.'</span>
		<select id="id_cost" name="id_cost"><option value="">--- Pilih Nama Bank---</option>';
	$comp = $database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id ="'.$_REQUEST['id_cost'].'"');
	while ($ccomp = mysql_fetch_array($comp)) {
	echo '<option value="'.$ccomp['id'].'"'._selected($_REQUEST['id_cost'], $ccomp['id']).' disabled>'.$ccomp['name'].'</option>';
	}
	echo '</select></label>
			<label><span>Nama Produk <font color="red">*</font> '.$error3.'</span>
			<select id="id_polis" name="id_polis"><option value="">--- Pilih Produk---</option>';
	$comp = $database->doQuery('SELECT * FROM fu_ajk_polis WHERE id ="'.$_REQUEST['id_polis'].'"');
	while ($ccomp = mysql_fetch_array($comp)) {
		echo '<option value="'.$ccomp['id'].'"'._selected($_REQUEST['id_polis'], $ccomp['id']).' disabled>'.$ccomp['nmproduk'].'</option>';
	}
	echo '</select></label>
<table border="0" width="100%" align="center" cellpadding="1" cellspacing="1">
<tr><th width="1%">No</th><th width="90%">Nama Dokumen</th><th><input type="checkbox" id="selectall"/></th></tr>';
$metdok = $database->doQuery('SELECT * FROM fu_ajk_dokumenklaim ORDER BY id ASC');
while ($metdok_ = mysql_fetch_array($metdok)) {
$dokBank = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dokumenklaim_bank WHERE id_bank="'.$_REQUEST['id_cost'].'" AND id_produk="'.$_REQUEST['id_polis'].'" AND id_dok="'.$metdok_['id'].'"'));
if ($dokBank['id']) {
	$metCeheck = '<input type="checkbox" class="case" name="namadok[]" value="'.$metdok_['id'].'" checked disabled>';
}else{
	$metCeheck = '<input type="checkbox" class="case" name="namadok[]" value="'.$metdok_['id'].'">';
}
if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	  <td align="center">'.++$no.'</td>
	  <td>'.$metdok_['nama_dok'].'</td>
	  <td align="center">'.$metCeheck.'</td>
	  </tr>';
}
echo '</table>
	  <label><span>&nbsp;</span><input type="submit" name="ope" value="Simpan" class="button" /></label>
	  </form>
	  </table>';
	//echo $_REQUEST['id_cost'].'<br />';
	//echo $_REQUEST['id_polis'];
}
}else{
echo '<table border="0" width="75%" align="center"><tr><td>
	  <form method="POST" action="" class="input-list style-1 smart-green">
		<h1>Tambah Dokumen Klaim</h1>
   		<label><span>Nama Bank <font color="red">*</font> '.$error1.'</span>
		<select id="id_cost" name="id_cost"><option value="" required>--- Pilih Nama Bank---</option>';
$comp = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name');
while ($ccomp = mysql_fetch_array($comp)) {
echo '<option value="'.$ccomp['id'].'"'._selected($_REQUEST['name'], $ccomp['id']).'>'.$ccomp['name'].'</option>';
	}
echo '</select></label>
		<label><span>Nama Produk <font color="red">*</font> '.$error3.'</span>
		<select name="id_polis" id="id_polis"><option value="">-- Pilih Produk --</option>
		</select></label>
<!--
	  <table border="0" width="100%" align="center" cellpadding="1" cellspacing="1">
	  <form method="post" action="" onload ="onbeforeunload">'.$error2.'
	  <tr><th width="1%">No</th><th width="90%">Nama Dokumen</th><th><input type="checkbox" id="selectall"/></th></tr>';
$metdok = $database->doQuery('SELECT * FROM fu_ajk_dokumenklaim ORDER BY id ASC');
while ($metdok_ = mysql_fetch_array($metdok)) {
if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	  <td align="center">'.++$no.'</td>
	  <td>'.$metdok_['nama_dok'].'</td>
	  <td align="center"><input type="checkbox" class="case" name="namadok[]" value="'.$metdok_['id'].'"></td>
	  </tr>';
	}
echo '</form></table>
-->
	  </label>
	  <!--<label><span>&nbsp;</span><input type="submit" name="ope" value="Simpan" class="button" /></label>-->
	  <label><span>&nbsp;</span><input type="submit" name="opeklaim" value="Proses" class="button" /></label>
	  </form>
	  </td></tr></table>';
echo"<script type='text/javascript' src='js/jquery/jquery.min-1.11.1.js'></script>
<script type='text/javascript'>//<![CDATA[
 	$(window).load(function(){
 		$(document).ready(function () {
 			(function ($) {
 				$('#cari').keyup(function () {
 					var rex = new RegExp($(this).val(), 'i');
 					$('.caritable tr').hide();
 					$('.caritable tr').filter(function () {
 						return rex.test($(this).text());
 					}).show();

 				})

 			}(jQuery));

			$('#id_cost').change(function(){
			var idcost = document.getElementById('id_cost').value;
				$.ajax({
					type:'post',
					url:'javascript/metcombo/data_peserta.php',
					data:{functionname: 'setpoliscostumer',cost:idcost},
					cache:false,
					success:function(returndata) {
						$('#id_polis').html(returndata);
					}
				});

				$.ajax({
					type:'post',
					url:'javascript/metcombo/data_peserta.php',
					data:{functionname: 'setpoliscostumerregional',cost:idcost},
					cache:false,
					success:function(returndata) {
						$('#id_reg').html(returndata);
					}
				});

			});
			$('#id_reg').change(function(){
			var noreg = document.getElementById('id_reg').value;
			var idcost = document.getElementById('id_cost').value;
				$.ajax({
					type:'post',
					url:'javascript/metcombo/data_peserta.php',
					data:{functionname: 'setpoliscostumercabang',cost:idcost,regi:noreg},
					cache:false,
					success:function(returndata) {
						$('#id_cab').html(returndata);
					}
				});

			});

 		});
 			var idcost = document.getElementById('id_cost').value;
			$.ajax({
				type:'post',
				url:'javascript/metcombo/data_peserta.php',
				data:{functionname: 'setpoliscostumer',cost:idcost},
				cache:false,
				success:function(returndata) {
					$('#id_polis').html(returndata);
				}
			});

			$.ajax({
				type:'post',
				url:'javascript/metcombo/data_peserta.php',
				data:{functionname: 'setpoliscostumerregional',cost:idcost},
				cache:false,
				success:function(returndata) {
					$('#id_reg').html(returndata);
				}
			});

			$.ajax({
				type:'post',
				url:'javascript/metcombo/data_peserta.php',
				data:{functionname: 'setpoliscostumercabang',cost:idcost,regi:noreg},
				cache:false,
				success:function(returndata) {
					$('#id_cab').html(returndata);
				}
			});
 	});


</script>";
}
	;
	break;

	case "edit_dok":
echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
	  <tr><th width="95%" align="left">Modul Dokumen Klaim</font></th><th align="center"><a title="modul dokumen klaim" href="ajk_dokklaim.php"><img src="image/Backward-64.png" border="0" width="25"></a></th></tr>
   	  </table><br />';
$met = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dokumenklaim WHERE id="'.$_REQUEST['idk'].'"'));
if ($_REQUEST['ope']=="Simpan") {
	if (!$_REQUEST['met_dok_klaim'])  $error1 .='<blink><font color=red>Nama dokumen tidak bleh kosong</font></blink><br>';
if ($error1)	{		}
	else
	{
		$r=$database->doQuery('UPDATE fu_ajk_dokumenklaim SET nama_dok="'.$_REQUEST['met_dok_klaim'].'",
									  						  update_by="'.$q['nm_lengkap'].'",
															  update_date="'.$futgl.'"
														   WHERE id="'.$_REQUEST['idk'].'"');
		header("location:ajk_dokklaim.php");
	}
}
echo '<table border="0" width="75%" align="center"><tr><td>
	  <form method="POST" action="" class="input-list style-1 smart-green">
		<h1>Tambah Dokumen Klaim</h1>
   		<label><span>Nama Dokumen Klaim <font color="red">*</font> '.$error1.'</span><br />
		<textarea name="met_dok_klaim" placeholder="Nama Dokumen Klaim"/>'.$met['nama_dok'].'</textarea>
		</label>
		<label><span>&nbsp;</span><input type="submit" name="ope" value="Simpan" class="button" /></label>
	  </form>
	  </td></tr></table>';
		;
		break;
	case "new_dok":
echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
	  <tr><th width="95%" align="left">Modul Dokumen Klaim</font></th><th align="center"><a title="modul dokumen klaim" href="ajk_dokklaim.php"><img src="image/Backward-64.png" border="0" width="25"></a></th></tr>
   	  </table><br />';
if ($_REQUEST['ope']=="Simpan") {
	if (!$_REQUEST['met_dok_klaim'])  $error1 .='<blink><font color=red>Nama dokumen tidak bleh kosong</font></blink><br>';
if ($error1)	{		}
	else
	{
		$s=$database->doQuery('INSERT INTO fu_ajk_dokumenklaim SET nama_dok="'.$_REQUEST['met_dok_klaim'].'",
									  							   input_by="'.$q['nm_lengkap'].'",
																   input_date="'.$futgl.'"');
		header("location:ajk_dokklaim.php");
	}
}
echo '<table border="0" width="75%" align="center"><tr><td>
	  <form method="POST" action="" class="input-list style-1 smart-green">
		<h1>Tambah Dokumen Klaim</h1>
   		<label><span>Nama Dokumen Klaim <font color="red">*</font> '.$error1.'</span><br />
		<textarea name="met_dok_klaim" placeholder="Nama Dokumen Klaim"/>'.$_REQUEST['met_dok_klaim'].'</textarea>
		</label>
		<label><span>&nbsp;</span><input type="submit" name="ope" value="Simpan" class="button" /></label>
	  </form>
	  </td></tr></table>';
		;
		break;
	default:
echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
	  <tr><th width="95%" align="left">Modul Dokumen Klaim</font></th><th align="center"><a title="tambah dokumen klaim" href="ajk_dokklaim.php?dok=new_dok"><img src="image/new.png" border="0" width="25"></a></th></tr>
   	  </table><br />';
echo '<table border="0" width="100%" cellpadding="4" cellspacing="1" bgcolor="#bde0e6">
	<tr>
	<th width="3%">No</th>
	<th>Nama Dokumen</th>
	<th width="10%">Input By</th>
	<th width="1%">Option</th>
	</tr>';
$metdokumen = $database->doQuery('SELECT * FROM fu_ajk_dokumenklaim WHERE del IS NULL ORDER BY id DESC');
while ($met = mysql_fetch_array($metdokumen)) {
if (($no % 2) == 1)	$objlass = 'tbl-odd'; else $objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		<td align="center">'.++$no.'</td>
		<td>'.$met['nama_dok'].'</td>
		<td align="center">'.$met['input_by'].'</td>
		<td align="center"><a title="edit dokumen klaim" href="ajk_dokklaim.php?dok=edit_dok&idk='.$met['id'].'"><img src="image/edit3.png"></a></td>
		</tr>';
}
		echo '</table>';
		;
} // switch
?>
<!--CHECKE ALL-->
<SCRIPT language="javascript">
$(function(){
    $("#selectall").click(function () {	$('.case').attr('checked', this.checked);	});			    // add multiple select / deselect functionality
    $(".case").click(function(){																	// if all checkbox are selected, check the selectall checkbox	// and viceversa
        if($(".case").length == $(".case:checked").length) {
            $("#selectall").attr("checked", "checked");
        } else {
            $("#selectall").removeAttr("checked");
        }

    });
});
</SCRIPT>