<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// @copyright 2014
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("../includes/functions.php");
connect();
if (isset($_SESSION['nm_user'])) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));}
$futgl = date("Y-m-d g:i:a");
switch ($_REQUEST['r']) {
	case "a":
		;
		break;

	case "newrek":
echo '<table border="0" cellpadding="1" cellspacing="0" width="100%">
     <tr><th width="100%" align="left">Data Rekening</font></th><th><a href="ajk_reg_cost.php?r=rek"><img src="../image/Backward-64.png" width="25"></a></th></tr>
     </table>';
if ($_REQUEST['ope']=="Simpan") {
if (!$_REQUEST['id_cost'])  		$error1 .='<blink><font color=red>Silahkan pilih nama perusahaan</font></blink>';
if (!$_REQUEST['id_cab'])  			$error2 .='<blink><font color=red>Silahkan pilih nama cabang</font></blink>';
if (!$_REQUEST['bankcabangdn'])  	$error11 .='<blink><font color=red>Nama bank cabang (Debet) tidak boleh kosong</font></blink>';
if (!$_REQUEST['bankcabangdn_an'])  $error12 .='<blink><font color=red>Nama pemegang rekening bank cabang (Debet) tidak boleh kosong</font></blink>';
if (!$_REQUEST['bankcabangdn_rek']) $error13 .='<blink><font color=red>Nomor rekening bank cabang (Debet) tidak boleh kosong</font></blink>';
if (!$_REQUEST['bankcabangdn_pic']) $error14 .='<blink><font color=red>Nama PIC bank cabang tidak boleh kosong</font></blink>';
if (!$_REQUEST['bankcabangcn'])  	$error15 .='<blink><font color=red>Nama bank cabang (Credit) tidak boleh kosong</font></blink>';
if (!$_REQUEST['bankcabangcn_an'])  $error16 .='<blink><font color=red>Nama pemegang rekening bank cabang (Credit) tidak boleh kosong</font></blink>';
if (!$_REQUEST['bankcabangcn_rek']) $error17 .='<blink><font color=red>Nomor rekening bank cabang (Credit) tidak boleh kosong</font></blink>';
if (!$_REQUEST['bankcabangcn_jbt']) $error18 .='<blink><font color=red>Jabatan PIC cabang tidak boleh kosong</font></blink>';
if ($error1 OR $error2 OR $error11 OR $error12 OR $error13 OR $error14 OR $error15 OR $error16 OR $error17 OR $error18) {	}
else{
$metCabang = $database->doQuery('INSERT INTO fu_ajk_rekening SET id_cost="'.$_REQUEST['id_cost'].'",
																 cabang="'.$_REQUEST['id_cab'].'",
																 rek_dn_cabang="'.$_REQUEST['bankcabangdn'].'",
																 rek_dn_cabang_name="'.$_REQUEST['bankcabangdn_an'].'",
																 rek_dn_nomor="'.$_REQUEST['bankcabangdn_rek'].'",
																 rek_cn_cabang="'.$_REQUEST['bankcabangcn'].'",
																 rek_cn_cabang_name="'.$_REQUEST['bankcabangcn_an'].'",
																 rek_cn_nomor="'.$_REQUEST['bankcabangcn_rek'].'",
																 pic_cab="'.$_REQUEST['bankcabangdn_pic'].'",
																 pic_cab_jabatan="'.$_REQUEST['bankcabangcn_jbt'].'",
																 input_by="'.$_SESSION['nm_user'].'",
																 input_time="'.$datelog.'"');
echo '<blink><center>Nomor rekening cabang telah diselesai dibuat.</b></center></blink><meta http-equiv="refresh" content="1; url=ajk_reg_cost.php?r=rek">';
}
}
echo '<form method="POST" action="" class="input-list style-1 smart-green">
		<h1>Setting Data Rekening Cabang</h1>
		<table border="0" width="100%" cellpadding="5" cellspacing="5">
		<tr><td colspan="2"><label><span>Nama Perusahaan <font color="red">*</font> '.$error1.'</span>
			   <select name="id_cost" id="id_cost">
			   <option value="">--- Pilih ---</option>';
		$comp = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name ASC');
		while ($ccomp = mysql_fetch_array($comp)) {
			echo '<option value="'.$ccomp['id'].'"'._selected($_REQUEST['name'], $ccomp['id']).'>'.$ccomp['name'].'</option>';
		}
echo '</select>
		</td></tr>
	   <tr><td colspan="2">Nama Cabang<font color="red">*</font> '.$error2.'</span><select name="id_cab" id="id_cab"><option value="">-- Pilih Cabang --</option></select></td></tr>
	   <tr><td class="title2">Bank Cabang</td></tr>
	   <tr><td valign="top">
	   	   	<table border="0" width="100%">
	   	   <tr><td><label><span>Nama Bank Cabang (Debet) <font color="red">*</font> '.$error11.'</span><input type="text" name="bankcabangdn" value="'.$_REQUEST['bankcabangdn'].'" placeholder="Nama Bank Debit Note"></label>
	   	   		   <label><span>A/n <font color="red">*</font> '.$error12.'</span><input type="text" name="bankcabangdn_an" value="'.$_REQUEST['bankcabangdn_an'].'" placeholder="Nama Pemegang Rekening"></label>
	   	   		   <label><span>Nomor Rekening <font color="red">*</font> '.$error13.'</span><input type="text" name="bankcabangdn_rek" value="'.$_REQUEST['bankcabangdn_rek'].'" placeholder="Nomor Rekening"></label>
	   	   		   <label><span>P.I.C <font color="red">*</font> '.$error14.'</span><input type="text" name="bankcabangdn_pic" value="'.$_REQUEST['bankcabangdn_pic'].'" placeholder="P.I.C"></label>
			  </td>
			  <td><label><span>Nama Bank Cabang (Credit) <font color="red">*</font> '.$error15.'</span><input type="text" name="bankcabangcn" value="'.$_REQUEST['bankcabangcn'].'" placeholder="Nama Bank Credit Note"></label>
	   	   		   <label><span>A/n <font color="red">*</font> '.$error16.'</span><input type="text" name="bankcabangcn_an" value="'.$_REQUEST['bankcabangcn_an'].'" placeholder="Nama Pemegang Rekening"></label>
	   	   		   <label><span>Nomor Rekening <font color="red">*</font> '.$error17.'</span><input type="text" name="bankcabangcn_rek" value="'.$_REQUEST['bankcabangcn_rek'].'" placeholder="Nomor Rekening"></label>
	   	   		   <label><span>Jabatan <font color="red">*</font> '.$error18.'</span><input type="text" name="bankcabangcn_jbt" value="'.$_REQUEST['bankcabangcn_jbt'].'" placeholder="Jabatan"></label>
			</td></tr>
		   </table>
	   	   </td>
	   	</tr>
		<tr><td colspan="2" align="center"><label><span>&nbsp;</span><input type="submit" name="ope" value="Simpan" class="button" /></label></td></tr>
	  </table></form>';
echo '<!--WILAYAH COMBOBOX-->
<script src="javascript/metcombo/prototype.js"></script>
<script src="javascript/metcombo/dynamicombo.js"></script>
<!--WILAYAH COMBOBOX-->
<script>
document.observe("dom:loaded",function(){
	new DynamiCombo( "id_cost" , {
		elements:{
			"id_cab":	{url:\'javascript/metcombo/data.php?req=setcabang\', value:\'id\', label:\'name\', init:\'<?php echo $_POST["id_cab"] ?>\'},
		},
		loadingImage:\'loader1.gif\',
		loadingText:\'Loading...\',
		debug:0
	} )
});
</script>';
		;
		break;

case "rekedit":
echo '<table border="0" cellpadding="1" cellspacing="0" width="100%">
     <tr><th width="100%" align="left">Data Rekening - Edit</font></th><th><a href="ajk_reg_cost.php?r=rek"><img src="../image/Backward-64.png" width="25"></a></th></tr>
     </table>';
$metRekCab = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_rekening WHERE id="'.$_REQUEST['id'].'"'));
if ($_REQUEST['ope']=="Simpan") {
if (!$_REQUEST['id_cab'])  			$error2 .='<blink><font color=red>Silahkan pilih nama cabang</font></blink>';
if (!$_REQUEST['bankcabangdn'])  	$error11 .='<blink><font color=red>Nama bank cabang (Debet) tidak boleh kosong</font></blink>';
if (!$_REQUEST['bankcabangdn_an'])  $error12 .='<blink><font color=red>Nama pemegang rekening bank cabang (Debet) tidak boleh kosong</font></blink>';
if (!$_REQUEST['bankcabangdn_rek']) $error13 .='<blink><font color=red>Nomor rekening bank cabang (Debet) tidak boleh kosong</font></blink>';
if (!$_REQUEST['bankcabangdn_pic']) $error14 .='<blink><font color=red>Nama PIC bank cabang tidak boleh kosong</font></blink>';
if (!$_REQUEST['bankcabangcn'])  	$error15 .='<blink><font color=red>Nama bank cabang (Credit) tidak boleh kosong</font></blink>';
if (!$_REQUEST['bankcabangcn_an'])  $error16 .='<blink><font color=red>Nama pemegang rekening bank cabang (Credit) tidak boleh kosong</font></blink>';
if (!$_REQUEST['bankcabangcn_rek']) $error17 .='<blink><font color=red>Nomor rekening bank cabang (Credit) tidak boleh kosong</font></blink>';
if (!$_REQUEST['bankcabangcn_jbt']) $error18 .='<blink><font color=red>Jabatan PIC cabang tidak boleh kosong</font></blink>';
if ($error2 OR $error11 OR $error12 OR $error13 OR $error14 OR $error15 OR $error16 OR $error17 OR $error18) {	}
else{
$metCabang = $database->doQuery('UPDATE fu_ajk_rekening SET cabang="'.$_REQUEST['id_cab'].'",
															rek_dn_cabang="'.$_REQUEST['bankcabangdn'].'",
															rek_dn_cabang_name="'.$_REQUEST['bankcabangdn_an'].'",
															rek_dn_nomor="'.$_REQUEST['bankcabangdn_rek'].'",
															rek_cn_cabang="'.$_REQUEST['bankcabangcn'].'",
															rek_cn_cabang_name="'.$_REQUEST['bankcabangcn_an'].'",
															rek_cn_nomor="'.$_REQUEST['bankcabangcn_rek'].'",
															pic_cab="'.$_REQUEST['bankcabangdn_pic'].'",
															pic_cab_jabatan="'.$_REQUEST['bankcabangcn_jbt'].'",
															update_by="'.$_SESSION['nm_user'].'",
															update_time="'.$datelog.'"
															WHERE id="'.$_REQUEST['id'].'"');
	echo '<blink><center>Nomor rekening cabang telah diselesai diedit.</b></center></blink><meta http-equiv="refresh" content="1; url=ajk_reg_cost.php?r=rek">';
}
}
echo '<form method="POST" action="" class="input-list style-1 smart-green">
		<h1>Edit Data Rekening Cabang</h1>
		<table border="0" width="100%" cellpadding="5" cellspacing="5">
		<input type="hidden" name="id" value="'.$_REQUEST['id'].'">';
$comp = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$metRekCab['id_cost'].'"'));
echo '<tr><td colspan="2"><label><span>Nama Perusahaan : '.$comp['name'].'</span>';
echo '</td></tr>
	   <tr><td colspan="2">Nama Cabang<font color="red">*</font> '.$error2.'</span>
			<select name="id_cab" id="id_cab"><option value="">-- Pilih Cabang --</option>';
	$comp = $database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id_cost="'.$metRekCab['id_cost'].'" GROUP BY name ORDER BY name ASC');
	while ($ccomp = mysql_fetch_array($comp)) {
		echo '<option value="'.$ccomp['id'].'"'._selected($metRekCab['cabang'], $ccomp['id']).'>'.$ccomp['name'].'</option>';
	}
echo '</select>
	   </td></tr>
	   <tr><td class="title2">Bank Cabang</td></tr>
	   <tr><td valign="top">
	   	   	<table border="0" width="100%">
	   	   <tr><td><label><span>Nama Bank Cabang (Debet) <font color="red">*</font> '.$error11.'</span><input type="text" name="bankcabangdn" value="'.$metRekCab['rek_dn_cabang'].'" placeholder="Nama Bank Debit Note"></label>
	   	   		   <label><span>A/n <font color="red">*</font> '.$error12.'</span><input type="text" name="bankcabangdn_an" value="'.$metRekCab['rek_dn_cabang_name'].'" placeholder="Nama Pemegang Rekening"></label>
	   	   		   <label><span>Nomor Rekening <font color="red">*</font> '.$error13.'</span><input type="text" name="bankcabangdn_rek" value="'.$metRekCab['rek_dn_nomor'].'" placeholder="Nomor Rekening"></label>
	   	   		   <label><span>P.I.C <font color="red">*</font> '.$error14.'</span><input type="text" name="bankcabangdn_pic" value="'.$metRekCab['pic_cab'].'" placeholder="P.I.C"></label>
		  </td>
		  <td><label><span>Nama Bank Cabang (Credit) <font color="red">*</font> '.$error15.'</span><input type="text" name="bankcabangcn" value="'.$metRekCab['rek_cn_cabang'].'" placeholder="Nama Bank Credit Note"></label>
	   	   		   <label><span>A/n <font color="red">*</font> '.$error16.'</span><input type="text" name="bankcabangcn_an" value="'.$metRekCab['rek_cn_cabang_name'].'" placeholder="Nama Pemegang Rekening"></label>
	   	   		   <label><span>Nomor Rekening <font color="red">*</font> '.$error17.'</span><input type="text" name="bankcabangcn_rek" value="'.$metRekCab['rek_cn_nomor'].'" placeholder="Nomor Rekening"></label>
	   	   		   <label><span>Jabatan <font color="red">*</font> '.$error18.'</span><input type="text" name="bankcabangcn_jbt" value="'.$metRekCab['pic_cab_jabatan'].'" placeholder="Jabatan"></label>
		</td></tr>
	   </table>
	   	   </td>
	   	</tr>
	<tr><td colspan="2" align="center"><label><span>&nbsp;</span><input type="submit" name="ope" value="Simpan" class="button" /></label></td></tr>
	  </table></form>';
echo '<!--WILAYAH COMBOBOX-->
<script src="javascript/metcombo/prototype.js"></script>
<script src="javascript/metcombo/dynamicombo.js"></script>
<!--WILAYAH COMBOBOX-->
<script>
document.observe("dom:loaded",function(){
	new DynamiCombo( "id_cost" , {
	elements:{
		"id_cab":	{url:\'javascript/metcombo/data.php?req=setcabang\', value:\'id\', label:\'name\', init:\'<?php echo $_POST["id_cab"] ?>\'},
	},
	loadingImage:\'loader1.gif\',
	loadingText:\'Loading...\',
	debug:0
	} )
});
</script>';
		;
		break;


	case "rek":
echo '<table border="0" cellpadding="1" cellspacing="0" width="100%">
     <tr><th width="100%" align="left">Data Rekening</font></th><th><a href="ajk_reg_cost.php?r=newrek"><img src="../image/new.png" width="25"></a></th></tr>
     </table>';
echo '<table border="0" width="100%" cellpadding="1" cellspacing="1">
	<form method="post" action="">
	<tr><td width="10%">Nama Cabang :</td><td><input type="text" name="cabrek" value="'.$_REQUEST['cabrek'].'"> <input type="submit" name="button" value="Cari" class="button"></td></tr>
	</form></table>';
if ($_REQUEST['cabrek'])		{	$satu = 'AND fu_ajk_cabang.`name` LIKE "%' . $_REQUEST['cabrek'] . '%"';		}
if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}
$t=$database->doQuery('SELECT fu_ajk_costumer.`name` AS perusahaan,
							  fu_ajk_cabang.`name` AS cabang,
							  fu_ajk_rekening.id,
							  fu_ajk_rekening.rek_dn_cabang AS debet_bank,
							  fu_ajk_rekening.rek_dn_cabang_name AS debet_an,
							  fu_ajk_rekening.rek_dn_nomor AS debet_nomor,
							  fu_ajk_rekening.rek_cn_cabang AS kredit_bank,
							  fu_ajk_rekening.rek_cn_cabang_name AS kredit_an,
							  fu_ajk_rekening.rek_cn_nomor AS kredit_nomor,
							  fu_ajk_rekening.pic_cab AS pic_cab,
							  fu_ajk_rekening.pic_cab_jabatan AS pic_jabatan
							  FROM fu_ajk_rekening
							  LEFT JOIN fu_ajk_costumer ON fu_ajk_rekening.id_cost = fu_ajk_costumer.id
							  LEFT JOIN fu_ajk_cabang ON fu_ajk_rekening.cabang = fu_ajk_cabang.id
							  WHERE fu_ajk_rekening.id_cost !="" '.$satu.'
							  ORDER BY fu_ajk_rekening.id ASC LIMIT '.$m.' , 25');
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(fu_ajk_rekening.id) FROM fu_ajk_rekening
LEFT JOIN fu_ajk_costumer ON fu_ajk_rekening.id_cost = fu_ajk_costumer.id
LEFT JOIN fu_ajk_cabang ON fu_ajk_rekening.cabang = fu_ajk_cabang.id
WHERE fu_ajk_rekening.id_cost !="" '.$satu.''));
$totalRows = $totalRows[0];
echo '<table border="0" cellpadding="3" cellspacing="1" width="100%" bgcolor="#bde0e6">
	<tr align="center">
	<th rowspan="2" width="3%">No</th>
	<th rowspan="2">Nama Perusahaan</th>
	<th rowspan="2">Cabang</th>
	<th colspan="3">Debet</th>
	<th colspan="3">Kredit</th>
	<th rowspan="2" width="15%">P.I.C</th>
	<th rowspan="2" width="1%">Jabatan</th>
	<th rowspan="2" width="5%">Edit</th>
	</tr>
	<tr><th>Nama Bank</th>
		<th>A/n</th>
		<th>Nomor Rekening</th>
		<th>Nama Bank</th>
		<th>A/n</th>
		<th>Nomor Rekening</th>
	</tr>';
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while($tt=mysql_fetch_array($t)){
if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	      <td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
		  <td>'.$tt['perusahaan'].'</td>
		  <td align="center">'.$tt['cabang'].'</td>
		  <td align="center">'.$tt['debet_bank'].'</td>
		  <td align="center">'.$tt['debet_an'].'</td>
		  <td align="center">'.$tt['debet_nomor'].'</td>
		  <td align="center">'.$tt['kredit_bank'].'</td>
		  <td align="center">'.$tt['kredit_an'].'</td>
		  <td align="center">'.$tt['kredit_nomor'].'</td>
		  <td align="center">'.$tt['pic_cab'].'</td>
		  <td align="center">'.$tt['pic_jabatan'].'</td>
		  <td align="center"><a href="ajk_reg_cost.php?r=rekedit&id='.$tt['id'].'&op=edit"><img border="0" src="../image/editaja.png" width="20"></td>
	</tr>';
} // while
echo '<tr><td colspan="22">';
echo createPageNavigations($file = 'ajk_reg_cost.php?r=rek&', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
echo '<b>Total Data Rekening Cabang : <u>' . $totalRows . '</u></b></td></tr>
	</table>';
		;
		break;

	case "docmeninggal":
		echo '<table border="0" cellpadding="1" cellspacing="0" width="100%">
     <tr><th width="100%" align="left">Data Costumer - Setting Dokumen Klaim Meninggal</font></th>
     	 <th><a href="ajk_reg_cost.php"><img src="image/back.png" width="20"></a></th>
     	 <th><a href="ajk_reg_cost.php?r=docmeninggal&id='.$_REQUEST['id'].'&new=doc"><img src="image/new.png" width="20"></a></th>
     </tr>
     </table>';
		if ($_REQUEST['new']=="doc") {
			if ($_REQUEST['doc_ok']=="Ok") {
				$_REQUEST['dok_klaim'] = $_POST['dok_klaim'];	if (!$_REQUEST['dok_klaim'])  $error .='<blink><font color=red>Dokumen klaim tidak boleh kosong.</font></blink><br>';
				if ($error)
				{	echo '<table width="100%" class="bgcolor1">
							  <tr><td align="left"><img src="image/warning.gif" border="0"></td>
								  <td align="center"><font class="option"><blink>'.$error.'</blink></td>
								  <td align="right"><img src="image/warning.gif" border="0"></td>
							  </tr>
							  </table>';
				}
				else
				{
					$met = $database->doQuery('INSERT INTO fu_ajk_klaim_dokumen SET id_cost="'.$_REQUEST['id_cost'].'",
																	dokumen="'.ucwords($_REQUEST['dok_klaim']).'",
																	input_by="'.$q['nm_lengkap'].'",
																	input_time="'.$futgl.'"');
					echo '<center>Data dokumen meninggal telah di tambah oleh '.$q['nm_lengkap'].' pada tanggal '.$futgl.'.</center><meta http-equiv="refresh" content="3; url=ajk_reg_cost.php?r=docmeninggal&id='.$_REQUEST['id_cost'].'">';
				}
			}
			echo '<form method="post" action="">
			  <table border="0" cellpadding="1" cellspacing="0" width="50%" align="center">
			  <input type="hidden" name="id_cost" value="'.$_REQUEST['id'].'">
			  <tr><td width="20%">Dokumen Klaim</td>
			  	  <td>: <input type="text" name="dok_klaim" value="'.$_REQUEST['dok_klaim'].'" size="75"> <input type="submit" name="doc_ok" value="Ok"></td>
			  </tr>
			  </table>
			  </form>';
		}

		if ($_REQUEST['edit']=="editdoc") {
			if ($_REQUEST['doc_ok_edit']=="Ok") {
				$metupdate = $database->doQuery('UPDATE fu_ajk_klaim_dokumen SET dokumen="'.ucwords($_REQUEST['dok_klaim']).'", update_by="'.$q['nm_lengkap'].'", update_time="'.$futgl.'" WHERE id="'.$_REQUEST['idD'].'"');
				echo '<center>Data dokumen meninggal telah di update oleh '.$q['nm_lengkap'].' pada tanggal '.$futgl.'.</center><meta http-equiv="refresh" content="3; url=ajk_reg_cost.php?r=docmeninggal&id='.$_REQUEST['id_cost'].'">';
			}
			$metedit = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_klaim_dokumen WHERE id="'.$_REQUEST['idD'].'"'));
			echo '<form method="post" action="">
			  <table border="0" cellpadding="1" cellspacing="0" width="50%" align="center">
			  <input type="hidden" name="id_cost" value="'.$metedit['id_cost'].'">
			  <input type="hidden" name="idD" value="'.$metedit['id'].'">
			  <tr><td width="20%">Dokumen Klaim</td>
			  	  <td>: <input type="text" name="dok_klaim" value="'.$metedit['dokumen'].'" size="75"> <input type="submit" name="doc_ok_edit" value="Ok"></td>
			  </tr>
			  </table>
			  </form>';
		}
		echo '<table border="0" cellpadding="3" cellspacing="1" width="50%" align="center" bgcolor="#bde0e6">
	  <tr><th width="1%">No</th><th>Nama Dokumen</th><th width="1%">Option</th></tr>';
		$metdok = $database->doQuery('SELECT * FROM fu_ajk_klaim_dokumen WHERE id_cost="'.$_REQUEST['id'].'" ORDER BY dokumen DESC');
		while ($rdok = mysql_fetch_array($metdok)) {
			if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
			echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
				<td align="center">'.++$no.'</td>
				<td>'.$rdok['dokumen'].'</td>
				<td align="center"><a href="ajk_reg_cost.php?r=docmeninggal&id='.$rdok['id_cost'].'&idD='.$rdok['id'].'&edit=editdoc"><img src="image/edit3.png" width="20"></a></td>
			  </tr>';
		}
		echo '</table>';

		;
		break;

	case "edit":
echo '<table border="0" cellpadding="1" cellspacing="0" width="100%"><tr><th width="95%" align="left">Daftar Costumer - Edit</font></th><th><a href="ajk_reg_cost.php"><img src="../image/Backward-64.png" width="25"></a></th></tr></table><br />';
if ($_REQUEST['ope']=="Simpan") {
	if (!$_REQUEST['nama'])  $error1 .='<blink><font color=red>Nama perusahaan tidak boleh kosong</font></blink><br>';
	if (!$_REQUEST['wilayahnya'])  $error2 .='<blink><font color=red>Wilayah tidak boleh kosong</font></blink><br>';
	if (!$_REQUEST['cost_alamat'])  $error3 .='<blink><font color=red>Alamat perusahaan tidak boleh kosong</font></blink><br>';
	if (!$_REQUEST['phone'])  $error4 .='<blink><font color=red>Nomor Telephone tidak boleh kosong</font></blink><br>';
	if ($error1 OR $error2 OR $error3 OR $error4)
	{		}
	else
	{
		$met = $database->doQuery('UPDATE fu_ajk_costumer SET name="'.strtoupper($_REQUEST['nama']).'",
																address="'.$_REQUEST['cost_alamat'].'",
																wilayah="'.$_REQUEST['wilayahnya'].'",
																picphone="'.$_REQUEST['phone'].'",
																fax="'.$_REQUEST['fax'].'",
																email="'.$_REQUEST['email'].'",
																city="'.$_REQUEST['kota'].'",
																postcode="'.$_REQUEST['kodepos'].'",
																pic="'.$_REQUEST['pic'].'",
																printlogo="'.$_REQUEST['eprintlogo'].'",
																update_by="'.$_SESSION['nm_user'].'",
																update_time="'.$datelog.'" WHERE id="'.$_REQUEST['id'].'"');
		echo '<script language="Javascript">window.location="ajk_reg_cost.php"</script>';
	}
}
$client = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$_REQUEST['id'].'"'));
echo '<table border="0" width="50%" align="center"><tr><td>
		<form method="POST" action="" class="input-list style-1 smart-green">
		<h1>Edit Data Perusahaan</h1>
		<label><span>Nama Perusahaan <font color="red">*</font> '.$error1.'</span><input type="text" name="nama" value="'.$client['name'].'" size="30" placeholder="Nama Perusahaan"></label>
		<label><span>Wilayah <font color="red">*</font> '.$error2.'</span>
		  		<select size="1" name="wilayahnya">
		   			<option value="">-- Wilayah --</option>
		   			<option value="Pusat"'._selected($client["wilayah"], "Pusat").'>Pusat</option>
		   			<option value="Region"'._selected($client["wilayah"], "Region").'>Region</option>
		   			<option value="Cabang"'._selected($client["wilayah"], "Cabang").'>Cabang</option>
		   			<option value="Cabang Pembantu"'._selected($client["wilayah"], "Cabang Pembantu").'>Cabang Pembantu</option>
		   			<option value="Cabang Unit"'._selected($client["wilayah"], "Cabang Unit").'>Cabang Unit</option>
		   			<option value="Lainnya"'._selected($client["wilayah"], "Lainnya").'>Lainnya</option>
		   			</select>
		 </label>
		 <label><span>Alamat <font color="red">*</font> '.$error3.'</span><textarea name="cost_alamat" cols="46" rows="2" placeholder="Alamat Perusahaan">'.$client['address'].'</textarea></label>
		 <label><span>Phone <font color="red">*</font> '.$error4.'</span><input type="text" name="phone" value="'.$client['picphone'].'" size="30" placeholder="Tlp / Hp"></label>
		 <label><span>Fax</span><input type="text" name="fax" value="'.$client['fax'].'" size="30" placeholder="Fax"></label>
		 <label><span>Email <font color="red">*</font> '.$error5.'</span><input type="text" name="email" value="'.$client['email'].'" size="30" placeholder="Email"></label>
		 <label><span>Kota</span><input type="text" name="kota" value="'.$client['city'].'" size="30" placeholder="Kota Perusahaan"></label>
		 <label><span>P I C</span><input type="text" name="pic" value="'.$client['pic'].'" size="30" placeholder="PIC Bank"></label>
		 <label><span>Kodepos</span><input type="text" name="kodepos" value="'.$client['postcode'].'" size="30" placeholder="Kodepos"></label>
		 <label><span>Tampilkan Logo</span>
		  		<select size="1" name="eprintlogo">
		   			<option value="">-- Logo --</option>
		   			<option value="Y"'._selected($client["printlogo"], "Y").'>Ya</option>
		   			<option value="T"'._selected($client["printlogo"], "T").'>Tidak</option>
		   			</select>
		 </label>';
/*
		 <label><span>P I C <font color="red">*</font> '.$error5.'</span>
		 	<select id="picnya" name="picnya">
		  	<option value="">-----Pilih PIC-----</option>';
		$msc = $database->doQuery('SELECT * FROM fu_ajk_agent WHERE del IS NULL ORDER BY name');
		while ($agent = mysql_fetch_array($msc)) {	echo '<option value="'.$agent['id'].'"'._selected($client['pic'], $agent['id']).'>'.$agent['name'].'</option>';	}
		echo '</select></label>
*/
echo '<label><span>&nbsp;</span><input type="submit" name="ope" value="Simpan" class="button" /></label>
		  </form></td></tr></table>';
		;
		break;

	case "editlogo":
echo '<table border="0" cellpadding="1" cellspacing="0" width="100%"><tr><th width="95%" align="left">Daftar Costumer - Edit Logo</font></th><th><a href="ajk_reg_cost.php"><img src="../image/Backward-64.png" width="25"></a></th></tr></table><br />';
$client = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$_REQUEST['id'].'"'));
if ($_REQUEST['ope']=="Simpan") {
if ($_FILES['userfile']['size'] / 1024 > $met_ttdsize)	{	$error6 .="<blink><font color=red>File tidak boleh lebih dari 500Kb !</font></blink><br />";	}
else{
if (!$_FILES['userfile']['tmp_name'])  $error6 .='<blink><font color=red>Silahkan upload file logo bank.</font></blink><br>';
	$allowedExtensions = array("jpg","jpeg","gif", "png");
	foreach ($_FILES as $file) {
		if ($file['tmp_name'] > '') {
			if (!in_array(end(explode(".",	strtolower($file['name']))), $allowedExtensions)) {
				die('<center><font color=red>'.$file['name'].' <br /><blink>File extension tidak diperbolehkan selain image!</blink></font><br/>'.'<a href="ajk_reg_cost.php?r=editlogo&id='.$_REQUEST['id'].'">'.'&lt;&lt Go Back</a></center>');
			}
		}
	}
}
			if ($error6)
			{	}
			else
			{
				unlink("$metpath_ttd/$client[logobank]");
				//gambar_kecil($direktori,$file_type)
				//move_uploaded_file($_FILES['userfile']['tmp_name'], $metpath_ttd . $_FILES['userfile']['name']);
				$nama_file =  strtolower($_FILES['userfile']['name']);
				$file_type = $_FILES['userfile']['type']; //tipe file
				$source = $_FILES['userfile']['tmp_name'];
				$direktori = "$metpath_ttd/$nama_file"; // direktori tempat menyimpan file
				move_uploaded_file($source,$direktori);
				gambar_kecil($direktori,$file_type);
				$met = $database->doQuery('UPDATE fu_ajk_costumer SET logobank="'.$_FILES['userfile']['name'].'",
																	  update_by="'.$_SESSION['nm_user'].'",
																	  update_time="'.$datelog.'" WHERE id="'.$_REQUEST['id'].'"');
				echo '<script language="Javascript">window.location="ajk_reg_cost.php"</script>';
			}
		}
echo '<table border="0" width="50%" align="center"><tr><td>
		<form method="POST" action="" class="input-list style-1 smart-green" enctype="multipart/form-data">
		<h1>Penambahan Data Perusahaan</h1>
		<label><span>Nama Perusahaan </span><input type="text" name="nama" value="'.$client['name'].'" size="30" placeholder="Nama Perusahaan" disabled></label>
		<label><span>Upload Logo Bank (max size 500Kb)<font color="red">*</font> '.$error6.'</span>
					<input name="userfile" type="file" size="50" placeholder="Logo Bank" onchange="checkfile(this);"><img src="../ajk_file/_ttd/'.$client['logobank'].'"></label>';
echo '<label><span>&nbsp;</span><input type="submit" name="ope" value="Simpan" class="button" /></label>
		  </form></td></tr></table>';
			;
			break;

	case "view":
echo '<table border="0" cellpadding="1" cellspacing="0" width="100%">
     <tr><th width="100%" align="left">Daftar Costumer - View</font></th><th><a href="ajk_reg_cost.php"><img src="../image/Backward-64.png" width="25"></a></th></tr>
     </table>';
$met = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$_REQUEST['id'].'"'));
echo '<table border="0" cellpadding="3" cellspacing="3" width="100%">
	  <tr><td width="5%" rowspan="9" width="7%" valign="top" align="center"><img src="../ajk_file/_ttd/'.$met['logobank'].'"></td>
		  <td colspan="2"><b>'.$met['name'].'</b></td>
	  </tr>
	  <tr><td colspan="2">'.nl2br($met['address']).'<br />'.$met['city'].'<br />'.$met['postcode'].'</td>
	  </tr>
	  <tr><td width="10%">Telephone</td><td>: '.$met['picphone'].'</td></tr>
	  <tr><td>Fax</td><td>: '.$met['fax'].'</td></tr>
	  <tr><td>Email</td><td>: '.$met['email'].'</td></tr>
	  <tr><td>PIC</td><td>: '.$met['pic'].'</td></tr>
	  </table>';
if ($_REQUEST['er']=="pview") {
$metproduk = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$_REQUEST['idp'].'"'));
echo '<table border="0" cellpadding="1" cellspacing="0" width="100%">
     <tr><th><a href="ajk_reg_cost.php?r=view&id='.$_REQUEST['id'].'"><img src="../image/Backward-64.png" width="25"></a></th><th width="100%" align="left">Nama Produk '.$metproduk['nmproduk'].'</font></th></tr>
     </table>';
echo '<table border="0" width="100%">
	  <tr><td width="20%" class="title_shadow">User Input</td><td colspan="3" class="title_shadow">: '.strtoupper($metproduk['input_by']).'</td></tr>
	  <tr><td width="20%" class="title_shadow">Tanggal Input</td><td colspan="3" class="title_shadow">: '.$metproduk['input_date'].'</td></tr>
	  <tr><td width="20%">Nomor Referensi</td><td colspan="3">: '.$metproduk['noreferensi'].'</td></tr>
	  <tr><td>Nomor Kontrak</td><td colspan="3">: '.$metproduk['nokontrak'].'</td></tr>
	  <tr><td>Tanggal Kontrak</td><td colspan="3">: '._convertDate($metproduk['tglkontrak']).'</td></tr>
	  <tr><td colspan="4" class="title2">Policy Rules</td></tr>
	  <tr><td>Type Polis</td><td width="30%">: '.strtoupper($metproduk['polis_type']).'</td>
	  	  <td width="20%">Jumlah Hari (Akhir Kredit)</td><td>: '.$metproduk['day_kredit'].' hari</td>
	  </tr>
	  <tr><td>Effective Date</td><td>: '._convertDate($metproduk['polis_start']).' - '._convertDate($metproduk['polis_end']).'</td>
		  <td>Single Rate</td><td>: '.$metproduk['singlerate'].'</td>
	  </tr>
	  <tr><td>Admin Fee</td><td>: '.duit($metproduk['adminfee']).'</td>
		<td>Jatuh Tempo Premi (wpc)</td><td>: '.duit($metproduk['jtempo']).' hari</td>
	  </tr>
	  <tr><td>Brokrage</td><td>: '.duit($metproduk['brokrage']).'</td>
		<td>Ppn</td><td>: '.duit($metproduk['ppn']).'%</td>
	  </tr>
	  <tr><td>Discount</td><td>: '.duit($metproduk['discount']).'</td>
		<td>Pph 23</td><td>: '.duit($metproduk['pph23']).'%</td>
	  </tr>
	  <tr><td>Cara Pembayaran</td><td>: '.$metproduk['waypaid'].'</td>
		<td>Type Benefit</td><td>: '.$metproduk['benefit'].'</td>
	  </tr>
	  <tr><td colspan="4" class="title2">Agent</td></tr>
	  <tr><td>Nama Penutup (GC/Agen)</td><td>: {nama penutup}</td>
		<td>Nama Manager Group</td><td>: {nama manager}</td>
	  </tr>
	  <tr><td>Posisi</td><td>: {posisi}</td>
		<td>Posisi</td><td>: {posisi}</td>
	  </tr>
	  <tr><td colspan="4" class="title2">Limit Insurance</td></tr>
	  <tr><td>Usia</td><td colspan="3">: '.$metproduk['age_min'].' -  '.$metproduk['age_max'].' thn</td></tr>
	  <tr><td>Maximum&nbsp; of Sum Insured</td><td colspan="3">: '.duit($metproduk['up_max']).'</td></tr>
	  <tr><td>Limit Financial</td><td colspan="3">: '.duit($metproduk['limitfinancial']).'</td></tr>
	  <tr><td colspan="4" class="title2">Bank</td></tr>
	  <tr><td>Nama Bank (DN)</td><td>: '.$metproduk['bank_1'].'</td>
		  <td>Nama Bank (CN)</td><td>: '.$metproduk['bank_2'].'</td>
	  </tr>
	  <tr><td>Cabang</td><td>: '.$metproduk['cabang_1'].'</td>
		  <td>Cabang</td><td>: '.$metproduk['cabang_2'].'</td>
	  </tr>
	  <tr><td>Nomor Rekening</td><td>: '.$metproduk['rek_1'].'</td>
		  <td>Nomor Rekening</td><td>: '.$metproduk['rek_2'].'</td>
	  </tr>
</table>';
if ($_REQUEST['del']=="dokklaim") {
	$d=$database->doQuery('DELETE FROM fu_ajk_dokumenklaim_bank WHERE id="'.$_REQUEST['iddok'].'"');
	header('location:ajk_reg_cost.php?r=view&er=pview&id='.$_REQUEST['id'].'&idp='.$_REQUEST['idp'].'');
}
echo '</table>
<table border="0" cellpadding="1" cellspacing="1" width="100%">
	  <tr><th colspan="16">NAMA DOKUMEN KLAIM</th></tr>
	  <tr><th width="1%">No</th>
	  	  <th align="left">Dokumen</th>
	  	  <th width="1%">Option</th>
	  </tr>';
$met_dokumen = $database->doQuery('SELECT * FROM fu_ajk_dokumenklaim_bank WHERE id_bank="'.$metproduk['id_cost'].'" AND id_produk="'.$metproduk['id'].'" ORDER BY id ASC');
while ($metDokumen = mysql_fetch_array($met_dokumen)) {
	$m_dokumen = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dokumenklaim WHERE id="'.$metDokumen['id_dok'].'"'));
if (($no1 % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		  <td align="center">'.++$no1.'</td>
		  <td>'.$m_dokumen['nama_dok'].'</td>
		  <td align="center"><a title="Hapus dokumen klaim" href="ajk_reg_cost.php?r=view&er=pview&del=dokklaim&id='.$metDokumen['id_bank'].'&idp='.$metDokumen['id_produk'].'&iddok='.$metDokumen['id'].'" onClick="if(confirm(\'Apakah anda yakin akan menghapus dokumen klaim ini ?\')){return true;}{return false;}"><img src="image/delete.gif"></a></td>
		  </tr>';
}
}elseif ($_REQUEST['er']=="tblrate") {

	//SETTING UNTUK STATUS RATE PREMI
	if ($_REQUEST['sett']=="nonaktifrate") {
		$met_rate = $database->doQuery('UPDATE fu_ajk_ratepremi SET status="lama" WHERE id_cost="'.$_REQUEST['id'].'" AND id_polis="'.$_REQUEST['idp'].'" AND status="'.$_REQUEST['status'].'" AND input_date="'.$_REQUEST['dttime'].'" AND del IS NULL');
		header('location:ajk_reg_cost.php?r=view&er=tblrate&id='.$_REQUEST['id'].'&idp='.$_REQUEST['idp'].'');
	}
	if ($_REQUEST['sett']=="aktifrate") {
		$met_rate = $database->doQuery('UPDATE fu_ajk_ratepremi SET status="baru" WHERE id_cost="'.$_REQUEST['id'].'" AND id_polis="'.$_REQUEST['idp'].'" AND status="'.$_REQUEST['status'].'" AND input_date="'.$_REQUEST['dttime'].'" AND del IS NULL');
		header('location:ajk_reg_cost.php?r=view&er=tblrate&id='.$_REQUEST['id'].'&idp='.$_REQUEST['idp'].'');
	}
	//SETTING UNTUK STATUS RATE PREMI
	$metproduk = mysql_fetch_array($database->doQuery('SELECT fu_ajk_costumer.id AS idcost, fu_ajk_costumer.`name`, fu_ajk_polis.id AS idproduk, fu_ajk_polis.nmproduk, fu_ajk_polis.singlerate, fu_ajk_polis.benefit
																									   FROM fu_ajk_costumer
																									   INNER JOIN fu_ajk_polis ON fu_ajk_costumer.id = fu_ajk_polis.id_cost
																									   WHERE  fu_ajk_costumer.id="'.$_REQUEST['id'].'" AND fu_ajk_polis.id="'.$_REQUEST['idp'].'"'));
	echo '<table border="0" cellpadding="1" cellspacing="1" width="100%">
		  <tr><th><a href="ajk_reg_cost.php?r=view&id='.$_REQUEST['id'].'"><img src="../image/Backward-64.png" width="25"></a></th>
			  <th width="100%" align="left">Nama Produk '.$metproduk['nmproduk'].'</font></th>
		  </tr>
		  </table>';
	if ($metproduk['benefit']=="F") {	$tipebenefit = "Flat";	}else{	$tipebenefit = "Decreasing";	}

	if ($metproduk['singlerate']=="T") {
		$mametrate_group = $database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$metproduk['idcost'].'" AND id_polis="'.$metproduk['idproduk'].'" AND del IS NULL GROUP BY input_date, status');
		while ($mametrategroup_ = mysql_fetch_array($mametrate_group)) {
			if ($mametrategroup_['status']=="baru") {
				$mametsplitrate = '<a title="nonaktifkan rate" href="ajk_reg_cost.php?r=view&er=tblrate&id='.$_REQUEST['id'].'&idp='.$_REQUEST['idp'].'&sett=nonaktifrate&status='.$mametrategroup_['status'].'&dttime='.$mametrategroup_['input_date'].'" onClick="if(confirm(\'Apakah anda yakin akan menonaktifkan keseluruhan rate ini ?\')){return true;}{return false;}"><img src="image/arrows_change_1.png" width="30"></a>';
			}else{
				$mametsplitrate = '<a title="aktifkan rate" href="ajk_reg_cost.php?r=view&er=tblrate&id='.$_REQUEST['id'].'&idp='.$_REQUEST['idp'].'&sett=aktifrate&status='.$mametrategroup_['status'].'&dttime='.$mametrategroup_['input_date'].'" onClick="if(confirm(\'Apakah anda yakin akan mengaktifkan keseluruhan rate ini ?\')){return true;}{return false;}"><img src="image/arrows_change.png" width="30"></a>';
			}
			echo '<table border="0" cellpadding="1" cellspacing="0" width="50%" align="center">
					  <tr><td><br />'.$mametsplitrate.'</td></tr>
					  <tr>
					  	<th width="1%">No</th>
				  	  <th width="10%">Type</th>
				  	  <th width="10%">Tenor (bln)</th>
				  	  <th width="10%">Rate</th>
				  	  <th width="30%">User</th>
				  	  <th width="20%">Date</th>
				  	  <th>Status</th>
				 		</tr>';
			$mametrate = $database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$metproduk['idcost'].'" AND id_polis="'.$metproduk['idproduk'].'" AND status="'.$mametrategroup_['status'].'" AND input_date="'.$mametrategroup_['input_date'].'" AND del IS NULL');
			while ($mametrate_ = mysql_fetch_array($mametrate)) {
				if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
				if ($mametrate_['status']=="baru") {
					$statusrate = "<font color=blue>aktif</font>";
				}else{
					$statusrate = "<font color=red>tidak aktif</font>";
				}
				echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
					  <td align="center" width=1%>'.++$no.'</td>
				  	<td align="center">'.$tipebenefit.'</td>
				  	<td align="center">'.$mametrate_['tenor'].'</td>
				  	<td align="center">'.$mametrate_['rate'].' &nbsp;</td>
				  	<td align="center">'.$mametrate_['input_by'].'</td>
				  	<td align="center">'.$mametrate_['input_date'].'</td>
				  	<td align="center">'.strtoupper($statusrate).'</td>
				  	</tr>';
			}
		}
	}else{		
		$mametrate_group = $database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$metproduk['idcost'].'" AND id_polis="'.$metproduk['idproduk'].'" AND del IS NULL GROUP BY input_date, status');
		while ($mametrategroup_ = mysql_fetch_array($mametrate_group)) {
			if ($mametrategroup_['status']=="baru") {
				$mametsplitrate = '<a title="nonaktifkan rate" href="ajk_reg_cost.php?r=view&er=tblrate&id='.$_REQUEST['id'].'&idp='.$_REQUEST['idp'].'&sett=nonaktifrate&status='.$mametrategroup_['status'].'&dttime='.$mametrategroup_['input_date'].'" onClick="if(confirm(\'Apakah anda yakin akan menonaktifkan keseluruhan rate ini ?\')){return true;}{return false;}"><img src="image/arrows_change_1.png" width="30"></a>';
			}else{
				$mametsplitrate = '<a title="aktifkan rate" href="ajk_reg_cost.php?r=view&er=tblrate&id='.$_REQUEST['id'].'&idp='.$_REQUEST['idp'].'&sett=aktifrate&status='.$mametrategroup_['status'].'&dttime='.$mametrategroup_['input_date'].'" onClick="if(confirm(\'Apakah anda yakin akan mengaktifkan keseluruhan rate ini ?\')){return true;}{return false;}"><img src="image/arrows_change.png" width="30"></a>';
			}
			echo '<table border="0" cellpadding="1" cellspacing="0" width="50%" align="center">
					  <tr><td><br />'.$mametsplitrate.'</td></tr>
					  <tr>
						  <th width="1%">No</th>
				  	  <th width="5%">Type</th>
				  	  <th width="1%">Usia</th>
				  	  <th width="5%">Tenor (thn)</th>
				  	  <th width="5%">Rate</th>
				  	  <th width="10%">User</th>
				  	  <th width="10%">Date</th>
				  	</tr>';
			
			
			$mametrate = $database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$metproduk['idcost'].'" AND id_polis="'.$metproduk['idproduk'].'" AND status="'.$mametrategroup_['status'].'" AND input_date="'.$mametrategroup_['input_date'].'" AND del IS NULL');
			while ($mametrate_ = mysql_fetch_array($mametrate)) {
				if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
				echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
					  <td align="center" width=1%>'.++$no.'</td>
				  	  <td align="center">'.$tipebenefit.'</td>
				  	  <td align="center">'.$mametrate_['usia'].'</td>
					  <td align="center">'.$mametrate_['tenor'].'</td>
				  	  <td align="right">'.$mametrate_['rate'].' &nbsp;</td>
				  	  <td align="center">'.$mametrate_['input_by'].'</td>
				  	  <td align="center">'.$mametrate_['input_date'].'</td>
				  	</tr>';
			}
		}
	}
	echo '</table>';
	}
	elseif ($_REQUEST['er']=="tblrefund"){	echo '<center>Data kosong</center>';	}
	elseif ($_REQUEST['er']=="tblklaim"){	echo '<center>Data kosong</center>';	}
	elseif ($_REQUEST['er']=="tblmedis"){
	$metproduk = mysql_fetch_array($database->doQuery('SELECT fu_ajk_costumer.id AS idcost, fu_ajk_costumer.`name`, fu_ajk_polis.id AS idproduk, fu_ajk_polis.nmproduk, fu_ajk_polis.singlerate, fu_ajk_polis.benefit
													   FROM fu_ajk_costumer
													   INNER JOIN fu_ajk_polis ON fu_ajk_costumer.id = fu_ajk_polis.id_cost
													   WHERE  fu_ajk_costumer.id="'.$_REQUEST['id'].'" AND fu_ajk_polis.id="'.$_REQUEST['idp'].'"'));
	echo '<table border="0" cellpadding="1" cellspacing="0" width="100%">
		  <tr><th><a href="ajk_reg_cost.php?r=view&id='.$_REQUEST['id'].'"><img src="../image/Backward-64.png" width="25"></a></th><th width="100%" align="left">Nama Produk '.$metproduk['nmproduk'].'</font></th></tr>
		  </table>';
	echo '<table border="0" cellpadding="1" cellspacing="0" width="60%" align="center">
		  <tr><th width="1%">No</th>
		  	  <th width="15%">Type Medical</th>
		  	  <th width="10%">Usia</th>
		  	  <th width="30%">Plafond</th>
		  	  <th width="10%">Status Data</th>
		  	  <th width="15%">User</th>
		  	  <th width="20%">Date</th>
		  </tr>';
	$mametmedical = $database->doQuery('SELECT * FROM fu_ajk_medical WHERE id_cost="'.$metproduk['idcost'].'" AND id_polis="'.$metproduk['idproduk'].'" AND del IS NULL');
	while ($mametmedical_ = mysql_fetch_array($mametmedical)) {
	if ($mametmedical_['type_medical']=="SPD" OR $mametmedical_['type_medical']=="FCL" OR $mametmedical_['type_medical']=="NM") {	$statusmedis = "<font color=blue>Aktif</font>";	}else{	$statusmedis = "<font color=red>Pending</font>";		}
	if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
	echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
			  <td align="center" width=1%>'.++$no.'</td>
			  <td align="center">'.$mametmedical_['type_medical'].'</td>
			  <td align="center">'.$mametmedical_['age_from'].' - '.$mametmedical_['age_to'].'</td>
			  <td align="right">'.duit($mametmedical_['si_from']).' - '.duit($mametmedical_['si_to']).' &nbsp;</td>
			  <td align="center">'.$statusmedis.'</td>
			  <td align="center">'.$mametmedical_['input_by'].'</td>
			  <td align="center">'.$mametmedical_['input_date'].'</td>
			  </tr>';
	}
	echo '</table>';
}elseif ($_REQUEST['er']=="preview_rmf"){
$metproduk = mysql_fetch_array($database->doQuery('SELECT fu_ajk_costumer.id AS idcost, fu_ajk_costumer.`name`, fu_ajk_polis.id AS idproduk, fu_ajk_polis.nmproduk, fu_ajk_polis.singlerate, fu_ajk_polis.benefit
												   FROM fu_ajk_costumer
												   INNER JOIN fu_ajk_polis ON fu_ajk_costumer.id = fu_ajk_polis.id_cost
												   WHERE  fu_ajk_costumer.id="'.$_REQUEST['id'].'" AND fu_ajk_polis.id="'.$_REQUEST['idp'].'"'));
echo '<table border="0" cellpadding="1" cellspacing="0" width="100%">
	  <tr><th><a href="ajk_reg_cost.php?r=view&id='.$_REQUEST['id'].'"><img src="../image/Backward-64.png" width="25"></a></th><th width="100%" align="left">Nama Produk '.$metproduk['nmproduk'].'</font></th></tr>
	  </table>';
echo '<table border="0" cellpadding="1" cellspacing="0" width="50%" align="center">
	  <tr><th width="1%">No</th>
	  	  <th width="10%">Tenor (bln)</th>
	  	  <th width="15%">Rate (RMF)</th>
	  	  <th width="30%">User</th>
	  	  <th width="20%">Date</th>
	  </tr>';
	$mametratermf = $database->doQuery('SELECT * FROM fu_ajk_ratepremi_rmf WHERE id_cost="'.$metproduk['idcost'].'" AND id_polis="'.$metproduk['idproduk'].'"');
while ($mametratermf_ = mysql_fetch_array($mametratermf)) {
if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	  <td align="center" width=1%>'.++$no.'</td>
  	  <td align="center">'.$mametratermf_['tenor'].'</td>
  	  <td align="center">'.$mametratermf_['rate'].' &nbsp;</td>
  	  <td align="center">'.$mametratermf_['input_by'].'</td>
  	  <td align="center">'.$mametratermf_['input_date'].'</td>
  	  </tr>';
}
	echo '</table>';
}
else{
echo '<table border="0" cellpadding="3" cellspacing="3" width="100%">
	  <tr><th colspan="17">Nama Produk</th></tr>
	  <tr><th width="1%">No</th>
	  	  <th width="10%">Nama Produk</th>
	  	  <th width="10%">No. Referensi</th>
	  	  <th width="10%">No. Kontrak</th>
	  	  <th width="8%">Tgl Kontrak</th>
	  	  <th width="5%">Tipe Polis</th>
	  	  <th width="15%">Tgl Polis</th>
	  	  <th width="5%">Admin</th>
	  	  <th width="5%">Brokrage</th>
	  	  <th width="5%">Discount</th>
	  	  <th width="1%">Ppn</th>
	  	  <th width="1%">Pph</th>
	  	  <th width="3%">Usia</th>
	  	  <th width="10%">Plafond</th>
	  	  <th width="1%">Status</th>
	  	  <th width="10%">Table Rate</th>
	  	  <th width="10%">Rate RMF</th>
	  </tr>';
$metprod = $database->doQuery('SELECT * FROM fu_ajk_polis WHERE id_cost="'.$met['id'].'" AND del IS NULL');
while ($metprod_ = mysql_fetch_array($metprod)) {
if ($metprod_['rmf'] != 0) {
	$cekratepremiRMF = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi_rmf WHERE id_cost="'.$metprod_['id_cost'].'" AND id_polis="'.$metprod_['id'].'"'));
	if ($cekratepremiRMF['id_cost']==$metprod_['id_cost'] AND $cekratepremiRMF['id_polis']==$metprod_['id']) {
	$kolomrmf = '<a title="Preview rate RMF" href="ajk_reg_cost.php?r=view&er=preview_rmf&id='.$metprod_['id_cost'].'&idp='.$metprod_['id'].'"><img src="image/rmf_2.png" width="30"></a>';
	}else{
	$kolomrmf = '<a title="Upload rate RMF" href="ajk_setrate.php?er=upload_rmf&id='.$metprod_['id_cost'].'&idp='.$metprod_['id'].'"><img src="image/rmf_1.png" width="30"></a>';
	}

}else{	$kolomrmf = '';	}

if ($metprod_['polis_type']=="openpolis") {	$tglpolisnya = _convertDate($metprod_['polis_start']);	}else{	$tglpolisnya = _convertDate($metprod_['polis_start']).' - '. _convertDate($metprod_['polis_end']);	}
if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	  <td align="center" width=1%>'.++$no.'</td>
	  <td><a href="ajk_reg_cost.php?r=view&er=pview&id='.$metprod_['id_cost'].'&idp='.$metprod_['id'].'">'.$metprod_['nmproduk'].'</a></td>
	  <td>'.$metprod_['noreferensi'].'</td>
	  <td>'.$metprod_['nokontrak'].'</td>
	  <td align="center">'._convertDate($metprod_['tglkontrak']).'</td>
	  <td align="center">'.$metprod_['polis_type'].'</td>
	  <td align="center">'.$tglpolisnya.'</td>
	  <td align="right">'.duit($metprod_['adminfee']).'</td>
	  <td align="center">'.$metprod_['brokrage'].'</td>
	  <td align="center">'.$metprod_['discount'].'</td>
	  <td align="center">'.$metprod_['ppn'].'</td>
	  <td align="center">'.$metprod_['pph23'].'</td>
	  <td align="center">'.$metprod_['age_min'].'-'.$metprod_['age_max'].'</td>
	  <td align="right">'.duit($metprod_['up_max']).'</td>
	  <td align="center">'.$metprod_['status'].'</td>
	  <td align="center"><a href="ajk_reg_cost.php?r=view&er=tblrate&id='.$metprod_['id_cost'].'&idp='.$metprod_['id'].'" title="table rate premi"><img src="../image/statistic.png" width="25"></a>
						 <a href="ajk_reg_cost.php?r=view&er=tblrefund&id='.$metprod_['id_cost'].'&idp='.$metprod_['id'].'" title="table formula refund"><img src="../image/money1.png" width="25"></a>
						 <a href="ajk_reg_cost.php?r=view&er=tblklaim&id='.$metprod_['id_cost'].'&idp='.$metprod_['id'].'" title="table formula klaim"><img src="../image/graph.png" width="25"></a>
						 <a href="ajk_reg_cost.php?r=view&er=tblmedis&id='.$metprod_['id_cost'].'&idp='.$metprod_['id'].'" title="table rate medical"><img src="../image/cekmedik.png" width="25"></a>
	  </td>
	  <td>'.$kolomrmf.'</td>
	  </tr>';
}
}
		;
		break;

	case "newcost":
echo '<table border="0" cellpadding="1" cellspacing="0" width="100%">
     <tr><th width="100%" align="left">Daftar Costumer - Tambah</font></th><th><a href="ajk_reg_cost.php"><img src="../image/Backward-64.png" width="25"></a></th></tr>
     </table><br />';
if ($_REQUEST['ope']=="Simpan") {
if (!$_REQUEST['nama'])  $error1 .='<blink><font color=red>Nama perusahaan tidak boleh kosong</font></blink><br>';
if (!$_REQUEST['wilayahnya'])  $error2 .='<blink><font color=red>Wilayah tidak boleh kosong</font></blink><br>';
if (!$_REQUEST['cost_alamat'])  $error3 .='<blink><font color=red>Alamat perusahaan tidak boleh kosong</font></blink><br>';
if (!$_REQUEST['phone'])  $error4 .='<blink><font color=red>Nomor Telephone tidak boleh kosong</font></blink><br>';
if (!$_REQUEST['emailnya'])  $error5 .='<blink><font color=red>Email tidak boleh kosong</font></blink><br>';
if ($_FILES['userfile']['size'] / 1024 > $met_ttdsize)	{	$error6 .="<blink><font color=red>File tidak boleh lebih dari 500Kb !</font></blink><br />";	}
else{
if (!$_FILES['userfile']['tmp_name'])  $error6 .='<blink><font color=red>Silahkan upload file logo bank.</font></blink><br>';
	$allowedExtensions = array("jpg","jpeg","gif", "png");
	foreach ($_FILES as $file) {
		if ($file['tmp_name'] > '') {
			if (!in_array(end(explode(".",	strtolower($file['name']))), $allowedExtensions)) {
				die('<center><font color=red>'.$file['name'].' <br /><blink>File extension tidak diperbolehkan selain image!</blink></font><br/>'.'<a href="ajk_reg_cost.php?r=newcost">'.'&lt;&lt Go Back</a></center>');
			}
		}
	}
}
			if ($error1 OR $error2 OR $error3 OR $error4 OR $error5 OR $error6)
			{	}
			else
			{
				$nama_file =  strtolower($_FILES['userfile']['name']);
				$file_type = $_FILES['userfile']['type']; //tipe file
				$source = $_FILES['userfile']['tmp_name'];
				$direktori = "$metpath_ttd/$nama_file"; // direktori tempat menyimpan file
				move_uploaded_file($source,$direktori);
				gambar_kecil($direktori,$file_type);

				$ceklvl = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY id DESC'));
				if ($ceklvl['id']=="") {	$x = 1;	}	else	{	$x = $polis['id'] + 1;	}
				$numb = 100000; $numb1 = substr($numb,1);	$futgl = date("ym");
				$idccostumer = "AJKP".$futgl.''.$numb1.''.$x;
				$met = $database->doQuery('INSERT INTO fu_ajk_costumer SET name="'.strtoupper($_REQUEST['nama']).'",
																idc="'.$idccostumer.'",
																address="'.$_REQUEST['cost_alamat'].'",
																wilayah="'.$_REQUEST['wilayahnya'].'",
																city="'.$_REQUEST['kota'].'",
																picphone="'.$_REQUEST['phone'].'",
																fax="'.$_REQUEST['faxnya'].'",
																email="'.$_REQUEST['emailnya'].'",
																postcode="'.$_REQUEST['kodepos'].'",
																logobank="'.$_FILES['userfile']['name'].'",
																pic="'.$_REQUEST['picnya'].'",
																input_by="'.$_SESSION['nm_user'].'",
																input_time="'.$datelog.'" ');
				echo '<script language="Javascript">window.location="ajk_reg_cost.php"</script>';
			}
		}
echo '<table border="0" width="50%" align="center"><tr><td>
		<form method="POST" action="" class="input-list style-1 smart-green" enctype="multipart/form-data">
		<h1>Penambahan Data Perusahaan</h1>
		<label><span>Nama Perusahaan <font color="red">*</font> '.$error1.'</span><input type="text" name="nama" value="'.$_REQUEST['nama'].'" size="30" placeholder="Nama Perusahaan"></label>
		<label><span>Wilayah <font color="red">*</font> '.$error2.'</span>
		  		<select size="1" name="wilayahnya">
		   			<option value="">-- Wilayah --</option>
		   			<option value="Pusat">Pusat</option>
		   			<option value="Region">Region</option>
		   			<option value="Cabang">Cabang</option>
		   			<option value="Cabang Pembantu">Cabang Pembantu</option>
		   			<option value="Cabang Unit">Cabang Unit</option>
		   			<option value="Lainnya">Lainnya</option>
		   			</select>
		 </label>
		 <label><span>Alamat <font color="red">*</font> '.$error3.'</span><textarea name="cost_alamat" cols="46" rows="2" placeholder="Alamat Perusahaan">'.$_REQUEST['cost_alamat'].'</textarea></label>
		 <label><span>Phone <font color="red">*</font> '.$error4.' </span><input type="text" name="phone" value="'.$_REQUEST['phone'].'" size="30" placeholder="Tlp / Hp" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"></label>
		 <label><span>Fax </span><input type="text" name="faxnya" value="'.$_REQUEST['faxnya'].'" size="30" placeholder="Fax" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"></label>
		 <label><span>Email <font color="red">*</font> '.$error5.'</span><input type="text" name="emailnya" value="'.$_REQUEST['emailnya'].'" size="30" placeholder="Email"></label>
		 <label><span>Kota</span><input type="text" name="kota" value="'.$_REQUEST['kota'].'" size="30" placeholder="Kota Perusahaan"></label>
		 <label><span>PIC</span><input type="text" name="picnya" value="'.$_REQUEST['picnya'].'" size="30" placeholder="PIC Bank"></label>
		 <label><span>Kodepos</span><input type="text" name="kodepos" value="'.$_REQUEST['kodepos'].'" size="30" placeholder="Kodepos" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"></label>
		 <label><span>Upload Logo Bank (max size 500Kb)<font color="red">*</font> '.$error6.'</span><input name="userfile" type="file" size="50" placeholder="Logo Bank" onchange="checkfile(this);"></label>';
/*
<label><span>P I C <font color="red">*</font> '.$error5.'</span>
		 	<select id="picnya" name="picnya">
		  	<option value="">-----Pilih PIC-----</option>';
		$msc = $database->doQuery('SELECT * FROM fu_ajk_agent WHERE del IS NULL ORDER BY name');
		while ($agent = mysql_fetch_array($msc)) {	echo '<option value="'.$agent['id'].'">'.$agent['name'].'</option>';	}
		echo '</select></label>
*/
echo '<label><span>&nbsp;</span><input type="submit" name="ope" value="Simpan" class="button" /></label>
		  </form></td></tr></table>';
			;
			break;

	default:
echo '<table border="0" cellpadding="1" cellspacing="0" width="100%">
     <tr><th width="100%" align="left">Data Costumer</font></th><th><a href="ajk_reg_cost.php?r=newcost"><img src="../image/new.png" width="25"></a></th></tr>
     </table>';
$met = $database->doQuery('SELECT * FROM fu_ajk_costumer WHERE del IS null ORDER BY name ASC');
echo '<table border="0" width="100%" cellpadding="5" cellspacing="1" bgcolor="#bde0e6">
		<tr><th width="2%">No</tth>
			<th width="25%">Costumer</th>
			<th>Alamat</th>
			<th width="10%">Kota</th>
			<th width="5%">Kodepos</th>
			<th width="5%">Wilayah</th>
			<th width="10%">PIC</th>
			<th width="10%">Phone</th>
			<th width="10%">Logo Tagihan</th>
			<th width="5%">Option</th>
		</tr>';
while ($r = mysql_fetch_array($met)) {
if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
$metagent = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_agent WHERE id="'.$r['pic'].'"'));
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	    <td align="center">'.++$no.'</td>
		<td><a href="ajk_reg_cost.php?r=view&id='.$r['id'].'">'.$r['name'].'</a></td>
		<td>'.$r['address'].'</td>
		<td>'.$r['city'].'</td>
		<td align="center">'.$r['postcode'].'</td>
		<td align="center">'.$r['wilayah'].'</td>
		<td align="center">'.$metagent['name'].'</td>
		<td align="center">'.$r['picphone'].'</td>
		<td align="center">'.$r['printlogo'].'</td>
		<td align="center">
			<!--<a title="Set Dokumen Meninggal" href="ajk_reg_cost.php?r=docmeninggal&id='.$r['id'].'"><img src="image/doc_death.png" border="0" width="25"></a> &nbsp;
			<a title="Import rate client" href="ajk_reg_cost_rate.php?id='.$r['id'].'"><img src="../image/editaja.png" border="0" width="25"></a> &nbsp;
			<a title="Import rate medical" href="ajk_reg_cost_rate.php?re=rate_medical&id='.$r['id'].'"><img src="../image/edit1.png" border="0" width="25"></a> &nbsp;
			<a title="buat data wilayah costumer" href="ajk_reg_cost.php?r=setwilayah&id='.$r['id'].'"><img src="../image/wilayah.png" border="0"></a> &nbsp;-->
			<a title="edit data costumer" href="ajk_reg_cost.php?r=edit&id='.$r['id'].'"><img src="image/edit3.png" border="0"></a>
			<a title="edit data logo" href="ajk_reg_cost.php?r=editlogo&id='.$r['id'].'"><img src="image/edit_image.png" width="20" border="0"></a></td>
	</tr>';
}
echo '</table>';
	;
} // switch

echo "<script type=\"text/javascript\" language=\"javascript\">
function checkfile(sender) {
	var validExts = new Array(\".jpeg\", \".jpg\", \".gif\", \".png\");
	var fileExt = sender.value;
	fileExt = fileExt.substring(fileExt.lastIndexOf('.'));
	if (validExts.indexOf(fileExt) < 0) {	alert(\"Invalid file selected, valid files are of \" + validExts.toString() + \" types.\");	return false;	}
	else return true;
}
</script>";
?>