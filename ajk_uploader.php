	<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("includes/functions.php");
connect();
if (isset($_SESSION['nm_user'])) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));}
switch ($_REQUEST['er']) {
case "parse_spaj":
	$fu = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$_REQUEST['idcost'].'"'));
	//$fupolis = mysql_fetch_array($database->doQuery('SELECT id,nopol,nmproduk, mpptype, mppbln FROM fu_ajk_polis WHERE id="'.$_REQUEST['idpolis'].'"'));
	$fupolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$_REQUEST['idpolis'].'" AND del IS NULL'));

//	$_REQUEST['idcost'] = $_POST['idcost'];				if (!$_REQUEST['idcost'])  $error .='Silahkan pilih nomor polis<br />.';
	$_REQUEST['idpolis'] = $_POST['idpolis'];			if (!$_REQUEST['idpolis'])  $error .='Silahkan pilih nomor polis<br />.';
	$_REQUEST['bataskolom'] = $_POST['bataskolom'];		if (!$_REQUEST['bataskolom'])  $error .='Silahkan tentukan batas kolom file<br />.';
		if (!$_FILES['userfile']['tmp_name'])  $error .='Silahkan upload file excel anda<br />.';
	$allowedExtensions = array("xls");
	foreach ($_FILES as $file) {
		if ($file['tmp_name'] > '') {
			if (!in_array(end(explode(".",	strtolower($file['name']))), $allowedExtensions)) {
				die('<center><font color=red><br />'.$file['name'].' <br /><blink>File extension tidak diperbolehkan selain excel!</blink></font><br/>'.'<a href="ajk_uploader.php?er=spaj">'.'Silahkan Upload kembali dengaan format file <b>.xls</b></a></center>');
			}
		}
	}
		if ($error)
		{	echo '<blink><center><font color=red>'.$error.'</font></blink><a href="ajk_uploader.php?er=spaj">'.'&lt;&lt Go Back</a></center>';	}

		else
		{
echo '<form method="post" action="ajk_uploader.php?r=approveuser" onload ="onbeforeunload">
	  <table border="0" width="100%" cellpadding="1" cellspacing="1" style="border: solid 3px #DEDEDE"  bgcolor="#bde0e6">
		<tr><td colspan="3"><input type="hidden" name="idcostumer" value="'.$fu['id'].'">Nama Perusahaan</td><td colspan="24">: <b>'.$fu['name'].'</b></td></tr>
		<tr><td colspan="3"><input type="hidden" name="idpolis" value="'.$fupolis['id'].'">Nama Produk</td><td colspan="24">: <b>'.$fupolis['nmproduk'].' ('.$fupolis['nopol'].')</b></td></tr>
		<tr><td colspan="3"><input type="hidden" name="bataskolom" value="'.$_REQUEST['bataskolom'].'"><input type="hidden" name="namafileexl" value="'.$_FILES['userfile']['name'].'">Nama File</td><td colspan="24">: <b>'.$_FILES['userfile']['name'].'</b></td></tr>
		<tr><th width="1%" rowspan="2">No</th>
			<th width="1%" rowspan="2">Nama Mitra</th>
			<th width="1%" rowspan="2">Regional</th>
			<th width="5%" rowspan="2">Cabang</th>
			<th rowspan="2">Nama Tertanggung</th>
			<th width="5%" colspan="3">Tanggal Lahir</th>
			<th width="5%" rowspan="2">Jenis Kelamin</th>
			<th width="5%" rowspan="2">Uang Asuransi</th>
			<th width="5%" colspan="3">Mulai Asuransi</th>
			<th width="1%" rowspan="2">Tenor<br />(thn)</th>
			<th width="1%" rowspan="2">Tenor<br />(bln)</th>
			<th width="1%" rowspan="2">Usia</th>
			<th width="1%" rowspan="2">Tarif Premi</th>
			<th width="5%" rowspan="2">Premi Standar</th>
			<th width="5%" rowspan="2">EM(%)</th>
			<th width="5%" rowspan="2">Premi Sekaligus</th>
			<th width="1%" rowspan="2">Medical</th>
			<th width="8%" rowspan="2">Produk</th>
			<th width="1%" rowspan="2">MPP(bln)</th>
			<th width="8%" rowspan="2">Keterangan</th>
			<th width="1%" rowspan="2">Nomor Formulir</th>
		</tr>
		<tr><th>Hari</th><th>Bulan</th><th>Tahun</th><th>Hari</th><th>Bulan</th><th>Tahun</th></tr>';
			$fileUploadAdonai = $timedeklarasi.'-'.$_FILES['userfile']['name'];
			$data = new Spreadsheet_Excel_Reader($_FILES['userfile']['tmp_name']);
			$baris = $data->rowcount($sheet_index=0);											//MEMBACA JUMLAH BARIS DATA EXCEL

			for ($i=10; $i<=$_REQUEST['bataskolom']; $i++)
			{
				$data1=$data->val($i, 1);		//NO
				$data2=$data->val($i, 2);		//NAMA MITRA
				$data3=$data->val($i, 3);		//CABANG
				$data4=$data->val($i, 4);		//NAMA TERTANGGUNG
				$data5=$data->val($i, 5);		//TANGGAL LAHIR (HARI)
				$data6=$data->val($i, 6);		//TANGGAL LAHIR (BLN)
				$data7=$data->val($i, 7);		//TANGGAL LAHIR (THN)
				$data8=$data->val($i, 8);		//UANG ASURANSI
				$data9=$data->val($i, 9);		//MULAI ASURANSI (HARI)
				$data10=$data->val($i, 10);		//MULAI ASURANSI (BLN)
				$data11=$data->val($i, 11);		//MULAI ASURANSI (THN)
				$data12=$data->val($i, 12);		//MA THN
				$data13=$data->val($i, 13);		//MA BLN
				$data14=$data->val($i, 14);		//USIA
				$data15=$data->val($i, 15);		//RATE (TARIF PREMI)
				$data16=$data->val($i, 16);		//PREMI
				$data17=$data->val($i, 17);		//EXTRA PREMI
				$data18=$data->val($i, 18);		//PREMI SEKALIGUS
				$data19=$data->val($i, 19);		//MEDICAL
				$data20=$data->val($i, 20);		//PRODUK
				$data21=$data->val($i, 21);		//KETERANGAN
				$data22=$data->val($i, 22);		//MPP
				$data23=$data->val($i, 23);		//APABILA PERCEPATAN TABLET
				$data24=$data->val($i, 24);		//JENIS KELAMIN

if ($q['cabang']!="PUSAT") {	$cekUserCabang = ' AND name="'.$q['cabang'].'"';	}else{	$cekUserCabang = ' AND name="'.$data3.'"';	}

				//VALIDASI DATA UPLOAD//
if ($data2 !="") {
	$cekmitra = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_grupproduk WHERE id_cost="'.$q['id_cost'].'" AND nmproduk="'.strtoupper($data2).'"'));
	if (!$cekmitra['nmproduk']) {	$error ='<font color="red" title="Nama mitra tidak terdaftar">error</font>'; $dataexcel2=$error;	}
	else{	$dataexcel2=$data2;	}
}else{
	$cekmitra = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_grupproduk WHERE id_cost="'.$q['id_cost'].'" AND nmproduk="BUKOPIN"'));
	$dataexcel2="BUKOPIN";
}

				if ($data3==""){ $error ='<font color="red" title="Kolom cabang tidak boleh kosong">error</font>'; $dataexcel3=$error;}else{ $dataexcel3=$data3;}
				if ($data4==""){ $error ='<font color="red" title="Kolom nama tertanggung tidak boleh kosong">error</font>'; $dataexcel4=$error;}else{ $dataexcel4=$data4;}

				if ($data5==""){ $error ='<font color="red">error</font>'; $dataexcel5=$error;}
				else{
					if(!is_numeric($data5)){$error ='<font color="red" title="Kolom hari pada tanggal lahir harus angka">error</font>'; $dataexcel5=$error;}else{ $dataexcel5=$data5;}		//VALIDASI HARI
					if(strlen($data5 > 31 )){$error ='<font color="red">error</font>'; $dataexcel5=$error;}else{ $dataexcel5=$data5;}		//VALIDASI HARI
				//	$dataexcel5=$data5;
				}

				if ($data6==""){ $error ='<font color="red">error</font>'; $dataexcel6=$error;}
				else{
					if(!is_numeric($data6)){$error ='<font color="red" title="Kolom bulan pada tanggal lahir harus angka">error</font>'; $dataexcel6=$error;}else{ $dataexcel6=$data6;}		//VALIDASI BULAN
					if(strlen($data6 > 12 )){$error ='<font color="red">error</font>'; $dataexcel6=$error;}else{ $dataexcel6=$data6;}		//VALIDASI BULAN
				//	$dataexcel6=$data6;
				}
				if ($data7==""){ $error ='<font color="red">error</font>'; $dataexcel7=$error;}
				else{
					if(!is_numeric($data7)){$error ='<font color="red" title="Kolom tahun pada tanggal lahir harus angka">error</font>'; $dataexcel7=$error;}else{ $dataexcel7=$data7;}		//VALIDASI TAHUN
					if($data7 < 1900 OR $data7 >= $dateY){$error ='<font color="red">error</font>'; $dataexcel7=$error;}else{ $dataexcel7=$data7;}	//VALIDASI TAHUN LAHIR
				//	$dataexcel7=$data7;
				}

				if ($data8==""){ $error ='error'; $dataexcel8=$error;}
				else{
					$asting = array(" ", ",", ".", "*", "-");
					$replace = array('', '', '', '', '');

					$malestr = str_replace($asting, $replace, $data8);
					//echo $malestr;
					$dataexcel8=duit($malestr);	$dataexcel8med = $malestr;
					if ($dataexcel8med > $fupolis['up_max'] ) {
						$error ='<a title="Nilai Plafond melebihi batas maksimum produk"><font color="red">error</font></a>'; $dataexcel8=$error;
					}elseif ($dataexcel8 <= 0 ) {
						$error ='<a title="Format cell pada kolom excel tidak sesuai"><font color="red">error</font></a>'; $dataexcel8=$error;
					}else{
						$dataexcel8=duit($malestr);	$dataexcel8med = $malestr;
					}
					//$_titikpos_ = strpos($data8, ".");if ($_titikpos) { $_titikposnya = str_replace(".", "", $data8); $dataexcel8=$_titikposnya;}else{ $dataexcel8=$data8;}
					//$_komapos = strpos($data8, ",");	if ($_komapos)  { $_komaposnya = str_replace(",", "", $data8); $dataexcel8=$_komaposnya;}else{ $dataexcel8=$data8;}
					//$_bintang = strpos($data8, ",");	if ($_bintang)  { $_bintangnya = str_replace("*", "", $data8); $dataexcel8=$_bintangnya;}else{ $dataexcel8=$data8;}
				//	$dataexcel8=$data8;
				}

				if ($data9==""){ $error ='<font color="red">error</font>'; $dataexcel9=$error;}
				else{
					if(!is_numeric($data9)){$error ='<font color="red" title="Kolom hari pada tanggal akad harus angka">error</font>'; $dataexcel9=$error;}else{ $dataexcel9=$data9;}		//VALIDASI HARI
					if(strlen($data9 > 31 )){$error ='<font color="red">error</font>'; $dataexcel9=$error;}else{ $dataexcel9=$data9;}		//VALIDASI HARI
				//	$dataexcel9=$data9;
				}
				if ($data10==""){ $error ='<font color="red">error</font>'; $dataexcel10=$error;}
				else{
					if(!is_numeric($data10)){$error ='<font color="red" title="Kolom bulan pada tanggal akad harus angka">error</font>'; $dataexcel10=$error;}else{ $dataexcel10=$data10;}		//VALIDASI BULAN
					if(strlen($data10 > 12 )){$error ='<font color="red">error</font>'; $dataexcel10=$error;}else{ $dataexcel10=$data10;}		//VALIDASI BULAN
				//	$dataexcel10=$data10;
				}

				if ($data11==""){ $error ='<font color="red">error</font>'; $dataexcel11=$error;}
				else{
					if(!is_numeric($data11)){$error ='<font color="red" title="Kolom tahun pada tanggal akad harus angka">error</font>'; $dataexcel11=$error;}else{ $dataexcel11=$data11;}					//VALIDASI TAHUN
					if($data11 < 2010 OR $data11 > $dateY){$error ='<font color="red">error</font>'; $dataexcel11=$error;}else{ $dataexcel11=$data11;}		//VALIDASI TAHUN KREDIT
				//	$dataexcel11=$data11;
				}


				// new for split mpp 20170303
				if ($data12 =="") { $data12 = 0;}
				if ($data13 =="") { $data13 = 0;}


				if ($fupolis['mpptype']=="Y") {
					if($data12 == 0 and  $data13 == 0){
						$error ='<font color="red" title="Tenor plafond tidak boleh kosong">error</font>';
						$dataexcel12=$error;
						$dataexcel13=$error;
					}else{
						if ($data13 !="" AND $data13 >=1) {
							if ($data13 <= 12) {
								$mppgp = 1;
							}elseif($data13 >= 13 AND $data13 <= 24) {
								$mppgp = 2;
							}elseif($data13 >= 25 AND $data13 <= 36) {
								$mppgp = 3;
							}else{
								$mppgp = 0;
							}
						$met_tenor = $data12 + $mppgp;
						}
						else{
						$met_tenor = $data12;
						}

						if ($data22 =="") {
							$bulanmpp = 0;
						}else{
							$bulanmpp = $data22;
						}
						$cekratepolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_polis="'.$fupolis['id'].'" AND tenor="'.$met_tenor.'" AND '.$bulanmpp.' BETWEEN mpp_s AND mpp_e AND status="baru" AND del IS NULL'));

						if ($met_tenor != $cekratepolis['tenor']) {$error ='<font color="red">error</font>';	$dataexcel12=$error;	}else{	$dataexcel12=$met_tenor;	}			//VALIDASI CEK TENOR DENGAN SETTING RATE POLIS
						$dataexcel12=$data12;
					}
				}else{
					if ($data12==""){ $error ='<font color="red" title="Tenor plafond tidak boleh kosong">error</font>'; $dataexcel12=$error;

					}else{
						$met_tenor = $data12 * 12 + $data13;
						$cekratepolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_polis="'.$fupolis['id'].'" AND tenor="'.$met_tenor.'" AND status="baru" AND del IS NULL'));

						if ($met_tenor != $cekratepolis['tenor']) {$error ='<font color="red">error</font>';	$dataexcel12=$error;	}else{	$dataexcel12=$met_tenor;	}			//VALIDASI CEK TENOR DENGAN SETTING RATE POLIS
						$dataexcel12=$data12;
					}
				}





				/* COMMENT BECAUSE SPLIT MPP 20170303
				if ($data12==""){ $error ='<font color="red" title="Tenor plafond tidak boleh kosong">error</font>'; $dataexcel12=$error;}
				else{
					if ($fupolis['mpptype']=="Y") {
						$met_tenor = $data12;
						$cekratepolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_polis="'.$fupolis['id'].'" AND tenor="'.$met_tenor.'" AND '.$data22.' BETWEEN mpp_s AND mpp_e AND status="baru" AND del IS NULL'));	//VALIDASI CEK TENOR DENGAN SETTING RATE POLIS
					}else{
						$met_tenor = $data12 * 12 + $data13;
						$cekratepolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_polis="'.$fupolis['id'].'" AND tenor="'.$met_tenor.'" AND status="baru" AND del IS NULL'));	//VALIDASI CEK TENOR DENGAN SETTING RATE POLIS
					}
					if ($met_tenor != $cekratepolis['tenor']) {$error ='<font color="red">error</font>';	$dataexcel12=$error;	}else{	$dataexcel12=$met_tenor;	}			//VALIDASI CEK TENOR DENGAN SETTING RATE POLIS

					$dataexcel12=$data12;
				}
				*/




				//if ($data13==""){ $error ='<font color="red">error</font>'; $dataexcel13=$error;}else{ $dataexcel13=$data13;}
				//if ($data14==""){ $error ='<font color="red">error</font>'; $dataexcel14=$error;}else{ $dataexcel14=$data14;}
				if ($data15=="" OR $data15==0){ $error ='<font color="red" title="rate tidak sesuai">error</font>'; $dataexcel15=$error;}else{	$dataexcel15=$data15;	}
				if ($data16==""){ $error ='<font color="red">error</font>'; $dataexcel16=$error;}
				else{
					$cekPremiReplace = array(".", ",", "*");
					$cekPremiReplace_ = ("");
					$dataexcel16_ = str_replace($cekPremiReplace,$cekPremiReplace_ , $data16);
					$dataexcel16 = duit($dataexcel16_);
/* 30 08 2016
					$titikpos_ = strpos($data16, ".");	if ($titikpos_)	{ $titikposnya_ = str_replace(".", "", $data16); $dataexcel16=$titikposnya_;}else{ $dataexcel16=$data16;}
					$komapos_ = strpos($data16, ",");	if ($komapos_)	{ $komaposnya_  = str_replace(",", "", $data16); $dataexcel16=$komaposnya_;}else{ $dataexcel16=$data16;}
					$bintang_ = strpos($data16, "*");	if ($bintang_)	{ $bintangnya_  = str_replace("*", "", $data16); $dataexcel16=$bintangnya_;}else{ $dataexcel16=$data16;}
*/
					//
				//	$dataexcel16=$data16;
				}
				//if ($data17==""){ $error ='<font color="red">error</font>'; $dataexcel17=$error;}else{ $dataexcel17=$data17;}
				if ($data18==""){ $error ='<font color="red">error</font>'; $dataexcel18=$error;}
				else{
/* 30 08 2016
					$titikpos__ = strpos($data18, ".");	if ($titikpos__) { $titikposnya__ = str_replace(".", "", $data18); $dataexcel18=$titikposnya__;}else{ $dataexcel18=$data18;}
					$komapos__ = strpos($data18, ",");	if ($komapos__) { $komaposnya__ = str_replace(",", "", $data18); $dataexcel18=$komaposnya__;}else{ $dataexcel18=$data18;}
					$bintang__ = strpos($data18, ",");	if ($bintang__) { $bintangnya__ = str_replace("*", "", $data18); $dataexcel18=$bintangnya__;}else{ $dataexcel18=$data18;}
*/
					$cekPremiReplace = array(".", ",", "*");
					$cekPremiReplace_ = ("");
					$dataexcel18_ = str_replace($cekPremiReplace,$cekPremiReplace_ , $data18);
					$dataexcel18 = duit($dataexcel18_);
				//	$dataexcel18=$data18;
				}
				if ($data19==""){ $error ='<font color="red">error</font>'; $dataexcel19=$error;}else{ $dataexcel19=$data19;}
				/*
				$cekdatareg = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE id_cost="'.$fu['id'].'" AND name="'.$data13.'"'));			//VALIDASI REGIONAL
				if ($data13 != $cekdatareg['name']) {$error ='<font color="red">error</font>';	$dataexcel13=$error;	}else{	$dataexcel13=$data13;	}			//VALIDASI REGIONAL

				$cekdataarea = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_area WHERE id_cost="'.$fu['id'].'" AND name="'.$data14.'"'));				//VALIDASI AREA
				if ($data14 != $cekdataarea['name']) {$error ='<font color="red">error</font>';	$dataexcel14=$error;	}else{	$dataexcel14=$data14;	}			//VALIDASI AREA

				//VALIDASI DATA UPLOAD//
				*/

				if ($data5 <= 9) { $data5_ = '0'.$data5;	}else{	$data5_ = $data5;}
				if ($data6 <= 9) { $data6_ = '0'.$data6;	}else{	$data6_ = $data6;}
				$datatgllahirnya = $data7.'-'.$data6_.'-'.$data5_;

				if ($data9 <= 9) { $data9_ = '0'.$data9;	}else{	$data9_ = $data9;}
				if ($data10 <= 9) { $data10_ = '0'.$data10;	}else{	$data10_ = $data10;}
				$datatglkreditnya = $data11.'-'.$data10_.'-'.$data9_;

	//CEK RELASI WILAYAH

/*
$cekdatacab = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id_cost="'.$fu['id'].'" '.$cekUserCabang.''));			//VALIDASI CABANG
	if ($data3 != $cekdatacab['name']) {$error ='<font color="red">error</font>';	$dataexcel3=$error;	}else{	$dataexcel3=$data3;	}				//VALIDASI CABANG
*/

	//CEK RELASI WILAYAH
	/*
	$cekdatacab = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id_cost="'.$fu['id'].'" '.$cekUserCabang.''));			//VALIDASI CABANG
	if (strtoupper($data3) != $cekdatacab['name']) {
		$cekCentral = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE name="'.strtoupper($data3).'" AND centralcbg="'.$cekdatacab['id'].'"'));
		if (strtoupper($data3) == $cekCentral['name']) {
		$dataexcel3=strtoupper($data3);
		}else{
		$error ='<font color="red" title="Nama cabang tidak sesuai">error</font>';	$dataexcel3=$error;
		}
	}else{	$dataexcel3=strtoupper($data3);	}				//VALIDASI CABANG
	*/
	$dataexcel3=strtoupper($data3);	

	$cekdatawilayah = mysql_fetch_array($database->doQuery('SELECT * FROM v_wilayah WHERE id_cost="'.$_REQUEST['idcost'].'" AND cabang="'.strtoupper($data3).'" AND delCab IS NULL'));
	//if ($cekdatawilayah['regional']!=$data13) {$error ='<font color="red">error</font>'; $dataexcel13=$error;}else{ $dataexcel13=$data13;}
	//if ($cekdatawilayah['area']!=$data14) {$error ='<font color="red">error</font>'; $dataexcel14=$error;}else{ $dataexcel14=$data14;}
	//if ($cekdatawilayah['cabang']!=$data15) {$error ='<font color="red">error</font>'; $dataexcel15=$error;}else{ $dataexcel15=$data15;}
	//CEK RELASI WILAYAH

	//CEK DATA DOUBLE UPLOAD DAN STATUS PEMABAYARAN
	//$metdouble = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE nama="'.$data4.'" AND tgl_lahir="'.$datatgllahirnya.'" AND kredit_tgl="'.$datatglkreditnya.'" AND kredit_jumlah="'.$dataexcel8.'" AND kredit_tenor="'.$met_tenor.'" AND status_peserta IS NULL AND del IS NULL'));
	$metdouble = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_cost="'.$q['id_cost'].'" AND
																						  nama="'.strtoupper($data4).'" AND
																						  tgl_lahir="'.$datatgllahirnya.'" AND
																						  kredit_tgl="'.$datatglkreditnya.'" AND
																						  kredit_jumlah="'.$dataexcel8med.'" AND
																						  status_peserta IS NULL AND
																						  (status_aktif ="Inforce" OR status_aktif ="Approve") AND
																						  cabang="'.strtoupper($data3).'" AND
																						  del IS NULL'));
	if ($metdouble['id_dn']!="" AND $metdouble['status_bayar']=="1") {
		$ceknomor_dn = mysql_fetch_array($database->doQuery('SELECT id, dn_kode FROM fu_ajk_dn WHERE id="'.$metdouble['id_dn'].'"'));
		$error ='<font color="red"><a title="data sudah pernah diinput, apabila anda yakin akan melakukan penutupan tersebut silahkan menghubungi PT. Adonai">Data sudah pernah di upload ('.$ceknomor_dn['dn_kode'].')</font>'; $dataexcel4=$data4.'<br />'.$error;
	}
	elseif ($metdouble['id_dn']!="" AND $metdouble['status_bayar']=="0") {
		$error ='<font color="red">Data Unpaid ('.$metdouble['nama'].' - '._convertDate($metdouble['tgl_lahir']).')</font>'; $dataexcel4=$error;
	}elseif ($metdouble['id_dn']=="" AND $metdouble['id']) {
		$error ='<font color="red">'.$data4.'<br />Data Double belum dibuat data DN</font>'; $dataexcel4=$error;
	}
	else	{	$dataexcel4=$data4;	}
	//CEK DATA DOUBLE UPLOAD DAN STATUS PEMABAYARAN

//CEK PRODUK
$metCekProduk = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$fupolis['id'].'" AND del IS NULL'));
if ($metCekProduk['nmproduk']== strtoupper($data20)) {	$dataexcel20=$data20;	}else{	$error ='<font color="red" title="Nama produk tidak sesuai">Error'; $dataexcel20=$error;	}
//CEK PRODUK

//CEK DOUBLE UPLOAD
//$cekValDouble = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta_tempf WHERE id_cost="'.$q['id_cost'].'" AND id_polis="'.$fupolis['id'].'" AND nama="'.$data4.'" AND tgl_lahir="'.$datatgllahirnya.'" AND kredit_jumlah="'.$data8.'" AND cabang="'.$data3.'"'));
$cekValDouble = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta_tempf WHERE id_cost="'.$q['id_cost'].'" AND nama="'.$data4.'" AND tgl_lahir="'.$datatgllahirnya.'" AND kredit_tgl="'.$datatglkreditnya.'" AND kredit_jumlah="'.$dataexcel8med.'" AND cabang="'.strtoupper($data3).'"'));
if ($cekValDouble['id_temp']) {	$error ='<font color="red"> (Double Upload)</font>'; $dataexcelDbl=$error;	}else{	$dataexcelDbl ='';	}
//CEK DOUBLE UPLOAD

//CEK PRODUK MPP
	if ($fupolis['mpptype']=="Y") {
		if ($data22==""){ $error ='<font color="red" title="Masukan jumlah bulan MPP">error</font>'; $dataexcel22=$error;}
		else{
			if ($data22 > $fupolis['mppbln_max']) {	$error ='<font color="red" title="Jumlah bulan MPP melewati batas bulan setup produk">error</font>'; $dataexcel22=$error;	}
			else{	$dataexcel22=$data22;	}
		}
	}else{
		if ($data22!=""){ $error ='<font color="red" title="Data debitur bukan Masa Pra Pensiun">error</font>'; $dataexcel22=$error;}
		else{
		$dataexcel22=$data22;
		}
	}
	//CEK PRODUK MPP

/*CEK PERCEPATAN TABLET	07072016
if ($fupolis['mpptype']=="Y") {
	$metPerc = mysql_fetch_array($database->doQuery('SELECT
	fu_ajk_spak_form.id,
	fu_ajk_spak_form.idcost,
	fu_ajk_spak_form.idspk,
	fu_ajk_spak_form.noidentitas,
	fu_ajk_spak_form.nama,
	fu_ajk_spak_form.jns_kelamin,
	fu_ajk_spak_form.dob,
	fu_ajk_spak_form.tgl_asuransi,
	fu_ajk_spak_form.tenor,
	fu_ajk_spak_form.tgl_akhir_asuransi,
	fu_ajk_spak_form.mpp,
	fu_ajk_spak_form.nopermohonan,
	fu_ajk_spak_form.plafond,
	fu_ajk_spak_form.x_premi,
	fu_ajk_spak_form.x_usia,
	fu_ajk_spak_form.filefotodebitursatu,
	fu_ajk_spak_form.filefotoktp,
	fu_ajk_regional.`name` AS regional,
	fu_ajk_area.`name` AS area,
	fu_ajk_cabang.`name` AS cabang,
	fu_ajk_spak.spak,
	fu_ajk_spak.`status`
	FROM fu_ajk_spak_form
	INNER JOIN fu_ajk_cabang ON fu_ajk_spak_form.cabang = fu_ajk_cabang.id
	INNER JOIN fu_ajk_regional ON fu_ajk_cabang.id_reg = fu_ajk_regional.id
	INNER JOIN fu_ajk_area ON fu_ajk_cabang.id_area = fu_ajk_area.id
	INNER JOIN fu_ajk_spak ON fu_ajk_spak_form.idspk = fu_ajk_spak.id
	WHERE fu_ajk_spak_form.nama = "'.$data4.'" AND
		  fu_ajk_spak_form.dob = "'.$datatgllahirnya.'" AND
		  fu_ajk_spak_form.plafond = "'.$dataexcel8med.'" AND
		  fu_ajk_cabang.`name` = "'.strtoupper($data3).'" AND
		  fu_ajk_spak.status="Aktif"'));
	if (!$metPerc['spak']) {
		$error ='<font color="red" title="Nomor formulir tidak sesuai dengan data tablet">error</font>'; $noformpercepatan=$error;
	}else{
		if ($metPerc['spak'] AND $data23=="") {
			$error ='<font color="red" title="Nomor formulir percepatan tidak boleh kosong">error</font>'; $noformpercepatan=$error;
		}elseif ($metPerc['spak'] AND $data23!=""){
			if (strtoupper($data23) != $metPerc['spak']) {
			$error ='<font color="red" title="Nomor formulir percepatan tidak sama">error</font>'; $noformpercepatan=$error;
			}elseif (strtoupper($data23) == $metPerc['spak'] AND $metPerc['status'] !="Aktif") {
			$error ='<font color="red" title="Nomor formulir percepatan belum diapprove">error</font>'; $noformpercepatan=$error;
			}elseif (strtoupper($data23) == $metPerc['spak'] AND $metPerc['mpp'] !=$data22) {	/*Penambahan validasi tenor harus sama dengan data tab (via tlp wenny 170413)
			$error ='<font color="red" title="Jumlah bulan MPP tidak sama">error</font>'; $dataexcel22=$error;
			}else{
			$noformpercepatan =$data23;
			}
		}
		else{
			$noformpercepatan =$data23;
		}
	}
}else{
	if ($data23 !="") {
		$metPerc = mysql_fetch_array($database->doQuery('SELECT fu_ajk_spak.id_cost,
																fu_ajk_spak.id_polis,
																fu_ajk_spak.spak,
																fu_ajk_spak.`status`,
																fu_ajk_spak_form.nama,
																fu_ajk_spak_form.dob,
																fu_ajk_spak_form.plafond,
																fu_ajk_spak_form.tenor,
																fu_ajk_spak_form.filefotodebitursatu,
																fu_ajk_spak_form.filefotoktp																
														FROM fu_ajk_spak
														INNER JOIN fu_ajk_spak_form ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
														WHERE fu_ajk_spak.spak = "'.strtoupper($data23).'"'));
		if (strtoupper($metPerc['nama']) != strtoupper($data4)) {
			$error ='<font color="red" title="Nama debitur tidak sama dengan data tablet">error</font>'; $noformpercepatan=$error;
		}elseif ($metPerc['dob'] != $datatgllahirnya) {
			$error ='<font color="red" title="Tanggal lahir debitur tidak sama dengan data tablet  '.$metPerc['dob'].' - '.$datatgllahirnya.'">error</font>'; $noformpercepatan=$error;
		}elseif ($metPerc['plafond'] != $dataexcel8med) {
			$error ='<font color="red" title="Nilai plafond debitur tidak sama dengan data tablet">error</font>'; $noformpercepatan=$error;
		}elseif ($metPerc['tenor'] != $data12) {
			$error ='<font color="red" title="Tenor debitur tidak sama dengan data tablet">error</font>'; $noformpercepatan=$error;
		}else{
			$noformpercepatan =$data23;
		}
	}

	//$noformpercepatan =$data23;
	//CEK KOLOM PADA NOMOR FORMULIR 17042017
/* DI DISABLE SEMENTARA KARENA MASIH ADA YANG MANUAL
	if ($fupolis['tab']=="T") {

	}else{
		if ($data23 =="") {
			if ($metPerc['spak'] != "") {
				$error ='<font color="red" title="Silahkan isi nomor MP tablet">error</font>'; $noformpercepatan=$error;
			}
		}else{
			//if ($data23 !="") {
				$metData = mysql_fetch_array($database->doQuery('SELECT fu_ajk_spak.id_cost,
																		fu_ajk_spak.id_polis,
																		fu_ajk_spak.spak,
																		fu_ajk_spak.`status`,
																		fu_ajk_spak_form.nama,
																		fu_ajk_spak_form.dob,
																		fu_ajk_spak_form.plafond,
																		fu_ajk_spak_form.tenor
																FROM fu_ajk_spak
																INNER JOIN fu_ajk_spak_form ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
																WHERE fu_ajk_spak.spak = "'.strtoupper($data23).'"'));
				if (strtoupper($metData['nama']) != strtoupper($data4)) {
					$error ='<font color="red" title="Nama debitur tidak sama dengan data tablet">error</font>'; $noformpercepatan=$error;
				}elseif ($metData['dob'] != $datatgllahirnya) {
					$error ='<font color="red" title="Tanggal lahir debitur tidak sama dengan data tablet  '.$metData['dob'].' - '.$datatgllahirnya.'">error</font>'; $noformpercepatan=$error;
				}elseif ($metData['plafond'] != $dataexcel8med) {
					$error ='<font color="red" title="Nilai plafond debitur tidak sama dengan data tablet">error</font>'; $noformpercepatan=$error;
				}elseif ($metData['tenor'] != $data12) {
					$error ='<font color="red" title="Tenor debitur tidak sama dengan data tablet">error</font>'; $noformpercepatan=$error;
				}else{
					$noformpercepatan =$data23;
				}
			//}else{
			//$noformpercepatan =$data23;
			//}
		}
	}

	//CEK KOLOM PADA NOMOR FORMULIR
}*/
//CEK PERCEPATAN TABLET	07072016

//CEK JENIS KELAMIN
$data24 = strtoupper($data24);
if ($fupolis['gender']=="Y") {
	if ($data24=="") {
	$error ='<font color="red" title="Silahkan isi jenis kelamin debitur">error</font>'; $dataexceljnskelamin=$error;
	}elseif ($data24 != "L" AND $data24 != "P") {
	$error ='<font color="red" title="Jenis kelamin hanya boleh di isi L / P (Laki-Laki / Perempuan)">error</font>'; $dataexceljnskelamin=$error;
	}else{
		if ($data24 == "L") {	$gender_ = "Laki-laki";	}
		elseif ($data24 == "P") {	$gender_ = "Perempuan";	}
		else{	$gender_ = '';	}
	$dataexceljnskelamin = $gender_;
	}
}else{
	$dataexceljnskelamin = $data24;
}
//CEK JENIS KELAMIN

if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		<td align="center">'.$data1.'</td>
		<td align="center">'.strtoupper($dataexcel2).'</td>
		<td align="center">'.$cekdatawilayah['regional'].'</td>
		<td>'.$dataexcel3.' </td>
		<td>'.$dataexcel4.''.$dataexcelDbl.'</td>
		<td align="center">'.$dataexcel5.'</td>
		<td align="center">'.$dataexcel6.'</td>
		<td align="center">'.$dataexcel7.'</td>
		<td align="center">'.$dataexceljnskelamin.'</td>
		<td align="right">'.$dataexcel8.'</td>
		<td align="center">'.$dataexcel9.'</td>
		<td align="center">'.$dataexcel10.'</td>
		<td align="center">'.$dataexcel11.'</td>
		<td align="center">'.$dataexcel12.'</td>
		<td align="center">'.$data13.'</td>
		<td align="center">'.$data14.'</td>
		<td align="center">'.$dataexcel15.'</td>
		<td align="right">'.$dataexcel16.'</td>
		<td>'.$data17.'</td>
		<td align="right">'.$dataexcel18.'</td>
		<td align="center">'.$data19.'</td>
		<td align="center">'.$dataexcel20.'</td>
		<td align="center">'.$dataexcel22.'</td>
		<td align="center">'.$data21.'</td>
		<td align="center">'.$data23.'</td>
	</tr>';

				//$exl = $database->doQuery('INSERT IGNORE fu_ajk_peserta_tempf SET id_cost="'.$fu['id'].'",
				//$cekdatadbl = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_cost="'.$fu['id'].'", nama="'.$data3.'", tgl_lahir="'.$datatgllahirnya.'", kredit_tgl="'.$datatglkreditnya.'", kredit_jumlah="'.$data7.'", kredit_tenor="'.$data11.'", cabang="'.$data15.'"'));
/*
$met = $database->doQuery('INSERT IGNORE fu_ajk_peserta_tempf SET id_cost="'.$fu['id'].'",
																  id_polis="'.$fupolis['id'].'",
																  namafile="'.$_FILES['userfile']['name'].'",
																  no_urut="'.$data1.'",
																  type_data="SPAJ",
																  spaj="",
																  nama_mitra="'.$data2.'",
																  nama="'.$data4.'",
																  gender="",
																  tgl_lahir="'.$datatgllahirnya.'",
																  usia="'.$data14.'",
																  kredit_tgl="'.$datatglkreditnya.'",
																  kredit_jumlah="'.$dataexcel8.'",
																  kredit_tenor="'.$met_tenor.'",
																  kredit_akhir="",
																  premi="",
																  disc_premi="",
																  bunga="",
																  biaya_adm="",
																  ext_premi="'.$data17.'",
																  totalpremi="",
																  badant="",
																  badanb="",
																  status_medik="'.$data19.'",
																  status_bayar="0",
																  status_aktif="Upload",
																  ket="'.$data21.'",
																  mppbln="'.$data22.'",
																  regional="'.$cekdatawilayah['regional'].'",
																  area="'.$cekdatawilayah['area'].'",
																  cabang="'.$data3.'",
																  input_by ="'.$_SESSION['nm_user'].'",
															      input_time ="'.$futgl.'"');
*/
}
if ($error) {	echo '<tr><td colspan="27" align="center"><font color="red"><blink>Silahkan lengkapi kolom yang error !!!</blink><br /><a href="ajk_uploader.php?er=cancelspaj&fileclient='.$_FILES['userfile']['name'].'">Back</a></font></td></tr>';
//echo $error;
}
else		{

	$today = date('Y-m-d His');
	$foldername = date("y",strtotime($today)).date("m",strtotime($today));
	$path = 'ajk_file/deklarasi/'.$foldername;
				if (!file_exists($path)) {
					mkdir($path, 0777);
					chmod($path, 0777);
				}
	move_uploaded_file($_FILES['userfile']['tmp_name'],$path.'/'.$fileUploadAdonai) or die( "Could not upload file!");

for ($i=10; $i<=$_REQUEST['bataskolom']; $i++)
{
	$data1=$data->val($i, 1);		//NO
	$data2=$data->val($i, 2);		//NAMA MITRA
	if ($data2=="") {
		$metMitranya = "BUKOPIN";
	}else{
		$metMitranya = strtoupper($data2);
	}
	$cekmitra = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_grupproduk WHERE id_cost="'.$q['id_cost'].'" AND nmproduk="'.$metMitranya.'"'));

	$data3=$data->val($i, 3);		//CABANG
	$cekdatawilayah = mysql_fetch_array($database->doQuery('SELECT * FROM v_wilayah WHERE id_cost="'.$_REQUEST['idcost'].'" AND cabang="'.strtoupper($data3).'"'));

	$data4=$data->val($i, 4);		//NAMA TERTANGGUNG
	$data5=$data->val($i, 5);		//TANGGAL LAHIR (HARI)
	$data6=$data->val($i, 6);		//TANGGAL LAHIR (BLN)
	$data7=$data->val($i, 7);		//TANGGAL LAHIR (THN)
	if ($data5 < 9) { $data5_ = '0'.$data5;	}else{	$data5_ = $data5;}
	if ($data6 < 9) { $data6_ = '0'.$data6;	}else{	$data6_ = $data6;}
	$datatgllahirnya = $data7.'-'.$data6_.'-'.$data5_;

	$data8=$data->val($i, 8);		//UANG ASURANSI
	$asting = array(" ", ",", ".", "*");
	$replace = array('', '', '', '');

	$malestr = str_replace($asting, $replace, $data8);
	$dataexcel8med = $malestr;

	$data9=$data->val($i, 9);		//MULAI ASURANSI (HARI)
	$data10=$data->val($i, 10);		//MULAI ASURANSI (BLN)
	$data11=$data->val($i, 11);		//MULAI ASURANSI (THN)
	if ($data9 < 9) { $data9_ = '0'.$data9;	}else{	$data9_ = $data9;}
	if ($data10 < 9) { $data10_ = '0'.$data10;	}else{	$data10_ = $data10;}
	$datatglkreditnya = $data11.'-'.$data10_.'-'.$data9_;

	$data12=$data->val($i, 12);		//MA THN
	$data13=$data->val($i, 13);		//MA BLN
	$met_tenor = $data12 * 12 + $data13;

	$data14=$data->val($i, 14);		//USIA
	$data15=$data->val($i, 15);		//RATE (TARIF PREMI)
	$data16=$data->val($i, 16);		//PREMI
	$data17=$data->val($i, 17);		//EXTRA PREMI
	$data18=$data->val($i, 18);		//PREMI SEKALIGUS
	$data19=$data->val($i, 19);		//MEDICAL
	$data20=$data->val($i, 20);		//PRODUK
	$data21=$data->val($i, 21);		//KETERANGAN
	$data22=$data->val($i, 22);		//MPP
	$data23=$data->val($i, 23);		//APABILA PERCEPATAN TABLET
	$data24=$data->val($i, 24);		//JENIS KELAMIN

//echo '<br />';
//PHOTO YANG DIAMBIL DARI TAB
if ($metPerc['spak']) {
	$metPotoPerc = 'photodebitur="'.$metPerc['filefotodebitursatu'].'",
					photoktp="'.$metPerc['filefotoktp'].'",';
}else{
	$metPotoPerc = '';
}

if($data21 == 'TALANGAN'){
	$danatalangan = ' danatalangan = 1,';
}

if ($fupolis['gender']=="Y") {
$data24 = strtoupper($data24);
$datagender = $data24;
}else{

}

$user = mysql_fetch_array(mysql_query("select * from pengguna where cabang = '".strtoupper($data3)."' and del is NULL and level = 7 and left(nm_user,5)='staff'"));

if($user['nm_user']!=""){
	$input_by = $user['nm_user'];
}else{
	$input_by = 'staffjakarta!';
}

//PHOTO YANG DIAMBIL DARI TAB
/*$met = $database->doQuery('INSERT INTO fu_ajk_peserta_tempf SET id_cost="'.$fu['id'].'",
																  id_polis="'.$fupolis['id'].'",
																  namafile="'.$fileUploadAdonai.'",
																  no_urut="'.$data1.'",
																  type_data="SPAJ",
																  spaj="'.strtoupper($data23).'",
																  nama_mitra="'.$cekmitra['id'].'",
																  nama="'.trim(strtoupper($data4)).'",
																  gender="'.$datagender.'",
																  tgl_lahir="'.$datatgllahirnya.'",
																  usia="'.$data14.'",
																  kredit_tgl="'.$datatglkreditnya.'",
																  kredit_jumlah="'.$dataexcel8med.'",
																  kredit_tenor="'.$met_tenor.'",
																  kredit_akhir="",
																  premi="",
																  disc_premi="",
																  bunga="",
																  biaya_adm="",
																  ext_premi="'.$data17.'",
																  totalpremi="",
																  badant="",
																  badanb="",
																  status_medik="'.$data19.'",
																  status_bayar="0",
																  status_aktif="Manual Upload",
																  '.$metPotoPerc.'
																  regional="'.$cekdatawilayah['regional'].'",
																  area="'.$cekdatawilayah['area'].'",
																  cabang="'.strtoupper($data3).'",
																  mppbln="'.$data22.'",
																  nopermohonan="'.$metPerc['nopermohonan'].'",
																  ket="'.strtoupper($data21).'",
																  input_by ="'.$input_by.'",
															      input_time ="'.$futgl.'"');*/
 //blocked 20170828

//$tgl_asuransi_akhir = date_add($datatglkreditnya, date_interval_create_from_date_string($met_tenor.' months'));

//$tgl_asuransi_akhir = strtotime(date("Y-m-d", strtotime($datatglkreditnya)) . " +".$met_tenor." month");
$tgl_asuransi_akhir = date('Y-m-d', strtotime("+".$met_tenor." months", strtotime($datatglkreditnya)));

$premi = str_replace(',', '', $data16);
$premi = trim(str_replace('*', '', $premi));

$met = $database->doQuery('INSERT INTO fu_ajk_peserta_tempf SET id_cost="'.$fu['id'].'",
																  id_polis="'.$fupolis['id'].'",
																  namafile="'.$fileUploadAdonai.'",
																  no_urut="'.$data1.'",
																  type_data="SPAJ",
																  spaj="'.strtoupper($data23).'",
																  nama_mitra="'.$cekmitra['id'].'",
																  nama="'.trim(strtoupper($data4)).'",
																  gender="'.$datagender.'",
																  tgl_lahir="'.$datatgllahirnya.'",
																  usia="'.$data14.'",
																  kredit_tgl="'.$datatglkreditnya.'",
																  kredit_jumlah="'.$dataexcel8med.'",
																  kredit_tenor="'.$met_tenor.'",
																  kredit_akhir="'.$tgl_asuransi_akhir.'",
																  premi="'.$premi.'",
																  disc_premi="",
																  bunga="",
																  biaya_adm="",
																  ext_premi="'.$data17.'",
																  totalpremi="'.$premi.'",
																  badant="",
																  badanb="",
																  status_medik="'.$data19.'",
																  status_bayar="0",
																  status_aktif="Upload",
																  '.$metPotoPerc.'
																  regional="'.$cekdatawilayah['regional'].'",
																  area="'.$cekdatawilayah['area'].'",
																  cabang="'.strtoupper($data3).'",
																  mppbln="'.$data22.'",
																  nopermohonan="'.$metPerc['nopermohonan'].'",
																  '.$danatalangan.'
																  ket="'.strtoupper($data21).'",
																  input_by ="'.$input_by.'",
															    input_time ="'.$futgl.'"');

}
$cekValidDoubleData = $database->doQuery('SELECT nama, tgl_lahir, kredit_tgl, kredit_jumlah, kredit_tenor, cabang, COUNT(*)
										  FROM fu_ajk_peserta_tempf
										  WHERE namafile="'.$fileUploadAdonai.'" AND input_by ="'.$_SESSION['nm_user'].'"
										  GROUP BY nama, tgl_lahir, kredit_tgl, kredit_jumlah, kredit_tenor, cabang
										  HAVING (COUNT(fu_ajk_peserta_tempf.nama) > 1)');
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '"><td colspan="27">&nbsp;</td></tr>';
while ($cekValidDoubleData_ = mysql_fetch_array($cekValidDoubleData)) {
if ($cekValidDoubleData_['nama']) {
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
			<td colspan="4"><b><font color="red">Double Data File Excel</td><td colspan="18"><b>'.$cekValidDoubleData_['nama'].'</td>
		</tr>';
}
}


echo '<tr><td colspan="27" align="center"><a title="Approve data upload" href="ajk_uploader.php?er=approveuser&nmfile='.$fileUploadAdonai.'&dateupl='.$futgl.'&idc='.$fu['id'].'" onClick="if(confirm(\'Apakah anda sudah yakin dengan semua data peserta ini ?\')){return true;}{return false;}"><img src="image/save.png" border="0" width="50"></a>
		&nbsp; &nbsp; <a title="Batalkan data upload" href="ajk_uploader.php?er=cancelspaj&fileclient='.$fileUploadAdonai.'"><img src="image/deleted.png" border="0" width="50"></a></td></tr>';
	}
echo '</table></form>';
}
	;
	break;

case "cancelspaj":
	$cha = $database->doQuery('DELETE FROM fu_ajk_peserta_tempf WHERE namafile="'.$_REQUEST['fileclient'].'"');
	header("location:ajk_uploader.php?er=spaj");
	;
	break;

case "approveuser":
$met_appr = $database->doQuery('SELECT *, DATE_FORMAT(input_time,"%Y-%m-%d") AS tglinput FROM fu_ajk_peserta_tempf WHERE id_cost="'.$_REQUEST['idc'].'" AND namafile="'.$_REQUEST['nmfile'].'" AND input_time="'.$_REQUEST['dateupl'].'"');

$message .= '<table width="100%" broder="0" cellpadding="3" cellspacing="1">
			<tr><th width="1%">No</th>
			<th width="5%">Nama Mitra</th>
			<th width="8%">Regional</th>
			<th width="8%">Cabang</th>
			<th>Nama Debitur</th>
			<th width="5%">Tanggal Lahir</th>
			<th width="5%">Plafond</th>
			<th width="5%">Tanggal Akad</th>
			<th width="1%">Tenor<br />(bulan)</th>
			<th width="1%">Usia</th>
			<th width="5%">Rate</th>
			<th width="5%">Tarif Premi</th>
			<th width="1%">EM(%)</th>
			<th width="5%">Premi</th>
			<th width="5%">Underwriting</th>
		</tr>';
while ($mamet_appr = mysql_fetch_array($met_appr)) {
$metproduk = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$mamet_appr['id_polis'].'" AND id_cost="'.$mamet_appr['id_cost'].'"'));

//$cekratepolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$mamet_appr['id_cost'].'" AND id_polis="'.$mamet_appr['id_polis'].'" AND tenor="'.$mamet_appr['kredit_tenor'].'" AND status="baru"'));	//VALIDASI CEK TENOR DENGAN SETTING RATE POLIS
	if ($metproduk['mpptype']=="Y") {
		//TAMBAHAN CARA HITUNG RATE, APABILA PERCEPATAN MPP 0 BLN SET RATE DENGAN PRODUK PERCEPATAN 30 08 2016
		if ($mamet_appr['mppbln']==0) {
			$cekratepolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$mamet_appr['id_cost'].'" AND id_polis="2" AND tenor="'.$mamet_appr['kredit_tenor'].'" AND status="baru" AND "'.$mamet_appr['tglinput'].'" BETWEEN eff_from AND eff_to AND del IS NULL'));	//VALIDASI CEK TENOR DENGAN SETTING RATE POLIS
		}else{
			if($mamet_appr['spaj'] != ""){
					//PERHITUNGAN MPP BARU - HANSEN - 20170309
					$dana_talangan = mysql_fetch_array($database->doQuery("SELECT IFNULL(CASE WHEN (S.id_polis in (select id from fu_ajk_polis where mpptype = 'Y')) AND
																												F.idspk = (SELECT idspk FROM fu_ajk_spak_form WHERE nopermohonan = F.nopermohonan ORDER BY idspk DESC LIMIT 1)
																							THEN 'mpp' END,'')AS datampp
																			FROM fu_ajk_spak_form AS F, fu_ajk_spak AS S,fu_ajk_polis AS P
																			WHERE S.spak='".$mamet_appr['spaj']."' AND F.idspk=S.id
																			AND P.id = S.id_polis"));
					if($dana_talangan['datampp']=="mpp"){
						if($mamet_appr['kredit_tenor'] <= 12){
							$tenor = 1;
						}elseif($mamet_appr['kredit_tenor'] >= 25){
							$tenor = 3;
						}else{
							$tenor = 2;
						}
					}else{
						$tenor = $mamet_appr['kredit_tenor'];
					}
				}else{
					$tenor = $mamet_appr['kredit_tenor'];
				}
				/*//cek spk 2
				if(!$spkke2){
					$spkke2 = mysql_fetch_array($database->doQuery("SELECT spak
																	FROM fu_ajk_spak_form
																			 INNER JOIN fu_ajk_spak
																			 ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
																	WHERE spak != '".$mamet_appr['spaj']."' and
																				nopermohonan = (SELECT fu_ajk_spak_form.nopermohonan
																												FROM fu_ajk_spak_form
																														 INNER JOIN fu_ajk_spak
																														 ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
																														 INNER JOIN fu_ajk_peserta_tempf
																														 ON fu_ajk_peserta_tempf.spaj = fu_ajk_spak.spak
																												WHERE fu_ajk_spak.status ='Aktif' and
																															spaj = '".$mamet_appr['spaj']."')"));
				}

				if($spkke2['spak']==$mamet_appr['spaj']){
					if($mamet_appr['kredit_tenor'] <= 12){
						$tenormpp = 1;
					}else{
						$tenormpp = 2;
					}
					unset($spkke2);
				}else{
					$tenormpp = $mamet_appr['kredit_tenor'] / 12;
				}
			}else{
				$tenormpp = $mamet_appr['kredit_tenor'] / 12;
			}*/


			$cekratepolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$mamet_appr['id_cost'].'" AND id_polis="'.$mamet_appr['id_polis'].'" AND tenor="'.$tenormpp .'" AND '.$mamet_appr['mppbln'].' BETWEEN mpp_s AND mpp_e AND status="baru" AND "'.$mamet_appr['tglinput'].'" BETWEEN eff_from AND eff_to AND del IS NULL'));	//VALIDASI CEK TENOR DENGAN SETTING RATE POLIS
		}
		//TAMBAHAN CARA HITUNG RATE, APABILA PERCEPATAN MPP 0 BLN SET RATE DENGAN PRODUK PERCEPATAN 30 08 2016
	}else{
		if ($mamet_appr['tglinput'] <= "2016-08-31" AND ($mamet_appr['id_polis']=="1" OR $mamet_appr['id_polis']=="2")) {		// Produk SPK REGULER dan PERCEPATAN yg tgl input dibawah tgl 31 agustus 2016 menggunakan rate lama
		$cekratepolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$mamet_appr['id_cost'].'" AND id_polis="'.$mamet_appr['id_polis'].'" AND tenor="'.$mamet_appr['kredit_tenor'].'" AND status="lama" AND del IS NULL'));	//VALIDASI CEK TENOR DENGAN SETTING RATE POLIS
		}else{
		$cekratepolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$mamet_appr['id_cost'].'" AND id_polis="'.$mamet_appr['id_polis'].'" AND tenor="'.$mamet_appr['kredit_tenor'].'" AND status="baru" AND del IS NULL'));	//VALIDASI CEK TENOR DENGAN SETTING RATE POLIS
		}
	}

$premistandar = $mamet_appr['kredit_jumlah'] * $cekratepolis['rate'] / 1000;

if($metproduk['min_premium'] > 0){
	if($premistandar < 250000){ //Minimum Premi
		$totalpremi = 250000;
	}else{
		$totalpremi = $premistandar;
	}
}else{
	$totalpremi = $premistandar;
}



if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';

if ($mamet_appr['nama_mitra']=="") {	$metmitra = "BUKOPIN";
}else{
	$cekmitra = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_grupproduk WHERE id="'.$mamet_appr['nama_mitra'].'"'));
	$metmitra = $cekmitra['nmproduk'];
}

$message .='<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
			<td align="center">'.++$no.'</td>
			<td align="center">'.strtoupper($metmitra).'</td>
			<td align="center">'.$mamet_appr['regional'].'</td>
			<td align="center">'.strtoupper($mamet_appr['cabang']).'</td>
			<td>'.strtoupper($mamet_appr['nama']).'</td>
			<td align="center">'._convertDate($mamet_appr['tgl_lahir']).'</td>
			<td align="right">'.duit($mamet_appr['kredit_jumlah']).'</td>
			<td align="center">'._convertDate($mamet_appr['kredit_tgl']).'</td>
			<td align="center">'.duit($mamet_appr['kredit_tenor']).'</td>
			<td align="center">'.$mamet_appr['usia'].'</td>
			<td align="center">'.$cekratepolis['rate'].'</td>
			<td align="right">'.duit($premistandar).'</td>
			<td align="center">'.$mamet_appr['ext_premi'].'</td>
			<td align="right">'.duit($totalpremi).'</td>
			<td align="center">'.$mamet_appr['status_medik'].'</td>
	  		</tr>';
}
$message .='</table>';
echo $message;

	$mail = new PHPMailer; // call the class
	$mail->IsSMTP();
	$mail->Host = SMTP_HOST; //Hostname of the mail server
	$mail->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
	//$mail->Username = SMTP_UNAME; //Username for SMTP authentication any valid email created in your domain
	//$mail->Password = SMTP_PWORD; //Password for SMTP authentication
	//$mail->AddReplyTo = SMTP_REPLYMAIL; //reply-to address
	$mail->SetFrom ($q['email'], $q['nm_lengkap']); //From address of the mail
	$mail->Subject = "AJKOnline - UPLOAD DATA PSERTA BARU SPAJ"; //Subject od your mail
	//EMAIL PENERIMA  SPV CLIENT
	$mailsupervisorajk = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="'.$q['id_cost'].'" AND id_polis="'.$q['id_polis'].'" AND level="3" AND status=""');
	while ($_mailsupervisorajk = mysql_fetch_array($mailsupervisorajk)) {
	$mail->AddAddress($_mailsupervisorajk['email'], $_mailsupervisorajk['nm_lengkap']); //To address who will receive this email
	}
	//EMAIL PENERIMA  SPV CLIENT

	//EMAIL PENERIMA  KANTOR U/W
	$mailclient = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="" AND status="" AND level="1"');
	while ($_mailclient = mysql_fetch_array($mailclient)) {
	$mail->AddAddress($_mailclient['email'], $_mailclient['nm_lengkap']); //To address who will receive this email
	}
	//EMAIL PENERIMA  KANTOR U/W

	$mail->AddCC("penting_kaga@yahoo.com");
	//$mail->AddAddress($mailsupervisorajk['email'], $mailsupervisorajk['nm_lengkap']); //To address who will receive this email
	//$mail->AddCC($approvemail);
	$mail->MsgHTML('<table><tr><th>Data peserta baru SPAJ telah diinput oleh <b>'.$_SESSION['nm_user'].' selaku staff AJK-Online pada tanggal '.$futgl.'</tr></table>'.$message); //Put your body of the message you can place html code here
	$send = $mail->Send(); //Send the mails
	//echo $mail;
echo '<center>Data Peserta Baru SPAJ sudah diinput oleh <b>'.$_SESSION['nm_user'].'</b>, tunggu konfirmasi selanjutnya dari Supervisor untuk pencetakan nomor DN.<br /><a href="ajk_uploader.php?er=spaj">Kembali Ke Halaman Utama</a></center>';
	;
	break;

case "spaj":
echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="100%" align="left">Modul Upload Data Kepesertaan (SPAJ)</font></th></tr></table>';
$fu1 = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$q['id_cost'].'"'));
//$fu2 = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$q['id_polis'].'"'));
$met_polis = $database->doQuery('SELECT * FROM fu_ajk_polis WHERE tipe_produk="ajk" AND typeproduk="NON SPK" AND ('.$mametProdukUser.') AND del IS NULL ORDER BY nmproduk ASC');
echo '<form name="f1" method="post" enctype="multipart/form-data" action="ajk_uploader.php?er=parse_spaj">
	<table border="0" width="60%" align="center">
	<tr><td width="15%" align="right">Nama Perusahaan</td>
		  <td width="30%">: <input type="hidden" name="idcost" value="'.$q['id_cost'].'">'.$fu1['name'].'</td></tr>
<!--<tr><td width="10%" align="right">Produk</td>
		<td width="20%">: <input type="hidden" name="idpolis" value="'.$q['id_polis'].'"> '.$fu2['nmproduk'].' ('.$fu2['nopol'].')</td>
	</tr>-->
<tr><td align="right">Nama Produk</td><td>: <select name="idpolis">
		<option value="">---Pilih Produk---</option>';
while($met_polis_ = mysql_fetch_array($met_polis)) {
	echo '<option value="'.$met_polis_['id'].'"'._selected($_REQUEST['idpolis'], $met_polis_['id']).'>'.$met_polis_['nmproduk'].'</option>';
}
echo '</select></td></tr>
	<tr><td align="right">Silakan Pilih File Excel<br /><font color="red" size="2">Format excel <b>.xls</b> </td><td valign="top">: <input name="userfile" type="file" size="50" onchange="checkfile(this);" ></td></tr>
	<tr><td align="right">Batas Akhir Baris </td><td>: <input type="text" name="bataskolom" value="'.$_REQUEST['bataskolom'].'" size="5" maxlength="3" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"></td></tr>
	<tr><td align="center"colspan="2"><input name="upload" type="submit" value="Import"></td></tr>
	</table></form>
	<!--<font color="red" size="5"><p>**Sehubungan dengan adanya prosedur penutupan baru di adonai,maka per tanggal 17-Juli-2017 deklarasi melalui excel ditiadakan, untuk cara penutupan deklarasi yang baru dapat dilihat <b><a href="ajk_file/Manual Book Deklarasi Ver 2.0.pdf" target="_blank" style="font-size: 30px; text-decoration:underline;" >disini</a></b></p>-->';
		;
		break;


case "tab":
echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="100%" align="left">Modul Upload Data Kepesertaan (Tablet)</font></th></tr></table>';
	$fu1 = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$q['id_cost'].'"'));
	//$fu2 = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$q['id_polis'].'"'));
	$met_polis = $database->doQuery('SELECT * FROM fu_ajk_polis WHERE tipe_produk="ajk" AND typeproduk="NON SPK" AND ('.$mametProdukUser.') AND tab="Y" AND del IS NULL ORDER BY nmproduk ASC');
		echo '<form name="f1" method="post" enctype="multipart/form-data" action="ajk_uploader.php?er=parsingtab">
		<table border="0" width="60%" align="center">
		<tr><td width="15%" align="right">Nama Perusahaan</td>
			  <td width="30%">: <input type="hidden" name="idcost" value="'.$q['id_cost'].'">'.$fu1['name'].'</td></tr>
	<!--<tr><td width="10%" align="right">Produk</td>
			<td width="20%">: <input type="hidden" name="idpolis" value="'.$q['id_polis'].'"> '.$fu2['nmproduk'].' ('.$fu2['nopol'].')</td>
		</tr>-->
	<tr><td align="right">Nama Produk</td><td>: <select name="idpolis">
			<option value="">---Pilih Produk---</option>';
		while($met_polis_ = mysql_fetch_array($met_polis)) {
			echo '<option value="'.$met_polis_['id'].'"'._selected($_REQUEST['idpolis'], $met_polis_['id']).'>'.$met_polis_['nmproduk'].'</option>';
		}
		echo '</select></td></tr>
		<tr><td align="right">Silakan Pilih File Excel<br /><font color="red" size="2">Format excel <b>.xls</b> </td><td valign="top">: <input name="userfile" type="file" size="50" onchange="checkfile(this);" ></td></tr>
		<tr><td align="right">Batas Akhir Baris </td><td>: <input type="text" name="bataskolom" value="'.$_REQUEST['bataskolom'].'" size="5" maxlength="3" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"></td></tr>
		<tr><td align="center"colspan="2"><input name="upload" type="submit" value="Import"></td></tr>
		</table></form><!--<font color="red" size="5"><p>**Sehubungan dengan adanya prosedur penutupan baru di adonai,maka per tanggal 17-Juli-2017 deklarasi melalui excel ditiadakan, untuk cara penutupan deklarasi yang baru dapat dilihat <b><a href="ajk_file/Manual Book Deklarasi Ver 2.0.pdf" target="_blank" style="font-size: 30px; text-decoration:underline;" >disini</a></b></p>-->';
		;
		break;

case "parsingtab":
$fu = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$_REQUEST['idcost'].'"'));
//$fupolis = mysql_fetch_array($database->doQuery('SELECT id,nopol,nmproduk, mpptype, mppbln FROM fu_ajk_polis WHERE id="'.$_REQUEST['idpolis'].'"'));
$fupolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$_REQUEST['idpolis'].'" AND del IS NULL'));

//	$_REQUEST['idcost'] = $_POST['idcost'];				if (!$_REQUEST['idcost'])  $error .='Silahkan pilih nomor polis<br />.';
$_REQUEST['idpolis'] = $_POST['idpolis'];			if (!$_REQUEST['idpolis'])  $error .='Silahkan pilih nomor polis<br />.';
$_REQUEST['bataskolom'] = $_POST['bataskolom'];		if (!$_REQUEST['bataskolom'])  $error .='Silahkan tentukan batas kolom file<br />.';
if (!$_FILES['userfile']['tmp_name'])  $error .='Silahkan upload file excel anda<br />.';
$allowedExtensions = array("xls");
foreach ($_FILES as $file) {
	if ($file['tmp_name'] > '') {
		if (!in_array(end(explode(".",	strtolower($file['name']))), $allowedExtensions)) {
			die('<center><font color=red><br />'.$file['name'].' <br /><blink>File extension tidak diperbolehkan selain excel!</blink></font><br/>'.'<a href="ajk_uploader.php?er=tab">'.'Silahkan Upload kembali dengaan format file <b>.xls</b></a></center>');
		}
	}
}
if ($error)
{	echo '<blink><center><font color=red>'.$error.'</font></blink><a href="ajk_uploader.php?er=tab">'.'&lt;&lt Go Back</a></center>';	}

else
{
echo '<form method="post" action="" onload ="onbeforeunload">
	  <table border="0" width="100%" cellpadding="1" cellspacing="1" style="border: solid 3px #DEDEDE"  bgcolor="#bde0e6">
		<tr><td colspan="2"><input type="hidden" name="idcostumer" value="'.$fu['id'].'">Nama Perusahaan</td><td colspan="24">: <b>'.$fu['name'].'</b></td></tr>
		<tr><td colspan="2"><input type="hidden" name="idpolis" value="'.$fupolis['id'].'">Nama Produk</td><td colspan="24">: <b>'.$fupolis['nmproduk'].'</b></td></tr>
		<tr><td colspan="2"><input type="hidden" name="bataskolom" value="'.$_REQUEST['bataskolom'].'"><input type="hidden" name="namafileexl" value="'.$_FILES['userfile']['name'].'">File Name</td><td colspan="24">: <b>'.$_FILES['userfile']['name'].'</b></td></tr>
		<tr><th width="1%" rowspan="2">No</th>
			<th width="5%" rowspan="2">Nama Mitra</th>
			<th width="5%" rowspan="2">No Form</th>
			<th rowspan="2">Nama Tertanggung</th>
			<th width="10%" colspan="3">Tanggal Lahir</th>
			<th rowspan="2" width="1%">Usia</th>
			<th width="5%" rowspan="2">Uang Asuransi</th>
			<th width="10%" colspan="3">Mulai Asuransi</th>
			<th width="1%" rowspan="2">Tenor<br />(thn)</th>
			<th width="1%" rowspan="2">EM<br />(%)</th>
			<th width="10%" rowspan="2">Cabang</th>
			<th width="10%" rowspan="2">Produk</th>
			<th width="1%" rowspan="2">MPP(bln)</th>
			<th width="10%" rowspan="2">KETERANGAN</th>
		</tr>
	<tr><th>Hari</th><th>Bulan</th><th>Tahun</th><th>Hari</th><th>Bulan</th><th>Tahun</th></tr>';
	$fileUploadAdonaiSPK = $timedeklarasi.'-'.$_FILES['userfile']['name'];
	$data = new Spreadsheet_Excel_Reader($_FILES['userfile']['tmp_name']);
	$baris = $data->rowcount($sheet_index=0);											//MEMBACA JUMLAH BARIS DATA EXCEL

for ($i=10; $i<=$_REQUEST['bataskolom']; $i++)
			{
				$data1=$data->val($i, 1);		//no
				$data17=$data->val($i, 2);		//NAMA MITRA
				$data2=$data->val($i, 3);		//S P K
				$data3=$data->val($i, 4);		//NAMA TERTANGGUNG
				$data4=$data->val($i, 5);		//TANGGAL LAHIR (TGL)
				$data5=$data->val($i, 6);		//TANGGAL LAHIR (BLN)
				$data6=$data->val($i, 7);		//TANGGAL LAHIR (THN)
				$data7=$data->val($i, 8);		//UANG ASURANSI
				$data8=$data->val($i, 9);		//MULAI ASURANSI (TGL)
				$data9=$data->val($i, 10);		//MULAI ASURANSI (BLN)
				$data10=$data->val($i, 11);		//MULAI ASURANSI (THN)
				$data11=$data->val($i, 12);		//MASA ASURANSI
				$data12=$data->val($i, 13);		//EXT. PREMI
				/* FORMAT SEBELUMNYA REGIONAL, AREA DAN CABANG
				   $data13=$data->val($i, 13);		//REGIOALL
				   $data14=$data->val($i, 14);		//AREA
				   $data15=$data->val($i, 15);		//CABANG
				*/
				$data13=$data->val($i, 14);		//CABANG
				$data14=$data->val($i, 15);		//PRODUK
				$data15=$data->val($i, 16);		//KETERANGAN
				$data16=$data->val($i, 17);		//MPP

				if ($q['cabang']!="PUSAT") {	$cekUserCabang = ' AND name="'.$q['cabang'].'"';	}else{	$cekUserCabang = ' AND name="'.strtoupper($data13).'"';	}

				//VALIDASI DATA UPLOAD//
				if ($data2==""){ $error ='<font color="red">error</font>'; $dataexcel2=$error;}else{ $dataexcel2=$data2;}
				if ($data3==""){ $error ='<font color="red">error</font>'; $dataexcel3=$error;}else{ $dataexcel3=$data3;}
				if ($data4==""){ $error ='<font color="red">error</font>'; $dataexcel4=$error;}else{ $dataexcel4=$data4;}
				if ($data5==""){ $error ='<font color="red">error</font>'; $dataexcel5=$error;}else{ $dataexcel5=$data5;}
				if ($data6==""){ $error ='<font color="red">error</font>'; $dataexcel6=$error;}else{ $dataexcel6=$data6;}

				if ($data17!=""){
					$metMitra = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_grupproduk WHERE id_cost="'.$q['id_cost'].'" AND nmproduk="'.strtoupper($data17).'"'));
					if (!$metMitra['nmproduk']) {
						$error ='<font color="red" title="nama mitra tidak ada">error</font>'; $dataexcel17=$error;
					}else{
						$dataexcel17=$data17;
					}
				}else{
					$metMitra = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_grupproduk WHERE id_cost="'.$q['id_cost'].'" AND nmproduk="BUKOPIN"'));
					$dataexcel17="BUKOPIN";
				}

				//$titikpos = strpos($data7, ".");
				//$komapos = strpos($data7, ",");
				if ($data7==""){ $error ='<font color="red">error</font>'; $dataexcel7=$error;}
				//elseif (strpos($data7, ".")) { $error ='<font color="red">error</font>'; $dataexcel7=$error;}	//VALIDASI NILAI ASURANSI DENGAN TITIK
				//elseif (strpos($data7, ",")) { $error ='<font color="red">error</font>'; $dataexcel7=$error;}	//VALIDASI NILAI ASURANSI DENGAN KOMA
				//elseif (strpos($data7, "*")) { $error ='<font color="red">error</font>'; $dataexcel7=$error;}	//VALIDASI NILAI ASURANSI DENGAN KOMA
				//else{ $dataexcel7=duit($data7);	$dataexcel7med = $data7;	}
				else{
					$asting = array(" ", ",", ".", "*");
					$replace = array('', '', '', '');

					$malestr = str_replace($asting, $replace, $data7);
					//echo $malestr;
					$dataexcel7=duit($malestr);	$dataexcel7med = $malestr;
				}


				if ($data8==""){ $error ='<font color="red">error</font>'; $dataexcel8=$error;}else{ $dataexcel8=$data8;}
				if ($data9==""){ $error ='<font color="red">error</font>'; $dataexcel9=$error;}else{ $dataexcel9=$data9;}
				if ($data10==""){ $error ='<font color="red">error</font>'; $dataexcel10=$error;}else{ $dataexcel10=$data10;}
				if ($data11==""){ $error ='<font color="red">error</font>'; $dataexcel11=$error;}else{ $dataexcel11=$data11;}
				if ($data13==""){ $error ='<font color="red">error</font>'; $dataexcel13=$error;}else{ $dataexcel13=$data13;}
				if ($data14==""){ $error ='<font color="red">error</font>'; $dataexcel14=$error;}else{ $dataexcel14=$data14;}
				//if ($data15==""){ $error ='<font color="red">error</font>'; $dataexcel15=$error;}else{ $dataexcel15=$data15;}

				if(!is_numeric($data4)){$error ='<font color="red">error</font>'; $dataexcel4=$error;}else{ $dataexcel4=$data4;}		//VALIDASI HARI
				if(strlen($data4 > 31 )){$error ='<font color="red">error</font>'; $dataexcel4=$error;}else{ $dataexcel4=$data4;}		//VALIDASI HARI

				if(!is_numeric($data5)){$error ='<font color="red">error</font>'; $dataexcel5=$error;}else{ $dataexcel5=$data5;}		//VALIDASI BULAN
				if(strlen($data5 > 12 )){$error ='<font color="red">error</font>'; $dataexcel5=$error;}else{ $dataexcel5=$data5;}		//VALIDASI BULAN

				if(!is_numeric($data6)){$error ='<font color="red">error</font>'; $dataexcel6=$error;}else{ $dataexcel6=$data6;}		//VALIDASI TAHUN
				if(strlen($data6 > $dateY )){$error ='<font color="red">error</font>'; $dataexcel6=$error;}else{ $dataexcel6=$data6;}		//VALIDASI TAHUN

				if(!is_numeric($data8)){$error ='<font color="red">error</font>'; $dataexcel8=$error;}else{ $dataexcel8=$data8;}		//VALIDASI HARI
				if(strlen($data8 > 31 )){$error ='<font color="red">error</font>'; $dataexcel8=$error;}else{ $dataexcel8=$data8;}		//VALIDASI HARI

				if(!is_numeric($data9)){$error ='<font color="red">error</font>'; $dataexcel9=$error;}else{ $dataexcel9=$data9;}		//VALIDASI BULAN
				if(strlen($data9 > 12 )){$error ='<font color="red">error</font>'; $dataexcel9=$error;}else{ $dataexcel9=$data9;}		//VALIDASI BULAN

				if(!is_numeric($data10)){$error ='<font color="red">error</font>'; $dataexcel10=$error;}else{ $dataexcel10=$data10;}		//VALIDASI TAHUN
				if(strlen($data10 > $dateY )){$error ='<font color="red">error</font>'; $dataexcel10=$error;}else{ $dataexcel10=$data10;}		//VALIDASI TAHUN


				//FORMAT TERNOR DLM BULAN DIBAGI 12
				//$_mettenor = $data11 * 12;	TENOR BULAN
				$_mettenor = $data11;
				/*
				   $cekdatareg = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE id_cost="'.$q['id_cost'].'" AND name="'.$data13.'"'));			//VALIDASI REGIONAL
				   if ($data13 != $cekdatareg['name']) {$error ='<font color="red">error</font>';	$dataexcel13=$error;	}else{	$dataexcel13=$data13;	}			//VALIDASI REGIONAL

				   $cekdataarea = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_area WHERE id_cost="'.$q['id_cost'].'" AND name="'.$data14.'"'));				//VALIDASI AREA
				   if ($data14 != $cekdataarea['name']) {$error ='<font color="red">error</font>';	$dataexcel14=$error;	}else{	$dataexcel14=$data14;	}			//VALIDASI AREA

				   $cekdatacab = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id_cost="'.$q['id_cost'].'" AND name="'.$data15.'"'));			//VALIDASI CABANG
				   if ($data15 != $cekdatacab['name']) {$error ='<font color="red">error</font>';	$dataexcel15=$error;	}else{	$dataexcel15=$data15;	}			//VALIDASI CABANG
				*/
				$cekdatacab = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id_cost="'.$q['id_cost'].'" AND name="'.strtoupper($data13).'" AND del IS NULL'));			//VALIDASI CABANG
				if (strtoupper($data13) != $cekdatacab['name']) {$error ='<font color="red" title="Nama cabang tidak sesuai">error</font>';	$dataexcel13=$error;	}else{	$dataexcel13=$data13;	}			//VALIDASI CABANG
				//VALIDASI DATA UPLOAD//

				$cekdataspk = mysql_fetch_array($database->doQuery('SELECT *,
														DATE_FORMAT(fu_ajk_spak.input_date,"%Y-%m-%d") AS inputDate,
														DATE_FORMAT(fu_ajk_spak.input_date,"%Y-%m-%d") + INTERVAL 3 MONTH AS BatasDate
														FROM fu_ajk_spak
														WHERE id_cost="'.$q['id_cost'].'" AND
															  id_polis="'.$fupolis['id'].'" AND
															  spak="'.$data2.'" AND
															  status="Aktif" AND
															  del IS NULL'));
				if ($cekdataspk['spak'] != $data2) {$error ='<font color="red" title="Nomor SPK tidak ada">error</font>'; $dataexcel2=$error;}
				elseif ($cekdataspk['BatasDate'] < $futoday) {$error ='<font color="red" title="Input data SPK sudah lebih dari 3 bulan">error</font>'; $dataexcel2=$error;}
				else{ $dataexcel2=$data2;}

				//$exl = $database->doQuery('INSERT IGNORE fu_ajk_peserta_tempf SET id_cost="'.$fu['id'].'",
				if ($data4 <= 9) { $data4_ = '0'.$data4;	}else{	$data4_ = $data4;}
				if ($data5 <= 9) { $data5_ = '0'.$data5;	}else{	$data5_ = $data5;}
				$datatgllahirnya = $data6.'-'.$data5_.'-'.$data4_;

				if ($data8 <= 9) { $data8_ = '0'.$data8;	}else{	$data8_ = $data8;}
				if ($data9 <= 9) { $data9_ = '0'.$data9;	}else{	$data9_ = $data9;}
				$datatglkreditnya = $data10.'-'.$data9_.'-'.$data8_;

				$cekDeklarasiSPK = mysql_fetch_array($database->doQuery('SELECT id_cost, id_polis, spaj, nama, id_peserta FROM fu_ajk_peserta WHERE id_cost="'.$q['id_cost'].'" AND id_polis="'.$fupolis['id'].'" AND spaj="'.$cekdataspk['spak'].'" AND status_aktif ="Inforce" AND del IS NULL'));
				//echo $cekDeklarasiSPK['nama'].'<br />';
				if ($cekDeklarasiSPK['nama']) {
					$error ='<font color="red" title="data sudah pernah diupload dengan ID Peserta '.$cekDeklarasiSPK['id_peserta'].'">error</font>'; $dataexcel3=$error;
				}else{
					$cekdataspknama = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_form WHERE idcost="'.$q['id_cost'].'" AND idspk="'.$cekdataspk['id'].'" AND del IS NULL'));
					//CEK APAKAH DIINPUT OLEH TAB ATAU WEB
					if (is_numeric($cekdataspknama['cabang'])) {
						$cekdataspknama = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_form WHERE idcost="'.$q['id_cost'].'" AND idspk="'.$cekdataspk['id'].'" AND cabang="'.$cekdatacab['id'].'" AND del IS NULL'));
					}else{
						$cekdataspknama = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_form WHERE idcost="'.$q['id_cost'].'" AND idspk="'.$cekdataspk['id'].'" AND cabang="'.strtoupper($data13).'" AND del IS NULL'));
					}

					//CEK APAKAH DIINPUT OLEH TAB ATAU WEB
					if (strtoupper($cekdataspknama['nama']) != strtoupper($data3)) {$error ='<font color="red" title="Nama debitur tidak sama dengan data SPK">error nama</font>'; $dataexcel3=$error;}
					elseif ($cekdataspknama['nama'] == strtoupper($data3) AND $cekdataspknama['dob']!=$datatgllahirnya) {$error ='<font color="red" title="tanggal lahir tidak sama dengan data SPK">error tgl lahir</font>'; $dataexcel3=$error;}
					elseif ($cekdataspknama['nama'] == strtoupper($data3) AND $cekdataspknama['dob']==$datatgllahirnya AND $cekdataspknama['idspk']!=$cekdataspk['id']) {$error ='<font color="red" title="Nomor SPK tidak sama">error nomor SPK</font>'; $dataexcel3=$error;}
					elseif ($cekdataspknama['nama'] == strtoupper($data3) AND $cekdataspknama['dob']==$datatgllahirnya AND $cekdataspknama['idspk']==$cekdataspk['id'] AND $cekdataspknama['plafond']!=$dataexcel7med) {$error ='<font color="red" title="nilai plafond tidak sama dengan data SPK">error plafond</font>'; $dataexcel7=$error;}	//penambahan filter data plafond
					else{ $dataexcel3=$data3;}
				}

				//CEK RELASI WILAYAH
				$cekdatacab = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id_cost="'.$fu['id'].'" '.$cekUserCabang.''));			//VALIDASI CABANG
				if (strtoupper($data13) != strtoupper($cekdatacab['name'])) {
					$cekCentral = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE name="'.strtoupper($data13).'" AND centralcbg="'.$cekdatacab['id'].'"'));
					if (strtoupper($data13) == strtoupper($cekCentral['name'])) {
						$dataexcel13=strtoupper($data13);
					}else{
						$error ='<font color="red" title="nama cabang tidak sesuai">error</font>';	$dataexcel13=$error;
					}
				}else{	$dataexcel13=strtoupper($data13);	}				//VALIDASI CABANG
				//CEK RELASI WILAYAH

				$mets = datediff($datatglkreditnya, $datatgllahirnya);
				$metTgl = explode(",",$mets);
				//	echo $mets['years'].' Tahun '.$mets['months'].' Bulan '.$mets['days'].' Hari';
				if ($metTgl[1] >= 6 ) {	$umur = $metTgl[0] + 1;	}else{	$umur = $metTgl[0];	}
				//echo $umur;
				if ($metTgl[1] == 5) {
					$sisahari = 30 - $metTgl[2];	$sisathn = $metTgl[0] + 1;
					if ($sisahari == 0) {
						$blnnnya ='';
					}else{
						$blnnnya =' (Dalam '.$sisahari.' hari usia akan bertambah menjadi '.$sisathn.')';
					}
				}else{	$blnnnya ='';	}
				//echo $mets['months'].' '.$mets['days'].' '.$umur.' | '.$blnnnya.'<br />';

				//VALIDASI TABEL MEDICAL STATUS MEDIK
				if ($fupolis['age_deviasi']=="Y") {
					$medik = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_medical WHERE id_cost="'.$q['id_cost'].'" AND  id_polis="'.$fupolis['id'].'" AND '.$umur.' BETWEEN age_from AND age_to AND '.$dataexcel7med.' BETWEEN si_from AND si_to'));
					$status_medik =$medik['type_medical'];
					if ($status_medik=="NM" OR $status_medik=="FCL" OR $status_medik=="SPD" OR $status_medik=="SPK" OR $status_medik=="SKKT")
					{	$status_pesertanya = "Approve";	}else{	$status_pesertanya = "Pending";	}
					$cekratepolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$q['id_cost'].'" AND id_polis="'.$fupolis['id'].'" AND usia="'.$umur.'" AND tenor="'.$_mettenor.'" AND status="baru" AND del IS NULL'));	//VALIDASI CEK TENOR DENGAN SETTING RATE POLIS
					if ($_mettenor != $cekratepolis['tenor']) {
						$error ='<font color="red" title="Tenor plafond tidak ada dalam rate">error</font>';	$dataexcel11=$error;
					}elseif($_mettenor != $cekdataspknama['tenor']){		//CEK PLAFOND DAN TENOR DATA SPK
						$error ='<font color="red" title="Tenor plafond tidak sama dengan tenor form SPK">error</font>';	$dataexcel11=$error;
					}//CEK PLAFOND DAN TENOR DATA SPK
					else{	$dataexcel11=$data11;	}			//VALIDASI CEK TENOR DENGAN SETTING RATE POLIS
				}else{
					$medik = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_medical WHERE id_cost="'.$q['id_cost'].'" AND  id_polis="'.$fupolis['id'].'" AND '.$umur.' BETWEEN age_from AND age_to AND '.$dataexcel7med.' BETWEEN si_from AND si_to'));
					$status_medik =$medik['type_medical'];
					if ($status_medik=="NM" OR $status_medik=="FCL" OR $status_medik=="SPD" OR $status_medik=="SPK" OR $status_medik=="SKKT")
					{	$status_pesertanya = "Approve";	}else{	$status_pesertanya = "Pending";	}
					$cekratepolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$q['id_cost'].'" AND id_polis="'.$fupolis['id'].'" AND type="F" AND tenor="'.$_mettenor.'" AND status="baru" AND del IS NULL'));	//VALIDASI CEK TENOR DENGAN SETTING RATE POLIS
					if ($_mettenor != $cekratepolis['tenor']) {
						$error ='<font color="red" title="Tenor plafond tidak ada dalam rate">error</font>';	$dataexcel11=$error;
					}elseif($_mettenor != $cekdataspknama['tenor']){		//CEK PLAFOND DAN TENOR DATA SPK
						$error ='<font color="red" title="Tenor plafond tidak sama dengan tenor form SPK">error</font>';	$dataexcel11=$error;
					}//CEK PLAFOND DAN TENOR DATA SPK
					else{	$dataexcel11=$data11;	}			//VALIDASI CEK TENOR DENGAN SETTING RATE POLIS
				}
				if (!$medik) {$error ='<font color="red"><a title="usia melewati batas maksimum polis">error</a></font>'; $dataexcel16=$error;}else{ $dataexcel16=$umur;}
				//VALIDASI TABEL MEDICAL STATUS MEDIK

				//CEK PRODUK
				$metCekProduk = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$fupolis['id'].'" AND del IS NULL'));
				if ($metCekProduk['nmproduk']==$data14) {	$dataexcel14=$data14;	}else{	$error ='<font color="red" title="Nama produk tidak sesuai">Error'; $dataexcel14=$error;	}
				//CEK PRODUK

				//CEK DOUBLE UPLOAD
				/*CEK TABLE TEMPRORARY*/
				$cekValDouble = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta_tempf
													  WHERE id_cost="'.$q['id_cost'].'" AND
													  		nama="'.strtoupper($data3).'" AND
													  		tgl_lahir="'.$datatgllahirnya.'" AND
													  		kredit_jumlah="'.$dataexcel7med.'" AND
													  		cabang="'.strtoupper($data13).'" AND del IS NULL'));
				if ($cekValDouble['id_temp']) {	$error ='<font color="red"> <br />(Double Upload)</font>'; $dataexcelDbl=$error;	}else{	$dataexcelDbl ='';	}

				$cekValDouble = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta_tempf
													  WHERE id_cost="'.$q['id_cost'].'" AND
													  		nama="'.strtoupper($data3).'" AND
													  		tgl_lahir="'.$datatgllahirnya.'" AND
													  		kredit_jumlah="'.$dataexcel7med.'" AND
													  		cabang="'.strtoupper($data13).'" AND del IS NULL'));
				if ($cekValDouble['spaj']==$data2) {	$error ='<font color="red"> <br />(Data Peserta sudah pernah diupload)</font>'; $dataexcelDbl2=$error;	}else{	$dataexcelDbl2 ='';	}
				/*CEK TABLE TEMPRORARY*/
				/*CEK TABLE PESERTA*/
				$cekValDouble = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta
														  WHERE id_cost="'.$q['id_cost'].'" AND
														  		nama="'.strtoupper($data3).'" AND
														  		tgl_lahir="'.$datatgllahirnya.'" AND
														  		kredit_jumlah="'.$dataexcel7med.'" AND
														  		cabang="'.strtoupper($data13).'" AND
														  		status_aktif ="Inforce" AND del IS NULL'));
				if ($cekValDouble['id']) {
					if ($cekValDouble['id_dn']=="") {
						$error ='<font color="red"> <br />(Data telah diupload tetapi belum di buat debitnote)</font>'; $dataexcelDbl3=$error;
					}elseif ($cekValDouble['id_dn']!="") {
						$cekValDoubleDN = mysql_fetch_array($database->doQuery('SELECT id, dn_kode FROM fu_ajk_dn WHERE id="'.$cekValDouble['id_dn'].'"'));
						$error ='<font color="red"> <br />(Data telah diupload dengan nomor debitnote '.$cekValDoubleDN['dn_kode'].')</font>'; $dataexcelDbl3=$error;
					}else{
						$dataexcelDbl3 ='';
					}
				}
				/*CEK TABLE PESERTA*/
				//CEK DOUBLE UPLOAD

				//CEK PRODUK MPP
				if ($fupolis['mpptype']=="Y") {
					if ($data16==""){ $error ='<font color="red" title="Masukan jumlah bulan MPP">error</font>'; $dataexcel18=$error;}
					else{
						if ($data16 > $fupolis['mppbln_max']) {	$error ='<font color="red" title="Jumlah bulan MPP melewati batas bulan setup produk">error</font>'; $dataexcel18=$error;	}
						else{	$dataexcel18=$data16;	}
					}
				}else{
					if ($data16!=""){ $error ='<font color="red" title="Data debitur bukan Masa Pra Pensiun">error</font>'; $dataexcel18=$error;}
					else{
						$dataexcel18=$data16;
					}
				}
				//CEK PRODUK MPP

				if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
				echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
					<td align="center">'.$data1.'</td>
					<td align="center">'.$dataexcel17.'</td>
					<td align="center">'.$dataexcel2.'</td>
					<td>'.strtoupper($dataexcel3).''.$blnnnya.''.$dataexcelDbl.''.$dataexcelDbl2.''.$dataexcelDbl3.'</td>
					<td align="center">'.$dataexcel4.'</td>
					<td align="center">'.$dataexcel5.'</td>
					<td align="center">'.$dataexcel6.'</td>
					<td align="center">'.$dataexcel16.'</td>
					<td align="right">'.$dataexcel7.'</td>
					<td align="center">'.$dataexcel8.'</td>
					<td align="center">'.$dataexcel9.'</td>
					<td align="center">'.$dataexcel10.'</td>
					<td align="center">'.$dataexcel11.'</td>
					<td align="center">'.$cekdataspk['ext_premi'].'</td>
					<td align="center">'.$dataexcel13.'</td>
					<td align="center">'.$dataexcel14.'</td>
					<td align="center">'.$dataexcel18.'</td>
					<td align="center">'.$data15.'</td>
				</tr>';
				/*
				   $met = $database->doQuery('INSERT INTO fu_ajk_peserta_tempf SET id_cost="'.$fu['id'].'",
				   id_polis="'.$fupolis['id'].'",
				   namafile="'.$_FILES['userfile']['name'].'",
				   no_urut="'.$data1.'",
				   spaj="'.$data2.'",
				   type_data="SPK",
				   nama_mitra="",
				   nama="'.$data3.'",
				   gender="",
				   tgl_lahir="'.$datatgllahirnya.'",
				   usia="'.$umur.'",
				   kredit_tgl="'.$datatglkreditnya.'",
				   kredit_jumlah="'.$dataexcel7med.'",
				   kredit_tenor="'.$data11.'",
				   kredit_akhir="",
				   premi="",
				   disc_premi="",
				   bunga="",
				   biaya_adm="",
				   ext_premi="'.$data12.'",
				   totalpremi="",
				   badant="",
				   badanb="",
				   status_medik="",
				   status_bayar="0",
				   status_aktif="Upload",
				   ket="'.$data15.'",
				   mppbln="'.$data16.'",
				   regional="'.$cekdatawilayah['regional'].'",
				   area="'.$cekdatawilayah['area'].'",
				   cabang="'.$data13.'",
				   input_by ="'.$_SESSION['nm_user'].'",
				   input_time ="'.$futgl.'"');
				*/
			}
	if ($error) {	echo '<tr><td colspan="27" align="center"><font color="red"><blink>Silahkan lengkapi kolom yang error !!!</blink><br /><a href="ajk_uplspak.php?el=cancelspk&fileclient='.$_FILES['userfile']['name'].'">Back</a></font></td></tr>';	}
	else{
				for ($i=10; $i<=$_REQUEST['bataskolom']; $i++)
				{
					/*
					   $data1=$data->val($i, 1);		//no
					   $data17=$data->val($i, 2);		//NAMA MITRA
					   $data2=$data->val($i, 2);		//S P K
					   $data3=$data->val($i, 3);		//NAMA TERTANGGUNG
					   $data4=$data->val($i, 4);		//TANGGAL LAHIR (TGL)
					   $data5=$data->val($i, 5);		//TANGGAL LAHIR (BLN)
					   $data6=$data->val($i, 6);		//TANGGAL LAHIR (THN)
					   $data7=$data->val($i, 7);		//UANG ASURANSI
					   $data8=$data->val($i, 8);		//MULAI ASURANSI (TGL)
					   $data9=$data->val($i, 9);		//MULAI ASURANSI (BLN)
					   $data10=$data->val($i, 10);		//MULAI ASURANSI (THN)
					   $data11=$data->val($i, 11);		//MASA ASURANSI
					   $data12=$data->val($i, 12);		//EXT. PREMI
					   $data13=$data->val($i, 13);		//CABANG
					   $data14=$data->val($i, 14);		//PRODUK
					   $data15=$data->val($i, 15);		//KETERNGAN
					*/
					$data1=$data->val($i, 1);		//no
					$data17=$data->val($i, 2);		//NAMA MITRA
					$data2=$data->val($i, 3);		//S P K
					$data3=$data->val($i, 4);		//NAMA TERTANGGUNG
					$data4=$data->val($i, 5);		//TANGGAL LAHIR (TGL)
					$data5=$data->val($i, 6);		//TANGGAL LAHIR (BLN)
					$data6=$data->val($i, 7);		//TANGGAL LAHIR (THN)
					$data7=$data->val($i, 8);		//UANG ASURANSI
					$data8=$data->val($i, 9);		//MULAI ASURANSI (TGL)
					$data9=$data->val($i, 10);		//MULAI ASURANSI (BLN)
					$data10=$data->val($i, 11);		//MULAI ASURANSI (THN)
					$data11=$data->val($i, 12);		//MASA ASURANSI
					$data12=$data->val($i, 13);		//EXT. PREMI
					$data13=$data->val($i, 14);		//CABANG
					$data14=$data->val($i, 15);		//PRODUK
					$data15=$data->val($i, 16);		//KETERANGAN
					$data16=$data->val($i, 17);		//MPP

					if ($q['cabang']!="PUSAT") {	$cekUserCabang = ' AND name="'.$q['cabang'].'"';	}else{	$cekUserCabang = ' AND name="'.$data13.'"';	}

					//VALIDASI DATA UPLOAD//
					if ($data2==""){ $error ='<font color="red">error</font>'; $dataexcel2=$error;}else{ $dataexcel2=$data2;}
					if ($data3==""){ $error ='<font color="red">error</font>'; $dataexcel3=$error;}else{ $dataexcel3=$data3;}
					if ($data4==""){ $error ='<font color="red">error</font>'; $dataexcel4=$error;}else{ $dataexcel4=$data4;}
					if ($data5==""){ $error ='<font color="red">error</font>'; $dataexcel5=$error;}else{ $dataexcel5=$data5;}
					if ($data6==""){ $error ='<font color="red">error</font>'; $dataexcel6=$error;}else{ $dataexcel6=$data6;}

					if ($data17!=""){
						$metMitra = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_grupproduk WHERE nmproduk="'.$data17.'"'));
						if (!$metMitra['nmproduk']) {
							$error ='<font color="red">error</font>'; $dataexcel17=$error;
						}else{
							$dataexcel17=$data17;
						}
					}else{
						$dataexcel17="BUKOPIN";
					}
					//$titikpos = strpos($data7, ".");
					//$komapos = strpos($data7, ",");
					if ($data7==""){ $error ='<font color="red">error</font>'; $dataexcel7=$error;}
					//elseif (strpos($data7, ".")) { $error ='<font color="red">error</font>'; $dataexcel7=$error;}	//VALIDASI NILAI ASURANSI DENGAN TITIK
					//elseif (strpos($data7, ",")) { $error ='<font color="red">error</font>'; $dataexcel7=$error;}	//VALIDASI NILAI ASURANSI DENGAN KOMA
					//elseif (strpos($data7, "*")) { $error ='<font color="red">error</font>'; $dataexcel7=$error;}	//VALIDASI NILAI ASURANSI DENGAN KOMA
					//else{ $dataexcel7=duit($data7);	$dataexcel7med = $data7;	}
					else{
						$asting = array(" ", ",", ".", "*");
						$replace = array('');

						$malestr = str_replace($asting, $replace, $data7);
						//echo $malestr;
						$dataexcel7=duit($malestr);	$dataexcel7med = $malestr;
					}


					if ($data8==""){ $error ='<font color="red">error</font>'; $dataexcel8=$error;}else{ $dataexcel8=$data8;}
					if ($data9==""){ $error ='<font color="red">error</font>'; $dataexcel9=$error;}else{ $dataexcel9=$data9;}
					if ($data10==""){ $error ='<font color="red">error</font>'; $dataexcel10=$error;}else{ $dataexcel10=$data10;}
					if ($data11==""){ $error ='<font color="red">error</font>'; $dataexcel11=$error;}else{ $dataexcel11=$data11;}
					if ($data13==""){ $error ='<font color="red">error</font>'; $dataexcel15=$error;}else{ $dataexcel15=$data13;}

					if(!is_numeric($data4)){$error ='<font color="red">error</font>'; $dataexcel4=$error;}else{ $dataexcel4=$data4;}		//VALIDASI HARI
					if(strlen($data4 > 31 )){$error ='<font color="red">error</font>'; $dataexcel4=$error;}else{ $dataexcel4=$data4;}		//VALIDASI HARI

					if(!is_numeric($data5)){$error ='<font color="red">error</font>'; $dataexcel5=$error;}else{ $dataexcel5=$data5;}		//VALIDASI BULAN
					if(strlen($data5 > 12 )){$error ='<font color="red">error</font>'; $dataexcel5=$error;}else{ $dataexcel5=$data5;}		//VALIDASI BULAN

					if(!is_numeric($data6)){$error ='<font color="red">error</font>'; $dataexcel6=$error;}else{ $dataexcel6=$data6;}		//VALIDASI TAHUN
					if(strlen($data6 > $dateY )){$error ='<font color="red">error</font>'; $dataexcel6=$error;}else{ $dataexcel6=$data6;}		//VALIDASI TAHUN

					if(!is_numeric($data8)){$error ='<font color="red">error</font>'; $dataexcel8=$error;}else{ $dataexcel8=$data8;}		//VALIDASI HARI
					if(strlen($data8 > 31 )){$error ='<font color="red">error</font>'; $dataexcel8=$error;}else{ $dataexcel8=$data8;}		//VALIDASI HARI

					if(!is_numeric($data9)){$error ='<font color="red">error</font>'; $dataexcel9=$error;}else{ $dataexcel9=$data9;}		//VALIDASI BULAN
					if(strlen($data9 > 12 )){$error ='<font color="red">error</font>'; $dataexcel9=$error;}else{ $dataexcel9=$data9;}		//VALIDASI BULAN

					if(!is_numeric($data10)){$error ='<font color="red">error</font>'; $dataexcel10=$error;}else{ $dataexcel10=$data10;}		//VALIDASI TAHUN
					if(strlen($data10 > $dateY )){$error ='<font color="red">error</font>'; $dataexcel10=$error;}else{ $dataexcel10=$data10;}		//VALIDASI TAHUN


					//FORMAT TERNOR DLM BULAN DIBAGI 12
					//$_mettenor = $data11 * 12;	TENOR BULAN
					$_mettenor = $data11;


					/*
					   $cekdatareg = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE id_cost="'.$q['id_cost'].'" AND name="'.$data13.'"'));			//VALIDASI REGIONAL
					   if ($data13 != $cekdatareg['name']) {$error ='<font color="red">error</font>';	$dataexcel13=$error;	}else{	$dataexcel13=$data13;	}			//VALIDASI REGIONAL

					   $cekdataarea = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_area WHERE id_cost="'.$q['id_cost'].'" AND name="'.$data14.'"'));				//VALIDASI AREA
					   if ($data14 != $cekdataarea['name']) {$error ='<font color="red">error</font>';	$dataexcel14=$error;	}else{	$dataexcel14=$data14;	}			//VALIDASI AREA
					*/
					$cekdatacab = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id_cost="'.$q['id_cost'].'" AND name="'.$data13.'"'));			//VALIDASI CABANG
					if ($data13 != $cekdatacab['name']) {$error ='<font color="red">error</font>';	$dataexcel13=$error;	}else{	$dataexcel13=$data13;	}			//VALIDASI CABANG

					//VALIDASI DATA UPLOAD//

					$cekdataspk = mysql_fetch_array($database->doQuery('SELECT *,
														DATE_FORMAT(fu_ajk_spak.input_date,"%Y-%m-%d") AS inputDate,
														DATE_FORMAT(fu_ajk_spak.input_date,"%Y-%m-%d") + INTERVAL 3 MONTH AS BatasDate
														FROM fu_ajk_spak
														WHERE id_cost="'.$q['id_cost'].'" AND
															  id_polis="'.$fupolis['id'].'" AND
															  spak="'.$data2.'" AND
															  status="Aktif" AND
															  del IS NULL'));
					//$cekdataspknya = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak WHERE id_cost="'.$q['id_cost'].'" AND spak="'.$data2.'" AND status="Aktif"'));
					if ($cekdataspk['spak'] != $data2) {$error ='<font color="red" title="Nomor SPK tidak ada">error</font>'; $dataexcel2=$error;}
					elseif ($cekdataspk['BatasDate'] < $futoday) {$error ='<font color="red" title="Input data SPK sudah lebih dari 3 bulan">error</font>'; $dataexcel2=$error;}
					else{ $dataexcel2=$data2;}

					//$exl = $database->doQuery('INSERT IGNORE fu_ajk_peserta_tempf SET id_cost="'.$fu['id'].'",
					if ($data4 <= 9) { $data4_ = '0'.$data4;	}else{	$data4_ = $data4;}
					if ($data5 <= 9) { $data5_ = '0'.$data5;	}else{	$data5_ = $data5;}
					$datatgllahirnya = $data6.'-'.$data5_.'-'.$data4_;

					if ($data8 <= 9) { $data8_ = '0'.$data8;	}else{	$data8_ = $data8;}
					if ($data9 <= 9) { $data9_ = '0'.$data9;	}else{	$data9_ = $data9;}
					$datatglkreditnya = $data10.'-'.$data9_.'-'.$data8_;

					$cekDeklarasiSPK = mysql_fetch_array($database->doQuery('SELECT id_cost, id_polis, spaj, nama FROM fu_ajk_peserta WHERE id_cost="'.$q['id_cost'].'" AND id_polis="'.$fupolis['id'].'" AND spaj="'.$cekdataspk['spak'].'" AND status_aktif ="Inforce" AND del IS NULL'));
					//echo $cekDeklarasiSPK['nama'].'<br />';
					if ($cekDeklarasiSPK['nama']) {
						$error ='<font color="red">error (data sudah pernah diupload)</font>'; $dataexcel3=$error;
					}else{
						$cekdataspknama = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_form WHERE idcost="'.$q['id_cost'].'" AND idspk="'.$cekdataspk['id'].'" AND del IS NULL'));
						//CEK APAKAH DIINPUT OLEH TAB ATAU WEB
						if (is_numeric($cekdataspknama['cabang'])) {
							$cekdataspknama = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_form WHERE idcost="'.$q['id_cost'].'" AND idspk="'.$cekdataspk['id'].'" AND cabang="'.$cekdatacab['id'].'" AND del IS NULL'));
						}else{
							$cekdataspknama = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_form WHERE idcost="'.$q['id_cost'].'" AND idspk="'.$cekdataspk['id'].'" AND cabang="'.$data13.'" AND del IS NULL'));
						}
						//CEK APAKAH DIINPUT OLEH TAB ATAU WEB

						if ($cekdataspknama['nama'] != strtoupper($data3)) {$error ='<font color="red">error nama</font>'; $dataexcel3=$error;}
						elseif ($cekdataspknama['nama'] == strtoupper($data3) AND $cekdataspknama['dob']!=$datatgllahirnya) {$error ='<font color="red">error tgl lahir</font>'; $dataexcel3=$error;}
						elseif ($cekdataspknama['nama'] == strtoupper($data3) AND $cekdataspknama['dob']==$datatgllahirnya AND $cekdataspknama['idspk']!=$cekdataspk['id']) {$error ='<font color="red">error nomor SPK</font>'; $dataexcel3=$error;}
						else{ $dataexcel3=$data3;}
					}

					//CEK RELASI WILAYAH
					/*
					   $cekdatawilayah = mysql_fetch_array($database->doQuery('SELECT pengguna.id, pengguna.id_cost, pengguna.cabang, v_wilayah.regional AS nmReg, v_wilayah.area AS nmArea, v_wilayah.cabang AS nmCab
					   FROM pengguna
					   INNER JOIN v_wilayah ON pengguna.id_cost = v_wilayah.id_cost AND pengguna.cabang = v_wilayah.cabang
					   WHERE pengguna.id_cost="'.$q['id_cost'].'" AND pengguna.cabang = "'.$data13.'"'));
					   if ($data13=="") {	$error ='<font color="red">error</font>'; $dataexcel13=$error;	}
					   else
					   {	if ($cekdatawilayah['nmCab']!=$data13) {$error ='<font color="red">error</font>'; $dataexcel13=$error;}
					   else{
					   //CEK DATA CABANG
					   $cekdatacab = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id_cost="'.$fu['id'].'" '.$cekUserCabang.''));
					   if ($data13 != $cekdatacab['name']) {$error ='<font color="red">error</font>';	$dataexcel13=$error;	}
					   else{	$dataexcel13=$data13;	}
					   }
					   }
					*/


					$cekdatacab = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id_cost="'.$fu['id'].'" '.$cekUserCabang.''));			//VALIDASI CABANG
					if ($data13 != $cekdatacab['name']) {
						$cekCentral = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE name="'.$data13.'" AND centralcbg="'.$cekdatacab['id'].'"'));
						if ($data13 == $cekCentral['name']) {
							$dataexcel13=$data13;
							$cekdatawilayah = mysql_fetch_array($database->doQuery('SELECT * FROM v_wilayah WHERE id_cost="'.$q['id_cost'].'" AND cabang="'.$data13.'" AND delReg IS NULL AND delArea IS NULL AND delCab IS NULL'));
						}else{
							$error ='<font color="red">error</font>';	$dataexcel13=$error;
						}
					}else{	$dataexcel13=$data13;
						$cekdatawilayah = mysql_fetch_array($database->doQuery('SELECT * FROM v_wilayah WHERE id_cost="'.$q['id_cost'].'" AND cabang="'.$data13.'" AND delReg IS NULL AND delArea IS NULL AND delCab IS NULL'));
					}				//VALIDASI CABANG
					//CEK RELASI WILAYAH


					$mets = datediff($datatglkreditnya, $datatgllahirnya);
					$metTgl = explode(",",$mets);
					//	echo $mets['years'].' Tahun '.$mets['months'].' Bulan '.$mets['days'].' Hari';
					if ($metTgl[1] >= 6 ) {	$umur = $metTgl[0] + 1;	}else{	$umur = $metTgl[0];	}
					//echo $umur;
					if ($metTgl[1] == 5) {	$sisahari = 30 - $metTgl[2];	$sisathn = $metTgl[0] + 1; $blnnnya ='<br />Dalam <font color="blue">'.$sisahari.'</font> hari usia akan bertambah menjadi '.$sisathn.'';	}else{	$blnnnya ='';	}
					//echo $mets['months'].' '.$mets['days'].' '.$umur.' | '.$blnnnya.'<br />';

					//VALIDASI TABEL MEDICAL STATUS MEDIK
					if ($fupolis['age_deviasi']=="Y") {
						$medik = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_medical WHERE id_cost="'.$q['id_cost'].'" AND  id_polis="'.$fupolis['id'].'" AND '.$umur.' BETWEEN age_from AND age_to AND '.$dataexcel7med.' BETWEEN si_from AND si_to'));
						$status_medik =$medik['type_medical'];
						if ($status_medik=="NM" OR $status_medik=="FCL" OR $status_medik=="SPD" OR $status_medik=="SPK" OR $status_medik=="SKKT")
						{	$status_pesertanya = "Approve";	}else{	$status_pesertanya = "Pending";	}
						$cekratepolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$q['id_cost'].'" AND id_polis="'.$fupolis['id'].'" AND usia="'.$umur.'" AND tenor="'.$_mettenor.'" AND status="baru" AND del IS NULL'));	//VALIDASI CEK TENOR DENGAN SETTING RATE POLIS
						if ($_mettenor != $cekratepolis['tenor']) {$error ='<font color="red">error</font>';	$dataexcel11=$error;	}else{	$dataexcel11=$data11;	}			//VALIDASI CEK TENOR DENGAN SETTING RATE POLIS

					}else{
						$medik = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_medical WHERE id_cost="'.$q['id_cost'].'" AND  id_polis="'.$fupolis['id'].'" AND '.$umur.' BETWEEN age_from AND age_to AND '.$dataexcel7med.' BETWEEN si_from AND si_to'));
						$status_medik =$medik['type_medical'];
						if ($status_medik=="NM" OR $status_medik=="FCL" OR $status_medik=="SPD" OR $status_medik=="SPK" OR $status_medik=="SKKT")
						{	$status_pesertanya = "Approve";	}else{	$status_pesertanya = "Pending";	}
						$cekratepolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$q['id_cost'].'" AND id_polis="'.$fupolis['id'].'" AND tenor="'.$_mettenor.'" AND status="baru" AND del IS NULL'));	//VALIDASI CEK TENOR DENGAN SETTING RATE POLIS
						if ($_mettenor != $cekratepolis['tenor']) {$error ='<font color="red">error</font>';	$dataexcel11=$error;	}else{	$dataexcel11=$data11;	}			//VALIDASI CEK TENOR DENGAN SETTING RATE POLIS
					}
					if (!$medik) {$error ='<font color="red"><a title="usia melewati batas maksimum polis">error</a></font>'; $dataexcel16=$error;}else{ $dataexcel16=$umur;}
					//VALIDASI TABEL MEDICAL STATUS MEDIK

					/*
					   //CEK VALIDASI PRODUK
					   if ($data14==""){ $error ='<font color="red">error</font>'; $dataexcel3=$error;}
					   elseif($data14!=$fupolis['nmproduk']){ $error ='<font color="red">error produk</font>'; $dataexcel3=$error;}
					   else{ }
					   //CEK VALIDASI PRODUK
					*/

					//CEK PRODUK
					$metCekProduk = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$fupolis['id'].'" AND del IS NULL'));
					if ($metCekProduk['nmproduk']==$data14) {	$dataexcel14=$data14;	}else{	$error ='<font color="red">Error'; $dataexcel14=$error;	}
					//CEK PRODUK

					//CEK DOUBLE UPLOAD
					$cekValDouble = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta_tempf WHERE id_cost="'.$q['id_cost'].'" AND nama="'.$data3.'" AND tgl_lahir="'.$datatgllahirnya.'" AND kredit_jumlah="'.$dataexcel7med.'" AND cabang="'.$data13.'" AND del IS NULL'));
					if ($cekValDouble['id_temp']) {	$error ='<font color="red"> <br />(Double Upload)</font>'; $dataexcelDbl=$error;	}else{	$dataexcelDbl ='';	}

					//CEK DATA DOUBLE UPLOAD DAN STATUS PEMABAYARAN
					/*$metdouble = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE nama="'.$data4.'" AND tgl_lahir="'.$datatgllahirnya.'" AND kredit_tgl="'.$datatglkreditnya.'" AND kredit_jumlah="'.$dataexcel7med.'" AND kredit_tenor="'.$_mettenor.'" AND status_peserta IS NULL AND del IS NULL'));
					   //	echo('SELECT * FROM fu_ajk_peserta WHERE nama="'.$data4.'" AND tgl_lahir="'.$datatgllahirnya.'" AND kredit_tgl="'.$datatglkreditnya.'" AND kredit_jumlah="'.$dataexcel8.'" AND kredit_tenor="'.$met_tenor.'" AND del IS NULL');
					   if ($metdouble['id_dn']!="" AND $metdouble['status_bayar']=="1") {
					   $ceknomor_dn = mysql_fetch_array($database->doQuery('SELECT id, dn_kode FROM fu_ajk_dn WHERE id="'.$metdouble['id_dn'].'"'));
					   $no_error ='<font color="red">Data sudah pernah di upload ('.$ceknomor_dn['dn_kode'].')</font>'; $dataexcel4=$data4.'<br />'.$no_error;
					   }
					   elseif ($metdouble['id_dn']!="" AND $metdouble['status_bayar']=="0") {
					   $error ='<font color="red">Data Unpaid ('.$metdouble['nama'].' - '._convertDate($metdouble['tgl_lahir']).')</font>'; $dataexcel4=$error;
					   }elseif ($metdouble['id_dn']=="") {
					   $error ='<font color="red">Data Double belum dibuat data DN</font>'; $dataexcel4=$error;
					   }
					   else	{	$dataexcel4=$data4;	}
					*/
					//CEK DATA DOUBLE UPLOAD DAN STATUS PEMABAYARAN
					//CEK DOUBLE UPLOAD

					//CEK PRODUK MPP
					if ($fupolis['tab']=="Y") {
						if ($data16==""){ $error ='<font color="red" title="Masukan jumlah bulan MPP">error</font>'; $dataexcel18=$error;}
						else{
							if ($data16 > $fupolis['mppbln_max']) {	$error ='<font color="red" title="Jumlah bulan MPP melewati batas bulan setup produk">error</font>'; $dataexcel18=$error;	}
							else{	$dataexcel18=$data16;	}
						}
					}else{
						if ($data16!=""){ $error ='<font color="red" title="Data debitur bukan Masa Pra Pensiun">error</font>'; $dataexcel18=$error;}
						else{
							$dataexcel18=$data16;
						}
					}
					//CEK PRODUK MPP
					$metMitra = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_grupproduk WHERE id_cost="'.$q['id_cost'].'" AND nmproduk="'.strtoupper($data17).'"'));

					$met = $database->doQuery('INSERT INTO fu_ajk_peserta_tempf SET id_cost="'.$fu['id'].'",
																  id_polis="'.$fupolis['id'].'",
																  namafile="'.$fileUploadAdonaiSPK.'",
																  no_urut="'.$data1.'",
																  spaj="'.$data2.'",
																  type_data="SPAJ",
																  nama_mitra="'.$metMitra['id'].'",
																  nama="'.trim(strtoupper($data3)).'",
																  gender="",
																  tgl_lahir="'.$datatgllahirnya.'",
																  usia="'.$umur.'",
																  kredit_tgl="'.$datatglkreditnya.'",
																  kredit_jumlah="'.$dataexcel7med.'",
																  kredit_tenor="'.$data11 * 12 .'",
																  kredit_akhir="",
																  premi="",
																  disc_premi="",
																  bunga="",
																  biaya_adm="",
																  ext_premi="'.$data12.'",
																  totalpremi="",
																  badant="",
																  badanb="",
																  status_medik="'.$status_medik.'",
																  status_bayar="0",
																  status_aktif="Manual Upload",
																  mppbln="'.$dataexcel18.'",
																  regional="'.$cekdatawilayah['regional'].'",
																  area="'.$cekdatawilayah['area'].'",
																  cabang="'.$data13.'",
																  photodebitur="'.$cekdataspknama['filefotodebitursatu'].'",
																  photoktp="'.$cekdataspknama['filefotoktp'].'",
																  ket="'.$data15.'",
																  input_by ="'.$_SESSION['nm_user'].'",
															      input_time ="'.$futgl.'"');
		}
echo '<tr><td colspan="27" align="center"><a title="Approve data upload peserta SPK" href="ajk_uploader.php?er=approveflspktab&nmfile='.$fileUploadAdonaiSPK.'&dateupl='.$futgl.'&idc='.$fu['id'].'" onClick="if(confirm(\'Apakah anda sudah yakin dengan semua data peserta SPK ini ?\')){return true;}{return false;}"><img src="image/save.png" border="0" width="50"></a>
	   &nbsp; &nbsp; <a title="Batalkan data upload peserta SPK" href="ajk_uploader.php?er=cancelspktab&fileclient='.$fileUploadAdonaiSPK.'"><img src="image/deleted.png" border="0" width="50"></a></td></tr>';	}
echo '</table></form>';
}
	;
	break;

case "cancelspktab":
	$cha = $database->doQuery('DELETE FROM fu_ajk_peserta_tempf WHERE namafile="'.$_REQUEST['fileclient'].'"');
	header("location:ajk_uploader.php?er=tab");
	;
	break;


case "approveflspktab":
	$met_appr = $database->doQuery('SELECT * FROM fu_ajk_peserta_tempf WHERE id_cost="'.$_REQUEST['idc'].'" AND namafile="'.$_REQUEST['nmfile'].'" AND input_time="'.$_REQUEST['dateupl'].'"');

		$message .= '<table width="100%" broder="0" cellpadding="3" cellspacing="1">
					 <tr bgcolor="#add8e6"><td width="1%">NO</td>
				 	 <td align="center" width="5%">SPAJ</td>
				 	 <td align="center">NAMA</td>
				 	 <td align="center" width="5%">D O B</td>
				 	 <td align="center" width="8%">TGL KREDIT</td>
				 	 <td align="center" width="10%">U P</td>
				 	 <td align="center" width="5%">TENOR</td>
				 	 <td align="center" width="5%">MPP</td>
				 	 <td align="center" width="10%">CABANG</td>
					 </tr>';
		while ($mamet_appr = mysql_fetch_array($met_appr)) {
			if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
			$message .='<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
					<td align="center">'.++$no.'</td>
					<td align="center">'.$mamet_appr['spaj'].'</td>
					<td>'.$mamet_appr['nama'].'</td>
					<td align="center">'._convertDate($mamet_appr['tgl_lahir']).'</td>
					<td align="center">'._convertDate($mamet_appr['kredit_tgl']).'</td>
					<td align="right">'.duit($mamet_appr['kredit_jumlah']).'</td>
					<td align="center">'.$mamet_appr['kredit_tenor'].'</td>
					<td align="center">'.$mamet_appr['mppbln'].'</td>
					<td>'.$mamet_appr['cabang'].'</td>
			  		</tr>';
		}
	$message .='</table>';
	echo $message;

	$mail = new PHPMailer;
	$mail->IsSMTP();
	$mail->Host = SMTP_HOST; //Hostname of the mail server
	$mail->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
	$mail->Username = SMTP_UNAME; //Username for SMTP authentication any valid email created in your domain
	$mail->Password = SMTP_PWORD; //Password for SMTP authentication
	$mail->AddReplyTo = SMTP_REPLYMAIL; //reply-to address
	$mail->debug = 1;
	$mail->SMTPSecure = "ssl";
	$mail->SetFrom ($q['email'], $q['nm_lengkap']); //From address of the mail
	$mail->Subject = "AJK-Online - UPLOAD DATA PSERTA BARU SPK"; //Subject od your mail
	//EMAIL PENERIMA  SPV CLIENT
	$mailsupervisorajk = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="'.$q['id_cost'].'" AND id_polis="'.$q['id_polis'].'" AND level="'.$q['level'].'" AND status=""');
	while ($_mailsupervisorajk = mysql_fetch_array($mailsupervisorajk)) {
		$mail->AddAddress($_mailsupervisorajk['email'], $_mailsupervisorajk['nm_lengkap']); //To address who will receive this email
	}
	//EMAIL PENERIMA  SPV CLIENT

	$mail->AddCC("penting_ga@hotmail.com, sysdev@kode.web.id, arief@arief.kurniawan.com, gunarso@adonai.co.id");
	//$mail->AddAddress($mailsupervisorajk['email'], $mailsupervisorajk['nm_lengkap']); //To address who will receive this email
	//$mail->AddCC($approvemail);
	$mail->MsgHTML('<table><tr><th>Data peserta baru SPK telah diinput oleh <b>'.$_SESSION['nm_user'].' selaku staff AJK-Online pada tanggal '.$futgl.'</tr></table>'.$message); //Put your body of the message you can place html code here
	$send = $mail->Send(); //Send the mails
	//echo $mail;
//echo $message.'<br />';

echo '<center>Data Peserta Baru SPK telah di upload oleh <b>'.$_SESSION['nm_user'].'</b>, tunggu konfirmasi selanjutnya dari Supervisor untuk Approve data yang telah di upload.<br />
	  <a href="ajk_uplspak.php?el=fl_spk">Kembali Ke Halaman Utama</a></center>';
		;
		break;


	default:
		;
} // switch
?>
<script type="text/javascript" language="javascript">
function checkfile(sender) {
	var validExts = new Array(".xlsx", ".xls", ".csv");
	var fileExt = sender.value;
	fileExt = fileExt.substring(fileExt.lastIndexOf('.'));
	if (validExts.indexOf(fileExt) < 0) {
	alert("Invalid file selected, valid files are of " +
	validExts.toString() + " types.");
	return false;
	}
	else return true;
}
</script>
