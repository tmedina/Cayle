<?php
//PERSON/BAND MODULE
//Created by Hallie Pritchett
//This page shows the dropdown menu for existing person records and the person search box


//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}

//connect to the database and functions.inc file
include("../includes/dbconnect.inc");
include("../includes/functions.inc");

//splash screen - select a record to view
 // view the drop down list of person record names & phone numbers available
 if ($_POST[op] != "view")

 {
     //set up person drop down menu - get fname, lname, phone
     $get_list = "SELECT id, CONCAT_WS(', ', lname, fname) as display_name, phone FROM person WHERE is_active=1 AND id != 1 ORDER BY lname, fname ASC";
     $get_list_res = mysql_query($get_list) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

     if (mysql_num_rows($get_list_res) <1)
     {
         //if no records available
         $display_block .= "<div id=\"first_links\"><a href=\"create_new_person.php\">Create a new person record</a> | <a href=\"band.php\">View/create band records</a></div>";
         $display_block .= "<div id=\"dropdown\" align=\"left\"><p><em>No records available</em></p></div>";
     } else
     {
         //if records available - get results & create drop down menu
         $display_block .= "<div id=\"first_links\"><a href=\"band.php\">View/create band records</a> | <a href=\"create_new_person.php\">Create a new person record</a></div>";
         $display_block .="<div id=\"dropdown\" align=\"left\">
         <form method=\"post\" name=\"person\" action=\"view_person.php\" onsubmit=\"return validate_form();\" >
         <h3>Select a record to view</h3>
         <select name=\"sel_id\">
         <option value=\"0\">--Select a person--</option>";

         while($recs = mysql_fetch_array($get_list_res))
         {
             //define variables
             $id = $recs['id'];
             $display_name = stripslashes($recs['display_name']);
             $phone = $recs['phone'];

             $display_block .= "<option value=\"$id\">
                $display_name - $phone</option>";
         }
         //button to select a person from the drop down menu
         $display_block .= "</select>
         <input type=\"submit\" name=\"submit\" value=\"View selected person\"></form></div>";
     }    
        
 }
//end section

 
 ?>

 <html>
    <head>
    <title>Person records</title>
    
    <link href="includes/person_band.css" rel="stylesheet" type="text/css" />
    
    <!--
        Form validation to make sure a value is selected from the drop down box
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
    
    <h2 align="left">View/create person records</h2>
    <? Print $display_block; ?>
    

    <div id="search_box" align="left">
    <h3 align="left">Search by first name OR last name OR phone</h3>
    <form action="<? $_SERVER[PHP_SELF] ?>" method="get" name="search">
  
      <input name="q" type="text" value="<?php echo $q; ?>" size="37">
      <input name="search" type="submit" value="Search">
      <? Print $resultmsg; ?>

</form>


<?

//specify how many results to display per page

$limit = 1000;

// Get the search variable from URL
  $var = @$_GET['q'] ;
//trim whitespace from the stored variable
  $trimmed = trim($var);
//separate key-phrases into keywords
  $trimmed_array = explode(" ",$trimmed);

// check for an empty string and display a message
if ($trimmed == "") {
  $resultmsg .=  "<h3 class=\"error\">Search Error</h3><p class=\"error\">&nbsp;&nbsp;&nbsp;Please enter a first name OR last name OR phone</p></div></body></html>" ;
  }

// check for a search parameter
if (!isset($var)){
  $resultmsg =  "<br /></div></body></html>" ;
  }

foreach ($trimmed_array as $trimm){


     $query = "SELECT * FROM person WHERE fname LIKE \"%$trimm%\" OR lname LIKE  \"%$trimm%\" OR phone LIKE \"%$trimm%\" ORDER BY fname, lname, phone DESC" ;
     // Execute the query to  get number of rows that contain search kewords
     $numresults=mysql_query ($query);
     $row_num_links_main =mysql_num_rows ($numresults);

     // next determine if 's' has been passed to script, if not use 0.
     // 's' is a variable that gets set as we navigate the search result pages.
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

// display search results
 if( isset ($resultmsg)){
  echo $resultmsg;
  exit();
 }else{
  echo "<h3>Search results for: " . $var . "</h3>";
 }

foreach($newarr as $value){


 $query_value = "SELECT * FROM person WHERE id = '$value'";
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
     
//end highlight
if ($is_active==1){

echo "&nbsp;&nbsp;&nbsp;<a href=\"person_record.php?person_id=$person_id\">$titlehigh $linkhigh - $linkdesc</a><br /><br />";
    }else
    {
 echo "&nbsp;&nbsp;&nbsp;<b>$titlehigh $linkhigh - $linkdesc - inactive record</b><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Contact administrator to activate<br /><br />";
}
}

}


}  //end foreach $newarr
echo "</div>";

?>


</div>

</body></html>
