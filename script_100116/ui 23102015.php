<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :penting_kaga@yahoo.com
// @copyright Januari 2014
// ----------------------------------------------------------------------------------
error_reporting(0);
session_start();
ob_start("ui_output_callback");
include_once('includes/ajk6106.php');
//include_once('includes/ajk_menus_adonai.php');
include_once('includes/functions.php');
include_once('includes/db.php');
include_once('includes/excel_reader2.php');
//include_once('includes/smtp_classes/library.php'); // include the library file
include_once('includes/smtp_classes/class.phpmailer.php'); // include the class name
include_once('includes/phpsecureurl.pclass.php'); // include the class name

global $database;
$database = new db();
$ui_template = dirname(__FILE__)."/templates/". theme . "/index.html";
out('site_title', 'ADONAI | Pialang Asuransi (AJK&P');
out('template_name', theme);
out('copyright', '<font size="2"><b>ADONAI | Pialang Asuransi</b><br />
				  Graha Adonai Ruko Taman Bougenville Estate. Blok A/33-34<br />Jl.KH. Noer Ali, Kalimalang <br />
				  T. (021) 8690 9090. F. (021) 8690 8849<br />
				  &copy Copyright '.date(Y).', All Right Reserved</font></b>');

$met_spaksize = 2048; // max file size (1Mb)
$metpath = "ajk_file/_spak/";
$dok_klaim_ajk = "ajk_file/klaim/";
$allowedExts = array("application/pdf", "image/jpg", "image/jpeg");
checkLogin();
getLogin();
debug();
generateMenu();
if (session_is_registered('nm_user')) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));}
if ($q['id']!="") {
	$logger = $database->doQuery('INSERT ajk_logger SET id_user="'.$q['id'].'", id_cost="'.$q['id_cost'].'", lastdate_login="'.$datelog.'", lasttime_login="'.$timelog.'", user_ip="'.$alamat_ip.'", user_referer="'.$nama_host.'", user_browser="'.$referrer.'"');
}else{	}

function fget_contents($file)
{
	$fd = fopen($file, "r");
	$content = "";
	while (!feof($fd)) {
		$content .= fgets($fd, 4096);
	}
	fclose($fd);
	return $content;
}

function ui_output_callback($buffer)
{
	global $ui_template;
	global $ui_vars;
	global $authenticate, $public;
	global $amp;

	session_cache_limiter('private');
	session_cache_expire(30);

	session_start();
	$script_name = explode('/', $_SERVER["SCRIPT_NAME"]);

	if (checkPrivileges()) {
		$template = fget_contents($ui_template);
		$ui_vars["content"] = ui_build($buffer, $ui_vars);
		$html = ui_build($template, $ui_vars);
	} else {
		$html = '<script language="JavaScript">
			     	alert(\'Maaf tidak sesuai dengan hak akses program!!!!\');
			    	history.back(1);
		    	</script>';
	}
	return $html;
}
function ui_build($template, $vars)
{
	if (preg_match_all('/\{([\w]+)\}/', $template, $matches) > 0) {
		$patterns = array();
		$replacements = array();
		for ($i = 0; $i < count($matches[0]); $i++) {
			$var = $matches[1][$i];
			$patterns[] = '/\{' . $var . '\}/';
			$replacements[] = $vars[$var];
		}
		return preg_replace($patterns, $replacements, $template);
	}

	return $template;
}
function out($var, $content)
{
	global $ui_vars;
	$ui_vars[$var] = $content;
}
function redirect($page)
{
	ob_end_clean();
	header("Location: $page");
	exit;
}
function notify_session()
{
	if (!session_is_registered("nm_user")) {
		return "Illegal User";
	}
	return $_SESSION["nm_user"];
}
function debug()
{
	if ($_REQUEST['debug'] == 1) {
		foreach($_REQUEST as $k => $v) {
			echo $k . ' : ' . $v . '<br />';
		}
	}
}
function checkLogin()
{
	if (!session_is_registered('nm_user')) {
		if (!eregi('login.php', $_SERVER['SCRIPT_NAME'])) {
			echo '<script language="Javascript">window.location="login.php?op=logout"</script>';
			//header('Location: login.php');
		}
	}
	// return true;
}
function getLogin()
{
	if (session_is_registered('nm_user')) {
		$database = new db();
		$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));
		out('user', 'Login : <b>'.$q['nm_lengkap'] . '</b>');
		$timeout = 1800; // Number of seconds until it times out.
		if(isset($_SESSION['timeout'])) { 		// Check if the timeout field exists.
			// See if the number of seconds since the last
			// visit is larger than the timeout period.
			$duration = time() - (int)$_SESSION['timeout'];
			if($duration > $timeout) {
				// Destroy the session and restart it.
				//session_destroy();
				header('Location: login.php?op=logout');
			}
		}
		// Update the timout field with the current time.
		$_SESSION['timeout'] = time();
	}
}
function checkPrivileges()
{
	global $ui_vars;
	if (isset($ui_vars['mod'])) {
		$q = mysql_query('SELECT ' . $ui_vars['privilege'] . '_PRIV FROM v_privileges WHERE GROUP_ID="' . $_SESSION['id_group'] . '" AND id_level="' . $_SESSION['id_level'] . '" AND MOD_CODE="' . $ui_vars['mod'] . '"');
		$r = mysql_fetch_array($q);
		if (($r[0]==1)) {
			return true;
		} else {
			return false;
		}
	} else {
		return true;
	}
}
function valid_date( $str )
{
	$stamp = strtotime( $str );
	if (!is_numeric($stamp))
	{	return FALSE;	}
	$month = date( 'm', $stamp );
	$day   = date( 'd', $stamp );
	$year  = date( 'Y', $stamp );
	if (checkdate($month, $day, $year))
	{	return $year.'-'.$month.'-'.$day;	}
	return FALSE;
}
function generateMenu()
{
	out('menus','');
	if (session_is_registered('nm_user')) {	$q=mysql_fetch_array(mysql_query('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));	}
	if (session_is_registered('usernama')) {
/*
	if ($q['level'] >= 5 AND $q['level'] <= 99 AND $q['id_cost'] !="" AND $q['id_polis']=="1") {	//USER CLIENT//
			$_temp = "<div id='cssmenu'>
				<ul><li><a href='index.php'><span>Home</span></a></li>
					<!--<li class='has-sub'><a href='ajk_user.php'><span>Master Account</span></a>
						<ul><li class='has-sub'><a href='ajk_user.php'><span>Administrator</span></a>
						</ul>
					</li>-->
					<li class='has-sub'><a href='#'><span>Master Upload</span></a>
						<ul><li class='has-sub'><a href='ajk_uplspak.php'><span>Upload Data File SPK</span></a>
							<li class='has-sub'><a href='ajk_val_upl.php?v=spk'><span>Validasi Data Upload SPK</span></a>
							<li class='has-sub'><a href='ajk_val_upl.php?v=spkForm'><span>Form Data Kepesertaan SPK</span></a>
							<li class='has-sub'><a href='ajk_uplspak.php?el=fl_spk'><span>Upload Data Kepesertaan SPK</span></a>
							<li class='has-sub'><a href='ajk_val_upl.php?v=fl_spk'><span>Validasi Data Kepesertaan SPK</span></a>
							<li class='has-sub'><a href='ajk_photo.php'><span>Upload Photo SPK</span></a>
						</ul>
					</li>
					<li class='has-sub'><a href='#'><span>Master Data</span></a>
						<ul><li class='has-sub'><a href='ajk_peserta.php?er=_spk'><span>Data SPK</span></a>
							<li class='has-sub'><a href='ajk_peserta.php'><span>Data Kepesertaan</span></a>
							<li class='has-sub'><a href='ajk_nota.php?er=dn'><span>Data Debit Note</span></a>
							<li class='has-sub'><a href='ajk_nota.php?er=cn'><span>Data Credit note</span></a>
							<li class='has-sub'><a href='ajk_md_spk.php'><span>Data File SPK</span></a>
							<li class='has-sub'><a href='ajk_peserta.php?er=pending'><span>Data Pending/Medical SKKT</span></a>
						</ul>
					</li>
					<li class='has-sub'><a href='#'><span>Master Klaim</span></a>
						<ul><li class='has-sub'><a href='ajk_klaim.php?er=reqrefund'><span>Data Refund</span></a>
							<li class='has-sub'><a href='ajk_pembatalan.php?'><span>Data Pembatalan</span></a>
							<li class='has-sub'><a href='ajk_klaim.php?er=reqklaim'><span>Data Klaim</span></a>
						</ul>
					</li>
					<li class='has-sub'><a href='#'><span>Master Laporan</span></a>
						<ul><li class='has-sub'><a href='er_peserta.php'><span>Laporan Kepesertaan</span></a>
							<li class='has-sub'><a href='er_peserta.php?er=spk'><span>Laporan SPK</span></a></ul>
					</li>
					<li><a href='login.php?op=logout'><span>Logout</span></a></li>
					<li class='displayname'>".strtoupper($q['nm_lengkap'])." (".$q['cabang'].")</li>
				</ul>
			</div><br /><br />";
		}
elseif ($q['level'] >= 5 AND $q['level'] <= 9 AND $q['id_cost'] !="" AND $q['id_polis']!="1"){	//USER CLIENT//
		$_temp = "<div id='cssmenu'>
				<ul><li><a href='index.php'><span>Home</span></a></li>
					<!--<li class='has-sub'><a href='#'><span>Master Account</span></a>
						<ul><li class='has-sub'><a href='ajk_user.php'><span>Administrator</span></a>
							<li class='has-sub'><a href='ajk_polis.php'><span>Master Polis</span></a>
							<li class='has-sub'><a href='ajk_wilayah.php'><span>Wilayah</span></a>
						</ul>
					</li>-->
					<li class='has-sub'><a href='#'><span>Master Upload</span></a>
						<ul><li class='has-sub'><a href='ajk_uploader.php?er=spaj'><span>Upload Data Peserta SPAJ/SPD</span></a>
							<li class='has-sub'><a href='#'><span>Validasi Data</span></a>
							<ul><li class='last'><a href='ajk_val_upl.php?v=spaj'><span>Data Upload SPAJ/SPD</span></a>
								<li class='last'><a href='ajk_val_batal.php'><span>Data Pembatalan Peserta</span></a>
								<li class='last'><a href='ajk_klaim.php?er=valRefund'><span>Data Refund Peserta</span></a>
								<li class='last'><a href='ajk_klaim.php?er=valKlaim'><span>Data Pengajuan Klaim</span></a>
								<!--<li class='last'><a href='ajk_val_upl.php?v=spk'><span>Data SPK</span></a>-->
							</ul>
							<li class='has-sub'><a href='ajk_photo.php'><span>Upload Photo Peserta</span></a>
						</ul>
					</li>
					<li class='has-sub'><a href='#'><span>Master Data</span></a>
						<ul><li class='has-sub'><a href='ajk_peserta.php'><span>Data Kepesertaan</span></a>
							<li class='has-sub'><a href='ajk_nota.php?er=dn'><span>Data Debit Note</span></a>
							<li class='has-sub'><a href='ajk_nota.php?er=cn'><span>Data Credit note</span></a>
							<li class='has-sub'><a href='ajk_peserta.php?er=pending'><span>Data Pending/Medical SKKT</span></a>
							<li class='has-sub'><a href='ajk_md_spk.php'><span>Data SPK</span></a>
						</ul>
					</li>

					<li class='has-sub'><a href='#'><span>Master Klaim</span></a>
						<ul><li class='has-sub'><a href='ajk_klaim.php?er=reqrefund'><span>Data Refund</span></a>
							<li class='has-sub'><a href='ajk_pembatalan.php'><span>Data Pembatalan</span></a>
							<li class='has-sub'><a href='ajk_klaim.php?er=reqklaim'><span>Data Klaim</span></a>
						</ul>
					</li>
					<li class='has-sub'><a href='#'><span>Master Laporan</span></a>
						<ul><li class='has-sub'><a href='er_peserta.php'><span>Laporan Kepesertaan</span></a>
							<li class='has-sub'><a href='er_dn.php'><span>Laporan Debit Note</span></a>
							<li class='has-sub'><a href='er_cn.php'><span>Laporan Credit note</span></a>
						</ul>
					</li>
					<li><a href='login.php?op=logout'><span>Logout</span></a></li>
					<li class='displayname'>".strtoupper($q['nm_lengkap'])." (".$q['cabang'].")</li>
	</ul>
	</div><br /><br />";
}
elseif ($q['level']<=4) {	//USER DOKTER//
	$_temp = "<div id='cssmenu'>
			  <ul><li><a href='index.php'><span>Home</span></a></li>
				  <li class='has-sub'><a href='#'><span>Master Data</span></a>
					<ul><li class='has-sub'><a href='ajk_peserta.php'><span>Data Kepesertaan</span></a>
						<li class='has-sub'><a href='ajk_nota.php?er=dn'><span>Data Debit Note</span></a>
						<li class='has-sub'><a href='ajk_nota.php?er=cn'><span>Data Credit note</span></a>
						<li class='has-sub'><a href='ajk_peserta.php?er=pending'><span>Data Pending/Medical SKKT</span></a>
						<li class='has-sub'><a href='ajk_md_spk.php'><span>Data SPK</span></a>
					</ul>
				  </li>
				  <li class='has-sub'><a href='#'><span>Master Laporan</span></a>
					<ul><li class='has-sub'><a href='er_peserta.php'><span>Laporan Kepesertaan</span></a>
						<li class='has-sub'><a href='er_dn.php'><span>Laporan Debit Note</span></a>
						<li class='has-sub'><a href='er_cn.php'><span>Laporan Credit note</span></a>
					</ul>
				  </li>
				  <li><a href='login.php?op=logout'><span>Logout</span></a></li>
				  <li class='displayname'>".strtoupper($q['nm_lengkap'])." (".$q['cabang'].")</li>
			</ul>
		</li>
		</ul>
		</div><br /><br />";
		}
elseif ($q['level']>=10 AND $q['id_cost'] !="" AND $q['wilayah']=="PUSAT") {	//USER KADIV//
$_temp = "<div id='cssmenu'>
		  <ul><li><a href='index.php'><span>Home</span></a></li>
			  <li class='has-sub'><a href='#'><span>Master Data</span></a>
				<ul><li class='has-sub'><a href='ajk_peserta.php'><span>Data Kepesertaan</span></a>
					<li class='has-sub'><a href='ajk_nota.php?er=dn'><span>Data Debit Note</span></a>
					<li class='has-sub'><a href='ajk_nota.php?er=cn'><span>Data Credit note</span></a>
				</ul>
			  </li>
			  <li class='has-sub'><a href='#'><span>Master Laporan</span></a>
				<ul><li class='has-sub'><a href='er_peserta.php'><span>Laporan Kepesertaan</span></a>
					<li class='has-sub'><a href='er_dn.php'><span>Laporan Debit Note</span></a>
					<li class='has-sub'><a href='er_cn.php'><span>Laporan Credit note</span></a>
					<li class='has-sub'><a href='er_rmf.php'><span>Laporan Risk Management Fund (RMF)</span></a>
				</ul>
			  </li>
			  <li><a href='login.php?op=logout'><span>Logout</span></a></li>
			  <li class='displayname'>".strtoupper($q['nm_lengkap'])." (".$q['cabang'].")</li>
		</li>
	</ul>
	</div><br /><br />";
}
*/

if ($q['level'] == 99 AND $q['id_cost'] !="" AND $q['id_polis']!="") {	//LEVEL 99//
$_temp = "<div id='cssmenu'>
				<ul><li><a href='index.php'><span>Home</span></a></li>
					<!--<li class='has-sub'><a href='ajk_user.php'><span>Master Account</span></a>
						<ul><li class='has-sub'><a href='ajk_user.php'><span>Administrator</span></a>
						</ul>
					</li>-->
					<li class='has-sub'><a href='#'><span>Master Upload</span></a>
						<ul><li class='has-sub'><a href='ajk_uplspak.php'><span>Upload Data File SPK</span></a>
							<li class='has-sub'><a href='ajk_val_upl.php?v=spk'><span>Validasi Data Upload SPK</span></a>
							<li class='has-sub'><a href='ajk_val_upl.php?v=spkForm'><span>Form Data Kepesertaan SPK</span></a>
							<li class='has-sub'><a href='ajk_photo.php'><span>Upload Photo SPK</span></a>
						</ul>
					</li>
					<li class='has-sub'><a href='#'><span>Master Data</span></a>
						<ul><!--<li class='has-sub'><a href='ajk_peserta.php?er=_spk'><span>Data SPK</span></a>
							<li class='has-sub'><a href='ajk_peserta.php'><span>Data Kepesertaan</span></a>
							<li class='has-sub'><a href='ajk_nota.php?er=dn'><span>Data Debit Note</span></a>
							<li class='has-sub'><a href='ajk_nota.php?er=cn'><span>Data Credit note</span></a>-->
							<li class='has-sub'><a href='ajk_md_spk.php'><span>Data File SPK</span></a>
							<!--<li class='has-sub'><a href='ajk_peserta.php?er=pending'><span>Data Pending/Medical SKKT</span></a>-->
						</ul>
					</li>
					<!--<li class='has-sub'><a href='#'><span>Master Klaim</span></a>
						<ul><li class='has-sub'><a href='ajk_klaim.php?er=reqrefund'><span>Data Refund</span></a>
							<li class='has-sub'><a href='ajk_pembatalan.php?'><span>Data Pembatalan</span></a>
							<li class='has-sub'><a href='ajk_klaim.php?er=reqklaim'><span>Data Klaim</span></a>
						</ul>
					</li>-->
					<li class='has-sub'><a href='#'><span>Master Laporan</span></a>
						<ul><!--<li class='has-sub'><a href='er_peserta.php'><span>Laporan Kepesertaan</span></a>-->
							<li class='has-sub'><a href='er_peserta.php?er=spk'><span>Laporan SPK</span></a></ul>
					</li>
					<li><a href='login.php?op=logout'><span>Logout</span></a></li>
					<li class='displayname'>".strtoupper($q['nm_lengkap'])." (".$q['cabang'].")</li>
				</ul>
			</div><br /><br />";
}
elseif ($q['level'] == 7 AND $q['id_cost'] !="" AND $q['id_polis']==1 OR $q['id_polis']==7 OR $q['id_polis']==8 OR $q['id_polis']==9 OR $q['id_polis']==10 OR $q['id_polis']==12) {	//LEVEL 7 PRODUK SPK//
$_temp = "<div id='cssmenu'>
				<ul><li><a href='index.php'><span>Home</span></a></li>
					<!--<li class='has-sub'><a href='ajk_user.php'><span>Master Account</span></a>
						<ul><li class='has-sub'><a href='ajk_user.php'><span>Administrator</span></a>
						</ul>
					</li>-->
					<li class='has-sub'><a href='#'><span>Master Upload</span></a>
						<ul><li class='has-sub'><a href='ajk_uplspak.php?el=fl_spk'><span>Upload Data Kepesertaan SPK</span></a>
							<li class='has-sub'><a href='ajk_val_upl.php?v=fl_spk'><span>Validasi Data Kepesertaan SPK</span></a>
							<li class='has-sub'><a href='ajk_photo.php'><span>Upload Photo SPK</span></a>
						</ul>
					</li>
					<li class='has-sub'><a href='#'><span>Master Data</span></a>
						<ul><li class='has-sub'><a href='ajk_peserta.php?er=_spk'><span>Data SPK</span></a>
							<li class='has-sub'><a href='ajk_peserta.php'><span>Data Kepesertaan</span></a>
							<li class='has-sub'><a href='ajk_nota.php?er=dn'><span>Data Debit Note</span></a>
							<li class='has-sub'><a href='ajk_nota.php?er=cn'><span>Data Credit note</span></a>
							<li class='has-sub'><a href='ajk_md_spk.php'><span>Data File SPK</span></a>
							<li class='has-sub'><a href='ajk_peserta.php?er=pending'><span>Data Pending/Medical SKKT</span></a>
						</ul>
					</li>
					<li class='has-sub'><a href='#'><span>Master Klaim</span></a>
						<ul><li class='has-sub'><a href='ajk_klaim.php?er=reqrefund'><span>Data Refund</span></a>
							<li class='has-sub'><a href='ajk_pembatalan.php?'><span>Data Pembatalan</span></a>
							<li class='has-sub'><a href='ajk_klaim.php?er=reqklaim'><span>Data Klaim</span></a>
						</ul>
					</li>
					<li class='has-sub'><a href='#'><span>Master Laporan</span></a>
						<ul><li class='has-sub'><a href='er_peserta.php'><span>Laporan Kepesertaan</span></a>
							<li class='has-sub'><a href='er_peserta.php?er=spk'><span>Laporan SPK</span></a></ul>
					</li>
					<li><a href='login.php?op=logout'><span>Logout</span></a></li>
					<li class='displayname'>".strtoupper($q['nm_lengkap'])." (".$q['cabang'].")</li>
				</ul>
			</div><br /><br />";
}
elseif ($q['level'] == 7 AND $q['id_cost'] !="" AND $q['id_polis']>1) {	//PRODUK SELAIN SPK//
$_temp = "<div id='cssmenu'>
				<ul><li><a href='index.php'><span>Home</span></a></li>
					<!--<li class='has-sub'><a href='#'><span>Master Account</span></a>
						<ul><li class='has-sub'><a href='ajk_user.php'><span>Administrator</span></a>
							<li class='has-sub'><a href='ajk_polis.php'><span>Master Polis</span></a>
							<li class='has-sub'><a href='ajk_wilayah.php'><span>Wilayah</span></a>
						</ul>
					</li>-->
					<li class='has-sub'><a href='#'><span>Master Upload</span></a>
						<ul><li class='has-sub'><a href='ajk_uploader.php?er=spaj'><span>Upload Data Peserta SPAJ/SPD</span></a>
							<li class='has-sub'><a href='#'><span>Validasi Data</span></a>
							<ul><li class='last'><a href='ajk_val_upl.php?v=spaj'><span>Data Upload SPAJ/SPD</span></a>
								<li class='last'><a href='ajk_val_batal.php'><span>Data Pembatalan Peserta</span></a>
								<li class='last'><a href='ajk_klaim.php?er=valRefund'><span>Data Refund Peserta</span></a>
								<li class='last'><a href='ajk_klaim.php?er=valKlaim'><span>Data Pengajuan Klaim</span></a>
								<!--<li class='last'><a href='ajk_val_upl.php?v=spk'><span>Data SPK</span></a>-->
							</ul>
							<li class='has-sub'><a href='ajk_photo.php'><span>Upload Photo Peserta</span></a>
						</ul>
					</li>
					<li class='has-sub'><a href='#'><span>Master Data</span></a>
						<ul><li class='has-sub'><a href='ajk_peserta.php'><span>Data Kepesertaan</span></a>
							<li class='has-sub'><a href='ajk_nota.php?er=dn'><span>Data Debit Note</span></a>
							<li class='has-sub'><a href='ajk_nota.php?er=cn'><span>Data Credit note</span></a>
							<li class='has-sub'><a href='ajk_peserta.php?er=pending'><span>Data Pending/Medical SKKT</span></a>
							<li class='has-sub'><a href='ajk_md_spk.php'><span>Data SPK</span></a>
						</ul>
					</li>

					<li class='has-sub'><a href='#'><span>Master Klaim</span></a>
						<ul><li class='has-sub'><a href='ajk_klaim.php?er=reqrefund'><span>Data Refund</span></a>
							<li class='has-sub'><a href='ajk_pembatalan.php'><span>Data Pembatalan</span></a>
							<li class='has-sub'><a href='ajk_klaim.php?er=reqklaim'><span>Data Klaim</span></a>
						</ul>
					</li>
					<li class='has-sub'><a href='#'><span>Master Laporan</span></a>
						<ul><li class='has-sub'><a href='er_peserta.php'><span>Laporan Kepesertaan</span></a>
							<li class='has-sub'><a href='er_dn.php'><span>Laporan Debit Note</span></a>
							<li class='has-sub'><a href='er_cn.php'><span>Laporan Credit note</span></a>
						</ul>
					</li>
					<li><a href='login.php?op=logout'><span>Logout</span></a></li>
					<li class='displayname'>".strtoupper($q['nm_lengkap'])." (".$q['cabang'].")</li>
	</ul>
	</div><br /><br />";
}elseif ($q['level'] == 6 AND $q['id_cost'] !="") {	//PRODUK SELAIN SPK//
$_temp = "<div id='cssmenu'>
				<ul><li><a href='index.php'><span>Home</span></a></li>
					<!--<li class='has-sub'><a href='#'><span>Master Account</span></a>
						<ul><li class='has-sub'><a href='ajk_user.php'><span>Administrator</span></a>
							<li class='has-sub'><a href='ajk_polis.php'><span>Master Polis</span></a>
							<li class='has-sub'><a href='ajk_wilayah.php'><span>Wilayah</span></a>
						</ul>
					</li>-->
					<li class='has-sub'><a href='#'><span>Master Upload</span></a>
						<ul><li class='has-sub'><a href='#'><span>Validasi Data</span></a>
							<ul><li class='last'><a href='ajk_val_upl.php?v=spaj'><span>Data Upload SPAJ/SPD</span></a>
								<li class='last'><a href='ajk_val_batal.php'><span>Data Pembatalan Peserta</span></a>
								<li class='last'><a href='ajk_klaim.php?er=valRefund'><span>Data Refund Peserta</span></a>
								<li class='last'><a href='ajk_klaim.php?er=valKlaim'><span>Data Pengajuan Klaim</span></a>
								<!--<li class='last'><a href='ajk_val_upl.php?v=spk'><span>Data SPK</span></a>-->
							</ul>
							<li class='has-sub'><a href='ajk_photo.php'><span>Upload Photo Peserta</span></a>
						</ul>
					</li>
					<li class='has-sub'><a href='#'><span>Master Data</span></a>
						<ul><li class='has-sub'><a href='ajk_peserta.php'><span>Data Kepesertaan</span></a>
							<li class='has-sub'><a href='ajk_nota.php?er=dn'><span>Data Debit Note</span></a>
							<li class='has-sub'><a href='ajk_nota.php?er=cn'><span>Data Credit note</span></a>
							<li class='has-sub'><a href='ajk_peserta.php?er=pending'><span>Data Pending/Medical SKKT</span></a>
							<li class='has-sub'><a href='ajk_md_spk.php'><span>Data SPK</span></a>
						</ul>
					</li>

					<li class='has-sub'><a href='#'><span>Master Klaim</span></a>
						<ul><li class='has-sub'><a href='ajk_klaim.php?er=reqrefund'><span>Data Refund</span></a>
							<li class='has-sub'><a href='ajk_pembatalan.php'><span>Data Pembatalan</span></a>
							<li class='has-sub'><a href='ajk_klaim.php?er=reqklaim'><span>Data Klaim</span></a>
						</ul>
					</li>
					<li class='has-sub'><a href='#'><span>Master Laporan</span></a>
						<ul><li class='has-sub'><a href='er_peserta.php'><span>Laporan Kepesertaan</span></a>
							<li class='has-sub'><a href='er_dn.php'><span>Laporan Debit Note</span></a>
							<li class='has-sub'><a href='er_cn.php'><span>Laporan Credit note</span></a>
						</ul>
					</li>
					<li><a href='login.php?op=logout'><span>Logout</span></a></li>
					<li class='displayname'>".strtoupper($q['nm_lengkap'])." (".$q['cabang'].")</li>
	</ul>
	</div><br /><br />";
}
else
	{	echo "<div id='cssmenu'><ul><li><a href='login.php?op=logout'><span>Logout</span></a></li></ul></div>";	}

echo "<script type='text/javascript'>
	$(function() {
		$(window).scroll(function(){
		var scrollTop = $(window).scrollTop();
		if(scrollTop != 0)
			$('#cssmenu').stop().animate({'opacity':'0.5'},400);
			else
			$('#cssmenu').stop().animate({'opacity':'1'},400);
		});

		$('#cssmenu').hover(
		function (e) {
			var scrollTop = $(window).scrollTop();
			if(scrollTop != 0){
			$('#cssmenu').stop().animate({'opacity':'1'},400);
			}
		},
		function (e) {
			var scrollTop = $(window).scrollTop();
			if(scrollTop != 0){
				$('#cssmenu').stop().animate({'opacity':'0.5'},400);
			}
		}
		);
	});
	</script>";
	if (!$q['nm_user']) {	}else{	out('menus', $_temp);	}
	}
}

?>