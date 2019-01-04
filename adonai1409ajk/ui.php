<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// 2014
// ----------------------------------------------------------------------------------
error_reporting(0);
session_start();
ob_start("ui_output_callback");
include_once('ajkp1708.php');
include_once('includes/metImage.php');
include_once('../includes/functions.php');
include_once('../includes/db.php');
include_once('../includes/excel_reader2.php');
include_once('../includes/smtp_classes/library.php'); // include the library file
include_once('../includes/smtp_classes/class.phpmailer.php'); // include the class name
include_once('../includes/smtp_classes/class.smtp.php'); // include the class smtp

global $database;
$database = new db();
$ui_template = dirname(__FILE__)."/templates/". theme . "/index.html";

out('site_title', 'Asuransi Jiwa Kredit dan Pensiunan - ADONAI | Pialang Asuransi');
out('template_name', theme);
out('copyright', '<br />&copy; Copyright '.date(Y).' <span>ADONAI</span> | Pialang Asuransi. All Right Reserved');

$met_ttdsize = 1024; // max file size (1Mb)
$met_spaksize = 2048; // max file size (1Mb)
$metpath_ttd = "../ajk_file/_ttd/";
$metpath_file = "../ajk_file/_spak/";
$dok_klaim_ajk = "../ajk_file/klaim/";
$metpathFIlePaid = "../ajk_file/_armpaid/";
$allowedExts = array("application/pdf", "image/jpg", "image/jpeg");
checkLogin();
getLogin();
debug();
generateMenu();

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
    if (!isset($_SESSION['nm_user'])) {
        return "Illegal User";
    }
    return $_SESSION["nm_user"];
}
function debug()
{
    if ($_REQUEST['debug'] == 1) {
        foreach ($_REQUEST as $k => $v) {
            echo $k . ' : ' . $v . '<br />';
        }
    }
}
function generateMenu()
{
    if (isset($_SESSION['nm_user'])) {
        $q=mysql_fetch_array(mysql_query('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'" AND id_cost=""'));
        function get_menu($data, $parent = 0)
        {
            static $i = 1;
            $tab = str_repeat("\t\t", $i);
            if (isset($data[$parent])) {
                $html = "\n$tab<ul>";
                $i++;
                foreach ($data[$parent] as $v) {
                    $child = get_menu($data, $v->id);
                    $html .= "\n\t$tab<li>";
                    $html .= '<a href="'.$v->menuurl.'">'.$v->menu.'</a>';
                    if ($child) {
                        $i--;
                        $html .= $child;
                        $html .= "\n\t$tab";
                    }
                    $html .= '</li>';
                }
                $html .= "\n$tab</ul>";
                return $html;
            } else {
                return false;
            }
        }

        if ($q['level']=="" or ($q['level']=="4" and $q['status']=="UNDERWRITING")) {
            $result = mysql_query('SELECT * FROM fu_ajk_menus WHERE del is null ORDER BY sub');
        } else {
            $result = mysql_query('SELECT fu_ajk_menus.id,
									  fu_ajk_menus.menu,
									  fu_ajk_menus.menuurl,
									  fu_ajk_menus.parent,
									  fu_ajk_menus.sub
							   FROM fu_ajk_menus
							   RIGHT JOIN fu_ajk_menususer ON fu_ajk_menus.id = fu_ajk_menususer.idmenu
							   LEFT JOIN pengguna ON fu_ajk_menususer.iduser = pengguna.id
							   WHERE fu_ajk_menususer.iduser = "'.$q['id'].'"
								 AND fu_ajk_menus.del is null
							   ORDER BY fu_ajk_menus.sub');
        }
        while ($row = mysql_fetch_object($result)) {
            $data[$row->parent][] = $row;
        }



        $menu = get_menu($data);


        $_temp = '<link rel="stylesheet" type="text/css" href="metmenus/jquerycssmenu.css" />

		  <div id="myjquerymenu" class="jquerycssmenu">'.$menu.' <br div align="right"></div><style="clear: left" /></div>';
    } else {
    }
    /*
    out('head','<LINK href="includes/tabs/tabpane.css" rel="stylesheet" type="text/css"/>
    <link rel="SHORTCUT ICON" href="images/locations.png" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="includes/js/menus/theme.css"/>
    <script language="JavaScript" src="includes/js/menus/JSCookMenu_mini.js" type="text/javascript"></script>
    <script language="JavaScript" src="includes/js/menus/joomla.js" type="text/javascript"></script>
    <script language="JavaScript" src="includes/js/menus/theme.js" type="text/javascript"></script>
    <link rel="stylesheet" href="includes/js/menus/theme.css" type="text/css">
    <link href="includes/calendar/calendar-mos.css" rel="stylesheet" type="text/css">
    <script language="JavaScript" src="includes/calendar/calendar.js" type="text/javascript"></script>
    <script language="JavaScript" src="includes/calendar/lang/calendar-id.js" type="text/javascript"></script>
    <script language="JavaScript" src="includes/js/main.js" type="text/javascript"></script>
    <script language="JavaScript" src="includes/js/popup.js" type="text/javascript"></script>
    <script language="JavaScript" src="includes/tabs/tabpane.js" type="text/javascript"></script>');
    if (isset($_SESSION['nm_user'])) {	$q=mysql_fetch_array(mysql_query('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'" AND id_cost=""'));	}
        if ($q['status']=="UNDERWRITING" AND $q['level']!="99")	//UNDERWRITING
        {
        $_temp = '<div id="myMenuID"></div><script language="JavaScript" type="text/javascript">
        var myMenu =
        [
        [\'\',\'Home\',\'index.php\',null,\'Halaman Depan\'],_cmSplit,
        [\'\',\'Control Panel\',null,null,\'Control Panel\',
            [\''._IMGLOGDL.'\',\'Setting Logo Perusahaan\',\'set_ttd.php?r=logoheader\',null,\'Setting Logo Perusahaan\'],_cmSplit,
            [\''._IMGLOGDL.'\',\'Setting Tanda Tangan\',\'set_ttd.php\',null,\'Setting Tanda Tangan\'],_cmSplit,
                <!--[\''._IMGLOGDL.'\',\'Log User Download PDF\',\'trj_logdl.php\',null,\'User Download PDF\'],_cmSplit,-->
                [\''._IMGMSPOLIS.'\',\'History User\',\'\',null,\'History User\',
                    [\''._IMGVALIDATE.'\',\'Website\',\'trj_log.php\',null,\'Website\'],_cmSplit,
                    [\''._IMGVALIDATE.'\',\'Tablet\',\'trj_log.php?h=tab\',null,\'Tablet\'],_cmSplit,
                ],_cmSplit,
            [\''._IMGLOGUSER.'\',\'Reset Password\',\'master_setting.php?er=qSet_passw\',null,\'Reset Password\'],_cmSplit,
            [\''._IMGLOGUSER.'\',\'Tab Generator\',\'master_setting.php?er=tabgen\',null,\'Tab Generator\'],_cmSplit,
            [\''._IMGLOGUSER.'\',\'Tablet Gps\',\'master_setting.php?er=tabgps\',null,\'Tablet Gps\'],_cmSplit,
        ],_cmSplit,
        [\'\',\'Master Account\',null,null,\'Master Account\',
            [\''._IMGACCOUNT.'\',\'Account Web\',\'user.php\',null,\'Account Web\'],_cmSplit,
            [\''._IMGACCOUNT.'\',\'Account Mobile\',\'user.php?op=vumobile\',null,\'Account Mobile\'],_cmSplit,
            [\''._IMGAGENTS.'\',\'Agents\',\'ajk_reg_agent.php\',null,\'Agents\'],_cmSplit,
        ],_cmSplit,
        [\'\',\'Master Setup\',null,null,\'Master Setup\',
                [\''._IMGCOSTUMERS.'\',\'Setup Bank\',\'ajk_reg_cost.php\',null,\'Setup Bank\'],
                [\''._IMGCOSTUMERS.'\',\'Setup Rekening Bank\',\'ajk_reg_cost.php?r=rek\',null,\'Setup Bank\'],
                [\''._IMGMSPOLIS.'\',\'Produk Bank\',\'ajk_mspolis.php\',null,\'Produk Bank\'],
                [\''._IMGMSPOLIS.'\',\'Setting Rate Bank\',\'\',null,\'Setting Rate Bank\',
                    [\''._IMGVALIDATE.'\',\'Rate Premi Bank\',\'ajk_setrate.php?er=setpremi\',null,\'Rate Premi Bank\'],
                    [\''._IMGVALIDATEMOVE.'\',\'Rate Refund Bank\',\'ajk_setrate.php?er=setrefund\',null,\'Rate Refund Bank\'],
                    [\''._IMGVALIDATEMOVE.'\',\'Penurunan UP Bank\',\'ajk_setrate.php?er=setklaim\',null,\'Penurunan UP Bank\'],
                    [\''._IMGVALIDATEMOVE.'\',\'Tabel Medis Bank\',\'ajk_setrate.php?er=setmedical\',null,\'Tabel Medis Bank\'],
                ],
                [\''._IMGVALIDATEMOVE.'\',\'Setting Dokumen Klaim\',\'ajk_dokklaim.php?dok=setbankklaim\',null,\'Setting Dokumen Klaim\'],_cmSplit,
                [\''._IMGCOSTUMERS.'\',\'Setup Asuransi\',\'ajk_reg_as.php\',null,\'Setup Asuransi\'],
                [\''._IMGMSPOLIS.'\',\'Polis Asuransi\',\'ajk_mspolis_as.php\',null,\'Polis Asuransi\'],
                [\''._IMGMSPOLIS.'\',\'Rate Premi Asuransi\',\'ajk_mspolis_as.php\',null,\'Rate Premi Asuransi\'],_cmSplit,
                [\''._IMGMSPOLIS.'\',\'Level User\',\'ajk_lvluser.php\',null,\'Level User\'],_cmSplit,
                [\''._IMGMSPOLIS.'\',\'Level Cabang\',\'ajk_lvlcabang.php\',null,\'Level Cabang\'],_cmSplit,
                [\''._IMGMSPOLIS.'\',\'Dokumen Klaim\',\'ajk_dokklaim.php\',null,\'Dokumen Klaim\'],_cmSplit,
                [\''._IMGMSPOLIS.'\',\'Group Produk\',\'ajk_grupprod.php\',null,\'Group Produk\'],_cmSplit,
            ],_cmSplit,
            [\'\',\'Upload Data Kepesertaan\',null,null,\'Upload Data Kepesertaan\',
                [\''._IMGUPLDATA.'\',\'Kepesertaan SPAJ/SPD\',\'ajk_uploader_peserta.php\',null,\'Kepesertaan SPAJ\'],_cmSplit,
                [\''._IMGUPLDATA.'\',\'Kepesertaan SPK\',\'ajk_uploader_spak.php\',null,\'Kepesertaan SPK\'],_cmSplit,
                [\''._IMGUPLDATA.'\',\'Kepesertaan General\',\'ajk_uploader_general.php\',null,\'Kepesertaan General\'],_cmSplit,
                [\''._IMGVALIDATE.'\',\'Validasi Data Upload\',null,null,\'Validasi Data Upload\',_cmSplit,
                    [\''._IMGVALIDATE.'\',\'Peserta Baru SPAJ/SPD\',\'ajk_uploader_peserta.php?r=viewall\',null,\'Peserta Baru\'],_cmSplit,
                    [\''._IMGVALIDATEMOVE.'\',\'Peserta Baru SPK\',\'ajk_uploader_spk.php?r=viewallspk\',null,\'Peserta SPK\'],_cmSplit,
                ],_cmSplit,
                [\''._IMGPESERTA.'\',\'Data Peserta\',\'ajk_uploader_fu.php\',null,\'Data Peserta\'],_cmSplit,
                [\''._IMGCEKMEDIK.'\',\'Cek Medical SKKT\',\'ajk_cekmedik_fu.php\',null,\'Cek Medical\'],_cmSplit,
            ],_cmSplit,
        [\'\',\'Invoice\',null,null,\'Invoice\',
            [\''._IMGNOTADN.'\',\'Debit Note (DN)\',null,null,\'Debit Note\',
                [\''._IMGINVDN.'\',\'Buat Debit Note (DN)\',\'ajk_dn.php\',null,\'Create Debit Note\'],_cmSplit,
                [\''._IMGINVDOK.'\',\'Invoice Debit Note (DN)\',\'ajk_dn.php?r=viewdn\',null,\'Invoice Debit note\'],_cmSplit,
            ],_cmSplit,
            [\''._IMGNOTACN.'\',\'Credit Note (CN)\',\'ajk_cn.php\',null,\'Credit Note\',],_cmSplit,
    
        ],_cmSplit,
        [\'\',\'Klaim\',null,null,\'Claim\',_cmSplit,
            [\''._IMGKLAIMDIE.'\',\'Batal\',\'ajk_klaim.php?fu=er_batal\',null,\'Batal\'],_cmSplit,
            [\''._IMGKLAIMDIE.'\',\'Refund\',\'ajk_klaim.php?fu=refund\',null,\'Refund\'],_cmSplit,
            [\''._IMGKLAIMDIE.'\',\'Meninggal\',\'ajk_claim.php\',null,\'Meninggal\'],_cmSplit,
        ],_cmSplit,
        [\'\',\'Report\',null,null,\'Report\',
            [\''._IMGCOSTUMERS.'\',\'Rekapitulasi Bank\',\'ajk_re_bank.php\',null,\'Laporan Rekapitulasi Bank\'],_cmSplit,
            [\''._IMGCOSTUMERS.'\',\'Rekapitulasi Asuransi\',\'ajk_re_asuransi.php\',null,\'Laporan Rekapitulasi Asuransi\'],_cmSplit,
            [\''._IMGCOSTUMERS.'\',\'Rekapitulasi Klaim\',null,null,\'Laporan Rekapitulasi Klaim\',_cmSplit,
                [\''._IMGINVDN.'\',\'Batal\',\'ajk_re_claim.php?c=batal\',null,\'Laporan Batal\'],_cmSplit,
                [\''._IMGINVDN.'\',\'Refund\',\'ajk_re_claim.php?c=refund\',null,\'Laporan Refund\'],_cmSplit,
                [\''._IMGINVDN.'\',\'Meninggal\',\'ajk_re_claim.php?c=klaim\',null,\'Laporan Meninggal\'],_cmSplit,
            ],_cmSplit,
            <!--[\''._IMGCOSTUMERS.'\',\'Produksi\',\'ajk_re_produksi.php\',null,\'Laporan Produksi\'],_cmSplit,-->
            <!--[\''._IMGCOSTUMERS.'\',\'SPK\',\'ajk_re_spk.php\',null,\'Laporan SPK\'],_cmSplit,-->
                [\''._IMGCOSTUMERS.'\',\'SPK\',null,null,\'Laporan Summary\',
                    [\''._IMGCOSTUMERS.'\',\'Data SPK\',\'ajk_re_spk.php\',null,\'Laporan SPK\'],_cmSplit,
                    [\''._IMGCOSTUMERS.'\',\'Status SPK\',\'ajk_re_spk.php?er=sSPK\',null,\'Laporan SPK\'],_cmSplit,
                    [\''._IMGCOSTUMERS.'\',\'Historical Klaim SPK\',\'ajk_re_spk.php?er=hisSPK\',null,\'Laporan SPK\'],_cmSplit,
                    [\''._IMGCOSTUMERS.'\',\'Rekap Summary SPK\',\'ajkspk.php?er=rkpsumm\',null,\'Rekap Summary SPK\'],_cmSplit,
                    [\''._IMGCOSTUMERS.'\',\'Rekap User SPK\',\'ajkspk.php?er=rkpuser\',null,\'Rekap User SPK\'],_cmSplit,
                ],_cmSplit,
                [\''._IMGCOSTUMERS.'\',\'Dokter\',\'ajk_re_dok.php\',null,\'Laporan Dokter\'],_cmSplit,
                [\''._IMGCOSTUMERS.'\',\'Summary Data\',null,null,\'Laporan Summary\',
                    [\''._IMGINVDN.'\',\'Statistik Rangking\',\'ajk_re_summary.php?c=ranking\',null,\'Statistik Rangking\'],_cmSplit,
                    [\''._IMGINVDN.'\',\'Lost Rasio\',\'ajk_re_summary.php?c=lostrasio\',null,\'Lost Rasio\'],_cmSplit,
                    [\''._IMGINVDN.'\',\'Klaim\',\'ajk_re_summary.php?c=klaim\',null,\'Klaim\'],_cmSplit,
                    [\''._IMGINVDN.'\',\'Produksi\',\'ajk_re_summary.php?c=produksi\',null,\'Produksi\'],_cmSplit,
                    [\''._IMGINVDN.'\',\'Tagihan Premi\',\'ajk_re_summary.php?c=tagprem\',null,\'Tagihan Premi\'],_cmSplit,
                    [\''._IMGINVDN.'\',\'Produksi Percabang\',\'ajk_re_summary.php?c=prodcab\',null,\'Produksi Percabang\'],_cmSplit,
                    [\''._IMGINVDN.'\',\'Pemeriksaan Kesehatan\',\'ajk_re_summary.php?c=pemkes\',null,\'Pemeriksaan Kesehatan\'],_cmSplit,
                ],_cmSplit,
                [\''._IMGCOSTUMERS.'\',\'Covering Letter\',\'ajk_re_dok.php?dok=cl\',null,\'Covering Letter\'],_cmSplit,
                <!--[\''._IMGCOSTUMERS.'\',\'Memorandum Creditnote\',\'ajk_re_claim.php?c=memocn\',null,\'Memorandum Creditnote\'],_cmSplit, DIPINDAHIN KE BAGIAN FRONTEND 191115-->
        ],_cmSplit,
        [\'\',\'Editor\',\'scase.php\',null,\'Editor\'],_cmSplit,
        [\'\',\'Logout\',\'login.php?op=logout\',null,\'Logout\'],_cmSplit,
        ];
        cmDraw (\'myMenuID\', myMenu, \'hbr\', cmThemeOffice, \'ThemeOffice\');
        </script>';
        }
        elseif ($q['status']=="ARM")	//ARM
        {
            $_temp = '<div id="myMenuID"></div><script language="JavaScript" type="text/javascript">
            var myMenu =
            [
            [\'\',\'Home\',\'index.php\',null,\'Halaman Depan\'],_cmSplit,
            [\'\',\'Invoice\',null,null,\'Invoice\',
                [\''._IMGNOTADN.'\',\'Debit Note (DN)\',null,null,\'Debit Note\',
                    [\''._IMGINVDOK.'\',\'Invoice Debit Note (DN)\',\'ajk_dn.php?r=viewdn\',null,\'Invoice Debit note\'],_cmSplit,
                ],_cmSplit,
                [\''._IMGARM.'\',\'ARM\',null,null,\'PRM\',
                    <!--[\''._IMGREGARM.'\',\'Upload Data Pembayaran\',\'ajk_prm_payment_dn.php?r=paidData\',null,\'Upload Data Pembayaran\'], PENDING DATA UPDATE BAYAR DENGAN UPLOAD EXCEL 150120-->
                        [\''._IMGARM.'\',\'Pembayaran\',null,null,\'Pembayaran\',
                            [\''._IMGREGARM.'\',\'Debit Note\',\'ajk_prm_payment_dn.php\',null,\'Debit Note\'],_cmSplit,
                            [\''._IMGREGARM.'\',\'Peserta\',\'ajk_paid.php?r=peserta\',null,\'Peserta\'],_cmSplit,
                        ],_cmSplit,
                    [\''._IMGUPDDN.'\',\'Update Status DN\',\'ajk_prm.php?op=dnassign\',null,\'Update Status DN\'],_cmSplit,
                    [\''._IMGUPDDN.'\',\'Update Status CN\',\'ajk_prm.php?op=cnassign\',null,\'Update Status CN\'],_cmSplit,
                ],_cmSplit,
                [\''._IMGNOTACN.'\',\'Credit Note (CN)\',\'ajk_cn.php\',null,\'Credit Note\',],_cmSplit,
                [\''._IMGNOTACN.'\',\'Credit Note (CN) - Pembatalan\',\'ajk_cn.php?fu=cnbatal\',null,\'Credit Note\',],_cmSplit,
            ],_cmSplit,
    [\'\',\'Klaim\',null,null,\'Claim\',_cmSplit,
            [\''._IMGKLAIMDIE.'\',\'Batal\',\'ajk_klaim.php?fu=er_batal\',null,\'Batal\'],_cmSplit,
            [\''._IMGKLAIMDIE.'\',\'Refund\',\'ajk_klaim.php?fu=refund\',null,\'Refund\'],_cmSplit,
            [\''._IMGKLAIMDIE.'\',\'Meninggal\',\'ajk_claim.php\',null,\'Meninggal\'],_cmSplit,
        ],_cmSplit,
            [\'\',\'Report\',null,null,\'Report\',
                [\''._IMGCOSTUMERS.'\',\'Laporan Rekapitulasi Bank\',\'ajk_re_bank.php\',null,\'Laporan Rekapitulasi Bank\'],_cmSplit,
                [\''._IMGCOSTUMERS.'\',\'Laporan Rekapitulasi Asuransi\',\'ajk_re_asuransi.php\',null,\'Laporan Rekapitulasi Asuransi\'],_cmSplit,
                [\''._IMGCOSTUMERS.'\',\'Laporan Rekapitulasi Klaim\',\'ajk_re_claim.php\',null,\'Laporan Rekapitulasi Klaim\'],_cmSplit,
                <!--[\''._IMGCOSTUMERS.'\',\'Laporan Produksi\',\'ajk_re_produksi.php\',null,\'Laporan Produksi\'],_cmSplit,-->
            ],_cmSplit,
            [\'\',\'Logout\',\'login.php?op=logout\',null,\'Logout\'],_cmSplit,
            ];
            cmDraw (\'myMenuID\', myMenu, \'hbr\', cmThemeOffice, \'ThemeOffice\');
            </script>';
        }
        elseif ($q['level']=="99" AND $q['id_cost']=="")	//DOKTER
        {
            $_temp = '<div id="myMenuID"></div><script language="JavaScript" type="text/javascript">
            var myMenu =
            [
            [\'\',\'Home\',\'index.php\',null,\'Halaman Depan\'],_cmSplit,
            [\'\',\'Upload Data Kepesertaan\',null,null,\'Upload Data Kepesertaan\',
                <!--[\''._IMGUPLDATA.'\',\'Kepesertaan SPK\',\'ajk_uploader_spak.php\',null,\'Kepesertaan SPK\'],_cmSplit,-->
                [\''._IMGUPLDATA.'\',\'Kepesertaan SPK\',\'ajk_uploader_spak.php?r=set_spak\',null,\'Kepesertaan SPK\'],_cmSplit,
                [\''._IMGUPLDATA.'\',\'Data SPK\',\'ajk_uploader_spak.php?r=spk_app\',null,\'Data SPK\'],_cmSplit,
                [\''._IMGVALIDATE.'\',\'Validasi Data Upload\',null,null,\'Validasi Data Upload\',_cmSplit,
                    [\''._IMGVALIDATEMOVE.'\',\'Peserta SPK\',\'ajk_uploader_spk.php?r=viewallspk\',null,\'Peserta SPK\'],_cmSplit,
                ],_cmSplit,
                [\''._IMGPESERTA.'\',\'Data Peserta\',\'ajk_uploader_fu.php\',null,\'Data Peserta\'],_cmSplit,
                [\''._IMGCEKMEDIK.'\',\'Cek Medical SKKT\',\'ajk_cekmedik_fu.php\',null,\'Cek Medical\'],_cmSplit,
            ],_cmSplit,
            [\'\',\'Report\',null,null,\'Report\',
                [\''._IMGCOSTUMERS.'\',\'Laporan SPK\',\'ajk_re_spk.php\',null,\'Laporan SPK\'],_cmSplit,
                [\''._IMGCOSTUMERS.'\',\'Laporan Dokter\',\'ajk_re_dok.php\',null,\'Laporan Dokter\'],_cmSplit,
            ],_cmSplit,
            [\'\',\'Logout\',\'login.php?op=logout\',null,\'Logout\'],_cmSplit,
            ];
            cmDraw (\'myMenuID\', myMenu, \'hbr\', cmThemeOffice, \'ThemeOffice\');
            </script>';
        }
        elseif(isset($_SESSION['nm_user']))
        {
            $_temp = '<div id="myMenuID"></div><script language="JavaScript" type="text/javascript">
            var myMenu =
            [
            [\'\',\'Home\',\'index.php\',null,\'Halaman Depan\'],_cmSplit,
            [\'\',\'Control Panel\',null,null,\'Control Panel\',
                [\''._IMGLOGDL.'\',\'Setting Tanda Tangan\',\'set_ttd.php\',null,\'Setting Tanda Tangan\'],_cmSplit,
                [\''._IMGLOGDL.'\',\'Setting Logo Perusahaan\',\'set_ttd.php?r=logoheader\',null,\'Setting Logo Perusahaan\'],_cmSplit,
                <!--[\''._IMGLOGDL.'\',\'Log User Download PDF\',\'trj_logdl.php\',null,\'User Download PDF\'],_cmSplit,-->
                [\''._IMGMSPOLIS.'\',\'History User\',\'\',null,\'History User\',
                    [\''._IMGVALIDATE.'\',\'Website\',\'trj_log.php\',null,\'Website\'],_cmSplit,
                    [\''._IMGVALIDATE.'\',\'Tablet\',\'trj_log.php?h=tab\',null,\'Tablet\'],_cmSplit,
                ],_cmSplit,
                [\''._IMGLOGUSER.'\',\'Reset Password\',\'master_setting.php?er=qSet_passw\',null,\'Reset Password\'],_cmSplit,
                [\''._IMGLOGUSER.'\',\'Tab Generator\',\'master_setting.php?er=tabgen\',null,\'Tab Generator\'],_cmSplit,
                [\''._IMGLOGUSER.'\',\'Tablet Gps\',\'master_setting.php?er=tabgps\',null,\'Tablet Gps\'],_cmSplit,
            ],_cmSplit,
            [\'\',\'Master Account\',null,null,\'Master Account\',
                [\''._IMGACCOUNT.'\',\'Account Web\',\'user.php\',null,\'Account Web\'],_cmSplit,
                [\''._IMGACCOUNT.'\',\'Account Mobile\',\'user.php?op=vumobile\',null,\'Account Mobile\'],_cmSplit,
                [\''._IMGAGENTS.'\',\'Agents\',\'ajk_reg_agent.php\',null,\'Agents\'],_cmSplit,
            ],_cmSplit,
               [\'\',\'Master Setup\',null,null,\'Master Setup\',
                [\''._IMGCOSTUMERS.'\',\'Setup Bank\',\'ajk_reg_cost.php\',null,\'Setup Bank\'],
                [\''._IMGCOSTUMERS.'\',\'Setup Rekening Bank\',\'ajk_reg_cost.php?r=rek\',null,\'Setup Bank\'],
                [\''._IMGMSPOLIS.'\',\'Produk Bank\',\'ajk_mspolis.php\',null,\'Produk Bank\'],
                [\''._IMGMSPOLIS.'\',\'Setting Rate Bank\',\'\',null,\'Setting Rate Bank\',
                    [\''._IMGVALIDATE.'\',\'Rate Premi Bank\',\'ajk_setrate.php?er=setpremi\',null,\'Rate Premi Bank\'],
                    [\''._IMGVALIDATEMOVE.'\',\'Rate Refund Bank\',\'ajk_setrate.php?er=setrefund\',null,\'Rate Refund Bank\'],
                    [\''._IMGVALIDATEMOVE.'\',\'Penurunan UP Bank\',\'ajk_setrate.php?er=setklaim\',null,\'Penurunan UP Bank\'],
                    [\''._IMGVALIDATEMOVE.'\',\'Tabel Medis Bank\',\'ajk_setrate.php?er=setmedical\',null,\'Tabel Medis Bank\'],
                ],
                [\''._IMGVALIDATEMOVE.'\',\'Setting Dokumen Klaim\',\'ajk_dokklaim.php?dok=setbankklaim\',null,\'Setting Dokumen Klaim\'],_cmSplit,
                [\''._IMGCOSTUMERS.'\',\'Setup Asuransi\',\'ajk_reg_as.php\',null,\'Setup Asuransi\'],
                [\''._IMGMSPOLIS.'\',\'Polis Asuransi\',\'ajk_mspolis_as.php\',null,\'Polis Asuransi\'],
                [\''._IMGMSPOLIS.'\',\'Setting Rate Asuransi\',\'\',null,\'Setting Rate\',
                    [\''._IMGVALIDATE.'\',\'Rate Premi Asuransi\',\'ajk_setrate_as.php?er=setpremi\',null,\'Rate Premi Asuransi\'],
                    [\''._IMGVALIDATEMOVE.'\',\'Rate Refund Asuransi\',\'ajk_setrate_as.php?er=setrefund\',null,\'Rate Refund Asuransi\'],
                    [\''._IMGVALIDATEMOVE.'\',\'Penurunan UP Asuransi\',\'ajk_setrate_as.php?er=setklaim\',null,\'Penurunan UP Asuransi\'],
                    [\''._IMGVALIDATEMOVE.'\',\'Tabel Medis Asuransi\',\'ajk_setrate_as.php?er=setmedical\',null,\'Tabel Medis Asuransi\'],
                ],_cmSplit,
                [\''._IMGMSPOLIS.'\',\'Level User\',\'ajk_lvluser.php\',null,\'Level User\'],_cmSplit,
                [\''._IMGMSPOLIS.'\',\'Level Cabang\',\'ajk_lvlcabang.php\',null,\'Level Cabang\'],_cmSplit,
                [\''._IMGMSPOLIS.'\',\'Dokumen Klaim\',\'ajk_dokklaim.php\',null,\'Dokumen Klaim\'],_cmSplit,
                [\''._IMGMSPOLIS.'\',\'Group Produk\',\'ajk_grupprod.php\',null,\'Group Produk\'],_cmSplit,
            ],_cmSplit,
            [\'\',\'Upload Data Kepesertaan\',null,null,\'Upload Data Kepesertaan\',
                [\''._IMGUPLDATA.'\',\'Kepesertaan SPAJ/SPD\',\'ajk_uploader_peserta.php\',null,\'Kepesertaan SPAJ\'],_cmSplit,
                [\''._IMGUPLDATA.'\',\'Kepesertaan SPK\',\'ajk_uploader_spak.php\',null,\'Kepesertaan SPK\'],_cmSplit,
                [\''._IMGUPLDATA.'\',\'Kepesertaan General\',\'ajk_uploader_general.php\',null,\'Kepesertaan General\'],_cmSplit,
                [\''._IMGVALIDATE.'\',\'Validasi Data Upload\',null,null,\'Validasi Data Upload\',_cmSplit,
                    [\''._IMGVALIDATE.'\',\'Peserta Baru SPAJ/SPD\',\'ajk_uploader_peserta.php?r=viewall\',null,\'Peserta Baru\'],_cmSplit,
                    [\''._IMGVALIDATEMOVE.'\',\'Peserta Baru SPK\',\'ajk_uploader_spk.php?r=viewallspk\',null,\'Peserta SPK\'],_cmSplit,
                ],_cmSplit,
                [\''._IMGPESERTA.'\',\'Data Peserta\',\'ajk_uploader_fu.php\',null,\'Data Peserta\'],_cmSplit,
                [\''._IMGCEKMEDIK.'\',\'Cek Medical SKKT\',\'ajk_cekmedik_fu.php\',null,\'Cek Medical\'],_cmSplit,
            ],_cmSplit,
            [\'\',\'Invoice\',null,null,\'Invoice\',
                [\''._IMGNOTADN.'\',\'Debit Note (DN)\',null,null,\'Debit Note\',
                    [\''._IMGINVDN.'\',\'Buat Debit Note (DN)\',\'ajk_dn.php\',null,\'Create Debit Note\'],_cmSplit,
                    [\''._IMGINVDOK.'\',\'Invoice Debit Note (DN)\',\'ajk_dn.php?r=viewdn\',null,\'Invoice Debit note\'],_cmSplit,
                ],_cmSplit,
                [\''._IMGARM.'\',\'ARM\',null,null,\'PRM\',
                    [\''._IMGREGARM.'\',\'Upload Data Pembayaran\',\'ajk_prm_payment_dn.php?r=paidData\',null,\'Upload Data Pembayaran\'],
                    [\''._IMGREGARM.'\',\'Payment Register\',\'ajk_prm_payment_dn.php\',null,\'Payment Register\'],_cmSplit,
                    [\''._IMGUPDDN.'\',\'Update Status DN\',\'ajk_prm.php?op=dnassign\',null,\'Update Status DN\'],_cmSplit,
                    [\''._IMGUPDDN.'\',\'Update Status CN\',\'ajk_prm.php?op=cnassign\',null,\'Update Status CN\'],_cmSplit,
                ],_cmSplit,
                [\''._IMGNOTACN.'\',\'Credit Note (CN)\',\'ajk_cn.php\',null,\'Credit Note\',],_cmSplit,
    <!--		[\''._IMGNOTACN.'\',\'Credit Note (CN) - Pembatalan\',\'ajk_cn.php?fu=cnbatal\',null,\'Credit Note\',],_cmSplit, -->
            ],_cmSplit,
            [\'\',\'Klaim\',null,null,\'Claim\',_cmSplit,
                [\''._IMGKLAIMDIE.'\',\'Batal\',\'ajk_klaim.php?fu=er_batal\',null,\'Batal\'],_cmSplit,
                [\''._IMGKLAIMDIE.'\',\'Refund\',\'ajk_klaim.php?fu=refund\',null,\'Refund\'],_cmSplit,
                [\''._IMGKLAIMDIE.'\',\'Meninggal\',\'ajk_claim.php\',null,\'Meninggal\'],_cmSplit,
            ],_cmSplit,
            [\'\',\'Report\',null,null,\'Report\',
                [\''._IMGCOSTUMERS.'\',\'Rekapitulasi Bank\',\'ajk_re_bank.php\',null,\'Laporan Rekapitulasi Bank\'],_cmSplit,
                [\''._IMGCOSTUMERS.'\',\'Rekapitulasi Asuransi\',\'ajk_re_asuransi.php\',null,\'Laporan Rekapitulasi Asuransi\'],_cmSplit,
                <!--[\''._IMGCOSTUMERS.'\',\'Rekapitulasi Creditnote\',\'ajk_re_claim.php?c=cn\',null,\'Rekapitulasi Creditnote\'],_cmSplit, -->
                [\''._IMGCOSTUMERS.'\',\'Rekapitulasi Klaim\',null,null,\'Laporan Rekapitulasi Klaim\',
                    [\''._IMGINVDN.'\',\'Laporan Batal\',\'ajk_re_claim.php?c=batal\',null,\'Laporan Batal\'],_cmSplit,
                    [\''._IMGINVDN.'\',\'Laporan Refund\',\'ajk_re_claim.php?c=refund\',null,\'Laporan Refund\'],_cmSplit,
                    [\''._IMGINVDN.'\',\'Laporan Meninggal\',\'ajk_re_claim.php?c=klaim\',null,\'Laporan Meninggal\'],_cmSplit,
                ],_cmSplit,
                <!--[\''._IMGCOSTUMERS.'\',\'Laporan Produksi\',\'ajk_re_produksi.php\',null,\'Laporan Produksi\'],_cmSplit,-->
                <!--[\''._IMGCOSTUMERS.'\',\'SPK\',\'ajk_re_spk.php\',null,\'Laporan SPK\'],_cmSplit,-->
                    [\''._IMGCOSTUMERS.'\',\'SPK\',null,null,\'Laporan Summary\',
                        [\''._IMGCOSTUMERS.'\',\'Data SPK\',\'ajk_re_spk.php\',null,\'Laporan SPK\'],_cmSplit,
                        [\''._IMGCOSTUMERS.'\',\'Status SPK\',\'ajk_re_spk.php?er=sSPK\',null,\'Laporan SPK\'],_cmSplit,
                        [\''._IMGCOSTUMERS.'\',\'Historical Klaim SPK\',\'ajk_re_spk.php?er=hisSPK\',null,\'Laporan SPK\'],_cmSplit,
                        [\''._IMGCOSTUMERS.'\',\'Rekap Summary SPK\',\'ajkspk.php?er=rkpsumm\',null,\'Rekap Summary SPK\'],_cmSplit,
                        [\''._IMGCOSTUMERS.'\',\'Rekap User SPK\',\'ajkspk.php?er=rkpuser\',null,\'Rekap User SPK\'],_cmSplit,
    
                    ],_cmSplit,
                [\''._IMGCOSTUMERS.'\',\'Dokter\',\'ajk_re_dok.php\',null,\'Laporan Dokter\'],_cmSplit,
                [\''._IMGCOSTUMERS.'\',\'Summary Data\',null,null,\'Laporan Summary\',
                    [\''._IMGINVDN.'\',\'Statistik Rangking\',\'ajk_re_summary.php?c=ranking\',null,\'Statistik Rangking\'],_cmSplit,
                    [\''._IMGINVDN.'\',\'Lost Rasio\',\'ajk_re_summary.php?c=lostrasio\',null,\'Lost Rasio\'],_cmSplit,
                    [\''._IMGINVDN.'\',\'Klaim\',\'ajk_re_summary.php?c=klaim\',null,\'Klaim\'],_cmSplit,
                    [\''._IMGINVDN.'\',\'Produksi\',\'ajk_re_summary.php?c=produksi\',null,\'Produksi\'],_cmSplit,
                    [\''._IMGINVDN.'\',\'Tagihan Premi\',\'ajk_re_summary.php?c=tagprem\',null,\'Tagihan Premi\'],_cmSplit,
                    [\''._IMGINVDN.'\',\'Produksi Percabang\',\'ajk_re_summary.php?c=prodcab\',null,\'Produksi Percabang\'],_cmSplit,
                    [\''._IMGINVDN.'\',\'Pemeriksaan Kesehatan\',\'ajk_re_summary.php?c=pemkes\',null,\'Pemeriksaan Kesehatan\'],_cmSplit,
                ],_cmSplit,
                [\''._IMGCOSTUMERS.'\',\'Covering Letter\',\'ajk_re_dok.php?dok=cl\',null,\'Covering Letter\'],_cmSplit,
                <!--[\''._IMGCOSTUMERS.'\',\'Memorandum Creditnote\',\'ajk_re_claim.php?c=memocn\',null,\'Memorandum Creditnote\'],_cmSplit, DIPINDAHIN KE BAGIAN FRONTEND 191115-->
    
                [\''._IMGCOSTUMERS.'\',\'Laporan SPK (leveldokter)\',\'ajk_re_spk.php\',null,\'Laporan SPK (leveldokter)\'],_cmSplit,
                [\''._IMGCOSTUMERS.'\',\'Laporan Dokter (leveldokter)\',\'ajk_re_dok.php\',null,\'Laporan Dokter (leveldokter)\'],_cmSplit,
    
                [\''._IMGCOSTUMERS.'\',\'Risk Management Fund (RMF)\',\'ajk_re_bank.php?b=rmf\',null,\'Laporan Risk Management Fund (RMF)\'],_cmSplit,
            ],_cmSplit,
            [\'\',\'Data Kepesertaan (leveldokter)\',null,null,\'Data Kepesertaan (leveldokter)\',
                <!--[\''._IMGUPLDATA.'\',\'Kepesertaan SPK\',\'ajk_uploader_spak.php\',null,\'Kepesertaan SPK\'],_cmSplit,-->
                [\''._IMGUPLDATA.'\',\'Kepesertaan SPK\',\'ajk_uploader_spak.php?r=set_spak\',null,\'Kepesertaan SPK\'],_cmSplit,
                [\''._IMGUPLDATA.'\',\'Data SPK\',\'ajk_uploader_spak.php?r=spk_app\',null,\'Data SPK\'],_cmSplit,
                [\''._IMGVALIDATE.'\',\'Validasi Data Upload\',null,null,\'Validasi Data Upload\',_cmSplit,
                    [\''._IMGVALIDATEMOVE.'\',\'Peserta SPK\',\'ajk_uploader_spk.php?r=viewallspk\',null,\'Peserta SPK\'],_cmSplit,
                ],_cmSplit,
            ],_cmSplit,
    
    <!--	[\'\',\'Count of Job\',null,null,\'Count of Job\', -->
    <!--		[\''._IMGCOBDN.'\',\'Proses Debit Note (DN)\',\'coj.php?r=cojdn\',null,\'Proses Debit Note (DN)\'],_cmSplit, -->
    <!--		[\''._IMGCOBCN.'\',\'Proses Credit Note (CN)\',\'coj.php?r=cojcn\',null,\'Proses Credit Note (CN)\'],_cmSplit, -->
    <!--	],_cmSplit, -->
            [\'\',\'Cek Terbilang\',\'testmail.php?x=cekterbilang\',null,\'Cek Terbilang\'],_cmSplit,
            [\'\',\'Test Mail\',\'testmail.php\',null,\'Test Mail\'],_cmSplit,
            [\'\',\'Logout\',\'login.php?op=logout\',null,\'Logout\'],_cmSplit,
            ];
            cmDraw (\'myMenuID\', myMenu, \'hbr\', cmThemeOffice, \'ThemeOffice\');
            </script>';
        }
    
    */
    out('menus', $_temp);
}


function checkLogin()
{
    if (!isset($_SESSION['nm_user'])) {
        if (!eregi('login.php', $_SERVER['SCRIPT_NAME'])) {
            echo '<script language="Javascript">window.location="login.php"</script>';
            //header('Location: login.php');
        }
    } else {
        $q=mysql_fetch_array(mysql_query('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'" AND id_cost=""'));
        if (!$q['nm_user']) {
            session_destroy();
            header('Location: login.php');
        }
    }
    // return true;
}
function getLogin()
{
    if (isset($_SESSION['nm_user'])) {
        $database = new db();
        $q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'" AND id_cost=""'));
        out('user', '<b><center>' .$q['nm_lengkap'] . '</center></b>');

        $timeout = 1800; // Number of seconds until it times out.
        if (isset($_SESSION['timeout'])) { 		// Check if the timeout field exists.
        // See if the number of seconds since the last
        // visit is larger than the timeout period.
        $duration = time() - (int)$_SESSION['timeout'];
            if ($duration > $timeout) {
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
function valid_date($str)
{
    $stamp = strtotime($str);
    if (!is_numeric($stamp)) {
        return false;
    }
    $month = date('m', $stamp);
    $day   = date('d', $stamp);
    $year  = date('Y', $stamp);
    if (checkdate($month, $day, $year)) {
        return $year.'-'.$month.'-'.$day;
    }
    return false;
}

//TGL CLAAIM MENINGGAL//
class T10DateCalc
{
    public $_time; // akan menyimpan unix time dari tanggal yang anda masukkan
    public $_d; // akan menyimpan data Hari dari tanggal
    public $_m; // akan menyimpan data Bulan dari tanggal
    public $_y; // akan menyimpan data Tahun dari tanggal
    public $_h; // akan menyimpan data Jam dari tanggal
    public $_i; // akan menyimpan data Menit dari tanggal
    public $_s; // akan menyimpan data Detik dari tanggal
    // array di bawah untuk mendeskripsikan nama-nama bulan dalam bahasa indonesia
    public $_indoMonth = array('','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');

    /* Parameter pada konstruktor yang harus dimasukkan adalah sebuah tanggal
       dengan format 'Y-m-d' atau 'Y-m-d H:i:s'	*/
    public function T10DateCalc($dateString)
    {
        $this->_time = strtotime($dateString); // membuat unix tanggal dengan fungsi strtotime
        $this->_d = date('d', $this->_time); // ambil data Hari dari unix format
        $this->_m = date('m', $this->_time); // ambil data Bulan dari unix format
        $this->_y = date('Y', $this->_time); // ambil data Tahun dari unix format
        $this->_h = date('H', $this->_time); // ambil data Jam dari unix format
        $this->_i = date('i', $this->_time); // ambil data Menit dari unix format
        $this->_s = date('s', $this->_time); // ambil data Detik dari unix format
    }

    public function getIndonesianFormat()
    {
        // akan mengembalikan nilai 05 September 2008 apabila anda memasukan format tanggal 2008-09-05
        return $this->_d .' '. $this->_indoMonth[date('n', $this->_time)] .' '. $this->_y;
    }

    public function _getDate($intervalDay=0, $intervalMonth=0, $intervalYear=0)
    {
        // fungsi yang akan mengembalikan nilai tanggal berdasarkan selisih hari, bulan dan tahun yang diinginkan
        if ($this->_h == '00' && $this->_i == '00' && $this->_s == '00') {
            $formatDate = 'd/m/Y';
        } // apabila jam, menit dan detik tidak dimasukkan pada tanggal awal maka format tanggal yang dikembalikan adalah Y-m-d
        else {
            $formatDate = 'd/m/Y H:i:s';
        }
        return date($formatDate, mktime($this->_h, $this->_i, $this->_s, $this->_m+$intervalMonth, $this->_d+$intervalDay, $this->_y+$intervalYear));
    }

    public function nextDay($interval=1)
    {
        return $this->_getDate($interval);
    }	// mendapatkan tanggal hari selanjutnya dari tanggal awal yang anda masukkan
    public function previousDay($interval=1)
    {
        return $this->_getDate(-$interval);
    } // mendapatkan tanggal hari sebelumnya dari tanggal awal yang anda masukkan
    public function nextWeek()
    {
        return $this->_getDate(7);
    } // mendapatkan tanggal minggu selanjutnya dari tanggal awal yang anda masukkan
    public function previousWeek()
    {
        return $this->_getDate(-7);
    } // mendapatkan tanggal minggu sebelumnya dari tanggal awal yang anda masukkan
    public function nextMonth($interval=1)
    {
        return $this->_getDate(0, $interval);
    } // mendapatkan tanggal bulan selanjutnya dari tanggal awal yang anda masukkan
    public function previousMonth($interval=1)
    {
        return $this->_getDate(0, -$interval);
    } // mendapatkan tanggal bulan sebelumnya dari tanggal awal yang anda masukkan
    public function nextYear($interval=1)
    {
        return $this->_getDate(0, 0, $interval);
    } // mendapatkan tanggal tahun selanjutnya dari tanggal awal yang anda masukkan
    public function previousYear($interval=1)
    {
        return $this->_getDate(0, 0, -$interval);
    } // mendapatkan tanggal tahun sebelumnya dari tanggal awal yang anda masukkan

    public function compareDate($date2)
    {
        // mendapatkan jumlah selisih dari tanggal yang anda masukan pada kontruktor awal dan tanggal yang anda masukkan pada parameter method ini
        $t = strtotime($date2);
        $gregorian1 = gregoriantojd($this->_m, $this->_d, $this->_y); // dapatkan format julian day dari tanggal awal
        $gregorian2 = gregoriantojd(date('m', $t), date('d', $t), date('Y', $t)); // dapatkan format julian day dari tanggal akhir
        $diff = $gregorian2 - $gregorian1;

        return $diff; // kembalikan hasil selisih tanggal
    }
}
