<style type="text/css">
.phpmakerkwit {
	color: inherit; /* text color */
	font-family: Arial, Tahoma; /* font name */
	font-size: small; /* font size */
	border-style: solid;
	border-width: 1px;
	border-collapse:collapse;
}

.phpmakerkpa {
	color: inherit; /* text color */
	font-family: Arial, Tahoma; /* font name */
	font-size: xx-small; /* font size */
}
.barcode {
	color: inherit; /* text color */
	font-family: IDAutomationHC39M.ttf, IDAutomationHC39M; /* font name */
	font-size: small; /* font size */
}
</style>
<?php 
/*
include_once "ewcfg9.php";
include_once "ewmysql9.php";
include_once "phpfn9.php";
include_once "posinfo.php";
include_once "employeeinfo.php";
include_once "rip_cv_3in_kparipgridcls.php";
include_once "userfn9.php";
*/
$host = "localhost";
$user = "root";
$pass = "t1g4b3l4s!2277";
$db   = "relife_office";

mysql_connect($host, $user, $pass);
mysql_select_db($db);

define('CODE128A_START_BASE', 103);
define('CODE128B_START_BASE', 104);
define('CODE128C_START_BASE', 105);
define('STOP', 106);

function code128BarCode ( $code , $density = 1 ) {
	$rahmad128code  = 	array(
								212222, 222122, 222221, 121223, 121322, 131222, 122213, 122312, 132212, 221213, 221312, 231212, 112232, 122132, 122231, 113222, 123122, 123221, 223211, 221132, 221231,
								213212, 223112, 312131, 311222, 321122, 321221, 312212, 322112, 322211, 212123, 212321, 232121, 111323, 131123, 131321, 112313, 132113, 132311, 211313, 231113, 231311,
								112133, 112331, 132131, 113123, 113321, 133121, 313121, 211331, 231131, 213113, 213311, 213131, 311123, 311321, 331121, 312113, 312311, 332111, 314111, 221411, 431111,
								111224, 111422, 121124, 121421, 141122, 141221, 112214, 112412, 122114, 122411, 142112, 142211, 241211, 221114, 413111, 241112, 134111, 111242, 121142, 121241, 114212,
								124112, 124211, 411212, 421112, 421211, 212141, 214121, 412121, 111143, 111341, 131141, 114113, 114311, 411113, 411311, 113141, 114131, 311141, 411131, 211412, 211214,
								211232, 23311120
							);
	$width			=	(((11 * strlen($code)) + 35) * ($density/72)); // density/72 determines bar width at image DPI of 72
	$height			=	($width * .15 > .7) ? $width * .15 : .7;
	$px_width		=	round($width * 72);
	$px_height		=	($height * 36);
	$rahmaddumpcode	=	imagecreatetruecolor($px_width, $px_height);
	$white     		=	imagecolorallocate($rahmaddumpcode, 255, 255, 255);
	$black     		=	imagecolorallocate($rahmaddumpcode, 0, 0, 0);
	imagefill($rahmaddumpcode, 0, 0, $white);
	imagesetthickness($rahmaddumpcode, $density);
	$rahmad	=	CODE128B_START_BASE;
	$encoding	=	array($rahmad128code[CODE128B_START_BASE]);

	for($i = 0; $i < strlen($code); $i++) {
		$rahmad	+=	(ord(substr($code, $i, 1)) - 32) * ($i + 1);
		array_push($encoding, $rahmad128code[(ord(substr($code, $i, 1))) - 32]);
	}
	array_push($encoding, $rahmad128code[$rahmad%103]);
	array_push($encoding, $rahmad128code[STOP]);

	$enc_str	=	implode($encoding);
	for($i = 0, $x = 0, $inc = round(($density/72) * 100); $i < strlen($enc_str); $i++) {
		$val	=	intval(substr($enc_str, $i, 1));

		for($n = 0; $n < $val; $n++, $x+=$inc) { if($i%2 == 0) imageline($rahmaddumpcode, $x, 0, $x, $px_height, $black); }
	}
	return $rahmaddumpcode;
}
 
switch ($_REQUEST['op']) {
case "barcode":
//echo $_REQUEST['idKwit'];
$barcode = mysql_fetch_array(mysql_query('select `pos`.`ID` AS `ID`,`pos`.`IDKirim` AS `IDKirim`,`pos`.`Nama` AS `Nama`,`pos`.`Alamat1` AS `Alamat1`,`pos`.`Alamat2` AS `Alamat2`,`pos`.`Alamat3` AS `Alamat3`,`pos`.`Alamat4` AS `Alamat4`,`pos`.`Kota` AS `Kota`,`pos`.`Zip` AS `Zip`,`pos`.`Departemen` AS `Departemen`,`pos`.`Pengirim` AS `Pengirim`,`pos`.`TglKirim` AS `TglKirim`,`pos`.`JnsDokumen` AS `JnsDokumen`,`pos`.`NoDokumen` AS `NoDokumen`,`pos`.`Segera` AS `Segera` from `pos` where `pos`.`ID`='.$_REQUEST['ID']));

//CONTOH NOMOR IDKWITANSI (hapus tanda "//" untuk melihat hasil barcode tanpa database)
//$barcode['IDKirim'] = "1234567";
//CONTOH NOMOR IDKWITANSI

if($barcode['IDKirim']) {
	$rahmaddumpcode	=	code128BarCode($barcode['IDKirim'], 1);
	ob_start();
	imagepng($rahmaddumpcode);
	$output_img		=	ob_get_clean();
}

echo '
<table border="0" width="400px" style="margin-left:0px" cellpadding="0" cellspacing="0" class=" ">
	<tr class="phpmaker">
		<td colspan="3" width="100%" align="left">Kepada</td>
	</tr>
	<tr class="phpmaker">
		<td width="20%" align="left">Nama</B></td>
		<td width="5%" align="center"> : </td>
		<td width="75%" align="left"><B>'.$barcode['Nama'].'</B></td>
	</tr>
	<tr class="phpmaker">
		<td width="20%" align="left">Alamat</td>
		<td width="5%" align="center"> : </td>
		<td width="75%" align="left"><B>'.$barcode['Alamat1'].'</br>'.$barcode['Alamat2'].'</br>'.$barcode['Alamat3'].'</br>'.$barcode['Alamat4'].'</B></td>
	</tr>
	<tr class="phpmaker">
		<td width="20%" align="left">Kota</td>
		<td width="5%" align="center"> : </td>
		<td width="75%" align="left"><B>'.$barcode['Kota'].'</B></td>
	</tr>
	<tr class="phpmaker">
		<td width="20%" align="left">Kode Pos</td>
		<td width="5%" align="center"> : </td>
		<td width="75%" align="left"><B>'.$barcode['Zip'].'</B></td>
	</tr>
	<tr class="barcode">
		<td colspan="3" height="60px" align="center"><B><img src="data:image/png;base64,' . base64_encode($output_img) . '" /></br>'.$barcode['IDKirim'].'</B></td>
	</tr>'
	;
		;
	break;
	default:
	;
} // switch

if (!$id){
	echo "<script language=javascript>
		function printWindow() {
		bV = parseInt(navigator.appVersion);
		if (bV >= 4) window.print();}
		printWindow();
		</script>";
}

?>
