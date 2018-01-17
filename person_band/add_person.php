<?php
//ADD PERSON/BAND TO RESERVATION MODULE
//Created by Hallie Pritchett
//show drop downs for exising person records & person search box

//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}

//connect to the db
    include("../includes/dbconnect.inc");
    include("../includes/functions.inc");

 // view the drop down list of person record names & phone numbers available
  $reservation_id = $_GET['reservation_id'];
  $repeat_id = $_GET['repeat_id'];
  $return_url = $_GET['return_url'];
  $event_type = $_GET['event_type'];
  $person_id = $_POST[sel_id];

//DEBUG
if ( $reservation_id == 0 )
{
    echo "Reservation ID is 0 = $reservation_id";
    exit (0);
}
//DROP DOWN MENU
    //set up person drop down menu
	if($event_type == 0)//If Rehearsal, filter out event types
     $get_list = "SELECT id, CONCAT_WS(', ', lname, fname) as display_name, phone FROM person 
		WHERE is_active = 1 AND id != 1 AND person_status_id != 5 ORDER BY lname, fname ASC";
	else if($event_type == 1)//If Event, filter out customers
     $get_list = "SELECT id, CONCAT_WS(', ', lname, fname) as display_name, phone, fname, lname FROM person
		WHERE is_active = 1 AND id != 1 AND person_status_id = 5 ORDER BY lname, fname ASC";

// TODO: Fetch this in chunks, to speed up response time
     $get_list_res = mysql_query($get_list) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

     if (mysql_num_rows($get_list_res) <1)
     {
         //if no records available
         $display_block .= "<h2 align=\"left\">Add person to reservation</h2>";
         $display_block .= "<div id=\"first_links\"><a href =\"create_new_person_on_res.php?reservation_id=$reservation_id&repeat_id=$repeat_id&return_url=$return_url\">Create a new person record</a> | ";
         $display_block .= "<a href=\"cancel_reservation.php?reservation_id=$reservation_id\">Start over</a></div>";
         $display_block .= "<h3>Select an active record to view</h3><p><em>No records available</em></p>";
     }
	 else
     {
         //if records available - get results & create drop down menu
	if($event_type==1){
         $display_block .= "<h2 align=\"left\">Add event type to reservation</h2>";
         $display_block .= "<div id=\"first_links\">";
         $display_block .= "<a href=\"cancel_reservation.php?reservation_id=$reservation_id\">
						Start over</a></div>";
         $display_block .="<div id=\"dropdown\" align=\"left\">";
		 $display_block .= "<h3>Select an event type</h3>";
         $display_block .= "<form method=\"post\" name=\"person\"
         action=\"confirmation.php\" onsubmit=\"return validate_form();\">
         <select name=\"sel_id\">";
         $display_block .= "<option value=\"0\">--Select an event--</option>";
    }
    else{
         $display_block .= "<h2 align=\"left\">Add person to reservation</h2>";
         $display_block .= "<div id=\"first_links\">
				<a href =\"create_new_person_on_res.php?
				reservation_id=$reservation_id&repeat_id=$repeat_id
				&return_url=$return_url\">Create a new person record</a> | ";
         $display_block .= "<a href=\"cancel_reservation.php?reservation_id=$reservation_id\">Start over</a></div>";
         $display_block .="<div id=\"dropdown\" align=\"left\">";
		 $display_block .= "<h3>Select a person record</h3>";
         $display_block .= "<form method=\"post\" name=\"person\"
         action=\"view_person.php\" onsubmit=\"return validate_form();\">
         <select name=\"sel_id\">";
         $display_block .= "<option value=\"0\">--Select a person--</option>";
		}


//DROP DOWN DISPLAY
         while($recs = mysql_fetch_array($get_list_res))
         {
				$id = $recs['id'];
				$display_name = stripslashes($recs['display_name']);
				$phone = $recs['phone'];
				$event_name_head = $recs['fname'];
				$event_name_tail = $recs['lname'];

				if($event_type == 1)
				$display_block .= "<option value=\"$id\">
						$event_name_head $event_name_tail</option>";
				else
				$display_block .= "<option value=\"$id\"> $display_name - $phone</option>";
         }

         $display_block .= "</select><input type=\"hidden\" name=\"op\" value=\"view\">
         <input type=\"hidden\" name=\"reservation_id\" value=\"$reservation_id\">
         <input type=\"hidden\" name=\"repeat_id\" value=\"$repeat_id\">
         <input type=\"hidden\" name=\"return_url\" value=\"$return_url\">
         <input type=\"hidden\" name=\"event_type\" value=\"$event_type\">";

		 if($event_type == 1){
				$display_block .= "<input type=\"submit\" name=\"submit\" value=\"Submit Event\">
				</form>
				</div>";
		}
		else{
				$display_block .= "<input type=\"submit\" name=\"submit\" value=\"View selected person\">
				</form>
				</div>";

		}

     }
        

 //end section

?>

<html>
    <head>
    <title>Add person to reservation</title>
    <link href="includes/person_band_res.css" rel="stylesheet" type="text/css" />
    <!--
    Form validation - checks to make sure a person is selected from the drop down menu
    -->
    <script language="JavaScript" type="text/javascript">
        <!--

        function validate_form()
        {
            valid = true;

            if ( document.person.sel_id.selectedIndex == 0 )
            {
            alert ( "Please select a person from the drop down menu" );
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
    <div id="search_box" align="left">
    <h3 align="left">Search by first name OR last name OR phone</h3>
    <form action="<? $_SERVER[PHP_SELF] ?>" method="get" name="search">

      <input name="q" type="text" value="<?php echo $q; ?>" size="37">
      <input type="hidden" value="<?php echo $reservation_id; ?>">
      <input type="hidden" name="reservation_id" value="<? echo $reservation_id; ?>">
      <input type="hidden" name="repeat_id" value="<? echo $repeat_id; ?>">
      <input type="hidden" name="return_url" value="<? echo $return_url; ?>">
      <input type="hidden" name="event_type" value="<? echo $event_type; ?>">
      <input name="search" type="submit" value="Search">
      <? Print $resultmsg; ?>
      

</form>


<?

//specify how many results to display per page

$limit = 1000;

// Get the search variable from URL
  $var = $_GET['q'] ;
  
//trim whitespace from the stored variable
  $trimmed = trim($var);
//separate key-phrases into keywords
  $trimmed_array = explode(" ",$trimmed);

// check for an empty string and display a message.
if ($trimmed == "") {
  $resultmsg .=  "<h3 class=\"error\">Search Error</h3><p class=\"error\">&nbsp;&nbsp;&nbsp;Please enter a first name OR last name OR phone</p></div></body></html>" ;
  }

// check for a search parameter
if (!isset($var)){
  $resultmsg =  "<br /></div></body></html>" ;
  }
// Build SQL Query for each keyword entered
foreach ($trimmed_array as $trimm){

     $query = "SELECT * FROM person WHERE fname LIKE \"%$trimm%\" OR lname LIKE  \"%$trimm%\" OR phone LIKE \"%$trimm%\" ORDER BY fname, lname, phone DESC" ;
     // Execute the query to  get number of rows that contain search kewords
     $numresults=mysql_query ($query);
     $row_num_links_main =mysql_num_rows ($numresults);

     // next determine if 's' has been passed to script, if not use 0.
     
     if (empty($s)) {
         $s=0;
     }

      // now let's get results.
      $query .= " LIMIT $s,$limit" ;
      $numresults = mysql_query ($query) or die ( "Couldn't execute query" );
      $row= mysql_fetch_array ($numresults);

      //store record id of every item that contains the keyword in the array we need to do this to avoid display of duplicate search result.
      do{
 
          $adid_array[] = $row[ 'id' ];
      }while( $row = mysql_fetch_array($numresults));
 } //end foreach

if($row_num_links_main == 0 && $row_set_num == 0){
   $resultmsg .= "<h3>Search results for: " . $trimmed  ."</h3><p>&nbsp;&nbsp;&nbsp;No records available</p></div>" ;
}
   //delete duplicate record id's from the array
   $tmparr = array_unique($adid_array);
   $i=0;
   foreach ($tmparr as $v) {
       $newarr[$i] = $v;
       $i++;
   }

// display what the person searched for.
 if( isset ($resultmsg)){
  echo $resultmsg;
  exit();
 }else{
  echo "<h3>Search results for: " . $var . "</h3>";
 }

foreach($newarr as $value){

if($event_type == 1)//If Event, get only event types
 $query_value = "SELECT * FROM person WHERE id = '$value' AND person_status_id = 5";
else //else get only non-event types
 $query_value = "SELECT * FROM person WHERE id = '$value' AND person_status_id != 5";

 $num_value=mysql_query ($query_value);
 $row_linkcat= mysql_fetch_array ($num_value);
 $row_num_links= mysql_num_rows ($num_value);

 foreach($trimmed_array as $trimm){

    if($trimm != 'b'){

        $person_id = $row_linkcat[ 'id' ];
        $titlehigh = $row_linkcat[ 'fname' ];
        $linkhigh = $row_linkcat[ 'lname' ] ;
        $linkdesc = $row_linkcat[ 'phone' ] ;
        $is_active = $row_linkcat ['is_active'];
        $person_status_id = $row_linkcat ['person_status_id'];
        

//end highlight

//QUERY RESULTS DISPLAY
	if ($event_type==1){
			if($is_active == 1){
				echo "&nbsp;&nbsp;&nbsp;<a href=\"confirmation.php?
				person_id=$person_id&reservation_id=$reservation_id
				&repeat_id=$repeat_id&return_url=$return_url&event_type=1\">
				$titlehigh $linkhigh</a><br /><br />";
			}
			/*
			else{
				echo "&nbsp;&nbsp;&nbsp;<b>$titlehigh $linkhigh -
					$linkdesc - inactive record</b><br />&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;Contact administrator to activate<br /><br />";
			}*/
    }//end if Event
	else{
		if($is_active == 1) {
				echo "&nbsp;&nbsp;&nbsp;<a href=\"person_record.php?
				person_id=$person_id&reservation_id=$reservation_id
				&repeat_id=$repeat_id&return_url=$return_url\">
				$titlehigh $linkhigh - $linkdesc</a><br /><br />";
        }
		else{
				echo "&nbsp;&nbsp;&nbsp;<b>$titlehigh $linkhigh -
					$linkdesc - inactive record</b><br />&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;Contact administrator to activate<br /><br />";
		}//end else (inactive)
    }//end else Rehearsal

}//end if trimmd !='b'
}//end foreach trimmed array


}  //end foreach $newarr
echo "</div>";

?>

</div>
</body></html>


