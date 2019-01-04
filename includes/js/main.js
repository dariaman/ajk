var calendar = null;
function selected(cal, date) {
	cal.sel.value = date;
}

function closeHandler(cal) {
	cal.hide();
	Calendar.removeEvent(document, "mousedown", checkCalendar);
}

function checkCalendar(ev) {
	var el = Calendar.is_ie ? Calendar.getElement(ev) : Calendar.getTargetElement(ev);
	for (; el != null; el = el.parentNode)
	if (el == calendar.element || el.tagName == "A") break;
	if (el == null) {
		calendar.callCloseHandler(); Calendar.stopEvent(ev);
	}
}

function showCalendar(id) {
	var el = document.getElementById(id);
	if (calendar != null) {
		calendar.hide();
		calendar.parseDate(el.value);
	} else {
		var cal = new Calendar(true, null, selected, closeHandler);
		calendar = cal;
		cal.setRange(1900, 2070);
		calendar.create();
	}
	calendar.sel = el;
	calendar.showAtElement(el);

	Calendar.addEvent(document, "mousedown", checkCalendar);
	return false;
}

function checkDate(fld) {
    var mo, day, yr;
    var entry = fld;
    var re = /\b\d{1,2}[\/-]\d{1,2}[\/-]\d{4}\b/;
    if (re.test(entry)) {
        var delimChar = (entry.indexOf("/") != -1) ? "/" : "-";
        var delim1 = entry.indexOf(delimChar);
        var delim2 = entry.lastIndexOf(delimChar);
        day = parseInt(entry.substring(0, delim1), 10);
        mo = parseInt(entry.substring(delim1+1, delim2), 10);
        yr = parseInt(entry.substring(delim2+1), 10);
        var testDate = new Date(yr, mo-1, day);
//        alert(testDate)
        if (testDate.getDate( ) == day) {
            if (testDate.getMonth( ) + 1 == mo) {
                if (testDate.getFullYear( ) == yr) {
                    return testDate;
                } else {
                    alert("Tahun yang dimasukkan salah");
                }
            } else {
                alert("Bulan yang dimasukkan salah");
            }
        } else {
            alert("Tanggal yang dimasukkan salah");
        }
    } else {
        alert("Format tanggal salah.\n(Gunakan tombol yang tersedia!)");
    }
    return false;
}



function checkDateRange(mulai, selesai){
  if(mulai != '' && selesai != ''){
    if((startDate = checkDate(mulai)) && (endDate = checkDate(selesai))){
//	alert(startDate);
	  if(startDate < endDate){
	    return true;
	  }else{
	  	alert('Tanggal mulai harus lebih dulu dari tanggal selesai');
		return false;
	  }
	 }else{
	  return false;
	  }
  }else{
  	alert('Tentukan tanggal mulai dan selesainya');
	return false;
  }
}

function giveToday(ayyana, obj, resultnya){
	if (obj.checked){
	 resultnya.value = ayyana;
	}else{
	 resultnya.value = '';
	}
}

var querywindow = '';

function popUp(url) {

    if (!querywindow.closed && querywindow.location) {
        querywindow.focus();
    } else {
        querywindow=window.open(url, '','toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=yes,resizable=yes,width=690,height=420');
    }

    if (!querywindow.opener) {
        querywindow.opener = self;
    }

    if (window.focus) {
        querywindow.focus();
    }

    return false;
}

function ubahWarna(me,color){
	me.style.background=color;
	}


//disabled right click ---mamet--- disabled right click//
 var isNS = (navigator.appName == "Netscape") ? 1 : 0;
  if(navigator.appName == "Netscape")
     document.captureEvents(Event.MOUSEDOWN||Event.MOUSEUP);
  function mischandler(){
   return false;
 }
  function mousehandler(e){
 	var myevent = (isNS) ? e : event;
 	var eventbutton = (isNS) ? myevent.which : myevent.button;
    if((eventbutton==2)||(eventbutton==3)) return false;
 }
 document.oncontextmenu = mischandler;
 document.onmousedown = mousehandler;
 document.onmouseup = mousehandler;
//disabled right click ---mamet--- disabled right click//

//DISABLES F5 BUTTON//
var version = navigator.appVersion;
function showKeyCode(e) {
    var keycode = (window.event) ? event.keyCode : e.keyCode;
	if ((version.indexOf('MSIE') != -1)) {
    if (keycode == 116) {
    	event.keyCode = 0;
    	event.returnValue = false;
        return false;
        }
     }
     else {
     	if (keycode == 116) {
        return false;
        }
     }
}
//DISABLES F5 BUTTON//