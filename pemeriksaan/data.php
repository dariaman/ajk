<?php 
include "jembatan.php";

session_start();

switch ($_POST['han']) {
	case 'login':
		$nip = $_POST['nip'];
		$password = $_POST['pass'];	
		
		$query = "SELECT *
							FROM user_mobile
							WHERE nip_primary = '".$nip."' and 
										passw = md5('".$password."')";
		
		$result = mysqli_fetch_array(mysqli_query($con,$query));
		
		if($result['nip_primary'] != ""){			
			$_SESSION["user"] = $nip;
			$_SESSION["namalengkap"] = $result['namalengkap'];
			$_SESSION["type"] = $result['type'];
			$_SESSION["email"] = $result['email'];
			$_SESSION["cabang"] = $result['cabang'];
			$_SESSION["halaman"] = 'vvalidasispk';
			echo "success";
		}else{
			echo "Nip atau Password Salah";
		}
	break;

	case 'vdashboard':
		echo '	<!-- begin #header -->
						<div id="header" class="header navbar navbar-default navbar-fixed-top">
							<!-- begin container-fluid -->
							<div class="container-fluid">
								<!-- begin mobile sidebar expand / collapse button -->
								<div class="navbar-header">
									<a href="index.html" class="navbar-brand"><span class="navbar-logo"></span> Color Admin</a>
									<button type="button" class="navbar-toggle" data-click="sidebar-toggled">
										<span class="icon-bar"></span>
										<span class="icon-bar"></span>
										<span class="icon-bar"></span>
									</button>
								</div>
								<!-- end mobile sidebar expand / collapse button -->
								
								<!-- begin header navigation right -->
								<ul class="nav navbar-nav navbar-right">
									<li>
										<form class="navbar-form full-width">
											<div class="form-group">
												<input type="text" class="form-control" placeholder="Enter keyword" />
												<button type="submit" class="btn btn-search"><i class="fa fa-search"></i></button>
											</div>
										</form>
									</li>
									<li class="dropdown">
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
									</li>
									<li class="dropdown navbar-user">
										<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
											<img src="assets/img/user-13.jpg" alt="" /> 
											<span class="hidden-xs">Adam Schwartz</span> <b class="caret"></b>
										</a>
										<ul class="dropdown-menu animated fadeInLeft">
											<li class="arrow"></li>
											<li><a href="javascript:;">Edit Profile</a></li>
											<li><a href="javascript:;"><span class="badge badge-danger pull-right">2</span> Inbox</a></li>
											<li><a href="javascript:;">Calendar</a></li>
											<li><a href="javascript:;">Setting</a></li>
											<li class="divider"></li>
											<li><a href="javascript:;">Log Out</a></li>
										</ul>
									</li>
								</ul>
								<!-- end header navigation right -->
							</div>
							<!-- end container-fluid -->
						</div>
						<!-- end #header -->
						
						<!-- begin #sidebar -->
						<div id="sidebar" class="sidebar">
							<!-- begin sidebar scrollbar -->
							<div data-scrollbar="true" data-height="100%">
								<!-- begin sidebar user -->
								<ul class="nav">
									<li class="nav-profile">
										<div class="image">
											<a href="javascript:;"><img src="assets/img/user-13.jpg" alt="" /></a>
										</div>
										<div class="info">
											Sean Ngu
											<small>Front end developer</small>
										</div>
									</li>
								</ul>
								<!-- end sidebar user -->
								<!-- begin sidebar nav -->
								<ul class="nav">
									<li class="nav-header">Navigation</li>
									<li class="has-sub active">
										<a href="javascript:;">
										    <b class="caret pull-right"></b>
										    <i class="fa fa-laptop"></i>
										    <span>Dashboard</span>
									    </a>
										<ul class="sub-menu">
										    <li class="active"><a href="index.html">Dashboard v1</a></li>
										    <li><a href="index_v2.html">Dashboard v2</a></li>
										</ul>
									</li>
									<li class="has-sub">
										<a href="javascript:;">
											<span class="badge pull-right">10</span>
											<i class="fa fa-inbox"></i> 
											<span>Email</span>
										</a>
										<ul class="sub-menu">
										    <li><a href="email_inbox.html">Inbox v1</a></li>
										    <li><a href="email_inbox_v2.html">Inbox v2</a></li>
										    <li><a href="email_compose.html">Compose</a></li>
										    <li><a href="email_detail.html">Detail</a></li>
										</ul>
									</li>
									<li class="has-sub">
										<a href="javascript:;">
										    <b class="caret pull-right"></b>
										    <i class="fa fa-suitcase"></i>
										    <span>UI Elements</span> 
										</a>
										<ul class="sub-menu">
											<li><a href="ui_general.html">General</a></li>
											<li><a href="ui_typography.html">Typography</a></li>
											<li><a href="ui_tabs_accordions.html">Tabs & Accordions</a></li>
											<li><a href="ui_unlimited_tabs.html">Unlimited Nav Tabs</a></li>
											<li><a href="ui_modal_notification.html">Modal & Notification</a></li>
											<li><a href="ui_widget_boxes.html">Widget Boxes</a></li>
											<li><a href="ui_media_object.html">Media Object</a></li>
											<li><a href="ui_buttons.html">Buttons</a></li>
											<li><a href="ui_icons.html">Icons</a></li>
											<li><a href="ui_simple_line_icons.html">Simple Line Icons</a></li>
											<li><a href="ui_ionicons.html">Ionicons</a></li>
											<li><a href="ui_tree.html">Tree View</a></li>
											<li><a href="ui_language_bar_icon.html">Language Bar & Icon</a></li>
										</ul>
									</li>
									<li class="has-sub">
										<a href="javascript:;">
										    <b class="caret pull-right"></b>
										    <i class="fa fa-file-o"></i>
										    <span>Form Stuff</span> 
										</a>
										<ul class="sub-menu">
											<li><a href="form_elements.html">Form Elements</a></li>
											<li><a href="form_plugins.html">Form Plugins</a></li>
											<li><a href="form_slider_switcher.html">Form Slider + Switcher</a></li>
											<li><a href="form_validation.html">Form Validation</a></li>
											<li><a href="form_wizards.html">Wizards</a></li>
											<li><a href="form_wizards_validation.html">Wizards + Validation</a></li>
											<li><a href="form_wysiwyg.html">WYSIWYG</a></li>
											<li><a href="form_editable.html">X-Editable</a></li>
											<li><a href="form_multiple_upload.html">Multiple File Upload</a></li>
										</ul>
									</li>
									<li class="has-sub">
										<a href="javascript:;">
										    <b class="caret pull-right"></b>
										    <i class="fa fa-th"></i>
										    <span>Tables  <span class="label label-theme m-l-5">NEW</span></span>
										</a>
										<ul class="sub-menu">
											<li><a href="table_basic.html">Basic Tables</a></li>
											<li class="has-sub">
											    <a href="javascript:;"><b class="caret pull-right"></b> Managed Tables</a>
											    <ul class="sub-menu">
											        <li><a href="table_manage.html">Default</a></li>
											        <li><a href="table_manage_autofill.html">Autofill</a></li>
											        <li><a href="table_manage_buttons.html">Buttons</a></li>
											        <li><a href="table_manage_colreorder.html">ColReorder</a></li>
											        <li><a href="table_manage_fixed_columns.html">Fixed Column</a></li>
											        <li><a href="table_manage_fixed_header.html">Fixed Header</a></li>
											        <li><a href="table_manage_keytable.html">KeyTable</a></li>
											        <li><a href="table_manage_responsive.html">Responsive</a></li>
											        <li><a href="table_manage_rowreorder.html">RowReorder <i class="fa fa-paper-plane text-theme m-l-5"></i></a></li>
											        <li><a href="table_manage_scroller.html">Scroller</a></li>
											        <li><a href="table_manage_select.html">Select</a></li>
											        <li><a href="table_manage_combine.html">Extension Combination</a></li>
											    </ul>
											</li>
										</ul>
									</li>
									<li class="has-sub">
										<a href="javascript:;">
										    <b class="caret pull-right"></b>
											<i class="fa fa-star"></i> 
											<span>Front End</span>
										</a>
										<ul class="sub-menu">
										    <li><a href="http://seantheme.com/color-admin-v1.9/frontend/one-page-parallax/index.html" target="_blank">One Page Parallax</a></li>
										    <li><a href="http://seantheme.com/color-admin-v1.9/frontend/blog/index.html" target="_blank">Blog</a></li>
										    <li><a href="http://seantheme.com/color-admin-v1.9/frontend/forum/index.html" target="_blank">Forum</a></li>
										</ul>
									</li>
									<li class="has-sub">
									    <a href="javascript:;">
										    <b class="caret pull-right"></b>
									        <i class="fa fa-envelope"></i>
									        <span>Email Template</span>
									    </a>
										<ul class="sub-menu">
											<li><a href="email_system.html">System Template</a></li>
											<li><a href="email_newsletter.html">Newsletter Template</a></li>
										</ul>
									</li>
									<li class="has-sub">
									    <a href="javascript:;">
										    <b class="caret pull-right"></b>
									        <i class="fa fa-area-chart"></i>
										    <span>Chart <span class="label label-theme m-l-5">NEW</span></span>
										</a>
										<ul class="sub-menu">
										    <li><a href="chart-flot.html">Flot Chart</a></li>
										    <li><a href="chart-morris.html">Morris Chart</a></li>
											<li><a href="chart-js.html">Chart JS</a></li>
										    <li><a href="chart-d3.html">d3 Chart <i class="fa fa-paper-plane text-theme m-l-5"></i></a></li>
										</ul>
									</li>
									<li><a href="calendar.html"><i class="fa fa-calendar"></i> <span>Calendar</span></a></li>
									<li class="has-sub">
									    <a href="javascript:;">
									        <b class="caret pull-right"></b>
									        <i class="fa fa-map-marker"></i>
									        <span>Map</span>
									    </a>
										<ul class="sub-menu">
											<li><a href="map_vector.html">Vector Map</a></li>
											<li><a href="map_google.html">Google Map</a></li>
										</ul>
									</li>
									<li class="has-sub">
									    <a href="javascript:;">
										    <b class="caret pull-right"></b>
										    <i class="fa fa-camera"></i>
										    <span>Gallery</span>
										</a>
									    <ul class="sub-menu">
									        <li><a href="gallery.html">Gallery v1</a></li>
									        <li><a href="gallery_v2.html">Gallery v2</a></li>
									    </ul>
									</li>
									<li class="has-sub">
										<a href="javascript:;">
										    <b class="caret pull-right"></b>
										    <i class="fa fa-cogs"></i>
										    <span>Page Options <span class="label label-theme m-l-5">NEW</span></span>
										</a>
										<ul class="sub-menu">
											<li><a href="page_blank.html">Blank Page</a></li>
											<li><a href="page_with_footer.html">Page with Footer</a></li>
											<li><a href="page_without_sidebar.html">Page without Sidebar</a></li>
											<li><a href="page_with_right_sidebar.html">Page with Right Sidebar</a></li>
											<li><a href="page_with_minified_sidebar.html">Page with Minified Sidebar</a></li>
											<li><a href="page_with_two_sidebar.html">Page with Two Sidebar</a></li>
											<li><a href="page_with_line_icons.html">Page with Line Icons</a></li>
											<li><a href="page_with_ionicons.html">Page with Ionicons</a></li>
											<li><a href="page_full_height.html">Full Height Content</a></li>
											<li><a href="page_with_wide_sidebar.html">Page with Wide Sidebar</a></li>
											<li><a href="page_with_light_sidebar.html">Page with Light Sidebar</a></li>
											<li><a href="page_with_mega_menu.html">Page with Mega Menu</a></li>
				                            <li><a href="page_with_top_menu.html">Page with Top Menu <i class="fa fa-paper-plane text-theme m-l-5"></i></a></li>
				                            <li><a href="page_with_boxed_layout.html">Page with Boxed Layout <i class="fa fa-paper-plane text-theme m-l-5"></i></a></li>
				                            <li><a href="page_with_mixed_menu.html">Page with Mixed Menu <i class="fa fa-paper-plane text-theme m-l-5"></i></a></li>
				                            <li><a href="page_boxed_layout_with_mixed_menu.html">Boxed Layout with Mixed Menu <i class="fa fa-paper-plane text-theme m-l-5"></i></a></li>
				                            <li><a href="page_with_transparent_sidebar.html">Page with Transparent Sidebar <i class="fa fa-paper-plane text-theme m-l-5"></i></a></li>
										</ul>
									</li>
									<li class="has-sub">
										<a href="javascript:;">
										    <b class="caret pull-right"></b>
										    <i class="fa fa-gift"></i>
										    <span>Extra</span>
										</a>
										<ul class="sub-menu">
										    <li><a href="extra_timeline.html">Timeline</a></li>
										    <li><a href="extra_coming_soon.html">Coming Soon Page</a></li>
											<li><a href="extra_search_results.html">Search Results</a></li>
											<li><a href="extra_invoice.html">Invoice</a></li>
											<li><a href="extra_404_error.html">404 Error Page</a></li>
											<li><a href="extra_profile.html">Profile Page</a></li>
										</ul>
									</li>
									<li class="has-sub">
									    <a href="javascript:;">
									        <b class="caret pull-right"></b>
									        <i class="fa fa-key"></i>
									        <span>Login & Register</span>
									    </a>
									    <ul class="sub-menu">
											<li><a href="login.html">Login</a></li>
									        <li><a href="login_v2.html">Login v2</a></li>
									        <li><a href="login_v3.html">Login v3</a></li>
									        <li><a href="register_v3.html">Register v3</a></li>
									    </ul>
									</li>
									<li class="has-sub">
									    <a href="javascript:;">
									        <b class="caret pull-right"></b>
									        <i class="fa fa-cubes"></i>
									        <span>Version <span class="label label-theme m-l-5">NEW</span></span>
									    </a>
									    <ul class="sub-menu">
											<li><a href="javascript:;">HTML</a></li>
									        <li><a href="http://seantheme.com/color-admin-v1.9/admin/ajax/index.html">AJAX</a></li>
									        <li><a href="http://seantheme.com/color-admin-v1.9/admin/angularjs/index.html">ANGULAR JS<i class="fa fa-paper-plane text-theme m-l-5"></i></a></li>
									    </ul>
									</li>
									<li class="has-sub">
									    <a href="javascript:;">
									        <b class="caret pull-right"></b>
									        <i class="fa fa-medkit"></i>
									        <span>Helper</span>
									    </a>
									    <ul class="sub-menu">
											<li><a href="helper_css.html">Predefined CSS Classes</a></li>
									    </ul>
									</li>
									<li class="has-sub">
										<a href="javascript:;">
										    <b class="caret pull-right"></b>
										    <i class="fa fa-align-left"></i> 
										    <span>Menu Level</span>
										</a>
										<ul class="sub-menu">
											<li class="has-sub">
												<a href="javascript:;">
										            <b class="caret pull-right"></b>
										            Menu 1.1
										        </a>
												<ul class="sub-menu">
													<li class="has-sub">
														<a href="javascript:;">
														    <b class="caret pull-right"></b>
														    Menu 2.1
														</a>
														<ul class="sub-menu">
															<li><a href="javascript:;">Menu 3.1</a></li>
															<li><a href="javascript:;">Menu 3.2</a></li>
														</ul>
													</li>
													<li><a href="javascript:;">Menu 2.2</a></li>
													<li><a href="javascript:;">Menu 2.3</a></li>
												</ul>
											</li>
											<li><a href="javascript:;">Menu 1.2</a></li>
											<li><a href="javascript:;">Menu 1.3</a></li>
										</ul>
									</li>
							        <!-- begin sidebar minify button -->
									<li><a href="javascript:;" class="sidebar-minify-btn" data-click="sidebar-minify"><i class="fa fa-angle-double-left"></i></a></li>
							        <!-- end sidebar minify button -->
								</ul>
								<!-- end sidebar nav -->
							</div>
							<!-- end sidebar scrollbar -->
						</div>
						<div class="sidebar-bg"></div>
						<!-- end #sidebar -->
						
						<!-- begin #content -->
						<div id="content" class="content">
							<!-- begin breadcrumb -->
							<ol class="breadcrumb pull-right">
								<li><a href="javascript:;">Home</a></li>
								<li class="active">Dashboard</li>
							</ol>
							<!-- end breadcrumb -->
							<!-- begin page-header -->
							<h1 class="page-header">Dashboard <small>header small text goes here...</small></h1>
							<!-- end page-header -->
							
							<!-- begin row -->
							<div class="row">
								<!-- begin col-3 -->
								<div class="col-md-3 col-sm-6">
									<div class="widget widget-stats bg-green">
										<div class="stats-icon"><i class="fa fa-desktop"></i></div>
										<div class="stats-info">
											<h4>TOTAL VISITORS</h4>
											<p>3,291,922</p>	
										</div>
										<div class="stats-link">
											<a href="javascript:;">View Detail <i class="fa fa-arrow-circle-o-right"></i></a>
										</div>
									</div>
								</div>
								<!-- end col-3 -->
								<!-- begin col-3 -->
								<div class="col-md-3 col-sm-6">
									<div class="widget widget-stats bg-blue">
										<div class="stats-icon"><i class="fa fa-chain-broken"></i></div>
										<div class="stats-info">
											<h4>BOUNCE RATE</h4>
											<p>20.44%</p>	
										</div>
										<div class="stats-link">
											<a href="javascript:;">View Detail <i class="fa fa-arrow-circle-o-right"></i></a>
										</div>
									</div>
								</div>
								<!-- end col-3 -->
								<!-- begin col-3 -->
								<div class="col-md-3 col-sm-6">
									<div class="widget widget-stats bg-purple">
										<div class="stats-icon"><i class="fa fa-users"></i></div>
										<div class="stats-info">
											<h4>UNIQUE VISITORS</h4>
											<p>1,291,922</p>	
										</div>
										<div class="stats-link">
											<a href="javascript:;">View Detail <i class="fa fa-arrow-circle-o-right"></i></a>
										</div>
									</div>
								</div>
								<!-- end col-3 -->
								<!-- begin col-3 -->
								<div class="col-md-3 col-sm-6">
									<div class="widget widget-stats bg-red">
										<div class="stats-icon"><i class="fa fa-clock-o"></i></div>
										<div class="stats-info">
											<h4>AVG TIME ON SITE</h4>
											<p>00:12:23</p>	
										</div>
										<div class="stats-link">
											<a href="javascript:;">View Detail <i class="fa fa-arrow-circle-o-right"></i></a>
										</div>
									</div>
								</div>
								<!-- end col-3 -->
							</div>
							<!-- end row -->
							<!-- begin row -->
							<div class="row">
								<!-- begin col-8 -->
								<div class="col-md-8">
									<div class="panel panel-inverse" data-sortable-id="index-1">
										<div class="panel-heading">
											<div class="panel-heading-btn">
												<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
												<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
												<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
												<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
											</div>
											<h4 class="panel-title">Website Analytics (Last 7 Days)</h4>
										</div>
										<div class="panel-body">
											<div id="interactive-chart" class="height-sm"></div>
										</div>
									</div>
									
									<ul class="nav nav-tabs nav-tabs-inverse nav-justified nav-justified-mobile" data-sortable-id="index-2">
										<li class="active"><a href="#latest-post" data-toggle="tab"><i class="fa fa-picture-o m-r-5"></i> <span class="hidden-xs">Latest Post</span></a></li>
										<li class=""><a href="#purchase" data-toggle="tab"><i class="fa fa-shopping-cart m-r-5"></i> <span class="hidden-xs">Purchase</span></a></li>
										<li class=""><a href="#email" data-toggle="tab"><i class="fa fa-envelope m-r-5"></i> <span class="hidden-xs">Email</span></a></li>
									</ul>
									<div class="tab-content" data-sortable-id="index-3">
										<div class="tab-pane fade active in" id="latest-post">
											<div class="height-sm" data-scrollbar="true">
												<ul class="media-list media-list-with-divider">
													<li class="media media-lg">
														<a href="javascript:;" class="pull-left">
															<img class="media-object" src="assets/img/gallery/gallery-1.jpg" alt="" />
														</a>
														<div class="media-body">
															<h4 class="media-heading">Aenean viverra arcu nec pellentesque ultrices. In erat purus, adipiscing nec lacinia at, ornare ac eros.</h4>
															Nullam at risus metus. Quisque nisl purus, pulvinar ut mauris vel, elementum suscipit eros. Praesent ornare ante massa, egestas pellentesque orci convallis ut. Curabitur consequat convallis est, id luctus mauris lacinia vel. Nullam tristique lobortis mauris, ultricies fermentum lacus bibendum id. Proin non ante tortor. Suspendisse pulvinar ornare tellus nec pulvinar. Nam pellentesque accumsan mi, non pellentesque sem convallis sed. Quisque rutrum erat id auctor gravida.
														</div>
													</li>
													<li class="media media-lg">
														<a href="javascript:;" class="pull-left">
															<img class="media-object" src="assets/img/gallery/gallery-10.jpg" alt="" />
														</a>
														<div class="media-body">
															<h4 class="media-heading">Vestibulum vitae diam nec odio dapibus placerat. Ut ut lorem justo.</h4>
															Fusce bibendum augue nec fermentum tempus. Sed laoreet dictum tempus. Aenean ac sem quis nulla malesuada volutpat. Nunc vitae urna pulvinar velit commodo cursus. Nullam eu felis quis diam adipiscing hendrerit vel ac turpis. Nam mattis fringilla euismod. Donec eu ipsum sit amet mauris iaculis aliquet. Quisque sit amet feugiat odio. Cras convallis lorem at libero lobortis, placerat lobortis sapien lacinia. Duis sit amet elit bibendum sapien dignissim bibendum.
														</div>
													</li>
													<li class="media media-lg">
														<a href="javascript:;" class="pull-left">
															<img class="media-object" src="assets/img/gallery/gallery-7.jpg" alt="" />
														</a>
														<div class="media-body">
															<h4 class="media-heading">Maecenas eget turpis luctus, scelerisque arcu id, iaculis urna. Interdum et malesuada fames ac ante ipsum primis in faucibus.</h4>
															Morbi placerat est nec pharetra placerat. Ut laoreet nunc accumsan orci aliquam accumsan. Maecenas volutpat dolor vitae sapien ultricies fringilla. Suspendisse vitae orci sed nibh ultrices tristique. Aenean in ante eget urna semper imperdiet. Pellentesque sagittis a nulla at scelerisque. Nam augue nulla, accumsan quis nisi a, facilisis eleifend nulla. Praesent aliquet odio non imperdiet fringilla. Morbi a porta nunc. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae.
														</div>
													</li>
													<li class="media media-lg">
														<a href="javascript:;" class="pull-left">
															<img class="media-object" src="assets/img/gallery/gallery-8.jpg" alt="" />
														</a>
														<div class="media-body">
															<h4 class="media-heading">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec auctor accumsan rutrum.</h4>
															Fusce augue diam, vestibulum a mattis sit amet, vehicula eu ipsum. Vestibulum eu mi nec purus tempor consequat. Vestibulum porta non mi quis cursus. Fusce vulputate cursus magna, tincidunt sodales ipsum lobortis tincidunt. Mauris quis lorem ligula. Morbi placerat est nec pharetra placerat. Ut laoreet nunc accumsan orci aliquam accumsan. Maecenas volutpat dolor vitae sapien ultricies fringilla. Suspendisse vitae orci sed nibh ultrices tristique. Aenean in ante eget urna semper imperdiet. Pellentesque sagittis a nulla at scelerisque.
														</div>
													</li>
												</ul>
											</div>
										</div>
										<div class="tab-pane fade" id="purchase">
											<div class="height-sm" data-scrollbar="true">
												<table class="table">
													<thead>
														<tr>
															<th>Date</th>
															<th class="hidden-sm">Product</th>
															<th>Amount</th>
															<th>User</th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td>13/02/2013</td>
															<td class="hidden-sm">
																<a href="javascript:;">
																	<img src="assets/img/product/product-1.png" alt=""  />
																</a>
															</td>
															<td>
																<h6><a href="javascript:;">Nunc eleifend lorem eu velit eleifend, eget faucibus nibh placerat.</a></h6>
															</td>
															<td>$349.00</td>
															<td><a href="javascript:;">Derick Wong</a></td>
														</tr>
														<tr>
															<td>13/02/2013</td>
															<td class="hidden-sm">
																<a href="javascript:;">
																	<img src="assets/img/product/product-2.png" alt="" />
																</a>
															</td>
															<td>
																<h6><a href="javascript:;">Nunc eleifend lorem eu velit eleifend, eget faucibus nibh placerat.</a></h6>
															</td>
															<td>$399.00</td>
															<td><a href="javascript:;">Derick Wong</a></td>
														</tr>
														<tr>
															<td>13/02/2013</td>
															<td class="hidden-sm">
																<a href="javascript:;">
																	<img src="assets/img/product/product-3.png" alt="" />
																</a>
															</td>
															<td>
																<h6><a href="javascript:;">Nunc eleifend lorem eu velit eleifend, eget faucibus nibh placerat.</a></h6>
															</td>
															<td>$499.00</td>
															<td><a href="javascript:;">Derick Wong</a></td>
														</tr>
														<tr>
															<td>13/02/2013</td>
															<td class="hidden-sm">
																<a href="javascript:;">
																	<img src="assets/img/product/product-4.png" alt="" />
																</a>
															</td>
															<td>
																<h6><a href="javascript:;">Nunc eleifend lorem eu velit eleifend, eget faucibus nibh placerat.</a></h6>
															</td>
															<td>$230.00</td>
															<td><a href="javascript:;">Derick Wong</a></td>
														</tr>
														<tr>
															<td>13/02/2013</td>
															<td class="hidden-tablet hidden-phone">
																<a href="javascript:;">
																	<img src="assets/img/product/product-5.png" alt="" />
																</a>
															</td>
															<td>
																<h6><a href="javascript:;">Nunc eleifend lorem eu velit eleifend, eget faucibus nibh placerat.</a></h6>
															</td>
															<td>$500.00</td>
															<td><a href="javascript:;">Derick Wong</a></td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
										<div class="tab-pane fade" id="email">
											<div class="height-sm" data-scrollbar="true">
												<ul class="media-list media-list-with-divider">
													<li class="media media-sm">
														<a href="javascript:;" class="pull-left">
															<img src="assets/img/user-1.jpg" alt="" class="media-object rounded-corner" />
														</a>
														<div class="media-body">
															<a href="javascript:;"><h4 class="media-heading">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</h4></a>
															<p class="m-b-5">
																Aenean mollis arcu sed turpis accumsan dignissim. Etiam vel tortor at risus tristique convallis. Donec adipiscing euismod arcu id euismod. Suspendisse potenti. Aliquam lacinia sapien ac urna placerat, eu interdum mauris viverra.
															</p>
															<i class="text-muted">Received on 04/16/2013, 12.39pm</i>
														</div>
													</li>
													<li class="media media-sm">
														<a href="javascript:;" class="pull-left">
															<img src="assets/img/user-2.jpg" alt="" class="media-object rounded-corner" />
														</a>
														<div class="media-body">
															<a href="javascript:;"><h4 class="media-heading">Praesent et sem porta leo tempus tincidunt eleifend et arcu.</h4></a>
															<p class="m-b-5">
																Proin adipiscing dui nulla. Duis pharetra vel sem ac adipiscing. Vestibulum ut porta leo. Pellentesque orci neque, tempor ornare purus nec, fringilla venenatis elit. Duis at est non nisl dapibus lacinia.
															</p>
															<i class="text-muted">Received on 04/16/2013, 12.39pm</i>
														</div>
													</li>
													<li class="media media-sm">
														<a href="javascript:;" class="pull-left">
															<img src="assets/img/user-3.jpg" alt="" class="media-object rounded-corner" />
														</a>
														<div class="media-body">
															<a href="javascript:;"><h4 class="media-heading">Ut mi eros, varius nec mi vel, consectetur convallis diam.</h4></a>
															<p class="m-b-5">
																Ut mi eros, varius nec mi vel, consectetur convallis diam. Nullam eget hendrerit eros. Duis lacinia condimentum justo at ultrices. Phasellus sapien arcu, fringilla eu pulvinar id, mattis quis mauris.
															</p>
															<i class="text-muted">Received on 04/16/2013, 12.39pm</i>
														</div>
													</li>
													<li class="media media-sm">
														<a href="javascript:;" class="pull-left">
															<img src="assets/img/user-4.jpg" alt="" class="media-object rounded-corner" />
														</a>
														<div class="media-body">
															<a href="javascript:;"><h4 class="media-heading">Aliquam nec dolor vel nisl dictum ullamcorper.</h4></a>
															<p class="m-b-5">
																Aliquam nec dolor vel nisl dictum ullamcorper. Duis vel magna enim. Aenean volutpat a dui vitae pulvinar. Nullam ligula mauris, dictum eu ullamcorper quis, lacinia nec mauris.
															</p>
															<i class="text-muted">Received on 04/16/2013, 12.39pm</i>
														</div>
													</li>
												</ul>
											</div>
										</div>
									</div>
									
									<div class="panel panel-inverse" data-sortable-id="index-4">
				                        <div class="panel-heading">
				                            <div class="panel-heading-btn">
				                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
				                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
				                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
				                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
				                            </div>
				                            <h4 class="panel-title">Quick Post</h4>
				                        </div>
				                        <div class="panel-toolbar">
				                            <div class="btn-group m-r-5">
												<a class="btn btn-white" href="javascript:;"><i class="fa fa-bold"></i></a>
												<a class="btn btn-white active" href="javascript:;"><i class="fa fa-italic"></i></a>
												<a class="btn btn-white" href="javascript:;"><i class="fa fa-underline"></i></a>
											</div>
				                            <div class="btn-group">
												<a class="btn btn-white" href="javascript:;"><i class="fa fa-align-left"></i></a>
												<a class="btn btn-white active" href="javascript:;"><i class="fa fa-align-center"></i></a>
												<a class="btn btn-white" href="javascript:;"><i class="fa fa-align-right"></i></a>
												<a class="btn btn-white" href="javascript:;"><i class="fa fa-align-justify"></i></a>
											</div>
				                        </div>
				                        <textarea class="form-control no-rounded-corner bg-silver" rows="14">Enter some comment.</textarea>
				                        <div class="panel-footer text-right">
				                            <a href="javascript:;" class="btn btn-white btn-sm">Cancel</a>
				                            <a href="javascript:;" class="btn btn-primary btn-sm m-l-5">Action</a>
				                        </div>
				                    </div>
				                    
									<div class="panel panel-inverse" data-sortable-id="index-5">
										<div class="panel-heading">
											<div class="panel-heading-btn">
												<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
												<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
												<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
												<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
											</div>
											<h4 class="panel-title">Message</h4>
										</div>
										<div class="panel-body">
											<div class="height-sm" data-scrollbar="true">
												<ul class="media-list media-list-with-divider media-messaging">
													<li class="media media-sm">
														<a href="javascript:;" class="pull-left">
															<img src="assets/img/user-5.jpg" alt="" class="media-object rounded-corner" />
														</a>
														<div class="media-body">
															<h5 class="media-heading">John Doe</h5>
															<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi id nunc non eros fermentum vestibulum ut id felis. Nunc molestie libero eget urna aliquet, vitae laoreet felis ultricies. Fusce sit amet massa malesuada, tincidunt augue vitae, gravida felis.</p>
														</div>
													</li>
													<li class="media media-sm">
														<a href="javascript:;" class="pull-left">
															<img src="assets/img/user-6.jpg" alt="" class="media-object rounded-corner" />
														</a>
														<div class="media-body">
															<h5 class="media-heading">Terry Ng</h5>
															<p>Sed in ante vel ipsum tristique euismod posuere eget nulla. Quisque ante sem, scelerisque iaculis interdum quis, eleifend id mi. Fusce congue leo nec mauris malesuada, id scelerisque sapien ultricies.</p>
														</div>
													</li>
													<li class="media media-sm">
														<a href="javascript:;" class="pull-left">
															<img src="assets/img/user-8.jpg" alt="" class="media-object rounded-corner" />
														</a>
														<div class="media-body">
															<h5 class="media-heading">Fiona Log</h5>
															<p>Pellentesque dictum in tortor ac blandit. Nulla rutrum eu leo vulputate ornare. Nulla a semper mi, ac lacinia sapien. Sed volutpat ornare eros, vel semper sem sagittis in. Quisque risus ipsum, iaculis quis cursus eu, tristique sed nulla.</p>
														</div>
													</li>
													<li class="media media-sm">
														<a href="javascript:;" class="pull-left">
															<img src="assets/img/user-7.jpg" alt="" class="media-object rounded-corner" />
														</a>
														<div class="media-body">
															<h5 class="media-heading">John Doe</h5>
															<p>Morbi molestie lorem quis accumsan elementum. Morbi condimentum nisl iaculis, laoreet risus sed, porta neque. Proin mi leo, dapibus at ligula a, aliquam consectetur metus.</p>
														</div>
													</li>
												</ul>
											</div>
										</div>
										<div class="panel-footer">
											<form>
												<div class="input-group">
													<input type="text" class="form-control bg-silver" placeholder="Enter message" />
													<span class="input-group-btn">
														<button class="btn btn-primary" type="button"><i class="fa fa-pencil"></i></button>
													</span>
												</div>
											</form>
				                        </div>
									</div>
								</div>
								<!-- end col-8 -->
								<!-- begin col-4 -->
								<div class="col-md-4">
									<div class="panel panel-inverse" data-sortable-id="index-6">
										<div class="panel-heading">
											<div class="panel-heading-btn">
												<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
												<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
												<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
												<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
											</div>
											<h4 class="panel-title">Analytics Details</h4>
										</div>
										<div class="panel-body p-t-0">
											<table class="table table-valign-middle m-b-0">
												<thead>
													<tr>	
														<th>Source</th>
														<th>Total</th>
														<th>Trend</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td><label class="label label-danger">Unique Visitor</label></td>
														<td>13,203 <span class="text-success"><i class="fa fa-arrow-up"></i></span></td>
														<td><div id="sparkline-unique-visitor"></div></td>
													</tr>
													<tr>
														<td><label class="label label-warning">Bounce Rate</label></td>
														<td>28.2%</td>
														<td><div id="sparkline-bounce-rate"></div></td>
													</tr>
													<tr>
														<td><label class="label label-success">Total Page Views</label></td>
														<td>1,230,030</td>
														<td><div id="sparkline-total-page-views"></div></td>
													</tr>
													<tr>
														<td><label class="label label-primary">Avg Time On Site</label></td>
														<td>00:03:45</td>
														<td><div id="sparkline-avg-time-on-site"></div></td>
													</tr>
													<tr>
														<td><label class="label label-default">% New Visits</label></td>
														<td>40.5%</td>
														<td><div id="sparkline-new-visits"></div></td>
													</tr>
													<tr>
														<td><label class="label label-inverse">Return Visitors</label></td>
														<td>73.4%</td>
														<td><div id="sparkline-return-visitors"></div></td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
									
									<div class="panel panel-inverse" data-sortable-id="index-7">
										<div class="panel-heading">
											<div class="panel-heading-btn">
												<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
												<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
												<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
												<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
											</div>
											<h4 class="panel-title">Visitors User Agent</h4>
										</div>
										<div class="panel-body">
											<div id="donut-chart" class="height-sm"></div>
										</div>
									</div>
									
									<div class="panel panel-inverse" data-sortable-id="index-8">
										<div class="panel-heading">
											<div class="panel-heading-btn">
												<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
												<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
												<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
												<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
											</div>
											<h4 class="panel-title">Todo List</h4>
										</div>
										<div class="panel-body p-0">
											<ul class="todolist">
												<li class="active">
													<a href="javascript:;" class="todolist-container active" data-click="todolist">
														<div class="todolist-input"><i class="fa fa-square-o"></i></div>
														<div class="todolist-title">Donec vehicula pretium nisl, id lacinia nisl tincidunt id.</div>
													</a>
												</li>
												<li>
													<a href="javascript:;" class="todolist-container" data-click="todolist">
														<div class="todolist-input"><i class="fa fa-square-o"></i></div>
														<div class="todolist-title">Duis a ullamcorper massa.</div>
													</a>
												</li>
												<li>
													<a href="javascript:;" class="todolist-container" data-click="todolist">
														<div class="todolist-input"><i class="fa fa-square-o"></i></div>
														<div class="todolist-title">Phasellus bibendum, odio nec vestibulum ullamcorper.</div>
													</a>
												</li>
												<li>
													<a href="javascript:;" class="todolist-container" data-click="todolist">
														<div class="todolist-input"><i class="fa fa-square-o"></i></div>
														<div class="todolist-title">Duis pharetra mi sit amet dictum congue.</div>
													</a>
												</li>
												<li>
													<a href="javascript:;" class="todolist-container" data-click="todolist">
														<div class="todolist-input"><i class="fa fa-square-o"></i></div>
														<div class="todolist-title">Duis pharetra mi sit amet dictum congue.</div>
													</a>
												</li>
												<li>
													<a href="javascript:;" class="todolist-container" data-click="todolist">
														<div class="todolist-input"><i class="fa fa-square-o"></i></div>
														<div class="todolist-title">Phasellus bibendum, odio nec vestibulum ullamcorper.</div>
													</a>
												</li>
												<li>
													<a href="javascript:;" class="todolist-container active" data-click="todolist">
														<div class="todolist-input"><i class="fa fa-square-o"></i></div>
														<div class="todolist-title">Donec vehicula pretium nisl, id lacinia nisl tincidunt id.</div>
													</a>
												</li>
											</ul>
										</div>
									</div>
									
									<div class="panel panel-inverse" data-sortable-id="index-9">
										<div class="panel-heading">
											<div class="panel-heading-btn">
												<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
												<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
												<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
												<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
											</div>
											<h4 class="panel-title">World Visitors</h4>
										</div>
										<div class="panel-body p-0">
											<div id="world-map" class="height-sm width-full"></div>
										</div>
									</div>
									
									<div class="panel panel-inverse" data-sortable-id="index-10">
										<div class="panel-heading">
											<div class="panel-heading-btn">
												<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
												<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
												<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
												<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
											</div>
											<h4 class="panel-title">Calendar</h4>
										</div>
										<div class="panel-body">
											<div id="datepicker-inline" class="datepicker-full-width"><div></div></div>
										</div>
									</div>
								</div>
								<!-- end col-4 -->
							</div>
							<!-- end row -->
						</div>
						<!-- end #content -->
												
						<!-- begin scroll to top btn -->
						<a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
						<!-- end scroll to top btn -->';
	break;

	case 'logout':		
		session_destroy();
	break;

	case 'vfrmpemeriksaan':
		$view = '<!-- begin #content -->
						<div id="content" class="content">
							<div class="row">
								<div class="col-md-12">
									<div class="panel panel-inverse">
										<div class="panel-heading">
											<div class="panel-heading-btn">
												<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
												<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
											</div>
											<h4 class="panel-title">Pemeriksaan Awal</h4>
										</div>
										<div class="panel-body">
											<form action="javascript:action('.$_POST['han'].')" id="frmpemeriksaan" class="form-horizontal" method="post" enctype="multipart/form-data">
												<div class="form-group">
													<div class="col-md-2"></div>
													<div class="col-md-10">Apakah anda dalam jangka 5 (ima) tahun terakhir ini pernah atau sedang menderita penyakit : Asma, Cacat, Tumor/Kanker, TBC, Paru - Paru, Kencing Manis, Hati, Ginjal, Jantung, Stroke, Tekanan Darah Tinggi, Epilepsi, Gangguan Jiwa, Penyakit Autoimum, Keterbelakangan Mental atau Idiot? Jika Perhan “Ya” Jelaskan!</div>
								        </div>
								        '.inputradio("pertanyaan1","").'
								        '.inputtext("ket1","","","disabled").'
								        <div class="form-group">
													<div class="col-md-2"></div>
													<div class="col-md-10">Apakah Berat badan anda berubah dalam 12 bulan terakhir ini? Jika Perhan “Ya” Jelaskan!</div>
								        </div>
								        '.inputradio("pertanyaan2","").'
								        '.inputtext("ket2","","","disabled").'
								        <div class="form-group">
													<div class="col-md-2"></div>
													<div class="col-md-10">Apakah Anda menderita HIV positif / AIDS?</div>
								        </div>
								        '.inputradio("pertanyaan3","").'
								        '.inputtext("ket3","","","disabled").'
								        <div class="form-group">
													<div class="col-md-2"></div>
													<div class="col-md-10">Apakah anda mengkonsumsi rutin (ketergantungan) pada narkoba? Jika Perhan “Ya” Jelaskan!</div>
								        </div>
								        '.inputradio("pertanyaan4","").'
								        '.inputtext("ket4","","","disabled").'
								        <div class="form-group">
													<div class="col-md-2"></div>
													<div class="col-md-10">Khusus untuk Wanita, apakah anda sedang hamil? Jika “Ya” adakah komplikasi / penyulit kehamilan? Jelaskan? Usia Kandungan?</div>
								        </div>							      
								        '.inputradio("pertanyaan5","").'  
								        '.inputtext("ket5","","","disabled").'
												'.inputtext("tinggibadan","Tinggi Badan (Cm)","","required").'
												'.inputtext("beratbadan","Berat Badan (Kg)","","required").'
												'.inputtext("tekanandarah","Tekanan Darah (mmHg)","","required").'
												'.inputtext("nadi","Nadi (x/menit)","","required").'
												'.inputtext("pernafasan","Pernafasan (x/menit)","","required").'
												'.inputtext("guladarah","Gula Darah (mg/dL)","","required").'
								        <div class="form-group">
													<div class="col-md-2"></div>
													<div class="col-md-10">Dari Pemeriksaan dan keterangan kesehatan diatas saya simpulkan bahwa saat ini Calon Debitur dalam keadaan :</div>
								        </div>	
								        '.inputtext("keterangan","").'
								        '.inputtext("catatan","Catatan").'
								        <div class="form-group text-center">
								        	<button type="submit" class="btn btn-success text-center">Submit</button>
								        </div>
											</form>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- end #content -->';

		$json = '{"result":"'.tohtml($view).'"}';
		echo json_encode($json);
	break;

	case 'vblank':
		echo '<!-- begin #content -->
						<div id="content" class="content">
							<div class="row">
								<div class="col-md-12">
									<div class="panel panel-inverse">
										<div class="panel-heading">
											<div class="panel-heading-btn">
												<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
												<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
											</div>
											<h4 class="panel-title">blank</h4>
										</div>
										<div class="panel-body">
										</div
									</div>
								</div>
							</div>
						</div>';
	break;

	case 'action':
		$error = 0;
		$idspk = $_POST['idspk'];
		$query = "SELECT * FROM fu_ajk_spak WHERE spak = '".$idspk."' and status = 'Proses' and del is null";
		
		$qspk = mysqli_query($con,$query);
		$result = mysqli_num_rows($qspk);

		if($result > 0){
			$_SESSION["halaman"] = 'vfrmpemeriksaan';
			$id = "redirect";
			$result = "vfrmpemeriksaan";
		}else{
			$error = 1;
			$id = "idspk";
			$result = "SPK Tidak Ada";			
		}

		$json = '{"error":"'.$error.'","dataresult":[{"id":"'.$id.'","result":"'.$result.'"}]}';		
		echo json_encode($json);
	break;

	case 'vvalidasispk':
		$view = '<!-- begin #content -->
						<div id="content" class="content">
							<div class="row">
								<div class="col-md-12">
									<div class="panel panel-inverse">
										<div class="panel-heading">
											<div class="panel-heading-btn">
												<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
												<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
											</div>
											<h4 class="panel-title">Pemeriksaan</h4>
										</div>
										<div class="panel-body">
											<form action="javascript:action(\'frmpemeriksaan\');" id="frmpemeriksaan" class="form-horizontal" method="post" enctype="multipart/form-data">
												'.inputtext("idspk","No SPK","Masukan No SPK","").'												
								        <div class="form-group text-center">
								        	<button type="submit" class="btn btn-success text-center">Submit</button>
								        </div>											
											</form>
										</div
									</div>
								</div>
							</div>
						</div>';
		$json = '{"result":"'.tohtml($view).'"}';
		echo json_encode($json);
	break;

	case 'ppemeriksaan':
		$pertanyaan1 = $_POST['pertanyaan1'];
		$pertanyaan2 = $_POST['pertanyaan2'];
		$pertanyaan3 = $_POST['pertanyaan3'];
		$pertanyaan4 = $_POST['pertanyaan4'];
		$pertanyaan5 = $_POST['pertanyaan5'];
		$ket1 = strtoupper($_POST['ket1']);
		$ket2 = strtoupper($_POST['ket2']);
		$ket3 = strtoupper($_POST['ket3']);
		$ket4 = strtoupper($_POST['ket4']);
		$ket5 = strtoupper($_POST['ket5']);
		$tinggibadan = $_POST['tinggibadan'];
		$tekanandarah = $_POST['tekanandarah'];
		$nadi = $_POST['nadi'];
		$pernafasan = $_POST['pernafasan'];
		$guladarah = $_POST['guladarah'];
		$keterangan = strtoupper($_POST['keterangan']);
		$catatan = strtoupper($_POST['catatan']);

		$query = "UPDATE fu_ajk_spak_form 
							SET pertanyaan1 = '".$pertanyaan1."',
									pertanyaan2 = '".$pertanyaan2."',
									pertanyaan3 = '".$pertanyaan3."',
									pertanyaan4 = '".$pertanyaan4."',
									pertanyaan5 = '".$pertanyaan5."',
									ket1 = '".$ket1."',
									ket2 = '".$ket2."',
									ket3 = '".$ket3."',
									ket4 = '".$ket4."',
									ket5 = '".$ket5."',
									tinggibadan = '".$tinggibadan."',
									tekanandarah = '".$tekanandarah."',
									nadi = '".$nadi."',
									pernafasan = '".$pernafasan."',
									guladarah = '".$guladarah."',
									keterangan = '".$keterangan."',
									catatan = '".$catatan."'
							WHERE idspk = '".$idspk."'";

		echo $query;
		//mysqli_query($query);
	break;
}
?>