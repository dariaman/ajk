<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>Adonai | Login Page</title>
		<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
		
		<!-- ================== BEGIN BASE CSS STYLE ================== -->
		<link href="assets/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css" rel="stylesheet" />
		<link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
		<link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
		<link href="assets/css/animate.min.css" rel="stylesheet" />
		<link href="assets/css/style.min.css" rel="stylesheet" />
		<link href="assets/css/style-responsive.min.css" rel="stylesheet" />
		<link href="assets/css/theme/default.css" rel="stylesheet" id="theme" />
		<link href="assets/plugins/sweetalert/sweetalert.css" rel="stylesheet" />
		<!-- ================== END BASE CSS STYLE ================== -->

	</head>
	<body class="pace-top">
		<!-- begin #page-loader -->
		<div id="page-loader" class="fade in"><span class="spinner"></span></div>
		<!-- end #page-loader -->

		<div class="login-cover" id="logincvr">
	    <div class="login-cover-bg"></div>
		</div>
		
		<div id="page-container" class="fade">
			<!-- begin login -->
	    <div class="login login-v2" data-pageload-addclass="animated fadeIn">
	      <!-- begin brand -->
	      <div class="login-header">
	        <div class="brand text-center">
	          A D O N A I
	          <small>Pialang Asuransi</small>
	        </div>          
	      </div>
	      <!-- end brand -->
	      <div class="login-content">
	        <form action="javascript:login();" id="frmlogin" method="POST" class="margin-bottom-0">
	          <div class="form-group m-b-20">
	            <input type="text" class="form-control input-lg" name="nip" placeholder="NIP" required>
	          </div>
	          <div class="form-group m-b-20">
	            <input type="password" class="form-control input-lg" name="pass" placeholder="Password" required>
	          </div>
	          <div class="login-buttons">
	          	<button type="submit" id="load" class="btn btn-success btn-block btn-lg" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Loading..">Masuk</button>
	          </div>
	        </form>
	      </div>
	    </div>
	    <!-- end login -->
		</div>

		<!-- ================== BEGIN JAVASCRIPT ================== -->
		<script src="assets/plugins/jquery/jquery-1.9.1.min.js"></script>
		<script src="assets/plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
		<script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
		<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
		<script src="assets/plugins/jquery-cookie/jquery.cookie.js"></script>
		<script src="assets/plugins/sweetalert/sweetalert.min.js"></script>		
		<script src="assets/js/apps.min.js"></script>
		<script src="assets/han.js"></script>
		<!-- ================== END JAVASCRIPT ================== -->

		<script>
			$(document).ready(function() {
				App.init();
			});			
		</script>
	</body>
</html>

