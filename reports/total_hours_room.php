<?php
//Created by Hallie Pritchett
//This report shows total hourly room use over a user-specified time period not including cancelled hours;
//the business day starts at 12PM & ends at 2AM
//
//TO DO:
// - form validation
// - have selected year set automatically to current year

/*
 * Additional Notes by Medina
 * -- this is a self-submitting script with name run_report
 * Main Code sections:
 * Set display Block
 * Get Post Vars
 * Query single room
 * Query all rooms
 * 
 */

$DEBUG = false;

//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}

include ("../includes/dbconnect.inc");
include ("../includes/functions.inc");


 if (!isset($_POST['run_report']))
 {
$get_list = "SELECT id, room_name FROM `reservation_room` WHERE room_name <> 'equipment'";
$get_list_res = mysql_query($get_list) or die ("ERROR 1: " . mysql_errno() . "-" . mysql_error() );


$display_block .= "<h2 align=\"left\">Total rental hours by room</h2>";
$display_block .= "<div id=\"links_create\"><a href=\"../admin/index.php\">
		Back to reports index</a>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<!--&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-->
		</div>";
//Note: original div id = dropdown
$display_block .= "<div id=\"col1\" style='text-align:left; position: relative; '>
		<form method=\"post\" name=\"run_report\" action=\"$_SERVER[PHP_SELF]\"
				onsubmit=\"return validate_form();\">";
//Select Granularity {daily, monthly}
$display_block .= "<h3>Select a report granularity</h3>";
$display_block .= "<input type='radio' name='granularity' checked= 'true' value='daily'>Daily</input><br>";
//$display_block .= "<input type='radio' name='granularity' value='monthly'>Monthly</input>";
//start date dropdowns
$display_block .= "<h3>Select a start date</h3>";
$display_block .=
"<select name=\"start_month\">
    <option value=\"0\">Month</option>
	<option value=\"01\">January</option>
	<option value=\"02\">February</option>
	<option value=\"03\">March</option>
	<option value=\"04\">April</option>
	<option value=\"05\">May</option>
	<option value=\"06\">June</option>
	<option value=\"07\">July</option>
	<option value=\"08\">August</option>
	<option value=\"09\">September</option>
	<option value=\"10\">October</option>
	<option value=\"11\">November</option>
	<option value=\"12\">December</option>
</select>
<select name=\"start_day\">
    <option value=\"0\">Day</option>
	<option value=\"01\">1</option>
	<option value=\"02\">2</option>
	<option value=\"03\">3</option>
	<option value=\"04\">4</option>
	<option value=\"05\">5</option>
	<option value=\"06\">6</option>
	<option value=\"07\">7</option>
	<option value=\"08\">8</option>
	<option value=\"09\">9</option>
	<option value=\"10\">10</option>
	<option value=\"11\">11</option>
	<option value=\"12\">12</option>
	<option value=\"13\">13</option>
	<option value=\"14\">14</option>
	<option value=\"15\">15</option>
	<option value=\"16\">16</option>
	<option value=\"17\">17</option>
	<option value=\"18\">18</option>
	<option value=\"19\">19</option>
	<option value=\"20\">20</option>
	<option value=\"21\">21</option>
	<option value=\"22\">22</option>
	<option value=\"23\">23</option>
	<option value=\"24\">24</option>
	<option value=\"25\">25</option>
	<option value=\"26\">26</option>
	<option value=\"27\">27</option>
	<option value=\"28\">28</option>
	<option value=\"29\">29</option>
	<option value=\"30\">30</option>
	<option value=\"31\">31</option>
</select>
<select name=\"start_year\">
    <option value=\"0\">Year</option>";
	for($i = 2009; $i<(date("Y",time())+4); $i++){
			$display_block .= "<option value=\"".$i."\">".$i."</option>";
	}
$display_block .= "</select>";

$display_block .= "<br />";

//end date dropdowns
$display_block .= "<h3>Select an end date</h3>";
$display_block .=
"<select name=\"end_month\">
    <option value=\"0\">Month</option>
	<option value=\"01\">January</option>
	<option value=\"02\">February</option>
	<option value=\"03\">March</option>
	<option value=\"04\">April</option>
	<option value=\"05\">May</option>
	<option value=\"06\">June</option>
	<option value=\"07\">July</option>
	<option value=\"08\">August</option>
	<option value=\"09\">September</option>
	<option value=\"10\">October</option>
	<option value=\"11\">November</option>
	<option value=\"12\">December</option>
</select>
<select name=\"end_day\">
    <option value=\"0\">Day</option>
	<option value=\"01\">1</option>
	<option value=\"02\">2</option>
	<option value=\"03\">3</option>
	<option value=\"04\">4</option>
	<option value=\"05\">5</option>
	<option value=\"06\">6</option>
	<option value=\"07\">7</option>
	<option value=\"08\">8</option>
	<option value=\"09\">9</option>
	<option value=\"10\">10</option>
	<option value=\"11\">11</option>
	<option value=\"12\">12</option>
	<option value=\"13\">13</option>
	<option value=\"14\">14</option>
	<option value=\"15\">15</option>
	<option value=\"16\">16</option>
	<option value=\"17\">17</option>
	<option value=\"18\">18</option>
	<option value=\"19\">19</option>
	<option value=\"20\">20</option>
	<option value=\"21\">21</option>
	<option value=\"22\">22</option>
	<option value=\"23\">23</option>
	<option value=\"24\">24</option>
	<option value=\"25\">25</option>
	<option value=\"26\">26</option>
	<option value=\"27\">27</option>
	<option value=\"28\">28</option>
	<option value=\"29\">29</option>
	<option value=\"30\">30</option>
	<option value=\"31\">31</option>
</select>
<select name=\"start_year\">
    <option value=\"0\">Year</option>";
	for($i = 2009; $i<(date("Y",time())+4); $i++){
			$display_block .= "<option value=\"".$i."\">".$i."</option>";
	}
$display_block .= "</select>";

$display_block .= "<br /></div>";


//Select Room(s), Total
$display_block .= "<div id='col2' style='text-align:left;
		position: relative; top:-290px; left: -30px; width:190px;'>";
$display_block .= "<h3>Select one or more rooms</h3>";
//$display_block .= "<input type ='checkbox' name='roomSet[]' value='1'>Room 7</input><br>";
//$display_block .= "<input type ='checkbox' name='roomSet[]' value='2'>Room 8</input><br>";
//$display_block .= "<input type ='checkbox' name='roomSet[]' value='3'>Room 9</input><br>";
//$display_block .= "<input type ='checkbox' name='roomSet[]' value='4'>Room 10</input><br>";
$display_block .= "<input type ='checkbox' checked='true' name='roomSet[]' value='0'>Total</input><br>";

$display_block .= "</div>
		<div id='col3' style='text-align:left;
		position: relative; top:-350px; left: 250px; width:300px;'>";
//Select data fields
$display_block .= "<h3>Select one or more data fields</h3>";
$display_block .= "
		<input type='checkbox' checked = 'true' name='dataFields[]' value='room_name'>
		Room Name</input><br>
		<input type='checkbox' checked = 'true' name='dataFields[]' value='date'>
		Date</input><br>
		<input type='checkbox' checked = 'true' name='dataFields[]' value='total_hours'>
		Total Hours Scheduled</input><br>
		<input type='checkbox' checked = 'true' name='dataFields[]' value='event_hours'>
		Event Hours</input><br>
		<input type='checkbox' checked = 'true' name='dataFields[]' value='canceled_hours'>
		Total Hours Canceled</input><br>
		<input type='checkbox' checked = 'true' name='dataFields[]' value='lt24_hours'>
		Canceled (owes half)</input><br>
		<input type='checkbox' checked = 'true' name='dataFields[]' value='gt24_hours'>
		Canceled (owes full)</input><br>
		<input type='checkbox' checked = 'true' name='dataFields[]' value='noshow_hours'>
		Canceled (owes nothing)</input><br>
		<input type='checkbox' checked = 'true' name='dataFields[]' value='hours_open'>
		Total Hours Open</input><br>
		<input type='checkbox' checked = 'true' name='dataFields[]' value='total_actual_hours'>
		Total Payable Hours</input><br>
		<input type='checkbox' checked = 'true' name='dataFields[]' value='percent_eff'>
		Percent Efficiency</input><br><br>";

//Submit Button
$display_block .=
            "<input type=\"submit\" name=\"run_report\" value=\"Run report\">
            </form></div>";
/*
 $display_block .= "<select name=\"sel_id\">
         <option value=\"0\">--Select a room--</option>";

         while($recs = mysql_fetch_array($get_list_res))
         {
             //define variables
             $id = $recs['id'];
             $room_name = stripslashes($recs['room_name']);

            $display_block .= "<option value=\"$id\">$room_name</option>";
         }
         $display_block .= "<option value=\"\">Show all rooms</option>";


         $display_block .= "</select><br />";

       $display_block .=  "<br />
            
            <input type=\"submit\" name=\"run_report\" value=\"Run report\">
            </form></div>";
*/
 }

/*////////////////////////////////////////////////////////////////////////////
/* RUN REPORT
/*
 * 1. Get all POST vars. If null, take a guess at what they should be.
 * 2. Generate $date_row array which is a set of tuples {start, end} for 
 *    each row of the data set
 * 3. Main loop: build and submit queries, store results in data sets
 */
//////////////////////////////////////////////////////////////////////////////
if (isset($_POST['run_report']))
{
//GET & VALIDATE POST VARS ///////////////////////////////////////////////////
//TODO: change month and year from string to int??
$granularity = $_POST['granularity'];
//printf("%s\n", $granularity);
//$room_id = $_POST['sel_id'];//Original
$room_id = $_POST['roomSet'];
//$room_id = "";

$start_date = $_POST['start_date'];//do we even use these??
$end_date = $_POST['end_date'];
if($DEBUG){
	printf("Start DATE: %d, End DATE: %d</br>", $start_date, $end_date);
}

//get start month
$start_month = $_POST['start_month'];
if($start_month == 0) $start_month = date(n, time());

//get start day
$start_day = $_POST['start_day'];
if($DEBUG){
	printf("Start DAY: %d, End DAY: %d</br>", $start_day, $_POST['end_day']);
}
if($granularity == "daily"){
	//if($start_day == 0) $start_day = date(j, time());
	if($start_day == 0) $start_day = 1;
}
else $start_day = 1;


//get start year
$start_year = $_POST['start_year'];
if($start_year == 0) $start_year = date(Y, time());//start_year = this year

//get end month
$end_month = $_POST['end_month'];
if($end_month == 0) $end_month = date(n, time());

//get end day
$end_day = $_POST['end_day'];
if($granularity == "daily"){
	//if($end_day == 0) $end_day = date(j, time());
	if($end_day == 0) $end_day = 31;
}
else $end_day = 31;

//get end year
$end_year = $_POST['end_year'];
if($end_year == 0){
		if($start_month > $end_month) $end_year = $start_year+1;
		else $end_year = $start_year;
}

//validate dates
//printf("%d\n", $end_month);
//printf("%d\n", $end_year);
while(!checkdate($start_month, $start_day, $start_year)){
		$start_day--;
		//printf("decreased start day\n");
}
while(!checkdate($end_month, $end_day, $end_year)){
		$end_day--;
		//printf("decreased end day:%d/%d/%d\n",$end_month, $end_day, $end_year );
}

//actual end day is 2AM the next calendar day
$actual_end_day = $end_day+1;
if(!checkdate($end_month, $actual_end_day, $end_year)){
	if($DEBUG){
		printf("Adjusting end_day\n");
		printf("\nend_month=%s; actual_end_day=%s, end_year=%s\n", $end_month, $actual_end_day, $end_year);
	}
	$actual_end_day = 1;
	$end_month += 1;
	if($end_month == 13) $end_month = 1;
	if($end_month == 1) $end_year+=1;
	if(!checkdate($end_month, $actual_end_day, $end_year))
		if($DEBUG){
			printf("Still not Good.\n");
			printf("\nend_month=%s; actual_end_day=%s, end_year=%s\n", $end_month, $actual_end_day, $end_year);
		}
}
//get data fields
$data_fields = $_POST['dataFields'];
//print_r($_POST['dataFields']);

//2. Generate $date_row[] set of {start, end}
$dates;
//$date_row;
/* Given a range of dates, produce an array of utc time tuples */
if($granularity=="daily"){
		//to provide a leap second buffer, day starts at 8am
		$start_time = date_2_utf_date($start_year, $start_month, $start_day, "8", "00");
		//to provide a leap second buffer, day ends at 5am
		$end_time = date_2_utf_date($end_year, $end_month, $actual_end_day, "08", "00");
		$day_start = $start_time;
		$day_end = $day_start+86400;
		$i = 0;
		while($day_start < $end_time){//?? should this be day_end < end_time ??
				$dates[$i]['start']= $day_start;
				$dates[$i]['end'] = $day_end;
				$dates[$i]['start_string']= date("F d, Y: H",$day_start);
				$dates[$i]['end_string']= date("F d, Y: H",$day_end);
				$day_start += 86400;
				$day_end = $day_start + 86400;
				$i++;
		}
		/*
		$d = $start_day;
		$m = $start_month;
		$y = $start_year;
		while(checkdate($m, $d, $y)){
				if(checkdate($m, $d, $y)){
						//$date_row['start'] = getdate($m, $d, $y);
						//$dates[$i] = $date_row;
				}
				else{

				}
		}
*/
		//$date_row['start']=
		//number of days between start and end times
		//$n=0; $m=31;
}//end make dates array for daily granularity

else if($granularity=="monthly"){
		//set start and end boundaries for total range query
		$start_day = $end_day = 1;
		$range_start = date_2_utf_date($start_year, $start_month, $start_day, "8", "00");
		$range_end = date_2_utf_date($end_year, $end_month, $end_day, "08", "00");

		//set initial sub-range boundaries
		$end_year = $start_year;
		$end_month = $start_month+1;
		//$end_month %= 12;
		if($end_month == 13) $end_month = 1;
		if($end_month == 1) $end_year++;

		$i = 0;
		while(date_2_utf_date($end_year, $end_month, $end_day, "08", "00") <= $range_end){
     		$dates[$i]['start'] = date_2_utf_date($start_year, $start_month, $start_day, "8", "00");
     		$dates[$i]['end'] = date_2_utf_date($end_year, $end_month, $end_day, "8", "00");
				$dates[$i]['start_string']= date("F d, Y: H",$dates[$i]['start']);
				$dates[$i]['end_string']= date("F d, Y: H",$dates[$i]['end']);

			//increment to next month
			$i++;

			$start_day = 1;
			$start_month += 1;
			//$start_month %= 12;
			if($end_month == 13) $end_month = 1;
			if($start_month == 1)$start_year+=1;

			$this_end_day = 1;
			$end_month += 1;
			//$end_month %= 12;
			if($end_month == 13) $end_month = 1;
			if($end_month == 1)$end_year+=1;
		}
}
//print_r($dates);
//printf("Room_id:");
//printf("%d", sizeof($room_id));
//print_r($room_id);


/*
//OUTER FOR LOOP
//Iterates through each month or day within the start and end dates
for($n = 0; $n<$m; $n++){
$i = 0;
foreach($room_id as $room){
*/

//declare field arrays
$canceled_hours;
$noshow_hours;
$lt24_hours;
$gt24_hours;
$event_hours;
$room_name;
$total_hours;
$total_actual_hours;

$total_hours_open;



/*MAIN LOOP: builds and submits DB queries for each room
 * no_show hours: defined as  misc_charge_id = 4, customer owes full amt
 * gt24: cancellation with at least 24hrs notice, not a billable hour
 * lt24: cancellation with less than 24hrs notice, customer owes half
 * total_hours: all hours which actually appear on a day page of the reservation system
 * event_hours: non-billable hours from a special event or holiday closing
 * total_actual_hours: payable/billable hours calculated as
 *		total_hours - event_hours + no_shows + (lt24 / 2)
 */
//for($i = 0, $k = 4; $i < 4; $i++, $k++){
//for($i = 0; $i < 4; $i++){
foreach($room_id as $room){
		//for($i=0; $i<30; $i++){
       $i = 0;
foreach($dates as $row){
	//iterate through each room and fill in array member for the various *_hours arrays
		//$room_qry = "AND room_id=".($room)." ";
		if($room == 0) $room_qry = "";
		else $room_qry = "AND room_id=". ($room + 6);

//Define start and end times for this row
$start = $row['start'];
$end = $row['end'];
//printf("Boundary {%d, %d}", $start, $end);

//GET HOUR OPEN INFO
//DECLARE VARS
//$req_start_date = $start_month . "/" . $start_day . "/" . $start_year;
//$req_end_date = $end_month . "/" . $end_day . "/" . $end_year;
//day starts at noon
//$utf_start_date = date_2_utf_date($start_year, $start_month, $start_day, "12", "00");
//day ends at 2AM
//$utf_end_date = date_2_utf_date($end_year, $end_month, $actual_end_day, "02", "00");

$get_hours_open = "SELECT end_time
		FROM reservation_entry
		WHERE is_cancelled = 0
		AND (SELECT person_status_id AS status FROM person WHERE
		     reservation_entry.person_id = person.id) <> 5
		AND start_time >= $start
		AND end_time <= $end;";
		//ORDER BY reservation_room.id ASC;";


$get_total_hours_open = mysql_query($get_hours_open) or die ("ERROR 2: " . mysql_errno() . "-" . mysql_error() );

		$open_hours = NULL;
       $m = 0;
       while ($open_hours_info = mysql_fetch_array($get_total_hours_open)){
            $open_hours[$m] = $open_hours_info['end_time'];
			$m++;
		}
		if($open_hours){
				rsort($open_hours);
				if($DEBUG){ 
					   printf("=-=-=-=-=-=-=-=-=-=-=-=-</br>");
					   printf("Date(start)==%s</br>",date("n/j", $row['start']));
					   printf("DST?==%s</br>",date("I", $row['start']));
					   printf("opening time (not adjusted) = %s</br>", date("h:i:s",$start));
				}

				//Set opening time to noon of opening day
				$opening_time = strtotime(date("Y-m-d", $row['start']));	
				$opening_time += 43200;//advance time by 12 hrs to noon
				//adjustment for daylight savings
				if(date("h", $opening_time) == "01") $opening_time -= 3600;//subtract an hour
				else if(date("h", $opening_time) == "11") $opening_time += 3600;//add an hour
				if(date("h", $opening_time) != "12") printf("ERROR: invalid opening time!</br>");
				if($DEBUG)printf("%s</br>",date("Y-m-d h:i:s", $opening_time));

				//TOTAL_HRS_OPEN = CLOSING_TIME - START_TIME
				$total_hours_open[$i] = ($open_hours[0] - $opening_time)/120;
				/*
				if(date("I", $row['start']) == 0){//adjustment for daylight savings
					$opening_time = strtotime(date("Y-m-d", $row['start']));	
					$opening_time += 43200;//advance time by 12 hrs to noon
					if($DEBUG)printf("%s</br>",date("Y-m-d h:i:s", $opening_time));
					$total_hours_open[$i] = ($open_hours[0] - ($start+14400))/120;
				}
				else $total_hours_open[$i] = ($open_hours[0] - ($start+10800))/120;
 				*/
//printf("Total hours open array:");
//print_r($total_hours_open);
		}
		else $total_hours_open[$i] = 0;

		//NOTE: We are always open at least until 9:30pm
		//plus estimate 1 hour for cleaning??
//!!!! ADD this Back!
		if($total_hours_open < 9.5) $total_hours_open = 9.5;
//!!!!
		//else if($total_hours_open == 9.5) $total_hours_open = 10;
		//else $total_hours_open += 1;
		//initialize next row of fields
		$canceled_hours[$i] = 0;
		$noshow_hours[$i] = 0;
		$lt24_hours[$i] = 0;
		$gt24_hours[$i] = 0;
		$event_hours[$i] = 0;
		$total_hours[$i] = 0;
		$total_actual_hours[$i] = 0;

if($room == 0) $room_name[$i] = "Total";
else $room_name[$i] = "Room ".($room_id[$i]+6);



//BUILD SQL QUERIES

$get_total_hours = "SELECT room_name, SUM((end_time - start_time)/120) AS total_hours
FROM reservation_entry, reservation_room
WHERE reservation_entry.room_id = reservation_room.id
AND is_cancelled=0
AND person_id <> 'NULL'
AND start_time >=".$start."
AND end_time <=".$end." ".$room_qry."
ORDER BY reservation_room.id ASC;";

$get_canceled_hours = "SELECT room_name, SUM((end_time - start_time)/120) AS total_hours
FROM reservation_entry, reservation_room
WHERE reservation_entry.room_id = reservation_room.id
AND   (SELECT person_status_id AS status FROM person WHERE
		reservation_entry.person_id = person.id) <> 5
AND is_cancelled=1
AND person_id <> 'NULL'
AND start_time >= ".$start."
AND end_time <= ".$end." "
.$room_qry."
ORDER BY reservation_room.id ASC;";

$get_lt24_hours = "SELECT room_name, SUM((end_time - start_time)/120) AS total_hours
FROM reservation_entry, reservation_room
WHERE reservation_entry.room_id = reservation_room.id
AND 1 IN (SELECT misc_charge_id AS canc_type FROM reservation_transaction
	 WHERE reservation_entry.id = reservation_transaction.reservation_entry_id)
AND is_cancelled=1
AND person_id <> 'NULL'
AND   (SELECT person_status_id AS status FROM person WHERE
		reservation_entry.person_id = person.id) <> 5
AND start_time >= ".$start."
AND end_time <= ".$end." "
.$room_qry."
ORDER BY reservation_room.id ASC;";

$get_gt24_hours = "SELECT room_name, SUM((end_time - start_time)/120) AS total_hours
FROM reservation_entry, reservation_room
WHERE reservation_entry.room_id = reservation_room.id
AND 2 IN (SELECT misc_charge_id AS canc_type FROM reservation_transaction
	 WHERE reservation_entry.id = reservation_transaction.reservation_entry_id)
AND is_cancelled=1
AND person_id <> 'NULL'
AND   (SELECT person_status_id AS status FROM person WHERE
		reservation_entry.person_id = person.id) <> 5
AND start_time >= ".$start."
AND end_time <= ".$end." "
.$room_qry."
ORDER BY reservation_room.id ASC;";

$get_noshow_hours = "SELECT room_name, SUM((end_time - start_time)/120) AS total_hours
FROM reservation_entry, reservation_room
WHERE reservation_entry.room_id = reservation_room.id
AND 4 IN (SELECT misc_charge_id AS canc_type FROM reservation_transaction
	 WHERE reservation_entry.id = reservation_transaction.reservation_entry_id)
AND is_cancelled=1
AND person_id <> 'NULL'
AND   (SELECT person_status_id AS status FROM person WHERE
		reservation_entry.person_id = person.id) <> 5
AND start_time >= ".$start."
AND end_time <= ".$end." "
.$room_qry."
ORDER BY reservation_room.id ASC;";

$get_event_hours = "SELECT room_name, SUM((end_time - start_time)/120) AS total_hours
FROM reservation_entry, reservation_room
WHERE reservation_entry.room_id = reservation_room.id
AND   (SELECT person_status_id AS status FROM person WHERE
		reservation_entry.person_id = person.id) = 5
AND is_cancelled=0
AND person_id <> 'NULL'
AND start_time >= ".$start."
AND end_time <= ".$end." "
.$room_qry."
ORDER BY reservation_room.id ASC;";

//SUBMIT QUERIES
$get_total_hours_res = mysql_query($get_total_hours) or die ("ERROR 2: " . mysql_errno() . "-" . mysql_error() );
$get_total_hours_canceled = mysql_query($get_canceled_hours) or die ("ERROR 2: " . mysql_errno() . "-" . mysql_error() );
$get_total_gt24 = mysql_query($get_gt24_hours) or die ("ERROR 2: " . mysql_errno() . "-" . mysql_error() );
$get_total_lt24 = mysql_query($get_lt24_hours) or die ("ERROR 2: " . mysql_errno() . "-" . mysql_error() );
$get_total_noshow = mysql_query($get_noshow_hours) or die ("ERROR 2: " . mysql_errno() . "-" . mysql_error() );
$get_total_hours_event = mysql_query($get_event_hours) or die ("ERROR 2: " . mysql_errno() . "-" . mysql_error() );


//FILL IN ARRAYS /////////////////////////////////////////////////////////////

		//GET HOUR INFO
		//if $data_fields contains total_hours
		//if(in_array('canceled_hours', $data_fields))
        if($canceled_hrs_info = mysql_fetch_array($get_total_hours_canceled))
            $canceled_hours[$i] = $canceled_hrs_info['total_hours'];

		//if(in_array('noshow_hours', $data_fields))
       if ($noshow_hrs_info = mysql_fetch_array($get_total_noshow))
            $noshow_hours[$i] = $noshow_hrs_info['total_hours'];

		//if(in_array('gt24_hours', $data_fields))
       if ($gt24_hrs_info = mysql_fetch_array($get_total_gt24))
            $gt24_hours[$i] = $gt24_hrs_info['total_hours'];

		//if(in_array('lt24_hours', $data_fields))
       if ($lt24_hrs_info = mysql_fetch_array($get_total_lt24))
            $lt24_hours[$i] = $lt24_hrs_info['total_hours'];

		//if(in_array('event_hours', $data_fields))
       if ($event_hrs_info = mysql_fetch_array($get_total_hours_event))
            $event_hours[$i] = $event_hrs_info['total_hours'];

		//if(in_array('total_hours', $data_fields))
       if ($hours_info = mysql_fetch_array($get_total_hours_res))
            $total_hours[$i] = $hours_info['total_hours'];

	  $date[$i] = date("n/j/Y", $row['start']);

//printf("Total Hours: %d ", $total_hours[$i]);
//print_r($total_hours[$i]);

	   //Calculate total payable hours
		if(in_array('total_actual_hours', $data_fields))
       $total_actual_hours[$i] = $total_hours[$i] - $event_hours[$i] + ($lt24_hours[$i] * 0.5) + $noshow_hours[$i];
	   if($total_hours_open[$i] == 0) $percent_efficiency[$i] = 0;
	   else $percent_efficiency[$i] = ($total_actual_hours[$i]/($total_hours_open[$i] * 4));
	   $i++;
	   }//end foreach row
	}//end foreach room_id
/////////////////////END LOOPS //////////////////////////////////////////////



//SERIALIZE USAGE DATA
		/*
		 * This array stores pointers to all data for the report. Once the
		 * data has been filled in, the array will be serialized so that
		 * it may be passed as a hidden input for exporting csv files.
		 */
		if(in_array('room_name', $data_fields))
		  $usage_data['room_name'] = $room_name;
		if(in_array('date', $data_fields))
		  $usage_data['date'] = $date;
		if(in_array('total_hours', $data_fields))
		  $usage_data['total_hours'] = $total_hours;
		if(in_array('event_hours', $data_fields))
  		  $usage_data['event_hours'] = $event_hours;
		if(in_array('canceled_hours', $data_fields))
		  $usage_data['canceled_hours'] = $canceled_hours;
		if(in_array('lt24_hours', $data_fields))
		  $usage_data['lt24_hours'] = $lt24_hours;
		if(in_array('gt24_hours', $data_fields))
		  $usage_data['gt24_hours'] = $gt24_hours;
		if(in_array('noshow_hours', $data_fields))
		  $usage_data['noshow_hours'] = $noshow_hours;
		if(in_array('total_actual_hours', $data_fields))
		  $usage_data['total_actual_hours'] = $total_actual_hours;
		if(in_array('total_actual_hours', $data_fields))
		  $usage_data['total_hours_open'] = $total_hours_open;
		if(in_array('percent_eff', $data_fields))
		  $usage_data['percent_eff'] = $percent_efficiency;


//print_r($usage_data);
//print_r($usage_data['date']);
		$usage_data_ser = serialize($usage_data);
		$usage_encoded = urlencode($usage_data_ser);

//DISPLAY RESULTS/////////////////////////////////////////////////
			if($room_id == "") $room_title = "all rooms";
			else $room_title = "Room " . ($room_id[$i]+7);

            if (($total_hours == NULL)&&
			   ($canceled_hours == NULL)&&
			   ($event_hours == NULL))
            {
            $display_block .= "<h2 align=\"left\">Total rental hours for " . $room_title .
				" from " . $req_start_date . " to " . $req_end_date    . "</h2>";
            $display_block .= "<div id=\"links_report_new\"><a href=\"../admin/index.php\">
				Back to reports index</a> | <a href=\"total_hours_room.php\">Select a different room</a></div>";
            $display_block .= "<div id=\"text\" align=\"left\"><p>
				No rental hours available for the requested date range</p></div>";
            }//end if hours == NULL

            else {
	    $req_start_date = (date("n/j/Y", $dates[0]['start']));
	    $req_end_date = (date("n/j/Y", $dates[sizeof($dates)-1]['start']));
		    /*
            $display_block .= "<h2 align=\"left\">Total rental hours for "
				. $room_title . " from " . $req_start_date . " to " . $req_end_date    . "</h2>";
 */
            $display_block .= "<h2 align=\"left\">Total rental hours for all rooms from "
				. $req_start_date . " to " . $req_end_date    . "</h2>";
				(date("n/j/Y", $row['start'])) . "</td>";
            $display_block .= "<div id=\"links_report_new\"><a href=\"../admin/index.php\">Back to reports index</a> | <a href=\"total_hours_room.php\">Select a different room</a></div>";
            $display_block .= "<form method=\"post\" action=\"$_SERVER[PHP_SELF]\">";
            $display_block .= "<div id=\"text\" align=\"left\">
				<table border=\"5\" cellpadding=\"7\" cellspacing=\"0\">";
		if(in_array('room_name', $data_fields))
	        $display_block .= "<th>Room number</th>";
		if(in_array('date', $data_fields))
			$display_block .= "<th>Date</th>";
		if(in_array('total_hours', $data_fields))
			$display_block .= "<th>Total hours on the books</th>";
		if(in_array('event_hours', $data_fields))
			$display_block .= "<th>Total Event Hours</th>";
		if(in_array('canceled_hours', $data_fields))
			$display_block .= "<th>Total hours canceled</th>";
		if(in_array('lt24_hours', $data_fields))
			$display_block .= "<th>Cancellations (owe half)</th>";
		if(in_array('gt24_hours', $data_fields))
			$display_block .= "<th>Cancellations (owe nothing)</th>";
		if(in_array('noshow_hours', $data_fields))
			$display_block .= "<th>Total no shows</th>";
		if(in_array('total_actual_hours', $data_fields))
			$display_block .= "<th>Total payable hours</th>";
		if(in_array('hours_open', $data_fields))
			$display_block .= "<th>Hours open</th>";
		if(in_array('percent_eff', $data_fields))
			$display_block .= "<th>%Eff</th>";

//TODO: make this a foreach($room_id as $i)
		$h = 0;
		//foreach($room_id as $i){
		$i=0;
		foreach($dates as $row){
			//if($room_id == ""){$i = 0; $j = 4;}//Show all rooms
			//else {$i = ($room_id[$i]-1); $j = $i+1;}//Show one room
			//$i -= 1;
			//while($i < $j){

        $display_block .= "<tr>";
		if(in_array('room_name', $data_fields))
				$display_block .= "<td>" . $room_name[$h] . "</td>";
		if(in_array('date', $data_fields))
				$display_block .= "<td>" . (date("n/j/Y", $row['start'])) . "</td>";
	    //$display_block .= "<td>" . (date("n/j h:m", $row['start']))."end:".(date("h:m", $row['end'])) . "</td>";
		if(in_array('total_hours', $data_fields))
				$display_block .= "<td>" . ($total_hours[$i]+0) . "</td>";
		if(in_array('event_hours', $data_fields))
				$display_block .= "&nbsp;<td>" . ($event_hours[$i]+0). "</td>";
		if(in_array('canceled_hours', $data_fields))
				$display_block .= "&nbsp;<td>" . ($lt24_hours[$i] + $gt24_hours[$i] + $noshow_hours[$i]). "</td>";
		if(in_array('lt24_hours', $data_fields))
				$display_block .= "&nbsp;<td>" . ($lt24_hours[$i]+0). "</td>";
		if(in_array('gt24_hours', $data_fields))
				$display_block .= "&nbsp;<td>" . ($gt24_hours[$i]+0). "</td>";
		if(in_array('noshow_hours', $data_fields))
				$display_block .= "&nbsp;<td>" . ($noshow_hours[$i]+0). "</td>";
		if(in_array('total_actual_hours', $data_fields))
				$display_block .= "&nbsp;<td>" . ($total_actual_hours[$i]+0). "</td>";
		if(in_array('hours_open', $data_fields))
				$display_block .= "&nbsp;<td>" . ($total_hours_open[$i]+0) . "&nbsp</td>";
		if(in_array('percent_eff', $data_fields))
				$display_block .= "&nbsp;<td>" . (number_format($percent_efficiency[$i]*100, 2)) . "&nbsp</td>";
		$display_block .= "</tr>";

				$i++;
				$h++;
			}//end while
            $display_block .= "</table><br />";

		//EXPORT TO EXCEL BUTTON

//TODO: create check boxes to select which fields should be exported
//Move the $data_usage array creation to here?? actually no,
//just use the check fields extracted from $_POST on the submit to
//decide which array fields to print to the temp file
/* To export select ONE data field (radio buttons) and ANY of {rooms, total}*/

$days = $end_day - $start_day +1;
//printf("Days: %d ", $days);

            $display_block .=
			"<input type=hidden name=utf_start_date value=$utf_start_date>
            <input type=hidden name=utf_end_date value=$utf_end_date>
            <input type=hidden name=num_days value=$days>

            <input type=hidden name=usage_data value=$usage_encoded>

            <input type=hidden name=room_id value=$room_id>
            <input type=submit name=run_csv value=\"Export report to Excel\"></form></div>";

        }//end else hours are not NULL
}//end if isset run_report

/////////////////////////////////////////////////////////////////////////////////
//export single item report to Excel********************************************/
if (isset($_POST['run_csv']))
{

$room_id = $_POST['room_id'];
$utf_start_date = $_POST['utf_start_date'];
$utf_end_date = $_POST['utf_end_date'];

$usage_data = unserialize(urldecode($_POST['usage_data']));
//print_r($usage_data);

/* 
 *
 * 2010-07-09 Medina: adapted from csv_download.inc
 *		opens a temp file, ouputs usage data as comma seperated values
 *		exports to Excel spreadheet, and closes temp file
 *                  
 */
$pageTitle = "csvfile";

        #  Turn OFF any binary compression
        #
        ini_set( 'zlib.output_compression','Off' );

        #  Build the name of the CSV file
        #
        $baseFileName   = preg_replace( '/[\s()\'\"\[\]]+/', '_', $pageTitle );
        $csvFile        = $baseFileName . '.csv';
        $csvFile_mangle = $baseFileName . '_' . time() . '.csv';

        #  Open the CSV file for output
        #
        $fp = fopen( '../tmp/' . $csvFile_mangle, "w" );

        #  Get the number of rows
        #
        //$numRows = mysql_num_rows( $result );

        #  Get the number of fields
        #
        //$numFields = mysql_num_fields( $result );

//Print data to CSV file
        #  Output a row that contains the column headers
        #
		
		if($usage_data['room_name'])
	        fwrite($fp, "Room Name,");
		if($usage_data['date'])
	        fwrite($fp, "Date,");
		if($usage_data['total_hours'])
	        fwrite($fp, "Total Hours,");
		if($usage_data['event_hours'])
	        fwrite($fp, "Event Hours,");
		if($usage_data['canceled_hours'])
	        fwrite($fp, "Total Canceled Hours,");
		if($usage_data['lt24_hours'])
	        fwrite($fp, "Cancellations (owe half),");
		if($usage_data['gt24_hours'])
	        fwrite($fp, "Cancellations (owe nothing),");
		if($usage_data['noshow_hours'])
	        fwrite($fp, "No Shows,");
		if($usage_data['total_actual_hours'])
	        fwrite($fp, "Total Payable Hours,");
		if($usage_data['total_hours_open'])
	        fwrite($fp, "Hours Open,");
		if($usage_data['percent_eff'])
	        fwrite($fp, "%Eff,");
			
        #  End this first fow
        #
        fwrite( $fp, "\n" );


		//Fill in the fields with data
		for($i=0; $i<$_POST['num_days']; $i++){
				foreach($usage_data as $field){
						if($field[$i]) fwrite($fp, "".$field[$i].",");
						else fwrite($fp, "0,");
				}
				fwrite( $fp, "\n" );
		}

        #  Close the file
        #
        fclose( $fp );

        #  Clear the output buffe
        #
        ob_clean();

        #  Build a timestamp for the file
        #
        $lastModified = date( "D, d M Y H:i:s T" );

        #  Send the headers to the browser
        #  telling it that there is a CSV file
        #  ready to download
        #
        header( "Content-Type: application/vnd.ms-excel" );
        #header( 'Content-Disposition: inline; filename="'.$csvFile.'";' );
        header( 'Content-Disposition: attachment; filename="' . $csvFile . '";' );
        header( 'Accept-Ranges: bytes' );
        header( 'Last-Modified: ' . $lastModified . '.ETag: "30365-3600-915c7b00"' );
        header( "Content-Length: " . filesize( '../tmp/' . $csvFile_mangle ) );

        #  Send the CSV file to the browser
        #
        @readfile( '../tmp/' . $csvFile_mangle );

        #  Flush the output buffer
        #
        flush();

        #  Remove the file
        #
        unlink( '../tmp/' . $csvFile_mangle );

        #  Exit Happily
        #
        //exit( 0 );
/*
$display_block = "Hello!"
		.$usage_data['room_name'][0]." "
		.($usage_data['total_hours'][0]+0)." "
		.($usage_data['event_hours'][0]+0)." ";
		//.$usage_data['total_actual_hours'].
		//"End";
*/
		

}//end if isset run_csv


/*
 * Original query
 *
$sql = "SELECT room_name, SUM((end_time - start_time)/120) AS total_hours,
SUM((actual_end_time - actual_start_time)/120) AS total_actual_hours
FROM reservation_entry, reservation_room
WHERE reservation_entry.room_id = reservation_room.id
AND is_cancelled=0
AND start_time >= $utf_start_date
AND end_time <= $utf_end_date
AND room_id=$room_id;";



//New query
$sql = "SELECT room_name, 
	     SUM((end_time - start_time)/120) AS total_hours,
	     (SUM((end_time - start_time)/120) -
		(SELECT SUM((end_time - start_time)/120) 
		     FROM reservation_entry
		     WHERE reservation_entry.room_id = reservation_room.id
			AND is_cancelled = 1
			AND start_time >= $utf_start_date
			AND end_time <= $utf_end_date
			AND $room_id = $room_id)) AS total_actual_hours
FROM reservation_entry, reservation_room
WHERE reservation_entry.room_id = reservation_room.id
AND is_cancelled=0
AND start_time >= $utf_start_date
AND end_time <= $utf_end_date
AND room_id=$room_id;";

$sql_res = mysql_query($sql) or die ("ERROR 4: " . mysql_errno() . "-" . mysql_error() );

$csvReport = "Y";
include ("../includes/csv_download.inc");
}

 */
//export total items report to Excel********************************************/

/*
if (isset($_POST['run_csv_total']))
{

$utf_start_date = $_POST['utf_start_date'];
$utf_end_date = $_POST['utf_end_date'];

 * Original query
$sql = "SELECT room_name, SUM((end_time - start_time)/120) AS total_hours,
SUM((actual_end_time - actual_start_time)/120) AS total_actual_hours
FROM reservation_entry, reservation_room
WHERE reservation_entry.room_id = reservation_room.id
AND is_cancelled=0
AND room_name <> 'equipment'
AND start_time >= $utf_start_date
AND end_time <= $utf_end_date
GROUP BY room_name
ORDER BY reservation_room.id ASC;";

//new query
$sql = "SELECT room_name, 
	     SUM((end_time - start_time)/120) AS total_hours,
	     (SUM((end_time - start_time)/120) -
		(SELECT SUM((end_time - start_time)/120) 
		     FROM reservation_entry
		     WHERE reservation_entry.room_id = reservation_room.id
			AND is_cancelled = 1
			AND start_time >= $utf_start_date
			AND end_time <= $utf_end_date
AND room_name <> 'equipment'
			)) AS total_actual_hours
FROM reservation_entry, reservation_room
WHERE reservation_entry.room_id = reservation_room.id
AND is_cancelled=0
AND start_time >= $utf_start_date
AND end_time <= $utf_end_date
AND room_name <> 'equipment'
GROUP BY room_name
ORDER BY reservation_room.id ASC;";

$sql_res = mysql_query($sql) or die ("ERROR 5: " . mysql_errno() . "-" . mysql_error() );

$csvReport = "Y";
include ("../includes/csv_download.inc");
}

 */
?>
<html>
 <head>
    <title>Report: total rental hours by room</title>
    <link href="../includes/person_band.css" rel="stylesheet" type="text/css" />

    <script language="JavaScript" type="text/javascript">
        <!--

        function validate_form()
        {
            valid = true;

            if ( document.run_report.sel_id.selectedIndex == 0 )
            {
            alert ( "Please select a room from the drop down menu" );
            valid = false;
            }

         return valid;
         }

    //-->
    </script>
  </head>
    <body>
    <? include("../includes/header.inc"); ?>
     <div id="page" align="center">

    <? Print $display_block; ?>
    </div>
</body>
</html>
