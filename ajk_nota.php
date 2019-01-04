<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ----------------------------------------------------------------------------------
include_once("ui.php");
include_once("includes/functions.php");
echo "<script language=\"JavaScript\" src=\"javascript/js/form_validation.js\"></script>";
connect();
$futgl = date("Y-m-d H:i:s");
if (isset($_SESSION['nm_user'])) {
    $q = mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="' . $_SESSION['nm_user'] . '"'));
}
if ($q['level'] == "99" or $q['level'] == "6") {
    $typedata = 'AND type_data="SPK"';
} else {
    $typedata = 'AND type_data!="SPK"';
}
switch ($_REQUEST['er']) {
  case "dn":
    echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="100%" align="left">Modul Data Debit Note (DN)</font></th></tr></table>';
    $userPerusahaan = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$q['id_cost'].'"'));
    if ($q['id_polis']=="") {
        $userProduk = $database->doQuery('SELECT * FROM fu_ajk_polis WHERE id_cost="'.$q['id_cost'].'" AND del IS NULL');
        $_ProdukUser .= '<select id="id_cost" name="cat"> <option value="">--- Pilih ---</option>';
        while ($userProduk_ = mysql_fetch_array($userProduk)) {
            $_ProdukUser .= '<option value="'.$userProduk_['id'].'"'._selected($_REQUEST['cat'], $userProduk_['id']).'>'.$userProduk_['nmproduk'].'</option>';
        }
        $_ProdukUser .= '</select>';
        $QueryProduk = 'AND fu_ajk_dn.id_nopol !=""';
    } else {
        $userProduk = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$q['id_polis'].'" AND del IS NULL'));
        $_ProdukUser = $userProduk['nmproduk'].' ('.$userProduk['nopol'].')';
        $QueryProduk = 'AND fu_ajk_dn.id_nopol="'.$q['id_polis'].'"';
    }

    if ($q['cabang']=="PUSAT") {
        $userCabang = $database->doQuery('SELECT DISTINCT * FROM fu_ajk_cabang WHERE id_cost="'.$q['id_cost'].'" AND del IS NULL GROUP BY name ORDER BY name ASC');
        $_userCabang .= '<select id="id_cost" name="subcat"> <option value="">--- Pilih ---</option>';
        while ($userCabang_ = mysql_fetch_array($userCabang)) {
            $_userCabang .= '<option value="'.$userCabang_['name'].'"'._selected($_REQUEST['subcat'], $userCabang_['name']).'>'.$userCabang_['name'].'</option>';
        }
        $_userCabang .= '</select>';
        $QueryCabang = 'AND fu_ajk_dn.id_cabang !=""';
        $QueryInput = 'AND fu_ajk_dn.input_by !=""';
        $met_polis = $database->doQuery('SELECT * FROM fu_ajk_polis WHERE tipe_produk="ajk" AND id_cost="'.$q['id_cost'].'" AND del IS NULL ORDER BY nmproduk ASC');
    } else {
        //$QueryCabang = 'AND fu_ajk_dn.id_cabang ="'.$q['cabang'].'"';
        $QueryCabang = 'AND (fu_ajk_dn.id_cabang ="'.$q['cabang'].'" or id_cabang in (select name from fu_ajk_cabang where centralcbg = (select id from fu_ajk_cabang where name = "'.$q['cabang'].'" and del is NULL)))';
        //$QueryCabang = 'AND fu_ajk_dn.id_cabang ="'.$q['cabang'].'"';
        $QueryInput = 'AND fu_ajk_dn.input_by ="'.$q['nm_user'].'"';
        $met_polis = $database->doQuery('SELECT * FROM fu_ajk_polis WHERE tipe_produk="ajk" AND id_cost="'.$q['id_cost'].'" AND ('.$mametProdukUser.') AND del IS NULL ORDER BY nmproduk ASC');
    }

    echo '<fieldset style="padding: 2">
    	  <legend align="center">S e a r c h</legend>
    	  <table border="0" width="100%" cellpadding="1" cellspacing="1">
    	<form method="post" action="">
    	<tr><td width="10%">Nama Perusahaan</td><td>: '.$userPerusahaan['name'].'</td></tr>
    	<tr><td width="10%">Cabang</td><td><b>: '.$_userCabang.'</b></td></tr>
    	<!--<tr><td width="10%">Produk</td><td>: '.$_ProdukUser.'</td></tr>-->
    	<tr><td>Nama Produk</td><td>: <select name="idpolis">
    		<option value="">---Pilih Produk---</option>';
    while ($met_polis_ = mysql_fetch_array($met_polis)) {
        echo '<option value="'.$met_polis_['id'].'"'._selected($_REQUEST['idpolis'], $met_polis_['id']).'>'.$met_polis_['nmproduk'].'</option>';
    }
    echo '</select></td></tr>
    	<tr><td width="10%">Nomor DN</td><td>: <input type="text" name="metdn" value="'.$_REQUEST['metdn'].'"></td></tr>
    	<tr><td width="10%">Tanggal Debitnote</td><td>:
    		<input type="text" name="tgl" id="tgl" class="tanggal" value="' . $_REQUEST['tgl'] . '" size="10"/> s/d
    		<input type="text" name="tgl2" id="tgl2" class="tanggal" value="' . $_REQUEST['tgl2'] . '" size="10"/>
    	</td></tr>
    	<tr><td>Status Pembayaran </td><td>:
    		  <select size="1" name="paiddata"><option value="">--- Status ---</option>
    										   <option value="Lunas"' . _selected($_REQUEST['paiddata'], "Lunas") . '>Paid</option>
    										   <option value="unpaid"' . _selected($_REQUEST['paiddata'], "unpaid") . '>Unpaid</option>
    		  </select>
    	</td></tr>
    	<tr><td colspan="2"><input type="submit" name="button" value="Cari" class="button"></td></tr>
    	</form>
    	</table>
    	</fieldset>';
    echo '<table border="0" width="100%" cellpadding="5" cellspacing="1" bgcolor="#bde0e6">
    	  <tr><th width="3%">No</th>
    	  	  <th width="10%">Produk</th>
    	  	  <th>Debitnote</th>
    	  	  <th width="5%">Debitur</th>
    	  	  <th width="5%">Total</th>
    	  	  <th width="5%">Tanggal Debitnote</th>
    	  	  <th width="5%">Tanggal WPC DN</th>
    	  	  <th width="1%">Status</th>
    	  	  <th width="5%">Due Date</th>
    	  	  <th width="10%">Cabang</th>
    	  	  <th width="9%">Regional</th>
    	  	  <th width="12%">Creditnote</th>
    	  	  <th width="5%">Nilai CN</th>
    	  	  <th width="5%">Status</th>
    	  	  <th width="5%">Download</th>
    	  </tr>';

    if ($_REQUEST['x']) {
        $m = ($_REQUEST['x'] - 1) * 25;
    } else {
        $m = 0;
    }

    /*
    if ($_REQUEST['metdn']) 								{	$satu = 'AND dn_kode LIKE "%' . $_REQUEST['metdn'] . '%"';	}
    if ($_REQUEST['tgl'] != '' AND $_REQUEST['tgl2'] != '') {	$duaa = 'AND tgl_createdn BETWEEN \'' . $_REQUEST['tgl'] . '\' AND \'' . $_REQUEST['tgl2'] . '\'';	}
    if ($_REQUEST['cat']) 									{	$dua = 'AND id_regional LIKE "%' . $_REQUEST['cat'] . '%"';	}
    if ($_REQUEST['paiddata']) 								{	$empt = 'AND dn_status = "' . $_REQUEST['paiddata'] . '"';	}
    if ($_REQUEST['subcat']) 								{	$lima = 'AND id_cabang LIKE "%' . $_REQUEST['subcat'] . '%"';	}

    $met = $database->doQuery('SELECT * FROM fu_ajk_dn WHERE id!="" AND id_cost="' . $q['id_cost'] . '" AND id_nopol="' . $q['id_polis'] . '" ' . $satu . ' ' . $duaa . ' ' . $tiga . ' ' . $empt . ' ' . $lima . ' ' . $typedata . ' AND  del IS NULL ORDER BY tgl_createdn DESC LIMIT ' . $m . ' , 25');
    $totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_dn WHERE id != "" AND id_cost="' . $q['id_cost'] . '" AND id_nopol="' . $q['id_polis'] . '" ' . $satu . ' ' . $duaa . ' ' . $tiga . ' ' . $empt . ' ' . $lima . ' ' . $typedata . ' AND del IS NULL '));
    $totalRows = $totalRows[0];
    $_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
    while ($metdn = mysql_fetch_array($met)) {
    if ($metdn['dn_status'] == "unpaid") {	$statusdn = '<blink><font color="red">Unpaid</font></blink>';	} else {	$statusdn = '<font color="blue">Paid</font>';	}

    $metperusahaan = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_costumer WHERE id="' . $metdn['id_cost'] . '"'));
    $metpolis = mysql_fetch_array($database->doQuery('SELECT id, id_cost, nmproduk, nopol, jtempo FROM fu_ajk_polis WHERE id="' . $metdn['id_nopol'] . '" AND id_cost="' . $metdn['id_cost'] . '"'));
    $tanggalplus = date('Y-m-d', strtotime($metdn['tgl_createdn'] . "+ " . $metpolis['jtempo'] . " day"));
    if ($metpolis['nmproduk'] == "") {	$metproduk = $metpolis['nopol'];	} else {	$metproduk = $metpolis['nmproduk'];	}

    $metpeserta = mysql_num_rows($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_dn="' . $metdn['id'] . '" AND id_cost="' . $metdn['id_cost'] . '" AND id_polis="' . $metdn['id_nopol'] . '" AND del IS NULL'));
                // CEK DATA CEN DENGAN DN
    $dncnnya = mysql_fetch_array($database->doQuery('SELECT fu_ajk_cn.id_cn, fu_ajk_cn.id_dn, fu_ajk_cn.tgl_createcn, SUM(fu_ajk_cn.total_claim) AS tclaim, fu_ajk_cn.type_claim, fu_ajk_cn.del
                                                     FROM fu_ajk_cn
                                                     WHERE fu_ajk_cn.id_dn = "' . $metdn['dn'] . '" AND id_cost="' . $metdn['id_cost'] . '" AND id_nopol="' . $metdn['id_nopol'] . '" AND type_claim !="Refund" AND fu_ajk_cn.del IS NULL
                                                     GROUP BY fu_ajk_cn.id_dn'));
                if ($dncnnya['id_dn'] == $metdn['dn_kode']) {
                    $cnnomor = '<a href="ajk_report_fu.php?fu=ajkpdfcn&id_cn=' . $dncnnya['id_cn'] . '">' . $dncnnya['id_cn'] . '</a>';
                    $cnpremi = duit($dncnnya['tclaim']);
                    $statuscn = '<font color="red">' . $dncnnya['type_claim'] . '</font>';
                } else {
                    $cnnomor = '-';
                    $cnpremi = '-';
                    $statuscn = 'Inforce';
                }
                // CEK DATA CEN DENGAN DN
                if ($metdn['tgl_createdn'] == $datelog) {
                    $datenowdn = '<div class="wrapper">
                  <a href="ajk_nota.php?er=viewmember&id=' . $metdn['id'] . '">' . $metdn['dn_kode'] . '</a>
                  <div class="ribbon-wrapper-green"><div class="ribbon-green">new</div></div>
                  </div>';
                }else {
                    $datenowdn = '<a href="ajk_nota.php?er=viewmember&id=' . $metdn['id'] . '">' . $metdn['dn_kode'] . '</a>';
                }

    $netpremi = $metdn['totalpremi'] - $dncnnya['tclaim'];
    if (($no % 2) == 1) $objlass = 'tbl-odd';	else $objlass = 'tbl-even';
    echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
        <td align="center">' . (++$no + ($pageNow - 1) * 25) . '</td>
        <td align="center">' . $metperusahaan['name'] . '</td>
        <td align="center">' . $metproduk . '</td>
        <td align="center">' . $datenowdn . '</td>
        <td align="center"><b>' . $metpeserta . ' Data</b></td>
        <td align="right"><b>' . duit($metdn['totalpremi']) . '</b></td>
        <td align="center">' . _convertDate($metdn['tgl_createdn']) . '</td>
        <td align="center">' . _convertDate($tanggalplus) . '</td>
        <td align="center">' . $statusdn . '</td>
        <td align="center">' . $metdn['tgl_dn_paid'] . '</td>
        <td>' . $metdn['id_cabang'] . '</td>
        <td>' . $metdn['id_regional'] . '</td>
        <td>' . $cnnomor . '</td>
        <td align="right">' . $cnpremi . '</td>
        <td align="right"><b>' . duit($netpremi) . '</b></td>
        <td align="center"><b>' . $statuscn . '</b></td>
        <td align="center">
        <a href="aajk_report.php?er=_kwitansi&idn=' . $metdn['id'] . '&s=' . $q['id'] . '" target="_blank"><img src="image/dninvoice.png" width="20"></a> &nbsp;
        <a href="aajk_report.php?er=_kwipeserta&idn=' . $metdn['id'] . '&s=' . $q['id'] . '" target="_blank"><img src="image/new.png" width="20"></a> &nbsp;
        </td>';
        }
    */

    if ($_REQUEST['metdn']) {
        $satu = 'AND fu_ajk_dn.dn_kode LIKE "%' . $_REQUEST['metdn'] . '%"';
    }
    if ($_REQUEST['tgl'] != '' and $_REQUEST['tgl2'] != '') {
        $dua = 'AND fu_ajk_dn.tgl_createdn BETWEEN \'' . $_REQUEST['tgl'] . '\' AND \'' . $_REQUEST['tgl2'] . '\'';
    }
    if ($_REQUEST['idpolis']) {
        $tiga = 'AND fu_ajk_dn.id_nopol = "' . $_REQUEST['idpolis'] . '"';
    }
    if ($_REQUEST['paiddata']) {
        $empat = 'AND fu_ajk_dn.dn_status = "' . $_REQUEST['paiddata'] . '"';
    }
    if ($_REQUEST['subcat']) {
        $lima = 'AND fu_ajk_dn.id_cabang = "' . $_REQUEST['subcat'] . '"';
    }

    if ($q['wilayah']=="PUSAT" and $q['cabang']=="PUSAT") {
        $metCabangCentral = '';
    } elseif ($q['wilayah']!="PUSAT" and $q['cabang']=="PUSAT") {
        $cekCentral = $database->doQuery('SELECT fu_ajk_regional.`name` AS regional, fu_ajk_cabang.`name` AS cabang
    									  FROM fu_ajk_regional
    									  INNER JOIN fu_ajk_cabang ON fu_ajk_regional.id = fu_ajk_cabang.id_reg
    									  WHERE fu_ajk_regional.`name` = "'.$q['wilayah'].'" AND fu_ajk_cabang.del IS NULL');
        while ($cekCentral__ = mysql_fetch_array($cekCentral)) {
            $metCabangCentral .= 'OR (fu_ajk_dn.id_cabang ="'.$cekCentral__['cabang'].'")';
        }
        $metCabangCentral = 'AND (fu_ajk_dn.id_cabang ="'.$q['cabang'].'" '.$metCabangCentral.')';
    } else {
        $cekCentral = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE name="'.$q['cabang'].'" and del is null'));
        $cekCentral_ = $database->doQuery('SELECT * FROM fu_ajk_cabang WHERE centralcbg="'.$cekCentral['id'].'" and del is null');
        while ($cekCentral__ = mysql_fetch_array($cekCentral_)) {
            $metCentralCabang .= ' OR fu_ajk_dn.id_cabang ="'.$cekCentral__['name'].'"';
        }
        //CEK DATA CABANG CENTRAL;
        if ($metCentralCabang=="") {
            $metCabangCentral = ' AND fu_ajk_dn.id_cabang ="'.$q['cabang'].'"';
        } else {
            $metCabangCentral = ' AND (fu_ajk_dn.id_cabang ="'.$q['cabang'].'" '.$metCentralCabang.')';
        }
    }
    $met = $database->doQuery(
    'SELECT
    fu_ajk_dn.id,
    fu_ajk_dn.id_cost,
    fu_ajk_dn.id_nopol,
    fu_ajk_dn.id_polis_as,
    fu_ajk_dn.id_as,
    fu_ajk_dn.id_regional,
    fu_ajk_dn.id_cabang,
    fu_ajk_dn.type_data,
    fu_ajk_dn.dn_kode,
    fu_ajk_dn.totalpremi,
    fu_ajk_dn.totalpremi_as,
    fu_ajk_dn.dn_total,
    fu_ajk_dn.dn_status,
    fu_ajk_dn.tgl_dn_paid,
    fu_ajk_dn.tgl_createdn,
    fu_ajk_dn.j_dl,
    fu_ajk_asuransi.`name`,
    fu_ajk_polis_as.nopol,
    COUNT(fu_ajk_peserta.nama) AS jNama,
    fu_ajk_cn.id AS idCN,
    fu_ajk_cn.id_cn,
    fu_ajk_cn.type_claim,
    SUM(fu_ajk_cn.total_claim) AS JumlahCN,
    fu_ajk_polis.nmproduk,
    fu_ajk_polis.jtempo
    FROM
    fu_ajk_dn
    LEFT JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
    LEFT JOIN fu_ajk_polis_as ON fu_ajk_dn.id_polis_as = fu_ajk_polis_as.id
    INNER JOIN fu_ajk_peserta ON fu_ajk_dn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_dn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_dn.id = fu_ajk_peserta.id_dn
    LEFT JOIN fu_ajk_cn ON fu_ajk_peserta.id_cost = fu_ajk_cn.id_cost AND fu_ajk_peserta.id_polis = fu_ajk_cn.id_nopol AND fu_ajk_peserta.id_klaim = fu_ajk_cn.id
    INNER JOIN fu_ajk_polis ON fu_ajk_dn.id_cost = fu_ajk_polis.id_cost AND fu_ajk_dn.id_nopol = fu_ajk_polis.id
    WHERE fu_ajk_dn.id!="" AND fu_ajk_dn.id_cost="'. $q['id_cost'] .'" '.$metCabangCentral.' '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' AND fu_ajk_dn.del IS NULL AND fu_ajk_peserta.del IS NULL
    GROUP BY fu_ajk_dn.dn_kode
    ORDER BY fu_ajk_dn.tgl_createdn DESC LIMIT ' . $m . ' , 25'
    );
    //WHERE fu_ajk_dn.id!="" AND fu_ajk_dn.id_cost="'. $q['id_cost'] .'" '.$QueryCabang.' '.$QueryInput.' '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' AND fu_ajk_dn.del IS NULL AND fu_ajk_peserta.del IS NULL
    $totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(fu_ajk_dn.id)
    FROM fu_ajk_dn
    LEFT JOIN fu_ajk_costumer ON fu_ajk_dn.id_cost = fu_ajk_costumer.id
    LEFT JOIN fu_ajk_polis ON fu_ajk_dn.id_nopol = fu_ajk_polis.id
    WHERE fu_ajk_dn.id!="" AND fu_ajk_dn.id_cost="'. $q['id_cost'] .'" '.$metCabangCentral.' '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' AND fu_ajk_dn.del IS NULL'));
    $totalRows = $totalRows[0];
    $_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
    //WHERE fu_ajk_dn.id!="" AND fu_ajk_dn.id_cost="'. $q['id_cost'] .'" '.$QueryProduk.' '.$QueryCabang.' '.$QueryInput.' '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' AND fu_ajk_dn.del IS NULL'));

    while ($metdn = mysql_fetch_array($met)) {

        $qpes = mysql_fetch_array(mysql_query("SELECT count(*)as cnt
                  FROM fu_ajk_peserta
                  WHERE id_dn = '".$metdn['id']."' and
                        del is null and
                        status_bayar = '0'"));

        $tanggalplus = date('Y-m-d', strtotime($metdn['tgl_createdn'] . "+ " . $metdn['jtempo'] . " day"));

        if($qpes['cnt'] > 0){
          $statusdn = '<blink><font color="red">Unpaid</font></blink>';
          $tgl_dn = '';
        }else{
          $qpes_ = mysql_fetch_array(mysql_query("
                  SELECT tgl_bayar
                  FROM fu_ajk_peserta
                  WHERE id_dn = '".$metdn['id']."' and
                        del is null
                  ORDER BY tgl_bayar desc limit 1"));
          $tgl_dn = _convertDate($qpes_['tgl_bayar']);
          $statusdn = '<font color="blue">Paid</font>';
        }

        // if ($metdn['dn_status'] == "unpaid") {
        //     $statusdn = '<blink><font color="red">Unpaid</font></blink>';
        // } else {
        //     $statusdn = '<font color="blue">Paid</font>';
        // }

        if ($metdn['type_claim']=="Batal") {
            $typeReport = '_eBatal';
        } elseif ($metdn['type_claim']=="Refund") {
            $typeReport = '_eRefund';
        } elseif ($metdn['type_claim']=="Death") {
            $typeReport = '_erKlaim';
        } else {
        }

        if (($no % 2) == 1) {
            $objlass = 'tbl-odd';
        } else {
            $objlass = 'tbl-even';
        }
        echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
    		<td align="center">' . (++$no + ($pageNow - 1) * 25) . '</td>
    		<td align="center">' . $metdn['nmproduk'] . '</td>
    		<td align="center">' . $metdn['dn_kode'] . '</td>
    		<td align="center"><b>' . $metdn['jNama'] . ' Data</b></td>
    		<td align="right"><b>' . duit($metdn['totalpremi']) . '</b></td>
    		<td align="center">' . _convertDate($metdn['tgl_createdn']) . '</td>
    		<td align="center">' . _convertDate($tanggalplus) . '</td>
    		<td align="center">' . $statusdn . '</td>
    		<td align="center">' . $tgl_dn . '</td>
    		<td>' . $metdn['id_cabang'] . '</td>
    		<td>' . $metdn['id_regional'] . '</td>
    		<td align="center"><a href="aajk_report.php?er='.$typeReport.'&idC='.$metdn['idCN'].'" target="_blank">'.$metdn['id_cn'].'</a></td>
    		<td align="right">' . duit($metdn['JumlahCN']) . '</td>
    		<td align="center"><b>' . $metdn['type_claim'] . '</b></td>
    		<td align="center">
    		<a href="aajk_report.php?er=_kwitansi&idn=' . $metdn['id'] . '&s=' . $q['id'] . '" target="_blank"><img src="image/dninvoice.png" width="20"></a> &nbsp;
    		<a href="aajk_report.php?er=_kwipeserta&idn=' . $metdn['id'] . '&s=' . $q['id'] . '" target="_blank"><img src="image/new.png" width="20"></a> &nbsp;
    	</td>';
    }
    echo '<tr><td colspan="22">';
    echo createPageNavigations($file = 'ajk_nota.php?er=dn&idpolis=' . $_REQUEST['idpolis'] . '&subcat=' . $_REQUEST['subcat'] . '&metdn=' . $_REQUEST['metdn'] . '&tgl=' . $_REQUEST['tgl'] . '&tgl2=' . $_REQUEST['tgl2'] . '&paiddata=' . $_REQUEST['paiddata'] . '&', $total = $totalRows, $psDeh = 10, $anchor = '', $perPage = 25);
    // echo createPageNavigations($file = 'ajk_dn.php?r=viewdn&rpays='.$_REQUEST['rpays'].'&rpaye='.$_REQUEST['rpaye'].'&rreg='.$_REQUEST['rreg'].'&rcabang='.$_REQUEST['rcabang'].'&rdnno='.$_REQUEST['rdnno'].'&rstat='.$_REQUEST['rstat'].'&dns='.$_REQUEST['rdns'].'&dne='.$_REQUEST['rdne'].'&', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
    echo '<b>Total Data Debit Note (DN): <u>' . $totalRows . '</u></b></td></tr>';
    echo '</table>'; ;
  break;

  case "cn":
    echo '<br />';
    if ($_REQUEST['req']=="btl") {
        $typeKlaim = 'AND fu_ajk_cn.type_claim="Batal"';
        $typereq = "Pembatalan";
    } elseif ($_REQUEST['req']=="ref") {
        $typeKlaim = 'AND fu_ajk_cn.type_claim="Refund"';
        $typereq = "Refund";
    } else {
        $typeKlaim = 'AND fu_ajk_cn.type_claim="Death"';
        $typereq = "Meninggal";
    }
    if ($q['cabang']=="PUSAT") {
        $userCabang = $database->doQuery('SELECT DISTINCT * FROM fu_ajk_cabang WHERE id_cost="'.$q['id_cost'].'" AND del IS NULL GROUP BY name ORDER BY name ASC');
        $_userCabang .= '<select id="id_cost" name="subcat"> <option value="">--- Pilih ---</option>';
        while ($userCabang_ = mysql_fetch_array($userCabang)) {
            $_userCabang .= '<option value="'.$userCabang_['name'].'"'._selected($_REQUEST['subcat'], $userCabang_['name']).'>'.$userCabang_['name'].'</option>';
        }
        $_userCabang .= '</select>';
        $QueryCabang = 'AND fu_ajk_dn.id_cabang !=""';
        $QueryInput = 'AND fu_ajk_dn.input_by !=""';
        $met_polis = $database->doQuery('SELECT * FROM fu_ajk_polis WHERE tipe_produk="ajk" AND del IS NULL ORDER BY nmproduk ASC');
    } else {
        $_userCabang = $q['cabang'];
        $QueryCabang = 'AND fu_ajk_dn.id_cabang ="'.$q['cabang'].'"';
        $QueryInput = 'AND fu_ajk_dn.input_by ="'.$q['nm_user'].'"';
        $met_polis = $database->doQuery('SELECT * FROM fu_ajk_polis WHERE tipe_produk="ajk" AND ('.$mametProdukUser.') AND del IS NULL ORDER BY nmproduk ASC');
    }
    echo '<fieldset style="padding: 2">
    	<legend>S e a r c h</legend>
    	<table border="0" width="100%" cellpadding="1" cellspacing="1">
    	<form method="post" action="">
    	<tr><td width="15%">Nama Perusahaan</td><td>: ';
    $quer2 = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="' . $q['id_cost'] . '"'));
    echo $quer2['name'].'</td></tr>
    <!--<tr><td width="10%">Produk</td><td>: '.$_ProdukUser.'</td></tr>-->
    	<tr><td width="10%">Cabang</td><td><b>: '.$_userCabang.'</b></td></tr>
       	<tr><td>Nama Produk</td><td>: <select name="idpolis">
       		<option value="">---Pilih Produk---</option>';
    while ($met_polis_ = mysql_fetch_array($met_polis)) {
        echo '<option value="'.$met_polis_['id'].'"'._selected($_REQUEST['idpolis'], $met_polis_['id']).'>'.$met_polis_['nmproduk'].'</option>';
    }
    echo '</select></td></tr>';

    /*	<tr><td align="right">Nama Produk</td>
            <td>: ';
            if ($q['id_polis'] != "") {
                $quer1 = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="' . $q['id_polis'] . '"'));
                echo $quer1['nmproduk'] . ' (' . $quer1['nopol'] . ')';
                $met_reg = $database->doQuery('SELECT * FROM fu_ajk_regional WHERE id_cost="' . $q['id_cost'] . '"');
                $kolomregional .= '<tr><td align="right">Regional</td>
                              <td>: <select id="id_cost" name="cat" onchange="reloadcn(this.form)">
                                  <option value="">--- Pilih ---</option>';
                $met_cost = $database->doQuery('SELECT * FROM fu_ajk_regional WHERE id_cost="' . $q['id_cost'] . '" ORDER BY name ASC');
                while ($met_cost_ = mysql_fetch_array($met_cost)) {
                    $kolomregional .= '<option value="' . $met_cost_['name'] . '"' . _selected($_REQUEST['cat'], $met_cost_['name']) . '>' . $met_cost_['name'] . '</option>';
                }
                $kolomregional .= '</select></td></tr>';
            } else {
                $quer1 = $database->doQuery('SELECT * FROM fu_ajk_polis WHERE id_cost="' . $q['id_cost'] . '"');
                echo '<select id="id_cost" name="cat">
            <option value="">--- Pilih ---</option>';
                while ($quer1_ = mysql_fetch_array($quer1)) {
                    echo '<option value="' . $quer1_['id'] . '"' . _selected($_REQUEST['cat'], $quer1_['id']) . '>' . $quer1_['nmproduk'] . '</option>';
                }
                echo '</select>';
                $kolomregional .= '<tr><td align="right">Regional</td>
                              <td>: ' . $q['wilayah'] . '</td></tr>';
            }

            echo '</td></tr>';
            echo $kolomregional;
            echo '<tr><td align="right">Cabang</td>
                    <td>: <select id="subcat" name="subcat">
                    <option value="">--- Pilih ---</option>';
            if ($q['id_polis'] != "") {
                $cek_regionalnya = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE name="' . $_REQUEST['cat'] . '"'));
                $rreg = $database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id_cost="' . $q['id_cost'] . '" AND id_reg="' . $cek_regionalnya['id'] . '" ORDER BY name ASC');
            }elseif ($q['id_polis'] == "" AND $q['level'] != "10") {
                $rreg = $database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id_cost="' . $q['id_cost'] . '" ORDER BY name ASC');
            }else {
                $cek_regionalnya = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE name="' . $q['wilayah'] . '"'));
                $rreg = $database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id_cost="' . $q['id_cost'] . '" AND id_reg="' . $cek_regionalnya['id'] . '" ORDER BY name ASC');
            }while ($freg = mysql_fetch_array($rreg)) {
                echo '<option value="' . $freg['name'] . '"' . _selected($_REQUEST['subcat'], $freg['name']) . '>' . $freg['name'] . '</option>';
            }
            echo '</select></td></tr>
    */
    echo '<tr><td>Debitnote</td><td>: <input type="text" name="metdn" value="' . $_REQUEST['metdn'] . '"></td></tr>
    	  <tr><td>Creditnote</td><td>: <input type="text" name="metcn" value="' . $_REQUEST['metcn'] . '"></td></tr>
    	  <tr><td>Nama debitur</td><td>: <input type="text" name="snama" value="' . $_REQUEST['snama'] . '"></td></tr>
    	  <tr><td width="10%">Tanggal Create CN</td><td>: ';print initCalendar();	print calendarBox('tgl1', 'triger1', $_REQUEST['tgl1']);
    echo 's/d ';print initCalendar();	print calendarBox('tgl2', 'triger2', $_REQUEST['tgl2']);
    echo '</td></tr>
        <tr><td>Id Peserta</td><td>: <input type="text" name="idpes" value="' . $_REQUEST['idpes'] . '"></td></tr>
    	  </td></tr>
    <!--  <tr><td>Type Claim</td>
    		  <td>: <select name="typeclaim"><option value="">---Pilih Klaim---</option>
    			<option name="typeclaim" value="Refund">Refund</option>
    			<option name="typeclaim" value="Death">Meninggal</option>
    			<option name="typeclaim" value="Batal">Batal</option>
    			</select></td></tr>-->
    	<tr><td align="center" colspan="2"><input type="submit" name="button" value="Cari" class="button"></td></tr>
    	</form></table></fieldset>';
    echo '<table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#FFFFF">
    		<tr><th width="1%" rowspan="2"><a href="e_report.php?er=download_klaim&req='.$_REQUEST['req'].'&nr='.$_SESSION['nm_user'].'&cat='.$_REQUEST['cat'].'&idpolis='.$_REQUEST['idpolis'].'&typeclaim='.$_REQUEST['typeclaim'].'&metcn='.$_REQUEST['metcn'].'&metdn='.$_REQUEST['metdn'].'&snama='.$_REQUEST['snama'].'&tgl1='.$_REQUEST['tgl1'].'&tgl2='.$_REQUEST['tgl2'].'">download</a></th></tr></table>';
    echo '<table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#E2E2E2">

    	<tr><th width="1%" rowspan="2">No</th>
    		<th width="5%" rowspan="2">Produk</th>
    		<th width="5%" rowspan="2">Debit Note</th>
    		<th width="5%" rowspan="2">Credit note</th>
    		<th rowspan="2">Status</th>
    		<th rowspan="2">ID Peserta</th>
    		<th rowspan="2">Nama</th>
    		<th rowspan="2" width="5%">Tgl Lahir</th>
    		<th rowspan="2" width="1%">Usia</th>
    		<th width="20%" colspan="4">Status Kredit Debitur</th>
    		<th width="1%" rowspan="2">Total Premi</th>
    		<th colspan="5">Klaim</th>
    		<th rowspan="2">Cabang</th>
    	</tr>
    	<tr><th width="5%">Akad</th>
    		<th width="1%">Jumlah</th>
    		<th width="1%">Tenor</th>
    		<th width="5%">Akhir</th>
    		<th width="1%">Type</th>
    		<th>Tanggal</th>
    		<th width="1%">MA-j</th>
    		<th width="1%">MA-s</th>
    		<!--<th>Jumlah</th>--!>
    		<!--<th>Tgl Bayar</th>--!>
    		<th>Status Dokumen</th>
    	</tr>';
    if ($_REQUEST['cat']) {
        $satu = 'AND fu_ajk_cn.id_regional LIKE "%' . $_REQUEST['cat'] . '%"';
    }
    if ($_REQUEST['idpolis']) {
        $dua = 'AND fu_ajk_cn.id_nopol LIKE "%' . $_REQUEST['idpolis'] . '%"';
    }
    if ($_REQUEST['typeclaim']) {
        $tiga = 'AND fu_ajk_cn.type_claim LIKE "%' . $_REQUEST['typeclaim'] . '%"';
    }
    if ($_REQUEST['metcn']) {
        $empat = 'AND fu_ajk_cn.id_cn LIKE "%' . $_REQUEST['metcn'] . '%"';
    }
    if ($_REQUEST['metdn']) {
        $lima = 'AND fu_ajk_dn.dn_kode LIKE "%' . $_REQUEST['metdn'] . '%"';
    }
    if ($_REQUEST['snama']) {
        $enam = 'AND fu_ajk_peserta.nama LIKE "%' . $_REQUEST['snama'] . '%"';
    }
    if ($_REQUEST['tgl1']) {
        $tujuh = 'AND fu_ajk_cn.tgl_createcn between "' . $_REQUEST['tgl1'] . '" and "' . $_REQUEST['tgl2'] . '"';
    }
    if ($_REQUEST['idpes']) {
        $delapan = 'AND fu_ajk_cn.id_peserta = "'.$_REQUEST['idpes'].'"';
    }


    if ($_REQUEST['x']) {
        $m = ($_REQUEST['x'] - 1) * 25;
    } else {
        $m = 0;
    }

    /*
    $cekCentral = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE name="'.$q['cabang'].'"'));
    $cekCentral_ = $database->doQuery('SELECT * FROM fu_ajk_cabang WHERE centralcbg="'.$cekCentral['id'].'"');
    while ($cekCentral__ = mysql_fetch_array($cekCentral_)) {
    $metCentralCabang .= ' OR fu_ajk_cn.id_cabang ="'.$cekCentral__['name'].'"';
    }
    //CEK DATA CABANG CENTRAL;
    if ($metCentralCabang=="") {
    $metCabangCentral = 'fu_ajk_cn.id_cabang ="'.$q['cabang'].'"';
    }else{
    $metCabangCentral = '(fu_ajk_cn.id_cabang ="'.$q['cabang'].'" '.$metCentralCabang.')';
    }

    if ($q['id_cost'] != "" AND $q['id_polis'] == "") {
    //$data = $database->doQuery('SELECT * FROM fu_ajk_cn WHERE id!="" AND id_cost="' . $q['id_cost'] . '" '.$typeKlaim.' ' . $satu . ' ' . $dua . ' ' . $tiga . ' ' . $empat . ' ' . $lima . ' AND del is null ORDER BY id DESC, input_by DESC LIMIT ' . $m . ' , 25');
    //$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_cn WHERE id != "" AND id_cost="' . $q['id_cost'] . '" '.$typeKlaim.' ' . $satu . ' ' . $dua . ' ' . $tiga . ' ' . $empat . ' ' . $lima . ' AND del is null '));
    $data = $database->doQuery('SELECT
    fu_ajk_cn.id,
    fu_ajk_cn.id_cost,
    fu_ajk_polis.nmproduk,
    fu_ajk_cn.id_nopol,
    fu_ajk_cn.id_dn,
    fu_ajk_cn.id_peserta,
    fu_ajk_cn.tgl_createcn,
    fu_ajk_cn.tgl_claim,
    fu_ajk_cn.tgl_byr_claim,
    fu_ajk_cn.type_claim,
    fu_ajk_cn.id_cn,
    fu_ajk_cn.id_cabang,
    fu_ajk_peserta.id_peserta,
    fu_ajk_peserta.spaj,
    fu_ajk_peserta.type_data,
    fu_ajk_peserta.nama_mitra,
    fu_ajk_peserta.nama,
    fu_ajk_peserta.tgl_lahir,
    fu_ajk_peserta.usia,
    fu_ajk_peserta.kredit_tgl,
    fu_ajk_peserta.kredit_tenor,
    fu_ajk_peserta.kredit_akhir,
    fu_ajk_peserta.kredit_jumlah,
    fu_ajk_peserta.totalpremi,
    IF(fu_ajk_peserta.type_data="SPK", fu_ajk_peserta.kredit_tenor * 12, fu_ajk_peserta.kredit_tenor) AS tenornya,
    fu_ajk_dn.dn_kode,
    fu_ajk_cn.total_claim,
    fu_ajk_cn.confirm_claim,
    fu_ajk_cn.input_by
    FROM fu_ajk_cn
    INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id = fu_ajk_peserta.id_klaim
    INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
    INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
    WHERE fu_ajk_cn.del IS NULL AND fu_ajk_cn.id_cost="' . $q['id_cost'] . '" '.$typeKlaim.' ' . $satu . ' ' . $dua . ' ' . $tiga . ' ' . $empat . ' ' . $lima . ' ' . $enam . '  '.$metCabangCentral.'
    ORDER BY fu_ajk_cn.id DESC LIMIT ' . $m . ' , 25');
    $totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(fu_ajk_cn.id)
                                                       FROM fu_ajk_cn
                                                       INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id = fu_ajk_peserta.id_klaim
                                                       INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
                                                       WHERE fu_ajk_cn.id != "" AND fu_ajk_cn.id_cost="' . $q['id_cost'] . '" '.$typeKlaim.' ' . $satu . ' ' . $dua . ' ' . $tiga . ' ' . $empat . ' ' . $lima . ' ' . $enam . ' '.$metCabangCentral.' AND fu_ajk_cn.del is null '));
    } else {
    //$data = $database->doQuery('SELECT * FROM fu_ajk_cn WHERE id!="" AND id_cost="' . $q['id_cost'] . '" '.$typeKlaim.' AND '.$metCabangCentral.' ' . $satu . ' ' . $dua . ' ' . $tiga . ' ' . $empat . ' ' . $lima . ' AND del is null ORDER BY id DESC, input_by DESC LIMIT ' . $m . ' , 25');
    $data = $database->doQuery('SELECT
    fu_ajk_cn.id,
    fu_ajk_cn.id_cost,
    fu_ajk_polis.nmproduk,
    fu_ajk_cn.id_nopol,
    fu_ajk_cn.id_dn,
    fu_ajk_cn.id_peserta,
    fu_ajk_cn.tgl_createcn,
    fu_ajk_cn.tgl_claim,
    fu_ajk_cn.tgl_byr_claim,
    fu_ajk_cn.type_claim,
    fu_ajk_cn.id_cn,
    fu_ajk_cn.id_cabang,
    fu_ajk_peserta.id_peserta,
    fu_ajk_peserta.spaj,
    fu_ajk_peserta.type_data,
    fu_ajk_peserta.nama_mitra,
    fu_ajk_peserta.nama,
    fu_ajk_peserta.tgl_lahir,
    fu_ajk_peserta.usia,
    fu_ajk_peserta.kredit_tgl,
    fu_ajk_peserta.kredit_tenor,
    fu_ajk_peserta.kredit_akhir,
    fu_ajk_peserta.kredit_jumlah,
    fu_ajk_peserta.totalpremi,
    IF(fu_ajk_peserta.type_data="SPK", fu_ajk_peserta.kredit_tenor * 12, fu_ajk_peserta.kredit_tenor) AS tenornya,
    fu_ajk_dn.dn_kode,
    fu_ajk_cn.total_claim,
    fu_ajk_cn.confirm_claim,
    fu_ajk_cn.input_by
    FROM fu_ajk_cn
    INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id = fu_ajk_peserta.id_klaim
    INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
    INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
    WHERE fu_ajk_cn.del IS NULL '.$typeKlaim.' AND '.$metCabangCentral.' ' . $satu . ' ' . $dua . ' ' . $tiga . ' ' . $empat . ' ' . $lima . ' ' . $enam . '
    ORDER BY fu_ajk_cn.id DESC LIMIT ' . $m . ' , 25');
    $totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(fu_ajk_cn.id)
                                                       FROM fu_ajk_cn
                                                       INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id = fu_ajk_peserta.id_klaim
                                                       INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
                                                       WHERE fu_ajk_cn.id != "" AND fu_ajk_cn.id_cost="' . $q['id_cost'] . '" '.$typeKlaim.' AND '.$metCabangCentral.' ' . $satu . ' ' . $dua . ' ' . $tiga . ' ' . $empat . ' ' . $lima . ' ' . $enam . ' AND fu_ajk_cn.del is null '));
    }

    $totalRows = $totalRows[0];
    */


    $cekCentral = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE name="'.$q['cabang'].'"'));
    $cekCentral_ = $database->doQuery('SELECT * FROM fu_ajk_cabang WHERE centralcbg="'.$cekCentral['id'].'"');
    while ($cekCentral__ = mysql_fetch_array($cekCentral_)) {
        $metCentralCabang .= ' OR fu_ajk_cn.id_cabang ="'.$cekCentral__['name'].'"';
    }
    //$data = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_cost="'.$q['id_cost'].'" '.$QueryInput.' AND '.$metCabangCentral.' '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' '.$typedata.' AND del IS NULL ORDER BY input_time DESC LIMIT ' . $m . ' , 50');
    if ($q['wilayah']=="PUSAT" and $q['cabang']=="PUSAT") {
        //$data = $database->doQuery('SELECT * FROM fu_ajk_cn WHERE id_cost="'.$q['id_cost'].'" AND status_aktif !="Pending" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' '.$tujuh.' '.$typedata.' AND del IS NULL ORDER BY input_time DESC LIMIT ' . $m . ' , 50');
        $data = $database->doQuery('SELECT fu_ajk_cn.id,
    								   fu_ajk_cn.id_cn,
    								   fu_ajk_costumer.name AS "perusahaan",
    								   fu_ajk_polis.nmproduk,
    								   fu_ajk_cn.id_peserta,
    								   fu_ajk_cn.id_regional,
    								   fu_ajk_cn.id_cabang,
    								   fu_ajk_cn.type_claim,
    								   fu_ajk_cn.tgl_claim,
    								   fu_ajk_cn.total_claim,
    								   fu_ajk_cn.tgl_byr_claim,
    								   fu_ajk_cn.confirm_claim,
    								   fu_ajk_klaim.tgl_document_lengkap,
    								   fu_ajk_cn.id_dn,
    								   fu_ajk_dn.dn_kode,
    								   fu_ajk_peserta.nama,
    								   fu_ajk_peserta.tgl_lahir,
    								   fu_ajk_peserta.usia,
    								   fu_ajk_peserta.kredit_tgl,
    								   fu_ajk_peserta.kredit_jumlah,
    								   fu_ajk_peserta.kredit_tenor,
    								   fu_ajk_peserta.kredit_akhir,
    								   fu_ajk_peserta.totalpremi,
    								   fu_ajk_peserta.status_aktif,
    								   fu_ajk_peserta.status_peserta,
    								   IF(fu_ajk_peserta.type_data="SPK", fu_ajk_peserta.kredit_tenor * 12, fu_ajk_peserta.kredit_tenor) AS tenornya,
    								   fu_ajk_peserta.cabang
    							FROM fu_ajk_cn
    							INNER JOIN fu_ajk_costumer ON fu_ajk_cn.id_cost = fu_ajk_costumer.id
    							INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
    							INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id = fu_ajk_peserta.id_klaim
    							INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
    							LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta AND fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost

    							WHERE fu_ajk_cn.del IS NULL AND
    								  fu_ajk_peserta.del IS NULL AND
    								  fu_ajk_cn.id_cost="' . $q['id_cost'] . '"
    								  '.$typeKlaim.' ' . $satu . ' ' . $dua . ' ' . $tiga . ' ' . $empat . ' ' . $lima . ' ' . $enam . ' '.$tujuh.' '.$delapan.'
    							ORDER BY fu_ajk_cn.input_date DESC
    							LIMIT ' . $m . ' , 50');
        $totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(fu_ajk_cn.id)
    												   FROM fu_ajk_cn
    												   INNER JOIN fu_ajk_costumer ON fu_ajk_cn.id_cost = fu_ajk_costumer.id
    												   INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
    												   INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id = fu_ajk_peserta.id_klaim
    												   WHERE fu_ajk_cn.del IS NULL AND
    						  						 fu_ajk_peserta.del IS NULL AND
    						  						 fu_ajk_cn.id_cost="' . $q['id_cost'] . '"
    						  						 '.$typeKlaim.' ' . $satu . ' ' . $dua . ' ' . $tiga . ' ' . $empat . ' ' . $lima . ' ' . $enam . '  '.$tujuh.' '.$delapan.''));
    } elseif ($q['wilayah']!="PUSAT" and $q['cabang']=="PUSAT") {
        $cekCentral = $database->doQuery('SELECT fu_ajk_regional.`name` AS regional, fu_ajk_cabang.`name` AS cabang
    									  FROM fu_ajk_regional
    									  INNER JOIN fu_ajk_cabang ON fu_ajk_regional.id = fu_ajk_cabang.id_reg
    									  WHERE fu_ajk_regional.`name` = "'.$q['wilayah'].'" AND fu_ajk_cabang.del IS NULL');
        while ($cekCentral__ = mysql_fetch_array($cekCentral)) {
            $metCabangCentral .= 'OR (fu_ajk_cn.id_cabang ="'.$cekCentral__['cabang'].'")';
        }
        $metCabangCentral = 'AND (fu_ajk_cn.id_cabang ="'.$q['cabang'].'" '.$metCabangCentral.')';
        //$data = $database->doQuery('SELECT * FROM fu_ajk_cn WHERE id_cost="'.$q['id_cost'].'" AND status_aktif !="Pending" '.$metCabangCentral.' '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' '.$tujuh.' '.$typedata.' AND del IS NULL ORDER BY input_time DESC LIMIT ' . $m . ' , 50');
        $data = $database->doQuery('SELECT fu_ajk_cn.id,
    								   fu_ajk_cn.id_cn,
    								   fu_ajk_costumer.name AS "perusahaan",
    								   fu_ajk_polis.nmproduk,
    								   fu_ajk_cn.id_peserta,
    								   fu_ajk_cn.id_regional,
    								   fu_ajk_cn.id_cabang,
    								   fu_ajk_cn.type_claim,
    								   fu_ajk_cn.tgl_claim,
    								   fu_ajk_cn.total_claim,
    								   fu_ajk_cn.tgl_byr_claim,
    								   fu_ajk_cn.confirm_claim,
    								   fu_ajk_klaim.tgl_document_lengkap,
    								   fu_ajk_cn.id_dn,
    								   fu_ajk_dn.dn_kode,
    								   fu_ajk_peserta.nama,
    								   fu_ajk_peserta.tgl_lahir,
    								   fu_ajk_peserta.usia,
    								   fu_ajk_peserta.kredit_tgl,
    								   fu_ajk_peserta.kredit_jumlah,
    								   fu_ajk_peserta.kredit_tenor,
    								   fu_ajk_peserta.kredit_akhir,
    								   fu_ajk_peserta.totalpremi,
    								   fu_ajk_peserta.status_aktif,
    								   fu_ajk_peserta.status_peserta,
    								   IF(fu_ajk_peserta.type_data="SPK", fu_ajk_peserta.kredit_tenor * 12, fu_ajk_peserta.kredit_tenor) AS tenornya,
    								   fu_ajk_peserta.cabang
    							FROM fu_ajk_cn
    							INNER JOIN fu_ajk_costumer ON fu_ajk_cn.id_cost = fu_ajk_costumer.id
    							INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
    							INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id = fu_ajk_peserta.id_klaim
    							INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
    							LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta AND fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost

    							WHERE fu_ajk_cn.del IS NULL AND
    								  fu_ajk_peserta.del IS NULL AND
    								  fu_ajk_cn.id_cost="' . $q['id_cost'] . '"
    								  '.$metCabangCentral.' '.$typeKlaim.' ' . $satu . ' ' . $dua . ' ' . $tiga . ' ' . $empat . ' ' . $lima . ' ' . $enam . '  '.$tujuh.' '.$delapan.'
    							ORDER BY fu_ajk_cn.input_date DESC
    							LIMIT ' . $m . ' , 50');
        $totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(fu_ajk_cn.id)
    												   FROM fu_ajk_cn
    												   INNER JOIN fu_ajk_costumer ON fu_ajk_cn.id_cost = fu_ajk_costumer.id
    												   INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
    												   INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id = fu_ajk_peserta.id_klaim
    												   INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
    												   WHERE fu_ajk_cn.del IS NULL AND
    								  						 fu_ajk_peserta.del IS NULL AND
    								  						 fu_ajk_cn.id_cost="' . $q['id_cost'] . '"
    								  						 '.$metCabangCentral.' '.$typeKlaim.' ' . $satu . ' ' . $dua . ' ' . $tiga . ' ' . $empat . ' ' . $lima . ' ' . $enam . '  '.$tujuh.' '.$delapan.''));
    } else {
        if ($metCentralCabang=="") {
            $metCabangCentral = 'AND fu_ajk_cn.id_cabang ="'.$q['cabang'].'"';
        } else {
            $metCabangCentral = 'AND ((fu_ajk_cn.id_cabang ="'.$q['cabang'].'" '.$metCentralCabang.') OR fu_ajk_cn.input_by="' . $q['nm_user'] . '")';
        }
        //CEK DATA CABANG CENTRAL;
        //$data = $database->doQuery('SELECT * FROM fu_ajk_cn WHERE id_cost="'.$q['id_cost'].'" AND status_aktif !="Pending" '.$metCabangCentral.' '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' '.$tujuh.' '.$typedata.' AND del IS NULL ORDER BY input_time DESC LIMIT ' . $m . ' , 50');
        $data = $database->doQuery('SELECT fu_ajk_cn.id,
    								   fu_ajk_cn.id_cn,
    								   fu_ajk_costumer.name AS "perusahaan",
    								   fu_ajk_polis.nmproduk,
    								   fu_ajk_cn.id_peserta,
    								   fu_ajk_cn.id_regional,
    								   fu_ajk_cn.id_cabang,
    								   fu_ajk_cn.type_claim,
    								   fu_ajk_cn.id_dn,
    								   fu_ajk_cn.tgl_claim,
    								   fu_ajk_cn.total_claim,
    								   fu_ajk_cn.tgl_byr_claim,
    								   fu_ajk_cn.confirm_claim,
    								   fu_ajk_klaim.tgl_document_lengkap,
    								   fu_ajk_dn.dn_kode,
    								   fu_ajk_peserta.nama,
    								   fu_ajk_peserta.tgl_lahir,
    								   fu_ajk_peserta.usia,
    								   fu_ajk_peserta.kredit_tgl,
    								   fu_ajk_peserta.kredit_jumlah,
    								   fu_ajk_peserta.kredit_tenor,
    								   fu_ajk_peserta.kredit_akhir,
    								   fu_ajk_peserta.totalpremi,
    								   fu_ajk_peserta.status_aktif,
    								   fu_ajk_peserta.status_peserta,
    								   IF(fu_ajk_peserta.type_data="SPK", fu_ajk_peserta.kredit_tenor * 12, fu_ajk_peserta.kredit_tenor) AS tenornya,
    								   fu_ajk_peserta.cabang,
    								   DATE_FORMAT(fu_ajk_cn.input_date,"%Y%m") as id_nya
    							FROM fu_ajk_cn
    							INNER JOIN fu_ajk_costumer ON fu_ajk_cn.id_cost = fu_ajk_costumer.id
    							INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
    							INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id = fu_ajk_peserta.id_klaim
    							INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
    							LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta AND fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost
    							WHERE fu_ajk_cn.del IS NULL AND
    								  fu_ajk_peserta.del IS NULL AND
    								  fu_ajk_cn.id_cost="' . $q['id_cost'] . '"
    								  '.$metCabangCentral.' '.$typeKlaim.' ' . $satu . ' ' . $dua . ' ' . $tiga . ' ' . $empat . ' ' . $lima . ' ' . $enam . ' '.$tujuh.' '.$delapan.'
    							ORDER BY fu_ajk_cn.id DESC,  fu_ajk_cn.input_date DESC
    							LIMIT ' . $m . ' , 50');
        $totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(fu_ajk_cn.id)
    												   FROM fu_ajk_cn
    												   INNER JOIN fu_ajk_costumer ON fu_ajk_cn.id_cost = fu_ajk_costumer.id
    												   INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
    												   INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id = fu_ajk_peserta.id_klaim
    												   INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
    												   WHERE fu_ajk_cn.del IS NULL AND
    								  						 fu_ajk_peserta.del IS NULL AND
    								  						 fu_ajk_cn.id_cost="' . $q['id_cost'] . '"
    								  						 '.$metCabangCentral.' '.$typeKlaim.' ' . $satu . ' ' . $dua . ' ' . $tiga . ' ' . $empat . ' ' . $lima . ' ' . $enam . '  '.$tujuh.' '.$delapan.''));
    }

            $_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;

    //$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_peserta WHERE id_cost="'.$q['id_cost'].'" '.$QueryInput.' AND '.$metCabangCentral.' '.$QueryInput.' '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' '.$typedata.' AND del IS NULL'));
    $totalRows = $totalRows[0];
    $_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
    while ($fudata = mysql_fetch_array($data)) {
        //$met_dn = mysql_fetch_array($database->doQuery('SELECT id, id_dn, dn_kode FROM fu_ajk_dn WHERE id="' . $fudata['id_dn'] . '"'));
        //$met_peserta = mysql_fetch_array($database->doQuery('SELECT *, IF(type_data="SPK", kredit_tenor * 12, kredit_tenor) AS tenornya FROM fu_ajk_peserta WHERE id_cost="' . $fudata['id_cost'] . '" AND id_polis="' . $fudata['id_nopol'] . '" AND id_peserta="' . $fudata['id_peserta'] . '"'));
        // MA-J//
        $awal = explode("-", $fudata['kredit_tgl']);
        $hari = $awal[2];
        $bulan = $awal[1];
        $tahun = $awal[0];
        $akhir = explode("-", $fudata['tgl_claim']);
        $hari2 = $akhir[2];
        $bulan2 = $akhir[1];
        $tahun2 = $akhir[0];
        $jhari = (mktime(0, 0, 0, $bulan2, $hari2, $tahun2) - mktime(0, 0, 0, $bulan, $hari, $tahun)) / 86400;
        $sisahr = floor($jhari);
        $sisabulan = ceil($sisahr / 30.4375);
        // MA-J//
        // MA-S//
        $masisa = $fudata['tenornya'] - $sisabulan;
        // MA-S//
        /*
    if ($fudata['type_claim']=="Death") {
        $met_cn = '<a href="aajk_report.php?er=_erKlaim&idC='.$fudata['id'].'" target="_blank">'.substr($fudata['id_cn'], 3).'</a>';
    }else{
        $met_cn = '<a href="aajk_report.php?er=_erBatal&idC='.$fudata['id'].'" target="_blank">'.substr($fudata['id_cn'], 3).'</a>';
    }
    */
        if (empty($fudata['id_cn'])) {
            /*	02122016 Data CN belum di create
                $id_cn='0000000000'.$fudata['id'];
                $id_cn='ACN'.$fudata['id_nya'].substr($id_cn, -10);
            */
            $id_cn = '';
        } else {
            $id_cn=$fudata['id_cn'];
        }
        if ($fudata['type_claim'] == "Death") {
            $met_cn = '<a href="aajk_report.php?er=_erKlaim&idC=' . $fudata['id'] . '" target="_blank">' . $id_cn . '</a>';
        } elseif ($fudata['type_claim'] == "Refund") {
            $met_cn = '<a href="aajk_report.php?er=_eRefund&idC=' . $fudata['id'] . '" target="_blank">' . $id_cn . '</a>';
        } else {
            $met_cn = '<a href="aajk_report.php?er=_eBatal&idC=' . $fudata['id'] . '" target="_blank">' . $id_cn . '</a>';
        }

        if ($fudata['type_claim'] == "Death") {
            $statusDN_ = '<a href="aajk_report.php?er=_kwipeserta&idn=' . $fudata['id_dn'] . '" target="_blank">' . substr($fudata['dn_kode'], 3) . '</a>';
            $type_klaimnya = "Meninggal";

            $cekDokumenLengkap = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_klaim_doc WHERE id_pes="'.$fudata['id_peserta'].'" AND nama_dokumen IS NULL'));

            if (is_null($fudata['tgl_document_lengkap']) or $fudata['tgl_document_lengkap']=='0000-00-00') {
                $_dokKlaim = '<a href="ajk_nota.php?er=vKlaim&sett=fixdoc&idk='.$fudata['id'].'">Belum Lengkap</a>';
            } else {
                $_dokKlaim = '<a href="ajk_nota.php?er=vKlaim&sett=fixdoc&idk='.$fudata['id'].'"><b>Lengkap</b></a>';
            }

            /*
            if ($cekDokumenLengkap['id']) {
                $_dokKlaim = '<a href="ajk_nota.php?er=vKlaim&sett=fixdoc&idk='.$fudata['id'].'">Belum Lengkap</a>';
            }else{
                $_dokKlaim = '<a href="ajk_nota.php?er=vKlaim&sett=fixdoc&idk='.$fudata['id'].'"><b>Lengkap</b></a>';
            }*/

    /* 21082016
        $statusDokumen = mysql_fetch_array($database->doQuery('SELECT fu_ajk_klaim.id, fu_ajk_klaim_status.status_klaim FROM fu_ajk_klaim LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim.id_klaim_status = fu_ajk_klaim_status.id WHERE fu_ajk_klaim.id_cn = "'.$fudata['id'].'"'));
        if ($statusDokumen['status_klaim']=="") {
            $_dokKlaim = '<a href="ajk_nota.php?er=vKlaim&sett=fixdoc&idk='.$fudata['id'].'"><b>Menunggu Status Klaim</b></a>';
        }else{
            $_dokKlaim = '<a href="ajk_nota.php?er=vKlaim&sett=fixdoc&idk='.$fudata['id'].'"><b>'.$statusDokumen['status_klaim'].'</b></a>';
        }
    */
        } elseif ($fudata['type_claim'] == "Refund") {
            $statusDN_ = substr($fudata['dn_kode'], 3);
            $type_klaimnya = $fudata['type_claim'];
            if ($fudata['fname'] == null) {
                $_dokKlaim = 'Refund';
            } else {
                $_dokKlaim = '<a href="'.$metpath.''.$fudata['fname'].'" target="blank">Refund</a>';
            }
        } elseif ($fudata['type_claim'] == "Batal") {
            $statusDN_ = substr($fudata['dn_kode'], 3);
            $type_klaimnya = $fudata['type_claim'];
            $_dokKlaim = "";
        } else {
            $statusDN_ = '<a href="aajk_report.php?er=_kwipeserta&idn=' . $fudata['id_dn'] . '" target="_blank">' . substr($fudata['dn_kode'], 3) . '</a>';
            $type_klaimnya = $fudata['type_claim'];
            $_dokKlaim = "";
        }

        if ($fudata['confirm_claim'] == "Pending") {
            $statuscn = '<font color="#DEB887"><a href="ajk_nota.php?er=vKlaim&idk=' . $fudata['id'] . '">' . $fudata['confirm_claim'] . '</a></font>';
        } elseif ($fudata['confirm_claim'] == "Rejected") {
            $statuscn = '<font color="#FF0000">' . $fudata['confirm_claim'] . '</font>';
        } elseif ($fudata['confirm_claim'] == "Approve(unpaid)") {
            $statuscn = '<font color="#0000FF">' . $fudata['confirm_claim'] . '</font>';
        } else {
            $statuscn = $fudata['confirm_claim'];
        }

        if (($no % 2) == 1) {
            $objlass = 'tbl-odd';
        } else {
            $objlass = 'tbl-even';
        }
        echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
    	  <td align="center" valign="top">' . (++$no + ($pageNow - 1) * 25) . '</td>
    	  <td align="center">' . $fudata['nmproduk'] . '</td>
    	  <td>'.$statusDN_.'</td>
    	  <td align="center">' . $met_cn . '</td>
    	  <td align="center"><!--' . $statuscn . '--></td>
    	  <td><a href="aajk_report.php?er=_erKlaim&idC='.$fudata['id'].'">' . $fudata['id_peserta'] . '</a></td>
    	  <td>' . $fudata['nama'] . '</td>
    	  <td align="center">' . _convertDate($fudata['tgl_lahir']) . '</td>
    	  <td align="center">' . $fudata['usia'] . '</td>
    	  <td align="center">' . _convertDate($fudata['kredit_tgl']) . '</td>
    	  <td align="right">' . duit($fudata['kredit_jumlah']) . '</td>
    	  <td align="center">' . $fudata['tenornya'] . '</td>
    	  <td align="center">' . _convertDate($fudata['kredit_akhir']) . '</td>
    	  <td align="right">' . duit($fudata['totalpremi']) . '</td>
    	  <td align="center">' . $type_klaimnya . '</td>
    	  <td align="center">' . _convertDate($fudata['tgl_claim']) . '</td>
    	  <td align="center">' . $sisabulan . '</td>
    	  <td align="center">' . $masisa . '</td>
    	  <!--<td align="right">' . duit($fudata['total_claim']) . '</td>--!>
    	  <!--<td align="center">' . _convertDate($fudata['tgl_byr_claim']) . '</td>--!>
    	  <td align="center">'.$_dokKlaim.'</td>
    	  <td align="center">' . $fudata['id_cabang'] . '</td>
    	  </tr>';
    }
            echo '<tr><td colspan="22">';
            echo createPageNavigations($file = 'ajk_nota.php?er=cn&subcat=' . $_REQUEST['subcat'] . '&eRcab=' . $_REQUEST['eRcab'] . '&req='.$_REQUEST['req'].'&eRreg=' . $_REQUEST['eRreg'] . '&cat=' . $_REQUEST['cat'] . '&typeclaim=' . $_REQUEST['typeclaim'] . '&snama='.$_REQUEST['snama'].'&tgl1='.$_REQUEST['tgl1'].'&tgl2='.$_REQUEST['tgl2'], $total = $totalRows, $psDeh = 10, $anchor = '', $perPage = 25);
            echo '<b>Total Data '.$typereq.': <u>' . $totalRows . '</u></b></td></tr>';
            echo '</table>'; ;
  break;

  case "vKlaim":
    $futgltime = date("YmdHis");
    $met_klaim = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cn WHERE id="' . $_REQUEST['idk'] . '"'));
    $met_oderklaim = mysql_fetch_array($database->doQuery('SELECT * FROM ajk_order_klaim WHERE idcost="' . $met_klaim['id_cost'] . '" AND idproduk="' . $met_klaim['id_nopol'] . '" AND iddn="' . $met_klaim['id_dn'] . '" AND idcn="' . $met_klaim['id'] . '" AND idpeserta="' . $met_klaim['id_peserta'] . '"'));
    $met_klaim_ = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_klaim WHERE id_cost="' . $met_klaim['id_cost'] . '" AND id_dn="' . $met_klaim['id_dn'] . '" AND id_cn="' . $met_klaim['id'] . '" AND id_peserta="' . $met_klaim['id_peserta'] . '"'));
    $met_klaim_cost = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_costumer WHERE id="' . $met_klaim['id_cost'] . '"'));
    $met_klaim_polis = mysql_fetch_array($database->doQuery('SELECT id, id_cost, nopol, nmproduk FROM fu_ajk_polis WHERE id="' . $met_klaim['id_nopol'] . '" AND id_cost="' . $met_klaim['id_cost'] . '" AND del IS NULL'));
    $met_klaim_peserta = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_cost="' . $met_klaim['id_cost'] . '" AND id_polis="' . $met_klaim['id_nopol'] . '" AND id_peserta="' . $met_klaim['id_peserta'] . '" AND id_dn="' . $met_klaim['id_dn'] . '" AND id_klaim="' . $met_klaim['id'] . '" AND status_peserta="Death"'));
    $met_klaim_dn = mysql_fetch_array($database->doQuery('SELECT id, id_cost, id_nopol, dn_kode, dn_status, tgl_dn_paid FROM fu_ajk_dn WHERE id_cost="' . $met_klaim['id_cost'] . '" AND id_nopol="' . $met_klaim['id_nopol'] . '" AND id="' . $met_klaim['id_dn'] . '"'));
    $met_penyakit = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_namapenyakit WHERE id="'.$met_klaim['nmpenyakit'].'"'));

    echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
    	<tr><th colspan="8" width="100%" align="left" colspan="2">Modul Klaim Meninggal Worksheet</font></th>
    		<!--<th width="5%" colspan="2"><a title="kembali ke halaman sebelumnya" href="ajk_nota.php?er=cn"><img src="image/Backward-64.png" width="20"></a></th>-->';
    if ($_REQUEST['sett']=="fixdoc") {
        echo '<th width="5%" colspan="2"><a title="kembali ke halaman sebelumnya" href="ajk_nota.php?er=cn"><img src="image/Backward-64.png" width="20"></a></th>';
        if ($met_klaim['confirm_claim'] == "Pending" or $met_klaim['confirm_claim'] =="Approve(unpaid)") {
            $sendtoadonai = '<th width="5%" colspan="2"><a title="Edit data Klaim" href="ajk_klaim.php?er=eKlaim&sett=fixdoc&idc=' . $met_klaim['id'] . '"><img src="image/book-edit.png" width="20"></a></th>';
        } else {
            $sendtoadonai = '<th width="5%" colspan="2"><a title="data klaim tidak bisa diedit karena status sudah '.$met_klaim['confirm_claim'].'" href="#"><img src="image/book-edit.png" width="20"></a></th>';
        }
    } else {
        echo '<th width="5%" colspan="2"><a title="kembali ke halaman sebelumnya" href="ajk_klaim.php?er=valKlaim"><img src="image/Backward-64.png" width="20"></a></th>';
        if ($met_klaim['confirm_claim'] == "Pending" or $met_klaim['confirm_claim'] =="Approve(unpaid)") {
            $sendtoadonai = '<th width="5%" colspan="2"><a title="Edit data Klaim" href="ajk_klaim.php?er=eKlaim&idc=' . $met_klaim['id'] . '"><img src="image/book-edit.png" width="20"></a></th>';
        } else {
            $sendtoadonai = '<th width="5%" colspan="2"><a title="data klaim tidak bisa diedit karena status sudah '.$met_klaim['confirm_claim'].'" href="#"><img src="image/book-edit.png" width="20"></a></th>';
        }
    }
    echo '' . $sendtoadonai . '
    	</tr>
    	</table><br />';

    $mets = datediff($met_klaim_peserta['kredit_tgl'], $met_klaim['tgl_claim']);
    $usiapolis = explode(",", $mets);

    if ($met_klaim_peserta['type_data']=="SPK") {
        $tenornya_ = $met_klaim_peserta['kredit_tenor'] * 12;
    } else {
        $tenornya_ = $met_klaim_peserta['kredit_tenor'];
    }
    echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
    	  <tr><td width="20%">Nama Perusahaan</td><td colspan="3">: ' . $met_klaim_cost['name'] . '</td></tr>
    	  <tr><td>Nama</td><td width="50%">: ' . $met_klaim_peserta['nama'] . '</td><td width="15%">Nama Produk</td><td>: ' . $met_klaim_polis['nmproduk'] . '</td></tr>
    	  <tr><td>Id Peserta</td><td>: ' . $met_klaim_peserta['id_peserta'] . '</td><td>Debit Note</td><td>: ' . $met_klaim_dn['dn_kode'] . '</td></tr>
    	  <tr><td>Tanggal Lahir</td><td>: ' . _convertDate($met_klaim_peserta['tgl_lahir']) . '</td><td>Credit Note</td><td>: ' . $met_klaim['id_cn'] . '</td></tr>
    	  <tr><td>Usia</td><td>: ' . $met_klaim_peserta['usia'] . ' tahun</td><td>Tanggal Bayar DN</td><td>: ' . _convertDate($met_klaim_peserta['tgl_bayar']) . '</td></tr>
    	  <tr><td>Uang Pertanggungan</td><td>: ' . duit($met_klaim_peserta['kredit_jumlah']) . '</td><td>Nilai Outstanding</td><td>: ' . duit($met_klaim['total_claim']) . '</td></tr>
    	  <tr><td>Tuntutan Klaim</td><td>: ' . duit($met_klaim['tuntutan_klaim']) . '</td><td>Tanggal Meninggal</td><td>: ' . _convertDate($met_klaim['tgl_claim']) . '</td></tr>
    	  <tr><td>Tanggal Mulai Asuransi</td><td>: ' . _convertDate($met_klaim_peserta['kredit_tgl']) . '</td><td>Usia Polis</td><td>: ' . $usiapolis[0] . ' Tahun ' . $usiapolis[1] . ' Bulan ' . $usiapolis[2] . ' Hari</td></tr>
    	  <tr><td>Tanggal Akhir Asuransi</td><td>: ' . _convertDate($met_klaim_peserta['kredit_akhir']) . '</td><td>Nomor Perjanjian Kredit</td><td>: <b>' . $met_klaim['noperkredit'] . '</b></td></tr>
    	  <tr><td>Tenor</td><td>: ' . $tenornya_ . ' Bulan</td>
    	  <tr><td>Penyabab Meninggal</td><td>: <b>' . $met_penyakit['namapenyakit'] . '</b></td><!--<td>Status Klaim</td><td>: <b>' . $met_oderklaim['status'] . '</b></td>--></tr>
    	  <tr><td>Tempat Meninggal</td><td>: <b>' . $met_klaim_['tempat_meninggal'] . '</b></td></tr>
    	  <tr><td>Keterangan Staff</td><td colspan="3">: <b>' . ucfirst($met_oderklaim['note']) . '</b></td></tr>
    	  </table><br />';
    if ($_REQUEST['vklaimDoc'] == "UploadDok") {
        //$met_dokUser = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_klaim_doc WHERE id="' . $_REQUEST['iddoc'] . '"'));
        $met_dokUser = mysql_fetch_array($database->doQuery('SELECT
    fu_ajk_klaim_doc.id,
    fu_ajk_klaim_doc.id_pes,
    fu_ajk_klaim_doc.id_cost,
    fu_ajk_klaim_doc.id_dn,
    fu_ajk_klaim_doc.id_klaim,
    fu_ajk_klaim_doc.dokumen,
    fu_ajk_klaim_doc.nama_dokumen,
    fu_ajk_dokumenklaim_bank.id_dok,
    fu_ajk_dokumenklaim.nama_dok
    FROM fu_ajk_klaim_doc
    INNER JOIN fu_ajk_dokumenklaim_bank ON fu_ajk_klaim_doc.dokumen = fu_ajk_dokumenklaim_bank.id
    INNER JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim_bank.id_dok = fu_ajk_dokumenklaim.id
    WHERE fu_ajk_klaim_doc.id ="' . $_REQUEST['iddoc'] . '"'));
        //$met_dokNama = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dokumenklaim WHERE id="' . $met_dokUser['dokumen'] . '"'));
        if ($_REQUEST['el'] == "upload_spk") {
            if ($_FILES['fileklaim']['name'] == "") {
                $errno = "<font color=red>Dokumen <b>" . $met_dokUser['nama_dok'] . "!</b> belum diupload</font><br />";
            }
            if ($_FILES['fileklaim']['name'] != "" and $_FILES['fileklaim']['type'] != "application/pdf") {
                $errno = "<font color=red>File " . $met_dokUser['nama_dok'] . " harus Format PDF !</font><br />";
            }
            if ($_FILES['fileklaim']['size'] / 1024 > $met_spaksize) {
                $errno = "<font color=red>File " . $met_dokUser['nama_dok'] . " tidak boleh lebih dari 1Mb !</font><br />";
            }
            if ($errno) {
                echo '<center>' . $errno . '</center><meta http-equiv="refresh" content="3;URL=ajk_nota.php?er=vKlaim&vklaimDoc=UploadDok&idk=' . $met_dokUser['id_klaim'] . '&iddoc=' . $met_dokUser['id'] . '">';
            } else {
                $met_upload_data = $database->doQUery('UPDATE fu_ajk_klaim_doc SET nama_dokumen="' . $futgltime.'_'.$met_klaim['id_peserta'] . '_' . $met_klaim_peserta['nama'] . '_' . $_FILES["fileklaim"]["name"] . '", update_by="' . $q['id'] . '", update_date="' . $futgl . '" WHERE id="' . $met_dokUser['id'] . '"');
                //Notif E-Office
                $usernotif = $database->doQUery('select * from adnoffice.usermst where UserDepartment = "Klaim" and usermst.Lock = 0');
                while ($usernotifr = mysql_fetch_array($usernotif)) {
                    $nama_dok = str_replace('"', '\"', $met_dokUser['nama_dok']);
                    $database->doQUery('INSERT adnoffice.notification
    																					SET namanotif="Klaim Upload",
    																							description = "'.$q['nm_user'].' Upload File '.$nama_dok.' '.$met_klaim_peserta['nama'].'",
    																							icon="<i class=\"fa fa-star media-object bg-orange\"></i>",
    																							noticdate="'.$futgl.'",
    																							notifuser="'.$usernotifr['UserID'].'",
    																							link="ajk/adonai1409ajk/ajk_claim.php?d=wklaim&id='.$met_klaim['id'].'",
    																							notiffrom="'.$q['nm_user'].'"');
                }
                move_uploaded_file($_FILES['fileklaim']['tmp_name'], $dok_klaim_ajk . $futgltime.'_'.$met_klaim['id_peserta'] . '_' . $met_klaim_peserta['nama'] . '_' . $_FILES["fileklaim"]["name"]);
                if ($_REQUEST['sett']=="fixdoc") {
                    echo '<center>File telah berhasil diupload.</center><meta http-equiv="refresh" content="1;URL=ajk_nota.php?er=vKlaim&sett='.$_REQUEST['sett'].'&idk=' . $met_dokUser['id_klaim'] . '">';
                } else {
                    echo '<center>File telah berhasil diupload.</center><meta http-equiv="refresh" content="1;URL=ajk_nota.php?er=vKlaim&idk=' . $met_dokUser['id_klaim'] . '">';
                }

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


                $mailsupervisorajk = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="" AND id_polis="" AND status="CLAIM" AND aktif="Y"');
                while ($_mailsupervisorajk = mysql_fetch_array($mailsupervisorajk)) {
                    $mail->AddAddress($_mailsupervisorajk['email'], $_mailsupervisorajk['nm_lengkap']); //To address who will receive this email
                }
                $mail->SetFrom($q['email'], $q['nm_lengkap']); //From address of the mail
                        $mail->Subject = "AJKOnline - Berkas Klaim Telah Diupload oleh ".$q['nm_lengkap']; //Subject od your mail


                        $message .='<html><head><title>AJKOnline -  Upload data klaim a.n '.$met_klaim_peserta['nama'].'</title></head><body>
    							<b>To All Team,</b><br><br>
    							Data dokumen klaim '.$met_dokUser['nama_dok'].' telah diupload
    							<br><br>
    							Terima kasih

    							</body></html>
    							';

                $mailOffice = $database->doQuery('SELECT * FROM fu_ajk_mail WHERE type="Klaim" AND status="Aktif"');
                while ($mailOffice_ = mysql_fetch_array($mailOffice)) {
                    $mail->AddAddress($mailOffice_['emailto'], $mailOffice_['emailnama']); //To address who will receive this email
                }

                // $mail->AddCC("adn.info.notif@gmail.com");
                // $mail->AddCC("rahmad@adonaits.co.id");
                // $mail->AddCC("it@adonai.co.id");

                $mail->MsgHTML($message); //Put your body of the message you can place html code here
                        //$send = $mail->Send(); //Send the mails
                        //echo $message;
            }
        } else {
            echo '<form method="post" action="" enctype="multipart/form-data">
    	  <input type="hidden" name="sett" value="'.$_REQUEST['sett'].'">
    	  <table border="0" cellpadding="3" cellspacing="0" width="100%">
    	  <tr><th width="45%">Nama Dokumen</th><th>Upload File</th></tr>
    	  <tr><td>' . $met_dokUser['nama_dok'] . '<br /><font color="red" size="2">file harus format .pdf</font></td><td><input name="fileklaim" type="file" size="50" onchange="checkfile(this);" accept="application/pdf">
    	  <input type="hidden" name="el" value="upload_spk"><input name="upload" type="submit" value="Upload File">
    	  </td></tr>
    	  </table></form>';
        }
    }
    if ($_REQUEST['sett']=="fixdoc") {
    } else {
        $cekInfoKlaim = mysql_fetch_array($database->doQuery('SELECT * FROM ajk_order_klaim WHERE idcost="'.$met_klaim['id_cost'].'" AND idproduk="'.$met_klaim['id_nopol'].'" AND iddn="'.$met_klaim['id_dn'].'" AND idcn="'.$met_klaim['id'].'" AND idpeserta="'.$met_klaim['id_peserta'].'"'));
        if ($cekInfoKlaim['id']) {
            echo '<font color="blue"><b>Menunggu konfirmasi Supervisor</b></font>';
        } else {
            $cekKelengkapanDok = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_klaim_doc WHERE id_cost="' . $met_klaim['id_cost'] . '" AND id_pes="' . $met_klaim['id_peserta'] . '" AND id_dn="' . $met_klaim['id_dn'] . '" AND id_klaim="' . $met_klaim['id'] . '" AND nama_dokumen IS NULL'));
            if (!$cekKelengkapanDok['id']) {
                echo '<center><a title="Kirim email pengajuan klaim" href="ajk_nota.php?er=KlaimDocLengkap&idk='.$_REQUEST['idk'].'" onClick="if(confirm(\'Kirim persetujuan pengajuan klaim ke bagian Supervisor ?\')){return true;}{return false;}"><img src="image/save.png" width="35"><br /><b>Buat Data Pengajuan Klaim</b></a></center><br />';
            } else {
                echo '<center><a title="Kirim email pengajuan klaim" href="ajk_nota.php?er=KlaimDocTdkLengkap&idk='.$_REQUEST['idk'].'" onClick="if(confirm(\'Dokumen belum lengkap, apakah anda ingin tetap melanjutkan persetujuan pengajuan klaim ke bagian Supervisor ?\')){return true;}{return false;}"><img src="image/save.png" width="35"><br /><b>Buat Data Pengajuan Klaim</b></a></center><br />';
            }
        }
    }
    echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
    	  <tr><th width="1%">No</th>
    	  	  <th>Nama Dokumen</th>
    	  	  <th width="35%">Nama File Upload</th>
    	  	  <th width="15%">Keterangan Dokumen</th>
    	  	  <th width="10%">User Input</th>
    	  	  <th width="15%">Tanggal Input</th>
    	  	  <th width="1%">Hapus</th>
    	  </tr>';
    $mamet_dokumen = $database->doQuery('SELECT *,DATE_FORMAT(update_date, "%d %M %Y %H:%i:%s") AS update_date FROM fu_ajk_klaim_doc WHERE id_cost="' . $met_klaim['id_cost'] . '" AND id_pes="' . $met_klaim['id_peserta'] . '" AND id_dn="' . $met_klaim['id_dn'] . '" AND id_klaim="' . $met_klaim['id'] . '"');
    while ($er_dokumen = mysql_fetch_array($mamet_dokumen)) {
        $met_usernya = mysql_fetch_array($database->doQuery('SELECT id, nm_lengkap FROM pengguna WHERE id="' . $er_dokumen['update_by'] . '" AND del IS NULL'));
        //NAMA DOKUMEN KLAIM//
        $_DokKlaim = mysql_fetch_array($database->doQuery('SELECT
    fu_ajk_dokumenklaim_bank.id,
    fu_ajk_dokumenklaim_bank.id_bank,
    fu_ajk_dokumenklaim_bank.id_produk,
    fu_ajk_dokumenklaim_bank.id_dok,
    fu_ajk_dokumenklaim.id,
    fu_ajk_dokumenklaim.nama_dok
    FROM
    fu_ajk_dokumenklaim_bank
    INNER JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim_bank.id_dok = fu_ajk_dokumenklaim.id
    WHERE
    fu_ajk_dokumenklaim_bank.id = "'.$er_dokumen['dokumen'].'"'));
        //NAMA DOKUMEN KLAIM//
        if ($er_dokumen['nama_dokumen']=="Non-Dokumen") {
            $_nmFile ='<b>'.$er_dokumen['nama_dokumen'].'</b>';
            $_nmFileDel ='';
        } else {
            $_nmFile ='<a href="'.$dok_klaim_ajk.''.$er_dokumen['nama_dokumen'].'" target="_blank">' . $er_dokumen['nama_dokumen'] . '</a>';
            if ($_REQUEST['sett']=="fixdoc") {
                $_nmFileDel ='<a title="hapus dokumen klaim" href="ajk_nota.php?er=del_dok&sett=fixdoc&idp=' . $met_klaim['id'] . '&id_dok=' . $er_dokumen['id'] . '" onClick="if(confirm(\'Apakah anda yakin untuk menghapus file klaim ini ?\')){return true;}{return false;}"><img src="image/deleted.png" width="20"></a>';
            } else {
                $_nmFileDel ='<a title="hapus dokumen klaim" href="ajk_nota.php?er=del_dok&idp=' . $met_klaim['id'] . '&id_dok=' . $er_dokumen['id'] . '" onClick="if(confirm(\'Apakah anda yakin untuk menghapus file klaim ini ?\')){return true;}{return false;}"><img src="image/deleted.png" width="20"></a>';
            }
        }

        if ($_REQUEST['sett']=="fixdoc") {
            $_nmFileUpload ='<a href="ajk_nota.php?er=vKlaim&vklaimDoc=UploadDok&sett=fixdoc&idk=' . $er_dokumen['id_klaim'] . '&iddoc=' . $er_dokumen['id'] . '">(Silahkan Upload Dokumen)</a>';
        } else {
            $_nmFileUpload ='<a href="ajk_nota.php?er=vKlaim&vklaimDoc=UploadDok&idk=' . $er_dokumen['id_klaim'] . '&iddoc=' . $er_dokumen['id'] . '">(Silahkan Upload Dokumen)</a>';
        }
        if ($er_dokumen['nama_dokumen'] == "") {
            $uploaddokdata = '<form method="post" action="">
    					<td colspan="5">'.$_DokKlaim['nama_dok'].'<br />'.$_nmFileUpload.'</td>
    				  </form>';
        } else {
            $uploaddokdata = '<td valign="top"><b>'.$_DokKlaim['nama_dok'].'</td>
    				  <td valign="top">'.$_nmFile.'</td>
    				  <td valign="top">'.$er_dokumen['ket_dokumen'].'</td>
    				  <td valign="top" align="center">' . $met_usernya['nm_lengkap'] . '</td>
    				  <td valign="top" align="center">' . $er_dokumen['update_date'] . '</b></td>
    				  <td valign="top" align="center">'.$_nmFileDel.'</td>';
        }
        if (($no1 % 2) == 1) {
            $objlass = 'tbl-odd';
        } else {
            $objlass = 'tbl-even';
        }
        echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
    	  <td align="center">' . ++$no1 . '</td>
    	  ' . $uploaddokdata . '
    	  </tr>';
    }
    echo '</table>';
            break;

        case "del_dok":
            $met_cek_dokumen = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_klaim_doc WHERE id="' . $_REQUEST['id_dok'] . '"'));
            unlink($dok_klaim_ajk . $met_cek_dokumen['nama_dokumen']);
            $metDelDok = $database->doQuery('UPDATE fu_ajk_klaim_doc SET nama_dokumen = NULL, update_by="' . $q['id'] . '", update_date="' . $futgl . '" WHERE id="' . $_REQUEST['id_dok'] . '"');
            if ($_REQUEST['sett']=="fixdoc") {
                header("location:ajk_nota.php?er=vKlaim&sett=$_REQUEST[sett]&idk=$_REQUEST[idp]");
            } else {
                header("location:ajk_nota.php?er=vKlaim&idk=$_REQUEST[idp]");
            }
            ;
  break;

  case "KlaimDocLengkap":
      $met_klaim = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cn WHERE id="'.$_REQUEST['idk'].'"'));
      $met_klaim_cost = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_costumer WHERE id="' . $met_klaim['id_cost'] . '"'));
      $met_klaim_polis = mysql_fetch_array($database->doQuery('SELECT id, id_cost, nopol, nmproduk FROM fu_ajk_polis WHERE id="' . $met_klaim['id_nopol'] . '" AND id_cost="' . $met_klaim['id_cost'] . '" AND del IS NULL'));
      $met_klaim_peserta = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_cost="' . $met_klaim['id_cost'] . '" AND id_polis="' . $met_klaim['id_nopol'] . '" AND id_peserta="' . $met_klaim['id_peserta'] . '" AND id_dn="' . $met_klaim['id_dn'] . '" AND id_klaim="' . $met_klaim['id'] . '" AND status_peserta="Death"'));
      $met_klaim_dn = mysql_fetch_array($database->doQuery('SELECT id, id_cost, id_nopol, dn_kode, dn_status, tgl_dn_paid FROM fu_ajk_dn WHERE id_cost="' . $met_klaim['id_cost'] . '" AND id_nopol="' . $met_klaim['id_nopol'] . '" AND id="' . $met_klaim['id_dn'] . '"'));

      $metOrderStaff = $database->doQuery('INSERT INTO ajk_order_klaim SET idcost="'.$met_klaim['id_cost'].'",
  																	 idproduk="'.$met_klaim['id_nopol'].'",
  																	 iddn="'.$met_klaim['id_dn'].'",
  																	 idcn="'.$met_klaim['id'].'",
  																	 idpeserta="'.$met_klaim['id_peserta'].'",
  																	 nama="'.$met_klaim_peserta['nama'].'",
  																	 dob="'.$met_klaim_peserta['tgl_lahir'].'",
  																	 plafond="'.$met_klaim_peserta['kredit_jumlah'].'",
  																	 tgl_akad="'.$met_klaim_peserta['kredit_tgl'].'",
  																	 nmrperjanjiankredit="'.$met_klaim['noperkredit'].'",
  																	 status="Pending",
  																	 input_by="'.$q['nm_user'].'",
  																	 input_date="'.$futgl.'"');

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

      // $mail = new PHPMailer; // call the class
      // $mail->IsSMTP();
      // $mail->Host = SMTP_HOST; //Hostname of the mail server
      // $mail->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
      $mail_spv = mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE id_cost="'.$met_klaim['id_cost'].'" AND id_polis="" AND level="6" AND del IS NULL'));
      $mail->AddAddress($mail_spv['email'], $mail_spv['nm_lengkap']); //To address who will receive this email
      $mail->SetFrom($q['email'], $q['nm_lengkap']); //From address of the mail
      $mail->Subject = "AJKOnline - PENGAJUAN KLAIM"; //Subject od your mail


      $message .='To '.$mail_spv['nm_lengkap'].',<br />Pengajuan Klaim oleh '.$q['nm_lengkap'].' berdasarkan data sebagai berikut:
  	  <table border="0" cellpadding="3" cellspacing="0" width="100%">
  	  <tr><td width="20%">Nama Perusahaan</td><td colspan="3">: ' . $met_klaim_cost['name'] . '</td></tr>
  	  <tr><td>Nama</td><td width="50%">: ' . $met_klaim_peserta['nama'] . '</td><td width="15%">Nama Produk</td><td>: ' . $met_klaim_polis['nmproduk'] . '</td></tr>
  	  <tr><td>Id Peserta</td><td>: ' . $met_klaim_peserta['id_peserta'] . '</td><td>Debit Note</td><td>: ' . $met_klaim_dn['dn_kode'] . '</td></tr>
  	  <tr><td>Tanggal Lahir</td><td>: ' . _convertDate($met_klaim_peserta['tgl_lahir']) . '</td><td>Credit Note</td><td>: ' . $met_klaim['id_cn'] . '</td></tr>
  	  <tr><td>Usia</td><td>: ' . $met_klaim_peserta['usia'] . ' tahun</td><td>Tanggal Bayar DN</td><td>: ' . _convertDate($met_klaim_peserta['tgl_bayar']) . '</td></tr>
  	  <tr><td>Uang Pertanggungan</td><td>: ' . duit($met_klaim_peserta['kredit_jumlah']) . '</td><td>Nilai Outstanding</td><td>: ' . duit($met_klaim['total_claim']) . '</td></tr>
  	  <tr><td>Tanggal Mulai Asuransi</td><td>: ' . _convertDate($met_klaim_peserta['kredit_tgl']) . '</td><td>Tanggal Meninggal</td><td>: <b>' . _convertDate($met_klaim['tgl_claim']).'</b></td></tr>
  	  <tr><td>Tanggal Akhir Asuransi</td><td>: ' . _convertDate($met_klaim_peserta['kredit_akhir']) . '</td><td>Usia Polis</td><td>: ' . $usiapolis[0] . ' Tahun ' . $usiapolis[1] . ' Bulan ' . $usiapolis[2] . ' Hari</td></tr>
  	  <tr><td>Tenor</td><td>: ' . $met_klaim_peserta['kredit_tenor'] . ' Bulan</td><td>Nomor Perjanjian Kredit</td><td>: <b>' . $met_klaim['noperkredit'] . '</b></tr>
  	  </table><br />
  	  <table border="0" cellpadding="3" cellspacing="0" width="100%">
  	  <tr><th width="1%">No</th>
  	  	  <th>Nama Dokumen</th>
  	  	  <th width="10%">Dokumen</th>
  	  </tr>';
      $mamet_dokumen = $database->doQuery('SELECT * FROM fu_ajk_klaim_doc WHERE id_cost="' . $met_klaim['id_cost'] . '" AND id_pes="' . $met_klaim['id_peserta'] . '" AND id_dn="' . $met_klaim['id_dn'] . '" AND id_klaim="' . $met_klaim['id'] . '"');
      while ($er_dokumen = mysql_fetch_array($mamet_dokumen)) {
          $metNamaDok = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dokumenklaim WHERE id="'.$er_dokumen['dokumen'].'"'));
          if ($er_dokumen['nama_dokumen'] !="") {
              $kelengkapanDok = 'Ada';
          } else {
              $kelengkapanDok = 'Tidak Ada';
          }
          if (($no1 % 2) == 1) {
              $objlass = 'tbl-odd';
          } else {
              $objlass = 'tbl-even';
          }
          $message .='<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
      		  <td align="center">'.++$no1.'</td>
      		  <td>'.$metNamaDok['nama_dok'].'</td>
      		  <td align="center">'.$kelengkapanDok.'</td>
      		  </tr>';
      }
      $message .='<tr><td colspan="3">Silahkan validasi data pengajuan klaim pada menu Master Upload dan pilih Validasi Data Pengajuan Klaim.</td></tr>
      			</table>';

          $mailOffice = $database->doQuery('SELECT * FROM fu_ajk_mail WHERE type="Klaim" AND status="Aktif"');
          while ($mailOffice_ = mysql_fetch_array($mailOffice)) {
              $mail->AddAddress($mailOffice_['emailto'], $mailOffice_['emailnama']); //To address who will receive this email
          }

          //$mail->AddCC("adn.info.notif@gmail.com");
          // $mail->AddCC("rahmad@adonaits.co.id");

          $mail->MsgHTML($message); //Put your body of the message you can place html code here
          $send = $mail->Send(); //Send the mails
          //echo $message;

          echo '<center>Pemberitahuan pengajuan klaim telah dikirim melalui email ke bagian Supervisor oleh sistem secara otomatis.<br />Pengajuan klaim akan segera diproses menunggu persetujuan oleh Supervisor.</center><meta http-equiv="refresh" content="2;URL=ajk_nota.php?er=cn">';

          ;
  break;

  case "KlaimDocTdkLengkap":
      $met_klaim = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cn WHERE id="'.$_REQUEST['idk'].'"'));
      $met_klaim_cost = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_costumer WHERE id="' . $met_klaim['id_cost'] . '"'));
      $met_klaim_polis = mysql_fetch_array($database->doQuery('SELECT id, id_cost, nopol, nmproduk FROM fu_ajk_polis WHERE id="' . $met_klaim['id_nopol'] . '" AND id_cost="' . $met_klaim['id_cost'] . '" AND del IS NULL'));
      $met_klaim_peserta = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_cost="' . $met_klaim['id_cost'] . '" AND id_polis="' . $met_klaim['id_nopol'] . '" AND id_peserta="' . $met_klaim['id_peserta'] . '" AND id_dn="' . $met_klaim['id_dn'] . '" AND id_klaim="' . $met_klaim['id'] . '" AND status_peserta="Death"'));
      $met_klaim_dn = mysql_fetch_array($database->doQuery('SELECT id, id_cost, id_nopol, dn_kode, dn_status, tgl_dn_paid FROM fu_ajk_dn WHERE id_cost="' . $met_klaim['id_cost'] . '" AND id_nopol="' . $met_klaim['id_nopol'] . '" AND id="' . $met_klaim['id_dn'] . '"'));
      $met_penyakit = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_namapenyakit WHERE id="'.$met_klaim['nmpenyakit'].'"'));
      echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
      	<tr><th colspan="8" width="100%" align="left" colspan="2">Modul Klaim Meninggal</font></th>
      		<th width="5%" colspan="2"><a title="kembali ke halaman sebelumnya" href="ajk_nota.php?er=vKlaim&idk='.$_REQUEST['idk'].'"><img src="image/Backward-64.png" width="20"></a></th>
      	</tr>
      	</table><br />';

      if ($_REQUEST['ope']=="Kirim") {
          if ($_REQUEST['dokumentdklengkap'] == "") {
              $error = "<font color=red>Alasan kelengkapan dokumen tidak boleh kosong.</font><br />";
          }
          if ($error) {
          } else {
              $metOrderStaff = $database->doQuery('INSERT INTO ajk_order_klaim SET idcost="'.$met_klaim['id_cost'].'",
      																	 idproduk="'.$met_klaim['id_nopol'].'",
      																	 iddn="'.$met_klaim['id_dn'].'",
      																	 idcn="'.$met_klaim['id'].'",
      																	 idpeserta="'.$met_klaim['id_peserta'].'",
      																	 nama="'.$met_klaim_peserta['nama'].'",
      																	 dob="'.$met_klaim_peserta['tgl_lahir'].'",
      																	 plafond="'.$met_klaim_peserta['kredit_jumlah'].'",
      																	 status="Pending",
      																	 note="'.$_REQUEST['dokumentdklengkap'].'",
      																	 input_by="'.$q['id'].'",
      																	 input_date="'.$futgl.'"');

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

          $mail_spv = mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE id_cost="'.$met_klaim['id_cost'].'" AND cabang="'.$q['cabang'].'" AND level="6" AND del IS NULL'));
              $mail->AddAddress($mail_spv['email'], $mail_spv['nm_lengkap']); //To address who will receive this email
          $mail->SetFrom($q['email'], $q['nm_lengkap']); //From address of the mail
          $mail->Subject = "AJKOnline - PENGAJUAN KLAIM"; //Subject od your mail
          //$mail->AddCC("kepodank@gmail.com");

              if ($met_klaim_peserta['type_data']=="SPK") {
                  $tenornya_ = $met_klaim_peserta['kredit_tenor'] * 12;
              } else {
                  $tenornya_ = $met_klaim_peserta['kredit_tenor'];
              }
              $mets = datediff($met_klaim_peserta['kredit_tgl'], $met_klaim['tgl_claim']);
              $usiapolis = explode(",", $mets);
              $message .='To '.$mail_spv['nm_lengkap'].',<br />Pengajuan Klaim oleh '.$q['nm_lengkap'].' berdasarkan data sebagai berikut:
      	  <table border="0" cellpadding="3" cellspacing="0" width="100%">
      	  <tr><td width="20%">Nama Perusahaan</td><td colspan="3">: ' . $met_klaim_cost['name'] . '</td></tr>
      	  <tr><td>Nama</td><td width="50%">: ' . $met_klaim_peserta['nama'] . '</td><td width="15%">Nama Produk</td><td>: ' . $met_klaim_polis['nmproduk'] . '</td></tr>
      	  <tr><td>Id Peserta</td><td>: ' . $met_klaim_peserta['id_peserta'] . '</td><td>Debit Note</td><td>: ' . $met_klaim_dn['dn_kode'] . '</td></tr>
      	  <tr><td>Tanggal Lahir</td><td>: ' . _convertDate($met_klaim_peserta['tgl_lahir']) . '</td><td>Credit Note</td><td>: ' . $met_klaim['id_cn'] . '</td></tr>
      	  <tr><td>Usia</td><td>: ' . $met_klaim_peserta['usia'] . ' tahun</td><td>Tanggal Bayar DN</td><td>: ' . _convertDate($met_klaim_peserta['tgl_bayar']) . '</td></tr>
      	  <tr><td>Uang Pertanggungan</td><td>: ' . duit($met_klaim_peserta['kredit_jumlah']) . '</td><td>Nilai Outstanding</td><td>: ' . duit($met_klaim['total_claim']) . '</td></tr>
      	  <tr><td>Tanggal Mulai Asuransi</td><td>: ' . _convertDate($met_klaim_peserta['kredit_tgl']) . '</td><td>Tanggal Meninggal</td><td>: <b>' . _convertDate($met_klaim['tgl_claim']).'</b></td></tr>
      	  <tr><td>Tanggal Akhir Asuransi</td><td>: ' . _convertDate($met_klaim_peserta['kredit_akhir']) . '</td><td>Usia Polis</td><td>: ' . $usiapolis[0] . ' Tahun ' . $usiapolis[1] . ' Bulan ' . $usiapolis[2] . ' Hari</td></tr>
      	  <tr><td>Tenor</td><td>: ' . $tenornya_ . ' Bulan</td><td>Nomor Perjanjian Kredit</td><td>: <b>' . $met_klaim['noperkredit'] . '</b></tr>
      	  </table><br />
      	  <table border="0" cellpadding="3" cellspacing="0" width="100%">
      	  <tr><th width="1%">No</th>
      	  	  <th>Nama Dokumen</th>
      	  	  <th width="5%">Dokumen</th>
      	  </tr>';
              $mamet_dokumen = $database->doQuery('SELECT * FROM fu_ajk_klaim_doc WHERE id_cost="' . $met_klaim['id_cost'] . '" AND id_pes="' . $met_klaim['id_peserta'] . '" AND id_dn="' . $met_klaim['id_dn'] . '" AND id_klaim="' . $met_klaim['id'] . '"');
              while ($er_dokumen = mysql_fetch_array($mamet_dokumen)) {
                  $_DokKlaim = mysql_fetch_array($database->doQuery('SELECT
      fu_ajk_dokumenklaim_bank.id,
      fu_ajk_dokumenklaim_bank.id_bank,
      fu_ajk_dokumenklaim_bank.id_produk,
      fu_ajk_dokumenklaim_bank.id_dok,
      fu_ajk_dokumenklaim.id,
      fu_ajk_dokumenklaim.nama_dok
      FROM
      fu_ajk_dokumenklaim_bank
      INNER JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim_bank.id_dok = fu_ajk_dokumenklaim.id
      WHERE
      fu_ajk_dokumenklaim_bank.id = "'.$er_dokumen['dokumen'].'"'));
                  if ($er_dokumen['nama_dokumen'] !="") {
                      $kelengkapanDok = 'Ada';
                  } else {
                      $kelengkapanDok = 'Tidak Ada';
                  }
                  if (($no1 % 2) == 1) {
                      $objlass = 'tbl-odd';
                  } else {
                      $objlass = 'tbl-even';
                  }
                  $message .='<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
      		  <td align="center">'.++$no1.'</td>
      		  <td>'.$_DokKlaim['nama_dok'].'</td>
      		  <td align="center">'.$kelengkapanDok.'</td>
      		  </tr>';
              }
              $message .='<tr><td colspan="3">Alasan dokumen tidak lengkap</td></tr>
      			<tr><td colspan="3">'.$_REQUEST['dokumentdklengkap'].'<br /><br /></td></tr>
      			<tr><td colspan="3">Silahkan validasi data pengajuan klaim pada menu Master Upload dan pilih Validasi Data Pengajuan Klaim.</td></tr>
      			</table>';

              $mailOffice = $database->doQuery('SELECT * FROM fu_ajk_mail WHERE type="Klaim" AND status="Aktif"');
              while ($mailOffice_ = mysql_fetch_array($mailOffice)) {
                  $mail->AddAddress($mailOffice_['emailto'], $mailOffice_['emailnama']); //To address who will receive this email
              }

              //$mail->AddCC("adn.info.notif@gmail.com");
              // $mail->AddCC("rahmad@adonaits.co.id");

              $mail->MsgHTML($message); //Put your body of the message you can place html code here
          $send = $mail->Send(); //Send the mails
          //echo '<br />'.$q['email'].'<br />';
          //echo $mail_spv['email'].'<br />';
          //echo $message.'<br />';

          echo '<center>Pemberitahuan pengajuan klaim telah dikirim melalui email ke bagian Supervisor oleh sistem secara otomatis.<br />Pengajuan klaim akan segera diproses menunggu persetujuan oleh Supervisor.</center><meta http-equiv="refresh" content="2;URL=ajk_nota.php?er=cn">';
          }
      }

      $mets = datediff($met_klaim_peserta['kredit_tgl'], $met_klaim['tgl_claim']);
      $usiapolis = explode(",", $mets);
      echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
      	  <tr><td width="20%">Nama Perusahaan</td><td colspan="3">: ' . $met_klaim_cost['name'] . '</td></tr>
      	  <tr><td>Nama</td><td width="50%">: ' . $met_klaim_peserta['nama'] . '</td><td width="15%">Nama Produk</td><td>: ' . $met_klaim_polis['nmproduk'] . '</td></tr>
      	  <tr><td>Id Peserta</td><td>: ' . $met_klaim_peserta['id_peserta'] . '</td><td>Debit Note</td><td>: ' . $met_klaim_dn['dn_kode'] . '</td></tr>
      	  <tr><td>Tanggal Lahir</td><td>: ' . _convertDate($met_klaim_peserta['tgl_lahir']) . '</td><td>Credit Note</td><td>: ' . $met_klaim['id_cn'] . '</td></tr>
      	  <tr><td>Usia</td><td>: ' . $met_klaim_peserta['usia'] . ' tahun</td><td>Tanggal Bayar DN</td><td>: ' . _convertDate($met_klaim_peserta['tgl_bayar']) . '</td></tr>
      	  <tr><td>Uang Pertanggungan</td><td>: ' . duit($met_klaim_peserta['kredit_jumlah']) . '</td><td>Nilai Outstanding</td><td>: ' . duit($met_klaim['total_claim']) . '</td></tr>
      	  <tr><td>Tanggal Mulai Asuransi</td><td>: ' . _convertDate($met_klaim_peserta['kredit_tgl']) . '</td><td>Tanggal Meninggal</td><td>: ' . _convertDate($met_klaim['tgl_claim']) . '</td></tr>
      	  <tr><td>Tanggal Akhir Asuransi</td><td>: ' . _convertDate($met_klaim_peserta['kredit_akhir']) . '</td><td>Usia Polis</td><td>: ' . $usiapolis[0] . ' Tahun ' . $usiapolis[1] . ' Bulan ' . $usiapolis[2] . ' Hari</td></tr>
      	  <tr><td>Tenor</td><td>: ' . $met_klaim_peserta['kredit_tenor'] . ' Bulan</td><td>Nomor Perjanjian Kredit</td><td>: <b>' . $met_klaim['noperkredit'] . '</b></tr>
      	  <tr><td>Penyabab Meninggal</td><td colspan="3">: <b>' . $met_penyakit['namapenyakit'] . '</b></td></tr>
      	  </table><br />';
      echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
      	  <tr><th width="1%">No</th>
      	  	  <th>Nama Dokumen</th>
      	  	  <th width="35%">Nama File Upload</th>
      	  	  <th width="10%">User Input</th>
      	  	  <th width="15%">Tanggal Input</th>
      	  	  <th width="5%">Kelengkapan</th>
      	  </tr>';
      $mamet_dokumen = $database->doQuery('SELECT * FROM fu_ajk_klaim_doc WHERE id_cost="' . $met_klaim['id_cost'] . '" AND id_pes="' . $met_klaim['id_peserta'] . '" AND id_dn="' . $met_klaim['id_dn'] . '" AND id_klaim="' . $met_klaim['id'] . '"');
      while ($er_dokumen = mysql_fetch_array($mamet_dokumen)) {
          //$metNamaDok = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dokumenklaim WHERE id="'.$er_dokumen['dokumen'].'"'));
          $met_usernya = mysql_fetch_array($database->doQuery('SELECT id, nm_lengkap FROM pengguna WHERE id="' . $er_dokumen['update_by'] . '" AND del IS NULL'));
          $_DokKlaim = mysql_fetch_array($database->doQuery('SELECT
      fu_ajk_dokumenklaim_bank.id,
      fu_ajk_dokumenklaim_bank.id_bank,
      fu_ajk_dokumenklaim_bank.id_produk,
      fu_ajk_dokumenklaim_bank.id_dok,
      fu_ajk_dokumenklaim.id,
      fu_ajk_dokumenklaim.nama_dok
      FROM fu_ajk_dokumenklaim_bank
      INNER JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim_bank.id_dok = fu_ajk_dokumenklaim.id
      WHERE fu_ajk_dokumenklaim_bank.id = "'.$er_dokumen['dokumen'].'"'));
          if ($er_dokumen['nama_dokumen'] !="") {
              $kelengkapanDok = '<font color="blue">Ada</font>';
          } else {
              $kelengkapanDok = '<font color="red">Tidak Ada</font>';
          }
          //NAMA DOKUMEN KLAIM//
          if ($er_dokumen['nama_dokumen'] == "") {
              $uploaddokdata = '<form method="post" action="">
      					<td colspan="4">'.$_DokKlaim['nama_dok'].'<br /><a href="ajk_nota.php?er=vKlaim&vklaimDoc=UploadDok&idk=' . $er_dokumen['id_klaim'] . '&iddoc=' . $er_dokumen['id'] . '">(Silahkan Upload Dokumen)</a></td>
      					<td align="center">'.$kelengkapanDok.'</td>
      				  </form>';
          } else {
              $uploaddokdata = '<td><b>'.$_DokKlaim['nama_dok'].'</td>
      				  <td><a href="'.$dok_klaim_ajk.''.$er_dokumen['nama_dokumen'].'" target="_blank">' . $er_dokumen['nama_dokumen'] . '</a></td>
      				  <td align="center">' . $met_usernya['nm_lengkap'] . '</td>
      				  <td align="center">' . $er_dokumen['update_date'] . '</b></td>
      				  <td align="center">'.$kelengkapanDok.'</td>';
          }
          if (($no1 % 2) == 1) {
              $objlass = 'tbl-odd';
          } else {
              $objlass = 'tbl-even';
          }
          echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
      		  <td align="center">' . ++$no1 . '</td>
      		  ' . $uploaddokdata . '
      		  </tr>';
      }
          echo '</table>';

      echo '<form method="post" action="">
      	  <table border="0" cellpadding="3" cellspacing="0" width="100%">
      		<tr><td valign="top" width="15%">Alasan dokumen tidak lengkap <font color="red">*</font> '.$error.'</td>
      			<td><textarea name="dokumentdklengkap" type="text"rows="3" cols="60" placeholder="Alasan dokumen tidak lengkap">'.$_REQUEST['dokumentdklengkap'].'</textarea></td>
      		</tr>
      		<tr><td>&nbsp;</td><td><input type="submit" name="ope" value="Kirim" class="button" /></td></tr>
      	  </table></form>';
          ;
  break;

  case "viewmember":
      echo '<table border="1" cellpadding="3" cellspacing="0" width="100%">
  	  <tr><th width="95%" align="left" colspan="2">Modul DN Members </font></th><th><a href="ajk_dn.php?r=viewdn">back</a></th></tr>
  	  </table>
  	  <form method="post" action="">';
      $fusdn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn WHERE id="' . $_REQUEST['id'] . '"'));
      $fucomp = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="' . $fusdn['id_cost'] . '"'));

      echo '<form method="post" action="#" onload ="onbeforeunload">
		  <table border="0" width="100%" cellpadding="3" cellspacing="1"  bgcolor="#CCDDEE">
		  <tr><td colspan="2">Perusahaan </td><td colspan="5">: ' . strtoupper($fucomp['name']) . '</td></tr>
		  <tr><td colspan="2">Regional </td><td colspan="5">: ' . $fusdn['id_regional'] . '</td></tr>
		  <tr><td colspan="2">Area  </td><td colspan="5">: ' . $fusdn['id_area'] . '</td></tr>
		  <tr><td colspan="2">Cabang  </td><td colspan="5">: ' . $fusdn['id_cabang'] . '</td></tr>
		  <tr><td colspan="2">No. DN  </td><td colspan="5">: ' . $fusdn['dn_kode'] . '</td></tr>
    	<tr><th width="1%" rowspan="2">No</th>
    		<th width="5%" rowspan="2">SPAJ</th>
    		<th width="5%" rowspan="2">No. Reg</th>
    		<th rowspan="2">Nama Debitur<br /><font size="1"><i>(sesuai KTP/SIM)</i></font></th>
    		<th width="1%" rowspan="2">P/W</th>
    		<th rowspan="2" width="5%">Tgl Lahir</th>
    		<th rowspan="2" width="1%">Usia</th>
    		<th colspan="4" width="10%">Status Kredit</th>
    		<th width="1%" rowspan="2">Premi</th>
    		<th colspan="3" width="10%">Biaya</th>
    		<th width="1%" rowspan="2">Total Premi</th>
    		<th rowspan="2" width="8%">Medical</th>
    	</tr>
    	<tr><th>Kredit Awal</th>
    		<th>Tenor</th>
    		<th>Kredit Akhir</th>
    		<th>Jumlah</th>
    		<th>Adm</th>
    		<th>Refund</th>
    		<th>Ext. Premi</th>
    	</tr>';

      $data = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id!="" AND id_dn="' . $fusdn['id'] . '" AND id_cost="' . $fusdn['id_cost'] . '" AND id_polis="' . $fusdn['id_nopol'] . '" AND del IS NULL ORDER BY cabang ASC');
      $jumdata = mysql_num_rows($data);
      while ($fudata = mysql_fetch_array($data)) {
                if ($jumdata == 1) {
                    // DISABLED (081013) $hapusdata = '<a href="ajk_dn.php?r=delUpddataDN&iddn='.$_REQUEST['id'].'&idP='.$fudata['id'].'" onClick="if(confirm(\'Anda yakin akan menghapus data peserta a/n '.addslashes($fudata['nama']).' (Reg.ID : '.$fudata['id_peserta'].') dan Penghapusan Nomor DN ?\')){return true;}{return false;}"><img src="image/deleted.png" width="20"></a>';	}
                    $hapusdata = '<a href="#" title="penghapusan data tidak di ijinkan."><img src="image/deleted.png" width="20"></a>';
                } else {
                    // DISABLED (081013) $hapusdata = '<a href="ajk_dn.php?r=delUpddata&iddn='.$_REQUEST['id'].'&idP='.$fudata['id'].'" onClick="if(confirm(\'Anda yakin akan menghapus data peserta a/n '.addslashes($fudata['nama']).' (Reg.ID : '.$fudata['id_peserta'].')?\')){return true;}{return false;}"><img src="image/deleted.png" width="20"></a>';	}
                    $hapusdata = '<a href="#" title="penghapusan data tidak di ijinkan."><img src="image/deleted.png" width="20"></a>';
                }

                $met_spk = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak WHERE id_cost="' . $fudata['id_cost'] . '" AND id_polis="' . $fudata['id_polis'] . '" AND spak="' . $fudata['spaj'] . '"'));
                // $metTrate = $mametrate['rate'] * (1 - $met_spk['ext_premi'] / 100);
                $em = $fudata['premi'] * $met_spk['ext_premi'] / 100;
                $totalpreminya = $fudata['premi'] + $em;
                if (($no % 2) == 1) {
                    $objlass = 'tbl-odd';
                } else {
                    $objlass = 'tbl-even';
                }
                echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
      <td align="center">' . ++$no . '</td>
      <td align="center">' . $fudata['spaj'] . '</td>
      <td>' . $fudata['id_peserta'] . '</td>
      <td>' . $fudata['nama'] . '</td>
      <td align="center">' . $fudata['gender'] . '</td>
      <td align="center">' . _convertDate($fudata['tgl_lahir']) . '</td>
      <td align="center">' . $fudata['usia'] . '</td>
      <td align="center">' . _convertDate($fudata['kredit_tgl']) . '</td>
      <td align="center">' . $fudata['kredit_tenor'] . '</td>
      <td align="center">' . _convertDate($fudata['kredit_akhir']) . '</td>
      <td align="right">' . duit($fudata['kredit_jumlah']) . '</td>
      <td align="right">' . duit($fudata['premi']) . '</td>
      <td align="right">' . duit($fudata['biaya_adm']) . '</td>
      <td align="right">' . duit($fudata['biaya_refund']) . '</td>
      <td align="right">' . duit($em) . '</td>
      <td align="right">' . duit($totalpreminya) . '</td>
      <td align="center">' . $fudata['status_medik'] . '</td>
      <!--<td align="center">' . $hapusdata . '</td>-->
      </tr>';
            $jkredit += $fudata['kredit_jumlah'];
            $jpremi += $fudata['premi'];
            $exjpremi += $em;
            $jtpremi += $totalpreminya;
        }
        echo '<tr><th colspan="10">Total</th>
		  	  <th>' . duit($jkredit) . '</th>
		  	  <th>' . duit($jpremi) . '</th><th>&nbsp;</th><th>&nbsp;</th>
			  <th>' . duit($exjpremi) . '</th>
		  	  <th>' . duit($jtpremi) . '</th><th>&nbsp;</th>
		  </tr></table>
		  </form>'; ;
  break;

    default: ;
} // switch

?>
<SCRIPT language=JavaScript>
function reload(form)
{
	var val=form.cat.options[form.cat.options.selectedIndex].value;
	self.location='ajk_nota.php?er=dn&cat=' + val;
}
</script>

<SCRIPT language=JavaScript>
function reloadcn(form)
{
	var val=form.cat.options[form.cat.options.selectedIndex].value;
	self.location='ajk_nota.php?er=cn&cat=' + val;
}
</script>
