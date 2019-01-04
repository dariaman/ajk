<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ----------------------------------------------------------------------------------
include_once("ui.php");
include_once("../includes/functions.php");
connect();
if (session_is_registered('nm_user')) {
    $q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));
}
$futgl = date("Y-m-d G:i:s");
$futgliddn = date("Y");
$futgldn = date("Y-m-d");
switch ($_REQUEST['fu']) {
    case "newdie":
echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
		<tr><th colspan="8" width="100%" align="left" colspan="2">Modul Klaim</font></th><th width="5%" colspan="2"><a href="ajk_klaim.php?fu=meninggal"><img src="image/Backward-64.png" width="20"></a></th></tr>
		</table><br />';
echo '<fieldset>
	<legend>Searching</legend>
	<table border="0" width="40%" cellpadding="3" cellspacing="1" align="center">
  <form method="post" action="ajk_klaim.php?fu=newdie">
  <tr><td width="15%" align="right">Nomor Polis</td>
	  <td width="30%">: <select id="cat" name="cat">```
	  	<option value="">---Pilih No. Polis---</option>';
$quer2=$database->doQuery('SELECT * FROM fu_ajk_polis ORDER BY nopol ASC');
while ($noticia2 = mysql_fetch_array($quer2)) {
    if ($noticia2['id']==$cat) {
        echo '<option selected value="'.$noticia2['id'].'">'.$noticia2['nopol'].'</option><BR>';
    } else {
        echo  '<option value="'.$noticia2['id'].'">'.$noticia2['nopol'].'</option>';
    }
}
echo '</select></td><td width="5%" align="right">Nama</td><td>: <input type="text" name="rnama" value="'.$_REQUEST['rnama'].'"></td></tr>
		<tr><td width="10%" align="right">Nomor DN</td><td width="20%">: <input type="text" name="nodn" value="'.$_REQUEST['nodn'].'"></td>
	<td width="5%" align="right">DOB</td><td>: ';print initCalendar();	print calendarBox('rdob', 'triger', $_REQUEST['rdob']);
echo '</td></tr>
	<tr><td colspan="4" align="center"><input type="submit" name="r" value="Searching" class="button"></td></tr>
	</form>
	</table></fieldset>';
echo '<br />';
echo '<table border="0" width="100%" cellpadding="5" cellspacing="1" bgcolor="#bde0e6">
	  <tr><th rowspan="2" width="3%">No</td>
	  	  <th rowspan="2" width="8%">Policy Number</td>
	  	  <th rowspan="2" width="7%">ID Peserta</td>
	  	  <th rowspan="2" width="12%">ID DN</td>
	  	  <th rowspan="2" >Name</td>
	  	  <th rowspan="2" width="3%">P/W</td>
	  	  <th rowspan="2" width="7%">DOB</td>
	  	  <th rowspan="2" width="3%">Usia</td>
	  	  <th rowspan="2" width="9%">Sum Insured</th>
	  	  <th colspan="3">Periode</th>
	  	  <th rowspan="2" width="8%">Total Premi</td>
	  	  <th rowspan="2" width="2%">Opt</td>
	  </tr>
	  <tr><th width="7%">Begin</th><th width="4%">Tenor</th><th width="7%">End</th></tr>';
if ($_REQUEST['x']) {
    $m = ($_REQUEST['x']-1) * 25;
} else {
    $m = 0;
}
//if ($_REQUEST['r']=="Searching") {
if ($_REQUEST['cat']) {
    $satu = 'AND id_polis LIKE "%' . $_REQUEST['cat'] . '%"';
}
if ($_REQUEST['nodn']) {
    $dua = 'AND id_dn LIKE "%' . $_REQUEST['nodn'] . '%"';
}
if ($_REQUEST['rnama']) {
    $tiga = 'AND nama LIKE "%' . $_REQUEST['rnama'] . '%"';
}
if ($_REQUEST['rdob']) {
    $empat = 'AND tgl_lahir LIKE "%' . _convertDateInd($_REQUEST['rdob']) . '%"';
}

//$fupes = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id!="" AND id_klaim ="" AND status_bayar=1 AND status_peserta="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' ORDER BY input_time DESC LIMIT ' . $m . ' , 25');
//$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_peserta WHERE id != "" AND id_klaim ="" AND status_bayar=1 AND status_peserta="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' ORDER BY id_peserta DESC '));
//$totalRows = $totalRows[0];
//}
$fupes = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id!="" AND id_klaim ="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' AND id_dn !="" AND del is null ORDER BY input_time DESC LIMIT ' . $m . ' , 25');
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_peserta WHERE id != "" AND id_klaim ="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.'AND id_dn !=""  AND del is null ORDER BY id_peserta DESC '));
$totalRows = $totalRows[0];

$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while ($met = mysql_fetch_array($fupes)) {
    $idmet = 1000000000 + $met['id'];
    $idmet2 = substr($idmet, 1);			//ID PESERTA

    $pol = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$met['id_polis'].'"'));
    if (($no % 2) == 1) {
        $objlass = 'tbl-odd';
    } else {
        $objlass = 'tbl-even';
    }
    echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	  <td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
	  <td align="center">'.$pol['nopol'].'</td>
	  <td align="center">'.$idmet2.'</td>
	  <td align="center">'.$met['id_dn'].'</td>
	  <td>'.$met['nama'].'</td>
	  <td align="center">'.$met['gender'].'</td>
	  <td align="center">'.$met['tgl_lahir'].'</td>
	  <td align="center">'.$met['usia'].'</td>
	  <td align="right">'.duit($met['kredit_jumlah']).'</td>
	  <td align="center">'.$met['kredit_tgl'].'</td>
	  <td align="center">'.$met['kredit_tenor'].'</td>
	  <td align="center">'.$met['kredit_akhir'].'</td>
	  <td align="right">'.duit($met['totalpremi']).'</td>
	  <td align="center"><a href="ajk_klaim.php?fu=prosdie&id='.$met['id'].'">Klaim</a></td>
	  </tr>';
}
echo '<tr><td colspan="22">';
echo createPageNavigations($file = 'ajk_klaim.php?fu=newdie&cat='.$_REQUEST['cat'].'&nodn='.$_REQUEST['nodn'].'&rnama='.$_REQUEST['rnama'].'&rdob='.$_REQUEST['rdob'].'', $total = $totalRows, $psDeh = 10, $anchor = '', $perPage = 25);
echo '<b>Total Data Peserta: <u>' . $totalRows . '</u></b></td></tr>';
echo '</table>';
    ;
    break;

    case "prosdie":
echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
		<tr><th colspan="8" width="100%" align="left" colspan="2">Modul Klaim - New Claim</font></th><th width="5%" colspan="2"><a href="ajk_klaim.php?fu=newdie"><img src="image/Backward-64.png" width="20"></a></th></tr>
		</table><br />';
$peserta = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id="'.$_REQUEST['id'].'"'));
$polis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$peserta['id_polis'].'"'));

if ($_REQUEST['oop']=="Save") {
    $fupeserta = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id="'.$_REQUEST['id'].'"'));
    // PERHITUNGAN BULAN MASA ASURANSI SAMPAI MASA ASURANSI BERJALAN
    $now2 = explode("/", $fupeserta['kredit_tgl']);
    $now3 = $now2[2].'-'.$now2[1].'-'.$now2[0];
    $now = new T10DateCalc($now3);
    $periodbulan = $now->compareDate($_REQUEST['tglklaim']) / 30.4375;
    $maj = ceil($periodbulan);
    // PERHITUNGAN BULAN MASA ASURANSI SAMPAI MASA ASURANSI BERJALAN
    $met = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_rateklaimdie WHERE masa_asuransi >= "'.$_REQUEST['kredit_tenor'].'" AND bulan_ke="'.$maj.'" AND id_cost="'.$peserta['id_cost'].'"'));
    $jum = $met['rate'] / 1000 * $peserta['kredit_jumlah'];
    if ($_REQUEST['jklaim'] > $jum and $polis['typeRate']=="Menurun") {
        $errorklaim = '<font color="red"><blink>Klaim tidak bisa di proses</blink></font>';
    } else {
        $cn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cn ORDER BY id DESC'));
        $metidcn = explode(" ", $cn['input_date']);
        $metidthncn = explode("-", $metidcn[0]);
        if ($metidthncn[0] < $futgliddn) {
            $metautocn = 1;
        } else {
            /*
                $cntglpecah = explode("-", $cn['id_cn']);
                $metautocn = $cntglpecah[3] + 1;
            */
            $metautocn = $cn['idC'] + 1;
        }
        $idcnnya = 100000000 + $metautocn;
        $idcn = substr($idcnnya, 1);
        $cntgl = explode("-", $futgldn);
        $cnthn = substr($cntgl[0], 2);
        $cn_kode = 'AJKCN-'.$cnthn.'-'.$cntgl[1].'-'.$idcn;

        //JUMLAH KLAIM BILA TIDAK DI INPUT MANUAL
        if ($_REQUEST['jklaim']=="") {
            $nilaiklaimnya = $jum;
        } else {
            $nilaiklaimnya = $_REQUEST['jklaim'];
        }
        //JUMLAH KLAIM BILA TIDAK DI INPUT MANUAL
        $fklaim = $database->doQuery('INSERT INTO fu_ajk_klaim SET id_dn="'.$fupeserta['id_dn'].'",
															 id_cn="'.$cn_kode.'",
															 id_cost="'.$fupeserta['id_cost'].'",
															 id_peserta="'.$fupeserta['id_peserta'].'",
															 tgl_klaim="'.$_REQUEST['tglklaim'].'",
															 type_klaim="Death",
															 type_subklaim="",
															 jumlah="'.$nilaiklaimnya.'",
															 ket="'.$_REQUEST['ket'].'",
															 input_by="'.$_SESSION['nm_user'].'",
															 input_date="'.$futgl.'" ');

        if ($fupeserta['cabang']=="") {
            $kcabang = $fupeserta['cabang_lama'];
        } else {
            $kcabang = $fupeserta['cabang'];
        }
        $rklaim = $database->doQuery('INSERT INTO fu_ajk_cn SET id_dn="'.$fupeserta['id_dn'].'",
															 idC="'.$metautocn.'",
															 id_cn="'.$cn_kode.'",
															 id_cost="'.$fupeserta['id_cost'].'",
															 id_nopol="'.$fupeserta['id_polis'].'",
															 id_peserta="'.$fupeserta['id_peserta'].'",
															 id_regional="'.$fupeserta['regional'].'",
															 id_cabang="'.$kcabang.'",
															 tgl_claim="'.$_REQUEST['tglklaim'].'",
															 tgl_createcn="'.$futgldn.'",
															 type_claim="Death",
															 total_claim="'.$nilaiklaimnya.'",
															 keterangan="'.$_REQUEST['ket'].'",
															 input_by="'.$_SESSION['nm_user'].'",
															 input_date="'.$futgl.'" ');
        $klaim = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_klaim ORDER BY id DESC'));
        $metupdate = $database->doQuery('UPDATE fu_ajk_peserta SET id_klaim="'.$klaim['id_cn'].'", status_peserta="Death", status_aktif="Lapse" WHERE id="'.$fupeserta['id'].'"');
        echo '<center><b>Data Klaim telah dibuat.<br /><meta http-equiv="refresh" content="3;URL=ajk_klaim.php?fu=meninggal"></b></center>';
    }
}
echo '<form method="post" action="ajk_klaim.php?fu=prosdie&id='.$_REQUEST['id'].'">
	  <table border="0" cellpadding="3" cellspacing="0" width="60%" align="center" style="border: solid 1px #DEDEDE" align="center">
	  <input type="hidden" name="id" value="'.$_REQUEST['id'].'">
	  <input type="hidden" name="kredit_tenor" value="'.$peserta['kredit_tenor'].'">
	<tr><th colspan="4">Form Pengisian Data Klaim Meninggal</th></tr>
	<tr><td width="20%">Policy Number</td><td>: <b>'.$polis['nopol'].'</b> ('.$polis['typeRate'].')</td>
		<td width="20%" align="right">Reg. ID</td><td>: <b>'.$peserta['id_peserta'].'</b></td>
	</tr>
	<tr><td>Name</td><td colspan="3">: <b>'.$peserta['nama'].'</b></td></tr>
	<tr><td>Sum Insured</td><td colspan="3">: <b>'.duit($peserta['kredit_jumlah']).'</b></td></tr>
	<tr><td>Period</td><td>: from <b>'.$peserta['kredit_tgl'].'</b> &nbsp; to : <b>'.$peserta['kredit_akhir'].'</b></td>
		<td align="right">Tenor</td><td>: <b>'.$peserta['kredit_tenor'].'</b> Bulan</td>
	</tr>
	<tr><td>Date</td><td>: ';print initCalendar();	print calendarBox('tglklaim', 'triger', $_REQUEST['tglklaim']);
echo '</td>
	<td align="right">Ms Asuransi Berjalan</td><td>: <b>'.$maj.'</b> Bulan</td></tr>
	<tr><td>Total Claim</td><td>: <input type="text" name="jklaim" value="'.$_REQUEST['jklaim'].'" onkeypress="return isNumberKey(event)"> '.$errorklaim.'</td>
		<td align="right">Max Claim</td><td>: <font color="blue"><b>'.duit($jum).'</b></font></td>
	</tr>
	<tr><td valign="top">Note</td><td colspan="3">&nbsp;<textarea rows="3" name="ket" value="'.$_REQUEST['ket'].'" cols="83">'.$_REQUEST['ket'].'</textarea></td></tr>
	<tr><td colspan="4" align="center"><input type="submit" name="oop" value="Save"></td></tr>
</table></form>';
    ;
    break;

    case "meninggal":
echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
		<tr><th colspan="8" width="100%" align="left" colspan="2">Modul Klaim - Meninggal</font></th><th width="5%" colspan="2"><a href="ajk_klaim.php?fu=newdie">New</a></th></tr>
		</table><br />
	<table border="0" width="100%" cellpadding="5" cellspacing="1" bgcolor="#bde0e6">
	<tr><th width="3%">No</td>
	<th width="5%">ID Peserta</td>
	<th width="10%">ID DN</td>
	<th width="10%">ID CN</td>
	<th>Name</td>
	<th width="8%">DOB</td>
	<th width="8%">Tgl Kredit</td>
	<th width="8%">Date Claim</td>
	<th width="5%">MA</td>
	<th width="5%">MA - J</td>
	<th width="7%">Jumlah</td>
	<th width="5%">Status</td>
	<th width="5%">Cek Doc</td>
	<th width="8%">Opt</td>
	</tr>';
if ($_REQUEST['x']) {
    $m = ($_REQUEST['x']-1) * 25;
} else {
    $m = 0;
}
$mklaim = $database->doQuery('SELECT * FROM fu_ajk_klaim WHERE type_klaim="Death" ORDER BY tgl_klaim DESC LIMIT ' . $m . ' , 25');
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_klaim WHERE id != "" AND type_klaim="Death"'));
$totalRows = $totalRows[0];
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while ($metklaim = mysql_fetch_array($mklaim)) {
    //$cek = $database->doQuery('UPDATE fu_ajk_peserta SET status_peserta="Death" WHERE id_peserta="'.$metklaim['id_peserta'].'"');
    if (($no % 2) == 1) {
        $objlass = 'tbl-odd';
    } else {
        $objlass = 'tbl-even';
    }
    $metpeserta = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_dn="'.$metklaim['id_dn'].'" AND id="'.$metklaim['id_peserta'].'" OR id_peserta="'.$metklaim['id_peserta'].'" '));

    $kredit = explode("/", $metpeserta['kredit_tgl']);
    $nowkredit = $kredit[2].'-'.$kredit[1].'-'.$kredit[0];
    $now = new T10DateCalc($nowkredit);

    $periodbulan = $now->compareDate($metklaim['tgl_klaim']) / 30.4375;
    //	$periodbulan = $now->compareDate($metklaim['tgl_klaim']);
    $maj = ceil($periodbulan);

    if ($metklaim['confirm_klaim']=="Processing") {
        $cekconfirm = '<img src="image/edit3.png">';
    } else {
        $cekconfirm = '<img src="image/edit1.png">';
    }

    $cekdoc = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_klaim_doc WHERE id_klaim = "'.$metklaim['id_cn'].'"'));
    if ($cekdoc['id_klaim']==$metklaim['id_cn']) {
        $docnya = '<a href="ajk_confirmed.php?r=eddeathclaim&id='.$metklaim['id'].'&adsess='.$q['id'].'" onclick="NewWindow(this.href,\'name\',\'625\',\'700\',\'no\');return false">'.$cekconfirm.'</a>';
    } else {
        $docnya = '<a href="ajk_confirmed.php?r=deathclaim&id='.$metklaim['id'].'&adsess='.$q['id'].'" onclick="NewWindow(this.href,\'name\',\'625\',\'700\',\'no\');return false">'.$cekconfirm.'</a>';
    }

    $a = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_klaim_doc WHERE id_klaim="'.$metklaim['id'].'"'));
    echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		<td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
		<td align="center">'.$metklaim['id_peserta'].'</td>
		<td align="center">'.$metklaim['id_dn'].'</td>
		<td align="center">'.$metklaim['id_cn'].'</td>
		<td>'.$metpeserta['nama'].'</td>
		<td align="center">'.$metpeserta['tgl_lahir'].'</td>
		<td align="center">'.$metpeserta['kredit_tgl'].'</td>
		<td align="center">'._convertDate($metklaim['tgl_klaim']).'</td>
		<td align="center">'.$metpeserta['kredit_tenor'].'</td>
		<td align="center">'.$maj.'</td>
		<td align="right">'.duit($metklaim['jumlah']).'</td>
		<td align="right">'.$metklaim['confirm_klaim'].'</td>
		<td align="center">'.$docnya.'</td>
		<td align="center"><a href="ajk_klaim.php?fu=klaimedit&id='.$metklaim['id'].'">Edit</a> &nbsp; <a href="#">Cancel</a></td>
		</tr>';
}
        echo '<tr><td colspan="22">';
        echo createPageNavigations($file = 'ajk_klaim.php?fu=meninggal', $total = $totalRows, $psDeh = 10, $anchor = '', $perPage = 25);
        echo '<b>Total Data Peserta: <u>' . $totalRows . '</u></b></td></tr>';
echo '</table>';
        ;
        break;

    case "klaimedit":
echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
	<tr><th colspan="8" width="100%" align="left" colspan="2">Modul Edit Klaim - Meninggal</font></th><th width="5%" colspan="2"><a href="ajk_klaim.php?fu=meninggal"><img src="image/Backward-64.png" width="20"></a></th></tr>
	</table><br />';
$cek = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_klaim WHERE id="'.$_REQUEST['id'].'"'));
$ceknama = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_klaim="'.$cek['id_cn'].'"'));
$cekpolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$ceknama['id_polis'].'"'));

if ($_REQUEST['ooed']=="Save") {
    $metupdate = $database->doQuery('UPDATE fu_ajk_klaim SET tgl_klaim="'.$_REQUEST['tgl_klaim'].'",
														 jumlah="'.$_REQUEST['jklaim'].'",
														 ket="'.$_REQUEST['ket'].'",
														 update_by="'.$q['nm_user'].'",
														 update_time="'.$futgl.'",
														 WHERE id="'.$_REQUEST['id'].'"');
    echo '<center><b>Data Klaim telah diedit.<br /><meta http-equiv="refresh" content="3;URL=ajk_klaim.php?fu=meninggal"></b></center>';
}
echo '<form method="post" action="">
	  <table border="0" cellpadding="3" cellspacing="0" width="60%" align="center" style="border: solid 1px #DEDEDE" align="center">
	  <input type="hidden" name="id" value="'.$_REQUEST['id'].'">
	  <input type="hidden" name="kredit_tenor" value="'.$ceknama['kredit_tenor'].'">
	<tr><th colspan="4">Form Pengisian Data Klaim Meninggal</th></tr>
	<tr><td width="20%">Policy Number</td><td>: <b>'.$cekpolis['nopol'].'</b> ('.$cekpolis['typeRate'].')</td>
		<td width="20%" align="right">Reg. ID</td><td>: <b>'.$ceknama['id_peserta'].'</b></td>
	</tr>
	<tr><td>Name</td><td colspan="3">: <b>'.$ceknama['nama'].'</b></td></tr>
	<tr><td>Sum Insured</td><td colspan="3">: <b>'.duit($ceknama['kredit_jumlah']).'</b></td></tr>
	<tr><td>Period</td><td>: from <b>'.$ceknama['kredit_tgl'].'</b> &nbsp; to : <b>'.$ceknama['kredit_akhir'].'</b></td>
		<td align="right">Tenor</td><td>: <b>'.$ceknama['kredit_tenor'].'</b> Bulan</td>
	</tr>
	<tr><td>Date</td><td>: ';print initCalendar();	print calendarBox('tgl_klaim', 'triger', $cek['tgl_klaim']);
echo '</td>
	<td align="right"> </td></tr>
	<tr><td>Total Claim</td><td>: <input type="text" name="jklaim" value="'.$cek['jumlah'].'" onkeypress="return isNumberKey(event)"></td>
		<td align="right"></td>
	</tr>
	<tr><td valign="top">Note</td><td colspan="3">&nbsp;<textarea rows="3" name="ket" value="'.$_REQUEST['ket'].'" cols="83">'.$cek['ket'].'</textarea></td></tr>
	<tr><td colspan="4" align="center"><input type="submit" name="ooed" value="Save"></td></tr>
</table></form>';
    ;
    break;

    case "restruktur":
echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
		<tr><th colspan="8" width="100%" align="left" colspan="2">Modul Klaim - Restruktur</font></th></tr>
		</table><br />
<fieldset>
	<legend align="center">S e a r c h</legend>
	<table border="0" width="50%" cellpadding="3" cellspacing="1">
	<form method="post" action="ajk_klaim.php?fu=restruktur">
	<tr><td width="10%">Nama</td><td>: <input type="text" name="cnama" value="'.$_REQUEST['cnama'].'" size="40"> &nbsp; <input type="submit" name="button" value="Search" class="button"></td></tr>
		</form>
		</table></fieldset>
	<table border="0" width="100%" cellpadding="5" cellspacing="1" bgcolor="#bde0e6">
	<tr><th rowspan="2" width="3%">Opt</td>
		<th rowspan="2" width="3%">No</td>
		<th rowspan="2" width="5%">No.SPAJ</td>
		<th rowspan="2" width="13%">ID DN</td>
		<th rowspan="2">Name</td>
		<th rowspan="2" width="7%">DOB</td>
		<th colspan="3"width="15%">Credit</td>
		<th rowspan="2" width="5%">Premi</td>
		<th rowspan="2" width="5%">Date Claim</td>
		<th rowspan="2" width="5%">Jumlah</td>
		<th rowspan="2" width="12%">Cabang</td>
		<th rowspan="2" width="8%">Regional</td>
	</tr>
	<tr><th>Date</th><th>Premium</th><th>Tenor</th></tr>';
if ($_REQUEST['ope']=="y") {
    $cekmetnew = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id="'.$_REQUEST['id'].'"'));	//NAMA PESERTA BARU
$cekmet = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE nama="'.$cekmetnew['nama'].'" AND tgl_lahir="'.$cekmetnew['tgl_lahir'].'" AND status_aktif="aktif" AND status_bayar="1" AND id_klaim=""'));	//NAMA PESERTA LAMA
if ($cekmet['cabang']=="") {
    $cabangcn =$cekmet['cabang_lama'];
} else {
    $cabangcn =$cekmet['cabang'];
}

    if ($cekmetnew['nama'] != $cekmet['nama'] and $cekmetnew['tgl_lahir'] != $cekmet['tgl_lahir'] or $cekmet['status_bayar']=="0") {
        echo '<center><blink><font color="red"><b>Data tidak bisa di compare karena data peserta tidak ada pada data sebelumnya atau status pembayaran masih unpaid.</b></font></blink></center>';
    } else {

//script pembentukan nomor dn//
        $dn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn ORDER BY id DESC'));
        $metid = explode(" ", $dn['input_time']);
        $metidthn = explode("-", $metid[0]);
        if ($metidthn[0] < $futgliddn) {
            $metauto = 1;
        } else {
            $metauto = $dn['id_dn'] + 1;
        }
        $idnya = 100000000 + $metauto;
        $iddn = substr($idnya, 1);
        $dntgl = explode("-", $futgldn);
        $dnthn = substr($dntgl[0], 2);
        $dn_kode = 'AJKDN-'.$dnthn.'-'.$dntgl[1].'-'.$iddn;
        //script pembentukan nomor dn//
        $Rdn = $database->doQuery('INSERT INTO fu_ajk_dn SET id_cost="'.$cekmetnew['id_cost'].'",
												   id_dn="'.$metauto.'",
												   id_nopol="'.$cekmetnew['id_polis'].'",
												   id_regional="'.$cekmetnew['regional'].'",
												   id_area="'.$cekmetnew['area'].'",
												   id_cabang="'.$cekmetnew['cabang'].'",
												   dn_kode="'.$dn_kode.'",
												   validasi_uw="ya",
												   dn_status="unpaid",
												   totalpremi="'.$cekmetnew['premi'].'",
												   tgl_createdn="'.$futgldn.'",
												   namafile="'.$cekmetnew['namafile'].'",
												   input_by="'.$_SESSION['nm_user'].'",
												   input_time="'.$futgl.'"');
        echo '<br /><br />';
        //perhitungan premi restruktur (MA(kredit_tgl(Data Awal)) - MA.J(kredit_tgl(Data Baru)) / MA(kredit_tgl(Data Awal)) * 100%)*premi

        $awal = explode("/", $cekmet['kredit_tgl']);
        $hari = $awal[0];
        $bulan = $awal[1];
        $tahun = $awal[2];

        $akhir = explode("/", $cekmetnew['kredit_tgl']);
        $hari2 = $akhir[0];
        $bulan2 = $akhir[1];
        $tahun2 = $akhir[2];

        $jhari=(mktime(0, 0, 0, $bulan2, $hari2, $tahun2) - mktime(0, 0, 0, $bulan, $hari, $tahun))/86400;
        $sisahr=floor($jhari);
        $sisabulan =ceil($sisahr / 30.4375);
        $masisa = $cekmet['kredit_tenor'] - $sisabulan;
        $hitungcn = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_rasio_claim WHERE id_cost="'.$cekmet['id_cost'].'" AND id_polis="'.$cekmet['id_polis'].'"'));
        //$jumlahnya = ((($cekmet['kredit_tenor'] - $sisabulan) / $cekmet['kredit_tenor']) * $hitungcn['restruktur']) * $cekmet['premi'];
        $jumlahnya = (($masisa / $cekmet['kredit_tenor']) * $hitungcn['restruktur']) * $cekmet['premi'];

        //script pembentukan nomor cn//
        $cn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cn ORDER BY id DESC'));
        $metidcn = explode(" ", $cn['input_date']);
        $metidthncn = explode("-", $metidcn[0]);
        if ($metidthncn[0] < $futgliddn) {
            $metautocn = 1;
        } else {
            $metautocn = $cn['idC'] + 1;
        }

        $idcnnya = 100000000 + $metautocn;
        $idcn = substr($idcnnya, 1);
        $cntgl = explode("-", $futgldn);
        $cnthn = substr($cntgl[0], 2);
        $cn_kode = 'AJKCN-'.$cnthn.'-'.$cntgl[1].'-'.$idcn;

        //script pembentukan nomor cn//
        $Rcn = $database->doQuery('INSERT INTO fu_ajk_cn SET id_cost="'.$cekmet['id_cost'].'",
												   idC="'.$metautocn.'",
												   id_cn="'.$cn_kode.'",
												   id_dn="'.$dn_kode.'",
												   id_nopol="'.$cekmet['id_polis'].'",
												   id_peserta="'.$cekmet['id_peserta'].'",
												   id_cabang="'.$cabangcn.'",
												   premi="'.$cekmet['premi'].'",
												   total_claim="'.$jumlahnya.'",
												   tgl_claim="'.$cekmetnew['kredit_tgl'].'",
												   type_claim="'.$cekmetnew['status_peserta'].'",
												   tgl_createcn="'.$futgldn.'",
												   tgl_byr_claim="",
												   confirm_claim="Approve(paid)",
												   input_by="'.$_SESSION['nm_user'].'",
												   input_date="'.$futgl.'"');
        echo '<br /><br />';
        $idp = $cekmetnew['id'];
        $idp2 = 100000000 + $idp;
        $id_peserta = substr($idp2, 1);
        $rDN = $database->doQuery('UPDATE fu_ajk_peserta SET id_dn="'.$dn_kode.'", id_peserta="'.$id_peserta.'" WHERE id="'.$_REQUEST['id'].'"');
        $rCN = $database->doQuery('UPDATE fu_ajk_peserta SET id_klaim="'.$cn_kode.'", status_peserta="Cancel", status_aktif="Lapse" WHERE id="'.$cekmet['id'].'"');
    }
}
//disabled batal dn claim
if ($_REQUEST['ope']=="t") {
    $tmet = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id="'.$_REQUEST['id'].'"'));
    $tmetz = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE nama="'.$tmet['nama'].'"'));
    $tmett = $database->doQuery('UPDATE fu_ajk_peserta SET id_dn="", id_polis="", id_klaim="" WHERE nama="'.$tmet['nama'].'" AND id="'.$_REQUEST['id'].'"');
    $tklaim = $database->doQuery('DELETE FROM fu_ajk_klaim WHERE id_peserta="'.$_REQUEST['id'].'"');
    $sdata = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE status_peserta="Cancel" AND nama="'.$tmet['nama'].'"'));
    $sdata2 = $database->doQuery('UPDATE fu_ajk_peserta SET status_peserta="" WHERE nama="'.$sdata['nama'].'" AND tgl_lahir="'.$sdata['tgl_lahir'].'" AND status_peserta="Cancel"');
}
//disabled batal dn claim

if ($_REQUEST['x']) {
    $m = ($_REQUEST['x']-1) * 25;
} else {
    $m = 0;
}
if ($_REQUEST['cnama']) {
    $dua = 'AND nama LIKE "%' . $_REQUEST['cnama'] . '%"';
}

$mklaim = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE status_peserta="Restruktur" AND status_aktif="aktif" '.$dua.' AND del IS NULL ORDER BY id_dn ASC, input_time DESC, id DESC LIMIT ' . $m . ' , 25');
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_peserta WHERE id != "" AND status_peserta="Restruktur" AND status_aktif="aktif" AND del IS NULL '.$dua.''));
$totalRows = $totalRows[0];
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;

while ($metklaim = mysql_fetch_array($mklaim)) {
    if (($no % 2) == 1) {
        $objlass = 'tbl-odd';
    } else {
        $objlass = 'tbl-even';
    }
    $tglklaim = explode(" ", $metklaim['input_time']);
    $tglklaim2 = $tglklaim[0];

    if ($metklaim['id_dn']=="") {
        $confirm = '<a href="ajk_klaim.php?fu=restruktur&ope=y&id='.$metklaim['id'].'">Ya</a>';
    } else {
        $confirm = 'DN Create';
    }

    //CEK FORMAT TANGGAL
    $findmet="/";
    $fpos = stripos($metklaim['tgl_lahir'], $findmet);
    if ($fpos === false) {
        $riweuh = explode("-", $metklaim['tgl_lahir']);
        $cektglnya = $riweuh[0].'/'.$riweuh[1].'/'.$riweuh[2];
    } else {
        $riweuh = explode("/", $metklaim['tgl_lahir']);
        $cektglnya = $riweuh[0].'/'.$riweuh[1].'/'.$riweuh[2];
    }
    //CEK FORMAT TANGGAL

    if ($metklaim['cabang']=="") {
        $cabangnya = $metklaim['cabang_lama'];
    } else {
        $cabangnya = $metklaim['cabang'];
    }

    $cekpeserta = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE nama="'.$metklaim['nama'].'" AND tgl_lahir="'.$metklaim['tgl_lahir'].'"'));
    if ($metklaim['id_dn']=="") {
        $hapusmovement = '<a href="ajk_klaim.php?fu=hapusmove&met=dellrestruktur&id='.$metklaim['id'].'"><img src="image/deleted.png" border="0" width="15"></a>';
    } else {
        $hapusmovement = '';
    }
    echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
			<td align="center">'.$hapusmovement.'</td>
			<td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
			<td align="center">'.$metklaim['spaj'].'</td>
			<td align="center">'.$metklaim['id_dn'].'</td>
			<td><a href="ajk_klaim.php?fu=restrukturmember&id='.$metklaim['id'].'">'.$metklaim['nama'].'</a></td>
			<td align="center">'.$cektglnya.'</td>
			<td align="center">'.$metklaim['kredit_tgl'].'</td>
			<td align="right">'.duit($metklaim['kredit_jumlah']).'</td>
			<td align="center">'.$metklaim['kredit_tenor'].'</td>
			<td align="right">'.duit($metklaim['premi']).'</td>
			<td align="center">'._convertDate($tglklaim2).'</td>
			<td align="right">'.duit($metklaim['totalpremi']).'</td>
			<td align="right">'.$cabangnya.'</td>
			<td align="right">'.$metklaim['regional'].'</td>
			</tr>';
}
echo '<tr><td colspan="22">';
echo createPageNavigations($file = 'ajk_klaim.php?fu=restruktur', $total = $totalRows, $psDeh = 10, $anchor = '', $perPage = 25);
echo '<b>Total Data Peserta Restruktur: <u>' . $totalRows . '</u></b></td></tr>';
echo '</table>';
        ;
        break;

    case "restrukturmember":
echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
		<tr><th colspan="8" width="100%" align="left" colspan="2">Modul Klaim - Restruktur Member</font></th><th><a href="ajk_klaim.php?fu=restruktur"><img src="image/Backward-64.png" width="20"></a></th></tr>
		</table><br />';
if ($_REQUEST['ope']="restruktur") {
    $deb_baru = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id="'.$_REQUEST['id'].'"'));
    if ($deb_baru['cabang']=="") {
        $cabangcn 	=$deb_baru['cabang_lama'];
    } else {
        $cabangcn 	=$deb_baru['cabang'];
    }
    if ($deb_baru['regional']=="") {
        $regionalcn =$deb_baru['regional_lama'];
    } else {
        $regionalcn =$deb_baru['regional'];
    }

    //script pembentukan nomor dn//
    $dn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn ORDER BY id DESC'));
    $metid = explode(" ", $dn['input_time']);
    $metidthn = explode("-", $metid[0]);
    if ($metidthn[0] < $futgliddn) {
        $metauto = 1;
    } else {
        $metauto = $dn['id_dn'] + 1;
    }
    $idnya = 100000000 + $metauto;
    $iddn = substr($idnya, 1);
    $dntgl = explode("-", $futgldn);
    $dnthn = substr($dntgl[0], 2);
    $dn_kode = 'AJKDN-'.$dnthn.'-'.$dntgl[1].'-'.$iddn;
    //script pembentukan nomor dn//
    $idp2 = 100000000 + $deb_baru['id'];
    $id_peserta = substr($idp2, 1);

    if (isset($_POST['submit']) && ($_POST['idl'])) {
        $metdnbaru = $database->doQuery('INSERT INTO fu_ajk_dn SET id_cost="'.$deb_baru['id_cost'].'",
												   id_dn="'.$metauto.'",
												   id_nopol="'.$deb_baru['id_polis'].'",
												   id_regional="'.$deb_baru['regional'].'",
												   id_area="'.$deb_baru['area'].'",
												   id_cabang="'.$deb_baru['cabang'].'",
												   dn_kode="'.$dn_kode.'",
												   validasi_uw="ya",
												   dn_status="unpaid",
												   totalpremi="'.$deb_baru['premi'].'",
												   tgl_createdn="'.$futgldn.'",
												   namafile="'.$deb_baru['namafile'].'",
												   input_by="'.$_SESSION['nm_user'].'",
												   input_time="'.$futgl.'"');
        echo '<br /><br />';
        $updatednbaru = $database->doQuery('UPDATE fu_ajk_peserta SET id_dn="'.$dn_kode.'" WHERE id="'.$_REQUEST['id'].'"');

        if (isset($_POST['submit']) && ($_POST['idl'])) {
            for ($i=0; $i<count($_POST['idl']);$i++) {
                $deb_lama = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id="'.$_POST['idl'][$i].'"'));
                //script pembentukan nomor cn//
                $cn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cn ORDER BY id DESC'));
                $metidcn = explode(" ", $cn['input_date']);
                $metidthncn = explode("-", $metidcn[0]);
                if ($metidthncn[0] < $futgliddn) {
                    $metautocn = 1;
                } else {
                    $metautocn = $cn['idC'] + 1;
                }

                $idcnnya = 100000000 + $metautocn;
                $idcn = substr($idcnnya, 1);
                $cntgl = explode("-", $futgldn);
                $cnthn = substr($cntgl[0], 2);
                $cn_kode = 'AJKCN-'.$cnthn.'-'.$cntgl[1].'-'.$idcn;

                //total premi cn
                $awal = explode("/", $deb_lama['kredit_tgl']);
                $hari = $awal[0];
                $bulan = $awal[1];
                $tahun = $awal[2];
                $akhir = explode("/", $deb_baru['kredit_tgl']);
                $hari2 = $akhir[0];
                $bulan2 = $akhir[1];
                $tahun2 = $akhir[2];
                $jhari=(mktime(0, 0, 0, $bulan2, $hari2, $tahun2) - mktime(0, 0, 0, $bulan, $hari, $tahun))/86400;
                $sisahr=floor($jhari);
                $sisabulan =ceil($sisahr / 30.4375);
                $masisa = $deb_lama['kredit_tenor'] - $sisabulan;

                $hitungcn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_rasio_claim WHERE id_cost="'.$deb_lama['id_cost'].'" AND id_polis="'.$deb_lama['id_polis'].'"'));
                //$jumlahnya = (($deb_lama['kredit_tenor'] - $metbulan) / $deb_lama['kredit_tenor']) * $hitungcn['topup'] * $deb_lama['premi'];
                $jumlahnya = (($masisa / $deb_lama['kredit_tenor']) * $hitungcn['restruktur']) * $deb_lama['premi'];

                //total premi cn
                $metcnlama = $database->doQuery('INSERT INTO fu_ajk_cn SET id_cost="'.$deb_lama['id_cost'].'",
												   idC="'.$metautocn.'",
												   id_cn="'.$cn_kode.'",
												   id_dn="'.$dn_kode.'",
												   id_nopol="'.$deb_lama['id_polis'].'",
												   id_peserta="'.$id_peserta.'",
												   id_regional="'.$regionalcn.'",
												   id_cabang="'.$cabangcn.'",
												   premi="'.$deb_lama['premi'].'",
												   total_claim="'.$jumlahnya.'",
												   tgl_claim="'.$deb_baru['kredit_tgl'].'",
												   type_claim="'.$deb_baru['status_peserta'].'",
												   tgl_createcn="'.$futgldn.'",
												   tgl_byr_claim="",
												   confirm_claim="Approve(paid)",
												   input_by="'.$_SESSION['nm_user'].'",
												   input_date="'.$futgl.'"');
                echo '<br /><br />';
                $updatecnlama = $database->doQuery('UPDATE fu_ajk_peserta SET id_klaim="'.$cn_kode.'", status_aktif="Lapse" WHERE id="'.$_POST['idl'][$i].'"');
                $sendmail = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id="'.$deb_lama['id'].'"'));			//DATA CN DARI PESERTA LAMA
        $exwilayah = explode(" ", $sendmail['regional']);	//PECAH WILAYAH PENAMAAN UNTUK EMIAL
        $sendemailcekcn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cn WHERE id_cn="'.$sendmail['id_klaim'].'"'));		//MENCOCOKAN TABLE CN DGN PESERTA LAMA

        $metmail = explode("- ", $sendmail['input_by']);

                /* FORMAT EMAIL LANGSUNG KE KLIENT
                    $fumail = mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$metmail[0].'" AND id_cost="'.$sendmail['id_cost'].'"'));
                    $Rmoremail = $database->doQuery('SELECT * FROM pengguna WHERE wilayah LIKE "%'.$exwilayah[0].'%" AND id_cost="'.$sendmail['id_cost'].'"');
                    while ($metmormail = mysql_fetch_array($Rmoremail)) {	$Rmoremailer .= $metmormail['email'].', ';	}
                FORMAT EMAIL LANGSUNG KE KLIENT */

                $emailARM = $database->doQuery('SELECT * FROM pengguna WHERE status="20"');
                while ($mailarm = mysql_fetch_array($emailARM)) {
                    $ARMemail .=$mailarm['email'].', ';
                }

                //$to = $ARMemail.''.$fumail['email']." sumiyanto@relife.co.id, arief.kurniawan@relife.co.id";
                $subject = 'AJKOnline - Data '.$sendmail['status_peserta'].' telah di buat oleh '.$q['nm_lengkap'].'';
                $message = '<html><head><title>CN CREATE</title></head><body>
			<table border="0" width="100%">
			<tr><td colspan="7">To '.$metmail[0].'</td></tr>
			<tr><td colspan="7"><br />Telah di buat Data CN oleh : <b>'.$q['nm_lengkap'].' </b> pada tanggal '._convertDate($futgldn).'</td></tr>
			<tr><th width="5%" align="center">SPAJ</th>
				<th align="center">Debitur</th>
				<th width="10%" align="center">Tanggal Lahir</th>
				<th width="15%" align="center">Nomor CN</th>
				<th width="10%" align="center">Refund Premi</th>
				<th width="10%" align="center">Movement</th>
				<th width="15%" align="center">Cabang</th>
				<th width="15%" align="center">Regional</th>
				</tr>
			<tr><td align="center">'.$sendemailcekcn['spaj'].'</td>
				<td>'.$sendmail['nama'].'</td>
				<td align="center">'.$sendmail['tgl_lahir'].'</td>
				<td>'.$sendemailcekcn['id_cn'].'</td>
				<td align="center">'.duit($sendemailcekcn['total_claim']).'</td>
				<td align="center">'.$sendemailcekcn['type_claim'].'</td>
				<td align="center">'.$sendemailcekcn['id_cabang'].'</td>
				<td align="center">'.$sendemailcekcn['id_regional'].'</td>
			</tr>
			</table>
		</body>
		</html>';
                $headers  = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                $headers .= 'From: '.$q['email'].'' . "\r\n";
                // $headers .= 'Cc:  relife-ajk@relife.co.id' . "\r\n";
                mail($to, $subject, $message, $headers);
            }
        }
    } else {
        echo '<center><b></font>Silahkan Ceklist Nama Peserta Yang Akan Di Buatkan Data CN</b></center>';
    }
}
$met = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id="'.$_REQUEST['id'].'"'));
if ($met['id_dn']=="") {
    $createdn = '<input type="submit" name="submit" value="Submit" />';
} else {
    $createdn = '';
}
echo '<table border="0" width="100%" cellpadding="5" cellspacing="1" bgcolor="#bde0e6">
	<tr><th rowspan="2" width="5%">No.SPAJ</td>
		<th rowspan="2" width="15%">ID CN</td>
		<th rowspan="2" width="15%">ID DN</td>
		<th rowspan="2">Name</td>
		<th rowspan="2" width="7%">DOB</td>
		<th colspan="3"width="15%">Credit</td>
		<th rowspan="2" width="7%">Premi</td>
		<th rowspan="2" width="7%">Date Claim</td>
		<th rowspan="2" width="7%">Jumlah</td>
		<th rowspan="2" width="10%">Status</td>
		<th rowspan="2" width="10%">Cabang</td>
		<th rowspan="2" width="7%">Regional</td>
		<th rowspan="2" width="5%">Option</td>
	</tr>
	<tr><th>Date</th><th>Premium</th><th>Tenor</th></tr>
	<form method="post" action="ajk_klaim.php?fu=restrukturmember&ope=restruktur&id='.$_REQUEST['id'].'">';
echo '<tr><td align="center">'.$met['spaj'].'</td>
		<td align="center">'.$met['id_klaim'].'</td>
		<td align="center">'.$met['id_dn'].'</td>
		<td><a href="ajk_klaim.php?fu=restrukturmember&id='.$met['id'].'">'.$met['nama'].'</a></td>
		<td align="center">'.$met['tgl_lahir'].'</td>
		<td align="center">'.$met['kredit_tgl'].'</td>
		<td align="right">'.duit($met['kredit_jumlah']).'</td>
		<td align="center">'.$met['kredit_tenor'].'</td>
		<td align="right">'.duit($met['premi']).'</td>
		<td align="center">'.$met['kredit_akhir'].'</td>
		<td align="right">'.duit($met['totalpremi']).'</td>
		<td align="center">'.$met['status_peserta'].'</td>
		<td align="center">'.$met['cabang'].'</td>
		<td align="center">'.$met['regional'].'</td>
		<td align="center">'.$createdn.'</td>
	</tr>';
$mklaim = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE nama="'.$met['nama'].'" AND tgl_lahir="'.$met['tgl_lahir'].'" AND id_dn !="" AND status_aktif="aktif"  AND del IS NULL');
while ($metklaim = mysql_fetch_array($mklaim)) {
    if ($metklaim['id_klaim']=="") {
        $cekdatacn = '<input type="checkbox" name="idl[]" id="checkbox" value="'.$metklaim['id'].'">';
    } else {
        $cekdatacn ='<b>done</b>';
    }
    echo '<tr bgcolor="#fff"><td align="center">'.$metklaim['spaj'].'</td>
		<td align="center">'.$metklaim['id_klaim'].'</td>
		<td align="center">'.$metklaim['id_dn'].'</td>
		<td><a href="ajk_klaim.php?fu=movementopup&id='.$metklaim['id'].'">'.$metklaim['nama'].'</a></td>
		<td align="center">'.$metklaim['tgl_lahir'].'</td>
		<td align="center">'.$metklaim['kredit_tgl'].'</td>
		<td align="right">'.duit($metklaim['kredit_jumlah']).'</td>
		<td align="center">'.$metklaim['kredit_tenor'].'</td>
		<td align="right">'.duit($metklaim['premi']).'</td>
		<td align="center">'.$metklaim['kredit_tgl'].'</td>
		<td align="right">'.duit($metklaim['totalpremi']).'</td>
		<td align="center">'.$metklaim['status_peserta'].'</td>
		<td align="center">'.$metklaim['cabang'].'</td>
		<td align="center">'.$metklaim['regional'].'</td>
		<td align="center">'.$cekdatacn.'</td>
		</tr>';
}
echo '</table>';

    break;

    case "resbaloon":
echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
		<tr><th colspan="8" width="100%" align="left" colspan="2">Modul Klaim - Baloon Payment</font></th></tr>
		</table><br />
		<fieldset>
	<legend align="center">S e a r c h</legend>
	<table border="0" width="50%" cellpadding="3" cellspacing="1">
	<form method="post" action="ajk_klaim.php?fu=resbaloon">
	<tr><td width="10%">Nama</td><td>: <input type="text" name="cnama" value="'.$_REQUEST['cnama'].'"> &nbsp; <input type="submit" name="button" value="Search" class="button"></td></tr>
		</form>
		</table></fieldset>
	<table border="0" width="100%" cellpadding="5" cellspacing="1" bgcolor="#bde0e6">
	<tr><th rowspan="2" width="3%">Opt</td>
	<th rowspan="2" width="3%">No</td>
	<th rowspan="2" width="5%">No.SPAJ</td>
	<th rowspan="2" width="15%">ID DN</td>
	<th rowspan="2">Name</td>
	<th rowspan="2" width="7%">DOB</td>
	<th colspan="3"width="15%">Credit</td>
	<th rowspan="2" width="7%">Premi</td>
	<th rowspan="2" width="7%">Date Claim</td>
	<th rowspan="2" width="7%">Jumlah</td>
<!--<th rowspan="2" width="5%">Confirm</td>-->
	</tr>
	<tr><th>Date</th><th>Premium</th><th>Tenor</th></tr>';
if ($_REQUEST['ope']=="y") {
    $cekmetnew = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id="'.$_REQUEST['id'].'"'));	//NAMA PESERTA BARU
    $cekmet = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE status_bayar=1 AND status_aktif="Lapse" AND nama="'.$cekmetnew['nama'].'" AND tgl_lahir="'.$cekmetnew['tgl_lahir'].'"'));	//NAMA PESERTA LAMA
    if ($cekmet['cabang']=="") {
        $cabangcn =$cekmet['cabang_lama'];
    } else {
        $cabangcn =$cekmet['cabang'];
    }
    if ($cekmet['regional']=="") {
        $regionalcn =$cekmet['regional_lama'];
    } else {
        $regionalcn =$cekmet['regional'];
    }
    //script pembentukan nomor dn//
    $dn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn ORDER BY id DESC'));
    $metid = explode(" ", $dn['input_time']);
    $metidthn = explode("-", $metid[0]);
    if ($metidthn[0] < $futgliddn) {
        $metauto = 1;
    } else {
        $metauto = $dn['id_dn'] + 1;
    }
    $idnya = 100000000 + $metauto;
    $iddn = substr($idnya, 1);
    $dntgl = explode("-", $futgldn);
    $dnthn = substr($dntgl[0], 2);
    $dn_kode = 'AJKDN-'.$dnthn.'-'.$dntgl[1].'-'.$iddn;
    //script pembentukan nomor dn//

    $Rdn = $database->doQuery('INSERT INTO fu_ajk_dn SET id_cost="'.$cekmetnew['id_cost'].'",
												   id_dn="'.$metauto.'",
												   id_nopol="'.$cekmetnew['id_polis'].'",
												   id_regional="'.$cekmetnew['regional'].'",
												   id_area="'.$cekmetnew['area'].'",
												   id_cabang="'.$cekmetnew['cabang'].'",
												   dn_kode="'.$dn_kode.'",
												   validasi_uw="ya",
												   dn_status="unpaid",
												   totalpremi="'.$cekmetnew['premi'].'",
												   tgl_createdn="'.$futgldn.'",
												   input_by="'.$_SESSION['nm_user'].'",
												   input_time="'.$futgl.'"');

    $rDN = $database->doQuery('UPDATE fu_ajk_peserta SET id_dn="'.$dn_kode.'" WHERE id="'.$_REQUEST['id'].'"');

    $awal = explode("/", $cekmet['kredit_tgl']);
    $hari = $awal[0];
    $bulan = $awal[1];
    $tahun = $awal[2];

    $akhir = explode("/", $cekmetnew['kredit_tgl']);
    $hari2 = $akhir[0];
    $bulan2 = $akhir[1];
    $tahun2 = $akhir[2];

    $jhari=(mktime(0, 0, 0, $bulan2, $hari2, $tahun2) - mktime(0, 0, 0, $bulan, $hari, $tahun))/86400;
    $sisahr=floor($jhari);
    $sisabulan =ceil($sisahr / 30.4375);
    $masisa = $cekmet['kredit_tenor'] - $sisabulan;
    $hitungcn = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_rasio_claim WHERE id_cost="'.$cekmet['id_cost'].'" AND id_polis="'.$cekmet['id_polis'].'"'));
    //perhitungan premi restruktur (MA(kredit_tgl(Data Awal)) - MA.J(kredit_tgl(Data Baru)) / MA(kredit_tgl(Data Awal)) * 100%)*premi
    //$jumlahnya = ((($cekmet['kredit_tenor'] - $sisabulan) / $cekmet['kredit_tenor']) * $hitungcn['restruktur']) * $cekmet['premi'];
    $jumlahnya = (($masisa / $cekmet['kredit_tenor']) * $hitungcn['restruktur']) * $cekmet['premi'];
    //	echo $jumlahnya;

    //script pembentukan nomor cn//
    $cn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cn ORDER BY id DESC'));
    $metidcn = explode(" ", $cn['input_date']);
    $metidthncn = explode("-", $metidcn[0]);
    if ($metidthncn[0] < $futgliddn) {
        $metautocn = 1;
    } else {
        $metautocn = $cn['idC'] + 1;
    }

    $idcnnya = 100000000 + $metautocn;
    $idcn = substr($idcnnya, 1);
    $cntgl = explode("-", $futgldn);
    $cnthn = substr($cntgl[0], 2);
    $cn_kode = 'AJKCN-'.$cnthn.'-'.$cntgl[1].'-'.$idcn;
    ;
    //script pembentukan nomor dn//
    $Rcn = $database->doQuery('INSERT INTO fu_ajk_cn SET id_cost="'.$cekmet['id_cost'].'",
												   idC="'.$metautocn.'",
												   id_cn="'.$cn_kode.'",
												   id_dn="'.$dn_kode.'",
												   id_nopol="'.$cekmet['id_polis'].'",
												   id_peserta="'.$cekmet['id_peserta'].'",
												   id_regional="'.$regionalcn.'",
												   id_cabang="'.$cabangcn.'",
												   premi="'.$cekmet['premi'].'",
												   total_claim="'.$jumlahnya.'",
												   tgl_claim="'.$cekmetnew['kredit_tgl'].'",
												   type_claim="'.$cekmetnew['status_peserta'].'",
												   confirm_claim="Approve(paid)",
												   tgl_createcn="'.$futgldn.'",
												   input_by="'.$_SESSION['nm_user'].'",
												   input_date="'.$futgl.'"');
    $rCN = $database->doQuery('UPDATE fu_ajk_peserta SET id_klaim="'.$cn_kode.'" WHERE id="'.$cekmet['id'].'"');
}

if ($_REQUEST['ope']=="t") {
    $tmet = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id="'.$_REQUEST['id'].'"'));
    $tmetz = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE nama="'.$tmet['nama'].'"'));
    $tmett = $database->doQuery('UPDATE fu_ajk_peserta SET id_dn="", id_polis="", id_klaim="" WHERE nama="'.$tmet['nama'].'" AND id="'.$_REQUEST['id'].'"');
    $tklaim = $database->doQuery('DELETE FROM fu_ajk_klaim WHERE id_peserta="'.$_REQUEST['id'].'"');
    $sdata = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE status_peserta="Cancel" AND nama="'.$tmet['nama'].'"'));
    $sdata2 = $database->doQuery('UPDATE fu_ajk_peserta SET status_peserta="" WHERE nama="'.$sdata['nama'].'" AND tgl_lahir="'.$sdata['tgl_lahir'].'" AND status_peserta="Cancel"');
}

if ($_REQUEST['x']) {
    $m = ($_REQUEST['x']-1) * 25;
} else {
    $m = 0;
}

if ($_REQUEST['cnama']) {
    $dua = 'AND nama LIKE "%' . $_REQUEST['cnama'] . '%"';
}

$mklaim = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE status_peserta="Baloon" '.$dua.' AND status_aktif="aktif" AND del IS NULL ORDER BY id_dn ASC, input_time DESC, cabang ASC, id DESC LIMIT ' . $m . ' , 25');
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_peserta WHERE id != "" AND status_peserta="Baloon" AND status_aktif="aktif"  AND del IS NULL  '.$dua.' '));
$totalRows = $totalRows[0];
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;

while ($metklaim = mysql_fetch_array($mklaim)) {
    if (($no % 2) == 1) {
        $objlass = 'tbl-odd';
    } else {
        $objlass = 'tbl-even';
    }
    $tglklaim = explode(" ", $metklaim['input_time']);
    $tglklaim2 = $tglklaim[0];

    if ($metklaim['id_dn']=="") {
        $confirm = '<a href="ajk_klaim.php?fu=resbaloon&ope=y&id='.$metklaim['id'].'">Ya</a>';
    } else {
        $confirm = 'DN Create';
    }

    $cekpeserta = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE nama="'.$metklaim['nama'].'" AND tgl_lahir="'.$metklaim['tgl_lahir'].'"'));

    if ($metklaim['id_dn']=="") {
        $hapusmovement = '<a href="ajk_klaim.php?fu=hapusmove&met=dellbaloon&id='.$metklaim['id'].'"><img src="image/deleted.png" border="0" width="15"></a>';
    } else {
        $hapusmovement = '';
    }

    echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		<td align="center">'.$hapusmovement.'</td>
		<td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
		<td align="center">'.$metklaim['spaj'].'</td>
		<td align="center">'.$metklaim['id_dn'].'</td>
		<td><a href="ajk_klaim.php?fu=resbaloonmember&id='.$metklaim['id'].'">'.$metklaim['nama'].'</a></td>
		<td align="center">'.$metklaim['tgl_lahir'].'</td>
		<td align="center">'.$metklaim['kredit_tgl'].'</td>
		<td align="right">'.duit($metklaim['kredit_jumlah']).'</td>
		<td align="center">'.$metklaim['kredit_tenor'].'</td>
		<td align="right">'.duit($metklaim['premi']).'</td>
		<td align="center">'._convertDate($tglklaim2).'</td>
		<td align="right">'.duit($metklaim['totalpremi']).'</td>
	<!--<td align="center">'.$confirm.'</td>-->
	</tr>';
}
echo '<tr><td colspan="22">';
echo createPageNavigations($file = 'ajk_klaim.php?fu=resbaloon', $total = $totalRows, $psDeh = 10, $anchor = '', $perPage = 25);
echo '<b>Total Data Peserta: <u>' . $totalRows . '</u></b></td></tr>';
echo '</table>';
    ;
    break;

    case "resbaloonmember":
echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
		<tr><th colspan="8" width="100%" align="left" colspan="2">Modul Klaim - Restruktur Baloon Member</font></th><th><a href="ajk_klaim.php?fu=resbaloon"><img src="image/Backward-64.png" width="20"></a></th></tr>
		</table><br />';
if ($_REQUEST['ope']="rbaloon") {
    $deb_baru = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id="'.$_REQUEST['id'].'"'));
    if ($deb_baru['cabang']=="") {
        $cabangcn 	=$deb_baru['cabang_lama'];
    } else {
        $cabangcn 	=$deb_baru['cabang'];
    }
    if ($deb_baru['regional']=="") {
        $regionalcn =$deb_baru['regional_lama'];
    } else {
        $regionalcn =$deb_baru['regional'];
    }

    //script pembentukan nomor dn//
    $dn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn ORDER BY id DESC'));
    $metid = explode(" ", $dn['input_time']);
    $metidthn = explode("-", $metid[0]);
    if ($metidthn[0] < $futgliddn) {
        $metauto = 1;
    } else {
        $metauto = $dn['id_dn'] + 1;
    }
    $idnya = 100000000 + $metauto;
    $iddn = substr($idnya, 1);
    $dntgl = explode("-", $futgldn);
    $dnthn = substr($dntgl[0], 2);
    $dn_kode = 'AJKDN-'.$dnthn.'-'.$dntgl[1].'-'.$iddn;
    //script pembentukan nomor dn//
    $idp2 = 100000000 + $deb_baru['id'];
    $id_peserta = substr($idp2, 1);

    if (isset($_POST['submit']) && ($_POST['idl'])) {
        $metdnbaru = $database->doQuery('INSERT INTO fu_ajk_dn SET id_cost="'.$deb_baru['id_cost'].'",
												   id_dn="'.$metauto.'",
												   id_nopol="'.$deb_baru['id_polis'].'",
												   id_regional="'.$deb_baru['regional'].'",
												   id_area="'.$deb_baru['area'].'",
												   id_cabang="'.$deb_baru['cabang'].'",
												   dn_kode="'.$dn_kode.'",
												   validasi_uw="ya",
												   dn_status="unpaid",
												   totalpremi="'.$deb_baru['premi'].'",
												   tgl_createdn="'.$futgldn.'",
												   namafile="'.$deb_baru['namafile'].'",
												   input_by="'.$_SESSION['nm_user'].'",
												   input_time="'.$futgl.'"');
        echo '<br /><br />';
        $updatednbaru = $database->doQuery('UPDATE fu_ajk_peserta SET id_dn="'.$dn_kode.'" WHERE id="'.$_REQUEST['id'].'"');


        if (isset($_POST['submit']) && ($_POST['idl'])) {
            for ($i=0; $i<count($_POST['idl']);$i++) {
                $deb_lama = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id="'.$_POST['idl'][$i].'"'));
                //script pembentukan nomor cn//
                $cn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cn ORDER BY id DESC'));
                $metidcn = explode(" ", $cn['input_date']);
                $metidthncn = explode("-", $metidcn[0]);
                if ($metidthncn[0] < $futgliddn) {
                    $metautocn = 1;
                } else {
                    $metautocn = $cn['idC'] + 1;
                }

                $idcnnya = 100000000 + $metautocn;
                $idcn = substr($idcnnya, 1);
                $cntgl = explode("-", $futgldn);
                $cnthn = substr($cntgl[0], 2);
                $cn_kode = 'AJKCN-'.$cnthn.'-'.$cntgl[1].'-'.$idcn;

                //total premi cn
                $awal = explode("/", $deb_lama['kredit_tgl']);
                $hari = $awal[0];
                $bulan = $awal[1];
                $tahun = $awal[2];
                $akhir = explode("/", $deb_baru['kredit_tgl']);
                $hari2 = $akhir[0];
                $bulan2 = $akhir[1];
                $tahun2 = $akhir[2];
                $jhari=(mktime(0, 0, 0, $bulan2, $hari2, $tahun2) - mktime(0, 0, 0, $bulan, $hari, $tahun))/86400;
                $sisahr=floor($jhari);
                $sisabulan =ceil($sisahr / 30.4375);
                $masisa = $deb_lama['kredit_tenor'] - $sisabulan;

                $hitungcn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_rasio_claim WHERE id_cost="'.$deb_lama['id_cost'].'" AND id_polis="'.$deb_lama['id_polis'].'"'));
                //$jumlahnya = (($deb_lama['kredit_tenor'] - $metbulan) / $deb_lama['kredit_tenor']) * $hitungcn['topup'] * $deb_lama['premi'];
                $jumlahnya = (($masisa / $deb_lama['kredit_tenor']) * $hitungcn['restruktur']) * $deb_lama['premi'];

                //total premi cn
                $metcnlama = $database->doQuery('INSERT INTO fu_ajk_cn SET id_cost="'.$deb_lama['id_cost'].'",
												   idC="'.$metautocn.'",
												   id_cn="'.$cn_kode.'",
												   id_dn="'.$dn_kode.'",
												   id_nopol="'.$deb_lama['id_polis'].'",
												   id_peserta="'.$id_peserta.'",
												   id_regional="'.$regionalcn.'",
												   id_cabang="'.$cabangcn.'",
												   premi="'.$deb_lama['premi'].'",
												   total_claim="'.$jumlahnya.'",
												   tgl_claim="'.$deb_baru['kredit_tgl'].'",
												   type_claim="'.$deb_baru['status_peserta'].'",
												   tgl_createcn="'.$futgldn.'",
												   tgl_byr_claim="",
												   confirm_claim="Approve(paid)",
												   input_by="'.$_SESSION['nm_user'].'",
												   input_date="'.$futgl.'"');
                echo '<br /><br />';
                $updatecnlama = $database->doQuery('UPDATE fu_ajk_peserta SET id_klaim="'.$cn_kode.'", status_aktif="Lapse" WHERE id="'.$_POST['idl'][$i].'"');
                $sendmail = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id="'.$deb_lama['id'].'"'));			//DATA CN DARI PESERTA LAMA
        $exwilayah = explode(" ", $sendmail['regional']);	//PECAH WILAYAH PENAMAAN UNTUK EMIAL
        $sendemailcekcn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cn WHERE id_cn="'.$sendmail['id_klaim'].'"'));		//MENCOCOKAN TABLE CN DGN PESERTA LAMA

        $metmail = explode("- ", $sendmail['input_by']);

                $fumail = mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$metmail[0].'" AND id_cost="'.$sendmail['id_cost'].'"'));
                $Rmoremail = $database->doQuery('SELECT * FROM pengguna WHERE wilayah LIKE "%'.$exwilayah[0].'%" AND id_cost="'.$sendmail['id_cost'].'"');
                while ($metmormail = mysql_fetch_array($Rmoremail)) {
                    $Rmoremailer .= $metmormail['email'].', ';
                }
                // $to = $Rmoremailer.''.$fumail['email']." sumiyanto@relife.co.id, arief.kurniawan@relife.co.id";
                $subject = 'AJKOnline - Data Movement telah di buat oleh '.$q['nm_lengkap'].'';
                $message = '<html><head><title>CN CREATE</title></head><body>
			<table border="0" width="100%">
			<tr><td colspan="7">To '.$metmail[0].'</td></tr>
			<tr><td colspan="7"><br />Telah di buat Data CN oleh : <b>'.$q['nm_lengkap'].' </b> pada tanggal '._convertDate($futgldn).'</td></tr>
			<tr><th width="5%" align="center">SPAJ</th>
				<th align="center">Debitur</th>
				<th width="10%" align="center">Tanggal Lahir</th>
				<th width="15%" align="center">Nomor CN</th>
				<th width="10%" align="center">Refund Premi</th>
				<th width="10%" align="center">Movement</th>
				<th width="15%" align="center">Cabang</th>
				<th width="15%" align="center">Regional</th>
				</tr>
			<tr><td align="center">'.$sendemailcekcn['spaj'].'</td>
				<td>'.$sendmail['nama'].'</td>
				<td align="center">'.$sendmail['tgl_lahir'].'</td>
				<td>'.$sendemailcekcn['id_cn'].'</td>
				<td align="center">'.duit($sendemailcekcn['total_claim']).'</td>
				<td align="center">'.$sendemailcekcn['type_claim'].'</td>
				<td align="center">'.$sendemailcekcn['id_cabang'].'</td>
				<td align="center">'.$sendemailcekcn['id_regional'].'</td>
			</tr>
			</table>
		</body>
		</html>';
                $headers  = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                $headers .= 'From: '.$q['email'].'' . "\r\n";
                // $headers .= 'Cc:  relife-ajk@relife.co.id' . "\r\n";
                mail($to, $subject, $message, $headers);
            }
        }
    } else {
        echo '<center><b></font>Silahkan Ceklist Nama Peserta Yang Akan Di Buatkan Data CN</b></center>';
    }
}

$met = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id="'.$_REQUEST['id'].'"'));
if ($met['id_dn']=="") {
    $createdn = '<input type="submit" name="submit" value="Submit" />';
} else {
    $createdn = '';
}
echo '<table border="0" width="100%" cellpadding="5" cellspacing="1" bgcolor="#bde0e6">
		<tr><th rowspan="2" width="5%">No.SPAJ</td>
			<th rowspan="2" width="10%">ID CN</td>
			<th rowspan="2" width="10%">ID DN</td>
			<th rowspan="2">Name</td>
			<th rowspan="2" width="7%">DOB</td>
			<th colspan="3"width="15%">Credit</td>
			<th rowspan="2" width="7%">Premi</td>
			<th rowspan="2" width="7%">Date Claim</td>
			<th rowspan="2" width="7%">Jumlah</td>
			<th rowspan="2" width="10%">Cabang</td>
			<th rowspan="2" width="7%">Regional</td>
			<th rowspan="2" width="5%">Option</td>
		</tr>
	<tr><th>Date</th><th>Premium</th><th>Tenor</th></tr>
	<form method="post" action="ajk_klaim.php?fu=resbaloonmember&ope=rbaloon&id='.$_REQUEST['id'].'">';
echo '<tr><td align="center">'.$met['spaj'].'</td>
				<td align="center">'.$met['id_klaim'].'</td>
				<td align="center">'.$met['id_dn'].'</td>
				<td>'.$met['nama'].'</td>
				<td align="center">'.$met['tgl_lahir'].'</td>
				<td align="center">'.$met['kredit_tgl'].'</td>
				<td align="right">'.duit($met['kredit_jumlah']).'</td>
				<td align="center">'.$met['kredit_tenor'].'</td>
				<td align="right">'.duit($met['premi']).'</td>
				<td align="center">'.$met['kredit_akhir'].'</td>
				<td align="right">'.duit($met['totalpremi']).'</td>
				<td align="center">'.$met['cabang'].'</td>
				<td align="center">'.$met['regional'].'</td>
				<td align="center">'.$createdn.'</td>
			</tr>';
$mklaim = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE nama="'.$met['nama'].'" AND tgl_lahir="'.$met['tgl_lahir'].'" AND id_dn !="" AND id_klaim="" AND status_aktif="aktif" AND del IS NULL');
while ($metklaim = mysql_fetch_array($mklaim)) {
    if ($mklaim['id_klaim']=="") {
        $cekdatacn = '<input type="checkbox" name="idl[]" id="checkbox" value="'.$metklaim['id'].'">';
    } else {
        $cekdatacn ='';
    }
    echo '<tr bgcolor="#fff"><td align="center">'.$metklaim['spaj'].'</td>
				<td align="center">'.$metklaim['id_klaim'].'</td>
				<td align="center">'.$metklaim['id_dn'].'</td>
				<td>'.$metklaim['nama'].'</td>
				<td align="center">'.$metklaim['tgl_lahir'].'</td>
				<td align="center">'.$metklaim['kredit_tgl'].'</td>
				<td align="right">'.duit($metklaim['kredit_jumlah']).'</td>
				<td align="center">'.$metklaim['kredit_tenor'].'</td>
				<td align="right">'.duit($metklaim['premi']).'</td>
				<td align="center">'.$metklaim['kredit_tgl'].'</td>
				<td align="right">'.duit($metklaim['totalpremi']).'</td>
				<td align="center">'.$metklaim['cabang'].'</td>
				<td align="center">'.$metklaim['regional'].'</td>
				<td align="center">'.$cekdatacn.'</td>
				</tr>';
}
        echo '</table>';
    ;
    break;

    case "movementopup":
echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
		<tr><th colspan="8" width="100%" align="left" colspan="2">Movement Top Up<font size="2">'.$topup['nama'].'</font></th><th><a href="ajk_klaim.php?fu=topup"><img src="image/Backward-64.png" width="20"></a></th></tr>
		</table><br />';
if ($_REQUEST['ope']=="topup") {
    $deb_baru = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id="'.$_REQUEST['id'].'"'));
    if ($deb_baru['cabang']=="") {
        $cabangcn 	=$deb_baru['cabang_lama'];
    } else {
        $cabangcn 	=$deb_baru['cabang'];
    }
    if ($deb_baru['regional']=="") {
        $regionalcn =$deb_baru['regional_lama'];
    } else {
        $regionalcn =$deb_baru['regional'];
    }

    //script pembentukan nomor dn//
    $dn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn ORDER BY id DESC'));
    $metid = explode(" ", $dn['input_time']);
    $metidthn = explode("-", $metid[0]);
    if ($metidthn[0] < $futgliddn) {
        $metauto = 1;
    } else {
        $metauto = $dn['id_dn'] + 1;
    }
    $idnya = 100000000 + $metauto;
    $iddn = substr($idnya, 1);
    $dntgl = explode("-", $futgldn);
    $dnthn = substr($dntgl[0], 2);
    $dn_kode = 'AJKDN-'.$dnthn.'-'.$dntgl[1].'-'.$iddn;
    //script pembentukan nomor dn//
    $idp2 = 100000000 + $deb_baru['id'];
    $id_peserta = substr($idp2, 1);

    if (isset($_POST['submit']) && ($_POST['idl'])) {
        $metdnbaru = $database->doQuery('INSERT INTO fu_ajk_dn SET id_cost="'.$deb_baru['id_cost'].'",
												   id_dn="'.$metauto.'",
												   id_nopol="'.$deb_baru['id_polis'].'",
												   id_regional="'.$deb_baru['regional'].'",
												   id_area="'.$deb_baru['area'].'",
												   id_cabang="'.$deb_baru['cabang'].'",
												   dn_kode="'.$dn_kode.'",
												   validasi_uw="ya",
												   dn_status="unpaid",
												   totalpremi="'.$deb_baru['premi'].'",
												   tgl_createdn="'.$futgldn.'",
												   namafile="'.$deb_baru['namafile'].'",
												   input_by="'.$_SESSION['nm_user'].'",
												   input_time="'.$futgl.'"');
        echo '<br /><br />';
        $updatednbaru = $database->doQuery('UPDATE fu_ajk_peserta SET id_dn="'.$dn_kode.'" WHERE id="'.$_REQUEST['id'].'"');
        for ($i=0; $i<count($_POST['idl']);$i++) {
            $deb_lama = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id="'.$_POST['idl'][$i].'"'));

            //script pembentukan nomor cn//
            $cn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cn ORDER BY id DESC'));
            $metidcn = explode(" ", $cn['input_date']);
            $metidthncn = explode("-", $metidcn[0]);
            if ($metidthncn[0] < $futgliddn) {
                $metautocn = 1;
            } else {
                $metautocn = $cn['idC'] + 1;
            }

            $idcnnya = 100000000 + $metautocn;
            $idcn = substr($idcnnya, 1);
            $cntgl = explode("-", $futgldn);
            $cnthn = substr($cntgl[0], 2);
            $cn_kode = 'AJKCN-'.$cnthn.'-'.$cntgl[1].'-'.$idcn;

            //total premi cn
            $awal = explode("/", $deb_lama['kredit_tgl']);
            $hari = $awal[0];
            $bulan = $awal[1];
            $tahun = $awal[2];
            $akhir = explode("/", $deb_baru['kredit_tgl']);
            $hari2 = $akhir[0];
            $bulan2 = $akhir[1];
            $tahun2 = $akhir[2];
            $jhari=(mktime(0, 0, 0, $bulan2, $hari2, $tahun2) - mktime(0, 0, 0, $bulan, $hari, $tahun))/86400;
            $sisahr=floor($jhari);
            $sisabulan =ceil($sisahr / 30.4375);
            $masisa = $deb_lama['kredit_tenor'] - $sisabulan;

            $hitungcn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_rasio_claim WHERE id_cost="'.$deb_lama['id_cost'].'" AND id_polis="'.$deb_lama['id_polis'].'"'));
            //$jumlahnya = (($deb_lama['kredit_tenor'] - $metbulan) / $deb_lama['kredit_tenor']) * $hitungcn['topup'] * $deb_lama['premi'];
            $jumlahnya = (($masisa / $deb_lama['kredit_tenor']) * $hitungcn['topup']) * $deb_lama['premi'];

            //total premi cn
            //echo '<br /><br />';
            $metcnlama = $database->doQuery('INSERT INTO fu_ajk_cn SET id_cost="'.$deb_lama['id_cost'].'",
												   idC="'.$metautocn.'",
												   id_cn="'.$cn_kode.'",
												   id_dn="'.$dn_kode.'",
												   id_nopol="'.$deb_lama['id_polis'].'",
												   id_peserta="'.$id_peserta.'",
												   id_regional="'.$regionalcn.'",
												   id_cabang="'.$cabangcn.'",
												   premi="'.$deb_lama['premi'].'",
												   total_claim="'.$jumlahnya.'",
												   tgl_claim="'.$deb_baru['kredit_tgl'].'",
												   type_claim="'.$deb_baru['status_peserta'].'",
												   tgl_createcn="'.$futgldn.'",
												   tgl_byr_claim="",
												   confirm_claim="Approve(paid)",
												   input_by="'.$_SESSION['nm_user'].'",
												   input_date="'.$futgl.'"');
            echo '<br /><br />';
            $updatecnlama = $database->doQuery('UPDATE fu_ajk_peserta SET id_klaim="'.$cn_kode.'", status_aktif="Lapse" WHERE id="'.$_POST['idl'][$i].'"');

            $sendmail = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id="'.$deb_lama['id'].'"'));			//DATA CN DARI PESERTA LAMA
    $exwilayah = explode(" ", $sendmail['regional']);	//PECAH WILAYAH PENAMAAN UNTUK EMIAL
    $sendemailcekcn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cn WHERE id_cn="'.$sendmail['id_klaim'].'"'));		//MENCOCOKAN TABLE CN DGN PESERTA LAMA

    $metmail = explode("- ", $sendmail['input_by']);

            $fumail = mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$metmail[0].'" AND id_cost="'.$sendmail['id_cost'].'"'));
            $Rmoremail = $database->doQuery('SELECT * FROM pengguna WHERE wilayah LIKE "%'.$exwilayah[0].'%" AND id_cost="'.$sendmail['id_cost'].'"');
            while ($metmormail = mysql_fetch_array($Rmoremail)) {
                $Rmoremailer .= $metmormail['email'].', ';
            }
            // $to = $Rmoremailer.''.$fumail['email']." sumiyanto@relife.co.id, arief.kurniawan@relife.co.id";
            $subject = 'AJKOnline - Data Movement telah di buat oleh '.$q['nm_lengkap'].'';
            $message = '<html><head><title>CN CREATE</title></head><body>
				<table border="0" width="100%">
				<tr><td colspan="7">To '.$metmail[0].'</td></tr>
				<tr><td colspan="7"><br />Telah di buat Data CN oleh : <b>'.$q['nm_lengkap'].' </b> pada tanggal '._convertDate($futgldn).'</td></tr>
				<tr><th width="5%" align="center">SPAJ</th>
					<th align="center">Debitur</th>
					<th width="10%" align="center">Tanggal Lahir</th>
					<th width="15%" align="center">Nomor CN</th>
					<th width="10%" align="center">Refund Premi</th>
					<th width="10%" align="center">Movement</th>
					<th width="15%" align="center">Cabang</th>
					<th width="15%" align="center">Regional</th>
					</tr>
				<tr><td align="center">'.$sendemailcekcn['spaj'].'</td>
					<td>'.$sendmail['nama'].'</td>
					<td align="center">'.$sendmail['tgl_lahir'].'</td>
					<td>'.$sendemailcekcn['id_cn'].'</td>
					<td align="center">'.duit($sendemailcekcn['total_claim']).'</td>
					<td align="center">'.$sendemailcekcn['type_claim'].'</td>
					<td align="center">'.$sendemailcekcn['id_cabang'].'</td>
					<td align="center">'.$sendemailcekcn['id_regional'].'</td>
				</tr>
				</table>
			</body>
			</html>';
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $headers .= 'From: '.$q['email'].'' . "\r\n";
            // $headers .= 'Cc:  relife-ajk@relife.co.id' . "\r\n";
            mail($to, $subject, $message, $headers);
        }
    } else {
        echo '<center><b></font>Silahkan Ceklist Nama Peserta Yang Akan Di Buatkan Data CN</b></center>';
    }
}
echo '<table border="0" width="100%" cellpadding="5" cellspacing="1">
	<tr><th rowspan="2" width="3%">No</td>
	<th rowspan="2" width="5%">No.SPAJ</td>
	<th rowspan="2" width="15%">ID DN</td>
	<th rowspan="2">Name</td>
	<th rowspan="2" width="5%">DOB</td>
	<th colspan="3"width="10%">Credit</td>
	<th rowspan="2" width="5%">Premi</td>
	<th rowspan="2" width="7%">Jumlah</td>
	<th rowspan="2" width="3%">opt</td>
	</tr>
	<tr><th>Date</th><th>Premium</th><th>Tenor</th></tr>
	<form method="post" action="ajk_klaim.php?fu=movementopup&ope=topup&id='.$_REQUEST['id'].'">';
$topup = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id="'.$_REQUEST['id'].'"'));

if ($topup['id_dn']=="") {
    $metsubmit = '<input type="submit" name="submit" value="Submit" />';
} else {
    $metsubmit ='DN create';
}
echo '<tr bgcolor="#CDE"><td align="center">'.++$no.'</td>
		<td align="center">'.$topup['spaj'].'</td>
		<td align="center">'.$topup['id_dn'].'</td>
		<td><a href="ajk_klaim.php?fu=movementopup&id='.$topup['id'].'">'.$topup['nama'].'</a></td>
		<td>'.$topup['tgl_lahir'].'</td>
		<td>'.$topup['kredit_tgl'].'</td>
		<td align="right">'.duit($topup['kredit_jumlah']).'</td>
		<td>'.$topup['kredit_tenor'].'</td>
		<td align="right">'.duit($topup['premi']).'</td>
		<td align="right">'.duit($topup['totalpremi']).'</td>
		<td align="center">'.$metsubmit.'</td></tr></td>
		</tr>';
$topup2 = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE nama="'.$topup['nama'].'" AND tgl_lahir="'.$topup['tgl_lahir'].'" AND id_dn!="" AND id_klaim="" AND status_aktif="aktif" AND del IS NULL ORDER BY id DESC');
while ($cektopup = mysql_fetch_array($topup2)) {
    echo '<tr><td align="center">'.++$no.'</td>
		<td align="center">'.$cektopup['spaj'].'</td>
		<td align="center">'.$cektopup['id_dn'].'</td>
		<td><a href="ajk_klaim.php?fu=movementopup&id='.$cektopup['id'].'">'.$cektopup['nama'].'</a></td>
		<td>'.$cektopup['tgl_lahir'].'</td>
		<td>'.$cektopup['kredit_tgl'].'</td>
		<td align="right">'.duit($cektopup['kredit_jumlah']).'</td>
		<td>'.$cektopup['kredit_tenor'].'</td>
		<td align="right">'.duit($cektopup['premi']).'</td>
		<td align="right">'.duit($cektopup['totalpremi']).'</td>
<!--	<td align="center"><a href="ajk_klaim.php?fu=movementopup&ope=topup&id='.$_REQUEST['id'].'&idl='.$cektopup['id'].'" onClick="if(confirm(\'Create data DN untuk peserta baru dan CN untuk peserta lama a/n peserta '.$topup['nama'].'?\')){return true;}{return false;}"><img src="image/ya.png"></a></td> -->
		<td align="center"><input type="checkbox" name="idl[]" id="checkbox" value="'.$cektopup['id'].'"></td>
		</tr>';
}
echo '</form></table>';
    ;
    break;

    case "topup":
echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
		<tr><th colspan="8" width="100%" align="left" colspan="2">Modul Klaim Top Up</font></th></tr>
		</table><br />
	<fieldset>
	<legend align="center">S e a r c h</legend>
	<table border="0" width="50%" cellpadding="3" cellspacing="1">
	<form method="post" action="ajk_klaim.php?fu=topup">
	<tr><td width="10%">Nama</td><td>: <input type="text" name="cnama" value="'.$_REQUEST['cnama'].'"> &nbsp; <input type="submit" name="button" value="Search" class="button"></td></tr>
	</form>
	</table></fieldset>
	<table border="0" width="100%" cellpadding="5" cellspacing="1" bgcolor="#bde0e6">
	<tr><th rowspan="2" width="1%">Opt</td>
	<th rowspan="2" width="3%">No</td>
	<th rowspan="2" width="5%">No.SPAJ</td>
	<th rowspan="2" width="10%">ID DN</td>
	<th rowspan="2">Name</td>
	<th rowspan="2" width="5%">DOB</td>
	<th colspan="3"width="10%">Credit</td>
	<th rowspan="2" width="5%">Premi</td>
	<th rowspan="2" width="10%">Date Claim</td>
	<th rowspan="2" width="7%">Jumlah</td>
	</tr>
	<tr><th>Date</th><th>Premium</th><th>Tenor</th></tr>';

if ($_REQUEST['x']) {
    $m = ($_REQUEST['x']-1) * 25;
} else {
    $m = 0;
}

if ($_REQUEST['cnama']) {
    $dua = 'AND nama LIKE "%' . $_REQUEST['cnama'] . '%"';
}

$mklaim = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE status_peserta="Top Up" AND status_aktif="aktif" '.$dua.' AND del IS NULL ORDER BY id_dn ASC, ID DESC LIMIT ' . $m . ' , 25');
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_peserta WHERE id != "" AND status_peserta="Top Up" AND status_aktif="aktif" AND del IS NULL '.$dua.''));
$totalRows = $totalRows[0];
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while ($metklaim = mysql_fetch_array($mklaim)) {
    if (($no % 2) == 1) {
        $objlass = 'tbl-odd';
    } else {
        $objlass = 'tbl-even';
    }
    $tglklaim = explode(" ", $metklaim['input_time']);
    $tglklaim2 = $tglklaim[0];

    if ($metklaim['id_dn']=="") {
        $confirm = '<a href="ajk_klaim.php?fu=topup&ope=y&id='.$metklaim['id'].'">Ya</a>';
    } else {
        $confirm = '<a href="ajk_klaim.php?fu=topup&ope=t&id='.$metklaim['id'].'">Tidak</a>';
    }

    $cekpeserta = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE nama="'.$metklaim['nama'].'" AND tgl_lahir="'.$metklaim['tgl_lahir'].'"'));

    if ($metklaim['id_dn']=="") {
        $hapusmovement = '<a href="ajk_klaim.php?fu=hapusmove&met=delltopup&id='.$metklaim['id'].'"><img src="image/deleted.png" border="0" width="15"></a>';
    } else {
        $hapusmovement = '';
    }

    echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		<td align="center">'.$hapusmovement.'</td>
		<td align="center">'.++$no.'</td>
		<td align="center">'.$metklaim['spaj'].'</td>
		<td align="center">'.$metklaim['id_dn'].'</td>
		<td><a href="ajk_klaim.php?fu=movementopup&id='.$metklaim['id'].'">'.$metklaim['nama'].'</a></td>
		<td>'.$metklaim['tgl_lahir'].'</td>
		<td>'.$metklaim['kredit_tgl'].'</td>
		<td>'.duit($metklaim['kredit_jumlah']).'</td>
		<td>'.$metklaim['kredit_tenor'].'</td>
		<td>'.duit($metklaim['premi']).'</td>
		<td align="center">'._convertDate($tglklaim2).'</td>
		<td align="right">'.duit($metklaim['totalpremi']).'</td>
	</tr>';
}
        echo '<tr><td colspan="22">';
        echo createPageNavigations($file = 'ajk_klaim.php?fu=topup', $total = $totalRows, $psDeh = 10, $anchor = '', $perPage = 25);
        echo '<b>Total Data Peserta Top Up: <u>' . $totalRows . '</u></b></td></tr>';
        echo '</table>';
        ;
        break;

    case "confclaim":
$conf = $database->doQuery('UPDATE fu_ajk_klaim SET confirm_klaim="Ya" WHERE id="'.$_REQUEST['id'].'"');
$confpes = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_klaim="'.$conf['id_peserta'].'"'));
$confcn = $database->doQuery('INSERT INTO fu_ajk_cn SET id_cost="'.$confpes['id_cost'].'",
														id_nopol="'.$confpes['id_polis'].'",
														id_peserta="'.$_REQUEST['id'].'",
														id_dn="'.$confpes['id_dn'].'",
														id_cabang="'.$confpes['cabang'].'",
														input_by="'.$_SESSION['nm_user'].'",
														input_date="'.$futgl.'"');
header("location:ajk_klaim.php");
    ;
    break;

//REFUND MODEL LAMA//
/*
    case "refund":
echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th colspan="8" width="100%" align="left" colspan="2">Modul Klaim - Refund</font></th></tr>
</table><br />
<table border="0" width="100%" cellpadding="5" cellspacing="1" bgcolor="#bde0e6">
<tr><th rowspan="2" width="3%">No</td>
<th rowspan="2" width="5%">No.SPAJ</td>
<th rowspan="2" width="12%">ID DN</td>
<th rowspan="2" width="12%">ID CN</td>
<th rowspan="2">Name</td>
<th rowspan="2" width="5%">DOB</td>
<th colspan="3">Credit Note</td>
<th rowspan="2" width="8%">Premi</td>
<th rowspan="2" width="5%">Date Claim</td>
<th rowspan="2" width="8%">Jumlah</td>
</tr>
<tr><th width="5%">Date</th><th width="8%">Premium</th><th width="3%">Tenor</th></tr>';
if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}
else {	$m = 0;		}

$mklaim = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE status_peserta="Refund" ORDER BY ID DESC LIMIT ' . $m . ' , 25');
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_peserta WHERE id != "" AND status_peserta="Refund"'));
$totalRows = $totalRows[0];
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;

while ($metklaim = mysql_fetch_array($mklaim)) {
if (($no % 2) == 1)	$objlass = 'tbl-odd'; else				$objlass = 'tbl-even';
$tglklaim = explode(" ", $metklaim['input_time']);
$tglklaim2 = $tglklaim[0];

if ($metklaim['id_dn']=="") {	$confirm = '<a href="ajk_klaim.php?fu=restruktur&ope=y&id='.$metklaim['id'].'">Ya</a>';	}
else	{	$confirm = '<a href="ajk_klaim.php?fu=restruktur&ope=t&id='.$metklaim['id'].'">Tidak</a>';	}
$cekpeserta = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE nama="'.$metklaim['nama'].'" AND tgl_lahir="'.$metklaim['tgl_lahir'].'"'));

echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
        <td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
        <td align="center">'.$metklaim['spaj'].'</td>
        <td align="center">'.$metklaim['id_dn'].'</td>
        <td align="center">'.$metklaim['id_klaim'].'</td>
        <td><a href="ajk_klaim.php?fu=IDrefund&idRef='.$metklaim['id'].'">'.$metklaim['nama'].'</a></td>
        <td>'.$metklaim['tgl_lahir'].'</td>
        <td>'.$metklaim['kredit_tgl'].'</td>
        <td align="right">'.duit($metklaim['kredit_jumlah']).'</td>
        <td align="center">'.$metklaim['kredit_tenor'].'</td>
        <td align="right">'.duit($metklaim['premi']).'</td>
        <td align="center">'._convertDate($tglklaim2).'</td>
        <td align="right">'.duit($metklaim['totalpremi']).'</td>
</tr>';
}
echo '<tr><td colspan="22">';
echo createPageNavigations($file = 'ajk_klaim.php?fu=refund', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
echo '<b>Total Data Peserta Refund : <u>' . $totalRows . '</u></b></td></tr>';
echo '</table>';
        ;
        break;
*/
//REFUND MODEL LAMA//

    case "refund":
$met_jum_refund = mysql_fetch_array($database->doQuery('SELECT COUNT(id_peserta) AS jData FROM fu_ajk_cn_tempf WHERE type_claim="Refund" AND confirm_claim="Approve" AND validasi_cn_uw="ya" AND del IS NULL'));
if ($met_jum_refund['jData'] <= 0) {
    $setRefund = '';
} else {
    $setRefund = '<th width="5%" colspan="2"><a title="Buat data CN Refund" href="ajk_klaim.php?fu=setrefund"><img src="image/rmf_1.png" width="25"></a></th>';
}

echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
		<tr><th colspan="8" width="100%" align="left" colspan="2">Modul Klaim - Refund</font></th>
			'.$setRefund.'
			<!--<th width="5%" colspan="2"><a href="ajk_klaim.php?fu=newrefund"><img src="image/new.png" width="25"></a></th>-->
		</tr>
	  </table><br />';
echo '<fieldset>
	<legend>Searching</legend>
	<form method="post" action="">
	<table border="0" width="100%" cellpadding="1" cellspacing="0" align="center">
	<tr><td width="5%">Nomor DN</td><td width="20%">: <input type="text" name="nodn" value="'.$_REQUEST['nodn'].'"></td></tr>
	<tr><td width="5%">Nomor CN</td><td width="20%">: <input type="text" name="nocn" value="'.$_REQUEST['nocn'].'"></td></tr>
	<tr><td width="5%">Nama</td><td>: <input type="text" name="rnama" value="'.$_REQUEST['rnama'].'"></td></tr>
	<tr><td align="center"><input type="submit" name="oppe" value="Searching" class="button"></td></tr>
	</table>
	</form></fieldset>';
if ($_REQUEST['nodn']) {
    $satu = 'AND fu_ajk_cn.id_dn LIKE "%' . $_REQUEST['nodn'] . '%"';
}
if ($_REQUEST['nocn']) {
    $dua = 'AND fu_ajk_cn.id_cn LIKE "%' . $_REQUEST['nocn'] . '%"';
}
if ($_REQUEST['rnama']) {
    $tiga = 'AND fu_ajk_peserta.nama LIKE "%' . $_REQUEST['rnama'] . '%"';
}

if ($_REQUEST['rdob']) {
    $empat = 'AND tgl_lahir LIKE "%' . $_REQUEST['rdob'] . '%"';
    $dobnya = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_klaim !="" '.$empat.' '));
    $erdob = 'AND tgl_lahir LIKE "%' . $dobnya['tgl_lahir'] . '%"';
}

echo '<table border="0" width="100%" cellpadding="5" cellspacing="1" bgcolor="#bde0e6">
	<tr><th width="3%">No</td>
	<th width="5%">Produk</td>
	<th width="5%">Mitra</td>
	<th width="5%">ID Peserta</td>
	<th width="8%">Debitnote</td>
	<th width="8%">Tgl Creditnote</td>
	<th width="8%">Creditnote</td>
	<th>Name</td>
	<th width="5%">DOB</td>
	<th width="5%">Tgl Kredit</td>
	<th width="1%">Tenor</td>
	<th width="5%">Akhir Kredit</td>
	<th width="5%">Tgl Refund</td>
	<th width="3%">MA - J</td>
	<th width="3%">MA - S</td>
	<th width="7%">Premi Bank</td>
	<th width="5%">Refund Bank</td>
	<th width="5%">Nett Refund Bank</td>
	<th width="5%">Nett Refund Asuransi</td>
	<th width="5%">Status</td>
	<th width="5%">Cabang</td>
	</tr>';
if ($_REQUEST['x']) {
    $m = ($_REQUEST['x']-1) * 25;
} else {
    $m = 0;
}
//$mklaim = $database->doQuery('SELECT * FROM fu_ajk_cn WHERE type_claim="Refund" AND id_cn !="" '.$satu.' '.$dua.' '.$ernama.' '.$erdob.' AND del IS NULL ORDER BY id_cn DESC, tgl_claim DESC LIMIT ' . $m . ' , 25');
$mklaim = $database->doQuery('SELECT
fu_ajk_polis.nmproduk,
fu_ajk_dn.dn_kode,
fu_ajk_cn.id,
fu_ajk_cn.id_cn,
fu_ajk_cn.id_peserta,
fu_ajk_cn.id_cabang,
fu_ajk_cn.tgl_createcn,
fu_ajk_cn.tgl_claim,
fu_ajk_cn.tgl_byr_claim,
fu_ajk_cn.type_claim,
fu_ajk_cn.premi,
fu_ajk_cn.total_claim,
fu_ajk_cn.total_claim_as,
fu_ajk_cn.confirm_claim,
fu_ajk_cn.fname,
IF(fu_ajk_peserta.type_data="SPK",fu_ajk_peserta.kredit_tenor * 12, fu_ajk_peserta.kredit_tenor) AS kredit_tenor,
fu_ajk_peserta.nama_mitra,
fu_ajk_peserta.nama,
fu_ajk_peserta.tgl_lahir,
fu_ajk_peserta.usia,
fu_ajk_peserta.kredit_tgl,
fu_ajk_peserta.kredit_akhir,
fu_ajk_peserta.totalpremi,
fu_ajk_grupproduk.nmproduk AS mitra
FROM fu_ajk_cn
INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id = fu_ajk_peserta.id_klaim
INNER JOIN fu_ajk_grupproduk ON fu_ajk_peserta.nama_mitra = fu_ajk_grupproduk.id
WHERE fu_ajk_cn.type_claim = "Refund" AND fu_ajk_cn.del IS NULL AND fu_ajk_cn.id_cn !="" '.$satu.' '.$dua.' '.$tiga.'
ORDER BY fu_ajk_cn.tgl_createcn DESC
LIMIT ' . $m . ' , 25');
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(fu_ajk_cn.id) FROM fu_ajk_cn
INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id = fu_ajk_peserta.id_klaim
INNER JOIN fu_ajk_grupproduk ON fu_ajk_peserta.nama_mitra = fu_ajk_grupproduk.id
WHERE fu_ajk_cn.id_cn != "" AND fu_ajk_cn.type_claim="Refund" '.$satu.' '.$dua.' '.$tiga.' AND fu_ajk_cn.del IS NULL'));
$totalRows = $totalRows[0];
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while ($metklaim = mysql_fetch_array($mklaim)) {
    //$cek = $database->doQuery('UPDATE fu_ajk_peserta SET status_peserta="Death" WHERE id_peserta="'.$metklaim['id_peserta'].'"');
    //$metpeserta = mysql_fetch_array($database->doQuery('SELECT *, IF(type_data="SPK", kredit_tenor * 12, kredit_tenor) AS kredit_tenor FROM fu_ajk_peserta WHERE id_dn="'.$metklaim['id_dn'].'" AND id_peserta="'.$metklaim['id_peserta'].'" '));
    //$metpeserta = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_dn="'.$metklaim['id_dn'].'" AND id_klaim="'.$metklaim['id'].'" AND id_peserta="'.$metklaim['id_peserta'].'" '));
    //$met_dnnya = mysql_fetch_array($database->doQuery('SELECT id, dn_kode FROM fu_ajk_dn WHERE id="'.$metklaim['id_dn'].'"'));
    //$met_produknya = mysql_fetch_array($database->doQuery('SELECT id, nmproduk FROM fu_ajk_polis WHERE id="'.$metklaim['id_nopol'].'"'));

    //MASA ASURANSI BERJALAN
    $met_Date = datediff($metklaim['tgl_claim'], $metklaim['kredit_tgl']);
    //echo $fudata['tgl_claim'].' - '.$met_peserta['kredit_tgl'].' - '.$met_Date.'<br />';
    $met_Date_ = explode(",", $met_Date);
    if ($met_Date_[0] < 0) {
        $thnbln = '';
    } else {
        $thnbln = $met_Date_[0] * 12;
    }
    $sisabulan = $met_Date_[1] + $thnbln;
    //MASA ASURANSI BERJALAN

    //MASA ASURANSI SISA
    $masisa = $metklaim['kredit_tenor'] - $sisabulan;
    //MASA ASURANSI SISA

    $er_nett = $metklaim['totalpremi'] - $metklaim['total_claim'];
    if ($metklaim['confirm_klaim']=="Processing") {
        $cekconfirm = '<img src="image/edit3.png">';
    } else {
        $cekconfirm = '<img src="image/edit1.png">';
    }

    if (($no % 2) == 1) {
        $objlass = 'tbl-odd';
    } else {
        $objlass = 'tbl-even';
    }
    echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
			<td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
			<td align="center">'.$metklaim['nmproduk'].'</td>
			<td align="center">'.$metklaim['mitra'].'</td>
			<td align="center">'.$metklaim['id_peserta'].'</td>
			<td align="center"><a href="../aajk_report.php?er=_kwipeserta&idn='.$met_dnnya['id'].'" target="_blank">'.substr($metklaim['dn_kode'], 3).'</a></td>
			<td align="center">'._convertDate($metklaim['tgl_createcn']).'</td>
			<td align="center"><a href="../aajk_report.php?er=_eRefund&idC='.$metklaim['id'].'" target="_blank">'.substr($metklaim['id_cn'], 3).'</a></td>
			<td><a href="'.$metpath_file.''.$metklaim['fname'].'" target="_blank">'.strtoupper($metklaim['nama']).'</a></td>
			<td align="center">'._convertDate($metklaim['tgl_lahir']).'</td>
			<td align="center">'._convertDate($metklaim['kredit_tgl']).'</td>
			<td align="center">'.$metklaim['kredit_tenor'].'</td>
			<td align="center">'._convertDate($metklaim['kredit_akhir']).'</td>
			<td align="center">'._convertDate($metklaim['tgl_claim']).'</td>
			<td align="center">'.$sisabulan.'</td>
			<td align="center">'.$masisa.'</td>
			<td align="right">'.duit($metklaim['totalpremi']).'</td>
			<td align="right">'.duit($metklaim['total_claim']).'</td>
			<td align="right">'.duit($er_nett).'</td>
			<td align="right">'.duit($metklaim['total_claim_as']).'</td>
			<td align="right">'.$metklaim['confirm_claim'].'</td>
			<td>'.strtoupper($metklaim['id_cabang']).'</td>
			</tr>';
}
echo '<tr><td colspan="22">';
echo createPageNavigations($file = 'ajk_klaim.php?fu=refund&nodn='.$_REQUEST['nodn'].'&nocn='.$_REQUEST['nocn'].'&rnama='.$namanya['rnama'].'', $total = $totalRows, $psDeh = 10, $anchor = '', $perPage = 25);
echo '<b>Total Data Refund: <u>' . $totalRows . '</u></b></td></tr>';
echo '</table>';
    ;
    break;

    case "setrefund":
			echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
				  		<tr><th colspan="8" width="100%" align="left" colspan="2">Modul Klaim - SET Refund</font></th><th width="5%" colspan="2"><a href="ajk_klaim.php?fu=refund"><img src="image/Backward-64.png" width="20"></a></th></tr>
				  	</table><br />';
			if ($_REQUEST['mamet']=="editrefund") {
			    if ($_REQUEST['rahmad']=="save") {
			        $r = $database->doQuery('UPDATE fu_ajk_cn_tempf SET tgl_claim="'.$_REQUEST['tglrefundnya'].'", total_claim="'.$_REQUEST['totrefundnya'].'", update_by="'.$q['nm_lengkap'].'", update_time="'.$futgl.'" WHERE id="'.$_REQUEST['id'].'"');
			        echo '<center><b>Data refund telah di edit oleh '.$q['nm_lengkap'].' pada tanggal '.$futgl.'.<br /><meta http-equiv="refresh" content="1;URL=ajk_klaim.php?fu=setrefund"></b></center>';
			    }
			    $met = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cn_tempf WHERE id="'.$_REQUEST['id'].'"'));
			    $metpeserta = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_peserta="'.$met['id_peserta'].'"'));
			    echo '<center>
				  <form method="post" action="ajk_klaim.php?fu=setrefund&mamet=editrefund&id='.$_REQUEST['id'].'">
				  <table border="0" cellpadding="1" cellspacing="1" width="50%">
				  <input type="hidden" name="id" value='.$_REQUEST['id'].'>
				  <tr><td align="right">Nama :</td><td>'.$metpeserta['nama'].'</td></tr>
			 	  <tr><td align="right">Tgl Refund :</td><td>'._convertDate($met['tgl_claim']).'</td></tr>
			 	  <tr><td align="right">Jumlah Refund :</td><td> <input type="text" name="totrefundnya" value='.$met['total_claim'].'  onkeyup="this.value=this.value.replace(/[^0-9/,]/g,\'\')"></td></tr>
				  <tr><td colspan="2" align="center"><input type="submit" name="rahmad" value="save"> &nbsp; <a href="ajk_klaim.php?fu=setrefund">Cancel</a></td></tr>
				  </form></center>';
			}

			$cat=$_GET['cat'];	if (strlen($cat) > 0 and !is_numeric($cat)) {
			    echo "Data Error";
			    exit;
			}
			echo '<fieldset><legend>Searching</legend>
					  <form method="post" action="">
					  <table border="0" width="100%" cellpadding="3" cellspacing="1" align="center">
					  <tr><td width="25%" align="right">Nama Perusahaan<font color="red">*</font></td>
					  <td width="30%">: <select id="cat" name="cat" onchange="reloadrefund(this.form)">
					  <option value="">---Select Company---</option>';
			$quer2=$database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name ASC');
			while ($noticia2 = mysql_fetch_array($quer2)) {
			    if ($noticia2['id']==$cat) {
			        echo '<option selected value="'.$noticia2['id'].'">'.$noticia2['name'].'</option><BR>';
			    } else {
			        echo  '<option value="'.$noticia2['id'].'">'.$noticia2['name'].'</option>';
			    }
			}
			echo '</select></td></tr>
				  <tr><td width="15%" align="right">Nama Produk<font color="red">*</font></td>
					  <td width="30%">: ';
			if (isset($cat) and strlen($cat) > 0) {
			    $quer=$database->doQuery('SELECT * FROM fu_ajk_polis where id_cost="'.$cat.'" AND del IS NULL ORDER BY id ASC');
			}
			echo '<select name="subcat"><option value="">---Pilih Produk---</option>';
			while ($noticia = mysql_fetch_array($quer)) {
			    echo  '<option value="'.$noticia['id'].'"'._selected($noticia["nmproduk"]. $noticia['id']).'>'.$noticia['nmproduk'].'</option>';
			}
			echo '</select></td></tr>
				<tr><td align="right">Staff Bank<font color="red">*</font></td><td>';
			/*	REVISI QUERY 18 11 2015
			$jData_User = $database->doQuery('SELECT * FROM fu_ajk_cn_tempf WHERE id_cost="'.$_REQUEST['cat'].'" AND type_claim = "Refund" AND confirm_claim = "Processing" AND del IS NULL GROUP BY id_cost, id_nopol, input_by');
			*/
			$jData_User = $database->doQuery('SELECT * FROM fu_ajk_cn_tempf WHERE id_cost="'.$_REQUEST['cat'].'" AND type_claim = "Refund" AND confirm_claim = "Approve" AND del IS NULL GROUP BY id_cost, id_nopol, input_by');
			echo ': <select name="user_input">
					<option value="">---Pilih Staff---</option>';
			while ($jData_User_ = mysql_fetch_array($jData_User)) {
			    echo '<option value="'.$jData_User_['input_by'].'"'._selected($_REQUEST['user_input'], $jData_User_['input_by']).'>'.$jData_User_['input_by'].'</option>';
			}
			echo '</td></tr>
				<tr><td colspan="2" align="center"><input type="submit" name="r" value="Searching" class="button"></td></tr>
				</table></form></fieldset>';

			if ($_REQUEST['r']!="Searching") {
			    echo '<table border="0" width="100%" cellpadding="3" cellspacing="1" bgcolor="#bde0e6">
				  <tr><th width="1%">No</th>
			  		  <th>Nama Produk</th>
			  	  	  <th width="20%">Jumlah Data Refund</th>
			  	  	  <th width="15%">Cabang</th>
			  	  	  <th width="15%">User</th>
			  	</tr>';
			    /* REVISI QUERY REFUND DATA SEBELUMNYA STATUS CN PROCESSING 18 11 2015
			    $jData = $database->doQuery('SELECT id_cn, id_dn, id_cost, id_nopol, COUNT(id_peserta) AS jData, input_by, del
			                                 FROM fu_ajk_cn_tempf
			                                 WHERE type_claim = "Refund" AND confirm_claim = "Processing" AND validasi_cn_uw="ya" AND del IS NULL
			                                 GROUP BY id_cost, id_nopol, input_by');
			    */
			    $jData = $database->doQuery('SELECT id_cn, id_dn, id_cost, id_nopol, COUNT(id_peserta) AS jData, id_cabang, input_by, del
										 FROM fu_ajk_cn_tempf
										 WHERE type_claim = "Refund" AND confirm_claim = "Approve" AND validasi_cn_uw="ya" AND del IS NULL
										 GROUP BY id_cost, id_nopol, input_by');
			    while ($jData_ = mysql_fetch_array($jData)) {
			        $met_produk = mysql_fetch_array($database->doQuery('SELECT id, nmproduk FROM fu_ajk_polis WHERE id="'.$jData_['id_nopol'].'"'));
			        if (($no % 2) == 1) {
			            $objlass = 'tbl-odd';
			        } else {
			            $objlass = 'tbl-even';
			        }
			        echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
				  	<td align="center">'.++$no.'</td>
				  	<td>'.$met_produk['nmproduk'].'</td>
				  	<td align="center">'.$jData_['jData'].' Data Refund</td>
				  	<td align="center">'.$jData_['id_cabang'].'</td>
				  	<td align="center">'.$jData_['input_by'].'</td>
				  </tr>';
			    }
			    echo '</table>';
			} else {
			    if (!$_REQUEST['cat']) {
			        $error1 .='<font color="red">Silahkan pilih nama perusahaan !.</font><br />';
			    }
			    if (!$_REQUEST['subcat']) {
			        $error2 .='<font color="red">Silahkan pilih nama produk !.</font><br />';
			    }
			    if (!$_REQUEST['user_input']) {
			        $error3 .='<font color="red">Silahkan pilih nama user !.</font>';
			    }
			    if ($error1 or $error2 or $error3) {
			        echo '<center>'.$error1 .''.$error2.''.$error3.'<meta http-equiv="refresh" content="2;URL=ajk_klaim.php?fu=setrefund"></center>';
			    } else {
			        if ($_REQUEST['cat']) {
			            $satu = 'AND id_cost = "' . $_REQUEST['cat'] . '"';
			        }
			        if ($_REQUEST['subcat']) {
			            $dua = 'AND id_nopol = "' . $_REQUEST['subcat'] . '"';
			        }
			        if ($_REQUEST['user_input']) {
			            $tiga = 'AND input_by = "' . $_REQUEST['user_input'] . '"';
			        }
			        echo '<form method="post" action="" onload ="onbeforeunload">
						  <input type="hidden" name="cn_cost" value="'.$_REQUEST['cat'].'">
						  <input type="hidden" name="cn_produk" value="'.$_REQUEST['subcat'].'">
						  <input type="hidden" name="cn_userinput" value="'.$_REQUEST['user_input'].'">
							<table border="0" width="100%" cellpadding="5" cellspacing="1" bgcolor="#bde0e6">
							<tr>
							<th width="5%">Option</th>
							<th width="1%"><input type="checkbox" id="selectall"/></th>
							<th width="3%">No</td>
							<th width="1%">ID Peserta</td>
							<th width="8%">ID DN</td>
							<th width="5%">Type Refund</td>
							<th width="6%">Asuransi</td>
							<th>Name</td>
							<th width="6%">Tgl Lahir</td>
							<th width="6%">Tgl Akad</td>
							<th width="1%">Tenor</td>
							<th width="6%">Tgl Akhir</td>
							<th width="6%">Tgl Refund</td>
							<th width="6%">Tgl Proses</td>
							<th width="5%">Total Premi</td>
							<th width="6%">Total Refund</td>
							<!--<th width="6%">Jumlah</td>-->
							<th width="8%">Regional</td>
							<th width="5%">Cabang</td>
							<th width="5%">User</td>
							<th width="5%">Dokumen</td>
							</tr>';
			        /* REVISI QUERY REFUND DATA SEBELUMNYA STATUS CN PROCESSING 18 11 2015
			        $mklaim = $database->doQuery('SELECT * FROM fu_ajk_cn_tempf WHERE id_cn="" AND confirm_claim="Processing" AND type_claim="Refund" AND validasi_cn_uw="ya" AND del IS NULL '.$satu.' '.$dua.' '.$tiga.' ORDER BY input_date DESC');
			        */
			        $mklaim = $database->doQuery('SELECT *,date(input_date)as tgl_proses FROM fu_ajk_cn_tempf WHERE id_cn="" AND confirm_claim="Approve" AND type_claim="Refund" AND validasi_cn_uw="ya" AND del IS NULL '.$satu.' '.$dua.' '.$tiga.' ORDER BY input_date DESC');
			        while ($metklaim = mysql_fetch_array($mklaim)) {
		            $metDN = mysql_fetch_array($database->doQuery('SELECT id, dn_kode FROM fu_ajk_dn WHERE id="'.$metklaim['id_dn'].'"'));
		            $metpeserta = mysql_fetch_array($database->doQuery('SELECT id, nama, tgl_lahir, kredit_tgl, kredit_akhir, kredit_tenor, id_dn, id_peserta, totalpremi FROM fu_ajk_peserta WHERE id_dn="'.$metklaim['id_dn'].'" AND id_peserta="'.$metklaim['id_peserta'].'" '));
		            $metas = mysql_fetch_array($database->doQuery('select * from fu_ajk_asuransi where id="'.$metDN['id_as'].'"'));

		            //$nettrefund = $metpeserta['totalpremi'] - $metklaim['total_claim'];
		            if (($no % 2) == 1) {
		                $objlass = 'tbl-odd';
		            } else {
		                $objlass = 'tbl-even';
		            }
		            echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
								<!--<td align="center"><a title="edit refund" href="ajk_klaim.php?fu=setrefund&mamet=editrefund&id='.$metklaim['id'].'"><img border="0" src="image/edit3.png" width="20"></a> &nbsp;-->
								<td align="center"> <a title="hapus peserta refund" href="ajk_klaim.php?fu=hapusmove&met=batalrefund&id='.$metklaim['id'].'" onClick="if(confirm(\'Anda yakin membatalkan peserta refund ini ?\')){return true;}{return false;}"><img border="0" src="image/delete.gif" width="20"></a></td> 
								<td align="center"><input type="checkbox" class="case" name="nama[]" value="'.$metklaim['id'].'"></td>
								<td align="center">'.++$no.'</td>
								<td align="center">'.$metklaim['id_peserta'].'</td>
								<td align="center">'.$metDN['dn_kode'].'</td>
								<td align="center">'.$metklaim['type_refund'].'</td>
								<td align="center">'.$metas['code'].'</td>
								<td>'.strtoupper($metpeserta['nama']).'</td>
								<td align="center">'._convertDate($metpeserta['tgl_lahir']).'</td>
								<td align="center">'._convertDate($metpeserta['kredit_tgl']).'</td>
								<td align="center">'.$metpeserta['kredit_tenor'].'</td>
								<td align="center">'._convertDate($metpeserta['kredit_akhir']).'</td>
								<td align="center"><b>'._convertDate($metklaim['tgl_claim']).'</b></td>
								<td align="center"><b>'._convertDate($metklaim['tgl_proses']).'</b></td>
								<td align="right">'.duit($metpeserta['totalpremi']).'</td>
								<td align="right"><font color="red">'.duit($metklaim['total_claim']).'</font></td>
								<!--<td align="right"><font color="blue">'.duit($nettrefund).'</font></td>-->
								<td>'.$metklaim['id_regional'].'</td>
								<td>'.$metklaim['id_cabang'].'</td>
								<td>'.$metklaim['input_by'].'</td>
								<td align="center"><a title="lihat dokumen refund" href="/ajk/ajk_file/_spak/' . $metklaim['fname'] . '" target="_blank">View</a></td>
								</tr>';
			        }
			        if ($q['status']=="" or $q['status']=="UNDERWRITING") {
		            echo '<tr><td colspan="27" align="center"><a href="" onClick="if(confirm(\'Apakah anda sudah yakin dengan semua data peserta ini ?\')){return true;}{return false;}">
							  <input type="hidden" name="fu" Value="approverefund"><input type="submit" name="Approve" Value="Approve"></a></td>
							  </tr>';
			        } else {
			          echo '';
			        }
			        echo '</form></table>';
			    }
			}
			        ;
    break;

    case "setrefundAppr":
echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
	  <tr><th colspan="8" width="100%" align="left" colspan="2">Modul Klaim - SET Refund</font></th><th width="5%" colspan="2"><a href="ajk_klaim.php?fu=refund"><img src="image/Backward-64.png" width="20"></a></th></tr>
	  </table><br />';
echo '<form method="post" action="ajk_klaim.php?fu=createcnrefund" onload ="onbeforeunload">
	<table border="0" width="100%" cellpadding="5" cellspacing="1" bgcolor="#bde0e6">
	<tr>
	<th width="4%">&nbsp; &nbsp; &nbsp; &nbsp; <input type="checkbox" id="selectall"/></th>
	<th width="3%">No</td>
	<th width="5%">ID Peserta</td>
	<th width="12%">ID DN</td>
	<th>Name</td>
	<th width="5%">DOB</td>
	<th width="5%">Tgl Kredit</td>
	<th width="3%">Tenor</td>
	<th width="5%">Akhir Kredit</td>
	<th width="5%">Premi</td>
	<th width="6%">Tgl Refund</td>
	<th width="7%">Jumlah</td>
	<th width="10%">Regional</td>
	<th width="12%">Cabang</td>
	</tr>';
if ($_REQUEST['x']) {
    $m = ($_REQUEST['x']-1) * 250;
} else {
    $m = 0;
}
$mklaim = $database->doQuery('SELECT * FROM fu_ajk_cn_tempf WHERE type_claim="Refund" AND confirm_claim="Approve(unpaid)" AND validasi_cn_uw="ya" AND del IS NULL ORDER BY tgl_claim DESC LIMIT ' . $m . ' , 250');
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_cn WHERE id != "" AND type_claim="Refund" AND confirm_claim="Approve(unpaid)" AND del IS NULL'));
$totalRows = $totalRows[0];
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while ($metklaim = mysql_fetch_array($mklaim)) {
    $metpeserta = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_dn="'.$metklaim['id_dn'].'" AND id_peserta="'.$metklaim['id_peserta'].'" '));
    if (($no % 2) == 1) {
        $objlass = 'tbl-odd';
    } else {
        $objlass = 'tbl-even';
    }
    echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		<td align="center"><a href="ajk_klaim.php?fu=hapusmove&met=batalrefund&id='.$metpeserta['id'].'" onClick="if(confirm(\'Anda yakin membatalkan peserta refund ini ?\')){return true;}{return false;}"><img border="0" src="image/delete.gif" width="15"></a>
		 <input type="checkbox" class="case" name="nama[]" value="'.$metklaim['id'].'">
		</td>
		<td align="center">'.(++$no + ($pageNow-1) * 250).'</td>
		<td align="center">'.$metklaim['id_peserta'].'</td>
		<td align="center">'.$metklaim['id_dn'].'</td>
		<td>'.$metpeserta['nama'].'</td>
		<td align="center">'.$metpeserta['tgl_lahir'].'</td>
		<td align="center">'.$metpeserta['kredit_tgl'].'</td>
		<td align="center">'.$metpeserta['kredit_tenor'].'</td>
		<td align="center">'.$metpeserta['kredit_akhir'].'</td>
		<td align="right">'.duit($metpeserta['totalpremi']).'</td>
		<td align="center"><b>'._convertDate($metklaim['tgl_claim']).'</b></td>
		<td align="right"><b>'.duit($metklaim['total_claim']).'</b></td>
		<td align="center">'.$metklaim['id_regional'].'</td>
		<td align="center">'.$metklaim['id_cabang'].'</td>
		</tr>';
    $cekdatacn .= $metklaim['id_cn'];
}
if (!$cekdatacn) {
    echo '<tr><td colspan="14" align="center"><a href="ajk_uploader_peserta.php?fu=createcnrefund" onClick="if(confirm(\'Apakah anda sudah yakin dengan semua data peserta Refund ini ?\')){return true;}{return false;}"><input type="submit" name="Create Refund" Value="Create Refund"></a></td></tr>';
} else {
    echo '';
}
echo '</table>';

        ;
        break;

    case "approverefund":
			if (!$_REQUEST['nama']) {
			    echo '<center><font color=red><blink>Tidak ada data peserta Refund yang di pilih, silahkan ceklist data yang akan diproses. !</blink></font><br/>
				  <a href="ajk_klaim.php?fu=setrefund">Kembali Ke Halaman Cetak CN Refund</a></center>';
			} else {
			    echo '<input type="hidden" name="cn_cost" value="'.$_REQUEST['cn_cost'].'">';
			    echo '<input type="hidden" name="cn_produk" value="'.$_REQUEST['cn_produk'].'">';
			    echo '<input type="hidden" name="cn_userinput" value="'.$_REQUEST['cn_userinput'].'"><br />';

			    //SETUPAUTOMAIL
			    $met_mail_staff = mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_REQUEST['cn_userinput'].'"'));
			    $message .='To '.$met_mail_staff['nm_lengkap'].',<br />Terlampir data-data Refund Peserta pada table di bawah ini :
					<table border="0" width="100%" bgcolor="#FFFFF0">
					<tr><th width="1%">No</th>
						<th width="10%">Debit Note</th>
						<th width="10%">Credit Note</th>
						<th width="8%">ID Peserta</th>
						<th>Nama</th>
						<th width="7%">Tanggal Lahir</th>
						<th width="1%">Usia</th>
						<th width="6%">Tgl Akad</th>
						<th width="1%">Tenor</th>
						<th width="6%">Tgl Akhir</th>
						<th width="7%">Plafond</th>
						<th width="7%">Premi</th>
						<th width="6%">Tgl Refund</th>
						<th width="6%">Nilai Refund</th>
						<th width="5%">Status</th>
						<th width="5%">Cabang</th>
					</tr>';
			    //SETUPAUTOMAIL

			    $_cn = mysql_fetch_array($database->doQuery('SELECT id, idC, tgl_createcn, validasi_batchcn, input_date FROM fu_ajk_cn WHERE idC !="" ORDER BY id DESC'));
			    if ($_cn['idC'] < 0) {
			        $idC = 1;
			    } else {
			        $thnInputDate = explode(" ", $_cn['input_date']);
			        $thnInput = explode("-", $thnInputDate);
			        /* revisi 13102016
			           $thnCN = explode("-", $_cn['tgl_createcn']);
			            if ($thnCN[0] < $dateY) {	$idC = 1;	}else{	$idC = $_cn['idC'] + 1;	}
			        */
			        if ($thnInput[0] < $dateY) {
			            $idC = 1;
			        } else {
			            $idC = $_cn['idC'] + 1;
			        }
			    }
			    $idcnnya = 10000000000 + $idC;
			    $idcn = substr($idcnnya, 1);
			    $cntgl = explode("-", $futgldn);
			    $cnthn = substr($cntgl[0], 2);
			    $cn_kode = 'ACN'.$cnthn.''.$cntgl[1].''.$idcn;

			    foreach ($_REQUEST['nama'] as $r => $eL) {
			        $met_cn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cn_tempf WHERE id="'.$eL.'"'));
			        //INSERT TABLE ASLI
			        $_met_cn = $database->doQuery('INSERT INTO fu_ajk_cn SET id_dn="'.$met_cn['id_dn'].'",
																	 idC="'.$idC.'",
																	 id_cn="'.$cn_kode.'",
																	 id_cost="'.$met_cn['id_cost'].'",
																	 id_nopol="'.$met_cn['id_nopol'].'",
																	 id_peserta="'.$met_cn['id_peserta'].'",
																	 id_regional="'.$met_cn['id_regional'].'",
																	 id_cabang="'.$met_cn['id_cabang'].'",
																	 tgl_createcn="'.$futoday.'",
																	 tgl_claim="'.$met_cn['tgl_claim'].'",
																	 type_claim="'.$met_cn['type_claim'].'",
																	 type_refund="'.$met_cn['type_refund'].'",
																	 premi="'.$met_cn['premi'].'",
																	 total_claim="'.$met_cn['total_claim'].'",
																	 total_claim_as="'.$met_cn['total_claim_as'].'",
																	 confirm_claim="Approve(unpaid)",
																	 validasi_cn_uw="'.$met_cn['validasi_cn_uw'].'",
																	 validasi_cn_arm="'.$met_cn['validasi_cn_arm'].'",
																	 keterangan="'.$met_cn['keterangan'].'",
																	 fname="'.$met_cn['fname'].'",
																	 input_by="'.$met_cn['input_by'].'",
																	 input_date="'.$met_cn['input_date'].'"');
			        $CNLastID = mysql_fetch_array($database->doQuery('SELECT id FROM fu_ajk_cn ORDER BY id DESC'));
			        $__met_pesertaCN = $database->doQuery('UPDATE fu_ajk_peserta SET id_klaim="'.$CNLastID['id'].'", status_aktif="Lapse" WHERE id_cost="'.$met_cn['id_cost'].'" AND id_polis="'.$met_cn['id_nopol'].'" AND  id_dn="'.$met_cn['id_dn'].'" AND  id_peserta="'.$met_cn['id_peserta'].'"');
			        $__met_pesertaCN_as = $database->doQuery('UPDATE fu_ajk_peserta_as SET id_cn="'.$CNLastID['id'].'" WHERE id_bank="'.$met_cn['id_cost'].'" AND id_polis="'.$met_cn['id_nopol'].'" AND  id_dn="'.$met_cn['id_dn'].'" AND  id_peserta="'.$met_cn['id_peserta'].'"');


			        // $arap_produksi_cn = $database->doQuery('insert into CMS_ArAp_Master(
											// 		fArAp_No
											// 		,fArAp_Status
											// 		,fArAp_TransactionCode
											// 		,fArAp_TransactionDate
											// 		,fArAp_Customer_Id
											// 		,fArAp_Customer_Nm
											// 		,fArAp_ReferenceNo1_1
											// 		,fArAp_ReferenceNo1_2
											// 		,fArAp_ReferenceNo1_3
											// 		,fArAp_Note
											// 		,fArAp_CrrencyCode
											// 		,fArAp_AmmountTotal
											// 		,input_by
											// 		,input_date) SELECT
											// 		REPLACE(fu_ajk_cn.id_cn,"CNA","DNA"),
											// 		"A",
											// 		"AR-03",
											// 		current_date(),
											// 		fu_ajk_asuransi.`code`,
											// 		fu_ajk_asuransi.`name`,
											// 		fu_ajk_costumer.`name`,
											// 		fu_ajk_cn.id_cn,
											// 		"",
											// 		fu_ajk_cn.noperkredit,
											// 		"IDR",
											// 		fu_ajk_cn.premi,
											// 		"' . $_SESSION['nm_user'] . '",
											// 		current_timestamp()
											// 		FROM
											// 		fu_ajk_cn
											// 		INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
											// 		INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
											// 		INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
											// 		INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta AND fu_ajk_cn.id = fu_ajk_peserta.id_klaim
											// 		INNER JOIN fu_ajk_costumer ON fu_ajk_cn.id_cost = fu_ajk_costumer.id
											// 		LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
											// 		where
											// 		fu_ajk_cn.type_claim = "Refund" AND fu_ajk_cn.confirm_claim <> "Pending" AND fu_ajk_cn.del IS NULL
											// 		and fu_ajk_cn.id_peserta="' . $met_cn['id_peserta'] . '"');


			        // $arap_produksi_dn = $database->doQuery('insert into CMS_ArAp_Master(
											// 		fArAp_No
											// 		,fArAp_Status
											// 		,fArAp_TransactionCode
											// 		,fArAp_TransactionDate
											// 		,fArAp_Customer_Id
											// 		,fArAp_Customer_Nm
											// 		,fArAp_ReferenceNo1_1
											// 		,fArAp_ReferenceNo1_2
											// 		,fArAp_ReferenceNo1_3
											// 		,fArAp_Note
											// 		,fArAp_CrrencyCode
											// 		,fArAp_AmmountTotal
											// 		,input_by
											// 		,input_date) SELECT
											// 		fu_ajk_cn.id_cn,
											// 		"A",
											// 		"AP-03",
											// 		current_date(),
											// 		fu_ajk_costumer.kd_databank,
											// 		fu_ajk_costumer.`name`,
											// 		fu_ajk_polis.nmproduk,
											// 		fu_ajk_cn.id_cabang,
											// 		"",
											// 		fu_ajk_cn.noperkredit,
											// 		"IDR",
											// 		fu_ajk_cn.premi+fu_ajk_peserta_as.nettpremi,
											// 		"' . $_SESSION['nm_user'] . '",
											// 		current_timestamp()
											// 		FROM
											// 		fu_ajk_cn
											// 		INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
											// 		INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
											// 		INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
											// 		INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta AND fu_ajk_cn.id = fu_ajk_peserta.id_klaim
											// 		INNER JOIN fu_ajk_costumer ON fu_ajk_cn.id_cost = fu_ajk_costumer.id

											// 		INNER JOIN fu_ajk_peserta_as ON fu_ajk_peserta.id_peserta = fu_ajk_peserta_as.id_peserta AND fu_ajk_peserta.id_dn = fu_ajk_peserta_as.id_dn

											// 		LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
											// 		where
											// 		fu_ajk_cn.type_claim = "Refund" AND fu_ajk_cn.confirm_claim <> "Pending" AND fu_ajk_cn.del IS NULL
											// 		and fu_ajk_cn.id_peserta="' . $met_cn['id_peserta'] . '"');


			        // $arap_produksi_detail_dn = $database->doQuery('insert into CMS_ArAp_Detail(
											// 		fArAp_No
											// 		,fArAp_Counter
											// 		,fArAp_BMaretialCode
											// 		,fArAp_Description
											// 		,fArAp_Amount
											// 		,input_by
											// 		,input_date)
											// 		SELECT
											// 		REPLACE(fu_ajk_cn.id_cn,"CNA","DNA"),
											// 		1,
											// 		"RFN",
											// 		"Penerimaan Pembayaran refund dari Asuransi",
											// 		fu_ajk_cn.premi,
											// 		"' . $_SESSION['nm_user'] . '",
											// 		current_timestamp()
											// 		FROM
											// 		fu_ajk_cn
											// 		INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
											// 		INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
											// 		INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
											// 		INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta AND fu_ajk_cn.id = fu_ajk_peserta.id_klaim
											// 		INNER JOIN fu_ajk_costumer ON fu_ajk_cn.id_cost = fu_ajk_costumer.id
											// 		LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
											// 		where
											// 		fu_ajk_cn.type_claim = "Refund" AND fu_ajk_cn.confirm_claim <> "Pending" AND fu_ajk_cn.del IS NULL
											// 		and fu_ajk_cn.id_peserta="' . $met_cn['id_peserta'] . '"');

			        // $arap_produksi__detail_cn = $database->doQuery('insert into CMS_ArAp_Detail(
											// 		fArAp_No
											// 		,fArAp_Counter
											// 		,fArAp_BMaretialCode
											// 		,fArAp_Description
											// 		,fArAp_Amount
											// 		,input_by
											// 		,input_date)
											// 		SELECT
											// 		fu_ajk_cn.id_cn,
											// 		1,
											// 		"RFN",
											// 		"Pembayaran Premi refund Ke Bank",
											// 		fu_ajk_cn.premi+fu_ajk_peserta_as.nettpremi,
											// 		"' . $_SESSION['nm_user'] . '",
											// 		current_timestamp()
											// 		FROM
											// 		fu_ajk_cn
											// 		INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
											// 		INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
											// 		INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
											// 		INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta AND fu_ajk_cn.id = fu_ajk_peserta.id_klaim
											// 		INNER JOIN fu_ajk_costumer ON fu_ajk_cn.id_cost = fu_ajk_costumer.id

											// 		INNER JOIN fu_ajk_peserta_as ON fu_ajk_peserta.id_peserta = fu_ajk_peserta_as.id_peserta AND fu_ajk_peserta.id_dn = fu_ajk_peserta_as.id_dn

											// 		LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
											// 		where
											// 		fu_ajk_cn.type_claim = "Refund" AND fu_ajk_cn.confirm_claim <> "Pending" AND fu_ajk_cn.del IS NULL
											// 		and fu_ajk_cn.id_peserta="' . $met_cn['id_peserta'] . '"');


			        // $arap_detail_dn=$database->doQuery('insert into CMS_ArAp_Referensi(
											// 							fArAp_No
											// 							,fArAp_Counter
											// 							,fArAp_CoreCode
											// 							,fArAp_BMaretialCode
											// 							,fArAp_RefMemberID
											// 							,fArAp_RefMemberNm
											// 							,fArAp_RefDescription
											// 							,fArAp_RefAmount
											// 							, input_by
											// 							, input_date)
											// 							SELECT
											// 							REPLACE(fu_ajk_cn.id_cn,"CNA","DNA")
											// 							, "1"
											// 							, "RFN"
											// 							, "RFN"
											// 							, fu_ajk_cn.id_peserta
											// 							, fu_ajk_peserta.nama
											// 							, ""
											// 							, fu_ajk_cn.premi
											// 							, "' . $_SESSION['nm_user'] . '"
											// 							, current_timestamp()
											// 							FROM
											// 							fu_ajk_cn
											// 							INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
											// 							INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
											// 							INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
											// 							INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta AND fu_ajk_cn.id = fu_ajk_peserta.id_klaim
											// 							INNER JOIN fu_ajk_costumer ON fu_ajk_cn.id_cost = fu_ajk_costumer.id
											// 							LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
											// 							where
											// 							fu_ajk_cn.type_claim = "Refund" AND fu_ajk_cn.confirm_claim <> "Pending" AND fu_ajk_cn.del IS NULL
											// 							and fu_ajk_cn.id_peserta="' . $met_cn['id_peserta'] . '"');


			        // $arap_detail_cn=$database->doQuery('insert into CMS_ArAp_Referensi(
											// 							fArAp_No
											// 							,fArAp_Counter
											// 							,fArAp_CoreCode
											// 							,fArAp_BMaretialCode
											// 							,fArAp_RefMemberID
											// 							,fArAp_RefMemberNm
											// 							,fArAp_RefDescription
											// 							,fArAp_RefAmount
											// 							, input_by
											// 							, input_date)
											// 							select fu_ajk_cn.id_cn
											// 							, "1"
											// 							, "RFN"
											// 							, "RFN"
											// 							, fu_ajk_cn.id_peserta
											// 							, fu_ajk_peserta.nama
											// 							, ""
											// 							, fu_ajk_cn.premi+fu_ajk_peserta_as.nettpremi
											// 							, "' . $_SESSION['nm_user'] . '"
											// 							, current_timestamp()
											// 							FROM
											// 							fu_ajk_cn
											// 							INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
											// 							INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
											// 							INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
											// 							INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta AND fu_ajk_cn.id = fu_ajk_peserta.id_klaim
											// 							INNER JOIN fu_ajk_costumer ON fu_ajk_cn.id_cost = fu_ajk_costumer.id

											// 							INNER JOIN fu_ajk_peserta_as ON fu_ajk_peserta.id_peserta = fu_ajk_peserta_as.id_peserta AND fu_ajk_peserta.id_dn = fu_ajk_peserta_as.id_dn

											// 							LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
											// 							where
											// 							fu_ajk_cn.type_claim = "Refund" AND fu_ajk_cn.confirm_claim <> "Pending" AND fu_ajk_cn.del IS NULL
											// 							and fu_ajk_cn.id_peserta="' . $met_cn['id_peserta'] . '"');


			        $arap_produksi_cn = $database->doQuery('INSERT INTO CMS_ArAp_Transaction(fArAp_TransactionCode,
																															fArAp_TransactionDate,
																															fArAp_Status,
																															fArAp_No,
																															fArAp_Customer_Id,
																															fArAp_Customer_Nm,
																															fArAp_Asuransi_Id,
																															fArAp_Asuransi_Nm,
																															fArAp_Produk_Nm,
																															fArAp_StatusPeserta,
																															fArAp_DateStatus,
																															fArAp_CoreCode,
																															fArAp_BMaterialCode,
																															fArAp_RefMemberID,
																															fArAp_RefMemberNm,
																															fArAp_RefCabang,
																															fArAp_RefDescription,
																															fArAp_RefAmount,
																															fArAp_RefAmount2,
																															fArAp_RefDOB,
																															fArAp_AssDate,
																															fArAp_RefTenor,
																															fArAp_RefPlafond,
																															fArAp_Return_Status,
																															fArAp_Return_Date,
																															fArAp_Return_Amount,
																															fArAp_SourceDB,
																															input_by,
																															input_date)
																									SELECT "AP-02" as fArAp_TransactionCode,
																												fu_ajk_cn.tgl_createcn as fArAp_TransactionDate,
																												"A" as fArAp_Status,
																												fu_ajk_cn.id_cn as fArAp_No,
																												fu_ajk_grupproduk.nmproduk as fArAp_Customer_Id,
																												fu_ajk_grupproduk.nm_mitra as fArAp_Customer_Nm,
																												fu_ajk_asuransi.`code` as fArAp_Asuransi_Id,
																												fu_ajk_asuransi.`name` as fArAp_Asuransi_Nm,
																												fu_ajk_polis.nmproduk as fArAp_Produk_Nm,
																												CONCAT(IFNULL(fu_ajk_peserta.status_aktif,"")," ",IFNULL(fu_ajk_peserta.status_peserta,""))as fArAp_StatusPeserta,
																												DATE_FORMAT(NOW(),"%Y-%m-%d")as fArAp_DateStatus,
																												"RFN" as fArAp_CoreCode,
																												"RFN" as fArAp_BMaterialCode,
																												fu_ajk_peserta.id_peserta as fArAp_RefMemberID,
																												fu_ajk_peserta.nama as fArAp_RefMemberNm,
																												fu_ajk_peserta.cabang as fArAp_RefCabang,
																												null as fArAp_RefDescription,
																												fu_ajk_cn.total_claim as fArAp_RefAmount,
																												NULL as fArAp_RefAmount2,
																												fu_ajk_peserta.tgl_lahir as fArAp_RefDOB,
																												fu_ajk_peserta.tgl_laporan as fArAp_AssDate,
																												fu_ajk_peserta.kredit_tenor as fArAp_RefTenor,
																												fu_ajk_peserta.kredit_jumlah as fArAp_RefPlafond,
																												null as fArAp_Return_Status,
																												null as fArAp_Return_Date,
																												null as fArAp_Return_Amount,
																												"AJK" AS fArAp_SourceDB,
																												"'.$_SESSION['nm_user'].'" as input_by,
																												now() as input_date
																									FROM fu_ajk_peserta
																										 INNER JOIN fu_ajk_peserta_as
																										 on fu_ajk_peserta_as.id_peserta = fu_ajk_peserta.id_peserta
																										 INNER JOIN fu_ajk_dn
																										 on fu_ajk_dn.id = fu_ajk_peserta.id_dn
																										 INNER JOIN fu_ajk_asuransi
																										 on fu_ajk_asuransi.id = fu_ajk_peserta_as.id_asuransi
																										 INNER JOIN fu_ajk_grupproduk
																										 on fu_ajk_grupproduk.id = fu_ajk_peserta.nama_mitra
																										 INNER JOIN fu_ajk_polis
																										 ON fu_ajk_polis.id = fu_ajk_peserta.id_polis
																										 INNER JOIN fu_ajk_cn
																										 ON fu_ajk_cn.id = fu_ajk_peserta.id_klaim
																									WHERE fu_ajk_peserta.del is NULL and
																											fu_ajk_peserta_as.del is NULL and
																											fu_ajk_dn.del is null and
																											fu_ajk_cn.del is null and
																											fu_ajk_asuransi.del is NULL AND
																											fu_ajk_grupproduk.del is NULL and
																											fu_ajk_polis.del is NULL and
																											fu_ajk_cn.type_claim = "Refund" and
																											fu_ajk_peserta.id_peserta = "'.$met_cn['id_peserta'].'"');
			        $arap_produksi_dn = $database->doQuery('INSERT INTO CMS_ArAp_Transaction(fArAp_TransactionCode,
																															fArAp_TransactionDate,
																															fArAp_Status,
																															fArAp_No,
																															fArAp_Customer_Id,
																															fArAp_Customer_Nm,
																															fArAp_Asuransi_Id,
																															fArAp_Asuransi_Nm,
																															fArAp_Produk_Nm,
																															fArAp_StatusPeserta,
																															fArAp_DateStatus,
																															fArAp_CoreCode,
																															fArAp_BMaterialCode,
																															fArAp_RefMemberID,
																															fArAp_RefMemberNm,
																															fArAp_RefCabang,
																															fArAp_RefDescription,
																															fArAp_RefAmount,
																															fArAp_RefAmount2,
																															fArAp_RefDOB,
																															fArAp_AssDate,
																															fArAp_RefTenor,
																															fArAp_RefPlafond,
																															fArAp_Return_Status,
																															fArAp_Return_Date,
																															fArAp_Return_Amount,
																															fArAp_SourceDB,
																															input_by,
																															input_date)
																									SELECT "AR-02" as fArAp_TransactionCode,
																													fu_ajk_cn.tgl_createcn as fArAp_TransactionDate,
																													"A" as fArAp_Status,
																													CONCAT("ADNA",MID(fu_ajk_cn.id_cn,4,20))as fArAp_No,
																													fu_ajk_grupproduk.nmproduk as fArAp_Customer_Id,
																													fu_ajk_grupproduk.nm_mitra as fArAp_Customer_Nm,
																													fu_ajk_asuransi.`code` as fArAp_Asuransi_Id,
																													fu_ajk_asuransi.`name` as fArAp_Asuransi_Nm,
																													fu_ajk_polis.nmproduk as fArAp_Produk_Nm,
																													CONCAT(IFNULL(fu_ajk_peserta.status_aktif,"")," ",IFNULL(fu_ajk_peserta.status_peserta,""))as fArAp_StatusPeserta,
																													DATE_FORMAT(NOW(),"%Y-%m-%d")as fArAp_DateStatus,
																													"RFN" as fArAp_CoreCode,
																													"RFN-AS" as fArAp_BMaterialCode,
																													fu_ajk_peserta.id_peserta as fArAp_RefMemberID,
																													fu_ajk_peserta.nama as fArAp_RefMemberNm,
																													fu_ajk_peserta.cabang as fArAp_RefCabang,
																													null as fArAp_RefDescription,
																													fu_ajk_cn.total_claim_as as fArAp_RefAmount,
																													NULL as fArAp_RefAmount2,
																													fu_ajk_peserta.tgl_lahir as fArAp_RefDOB,
																													fu_ajk_peserta.tgl_laporan as fArAp_AssDate,
																													fu_ajk_peserta.kredit_tenor as fArAp_RefTenor,
																													fu_ajk_peserta.kredit_jumlah as fArAp_RefPlafond,
																													null as fArAp_Return_Status,
																													null as fArAp_Return_Date,
																													null as fArAp_Return_Amount,
																													"AJK" AS fArAp_SourceDB,
																													"'.$_SESSION['nm_user'].'" as input_by,
																													now() as input_date
																									FROM fu_ajk_peserta
																											 INNER JOIN fu_ajk_peserta_as
																											 on fu_ajk_peserta_as.id_peserta = fu_ajk_peserta.id_peserta
																											 INNER JOIN fu_ajk_dn
																											 on fu_ajk_dn.id = fu_ajk_peserta.id_dn
																											 INNER JOIN fu_ajk_asuransi
																											 on fu_ajk_asuransi.id = fu_ajk_peserta_as.id_asuransi
																											 INNER JOIN fu_ajk_grupproduk
																											 on fu_ajk_grupproduk.id = fu_ajk_peserta.nama_mitra
																											 INNER JOIN fu_ajk_polis
																											 ON fu_ajk_polis.id = fu_ajk_peserta.id_polis
																											 INNER JOIN fu_ajk_cn
																											 ON fu_ajk_cn.id = fu_ajk_peserta.id_klaim
																									WHERE fu_ajk_peserta.del is NULL and
																												fu_ajk_peserta_as.del is NULL and
																												fu_ajk_dn.del is null and
																												fu_ajk_cn.del is null and
																												fu_ajk_asuransi.del is NULL AND
																												fu_ajk_grupproduk.del is NULL and
																												fu_ajk_polis.del is NULL and
																												fu_ajk_cn.type_claim = "Refund" and
																												fu_ajk_peserta.id_peserta = "'.$met_cn['id_peserta'].'"');
			    }

			    $CNLastMAIL = $database->doQuery('SELECT * FROM fu_ajk_cn WHERE id_cn="'.$cn_kode.'" ORDER BY id DESC');
			    while ($met_mailCN = mysql_fetch_array($CNLastMAIL)) {
			        $met_mailPeserta = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_klaim="'.$met_mailCN['id'].'"'));
			        $met_mailDN = mysql_fetch_array($database->doQuery('SELECT id, dn_kode FROM fu_ajk_dn WHERE id="'.$met_mailCN['id_dn'].'"'));
			        if (($no % 2) == 1) {
			            $objlass = 'tbl-odd';
			        } else {
			            $objlass = 'tbl-even';
			        }
			        $message .='<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
								<td width="1%" align="center">'.++$no.'</td>
								<td width="10%" align="center">'.$met_mailDN['dn_kode'].'</td>
								<td width="10%" align="center">'.$met_mailCN['id_cn'].'</td>
								<td width="8%" align="center">'.$met_mailPeserta['id_peserta'].'</td>
								<td>'.$met_mailPeserta['nama'].'</td>
								<td width="7%" align="center">'._convertDate($met_mailPeserta['tgl_lahir']).'</td>
								<td width="1%" align="center">'.$met_mailPeserta['usia'].'</td>
								<td width="6%" align="center">'._convertDate($met_mailPeserta['kredit_tgl']).'</td>
								<td width="1%" align="center">'.$met_mailPeserta['kredit_tenor'].'</td>
								<td width="6%" align="center">'._convertDate($met_mailPeserta['kredit_akhir']).'</td>
								<td width="7%" align="right">'.duit($met_mailPeserta['kredit_jumlah']).'</td>
								<td width="7%" align="right">'.duit($met_mailPeserta['totalpremi']).'</td>
								<td width="6%" align="center">'._convertDate($met_mailCN['tgl_claim']).'</td>
								<td width="6%" align="right">'.duit($met_mailCN['total_claim']).'</td>
								<td width="5%" align="center">'.$met_mailCN['type_claim'].'</td>
								<td width="5%" align="center">'.$met_mailCN['id_cabang'].'</td>
								</tr>';
			    }
			    $mamet_today = explode("-", $futoday);
			    $mamet_today_ = $mamet_today[2].'-'.$mamet_today[1].'-'.$mamet_today[0];
			    $message .='<tr><td colspan="16"><br />Data Credit Note nomor '.$met_mailCN['id_cn'].' telah dibuat oleh '.$q['nm_lengkap'].' pada tanggal '.$mamet_today_.' '.$timelog.'</td></tr>
							<tr><td colspan="16"><br />Terimakasih,</td></tr>
							<tr><td colspan="16">'.$q['nm_lengkap'].'</td></tr>
							</table>';
			    //echo $message;
			    //SMTP CLIENT
			    $mail = new PHPMailer; // call the class
			    $mail->IsSMTP();
			    $mail->Host = SMTP_HOST; //Hostname of the mail server
			    $mail->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
	        $mail->Username = SMTP_UNAME; //Username for SMTP authentication any valid email created in your domain
	        $mail->Password = SMTP_PWORD; //Password for SMTP authentication
	        $mail->AddReplyTo = SMTP_REPLYMAIL; //reply-to address
	        $mail->debug = 1;
	        $mail->SMTPSecure = "ssl";
	        $mail->IsHTML(true);

			    //EMAIL STAFF BANK
			    $mail_staff_client = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="'.$met_mail_staff['id_cost'].'" AND id_polis="'.$met_mail_staff['id_polis'].'" AND nm_user="'.$met_mail_staff['nm_user'].'"');
			    while ($mail_staff_client_ = mysql_fetch_array($mail_staff_client)) {
			        $mail->AddAddress($mail_staff_client_['email'], $mail_staff_client_['nm_lengkap']); //To address who will receive this email
			    }
			    //EMAIL SPV BANK
			    $mail_spv_client = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="'.$met_mail_staff['id_cost'].'" AND id_polis="" AND level="6"');
			    while ($mail_spv_client_ = mysql_fetch_array($mail_spv_client)) {
			        $mail->AddAddress($mail_spv_client_['email'], $mail_spv_client_['nm_lengkap']); //To address who will receive this email
			    }
			    //EMAIL ARM
			    $mail_arm = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="" AND id_polis="" AND level="7" AND status="ARM"');
			    while ($mail_arm_ = mysql_fetch_array($mail_arm)) {
			        $mail->AddAddress($mail_arm_['email'], $mail_arm_['nm_lengkap']); //To address who will receive this email
			    }
			    $mail->SetFrom($q['email'], $q['nm_lengkap']); //From address of the mail
			    $mail->Subject = "AJKOnline - DATA CREDIT NOTE (CN) REFUND"; //Subject od your mail
			    $mail->AddCC("hansen@adonai.co.id");
			    $mail->MsgHTML($message); //Put your body of the message you can place html code here
			    //$send = $mail->Send(); //Send the mails
			    //SMTP CLIENT
			    //echo $mail_staff_client_['email'].'<br />';
			    //echo $message.'<br />';

			    foreach ($_REQUEST['nama'] as $r1 => $eL1) {
			        $met_cn1 = $database->doQuery('DELETE FROM fu_ajk_cn_tempf WHERE id="'.$eL1.'"');
			    }

			    echo '<center>Data Refund telah di buat oleh '.$q['nm_lengkap'].' pada tanggal '._convertDate($futgldn).'</center><meta http-equiv="refresh" content="1;ajk_klaim.php?fu=refund">';
			}
			        ;
    break;

    case "createcnrefund":
if (!$_REQUEST['nama']) {
    echo '<center><font color=red><blink>Tidak ada data yang di pilih, silahkan ceklist data yang akan di buatkan data CN. !</blink></font><br/>
	  <a href="ajk_klaim.php?fu=setrefundAppr">Kembali Ke Halaman Create CN</a></center>';
} else {
    //SET NOMOR CN REFUND
    $ceknmrCN = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cn ORDER BY id DESC'));
    if ($ceknmrCN['id_regional']==$r['id_regional'] and $ceknmrCN['type_claim']=="Refund") {
        $nmridC = $ceknmrCN['idC'];
    } else {
        $nmridC = $ceknmrCN['idC'] + 1;
    }
    $idcnnya = 100000000 + $nmridC;
    $idcn = substr($idcnnya, 1);
    $cntgl = explode("-", $futgldn);
    $cnthn = substr($cntgl[0], 2);
    $cn_kode = 'AJKCN-'.$cnthn.'-'.$cntgl[1].'-'.$idcn;
    //SET NOMOR CN REFUND
    foreach ($_REQUEST['nama'] as $k => $val) {
        //echo $val.'-'.$cn_kode.'<br />';
        $r = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cn_tempf WHERE id="'.$val.'"'));
        $er = $database->doQuery('INSERT INTO fu_ajk_cn SET id_cost="'.$r['id_cost'].'",
								 id_nopol="'.$r['id_nopol'].'",
								 id_dn="'.$r['id_dn'].'",
								 idC="'.$nmridC.'",
								 id_cn="'.$cn_kode.'",
								 id_peserta="'.$r['id_peserta'].'",
								 id_regional="'.$r['id_regional'].'",
								 id_cabang="'.$r['id_cabang'].'",
								 tgl_createcn="'.$r['tgl_createcn'].'",
								 tgl_claim="'.$r['tgl_claim'].'",
								 type_claim="'.$r['type_claim'].'",
								 validasi_cn_uw="ya",
								 premi="'.$r['premi'].'",
								 total_claim="'.$r['total_claim'].'",
								 confirm_claim="'.$r['confirm_claim'].'",
								 input_by="'.$q['nm_lengkap'].'",
								 input_date="'.$futgl.'",
 								 update_by="'.$r['update_by'].'",
								 update_time="'.$r['update_time'].'" ');

        $metpeserta = $database->doQuery('UPDATE fu_ajk_peserta SET id_klaim="'.$cn_kode.'" WHERE id_peserta="'.$r['id_peserta'].'"');
        $mametganteng = $database->doQuery('DELETE FROM fu_ajk_cn_tempf WHERE id_peserta="'.$r['id_peserta'].'"');
    }
    //CONFIRM EMAIL REFUND
    $Rmail = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="" AND status=30');
    while ($eRmail = mysql_fetch_array($Rmail)) {
        $metMail .=$eRmail['email'].', ';
    }
    // $to = $metMail.''.$q['email'].', '."sumiyanto@relife.co.id, arief.kurniawan@relife.co.id" ;
    $subject = 'AJKOnline - Data CN Refund telah di buat oleh '.$q['nm_lengkap'].'';
    $message .= '<html><head><title>Data Peserta Refund sudah di Approve oleh '.$q['nm_lengkap'].'</title></head><body>
				<table border="1" width="100%" cellpading="1" cellspacing="1">
					<tr><th colspan="14">Data Peserta Refund telah di buat oleh <b>'.$_SESSION['nm_user'].'</b> pada tanggal '._convertDate($futgldn).'</tr>
					<tr><td width="1%" align="center">No</td>
						<td align="center">Client</td>
						<td width="5%" align="center">ID_Peserta</td>
						<td width="5%" align="center">Nomor CN</td>
						<td width="10%" align="center">Nama</td>
						<td width="1%" align="center">Usia</td>
						<td width="8%" align="center">Kredit Awal</td>
						<td width="8%" align="center">Kredit Akhir</td>
						<td width="8%" align="center">Tgl Refund</td>
						<td width="8%" align="center">Total</td>
						<td width="8%" align="center">Status</td>
						<td width="10%" align="center">Regional</td>
						<td width="10%" align="center">Area</td>
						<td width="10%" align="center">Cabang</td>
					</tr>';
    $refpeserta = $database->doQuery('SELECT * FROM fu_ajk_cn WHERE id_cn="'.$cn_kode.'" AND type_claim="Refund"');
    while ($mametrefnya = mysql_fetch_array($refpeserta)) {
        $mametcostumerref = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$mametrefnya['id_cost'].'"'));
        $mametcnref = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_peserta="'.$mametrefnya['id_peserta'].'"'));
        $message .= '<tr><td>'.++$no.'</td>
				 	 <td>'.$mametcostumerref['name'].'</td>
				 	 <td>'.$mametrefnya['id_peserta'].'</td>
				 	 <td>'.$mametcnref['id_klaim'].'</td>
				 	 <td>'.$mametcnref['nama'].'</td>
				 	 <td>'.$mametcnref['usia'].'</td>
				 	 <td>'.$mametcnref['kredit_tgl'].'</td>
				 	 <td>'.$mametcnref['kredit_akhir'].'</td>
				 	 <td>'._convertDate($mametrefnya['tgl_claim']).'</td>
				 	 <td>'.duit($mametrefnya['total_claim']).'</td>
				 	 <td>'.$mametcnref['status_peserta'].'</td>
				 	 <td>'.$mametcnref['regional'].'</td>
				 	 <td>'.$mametcnref['area'].'</td>
				 	 <td>'.$mametcnref['cabang'].'</td>
				 </tr>';
    }
    $message .= '</table>
				</body></html>';
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $headers .= 'From: '.$q['email'].'' . "\r\n";
    // $headers .= 'Cc:  relife-ajk@relife.co.id' . "\r\n";
    mail($to, $subject, $message, $headers);
    /*echo $to.'<br />';
    echo $subject.'<br />';
    echo $message.'<br />';
    echo $headers.'<br />';*/
    echo '<div align="center">Data DN telah selesai di buat oleh '.$q['nm_lengkap'].' pada tanggal '.$futgldn.'.</div><meta http-equiv="refresh" content="2; url=ajk_klaim.php?fu=refund">';
}
    ;
    break;

    case "newrefund":
echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
		<tr><th colspan="8" width="100%" align="left" colspan="2">Modul Klaim Refund</font></th><th width="5%" colspan="2"><a href="ajk_klaim.php?fu=refund"><img src="image/Backward-64.png" width="20"></a></th></tr>
		</table><br />';
$cat=$_GET['cat']; // Use this line or below line if register_global is off
if (strlen($cat) > 0 and !is_numeric($cat)) { // to check if $cat is numeric data or not.
echo "Data Error";
    exit;
}
echo '<fieldset>
	<legend>Searching</legend>
	<table border="0" width="100%" cellpadding="3" cellspacing="1" align="center">
  <form method="post" action="">
  <tr><td width="25%" align="right">Nama Perusahaan</td>
	  <td width="30%">: <select id="cat" name="cat" onchange="reloadrefund(this.form)">
	  	<option value="">---Select Company---</option>';
        $quer2=$database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name ASC');
while ($noticia2 = mysql_fetch_array($quer2)) {
    if ($noticia2['id']==$cat) {
        echo '<option selected value="'.$noticia2['id'].'">'.$noticia2['name'].'</option><BR>';
    } else {
        echo  '<option value="'.$noticia2['id'].'">'.$noticia2['name'].'</option>';
    }
}
echo '</select></td></tr>
  <tr><td width="15%" align="right">Nama Produk</td>
	  <td width="30%">: ';
if (isset($cat) and strlen($cat) > 0) {
    $quer=$database->doQuery('SELECT * FROM fu_ajk_polis where id_cost="'.$cat.'" ORDER BY id ASC');
} else {
    $quer=$database->doQuery("SELECT * FROM fu_ajk_polis ORDER BY id ASC");
}
echo '<select name="subcat"><option value="">---Pilih Produk---</option>';
while ($noticia = mysql_fetch_array($quer)) {
    echo  '<option value="'.$noticia['id'].'"'._selected($noticia["nmproduk"]. $noticia['id']).'>'.$noticia['nmproduk'].'</option>';
}
echo '</select></td></tr>
		<tr><td width="5%" align="right">Nama</td><td>: <input type="text" name="rnama" value="'.$_REQUEST['rnama'].'"></td></tr>
	<tr><td width="10%" align="right">Nomor DN</td><td width="20%">: <input type="text" name="nodn" value="'.$_REQUEST['nodn'].'"></td></tr>
	<tr><td width="5%" align="right">Tanggal Lahir</td><td>: ';print initCalendar();	print calendarBox('rdob', 'triger', $_REQUEST['rdob']);
echo '</td></tr>
			<tr><td colspan="4" align="center"><input type="submit" name="r" value="Searching" class="button"></td></tr>
			</form>
			</table></fieldset><br />';
echo '<table border="0" width="100%" cellpadding="5" cellspacing="1" bgcolor="#bde0e6">
	<tr><th rowspan="2" width="3%">No</td>
		<th rowspan="2" width="8%">Nama Produk</td>
		<th rowspan="2" width="7%">ID Peserta</td>
		<th rowspan="2" width="10%">Nomor DN</td>
		<th rowspan="2" >Nama</td>
		<th rowspan="2" width="5%">DOB</td>
		<th rowspan="2" width="3%">Usia</td>
		<th rowspan="2" width="7%">Sum Insured</th>
		<th colspan="3" width="5%">Periode</th>
		<th rowspan="2" width="5%">Premi</td>
		<th rowspan="2" width="5%">Status</td>
		<th rowspan="2" width="5%">Cabang</td>
		<th rowspan="2" width="2%">Opt</td>
	</tr>
	<tr><th width="5%">Awal</th><th width="2%">Tenor</th><th width="5%">Akhir</th></tr>
	<tr bgcolor="#BFFA87"><td colspan="18" align="center">';

if ($_REQUEST['r']=="Searching") {
    if ($_REQUEST['met']=="getrefund") {
        if ($_REQUEST['eLs']=="Ok") {
            $mamet = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id="'.$_REQUEST['id'].'"'));

            //CONVERT TANGGAL YANG ADA "/"
            //$findmet="/";
            //$fpos = stripos($mamet['kredit_akhir'], $findmet);
            //if ($fpos === false) { $cektglnya = $mamet['kredit_akhir'];	}
            //else	{	$riweuh = explode("/", $mamet['kredit_akhir']);						$cektglnya = $riweuh[0].'-'.$riweuh[1].'-'.$riweuh[2];	}
            $m = ceil(abs(strtotime($mamet['kredit_akhir']) - strtotime($_REQUEST['rdns'])) / 86400);
            $r = floor($m / 30.4375);
            $movementrefund=mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_rasio_claim WHERE id_cost="'.$mamet['id_cost'].'" AND id_polis="'.$mamet['id_polis'].'"'));
            $biayapenutupan = $mamet['totalpremi'] *  $movementrefund['refund'] / 100 .'<br />';
            $premirefund = ceil(($r / $mamet['kredit_tenor']) * ($mamet['totalpremi'] - $biayapenutupan));
            //$premirefund = ((($r / $mamet['kredit_tenor']) * 0.7) * $mamet['totalpremi']);
            //CONVERT TANGGAL YANG ADA "/"

            //SET NOMOR CN REFUND
            $cn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cn ORDER BY id DESC'));
            if ($cn['id_cn']=="") {
                $metautocn = $cn['idC'];
            } else {
                $metidcn = explode(" ", $cn['input_date']);
                $metidthncn = explode("-", $metidcn[0]);
                if ($metidthncn[0] < $futgliddn) {
                    $metautocn = 1;
                } else {
                    $metautocn = $cn['idC'] + 1;
                }
            }
            $idcnnya = 100000000 + $metautocn;
            $idcn = substr($idcnnya, 1);
            $cntgl = explode("-", $futgldn);
            $cnthn = substr($cntgl[0], 2);
            $cn_kode = 'ACN'.$cnthn.''.$cntgl[1].''.$idcn;
            //SET NOMOR CN REFUND
            $metrefundcn = $database->doQuery('INSERT INTO fu_ajk_cn_tempf SET id_cost="'.$mamet['id_cost'].'",
												   id_cn="'.$cn_kode.'",
												   id_dn="'.$mamet['id_dn'].'",
												   id_nopol="'.$mamet['id_polis'].'",
												   id_peserta="'.$mamet['id_peserta'].'",
												   id_regional="'.$mamet['regional'].'",
												   id_cabang="'.$mamet['cabang'].'",
												   premi="'.$mamet['totalpremi'].'",
												   total_claim="'.$premirefund.'",
												   tgl_claim="'.$_REQUEST['rdns'].'",
												   type_claim="Refund",
												   tgl_createcn="'.$futgldn.'",
												   tgl_byr_claim="",
												   confirm_claim="Pending",
												   input_by="'.$_SESSION['nm_user'].'",
												   input_date="'.$futgl.'"');

            $metrefunddn = $database->doQuery('UPDATE fu_ajk_peserta SET id_klaim="", status_peserta="Refund", status_aktif="Lapse" WHERE id="'.$_REQUEST['id'].'"');
            //CONFIRM EMAIL REFUND
            $Rmail = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="" AND status="UNDERWRITING"');
            while ($eRmail = mysql_fetch_array($Rmail)) {
                $metMail .=$eRmail['email'].', ';
            }
            $to = $metMail.''.$q['email'].' ';
            $subject = 'AJKOnline - Refund Approve By User';
            $message = '<html><head><title>Data Peserta Refund sudah telah dibuat oleh '.$q['nm_lengkap'].'</title></head>
				<body><table><tr><th>Data Peserta Refund telah dibuat oleh <b>'.$q['nm_lengkap'].'</b> pada tanggal '._convertDate($futgldn).'</tr></table></body></html>';
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $headers .= 'From: '.$q['email'].'' . "\r\n";
            // $headers .= 'Cc:  kepodank@gmail.com' . "\r\n";
            mail($to, $subject, $message, $headers);
            //CONFIRM CLAIM REFUND
            header("location:ajk_klaim.php?fu=setrefund");
        }
        $futglprm = date("Y-m-d");
        $mamet = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id="'.$_REQUEST['id'].'"'));
        //echo '<form method="post" action="ajk_klaim.php?fu=newrefund&eLs=Ok&r=Searching&cat='.$_REQUEST['cat'].'&subcat='.$_REQUEST['subcat'].'&nodn='.$_REQUEST['nodn'].'&rnama='.$_REQUEST['rnama'].'&rdob='.$_REQUEST['rdob'].'&met=getrefund&id='.$met['id'].'">
        echo '<form method="post" action="">
	  <input type="hidden" name="id" value="'.$_REQUEST['id'].'">
	  <center>Refund atas nama <b>'.$mamet['nama'].'</b> pada tanggal ';
        print initCalendar();
        print calendarBox('rdns', 'triger2', $futglprm);
        echo '&nbsp; <input type="submit" name="eLs" value="Ok"> &nbsp;  &nbsp; <a href="ajk_klaim.php?fu=newrefund">cancel</a></center>';
        echo '<form>';
    }
    echo '</td></tr>';

    if ($_REQUEST['x']) {
        $m = ($_REQUEST['x']-1) * 25;
    } else {
        $m = 0;
    }
    //if ($_REQUEST['r']=="Searching") {

    if ($_REQUEST['cat']) {
        $satu = 'AND id_cost = "' . $_REQUEST['cat'] . '"';
    }
    if ($_REQUEST['subcat']) {
        $dua = 'AND id_polis = "' . $_REQUEST['subcat'] . '"';
    }
    if ($_REQUEST['nodn']) {
        $tiga = 'AND id_dn LIKE "%' . $_REQUEST['nodn'] . '%"';
    }
    if ($_REQUEST['rnama']) {
        $empat = 'AND nama LIKE "%' . $_REQUEST['rnama'] . '%"';
    }
    $mydob = explode("-", $_REQUEST['rdob']);
    $mydob_ = $mydob[2].'/'.$mydob[1].'/'.$mydob[0];
    if ($_REQUEST['rdob']) {
        $lima = 'AND tgl_lahir LIKE "' . $mydob_ . '"';
    }

    $fupes = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id!="" AND id_dn !="" AND id_klaim ="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' AND status_peserta IS NULL AND status_aktif="Inforce" AND del is null ORDER BY status_bayar ASC, id_dn DESC LIMIT ' . $m . ' , 25');
    $totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_peserta WHERE id!= "" AND id_dn !="" AND id_klaim ="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' AND status_peserta IS NULL AND status_aktif="Inforce" AND del is null '));
    $totalRows = $totalRows[0];

    $_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
    while ($met = mysql_fetch_array($fupes)) {
        $pol = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$met['id_polis'].'"'));

        if ($met['status_bayar'] == 0) {
            $metstatusrefund = "UNPAID";
            $Rrefund = '';
        } else {
            $metstatusrefund = "PAID";
            $Rrefund = '<a href="ajk_klaim.php?fu=newrefund&r=Searching&cat='.$_REQUEST['cat'].'&subcat='.$_REQUEST['subcat'].'&nodn='.$_REQUEST['nodn'].'&rnama='.$_REQUEST['rnama'].'&rdob='.$_REQUEST['rdob'].'&met=getrefund&id='.$met['id'].'"><img src="image/check.png" width="30" border="0"></a>';
        }
        $cekdatadn = mysql_fetch_array($database->doQuery('SELECT id, dn_kode, tgl_createdn FROM fu_ajk_dn WHERE id="'.$met['id_dn'].'"'));
        if (($no % 2) == 1) {
            $objlass = 'tbl-odd';
        } else {
            $objlass = 'tbl-even';
        }
        echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		  <td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
		  <td align="center">'.$pol['nmproduk'].'</td>
		  <td align="center">'.$met['id_peserta'].'</td>
		  <td align="center">'.$cekdatadn['dn_kode'].'</td>
		  <td>'.$met['nama'].'</td>
		  <td align="center">'._convertDate($met['tgl_lahir']).'</td>
		  <td align="center">'.$met['usia'].'</td>
		  <td align="right">'.duit($met['kredit_jumlah']).'</td>
		  <td align="center">'._convertDate($met['kredit_tgl']).'</td>
		  <td align="center">'.$met['kredit_tenor'].'</td>
		  <td align="center">'._convertDate($met['kredit_akhir']).'</td>
		  <td align="right">'.duit($met['totalpremi']).'</td>
		  <td align="center">'.$metstatusrefund.'</td>
		  <td align="center">'.strtoupper($met['cabang']).'</td>
		  <td align="center">'.$Rrefund.'</td>
		  </tr>';
    }
    echo '<tr><td colspan="22">';
    echo createPageNavigations($file = 'ajk_klaim.php?fu=newrefund&r=Searching&cat='.$_REQUEST['cat'].'&subcat='.$_REQUEST['subcat'].'&nodn='.$_REQUEST['nodn'].'&rnama='.$_REQUEST['rnama'].'&rdob='.$_REQUEST['rdob'].'', $total = $totalRows, $psDeh = 10, $anchor = '', $perPage = 25);
    echo '<b>Total Data Peserta: <u>' . $totalRows . '</u></b></td></tr>';
}
echo '</table>';
        ;
        break;

    case "IDrefund":
echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th colspan="8" width="100%" align="left" colspan="2">Modul Movement - View Data Refund</font></th><th width="5%" colspan="2"><a href="ajk_klaim.php?fu=refund"><img src="image/Backward-64.png" width="20"></a></th></tr>
</table><br />';
if ($_REQUEST['ope']=="Refund") {
    if (isset($_POST['submit']) && ($_POST['idRef'])) {
        for ($i=0; $i<count($_POST['idRef']);$i++) {
            $cn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cn ORDER BY id DESC'));
            $metidcn = explode(" ", $cn['input_date']);
            $metidthncn = explode("-", $metidcn[0]);
            if ($metidthncn[0] < $futgliddn) {
                $metautocn = 1;
            } else {
                $metautocn = $cn['idC'] + 1;
            }

            $idcnnya = 100000000 + $metautocn;
            $idcn = substr($idcnnya, 1);
            $cntgl = explode("-", $futgldn);
            $cnthn = substr($cntgl[0], 2);
            $cn_kode = 'AJKCN-'.$cnthn.'-'.$cntgl[1].'-'.$idcn;
            ;

            $cekRefnew = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id="'.$_REQUEST['id'].'"'));	//NAMA PESERTA BARU
            $cekRefOld = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id="'.$_REQUEST['idRef'][$i].'"'));	//NAMA PESERTA LAMA

            $awal = explode("/", $cekRefOld['kredit_tgl']);
            $hari = $awal[0];
            $bulan = $awal[1];
            $tahun = $awal[2];

            $akhir = explode("/", $cekRefnew['kredit_tgl']);
            $hari2 = $akhir[0];
            $bulan2 = $akhir[1];
            $tahun2 = $akhir[2];

            $jhari=(mktime(0, 0, 0, $bulan2, $hari2, $tahun2) - mktime(0, 0, 0, $bulan, $hari, $tahun))/86400;
            $sisahr=floor($jhari);
            $sisabulan =ceil($sisahr / 30.4375);
            $masisa = $cekRefOld['kredit_tenor'] - $sisabulan;
            $hitungcn = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_rasio_claim WHERE id_cost="'.$cekRefOld['id_cost'].'" AND id_polis="'.$cekRefOld['id_polis'].'"'));
            $jumlahnya = (($masisa / $cekRefOld['kredit_tenor']) * $hitungcn['restruktur']) * $cekRefOld['premi'];

            $Rcn = $database->doQuery('INSERT INTO fu_ajk_cn SET id_cost="'.$cekRefnew['id_cost'].'",
												   idC="'.$metautocn.'",
												   id_cn="'.$cn_kode.'",
												   id_dn="'.$cekRefOld['id_dn'].'",
												   id_nopol="'.$cekRefnew['id_polis'].'",
												   id_peserta="'.$cekRefnew['id_peserta'].'",
												   id_regional="'.$cekRefnew['regional'].'",
												   id_cabang="'.$cekRefnew['cabang'].'",
												   premi="'.$cekRefnew['premi'].'",
												   total_claim="'.$jumlahnya.'",
												   tgl_claim="'.$cekRefnew['kredit_tgl'].'",
												   type_claim="'.$cekRefnew['status_peserta'].'",
												   confirm_claim="Approve(paid)",
												   tgl_createcn="'.$futgldn.'",
												   input_by="'.$_SESSION['nm_user'].'",
												   input_date="'.$futgl.'"');
            $rCN = $database->doQuery('UPDATE fu_ajk_peserta SET id_klaim="'.$cn_kode.'" WHERE id="'.$cekRefnew['id'].'"');
        }
        header('location:ajk_klaim.php?fu=IDrefund&idRef='.$_REQUEST['id'].'');
    } else {
        echo '<center><font color="red">Silahkan ceklist data peserta yang akan di Refund. !<a href="ajk_klaim.php?fu=IDrefund&idRef='.$_REQUEST['id'].'">Back</a></center></font>';
    }
}
echo '<table border="0" width="100%" cellpadding="5" cellspacing="1">
<form method="post" action="ajk_klaim.php?fu=IDrefund&ope=Refund&id='.$_REQUEST['idRef'].'">
<th rowspan="2" width="5%">No.SPAJ</td>
<th rowspan="2" width="10%">ID DN</td>
<th rowspan="2" width="10%">IC DN</td>
<th rowspan="2">Name</td>
<th rowspan="2" width="5%">DOB</td>
<th colspan="3"width="10%">Status Credit</td>
<th rowspan="2" width="5%">Premi</td>
<th rowspan="2" width="5%">Date Movement</td>
<th rowspan="2" width="7%">Jumlah</td>
<th rowspan="2" width="1%">Confirm</td>
</tr>
<tr><th>Date</th><th>U P</th><th>Tenor</th></tr>';
echo $_REQUEST['idRef'];
$metref = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id="'.$_REQUEST['idRef'].'" AND id_dn=""'));
$metcnnya = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cn WHERE id_cn="'.$metref['id_klaim'].'"'));
if ($metref['id_klaim']=="") {
    $submitrefund = '<input type="submit" name="submit" value="Submit" />';
} else {
    $submitrefund ='CN create';
}
echo '<tr bgcolor="#bde0e6"><td align="center">'.$metref['spaj'].'</td>
		<td align="center">'.$metref['id_dn'].'</td>
		<td align="center">'.$metref['id_klaim'].'</td>
		<td>'.$metref['nama'].'</a></td>
		<td>'.$metref['tgl_lahir'].'</td>
		<td>'.$metref['kredit_tgl'].'</td>
		<td>'.duit($metref['kredit_jumlah']).'</td>
		<td align="center">'.$metref['kredit_tenor'].'</td>
		<td>'.duit($metref['premi']).'</td>
		<td align="center">'.$metref['kredit_tgl'].'</td>
		<td align="right">'.duit($metcnnya['total_claim']).'</td>
		<td align="center">'.$submitrefund.'</td>
</tr>';
if ($metref['spaj']!="") {
    $rRefund = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE spaj="'.$metref['spaj'].'" AND id_dn!=""');
} else {
    $rRefund = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE nama="'.$metref['nama'].'" AND tgl_lahir="'.$metref['tgl_lahir'].'" AND id_dn!=""');
}

while ($mamet = mysql_fetch_array($rRefund)) {
    echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		<td align="center">'.$mamet['spaj'].'</td>
		<td align="center">'.$mamet['id_dn'].'</td>
		<td align="center">'.$mamet['id_klaim'].'</td>
		<td>'.$mamet['nama'].'</a></td>
		<td>'.$mamet['tgl_lahir'].'</td>
		<td>'.$mamet['kredit_tgl'].'</td>
		<td>'.duit($mamet['kredit_jumlah']).'</td>
		<td align="center">'.$mamet['kredit_tenor'].'</td>
		<td>'.duit($mamet['premi']).'</td>
		<td align="center">&nbsp;</td>
		<td align="right">'.duit($mamet['totalpremi']).'</td>
		<td align="center"><input type="checkbox" name="idRef[]" id="checkbox" value="'.$mamet['id'].'"></td>
</tr>';
}
echo '</form></table>';

    ;
    break;

    case "hapusmove":
			/*
			if ($_REQUEST['met']=="dellrestruktur") {
			$r = mysql_fetch_array($database->doQuery('UPDATE fu_ajk_peserta SET del="1", update_by="'.$q['nm_user'].'", update_time="'.$futgl.'" WHERE id="'.$_REQUEST['id'].'"'));
			header("location:ajk_klaim.php?fu=restruktur");
			}

			if ($_REQUEST['met']=="dellbaloon") {
			$r = mysql_fetch_array($database->doQuery('UPDATE fu_ajk_peserta SET del="1", update_by="'.$q['nm_user'].'", update_time="'.$futgl.'" WHERE id="'.$_REQUEST['id'].'"'));
			header("location:ajk_klaim.php?fu=resbaloon");
			}

			if ($_REQUEST['met']=="delltopup") {
			$r = mysql_fetch_array($database->doQuery('UPDATE fu_ajk_peserta SET del="1", update_by="'.$q['nm_user'].'", update_time="'.$futgl.'" WHERE id="'.$_REQUEST['id'].'"'));
			header("location:ajk_klaim.php?fu=topup");
			}
			*/

			if ($_REQUEST['met']=="batalrefund") {
			    $refund = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cn_tempf WHERE id="'.$_REQUEST['id'].'"'));
			    $r = $database->doQuery('UPDATE fu_ajk_peserta SET id_klaim="", status_aktif="Inforce", status_peserta="" WHERE id_dn="'.$refund['id_dn'].'" AND id_peserta="'.$refund['id_peserta'].'"');
			    $rrefundcn = $database->doQuery('UPDATE fu_ajk_cn_tempf SET del="1", update_by="'.$q['nm_user'].'", update_time="'.$futgl.'" WHERE id="'.$_REQUEST['id'].'"');
			    header("location:ajk_klaim.php?fu=setrefund");
			};
    break;

    case "er_batal":
$met_jum_batal = mysql_fetch_array($database->doQuery('SELECT COUNT(id_peserta) AS jData FROM fu_ajk_cn_tempf WHERE type_claim="Batal" AND confirm_claim="Approve" AND del IS NULL'));
if ($met_jum_batal['jData'] <= 0) {
    $setBatal = '';
} else {
    $setBatal = '<th width="5%" colspan="2"><a title="Buat data CN Batal" href="ajk_klaim.php?fu=setDataBatal"><img src="image/rmf_1.png" width="25"></a></th>';
}

echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
		<tr><th colspan="8" width="100%" align="left" colspan="2">Modul Klaim - Data Batal</font></th>
			'.$setBatal.'
			<!--<th width="5%" colspan="2"><a href="ajk_klaim.php?fu=newrefund"><img src="image/new.png" width="25"></a></th>-->
		</tr>
	  </table><br />';
echo '<fieldset>
	<legend>Searching</legend>
	<form method="post" action="">
	<table border="0" width="100%" cellpadding="1" cellspacing="0" align="center">
		<tr><td width="5%">Nomor DN</td><td width="20%">: <input type="text" name="nodn" value="'.$_REQUEST['nodn'].'"></td></tr>
		<tr><td width="5%">Nomor CN</td><td width="20%">: <input type="text" name="nocn" value="'.$_REQUEST['nocn'].'"></td></tr>
		<tr><td width="5%">Nama</td><td>: <input type="text" name="rnama" value="'.$_REQUEST['rnama'].'"></td></tr>
		<tr><td align="center"><input type="submit" name="oppe" value="Searching" class="button"></td></tr>
	</table>
	</form></fieldset>';
if ($_REQUEST['nodn']) {
    $satu = 'AND fu_ajk_cn.id_dn LIKE "%' . $_REQUEST['nodn'] . '%"';
}
if ($_REQUEST['nocn']) {
    $dua = 'AND fu_ajk_cn.id_cn LIKE "%' . $_REQUEST['nocn'] . '%"';
}
if ($_REQUEST['rnama']) {
    $tiga = 'AND fu_ajk_peserta.nama LIKE "%' . $_REQUEST['rnama'] . '%"';
}
if ($_REQUEST['rdob']) {
    $empat = 'AND tgl_lahir LIKE "%' . $_REQUEST['rdob'] . '%"';
    $dobnya = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_klaim !="" '.$empat.' '));
    $erdob = 'AND tgl_lahir LIKE "%' . $dobnya['tgl_lahir'] . '%"';
}

echo '<table border="0" width="100%" cellpadding="5" cellspacing="1" bgcolor="#bde0e6">
<tr><th width="3%">No</td>
	<th width="5%">Produk</td>
	<th width="5%">Mitra</td>
	<th width="5%">ID Peserta</td>
	<th width="8%">ID DN</td>
	<th width="8%">ID CN</td>
	<th>Name</td>
	<th width="5%">DOB</td>
	<th width="5%">Tgl Kredit</td>
	<th width="1%">Tenor</td>
	<th width="5%">Akhir Kredit</td>
	<th width="5%">Tgl Refund</td>
	<th width="3%">MA - J</td>
	<th width="3%">MA - S</td>
	<th width="7%">Premi Bank</td>
	<th width="5%">Refund Bank</td>
	<th width="5%">Status</td>
	<th width="5%">Cabang</td>
	<th width="5%">Form Batal</td>
</tr>';
if ($_REQUEST['x']) {
    $m = ($_REQUEST['x']-1) * 25;
} else {
    $m = 0;
}
//$mklaim = $database->doQuery('SELECT * FROM fu_ajk_cn WHERE type_claim="Batal" AND id_cn !="" '.$satu.' '.$dua.' '.$ernama.' '.$erdob.' AND del IS NULL ORDER BY tgl_createcn DESC LIMIT ' . $m . ' , 25');
//$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_cn WHERE id != "" AND type_claim="Batal" AND id_cn !="" '.$satu.' '.$dua.' '.$ernama.' '.$erdob.' AND del IS NULL'));
$mklaim = $database->doQuery('SELECT
fu_ajk_polis.nmproduk,
fu_ajk_dn.dn_kode,
fu_ajk_cn.id,
fu_ajk_cn.id_cn,
fu_ajk_cn.id_peserta,
fu_ajk_cn.id_cabang,
fu_ajk_cn.tgl_createcn,
fu_ajk_cn.tgl_claim,
fu_ajk_cn.tgl_byr_claim,
fu_ajk_cn.type_claim,
fu_ajk_cn.premi,
fu_ajk_cn.total_claim,
fu_ajk_cn.confirm_claim,
IF(fu_ajk_peserta.type_data="SPK",fu_ajk_peserta.kredit_tenor * 12, fu_ajk_peserta.kredit_tenor) AS kredit_tenor,
fu_ajk_peserta.nama_mitra,
fu_ajk_peserta.nama,
fu_ajk_peserta.ket,
fu_ajk_peserta.tgl_lahir,
fu_ajk_peserta.usia,
fu_ajk_peserta.kredit_tgl,
fu_ajk_peserta.kredit_akhir,
fu_ajk_peserta.totalpremi,
fu_ajk_grupproduk.nmproduk AS mitra
FROM fu_ajk_cn
INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id = fu_ajk_peserta.id_klaim
INNER JOIN fu_ajk_grupproduk ON fu_ajk_peserta.nama_mitra = fu_ajk_grupproduk.id
WHERE fu_ajk_cn.type_claim = "Batal" AND fu_ajk_cn.del IS NULL AND fu_ajk_cn.id_cn !="" '.$satu.' '.$dua.' '.$tiga.'
ORDER BY fu_ajk_cn.tgl_createcn DESC
LIMIT ' . $m . ' , 25');
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(fu_ajk_cn.id) FROM fu_ajk_cn
INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id = fu_ajk_peserta.id_klaim
INNER JOIN fu_ajk_grupproduk ON fu_ajk_peserta.nama_mitra = fu_ajk_grupproduk.id
WHERE fu_ajk_cn.id_cn != "" AND fu_ajk_cn.type_claim="Batal" '.$satu.' '.$dua.' '.$tiga.' AND fu_ajk_cn.del IS NULL'));
$totalRows = $totalRows[0];
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while ($metklaim = mysql_fetch_array($mklaim)) {
    //$cek = $database->doQuery('UPDATE fu_ajk_peserta SET status_peserta="Death" WHERE id_peserta="'.$metklaim['id_peserta'].'"');
    //$metpeserta = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_dn="'.$metklaim['id_dn'].'" AND id_klaim="'.$metklaim['id'].'" AND id_peserta="'.$metklaim['id_peserta'].'" '));
    //$met_dnnya = mysql_fetch_array($database->doQuery('SELECT id, dn_kode FROM fu_ajk_dn WHERE id="'.$metklaim['id_dn'].'"'));

    //MASA ASURANSI BERJALAN
    $met_Date = datediff($metklaim['tgl_claim'], $metklaim['kredit_tgl']);
    //echo $fudata['tgl_claim'].' - '.$met_peserta['kredit_tgl'].' - '.$met_Date.'<br />';
    $met_Date_ = explode(",", $met_Date);
    if ($met_Date_[0] < 0) {
        $thnbln = '';
    } else {
        $thnbln = $met_Date_[0] * 12;
    }
    $sisabulan = $met_Date_[1] + $thnbln;
    //MASA ASURANSI BERJALAN

    //MASA ASURANSI SISA
    $masisa = $metklaim['kredit_tenor'] - $sisabulan;
    //MASA ASURANSI SISA

    $er_nett = $metklaim['totalpremi'] - $metklaim['total_claim'];
    if ($metklaim['confirm_klaim']=="Processing") {
        $cekconfirm = '<img src="image/edit3.png">';
    } else {
        $cekconfirm = '<img src="image/edit1.png">';
    }

    $formbatal = explode("|", $metklaim['ket']);
    $fileform = '<a href="../ajk_file/_spak/'.$formbatal[1].'" target="_blank">Print</a>';

    if (($no % 2) == 1) {
        $objlass = 'tbl-odd';
    } else {
        $objlass = 'tbl-even';
    }
    echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
			<td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
			<td align="center">'.$metklaim['nmproduk'].'</td>
			<td align="center">'.$metklaim['mitra'].'</td>
			<td align="center">'.$metklaim['id_peserta'].'</td>
			<td align="center"><a href="../aajk_report.php?er=_kwipeserta&idn='.$met_dnnya['id'].'" target="_blank">'.substr($metklaim['dn_kode'], 3).'</a></td>
			<td align="center">'.substr($metklaim['id_cn'], 3).'</td>
			<td><a href="../aajk_report.php?er=_eRefund&idC='.$metklaim['id'].'" target="_blank">'.strtoupper($metklaim['nama']).'</a></td>
			<td align="center">'._convertDate($metklaim['tgl_lahir']).'</td>
			<td align="center">'._convertDate($metklaim['kredit_tgl']).'</td>
			<td align="center">'.$metklaim['kredit_tenor'].'</td>
			<td align="center">'._convertDate($metklaim['kredit_akhir']).'</td>
			<td align="center">'._convertDate($metklaim['tgl_claim']).'</td>
			<td align="center">'.$sisabulan.'</td>
			<td align="center">'.$masisa.'</td>
			<td align="right">'.duit($metklaim['totalpremi']).'</td>
			<td align="right">'.duit($metklaim['total_claim']).'</td>
			<td align="right">'.$metklaim['confirm_claim'].'</td>
			<td>'.strtoupper($metklaim['id_cabang']).'</td>
			<td align="center">'.$fileform.'</td>
			</tr>';
}
echo '<tr><td colspan="22">';
echo createPageNavigations($file = 'ajk_klaim.php?fu=refund&nodn='.$_REQUEST['nodn'].'&nocn='.$_REQUEST['nocn'].'&rnama='.$namanya['rnama'].'', $total = $totalRows, $psDeh = 10, $anchor = '', $perPage = 25);
echo '<b>Total Data Batal: <u>' . $totalRows . '</u></b></td></tr>';
echo '</table>';
    ;
        break;

case "setDataBatal":
    echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
			<tr><th colspan="8" width="100%" align="left" colspan="2">Modul Klaim - Data Batal</font></th>
				<th width="5%" colspan="2"><a href="ajk_klaim.php?fu=er_batal"><img src="image/back.png" width="25"></a></th>
			</tr>
		  </table><br />';
    if ($_REQUEST['met']=="tolakDTbatal") {
        //echo $_REQUEST['id'];
        //echo '<br />';
        $metCekData = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cn_tempf WHERE id="'.$_REQUEST['id'].'"'));
        //echo $metCekData['input_by'].'<br />';
        $metBatalCNTemp = $database->doQuery('UPDATE fu_ajk_cn_tempf SET del="1", update_by="'.$q['nm_user'].'", update_time="'.$futgl.'" WHERE id="'.$_REQUEST['id'].'"');
        $metBatalPeserta = $database->doQuery('UPDATE fu_ajk_peserta SET ket=null, status_peserta=null, update_by="'.$q['nm_user'].'", update_time="'.$futgl.'" WHERE id_cost="'.$metCekData['id_cost'].'" AND id_polis="'.$metCekData['id_nopol'].'" AND id_peserta="'.$metCekData['id_peserta'].'"');
        $metBatalPesertaView = mysql_fetch_array($database->doQuery('SELECT id_cost, id_polis, id_peserta, nama FROM  fu_ajk_peserta WHERE id_cost="'.$metCekData['id_cost'].'" AND id_polis="'.$metCekData['id_nopol'].'" AND id_peserta="'.$metCekData['id_peserta'].'"'));
        $cekEmailClient = mysql_fetch_array($database->doQuery('SELECT id, id_cost, nm_lengkap, nm_user, email FROM pengguna WHERE id_cost="'.$metCekData['id_cost'].'" AND nm_user="'.$metCekData['input_by'].'"'));
        $cekEmailSPV = mysql_fetch_array($database->doQuery('SELECT id, id_cost, nm_lengkap, nm_user, email FROM pengguna WHERE id_cost="'.$metCekData['id_cost'].'" AND level="6"'));
        //echo $cekEmailClient['email'].'<br />';
        //echo $cekEmailSPV['email'];
        $mail = new PHPMailer; // call the class
		    $mail->IsSMTP();
		    $mail->Host = SMTP_HOST; //Hostname of the mail server
		    $mail->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
	      $mail->Username = SMTP_UNAME; //Username for SMTP authentication any valid email created in your domain
	      $mail->Password = SMTP_PWORD; //Password for SMTP authentication
	      $mail->AddReplyTo = SMTP_REPLYMAIL; //reply-to address
	      $mail->debug = 1;
		    $mail->SMTPSecure = "ssl";
		    $mail->IsHTML(true);

        $mail->SetFrom($q['email'], $q['nm_lengkap']);
        $mail->Subject = "AJKOnline - DATA PEMBATALAN DITOLAK";
        //EMAIL PENERIMA KANTOR U/W
        $mail->AddAddress($cekEmailClient['email'], $cekEmailClient['nm_lengkap']); //To address who will receive this email
        $mail->AddAddress($cekEmailSPV['email'], $cekEmailSPV['nm_lengkap']); //To address who will receive this email
        $message = "To ".$cekEmailClient['nm_lengkap'].", <br /> Data penolakan pembatalan peserta :
				<table border=0 width=100%>
				<tr><td width=10%>ID Peserta</td><td>: ".$metBatalPesertaView['id_peserta']."</td></tr>
				<tr><td>Nama Peserta</td><td>: ".$metBatalPesertaView['nama']."</td></tr>
				<tr><td colspan=2>Data pembatalan peserta telah di tolak oleh ".$q['nm_lengkap']." pada tanggal "._convertDate($futgldn).".</td></tr>
				</table>";
        $mail->AddCC("penting_ga@hotmail.com");
        $mail->MsgHTML($message); //Put your body of the message you can place html code here
        $send = $mail->Send(); //Send the mails
        //echo $message.'<br />';
        echo '<center>Penolakan data pembatalan peserta telah disetujui oleh <b>'.$_SESSION['nm_user'].'</b>.<br /><meta http-equiv="refresh" content="2; url=ajk_klaim.php?fu=er_batal"></center>';
    }
    /* REVISI QUERY STATUS APPROVE DARI SPV PUSAT BARU DAPAT DIPROSES OLEH UW 18 11 2015
    $metDataBatal = $database->doQuery('SELECT * FROM fu_ajk_cn_tempf WHERE type_claim="Batal" AND confirm_claim="Processing" AND del IS NULL');
    */
    $metDataBatal = $database->doQuery('SELECT * FROM fu_ajk_cn_tempf WHERE type_claim="Batal" AND confirm_claim="Approve" AND del IS NULL');
    echo '<form method="post" action="ajk_klaim.php?fu=createcnbatal">
		<table border="0" width="100%" cellpadding="5" cellspacing="1" bgcolor="#bde0e6">
		<tr><th width="1%">Hapus</th>
			<th width="1%"><input type="checkbox" id="selectall"/></th>
			<th width="3%">No</td>
			<th width="1%">ID Peserta</td>
			<th width="8%">ID DN</td>
			<th>Name</td>
			<th width="6%">Tgl Lahir</td>
			<th width="6%">Tgl Akad</td>
			<th width="1%">Tenor</td>
			<th width="6%">Tgl Akhir</td>
			<th width="6%">Tgl Batal</td>
			<th width="5%">Total Premi</td>
			<th width="6%">Total Refund</td>
			<th width="8%">Regional</td>
			<th width="5%">Cabang</td>
			<th width="5%">User</td>
			<th width="5%">Approve</td>
		</tr>';
    while ($_metDataBatal = mysql_fetch_array($metDataBatal)) {
        $met_pesertaBatal = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_cost="'.$_metDataBatal['id_cost'].'" AND id_polis="'.$_metDataBatal['id_nopol'].'" AND id_dn="'.$_metDataBatal['id_dn'].'" AND id_peserta="'.$_metDataBatal['id_peserta'].'" AND status_peserta="App_Batal"'));
        $met_DNbatal= mysql_fetch_array($database->doQuery('SELECT id, dn_kode FROM fu_ajk_dn WHERE id="'.$_metDataBatal['id_dn'].'"'));
        if (($no % 2) == 1) {
            $objlass = 'tbl-odd';
        } else {
            $objlass = 'tbl-even';
        }
        echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
			  <td align="center"><a title="Tolak data pembatalan" href="ajk_klaim.php?fu=setDataBatal&met=tolakDTbatal&id='.$_metDataBatal['id'].'" onClick="if(confirm(\'Anda yakin akan menolak data pembatalan peserta ini ?\')){return true;}{return false;}"><img border="0" src="image/delete.gif" width="20"></a></td>
			  <td><input type="checkbox" class="case" name="btlnama[]" value="'.$_metDataBatal['id'].'"></td>
			  <td align="center" valign="top">'.(++$no).'</td>
			  <td>'.$_metDataBatal['id_peserta'].'</td>
			  <td>'.$met_DNbatal['dn_kode'].'</td>
			  <td>'.$met_pesertaBatal['nama'].'</td>
			  <td>'.$met_pesertaBatal['tgl_lahir'].'</td>
			  <td>'._convertDate($met_pesertaBatal['kredit_tgl']).'</td>
			  <td>'.$met_pesertaBatal['kredit_tenor'].'</td>
			  <td>'._convertDate($met_pesertaBatal['kredit_akhir']).'</td>
			  <td>'._convertDate($_metDataBatal['tgl_claim']).'</td>
			  <td>'.duit($met_pesertaBatal['totalpremi']).'</td>
			  <td>'.duit($_metDataBatal['total_claim']).'</td>
			  <td>'.$_metDataBatal['id_regional'].'</td>
			  <td>'.$_metDataBatal['id_cabang'].'</td>
			  <td>'.$_metDataBatal['input_by'].'</td>
			  <td>'.$_metDataBatal['update_by'].'</td>
		  </tr>';
    }
    if ($q['id_cost']=="" and $q['status']=="UNDERWRITING" or $q['status']=="") {
        echo '<tr><td colspan="17" align="center"><a href="ajk_klaim.php?fu=createcnbatal" onClick="if(confirm(\'Apakah anda sudah yakin dengan semua data peserta Batal ini ?\')){return true;}{return false;}"><input type="submit" name="databatal" Value="Buat Data Batal"></a></td></tr>';
    } else {
        echo '';
    }
    echo '</table></form>';
        ;
break;

case "createcnbatal":
    foreach ($_REQUEST['btlnama'] as $r => $eL) {
        $metCNbatal_temp = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cn_tempf WHERE id="'.$eL.'"'));

        //SET NOMOR CN//
        $metCNbatal = mysql_fetch_array($database->doQuery('SELECT id, idC, tgl_createcn, input_date FROM fu_ajk_cn ORDER BY id DESC'));

        $thnInputDate = explode(" ", $metCNbatal['input_date']);
        $thnInput = explode("-", $thnInputDate);
        if ($thnInput[0] < $dateY) {
            $idC = 1;
        } else {
            $idC = $metCNbatal['idC'] + 1;
        }

        $idcnnya = 10000000000 + $idC;
        $idcn = substr($idcnnya, 1);
        $cntgl = explode("-", $futgldn);
        $cnthn = substr($cntgl[0], 2);
        $cn_kode = 'ACN'.$cnthn.''.$cntgl[1].''.$idcn;
        //SET NOMOR CN//

        //UPDATE PREMI DN//
        $metUpdatePremi = $database->doQuery('UPDATE fu_ajk_dn SET totalpremi = totalpremi - "'.$metCNbatal_temp['total_claim'].'" WHERE id="'.$metCNbatal_temp['id_dn'].'" ');
        //UPDATE PREMI DN//

        $metBatal = $database->doQuery('INSERT INTO fu_ajk_cn SET idC="'.$idC.'",
																  id_cn="'.$cn_kode.'",
																  id_dn="'.$metCNbatal_temp['id_dn'].'",
																  id_cost="'.$metCNbatal_temp['id_cost'].'",
																  id_nopol="'.$metCNbatal_temp['id_nopol'].'",
																  id_peserta="'.$metCNbatal_temp['id_peserta'].'",
																  id_regional="'.$metCNbatal_temp['id_regional'].'",
																  id_cabang="'.$metCNbatal_temp['id_cabang'].'",
																  tgl_createcn="'.$futgldn.'",
																  tgl_claim="'.$metCNbatal_temp['tgl_claim'].'",
																  type_claim="'.$metCNbatal_temp['type_claim'].'",
																  premi="'.$metCNbatal_temp['premi'].'",
																  total_claim="'.$metCNbatal_temp['total_claim'].'",
																  confirm_claim="Approve(unpaid)",
																  validasi_cn_uw="ya",
																  input_by="'.$q['nm_user'].'",
																  input_date="'.$futgl.'" ');

        // $arap_produksi_cn = $database->doQuery('insert into CMS_ArAp_Master(
								// 				fArAp_No
								// 				,fArAp_Status
								// 				,fArAp_TransactionCode
								// 				,fArAp_TransactionDate
								// 				,fArAp_Customer_Id
								// 				,fArAp_Customer_Nm
								// 				,fArAp_ReferenceNo1_1
								// 				,fArAp_ReferenceNo1_2
								// 				,fArAp_ReferenceNo1_3
								// 				,fArAp_Note
								// 				,fArAp_CrrencyCode
								// 				,fArAp_AmmountTotal
								// 				,input_by
								// 				,input_date) SELECT
								// 				REPLACE(fu_ajk_cn.id_cn,"CNA","DNA"),
								// 				"A",
								// 				"AR-02",
								// 				current_date(),
								// 				fu_ajk_asuransi.`code`,
								// 				fu_ajk_asuransi.`name`,
								// 				fu_ajk_costumer.`name`,
								// 				fu_ajk_cn.id_cn,
								// 				"",
								// 				fu_ajk_cn.noperkredit,
								// 				"IDR",
								// 				fu_ajk_cn.premi,
								// 				"' . $_SESSION['nm_user'] . '",
								// 				current_timestamp()
								// 				FROM
								// 				fu_ajk_cn
								// 				INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
								// 				INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
								// 				INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
								// 				INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta AND fu_ajk_cn.id = fu_ajk_peserta.id_klaim
								// 				INNER JOIN fu_ajk_costumer ON fu_ajk_cn.id_cost = fu_ajk_costumer.id
								// 				LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
								// 				where
								// 				fu_ajk_cn.type_claim = "Batal" AND fu_ajk_cn.confirm_claim <> "Pending" AND fu_ajk_cn.del IS NULL
								// 				and fu_ajk_cn.id_peserta="' . $metCNbatal_temp['id_peserta'] . '"');


        // $arap_produksi_dn = $database->doQuery('insert into CMS_ArAp_Master(
								// 				fArAp_No
								// 				,fArAp_Status
								// 				,fArAp_TransactionCode
								// 				,fArAp_TransactionDate
								// 				,fArAp_Customer_Id
								// 				,fArAp_Customer_Nm
								// 				,fArAp_ReferenceNo1_1
								// 				,fArAp_ReferenceNo1_2
								// 				,fArAp_ReferenceNo1_3
								// 				,fArAp_Note
								// 				,fArAp_CrrencyCode
								// 				,fArAp_AmmountTotal
								// 				,input_by
								// 				,input_date) SELECT
								// 				fu_ajk_cn.id_cn,
								// 				"A",
								// 				"AP-02",
								// 				current_date(),
								// 				fu_ajk_costumer.kd_databank,
								// 				fu_ajk_costumer.`name`,
								// 				fu_ajk_polis.nmproduk,
								// 				fu_ajk_cn.id_cabang,
								// 				"",
								// 				fu_ajk_cn.noperkredit,
								// 				"IDR",
								// 				fu_ajk_cn.premi+fu_ajk_peserta_as.nettpremi,
								// 				"' . $_SESSION['nm_user'] . '",
								// 				current_timestamp()
								// 				FROM
								// 				fu_ajk_cn
								// 				INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
								// 				INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
								// 				INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
								// 				INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta AND fu_ajk_cn.id = fu_ajk_peserta.id_klaim
								// 				INNER JOIN fu_ajk_costumer ON fu_ajk_cn.id_cost = fu_ajk_costumer.id

								// 				INNER JOIN fu_ajk_peserta_as ON fu_ajk_peserta.id_peserta = fu_ajk_peserta_as.id_peserta AND fu_ajk_peserta.id_dn = fu_ajk_peserta_as.id_dn

								// 				LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
								// 				where
								// 				fu_ajk_cn.type_claim = "Batal" AND fu_ajk_cn.confirm_claim <> "Pending" AND fu_ajk_cn.del IS NULL
								// 				and fu_ajk_cn.id_peserta="' . $metCNbatal_temp['id_peserta'] . '"');


        // $arap_produksi_detail_dn = $database->doQuery('insert into CMS_ArAp_Detail(
								// 				fArAp_No
								// 				,fArAp_Counter
								// 				,fArAp_BMaretialCode
								// 				,fArAp_Description
								// 				,fArAp_Amount
								// 				,input_by
								// 				,input_date)
								// 				SELECT
								// 				REPLACE(fu_ajk_cn.id_cn,"CNA","DNA"),
								// 				1,
								// 				"BTL",
								// 				"Penerimaan Pembayaran Pembatalan dari Asuransi",
								// 				fu_ajk_cn.premi,
								// 				"' . $_SESSION['nm_user'] . '",
								// 				current_timestamp()
								// 				FROM
								// 				fu_ajk_cn
								// 				INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
								// 				INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
								// 				INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
								// 				INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta AND fu_ajk_cn.id = fu_ajk_peserta.id_klaim
								// 				INNER JOIN fu_ajk_costumer ON fu_ajk_cn.id_cost = fu_ajk_costumer.id
								// 				LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
								// 				where
								// 				fu_ajk_cn.type_claim = "Batal" AND fu_ajk_cn.confirm_claim <> "Pending" AND fu_ajk_cn.del IS NULL
								// 				and fu_ajk_cn.id_peserta="' . $metCNbatal_temp['id_peserta'] . '"');

        // $arap_produksi__detail_cn = $database->doQuery('insert into CMS_ArAp_Detail(
								// 				fArAp_No
								// 				,fArAp_Counter
								// 				,fArAp_BMaretialCode
								// 				,fArAp_Description
								// 				,fArAp_Amount
								// 				,input_by
								// 				,input_date)
								// 				SELECT
								// 				fu_ajk_cn.id_cn,
								// 				1,
								// 				"BTL",
								// 				"Pembayaran Premi Pembatalan Ke Bank",
								// 				fu_ajk_cn.premi+fu_ajk_peserta_as.nettpremi,
								// 				"' . $_SESSION['nm_user'] . '",
								// 				current_timestamp()
								// 				FROM
								// 				fu_ajk_cn
								// 				INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
								// 				INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
								// 				INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
								// 				INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta AND fu_ajk_cn.id = fu_ajk_peserta.id_klaim
								// 				INNER JOIN fu_ajk_costumer ON fu_ajk_cn.id_cost = fu_ajk_costumer.id

								// 				INNER JOIN fu_ajk_peserta_as ON fu_ajk_peserta.id_peserta = fu_ajk_peserta_as.id_peserta AND fu_ajk_peserta.id_dn = fu_ajk_peserta_as.id_dn

								// 				LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
								// 				where
								// 				fu_ajk_cn.type_claim = "Batal" AND fu_ajk_cn.confirm_claim <> "Pending" AND fu_ajk_cn.del IS NULL
								// 				and fu_ajk_cn.id_peserta="' . $metCNbatal_temp['id_peserta'] . '"');


        // $arap_detail_dn=$database->doQuery('insert into CMS_ArAp_Referensi(
								// 									fArAp_No
								// 									,fArAp_Counter
								// 									,fArAp_CoreCode
								// 									,fArAp_BMaretialCode
								// 									,fArAp_RefMemberID
								// 									,fArAp_RefMemberNm
								// 									,fArAp_RefDescription
								// 									,fArAp_RefAmount
								// 									, input_by
								// 									, input_date)
								// 									SELECT
								// 									REPLACE(fu_ajk_cn.id_cn,"CNA","DNA")
								// 									, "1"
								// 									, "BTL"
								// 									, "BTL"
								// 									, fu_ajk_cn.id_peserta
								// 									, fu_ajk_peserta.nama
								// 									, ""
								// 									, fu_ajk_cn.premi
								// 									, "' . $_SESSION['nm_user'] . '"
								// 									, current_timestamp()
								// 									FROM
								// 									fu_ajk_cn
								// 									INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
								// 									INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
								// 									INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
								// 									INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta AND fu_ajk_cn.id = fu_ajk_peserta.id_klaim
								// 									INNER JOIN fu_ajk_costumer ON fu_ajk_cn.id_cost = fu_ajk_costumer.id
								// 									LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
								// 									where
								// 									fu_ajk_cn.type_claim = "Batal" AND fu_ajk_cn.confirm_claim <> "Pending" AND fu_ajk_cn.del IS NULL
								// 									and fu_ajk_cn.id_peserta="' . $metCNbatal_temp['id_peserta'] . '"');


        // $arap_detail_cn=$database->doQuery('insert into CMS_ArAp_Referensi(
								// 									fArAp_No
								// 									,fArAp_Counter
								// 									,fArAp_CoreCode
								// 									,fArAp_BMaretialCode
								// 									,fArAp_RefMemberID
								// 									,fArAp_RefMemberNm
								// 									,fArAp_RefDescription
								// 									,fArAp_RefAmount
								// 									, input_by
								// 									, input_date)
								// 									select fu_ajk_cn.id_cn
								// 									, "1"
								// 									, "BTL"
								// 									, "BTL"
								// 									, fu_ajk_cn.id_peserta
								// 									, fu_ajk_peserta.nama
								// 									, ""
								// 									, fu_ajk_cn.premi+fu_ajk_peserta_as.nettpremi
								// 									, "' . $_SESSION['nm_user'] . '"
								// 									, current_timestamp()
								// 									FROM
								// 									fu_ajk_cn
								// 									INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
								// 									INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
								// 									INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
								// 									INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta AND fu_ajk_cn.id = fu_ajk_peserta.id_klaim
								// 									INNER JOIN fu_ajk_costumer ON fu_ajk_cn.id_cost = fu_ajk_costumer.id

								// 									INNER JOIN fu_ajk_peserta_as ON fu_ajk_peserta.id_peserta = fu_ajk_peserta_as.id_peserta AND fu_ajk_peserta.id_dn = fu_ajk_peserta_as.id_dn

								// 									LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
								// 									where
								// 									fu_ajk_cn.type_claim = "Batal" AND fu_ajk_cn.confirm_claim <> "Pending" AND fu_ajk_cn.del IS NULL
								// 									and fu_ajk_cn.id_peserta="' . $metCNbatal_temp['id_peserta'] . '"');

        $arap_cms_transaction=$database->doQuery('INSERT INTO CMS_ArAp_Transaction(fArAp_TransactionCode,
																													fArAp_TransactionDate,
																													fArAp_Status,
																													fArAp_No,
																													fArAp_Customer_Id,
																													fArAp_Customer_Nm,
																													fArAp_Asuransi_Id,
																													fArAp_Asuransi_Nm,
																													fArAp_Produk_Nm,
																													fArAp_StatusPeserta,
																													fArAp_DateStatus,
																													fArAp_CoreCode,
																													fArAp_BMaterialCode,
																													fArAp_RefMemberID,
																													fArAp_RefMemberNm,
																													fArAp_RefCabang,
																													fArAp_RefDescription,
																													fArAp_RefAmount,
																													fArAp_RefAmount2,
																													fArAp_RefDOB,
																													fArAp_AssDate,
																													fArAp_RefTenor,
																													fArAp_RefPlafond,
																													fArAp_Return_Status,
																													fArAp_Return_Date,
																													fArAp_Return_Amount,
																													fArAp_SourceDB,
																													input_by,
																													input_date)
																								SELECT "AP-00" as fArAp_TransactionCode,
																											fu_ajk_cn.tgl_createcn as fArAp_TransactionDate,
																											"A" as fArAp_Status,
																											fu_ajk_cn.id_cn as fArAp_No,
																											fu_ajk_grupproduk.nmproduk as fArAp_Customer_Id,
																											fu_ajk_grupproduk.nm_mitra as fArAp_Customer_Nm,
																											fu_ajk_asuransi.`code` as fArAp_Asuransi_Id,
																											fu_ajk_asuransi.`name` as fArAp_Asuransi_Nm,
																											fu_ajk_polis.nmproduk as fArAp_Produk_Nm,
																											CONCAT(IFNULL(fu_ajk_peserta.status_aktif,"")," ",IFNULL(fu_ajk_peserta.status_peserta,""))as fArAp_StatusPeserta,
																											DATE_FORMAT(NOW(),"%Y-%m-%d")as fArAp_DateStatus,
																											"BTL" as fArAp_CoreCode,
																											"BTL" as fArAp_BMaterialCode,
																											fu_ajk_peserta.id_peserta as fArAp_RefMemberID,
																											fu_ajk_peserta.nama as fArAp_RefMemberNm,
																											fu_ajk_peserta.cabang as fArAp_RefCabang,
																											null as fArAp_RefDescription,
																											fu_ajk_cn.total_claim as fArAp_RefAmount,
																											NULL as fArAp_RefAmount2,
																											fu_ajk_peserta.tgl_lahir as fArAp_RefDOB,
																											fu_ajk_peserta.tgl_laporan as fArAp_AssDate,
																											fu_ajk_peserta.kredit_tenor as fArAp_RefTenor,
																											fu_ajk_peserta.kredit_jumlah as fArAp_RefPlafond,
																											null as fArAp_Return_Status,
																											null as fArAp_Return_Date,
																											null as fArAp_Return_Amount,
																											"AJK" AS fArAp_SourceDB,
																											"'.$_SESSION['nm_user'].'" as input_by,
																											now() as input_date
																								FROM fu_ajk_peserta
																										 INNER JOIN fu_ajk_peserta_as
																										 on fu_ajk_peserta_as.id_peserta = fu_ajk_peserta.id_peserta
																										 INNER JOIN fu_ajk_dn
																										 on fu_ajk_dn.id = fu_ajk_peserta.id_dn
																										 INNER JOIN fu_ajk_asuransi
																										 on fu_ajk_asuransi.id = fu_ajk_peserta_as.id_asuransi
																										 INNER JOIN fu_ajk_grupproduk
																										 on fu_ajk_grupproduk.id = fu_ajk_peserta.nama_mitra
																										 INNER JOIN fu_ajk_polis
																										 ON fu_ajk_polis.id = fu_ajk_peserta.id_polis
																										 INNER JOIN fu_ajk_cn
																										 ON fu_ajk_cn.id = fu_ajk_peserta.id_klaim
																								WHERE fu_ajk_peserta.del is NULL and
																											fu_ajk_peserta_as.del is NULL and
																											fu_ajk_dn.del is null and
																											fu_ajk_cn.del is null and
																											fu_ajk_asuransi.del is NULL AND
																											fu_ajk_grupproduk.del is NULL and
																											fu_ajk_polis.del is NULL and
																											fu_ajk_cn.type_claim = "Batal" and
																											fu_ajk_peserta.id_peserta = "'.$metCNbatal_temp['id_peserta'].'"');

        $metCNbatalID = mysql_fetch_array($database->doQuery('SELECT id FROM fu_ajk_cn ORDER BY id DESC'));
        $pesertaBatal = $database->doQuery('UPDATE fu_ajk_peserta SET id_klaim="'.$metCNbatalID['id'].'", status_aktif="Lapse", status_peserta="Batal" WHERE id_cost="'.$metCNbatal_temp['id_cost'].'" AND id_polis="'.$metCNbatal_temp['id_nopol'].'" AND id_dn="'.$metCNbatal_temp['id_dn'].'" AND id_peserta="'.$metCNbatal_temp['id_peserta'].'"');
        $pesertaBatal_as = $database->doQuery('UPDATE fu_ajk_peserta_as SET id_cn="'.$metCNbatalID['id'].'", status_peserta="Batal" WHERE id_bank="'.$metCNbatal_temp['id_cost'].'" AND id_polis="'.$metCNbatal_temp['id_nopol'].'" AND id_dn="'.$metCNbatal_temp['id_dn'].'" AND id_peserta="'.$metCNbatal_temp['id_peserta'].'"');
        $pesertaBatalTemp = $database->doQuery('DELETE FROM fu_ajk_cn_tempf  WHERE id="'.$eL.'"');

        /* SMTP MAIL */
        $pesertaBatal_email = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_klaim="'.$metCNbatalID['id'].'" AND status_peserta="Batal" AND id_cost="'.$metCNbatal_temp['id_cost'].'" AND id_polis="'.$metCNbatal_temp['id_nopol'].'" AND id_dn="'.$metCNbatal_temp['id_dn'].'" AND id_peserta="'.$metCNbatal_temp['id_peserta'].'"'));
        $pesertaBatalDN_email = mysql_fetch_array($database->doQuery('SELECT id, dn_kode FROM fu_ajk_dn WHERE id="'.$pesertaBatal_email['id_dn'].'"'));
		    $mail = new PHPMailer; // call the class
		    $mail->IsSMTP();
		    $mail->Host = SMTP_HOST; //Hostname of the mail server
		    $mail->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
	      $mail->Username = SMTP_UNAME; //Username for SMTP authentication any valid email created in your domain
	      $mail->Password = SMTP_PWORD; //Password for SMTP authentication
	      $mail->AddReplyTo = SMTP_REPLYMAIL; //reply-to address
	      $mail->debug = 1;
		    $mail->SMTPSecure = "ssl";
		    $mail->IsHTML(true);

        $mail->SetFrom($q['email'], $q['nm_lengkap']);
        $mail->Subject = "AJKOnline - APPROVE PEMBATALAN PESERTA AJK ONLINE";
        //EMAIL PENERIMA KANTOR U/W
        $mailsupervisorajk = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="" AND status="UNDERWRITING"');
        while ($_mailsupervisorajk = mysql_fetch_array($mailsupervisorajk)) {
            $mail->AddAddress($_mailsupervisorajk['email'], $_mailsupervisorajk['nm_lengkap']); //To address who will receive this email
        }
        //EMAIL PENERIMA KANTOR U/W

        //EMAIL PENERIMA CLIENT
        $mailuserclient = mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE id_cost="'.$pesertaBatal_email['id_cost'].'" AND id_polis="'.$pesertaBatal_email['id_polis'].'" AND nm_user="'.$pesertaBatal_email['input_by'].'"'));
        $mail->AddAddress($mailuserclient['email'], $mailuserclient['nm_lengkap']); //To address who will receive this email
        //EMAIL PENERIMA CLIENT

        $message = "To ".$mailuserclient['nm_lengkap'].", <br /> Data pembatalan peserta :
					<table border=0 width=100%>
					<tr><td width=20%>ID Peserta</td><td>".$metCNbatal_temp['id_peserta']."</td></tr>
					<tr><td>Nama Peserta</td><td>".$pesertaBatal_email['nama']."</td></tr>
					<tr><td>Nomor Debit Note</td><td>".$pesertaBatalDN_email['dn_kode']."</td></tr>
					<tr><td>Nomor Credit Note</td><td>".$cn_kode."</td></tr>
					<tr><td>Premi</td><td>".duit($metCNbatal_temp['total_claim'])."</td></tr>
					<tr><td colspan=2>Data pembatalan peserta telah di setujui oleh ".$q['nm_lengkap']." pada tanggal ".$futgldn.".</td></tr>
					</table>";
        //$mail->AddCC("penting_kaga@yahoo.co.id");
        $mail->MsgHTML('Pembatalan peserta AJK telah di approve oleh <b>'.$_SESSION['nm_user'].' selaku Supervisor AJK-Online pada tanggal '.date('Y-m-d').$message); //Put your body of the message you can place html code here
        $send = $mail->Send(); //Send the mails
            //echo $message.'<br />';
    }
    echo '<center>Approve data pembatalan peserta telah disetujui oleh <b>'.$_SESSION['nm_user'].'</b>.<br /><meta http-equiv="refresh" content="2; url=ajk_klaim.php?fu=setDataBatal"></center>';
            ;
break;

        default:
echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
		<tr><th colspan="8" width="100%" align="left" colspan="2">Modul Klaim</font></th><th width="5%" colspan="2"><a href="ajk_klaim.php?fu=newdie">New</a></th></tr>
		</table><br />
	<fieldset>
	<legend align="center">S e a r c h</legend>
	<table border="0" width="50%" cellpadding="3" cellspacing="1">
	<form method="post" action="ajk_klaim.php?fu=topup">
	<tr><td width="10%">Nama</td><td>: <input type="text" name="cnama" value='.$_REQUEST['cnama'].'> &nbsp; <input type="submit" name="button" value="Search" class="button"></td></tr>
	</form>
	</table></fieldset>

	<table border="0" width="100%" cellpadding="5" cellspacing="1" bgcolor="#bde0e6">
	<tr><th rowspan="2" width="3%">No</td>
	<th rowspan="2" width="10%">ID Peserta</td>
	<th rowspan="2" width="10%">ID DN</td>
	<th rowspan="2">Name</td>
	<th colspan="2" width="5%">Periode</td>
	<th colspan="2" width="10%">Claim</td>
	<th rowspan="2" width="7%">Jumlah</td>
	<th rowspan="2" width="5%">Confirm</td>
	<th rowspan="2" width="8%">Opt</td>
	</tr>
	<tr><th>MA</th><th>MA-J</th>
		<th>Type</th><th>Date</th>
	</tr>';

if ($_REQUEST['x']) {
    $m = ($_REQUEST['x']-1) * 25;
} else {
    $m = 0;
}

if ($_REQUEST['cnama']) {
    $dua = 'AND nama LIKE "%' . $_REQUEST['cnama'] . '%"';
}

$mklaim = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE status_peserta!="" AND status_peserta!="Cancel" '.$dua.' ORDER BY id DESC, input_time DESC LIMIT ' . $m . ' , 25');
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_peserta WHERE id != "" AND status_peserta!="" AND status_peserta!="Cancel" '.$dua.' '));
$totalRows = $totalRows[0];
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while ($metklaim = mysql_fetch_array($mklaim)) {

/*
$idp = 1000000000 + $metklaim['id'];
$idp2 = substr($idp,1);
$metpeserta = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_klaim="'.$metklaim['id_cn'].'"'));
$metpesertaawal = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE nama="'.$metpeserta['nama'].'"'));
    // MA - J
    $now = new T10DateCalc($metpesertaawal['kredit_tgl']);
    $periodbulan = $now->compareDate($metklaim['tgl_klaim']) / 30.4375;
    $metbulan = ceil($periodbulan);
*/
    //	$jumlahnya = ((($ymetz['kredit_tenor'] - $metbulan) / $ymetz['kredit_tenor']) * 1) * $ymetz['premi'];
    // MA - J

    if (($no % 2) == 1) {
        $objlass = 'tbl-odd';
    } else {
        $objlass = 'tbl-even';
    }
    echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	<td align="center" valign="top">'.(++$no + ($pageNow-1) * 25).'</td>
	<td align="center">'.$metklaim['id_peserta'].'</td>
	<td align="center">'.$metklaim['id_dn'].'</td>
	<td>'.$metklaim['nama'].'</td>
	<td>'.$metklaim['kredit_tenor'].'</td>
	<td>'.$metbulan.'</td>
	<td align="center">'.$metklaim['status_peserta'].'</td>
	<td align="center">'.$metklaim['kredit_tgl'].'</td>
	<td align="right">'.duit($metklaim['jumlah']).'</td>
	<td align="center"><a href="ajk_klaim.php?fu=confclaim&id='.$metklaim['id'].'">'.$metklaim['confirm_klaim'].'</a></td>
	<td align="center"><a href="#">Edit</a> &nbsp; <a href="#">Cancel</a></td>
	</tr>';
}
        echo '<tr><td colspan="22">';
        echo createPageNavigations($file = 'ajk_klaim.php?', $total = $totalRows, $psDeh = 10, $anchor = '', $perPage = 25);
        echo '<b>Total Data Peserta: <u>' . $totalRows . '</u></b></td></tr>';
        echo '</table>';
        ;
} // switch
?>
<SCRIPT language=JavaScript>
function reload(form)
{
	var val=form.cat.options[form.cat.options.selectedIndex].value;
	self.location='ajk_klaim.php?fu=newdie&cat=' + val;
}
</script>

<SCRIPT language=JavaScript>
function reloadrefund(form)
{
	var val=form.cat.options[form.cat.options.selectedIndex].value;
	self.location='ajk_klaim.php?fu=setrefund&cat=' + val;
}
</script>

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
