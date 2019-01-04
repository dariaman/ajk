<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// YM(Yahoo Messenger) : penting_kaga
// @ Copyright 2013 on January
// ----------------------------------------------------------------------------------
include_once('ui.php');
session_start();
$database = new db();
out('on_load','onLoad="javascript: document.formLogin.nama.focus()"');

if ($_REQUEST['op'] == 'login') {
//$database->doQuery('SELECT * FROM pengguna WHERE nm_user="' . $_REQUEST['username'] . '" AND id_cost!="" AND aktif="Y" AND log="T" ');
//$database->doQuery('SELECT * FROM pengguna WHERE nm_user="' . mysql_real_escape_string($_REQUEST['username']) . '" AND (level="" OR level>=5 AND id_cost!="" AND aktif="Y" AND log="T") '); 05112014
  $r = mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="' . mysql_real_escape_string($_REQUEST['username']) . '" AND id_cost!="" AND aktif="Y" '));
    if (!$r['nm_user']) {
    	$mobdatabase = mysql_fetch_array($database->doQuery('SELECT * FROM user_mobile WHERE nama="' . mysql_real_escape_string($_REQUEST['username']) . '" AND idbank!="" AND status="Aktif" AND supervisor=0'));
		echo $mobdatabase['nama'];
    	if (md5($_REQUEST['pass']) == $mobdatabase['passw']) {
    		session_register('usernama');
    		$_SESSION['nm_user'] = $_REQUEST['username'];
    		header('Location: imob.php');
    	}
    	else {	header('Location: login.php?msg=user mobile tidak terdaftar');	}
    }else{
	if (md5($_REQUEST['pass']) == $r['password']) {
        session_register('usernama');
        $_SESSION['nm_user'] = $_REQUEST['username'];
    	$userlog_in = $database->doQuery('UPDATE pengguna SET log="Y" WHERE nm_user="'.$_REQUEST['username'].'" AND id_polis !=""');
        header('Location: index.php');
    }
	else {	header('Location: login.php?msg=Kesalahan pada username atau password');	}
	}
} elseif ($_REQUEST['op'] == 'logout') {
if (session_is_registered('nm_user')) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));	}
$userlog_out = $database->doQuery('UPDATE pengguna SET log="T" WHERE nm_user="'.$q['nm_user'].'"');
if ($q['supervisor']=="1") {	$met = $database->doQuery('UPDATE pengguna SET id_cost="",id_polis="", level="", wilayah="" WHERE id="'.$q['id'].'"');	}else{	}
$loggerout = $database->doQuery('UPDATE ajk_logger SET lastdate_logout="'.$datelog.'", lasttime_logout="'.$timelog.'" WHERE id_user="'.$q['id'].'" AND lastdate_logout IS NULL AND lasttime_logout IS NULL');
session_destroy();
header('Location: login.php');
}


echo '<div class="wrapper">
		<h3><span><img src="image/logo_adonai_1.gif" width="50"> A D O N A I</span> | Pialang Asuransi</h3>
		<h2>Aplikasi Asuransi Jiwa Kredit dan Pensiunan</h2>
		<div class="content">
			<div id="form_wrapper" class="form_wrapper">
			<form method="post" action="" class="register">
			<h3>Register</h3>
				<div class="column">
					<div><label>First Name:</label><input type="text" name="regfname" /><span class="error">This is an error</span></div>
					<div><label>Last Name:</label><input type="text" name="reglname" /><span class="error">This is an error</span></div>
					<div><label>Website:</label><input type="text" name="regweb" value="http://"/><span class="error">This is an error</span></div>
				</div>
				<div class="column">
					<div><label>Username:</label><input type="text" name="reguname"/><span class="error">This is an error</span></div>
					<div><label>Email:</label><input type="text" name="regmail" /><span class="error">This is an error</span></div>
					<div><label>Password:</label><input type="password" name="regpassw" /><span class="error">This is an error</span></div>
				</div>
				<div class="bottom">
					<div class="remember"><input type="checkbox" /><span>Send me updates</span></div>
					<input type="submit" value="Register" /><input type="hidden" name="op" value="regist"><a href="index.html" rel="login" class="linkform"><font color="white">You have an account already? Log in here</font></a>
					<div class="clear"></div>
				</div>
			</form>

			<form method="post" action="" class="login active">
			<h3>Login</h3>
			<div><label><h100><span><center>'.$_REQUEST['msg'].'</center></span></h100>Username:</label><input type="text" name="username" /><span class="error">This is an error</span></div>
			<div><label>Password: </label>
				<input type="password" name="pass" /><span class="error">This is an error</span>
			<a href="forgot_password.html" rel="forgot_password" class="forgot linkform">Forgot your password?</a></div>
			<div class="bottom">
				<div class="remember"><input type="checkbox" /><span>Keep me logged in</span></div>
				<input type="submit" value="Login">
				<input type="hidden" name="op" value="login">
				<a href="register.html" rel="register" class="linkform"><font color="white">You don\'t have an account yet?<br />Register here</font></a>
				<div class="clear"></div>
			</div>
			</form>

		<form method="post" action="" class="forgot_password">
		<h3>Forgot Password</h3>
		<div><label>Email:</label><input type="text" name="usermail" /><span class="error">This is an error</span></div>
		<div class="bottom"><input type="submit" value="Send reminder"><input type="hidden" name="op" value="fgotpass">
			<a href="index.html" rel="login" class="linkform"><font color="white">Suddenly remebered? Log in here</font></a>
			<a href="register.html" rel="register" class="linkform"><font color="white">You don\'t have an account? Register here</font></a>
		<div class="clear"></div>
		</div>
		</form>
	</div>
	<div class="clear"></div>
</div>
<!--<a class="back" href="#">back to the Codrops tutorial</a>-->
</div>';
?>

<link rel="stylesheet" type="text/css" href="toolsajkscript/ajklogin/css/style.css" />
		<script src="toolsajkscript/ajklogin/js/cufon-yui.js" type="text/javascript"></script>
		<script src="toolsajkscript/ajklogin/js/ChunkFive_400.font.js" type="text/javascript"></script>
		<script type="text/javascript">
			Cufon.replace('h1',{ textShadow: '1px 1px #fff'});
Cufon.replace('h2',{ textShadow: '1px 1px #fff'});
Cufon.replace('h3',{ textShadow: '1px 1px #000'});
Cufon.replace('.back');
</script>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
		<script type="text/javascript">
$(function() {
	//the form wrapper (includes all forms)
	var $form_wrapper	= $('#form_wrapper'),
	//the current form is the one with class active
	$currentForm	= $form_wrapper.children('form.active'),
	//the change form links
	$linkform		= $form_wrapper.find('.linkform');

	//get width and height of each form and store them for later
	$form_wrapper.children('form').each(function(i){
		var $theForm	= $(this);
		//solve the inline display none problem when using fadeIn fadeOut
		if(!$theForm.hasClass('active'))
		$theForm.hide();
		$theForm.data({
			width	: $theForm.width(),
			height	: $theForm.height()
		});
	});

	//set width and height of wrapper (same of current form)
	setWrapperWidth();

	/*
	clicking a link (change form event) in the form
	makes the current form hide.
	The wrapper animates its width and height to the
	width and height of the new current form.
	After the animation, the new form is shown
	*/
	$linkform.bind('click',function(e){
		var $link	= $(this);
		var target	= $link.attr('rel');
		$currentForm.fadeOut(400,function(){
			//remove class active from current form
			$currentForm.removeClass('active');
			//new current form
			$currentForm= $form_wrapper.children('form.'+target);
			//animate the wrapper
			$form_wrapper.stop()
			.animate({
				width	: $currentForm.data('width') + 'px',
				height	: $currentForm.data('height') + 'px'
			},500,function(){
				//new form gets class active
				$currentForm.addClass('active');
				//show the new form
				$currentForm.fadeIn(400);
			});
		});
		e.preventDefault();
	});

	function setWrapperWidth(){
		$form_wrapper.css({
			width	: $currentForm.data('width') + 'px',
			height	: $currentForm.data('height') + 'px'
		});
	}

	/*
	for the demo we disabled the submit buttons
	if you submit the form, you need to check the
	which form was submited, and give the class active
	to the form you want to show

	$form_wrapper.find('input[type="submit"]')
	.click(function(e){
	e.preventDefault();
	});*/
});
</script>
