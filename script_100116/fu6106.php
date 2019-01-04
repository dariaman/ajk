<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :penting_kaga@yahoo.com
// ----------------------------------------------------------------------------------
$host = "localhost";
$user = "adonai010914ajk";
$pass = "GtZpZWXy53aJUcWU";
$db   = "adonai_ajk0109";


//	mysql_connect($host, $user, $pass);
//	mysql_select_db($db);

$conn = mysql_connect( $host, $user, $pass ) or die( mysql_error( ) );
mysql_select_db( $db, $conn ) or die( mysql_error( $conn ) );
?>
