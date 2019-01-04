<?php
	session_start();
	include_once('includes/ajk6106.php');
	include_once('includes/functions.php');
	include_once('includes/db.php');
	$database = new db();

	if ($_REQUEST['op'] == 'login') {
		$r = mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="' . mysql_real_escape_string($_REQUEST['username']) . '" AND id_cost!="" AND aktif="Y" AND del IS NULL '));
		if (!$r['nm_user']) {
			$mobdatabase = mysql_fetch_array($database->doQuery('SELECT * FROM user_mobile WHERE nama="' . mysql_real_escape_string($_REQUEST['username']) . '" AND idbank!="" AND status="Aktif" AND supervisor=0 AND del IS NULL'));
			//echo $mobdatabase['nama'];
			if (md5($_REQUEST['pass']) == $mobdatabase['passw']) {
				session_register('usernama');
				$_SESSION['nm_user'] = $_REQUEST['username'];
				header('Location: imob.php');
			}else{
				header('Location: login.php?msg=user mobile tidak terdaftar');
			}
		}else{
			if (md5($_REQUEST['pass']) == $r['password']) {
				session_register('usernama');
				$_SESSION['nm_user'] = $_REQUEST['username'];
				$userlog_in = $database->doQuery('UPDATE pengguna SET log="Y" WHERE nm_user="'.$_REQUEST['username'].'" AND id_polis !=""');
				header('Location: index.php');
			}else {
				header('Location: login.php?msg=Kesalahan pada username atau password');
			}
		}
	}elseif ($_REQUEST['op'] == 'logout') {
		if (session_is_registered('nm_user')) {
			$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'" AND del IS NULL'));
		}

		$userlog_out = $database->doQuery('UPDATE pengguna SET log="T" WHERE nm_user="'.$q['nm_user'].'"');

		if ($q['supervisor']=="1") {
			$met = $database->doQuery('UPDATE pengguna SET id_cost="",id_polis="", level="", wilayah="" WHERE id="'.$q['id'].'"');
		}else{
		}

		$loggerout = $database->doQuery('UPDATE ajk_logger SET lastdate_logout="'.$datelog.'", lasttime_logout="'.$timelog.'" WHERE id_user="'.$q['id'].'" AND lastdate_logout IS NULL AND lasttime_logout IS NULL');
		session_destroy();

		header('Location: login.php');
	}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>Adonai | Pialang Asuransi</title>
		<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
		<meta content="" name="description" />
		<meta content="" name="author" />
		<link rel="shortcut icon" href="assets/img/logo.png">
		<!-- ================== BEGIN BASE CSS STYLE ================== -->
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
		<link href="assets/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css" rel="stylesheet" />
		<link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
		<link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
		<link href="assets/css/animate.min.css" rel="stylesheet" />
		<link href="assets/css/style.min.css" rel="stylesheet" />
		<link href="assets/css/style-responsive.min.css" rel="stylesheet" />
		<link href="assets/css/theme/default.css" rel="stylesheet" id="theme" />
		<!-- ================== END BASE CSS STYLE ================== -->

		<!-- ================== BEGIN BASE JS ================== -->
		<script src="assets/plugins/pace/pace.min.js"></script>
		<!-- ================== END BASE JS ================== -->
	</head>
	<body class="pace-top">
		<!-- begin #page-loader -->
		<div id="page-loader" class="fade in"><span class="spinner"></span></div>
		<!-- end #page-loader -->

		<div class="login-cover">
		    <div class="login-cover-image"><img src="assets/img/login-bg/white.jpg" data-id="login-cover-image" alt="" class="img-responsive"/></div>
		    <div class="login-cover-bg"></div>
		</div>
		<!-- begin #page-container -->
		<div id="page-container" class="fade">
		    <!-- begin login -->
	        <div class="login login-v2" data-pageload-addclass="animated fadeIn">
	            <!-- begin brand -->
	            <div class="login-header">
                    <div class="brand text-center">
                        Aplikasi Asuransi <br>Jiwa Kredit dan Pensiunan
                    </div>
	 						</div>
	            <!-- end brand -->
	            <div class="login-content">
	                <form action="" method="POST" class="margin-bottom-0">
	                		<?php
	                			if($_REQUEST['msg']!=""){
													echo '<div class="text-center" style="color:#ff4747;font-size:20px">'.$_REQUEST['msg'].'</div>';
	                			}else{}
	                		?>
	                    <div class="form-group m-b-20">
	                        <input type="text" class="form-control input-lg" id="username" name="username" placeholder="Username" required="" />
	                    </div>
	                    <div class="form-group m-b-20">
	                        <input type="password" class="form-control input-lg" id="pass" name="pass" placeholder="Password" required="" />
	                    </div>
	                    <div class="login-buttons">
	                        <!-- <button type="submit" class="btn btn-warning btn-block btn-lg">Login</button> -->
													<input type="submit" value="Login" class="btn btn-warning btn-block btn-lg">
													<input type="hidden" name="op" value="login">
	                    </div>
	                </form>
	            </div>
	        </div>
	        <!-- end login -->
		</div>
		<!-- end page container -->

		<!-- ================== BEGIN BASE JS ================== -->
		<script src="assets/plugins/jquery/jquery-1.9.1.min.js"></script>
		<script src="assets/plugins/jquery/jquery-migrate-1.1.0.min.js"></script>
		<script src="assets/plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
		<script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
		<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
		<script src="assets/plugins/jquery-cookie/jquery.cookie.js"></script>
		<!-- ================== END BASE JS ================== -->

		<!-- ================== BEGIN PAGE LEVEL JS ================== -->
		<script src="assets/js/login-v2.demo.min.js"></script>
		<script src="assets/js/apps.min.js"></script>
		<!-- ================== END PAGE LEVEL JS ================== -->

		<script>
			$(document).ready(function() {
				App.init();
				LoginV2.init();
			});
		</script>
	</body>
</html>
