<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// 2013
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("../includes/functions.php");
connect();
if (session_is_registered('nm_user')) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));	}
$futglupdate = date("Y-m-d g:i:a");
$futgl = date("Y-m-d");
$futglidcn = date("Y");

switch ($_REQUEST['fu']) {
	case "a":
		;
		break;
/*
case "app":
$mamet = $database->doQuery('UPDATE fu_ajk_cn SET confirm_claim="Approve(paid)" WHERE id = "'.$_REQUEST['id'].'"');
header("location:ajk_cn.php");
	;
	break;
*/
case "unapp":
$met = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cn WHERE id="'.$_REQUEST['id'].'"'));
$metpeserta = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_klaim="'.$met['id_cn'].'"'));
$metclient = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$met['id_cost'].'"'));
$metpolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$met['id_nopol'].'"'));
echo '<table border="0" cellpadding="5" cellspacing="1" width="100%">
<tr><th width="100%" align="left">Modul Credit Note ['.$met['id_cn'].']</font></th><th width="5%"><a href="ajk_cn.php">[back]</a></th></tr>
</table>';
if ($_REQUEST['oop']=="Paid") {
$_REQUEST['duedate'] = $_POST['duedate'];	if (!$_REQUEST['duedate'])  $error .='<blink><font color=red>Tanggal pembayaran tidak boleh kosong</font></blink>';
	if ($error)
	{	echo '<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border: solid 1px red">
			  <tr><td><table width="100%" class="bgcolor1">
				  <tr><td align="left"><img src="image/warning.gif" border="0"></td>
					  <td align="center"><font class="option"><blink>'.$error.'</blink></td>
					  <td align="right"><img src="image/warning.gif" border="0"></td>
				  </tr>
				  </table></td></tr>
			  </table>';
	}
	else
	{
	$met = $database->doQuery('UPDATE fu_ajk_cn SET tgl_byr_claim="'.$_REQUEST['duedate'].'", confirm_claim="Approve(paid)", keterangan="'.$_REQUEST['ket'].'", update_by="'.$q['nm_lengkap'].'", update_time="'.$futglupdate.'" WHERE id = "'.$_REQUEST['id'].'"');
	echo '<center>Data pembayaran cliam telah di input.</center><meta http-equiv="refresh" content="2;URL=ajk_cn.php">';
	}
}
echo '<form method="post" action="ajk_cn.php?fu=unapp&id='.$_REQUEST['id'].'">
	  <input type="hidden" name="id" value="'.$_REQUEST['id'].'">
	  <table border="0" cellpadding="5" cellspacing="1" width="50%" align="center">
	  <tr><td width="20%">Nama CLient</td><td>: '.$metclient['name'].'</td></tr>
	  <tr><td>Nomor Polis</td><td>: '.$metpolis['nopol'].'</td></tr>
	  <tr><td>Nama</td><td>: '.$metpeserta['nama'].'</td></tr>
	  <tr><td>Type Claim</td><td>: '.$met['type_claim'].'</td></tr>
	  <tr><td>Tanggal Claim</td><td>: '.$met['tgl_claim'].'</td></tr>
	  <tr><td>Total Claim</td><td>: Rp. '.duit($met['total_claim']).'</td></tr>
	  <tr><td>Tanggal Bayar Claim</td><td>:';	echo initCalendar();	echo calendarBox('duedate', 'triger', $_REQUEST['duedate']);
echo '</td></tr>
	  <tr><td valign="top">Keterangan</td><td><textarea rows="3" name="ket" value="'.$_REQUEST['ket'].'" cols="50">'.$_REQUEST['ket'].'</textarea></td></tr>
	  <tr><td colspan="2" align="center"><input type="submit" name="oop" value="Paid"></td></tr>
	  </table></form>';
	;
	break;

case "proses":
	$mamet = $database->doQuery('UPDATE fu_ajk_cn SET confirm_claim="Approve(unpaid)" WHERE id = "'.$_REQUEST['id'].'"');
	header("location:ajk_cn.php");
	;
	break;

case "reject":
	$mamet = $database->doQuery('UPDATE fu_ajk_cn SET confirm_claim="Rejected" WHERE id = "'.$_REQUEST['id'].'"');
	header("location:ajk_cn.php");
	;
	break;

case "createcn":
//'echo('SELECT * FROM fu_ajk_cn WHERE id = "'.$_REQUEST['id'].'"');
//$mamet = $database->doQuery('UPDATE fu_ajk_cn SET confirm_claim="Approve(unpaid)" WHERE id = "'.$_REQUEST['id'].'"');
//header("location:ajk_cn.php");
/*
$mametcn = $database->doQuery('INSERT INTO fu_ajk_cn SET id_claim="'.$_REQUEST['id'].'",
														 	id_cost="'.$_REQUEST['id'].'",
														  	tgl_klaim="'.$tglklaim2.'",
														 	type_klaim="'.$ymet['status_peserta'].'",
														  	jumlah="'.$jumlahnya.'",
														 	input_by="'.$_SESSION['nm_user'].'",
														  	input_date="'.$futgl.'"');
*/
if ($_REQUEST['er']=="created") {
echo '<table border="0" cellpadding="5" cellspacing="1" width="100%"><tr><th width="100%" align="left">Modul Create Credit Note</font></th></tr></table>';
if ($_REQUEST['createdncn']!="CreateDNCN") {
$totalprice = 0;
foreach($idpNew as $idpNew)
$totalprice += $cd;
	echo "$totalprice";
	}
echo '<form method="post" action="">
	  <input type="hidden" name="id_cost" value="'.$_REQUEST['id_cost'].'">
	  <input type="hidden" name="id_polis" value="'.$_REQUEST['id_polis'].'">
	  <table border="0" cellpadding="5" cellspacing="1" width="100%" bgcolor="#bde0e6">
	  <tr><th width="3%" rowspan="2">No</th>
	  	  <th width="1%" rowspan="2">No. Reg</th>
	  	  <th width="1%" rowspan="2">SPAJ</th>
	  	  <th width="10%" rowspan="2">No. Dn</th>
	  	  <th rowspan="2">Nama</th>
	  	  <th width="1%" rowspan="2">DOB</th>
	  	  <th width="1%" rowspan="2">Gender</th>
	  	  <th width="1%" colspan="4">Status Kredit</th>
	  	  <th width="1%" rowspan="2">Bunga<br />%</th>
	  	  <th width="1%" rowspan="2">Premi</th>
	  	  <th width="1%" colspan="3">Biaya</th>
	  	  <th width="1%" rowspan="2">Total Premi</th>
	  	  <th width="1%" rowspan="2">Type Claim</th>
	  	  <th width="1%" rowspan="2">Medical</th>
	  	  <th width="5%" rowspan="2">Cabang</th>
	  	  <th width="5%" rowspan="2">Area</th>
	  	  <th width="5%" rowspan="2">Regional</th>
	  </tr>
	  <tr><th>Kredit Awal</th><th>Tenor</th><th>Kredit Akhir</th><th>Jumlah</th>
	  	  <th>Adm</th><th>Refund</th><th>Ext Premi</th>
	  </tr>';

$metclient = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_cost="'.$_REQUEST['id_cost'].'" AND id_polis="'.$_REQUEST['id_polis'].'" AND id_dn="" AND status_peserta!="" AND status_aktif="aktif" AND status_medik="NM" ORDER BY input_time DESC');
while ($cn = mysql_fetch_array($metclient)) {
echo '<tr><td align="center"><input type="hidden" name="idpNew[]" value="'.$cn['id'].'" checked>'.++$no.'</td>
		  <td>'.$cn['id_peserta'].'</td>
		  <td>'.$cn['spaj'].'</td>
		  <td>'.$cn['id_dn'].'</td>
		  <td>'.$cn['nama'].'</td>
		  <td>'.$cn['tgl_lahir'].'</td>
		  <td align="center">'.$cn['gender'].'</td>
		  <td>'.$cn['kredit_tgl'].'</td>
		  <td align="center">'.$cn['kredit_tenor'].'</td>
		  <td>'.$cn['kredit_akhir'].'</td>
		  <td align="right">'.duit($cn['kredit_jumlah']).'</td>
		  <td>'.$cn['bunga'].'</td>
		  <td align="right">'.duit($cn['premi']).'</td>
		  <td align="right">'.duit($cn['biaya_adm']).'</td>
		  <td align="right">'.duit($cn['biaya_refund']).'</td>
		  <td align="right">'.duit($cn['ext_premi']).'</td>
		  <td align="right">'.duit($cn['totalpremi']).'</td>
		  <td align="center">'.$cn['status_medik'].'</td>
		  <td align="center">'.$cn['status_peserta'].'</td>
		  <td>'.$cn['cabang'].'</td>
		  <td>'.$cn['area'].'</td>
		  <td>'.$cn['regional'].'</td>
	  </tr>';
$metdata = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE nama="'.$cn['nama'].'" AND tgl_lahir="'.$cn['tgl_lahir'].'" AND status_peserta=""'));
if ($metdata['nama']==$cn['nama'] AND $metdata['tgl_lahir']==$cn['tgl_lahir'] AND $metdata['status_peserta']=="") {
echo '<tr bgcolor="white"><td align="center">'.$no.'</td>
		  <td>'.$metdata['id_peserta'].'</td>
		  <td>'.$metdata['spaj'].'</td>
		  <td>'.$metdata['id_dn'].'</td>
		  <td>'.$metdata['nama'].' &nbsp;(data lama)</td>
		  <td>'.$metdata['tgl_lahir'].'</td>
		  <td align="center">'.$metdata['gender'].'</td>
		  <td>'.$metdata['kredit_tgl'].'</td>
		  <td align="center">'.$metdata['kredit_tenor'].'</td>
		  <td>'.$metdata['kredit_akhir'].'</td>
		  <td align="right">'.duit($metdata['kredit_jumlah']).'</td>
		  <td>'.$metdata['bunga'].'</td>
		  <td align="right">'.duit($metdata['premi']).'</td>
		  <td align="right">'.duit($metdata['biaya_adm']).'</td>
		  <td align="right">'.duit($metdata['biaya_refund']).'</td>
		  <td align="right">'.duit($metdata['ext_premi']).'</td>
		  <td align="right">'.duit($metdata['totalpremi']).'</td>
		  <td align="center">'.$metdata['status_medik'].'</td>
		  <td align="center">'.$metdata['status_peserta'].'</td>
		  <td>'.$metdata['cabang'].'</td>
		  <td>'.$metdata['area'].'</td>
		  <td>'.$metdata['regional'].'</td>
	  </tr>';
}else{
echo '<tr bgcolor="white"><td align="center">'.$no.'</td><td colspan="21" align="center"><font color="red">Data tidak ada</font></td></tr>';
}

}
echo '<tr><td colspan="21" align="center"><input type="hidden" name="createdncn" value="CreateDNCN"><input type="submit" name="createdncn" value="Create DN CN" class="button"></td></tr></table></form>';
}else{
$cat=$_GET['cat']; // Use this line or below line if register_global is off
if(strlen($cat) > 0 and !is_numeric($cat)){ // to check if $cat is numeric data or not.
	echo "Data Error";
	exit;
}
echo '<table border="0" cellpadding="5" cellspacing="1" width="100%"><tr><th width="100%" align="left">Modul Create Credit Note</font></th></tr></table>
	<table border="0" width="50%" cellpadding="5" cellspacing="1" align="center">
	<form method="post" action="ajk_cn.php?fu=createcn">
	<tr><td width="15%" align="right">Company Name</td>
	  <td width="30%">: <select id="cat" name="cat" onchange="reload2(this.form)">
	  	<option value="">---Select Company---</option>';
$quer2=$database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name ASC');
while($noticia2 = mysql_fetch_array($quer2)) {
if($noticia2['id']==$cat){echo '<option selected value="'.$noticia2['id'].'">'.$noticia2['name'].'</option><BR>';}
else{echo  '<option value="'.$noticia2['id'].'">'.$noticia2['name'].'</option>';}
}
echo '</select></td></tr>
				<tr><td width="10%" align="right">Policy Number</td>
					<td width="20%">: ';
if(isset($cat) and strlen($cat) > 0){
	$quer=$database->doQuery('SELECT * FROM fu_ajk_polis where id_cost="'.$cat.'" ORDER BY id ASC');
}else{$quer=$database->doQuery("SELECT * FROM fu_ajk_polis ORDER BY id ASC"); }
		echo '<select name="subcat"><option value="">---Select Policy---</option>';
while($noticia = mysql_fetch_array($quer)) {

	echo  '<option value='.$noticia['id'].'>'.$noticia['nopol'].'</option>';
}
echo '</select> &nbsp; <input type="submit" name="r" value="Searching" class="button"></td></tr>
		</form></table>';

if ($_REQUEST['r']=="Searching") {
echo '<form method="post" action="">
	  <table border="0" cellpadding="1" cellspacing="1">
	<tr><td align="center"><a href="ajk_cn.php?fu=createcn&er=created&id_cost='.$_REQUEST['cat'].'&id_polis='.$_REQUEST['subcat'].'" onClick="if(confirm(\'Nomor CN Peserta akan dibuat berdasarkan per Cabang ?\')){return true;}{return false;}"><img src="image/createDN.png" border="0" width="25"><br />Create CN</a></td></tr>
    </table>
	<input type="hidden" name="id_cost" value="'.$_REQUEST['cat'].'">
	<input type="hidden" name="id_polis" value="'.$_REQUEST['subcat'].'">
	<table border="0" width="100%" cellpadding="3" cellspacing="1" bgcolor="#bde0e6">
	<tr><th width="1%" rowspan="2">No</th>
		<th width="5%" rowspan="2">SPAJ</th>
		<th width="5%" rowspan="2">No. Reg</th>
		<th rowspan="2">Nama Debitur<br /><font size="1"><i>(sesuai KTP/SIM)</i></font></th>
		<th width="1%" rowspan="2">P/W</th>
		<th colspan="2">Kartu Identitas</th>
		<th rowspan="2">Tgl Lahir</th>
		<th colspan="4">Status Kredit</th>
		<th width="1%" rowspan="2">Bunga<br>%</th>
		<th width="1%" rowspan="2">Premi</th>
		<th colspan="3">Biaya</th>
		<th width="1%" rowspan="2">Total Premi</th>
		<th width="1%" rowspan="2">Tinggi/ Berat Badan</th>
		<th rowspan="2">Claim</th>
		<th rowspan="2">Medical</th>
		<th rowspan="2">Cabang</th>
		<th rowspan="2">Area</th>
		<th rowspan="2">Regional</th>
	</tr>
	<tr><th width="5%">Type</th>
		<th width="5%">No</th>
		<th>Kredit Awal</th>
		<th>Jumlah</th>
		<th>Tenor</th>
		<th>Kredit Akhir</th>
		<th>Adm</th>
		<th>Refund</th>
		<th>Ext. Premi</th>
	</tr>';
$metCN = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_dn="" AND status_peserta!="" AND status_aktif="aktif" AND status_medik="NM" AND id_polis="'.$_REQUEST['subcat'].'"');
while ($mCN = mysql_fetch_array($metCN)) {
echo '<tr class="'.rowClass(++$i).'">
		  <td align="center">'.++$no.'</td>
	  	  <td align="center">'.$mCN['spaj'].'</td>
	  	  <td align="center">'.$mCN['id_peserta'].'</td>
	  	  <td>'.$mCN['nama'].'</td>
	  	  <td align="center">'.$mCN['gender'].'</td>
	  	  <td align="center">'.$mCN['kartu_type'].'</td>
	  	  <td>'.$mCN['kartu_no'].'</td>
	  	  <td>'.$mCN['tgl_lahir'].'</td>
	  	  <td align="center">'.$mCN['kredit_tgl'].'</td>
	  	  <td align="right">'.duit($mCN['kredit_jumlah']).'</td>
	  	  <td align="center">'.$mCN['kredit_tenor'].'</td>
	  	  <td align="center">'.$mCN['kredit_akhir'].'</td>
	  	  <td>'.$mCN['bunga'].'</td>
	  	  <td>'.$mCN['premi'].'</td>
	  	  <td align="right">'.duit($mCN['biaya_adm']).'</td>
	  	  <td align="right">'.duit($mCN['biaya_refund']).'</td>
	  	  <td align="right">'.duit($mCN['ext_premi']).'</td>
	  	  <td align="right">'.duit($mCN['totalpremi']).'</td>
	  	  <td align="center">'.$mCN['badant'].'/'.$mCN['badanb'].'</td>
	  	  <td align="center">'.$mCN['status_peserta'].'</td>
	  	  <td align="center">'.$mCN['status_medik'].'</td>
	  	  <td>'.$mCN['cabang'].'</td>
	  	  <td>'.$mCN['area'].'</td>
	  	  <td>'.$mCN['regional'].'</td>
	  </tr>';
}
echo '</table>';
}else{
	$datacn = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_dn="" AND status_peserta!="" AND status_aktif="aktif" AND status_medik="NM" ORDER BY input_time DESC');
	$met = mysql_num_rows($datacn);
if ($met > 0) { echo '<center><font size="4">Pilih nama kostumer dan nomor polis untuk melihat data peserta yang harus di buat CN</font></center>';	}
else	{	echo '<center>Data Kosong.</center>';	}
	}
}
;
		break;

case "cnbatal":
echo '<table border="0" cellpadding="5" cellspacing="1" width="100%">
<tr><th width="100%" align="left">Modul Credit Note</font></th></tr>
</table>';

$cat=$_GET['cat']; // Use this line or below line if register_global is off
if(strlen($cat) > 0 and !is_numeric($cat)){ // to check if $cat is numeric data or not.
echo "Data Error";	exit;
}

echo '<fieldset>
		<legend align="center">S e a r c h</legend>
		<table border="0" width="50%" cellpadding="1" cellspacing="0" align="center">
		<form method="post" action="ajk_cn.php?fu=cnbatal">
		<tr><td width="15%" align="right">Company Name</td>
		  <td width="30%">: <select id="cat" name="cat" onchange="reload(this.form)">
		  	<option value="">---Select Company---</option>';
	$quer2=$database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name ASC');
while($noticia2 = mysql_fetch_array($quer2)) {
if($noticia2['id']==$cat){echo '<option selected value="'.$noticia2['id'].'">'.$noticia2['name'].'</option><BR>';}
else{echo  '<option value="'.$noticia2['id'].'">'.$noticia2['name'].'</option>';}
}
echo '</select></td></tr>
			<tr><td width="10%" align="right">Regional</td>
		  <td>: <select id="eRreg" name="eRreg">
		  	<option value="">---Select Regional---</option>';
	$Rreg=$database->doQuery('SELECT * FROM fu_ajk_regional ORDER BY name ASC');
while($eRreg = mysql_fetch_array($Rreg)) {
	echo  '<option value="'.$eRreg['name'].'">'.$eRreg['name'].'</option>';
}
echo '</select></td></tr>
			<tr><td align="right">Branch</td>
				<td>: ';
	$Rcab=$database->doQuery("SELECT * FROM fu_ajk_cabang ORDER BY name ASC");
	echo '<select name="eRcab"><option value="">---Select Branch---</option>';
while($eRcab = mysql_fetch_array($Rcab)) {
	echo  '<option value='.$eRcab['name'].'>'.$eRcab['name'].'</option>';
}
echo '<tr><td width="10%" align="right">CN Number</td>
		<td width="20%">: <input type="text" name="id_cn" value='.$_REQUEST['id_cn'].'>';
echo '</td></tr>';
echo '<tr><td colspan="2" align="center"><input type="submit" name="r" value="Searching" class="button"></td></tr>
	</form>
	</table></fieldset>';
echo '<table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#E2E2E2">
		<tr><th width="1%" rowspan="2">No</th>
			<th width="3%" rowspan="2">SPAJ</th>
			<th width="5%" rowspan="2">No. DN</th>
			<th width="5%" rowspan="2">No. CN</th>
			<th rowspan="2">Nama Debitur<br /><font size="1"><i>(sesuai KTP/SIM)</i></font></th>
			<th width="1%" rowspan="2">P/W</th>
			<th rowspan="2">Tgl Lahir</th>
			<th rowspan="2">Usia</th>
			<th width="20%" colspan="6">Status Kredit</th>
			<th width="1%" rowspan="2">Premi</th>
			<th colspan="3" width="10%" >Biaya</th>
			<th width="1%" rowspan="2">Total Premi</th>
			<th colspan="7">Klaim</th>
			<th rowspan="2">Cabang</th>
			<th rowspan="2">Regional</th>
		</tr>
		<tr><th>Kredit Awal</th>
			<th>Jumlah</th>
			<th>Tenor</th>
			<th>Kredit Akhir</th>
			<th>Movement Date</th>
			<th>Movement Tenor</th>
			<th>Adm</th>
			<th>Refund</th>
			<th>Ext. Premi</th>
			<th>Claim</th>
			<th>MA-j</th>
			<th>MA-s</th>
			<th>Jumlah</th>
			<th>Tanggal</th>
			<th>Status</th>
			<th>Tgl Bayar</th>
		</tr>';

if ($_REQUEST['subcat'])		{	$satu = 'AND id LIKE "%' . $_REQUEST['subcat'] . '%"';		}	// pencarian berdasarkan combobox 13/06/13
if ($_REQUEST['id_cn'])			{	$satu = 'AND id_cn LIKE "%' . $_REQUEST['id_cn'] . '%"';		}
	//if ($_REQUEST['eRreg'])		{	$dua = 'AND id_cabang LIKE "%' . $_REQUEST['eRreg'] . '%"';		}
if ($_REQUEST['eRcab'])			{	$dua = 'AND id_cabang LIKE "%' . $_REQUEST['eRcab'] . '%"';		}
if ($_REQUEST['eRreg'])			{	$tiga = 'AND id_regional LIKE "%' . $_REQUEST['eRreg'] . '%"';		}
if ($_REQUEST['cat'])			{	$empat = 'AND id_cost LIKE "%' . $_REQUEST['cat'] . '%"';		}

if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}
else {	$m = 0;		}
	$data = $database->doQuery('SELECT * FROM fu_ajk_cn WHERE id!="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' AND type_claim="Batal" AND del is null ORDER BY id DESC, input_by DESC LIMIT ' . $m . ' , 25');
	$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_cn WHERE id != "" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' AND type_claim="Batal" AND del is null '));
	$totalRows = $totalRows[0];
	$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while ($fudata = mysql_fetch_array($data)) {
/*
	$peserta = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_klaim = "'.$fudata['id_cn'].'" AND status_aktif="batal"'));		//PESERTA LAMA
	$metcnklaim = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_klaim WHERE id_cn = "'.$fudata['id_cn'].'"'));

	if ($fudata['confirm_claim']=="Approve(paid)") {	$statklaim = '<a href="ajk_report_fu.php?fu=ajkpdfcn&id='.$fudata['id'].'"><font color="green">'. $fudata['confirm_claim'].'</font> </a>';	}
	elseif ($fudata['confirm_claim']=="Approve(unpaid)") {	$statklaim = '<a href="ajk_cn.php?fu=unapp&id='.$fudata['id'].'"><font color="red">'.$fudata['confirm_claim'].'</font> </a>';	}
	elseif ($fudata['confirm_claim']=="Processing") {	$statklaim = '<a href="ajk_cn.php?fu=proses&id='.$fudata['id'].'"><font color="red">'.$fudata['confirm_claim'].'</font> </a>';	}
	else{	$statklaim = '<a href="ajk_cn.php?fu=reject&id='.$fudata['id'].'"><font color="red">'.$fudata['confirm_claim'].'</font> </a>';	}

	$metregio = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE name="'.$fudata['id_cabang'].'"'));
	$namareg = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE id="'.$metregio['id_reg'].'"'));
	$metupdatereg = $database->doQuery('UPDATE fu_ajk_cn SET id_regional="'.$namareg['name'].'" WHERE id="'.$fudata['id'].'"');

if ($fudata['type_claim']=="Death")
{

	$movementdate = $fudata['tgl_claim'];
	$awal = explode ("/", $peserta['kredit_tgl']);		$hari = $awal[0];	$bulan = $awal[1];		$tahun = $awal[2];
	$akhir = explode ("/", $fudata['tgl_claim']);	$hari2 = $akhir[0];	$bulan2 = $akhir[1];	$tahun2 = $akhir[2];
	$jhari=(mktime (0,0,0,$bulan2,$hari2,$tahun2) - mktime (0,0,0,$bulan,$hari,$tahun))/86400;
	$sisahr=floor($jhari);
	$sisabulan =ceil($sisahr / 30.4375);
	$masisa = $peserta['kredit_tenor'] - $sisabulan;

	$kredit2  = explode("/", $fudata['tgl_claim']);	$kredithr2 = $kredit2[0];	$kreditbl2 = $kredit2[1];	$kreditth2 = $kredit2[2];
}
else
{
	$movementdate = $peserta['kredit_tgl'];

	$awal = explode ("/", $peserta['kredit_tgl']);		$hari = $awal[0];	$bulan = $awal[1];		$tahun = $awal[2];
	$akhir = explode ("/", $fudata['tgl_claim']);	$hari2 = $akhir[0];	$bulan2 = $akhir[1];	$tahun2 = $akhir[2];
	$jhari=(mktime (0,0,0,$bulan2,$hari2,$tahun2) - mktime (0,0,0,$bulan,$hari,$tahun))/86400;
	$sisahr=floor($jhari);
	$sisabulan =ceil($sisahr / 30.4375);

}
	$masisa = $peserta['kredit_tenor'] - $sisabulan;

	if ($fudata['type_claim']=="Death") {
		$dnclaim ='<a href="ajk_report_fu.php?fu=ajkpdfinvdn&invmove=movemant&id='.$peserta['id'].'" target="_blank">'.substr($peserta['id_dn'], 6).'</a>';
		$jumlahnya = $fudata['total_claim'];
	}else{
		$dnclaim ='<a href="ajk_report_fu.php?fu=ajkpdfinvdn&invmove=movemant&id='.$peserta['id'].'" target="_blank">'.substr($peserta['id_dn'], 6).'</a>';	//total premi cn
	}

if ($fudata['total_claim'] < 0) { $totalclaimnya = 0;	}else{ $totalclaimnya =	$fudata['total_claim'];}
if ($fudata['id_cabang']=="") { $metcabangnya = $fudata['cabang_lama'];	}else{ $metcabangnya = $fudata['id_cabang'];}
*/
echo '<tr class="'.rowClass(++$i).'">
		  <td align="center" valign="top">'.(++$no + ($pageNow-1) * 25).'</td>
		  <td>'.$peserta['spaj'].'</td>
		  <!--<td>'.$dnclaim.'</td>
		  <td><a href="ajk_report_fu.php?fu=ajkpdfcn&id='.$fudata['id'].'" target="_blank">'.substr($fudata['id_cn'], 6).'</a></td>-->
		  <td>'.substr($peserta['id_dn'], 6).'</td>
		  <td>'.substr($fudata['id_cn'], 6).'</td>
		  <td>'.$peserta['nama'].'</td>
		  <td align="center">'.$peserta['gender'].'</td>
		  <td align="center">'.$peserta['tgl_lahir'].'</td>
		  <td align="center">'.$peserta['usia'].'</td>
		  <td align="center">'.$peserta['kredit_tgl'].'</td>
		  <td align="right">'.duit($peserta['kredit_jumlah']).'</td>
		  <td align="center">'.$peserta['kredit_tenor'].'</td>
		  <td align="center">'.$peserta['kredit_akhir'].'</td>
		  <td align="center">'.$movementdate.'</td>
		  <td align="center">'.$cekpeserta['kredit_tenor'].'</td>
		  <td align="right">'.duit($peserta['premi']).'</td>
		  <td align="right">'.duit($peserta['biaya_adm']).'</td>
		  <td align="right">'.duit($peserta['biaya_refund']).'</td>
		  <td align="right">'.duit($peserta['ext_premi']).'</td>
		  <td align="right">'.duit($peserta['totalpremi']).'</td>
		  <td align="right">'.$fudata['type_claim'].'</td>
		  <td align="center">'.$sisabulan.'</td>
		  <td align="center">'.$masisa.'</td>
		  <td align="right"><b>'.duit($totalclaimnya).'</b></td>
		  <td align="center"><b>'.$fudata['tgl_claim'].'</b></td>
		  <td align="center"><b>'.$statklaim.'</b></td>
		  <td align="center">'._convertDate($fudata['tgl_byr_claim']).'</td>
		  <td align="center">'.$fudata['id_cabang'].'</td>
		  <td align="center">'.$fudata['id_regional'].'</td>
		  </tr>';
}
	echo '<tr><td colspan="22">';
	echo createPageNavigations($file = 'ajk_cn.php?subcat='.$_REQUEST['subcat'].'&eRcab='.$_REQUEST['eRcab'].'&eRreg='.$_REQUEST['eRreg'].'&cat='.$_REQUEST['cat'].'&typeclaim='.$_REQUEST['typeclaim'].'&', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
	echo '<b>Total Data CN Batal: <u>' . $totalRows . '</u></b></td></tr>';
	echo '</table>';
	;
	break;


	default:
echo '<table border="0" cellpadding="5" cellspacing="1" width="100%"><tr><th width="100%" align="left">Modul Credit Note</font></th></tr></table>';

$cat=$_GET['cat']; // Use this line or below line if register_global is off
if(strlen($cat) > 0 and !is_numeric($cat)){ // to check if $cat is numeric data or not.
	echo "Data Error";	exit;
}

echo '<fieldset>
	<legend align="center">S e a r c h</legend>
	<table border="0" width="50%" cellpadding="1" cellspacing="0" align="center">
	<form method="post" action="ajk_cn.php">
	<tr><td width="15%" align="right">Company Name</td>
	  <td width="30%">: <select id="cat" name="cat" onchange="reload(this.form)">
	  	<option value="">---Select Company---</option>';
$quer2=$database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name ASC');
while($noticia2 = mysql_fetch_array($quer2)) {
if($noticia2['id']==$cat){echo '<option selected value="'.$noticia2['id'].'">'.$noticia2['name'].'</option><BR>';}
else{echo  '<option value="'.$noticia2['id'].'">'.$noticia2['name'].'</option>';}
}
echo '</select></td></tr>
		<tr><td width="10%" align="right">Regional</td>
	  <td>: <select id="eRreg" name="eRreg">
	  	<option value="">---Select Regional---</option>';
$Rreg=$database->doQuery('SELECT * FROM fu_ajk_regional ORDER BY name ASC');
while($eRreg = mysql_fetch_array($Rreg)) {
echo  '<option value="'.$eRreg['name'].'">'.$eRreg['name'].'</option>';
}
echo '</select></td></tr>
		<tr><td align="right">Branch</td>
			<td>: ';
$Rcab=$database->doQuery("SELECT * FROM fu_ajk_cabang ORDER BY name ASC");
echo '<select name="eRcab"><option value="">---Select Branch---</option>';
while($eRcab = mysql_fetch_array($Rcab)) {
	echo  '<option value='.$eRcab['name'].'>'.$eRcab['name'].'</option>';
}
echo '<tr><td width="10%" align="right">CN Number</td>
	<td width="20%">: <input type="text" name="id_cn" value='.$_REQUEST['id_cn'].'>';
/*
if(isset($cat) and strlen($cat) > 0){
$quer=$database->doQuery('SELECT * FROM fu_ajk_cn where id_cost="'.$cat.'" AND del is NULL ORDER BY id_cn DESC');
}else{$quer=$database->doQuery("SELECT * FROM fu_ajk_cn ORDER BY id_cn DESC"); }
echo '<select name="subcat"><option value="">---Select CN---</option>';
while($noticia = mysql_fetch_array($quer)) {

echo  '<option value='.$noticia['id'].'>'.$noticia['id_cn'].'</option>';
}
echo '</select>';
*/
echo '</td></tr>';
echo '<tr><td width="10%" align="right">DN Number</td>
	<td width="20%">: <input type="text" name="id_dn" value='.$_REQUEST['id_dn'].'>';
echo '</td></tr>';

echo '<tr><td width="10%" align="right">Type Claim</td>
<td width="20%">: <select name="typeclaim"><option value="">---Select Claim---</option>
	<option name="typeclaim" value="Refund">Refund</option>
	<option name="typeclaim" value="Death">Meninggal</option>
	<option name="typeclaim" value="Batal">Batal</option>
	</select>
</td></tr>
<tr><td colspan="2" align="center"><input type="submit" name="r" value="Searching" class="button"></td></tr>
</form>
</table></fieldset>';
echo '<table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#E2E2E2">
	<tr><th width="1%" rowspan="2">No</th>
		<th width="5%" rowspan="2">Debit Note</th>
		<th width="5%" rowspan="2">Credit Note</th>
		<th rowspan="2">Nama</th>
		<th rowspan="2">Tgl Lahir</th>
		<th rowspan="2">Usia</th>
		<th colspan="4">Status Kredit</th>
		<th width="1%" rowspan="2">Premi</th>
		<th colspan="7" width="1%">Klaim</th>
		<th rowspan="2" width="10%">Cabang</th>
	</tr>
	<tr><th>Kredit Awal</th>
		<th>Plafond</th>
		<th>Tenor</th>
		<th>Kredit Akhir</th>
		<th>Type</th>
		<th>MA-j</th>
		<th>MA-s</th>
		<th>Jumlah</th>
		<th>Tanggal</th>
		<th>Status</th>
		<th>Tgl Bayar</th>
	</tr>';

if ($_REQUEST['subcat'])		{	$satu = 'AND id LIKE "%' . $_REQUEST['subcat'] . '%"';		}	// pencarian berdasarkan combobox 13/06/13
//if ($_REQUEST['eRreg'])		{	$dua = 'AND id_cabang LIKE "%' . $_REQUEST['eRreg'] . '%"';		}
if ($_REQUEST['eRcab'])			{	$dua = 'AND id_cabang LIKE "%' . $_REQUEST['eRcab'] . '%"';		}
if ($_REQUEST['eRreg'])			{	$tiga = 'AND id_regional LIKE "%' . $_REQUEST['eRreg'] . '%"';		}
if ($_REQUEST['cat'])			{	$empat = 'AND id_cost LIKE "%' . $_REQUEST['cat'] . '%"';		}
if ($_REQUEST['typeclaim'])		{	$lima = 'AND type_claim LIKE "%' . $_REQUEST['typeclaim'] . '%"';		}
if ($_REQUEST['id_cn'])			{	$enam = 'AND id_cn LIKE "%' . $_REQUEST['id_cn'] . '%"';		}
if ($_REQUEST['id_dn'])			{	$tujuh = 'AND id_dn LIKE "%' . $_REQUEST['id_dn'] . '%"';		}

if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}
$data = $database->doQuery('SELECT * FROM fu_ajk_cn WHERE id!="" AND id_cn!="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' '.$tujuh.' AND del is null ORDER BY id DESC, input_by DESC LIMIT ' . $m . ' , 25');
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_cn WHERE id != "" AND id_cn!="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' '.$tujuh.' AND del is null '));
$totalRows = $totalRows[0];
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while ($fudata = mysql_fetch_array($data)) {
/*
$met_dn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn WHERE id="'.$fudata['id_dn'].'"'));
$met_peserta = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_cost="'.$fudata['id_cost'].'" AND id_polis="'.$fudata['id_nopol'].'" AND id_klaim="'.$fudata['id'].'"'));

//MA-J//
	$awal = explode ("-", $met_peserta['kredit_tgl']);		$hari = $awal[2];	$bulan = $awal[1];		$tahun = $awal[0];
	$akhir = explode ("-", $fudata['tgl_claim']);			$hari2 = $akhir[2];	$bulan2 = $akhir[1];	$tahun2 = $akhir[0];
	$jhari=(mktime (0,0,0,$bulan2,$hari2,$tahun2) - mktime (0,0,0,$bulan,$hari,$tahun))/86400;
	$sisahr=floor($jhari);
	$sisabulan =ceil($sisahr / 30.4375);
//MA-J//

//MA-S//
$masisa = $met_peserta['kredit_tenor'] - $sisabulan;
//MA-S//

if ($fudata['type_claim']=="Death") {
$met_cn = '<a href="../aajk_report.php?er=_erKlaim&idC='.$fudata['id'].'">'.substr($fudata['id_cn'], 3).'</a>';
}else{
$met_cn = '<a href="../aajk_report.php?er=_erBatal&idC='.$fudata['id'].'">'.substr($fudata['id_cn'], 3).'</a>';
}
*/

$met_peserta = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_klaim="'.$fudata['id'].'"'));
$met_pesertaDN = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn WHERE id="'.$fudata['id_dn'].'"'));

//MASA ASURANSI BERJALAN
$met_Date = datediff($fudata['tgl_claim'], $met_peserta['kredit_tgl']);
//echo $fudata['tgl_claim'].' - '.$met_peserta['kredit_tgl'].' - '.$met_Date.'<br />';
$met_Date_ = explode(",", $met_Date);
if ($met_Date_[0] < 0) {	$thnbln = '';	}	else	{	$thnbln = $met_Date_[0] * 12;	}
$maj_ = $met_Date_[1] + $thnbln;
//MASA ASURANSI BERJALAN

//MASA ASURANSI SISA
$mas_ = $met_peserta['kredit_tenor'] - $maj_;
//MASA ASURANSI SISA

if ($fudata['type_claim']=="Death") {
	$met_cn = '<a href="../aajk_report.php?er=_erKlaim&idC='.$fudata['id'].'" target="_blank">'.substr($fudata['id_cn'], 3).'</a>';
}elseif ($fudata['type_claim']=="Refund") {
	$met_cn = '<a href="../aajk_report.php?er=_eRefund&idC='.$fudata['id'].'" target="_blank">'.substr($fudata['id_cn'], 3).'</a>';
}else{
	$met_cn = '<a href="../aajk_report.php?er=_eBatal&idC='.$fudata['id'].'" target="_blank">'.substr($fudata['id_cn'], 3).'</a>';
}
if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	  <td align="center" valign="top">'.(++$no + ($pageNow-1) * 25).'</td>
	  <td><a href="">'.substr($met_pesertaDN['dn_kode'], 3).'</a></td>
	  <td>'.$met_cn.'</td>
	  <td>'.$met_peserta['nama'].'</td>
	  <td align="center">'._convertDate($met_peserta['tgl_lahir']).'</td>
	  <td align="center">'.$met_peserta['usia'].'</td>
	  <td align="center">'._convertDate($met_peserta['kredit_tgl']).'</td>
	  <td align="right">'.duit($met_peserta['kredit_jumlah']).'</td>
	  <td align="center">'.$met_peserta['kredit_tenor'].'</td>
	  <td align="center">'._convertDate($met_peserta['kredit_akhir']).'</td>
	  <td align="right">'.duit($met_peserta['totalpremi']).'</td>
	  <td align="center">'.$fudata['type_claim'].'</td>
	  <td align="center">'.$maj_.'</td>
	  <td align="center">'.$mas_.'</td>
	  <td align="right">'.duit($fudata['total_claim']).'</td>
	  <td align="center">'._convertDate($fudata['tgl_claim']).'</td>
	  <td align="center">'.$fudata['confirm_claim'].'</b></td>
	  <td align="center">'._convertDate($fudata['tgl_byr_claim']).'</td>
	  <td align="center">'.$fudata['id_cabang'].'</td>
	  </tr>';
}
echo '<tr><td colspan="22">';
echo createPageNavigations($file = 'ajk_cn.php?subcat='.$_REQUEST['subcat'].'&eRcab='.$_REQUEST['eRcab'].'&eRreg='.$_REQUEST['eRreg'].'&cat='.$_REQUEST['cat'].'&typeclaim='.$_REQUEST['typeclaim'].'&', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
echo '<b>Total Data CN: <u>' . $totalRows . '</u></b></td></tr>';
echo '</table>';
		;
} // switch
function ceiling($number, $significance = 1)
{	return ( is_numeric($number) && is_numeric($significance) ) ? (ceil($number/$significance)*$significance) : false;	}
?>
<SCRIPT language=JavaScript>
function reload(form)
{
	var val=form.cat.options[form.cat.options.selectedIndex].value;
	self.location='ajk_cn.php?cat=' + val;
}
</script>
<SCRIPT language=JavaScript>
function reload2(form)
{
	var val=form.cat.options[form.cat.options.selectedIndex].value;
	self.location='ajk_cn.php?fu=createcn&cat=' + val;
}
</script>

<SCRIPT language=JavaScript>
function reload3(form)
{
	var val3=form.eRreg.options[form.eRreg.options.selectedIndex].value;
	self.location='ajk_cn.php?eRreg=' + val3;
}
</script>