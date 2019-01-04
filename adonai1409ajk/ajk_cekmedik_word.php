<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("../includes/functions.php");
connect();
require_once 'includes/metPHPWORD/PHPWord.php';
$futglinput = date("Y-m-d g:i:a");
$futgl = date("d/m/Y");
if (session_is_registered('nm_user')) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));	}
$met = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_peserta WHERE id="'.$_REQUEST['id'].'" AND status_aktif="pending" '.$satu.' '.$dua.' '.$tiga.' '));
$rcost = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_costumer WHERE id="'.$met['id_cost'].'"'));
$typemedik = mysql_query('SELECT * FROM fu_ajk_medical_desc WHERE type_medical="'.$met['status_medik'].'"');
while ($rtypemedik = mysql_fetch_array($typemedik)) {
$metmedik .=$rtypemedik['Desc'].', ';
}
$PHPWord = new PHPWord();					// New Word Document
$section = $PHPWord->createSection();		// New portrait section

// Add header
$header = $section->createHeader();
$table = $header->addTable();
$table->addRow();

//$table->addCell(4500)->addText('This is the header.');
$table->addCell(4500)->addImage('image/logo_recapitalife.png', array('width'=>175, 'height'=>75, 'align'=>'left'));

$kodetgl = explode("/", $futgl);	$tkodetglthn = substr($kodetgl[2],2);	$rkodetgl = $kodetgl[1].'/'.$tkodetglthn;
if ($kodetgl[1]==01) {	$rbulan = "Januari";	}	elseif ($kodetgl[1]==02) {	$rbulan = "Februari";	}	elseif ($kodetgl[1]==03) {	$rbulan = "Maret";	}	elseif ($kodetgl[1]==04) {	$rbulan = "April";	}	elseif ($kodetgl[1]==05) {	$rbulan = "Mei";	}	elseif ($kodetgl[1]==06) {	$rbulan = "Juni";	}	elseif ($kodetgl[1]==07) {	$rbulan = "Juli";	}	elseif ($kodetgl[1]==08) {	$rbulan = "Agustus";	}	elseif ($kodetgl[1]==09) {	$rbulan = "September";	}	elseif ($kodetgl[1]==10) {	$rbulan = "Oktober";	}	elseif ($kodetgl[1]==11) {	$rbulan = "November";	}	else {	$rbulan = "Desember";	}

$section->addText(''.$met['id'].'/AJK/'.$rkodetgl.'');
$section->addText('Jakarta, '.$kodetgl[0].' '.$rbulan.' '.$kodetgl[2].'');

$PHPWord->addFontStyle('BoldText', array('bold'=>true));
$PHPWord->addParagraphStyle('rStyle', array('align'=>'center', 'spacing'=>50));
$PHPWord->addParagraphStyle('pStyle', array('align'=>'left', 'spaceAfter'=>50));
$PHPWord->addLinkStyle('NLink', array('underline'=>PHPWord_Style_Font::UNDERLINE_SINGLE));

$section->addText('Kepada Yth :', 'rStyle', 'pStyle');
$section->addText(''.$rcost['name'], 'BoldText','pStyle');
$section->addText(''.$rcost['address'], 'rStyle', 'pStyle');
$section->addText(''.$rcost['city'], 'rStyle', 'pStyle');

$section->addTextBreak(1);
$section->addText('Perihal : Uji Kesehatan Wajib (Medical Check Up)', 'BoldText','pStyle');

$section->addTextBreak(1);
$section->addText('Dengan hormat,');

$section->addTextBreak(0);
$textrun = $section->createTextRun('pStyle');
$textrun->addText('Sehubungan dengan adanya peserta baru untuk debitur '.$rcost['name'].', bersama ini kami sampaikan bahwa sesuai dengan ketentuan Underwriting, maka nama Peserta  yang tercantum dibawah ini');
$textrun->addText(' wajib melakukan Medical Check Up (MCU), melakukan pada laboratorium PRODIA terdekat.', 'BoldText');

$section->addTextBreak(0);
$section->addText('Nama                : '.$met['nama'].'', 'BoldText', 'pStyle');
$section->addText('Usia                  : '.$met['usia'].' tahun', 'BoldText', 'pStyle');

$section->addTextBreak(1);
$section->addText('Adapun jenis pemeriksaan yang harus dilakukan yaitu :');
$section->addText('- Medical Type '.$met['status_medik'].' (Group) : '.$metmedik.'');

$section->addTextBreak(0);
$section->addText('Selain itu, perlu kami kemukakan disini apabila dari hasil seleksi resiko yang kami jalankan terdapat hal-hal yang mempertinggi tingkat resiko yang bersangkutan maka kami akan mengenakan extra premi yang besarnya sesuai dengan pertambahan tingkat resiko tersebut. Dan kami mengharapkan dapat menerima hasil medis dalam jangka waktu 1 bulan, terhitung dari tanggal yang tertera pada surat. ', 'rStyle', 'pStyle');

$section->addTextBreak(1);
$section->addText('Atas kerja sama dan perhatiannya kami ucapkan terima kasih.');

$section->addTextBreak(0);
$section->addText('Salam,');
$section->addImage('image/ttd_andress.jpg', array('width'=>125, 'height'=>75, 'align'=>'left'));
$section->addText('Andress Manansal, ASAI., AAAIJ.', 'NLink', 'pStyle');
$section->addText('Kadiv. Tehnik', null, 'pStyle');
// Add footer
$footer = $section->createFooter();
//$footer->addPreserveText('Page {PAGE} of {NUMPAGES}.', array('align'=>'center'));
$footer->addText('PT. Recapital - Asuransi Jiwa Kredit 2013', null, 'rStyle');
//$footer->addText('PT. RECAPITAL', array('align'=>'center'));

// Save File
$idp1 = 100000000 + $met['id'];		$idp2 = substr($idp1,1);	// ID PESERTA //
$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
$namafilenya = $idp2.'-'.$met['nama'].'.docx';
$objWriter->save('../ajk_file/medical/'.$namafilenya);
$metinput = explode("/", $futgl);
$elinput =$metinput[2].'-'.$metinput[1].'-'.$metinput[0];
$metformmedic = mysql_query('INSERT INTO fu_ajk_medical_form SET id_cost="'.$met['id_cost'].'",
																 idp="'.$_REQUEST['id'].'",
																 file_medical="'.$namafilenya.'",
																 date_form="'.$elinput.'",
																 file_type="form_medic",
																 input_time="'.$futglinput.'",
																 input_by="'.$q['nm_lengkap'].'"');
echo '<center>Form medical telah di buat.</center><meta http-equiv="refresh" content="2;URL=ajk_cekmedik_fu.php">';
?>