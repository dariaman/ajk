<?php

// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ADONAI - AJK Online 2014
// ----------------------------------------------------------------------------------
include_once("ui.php");
include_once("../includes/functions.php");
connect();
if (session_is_registered('nm_user')) {
    $q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));
}
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%"><tr><th width="95%" align="left">Modul Laporan Rekapitulasi Asuransi</font></th>'.$metnewuser.'</tr></table>';
switch ($_REQUEST['r']) {
    case "s":
        ;
        break;
    case "d":
        ;
        break;
    default:
$metcost = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name DESC');
$metAsuransi = $database->doQuery('SELECT * FROM fu_ajk_asuransi ORDER BY name ASC');
echo '<form method="post" action="" name="postform">
	  <input type="hidden" name="id_cost" value="'.$metcost['id'].'">
	  <table border="0" cellpadding="1" cellspacing="0" width="100%">
      <tr><td width="40%" align="right">Nama Perusahaan <font color="red">*</font></td><td> : <select name="id_cost" id="id_cost">
		  	<option value="">---Pilih Perusahaan---</option>';
while ($metcost_ = mysql_fetch_array($metcost)) {
    echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['id_cost'], $metcost_['id']).'>'.$metcost_['name'].'</option>';
}
echo '</select></td></tr>
	  <tr><td align="right">Nama Produk</td>
		<td id="polis_rate">: <select name="id_polis" id="id_polis">
		<option value="">-- Semua Produk --</option>
		</select></td></tr>
	  <tr><td align="right">Pilih Asuransi</td>
		<!--<td id="polis_rate">: <select name="id_as" id="id_as">
		<option value="">-- Semua Asuransi --</option>
		</select></td> DISABLED 04112015-->
		<td id="polis_rate">: <select name="id_as" id="id_as">
		<option value="">-- Semua Asuransi --</option>';
while ($metAsuransi_ = mysql_fetch_array($metAsuransi)) {
    echo  '<option value="'.$metAsuransi_['id'].'"'._selected($_REQUEST['id_as'], $metAsuransi_['id']).'>'.$metAsuransi_['name'].'</option>';
}
echo '</select></td></tr>
	  <tr><td align="right">Tanggal Mulai Asuransi</td>
	  <td> : <input type="text" id="fromakad1" name="tglakad3" value="'.$_REQUEST['tglakad3'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromakad1);return false;"/>
			<a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromakad1);return false;">
			<img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
			<iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
	  		s/d
	  		<input type="text" id="fromakad2" name="tglakad4" value="'.$_REQUEST['tglakad4'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromakad2);return false;"/>
			<a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromakad2);return false;">
			<img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
			<iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
	  </td>
	  </tr>
	  <tr><td align="right">Tanggal Debit Note</td>
	  <td> : <input type="text" id="fromdn1" name="tglakad1" value="'.$_REQUEST['tglakad1'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromdn1);return false;"/>
			<a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromdn1);return false;">
			<img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
			<iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
	  		s/d
	  		<input type="text" id="fromdn2" name="tglakad2" value="'.$_REQUEST['tglakad2'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromdn2);return false;"/>
			<a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromdn2);return false;">
			<img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
			<iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
	  </td>
	  </tr>
	  <tr><td align="right">Tanggal Lapor Asuransi</td>
	  	  <td> : <input type="text" id="fromdn3" name="tgltrans1" value="'.$_REQUEST['tgltrans1'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromdn3);return false;"/>
				 <a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromdn3);return false;">
				 <img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
				 <iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
				 s/d
				 <input type="text" id="fromdn4" name="tgltrans2" value="'.$_REQUEST['tgltrans2'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromdn4);return false;"/>
				 <a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromdn4);return false;">
				 <img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
				 <iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
			</td>
	  </tr>
	  <tr><td align="right">Status Pembayaran </td><td> :
			  <select size="1" name="paiddata"><option value="">--- Status ---</option>
			  								  <option value="paid"'._selected($_REQUEST['paiddata'], "paid").'>Lunas</option>
											  <option value="unpaid"'._selected($_REQUEST['paiddata'], "unpaid").'>Belum Bayar</option>
											  <option value="Kurang Bayar"'._selected($_REQUEST['paiddata'], "Kurang Bayar").'>Kurang Bayar</option>
			  </select>
	  </td></tr>
	  <tr><td align="right">Status Peserta </td><td> :
			  <select size="1" name="statpeserta"><option value="">--- Status Peserta---</option>
					<option value="Inforce"'._selected($_REQUEST['statpeserta'], "Inforce").'>Inforce</option>
					<option value="Maturity"'._selected($_REQUEST['statpeserta'], "Maturity").'>Maturity</option>
					<option value="Pending"'._selected($_REQUEST['statpeserta'], "Pending").'>Pending</option>
					<option value="Lapse-Batal"'._selected($_REQUEST['statpeserta'], "Lapse-Batal").'>Lapse - Batal</option>
					<option value="Lapse-Refund"'._selected($_REQUEST['statpeserta'], "Lapse-Refund").'>Lapse - Refund</option>
					<option value="Lapse-Death"'._selected($_REQUEST['statpeserta'], "Lapse-Death").'>Lapse - Meninggal</option>
			  </select>
	  </td></tr>
	  <tr><td align="right">Regional</td>
		<td id="polis_rate">: <select name="id_reg" id="id_reg">
		<option value="">-- Pilih Regional --</option>
		</select></td></tr>
	  <tr><td align="right">Cabang</td>
		<td id="polis_rate">: <select name="id_cab" id="id_cab">
		<option value="">-- Pilih Cabang --</option>
		</select></td></tr>
	  <tr><td align="center"colspan="2"><input type="hidden" name="re" value="dataasuransi"><input type="submit" name="ere" value="Cari"></td></tr>
	  </table>
	  </form>';
if ($_REQUEST['re']=="dataasuransi") {
    if ($_REQUEST['id_cost']=="") {
        $error_1 = '<div align="center"><font color="red"><blink>Silahkan pilih nama Costumer...!!<br /></div></font></blink>';
    }
    if ($_REQUEST['tglakad3']=="") {
        $error_2 = '<div align="center"><font color="red"><blink>Tanggal mulai asuransi tidak boleh kosong<br /></div></font></blink>';
    }
    if ($_REQUEST['tglakad4']=="") {
        $error_3 = '<div align="center"><font color="red"><blink>Tanggal akhir akad tidak boleh kosong</div></font></blink>';
    }
    if ($_REQUEST['tglakad1']=="") {
        $error_4 = '<div align="center"><font color="red"><blink>Tanggal mulai debitnote tidak boleh kosong<br /></div></font></blink>';
    }
    if ($_REQUEST['tglakad2']=="") {
        $error_5 = '<div align="center"><font color="red"><blink>Tanggal akhir debitnote tidak boleh kosong</div></font></blink>';
    }
    if ($error_1) {
        echo $error_1;
    } else {
        if ($_REQUEST['id_cost']) {
            $satu = 'AND fu_ajk_peserta.id_cost = "'.$_REQUEST['id_cost'].'"';
        }
        if ($_REQUEST['id_polis']) {
            $dua = 'AND fu_ajk_peserta.id_polis = "'.$_REQUEST['id_polis'].'"';
        }
        if ($_REQUEST['tglakad1']) {
            $tiga = 'AND fu_ajk_dn.tgl_createdn BETWEEN "'.$_REQUEST['tglakad1'].'" AND "'.$_REQUEST['tglakad2'].'"';
            $tanggaldebitnote ='<tr><td colspan="2">Tanggal Debit Note</td><td colspan="14">: '._convertDate($_REQUEST['tgldn1']).' s/d '._convertDate($_REQUEST['tgldn2']).'</td></tr>';
        }
        if ($_REQUEST['paiddata']) {
            if ($_REQUEST['paiddata']=="Kurang Bayar") {
                $empat = 'AND fu_ajk_dn.dn_status IN ("kurang bayar", "paid(*)")';
            } else {
                $empat = 'AND fu_ajk_dn.dn_status = "'.$_REQUEST['paiddata'].'"';
            }
        }
        if ($_REQUEST['id_reg']) {
            $met_reg = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['id_reg'].'"'));
            $lima = 'AND fu_ajk_peserta.regional = "'.$met_reg['name'].'"';
        }
        if ($_REQUEST['id_reg']=="") {
            $status_regional = "SEMUA REGIONAL";
        } else {
            $status_regional = $met_reg['name'];
        }
        if ($_REQUEST['id_cab']) {
            $met_cab = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id="'.$_REQUEST['id_cab'].'"'));
            $enam = 'AND fu_ajk_peserta.cabang = "'.$met_cab['name'].'"';
        }
        if ($_REQUEST['id_cab']=="") {
            $status_cabang = "SEMUA CABANG";
        } else {
            $status_cabang = $met_cab['name'];
        }

        if ($_REQUEST['statpeserta']) {
            $status_ = explode("-", $_REQUEST['statpeserta']);
            if (!$status_[1]) {
                $tujuh = 'AND fu_ajk_peserta.status_aktif = "'.$status_[0].'"';
            } else {
                $tujuh = 'AND fu_ajk_peserta.status_aktif = "'.$status_[0].'"';
                $delapan = 'AND fu_ajk_peserta.status_peserta = "'.$status_[1].'"';
            }
        }
        if ($_REQUEST['statpeserta']=="") {
            $statuspeserta_ ="SEMUA STATUS PESERTA";
        } else {
            $statuspeserta_ = $_REQUEST['statpeserta'];
        }

        if ($_REQUEST['id_as']) {
            $sembilan = 'AND fu_ajk_dn.id_as = "'.$_REQUEST['id_as'].'"';
        }
        if ($_REQUEST['tglakad3']) {
            $sepuluh = 'AND fu_ajk_peserta.kredit_tgl BETWEEN "'.$_REQUEST['tglakad3'].'" AND "'.$_REQUEST['tglakad4'].'"';
        }

        if ($_REQUEST['tgltrans1']) {
            $sebelas = 'AND fu_ajk_peserta.tgl_laporan BETWEEN "'.$_REQUEST['tgltrans1'].'" AND "'.$_REQUEST['tgltrans2'].'"';
            $tanggaltransaksi ='<tr><td bgcolor="#FFF" colspan="3">Tanggal Lapor Asuransi</td><td bgcolor="#FFF" colspan="20">: '._convertDate($_REQUEST['tgltrans1']).' s/d '._convertDate($_REQUEST['tgltrans2']).'</td></tr>';
        }

        if ($_REQUEST['id_as']=="") {
            $searchasuransi_ = "SEMUA ASURANSI";
        } else {
            $cekAsuransi = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_asuransi WHERE id="'.$_REQUEST['id_as'].'"'));
            $searchasuransi_ = $cekAsuransi['name'];
        }
        $searchbank = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$_REQUEST['id_cost'].'"'));
        $searchproduk = mysql_fetch_array($database->doQuery('SELECT id, nmproduk FROM fu_ajk_polis WHERE id="'.$_REQUEST['id_polis'].'"'));
        if ($_REQUEST['paiddata']!="") {
            $searchpaid = strtoupper($_REQUEST['paiddata']);
        } else {
            $searchpaid = "SEMUA STATUS";
        }
        if ($_REQUEST['id_polis']=="") {
            $status_produknya = "SEMUA PRODUK";
        } else {
            $status_produknya = $searchproduk['nmproduk'];
        }


        if ($_REQUEST['x']) {
            $m = ($_REQUEST['x']-1) * 25;
        } else {
            $m = 0;
        }

        echo '<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="#E2E2E2">
			<tr><td bgcolor="#FFF"colspan="22"><a href="e_report.php?er=eL_asuransinew&cat='.$_REQUEST['id_cost'].'&subcat='.$_REQUEST['id_polis'].'&idas='.$_REQUEST['id_as'].'&tgl1='.$_REQUEST['tglakad1'].'&tgl2='.$_REQUEST['tglakad2'].'&tglakad3='.$_REQUEST['tglakad3'].'&tglakad4='.$_REQUEST['tglakad4'].'&tgltrans1='.$_REQUEST['tgltrans1'].'&tgltrans2='.$_REQUEST['tgltrans2'].'&paid='.$_REQUEST['paiddata'].'&status='.$_REQUEST['statpeserta'].'&id_reg='.$_REQUEST['id_reg'].'&id_cab='.$_REQUEST['id_cab'].'"><img src="image/excel.png" width="25" border="0"><br />Excel</a></td></tr>
			<tr><td colspan="2">Nama Perusahaan</td><td colspan="14">: '.$searchbank['name'].'</td></tr>
			<tr><td colspan="2">Nama Produk</td><td colspan="14">: '.$status_produknya.'</td></tr>
			<tr><td colspan="2">Nama Asuransi</td><td colspan="14">: '.$searchasuransi_.'</td></tr>
			<!--<tr><td colspan="2">Polis Asuransi</td><td colspan="14">: '.$searchpolis_.'</td></tr>-->
			<tr><td colspan="2">Tanggal Debitnote</td><td colspan="14">: '._convertDate($_REQUEST['tglakad1']).' s/d '._convertDate($_REQUEST['tglakad2']).'</td></tr>
			<tr><td colspan="2">Tanggal Akad</td><td colspan="14">: '._convertDate($_REQUEST['tglakad3']).' s/d '._convertDate($_REQUEST['tglakad4']).'</td></tr>
			<tr><td colspan="2">Tanggal Lapor Asuransi</td><td colspan="14">: '._convertDate($_REQUEST['tgltrans1']).' s/d '._convertDate($_REQUEST['tgltrans2']).'</td></tr>
			<tr><td colspan="2">Status Pembayaran</td><td colspan="14">: '.strtoupper($searchpaid).'</td></tr>
			<tr><td colspan="2">Status Peserta</td><td colspan="14">: '.strtoupper($statuspeserta_).'</td></tr>
			<tr><td colspan="2">Regional</td><td colspan="14">: '.$status_regional.'</td></tr>
			<tr><td colspan="2">Cabang</td><td colspan="14">: '.$status_cabang.'</td></tr>
			<tr><th width="1%">No</th>
			<th>Asuransi</th>
			<th>Produk</th>
			<th>Debit Note</th>
			<th>Tanggal DN</th>
			<th>No. Reg</th>
			<th>Nama Debitur</th>
			<th>KTP</th>
			<th>Jenis Kelamin</th>
			<th>Pekerjaan</th>
			<th>Cabang</th>
			<th>Tgl Lahir</th>
			<th>Usia</th>
			<th>Plafond</th>
			<th>JK.W</th>
			<th>Mulai Asuransi</th>
			<th>Akhir Asuransi</th>
			<th>MPP</th>
			<th>Premi</th>
			<th>Rate Tunggal</th>
			<th>EM(%)</th>
			<th>Extra Premi</th>
			<th>Total Rate</th>
			<th>Total Premi</th>
			<th>Status</th>
			</tr>';

        $query = 'SELECT
			fu_ajk_costumer.`name`,
			fu_ajk_polis.nmproduk,
			fu_ajk_polis.mpptype,
			fu_ajk_polis.mppbln_min,
			fu_ajk_dn.dn_kode,
			fu_ajk_dn.dn_status,
			fu_ajk_dn.tgl_dn_paid,
			fu_ajk_dn.tgl_createdn,
			fu_ajk_dn.id_as,
			fu_ajk_dn.id_polis_as,
			fu_ajk_peserta.id_peserta,
			fu_ajk_peserta.id_dn,
			fu_ajk_peserta.id_cost,
			fu_ajk_peserta.id_polis,
			fu_ajk_peserta.nama_mitra,
			fu_ajk_peserta.spaj,
			fu_ajk_peserta.nama,
			fu_ajk_peserta.tgl_lahir,
			fu_ajk_peserta.usia,
			fu_ajk_peserta.kredit_tgl,
			fu_ajk_peserta.kredit_tenor,
			fu_ajk_peserta.kredit_akhir,
			fu_ajk_peserta.kredit_jumlah,
			fu_ajk_peserta.premi,
			fu_ajk_peserta.totalpremi,
			fu_ajk_peserta.type_data,
			fu_ajk_peserta.status_aktif,
			fu_ajk_peserta.status_peserta,
			fu_ajk_peserta.regional,
			fu_ajk_peserta.danatalangan,
			fu_ajk_peserta.cabang,
			fu_ajk_peserta.mppbln,
			DATE_FORMAT(fu_ajk_peserta.input_time, "%Y-%m-%d") AS tglinput,
			fu_ajk_asuransi.`name` AS nmAsuransi,
			fu_ajk_peserta_as.rateasuransi,
			fu_ajk_peserta_as.b_premi,
			fu_ajk_peserta_as.b_extpremi,
			fu_ajk_peserta_as.nettpremi,
			TIMESTAMPDIFF(MONTH, fu_ajk_peserta.kredit_tgl, kredit_akhir)as bulan,
			fu_ajk_peserta.gender,
			fu_ajk_peserta.no_ktp,
			fu_ajk_peserta.pekerjaan,
			fu_ajk_spak.ext_premi
			FROM fu_ajk_peserta
			INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id AND fu_ajk_peserta.id_cost = fu_ajk_dn.id_cost AND fu_ajk_peserta.id_polis = fu_ajk_dn.id_nopol
			INNER JOIN fu_ajk_costumer ON fu_ajk_peserta.id_cost = fu_ajk_costumer.id
			INNER JOIN fu_ajk_peserta_as
			ON fu_ajk_peserta_as.id_peserta = fu_ajk_peserta.id_peserta
			INNER JOIN fu_ajk_polis ON fu_ajk_peserta.id_polis = fu_ajk_polis.id
			INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
			LEFT JOIN fu_ajk_spak ON fu_ajk_spak.spak = fu_ajk_peserta.spaj and fu_ajk_spak.del is null
			WHERE fu_ajk_peserta.id_dn !="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' '.$tujuh.' '.$delapan.' '.$sembilan.' '.$sepuluh.' '.$sebelas.' AND fu_ajk_dn.del IS NULL AND fu_ajk_peserta.del IS NULL
			ORDER BY fu_ajk_peserta.id_polis ASC,
					 fu_ajk_dn.id_as ASC,
					 fu_ajk_peserta.cabang ASC,
					 fu_ajk_peserta.nama ASC';

        $met = $database->doQuery($query.' LIMIT '.$m.', 25');
        $totalRows = mysql_num_rows($database->doQuery($query));

        $_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
        while ($met_ = mysql_fetch_array($met)) {
            $cekdataret = $met_['rateasuransi'];
            $tenorpeserta = $met_['bulan'];
            if ($met_['ext_premi']==0) {
                $mametrate_ext = '';
            } else {
                $mametrate_ext = $met_['ext_premi'];
            }
            $mettotalrate = $cekdataret * (1 + $met_['ext_premi'] / 100);

            if ($met_['status_peserta']=="Death") {
                $_statuspeserta = "Meninggal";
            } else {
                $_statuspeserta = $met_['status_peserta'];
            }
            if (($no % 2) == 1) {
                $objlass = 'tbl-odd';
            } else {
                $objlass = 'tbl-even';
            }



            if ($met_['gender']=="L") {
                $jnskelamin = "Laki - Laki";
            } elseif ($met_['gender']=="P") {
                $jnskelamin = "Perempuan";
            }

            echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
				  <td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
				  <td align="center">'.$met_['nmAsuransi'].'</td>
				  <td align="center">'.$met_['nmproduk'].'</td>
				  <td align="center">'.$met_['dn_kode'].'</td>
				  <td align="center">'._convertDate($met_['tgl_createdn']).'</td>
				  <td align="center">'.$met_['id_peserta'].'</td>
				  <td>'.$met_['nama'].'</td>
				  <td>'.$met_['noidentitas'].'</td>
				  <td>'.$jnskelamin.'</td>
				  <td>'.$met_['pekerjaan'].'</td>
				  <td>'.$met_['cabang'].'</td>
				  <td align="center">'._convertDate($met_['tgl_lahir']).'</td>
				  <td align="center">'.$met_['usia'].'</td>
				  <td align="right">'.duit($met_['kredit_jumlah']).'</td>
				  <td align="center">'.$tenorpeserta.'</td>
				  <td align="center">'._convertDate($met_['kredit_tgl']).'</td>
				  <td align="center">'._convertDate($met_['kredit_akhir']).'</td>
				  <td align="center">'.$met_['mppbln'].'</td>
				  <td align="right">'.duit($met_['b_premi']).'</td>
				  <td align="center">'.$cekdataret.'</td>
				  <td align="center">'.$met_['ext_premi'].'</td>
				  <td align="right">'.duit($met_['b_extpremi']).'</td>
				  <td align="center">'.$mettotalrate.'</td>
				  <td align="right">'.duit($met_['nettpremi']).'</td>
				  <td align="center">'.$met_['status_aktif'].' '.$_statuspeserta.' </td>
				  </tr>';
            $jumUP += $met_['kredit_jumlah'];
            $jumPremi += ROUND($met_['b_premi']);
            $jumExtPremi += ROUND($met_['b_extpremi']);
            $jumNettPremi += ROUND($met_['nettpremi']);
        }


        echo '<tr class="tr1"><td colspan="11" align="center"><b>TOTAL</b></td>
							  <td align="right"><b>'.duit($jumUP).'</td>
							  <td colspan="3"></td>
							  <td align="right"><b>'.duit($jumPremi).'</td>
							  <td align="right"></td>
							  <td align="right"></td>
							  <td align="right"><b>'.duit($jumExtPremi).'</td>
							  <td align="right"></td>
							  <td align="right"><b>'.duit($jumNettPremi).'</td>
			  </tr>';
        echo '<tr><td colspan="22">';
        echo createPageNavigations($file = 'ajk_re_asuransi.php?re=dataasuransi&id_cost='.$_REQUEST['id_cost'].'&id_polis='.$_REQUEST['id_polis'].'&id_as='.$_REQUEST['id_as'].'&tglakad1='.$_REQUEST['tglakad1'].'&tglakad2='.$_REQUEST['tglakad2'].'&tglakad3='.$_REQUEST['tglakad3'].'&tglakad4='.$_REQUEST['tglakad4'].'&paiddata='.$_REQUEST['paiddata'].'&statpeserta='.$_REQUEST['statpeserta'].'', $total = $totalRows, $psDeh = 10, $anchor = '', $perPage = 25);
        echo '<b>Total Data Peserta Asuransi: <u>' . $totalRows . '</u></b></td></tr>';
        echo '</table>';
    }
}
        ;
} // switch
echo '<!--WILAYAH COMBOBOX-->
<script src="javascript/metcombo/prototype.js"></script>
<script src="javascript/metcombo/dynamicombo.js"></script>
<!--WILAYAH COMBOBOX-->
<script>
document.observe("dom:loaded",function(){
	new DynamiCombo( "id_cost" , {
		elements:{
			"id_polis":		{url:\'javascript/metcombo/data.php?req=setpoliscostumer\', value:\'id\', label:\'name\', init:\'<?php echo $_POST["id_polis"] ?>\'},
			<!--"id_as":		{url:\'javascript/metcombo/data.php?req=setpolisasuransi\', value:\'id\', label:\'name\', init:\'<?php echo $_POST["id_as"] ?>\'}, DISABLED 04112015-->
			"id_reg":		{url:\'javascript/metcombo/data.php?req=setpoliscostumerregional\', value:\'id\', label:\'name\', init:\'<?php echo $_POST["id_reg"] ?>\'},
			"id_cab":		{url:\'javascript/metcombo/data.php?req=setpoliscostumercabang\', value:\'id\', label:\'name\', init:\'<?php echo $_POST["id_cab"] ?>\'},

		},
		loadingImage:\'../loader1.gif\',
		loadingText:\'Loading...\',
		debug:0
	} )
});
</script>';
