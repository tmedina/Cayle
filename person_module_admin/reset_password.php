<?php

//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}

//connect to the database
include("../includes/dbconnect.inc");

$username = $_GET['username'];

     // view the drop down list of person record names & phone numbers available

     //set up person drop down menu - get fname, lname, phone
     if ( isset($username) && $username != "")
     {
        $get_list = "SELECT id, CONCAT_WS(', ', lname, fname) as display_name, phone FROM person WHERE user_name = '$username'";        
     }
     else
     {
        $get_list = "SELECT id, CONCAT_WS(', ', lname, fname) as display_name, phone FROM person WHERE is_active=1 AND id != 1 AND person_status_id <> 1 ORDER BY lname, fname ASC";         
     }
    $get_list_res = mysql_query($get_list) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

     if (mysql_num_rows($get_list_res) <1)
     {
         //if no records available
         
         $display_block .= "<h3>Select a record to view</h3><p><em>No records available</em></p>";
     } else
     {
        //if records available - get results & create drop down menu
         
         $display_block .="<div id=\"dropdown\" align=\"left\">
         <form method=\"post\" name=\"person\" action=\"reset_form.php\" onsubmit=\"return validate_form();\">
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
         <input type=\"hidden\" name=\"op\" value=\"reset\">
         <input type=\"submit\" name=\"submit\" value=\"View selected person\"></form><br />";

     }

?>

<html><head>
<title>Reset password</title>
    <link href="includes/person_band_admin.css" rel="stylesheet" type="text/css" />

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
    <h2 align="left">Reset password</h2>
    <? Print $display_block; ?>
    </div>
    </body>
</html>