<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :penting_kaga@yahoo.com
// ----------------------------------------------------------------------------------
$host = "202.43.164.122:3361";
$user = "dariaman";
//$pass = "GtZpZWXy53aJUcWU";
$pass = "Dar012018";
$db   = "adonai_dummy";


//	mysql_connect($host, $user, $pass);
//	mysql_select_db($db);

$conn = mysql_connect( $host, $user, $pass ) or die( mysql_error( ) );
mysql_select_db( $db, $conn ) or die( mysql_error( $conn ) );

$datelog = date("Y-m-d");
$timelog = date("G:i:s");
$alamat_ip = $_SERVER['REMOTE_ADDR'];
$nama_host = gethostbyaddr($_SERVER['REMOTE_ADDR']);
$useragent = $_SERVER ['HTTP_USER_AGENT'];
$referrer = getenv('HTTP_REFERER');
?>