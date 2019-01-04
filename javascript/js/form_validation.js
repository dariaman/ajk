var xmlhttp = createRequestObject();
function createRequestObject() {
    var ro;
    var browser = navigator.appName;
    if(browser == "Microsoft Internet Explorer"){
        ro = new ActiveXObject("Microsoft.XMLHTTP");
    }else{
        ro = new XMLHttpRequest();
    }
    return ro;
}

function pilrotasi(combobox)
{
    var kode = combobox.value;
    if (!kode) return;
    xmlhttp.open('get', 'template/get_pil_rotasi-opr.php?kode='+kode, true);
    xmlhttp.onreadystatechange = function() {
        if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200))
        {
             document.getElementById("idrotasi").innerHTML = xmlhttp.responseText;
        }
        return false;
    }
    xmlhttp.send(null);
}

function DinamisRegional(combobox)
{
    var kode = combobox.value;
    if (!kode) return;
    xmlhttp.open('get', 'javascript/template/get_area-opr.php?kode='+kode, true);
    xmlhttp.onreadystatechange = function() {
        if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200))
        {
             document.getElementById("area").innerHTML = xmlhttp.responseText;
        }
        return false;
    }
    xmlhttp.send(null);
}

function DinamisArea(combobox)
{
    var kode = combobox.value;
    if (!kode) return;
    xmlhttp.open('get', 'javascript/template/get_cabang-opr.php?kode='+kode, true);
    xmlhttp.onreadystatechange = function() {
        if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200))
        {
             document.getElementById("cabang").innerHTML = xmlhttp.responseText;
        }
        return false;
    }
    xmlhttp.send(null);
}


function Dinamiscabang(combobox)
{
    var kode = combobox.value;
    if (!kode) return;
    xmlhttp.open('get', 'template/get_kecamatan-opr.php?kode='+kode, true);
    xmlhttp.onreadystatechange = function() {
        if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200))
        {
             document.getElementById("kecamatan").innerHTML = xmlhttp.responseText;
        }
        return false;
    }
    xmlhttp.send(null);
}


function searchactive(combobox,aktif)
{
    var kode = combobox.value;
    if (!kode) return;
    xmlhttp.open('get', 'template/get_pil_aktifasi-opr.php?kode='+kode+'&aktif='+aktif, true);
    xmlhttp.onreadystatechange = function() {
        if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200))
        {
             document.getElementById("key").innerHTML = xmlhttp.responseText;
        }
        return false;
    }
    xmlhttp.send(null);
}

function Schedule1(combobox)
{
    var kode = combobox.value;
    if (!kode) return;
    xmlhttp.open('get', 'template/get_airport-opr.php?kode='+kode, true);
    xmlhttp.onreadystatechange = function() {
        if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200))
        {
             document.getElementById("1").innerHTML = xmlhttp.responseText;
        }
        return false;
    }
    xmlhttp.send(null);
}

function Schedule2(combobox)
{
    var kode = combobox.value;
    if (!kode) return;
    xmlhttp.open('get', 'template/get_airport2-opr.php?kode='+kode, true);
    xmlhttp.onreadystatechange = function() {
        if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200))
        {
             document.getElementById("2").innerHTML = xmlhttp.responseText;
        }
        return false;
    }
    xmlhttp.send(null);
}

function Schedule3(combobox)
{
    var kode = combobox.value;
    if (!kode) return;
    xmlhttp.open('get', 'template/get_airport3-opr.php?kode='+kode, true);
    xmlhttp.onreadystatechange = function() {
        if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200))
        {
             document.getElementById("3").innerHTML = xmlhttp.responseText;
        }
        return false;
    }
    xmlhttp.send(null);
}

function Schedule4(combobox)
{
    var kode = combobox.value;
    if (!kode) return;
    xmlhttp.open('get', 'template/get_airport4-opr.php?kode='+kode, true);
    xmlhttp.onreadystatechange = function() {
        if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200))
        {
             document.getElementById("4").innerHTML = xmlhttp.responseText;
        }
        return false;
    }
    xmlhttp.send(null);
}

function Schedule5(combobox)
{
    var kode = combobox.value;
    if (!kode) return;
    xmlhttp.open('get', 'template/get_airport5-opr.php?kode='+kode, true);
    xmlhttp.onreadystatechange = function() {
        if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200))
        {
             document.getElementById("5").innerHTML = xmlhttp.responseText;
        }
        return false;
    }
    xmlhttp.send(null);
}

function searchreportharian(combobox)
{
    var kode = combobox.value;
    if (!kode) return;
    xmlhttp.open('get', 'template/get_pil_report_harian-opr.php?kode='+kode, true);
    xmlhttp.onreadystatechange = function() {
        if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200))
        {
             document.getElementById("key").innerHTML = xmlhttp.responseText;
        }
        return false;
    }
    xmlhttp.send(null);
}

function Sectorschedule(combobox)
{
    var kode = combobox.value;
    if (!kode) return;
    xmlhttp.open('get', 'template/get_sector_schedule-opr.php?kode='+kode, true);
    xmlhttp.onreadystatechange = function() {
        if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200))
        {
             document.getElementById("sector").innerHTML = xmlhttp.responseText;
        }
        return false;
    }
    xmlhttp.send(null);
}

function searchschedule(combobox,aktif,cek,tgl)
{
    var kode = combobox.value;
    if (!kode) return;
    xmlhttp.open('get', 'template/get_pil_schedule-opr.php?kode='+kode+'&aktif='+aktif+'&cek='+cek+'&tgl='+tgl, true);
    xmlhttp.onreadystatechange = function() {
        if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200))
        {
             document.getElementById("key").innerHTML = xmlhttp.responseText;
        }
        return false;
    }
    xmlhttp.send(null);
}

function dinamissector(combobox)
{
    var kode = combobox.value;
    if (!kode) return;
    xmlhttp.open('get', 'template/get_tujuan_sector-opr.php?kode='+kode, true);
    xmlhttp.onreadystatechange = function() {
        if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200))
        {
             document.getElementById("tujsector").innerHTML = xmlhttp.responseText;
        }
        return false;
    }
    xmlhttp.send(null);
}

function Checkiatacust(text)
{
    var kode = text.value;
    if (!kode) return;
    xmlhttp.open('get', 'template/get_iata-opr.php?kode='+kode, true);
    xmlhttp.onreadystatechange = function() {
        if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200))
        {
             document.getElementById("iataresult").innerHTML = xmlhttp.responseText;
        }
        return false;
    }
    xmlhttp.send(null);
}

function Checknomor(text)
{
    var kode = text.value;
    if (!kode) return;
    xmlhttp.open('get', 'template/get_nomor-opr.php?kode='+kode, true);
    xmlhttp.onreadystatechange = function() {
        if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200))
        {
             document.getElementById("cek").innerHTML = xmlhttp.responseText;
        }
        return false;
    }
    xmlhttp.send(null);
}

function Checknomor2(text)
{
    var kode = text.value;
    if (!kode) return;
    xmlhttp.open('get', 'template/get_nomor-opr.php?kode='+kode, true);
    xmlhttp.onreadystatechange = function() {
        if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200))
        {
             document.getElementById("cek2").innerHTML = xmlhttp.responseText;
        }
        return false;
    }
    xmlhttp.send(null);
}

function Checknomor3(text)
{
    var kode = text.value;
    if (!kode) return;
    xmlhttp.open('get', 'template/get_nomor-opr.php?kode='+kode, true);
    xmlhttp.onreadystatechange = function() {
        if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200))
        {
             document.getElementById("cek3").innerHTML = xmlhttp.responseText;
        }
        return false;
    }
    xmlhttp.send(null);
}

function Checknomor4(text)
{
    var kode = text.value;
    if (!kode) return;
    xmlhttp.open('get', 'template/get_nomor-opr.php?kode='+kode, true);
    xmlhttp.onreadystatechange = function() {
        if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200))
        {
             document.getElementById("cek4").innerHTML = xmlhttp.responseText;
        }
        return false;
    }
    xmlhttp.send(null);
}

function Checknomorschedule(text)
{
    var kode = text.value;
    if (!kode) return;
    xmlhttp.open('get', 'template/get_nomorschedule-opr.php?kode='+kode, true);
    xmlhttp.onreadystatechange = function() {
        if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200))
        {
             document.getElementById("cek").innerHTML = xmlhttp.responseText;
        }
        return false;
    }
    xmlhttp.send(null);
}

function ValidPhone(aphone,type)
{
	var valid = "0123456789+()";
 	if(aphone=="")
	{
		alert(type +" number is required field!")
		return false
	}
 	if(aphone.length < 7)
	{
		alert("Invalid "+ type +" number! Please try again.")
		return false
	}
 	for (var i=0; i < aphone.length; i++)
	{
		temp = "" + aphone.substring(i, i+1);
        if (valid.indexOf(temp) == "-1")
		{
			alert("Invalid characters in your "+ type +" number!.  Please try again.")
			return false;
		}
	}
    return true
}

function valid_required(field)
{
	if(field=="")
	{
		return false;
	}
 	return true;
}


function Checktotal(qtty,hrg,returnelement)
{
	var valid = "0123456789";
	var qtty2 = qtty;
 	if(qtty2=="")
	{
		document.getElementById(returnelement).innerHTML = "Qtty is empty!";
		return false
	}
 	if(qtty2.length > 3)
	{
		document.getElementById(returnelement).innerHTML = "Invalid input!";
		return false
	}
 	for (var i=0; i < qtty2.length; i++)
	{
		temp = "" + qtty2.substring(i, i+1);
        if (valid.indexOf(temp) == "-1")
		{
			document.getElementById(returnelement).innerHTML = "Invalid characters";
			return false;
		}
	}
	hasil = qtty2 * hrg;
	document.getElementById(returnelement).innerHTML = hasil;
	return true
}

function total()
{
	var Total = 0;
	var i = 1;
	while(document.getElementById(i)!=null)
	{
		var jum= document.getElementById(i).innerHTML;
		if (isNaN(jum))
		{
			jum=0;
		}
		else
		{
			jum=jum*1;
		}
		Total = Total + jum;
		i=i+1;
	}
	document.getElementById("total").innerHTML = "Rp. " +Total;
}


function cekqty()
{
	var i = 1;
	var jalan = true;
	while(document.getElementById(i)!=null)
	{
		var nama =document.getElementById(i).innerHTML
		var depan = nama.substring(0,1)
		if (depan == "O" || depan == "N" || depan == "D" || depan == "0")
		{
			jalan = false;
			document.getElementById("errornya").innerHTML = "Check QTY!!!";
		}
		i=i+1;
	}
	return jalan;
}

function Checktotalorder(qtty,returnelement)
{
	var valid = "0123456789";
	var qtty2 = qtty;
 	if(qtty2=="")
	{
		document.getElementById(returnelement).innerHTML = "Qtty is empty!";
		return false
	}
 	if(qtty2.length > 3)
	{
		document.getElementById(returnelement).innerHTML = "Invalid input!";
		return false
	}
 	for (var i=0; i < qtty2.length; i++)
	{
		temp = "" + qtty2.substring(i, i+1);
        if (valid.indexOf(temp) == "-1")
		{
			document.getElementById(returnelement).innerHTML = "Invalid characters";
			return false
		}
	}
	document.getElementById(returnelement).innerHTML = qtty2+".00  package" ;
	return true
}

function unit1(combobox)
{
    var kode = combobox.value;
    document.getElementById("unitna").innerHTML =kode;
    return kode;
}

function totalorder()
{
	var Order = 0;
	var i = 1;
	while(document.getElementById(i)!=null)
	{
		var jum= document.getElementById(i).innerHTML;
		jum = jum.substring(0, 3);
		if (isNaN(jum))
		{
			jum=0;
		}
		else
		{
			jum=jum*1;
		}
		Order = Order + jum;
		i=i+1;
	}
	document.getElementById("total").innerHTML = Order+".00 package" ;
}

function viewcontent(combobox,tgl,mono,idmo,kategori,key,hal,idcust,up)
{
    var kode = combobox.value;
    if (!kode) return;
    xmlhttp.open('get', 'template/get_item-cust.php?kode='+kode+'&tgl='+tgl+'&mono='+mono+'&idmo='+idmo+'&kategori='+kategori+'&key='+key+'&hal='+hal+'&idcust='+idcust+'&up='+up, true);
    xmlhttp.onreadystatechange = function() {
        if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200))
        {
             document.getElementById("content").innerHTML = xmlhttp.responseText;
        }
        return false;
    }
    xmlhttp.send(null);
}

function viewcontentSOB(combobox,tgl,mono,idmo,kategori,key,hal,idcust,up)
{
    var kode = combobox.value;
    if (!kode) return;
    xmlhttp.open('get', 'template/get_content_SOB-opr.php?kode='+kode+'&tgl='+tgl+'&mono='+mono+'&idmo='+idmo+'&kategori='+kategori+'&key='+key+'&hal='+hal+'&idcust='+idcust+'&up='+up, true);
    xmlhttp.onreadystatechange = function() {
        if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200))
        {
             document.getElementById("content").innerHTML = xmlhttp.responseText;
        }
        return false;
    }
    xmlhttp.send(null);
}


function viewcontentSOG(combobox,tgl,mono,idmo,kategori,key,hal,idcust,up)
{
    var kode = combobox.value;
    if (!kode) return;
    xmlhttp.open('get', 'template/get_content_SOG-opr.php?kode='+kode+'&tgl='+tgl+'&mono='+mono+'&idmo='+idmo+'&kategori='+kategori+'&key='+key+'&hal='+hal+'&idcust='+idcust+'&up='+up, true);
    xmlhttp.onreadystatechange = function() {
        if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200))
        {
             document.getElementById("content").innerHTML = xmlhttp.responseText;
        }
        return false;
    }
    xmlhttp.send(null);
}

function searchcontent(combobox)
{
    var kode = combobox.value;
    if (!kode) return;
    xmlhttp.open('get', 'template/get_pil_content-cust.php?kode='+kode, true);
    xmlhttp.onreadystatechange = function() {
        if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200))
        {
             document.getElementById("key").innerHTML = xmlhttp.responseText;
        }
        return false;
    }
    xmlhttp.send(null);
}




