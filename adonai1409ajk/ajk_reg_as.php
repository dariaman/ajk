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
case "deleterate":
/*
echo $_REQUEST['idb'].'<br />';
echo $_REQUEST['idp'].'<br />';
echo $_REQUEST['idas'].'<br />';
echo $_REQUEST['idpas'].'<br />';
*/
$met_ = $database->doQuery('UPDATE fu_ajk_ratepremi_as SET status="lama", eff_to="'.$futoday.'", update_by="'.$q['id'].'", update_date="'.$futgl.'", del = "1" WHERE id_cost="'.$_REQUEST['idb'].'" AND id_polis="'.$_REQUEST['idp'].'" AND id_as="'.$_REQUEST['idas'].'" AND id_polis_as="'.$_REQUEST['idpas'].'" AND del IS NULL');
header('location:ajk_reg_as.php?r=view&id='.$_REQUEST['idas'].'');
	;
	break;

	case "edit":
		echo '<table border="0" cellpadding="1" cellspacing="0" width="100%"><tr><th width="95%" align="left">Daftar Asuransi- Edit</font></th><th><a href="ajk_reg_as.php"><img src="../image/Backward-64.png" width="25"></a></th></tr></table><br />';
		if ($_REQUEST['ope']=="Simpan") {
			if (!$_REQUEST['nama'])  $error1 .='<blink><font color=red>Nama Asuransi tidak boleh kosong</font></blink><br>';
			if (!$_REQUEST['wilayahnya'])  $error2 .='<blink><font color=red>Wilayah tidak boleh kosong</font></blink><br>';
			if (!$_REQUEST['cost_alamat'])  $error3 .='<blink><font color=red>Alamat Asuransi tidak boleh kosong</font></blink><br>';
			if (!$_REQUEST['phone'])  $error4 .='<blink><font color=red>Nomor Telephone tidak boleh kosong</font></blink><br>';
			if (!$_REQUEST['picnya'])  $error5 .='<blink><font color=red>Silahkan pilih PIC</font></blink><br>';
			if ($error1 OR $error2 OR $error3 OR $error4 OR $error5)
			{	}
			else
			{
				$met = $database->doQuery('UPDATE fu_ajk_asuransi SET name="'.strtoupper($_REQUEST['nama']).'",
																address="'.$_REQUEST['cost_alamat'].'",
																wilayah="'.$_REQUEST['wilayahnya'].'",
																city="'.$_REQUEST['kota'].'",
																postcode="'.$_REQUEST['kodepos'].'",
																pic="'.$_REQUEST['picnya'].'",
																picphone="'.$_REQUEST['phone'].'",
																printlogo="'.$_REQUEST['eprintlogo'].'",
																update_by="'.$_SESSION['nm_user'].'",
																update_time="'.$datelog.'" WHERE id="'.$_REQUEST['id'].'"');
			echo '<script language="Javascript">window.location="ajk_reg_as.php"</script>';
			}
		}
		$client = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_asuransi WHERE id="'.$_REQUEST['id'].'"'));
		echo '<table border="0" width="50%" align="center"><tr><td>
				<form method="POST" action="" class="input-list style-1 smart-green">
				<h1>Edit Data Asuransi</h1>
				<label><span>Nama Asuransi <font color="red">*</font> '.$error1.'</span><input type="text" name="nama" value="'.$client['name'].'" size="30" placeholder="Nama Asuransi"></label>
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
				 <label><span>Alamat <font color="red">*</font> '.$error3.'</span><textarea name="cost_alamat" cols="46" rows="2" placeholder="Alamat Asuransi">'.$client['address'].'</textarea></label>
				 <label><span>Phone <font color="red">*</font> '.$error4.'</span><input type="text" name="phone" value="'.$client['picphone'].'" size="30" placeholder="Tlp / Hp"></label>
				 <label><span>Kota</span><input type="text" name="kota" value="'.$client['city'].'" size="30" placeholder="Kota Asuransi"></label>
				 <label><span>Kodepos</span><input type="text" name="kodepos" value="'.$client['postcode'].'" size="30" placeholder="Kodepos"></label>
				 <label><span>P I C <font color="red">*</font> '.$error5.'</span>
				 	<select id="picnya" name="picnya">
				  	<option value="">-----Pilih PIC-----</option>';
		$msc = $database->doQuery('SELECT * FROM fu_ajk_agent WHERE del IS NULL ORDER BY name');
		while ($agent = mysql_fetch_array($msc)) {	echo '<option value="'.$agent['id'].'"'._selected($client['pic'], $agent['id']).'>'.$agent['name'].'</option>';	}
		echo '</select></label>
		 <label><span>Tampilkan Logo</span>
		  		<select size="1" name="eprintlogo">
		   			<option value="">-- Logo --</option>
		   			<option value="Y"'._selected($client["printlogo"], "Y").'>Ya</option>
		   			<option value="T"'._selected($client["printlogo"], "T").'>Tidak</option>
		   			</select>
		 </label>
		 <label><span>&nbsp;</span><input type="submit" name="ope" value="Simpan" class="button" /></label>
				  </form></td></tr></table>';
		;
		break;

	case "view":
echo '<table border="0" cellpadding="1" cellspacing="0" width="100%">
     <tr><th width="100%" align="left">Daftar Asuransi- View</font></th><th><a href="ajk_reg_as.php"><img src="../image/Backward-64.png" width="25"></a></th></tr>
     </table>';
$met = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_asuransi WHERE id="'.$_REQUEST['id'].'"'));
if ($met['logo_asuransi']!="") {
	$logoAsuransi = '<img src="../ajk_file/_ttd/'.$met['logo_asuransi'].'" width="150">';
}else{
	$logoAsuransi = '<img src="../image/non-user.png" width="150" title="no logo">';
}
echo '<table border="0" cellpadding="1" cellspacing="1" width="100%">
	  <tr><td width="5%" rowspan="9" width="7%" valign="top" align="center">'.$logoAsuransi.'</td>
		  <td colspan="2"><b>'.$met['name'].'</b></td>
	  </tr>
	  <tr><td colspan="2">'.nl2br($met['address']).'<br />'.$met['city'].'<br />'.$met['postcode'].'</td>
	  </tr>
	  <tr><td width="10%">Telephone</td><td>: '.$met['picphone'].'</td></tr>
	  <tr><td>Fax</td><td>: '.$met['fax'].'</td></tr>
	  <tr><td>Email</td><td>: '.$met['email'].'</td></tr>
	  </table>';
if ($_REQUEST['rateAs']=="tblrate") {
$cekRateProduk = mysql_fetch_array($database->doQuery('SELECT id, nmproduk, age_deviasi, mpptype, id_cost FROM fu_ajk_polis WHERE id="'.$_REQUEST['idp'].'"'));
$rateAsCustomer = $database->doQuery('SELECT fu_ajk_polis.nmproduk,
											 fu_ajk_polis.age_deviasi,
											 fu_ajk_polis.mpptype,
											 fu_ajk_costumer.name AS customer,
											 fu_ajk_asuransi.`name`,
											 fu_ajk_polis_as.nopol,
											 fu_ajk_ratepremi_as.type,
											 fu_ajk_ratepremi_as.usia,
											 fu_ajk_ratepremi_as.tenor,
											 fu_ajk_ratepremi_as.mpp_s,
											 fu_ajk_ratepremi_as.mpp_e,
											 fu_ajk_ratepremi_as.rate,
											 fu_ajk_ratepremi_as.`status`
											 FROM fu_ajk_asuransi
											 INNER JOIN fu_ajk_polis_as ON fu_ajk_asuransi.id = fu_ajk_polis_as.id_as
											 INNER JOIN fu_ajk_polis ON fu_ajk_polis_as.nmproduk = fu_ajk_polis.id
											 INNER JOIN fu_ajk_costumer ON fu_ajk_polis_as.id_cost = fu_ajk_costumer.id
											 INNER JOIN fu_ajk_ratepremi_as ON fu_ajk_asuransi.id = fu_ajk_ratepremi_as.id_as AND fu_ajk_polis_as.id = fu_ajk_ratepremi_as.id_polis_as
											 WHERE fu_ajk_asuransi.id = "'.$_REQUEST['id'].'" AND fu_ajk_polis_as.id = "'.$_REQUEST['idpAs'].'" AND fu_ajk_ratepremi_as.`status`="baru" AND fu_ajk_ratepremi_as.del IS NULL ORDER BY fu_ajk_ratepremi_as.`status` ASC');
if ($cekRateProduk['age_deviasi']=="Y") {
$kolomusia = '<th width="1%">Usia (thn)</th></th>
			  <th width="10%">Tenor (thn)</th>';
}else{
$kolomusia = '<th width="10%">Tenor (bln)</th>';
}
if ($cekRateProduk['mpptype']=="Y") {
$kolommpp = '<th width="1%">MPP Awal (bln)</th></th>
			  <th width="10%">MPP Akhir (bln)</th>';
}else{
$kolommpp = '';
}
$metStatusRate = mysql_fetch_array($database->doQuery('SELECT status FROM fu_ajk_ratepremi_as
													   WHERE id_cost="'.$cekRateProduk['id_cost'].'" AND
													   		 id_polis="'.$cekRateProduk['id'].'" AND
													   		 id_as="'.$_REQUEST['id'].'" AND
													   		 id_polis_as="'.$_REQUEST['idpAs'].'"
													   	GROUP BY status'));
if ($metStatusRate['status']=="baru") {
	$statusratenya ='<a href="ajk_reg_as.php?r=deleterate&idb='.$cekRateProduk['id_cost'].'&idp='.$cekRateProduk['id'].'&idas='.$_REQUEST['id'].'&idpas='.$_REQUEST['idpAs'].'" AND status="baru" onClick="if(confirm(\'Apakah anda yakin akan menonaktifkan rate asuransi ini ?\')){return true;}{return false;}"><img src="image/delete.gif"></a>';
}else{
	$statusratenya ='';
}
echo '<table border="0" cellpadding="1" cellspacing="0" width="50%" align="center">
	  <!--<tr><td colspan="15" align="right"><a href="ajk_reg_as.php?r=delrate&idb="'.$_REQUEST['idpAs'].'"&idp="'.$_REQUEST['idpAs'].'"&ida="'.$_REQUEST['id'].'"&idp="'.$_REQUEST['idpAs'].'">Delete</a></td></tr>-->
	  <tr><td colspan="15" align="right">'.$statusratenya.'</td></tr>
	  <tr><th width="1%">No</th>
	  	  <th>Produk</th>
	  	  <th width="20%">Polis Asuransi</th>
	  	  '.$kolomusia.'
	  	  '.$kolommpp.'
		  <th width="1%">Rate</th>
	  	  <th width="1%">Status</th>
	  </tr>';
while ($rateAsCustomer_ = mysql_fetch_array($rateAsCustomer)) {
if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
if ($cekRateProduk['age_deviasi']=="Y") {
$kolomusia_ = '<td align="center">'.$rateAsCustomer_['usia'].'</td>
			   <td align="center">'.$rateAsCustomer_['tenor'].'</td>';
}else{
$kolomusia_ = '<td align="center">'.$rateAsCustomer_['tenor'].'</td>';
}
if ($cekRateProduk['mpptype']=="Y") {
$kolommpp_ = '<td align="center">'.$rateAsCustomer_['mpp_s'].'</td>
			   <td align="center">'.$rateAsCustomer_['mpp_e'].'</td>';
}else{
$kolommpp_ = '';
}
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	  <td align="center" width=1%>'.++$no.'</td>
	  <td align="center">'.$rateAsCustomer_['nmproduk'].'</td>
	  <td align="center">'.$rateAsCustomer_['nopol'].'</td>
	  '.$kolomusia_.'
	  '.$kolommpp_.'
	  <td align="center"><b>'.$rateAsCustomer_['rate'].'</b></td>
	  <td align="center">'.strtoupper($rateAsCustomer_['status']).'</td>
	  </tr>';
}
}
else{
$metPolAs = $database->doQuery('SELECT * FROM fu_ajk_polis_as WHERE id_as="'.$met['id'].'" AND del IS NULL ORDER BY id_cost ASC');
echo '<table border="0" cellpadding="1" cellspacing="1" width="100%">
	  <tr><th width="1%">No</th>
	  	  <th width="15%">Bank</th>
	  	  <th width="10%">Nama Produk</th>
	  	  <th width="8%">Tgl Kontrak</th>
	  	  <th width="1%">Tipe Polis</th>
	  	  <th width="10%">Tgl Polis</th>
	  	  <th width="1%">Biaya Admin</th>
	  	  <th width="1%">Brokrage</th>
	  	  <th width="1%">Discount</th>
	  	  <th width="1%">Ppn</th>
	  	  <th width="1%">Pph</th>
	  	  <th width="1%">Usia</th>
	  	  <th width="1%">Status Polis</th>
	  	  <th width="1%">Table Rate</th>
	  </tr>';
	while ($metPolAs_ = mysql_fetch_array($metPolAs)) {
	$metAsProd = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$metPolAs_['nmproduk'].'"'));
	$metAsClient = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$metPolAs_['id_cost'].'"'));
	if ($metPolAs_['polis_type']=="openpolis") {	$tglpolisnya = _convertDate($metPolAs_['polis_start']);	}else{	$tglpolisnya = _convertDate($metPolAs_['polis_start']).' - '. _convertDate($metPolAs_['polis_end']);	}
	if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
	echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		  <td align="center" width=1%>'.++$no.'</td>
		  <td>'.$metAsClient['name'].'</td>
		  <td><b>'.$metAsProd['nmproduk'].'</b></td>
		  <td align="center">'._convertDate($metPolAs_['tglkontrak']).'</td>
		  <td align="center">'.$metPolAs_['polis_type'].'</td>
		  <td align="center">'.$tglpolisnya.'</td>
		  <td align="right">'.duit($metPolAs_['adminfee']).'</td>
		  <td align="center">'.$metPolAs_['brokrage'].'</td>
		  <td align="center">'.$metPolAs_['discount'].'</td>
		  <td align="center">'.$metPolAs_['ppn'].'</td>
		  <td align="center">'.$metPolAs_['pph23'].'</td>
		  <td align="center">'.$metPolAs_['age_min'].'-'.$metPolAs_['age_max'].'</td>
		  <td align="center">'.$metPolAs_['status'].'</td>
		  <td align="center"><a href="ajk_reg_as.php?r=view&rateAs=tblrate&id='.$_REQUEST['id'].'&idpAs='.$metPolAs_['id'].'&idp='.$metAsProd['id'].'" title="table rate premi"><img src="../image/statistic.png" width="25"></a></td></tr>
		  </td>
		  </tr>';
	}
}
		;
		break;

	case "newcost":
echo '<table border="0" cellpadding="1" cellspacing="0" width="100%">
     <tr><th width="100%" align="left">Daftar Asuransi- Tambah</font></th><th><a href="ajk_reg_as.php"><img src="../image/Backward-64.png" width="25"></a></th></tr>
     </table><br />';
if ($_REQUEST['ope']=="Simpan") {
if (!$_REQUEST['nama'])  $error1 .='<blink><font color=red>Nama Asuransi tidak boleh kosong</font></blink><br>';
if (!$_REQUEST['wilayahnya'])  $error2 .='<blink><font color=red>Wilayah tidak boleh kosong</font></blink><br>';
if (!$_REQUEST['cost_alamat'])  $error3 .='<blink><font color=red>Alamat Asuransi tidak boleh kosong</font></blink><br>';
if (!$_REQUEST['phone'])  $error4 .='<blink><font color=red>Nomor Telephone tidak boleh kosong</font></blink><br>';
if (!$_REQUEST['emailnya'])  $error5 .='<blink><font color=red>Email tidak boleh kosong</font></blink><br>';
if ($_FILES['userfile']['size'] / 1024 > $met_ttdsize)	{	$error6 .="<blink><font color=red>File tidak boleh lebih dari 500Kb !</font></blink><br />";	}
else{
if (!$_FILES['userfile']['tmp_name'])  $error6 .='<blink><font color=red>Silahkan upload file logo asuransi.</font></blink><br>';
	$allowedExtensions = array("jpg","jpeg","gif", "png");
	foreach ($_FILES as $file) {
		if ($file['tmp_name'] > '') {
			if (!in_array(end(explode(".",	strtolower($file['name']))), $allowedExtensions)) {
				die('<center><font color=red>'.$file['name'].' <br /><blink>File extension tidak diperbolehkan selain image!</blink></font><br/>'.'<a href="ajk_reg_as.php?r=newcost">'.'&lt;&lt Go Back</a></center>');
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

	$ceklvl = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_asuransi ORDER BY id DESC'));
	if ($ceklvl['id']=="") {	$x = 1;	}	else	{	$x = $polis['id'] + 1;	}
	$numb = 100000; $numb1 = substr($numb,1);	$futgl = date("ym");
	$idcAsuransi= "AJK-AS".$futgl.''.$numb1.''.$x;
	$met = $database->doQuery('INSERT INTO fu_ajk_asuransi SET name="'.strtoupper($_REQUEST['nama']).'",
																idc="'.$idccostumer.'",
																address="'.$_REQUEST['cost_alamat'].'",
																wilayah="'.$_REQUEST['wilayahnya'].'",
																city="'.$_REQUEST['kota'].'",
																postcode="'.$_REQUEST['kodepos'].'",
																fax="'.$_REQUEST['faxnya'].'",
																email="'.$_REQUEST['emailnya'].'",
																logo_asuransi="'.$_FILES['userfile']['name'].'",
																pic="'.$_REQUEST['picnya'].'",
																picphone="'.$_REQUEST['phone'].'",
																input_by="'.$_SESSION['nm_user'].'",
																input_time="'.$datelog.'" ');
				echo '<script language="Javascript">window.location="ajk_reg_as.php"</script>';
			}
		}
		echo '<table border="0" width="50%" align="center"><tr><td>
		<form method="POST" action="" class="input-list style-1 smart-green" enctype="multipart/form-data">
		<h1>Penambahan Data Asuransi</h1>
		<label><span>Nama Asuransi <font color="red">*</font> '.$error1.'</span><input type="text" name="nama" value="'.$_REQUEST['nama'].'" size="30" placeholder="Nama Asuransi"></label>
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
		 <label><span>Alamat <font color="red">*</font> '.$error3.'</span><textarea name="cost_alamat" cols="46" rows="2" placeholder="Alamat Asuransi">'.$_REQUEST['cost_alamat'].'</textarea></label>
		 <label><span>Phone <font color="red">*</font> '.$error4.'</span><input type="text" name="phone" value="'.$_REQUEST['phone'].'" size="30" placeholder="Tlp / Hp"></label>
		 <label><span>Fax </span><input type="text" name="faxnya" value="'.$_REQUEST['faxnya'].'" size="30" placeholder="Fax" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"></label>
		 <label><span>Email <font color="red">*</font> '.$error5.'</span><input type="text" name="emailnya" value="'.$_REQUEST['emailnya'].'" size="30" placeholder="Email"></label>
		 <label><span>Kota</span><input type="text" name="kota" value="'.$_REQUEST['kota'].'" size="30" placeholder="Kota Asuransi"></label>
		 <label><span>Kodepos</span><input type="text" name="kodepos" value="'.$_REQUEST['kodepos'].'" size="30" placeholder="Kodepos"></label>
		 <label><span>P I C</span>
		 	<select id="picnya" name="picnya">
		  	<option value="">-----Pilih PIC-----</option>';
		$msc = $database->doQuery('SELECT * FROM fu_ajk_agent WHERE del IS NULL ORDER BY name');
		while ($agent = mysql_fetch_array($msc)) {	echo '<option value="'.$agent['id'].'">'.$agent['name'].'</option>';	}
echo '</select></label>
		  <label><span>Upload Logo Asuransi (max size 500Kb)<font color="red">*</font> '.$error6.'</span><input name="userfile" type="file" size="50" placeholder="Logo Asuransi" onchange="checkfile(this);"></label>
		  <label><span>&nbsp;</span><input type="submit" name="ope" value="Simpan" class="button" /></label>
		  </form></td></tr></table>';
		;
		break;

	case "editlogo":
echo '<table border="0" cellpadding="1" cellspacing="0" width="100%"><tr><th width="95%" align="left">Daftar Costumer - Edit Logo</font></th><th><a href="ajk_reg_as.php"><img src="../image/Backward-64.png" width="25"></a></th></tr></table><br />';
$client = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_asuransi WHERE id="'.$_REQUEST['id'].'"'));
if ($_REQUEST['ope']=="Simpan") {
if ($_FILES['userfile']['size'] / 1024 > $met_ttdsize)	{	$error6 .="<blink><font color=red>File tidak boleh lebih dari 500Kb !</font></blink><br />";	}
else{
if (!$_FILES['userfile']['tmp_name'])  $error6 .='<blink><font color=red>Silahkan upload file logo bank.</font></blink><br>';
	$allowedExtensions = array("jpg","jpeg","gif", "png");
	foreach ($_FILES as $file) {
		if ($file['tmp_name'] > '') {
			if (!in_array(end(explode(".",	strtolower($file['name']))), $allowedExtensions)) {
				die('<center><font color=red>'.$file['name'].' <br /><blink>File extension tidak diperbolehkan selain image!</blink></font><br/>'.'<a href="ajk_reg_as.php?r=editlogo&id='.$_REQUEST['id'].'">'.'&lt;&lt Go Back</a></center>');
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
		$met = $database->doQuery('UPDATE fu_ajk_asuransi SET logo_asuransi="'.$nama_file.'",
																	  update_by="'.$_SESSION['nm_user'].'",
																	  update_time="'.$datelog.'" WHERE id="'.$_REQUEST['id'].'"');
		echo '<script language="Javascript">window.location="ajk_reg_as.php"</script>';
	}
}
echo '<table border="0" width="50%" align="center"><tr><td>
				<form method="POST" action="" class="input-list style-1 smart-green" enctype="multipart/form-data">
				<h1>Penambahan Data Perusahaan</h1>
				<label><span>Nama Asuransi </span><input type="text" name="nama" value="'.$client['name'].'" size="30" placeholder="Nama Perusahaan" disabled></label>
				<label><span>Upload Logo Asuransi (max size 500Kb)<font color="red">*</font> '.$error6.'</span>
							<input name="userfile" type="file" size="50" placeholder="Logo Bank" onchange="checkfile(this);"><img src="../ajk_file/_ttd/'.$client['logo_asuransi'].'"></label>';
echo '<label><span>&nbsp;</span><input type="submit" name="ope" value="Simpan" class="button" /></label>
				  </form></td></tr></table>';
		;
		break;

	default:
echo '<table border="0" cellpadding="1" cellspacing="0" width="100%">
     <tr><th width="100%" align="left">Data Costumer</font></th><th><a href="ajk_reg_as.php?r=newcost"><img src="../image/new.png" width="25"></a></th></tr>
     </table>';
$met = $database->doQuery('SELECT * FROM fu_ajk_asuransi WHERE del IS null ORDER BY name ASC');
echo '<table border="0" width="100%" cellpadding="5" cellspacing="1" bgcolor="#bde0e6">
		<tr><th width="2%">No</tth>
			<th width="25%">Costumer</th>
			<th>Alamat</th>
			<th width="10%">Kota</th>
			<th width="5%">Kodepos</th>
			<th width="5%">Wilayah</th>
			<th width="10%">PIC</th>
			<th width="10%">Phone</th>
			<th width="5%">Option</th>
		</tr>';
while ($r = mysql_fetch_array($met)) {
if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
$metagent = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_agent WHERE id="'.$r['pic'].'"'));
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	    <td align="center">'.++$no.'</td>
		<td><a href="ajk_reg_as.php?r=view&id='.$r['id'].'">'.$r['name'].'</a></td>
		<td>'.$r['address'].'</td>
		<td>'.$r['city'].'</td>
		<td align="center">'.$r['postcode'].'</td>
		<td align="center">'.$r['wilayah'].'</td>
		<td align="center">'.$metagent['name'].'</td>
		<td align="center">'.$r['picphone'].'</td>
		<td align="center">
		<!--<a title="Set Dokumen Meninggal" href="ajk_reg_as.php?r=docmeninggal&id='.$r['id'].'"><img src="image/doc_death.png" border="0" width="25"></a> &nbsp;
			<a title="Import rate client" href="ajk_reg_as_rate.php?id='.$r['id'].'"><img src="../image/editaja.png" border="0" width="25"></a> &nbsp;
			<a title="Import rate medical" href="ajk_reg_as_rate.php?re=rate_medical&id='.$r['id'].'"><img src="../image/edit1.png" border="0" width="25"></a> &nbsp;
			<a title="buat data wilayah costumer" href="ajk_reg_as.php?r=setwilayah&id='.$r['id'].'"><img src="../image/wilayah.png" border="0"></a> &nbsp;-->
			<a title="edit data costumer" href="ajk_reg_as.php?r=edit&id='.$r['id'].'"><img src="image/edit3.png" border="0"></a>
			<a title="edit data logo" href="ajk_reg_as.php?r=editlogo&id='.$r['id'].'"><img src="image/edit_image.png" width="20" border="0"></a></td>
	</tr>';
}
echo '</table>';
	;
} // switch

?>