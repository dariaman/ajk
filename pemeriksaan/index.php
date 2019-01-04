<?php 
	session_start();
	if(!isset($_SESSION["user"])){
		header('Location: login.php');
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>Adonai | Dashboard</title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
	
	<!-- ================== BEGIN BASE CSS STYLE ================== -->
	<link href="assets/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css" rel="stylesheet" />
	<link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
	<link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
	<link href="assets/css/animate.min.css" rel="stylesheet" />
	<link href="assets/css/style.min.css" rel="stylesheet" />

	<link href="assets/css/style-responsive.min.css" rel="stylesheet" />
	<link href="assets/css/theme/default.css" rel="stylesheet" id="theme" />
  <link href="assets/plugins/bootstrap-calendar/css/bootstrap_calendar.css" rel="stylesheet" />
  <link href="assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
  <link href="assets/plugins/morris/morris.css" rel="stylesheet" />
	<script src="assets/plugins/pace/pace.min.js"></script>
	<!-- ================== END BASE JS ================== -->
</head>
<body>
	<!-- begin #page-loader -->
	<div id="page-loader" class="fade in"><span class="spinner"></span></div>
	<!-- end #page-loader -->
	
	<!-- begin #page-container -->
	<div id="page-container" class="fade page-without-sidebar page-header-fixed">
		<!-- begin #header -->
		<div id="header" class="header navbar navbar-default navbar-fixed-top">
			<!-- begin container-fluid -->
			<div class="container-fluid">
				<!-- begin mobile sidebar expand / collapse button -->
				<!-- 				<button type="button" class="navbar-toggle" data-click="sidebar-toggled">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>-->
				<a href="index.php" class="navbar-brand"><span class="navbar-logo"></span> A D O N A I </a>
				<!-- begin header navigation right -->
				<ul class="nav navbar-nav navbar-right">
					<!-- <li class="dropdown">
						<a href="javascript:;" data-toggle="dropdown" class="dropdown-toggle f-s-14">
							<i class="fa fa-bell-o"></i>
							<span class="label">5</span>
						</a>
						<ul class="dropdown-menu media-list pull-right animated fadeInDown">							
	            <li class="dropdown-header">Notifications (5)</li>
	            <li class="media">
	                <a href="javascript:;">
	                    <div class="media-left"><i class="fa fa-bug media-object bg-red"></i></div>
	                    <div class="media-body">
	                        <h6 class="media-heading">Server Error Reports</h6>
	                        <div class="text-muted f-s-11">3 minutes ago</div>
	                    </div>
	                </a>
	            </li>
	            <li class="media">
	                <a href="javascript:;">
	                    <div class="media-left"><img src="assets/img/user-1.jpg" class="media-object" alt="" /></div>
	                    <div class="media-body">
	                        <h6 class="media-heading">John Smith</h6>
	                        <p>Quisque pulvinar tellus sit amet sem scelerisque tincidunt.</p>
	                        <div class="text-muted f-s-11">25 minutes ago</div>
	                    </div>
	                </a>
	            </li>
	            <li class="media">
	                <a href="javascript:;">
	                    <div class="media-left"><img src="assets/img/user-2.jpg" class="media-object" alt="" /></div>
	                    <div class="media-body">
	                        <h6 class="media-heading">Olivia</h6>
	                        <p>Quisque pulvinar tellus sit amet sem scelerisque tincidunt.</p>
	                        <div class="text-muted f-s-11">35 minutes ago</div>
	                    </div>
	                </a>
	            </li>
	            <li class="media">
	                <a href="javascript:;">
	                    <div class="media-left"><i class="fa fa-plus media-object bg-green"></i></div>
	                    <div class="media-body">
	                        <h6 class="media-heading"> New User Registered</h6>
	                        <div class="text-muted f-s-11">1 hour ago</div>
	                    </div>
	                </a>
	            </li>
	            <li class="media">
	                <a href="javascript:;">
	                    <div class="media-left"><i class="fa fa-envelope media-object bg-blue"></i></div>
	                    <div class="media-body">
	                        <h6 class="media-heading"> New Email From John</h6>
	                        <div class="text-muted f-s-11">2 hour ago</div>
	                    </div>
	                </a>
	            </li>
	            <li class="dropdown-footer text-center">
	                <a href="javascript:;">View more</a>
	            </li>	          
						</ul>
					</li> -->
					<li class="dropdown navbar-user">
						<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
							<img src="assets/img/user-13.jpg" alt="" /> 
							<span class="hidden-xs"><?php echo $_SESSION['namalengkap']; ?></span> <b class="caret"></b>
						</a>
						<ul class="dropdown-menu animated fadeInLeft">
							<li class="arrow"></li>
							<!-- <li><a href="javascript:;">Edit Profile</a></li>
							<li><a href="javascript:;"><span class="badge badge-danger pull-right">2</span> Inbox</a></li>
							<li><a href="javascript:;">Calendar</a></li>
							<li><a href="javascript:;">Setting</a></li>
							<li class="divider"></l i>-->
							<li><a href="javascript:;" onclick="logout();">Log Out</a></li>
						</ul>
					</li>
				</ul>
				<!-- end header navigation right -->
			</div>
			<!-- end container-fluid -->
		</div>
		<!-- end #header -->

		<div id="badan"></div>
		
		<!-- begin scroll to top btn -->
		<a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
		<!-- end scroll to top btn -->
	</div>
	<!-- end page container -->
	
	<!-- ================== BEGIN BASE JS ================== -->
	<script src="assets/plugins/jquery/jquery-1.9.1.min.js"></script>
	<script src="assets/plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
	<script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
	<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
	<script src="assets/plugins/jquery-cookie/jquery.cookie.js"></script>
  <script src="assets/plugins/morris/raphael.min.js"></script>
  <script src="assets/plugins/bootstrap-calendar/js/bootstrap_calendar.min.js"></script>
	<script src="assets/plugins/gritter/js/jquery.gritter.js"></script>
	<script src="assets/plugins/masked-input/masked-input.min.js"></script>
	<script src="assets/js/apps.min.js"></script>
	<script src="assets/han.js?v=<?php echo date('YmdHis'); ?>"></script>
	<!-- ================== END PAGE LEVEL JS ================== -->
	
	<script>
		$(document).ready(function() {
			App.init();			
			console.log("<?php echo $_SESSION['halaman']; ?>");
			viewaja("<?php echo $_SESSION['halaman']; ?>","badan")
		});

	</script>

</body>

</html>

