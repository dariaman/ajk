<?php
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

function _convertDateInd($date)
{
	if (empty($date))
		return null;

	$date = explode("-", $date);
	if ($date[1]=="01") {	$datebulan = "Januari";	}
	elseif ($date[1]=="02") {	$datebulan = "Februari";	}
	elseif ($date[1]=="03") {	$datebulan = "Maret";	}
	elseif ($date[1]=="04") {	$datebulan = "April";	}
	elseif ($date[1]=="05") {	$datebulan = "Mei";	}
	elseif ($date[1]=="06") {	$datebulan = "Juni";	}
	elseif ($date[1]=="07") {	$datebulan = "Juli";	}
	elseif ($date[1]=="08") {	$datebulan = "Agustus";	}
	elseif ($date[1]=="09") {	$datebulan = "September";	}
	elseif ($date[1]=="10") {	$datebulan = "Oktober";	}
	elseif ($date[1]=="11") {	$datebulan = "November";	}
	else {	$datebulan = "Desember";	}
	return
	$date[2] . ' ' . $datebulan . ' ' . $date[0];
}

function duit($value)
{
    $orro = number_format($value, 0, ',', '.');
    return $orro;
}

function duitdollar($amount) {
	return number_format($amount,2,'.',',');
}

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
?>