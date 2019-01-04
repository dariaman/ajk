<?php
// ----------------------------------------------------------------------------------
// Copyright (C) 2013 APLIKASI AJK
// ----------------------------------------------------------------------------------
//
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ----------------------------------------------------------------------------------
include_once ("ajk6106.php");
include_once ("metImage.php");
//penambahan tanggal //
function add_days($my_date,$numdays) {
$date_t = strtotime($my_date.' UTC');
return gmdate('Y-m-d',$date_t + ($numdays*86400));
}
//penambahan tanggal //
// DOLLAR //
function num2words($num, $c=1) {
    $ZERO = 'zero';
    $MINUS = 'minus';
    $lowName = array(
         /* zero is shown as "" since it is never used in combined forms */
         /* 0 .. 19 */
         "", "one", "two", "three", "four", "five","six", "seven", "eight", "nine", "ten","eleven", "twelve", "thirteen", "fourteen", "fifteen","sixteen", "seventeen", "eighteen", "nineteen");

    $tys = array(
         /* 0, 10, 20, 30 ... 90 */
         "", "", "twenty", "thirty", "forty", "fifty","sixty", "seventy", "eighty", "ninety");

    $groupName = array(
         /* We only need up to a quintillion, since a long is about 9 * 10 ^ 18 */
         /* American: unit, hundred, thousand, million, billion, trillion, quadrillion, quintillion */
         "", "hundred", "thousand", "million", "billion","trillion", "quadrillion", "quintillion");

    $divisor = array(
         /* How many of this group is needed to form one of the succeeding group. */
         /* American: unit, hundred, thousand, million, billion, trillion, quadrillion, quintillion */
         100, 10, 1000, 1000, 1000, 1000, 1000, 1000) ;

    $num = str_replace(",","",$num);
    $num = number_format($num,2,'.','');
    $cents = substr($num,strlen($num)-2,strlen($num)-1);
    $num = (int)$num;

    $s = "";

    if ( $num == 0 ) $s = $ZERO;
    $negative = ($num < 0 );
    if ( $negative ) $num = -$num;
    // Work least significant digit to most, right to left.
    // until high order part is all 0s.
    for ( $i=0; $num>0; $i++ ) {
       $remdr = (int)($num % $divisor[$i]);
       $num = $num / $divisor[$i];
       // check for 1100 .. 1999, 2100..2999, ... 5200..5999
       // but not 1000..1099,  2000..2099, ...
       // Special case written as fifty-nine hundred.
       // e.g. thousands digit is 1..5 and hundreds digit is 1..9
       // Only when no further higher order.
       if ( $i == 1 /* doing hundreds */ && 1 <= $num && $num <= 5 ){
           if ( $remdr > 0 ){
               $remdr = ($num * 10);
               $num = 0;
           } // end if
       } // end if
       if ( $remdr == 0 ){
           continue;
       }
       $t = "";
       if ( $remdr < 20 ){
           $t = $lowName[$remdr];
       }
       else if ( $remdr < 100 ){
           $units = (int)$remdr % 10;
           $tens = (int)$remdr / 10;
           $t = $tys [$tens];
           if ( $units != 0 ){
               $t .= "-" . $lowName[$units];
           }
       }else {
           $t = num2words($remdr, 0);
       }
       $s = $t." ".$groupName[$i]." ".$s;
       $num = (int)$num;
    } // end for
    $s = trim($s);
    if ( $negative ){
       $s = $MINUS . " " . $s;
    }

   // SCRIPT ASLINYA  if ($c == 1) $s .= " and $cents/100";
    if ($c == 1) $s .= " dollars";

    return $s;
} // end num2words
// DOLLAR //
// DOLLAR //
function num2wordsdollar($num, $c=1) {
	$ZERO = 'zero';
	$MINUS = 'minus';
	$lowName = array(
	     /* zero is shown as "" since it is never used in combined forms */
	     /* 0 .. 19 */
	     "", "one", "two", "three", "four", "five","six", "seven", "eight", "nine", "ten","eleven", "twelve", "thirteen", "fourteen", "fifteen","sixteen", "seventeen", "eighteen", "nineteen");

	$tys = array(
	     /* 0, 10, 20, 30 ... 90 */
	     "", "", "twenty", "thirty", "forty", "fifty","sixty", "seventy", "eighty", "ninety");

	$groupName = array(
	     /* We only need up to a quintillion, since a long is about 9 * 10 ^ 18 */
	     /* American: unit, hundred, thousand, million, billion, trillion, quadrillion, quintillion */
	     "", "hundred", "thousand", "million", "billion","trillion", "quadrillion", "quintillion");

	$divisor = array(
	     /* How many of this group is needed to form one of the succeeding group. */
	     /* American: unit, hundred, thousand, million, billion, trillion, quadrillion, quintillion */
	     100, 10, 1000, 1000, 1000, 1000, 1000, 1000) ;

	$num = str_replace(",","",$num);
	$num = number_format($num,2,'.','');
	$cents = substr($num,strlen($num)-2,strlen($num)-1);
	$num = (int)$num;

	$s = "";

	if ( $num == 0 ) $s = $ZERO;
	$negative = ($num < 0 );
	if ( $negative ) $num = -$num;
	// Work least significant digit to most, right to left.
	// until high order part is all 0s.
	for ( $i=0; $num>0; $i++ ) {
		$remdr = (int)($num % $divisor[$i]);
		$num = $num / $divisor[$i];
		// check for 1100 .. 1999, 2100..2999, ... 5200..5999
		// but not 1000..1099,  2000..2099, ...
		// Special case written as fifty-nine hundred.
		// e.g. thousands digit is 1..5 and hundreds digit is 1..9
		// Only when no further higher order.
		if ( $i == 1 /* doing hundreds */ && 1 <= $num && $num <= 5 ){
			if ( $remdr > 0 ){
				$remdr = ($num * 10);
				$num = 0;
			} // end if
		} // end if
		if ( $remdr == 0 ){
			continue;
		}
		$t = "";
		if ( $remdr < 20 ){
			$t = $lowName[$remdr];
		}
		else if ( $remdr < 100 ){
			$units = (int)$remdr % 10;
			$tens = (int)$remdr / 10;
			$t = $tys [$tens];
			if ( $units != 0 ){
				$t .= "-" . $lowName[$units];
			}
		}else {
			$t = num2words($remdr, 0);
		}
		$s = $t." ".$groupName[$i]." ".$s;
		$num = (int)$num;
	} // end for
	$s = trim($s);
	if ( $negative ){
		$s = $MINUS . " " . $s;
	}

	// SCRIPT ASLINYA  if ($c == 1) $s .= " and $cents/100";
	if ($c == 1) $s .= "";

	return $s;
}// end num2words
// DOLLAR //
//DOLLAR KOMA//
function num2wordskoma($num, $c=1) {
	$ZERO = 'zero';
	$MINUS = 'minus';
	$lowName = array(
	     /* zero is shown as "" since it is never used in combined forms */
	     /* 0 .. 19 */
	     "", "one", "two", "three", "four", "five","six", "seven", "eight", "nine", "ten","eleven", "twelve", "thirteen", "fourteen", "fifteen","sixteen", "seventeen", "eighteen", "nineteen");

	$tys = array(
	     /* 0, 10, 20, 30 ... 90 */
	     "", "", "twenty", "thirty", "forty", "fifty","sixty", "seventy", "eighty", "ninety");

	$groupName = array(
	     /* We only need up to a quintillion, since a long is about 9 * 10 ^ 18 */
	     /* American: unit, hundred, thousand, million, billion, trillion, quadrillion, quintillion */
	     "", "hundred", "thousand", "million", "billion","trillion", "quadrillion", "quintillion");

	$divisor = array(
	     /* How many of this group is needed to form one of the succeeding group. */
	     /* American: unit, hundred, thousand, million, billion, trillion, quadrillion, quintillion */
	     100, 10, 1000, 1000, 1000, 1000, 1000, 1000) ;

	$num = str_replace(",","",$num);
	$num = number_format($num,2,'.','');
	$cents = substr($num,strlen($num)-2,strlen($num)-1);
	$num = (int)$num;

	$s = "";

	if ( $num == 0 ) $s = $ZERO;
	$negative = ($num < 0 );
	if ( $negative ) $num = -$num;
	// Work least significant digit to most, right to left.
	// until high order part is all 0s.
	for ( $i=0; $num>0; $i++ ) {
		$remdr = (int)($num % $divisor[$i]);
		$num = $num / $divisor[$i];
		// check for 1100 .. 1999, 2100..2999, ... 5200..5999
		// but not 1000..1099,  2000..2099, ...
		// Special case written as fifty-nine hundred.
		// e.g. thousands digit is 1..5 and hundreds digit is 1..9
		// Only when no further higher order.
		if ( $i == 1 /* doing hundreds */ && 1 <= $num && $num <= 5 ){
			if ( $remdr > 0 ){
				$remdr = ($num * 10);
				$num = 0;
			} // end if
		} // end if
		if ( $remdr == 0 ){
			continue;
		}
		$t = "";
		if ( $remdr < 20 ){
			$t = $lowName[$remdr];
		}
		else if ( $remdr < 100 ){
			$units = (int)$remdr % 10;
			$tens = (int)$remdr / 10;
			$t = $tys [$tens];
			if ( $units != 0 ){
				$t .= "-" . $lowName[$units];
			}
		}else {
			$t = num2words($remdr, 0);
		}
		$s = $t." ".$groupName[$i]." ".$s;
		$num = (int)$num;
	} // end for
	$s = trim($s);
	if ( $negative ){
		$s = $MINUS . " " . $s;
	}

	// SCRIPT ASLINYA  if ($c == 1) $s .= " and $cents/100";
	if ($c == 1) $s .= " cents";

	return $s;
} // end num2words
//DOLLAR KOMA//
//RUPIAH//
function mametbilang($x) {
    $x = abs($x);
    $angka = array("", "satu", "dua", "tiga", "empat", "lima","enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
    $temp = "";
    if ($x <12)
	{	$temp = " ". $angka[$x];	}
	else if ($x <20) 				{	$temp = mametbilang($x - 10). " belas";	}
	else if ($x <100) 				{	$temp = mametbilang($x/10)." puluh". mametbilang($x % 10);	}
	else if ($x <200) 				{	$temp = " seratus" . mametbilang($x - 100);	}
	else if ($x <1000) 				{	$temp = mametbilang($x/100) . " ratus" . mametbilang($x % 100);	}
	else if ($x <2000) 				{	$temp = " seribu" . mametbilang($x - 1000);	}
	else if ($x <1000000) 			{	$temp = mametbilang($x/1000) . " ribu" . mametbilang($x % 1000);	}
	else if ($x <1000000000) 		{	$temp = mametbilang($x/1000000) . " juta" . mametbilang($x % 1000000);	}
	else if ($x <1000000000000) 	{	$temp = mametbilang($x/1000000000) . " milyar" . mametbilang(fmod($x,1000000000));	}
	else if ($x <1000000000000000) 	{	$temp = mametbilang($x/1000000000000) . " trilyun" . mametbilang(fmod($x,1000000000000));	}
	return $temp;
}
//RUPIAH//

function initCalendar(){
        	$teks = <<<HEREDOC
    			<style type="text/css">@import url("javascript/jscalendar/calendar.css");</style>
    			<script src="javascript/jscalendar/calendar.js" type="text/javascript"></script>
    			<script src="javascript/jscalendar/lang/calendar-id.js" type="text/javascript"></script>
    			<script src="javascript/jscalendar/calendar-setup.js" type="text/javascript"></script>
HEREDOC;
       return $teks;
}
function calendarBox($id = 'tanggal', $button = 'trigger', $default = '', $str = '<img border="0" src="../image/b_calendar.png" width="10" height="10">', $act=''){
          $teks = '';
	      $teks .= "\t<input type=\"text\" id=\"$id\" name=\"$id\" \"$act\" value=\"$default\" size=\"14\"  />\n";
	      $teks .= "\t<button id=\"$button\">$str</button>\n";
	      $teks .= "\t<script type=\"text/javascript\">\n";
	      $teks .= "\t\tCalendar.setup({inputField: \"$id\", ifFormat: \"%Y-%m-%d\", button: \"$button\"});\n";
	      $teks .= "\t</script>\n";
	      return $teks;
    	}

function _convertDate($date)
{
    if (empty($date))
        return null;

    $date = explode("-", $date);
    return
    $date[2] . '-' . $date[1] . '-' . $date[0];
}
function viewBulan($date)
{
$bulan=array("01"=>"Jan","02"=>"Feb","03"=>"Mrc","04"=>"Apr","05"=>"May","06"=>"Jun",
             "07"=>"Jul","08"=>"Agst","09"=>"Sep","10"=>"Oct","11"=>"Nov","12"=>"Desc");
	if (empty($date))
        return null;

    $date = explode("-", $date);
    $buln=$bulan[$date[1]];
    return
    $date[2] . '-' . $buln . '-' . $date[0];
}
function arrayCombine($a = array(), $b = array())
{
    foreach($a as $key => $value) {
        $c[$value] = $b[$key];
    }
    return $c;
}
function connect()
{
    $GLOBALS["connect"] = @mysql_connect(hostname, username, password) or die ("Can't connect to database" . mysql_error());
    mysql_select_db(dbname);
} //function connect
function query($command)
{
    if (!isset($GLOBALS["connect"])) {
        connect();
    } // if
    if ($_REQUEST['debug'] == 1) {
        // $query = ('INSERT INTO logs (username, waktu, operation, PC) VALUES("' . $_SESSION['nama'] . '","' . date('D,d F Y  H:i:s') . '", "' . $command . '", "' . $_SERVER['REMOTE_ADDR'] . '")');
        echo '<pre>' . $command . '<br>' . $query . '</pre>';
    }
    if (strtoupper(substr($command, 0, 6)) != 'SELECT') {
        // $query = mysql_query('INSERT INTO logs (username, waktu, operation, PC) VALUES("' . $_SESSION['nama'] . '","' . date('D,d F Y  H:i:s') . '","' . $command . '","' . $_SERVER['REMOTE_ADDR'] . '")');
        // echo mysql_error();
        // exit;
    }

    $query = mysql_query($command);
    return $query;
} //function query
function back()
{
    return "<a href=\"javascript:history.back(1)\"><img src=\"image/Backward-64.png\" width=\"20\"></a>";
}
function alerte()
{
    ob_start('ob_tidyhandler');
    echo '<script language="JavaScript"> window.location=\'' . $_ENV['HTTP_REFERER'] . '\' window.alert(\'Anda tidak berhak melakukan operasi ini !\'); </script>';
    ob_end_flush();
}
function showHead()
{
    return '
<link href="themes/' . theme . '/styles/style.css" rel="stylesheet" type="text/css">
<script src="includes/js/TreeView/ua.js" type="text/javascript"></script>
<script src="includes/js/TreeView/ftiens4.js" type="text/javascript"></script>
<script src="includes/js/TreeView/links.js" type="text/javascript"></script>

';
}

function createPageNavigations($file = '', $total = 0, $psDeh = 10 , $anchor = '', $perPage = 12)
{
    $tmp = '<table align="center" cellpadding="0" cellspacing="0">
			<tr><td>';
    $perPage == 0 ? $rowPage = rowsPerPage : $rowPage = $perPage;
    $pages = '';
    $m = 0;
    strpos($file, '?') ? $file = explode('?', $file) : $file[0] = $file;

    $_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
    $_REQUEST['sp'] ? $ps = $_REQUEST['sp'] : $ps = 1;
    $anchor == '' ? $anchor = '' : $anchor = '#' . $anchor;

    if ($ps == 1) {
        $prev = '';
        $end = '<a href="' . $file[0] . '?sp=' . ($ps + 1) . '&x=11' . $anchor . '">' . _NEXT . ' </a>';
    } else {
        $prev = '<a href="' . $file[0] . '?sp=1&x=1&' . $file[1] . $anchor . '">1 ... </a>&nbsp;
				<a href="' . $file[0] . '?sp=' . ($ps-1) . '&x=' . (($ps-1) * $psDeh - $psDeh) . '&' . $file[1] . $anchor . '">' . _PREV . '</a> |';
    }

    if ($ps < ceil($total / $rowPage / $psDeh)) {
        $end = '<a href="' . $file[0] . '?sp=' . ($ps + 1) . '&x=' . ($ps * $psDeh) . '&' . $file[1] . $anchor . '">' . _NEXT . '</a>...
				 <a href="' . $file[0] . '?sp=' . (ceil($total / $rowPage / $psDeh)) . '&x=' . (ceil($total / $rowPage)) . '&' . $file[1] . $anchor . '">' . ceil($total / $rowPage) . '</a>';
    } else {
        $end = '';
    }

    for($i = ($ps-1) * 10 ; $i <= (($ps-1) * 10) + 10 && $i <= ceil($total / $rowPage); $i++) {
        if ($i <> 0) {
            if ($i == $pageNow) {
                $pages .= '<span style="background-color: #AAAAFF; font-weight: bold;">' . $i . '</span> | ';
            } else {
                $pages .= '<a href="' . $file[0] . '?x=' . $i . '&sp=' . $ps . '&' . $file[1] . $anchor . '">' . $i . '</a> | ';
            }
        }
    } // for
    // initialization gitu loh
    $tmp .= $prev . $pages . $end;
    $tmp .= '</td></tr></table>';
    return $tmp;
}
function rowClass($i, $j = 0)
{
    if ($i % 2 == 1) {
        $clash = "tableentry1";
    } else {
        $clash = "tableentry2";
    }
    if ($j == 1) {
        if ($i % 2 == 1) {
            $clash = "#FEFEFE";
        } else {
            $clash = "#EAF7FF";
        }
    }
    return $clash;
}
function pilih($data, $value){

		if ($data == $value)
			return 'checked';
		return;
}
function _selected($x, $y){
		return ($x==$y)?'selected':'';
}
function duit($value)
{
    $orro = number_format($value, 0, ',', '.');
    return $orro;
}
function duitkoma($value)
{
	$orro = number_format($value, 2, ',', '.');
	return $orro;
}
function duittanpakoma($value)
{
	$orro = number_format($value, 0, '.', '.');
	return $orro;
}
function duitdollar($amount) {
  return number_format($amount,2,',','.');
}
/*
function duitdollar($number) {
   if ($number < 0) {
     $print_number = "( " . str_replace('-', '', number_format ($number, 2, ".", ",")) . ")";
    } else {
     $print_number = " " .  number_format ($number, 0, ".", ",") ;
   }
   return $print_number;
}
*/
function generateHTML($value, $queryResult, $op = 'view' , $name = '', $editable = '')
{
    if ($op == 'edit') {
        switch ($value['type']) {
            case 'date' :
                if ($value['format']) {
                    $temp = createDateSelector($value['field'] . $name, 15, date($value['format'], strtotime($queryResult[$value['field']])));
                } else {
                    $temp = createDateSelector($value['field'] . $name, 15, $queryResult[$value['field']]);
                }

                break;
            case 'none' :
                $temp = '<' . $value['view'] . '>' . $queryResult[$value['field']] . '</' . $value['view'] . '> <span style="font-size:8.5px;">' . $value['add'] . '</span>';
                break;
            case 'radio' :
                foreach($value['value'] as $key => $i) {
                    if ($queryResult[$value['field']] == $key) {
                        $option .= '<input name="' . $value['field'] . $name . '" id="' . $value['field'] . $name . '" type="radio" value="' . $key . '" checked onClick="' . $value['event'] . '" "' . $editable . '">' . $i . ' &nbsp; ';
                    } else {
                        $option .= '<input name="' . $value['field'] . $name . '" id="' . $value['field'] . $name . '"  type="radio" value="' . $key . '" onClick="' . $value['event'] . '" "' . $editable . '">' . $i . ' &nbsp; ';
                    }
                }

                $temp = $option . '<span style="font-size:8.5px;">' . $value['add'] . '</span>';
                break;
            case 'select' :
                foreach($value['value'] as $key => $i) {
                    if ($queryResult[$value['field']] == $key) {
                        $option .= '<option value="' . $key . '" selected>' . $i . '</option>';
                    } else {
                        $option .= '<option value="' . $key . '">' . $i . '</option>';
                    }
                }
                $temp = '<select name="' . $value['field'] . $name . '" id="' . $value['field'] . $name . '" ' . $value['on'] . '>' . $option . '</select> <span style="font-size:8.5px;">' . $value['add'] . '</span>';
                break;
            // nyelipin dikit aah !:D
            case 'file' :
                $temp = '<input type="file" name="' . $value['field'] . $name . '" value="' . $queryResult[$value['field'] . $name] . '" size="' . $value['size'] . '" id="' . $value['field'] . $name . '" "' . $editable . '" > <span style="font-size:8.5px;">' . $value['add'] . '</span>';
                break;
            default :
            case 'text' :

                $temp = '<input type="text" name="' . $value['field'] . $name . '" value="' . $queryResult[$value['field'] . $name] . '" size="' . $value['size'] . '" id="' . $value['field'] . $name . '" ' . $value[onEvent] . '> ';
                if ($value['add']) {
                    $temp .= '<span style="font-size:8.5px;">' . $value['add'] . '</span>';
                }

                break;

            case 'password' :

                $temp = '<input type="password" name="' . $value['field'] . $name . '" size="' . $value['size'] . '" id="' . $value['field'] . $name . '" ' . $value[onEvent] . '> <span style="font-size:8.5px;">' . $value['add'] . '</span>';

                break;

            case 'textarea':
                $temp = '<textarea name="' . $value['field'] . $name . '" cols="' . $value['size'] . '">' . $queryResult[$value['field']] . '</textarea> ' . $value['add'] . '<span style="font-size:8.5px;">' . $value['add'] . '</span>';
                break;

            case 'hide' :
                $temp = $queryResult[$value['field']];
                break;
            case 'checkbox' :
                foreach($value['value'] as $key => $i) {
                    if ($queryResult[$value['field']] == $key) {
                        $option .= $value['add'] . '<div id="' . $key . '"><input name="' . $key . $name . '"   type="checkbox" value="' . $key . '" checked onClick="' . $value['event'] . '" "' . $editable . '">' . $i . $value['addc'] . '</div>';
                    } else {
                        $option .= $value['add'] . '<div id="' . $key . '"><input name="' . $key . $name . '"    type="checkbox" value="' . $key . '" onClick="' . $value['event'] . '" "' . $editable . '">' . $i . $value['addc'] . '</div>';
                    }
                }

                $temp = $option;
                break;
        } // switch
    } else {
        if (is_array($value)) {
            if (array_key_exists('view', $value)) {
                $temp = '<' . $value['view'] . '>' . $queryResult[$value['field']] . '</' . $value['view'] . '> ' . $value['add'];
            } elseif ($value['type'] == 'date') {
                if ($queryResult[$value['field']]) {
                    $temp = dateFormat($queryResult[$value['field']]) ;
                } else {
                    $temp = '' ;
                }
            } elseif ($value['sign']) {
                $temp = $value['sign'] . ' ' . number_format($queryResult[$value['field'] . $name], '', ',', '.') . ' ' . $value['add'];
            } else {
                // $value['add'] != ''? $temp = '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td align="right" valign="bottom" width="50%">' . $queryResult[$value['field'] . $name] . '&nbsp;</td><td valign="bottom">&nbsp;' . $value['add'] . '</tr></table>' : $temp = $queryResult[$value['field'] . $name];
            }
        } else {
            $temp = $queryResult[$value];
        }
    }
    return $temp;
}
# //---------------------------------------------------------------------------------------
# ////////////////////////////////////////////
# //  PAGINATION FUNCTION  //
# //  by: Karl Steltenpohl          //
# ////////////////////////////////////////////
function pagination($table, $order, $searchstring, $pre, $pos, $nav, $page, $pages)
 {
 ///////////////////////
 //  Get Current Url  //
 ///////////////////////
 $webpage = basename($_SERVER['PHP_SELF']);
 global $webpage;

 ////////////////////////
 //  Sorter and Pagination Query Begin  //
 /////////////////////////////////////////
 //$pre = $_REQUEST['pre'];
 //$pos = $_REQUEST['pos'];
 //$nav = $_REQUEST['nav'];
 //$page = $_REQUEST['page'];
 //$pages = $_REQUEST['pages'];


 ///////////////////////////////////////////
 //  Set Initial Pre Pos and Page Limits  //
 ///////////////////////////////////////////
 if($pre == "" and $pos == "" and $page == "")
 {
 $pre = 0;
 $pos = 9;
 $page = 1;
 }


 ///////////////////////////////
 //  User Navigates Previous  //
 ///////////////////////////////
 if($nav == "prev")
 {
 $pre = ($pre - 10);
 $pos = ($pos - 10);
 $page = ($page - 1);
 }


 ///////////////////////////
 //  User Navigates Next  //
 ///////////////////////////
 if($nav == "next")
 {
 $pre = ($pre + 10);
 $pos = ($pos + 10);
 $page = ($page + 1);
 }


 /////////////////////////////
 //  If page number to low  //
 /////////////////////////////
 if($page < 1)
 {
 $pre = 0;
 $pos = 9;
 $page = 1;
 }

 //////////////////////////////
 //  If page number to high  //
 //////////////////////////////
 if($page > $pages)
 {
 $pre = 0;
 $pos = 9;
 $page = 1;
 }


 //////////////////////////////////////////
 //  Select for total number or results  //
 //////////////////////////////////////////
 $r = "SELECT DISTINCT * FROM $table $searchstring";
 $re = mysql_query($r) or die("error 12547");
 $nums = mysql_num_rows($re);


 ////////////////////////////////////////////
 //  Select for current displayed results  //
 ////////////////////////////////////////////
 $request = "SELECT DISTINCT * FROM $table $searchstring ORDER BY $order DESC LIMIT $pre, 10";
 $result = mysql_query($request) or die("error 25352");
 $num = mysql_num_rows($result);


 ///////////////////////////////////////
 //  Determine total number of pages  //
 ///////////////////////////////////////
 $pages = ceil($nums/10);


 /////////////////////////////////
 //  Create Navigation Display  //
 /////////////////////////////////
 $navigation_old = "
 $nums entries on $pages Page(s)<br>
 <a href=\"$webpage?page=$page&nav=prev&pre=$pre&pos=$pos&pages=$pages&view=view\">Previous</a> |
 Page $page |
 <a href=\"$webpage?page=$page&nav=next&pre=$pre&pos=$pos&pages=$pages&view=view\">Next</a><br>
 Results $pre
 ";

 $navigation = "
 $nums Record on $pages Page(s)<br>
 <a href=\"$webpage?page=$page&nav=prev&pre=$pre&pos=$pos&pages=$pages&view=view\">Previous</a> |
 Page $page |
 <a href=\"$webpage?page=$page&nav=next&pre=$pre&pos=$pos&pages=$pages&view=view\">Next</a><br>
 Results $request
 ";
 //Results $pre - $pos
 /////////////////////////////////
 //  Create Paginagtion Array   //
 /////////////////////////////////
 // result is the result of the limited query
 $pagination = array($navigation, $result, $num, $pre);


 /////////////////////////////////
 //  Return Paginagtion Array   //
 /////////////////////////////////
 return $pagination;
 }//end function

function gambar_kecil($direktori,$file_type){
	list($width,$height)= getimagesize($direktori);

	$max_width = 75; // lebar maksimal
	$max_height = 75; // tinggi maksimal


	if($width>$max_width){
		$scale = (float)$max_width/(float)$width;
		$new_width = (int) $width*$scale;
		$new_height = (int) $height*$scale;
	}


	if($height>$max_height){
		$scale = (float)$max_height/(float)$height;
		$new_width = (int) $width*$scale;
		$new_height = (int) $height*$scale;
	}


	if($width<2){
		$new_width = 2;
	}
	if($height<2){
		$new_height = 2;
	}

	//---------- memeriksa tipe file --------------//
	if(($file_type=="image/jpg")or ($file_type=="image/pjpeg")or($file_type=="image/jpeg")){
		$src_img = imagecreatefromjpeg($direktori);
	}
	if(($file_type=="image/png")or($file_type=="image/x-png")){
		$src_img = imagecreatefrompng($direktori);
	}
	if($file_type=="image/gif"){
		$src_img = imagecreatefromgif($direktori);
	}



	$image_p = imagecreatetruecolor($new_width, $new_height);
	imagecopyresized($image_p, $src_img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);


	if(($file_type=="image/jpg")or ($file_type=="image/pjpeg")or($file_type=="image/jpeg")){
		imagejpeg($image_p, $direktori);
	}
	if(($file_type=="image/png")or($file_type=="image/x-png")){
		imagepng($image_p, $direktori);
	}
	if($file_type=="image/gif"){
		imagegif($image_p, $direktori);
	}


}

//HURUF VOKAL DAN KONSONAN
function countConsonant($strString = "")
{
	$strBuffer = strtolower(preg_replace('/s-/', '', $strString));
	$intLen = strlen($strBuffer);
	$arrVowel = array("a", "i", "u", "e", "o");
	$intConsonant = 0;
	for ($i = 0; $i <= $intLen - 1; $i++)
	{
		if(!in_array($strBuffer[$i], $arrVowel))
		{
			$intConsonant++;
		}
	}
	return $intConsonant;
}
function countVowel($strString = "")
{
	$strBuffer = strtolower(preg_replace('/s-/', '', $strString));
	$intLen = strlen($strBuffer);
	$intVowel = 0;
	$arrVowel = array("a", "i", "u", "e", "o");
	for ($i = 0; $i <= $intLen - 1; $i++)
	{
		if (in_array($strBuffer[$i], $arrVowel))
		{
			$intVowel++;
		}
	}
	return $intVowel;
}
function countLetter($strString = "")
{
	return strlen(preg_replace('/s-/', '', $strString));
}
//HURUF VOKAL DAN KONSONAN

function createRandomPassword () {
	$chars = "abcdefghijkmnopqrstuvwxyz023456789" ;
	srand ((double) microtime ()* 1000000 );
	$i = 0 ;
	$pass = '' ;
while ( $i <= 8 ) {
	$num = rand () % 33 ;
	$tmp = substr ( $chars , $num , 1 );
	$pass = $pass . $tmp ;
	$i ++;
}
	return $pass ;
}

function smallRandomPassword () {
	$chars = "abcdefghijkmnopqrstuvwxyz023456789" ;
	srand ((double) microtime ()* 1000000 );
	$i = 0 ;
	$pass = '' ;
	while ( $i <= 5 ) {
		$num = rand () % 33 ;
		$tmp = substr ( $chars , $num , 1 );
		$pass = $pass . $tmp ;
		$i ++;
	}
	return $pass ;
}


function datediff($time1, $time2, $precision = 6) {
// If not numeric then convert texts to unix timestamps
if (!is_int($time1)) {	$time1 = strtotime($time1);	}
if (!is_int($time2)) {	$time2 = strtotime($time2);	}

// If time1 is bigger than time2
// Then swap time1 and time2
if ($time1 > $time2) {	$ttime = $time1;
						$time1 = $time2;
						$time2 = $ttime;
					}

// Set up intervals and diffs arrays
$intervals = array('year','month','day','hour','minute','second');
$diffs = array();

// Loop thru all intervals
foreach ($intervals as $interval) {
	// Create temp time from time1 and interval
	$ttime = strtotime('+1 ' . $interval, $time1);
	// Set initial values
	$add = 1;
	$looped = 0;
	// Loop until temp time is smaller than time2
	while ($time2 >= $ttime) {
		// Create new temp time from time1 and interval
		$add++;
		$ttime = strtotime("+" . $add . " " . $interval, $time1);
		$looped++;
	}

	$time1 = strtotime("+" . $looped . " " . $interval, $time1);
	$diffs[$interval] = $looped;
}

$count = 0;
$times = array();
// Loop thru all diffs
foreach ($diffs as $interval => $value) {
	// Break if we have needed precission
	if ($count >= $precision) {
		break;
	}
	// Add value and interval
	// if value is bigger than 0
	if ($value >= 0) {
		// Add s if value is not 1
		if ($value != 1) {
			$interval .= "s";
		}
		// Add value and interval to times array
		//$times[] = $value . " " . $interval;	// DEFAULT
		$times[] = $value;
		$count++;
	}
}

// Return string with times
//return implode(", ", $times);	// DEFAULT
return implode(",", $times);
}

//hitung jumlah hari//
function daysBetween($s, $e)
{
	$s = strtotime($s);
	$e = strtotime($e);

	return ($e - $s)/ (24 *3600);
}
//hitung jumlah hari//

function KonDecRomawi($angka){
	$hsl = "";
	if($angka<1||$angka>3999){
		$hsl = "Batas Angka 1 s/d 3999";
	}else{
		while($angka>=1000){
			$hsl .= "M";
			$angka -= 1000;
		}
		if($angka>=500){
			if($angka>500){
				if($angka>=900){
					$hsl .= "CM";
					$angka-=900;
				}else{
					$hsl .= "D";
					$angka-=500;
				}
			}
		}
		while($angka>=100){
			if($angka>=400){
				$hsl .= "CD";
				$angka-=400;
			}else{
				$angka-=100;
			}
		}
		if($angka>=50){
			if($angka>=90){
				$hsl .= "XC";
				$angka-=90;
			}else{
				$hsl .= "L";
				$angka-=50;
			}
		}
		while($angka>=10){
			if($angka>=40){
				$hsl .= "XL";
				$angka-=40;
			}else{
				$hsl .= "X";
				$angka-=10;
			}
		}
		if($angka>=5){
			if($angka==9){
				$hsl .= "IX";
				$angka-=9;
			}else{
				$hsl .= "V";
				$angka-=5;
			}
		}
		while($angka>=1){
			if($angka==4){
				$hsl .= "IV";
				$angka-=4;
			}else{
				$hsl .= "I";
				$angka-=1;
			}
		}
	}
	return ($hsl);
}

function bulan($bln){
	$bulan = $bln;
Switch ($bulan){
	case 1 : $bulan="Januari";
		Break;
	case 2 : $bulan="Februari";
		Break;
	case 3 : $bulan="Maret";
		Break;
	case 4 : $bulan="April";
		Break;
	case 5 : $bulan="Mei";
		Break;
	case 6 : $bulan="Juni";
		Break;
	case 7 : $bulan="Juli";
		Break;
	case 8 : $bulan="Agustus";
		Break;
	case 9 : $bulan="September";
		Break;
	case 10 : $bulan="Oktober";
		Break;
	case 11 : $bulan="November";
		Break;
	case 12 : $bulan="Desember";
		Break;
}
	return $bulan;
}

//RANDOM STRING
function MetRandom($muncul){
	if($muncul == '3'){
		$ryRandom = rand(111,256); //*Acak angka 111 - 999 menampilkan 3 angka<br />
	}elseif($muncul == '2'){
		$ryRandom = rand(11,99); //* menampilkan 2 angka
	}else{
		$ryRandom = "Random belum di setting";
	}
	return $ryRandom;
}

$dateY = date("Y");
$datelog = date("Y-m-d");
$futgl = date("Y-m-d H:i:s");
$futoday  = date("Y-m-d");
$futgldn = date("d/m/Y");
$timelog = date("G:i:s");
$alamat_ip = $_SERVER['REMOTE_ADDR'];
$nama_host = gethostbyaddr($_SERVER['REMOTE_ADDR']);
$useragent = $_SERVER ['HTTP_USER_AGENT'];
$referrer = getenv('HTTP_REFERER');
?>