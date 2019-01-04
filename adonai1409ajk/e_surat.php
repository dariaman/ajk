<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ----------------------------------------------------------------------------------
error_reporting(0);
require('fpdf.php');
// require('wordwrap.php');
require('../includes/code39.php');
include_once "../includes/Spreadsheet/Excel/Writer.php";
require_once('includes/metPHPXLS/Worksheet.php');
require_once('includes/metPHPXLS/Workbook.php');
include_once '../includes/smtp_classes/library.php'; // include the library file
include_once '../includes/smtp_classes/class.phpmailer.php'; // include the class name

$futoday  = date("d M Y");
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

function datediff($time1, $time2, $precision = 6)
{
    // If not numeric then convert texts to unix timestamps
    if (!is_int($time1)) {
        $time1 = strtotime($time1);
    }
    if (!is_int($time2)) {
        $time2 = strtotime($time2);
    }

    // If time1 is bigger than time2
    // Then swap time1 and time2
    if ($time1 > $time2) {
        $ttime = $time1;
        $time1 = $time2;
        $time2 = $ttime;
    }

    // Set up intervals and diffs arrays
    $intervals = array('year','month','day','hour','minute','second');
    $diffs = array();

    // Loop thru all intervals
    foreach ($intervals as $interval) {
        // Create temp time from time1 and interval
        $ttime = strtotime('+1 ' . $interval, $time1);
        // Set initial values
        $add = 1;
        $looped = 0;
        // Loop until temp time is smaller than time2
        while ($time2 >= $ttime) {
            // Create new temp time from time1 and interval
            $add++;
            $ttime = strtotime("+" . $add . " " . $interval, $time1);
            $looped++;
        }

        $time1 = strtotime("+" . $looped . " " . $interval, $time1);
        $diffs[$interval] = $looped;
    }

    $count = 0;
    $times = array();
    // Loop thru all diffs
    foreach ($diffs as $interval => $value) {
        // Break if we have needed precission
        if ($count >= $precision) {
            break;
        }
        // Add value and interval
        // if value is bigger than 0
        if ($value >= 0) {
            // Add s if value is not 1
            if ($value != 1) {
                $interval .= "s";
            }
            // Add value and interval to times array
            //$times[] = $value . " " . $interval;	// DEFAULT
            $times[] = $value;
            $count++;
        }
    }

    // Return string with times
    //return implode(", ", $times);	// DEFAULT
    return implode(",", $times);
}

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
function duitterbilang($value)
{
    $orro = number_format($value, 0, ',', '');
    return $orro;
}

switch ($_REQUEST['er']) {
  case "eL_pengajuanklaim":
      $pdf=new FPDF('P', 'mm', 'A4');
      $pdf->Open();
      $pdf->AliasNbPages();


      $no_surat_klaim = $_REQUEST['nsk'];
      $no_surat_keterangan = $_REQUEST['sk'];
      $no_surat_pembayaran = $_REQUEST['sp'];
      $id_klaim = $_REQUEST['id'];
      $no_tanda_terima = $_REQUEST['ntt'];
      $no_pengajuan_medical = $_REQUEST['pm'];
      $no_info_medical = $_REQUEST['im'];
      $tgl_tt = viewBulanIndo($_REQUEST['tgltt']);
      $today = viewBulanIndo(date('Y-m-d'));
      $qpeserta = mysql_fetch_array(mysql_query("select *,(select nmproduk from fu_ajk_polis where fu_ajk_polis.id = fu_ajk_peserta.id_polis)as nm_produk from fu_ajk_peserta where id_klaim = '".$id_klaim."'"));
      $qcn = mysql_fetch_array(mysql_query("select * from fu_ajk_cn where id = '".$id_klaim."'"));
      $qklaim = mysql_fetch_array(mysql_query("select *,DATE_ADD(tgl_klaim,INTERVAL 150 DAY)as due_date from fu_ajk_klaim where id_cn = '".$id_klaim."'"));
      $qmitra = mysql_fetch_array(mysql_query("select * from fu_ajk_grupproduk where id = ".$qpeserta['nama_mitra']));
      $qasuransi = mysql_fetch_array(mysql_query("select fu_ajk_asuransi.id,
																fu_ajk_asuransi.name,
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
														WHERE id_klaim = ".$id_klaim." AND dok_kirim = 'T' and fu_ajk_klaim_doc.del is NULL  order by fu_ajk_dokumenklaim.urut asc");
      $qdocklaim2 = mysql_query("SELECT fu_ajk_dokumenklaim.nama_dok
														FROM fu_ajk_dokumenklaim_bank
														INNER JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim_bank.id_dok = fu_ajk_dokumenklaim.id
														INNER JOIN fu_ajk_klaim_doc ON fu_ajk_klaim_doc.dokumen = fu_ajk_dokumenklaim_bank.id
														WHERE id_klaim = ".$id_klaim." AND dok_kirim = 'T' and fu_ajk_klaim_doc.del is NULL  order by fu_ajk_dokumenklaim.urut asc");
      $qdocklaimkurang = mysql_query("SELECT fu_ajk_dokumenklaim.nama_dok,fu_ajk_dokumenklaim.id
														FROM fu_ajk_dokumenklaim_bank
														INNER JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim_bank.id_dok = fu_ajk_dokumenklaim.id
														INNER JOIN fu_ajk_klaim_doc ON fu_ajk_klaim_doc.dokumen = fu_ajk_dokumenklaim_bank.id
														WHERE id_klaim = ".$id_klaim." AND dok_kirim = 'F' AND fu_ajk_dokumenklaim.id != 15 and fu_ajk_klaim_doc.del is NULL
														order by fu_ajk_dokumenklaim.urut asc");
      $qdockelengkapan = mysql_query("SELECT fu_ajk_dokumenklaim.nama_dok,ket_dokumen
																	FROM fu_ajk_dokumenklaim_bank
																	INNER JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim_bank.id_dok = fu_ajk_dokumenklaim.id
																	INNER JOIN fu_ajk_klaim_doc ON fu_ajk_klaim_doc.dokumen = fu_ajk_dokumenklaim_bank.id
																	WHERE id_klaim = ".$id_klaim." AND fu_ajk_klaim_doc.status != 'close' and fu_ajk_klaim_doc.del is NULL  order by fu_ajk_dokumenklaim.urut asc");

      $qanalisa = mysql_fetch_array(mysql_query("SELECT * FROM fu_ajk_analisa_klaim WHERE id_peserta = '".$qpeserta['id_peserta']."'"));

      $nm_peserta = ucwords(strtolower($qpeserta['nama']));
      $nm_cabang = ucwords(strtolower($qpeserta['cabang']));
      $nm_produk= ucwords(strtolower($qpeserta['nm_produk']));
      $tgl_lahir = viewBulanIndo($qpeserta['tgl_lahir']);
      $tgl_meninggal = viewBulanIndo($qcn['tgl_claim']);
      $periode_asuransi = viewBulanIndo($qpeserta['kredit_tgl']) .' s/d '.viewBulanIndo($qpeserta['kredit_akhir']);
      $tgl_surat = viewBulanIndo($_REQUEST['tgl']);
      $plafond = duit($qpeserta['kredit_jumlah']);
      $nilai_tuntutan_klaim = duit($_REQUEST['teb']);
      $tuntutan_klaim = duit($qcn['tuntutan_klaim']);
      $tgl_terima_document = viewBulanIndo($qklaim['tgl_document']);
      $tgl_duedate = viewBulanIndo($qklaim['due_date']);
      $mitra = $qpeserta['nama_mitra'];
      $tgl_bayar_bank = $qcn['tgl_bayar_claim'];
      $analisabank = $qanalisa['analisa_bank'];
      $analisaasuransi = $qanalisa['analisa_asuransi'];
      /*
      if($qasuransi['id']==5){
          $jabatan_as = '';
      }else{
          $jabatan_as = ' - Wakil Kepala Cabang Jakarta';
      }
      */


      $pdf->AddPage();
      $y = 25;
      $x = 10;
      $font_size = 12;

      if ($mitra == 3) {
          $bank = 'Bank Mandiri';
          $anbank = 'Koperasi Simpan Pinjam Mekarsari';
          $norek = '1660000389692';
          $ttd = 'Manonga Pasaribu SE, MM';
          $jabatan = 'Ketua KSP Mekarsari';

          if ($qpeserta['id_polis'] == 16) {
              $nmmitra = 'PT. Bank Bukopin, Tbk';
          } else {
              $nmmitra = $qmitra['nm_mitra'];
          }
      } else {
          $bank = 'Bank Bukopin Cabang S. Parman';
          $anbank = 'KL. Admk Notaris dan Ass Kredit';
          $norek = '3001054152';
          $ttd = 'Zulfikar Andiko';
          $jabatan = 'Kadiv Managemen Operasional Mikro';
          $nmmitra = $qmitra['nm_mitra'];
      }

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
          $pdf->cell(100, 25, 'Yth, Bapak/Ibu '.$qasuransi['pic'].' '.$jabatan_as, 0, 0, 'L');
          $pdf->SetFont('Arial', '', $font_size);
          $pdf->SetY($y = $y+10);
          $pdf->SetX($x);
          $pdf->cell(50, 25, 'Dengan Hormat,', 0, 0, 'L');
          $pdf->SetY($y = $y+10);
          $pdf->SetX($x);
          $pdf->cell(200, 25, 'Menindaklanjuti laporan klaim awal tanggal '.$tgl_terima_document.' serta merunjuk surat '.$nmmitra, 0, 0, 'L');
          $pdf->SetY($y = $y+5);
          $pdf->SetX($x);
          $pdf->cell(200, 25, 'Nomor : '.$no_surat_keterangan.' tertanggal '.$tgl_surat.'. Bersama ini kami sampaikan pengajuan klaim', 0, 0, 'L');
          $pdf->SetY($y = $y+5);
          $pdf->SetX($x);
          $pdf->cell(200, 25, 'dengan data debitur sebagai berikut:', 0, 0, 'L');
          $pdf->SetFont('Arial', 'B', $font_size);
          $pdf->SetY($y = $y+8);
          $pdf->SetX($x);
          $pdf->cell(200, 25, 'Nama Debitur', 0, 0, 'L');
          $pdf->SetX(65);
          $pdf->cell(1, 25, ':', 0, 0, 'L');
          $pdf->SetX(67);
          $pdf->cell(100, 25, $nm_peserta, 0, 0, 'L');
          $pdf->SetY($y = $y+6);
          $pdf->SetX($x);
          $pdf->cell(200, 25, 'Tanggal Lahir', 0, 0, 'L');
          $pdf->SetX(65);
          $pdf->cell(1, 25, ':', 0, 0, 'L');
          $pdf->SetX(67);
          $pdf->cell(100, 25, $tgl_lahir, 0, 0, 'L');
          $pdf->SetY($y = $y+6);
          $pdf->SetX($x);
          $pdf->cell(200, 25, 'Periode Asuransi', 0, 0, 'L');
          $pdf->SetX(65);
          $pdf->cell(1, 25, ':', 0, 0, 'L');
          $pdf->SetX(67);
          $pdf->cell(100, 25, $periode_asuransi, 0, 0, 'L');
          $pdf->SetY($y = $y+6);
          $pdf->SetX($x);
          $pdf->cell(200, 25, 'Plafond Kredit', 0, 0, 'L');
          $pdf->SetX(65);
          $pdf->cell(1, 25, ':', 0, 0, 'L');
          $pdf->SetX(67);
          $pdf->cell(100, 25, 'Rp. '.$plafond.',-', 0, 0, 'L');
          $pdf->SetY($y = $y+6);
          $pdf->SetX($x);
          $pdf->cell(200, 25, 'Nilai Tuntutan Klaim', 0, 0, 'L');
          $pdf->SetX(65);
          $pdf->cell(1, 25, ':', 0, 0, 'L');
          $pdf->SetX(67);
          $pdf->cell(100, 25, 'Rp. '.$tuntutan_klaim.',-', 0, 0, 'L');
          $pdf->SetY($y = $y+6);
          $pdf->SetX($x);
          $pdf->cell(200, 25, 'Tanggal Meninggal Dunia', 0, 0, 'L');
          $pdf->SetX(65);
          $pdf->cell(1, 25, ':', 0, 0, 'L');
          $pdf->SetX(67);
          $pdf->cell(100, 25, $tgl_meninggal, 0, 0, 'L');
          $pdf->SetY($y = $y+6);
          $pdf->SetX($x);
          $pdf->cell(200, 25, 'Program Asuransi', 0, 0, 'L');
          $pdf->SetX(65);
          $pdf->cell(1, 25, ':', 0, 0, 'L');
          $pdf->SetX(67);
          $pdf->cell(100, 25, $nm_produk, 0, 0, 'L');
          $pdf->SetFont('Arial', '', $font_size);
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

          if ($qklaim['id_klaim_status']==2) {
              $pdf->SetY($y);
              $pdf->SetX($x);
              $pdf->cell(200, 25, 'Kekurangan Dokumen :', 0, 0, 'L');
              $no = 1;
              while ($qdocklaimkurang_row = mysql_fetch_array($qdocklaimkurang)) {
                  if (($qdocklaimkurang_row['id'] == 19 or $qdocklaimkurang_row['id'] == 14) and $qklaim['tempat_meninggal'] == 'Rumah Sakit') {
                      continue;
                  }
                  if ($qpeserta['id_polis'] == '1' or $qpeserta['id_polis'] == '12') {
                      if ($qdocklaimkurang_row['id'] == 17 or $qdocklaimkurang_row['id'] == 18) {
                          continue;
                      }
                  }

                  $pdf->SetY($y = $y+5);
                  $pdf->SetX(20);
                  $pdf->cell(3, 25, $no.'.', 0, 0, 'L');
                  $pdf->SetX(26);
                  $pdf->cell(200, 25, $qdocklaimkurang_row['nama_dok'], 0, 0, 'L');
                  $no++;
              }
              $pdf->SetY($y = $y + 10);
              $pdf->SetY($y);
              $pdf->SetX($x);
              $pdf->cell(200, 25, 'Adapun kekurangan dokumen tersebut akan kami sampaikan setelah kami mendapatkan kelengkapan', 0, 0, 'L');
              $pdf->SetY($y = $y + 5);
              $pdf->SetX($x);
              $pdf->cell(200, 25, 'dokumen dari cabang terkait.', 0, 0, 'L');
              $pdf->SetY($y = $y + 10);
          }


          if ($qpeserta['id_polis'] == 16) {
              $pdf->SetFont('Arial', 'B', 10);
              $pdf->SetY($y);
              $pdf->SetX($x);
              $pdf->cell(200, 25, 'Sehubungan dengan program asuransi dari debitur terkait adalah termasuk dalam program asuransi', 0, 0, 'L');
              $pdf->SetY($y = $y+5);
              $pdf->SetX($x);
              $pdf->cell(200, 25, 'AJK Pensiun Khusus Usia Platinum, dimana penutupan dilakukan secara Free Cover Limit oleh karena', 0, 0, 'L');
              $pdf->SetY($y = $y+5);
              $pdf->SetX($x);
              $pdf->cell(200, 25, 'itu dimohonkan agar pembayaran manfaat asuransi a.n. '.$nm_peserta.' dapat dijadikan prioritas', 0, 0, 'L');
              $pdf->SetY($y = $y+5);
              $pdf->SetX($x);
              $pdf->cell(200, 25, 'dan pembayaran dapat dilakukan dalam waktu dekat.', 0, 0, 'L');
              $pdf->SetFont('Arial', '', $font_size);
              $pdf->SetY($y = $y+10);
          }

          $pdf->SetY($y);
          $pdf->SetX($x);
          $pdf->cell(200, 25, 'Untuk pembayaran klaim tersebut dapat di transfer ke rekening sebagai berikut :', 0, 0, 'L');
          $pdf->SetFont('Arial', 'B', $font_size);
          $pdf->SetY($y = $y+10);
          $pdf->SetX($x+20);
          $pdf->cell(10, 25, 'Bank', 0, 0, 'L');
          $pdf->SetX(85);
          $pdf->cell(200, 25, ':', 0, 0, 'L');
          $pdf->SetX(88);
          $pdf->cell(200, 25, 'Bank bukopin cabang tanah abang jakarta', 0, 0, 'L');
          $pdf->SetY($y = $y+5);
          $pdf->SetX($x+20);
          $pdf->cell(10, 25, 'Nama Pemilik Rekening', 0, 0, 'L');
          $pdf->SetX(85);
          $pdf->cell(200, 25, ':', 0, 0, 'L');
          $pdf->SetX(88);
          $pdf->cell(200, 25, 'PT. Adonai Pialang Asuransi', 0, 0, 'L');
          $pdf->SetY($y = $y+5);
          $pdf->SetX($x+20);
          $pdf->cell(10, 25, 'No. Rekening', 0, 0, 'L');
          $pdf->SetX(85);
          $pdf->cell(200, 25, ':', 0, 0, 'L');
          $pdf->SetX(88);
          $pdf->cell(200, 25, '1000 967 439', 0, 0, 'L');
          $pdf->SetFont('Arial', '', $font_size);
          $pdf->SetY($y = $y+10);
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
          $pdf->cell(200, 25, 'Dessy Puji Astuti', 0, 0, 'L');
          $pdf->SetFont('Arial', 'B', $font_size);
          $pdf->SetY($y = $y+5);
          $pdf->SetX($x);
          $pdf->cell(200, 25, 'Manager Claim', 0, 0, 'L');
          //TANDA TERIMA
          $pdf->AddPage();
          $pdf->SetFont('Arial', '', $font_size);
          $y = 35;
          $pdf->SetY($y = $y+3);
          $pdf->SetX(10);
          $pdf->cell(100, 25, 'Bekasi, '.$tgl_tt, 0, 0, 'L');
          $pdf->SetY($y = $y+8);
          $pdf->SetX(10);
          $pdf->cell(100, 25, 'Ditujukan Kepada : ', 0, 0, 'L');
          $pdf->SetFont('Arial', 'B', $font_size);
          $pdf->SetX(47);
          $pdf->cell(100, 25, 'Yth, Bapak/Ibu '.$qasuransi['pic'], 0, 0, 'L');
          $pdf->SetY($y = $y+5);
          $pdf->SetX(10);
          $pdf->cell(100, 25, $jabatan_as, 0, 0, 'L');
          $pdf->SetY($y = $y+5);
          $pdf->SetX(10);
          $pdf->cell(100, 25, $qasuransi['name'], 0, 0, 'L');
          $pdf->SetY($y = $y+5);
          $pdf->SetX(10);
          $pdf->cell(100, 25, $qasuransi['wilayah'], 0, 0, 'L');
          $pdf->SetFont('Arial', '', $font_size);
          $pdf->SetY($y = $y+5);
          $pdf->SetX(10);
          $pdf->cell(100, 25, $qasuransi['address'], 0, 0, 'L');
          $pdf->SetY($y = $y+5);
          $pdf->SetX(10);
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
          $pdf->SetY($y = $y+10);
          $pdf->SetX(10);
          $pdf->cell(200, 25, 'No Surat : '.$no_surat_klaim, 0, 0, 'L');
          $pdf->SetY($y = $y+5);
          $pdf->SetX(10);
          $pdf->cell(200, 25, 'Pengajuan Klaim AJK Bukopin an : '.$nm_peserta.' berupa:', 0, 0, 'L');
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
          $pdf->SetFont('Arial', 'B', $font_size);
          $pdf->SetY($y = $y + 7);
          $pdf->cell(50, 25, $qasuransi['name'], 0, 0, 'L');
      } elseif ($_REQUEST['tipe']=="doklengkap") {
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
          $pdf->cell(100, 25, 'Yth, Bapak/Ibu '.$qasuransi['pic'].' '.$jabatan_as, 0, 0, 'L');
          $pdf->SetFont('Arial', '', $font_size);
          $pdf->SetY($y = $y+10);
          $pdf->SetX($x);
          $pdf->cell(50, 25, 'Dengan Hormat,', 0, 0, 'L');
          $pdf->SetY($y = $y+10);
          $pdf->SetX($x);
          $pdf->cell(200, 25, 'Menindaklanjuti laporan klaim awal pada tanggal '.$tgl_terima_document.' serta merunjuk surat '.$nmmitra, 0, 0, 'L');
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
          $pdf->cell(200, 25, 'Sebagai Syarat pengajuan klaim tersebut terlampir, kami sampaikan dokumen pendukung klaim untuk melengkapi kekurangan dokumen klaim terdahulu ', 0, 0, 'L');
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

          if ($qklaim['id_klaim_status']==2) {
              $pdf->SetY($y);
              $pdf->SetX($x);
              $pdf->cell(200, 25, 'Kekurangan Dokumen :', 0, 0, 'L');
              $no = 1;
              while ($qdocklaimkurang_row = mysql_fetch_array($qdocklaimkurang)) {
                  if ($qpeserta['id_polis'] == '1' or $qpeserta['id_polis'] == '12') {
                      if ($qdocklaimkurang_row['id'] == 17 or $qdocklaimkurang_row['id'] == 18) {
                          continue;
                      }
                  }

                  $pdf->SetY($y = $y+5);
                  $pdf->SetX(20);
                  $pdf->cell(3, 25, $no.'.', 0, 0, 'L');
                  $pdf->SetX(26);
                  $pdf->cell(200, 25, $qdocklaimkurang_row['nama_dok'], 0, 0, 'L');
                  $no++;
              }
              $pdf->SetY($y = $y + 10);
              $pdf->SetY($y);
              $pdf->SetX($x);
              $pdf->cell(200, 25, 'Adapun kekurangan dokumen tersebut akan kami sampaikan setelah kami mendapatkan kelengkapan', 0, 0, 'L');
              $pdf->SetY($y = $y + 5);
              $pdf->SetX($x);
              $pdf->cell(200, 25, 'dokumen dari cabang terkait.', 0, 0, 'L');
              $pdf->SetY($y = $y + 10);
          }


          if ($qpeserta['id_polis'] == 16) {
              $pdf->SetFont('Arial', 'B', 10);
              $pdf->SetY($y);
              $pdf->SetX($x);
              $pdf->cell(200, 25, 'Sehubungan dengan program asuransi dari debitur terkait adalah termasuk dalam program asuransi', 0, 0, 'L');
              $pdf->SetY($y = $y+5);
              $pdf->SetX($x);
              $pdf->cell(200, 25, 'AJK Pensiun Khusus Usia Platinum, dimana penutupan dilakukan secara Free Cover Limit oleh karena', 0, 0, 'L');
              $pdf->SetY($y = $y+5);
              $pdf->SetX($x);
              $pdf->cell(200, 25, 'itu dimohonkan agar pembayaran manfaat asuransi a.n. '.$nm_peserta.' dapat dijadikan prioritas', 0, 0, 'L');
              $pdf->SetY($y = $y+5);
              $pdf->SetX($x);
              $pdf->cell(200, 25, 'dan pembayaran dapat dilakukan dalam waktu dekat.', 0, 0, 'L');
              $pdf->SetFont('Arial', '', $font_size);
              $pdf->SetY($y = $y+10);
          }

          $pdf->SetY($y);
          $pdf->SetX($x);
          $pdf->cell(200, 25, 'Demikian kami sampaikan untuk menjadi perhatian untuk segera diselesaikan pembayaran klaimnya. Atas perhatian dan', 0, 0, 'L');
          $pdf->SetY($y = $y+5);
          $pdf->SetX($x);
          $pdf->cell(200, 25, 'kerjasamanya, kami ucapkan terima kasih.', 0, 0, 'L');
          $pdf->SetY($y = $y+10);
          $pdf->SetX($x);
          $pdf->cell(200, 25, 'Hormat kami,', 0, 0, 'L');
          $pdf->SetFont('Arial', 'BU', $font_size);
          $pdf->SetY($y = $y+30);
          $pdf->SetX($x);
          $pdf->cell(200, 25, 'Dessy Puji Astuti', 0, 0, 'L');
          $pdf->SetFont('Arial', 'B', $font_size);
          $pdf->SetY($y = $y+5);
          $pdf->SetX($x);
          $pdf->cell(200, 25, 'Manager Claim', 0, 0, 'L');
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
          $pdf->cell(100, 25, $jabatan_as, 0, 0, 'L');
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
          $pdf->SetFont('Arial', 'B', $font_size);
          $pdf->SetY($y = $y + 7);
          $pdf->cell(50, 25, $qasuransi['name'], 0, 0, 'L');
      } elseif ($_REQUEST['tipe']=="pembayaran") {
          $font_size = 10;
          $pdf->SetFont('Arial', '', $font_size);
          $pdf->SetY($y);
          $pdf->SetX($x);
          $pdf->cell(50, 25, 'No.', 0, 0, 'L');
          $pdf->SetX(40);
          $pdf->cell(1, 25, ':', 0, 0, 'L');
          $pdf->SetX(42);
          $pdf->cell(20, 25, $no_surat_pembayaran, 0, 0, 'L');
          $pdf->SetX(90);
          $pdf->cell(100, 25, 'Bekasi, '.$today, 0, 0, 'R');
          $pdf->SetY($y=$y+5);
          $pdf->SetX($x);
          $pdf->cell(50, 25, 'Lamp.', 0, 0, 'L');
          $pdf->SetX(40);
          $pdf->cell(1, 25, ':', 0, 0, 'L');
          $pdf->SetX(42);
          $pdf->cell(20, 25, '-', 0, 0, 'L');
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
          if ($mitra == 3) {
              $pdf->SetY($y = $y+5);
              $pdf->SetX($x);
              $pdf->cell(50, 25, 'Koperasi Simpan Pinjam Mekarsari', 0, 0, 'L');
              $pdf->SetFont('Arial', '', $font_size);
              $pdf->SetY($y = $y+5);
              $pdf->SetX($x);
              $pdf->cell(50, 25, 'Ruko Hexa Green Kalimalang No. 45', 0, 0, 'L');
              $pdf->SetY($y = $y+5);
              $pdf->SetX($x);
              $pdf->cell(50, 25, 'Jl. Pahlawan Revolusi RT 009 RW007', 0, 0, 'L');
              $pdf->SetY($y = $y+5);
              $pdf->SetX($x);
              $pdf->cell(50, 25, 'Kel. Pondok Bambu Kec. Duren Sawit', 0, 0, 'L');
              $pdf->SetY($y = $y+5);
              $pdf->SetX($x);
              $pdf->cell(50, 25, 'Jakarta Timur Telp. (021) ', 0, 0, 'L');
              $pdf->SetFont('Arial', 'B', $font_size);
              $pdf->SetY($y = $y+10);
              $pdf->SetX($x);
              $pdf->cell(50, 25, 'UP', 0, 0, 'L');
              $pdf->SetX(40);
              $pdf->cell(1, 25, ':', 0, 0, 'L');
              $pdf->SetX(42);
              $pdf->cell(100, 25, 'Yth, Bapak Manonga Pasaribu, SE, MM.', 0, 0, 'L');
          } else {
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
              $pdf->cell(100, 25, 'Yth, Bapak/Ibu '.$ttd, 0, 0, 'L');
              $pdf->SetY($y = $y+5);
              $pdf->SetX(42);
              $pdf->cell(100, 25, 'Yth, Ibu Runti', 0, 0, 'L');
          }
          $pdf->SetFont('Arial', '', $font_size);
          $pdf->SetY($y = $y+10);
          $pdf->SetX($x);
          $pdf->cell(50, 25, 'Menindak lanjuti surat nomor : '.$no_surat_keterangan.' tertanggal '.$tgl_surat.', bersama ini perlu kami ', 0, 0, 'L');
          $pdf->SetY($y = $y+5);
          $pdf->SetX($x);
          $pdf->cell(50, 25, 'sampaikan data sebagai berikut:', 0, 0, 'L');
          $pdf->SetY($y = $y+10);
          $pdf->SetX($x+10);
          $pdf->cell(200, 25, 'Nama Lengkap', 0, 0, 'L');
          $pdf->SetX(75);
          $pdf->cell(1, 25, ':', 0, 0, 'L');
          $pdf->SetX(77);
          $pdf->cell(100, 25, $nm_peserta, 0, 0, 'L');
          $pdf->SetY($y = $y+5);
          $pdf->SetX($x+10);
          $pdf->cell(200, 25, 'Tanggal Lahir', 0, 0, 'L');
          $pdf->SetX(75);
          $pdf->cell(1, 25, ':', 0, 0, 'L');
          $pdf->SetX(77);
          $pdf->cell(100, 25, $tgl_lahir, 0, 0, 'L');
          $pdf->SetY($y = $y+5);
          $pdf->SetX($x+10);
          $pdf->cell(200, 25, 'Periode Asuransi', 0, 0, 'L');
          $pdf->SetX(75);
          $pdf->cell(1, 25, ':', 0, 0, 'L');
          $pdf->SetX(77);
          $pdf->cell(100, 25, $periode_asuransi, 0, 0, 'L');
          $pdf->SetY($y = $y+5);
          $pdf->SetX($x+10);
          $pdf->cell(200, 25, 'Nilai Plafond Kredit', 0, 0, 'L');
          $pdf->SetX(75);
          $pdf->cell(1, 25, ':', 0, 0, 'L');
          $pdf->SetX(77);
          $pdf->cell(100, 25, 'IDR. '.$plafond.',-', 0, 0, 'L');
          $pdf->SetY($y = $y+5);
          $pdf->SetX($x+10);
          $pdf->cell(200, 25, 'Tanggal Meninggal Dunia', 0, 0, 'L');
          $pdf->SetX(75);
          $pdf->cell(1, 25, ':', 0, 0, 'L');
          $pdf->SetX(77);
          $pdf->cell(100, 25, $tgl_meninggal, 0, 0, 'L');
          $pdf->SetY($y = $y+5);
          $pdf->SetX($x+10);
          $pdf->cell(200, 25, 'Nilai Tuntutan Klaim', 0, 0, 'L');
          $pdf->SetX(75);
          $pdf->cell(1, 25, ':', 0, 0, 'L');
          $pdf->SetX(77);
          $pdf->cell(100, 25, 'IDR. '.$nilai_tuntutan_klaim.',-', 0, 0, 'L');
          $pdf->SetY($y = $y+20);
          $pdf->SetX($x);
          $pdf->Multicell(180, 5, 'Besarnya nilai pembayaran klaim Asuransi adalah sebesar Nilai Tuntutan Klaim dimana nilai maksimum adalah sebesar Nilai Plafond Kredit, dengan demikian besarnya nilai pembayaran manfaat Asuransi a.n. '.$nm_peserta.' yang akan dibayarkan adalah sebesar: IDR. '.$nilai_tuntutan_klaim.',- ('.ucwords(strtolower(mametbilang(duitterbilang($_REQUEST['teb'])))).' Rupiah).', 0);
          //$pdf->AddPage();
          $pdf->SetFont('Arial', '', $font_size);
          if ($tgl_bayar_bank == "") {
              $pdf->SetY($y = $y+20);
              $pdf->SetX($x);
              $pdf->Multicell(180, 5, 'Untuk proses pembayaran manfaat Asuransi atas nama '.$nm_peserta.' akan kami transfer ke rekening sebagai berikut :', 0);
          } else {
              $pdf->SetY($y = $y+20);
              $pdf->SetX($x);
              $pdf->Multicell(180, 5, 'Adapun proses pembayaran manfaat asuransi a.n '.$nm_peserta.' telah kami transfer ke rekening sebagai berikut : ', 0);
          }

          $pdf->SetFont('Arial', 'B', $font_size);
          $pdf->SetY($y = $y+5);
          $pdf->SetX($x+20);
          $pdf->cell(10, 25, 'Bank', 0, 0, 'L');
          $pdf->SetX(85);
          $pdf->cell(200, 25, ':', 0, 0, 'L');
          $pdf->SetX(88);
          $pdf->cell(200, 25, $bank, 0, 0, 'L');
          $pdf->SetY($y = $y+5);
          $pdf->SetX($x+20);
          $pdf->cell(10, 25, 'Nama Pemilik Rekening', 0, 0, 'L');
          $pdf->SetX(85);
          $pdf->cell(200, 25, ':', 0, 0, 'L');
          $pdf->SetX(88);
          $pdf->cell(200, 25, $anbank, 0, 0, 'L');
          $pdf->SetY($y = $y+5);
          $pdf->SetX($x+20);
          $pdf->cell(10, 25, 'No. Rekening', 0, 0, 'L');
          $pdf->SetX(85);
          $pdf->cell(200, 25, ':', 0, 0, 'L');
          $pdf->SetX(88);
          $pdf->cell(200, 25, $norek, 0, 0, 'L');


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
          $pdf->SetX(120);
          $pdf->cell(100, 25, 'Menyetujui', 0, 0, 'L');
          $pdf->SetFont('Arial', 'BU', $font_size);
          $pdf->SetY($y = $y+30);
          $pdf->SetX($x);
          $pdf->cell(200, 25, 'Ing Sriwati', 0, 0, 'L');
          $pdf->SetX(120);
          $pdf->cell(100, 25, $ttd, 0, 0, 'L');
          $pdf->SetFont('Arial', 'B', $font_size);
          $pdf->SetY($y = $y+5);
          $pdf->SetX($x);
          $pdf->cell(200, 25, 'Direktur', 0, 0, 'L');
          $pdf->SetX(120);
          $pdf->cell(100, 25, $jabatan, 0, 0, 'L');
      } elseif ($_REQUEST['tipe']=="kadaluarsa") {
          if ($_GET['rem']=='1') {
              $no_reminder=$qklaim['no_surat_reminder1'];
              $ket_reminder=$qklaim['ket_surat_reminder1'];
          } elseif ($_GET['rem']=='2') {
              $no_reminder=$qklaim['no_surat_reminder2'];
              $ket_reminder=$qklaim['ket_surat_reminder2'];
          } elseif ($_GET['rem']=='3') {
              $no_reminder=$qklaim['no_surat_reminder3'];
              $ket_reminder=$qklaim['ket_surat_reminder3'];
          }
          //$pdf->Image('http://150.107.149.27/ajk/image/logo_adonai.png',100,$pdf->getY()+10,-300);
          $pdf->Image('../image/logo_adonai.png', 50, 5, 100);
          $font_size = 10;
          $pdf->SetFont('Arial', '', $font_size);
          $pdf->SetY($y);
          $pdf->SetX($x);
          $pdf->cell(50, 25, 'No.', 0, 0, 'L');
          $pdf->SetX(40);
          $pdf->cell(1, 25, ':', 0, 0, 'L');
          $pdf->SetX(42);
          $pdf->cell(20, 25, $no_reminder, 0, 0, 'L');
          $pdf->SetX(90);
          $pdf->cell(100, 25, 'Bekasi, '.$today, 0, 0, 'R');
          $pdf->SetY($y=$y+5);
          $pdf->SetX($x);
          $pdf->cell(50, 25, 'Lamp.', 0, 0, 'L');
          $pdf->SetX(40);
          $pdf->cell(1, 25, ':', 0, 0, 'L');
          $pdf->SetX(42);
          $pdf->cell(20, 25, '-', 0, 0, 'L');
          $pdf->SetY($y = $y+5);
          $pdf->SetX($x);
          $pdf->cell(50, 25, 'Perihal', 0, 0, 'L');
          $pdf->SetX(40);
          $pdf->cell(1, 25, ':', 0, 0, 'L');
          $pdf->SetFont('Arial', 'B', $font_size);
          $pdf->SetX(42);
          $pdf->cell(20, 25, 'Reminder '.$_GET['rem'].' Kelengkapan Dokumen Klaim a.n. : '.$nm_peserta.'', 0, 0, 'L');
          $pdf->SetFont('Arial', '', $font_size);
          $pdf->SetY($y = $y+7);
          $pdf->SetX($x);
          $pdf->cell(50, 25, 'Kepada Yth,', 0, 0, 'L');
          $pdf->SetFont('Arial', 'B', $font_size);
          if ($mitra == 3) {
              $pdf->SetY($y = $y+5);
              $pdf->SetX($x);
              $pdf->cell(50, 25, 'Koperasi Simpan Pinjam Mekarsari', 0, 0, 'L');
              $pdf->SetFont('Arial', '', $font_size);
              $pdf->SetY($y = $y+5);
              $pdf->SetX($x);
              $pdf->cell(50, 25, 'Ruko Hexa Green Kalimalang No. 45', 0, 0, 'L');
              $pdf->SetY($y = $y+5);
              $pdf->SetX($x);
              $pdf->cell(50, 25, 'Jl. Pahlawan Revolusi RT 009 RW007', 0, 0, 'L');
              $pdf->SetY($y = $y+5);
              $pdf->SetX($x);
              $pdf->cell(50, 25, 'Kel. Pondok Bambu Kec. Duren Sawit', 0, 0, 'L');
              $pdf->SetY($y = $y+5);
              $pdf->SetX($x);
              $pdf->cell(50, 25, 'Jakarta Timur Telp. (021) ', 0, 0, 'L');
              $pdf->SetFont('Arial', 'B', $font_size);
              $pdf->SetY($y = $y+7);
              $pdf->SetX($x);
              $pdf->cell(50, 25, 'UP', 0, 0, 'L');
              $pdf->SetX(40);
              $pdf->cell(1, 25, ':', 0, 0, 'L');
              $pdf->SetX(42);
              $pdf->cell(100, 25, 'Yth, Bapak Manonga Pasaribu, SE, MM.', 0, 0, 'L');
          } else {
              /*if($_GET['rem']=='3'){
                  $pdf->SetY($y = $y+5);$pdf->SetX($x);$pdf->cell(50,25,'PT. Bank Bukopin, Tbk Capem S. Parman',0,0,'L');
                  $pdf->SetFont('Arial','',$font_size);
                  $pdf->SetY($y = $y+5);$pdf->SetX($x);$pdf->cell(50,25,'Bisnis Mikro',0,0,'L');

                  $qcabang = mysql_fetch_array(mysql_query("select * from fu_ajk_cacang where name = '".$nm_cabang."'"));

                  $pdf->SetY($y = $y+5);$pdf->SetX($x);$pdf->cell(50,25,'Jl. S. Parman Kav. 80, Slipi,',0,0,'L');
                  $pdf->SetY($y = $y+5);$pdf->SetX($x);$pdf->cell(50,25,'Jakarta 11460',0,0,'L');
                  $pdf->SetFont('Arial','B',$font_size);
                  $pdf->SetY($y = $y+10);$pdf->SetX($x);$pdf->cell(50,25,'UP',0,0,'L');$pdf->SetX(40);$pdf->cell(1,25,':',0,0,'L');$pdf->SetX(42);$pdf->cell(100,25,'Yth. Bapak Agny Irsyad',0,0,'L');
              }else{*/

              $pdf->SetY($y = $y+5);
              $pdf->SetX($x);
              $pdf->cell(50, 25, 'PT. Bank Bukopin Tbk, Kredit Mikro', 0, 0, 'L');
              $pdf->SetFont('Arial', '', $font_size);
              $pdf->SetY($y = $y+5);
              $pdf->SetX($x);
              $pdf->cell(50, 25, 'Cabang '.$nm_cabang, 0, 0, 'L');

              $qcabang = mysql_fetch_array(mysql_query("select * from fu_ajk_cabang where name = '".$nm_cabang."'"));



              $pdf->SetY($y = $y+5);
              $pdf->SetX($x);
              $pdf->cell(50, 25, $qcabang['address'], 0, 0, 'L');
              $pdf->SetY($y = $y+5);
              $pdf->SetX($x);
              $pdf->cell(50, 25, $qcabang1['address2'], 0, 0, 'L');
              $pdf->SetFont('Arial', 'B', $font_size);
              $pdf->SetY($y = $y+7);
              $pdf->SetX($x);
              $pdf->cell(50, 25, 'UP', 0, 0, 'L');
              $pdf->SetX(40);
              $pdf->cell(1, 25, ':', 0, 0, 'L');
              $pdf->SetX(42);
              $pdf->cell(100, 25, 'Yth. Bapak/Ibu Manager Pelayanan dan Operasional Cabang '.$nm_cabang, 0, 0, 'L');
              /*}*/
          }
          $pdf->SetFont('Arial', '', $font_size);
          $pdf->SetY($y = $y+7);
          $pdf->SetX($x);
          $pdf->cell(50, 25, 'Dengan Hormat, ', 0, 0, 'L');
          $pdf->SetY($y = $y+7);
          $pdf->SetX($x);
          $pdf->cell(50, 25, 'Pertama-tama kami mengucapkan terima kasih atas kerjasama yang yang telah terjalin dengan sangat baik ', 0, 0, 'L');
          $pdf->SetY($y = $y+5);
          $pdf->SetX($x);
          $pdf->cell(50, 25, 'selama ini antara PT. Bank Bukopin, Tbk dan PT. Adonai Pialang Asuransi.', 0, 0, 'L');
          $pdf->SetY($y = $y+7);
          $pdf->SetX($x);
          $pdf->cell(50, 25, 'Sehubungan dengan dokumen klaim yang kami terima dengan data sebagai berikut : ', 0, 0, 'L');
          $pdf->SetFont('Arial', 'B', $font_size);
          $pdf->SetY($y = $y+5);
          $pdf->SetX($x+5);
          $pdf->cell(50, 25, 'Nama', 0, 0, 'L');
          $pdf->SetX($x+50);
          $pdf->cell(50, 25, ':', 0, 0, 'L');
          $pdf->SetX($x+55);
          $pdf->cell(50, 25, $nm_peserta, 0, 0, 'L');
          $pdf->SetY($y = $y+5);
          $pdf->SetX($x+5);
          $pdf->cell(50, 25, 'Tanggal Lahir', 0, 0, 'L');
          $pdf->SetX($x+50);
          $pdf->cell(50, 25, ':', 0, 0, 'L');
          $pdf->SetX($x+55);
          $pdf->cell(50, 25, $tgl_lahir, 0, 0, 'L');
          $pdf->SetY($y = $y+5);
          $pdf->SetX($x+5);
          $pdf->cell(50, 25, 'Plafond', 0, 0, 'L');
          $pdf->SetX($x+50);
          $pdf->cell(50, 25, ':', 0, 0, 'L');
          $pdf->SetX($x+55);
          $pdf->cell(50, 25, $plafond, 0, 0, 'L');
          $pdf->SetY($y = $y+5);
          $pdf->SetX($x+5);
          $pdf->cell(50, 25, 'Tanggal Akad', 0, 0, 'L');
          $pdf->SetX($x+50);
          $pdf->cell(50, 25, ':', 0, 0, 'L');
          $pdf->SetX($x+55);
          $pdf->cell(50, 25, viewBulanIndo($qpeserta['kredit_tgl']), 0, 0, 'L');
          $pdf->SetFont('Arial', '', $font_size);
          $pdf->SetY($y = $y+7);
          $pdf->SetX($x);
          $pdf->cell(50, 25, 'Kami sampaikan kekurangan dokumen klaim yang terdiri dari :', 0, 0, 'L');
          $pdf->SetFont('Arial', 'B', $font_size);
          $pdf->SetY($y = $y+15);
          $pdf->SetX($x+10);
          $pdf->Multicell(180, 5, $ket_reminder, 0);
          //$pdf->AddPage();
          $pdf->SetFont('Arial', '', $font_size);
          $pdf->SetY($y = $pdf->getY()+7);
          $pdf->SetX($x);
          // $pdf->Multicell(180,5,'Mohon agar kelengkapan dokumen klaim tersebut dikirimkan kepada kami sebelum tanggal '.viewBulanIndo($qklaim['due_date']).', apabila dokumen kami terima setelah tanggal tersebut klaim ini tidak dapat ditindaklanjuti karena sudah melewati batas waktu yang ditentukan.',0);
          $pdf->cell(180, 5, 'Mohon agar kelengkapan dokumen klaim tersebut dikirimkan kepada kami sebelum tanggal ', 0, 0, 'L');
          $pdf->SetFont('Arial', 'B', $font_size);
          $pdf->SetX($x+145);
          $pdf->cell(180, 5, viewBulanIndo($qklaim['due_date']).', ', 0, 0, 'L');
          $pdf->SetFont('Arial', '', $font_size);
          $pdf->SetY($y = $y+5);
          $pdf->SetX($x);
          $pdf->cell(180, 5, 'apabila dokumen kami terima setelah tanggal tersebut klaim ini tidak dapat ditindaklanjuti karena sudah melewati', 0, 0, 'L');
          $pdf->SetY($y = $y+5);
          $pdf->SetX($x);
          $pdf->cell(180, 5, 'batas waktu yang ditentukan.', 0, 0, 'L');

          $pdf->SetFont('Arial', '', $font_size);
          $pdf->SetY($y = $y+7);
          $pdf->SetX($x);
          $pdf->cell(200, 25, 'Demikian informasi ini kami sampaikan, atas perhatian dan kerjasamanya kami mengucapkan terima kasih.', 0, 0, 'L');
          $pdf->SetY($y = $y+10);
          $pdf->SetX($x);
          $pdf->cell(200, 25, 'Hormat kami,', 0, 0, 'L');
          $pdf->SetFont('Arial', 'B', $font_size);
          $pdf->SetY($y = $y+5);
          $pdf->SetX($x);
          $pdf->cell(50, 25, 'PT. Adonai Pialang Asuransi', 0, 0, 'L');
          $pdf->SetFont('Arial', 'BU', $font_size);
          $pdf->Image('../image/ttd_dessy.png', 5, $pdf->getY()+15, -140);
          $pdf->SetY($y = $y+25);
          $pdf->SetX($x);
          $pdf->cell(200, 25, 'Dessy Puji Astuti', 0, 0, 'L');
          $pdf->SetFont('Arial', 'B', $font_size);
          $pdf->SetY($y = $y+5);
          $pdf->SetX($x);
          $pdf->cell(200, 25, 'Manager Claim', 0, 0, 'L');

          $pdf->SetFont('Arial', '', $font_size);
          $pdf->SetY($y = $y+7);
          $pdf->SetX($x);
          $pdf->cell(200, 25, 'Tembusan : Yth. Bapak Priambodo - Kadiv Managemen Operasi Kartu Kredit, Kredit Konsumer dan Kredit Personal', 0, 0, 'L');
      } elseif ($_REQUEST['tipe']=="pengajuanmedical") {
          $pdf->SetFont('Arial', '', $font_size);
          $pdf->SetY($y);
          $pdf->SetX($x);
          $pdf->cell(50, 25, 'Nomor', 0, 0, 'L');
          $pdf->SetX(40);
          $pdf->cell(1, 25, ':', 0, 0, 'L');
          $pdf->SetX(42);
          $pdf->cell(20, 25, $no_pengajuan_medical, 0, 0, 'L');
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
          $pdf->cell(100, 25, 'Yth, Bapak/Ibu '.$qasuransi['pic'].' '.$jabatan_as, 0, 0, 'L');
          $pdf->SetFont('Arial', '', $font_size);
          $pdf->SetY($y = $y+10);
          $pdf->SetX($x);
          $pdf->cell(50, 25, 'Dengan Hormat,', 0, 0, 'L');
          $pdf->SetY($y = $y+20);
          $pdf->SetX($x);
          // $pdf->cell(200, 25, 'Pertama-tama kami ucapkan terima kasih atas kepercayaan dan kerjasama yang telah terjalin dengan sangat baik selama ini antara PT Asuransi XXXX  dan PT Adonai Pialang Asuransi.', 0, 0, 'L');
          $pdf->MultiCell( 190, 5, "Pertama-tama kami ucapkan terima kasih atas kepercayaan dan kerjasama yang telah terjalin dengan sangat baik selama ini antara ".$qasuransi['name']." dan PT Adonai Pialang Asuransi.", 0);
          $pdf->SetY($y = $y+10);
          $pdf->SetX($x);
          $pdf->cell(200, 25, 'Dengan ini kami menyampaikan pengajuan klaim Asuransi untuk tertanggung sbb :', 0, 0, 'L');
          $pdf->SetFont('Arial', 'B', $font_size);
          $pdf->SetY($y = $y+10);
          $pdf->SetX($x);
          $pdf->cell(200, 25, 'Nama Debitur', 0, 0, 'L');
          $pdf->SetX(65);
          $pdf->cell(1, 25, ':', 0, 0, 'L');
          $pdf->SetX(67);
          $pdf->cell(100, 25, $nm_peserta, 0, 0, 'L');
          $pdf->SetY($y = $y+6);
          $pdf->SetX($x);
          $pdf->cell(200, 25, 'Tanggal Lahir', 0, 0, 'L');
          $pdf->SetX(65);
          $pdf->cell(1, 25, ':', 0, 0, 'L');
          $pdf->SetX(67);
          $pdf->cell(100, 25, $tgl_lahir, 0, 0, 'L');
          $pdf->SetY($y = $y+6);
          $pdf->SetX($x);
          $pdf->cell(200, 25, 'Periode Asuransi', 0, 0, 'L');
          $pdf->SetX(65);
          $pdf->cell(1, 25, ':', 0, 0, 'L');
          $pdf->SetX(67);
          $pdf->cell(100, 25, $periode_asuransi, 0, 0, 'L');
          $pdf->SetY($y = $y+6);
          $pdf->SetX($x);
          $pdf->cell(200, 25, 'Plafond Kredit', 0, 0, 'L');
          $pdf->SetX(65);
          $pdf->cell(1, 25, ':', 0, 0, 'L');
          $pdf->SetX(67);
          $pdf->cell(100, 25, 'Rp. '.$plafond.',-', 0, 0, 'L');
          $pdf->SetY($y = $y+6);
          $pdf->SetX($x);
          $pdf->cell(200, 25, 'Tanggal Meninggal Dunia', 0, 0, 'L');
          $pdf->SetX(65);
          $pdf->cell(1, 25, ':', 0, 0, 'L');
          $pdf->SetX(67);
          $pdf->cell(100, 25, $tgl_meninggal, 0, 0, 'L');
          $pdf->SetY($y = $y+6);
          $pdf->SetX($x);
          $pdf->cell(200, 25, 'Program Asuransi', 0, 0, 'L');
          $pdf->SetX(65);
          $pdf->cell(1, 25, ':', 0, 0, 'L');
          $pdf->SetX(67);
          $pdf->cell(100, 25, $nm_produk, 0, 0, 'L');
          $pdf->SetY($y = $y+6);
          $pdf->SetX($x);
          $pdf->cell(200, 25, 'Cabang', 0, 0, 'L');
          $pdf->SetX(65);
          $pdf->cell(1, 25, ':', 0, 0, 'L');
          $pdf->SetX(67);
          $pdf->cell(100, 25, $nm_cabang, 0, 0, 'L');

          $pdf->SetFont('Arial', '', $font_size);
          $pdf->SetY($y = $y+8);
          $pdf->SetX($x);
          $pdf->cell(200, 25, 'Kami harapkan klaim tersebut dapat segera diproses dan diterima dengan baik dengan pertimbangan. ', 0, 0, 'L');
          $pdf->SetY($y = $y+20);
          $pdf->SetFont('Arial', 'I', $font_size);
          $pdf->MultiCell( 190, 5, $analisaasuransi, 0);            
          $pdf->SetFont('Arial', '', $font_size);
          // $pdf->SetY($y= $y+15);
          $pdf->SetY($y= $y+(strlen($analisaasuransi)/20));
          $pdf->SetX($x);
          $pdf->cell(200, 25, 'Demikian kami sampaikan, atas perhatian dan kerja samanya kami ucapkan terimakasih. ', 0, 0, 'L');
          $pdf->SetY($y = $y+10);
          $pdf->SetX($x);
          $pdf->cell(200, 25, 'Hormat kami,', 0, 0, 'L');
          $pdf->SetFont('Arial', 'B', $font_size);
          $pdf->SetY($y = $y+5);
          $pdf->SetX($x);

          $pdf->cell(200, 25, 'PT. Adonai Pialang Asuransi', 0, 0, 'L');            
          // $pdf->SetY($y = $y+30);
          // $pdf->SetX($x);
          // $pdf->cell(200, 25, 'Dessy Puji Astuti', 0, 0, 'L');
          // $pdf->SetFont('Arial', 'B', $font_size);
          // $pdf->SetY($y = $y+5);
          // $pdf->SetX($x);
          // $pdf->cell(200, 25, 'Manager Claim', 0, 0, 'L');
      } elseif ($_REQUEST['tipe']=="infomedical") {
          // $pdf->Image('../image/logo_adonai.png', 50, 5, 100);
          // $font_size = 10;
          $pdf->SetFont('Arial', '', $font_size);
          $pdf->SetY($y);
          $pdf->SetX($x);
          $pdf->cell(50, 25, 'No.', 0, 0, 'L');
          $pdf->SetX(40);
          $pdf->cell(1, 25, ':', 0, 0, 'L');
          $pdf->SetX(42);
          $pdf->cell(20, 25, $no_info_medical, 0, 0, 'L');
          $pdf->SetX(90);
          $pdf->cell(100, 25, 'Bekasi, '.$today, 0, 0, 'R');
          $pdf->SetY($y=$y+5);
          $pdf->SetX($x);
          $pdf->cell(50, 25, 'Lamp.', 0, 0, 'L');
          $pdf->SetX(40);
          $pdf->cell(1, 25, ':', 0, 0, 'L');
          $pdf->SetX(42);
          $pdf->cell(20, 25, '-', 0, 0, 'L');
          $pdf->SetY($y = $y+5);
          $pdf->SetX($x);
          $pdf->cell(50, 25, 'Perihal', 0, 0, 'L');
          $pdf->SetX(40);
          $pdf->cell(1, 25, ':', 0, 0, 'L');
          $pdf->SetFont('Arial', 'B', $font_size);
          $pdf->SetX(42);
          $pdf->cell(20, 25, 'Informasi Klaim a.n. : '.$nm_peserta.'', 0, 0, 'L');
          $pdf->SetFont('Arial', '', $font_size);
          $pdf->SetY($y = $y+7);
          $pdf->SetX($x);
          $pdf->cell(50, 25, 'Kepada Yth,', 0, 0, 'L');
          $pdf->SetFont('Arial', 'B', $font_size);
          
          $pdf->SetY($y = $y+5);
          $pdf->SetX($x);
          $pdf->cell(50, 25, 'PT. Bank Bukopin Tbk, Kredit Mikro', 0, 0, 'L');
          $pdf->SetFont('Arial', '', $font_size);
          $pdf->SetY($y = $y+5);
          $pdf->SetX($x);
          $pdf->cell(50, 25, 'Cabang '.$nm_cabang, 0, 0, 'L');

          $qcabang = mysql_fetch_array(mysql_query("select * from fu_ajk_cabang where name = '".$nm_cabang."'"));

          $pdf->SetY($y = $y+5);
          $pdf->SetX($x);
          $pdf->cell(50, 25, $qcabang['address'], 0, 0, 'L');
          $pdf->SetY($y = $y+5);
          $pdf->SetX($x);
          $pdf->cell(50, 25, $qcabang1['address2'], 0, 0, 'L');
          $pdf->SetFont('Arial', 'B', $font_size);
          $pdf->SetY($y = $y+7);
          $pdf->SetX($x);
          $pdf->cell(50, 25, 'UP', 0, 0, 'L');
          $pdf->SetX(40);
          $pdf->cell(1, 25, ':', 0, 0, 'L');
          $pdf->SetX(42);
          $pdf->cell(100, 25, 'Yth. Bapak/Ibu Manager Pelayanan dan Operasional Cabang '.$nm_cabang, 0, 0, 'L');        
          $pdf->SetFont('Arial', '', $font_size);
          $pdf->SetY($y = $y+10);
          $pdf->SetX($x);
          $pdf->cell(50, 25, 'Dengan Hormat,', 0, 0, 'L');
          $pdf->SetY($y = $y+20);
          $pdf->SetX($x);
          $pdf->MultiCell( 190, 5, "Pertama-tama kami ucapkan terima kasih atas kepercayaan dan kerjasama yang telah terjalin dengan sangat baik selama ini antara PT Bank Bukopin,Tbk. dan PT Adonai Pialang Asuransi.", 0);
          $pdf->SetY($y = $y+5);
          $pdf->SetX($x);
          $pdf->cell(200, 25, 'Dengan ini kami menyampaikan pengajuan klaim Asuransi untuk tertanggung sbb :', 0, 0, 'L');
          $pdf->SetFont('Arial', 'B', $font_size);
          $pdf->SetY($y = $y+10);
          $pdf->SetX($x);
          $pdf->cell(200, 25, 'Nama Debitur', 0, 0, 'L');
          $pdf->SetX(65);
          $pdf->cell(1, 25, ':', 0, 0, 'L');
          $pdf->SetX(67);
          $pdf->cell(100, 25, $nm_peserta, 0, 0, 'L');
          $pdf->SetY($y = $y+6);
          $pdf->SetX($x);
          $pdf->cell(200, 25, 'Tanggal Lahir', 0, 0, 'L');
          $pdf->SetX(65);
          $pdf->cell(1, 25, ':', 0, 0, 'L');
          $pdf->SetX(67);
          $pdf->cell(100, 25, $tgl_lahir, 0, 0, 'L');
          $pdf->SetY($y = $y+6);
          $pdf->SetX($x);
          $pdf->cell(200, 25, 'Periode Asuransi', 0, 0, 'L');
          $pdf->SetX(65);
          $pdf->cell(1, 25, ':', 0, 0, 'L');
          $pdf->SetX(67);
          $pdf->cell(100, 25, $periode_asuransi, 0, 0, 'L');
          $pdf->SetY($y = $y+6);
          $pdf->SetX($x);
          $pdf->cell(200, 25, 'Plafond Kredit', 0, 0, 'L');
          $pdf->SetX(65);
          $pdf->cell(1, 25, ':', 0, 0, 'L');
          $pdf->SetX(67);
          $pdf->cell(100, 25, 'Rp. '.$plafond.',-', 0, 0, 'L');
          $pdf->SetY($y = $y+6);
          $pdf->SetX($x);
          $pdf->cell(200, 25, 'Tanggal Meninggal Dunia', 0, 0, 'L');
          $pdf->SetX(65);
          $pdf->cell(1, 25, ':', 0, 0, 'L');
          $pdf->SetX(67);
          $pdf->cell(100, 25, $tgl_meninggal, 0, 0, 'L');
          $pdf->SetY($y = $y+6);
          $pdf->SetX($x);
          $pdf->cell(200, 25, 'Program Asuransi', 0, 0, 'L');
          $pdf->SetX(65);
          $pdf->cell(1, 25, ':', 0, 0, 'L');
          $pdf->SetX(67);
          $pdf->cell(100, 25, $nm_produk, 0, 0, 'L');
          $pdf->SetY($y = $y+6);
          $pdf->SetX($x);
          $pdf->cell(200, 25, 'Cabang', 0, 0, 'L');
          $pdf->SetX(65);
          $pdf->cell(1, 25, ':', 0, 0, 'L');
          $pdf->SetX(67);
          $pdf->cell(100, 25, $nm_cabang, 0, 0, 'L');
          $pdf->SetFont('Arial', '', $font_size);
          $pdf->SetY($y = $y+8);
          $pdf->SetX($x);
          $pdf->cell(200, 25, 'Berikut Analis awal dari pihak Asuransi untuk pengajuan klaim tersebut.', 0, 0, 'L');
          $pdf->SetY($y = $y+20);
          $pdf->SetFont('Arial', 'I', $font_size);
          $pdf->MultiCell( 190, 5, $analisabank, 0);            
          $pdf->SetFont('Arial', '', $font_size);            
          $pdf->SetY($y= $y+(strlen($analisabank)/25));
          // $pdf->SetX($x);
          // $pdf->cell(200, 25, 'Usia Polis ', 0, 0, 'L');
          $pdf->SetY($y= $y+10);
          $pdf->SetX($x);
          $pdf->MultiCell( 190, 5,'Akan tetapi klaim tersebut sedang kami usahakan untuk dapat di proses atau di bayarkan oleh pihak Asuransi.', 0);            
          // $pdf->cell(200, 25, 'Akan tetapi klaim tersebut sedang kami usahakan untuk dapat di proses atau di bayarkan oleh pihak Asuransi.', 0, 0, 'L');
          $pdf->SetY($y= $y+5);
          $pdf->SetX($x);
          $pdf->cell(200, 25, 'Demikian kami sampaikan, atas perhatian dan kerja samanya kami ucapkan terimakasih. ', 0, 0, 'L');
          $pdf->SetY($y = $y+10);
          $pdf->SetX($x);
          $pdf->cell(200, 25, 'Hormat kami,', 0, 0, 'L');
          $pdf->SetFont('Arial', 'B', $font_size);
          $pdf->SetY($y = $y+5);
          $pdf->SetX($x);

          $pdf->cell(200, 25, 'PT. Adonai Pialang Asuransi', 0, 0, 'L');
          // $pdf->SetFont('Arial', 'BU', $font_size);
          // $pdf->SetY($y = $y+30);
          // $pdf->SetX($x);
          // $pdf->cell(200, 25, 'Dessy Puji Astuti', 0, 0, 'L');
          // $pdf->SetFont('Arial', 'B', $font_size);
          // $pdf->SetY($y = $y+5);
          // $pdf->SetX($x);
          // $pdf->cell(200, 25, 'Manager Claim', 0, 0, 'L');            
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

  case "lapklaimmgm":
      $query = mysql_query("select fu_ajk_peserta.id_peserta,
															 fu_ajk_peserta.nama,
															 fu_ajk_peserta.tgl_lahir,
															 fu_ajk_peserta.kredit_jumlah,
															 fu_ajk_peserta.kredit_tenor,
															 fu_ajk_klaim.tgl_klaim,
															 IFNULL(DATE_FORMAT(fu_ajk_cn.approve_date,'%d-%m-%Y'),'00-00-0000')as approve_date,
															 IFNULL(DATE_FORMAT(fu_ajk_klaim.tgl_lapor_klaim,'%d-%m-%Y'),'00-00-0000')as tgl_lapor_klaim,
															 IFNULL(DATE_FORMAT(fu_ajk_klaim.tgl_document_lengkap,'%d-%m-%Y'),'00-00-0000')as tgl_document_lengkap,
															 IFNULL(DATE_FORMAT(fu_ajk_klaim.tgl_kirim_dokumen,'%d-%m-%Y'),'00-00-0000')as tgl_kirim_dokumen,
															 IFNULL(DATE_FORMAT(fu_ajk_cn.tgl_bayar_asuransi,'%d-%m-%Y'),'00-00-0000')as tgl_bayar_asuransi,
															 fu_ajk_klaim_status.status_klaim,
															 fu_ajk_klaim.id_klaim_status,
															 IFNULL(DATEDIFF(fu_ajk_klaim.tgl_document_lengkap,fu_ajk_klaim.tgl_klaim),0)as lamapengumpulandok,
															 IFNULL(DATEDIFF(fu_ajk_klaim.tgl_kirim_dokumen,fu_ajk_klaim.tgl_document_lengkap),0)as lamaprosesklaim,
															 IFNULL(DATEDIFF(fu_ajk_cn.approve_date,fu_ajk_klaim.tgl_klaim),0)as lamaterimalaporan
												from fu_ajk_peserta
														 inner join fu_ajk_cn
														 on fu_ajk_cn.id = fu_ajk_peserta.id_klaim
														 inner join fu_ajk_klaim
														 on fu_ajk_klaim.id_cn = fu_ajk_cn.id
														 inner join fu_ajk_klaim_status
														 on fu_ajk_klaim_status.id = fu_ajk_klaim.id_klaim_status
												where fu_ajk_peserta.del is null and
															fu_ajk_klaim.del is null and
															fu_ajk_cn.del is null and
															fu_ajk_peserta.status_peserta = 'Death' and
															id_klaim_status != 1");

      $pdf=new FPDF('L', 'mm', 'A4');
      $pdf->Open();
      $pdf->AliasNbPages();
      $pdf->AddPage();
      $y = 20;
      $x = 1;
      $pdf->SetFont('Arial', '', 7);
      $pdf->SetY($y);$pdf->SetX($x);$pdf->cell(10, 10, 'No (A)', 1, 0, 'C');
      $pdf->SetY($y);$pdf->SetX($x=$x+10);$pdf->cell(20, 10, 'ID Peserta (B)', 1, 0, 'C');
      $pdf->SetY($y);$pdf->SetX($x=$x+20);$pdf->cell(40, 10, 'Nama (C)', 1, 0, 'C');
      $pdf->SetY($y);$pdf->SetX($x=$x+40);$pdf->cell(17, 10, 'Tgl Lahir (D)', 1, 0, 'C');
      $pdf->SetY($y);$pdf->SetX($x=$x+17);$pdf->cell(20, 10, 'Plafond (E)', 1, 0, 'C');
      $pdf->SetY($y);$pdf->SetX($x=$x+20);$pdf->MultiCell(10, 5, 'Tenor (F)', 1, 'C');
      $pdf->SetY($y);$pdf->SetX($x=$x+10);$pdf->MultiCell(17, 5, 'Tgl Meninggal(G)', 1, 'C');
      $pdf->SetY($y);$pdf->SetX($x=$x+17);$pdf->cell(34, 5, 'Tgl Lapor', 1, 0, 'C');
      $pdf->SetY($y=$y+5);$pdf->SetX($x=$x);$pdf->cell(17, 5, 'Bank (H)', 1, 0, 'C');
      $pdf->SetY($y);$pdf->SetX($x=$x+17);$pdf->cell(17, 5, 'Asuransi (I)', 1, 0, 'C');
      $pdf->SetY($y=$y-5);$pdf->SetX($x=$x+17);$pdf->MultiCell(10, 5, 'Lama (G-H)', 1, 'C');
      $pdf->SetY($y);$pdf->SetX($x=$x+10);$pdf->MultiCell(17, 5, 'Tgl Dok Lengkap (K)', 1, 'C');
      $pdf->SetY($y);$pdf->SetX($x=$x+17);$pdf->MultiCell(10, 5, 'Lama (G-K)', 1, 'C');
      $pdf->SetY($y);$pdf->SetX($x=$x+10);$pdf->MultiCell(17, 5, 'Tgl kirim asuransi (M)', 1, 'C');
      $pdf->SetY($y);$pdf->SetX($x=$x+17);$pdf->MultiCell(10, 5, 'Lama (K-M)', 1, 'C');
      $pdf->SetY($y);$pdf->SetX($x=$x+10);$pdf->cell(45, 5, 'Status', 1, 0, 'C');
      $pdf->SetY($y=$y+5);$pdf->SetX($x=$x);$pdf->cell(35, 5, 'keterangan (O)', 1, 0, 'C');
      $pdf->SetY($y);$pdf->SetX($x=$x+35);$pdf->cell(10, 5, 'Day (P)', 1, 0, 'C');
      $pdf->SetY($y=$y-5);$pdf->SetX($x=$x+10);$pdf->MultiCell(17, 5, 'Tgl asuransi bayar (Q)', 1, 'C');

      $y = 25;
      $no = 1;
      $pagebreak = 0;
      $pdf->SetFont('Arial', '', 7);
      while ($query_row = mysql_fetch_array($query)) {
          if ($query_row['id_klaim_status'] == 2) {
              $id_klaim_status = 'Menunggu Kel. Dok. Klaim';
          } else {
              $id_klaim_status = $query_row['status_klaim'];
          }
          if ($query_row['tgl_kirim_dokumen'] != '00-00-0000') {
              $tgl_kirim_dokumen = $query_row['tgl_kirim_dokumen'];
          } else {
              $tgl_kirim_dokumen = '-';
          }
          if ($query_row['tgl_bayar_asuransi'] != '00-00-0000') {
              $tgl_bayar_asuransi = $query_row['tgl_bayar_asuransi'];
          } else {
              $tgl_bayar_asuransi = '-';
          }
          if ($query_row['tgl_document_lengkap'] != '00-00-0000') {
              $tgl_document_lengkap = $query_row['tgl_document_lengkap'];
          } else {
              $tgl_document_lengkap = '-';
          }
          if ($query_row['tgl_lapor_klaim'] != '00-00-0000') {
              $tgl_lapor_klaim = $query_row['tgl_lapor_klaim'];
          } else {
              $tgl_lapor_klaim = '-';
          }
          if ($query_row['approve_date'] != '00-00-0000') {
              $approve_date = $query_row['approve_date'];
          } else {
              $approve_date = '-';
          }

          $x=1;
          $pdf->SetY($y=$y+5);
          $pdf->SetX($x);
          $pdf->cell(10, 5, $no, 1, 0, 'C');
          $pdf->SetY($y);
          $pdf->SetX($x=$x+10);
          $pdf->cell(20, 5, $query_row['id_peserta'], 1, 0, 'C');
          $pdf->SetY($y);
          $pdf->SetX($x=$x+20);
          $pdf->cell(40, 5, $query_row['nama'], 1, 0, 'L');
          $pdf->SetY($y);
          $pdf->SetX($x=$x+40);
          $pdf->cell(17, 5, date('d-m-Y', strtotime($query_row['tgl_lahir'])), 1, 0, 'C');
          $pdf->SetY($y);
          $pdf->SetX($x=$x+17);
          $pdf->cell(20, 5, duit($query_row['kredit_jumlah']), 1, 0, 'C');
          $pdf->SetY($y);
          $pdf->SetX($x=$x+20);
          $pdf->cell(10, 5, $query_row['kredit_tenor'], 1, 0, 'C');
          $pdf->SetY($y);
          $pdf->SetX($x=$x+10);
          $pdf->cell(17, 5, date('d-m-Y', strtotime($query_row['tgl_klaim'])), 1, 0, 'C');
          $pdf->SetY($y);
          $pdf->SetX($x=$x+17);
          $pdf->cell(17, 5, $approve_date, 1, 0, 'C');
          $pdf->SetY($y);
          $pdf->SetX($x=$x+17);
          $pdf->cell(17, 5, $tgl_lapor_klaim, 1, 0, 'C');
          $pdf->SetY($y);
          $pdf->SetX($x=$x+17);
          $pdf->cell(10, 5, $query_row['lamaterimalaporan'], 1, 0, 'C');
          $pdf->SetY($y);
          $pdf->SetX($x=$x+10);
          $pdf->cell(17, 5, $tgl_document_lengkap, 1, 0, 'C');
          $pdf->SetY($y);
          $pdf->SetX($x=$x+17);
          $pdf->cell(10, 5, $query_row['lamapengumpulandok'], 1, 0, 'C');
          $pdf->SetY($y);
          $pdf->SetX($x=$x+10);
          $pdf->cell(17, 5, $tgl_kirim_dokumen, 1, 0, 'C');
          $pdf->SetY($y);
          $pdf->SetX($x=$x+17);
          $pdf->cell(10, 5, $query_row['lamaprosesklaim'], 1, 0, 'C');
          $pdf->SetY($y);
          $pdf->SetX($x=$x+10);
          $pdf->cell(35, 5, $id_klaim_status, 1, 0, 'C');
          $pdf->SetY($y);
          $pdf->SetX($x=$x+35);
          $pdf->cell(10, 5, 'day', 1, 0, 'C');
          $pdf->SetY($y);
          $pdf->SetX($x=$x+10);
          $pdf->cell(17, 5, $tgl_bayar_asuransi, 1, 0, 'C');
          if ($pagebreak == 1 and $y > 170) {
              $pdf->AddPage();
              $y = 15;
              $x = 1;
              $pdf->SetFont('Arial', '', 7);
              $pdf->SetY($y);
              $pdf->SetX($x);
              $pdf->cell(10, 10, 'No (A)', 1, 0, 'C');
              $pdf->SetY($y);
              $pdf->SetX($x=$x+10);
              $pdf->cell(20, 10, 'ID Peserta (B)', 1, 0, 'C');
              $pdf->SetY($y);
              $pdf->SetX($x=$x+20);
              $pdf->cell(40, 10, 'Nama (C)', 1, 0, 'C');
              $pdf->SetY($y);
              $pdf->SetX($x=$x+40);
              $pdf->cell(17, 10, 'Tgl Lahir (D)', 1, 0, 'C');
              $pdf->SetY($y);
              $pdf->SetX($x=$x+17);
              $pdf->cell(20, 10, 'Plafond (E)', 1, 0, 'C');
              $pdf->SetY($y);
              $pdf->SetX($x=$x+20);
              $pdf->MultiCell(10, 5, 'Tenor (F)', 1, 'C');
              $pdf->SetY($y);
              $pdf->SetX($x=$x+10);
              $pdf->MultiCell(17, 5, 'Tgl Meninggal(G)', 1, 'C');
              $pdf->SetY($y);
              $pdf->SetX($x=$x+17);
              $pdf->cell(34, 5, 'Tgl Lapor', 1, 0, 'C');
              $pdf->SetY($y=$y+5);
              $pdf->SetX($x=$x);
              $pdf->cell(17, 5, 'Bank (H)', 1, 0, 'C');
              $pdf->SetY($y);
              $pdf->SetX($x=$x+17);
              $pdf->cell(17, 5, 'Asuransi (I)', 1, 0, 'C');
              $pdf->SetY($y=$y-5);
              $pdf->SetX($x=$x+17);
              $pdf->MultiCell(10, 5, 'Lama (G-H)', 1, 'C');
              $pdf->SetY($y);
              $pdf->SetX($x=$x+10);
              $pdf->MultiCell(17, 5, 'Tgl Dok Lengkap (K)', 1, 'C');
              $pdf->SetY($y);
              $pdf->SetX($x=$x+17);
              $pdf->MultiCell(10, 5, 'Lama (G-K)', 1, 'C');
              $pdf->SetY($y);
              $pdf->SetX($x=$x+10);
              $pdf->MultiCell(17, 5, 'Tgl kirim asuransi (M)', 1, 'C');
              $pdf->SetY($y);
              $pdf->SetX($x=$x+17);
              $pdf->MultiCell(10, 5, 'Lama (K-M)', 1, 'C');
              $pdf->SetY($y);
              $pdf->SetX($x=$x+10);
              $pdf->cell(45, 5, 'Status', 1, 0, 'C');
              $pdf->SetY($y=$y+5);
              $pdf->SetX($x=$x);
              $pdf->cell(35, 5, 'keterangan (O)', 1, 0, 'C');
              $pdf->SetY($y);
              $pdf->SetX($x=$x+35);
              $pdf->cell(10, 5, 'Day (P)', 1, 0, 'C');
              $pdf->SetY($y=$y-5);
              $pdf->SetX($x=$x+10);
              $pdf->MultiCell(17, 5, 'Tgl asuransi bayar (Q)', 1, 'C');
              $y=$y+5;
          } else {
              if ($no == 30) {
                  $pdf->AddPage();
                  $y = 20;
                  $x = 1;
                  $pdf->SetFont('Arial', '', 7);
                  $pdf->SetY($y);
                  $pdf->SetX($x);
                  $pdf->cell(10, 10, 'No (A)', 1, 0, 'C');
                  $pdf->SetY($y);
                  $pdf->SetX($x=$x+10);
                  $pdf->cell(20, 10, 'ID Peserta (B)', 1, 0, 'C');
                  $pdf->SetY($y);
                  $pdf->SetX($x=$x+20);
                  $pdf->cell(40, 10, 'Nama (C)', 1, 0, 'C');
                  $pdf->SetY($y);
                  $pdf->SetX($x=$x+40);
                  $pdf->cell(17, 10, 'Tgl Lahir (D)', 1, 0, 'C');
                  $pdf->SetY($y);
                  $pdf->SetX($x=$x+17);
                  $pdf->cell(20, 10, 'Plafond (E)', 1, 0, 'C');
                  $pdf->SetY($y);
                  $pdf->SetX($x=$x+20);
                  $pdf->MultiCell(10, 5, 'Tenor (F)', 1, 'C');
                  $pdf->SetY($y);
                  $pdf->SetX($x=$x+10);
                  $pdf->MultiCell(17, 5, 'Tgl Meninggal(G)', 1, 'C');
                  $pdf->SetY($y);
                  $pdf->SetX($x=$x+17);
                  $pdf->cell(34, 5, 'Tgl Lapor', 1, 0, 'C');
                  $pdf->SetY($y=$y+5);
                  $pdf->SetX($x=$x);
                  $pdf->cell(17, 5, 'Bank (H)', 1, 0, 'C');
                  $pdf->SetY($y);
                  $pdf->SetX($x=$x+17);
                  $pdf->cell(17, 5, 'Asuransi (I)', 1, 0, 'C');
                  $pdf->SetY($y=$y-5);
                  $pdf->SetX($x=$x+17);
                  $pdf->MultiCell(10, 5, 'Lama (G-H)', 1, 'C');
                  $pdf->SetY($y);
                  $pdf->SetX($x=$x+10);
                  $pdf->MultiCell(17, 5, 'Tgl Dok Lengkap (K)', 1, 'C');
                  $pdf->SetY($y);
                  $pdf->SetX($x=$x+17);
                  $pdf->MultiCell(10, 5, 'Lama (G-K)', 1, 'C');
                  $pdf->SetY($y);
                  $pdf->SetX($x=$x+10);
                  $pdf->MultiCell(17, 5, 'Tgl kirim asuransi (M)', 1, 'C');
                  $pdf->SetY($y);
                  $pdf->SetX($x=$x+17);
                  $pdf->MultiCell(10, 5, 'Lama (K-M)', 1, 'C');
                  $pdf->SetY($y);
                  $pdf->SetX($x=$x+10);
                  $pdf->cell(45, 5, 'Status', 1, 0, 'C');
                  $pdf->SetY($y=$y+5);
                  $pdf->SetX($x=$x);
                  $pdf->cell(35, 5, 'keterangan (O)', 1, 0, 'C');
                  $pdf->SetY($y);
                  $pdf->SetX($x=$x+35);
                  $pdf->cell(10, 5, 'Day (P)', 1, 0, 'C');
                  $pdf->SetY($y=$y-5);
                  $pdf->SetX($x=$x+10);
                  $pdf->MultiCell(17, 5, 'Tgl asuransi bayar (Q)', 1, 'C');
                  $y=$y+5;
                  $pagebreak = 1;
              }
          }
          $no++;
      }

      $pdf->Output("Laporan Klaim Management ".date("d-m-Y").".pdf", "I");
  break;

  case "sendmailkadaluarsapdf":
      $id_klaim = $_REQUEST['id'];
      $reminder_ke = $_GET['rem'];
      //$email = $_REQUEST['email'];
      $qklaim = mysql_fetch_array(mysql_query("select *,DATE_ADD(tgl_klaim,INTERVAL 150 DAY)as due_date from fu_ajk_klaim where id_cn = '".$id_klaim."'"));
      $qpeserta = mysql_fetch_array(mysql_query("select *,(select nmproduk from fu_ajk_polis where fu_ajk_polis.id = fu_ajk_peserta.id_polis)as nm_produk from fu_ajk_peserta where id_klaim = '".$id_klaim."'"));
      $mitra = $qpeserta['nama_mitra'];
      $nm_peserta = $qpeserta['nama'];
      $nm_cabang = $qpeserta['cabang'];
      $tgl_lahir = viewBulanIndo($qpeserta['tgl_lahir']);
      $plafond = duit($qpeserta['kredit_jumlah']);
      $today = viewBulanIndo(date('Y-m-d'));

      $pdf=new FPDF('P', 'mm', 'A4');
      $pdf->Open();
      $pdf->AliasNbPages();
      $pdf->AddPage();
      $y = 25;
      $x = 10;
      $font_size = 11;

      /* PDF */
          if ($_GET['rem']=='1') {
              $no_reminder=$qklaim['no_surat_reminder1'];
              $ket_reminder=$qklaim['ket_surat_reminder1'];
          } elseif ($_GET['rem']=='2') {
              $no_reminder=$qklaim['no_surat_reminder2'];
              $ket_reminder=$qklaim['ket_surat_reminder2'];
          } elseif ($_GET['rem']=='3') {
              $no_reminder=$qklaim['no_surat_reminder3'];
              $ket_reminder=$qklaim['ket_surat_reminder3'];
          }
          $pdf->Image('../image/logo_adonai.png', 50, 5, 100);
          $font_size = 10;
          $pdf->SetFont('Arial', '', $font_size);
          $pdf->SetY($y);$pdf->SetX($x);$pdf->cell(50, 25, 'No.', 0, 0, 'L');$pdf->SetX(40);$pdf->cell(1, 25, ':', 0, 0, 'L');$pdf->SetX(42);$pdf->cell(20, 25, $no_reminder, 0, 0, 'L');	$pdf->SetX(90);	$pdf->cell(100, 25, 'Bekasi, '.$today, 0, 0, 'R');
          $pdf->SetY($y=$y+5);$pdf->SetX($x);$pdf->cell(50, 25, 'Lamp.', 0, 0, 'L');$pdf->SetX(40);$pdf->cell(1, 25, ':', 0, 0, 'L');$pdf->SetX(42);$pdf->cell(20, 25, '-', 0, 0, 'L');
          $pdf->SetY($y = $y+5);$pdf->SetX($x);$pdf->cell(50, 25, 'Perihal', 0, 0, 'L');$pdf->SetX(40);$pdf->cell(1, 25, ':', 0, 0, 'L');$pdf->SetFont('Arial', 'B', $font_size);$pdf->SetX(42);
          $pdf->cell(20, 25, 'Reminder '.$_GET['rem'].' Kelengkapan Dokumen Klaim a.n. : '.$nm_peserta.'', 0, 0, 'L');
          $pdf->SetFont('Arial', '', $font_size);
          $pdf->SetY($y = $y+7);$pdf->SetX($x);$pdf->cell(50, 25, 'Kepada Yth,', 0, 0, 'L');
          $pdf->SetFont('Arial', 'B', $font_size);
          if ($mitra == 3) {
              $pdf->SetY($y = $y+5);
              $pdf->SetX($x);
              $pdf->cell(50, 25, 'Koperasi Simpan Pinjam Mekarsari', 0, 0, 'L');
              $pdf->SetFont('Arial', '', $font_size);
              $pdf->SetY($y = $y+5);
              $pdf->SetX($x);
              $pdf->cell(50, 25, 'Ruko Hexa Green Kalimalang No. 45', 0, 0, 'L');
              $pdf->SetY($y = $y+5);
              $pdf->SetX($x);
              $pdf->cell(50, 25, 'Jl. Pahlawan Revolusi RT 009 RW007', 0, 0, 'L');
              $pdf->SetY($y = $y+5);
              $pdf->SetX($x);
              $pdf->cell(50, 25, 'Kel. Pondok Bambu Kec. Duren Sawit', 0, 0, 'L');
              $pdf->SetY($y = $y+5);
              $pdf->SetX($x);
              $pdf->cell(50, 25, 'Jakarta Timur Telp. (021) ', 0, 0, 'L');
              $pdf->SetFont('Arial', 'B', $font_size);
              $pdf->SetY($y = $y+7);
              $pdf->SetX($x);
              $pdf->cell(50, 25, 'UP', 0, 0, 'L');
              $pdf->SetX(40);
              $pdf->cell(1, 25, ':', 0, 0, 'L');
              $pdf->SetX(42);
              $pdf->cell(100, 25, 'Yth, Bapak Manonga Pasaribu, SE, MM.', 0, 0, 'L');
          } else {
              /*if($_GET['rem']=='3'){
                  $pdf->SetY($y = $y+5);$pdf->SetX($x);$pdf->cell(50,25,'PT. Bank Bukopin, Tbk Capem S. Parman',0,0,'L');
                  $pdf->SetFont('Arial','',$font_size);
                  $pdf->SetY($y = $y+5);$pdf->SetX($x);$pdf->cell(50,25,'Bisnis Mikro',0,0,'L');

                  $qcabang = mysql_fetch_array(mysql_query("select * from fu_ajk_cacang where name = '".$nm_cabang."'"));

                  $pdf->SetY($y = $y+5);$pdf->SetX($x);$pdf->cell(50,25,'Jl. S. Parman Kav. 80, Slipi,',0,0,'L');
                  $pdf->SetY($y = $y+5);$pdf->SetX($x);$pdf->cell(50,25,'Jakarta 11460',0,0,'L');
                  $pdf->SetFont('Arial','B',$font_size);
                  $pdf->SetY($y = $y+10);$pdf->SetX($x);$pdf->cell(50,25,'UP',0,0,'L');$pdf->SetX(40);$pdf->cell(1,25,':',0,0,'L');$pdf->SetX(42);$pdf->cell(100,25,'Yth. Bapak Agny Irsyad',0,0,'L');
              }else{*/

              $pdf->SetY($y = $y+5);
              $pdf->SetX($x);
              $pdf->cell(50, 25, 'PT. Bank Bukopin Tbk, Kredit Mikro', 0, 0, 'L');
              $pdf->SetFont('Arial', '', $font_size);
              $pdf->SetY($y = $y+5);
              $pdf->SetX($x);
              $pdf->cell(50, 25, 'Cabang '.$nm_cabang, 0, 0, 'L');

              $qcabang = mysql_fetch_array(mysql_query("select * from fu_ajk_cabang where name = '".$nm_cabang."'"));

              $pdf->SetY($y = $y+5);
              $pdf->SetX($x);
              $pdf->cell(50, 25, $qcabang['address'], 0, 0, 'L');
              $pdf->SetY($y = $y+5);
              $pdf->SetX($x);
              $pdf->cell(50, 25, $qcabang['address2'], 0, 0, 'L');
              $pdf->SetFont('Arial', 'B', $font_size);
              $pdf->SetY($y = $y+7);
              $pdf->SetX($x);
              $pdf->cell(50, 25, 'UP', 0, 0, 'L');
              $pdf->SetX(40);
              $pdf->cell(1, 25, ':', 0, 0, 'L');
              $pdf->SetX(42);
              $pdf->cell(100, 25, 'Yth. Bapak/Ibu Manager Pelayanan dan Operasional Cabang '.$nm_cabang, 0, 0, 'L');
              /*}*/
          }
          $pdf->SetFont('Arial', '', $font_size);
          $pdf->SetY($y = $y+7);$pdf->SetX($x);$pdf->cell(50, 25, 'Dengan Hormat, ', 0, 0, 'L');
          $pdf->SetY($y = $y+7);$pdf->SetX($x);$pdf->cell(50, 25, 'Pertama-tama kami mengucapkan terima kasih atas kerjasama yang yang telah terjalin dengan sangat baik ', 0, 0, 'L');
          $pdf->SetY($y = $y+5);$pdf->SetX($x);$pdf->cell(50, 25, 'selama ini antara PT. Bank Bukopin, Tbk dan PT. Adonai Pialang Asuransi.', 0, 0, 'L');
          $pdf->SetY($y = $y+7);$pdf->SetX($x);$pdf->cell(50, 25, 'Sehubungan dengan dokumen klaim yang kami terima dengan data sebagai berikut : ', 0, 0, 'L');
          $pdf->SetFont('Arial', 'B', $font_size);
          $pdf->SetY($y = $y+5);$pdf->SetX($x+5);$pdf->cell(50, 25, 'Nama', 0, 0, 'L');
          $pdf->SetX($x+50);$pdf->cell(50, 25, ':', 0, 0, 'L');
          $pdf->SetX($x+55);$pdf->cell(50, 25, $nm_peserta, 0, 0, 'L');
          $pdf->SetY($y = $y+5);$pdf->SetX($x+5);$pdf->cell(50, 25, 'Tanggal Lahir', 0, 0, 'L');
          $pdf->SetX($x+50);$pdf->cell(50, 25, ':', 0, 0, 'L');
          $pdf->SetX($x+55);$pdf->cell(50, 25, $tgl_lahir, 0, 0, 'L');
          $pdf->SetY($y = $y+5);$pdf->SetX($x+5);$pdf->cell(50, 25, 'Plafond', 0, 0, 'L');
          $pdf->SetX($x+50);$pdf->cell(50, 25, ':', 0, 0, 'L');
          $pdf->SetX($x+55);$pdf->cell(50, 25, $plafond, 0, 0, 'L');
          $pdf->SetY($y = $y+5);$pdf->SetX($x+5);$pdf->cell(50, 25, 'Tanggal Akad', 0, 0, 'L');
          $pdf->SetX($x+50);$pdf->cell(50, 25, ':', 0, 0, 'L');
          $pdf->SetX($x+55);$pdf->cell(50, 25, viewBulanIndo($qpeserta['kredit_tgl']), 0, 0, 'L');
          $pdf->SetFont('Arial', '', $font_size);
          $pdf->SetY($y = $y+7);$pdf->SetX($x);$pdf->cell(50, 25, 'Kami sampaikan kekurangan dokumen klaim yang terdiri dari :', 0, 0, 'L');
          $pdf->SetFont('Arial', 'B', $font_size);
          $pdf->SetY($y = $y+15);$pdf->SetX($x+10);$pdf->Multicell(180, 5, $ket_reminder, 0);
          //$pdf->AddPage();
          $pdf->SetFont('Arial', '', $font_size);
          $pdf->SetY($y = $pdf->getY()+5);$pdf->SetX($x);$pdf->Multicell(180, 5, 'Mohon agar kelengkapan dokumen klaim tersebut dikirimkan kepada kami sebelum tanggal '.viewBulanIndo($qklaim['due_date']).', apabila dokumen kami terima setelah tanggal tersebut klaim ini tidak dapat ditindaklanjuti karena sudah melewati batas waktu yang ditentukan.', 0);

          $pdf->SetFont('Arial', '', $font_size);
          $pdf->SetY($y = $y+7);$pdf->SetX($x);$pdf->cell(200, 25, 'Demikian informasi ini kami sampaikan, atas perhatian dan kerjasamanya kami mengucapkan terima kasih.', 0, 0, 'L');
          $pdf->SetY($y = $y+10);$pdf->SetX($x);$pdf->cell(200, 25, 'Hormat kami,', 0, 0, 'L');
          $pdf->SetFont('Arial', 'B', $font_size);
          $pdf->SetY($y = $y+5);$pdf->SetX($x);$pdf->cell(50, 25, 'PT. Adonai Pialang Asuransi', 0, 0, 'L');
          $pdf->SetFont('Arial', 'BU', $font_size);
          // $pdf->Image('../image/ttd_dessy.jpg', 10, $pdf->getY()+15, -100);
          $pdf->Image('../image/ttd_dessy.png', 5, $pdf->getY()+15, -140);
          $pdf->SetY($y = $y+25);$pdf->SetX($x);$pdf->cell(200, 25, 'Dessy Puji Astuti', 0, 0, 'L');
          $pdf->SetFont('Arial', 'B', $font_size);
          $pdf->SetY($y = $y+5);$pdf->SetX($x);$pdf->cell(200, 25, 'Manager Claim', 0, 0, 'L');

          $pdf->SetFont('Arial', '', $font_size);
          $pdf->SetY($y = $y+7);$pdf->SetX($x);$pdf->cell(200, 25, 'Tembusan : Yth. Bapak Zulfikar Andiko - Kadiv Managemen Operasional', 0, 0, 'L');
      /* END PDF */

      $qcabang = mysql_query("select * from fu_ajk_cabang where name = '".$qpeserta['cabang']."' and del is null and id_cost = 1");
      if (mysql_num_rows($qcabang) > 0) {
          $qcabang_row = mysql_fetch_array($qcabang);
          //cek sentralisasi
          // if ($qcabang_row['centralcbg']=="") {
          $qemailto = mysql_query("select * from fu_ajk_email_cabang where id_cabang = (select id from fu_ajk_cabang where name = '".$qpeserta['cabang']."' and del is null and id_cost = 1)");
          if (mysql_num_rows($qemailto) == 0) {
            $qemailtoaa = mysql_query("select * from fu_ajk_email_cabang where id_cabang = (select name from fu_ajk_regional where id = (select id_reg from fu_ajk_cabang where name = '".$qpeserta['cabang']."' and del is null and id_cost = 1))");
          }
          // } else {
          //     $qemailto = mysql_query("select * from fu_ajk_email_cabang where id_cabang = 4");
          // }
      } else {
          $qemailto = mysql_query("select * from fu_ajk_email_cabang where id_cabang = 4");
      }

      $pdfdoc = $pdf->Output('', 'S');
      $mail	= new PHPMailer; // call the class
      $mail->IsSMTP();
      $mail->Host = SMTP_HOST; //Hostname of the mail server
      $mail->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
      $mail->Username = SMTP_UNAME; //Username for SMTP authentication any valid email created in your domain
      $mail->Password = SMTP_PWORD; //Password for SMTP authentication
      $mail->AddReplyTo = SMTP_REPLYMAIL; //reply-to address
      $mail->debug = 1;
      $mail->SMTPSecure = "ssl";
      $mail->IsHTML(true);
      $mail->SetFrom('klaim@adonai.co.id', 'Adonai Notification [no reply]');
      $mail->Subject = "[App AJK] Reminder Kelengkapan Dokumen Klaim ke ".$reminder_ke.' an '.$nm_peserta.'('.$qpeserta['cabang'].')'; //Subject od your mail
      //$mail->AddAddress('hansen@adonai.co.id'); //To address who will receive this email

      // while ($qemailto_row = mysql_fetch_array($qemailto)) {
      //     $mail->AddAddress($qemailto_row['email']);
      //     $tomail.= $qemailto_row['email'].', ';
      // }
      if (mysql_num_rows($qcabang) > 0) {
        if (mysql_num_rows($qemailto) == 0) {
          while ($qemailtoaa_row = mysql_fetch_array($qemailtoaa)) {
            $mail->AddAddress($qemailtoaa_row['email']);
            $tomail.= $qemailtoaa_row['email'].', ';
          }
        }else{
          while ($qemailto_row = mysql_fetch_array($qemailto)) {
            $mail->AddAddress($qemailto_row['email']);
            $tomail.= $qemailto_row['email'].', ';
          }
        }
          // if ($qcabang_row['centralcbg']=="") {
          //     while ($qemailto_row = mysql_fetch_array($qemailto)) {
          //         $mail->AddAddress($qemailto_row['email']);
          //         $tomail.= $qemailto_row['email'].', ';
          //     }
          // } else {
          //     while ($qemailto_row = mysql_fetch_array($qemailto)) {
          //         $mail->AddAddress($qemailto_row['email']);
          //         $tomail.= $qemailto_row['email'].', ';
          //     }
          //     while ($qemailtoaa_row = mysql_fetch_array($qemailtoaa)) {
          //         $mail->AddAddress($qemailtoaa_row['email']);
          //         $tomail.= $qemailtoaa_row['email'].', ';
          //     }
          // }
      } else {
          while ($qemailto_row = mysql_fetch_array($qemailto)) {
              $mail->AddAddress($qemailto_row['email']);
              $tomail.= $qemailto_row['email'].', ';
          }
      }
      // $mail->AddCC("klaim@adonai.co.id", "Klaim Adonai");
      // $mail->AddCC("asuransi.dmom@gmail.com");
      // $mail->AddCC("asriany1508@gmail.com");
      // $mail->AddCC("zulfikar.andiko@bukopin.co.id");
      // $mail->AddCC("trisno0866@gmail.com");
      // $mail->AddCC("asri.nasrani@adonai.co.id", "Adonai Klaim");
      // $mail->AddCC("rohaida@adonai.co.id", "Adonai Klaim");
      // $mail->AddCC("mikha@adonai.co.id", "Adonai Klaim");

      $mail->AddAddress("klaim@adonai.co.id", "Klaim Adonai");
      $mail->AddCC("asuransi.dmom@gmail.com");
      $mail->AddCC("asriany1508@gmail.com");
      $mail->AddCC("asri.nasrani@adonai.co.id", "Adonai Klaim");
      $mail->AddCC("rohaida@adonai.co.id", "Adonai Klaim");
      $mail->AddCC("mikha@adonai.co.id", "Adonai Klaim");


      //$mail->MsgHTML("<b>Test SMTP MAIL ADONAI !.. <br/><br/>by <a href='#'>Hansen</a></b>"); //Put your body of the message you can place html code here
      $mail->MsgHTML("Dear Bp/Ibu Manager Pelayanan dan Operasional. <br><br>Berikut kami sampaikan surat Reminder Kelengkapan Dokumen Klaim.<br><br>Mohon dapat ditindak lanjuti kembali. Atas perhatian dan kerjasamanya diucapkan terimakasih.<br><br><br>Hormat kami,<br><br><br>Tim Klaim Adonai<br><br>Telp : 021 8690 9090, Fax : 021 8690 8849<br><a href='#'>klaim@adonai.co.id</a>");

      $mail->addStringAttachment($pdfdoc, 'reminder-'.$reminder_ke.'.pdf');

      $send = $mail->Send(); //Send the mails
      if ($send) {
          echo '<center><h3 style="color:#009933;">Mail sent successfully</h3></center> <meta http-equiv="refresh" content="3;URL=ajk_claim.php?d=kadaluarsa">';
      } else {
          echo '<center><h3 style="color:#FF3300;">Mail error: </h3></center>'.$mail->ErrorInfo;
      }
  break;

  case "resendmailklaimawalpdf":
      $metklaim = mysql_query(' SELECT  fu_ajk_klaim.id as klaim_id,
                                    fu_ajk_asuransi.id as id_asuransi,
                                    fu_ajk_asuransi.`name` as nmasuransi,
                                    fu_ajk_asuransi.code as asuransi,
                                    fu_ajk_klaim.no_email,
                                    fu_ajk_klaim.no_polis,
                                    fu_ajk_polis.nmproduk,
                                    fu_ajk_klaim.tgl_lapor_klaim,
                                    date_format(fu_ajk_klaim.tgl_lapor_klaim,"%d-%m-%Y")as tgl_lapor_bak,
                                    fu_ajk_cn.id_peserta,
                                    fu_ajk_peserta.nama,
                                    date_format(fu_ajk_peserta.tgl_lahir,"%d-%m-%Y")as tgl_lahir,
                                    fu_ajk_peserta.cabang,
                                    fu_ajk_peserta.kredit_tgl,
                                    date_format(fu_ajk_peserta.kredit_tgl,"%d-%m-%Y")as tgl_akad,
                                    date_format(fu_ajk_peserta.kredit_akhir,"%d-%m-%Y")as tgl_akhir_kredit,
                                    fu_ajk_peserta.kredit_jumlah,
                                    fu_ajk_peserta.kredit_tenor,
                                    date_format(fu_ajk_cn.tgl_claim,"%d-%m-%Y")as tgl_meninggal,
                                    fu_ajk_cn.tgl_claim,
                                    fu_ajk_cn.tuntutan_klaim,
                                    date_format(curdate(),"%d-%m-%Y")as tgl_lapor
                            FROM fu_ajk_cn
                            INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
                            INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
                            INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta AND fu_ajk_cn.id = fu_ajk_peserta.id_klaim
                            INNER JOIN fu_ajk_polis ON fu_ajk_peserta.id_polis = fu_ajk_polis.id
                            LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
                            WHERE fu_ajk_cn.type_claim = "Death" AND
                                  fu_ajk_cn.confirm_claim <> "Pending" AND
                                  fu_ajk_cn.del IS NULL and
                                  fu_ajk_klaim.no_email="'.$_GET['noe'].'"');

      /* PDF ATTACHMENT */
          $pdf=new FPDF('L', 'mm', 'A4');
          $pdf->Open();
          $pdf->AliasNbPages();
          $pdf->AddPage();
          $font_size = 8;
          $pdf->SetFont('Arial', '', $font_size);

          $pdf->cell(8, 5, 'No.', 1, 0, 'C');
          $pdf->cell(30, 5, 'Produk', 1, 0, 'C');
          $pdf->cell(20, 5, 'ID Peserta', 1, 0, 'C');
          $pdf->cell(45, 5, 'Nama Peserta', 1, 0, 'C');
          $pdf->cell(20, 5, 'Tgl Lahir', 1, 0, 'C');
          $pdf->cell(30, 5, 'Cabang', 1, 0, 'C');
          $pdf->cell(20, 5, 'Tgl Akad', 1, 0, 'C');
          $pdf->cell(20, 5, 'Tgl Akhir', 1, 0, 'C');
          $pdf->cell(20, 5, 'Tenor', 1, 0, 'C');
          $pdf->cell(20, 5, 'Plafond', 1, 0, 'C');
          $pdf->cell(20, 5, 'Tgl Meninggal', 1, 0, 'C');
          $pdf->cell(20, 5, 'Tuntutan Klaim', 1, 0, 'C');
          $y = 20;
          $no = 1;

          $jml_peserta=mysql_num_rows($metklaim);
          $jml=1;

          while ($metklaim_ = mysql_fetch_array($metklaim)) {
              $tgl_lapor = $metklaim_['tgl_lapor_bak'];
              $pdf->SetY($y=$y+5);
              $pdf->cell(8, 5, $no, 1, 0, 'C');
              $pdf->cell(30, 5, $metklaim_['nmproduk'], 1, 0, 'C');
              $pdf->cell(20, 5, $metklaim_['id_peserta'], 1, 0, 'C');
              $pdf->cell(45, 5, $metklaim_['nama'], 1, 0, 'C');
              $pdf->cell(20, 5, $metklaim_['tgl_lahir'], 1, 0, 'C');
              $pdf->cell(30, 5, $metklaim_['cabang'], 1, 0, 'C');
              $pdf->cell(20, 5, $metklaim_['tgl_akad'], 1, 0, 'C');
              $pdf->cell(20, 5, $metklaim_['tgl_akhir_kredit'], 1, 0, 'C');
              $pdf->cell(20, 5, $metklaim_['kredit_tenor'], 1, 0, 'C');
              $pdf->cell(20, 5, duit($metklaim_['kredit_jumlah']), 1, 0, 'C');
              $pdf->cell(20, 5, $metklaim_['tgl_meninggal'], 1, 0, 'C');
              $pdf->cell(20, 5, duit($metklaim_['tuntutan_klaim']), 1, 0, 'C');
              $no++;
              $id_asuransi = $metklaim_['id_asuransi'];

              if ($jml_peserta>1) {
                  if ($jml==$jml_peserta) {
                      $nama=substr($nama, 0, -2).' dan '.$metklaim_['nama'];
                  } else {
                      $nama.=$metklaim_['nama'].', ';
                  }
              } else {
                  $nama.=$metklaim_['nama'].'';
              }

              $email=$email.$jml.'. '.$metklaim_['nama'].' ('.$metklaim_['nmproduk'].' '.$metklaim_['no_polis'].' '.viewBulanIndo(date_format(date_create($metklaim_['kredit_tgl']), 'm')).' '.date_format(date_create($metklaim_['kredit_tgl']), 'Y').')<br>';
              //$email=$email.$metklaim_['nama'].' ('.$metklaim_['nmproduk'].' '.viewBulanIndo(date_format(date_create($metklaim_['kredit_tgl']), 'm')).' ';
              $jml++;
          }
          $pdfdoc = $pdf->Output('', 'S');
      /* PDF ATTACHMENT END */

      $data_klaim=mysql_fetch_array(mysql_query("select email_klaim from fu_ajk_asuransi where id=".$id_asuransi));

      $data = json_decode($data_klaim['email_klaim'], true);

      for ($x=0;$x<=count($data['email'])-1;$x++) {
          $to_email.=$data['email'][$x]['nama'].'('.$data['email'][$x]['email1'].'),';
          $to_mail.=$data['email'][$x]['email1'];
      }

      $message = '<html>
                <head>
                  <title>AJKOnline -  Pengajuan Klaim AJK PT Bank Bukopin, Tbk a.n. '.$nama.'</title>
                </head>
                <body>
                  <p style="padding-left: 10%;padding-right: 10%;">
                    <b>Yth. '.$data['pic'].'</b>
                    <br><br>
                    Berdasarkan informasi yang kami dapat dari Bank Bukopin Jakarta, bersama ini perlu di sampaikan pengajuan klaim (data terlampir) untuk debitur di bawah ini :<br><br>
                    '.$email.'
                    <br>
                    Adapun kelengkapan dokumen klaim akan segera di sampaikan, setelah kami menerima secara lengkap dan valid dari Bank Bukopin.<br>
                    Demikian disampaikan. Atas perhatian dan kerjasamanya, diucapkan terima kasih
                    <br><br>
                    Hormat kami,
                    <br><br><br>
                    '.$_REQUEST['user'].'
                    <br>
                    (Send to Insurance : '.$tgl_lapor.')
                  </p>
                </body>
              </html>';

      /* SEND EMAIL */
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
      $mail->SetFrom('klaim@adonai.co.id', 'Adonai Notification [no reply]');
      $mail->Subject = "[App AJK] Resend Pengajuan Klaim AJK PT Bank Bukopin, Tbk a.n. ".$nama; //Subject od your mail

      // for ($x=0;$x<=count($data['email'])-1;$x++) {
      //     $mail->AddAddress($data['email'][$x]['email1'], $data['email'][$x]['nama']);
      // }
      // $mail->AddCC("klaim@adonai.co.id", "Klaim Adonai");
      // $mail->AddAddress("hansen@adonai.co.id", "Adonai");
      $mail->AddCC("asri.nasrani@adonai.co.id", "Adonai Klaim");
      $mail->AddAddress("rohaida@adonai.co.id", "Adonai Klaim");
      $mail->AddCC("mikha@adonai.co.id", "Adonai Klaim");

      $mail->MsgHTML($message);

      $mail->addStringAttachment($pdfdoc, $_REQUEST['noe'].'.pdf');

      $send = $mail->Send(); //Send the mails
      if ($send) {
          //     $today = date("Y-m-d");
          //     mysql_query("update fu_ajk_klaim set tgl_lapor_klaim = '".$today."' where no_email = '".$_REQUEST['noe']."'");
          echo '<center><h3 style="color:#009933;">Mail sent successfully</h3></center>';
      } else {
          echo '<center><h3 style="color:#FF3300;">Mail error: </h3></center>'.$mail->ErrorInfo;
      }
  break;

  case "sendmailklaimawalpdf":
      $metklaim = mysql_query('	SELECT  fu_ajk_klaim.id as klaim_id,
																		fu_ajk_asuransi.id as id_asuransi,
																		fu_ajk_asuransi.`name` as nmasuransi,
																		fu_ajk_asuransi.code as asuransi,
																		fu_ajk_klaim.no_email,
                                    fu_ajk_klaim.no_polis,
																		fu_ajk_polis.nmproduk,
																		fu_ajk_klaim.tgl_lapor_klaim,
																		date_format(fu_ajk_klaim.tgl_lapor_klaim,"%d-%m-%Y")as tgl_lapor_bak,
																		fu_ajk_cn.id_peserta,
																		fu_ajk_peserta.nama,
																		date_format(fu_ajk_peserta.tgl_lahir,"%d-%m-%Y")as tgl_lahir,
																		fu_ajk_peserta.cabang,
																		fu_ajk_peserta.kredit_tgl,
																		date_format(fu_ajk_peserta.kredit_tgl,"%d-%m-%Y")as tgl_akad,
																		date_format(fu_ajk_peserta.kredit_akhir,"%d-%m-%Y")as tgl_akhir_kredit,
																		fu_ajk_peserta.kredit_jumlah,
																		fu_ajk_peserta.kredit_tenor,
																		date_format(fu_ajk_cn.tgl_claim,"%d-%m-%Y")as tgl_meninggal,
																		fu_ajk_cn.tgl_claim,
																		fu_ajk_cn.tuntutan_klaim,
																		date_format(curdate(),"%d-%m-%Y")as tgl_lapor
														FROM fu_ajk_cn
														INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_dn = fu_ajk_dn.id
														INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
														INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta AND fu_ajk_cn.id = fu_ajk_peserta.id_klaim
														INNER JOIN fu_ajk_polis ON fu_ajk_peserta.id_polis = fu_ajk_polis.id
														LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
														WHERE fu_ajk_cn.type_claim = "Death" AND
																	fu_ajk_cn.confirm_claim <> "Pending" AND
																	fu_ajk_cn.del IS NULL and
																	fu_ajk_klaim.no_email="'.$_GET['noe'].'"');

      /* PDF ATTACHMENT */
          $pdf=new FPDF('L', 'mm', 'A4');
          $pdf->Open();
          $pdf->AliasNbPages();
          $pdf->AddPage();
          $font_size = 8;
          $pdf->SetFont('Arial', '', $font_size);

          $pdf->cell(8, 5, 'No.', 1, 0, 'C');
          $pdf->cell(30, 5, 'Produk', 1, 0, 'C');
          $pdf->cell(20, 5, 'ID Peserta', 1, 0, 'C');
          $pdf->cell(45, 5, 'Nama Peserta', 1, 0, 'C');
          $pdf->cell(20, 5, 'Tgl Lahir', 1, 0, 'C');
          $pdf->cell(30, 5, 'Cabang', 1, 0, 'C');
          $pdf->cell(20, 5, 'Tgl Akad', 1, 0, 'C');
          $pdf->cell(20, 5, 'Tgl Akhir', 1, 0, 'C');
          $pdf->cell(20, 5, 'Tenor', 1, 0, 'C');
          $pdf->cell(20, 5, 'Plafond', 1, 0, 'C');
          $pdf->cell(20, 5, 'Tgl Meninggal', 1, 0, 'C');
          $pdf->cell(20, 5, 'Tuntutan Klaim', 1, 0, 'C');
          $y = 20;
          $no = 1;

          $jml_peserta=mysql_num_rows($metklaim);
          $jml=1;

          while ($metklaim_ = mysql_fetch_array($metklaim)) {
              $pdf->SetY($y=$y+5);
              $pdf->cell(8, 5, $no, 1, 0, 'C');
              $pdf->cell(30, 5, $metklaim_['nmproduk'], 1, 0, 'C');
              $pdf->cell(20, 5, $metklaim_['id_peserta'], 1, 0, 'C');
              $pdf->cell(45, 5, $metklaim_['nama'], 1, 0, 'C');
              $pdf->cell(20, 5, $metklaim_['tgl_lahir'], 1, 0, 'C');
              $pdf->cell(30, 5, $metklaim_['cabang'], 1, 0, 'C');
              $pdf->cell(20, 5, $metklaim_['tgl_akad'], 1, 0, 'C');
              $pdf->cell(20, 5, $metklaim_['tgl_akhir_kredit'], 1, 0, 'C');
              $pdf->cell(20, 5, $metklaim_['kredit_tenor'], 1, 0, 'C');
              $pdf->cell(20, 5, duit($metklaim_['kredit_jumlah']), 1, 0, 'C');
              $pdf->cell(20, 5, $metklaim_['tgl_meninggal'], 1, 0, 'C');
              $pdf->cell(20, 5, duit($metklaim_['tuntutan_klaim']), 1, 0, 'C');
              $no++;
              $id_asuransi = $metklaim_['id_asuransi'];

              if ($jml_peserta>1) {
                  if ($jml==$jml_peserta) {
                      $nama=substr($nama, 0, -2).' dan '.$metklaim_['nama'];
                  } else {
                      $nama.=$metklaim_['nama'].', ';
                  }
              } else {
                  $nama.=$metklaim_['nama'].'';
              }

              $email=$email.$jml.'. '.$metklaim_['nama'].' '.$metklaim_['no_polis'].' ('.$metklaim_['nmproduk'].' '.viewBulanIndo(date_format(date_create($metklaim_['kredit_tgl']), 'm')).' '.date_format(date_create($metklaim_['kredit_tgl']), 'Y').')<br>';
              //$email=$email.$metklaim_['nama'].' ('.$metklaim_['nmproduk'].' '.viewBulanIndo(date_format(date_create($metklaim_['kredit_tgl']), 'm')).' ';
              $jml++;
          }
          $pdfdoc = $pdf->Output('', 'S');
      /* PDF ATTACHMENT END */

      $data_klaim=mysql_fetch_array(mysql_query("select email_klaim from fu_ajk_asuransi where id=".$id_asuransi));

      $data = json_decode($data_klaim['email_klaim'], true);

      for ($x=0;$x<=count($data['email'])-1;$x++) {
          $to_email.=$data['email'][$x]['nama'].'('.$data['email'][$x]['email1'].'),';
          $to_mail.=$data['email'][$x]['email1'];
      }

      $message = '<html>
								<head>
									<title>AJKOnline -  Pengajuan Klaim AJK PT Bank Bukopin, Tbk a.n. '.$nama.'</title>
								</head>
								<body>
									<p style="padding-left: 10%;padding-right: 10%;">
										<b>Yth. '.$data['pic'].'</b>
										<br><br>
										Berdasarkan informasi yang kami dapat dari Bank Bukopin Jakarta, bersama ini perlu di sampaikan pengajuan klaim (data terlampir) untuk debitur di bawah ini :<br><br>
										'.$email.'
										<br>
										Adapun kelengkapan dokumen klaim akan segera di sampaikan, setelah kami menerima secara lengkap dan valid dari Bank Bukopin.<br>
										Demikian disampaikan. Atas perhatian dan kerjasamanya, diucapkan terima kasih
										<br><br>
										Hormat kami,
										<br><br><br>
										'.$_REQUEST['user'].'
										<br>
									</p>
								</body>
							</html>';

      /* SEND EMAIL */
      $mail	= new PHPMailer; // call the class
      $mail->IsSMTP();
      $mail->Host = SMTP_HOST; //Hostname of the mail server
      $mail->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
      $mail->Username = SMTP_UNAME; //Username for SMTP authentication any valid email created in your domain
      $mail->Password = SMTP_PWORD; //Password for SMTP authentication
      $mail->AddReplyTo = SMTP_REPLYMAIL; //reply-to address
      $mail->debug = 1;
      $mail->SMTPSecure = "ssl";
      $mail->IsHTML(true);
      $mail->SetFrom('klaim@adonai.co.id', 'Adonai Notification [no reply]');
      $mail->Subject = "[App AJK] Pengajuan Klaim AJK PT Bank Bukopin, Tbk a.n. ".$nama; //Subject od your mail

      for ($x=0;$x<=count($data['email'])-1;$x++) {
          $mail->AddAddress($data['email'][$x]['email1'], $data['email'][$x]['nama']);
      }
      $mail->AddCC("klaim@adonai.co.id", "Klaim Adonai");
      $mail->AddCC("hansen@adonai.co.id", "Adonai");
      $mail->AddCC("asri.nasrani@adonai.co.id", "Adonai Klaim");
      $mail->AddCC("rohaida@adonai.co.id", "Adonai Klaim");
      $mail->AddCC("mikha@adonai.co.id", "Adonai Klaim");

      $mail->MsgHTML($message);

      $mail->addStringAttachment($pdfdoc, $_REQUEST['noe'].'.pdf');

      $send = $mail->Send(); //Send the mails
      if ($send) {
          $today = date("Y-m-d");
          mysql_query("update fu_ajk_klaim set tgl_lapor_klaim = '".$today."' where no_email = '".$_REQUEST['noe']."'");
          echo '<center><h3 style="color:#009933;">Mail sent successfully</h3></center>';
      } else {
          echo '<center><h3 style="color:#FF3300;">Mail error: </h3></center>'.$mail->ErrorInfo;
      }
  break;

  case "sendmailklaimawalcabangpdf":
      /* PDF ATTACHMENT */
          $pdf=new FPDF('P', 'mm', 'A4');
          //$pdf=new PDF_Code39();
          $pdf->Open();
          $pdf->AliasNbPages();
          $pdf->AddPage();
          $cekLogoHeader = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_logoheader WHERE id="1"'));
          if ($cekLogoHeader['creditnote']=="Y") {
              $pdf->Image('image/adonai_64.gif', 10, 5);
              $pdf->SetFont('helvetica', 'B', 20);
              $pdf->SetTextColor(255, 0, 0);
              $pdf->Text(35, 15, 'A D O N A I');
              $pdf->SetFont('helvetica', '', 14);
              $pdf->SetTextColor(0, 0, 0);
              $pdf->Text(35, 20, 'Pialang Asuransi');
          } else {
              $pdf->SetFont('helvetica', 'B', 20);
              $pdf->SetFont('helvetica', '', 14);
          }

          $met_klaim = mysql_fetch_array(mysql_query('SELECT *,DATE_ADD(tgl_claim,INTERVAL 120 DAY)as date_exp FROM fu_ajk_cn WHERE id="'.$_REQUEST['idC'].'" AND del IS NULL'));
          $met_klaim_ = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_klaim WHERE id_cost="'.$met_klaim['id_cost'].'" AND id_dn="'.$met_klaim['id_dn'].'" AND id_cn="'.$met_klaim['id'].'" AND id_peserta="'.$met_klaim['id_peserta'].'" AND del IS NULL'));
          $met_klaim_polis = mysql_fetch_array(mysql_query('SELECT id, id_cost, nopol, nmproduk, bank_2, cabang_2, rek_2 FROM fu_ajk_polis WHERE id="'.$met_klaim['id_nopol'].'" AND id_cost="'.$met_klaim['id_cost'].'" AND del IS NULL'));
          $met_klaim_peserta = mysql_fetch_array(mysql_query('SELECT *, IF(type_data="SPK", kredit_tenor * 12, kredit_tenor) AS kredit_tenor FROM fu_ajk_peserta WHERE id_cost="'.$met_klaim['id_cost'].'" AND id_polis="'.$met_klaim['id_nopol'].'" AND id_peserta="'.$met_klaim['id_peserta'].'" AND id_dn="'.$met_klaim['id_dn'].'" AND id_klaim="'.$met_klaim['id'].'" AND status_peserta="Death" AND del IS NULL'));
          $met_klaim_dn = mysql_fetch_array(mysql_query('SELECT id, id_cost, id_nopol, dn_kode, dn_status, tgl_dn_paid FROM fu_ajk_dn WHERE id_cost="'.$met_klaim['id_cost'].'" AND id_nopol="'.$met_klaim['id_nopol'].'" AND id="'.$met_klaim['id_dn'].'" AND del IS NULL'));
          $met_penyakit = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_namapenyakit WHERE id="'.$met_klaim['nmpenyakit'].'"'));

          if ($met_klaim['confirm_claim']!="Approve(paid)") {
              $headtagihan = $pdf->Text(68, 30, 'CHECKLIST DOKUMEN KLAIM');
          } else {
              $headtagihan = $pdf->Text(83, 30, 'PEMBAYARAN KLAIM');
          }

          $mets = datediff($met_klaim_peserta['kredit_tgl'], $met_klaim['tgl_claim']);
          $usiapolis = explode(",", $mets);
          $pdf->SetFont('helvetica', 'B', 12);
          $headtagihan;
          $pdf->SetFont('helvetica', 'B', 10);
          $metcost = mysql_fetch_array(mysql_query('SELECT id, name, address FROM fu_ajk_costumer WHERE id="'.$met_klaim['id_cost'].'"'));
          $pdf->Text(10, 50, $metcost['name']);
          $pdf->SetFont('helvetica', '', 10);
          $pdf->Text(10, 55, 'ID Peserta');		$pdf->Text(50, 55, ': '.$met_klaim_peserta['id_peserta']);
          $pdf->Text(10, 60, 'Nama');				$pdf->Text(50, 60, ': '.$met_klaim_peserta['nama']);
          $pdf->Text(10, 65, 'Tanggal Lahir');		$pdf->Text(50, 65, ': '._convertDate($met_klaim_peserta['tgl_lahir']));
          $pdf->Text(10, 70, 'Usia');				$pdf->Text(50, 70, ': '.$met_klaim_peserta['usia'].' Tahun');
          $pdf->Text(10, 75, 'Plafond');			$pdf->Text(50, 75, ': Rp. '.duit($met_klaim_peserta['kredit_jumlah']));
          $pdf->Text(10, 80, 'Tanggal Akad');		$pdf->Text(50, 80, ': '._convertDate($met_klaim_peserta['kredit_tgl']));
          $pdf->Text(10, 85, 'Tanggal Akhir');		$pdf->Text(50, 85, ': '._convertDate($met_klaim_peserta['kredit_akhir']));
          $pdf->Text(10, 90, 'Tenor');				$pdf->Text(50, 90, ': '.$met_klaim_peserta['kredit_tenor'].' Bulan');
          $pdf->Text(10, 95, 'Penyebab Meninggal');$pdf->Text(50, 95, ': '.$met_penyakit['namapenyakit']);
          $pdf->Text(10, 100, 'Lokasi Meninggal');$pdf->Text(50, 100, ': '.$met_klaim_['tempat_meninggal']);

          $pdf->Text(125, 55, 'Nama Produk');		$pdf->Text(165, 55, ': '.$met_klaim_polis['nmproduk']);
          $pdf->Text(125, 60, 'Tanggal DOL');	$pdf->Text(165, 60, ': '._convertDate($met_klaim['tgl_claim']));
          $pdf->Text(125, 65, 'Usia Polis');		$pdf->Text(165, 65, ': '.$usiapolis[0].' Tahun '.$usiapolis[1].' Bulan '.$usiapolis[2].' Hari');
          $pdf->Text(125, 70, 'Cabang');		$pdf->Text(165, 70, ': '.$met_klaim['id_cabang']);
          $pdf->Text(125, 75, 'No.Urut');   $pdf->Text(165, 75, ': '.$met_klaim_['no_urut_klaim']);
          $gdr = explode(',', datediff($met_klaim_peserta['tgl_lahir'], $met_klaim['tgl_claim']));

          $pdf->Text(125, 80, 'Usia Debitur Meninggal');  $pdf->Text(165, 80, ': '.$gdr[0].' Tahun '.$gdr[1].' Bulan '.$gdr[2].' Hari ');

          $y_axis1 = 108;
          $y_initial = 98;
          $pdf->setFont('Arial', '', 7);

          $pdf->setFillColor(233, 233, 233);
          $pdf->setY($y_axis1);
          $pdf->setX(10);

          $pdf->cell(8, 6, 'No', 1, 0, 'C', 1);
          $pdf->cell(75, 6, 'Nama Dokumen', 1, 0, 'C', 1);
          $pdf->cell(15, 6, 'Tgl Terima', 1, 0, 'C', 1);
          $pdf->cell(15, 6, 'Status', 1, 0, 'C', 1);
          $pdf->cell(75, 6, 'Keterangan', 1, 0, 'C', 1);
          $no = 0;
          $row = 6;
          $y = $y_initial + $row;

          $metDok = mysql_query('
    			SELECT tgl_dokumen,
    							ket_dokumen,
    							nama_dok,
    							urut,
    							IF (nama_dokumen IS NULL, "Tidak ada", "Ada") AS dataKlaim
    			FROM fu_ajk_klaim_doc
    					 INNER JOIN fu_ajk_dokumenklaim_bank
    					 ON fu_ajk_dokumenklaim_bank.id = fu_ajk_klaim_doc.dokumen
    					 INNER JOIN fu_ajk_dokumenklaim
    					 ON fu_ajk_dokumenklaim.id = fu_ajk_dokumenklaim_bank.id_dok
    			WHERE fu_ajk_klaim_doc.id_klaim = '.$met_klaim['id'].'
    			and
    				fu_ajk_klaim_doc.del is null
    			ORDER BY urut ASC');
          $pdf->ln();
          $no = 1;
          while ($metDok_ = mysql_fetch_array($metDok)) {
              $y = $pdf->GetY();
              $x = $pdf->GetX();
              $line_width = 75;
              $namadok = 0;
              if ($pdf->GetStringWidth($metDok_['ket_dokumen']) > $line_width or $pdf->GetStringWidth($metDok_['nama_dok']) > $line_width) {
                  $pdf->cell(8, 12, $no, 1, 0, 'C', 0);

                  if ($pdf->GetStringWidth($metDok_['nama_dok']) > $line_width) {
                      $pdf->multicell(75, 6, $metDok_['nama_dok'], 1, 'L', 0);
                      $pdf->SetXY($pdf->GetX()+83, $pdf->GetY()-12);
                      $namadok = 1;
                  } else {
                      $pdf->cell(75, 12, $metDok_['nama_dok'], 1, 0, 'L', 0);
                  }
                  $pdf->cell(15, 12, $metDok_['tgl_dokumen'], 1, 0, 'L', 0);
                  $pdf->cell(15, 12, $metDok_['dataKlaim'], 1, 0, 'C', 0);
                  if ($pdf->GetStringWidth($metDok_['ket_dokumen']) > $line_width) {
                      $pdf->multicell(75, 6, $metDok_['ket_dokumen'], 1, 'L', 0);
                      $namadok = 0;
                  } else {
                      $pdf->cell(75, 12, $metDok_['ket_dokumen'], 1, 0, 'L', 0);
                  }
                  if ($namadok == 1) {
                      $pdf->ln();
                  } else {
                      $pdf->ln(0);
                  }
              } else {
                  $pdf->cell(8, 6, $no, 1, 0, 'C', 0);
                  $pdf->cell(75, 6, $metDok_['nama_dok'], 1, 0, 'L', 0);
                  $pdf->cell(15, 6, $metDok_['tgl_dokumen'], 1, 0, 'L', 0);
                  $pdf->cell(15, 6, $metDok_['dataKlaim'], 1, 0, 'C', 0);
                  $pdf->cell(75, 6, $metDok_['ket_dokumen'], 1, 0, 'L', 0);
                  $pdf->ln();
              }
              $no++;
          }
          $pdf->ln();
          $pdf->SetFont('helvetica', '', 10);
          $pdf->cell(30, 7, '', 0, 0, 'L', 0);
          $pdf->cell(80, 7, ' ', 0, 0, 'L', 0);
          $pdf->cell(1, 7, '', 0, 0, 'L', 0);
          $pdf->cell(75, 7, 'Bekasi, '.$futoday.'', 0, 0, 'L', 0);
          $pdf->setFont('Arial', '', 7);
          $y = $pdf->getY();
          $pdf->setX(10);
          $pdf->setY($y+2);
          $pdf->MultiCell(98, 5, "Keterangan : \n".$met_klaim['keterangan'], 1, 'L', 0);

          $pdf->SetFont('helvetica', 'B', 10);
          $pdf->setX(12);
          $pdf->setY($y+5);
          $pdf->cell(111, 7, '', 0, 0, 'L', 0);	$pdf->cell(75, 7, 'PT. ADONAI PIALANG ASURANSI', 0, 0, 'L', 0);
          $pdf->setFont('Arial', '', 10);
          //$pdf->setY($y=$y+65);

          if ($met_klaim['diftoday'] <= 90) {
              $note = "Mohon melengkapi serta mengirimkan hardcopy sebelum tanggal ".viewBulanIndo($met_klaim['date_exp']);
          } elseif ($met_klaim['diftoday'] > 90 and $met_klaim['diftoday'] <=120) {
              $note = "Klaim tersebut telah kadaluarsa, namun kami akan mengusahakannya dan mohon untuk melengkapi sebelum ".viewBulanIndo($met_klaim['date_exp']);
          } elseif ($met_klaim['diftoday'] > 120) {
              $note = "Klaim tersebut telah kadaluarsa, namun kami akan mengusahakannya dan mohon dapat melengkapi dan mengirimkan dalam waktu dekat";
          }
          $pdf->setY($y=$y+25);
          $pdf->setX(122);
            if ($met_klaim_['id_klaim_status']=="2") {
                $pdf->setFont('Arial', 'B', 10);
                $pdf->MultiCell(75, 5, "Note : ".$note, 0, 'L', 0);
                $pdf->setFont('Arial', '', 10);
            }
          $pdfdoc = $pdf->Output('', 'S');
          //$pdf->Output();
          /* PDF ATTACHMENT END */
          $qpeserta	= mysql_fetch_array(mysql_query("select * from fu_ajk_peserta where id_klaim = '".$_REQUEST['idC']."'"));
          $qcabang = mysql_query("select * from fu_ajk_cabang where name = '".$qpeserta['cabang']."' and del is null and id_cost = '".$qpeserta['id_cost']."'");
          if (mysql_num_rows($qcabang) > 0) {
              $qcabang_row = mysql_fetch_array($qcabang);
              if ($qcabang_row['id_reg'] == 15 or $qcabang_row['id_reg'] == 24 or $qcabang_row['id_reg'] == 17 or $qcabang_row['id_reg'] == 20 or $qcabang_row['id_reg'] == 19 or $qcabang_row['id_reg'] == 18) {
                  $qemailto = mysql_query("select * from fu_ajk_email_cabang where id_cabang = (select name from fu_ajk_regional where id = '".$qcabang_row['id_reg']."')");
              } else {
                  //cek sentralisasi
                  if ($qcabang_row['centralcbg']=="") {
                      $qemailto = mysql_query("select * from fu_ajk_email_cabang where id_cabang = (select id from fu_ajk_cabang where name = '".$qpeserta['cabang']."' and del is null and id_cost = '".$qpeserta['id_cost']."')");
                  } else {
                      $qemailto = mysql_query("select * from fu_ajk_email_cabang where id_cabang = 4");
                      $qemailtoaa = mysql_query("select * from fu_ajk_email_cabang where id_cabang = (select id from fu_ajk_cabang where name = '".$qpeserta['cabang']."' and del is null and id_cost = '".$qpeserta['id_cost']."')");
                  }
              }
          } else {
              $qemailto = mysql_query("select * from fu_ajk_email_cabang where id_cabang = 4");
          }



      $message = '<html>
								<head>
									<title>AJKOnline -  Kelengkapan Dokumen Klaim a.n. '.$qpeserta['nama'].' (Cabang '.$qpeserta['cabang'].')</title>
								</head>
								<body>
									<p style="padding-left: 10%;padding-right: 10%;">
										<b>Dear Bukopin Cabang '.$qpeserta['cabang'].'</b>
										<br><br>
										Berikut kami sampaikan form checklist kelengkapan dokumen klaim a.n. '.$qpeserta['nama'].' sebagai berikut (Terlampir)
										<br>
										Demikian disampaikan. Atas perhatian dan kerjasamanya, diucapkan terima kasih
										<br><br>
										Hormat kami,
										<br><br><br>
										'.$_REQUEST['user'].'
										<br>
									</p>
								</body>
							</html>';

      /* SEND EMAIL */
      $mail	= new PHPMailer; // call the class
      $mail->IsSMTP();
      $mail->Host = SMTP_HOST; //Hostname of the mail server
      $mail->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
      $mail->Username = SMTP_UNAME; //Username for SMTP authentication any valid email created in your domain
      $mail->Password = SMTP_PWORD; //Password for SMTP authentication
      $mail->AddReplyTo = SMTP_REPLYMAIL; //reply-to address
      $mail->debug = 1;
      $mail->SMTPSecure = "ssl";
      $mail->IsHTML(true);
      $mail->SetFrom('klaim@adonai.co.id', 'Adonai Notification [no reply]');
      $mail->Subject = "[App AJK] Kelengkapan Dokumen Klaim a.n. ".$qpeserta['nama']." (Cabang ".$qpeserta['cabang'].")"; //Subject od your mail
      //$mail->AddAddress('hansen@adonai.co.id');
      //    while($qemailto_row = mysql_fetch_array($qemailto)){
      //      $mail->AddAddress($qemailto_row['email']);
      //      $tomail.= $qemailto_row['email'].', ';
      //    }        
     
      if (mysql_num_rows($qcabang) > 0) {
          if ($qcabang_row['centralcbg']=="") {
              while ($qemailto_row = mysql_fetch_array($qemailto)) {
                  $mail->AddAddress($qemailto_row['email']);
                  $tomail.= $qemailto_row['email'].', ';
              }
          } else {
              while ($qemailto_row = mysql_fetch_array($qemailto)) {
                  $mail->AddAddress($qemailto_row['email']);
                  $tomail.= $qemailto_row['email'].', ';
              }
              while ($qemailtoaa_row = mysql_fetch_array($qemailtoaa)) {
                  $mail->AddAddress($qemailtoaa_row['email']);
                  $tomail.= $qemailtoaa_row['email'].', ';
              }
          }
      } else {
          while ($qemailto_row = mysql_fetch_array($qemailto)) {
              $mail->AddAddress($qemailto_row['email']);
              $tomail.= $qemailto_row['email'].', ';
          }
      }

      $mail->AddAddress("klaim@adonai.co.id", "Klaim Adonai");
      $mail->AddCC("asuransi.dmom@gmail.com");
      $mail->AddCC("asriany1508@gmail.com");
      $mail->AddCC("asri.nasrani@adonai.co.id", "Adonai Klaim");
      $mail->AddCC("rohaida@adonai.co.id", "Adonai Klaim");
      $mail->AddCC("mikha@adonai.co.id", "Adonai Klaim");

      $mail->MsgHTML($message);

      $mail->addStringAttachment($pdfdoc, 'Kelengkapan Dokumen Klaim.pdf');

      $send = $mail->Send(); //Send the mails
      if ($send) {
          $today = date("Y-m-d h:i:s");
          //$query = "INSERT INTO fu_ajk_his_kirim_surat SET surat = 'Pelaporan Klaim Cabang' ,tgl_kirim='".$today."',to='".$tomail."',user='".$_REQUEST['user']."',key='".$_REQUEST['idC']."'";
          mysql_query("INSERT INTO fu_ajk_his_kirim_surat SET surat = 'Pelaporan Klaim Cabang' ,tgl_kirim='".$today."',tomail='".$tomail."',user='".$_REQUEST['user']."',keytable='".$_REQUEST['idC']."'");
          //echo $query;
          echo '<br><center><h3 style="color:#009933;">Mail sent successfully</h3></center>';
      } else {
          echo '<center><h3 style="color:#FF3300;">Mail error: </h3></center>'.$mail->ErrorInfo;
      }
  break;

  case "sendklaimkurangdokumen":
    function HeaderingExcel($filename)
    {
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$filename");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
        header("Pragma: public");
    }

    HeaderingExcel('Laporan_Klaim_kurangdokumen.xls');
    // $cabang = $_REQUEST['cab'];
    $cabang = 'BANDA ACEH';
    // $fjudul =& $workbook->add_format();   
    // $fjudul->setAlign('vcenter'); 
    // $fjudul->setAlign('center'); 
    // $fjudul->setBold();

    $fjudul =& $workbook->add_format();   
    $fjudul->set_align('vcenter');  
    $fjudul->set_align('center'); 
    $fjudul->set_bold();

    $workbook = new Workbook("");
    $worksheet1 =& $workbook->add_worksheet('BANDA ACEH');

    $format =& $workbook->add_format();
    $format->set_align('vcenter');  
    $format->set_align('center'); 
    $format->set_color('white');  
    $format->set_bold();  
    $format->set_pattern(); 
    $format->set_fg_color('green');    

    $worksheet1->setMerge(0, 0, 0, 19); 
    $worksheet1->write_string(0, 0, "List Klaim Kurang Dokumen", $fjudul, 1);
    
    $worksheet1->setRow(4, 16);
    $worksheet1->set_column(4, 0, 5);  $worksheet1->write_string(4, 0, "NO", $format);
    $worksheet1->set_column(4, 1, 15); $worksheet1->write_string(4, 1, "CABANG", $format);
    $worksheet1->set_column(4, 2, 15); $worksheet1->write_string(4, 2, "MITRA", $format);
    $worksheet1->set_column(4, 3, 15); $worksheet1->write_string(4, 3, "ID PESERTA", $format);
    $worksheet1->set_column(4, 4, 15); $worksheet1->write_string(4, 4, "NAMA DEBITUR", $format);
    $worksheet1->set_column(4, 5, 15); $worksheet1->write_string(4, 5, "TGL LAHIR", $format);
    $worksheet1->set_column(4, 6, 15); $worksheet1->write_string(4, 6, "USIA", $format);
    $worksheet1->set_column(4, 7, 15); $worksheet1->write_string(4, 7, "PLAFOND KREDIT", $format);
    $worksheet1->set_column(4, 8, 15); $worksheet1->write_string(4, 8, "TUNTUTAN KLAIM", $format);
    $worksheet1->set_column(4, 9, 15); $worksheet1->write_string(4, 9, "TGL AKAD", $format);
    $worksheet1->set_column(4, 10, 15); $worksheet1->write_string(4, 10, "JK.WKT", $format);
    $worksheet1->set_column(4, 11, 15); $worksheet1->write_string(4, 11, "DOL", $format);
    $worksheet1->set_column(4, 12, 15); $worksheet1->write_string(4, 12, "TGL TERIMA LAPORAN", $format);
    $worksheet1->set_column(4, 13, 15); $worksheet1->write_string(4, 13, "KELENGKAPAN DOKUMEN", $format);
    $worksheet1->set_column(4, 14, 15); $worksheet1->write_string(4, 14, "STATUS", $format);
    $worksheet1->set_column(4, 15, 15); $worksheet1->write_string(4, 15, "KOL", $format);
    $worksheet1->set_column(4, 16, 15); $worksheet1->write_string(4, 16, "EXP", $format);
    
    $query = "
    SELECT id_cabang,
            mitra,
            nama,
            tgl_lahir,
            usia,
            kredit_jumlah,
            tuntutan_klaim,
            kredit_tgl,
            tenor,
            dol,
            tgl_terima_laporan,
            kelengkapan_dokumen,
            status_klaim,
            kol,
            kadaluarsa
    FROM vmasterklaim 
    WHERE id_cabang = '".$cabang."' and 
    id_klaim_status = 2";
    $result = mysql_query($query);

    $baris = 5;
    while ($row = mysql_fetch_array($result)) {      
      $worksheet1->write_string($baris, 0, ++$no);
      $worksheet1->write_string($baris, 1, $row['cabang']);
      $worksheet1->write_string($baris, 2, $row['mitra']);
      $worksheet1->write_string($baris, 3, $row['id_cabang']);
      $worksheet1->write_string($baris, 4, $row['nama']);
      $worksheet1->write_string($baris, 5, $row['tgl_lahir']);
      $worksheet1->write_string($baris, 6, $row['usia']);
      $worksheet1->write_string($baris, 7, $row['kredit_jumlah']);
      $worksheet1->write_string($baris, 8, $row['tuntutan_klaim']);
      $worksheet1->write_string($baris, 9, $row['kredit_tgl']);
      $worksheet1->write_string($baris, 10, $row['tenor']);
      $worksheet1->write_string($baris, 11, $row['dol']);
      $worksheet1->write_string($baris, 12, $row['tgl_terima_laporan']);
      $worksheet1->write_string($baris, 13, $row['kelengkapan_dokumen']);
      $worksheet1->write_string($baris, 14, $row['status_klaim']);
      $worksheet1->write_string($baris, 15, $row['kol']);
      $worksheet1->write_string($baris, 16, $row['kadaluarsa']);
      $baris++;
    }

    $workbook->close();
  break;

  default:
    ;
} // switch
