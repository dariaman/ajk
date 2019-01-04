<?php
	session_start();
	include_once('ajkp1708.php');
	include_once('includes/metImage.php');
	include_once('../includes/functions.php');
	include_once('../includes/db.php');
	include_once('../includes/excel_reader2.php');
	include_once('../includes/smtp_classes/library.php'); // include the library file
	include_once('../includes/smtp_classes/class.phpmailer.php'); // include the class name
	include_once('../includes/smtp_classes/class.smtp.php'); // include the class smtp
	$database = new db();
	if ($_REQUEST['op'] == 'login') {
	    $database->doQuery('SELECT * FROM pengguna WHERE nm_user="' . $_REQUEST['username'] . '" AND id_cost="" and aktif = "Y"');
	  if (mysql_num_rows($database->dbQuery)) {
	        $r = mysql_fetch_array($database->dbQuery);
	    if (md5($_REQUEST['pass']) == $r['password']) {

	        session_register('usernama');
	        $_SESSION['nm_user'] = $_REQUEST['username'];
	        header('Location: index.php');
	        } else {
	            header('Location: login.php?msg=Password salah');
	        }
	    } else {
	        header('Location: login.php?msg=User tidak terdaftar');
	    }
	} elseif ($_REQUEST['op'] == 'logout') {
	    //session_destroy();
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
		<link rel="shortcut icon" href="../assets/img/logo.png">
		<!-- ================== BEGIN BASE CSS STYLE ================== -->
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
		<link href="../assets/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css" rel="stylesheet" />
		<link href="../assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
		<link href="../assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
		<link href="../assets/css/animate.min.css" rel="stylesheet" />
		<link href="../assets/css/style.min.css" rel="stylesheet" />
		<link href="../assets/css/style-responsive.min.css" rel="stylesheet" />
		<link href="../assets/css/theme/default.css" rel="stylesheet" id="theme" />
		<!-- ================== END BASE CSS STYLE ================== -->

		<!-- ================== BEGIN BASE JS ================== -->
		<script src="../assets/plugins/pace/pace.min.js"></script>
		<!-- ================== END BASE JS ================== -->
	</head>
	<body class="pace-top">
		<!-- begin #page-loader -->
		<div id="page-loader" class="fade in"><span class="spinner"></span></div>
		<!-- end #page-loader -->

		<div class="login-cover">
		    <div class="login-cover-image "><img src="../assets/img/login-bg/white.jpg" class="img-responsive" data-id="login-cover-image" alt="" /></div>
		    <div class="login-cover-bg"></div>
		</div>
		<!-- begin #page-container -->
		<div id="page-container" class="fade">
		    <!-- begin login -->
	        <div class="login login-v2" data-pageload-addclass="animated fadeIn">
	            <!-- begin brand -->
	            <div class="login-header">
                    <div class="brand text-center">
                        ADONAI PIALANG ASURANSI<br>ADMIN AJK
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
		<script src="../assets/plugins/jquery/jquery-1.9.1.min.js"></script>
		<script src="../assets/plugins/jquery/jquery-migrate-1.1.0.min.js"></script>
		<script src="../assets/plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
		<script src="../assets/plugins/bootstrap/js/bootstrap.min.js"></script>
		<script src="../assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
		<script src="../assets/plugins/jquery-cookie/jquery.cookie.js"></script>
		<!-- ================== END BASE JS ================== -->

		<!-- ================== BEGIN PAGE LEVEL JS ================== -->
		<script src="../assets/js/login-v2.demo.min.js"></script>
		<script src="../assets/js/apps.min.js"></script>
		<!-- ================== END PAGE LEVEL JS ================== -->

		<script>
			$(document).ready(function() {
				App.init();
				LoginV2.init();
			});
		</script>
	</body>
</html>
