<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("../includes/functions.php");
connect();
if (isset($_SESSION['nm_user'])) {
    $q = mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="' . $_SESSION['nm_user'] . '"'));
}
switch ($_REQUEST['sc']) {
	case "closeED":
		$closePassEd = $database->doQuery('UPDATE fu_ajk_passedit SET status="close" WHERE today="'.$_REQUEST['dt'].'" AND status="'.$_REQUEST['st'].'"');
		header("location:scase.php");
			;
	break;

	case "eSPK":
		echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
			<tr><th width="100%" align="left">Edit Data SPK</font></th>
				<th><a href="scase.php"><img src="../image/Backward-64.png" width="20" border="0"></a></th>
			</tr></table>';
		echo '<table border="0" width="100%" cellpadding="1" cellspacing="1">
			<form method="post" action="">
			<tr><td width="10%">Nomor SPK :</td><td><input type="text" name="scEspk" value="' . $_REQUEST['scEspk'] . '"> <input type="submit" name="button" value="Cari" class="button"></td></tr>
			</form></table>';
						if ($_REQUEST['button'] == "Cari") {
								$_eSPK = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak WHERE spak="' . $_REQUEST['scEspk'] . '" AND del IS NULL'));
								$_eSPK_form = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_form WHERE idspk ="' . $_eSPK['id'] . '" AND del IS NULL'));
								if ($_eSPK['spak'] == "") {
										echo '<center><font color=red>Nomor SPK tidak ada.</font></center>';
								} else {
						$metcost = $database->doQuery('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$_eSPK['id_cost'].'" ORDER BY name ASC');
						$metprod = $database->doQuery('SELECT id, nmproduk FROM fu_ajk_polis WHERE id_cost="'.$_eSPK['id_cost'].'" AND grupproduk !="" AND del IS NULL ORDER BY nmproduk ASC');
		echo '<form method="POST" action="" class="input-list style-1 smart-green">
			<input type="hidden" name="idspk" value="' . $_eSPK['id'] . '">
			<table border="0" width="75%" align="center"><tr><td>
			<h1>Nomor SPK ' . $_REQUEST['scEspk'] . '</h1>
			<label><span>Nama Perusahaan</span><select name="id_cost" id="id_cost">
					<!--<option value="">Pilih Perusahaan</option>-->';
										while ($ccost = mysql_fetch_array($metcost)) {
												echo '<option value="' . $ccost['id'] . '"' . _selected($_eSPK['id_cost'], $ccost['id']) . ' disabled>' . $ccost['name'] . '</option>';
										}
										echo '</select>
			</label>
			<label><span>Nama Produk</span><select name="idprod" id="idprod">
				<option value="">Pilih Produk</option>';
										while ($cprod = mysql_fetch_array($metprod)) {
												echo '<option value="' . $cprod['id'] . '"' . _selected($_eSPK['id_polis'], $cprod['id']) . '>' . $cprod['nmproduk'] . '</option>';
										}
										echo '</select>
			</label>
			<label><span>Nomor SPK <font color="red">*</font> ' . $error1 . '</span><input type="text" name="_nomorSPK" value="' . $_eSPK['spak'] . '" size="30" placeholder="Nomor SPK"></label>
			<label><span>Extra Premi</span><input type="text" name="_nomorSPK" value="' . $_eSPK['ext_premi'] . '%" size="30" placeholder="Extra Premi" Disabled></label>
			<label><span>Status</span><select size="1" name="rstatus">
				<option value="Aktif"' . _selected($_eSPK["status"], "Aktif") . '>Aktif</option>
				<option value="Approve"' . _selected($_eSPK["status"], "Approve") . '>Approve</option>
				<option value="Batal"' . _selected($_eSPK["status"], "Batal") . '>Batal</option>
				<option value="Pending"' . _selected($_eSPK["status"], "Pending") . '>Pending</option>
				<option value="Preapproval"' . _selected($_eSPK["status"], "Preapproval") . '>Preapproval</option>
				<option value="Proses"' . _selected($_eSPK["status"], "Proses") . '>Proses</option>
				<option value="Realisasi"' . _selected($_eSPK["status"], "Realisasi") . '>Realisasi</option>
				<option value="Tolak"' . _selected($_eSPK["status"], "Tolak") . '>Tolak</option>
				<option value="Kadaluarsa"' . _selected($_eSPK["status"], "Kadaluarsa") . '>Kadaluarsa</option>
			</select></label>
			<label><span>Tanggal Input SPK <font color="red">*</font></span><input type="text" name="tglInputSPK" id="tglInputSPK" class="tanggal" value="'.$_eSPK['input_date'].'" size="10"/></label>
			<a href="scase.php?sc=uplphoto&i='.$_eSPK['id'].'">Ganti Foto</a>
			<label><span>&nbsp;</span><input type="hidden" name="sc" value="SimpanSPK" class="button" /><input type="submit" name="ope" value="Simpan" class="button" /></label>
		</form></td></tr></table>';
					}
			}
				;
	break;
	
	case "uplphoto":
			$metpath = "../../ajkmobilescript/uploads/";
			//$metpath = "ajk_file/_spak/";	
			$datelog = date("Y-m-d h:i:s");
			$tempDeb = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_form WHERE idspk="'.$_REQUEST['i'].'"'));
			echo '<table border="0" cellpadding="5" cellspacing="0" width="100%"><tr><th width="95%" align="left">Modul Upload Photo Debitur Tablet</font></th></tr></table>';

			if ($_REQUEST['el']=="_photo") {		
				if ($_FILES['filefotodebitursatu']['name'] !=""){
					$info = pathinfo($_FILES['filefotodebitursatu']['name']);		
					$extension = strtolower($info["extension"]); //image extension
					$name_only = strtolower($info["filename"]);//file name only, no extension	
					$photomet = $datelog.'_'.md5($name_only).'.'.$extension;

					if ($_FILES['filefotodebitursatu']['type'] !="image/jpeg" AND $_FILES['filefotodebitursatu']['type'] !="image/JPG" AND $_FILES['filefotodebitursatu']['type'] !="image/jpg" AND $_FILES['filefotodebitursatu']['type'] !="image/png")	{	
						$errno1 ="File photo harus Format JPG!<br />";	
					}elseif ($_FILES['filefotodebitursatu']['size'] / 1024 > $met_spaksize)	{	
						$errno1 ="File tidak boleh lebih dari 2Mb !<br />";	
					}elseif(file_exists($metpath.'/'.$photomet)){
						$errno1 = '<div align="center"><font color="red">Nama file photo debitur sudah ada, photo tidak bisa diupload !</div>';
					}else{				
						$update1 = "filefotodebitursatu = 'uploads/".$photomet."'";
						$tmpfile = $_FILES['filefotodebitursatu']['tmp_name'];
						$filetmp = $metpath . $photomet;
						move_uploaded_file($tmpfile,$filetmp);

						// HISTORY UPDATE
						//$berkas = fopen("logquery.txt", "a") or die ("File history tidak ada.");
						//fwrite($berkas, "Date Update : ".date("Y-m-d G:i:s")." By : ".$_SESSION['nm_user']." Title : Update filefotodebitursatu idspk ".$tempDeb['idspk']. " dari ".$tempDeb['filefotodebitursatu']." menjadi ".$filetmp."\r\n");
						//fclose($berkas);			

					}
				}

				if ($_FILES['filefotodebiturdua']['name'] !=""){
					$info = pathinfo($_FILES['filefotodebiturdua']['name']);		
					$extension = strtolower($info["extension"]); //image extension
					$name_only = strtolower($info["filename"]);//file name only, no extension	
					$photomet = $datelog.'_'.md5($name_only).'.'.$extension;

					if ($_FILES['filefotodebiturdua']['type'] !="image/jpeg" AND $_FILES['filefotodebiturdua']['type'] !="image/JPG" AND $_FILES['filefotodebiturdua']['type'] !="image/jpg" AND $_FILES['filefotodebiturdua']['type'] !="image/png")	{	
						$errno2 ="File photo harus Format JPG!<br />";	
					}elseif ($_FILES['filefotodebiturdua']['size'] / 1024 > $met_spaksize)	{	
						$errno2 ="File tidak boleh lebih dari 2Mb !<br />";	
					}elseif(file_exists($metpath.'/'.$photomet)){
						$errno2 = '<div align="center"><font color="red">Nama file photo debitur 2 sudah ada, photo tidak bisa diupload !</div>';
					}else{				
						if($update1 != ""){
							$update2 = ",filefotodebiturdua = 'uploads/".$photomet."'";
						}else{
							$update2 = "filefotodebiturdua = 'uploads/".$photomet."'";
						}
						
						$tmpfile = $_FILES['filefotodebiturdua']['tmp_name'];
						$filetmp = $metpath . $photomet;
						move_uploaded_file($tmpfile,$filetmp);
						// HISTORY UPDATE
						//$berkas = fopen("logquery.txt", "a") or die ("File history tidak ada.");
						//fwrite($berkas, "Date Update : ".date("Y-m-d G:i:s")." By : ".$_SESSION['nm_user']." Title : Update filefotodebiturdua idspk ".$tempDeb['idspk']. " dari ".$tempDeb['filefotodebiturdua']." menjadi ".$filetmp."\r\n");
						//fclose($berkas);			

					}
				}

				if ($_FILES['filefotoktp']['name'] !=""){
					$info = pathinfo($_FILES['filefotoktp']['name']);		
					$extension = strtolower($info["extension"]); //image extension
					$name_only = strtolower($info["filename"]);//file name only, no extension	
					$photomet = $datelog.'_'.md5($name_only).'.'.$extension;

					if ($_FILES['filefotoktp']['type'] !="image/jpeg" AND $_FILES['filefotoktp']['type'] !="image/JPG" AND $_FILES['filefotoktp']['type'] !="image/jpg" AND $_FILES['filefotoktp']['type'] !="image/png")	{	
						$errno3 ="File photo harus Format JPG!<br />";	
					}elseif ($_FILES['filefotoktp']['size'] / 1024 > $met_spaksize)	{	
						$errno3 ="File tidak boleh lebih dari 2Mb !<br />";	
					}elseif(file_exists($metpath.'/'.$photomet)){
						$errno3 = '<div align="center"><font color="red">Nama file photo ktp sudah ada, photo tidak bisa diupload !</div>';
					}else{				
						if($update1 != "" OR $update2 != ""){
							$update3 = ",filefotoktp = 'uploads/".$photomet."'";
						}else{
							$update3 = "filefotoktp = 'uploads/".$photomet."'";
						}
						
						$tmpfile = $_FILES['filefotoktp']['tmp_name'];
						$filetmp = $metpath . $photomet;
						move_uploaded_file($tmpfile,$filetmp);
						// HISTORY UPDATE
						//$berkas = fopen("logquery.txt", "a") or die ("File history tidak ada.");
						//fwrite($berkas, "Date Update : ".date("Y-m-d G:i:s")." By : ".$_SESSION['nm_user']." Title : Update filefotoktp idspk ".$tempDeb['idspk']. " dari ".$tempDeb['filefotoktp']." menjadi ".$filetmp."\r\n");
						//fclose($berkas);					
					}
				}

				if ($_FILES['filettddebitur']['name'] !=""){
					$info = pathinfo($_FILES['filettddebitur']['name']);		
					$extension = strtolower($info["extension"]); //image extension
					$name_only = strtolower($info["filename"]);//file name only, no extension	
					$photomet = $datelog.'_'.md5($name_only).'.'.$extension;			
					if ($_FILES['filettddebitur']['type'] !="image/jpeg" AND $_FILES['filettddebitur']['type'] !="image/JPG" AND $_FILES['filettddebitur']['type'] !="image/jpg" AND $_FILES['filettddebitur']['type'] !="image/png")	{	
						$errno4 ="File photo harus Format JPG!<br />";	
					}elseif ($_FILES['filettddebitur']['size'] / 1024 > $met_spaksize)	{	
						$errno4 ="File tidak boleh lebih dari 2Mb !<br />";	
					}elseif(file_exists($metpath.'/'.$photomet)){
						$errno4 = '<div align="center"><font color="red">Nama file photo ttd debitur sudah ada, photo tidak bisa diupload !</div>';
					}else{				
						if($update1 != "" OR $update2 != "" OR $update3 != ""){
							$update4 = ",filettddebitur = 'uploads/".$photomet."'";	
						}else{
							$update4 = "filettddebitur = 'uploads/".$photomet."'";
						}
						
						$tmpfile = $_FILES['filettddebitur']['tmp_name'];
						$filetmp = $metpath . $photomet;
						move_uploaded_file($tmpfile,$filetmp);
						// HISTORY UPDATE
						//$berkas = fopen("logquery.txt", "a") or die ("File history tidak ada.");
						//fwrite($berkas, "Date Update : ".date("Y-m-d G:i:s")." By : ".$_SESSION['nm_user']." Title : Update filettddebitur idspk ".$tempDeb['idspk']. " dari ".$tempDeb['filettddebitur']." menjadi ".$filetmp."\r\n");
						//fclose($berkas);				
					}
				}			
				
				if ($_FILES['filettdmarketing']['name'] !=""){
					$info = pathinfo($_FILES['filettdmarketing']['name']);		
					$extension = strtolower($info["extension"]); //image extension
					$name_only = strtolower($info["filename"]);//file name only, no extension	
					$photomet = $datelog.'_'.md5($name_only).'.'.$extension;				
					if ($_FILES['filettdmarketing']['type'] !="image/jpeg" AND $_FILES['filettdmarketing']['type'] !="image/JPG" AND $_FILES['filettdmarketing']['type'] !="image/jpg" AND $_FILES['filettdmarketing']['type'] !="image/png")	{	
						$errno5 ="File photo harus Format JPG!<br />";	
					}elseif ($_FILES['filettdmarketing']['size'] / 1024 > $met_spaksize)	{	
						$errno5 ="File tidak boleh lebih dari 2Mb !<br />";	
					}elseif(file_exists($metpath.'/'.$photomet)){
						$errno5 = '<div align="center"><font color="red">Nama file photo ttd Marketing sudah ada, photo tidak bisa diupload !</div>';
					}else{				
						if($update1 != "" OR $update2 != "" OR $update3 != "" OR $update4 != ""){
							$update5 = ",filettdmarketing = 'uploads/".$photomet."'";
						}else{
							$update5 = "filettdmarketing = 'uploads/".$photomet."'";
						}
						
						$tmpfile = $_FILES['filettdmarketing']['tmp_name'];
						$filetmp = $metpath . $photomet;
						move_uploaded_file($tmpfile,$filetmp);
						// HISTORY UPDATE
						//$berkas = fopen("logquery.txt", "a") or die ("File history tidak ada.");
						//fwrite($berkas, "Date Update : ".date("Y-m-d G:i:s")." By : ".$_SESSION['nm_user']." Title : Update filettdmarketing idspk ".$tempDeb['idspk']. " dari ".$tempDeb['filettdmarketing']." menjadi ".$filetmp."\r\n");
						//fclose($berkas);				
					}
				}

				if ($_FILES['filettddokter']['name'] !=""){
					$info = pathinfo($_FILES['filettddokter']['name']);		
					$extension = strtolower($info["extension"]); //image extension
					$name_only = strtolower($info["filename"]);//file name only, no extension	
					$photomet = $datelog.'_'.md5($name_only).'.'.$extension;			
					if ($_FILES['filettddokter']['type'] !="image/jpeg" AND $_FILES['filettddokter']['type'] !="image/JPG" AND $_FILES['filettddokter']['type'] !="image/jpg" AND $_FILES['filettddokter']['type'] !="image/png")	{	
						$errno6 ="File photo harus Format JPG!<br />";	
					}elseif ($_FILES['filettddokter']['size'] / 1024 > $met_spaksize)	{	
						$errno6 ="File tidak boleh lebih dari 2Mb !<br />";	
					}elseif(file_exists($metpath.'/'.$photomet)){
						$errno6 = '<div align="center"><font color="red">Nama file photo ttd Dokter sudah ada, photo tidak bisa diupload !</div>';
					}else{				
						if($update1 != "" OR $update2 != "" OR $update3 != "" OR $update4 != "" OR $update5 != ""){
							$update6 = ",filettddokter = 'uploads/".$photomet."'";	
						}else{
							$update6 = "filettddokter = 'uploads/".$photomet."'";
						}
						
						$tmpfile = $_FILES['filettddokter']['tmp_name'];
						$filetmp = $metpath . $photomet;
						move_uploaded_file($tmpfile,$filetmp);
						// HISTORY UPDATE
						//$berkas = fopen("logquery.txt", "a") or die ("File history tidak ada.");
						//fwrite($berkas, "Date Update : ".date("Y-m-d G:i:s")." By : ".$_SESSION['nm_user']." Title : Update filettddokter idspk ".$tempDeb['idspk']. " dari ".$tempDeb['filettddokter']." menjadi ".$filetmp."\r\n");
						//fclose($berkas);					
					}
				}

				if ($_FILES['filefotoskpensiun']['name'] !=""){
					$info = pathinfo($_FILES['filefotoskpensiun']['name']);		
					$extension = strtolower($info["extension"]); //image extension
					$name_only = strtolower($info["filename"]);//file name only, no extension	
					$photomet = $datelog.'_'.md5($name_only).'.'.$extension;				
					if ($_FILES['filefotoskpensiun']['type'] !="image/jpeg" AND $_FILES['filefotoskpensiun']['type'] !="image/JPG" AND $_FILES['filefotoskpensiun']['type'] !="image/jpg" AND $_FILES['filefotoskpensiun']['type'] !="image/png")	{	
						$errno7 ="File photo harus Format JPG!<br />";	
					}elseif ($_FILES['filefotoskpensiun']['size'] / 1024 > $met_spaksize)	{	
						$errno7 ="File tidak boleh lebih dari 2Mb !<br />";	
					}elseif(file_exists($metpath.'/'.$photomet)){
						$errno7 = '<div align="center"><font color="red">Nama file photo sk pensiun sudah ada, photo tidak bisa diupload !</div>';
					}else{				
						if($update1 != "" OR $update2 != "" OR $update3 != "" OR $update4 != "" OR $update5 != "" OR $update6 != ""){
							$update7 = ",filefotoskpensiun = 'uploads/".$photomet."'";	
						}else{
							$update7 = "filefotoskpensiun = 'uploads/".$photomet."'";
						}
						
						$tmpfile = $_FILES['filefotoskpensiun']['tmp_name'];
						$filetmp = $metpath . $photomet;
						move_uploaded_file($tmpfile,$filetmp);
						// HISTORY UPDATE
						//$berkas = fopen("logquery.txt", "a") or die ("File history tidak ada.");
						//fwrite($berkas, "Date Update : ".date("Y-m-d G:i:s")." By : ".$_SESSION['nm_user']." Title : Update filefotoskpensiun idspk ".$tempDeb['idspk']. " dari ".$tempDeb['filefotoskpensiun']." menjadi ".$filetmp."\r\n");
						//fclose($berkas);					
					}
				}								

				if ($errno1 OR $errno2 OR $errno3 OR $errno4 OR $errno5 OR $errno6 OR $errno7) {	
					echo '<tr><td colspan="4" align="center"><font color="red">'.$errno1.''.$errno2.''.$errno3.''.$errno4.''.$errno5.''.$errno6.''.$errno7.'</font></td></tr>';	
				}else{
					//echo $tmpfile.' '.$filetmp.' '.$photomet.'<br>';
					//echo 'UPDATE fu_ajk_spak_form SET '.$update1.' '.$update2.' '.$update3.' '.$update4.' '.$update5.' '.$update6.' '.$update7.' WHERE id="'.$tempDeb['id'].'"';

					$metphoto = $database->doQuery('UPDATE fu_ajk_spak_form 
																					SET '.$update1.' '.$update2.' '.$update3.' '.$update4.' '.$update5.' '.$update6.' '.$update7.'
																					WHERE id="'.$tempDeb['id'].'"');
					$metphototemp = $database->doQuery('UPDATE fu_ajk_spak_form_temp
																							SET '.$update1.' '.$update2.' '.$update3.' '.$update4.' '.$update5.' '.$update6.' '.$update7.'
																							WHERE id="'.$tempDeb['id'].'"');			
					echo '<div class="title2" align="center">Photo peserta telah berhasil di upload oleh <b>'.$q['nm_lengkap'].'</b> pada tanggal '.$futgldn.' !</div><meta http-equiv="refresh" content="3; url=scase.php?sc=eSPK">';
				}
			}

			echo '<form name="f1" method="post" enctype="multipart/form-data" action="">
					<input type="hidden" name="idp" value="'.$tempDeb['id'].'">
					<table border="0" cellpadding="1" cellspacing="0" width="100%">
						<tr><td width="50%" align="right">Nama Peserta</td><td> : '.$tempDeb['nama'].'</td></tr>	      
						<tr><td align="right">Photo Debitur 1<a href="../../ajkmobilescript/'.$tempDeb['filefotodebitursatu'].'" rel="lightbox" target="_blank"><img src="../../ajkmobilescript/'.$tempDeb['filefotodebitursatu'].'" width="40"></a><br /><font color="red">*<font size="1">Maksimal ukuran file 2MB</font></td><td valign="top">: <input name="filefotodebitursatu" type="file" size="50" onchange="checkfile(this);"></td></tr>
						<tr><td align="right">Photo Debitur 2<a href="../../ajkmobilescript/'.$tempDeb['filefotodebiturdua'].'" rel="lightbox" target="_blank"><img src="../../ajkmobilescript/'.$tempDeb['filefotodebiturdua'].'" width="40"></a><br /><font color="red">*<font size="1">Maksimal ukuran file 2MB</font></td><td valign="top">: <input name="filefotodebiturdua" type="file" size="50" onchange="checkfile(this);"></td></tr>
						<tr><td align="right">Photo KTP<a href="../../ajkmobilescript/'.$tempDeb['filefotoktp'].'" rel="lightbox" target="_blank"><img src="../../ajkmobilescript/'.$tempDeb['filefotoktp'].'" width="40"></a><br /><font color="red">*<font size="1">Maksimal ukuran file 2MB</font></td><td valign="top">: <input name="filefotoktp" type="file" size="50" onchange="checkfile(this);"></td></tr>
						<tr><td align="right">TTD Debitur<a href="../../ajkmobilescript/'.$tempDeb['filettddebitur'].'" rel="lightbox" target="_blank"><img src="../../ajkmobilescript/'.$tempDeb['filettddebitur'].'" width="40"></a><br /><font color="red">*<font size="1">Maksimal ukuran file 2MB</font></td><td valign="top">: <input name="filettddebitur" type="file" size="50" onchange="checkfile(this);"></td></tr>
						<tr><td align="right">TTD Marketing<a href="../../ajkmobilescript/'.$tempDeb['filettdmarketing'].'" rel="lightbox" target="_blank"><img src="../../ajkmobilescript/'.$tempDeb['filettdmarketing'].'" width="40"></a><br /><font color="red">*<font size="1">Maksimal ukuran file 2MB</font></td><td valign="top">: <input name="filettdmarketing" type="file" size="50" onchange="checkfile(this);"></td></tr>
						<tr><td align="right">TTD Dokter<a href="../../ajkmobilescript/'.$tempDeb['filettddokter'].'" rel="lightbox" target="_blank"><img src="../../ajkmobilescript/'.$tempDeb['filettddokter'].'" width="40"></a><br /><font color="red">*<font size="1">Maksimal ukuran file 2MB</font></td><td valign="top">: <input name="filettddokter" type="file" size="50" onchange="checkfile(this);"></td></tr>
						<tr><td align="right">SK Pensiun<a href="../../ajkmobilescript/'.$tempDeb['filefotoskpensiun'].'" rel="lightbox" target="_blank"><img src="../../ajkmobilescript/'.$tempDeb['filefotoskpensiun'].'" width="40"></a><br /><font color="red">*<font size="1">Maksimal ukuran file 2MB</font></td><td valign="top">: <input name="filefotoskpensiun" type="file" size="50" onchange="checkfile(this);"></td></tr>
					<tr><td align="center"colspan="2"><input type="hidden" name="el" value="_photo"><input type="submit" name="upload_rate" value="Upload"></td></tr>
					</table>
					</form>';
				;
	break;

	case "eRefund":
		echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
			<tr><th width="100%" align="left">Edit Data Nilai Refund</font></th>
				<th><a href="scase.php"><img src="../image/Backward-64.png" width="20" border="0"></a></th>
			</tr></table>';
		echo '<table border="0" width="100%" cellpadding="1" cellspacing="1">
			<form method="post" action="">
			<tr><td width="10%">Nama Debitur :</td><td><input type="text" name="scEref" value="' . $_REQUEST['scEref'] . '"> <input type="hidden" name="edR" value="myRef" class="button"><input type="submit" name="button" value="Cari" class="button"></td></tr>
			</form></table>';
		if ($_REQUEST['edR'] == "myRef") {
		if ($_REQUEST['scEref']=="") 	{	$error_1 = '<div align="center"><font color="red"><blink>Masukan nama debitur yang akan di edit unttk data Refund.<br /></div></font></blink>';	}
		if ($error_1) { echo $error_1;	}
		else {
		if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}
		$_scEref = $database->doQuery('SELECT
		fu_ajk_costumer.`name`,
		fu_ajk_polis.nmproduk,
		fu_ajk_polis.typeproduk,
		fu_ajk_peserta.id_peserta,
		fu_ajk_cn.id_cn AS cn_kode,
		fu_ajk_cn.tgl_claim,
		fu_ajk_cn.premi,
		fu_ajk_cn.total_claim,
		fu_ajk_cn.type_claim,
		fu_ajk_peserta.id,
		fu_ajk_peserta.nama_mitra,
		fu_ajk_peserta.nama,
		fu_ajk_peserta.tgl_lahir,
		fu_ajk_peserta.usia,
		fu_ajk_peserta.kredit_tgl,
		IF(fu_ajk_polis.typeproduk ="SPK", fu_ajk_peserta.kredit_tenor * 12, fu_ajk_peserta.kredit_tenor) AS tenor,
		fu_ajk_peserta.kredit_akhir,
		fu_ajk_peserta.kredit_jumlah,
		fu_ajk_peserta.totalpremi,
		fu_ajk_peserta.status_aktif,
		fu_ajk_peserta.status_peserta,
		fu_ajk_peserta.cabang,
		fu_ajk_dn.dn_kode
		FROM fu_ajk_peserta
		INNER JOIN fu_ajk_costumer ON fu_ajk_peserta.id_cost = fu_ajk_costumer.id
		INNER JOIN fu_ajk_polis ON fu_ajk_peserta.id_polis = fu_ajk_polis.id
		INNER JOIN fu_ajk_cn ON fu_ajk_peserta.id_peserta = fu_ajk_cn.id_peserta AND fu_ajk_peserta.id_dn = fu_ajk_cn.id_dn
		INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id
		WHERE
		fu_ajk_peserta.nama LIKE "%'.$_REQUEST['scEref'].'%" AND
		fu_ajk_peserta.status_peserta = "Refund" AND
		fu_ajk_peserta.del IS NULL AND
		fu_ajk_cn.del IS NULL
		ORDER BY fu_ajk_polis.nmproduk ASC,fu_ajk_peserta.nama ASC
		LIMIT '.$m.', 25');
		echo '<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="#E2E2E2">
			<tr><th width="1%">No</th>
			<th>Nama Perusahaan</th>
			<th>Produk</th>
			<th>Debitnote</th>
			<th>Creditnote</th>
			<th>ID Peserta</th>
			<th>Nama Debitur</th>
			<th>Tgl Lahir</th>
			<th>Usia</th>
			<th>Plafond</th>
			<th>Mulai Asuransi</th>
			<th>Tenor</th>
			<th>Akhir Asuransi</th>
			<th>Total Premi</th>
			<th>Status</th>
			<th>Data</th>
			<th>Tanggal Refund</th>
			<th>Nilai Refund</th>
			<th>Cabang</th>
			<th>Edit</th>
			</tr>';
		$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(fu_ajk_peserta.nama)
		FROM fu_ajk_peserta
		INNER JOIN fu_ajk_cn ON fu_ajk_peserta.id_peserta = fu_ajk_cn.id_peserta AND fu_ajk_peserta.id_dn = fu_ajk_cn.id_dn
		WHERE
		fu_ajk_peserta.nama LIKE "%'.$_REQUEST['scEref'].'%" AND
		fu_ajk_peserta.status_peserta = "Refund" AND
		fu_ajk_peserta.del IS NULL AND
		fu_ajk_cn.del IS NULL'));
		$totalRows = $totalRows[0];
		$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
		while ($_scEref_ = mysql_fetch_array($_scEref)) {
		if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
		echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
				<td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
				<td align="center">'.$_scEref_['name'].'</td>
				<td align="center">'.$_scEref_['nmproduk'].'</td>
				<td align="center">'.$_scEref_['dn_kode'].'</td>
				<td align="center">'.$_scEref_['cn_kode'].'</td>
				<td align="center">'.$_scEref_['id_peserta'].'</td>
				<td>'.$_scEref_['nama'].'</td>
				<td align="center">'._convertDate($_scEref_['tgl_lahir']).'</td>
				<td align="center">'.$_scEref_['usia'].'</td>
				<td align="right"><b>'.duit($_scEref_['kredit_jumlah']).'</b></td>
				<td align="center">'._convertDate($_scEref_['kredit_tgl']).'</td>
				<td align="center">'.$_scEref_['tenor'].'</td>
				<td align="center">'._convertDate($_scEref_['kredit_akhir']).'</td>
				<td align="right">'.duit($_scEref_['premi']).'</td>
				<td align="right">'.$_scEref_['status_peserta'].'</td>
				<td align="right">'.$_scEref_['status_aktif'].'</td>
				<td align="center"><b>'._convertDate($_scEref_['tgl_claim']).'</b></td>
				<td align="right"><b>'.duit($_scEref_['total_claim']).'</b></td>
				<td align="right">'.$_scEref_['cabang'].'</td>
				<td align="center"><a href="scase.php?sc=edRefund&idr='.$_scEref_['id'].'"><img src="image/edit3.png" width="20"></a></td>
				</tr>';
		}
			echo '<tr><td colspan="22">';
			echo createPageNavigations($file = 'scase.php?sc=eRefund&edR='.$_REQUEST['edR'].'&scEref='.$_REQUEST['scEref'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
			echo '<b>Total Data Pencarian Refund: <u>' . $totalRows . '</u></b></td></tr>';
			echo '</table>';
		}

		}
			;
	break;

	case "edRefund":
		echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
			<tr><th width="100%" align="left">Edit Data Nilai Refund</font></th>
				<th><a href="scase.php?sc=eRefund"><img src="../image/Backward-64.png" width="20" border="0"></a></th>
			</tr></table>';
		if ($_REQUEST['ope']=="saveditrefund") {
		$mamet = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id="' . $_REQUEST['idp'] . '"'));
		$mametCN = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cn WHERE id="' . $_REQUEST['idcn'] . '"'));
			$metbulanplus = date('Y-m-d', strtotime('+ 1 month', strtotime($mamet['kredit_tgl'])));
			if ($_REQUEST['tglRefund'] <= $metbulanplus) {
				$premirefund = $mamet['totalpremi'];
			} else {
				$movementrefund = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_rasio_claim WHERE id_cost="' . $mamet['id_cost'] . '" AND id_polis="' . $mamet['id_polis'] . '"'));
				// $biayapenutupan = $mamet['totalpremi'] *  $movementrefund['refund'];
				// $premirefund = ceil(($r / $mamet['kredit_tenor']) * ($mamet['totalpremi'] - $biayapenutupan));
				$mets = datediff($mamet['kredit_tgl'], $_REQUEST['tglRefund']);
				$pecahtglrefund = explode(",", $mets);
				if ($mamet['type_data'] == "SPK") {
					$metTenornya = $mamet['kredit_tenor'] * 12 ;
				} else {
					$metTenornya = $mamet['kredit_tenor'];
				}
				$jumbulan = $metTenornya - ($pecahtglrefund[0] * 12 + $pecahtglrefund[1]);
				$biayapenutupan = $mamet['totalpremi'] * $movementrefund['refund'];
				$premirefund = $jumbulan / $metTenornya * ($mamet['totalpremi'] - $biayapenutupan);
				// echo $premirefund;
			}
			// CEK PERHITUNGAN REFUND PREMI
			/*$berkas = fopen("historyedit.txt", "a") or die ("File history tidak ada.");
			$asli__ = "(ASLIREFUND)\t" . $mamet['id_cost'] . " - " . $mamet['id_polis'] . " - " . $mametCN['tgl_claim'] . " - " . duit($mametCN['total_claim']) . "";
			fwrite($berkas, $asli__ . "\r\n");
			$revisi__ = "(REVISIREFUND)" . $mamet['id_cost'] . " - " . $mamet['id_polis'] . " - " . $_REQUEST['tglRefund'] . " - " . duit($premirefund) . " [" . $q['nm_user'] . " - " . $futgl . "]";
			fwrite($berkas, $revisi__ . "\r\n");
			fclose($berkas);*/
			$metrefundcn = $database->doQuery('UPDATE fu_ajk_cn SET total_claim="' . $premirefund . '", tgl_claim="' . $_REQUEST['tglRefund'] . '" WHERE id="'.$_REQUEST['idcn'].'"');
			echo '<center><b>Tanggal Refund telah diedit oleh ' . $q['nm_lengkap'] . '.<br /><meta http-equiv="refresh" content="3;URL=scase.php?sc=eRefund"></b></center>';
		}

		$_scEref = mysql_fetch_array($database->doQuery('SELECT
		fu_ajk_costumer.`name`,
		fu_ajk_polis.nmproduk,
		fu_ajk_polis.typeproduk,
		fu_ajk_peserta.id_peserta,
		fu_ajk_cn.id AS idcn,
		fu_ajk_cn.id_cn AS cn_kode,
		fu_ajk_cn.tgl_claim,
		fu_ajk_cn.premi,
		fu_ajk_cn.total_claim,
		fu_ajk_cn.type_claim,
		fu_ajk_peserta.id,
		fu_ajk_peserta.nama_mitra,
		fu_ajk_peserta.nama,
		fu_ajk_peserta.tgl_lahir,
		fu_ajk_peserta.usia,
		fu_ajk_peserta.kredit_tgl,
		IF(fu_ajk_polis.typeproduk ="SPK", fu_ajk_peserta.kredit_tenor * 12, fu_ajk_peserta.kredit_tenor) AS tenor,
		fu_ajk_peserta.kredit_akhir,
		fu_ajk_peserta.kredit_jumlah,
		fu_ajk_peserta.totalpremi,
		fu_ajk_peserta.status_aktif,
		fu_ajk_peserta.status_peserta,
		fu_ajk_peserta.cabang,
		fu_ajk_dn.dn_kode
		FROM fu_ajk_peserta
		INNER JOIN fu_ajk_costumer ON fu_ajk_peserta.id_cost = fu_ajk_costumer.id
		INNER JOIN fu_ajk_polis ON fu_ajk_peserta.id_polis = fu_ajk_polis.id
		INNER JOIN fu_ajk_cn ON fu_ajk_peserta.id_peserta = fu_ajk_cn.id_peserta AND fu_ajk_peserta.id_dn = fu_ajk_cn.id_dn
		INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id
		WHERE
		fu_ajk_peserta.id ="'.$_REQUEST['idr'].'"'));
		//echo $_scEref['id'].'<br />';
		//echo $_scEref['nama'].'<br />';
		echo '<form method="POST" action="" class="input-list style-1 smart-green">
			<input type="hidden" name="idp" value="' . $_scEref['id'] . '">
			<input type="hidden" name="idcn" value="' . $_scEref['idcn'] . '">
			<table border="0" width="80%" align="center">
			<tr><td colspan="2"><h1>Edit Data Refund a.n ' . $_scEref['nama'] . '</h1></td></tr>
			<tr><td width="50%">
			<label><span>Nama Perusahaan</span><input type="text" name="_nomorSPK" value="' . $_scEref['name'] . '" size="30" placeholder="Nama Perusahaan" DISABLED></label>
			<label><span>Nama Produk</span><input type="text" name="_nomorSPK" value="' . $_scEref['nmproduk'] . '" size="30" placeholder="Nama Produk" DISABLED></label>
			<label><span>ID Peserta</span><input type="text" name="_nomorSPK" value="' . $_scEref['id_peserta'] . '" size="30" placeholder="ID Peserta" DISABLED></label>
			<label><span>Nama Peserta</span><input type="text" name="_nomorSPK" value="' . $_scEref['nama'] . '" size="30" placeholder="Nama Peserta" DISABLED></label>
			<label><span>Tanggal Lahir</span><input type="text" name="_nomorSPK" value="' . $_scEref['tgl_lahir'] . '" size="30" placeholder="Tanggal Lahir" DISABLED></label>
			<label><span>Usia</span><input type="text" name="_nomorSPK" value="' . $_scEref['usia'] . '" size="30" placeholder="Usia" DISABLED></label>
			</td>
			<td>
			<label><span>Tenor Asuransi</span><input type="text" name="_nomorSPK" value="' . $_scEref['tenor'] . ' bulan" size="30" placeholder="Tenor Asuransi" DISABLED></label>
			<label><span>Tanggal Asuransi</span><input type="text" name="_nomorSPK" value="' ._convertDate($_scEref['kredit_tgl']). ' s/d ' ._convertDate( $_scEref['kredit_akhir']). '" size="30" placeholder="Tanggal Akhir Asuransi" DISABLED></label>
			<label><span>Plafond</span><input type="text" name="_nomorSPK" value="' . duit($_scEref['kredit_jumlah']) . '" size="30" placeholder="Plafond" DISABLED></label>
			<label><span>Total Premi</span><input type="text" name="_nomorSPK" value="' .duit( $_scEref['totalpremi']) . '" size="30" placeholder="Total Premi" DISABLED></label>
			<label><span>Tanggal Refund <font color="red">*</font></span><input type="text" name="tglRefund" id="tglRefund" class="tanggal" value="'.$_scEref['tgl_claim'].'" size="10"/></label>
			<label><span><b>Nilai Refund</b></span><input type="text" name="_nomorSPK" value="'.duit( $_scEref['total_claim']).'" size="30" placeholder="Nilai Refund" DISABLED></label>
			</td>
			</tr>
			<tr><td colspan="2"><label><span>&nbsp;</span><input type="submit" name="ope" value="Simpan" class="button" /><input type="hidden" name="ope" value="saveditrefund" class="button" /></label></td></tr>
			</table></form>';
			;
	break;

	case "SimpanSPK":
		$_eSPK = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak WHERE id="' . $_REQUEST['idspk'] . '" AND del IS NULL'));
		if ($_REQUEST['_nomorSPK'] == "") {
		$error1 = "<center><font color=red>Nomor SPK tidak boleh kosong</font></center>";
		}
		if ($error1) {	echo $error1;	}
		else {
		//CEK APABILA PREMI BERUBAH KARENA GANTI PRODUK
		$metSPK = mysql_fetch_array($database->doQuery('SELECT fu_ajk_spak.id,
																fu_ajk_spak.id_cost,
																fu_ajk_spak.id_polis,
																fu_ajk_spak.id_mitra,
																fu_ajk_spak.spak,
																fu_ajk_spak.ext_premi,
																fu_ajk_spak_form.id AS idformspk,
																fu_ajk_spak_form.plafond,
																fu_ajk_spak_form.tenor,
																fu_ajk_spak_form.mpp,
																fu_ajk_spak_form.x_premi,
																fu_ajk_spak_form.x_usia,
																fu_ajk_spak_form.cabang
																FROM fu_ajk_spak
																INNER JOIN fu_ajk_spak_form ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
																WHERE fu_ajk_spak.id ="' . $_eSPK['id'] . '"'));
		/*
		echo $_REQUEST['idprod'].'<br />';
		echo $_REQUEST['idspk'].'<br />';
		echo $metSPK['plafond'].'<br />';
		echo $metSPK['mpp'].'<br />';
		*/

		$cekProduk = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$_REQUEST['idprod'].'"'));
		if ($cekProduk['mpptype']=="Y") {
			$spkRate = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$metSPK['id_cost'].'" AND id_polis="'.$cekProduk['id'].'" AND tenor="'.$metSPK['tenor'].'" AND "'.$metSPK['mpp'].'" BETWEEN mpp_s AND mpp_e AND status="baru" AND del IS NULL'));
			$premirate = $metSPK['plafond'] * ($spkRate['rate'] / 1000 );
			$premiem = $premirate * ($metSPK['ext_premi'] / 100 );
			$preminett = $premirate + $premiem;
			//$metEditForm = $database->doQuery('UPDATE fu_ajk_spak_form SET x_premi="'.$preminett.'" WHERE id="' . $metSPK['idformspk'] . '"');	22092016
			//$metEditForm = $database->doQuery('UPDATE fu_ajk_spak_form SET x_premi="'.$premirate.'" WHERE id="' . $metSPK['idformspk'] . '"');
		}else{

		}
		//CEK APABILA PREMI BERUBAH KARENA GANTI PRODUK

			$berkas = fopen("historyedit.txt", "a") or die ("File history tidak ada.");
			$asli__ = "(ASLI)\t" . $_eSPK['id_cost'] . " - " . $_eSPK['id_polis'] . " - " . $_eSPK['spak'] . " - " . $_eSPK['status'] . " - " . $_eSPK['input_date'] . "";
			fwrite($berkas, $asli__ . "\r\n");
			$revisi__ = "(REVISI)" . $_eSPK['id_cost'] . " - " . $_REQUEST['idprod'] . " - " . $_REQUEST['_nomorSPK'] . " - " . $_REQUEST['rstatus'] . " - " . $_REQUEST['tglInputSPK'] . " [" . $q['nm_user'] . " - " . $futgl . "]";
			fwrite($berkas, $revisi__ . "\r\n");
			fclose($berkas);
		$metEdit = $database->doQuery('UPDATE fu_ajk_spak SET id_polis="' . $_REQUEST['idprod'] . '", spak="' . $_REQUEST['_nomorSPK'] . '", status="' . $_REQUEST['rstatus'] . '", input_date="'.$_REQUEST['tglInputSPK'].'" WHERE id="' . $_eSPK['id'] . '"');
		echo '<center>Data SPK telah direvisi oleh ' . $q['nm_lengkap'] . '.</center><meta http-equiv="refresh" content="2; url=scase.php?sc=eSPK">';
			}
			;
  break;

	case "eAge75":
		echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
			<tr><th width="100%" align="left">Cek Usia 75 thn</font></th>
				<th><a href="scase.php"><img src="../image/Backward-64.png" width="20" border="0"></a></th>
			</tr></table>';
		echo '<table border="0" width="100%" cellpadding="1" cellspacing="1">
			<form method="post" action="">
			<tr><td width="10%">Cek Usia :</td><td><input type="text" name="scEAge" value="' . $_REQUEST['scEAge'] . '"> <input type="hidden" name="edU" value="myAge" class="button"><input type="submit" name="button" value="Cari" class="button"></td></tr>
			</form></table>';
		if ($_REQUEST['scEAge'])		{	$cekUsia = 'AND (fu_ajk_spak_form.x_usia + fu_ajk_spak_form.tenor) = "' . $_REQUEST['scEAge'] . '"';		}
		if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}
		$metUsia = $database->doQuery('SELECT
		fu_ajk_spak.id AS idspk,
		fu_ajk_spak.spak,
		fu_ajk_spak.ext_premi,
		fu_ajk_spak.`status`,
		fu_ajk_spak.input_by AS inputspk,
		fu_ajk_spak.input_date AS inputspkdate,
		fu_ajk_spak_form.id AS idformspk,
		fu_ajk_spak_form.noidentitas,
		fu_ajk_spak_form.nama,
		fu_ajk_spak_form.jns_kelamin,
		fu_ajk_spak_form.dob,
		fu_ajk_spak_form.x_usia,
		fu_ajk_spak_form.tenor,
		(fu_ajk_spak_form.x_usia + fu_ajk_spak_form.tenor) AS totalUsia,
		fu_ajk_spak_form.input_by AS inputformspk,
		fu_ajk_spak_form.input_date AS inputformspkdate
		FROM fu_ajk_spak
		INNER JOIN fu_ajk_spak_form ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
		WHERE fu_ajk_spak.del IS NULL AND fu_ajk_spak_form.del IS NULL '.$cekUsia.' ORDER BY fu_ajk_spak.id DESC LIMIT '.$m.', 25');
		echo '<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="#E2E2E2">
			<tr><th width="1%">No</th>
			<th>Nomor SPK</th>
			<th>EM SPK</th>
			<th>Ststus</th>
			<th>User Input</th>
			<th>Tgl Input</th>
			<th>noidentitas</th>
			<th>nama</th>
			<th>dob</th>
			<th>x_usia</th>
			<th>tenor</th>
			<th>totalUsia</th>
			<th>User Input Form SPK</th>
			<th>Tgl Input Form SPK</th>
			</tr>';
		$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(fu_ajk_spak.id), (fu_ajk_spak_form.x_usia + fu_ajk_spak_form.tenor) AS totalUsia
															FROM fu_ajk_spak
															INNER JOIN fu_ajk_spak_form ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
															WHERE fu_ajk_spak.del IS NULL AND fu_ajk_spak_form.del IS NULL '.$cekUsia.'
															GROUP BY fu_ajk_spak.id_cost'));
		$totalRows = $totalRows[0];
		$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
		while ($metUsia_ = mysql_fetch_array($metUsia)) {
		if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
		echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
					<td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
					<td align="center">'.$metUsia_['spak'].'</td>
					<td align="center">'.$metUsia_['ext_premi'].'</td>
					<td align="center">'.$metUsia_['status'].'</td>
					<td align="center">'.$metUsia_['inputspk'].'</td>
					<td align="center"><b>'.$metUsia_['inputspkdate'].'</b></td>
					<td align="center">'.$metUsia_['noidentitas'].'</td>
					<td>'.$metUsia_['nama'].'</td>
					<td align="center">'.$metUsia_['dob'].'</td>
					<td align="center">'.$metUsia_['x_usia'].'</td>
					<td align="center">'.$metUsia_['tenor'].'</td>
					<td align="center">'.$metUsia_['totalUsia'].'</td>
					<td align="right">'.$metUsia_['inputformspk'].'</td>
					<td align="right"><b>'.$metUsia_['inputformspkdate'].'</b></td>
					</tr>';
		}
			echo '<tr><td colspan="22">';
			echo createPageNavigations($file = 'scase.php?sc=eAge75&scEAge='.$_REQUEST['scEAge'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
			echo '<b>Total Data: <u>' . $totalRows . '</u></b></td></tr>';
			echo '</table>';
			;
  break;

	case "eDDebitur":
		echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
			<tr><th width="100%" align="left">Edit Data Debitur</font></th>
			<th><a href="scase.php?sc=eRefund"><img src="../image/Backward-64.png" width="20" border="0"></a></th>
			</tr></table>';
		if ($_REQUEST['ope']=="saveditdebitur") {
			$mamet = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id="' . $_REQUEST['idp'] . '"'));
			// CEK PERHITUNGAN REFUND PREMI
			$berkas = fopen("historyedit.txt", "a") or die ("File history tidak ada.");
			$asli__ = "(ASLIDEBITUR)\t".$mamet['id_cost']."|".$mamet['id_polis']."|".$mamet['nama']."|".$mamet['tgl_lahir']."|".$mamet['usia']."|".$mamet['kredit_tgl']."|".$mamet['kredit_tenor']."|".$mamet['kredit_akhir']."|".$mamet['mppbln']."|".$mamet['kredit_jumlah']."|".$mamet['premi']."|".$mamet['ext_premi']."|".$mamet['totalpremi']."";
			fwrite($berkas, $asli__ . "\r\n");
			$revisi__ = "(REVISIDEBITUR)\t".$mamet['id_cost']."|".$mamet['id_polis']."|".$_REQUEST['namadebitur']."|".$_REQUEST['tgllahir']."|".$_REQUEST['usia']."|".$_REQUEST['tglakad']."|".$_REQUEST['tenor']."|".$_REQUEST['tglakhir']."|".$_REQUEST['mppbln']."|".$_REQUEST['plafond']."|".$_REQUEST['premi']."|".$_REQUEST['extpremi']."|".$_REQUEST['totalpremi']." [".$q['nm_user']."|".$futgl."]";
			fwrite($berkas, $revisi__ . "\r\n");
			fclose($berkas);
			//$metrefundcn = $database->doQuery('UPDATE fu_ajk_peserta SET total_claim="' . $premirefund . '", tgl_claim="' . $_REQUEST['tglRefund'] . '" WHERE id="'.$_REQUEST['idp'].'"');
			$metrefundcn = $database->doQuery('UPDATE fu_ajk_peserta SET nama="'.$_REQUEST['namadebitur'].'",
																		tgl_lahir="'.$_REQUEST['tgllahir'].'",
																		usia="'.$_REQUEST['usia'].'",
																		kredit_tgl="'.$_REQUEST['tglakad'].'",
																		kredit_tenor="'.$_REQUEST['tenor'].'",
																		kredit_akhir="'.$_REQUEST['tglakhir'].'",
																		mppbln="'.$_REQUEST['mppbln'].'",
																		kredit_jumlah="'.$_REQUEST['plafond'].'",
																		premi="'.$_REQUEST['premi'].'",
																		ext_premi="'.$_REQUEST['extpremi'].'",
																		totalpremi="'.$_REQUEST['totalpremi'].'"
												WHERE id="'.$_REQUEST['idp'].'"');
			echo '<center><b>Data debitur telah diedit oleh ' . $q['nm_lengkap'] . '.<br /><meta http-equiv="refresh" content="3;URL=scase.php?sc=eDataDebitur"></b></center>';
		}

		$_scEref = mysql_fetch_array($database->doQuery('SELECT
		fu_ajk_costumer.`name`,
		fu_ajk_polis.nmproduk,
		fu_ajk_polis.typeproduk,
		fu_ajk_peserta.id_peserta,
		fu_ajk_peserta.id,
		fu_ajk_peserta.nama_mitra,
		fu_ajk_peserta.nama,
		fu_ajk_peserta.tgl_lahir,
		fu_ajk_peserta.usia,
		fu_ajk_peserta.kredit_tgl,
		IF(fu_ajk_polis.typeproduk ="SPK", fu_ajk_peserta.kredit_tenor * 12, fu_ajk_peserta.kredit_tenor) AS tenor,
		fu_ajk_peserta.kredit_akhir,
		fu_ajk_peserta.kredit_jumlah,
		fu_ajk_peserta.premi,
		fu_ajk_peserta.ext_premi,
		fu_ajk_peserta.totalpremi,
		fu_ajk_peserta.status_aktif,
		fu_ajk_peserta.status_peserta,
		fu_ajk_peserta.mppbln,
		fu_ajk_peserta.cabang
		FROM fu_ajk_peserta
		INNER JOIN fu_ajk_costumer ON fu_ajk_peserta.id_cost = fu_ajk_costumer.id
		INNER JOIN fu_ajk_polis ON fu_ajk_peserta.id_polis = fu_ajk_polis.id
		WHERE fu_ajk_peserta.id ="'.$_REQUEST['idr'].'"'));
			//echo $_scEref['id'].'<br />';
			//echo $_scEref['nama'].'<br />';
		echo '<form method="POST" action="" class="input-list style-1 smart-green">
				<input type="hidden" name="idp" value="' . $_scEref['id'] . '">
				<table border="0" width="80%" align="center">
				<tr><td colspan="2"><h1>Edit Data Debitur a.n ' . $_scEref['nama'] . '</h1></td></tr>
				<tr><td width="50%" valign="top">
				<label><span>Nama Perusahaan</span><input type="text" name="perusahaan" value="' . $_scEref['name'] . '" size="30" placeholder="Nama Perusahaan" DISABLED></label>
				<label><span>Nama Produk</span><input type="text" name="produk" value="' . $_scEref['nmproduk'] . '" size="30" placeholder="Nama Produk" DISABLED></label>
				<label><span>ID Peserta</span><input type="text" name="idpeserta" value="' . $_scEref['id_peserta'] . '" size="30" placeholder="ID Peserta" DISABLED></label>
				<label><span>Nama Peserta</span><input type="text" name="namadebitur" value="' . $_scEref['nama'] . '" size="30" placeholder="Nama Peserta"></label>
				<label><span>Tanggal Lahir</span><input type="text" name="tgllahir" value="' . $_scEref['tgl_lahir'] . '" class="tanggal" size="30" placeholder="Tanggal Lahir"></label>
				<label><span>Usia</span><input type="text" name="usia" value="' . $_scEref['usia'] . '" size="30" placeholder="Usia" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"/></label>
				</td>
				<td>
				<table border="0" width="100%" align="center" cellpadding="0" cellspacing="0">
				<tr><td><label><span>Tanggal Akad</span><input type="text" name="tglakad" value="' .$_scEref['kredit_tgl']. '" class="tanggal" size="30" placeholder="Tanggal Akhir Asuransi"></label></td>
					<td><label><span>Tanggal Akhir</span><input type="text" name="tglakhir" value="' . $_scEref['kredit_akhir']. '" class="tanggal" size="30" placeholder="Tanggal Akhir Asuransi"></label></td>
				</tr>
				<tr><td><label><span>Tenor Asuransi</span><input type="text" name="tenor" value="' . $_scEref['tenor'] . '" size="30" placeholder="Tenor Asuransi" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"/></label></td>
					<td><label><span>MPP</span><input type="text" name="mppbln" value="' . $_scEref['mppbln'] . '" size="30" placeholder="Plafond" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"/></label></td>
				</tr>
				</table>
				<label><span>Plafond</span><input type="text" name="plafond" value="' . $_scEref['kredit_jumlah'] . '" size="30" placeholder="Plafond" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"/></label>
				<label><span>Premi</span><input type="text" name="premi" value="' . $_scEref['premi'] . '" size="30" placeholder="Premi" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"/></label>
				<label><span>Extra Premi</span><input type="text" name="extpremi" value="' . $_scEref['ext_premi'] . '" size="30" placeholder="Extra Premi" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"/></label>
				<label><span>Total Premi</span><input type="text" name="totalpremi" value="' . $_scEref['totalpremi'] . '" size="30" placeholder="Total Premi" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"/></label>
				</td>
				</tr>
				<tr><td colspan="2"><label><span>&nbsp;</span><input type="submit" name="ope" value="Simpan" class="button" /><input type="hidden" name="ope" value="saveditdebitur" class="button" /></label></td></tr>
				</table></form>';
			;
	break;

	case "eDataDebitur":
		echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
			<tr><th width="100%" align="left">Edit Data Debitur</font></th>
				<th><a href="scase.php"><img src="../image/Backward-64.png" width="20" border="0"></a></th>
			</tr></table>';
		echo '<table border="0" width="100%" cellpadding="1" cellspacing="1">
			<form method="post" action="">
			<tr><td width="10%">Nama Debitur :</td><td><input type="text" name="scEref" value="' . $_REQUEST['scEref'] . '"> <input type="hidden" name="edD" value="myDeb" class="button"><input type="submit" name="button" value="Cari" class="button"></td></tr>
			</form></table>';
		if ($_REQUEST['edD'] == "myDeb") {
			if ($_REQUEST['scEref']=="") 	{	$error_1 = '<div align="center"><font color="red"><blink>Masukan nama debitur yang akan di edit untuk data perubahan data.<br /></div></font></blink>';	}
			if ($error_1) { echo $error_1;	}
			else {
			if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}
		$_scEref = $database->doQuery('SELECT
		fu_ajk_costumer.`name`,
		fu_ajk_polis.nmproduk,
		fu_ajk_polis.typeproduk,
		fu_ajk_peserta.id_peserta,
		fu_ajk_peserta.id,
		fu_ajk_peserta.nama_mitra,
		fu_ajk_peserta.nama,
		fu_ajk_peserta.tgl_lahir,
		fu_ajk_peserta.usia,
		fu_ajk_peserta.kredit_tgl,
		IF(fu_ajk_polis.typeproduk ="SPK", fu_ajk_peserta.kredit_tenor * 12, fu_ajk_peserta.kredit_tenor) AS tenor,
		fu_ajk_peserta.kredit_akhir,
		fu_ajk_peserta.kredit_jumlah,
		fu_ajk_peserta.totalpremi,
		fu_ajk_peserta.status_aktif,
		fu_ajk_peserta.status_peserta,
		fu_ajk_peserta.cabang
		FROM fu_ajk_peserta
		INNER JOIN fu_ajk_costumer ON fu_ajk_peserta.id_cost = fu_ajk_costumer.id
		INNER JOIN fu_ajk_polis ON fu_ajk_peserta.id_polis = fu_ajk_polis.id
		WHERE
		fu_ajk_peserta.nama LIKE "%'.$_REQUEST['scEref'].'%" AND
		fu_ajk_peserta.status_aktif ="Approve" AND
		fu_ajk_peserta.status_peserta IS NULL AND
		fu_ajk_peserta.del IS NULL
		ORDER BY fu_ajk_polis.nmproduk ASC,fu_ajk_peserta.nama ASC
		LIMIT '.$m.', 25');
		echo '<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="#E2E2E2">
			<tr><th width="1%">No</th>
			<th>Nama Perusahaan</th>
			<th>Produk</th>
			<th>Debitnote</th>
			<th>ID Peserta</th>
			<th>Nama Debitur</th>
			<th>Tgl Lahir</th>
			<th>Usia</th>
			<th>Plafond</th>
			<th>Mulai Asuransi</th>
			<th>Tenor</th>
			<th>Akhir Asuransi</th>
			<th>MPP</th>
			<th>Total Premi</th>
			<th>Status</th>
			<th>Cabang</th>
			<th>Edit</th>
			</tr>';
		$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(fu_ajk_peserta.nama) FROM fu_ajk_peserta WHERE fu_ajk_peserta.nama LIKE "%'.$_REQUEST['scEref'].'%" AND fu_ajk_peserta.status_aktif ="Approve" AND fu_ajk_peserta.status_peserta IS NULL AND fu_ajk_peserta.del IS NULL'));
		$totalRows = $totalRows[0];
		$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
		while ($_scEref_ = mysql_fetch_array($_scEref)) {
		if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
			echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
					<td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
					<td align="center">'.$_scEref_['name'].'</td>
					<td align="center">'.$_scEref_['nmproduk'].'</td>
					<td align="center">'.$_scEref_['dn_kode'].'</td>
					<td align="center">'.$_scEref_['id_peserta'].'</td>
					<td>'.$_scEref_['nama'].'</td>
					<td align="center">'._convertDate($_scEref_['tgl_lahir']).'</td>
					<td align="center">'.$_scEref_['usia'].'</td>
					<td align="right"><b>'.duit($_scEref_['kredit_jumlah']).'</b></td>
					<td align="center">'._convertDate($_scEref_['kredit_tgl']).'</td>
					<td align="center">'.$_scEref_['tenor'].'</td>
					<td align="center">'._convertDate($_scEref_['kredit_akhir']).'</td>
					<td align="center">'.$_scEref_['mpp'].'</td>
					<td align="right">'.duit($_scEref_['totalpremi']).'</td>
					<td align="right">'.$_scEref_['status_aktif'].'</td>
					<td align="right">'.$_scEref_['cabang'].'</td>
					<td align="center"><a href="scase.php?sc=eDDebitur&idr='.$_scEref_['id'].'"><img src="image/edit3.png" width="20"></a></td>
				</tr>';
			}
			echo '<tr><td colspan="22">';
			echo createPageNavigations($file = 'scase.php?sc=eRefund&edR='.$_REQUEST['edR'].'&scEref='.$_REQUEST['scEref'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
			echo '<b>Total Data Pencarian Refund: <u>' . $totalRows . '</u></b></td></tr>';
			echo '</table>';
			}
		}
			;
	break;

	case "eDataDebiturInforce":
		echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
			<tr><th width="100%" align="left">Edit Data Debitur</font></th>
				<th><a href="scase.php"><img src="../image/Backward-64.png" width="20" border="0"></a></th>
			</tr></table>';
		echo '<table border="0" width="100%" cellpadding="1" cellspacing="1">
			<form method="post" action="">
			<tr><td width="10%">Nama Debitur :</td><td><input type="text" name="scEref" value="' . $_REQUEST['scEref'] . '"> <input type="hidden" name="edD" value="myDeb" class="button"><input type="submit" name="button" value="Cari" class="button"></td></tr>
			</form></table>';
		if ($_REQUEST['edD'] == "myDeb") {
			if ($_REQUEST['scEref']=="") 	{	$error_1 = '<div align="center"><font color="red"><blink>Masukan nama debitur yang akan di edit untuk data perubahan data.<br /></div></font></blink>';	}
			if ($error_1) { echo $error_1;	}
			else {
			if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}
		$_scEref = $database->doQuery('SELECT
		fu_ajk_costumer.`name`,
		fu_ajk_polis.nmproduk,
		fu_ajk_polis.typeproduk,
		fu_ajk_peserta.id_peserta,
		fu_ajk_peserta.id,
		fu_ajk_peserta.nama_mitra,
		fu_ajk_peserta.nama,
		fu_ajk_peserta.tgl_lahir,
		fu_ajk_peserta.usia,
		fu_ajk_peserta.kredit_tgl,
		IF(fu_ajk_polis.typeproduk ="SPK", fu_ajk_peserta.kredit_tenor * 12, fu_ajk_peserta.kredit_tenor) AS tenor,
		fu_ajk_peserta.kredit_akhir,
		fu_ajk_peserta.kredit_jumlah,
		fu_ajk_peserta.totalpremi,
		fu_ajk_peserta.status_aktif,
		fu_ajk_peserta.status_peserta,
		fu_ajk_peserta.cabang
		FROM fu_ajk_peserta
		INNER JOIN fu_ajk_costumer ON fu_ajk_peserta.id_cost = fu_ajk_costumer.id
		INNER JOIN fu_ajk_polis ON fu_ajk_peserta.id_polis = fu_ajk_polis.id
		WHERE
		fu_ajk_peserta.nama LIKE "%'.$_REQUEST['scEref'].'%" AND
		fu_ajk_peserta.status_aktif ="Inforce" AND
		fu_ajk_peserta.status_peserta IS NULL AND
		fu_ajk_peserta.del IS NULL
		ORDER BY fu_ajk_polis.nmproduk ASC,fu_ajk_peserta.nama ASC
		LIMIT '.$m.', 25');
		echo '<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="#E2E2E2">
			<tr><th width="1%">No</th>
			<th>Nama Perusahaan</th>
			<th>Produk</th>
			<th>Debitnote</th>
			<th>ID Peserta</th>
			<th>Nama Debitur</th>
			<th>Tgl Lahir</th>
			<th>Usia</th>
			<th>Plafond</th>
			<th>Mulai Asuransi</th>
			<th>Tenor</th>
			<th>Akhir Asuransi</th>
			<th>MPP</th>
			<th>Total Premi</th>
			<th>Status</th>
			<th>Cabang</th>
			<th>Edit</th>
			</tr>';
		$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(fu_ajk_peserta.nama) FROM fu_ajk_peserta WHERE fu_ajk_peserta.nama LIKE "%'.$_REQUEST['scEref'].'%" AND fu_ajk_peserta.status_aktif ="Approve" AND fu_ajk_peserta.status_peserta IS NULL AND fu_ajk_peserta.del IS NULL'));
		$totalRows = $totalRows[0];
		$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
		while ($_scEref_ = mysql_fetch_array($_scEref)) {
		if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
			echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
					<td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
					<td align="center">'.$_scEref_['name'].'</td>
					<td align="center">'.$_scEref_['nmproduk'].'</td>
					<td align="center">'.$_scEref_['dn_kode'].'</td>
					<td align="center">'.$_scEref_['id_peserta'].'</td>
					<td>'.$_scEref_['nama'].'</td>
					<td align="center">'._convertDate($_scEref_['tgl_lahir']).'</td>
					<td align="center">'.$_scEref_['usia'].'</td>
					<td align="right"><b>'.duit($_scEref_['kredit_jumlah']).'</b></td>
					<td align="center">'._convertDate($_scEref_['kredit_tgl']).'</td>
					<td align="center">'.$_scEref_['tenor'].'</td>
					<td align="center">'._convertDate($_scEref_['kredit_akhir']).'</td>
					<td align="center">'.$_scEref_['mpp'].'</td>
					<td align="right">'.duit($_scEref_['totalpremi']).'</td>
					<td align="right">'.$_scEref_['status_aktif'].'</td>
					<td align="right">'.$_scEref_['cabang'].'</td>
					<td align="center"><a href="scase.php?sc=eDDebiturInforce&idr='.$_scEref_['id'].'"><img src="image/edit3.png" width="20"></a></td>
				</tr>';
			}
			echo '<tr><td colspan="22">';
			echo createPageNavigations($file = 'scase.php?sc=eDataDebiturInforce&edD='.$_REQUEST['edD'].'&scEref='.$_REQUEST['scEref'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
			echo '<b>Total Data Pencarian Refund: <u>' . $totalRows . '</u></b></td></tr>';
			echo '</table>';
			}
		}
			;
	break;

	case "eDDebiturInforce":
		echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
			<tr><th width="100%" align="left">Edit Data Debitur</font></th>
			<th><a href="scase.php?sc=eDataDebiturInforce"><img src="../image/Backward-64.png" width="20" border="0"></a></th>
			</tr></table>';
		if ($_REQUEST['ope']=="saveditdebitur") {
			$mamet = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id="' . $_REQUEST['idp'] . '"'));
			//insert log
			$queryold = "INSERT INTO fu_ajk_peserta_log 
										SET id_peserta = '".$mamet['id_peserta']."',
												nama = '".$mamet['nama']."',
												gender = '".$mamet['gender']."',
												tgl_lahir = '".$mamet['tgl_lahir']."',
												usia = '".$mamet['usia']."',
												no_ktp = '".$mamet['no_ktp']."',
												kredit_tgl = '".$mamet['kredit_tgl']."',
												kredit_jumlah = '".$mamet['kredit_jumlah']."',
												kredit_tenor = '".$mamet['kredit_tenor']."',
												kredit_akhir = '".$mamet['kredit_akhir']."',
												ratebank = '".$mamet['ratebank']."',
												premi = '".$mamet['premi']."',
												ext_premi = '".$mamet['ext_premi']."',
												totalpremi = '".$mamet['totalpremi']."',
												system = 'OLD',
												input_by = '".$q['nm_user']."',
												input_date = now()";

			$querynew = "INSERT INTO fu_ajk_peserta_log 
										SET id_peserta = '".$mamet['id_peserta']."',
												nama = '".$_REQUEST['namadebitur']."',
												gender = '".$mamet['gender']."',
												tgl_lahir = '".$_REQUEST['tgllahir']."',
												usia = '".$_REQUEST['usia']."',
												no_ktp = '".$mamet['no_ktp']."',
												kredit_tgl = '".$_REQUEST['tglakad']."',
												kredit_jumlah = '".$_REQUEST['plafond']."',
												kredit_tenor = '".$_REQUEST['tenor']."',
												kredit_akhir = '".$_REQUEST['tglakhir']."',
												ratebank = '".$mamet['ratebank']."',
												premi '".$_REQUEST['premi']."',
												ext_premi = '".$_REQUEST['extpremi']."',
												totalpremi = '".$_REQUEST['totalpremi']."',
												system = 'NEW',
												input_by = '".$q['nm_user']."'
												input_date = now()";
			
			$database->doQuery($queryold);
			$database->doQuery($querynew);

			$metrefundcn = $database->doQuery('UPDATE fu_ajk_peserta SET nama="'.$_REQUEST['namadebitur'].'",
																		tgl_lahir="'.$_REQUEST['tgllahir'].'",
																		usia="'.$_REQUEST['usia'].'",
																		kredit_tgl="'.$_REQUEST['tglakad'].'",
																		kredit_tenor="'.$_REQUEST['tenor'].'",
																		kredit_akhir="'.$_REQUEST['tglakhir'].'",
																		mppbln="'.$_REQUEST['mppbln'].'",
																		kredit_jumlah="'.$_REQUEST['plafond'].'",
																		premi="'.$_REQUEST['premi'].'",
																		ext_premi="'.$_REQUEST['extpremi'].'",
																		totalpremi="'.$_REQUEST['totalpremi'].'"
												WHERE id="'.$_REQUEST['idp'].'"');
			echo '<center><b>Data debitur telah diedit oleh ' . $q['nm_lengkap'] . '.<br /><meta http-equiv="refresh" content="3;URL=scase.php?sc=eDataDebitur"></b></center>';
		}

		$_scEref = mysql_fetch_array($database->doQuery('SELECT
		fu_ajk_costumer.`name`,
		fu_ajk_polis.nmproduk,
		fu_ajk_polis.typeproduk,
		fu_ajk_peserta.id_peserta,
		fu_ajk_peserta.id,
		fu_ajk_peserta.nama_mitra,
		fu_ajk_peserta.nama,
		fu_ajk_peserta.tgl_lahir,
		fu_ajk_peserta.usia,
		fu_ajk_peserta.kredit_tgl,
		IF(fu_ajk_polis.typeproduk ="SPK", fu_ajk_peserta.kredit_tenor * 12, fu_ajk_peserta.kredit_tenor) AS tenor,
		fu_ajk_peserta.kredit_akhir,
		fu_ajk_peserta.kredit_jumlah,
		fu_ajk_peserta.premi,
		fu_ajk_peserta.ext_premi,
		fu_ajk_peserta.totalpremi,
		fu_ajk_peserta.status_aktif,
		fu_ajk_peserta.status_peserta,
		fu_ajk_peserta.mppbln,
		fu_ajk_peserta.cabang
		FROM fu_ajk_peserta
		INNER JOIN fu_ajk_costumer ON fu_ajk_peserta.id_cost = fu_ajk_costumer.id
		INNER JOIN fu_ajk_polis ON fu_ajk_peserta.id_polis = fu_ajk_polis.id
		WHERE fu_ajk_peserta.id ="'.$_REQUEST['idr'].'"'));
			//echo $_scEref['id'].'<br />';
			//echo $_scEref['nama'].'<br />';
		echo '<form method="POST" action="" class="input-list style-1 smart-green">
				<input type="hidden" name="idp" value="' . $_scEref['id'] . '">
				<table border="0" width="80%" align="center">
				<tr><td colspan="2"><h1>Edit Data Debitur a.n ' . $_scEref['nama'] . '</h1></td></tr>
				<tr><td width="50%" valign="top">
				<label><span>Nama Perusahaan</span><input type="text" name="perusahaan" value="' . $_scEref['name'] . '" size="30" placeholder="Nama Perusahaan" DISABLED></label>
				<label><span>Nama Produk</span><input type="text" name="produk" value="' . $_scEref['nmproduk'] . '" size="30" placeholder="Nama Produk" DISABLED></label>
				<label><span>ID Peserta</span><input type="text" name="idpeserta" value="' . $_scEref['id_peserta'] . '" size="30" placeholder="ID Peserta" DISABLED></label>
				<label><span>Nama Peserta</span><input type="text" name="namadebitur" value="' . $_scEref['nama'] . '" size="30" placeholder="Nama Peserta"></label>
				<label><span>Tanggal Lahir</span><input type="text" name="tgllahir" value="' . $_scEref['tgl_lahir'] . '" class="tanggal" size="30" placeholder="Tanggal Lahir"></label>
				<label><span>Usia</span><input type="text" name="usia" value="' . $_scEref['usia'] . '" size="30" placeholder="Usia" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"/></label>
				</td>
				<td>
				<table border="0" width="100%" align="center" cellpadding="0" cellspacing="0">
				<tr><td><label><span>Tanggal Akad</span><input type="text" name="tglakad" value="' .$_scEref['kredit_tgl']. '" class="tanggal" size="30" placeholder="Tanggal Akhir Asuransi"></label></td>
					<td><label><span>Tanggal Akhir</span><input type="text" name="tglakhir" value="' . $_scEref['kredit_akhir']. '" class="tanggal" size="30" placeholder="Tanggal Akhir Asuransi"></label></td>
				</tr>
				<tr><td><label><span>Tenor Asuransi</span><input type="text" name="tenor" value="' . $_scEref['tenor'] . '" size="30" placeholder="Tenor Asuransi" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"/></label></td>
					<td><label><span>MPP</span><input type="text" name="mppbln" value="' . $_scEref['mppbln'] . '" size="30" placeholder="Plafond" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"/></label></td>
				</tr>
				</table>
				<label><span>Plafond</span><input type="text" name="plafond" value="' . $_scEref['kredit_jumlah'] . '" size="30" placeholder="Plafond" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"/></label>
				<label><span>Premi</span><input type="text" name="premi" value="' . $_scEref['premi'] . '" size="30" placeholder="Premi" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"/></label>
				<label><span>Extra Premi</span><input type="text" name="extpremi" value="' . $_scEref['ext_premi'] . '" size="30" placeholder="Extra Premi" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"/></label>
				<label><span>Total Premi</span><input type="text" name="totalpremi" value="' . $_scEref['totalpremi'] . '" size="30" placeholder="Total Premi" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"/></label>
				</td>
				</tr>
				<tr><td colspan="2"><label><span>&nbsp;</span><input type="submit" name="ope" value="Simpan" class="button" /><input type="hidden" name="ope" value="saveditdebitur" class="button" /></label></td></tr>
				</table></form>';
			;
	break;

    default:
// $_mail = 'gunarso@adonai.co.id';
$_mail = 'gunarso@adonai.co.id';
if ($_REQUEST['ope'] == "sendmail") {
$randompassword = smallRandomPassword("test");
$met_ = $database->doQuery('INSERT INTO fu_ajk_passedit SET pass="'.$randompassword.'", today="'.$datelog.'"');

//SENDMAIL
$message .= '<html><head><title>DN CREATE</title></head><body>
		<table border="0" width="50%" cellpadding="1" cellspacing="3">
		<tr><td colspan="3">To '.$q['nm_user'].'</td></tr>
		<tr><td colspan="3">Password untuk edit data pada hari ini tanggal '.$futgldn.' : <b>'.$randompassword.'</td></tr>
		<tr><td colspan="3">Setiap hari password untuk edit data akan selalu berubah.</td></tr>
	    </table>
	    </body>
	    </html>';
	$mail = new PHPMailer; // call the class
	$mail->IsSMTP();
	$mail->Host = SMTP_HOST; //Hostname of the mail server
	$mail->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
	$mail->SMTPAuth = true; // turn on SMTP authentication
	$mail->Username = SMTP_UNAME; //Username for SMTP authentication any valid email created in your domain
	$mail->Password = SMTP_PWORD; //Password for SMTP authentication
	$mail->AddReplyTo = SMTP_REPLYMAIL; //reply-to address
	$mail->debug = 1;
	$mail->SMTPSecure = "ssl";
	$mail->IsHTML(true); // send as HTML

$mail->SetFrom ("cs@adonai.co.id", "CS"); //From address of the mail
//$mail->Subject = "AJKOnline - DN BARU DARI U/W (".$q['nm_lengkap'].")"; //Subject od your mail
$mail->Subject = "Password edit data"; //Subject od your mail
$mail->AddAddress($_mail, "CS"); //To address who will receive this email
//$mail->AddCC("kepodank@gmail.com");
$mail->MsgHTML($message); //Put your body of the message you can place html code here
//echo $message;
$send = $mail->Send(); //Send the mails
//if($mail->Send()) echo "Message has been sent";	else echo "Failed to sending message";
//SENDMAIL

echo '<center>Password untuk edit data adalah <b>'.$randompassword.'</b> dan telah dikirim ke '.$_mail.', silahkan cek email anda untuk mendapatkan password hari ini.</center><meta http-equiv="refresh" content="10; url=scase.php">';
}

$metPass = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_passedit ORDER BY id DESC'));
/* ---- MENGHILANGKAN NOTIF EMAIL KE PAK GUN - HANSEN ----
if ($datelog != $metPass['today']) {
echo '<form method="POST" action="" class="input-list style-1 smart-green">
	<table border="0" width="75%" align="center"><tr><td>
	<h1>Kirim password untuk edit data</h1>
	<label><span>Email</span><input type="text" name="emailpassedit" value="' . $_mail . '" Disabled></label>
	<label><span>&nbsp;</span><input type="hidden" name="ope" value="sendmail" class="button" /><input type="submit" name="op" value="Kirim" class="button" /></label>
	</form></td></tr></table>';
}else{

if ($datelog == $metPass['today'] AND $metPass['status']=="close") {
if ($_REQUEST['ope']=="cekPassEd") {
$openPassEd = $database->doQuery('UPDATE fu_ajk_passedit SET status="open" WHERE pass="'.$_REQUEST['_passEdit'].'" AND today="'.$datelog.'"');
echo '<center>Status password telah terbuka, edit data sudah bisa dilakukan.</center><meta http-equiv="refresh" content="3; url=scase.php">';
}
echo '<form method="POST" action="" class="input-list style-1 smart-green">
	<table border="0" width="75%" align="center"><tr><td>
	<h1>Password Edit Data</h1>
	<label><span>Password</span><input type="text" name="_passEdit" value="' . $_REQUEST['_passEdit'] . '"></label>
	<label><span>&nbsp;</span><input type="hidden" name="ope" value="cekPassEd" class="button" /><input type="submit" name="op" value="Kirim" class="button" /></label>
	</form></td></tr></table>';
}else{*/
echo '<table border="0" width="100%" cellpadding="1" cellspacing="1">
      <tr><td align="center">
      		<a href="scase.php?sc=eSPK">Edit Data SPK</a> &nbsp;
      		<a href="scase.php?sc=eRefund">Edit Data Refund</a> &nbsp;
      		<a href="scase.php?sc=eAge75">Cek Usia 75 thn</a> &nbsp;
					<a href="scase.php?sc=eDataDebitur">Edit Data Debitur</a> &nbsp;
					<a href="scase.php?sc=eDataDebiturInforce">Edit Data Debitur Inforce</a> &nbsp;
      </td></tr>
      <tr><td align="center">
			<a href="historyedit.txt" title="download data history" target="_blank">Field History Edit dan SPK (em)</a> &nbsp;
			<a href="scase.php?sc=closeED&dt='.$metPass['today'].'&st='.$metPass['status'].'">Tutup Editor</a> &nbsp;
      </td></tr>
      </table>';
/*}
}; ---- MENGHILANGKAN NOTIF EMAIL KE PAK GUN - HANSEN ----*/

} // switch

?>