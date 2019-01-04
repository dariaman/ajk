<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// @copyright 2014
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("../includes/functions.php");
connect();
switch ($_REQUEST['as']) {
	case "aa":
		;
		break;
	case "approverateas":
echo '<table border="0" cellpadding="1" cellspacing="0" width="100%">
     <tr><th width="100%" align="left">Modul Import Rate Premi</font></th><th><a href="ajk_reg_cost.php"><img src="image/back.png" width="20"></a></th></tr>
     </table>';
$met_rate = $database->doQuery('INSERT INTO fu_ajk_ratepremi_as(id_cost, id_polis, id_as, id_polis_as, type, usia, tenor, mpp_s, mpp_e, rate, eff_from, eff_to, filename, input_by, input_date)
								SELECT id_cost, id_polis, id_as, id_polis_as, type, usia, tenor, mpp_s, mpp_e, rate, "'.$futoday.'", "2500-01-30",filename, input_by, input_date
								FROM fu_ajk_ratepremi_as_temp
								WHERE filename="'.$_REQUEST['idf'].'"');
$del_rate = $database->doQuery('DELETE  FROM fu_ajk_ratepremi_as_temp WHERE filename="'.$_REQUEST['idf'].'"');
echo '<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border: solid 1px blue">
	  <tr><td><table width="100%" class="bgcolor1">
		  <tr><td align="left"><img src="image/warning.gif" border="0"></td>
			  <td align="center"><font class="option"><blink>Upload rate asuransi telah berhasil.</blink></td>
			  <td align="right"><img src="image/warning.gif" border="0"></td>
		  </tr>
	  </table>
	  <meta http-equiv="refresh" content="3; url=ajk_setrate_as.php?er=setpremi">';
		;
		break;
	case "delrateas":
		$el = $database->doQuery('DELETE FROM fu_ajk_ratepremi_as_temp WHERE filename="'.$_REQUEST['fileclient'].'"');
		header('location:ajk_setrate_as.php?er=setpremi');
	;
	break;
	default:
echo '<table border="0" cellpadding="1" cellspacing="0" width="100%">
     <tr><th width="100%" align="left">Modul Import Rate Premi Asuransi</font></th></tr>
     </table>';
$metcost = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name DESC');
echo '<form name="f1" method="post" enctype="multipart/form-data" action="">
	  <input type="hidden" name="id_cost" value="'.$metcost['id'].'">
	  <table border="0" cellpadding="1" cellspacing="0" width="100%">
      <tr><td width="40%" align="right">Nama Perusahaan</td><td> : <select name="client" onChange="getProduk(this.value)">
	  	<option value="">---Pilih Perusahaan---</option>';
while($metcost_ = mysql_fetch_array($metcost)) {
echo  '<option value="'.$metcost_['id'].'">'.$metcost_['name'].'</option>';
}
echo '</select></td></tr>
	  <tr><td align="right">Nama Produk</td><td id="divproduk">: <select name="produk"><option value="">-- Pilih Produk --</option></select></td></tr>
	  <tr><td align="right">Nama Asuransi</td><td id="divasuransi">: <select name="asuransi"><option value="">-- Pilih Asuransi --</option></select></td></tr>
	  <tr><td align="right">Polis Asuransi</td><td id="divpolisasuransi">: <select name="polisasuransi"><option value="">-- Pilih Polis Asuransi --</option></select></td></tr>
	  <tr><td align="right">Silakan Pilih File Excel </td><td>: '.$bataskolom.' <input name="userfile" type="file" size="50" onchange="checkfile(this);" ></td></tr>
	  <tr><td align="right">Batas Akhir Baris </td><td>: <input type="text" name="bataskolom" value="'.$bataskolom.'" size="3" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"></td></tr>
	  <tr><td align="center"colspan="2"><input type="hidden" name="re" value="upl_rate_as"><input type="submit" name="upload_rate" value="Import"></td></tr>
	  </table>
	  </form>';

if ($_REQUEST['re']=="upl_rate_as") {
if (!$_REQUEST['client'])  			$error .='<blink><font color=red>Silahkan pilih nama perusahaan. !</font></blink><br>';
if (!$_REQUEST['produk'])  			$error .='<blink><font color=red>Silahkan pilih nama produk. !</font></blink><br>';
if (!$_REQUEST['asuransi'])  		$error .='<blink><font color=red>Silahkan pilih nama asuransi. !</font></blink><br>';
$metAsuransinya = explode("-", $_REQUEST['asuransi']);
if (!$_REQUEST['polisasuransi'])	$error .='<blink><font color=red>Silahkan pilih nama polis asuransi. !</font></blink><br>';
if (!$_REQUEST['bataskolom'])		$error .='<blink><font color=red>Tentukan batas kolom pada file asuransi. !</font></blink><br>';
			if (!$_FILES['userfile']['tmp_name'])  $error .='<blink><font color=red>Silahkan upload file excel anda</font></blink>.';
$allowedExtensions = array("xls","xlsx","csv");
foreach ($_FILES as $file) {
	if ($file['tmp_name'] > '') {
		if (!in_array(end(explode(".",	strtolower($file['name']))),
		$allowedExtensions)) {	$error .='<center><font color=red>'.$file['name'].' <br /><blink><font color=red>File extension tidak diperbolehkan selain file excel!</blink></font></center>';	}
	}
}
$cekratepreminya = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi_as WHERE id_cost="'.$_REQUEST['client'].'" AND
																								 id_polis="'.$_REQUEST['produk'].'" AND
																								 id_as="'.$metAsuransinya[1].'" AND
																								 id_polis_as="'.$_REQUEST['polisasuransi'].'" AND
																								 status="baru" AND del IS NULL'));
	if ($cekratepreminya['id']) {
		$error .='<font color=red>Rate premi asuransi sudah ada, rate tidak bisa di upload.!</font>';
	}
if ($error)
{	echo '<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border: solid 1px red">
			  <tr><td><table width="100%" class="bgcolor1">
			  <tr><td align="left"><img src="image/warning.gif" border="0"></td>
				  <td align="center"><font class="option"><blink>'.$error.'</blink></td>
				  <td align="right"><img src="image/warning.gif" border="0"></td>
			  </tr>
			  </table></td></tr>
			  </table><meta http-equiv="refresh" content="4; url=ajk_setrate_as.php?er=setpremi">';
}
else
{
	echo '<hr>';
	$metAsuransinya = explode("-", $_REQUEST['asuransi']);
	$mametset = mysql_fetch_array($database->doQuery('SELECT fu_ajk_costumer.`name` AS perusahaan,
															 fu_ajk_polis.nmproduk AS produk,
															 fu_ajk_polis.mpptype,
															 fu_ajk_asuransi.`name` AS asuransi,
															 IF (fu_ajk_polis_as.singlerate="Y","Ya (Usia, Tenor dan Rate)","Tidak (Tenor dan Rate)") AS singleratenya,
															 fu_ajk_polis_as.nopol,
															 fu_ajk_polis_as.benefit,
															 fu_ajk_polis_as.singlerate
													FROM fu_ajk_polis_as
													INNER JOIN fu_ajk_costumer ON fu_ajk_polis_as.id_cost = fu_ajk_costumer.id
													INNER JOIN fu_ajk_polis ON fu_ajk_polis_as.nmproduk = fu_ajk_polis.id
													INNER JOIN fu_ajk_asuransi ON fu_ajk_polis_as.id_as = fu_ajk_asuransi.id
													WHERE fu_ajk_polis_as.id_cost = "'.$_REQUEST['client'].'" AND
														  fu_ajk_polis_as.nmproduk = "'.$_REQUEST['produk'].'" AND
														  fu_ajk_polis_as.id_as = "'.$metAsuransinya[1].'" AND
														  fu_ajk_polis_as.id = "'.$_REQUEST['polisasuransi'].'" AND
														  fu_ajk_polis_as.del IS NULL'));

	echo '<form name="f1" method="post" enctype="multipart/form-data" action="">
		  <table width="100%" border="0" cellspacing="3" cellpadding="1">
		  <tr><td width="10%">Nama Perusahaan</td><td>: <b>'.$mametset['perusahaan'].'</b></td></tr>
		  <tr><td>Produk</td><td>: <b>'.$mametset['produk'].'</b></td></tr>
		  <tr><td>Asuransi</td><td>: <b>'.$mametset['asuransi'].'</b></td></tr>
		  <tr><td>Polis Asuransi</td><td>: <b>'.$mametset['nopol'].'</b></td></tr>
		  <tr><td>Single Rate By Usia</td><td>: <b>'.$mametset['singleratenya'].'</b></td></tr>
		  <tr><td>Nama File</td><td>: <b>'.$_FILES['userfile']['name'].'</b></td></tr>
		  <tr><td>Batas Kolom</td><td>: <b>'.$_REQUEST['bataskolom'].' baris</b></td></tr>
		  </table>
		  <table width="30%" border="0" cellspacing="1" cellpadding="1" align="center">';
		$data = new Spreadsheet_Excel_Reader($_FILES['userfile']['tmp_name']);
		$baris = $data->rowcount($sheet_index=0);
		if ($mametset['singlerate']=="T" AND $mametset['mpptype']=="T") {
		echo '<tr><th width="1%">No</th><th>Tenor(bln)</th><th>Rate</th></tr>';
			for ($i=2; $i<=$_REQUEST['bataskolom']; $i++)
			{
				$data1=$data->val($i, 1);		//TENOR BLN/THN
				$data2=$data->val($i, 2);		//RATE
				if ($data1==""){ $error1 ='<font color="red">error</font>'; $dataexcel1=$error1;}else{ $dataexcel1=$data1;}
				if ($data2==""){ $error2 ='<font color="red">error</font>'; $dataexcel2=$error2;}else{ $dataexcel2=$data2;}
				if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
				echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
						<td align="center">'.++$no.'</td>
						<td align="center">'.$dataexcel1.'</td>
						<td align="center">'.$dataexcel2.'</td>
					</tr>';
			}
		}elseif ($mametset['singlerate']=="Y" AND $mametset['mpptype']=="T") {
		echo '<tr><th width="1%">No</th><th>Usia</th><th>Tenor(bln)</th><th>Rate</th></tr>';
			for ($i=2; $i<=$_REQUEST['bataskolom']; $i++)
			{
				$data1=$data->val($i, 1);		//USIA
				$data2=$data->val($i, 2);		//TENOR BLN/THN
				$data3=$data->val($i, 3);		//RATE
				if ($data1==""){ $error1 ='<font color="red">error</font>'; $dataexcel1=$error1;}else{ $dataexcel1=$data1;}
				if ($data2==""){ $error2 ='<font color="red">error</font>'; $dataexcel2=$error2;}else{ $dataexcel2=$data2;}
				if ($data3==""){ $error3 ='<font color="red">error</font>'; $dataexcel3=$error3;}else{ $dataexcel3=$data3;}
				if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
				echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
						<td align="center">'.++$no.'</td>
						<td align="center">'.$dataexcel1.'</td>
						<td align="center">'.$dataexcel2.'</td>
						<td align="center">'.$dataexcel3.'</td>
					</tr>';
			}
		}elseif ($mametset['singlerate']!="" AND $mametset['mpptype']=="Y") {
		echo '<tr><th width="1%">No</th><th>Tenor(bln)</th><th>MPP Mulai</th><th>MPP Akhir</th><th>Rate</th></tr>';
			for ($i=2; $i<=$_REQUEST['bataskolom']; $i++)
			{
				$data1=$data->val($i, 1);		//TENOR
				$data2=$data->val($i, 2);		//MPP START
				$data3=$data->val($i, 3);		//MPP END
				$data4=$data->val($i, 4);		//RATE
				if ($data1==""){ $error1 ='<font color="red">error</font>'; $dataexcel1=$error1;}else{ $dataexcel1=$data1;}
				if ($data2==""){ $error2 ='<font color="red">error</font>'; $dataexcel2=$error2;}else{ $dataexcel2=$data2;}
				if ($data3==""){ $error3 ='<font color="red">error</font>'; $dataexcel3=$error3;}else{ $dataexcel3=$data3;}
				if ($data4==""){ $error4 ='<font color="red">error</font>'; $dataexcel4=$error4;}else{ $dataexcel4=$data4;}
				if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
				echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
							<td align="center">'.++$no.'</td>
							<td align="center">'.$dataexcel1.'</td>
							<td align="center">'.$dataexcel2.'</td>
							<td align="center">'.$dataexcel3.'</td>
							<td align="center">'.$dataexcel4.'</td>
						</tr>';
			}
		}else{
		}
		if ($error1 OR $error2 OR $error3 OR $error4) {
		echo '<tr><td colspan="6" align="center"><a href="ajk_setrate_as.php?as=delrateas&fileclient='.$_FILES['userfile']['name'].'"><img src="image/deleted.png" width="32"><br />[Batal]</a></td></tr>';
		}else{
		if ($mametset['singlerate']=="T" AND $mametset['mpptype']=="T") {
			for ($i=2; $i<=$_REQUEST['bataskolom']; $i++)
			{
				$data1=$data->val($i, 1);		//TENOR BLN/THN
				$data2=$data->val($i, 2);		//RATE
		$met_rate = $database->doQuery('INSERT INTO fu_ajk_ratepremi_as_temp SET id_cost="'.$_REQUEST['client'].'",
																				 id_polis="'.$_REQUEST['produk'].'",
																				 id_as="'.$metAsuransinya[1].'",
																				 id_polis_as="'.$_REQUEST['polisasuransi'].'",
																				 tenor="'.$data1.'",
																				 rate="'.$data2.'",
																				 type="'.$mametset['benefit'].'",
																				 filename="'.$_FILES['userfile']['name'].'",
																				 input_by="'.$q['nm_lengkap'].'",
																				 input_date="'.$futgl.'" ');
			}
		}elseif ($mametset['singlerate']=="Y" AND $mametset['mpptype']=="T") {
			for ($i=2; $i<=$_REQUEST['bataskolom']; $i++)
			{
				$data1=$data->val($i, 1);		//USIA
				$data2=$data->val($i, 2);		//TENOR BLN/THN
				$data3=$data->val($i, 3);		//RATE
				$met_rate = $database->doQuery('INSERT INTO fu_ajk_ratepremi_as_temp SET id_cost="'.$_REQUEST['client'].'",
																				 id_polis="'.$_REQUEST['produk'].'",
																				 id_as="'.$metAsuransinya[1].'",
																				 id_polis_as="'.$_REQUEST['polisasuransi'].'",
																				 usia="'.$data1.'",
																				 tenor="'.$data2.'",
																				 rate="'.$data3.'",
																				 type="'.$mametset['benefit'].'",
																				 filename="'.$_FILES['userfile']['name'].'",
																				 input_by="'.$q['nm_lengkap'].'",
																				 input_date="'.$futgl.'" ');
			}
		}elseif ($mametset['singlerate']!="" AND $mametset['mpptype']=="Y") {
			for ($i=2; $i<=$_REQUEST['bataskolom']; $i++)
			{
				$data1=$data->val($i, 1);		//TENOR
				$data2=$data->val($i, 2);		//MPP START
				$data3=$data->val($i, 3);		//MPP END
				$data4=$data->val($i, 4);		//RATE
				$met_rate = $database->doQuery('INSERT INTO fu_ajk_ratepremi_as_temp SET id_cost="'.$_REQUEST['client'].'",
																				 id_polis="'.$_REQUEST['produk'].'",
																				 id_as="'.$metAsuransinya[1].'",
																				 id_polis_as="'.$_REQUEST['polisasuransi'].'",
																				 tenor="'.$data1.'",
																				 mpp_s="'.$data2.'",
																				 mpp_e="'.$data3.'",
																				 rate="'.$data4.'",
																				 type="'.$mametset['benefit'].'",
																				 filename="'.$_FILES['userfile']['name'].'",
																				 input_by="'.$q['nm_lengkap'].'",
																				 input_date="'.$futgl.'" ');
			}
		}else{

		}
		}
	echo '<tr><td colspan="6" align="center"><a href="ajk_setrate_as.php?as=delrateas&fileclient='.$_FILES['userfile']['name'].'" title="Batal upload rate asuransi"><img src="image/deleted.png" width="32"></a> &nbsp;
								    		 <a href="ajk_setrate_as.php?as=approverateas&idf='.$_FILES['userfile']['name'].'" title="Approve upload rate asuransi"><img src="image/save.png" width="32"></a></td>
		  </tr>';
	echo '</table></form> ';
	}
}
		;
} // switch

?>
<script language="javascript" type="text/javascript">
// Roshan's Ajax dropdown code with php
// This notice must stay intact for legal use
// Copyright reserved to Roshan Bhattarai - nepaliboy007@yahoo.com
// If you have any problem contact me at http://roshanbh.com.np
function getXMLHTTP() { //fuction to return the xml http object
	var xmlhttp=false;
	try{ xmlhttp=new XMLHttpRequest();	}
	catch(e)	{	try{ xmlhttp= new ActiveXObject("Microsoft.XMLHTTP"); }
		catch(e){	try{ xmlhttp = new ActiveXObject("Msxml2.XMLHTTP"); }	catch(e1){ xmlhttp=false; }	}
	}	return xmlhttp;
}

function getProduk(idclient) {
	var strURL="javascript/listdata.php?list=listproduk&client="+idclient;
	var req = getXMLHTTP();
	if (req) {
		req.onreadystatechange = function() {
			if (req.readyState == 4) {
				if (req.status == 200) {	document.getElementById('divproduk').innerHTML=req.responseText;
				} else {	alert("There was a problem while using XMLHTTP:\n" + req.statusText);	}
			}
		}
		req.open("GET", strURL, true);
		req.send(null);
	}
}

function getAsuransi(idclient, idproduk) {
	var strURL="javascript/listdata.php?list=listasuransi&client="+idclient+"&produk="+idproduk;
	var req = getXMLHTTP();
	if (req) {
		req.onreadystatechange = function() {
			if (req.readyState == 4) {
				if (req.status == 200) {	document.getElementById('divasuransi').innerHTML=req.responseText;
				} else {	alert("There was a problem while using XMLHTTP:\n" + req.statusText);	}
			}
		}
		req.open("GET", strURL, true);
		req.send(null);
	}
}

function getPolis(idclient, idproduk, idasuransi) {
	var strURL="javascript/listdata.php?list=listpolisasuransi&client="+idclient+"&produk="+idproduk+"&asuransi="+idasuransi;
	var req = getXMLHTTP();
	if (req) {
		req.onreadystatechange = function() {
			if (req.readyState == 4) {
				if (req.status == 200) {	document.getElementById('divpolisasuransi').innerHTML=req.responseText;
				} else {	alert("There was a problem while using XMLHTTP:\n" + req.statusText);	}
			}
		}
		req.open("GET", strURL, true);
		req.send(null);
	}
}
</script>