//This script is used to validate reasonable date entry
//for Speednet reports.

function check_date(field,flg){
var checkstr = "0123456789";
var DateField = field;
var Datevalue = "";
var DateTemp = "";
var seperator = ".";
var day;
var month;
var year;
var leap = 0;
var err = "";
var xlg = 0;
var i;
   err = 0;
   DateValue = field;
   xlg = flg;
   /* Delete all chars except 0..9 */
   for (i = 0; i < DateValue.length; i++) {
	  if (checkstr.indexOf(DateValue.substr(i,1)) >= 0) {
	     DateTemp = DateTemp + DateValue.substr(i,1);
	  }
   }
   DateValue = DateTemp;
   /* Always change date to 8 digits - string*/
   /* if year is entered as 2-digit / always assume 20xx */
   if (DateValue.length == 6) {
      DateValue = DateValue.substr(0,4) + '20' + DateValue.substr(4,2); }
   if (DateValue.length != 8) {
      err = "Length != 8";}
   /* year is wrong if year = 0000 */
   year = DateValue.substr(4,4);
   if (year == 0) {
      err = "Year is zero";
   }
   /* Validation of month*/
   month = DateValue.substr(2,2);
   if ((month < 1) || (month > 12)) {
      err = "Month not between 1 & 12";
   }
   /* Validation of day*/
   day = DateValue.substr(0,2);
   if (day < 1) {
     err = "Day less than 1";
   }
   /* Validation leap-year / february / day */
   if ((year % 4 == 0) || (year % 100 == 0) || (year % 400 == 0)) {
      leap = 1;
   }
   if ((month == 2) && (leap == 1) && (day > 29)) {
      err = "Leap year, but >(0229)";
   }
   if ((month == 2) && (leap != 1) && (day > 28)) {
      err = "Not leap year, but >(0228)";
   }
   /* Validation of other months */
   if ((day > 31) && ((month == "01") || (month == "03") || (month == "05") || (month == "07") || (month == "08") || (month == "10") || (month == "12"))) {
      err = "Day > 31 in wrong month";
   }
   if ((day > 30) && ((month == "04") || (month == "06") || (month == "09") || (month == "11"))) {
      err = "Day > 30 in wrong month";
   }
   /* if 00 ist entered, no error, deleting the entry */
   if ((day == 0) && (month == 0) && (year == 00)) {
      err = "Date is all zero";
      day = ""; month = ""; year = ""; seperator = "";
   }
   /* if no error, write the completed date to Input-Field (e.g. 13.12.2001) */
   if (!err) {
      return true;
   }
   /* Error-message if err != NULL */
   else {
      DateField = month + "/" + day + "/" + year;
      alert(xlg + " date is incorrect! " + DateField + "\n" + err);
      return false;
   }
}
