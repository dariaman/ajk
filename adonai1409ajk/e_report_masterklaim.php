<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ----------------------------------------------------------------------------------
error_reporting(0);
require('fpdf.php');
include_once "../includes/Spreadsheet/Excel/Writer.php";
include_once "../includes/Spreadsheet/Excel/Worksheet.php";
include_once "../includes/Spreadsheet/Excel/Workbook.php";
// require_once('includes/metPHPXLS/Worksheet.php');
// require_once('includes/metPHPXLS/Workbook.php');
$futoday  = date("d m Y");
$futgl = date("Y-m-d H:i:s");
$nmBulan = explode(" ", $futoday);
if ($nmBulan=="01") {
    $_nmBulan = "Januari";
} elseif ($nmBulan[1]=="02") {
    $_nmBulan = "Februari";
} elseif ($nmBulan[1]=="03") {
    $_nmBulan = "Maret";
} elseif ($nmBulan[1]=="04") {
    $_nmBulan = "April";
} elseif ($nmBulan[1]=="05") {
    $_nmBulan = "Mei";
} elseif ($nmBulan[1]=="06") {
    $_nmBulan = "Juni";
} elseif ($nmBulan[1]=="07") {
    $_nmBulan = "Juli";
} elseif ($nmBulan[1]=="08") {
    $_nmBulan = "Agustus";
} elseif ($nmBulan[1]=="09") {
    $_nmBulan = "September";
} elseif ($nmBulan[1]=="10") {
    $_nmBulan = "Oktober";
} elseif ($nmBulan[1]=="11") {
    $_nmBulan = "November";
} else {
    $_nmBulan = "Desember";
}
$Today_ =$nmBulan[0].' '.$_nmBulan.' '.$nmBulan[2];
include "../includes/fu6106.php";
$metpath_file = "../ajk_file/_spak/";
function KonDecRomawi($angka)
{
    $hsl = "";
    if ($angka<1||$angka>3999) {
        $hsl = "Batas Angka 1 s/d 3999";
    } else {
        while ($angka>=1000) {
            $hsl .= "M";
            $angka -= 1000;
        }
        if ($angka>=500) {
            if ($angka>500) {
                if ($angka>=900) {
                    $hsl .= "CM";
                    $angka-=900;
                } else {
                    $hsl .= "D";
                    $angka-=500;
                }
            }
        }
        while ($angka>=100) {
            if ($angka>=400) {
                $hsl .= "CD";
                $angka-=400;
            } else {
                $angka-=100;
            }
        }
        if ($angka>=50) {
            if ($angka>=90) {
                $hsl .= "XC";
                $angka-=90;
            } else {
                $hsl .= "L";
                $angka-=50;
            }
        }
        while ($angka>=10) {
            if ($angka>=40) {
                $hsl .= "XL";
                $angka-=40;
            } else {
                $hsl .= "X";
                $angka-=10;
            }
        }
        if ($angka>=5) {
            if ($angka==9) {
                $hsl .= "IX";
                $angka-=9;
            } else {
                $hsl .= "V";
                $angka-=5;
            }
        }
        while ($angka>=1) {
            if ($angka==4) {
                $hsl .= "IV";
                $angka-=4;
            } else {
                $hsl .= "I";
                $angka-=1;
            }
        }
    }
    return ($hsl);
}
function bulan($bln)
{
    $bulan = $bln;
    switch ($bulan) {
    case 1: $bulan="Januari";
        break;
    case 2: $bulan="Februari";
        break;
    case 3: $bulan="Maret";
        break;
    case 4: $bulan="April";
        break;
    case 5: $bulan="Mei";
        break;
    case 6: $bulan="Juni";
        break;
    case 7: $bulan="Juli";
        break;
    case 8: $bulan="Agustus";
        break;
    case 9: $bulan="September";
        break;
    case 10: $bulan="Oktober";
        break;
    case 11: $bulan="November";
        break;
    case 12: $bulan="Desember";
        break;
}
    return $bulan;
}
function viewBulanIndo($date)
{
    $bulan=array("01"=>"Januari","02"=>"Februari","03"=>"Maret","04"=>"April","05"=>"Mei","06"=>"Juni","07"=>"Juli","08"=>"Agustus","09"=>"September","10"=>"Oktober","11"=>"November","12"=>"Desember");
    if (empty($date)) {
        return null;
    }

    $date = explode("-", $date);
    $buln=$bulan[$date[1]];
    return
        $date[2] . ' ' . $buln . ' ' . $date[0];
}


switch ($_REQUEST['er']) {
case "el_peserta_covering":
    function HeaderingExcel($filename)
    {
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$filename");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
        header("Pragma: public");
    }
    HeaderingExcel('Laporan_Peserta_Covering_Letter_'.$_REQUEST['tgldn1'].'-'.$_REQUEST['tgldn2'].'.xls');
    $workbook = new Workbook("");
    $worksheet1 =& $workbook->add_worksheet('Laporan Peserta Covering Letter');
    $format =& $workbook->add_format();
    $format->setAlign('vcenter');	$format->setAlign('center');	$format->setColor('white');	$format->setBold();	$format->setPattern();	$format->setFgColor('green');

    $fjudul =& $workbook->add_format();		$fjudul->setAlign('vcenter');	$fjudul->setAlign('center');	$fjudul->setBold();
    $fjudul1 =& $workbook->add_format();	$fjudul1->setAlign('vcenter');	$fjudul1->setAlign('right');	$fjudul1->setBold();

    $worksheet1->writeString(0, 0, "DAFTAR PESERTA ASURANSI JIWA KUMPULAN", $fjudul, 1);
    $worksheet1->setMerge(0, 0, 0, 15);

    $worksheet1->setColumn(4, 0, 5);		$worksheet1->writeString(4, 0, "NO", $format);
    $worksheet1->setColumn(4, 1, 15);	$worksheet1->writeString(4, 1, "CABANG", $format);
    $worksheet1->setColumn(4, 2, 15);	$worksheet1->writeString(4, 2, "PRODUK", $format);
    $worksheet1->setColumn(4, 3, 15);	$worksheet1->writeString(4, 3, "NOMOR DN", $format);
    $worksheet1->setColumn(4, 4, 15);	$worksheet1->writeString(4, 4, "TGL DN", $format);
    $worksheet1->setColumn(4, 5, 15);	$worksheet1->writeString(4, 5, "ID PESERTA", $format);
    $worksheet1->setColumn(4, 6, 15);	$worksheet1->writeString(4, 6, "NO PERMOHONAN", $format);
    $worksheet1->setColumn(4, 7, 15);	$worksheet1->writeString(4, 7, "NAMA DEBITUR", $format);
    $worksheet1->setColumn(4, 8, 10);	$worksheet1->writeString(4, 8, "TGL LAHIR", $format);
    $worksheet1->setColumn(4, 9, 10);	$worksheet1->writeString(4, 9, "USIA", $format);
    $worksheet1->setColumn(4, 10, 5);		$worksheet1->writeString(4, 10, "PLAFOND", $format);
    $worksheet1->setColumn(4, 11, 10);	$worksheet1->writeString(4, 11, "JK.W", $format);
    $worksheet1->setColumn(4, 12, 5);	$worksheet1->writeString(4, 12, "MULAI ASURANSI", $format);
    $worksheet1->setColumn(4, 13, 15);	$worksheet1->writeString(4, 13, "AKHIR ASURANSI", $format);
    $worksheet1->setColumn(4, 14, 15);	$worksheet1->writeString(4, 14, "AKHIR ASURANSI", $format);
    $worksheet1->setColumn(4, 15, 15);	$worksheet1->writeString(4, 15, "EM", $format);
    $worksheet1->setColumn(4, 16, 15);	$worksheet1->writeString(4, 16, "TOTAL PREMI", $format);

    if ($_REQUEST['cat']) {
        $satu = 'AND fu_ajk_peserta.id_cost = "'.$_REQUEST['cat'].'"';
    }
    if ($_REQUEST['subcat']) {
        $dua = 'AND fu_ajk_peserta.id_polis = "'.$_REQUEST['subcat'].'"';
    }
    if ($_REQUEST['tgldn1']) {
        $tiga = 'AND fu_ajk_dn.tgl_createdn BETWEEN "'.$_REQUEST['tgldn1'].'" AND "'.$_REQUEST['tgldn2'].'"';
    }
    if ($_REQUEST['paid']) {
        $empat = 'AND fu_ajk_dn.dn_status = "'.$_REQUEST['paiddata'].'"';
    }

    if ($_REQUEST['status']) {
        $status_ = explode("-", $_REQUEST['status']);
        if (!$status_[1]) {
            $lima = 'AND fu_ajk_peserta.status_aktif = "'.$status_[0].'"';
        } else {
            $lima = 'AND fu_ajk_peserta.status_aktif = "'.$status_[0].'"';
            $enam = 'AND fu_ajk_peserta.status_peserta = "'.$status_[1].'"';
        }
    }
    if ($_REQUEST['grupprod']) {
        $tujuh = 'AND fu_ajk_peserta.nama_mitra = "'.$_REQUEST['grupprod'].'"';
    }
    if ($_REQUEST['tgltrans1']) {
        $delapan = 'AND fu_ajk_dn.tgltransaksi BETWEEN "'.$_REQUEST['tgltrans1'].'" AND "'.$_REQUEST['tgltrans2'].'"';
    }

    $query = "SELECT fu_ajk_peserta.cabang,
									 fu_ajk_polis.nmproduk,
									 fu_ajk_dn.dn_kode,
									 fu_ajk_dn.tgl_createdn,
									 fu_ajk_peserta.id_peserta,
									 fu_ajk_peserta.nama,
									 fu_ajk_peserta.tgl_lahir,
									 fu_ajk_peserta.usia,
									 fu_ajk_peserta.kredit_jumlah,
									 fu_ajk_peserta.kredit_tenor,
									 fu_ajk_peserta.kredit_tgl,
									 fu_ajk_peserta.kredit_akhir,
									 fu_ajk_peserta.premi,
									 fu_ajk_peserta.ext_premi,
									 fu_ajk_peserta.totalpremi,
									 fu_ajk_peserta.nopermohonan
						FROM fu_ajk_peserta
								 inner join fu_ajk_dn
								 on fu_ajk_dn.id = fu_ajk_peserta.id_dn
								 inner join fu_ajk_polis
								 on fu_ajk_polis.id = fu_ajk_peserta.id_polis
								 inner join fu_ajk_cabang
								 on fu_ajk_cabang.name = fu_ajk_peserta.cabang and
								 		fu_ajk_cabang.id_cost = fu_ajk_peserta.id_cost
						WHERE fu_ajk_peserta.del is null AND
									fu_ajk_dn.del is null and
									fu_ajk_polis.del is null and
									fu_ajk_cabang.del is null ".$satu." ".$dua." ".$tiga." ".$empat." ".$lima." ".$enam." ".$tujuh." ".$delapan."
						ORDER BY fu_ajk_peserta.cabang";

    $qpeserta = mysql_query($query);
    $baris = 5;
    //$worksheet1->writeString(5, 0, $satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' '.$tujuh.' '.$delapan);
    while ($qpeserta_ = mysql_fetch_array($qpeserta)) {
        $worksheet1->writeString($baris, 0, ++$no);
        $worksheet1->writeString($baris, 1, $qpeserta_['cabang']);
        $worksheet1->writeString($baris, 2, $qpeserta_['nmproduk']);
        $worksheet1->writeString($baris, 3, $qpeserta_['dn_kode']);
        $worksheet1->writeString($baris, 4, $qpeserta_['tgl_createdn']);
        $worksheet1->writeString($baris, 5, $qpeserta_['id_peserta']);
        $worksheet1->writeString($baris, 6, $qpeserta_['nopermohonan']);
        $worksheet1->writeString($baris, 7, $qpeserta_['nama']);
        $worksheet1->writeString($baris, 8, $qpeserta_['tgl_lahir']);
        $worksheet1->writeNumber($baris, 9, $qpeserta_['usia']);
        $worksheet1->writeNumber($baris, 10, $qpeserta_['kredit_jumlah']);
        $worksheet1->writeNumber($baris, 11, $qpeserta_['kredit_tenor']);
        $worksheet1->writeString($baris, 12, $qpeserta_['kredit_tgl']);
        $worksheet1->writeString($baris, 13, $qpeserta_['kredit_akhir']);
        $worksheet1->writeNumber($baris, 14, $qpeserta_['premi']);
        $worksheet1->writeNumber($baris, 15, $qpeserta_['ext_premi']);
        $worksheet1->writeNumber($baris, 16, $qpeserta_['totalpremi'], $fjudul1);
        $baris++;
    }

    $workbook->close();
break;

case "eL_peserta":
function HeaderingExcel($filename)
{
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$filename");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
    header("Pragma: public");
}
$met_c = mysql_fetch_array(mysql_query('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$_REQUEST['cat'].'"'));

$stringcost =  str_replace(" ", "_", $met_c['name']);
if ($_REQUEST['dncreate']==$_REQUEST['dncreate1']) {
    HeaderingExcel('Laporan_'.$stringcost.'_'.$_REQUEST['tgldn1'].'-'.$_REQUEST['tgldn2'].'.xls');
} else {
    HeaderingExcel('Laporan_'.$stringcost.'_'.$_REQUEST['tgldn1'].'-'.$_REQUEST['tgldn2'].'.xls');
}

$workbook = new Workbook("");
$worksheet1 =& $workbook->add_worksheet('Laporan Peserta');

$format =& $workbook->add_format();
$format->setAlign('vcenter');	$format->setAlign('center');	$format->setColor('white');	$format->setBold();	$format->setPattern();	$format->setFgColor('green');

$fjudul =& $workbook->add_format();		$fjudul->setAlign('vcenter');	$fjudul->setAlign('center');	$fjudul->setBold();
$fjudul1 =& $workbook->add_format();	$fjudul1->setAlign('vcenter');	$fjudul1->setAlign('right');	$fjudul1->setBold();

/*
if ($_REQUEST['cat'])		{	$satu = 'AND fu_ajk_peserta.id_cost = "'.$_REQUEST['cat'].'"';	}
if ($_REQUEST['grupprod'])		{	$sembilan = 'AND fu_ajk_polis.grupproduk = "'.$_REQUEST['grupprod'].'"';	}
if ($_REQUEST['subcat'])	{	$duaa = 'AND fu_ajk_peserta.id_polis = "'.$_REQUEST['subcat'].'"';	}
if ($_REQUEST['tgldn1'])		{	$tigaa = 'AND fu_ajk_dn.tgl_createdn BETWEEN "'.$_REQUEST['tgldn1'].'" AND "'.$_REQUEST['tgldn2'].'"';
    $tanggaldebitnote ='<tr><td colspan="2">Tanggal Debit Note</td><td colspan="14">: '._convertDate($_REQUEST['tgldn1']).' s/d '._convertDate($_REQUEST['tgldn2']).'</td></tr>';
    $tgl_data = "PERIODE TANGGAL INPUT "._convertDate($_REQUEST['tgldn1'])." S/D "._convertDate($_REQUEST['tgldn2'])."";
}
if ($_REQUEST['tgl1'])		{	$tiga = 'AND IF(fu_ajk_peserta.tgl_laporan is NULL, fu_ajk_peserta.kredit_tgl BETWEEN "'.$_REQUEST['tgl1'].'" AND "'.$_REQUEST['tgl2'].'", fu_ajk_peserta.tgl_laporan BETWEEN "'.$_REQUEST['tgl1'].'" AND "'.$_REQUEST['tgl2'].'")';
    $tanggalakadasuransi = '<tr><td colspan="2">Tanggal Akad</td><td colspan="14">: '._convertDate($_REQUEST['tglakad1']).' s/d '._convertDate($_REQUEST['tglakad2']).'</td></tr>';
    $tgl_data = "PERIODE TANGGAL AKAD "._convertDate($_REQUEST['tgl1'])." S/D "._convertDate($_REQUEST['tgl2'])."";
}
if ($_REQUEST['paid'])		{	$empt = 'AND fu_ajk_peserta.status_bayar = "'.$_REQUEST['paid'].'"';	}
if ($_REQUEST['status'])	{
    //$lima = 'AND fu_ajk_peserta.status_aktif = "'.$_REQUEST['status'].'"';

    $status_ = explode("-", $_REQUEST['status']);
    if (!$status_[1]) {
        $lima = 'AND fu_ajk_peserta.status_aktif = "'.$status_[0].'"';
    }else{
        $lima = 'AND fu_ajk_peserta.status_aktif = "'.$status_[0].'"';
        $delapan = 'AND fu_ajk_peserta.status_peserta = "'.$status_[1].'"';
    }

}
if ($_REQUEST['id_reg'])	{	$met_reg = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['id_reg'].'"'));
    $enam = 'AND fu_ajk_peserta.regional = "'.$met_reg['name'].'"';
}
if ($_REQUEST['id_cab'])	{ 	$met_cab = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_cabang WHERE id="'.$_REQUEST['id_cab'].'"'));
    $tujuh = 'AND fu_ajk_peserta.cabang = "'.$met_cab['name'].'"';
}
*/
if ($_REQUEST['cat']) {
    $satu = 'AND fu_ajk_peserta.id_cost = "'.$_REQUEST['cat'].'"';
}
if ($_REQUEST['subcat']) {
    $dua = 'AND fu_ajk_peserta.id_polis = "'.$_REQUEST['subcat'].'"';
}
    //if ($_REQUEST['grupprod'])		{	$sembilan = 'AND fu_ajk_polis.grupproduk = "'.$_REQUEST['grupprod'].'"';	}
if ($_REQUEST['tgldn1']) {
    $tiga = 'AND fu_ajk_dn.tgl_createdn BETWEEN "'.$_REQUEST['tgldn1'].'" AND "'.$_REQUEST['tgldn2'].'"';
    $tgl_data = "PERIODE TANGGAL DEBITNOTE "._convertDate($_REQUEST['tgldn1'])." S/D "._convertDate($_REQUEST['tgldn2'])."";
}
    //if ($_REQUEST['tglakad1'])		{	$tiga = 'AND IF(fu_ajk_peserta.tgl_laporan is NULL, fu_ajk_peserta.kredit_tgl BETWEEN "'.$_REQUEST['tglakad1'].'" AND "'.$_REQUEST['tglakad2'].'", fu_ajk_peserta.tgl_laporan BETWEEN "'.$_REQUEST['tglakad1'].'" AND "'.$_REQUEST['tglakad2'].'")';
    //$tanggalakadasuransi = '<tr><td colspan="2">Tanggal Akad</td><td colspan="14">: '._convertDate($_REQUEST['tglakad1']).' s/d '._convertDate($_REQUEST['tglakad2']).'</td></tr>';
    //}
//if ($_REQUEST['paid'])			{	$empat = 'AND fu_ajk_peserta.status_bayar = "'.$_REQUEST['paid'].'"';	}
if ($_REQUEST['paid']) {
    if ($_REQUEST['paid'] == 'paid') {
        $empat = 'AND fu_ajk_peserta.status_bayar = "1"';
    } elseif ($_REQUEST['paid']=='unpaid') {
        $empat = 'AND fu_ajk_peserta.status_bayar = "0"';
    } else {
        $empat ='';
    }
}
if ($_REQUEST['id_reg']) {
    $met_reg = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['id_reg'].'"'));
    $lima = 'AND fu_ajk_peserta.regional = "'.$met_reg['name'].'"';
}
if ($_REQUEST['id_cab']) {
    $met_cab = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_cabang WHERE id="'.$_REQUEST['id_cab'].'"'));
    $enam = 'AND fu_ajk_peserta.cabang = "'.$met_cab['name'].'"';
}

if ($_REQUEST['status']) {
    $status_ = explode("-", $_REQUEST['status']);
    if (!$status_[1]) {
        if ($_REQUEST['status']=="Produksi") {
            $tujuh = 'AND fu_ajk_peserta.status_aktif IN ("Inforce", "Lapse","Pending") AND (fu_ajk_peserta.status_peserta NOT IN ("Batal", "Req_Batal") OR fu_ajk_peserta.status_peserta IS NULL )';
        } else {
            $tujuh = 'AND fu_ajk_peserta.status_aktif = "'.$status_[0].'"';
        }
        //		$tujuh = 'AND fu_ajk_peserta.status_aktif = "'.$status_[0].'"';
    } else {
        $tujuh = 'AND fu_ajk_peserta.status_aktif = "'.$status_[0].'"';
        $delapan = 'AND fu_ajk_peserta.status_peserta = "'.$status_[1].'"';
    }
}

if ($_REQUEST['grupprod']) {
    $sembilan = 'AND fu_ajk_peserta.nama_mitra = "'.$_REQUEST['grupprod'].'"';
}

//if ($_REQUEST['tgltrans1'])		{	$sepuluh = 'AND fu_ajk_peserta.input_time >= "'.$_REQUEST['tgltrans1'].'" AND  fu_ajk_peserta.input_time <= "'.$_REQUEST['tgltrans2'].'"';	}
if ($_REQUEST['tgltrans1']) {
    $sepuluh = 'AND fu_ajk_dn.tgltransaksi BETWEEN "'.$_REQUEST['tgltrans1'].'" AND "'.$_REQUEST['tgltrans2'].'"';
}

$worksheet1->setMerge(0, 0, 0, 19);	$worksheet1->writeString(0, 0, "DAFTAR PESERTA ASURANSI JIWA KUMPULAN REGULER", $fjudul, 1);
$worksheet1->setMerge(1, 0, 1, 19);	$worksheet1->writeString(1, 0, $tgl_data, $fjudul);
$worksheet1->setMerge(2, 0, 2, 19);	$worksheet1->writeString(2, 0, strtoupper($met_c['name']), $fjudul);

$worksheet1->setRow(4, 16);
$worksheet1->setColumn(4, 0, 5);	$worksheet1->writeString(4, 0, "NO", $format);
$worksheet1->setColumn(4, 1, 15);	$worksheet1->writeString(4, 1, "MITRA", $format);
$worksheet1->setColumn(4, 2, 15);	$worksheet1->writeString(4, 2, "SPAJ", $format);
$worksheet1->setColumn(4, 3, 15);	$worksheet1->writeString(4, 3, "PRODUK", $format);
$worksheet1->setColumn(4, 4, 15);	$worksheet1->writeString(4, 4, "NOMOR DN", $format);
$worksheet1->setColumn(4, 5, 15);	$worksheet1->writeString(4, 5, "TGL DN", $format);
$worksheet1->setColumn(4, 6, 15);	$worksheet1->writeString(4, 6, "ID PESERTA", $format);
$worksheet1->setColumn(4, 7, 15);	$worksheet1->writeString(4, 7, "NO PINJAMAN", $format);
$worksheet1->setColumn(4, 8, 15);	$worksheet1->writeString(4, 8, "NAMA DEBITUR", $format);
$worksheet1->setColumn(4, 9, 10);	$worksheet1->writeString(4, 9, "CABANG", $format);
$worksheet1->setColumn(4, 10, 10);	$worksheet1->writeString(4, 10, "TGL LAHIR", $format);
$worksheet1->setColumn(4, 11, 5);	$worksheet1->writeString(4, 11, "USIA", $format);
$worksheet1->setColumn(4, 12, 10);	$worksheet1->writeString(4, 12, "PLAFOND", $format);
$worksheet1->setColumn(4, 13, 5);	$worksheet1->writeString(4, 13, "JK.W", $format);
$worksheet1->setColumn(4, 14, 15);	$worksheet1->writeString(4, 14, "MULAI ASURANSI", $format);
$worksheet1->setColumn(4, 15, 15);	$worksheet1->writeString(4, 15, "AKHIR ASURANSI", $format);
$worksheet1->setColumn(4, 16, 10);	$worksheet1->writeString(4, 16, "RATE TUNGGAL", $format);
$worksheet1->setColumn(4, 17, 5);	$worksheet1->writeString(4, 17, "EM (%)", $format);
$worksheet1->setColumn(4, 18, 15);	$worksheet1->writeString(4, 18, "TOTAL RATE", $format);
$worksheet1->setColumn(4, 19, 15);	$worksheet1->writeString(4, 19, "TOTAL PREMI", $format);
$worksheet1->setColumn(4, 20, 15);	$worksheet1->writeString(4, 20, "ASURANSI", $format);
$worksheet1->setColumn(4, 21, 15);	$worksheet1->writeString(4, 21, "MPP (BULAN)", $format);
$worksheet1->setColumn(4, 22, 10);	$worksheet1->writeString(4, 22, "STATUS", $format);
$worksheet1->setColumn(4, 23, 10);	$worksheet1->writeString(4, 23, "INPUT SPK", $format);
$worksheet1->setColumn(4, 24, 10);	$worksheet1->writeString(4, 24, "TYPE INPUT", $format);


$baris = 5;
/*
$er_dataPeserta = mysql_query('SELECT fu_ajk_peserta.id_dn,
                                      fu_ajk_peserta.id_cost,
                                      fu_ajk_peserta.id_polis,
                                      fu_ajk_peserta.id_klaim,
                                      fu_ajk_peserta.id_peserta,
                                      fu_ajk_peserta.spaj,
                                      fu_ajk_peserta.type_data,
                                      fu_ajk_peserta.nama,
                                      fu_ajk_peserta.gender,
                                      fu_ajk_peserta.tgl_lahir,
                                      fu_ajk_peserta.usia,
                                      fu_ajk_peserta.kredit_tgl,
                                      fu_ajk_peserta.kredit_jumlah,
                                      fu_ajk_peserta.kredit_tenor,
                                      fu_ajk_peserta.kredit_akhir,
                                      IF(fu_ajk_peserta.status_bayar="0" AND fu_ajk_peserta.status_peserta ="Batal", 0, fu_ajk_peserta.premi) AS premi,
                                      fu_ajk_peserta.disc_premi,
                                      fu_ajk_peserta.bunga,
                                      fu_ajk_peserta.biaya_adm,
                                      fu_ajk_peserta.ext_premi,
                                      IF(fu_ajk_peserta.status_bayar="0" AND fu_ajk_peserta.status_peserta ="Batal", 0, fu_ajk_peserta.totalpremi) AS totalpremi,
                                      fu_ajk_peserta.rmf,
                                      fu_ajk_peserta.ket,
                                      fu_ajk_peserta.status_medik,
                                      fu_ajk_peserta.status_bayar,
                                      fu_ajk_peserta.tgl_bayar,
                                      fu_ajk_peserta.status_aktif,
                                      fu_ajk_peserta.status_peserta,
                                      fu_ajk_peserta.regional,
                                      fu_ajk_peserta.area,
                                      fu_ajk_peserta.cabang,
                                      fu_ajk_peserta.tgl_laporan,
                                      fu_ajk_peserta.del,
                                      fu_ajk_dn.tgl_createdn,
                                      fu_ajk_polis.nmproduk,
                                      fu_ajk_polis.grupproduk
                                      FROM fu_ajk_peserta
                                      INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id
                                      INNER JOIN fu_ajk_polis ON fu_ajk_peserta.id_polis = fu_ajk_polis.id
                                      WHERE fu_ajk_peserta.id_dn !="" AND fu_ajk_peserta.del is NULL '.$satu.' '.$duaa.' '.$tigaa.' '.$tiga.' '.$empt.' '.$lima.' '.$enam.' '.$tujuh.' '.$delapan.' '.$sembilan.' ORDER BY fu_ajk_peserta.id_polis ASC, fu_ajk_peserta.kredit_tgl ASC');
*/

$er_dataPeserta = mysql_query('SELECT
fu_ajk_costumer.`name`,
fu_ajk_polis.nmproduk,
fu_ajk_polis.mpptype,
fu_ajk_polis.mppbln_min,
fu_ajk_dn.dn_kode,
fu_ajk_dn.dn_status,
fu_ajk_dn.tgl_dn_paid,
fu_ajk_dn.tgl_createdn,
fu_ajk_peserta.id_peserta,
fu_ajk_peserta.id_cost,
fu_ajk_peserta.id_polis,
fu_ajk_peserta.nama_mitra,
fu_ajk_peserta.nopinjaman,
fu_ajk_peserta.nama,
fu_ajk_peserta.tgl_lahir,
fu_ajk_peserta.spaj,
fu_ajk_peserta.usia,
fu_ajk_peserta.kredit_tgl,
fu_ajk_peserta.kredit_tenor,
fu_ajk_peserta.kredit_akhir,
fu_ajk_peserta.kredit_jumlah,
fu_ajk_peserta.premi,
fu_ajk_peserta.ratebank,
fu_ajk_peserta.ext_premi,
fu_ajk_peserta.totalpremi,
fu_ajk_peserta.type_data,
fu_ajk_peserta.status_aktif,
fu_ajk_peserta.status_peserta,
fu_ajk_peserta.danatalangan,
fu_ajk_peserta.mppbln,
fu_ajk_peserta.regional,
fu_ajk_peserta.cabang,
DATE_FORMAT(fu_ajk_peserta.input_time,"%Y-%m-%d") AS tglinput,
fu_ajk_asuransi.`name` AS nmAsuransi
FROM fu_ajk_peserta
INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id AND fu_ajk_peserta.id_cost = fu_ajk_dn.id_cost AND fu_ajk_peserta.id_polis = fu_ajk_dn.id_nopol
INNER JOIN fu_ajk_costumer ON fu_ajk_peserta.id_cost = fu_ajk_costumer.id
INNER JOIN fu_ajk_polis ON fu_ajk_peserta.id_polis = fu_ajk_polis.id
INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
WHERE fu_ajk_peserta.id_dn !="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' '.$tujuh.' '.$delapan.' '.$sembilan.' '.$sepuluh.' AND
fu_ajk_dn.del IS NULL AND
fu_ajk_peserta.del IS NULL
ORDER BY fu_ajk_peserta.id_polis ASC,
		 fu_ajk_dn.id_as ASC,
		 fu_ajk_peserta.cabang ASC,
		 fu_ajk_peserta.nama ASC');
while ($mamet = mysql_fetch_array($er_dataPeserta)) {
    $cekdatadn = mysql_fetch_array(mysql_query('SELECT id, dn_kode, id_as, tgl_createdn FROM fu_ajk_dn WHERE id="'.$mamet['id_dn'].'" AND id_cost="'.$mamet['id_cost'].'"'));
    $cekdataAS = mysql_fetch_array(mysql_query('SELECT id, name FROM fu_ajk_asuransi WHERE id="'.$cekdatadn['id_as'].'"'));


    /*
    if ($mamet['type_data']=="SPK") {
        $mettenornya = $mamet['kredit_tenor'];
        $cekdataret = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id !="" AND id_cost="'.$mamet['id_cost'].'" AND id_polis="'.$mamet['id_polis'].'" AND usia="'.$mamet['usia'].'" AND tenor="'.$mettenornya.'" AND status="baru"'));		// RATE PREMI

        //$met_emnya = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_spak_form WHERE nama="'.$mamet['nama'].'" AND dob="'.$mamet['tgl_lahir'].'"'));
        $met_emnya_2 = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_spak WHERE spak="'.$mamet['spaj'].'" AND (status="Realisasi" OR status="Aktif")'));

    }else{
        $cekdataret = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id !="" AND id_cost="'.$mamet['id_cost'].'" AND id_polis="'.$mamet['id_polis'].'" AND tenor="'.$mamet['kredit_tenor'].'" AND status="baru"'));
        $met_emnya_2 = '';
    }
    */

    $mettenornya = $mamet['kredit_tenor'];
    if ($mamet['type_data']=="SPK") {
        if ($mamet['mpptype']=="Y") {
            if ($mamet['mppbln'] < $mamet['mppbln_min']) {
                $cekdataret = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id !="" AND id_cost="'.$mamet['id_cost'].'" AND id_polis="'.$mamet['id_polis'].'" AND usia="'.$mamet['usia'].'" AND tenor="'.$mettenornya.'" AND status="lama"'));		// RATE PREMI
            } else {
                //$cekdataret = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id !="" AND id_cost="'.$mamet['id_cost'].'" AND id_polis="'.$mamet['id_polis'].'" AND tenor="'.$mettenornya.'" AND status="baru"'));		// RATE PREMI
            //$cekdataret = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id !="" AND id_cost="'.$mamet['id_cost'].'" AND id_polis="'.$mamet['id_polis'].'" AND usia="'.$mamet['usia'].'" AND tenor="'.$mettenornya.'" AND status="baru"'));		// RATE PREMI
            $cekdataret = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$mamet['id_cost'].'" AND id_polis="'.$mamet['id_polis'].'" AND tenor="'.$mamet['kredit_tenor'] * 12 .'" AND '.$mamet['mppbln'].' BETWEEN mpp_s AND mpp_e AND status="baru"'));	//VALIDASI CEK TENOR DENGAN SETTING RATE POLIS
            }
        } else {
            $cekdataret = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id !="" AND id_cost="'.$mamet['id_cost'].'" AND id_polis="'.$mamet['id_polis'].'" AND usia="'.$mamet['usia'].'" AND tenor="'.$mettenornya.'" AND status="baru"'));		// RATE PREMI
        }
        $met_emnya_2 = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_spak WHERE spak="'.$mamet['spaj'].'"  AND (status="Realisasi" OR status="Aktif") AND del IS NULL'));
        $met_emnya_2 = $met_emnya_2['ext_premi'];
    } else {
        //$cekdataret = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id !="" AND id_cost="'.$mamet['id_cost'].'" AND id_polis="'.$mamet['id_polis'].'" AND tenor="'.$mamet['kredit_tenor'].'" AND status="baru"'));
        if ($mamet['mpptype']=="Y") {
            if ($mamet['mppbln'] < $mamet['mppbln_min']) {
                $cekdataret = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$mamet['id_cost'].'" AND id_polis="'.$mamet['id_polis'].'" AND tenor="'.$mamet['kredit_tenor'].'" AND status="lama"'));		// RATE PREMI MENUNGGU PERSETUJUAN DARI BUKOPIN
            } else {
                $cekdataret = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$mamet['id_cost'].'" AND id_polis="'.$mamet['id_polis'].'" AND tenor="'.$mamet['kredit_tenor'].'" AND '.$mamet['mppbln'].' BETWEEN mpp_s AND mpp_e AND status="baru"'));	//VALIDASI CEK TENOR DENGAN SETTING RATE POLIS
            }
        } else {
            $cekdataret = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$mamet['id_cost'].'" AND id_polis="'.$mamet['id_polis'].'" AND tenor="'.$mamet['kredit_tenor'].'" AND status="baru"'));		// RATE PREMI
        }
        $met_emnya_2 = $mamet['ext_premi'] / $mamet['premi']* 100;
    }
    $metproduknya = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_polis WHERE id="'.$mamet['id_polis'].'"'));

    if ($met_['ext_premi']==0) {
        $mametrate_ext = '';
    } else {
        $mametrate_ext = $met_['ext_premi'];
    }
    //$mettotalrate = $cekdataret['rate'] * (1 + $met_emnya_2 / 100);
    $mettotalrate = $mamet['ratebank'] * (1 + $met_emnya_2 / 100);


    /*if ($mamet['type_data']=="SPK") {
        if ($mamet['mpptype']=="Y") {
            $dana_talangan = mysql_fetch_array(mysql_query("SELECT IFNULL(CASE WHEN (S.id_polis in (select id from fu_ajk_polis where mpptype = 'Y')) AND
                                                                                        F.idspk = (SELECT idspk FROM fu_ajk_spak_form WHERE nopermohonan = F.nopermohonan and (select status
                                                                                                                                    from fu_ajk_spak
                                                                                                                                    where fu_ajk_spak.id = fu_ajk_spak_form.idspk) not in ('kadaluarsa','batal') ORDER BY idspk DESC LIMIT 1)
                                                                                  THEN 'mpp' END,'')AS datampp
                                                                    FROM fu_ajk_spak_form AS F, fu_ajk_spak AS S,fu_ajk_polis AS P
                                                                    WHERE S.spak='".$mamet['spaj']."' AND F.idspk=S.id
                                                                    AND P.id = S.id_polis"));

            if($dana_talangan['datampp']=="mpp"){
                $tenorpeserta = $mamet['kredit_tenor'];
            }else{
                $tenorpeserta = $mamet['kredit_tenor'] * 12;
            }

        }else{
            $tenorpeserta = $mamet['kredit_tenor']*12;
        }
    }else{
        $tenorpeserta = $mamet['kredit_tenor'];
    }
    */

    if ($mamet['type_data']=="SPK") {
        if ($mamet['danatalangan'] != "") {
            $tenorpeserta = $mamet['kredit_tenor'];
        } else {
            $tenorpeserta = $mamet['kredit_tenor']*12;
        }
    } else {
        $tenorpeserta = $mamet['kredit_tenor'];
    }



    if ($mamet['status_peserta']=="Death") {
        $_statuspeserta = "Meninggal";
    } else {
        $_statuspeserta = $mamet['status_peserta'];
    }

    //CEK DATA SPK

    $metPesertaSPK = mysql_fetch_array(mysql_query('SELECT id, id_cost, id_polis, spak, input_by FROM fu_ajk_spak WHERE id_cost="'.$mamet['id_cost'].'" AND id_polis="'.$mamet['id_polis'].'" AND spak="'.$mamet['spaj'].'"'));
    if (is_numeric($metPesertaSPK['input_by'])) {
        $metUserSPK = mysql_fetch_array(mysql_query('SELECT * FROM user_mobile WHERE id="'.$metPesertaSPK['input_by'].'" '));
        $metUserSPK_ = $metUserSPK['namalengkap'];
    } else {
        $metUserSPK_ = $metPesertaSPK['input_by'];
    }

    $metgproduk = mysql_fetch_array(mysql_query('SELECT id, nmproduk FROM fu_ajk_grupproduk WHERE id_cost="'.$mamet['id_cost'].'" AND id="'.$mamet['nama_mitra'].'"'));
    if ($mamet['nama_mitra']=="") {
        $groupProduk = "BUKOPIN";
    } else {
        $groupProduk = $metgproduk['nmproduk'];
    }
    if (substr($mamet['spaj'], 0, 1)=="M" or substr($mamet['spaj'], 0, 1)=="MP") {
        $typeinput = "Tablet";
    } else {
        $typeinput = "Manual";
    }

    $worksheet1->writeString($baris, 0, ++$no);
    $worksheet1->writeString($baris, 1, $groupProduk);
    $worksheet1->writeString($baris, 2, $mamet['spaj']);
    $worksheet1->writeString($baris, 3, $mamet['nmproduk']);
    $worksheet1->writeString($baris, 4, $mamet['dn_kode']);
    $worksheet1->writeString($baris, 5, _convertDate($mamet['tgl_createdn']));
    $worksheet1->writeString($baris, 6, $mamet['id_peserta']);
    $worksheet1->writeString($baris, 7, $mamet['nopinjaman']);
    $worksheet1->writeString($baris, 8, $mamet['nama']);
    $worksheet1->writeString($baris, 9, $mamet['cabang']);
    $worksheet1->writeString($baris, 10, _convertDate($mamet['tgl_lahir']));
    $worksheet1->writeNumber($baris, 11, $mamet['usia']);
    $worksheet1->writeNumber($baris, 12, $mamet['kredit_jumlah'], $fjudul1);
    $worksheet1->writeNumber($baris, 13, $tenorpeserta);
    $worksheet1->writeString($baris, 14, _convertDate($mamet['kredit_tgl']));
    $worksheet1->writeString($baris, 15, _convertDate($mamet['kredit_akhir']));
    $worksheet1->writeString($baris, 16, $mamet['ratebank']);
    $worksheet1->writeNumber($baris, 17, $met_emnya_2);
    $worksheet1->writeString($baris, 18, $mettotalrate);
    $worksheet1->writeNumber($baris, 19, $mamet['totalpremi'], $fjudul1);
    $worksheet1->writeString($baris, 20, $mamet['nmAsuransi']);
    $worksheet1->writeString($baris, 21, $mamet['mppbln']);
    $worksheet1->writeString($baris, 22, $mamet['status_aktif'].' '.$_statuspeserta.'');
    $worksheet1->writeString($baris, 23, $metUserSPK_.'');
    $worksheet1->writeString($baris, 24, $typeinput);
    $baris++;

    $tPlafond += $mamet['kredit_jumlah'];
    $tTotalPremi += $mamet['totalpremi'];
}
$worksheet1->setMerge($baris, 0, $baris, 10);		$worksheet1->writeString($baris, 0, "TOTAL", $fjudul);
$worksheet1->writeString($baris, 11, $tPlafond, $fjudul1);
$worksheet1->setMerge($baris, 12, $baris, 17);	$worksheet1->writeString($baris, 9, "", $fjudul);
$worksheet1->writeString($baris, 18, $tTotalPremi, $fjudul1);

$workbook->close();
        ;
        break;

case "eL_peserta_as":
function HeaderingExcel($filename)
{
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$filename");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
    header("Pragma: public");
}
$met_c = mysql_fetch_array(mysql_query('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$_REQUEST['cat'].'"'));

$stringcost =  str_replace(" ", "_", $met_c['name']);
if ($_REQUEST['dncreate']==$_REQUEST['dncreate1']) {
    HeaderingExcel('Laporan_'.$stringcost.'_'.$_REQUEST['tgldn1'].'-'.$_REQUEST['tgldn2'].'.xls');
} else {
    HeaderingExcel('Laporan_'.$stringcost.'_'.$_REQUEST['tgldn1'].'-'.$_REQUEST['tgldn2'].'.xls');
}

$workbook = new Workbook("");
$worksheet1 =& $workbook->add_worksheet('Laporan Peserta');

$format =& $workbook->add_format();
$format->setAlign('vcenter');	$format->setAlign('center');	$format->setColor('white');	$format->setBold();	$format->setPattern();	$format->setFgColor('green');

$fjudul =& $workbook->add_format();		$fjudul->setAlign('vcenter');	$fjudul->setAlign('center');	$fjudul->setBold();
$fjudul1 =& $workbook->add_format();	$fjudul1->setAlign('vcenter');	$fjudul1->setAlign('right');	$fjudul1->setBold();

if ($_REQUEST['cat']) {
    $satu = 'AND fu_ajk_peserta.id_cost = "'.$_REQUEST['cat'].'"';
}
if ($_REQUEST['subcat']) {
    $dua = 'AND fu_ajk_peserta.id_polis = "'.$_REQUEST['subcat'].'"';
}
    //if ($_REQUEST['grupprod'])		{	$sembilan = 'AND fu_ajk_polis.grupproduk = "'.$_REQUEST['grupprod'].'"';	}
if ($_REQUEST['tgldn1']) {
    $tiga = 'AND fu_ajk_dn.tgl_createdn BETWEEN "'.$_REQUEST['tgldn1'].'" AND "'.$_REQUEST['tgldn2'].'"';
    $tgl_data = "PERIODE TANGGAL DEBITNOTE "._convertDate($_REQUEST['tgldn1'])." S/D "._convertDate($_REQUEST['tgldn2'])."";
}
    //if ($_REQUEST['tglakad1'])		{	$tiga = 'AND IF(fu_ajk_peserta.tgl_laporan is NULL, fu_ajk_peserta.kredit_tgl BETWEEN "'.$_REQUEST['tglakad1'].'" AND "'.$_REQUEST['tglakad2'].'", fu_ajk_peserta.tgl_laporan BETWEEN "'.$_REQUEST['tglakad1'].'" AND "'.$_REQUEST['tglakad2'].'")';
    //$tanggalakadasuransi = '<tr><td colspan="2">Tanggal Akad</td><td colspan="14">: '._convertDate($_REQUEST['tglakad1']).' s/d '._convertDate($_REQUEST['tglakad2']).'</td></tr>';
    //}
if ($_REQUEST['paid']) {
    $empat = 'AND fu_ajk_dn.dn_status = "'.$_REQUEST['paiddata'].'"';
}
if ($_REQUEST['id_reg']) {
    $met_reg = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['id_reg'].'"'));
    $lima = 'AND fu_ajk_peserta.regional = "'.$met_reg['name'].'"';
}
if ($_REQUEST['id_cab']) {
    $met_cab = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_cabang WHERE id="'.$_REQUEST['id_cab'].'"'));
    $enam = 'AND fu_ajk_peserta.cabang = "'.$met_cab['name'].'"';
}

if ($_REQUEST['status']) {
    $status_ = explode("-", $_REQUEST['status']);
    if (!$status_[1]) {
        $tujuh = 'AND fu_ajk_peserta.status_aktif = "'.$status_[0].'"';
    } else {
        $tujuh = 'AND fu_ajk_peserta.status_aktif = "'.$status_[0].'"';
        $delapan = 'AND fu_ajk_peserta.status_peserta = "'.$status_[1].'"';
    }
}

if ($_REQUEST['grupprod']) {
    $sembilan = 'AND fu_ajk_peserta.nama_mitra = "'.$_REQUEST['grupprod'].'"';
}

//if ($_REQUEST['tgltrans1'])		{	$sepuluh = 'AND fu_ajk_peserta.input_time >= "'.$_REQUEST['tgltrans1'].'" AND  fu_ajk_peserta.input_time <= "'.$_REQUEST['tgltrans2'].'"';	}
if ($_REQUEST['tgltrans1']) {
    $sepuluh = 'AND fu_ajk_dn.tgltransaksi BETWEEN "'.$_REQUEST['tgltrans1'].'" AND "'.$_REQUEST['tgltrans2'].'"';
}
if ($_REQUEST['idas']) {
    $sebelas = 'AND fu_ajk_asuransi.id = "'.$_REQUEST['idas'].'"';
}


$worksheet1->setMerge(0, 0, 0, 19);	$worksheet1->writeString(0, 0, "DAFTAR PESERTA ASURANSI JIWA KUMPULAN REGULER", $fjudul, 1);
$worksheet1->setMerge(1, 0, 1, 19);	$worksheet1->writeString(1, 0, $tgl_data, $fjudul);
$worksheet1->setMerge(2, 0, 2, 19);	$worksheet1->writeString(2, 0, strtoupper($met_c['name']), $fjudul);

$worksheet1->setRow(4, 16);
$worksheet1->setColumn(4, 0, 5);	$worksheet1->writeString(4, 0, "NO", $format);
$worksheet1->setColumn(4, 1, 15);	$worksheet1->writeString(4, 1, "MITRA", $format);
$worksheet1->setColumn(4, 2, 15);	$worksheet1->writeString(4, 2, "SPAJ", $format);
$worksheet1->setColumn(4, 3, 15);	$worksheet1->writeString(4, 3, "PRODUK", $format);
$worksheet1->setColumn(4, 4, 15);	$worksheet1->writeString(4, 4, "NOMOR DN", $format);
$worksheet1->setColumn(4, 5, 15);	$worksheet1->writeString(4, 5, "TGL DN", $format);
$worksheet1->setColumn(4, 6, 15);	$worksheet1->writeString(4, 6, "ID PESERTA", $format);
$worksheet1->setColumn(4, 7, 15);	$worksheet1->writeString(4, 7, "NAMA DEBITUR", $format);
$worksheet1->setColumn(4, 8, 10);	$worksheet1->writeString(4, 8, "CABANG", $format);
$worksheet1->setColumn(4, 9, 10);	$worksheet1->writeString(4, 9, "TGL LAHIR", $format);
$worksheet1->setColumn(4, 10, 5);	$worksheet1->writeString(4, 10, "USIA", $format);
$worksheet1->setColumn(4, 11, 10);	$worksheet1->writeString(4, 11, "PLAFOND", $format);
$worksheet1->setColumn(4, 12, 5);	$worksheet1->writeString(4, 12, "JK.W", $format);
$worksheet1->setColumn(4, 13, 15);	$worksheet1->writeString(4, 13, "MULAI ASURANSI", $format);
$worksheet1->setColumn(4, 14, 15);	$worksheet1->writeString(4, 14, "AKHIR ASURANSI", $format);
$worksheet1->setColumn(4, 15, 10);	$worksheet1->writeString(4, 15, "RATE TUNGGAL", $format);
$worksheet1->setColumn(4, 16, 5);	$worksheet1->writeString(4, 16, "EM (%)", $format);
$worksheet1->setColumn(4, 17, 15);	$worksheet1->writeString(4, 17, "TOTAL RATE", $format);
$worksheet1->setColumn(4, 18, 15);	$worksheet1->writeString(4, 18, "TOTAL PREMI", $format);
$worksheet1->setColumn(4, 19, 15);	$worksheet1->writeString(4, 19, "RATE ASURANSI", $format);
$worksheet1->setColumn(4, 20, 15);	$worksheet1->writeString(4, 20, "TOTAL PREMI ASURANSI", $format);
$worksheet1->setColumn(4, 21, 15);	$worksheet1->writeString(4, 21, "ASURANSI", $format);
$worksheet1->setColumn(4, 22, 15);	$worksheet1->writeString(4, 22, "MPP (BULAN)", $format);
$worksheet1->setColumn(4, 23, 10);	$worksheet1->writeString(4, 23, "STATUS", $format);
$worksheet1->setColumn(4, 24, 10);	$worksheet1->writeString(4, 24, "INPUT SPK", $format);
$worksheet1->setColumn(4, 25, 10);	$worksheet1->writeString(4, 25, "TYPE INPUT", $format);


$baris = 5;


$er_dataPeserta = mysql_query('
SELECT
fu_ajk_costumer.`name`,
fu_ajk_polis.nmproduk,
fu_ajk_polis.mpptype,
fu_ajk_polis.mppbln_min,
fu_ajk_dn.dn_kode,
fu_ajk_dn.dn_status,
fu_ajk_dn.tgl_dn_paid,
fu_ajk_dn.tgl_createdn,
fu_ajk_peserta.id_peserta,
fu_ajk_peserta.id_cost,
fu_ajk_peserta.id_polis,
fu_ajk_peserta.nama_mitra,
fu_ajk_peserta.nama,
fu_ajk_peserta.tgl_lahir,
fu_ajk_peserta.spaj,
fu_ajk_peserta.usia,
fu_ajk_peserta.kredit_tgl,
fu_ajk_peserta.kredit_tenor,
fu_ajk_peserta.kredit_akhir,
fu_ajk_peserta.kredit_jumlah,
fu_ajk_peserta.premi,
fu_ajk_peserta.ext_premi,
fu_ajk_peserta.totalpremi,
fu_ajk_peserta.type_data,
fu_ajk_peserta.status_aktif,
fu_ajk_peserta.status_peserta,
fu_ajk_peserta.mppbln,
fu_ajk_peserta.regional,
fu_ajk_peserta.cabang,
DATE_FORMAT(fu_ajk_peserta.input_time,"%Y-%m-%d") AS tglinput,
fu_ajk_asuransi.`name` AS nmAsuransi
FROM fu_ajk_peserta
INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id AND fu_ajk_peserta.id_cost = fu_ajk_dn.id_cost AND fu_ajk_peserta.id_polis = fu_ajk_dn.id_nopol
INNER JOIN fu_ajk_costumer ON fu_ajk_peserta.id_cost = fu_ajk_costumer.id
INNER JOIN fu_ajk_polis ON fu_ajk_peserta.id_polis = fu_ajk_polis.id
INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
WHERE fu_ajk_peserta.id_dn !="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' '.$tujuh.' '.$delapan.' '.$sembilan.' '.$sepuluh.' '.$sebelas.' AND
fu_ajk_dn.del IS NULL AND
fu_ajk_peserta.del IS NULL
ORDER BY fu_ajk_peserta.id_polis ASC,
		 fu_ajk_dn.id_as ASC,
		 fu_ajk_peserta.cabang ASC,
		 fu_ajk_peserta.nama ASC');
while ($mamet = mysql_fetch_array($er_dataPeserta)) {
    $cekdatadn = mysql_fetch_array(mysql_query('SELECT id, dn_kode, id_as, tgl_createdn FROM fu_ajk_dn WHERE id="'.$mamet['id_dn'].'" AND id_cost="'.$mamet['id_cost'].'"'));
    $cekdataAS = mysql_fetch_array(mysql_query('SELECT id, name FROM fu_ajk_asuransi WHERE id="'.$cekdatadn['id_as'].'"'));
    $asuransi = mysql_fetch_array(mysql_query("SELECT rateasuransi,nettpremi FROM fu_ajk_peserta_as WHERE id_peserta = '".$mamet['id_peserta']."'"));
    $mettenornya = $mamet['kredit_tenor'];
    if ($mamet['type_data']=="SPK") {
        if ($mamet['mpptype']=="Y") {
            if ($mamet['mppbln'] < $mamet['mppbln_min']) {
                $cekdataret = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id !="" AND id_cost="'.$mamet['id_cost'].'" AND id_polis="'.$mamet['id_polis'].'" AND usia="'.$mamet['usia'].'" AND tenor="'.$mettenornya.'" AND status="lama"'));		// RATE PREMI
            } else {
                $cekdataret = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$mamet['id_cost'].'" AND id_polis="'.$mamet['id_polis'].'" AND tenor="'.$mamet['kredit_tenor'] * 12 .'" AND '.$mamet['mppbln'].' BETWEEN mpp_s AND mpp_e AND status="baru"'));	//VALIDASI CEK TENOR DENGAN SETTING RATE POLIS
            }
        } else {
            $cekdataret = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id !="" AND id_cost="'.$mamet['id_cost'].'" AND id_polis="'.$mamet['id_polis'].'" AND usia="'.$mamet['usia'].'" AND tenor="'.$mettenornya.'" AND status="baru"'));		// RATE PREMI
        }
        $met_emnya_2 = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_spak WHERE spak="'.$mamet['spaj'].'"  AND (status="Realisasi" OR status="Aktif") AND del IS NULL'));
        $met_emnya_2 = $met_emnya_2['ext_premi'];
    } else {
        if ($mamet['mpptype']=="Y") {
            if ($mamet['mppbln'] < $mamet['mppbln_min']) {
                $cekdataret = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$mamet['id_cost'].'" AND id_polis="'.$mamet['id_polis'].'" AND tenor="'.$mamet['kredit_tenor'].'" AND status="lama"'));		// RATE PREMI MENUNGGU PERSETUJUAN DARI BUKOPIN
            } else {
                $cekdataret = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$mamet['id_cost'].'" AND id_polis="'.$mamet['id_polis'].'" AND tenor="'.$mamet['kredit_tenor'].'" AND '.$mamet['mppbln'].' BETWEEN mpp_s AND mpp_e AND status="baru"'));	//VALIDASI CEK TENOR DENGAN SETTING RATE POLIS
            }
        } else {
            $cekdataret = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$mamet['id_cost'].'" AND id_polis="'.$mamet['id_polis'].'" AND tenor="'.$mamet['kredit_tenor'].'" AND status="baru"'));		// RATE PREMI
        }
        $met_emnya_2 = $mamet['ext_premi'] / $mamet['premi']* 100;
    }
    $metproduknya = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_polis WHERE id="'.$mamet['id_polis'].'"'));

    if ($met_['ext_premi']==0) {
        $mametrate_ext = '';
    } else {
        $mametrate_ext = $met_['ext_premi'];
    }
    $mettotalrate = $cekdataret['rate'] * (1 + $met_emnya_2 / 100);

    if ($mamet['type_data']=="SPK") {
        if ($mamet['mpptype']=="Y") {
            $dana_talangan = mysql_fetch_array(mysql_query("SELECT IFNULL(CASE WHEN (S.id_polis in (select id from fu_ajk_polis where mpptype = 'Y')) AND
																							F.idspk = (SELECT idspk FROM fu_ajk_spak_form WHERE nopermohonan = F.nopermohonan and (select status
																																from fu_ajk_spak
																																where fu_ajk_spak.id = fu_ajk_spak_form.idspk) not in ('kadaluarsa','batal') ORDER BY idspk DESC LIMIT 1)
																						THEN 'mpp' END,'')AS datampp
																		FROM fu_ajk_spak_form AS F, fu_ajk_spak AS S,fu_ajk_polis AS P
																		WHERE S.spak='".$mamet['spaj']."' AND F.idspk=S.id
																		AND P.id = S.id_polis"));

            if ($dana_talangan['datampp']=="mpp") {
                $tenorpeserta = $mamet['kredit_tenor'];
            } else {
                $tenorpeserta = $mamet['kredit_tenor'] * 12;
            }
        } else {
            $tenorpeserta = $mamet['kredit_tenor'] * 12;
        }
    } else {
        $tenorpeserta = $mamet['kredit_tenor'];
    }
    if ($mamet['status_peserta']=="Death") {
        $_statuspeserta = "Meninggal";
    } else {
        $_statuspeserta = $mamet['status_peserta'];
    }

    //CEK DATA SPK

    $metPesertaSPK = mysql_fetch_array(mysql_query('SELECT id, id_cost, id_polis, spak, input_by FROM fu_ajk_spak WHERE id_cost="'.$mamet['id_cost'].'" AND id_polis="'.$mamet['id_polis'].'" AND spak="'.$mamet['spaj'].'"'));
    if (is_numeric($metPesertaSPK['input_by'])) {
        $metUserSPK = mysql_fetch_array(mysql_query('SELECT * FROM user_mobile WHERE id="'.$metPesertaSPK['input_by'].'" '));
        $metUserSPK_ = $metUserSPK['namalengkap'];
    } else {
        $metUserSPK_ = $metPesertaSPK['input_by'];
    }

    $metgproduk = mysql_fetch_array(mysql_query('SELECT id, nmproduk FROM fu_ajk_grupproduk WHERE id_cost="'.$mamet['id_cost'].'" AND id="'.$mamet['nama_mitra'].'"'));
    if ($mamet['nama_mitra']=="") {
        $groupProduk = "BUKOPIN";
    } else {
        $groupProduk = $metgproduk['nmproduk'];
    }
    if (substr($mamet['spaj'], 0, 1)=="M" or substr($mamet['spaj'], 0, 1)=="MP") {
        $typeinput = "Tablet";
    } else {
        $typeinput = "Manual";
    }

    $worksheet1->writeString($baris, 0, ++$no);
    $worksheet1->writeString($baris, 1, $groupProduk);
    $worksheet1->writeString($baris, 2, $mamet['spaj']);
    $worksheet1->writeString($baris, 3, $mamet['nmproduk']);
    $worksheet1->writeString($baris, 4, $mamet['dn_kode']);
    $worksheet1->writeString($baris, 5, _convertDate($mamet['tgl_createdn']));
    $worksheet1->writeString($baris, 6, $mamet['id_peserta']);
    $worksheet1->writeString($baris, 7, $mamet['nama']);
    $worksheet1->writeString($baris, 8, $mamet['cabang']);
    $worksheet1->writeString($baris, 9, _convertDate($mamet['tgl_lahir']));
    $worksheet1->writeNumber($baris, 10, $mamet['usia']);
    $worksheet1->writeNumber($baris, 11, $mamet['kredit_jumlah'], $fjudul1);
    $worksheet1->writeNumber($baris, 12, $tenorpeserta);
    $worksheet1->writeString($baris, 13, _convertDate($mamet['kredit_tgl']));
    $worksheet1->writeString($baris, 14, _convertDate($mamet['kredit_akhir']));
    $worksheet1->writeString($baris, 15, $cekdataret['rate']);
    $worksheet1->writeNumber($baris, 16, $met_emnya_2);
    $worksheet1->writeString($baris, 17, $mettotalrate);
    $worksheet1->writeNumber($baris, 18, $mamet['totalpremi'], $fjudul1);
    $worksheet1->writeNumber($baris, 19, $asuransi['rateasuransi']);
    $worksheet1->writeNumber($baris, 20, $asuransi['nettpremi']);
    $worksheet1->writeString($baris, 21, $mamet['nmAsuransi']);
    $worksheet1->writeNumber($baris, 22, $mamet['mppbln']);
    $worksheet1->writeString($baris, 23, $mamet['status_aktif'].' '.$_statuspeserta.'');
    $worksheet1->writeString($baris, 24, $metUserSPK_.'');
    $worksheet1->writeString($baris, 25, $typeinput);
    $baris++;

    $tPlafond += $mamet['kredit_jumlah'];
    $tTotalPremi += $mamet['totalpremi'];
}
$worksheet1->setMerge($baris, 0, $baris, 10);		$worksheet1->writeString($baris, 0, "TOTAL", $fjudul);
$worksheet1->writeString($baris, 11, $tPlafond, $fjudul1);
$worksheet1->setMerge($baris, 12, $baris, 17);	$worksheet1->writeString($baris, 9, "", $fjudul);
$worksheet1->writeString($baris, 18, $tTotalPremi, $fjudul1);

$workbook->close();
        ;
        break;

case "eL_peserta_as_summary":
function HeaderingExcel($filename)
{
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$filename");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
    header("Pragma: public");
}
$met_c = mysql_fetch_array(mysql_query('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$_REQUEST['cat'].'"'));

$stringcost =  str_replace(" ", "_", $met_c['name']);
if ($_REQUEST['dncreate']==$_REQUEST['dncreate1']) {
    HeaderingExcel('Laporan_'.$stringcost.'_'.$_REQUEST['tgldn1'].'-'.$_REQUEST['tgldn2'].'.xls');
} else {
    HeaderingExcel('Laporan_'.$stringcost.'_'.$_REQUEST['tgldn1'].'-'.$_REQUEST['tgldn2'].'.xls');
}

$workbook = new Workbook("");
$worksheet1 =& $workbook->add_worksheet('Laporan Peserta');

$format =& $workbook->add_format();
$format->setAlign('vcenter');	$format->setAlign('center');	$format->setColor('white');	$format->setBold();	$format->setPattern();	$format->setFgColor('green');

$fjudul =& $workbook->add_format();		$fjudul->setAlign('vcenter');	$fjudul->setAlign('center');	$fjudul->setBold();
$fjudul1 =& $workbook->add_format();	$fjudul1->setAlign('vcenter');	$fjudul1->setAlign('right');	$fjudul1->setBold();

if ($_REQUEST['cat']) {
    $satu = 'AND fu_ajk_peserta.id_cost = "'.$_REQUEST['cat'].'"';
}
if ($_REQUEST['subcat']) {
    $dua = 'AND fu_ajk_peserta.id_polis = "'.$_REQUEST['subcat'].'"';
}
    //if ($_REQUEST['grupprod'])		{	$sembilan = 'AND fu_ajk_polis.grupproduk = "'.$_REQUEST['grupprod'].'"';	}
if ($_REQUEST['tgldn1']) {
    $tiga = 'AND fu_ajk_dn.tgl_createdn BETWEEN "'.$_REQUEST['tgldn1'].'" AND "'.$_REQUEST['tgldn2'].'"';
    $tgl_data = "PERIODE TANGGAL DEBITNOTE "._convertDate($_REQUEST['tgldn1'])." S/D "._convertDate($_REQUEST['tgldn2'])."";
}
    //if ($_REQUEST['tglakad1'])		{	$tiga = 'AND IF(fu_ajk_peserta.tgl_laporan is NULL, fu_ajk_peserta.kredit_tgl BETWEEN "'.$_REQUEST['tglakad1'].'" AND "'.$_REQUEST['tglakad2'].'", fu_ajk_peserta.tgl_laporan BETWEEN "'.$_REQUEST['tglakad1'].'" AND "'.$_REQUEST['tglakad2'].'")';
    //$tanggalakadasuransi = '<tr><td colspan="2">Tanggal Akad</td><td colspan="14">: '._convertDate($_REQUEST['tglakad1']).' s/d '._convertDate($_REQUEST['tglakad2']).'</td></tr>';
    //}
if ($_REQUEST['paid']) {
    $empat = 'AND fu_ajk_dn.dn_status = "'.$_REQUEST['paiddata'].'"';
}
if ($_REQUEST['id_reg']) {
    $met_reg = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['id_reg'].'"'));
    $lima = 'AND fu_ajk_peserta.regional = "'.$met_reg['name'].'"';
}
if ($_REQUEST['id_cab']) {
    $met_cab = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_cabang WHERE id="'.$_REQUEST['id_cab'].'"'));
    $enam = 'AND fu_ajk_peserta.cabang = "'.$met_cab['name'].'"';
}

if ($_REQUEST['snama']) {
    $sebelas = 'AND nama LIKE "%' . $_REQUEST['snama'] . '%"';
}
if ($_REQUEST['idas']) {
    $duabelas = 'AND fu_ajk_asuransi.id = "'.$_REQUEST['idas'].'"';
}

if ($_REQUEST['status']) {
    $status_ = explode("-", $_REQUEST['status']);
    if (!$status_[1]) {
        $tujuh = 'AND fu_ajk_peserta.status_aktif = "'.$status_[0].'"';
    } else {
        $tujuh = 'AND fu_ajk_peserta.status_aktif = "'.$status_[0].'"';
        $delapan = 'AND fu_ajk_peserta.status_peserta = "'.$status_[1].'"';
    }
}

if ($_REQUEST['grupprod']) {
    $sembilan = 'AND fu_ajk_peserta.nama_mitra = "'.$_REQUEST['grupprod'].'"';
}

//if ($_REQUEST['tgltrans1'])		{	$sepuluh = 'AND fu_ajk_peserta.input_time >= "'.$_REQUEST['tgltrans1'].'" AND  fu_ajk_peserta.input_time <= "'.$_REQUEST['tgltrans2'].'"';	}
if ($_REQUEST['tgltrans1']) {
    $sepuluh = 'AND fu_ajk_dn.tgltransaksi BETWEEN "'.$_REQUEST['tgltrans1'].'" AND "'.$_REQUEST['tgltrans2'].'"';
}

$worksheet1->setMerge(0, 0, 0, 19);	$worksheet1->writeString(0, 0, "DAFTAR PESERTA SUMMARY PRODUKSI", $fjudul, 1);
$worksheet1->setMerge(1, 0, 1, 19);	$worksheet1->writeString(1, 0, $tgl_data, $fjudul);
$worksheet1->setMerge(2, 0, 2, 19);	$worksheet1->writeString(2, 0, strtoupper($met_c['name']), $fjudul);

$worksheet1->setRow(4, 16);
$worksheet1->setColumn(4, 0, 5);	$worksheet1->writeString(4, 0, "PRODUK", $format);
$worksheet1->setColumn(4, 1, 15);	$worksheet1->writeString(4, 1, "DEBITUR", $format);
$worksheet1->setColumn(4, 2, 15);	$worksheet1->writeString(4, 2, "PLAFOND", $format);
$worksheet1->setColumn(4, 3, 15);	$worksheet1->writeString(4, 3, "PREMI BANK", $format);
$worksheet1->setColumn(4, 4, 15);	$worksheet1->writeString(4, 4, "PREMI ASURANSI", $format);

$baris = 5;


$er_dataPeserta = mysql_query('SELECT fu_ajk_polis.nmproduk,
																			 count(*)as jml_peserta,
																			 sum(fu_ajk_peserta.kredit_jumlah)as plafond,
																			 sum(fu_ajk_peserta.totalpremi)as premi_bank,
																			 sum(fu_ajk_peserta_as.nettpremi)as premi_asuransi
																FROM fu_ajk_peserta
																		 INNER JOIN fu_ajk_polis
																		 ON fu_ajk_polis.id = fu_ajk_peserta.id_polis
																		 INNER JOIN fu_ajk_peserta_as
																		 ON fu_ajk_peserta_as.id_peserta = fu_ajk_peserta.id_peserta
																		 INNER JOIN fu_ajk_dn
																		 ON fu_ajk_dn.id = fu_ajk_peserta.id_dn
																		 INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
																WHERE fu_ajk_peserta.del is NULL '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' '.$tujuh.' '.$delapan.' '.$sembilan.' '.$sepuluh.' '.$sebelas.' '.$duabelas.'
																GROUP BY fu_ajk_peserta.id_polis');
while ($mamet = mysql_fetch_array($er_dataPeserta)) {
    $worksheet1->writeString($baris, 0, $mamet['nmproduk']);
    $worksheet1->writeString($baris, 1, $mamet['jml_peserta']);
    $worksheet1->writeString($baris, 2, duit($mamet['plafond']));
    $worksheet1->writeString($baris, 3, duit($mamet['premi_bank']));
    $worksheet1->writeString($baris, 4, duit($mamet['premi_asuransi']));

    $baris++;
}

$workbook->close();
        ;
        break;

case "eL_asuransinew":
    function HeaderingExcel($filename)
    {
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$filename");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
        header("Pragma: public");
    }
    $met_c = mysql_fetch_array(mysql_query('SELECT id, name FROM fu_ajk_asuransi WHERE id="'.$_REQUEST['idas'].'"'));
    $met_r = mysql_fetch_array(mysql_query('SELECT id, nmproduk FROM fu_ajk_polis WHERE id="'.$_REQUEST['subcat'].'"'));

    if ($_REQUEST['dncreate']==$_REQUEST['dncreate1']) {
        HeaderingExcel('Laporan_Asuransi_Periode_'._convertDate($_REQUEST['tgl1']).'_s/d-'._convertDate($_REQUEST['tgl2']).'.xls');
    } else {
        HeaderingExcel('Laporan_'._convertDate($_REQUEST['tgl1']).'_s/d_'._convertDate($_REQUEST['tgl2']).'.xls');
    }

    $workbook = new Workbook("");
    $worksheet1 =& $workbook->add_worksheet('Lap. Peserta Asuransi');

    $format =& $workbook->add_format();
    $format->setAlign('vcenter');	$format->setAlign('center');	$format->setColor('white');	$format->setBold();	$format->setPattern();	$format->setFgColor('green');

    $fjudul =& $workbook->add_format();	$fjudul->setAlign('vcenter');	$fjudul->setAlign('center');	$fjudul->setBold();

    $worksheet1->setMerge(0, 0, 0, 21);	$worksheet1->writeString(0, 0, "DAFTAR PESERTA ASURANSI JIWA KUMPULAN ".$met_r['nmproduk']."", $fjudul, 1);
    $worksheet1->setMerge(1, 0, 1, 21);	$worksheet1->writeString(1, 0, "PERIODE "._convertDate($_REQUEST['tgl1'])." S/D "._convertDate($_REQUEST['tgl2'])."", $fjudul);
    $worksheet1->setMerge(2, 0, 2, 21);	$worksheet1->writeString(2, 0, strtoupper($met_c['name']), $fjudul);

    $worksheet1->setRow(4, 15);
    $worksheet1->setColumn(4, 0, 5);	$worksheet1->writeString(4, 0, "NO", $format);
    $worksheet1->setColumn(4, 1, 15);	$worksheet1->writeString(4, 1, "ASURANSI", $format);
    $worksheet1->setColumn(4, 2, 15);	$worksheet1->writeString(4, 2, "PRODUK", $format);
    $worksheet1->setColumn(4, 3, 15);	$worksheet1->writeString(4, 3, "NOMOR DN", $format);
    $worksheet1->setColumn(4, 4, 15);	$worksheet1->writeString(4, 4, "TANGGAL DN", $format);
    $worksheet1->setColumn(4, 5, 15);	$worksheet1->writeString(4, 5, "ID.PESERTA", $format);
    $worksheet1->setColumn(4, 6, 15);	$worksheet1->writeString(4, 6, "NAMA", $format);
    $worksheet1->setColumn(4, 7, 15);	$worksheet1->writeString(4, 7, "KTP", $format);
    $worksheet1->setColumn(4, 8, 15);	$worksheet1->writeString(4, 8, "JENIS KELAMIN", $format);
    $worksheet1->setColumn(4, 9, 15);	$worksheet1->writeString(4, 9, "PEKERJAAN", $format);
    $worksheet1->setColumn(4, 10, 15);	$worksheet1->writeString(4, 10, "CABANG", $format);
    $worksheet1->setColumn(4, 11, 15);	$worksheet1->writeString(4, 11, "TGL LAHIR", $format);
    $worksheet1->setColumn(4, 12, 15);	$worksheet1->writeString(4, 12, "USIA", $format);
    $worksheet1->setColumn(4, 13, 15);	$worksheet1->writeString(4, 13, "PLAFOND", $format);
    $worksheet1->setColumn(4, 14, 15);	$worksheet1->writeString(4, 14, "MULAI ASURANSI", $format);
    $worksheet1->setColumn(4, 15, 10);	$worksheet1->writeString(4, 15, "TENOR", $format);
    $worksheet1->setColumn(4, 16, 15);	$worksheet1->writeString(4, 16, "AKHIR ASURANSI", $format);
    $worksheet1->setColumn(4, 17, 15);	$worksheet1->writeString(4, 17, "RATE", $format);
    $worksheet1->setColumn(4, 18, 15);	$worksheet1->writeString(4, 18, "PREMI", $format);
    $worksheet1->setColumn(4, 19, 10);	$worksheet1->writeString(4, 19, "EM(%)", $format);
    $worksheet1->setColumn(4, 20, 15);	$worksheet1->writeString(4, 20, "EXT.PREMI", $format);
    $worksheet1->setColumn(4, 21, 15);	$worksheet1->writeString(4, 21, "TOTAL PREMI", $format);
    $worksheet1->setColumn(4, 22, 15);	$worksheet1->writeString(4, 22, "PEMBAYARAN", $format);
    $worksheet1->setColumn(4, 23, 15);	$worksheet1->writeString(4, 23, "MPP(bln)", $format);
    $worksheet1->setColumn(4, 24, 15);	$worksheet1->writeString(4, 24, "STATUS", $format);

    if ($_REQUEST['cat']) {
        $satu = 'AND fu_ajk_peserta.id_cost = "'.$_REQUEST['cat'].'"';
    }
    if ($_REQUEST['subcat']) {
        $dua = 'AND fu_ajk_peserta.id_polis = "'.$_REQUEST['subcat'].'"';
    }
    if ($_REQUEST['tgl1']) {
        $tiga = 'AND fu_ajk_dn.tgl_createdn BETWEEN "'.$_REQUEST['tgl1'].'" AND "'.$_REQUEST['tgl2'].'"';
        $tanggaldebitnote ='<tr><td colspan="2">Tanggal Debit Note</td><td colspan="14">: '._convertDate($_REQUEST['tgldn1']).' s/d '._convertDate($_REQUEST['tgldn2']).'</td></tr>';
    }
    if ($_REQUEST['paid']) {
        $empat = 'AND fu_ajk_dn.dn_status = "'.$_REQUEST['paid'].'"';
    }
    if ($_REQUEST['id_reg']) {
        $met_reg = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['id_reg'].'"'));
        $lima = 'AND fu_ajk_peserta.regional = "'.$met_reg['name'].'"';
    }
    if ($_REQUEST['id_cab']) {
        $met_cab = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_cabang WHERE id="'.$_REQUEST['id_cab'].'"'));
        $enam = 'AND fu_ajk_peserta.cabang = "'.$met_cab['name'].'"';
    }

    if ($_REQUEST['status']) {
        $status_ = explode("-", $_REQUEST['status']);
        if (!$status_[1]) {
            $tujuh = 'AND fu_ajk_peserta.status_aktif = "'.$status_[0].'"';
        } else {
            $tujuh = 'AND fu_ajk_peserta.status_aktif = "'.$status_[0].'"';
            $delapan = 'AND fu_ajk_peserta.status_peserta = "'.$status_[1].'"';
        }
    }
    if ($_REQUEST['idas']) {
        $sembilan = 'AND fu_ajk_dn.id_as = "'.$_REQUEST['idas'].'"';
    }
    if ($_REQUEST['tglakad3']) {
        $sepuluh = 'AND fu_ajk_peserta.kredit_tgl BETWEEN "'.$_REQUEST['tglakad3'].'" AND "'.$_REQUEST['tglakad4'].'"';
    }
    if ($_REQUEST['tgltrans1']) {
        $sebelas = 'AND fu_ajk_peserta.tgl_laporan BETWEEN "'.$_REQUEST['tgltrans1'].'" AND "'.$_REQUEST['tgltrans2'].'"';
    }

    $baris = 5;

    $er_data = mysql_query('SELECT 	fu_ajk_costumer.`name`,
																	fu_ajk_polis.nmproduk,
																	fu_ajk_polis.typeproduk,
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
																	fu_ajk_peserta.ext_premi,
																	fu_ajk_peserta.totalpremi,
																	fu_ajk_peserta.type_data,
																	fu_ajk_peserta.status_aktif,
																	fu_ajk_peserta.status_peserta,
																	fu_ajk_peserta.regional,
																	fu_ajk_peserta.cabang,
																	fu_ajk_peserta.danatalangan,
																	fu_ajk_peserta.mppbln,
																	DATE_FORMAT(fu_ajk_peserta.input_time, "%Y-%m-%d") AS tglinput,
																	fu_ajk_asuransi.`name` AS nmAsuransi,
																	fu_ajk_spak.ext_premi as extpremi_spk,
																	fu_ajk_spak_form.jns_kelamin,
																	fu_ajk_spak_form.noidentitas,
																	fu_ajk_spak_form.pekerjaan
																	FROM fu_ajk_peserta
																	INNER JOIN fu_ajk_dn
																	ON fu_ajk_peserta.id_dn = fu_ajk_dn.id AND
																		 fu_ajk_peserta.id_cost = fu_ajk_dn.id_cost AND
																		 fu_ajk_peserta.id_polis = fu_ajk_dn.id_nopol
																	INNER JOIN fu_ajk_costumer
																	ON fu_ajk_peserta.id_cost = fu_ajk_costumer.id
																	INNER JOIN fu_ajk_polis
																	ON fu_ajk_peserta.id_polis = fu_ajk_polis.id
																	INNER JOIN fu_ajk_asuransi
																	ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
																	LEFT JOIN fu_ajk_spak
																	ON fu_ajk_spak.spak = fu_ajk_peserta.spaj
																	LEFT JOIN fu_ajk_spak_form
																	ON fu_ajk_spak_form.idspk = fu_ajk_spak.id
																	WHERE fu_ajk_peserta.id_dn !="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' '.$tujuh.' '.$delapan.' '.$sembilan.' '.$sepuluh.' '.$sebelas.' AND
																	fu_ajk_dn.del IS NULL AND
																	fu_ajk_peserta.del IS NULL
																	ORDER BY fu_ajk_peserta.id_polis ASC,
																			 fu_ajk_dn.id_as ASC,
																			 fu_ajk_peserta.cabang ASC,
																			 fu_ajk_peserta.nama ASC');

    while ($mamet = mysql_fetch_array($er_data)) {
        $metPremiAsuransi = mysql_fetch_array(mysql_query('SELECT *
																											 FROM fu_ajk_peserta_as
																											 WHERE id_bank="'.$mamet['id_cost'].'" AND
																											 			 id_polis="'.$mamet['id_polis'].'" AND
																											 			 id_peserta="'.$mamet['id_peserta'].'" AND
																											 			 id_dn="'.$mamet['id_dn'].'"'));
        if ($mamet['typeproduk']=="SPK") {
            if ($mamet['danatalangan']==1) {
                $tenorpeserta = $mamet['kredit_tenor'];
            } else {
                $tenorpeserta = $mamet['kredit_tenor']*12;
            }
        } else {
            $tenorpeserta = $mamet['kredit_tenor'];
        }

        if ($mamet['jns_kelamin']=="M") {
            $jnskelamin = 'Laki - Laki';
        } elseif ($mamet['jns_kelamin']=="F") {
            $jnskelamin = 'Perempuan';
        }

        $worksheet1->writeString($baris, 0, ++$no);
        $worksheet1->writeString($baris, 1, $mamet['nmAsuransi']);
        $worksheet1->writeString($baris, 2, $mamet['nmproduk']);
        $worksheet1->writeString($baris, 3, $mamet['dn_kode']);
        $worksheet1->writeString($baris, 4, _convertDate($mamet['tgl_createdn']));
        $worksheet1->writeString($baris, 5, $mamet['id_peserta']);
        $worksheet1->writeString($baris, 6, $mamet['nama']);
        $worksheet1->writeString($baris, 7, $mamet['noidentitas']);
        $worksheet1->writeString($baris, 8, $jnskelamin);
        $worksheet1->writeString($baris, 9, $mamet['pekerjaan']);
        $worksheet1->writeString($baris, 10, $mamet['cabang']);
        $worksheet1->writeString($baris, 11, _convertDate($mamet['tgl_lahir']));
        $worksheet1->writeNumber($baris, 12, $mamet['usia']);
        $worksheet1->writeNumber($baris, 13, $mamet['kredit_jumlah']);
        $worksheet1->writeString($baris, 14, _convertDate($mamet['kredit_tgl']));
        $worksheet1->writeNumber($baris, 15, $tenorpeserta);
        $worksheet1->writeString($baris, 16, _convertDate($mamet['kredit_akhir']));
        $worksheet1->writeNumber($baris, 17, $metPremiAsuransi['rateasuransi']);
        $worksheet1->writeNumber($baris, 18, $metPremiAsuransi['b_premi']);
        $worksheet1->writeNumber($baris, 19, duit($mamet['extpremi_spk']));
        $worksheet1->writeNumber($baris, 20, $metPremiAsuransi['b_extpremi']);
        $worksheet1->writeNumber($baris, 21, $metPremiAsuransi['nettpremi']);
        $worksheet1->writeString($baris, 22, strtoupper($mamet['dn_status']));
        $worksheet1->writeNumber($baris, 23, $mamet['mppbln']);
        $worksheet1->writeString($baris, 24, strtoupper($mamet['status_aktif']).' '.strtoupper($mamet['status_peserta']));

        $baris++;
    }
    $workbook->close();;
break;

case "eL_asuransi":
function HeaderingExcel($filename)
{
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$filename");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
    header("Pragma: public");
}
    $met_c = mysql_fetch_array(mysql_query('SELECT id, name FROM fu_ajk_asuransi WHERE id="'.$_REQUEST['idas'].'"'));
    $met_r = mysql_fetch_array(mysql_query('SELECT id, nmproduk FROM fu_ajk_polis WHERE id="'.$_REQUEST['subcat'].'"'));

    //$stringcost =  str_replace(" ", "_", $met_c['name']);
if ($_REQUEST['dncreate']==$_REQUEST['dncreate1']) {
    HeaderingExcel('Laporan_Asuransi_Periode_'._convertDate($_REQUEST['tgl1']).'_s/d-'._convertDate($_REQUEST['tgl2']).'.xls');
} else {
    HeaderingExcel('Laporan_'._convertDate($_REQUEST['tgl1']).'_s/d_'._convertDate($_REQUEST['tgl2']).'.xls');
}

    $workbook = new Workbook("");
    $worksheet1 =& $workbook->add_worksheet('Lap. Peserta Asuransi');

    $format =& $workbook->add_format();
    $format->setAlign('vcenter');	$format->setAlign('center');	$format->setColor('white');	$format->setBold();	$format->setPattern();	$format->setFgColor('green');

    $fjudul =& $workbook->add_format();	$fjudul->setAlign('vcenter');	$fjudul->setAlign('center');	$fjudul->setBold();

    $worksheet1->setMerge(0, 0, 0, 21);	$worksheet1->writeString(0, 0, "DAFTAR PESERTA ASURANSI JIWA KUMPULAN ".$met_r['nmproduk']."", $fjudul, 1);
    $worksheet1->setMerge(1, 0, 1, 21);	$worksheet1->writeString(1, 0, "PERIODE "._convertDate($_REQUEST['tgl1'])." S/D "._convertDate($_REQUEST['tgl2'])."", $fjudul);
    $worksheet1->setMerge(2, 0, 2, 21);	$worksheet1->writeString(2, 0, strtoupper($met_c['name']), $fjudul);

    $worksheet1->setRow(4, 15);
    $worksheet1->setColumn(4, 0, 5);	$worksheet1->writeString(4, 0, "NO", $format);
    $worksheet1->setColumn(4, 1, 15);	$worksheet1->writeString(4, 1, "ASURANSI", $format);
    $worksheet1->setColumn(4, 2, 15);	$worksheet1->writeString(4, 2, "PRODUK", $format);
    $worksheet1->setColumn(4, 3, 15);	$worksheet1->writeString(4, 3, "NOMOR DN", $format);
    $worksheet1->setColumn(4, 4, 15);	$worksheet1->writeString(4, 4, "TANGGAL DN", $format);
    $worksheet1->setColumn(4, 5, 15);	$worksheet1->writeString(4, 5, "ID.PESERTA", $format);
    $worksheet1->setColumn(4, 6, 15);	$worksheet1->writeString(4, 6, "NAMA", $format);
    $worksheet1->setColumn(4, 7, 15);	$worksheet1->writeString(4, 7, "KTP", $format);
    $worksheet1->setColumn(4, 8, 15);	$worksheet1->writeString(4, 8, "JENIS KELAMIN", $format);
    $worksheet1->setColumn(4, 9, 15);	$worksheet1->writeString(4, 9, "PEKERJAAN", $format);
    $worksheet1->setColumn(4, 10, 15);	$worksheet1->writeString(4, 10, "CABANG", $format);
    $worksheet1->setColumn(4, 11, 15);	$worksheet1->writeString(4, 11, "TGL LAHIR", $format);
    $worksheet1->setColumn(4, 12, 15);	$worksheet1->writeString(4, 12, "USIA", $format);
    $worksheet1->setColumn(4, 13, 15);	$worksheet1->writeString(4, 13, "PLAFOND", $format);
    $worksheet1->setColumn(4, 14, 15);	$worksheet1->writeString(4, 14, "MULAI ASURANSI", $format);
    $worksheet1->setColumn(4, 15, 10);	$worksheet1->writeString(4, 15, "TENOR", $format);
    $worksheet1->setColumn(4, 16, 15);	$worksheet1->writeString(4, 16, "AKHIR ASURANSI", $format);
    $worksheet1->setColumn(4, 17, 15);	$worksheet1->writeString(4, 17, "RATE", $format);
    $worksheet1->setColumn(4, 18, 15);	$worksheet1->writeString(4, 18, "PREMI", $format);
    $worksheet1->setColumn(4, 19, 10);	$worksheet1->writeString(4, 19, "EM(%)", $format);
    $worksheet1->setColumn(4, 20, 15);	$worksheet1->writeString(4, 20, "EXT.PREMI", $format);
    $worksheet1->setColumn(4, 21, 15);	$worksheet1->writeString(4, 21, "TOTAL PREMI", $format);
    $worksheet1->setColumn(4, 22, 15);	$worksheet1->writeString(4, 22, "PEMBAYARAN", $format);
    $worksheet1->setColumn(4, 23, 15);	$worksheet1->writeString(4, 23, "MPP(bln)", $format);
    $worksheet1->setColumn(4, 24, 15);	$worksheet1->writeString(4, 24, "STATUS", $format);

/*
if ($_REQUEST['cat'])		{	$satu = 'AND fu_ajk_peserta_as.id_bank = "'.$_REQUEST['cat'].'"';	}
if ($_REQUEST['subcat'])	{	$dua = 'AND fu_ajk_peserta_as.id_polis = "'.$_REQUEST['subcat'].'"';	}
if ($_REQUEST['idas'])		{	$tiga = 'AND fu_ajk_peserta_as.id_asuransi = "'.$_REQUEST['idas'].'"';	}
if ($_REQUEST['tgl1'])		{	$empat = 'AND fu_ajk_dn.tgl_createdn BETWEEN "'.$_REQUEST['tgl1'].'" AND "'.$_REQUEST['tgl2'].'"';	}
//if ($_REQUEST['tgl1'])		{	$empt = 'AND fu_ajk_peserta.kredit_tgl BETWEEN "'.$_REQUEST['tgl1'].'" AND "'.$_REQUEST['tgl2'].'" ';	}
if ($_REQUEST['paid'])		{	$lima = 'AND fu_ajk_dn.dn_status = "'.$_REQUEST['paid'].'"';	}
if ($_REQUEST['status'])	{	$enam = 'AND fu_ajk_peserta.status_aktif = "'.$_REQUEST['status'].'"';	}
if ($_REQUEST['tglakad3'])	{	$tujuh = 'AND fu_ajk_peserta.kredit_tgl BETWEEN "'.$_REQUEST['tglakad3'].'" AND "'.$_REQUEST['tglakad4'].'"';	}
*/
if ($_REQUEST['cat']) {
    $satu = 'AND fu_ajk_peserta.id_cost = "'.$_REQUEST['cat'].'"';
}
if ($_REQUEST['subcat']) {
    $dua = 'AND fu_ajk_peserta.id_polis = "'.$_REQUEST['subcat'].'"';
}
    //if ($_REQUEST['grupprod'])		{	$sembilan = 'AND fu_ajk_polis.grupproduk = "'.$_REQUEST['grupprod'].'"';	}
if ($_REQUEST['tgl1']) {
    $tiga = 'AND fu_ajk_dn.tgl_createdn BETWEEN "'.$_REQUEST['tgl1'].'" AND "'.$_REQUEST['tgl2'].'"';
    $tanggaldebitnote ='<tr><td colspan="2">Tanggal Debit Note</td><td colspan="14">: '._convertDate($_REQUEST['tgldn1']).' s/d '._convertDate($_REQUEST['tgldn2']).'</td></tr>';
}
    //if ($_REQUEST['tglakad1'])		{	$tiga = 'AND IF(fu_ajk_peserta.tgl_laporan is NULL, fu_ajk_peserta.kredit_tgl BETWEEN "'.$_REQUEST['tglakad1'].'" AND "'.$_REQUEST['tglakad2'].'", fu_ajk_peserta.tgl_laporan BETWEEN "'.$_REQUEST['tglakad1'].'" AND "'.$_REQUEST['tglakad2'].'")';
    //$tanggalakadasuransi = '<tr><td colspan="2">Tanggal Akad</td><td colspan="14">: '._convertDate($_REQUEST['tglakad1']).' s/d '._convertDate($_REQUEST['tglakad2']).'</td></tr>';
    //}
if ($_REQUEST['paid']) {
    $empat = 'AND fu_ajk_dn.dn_status = "'.$_REQUEST['paid'].'"';
}
if ($_REQUEST['id_reg']) {
    $met_reg = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['id_reg'].'"'));
    $lima = 'AND fu_ajk_peserta.regional = "'.$met_reg['name'].'"';
}
if ($_REQUEST['id_cab']) {
    $met_cab = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_cabang WHERE id="'.$_REQUEST['id_cab'].'"'));
    $enam = 'AND fu_ajk_peserta.cabang = "'.$met_cab['name'].'"';
}

if ($_REQUEST['status']) {
    $status_ = explode("-", $_REQUEST['status']);
    if (!$status_[1]) {
        $tujuh = 'AND fu_ajk_peserta.status_aktif = "'.$status_[0].'"';
    } else {
        $tujuh = 'AND fu_ajk_peserta.status_aktif = "'.$status_[0].'"';
        $delapan = 'AND fu_ajk_peserta.status_peserta = "'.$status_[1].'"';
    }
}

if ($_REQUEST['idas']) {
    $sembilan = 'AND fu_ajk_dn.id_as = "'.$_REQUEST['idas'].'"';
}
if ($_REQUEST['tglakad3']) {
    $sepuluh = 'AND fu_ajk_peserta.kredit_tgl BETWEEN "'.$_REQUEST['tglakad3'].'" AND "'.$_REQUEST['tglakad4'].'"';
}
if ($_REQUEST['tgltrans1']) {
    $sebelas = 'AND fu_ajk_peserta.tgl_laporan BETWEEN "'.$_REQUEST['tgltrans1'].'" AND "'.$_REQUEST['tgltrans2'].'"';
}
$baris = 5;
/*
$er_data = mysql_query('SELECT fu_ajk_asuransi.`name` AS nmasuransi,
                                  fu_ajk_peserta.id_dn AS debitnote,
                                  fu_ajk_peserta_as.id_polis,
                                  fu_ajk_peserta_as.id_peserta AS nopeserta,
                                  fu_ajk_peserta.nama AS nama,
                                  fu_ajk_peserta.tgl_lahir AS dob,
                                  fu_ajk_peserta.usia AS usianya,
                                  fu_ajk_peserta.kredit_tgl AS tglmulai,
                                  fu_ajk_peserta.kredit_tenor AS tenor,
                                  fu_ajk_peserta.kredit_akhir AS tglakhir,
                                  fu_ajk_peserta.kredit_jumlah AS plafond,
                                  fu_ajk_peserta_as.b_premi AS bpremi,
                                  fu_ajk_peserta_as.b_admin AS badmin,
                                  fu_ajk_peserta_as.b_disc AS bdisc,
                                  fu_ajk_peserta_as.b_extpremi AS bextpremi,
                                  fu_ajk_peserta_as.b_ppn AS ppn,
                                  fu_ajk_peserta_as.b_pph AS pph,
                                  fu_ajk_peserta_as.nettpremi AS nettpremi,
                                  IF (fu_ajk_peserta.id_polis="1", fu_ajk_peserta.kredit_tenor * 12 ,fu_ajk_peserta.kredit_tenor) AS tenorbln,
                                  IF (fu_ajk_peserta.status_bayar="0", "Unpaid","Paid") AS statusbayar,
                                  fu_ajk_peserta.status_aktif AS statusaktif,
                                  fu_ajk_peserta.cabang AS cabang
                                  FROM fu_ajk_peserta_as
                                  INNER JOIN fu_ajk_peserta ON fu_ajk_peserta_as.id_bank = fu_ajk_peserta.id_cost AND fu_ajk_peserta_as.id_polis = fu_ajk_peserta.id_polis AND fu_ajk_peserta_as.id_dn = fu_ajk_peserta.id_dn AND fu_ajk_peserta_as.id_peserta = fu_ajk_peserta.id_peserta
                                  INNER JOIN fu_ajk_asuransi ON fu_ajk_peserta_as.id_asuransi = fu_ajk_asuransi.id
                                  WHERE fu_ajk_peserta_as.id !="" '.$satu.' '.$duaa.' '.$tiga.' '.$empt.' '.$lima.' '.$enam.'');
*/
$er_data = mysql_query('SELECT
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
fu_ajk_peserta.danatalangan,
fu_ajk_peserta.ext_premi,
fu_ajk_peserta.totalpremi,
fu_ajk_peserta.type_data,
fu_ajk_peserta.status_aktif,
fu_ajk_peserta.status_peserta,
fu_ajk_peserta.regional,
fu_ajk_peserta.cabang,
fu_ajk_peserta.mppbln,
DATE_FORMAT(fu_ajk_peserta.input_time, "%Y-%m-%d") AS tglinput,
fu_ajk_asuransi.`name` AS nmAsuransi
FROM fu_ajk_peserta
INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id AND fu_ajk_peserta.id_cost = fu_ajk_dn.id_cost AND fu_ajk_peserta.id_polis = fu_ajk_dn.id_nopol
INNER JOIN fu_ajk_costumer ON fu_ajk_peserta.id_cost = fu_ajk_costumer.id
INNER JOIN fu_ajk_polis ON fu_ajk_peserta.id_polis = fu_ajk_polis.id
INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
WHERE fu_ajk_peserta.id_dn !="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' '.$tujuh.' '.$delapan.' '.$sembilan.' '.$sepuluh.' '.$sebelas.' AND
fu_ajk_dn.del IS NULL AND
fu_ajk_peserta.del IS NULL
ORDER BY fu_ajk_peserta.id_polis ASC,
		 fu_ajk_dn.id_as ASC,
		 fu_ajk_peserta.cabang ASC,
		 fu_ajk_peserta.nama ASC');

while ($mamet = mysql_fetch_array($er_data)) {
    //	$met_dn = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_dn WHERE id="'.$mamet['debitnote'].'"'));
    //	$met_polis = mysql_fetch_array(mysql_query('SELECT id, nmproduk FROM fu_ajk_polis WHERE id="'.$mamet['id_polis'].'"'));
    /*
    $emnya = mysql_fetch_array(mysql_query('SELECT id, id_cost, id_polis, spak, ext_premi FROM fu_ajk_spak WHERE id_cost="'.$mamet['id_cost'].'" AND id_polis="'.$mamet['id_polis'].'" AND spak="'.$mamet['spaj'].'" AND status="Aktif"'));
    if ($mamet['ext_premi']!="" AND $mamet['spaj']!="SPK") {	$met_emnya = $mamet['ext_premi'] / $mamet['premi'] * 100;	}else{	$met_emnya = $emnya['ext_premi'];	}
    if ($mamet['status_peserta']=="") {	$statusnya__ = $mamet['status_aktif'];	}else{	$statusnya__ = $mamet['status_aktif'].'-'.$mamet['status_peserta'].'';	}
    */

    //$mettenornya = $mamet['kredit_tenor'];
    if ($mamet['type_data']=="SPK") {
        if ($mamet['mpptype']=="Y") {
            $tenormpp = $mamet['kredit_tenor'];

            if ($mamet['tglinput'] <= "2016-07-31") {		// PERUBAHAN RATE ASURANSI PERTANGGAL 1 AGUSTUS 2016
            $cekdataret = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi_as WHERE id !="" AND id_cost="'.$mamet['id_cost'].'" AND id_polis="'.$mamet['id_polis'].'" AND id_as="'.$mamet['id_as'].'" AND id_polis_as="'.$mamet['id_polis_as'].'" AND tenor="'.$tenormpp.'" AND '.$mamet['mppbln'].' BETWEEN mpp_s AND mpp_e AND status="lama" AND del="1"'));		// RATE PREMI
            if (!$cekdataret['rate']) {
                $cekdataret = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi_as WHERE id !="" AND id_cost="'.$mamet['id_cost'].'" AND id_polis="'.$mamet['id_polis'].'" AND id_as="'.$mamet['id_as'].'" AND id_polis_as="'.$mamet['id_polis_as'].'" AND tenor="'.$tenormpp.'" AND '.$mamet['mppbln'].' BETWEEN mpp_s AND mpp_e AND status="baru" AND del IS NULL'));		// RATE PREMI
            }
            } else {
                $cekdataret = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi_as WHERE id !="" AND id_cost="'.$mamet['id_cost'].'" AND id_polis="'.$mamet['id_polis'].'" AND id_as="'.$mamet['id_as'].'" AND id_polis_as="'.$mamet['id_polis_as'].'" AND tenor="'.$tenormpp.'" AND '.$mamet['mppbln'].' BETWEEN mpp_s AND mpp_e AND status="baru" AND del IS NULL'));		// RATE PREMI
            }
            /*
                    if ($mamet['mppbln'] < $mamet['mppbln_min']) {
                    $cekdataret = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi_as WHERE id !="" AND id_cost="'.$mamet['id_cost'].'" AND id_polis="'.$mamet['id_polis'].'" AND id_as="'.$mamet['id_as'].'" AND id_polis_as="'.$mamet['id_polis_as'].'" AND usia="'.$mamet['usia'].'" AND tenor="'.$tenormpp.'" AND status="lama"'));		// RATE PREMI
                    }else{
                    $cekdataret = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi_as WHERE id !="" AND id_cost="'.$mamet['id_cost'].'" AND id_polis="'.$mamet['id_polis'].'" AND id_as="'.$mamet['id_as'].'" AND id_polis_as="'.$mamet['id_polis_as'].'" AND tenor="'.$tenormpp.'" AND '.$mamet['mppbln'].' BETWEEN mpp_s AND mpp_e AND status="baru" AND del IS NULL'));		// RATE PREMI
                    }
            */
        } else {
            if ($mamet['tglinput'] <= "2016-07-31") {		// PERUBAHAN RATE ASURANSI PERTANGGAL 1 AGUSTUS 2016
        $cekdataret = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi_as WHERE id !="" AND id_cost="'.$mamet['id_cost'].'" AND id_polis="'.$mamet['id_polis'].'" AND id_as="'.$mamet['id_as'].'" AND id_polis_as="'.$mamet['id_polis_as'].'" AND usia="'.$mamet['usia'].'" AND tenor="'.$mamet['kredit_tenor'].'" AND status="lama" AND del="1"'));		// RATE PREMI
            if (!$cekdataret['rate']) {
                $cekdataret = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi_as WHERE id !="" AND id_cost="'.$mamet['id_cost'].'" AND id_polis="'.$mamet['id_polis'].'" AND id_as="'.$mamet['id_as'].'" AND id_polis_as="'.$mamet['id_polis_as'].'" AND usia="'.$mamet['usia'].'" AND tenor="'.$mamet['kredit_tenor'].'" AND status="baru" AND del IS NULL'));		// RATE PREMI
            }
            } else {
                $cekdataret = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi_as WHERE id !="" AND id_cost="'.$mamet['id_cost'].'" AND id_polis="'.$mamet['id_polis'].'" AND id_as="'.$mamet['id_as'].'" AND id_polis_as="'.$mamet['id_polis_as'].'" AND usia="'.$mamet['usia'].'" AND tenor="'.$mamet['kredit_tenor'].'" AND status="baru" AND del IS NULL'));		// RATE PREMI
            }
        }
        $met_emnya_2 = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_spak WHERE spak="'.$mamet['spaj'].'"  AND (status="Realisasi" OR status="Aktif")'));
    } else {
        if ($mamet['mpptype']=="Y") {
            $tenormpp = $mamet['kredit_tenor'] / 12;
            if ($mamet['tglinput'] <= "2016-07-31") {		// PERUBAHAN RATE ASURANSI PERTANGGAL 1 AGUSTUS 2016
                $cekdataret = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi_as WHERE id !="" AND id_cost="'.$mamet['id_cost'].'" AND id_polis="'.$mamet['id_polis'].'" AND id_as="'.$mamet['id_as'].'" AND id_polis_as="'.$mamet['id_polis_as'].'" AND tenor="'.$tenormpp.'" AND '.$mamet['mppbln'].' BETWEEN mpp_s AND mpp_e AND status="lama" AND del="1"'));
                if (!$cekdataret['rate']) {
                    $cekdataret = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi_as WHERE id !="" AND id_cost="'.$mamet['id_cost'].'" AND id_polis="'.$mamet['id_polis'].'" AND id_as="'.$mamet['id_as'].'" AND id_polis_as="'.$mamet['id_polis_as'].'" AND tenor="'.$tenormpp.'" AND '.$mamet['mppbln'].' BETWEEN mpp_s AND mpp_e AND status="baru" AND del IS NULL'));
                }
            } else {
                $cekdataret = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi_as WHERE id !="" AND id_cost="'.$mamet['id_cost'].'" AND id_polis="'.$mamet['id_polis'].'" AND id_as="'.$mamet['id_as'].'" AND id_polis_as="'.$mamet['id_polis_as'].'" AND tenor="'.$tenormpp.'" AND '.$mamet['mppbln'].' BETWEEN mpp_s AND mpp_e AND status="baru" AND del IS NULL'));
            }
            /*
                    if ($mamet['mppbln'] < $mamet['mppbln_min']) {
                        $cekdataret = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi_as WHERE id !="" AND id_cost="'.$mamet['id_cost'].'" AND id_polis="'.$mamet['id_polis'].'" AND id_as="'.$mamet['id_as'].'" AND id_polis_as="'.$mamet['id_polis_as'].'" AND tenor="'.$mamet['kredit_tenor'].'" AND status="lama"'));		// RATE PREMI
                    }else{
                        $cekdataret = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi_as WHERE id !="" AND id_cost="'.$mamet['id_cost'].'" AND id_polis="'.$mamet['id_polis'].'" AND id_as="'.$mamet['id_as'].'" AND id_polis_as="'.$mamet['id_polis_as'].'" AND tenor="'.$tenormpp.'" AND '.$mamet['mppbln'].' BETWEEN mpp_s AND mpp_e AND status="baru" AND del IS NULL'));
                    }
            */
        } else {
            if ($mamet['tglinput'] <= "2016-07-31") {		// PERUBAHAN RATE ASURANSI PERTANGGAL 1 AGUSTUS 2016
                $cekdataret = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi_as WHERE id !="" AND id_cost="'.$mamet['id_cost'].'" AND id_polis="'.$mamet['id_polis'].'" AND id_as="'.$mamet['id_as'].'" AND id_polis_as="'.$mamet['id_polis_as'].'" AND tenor="'.$mamet['kredit_tenor'].'" AND status="lama" AND del="1"'));
                if (!$cekdataret['rate']) {
                    $cekdataret = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi_as WHERE id !="" AND id_cost="'.$mamet['id_cost'].'" AND id_polis="'.$mamet['id_polis'].'" AND id_as="'.$mamet['id_as'].'" AND id_polis_as="'.$mamet['id_polis_as'].'" AND tenor="'.$mamet['kredit_tenor'].'" AND status="baru" AND del IS NULL'));
                }
            } else {
                $cekdataret = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi_as WHERE id !="" AND id_cost="'.$mamet['id_cost'].'" AND id_polis="'.$mamet['id_polis'].'" AND id_as="'.$mamet['id_as'].'" AND id_polis_as="'.$mamet['id_polis_as'].'" AND tenor="'.$mamet['kredit_tenor'].'" AND status="baru" AND del IS NULL'));
            }
        }
        $met_emnya_2 = '';
    }
    $emnya = mysql_fetch_array(mysql_query('SELECT id, id_cost, id_polis, spak, ext_premi FROM fu_ajk_spak WHERE id_cost="'.$mamet['id_cost'].'" AND id_polis="'.$mamet['id_polis'].'" AND spak="'.$mamet['spaj'].'" AND (status="Realisasi" OR status="Aktif")'));
    if ($mamet['ext_premi']!="" and $met_['spaj']!="SPK") {
        $met_emnya = $mamet['ext_premi'] / $mamet['premi'] * 100;
    } else {
        $met_emnya = $emnya['ext_premi'];
    }
    $metPremiAsuransi = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_peserta_as WHERE id_bank="'.$mamet['id_cost'].'" AND id_polis="'.$mamet['id_polis'].'" AND id_peserta="'.$mamet['id_peserta'].'" AND id_dn="'.$mamet['id_dn'].'"'));
    if ($metPremiAsuransi['rateasuransi']== null) {
        $rateAsuransinya = $cekdataret['rate'];
    } else {
        $rateAsuransinya = $metPremiAsuransi['rateasuransi'];
    }
    /*
    if ($mamet['type_data']=="SPK") {
        if ($mamet['mpptype']=="Y") {
            $dana_talangan = mysql_fetch_array(mysql_query("SELECT IFNULL(CASE WHEN (S.id_polis in (select id from fu_ajk_polis where mpptype = 'Y')) AND
                                                                                                F.idspk = (SELECT idspk FROM fu_ajk_spak_form WHERE nopermohonan = F.nopermohonan and (select status
                                                                                                                                    from fu_ajk_spak
                                                                                                                                    where fu_ajk_spak.id = fu_ajk_spak_form.idspk) not in ('kadaluarsa','batal') ORDER BY idspk DESC LIMIT 1)
                                                                                            THEN 'mpp' END,'')AS datampp
                                                                            FROM fu_ajk_spak_form AS F, fu_ajk_spak AS S,fu_ajk_polis AS P
                                                                            WHERE S.spak='".$mamet['spaj']."' AND F.idspk=S.id
                                                                            AND P.id = S.id_polis"));

            if($dana_talangan['datampp']=="mpp"){
                $tenorpeserta = $mamet['kredit_tenor'];
            }else{
                $tenorpeserta = $mamet['kredit_tenor'] * 12;
            }
        }else{
            $tenorpeserta = $mamet['kredit_tenor'] * 12;
        }
    }else{
        $tenorpeserta = $mamet['kredit_tenor'];
    }*/

    if ($mamet['type_data']=="SPK") {
        if ($mamet['danatalangan']!="") {
            $tenorpeserta = $mamet['kredit_tenor'];
        } else {
            $tenorpeserta = $mamet['kredit_tenor'] * 12;
        }
    } else {
        $tenorpeserta = $mamet['kredit_tenor'];
    }

    if ($mamet['id_as']==8) {
        $qspakform = mysql_fetch_array(mysql_query("SELECT fu_ajk_spak_form.*
																							FROM fu_ajk_spak
																										inner join fu_ajk_spak_form
																										on fu_ajk_spak_form.idspk = fu_ajk_spak.id
																							WHERE fu_ajk_spak.del is null and
																										fu_ajk_spak_form.del is null and
																										fu_ajk_spak.spak = '".$mamet['spaj']."'"));

        if ($qspakform['jns_kelamin']=="M") {
            $jnskelamin = 'Laki - Laki';
        } elseif ($qspakform['jns_kelamin']=="F") {
            $jnskelamin = 'Perempuan';
        }
    } else {
    }

    /*	$qspakform = mysql_fetch_array(mysql_query("SELECT fu_ajk_spak_form.*
                                                                                                FROM fu_ajk_spak
                                                                                                            inner join fu_ajk_spak_form
                                                                                                            on fu_ajk_spak_form.idspk = fu_ajk_spak.id
                                                                                                WHERE fu_ajk_spak.del is null and
                                                                                                            fu_ajk_spak_form.del is null and
                                                                                                            fu_ajk_spak.spak = '".$mamet['spaj']."'"));

        if($qspakform['jns_kelamin']=="M"){
            $jnskelamin = 'Laki - Laki';
        }elseif($qspakform['jns_kelamin']=="F"){
            $jnskelamin = 'Perempuan';
        }*/

    $worksheet1->writeString($baris, 0, ++$no);
    $worksheet1->writeString($baris, 1, $mamet['nmAsuransi']);
    $worksheet1->writeString($baris, 2, $mamet['nmproduk']);
    $worksheet1->writeString($baris, 3, $mamet['dn_kode']);
    $worksheet1->writeString($baris, 4, _convertDate($mamet['tgl_createdn']));
    $worksheet1->writeString($baris, 5, $mamet['id_peserta']);
    $worksheet1->writeString($baris, 6, $mamet['nama']);
    $worksheet1->writeString($baris, 7, $qspakform['noidentitas']);
    $worksheet1->writeString($baris, 8, $jnskelamin);
    $worksheet1->writeString($baris, 9, $qspakform['pekerjaan']);
    $worksheet1->writeString($baris, 10, $mamet['cabang']);
    $worksheet1->writeString($baris, 11, _convertDate($mamet['tgl_lahir']));
    $worksheet1->writeNumber($baris, 12, $mamet['usia']);
    $worksheet1->writeNumber($baris, 13, $mamet['kredit_jumlah']);
    $worksheet1->writeString($baris, 14, _convertDate($mamet['kredit_tgl']));
    $worksheet1->writeNumber($baris, 15, $tenorpeserta);
    $worksheet1->writeString($baris, 16, _convertDate($mamet['kredit_akhir']));
    $worksheet1->writeNumber($baris, 17, $rateAsuransinya);
    $worksheet1->writeNumber($baris, 18, $metPremiAsuransi['b_premi']);
    $worksheet1->writeNumber($baris, 19, duit($met_emnya_2['ext_premi']));
    $worksheet1->writeNumber($baris, 20, $metPremiAsuransi['b_extpremi']);
    $worksheet1->writeNumber($baris, 21, $metPremiAsuransi['nettpremi']);
    $worksheet1->writeString($baris, 22, strtoupper($mamet['dn_status']));
    $worksheet1->writeNumber($baris, 23, $mamet['mppbln']);
    $worksheet1->writeString($baris, 24, strtoupper($mamet['status_aktif']).' '.strtoupper($mamet['status_peserta']));

    $baris++;
}
$workbook->close();
    ;
    break;

case "eL_spk":
function HeaderingExcel($filename)
{
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$filename");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
    header("Pragma: public");
}
$met_c = mysql_fetch_array(mysql_query('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$_REQUEST['cat'].'"'));
HeaderingExcel('Laporan_SPK_'.$_REQUEST['tgl1'].'_sd_'.$_REQUEST['tgl2'].'.xls');
$workbook = new Workbook("");
$worksheet1 =& $workbook->add_worksheet('Laporan SPK');

$worksheet1->setMerge(0, 0, 0, 27);	$worksheet1->writeString(0, 0, "KONFIRMASI PREMIUM CALCULATION - AJK KONSORSIUM", $fjudul, 1);
$worksheet1->setMerge(1, 0, 1, 27);	$worksheet1->writeString(1, 0, strtoupper($met_c['name']), $fjudul);

$format =& $workbook->add_format();
$format->setAlign('vcenter');	$format->setAlign('center');	$format->setColor('white');	$format->setBold();	$format->setPattern();	$format->setFgColor('green');
$fjudul =& $workbook->add_format();	$fjudul->setAlign('vcenter');	$fjudul->setAlign('center');	$fjudul->setBold();

        $worksheet1->setRow(2, 15);
        $worksheet1->setColumn(2, 0, 5);	$worksheet1->writeString(2, 0, "NO", $format);
        $worksheet1->setColumn(2, 1, 15);	$worksheet1->writeString(2, 1, "PRODUK", $format);
        $worksheet1->setColumn(2, 2, 15);	$worksheet1->writeString(2, 2, "NAMA DEBITUR", $format);
        $worksheet1->setColumn(2, 3, 15);	$worksheet1->writeString(2, 3, "CABANG", $format);
        $worksheet1->setColumn(2, 4, 15);	$worksheet1->writeString(2, 4, "NO. SPK", $format);
        $worksheet1->setColumn(2, 5, 15);	$worksheet1->writeString(2, 5, "TGL INPUT SPK", $format);
        $worksheet1->setColumn(2, 6, 15);	$worksheet1->writeString(2, 6, "TGL APPROVE SPK", $format);
        $worksheet1->setColumn(2, 7, 15);	$worksheet1->writeString(2, 7, "TGL LAHIR", $format);
        $worksheet1->setColumn(2, 8, 15);	$worksheet1->writeString(2, 8, "TGL ASURANSI", $format);
        $worksheet1->setColumn(2, 9, 10);	$worksheet1->writeString(2, 9, "USIA AWAL ", $format);
        $worksheet1->setColumn(2, 10, 10);	$worksheet1->writeString(2, 10, "USIA AKHIR", $format);
        $worksheet1->setColumn(2, 11, 15);	$worksheet1->writeString(2, 11, "PLAFOND", $format);
        $worksheet1->setColumn(2, 12, 15);	$worksheet1->writeString(2, 12, "EM", $format);
        $worksheet1->setColumn(2, 13, 5);	$worksheet1->writeString(2, 13, "TENOR", $format);
        $worksheet1->setColumn(2, 14, 5);	$worksheet1->writeString(2, 14, "TB", $format);
        $worksheet1->setColumn(2, 15, 5);	$worksheet1->writeString(2, 15, "BB", $format);
        $worksheet1->setColumn(2, 16, 15);	$worksheet1->writeString(2, 16, "SISTOLIK", $format);
        $worksheet1->setColumn(2, 17, 15);	$worksheet1->writeString(2, 17, "DIASTOLIK", $format);
        $worksheet1->setColumn(2, 18, 15);	$worksheet1->writeString(2, 18, "NADI", $format);
        $worksheet1->setColumn(2, 19, 15);	$worksheet1->writeString(2, 19, "PERNAFASAN", $format);
        $worksheet1->setColumn(2, 20, 15);	$worksheet1->writeString(2, 20, "GULA DARAH", $format);
        $worksheet1->setColumn(2, 21, 10);	$worksheet1->writeString(2, 21, "MEROKOK", $format);
        $worksheet1->setColumn(2, 22, 15);	$worksheet1->writeString(2, 22, "JML ROKOK", $format);
        $worksheet1->setColumn(2, 23, 15);	$worksheet1->writeString(2, 23, "CATATAN SKS", $format);
        $worksheet1->setColumn(2, 24, 15);	$worksheet1->writeString(2, 24, "STATUS", $format);
        $worksheet1->setColumn(2, 25, 15);	$worksheet1->writeString(2, 25, "INPUT BY", $format);
        $worksheet1->setColumn(2, 26, 15);	$worksheet1->writeString(2, 26, "DEBITNOTE", $format);
        $worksheet1->setColumn(2, 27, 15);	$worksheet1->writeString(2, 27, "TANGGAL DEBITNOTE", $format);

if ($_REQUEST['cat']) {
    $satu = 'AND fu_ajk_spak_form.idcost = "'.$_REQUEST['cat'].'"';
}
if ($_REQUEST['subcat']) {
    $dua = 'AND fu_ajk_spak.id_polis = "'.$_REQUEST['subcat'].'"';
}
if ($_REQUEST['nmitra']) {
    $tiga = 'AND fu_ajk_spak.id_mitra = "'.$_REQUEST['nmitra'].'"';
}
if ($_REQUEST['tgl1']) {
    if ($_REQUEST['tgl1'] == $_REQUEST['tgl2']) {
        $PenambahanTgl = strtotime('+1 day', strtotime($_REQUEST['tgl2'])) ;
        ;
        $newdate = date('Y-m-d', $PenambahanTgl);
        $empat = 'AND fu_ajk_spak.input_date BETWEEN "'.$_REQUEST['tgl1'].'" AND "'.$newdate.'" ';
    } else {
        $PenambahanTgl = strtotime('+1 day', strtotime($_REQUEST['tgl2'])) ;
        ;
        $newdate2 = date('Y-m-d', $PenambahanTgl);
        $empat = 'AND fu_ajk_spak.input_date BETWEEN "'.$_REQUEST['tgl1'].'" AND "'.$newdate2.'" ';
    }
}
/*
if ($_REQUEST['st'])		{
    if ($_REQUEST['st']=="Realisasi") {
        $tiga = 'AND fu_ajk_spak.status = "Aktif" AND fu_ajk_peserta.id_dn !=""';
        $realisasi1 = ', fu_ajk_peserta.id_dn, fu_ajk_peserta.id_cost, fu_ajk_peserta.id_polis';
        $realisasi2 = 'LEFT JOIN fu_ajk_peserta ON fu_ajk_spak.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_spak.spak = fu_ajk_peserta.spaj AND fu_ajk_spak.id_polis = fu_ajk_peserta.id_polis';
        //$datastatus= 'Realisasi';
    }else{
        $tiga = 'AND fu_ajk_spak.status = "'.$_REQUEST['st'].'"';
        //$datastatus= $_REQUEST['st'];
    }
}
*/
if ($_REQUEST['st']) {
    $lima = 'AND fu_ajk_spak.status = "'.$_REQUEST['st'].'"';
}

if ($_REQUEST['em']) {
    if ($_REQUEST['em']=="Y") {
        $enam = 'AND fu_ajk_spak.ext_premi != ""';
    } else {
        $enam = 'AND fu_ajk_spak.ext_premi = ""';
    }
}

$baris = 3;
/*
$er_data = mysql_query('SELECT
fu_ajk_spak.spak,
fu_ajk_spak.ext_premi,
fu_ajk_spak.keterangan,
fu_ajk_spak.`status`,
fu_ajk_spak.input_by,
fu_ajk_spak_form.nama,
fu_ajk_spak_form.cabang,
DATE_FORMAT(fu_ajk_spak.input_date, "%Y-%m-%d") AS tglInput,
DATE_FORMAT(fu_ajk_spak.update_date, "%Y-%m-%d") AS tglApproveSPV,
fu_ajk_spak_form.tgl_periksa,
fu_ajk_spak_form.tgl_asuransi,
fu_ajk_spak_form.dob,
fu_ajk_spak_form.x_usia,
(fu_ajk_spak_form.x_usia + fu_ajk_spak_form.tenor) AS usiaakhir,
fu_ajk_spak_form.tenor,
fu_ajk_spak_form.plafond,
fu_ajk_spak_form.tinggibadan,
fu_ajk_spak_form.beratbadan,
fu_ajk_spak_form.tekanandarah,
fu_ajk_spak_form.nadi,
fu_ajk_spak_form.pernafasan,
fu_ajk_spak_form.guladarah,
fu_ajk_spak_form.catatan,
fu_ajk_spak_form.pertanyaan6,
fu_ajk_spak_form.ket6,
fu_ajk_spak_form.kesimpulan,
fu_ajk_polis.nmproduk
'.$realisasi1.'
FROM fu_ajk_spak
LEFT JOIN fu_ajk_spak_form ON fu_ajk_spak.id_cost = fu_ajk_spak_form.idcost AND fu_ajk_spak.id = fu_ajk_spak_form.idspk
LEFT JOIN fu_ajk_polis ON fu_ajk_spak.id_polis = fu_ajk_polis.id
'.$realisasi2.'
WHERE fu_ajk_spak.del IS NULL AND fu_ajk_spak_form.del IS NULL '.$satu.' '.$duaa.' '.$tiga.' '.$empat.'');
*/

$er_data = mysql_query('SELECT fu_ajk_spak.id,
fu_ajk_spak.id_cost,
fu_ajk_spak.id_polis,
fu_ajk_spak.id_mitra,
fu_ajk_spak.spak,
fu_ajk_spak.ext_premi,
fu_ajk_spak.`status`,
fu_ajk_spak.input_by,
DATE_FORMAT(fu_ajk_spak.input_date, "%Y-%m-%d") AS tglInput,
DATE_FORMAT(fu_ajk_spak.update_date, "%Y-%m-%d") AS tglApproveSPV,
fu_ajk_costumer.`name`,
fu_ajk_polis.nmproduk,
fu_ajk_spak_form.nama,
fu_ajk_spak_form.dob,
fu_ajk_spak_form.cabang,
fu_ajk_spak_form.tgl_asuransi,
fu_ajk_spak_form.x_usia,
(fu_ajk_spak_form.x_usia + fu_ajk_spak_form.tenor) AS usiaakhir,
fu_ajk_spak_form.tenor,
fu_ajk_spak_form.plafond,
fu_ajk_spak_form.tinggibadan,
fu_ajk_spak_form.beratbadan,
fu_ajk_spak_form.tekanandarah,
fu_ajk_spak_form.nadi,
fu_ajk_spak_form.pernafasan,
fu_ajk_spak_form.guladarah,
fu_ajk_spak_form.pertanyaan6,
fu_ajk_spak_form.ket6,
fu_ajk_spak_form.catatan,
fu_ajk_spak_form.kesimpulan
FROM fu_ajk_spak
INNER JOIN fu_ajk_costumer ON fu_ajk_spak.id_cost = fu_ajk_costumer.id
INNER JOIN fu_ajk_polis ON fu_ajk_spak.id_polis = fu_ajk_polis.id
INNER JOIN fu_ajk_spak_form ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
WHERE fu_ajk_spak.del IS NULL AND fu_ajk_spak_form.del IS NULL '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.'
ORDER BY fu_ajk_spak.input_date ASC');
while ($mamet = mysql_fetch_array($er_data)) {
    $tgl_terima_spak = explode(" ", $mamet['input_date']);
    $tolik = explode("/", $mamet['tekanandarah']);

    if ($mamet['pertanyaan6']=="T") {
        $pertanyaan6 = "Tidak";
    } else {
        $pertanyaan6 = "Iya";
    }

    if (is_numeric($mamet['cabang'])) {
        $met_Cabang = mysql_fetch_array(mysql_query('SELECT id, name FROM fu_ajk_cabang WHERE id="'.$mamet['cabang'].'"'));
        $inputcabang = $met_Cabang['name'];
    } else {
        $inputcabang = $mamet['cabang'];
    }

    if (is_numeric($mamet['input_by'])) {
        $nmUser = mysql_fetch_array(mysql_query('SELECT id, namalengkap FROM user_mobile WHERE id="'.$mamet['input_by'].'"'));
        $_nmMarketing = $nmUser['namalengkap'];
    } else {
        $_nmMarketing = $mamet['input_by'];
    }

    //$metDN = mysql_fetch_array(mysql_query('SELECT id, dn_kode FROM fu_ajk_dn WHERE id="'.$mamet['id_dn'].'"'));
    $metDN = mysql_fetch_array(mysql_query('SELECT fu_ajk_peserta.id,
fu_ajk_peserta.id_dn,
fu_ajk_peserta.spaj,
fu_ajk_peserta.status_aktif,
fu_ajk_peserta.status_peserta,
fu_ajk_dn.dn_kode,
fu_ajk_dn.tgl_createdn
FROM fu_ajk_peserta
INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id
WHERE fu_ajk_peserta.spaj="'.$mamet['spak'].'"'));
    if ($metDN['status_aktif']=="Lapse") {
        $statusDataSPK = $metDN['status_peserta'];
    } else {
        $statusDataSPK = $mamet['status'];
    }
    $worksheet1->writeString($baris, 0, ++$no);
    $worksheet1->writeString($baris, 1, $mamet['nmproduk'].'');
    $worksheet1->writeString($baris, 2, $mamet['nama'].'');
    $worksheet1->writeString($baris, 3, $inputcabang);
    $worksheet1->writeString($baris, 4, $mamet['spak']);
    $worksheet1->writeString($baris, 5, $mamet['tglInput']);
    $worksheet1->writeString($baris, 6, $mamet['tglApproveSPV']);
    $worksheet1->writeString($baris, 7, $mamet['dob']);
    $worksheet1->writeString($baris, 8, $mamet['tgl_asuransi']);
    $worksheet1->writeNumber($baris, 9, $mamet['x_usia']);
    $worksheet1->writeNumber($baris, 10, $mamet['usiaakhir']);
    $worksheet1->writeNumber($baris, 11, $mamet['plafond']);
    $worksheet1->writeNumber($baris, 12, $mamet['ext_premi']);
    $worksheet1->writeNumber($baris, 13, $mamet['tenor']);
    $worksheet1->writeNumber($baris, 14, $mamet['tinggibadan']);
    $worksheet1->writeNumber($baris, 15, $mamet['beratbadan']);
    $worksheet1->writeNumber($baris, 16, $tolik[0]);
    $worksheet1->writeNumber($baris, 17, $tolik[1]);
    $worksheet1->writeNumber($baris, 18, $mamet['nadi']);
    $worksheet1->writeNumber($baris, 19, $mamet['pernafasan']);
    $worksheet1->writeNumber($baris, 20, $mamet['guladarah']);
    $worksheet1->writeString($baris, 21, $pertanyaan6);
    $worksheet1->writeString($baris, 22, $mamet['ket6']);
    $worksheet1->writeString($baris, 23, $mamet['catatan']);
    $worksheet1->writeString($baris, 24, $statusDataSPK);
    $worksheet1->writeString($baris, 25, $_nmMarketing);
    $worksheet1->writeString($baris, 26, $metDN['dn_kode']);
    $worksheet1->writeString($baris, 27, $metDN['tgl_createdn']);
    $baris++;
}
$workbook->close();
        ;
        break;

case "eL_RekapSPK":
function HeaderingExcel($filename)
{
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$filename");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
    header("Pragma: public");
}
    $met_c = mysql_fetch_array(mysql_query('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$_REQUEST['cat'].'"'));
    HeaderingExcel('REKAP_SPK.xls');
    $workbook = new Workbook("");
    $worksheet1 =& $workbook->add_worksheet('REKAP SPK');

    $met_c = mysql_fetch_array(mysql_query('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$_REQUEST['idCost'].'"'));
    $worksheet1->setMerge(0, 0, 0, 11);	$worksheet1->writeString(0, 0, "REKAPITULASI DATA SPK", $fjudul, 1);
    $worksheet1->setMerge(1, 0, 1, 11);	$worksheet1->writeString(1, 0, strtoupper($met_c['name']), $fjudul);

    $format =& $workbook->add_format();
    $format->setAlign('vcenter');	$format->setAlign('center');	$format->setColor('white');	$format->setBold();	$format->setPattern();	$format->setFgColor('green');
    $fjudul =& $workbook->add_format();	$fjudul->setAlign('vcenter');	$fjudul->setAlign('center');	$fjudul->setBold();

    $worksheet1->setRow(2, 15);
    $worksheet1->setColumn(2, 0, 5);	$worksheet1->writeString(2, 0, "NO", $format);
    $worksheet1->setColumn(2, 1, 15);	$worksheet1->writeString(2, 1, "NAMA PRODUK", $format);
    $worksheet1->setColumn(2, 2, 15);	$worksheet1->writeString(2, 2, "STATUS", $format);
    $worksheet1->setColumn(2, 3, 15);	$worksheet1->writeString(2, 3, "NAMA DEBITUR", $format);
    $worksheet1->setColumn(2, 4, 15);	$worksheet1->writeString(2, 4, "NOMOR SPK", $format);
    $worksheet1->setColumn(2, 5, 15);	$worksheet1->writeString(2, 5, "PREMI", $format);
    $worksheet1->setColumn(2, 6, 15);	$worksheet1->writeString(2, 6, "CABANG", $format);
    $worksheet1->setColumn(2, 7, 15);	$worksheet1->writeString(2, 7, "TANGGAL SPK", $format);
    $worksheet1->setColumn(2, 8, 15);	$worksheet1->writeString(2, 8, "TANGGAL APPROVE SPV", $format);
    $worksheet1->setColumn(2, 9, 15);	$worksheet1->writeString(2, 9, "TANGGAL PERIKSA DOKTER CABANG", $format);
    $worksheet1->setColumn(2, 10, 10);	$worksheet1->writeString(2, 10, "TANGGAL APPROVE DOKTER ADONAI", $format);
    $worksheet1->setColumn(2, 10, 11);	$worksheet1->writeString(2, 11, "INPUT SPK", $format);

if ($_REQUEST['idCost']) {
    $satu = 'AND fu_ajk_spak.id_cost = "'.$_REQUEST['idCost'].'"';
}
//if ($_REQUEST['tgl1'])			{	$duaa = 'AND fu_ajk_spak.input_date BETWEEN "'.$_REQUEST['tgl1'].'" AND "'.$_REQUEST['tgl2'].'" ';	}
if ($_REQUEST['tgl1']) {
    if ($_REQUEST['tgl1'] == $_REQUEST['tgl2']) {
        $PenambahanTgl = strtotime('+1 day', strtotime($_REQUEST['tgl2'])) ;
        ;
        $newdate = date('Y-m-d', $PenambahanTgl);
        $duaa = 'AND fu_ajk_spak.input_date BETWEEN "'.$_REQUEST['tgl1'].'" AND "'.$newdate.'" ';
    } else {
        $PenambahanTgl = strtotime('+1 day', strtotime($_REQUEST['tgl2'])) ;
        ;
        $newdate2 = date('Y-m-d', $PenambahanTgl);
        $duaa = 'AND fu_ajk_spak.input_date BETWEEN "'.$_REQUEST['tgl1'].'" AND "'.$newdate2.'" ';
    }
}
if ($_REQUEST['st']) {
    $tiga = 'AND fu_ajk_spak.status = "'.$_REQUEST['st'].'"';
}

$baris = 3;
$er_data = mysql_query('SELECT
fu_ajk_costumer.`name`,
fu_ajk_polis.nmproduk,
fu_ajk_spak.id,
fu_ajk_spak.id_cost,
fu_ajk_spak.id_polis,
fu_ajk_spak.spak,
fu_ajk_spak.`status`,
fu_ajk_spak.input_by,
DATE_FORMAT(fu_ajk_spak.input_date,"%Y-%m-%d") AS tglInput,
DATE_FORMAT(fu_ajk_spak.update_date,"%Y-%m-%d") AS tglApprove,
fu_ajk_spak_form.nama,
fu_ajk_spak_form.input_by AS ApproveDokterCabang,
DATE_FORMAT(fu_ajk_spak_form.input_date,"%Y-%m-%d") AS ApproveTglDokterCabang,
fu_ajk_spak.approve_by AS ApproveDokterUW,
DATE_FORMAT(fu_ajk_spak.approve_date,"%Y-%m-%d") AS ApproveTglDokterUW,
fu_ajk_spak_form.x_premi,
fu_ajk_spak_form.filefotodebitursatu,
fu_ajk_spak_form.filefotodebiturdua,
fu_ajk_spak_form.filefotoktp,
fu_ajk_spak_form.filettddebitur,
fu_ajk_spak_form.filettdmarketing,
fu_ajk_spak_form.filettddokter,
fu_ajk_spak_form.filefotoskpensiun,
fu_ajk_spak_form.cabang
FROM
fu_ajk_spak
INNER JOIN fu_ajk_spak_form ON fu_ajk_spak.id_cost = fu_ajk_spak_form.idcost AND fu_ajk_spak.id = fu_ajk_spak_form.idspk
INNER JOIN fu_ajk_costumer ON fu_ajk_spak.id_cost = fu_ajk_costumer.id
INNER JOIN fu_ajk_polis ON fu_ajk_spak.id_polis = fu_ajk_polis.id
WHERE  fu_ajk_spak.id !="" '.$satu.' '.$duaa.' '.$tiga.' AND fu_ajk_spak_form.del IS NULL
ORDER BY fu_ajk_spak.input_date DESC');
while ($mamet = mysql_fetch_array($er_data)) {
    if (is_numeric($mamet['cabang'])) {
        $met_Cabang = mysql_fetch_array(mysql_query('SELECT id, name FROM fu_ajk_cabang WHERE id="'.$mamet['cabang'].'"'));
        $inputcabang = $met_Cabang['name'];
    } else {
        $inputcabang = $mamet['cabang'];
    }

    if (is_numeric($mamet['input_by'])) {
        $nmUser = mysql_fetch_array(mysql_query('SELECT id, namalengkap FROM user_mobile WHERE id="'.$mamet['input_by'].'"'));
        $_nmMarketing = $nmUser['namalengkap'];
    } else {
        $_nmMarketing = $mamet['input_by'];
    }

    $worksheet1->writeString($baris, 0, ++$no);
    $worksheet1->writeString($baris, 1, $mamet['nmproduk']);
    $worksheet1->writeString($baris, 2, $mamet['status'], "C");
    $worksheet1->writeString($baris, 3, $mamet['nama']);
    $worksheet1->writeString($baris, 4, $mamet['spak'], "C");
    $worksheet1->writeNumber($baris, 5, $mamet['x_premi'], "R");
    $worksheet1->writeString($baris, 6, $inputcabang, "C");
    $worksheet1->writeString($baris, 7, _ConvertDate($mamet['tglInput']));
    $worksheet1->writeString($baris, 8, _ConvertDate($mamet['tglApprove']));
    $worksheet1->writeString($baris, 9, _ConvertDate($mamet['ApproveTglDokterCabang']));
    $worksheet1->writeString($baris, 10, _ConvertDate($mamet['ApproveTglDokterUW']));
    $worksheet1->writeString($baris, 11, $_nmMarketing);
    $baris++;
}
    $workbook->close();
    ;
    break;

case "eL_prod":
function HeaderingExcel($filename)
{
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$filename");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
    header("Pragma: public");
}
    $met_c = mysql_fetch_array(mysql_query('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$_REQUEST['cat'].'"'));

    $stringcost =  str_replace(" ", "_", $met_c['name']);
if ($_REQUEST['dncreate']==$_REQUEST['dncreate1']) {
    HeaderingExcel('Laporan_'.$stringcost.'.xls');
} else {
    HeaderingExcel('Laporan_'.$stringcost.'.xls');
}

    $workbook = new Workbook("");
    $worksheet1 =& $workbook->add_worksheet('Laporan Produksi');

    $format =& $workbook->add_format();
    $format->setAlign('vcenter');	$format->setAlign('center');	$format->setColor('white');	$format->setBold();	$format->setPattern();	$format->setFgColor('green');

    $fjudul =& $workbook->add_format();	$fjudul->setAlign('vcenter');	$fjudul->setAlign('center');	$fjudul->setBold();

    $worksheet1->setMerge(0, 0, 0, 16);	$worksheet1->writeString(0, 0, "LAPORAN PRODUKSI PESERTA ASURANSI JIWA KUMPULAN REGULER", $fjudul, 1);
    $worksheet1->setMerge(1, 0, 1, 16);	$worksheet1->writeString(1, 0, "PERIODE "._convertDate($_REQUEST['tgl1'])." S/D "._convertDate($_REQUEST['tgl2'])."", $fjudul);
    $worksheet1->setMerge(2, 0, 2, 16);	$worksheet1->writeString(2, 0, strtoupper($met_c['name']), $fjudul);


    $worksheet1->setRow(4, 15);
    $worksheet1->setColumn(4, 0, 5);	$worksheet1->writeString(4, 0, "NO", $format);
    $worksheet1->setColumn(4, 1, 15);	$worksheet1->writeString(4, 1, "NOMOR DN", $format);
    $worksheet1->setColumn(4, 2, 15);	$worksheet1->writeString(4, 2, "TGL DN", $format);
    $worksheet1->setColumn(4, 3, 15);	$worksheet1->writeString(4, 3, "ID PESERTA", $format);
    $worksheet1->setColumn(4, 4, 15);	$worksheet1->writeString(4, 4, "NAMA DEBITUR", $format);
    $worksheet1->setColumn(4, 5, 10);	$worksheet1->writeString(4, 5, "CABANG", $format);
    $worksheet1->setColumn(4, 6, 10);	$worksheet1->writeString(4, 6, "TGL LAHIR", $format);
    $worksheet1->setColumn(4, 7, 5);	$worksheet1->writeString(4, 7, "USIA", $format);
    $worksheet1->setColumn(4, 8, 10);	$worksheet1->writeString(4, 8, "PLAFOND", $format);
    $worksheet1->setColumn(4, 9, 5);	$worksheet1->writeString(4, 9, "JK.W", $format);
    $worksheet1->setColumn(4, 10, 15);	$worksheet1->writeString(4, 10, "MULAI ASURANSI", $format);
    $worksheet1->setColumn(4, 11, 15);	$worksheet1->writeString(4, 11, "AKHIR ASURANSI", $format);
    $worksheet1->setColumn(4, 12, 10);	$worksheet1->writeString(4, 12, "RATE TUNGGAL", $format);
    $worksheet1->setColumn(4, 13, 15);	$worksheet1->writeString(4, 13, "EM (%)", $format);
    $worksheet1->setColumn(4, 14, 15);	$worksheet1->writeString(4, 14, "TOTAL RATE", $format);
    $worksheet1->setColumn(4, 15, 15);	$worksheet1->writeString(4, 15, "TOTAL PREMI", $format);
    $worksheet1->setColumn(4, 16, 15);	$worksheet1->writeString(4, 16, "ASURANSI", $format);

if ($_REQUEST['cat']) {
    $satu = 'AND id_cost ="' . $_REQUEST['cat'] . '"';
}
if ($_REQUEST['subcat']) {
    $duaa = 'AND id_polis ="' . $_REQUEST['subcat'] . '"';
}
if ($_REQUEST['tgl1']) {
    $tiga = 'AND kredit_tgl BETWEEN "'.$_REQUEST['tgl1'].'" AND "'.$_REQUEST['tgl2'].'" ';
}
if ($_REQUEST['paid']) {
    $empt = 'AND status_bayar ="' . $_REQUEST['paid'] . '"';
}
if ($_REQUEST['status']) {
    $lima = 'AND status_aktif ="' . $_REQUEST['status'] . '"';
}
if ($_REQUEST['id_reg']) {
    $met_reg = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['id_reg'].'"'));
    $enam = 'AND regional = "'.$met_reg['name'].'"';
}
if ($_REQUEST['id_cab']) {
    $met_cab = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_cabang WHERE id="'.$_REQUEST['id_cab'].'"'));
    $tujuh = 'AND cabang = "'.$met_cab['name'].'"';
}
$baris = 5;
$er_data = mysql_query('SELECT * FROM fu_ajk_peserta WHERE id_dn !="" '.$satu.' '.$duaa.' '.$tiga.' '.$empt.' '.$lima.' '.$enam.' '.$tujuh.' AND del IS NULL ORDER BY kredit_tgl ASC');
while ($mamet = mysql_fetch_array($er_data)) {
    $cekdatadn = mysql_fetch_array(mysql_query('SELECT id, dn_kode, id_as, tgl_createdn FROM fu_ajk_dn WHERE id="'.$mamet['id_dn'].'" AND id_cost="'.$mamet['id_cost'].'"'));
    $cekdataAS = mysql_fetch_array(mysql_query('SELECT id, name FROM fu_ajk_asuransi WHERE id="'.$cekdatadn['id_as'].'"'));

    $cekdataret = mysql_fetch_array(mysql_query('SELECT id_cost, id_polis, tenor, rate FROM fu_ajk_ratepremi WHERE id !="" '.$satu.' '.$dua.' AND tenor="'.$mamet['kredit_tenor'].'"'));
    $worksheet1->writeString($baris, 0, ++$no);
    $worksheet1->writeString($baris, 1, $cekdatadn['dn_kode']);
    $worksheet1->writeString($baris, 2, $cekdatadn['tgl_createdn']);
    $worksheet1->writeString($baris, 3, $mamet['id_peserta']);
    $worksheet1->writeString($baris, 4, $mamet['nama']);
    $worksheet1->writeString($baris, 5, $mamet['cabang']);
    $worksheet1->writeString($baris, 6, _convertDate($mamet['tgl_lahir']));
    $worksheet1->writeString($baris, 7, $mamet['usia']);
    $worksheet1->writeString($baris, 8, $mamet['kredit_jumlah']);
    $worksheet1->writeString($baris, 9, $mamet['kredit_tenor']);
    $worksheet1->writeString($baris, 10, _convertDate($mamet['kredit_tgl']));
    $worksheet1->writeString($baris, 11, _convertDate($mamet['kredit_akhir']));
    $worksheet1->writeString($baris, 12, $cekdataret['rate']);
    $worksheet1->writeString($baris, 13, $mamet['']);
    $worksheet1->writeString($baris, 14, $mamet['']);
    $worksheet1->writeString($baris, 15, $mamet['totalpremi']);
    $worksheet1->writeString($baris, 16, $cekdataAS['name']);
    $baris++;
}
    $workbook->close();
    ;
    break;

case "eL_Batal":
function HeaderingExcel($filename)
{
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$filename");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
    header("Pragma: public");
}
    $met_c = mysql_fetch_array(mysql_query('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$_REQUEST['cat'].'"'));

    $stringcost =  str_replace(" ", "_", $met_c['name']);
if ($_REQUEST['dncreate']==$_REQUEST['dncreate1']) {
    HeaderingExcel('Laporan_Pembatalan_'.$stringcost.'.xls');
} else {
    HeaderingExcel('Laporan_'.$stringcost.'.xls');
}

    $workbook = new Workbook("");
    $worksheet1 =& $workbook->add_worksheet('Laporan Pembatalan');

    $format =& $workbook->add_format();
    $format->setAlign('vcenter');	$format->setAlign('center');	$format->setColor('white');	$format->setBold();	$format->setPattern();	$format->setFgColor('green');

    $fjudul =& $workbook->add_format();	$fjudul->setAlign('vcenter');	$fjudul->setAlign('center');	$fjudul->setBold();

    $worksheet1->setMerge(0, 0, 0, 20);	$worksheet1->writeString(0, 0, "LAPORAN PEMBATALAN PESERTA ASURANSI JIWA KREDIT (AJK)", $fjudul, 1);
    $worksheet1->setMerge(1, 0, 1, 20);	$worksheet1->writeString(1, 0, "PERIODE "._convertDate($_REQUEST['tgl1'])." S/D "._convertDate($_REQUEST['tgl2'])."", $fjudul);
    $worksheet1->setMerge(2, 0, 2, 20);	$worksheet1->writeString(2, 0, strtoupper($met_c['name']), $fjudul);


    $worksheet1->setRow(4, 15);
    $worksheet1->setColumn(4, 0, 5);	$worksheet1->writeString(4, 0, "NO", $format);
    $worksheet1->setColumn(4, 1, 15);	$worksheet1->writeString(4, 1, "ASURANSI", $format);
    $worksheet1->setColumn(4, 2, 15);	$worksheet1->writeString(4, 2, "ID PESERTA", $format);
    $worksheet1->setColumn(4, 3, 15);	$worksheet1->writeString(4, 3, "NAMA DEBITUR", $format);
    $worksheet1->setColumn(4, 4, 15);	$worksheet1->writeString(4, 4, "REGIONAL", $format);
    $worksheet1->setColumn(4, 5, 15);	$worksheet1->writeString(4, 5, "CABANG", $format);
    $worksheet1->setColumn(4, 6, 10);	$worksheet1->writeString(4, 6, "TGL LAHIR", $format);
    $worksheet1->setColumn(4, 7, 5);	$worksheet1->writeString(4, 7, "USIA", $format);
    $worksheet1->setColumn(4, 8, 10);	$worksheet1->writeString(4, 8, "PLAFOND", $format);
    $worksheet1->setColumn(4, 9, 5);	$worksheet1->writeString(4, 9, "JK.W", $format);
    $worksheet1->setColumn(4, 10, 5);	$worksheet1->writeString(4, 10, "Grace Period", $format);
    $worksheet1->setColumn(4, 11, 15);	$worksheet1->writeString(4, 11, "TGL AKAD", $format);
    $worksheet1->setColumn(4, 12, 15);	$worksheet1->writeString(4, 12, "TGL AKHIR", $format);
    $worksheet1->setColumn(4, 13, 10);	$worksheet1->writeString(4, 13, "RATE", $format);
    $worksheet1->setColumn(4, 14, 10);	$worksheet1->writeString(4, 14, "PREMI", $format);
    $worksheet1->setColumn(4, 15, 10);	$worksheet1->writeString(4, 15, "E.M", $format);
    $worksheet1->setColumn(4, 16, 10);	$worksheet1->writeString(4, 16, "TOTAL PREMI", $format);
    $worksheet1->setColumn(4, 17, 10);	$worksheet1->writeString(4, 17, "PREMI REFUND", $format);
    $worksheet1->setColumn(4, 18, 15);	$worksheet1->writeString(4, 18, "PRODUK", $format);
    $worksheet1->setColumn(4, 19, 10);	$worksheet1->writeString(4, 19, "TGL BATAL", $format);
    $worksheet1->setColumn(4, 20, 15);	$worksheet1->writeString(4, 20, "ALASAN", $format);
    $worksheet1->setColumn(4, 21, 15);	$worksheet1->writeString(4, 21, "DEBIT NOTE", $format);
    $worksheet1->setColumn(4, 22, 15);	$worksheet1->writeString(4, 22, "CREDIT NOTE", $format);

if ($_REQUEST['cat']) {
    $satu = 'AND id_cost = "'.$_REQUEST['cat'].'"';
}
if ($_REQUEST['subcat']) {
    $duaa = 'AND id_nopol = "'.$_REQUEST['subcat'].'"';
}
    //if ($_REQUEST['tglcheck1'])		{	$tiga = 'AND tgl_claim BETWEEN "'.$_REQUEST['tglcheck1'].'" AND "'.$_REQUEST['tglcheck2'].'" ';	}
if ($_REQUEST['tgl1']) {
    $empt = 'AND tgl_createcn BETWEEN "'.$_REQUEST['tgl1'].'" AND "'.$_REQUEST['tgl2'].'" ';
}
    $baris = 5;
$met = mysql_query('SELECT * FROM fu_ajk_cn WHERE id_cn !="" AND type_claim="Batal" AND del is NULL '.$satu.' '.$duaa.' '.$tiga.' '.$empt.' ORDER BY tgl_createcn DESC');
while ($met_ = mysql_fetch_array($met)) {
    $met_produk = mysql_fetch_array(mysql_query('SELECT id, nmproduk FROM fu_ajk_polis WHERE id="'.$met_['id_nopol'].'"'));
    $met_peserta = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_peserta WHERE id_cost="'.$met_['id_cost'].'" AND id_polis="'.$met_['id_nopol'].'" AND id_peserta="'.$met_['id_peserta'].'"'));
    $tglbatal = explode("#", $met_peserta['ket']);
    if ($met_produk['singlerate']=="Y") {
        $ratetenornya = $met_peserta['kredit_tenor'] / 12;
    } else {
        $ratetenornya = $met_peserta['kredit_tenor'];
    }
    $cekdataret = mysql_fetch_array(mysql_query('SELECT id_cost, id_polis, tenor, rate FROM fu_ajk_ratepremi WHERE id !="" AND id_cost="'.$met_['id_cost'].'" AND id_polis="'.$met_['id_nopol'].'" AND tenor="'.$ratetenornya.'" AND status="Baru"'));
    $met_dn = mysql_fetch_array(mysql_query('SELECT id, dn_kode, id_as FROM fu_ajk_dn WHERE id="'.$met_['id_dn'].'"'));
    $met_dn_Asuransi = mysql_fetch_array(mysql_query('SELECT id, name FROM fu_ajk_asuransi WHERE id="'.$met_dn['id_as'].'"'));

    $worksheet1->writeString($baris, 0, ++$no);
    $worksheet1->writeString($baris, 1, $met_dn_Asuransi['name']);
    $worksheet1->writeString($baris, 2, $met_['id_peserta']);
    $worksheet1->writeString($baris, 3, $met_peserta['nama']);
    $worksheet1->writeString($baris, 4, $met_['id_regional']);
    $worksheet1->writeString($baris, 5, $met_['id_cabang']);
    $worksheet1->writeString($baris, 6, _convertDate($met_peserta['tgl_lahir']));
    $worksheet1->writeNumber($baris, 7, $met_peserta['usia']);
    $worksheet1->writeNumber($baris, 8, $met_peserta['kredit_jumlah']);
    $worksheet1->writeString($baris, 9, $met_peserta['kredit_tenor']);
    $worksheet1->writeString($baris, 10, $met_peserta['mppbln']);
    $worksheet1->writeString($baris, 11, _convertDate($met_peserta['kredit_tgl']));
    $worksheet1->writeString($baris, 12, _convertDate($met_peserta['kredit_akhir']));
    $worksheet1->writeString($baris, 13, $cekdataret['rate']);
    $worksheet1->writeNumber($baris, 14, $met_peserta['premi']);
    $worksheet1->writeString($baris, 15, $met_peserta['ext_premi']);
    $worksheet1->writeNumber($baris, 16, $met_peserta['totalpremi']);
    $worksheet1->writeNumber($baris, 17, $met['premi']);
    $worksheet1->writeString($baris, 18, $met_produk['nmproduk']);
    $worksheet1->writeString($baris, 19, $tglbatal[0]);
    $worksheet1->writeString($baris, 20, $tglbatal[1]);
    $worksheet1->writeString($baris, 21, $met_dn['dn_kode']);
    $worksheet1->writeString($baris, 22, $met_['id_cn']);
    $baris++;
}
    $workbook->close();
    ;
    break;

case "eL_rmf":
function HeaderingExcel($filename)
{
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$filename");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
    header("Pragma: public");
}
    $met_c = mysql_fetch_array(mysql_query('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$_REQUEST['cat'].'"'));

    $stringcost =  str_replace(" ", "_", $met_c['name']);
if ($_REQUEST['dncreate']==$_REQUEST['dncreate1']) {
    HeaderingExcel('Laporan_'.$stringcost.'_RMF.xls');
} else {
    HeaderingExcel('Laporan_'.$stringcost.'_RMF.xls');
}

    $workbook = new Workbook("");
    $worksheet1 =& $workbook->add_worksheet('Laporan RMF');

    $format =& $workbook->add_format();
    $format->setAlign('vcenter');	$format->setAlign('center');	$format->setColor('white');	$format->setBold();	$format->setPattern();	$format->setFgColor('green');

    $fjudul =& $workbook->add_format();	$fjudul->setAlign('vcenter');	$fjudul->setAlign('center');	$fjudul->setBold();
    $fjudul1 =& $workbook->add_format();	$fjudul1->setAlign('vcenter');	$fjudul1->setAlign('center');
    $fjudul2 =& $workbook->add_format();	$fjudul2->setAlign('vcenter');	$fjudul2->setAlign('right');
    $fjudul3 =& $workbook->add_format();	$fjudul3->setAlign('vcenter');	$fjudul3->setAlign('center');

    $worksheet1->setMerge(0, 0, 0, 15);	$worksheet1->writeString(0, 0, "LAPORAN RISK MANAGEMENT FUND (RMF) PESERTA ASURANSI JIWA KUMPULAN REGULER", $fjudul, 1);
    $worksheet1->setMerge(1, 0, 1, 15);	$worksheet1->writeString(1, 0, "PERIODE "._convertDate($_REQUEST['tgl1'])." S/D "._convertDate($_REQUEST['tgl2'])."", $fjudul);
    $worksheet1->setMerge(2, 0, 2, 15);	$worksheet1->writeString(2, 0, strtoupper($met_c['name']), $fjudul);

if ($_REQUEST['cat']) {
    $satu = 'AND id_cost ="' . $_REQUEST['cat'] . '"';
}
if ($_REQUEST['subcat']) {
    $duaa = 'AND id_polis ="' . $_REQUEST['subcat'] . '"';
}
if ($_REQUEST['tgl1']) {
    $tiga = 'AND kredit_tgl BETWEEN "'.$_REQUEST['tgl1'].'" AND "'.$_REQUEST['tgl2'].'" ';
}
if ($_REQUEST['paid']) {
    $empt = 'AND status_bayar ="' . $_REQUEST['paid'] . '"';
}
if ($_REQUEST['status']) {
    $lima = 'AND status_aktif ="' . $_REQUEST['status'] . '"';
}
if ($_REQUEST['reg']) {
    $met_reg = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['reg'].'"'));
    $enam = 'AND regional = "'.$met_reg['name'].'"';
}
if ($_REQUEST['cab']) {
    $met_cab = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_cabang WHERE id="'.$_REQUEST['cab'].'"'));
    $tujuh = 'AND cabang = "'.$met_cab['name'].'"';
}

    $_namaperusahaan = mysql_fetch_array(mysql_query('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$_REQUEST['cat'].'"'));
    //STATUS PRODUK//
    $_namaproduk = mysql_fetch_array(mysql_query('SELECT id, nmproduk FROM fu_ajk_polis WHERE id="'.$_REQUEST['subcat'].'"'));
if (!$_REQUEST['id_polis']) {
    $_namaproduknya_ = "SEMUA PRODUK";
} else {
    $_namaproduknya_ = $_namaproduk['nmproduk'];
}
    //STATUS PRODUK//

    //STATUS PEMBAYARAN/
if ($_REQUEST['paid']=="1") {
    $_statusbayar="PAID";
} elseif ($_REQUEST['paid']=="0") {
    $_statusbayar="UNPAID";
} else {
    $_statusbayar="PAID dan UNPAID";
}
    //STATUS PEMBAYARAN//

    //STATUS WILAYAH//
if (!$_REQUEST['reg']) {
    $regionalnya = "SEMUA REGIONAL";
} else {
    $regionalnya = $met_reg['name'];
}
if (!$_REQUEST['cab']) {
    $cabangnya = "SEMUA CABANG";
} else {
    $cabangnya = $met_cab['name'];
}
    //STATUS WILAYAH//

    $cekdata_paid = mysql_query('SELECT id, id_cost, id_polis, kredit_tgl, status_bayar, status_aktif, status_bayar, totalpremi, rmf, del FROM fu_ajk_peserta WHERE id !="" AND status_aktif!="Batal" AND del is NULL '.$satu.' '.$duaa.' '.$tiga.' '.$empt.' '.$lima.' '.$enam.' '.$tujuh.'');
while ($cekdata_paid_ = mysql_fetch_array($cekdata_paid)) {
    if ($cekdata_paid_['status_bayar']=="1") {
        $met_cekdata_paid += $cekdata_paid_['totalpremi'];
        $met_cekdata_paid_rmf += $cekdata_paid_['rmf'];
    } else {
        $met_cekdata_unpaid += $cekdata_paid_['totalpremi'];
    }
}
    $metRMFtotal = $met_cekdata_paid + $met_cekdata_unpaid;

/*
$met_rmf_paid = mysql_query('SELECT id, id_cost, id_polis, kredit_tgl, status_bayar, status_aktif, premi, rmf, del FROM fu_ajk_peserta WHERE id !="" AND status_aktif!="Batal" AND status_bayar="1" AND del is NULL '.$satu.' '.$duaa.' '.$tiga.' '.$empt.' '.$lima.' '.$enam.' '.$tujuh.'');
while ($met_rmf_paid_ = mysql_fetch_array($met_rmf_paid)) {
    $_metrmfnya_paid += ROUND($met_rmf_paid_['rmf']);
}

$met_rmf_unpaid = mysql_query('SELECT * FROM fu_ajk_peserta WHERE id !="" AND status_bayar="0" AND status_aktif!="Batal" AND del is NULL '.$satu.' '.$duaa.' '.$tiga.' '.$empt.' '.$lima.' '.$enam.' '.$tujuh.'');
while ($met_rmf_unpaid_ = mysql_fetch_array($met_rmf_unpaid)) {
    $cek_rate_RMF = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi_rmf WHERE id_cost="'.$met_rmf_unpaid_['id_cost'].'" AND id_polis="'.$met_rmf_unpaid_['id_polis'].'" AND tenor="'.$met_rmf_unpaid_['kredit_tenor'].'"'));
    $mametRMF = $met_rmf_unpaid_['kredit_jumlah'] * $cek_rate_RMF['rate'] / 1000;
    $_metrmfnya_unpaid += ROUND($mametRMF);
}
    $metRMFtotal = $_metrmfnya_paid + $_metrmfnya_unpaid;
*/

    $worksheet1->setMerge(4, 0, 4, 1);	$worksheet1->writeString(4, 0, "Nama Perusahaan", $fjudul, 1);
    $worksheet1->setMerge(4, 2, 4, 10);	$worksheet1->writeString(4, 2, ": " .$_namaperusahaan['name'], $fjudul, 1);

    $worksheet1->setMerge(4, 12, 4, 13);	$worksheet1->writeString(4, 12, "Tanggal Akad", $fjudul1, 1);
    $worksheet1->setMerge(4, 14, 4, 15);	$worksheet1->writeString(4, 14, ": " ._convertDate($_REQUEST['tgl1']).' - '._convertDate($_REQUEST['tgl2']), $fjudul1, 1);

    $worksheet1->setMerge(5, 0, 5, 1);	$worksheet1->writeString(5, 0, "Nama Produk", $fjudul1, 1);
    $worksheet1->setMerge(5, 2, 5, 10);	$worksheet1->writeString(5, 2, ": " .$_namaproduknya_, $fjudul1, 1);

    $worksheet1->setMerge(5, 12, 5, 13);	$worksheet1->writeString(5, 12, "Regional", $fjudul1, 1);
    $worksheet1->setMerge(5, 14, 5, 15);	$worksheet1->writeString(5, 14, ": " .$regionalnya, $fjudul1, 1);

    $worksheet1->setMerge(6, 0, 6, 1);	$worksheet1->writeString(6, 0, "Status Peserta", $fjudul1, 1);
    $worksheet1->setMerge(6, 2, 6, 10);	$worksheet1->writeString(6, 2, ": " .$_statusbayar, $fjudul1, 1);

    $worksheet1->setMerge(6, 12, 6, 13);	$worksheet1->writeString(6, 12, "Cabang", $fjudul1, 1);
    $worksheet1->setMerge(6, 14, 6, 15);	$worksheet1->writeString(6, 14, ": " .$cabangnya, $fjudul1, 1);

    $worksheet1->setMerge(7, 0, 7, 1);	$worksheet1->writeString(7, 0, "Premi RMF", $fjudul, 1);
    $worksheet1->setMerge(7, 2, 7, 10);	$worksheet1->writeString(7, 2, ": " .ROUND($met_cekdata_paid_rmf), $fjudul, 1);

    $worksheet1->setMerge(8, 0, 8, 1);	$worksheet1->writeString(8, 0, "Premi dibayar", $fjudul1, 1);
    $worksheet1->setMerge(8, 2, 8, 10);	$worksheet1->writeString(8, 2, ": " .ROUND($met_cekdata_paid), $fjudul1, 1);

    $worksheet1->setMerge(9, 0, 9, 1);	$worksheet1->writeString(9, 0, "Premi belum dibayar", $fjudul1, 1);
    $worksheet1->setMerge(9, 2, 9, 10);	$worksheet1->writeString(9, 2, ": " .ROUND($met_cekdata_unpaid), $fjudul1, 1);

    $worksheet1->setMerge(10, 0, 10, 1);	$worksheet1->writeString(10, 0, "Total", $fjudul, 1);
    $worksheet1->setMerge(10, 2, 10, 10);$worksheet1->writeString(10, 2, ": " .ROUND($metRMFtotal), $fjudul, 1);

    $worksheet1->setRow(12, 13);
    $worksheet1->setColumn(12, 0, 5);	$worksheet1->writeString(12, 0, "NO", $format);
    $worksheet1->setColumn(12, 1, 15);	$worksheet1->writeString(12, 1, "NOMOR DN", $format);
    $worksheet1->setColumn(12, 2, 15);	$worksheet1->writeString(12, 2, "TGL DN", $format);
    $worksheet1->setColumn(12, 3, 15);	$worksheet1->writeString(12, 3, "ID PESERTA", $format);
    $worksheet1->setColumn(12, 4, 15);	$worksheet1->writeString(12, 4, "NAMA DEBITUR", $format);
    $worksheet1->setColumn(12, 5, 10);	$worksheet1->writeString(12, 5, "CABANG", $format);
    $worksheet1->setColumn(12, 6, 10);	$worksheet1->writeString(12, 6, "TGL LAHIR", $format);
    $worksheet1->setColumn(12, 7, 5);	$worksheet1->writeString(12, 7, "USIA", $format);
    $worksheet1->setColumn(12, 8, 10);	$worksheet1->writeString(12, 8, "PLAFOND", $format);
    $worksheet1->setColumn(12, 9, 5);	$worksheet1->writeString(12, 9, "JK.W", $format);
    $worksheet1->setColumn(12, 10, 15);	$worksheet1->writeString(12, 10, "MULAI ASURANSI", $format);
    $worksheet1->setColumn(12, 11, 15);	$worksheet1->writeString(12, 11, "AKHIR ASURANSI", $format);
    $worksheet1->setColumn(12, 12, 15);	$worksheet1->writeString(12, 12, "STATUS", $format);
    $worksheet1->setColumn(12, 13, 15);	$worksheet1->writeString(12, 13, "TOTAL PREMI", $format);
    $worksheet1->setColumn(12, 14, 15);	$worksheet1->writeString(12, 14, "RMF (paid)", $format);
    $worksheet1->setColumn(12, 15, 15);	$worksheet1->writeString(12, 15, "RMF (unpaid)", $format);


$baris = 13;
$er_data = mysql_query('SELECT * FROM fu_ajk_peserta WHERE id !="" AND status_aktif!="Batal" '.$satu.' '.$duaa.' '.$tiga.' '.$empt.' '.$lima.' '.$enam.' '.$tujuh.' AND del IS NULL ORDER BY kredit_tgl ASC');
while ($mamet = mysql_fetch_array($er_data)) {
    $cekdatadn = mysql_fetch_array(mysql_query('SELECT id, dn_kode, tgl_createdn FROM fu_ajk_dn WHERE id="'.$mamet['id_dn'].'" AND id_cost="'.$mamet['id_cost'].'"'));
    $cekdataret = mysql_fetch_array(mysql_query('SELECT id_cost, id_polis, tenor, rate FROM fu_ajk_ratepremi WHERE id !="" '.$satu.' '.$dua.' AND tenor="'.$mamet['kredit_tenor'].'"'));

    /*NILAI RMF DIRUBAH MENJADI HANYA YG PAID YG MUNUCL NILAI RMF 141101
    $met_rmf = mysql_fetch_array(mysql_query('SELECT id, id_cost, rmf FROM fu_ajk_polis WHERE id_cost="'.$mamet['id_cost'].'" AND id="'.$mamet['id_polis'].'"'));		//NILAI RMF
    $er_rmf = $mamet['totalpremi'] * $met_rmf['rmf']/100;
    NILAI RMF DIRUBAH MENJADI HANYA YG PAID YG MUNUCL NILAI RMF 141101*/

    if ($mamet['status_bayar']=="1") {
        $metrmfnya_paid = ROUND($mamet['rmf']);
        $metrmfnya_unpaid = '';
        $met_bayar = "PAID";
    } else {
        $metrmfnya_paid ='';
        $cek_rate_RMF = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi_rmf WHERE id_cost="'.$mamet['id_cost'].'" AND id_polis="'.$mamet['id_polis'].'" AND tenor="'.$mamet['kredit_tenor'].'"'));
        //$metrmfnya_unpaid = ROUND($mamet['kredit_jumlah']) * $cek_rate_RMF['rate'] / 1000;
        $met_bayar = "UNPAID";
    }
    $worksheet1->writeString($baris, 0, ++$no);
    $worksheet1->writeString($baris, 1, $cekdatadn['dn_kode'], $fjudul3);
    $worksheet1->writeString($baris, 2, $cekdatadn['tgl_createdn'], $fjudul3);
    $worksheet1->writeString($baris, 3, $mamet['id_peserta'], $fjudul3);
    $worksheet1->writeString($baris, 4, $mamet['nama']);
    $worksheet1->writeString($baris, 5, $mamet['cabang'], $fjudul3);
    $worksheet1->writeString($baris, 6, _convertDate($mamet['tgl_lahir']), $fjudul3);
    $worksheet1->writeString($baris, 7, $mamet['usia'], $fjudul3);
    $worksheet1->writeString($baris, 8, $mamet['kredit_jumlah'], $fjudul2);
    $worksheet1->writeString($baris, 9, $mamet['kredit_tenor'], $fjudul3);
    $worksheet1->writeString($baris, 10, _convertDate($mamet['kredit_tgl']), $fjudul3);
    $worksheet1->writeString($baris, 11, _convertDate($mamet['kredit_akhir']), $fjudul3);
    $worksheet1->writeString($baris, 12, $met_bayar, $fjudul3);
    $worksheet1->writeString($baris, 13, ROUND($mamet['totalpremi']), $fjudul2);
    $worksheet1->writeString($baris, 14, ROUND($metrmfnya_paid), $fjudul2);
    $worksheet1->writeString($baris, 15, ROUND($metrmfnya_unpaid), $fjudul2);
    $baris++;
}
    $workbook->close();
    ;
    break;

case "eL_dok":
function HeaderingExcel($filename)
{
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$filename");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
    header("Pragma: public");
}
    $met_c = mysql_fetch_array(mysql_query('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$_REQUEST['cat'].'"'));
    HeaderingExcel('Laporan_Dokter.xls');
    $workbook = new Workbook("");
    $worksheet1 =& $workbook->add_worksheet('Laporan Dokter');

    $worksheet1->setMerge(0, 0, 0, 10);	$worksheet1->writeString(0, 0, "LAPORAN DOKTER - SPK REGULER", $fjudul, 1);
    $worksheet1->setMerge(1, 0, 1, 10);	$worksheet1->writeString(1, 0, strtoupper($met_c['name']), $fjudul);
    $worksheet1->setMerge(2, 0, 2, 10);	$worksheet1->writeString(2, 0, 'Tanggal Pemeriksaan '._convertDate($_REQUEST['tgl1']) .' s/d '. _convertDate($_REQUEST['tgl2']), $fjudul);

    $format =& $workbook->add_format();
    $format->setAlign('vcenter');	$format->setAlign('center');	$format->setColor('white');	$format->setBold();	$format->setPattern();	$format->setFgColor('green');
    $fjudul =& $workbook->add_format();	$fjudul->setAlign('vcenter');	$fjudul->setAlign('center');	$fjudul->setBold();

    $worksheet1->setRow(3, 15);
    $worksheet1->setColumn(3, 0, 5);	$worksheet1->writeString(3, 0, "NO", $format);
    $worksheet1->setColumn(3, 1, 15);	$worksheet1->writeString(3, 1, "NAMA DOKTER", $format);
    $worksheet1->setColumn(3, 2, 15);	$worksheet1->writeString(3, 2, "NAMA DEBITUR", $format);
    $worksheet1->setColumn(3, 3, 15);	$worksheet1->writeString(3, 3, "NO.SPK", $format);
    $worksheet1->setColumn(3, 4, 15);	$worksheet1->writeString(3, 4, "TANGGAL PERIKSA", $format);
    $worksheet1->setColumn(3, 5, 15);	$worksheet1->writeString(3, 5, "USIA", $format);
    $worksheet1->setColumn(3, 6, 15);	$worksheet1->writeString(3, 6, "PREMI", $format);
    $worksheet1->setColumn(3, 7, 15);	$worksheet1->writeString(3, 7, "EM(%)", $format);
    $worksheet1->setColumn(3, 8, 15);	$worksheet1->writeString(3, 8, "NILAI EM", $format);
    $worksheet1->setColumn(3, 9, 15);	$worksheet1->writeString(3, 9, "TOTAL PREMI", $format);
    $worksheet1->setColumn(3, 10, 15);	$worksheet1->writeString(3, 10, "STATUS", $format);
    $worksheet1->setColumn(3, 11, 15);	$worksheet1->writeString(3, 11, "CABANG", $format);

if ($_REQUEST['cat']) {
    $satu = 'AND fu_ajk_spak.id_cost = "'.$_REQUEST['cat'].'"';
}
if ($_REQUEST['nm_dok']) {
    $tiga = 'AND fu_ajk_spak_form.dokter_pemeriksa = "'.$_REQUEST['nm_dok'].'"';
}
if ($_REQUEST['tgl1']) {
    $duaa = 'AND DATE_FORMAT(fu_ajk_spak.input_date, "%Y-%m-%d") BETWEEN "'.$_REQUEST['tgl1'].'" AND "'.$_REQUEST['tgl2'].'" ';
}

$baris = 4;
//$er_data = mysql_query('SELECT * FROM fu_ajk_spak_form WHERE id !="" AND del is NULL '.$satu.' '.$tiga.' '.$duaa.' ORDER BY dokter_pemeriksa ASC, cabang ASC, tgl_periksa ASC');
$er_data = mysql_query('SELECT
fu_ajk_spak.id,
fu_ajk_spak.id_cost,
fu_ajk_spak.id_polis,
fu_ajk_spak.spak,
fu_ajk_spak.input_by,
fu_ajk_spak.input_date,
fu_ajk_spak.`status`,
fu_ajk_spak.ext_premi,
fu_ajk_spak_form.nama,
fu_ajk_spak_form.dob,
fu_ajk_spak_form.x_usia,
fu_ajk_spak_form.x_premi,
IF(fu_ajk_spak.ext_premi!="", ((fu_ajk_spak_form.x_premi * fu_ajk_spak.ext_premi) / 100), "") AS nilai_EM,
IF(fu_ajk_spak.ext_premi!="", ((fu_ajk_spak_form.x_premi * fu_ajk_spak.ext_premi) / 100) + fu_ajk_spak_form.x_premi, fu_ajk_spak_form.x_premi) AS TotalPremi,
fu_ajk_spak_form.tgl_periksa,
fu_ajk_spak_form.dokter_pemeriksa,
fu_ajk_spak_form.cabang
FROM fu_ajk_spak
INNER JOIN fu_ajk_spak_form ON fu_ajk_spak.id = fu_ajk_spak_form.idspk AND fu_ajk_spak.id_cost = fu_ajk_spak_form.idcost
WHERE fu_ajk_spak.id !="" AND fu_ajk_spak.del is NULL AND fu_ajk_spak_form.del is NULL '.$satu.' '.$tiga.' '.$duaa.' AND fu_ajk_spak.`status` != "Pending" AND fu_ajk_spak.`status` != "Proses"
ORDER BY fu_ajk_spak.input_date ASC, fu_ajk_spak_form.dokter_pemeriksa ASC, fu_ajk_spak_form.cabang ASC, fu_ajk_spak_form.tgl_periksa ASC');
while ($mamet = mysql_fetch_array($er_data)) {
    if (is_numeric($mamet['dokter_pemeriksa'])) {
        $metUserDok = mysql_fetch_array(mysql_query('SELECT * FROM user_mobile WHERE id="'.$mamet['dokter_pemeriksa'].'" '));
        $metUserDok_ = $metUserDok['namalengkap'];
    } else {
        $metUserDok_ = $mamet['dokter_pemeriksa'];
    }

    if (is_numeric($mamet['cabang'])) {
        $metUserCab = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_cabang WHERE id="'.$mamet['cabang'].'" '));
        $metUserCab_ = $metUserCab['name'];
    } else {
        $metUserCab_ = $mamet['cabang'];
    }

    $worksheet1->writeString($baris, 0, ++$no);
    $worksheet1->writeString($baris, 1, $metUserDok_);
    $worksheet1->writeString($baris, 2, $mamet['nama']);
    $worksheet1->writeString($baris, 3, $mamet['spak'], 'C');
    $worksheet1->writeString($baris, 4, _convertDate($mamet['tgl_periksa']), 'C');
    $worksheet1->writeNumber($baris, 5, $mamet['x_usia']);
    $worksheet1->writeNumber($baris, 6, $mamet['x_premi']);
    $worksheet1->writeNumber($baris, 7, $mamet['ext_premi']);
    $worksheet1->writeNumber($baris, 8, $mamet['nilai_EM']);
    $worksheet1->writeNumber($baris, 9, $mamet['TotalPremi']);
    $worksheet1->writeString($baris, 10, $mamet['status']);
    $worksheet1->writeString($baris, 11, $metUserCab_);
    $baris++;
}
    $workbook->close();
    ;
    break;

case "eL_refund":
function HeaderingExcel($filename)
{
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$filename");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
    header("Pragma: public");
}
    $met_c = mysql_fetch_array(mysql_query('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$_REQUEST['cat'].'"'));
    HeaderingExcel('Laporan_Refund.xls');
    $workbook = new Workbook("");
    $worksheet1 =& $workbook->add_worksheet('Laporan Refund');

    $worksheet1->setMerge(0, 0, 0, 19);	$worksheet1->writeString(0, 0, "LAPORAN DATA REFUND", $fjudul, 1);
    $worksheet1->setMerge(1, 0, 1, 19);	$worksheet1->writeString(1, 0, strtoupper($met_c['name']), $fjudul);
    if ($_REQUEST['tgl1']) {
        $worksheet1->setMerge(2, 0, 2, 19);
        $worksheet1->writeString(2, 0, 'Tanggal Refund '._convertDate($_REQUEST['tgl1']) .' s/d '. _convertDate($_REQUEST['tgl2']), $fjudul);
    } else {
        $worksheet1->setMerge(2, 0, 2, 19);
        $worksheet1->writeString(2, 0, 'Tanggal Credit Note '._convertDate($_REQUEST['tgl3']) .' s/d '. _convertDate($_REQUEST['tgl4']), $fjudul);
    }
    $format =& $workbook->add_format();
    $format->setAlign('vcenter');	$format->setAlign('center');	$format->setColor('white');	$format->setBold();	$format->setPattern();	$format->setFgColor('green');
    $fjudul =& $workbook->add_format();	$fjudul->setAlign('vcenter');	$fjudul->setAlign('center');	$fjudul->setBold();

    $worksheet1->setRow(4, 15);
    $worksheet1->setColumn(4, 0, 5);	$worksheet1->writeString(4, 0, "NO", $format);
    $worksheet1->setColumn(4, 1, 15);	$worksheet1->writeString(4, 1, "ASURANSI", $format);
    $worksheet1->setColumn(4, 2, 15);	$worksheet1->writeString(4, 2, "ID PESERTA", $format);
    $worksheet1->setColumn(4, 3, 15);	$worksheet1->writeString(4, 3, "NAMA DEBITUR", $format);
    $worksheet1->setColumn(4, 4, 15);	$worksheet1->writeString(4, 4, "REGIONAL", $format);
    $worksheet1->setColumn(4, 5, 15);	$worksheet1->writeString(4, 5, "CABANG", $format);
    $worksheet1->setColumn(4, 6, 10);	$worksheet1->writeString(4, 6, "TGL LAHIR", $format);
    $worksheet1->setColumn(4, 7, 5);	$worksheet1->writeString(4, 7, "USIA", $format);
    $worksheet1->setColumn(4, 8, 10);	$worksheet1->writeString(4, 8, "PLAFOND", $format);
    $worksheet1->setColumn(4, 9, 5);	$worksheet1->writeString(4, 9, "JK.W", $format);
    $worksheet1->setColumn(4, 10, 5);	$worksheet1->writeString(4, 10, "GRACE PERIOD", $format);
    $worksheet1->setColumn(4, 11, 15);	$worksheet1->writeString(4, 11, "TGL AKAD", $format);
    $worksheet1->setColumn(4, 12, 15);	$worksheet1->writeString(4, 12, "TGL AKHIR", $format);
    $worksheet1->setColumn(4, 13, 10);	$worksheet1->writeString(4, 13, "RATE", $format);
    $worksheet1->setColumn(4, 14, 10);	$worksheet1->writeString(4, 14, "PREMI", $format);
    $worksheet1->setColumn(4, 15, 10);	$worksheet1->writeString(4, 15, "EM", $format);
    $worksheet1->setColumn(4, 16, 10);	$worksheet1->writeString(4, 16, "TOTAL PREMI", $format);
    $worksheet1->setColumn(4, 17, 10);	$worksheet1->writeString(4, 17, "PREMI REFUND", $format);
    $worksheet1->setColumn(4, 18, 15);	$worksheet1->writeString(4, 18, "PRODUK", $format);
    $worksheet1->setColumn(4, 19, 10);	$worksheet1->writeString(4, 19, "TGL REFUND", $format);
    $worksheet1->setColumn(4, 20, 10);	$worksheet1->writeString(4, 20, "TGL CETAK CN", $format); //tambahan hansen
    $worksheet1->setColumn(4, 21, 15);	$worksheet1->writeString(4, 21, "DEBIT NOTE", $format);
    $worksheet1->setColumn(4, 22, 15);	$worksheet1->writeString(4, 22, "CREDIT NOTE", $format);
    $worksheet1->setColumn(4, 23, 15);	$worksheet1->writeString(4, 23, "KETERANGAN", $format);

if ($_REQUEST['cat']) {
    $satu = 'AND id_cost = "'.$_REQUEST['cat'].'"';
}
if ($_REQUEST['subcat']) {
    $duaa = 'AND id_nopol = "'.$_REQUEST['subcat'].'"';
}
if ($_REQUEST['tgl1']) {
    $tiga = 'AND tgl_claim BETWEEN "'.$_REQUEST['tgl1'].'" AND "'.$_REQUEST['tgl2'].'" ';
}
if ($_REQUEST['tgl3']) {
    $empt = 'AND tgl_createcn BETWEEN "'.$_REQUEST['tgl3'].'" AND "'.$_REQUEST['tgl4'].'" ';
}
$baris = 5;
$er_data = mysql_query('SELECT * FROM fu_ajk_cn WHERE id_cn !="" AND type_claim="Refund" AND del is NULL '.$satu.' '.$duaa.' '.$tiga.' '.$empt.' ORDER BY tgl_createcn ASC, id_cabang ASC');
while ($mamet = mysql_fetch_array($er_data)) {
    $met_peserta = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_peserta WHERE id_cost="'.$mamet['id_cost'].'" AND id_polis="'.$mamet['id_nopol'].'" AND id_peserta="'.$mamet['id_peserta'].'"'));
    $met_dn = mysql_fetch_array(mysql_query('SELECT id, dn_kode, id_as FROM fu_ajk_dn WHERE id="'.$mamet['id_dn'].'"'));
    $met_dn_Asuransi = mysql_fetch_array(mysql_query('SELECT id, name FROM fu_ajk_asuransi WHERE id="'.$met_dn['id_as'].'"'));

    $met_produk = mysql_fetch_array(mysql_query('SELECT id, nmproduk, singlerate FROM fu_ajk_polis WHERE id="'.$mamet['id_nopol'].'"'));

    if ($met_produk['singlerate']=="Y") {
        $ratetenornya = $met_peserta['kredit_tenor'] / 12;
    } else {
        $ratetenornya = $met_peserta['kredit_tenor'];
    }
    $cekdataret = mysql_fetch_array(mysql_query('SELECT id_cost, id_polis, tenor, rate FROM fu_ajk_ratepremi WHERE id !="" AND id_cost="'.$mamet['id_cost'].'" AND id_polis="'.$mamet['id_nopol'].'" AND tenor="'.$ratetenornya.'" AND status="Baru" AND del IS NULL'));

    if ($mamet['type_refund']=="Lunas") {
        $ketRefund = "Lunas Dipercepat";
    } else {
        $ketRefund = $mamet['type_refund'];
    }

    $worksheet1->writeNumber($baris, 0, ++$no);
    $worksheet1->writeString($baris, 1, $met_dn_Asuransi['name']);
    $worksheet1->writeString($baris, 2, $mamet['id_peserta']);
    $worksheet1->writeString($baris, 3, $met_peserta['nama']);
    $worksheet1->writeString($baris, 4, $mamet['id_regional']);
    $worksheet1->writeString($baris, 5, $mamet['id_cabang']);
    $worksheet1->writeString($baris, 6, _convertDate($met_peserta['tgl_lahir']));
    $worksheet1->writeNumber($baris, 7, $met_peserta['usia'], "C");
    $worksheet1->writeNumber($baris, 8, $met_peserta['kredit_jumlah']);
    $worksheet1->writeNumber($baris, 9, $met_peserta['kredit_tenor']);
    $worksheet1->writeNumber($baris, 10, $met_peserta['mppbln']);
    $worksheet1->writeString($baris, 11, _convertDate($met_peserta['kredit_tgl']));
    $worksheet1->writeString($baris, 12, _convertDate($met_peserta['kredit_akhir']));
    $worksheet1->writeString($baris, 13, $cekdataret['rate']);
    $worksheet1->writeNumber($baris, 14, $met_peserta['premi']);
    $worksheet1->writeNumber($baris, 15, $met_peserta['ext_premi']);
    $worksheet1->writeNumber($baris, 16, $met_peserta['totalpremi']);
    $worksheet1->writeNumber($baris, 17, $mamet['total_claim']);
    $worksheet1->writeString($baris, 18, $met_produk['nmproduk']);
    $worksheet1->writeString($baris, 19, _convertDate($mamet['tgl_claim']));
    $worksheet1->writeString($baris, 20, _convertDate($mamet['tgl_createcn'])); //tambahan hansen
    $worksheet1->writeString($baris, 21, $met_dn['dn_kode']);
    $worksheet1->writeString($baris, 22, $mamet['id_cn']);
    $worksheet1->writeString($baris, 23, $ketRefund);
    $baris++;
}
    $workbook->close();
    ;
    break;

case "eL_klaim":
function HeaderingExcel($filename)
{
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$filename");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
    header("Pragma: public");
}
    $met_c = mysql_fetch_array(mysql_query('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$_REQUEST['cat'].'"'));
    HeaderingExcel('Laporan_Klaim.xls');
    $workbook = new Workbook("");
    $worksheet1 =& $workbook->add_worksheet('Laporan Klaim');

    $worksheet1->setMerge(0, 0, 0, 19);	$worksheet1->writeString(0, 0, "ALL KLAIM AJK ".  strtoupper($met_c['name']). ' BELUM CLOSE LIFE', $fjudul, 1);
    $worksheet1->setMerge(1, 0, 1, 19);	$worksheet1->writeString(1, 0, 'PER TANGGAL CREDITNOTE '._convertDate($_REQUEST['tgl3']) .' s/d '. _convertDate($_REQUEST['tgl4']), $fjudul);
    if ($_REQUEST['kat']=="") {
        $worksheet1->setMerge(2, 0, 2, 19);
        $worksheet1->writeString(2, 0, 'PENYEBAB MENINGGAL : 9 JENIS PENYAKIT ', $fjudul);
    } else {
        $metPenyakit = mysql_fetch_array(mysql_query('SELECT id, namapenyakit FROM fu_ajk_namapenyakit WHERE id="'.$_REQUEST['kat'].'"'));
        $worksheet1->setMerge(2, 0, 2, 19);
        $worksheet1->writeString(2, 0, 'PENYEBAB MENINGGAL : '.strtoupper($metPenyakit['namapenyakit']), $fjudul);
    }

    $format =& $workbook->add_format();
    $format->setAlign('vcenter');	$format->setAlign('center');	$format->setColor('white');	$format->setBold();	$format->setPattern();	$format->setFgColor('green');
    $fjudul =& $workbook->add_format();	$fjudul->setAlign('vcenter');	$fjudul->setAlign('center');	$fjudul->setBold();

    $worksheet1->setRow(4, 15);
    $worksheet1->setColumn(4, 0, 5);	$worksheet1->writeString(4, 0, "NO", $format);
    $worksheet1->setColumn(4, 1, 15);	$worksheet1->writeString(4, 1, "Cabang", $format);
    $worksheet1->setColumn(4, 2, 15);	$worksheet1->writeString(4, 2, "Cover Asuransi", $format);
    $worksheet1->setColumn(4, 3, 15);	$worksheet1->writeString(4, 3, "ID Peserta", $format);
    $worksheet1->setColumn(4, 4, 15);	$worksheet1->writeString(4, 4, "Nama Debitur", $format);
    $worksheet1->setColumn(4, 5, 15);	$worksheet1->writeString(4, 5, "Tgl Lahir", $format);
    $worksheet1->setColumn(4, 6, 15);	$worksheet1->writeString(4, 6, "Usia", $format);
    $worksheet1->setColumn(4, 7, 15);	$worksheet1->writeString(4, 7, "Jml Kredit", $format);
    $worksheet1->setColumn(4, 8, 15);	$worksheet1->writeString(4, 8, "Tuntutan Klaim", $format);
    $worksheet1->setColumn(4, 9, 15);	$worksheet1->writeString(4, 9, "Tgl Akad", $format);
    $worksheet1->setColumn(4, 10, 15);	$worksheet1->writeString(4, 10, "J.Wkt", $format);
    $worksheet1->setColumn(4, 11, 15);	$worksheet1->writeString(4, 11, "DOL", $format);
    $worksheet1->setColumn(4, 12, 15);	$worksheet1->writeString(4, 12, "Akad s/d DOL (hari)", $format);
    $worksheet1->setColumn(4, 13, 15);	$worksheet1->writeString(4, 13, "Tgl. Lapor Asuransi", $format);
    $worksheet1->setColumn(4, 14, 15);	$worksheet1->writeString(4, 14, "Kelengkapan Dok Klaim", $format);
    $worksheet1->setColumn(4, 15, 15);	$worksheet1->writeString(4, 15, "Tgl Status Lengkap", $format);
    $worksheet1->setColumn(4, 16, 15);	$worksheet1->writeString(4, 16, "Reminder Ke", $format);
    $worksheet1->setColumn(4, 17, 15);	$worksheet1->writeString(4, 17, "Tgl Reminder", $format);
    $worksheet1->setColumn(4, 18, 15);	$worksheet1->writeString(4, 18, "Dokumen", $format);
    $worksheet1->setColumn(4, 19, 15);	$worksheet1->writeString(4, 19, "Status Klaim", $format);
    $worksheet1->setColumn(4, 20, 15);	$worksheet1->writeString(4, 20, "Total Bayar dari Asuransi", $format);
    $worksheet1->setColumn(4, 21, 15);	$worksheet1->writeString(4, 21, "Tgl Bayar dari Asuransi", $format);
    $worksheet1->setColumn(4, 22, 15);	$worksheet1->writeString(4, 22, "Total Bayar ke Bank", $format);
    $worksheet1->setColumn(4, 23, 15);	$worksheet1->writeString(4, 23, "Tgl Bayar ke Bank", $format);
    $worksheet1->setColumn(4, 24, 15);	$worksheet1->writeString(4, 24, "No SPK", $format);
    $worksheet1->setColumn(4, 25, 15);	$worksheet1->writeString(4, 25, "Keterangan Dokter", $format);
    $worksheet1->setColumn(4, 26, 15);	$worksheet1->writeString(4, 26, "Keterangan", $format);
    $worksheet1->setColumn(4, 27, 15);	$worksheet1->writeString(4, 27, "Produk", $format);
    $worksheet1->setColumn(4, 28, 15);	$worksheet1->writeString(4, 28, "Sebab Meninggal", $format);
    $worksheet1->setColumn(4, 29, 15);	$worksheet1->writeString(4, 29, "Bln Dol", $format);
    $worksheet1->setColumn(4, 30, 15);	$worksheet1->writeString(4, 30, "Thn Dol", $format);
    $worksheet1->setColumn(4, 31, 15);	$worksheet1->writeString(4, 31, "Thn Akad", $format);
    $worksheet1->setColumn(4, 32, 15);	$worksheet1->writeString(4, 32, "KOL", $format);
    if ($_REQUEST['cat']) {
        $satu = 'AND fu_ajk_cn.id_cost = "'.$_REQUEST['cat'].'"';
    }
    if ($_REQUEST['subcat']) {
        $duaa = 'AND fu_ajk_cn.id_nopol = "'.$_REQUEST['subcat'].'"';
    }
    if ($_REQUEST['tgl1']) {
        $tiga = 'AND fu_ajk_cn.tgl_claim BETWEEN "'.$_REQUEST['tgl1'].'" AND "'.$_REQUEST['tgl2'].'" ';
    }
    if ($_REQUEST['tgl3']) {
        $empt = 'AND DATE_FORMAT(fu_ajk_cn.approve_date,"%Y-%m-%d") BETWEEN "'.$_REQUEST['tgl3'].'" AND "'.$_REQUEST['tgl4'].'" ';
    }
    if ($_REQUEST['kat']) {
        $lima = 'AND fu_ajk_cn.nmpenyakit ="'.$_REQUEST['kat'].'"';
    }
    $baris = 5;
    $er_data = mysql_query('SELECT
/*fu_ajk_costumer.`name`,*/
fu_ajk_polis.nmproduk,
fu_ajk_cn.id,
fu_ajk_cn.id_cabang,
fu_ajk_cn.id_cost,
fu_ajk_cn.id_cn,
fu_ajk_cn.id_dn,
fu_ajk_dn.dn_kode,
fu_ajk_peserta.id_peserta,
fu_ajk_peserta.nama,
fu_ajk_peserta.tgl_lahir,
fu_ajk_peserta.usia,
fu_ajk_peserta.totalpremi,
fu_ajk_cn.total_claim,
fu_ajk_peserta.kredit_tgl,
fu_ajk_peserta.kredit_tenor,
fu_ajk_peserta.kredit_akhir,
fu_ajk_peserta.kredit_jumlah,
DATEDIFF(fu_ajk_cn.tgl_claim,fu_ajk_peserta.kredit_tgl) AS jHari,
fu_ajk_cn.tgl_claim,
fu_ajk_cn.keterangan,
fu_ajk_cn.confirm_claim,
fu_ajk_cn.tgl_document,
fu_ajk_cn.tgl_document_lengkap,
fu_ajk_cn.tgl_byr_claim,
fu_ajk_cn.nmpenyakit,
fu_ajk_namapenyakit.namapenyakit,
fu_ajk_asuransi.`name` AS nmAsuransi,
fu_ajk_klaim.sebab_meninggal AS sebabmeninggal,
fu_ajk_klaim.tempat_meninggal AS tempatmeninggal,
fu_ajk_klaim.ket_dokter AS ketDokter,
fu_ajk_klaim.tgl_surat_reminder1,
fu_ajk_klaim.tgl_surat_reminder2,
fu_ajk_klaim.tgl_surat_reminder3,
fu_ajk_klaim_status.status_klaim,
fu_ajk_peserta.spaj,
fu_ajk_cn.tuntutan_klaim,
fu_ajk_cn.tgl_bayar_asuransi,
fu_ajk_cn.total_bayar_asuransi

FROM
	fu_ajk_cn
	INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
	INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
	INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
	INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
	LEFT join fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
	LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id=fu_ajk_klaim.id_klaim_status
/*
	fu_ajk_cn
	INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
	INNER JOIN fu_ajk_costumer ON fu_ajk_cn.id_cost = fu_ajk_costumer.id
	INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
	INNER JOIN fu_ajk_dn ON fu_ajk_cn.id = fu_ajk_dn.id
	INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
	INNER JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
	LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim.id_klaim_status = fu_ajk_klaim_status.id
	LEFT JOIN fu_ajk_grupproduk ON fu_ajk_grupproduk.id=fu_ajk_peserta.nama_mitra
	*/


	LEFT JOIN fu_ajk_namapenyakit ON fu_ajk_namapenyakit.id=fu_ajk_klaim.sebab_meninggal
WHERE fu_ajk_cn.type_claim = "Death" AND confirm_claim !="Pending" and fu_ajk_cn.del IS NULL
	'.$satu.' '.$duaa.' '.$tiga.' '.$empt.' '.$lima.'');
while ($mamet = mysql_fetch_array($er_data)) {
    $cekDok = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_klaim_doc WHERE id_klaim="'.$mamet['id'].'"'));
    //if ($cekDok['nama_dokumen'] == NULL) {	$statusDokumen = 'Tidak lengkap';	}else{	$statusDokumen = 'Lengkap';	} 15102015
    $cekDok = mysql_num_rows(mysql_query('SELECT * FROM fu_ajk_klaim_doc WHERE id_klaim="'.$met_['id'].'"'));
    $jumDok = mysql_num_rows(mysql_query('SELECT * FROM fu_ajk_dokumenklaim_bank WHERE id_bank="'.$met_['id_cost'].'"'));

    $cekTglKlaim = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_klaim WHERE id_cn="'.$mamet['id'].'"'));
    if ($cekTglKlaim['tgl_document_lengkap'] != "" and $cekTglKlaim['tgl_document_lengkap'] > "0000-00-00") {
        $statusDokumen = 'Lengkap';
    } else {
        $statusDokumen = 'Tidak lengkap';
    }
    $tgl_klaim = explode("-", $mamet['tgl_claim']);
    $kredit_tgl = explode("-", $mamet['kredit_tgl']);

    if ($mamet['jHari'] <= 90) {
        $kol = 2;
    } elseif ($mamet['jHari'] >= 91 and $mamet['jHari'] <= 120) {
        $kol = 3;
    } elseif ($mamet['jHari'] >= 121 and $mamet['jHari'] <= 180) {
        $kol = 4;
    } elseif ($mamet['jHari'] > 180) {
        $kol = 5;
    }

    if ($mamet['tgl_surat_reminder3']!='0000-00-00') {
        $tgl_reminder = $mamet['tgl_surat_reminder3'];
        $reminder_ke = "3";
    } elseif ($mamet['tgl_surat_reminder2']!='0000-00-00') {
        $tgl_reminder = $mamet['tgl_surat_reminder2'];
        $reminder_ke = "2";
    } elseif ($mamet['tgl_surat_reminder1']!='0000-00-00') {
        $tgl_reminder = $mamet['tgl_surat_reminder1'];
        $reminder_ke = "1";
    }

    $worksheet1->writeNumber($baris, 0, ++$no);
    $worksheet1->writeString($baris, 1, $mamet['id_cabang']);
    $worksheet1->writeString($baris, 2, $mamet['nmAsuransi']);
    $worksheet1->writeString($baris, 3, $mamet['id_peserta']);
    $worksheet1->writeString($baris, 4, $mamet['nama'], 'C');
    $worksheet1->writeString($baris, 5, _convertDate($mamet['tgl_lahir']));
    $worksheet1->writeNumber($baris, 6, $mamet['usia'], 'C');
    $worksheet1->writeNumber($baris, 7, $mamet['kredit_jumlah']);
    $worksheet1->writeNumber($baris, 8, $mamet['tuntutan_klaim']);
    $worksheet1->writeString($baris, 9, _convertDate($mamet['kredit_tgl']));
    $worksheet1->writeNumber($baris, 10, $mamet['kredit_tenor'], 'C');
    $worksheet1->writeString($baris, 11, _convertDate($mamet['tgl_claim']));
    $worksheet1->writeNumber($baris, 12, $mamet['jHari'], 'C');
    $worksheet1->writeString($baris, 13, '');
    $worksheet1->writeString($baris, 14, $jumDok .'-'. $cekDok);
    $worksheet1->writeString($baris, 15, _convertDate($cekTglKlaim['tgl_document_lengkap']));
    $worksheet1->writeString($baris, 16, $reminder_ke);
    $worksheet1->writeString($baris, 17, _convertDate($tgl_reminder));
    $worksheet1->writeString($baris, 18, $statusDokumen);
    $worksheet1->writeString($baris, 19, $mamet['status_klaim']);
    $worksheet1->writeNumber($baris, 20, $mamet['total_bayar_asuransi']);
    $worksheet1->writeString($baris, 21, _convertDate($mamet['tgl_bayar_asuransi']));
    $worksheet1->writeNumber($baris, 22, $mamet['total_claim']);
    $worksheet1->writeString($baris, 23, _convertDate($mamet['tgl_byr_claim']));
    $worksheet1->writeString($baris, 24, $mamet['spaj']);
    $worksheet1->writeString($baris, 25, $mamet['ketDokter']);
    $worksheet1->writeString($baris, 26, $mamet['keterangan']);
    $worksheet1->writeString($baris, 27, $mamet['nmproduk']);
    $worksheet1->writeString($baris, 28, strtoupper($mamet['namapenyakit']));
    $worksheet1->writeString($baris, 29, $tgl_klaim[1]);
    $worksheet1->writeString($baris, 30, $tgl_klaim[0]);
    $worksheet1->writeString($baris, 31, $kredit_tgl[0]);
    $worksheet1->writeString($baris, 32, $kol);
    $baris++;
}
    /* 150515
    $met_c = mysql_fetch_array(mysql_query('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$_REQUEST['cat'].'"'));
    HeaderingExcel('Laporan_Klaim.xls');
    $workbook = new Workbook("");
    $worksheet1 =& $workbook->add_worksheet('Laporan Klaim');

    $worksheet1->setMerge(0, 0, 0, 10);	$worksheet1->writeString(0, 0, "LAPORAN DATA KLAIM", $fjudul, 1);
    $worksheet1->setMerge(1, 0, 1, 10);	$worksheet1->writeString(1, 0, strtoupper($met_c['name']), $fjudul);
    $worksheet1->setMerge(2, 0, 2, 10);	$worksheet1->writeString(2, 0, 'Tanggal Credit Note '._convertDate($_REQUEST['tgl1']) .' s/d '. _convertDate($_REQUEST['tgl2']), $fjudul);

    $format =& $workbook->add_format();
    $format->setAlign('vcenter');	$format->setAlign('center');	$format->setColor('white');	$format->setBold();	$format->setPattern();	$format->setFgColor('green');
    $fjudul =& $workbook->add_format();	$fjudul->setAlign('vcenter');	$fjudul->setAlign('center');	$fjudul->setBold();

    $worksheet1->setRow(3, 15);
    $worksheet1->setColumn(3, 0, 5);	$worksheet1->writeString(3, 0, "NO", $format);
    $worksheet1->setColumn(3, 1, 15);	$worksheet1->writeString(3, 1, "PRODUK", $format);
    $worksheet1->setColumn(3, 2, 15);	$worksheet1->writeString(3, 2, "NO.PESERTA", $format);
    $worksheet1->setColumn(3, 3, 15);	$worksheet1->writeString(3, 3, "NAMA DEBITUR", $format);
    $worksheet1->setColumn(3, 4, 15);	$worksheet1->writeString(3, 4, "DEBIT NOTE", $format);
    $worksheet1->setColumn(3, 5, 15);	$worksheet1->writeString(3, 5, "CREDIT NOTE", $format);
    $worksheet1->setColumn(3, 6, 15);	$worksheet1->writeString(3, 6, "TGL CN", $format);
    $worksheet1->setColumn(3, 7, 15);	$worksheet1->writeString(3, 7, "TGL KLAIM", $format);
    $worksheet1->setColumn(3, 8, 15);	$worksheet1->writeString(3, 8, "PREMI", $format);
    $worksheet1->setColumn(3, 9, 15);	$worksheet1->writeString(3, 9, "NILAI KLAIM", $format);
    $worksheet1->setColumn(3, 10, 15);	$worksheet1->writeString(3, 10, "CABANG", $format);

if ($_REQUEST['id_cost'])		{	$satu = 'AND id_cost = "'.$_REQUEST['id_cost'].'"';	}
if ($_REQUEST['id_polis'])		{	$duaa = 'AND id_nopol = "'.$_REQUEST['id_polis'].'"';	}
if ($_REQUEST['tgl1'])			{	$tiga = 'AND tgl_claim BETWEEN "'.$_REQUEST['tgl1'].'" AND "'.$_REQUEST['tgl2'].'" ';	}
if ($_REQUEST['tgl3'])			{	$empt = 'AND tgl_createcn BETWEEN "'.$_REQUEST['tgl3'].'" AND "'.$_REQUEST['tgl4'].'" ';	}
    $baris = 4;
    $er_data = mysql_query('SELECT * FROM fu_ajk_cn WHERE id_cn !="" AND type_claim="Death" AND del is NULL '.$satu.' '.$duaa.' '.$tiga.' '.$empt.' ORDER BY tgl_createcn ASC, id_cabang ASC');
while ($mamet = mysql_fetch_array($er_data))
{
    $met_peserta = mysql_fetch_array(mysql_query('SELECT id, id_cost, id_polis, id_peserta, nama FROM fu_ajk_peserta WHERE id_cost="'.$mamet['id_cost'].'" AND id_polis="'.$mamet['id_nopol'].'" AND id_peserta="'.$mamet['id_peserta'].'"'));
    $met_dn = mysql_fetch_array(mysql_query('SELECT id, dn_kode FROM fu_ajk_dn WHERE id="'.$mamet['id_dn'].'"'));
    $met_produk = mysql_fetch_array(mysql_query('SELECT id, nmproduk FROM fu_ajk_polis WHERE id="'.$mamet['id_nopol'].'"'));

    $worksheet1->writeString($baris, 0, ++$no);
    $worksheet1->writeString($baris, 1, $met_produk['nmproduk']);
    $worksheet1->writeString($baris, 2, $mamet['id_peserta']);
    $worksheet1->writeString($baris, 3, $met_peserta['nama']);
    $worksheet1->writeString($baris, 4, $met_dn['dn_kode'], 'C');
    $worksheet1->writeString($baris, 5, $mamet['id_cn']);
    $worksheet1->writeString($baris, 6, _convertDate($mamet['tgl_createcn']));
    $worksheet1->writeString($baris, 7, _convertDate($mamet['tgl_claim']));
    $worksheet1->writeNumber($baris, 8, $mamet['premi']);
    $worksheet1->writeNumber($baris, 9, $mamet['total_claim']);
    $worksheet1->writeString($baris, 10, $mamet['id_cabang']);
    $baris++;
}
       */
    $workbook->close();
    ;
    break;

case "eL_CoveringLetter":
function HeaderingExcel($filename)
{
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$filename");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
    header("Pragma: public");
}
    $met_c = mysql_fetch_array(mysql_query('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$_REQUEST['id_cost'].'"'));

    HeaderingExcel('REKAP_COVERING_LETTER_'.$_REQUEST['cbg'].'.xls');
    $workbook = new Workbook("");
    $worksheet1 =& $workbook->add_worksheet('REKAP SPK');

    if ($_REQUEST['id_cost']) {
        $satu = 'AND fu_ajk_dn.id_cost = "'.$_REQUEST['id_cost'].'"';
    }
    if ($_REQUEST['nmProd']) {
        $dua = 'AND fu_ajk_dn.id_nopol = "'.$_REQUEST['nmProd'].'"';
    }
    if ($_REQUEST['cbg']) {
        $tiga = 'AND fu_ajk_dn.id_cabang = "'.$_REQUEST['cbg'].'"';
    }
    if ($_REQUEST['tgl1']) {
        $empat = 'AND fu_ajk_dn.tgl_createdn BETWEEN "'.$_REQUEST['tgl1'].'" AND "'.$_REQUEST['tgl2'].'" ';
    }

    $worksheet1->setMerge(0, 0, 0, 9);	$worksheet1->writeString(0, 0, "COVERING LETTER", $fjudul, 1);
    $worksheet1->setMerge(1, 0, 1, 9);	$worksheet1->writeString(1, 0, strtoupper($met_c['name']), $fjudul);
    $worksheet1->setMerge(2, 0, 2, 9);	$worksheet1->writeString(2, 0, 'CABANG '.strtoupper($_REQUEST['cbg']), $fjudul);

    $format =& $workbook->add_format();
    $format->setAlign('vcenter');	$format->setAlign('center');	$format->setColor('white');	$format->setBold();	$format->setPattern();	$format->setFgColor('green');
    $fjudul =& $workbook->add_format();	$fjudul->setAlign('vcenter');	$fjudul->setAlign('center');	$fjudul->setBold();


    $worksheet1->setRow(4, 15);
    $worksheet1->setColumn(4, 0, 5);	$worksheet1->writeString(4, 0, "NO", $format);
    $worksheet1->setColumn(4, 1, 15);	$worksheet1->writeString(4, 1, "NAMA PRODUK", $format);
    $worksheet1->setColumn(4, 2, 15);	$worksheet1->writeString(4, 2, "NOMOR DN", $format);
    $worksheet1->setColumn(4, 3, 15);	$worksheet1->writeString(4, 3, "TANGGAL DN", $format);
    $worksheet1->setColumn(4, 4, 15);	$worksheet1->writeString(4, 4, "NOMOR SPK", $format);
    $worksheet1->setColumn(4, 5, 15);	$worksheet1->writeString(4, 5, "ID PESERTA", $format);
    $worksheet1->setColumn(4, 6, 15);	$worksheet1->writeString(4, 6, "NAMA DEBITUR", $format);
    $worksheet1->setColumn(4, 7, 15);	$worksheet1->writeString(4, 7, "PREMI", $format);
    $worksheet1->setColumn(4, 8, 15);	$worksheet1->writeString(4, 8, "EM", $format);
    $worksheet1->setColumn(4, 9, 15);	$worksheet1->writeString(4, 9, "JUMLAH", $format);
    $baris = 5;
    $metCL = mysql_query('SELECT
	fu_ajk_polis.nmproduk,
	fu_ajk_dn.id_cabang,
	fu_ajk_dn.dn_kode,
	fu_ajk_dn.tgl_createdn,
	fu_ajk_peserta.spaj,
	fu_ajk_peserta.id_peserta,
	fu_ajk_peserta.nama,
	fu_ajk_peserta.premi,
	fu_ajk_peserta.ext_premi,
	fu_ajk_peserta.totalpremi
	FROM
	fu_ajk_dn
	INNER JOIN fu_ajk_peserta ON fu_ajk_dn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_dn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_dn.id = fu_ajk_peserta.id_dn
	LEFT JOIN fu_ajk_polis ON fu_ajk_peserta.id_polis = fu_ajk_polis.id
	WHERE fu_ajk_dn.id != "" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' AND fu_ajk_dn.del IS NULL AND fu_ajk_peserta.del IS NULL
	ORDER BY fu_ajk_dn.dn_kode ASC');
    while ($metCL_ = mysql_fetch_array($metCL)) {
        $worksheet1->writeString($baris, 0, ++$no);
        $worksheet1->writeString($baris, 1, $metCL_['nmproduk']);
        $worksheet1->writeString($baris, 2, $metCL_['dn_kode']);
        $worksheet1->writeString($baris, 3, _convertDate($metCL_['tgl_createdn']), 'C');
        $worksheet1->writeString($baris, 4, $metCL_['spaj'], 'C');
        $worksheet1->writeString($baris, 5, $metCL_['id_peserta']);
        $worksheet1->writeString($baris, 6, $metCL_['nama']);
        $worksheet1->writeNumber($baris, 7, $metCL_['premi']);
        $worksheet1->writeNumber($baris, 8, $metCL_['ext_premi']);
        $worksheet1->writeNumber($baris, 9, $metCL_['totalpremi']);
        $baris++;
        $tPlafond += ROUND($metCL_['premi']);
        $tTotalEM += ROUND($metCL_['ext_premi']);
        $tTotalPremi += ROUND($metCL_['totalpremi']);
    }
    $worksheet1->setMerge($baris, 0, $baris, 6);		$worksheet1->writeString($baris, 0, "TOTAL", $fjudul);
    $worksheet1->writeString($baris, 7, $tPlafond, $fjudul);
    $worksheet1->writeString($baris, 8, $tTotalEM, $fjudul);
    $worksheet1->writeString($baris, 9, $tTotalPremi, $fjudul);
    $workbook->close();
    ;
    break;

case "eL_PrintCoveringLetter":
echo '<style type="text/css">
   @media print {

    * {
        color: #000 !important;
        -webkit-text-shadow: none !important;
        <!--text-shadow: none !important;-->
        <!--font-family: "Times New Roman", Times, serif;-->
        <!--background: transparent !important;-->
        -webkit-box-shadow: none !important;
        box-shadow: none !important;
        <!--border: none!important;-->
        <!--font-weight: normal!Important;-->
    }

    header, nav, footer {
       overflow:visible;
    }

    .body {
        width: auto;
        border: 0;
        margin: 0 5%;
        padding: 0;
        float: none !important;
    }


    a, a:link, a:visited {

        &[href]:after {
            content: " (" attr(href) ") ";
            font-size: 90%;
        }

        &[href^="javascript:"],
        &[href^="#"] {
            &:after {
                content: "";
            }
        }
    }

    abbr[title]:after {
        content: " (" attr(title) ")";
    }

    pre,
    blockquote {
        border: 1px solid #999;
        page-break-inside: avoid;
    }

    thead {
        display: table-header-group;
    }

    tr,
    img {
        page-break-inside: avoid;
    }

    img {
        max-width: 100% !important;
    }

    @page {
        margin: 0.5cm;
    }

    p,
    h2,
    h3 {
        orphans: 3;
        widows: 3;
    }
}
table.serftifikatrelife {	font-family: verdana,arial,sans-serif;	font-size:12px;	color:#333333;	border-collapse: collapse;	}
table.serftifikatrelife td {	padding: 2px;	}
.serffontjudul{	font-size:16px;	font-weight: bold;	}
.sertifikatthjudul {	font-weight: bold;	font-size: 12px;	text-transform: uppercase;	background-color: #ffa800;	padding: 5px;	border: 1px solid #666666;	text-align: center;	}
.sertifikatthjudulbawah {	font-weight: bold;	font-size: 12px;	text-transform: uppercase;	background-color: #D8D8D8;	padding: 5px;	border: 1px solid #666666;	}
.sertifikatth {	font-size: 10.5px;	text-transform: uppercase;	color: #red;	padding: 5px;	border: 1px solid #666666;	}
</style>';
$met_c = mysql_fetch_array(mysql_query('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$_REQUEST['id_cost'].'"'));
    if ($_REQUEST['id_cost']) {
        $satu = 'AND fu_ajk_dn.id_cost = "'.$_REQUEST['id_cost'].'"';
    }
    if ($_REQUEST['nmProd']) {
        $dua = 'AND fu_ajk_dn.id_nopol = "'.$_REQUEST['nmProd'].'"';
    }
    if ($_REQUEST['cbg']) {
        $tiga = 'AND fu_ajk_peserta.cabang = "'.$_REQUEST['cbg'].'"';
    }
    if ($_REQUEST['tgl1']) {
        $empat = 'AND fu_ajk_dn.tgl_createdn BETWEEN "'.$_REQUEST['tgl1'].'" AND "'.$_REQUEST['tgl2'].'" ';
    }
    if ($_REQUEST['tgltrans1']) {
        $lima = 'AND fu_ajk_dn.tgltransaksi BETWEEN "'.$_REQUEST['tgltrans1'].'" AND "'.$_REQUEST['tgltrans2'].'" ';
    }

if ($_REQUEST['val']=="bank") {
    //	if ($_REQUEST['grupprod'])		{	$lima = 'AND fu_ajk_polis.grupproduk = "'.$_REQUEST['grupprod'].'"';	}
    $_typeCL = 'COVERING LETTER BANK';
    if ($_REQUEST['status']) {
        $status_ = explode("-", $_REQUEST['status']);
        if (!$status_[1]) {
            $enam = 'AND fu_ajk_peserta.status_aktif = "'.$status_[0].'"';
        } else {
            $enam = 'AND fu_ajk_peserta.status_aktif = "'.$status_[0].'"';
            $tujuh = 'AND fu_ajk_peserta.status_peserta = "'.$status_[1].'"';
        }
    }
} else {
    $_typeCL = 'COVERING LETTER';
}

if ($_REQUEST['grupprod']) {
    $delapan = 'AND fu_ajk_peserta.nama_mitra = "'.$_REQUEST['grupprod'].'"';
}
$metCL = mysql_query('SELECT
	fu_ajk_polis.nmproduk,
	fu_ajk_dn.id_regional,
	fu_ajk_dn.id_cabang,
	fu_ajk_dn.dn_kode,
	fu_ajk_dn.tgl_createdn,
	fu_ajk_peserta.spaj,
	fu_ajk_peserta.id_peserta,
	fu_ajk_peserta.nama,
	fu_ajk_peserta.nama_mitra AS mitra,
	fu_ajk_peserta.premi,
	fu_ajk_peserta.ext_premi,
	fu_ajk_peserta.regional,
	fu_ajk_peserta.cabang,
	fu_ajk_peserta.totalpremi
	FROM fu_ajk_dn
	INNER JOIN fu_ajk_peserta ON fu_ajk_dn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_dn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_dn.id = fu_ajk_peserta.id_dn
	LEFT JOIN fu_ajk_polis ON fu_ajk_peserta.id_polis = fu_ajk_polis.id
	LEFT JOIN fu_ajk_grupproduk ON fu_ajk_peserta.nama_mitra = fu_ajk_grupproduk.id
	WHERE fu_ajk_dn.id != "" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' '.$tujuh.' '.$delapan.' AND fu_ajk_dn.del IS NULL AND fu_ajk_peserta.del IS NULL
	ORDER BY fu_ajk_dn.dn_kode ASC');

echo '<table border="0" width="95%" cellpadding="1" cellspacing="3" class="serftifikatrelife" align="center">
	  <thead><tr><td align="center" colspan="9" class="serffontjudul">'.$_typeCL.'</td></tr>
	  <tr><td align="center" colspan="9" class="serffontjudul">'.$met_c['name'].'</td></tr>
	  <tr><td align="center" colspan="9" class="serffontjudul">CABANG '.strtoupper($_REQUEST['cbg']).'</td></tr>
	  <tr><td class="sertifikatthjudul" width="1%">No</td>
	  	  <td class="sertifikatthjudul" width="10%">Mitra</td>
	  	  <td class="sertifikatthjudul" width="10%">Nama Produk</td>
	  	  <td class="sertifikatthjudul" width="15%">Debitnote</td>
	  	  <td class="sertifikatthjudul" width="10%">Tanggal DN</td>
	  	  <td class="sertifikatthjudul" width="10%">NOMOR SPK</td>
	  	  <td class="sertifikatthjudul" width="10%">ID Peserta</td>
	  	  <td class="sertifikatthjudul">Nama</td>
	  	  <td class="sertifikatthjudul" width="10%">Premi</td>
	  	  <td class="sertifikatthjudul" width="10%">EM</td>
	  	  <td class="sertifikatthjudul" width="10%">Jumlah</td>
	  	  <td class="sertifikatthjudul" width="10%">regional</td>
	  	  <td class="sertifikatthjudul" width="10%">cabang</td>
	  </tr></thead>';

while ($metCL_ = mysql_fetch_array($metCL)) {
    if ($_REQUEST['grupprod']=="") {
        $status_mitranya = "BUKOPIN";
    } else {
        $searchmitra = mysql_fetch_array(mysql_query('SELECT id, nmproduk FROM fu_ajk_grupproduk WHERE id="'.$_REQUEST['grupprod'].'"'));
        $status_mitranya = $searchmitra['nmproduk'];
    }
    echo '<tr><td class="sertifikatth">'.++$no.'</td>
		  <td class="sertifikatth">'.$status_mitranya.'</td>
		  <td class="sertifikatth">'.$metCL_['nmproduk'].'</td>
		  <td class="sertifikatth" align="center">'.$metCL_['dn_kode'].'</td>
		  <td class="sertifikatth" align="center">'._convertDate($metCL_['tgl_createdn']).'</td>
		  <td class="sertifikatth" align="center">'.$metCL_['spaj'].'</td>
		  <td class="sertifikatth" align="center">'.$metCL_['id_peserta'].'</td>
		  <td class="sertifikatth">'.$metCL_['nama'].'</td>
		  <td class="sertifikatth" align="right">'.duit($metCL_['premi']).'</td>
		  <td class="sertifikatth" align="right">'.duit($metCL_['ext_premi']).'</td>
		  <td class="sertifikatth" align="right">'.duit($metCL_['totalpremi']).'</td>
		  <td class="sertifikatth">'.$metCL_['regional'].'</td>
		  <td class="sertifikatth">'.$metCL_['cabang'].'</td>
	  </tr>';
    $tPlafond += ROUND($metCL_['premi']);
    $tTotalEM += ROUND($metCL_['ext_premi']);
    $tTotalPremi += ROUND($metCL_['totalpremi']);
}
echo '<tr><td class="sertifikatthjudulbawah" colspan="8">TOTAL</td>
		  <td class="sertifikatthjudulbawah" align="right">'.duit($tPlafond).'</td>
		  <td class="sertifikatthjudulbawah" align="right">'.duit($tTotalEM).'</td>
		  <td class="sertifikatthjudulbawah" align="right">'.duit($tTotalPremi).'</td>
	</tr>
	</table>';
if (!$id) {
    echo "<script language=javascript>
function printWindow() {
bV = parseInt(navigator.appVersion);
if (bV >= 5) window.print();
}
printWindow();
</script>";
}
    ;
    break;

case "eL_PrintCoveringLetterALLCab":
function HeaderingExcel($filename)
{
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$filename");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
    header("Pragma: public");
}
    $met_c = mysql_fetch_array(mysql_query('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$_REQUEST['id_cost'].'"'));
    HeaderingExcel('COVERING_LETTER.xls');
    $workbook = new Workbook("");
    $worksheet1 =& $workbook->add_worksheet('Laporan Covering Letter');

    $worksheet1->setMerge(0, 0, 0, 6);	$worksheet1->writeString(0, 0, "LAPORAN DATA COVERING LETTER CABANG", $fjudul, 1);
    $worksheet1->setMerge(1, 0, 1, 6);	$worksheet1->writeString(1, 0, strtoupper($met_c['name']), $fjudul);
    if ($_REQUEST['tgl1']) {
        $worksheet1->setMerge(2, 0, 2, 6);
        $worksheet1->writeString(2, 0, 'Tanggal Debitnote  '._convertDate($_REQUEST['tgl1']) .' s/d '. _convertDate($_REQUEST['tgl2']), $fjudul);
    }
    $format =& $workbook->add_format();
    $format->setAlign('vcenter');	$format->setAlign('center');	$format->setColor('white');	$format->setBold();	$format->setPattern();	$format->setFgColor('green');
    $fjudul =& $workbook->add_format();	$fjudul->setAlign('vcenter');	$fjudul->setAlign('center');	$fjudul->setBold();

    $worksheet1->setRow(4, 15);
    $worksheet1->setColumn(4, 0, 5);	$worksheet1->writeString(4, 0, "NO", $format);
    $worksheet1->setColumn(4, 1, 15);	$worksheet1->writeString(4, 1, "CABANG", $format);
    $worksheet1->setColumn(4, 2, 15);	$worksheet1->writeString(4, 2, "PRODUK", $format);
    $worksheet1->setColumn(4, 3, 15);	$worksheet1->writeString(4, 3, "JUMLAH PESERTA", $format);
    $worksheet1->setColumn(4, 4, 15);	$worksheet1->writeString(4, 4, "JUMLAH PLAFOND", $format);
    $worksheet1->setColumn(4, 5, 15);	$worksheet1->writeString(4, 5, "PREMI", $format);
    $worksheet1->setColumn(4, 6, 10);	$worksheet1->writeString(4, 6, "EM", $format);
    $worksheet1->setColumn(4, 7, 5);	$worksheet1->writeString(4, 7, "TOTAL PREMI", $format);
if ($_REQUEST['id_cost']) {
    $satu = 'AND fu_ajk_dn.id_cost = "'.$_REQUEST['id_cost'].'"';
}
if ($_REQUEST['nmProd']) {
    $tiga = 'AND fu_ajk_dn.id_nopol = "'.$_REQUEST['nmProd'].'"';
}
if ($_REQUEST['tgl1']) {
    $duaa = 'AND fu_ajk_dn.tgl_createdn BETWEEN "'.$_REQUEST['tgl1'].'" AND "'.$_REQUEST['tgl2'].'" ';
}
$baris = 5;
$metCabang = mysql_query('SELECT
fu_ajk_polis.nmproduk,
fu_ajk_dn.id_cabang,
COUNT(fu_ajk_dn.dn_kode) AS jDN,
fu_ajk_dn.tgl_createdn,
fu_ajk_peserta.spaj,
fu_ajk_peserta.nama,
SUM(fu_ajk_peserta.kredit_jumlah) AS tPlafond,
SUM(fu_ajk_peserta.premi) AS tPremi,
SUM(fu_ajk_peserta.ext_premi) AS tEM,
SUM(fu_ajk_peserta.totalpremi) AS tTotalpremi
FROM
fu_ajk_dn
INNER JOIN fu_ajk_peserta ON fu_ajk_dn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_dn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_dn.id = fu_ajk_peserta.id_dn
LEFT JOIN fu_ajk_polis ON fu_ajk_peserta.id_polis = fu_ajk_polis.id
WHERE fu_ajk_dn.id != "" '.$satu.' '.$duaa.' '.$tiga.' AND fu_ajk_dn.del IS NULL AND fu_ajk_peserta.del IS NULL
GROUP BY fu_ajk_dn.id_cabang
ORDER BY fu_ajk_dn.id_cabang ASC, fu_ajk_dn.dn_kode ASC');
while ($metCabang_ = mysql_fetch_array($metCabang)) {
    if ($_REQUEST['nmProd']=="") {
        $produknya = 'SEMUA PRODUK';
    } else {
        $produknya = $metCabang_['nmproduk'];
    }
    $worksheet1->writeNumber($baris, 0, ++$no);
    $worksheet1->writeString($baris, 1, $metCabang_['id_cabang']);
    $worksheet1->writeString($baris, 2, $produknya);
    $worksheet1->writeNumber($baris, 3, $metCabang_['jDN']);
    $worksheet1->writeNumber($baris, 4, $metCabang_['tPlafond']);
    $worksheet1->writeNumber($baris, 5, $metCabang_['tPremi'], "R");
    $worksheet1->writeNumber($baris, 6, $metCabang_['tEM'], "R");
    $worksheet1->writeNumber($baris, 7, $metCabang_['tTotalpremi'], "R");
    $baris++;
}

$workbook->close();
    ;
    break;


case "eL_HistUser":
function HeaderingExcel($filename)
{
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$filename");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
    header("Pragma: public");
}
    HeaderingExcel('HISTORY_USER_WEB'.$_REQUEST['cbg'].'.xls');
    $workbook = new Workbook("");
    $worksheet1 =& $workbook->add_worksheet('REKAP USER WEB');
    $format =& $workbook->add_format();
    $format->setAlign('vcenter');	$format->setAlign('center');	$format->setColor('white');	$format->setBold();	$format->setPattern();	$format->setFgColor('green');
    $fjudul =& $workbook->add_format();	$fjudul->setAlign('vcenter');	$fjudul->setAlign('center');	$fjudul->setBold();

if ($_REQUEST['tgldn1']) {
    $satu = 'AND lastdate_login BETWEEN "'.$_REQUEST['tgldn1'].'" AND "'.$_REQUEST['tgldn2'].'"';
}
    $worksheet1->setMerge(0, 0, 0, 8);	$worksheet1->writeString(0, 0, "HISTORY LOG USER WEBSITE", $fjudul, 1);
    $worksheet1->setMerge(1, 0, 1, 8);	$worksheet1->writeString(1, 0, "TANGGAL LOGIN "._convertDate($_REQUEST['tgldn1'])." s/d "._convertDate($_REQUEST['tgldn2'])."", $fjudul);
    $worksheet1->setRow(3, 15);
    $worksheet1->setColumn(3, 0, 5);	$worksheet1->writeString(3, 0, "NO", $format);
    $worksheet1->setColumn(3, 1, 10);	$worksheet1->writeString(3, 1, "USER", $format);
    $worksheet1->setColumn(3, 2, 10);	$worksheet1->writeString(3, 2, "TANGGAL LOGIN", $format);
    $worksheet1->setColumn(3, 3, 10);	$worksheet1->writeString(3, 3, "WAKTU LOGIN", $format);
    $worksheet1->setColumn(3, 4, 10);	$worksheet1->writeString(3, 4, "TANGGAL LOGOUT", $format);
    $worksheet1->setColumn(3, 5, 10);	$worksheet1->writeString(3, 5, "WAKTU LOGOUT", $format);
    $worksheet1->setColumn(3, 6, 10);	$worksheet1->writeString(3, 6, "IP ADDRESS", $format);
    $worksheet1->setColumn(3, 7, 25);	$worksheet1->writeString(3, 7, "NAMA KOMPUTER", $format);
    $worksheet1->setColumn(3, 8, 25);	$worksheet1->writeString(3, 8, "BROWSER", $format);
    $baris = 4;
$kucing = mysql_query('SELECT * FROM ajk_logger WHERE id != "" '.$satu.' ORDER BY id DESC');
while ($jangkrik = mysql_fetch_array($kucing)) {
    $userweb = mysql_fetch_array(mysql_query('SELECT id, nm_lengkap FROM pengguna WHERE id="'.$jangkrik['id_user'].'"'));
    $worksheet1->writeNumber($baris, 0, ++$no);
    $worksheet1->writeString($baris, 1, $userweb['nm_lengkap']);
    $worksheet1->writeString($baris, 2, $jangkrik['lastdate_login']);
    $worksheet1->writeString($baris, 3, $jangkrik['lasttime_login']);
    $worksheet1->writeString($baris, 4, $jangkrik['lastdate_logout']);
    $worksheet1->writeString($baris, 5, $jangkrik['lasttime_logout']);
    $worksheet1->writeString($baris, 6, $jangkrik['user_ip']);
    $worksheet1->writeString($baris, 7, $jangkrik['user_referer']);
    $worksheet1->writeString($baris, 8, $jangkrik['user_browser']);
    $baris++;
}

$workbook->close();
    ;
    break;

case "eL_HistSPK":
    function HeaderingExcel($filename)
    {
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$filename");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
        header("Pragma: public");
    }
    $met_c = mysql_fetch_array(mysql_query('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$_REQUEST['cat'].'"'));
    HeaderingExcel('HISTORICAL_SPK.xls');
    $workbook = new Workbook("");
    $worksheet1 =& $workbook->add_worksheet('HISTORICAL SPK');

    $met_c = mysql_fetch_array(mysql_query('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$_REQUEST['idCost'].'"'));
    $worksheet1->setMerge(0, 0, 0, 11);	$worksheet1->writeString(0, 0, "LAPORAN HISTORICAL SPK", $fjudul, 1);
    $worksheet1->setMerge(1, 0, 1, 11);	$worksheet1->writeString(1, 0, strtoupper($met_c['name']), $fjudul);
    $worksheet1->setMerge(2, 0, 2, 11);	$worksheet1->writeString(2, 0, "PERIODE "._ConvertDate($_REQUEST['tgl1'])." s/d "._ConvertDate($_REQUEST['tgl2']), $fjudul);

    $format =& $workbook->add_format();
    $format->setAlign('vcenter');	$format->setAlign('center');	$format->setColor('white');	$format->setBold();	$format->setPattern();	$format->setFgColor('green');
    $fjudul =& $workbook->add_format();	$fjudul->setAlign('vcenter');	$fjudul->setAlign('center');	$fjudul->setBold();

    $worksheet1->setRow(3, 15);
    $worksheet1->setColumn(3, 0, 5);	$worksheet1->writeString(3, 0, "NO", $format);
    $worksheet1->setColumn(3, 1, 15);	$worksheet1->writeString(3, 1, "NOMOR SPK", $format);
    $worksheet1->setColumn(3, 2, 15);	$worksheet1->writeString(3, 2, "ID CN", $format);
    $worksheet1->setColumn(3, 3, 15);	$worksheet1->writeString(3, 3, "ID PESERTA", $format);
    $worksheet1->setColumn(3, 4, 15);	$worksheet1->writeString(3, 4, "NAMA PESERTA", $format);
    $worksheet1->setColumn(3, 5, 15);	$worksheet1->writeString(3, 5, "NAMA MARKETING", $format);
    $worksheet1->setColumn(3, 6, 15);	$worksheet1->writeString(3, 6, "TANGGAL SPK", $format);
    $worksheet1->setColumn(3, 7, 15);	$worksheet1->writeString(3, 7, "PLAFON", $format);
    $worksheet1->setColumn(3, 8, 15);	$worksheet1->writeString(3, 8, "PREMI", $format);
    $worksheet1->setColumn(3, 9, 15);	$worksheet1->writeString(3, 9, "NAMA DOKTER PERIKSA", $format);
    $worksheet1->setColumn(3, 10, 15);	$worksheet1->writeString(3, 10, "TANGGAL DOKTER PERIKSA", $format);
    $worksheet1->setColumn(3, 11, 10);	$worksheet1->writeString(3, 11, "CABANG ", $format);

    if ($_REQUEST['idCost']) {
        $satu = 'AND fu_ajk_spak.id_cost = "'.$_REQUEST['idCost'].'"';
    }
    //if ($_REQUEST['tgl1'])			{	$duaa = 'AND fu_ajk_spak.input_date BETWEEN "'.$_REQUEST['tgl1'].'" AND "'.$_REQUEST['tgl2'].'" ';	}
    if ($_REQUEST['tgl1']) {
        if ($_REQUEST['tgl1'] == $_REQUEST['tgl2']) {
            $PenambahanTgl = strtotime('+1 day', strtotime($_REQUEST['tgl2'])) ;
            ;
            $newdate = date('Y-m-d', $PenambahanTgl);
            $duaa = 'AND fu_ajk_spak.input_date BETWEEN "'.$_REQUEST['tgl1'].'" AND "'.$newdate.'" ';
        } else {
            $PenambahanTgl = strtotime('+1 day', strtotime($_REQUEST['tgl2'])) ;
            ;
            $newdate2 = date('Y-m-d', $PenambahanTgl);
            $duaa = 'AND fu_ajk_spak.input_date BETWEEN "'.$_REQUEST['tgl1'].'" AND "'.$newdate2.'" ';
        }
    }
    if ($_REQUEST['st']) {
        $tiga = 'AND fu_ajk_spak.status = "'.$_REQUEST['st'].'"';
    }

    $baris = 4;
    $er_data = mysql_query('SELECT
fu_ajk_cn.id_cn,
fu_ajk_cn.id_peserta,
fu_ajk_peserta.nama,
fu_ajk_peserta.spaj,
fu_ajk_spak.spak,
fu_ajk_spak.input_by,
fu_ajk_spak_form.dokter_pemeriksa,
fu_ajk_spak_form.tgl_periksa,
date_format(fu_ajk_spak.input_date,"%Y-%m-%d") as input_date,
fu_ajk_peserta.kredit_jumlah AS plafond,
fu_ajk_peserta.totalpremi AS premi,
fu_ajk_peserta.cabang
FROM
fu_ajk_cn
LEFT JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id = fu_ajk_peserta.id_klaim
LEFT JOIN fu_ajk_spak ON fu_ajk_peserta.id_cost = fu_ajk_spak.id_cost AND fu_ajk_peserta.id_polis = fu_ajk_spak.id_polis AND fu_ajk_peserta.spaj = fu_ajk_spak.spak
LEFT JOIN fu_ajk_spak_form ON fu_ajk_spak.id_cost = fu_ajk_spak_form.idcost AND fu_ajk_spak.id = fu_ajk_spak_form.idspk
WHERE
fu_ajk_cn.type_claim = "Death" AND
fu_ajk_cn.id_nopol = "1" AND
date_format(fu_ajk_spak.input_date,"%Y-%m-%d") BETWEEN "'.$_REQUEST['tgl1'].'" AND "'.$_REQUEST['tgl2'].'"');
    while ($mamet = mysql_fetch_array($er_data)) {
        if (is_numeric($mamet['cabang'])) {
            $met_Cabang = mysql_fetch_array(mysql_query('SELECT id, name FROM fu_ajk_cabang WHERE id="'.$mamet['cabang'].'"'));
            $inputcabang = $met_Cabang['name'];
        } else {
            $inputcabang = $mamet['cabang'];
        }

        if (is_numeric($mamet['input_by'])) {
            $nmUser = mysql_fetch_array(mysql_query('SELECT id, namalengkap FROM user_mobile WHERE id="'.$mamet['input_by'].'"'));
            $_nmMarketing = $nmUser['namalengkap'];
        } else {
            $_nmMarketing = $mamet['input_by'];
        }
        $worksheet1->writeNumber($baris, 0, ++$no);
        $worksheet1->writeString($baris, 1, $mamet['spak']);
        $worksheet1->writeString($baris, 2, $mamet['id_cn']);
        $worksheet1->writeString($baris, 3, $mamet['id_peserta'], "C");
        $worksheet1->writeString($baris, 4, $mamet['nama']);
        $worksheet1->writeString($baris, 5, $_nmMarketing);
        $worksheet1->writeString($baris, 6, _ConvertDate($mamet['input_date']), "C");
        $worksheet1->writeString($baris, 7, $mamet['plafond']);
        $worksheet1->writeString($baris, 8, $mamet['premi']);
        $worksheet1->writeString($baris, 9, $mamet['dokter_pemeriksa']);
        $worksheet1->writeString($baris, 10, _ConvertDate($mamet['tgl_periksa']));
        $worksheet1->writeString($baris, 11, $mamet['cabang']);
        $baris++;
    }
    $workbook->close();
    ;
    break;

    $workbook->close();
    ;
    break;

case "eL_HistUserTab":
function HeaderingExcel($filename)
{
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$filename");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
    header("Pragma: public");
}
    HeaderingExcel('HISTORY_USER_TABLET'.$_REQUEST['cbg'].'.xls');
    $workbook = new Workbook("");
    $worksheet1 =& $workbook->add_worksheet('REKAP USER TABLET');
    $format =& $workbook->add_format();
    $format->setAlign('vcenter');	$format->setAlign('center');	$format->setColor('white');	$format->setBold();	$format->setPattern();	$format->setFgColor('green');
    $fjudul =& $workbook->add_format();	$fjudul->setAlign('vcenter');	$fjudul->setAlign('center');	$fjudul->setBold();

if ($_REQUEST['tgldn1']) {
    $satu = 'AND DATE(fu_ajk_user_mobile_history.`timestamp`) BETWEEN "'.$_REQUEST['tgldn1'].'" AND "'.$_REQUEST['tgldn2'].'"';
}
    $worksheet1->setMerge(0, 0, 0, 7);	$worksheet1->writeString(0, 0, "HISTORY LOG USER TABLET", $fjudul, 1);
    $worksheet1->setMerge(1, 0, 1, 7);	$worksheet1->writeString(1, 0, "TANGGAL LOGIN "._convertDate($_REQUEST['tgldn1'])." s/d "._convertDate($_REQUEST['tgldn2'])."", $fjudul);
    $worksheet1->setRow(3, 15);
    $worksheet1->setColumn(3, 0, 5);	$worksheet1->writeString(3, 0, "NO", $format);
    $worksheet1->setColumn(3, 1, 10);	$worksheet1->writeString(3, 1, "USER", $format);
    $worksheet1->setColumn(3, 2, 10);	$worksheet1->writeString(3, 2, "STATUS", $format);
    $worksheet1->setColumn(3, 3, 10);	$worksheet1->writeString(3, 3, "TANGGAL", $format);
    $worksheet1->setColumn(3, 4, 10);	$worksheet1->writeString(3, 4, "IP ADDRESS", $format);
    $worksheet1->setColumn(3, 5, 10);	$worksheet1->writeString(3, 5, "NOMOR SPK", $format);
    $worksheet1->setColumn(3, 6, 10);	$worksheet1->writeString(3, 6, "TANGGAL SPK", $format);
    $worksheet1->setColumn(3, 7, 10);	$worksheet1->writeString(3, 7, "CABANG", $format);
    $baris = 4;
    $kucing = mysql_query('SELECT
fu_ajk_user_mobile_history.iduser,
fu_ajk_user_mobile_history.ipuser,
fu_ajk_user_mobile_history.module,
fu_ajk_user_mobile_history.`timestamp`,
user_mobile.type,
user_mobile.nama,
user_mobile.namalengkap,
fu_ajk_spak.spak,
fu_ajk_spak.input_date,
fu_ajk_cabang.`name`
FROM
fu_ajk_user_mobile_history
INNER JOIN user_mobile ON fu_ajk_user_mobile_history.iduser = user_mobile.id
LEFT JOIN fu_ajk_spak ON fu_ajk_user_mobile_history.iduser = fu_ajk_spak.input_by
LEFT JOIN fu_ajk_cabang ON user_mobile.cabang = fu_ajk_cabang.id
WHERE fu_ajk_user_mobile_history.id != "" '.$satu.'
ORDER BY fu_ajk_user_mobile_history.`timestamp` DESC');
while ($jangkrik = mysql_fetch_array($kucing)) {
    /*$userweb = mysql_fetch_array(mysql_query('SELECT fu_ajk_cabang.`name` AS cabang,
    user_mobile.id,
    user_mobile.type,
    user_mobile.`level`,
    user_mobile.supervisor,
    user_mobile.`status`,
    user_mobile.namalengkap
    FROM
    user_mobile
    LEFT JOIN fu_ajk_cabang ON user_mobile.cabang = fu_ajk_cabang.id WHERE user_mobile.id="'.$jangkrik['iduser'].'"'));;
    $jumlahSPK = mysql_fetch_array(mysql_query('SELECT input_by, COUNT(spak) AS jSPAK FROM fu_ajk_spak WHERE input_by="'.$jangkrik['iduser'].'" GROUP BY input_by'));
    */
    $worksheet1->writeNumber($baris, 0, ++$no);
    $worksheet1->writeString($baris, 1, $jangkrik['namalengkap']);
    $worksheet1->writeString($baris, 2, $jangkrik['type']);
    $worksheet1->writeString($baris, 3, $jangkrik['timestamp']);
    $worksheet1->writeString($baris, 4, $jangkrik['ipuser']);
    $worksheet1->writeString($baris, 5, $jangkrik['spak']);
    $worksheet1->writeString($baris, 6, $jangkrik['input_date']);
    $worksheet1->writeString($baris, 7, $jangkrik['name']);
    $baris++;
}

    $workbook->close();
    ;
    break;

case "eL_PrintCoveringLetterPDF":
$pdf=new FPDF('L', 'mm', 'A4');
$pdf->Open();
$pdf->AliasNbPages();
$pdf->AddPage();

        //$pdf->Image('../image/logo_adonai.gif',10,5);
$pdf->SetFont('Arial', 'B', 7);
    if ($_REQUEST['id_cost']) {
        $satu = 'AND fu_ajk_dn.id_cost = "'.$_REQUEST['id_cost'].'"';
    }
    if ($_REQUEST['nmProd']) {
        $dua = 'AND fu_ajk_dn.id_nopol = "'.$_REQUEST['nmProd'].'"';
    }
    if ($_REQUEST['cbg']) {
        $tiga = 'AND fu_ajk_dn.id_cabang = "'.$_REQUEST['cbg'].'"';
    }
    if ($_REQUEST['tgl1']) {
        $empat = 'AND fu_ajk_dn.tgl_createdn BETWEEN "'.$_REQUEST['tgl1'].'" AND "'.$_REQUEST['tgl2'].'" ';
    }
    if ($_REQUEST['tgltrans1']) {
        $lima = 'AND fu_ajk_dn.tgltransaksi BETWEEN "'.$_REQUEST['tgltrans1'].'" AND "'.$_REQUEST['tgltrans2'].'" ';
    }

if ($_REQUEST['val']=="bank") {
    //	if ($_REQUEST['grupprod'])		{	$lima = 'AND fu_ajk_peserta.nama_mitra = "'.$_REQUEST['grupprod'].'"';	}
    $_typeCL = 'COVERING LETTER BANK';
    if ($_REQUEST['status']) {
        $status_ = explode("-", $_REQUEST['status']);
        if (!$status_[1]) {
            $enam = 'AND fu_ajk_peserta.status_aktif = "'.$status_[0].'"';
        } else {
            $enam = 'AND fu_ajk_peserta.status_aktif = "'.$status_[0].'"';
            $tujuh = 'AND fu_ajk_peserta.status_peserta = "'.$status_[1].'"';
        }
    }
} else {
    $_typeCL = 'COVERING LETTER';
}

if ($_REQUEST['grupprod']) {
    $lima = 'AND fu_ajk_peserta.nama_mitra = "'.$_REQUEST['grupprod'].'"';
}
$_nmPerusahaan = mysql_fetch_array(mysql_query('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$_REQUEST['id_cost'].'"'));
$_nmMitra = mysql_fetch_array(mysql_query('SELECT id, nmproduk FROM fu_ajk_grupproduk WHERE id="'.$_REQUEST['grupprod'].'"'));
$metCL = mysql_query('SELECT
	fu_ajk_grupproduk.nmproduk AS mitra,
	fu_ajk_polis.nmproduk,
	fu_ajk_dn.id_cabang,
	fu_ajk_dn.dn_kode,
	fu_ajk_dn.tgl_createdn,
	fu_ajk_peserta.id_cost,
	fu_ajk_peserta.spaj,
	fu_ajk_peserta.id_peserta,
	fu_ajk_peserta.nama,
	fu_ajk_peserta.tgl_lahir,
	fu_ajk_peserta.usia,
	fu_ajk_peserta.kredit_jumlah,
	fu_ajk_peserta.kredit_tgl,
	fu_ajk_peserta.kredit_akhir,
	fu_ajk_peserta.kredit_tenor,
	fu_ajk_peserta.ext_premi,
	fu_ajk_peserta.premi,
	fu_ajk_peserta.ext_premi,
	fu_ajk_peserta.totalpremi
	FROM fu_ajk_dn
	INNER JOIN fu_ajk_peserta ON fu_ajk_dn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_dn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_dn.id = fu_ajk_peserta.id_dn
	LEFT JOIN fu_ajk_polis ON fu_ajk_peserta.id_polis = fu_ajk_polis.id
	LEFT JOIN fu_ajk_grupproduk ON fu_ajk_peserta.nama_mitra = fu_ajk_grupproduk.id
	WHERE fu_ajk_dn.id != "" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' '.$tujuh.' AND fu_ajk_dn.del IS NULL AND fu_ajk_peserta.del IS NULL
	ORDER BY fu_ajk_dn.dn_kode ASC');
    while ($metCL_ = mysql_fetch_array($metCL)) {
        $namaBank = $metCL_['name'];
        $cell[$i][0] = $metCL_['nmproduk'];
        $cell[$i][1] = SUBSTR($metCL_['dn_kode'], 3);
        $cell[$i][2] = _convertDate($metCL_['tgl_createdn']);
        $cell[$i][3] = $metCL_['id_peserta'];
        $cell[$i][4] = $metCL_['nama'];
        $cell[$i][5] = _convertDate($metCL_['tgl_lahir']);
        $cell[$i][6] = $metCL_['usia'];
        $cell[$i][7] = $metCL_['kredit_jumlah'];
        $cell[$i][8] = $metCL_['kredit_tenor'];
        $cell[$i][9] = _convertDate($metCL_['kredit_tgl']);
        $cell[$i][10] = _convertDate($metCL_['kredit_akhir']);
        $cell[$i][11] = duit($metCL_['ext_premi']);
        $cell[$i][12] = $metCL_['totalpremi'];
        $i++;
        $metCL__= $metCL_['id_cost'];
    }$pdf->Ln();
    $batashalaman = 29;
        for ($j<1;$j<$i;$j++) {
            $pdf->cell(8, 5, $j+1, 1, 0, 'C');
            $pdf->cell(33, 5, $cell[$j][0], 1, 0, 'C');
            $pdf->cell(25, 5, $cell[$j][1], 1, 0, 'C');
            $pdf->cell(15, 5, $cell[$j][2], 1, 0, 'C');
            $pdf->cell(20, 5, $cell[$j][3], 1, 0, 'C');
            $pdf->cell(50, 5, $cell[$j][4], 1, 0, 'L');
            $pdf->cell(15, 5, $cell[$j][5], 1, 0, 'C');
            $pdf->cell(8, 5, $cell[$j][6], 1, 0, 'C');
            $pdf->cell(20, 5, duit($cell[$j][7]), 1, 0, 'R');
            $pdf->cell(8, 5, $cell[$j][8], 1, 0, 'C');
            $pdf->cell(23, 5, $cell[$j][9], 1, 0, 'C');
            $pdf->cell(23, 5, $cell[$j][10], 1, 0, 'C');
            $pdf->cell(15, 5, $cell[$j][11], 1, 0, 'R');
            $pdf->cell(20, 5, duit($cell[$j][12]), 1, 0, 'R');
            $pdf->Ln();
            $tPeserta += COUNT($cell[$j][4]);
            $tPlafondnya += $cell[$j][7];
            $tPreminya += $cell[$j][12];

            if ($j==$batashalaman) {
                $pdf->AddPage();
                $pdf->Ln();
                $cell[$i][0] = $metCL_['nmproduk'];
                $cell[$i][1] = SUBSTR($metCL_['dn_kode'], 3);
                $cell[$i][2] = _convertDate($metCL_['tgl_createdn']);
                $cell[$i][3] = $metCL_['id_peserta'];
                $cell[$i][4] = $metCL_['nama'];
                $cell[$i][5] = _convertDate($metCL_['tgl_lahir']);
                $cell[$i][6] = $metCL_['usia'];
                $cell[$i][7] = $metCL_['kredit_jumlah'];
                $cell[$i][8] = $metCL_['kredit_tenor'];
                $cell[$i][9] = _convertDate($metCL_['kredit_tgl']);
                $cell[$i][10] = _convertDate($metCL_['kredit_akhir']);
                $cell[$i][11] = duit($metCL_['ext_premi']);
                $cell[$i][12] = $metCL_['totalpremi'];
                $batashalaman=30+$j;
            }
        }
    $mod = fmod($j, 30);
    $pdf->cell(146, 6, ' ', 0, 0, 'C');
//	$pdf->cell(88,6,'JUMLAH PESERTA',1,0,'C');
//	$pdf->cell(50,6,duit($tPeserta),1,0,'R');
    $pdf->cell(28, 6, 'TOTAL', 0, 0, 'C');
    $pdf->cell(20, 6, duit($tPlafondnya), 0, 0, 'R');
    $pdf->cell(69, 6, 'TOTAL', 0, 0, 'C');
    $pdf->cell(20, 6, duit($tPreminya), 0, 0, 'R');
    if ($mod > 19) {
        $pdf->AddPage('', '', 'false');
    }
    $pdf->Ln();	$pdf->cell(50, 3, 'JUMLAH PESERTA :', 0, 0, 'R');	$pdf->cell(30, 3, duit($tPeserta), 0, 0, 'R');
    $pdf->Ln();	$pdf->cell(50, 3, 'JUMLAH PREMI :', 0, 0, 'R');	$pdf->cell(30, 3, duit($tPreminya), 0, 0, 'R');
    $pdf->Ln();	$pdf->cell(50, 3, 'JUMLAH PLAFOND :', 0, 0, 'R');	$pdf->cell(30, 3, duit($tPlafondnya), 0, 0, 'R');
    $pdf->Ln();
    $pdf->MultiCell(0, 4, 'Note :', 0, 'L');
if ($_REQUEST['val']=="bank") {
    $_nmPerusahaanBank = mysql_fetch_array(mysql_query('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$metCL__['id_cost'].'"'));
    $pdf->MultiCell(0, 4, 'Mohon diperiksa dan dicocokan dengan data yang ada di Cabang '.$_nmPerusahaanBank['name'].', jika terdapat perbedaan harap memberitahukan secara tertulis ke PT. Adonai Pialang Asuransi serta mengisi Form Revisi (terlampir) dan tembusan dikirim ke '.$_nmPerusahaanBank['name'].', PUSAT. Apabila dalam kurun waktu 7 (tujuh) hari kerja sejak diterimanya daftar peserta ini tidak ada pemberitahuan, maka data diatas kami anggap sudah benar dan sesuai dengan data yang ada di '.$_nmPerusahaanBank['name'].' Cabang '.$_REQUEST['cbg'].'.', 0, 'L');
} else {
    $pdf->MultiCell(0, 4, 'Bila ada perbedaan data yang ada di Cabang '.$_nmPerusahaan['name'].'. harap segera melaporkan secara tertulis ke PT. Adonai Pialang Asuransi dan tembusan dikirim ke '.$_nmPerusahaan['name'].'. Pusat. Apabila dalam waktu 7 (tujuh) hari kalender sejak tanggal terbitnya daftar peserta AJK ini tidak ada pemberitahuan, maka data di atas kami anggap sudah benar dan sesuai dengan data yang sudah ada di Cabang '.$_nmPerusahaan['name'].'.', 0, 'L');
}
    $pdf->Ln();
    $pdf->cell(30, 5, 'Bekasi, '.$Today_.'', 0, 0, 'C');
    $pdf->Ln();	$pdf->cell(70, 5, 'Disampaikan oleh', 0, 0, 'C');			$pdf->cell(100, 5, '', 0, 0, 'C');			$pdf->cell(80, 5, 'Mengetahui dan Menyetujui', 0, 0, 'C');
    $pdf->Ln();	$pdf->cell(70, 3, 'PT. Adonai Pialang Asuransi', 0, 0, 'C');	$pdf->cell(100, 3, '', 0, 0, 'C');
if ($_REQUEST['val']=="bank") {
    $pdf->cell(80, 3, $_nmPerusahaanBank['name'].', Cabang '.$_REQUEST['cbg'].'', 0, 0, 'C');
} else {
    $pdf->cell(80, 3, $_nmPerusahaan['name'].', Cabang '.$_REQUEST['cbg'].'', 0, 0, 'C');
}
    $pdf->Ln();$pdf->Ln();$pdf->Ln();$pdf->Ln();$pdf->Ln();$pdf->Ln();
    $pdf->cell(70, 5, '( Dessy Puji Astuti )', 0, 0, 'C');		$pdf->cell(100, 5, '', 0, 0, 'C');		$pdf->cell(80, 5, '(                                                        )', 0, 0, 'C');$pdf->Ln();
    $pdf->cell(70, 1, 'Manager Life Insurance', 0, 0, 'C');		$pdf->cell(100, 5, '', 0, 0, 'C');
    $pdf->Output("SPK_".$met['spak']."_".$met_namafilenya.".pdf", "I");
    ;
    break;

case "memocn":
$pdf=new FPDF('P', 'mm', 'A4');
$pdf->Open();
$pdf->AliasNbPages();
$pdf->AddPage();
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Text(95, 15, 'MEMORANDUM');
    $metIDmemo = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_cnmemo WHERE id="'.$_REQUEST['idMemo'].'"'));
    $metPDFfile = 'MEMORANDUM_CN_'.$metIDmemo['creatememo'].'_'.$metIDmemo['cabangmemo'];
    $met_Filememo = mysql_query('UPDATE fu_ajk_cnmemo SET filememo="'.$metPDFfile.'" WHERE id="'.$_REQUEST['idMemo'].'"');

    //$idmemonya = $metIDmemo['id'] + 1;
    $tglMemo = explode("-", $metIDmemo['creatememo']);
    $pdf->Text(85, 19, 'NO.'.$metIDmemo['kodememo'].'');

    $jMetMemo = mysql_fetch_array(mysql_query('SELECT COUNT(fu_ajk_cn.id_peserta) AS jData FROM fu_ajk_cn WHERE fu_ajk_cn.tgl_createcn = "'.$metIDmemo['creatememo'].'" AND fu_ajk_cn.id_cabang = "'.$metIDmemo['cabangmemo'].'" GROUP BY fu_ajk_cn.tgl_createcn, fu_ajk_cn.id_cabang'));
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Text(75, 25, 'Kepada');	$pdf->Text(90, 25, ':');		$pdf->Text(93, 25, 'Bag. Adm Kredit/Sundries');
    $pdf->Text(75, 29, 'Dari');		$pdf->Text(90, 29, ':');		$pdf->Text(93, 29, 'Bag. Asuransi');
    $pdf->Text(75, 33, 'Perihal');	$pdf->Text(90, 33, ':');		$pdf->Text(93, 33, 'Refund Asuransi ADONAI '.$jMetMemo['jData'].' DEB '.$metIDmemo['cabangmemo']);
    $pdf->Text(75, 37, 'Tanggal');	$pdf->Text(90, 37, ':');		$pdf->Text(93, 37, $tglMemo[2].' '.bulan($tglMemo[1]).' '.$tglMemo[0]);
    $pdf->Line(10, 40, 200, 40);
    $pdf->Ln(33);
    $pdf->MultiCell(0, 4, 'Sehubungan dengan telah disetujuinya Refund Asuransi dari PT. ADONAI untuk debitur atas nama '.$jMetMemo['jData'].' DEB '.$metIDmemo['cabangmemo'].' maka dengan ini kami mohon bantuannya untuk transaksi sbb:', 0, 'L');


/*
$metPDFfile = 'MEMORANDUM_CN_'.$_REQUEST['tglCN'].'_'.$_REQUEST['cabCN'];
$memomamet = mysql_query('INSERT INTO fu_ajk_cnmemo SET kodememo="'.$idmemonya.'/MEMO/ASR-SPA/'.KonDecRomawi($tglMemo[1]).'/'.substr($tglMemo[0],2).'",
                                                        creatememo = "'.$_REQUEST['tglCN'].'",
                                                        cabangmemo = "'.$_REQUEST['cabCN'].'",
                                                        filememo = "'.$metPDFfile.'",
                                                        input_by = "'.$_REQUEST['u'].'",
                                                        input_date = "'.$futgl.'"');
*/
$metMemoData = mysql_query('SELECT fu_ajk_costumer.`name`,
										  fu_ajk_polis.nmproduk,
										  fu_ajk_polis.rek_1,
										  fu_ajk_cn.id_peserta,
										  fu_ajk_peserta.id,
										  fu_ajk_peserta.nama,
										  fu_ajk_dn.dn_kode,
										  fu_ajk_cn.tgl_claim,
										  fu_ajk_cn.type_claim,
										  fu_ajk_peserta.totalpremi,
										  fu_ajk_cn.total_claim
										  FROM fu_ajk_cn
										  LEFT JOIN fu_ajk_costumer ON fu_ajk_cn.id_cost = fu_ajk_costumer.id
										  LEFT JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
										  LEFT JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
										  LEFT JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
										  WHERE fu_ajk_cn.del IS NULL AND fu_ajk_cn.tgl_createcn = "'.$metIDmemo['creatememo'].'" AND fu_ajk_cn.id_cabang = "'.$metIDmemo['cabangmemo'].'" ');
while ($metMemoData_ = mysql_fetch_array($metMemoData)) {
    $cell[$i][0] = $metMemoData_['nama'];
    $cell[$i][1] = $metMemoData_['total_claim'];
    $i++;
}$pdf->Ln();
for ($j<1;$j<$i;$j++) {
    $pdf->cell(100, 5, $cell[$j][0], 0, 0, 'L');
    $pdf->cell(30, 5, duit($cell[$j][1]), 0, 0, 'R');
    $tNilaiCN += $cell[$j][1];
    $pdf->Ln();
}
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->cell(100, 5);
    $pdf->cell(30, 5, duit($tNilaiCN), 'T', 0, 'R', 0);$pdf->Ln();$pdf->Ln();

$metRekCabang = mysql_fetch_array(mysql_query('SELECT fu_ajk_rekening.id,
															 fu_ajk_rekening.rek_dn_cabang,
															 fu_ajk_rekening.rek_dn_cabang_name,
															 fu_ajk_rekening.rek_dn_nomor,
															 fu_ajk_rekening.rek_cn_cabang,
															 fu_ajk_rekening.rek_cn_cabang_name,
															 fu_ajk_rekening.rek_cn_nomor,
															 fu_ajk_rekening.pic_cab,
															 fu_ajk_rekening.pic_cab_jabatan,
															 fu_ajk_cabang.`name`,
															 fu_ajk_costumer.pic,
															 fu_ajk_costumer.pic2,
															 fu_ajk_costumer.rekdebet,
															 fu_ajk_costumer.rekdebet_an,
															 fu_ajk_costumer.rekcredit,
															 fu_ajk_costumer.rekcredit_an,
															 fu_ajk_costumer.picjabatan,
															 fu_ajk_costumer.picjabatan2
															 FROM fu_ajk_rekening
															 LEFT JOIN fu_ajk_costumer ON fu_ajk_rekening.id_cost = fu_ajk_costumer.id
															 LEFT JOIN fu_ajk_cabang ON fu_ajk_rekening.id_cost = fu_ajk_cabang.id_cost AND fu_ajk_rekening.cabang = fu_ajk_cabang.id
															 WHERE fu_ajk_rekening.id != "" AND fu_ajk_rekening.id_cost="'.$metIDmemo['idcost'].'" AND fu_ajk_cabang.name="'.$metIDmemo['cabangmemo'].'" AND fu_ajk_cabang.del IS NULL'));

    $pdf->SetFont('helvetica', '', 10);
    $pdf->cell(30, 5, 'DEBET', 'T', 0, 'L', 0);	$pdf->cell(100, 5, 'REK NO. 1000660431', 'T', 0, 'L', 0);		$pdf->SetFont('helvetica', 'B', 10);	$pdf->cell(5, 5, 'Rp.', 'T', 0, 'L', 0);	$pdf->cell(30, 5, duit($tNilaiCN), 'T', 1, 'R', 0);

    $pdf->SetFont('helvetica', '', 10);
    $pdf->cell(30, 3, ' ', 0, 'L', 0);			$pdf->cell(100, 3, 'PT. ADONAI PIALANG ASURANSI', '', 1, 'L', 0);$pdf->Ln();

    $pdf->cell(30, 5, 'KREDIT', '', 0, 'L', 0);	$pdf->cell(100, 5, 'REK KS NO. '.$metRekCabang['rekcredit'].'', '', 0, 'L', 0);		$pdf->SetFont('helvetica', 'B', 10);	$pdf->cell(5, 5, 'Rp.', '', 0, 'L', 0);	$pdf->cell(30, 5, duit($tNilaiCN), '', 1, 'R', 0);
    $pdf->SetFont('helvetica', '', 10);
    $pdf->cell(30, 3, ' ', 0, 'L', 0);			$pdf->cell(100, 3, '('.$metRekCabang['rekcredit_an'].')', '', 1, 'L', 0);$pdf->Ln();

    $pdf->SetFont('helvetica', '', 10);
    $pdf->cell(30, 5, 'DEBET', 'T', 0, 'L', 0);	$pdf->cell(100, 5, 'REK NO. '.$metRekCabang['rekdebet'].'', 'T', 0, 'L', 0);		$pdf->SetFont('helvetica', 'B', 10);	$pdf->cell(5, 5, 'Rp.', 'T', 0, 'L', 0);	$pdf->cell(30, 5, duit($tNilaiCN), 'T', 1, 'R', 0);

    $pdf->SetFont('helvetica', '', 10);
    $pdf->cell(30, 3, ' ', 0, 'L', 0);			$pdf->cell(100, 3, $metRekCabang['rekdebet_an'], '', 1, 'L', 0);$pdf->Ln();

    $pdf->cell(30, 5, 'KREDIT', '', 0, 'L', 0);	$pdf->cell(100, 5, 'REK NO. '.$metRekCabang['rek_cn_nomor'].'', '', 0, 'L', 0);		$pdf->SetFont('helvetica', 'B', 10);	$pdf->cell(5, 5, 'Rp.', '', 0, 'L', 0);	$pdf->cell(30, 5, duit($tNilaiCN), '', 1, 'R', 0);
    $pdf->SetFont('helvetica', '', 10);
    $pdf->cell(30, 3, ' ', 0, 'L', 0);			$pdf->cell(100, 3, '('.$metRekCabang['rek_cn_cabang_name'].')', '', 1, 'L', 0);$pdf->Ln();

    $pdf->cell(165, 5, '', 'T', 0, 'L', 0);$pdf->Ln();
    $pdf->MultiCell(0, 4, 'Demikian hal ini disampaikan, atas perhatian dan kerjasamanya diucapkan terima kasih.', 0, 'L');
    $pdf->Ln(20);
    $pdf->cell(95, 5, $metRekCabang['pic'], '', 0, 'L', 0);			$pdf->cell(95, 5, $metRekCabang['pic2'], '', 1, 'L', 0);
    $pdf->cell(95, 5, $metRekCabang['picjabatan'], '', 0, 'L', 0);	$pdf->cell(95, 5, $metRekCabang['picjabatan2'], '', 1, 'L', 0);

    $metPDFfileDir = $metpath_file.''.$metPDFfile;
    $pdf->Output($metPDFfileDir.".pdf", "F");
$pdf->Output("MEMORANDUM _CN_".$_REQUEST['tglCN']."_".$_REQUEST['cabCN'].".pdf", "I");
    ;
    break;

case "eL_SumSPK":

        function HeaderingExcel($filename)
        {
            header("Content-type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=$filename");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
            header("Pragma: public");
        }
        HeaderingExcel('DATA SUMMERY SPK.xls');
        $workbook = new Workbook("");
        $worksheet1 =& $workbook->add_worksheet('SUMMERY DATA SPK');
        $format =& $workbook->add_format();
        $format->setAlign('vcenter');	$format->setAlign('center');	$format->setColor('white');	$format->setBold();	$format->setPattern();	$format->setFgColor('green');
        $fjudul =& $workbook->add_format();	$fjudul->setAlign('vcenter');	$fjudul->setAlign('center');	$fjudul->setBold();

        $worksheet1->setMerge(0, 0, 0, 7);	$worksheet1->writeString(0, 0, "REKAP SUMMERY SPK", $fjudul, 1);
        $worksheet1->setMerge(1, 0, 1, 7);	$worksheet1->writeString(1, 0, "TANGGAL INPUT "._convertDate($_REQUEST['tgl1'])." s/d "._convertDate($_REQUEST['tgl2'])."", $fjudul);

        $worksheet1->setRow(4, 15);
        $worksheet1->setColumn(4, 0, 5);	$worksheet1->writeString(4, 0, "NO", $format);
        $worksheet1->setColumn(4, 1, 15);	$worksheet1->writeString(4, 1, "NAMA CABANG", $format);
        $worksheet1->setColumn(4, 2, 15);	$worksheet1->writeString(4, 2, "TOTAL SPK", $format);
        $worksheet1->setColumn(4, 3, 15);	$worksheet1->writeString(4, 3, "REALISASI", $format);
        $worksheet1->setColumn(4, 4, 15);	$worksheet1->writeString(4, 4, "AKTIF", $format);
        $worksheet1->setColumn(4, 5, 15);	$worksheet1->writeString(4, 5, "APPROVE", $format);
        $worksheet1->setColumn(4, 6, 10);	$worksheet1->writeString(4, 6, "PROSES", $format);
        $worksheet1->setColumn(4, 7, 5);	$worksheet1->writeString(4, 7, "PREAPPROVAL", $format);
        $worksheet1->setColumn(4, 8, 10);	$worksheet1->writeString(4, 8, "PENDING", $format);
        $worksheet1->setColumn(4, 9, 5);	$worksheet1->writeString(4, 9, "BATAL", $format);
        $worksheet1->setColumn(4, 10, 5);	$worksheet1->writeString(4, 10, "TOLAK", $format);
        $worksheet1->setColumn(4, 11, 5);	$worksheet1->writeString(4, 11, "KADALUARSA", $format);
        $sql="";
        if ($_REQUEST['idReg']!="") {
            $sql=" and fu_ajk_regional.id=".$_REQUEST['idReg'];
        }

        if ($_REQUEST['idCab']) {
            $sql=$sql." and fu_ajk_cabang.id=".$_REQUEST['idCab'];
        }
        $baris = 5;
        $kucing = mysql_query("SELECT
							nama_cost,
							nama_regional,
							nama_cabang,
							GROUP_CONCAT(`status`,' : ',CAST(jml_pemeriksaan AS CHAR),'|') AS ok
							FROM (SELECT fu_ajk_costumer.`name` AS nama_cost,
										 fu_ajk_regional.`name` AS nama_regional,
										 fu_ajk_cabang.`name` AS nama_cabang,
										 fu_ajk_spak.`status`,
								  COUNT(fu_ajk_cabang.`name`) AS jml_pemeriksaan
								  FROM fu_ajk_spak
								  INNER JOIN fu_ajk_costumer ON fu_ajk_spak.id_cost = fu_ajk_costumer.id
								  INNER JOIN fu_ajk_polis ON fu_ajk_spak.id_polis = fu_ajk_polis.id
								  LEFT JOIN fu_ajk_spak_form ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
								  LEFT JOIN fu_ajk_cabang ON fu_ajk_cabang.id=fu_ajk_spak_form.cabang
								  LEFT JOIN fu_ajk_regional ON fu_ajk_cabang.id_reg = fu_ajk_regional.id
								  LEFT JOIN fu_ajk_grupproduk ON fu_ajk_spak.id_mitra = fu_ajk_grupproduk.id
							WHERE 	fu_ajk_spak.del IS NULL AND
									fu_ajk_spak_form.del IS NULL AND
									date(fu_ajk_spak_form.input_date) between '".$_REQUEST ['tgl1']."'  and '".$_REQUEST ['tgl2']."'
							AND fu_ajk_costumer.id=".$_REQUEST['idCost']." ".$sql." and LEFT(spak,2) != 'MP'
							GROUP BY
							fu_ajk_cabang.`name`,
							fu_ajk_regional.`name`,
							fu_ajk_spak.`status`,
							fu_ajk_costumer.`name`
							) aa
							GROUP BY nama_cost,nama_regional,nama_cabang");
        /*$kucing = mysql_query("select
                        nama_cost,
                        nama_regional,
                        nama_cabang,
                        GROUP_CONCAT(`status`,' : ',CAST(jml_pemeriksaan AS CHAR),'|') as ok
                        from (
                        SELECT
                        fu_ajk_costumer.`name` as nama_cost,
                        fu_ajk_regional.`name` as nama_regional,
                        fu_ajk_cabang.`name` as nama_cabang,
                        fu_ajk_spak.`status`,
                        Count(fu_ajk_cabang.`name`) AS jml_pemeriksaan
                        FROM
                        fu_ajk_spak_form
                        INNER JOIN fu_ajk_spak ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
                        LEFT JOIN user_mobile ON fu_ajk_spak_form.dokter_pemeriksa = user_mobile.id
                        LEFT JOIN fu_ajk_cabang ON user_mobile.cabang = fu_ajk_cabang.id
                        LEFT JOIN fu_ajk_regional ON fu_ajk_cabang.id_reg = fu_ajk_regional.id
                        INNER JOIN fu_ajk_costumer ON fu_ajk_regional.id_cost = fu_ajk_costumer.id
                        where date(fu_ajk_spak_form.input_date) between '".$_REQUEST ['tgl1']."'  and '".$_REQUEST ['tgl2']."'
                        and fu_ajk_costumer.id=".$_REQUEST['idCost']." ".$sql."

                        group BY
                        fu_ajk_cabang.`name`,
                        fu_ajk_regional.`name`,
                        fu_ajk_spak.`status`,
                        fu_ajk_costumer.`name`
                        ) aa group by nama_cost,nama_regional,nama_cabang");
        */
        while ($jangkrik = mysql_fetch_array($kucing)) {
            $status=explode("|", $jangkrik['ok']);

            $pending=0;
            $aktif=0;
            $proses=0;
            $approve=0;
            $preapproval=0;
            $batal=0;
            $tolak=0;
            $realisasi=0;
            $kadaluarsa=0;

            //Aktif | Proses | Approve | Preapproval | Batal | Tolak | Realisasi
            for ($x=0;$x<=sizeof($status);$x++) {
                list($list_name, $list_count)=explode(" : ", $status[$x]);

                if (str_replace(",", "", $list_name)=="Pending") {
                    $pending=$list_count;
                }

                if (str_replace(",", "", $list_name)=="Aktif") {
                    $aktif=$list_count;
                }

                if (str_replace(",", "", $list_name)=="Proses") {
                    $proses=$list_count;
                }

                if (str_replace(",", "", $list_name)=="Approve") {
                    $approve=$list_count;
                }

                if (str_replace(",", "", $list_name)=="Preapproval") {
                    $preapproval=$list_count;
                }

                if (str_replace(",", "", $list_name)=="Batal") {
                    $batal=$list_count;
                }

                if (str_replace(",", "", $list_name)=="Tolak") {
                    $tolak=$list_count;
                }

                if (str_replace(",", "", $list_name)=="Realisasi") {
                    $realisasi=$list_count;
                }

                if (str_replace(",", "", $list_name)=="Kadaluarsa") {
                    $kadaluarsa=$list_count;
                }
            }
            $totalspk = $pending + $aktif + $proses + $approve + $preapproval + $batal + $tolak + $realisasi + $kadaluarsa;
            $worksheet1->writeNumber($baris, 0, ++$no);
            $worksheet1->writeString($baris, 1, $jangkrik['nama_cabang']);
            $worksheet1->writeNumber($baris, 2, $totalspk);
            $worksheet1->writeNumber($baris, 3, $realisasi);
            $worksheet1->writeNumber($baris, 4, $aktif);
            $worksheet1->writeNumber($baris, 5, $approve);
            $worksheet1->writeNumber($baris, 6, $proses);
            $worksheet1->writeNumber($baris, 7, $preapproval);
            $worksheet1->writeNumber($baris, 8, $pending);
            $worksheet1->writeNumber($baris, 9, $batal);
            $worksheet1->writeNumber($baris, 10, $total);
            $worksheet1->writeNumber($baris, 11, $kadaluarsa);
            $baris++;
        }

        $workbook->close();
        ;

    break;

    case "eL_UserSPK":

        function HeaderingExcel($filename)
        {
            header("Content-type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=$filename");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
            header("Pragma: public");
        }
        HeaderingExcel('DATA REKAP USER SPK.xls');
        $workbook = new Workbook("");
        $worksheet1 =& $workbook->add_worksheet('REKAP DATA USER SPK');
        $format =& $workbook->add_format();
        $format->setAlign('vcenter');	$format->setAlign('center');	$format->setColor('white');	$format->setBold();	$format->setPattern();	$format->setFgColor('green');
        $fjudul =& $workbook->add_format();	$fjudul->setAlign('vcenter');	$fjudul->setAlign('center');	$fjudul->setBold();

        $worksheet1->setMerge(0, 0, 0, 7);	$worksheet1->writeString(0, 0, "REKAP USER SPK", $fjudul, 1);
        $worksheet1->setMerge(1, 0, 1, 7);	$worksheet1->writeString(1, 0, "TANGGAL INPUT "._convertDate($_REQUEST['tgl1'])." s/d "._convertDate($_REQUEST['tgl2'])."", $fjudul);

        $worksheet1->setRow(4, 15);
        $worksheet1->setColumn(4, 0, 5);	$worksheet1->writeString(4, 0, "NO", $format);
        $worksheet1->setColumn(4, 1, 15);	$worksheet1->writeString(4, 1, "CABANG", $format);
        $worksheet1->setColumn(4, 2, 15);	$worksheet1->writeString(4, 2, "USER INPUT", $format);
        $worksheet1->setColumn(4, 3, 15);	$worksheet1->writeString(4, 3, "NO SPK", $format);
        $worksheet1->setColumn(4, 5, 15);	$worksheet1->writeString(4, 4, "STATUS", $format);

        $baris = 5;
        $sql="";
        if ($_REQUEST['idReg']!="") {
            $sql="and fu_ajk_regional.id=".$_REQUEST['idReg'];
        }

        if ($_REQUEST['idCab']) {
            $sql=$sql."and fu_ajk_cabang.id=".$_REQUEST['idCab'];
        }
        $kucing = mysql_query("SELECT
						fu_ajk_costumer.`name` as nama_cost,
						fu_ajk_regional.`name` as nama_regional,
						fu_ajk_cabang.`name` as nama_cabang,
						fu_ajk_spak.spak,
						fu_ajk_spak.input_date,
						ifnull(user_mobile.namalengkap,fu_ajk_spak.input_by) as namalengkap,
						fu_ajk_spak.`status`
						FROM
						fu_ajk_spak
						LEFT JOIN user_mobile ON fu_ajk_spak.input_by = user_mobile.id
						INNER JOIN fu_ajk_cabang ON user_mobile.cabang = fu_ajk_cabang.id
						INNER JOIN fu_ajk_regional ON fu_ajk_cabang.id_reg = fu_ajk_regional.id
						INNER JOIN fu_ajk_costumer ON fu_ajk_regional.id_cost = fu_ajk_costumer.id
						where fu_ajk_spak.input_date between '".$_REQUEST ['tgl1']."'  and '".$_REQUEST ['tgl2']."'
						and fu_ajk_costumer.id=".$_REQUEST['idCost']."
						and fu_ajk_spak.danatalangan is null ".$sql."");
        while ($jangkrik = mysql_fetch_array($kucing)) {

            //Aktif | Proses | Approve | Preapproval | Batal | Tolak | Realisasi
            $worksheet1->writeNumber($baris, 0, ++$no);
            $worksheet1->writeString($baris, 1, $jangkrik['nama_cabang']);
            $worksheet1->writeString($baris, 2, $jangkrik['namalengkap']);
            $worksheet1->writeString($baris, 3, $jangkrik['spak']);
            $worksheet1->writeString($baris, 4, $jangkrik['status']);
            $baris++;
        }

        $workbook->close();
    ;
    break;
case "eL_UserSPKDetail":

        function HeaderingExcel($filename)
        {
            header("Content-type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=$filename");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
            header("Pragma: public");
        }
        HeaderingExcel('DATA REKAP USER SPK DETAIL.xls');
        $workbook = new Workbook("");
        $worksheet1 =& $workbook->add_worksheet('REKAP DATA USER SPK DETAIL');
        $format =& $workbook->add_format();
        $format->setAlign('vcenter');	$format->setAlign('center');	$format->setColor('white');	$format->setBold();	$format->setPattern();	$format->setFgColor('green');

        $fjudul1 =& $workbook->add_format();
        $fjudul1->setAlign('vcenter');
        $fjudul1->setAlign('center');

        $fjudul =& $workbook->add_format();
        $fjudul->setAlign('vcenter');
        $fjudul->setAlign('center');
        $fjudul->setBold();

        $worksheet1->writeString(0, 0, "REKAP USER SPK DETAIL", $fjudul, 1);
        $worksheet1->setMerge(0, 0, 0, 7);
        $worksheet1->writeString(1, 0, "TANGGAL INPUT "._convertDate($_REQUEST['tgl1'])." s/d "._convertDate($_REQUEST['tgl2'])."", $fjudul);
        $worksheet1->setMerge(1, 0, 1, 7);

        $worksheet1->setRow(4, 15);
        $worksheet1->setColumn(0, 0, 5);	$worksheet1->writeString(4, 0, "NO", $format);
        $worksheet1->setColumn(1, 1, 25);	$worksheet1->writeString(4, 1, "CABANG", $format);
        $worksheet1->setColumn(2, 2, 25);	$worksheet1->writeString(4, 2, "USER INPUT", $format);
        $worksheet1->setColumn(3, 3, 15);	$worksheet1->writeString(4, 3, "JUMLAH SPK", $format);
        $worksheet1->setColumn(4, 4, 15);	$worksheet1->writeString(4, 4, "JUMLAH SKKT", $format);
        $worksheet1->setColumn(5, 5, 15);	$worksheet1->writeString(4, 5, "JUMLAH PLATINUM", $format);
        $worksheet1->setColumn(6, 6, 15);	$worksheet1->writeString(4, 6, "TOTAL", $format);

        $baris = 5;
        $sql="";
        if ($_REQUEST['idReg']!="") {
            $qreg=" and fu_ajk_cabang.id_reg=".$_REQUEST['idReg'];
        }

        if ($_REQUEST['idCab']!="") {
            $qcab=" and fu_ajk_cabang.id=".$_REQUEST['idCab'];
        }

        $query = "SELECT  fu_ajk_cabang.name as nm_cabang,
										fu_ajk_cabang.id as id_cabang,
									(SELECT user_mobile.namalengkap
									 FROM user_mobile
									 WHERE id = fu_ajk_spak.input_by and
												 del is NULL and
												 type = 'Marketing' and
												 level = 'Staff')as user,
										count(case when(id_polis in (1,12)) then spak end)as spk_tab,
										count(case when(id_polis = 16) then spak end)as platinum_tab,
										count(case when(id_polis not in(1,12,16)) then spak end)as skkt_tab

						FROM fu_ajk_spak
								INNER JOIN fu_ajk_spak_form_temp
								ON fu_ajk_spak_form_temp.idspk = fu_ajk_spak.id
								INNER JOIN fu_ajk_cabang
								ON fu_ajk_cabang.id = fu_ajk_spak_form_temp.cabang
						WHERE fu_ajk_spak.input_date BETWEEN '".$_REQUEST['tgl1']."' and '".$_REQUEST['tgl2']."' and
									fu_ajk_spak.del is null AND
									fu_ajk_spak_form_temp.del is null
						GROUP BY fu_ajk_spak_form_temp.cabang,fu_ajk_spak.input_by
						ORDER BY fu_ajk_cabang.name	";

        $kucing = mysql_query($query);

        while ($jangkrik = mysql_fetch_array($kucing)) {

            //Aktif | Proses | Approve | Preapproval | Batal | Tolak | Realisasi
            $worksheet1->writeNumber($baris, 0, ++$no);
            $worksheet1->writeString($baris, 1, $jangkrik['nm_cabang'], $fjudul1);
            $worksheet1->writeString($baris, 2, $jangkrik['user'], $fjudul1);
            $worksheet1->writeNumber($baris, 3, $jangkrik['spk_tab'], $fjudul1);
            $worksheet1->writeNumber($baris, 4, $jangkrik['skkt_tab'], $fjudul1);
            $worksheet1->writeNumber($baris, 5, $jangkrik['platinum_tab'], $fjudul1);
            $worksheet1->writeNumber($baris, 6, $jangkrik['spk_tab'] + $jangkrik['skkt_tab'] + $jangkrik['skkt_platinum'], $fjudul);
            $baris++;
        }

        $workbook->close();
    ;
    break;
case "eL_UserSPKSummary":

        function HeaderingExcel($filename)
        {
            header("Content-type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=$filename");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
            header("Pragma: public");
        }
        HeaderingExcel('DATA REKAP USER SPK SUMMARY.xls');
        $workbook = new Workbook("");
        $worksheet1 =& $workbook->add_worksheet('REKAP DATA USER SPK SUMMARY');
        $format =& $workbook->add_format();
        $format->setAlign('vcenter');
        $format->setAlign('center');
        $format->setColor('white');
        $format->setBold();
        $format->setPattern();
        $format->setFgColor('green');

        $fjudul =& $workbook->add_format();
        $fjudul->setAlign('vcenter');
        $fjudul->setAlign('center');
        $fjudul->setBold();

        $fdata =& $workbook->add_format();
        $fdata->setAlign('center');

        $ftotal =& $workbook->add_format();
        $ftotal->setAlign('center');
        $ftotal->setBold();

        $worksheet1->writeString(0, 0, "REKAP USER SPK SUMMARY", $fjudul);
        $worksheet1->setMerge(0, 0, 0, 9);
        $worksheet1->writeString(1, 0, "TANGGAL INPUT "._convertDate($_REQUEST['tgl1'])." s/d "._convertDate($_REQUEST['tgl2'])."", $fjudul);
        $worksheet1->setMerge(1, 0, 1, 9);

        $worksheet1->setRow(4, 15);
        $worksheet1->setColumn(0, 0, 5);	$worksheet1->writeString(4, 0, "NO", $format);
        $worksheet1->setColumn(1, 1, 32);	$worksheet1->writeString(4, 1, "CABANG", $format);
        $worksheet1->setColumn(2, 2, 14);	$worksheet1->writeString(4, 2, "JML USER", $format);
        $worksheet1->setColumn(3, 3, 17);	$worksheet1->writeString(4, 3, "JML SPK TAB", $format);
        $worksheet1->setColumn(4, 4, 22);	$worksheet1->writeString(4, 4, "JML SPK MANUAL", $format);
        $worksheet1->setColumn(5, 5, 17);	$worksheet1->writeString(4, 5, "JML SKKT TAB", $format);
        $worksheet1->setColumn(6, 6, 22);	$worksheet1->writeString(4, 6, "JML SKKT MANUAL", $format);
        $worksheet1->setColumn(7, 7, 17);	$worksheet1->writeString(4, 7, "JML PLATINUM TAB", $format);
        $worksheet1->setColumn(8, 8, 22);	$worksheet1->writeString(4, 8, "JML PLATINUM MANUAL", $format);
        $worksheet1->setColumn(9, 9, 7);	$worksheet1->writeString(4, 9, "TOTAL", $format);

        $baris = 5;
        $sql="";
        if ($_REQUEST['idReg']!="") {
            $qreg=" and fu_ajk_cabang.id_reg=".$_REQUEST['idReg'];
        }

        if ($_REQUEST['idCab']!="") {
            $qcab=" and fu_ajk_cabang.id=".$_REQUEST['idCab'];
        }

        $sql = "SELECT  fu_ajk_cabang.name as nm_cabang,
										fu_ajk_cabang.id as id_cabang,
									(SELECT count(id)
									 FROM user_mobile
									 WHERE cabang = fu_ajk_cabang.id and
												 del is NULL and
												 type = 'Marketing' and
												 level = 'Staff')as user,
										count(case when(id_polis in (1,12)) then spak end)as spk_tab,
										IFNULL(manual.spk_manual,0)as spk_manual,
										count(case when(id_polis = 16) then spak end)as platinum_tab,
										IFNULL(manual.platinum_manual,0)as platinum_manual,
										count(case when(id_polis not in(1,12,16)) then spak end)as skkt_tab,
										IFNULL(manual.skkt_manual,0)as skkt_manual
						FROM fu_ajk_spak
								INNER JOIN fu_ajk_spak_form_temp
								ON fu_ajk_spak_form_temp.idspk = fu_ajk_spak.id
								INNER JOIN fu_ajk_cabang
								ON fu_ajk_cabang.id = fu_ajk_spak_form_temp.cabang
								LEFT JOIN (SELECT count(case when (fu_ajk_peserta.id_polis in (1,12) and left(spaj,1) != 'M')then id end)as spk_manual,
																	 count(case when (fu_ajk_peserta.id_polis in (16) and spaj = '')then id end)as platinum_manual,
																	count(case when (fu_ajk_peserta.id_polis not in (16) and spaj = '')then id end)as skkt_manual,
																	cabang
														FROM fu_ajk_peserta
														WHERE fu_ajk_peserta.input_time BETWEEN '".$_REQUEST['tgl1']."' and '".$_REQUEST['tgl2']."' and
																	fu_ajk_peserta.del is null
														GROUP BY cabang)as manual
								ON manual.cabang = fu_ajk_cabang.name
						WHERE fu_ajk_spak.input_date BETWEEN '".$_REQUEST['tgl1']."' and '".$_REQUEST['tgl2']."' and
									fu_ajk_spak.del is null AND
									fu_ajk_spak_form_temp.del is null
						GROUP BY fu_ajk_spak_form_temp.cabang
						ORDER BY fu_ajk_cabang.name";
        $kucing= mysql_query("$sql");
        while ($jangkrik = mysql_fetch_array($kucing)) {
            $total = $jangkrik['spk_tab'] + $jangkrik['spk_manual'] + $jangkrik['platinum_tab'] + $jangkrik['platinum_manual'] + $jangkrik['skkt_tab'] + $jangkrik['skkt_manual'];
            $worksheet1->writeNumber($baris, 0, ++$no);
            $worksheet1->writeString($baris, 1, $jangkrik['nm_cabang']);
            $worksheet1->writeString($baris, 2, $jangkrik['user'], $fdata);
            $worksheet1->writeNumber($baris, 3, $jangkrik['spk_tab'], $fdata);
            $worksheet1->writeNumber($baris, 4, $jangkrik['spk_manual'], $fdata);
            $worksheet1->writeNumber($baris, 5, $jangkrik['skkt_tab'], $fdata);
            $worksheet1->writeNumber($baris, 6, $jangkrik['skkt_manual'], $fdata);
            $worksheet1->writeNumber($baris, 7, $jangkrik['platinum_tab'], $fdata);
            $worksheet1->writeNumber($baris, 8, $jangkrik['platinum_manual'], $fdata);
            $worksheet1->writeNumber($baris, 9, $total, $ftotal);
            $baris++;
            $totalall = $totalall + $total;
        }

        $worksheet1->writeString($baris, 0, 'Total', $ftotal);
        $worksheet1->setMerge($baris, 0, $baris, 8);
        $worksheet1->writeNumber($baris, 9, $totalall, $ftotal);
        $workbook->close();
    ;
    break;
    case "eL_FeeDokter":

        function HeaderingExcel($filename)
        {
            header("Content-type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=$filename");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
            header("Pragma: public");
        }
        HeaderingExcel('DATA FEE DOKTER.xls');
        $workbook = new Workbook("");
        $worksheet1 =& $workbook->add_worksheet('REKAP DATA FEE DOKTER');
        $format =& $workbook->add_format();
        $format->setAlign('vcenter');	$format->setAlign('center');	$format->setColor('white');	$format->setBold();	$format->setPattern();	$format->setFgColor('green');
        $fjudul =& $workbook->add_format();	$fjudul->setAlign('vcenter');	$fjudul->setAlign('center');	$fjudul->setBold();

        $worksheet1->setMerge(0, 0, 0, 7);	$worksheet1->writeString(0, 0, "REKAP DATA FEE DOKTER", $fjudul, 1);
        $worksheet1->setMerge(1, 0, 1, 7);	$worksheet1->writeString(1, 0, "TANGGAL PERIKSA "._convertDate($_REQUEST['tgl1'])." s/d "._convertDate($_REQUEST['tgl2'])."", $fjudul);

        $worksheet1->setRow(4, 15);
        $worksheet1->setColumn(4, 0, 5);	$worksheet1->writeString(4, 0, "NO", $format);
        $worksheet1->setColumn(4, 1, 15);	$worksheet1->writeString(4, 1, "NAMA PERUSAHAAN", $format);
        $worksheet1->setColumn(4, 2, 15);	$worksheet1->writeString(4, 2, "REGIONAL", $format);
        $worksheet1->setColumn(4, 3, 15);	$worksheet1->writeString(4, 3, "CABANG", $format);
        $worksheet1->setColumn(4, 5, 15);	$worksheet1->writeString(4, 4, "NAMA DOKTER", $format);
        $worksheet1->setColumn(4, 5, 15);	$worksheet1->writeString(4, 5, "TGL PERIKSA", $format);
        $worksheet1->setColumn(4, 5, 15);	$worksheet1->writeString(4, 6, "JUMLAH PERIKSA", $format);
        $worksheet1->setColumn(4, 5, 15);	$worksheet1->writeString(4, 7, "FEE DOKTER", $format);
        $worksheet1->setColumn(4, 5, 15);	$worksheet1->writeString(4, 8, "TOTAL", $format);

        $baris = 5;
        $sql="";

        /*$kucing = mysql_query("SELECT
                        fu_ajk_costumer.`name` as nama_cost,
                        fu_ajk_regional.`name` as nama_regional,
                        fu_ajk_cabang.`name` as nama_cabang,
                        ifnull(user_mobile.namalengkap,fu_ajk_spak_form.dokter_pemeriksa) as namalengkap,
                        fu_ajk_spak_form.tgl_periksa,
                        Count(fu_ajk_cabang.`name`) AS jml_pemeriksaan,
                        ifnull((select sum(fu_ajk_dokter_fee.fee_dokter) from fu_ajk_dokter_fee where fu_ajk_dokter_fee.id_user_mobile=user_mobile.id and
                        fu_ajk_spak_form.tgl_periksa BETWEEN fu_ajk_dokter_fee.tgl_mulai_fee and fu_ajk_dokter_fee.tgl_akhir_fee),0) AS fee_dokter,
                        ifnull((select sum(fu_ajk_dokter_fee.fee_dokter) from fu_ajk_dokter_fee where fu_ajk_dokter_fee.id_user_mobile=user_mobile.id and
                        fu_ajk_spak_form.tgl_periksa BETWEEN fu_ajk_dokter_fee.tgl_mulai_fee and fu_ajk_dokter_fee.tgl_akhir_fee)*count(fu_ajk_cabang.`name`),0) AS total
                        FROM
                        fu_ajk_spak_form
                        INNER JOIN fu_ajk_spak ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
                        LEFT JOIN user_mobile ON fu_ajk_spak_form.dokter_pemeriksa = user_mobile.id
                        INNER JOIN fu_ajk_cabang ON user_mobile.cabang = fu_ajk_cabang.id
                        INNER JOIN fu_ajk_regional ON fu_ajk_cabang.id_reg = fu_ajk_regional.id
                        INNER JOIN fu_ajk_costumer ON fu_ajk_regional.id_cost = fu_ajk_costumer.id
                        where fu_ajk_spak_form.tgl_periksa between '".$_REQUEST ['tgl1']."'  and '".$_REQUEST ['tgl2']."'
                        group BY
                        fu_ajk_cabang.`name`,
                        user_mobile.namalengkap,
                        fu_ajk_spak_form.dokter_pemeriksa,
                        fu_ajk_spak_form.tgl_periksa,
                        fu_ajk_regional.`name`,
                        fu_ajk_costumer.`name`");*/
        $kucing = mysql_query("SELECT aa.ID,
						aa.namalengkap,
						aa.norek,
						aa.atas_nama,
						aa.bank_rek,
						aa.nama_cost,
						aa.nama_regional,
						aa.nama_cabang,
						DATE_FORMAT(aa.tgl_periksa,'%M %Y') as tgl,
						sum(aa.jml_pemeriksaan) as jml_pemeriksaan,
						fu_ajk_dokter_fee.fee_dokter,
						sum(aa.jml_pemeriksaan) * fu_ajk_dokter_fee.fee_dokter as total
						FROM (SELECT
						user_mobile.ID,
						user_mobile.namalengkap,
						user_mobile.norek,
						user_mobile.atas_nama,
						user_mobile.bank_rek,
						fu_ajk_costumer.`name` as nama_cost,
						fu_ajk_regional.`name` as nama_regional,
						fu_ajk_cabang.`name` as nama_cabang,
						fu_ajk_spak_form.tgl_periksa,
						Count(fu_ajk_cabang.`name`) AS jml_pemeriksaan
						FROM
						fu_ajk_spak_form
						INNER JOIN fu_ajk_spak ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
						LEFT JOIN user_mobile ON fu_ajk_spak_form.dokter_pemeriksa = user_mobile.id
						INNER JOIN fu_ajk_cabang ON user_mobile.cabang = fu_ajk_cabang.id
						INNER JOIN fu_ajk_regional ON fu_ajk_cabang.id_reg = fu_ajk_regional.id
						INNER JOIN fu_ajk_costumer ON fu_ajk_regional.id_cost = fu_ajk_costumer.id
						where user_mobile.`type`='Dokter'
						and DATE_FORMAT(fu_ajk_spak_form.tgl_periksa,'%Y-%m-%d') between '".$_REQUEST ['tgl1']."'  and '".$_REQUEST ['tgl2']."' and dokter_pemeriksa = '".$_REQUEST['dk']."'

						group BY
						user_mobile.ID,
						user_mobile.namalengkap,
						user_mobile.norek,
						user_mobile.atas_nama,
						user_mobile.bank_rek,
						fu_ajk_cabang.`name`,
						fu_ajk_spak_form.tgl_periksa,
						fu_ajk_regional.`name`,
						fu_ajk_costumer.`name`) aa
						INNER JOIN fu_ajk_dokter_fee ON fu_ajk_dokter_fee.id_user_mobile = aa.id
						where DATE_FORMAT(aa.tgl_periksa,'%Y-%m-%d') between fu_ajk_dokter_fee.tgl_mulai_fee and fu_ajk_dokter_fee.tgl_akhir_fee
						group by
						aa.ID,
						aa.namalengkap,
						aa.norek,
						aa.atas_nama,
						aa.bank_rek,
						aa.nama_cost,
						aa.nama_regional,
						aa.nama_cabang,
						DATE_FORMAT(aa.tgl_periksa,'%M %Y'),
						fu_ajk_dokter_fee.fee_dokter
						ORDER BY DATE_FORMAT(aa.tgl_periksa,'%Y%m'), aa.namalengkap");

        while ($jangkrik = mysql_fetch_array($kucing)) {

            //Aktif | Proses | Approve | Preapproval | Batal | Tolak | Realisasi
            $worksheet1->writeNumber($baris, 0, ++$no);
            $worksheet1->writeString($baris, 1, $jangkrik['nama_cost']);
            $worksheet1->writeString($baris, 2, $jangkrik['nama_regional']);
            $worksheet1->writeString($baris, 3, $jangkrik['nama_cabang']);
            $worksheet1->writeString($baris, 4, $jangkrik['namalengkap']);
            $worksheet1->writeString($baris, 5, $jangkrik['tgl']);
            $worksheet1->writeNumber($baris, 6, $jangkrik['jml_pemeriksaan']);
            $worksheet1->writeNumber($baris, 7, $jangkrik['fee_dokter']);
            $worksheet1->writeNumber($baris, 8, $jangkrik['total']);
            $baris++;
        }

        $workbook->close();
        ;
    break;

    case "eL_FeePemeriksaanDokter":

        function HeaderingExcel($filename)
        {
            header("Content-type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=$filename");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
            header("Pragma: public");
        }
        HeaderingExcel('DATA FEE DOKTER.xls');
        $workbook = new Workbook("");
        $worksheet1 =& $workbook->add_worksheet('REKAP DATA FEE DOKTER');
        $format =& $workbook->add_format();
        $format->setAlign('vcenter');	$format->setAlign('center');	$format->setColor('white');	$format->setBold();	$format->setPattern();	$format->setFgColor('green');
        $fjudul =& $workbook->add_format();	$fjudul->setAlign('vcenter');	$fjudul->setAlign('center');	$fjudul->setBold();

        $worksheet1->setMerge(0, 0, 0, 7);	$worksheet1->writeString(0, 0, "REKAP DATA FEE DOKTER", $fjudul, 1);
        $worksheet1->setMerge(1, 0, 1, 7);	$worksheet1->writeString(1, 0, "TANGGAL PERIKSA "._convertDate($_REQUEST['tgl1'])." s/d "._convertDate($_REQUEST['tgl2'])."", $fjudul);

        $worksheet1->setRow(4, 15);
        $worksheet1->setColumn(4, 0, 5);	$worksheet1->writeString(4, 0, "NO", $format);
        $worksheet1->setColumn(4, 1, 15);	$worksheet1->writeString(4, 1, "NAMA DOKTER", $format);
        $worksheet1->setColumn(4, 2, 15);	$worksheet1->writeString(4, 2, "JUMLAH SPK", $format);
        $worksheet1->setColumn(4, 3, 15);	$worksheet1->writeString(4, 3, "BIAYA", $format);
        $worksheet1->setColumn(4, 5, 15);	$worksheet1->writeString(4, 4, "TOTAL", $format);
        $worksheet1->setColumn(4, 5, 15);	$worksheet1->writeString(4, 5, "PERIODE PERIKSA", $format);
        $worksheet1->setColumn(4, 5, 15);	$worksheet1->writeString(4, 6, "ATAS NAMA", $format);
        $worksheet1->setColumn(4, 5, 15);	$worksheet1->writeString(4, 7, "NO REKENING", $format);
        $worksheet1->setColumn(4, 5, 15);	$worksheet1->writeString(4, 8, "BANK", $format);

        $baris = 5;
        $sql="";

        $kucing = mysql_query("SELECT aa.ID,
					aa.namalengkap,
					aa.norek,
					aa.atas_nama,
					aa.bank_rek,
					aa.nama_cost,
					aa.nama_regional,
					aa.nama_cabang,
					DATE_FORMAT(aa.tgl_periksa,'%M %Y') as tgl,
					sum(aa.jml_pemeriksaan) as jml_pemeriksaan,
					fu_ajk_dokter_fee.fee_dokter,
					sum(aa.jml_pemeriksaan) * fu_ajk_dokter_fee.fee_dokter as total
					FROM (
					SELECT *
					FROM(
					SELECT	user_mobile.ID,
									user_mobile.namalengkap,
									user_mobile.norek,
									user_mobile.atas_nama,
									user_mobile.bank_rek,
									fu_ajk_costumer.`name` as nama_cost,
									fu_ajk_regional.`name` as nama_regional,
									fu_ajk_cabang.`name` as nama_cabang,
									fu_ajk_spak_form.tgl_periksa,
									Count(fu_ajk_cabang.`name`) AS jml_pemeriksaan
					FROM
					fu_ajk_spak_form
					INNER JOIN fu_ajk_spak ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
					LEFT JOIN user_mobile ON fu_ajk_spak_form.dokter_pemeriksa = user_mobile.id
					LEFT JOIN fu_ajk_cabang ON user_mobile.cabang = fu_ajk_cabang.id
					LEFT JOIN fu_ajk_regional ON fu_ajk_cabang.id_reg = fu_ajk_regional.id
					LEFT JOIN fu_ajk_costumer ON fu_ajk_regional.id_cost = fu_ajk_costumer.id
					where user_mobile.`type`='Dokter' and  fu_ajk_spak.del IS NULL AND fu_ajk_spak_form.del IS NULL
					and DATE_FORMAT(fu_ajk_spak_form.tgl_periksa,'%Y-%m-%d') between '".$_GET['tgl1']."'  and '".$_GET['tgl2']."'
					and (nopermohonan is NULL or nopermohonan = '')
					group BY
					user_mobile.ID,
					user_mobile.namalengkap,
					user_mobile.norek,
					user_mobile.atas_nama,
					user_mobile.bank_rek,
					fu_ajk_cabang.`name`,
					fu_ajk_spak_form.tgl_periksa,
					fu_ajk_regional.`name`,
					fu_ajk_costumer.`name`

					UNION ALL

					SELECT	user_mobile.ID,
									user_mobile.namalengkap,
									user_mobile.norek,
									user_mobile.atas_nama,
									user_mobile.bank_rek,
									fu_ajk_costumer.`name` as nama_cost,
									fu_ajk_regional.`name` as nama_regional,
									fu_ajk_cabang.`name` as nama_cabang,
									fu_ajk_spak_form.tgl_periksa,
									1 AS jml_pemeriksaan
					FROM
					fu_ajk_spak_form
					INNER JOIN fu_ajk_spak ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
					LEFT JOIN user_mobile ON fu_ajk_spak_form.dokter_pemeriksa = user_mobile.id
					LEFT JOIN fu_ajk_cabang ON user_mobile.cabang = fu_ajk_cabang.id
					LEFT JOIN fu_ajk_regional ON fu_ajk_cabang.id_reg = fu_ajk_regional.id
					LEFT JOIN fu_ajk_costumer ON fu_ajk_regional.id_cost = fu_ajk_costumer.id
					where user_mobile.`type`='Dokter' and  fu_ajk_spak.del IS NULL AND fu_ajk_spak_form.del IS NULL
					and DATE_FORMAT(fu_ajk_spak_form.tgl_periksa,'%Y-%m-%d') between '".$_GET['tgl1']."'  and '".$_GET['tgl2']."'

					group BY
					user_mobile.ID,
					user_mobile.namalengkap,
					user_mobile.norek,
					user_mobile.atas_nama,
					user_mobile.bank_rek,
					fu_ajk_cabang.`name`,
					fu_ajk_spak_form.tgl_periksa,
					fu_ajk_regional.`name`,
					fu_ajk_costumer.`name`,nopermohonan HAVING nopermohonan > 1
					)as temp)aa
					LEFT JOIN fu_ajk_dokter_fee ON fu_ajk_dokter_fee.id_user_mobile = aa.id
					and DATE_FORMAT(aa.tgl_periksa,'%Y-%m-%d') between fu_ajk_dokter_fee.tgl_mulai_fee and fu_ajk_dokter_fee.tgl_akhir_fee
					group by
					aa.ID,
					aa.namalengkap,
					aa.norek,
					aa.atas_nama,
					aa.bank_rek,
					aa.nama_cost,
					aa.nama_regional,
					aa.nama_cabang,
					DATE_FORMAT(aa.tgl_periksa,'%M %Y'),
					fu_ajk_dokter_fee.fee_dokter
					ORDER BY DATE_FORMAT(aa.tgl_periksa,'%Y%m'), aa.namalengkap");
        $totalku=0;
        $jmlku=0;
        $feeku=0;
        while ($jangkrik = mysql_fetch_array($kucing)) {
            $worksheet1->writeNumber($baris, 0, ++$no);
            $worksheet1->writeString($baris, 1, $jangkrik['namalengkap']);
            $worksheet1->writeNumber($baris, 2, $jangkrik['jml_pemeriksaan']);
            $worksheet1->writeNumber($baris, 3, $jangkrik['fee_dokter']);
            $worksheet1->writeNumber($baris, 4, $jangkrik['total']);
            $worksheet1->writeString($baris, 5, $jangkrik['tgl']);
            $worksheet1->writeString($baris, 6, $jangkrik['atas_nama']);
            $worksheet1->writeString($baris, 7, $jangkrik['norek']);
            $worksheet1->writeString($baris, 8, $jangkrik['bank_rek']);

            $totalku=$totalku+$jangkrik['total'];
            $jmlku=$jmlku+$jangkrik['jml_pemeriksaan'];
            $feeku=$feeku+$jangkrik['fee_dokter'];

            $baris++;
        }

        $worksheet1->writeString($baris, 0, '');
        $worksheet1->writeString($baris, 1, '');
        $worksheet1->writeNumber($baris, 2, $jmlku);
        $worksheet1->writeNumber($baris, 3, $feeku);
        $worksheet1->writeNumber($baris, 4, $totalku);
        $worksheet1->writeString($baris, 5, '');
        $worksheet1->writeString($baris, 6, '');
        $worksheet1->writeString($baris, 7, '');
        $worksheet1->writeString($baris, 8, '');
        $workbook->close();
        ;
        break;

    case "eL_User_SPK":

            function HeaderingExcel($filename)
            {
                header("Content-type: application/vnd.ms-excel");
                header("Content-Disposition: attachment; filename=$filename");
                header("Expires: 0");
                header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
                header("Pragma: public");
            }
            HeaderingExcel('DATA REKAP USER SPK.xls');
            $workbook = new Workbook("");
            $worksheet1 =& $workbook->add_worksheet('REKAP DATA USER SPK');
            $format =& $workbook->add_format();
            $format->setAlign('vcenter');	$format->setAlign('center');	$format->setColor('white');	$format->setBold();	$format->setPattern();	$format->setFgColor('green');
            $fjudul =& $workbook->add_format();	$fjudul->setAlign('vcenter');	$fjudul->setAlign('center');	$fjudul->setBold();

            $worksheet1->setMerge(0, 0, 0, 7);	$worksheet1->writeString(0, 0, "REKAP USER SPK", $fjudul, 1);
            $worksheet1->setMerge(1, 0, 1, 7);	$worksheet1->writeString(1, 0, "TANGGAL INPUT "._convertDate($_REQUEST['tgl1'])." s/d "._convertDate($_REQUEST['tgl2'])."", $fjudul);

            $worksheet1->setRow(4, 15);
            $worksheet1->setColumn(4, 0, 5);	$worksheet1->writeString(4, 0, "NO", $format);
            $worksheet1->setColumn(4, 1, 15);	$worksheet1->writeString(4, 1, "CABANG", $format);
            $worksheet1->setColumn(4, 2, 15);	$worksheet1->writeString(4, 2, "USER INPUT", $format);
            $worksheet1->setColumn(4, 3, 15);	$worksheet1->writeNumber(4, 3, "JUMLAH DATA", $format);

            $baris = 5;
            $sql="";
            if ($_REQUEST['idReg']!="") {
                $sql="and fu_ajk_regional.id=".$_REQUEST['idReg'];
            }

            if ($_REQUEST['idCab']) {
                $sql=$sql."and fu_ajk_cabang.id=".$_REQUEST['idCab'];
            }

            if ($_REQUEST['tipe']=='Dokter') {
                $kucing=mysql_query("SELECT
						fu_ajk_costumer.`name` AS nama_cost,
						fu_ajk_regional.`name` AS nama_regional,
						ifnull(fu_ajk_cabang.`name`,aa.cabang) AS nama_cabang,
						aa.namalengkap,
						COUNT(aa.id) AS jml
						FROM
						(
						SELECT
						fu_ajk_spak_form.id,
						IFNULL(user_mobile.cabang,4) AS cabang,
						IF(`pengguna`.`nm_lengkap` IS NOT NULL, `pengguna`.`nm_lengkap`,`user_mobile`.`namalengkap`) AS namalengkap,
						fu_ajk_spak_form.input_date
						FROM
						    `fu_ajk_spak_form`
						    LEFT JOIN `user_mobile`
						        ON (`fu_ajk_spak_form`.`dokter_pemeriksa` = `user_mobile`.`id`)
						    LEFT JOIN `pengguna`
						        ON (`fu_ajk_spak_form`.`input_by` = `pengguna`.`nm_user`)
						WHERE `user_mobile`.`namalengkap` IS NOT NULL
						) aa
						LEFT JOIN fu_ajk_cabang ON aa.cabang = fu_ajk_cabang.id
						LEFT JOIN fu_ajk_regional ON fu_ajk_cabang.id_reg = fu_ajk_regional.id
						LEFT JOIN fu_ajk_costumer ON fu_ajk_regional.id_cost = fu_ajk_costumer.id
						where  fu_ajk_costumer.id=".$_REQUEST['idCost']."
						and aa.input_date between '".$_REQUEST ['tgl1']."'  and '".$_REQUEST ['tgl2']."' ".$sql."
						GROUP BY
						fu_ajk_costumer.`name`,
						fu_ajk_regional.`name`,
						fu_ajk_cabang.`name`,
						aa.namalengkap");
            } elseif ($_REQUEST['tipe']=='Marketing') {
                $kucing=mysql_query("SELECT
						fu_ajk_costumer.`name` AS nama_cost,
						fu_ajk_regional.`name` AS nama_regional,
						ifnull(fu_ajk_cabang.`name`,aa.cabang) AS nama_cabang,
						aa.namalengkap,
						COUNT(aa.id) AS jml
						FROM
						(SELECT
						fu_ajk_spak.id,
						IFNULL(user_mobile.cabang,4) AS cabang,
						IF(`pengguna`.`nm_lengkap` IS NOT NULL, `pengguna`.`nm_lengkap`,`user_mobile`.`namalengkap`) AS namalengkap,
						fu_ajk_spak.input_date
						FROM
						    `fu_ajk_spak`
						    LEFT JOIN `user_mobile`
						        ON (`fu_ajk_spak`.`input_by` = `user_mobile`.`id`)
						    LEFT JOIN `pengguna`
						        ON (`fu_ajk_spak`.`input_by` = `pengguna`.`nm_user`)
						) aa
						LEFT JOIN fu_ajk_cabang ON aa.cabang = fu_ajk_cabang.id
						LEFT JOIN fu_ajk_regional ON fu_ajk_cabang.id_reg = fu_ajk_regional.id
						LEFT JOIN fu_ajk_costumer ON fu_ajk_regional.id_cost = fu_ajk_costumer.id
						WHERE  fu_ajk_costumer.id=".$_REQUEST['idCost']." AND aa.input_date BETWEEN '".$_REQUEST['tgl1']."' AND '".$_REQUEST['tgl2']."' ".$sql."
						GROUP BY
						fu_ajk_costumer.`name`,
						fu_ajk_regional.`name`,
						fu_ajk_cabang.`name`,
						aa.namalengkap");
            }
            /*$kucing = mysql_query("SELECT
                        fu_ajk_costumer.`name` as nama_cost,
                        fu_ajk_regional.`name` as nama_regional,
                        fu_ajk_cabang.`name` as nama_cabang,
                        ifnull(user_mobile.namalengkap,fu_ajk_spak.input_by) as namalengkap,
                        count(fu_ajk_spak.spak) as jml
                        FROM
                        user_mobile
                        LEFT JOIN fu_ajk_spak ON fu_ajk_spak.input_by = user_mobile.id
                        INNER JOIN fu_ajk_cabang ON user_mobile.cabang = fu_ajk_cabang.id
                        INNER JOIN fu_ajk_regional ON fu_ajk_cabang.id_reg = fu_ajk_regional.id
                        INNER JOIN fu_ajk_costumer ON fu_ajk_regional.id_cost = fu_ajk_costumer.id
                        where fu_ajk_spak.input_date between '".$_REQUEST ['tgl1']."'  and '".$_REQUEST ['tgl2']."'
                        and fu_ajk_costumer.id=".$_REQUEST['idCost']." ".$sql."

                        group by
                        fu_ajk_costumer.`name`,
                        fu_ajk_regional.`name`,
                        fu_ajk_cabang.`name`,
                        user_mobile.namalengkap");*/
            while ($jangkrik = mysql_fetch_array($kucing)) {

                //Aktif | Proses | Approve | Preapproval | Batal | Tolak | Realisasi
                $worksheet1->writeNumber($baris, 0, ++$no);
                $worksheet1->writeString($baris, 1, $jangkrik['nama_cabang']);
                $worksheet1->writeString($baris, 2, $jangkrik['namalengkap']);
                $worksheet1->writeNumber($baris, 3, $jangkrik['jml']);
                $baris++;
            }

            $workbook->close();
            ;
            break;

    case "klaim_liable":

        function bulan_convert($tanggal)
        {
            $dateku=str_replace("01-01-1970", "", date('d-m-Y', strtotime($tanggal)));
            if ($dateku!=="") {
                $tgl=explode("-", $dateku);

                if ($tgl['1']=='01') {
                    $ls_namabulan =  'Jan';
                } elseif ($tgl['1']=='02') {
                    $ls_namabulan =  'Feb';
                } elseif ($tgl['1']=='03') {
                    $ls_namabulan =  'Mar';
                } elseif ($tgl['1']=='04') {
                    $ls_namabulan =  'Apr';
                } elseif ($tgl['1']=='05') {
                    $ls_namabulan =  'Mei';
                } elseif ($tgl['1']=='06') {
                    $ls_namabulan =  'Jun';
                } elseif ($tgl['1']=='07') {
                    $ls_namabulan =  'Jul';
                } elseif ($tgl['1']=='08') {
                    $ls_namabulan =  'Agt';
                } elseif ($tgl['1']=='09') {
                    $ls_namabulan =  'Sep';
                } elseif ($tgl['1']=='10') {
                    $ls_namabulan =  'Okt';
                } elseif ($tgl['1']=='11') {
                    $ls_namabulan =  'Nov';
                } elseif ($tgl['1']=='12') {
                    $ls_namabulan =  'Des';
                }

                return $tgl['0'].'-'.$ls_namabulan.'-'.$tgl['2'];
            }
        }
                function HeaderingExcel($filename)
                {
                    header("Content-type: application/vnd.ms-excel");
                    header("Content-Disposition: attachment; filename=$filename");
                    header("Expires: 0");
                    header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
                    header("Pragma: public");
                }
                HeaderingExcel('DATA_KLAIM_LIABLE.xls');
                $workbook = new Workbook("");
                $worksheet1 =& $workbook->add_worksheet('DATA KLAIM LIABLE');
                $format =& $workbook->add_format();
                $format->setAlign('vcenter');	$format->setAlign('center');	$format->setColor('white');	$format->setBold();	$format->setPattern();	$format->setFgColor('green');
                $fjudul =& $workbook->add_format();	$fjudul->setAlign('vcenter');	$fjudul->setAlign('center');	$fjudul->setBold();

                $worksheet1->setMerge(0, 0, 0, 23);	$worksheet1->writeString(0, 0, "DATA KLAIM LIABLE ".strtoupper($_REQUEST['status_klaim']), $fjudul, 1);

                $q_tglklaim='';
                if (!empty($_REQUEST['tgl1'])) {
                    $worksheet1->setMerge(1, 0, 1, 23);
                    $worksheet1->writeString(1, 0, "TANGGAL LAPOR "._convertDate($_REQUEST['tgl1'])." s/d "._convertDate($_REQUEST['tgl2'])."", $fjudul);
                }


                $q_dol='';
                if (!empty($_REQUEST['tgl3'])) {
                    $worksheet1->setMerge(2, 0, 2, 23);
                    $worksheet1->writeString(1, 0, "DOL "._convertDate($_REQUEST['tgl3'])." s/d "._convertDate($_REQUEST['tgl4'])."", $fjudul);
                }


                $worksheet1->setRow(4, 15);
                $worksheet1->setColumn(4, 0, 5);	$worksheet1->writeString(4, 0, "No", $format);
                $worksheet1->setColumn(4, 1, 5);	$worksheet1->writeString(4, 1, "Nomor Urut", $format);
                $worksheet1->setColumn(4, 2, 5);	$worksheet1->writeString(4, 2, "Bukopin Cabang", $format);
                $worksheet1->setColumn(4, 3, 5);	$worksheet1->writeString(4, 3, "Cover Asuransi", $format);
                $worksheet1->setColumn(4, 4, 5);	$worksheet1->writeString(4, 4, "Kategori", $format);
                $worksheet1->setColumn(4, 5, 5);	$worksheet1->writeString(4, 5, "Produk", $format);
                $worksheet1->setColumn(4, 6, 5);	$worksheet1->writeString(4, 6, "ID Peserta", $format);
                $worksheet1->setColumn(4, 7, 5);	$worksheet1->writeString(4, 7, "Nama Debitur", $format);
                $worksheet1->setColumn(4, 8, 5);	$worksheet1->writeString(4, 8, "Tgl Lahir", $format);
                $worksheet1->setColumn(4, 9, 5);	$worksheet1->writeString(4, 9, "Usia", $format);
                $worksheet1->setColumn(4, 10, 5);	$worksheet1->writeString(4, 10, "Plafond Kredit ", $format);
                $worksheet1->setColumn(4, 11, 5);	$worksheet1->writeString(4, 11, "Tuntutan Klaim ", $format);
                $worksheet1->setColumn(4, 12, 5);	$worksheet1->writeString(4, 12, "Tgl Akad", $format);
                $worksheet1->setColumn(4, 13, 5);	$worksheet1->writeString(4, 13, "J.Wkt (Th.)", $format);
                $worksheet1->setColumn(4, 14, 5);	$worksheet1->writeString(4, 14, "DOL", $format);
                $worksheet1->setColumn(4, 15, 5);	$worksheet1->writeString(4, 15, "Akad s/d DOL (hari)", $format);
                $worksheet1->setColumn(4, 16, 5);	$worksheet1->writeString(4, 16, "Tgl. Terima Laporan", $format);
                $worksheet1->setColumn(4, 17, 5);	$worksheet1->writeString(4, 17, "Kelengkapan Dokumen Klaim", $format);
                $worksheet1->setColumn(4, 18, 5);	$worksheet1->writeString(4, 18, "Tgl Lapor Klaim", $format);
                $worksheet1->setColumn(4, 19, 5);	$worksheet1->writeString(4, 19, "Tanggal Status Lengkap", $format);
                $worksheet1->setColumn(4, 20, 5);	$worksheet1->writeString(4, 20, "Status Klaim", $format);
                $worksheet1->setColumn(4, 21, 5);	$worksheet1->writeString(4, 21, "Asuransi Bayar (Rp.)", $format);
                $worksheet1->setColumn(4, 22, 5);	$worksheet1->writeString(4, 22, "Tgl Bayar Dari Asuransi", $format);
                $worksheet1->setColumn(4, 23, 5);	$worksheet1->writeString(4, 23, "Bayar Ke Bank (Rp.)", $format);
                $worksheet1->setColumn(4, 24, 5);	$worksheet1->writeString(4, 24, "Tgl Bayar Ke Client", $format);
                $worksheet1->setColumn(4, 25, 5);	$worksheet1->writeString(4, 25, "Selisih Bayar (Rp.)", $format);
                $worksheet1->setColumn(4, 26, 5);	$worksheet1->writeString(4, 26, "Kol", $format);

                $baris = 5;
                $sql="";

                $q_asuransi='';
                if ($_REQUEST['id_asuransi']!="") {
                    $q_asuransi=" and fu_ajk_asuransi.id=".$_REQUEST['id_asuransi'];
                }

                $q_status="";
                if (!empty($_REQUEST['status_klaim'])) {
                    $q_status=" and if(`id_klaim_status`=6,'Ditolak',
								if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
								'Dokumen Belum Lengkap'))='".$_REQUEST['status_klaim']."' ";
                }


                $q_tglklaim='';
                if (!empty($_REQUEST['tgl1'])) {
                    $q_tglklaim="and fu_ajk_cn.approve_date between '".$_REQUEST ['tgl1']."' and '".$_REQUEST ['tgl2']."'";
                }


                $q_dol='';
                if (!empty($_REQUEST['tgl3'])) {
                    $q_dol="and fu_ajk_klaim.tgl_klaim between '".$_REQUEST ['tgl3']."' and '".$_REQUEST ['tgl4']."'";
                }


                $q_kol='';
                if (!empty($_REQUEST['kol'])) {
                    $q_kol="and
						IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',

									IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
									IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
									IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
									,
									IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
									IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
									IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5'))))=".$_REQUEST['kol'];
                }
        $kucing = mysql_query("SELECT
								CONCAT(DATE_FORMAT(fu_ajk_cn.approve_date,'%Y%m'),'/',fu_ajk_klaim.id) AS klaim_id,
								fu_ajk_klaim.no_urut_klaim,
								fu_ajk_cn.id_cabang,
								fu_ajk_grupproduk.nmproduk AS mitra,
								fu_ajk_asuransi.`name`,
								fu_ajk_polis.nmproduk,
								IF(fu_ajk_polis.`typeproduk`='NON SPK','Percepatan','Reguler') AS kategori,
								fu_ajk_peserta.id_peserta,
								fu_ajk_peserta.nama,
								fu_ajk_peserta.tgl_lahir,
								fu_ajk_peserta.usia,
								fu_ajk_peserta.kredit_jumlah,
								fu_ajk_cn.total_claim,
								fu_ajk_cn.tuntutan_klaim,
								fu_ajk_peserta.kredit_tgl,
								ROUND(IF(fu_ajk_peserta.type_data='SPK',fu_ajk_peserta.kredit_tenor*12,fu_ajk_peserta.kredit_tenor)/12) AS kredit_tenor,
								fu_ajk_klaim.tgl_klaim AS dol,
								DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl) AS akad_dol,
								IF(fu_ajk_klaim.tgl_document='0000-00-00',NULL,fu_ajk_klaim.tgl_document) AS tgl_document,
								DATEDIFF(fu_ajk_cn.approve_date,fu_ajk_klaim.tgl_document) AS lama_terima_laporan,
								'' AS tgl_update_klaim,
								IF(fu_ajk_klaim.tgl_lapor_klaim='0000-00-00',NULL,fu_ajk_klaim.tgl_lapor_klaim) AS tgl_lapor_klaim,
								fu_ajk_cn.keterangan AS kelengkapan_dokumen,
								IF(fu_ajk_klaim.tgl_document_lengkap='0000-00-00',NULL,fu_ajk_klaim.tgl_document_lengkap) AS tgl_document_lengkap,
								IF(fu_ajk_polis.`typeproduk`='NON SPK',DATE_ADD(fu_ajk_klaim.tgl_document_lengkap,INTERVAL 28 DAY),DATE_ADD(fu_ajk_klaim.tgl_document_lengkap,INTERVAL 14 DAY)) as due_date,
								IF(fu_ajk_klaim.tgl_kirim_dokumen='0000-00-00',NULL,fu_ajk_klaim.tgl_kirim_dokumen) AS tgl_kirim_dokumen,
								CURRENT_DATE() AS today,
								IF(fu_ajk_klaim.tgl_kirim_dokumen<>'0000-00-00' OR fu_ajk_klaim.tgl_kirim_dokumen IS NOT NULL,DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_kirim_dokumen),'') AS status_release,
								/*fu_ajk_spak.ext_premi*/ '' AS EM,
								/*fu_ajk_spak.ket_ext*/ '' AS keterangan_EM,
								IF(fu_ajk_klaim.tgl_investigasi='0000-00-00',NULL,fu_ajk_klaim.tgl_investigasi) AS tgl_investigasi,
								fu_ajk_klaim.diagnosa AS hasil_investigasi,
								fu_ajk_namapenyakit.namapenyakit AS penyebab_meinggal,
								fu_ajk_cn.policy_liability AS polis_liability,
								fu_ajk_pembayaran_status.pembayaran_status,
								fu_ajk_klaim_status.status_klaim,
								if(`id_klaim_status`=6,'Ditolak',
								if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
								'Dokumen Belum Lengkap')) AS keterangan,
								'' AS keterangan_asuransi,
								fu_ajk_cn.total_bayar_asuransi,
								'' AS ref_bayar_asuransi,
								fu_ajk_cn.tgl_bayar_asuransi,
								'' AS nilai_pengajuan_keuangan,
								fu_ajk_cn.total_claim  AS bayar_ke_bank,
								'' AS ref_pembayaran_ke_bank,
								fu_ajk_cn.tgl_byr_claim AS tgl_bayar_ke_client,
								fu_ajk_cn.total_bayar_asuransi-fu_ajk_cn.total_claim AS selisih,
								IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',
								IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
								IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 AND DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
								IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
								,
								IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
								IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
								IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol
								FROM
								fu_ajk_cn
								INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
								INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
								INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
								INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
								LEFT JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
								LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id=fu_ajk_klaim.id_klaim_status
								LEFT JOIN fu_ajk_grupproduk ON fu_ajk_grupproduk.id=fu_ajk_peserta.nama_mitra
								/*LEFT JOIN fu_ajk_spak ON fu_ajk_peserta.spaj=fu_ajk_spak.spak*/
								LEFT JOIN fu_ajk_namapenyakit ON fu_ajk_namapenyakit.id=fu_ajk_klaim.sebab_meninggal
								LEFT JOIN fu_ajk_pembayaran_status ON fu_ajk_pembayaran_status.id=fu_ajk_cn.status_bayar
								where  fu_ajk_cn.del is null  and fu_ajk_cn.type_claim='Death'
								".$q_tglklaim."
								".$q_dol."
								".$q_status."
								".$q_kol."
								".$q_asuransi."
								and fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']."
								and fu_ajk_cn.confirm_claim !='Pending'
								and fu_ajk_cn.policy_liability='LIABLE'
								order by
								fu_ajk_peserta.id");
                while ($kucingkawin = mysql_fetch_array($kucing)) {
                    $worksheet1->writeNumber($baris, 0, ++$no);
                    $worksheet1->writeString($baris, 1, $kucingkawin['no_urut_klaim']);
                    $worksheet1->writeString($baris, 2, $kucingkawin['id_cabang']);
                    $worksheet1->writeString($baris, 3, $kucingkawin['name']);
                    $worksheet1->writeString($baris, 4, $kucingkawin['kategori']);
                    $worksheet1->writeString($baris, 5, $kucingkawin['nmproduk']);
                    $worksheet1->writeString($baris, 6, $kucingkawin['id_peserta']);
                    $worksheet1->writeString($baris, 7, $kucingkawin['nama']);
                    $worksheet1->writeString($baris, 8, $kucingkawin['tgl_lahir']);
                    $worksheet1->writeNumber($baris, 9, $kucingkawin['usia']);
                    $worksheet1->writeNumber($baris, 10, $kucingkawin['kredit_jumlah']);
                    $worksheet1->writeNumber($baris, 11, $kucingkawin['tuntutan_klaim']);
                    $worksheet1->writeString($baris, 12, $kucingkawin['kredit_tgl']);
                    $worksheet1->writeNumber($baris, 13, $kucingkawin['kredit_tenor']);
                    $worksheet1->writeString($baris, 14, $kucingkawin['dol']);
                    $worksheet1->writeString($baris, 15, $kucingkawin['akad_dol']);
                    $worksheet1->writeString($baris, 16, $kucingkawin['tgl_lapor_klaim']);
                    $worksheet1->writeString($baris, 17, $kucingkawin['keterangan']);
                    $worksheet1->writeString($baris, 18, $kucingkawin['input_date']);
                    $worksheet1->writeString($baris, 19, $kucingkawin['tgl_document_lengkap']);
                    $worksheet1->writeString($baris, 20, $kucingkawin['status_klaim']);
                    $worksheet1->writeNumber($baris, 21, $kucingkawin['total_bayar_asuransi']);
                    $worksheet1->writeString($baris, 22, $kucingkawin['tgl_bayar_asuransi']);
                    $worksheet1->writeNumber($baris, 23, $kucingkawin['bayar_ke_bank']);
                    $worksheet1->writeString($baris, 24, $kucingkawin['tgl_bayar_ke_client']);
                    $worksheet1->writeString($baris, 25, $kucingkawin['selisih']);
                    $worksheet1->writeNumber($baris, 26, $kucingkawin['kol']);

                    $baris++;
                }

                $workbook->close();
                ;
                break;

case "klaim_nonliable":

                    function HeaderingExcel($filename)
                    {
                        header("Content-type: application/vnd.ms-excel");
                        header("Content-Disposition: attachment; filename=$filename");
                        header("Expires: 0");
                        header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
                        header("Pragma: public");
                    }
                    HeaderingExcel('DATA KLAIM NONLIABLE.xls');
                    $workbook = new Workbook("");
                    $worksheet1 =& $workbook->add_worksheet('DATA KLAIM NONLIABLE');
                    $format =& $workbook->add_format();
                    $format->setAlign('vcenter');	$format->setAlign('center');	$format->setColor('white');	$format->setBold();	$format->setPattern();	$format->setFgColor('green');
                    $fjudul =& $workbook->add_format();	$fjudul->setAlign('vcenter');	$fjudul->setAlign('center');	$fjudul->setBold();

                    $worksheet1->setMerge(0, 0, 0, 22);	$worksheet1->writeString(0, 0, "DATA KLAIM NONLIABLE ".strtoupper($_REQUEST['status_klaim']), $fjudul, 1);

                    $q_tglklaim='';
                    if (!empty($_REQUEST['tgl1'])) {
                        $worksheet1->setMerge(1, 0, 1, 22);
                        $worksheet1->writeString(1, 0, "TANGGAL LAPOR "._convertDate($_REQUEST['tgl1'])." s/d "._convertDate($_REQUEST['tgl2'])."", $fjudul);
                    }


                    $q_dol='';
                    if (!empty($_REQUEST['tgl3'])) {
                        $worksheet1->setMerge(2, 0, 2, 22);
                        $worksheet1->writeString(1, 0, "DOL "._convertDate($_REQUEST['tgl3'])." s/d "._convertDate($_REQUEST['tgl4'])."", $fjudul);
                    }


                    $worksheet1->setRow(4, 15);
                    $worksheet1->setColumn(4, 0, 5);	$worksheet1->writeString(4, 0, "No", $format);
                    $worksheet1->setColumn(4, 1, 5);	$worksheet1->writeString(4, 1, "No Urut", $format);
                    $worksheet1->setColumn(4, 2, 5);	$worksheet1->writeString(4, 2, "Bukopin Cabang", $format);
                    $worksheet1->setColumn(4, 3, 5);	$worksheet1->writeString(4, 3, "Cover Asuransi", $format);
                    $worksheet1->setColumn(4, 4, 5);	$worksheet1->writeString(4, 4, "Kategori", $format);
                    $worksheet1->setColumn(4, 5, 5);	$worksheet1->writeString(4, 5, "Produk", $format);
                    $worksheet1->setColumn(4, 6, 5);	$worksheet1->writeString(4, 6, "Nama Debitur", $format);
                    $worksheet1->setColumn(4, 7, 5);	$worksheet1->writeString(4, 7, "Tgl Lahir", $format);
                    $worksheet1->setColumn(4, 8, 5);	$worksheet1->writeString(4, 8, "Usia", $format);
                    $worksheet1->setColumn(4, 9, 5);	$worksheet1->writeString(4, 9, "Plafond Kredit ", $format);
                    $worksheet1->setColumn(4, 10, 5);	$worksheet1->writeString(4, 10, "Tuntutan Klaim ", $format);
                    $worksheet1->setColumn(4, 11, 5);	$worksheet1->writeString(4, 11, "Tgl Akad", $format);
                    $worksheet1->setColumn(4, 12, 5);	$worksheet1->writeString(4, 12, "J.Wkt (Th.)", $format);
                    $worksheet1->setColumn(4, 13, 5);	$worksheet1->writeString(4, 13, "DOL", $format);
                    $worksheet1->setColumn(4, 14, 5);	$worksheet1->writeString(4, 14, "Akad s/d DOL (hari)", $format);
                    $worksheet1->setColumn(4, 15, 5);	$worksheet1->writeString(4, 15, "Tgl. Laporan Asuransi", $format);
                    $worksheet1->setColumn(4, 16, 5);	$worksheet1->writeString(4, 16, "Kelengkapan Dokumen Klaim", $format);
                    $worksheet1->setColumn(4, 17, 5);	$worksheet1->writeString(4, 17, "Tanggal Status Lengkap", $format);
                    $worksheet1->setColumn(4, 18, 5);	$worksheet1->writeString(4, 18, "Status Klaim", $format);
                    $worksheet1->setColumn(4, 19, 5);	$worksheet1->writeString(4, 19, "Tgl Lapor Klaim", $format);
                    $worksheet1->setColumn(4, 20, 5);	$worksheet1->writeString(4, 20, "Asuransi Bayar (Rp.)", $format);
                    $worksheet1->setColumn(4, 21, 5);	$worksheet1->writeString(4, 21, "Tgl Bayar Dari Asuransi", $format);
                    $worksheet1->setColumn(4, 22, 5);	$worksheet1->writeString(4, 22, "Bayar Ke Bank (Rp.)", $format);
                    $worksheet1->setColumn(4, 23, 5);	$worksheet1->writeString(4, 23, "Tgl Bayar Ke Client", $format);
                    $worksheet1->setColumn(4, 24, 5);	$worksheet1->writeString(4, 24, "Selisih Bayar (Rp.)", $format);
                    $worksheet1->setColumn(4, 25, 5);	$worksheet1->writeString(4, 25, "Kol", $format);

                    $baris = 5;
                    $sql="";

                    $q_asuransi='';
                    if ($_REQUEST['id_asuransi']!="") {
                        $q_asuransi=" and fu_ajk_asuransi.id=".$_REQUEST['id_asuransi'];
                    }

                    $q_status="";
                    if (!empty($_REQUEST['status_klaim'])) {
                        $q_status=" and if(`id_klaim_status`=6,'Ditolak',
							if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
							'Dokumen Belum Lengkap'))='".$_REQUEST['status_klaim']."' ";
                    }


                    $q_tglklaim='';
                    if (!empty($_REQUEST['tgl1'])) {
                        $q_tglklaim="and fu_ajk_cn.approve_date between '".$_REQUEST ['tgl1']."' and '".$_REQUEST ['tgl2']."'";
                    }


                    $q_dol='';
                    if (!empty($_REQUEST['tgl3'])) {
                        $q_dol="and fu_ajk_klaim.tgl_klaim between '".$_REQUEST ['tgl3']."' and '".$_REQUEST ['tgl4']."'";
                    }


                    $q_kol='';
                    if (!empty($_REQUEST['kol'])) {
                        $q_kol="and
							IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',

							IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
							IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
							IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
							,
							IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
							IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
							IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5'))))=".$_REQUEST['kol'];
                    }
                    $kucing = mysql_query("SELECT
								CONCAT(DATE_FORMAT(fu_ajk_cn.approve_date,'%Y%m'),'/',fu_ajk_klaim.id) AS klaim_id,
								fu_ajk_klaim.no_urut_klaim,
								fu_ajk_cn.id_cabang,
								fu_ajk_grupproduk.nmproduk AS mitra,
								fu_ajk_asuransi.`name`,
								fu_ajk_polis.nmproduk,
								IF(fu_ajk_polis.`typeproduk`='NON SPK','Percepatan','Reguler') AS kategori,
								fu_ajk_peserta.id_peserta,
								fu_ajk_peserta.nama,
								fu_ajk_peserta.tgl_lahir,
								fu_ajk_peserta.usia,
								fu_ajk_peserta.kredit_jumlah,
								fu_ajk_cn.total_claim,
								fu_ajk_cn.tuntutan_klaim,
								fu_ajk_peserta.kredit_tgl,
								ROUND(IF(fu_ajk_peserta.type_data='SPK',fu_ajk_peserta.kredit_tenor*12,fu_ajk_peserta.kredit_tenor)/12) AS kredit_tenor,
								fu_ajk_klaim.tgl_klaim AS dol,
								DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl) AS akad_dol,
								IF(fu_ajk_klaim.tgl_document='0000-00-00',NULL,fu_ajk_klaim.tgl_document) AS tgl_document,
								DATEDIFF(fu_ajk_cn.approve_date,fu_ajk_klaim.tgl_document) AS lama_terima_laporan,
								'' AS tgl_update_klaim,
								IF(fu_ajk_klaim.tgl_lapor_klaim='0000-00-00',NULL,fu_ajk_klaim.tgl_lapor_klaim) AS tgl_lapor_klaim,
								fu_ajk_cn.keterangan AS kelengkapan_dokumen,
								IF(fu_ajk_klaim.tgl_document_lengkap='0000-00-00',NULL,fu_ajk_klaim.tgl_document_lengkap) AS tgl_document_lengkap,
								IF(fu_ajk_polis.`typeproduk`='NON SPK',DATE_ADD(fu_ajk_klaim.tgl_document_lengkap,INTERVAL 28 DAY),DATE_ADD(fu_ajk_klaim.tgl_document_lengkap,INTERVAL 14 DAY)) as due_date,
								IF(fu_ajk_klaim.tgl_kirim_dokumen='0000-00-00',NULL,fu_ajk_klaim.tgl_kirim_dokumen) AS tgl_kirim_dokumen,
								CURRENT_DATE() AS today,
								IF(fu_ajk_klaim.tgl_kirim_dokumen<>'0000-00-00' OR fu_ajk_klaim.tgl_kirim_dokumen IS NOT NULL,DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_kirim_dokumen),'') AS status_release,
								/*fu_ajk_spak.ext_premi*/ '' AS EM,
								/*fu_ajk_spak.ket_ext*/ '' AS keterangan_EM,
								IF(fu_ajk_klaim.tgl_investigasi='0000-00-00',NULL,fu_ajk_klaim.tgl_investigasi) AS tgl_investigasi,
								fu_ajk_klaim.diagnosa AS hasil_investigasi,
								fu_ajk_namapenyakit.namapenyakit AS penyebab_meinggal,
								fu_ajk_cn.policy_liability AS polis_liability,
								fu_ajk_pembayaran_status.pembayaran_status,
								fu_ajk_klaim_status.status_klaim,
								if(`id_klaim_status`=6,'Ditolak',
								if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
								'Dokumen Belum Lengkap')) AS keterangan,
								'' AS keterangan_asuransi,
								fu_ajk_cn.total_bayar_asuransi,
								'' AS ref_bayar_asuransi,
								fu_ajk_cn.tgl_bayar_asuransi,
								'' AS nilai_pengajuan_keuangan,
								fu_ajk_cn.total_claim  AS bayar_ke_bank,
								'' AS ref_pembayaran_ke_bank,
								fu_ajk_cn.tgl_byr_claim AS tgl_bayar_ke_client,
								fu_ajk_cn.total_bayar_asuransi-fu_ajk_cn.total_claim AS selisih,
								IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',
								IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
								IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 AND DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
								IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
								,
								IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
								IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
								IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol
								FROM
								fu_ajk_cn
								INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
								INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
								INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
								INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
								LEFT JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
								LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id=fu_ajk_klaim.id_klaim_status
								LEFT JOIN fu_ajk_grupproduk ON fu_ajk_grupproduk.id=fu_ajk_peserta.nama_mitra
								/*LEFT JOIN fu_ajk_spak ON fu_ajk_peserta.spaj=fu_ajk_spak.spak*/
								LEFT JOIN fu_ajk_namapenyakit ON fu_ajk_namapenyakit.id=fu_ajk_klaim.sebab_meninggal
								LEFT JOIN fu_ajk_pembayaran_status ON fu_ajk_pembayaran_status.id=fu_ajk_cn.status_bayar
								where fu_ajk_cn.type_claim='Death' ".$q_status."
								".$q_tglklaim."
								".$q_dol."
								".$q_kol."
								".$q_asuransi."
								and fu_ajk_cn.confirm_claim !='Pending'
								and confirm_claim !='Pending'
								and fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']."
								/*and fu_ajk_cn.tgl_claim between '".$_REQUEST ['tgl1']."' and '".$_REQUEST ['tgl2']."'*/
								and fu_ajk_cn.del is null
								and fu_ajk_cn.policy_liability='NONLIABLE'
								/*and (DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<365 and fu_ajk_polis.typeproduk='NON SPK')*/

								order by
								fu_ajk_peserta.id");
                    while ($datanya_ = mysql_fetch_array($kucing)) {
                        $worksheet1->writeNumber($baris, 0, ++$no);
                        $worksheet1->writeString($baris, 1, $datanya_['no_urut_klaim']);
                        $worksheet1->writeString($baris, 2, $datanya_['id_cabang']);
                        $worksheet1->writeString($baris, 3, $datanya_['name']);
                        $worksheet1->writeString($baris, 4, $datanya_['kategori']);
                        $worksheet1->writeString($baris, 5, $datanya_['nmproduk']);
                        $worksheet1->writeString($baris, 6, $datanya_['nama']);
                        $worksheet1->writeString($baris, 7, $datanya_['tgl_lahir']);
                        $worksheet1->writeNumber($baris, 8, $datanya_['usia']);
                        $worksheet1->writeNumber($baris, 9, $datanya_['kredit_jumlah']);
                        $worksheet1->writeNumber($baris, 10, $datanya_['tuntutan_klaim']);
                        $worksheet1->writeString($baris, 11, $datanya_['kredit_tgl']);
                        $worksheet1->writeNumber($baris, 12, $datanya_['kredit_tenor']);
                        $worksheet1->writeString($baris, 13, $datanya_['dol']);
                        $worksheet1->writeString($baris, 14, $datanya_['akad_dol']);
                        $worksheet1->writeString($baris, 15, $datanya_['tgl_document']);
                        $worksheet1->writeString($baris, 16, $datanya_['keterangan']);
                        $worksheet1->writeString($baris, 17, $datanya_['tgl_document_lengkap']);
                        $worksheet1->writeString($baris, 18, $datanya_['status_klaim']);
                        $worksheet1->writeNumber($baris, 19, $datanya_['input_date']);
                        $worksheet1->writeNumber($baris, 20, $datanya_['total_bayar_asuransi']);
                        $worksheet1->writeString($baris, 21, $datanya_['tgl_bayar_asuransi']);
                        $worksheet1->writeNumber($baris, 22, $datanya_['bayar_ke_bank']);
                        $worksheet1->writeString($baris, 23, $datanya_['tgl_bayar_ke_client']);
                        $worksheet1->writeString($baris, 24, $datanya_['selisih']);
                        $worksheet1->writeNumber($baris, 25, $datanya_['kol']);

                        $baris++;
                    }

                    $workbook->close();
                    ;
                    break;
case "list_pembas":
    function HeaderingExcel($filename)
    {
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$filename");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
        header("Pragma: public");
    }
    HeaderingExcel('DATA REKAP USER SPK.xls');
    $workbook = new Workbook("");
    $worksheet1 =& $workbook->add_worksheet('DATA KLAIM NONLIABLE');
    $format =& $workbook->add_format();
    $format->setAlign('vcenter');	$format->setAlign('center');	$format->setColor('white');	$format->setBold();	$format->setPattern();	$format->setFgColor('green');
    $fjudul =& $workbook->add_format();	$fjudul->setAlign('vcenter');	$fjudul->setAlign('center');	$fjudul->setBold();

    $worksheet1->setMerge(0, 0, 0, 7);	$worksheet1->writeString(0, 0, "DATA KLAIM NONLIABLE", $fjudul, 1);
    $worksheet1->setMerge(1, 0, 1, 7);	$worksheet1->writeString(1, 0, "TANGGAL KLAIM "._convertDate($_REQUEST['tgl1'])." s/d "._convertDate($_REQUEST['tgl2'])."", $fjudul);

    $worksheet1->setRow(4, 15);
    $worksheet1->setColumn(4, 0, 5);	$worksheet1->writeString(4, 0, "No", $format);
    $worksheet1->setColumn(4, 1, 5);	$worksheet1->writeString(4, 1, "Nama Perusahaan", $format);
    $worksheet1->setColumn(4, 2, 5);	$worksheet1->writeString(4, 2, "Asuransi", $format);
    $worksheet1->setColumn(4, 3, 5);	$worksheet1->writeString(4, 3, "Nomor Polis", $format);
    $worksheet1->setColumn(4, 4, 5);	$worksheet1->writeString(4, 4, "Nomor DN", $format);
    $worksheet1->setColumn(4, 5, 5);	$worksheet1->writeString(4, 5, "ID Peserta", $format);
    $worksheet1->setColumn(4, 6, 5);	$worksheet1->writeString(4, 6, "Nama", $format);
    $worksheet1->setColumn(4, 7, 5);	$worksheet1->writeString(4, 7, "DOB", $format);
    $worksheet1->setColumn(4, 8, 5);	$worksheet1->writeString(4, 8, "Usia", $format);
    $worksheet1->setColumn(4, 9, 5);	$worksheet1->writeString(4, 9, "Kredit Awal", $format);
    $worksheet1->setColumn(4, 10, 5);	$worksheet1->writeString(4, 10, "Tenor", $format);
    $worksheet1->setColumn(4, 11, 5);	$worksheet1->writeString(4, 11, "Kredit Akhir", $format);
    $worksheet1->setColumn(4, 12, 5);	$worksheet1->writeString(4, 12, "U P", $format);
    $worksheet1->setColumn(4, 13, 5);	$worksheet1->writeString(4, 13, "Total Klaim", $format);
    $worksheet1->setColumn(4, 14, 5);	$worksheet1->writeString(4, 14, "Cabang", $format);
    $worksheet1->setColumn(4, 15, 5);	$worksheet1->writeString(4, 15, "Tgl Bayar Dari Asuransi", $format);
    $worksheet1->setColumn(4, 16, 5);	$worksheet1->writeString(4, 16, "Total Bayar Dari Asuransi", $format);
    $worksheet1->setColumn(4, 17, 5);	$worksheet1->writeString(4, 17, "Outstanding Dari Asuransi", $format);


    $baris = 5;
    $sql="";
    if ($_REQUEST['cat']) {
        $satu = 'AND fu_ajk_peserta.id_polis = "' . $_REQUEST['cat'] . '"';
    }
    //if ($_REQUEST['nodn'])			{	$dua = 'AND id_dn = "' . $_REQUEST['nodn'] . '"';		} //before edit by hansen 27-06-2016
    if ($_REQUEST['nodn']) {
        $dua = 'AND fu_ajk_peserta.id_dn = (SELECT id FROM fu_ajk_dn where dn_kode = "' . $_REQUEST['nodn'] . '" ) ';
    }// update by hansen 27-06-2016
    if ($_REQUEST['rnama']) {
        $tiga = 'AND fu_ajk_peserta.nama LIKE "%' . $_REQUEST['rnama'] . '%"';
    }
    $dob = explode("-", $_REQUEST['rdob']);
    $dobpeserta = $dob[2] . '/' . $dob[1] . '/' . $dob[0];
    if ($_REQUEST['rdob']) {
        $empat = 'AND fu_ajk_peserta.tgl_lahir LIKE "%' . $dobpeserta . '%"';
    }
    $kucing = mysql_query('SELECT fu_ajk_peserta.*,fu_ajk_note_as.note_paid_date,fu_ajk_note_as.note_paid_total
											FROM fu_ajk_peserta
											INNER JOIN fu_ajk_klaim ON fu_ajk_peserta.id_klaim=fu_ajk_klaim.id_cn
											inner join fu_ajk_note_as on fu_ajk_note_as.id_peserta=fu_ajk_peserta.id_peserta
											WHERE fu_ajk_note_as.note_paid_date is not null and fu_ajk_peserta.id !="" ' . $satu . ' ' . $dua . ' ' . $tiga . ' ' . $empat . ' AND fu_ajk_peserta.status_aktif="Lapse" ORDER BY fu_ajk_peserta.id_cost ASC, fu_ajk_peserta.nama ASC, fu_ajk_peserta.id_dn ASC');
    while ($datanya_ = mysql_fetch_array($kucing)) {
        $xperusahaan = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="' . $xpeserta['id_cost'] . '"'));
        $xpolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="' . $xpeserta['id_polis'] . '"'));
        $xdn = mysql_fetch_array($database->doQuery('SELECT id, dn_kode FROM fu_ajk_dn WHERE id="' . $xpeserta['id_dn'] . '"'));
        $xcn = mysql_fetch_array($database->doQuery('SELECT total_claim FROM fu_ajk_cn WHERE id_dn="' . $xpeserta['id_dn'] . '"'));
        $xAsuransi = mysql_fetch_array($database->doQuery('SELECT id, id_bank, id_polis, id_asuransi, id_polis_as, id_dn, id_peserta FROM fu_ajk_peserta_as WHERE id_bank="' . $xpeserta['id_cost'] . '" AND id_polis="' . $xpeserta['id_polis'] . '" AND id_peserta="' . $xpeserta['id_peserta'] . '"'));
        $xAsuransi_ = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_asuransi WHERE id="' . $xAsuransi['id_asuransi'] . '"'));


        $worksheet1->writeNumber($baris, 0, ++$no);
        $worksheet1->writeString($baris, 1, $xperusahaan['name']);
        $worksheet1->writeString($baris, 2, $xAsuransi_['name']);
        $worksheet1->writeString($baris, 3, $xpolis['nopol']);
        $worksheet1->writeString($baris, 4, $xdn['dn_kode']);
        $worksheet1->writeString($baris, 5, $xpeserta['id_peserta']);
        $worksheet1->writeNumber($baris, 6, $xpeserta['nama']);
        $worksheet1->writeNumber($baris, 7, $xpeserta['tgl_lahir']);
        $worksheet1->writeNumber($baris, 8, $xpeserta['usia']);
        $worksheet1->writeString($baris, 9, $xpeserta['kredit_tgl']);
        $worksheet1->writeNumber($baris, 10, $xpeserta['kredit_tenor']);
        $worksheet1->writeString($baris, 11, $xpeserta['kredit_akhir']);
        $worksheet1->writeString($baris, 12, duit($xpeserta['kredit_jumlah']));
        $worksheet1->writeString($baris, 13, duit($xcn['total_claim']));
        $worksheet1->writeNumber($baris, 14, $xpeserta['cabang']);
        $worksheet1->writeString($baris, 15, $xpeserta['note_paid_date']);
        $worksheet1->writeString($baris, 16, duit($xpeserta['note_paid_total']));
        $worksheet1->writeNumber($baris, 17, duit($xpeserta['note_paid_total']-$xcn['total_claim']));

        $baris++;
    }

    $workbook->close();

    ;
    break;
case "klaim_tiering_asuransi":

    function HeaderingExcel($filename)
    {
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$filename");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
        header("Pragma: public");
    }
    HeaderingExcel('KLAIM_TIERING.xls');
    $workbook = new Workbook("");
    $worksheet1 =& $workbook->add_worksheet('DATA KLAIM TIERING');
    $format =& $workbook->add_format();
    $format->setAlign('vcenter');	$format->setAlign('center');	$format->setColor('white');	$format->setBold();	$format->setPattern();	$format->setFgColor('green');
    $fjudul =& $workbook->add_format();	$fjudul->setAlign('vcenter');	$fjudul->setAlign('center');	$fjudul->setBold();


    $worksheet1->setMerge(0, 0, 0, 24);	$worksheet1->writeString(0, 0, "DATA KLAIM TIERING ".strtoupper($_REQUEST['status_klaim']), $fjudul, 1);


    if (!empty($_REQUEST['tgl1'])) {
        $worksheet1->setMerge(1, 0, 1, 24);
        $worksheet1->writeString(1, 0, "TANGGAL LAPOR "._convertDate($_REQUEST['tgl1'])." s/d "._convertDate($_REQUEST['tgl2'])."", $fjudul);
    }


    if (!empty($_REQUEST['tgl3'])) {
        $worksheet1->setMerge(2, 0, 2, 24);
        $worksheet1->writeString(1, 0, "DOL "._convertDate($_REQUEST['tgl3'])." s/d "._convertDate($_REQUEST['tgl4'])."", $fjudul);
    }


    $worksheet1->setRow(4, 15);
    $worksheet1->setColumn(4, 0, 5); $worksheet1->writeString(4, 0, "No", $format);
    $worksheet1->setColumn(4, 1, 5); $worksheet1->writeString(4, 1, "Nomor Urut", $format);
    $worksheet1->setColumn(4, 2, 5); $worksheet1->writeString(4, 2, "Bukopin Cabang", $format);
    $worksheet1->setColumn(4, 3, 5); $worksheet1->writeString(4, 3, "Cover Asuransi", $format);
    $worksheet1->setColumn(4, 4, 5); $worksheet1->writeString(4, 4, "Kategori", $format);
    $worksheet1->setColumn(4, 5, 5); $worksheet1->writeString(4, 5, "Nama Debitur", $format);
    $worksheet1->setColumn(4, 6, 5); $worksheet1->writeString(4, 6, "Tgl Lahir", $format);
    $worksheet1->setColumn(4, 7, 5); $worksheet1->writeString(4, 7, "Usia", $format);
    $worksheet1->setColumn(4, 8, 5); $worksheet1->writeString(4, 8, "Plafond Kredit ", $format);
    $worksheet1->setColumn(4, 9, 5); $worksheet1->writeString(4, 9, "Tuntutan Klaim ", $format);
    $worksheet1->setColumn(4, 10, 5); $worksheet1->writeString(4, 10, "Presentase Tiering", $format);
    $worksheet1->setColumn(4, 11, 5); $worksheet1->writeString(4, 11, "Nilai Tiering ", $format);
    $worksheet1->setColumn(4, 12, 5); $worksheet1->writeString(4, 12, "Tgl Akad", $format);
    $worksheet1->setColumn(4, 13, 5); $worksheet1->writeString(4, 13, "J.Wkt (Th.)", $format);
    $worksheet1->setColumn(4, 14, 5); $worksheet1->writeString(4, 14, "DOL", $format);
    $worksheet1->setColumn(4, 15, 5); $worksheet1->writeString(4, 15, "Akad s/d DOL (hari)", $format);
    $worksheet1->setColumn(4, 16, 5); $worksheet1->writeString(4, 16, "Tgl. Lapor Asuransi ", $format);
    $worksheet1->setColumn(4, 17, 5); $worksheet1->writeString(4, 17, "Kelengkapan Dokumen Klaim", $format);
    $worksheet1->setColumn(4, 18, 5); $worksheet1->writeString(4, 18, "Tanggal Status Lengkap", $format);
    $worksheet1->setColumn(4, 19, 5); $worksheet1->writeString(4, 19, "Status Klaim", $format);
    $worksheet1->setColumn(4, 20, 5); $worksheet1->writeString(4, 20, "Asuransi Bayar (Rp.) ", $format);
    $worksheet1->setColumn(4, 21, 5); $worksheet1->writeString(4, 21, "Tanggal Pembayaran dari Asuransi ", $format);
    $worksheet1->setColumn(4, 22, 5); $worksheet1->writeString(4, 22, " Bayar ke Bank (Rp.) ", $format);
    $worksheet1->setColumn(4, 23, 5); $worksheet1->writeString(4, 23, "Tanggal Pembayaran ke Bank", $format);
    $worksheet1->setColumn(4, 24, 5); $worksheet1->writeString(4, 24, " Selisih Bayar (Rp.) ", $format);
    $worksheet1->setColumn(4, 25, 5); $worksheet1->writeString(4, 25, "Kol", $format);



    $baris = 5;

    $q1='';
    $q2='';
    $q3='';
    $q4='';
    $q5='';
    $q6='';

    if ($_REQUEST['id_asuransi']!="") {
        $q1=" and fu_ajk_asuransi.id=".$_REQUEST['id_asuransi'];
    }

    if ($_REQUEST['kol']!="") {
        $q2=" and
IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',

			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
			,
			IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5'))))=".$_REQUEST['kol']."";
    }


    $q3="";
    if (!empty($_REQUEST['status_klaim'])) {
        $q3="  and if(`id_klaim_status`=6,'Ditolak',
			if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
			'Dokumen Belum Lengkap')) ='".$_REQUEST['status_klaim']."' ";
    }
    $q4='';
    if (!empty($_REQUEST['tgl1'])) {
        $q4="and fu_ajk_cn.approve_date between '".$_REQUEST ['tgl1']."' and '".$_REQUEST ['tgl2']."'";
    }


    $q5='';
    if (!empty($_REQUEST['tgl3'])) {
        $q5="and fu_ajk_klaim.tgl_klaim between '".$_REQUEST ['tgl3']."' and '".$_REQUEST ['tgl4']."'";
    }


    $kucing = mysql_query("SELECT
								fu_ajk_klaim.id as klaim_id,
								fu_ajk_klaim.no_urut_klaim,
								fu_ajk_klaim_status.status_klaim,
								fu_ajk_cn.id_cabang,
								fu_ajk_asuransi.`name`,
								fu_ajk_polis.nmproduk,
								fu_ajk_peserta.nama,
								fu_ajk_peserta.tgl_lahir,
								fu_ajk_peserta.usia,
								fu_ajk_peserta.kredit_jumlah,
								fu_ajk_cn.tuntutan_klaim,
								fu_ajk_cn.total_claim,
								fu_ajk_peserta.kredit_tgl,
								fu_ajk_klaim.tgl_document_lengkap,
								if(`id_klaim_status`=6,'Ditolak',
								if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
								'Dokumen Belum Lengkap'))  as keterangan,
								fu_ajk_cn.total_bayar_asuransi as asuransi_bayar,
								fu_ajk_cn.tgl_bayar_asuransi as tgl_asuransi_bayar,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<31,10,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<61,20,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<91,30,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<121,40,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<151,50,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<181,60,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<271,70,80))))))) as persentase_tiering,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<31,10,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<61,20,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<91,30,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<121,40,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<151,50,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<181,60,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<271,70,80)))))))/100*fu_ajk_cn.total_claim as nilai_tiering,

								fu_ajk_cn.total_bayar_asuransi-fu_ajk_cn.total_claim as selisih,

								if(fu_ajk_klaim.tgl_lapor_klaim='0000-00-00','',fu_ajk_klaim.tgl_lapor_klaim) as tgl_lapor_klaim,
								if(fu_ajk_peserta.type_data='SPK',fu_ajk_peserta.kredit_tenor*12,fu_ajk_peserta.kredit_tenor) as kredit_tenor,
								fu_ajk_klaim.tgl_klaim as dol,
								datediff(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl) as akad_dol,
								if(fu_ajk_cn.tgl_byr_claim<>'',fu_ajk_cn.total_claim,'')  as bayar_ke_bank,
								fu_ajk_cn.tgl_byr_claim as tgl_bayar_ke_client,


								IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',

								IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
								IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
								IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
								,
								IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
								IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
								IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol
								FROM
								fu_ajk_cn
								INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
								INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta


								INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
								INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
								LEFT join fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
								LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id=fu_ajk_klaim.id_klaim_status

								where fu_ajk_cn.type_claim='Death'
								and fu_ajk_cn.id_cost=".$_REQUEST['id_cost']." ".$q1." ".$q2." ".$q3." ".$q4." ".$q5."

								and fu_ajk_cn.policy_liability='NONLIABLE'
								order by
								fu_ajk_peserta.id");

    while ($datanya_ = mysql_fetch_array($kucing)) {
        $worksheet1->writeNumber($baris, 0, ++$no);
        $worksheet1->writeString($baris, 1, $datanya_['no_urut_klaim']);
        $worksheet1->writeString($baris, 2, $datanya_['id_cabang']);
        $worksheet1->writeString($baris, 3, $datanya_['name']);
        $worksheet1->writeString($baris, 4, $datanya_['nmproduk']);
        $worksheet1->writeString($baris, 5, $datanya_['nama']);
        $worksheet1->writeString($baris, 6, $datanya_['tgl_lahir']);
        $worksheet1->writeString($baris, 7, $datanya_['usia']);
        $worksheet1->writeNumber($baris, 8, $datanya_['kredit_jumlah']);
        $worksheet1->writeNumber($baris, 9, $datanya_['tuntutan_klaim']);
        $worksheet1->writeString($baris, 10, $datanya_['persentase_tiering']);
        $worksheet1->writeNumber($baris, 11, $datanya_['nilai_tiering']);
        $worksheet1->writeString($baris, 12, $datanya_['kredit_tgl']);
        $worksheet1->writeString($baris, 13, $datanya_['kredit_tenor']);
        $worksheet1->writeString($baris, 14, $datanya_['dol']);
        $worksheet1->writeString($baris, 15, $datanya_['akad_dol']);
        $worksheet1->writeString($baris, 16, $datanya_['tgl_lapor_klaim']);
        $worksheet1->writeString($baris, 17, $datanya_['keterangan']);
        $worksheet1->writeString($baris, 18, $datanya_['tgl_document_lengkap']);
        $worksheet1->writeString($baris, 19, $datanya_['status_klaim']);
        $worksheet1->writeNumber($baris, 20, $datanya_['asuransi_bayar']);
        $worksheet1->writeString($baris, 21, $datanya_['tgl_asuransi_bayar']);
        $worksheet1->writeNumber($baris, 22, $datanya_['bayar_ke_bank']);
        $worksheet1->writeString($baris, 23, $datanya_['tgl_bayar_ke_client']);
        $worksheet1->writeNumber($baris, 24, $datanya_['selisih']);
        $worksheet1->writeString($baris, 25, $datanya_['kol']);

        $baris++;
    }

    $workbook->close();

    ;
    break;
case "eL_Rekap_Klaim":


        function HeaderingExcel($filename)
        {
            header("Content-type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=$filename");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
            header("Pragma: public");
        }
        HeaderingExcel('REKAPITULASI SUMMARY KLAIM.xls');
        $workbook = new Workbook("");
        $worksheet1 =& $workbook->add_worksheet('REKAPITULASI SUMMARY KLAIM');
        $format =& $workbook->add_format();
        $format->setAlign('vcenter');	$format->setAlign('center');	$format->setColor('white');	$format->setBold();	$format->setPattern();	$format->setFgColor('green');
        $fjudul =& $workbook->add_format();	$fjudul->setAlign('vcenter');	$fjudul->setAlign('center');	$fjudul->setBold();

        if ($_REQUEST['id_cost']) {
            $cost_ = mysql_fetch_array(mysql_query('SELECT * from fu_ajk_costumer where id="'.$_REQUEST['id_cost'].'"'));
            $satu = 'AND fu_ajk_peserta.id_cost = "'.$_REQUEST['id_cost'].'"';
            $cost=$cost_['name'];
        }

        $as='Semua Asuransi';
        if ($_REQUEST['id_as']) {
            $as_ = mysql_fetch_array(mysql_query('SELECT * from fu_ajk_asuransi where id="'.$_REQUEST['id_as'].'"'));
            $satu = 'AND fu_ajk_dn.id_as = "'.$_REQUEST['id_as'].'"';
            $as=$as_['name'];
        }

        $polis='Semua Produk';
        if ($_REQUEST['id_polis']) {
            $polis_ = mysql_fetch_array(mysql_query('SELECT * from fu_ajk_polis where id="'.$_REQUEST['id_polis'].'"'));
            $dua = 'AND fu_ajk_peserta.id_polis = "'.$_REQUEST['id_polis'].'"';
            $polis=$polis_['nmproduk'];
        }

        $tgl_klaim=$_REQUEST['tglklaim1'].' s/d '.$_REQUEST['tglklaim2'];
        if ($_REQUEST['tglklaim1']) {
            $tiga = 'AND fu_ajk_cn.tgl_createcn BETWEEN "'.$_REQUEST['tglklaim1'].'" AND "'.$_REQUEST['tglklaim2'].'"';
        }

        $status='Semua Status';
        if ($_REQUEST['status']) {
            $status_ = mysql_fetch_array(mysql_query('SELECT * from fu_ajk_klaim_status where id="'.$_REQUEST['status'].'"'));
            $lima = 'AND fu_ajk_klaim.id_klaim_status = "'.$_REQUEST['status'].'"';
            $status=$status_['status_klaim'];
        }
        $regional ='Semua Regional';
        if ($_REQUEST['id_reg']!=="") {
            $regional=$_REQUEST['id_reg'];
            $enam = $_REQUEST['id_reg'];
        }
        $cabang='Semua Cabang';
        if ($_REQUEST['id_cab']!=="") {
            $tujuh= $_REQUEST['id_cab'];
            $cabang=$_REQUEST['id_cab'];
        }


        $met = mysql_query('SELECT
				fu_ajk_costumer.`name`,
				fu_ajk_polis.nmproduk,
				fu_ajk_polis.mpptype,
				fu_ajk_dn.dn_kode,
				fu_ajk_dn.dn_status,
				fu_ajk_dn.tgltransaksi,
				fu_ajk_dn.tgl_dn_paid,
				fu_ajk_dn.tgl_createdn,
				fu_ajk_peserta.id_peserta,
				fu_ajk_peserta.id_cost,
				fu_ajk_peserta.id_polis,
				fu_ajk_peserta.nama_mitra,
				fu_ajk_peserta.nama,
				fu_ajk_peserta.tgl_lahir,
				fu_ajk_peserta.spaj,
				fu_ajk_peserta.usia,
				fu_ajk_peserta.kredit_tgl,
				fu_ajk_peserta.kredit_tenor,
				fu_ajk_peserta.kredit_akhir,
				fu_ajk_peserta.kredit_jumlah,
				fu_ajk_peserta.premi,
				fu_ajk_peserta.ext_premi,
				fu_ajk_peserta.totalpremi,
				fu_ajk_peserta.type_data,
				fu_ajk_peserta.status_aktif,
				fu_ajk_peserta.status_peserta,
				fu_ajk_peserta.mppbln,
				fu_ajk_peserta.regional,
				fu_ajk_peserta.cabang,
				fu_ajk_cn.tgl_createcn,
				fu_ajk_cn.total_claim,
				fu_ajk_cn.tgl_byr_claim,
				fu_ajk_cn.tgl_bayar_asuransi,
				fu_ajk_cn.total_bayar_asuransi,
				fu_ajk_klaim_status.status_klaim,
				fu_ajk_asuransi.`name` AS nmAsuransi
				FROM fu_ajk_peserta
				INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id AND fu_ajk_peserta.id_cost = fu_ajk_dn.id_cost AND fu_ajk_peserta.id_polis = fu_ajk_dn.id_nopol
				INNER JOIN fu_ajk_costumer ON fu_ajk_peserta.id_cost = fu_ajk_costumer.id
				INNER JOIN fu_ajk_polis ON fu_ajk_peserta.id_polis = fu_ajk_polis.id
				INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
				INNER JOIN fu_ajk_cn ON fu_ajk_cn.id_dn = fu_ajk_dn.id and fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
				LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
				LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim.id_klaim_status = fu_ajk_klaim_status.id
				WHERE fu_ajk_cn.type_claim="Death" and fu_ajk_peserta.id_dn !="" '.$satu.' '.$dua.' '.$tiga.' '.$lima.' '.$enam.' '.$tujuh.' AND fu_ajk_dn.del IS NULL AND fu_ajk_peserta.del IS NULL and fu_ajk_cn.del IS NULL
				ORDER BY fu_ajk_peserta.id_polis ASC,
						 fu_ajk_dn.id_as ASC,
						 fu_ajk_peserta.cabang ASC,
						 fu_ajk_peserta.nama ASC');
        $worksheet1->setMerge(0, 0, 0, 7);	$worksheet1->writeString(0, 0, "SUMMARY KLAIM ", $fjudul, 1);
        $worksheet1->setMerge(1, 0, 1, 7);	$worksheet1->writeString(1, 0, "NAMA PERUSAHAAN : ".$cost, $fjudul);
        $worksheet1->setMerge(2, 0, 2, 7);	$worksheet1->writeString(2, 0, "NAMA ASURANSI : ".$as, $fjudul);
        $worksheet1->setMerge(3, 0, 3, 7);	$worksheet1->writeString(3, 0, "NAMA PRODUK : ".$polis, $fjudul);
        $worksheet1->setMerge(4, 0, 4, 7);	$worksheet1->writeString(4, 0, "PERIODE KLAIM : ".$tgl_klaim, $fjudul);
        $worksheet1->setMerge(5, 0, 5, 7);	$worksheet1->writeString(5, 0, "STATUS KLAIM : ".$status, $fjudul);
        $worksheet1->setMerge(6, 0, 6, 7);	$worksheet1->writeString(6, 0, "REGIONAL : ".$regional, $fjudul);
        $worksheet1->setMerge(7, 0, 7, 7);	$worksheet1->writeString(7, 0, "CABANG : ".$cabang, $fjudul);

        $worksheet1->setRow(9, 15);
        $worksheet1->setColumn(9, 0, 5);	$worksheet1->writeString(9, 0, "NO", $format);
        $worksheet1->setColumn(9, 1, 15);	$worksheet1->writeString(9, 1, "Asuransi", $format);
        $worksheet1->setColumn(9, 2, 15);	$worksheet1->writeString(9, 2, "Produk", $format);
        $worksheet1->setColumn(9, 3, 15);	$worksheet1->writeString(9, 3, "Debit Note", $format);
        $worksheet1->setColumn(9, 4, 15);	$worksheet1->writeString(9, 4, "Tanggal DN", $format);
        $worksheet1->setColumn(9, 5, 15);	$worksheet1->writeString(9, 5, "Mulai Asuransi", $format);
        $worksheet1->setColumn(9, 6, 15);	$worksheet1->writeString(9, 6, "Akhir Asuransi", $format);
        $worksheet1->setColumn(9, 7, 15);	$worksheet1->writeString(9, 7, "No. Reg", $format);
        $worksheet1->setColumn(9, 8, 15);	$worksheet1->writeString(9, 8, "Nama Debitur", $format);
        $worksheet1->setColumn(9, 9, 15);	$worksheet1->writeString(9, 9, "Cabang", $format);
        $worksheet1->setColumn(9, 10, 15);	$worksheet1->writeString(9, 10, "Tgl Lahir", $format);
        $worksheet1->setColumn(9, 11, 15);	$worksheet1->writeString(9, 11, "Usia", $format);
        $worksheet1->setColumn(9, 12, 15);	$worksheet1->writeString(9, 12, "Plafond", $format);
        $worksheet1->setColumn(9, 13, 15);	$worksheet1->writeString(9, 13, "Total Premi", $format);
        $worksheet1->setColumn(9, 14, 15);	$worksheet1->writeString(9, 14, "Nilai Klaim", $format);
        $worksheet1->setColumn(9, 15, 15);	$worksheet1->writeString(9, 15, "Asuransi Bayar", $format);
        $worksheet1->setColumn(9, 16, 15);	$worksheet1->writeString(9, 16, "Status", $format);

        $baris = 10;
        while ($jangkrik = mysql_fetch_array($met)) {

            //Aktif | Proses | Approve | Preapproval | Batal | Tolak | Realisasi
            $worksheet1->writeNumber($baris, 0, ++$no);
            $worksheet1->writeString($baris, 1, $jangkrik['nmAsuransi']);
            $worksheet1->writeString($baris, 2, $jangkrik['nmproduk']);
            $worksheet1->writeString($baris, 3, $jangkrik['dn_kode']);
            $worksheet1->writeString($baris, 4, $jangkrik['tgl_createdn']);
            $worksheet1->writeString($baris, 5, $jangkrik['kredit_tgl']);
            $worksheet1->writeString($baris, 6, $jangkrik['kredit_akhir']);
            $worksheet1->writeString($baris, 7, $jangkrik['id_peserta']);
            $worksheet1->writeString($baris, 8, $jangkrik['nama']);
            $worksheet1->writeString($baris, 9, $jangkrik['cabang']);
            $worksheet1->writeString($baris, 10, $jangkrik['tgl_lahir']);
            $worksheet1->writeString($baris, 11, $jangkrik['usia']);
            $worksheet1->writeNumber($baris, 12, $jangkrik['kredit_jumlah']);
            $worksheet1->writeNumber($baris, 13, $jangkrik['totalpremi']);
            $worksheet1->writeNumber($baris, 14, $jangkrik['total_claim']);
            $worksheet1->writeNumber($baris, 15, $jangkrik['total_bayar_asuransi']);
            $worksheet1->writeString($baris, 16, $jangkrik['status_klaim']);
            $baris++;
        }

        $workbook->close();
        ;
        break;

case "eL_PrintCoveringLetterPDFAsuransi":
            $pdf=new FPDF('L', 'mm', 'A4');
            $pdf->Open();
            $pdf->AliasNbPages();
            $pdf->AddPage();

            //$pdf->Image('../image/logo_adonai.gif',10,5);
            $pdf->SetFont('Arial', 'B', 7);

            if ($_REQUEST['id_cost']) {
                $cost_ = mysql_fetch_array(mysql_query('SELECT * from fu_ajk_costumer where id="'.$_REQUEST['id_cost'].'"'));
                $satu = 'AND fu_ajk_peserta.id_cost = "'.$_REQUEST['id_cost'].'"';
                $cost=$cost_['name'];
            }

            $as='Semua Asuransi';
            if ($_REQUEST['id_as']) {
                $as_ = mysql_fetch_array(mysql_query('SELECT * from fu_ajk_asuransi where id="'.$_REQUEST['id_as'].'"'));
                $satu = 'AND fu_ajk_dn.id_as = "'.$_REQUEST['id_as'].'"';
                $as=$as_['name'];
            }

            $polis='Semua Produk';
            if ($_REQUEST['id_polis']) {
                $polis_ = mysql_fetch_array(mysql_query('SELECT * from fu_ajk_polis where id="'.$_REQUEST['id_polis'].'"'));
                $dua = 'AND fu_ajk_peserta.id_polis = "'.$_REQUEST['id_polis'].'"';
                $polis=$polis_['nmproduk'];
            }

            $tgl_klaim=$_REQUEST['tglklaim1'].' s/d '.$_REQUEST['tglklaim2'];
            if ($_REQUEST['tglklaim1']) {
                $tiga = 'AND fu_ajk_cn.tgl_createcn BETWEEN "'.$_REQUEST['tglklaim1'].'" AND "'.$_REQUEST['tglklaim2'].'"';
            }

            $status='Semua Status';
            if ($_REQUEST['status']) {
                $status_ = mysql_fetch_array(mysql_query('SELECT * from fu_ajk_klaim_status where id="'.$_REQUEST['status'].'"'));
                $lima = 'AND fu_ajk_klaim.id_klaim_status = "'.$_REQUEST['status'].'"';
                $status=$status_['status_klaim'];
            }

            $regional ='Semua Regional';
            if ($_REQUEST['id_reg']!=="") {
                $regional=$_REQUEST['id_reg'];
                $enam = ' AND fu_ajk_peserta.regional="'.$_REQUEST['id_reg'].'"';
            }
            $cabang='Semua Cabang';
            if ($_REQUEST['id_cab']!=="") {
                $tujuh= ' AND fu_ajk_peserta.cabang="'.$_REQUEST['id_cab'].'"';
                $cabang=$_REQUEST['id_cab'];
            }

            if ($_REQUEST['x']) {
                $m = ($_REQUEST['x']-1) * 25;
            } else {
                $m = 0;
            }

            $met = mysql_query('SELECT
							fu_ajk_costumer.`name`,
							fu_ajk_polis.nmproduk,
							fu_ajk_polis.mpptype,
							fu_ajk_dn.dn_kode,
							fu_ajk_dn.dn_status,
							fu_ajk_dn.tgltransaksi,
							fu_ajk_dn.tgl_dn_paid,
							fu_ajk_dn.tgl_createdn,
							fu_ajk_peserta.id_peserta,
							fu_ajk_peserta.id_cost,
							fu_ajk_peserta.id_polis,
							fu_ajk_peserta.nama_mitra,
							fu_ajk_peserta.nama,
							fu_ajk_peserta.tgl_lahir,
							fu_ajk_peserta.spaj,
							fu_ajk_peserta.usia,
							fu_ajk_peserta.kredit_tgl,
							fu_ajk_peserta.kredit_tenor,
							fu_ajk_peserta.kredit_akhir,
							fu_ajk_peserta.kredit_jumlah,
							fu_ajk_peserta.premi,
							fu_ajk_peserta.ext_premi,
							fu_ajk_peserta.totalpremi,
							fu_ajk_peserta.type_data,
							fu_ajk_peserta.status_aktif,
							fu_ajk_peserta.status_peserta,
							fu_ajk_peserta.mppbln,
							fu_ajk_peserta.regional,
							fu_ajk_peserta.cabang,
							fu_ajk_cn.id_cn,
							fu_ajk_cn.tgl_createcn,
							fu_ajk_cn.total_claim,
							fu_ajk_cn.tgl_byr_claim,
							fu_ajk_cn.tgl_bayar_asuransi,
							fu_ajk_cn.total_bayar_asuransi,
							fu_ajk_klaim_status.status_klaim,
							fu_ajk_asuransi.`name` AS nmAsuransi
							FROM fu_ajk_peserta
							INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id AND fu_ajk_peserta.id_cost = fu_ajk_dn.id_cost AND fu_ajk_peserta.id_polis = fu_ajk_dn.id_nopol
							INNER JOIN fu_ajk_costumer ON fu_ajk_peserta.id_cost = fu_ajk_costumer.id
							INNER JOIN fu_ajk_polis ON fu_ajk_peserta.id_polis = fu_ajk_polis.id
							INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
							INNER JOIN fu_ajk_cn ON fu_ajk_cn.id_dn = fu_ajk_dn.id and fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
							LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
							LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim.id_klaim_status = fu_ajk_klaim_status.id
							WHERE fu_ajk_cn.type_claim="Death" and fu_ajk_peserta.id_dn !="" '.$satu.' '.$dua.' '.$tiga.' '.$lima.' '.$enam.' '.$tujuh.' AND fu_ajk_dn.del IS NULL AND fu_ajk_peserta.del IS NULL and fu_ajk_cn.del IS NULL
							ORDER BY fu_ajk_peserta.id_polis ASC,
									 fu_ajk_dn.id_as ASC,
									 fu_ajk_peserta.cabang ASC,
									 fu_ajk_peserta.nama ASC
			');
            $pdf->SetFont('Times', 'B', 7);

            $pdf->SetX(5);
            $pdf->Cell(30, 5, 'Nama Perusahaan', 0, 0, 'L');
            $pdf->SetX(35);
            $pdf->Cell(5, 5, ':', 0, 0, 'L');
            $pdf->SetX(40);
            $pdf->Cell(50, 5, $cost, 0, 0, 'L');
            $pdf->Ln();
            $pdf->SetX(5);
            $pdf->Cell(30, 5, 'Nama Asuransi', 0, 0, 'L');
            $pdf->SetX(35);
            $pdf->Cell(5, 5, ':', 0, 0, 'L');
            $pdf->SetX(40);
            $pdf->Cell(50, 5, $as, 0, 0, 'L');
            $pdf->Ln();
            $pdf->SetX(5);
            $pdf->Cell(30, 5, 'Nama Produk', 0, 0, 'L');
            $pdf->SetX(35);
            $pdf->Cell(5, 5, ':', 0, 0, 'L');
            $pdf->SetX(40);
            $pdf->Cell(50, 5, $polis, 0, 0, 'L');
            $pdf->Ln();
            $pdf->SetX(5);
            $pdf->Cell(30, 5, 'Tanggal Klaim', 0, 0, 'L');
            $pdf->SetX(35);
            $pdf->Cell(5, 5, ':', 0, 0, 'L');
            $pdf->SetX(40);
            $pdf->Cell(50, 5, $tgl_klaim, 0, 0, 'L');
            $pdf->Ln();
            $pdf->SetX(5);
            $pdf->Cell(30, 5, 'Status Klaim', 0, 0, 'L');
            $pdf->SetX(35);
            $pdf->Cell(5, 5, ':', 0, 0, 'L');
            $pdf->SetX(40);
            $pdf->Cell(50, 5, $status, 0, 0, 'L');
            $pdf->Ln();
            $pdf->SetX(5);
            $pdf->Cell(30, 5, 'Regional', 0, 0, 'L');
            $pdf->SetX(35);
            $pdf->Cell(5, 5, ':', 0, 0, 'L');
            $pdf->SetX(40);
            $pdf->Cell(50, 5, $regional, 0, 0, 'L');
            $pdf->Ln();
            $pdf->SetX(5);
            $pdf->Cell(30, 5, 'Cabang', 0, 0, 'L');
            $pdf->SetX(35);
            $pdf->Cell(5, 5, ':', 0, 0, 'L');
            $pdf->SetX(40);
            $pdf->Cell(50, 5, $cabang, 0, 0, 'L');
            $pdf->Ln(10);


            $pdf->SetX(5);
            $pdf->Cell(10, 5, 'No.', 1, 0, 'C');
            $pdf->SetX(15);
            $pdf->Cell(30, 5, 'Asuransi', 1, 0, 'L');
            $pdf->SetX(45);
            $pdf->Cell(20, 5, 'Produk', 1, 0, 'L');
            $pdf->SetX(65);
            $pdf->Cell(25, 5, 'Credit Note', 1, 0, 'L');
            $pdf->SetX(90);
            $pdf->Cell(20, 5, 'Periode Asuransi', 1, 0, 'L');
            $pdf->SetX(110);
            $pdf->Cell(15, 5, 'Tanggal CN', 1, 0, 'C');
            $pdf->SetX(125);
            $pdf->Cell(15, 5, 'No. Reg', 1, 0, 'L');
            $pdf->SetX(140);
            $pdf->Cell(30, 5, 'Nama Debitur', 1, 0, 'L');
            $pdf->SetX(170);
            $pdf->Cell(20, 5, 'Cabang', 1, 0, 'L');
            $pdf->SetX(190);
            $pdf->Cell(15, 5, 'Tgl Lahir', 1, 0, 'C');
            $pdf->SetX(205);
            $pdf->Cell(10, 5, 'Usia', 1, 0, 'C');
            $pdf->SetX(215);
            $pdf->Cell(15, 5, 'Plafond', 1, 0, 'R');
            $pdf->SetX(230);
            $pdf->Cell(15, 5, 'Nilai Klaim', 1, 0, 'R');
            $pdf->SetX(245);
            $pdf->Cell(15, 5, 'As. Bayar', 1, 0, 'R');
            $pdf->SetX(260);
            $pdf->Cell(35, 5, 'Status', 1, 0, 'L');
            $pdf->Ln();
            $no=1;

            $pdf->SetFont('Times', '', 6);
            while ($metCL_ = mysql_fetch_array($met)) {
                $pdf->SetX(5);
                $pdf->Cell(10, 5, $no, 1, 0, 'C');
                $pdf->SetX(15);
                $pdf->Cell(30, 5, $metCL_['nmAsuransi'], 1, 0, 'L');
                $pdf->SetX(45);
                $pdf->Cell(20, 5, $metCL_['nmproduk'], 1, 0, 'L');
                $pdf->SetX(65);
                $pdf->Cell(25, 5, $metCL_['id_cn'], 1, 0, 'L');
                $pdf->SetX(90);
                $pdf->Cell(20, 5, $metCL_['kredit_tgl'].' s.d '.$metCL_['kredit_akhir'], 1, 0, 'L');
                $pdf->SetX(110);
                $pdf->Cell(15, 5, $metCL_['tgl_createcn'], 1, 0, 'C');
                $pdf->SetX(125);
                $pdf->Cell(15, 5, $metCL_['id_peserta'], 1, 0, 'L');
                $pdf->SetX(140);
                $pdf->Cell(30, 5, $metCL_['nama'], 1, 0, 'L');
                $pdf->SetX(170);
                $pdf->Cell(20, 5, $metCL_['cabang'], 1, 0, 'L');
                $pdf->SetX(190);
                $pdf->Cell(15, 5, $metCL_['tgl_lahir'], 1, 0, 'C');
                $pdf->SetX(205);
                $pdf->Cell(10, 5, $metCL_['usia'], 1, 0, 'C');
                $pdf->SetX(215);
                $pdf->Cell(15, 5, duit($metCL_['kredit_jumlah']), 1, 0, 'R');
                $pdf->SetX(230);
                $pdf->Cell(15, 5, duit($metCL_['total_claim']), 1, 0, 'R');
                $pdf->SetX(245);
                $pdf->Cell(15, 5, duit($metCL_['total_bayar_asuransi']), 1, 0, 'R');
                $pdf->SetX(260);
                $pdf->Cell(35, 5, $metCL_['status_klaim'], 1, 0, 'L');
                $pdf->Ln();
                $no++;

                $t_plafond+=$metCL_['kredit_jumlah'];
                $t_as_bayar+=$metCL_['total_bayar_asuransi'];
                $t_klaim+=$metCL_['total_claim'];
            }

            $pdf->Ln();

            $pdf->SetFont('Times', 'B', 6);

            $pdf->SetX(190);
            $pdf->Cell(25, 5, 'TOTAL', 0, 0, 'C');
            $pdf->SetX(215);
            $pdf->Cell(15, 5, duit($t_plafond), 0, 0, 'R');
            $pdf->SetX(230);
            $pdf->Cell(15, 5, duit($t_klaim), 0, 0, 'R');
            $pdf->SetX(245);
            $pdf->Cell(15, 5, duit($t_as_bayar), 0, 0, 'R');


            $pdf->SetFont('Times', 'B', 7);

            $pdf->SetX(5);
            $pdf->Cell(50, 5, 'JUMLAH PESERTA', 0, 0, 'L');
            $pdf->SetX(55);
            $pdf->Cell(5, 5, ':', 0, 0, 'L');
            $pdf->SetX(60);
            $pdf->Cell(50, 5, $no-1, 0, 0, 'R');
            $pdf->Ln(4);
            $pdf->SetX(5);
            $pdf->Cell(50, 5, 'JUMLAH PLAFOND', 0, 0, 'L');
            $pdf->SetX(55);
            $pdf->Cell(5, 5, ':', 0, 0, 'L');
            $pdf->SetX(60);
            $pdf->Cell(50, 5, duit($t_plafond), 0, 0, 'R');
            $pdf->Ln(4);
            $pdf->SetX(5);
            $pdf->Cell(50, 5, 'JUMLAH KLAIM', 0, 0, 'L');
            $pdf->SetX(55);
            $pdf->Cell(5, 5, ':', 0, 0, 'L');
            $pdf->SetX(60);
            $pdf->Cell(50, 5, duit($t_klaim), 0, 0, 'R');
            $pdf->Ln(4);
            $pdf->SetX(5);
            $pdf->Cell(50, 5, 'JUMLAH ASURANSI BAYAR', 0, 0, 'L');
            $pdf->SetX(55);
            $pdf->Cell(5, 5, ':', 0, 0, 'L');
            $pdf->SetX(60);
            $pdf->Cell(50, 5, duit($t_as_bayar), 0, 0, 'R');
            $pdf->Ln(10);

            $pdf->SetX(5);
            $pdf->Cell(50, 5, "Note :", 0, 0, 'L');
            $pdf->Ln(4);
            $pdf->SetX(5);
            $pdf->MultiCell(0, 4, "Mohon diperiksa dan dicocokan dengan data yang ada di Cabang ".$cost.", jika terdapat perbedaan harap memberitahukan secara tertulis ke PT. Adonai Pialang Asuransi serta mengisi Form Revisi (terlampir) dan tembusan dikirim ke ".$cost.", PUSAT. Apabila dalam kurun waktu 7 (tujuh) hari kerja sejak diterimanya daftar peserta ini tidak ada pemberitahuan, maka data diatas kami anggap sudah benar dan sesuai dengan data yang ada di ".$cost." Cabang ".$cabang.".");
            $pdf->Ln(6);
            $pdf->SetX(5);
            $pdf->cell(30, 5, 'Bekasi, '.$Today_.'', 0, 0, 'C');
            $pdf->Ln();	$pdf->cell(70, 5, 'Disampaikan oleh', 0, 0, 'C');			$pdf->cell(100, 5, '', 0, 0, 'C');			$pdf->cell(80, 5, 'Mengetahui dan Menyetujui', 0, 0, 'C');
            $pdf->Ln();	$pdf->cell(70, 3, 'PT. Adonai Pialang Asuransi', 0, 0, 'C');	$pdf->cell(100, 3, '', 0, 0, 'C');
            $pdf->cell(80, 3, $cost.', Cabang '.$cabang.'', 0, 0, 'C');
            $pdf->Ln();$pdf->Ln();$pdf->Ln();$pdf->Ln();$pdf->Ln();$pdf->Ln();
            $pdf->cell(70, 5, '( Dessy Puji Astuti )', 0, 0, 'C');		$pdf->cell(100, 5, '', 0, 0, 'C');		$pdf->cell(80, 5, '(                                                        )', 0, 0, 'C');$pdf->Ln();
            $pdf->cell(70, 1, 'Manager Life Insurance', 0, 0, 'C');		$pdf->cell(100, 5, '', 0, 0, 'C');
            $pdf->Output();
            ;
            break;

case "feepemeriksaandokterdetail":

                function HeaderingExcel($filename)
                {
                    header("Content-type: application/vnd.ms-excel");
                    header("Content-Disposition: attachment; filename=$filename");
                    header("Expires: 0");
                    header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
                    header("Pragma: public");
                }
                HeaderingExcel('FEE PEMERIKSAAN DOKTER DETAIL.xls');
                $workbook = new Workbook("");
                $worksheet1 =& $workbook->add_worksheet('DATA PEMERIKSAAN DOKTER');
                $format =& $workbook->add_format();
                $format->setAlign('vcenter');	$format->setAlign('center');	$format->setColor('white');	$format->setBold();	$format->setPattern();	$format->setFgColor('green');
                $fjudul =& $workbook->add_format();	$fjudul->setAlign('vcenter');	$fjudul->setAlign('center');	$fjudul->setBold();

                $worksheet1->setMerge(0, 0, 0, 7);	$worksheet1->writeString(0, 0, "DATA PEMERIKSAAN DOKTER", $fjudul, 1);
                $worksheet1->setMerge(1, 0, 1, 7);	$worksheet1->writeString(1, 0, "TANGGAL PEMERIKSAAN "._convertDate($_REQUEST['tgl1'])." s/d "._convertDate($_REQUEST['tgl2'])."", $fjudul);

                $worksheet1->setRow(4, 15);
                $worksheet1->setColumn(4, 0, 5);	$worksheet1->writeString(4, 0, "No", $format);
                $worksheet1->setColumn(4, 1, 5);	$worksheet1->writeString(4, 1, "Nama", $format);
                $worksheet1->setColumn(4, 2, 5);	$worksheet1->writeString(4, 2, "No SPK", $format);
                $worksheet1->setColumn(4, 3, 5);	$worksheet1->writeString(4, 3, "Tgl Periksa", $format);
                $worksheet1->setColumn(4, 4, 5);	$worksheet1->writeString(4, 4, "Usia", $format);
                $worksheet1->setColumn(4, 5, 5);	$worksheet1->writeString(4, 5, "Cabang", $format);
                $worksheet1->setColumn(4, 5, 5);	$worksheet1->writeString(4, 6, "No Pensiun", $format);
                $worksheet1->setColumn(4, 6, 5);	$worksheet1->writeString(4, 7, "Keterangan", $format);


                $baris = 5;
                $sql="";

                $kucing = mysql_query("	SELECT *
																FROM (
																SELECT fu_ajk_spak_form.nama,
																				fu_ajk_spak.spak,
																				DATE_FORMAT(fu_ajk_spak_form.tgl_periksa,'%d-%m-%Y')as tgl_periksa,
																				fu_ajk_spak_form.x_usia,
																				fu_ajk_cabang.name,
																				fu_ajk_spak_form.nopermohonan,
																				catatan
																FROM fu_ajk_spak_form
																		INNER JOIN fu_ajk_spak ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
																		INNER JOIN fu_ajk_cabang ON fu_ajk_cabang.id = fu_ajk_spak_form.cabang and fu_ajk_cabang.del is null
																WHERE dokter_pemeriksa = ".$_REQUEST ['id_dokter']." and
																			tgl_periksa between '".$_REQUEST ['tgl1']."' and '".$_REQUEST ['tgl2']."'
																			and (nopermohonan is NULL or nopermohonan = '')

																UNION ALL

																SELECT fu_ajk_spak_form.nama,
																				fu_ajk_spak.spak,
																				DATE_FORMAT(fu_ajk_spak_form.tgl_periksa,'%d-%m-%Y')as tgl_periksa,
																				fu_ajk_spak_form.x_usia,
																				fu_ajk_cabang.name,
																				fu_ajk_spak_form.nopermohonan,
																				catatan
																FROM fu_ajk_spak_form
																		INNER JOIN fu_ajk_spak ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
																		INNER JOIN fu_ajk_cabang ON fu_ajk_cabang.id = fu_ajk_spak_form.cabang and fu_ajk_cabang.del is null
																WHERE dokter_pemeriksa = ".$_REQUEST ['id_dokter']." and
																			tgl_periksa between '".$_REQUEST ['tgl1']."' and '".$_REQUEST ['tgl2']."' and nopermohonan != ''
																GROUP BY nopermohonan)as temp
																ORDER BY tgl_periksa");
                while ($datanya_ = mysql_fetch_array($kucing)) {
                    $worksheet1->writeNumber($baris, 0, ++$no);
                    $worksheet1->writeString($baris, 1, $datanya_['nama']);
                    $worksheet1->writeString($baris, 2, $datanya_['spak']);
                    $worksheet1->writeString($baris, 3, $datanya_['tgl_periksa']);
                    $worksheet1->writeString($baris, 4, $datanya_['x_usia']);
                    $worksheet1->writeString($baris, 5, $datanya_['name']);
                    $worksheet1->writeString($baris, 6, $datanya_['nopermohonan']);
                    $worksheet1->writeString($baris, 7, $datanya_['catatan']);

                    $baris++;
                }

                $workbook->close();
                ;
                break;

                case "klaim_outstanding":
                    set_time_limit(50000);
                    // ini_set('display_errors', 1);
                    // ini_set('display_startup_errors', 1);
                    // error_reporting(E_ALL);
                    function HeaderingExcel($filename)
                    {
                        header("Content-type: application/vnd.ms-excel");
                        header("Content-Disposition: attachment; filename=$filename");
                        header("Expires: 0");
                        header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
                        header("Pragma: public");
                    }
                    HeaderingExcel('All Klaim.xls');
                    $workbook = new Spreadsheet_Excel_Writer("");
                    $workbook->setVersion(8);
                    $worksheet1 =& $workbook->addWorksheet('DATA ALL KLAIM');
                    $format =& $workbook->addFormat();
                    $format->setAlign('vcenter');
                    $format->setAlign('center');
                    $format->setColor('white');
                    $format->setBold();
                    $format->setPattern();
                    $format->setFgColor('green');
                    $fjudul =& $workbook->addFormat();
                    $fjudul->setAlign('vcenter');
                    $fjudul->setAlign('center');
                    $fjudul->setBold();


                    $worksheet1->setMerge(0, 0, 0, 53);	$worksheet1->writeString(0, 0, "DATA KLAIM OUTSTANDING", $fjudul, 1);
                    $worksheet1->setMerge(1, 0, 1, 53);	$worksheet1->writeString(1, 0, "PERUSAHAAN ", $fjudul);
                    $worksheet1->setMerge(2, 0, 2, 53);	$worksheet1->writeString(2, 0, "PRODUK ", $fjudul);
                    $worksheet1->setMerge(3, 0, 3, 53);	$worksheet1->writeString(3, 0, "ASURANSI ", $fjudul);
                    $worksheet1->setMerge(4, 0, 4, 53);	$worksheet1->writeString(4, 0, "STATUS BAYAR ", $fjudul);
                    $worksheet1->setMerge(5, 0, 5, 53);	$worksheet1->writeString(4, 0, "STATUS KLAIM ", $fjudul);
                    $worksheet1->setMerge(6, 0, 6, 53);	$worksheet1->writeString(4, 0, "KOL ", $fjudul);
                    $worksheet1->setMerge(7, 0, 7, 53);	$worksheet1->writeString(4, 0, "TANGGAL KLAIM "._convertDate($_REQUEST['tgl1'])." s/d "._convertDate($_REQUEST['tgl2'])."", $fjudul);

                    $sql="";


                    if ($_REQUEST['id_asuransi']=="all") {
                        $asuransi="";
                    } else {
                        $asuransi="and fu_ajk_asuransi.id=".$_REQUEST['id_asuransi'];
                    }

                    if (empty($_REQUEST['id_polis'])) {
                        $polis="";
                    } else {
                        $polis=" and fu_ajk_polis.id=".$_REQUEST['id_polis'];
                    }

                    if ($_REQUEST['liability']=='ALL') {
                        $liability='';
                    } else {
                        $liability=' and fu_ajk_cn.policy_liability="'.$_REQUEST['liability'].'"';
                    }
                    $tgl_lapor='';
                    if ($_REQUEST['tglcheck1']!=="") {
                        $tgl_lapor=' and fu_ajk_cn.approve_date between "'.$_REQUEST['tglcheck1'].'" and "'.$_REQUEST['tglcheck2'].'"';
                    }

                    $tipe_produk='';
                    if ($_REQUEST['tipe_produk']!=="All") {
                        $tipe_produk=' and fu_ajk_polis.typeproduk="'.$_REQUEST['tipe_produk'].'"';
                    }

                    $tgl_dol='';
                    if ($_REQUEST['tglcheck3']!=="") {
                        $tgl_lapor=' and fu_ajk_cn.tgl_claim between "'.$_REQUEST['tglcheck1'].'" and "'.$_REQUEST['tglcheck2'].'"';
                    }


                    if (empty($_REQUEST['status_klaim'])) {
                        $status_klaim="";
                    } else {
                        $status_klaim="  and if(`id_klaim_status`=6,'Ditolak',if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap','Dokumen Belum Lengkap')) ='".$_REQUEST['status_klaim']."'";
                    }

                    if (empty($_REQUEST['status_bayar'])) {
                        $status_bayar="";
                    } else {
                        $status_bayar=" and fu_ajk_cn.status_bayar=".$_REQUEST['status_bayar'];
                    }

                    if (empty($_REQUEST['kol'])) {
                        $kol="";
                    } else {
                        $kol=" and
                			IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',
                			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
                			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
                			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5'))),
                			IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
                			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
                			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5'))))=".$_REQUEST['kol'];
                    }

                    $kucing = mysql_query("SELECT
                		CONCAT(DATE_FORMAT(fu_ajk_cn.approve_date,'%Y%m'),'/',fu_ajk_klaim.id) AS klaim_id,
                		fu_ajk_klaim.no_urut_klaim,
                		fu_ajk_cn.id_cabang,
                		fu_ajk_grupproduk.nmproduk AS mitra,
                		fu_ajk_asuransi.`name`,
                		fu_ajk_asuransi.code,
                		fu_ajk_polis.nmproduk,
                		IF(fu_ajk_polis.`typeproduk`='NON SPK','Percepatan','Reguler') AS kategori,
                		fu_ajk_peserta.id_peserta,
                		fu_ajk_peserta.nama,
                		fu_ajk_peserta.tgl_lahir,
                		fu_ajk_peserta.usia,
                		fu_ajk_peserta.kredit_jumlah,
                		fu_ajk_cn.total_claim,
                		fu_ajk_cn.tuntutan_klaim,
                		DATEDIFF(fu_ajk_cn.approve_date,fu_ajk_cn.tgl_claim)as diftoday,DATE_ADD(fu_ajk_cn.tgl_claim,INTERVAL 120 DAY)as date_exp,
                		fu_ajk_peserta.kredit_tgl,
                		fu_ajk_peserta.spaj,
                		ROUND(IF(fu_ajk_peserta.type_data='SPK',fu_ajk_peserta.kredit_tenor*12,fu_ajk_peserta.kredit_tenor)/12) AS tenor,
                		fu_ajk_klaim.tgl_klaim AS dol,
                		DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl) AS akad_dol,
                		fu_ajk_cn.approve_date AS tgl_terima_laporan,
                		DATEDIFF(fu_ajk_cn.approve_date,fu_ajk_klaim.tgl_klaim) AS lama_terima_laporan,
                		'' AS tgl_update_klaim,
                		IF(fu_ajk_klaim.tgl_lapor_klaim='0000-00-00',NULL,fu_ajk_klaim.tgl_lapor_klaim) AS tgl_lapor_asuransi,
                		fu_ajk_cn.keterangan AS kelengkapan_dokumen,
                		IF(fu_ajk_klaim.tgl_document_lengkap='0000-00-00',NULL,fu_ajk_klaim.tgl_document_lengkap) AS tgl_status_lengkap,
                		IF(fu_ajk_polis.`typeproduk`='NON SPK',DATE_ADD(fu_ajk_klaim.tgl_document_lengkap,INTERVAL 28 DAY),DATE_ADD(fu_ajk_klaim.tgl_document_lengkap,INTERVAL 14 DAY)) as due_date,
                		IF(fu_ajk_klaim.tgl_kirim_dokumen='0000-00-00',NULL,fu_ajk_klaim.tgl_kirim_dokumen) AS tgl_kirim_dokumen,
                		CURRENT_DATE() AS today,
                		IF(fu_ajk_klaim.tgl_kirim_dokumen<>'0000-00-00' OR fu_ajk_klaim.tgl_kirim_dokumen IS NOT NULL,DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_kirim_dokumen),'') AS status_release,
                		/*fu_ajk_spak.ext_premi*/ '' AS EM,
                		/*fu_ajk_spak.ket_ext*/ '' AS keterangan_EM,
                		/*if(user_mobile.namalengkap is null,fu_ajk_spak_form.dokter_pemeriksa,user_mobile.namalengkap)*/ '' as dokter_pemeriksa,
                		IF(fu_ajk_klaim.tgl_investigasi='0000-00-00',NULL,fu_ajk_klaim.tgl_investigasi) AS tgl_investigasi,
                		fu_ajk_klaim.diagnosa AS hasil_investigasi,
                		fu_ajk_namapenyakit.namapenyakit AS penyebab_meinggal,
                		fu_ajk_cn.policy_liability AS polis_liability,
                		fu_ajk_pembayaran_status.pembayaran_status,
                		fu_ajk_klaim_status.status_klaim,
                    fu_ajk_peserta.regional,
                		fu_ajk_klaim.tgl_surat_reminder1,
                		fu_ajk_klaim.tgl_surat_reminder2,
                		fu_ajk_klaim.tgl_surat_reminder3,
                		fu_ajk_klaim_status.status_klaim,
                		if(`id_klaim_status`=6,'Ditolak',
                		if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
                		'Dokumen Belum Lengkap'))  AS status_dokumen,
                		'' AS keterangan_asuransi,
                		fu_ajk_cn.total_bayar_asuransi,
                		'' AS ref_bayar_asuransi,
                		fu_ajk_cn.tgl_bayar_asuransi,
                		fu_ajk_klaim.estimasi_bayar AS nilai_pengajuan_keuangan,
                		fu_ajk_cn.total_claim  AS bayar_ke_bank,
                		'' AS ref_pembayaran_ke_bank,
                		fu_ajk_cn.tgl_byr_claim AS tgl_bayar_ke_client,
                        fu_ajk_klaim.tempat_meninggal AS tempat_meninggal,
                		(CASE WHEN fu_ajk_klaim.id_klaim_status = 7 THEN rencana_bayar ELSE 0 END)as approve,
                		IF(DATEDIFF(fu_ajk_cn.approve_date,fu_ajk_cn.tgl_claim) > 90,'Ya',
                		if(if(DATEDIFF(fu_ajk_cn.tgl_claim,fu_ajk_klaim.tgl_document_lengkap) is null,
                		DATEDIFF(current_date(),fu_ajk_cn.approve_date),
                		DATEDIFF(fu_ajk_klaim.tgl_document_lengkap,fu_ajk_cn.approve_date)) > 150,'Ya','')) as kadaluarsa,
                		ifnull(fu_ajk_cn.total_bayar_asuransi,0)-fu_ajk_cn.total_claim AS selisih,
                		IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',
                		IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
                		IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 AND DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
                		IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5'))),
                		IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
                		IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
                		IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol,
                    (SELECT tgl_claim
                    FROM fu_ajk_peserta pes
                    INNER JOIN fu_ajk_cn cn
                    ON cn.id = pes.id_klaim
                    WHERE cn.type_refund = 'Topup' and
                          pes.nama = fu_ajk_peserta.nama and
                          pes.tgl_lahir = fu_ajk_peserta.tgl_lahir and
                          pes.cabang = fu_ajk_peserta.cabang
                          ORDER BY tgl_claim desc limit 1)AS topup,
                          date_format(fu_ajk_analisa_klaim.input_date,'%Y-%m-%d') as tgl_kirim_analisa,
                          fu_ajk_analisa_klaim.kode_diagnosa,
                          fu_ajk_analisa_klaim.ext_penyebab,
                          fu_ajk_analisa_klaim.analisa_bank,
                          fu_ajk_analisa_klaim.analisa_asuransi,
                          -- '' as tolak_asuransi,'' as tgl_tolak_asuransi,
													-- ''as sanggahan,''as info_asuransi,'' as tolak_bukopin,
                      (select alasan_penolakan from fu_ajk_penolakan where trim(id_peserta) = fu_ajk_cn.id_peserta and del is null and tipe_penolakan='Tolakan Asuransi' order by tgl_penolakan limit 1)as tolak_asuransi,
                      (select tgl_penolakan from fu_ajk_penolakan where trim(id_peserta) = fu_ajk_cn.id_peserta and del is null and tipe_penolakan='Tolakan Asuransi' order by tgl_penolakan limit 1)as tgl_tolak_asuransi,
                      (select alasan_penolakan from fu_ajk_penolakan where trim(id_peserta) = fu_ajk_cn.id_peserta and del is null and tipe_penolakan='Sanggahan' order by tgl_penolakan limit 1)as sanggahan,
                      (select alasan_penolakan from fu_ajk_penolakan where trim(id_peserta) = fu_ajk_cn.id_peserta and del is null and tipe_penolakan='Informasi Klaim' order by tgl_penolakan limit 1)as info_asuransi,
                      (select alasan_penolakan from fu_ajk_penolakan where trim(id_peserta) = fu_ajk_cn.id_peserta and del is null and tipe_penolakan='Tolak Bukopin' order by tgl_penolakan limit 1)as tolak_bukopin
                		FROM
                		fu_ajk_cn
                		INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
                		INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
                		INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
                		INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
                		INNER JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id and fu_ajk_klaim.del is null
                		LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id=fu_ajk_klaim.id_klaim_status
                		LEFT JOIN fu_ajk_grupproduk ON fu_ajk_grupproduk.id=fu_ajk_peserta.nama_mitra
                		LEFT JOIN fu_ajk_namapenyakit ON fu_ajk_namapenyakit.id=fu_ajk_klaim.sebab_meninggal
                		LEFT JOIN fu_ajk_pembayaran_status ON fu_ajk_pembayaran_status.id=fu_ajk_cn.status_bayar
                    LEFT JOIN fu_ajk_analisa_klaim ON fu_ajk_analisa_klaim.id_peserta=fu_ajk_peserta.id_peserta AND type_analisa='medical'
                		/*LEFT JOIN fu_ajk_spak ON fu_ajk_spak.spak = fu_ajk_peserta.spaj and fu_ajk_spak.del is null and fu_ajk_spak.`status`='Realisasi'
                		LEFT JOIN fu_ajk_spak_form ON fu_ajk_spak.id = fu_ajk_spak_form.idspk and fu_ajk_spak_form.del is null
                		LEFT JOIN user_mobile ON fu_ajk_spak_form.dokter_pemeriksa = user_mobile.id*/
                		WHERE fu_ajk_cn.type_claim='Death' AND fu_ajk_cn.del IS NULL AND `fu_ajk_cn`.`confirm_claim` <> 'Pending'
                		AND fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']." ".$asuransi." ".$tipe_produk." ".$polis." ".$status_bayar." ".$status_klaim." ".$kol." ".$tgl_dol." ".$tgl_lapor." ".$liability."
                		ORDER BY fu_ajk_asuransi.id,fu_ajk_polis.`typeproduk`,
                		IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',
                		IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
                		IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 AND DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
                		IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5'))),
                		IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
                		IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
                		IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))),
                		fu_ajk_cn.id DESC");

                    if ($_REQUEST['format_report']=='1') {
                        $worksheet1->setRow(4, 15);
                        $worksheet1->setColumn(9, 0, 5);
                        $worksheet1->writeString(9, 0, "No", $format);
                        $worksheet1->setColumn(9, 1, 5);
                        $worksheet1->writeString(9, 1, "Urut", $format);
                        $worksheet1->setColumn(9, 2, 5);
                        $worksheet1->writeString(9, 2, "Cabang", $format);
                        $worksheet1->setColumn(9, 3, 5);
                        $worksheet1->writeString(9, 3, "Mitra", $format);
                        $worksheet1->setColumn(9, 4, 5);
                        $worksheet1->writeString(9, 4, "Cover Asuransi", $format);
                        $worksheet1->setColumn(9, 5, 5);
                        $worksheet1->writeString(9, 5, "Produk", $format);
                        $worksheet1->setColumn(9, 6, 5);
                        $worksheet1->writeString(9, 6, "ID Peserta", $format);
                        $worksheet1->setColumn(9, 7, 5);
                        $worksheet1->writeString(9, 7, "Nama Debitur", $format);
                        $worksheet1->setColumn(9, 8, 5);
                        $worksheet1->writeString(9, 8, "Tgl Lahir", $format);
                        $worksheet1->setColumn(9, 9, 5);
                        $worksheet1->writeString(9, 9, "Usia", $format);
                        $worksheet1->setColumn(9, 10, 5);
                        $worksheet1->writeString(9, 10, "Plafond Kredit", $format);
                        $worksheet1->setColumn(9, 11, 5);
                        $worksheet1->writeString(9, 11, "Tuntutan Klaim", $format);
                        $worksheet1->setColumn(9, 12, 5);
                        $worksheet1->writeString(9, 12, "Tgl Akad", $format);
                        $worksheet1->setColumn(9, 13, 5);
                        $worksheet1->writeString(9, 13, "J.Wkt (Th.)", $format);
                        $worksheet1->setColumn(9, 14, 5);
                        $worksheet1->writeString(9, 14, "DOL", $format);
                        $worksheet1->setColumn(9, 15, 5);
                        $worksheet1->writeString(9, 15, "Akad s/d DOL (hari)", $format);
                        $worksheet1->setColumn(9, 16, 5);
                        $worksheet1->writeString(9, 16, "Tgl. Terima Laporan", $format);
                        $worksheet1->setColumn(9, 17, 5);
                        $worksheet1->writeString(9, 17, "Dur", $format);
                        $worksheet1->setColumn(9, 18, 5);
                        $worksheet1->writeString(9, 18, "Tgl. lapor As", $format);
                        $worksheet1->setColumn(9, 19, 5);
                        $worksheet1->writeString(9, 19, "Kelengkapan Dokumen", $format);
                        $worksheet1->setColumn(9, 20, 5);
                        $worksheet1->writeString(9, 20, "Tgl. Status Lengkap", $format);
                        $worksheet1->setColumn(9, 21, 5);
                        $worksheet1->writeString(9, 21, "Tgl. Pengajuan Medical", $format);
                        $worksheet1->setColumn(9, 22, 5);
                        $worksheet1->writeString(9, 22, "R ke", $format);
                        $worksheet1->setColumn(9, 23, 5);
                        $worksheet1->writeString(9, 23, "Tgl R", $format);
                        $worksheet1->setColumn(9, 24, 5);
                        $worksheet1->writeString(9, 24, "Due Date", $format);
                        $worksheet1->setColumn(9, 25, 5);
                        $worksheet1->writeString(9, 25, "Dok As Date", $format);
                        $worksheet1->setColumn(9, 26, 5);
                        $worksheet1->writeString(9, 26, "SLA As", $format);
                        $worksheet1->setColumn(9, 27, 5);
                        $worksheet1->writeString(9, 27, "EM", $format);
                        $worksheet1->setColumn(9, 28, 5);
                        $worksheet1->writeString(9, 28, "EM Keterangan", $format);
                        $worksheet1->setColumn(9, 29, 5);
                        $worksheet1->writeString(9, 29, "Dokter Pemeriksa", $format);
                        $worksheet1->setColumn(9, 30, 5);
                        $worksheet1->writeString(9, 30, "SPAK", $format);
                        $worksheet1->setColumn(9, 31, 5);
                        $worksheet1->writeString(9, 31, "Tgl. Inv", $format);
                        $worksheet1->setColumn(9, 32, 5);
                        $worksheet1->writeString(9, 32, "Hasil investigasi", $format);
                        $worksheet1->setColumn(9, 33, 5);
                        $worksheet1->writeString(9, 33, "Penyebab Kematian", $format);
                        $worksheet1->setColumn(9, 34, 5);
                        $worksheet1->writeString(9, 34, "Liability", $format);
                        $worksheet1->setColumn(9, 35, 5);
                        $worksheet1->writeString(9, 35, "Status", $format);
                        $worksheet1->setColumn(9, 36, 5);
                        $worksheet1->writeString(9, 36, "Paid As", $format);
                        $worksheet1->setColumn(9, 37, 5);
                        $worksheet1->writeString(9, 37, "Paid As Date", $format);
                        $worksheet1->setColumn(9, 38, 5);
                        $worksheet1->writeString(9, 38, "Approve", $format);
                        $worksheet1->setColumn(9, 39, 5);
                        $worksheet1->writeString(9, 39, "Paid B", $format);
                        $worksheet1->setColumn(9, 40, 5);
                        $worksheet1->writeString(9, 40, "Paid C Date", $format);
                        $worksheet1->setColumn(9, 41, 5);
                        $worksheet1->writeString(9, 41, "Selisih", $format);
                        $worksheet1->setColumn(9, 42, 5);
                        $worksheet1->writeString(9, 42, "Kol", $format);
                        $worksheet1->setColumn(9, 43, 5);
                        $worksheet1->writeString(9, 43, "Exp", $format);
                        $worksheet1->setColumn(9, 44, 5);
                        $worksheet1->writeString(9, 44, "Top Up", $format);
                        $worksheet1->setColumn(9, 45, 5);
                    	$worksheet1->writeString(9, 45, "Tempat Meninggal", $format);
                    	$worksheet1->setColumn(9, 46, 5);
                    	$worksheet1->writeString(9, 46, "Batas Kadaluarsa", $format);
                        $worksheet1->setColumn(9, 47, 5);
                        $worksheet1->writeString(9, 47, "Tolakan Asuransi", $format);
                        $worksheet1->setColumn(9, 48, 5);
                        $worksheet1->writeString(9, 48, "Tgl Tolakan Asuransi", $format);
                        $worksheet1->setColumn(9, 49, 5);
                        $worksheet1->writeString(9, 49, "Sanggahan", $format);
                        $worksheet1->setColumn(9, 50, 5);
                        $worksheet1->writeString(9, 50, "Info Bukopin", $format);
                        $worksheet1->setColumn(9, 51, 5);
                        $worksheet1->writeString(9, 51, "Tolak Bukopin", $format);
                        $worksheet1->setColumn(9, 52, 5);
                        $worksheet1->writeString(9, 52, "Kode Diagnosa", $format);
                        $worksheet1->setColumn(9, 53, 5);
                        $worksheet1->writeString(9, 53, "Penyebab Kematian (AMK)", $format);
                        $worksheet1->setColumn(9, 54, 5);
                        $worksheet1->writeString(9, 54, "Regional", $format);

                        $baris = 10;
                        $no=1;
                        while ($datanya_ = mysql_fetch_array($kucing)) {
                            if ($datanya_['tgl_surat_reminder3']!='0000-00-00') {
                                $tgl_reminder = $datanya_['tgl_surat_reminder3'];
                                $reminder_ke = "3";
                            } elseif ($datanya_['tgl_surat_reminder2']!='0000-00-00') {
                                $tgl_reminder = $datanya_['tgl_surat_reminder2'];
                                $reminder_ke = "2";
                            } elseif ($datanya_['tgl_surat_reminder1']!='0000-00-00') {
                                $tgl_reminder = $datanya_['tgl_surat_reminder1'];
                                $reminder_ke = "1";
                            }
                            $worksheet1->writeString($baris, 0, $no);
                            $worksheet1->writeString($baris, 1, $datanya_['no_urut_klaim']);
                            $worksheet1->writeString($baris, 2, $datanya_['id_cabang']);
                            $worksheet1->writeString($baris, 3, $datanya_['mitra']);
                            $worksheet1->writeString($baris, 4, $datanya_['code']);
                            $worksheet1->writeString($baris, 5, $datanya_['nmproduk']);
                            $worksheet1->writeString($baris, 6, $datanya_['id_peserta']);
                            $worksheet1->writeString($baris, 7, $datanya_['nama']);
                            $worksheet1->writeString($baris, 8, $datanya_['tgl_lahir']);
                            $worksheet1->writeString($baris, 9, $datanya_['usia']);
                            $worksheet1->writeNumber($baris, 10, $datanya_['kredit_jumlah']);
                            $worksheet1->writeNumber($baris, 11, $datanya_['tuntutan_klaim']);
                            $worksheet1->writeString($baris, 12, $datanya_['kredit_tgl']);
                            $worksheet1->writeNumber($baris, 13, $datanya_['tenor']);
                            $worksheet1->writeString($baris, 14, $datanya_['dol']);
                            $worksheet1->writeNumber($baris, 15, $datanya_['akad_dol']);
                            $worksheet1->writeString($baris, 16, $datanya_['tgl_terima_laporan']);
                            $worksheet1->writeString($baris, 17, $datanya_['lama_terima_laporan']);
                            $worksheet1->writeString($baris, 18, $datanya_['tgl_lapor_asuransi']);
                            $worksheet1->writeString($baris, 19, $datanya_['kelengkapan_dokumen']);
                            $worksheet1->writeString($baris, 20, $datanya_['tgl_status_lengkap']);
                            $worksheet1->writeString($baris, 21, $datanya_['tgl_kirim_analisa']);
                            $worksheet1->writeString($baris, 22, $reminder_ke);
                            $worksheet1->writeString($baris, 23, $tgl_reminder);
                            $worksheet1->writeString($baris, 24, $datanya_['due_date']);
                            $worksheet1->writeString($baris, 25, $datanya_['tgl_kirim_dokumen']);
                            $worksheet1->writeString($baris, 26, $datanya_['status_release']);
                            $worksheet1->writeString($baris, 27, $datanya_['EM']);
                            $worksheet1->writeString($baris, 28, $datanya_['keterangan_EM']);
                            $worksheet1->writeString($baris, 29, $datanya_['dokter_pemeriksa']);
                            $worksheet1->writeString($baris, 30, $datanya_['spaj']);
                            $worksheet1->writeString($baris, 31, $datanya_['tgl_investigasi']);
                            $worksheet1->writeString($baris, 32, $datanya_['hasil_investigasi']);
                            $worksheet1->writeString($baris, 33, $datanya_['penyebab_meinggal']);
                            $worksheet1->writeString($baris, 34, $datanya_['polis_liability']);
                            $worksheet1->writeString($baris, 35, $datanya_['status_klaim']);
                            $worksheet1->writeNumber($baris, 36, $datanya_['total_bayar_asuransi']);
                            $worksheet1->writeString($baris, 37, $datanya_['tgl_bayar_asuransi']);
                            //$worksheet1->writeNumber($baris, 37,$datanya_['nilai_pengajuan_keuangan']);
                            $worksheet1->writeNumber($baris, 38, $datanya_['approve']);
                            $worksheet1->writeNumber($baris, 39, $datanya_['bayar_ke_bank']);
                            $worksheet1->writeString($baris, 40, $datanya_['tgl_bayar_ke_client']);
                            $worksheet1->writeNumber($baris, 41, $datanya_['selisih']);
                            $worksheet1->writeString($baris, 42, $datanya_['kol']);
                            $worksheet1->writeString($baris, 43, $datanya_['kadaluarsa']);
                            $worksheet1->writeString($baris, 44, $datanya_['topup']);
                            $worksheet1->writeString($baris, 45, $datanya_['tempat_meninggal']);
                            $worksheet1->writeString($baris, 46, $datanya_['date_exp']);
                            $worksheet1->writeString($baris, 47, $datanya_['tolak_asuransi']);
                            $worksheet1->writeString($baris, 48, $datanya_['tgl_tolak_asuransi']);
                            $worksheet1->writeString($baris, 49, $datanya_['sanggahan']);
                            $worksheet1->writeString($baris, 50, $datanya_['info_asuransi']);
                            $worksheet1->writeString($baris, 51, $datanya_['tolak_bukopin']);
                            $worksheet1->writeString($baris, 52, $datanya_['kode_diagnosa']);
                            $worksheet1->writeString($baris, 53, $datanya_['ext_penyebab']);
                            $worksheet1->writeString($baris, 54, $datanya_['regional']);
                            // $worksheet1->writeString($baris, 54, $datanya_['analisa_bank']);
                            // $worksheet1->writeString($baris, 53, $datanya_['analisa_asuransi']);
                            // $worksheet1->writeString($baris, 51, $datanya_['tolak_bukopin']);
                            $baris++;
                            $no++;
                        }
                    } elseif ($_REQUEST['format_report']=='2') {
                        $worksheet1->setRow(4, 15);
                        $worksheet1->setColumn(9, 0, 5);
                        $worksheet1->writeString(9, 0, "No", $format);
                        $worksheet1->setColumn(9, 1, 5);
                        $worksheet1->writeString(9, 1, "Urut", $format);
                        $worksheet1->setColumn(9, 2, 5);
                        $worksheet1->writeString(9, 2, "Cabang", $format);
                        $worksheet1->setColumn(9, 3, 5);
                        $worksheet1->writeString(9, 3, "Mitra", $format);
                        $worksheet1->setColumn(9, 4, 5);
                        $worksheet1->writeString(9, 4, "Asuransi", $format);
                        $worksheet1->setColumn(9, 5, 5);
                        $worksheet1->writeString(9, 5, "Produk", $format);
                        $worksheet1->setColumn(9, 6, 5);
                        $worksheet1->writeString(9, 6, "ID Peserta", $format);
                        $worksheet1->setColumn(9, 7, 5);
                        $worksheet1->writeString(9, 7, "Nama Debitur", $format);
                        $worksheet1->setColumn(9, 8, 5);
                        $worksheet1->writeString(9, 8, "Tgl Lahir", $format);
                        $worksheet1->setColumn(9, 9, 5);
                        $worksheet1->writeString(9, 9, "Usia", $format);
                        $worksheet1->setColumn(9, 10, 5);
                        $worksheet1->writeString(9, 10, "Plafond Kredit", $format);
                        $worksheet1->setColumn(9, 11, 5);
                        $worksheet1->writeString(9, 11, "Tuntutan Klaim", $format);
                        $worksheet1->setColumn(9, 12, 5);
                        $worksheet1->writeString(9, 12, "Tgl Akad", $format);
                        $worksheet1->setColumn(9, 13, 5);
                        $worksheet1->writeString(9, 13, "J.Wkt (Th.)", $format);
                        $worksheet1->setColumn(9, 14, 5);
                        $worksheet1->writeString(9, 14, "DOL", $format);
                        $worksheet1->setColumn(9, 15, 5);
                        $worksheet1->writeString(9, 15, "Akad s/d DOL (hari)", $format);
                        $worksheet1->setColumn(9, 16, 5);
                        $worksheet1->writeString(9, 16, "Tgl. Terima Laporan", $format);
                        $worksheet1->setColumn(9, 17, 5);
                        $worksheet1->writeString(9, 17, "Dur", $format);
                        $worksheet1->setColumn(9, 18, 5);
                        $worksheet1->writeString(9, 18, "Tgl. lapor As", $format);
                        $worksheet1->setColumn(9, 19, 5);
                        $worksheet1->writeString(9, 19, "Kelengkapan Dokumen", $format);
                        $worksheet1->setColumn(9, 20, 5);
                        $worksheet1->writeString(9, 20, "Tgl. Status Lengkap", $format);
                        $worksheet1->setColumn(9, 21, 5);
                        $worksheet1->writeString(9, 21, "R ke", $format);
                        $worksheet1->setColumn(9, 22, 5);
                        $worksheet1->writeString(9, 22, "Tgl R", $format);
                        $worksheet1->setColumn(9, 23, 5);
                        $worksheet1->writeString(9, 23, "Due Date", $format);
                        $worksheet1->setColumn(9, 24, 5);
                        $worksheet1->writeString(9, 24, "Tgl. kirim Dok. Ke Asuransi", $format);
                        $worksheet1->setColumn(9, 25, 5);
                        $worksheet1->writeString(9, 25, "Status Release Asuransi (hari)", $format);
                        $worksheet1->setColumn(9, 26, 5);
                        $worksheet1->writeString(9, 26, "EM", $format);
                        $worksheet1->setColumn(9, 27, 5);
                        $worksheet1->writeString(9, 27, "EM Keterangan", $format);
                        $worksheet1->setColumn(9, 28, 5);
                        $worksheet1->writeString(9, 28, "Dokter Pemeriksa", $format);
                        $worksheet1->setColumn(9, 29, 5);
                        $worksheet1->writeString(9, 29, "SPAK", $format);
                        $worksheet1->setColumn(9, 30, 5);
                        $worksheet1->writeString(9, 30, "Tgl. Inv", $format);
                        $worksheet1->setColumn(9, 31, 5);
                        $worksheet1->writeString(9, 31, "Hasil investigasi", $format);
                        $worksheet1->setColumn(9, 32, 5);
                        $worksheet1->writeString(9, 32, "Penyebab Kematian", $format);
                        $worksheet1->setColumn(9, 33, 5);
                        $worksheet1->writeString(9, 33, "Liability", $format);
                        $worksheet1->setColumn(9, 34, 5);
                        $worksheet1->writeString(9, 34, "Status", $format);
                        $worksheet1->setColumn(9, 35, 5);
                        $worksheet1->writeString(9, 35, "Approve", $format);
                        $worksheet1->setColumn(9, 36, 5);
                        $worksheet1->writeString(9, 36, "Paid B", $format);
                        $worksheet1->setColumn(9, 37, 5);
                        $worksheet1->writeString(9, 37, "Paid C Date", $format);
                        $worksheet1->setColumn(9, 38, 5);
                        $worksheet1->writeString(9, 38, "Kol", $format);
                        $worksheet1->setColumn(9, 39, 5);
                        $worksheet1->writeString(9, 39, "Exp", $format);
                        $baris = 10;
                        $no=1;
                        while ($datanya_ = mysql_fetch_array($kucing)) {
                            if ($datanya_['tgl_surat_reminder3']!='0000-00-00') {
                                $tgl_reminder = $datanya_['tgl_surat_reminder3'];
                                $reminder_ke = "3";
                            } elseif ($datanya_['tgl_surat_reminder2']!='0000-00-00') {
                                $tgl_reminder = $datanya_['tgl_surat_reminder2'];
                                $reminder_ke = "2";
                            } elseif ($datanya_['tgl_surat_reminder1']!='0000-00-00') {
                                $tgl_reminder = $datanya_['tgl_surat_reminder1'];
                                $reminder_ke = "1";
                            }
                            $worksheet1->writeString($baris, 0, $no);
                            $worksheet1->writeString($baris, 1, $datanya_['no_urut_klaim']);
                            $worksheet1->writeString($baris, 2, $datanya_['id_cabang']);
                            $worksheet1->writeString($baris, 3, $datanya_['mitra']);
                            $worksheet1->writeString($baris, 4, $datanya_['code']);
                            $worksheet1->writeString($baris, 5, $datanya_['nmproduk']);
                            $worksheet1->writeString($baris, 6, $datanya_['id_peserta']);
                            $worksheet1->writeString($baris, 7, $datanya_['nama']);
                            $worksheet1->writeString($baris, 8, $datanya_['tgl_lahir']);
                            $worksheet1->writeString($baris, 9, $datanya_['usia']);
                            $worksheet1->writeNumber($baris, 10, $datanya_['kredit_jumlah']);
                            $worksheet1->writeNumber($baris, 11, $datanya_['tuntutan_klaim']);
                            $worksheet1->writeString($baris, 12, $datanya_['kredit_tgl']);
                            $worksheet1->writeNumber($baris, 13, $datanya_['tenor']);
                            $worksheet1->writeString($baris, 14, $datanya_['dol']);
                            $worksheet1->writeNumber($baris, 15, $datanya_['akad_dol']);
                            $worksheet1->writeString($baris, 16, $datanya_['tgl_terima_laporan']);
                            $worksheet1->writeString($baris, 17, $datanya_['lama_terima_laporan']);
                            $worksheet1->writeString($baris, 18, $datanya_['tgl_lapor_asuransi']);
                            $worksheet1->writeString($baris, 19, $datanya_['kelengkapan_dokumen']);
                            $worksheet1->writeString($baris, 20, $datanya_['tgl_status_lengkap']);
                            $worksheet1->writeString($baris, 21, $reminder_ke);
                            $worksheet1->writeString($baris, 22, $tgl_reminder);
                            $worksheet1->writeString($baris, 23, $datanya_['due_date']);
                            $worksheet1->writeString($baris, 24, $datanya_['tgl_kirim_dokumen']);
                            $worksheet1->writeString($baris, 25, $datanya_['status_release']);
                            $worksheet1->writeString($baris, 26, $datanya_['EM']);
                            $worksheet1->writeString($baris, 27, $datanya_['keterangan_EM']);
                            $worksheet1->writeString($baris, 28, $datanya_['dokter_pemeriksa']);
                            $worksheet1->writeString($baris, 29, $datanya_['spaj']);
                            $worksheet1->writeString($baris, 30, $datanya_['tgl_investigasi']);
                            $worksheet1->writeString($baris, 31, $datanya_['hasil_investigasi']);
                            $worksheet1->writeString($baris, 32, $datanya_['penyebab_meinggal']);
                            $worksheet1->writeString($baris, 33, $datanya_['polis_liability']);
                            $worksheet1->writeString($baris, 34, $datanya_['status_klaim']);
                            $worksheet1->writeNumber($baris, 35, $datanya_['nilai_pengajuan_keuangan']);
                            $worksheet1->writeNumber($baris, 36, $datanya_['bayar_ke_bank']);
                            $worksheet1->writeString($baris, 37, $datanya_['tgl_bayar_ke_client']);
                            $worksheet1->writeString($baris, 38, $datanya_['kol']);
                            $worksheet1->writeString($baris, 39, $datanya_['kadaluarsa']);

                            $baris++;
                            $no++;
                        }
                    } elseif ($_REQUEST['format_report']=='3') {
                        if ($datanya_['tgl_surat_reminder3']!='0000-00-00') {
                            $tgl_reminder = $datanya_['tgl_surat_reminder3'];
                            $reminder_ke = "3";
                        } elseif ($datanya_['tgl_surat_reminder2']!='0000-00-00') {
                            $tgl_reminder = $datanya_['tgl_surat_reminder2'];
                            $reminder_ke = "2";
                        } elseif ($datanya_['tgl_surat_reminder1']!='0000-00-00') {
                            $tgl_reminder = $datanya_['tgl_surat_reminder1'];
                            $reminder_ke = "1";
                        }
                        $worksheet1->setRow(4, 15);
                        $worksheet1->setColumn(9, 0, 5);
                        $worksheet1->writeString(9, 0, "No", $format);
                        $worksheet1->setColumn(9, 1, 5);
                        $worksheet1->writeString(9, 1, "Urut", $format);
                        $worksheet1->setColumn(9, 2, 5);
                        $worksheet1->writeString(9, 2, "Cabang", $format);
                        $worksheet1->setColumn(9, 3, 5);
                        $worksheet1->writeString(9, 3, "Mitra", $format);
                        $worksheet1->setColumn(9, 4, 5);
                        $worksheet1->writeString(9, 4, "Asuransi", $format);
                        $worksheet1->setColumn(9, 5, 5);
                        $worksheet1->writeString(9, 5, "Produk", $format);
                        $worksheet1->setColumn(9, 6, 5);
                        $worksheet1->writeString(9, 6, "ID Peserta", $format);
                        $worksheet1->setColumn(9, 7, 5);
                        $worksheet1->writeString(9, 7, "Nama Debitur", $format);
                        $worksheet1->setColumn(9, 8, 5);
                        $worksheet1->writeString(9, 8, "Tgl Lahir", $format);
                        $worksheet1->setColumn(9, 9, 5);
                        $worksheet1->writeString(9, 9, "Usia", $format);
                        $worksheet1->setColumn(9, 10, 5);
                        $worksheet1->writeString(9, 10, "Plafond Kredit", $format);
                        $worksheet1->setColumn(9, 11, 5);
                        $worksheet1->writeString(9, 11, "Tuntutan Klaim", $format);
                        $worksheet1->setColumn(9, 12, 5);
                        $worksheet1->writeString(9, 12, "Tgl Akad", $format);
                        $worksheet1->setColumn(9, 13, 5);
                        $worksheet1->writeString(9, 13, "J.Wkt (Th.)", $format);
                        $worksheet1->setColumn(9, 14, 5);
                        $worksheet1->writeString(9, 14, "DOL", $format);
                        $worksheet1->setColumn(9, 15, 5);
                        $worksheet1->writeString(9, 15, "Akad s/d DOL (hari)", $format);
                        $worksheet1->setColumn(9, 16, 5);
                        $worksheet1->writeString(9, 16, "Tgl. Terima Laporan", $format);
                        $worksheet1->setColumn(9, 17, 5);
                        $worksheet1->writeString(9, 17, "Dur", $format);
                        $worksheet1->setColumn(9, 18, 5);
                        $worksheet1->writeString(9, 18, "Tgl. lapor As", $format);
                        $worksheet1->setColumn(9, 19, 5);
                        $worksheet1->writeString(9, 19, "Kelengkapan Dokumen", $format);
                        $worksheet1->setColumn(9, 20, 5);
                        $worksheet1->writeString(9, 20, "Tgl. Status Lengkap", $format);
                        $worksheet1->setColumn(9, 21, 5);
                        $worksheet1->writeString(9, 21, "R ke", $format);
                        $worksheet1->setColumn(9, 22, 5);
                        $worksheet1->writeString(9, 22, "Tgl R", $format);
                        $worksheet1->setColumn(9, 23, 5);
                        $worksheet1->writeString(9, 23, "Due Date", $format);
                        $worksheet1->setColumn(9, 24, 5);
                        $worksheet1->writeString(9, 24, "Dok As Date", $format);
                        $worksheet1->setColumn(9, 25, 5);
                        $worksheet1->writeString(9, 25, "SLA As", $format);
                        $worksheet1->setColumn(9, 26, 5);
                        $worksheet1->writeString(9, 26, "EM", $format);
                        $worksheet1->setColumn(9, 27, 5);
                        $worksheet1->writeString(9, 27, "EM Keterangan", $format);
                        $worksheet1->setColumn(9, 28, 5);
                        $worksheet1->writeString(9, 28, "Dokter Pemeriksa", $format);
                        $worksheet1->setColumn(9, 29, 5);
                        $worksheet1->writeString(9, 29, "SPAK", $format);
                        $worksheet1->setColumn(9, 30, 5);
                        $worksheet1->writeString(9, 30, "Tgl. Inv", $format);
                        $worksheet1->setColumn(9, 31, 5);
                        $worksheet1->writeString(9, 31, "Hasil investigasi", $format);
                        $worksheet1->setColumn(9, 32, 5);
                        $worksheet1->writeString(9, 32, "Penyebab Kematian", $format);
                        $worksheet1->setColumn(9, 33, 5);
                        $worksheet1->writeString(9, 33, "Liability", $format);
                        $worksheet1->setColumn(9, 34, 5);
                        $worksheet1->writeString(9, 34, "Status", $format);
                        $worksheet1->setColumn(9, 35, 5);
                        $worksheet1->writeString(9, 35, "Paid As", $format);
                        $worksheet1->setColumn(9, 36, 5);
                        $worksheet1->writeString(9, 36, "Paid As Date", $format);
                        $worksheet1->setColumn(9, 37, 5);
                        $worksheet1->writeString(9, 37, "Kol", $format);
                        $worksheet1->setColumn(9, 38, 5);
                        $worksheet1->writeString(9, 38, "Exp", $format);
                        $baris = 10;
                        $no=1;
                        while ($datanya_ = mysql_fetch_array($kucing)) {
                            $worksheet1->writeString($baris, 0, $no);
                            $worksheet1->writeString($baris, 1, $datanya_['no_urut_klaim']);
                            $worksheet1->writeString($baris, 2, $datanya_['id_cabang']);
                            $worksheet1->writeString($baris, 3, $datanya_['mitra']);
                            $worksheet1->writeString($baris, 4, $datanya_['name']);
                            $worksheet1->writeString($baris, 5, $datanya_['nmproduk']);
                            $worksheet1->writeString($baris, 6, $datanya_['id_peserta']);
                            $worksheet1->writeString($baris, 7, $datanya_['nama']);
                            $worksheet1->writeString($baris, 8, $datanya_['tgl_lahir']);
                            $worksheet1->writeString($baris, 9, $datanya_['usia']);
                            $worksheet1->writeNumber($baris, 10, $datanya_['kredit_jumlah']);
                            $worksheet1->writeNumber($baris, 11, $datanya_['tuntutan_klaim']);
                            $worksheet1->writeString($baris, 12, $datanya_['kredit_tgl']);
                            $worksheet1->writeNumber($baris, 13, $datanya_['tenor']);
                            $worksheet1->writeString($baris, 14, $datanya_['dol']);
                            $worksheet1->writeNumber($baris, 15, $datanya_['akad_dol']);
                            $worksheet1->writeString($baris, 16, $datanya_['tgl_terima_laporan']);
                            $worksheet1->writeString($baris, 17, $datanya_['lama_terima_laporan']);
                            $worksheet1->writeString($baris, 18, $datanya_['tgl_lapor_asuransi']);
                            $worksheet1->writeString($baris, 19, $datanya_['kelengkapan_dokumen']);
                            $worksheet1->writeString($baris, 20, $datanya_['tgl_status_lengkap']);
                            $worksheet1->writeString($baris, 21, $reminder_ke);
                            $worksheet1->writeString($baris, 22, $tgl_reminder);
                            $worksheet1->writeString($baris, 23, $datanya_['due_date']);
                            $worksheet1->writeString($baris, 24, $datanya_['tgl_kirim_dokumen']);
                            $worksheet1->writeString($baris, 25, $datanya_['status_release']);
                            $worksheet1->writeString($baris, 26, $datanya_['EM']);
                            $worksheet1->writeString($baris, 27, $datanya_['keterangan_EM']);
                            $worksheet1->writeString($baris, 28, $datanya_['dokter_pemeriksa']);
                            $worksheet1->writeString($baris, 29, $datanya_['spaj']);
                            $worksheet1->writeString($baris, 30, $datanya_['tgl_investigasi']);
                            $worksheet1->writeString($baris, 31, $datanya_['hasil_investigasi']);
                            $worksheet1->writeString($baris, 32, $datanya_['penyebab_meinggal']);
                            $worksheet1->writeString($baris, 33, $datanya_['polis_liability']);
                            $worksheet1->writeString($baris, 34, $datanya_['status_klaim']);
                            $worksheet1->writeNumber($baris, 35, $datanya_['total_bayar_asuransi']);
                            $worksheet1->writeString($baris, 36, $datanya_['tgl_bayar_asuransi']);
                            $worksheet1->writeString($baris, 37, $datanya_['kol']);
                            $worksheet1->writeString($baris, 38, $datanya_['kadaluarsa']);

                            $baris++;
                            $no++;
                        }
                    }
                    $workbook->close();
                    ;
                  break;
case "klaim_tiering_asuransi_all":

                        function HeaderingExcel($filename)
                        {
                            header("Content-type: application/vnd.ms-excel");
                            header("Content-Disposition: attachment; filename=$filename");
                            header("Expires: 0");
                            header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
                            header("Pragma: public");
                        }
                        HeaderingExcel('KLAIM_TIERING.xls');
                        $workbook = new Workbook("");
                        $worksheet1 =& $workbook->add_worksheet('DATA KLAIM TIERING');
                        $format =& $workbook->add_format();
                        $format->setAlign('vcenter');	$format->setAlign('center');	$format->setColor('white');	$format->setBold();	$format->setPattern();	$format->setFgColor('green');
                        $fjudul =& $workbook->add_format();	$fjudul->setAlign('vcenter');	$fjudul->setAlign('center');	$fjudul->setBold();


                        $worksheet1->setMerge(0, 0, 0, 44);	$worksheet1->writeString(0, 0, "DATA KLAIM TIERING ".strtoupper($_REQUEST['status_klaim']), $fjudul, 1);


                        if (!empty($_REQUEST['tgl1'])) {
                            $worksheet1->setMerge(1, 0, 1, 44);
                            $worksheet1->writeString(1, 0, "TANGGAL LAPOR "._convertDate($_REQUEST['tgl1'])." s/d "._convertDate($_REQUEST['tgl2'])."", $fjudul);
                        }


                        if (!empty($_REQUEST['tgl3'])) {
                            $worksheet1->setMerge(2, 0, 2, 44);
                            $worksheet1->writeString(1, 0, "DOL "._convertDate($_REQUEST['tgl3'])." s/d "._convertDate($_REQUEST['tgl4'])."", $fjudul);
                        }


                        $worksheet1->setRow(4, 15);
                        $worksheet1->setColumn(9, 0, 5);	$worksheet1->writeString(9, 0, "No", $format);
                        $worksheet1->setColumn(9, 1, 5);	$worksheet1->writeString(9, 1, "Nomor Urut", $format);
                        $worksheet1->setColumn(9, 2, 5);	$worksheet1->writeString(9, 2, "Cabang", $format);
                        $worksheet1->setColumn(9, 3, 5);	$worksheet1->writeString(9, 3, "Mitra", $format);
                        $worksheet1->setColumn(9, 4, 5);	$worksheet1->writeString(9, 4, "Cover Asuransi", $format);
                        $worksheet1->setColumn(9, 5, 5);	$worksheet1->writeString(9, 5, "Kategori", $format);
                        $worksheet1->setColumn(9, 6, 5);	$worksheet1->writeString(9, 6, "ID Peserta", $format);
                        $worksheet1->setColumn(9, 7, 5);	$worksheet1->writeString(9, 7, "Nama Debitur", $format);
                        $worksheet1->setColumn(9, 8, 5);	$worksheet1->writeString(9, 8, "Tgl Lahir", $format);
                        $worksheet1->setColumn(9, 9, 5);	$worksheet1->writeString(9, 9, "Usia", $format);
                        $worksheet1->setColumn(9, 10, 5);	$worksheet1->writeString(9, 10, "Plafond Kredit", $format);
                        $worksheet1->setColumn(9, 11, 5);	$worksheet1->writeString(9, 11, "Tuntutan Klaim", $format);
                        $worksheet1->setColumn(9, 11, 5);	$worksheet1->writeString(9, 12, "Perc. Tiering", $format);
                        $worksheet1->setColumn(9, 11, 5);	$worksheet1->writeString(9, 13, "Nilai Tiering", $format);
                        $worksheet1->setColumn(9, 12, 5);	$worksheet1->writeString(9, 14, "Tgl Akad", $format);
                        $worksheet1->setColumn(9, 13, 5);	$worksheet1->writeString(9, 15, "J.Wkt (Th.)", $format);
                        $worksheet1->setColumn(9, 14, 5);	$worksheet1->writeString(9, 16, "DOL", $format);
                        $worksheet1->setColumn(9, 15, 5);	$worksheet1->writeString(9, 17, "Akad s/d DOL (hari)", $format);
                        $worksheet1->setColumn(9, 16, 5);	$worksheet1->writeString(9, 18, "Tgl. Terima Laporan", $format);
                        $worksheet1->setColumn(9, 17, 5);	$worksheet1->writeString(9, 19, "Lama Terima Laporan", $format);
                        $worksheet1->setColumn(9, 18, 5);	$worksheet1->writeString(9, 20, "Tgl. Update Klaim", $format);
                        $worksheet1->setColumn(9, 19, 5);	$worksheet1->writeString(9, 21, "Tgl. lapor Asuransi", $format);
                        $worksheet1->setColumn(9, 20, 5);	$worksheet1->writeString(9, 22, "Kelengkapan Dokumen", $format);
                        $worksheet1->setColumn(9, 21, 5);	$worksheet1->writeString(9, 23, "Tgl. Status Lengkap", $format);
                        $worksheet1->setColumn(9, 22, 5);	$worksheet1->writeString(9, 24, "Due Date (PKS)", $format);
                        $worksheet1->setColumn(9, 23, 5);	$worksheet1->writeString(9, 25, "Tgl. kirim Dok. Ke Asuransi", $format);
                        $worksheet1->setColumn(9, 24, 5);	$worksheet1->writeString(9, 26, "Today", $format);
                        $worksheet1->setColumn(9, 25, 5);	$worksheet1->writeString(9, 27, "Status Release Asuransi (hari)", $format);
                        $worksheet1->setColumn(9, 26, 5);	$worksheet1->writeString(9, 28, "EM", $format);
                        $worksheet1->setColumn(9, 27, 5);	$worksheet1->writeString(9, 29, "EM Keterangan", $format);
                        $worksheet1->setColumn(9, 28, 5);	$worksheet1->writeString(9, 30, "Tgl. Investigasi", $format);
                        $worksheet1->setColumn(9, 29, 5);	$worksheet1->writeString(9, 31, "Hasil investigasi", $format);
                        $worksheet1->setColumn(9, 30, 5);	$worksheet1->writeString(9, 32, "Penyebab Kematian", $format);
                        $worksheet1->setColumn(9, 31, 5);	$worksheet1->writeString(9, 33, "policy Liability", $format);
                        $worksheet1->setColumn(9, 32, 5);	$worksheet1->writeString(9, 34, "Status Klaim", $format);
                        $worksheet1->setColumn(9, 33, 5);	$worksheet1->writeString(9, 35, "Keterangan Asuransi", $format);
                        $worksheet1->setColumn(9, 34, 5);	$worksheet1->writeString(9, 36, "Asuransi Bayar", $format);
                        $worksheet1->setColumn(9, 35, 5);	$worksheet1->writeString(9, 37, "Ref. Pemb Dari Asuransi", $format);
                        $worksheet1->setColumn(9, 36, 5);	$worksheet1->writeString(9, 38, "Tgl Bayar dari Asuransi", $format);
                        $worksheet1->setColumn(9, 37, 5);	$worksheet1->writeString(9, 39, "Pengajuan Keuangan", $format);
                        $worksheet1->setColumn(9, 38, 5);	$worksheet1->writeString(9, 40, "Bayar Ke Bank (Rp)", $format);
                        $worksheet1->setColumn(9, 39, 5);	$worksheet1->writeString(9, 41, "Ref. Pemb ke bank", $format);
                        $worksheet1->setColumn(9, 40, 5);	$worksheet1->writeString(9, 42, "Tgl Pembayaran ke Client", $format);
                        $worksheet1->setColumn(9, 41, 5);	$worksheet1->writeString(9, 43, "Selisih", $format);
                        $worksheet1->setColumn(9, 42, 5);	$worksheet1->writeString(9, 44, "Kol", $format);


                        $baris = 10;

                        $q1='';
                        $q2='';
                        $q3='';
                        $q4='';
                        $q5='';
                        $q6='';

                        if ($_REQUEST['id_asuransi']!="") {
                            $q1=" and fu_ajk_asuransi.id=".$_REQUEST['id_asuransi'];
                        }

                        if ($_REQUEST['kol']!="") {
                            $q2=" and
							IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',

			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
			,
			IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5'))))=".$_REQUEST['kol']."";
                        }


                        $q3="";
                        if (!empty($_REQUEST['status_klaim'])) {
                            $q3="  and if(`id_klaim_status`=6,'Ditolak',
							if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
							'Dokumen Belum Lengkap')) ='".$_REQUEST['status_klaim']."' ";
                        }
                        $q4='';
                        if (!empty($_REQUEST['tgl1'])) {
                            $q4="and fu_ajk_cn.approve_date between '".$_REQUEST ['tgl1']."' and '".$_REQUEST ['tgl2']."'";
                        }


                        $q5='';
                        if (!empty($_REQUEST['tgl3'])) {
                            $q5="and fu_ajk_klaim.tgl_klaim between '".$_REQUEST ['tgl3']."' and '".$_REQUEST ['tgl4']."'";
                        }


                        $kucing = mysql_query("SELECT
						fu_ajk_klaim.no_urut_klaim,
						CONCAT(DATE_FORMAT(fu_ajk_cn.tgl_claim,'%Y%m'),'/',fu_ajk_klaim.id) AS klaim_id,
						fu_ajk_cn.id_cabang,
						fu_ajk_grupproduk.nmproduk as mitra,
						fu_ajk_asuransi.`name`,
						fu_ajk_polis.nmproduk,
						fu_ajk_peserta.id_peserta,
						fu_ajk_peserta.nama,
						fu_ajk_peserta.tgl_lahir,
						fu_ajk_peserta.usia,
						fu_ajk_peserta.kredit_jumlah,
						fu_ajk_cn.total_claim,
						fu_ajk_peserta.kredit_tgl,
						ROUND(if(fu_ajk_peserta.type_data='SPK',fu_ajk_peserta.kredit_tenor*12,fu_ajk_peserta.kredit_tenor)/12) as tenor,
						fu_ajk_klaim.tgl_klaim as dol,
						DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl) as akad_dol,
						fu_ajk_cn.approve_date as tgl_terima_laporan,
						DATEDIFF(fu_ajk_cn.approve_date,fu_ajk_klaim.tgl_klaim) as lama_terima_laporan,
						current_date as tgl_update_klaim,
						fu_ajk_klaim.tgl_lapor_klaim as tgl_lapor_asuransi,
						fu_ajk_cn.keterangan as kelengkapan_dokumen,
						fu_ajk_klaim.tgl_document_lengkap as tgl_status_lengkap,
						if(fu_ajk_peserta.type_data='SPK',fu_ajk_klaim.tgl_document_lengkap+28,fu_ajk_klaim.tgl_document_lengkap+14) as due_date,
						fu_ajk_klaim.tgl_kirim_dokumen,
						CURRENT_DATE() as today,
						DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_kirim_dokumen) as status_release,
						/*fu_ajk_spak.ext_premi*/ '' as EM,
						/*fu_ajk_spak.ket_ext*/ '' as keterangan_EM,
						fu_ajk_klaim.tgl_investigasi,
						fu_ajk_klaim.diagnosa as hasil_investigasi,
						fu_ajk_namapenyakit.namapenyakit as penyebab_meinggal,
						fu_ajk_cn.policy_liability as polis_liability,
						fu_ajk_klaim_status.status_klaim,
						'' as keterangan_asuransi,
						fu_ajk_cn.total_bayar_asuransi,
						'' as ref_bayar_asuransi,
						fu_ajk_cn.tgl_bayar_asuransi,
						'' as nilai_pengajuan_keuangan,
						fu_ajk_cn.total_claim  as bayar_ke_bank,
						'' as ref_pembayaran_ke_bank,
						fu_ajk_cn.tgl_byr_claim as tgl_bayar_ke_client,
						fu_ajk_cn.total_bayar_asuransi-fu_ajk_cn.total_claim as selisih,


						fu_ajk_klaim.tgl_document_lengkap,
						fu_ajk_cn.keterangan,
						fu_ajk_cn.total_bayar_asuransi as asuransi_bayar,
						fu_ajk_cn.tgl_bayar_asuransi as tgl_asuransi_bayar,

						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<31,10,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<61,20,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<91,30,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<121,40,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<151,50,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<181,60,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<271,70,80))))))) as persentase_tiering,

						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<31,10,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<61,20,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<91,30,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<121,40,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<151,50,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<181,60,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<271,70,80)))))))/100*fu_ajk_cn.total_claim as nilai_tiering,

						fu_ajk_cn.total_bayar_asuransi-if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<31,10,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<61,20,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<91,30,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<121,40,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<151,50,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<181,60,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<271,70,80)))))))/100*fu_ajk_cn.total_claim as selisih,

						/*
						fu_ajk_peserta.kredit_tgl,
						if(fu_ajk_peserta.type_data='SPK',fu_ajk_peserta.kredit_tenor*12,fu_ajk_peserta.kredit_tenor) as kredit_tenor,
						fu_ajk_klaim.tgl_klaim as dol,
						*/

						/*CONVERT(GROUP_CONCAT(fu_ajk_dokumenklaim_bank.id_dok) USING utf8) as dok,*/

						IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',

							IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
							IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
							IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
							,
							IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
							IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
							IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol
								FROM
								fu_ajk_cn
								INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
								INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta


								INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
								INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
								LEFT join fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
								LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id=fu_ajk_klaim.id_klaim_status
								LEFT JOIN fu_ajk_grupproduk ON fu_ajk_grupproduk.id=fu_ajk_peserta.nama_mitra
								/*LEFT JOIN fu_ajk_spak ON fu_ajk_peserta.spaj=fu_ajk_spak.spak*/
								LEFT JOIN fu_ajk_namapenyakit on fu_ajk_namapenyakit.id=fu_ajk_klaim.sebab_meninggal
								/*left JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim_bank.id_dok = fu_ajk_dokumenklaim.id*/

								/*LEFT JOIN fu_ajk_klaim_doc ON fu_ajk_klaim_doc.id_klaim=fu_ajk_klaim.id
								INNER JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim.id=fu_ajk_klaim_doc.dokumen
								*/

								where fu_ajk_cn.type_claim='Death'
								and fu_ajk_cn.id_cost=".$_REQUEST['id_cost']." ".$q1." ".$q2." ".$q3." ".$q4." ".$q5."

								and fu_ajk_cn.policy_liability='NONLIABLE'
								order by
								fu_ajk_peserta.id");
                        $no=1;
                        while ($datanya_ = mysql_fetch_array($kucing)) {
                            $worksheet1->writeString($baris, 0, $no);
                            $worksheet1->writeString($baris, 1, $datanya_['no_urut_klaim']);
                            $worksheet1->writeString($baris, 2, $datanya_['id_cabang']);
                            $worksheet1->writeString($baris, 3, $datanya_['mitra']);
                            $worksheet1->writeString($baris, 4, $datanya_['name']);
                            $worksheet1->writeString($baris, 5, $datanya_['nmproduk']);
                            $worksheet1->writeString($baris, 6, $datanya_['id_peserta']);
                            $worksheet1->writeString($baris, 7, $datanya_['nama']);
                            $worksheet1->writeString($baris, 8, $datanya_['tgl_lahir']);
                            $worksheet1->writeString($baris, 9, $datanya_['usia']);
                            $worksheet1->writeNumber($baris, 10, $datanya_['kredit_jumlah']);
                            $worksheet1->writeNumber($baris, 11, $datanya_['total_claim']);
                            $worksheet1->writeString($baris, 12, $datanya_['persentase_tiering']);
                            $worksheet1->writeString($baris, 13, $datanya_['nilai_tiering']);
                            $worksheet1->writeString($baris, 14, $datanya_['kredit_tgl']);
                            $worksheet1->writeNumber($baris, 15, $datanya_['tenor']);
                            $worksheet1->writeString($baris, 16, $datanya_['dol']);
                            $worksheet1->writeNumber($baris, 17, $datanya_['akad_dol']);
                            $worksheet1->writeString($baris, 18, $datanya_['tgl_terima_laporan']);
                            $worksheet1->writeString($baris, 19, $datanya_['lama_terima_laporan']);
                            $worksheet1->writeString($baris, 20, $datanya_['tgl_update_klaim']);
                            $worksheet1->writeString($baris, 21, $datanya_['tgl_lapor_asuransi']);
                            $worksheet1->writeString($baris, 22, $datanya_['kelengkapan_dokumen']);
                            $worksheet1->writeString($baris, 23, $datanya_['tgl_status_lengkap']);
                            $worksheet1->writeString($baris, 24, $datanya_['due_date']);
                            $worksheet1->writeString($baris, 25, $datanya_['tgl_kirim_dokumen']);
                            $worksheet1->writeString($baris, 26, $datanya_['today']);
                            $worksheet1->writeString($baris, 27, $datanya_['status_release']);
                            $worksheet1->writeString($baris, 28, $datanya_['EM']);
                            $worksheet1->writeString($baris, 29, $datanya_['keterangan_EM']);
                            $worksheet1->writeString($baris, 30, $datanya_['tgl_investigasi']);
                            $worksheet1->writeString($baris, 31, $datanya_['hasil_investigasi']);
                            $worksheet1->writeString($baris, 32, $datanya_['penyebab_meinggal']);
                            $worksheet1->writeString($baris, 33, $datanya_['polis_liability']);
                            $worksheet1->writeString($baris, 34, $datanya_['status_klaim']);
                            $worksheet1->writeString($baris, 35, $datanya_['keterangan_asuransi']);
                            $worksheet1->writeNumber($baris, 36, $datanya_['total_bayar_asuransi']);
                            $worksheet1->writeString($baris, 37, $datanya_['ref_bayar_asuransi']);
                            $worksheet1->writeString($baris, 38, $datanya_['tgl_bayar_asuransi']);
                            $worksheet1->writeNumber($baris, 39, $datanya_['nilai_pengajuan_keuangan']);
                            $worksheet1->writeNumber($baris, 40, $datanya_['bayar_ke_bank']);
                            $worksheet1->writeString($baris, 41, $datanya_['ref_pembayaran_ke_bank']);
                            $worksheet1->writeString($baris, 42, $datanya_['tgl_bayar_ke_client']);
                            $worksheet1->writeNumber($baris, 43, $datanya_['selisih']);
                            $worksheet1->writeString($baris, 44, $datanya_['kol']);

                            $baris++;
                            $no++;
                        }

                        $workbook->close();

                        ;
                        break;

case "spkkadaluarsa":
                //created by hansen 20161005
                function HeaderingExcel($filename)
                {
                    header("Content-type: application/vnd.ms-excel");
                    header("Content-Disposition: attachment; filename=$filename");
                    header("Expires: 0");
                    header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
                    header("Pragma: public");
                }
                HeaderingExcel('SPK KADALUARSA.xls');
                $workbook = new Workbook("");
                $worksheet1 =& $workbook->add_worksheet('DATA SPK KADALUARSA');
                $format =& $workbook->add_format();
                $format->setAlign('vcenter');	$format->setAlign('center');	$format->setColor('white');	$format->setBold();	$format->setPattern();	$format->setFgColor('green');
                $fjudul =& $workbook->add_format();	$fjudul->setAlign('vcenter');	$fjudul->setAlign('center');	$fjudul->setBold();

                $worksheet1->setMerge(0, 0, 0, 9);
                $worksheet1->setMerge(1, 0, 1, 9);
                $worksheet1->setMerge(2, 0, 2, 9);
                $worksheet1->writeString(0, 0, "DATA SPK KADALUARSA", $fjudul, 1);
                $worksheet1->writeString(1, 0, "CABANG ".$_REQUEST['nmcabang'], $fjudul);
                $worksheet1->writeString(2, 0, $_REQUEST['jmlhr']." Hari Sebelum Jatuh Tempo", $fjudul);

                $worksheet1->setRow(5, 15);
                $worksheet1->setColumn(5, 0, 5);	$worksheet1->writeString(5, 0, "No", $format);
                $worksheet1->setColumn(5, 1, 5);	$worksheet1->writeString(5, 1, "Nama Produk", $format);
                $worksheet1->setColumn(5, 2, 5);	$worksheet1->writeString(5, 2, "No SPK", $format);
                $worksheet1->setColumn(5, 3, 5);	$worksheet1->writeString(5, 3, "Nama", $format);
                $worksheet1->setColumn(5, 4, 5);	$worksheet1->writeString(5, 4, "Plafond", $format);
                $worksheet1->setColumn(5, 5, 5);	$worksheet1->writeString(5, 5, "Tenor", $format);
                $worksheet1->setColumn(5, 6, 5);	$worksheet1->writeString(5, 6, "Cabang", $format);
                $worksheet1->setColumn(5, 7, 5);	$worksheet1->writeString(5, 7, "Tgl Input", $format);
                $worksheet1->setColumn(5, 8, 5);	$worksheet1->writeString(5, 8, "Tgl Kadaluarsa", $format);
                $worksheet1->setColumn(5, 9, 5);	$worksheet1->writeString(5, 9, "Selisih", $format);

                $baris = 6;
                $sql="";

                $kucing = mysql_query('SELECT nmproduk,
																				spak,
																				nama,
																				plafond,
																				tenor,
																				name,
																				DATE_FORMAT(input_date,"%Y-%m-%d") as input_date,
																				DATE_FORMAT(tgl_kadaluarsa,"%Y-%m-%d") as tgl_kadaluarsa,
																				selisih
																from(
																select fu_ajk_polis.nmproduk,
																				fu_ajk_spak.spak,
																				fu_ajk_spak_form.nama,
																				fu_ajk_spak_form.plafond,
																				fu_ajk_spak_form.tenor,
																				fu_ajk_spak_form.cabang,
																				fu_ajk_cabang.name,
																				fu_ajk_spak.input_date,
																				fu_ajk_spak.id_polis,
																				DATE_ADD(fu_ajk_spak.input_date,INTERVAL 3 MONTH)as tgl_kadaluarsa,
																				CASE WHEN DATEDIFF(DATE_ADD(fu_ajk_spak.input_date,INTERVAL 3 MONTH),CURDATE()) < 1 THEN
																					"EXPIRED"
																				else
																					DATEDIFF(DATE_ADD(fu_ajk_spak.input_date,INTERVAL 3 MONTH),CURDATE())
																				end as selisih
																from fu_ajk_spak
																		 inner join fu_ajk_spak_form
																		 on fu_ajk_spak_form.idspk = fu_ajk_spak.id
																		 left JOIN fu_ajk_polis
																		 on fu_ajk_polis.id = fu_ajk_spak.id_polis
																		 left JOIN fu_ajk_cabang
																		 on fu_ajk_cabang.id = fu_ajk_spak_form.cabang
																where fu_ajk_spak.del is null and
																			fu_ajk_spak_form.del is NULL and
																			fu_ajk_spak.STATUS = "aktif" and
																			fu_ajk_spak.id_polis in (1,12,15) and
																			fu_ajk_spak.input_date !="0000-00-00 00:00:00"
																)as temp
																where selisih != "EXPIRED" AND name = "'.$_REQUEST['nmcabang'].'" and selisih <= '.$_REQUEST['jmlhr'].'
																GROUP BY input_date');

                while ($datanya_ = mysql_fetch_array($kucing)) {
                    $worksheet1->writeNumber($baris, 0, ++$no);
                    $worksheet1->writeString($baris, 1, $datanya_['nmproduk']);
                    $worksheet1->writeString($baris, 2, $datanya_['spak']);
                    $worksheet1->writeString($baris, 3, $datanya_['nama']);
                    $worksheet1->writeString($baris, 4, $datanya_['plafond']);
                    $worksheet1->writeString($baris, 5, $datanya_['tenor']);
                    $worksheet1->writeString($baris, 6, $datanya_['name']);
                    $worksheet1->writeString($baris, 7, $datanya_['input_date']);
                    $worksheet1->writeString($baris, 8, $datanya_['tgl_kadaluarsa']);
                    $worksheet1->writeString($baris, 9, $datanya_['selisih']);

                    $baris++;
                }

                $workbook->close();
                ;
                break;

case "eL_FeePemeriksaanDokterDetailAll":

        function HeaderingExcel($filename)
        {
            header("Content-type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=$filename");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
            header("Pragma: public");
        }
        HeaderingExcel('DATA_FEE_DOKTER_DETAIL.xls');
        $workbook = new Workbook("");
        $worksheet1 =& $workbook->add_worksheet('REKAP DATA FEE DOKTER DETAIL');
        $format =& $workbook->add_format();
        $format->setAlign('vcenter');	$format->setAlign('center');	$format->setColor('white');	$format->setBold();	$format->setPattern();	$format->setFgColor('green');
        $fjudul =& $workbook->add_format();	$fjudul->setAlign('vcenter');	$fjudul->setAlign('center');	$fjudul->setBold();

        $worksheet1->setMerge(0, 0, 0, 7);	$worksheet1->writeString(0, 0, "REKAP DATA FEE DOKTER DETAIL", $fjudul, 1);
        $worksheet1->setMerge(1, 0, 1, 7);	$worksheet1->writeString(1, 0, "TANGGAL PERIKSA "._convertDate($_REQUEST['tgl1'])." s/d "._convertDate($_REQUEST['tgl2'])."", $fjudul);

        $worksheet1->setRow(4, 15);
        $worksheet1->setColumn(4, 0, 5);	$worksheet1->writeString(4, 0, "NO", $format);
        $worksheet1->setColumn(4, 1, 15);	$worksheet1->writeString(4, 1, "NAMA DOKTER", $format);
        $worksheet1->setColumn(4, 2, 15);	$worksheet1->writeString(4, 2, "NAMA DEBITUR", $format);
        $worksheet1->setColumn(4, 3, 15);	$worksheet1->writeString(4, 3, "NO SPK", $format);
        $worksheet1->setColumn(4, 5, 15);	$worksheet1->writeString(4, 4, "TGL PERIKSA", $format);
        $worksheet1->setColumn(4, 5, 15);	$worksheet1->writeString(4, 5, "USIA", $format);
        $worksheet1->setColumn(4, 5, 15);	$worksheet1->writeString(4, 6, "CABANG", $format);
        $worksheet1->setColumn(4, 5, 15);	$worksheet1->writeString(4, 7, "CATATAN", $format);
        $worksheet1->setColumn(4, 5, 15);	$worksheet1->writeString(4, 8, "NO PENGAJUAN", $format);

        $baris = 5;
        $sql="";

        $kucing = mysql_query("SELECT *
														FROM
														(SELECT user_mobile.namalengkap,
																		fu_ajk_spak_form.nama,
																		fu_ajk_spak.spak,
																		DATE_FORMAT(fu_ajk_spak_form.tgl_periksa,'%d-%m-%Y')as tgl_periksa,
																		fu_ajk_spak_form.x_usia,
																		fu_ajk_cabang.name,
																		catatan,
																		fu_ajk_spak_form.nopermohonan
														FROM fu_ajk_spak_form
																	INNER JOIN fu_ajk_spak ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
																	INNER JOIN fu_ajk_cabang ON fu_ajk_cabang.id = fu_ajk_spak_form.cabang and fu_ajk_cabang.del is null
																	INNER JOIN user_mobile ON user_mobile.id = fu_ajk_spak_form.dokter_pemeriksa
														WHERE tgl_periksa between '".$_GET['tgl1']."'  and '".$_GET['tgl2']."' and
																	(nopermohonan is NULL or nopermohonan = '')

														UNION ALL

														SELECT user_mobile.namalengkap,
																		fu_ajk_spak_form.nama,
																		fu_ajk_spak.spak,
																		DATE_FORMAT(fu_ajk_spak_form.tgl_periksa,'%d-%m-%Y')as tgl_periksa,
																		fu_ajk_spak_form.x_usia,
																		fu_ajk_cabang.name,
																		catatan,
																		fu_ajk_spak_form.nopermohonan
														FROM fu_ajk_spak_form
																	INNER JOIN fu_ajk_spak ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
																	INNER JOIN fu_ajk_cabang ON fu_ajk_cabang.id = fu_ajk_spak_form.cabang and fu_ajk_cabang.del is null
																	INNER JOIN user_mobile ON user_mobile.id = fu_ajk_spak_form.dokter_pemeriksa
														WHERE tgl_periksa between '".$_GET['tgl1']."'  and '".$_GET['tgl2']."' and nopermohonan != ''
														GROUP BY nopermohonan)as temp1
														ORDER BY namalengkap,tgl_periksa ");

        while ($jangkrik = mysql_fetch_array($kucing)) {
            $worksheet1->writeNumber($baris, 0, ++$no);
            $worksheet1->writeString($baris, 1, $jangkrik['namalengkap']);
            $worksheet1->writeString($baris, 2, $jangkrik['nama']);
            $worksheet1->writeString($baris, 3, $jangkrik['spak']);
            $worksheet1->writeString($baris, 4, $jangkrik['tgl_periksa']);
            $worksheet1->writeString($baris, 5, $jangkrik['x_usia']);
            $worksheet1->writeString($baris, 6, $jangkrik['name']);
            $worksheet1->writeString($baris, 7, $jangkrik['catatan']);
            $worksheet1->writeString($baris, 8, $jangkrik['nopermohonan']);
            $baris++;
        }

        $workbook->close();
        ;
        break;

    case "eL_klaimtotal":
        $pdf=new FPDF('P', 'mm', 'A4');
        $pdf->Open();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetY(20);
        $pdf->SetX(25);
        $pdf->cell(50, 25, 'KLAIM TOTAL PER TANGGAL : '.date("d-m-Y").'', 0, 0, 'C');
        $pdf->SetY(40);
        $pdf->SetFont('Arial', 'B', 9);
        $y = 45;
        $x = 20;

        $jml_spk_closefile = 0;
        $plafond_spk_closefile = 0;
        $klaim_spk_closefile = 0;
        $klaim_payment_spk_closefile = 0;
        $jml_percepatan_closefile = 0;
        $plafond_percepatan_closefile = 0;
        $klaim_percepatan_closefile = 0;
        $klaim_payment_percepatan_closefile = 0;
        $jml_spkmpp_closefile = 0;
        $plafond_spkmpp_closefile = 0;
        $klaim_spkmpp_closefile = 0;
        $klaim_payment_spkmpp_closefile = 0;
        $jml_abri_closefile = 0;
        $plafond_abri_closefile = 0;
        $klaim_abri_closefile = 0;
        $klaim_payment_abri_closefile = 0;
        $jml_percmpp_closefile = 0;
        $plafond_percmpp_closefile = 0;
        $klaim_percmpp_closefile = 0;
        $klaim_payment_percmpp_closefile = 0;
        $jml_platinum_closefile = 0;
        $plafond_platinum_closefile = 0;
        $klaim_platinum_closefile = 0;
        $klaim_payment_platinum_closefile = 0;
        $jml_hakashima_closefile = 0;
        $plafond_hakashima_closefile = 0;
        $klaim_hakashima_closefile = 0;
        $klaim_payment_hakashima_closefile = 0;

        $qklaim = mysql_query(" select fu_ajk_cn.id_nopol,
										 fu_ajk_polis.nmproduk,
										 count(*)as jml_debitur,
										 sum(fu_ajk_peserta.kredit_jumlah)as  plafond,
										 sum(fu_ajk_cn.tuntutan_klaim) as klaim,
										 sum(fu_ajk_cn.total_claim) as klaim_payment
							from fu_ajk_cn
									 inner join fu_ajk_klaim
									 on fu_ajk_klaim.id_cn = fu_ajk_cn.id
									 inner join fu_ajk_peserta
									 on fu_ajk_peserta.id_klaim = fu_ajk_cn.id
									 inner JOIN fu_ajk_polis
									 on fu_ajk_polis.id = fu_ajk_cn.id_nopol
							where fu_ajk_cn.type_claim = 'Death' and
										fu_ajk_cn.del is NULL and
										fu_ajk_klaim.del is NULL and
										fu_ajk_peserta.del is NULL and
										confirm_claim !='Pending'
							group by fu_ajk_cn.id_nopol");

        while ($qklaim_row = mysql_fetch_array($qklaim)) {
            if ($qklaim_row['id_nopol']== 1) {
                $jml_spk_closefile = $qklaim_row['jml_debitur'];
                $plafond_spk_closefile = $qklaim_row['plafond'];
                $klaim_spk_closefile = $qklaim_row['klaim'];
                $klaim_payment_spk_closefile = $qklaim_row['klaim_payment'];
            }

            if ($qklaim_row['id_nopol']== 12) {
                $jml_spkmpp_closefile = $qklaim_row['jml_debitur'];
                $plafond_spkmpp_closefile = $qklaim_row['plafond'];
                $klaim_spkmpp_closefile = $qklaim_row['klaim'];
                $klaim_payment_spkmpp_closefile = $qklaim_row['klaim_payment'];
            }

            if ($qklaim_row['id_nopol']== 2) {
                $jml_percepatan_closefile = $qklaim_row['jml_debitur'];
                $plafond_percepatan_closefile = $qklaim_row['plafond'];
                $klaim_percepatan_closefile = $qklaim_row['klaim'];
                $klaim_payment_percepatan_closefile = $qklaim_row['klaim_payment'];
            }

            if ($qklaim_row['id_nopol']== 3) {
                $jml_abri_closefile = $qklaim_row['jml_debitur'];
                $plafond_abri_closefile = $qklaim_row['plafond'];
                $klaim_abri_closefile = $qklaim_row['klaim'];
                $klaim_payment_abri_closefile = $qklaim_row['klaim_payment'];
            }

            if ($qklaim_row['id_nopol']== 11) {
                $jml_percmpp_closefile = $qklaim_row['jml_debitur'];
                $plafond_percmpp_closefile = $qklaim_row['plafond'];
                $klaim_percmpp_closefile = $qklaim_row['klaim'];
                $klaim_payment_percmpp_closefile = $qklaim_row['klaim_payment'];
            }

            if ($qklaim_row['id_nopol']== 16) {
                $jml_platinum_closefile = $qklaim_row['jml_debitur'];
                $plafond_platinum_closefile = $qklaim_row['plafond'];
                $klaim_platinum_closefile = $qklaim_row['klaim'];
                $klaim_payment_platinum_closefile = $qklaim_row['klaim_payment'];
            }

            if ($qklaim_row['id_nopol']== 18) {
                $jml_hakashima_closefile = $qklaim_row['jml_debitur'];
                $plafond_hakashima_closefile = $qklaim_row['plafond'];
                $klaim_hakashima_closefile = $qklaim_row['klaim'];
                $klaim_payment_hakashima_closefile = $qklaim_row['klaim_payment'];
            }
        }

            $sub_jml_spk_closefile = $jml_spkmpp_closefile + $jml_spk_closefile;
            $sub_plafond_spk_closefile = $plafond_spkmpp_closefile + $plafond_spk_closefile;
            $sub_klaim_spk_closefile = $klaim_spkmpp_closefile + $klaim_spk_closefile;
            $sub_klaim_payment_spk_closefile = $klaim_payment_spkmpp_closefile + $klaim_payment_spk_closefile;

            $sub_jml_other_closefile = $jml_percepatan_closefile + $jml_abri_closefile + $jml_percmpp_closefile + $jml_platinum_closefile + $jml_hakashima_closefile;
            $sub_plafond_other_closefile = $plafond_percepatan_closefile + $plafond_abri_closefile + $plafond_percmpp_closefile + $plafond_platinum_closefile + $plafond_hakashima_closefile;
            $sub_klaim_other_closefile = $klaim_percepatan_closefile + $klaim_abri_closefile + $klaim_percmpp_closefile + $klaim_platinum_closefile + $klaim_hakashima_closefile;
            $sub_klaim_payment_other_closefile = $klaim_payment_percepatan_closefile + $klaim_payment_abri_closefile + $klaim_payment_percmpp_closefile + $klaim_payment_platinum_closefile + $klaim_payment_hakashima_closefile;

            $pdf->SetX(18);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->cell(35, 5, 'Total Klaim :'.'', 0, 0, 'L');
            $pdf->SetFont('Arial', '', 9);
            $pdf->Line(18, $y, 200, $y);
            $pdf->SetY($y);
            $pdf->SetX(45);
            $pdf->cell(40, 5, 'Debitur'.'', 0, 0, 'C');
            $pdf->cell(40, 5, 'Plafond'.'', 0, 0, 'C');
            $pdf->cell(40, 5, 'Nilai Klaim'.'', 0, 0, 'C');
            $pdf->cell(40, 5, 'Dibayar'.'', 0, 0, 'C');
            $pdf->SetY($y=$y+5);
            $pdf->Line(18, $y, 200, $y);
            $pdf->SetX($x);

            $pdf->cell(50, 5, 'SPK Reguler', 0, 0, 'L');
            $pdf->SetX(50);
            $pdf->cell(50, 5, ':', 0, 0, 'L');
            $pdf->SetX(45);
            $pdf->cell(40, 5, duit($jml_spk_closefile), 0, 0, 'C');
            $pdf->cell(40, 5, duit($plafond_spk_closefile), 0, 0, 'C');
            $pdf->cell(40, 5, duit($klaim_spk_closefile), 0, 0, 'C');
            $pdf->cell(40, 5, duit($klaim_payment_spk_closefile), 0, 0, 'C');

            $pdf->SetY($y=$y+5);
            $pdf->SetX($x);
            $pdf->cell(50, 5, 'Reguler MPP'.'', 0, 0, 'L');
            $pdf->SetX(50);
            $pdf->cell(50, 5, ':', 0, 0, 'L');
            $pdf->SetX(45);
            $pdf->cell(40, 5, duit($jml_spkmpp_closefile), 0, 0, 'C');
            $pdf->cell(40, 5, duit($plafond_spkmpp_closefile), 0, 0, 'C');
            $pdf->cell(40, 5, duit($klaim_spkmpp_closefile), 0, 0, 'C');
            $pdf->cell(40, 5, duit($klaim_payment_spkmpp_closefile), 0, 0, 'C');
            $pdf->SetY($y=$y+5);
            $pdf->cell(50, 5, 'Subtotal'.'', 0, 0, 'C');
            $pdf->SetX(50);
            $pdf->cell(50, 5, ':', 0, 0, 'L');
            $pdf->SetX(45);
            $pdf->cell(40, 5, duit($sub_jml_spk_closefile), 0, 0, 'C');
            $pdf->cell(40, 5, duit($sub_plafond_spk_closefile), 0, 0, 'C');
            $pdf->cell(40, 5, duit($sub_klaim_spk_closefile), 0, 0, 'C');
            $pdf->cell(40, 5, duit($sub_klaim_payment_spk_closefile), 0, 0, 'C');
            $pdf->SetY($y=$y+10);
            $pdf->SetX($x);

            $pdf->cell(50, 5, 'Percepatan', 0, 0, 'L');
            $pdf->SetX(50);
            $pdf->cell(50, 5, ':', 0, 0, 'L');
            $pdf->SetX(45);
            $pdf->cell(40, 5, duit($jml_percepatan_closefile), 0, 0, 'C');
            $pdf->cell(40, 5, duit($plafond_percepatan_closefile), 0, 0, 'C');
            $pdf->cell(40, 5, duit($klaim_percepatan_closefile), 0, 0, 'C');
            $pdf->cell(40, 5, duit($klaim_payment_percepatan_closefile), 0, 0, 'C');
            $pdf->SetY($y=$y+5);
            $pdf->SetX($x);

            $pdf->cell(50, 5, 'Percepatan MPP'.'', 0, 0, 'L');
            $pdf->SetX(50);
            $pdf->cell(50, 5, ':', 0, 0, 'L');
            $pdf->SetX(45);
            $pdf->cell(40, 5, duit($jml_percmpp_closefile), 0, 0, 'C');
            $pdf->cell(40, 5, duit($plafond_percmpp_closefile), 0, 0, 'C');
            $pdf->cell(40, 5, duit($klaim_percmpp_closefile), 0, 0, 'C');
            $pdf->cell(40, 5, duit($klaim_payment_percmpp_closefile), 0, 0, 'C');
            $pdf->SetY($y=$y+5);
            $pdf->SetX($x);

            $pdf->cell(50, 5, 'ABRI'.'', 0, 0, 'L');
            $pdf->SetX(50);
            $pdf->cell(50, 5, ':', 0, 0, 'L');
            $pdf->SetX(45);
            $pdf->cell(40, 5, duit($jml_abri_closefile), 0, 0, 'C');
            $pdf->cell(40, 5, duit($plafond_abri_closefile), 0, 0, 'C');
            $pdf->cell(40, 5, duit($klaim_abri_closefile), 0, 0, 'C');
            $pdf->cell(40, 5, duit($klaim_payment_abri_closefile), 0, 0, 'C');
            $pdf->SetY($y=$y+5);
            $pdf->SetX($x);

            $pdf->cell(50, 5, 'Platinum'.'', 0, 0, 'L');
            $pdf->SetX(50);
            $pdf->cell(50, 5, ':', 0, 0, 'L');
            $pdf->SetX(45);
            $pdf->cell(40, 5, duit($jml_platinum_closefile), 0, 0, 'C');
            $pdf->cell(40, 5, duit($plafond_platinum_closefile), 0, 0, 'C');
            $pdf->cell(40, 5, duit($klaim_platinum_closefile), 0, 0, 'C');
            $pdf->cell(40, 5, duit($klaim_payment_platinum_closefile), 0, 0, 'C');
            $pdf->SetY($y=$y+5);
            $pdf->SetX($x);

            $pdf->cell(50, 5, 'Hakashima'.'', 0, 0, 'L');
            $pdf->SetX(50);
            $pdf->cell(50, 5, ':', 0, 0, 'L');
            $pdf->SetX(45);
            $pdf->cell(40, 5, duit($jml_hakashima_closefile), 0, 0, 'C');
            $pdf->cell(40, 5, duit($plafond_hakashima_closefile), 0, 0, 'C');
            $pdf->cell(40, 5, duit($klaim_hakashima_closefile), 0, 0, 'C');
            $pdf->cell(40, 5, duit($klaim_paymenthakashima_closefile), 0, 0, 'C');
            $pdf->SetY($y=$y+5);
            $pdf->cell(50, 5, 'Subtotal'.'', 0, 0, 'C');
            $pdf->SetX(50);
            $pdf->cell(50, 5, ':', 0, 0, 'L');
            $pdf->SetX(45);
            $pdf->cell(40, 5, duit($sub_jml_other_closefile), 0, 0, 'C');
            $pdf->cell(40, 5, duit($sub_plafond_other_closefile), 0, 0, 'C');
            $pdf->cell(40, 5, duit($sub_klaim_other_closefile), 0, 0, 'C');
            $pdf->cell(40, 5, duit($sub_klaim_payment_other_closefile), 0, 0, 'C');
            $pdf->SetY($y=$y+10);
            $pdf->SetX($x);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->cell(50, 5, 'Total Klaim'.'', 0, 0, 'L');
            $pdf->SetX(50);
            $pdf->cell(50, 5, ':', 0, 0, 'L');
            $pdf->SetX(45);
            $pdf->cell(40, 5, duit($sub_jml_spk_closefile + $sub_jml_other_closefile), 0, 0, 'C');
            $pdf->cell(40, 5, duit($sub_plafond_spk_closefile + $sub_plafond_other_closefile), 0, 0, 'C');
            $pdf->cell(40, 5, duit($sub_klaim_spk_closefile + $sub_klaim_other_closefile), 0, 0, 'C');
            $pdf->cell(40, 5, duit($sub_klaim_payment_spk_closefile + $sub_klaim_payment_other_closefile), 0, 0, 'C');
            $pdf->SetY($y=$y+5);
            $pdf->Line(18, $y, 200, $y);

            //belum close file

            $jml_spk_closefile = 0;
            $plafond_spk_closefile = 0;
            $klaim_spk_closefile = 0;
            $klaim_payment_spk_closefile = 0;
            $jml_percepatan_closefile = 0;
            $plafond_percepatan_closefile = 0;
            $klaim_percepatan_closefile = 0;
            $klaim_payment_percepatan_closefile = 0;
            $jml_spkmpp_closefile = 0;
            $plafond_spkmpp_closefile = 0;
            $klaim_spkmpp_closefile = 0;
            $klaim_payment_spkmpp_closefile = 0;
            $jml_abri_closefile = 0;
            $plafond_abri_closefile = 0;
            $klaim_abri_closefile = 0;
            $klaim_payment_abri_closefile = 0;
            $jml_percmpp_closefile = 0;
            $plafond_percmpp_closefile = 0;
            $klaim_percmpp_closefile = 0;
            $klaim_payment_percmpp_closefile = 0;
            $jml_platinum_closefile = 0;
            $plafond_platinum_closefile = 0;
            $klaim_platinum_closefile = 0;
            $klaim_payment_platinum_closefile = 0;
            $jml_hakashima_closefile = 0;
            $plafond_hakashima_closefile = 0;
            $klaim_hakashima_closefile = 0;
            $klaim_payment_hakashima_closefile = 0;

            $qklaim = mysql_query(" select fu_ajk_cn.id_nopol,
																		 fu_ajk_polis.nmproduk,
																		 count(*)as jml_debitur,
																		 sum(fu_ajk_peserta.kredit_jumlah)as  plafond,
																		 sum(fu_ajk_cn.tuntutan_klaim) as klaim,
																		 sum(fu_ajk_cn.total_claim) as klaim_payment
															from fu_ajk_cn
																	 inner join fu_ajk_klaim
																	 on fu_ajk_klaim.id_cn = fu_ajk_cn.id
																	 inner join fu_ajk_peserta
																	 on fu_ajk_peserta.id_klaim = fu_ajk_cn.id
																	 inner JOIN fu_ajk_polis
																	 on fu_ajk_polis.id = fu_ajk_cn.id_nopol
															where fu_ajk_cn.type_claim = 'Death' and
																		fu_ajk_cn.del is NULL and
																		fu_ajk_klaim.del is NULL and
																		fu_ajk_peserta.del is NULL and
																		fu_ajk_klaim.id_klaim_status != 1 AND
																		confirm_claim !='Pending'
															group by fu_ajk_cn.id_nopol");

            while ($qklaim_row = mysql_fetch_array($qklaim)) {
                if ($qklaim_row['id_nopol']== 1) {
                    $jml_spk_closefile = $qklaim_row['jml_debitur'];
                    $plafond_spk_closefile = $qklaim_row['plafond'];
                    $klaim_spk_closefile = $qklaim_row['klaim'];
                    $klaim_payment_spk_closefile = $qklaim_row['klaim_payment'];
                }

                if ($qklaim_row['id_nopol']== 12) {
                    $jml_spkmpp_closefile = $qklaim_row['jml_debitur'];
                    $plafond_spkmpp_closefile = $qklaim_row['plafond'];
                    $klaim_spkmpp_closefile = $qklaim_row['klaim'];
                    $klaim_payment_spkmpp_closefile = $qklaim_row['klaim_payment'];
                }

                if ($qklaim_row['id_nopol']== 2) {
                    $jml_percepatan_closefile = $qklaim_row['jml_debitur'];
                    $plafond_percepatan_closefile = $qklaim_row['plafond'];
                    $klaim_percepatan_closefile = $qklaim_row['klaim'];
                    $klaim_payment_percepatan_closefile = $qklaim_row['klaim_payment'];
                }

                if ($qklaim_row['id_nopol']== 3) {
                    $jml_abri_closefile = $qklaim_row['jml_debitur'];
                    $plafond_abri_closefile = $qklaim_row['plafond'];
                    $klaim_abri_closefile = $qklaim_row['klaim'];
                    $klaim_payment_abri_closefile = $qklaim_row['klaim_payment'];
                }

                if ($qklaim_row['id_nopol']== 11) {
                    $jml_percmpp_closefile = $qklaim_row['jml_debitur'];
                    $plafond_percmpp_closefile = $qklaim_row['plafond'];
                    $klaim_percmpp_closefile = $qklaim_row['klaim'];
                    $klaim_payment_percmpp_closefile = $qklaim_row['klaim_payment'];
                }

                if ($qklaim_row['id_nopol']== 16) {
                    $jml_platinum_closefile = $qklaim_row['jml_debitur'];
                    $plafond_platinum_closefile = $qklaim_row['plafond'];
                    $klaim_platinum_closefile = $qklaim_row['klaim'];
                    $klaim_payment_platinum_closefile = $qklaim_row['klaim_payment'];
                }

                if ($qklaim_row['id_nopol']== 18) {
                    $jml_hakashima_closefile = $qklaim_row['jml_debitur'];
                    $plafond_hakashima_closefile = $qklaim_row['plafond'];
                    $klaim_hakashima_closefile = $qklaim_row['klaim'];
                    $klaim_payment_hakashima_closefile = $qklaim_row['klaim_payment'];
                }
            }

                $sub_jml_spk_closefile = $jml_spkmpp_closefile + $jml_spk_closefile;
                $sub_plafond_spk_closefile = $plafond_spkmpp_closefile + $plafond_spk_closefile;
                $sub_klaim_spk_closefile = $klaim_spkmpp_closefile + $klaim_spk_closefile;
                $sub_klaim_payment_spk_closefile = $klaim_payment_spkmpp_closefile + $klaim_payment_spk_closefile;

                $sub_jml_other_closefile = $jml_percepatan_closefile + $jml_abri_closefile + $jml_percmpp_closefile + $jml_platinum_closefile + $jml_hakashima_closefile;
                $sub_plafond_other_closefile = $plafond_percepatan_closefile + $plafond_abri_closefile + $plafond_percmpp_closefile + $plafond_platinum_closefile + $plafond_hakashima_closefile;
                $sub_klaim_other_closefile = $klaim_percepatan_closefile + $klaim_abri_closefile + $klaim_percmpp_closefile + $klaim_platinum_closefile + $klaim_hakashima_closefile;
                $sub_klaim_payment_other_closefile = $klaim_payment_percepatan_closefile + $klaim_payment_abri_closefile + $klaim_payment_percmpp_closefile + $klaim_payment_platinum_closefile + $klaim_payment_hakashima_closefile;

                $pdf->SetY($y = $y+10);
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->SetX(18);
                $pdf->cell(35, 5, 'Belum Close File :'.'', 0, 0, 'L');
                $pdf->SetFont('Arial', '', 9);
                $pdf->SetY($y = $y+5);
                $pdf->Line(18, $y, 200, $y);
                $pdf->SetX(45);
                $pdf->cell(40, 5, 'Debitur'.'', 0, 0, 'C');
                $pdf->cell(40, 5, 'Plafond'.'', 0, 0, 'C');
                $pdf->cell(40, 5, 'Nilai Klaim'.'', 0, 0, 'C');
                $pdf->cell(40, 5, 'Dibayar'.'', 0, 0, 'C');
                $pdf->SetY($y=$y+5);
                $pdf->Line(18, $y, 200, $y);
                $pdf->SetX($x);

                $pdf->cell(50, 5, 'SPK Reguler', 0, 0, 'L');
                $pdf->SetX(50);
                $pdf->cell(50, 5, ':', 0, 0, 'L');
                $pdf->SetX(45);
                $pdf->cell(40, 5, duit($jml_spk_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($plafond_spk_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($klaim_spk_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($klaim_payment_spk_closefile), 0, 0, 'C');

                $pdf->SetY($y=$y+5);
                $pdf->SetX($x);
                $pdf->cell(50, 5, 'Reguler MPP'.'', 0, 0, 'L');
                $pdf->SetX(50);
                $pdf->cell(50, 5, ':', 0, 0, 'L');
                $pdf->SetX(45);
                $pdf->cell(40, 5, duit($jml_spkmpp_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($plafond_spkmpp_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($klaim_spkmpp_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($klaim_payment_spkmpp_closefile), 0, 0, 'C');
                $pdf->SetY($y=$y+5);
                $pdf->cell(50, 5, 'Subtotal'.'', 0, 0, 'C');
                $pdf->SetX(50);
                $pdf->cell(50, 5, ':', 0, 0, 'L');
                $pdf->SetX(45);
                $pdf->cell(40, 5, duit($sub_jml_spk_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($sub_plafond_spk_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($sub_klaim_spk_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($sub_payment_klaim_spk_closefile), 0, 0, 'C');
                $pdf->SetY($y=$y+10);
                $pdf->SetX($x);

                $pdf->cell(50, 5, 'Percepatan', 0, 0, 'L');
                $pdf->SetX(50);
                $pdf->cell(50, 5, ':', 0, 0, 'L');
                $pdf->SetX(45);
                $pdf->cell(40, 5, duit($jml_percepatan_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($plafond_percepatan_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($klaim_percepatan_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($klaim_payment_percepatan_closefile), 0, 0, 'C');
                $pdf->SetY($y=$y+5);
                $pdf->SetX($x);

                $pdf->cell(50, 5, 'Percepatan MPP'.'', 0, 0, 'L');
                $pdf->SetX(50);
                $pdf->cell(50, 5, ':', 0, 0, 'L');
                $pdf->SetX(45);
                $pdf->cell(40, 5, duit($jml_percmpp_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($plafond_percmpp_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($klaim_percmpp_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($klaim_payment_percmpp_closefile), 0, 0, 'C');
                $pdf->SetY($y=$y+5);
                $pdf->SetX($x);

                $pdf->cell(50, 5, 'ABRI'.'', 0, 0, 'L');
                $pdf->SetX(50);
                $pdf->cell(50, 5, ':', 0, 0, 'L');
                $pdf->SetX(45);
                $pdf->cell(40, 5, duit($jml_abri_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($plafond_abri_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($klaim_abri_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($klaim_payment_abri_closefile), 0, 0, 'C');
                $pdf->SetY($y=$y+5);
                $pdf->SetX($x);

                $pdf->cell(50, 5, 'Platinum'.'', 0, 0, 'L');
                $pdf->SetX(50);
                $pdf->cell(50, 5, ':', 0, 0, 'L');
                $pdf->SetX(45);
                $pdf->cell(40, 5, duit($jml_platinum_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($plafond_platinum_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($klaim_platinum_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($klaim_payment_platinum_closefile), 0, 0, 'C');
                $pdf->SetY($y=$y+5);
                $pdf->SetX($x);

                $pdf->cell(50, 5, 'Hakashima'.'', 0, 0, 'L');
                $pdf->SetX(50);
                $pdf->cell(50, 5, ':', 0, 0, 'L');
                $pdf->SetX(45);
                $pdf->cell(40, 5, duit($jml_hakashima_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($plafond_hakashima_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($klaim_hakashima_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($klaim_paymenthakashima_closefile), 0, 0, 'C');
                $pdf->SetY($y=$y+5);
                $pdf->cell(50, 5, 'Subtotal'.'', 0, 0, 'C');
                $pdf->SetX(50);
                $pdf->cell(50, 5, ':', 0, 0, 'L');
                $pdf->SetX(45);
                $pdf->cell(40, 5, duit($sub_jml_other_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($sub_plafond_other_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($sub_klaim_other_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($sub_klaim_payment_other_closefile), 0, 0, 'C');
                $pdf->SetY($y=$y+10);
                $pdf->SetX($x);
                $pdf->SetFont('Arial', 'B', 9);
                $pdf->cell(50, 5, 'Total Klaim'.'', 0, 0, 'L');
                $pdf->SetX(50);
                $pdf->cell(50, 5, ':', 0, 0, 'L');
                $pdf->SetX(45);
                $pdf->cell(40, 5, duit($sub_jml_spk_closefile + $sub_jml_other_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($sub_plafond_spk_closefile + $sub_plafond_other_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($sub_klaim_spk_closefile + $sub_klaim_other_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($sub_klaim_payment_spk_closefile + $sub_klaim_payment_other_closefile), 0, 0, 'C');
                $pdf->SetY($y=$y+5);
                $pdf->Line(18, $y, 200, $y);
            //Klaim Baru Hari ini

            $jml_spk_closefile = 0;
            $plafond_spk_closefile = 0;
            $klaim_spk_closefile = 0;
            $klaim_payment_spk_closefile = 0;
            $jml_percepatan_closefile = 0;
            $plafond_percepatan_closefile = 0;
            $klaim_percepatan_closefile = 0;
            $klaim_payment_percepatan_closefile = 0;
            $jml_spkmpp_closefile = 0;
            $plafond_spkmpp_closefile = 0;
            $klaim_spkmpp_closefile = 0;
            $klaim_payment_spkmpp_closefile = 0;
            $jml_abri_closefile = 0;
            $plafond_abri_closefile = 0;
            $klaim_abri_closefile = 0;
            $klaim_payment_abri_closefile = 0;
            $jml_percmpp_closefile = 0;
            $plafond_percmpp_closefile = 0;
            $klaim_percmpp_closefile = 0;
            $klaim_payment_percmpp_closefile = 0;
            $jml_platinum_closefile = 0;
            $plafond_platinum_closefile = 0;
            $klaim_platinum_closefile = 0;
            $klaim_payment_platinum_closefile = 0;
            $jml_hakashima_closefile = 0;
            $plafond_hakashima_closefile = 0;
            $klaim_hakashima_closefile = 0;
            $klaim_payment_hakashima_closefile = 0;

            $qklaim = mysql_query(" select fu_ajk_cn.id_nopol,
																		 fu_ajk_polis.nmproduk,
																		 count(*)as jml_debitur,
																		 sum(fu_ajk_peserta.kredit_jumlah)as  plafond,
																		 sum(fu_ajk_cn.tuntutan_klaim) as klaim,
																		 sum(fu_ajk_cn.total_claim) as klaim_payment
															from fu_ajk_cn
																	 inner join fu_ajk_klaim
																	 on fu_ajk_klaim.id_cn = fu_ajk_cn.id
																	 inner join fu_ajk_peserta
																	 on fu_ajk_peserta.id_klaim = fu_ajk_cn.id
																	 inner JOIN fu_ajk_polis
																	 on fu_ajk_polis.id = fu_ajk_cn.id_nopol
															where fu_ajk_cn.type_claim = 'Death' and
																		fu_ajk_cn.del is NULL and
																		fu_ajk_klaim.del is NULL and
																		fu_ajk_peserta.del is NULL and
																		date(fu_ajk_cn.approve_date) = CURDATE() AND
																		confirm_claim !='Pending'
															group by fu_ajk_cn.id_nopol");

            while ($qklaim_row = mysql_fetch_array($qklaim)) {
                if ($qklaim_row['id_nopol']== 1) {
                    $jml_spk_closefile = $qklaim_row['jml_debitur'];
                    $plafond_spk_closefile = $qklaim_row['plafond'];
                    $klaim_spk_closefile = $qklaim_row['klaim'];
                    $klaim_payment_spk_closefile = $qklaim_row['klaim_payment'];
                }

                if ($qklaim_row['id_nopol']== 12) {
                    $jml_spkmpp_closefile = $qklaim_row['jml_debitur'];
                    $plafond_spkmpp_closefile = $qklaim_row['plafond'];
                    $klaim_spkmpp_closefile = $qklaim_row['klaim'];
                    $klaim_payment_spkmpp_closefile = $qklaim_row['klaim_payment'];
                }

                if ($qklaim_row['id_nopol']== 2) {
                    $jml_percepatan_closefile = $qklaim_row['jml_debitur'];
                    $plafond_percepatan_closefile = $qklaim_row['plafond'];
                    $klaim_percepatan_closefile = $qklaim_row['klaim'];
                    $klaim_payment_percepatan_closefile = $qklaim_row['klaim_payment'];
                }

                if ($qklaim_row['id_nopol']== 3) {
                    $jml_abri_closefile = $qklaim_row['jml_debitur'];
                    $plafond_abri_closefile = $qklaim_row['plafond'];
                    $klaim_abri_closefile = $qklaim_row['klaim'];
                    $klaim_payment_abri_closefile = $qklaim_row['klaim_payment'];
                }

                if ($qklaim_row['id_nopol']== 11) {
                    $jml_percmpp_closefile = $qklaim_row['jml_debitur'];
                    $plafond_percmpp_closefile = $qklaim_row['plafond'];
                    $klaim_percmpp_closefile = $qklaim_row['klaim'];
                    $klaim_payment_percmpp_closefile = $qklaim_row['klaim_payment'];
                }

                if ($qklaim_row['id_nopol']== 16) {
                    $jml_platinum_closefile = $qklaim_row['jml_debitur'];
                    $plafond_platinum_closefile = $qklaim_row['plafond'];
                    $klaim_platinum_closefile = $qklaim_row['klaim'];
                    $klaim_payment_platinum_closefile = $qklaim_row['klaim_payment'];
                }

                if ($qklaim_row['id_nopol']== 18) {
                    $jml_hakashima_closefile = $qklaim_row['jml_debitur'];
                    $plafond_hakashima_closefile = $qklaim_row['plafond'];
                    $klaim_hakashima_closefile = $qklaim_row['klaim'];
                    $klaim_payment_hakashima_closefile = $qklaim_row['klaim_payment'];
                }
            }

                $sub_jml_spk_closefile = $jml_spkmpp_closefile + $jml_spk_closefile;
                $sub_plafond_spk_closefile = $plafond_spkmpp_closefile + $plafond_spk_closefile;
                $sub_klaim_spk_closefile = $klaim_spkmpp_closefile + $klaim_spk_closefile;
                $sub_klaim_payment_spk_closefile = $klaim_payment_spkmpp_closefile + $klaim_payment_spk_closefile;

                $sub_jml_other_closefile = $jml_percepatan_closefile + $jml_abri_closefile + $jml_percmpp_closefile + $jml_platinum_closefile + $jml_hakashima_closefile;
                $sub_plafond_other_closefile = $plafond_percepatan_closefile + $plafond_abri_closefile + $plafond_percmpp_closefile + $plafond_platinum_closefile + $plafond_hakashima_closefile;
                $sub_klaim_other_closefile = $klaim_percepatan_closefile + $klaim_abri_closefile + $klaim_percmpp_closefile + $klaim_platinum_closefile + $klaim_hakashima_closefile;
                $sub_klaim_payment_other_closefile = $klaim_payment_percepatan_closefile + $klaim_payment_abri_closefile + $klaim_payment_percmpp_closefile + $klaim_payment_platinum_closefile + $klaim_payment_hakashima_closefile;

                $pdf->SetY($y = $y+10);
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->SetX(18);
                $pdf->cell(35, 5, 'Klaim baru hari ini :'.'', 0, 0, 'L');
                $pdf->SetFont('Arial', '', 9);
                $pdf->SetY($y = $y+5);
                $pdf->Line(18, $y, 200, $y);
                $pdf->SetX(45);
                $pdf->cell(40, 5, 'Debitur'.'', 0, 0, 'C');
                $pdf->cell(40, 5, 'Plafond'.'', 0, 0, 'C');
                $pdf->cell(40, 5, 'Nilai Klaim'.'', 0, 0, 'C');
                $pdf->cell(40, 5, 'Dibayar'.'', 0, 0, 'C');
                $pdf->SetY($y=$y+5);
                $pdf->Line(18, $y, 200, $y);
                $pdf->SetX($x);

                $pdf->cell(50, 5, 'SPK Reguler', 0, 0, 'L');
                $pdf->SetX(50);
                $pdf->cell(50, 5, ':', 0, 0, 'L');
                $pdf->SetX(45);
                $pdf->cell(40, 5, duit($jml_spk_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($plafond_spk_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($klaim_spk_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($klaim_payment_spk_closefile), 0, 0, 'C');

                $pdf->SetY($y=$y+5);
                $pdf->SetX($x);
                $pdf->cell(50, 5, 'Reguler MPP'.'', 0, 0, 'L');
                $pdf->SetX(50);
                $pdf->cell(50, 5, ':', 0, 0, 'L');
                $pdf->SetX(45);
                $pdf->cell(40, 5, duit($jml_spkmpp_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($plafond_spkmpp_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($klaim_spkmpp_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($klaim_payment_spkmpp_closefile), 0, 0, 'C');
                $pdf->SetY($y=$y+5);
                $pdf->cell(50, 5, 'Subtotal'.'', 0, 0, 'C');
                $pdf->SetX(50);
                $pdf->cell(50, 5, ':', 0, 0, 'L');
                $pdf->SetX(45);
                $pdf->cell(40, 5, duit($sub_jml_spk_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($sub_plafond_spk_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($sub_klaim_spk_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($sub_payment_klaim_spk_closefile), 0, 0, 'C');
                $pdf->SetY($y=$y+10);
                $pdf->SetX($x);

                $pdf->cell(50, 5, 'Percepatan', 0, 0, 'L');
                $pdf->SetX(50);
                $pdf->cell(50, 5, ':', 0, 0, 'L');
                $pdf->SetX(45);
                $pdf->cell(40, 5, duit($jml_percepatan_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($plafond_percepatan_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($klaim_percepatan_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($klaim_payment_percepatan_closefile), 0, 0, 'C');
                $pdf->SetY($y=$y+5);
                $pdf->SetX($x);

                $pdf->cell(50, 5, 'Percepatan MPP'.'', 0, 0, 'L');
                $pdf->SetX(50);
                $pdf->cell(50, 5, ':', 0, 0, 'L');
                $pdf->SetX(45);
                $pdf->cell(40, 5, duit($jml_percmpp_closefile), 0, 0, 'C');
                $pdf->SetY($y=$y+5);
                $pdf->SetX($x);

                $pdf->cell(50, 5, 'ABRI'.'', 0, 0, 'L');
                $pdf->SetX(50);
                $pdf->cell(50, 5, ':', 0, 0, 'L');
                $pdf->SetX(45);
                $pdf->cell(40, 5, duit($jml_abri_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($plafond_abri_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($klaim_abri_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($klaim_payment_abri_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($plafond_percmpp_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($klaim_percmpp_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($klaim_payment_percmpp_closefile), 0, 0, 'C');
                $pdf->SetY($y=$y+5);
                $pdf->SetX($x);

                $pdf->cell(50, 5, 'Platinum'.'', 0, 0, 'L');
                $pdf->SetX(50);
                $pdf->cell(50, 5, ':', 0, 0, 'L');
                $pdf->SetX(45);
                $pdf->cell(40, 5, duit($jml_platinum_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($plafond_platinum_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($klaim_platinum_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($klaim_payment_platinum_closefile), 0, 0, 'C');
                $pdf->SetY($y=$y+5);
                $pdf->SetX($x);

                $pdf->cell(50, 5, 'Hakashima'.'', 0, 0, 'L');
                $pdf->SetX(50);
                $pdf->cell(50, 5, ':', 0, 0, 'L');
                $pdf->SetX(45);
                $pdf->cell(40, 5, duit($jml_hakashima_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($plafond_hakashima_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($klaim_hakashima_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($klaim_paymenthakashima_closefile), 0, 0, 'C');
                $pdf->SetY($y=$y+5);
                $pdf->cell(50, 5, 'Subtotal'.'', 0, 0, 'C');
                $pdf->SetX(50);
                $pdf->cell(50, 5, ':', 0, 0, 'L');
                $pdf->SetX(45);
                $pdf->cell(40, 5, duit($sub_jml_other_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($sub_plafond_other_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($sub_klaim_other_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($sub_klaim_payment_other_closefile), 0, 0, 'C');
                $pdf->SetY($y=$y+10);
                $pdf->SetX($x);
                $pdf->SetFont('Arial', 'B', 9);
                $pdf->cell(50, 5, 'Total Klaim'.'', 0, 0, 'L');
                $pdf->SetX(50);
                $pdf->cell(50, 5, ':', 0, 0, 'L');
                $pdf->SetX(45);
                $pdf->cell(40, 5, duit($sub_jml_spk_closefile + $sub_jml_other_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($sub_plafond_spk_closefile + $sub_plafond_other_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($sub_klaim_spk_closefile + $sub_klaim_other_closefile), 0, 0, 'C');
                $pdf->cell(40, 5, duit($sub_klaim_payment_spk_closefile + $sub_klaim_payment_other_closefile), 0, 0, 'C');
                $pdf->SetY($y=$y+5);
                $pdf->Line(18, $y, 200, $y);

            $pdf->Output("Total Klaim ".date("d-m-Y").".pdf", "I");
        ;
    break;

    case "eL_pengajuanklaim":
        $pdf=new FPDF('P', 'mm', 'A4');
        $pdf->Open();
        $pdf->AliasNbPages();


        $no_surat_klaim = $_REQUEST['nsk'];
        $no_surat_keterangan = $_REQUEST['sk'];
        $no_surat_pembayaran = $_REQUEST['sp'];
        $id_klaim = $_REQUEST['id'];
        $no_tanda_terima = $_REQUEST['ntt'];
        $tgl_tt = viewBulanIndo($_REQUEST['tgltt']);
        $today = viewBulanIndo(date('Y-m-d'));
        $qpeserta = mysql_fetch_array(mysql_query("select *,(select nmproduk from fu_ajk_polis where fu_ajk_polis.id = fu_ajk_peserta.id_polis)as nm_produk from fu_ajk_peserta where id_klaim = '".$id_klaim."'"));
        $qcn = mysql_fetch_array(mysql_query("select * from fu_ajk_cn where id = '".$id_klaim."'"));
        $qklaim = mysql_fetch_array(mysql_query("select *,DATE_ADD(tgl_klaim,INTERVAL 150 DAY)as due_date from fu_ajk_klaim where id_cn = '".$id_klaim."'"));
        $qmitra = mysql_fetch_array(mysql_query("select * from fu_ajk_grupproduk where id = ".$qpeserta['nama_mitra']));
        $qasuransi = mysql_fetch_array(mysql_query("select fu_ajk_asuransi.name,
																												fu_ajk_asuransi.address,
																												fu_ajk_asuransi.city,
																												fu_ajk_asuransi.postcode,
																												fu_ajk_asuransi.pic,
																												fu_ajk_asuransi.wilayah
																								from fu_ajk_asuransi
																										 inner join fu_ajk_peserta_as
																										 on fu_ajk_peserta_as.id_asuransi = fu_ajk_asuransi.id
																								where fu_ajk_peserta_as.id_peserta = '".$qpeserta['id_peserta']."'"));
        $qdocklaim = mysql_query("SELECT fu_ajk_dokumenklaim.nama_dok
															FROM fu_ajk_dokumenklaim_bank
															INNER JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim_bank.id_dok = fu_ajk_dokumenklaim.id
															INNER JOIN fu_ajk_klaim_doc ON fu_ajk_klaim_doc.dokumen = fu_ajk_dokumenklaim_bank.id
															WHERE id_klaim = ".$id_klaim." AND dok_kirim = 'T' order by fu_ajk_dokumenklaim.urut asc");
        $qdocklaim2 = mysql_query("SELECT fu_ajk_dokumenklaim.nama_dok
															FROM fu_ajk_dokumenklaim_bank
															INNER JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim_bank.id_dok = fu_ajk_dokumenklaim.id
															INNER JOIN fu_ajk_klaim_doc ON fu_ajk_klaim_doc.dokumen = fu_ajk_dokumenklaim_bank.id
															WHERE id_klaim = ".$id_klaim." AND dok_kirim = 'T' order by fu_ajk_dokumenklaim.urut asc");
        $qdockelengkapan = mysql_query("SELECT fu_ajk_dokumenklaim.nama_dok,ket_dokumen
																		FROM fu_ajk_dokumenklaim_bank
																		INNER JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim_bank.id_dok = fu_ajk_dokumenklaim.id
																		INNER JOIN fu_ajk_klaim_doc ON fu_ajk_klaim_doc.dokumen = fu_ajk_dokumenklaim_bank.id
																		WHERE id_klaim = ".$id_klaim." AND fu_ajk_klaim_doc.status != 'close' order by fu_ajk_dokumenklaim.urut asc");

        $nm_peserta = ucwords(strtolower($qpeserta['nama']));
        $nm_cabang = ucwords(strtolower($qpeserta['cabang']));
        $nm_produk= ucwords(strtolower($qpeserta['nm_produk']));
        $tgl_lahir = viewBulanIndo($qpeserta['tgl_lahir']);
        $tgl_meninggal = viewBulanIndo($qcn['tgl_claim']);
        $periode_asuransi = viewBulanIndo($qpeserta['kredit_tgl']) .' s/d '.viewBulanIndo($qpeserta['kredit_akhir']);
        $tgl_surat = viewBulanIndo($_REQUEST['tgl']);
        $plafond = duit($qpeserta['kredit_jumlah']);
        $tuntutan_klaim = duit($qcn['tuntutan_klaim']);
        $tgl_terima_document = viewBulanIndo($qklaim['tgl_document']);
        $tgl_duedate = viewBulanIndo($qklaim['due_date']);
        if ($qasuransi['id']==5) {
            $jabatan = '';
        } else {
            $jabatan = '';
        }

        $pdf->AddPage();
        $y = 25;
        $x = 10;
        $font_size = 11;

        if ($_REQUEST['tipe']=="pengajuan") {
            $pdf->SetFont('Arial', '', $font_size);
            $pdf->SetY($y);
            $pdf->SetX($x);
            $pdf->cell(50, 25, 'Nomor', 0, 0, 'L');
            $pdf->SetX(40);
            $pdf->cell(1, 25, ':', 0, 0, 'L');
            $pdf->SetX(42);
            $pdf->cell(20, 25, $no_surat_klaim, 0, 0, 'L');
            $pdf->SetX(100);
            $pdf->cell(100, 25, 'Bekasi, '.$today, 0, 0, 'R');
            $pdf->SetY($y=$y+5);
            $pdf->SetX($x);
            $pdf->cell(50, 25, 'Lampiran', 0, 0, 'L');
            $pdf->SetX(40);
            $pdf->cell(1, 25, ':', 0, 0, 'L');
            $pdf->SetX(42);
            $pdf->cell(20, 25, 'Terlampir', 0, 0, 'L');
            $pdf->SetY($y = $y+5);
            $pdf->SetX($x);
            $pdf->cell(50, 25, 'Perihal', 0, 0, 'L');
            $pdf->SetX(40);
            $pdf->cell(1, 25, ':', 0, 0, 'L');
            $pdf->SetFont('Arial', 'B', $font_size);
            $pdf->SetX(42);
            $pdf->cell(20, 25, 'Pengajuan Klaim AJK Bukopin an : '.$nm_peserta.'', 0, 0, 'L');
            $pdf->SetFont('Arial', '', $font_size);
            $pdf->SetY($y = $y+10);
            $pdf->SetX($x);
            $pdf->cell(50, 25, 'Kepada Yth,', 0, 0, 'L');
            $pdf->SetFont('Arial', 'B', $font_size);
            $pdf->SetY($y = $y+5);
            $pdf->SetX($x);
            $pdf->cell(50, 25, $qasuransi['name'], 0, 0, 'L');
            $pdf->SetY($y = $y+5);
            $pdf->SetX($x);
            $pdf->cell(50, 25, $qasuransi['city'], 0, 0, 'L');
            $pdf->SetFont('Arial', '', $font_size);
            $pdf->SetY($y = $y+5);
            $pdf->SetX($x);
            $pdf->cell(50, 25, $qasuransi['address'], 0, 0, 'L');
            $pdf->SetY($y = $y+5);
            $pdf->SetX($x);
            $pdf->cell(50, 25, $qasuransi['wilayah'].' '.$qasuransi['postcode'], 0, 0, 'L');
            $pdf->SetFont('Arial', 'B', $font_size);
            $pdf->SetY($y = $y+10);
            $pdf->SetX($x);
            $pdf->cell(50, 25, 'UP', 0, 0, 'L');
            $pdf->SetX(40);
            $pdf->cell(1, 25, ':', 0, 0, 'L');
            $pdf->SetX(42);
            $pdf->cell(100, 25, 'Yth, Bapak '.$qasuransi['pic'].' '.$jabatan, 0, 0, 'L');
            $pdf->SetFont('Arial', '', $font_size);
            $pdf->SetY($y = $y+10);
            $pdf->SetX($x);
            $pdf->cell(50, 25, 'Dengan Hormat,', 0, 0, 'L');
            $pdf->SetY($y = $y+10);
            $pdf->SetX($x);
            $pdf->cell(200, 25, 'Menindaklanjuti laporan klaim awal pada tanggal '.$tgl_terima_document.' serta merunjuk surat '.$qmitra['nm_mitra'], 0, 0, 'L');
            $pdf->SetY($y = $y+5);
            $pdf->SetX($x);
            $pdf->cell(200, 25, 'Nomor : '.$no_surat_keterangan.' tertanggal '.$tgl_surat.'. Bersama ini kami sampaikan pengajuan klaim', 0, 0, 'L');
            $pdf->SetY($y = $y+5);
            $pdf->SetX($x);
            $pdf->cell(200, 25, 'dengan data debitur sebagai berikut:', 0, 0, 'L');
            $pdf->SetY($y = $y+8);
            $pdf->SetX($x);
            $pdf->cell(200, 25, 'Nama Debitur', 0, 0, 'L');
            $pdf->SetX(65);
            $pdf->cell(1, 25, ':', 0, 0, 'L');
            $pdf->SetX(67);
            $pdf->cell(100, 25, $nm_peserta, 0, 0, 'L');
            $pdf->SetY($y = $y+5);
            $pdf->SetX($x);
            $pdf->cell(200, 25, 'Tanggal Lahir', 0, 0, 'L');
            $pdf->SetX(65);
            $pdf->cell(1, 25, ':', 0, 0, 'L');
            $pdf->SetX(67);
            $pdf->cell(100, 25, $tgl_lahir, 0, 0, 'L');
            $pdf->SetY($y = $y+5);
            $pdf->SetX($x);
            $pdf->cell(200, 25, 'Periode Asuransi', 0, 0, 'L');
            $pdf->SetX(65);
            $pdf->cell(1, 25, ':', 0, 0, 'L');
            $pdf->SetX(67);
            $pdf->cell(100, 25, $periode_asuransi, 0, 0, 'L');
            $pdf->SetY($y = $y+5);
            $pdf->SetX($x);
            $pdf->cell(200, 25, 'Plafond Kredit', 0, 0, 'L');
            $pdf->SetX(65);
            $pdf->cell(1, 25, ':', 0, 0, 'L');
            $pdf->SetX(67);
            $pdf->cell(100, 25, 'Rp. '.$plafond.',-', 0, 0, 'L');
            $pdf->SetY($y = $y+5);
            $pdf->SetX($x);
            $pdf->cell(200, 25, 'Nilai Tuntutan Klaim', 0, 0, 'L');
            $pdf->SetX(65);
            $pdf->cell(1, 25, ':', 0, 0, 'L');
            $pdf->SetX(67);
            $pdf->cell(100, 25, 'Rp. '.$tuntutan_klaim.',-', 0, 0, 'L');
            $pdf->SetY($y = $y+5);
            $pdf->SetX($x);
            $pdf->cell(200, 25, 'Tanggal Meninggal Dunia', 0, 0, 'L');
            $pdf->SetX(65);
            $pdf->cell(1, 25, ':', 0, 0, 'L');
            $pdf->SetX(67);
            $pdf->cell(100, 25, $tgl_meninggal, 0, 0, 'L');
            $pdf->SetY($y = $y+5);
            $pdf->SetX($x);
            $pdf->cell(200, 25, 'Program Asuransi', 0, 0, 'L');
            $pdf->SetX(65);
            $pdf->cell(1, 25, ':', 0, 0, 'L');
            $pdf->SetX(67);
            $pdf->cell(100, 25, $nm_produk, 0, 0, 'L');
            $pdf->SetY($y = $y+8);
            $pdf->SetX($x);
            $pdf->cell(200, 25, 'Sebagai Syarat pengajuan klaim tersebut, kami sampaikan dokumen pendukung klaim (terlampir) ', 0, 0, 'L');
            $pdf->SetY($y = $y+5);
            $pdf->SetX($x);
            $pdf->cell(200, 25, 'terdiri dari :', 0, 0, 'L');
            $pdf->SetY($y = $y+5);
            $no = 1;
            while ($qdocklaim_row = mysql_fetch_array($qdocklaim)) {
                $pdf->SetY($y = $y+5);
                $pdf->SetX(20);
                $pdf->cell(3, 25, $no.'.', 0, 0, 'L');
                $pdf->SetX(26);
                $pdf->cell(200, 25, $qdocklaim_row['nama_dok'], 0, 0, 'L');
                $no++;
            }
            $pdf->AddPage();
            $y = 25;
            $x = 10;
            $pdf->SetFont('Arial', '', $font_size);
            $pdf->SetY($y);
            $pdf->SetX($x);
            $pdf->cell(200, 25, 'Demikian kami sampaikan untuk menjadi perhatian dan dapat ditindaklanjuti. Atas perhatian dan', 0, 0, 'L');
            $pdf->SetY($y = $y+5);
            $pdf->SetX($x);
            $pdf->cell(200, 25, 'kerjasamanya, kami ucapkan terima kasih.', 0, 0, 'L');
            $pdf->SetY($y = $y+10);
            $pdf->SetX($x);
            $pdf->cell(200, 25, 'Hormat kami,', 0, 0, 'L');
            $pdf->SetFont('Arial', 'BU', $font_size);
            $pdf->SetY($y = $y+30);
            $pdf->SetX($x);
            $pdf->cell(200, 25, 'Wirawendra', 0, 0, 'L');
            $pdf->SetFont('Arial', 'B', $font_size);
            $pdf->SetY($y = $y+5);
            $pdf->SetX($x);
            $pdf->cell(200, 25, 'Claim Head', 0, 0, 'L');
            //TANDA TERIMA
            $pdf->AddPage();
            $pdf->SetFont('Arial', '', $font_size);
            $y = 35;
            $pdf->SetY($y = $y+5);
            $pdf->SetX(120);
            $pdf->cell(100, 25, 'Bekasi, '.$tgl_tt, 0, 0, 'L');
            $pdf->SetY($y = $y+10);
            $pdf->SetX(120);
            $pdf->cell(100, 25, 'Ditujukan Kepada : ', 0, 0, 'L');
            $pdf->SetFont('Arial', 'B', $font_size);
            $pdf->SetX(155);
            $pdf->cell(100, 25, 'Yth, Bapak '.$qasuransi['pic'], 0, 0, 'L');
            $pdf->SetY($y = $y+5);
            $pdf->SetX(120);
            $pdf->cell(100, 25, $jabatan, 0, 0, 'L');
            $pdf->SetY($y = $y+5);
            $pdf->SetX(120);
            $pdf->cell(100, 25, $qasuransi['name'], 0, 0, 'L');
            $pdf->SetY($y = $y+5);
            $pdf->SetX(120);
            $pdf->cell(100, 25, $qasuransi['wilayah'], 0, 0, 'L');
            $pdf->SetFont('Arial', '', $font_size);
            $pdf->SetY($y = $y+5);
            $pdf->SetX(120);
            $pdf->cell(100, 25, $qasuransi['address'], 0, 0, 'L');
            $pdf->SetY($y = $y+5);
            $pdf->SetX(120);
            $pdf->cell(100, 25, $qasuransi['wilayah'].' '.$qasuransi['postcode'], 0, 0, 'L');
            $pdf->SetFont('Arial', 'B', 15);
            $pdf->SetY($y = $y+15);
            $pdf->SetX(50);
            $pdf->cell(100, 25, 'Tanda Terima', 0, 0, 'C');
            $pdf->SetFont('Arial', 'U', $font_size);
            $pdf->SetY($y = $y+10);
            $pdf->SetX(50);
            $pdf->cell(100, 25, $no_tanda_terima, 0, 0, 'C');
            $pdf->SetFont('Arial', '', $font_size);
            $pdf->SetY($y = $y+20);
            $pdf->SetX(10);
            $pdf->cell(200, 25, 'No Surat : '.$no_surat_klaim.'. Pengajuan Klaim AJK Bukopin an : '.$nm_peserta.' berupa:', 0, 0, 'L');
            $pdf->SetY($y = $y+5);
            $no = 1;
            while ($qdocklaim_row2 = mysql_fetch_array($qdocklaim2)) {
                $pdf->SetY($y = $y+5);
                $pdf->SetX(20);
                $pdf->cell(3, 25, $no.'.', 0, 0, 'L');
                $pdf->SetX(26);
                $pdf->cell(200, 25, $qdocklaim_row2['nama_dok'], 0, 0, 'L');
                $no++;
            }
            $pdf->SetY($y = $y+10);
            $pdf->SetX($x);
            $pdf->cell(200, 25, 'Demikian kami sampaikan, atas perhatian dan kerjasamanya kami mengucapkan terima kasih.', 0, 0, 'L');
            $pdf->SetY($y = $y+10);
            $pdf->SetX(23);
            $pdf->cell(20, 25, 'Yang Menerima', 0, 0, 'L');
            $pdf->SetY($y);
            $pdf->SetX(130);
            $pdf->cell(20, 25, 'Yang Menyerahkan', 0, 0, 'L');
            $pdf->SetY($y = $y + 30);
            $pdf->SetX(15);
            $pdf->cell(50, 25, '(', 0, 0, 'L');
            $pdf->SetX(55);
            $pdf->cell(50, 25, ')', 0, 0, 'L');
            $pdf->SetX(125);
            $pdf->cell(50, 25, '(', 0, 0, 'L');
            $pdf->SetX(165);
            $pdf->cell(50, 25, ')', 0, 0, 'L');
        } elseif ($_REQUEST['tipe']=="pembayaran") {
            $pdf->SetFont('Arial', '', $font_size);
            $pdf->SetY($y);
            $pdf->SetX($x);
            $pdf->cell(50, 25, 'No.', 0, 0, 'L');
            $pdf->SetX(40);
            $pdf->cell(1, 25, ':', 0, 0, 'L');
            $pdf->SetX(42);
            $pdf->cell(20, 25, $no_surat_pembayaran, 0, 0, 'L');
            $pdf->SetX(100);
            $pdf->cell(100, 25, 'Bekasi, '.$today, 0, 0, 'R');
            $pdf->SetY($y=$y+5);
            $pdf->SetX($x);
            $pdf->cell(50, 25, 'Lamp.', 0, 0, 'L');
            $pdf->SetX(40);
            $pdf->cell(1, 25, ':', 0, 0, 'L');
            $pdf->SetX(42);
            $pdf->cell(20, 25, 'Terlampir', 0, 0, 'L');
            $pdf->SetY($y = $y+5);
            $pdf->SetX($x);
            $pdf->cell(50, 25, 'Perihal', 0, 0, 'L');
            $pdf->SetX(40);
            $pdf->cell(1, 25, ':', 0, 0, 'L');
            $pdf->SetFont('Arial', 'B', $font_size);
            $pdf->SetX(42);
            $pdf->cell(20, 25, 'Pembayaran Manfaat Asuransi a.n. : '.$nm_peserta.'', 0, 0, 'L');
            $pdf->SetFont('Arial', '', $font_size);
            $pdf->SetY($y = $y+10);
            $pdf->SetX($x);
            $pdf->cell(50, 25, 'Kepada Yth,', 0, 0, 'L');
            $pdf->SetFont('Arial', 'B', $font_size);
            $pdf->SetY($y = $y+5);
            $pdf->SetX($x);
            $pdf->cell(50, 25, 'PT. Bank Bukopin, Tbk Capem S.Parman', 0, 0, 'L');
            $pdf->SetY($y = $y+5);
            $pdf->SetX($x);
            $pdf->cell(50, 25, 'Bisnis Mikro', 0, 0, 'L');
            $pdf->SetFont('Arial', '', $font_size);
            $pdf->SetY($y = $y+5);
            $pdf->SetX($x);
            $pdf->cell(50, 25, 'Jl. S. Parman Kav. 80, Slipi,', 0, 0, 'L');
            $pdf->SetY($y = $y+5);
            $pdf->SetX($x);
            $pdf->cell(50, 25, 'Jakarta 11460', 0, 0, 'L');
            $pdf->SetFont('Arial', 'B', $font_size);
            $pdf->SetY($y = $y+10);
            $pdf->SetX($x);
            $pdf->cell(50, 25, 'UP', 0, 0, 'L');
            $pdf->SetX(40);
            $pdf->cell(1, 25, ':', 0, 0, 'L');
            $pdf->SetX(42);
            $pdf->cell(100, 25, 'Yth, Bapak Agny Irsyad', 0, 0, 'L');
            $pdf->SetY($y = $y+5);
            $pdf->SetX(42);
            $pdf->cell(100, 25, 'Yth, Ibu Runti', 0, 0, 'L');
            $pdf->SetFont('Arial', '', $font_size);
            $pdf->SetY($y = $y+10);
            $pdf->SetX($x);
            $pdf->cell(50, 25, 'Menindak lanjuti surat nomor : '.$no_surat_klaim.' tertanggal '.$tgl_surat.', bersama ini perlu kami ', 0, 0, 'L');
            $pdf->SetY($y = $y+5);
            $pdf->SetX($x);
            $pdf->cell(50, 25, 'sampaikan data sebagai berikut:', 0, 0, 'L');
            $pdf->SetY($y = $y+8);
            $pdf->SetX($x);
            $pdf->cell(200, 25, 'Nama Lengkap', 0, 0, 'L');
            $pdf->SetX(65);
            $pdf->cell(1, 25, ':', 0, 0, 'L');
            $pdf->SetX(67);
            $pdf->cell(100, 25, $nm_peserta, 0, 0, 'L');
            $pdf->SetY($y = $y+5);
            $pdf->SetX($x);
            $pdf->cell(200, 25, 'Tanggal Lahir', 0, 0, 'L');
            $pdf->SetX(65);
            $pdf->cell(1, 25, ':', 0, 0, 'L');
            $pdf->SetX(67);
            $pdf->cell(100, 25, $tgl_lahir, 0, 0, 'L');
            $pdf->SetY($y = $y+5);
            $pdf->SetX($x);
            $pdf->cell(200, 25, 'Periode Asuransi', 0, 0, 'L');
            $pdf->SetX(65);
            $pdf->cell(1, 25, ':', 0, 0, 'L');
            $pdf->SetX(67);
            $pdf->cell(100, 25, $periode_asuransi, 0, 0, 'L');
            $pdf->SetY($y = $y+5);
            $pdf->SetX($x);
            $pdf->cell(200, 25, 'Nilai Plafond Kredit', 0, 0, 'L');
            $pdf->SetX(65);
            $pdf->cell(1, 25, ':', 0, 0, 'L');
            $pdf->SetX(67);
            $pdf->cell(100, 25, 'Rp. '.$plafond.',-', 0, 0, 'L');
            $pdf->SetY($y = $y+5);
            $pdf->SetX($x);
            $pdf->cell(200, 25, 'Tanggal Meninggal Dunia', 0, 0, 'L');
            $pdf->SetX(65);
            $pdf->cell(1, 25, ':', 0, 0, 'L');
            $pdf->SetX(67);
            $pdf->cell(100, 25, $tgl_meninggal, 0, 0, 'L');
            $pdf->SetY($y = $y+5);
            $pdf->SetX($x);
            $pdf->cell(200, 25, 'Nilai Tuntutan Klaim', 0, 0, 'L');
            $pdf->SetX(65);
            $pdf->cell(1, 25, ':', 0, 0, 'L');
            $pdf->SetX(67);
            $pdf->cell(100, 25, '', 0, 0, 'L');
            $pdf->SetY($y = $y+10);
            $pdf->SetX($x);
            $pdf->cell(200, 25, 'Besarnya nilai pembayaran klaim Asuransi adalah sebesar Nilai Tuntutan Klaim dimana nilai', 0, 0, 'L');
            $pdf->SetY($y = $y+5);
            $pdf->SetX($x);
            $pdf->cell(200, 25, 'maksimum adalah sebesar Nilai Plafond Kredit, dengan demikian besarnya nilai pembayaran manfaat', 0, 0, 'L');

            $pdf->AddPage();
            $pdf->SetFont('Arial', '', $font_size);
            $y = 25;
            $pdf->SetY($y = $y);
            $pdf->SetX($x);
            $pdf->cell(200, 25, 'Untuk proses pembayaran manfaat Asuransi atas nama akan kami transfer ke rekening', 0, 0, 'L');
            $pdf->SetY($y = $y+5);
            $pdf->SetX($x);
            $pdf->cell(200, 25, 'Untuk proses pembayaran manfaat Asuransi atas nama akan kami transfer ke rekening', 0, 0, 'L');
            $pdf->SetY($y = $y+5);
            $pdf->SetX($x);
            $pdf->cell(200, 25, 'sebagai berikut :', 0, 0, 'L');
            $pdf->SetFont('Arial', 'B', $font_size);
            $pdf->SetFont('Arial', '', $font_size);
            $pdf->SetY($y = $y+10);
            $pdf->SetX($x);
            $pdf->cell(200, 25, 'Demikian informasi ini kami sampaikan, atas perhatian dan kerjasamanya kami mengucapkan terima kasih.', 0, 0, 'L');
            $pdf->SetY($y = $y+10);
            $pdf->SetX($x);
            $pdf->cell(200, 25, 'Hormat kami,', 0, 0, 'L');
            $pdf->SetFont('Arial', 'B', $font_size);
            $pdf->SetY($y = $y+5);
            $pdf->SetX($x);
            $pdf->cell(50, 25, 'PT. Adonai Pialang Asuransi', 0, 0, 'L');
            $pdf->SetX(80);
            $pdf->cell(100, 25, 'Menyetujui', 0, 0, 'L');
            $pdf->SetFont('Arial', 'BU', $font_size);
            $pdf->SetY($y = $y+30);
            $pdf->SetX($x);
            $pdf->cell(200, 25, 'Ing Sriwati', 0, 0, 'L');
            $pdf->SetFont('Arial', 'B', $font_size);
            $pdf->SetY($y = $y+5);
            $pdf->SetX($x);
            $pdf->cell(200, 25, 'Direktur', 0, 0, 'L');
        } else {
            $pdf->SetFont('Arial', '', $font_size);
            $pdf->SetY($y);
            $pdf->SetX($x);
            $pdf->cell(50, 25, 'No.', 0, 0, 'L');
            $pdf->SetX(40);
            $pdf->cell(1, 25, ':', 0, 0, 'L');
            $pdf->SetX(42);
            $pdf->cell(20, 25, $no_surat_klaim, 0, 0, 'L');
            $pdf->SetX(100);
            $pdf->cell(100, 25, 'Bekasi, '.$today, 0, 0, 'R');
            $pdf->SetY($y=$y+5);
            $pdf->SetX($x);
            $pdf->cell(50, 25, 'Lamp.', 0, 0, 'L');
            $pdf->SetX(40);
            $pdf->cell(1, 25, ':', 0, 0, 'L');
            $pdf->SetX(42);
            $pdf->cell(20, 25, 'Terlampir', 0, 0, 'L');
            $pdf->SetY($y = $y+5);
            $pdf->SetX($x);
            $pdf->cell(50, 25, 'Perihal', 0, 0, 'L');
            $pdf->SetX(40);
            $pdf->cell(1, 25, ':', 0, 0, 'L');
            $pdf->SetFont('Arial', 'B', $font_size);
            $pdf->SetX(42);
            $pdf->cell(20, 25, 'Kelengkapan Dokumen Klaim a.n. : '.$nm_peserta.'', 0, 0, 'L');
            $pdf->SetFont('Arial', 'B', $font_size);
            $pdf->SetY($y = $y+10);
            $pdf->SetX($x);
            $pdf->cell(50, 25, 'PT. Bank Bukopin Tbk, Kredit Mikro', 0, 0, 'L');
            $pdf->SetY($y = $y+5);
            $pdf->SetX($x);
            $pdf->cell(50, 25, 'CABANG BOGOR', 0, 0, 'L');
            $pdf->SetFont('Arial', '', $font_size);
            $pdf->SetY($y = $y+5);
            $pdf->SetX($x);
            $pdf->cell(50, 25, 'Jl. Raya Kedung Halang No.24 (Samping Rumah makan Saiyo)', 0, 0, 'L');
            $pdf->SetY($y = $y+5);
            $pdf->SetX($x);
            $pdf->cell(50, 25, 'Bogor', 0, 0, 'L');
            $pdf->SetFont('Arial', 'B', $font_size);
            $pdf->SetY($y = $y+10);
            $pdf->SetX($x);
            $pdf->cell(50, 25, 'UP', 0, 0, 'L');
            $pdf->SetX(40);
            $pdf->cell(1, 25, ':', 0, 0, 'L');
            $pdf->SetX(42);
            $pdf->cell(100, 25, 'Yth, Bapak/Ibu Manager Pelayanan dan Operasional', 0, 0, 'L');
            $pdf->SetFont('Arial', '', $font_size);
            $pdf->SetY($y = $y+10);
            $pdf->SetX($x);
            $pdf->cell(50, 25, 'Dengan Hormat,', 0, 0, 'L');
            $pdf->SetY($y = $y+10);
            $pdf->SetX($x);
            $pdf->cell(200, 25, 'Pertama-tama kami mengucapkan terima kasih atas kerjasama yang telah terjalin dengan sangat', 0, 0, 'L');
            $pdf->SetY($y = $y+5);
            $pdf->SetX($x);
            $pdf->cell(200, 25, 'baik selama ini antara PT. Bank Bukopin, Tbk dan PT. Adonai Pialang Asuransi.', 0, 0, 'L');
            $pdf->SetY($y = $y+10);
            $pdf->SetX($x);
            $pdf->cell(200, 25, 'Sehubungan dengan dokumen klaim yang kami terima a.n. '.$nm_peserta.', kami sampaikan kekurangan', 0, 0, 'L');
            $pdf->SetY($y = $y+5);
            $pdf->SetX($x);
            $pdf->cell(200, 25, 'dokumen klaim yang terdiri dari :', 0, 0, 'L');
            $pdf->SetY($y = $y+5);
            $no = 1;
            while ($qdockelengkapan_row = mysql_fetch_array($qdockelengkapan)) {
                $pdf->SetY($y = $y+5);
                $pdf->SetX(20);
                $pdf->cell(3, 25, $no.'.', 0, 0, 'L');
                $pdf->SetX(26);
                $pdf->cell(200, 25, $qdockelengkapan_row['nama_dok'].' - '.$qdockelengkapan_row['ket_dokumen'], 0, 0, 'L');
                $no++;
            }
            $pdf->SetY($y = $y+10);
            $pdf->SetX($x);
            $pdf->cell(129, 25, 'Mohon agar kelengkapan dokumen klaim tersebut dikirimkan kepada kami', 0, 0, 'L');
            $pdf->SetFont('Arial', 'BI', $font_size);
            $pdf->cell(100, 25, 'sebelum tanggal '.$tgl_duedate.',', 0, 0, 'L');
            $pdf->SetFont('Arial', '', $font_size);
            $pdf->SetY($y = $y+5);
            $pdf->SetX($x);
            $pdf->cell(200, 25, 'apabila dokumen kami terima setelah tanggal tersebut klaim ini tidak dapat ditindaklanjuti karena sudah', 0, 0, 'L');
            $pdf->SetY($y = $y+5);
            $pdf->SetX($x);
            $pdf->cell(200, 25, 'melewati batas waktu yang ditentukan.', 0, 0, 'L');
            $pdf->SetY($y = $y+10);
            $pdf->SetX($x);
            $pdf->cell(200, 25, 'Demikian informasi ini kami sampaikan, atas perhatian dan kerjasamanya kami mengucapkan terima kasih.', 0, 0, 'L');
            $pdf->SetY($y = $y+10);
            $pdf->SetX($x);
            $pdf->cell(200, 25, 'Hormat kami,', 0, 0, 'L');
            $pdf->SetFont('Arial', 'B', $font_size);
            $pdf->SetY($y = $y+5);
            $pdf->SetX($x);
            $pdf->cell(200, 25, 'PT. Adonai Pialang Asuransi', 0, 0, 'L');
            $pdf->SetFont('Arial', 'BU', $font_size);
            $pdf->SetY($y = $y+30);
            $pdf->SetX($x);
            $pdf->cell(200, 25, 'Friska Trioria', 0, 0, 'L');
            $pdf->SetFont('Arial', 'B', $font_size);
            $pdf->SetY($y = $y+5);
            $pdf->SetX($x);
            $pdf->cell(200, 25, 'Claim Head', 0, 0, 'L');
        }
        $pdf->Output("Pengajuan Klaim ".date("d-m-Y").".pdf", "I");
    break;
    case "klaim_investigasi":

        function HeaderingExcel($filename)
        {
            header("Content-type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=$filename");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
            header("Pragma: public");
        }
        HeaderingExcel('DATA INVESTIGASI KLAIM.xls');
        $workbook = new Workbook("");
        $worksheet1 =& $workbook->add_worksheet('INVESTIGASI KLAIM');
        $format =& $workbook->add_format();
        $format->setAlign('vcenter');	$format->setAlign('center');	$format->setColor('white');	$format->setBold();	$format->setPattern();	$format->setFgColor('green');
        $fjudul =& $workbook->add_format();	$fjudul->setAlign('vcenter');	$fjudul->setAlign('center');	$fjudul->setBold();

        $worksheet1->setMerge(0, 0, 0, 7);	$worksheet1->writeString(0, 0, "REKAP INVESTIGASI KLAIM", $fjudul, 1);
        if ($_REQUEST['tgl1']!=="") {
            $worksheet1->setMerge(1, 0, 1, 7);
            $worksheet1->writeString(1, 0, "TANGGAL APPROVED INVESTIGASI "._convertDate($_REQUEST['tgl1'])." s/d "._convertDate($_REQUEST['tgl2'])."", $fjudul);
        }

        if ($_REQUEST['tgl3']!=="") {
            $worksheet1->setMerge(2, 0, 2, 7);
            $worksheet1->writeString(2, 0, "TANGGAL APPROVED MEDICAL "._convertDate($_REQUEST['tgl3'])." s/d "._convertDate($_REQUEST['tgl4'])."", $fjudul);
        }

        $baris = 5;
        $sql="";
        if ($_REQUEST['ridp']) {
            $empat = 'AND fu_ajk_peserta.id_peserta LIKE "%' . $_REQUEST['ridp'] . '%"';
        }
        if ($_REQUEST['rnama']) {
            $tiga = 'AND fu_ajk_peserta.nama LIKE "%'.$_REQUEST['rnama'].'%"';
        }

        $tgl_inv='';
        if ($_REQUEST['tgl1']!=="") {
            $date1=$_REQUEST['tgl1'];
            $date2=$_REQUEST['tgl2'];
            $tgl_inv='and date(fu_ajk_klaim.tgl_app_investigasi) between "'.$date1.'" and "'.$date2.'"';
        }

        $tgl_med='';
        if ($_REQUEST['tgl3']!=="") {
            $date3=$_REQUEST['tgl3'];
            $date4=$_REQUEST['tgl4'];
            $tgl_med='and date(fu_ajk_klaim.tgl_app_opinimedis) between "'.$date3.'" and "'.$date4.'"';
        }

        $kucing = mysql_query('SELECT fu_ajk_asuransi.`name` AS asuransi,
											fu_ajk_cn.id,
											fu_ajk_klaim.id as id_klaim,
											fu_ajk_klaim.no_urut_klaim,
											fu_ajk_cn.id_cost,
											fu_ajk_cn.id_cn,
											fu_ajk_dn.dn_kode,
											fu_ajk_peserta.id_peserta,
											fu_ajk_peserta.nama,
											fu_ajk_peserta.tgl_lahir,
											fu_ajk_peserta.usia,
											fu_ajk_peserta.kredit_tgl,
											IF(fu_ajk_peserta.type_data="SPK", fu_ajk_peserta.kredit_tenor * 12, fu_ajk_peserta.kredit_tenor) AS tenor,
											fu_ajk_peserta.kredit_akhir,
											fu_ajk_peserta.kredit_jumlah,
											fu_ajk_cn.tgl_claim,
											fu_ajk_cn.premi,
											fu_ajk_cn.confirm_claim,
											fu_ajk_cn.total_claim,
											fu_ajk_cn.tuntutan_klaim,
											fu_ajk_cn.tgl_byr_claim,
											fu_ajk_polis.nmproduk,
											fu_ajk_peserta.cabang,
											fu_ajk_peserta.totalpremi,
											fu_ajk_klaim.tgl_kirim_dokumen,
											fu_ajk_cn.tgl_bayar_asuransi,
											fu_ajk_klaim.tgl_app_investigasi,
											fu_ajk_klaim.tgl_app_opinimedis,
											fu_ajk_cn.approve_date as input_date,
											fu_ajk_cn.approve_date,
											fu_ajk_klaim.tempat_meninggal,
											fu_ajk_klaim.sebab_meninggal,
											fu_ajk_klaim.kategori_klaim,
											fu_ajk_klaim.riwayat_penyakit,
											fu_ajk_klaim.kronologi,
											fu_ajk_klaim.preexisting_cond,
											fu_ajk_klaim.diagnosa,
											fu_ajk_klaim.ic_diagnosis,
											fu_ajk_klaim.ket_dokter,
											fu_ajk_klaim.hasil_investigasi,
											fu_ajk_namapenyakit.namapenyakit,
											fu_ajk_klaim_status.status_klaim
											FROM
											fu_ajk_cn
											INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
											INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
											INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
											INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta AND fu_ajk_cn.id = fu_ajk_peserta.id_klaim
											LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
											LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim.id_klaim_status = fu_ajk_klaim_status.id

											LEFT JOIN fu_ajk_namapenyakit ON fu_ajk_klaim.sebab_meninggal = fu_ajk_namapenyakit.id
											WHERE
											fu_ajk_cn.type_claim = "Death" AND fu_ajk_cn.confirm_claim <> "Pending" AND fu_ajk_cn.del IS NULL
											and fu_ajk_klaim.investigasi="Y"
											'.$tgl_inv.' '.$tgl_med.'
											'.$tiga.' '.$empat.'
											ORDER BY fu_ajk_cn.id DESC ');

        $worksheet1->setRow(4, 15);
        $worksheet1->setColumn(4, 0, 5);	$worksheet1->writeString(4, 0, "NO", $format);
        $worksheet1->setColumn(4, 1, 15);	$worksheet1->writeString(4, 1, "TGL LAPOR", $format);
        $worksheet1->setColumn(4, 2, 15);	$worksheet1->writeString(4, 2, "NO URUT", $format);
        $worksheet1->setColumn(4, 3, 15);	$worksheet1->writeString(4, 3, "ASURANSI", $format);
        $worksheet1->setColumn(4, 4, 15);	$worksheet1->writeString(4, 4, "ID PESERTA", $format);
        $worksheet1->setColumn(4, 5, 15);	$worksheet1->writeString(4, 5, "NAMA DEBITUR", $format);
        $worksheet1->setColumn(4, 6, 15);	$worksheet1->writeString(4, 6, "PRODUK", $format);
        $worksheet1->setColumn(4, 7, 15);	$worksheet1->writeString(4, 7, "CABANG", $format);
        $worksheet1->setColumn(4, 8, 15);	$worksheet1->writeString(4, 8, "AWAL KREDIT", $format);
        $worksheet1->setColumn(4, 9, 15);	$worksheet1->writeString(4, 9, "AKHIR KREDIT", $format);
        $worksheet1->setColumn(4, 10, 15);	$worksheet1->writeString(4, 10, "DOL", $format);
        $worksheet1->setColumn(4, 11, 15);	$worksheet1->writeString(4, 11, "TGL APPROVE", $format);
        $worksheet1->setColumn(4, 12, 15);	$worksheet1->writeString(4, 12, "TENOR", $format);
        $worksheet1->setColumn(4, 13, 15);	$worksheet1->writeString(4, 13, "PLAFOND", $format);
        $worksheet1->setColumn(4, 14, 15);	$worksheet1->writeString(4, 14, "TUNTUTAN KLAIM", $format);
        $worksheet1->setColumn(4, 15, 15);	$worksheet1->writeString(4, 15, "TGL APP INVESTIGASI", $format);
        $worksheet1->setColumn(4, 16, 15);	$worksheet1->writeString(4, 16, "TGL APP MEDICAL", $format);
        $worksheet1->setColumn(4, 17, 15);	$worksheet1->writeString(4, 17, "TEMPAT MENINGGAL", $format);
        $worksheet1->setColumn(4, 18, 15);	$worksheet1->writeString(4, 18, "KATEGORI KLAIM", $format);
        $worksheet1->setColumn(4, 19, 15);	$worksheet1->writeString(4, 19, "RIWAYAT PENYAKIT", $format);
        $worksheet1->setColumn(4, 20, 15);	$worksheet1->writeString(4, 20, "KRONOLOGI", $format);
        $worksheet1->setColumn(4, 21, 15);	$worksheet1->writeString(4, 21, "PREEXISTING CONDITION", $format);
        $worksheet1->setColumn(4, 22, 15);	$worksheet1->writeString(4, 22, "DIAGNOSA", $format);
        $worksheet1->setColumn(4, 23, 15);	$worksheet1->writeString(4, 23, "IC DIAGNOSA", $format);
        $worksheet1->setColumn(4, 24, 15);	$worksheet1->writeString(4, 24, "KET DOKTER", $format);
        $worksheet1->setColumn(4, 25, 15);	$worksheet1->writeString(4, 25, "HASIL INVESTIGASI", $format);
        $worksheet1->setColumn(4, 26, 15);	$worksheet1->writeString(4, 26, "SEBAB MENINGGAL", $format);


        while ($jangkrik = mysql_fetch_array($kucing)) {
            $worksheet1->writeNumber($baris, 0, ++$no);
            $worksheet1->writeString($baris, 1, $jangkrik['input_date']);
            $worksheet1->writeString($baris, 2, $jangkrik['no_urut_klaim']);
            $worksheet1->writeString($baris, 3, $jangkrik['asuransi']);
            $worksheet1->writeString($baris, 4, $jangkrik['id_peserta']);
            $worksheet1->writeString($baris, 5, $jangkrik['nama']);
            $worksheet1->writeString($baris, 6, $jangkrik['nmproduk']);
            $worksheet1->writeString($baris, 7, $jangkrik['cabang']);
            $worksheet1->writeString($baris, 8, $jangkrik['kredit_tgl']);
            $worksheet1->writeString($baris, 9, $jangkrik['kredit_akhir']);
            $worksheet1->writeString($baris, 10, $jangkrik['tgl_claim']);
            $worksheet1->writeString($baris, 11, $jangkrik['approve_date']);
            $worksheet1->writeNumber($baris, 12, $jangkrik['tenor']);
            $worksheet1->writeNumber($baris, 13, $jangkrik['totalpremi']);
            $worksheet1->writeNumber($baris, 14, $jangkrik['tuntutan_klaim']);
            $worksheet1->writeString($baris, 15, $jangkrik['tgl_app_investigasi']);
            $worksheet1->writeString($baris, 16, $jangkrik['tgl_app_opinimedis']);
            $worksheet1->writeString($baris, 17, $jangkrik['tempat_meninggal']);
            $worksheet1->writeString($baris, 18, $jangkrik['kategori_klaim']);
            $worksheet1->writeString($baris, 19, $jangkrik['riwayat_penyakit']);
            $worksheet1->writeString($baris, 20, $jangkrik['kronologi']);
            $worksheet1->writeString($baris, 21, $jangkrik['preexisting_cond']);
            $worksheet1->writeString($baris, 22, $jangkrik['diagnosa']);
            $worksheet1->writeString($baris, 23, $jangkrik['ic_diagnosis']);
            $worksheet1->writeString($baris, 24, $jangkrik['ket_dokter']);
            $worksheet1->writeString($baris, 25, $jangkrik['hasil_investigasi']);
            $worksheet1->writeString($baris, 26, $jangkrik['namapenyakit']);
            $baris++;
        }

        $workbook->close();
        ;

    break;

    case "kadaluarsa":

        function bulan_convert($tanggal)
        {
            $dateku=str_replace("01-01-1970", "", date('d-m-Y', strtotime($tanggal)));
            if ($dateku!=="") {
                $tgl=explode("-", $dateku);

                if ($tgl['1']=='01') {
                    $ls_namabulan =  'Jan';
                } elseif ($tgl['1']=='02') {
                    $ls_namabulan =  'Feb';
                } elseif ($tgl['1']=='03') {
                    $ls_namabulan =  'Mar';
                } elseif ($tgl['1']=='04') {
                    $ls_namabulan =  'Apr';
                } elseif ($tgl['1']=='05') {
                    $ls_namabulan =  'Mei';
                } elseif ($tgl['1']=='06') {
                    $ls_namabulan =  'Jun';
                } elseif ($tgl['1']=='07') {
                    $ls_namabulan =  'Jul';
                } elseif ($tgl['1']=='08') {
                    $ls_namabulan =  'Agt';
                } elseif ($tgl['1']=='09') {
                    $ls_namabulan =  'Sep';
                } elseif ($tgl['1']=='10') {
                    $ls_namabulan =  'Okt';
                } elseif ($tgl['1']=='11') {
                    $ls_namabulan =  'Nov';
                } elseif ($tgl['1']=='12') {
                    $ls_namabulan =  'Des';
                }

                return $tgl['0'].'-'.$ls_namabulan.'-'.$tgl['2'];
            }
        }
        function HeaderingExcel($filename)
        {
            header("Content-type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=$filename");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
            header("Pragma: public");
        }
        HeaderingExcel('DATA_KLAIM_PRAKADALUARSA.xls');
        $workbook = new Workbook("");
        $worksheet1 =& $workbook->add_worksheet('DATA KLAIM PRA KADALUARSA');
        $format =& $workbook->add_format();
        $format->setAlign('vcenter');	$format->setAlign('center');	$format->setColor('white');	$format->setBold();	$format->setPattern();	$format->setFgColor('green');
        $fjudul =& $workbook->add_format();	$fjudul->setAlign('vcenter');	$fjudul->setAlign('center');	$fjudul->setBold();

        $worksheet1->setMerge(0, 0, 0, 23);	$worksheet1->writeString(0, 0, "DATA KLAIM PRA KADALUARSA PER TANGGAL ".date("d M Y"), $fjudul, 1);


        $worksheet1->setRow(4, 15);
        $worksheet1->setColumn(4, 0, 5);	$worksheet1->writeString(4, 0, "No", $format);
        $worksheet1->setColumn(4, 1, 5);	$worksheet1->writeString(4, 1, "Nomor Urut", $format);
        $worksheet1->setColumn(4, 2, 5);	$worksheet1->writeString(4, 2, "Bukopin Cabang", $format);
        $worksheet1->setColumn(4, 3, 5);	$worksheet1->writeString(4, 3, "Cover Asuransi", $format);
        $worksheet1->setColumn(4, 4, 5);	$worksheet1->writeString(4, 4, "Kategori", $format);
        $worksheet1->setColumn(4, 5, 5);	$worksheet1->writeString(4, 5, "Produk", $format);
        $worksheet1->setColumn(4, 6, 5);	$worksheet1->writeString(4, 6, "ID Peserta", $format);
        $worksheet1->setColumn(4, 7, 5);	$worksheet1->writeString(4, 7, "Nama Debitur", $format);
        $worksheet1->setColumn(4, 8, 5);	$worksheet1->writeString(4, 8, "Tgl Lahir", $format);
        $worksheet1->setColumn(4, 9, 5);	$worksheet1->writeString(4, 9, "Usia", $format);
        $worksheet1->setColumn(4, 10, 5);	$worksheet1->writeString(4, 10, "Plafond Kredit ", $format);
        $worksheet1->setColumn(4, 11, 5);	$worksheet1->writeString(4, 11, "Tuntutan Klaim ", $format);
        $worksheet1->setColumn(4, 12, 5);	$worksheet1->writeString(4, 12, "Tgl Akad", $format);
        $worksheet1->setColumn(4, 13, 5);	$worksheet1->writeString(4, 13, "J.Wkt (Th.)", $format);
        $worksheet1->setColumn(4, 14, 5);	$worksheet1->writeString(4, 14, "DOL", $format);
        $worksheet1->setColumn(4, 15, 5);	$worksheet1->writeString(4, 15, "Akad s/d DOL (hari)", $format);
        $worksheet1->setColumn(4, 16, 5);	$worksheet1->writeString(4, 16, "Tgl. Terima Laporan", $format);
        $worksheet1->setColumn(4, 17, 5);	$worksheet1->writeString(4, 17, "Kelengkapan Dokumen Klaim", $format);
        $worksheet1->setColumn(4, 18, 5);	$worksheet1->writeString(4, 18, "Tgl Lapor Klaim", $format);
        $worksheet1->setColumn(4, 19, 5);	$worksheet1->writeString(4, 19, "Tanggal Status Lengkap", $format);
        $worksheet1->setColumn(4, 20, 5);	$worksheet1->writeString(4, 20, "Status Klaim", $format);
        $worksheet1->setColumn(4, 21, 5);	$worksheet1->writeString(4, 21, "Asuransi Bayar (Rp.)", $format);
        $worksheet1->setColumn(4, 22, 5);	$worksheet1->writeString(4, 22, "Tgl Bayar Dari Asuransi", $format);
        $worksheet1->setColumn(4, 23, 5);	$worksheet1->writeString(4, 23, "Bayar Ke Bank (Rp.)", $format);
        $worksheet1->setColumn(4, 24, 5);	$worksheet1->writeString(4, 24, "Tgl Bayar Ke Client", $format);
        $worksheet1->setColumn(4, 25, 5);	$worksheet1->writeString(4, 25, "Selisih Bayar (Rp.)", $format);
        $worksheet1->setColumn(4, 26, 5);	$worksheet1->writeString(4, 26, "Kol", $format);
        $worksheet1->setColumn(4, 27, 5);	$worksheet1->writeString(4, 27, "Kadalaursa (Hari)", $format);

        $baris = 5;
        $kucing = mysql_query("SELECT
								CONCAT(DATE_FORMAT(fu_ajk_cn.approve_date,'%Y%m'),'/',fu_ajk_klaim.id) AS klaim_id,
								fu_ajk_klaim.no_urut_klaim,
								fu_ajk_cn.id_cabang,
								fu_ajk_grupproduk.nmproduk AS mitra,
								fu_ajk_asuransi.`name`,
								fu_ajk_polis.nmproduk,
								IF(fu_ajk_polis.`typeproduk`='NON SPK','Percepatan','Reguler') AS kategori,
								fu_ajk_peserta.id_peserta,
								fu_ajk_peserta.nama,
								fu_ajk_peserta.tgl_lahir,
								fu_ajk_peserta.usia,
								fu_ajk_peserta.kredit_jumlah,
								fu_ajk_cn.total_claim,
								fu_ajk_cn.tuntutan_klaim,
								fu_ajk_peserta.kredit_tgl,
								ROUND(IF(fu_ajk_peserta.type_data='SPK',fu_ajk_peserta.kredit_tenor*12,fu_ajk_peserta.kredit_tenor)/12) AS kredit_tenor,
								fu_ajk_klaim.tgl_klaim AS dol,
								DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl) AS akad_dol,
								IF(fu_ajk_klaim.tgl_document='0000-00-00',NULL,fu_ajk_klaim.tgl_document) AS tgl_document,
								DATEDIFF(fu_ajk_cn.approve_date,fu_ajk_klaim.tgl_document) AS lama_terima_laporan,
								'' AS tgl_update_klaim,
								IF(fu_ajk_klaim.tgl_lapor_klaim='0000-00-00',NULL,fu_ajk_klaim.tgl_lapor_klaim) AS tgl_lapor_klaim,
								fu_ajk_cn.keterangan AS kelengkapan_dokumen,
								IF(fu_ajk_klaim.tgl_document_lengkap='0000-00-00',NULL,fu_ajk_klaim.tgl_document_lengkap) AS tgl_document_lengkap,
								IF(fu_ajk_polis.`typeproduk`='NON SPK',DATE_ADD(fu_ajk_klaim.tgl_document_lengkap,INTERVAL 28 DAY),DATE_ADD(fu_ajk_klaim.tgl_document_lengkap,INTERVAL 14 DAY)) as due_date,
								IF(fu_ajk_klaim.tgl_kirim_dokumen='0000-00-00',NULL,fu_ajk_klaim.tgl_kirim_dokumen) AS tgl_kirim_dokumen,
								CURRENT_DATE() AS today,
								IF(fu_ajk_klaim.tgl_kirim_dokumen<>'0000-00-00' OR fu_ajk_klaim.tgl_kirim_dokumen IS NOT NULL,DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_kirim_dokumen),'') AS status_release,
								/*fu_ajk_spak.ext_premi*/ '' AS EM,
								/*fu_ajk_spak.ket_ext*/ '' AS keterangan_EM,
								IF(fu_ajk_klaim.tgl_investigasi='0000-00-00',NULL,fu_ajk_klaim.tgl_investigasi) AS tgl_investigasi,
								fu_ajk_klaim.diagnosa AS hasil_investigasi,
								fu_ajk_namapenyakit.namapenyakit AS penyebab_meinggal,
								fu_ajk_cn.policy_liability AS polis_liability,
								fu_ajk_pembayaran_status.pembayaran_status,
								fu_ajk_klaim_status.status_klaim,
								if(`id_klaim_status`=6,'Ditolak',
								if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
								'Dokumen Belum Lengkap')) AS keterangan,
								'' AS keterangan_asuransi,
								fu_ajk_cn.total_bayar_asuransi,
								'' AS ref_bayar_asuransi,
								fu_ajk_cn.tgl_bayar_asuransi,
								'' AS nilai_pengajuan_keuangan,
								fu_ajk_cn.total_claim  AS bayar_ke_bank,
								'' AS ref_pembayaran_ke_bank,
								fu_ajk_cn.tgl_byr_claim AS tgl_bayar_ke_client,
								fu_ajk_cn.total_bayar_asuransi-fu_ajk_cn.total_claim AS selisih,
								IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',
								IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
								IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 AND DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
								IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
								,
								IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
								IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
								IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol,


								(150)-if(DATEDIFF(fu_ajk_cn.tgl_claim,fu_ajk_klaim.tgl_document_lengkap) is null,
								DATEDIFF(current_date(),fu_ajk_cn.tgl_claim),
								DATEDIFF(fu_ajk_klaim.tgl_document_lengkap,fu_ajk_cn.tgl_claim)) as kadaluarsa1,
								if(DATEDIFF(fu_ajk_cn.tgl_claim,fu_ajk_klaim.tgl_document_lengkap) is null,
								DATEDIFF(current_date(),fu_ajk_cn.tgl_claim),
								DATEDIFF(fu_ajk_klaim.tgl_document_lengkap,fu_ajk_cn.tgl_claim)) as kadaluarsa_hari
								FROM
								fu_ajk_cn
								INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
								INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
								INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
								INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
								LEFT JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
								LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id=fu_ajk_klaim.id_klaim_status
								LEFT JOIN fu_ajk_grupproduk ON fu_ajk_grupproduk.id=fu_ajk_peserta.nama_mitra
								/*LEFT JOIN fu_ajk_spak ON fu_ajk_peserta.spaj=fu_ajk_spak.spak*/
								LEFT JOIN fu_ajk_namapenyakit ON fu_ajk_namapenyakit.id=fu_ajk_klaim.sebab_meninggal
								LEFT JOIN fu_ajk_pembayaran_status ON fu_ajk_pembayaran_status.id=fu_ajk_cn.status_bayar
								where  fu_ajk_cn.del is null  and fu_ajk_cn.type_claim='Death'
								and fu_ajk_cn.confirm_claim !='Pending'
								and
								(if(DATEDIFF(fu_ajk_cn.tgl_claim,fu_ajk_klaim.tgl_document_lengkap) is null,
								DATEDIFF(current_date(),fu_ajk_cn.tgl_claim),160)	 between 120 and 150 )
								and if(fu_ajk_cn.tgl_bayar_asuransi is null or fu_ajk_cn.tgl_bayar_asuransi='0000-00-00','UNPAID' ,'PAID')='UNPAID'

								order by

								(if(DATEDIFF(fu_ajk_cn.tgl_claim,fu_ajk_klaim.tgl_document_lengkap) is null,
								DATEDIFF(current_date(),fu_ajk_cn.tgl_claim),160)	 between 120 and 150 )
								and if(fu_ajk_cn.tgl_bayar_asuransi is null or fu_ajk_cn.tgl_bayar_asuransi='0000-00-00','UNPAID' ,'PAID')='UNPAID'
								asc");
        while ($kucingkawin = mysql_fetch_array($kucing)) {
            $worksheet1->writeNumber($baris, 0, ++$no);
            $worksheet1->writeString($baris, 1, $kucingkawin['no_urut_klaim']);
            $worksheet1->writeString($baris, 2, $kucingkawin['id_cabang']);
            $worksheet1->writeString($baris, 3, $kucingkawin['name']);
            $worksheet1->writeString($baris, 4, $kucingkawin['kategori']);
            $worksheet1->writeString($baris, 5, $kucingkawin['nmproduk']);
            $worksheet1->writeString($baris, 6, $kucingkawin['id_peserta']);
            $worksheet1->writeString($baris, 7, $kucingkawin['nama']);
            $worksheet1->writeString($baris, 8, $kucingkawin['tgl_lahir']);
            $worksheet1->writeNumber($baris, 9, $kucingkawin['usia']);
            $worksheet1->writeNumber($baris, 10, $kucingkawin['kredit_jumlah']);
            $worksheet1->writeNumber($baris, 11, $kucingkawin['tuntutan_klaim']);
            $worksheet1->writeString($baris, 12, $kucingkawin['kredit_tgl']);
            $worksheet1->writeNumber($baris, 13, $kucingkawin['kredit_tenor']);
            $worksheet1->writeString($baris, 14, $kucingkawin['dol']);
            $worksheet1->writeString($baris, 15, $kucingkawin['akad_dol']);
            $worksheet1->writeString($baris, 16, $kucingkawin['tgl_lapor_klaim']);
            $worksheet1->writeString($baris, 17, $kucingkawin['kelengkapan_dokumen']);
            $worksheet1->writeString($baris, 18, $kucingkawin['input_date']);
            $worksheet1->writeString($baris, 19, $kucingkawin['tgl_document_lengkap']);
            $worksheet1->writeString($baris, 20, $kucingkawin['status_klaim']);
            $worksheet1->writeNumber($baris, 21, $kucingkawin['total_bayar_asuransi']);
            $worksheet1->writeString($baris, 22, $kucingkawin['tgl_bayar_asuransi']);
            $worksheet1->writeNumber($baris, 23, $kucingkawin['bayar_ke_bank']);
            $worksheet1->writeString($baris, 24, $kucingkawin['tgl_bayar_ke_client']);
            $worksheet1->writeString($baris, 25, $kucingkawin['selisih']);
            $worksheet1->writeNumber($baris, 26, $kucingkawin['kol']);
            $worksheet1->writeNumber($baris, 27, $kucingkawin['kadaluarsa1'].' hari sebelum kadaluarsa');

            $baris++;
        }

        $workbook->close();
        ;
        break;
    default:
        ;
} // switch
