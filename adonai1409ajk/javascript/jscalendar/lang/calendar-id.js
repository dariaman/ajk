// ** I18N

// Calendar ID language (bahasa Indonesia)
// Author: T. Budiman, <tbudiman@malaka9.com>
// Encoding: any
// Distributed under the same terms as the calendar itself.

// For translators: please use UTF-8 if possible.  We strongly believe that
// Unicode is the answer to a real internationalized world.  Also please
// include your contact information in the header, as can be seen above.

// full day names
Calendar._DN = new Array
("Minggu",
 "Senin",
 "Selasa",
 "Rabu",
 "Kamis",
 "Jumat",
 "Sabtu",
 "Minggu");

// Please note that the following array of short day names (and the same goes
// for short month names, _SMN) isn't absolutely necessary.  We give it here
// for exemplification on how one can customize the short day names, but if
// they are simply the first N letters of the full name you can simply say:
//
//   Calendar._SDN_len = N; // short day name length
//   Calendar._SMN_len = N; // short month name length
//
// If N = 3 then this is not needed either since we assume a value of 3 if not
// present, to be compatible with translation files that were written before
// this feature.

// short day names
Calendar._SDN = new Array
("Mng",
 "Sen",
 "Sel",
 "Rab",
 "Kam",
 "Jum",
 "Sab",
 "Mng");

// full month names
Calendar._MN = new Array
("Januari",
 "Februari",
 "Maret",
 "April",
 "Mei",
 "Juni",
 "Juli",
 "Agustus",
 "September",
 "Oktober",
 "November",
 "Desember");

// short month names
Calendar._SMN = new Array
("Jan",
 "Feb",
 "Mar",
 "Apr",
 "Mei",
 "Jun",
 "Jul",
 "Agu",
 "Sep",
 "Okt",
 "Nov",
 "Des");

// tooltips
Calendar._TT = {};
Calendar._TT["INFO"] = "Tentang kalender";

Calendar._TT["ABOUT"] =
"DHTML Date/Time Selector\n" +
"(c) dynarch.com 2002-2003\n" + // don't translate this this ;-)
"Kunjungi versi terbaru di: http://dynarch.com/mishoo/calendar.epl\n" +
"Didistribusikan dalam GNU LGPL.  Lihat http://gnu.org/licenses/lgpl.html untuk detilnya." +
"\n\n" +
"Pilihan tanggal:\n" +
"- Gunakan tombol \xab, \xbb untuk memilih tahun\n" +
"- Gunakan tombol " + String.fromCharCode(0x2039) + ", " + String.fromCharCode(0x203a) + " untuk memilih bulan\n" +
"- Tahan tombol mouse di salah satu tombol di atas untuk memilih dengan lebih cepat.";
Calendar._TT["ABOUT_TIME"] = "\n\n" +
"Pilihan waktu:\n" +
"- Klik bagian waktu untuk menambah\n" +
"- atau tahan tombol Shift lalu klik bagian waktu untuk menguranginya\n" +
"- atau klik dan drag untuk memilih dengan lebih cepat.";

Calendar._TT["PREV_YEAR"] = "Tahun lalu (tahan untuk menu)";
Calendar._TT["PREV_MONTH"] = "Bulan lalu (tahan untuk menu)";
Calendar._TT["GO_TODAY"] = "Hari ini";
Calendar._TT["NEXT_MONTH"] = "Bulan depan (tahan untuk menu)";
Calendar._TT["NEXT_YEAR"] = "Tahun depan (tahan untuk menu)";
Calendar._TT["SEL_DATE"] = "Pilih tanggal";
Calendar._TT["DRAG_TO_MOVE"] = "Drag untuk memindahkan";
Calendar._TT["PART_TODAY"] = " (hari ini)";

// the following is to inform that "%s" is to be the first day of week
// %s will be replaced with the day name.
Calendar._TT["DAY_FIRST"] = "Tampilkan Hari %s di awal minggu";

// This may be locale-dependent.  It specifies the week-end days, as an array
// of comma-separated numbers.  The numbers are from 0 to 6: 0 means Sunday, 1
// means Monday, etc.
Calendar._TT["WEEKEND"] = "0,6";

Calendar._TT["CLOSE"] = "Tutup";
Calendar._TT["TODAY"] = "Hari ini";
Calendar._TT["TIME_PART"] = "(Shift-)Klik atau drag untuk mengubah nilai";

// date formats
Calendar._TT["DEF_DATE_FORMAT"] = "%d-%m-%Y";
Calendar._TT["TT_DATE_FORMAT"] = "%a, %e %b";

Calendar._TT["WK"] = "mg";
Calendar._TT["TIME"] = "Pukul:";
