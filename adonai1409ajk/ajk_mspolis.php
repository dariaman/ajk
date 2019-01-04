<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ----------------------------------------------------------------------------------
include_once ("ui.php");
echo '
<script type="text/javascript">
function ohYesOhNo() {
	if (document.getElementById("general").checked) {
		document.getElementById("ifYes").style.display = "block";
	} else {
		document.getElementById("ifYes").style.display = "none";
	}
}
</script>
';
include_once ("../includes/functions.php");
connect();
if (isset($_SESSION['nm_user'])) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));}
$futgl = date("Y-m-d g:i:a");
$DatePolis = date("dmy");

switch ($_REQUEST['op']) {
	case "newpol":
echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
     <tr><th width="95%" align="left">Setting Produk Baru</font></th>
     	 <th align="center"><a href="ajk_mspolis.php"><img border="0" src="image/Backward-64.png" width="25"></a></th>
     </tr>
     </table><br />';
if ($_REQUEST['ope']=="Simpan")
{
	if ($_REQUEST['typeProduk']=="general")
	{
		$_REQUEST['max_up_ajk'] = $_POST['max_up_ajk'];			if (!$_REQUEST['max_up_ajk'])  $error8 .='<blink><font color=red>Max UP AJK wajib diisi</font></blink><br>';
		$_REQUEST['max_up_jamkred'] = $_POST['max_up_jamkred'];	if (!$_REQUEST['max_up_jamkred'])  $error9 .='<blink><font color=red>Max UP Jamkred wajib diisi</font></blink><br>';
		$_REQUEST['max_up_fire'] = $_POST['max_up_fire'];		if (!$_REQUEST['max_up_fire'])  $error10 .='<blink><font color=red>Max UP Fire wajib diisi</font></blink><br>';
		$_REQUEST['max_up_mv'] = $_POST['max_up_mv'];			if (!$_REQUEST['max_up_mv'])  $error11 .='<blink><font color=red>Max UP MV wajib diisi</font></blink><br>';
	}
	$_REQUEST['name'] = $_POST['name'];
	if (!$_REQUEST['name'])  $error1 .='<blink><font color=red>Silahkan pilih nama perusahaan</font></blink><br>';
	if ($_REQUEST['typePolis']=="openpolis")
	{
		$_REQUEST['effdate'] = $_POST['effdate'];	if (!$_REQUEST['effdate'])  $error2 .='<blink><font color=red>Tentukan tanggal efektif polis</font></blink><br>';
		$queryeffdate = $_REQUEST['effdate'];
	}
	else
	{
		$_REQUEST['effdate1'] = $_POST['effdate1'];	if (!$_REQUEST['effdate1'])  $error2 .='<blink><font color=red>Tentukan tanggal awal efektif polis</font></blink><br>';
		$_REQUEST['enddate'] = $_POST['enddate'];	if (!$_REQUEST['enddate'])  $error2 .='<blink><font color=red>Tentukan tanggal akhir efektif polis</font></blink><br>';
		$queryeffdate = $_REQUEST['effdate1'];
	}

	$_REQUEST['benefit'] = $_POST['benefit'];
	if (!$_REQUEST['benefit'])  $error3 .='<blink><font color=red>Tentukan tipe benefit</font></blink><br>';
	//$_REQUEST['typetenor'] = $_POST['typetenor'];	if (!$_REQUEST['typetenor'])  $error4 .='<blink><font color=red>Tentukan type tenor</font></blink><br>';
	$_REQUEST['maxage'] = $_POST['maxage'];
	if (!$_REQUEST['maxage'])  $error5 .='<blink><font color=red>Tentukan batasan usia maksimum</font></blink><br>';
	$_REQUEST['maxup'] = $_POST['maxup'];
	if (!$_REQUEST['maxup'])  $error6 .='<blink><font color=red>Tentukan batasan jumlah pertanggungan</font></blink><br>';
	$_REQUEST['nmproduk'] = $_POST['nmproduk'];
	if (!$_REQUEST['nmproduk'])  $error7 .='<blink><font color=red>Silahkan masukan nama produk</font></blink><br>';
	if (!$_REQUEST['gproduk'])  $error12 .='<blink><font color=red>Silahkan pilih grup produk</font></blink><br>';
	if (!$_REQUEST['typeproduk_'])  $error13 .='<blink><font color=red>Silahkan pilih type produk</font></blink><br>';
	if ($_REQUEST['typempp']=="Y" AND ($_REQUEST['mppbln_min']=="" OR $_REQUEST['mppbln_max']==""))  $error14 .='<blink><font color=red>Tentukan jumlah tahun masa pra pensiun</font></blink><br>';
	if ($error1 OR $error2 OR $error3 OR $error4 OR $error5 OR $error6 OR $error7 OR $error8 OR $error9 OR $error10 OR $error11 OR $error12 OR $error13 OR $error14)
	{	}
	else
	{
		$rcomp = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE name="'.$_REQUEST['name'].'"'));
		$polis =mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis ORDER BY id DESC'));
		if ($polis['idp']=="") {	$x = 1;	}
		else	{	$x = $polis['idp'] + 1;	}
		$numb = 100000; $numb1 = substr($numb,1);
		$RNoPolis = $DatePolis.''.$numb1.''.$x;
		if ($_REQUEST['benefit']=="L") {	$typeratenya = "Tetap";	}	else	{	$typeratenya = "Menurun";	}
		$met = $database->doQuery('INSERT INTO fu_ajk_polis SET idp="'.$x.'",
														nopol="'.$RNoPolis.'",
														id_cost="'.$rcomp['id'].'",
														ajksyariah="'.$_REQUEST['ajksyariah'].'",
														grupproduk="'.$_REQUEST['gproduk'].'",
														typeproduk="'.$_REQUEST['typeproduk_'].'",
														polis_type="'.$_REQUEST['typePolis'].'",
														nmproduk="'.strtoupper($_REQUEST['nmproduk']).'",
														noreferensi="'.$_REQUEST['er_noref'].'",
														nokontrak="'.$_REQUEST['er_nokont'].'",
														polis_start="'.$queryeffdate.'",
														polis_end="'.$_REQUEST['enddate'].'",
														day_kredit="'.$_REQUEST['dateminus'].'",
														singlerate="'.$_REQUEST['singlerate'].'",
														jtempo="'.$_REQUEST['jtempo'].'",
														adminfee="'.$_REQUEST['adminfee'].'",
														brokrage="'.$_REQUEST['brokrage'].'",
														discount="'.$_REQUEST['discount'].'",
														benefit="'.$_REQUEST['benefit'].'",
														ppn="'.$_REQUEST['er_ppn'].'",
														pph23="'.$_REQUEST['er_pph'].'",
														waypaid="'.$_REQUEST['waypaid'].'",
														age_min="'.$_REQUEST['minage'].'",
														age_max="'.$_REQUEST['maxage'].'",
														age_memo="'.$_REQUEST['age_memo'].'",
														up_max="'.$_REQUEST['maxup'].'",
														limitfinancial="'.$_REQUEST['limitfinancial'].'",
														min_premium="'.$_REQUEST['minpremium'].'",
														mpptype="'.$_REQUEST['typempp'].'",
														mppbln_min="'.$_REQUEST['mppbln_min'].'",
														mppbln_max="'.$_REQUEST['mppbln_max'].'",
														bank_1="'.$_REQUEST['bank1'].'",
														cabang_1="'.$_REQUEST['cabang1'].'",
														rek_1="'.$_REQUEST['norek1'].'",
														bank_2="'.$_REQUEST['bank2'].'",
														cabang_2="'.$_REQUEST['cabang2'].'",
														rek_2="'.$_REQUEST['norek2'].'",
														input_by="'.$_SESSION['nm_user'].'",
														input_date="'.$futgl.'",
														tipe_produk="'.$_REQUEST['typeProduk'].'",
														max_up_ajk="'.$_REQUEST['max_up_ajk'].'",
														max_up_jamkred="'.$_REQUEST['max_up_jamkred'].'",
														max_up_fire="'.$_REQUEST['max_up_fire'].'",
														max_up_mv="'.$_REQUEST['max_up_mv'].'"
														');
	echo '<blink><center>ID Produk <b>'.$RNoPolis.'</b> telah di buat oleh <b>'.$_SESSION['nm_user'].'</b></center></blink><meta http-equiv="refresh" content="1; url=ajk_mspolis.php">';
	}
}

echo '<form method="POST" action="" class="input-list style-1 smart-green">
		<h1>Setting Produk Baru</h1>
		<table border="0" width="100%" cellpadding="5" cellspacing="5">
		<tr><td colspan="2" class="title2">Product Type</td></tr>
		<tr>
			<td><label><input type="radio" name="typeProduk" onclick="javascript:ohYesOhNo();" value="ajk"'.pilih($_REQUEST["ajk"], "ajk").' id="ajk" checked><strong>AJK</strong></label></td>
			<td><label><input type="radio" name="typeProduk" onclick="javascript:ohYesOhNo();" value="general"'.pilih($_REQUEST["general"], "general").' id="general" '.($_REQUEST["typeProduk"]=="general" ? " checked":"").'><strong>General</strong></label></td>
		</tr>
		<tr><td colspan="2">
		<label><span><strong>AJK Syariah : </strong></span><input type="radio" name="ajksyariah" value="Ya"'.pilih($_REQUEST["ajksyariah"], "Ya").'>Ya
										  			<input type="radio" name="ajksyariah" value="Tidak"'.pilih($_REQUEST["ajksyariah"], "Tidak").' checked>Tidak
		</label>
		<label><span>Pemegang Polis <font color="red">*</font> '.$error1.'</span>
			   <select id="name" name="name">
			   <option value="">--- Pilih ---</option>';
			   $comp = $database->doQuery('SELECT fu_ajk_costumer.name FROM fu_ajk_costumer ORDER BY name DESC');
			   while ($ccomp = mysql_fetch_array($comp)) {
			   echo '<option value="'.$ccomp['name'].'"'._selected($_REQUEST['name'], $ccomp['name']).'>'.$ccomp['name'].'</option>';
			   }
echo '</select>
		</label>
		<label><span>Grup Produk <font color="red">*</font> '.$error12.'</span>
			   <select id="gproduk" name="gproduk">
			   <option value="">--- Pilih ---</option>';
		$metgproduk = $database->doQuery('SELECT * FROM fu_ajk_grupproduk ORDER BY nmproduk ASC');
		while ($metgproduk_ = mysql_fetch_array($metgproduk)) {
			echo '<option value="'.$metgproduk_['id'].'"'._selected($_REQUEST['gproduk'], $metgproduk_['id']).'>'.$metgproduk_['nmproduk'].'</option>';
		}
echo '</select>
		</label>
		<label><span>Type Produk <font color="red">*</font> '.$error13.'</span>
	   		   <select size="1" name="typeproduk_">
	   			<option value="">--- Pilih ---</option>
	   			<option value="SPK"'._selected($_REQUEST['typeproduk_'],"SPK").'>SPK</option>
	   			<option value="NON SPK"'._selected($_REQUEST['typeproduk_'],"NON SPK").'>NON SPK</option>
		</select>
	   	</label>
		<label><span>Nama Produk <font color="red">*</font> '.$error7.'</span><input type="text" name="nmproduk" value="'.$_REQUEST['nmproduk'].'" placeholder="Nama Produk"></label>
		<label><span>Nomor Konfirmasi</span><input type="text" name="er_noref" value="'.$_REQUEST['er_noref'].'" placeholder="Nomor Referensi"></label>
		<label><span>Nomor Kontrak</span><input type="text" name="er_nokont" value="'.$_REQUEST['er_nokont'].'" placeholder="Nomor Kontrak"></label>
		<div id="ifYes" '.($_REQUEST["typeProduk"]=="general" ? " style=\"display:block\"":" style=\"display:none\"").'>
		<div class="title2">General</div>
			<label><span>Max Up AJK <font color="red">*</font>'.$error8.'</span><input type="text" id="max_up_ajk" name="max_up_ajk" value="'.$_REQUEST['max_up_ajk'].'" placeholder="Max Up AJK"></label>
			<label><span>Max Up Jamkred <font color="red">*</font>'.$error9.'</span><input type="text" id="max_up_jamkred" name="max_up_jamkred" value="'.$_REQUEST['max_up_jamkred'].'" placeholder="Max Up Jamkred"></label>
			<label><span>Max Up Fire <font color="red">*</font>'.$error10.'</span><input type="text" id="max_up_fire" name="max_up_fire" value="'.$_REQUEST['max_up_fire'].'" placeholder="Max Up Fire"></label>
			<label><span>Max Up MV <font color="red">*</font>'.$error11.'</span><input type="text" id="max_up_mv" name="max_up_mv" value="'.$_REQUEST['max_up_mv'].'" placeholder="Max Up MV"></label>
		</div>
	   </td></tr>
	   <tr><td colspan="2" class="title2">Policy Rules</td></tr>
	   <tr><td width="50%">
	   <label><span> </span><input type="radio" name="typePolis" value="openpolis"'.pilih($_REQUEST["openpolis"], "openpolis").' id="type_0">Open Policy
							<input type="radio" name="typePolis" value="closepolis"'.pilih($_REQUEST["closepolis"], "closepolis").' id="type_1">Close Policy
		</label>
   		<div id="Individual_box">
   		<label><span>Effective Date <font color="red">*</font> '.$error2.'</span><br />
		<input type="text" name="effdate" id="effdate" class="tanggal" value="'.$_REQUEST['effdate'].'" size="10"/>
		</label>
		</div>
		<div id="Company_box">
			<label><span>Effective Date <font color="red">*</font> '.$error2.'</span><br />
	  		<table border="0" width="100%">
	  		<tr><td><input type="text" name="effdate1" id="effdate1" class="tanggal" value="'.$_REQUEST['effdate1'].'" size="10"/></td>
				<td width="1%"> s/d</td>
				<td><input type="text" name="enddate" id="enddate" class="tanggal" value="'.$_REQUEST['enddate'].'" size="10"/></td>
			</tr>
			</table>
			</label>
		</div>
		<label><span>Admin Fee</span><input type="text" name="adminfee" value="'.$_REQUEST['adminfee'].'" placeholder="Biaya Admin"></label>
		<label><span>Brokrage</span><input type="text" name="brokrage" value="'.$_REQUEST['brokrage'].'" placeholder="Brokrage (%)"></label>
		<label><span>Discount</span><input type="text" name="discount" value="'.$_REQUEST['discount'].'" placeholder="Diskon (%)" maxlength="3"></label>
		<label><span>Minimum Premi</span><input type="text" name="minpremium" value="'.$_REQUEST['minpremium'].'" placeholder="Minimum Premi" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"></label>
	   	<label><span>Masa Pra Pensiun (MPP)</span></label>
		<label>';
		if ($met['mpptype']=="Y") {
			echo '<input type="radio" name="typempp" value="Y"'.pilih($_REQUEST["typempp"], "Y").' checked="checked" />Ya &nbsp;
				  <input type="radio" name="typempp" value="T"'.pilih($_REQUEST["typempp"], "T").'/>Tidak';
		}else{
			echo '<input type="radio" name="typempp" value="Y"'.pilih($_REQUEST["typempp"], "Y").' />Ya &nbsp;
				  <input type="radio" name="typempp" value="T"'.pilih($_REQUEST["typempp"], "T").' checked="checked" />Tidak';
		}
		echo '</label>
			<div id="mppya">
		  	<label>
			<table border="0" width="100%">
	  		<tr><td><input type="text" name="mppbln_min" value="'.$met['mppbln_min'].'" placeholder="Masa Pra Pensiun (jumlah bulan) Minimum" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')" maxlength="2"><br /> '.$error14.'</td>
				<td><input type="text" name="mppbln_max" value="'.$met['mppbln_max'].'" placeholder="Masa Pra Pensiun (jumlah bulan) Maksimum" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')" maxlength="2"><br /></td>
			</tr>
			</table>
			</label>
	   </td>
	   <td valign="top">
		<label><span>Pengurangan Jumlah Hari (Akhir Kredit) : </span><input type="radio" name="dateminus" value="1"'.pilih($_REQUEST["dateminus"], "1").'>1 Hari
											 						 <input type="radio" name="dateminus" value="0"'.pilih($_REQUEST["dateminus"], "0").'>0 Hari
		</label><br />
		<label><span>Single Rate By Usia <a href="#" title="menentukan Rate Premi dengan usia atau tidak !"><img src="../image/Information-icon.png" width="12"></a></span>
					<input type="radio" name="singlerate" value="Y"'.pilih($_REQUEST["singlerate"], "Y").'>Ya
					<input type="radio" name="singlerate" value="T"'.pilih($_REQUEST["singlerate"], "T").'>Tidak
		</label><br />
	   	<label><span>Jatuh Tempo Penagihan Premi (jumlah hari)</span><input type="text" name="jtempo" value="'.$_REQUEST['jtempo'].'" placeholder="Jatuh Tempo (jumlah hari)" maxlength="2"></label>
	   	<label><span>PPN</span><input type="text" name="er_ppn" value="'.$_REQUEST['er_ppn'].'" placeholder="PPN (%)" maxlength="2"></label>
	   	<label><span>PPN 23</span><input type="text" name="er_pph" value="'.$_REQUEST['er_pph'].'" placeholder="PPH 23 (%)" maxlength="2"></label>
		<label><span>Benefit Type <font color="red">*</font> '.$error3.'</span>
				<select size="1" name="benefit">
	   			<option value="">--- Pilih ---</option>
	   			<option value="D"'._selected($_REQUEST['benefit'],"D").'>Decreasing</option>
	   			<option value="L"'._selected($_REQUEST['benefit'],"F").'>Level/Flat</option>
	   			</select>
		</label>
		<label><span>Cara Pembayaran</span>
	   		   <select size="1" name="waypaid">
	   			<option value="">--- Pilih ---</option>
	   			<option value="Sekaligus"'._selected($_REQUEST['waypaid'],"Sekaligus").'>Sekaligus</option>
	   			<option value="Triwulan"'._selected($_REQUEST['waypaid'],"Triwulan").'>Triwulan</option>
	   			<option value="Quartal"'._selected($_REQUEST['waypaid'],"Quartal").'>Quartal</option>
	   			<option value="Semester"'._selected($_REQUEST['waypaid'],"Semester").'>Semester</option>
	   			<option value="Tahunan"'._selected($_REQUEST['waypaid'],"Tahunan").'>Tahunan</option>
	   		   </select>
	   	</label>
	   </td></tr>
	   <tr><td colspan="2" class="title2">Agent</td></tr>
	   <tr><td>
	   <label><span>Nama Penutup (GC / Agen)</span><input type="text" name="agentpenutup" value="'.$_REQUEST['agentpenutup'].'" placeholder="Nama Penutup"></label>
	   <label><span>Nama Manager Group </span><input type="text" name="agentmanager" value="'.$_REQUEST['agentmanager'].'" placeholder="Nama Manager"></label>
	   </td>
	   <td>
	   <label><span>Posisi Nama Penutup</span><select size="1" name="posisiagent">
	   			<option value="">--- Pilih ---</option>
	   			<option value="Manager"'._selected($_REQUEST['posisiagent'],"Manager").'>Manager</option>
	   			<option value="Supervisor"'._selected($_REQUEST['posisiagent'],"Supervisor").'>Supervisor</option>
	   			<option value="Staff"'._selected($_REQUEST['posisiagent'],"Staff").'>Staff</option>
	   			<option value="Alt. Distribution"'._selected($_REQUEST['posisiagent'],"Alt. Distribution").'>Alt. Distribution</option>
	   			</select></label>
	   <label><span>Posisi Nama Manager</span><select size="1" name="posisimngr">
	   			<option value="">--- Pilih ---</option>
	   			<option value="Manager"'._selected($_REQUEST['posisimngr'],"Manager").'>Manager</option>
	   			<option value="Supervisor"'._selected($_REQUEST['posisimngr'],"Supervisor").'>Supervisor</option>
	   			<option value="Staff"'._selected($_REQUEST['posisimngr'],"Staff").'>Staff</option>
	   			<option value="Alt. Distribution"'._selected($_REQUEST['posisimngr'],"Alt. Distribution").'>Alt. Distribution</option>
	   			</select></label>
	   </td>
	   </tr>

	   <tr><td class="title2">Insurance Limit</td><td class="title2">Bank</td></tr>
	   <tr><td valign="top">
	   		<label><span>Age <font color="red">*</font> '.$error5.'</span><br />
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
			<tr><td><input type="text" name="minage" value="'.$_REQUEST['minage'].'" placeholder="Minimum Usia"></td>
				<td width="1%"> s/d</td>
				<td><input type="text" name="maxage" value="'.$_REQUEST['maxage'].'" placeholder="Maksimum Usia (x + n)"></td>
			</tr>
			</table>
			</label>
	   		<label><span>Batasan Memo Usia (thn)</span><input type="text" name="age_memo" value="'.$_REQUEST['age_memo'].'" placeholder="Batasan Memo Usia (thn)" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"></label>
			<label><span>Maximum of Sum Insured <font color="red">*</font> '.$error6.'</span><input type="text" name="maxup" value="'.$_REQUEST['maxup'].'" placeholder="Maksimum Uang Pertanggungan"></label>
			<label><span>Limit For Financial Statements</span><input type="text" name="limitfinancial" value="'.$_REQUEST['limitfinancial'].'" placeholder="Batasan Laporan Keuangan"></label>
	   	   </td>
	   	   <td valign="top">
	   	   	<table border="0" width="100%">
	   	   <tr><td><label><span>Bank DN</span><input type="text" name="bank1" value="'.$_REQUEST['bank1'].'" placeholder="Nama Bank Debit Note"></label>
	   	   		   <label><span>Cabang</span><input type="text" name="cabang1" value="'.$_REQUEST['cabang1'].'" placeholder="Cabang"></label>
	   	   		   <label><span>No. Rek</span><input type="text" name="norek1" value="'.$_REQUEST['norek1'].'" placeholder="Nomor Rekening"></label>
			   </td>
			   <td><label><span>Bank CN</span><input type="text" name="bank2" value="'.$_REQUEST['bank2'].'" placeholder="Nama Bank Credit Note"></label>
	   	   		   <label><span>Cabang</span><input type="text" name="cabang2" value="'.$_REQUEST['cabang2'].'" placeholder="Cabang"></label>
	   	   		   <label><span>No. Rek</span><input type="text" name="norek2" value="'.$_REQUEST['norek2'].'" placeholder="Nomor Rekening"></label>
			   </td>
			</tr>
		   </table>
	   	   </td>
	   	</tr>
		<tr><td colspan="2" align="center"><label><span>&nbsp;</span><input type="submit" name="ope" value="Simpan" class="button" /></label></td></tr>
	  </table></form>';
		;
		break;

	case "view":
echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
	  <tr><th width="95%" align="left">Data Produk - View</font></th>
     	  <th align="center"><a href="ajk_mspolis.php"><img src="image/Backward-64.png" border="0" width="25"></a></th>
     	  </tr>
   	  </table><br />';
$polis = mysql_fetch_array($database->doQuery('SELECT fu_ajk_polis.nopol,
											   		  fu_ajk_costumer.name AS namaclient,
											   		  fu_ajk_agent.name AS namaagent,
											   		  fu_ajk_polis.polis_type,
											   		  fu_ajk_polis.polis_start,
											   		  fu_ajk_polis.polis_end,
											   		  fu_ajk_polis.day_kredit,
											   		  fu_ajk_polis.adminfee,
											   		  fu_ajk_polis.brokrage,
											   		  fu_ajk_polis.discount,
											   		  fu_ajk_polis.benefit,
											   		  fu_ajk_polis.waypaid,
											   		  fu_ajk_polis.age_min,
											   		  fu_ajk_polis.age_max,
											   		  fu_ajk_polis.up_max,
											   		  fu_ajk_polis.limitfinancial,
											   		  fu_ajk_polis.bank_1,
											   		  fu_ajk_polis.cabang_1,
											   		  fu_ajk_polis.rek_1,
											   		  fu_ajk_polis.bank_2,
											   		  fu_ajk_polis.cabang_2,
											   		  fu_ajk_polis.rek_2,
											   		  fu_ajk_polis.status
											   		  FROM fu_ajk_polis
											   		  LEFT JOIN fu_ajk_costumer ON fu_ajk_polis.id_cost = fu_ajk_costumer.id
											   		  LEFT JOIN fu_ajk_agent ON fu_ajk_costumer.pic = fu_ajk_agent.id
											   		  WHERE fu_ajk_polis.id= "'.$_REQUEST['id'].'"'));
if ($polis['polis_type'] = "closepolis") {	$met_effpolis = _convertDate($polis['polis_start']).' s./d '._convertDate($polis['polis_end']);	}
else{	 $met_effpolis= $polis($met['polis_start']);	}
echo '<table border="0" width="100%" cellpadding="3" cellspacing="1" style="border: solid 1px #DEDEDE">
	  <tr><td width="15%">Company Name</td><td width="1%">:</td><td>'.$polis['namaclient'].'</td></tr>
	  <tr><td width="15%">PIC</td><td width="1%">:</td><td>'.$polis['namaagent'].'</td></tr>
	  <tr><td>Policy Number</td><td>:</td><td>'.$polis['nopol'].'</td></tr>
	  <tr><td>Effective Date</td><td>:</td><td>'.$met_effpolis.'</td></tr>
	  <tr><td>Age</td><td>:</td><td>'.$polis['age_min'].' - '.$polis['age_max'].'</td></tr>
	  <tr><td>Maximum Sum Insured</td><td>:</td><td>'.duit($polis['up_max']).'</td></tr>
	  <tr><td>Limit For Financial Statements</td><td>:</td><td>'.duit($polis['limitfinancial']).'</td></tr>
	  </table>';
//echo $client['name'].'<br />';
//echo $polis['nopol'].'<br />';

		;
		break;

	case "e_polis":
echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
	  <tr><th width="98%" align="left">Edit Nama Produk</font></th>
		  <th align="center"><a href="ajk_mspolis.php"><img border="0" src="image/Backward-64.png" width="15"></a></th>
	  </tr>
	  </table><br />';

if ($_REQUEST['ope']=="Simpan") {
	if ($_REQUEST['typeProduk']=="general")
	{
		$_REQUEST['max_up_ajk'] = $_POST['max_up_ajk'];
		if (!$_REQUEST['max_up_ajk'])  $error8 .='<blink><font color=red>Max UP AJK wajib diisi</font></blink><br>';
		$_REQUEST['max_up_jamkred'] = $_POST['max_up_jamkred'];
		if (!$_REQUEST['max_up_jamkred'])  $error9 .='<blink><font color=red>Max UP Jamkred wajib diisi</font></blink><br>';
		$_REQUEST['max_up_fire'] = $_POST['max_up_fire'];
		if (!$_REQUEST['max_up_fire'])  $error10 .='<blink><font color=red>Max UP Fire wajib diisi</font></blink><br>';
		$_REQUEST['max_up_mv'] = $_POST['max_up_mv'];
		if (!$_REQUEST['max_up_mv'])  $error11 .='<blink><font color=red>Max UP MV wajib diisi</font></blink><br>';
	}
	if ($_REQUEST['typePolis']=="openpolis") {
		$_REQUEST['effdate'] = $_POST['effdate'];	if (!$_REQUEST['effdate'])  $error2 .='<blink><font color=red>Tentukan tanggal efektif polis</font></blink><br>';
		$queryeffdate1 = $_REQUEST['effdate'];
		$queryeffdate2 = "";
	}else{
		$_REQUEST['effdate1'] = $_POST['effdate1'];	if (!$_REQUEST['effdate1'])  $error2 .='<blink><font color=red>Tentukan tanggal awal efektif polis</font></blink><br>';
		$_REQUEST['enddate'] = $_POST['enddate'];	if (!$_REQUEST['enddate'])  $error2 .='<blink><font color=red>Tentukan tanggal akhir efektif polis</font></blink><br>';
		$queryeffdate1 = $_REQUEST['effdate1'];
		$queryeffdate2 = $_REQUEST['enddate'];
	}
	$_REQUEST['benefit'] = $_POST['benefit'];
	$_REQUEST['claimrule'] = $_POST['claimrule'];
	$_REQUEST['minage'] = $_POST['minage'];
	$_REQUEST['maxage'] = $_POST['maxage'];
	$_REQUEST['maxup'] = $_POST['maxup'];
	if (!$_REQUEST['benefit'])  $error3 .='<blink><font color=red>Tentukan tipe benefit</font></blink><br>';
	//if (!$_REQUEST['typetenor'])  $error4 .='<blink><font color=red>Tentukan type tenor</font></blink><br>';
	if (!$_REQUEST['minage'])  $error5 .='<blink><font color=red>Tentukan batasan minimum usia</font></blink><br>';
	if (!$_REQUEST['maxage'])  $error5 .='<blink><font color=red>Tentukan batasan maksimum usia</font></blink><br>';
	if (!$_REQUEST['maxup'])  $error6 .='<blink><font color=red>Tentukan batasan jumlah pertanggungan</font></blink><br>';
	if (!$_REQUEST['nmproduk'])  $error7 .='<blink><font color=red>Nama produk tidak boleh kosong</font></blink><br>';
	if (!$_REQUEST['nopol'])  $error12 .='<blink><font color=red>Nomor polis tidak boleh kosong</font></blink><br>';
	if ($_REQUEST['typempp']=="Y" AND ($_REQUEST['mppbln_min']=="" OR $_REQUEST['mppbln_max']==""))  $error13 .='<blink><font color=red>Tentukan jumlah tahun masa pra pensiun</font></blink><br>';
	if ($error2 OR $error3 OR $error5 OR $error6 OR $error8 OR $error7 OR $error9 OR $error10 OR $error11 OR $error12 OR $error13)
	{	}
	else
	{
		if ($_FILES['deklarasifile']['tmp_name']) {
			$nama_file =  strtoupper($_REQUEST['nmproduk']).'_'.$_FILES['deklarasifile']['name'];
			$file_type = $_FILES['deklarasifile']['type']; //tipe file
			$source = $_FILES['deklarasifile']['tmp_name'];
			$direktori = "$metpath_ttd/$nama_file"; // direktori tempat menyimpan file
			move_uploaded_file($source,$direktori);
			$metFileDkl = 'deklarasifile = "'.$nama_file.'",';
		}else{	$metFileDkl = 'deklarasifile = "",';	}

		$met = $database->doQuery('UPDATE fu_ajk_polis SET 	polis_type="'.$_REQUEST['typePolis'].'",
															nopol="'.$_REQUEST['nopol'].'",
															rmf="'.$_REQUEST['er_rmf'].'",
															polis_start="'.$queryeffdate1.'",
															nmproduk="'.strtoupper($_REQUEST['nmproduk']).'",
															noreferensi="'.$_REQUEST['er_noref'].'",
															nokontrak="'.$_REQUEST['er_nokont'].'",
															polis_end="'.$queryeffdate2.'",
															day_kredit="'.$_REQUEST['batashari'].'",
															singlerate="'.$_REQUEST['singlerate'].'",
															jtempo="'.$_REQUEST['jtempo'].'",
															ppn="'.$_REQUEST['er_ppn'].'",
															pph23="'.$_REQUEST['er_pph'].'",
															adminfee="'.$_REQUEST['adminfee'].'",
															brokrage="'.$_REQUEST['brokrage'].'",
															discount="'.$_REQUEST['discount'].'",
															benefit="'.$_REQUEST['benefit'].'",
															waypaid="'.$_REQUEST['waypaid'].'",
															age_min="'.$_REQUEST['minage'].'",
															age_max="'.$_REQUEST['maxage'].'",
															age_memo="'.$_REQUEST['age_memo'].'",
															up_max="'.$_REQUEST['maxup'].'",
															limitfinancial="'.$_REQUEST['limitfinancial'].'",
															min_premium="'.$_REQUEST['minpremium'].'",
															mpptype="'.$_REQUEST['typempp'].'",
															mppbln_min="'.$_REQUEST['mppbln_min'].'",
															mppbln_max="'.$_REQUEST['mppbln_max'].'",
															'.$metFileDkl.'
															bank_1="'.$_REQUEST['bank1'].'",
															cabang_1="'.$_REQUEST['cabang1'].'",
															rek_1="'.$_REQUEST['norek1'].'",
															bank_2="'.$_REQUEST['bank2'].'",
															cabang_2="'.$_REQUEST['cabang2'].'",
															rek_2="'.$_REQUEST['norek2'].'",
												   			update_by="'.$_SESSION['nm_user'].'",
												   			update_date="'.$futgl.'",
												   			tipe_produk="'.$_REQUEST['typeProduk'].'",
												   			max_up_ajk="'.$_REQUEST['max_up_ajk'].'",
												   			max_up_jamkred="'.$_REQUEST['max_up_jamkred'].'",
												   			max_up_fire="'.$_REQUEST['max_up_fire'].'",
												   			max_up_mv="'.$_REQUEST['max_up_mv'].'"
												WHERE id="'.$_REQUEST['idp'].'"');
	echo '<blink><center>Nama produk <b>'.$met['nopol'].'</b> telah di edit oleh <b>'.$_SESSION['nm_user'].'</b></center></blink><meta http-equiv="refresh" content="1; url=ajk_mspolis.php">';
	}
}
$met = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$_REQUEST['idp'].'"'));
$comp = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$met['id_cost'].'" ORDER BY name'));
echo '<form method="POST" action="" class="input-list style-1 smart-green"  enctype="multipart/form-data">
		<h1>Edit Polis</h1>
		<table border="0" width="100%" cellpadding="5" cellspacing="5">
		<tr><td colspan="2" class="title2">Product Type</td></tr>
		<tr>
			<td><label><input type="radio" name="typeProduk" onclick="javascript:ohYesOhNo();" value="ajk"'.pilih($_REQUEST["ajk"], "ajk").' id="ajk" checked><strong>AJK</strong></label></td>
			<td><label><input type="radio" name="typeProduk" onclick="javascript:ohYesOhNo();" value="general"'.pilih($_REQUEST["general"], "general").' id="general" '.($met["tipe_produk"]=="general" ? " checked":"").'><strong>General</strong></label></td>
		</tr>
		<tr><td colspan="2"><label><span>Nama Perusahaan '.$comp['name'].'<br />Nomor Polis '.$met['nopol'].'</label></td></tr>
		<tr><td colspan="2"><label><span>Nomor Polis <font color="red">*</font> '.$error12.'</span><input type="text" name="nopol" value="'.$met['nopol'].'" placeholder="Nomor polis"></label></td></tr>
		<tr><td colspan="2"><label><span>Nama Produk <font color="red">*</font> '.$error7.'</span><input type="text" name="nmproduk" value="'.$met['nmproduk'].'" placeholder="Nama Produk"></label></td></tr>
		<tr><td colspan="2"><label><span>Nomor Konfirmasi</span><input type="text" name="er_noref" value="'.$met['noreferensi'].'" placeholder="Nomor Referensi"></label></td></tr>
		<tr><td colspan="2"><label><span>Nomor Kontrak</span><input type="text" name="er_nokont" value="'.$met['nokontrak'].'" placeholder="Nomor Kontrak"></label></td></tr>
		<tr><td colspan="2"><label><span>Risk Management Fund (RMF)</span><input type="text" name="er_rmf" value="'.$met['rmf'].'" placeholder="% Risk Management Fund (RMF)" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"></label></td></tr>
		<tr><td colspan="2">
		<div id="ifYes" '.($met["tipe_produk"]=="general" ? " style=\"display:block\"":" style=\"display:none\"").'>
		<div class="title2">General</div>
			<label><span>Max Up AJK <font color="red">*</font>'.$error8.'</span><input type="text" id="max_up_ajk" name="max_up_ajk" value="'.$met['max_up_ajk'].'" placeholder="Max Up AJK" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"></label>
			<label><span>Max Up Jamkred <font color="red">*</font>'.$error9.'</span><input type="text" id="max_up_jamkred" name="max_up_jamkred" value="'.$met['max_up_jamkred'].'" placeholder="Max Up Jamkred" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"></label>
			<label><span>Max Up Fire <font color="red">*</font>'.$error10.'</span><input type="text" id="max_up_fire" name="max_up_fire" value="'.$met['max_up_fire'].'" placeholder="Max Up Fire" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"></label>
			<label><span>Max Up MV <font color="red">*</font>'.$error11.'</span><input type="text" id="max_up_mv" name="max_up_mv" value="'.$met['max_up_mv'].'" placeholder="Max Up MV" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"></label>
		</div>
		</td></tr>
	   <tr><td colspan="2" class="title2">Policy Rules</td></tr>
	   <tr><td>';
	   		if ($met['polis_type']=="openpolis") {
	   		$cektglpolis = '<input type="radio" name="typePolis" value="openpolis" id="type_0" checked="checked" />Open Policy &nbsp; <input type="radio" name="typePolis" value="closepolis" id="type_1" />Close Policy</label>';
	   		}else{
	   		$cektglpolis = '<input type="radio" name="typePolis" value="openpolis" id="type_0" />Open Policy &nbsp; <input type="radio" name="typePolis" value="closepolis" id="type_1" checked="checked" />Close Policy</label>';
	   		}
			echo $cektglpolis.'
      		<div id="Individual_box">
      		<label><span>Effective Date <font color="red">*</font> '.$error2.'</span><br />
	  		<input type="text" name="effdate" id="effdate" class="tanggal" value="'.$met['polis_start'].'" size="10"/>
			</label>
		</div>
		<div id="Company_box">
			<label><span>Effective Date <font color="red">*</font> '.$error2.'</span><br />
	  		<table border="0" width="100%">
	  		<tr><td><input type="text" name="effdate1" id="effdate1" class="tanggal" value="'.$met['polis_start'].'" size="10"/></td>
				<td width="1%"> s/d</td>
				<td><input type="text" name="enddate" id="enddate" class="tanggal" value="'.$met['polis_end'].'" size="10"/></td>
			</tr>
			</table>
			</label>
		</div>
		<label><span>Admin Fee</span><input type="text" name="adminfee" value="'.$met['adminfee'].'" placeholder="Biaya Admin" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"></label>
		<label><span>Brokrage</span><input type="text" name="brokrage" value="'.$met['brokrage'].'" placeholder="Brokrage" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"></label>
		<label><span>Discount</span><input type="text" name="discount" value="'.$met['discount'].'" placeholder="Diskon (%)" maxlength="3" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"></label>
	   	<label><span>Minimum Premi</span><input type="text" name="minpremium" value="'.$met['min_premium'].'" placeholder="Minimum Premi" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"></label>
	   	<label><span>Masa Pra Pensiun (MPP)</span></label>
		<label>';
		if ($met['mpptype']=="Y") {
			echo '<input type="radio" name="typempp" value="Y"'.pilih($_REQUEST["typempp"], "Y").' checked="checked" />Ya &nbsp;
				  <input type="radio" name="typempp" value="T"'.pilih($_REQUEST["typempp"], "T").'/>Tidak';
		}else{
			echo '<input type="radio" name="typempp" value="Y"'.pilih($_REQUEST["typempp"], "Y").' />Ya &nbsp;
				  <input type="radio" name="typempp" value="T"'.pilih($_REQUEST["typempp"], "T").' checked="checked" />Tidak';
		}
		echo '</label>
			<div id="mppya">
		  	<label>
			<table border="0" width="100%">
	  		<tr><td><input type="text" name="mppbln_min" value="'.$met['mppbln_min'].'" placeholder="Masa Pra Pensiun (jumlah bulan) Minimum" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')" maxlength="2"><br /> '.$error13.'</td>
				<td><input type="text" name="mppbln_max" value="'.$met['mppbln_max'].'" placeholder="Masa Pra Pensiun (jumlah bulan) Maksimum" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')" maxlength="2"><br /></td>
			</tr>
			</table>
			</label>
   	   </td>
	   <td valign="top">
		<label><span></span>
	   	<table border="0" width="100%" cellpadding="0" cellspacing="0">
	   		<tr><td>Pengurangan Jumlah Hari (Akhir Kredit)</td><td>';
		if ($met['day_kredit']=="1") {
			echo '<input type="radio" name="batashari" value="1" checked/>1 Hari
				  <input type="radio" name="batashari" value="0" />0 Hari<br />';
		}else{
			echo '<input type="radio" name="batashari" value="1"/>1 Hari
				  <input type="radio" name="batashari" value="0" checked />0 Hari<br />';
		}
	   	echo '</td></tr>
			<tr><td>Single Rate By Usia <a href="#" title="menentukan Rate Premi dengan usia atau tidak !"><img src="../image/Information-icon.png" width="12"></a></td><td>';
		if ($met['singlerate']=="Y") {
			echo '<input type="radio" name="singlerate" value="Y" checked/>Y
				  <input type="radio" name="singlerate" value="T" />T<br />';
		}else{
			echo '<input type="radio" name="singlerate" value="Y"/>Y
				  <input type="radio" name="singlerate" value="T" checked />T<br />';
		}
		echo '</td></tr>
		</table></label>
	   	<label><span>Jatuh Tempo Penagihan Premi (jumlah hari)</span><input type="text" name="jtempo" value="'.$met['jtempo'].'" placeholder="Jatuh Tempo (jumlah hari)" maxlength="2" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"></label>
	   	<label><span>PPN</span><input type="text" name="er_ppn" value="'.$met['ppn'].'" placeholder="PPN (%)" maxlength="2" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"></label>
	   	<label><span>PPN 23</span><input type="text" name="er_pph" value="'.$met['pph'].'" placeholder="PPH 23 (%)" maxlength="2" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"></label>
		<label><span>Benefit Type <font color="red">*</font> '.$error3.'</span>
				<select size="1" name="benefit">
	   			<option value="">---Tipe Benefit---</option>
	   			<option value="D"'._selected($met['benefit'],"D").'>Decreasing</option>
	   			<option value="F"'._selected($met['benefit'],"F").'>Level/Flat</option>
	   			</select>
		</label>
		<label><span>Cara Pembayaran</span>
	   		   <select size="1" name="waypaid">
	   			<option value="">--- Pilih ---</option>
	   			<option value="Sekaligus"'._selected($met['waypaid'],"Sekaligus").'>Sekaligus</option>
	   			<option value="Triwulan"'._selected($met['waypaid'],"Triwulan").'>Triwulan</option>
	   			<option value="Quartal"'._selected($met['waypaid'],"Quartal").'>Quartal</option>
	   			<option value="Semester"'._selected($met['waypaid'],"Semester").'>Semester</option>
	   			<option value="Tahunan"'._selected($met['waypaid'],"Tahunan").'>Tahunan</option>
	   		   </select>
	   	</label>
	   </td>
	   </tr>
	   <tr><td class="title2">ReInsurance Limit</td><td class="title2">Bank</td></tr>
	   <tr><td valign="top">
	   		<label><span>Age ( x + n )<font color="red">*</font> '.$error5.'</span><br />
	   		<table border="0" width="100%" cellpadding="0" cellspacing="0">
			<tr><td><input type="text" name="minage" value="'.$met['age_min'].'" placeholder="Minimum Usia" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"></td>
				<td width="1%"> s/d</td>
				<td><input type="text" name="maxage" value="'.$met['age_max'].'" placeholder="Maksimum Usia (x + n)" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"></td>
			</tr>
			</table>
	   		<label><span>Batasan Memo Usia (thn)</span><input type="text" name="age_memo" value="'.$met['age_memo'].'" placeholder="Batasan Memo Usia (thn)" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"></label>
	   		<label><span>Maximum of Sum Insured <font color="red">*</font> '.$error6.'</span><input type="text" name="maxup" value="'.$met['up_max'].'" placeholder="Maksimum Uang Pertanggungan" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"></label>
			<label><span>Limit For Financial Statements</span><input type="text" name="limitfinancial" value="'.$met['limitfinancial'].'" placeholder="Batasan Laporan Keuangan" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"></label>
	   	   </td>
	   	   <td valign="top">
	   	   <table border="0" width="100%">
	   	   <tr><td><label><span>Bank DN</span><input type="text" name="bank1" value="'.$met['bank_1'].'" placeholder="Nama Bank Debit Note"></label>
	   	   		   <label><span>Cabang</span><input type="text" name="cabang1" value="'.$met['cabang_1'].'" placeholder="Cabang"></label>
	   	   		   <label><span>No. Rek</span><input type="text" name="norek1" value="'.$met['rek_1'].'" placeholder="Nomor Rekening"></label>
			   </td>
			   <td><label><span>Bank CN</span><input type="text" name="bank2" value="'.$met['bank_2'].'" placeholder="Nama Bank Credit Note"></label>
	   	   		   <label><span>Cabang</span><input type="text" name="cabang2" value="'.$met['cabang_2'].'" placeholder="Cabang"></label>
	   	   		   <label><span>No. Rek</span><input type="text" name="norek2" value="'.$met['rek_2'].'" placeholder="Nomor Rekening"></label>
			   </td>
			</tr>
		   </table>
	   	   </td>
	   	</tr>
	   	<!--<tr><td class="title2" colspan="2">Account Pembayaran Debit Note Bank</td></tr>
	   	<tr><td colspan="2">
	   		<label><span>Nomor Rekening</span><input type="text" name="bank1" value="'.$met['bank_1'].'" placeholder="Nama Bank Debit Note"></label>
	   	   	<label><span>Nama Pemilik Rekening</span><input type="text" name="cabang1" value="'.$met['cabang_1'].'" placeholder="Cabang"></label>
	   	   	<label><span>Nama Bank</span><input type="text" name="norek1" value="'.$met['rek_1'].'" placeholder="Nomor Rekening"></label>
	   	   	<label><span>Alamat Bank</span><input type="text" name="norek1" value="'.$met['rek_1'].'" placeholder="Nomor Rekening"></label>
		</td></tr>-->
	  <tr><td colspan="2" class="title2">File Deklarasi Produk</td></tr>
	  <tr><td colspan="2"><label><span>Excel File (.xls)<br /><input name="deklarasifile" type="file" size="50" accept="application/vnd.ms-excel" ></label></td></tr>
	  <tr><td colspan="2" align="center"><label><span>&nbsp;</span><input type="submit" name="ope" value="Simpan" class="button" /></label></td></tr>
	  </table></form>';
	;
	break;

	default:
echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
	  <tr><th width="95%" align="left">Data Produk</font></th>
     	  <th align="center"><a href="ajk_mspolis.php?op=newpol"><img src="image/new.png" border="0" width="25"></a></th>
     	  </tr>
   	  </table><br />';
echo '<table border="0" width="100%" cellpadding="3" cellspacing="1" style="border: solid 1px #DEDEDE">
	<form method="post" action="">
	<tr><td width="10%">Nama Produk:</td><td width="10%"><input type="text" name="nmproduk" value="'.$_REQUEST['nmproduk'].'"></td>
		<td><input type="submit" name="button" value="Cari" class="button"></td></tr>
	</form>
	</table>';
if ($_REQUEST['nmproduk'])		{	$satu = 'AND nmproduk LIKE "%' . $_REQUEST['nmproduk'] . '%"';		}
echo '<table border="0" width="100%" cellpadding="4" cellspacing="1" bgcolor="#bde0e6">
	<tr>
	<th width="3%">No</th>
	<th>Costumer</th>
	<th width="15%">Polis</th>
	<th width="15%">Produk</th>
	<th width="1%">Share</th>
	<th width="10%">Max Insured</th>
	<th width="15%">Eff Date</th>
	<th width="5%">Admin Fee</th>
	<th width="3%">Disc%</th>
	<th width="5%">Type Benefit</th>
	<th width="3%">Max Age</th>
	<th width="1%">Option</th>
	<th width="1%">Deklarasi</th>
	</tr>';
$metpolis = $database->doQuery('SELECT * FROM fu_ajk_polis WHERE id !="" '.$satu.' AND del IS NULL ORDER BY input_date DESC');
while ($met = mysql_fetch_array($metpolis)) {
$cekcost = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id= "'.$met['id_cost'].'"'));
if ($met['polis_type'] = "closepolis") {	$met_effpolis = _convertDate($met['polis_start']).' s./d '._convertDate($met['polis_end']);	}
else{	 $met_effpolis= _convertDate($met['polis_start']);	}

if ($met['deklarasifile']=="") {
	$metDKFile = '';
}else{
	$metDKFile = '<a href="'.$metpath_ttd.''.$met['deklarasifile'].'"><img src="image/pdftoexl.png" width="20"></a>';
}
if (($no % 2) == 1)	$objlass = 'tbl-odd'; else				$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	<td align="center">'.++$no.'</td>
	<td>'.$cekcost['name'].'</td>
	<td><a href="ajk_mspolis.php?op=view&id='.$met['id'].'">'.$met['nopol'].'</a></td>
	<td>'.$met['nmproduk'].'</td>
	<td>'.$met['brokrage'].'%</td>
	<td align="right">'.duit($met['up_max']).' &nbsp;</td>
	<td align="center">'.$met_effpolis.'</td>
	<td align="right">'.duit($met['adminfee']).'</td>
	<td align="center">'.$met['discount'].'</td>
	<td align="center">'.$met['benefit'].'</td>
	<td align="center">'.$met['age_max'].'</td>
	<td align="center"><a href="ajk_mspolis.php?op=e_polis&idp='.$met['id'].'"><img src="image/edit3.png"></a></td>
	<td align="center">'.$metDKFile.'</td>
	</tr>';
}
echo '</table>';
;
} // switch
echo '<script>
$(document).ready(function(){
  $("input[name$=\'typePolis\']").click(function(){
  var value = $(this).val();
  if(value==\'openpolis\') {
    $("#Individual_box").show();
     $("#Company_box").hide();
  }
  else if(value==\'closepolis\') {
   $("#Company_box").show();
    $("#Individual_box").hide();
   }
  });
  $("#Individual_box").show();
  $("#Company_box").hide();
});

</script>';

echo '<script>
$(document).ready(function(){
  $("input[name$=\'typePolis\']").click(function(){
  var value = $(this).val();
  if(value==\'openpolis\') {
    $("#Individual_box").show();
     $("#Company_box").hide();
  }
  else if(value==\'closepolis\') {
   $("#Company_box").show();
    $("#Individual_box").hide();
   }
  });
  $("#Individual_box").show();
  $("#Company_box").hide();
});

</script>';

echo '<script>
$(document).ready(function(){
  $("input[name$=\'typempp\']").click(function(){
  var value = $(this).val();
  if(value==\'Y\') {
    $("#mppya").show();
     $("#mpptidak").hide();
  }
  else if(value==\'T\') {
   $("#mpptidak").hide();
    $("#mppya").hide();
   }
  });
  $("#mppya").hide();
  $("#mpptidak").hide();
});

</script>';
?>