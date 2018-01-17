<?php
//ADD PERSON/BAND TO RESERVATION MODULE
//Created by Hallie Pritchett

//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}

 //connect to the db
     include("../includes/dbconnect.inc");
     include("../includes/config.inc");

    $reservation_id = $_GET['reservation_id'];
    $person_id = $_GET['person_id'];
    $repeat_id = $_GET['repeat_id'];
    $return_url = $_GET['return_url'];
    $band_id = $_GET['band_id'];

    //override for for persons with unpaid reservations
    
    $display_block .= "<div id=\"first_links\"><a href=\"add_person.php?reservation_id=$reservation_id&repeat_id=$repeat_id&return_url=$return_url\">Select a different person</a> | <a href=\"cancel_reservation.php?reservation_id=$reservation_id\">Cancel this reservation</a></div>";
    $display_block .= "<div id=\"text\" align=\"left\"><h3 align=\"left\"><font style=\"color:#FF0000\">ALERT!!!</font></h3>";
    $display_block .= "<h4>This person has an outstanding balance on their record - <br />enter initials and a comment to continue this reservation</h4>";
    $display_block .= "<form method=\"post\" action=\"override_band.php\" onsubmit=\"return checkform(this);\">
    <table border=\"0\">
            <tr><td>Employee initials: <input type=\"text\" size=2 name=\"override_initials\" value=\"$override_initials\" ></td></tr>
            <tr><td valign=\"top\"><br />Comment: <br /><textarea rows=\"2\" cols=\"26\" name=\"override_comment\"></textarea></td></tr>

            <tr><td align=\"right\">
            <input type=\"hidden\" name=\"reservation_id\" value=\"$reservation_id\">
            <input type=\"hidden\" name=\"repeat_id\" value=\"$repeat_id\">
            <input type=\"hidden\" name=\"return_url\" value=\"$return_url\">
            <input type=\"hidden\" name=\"person_id\" value=\"$person_id\">
            <input type=\"hidden\" name=\"band_id\" value=\"$band_id\">
            <input type=\"reset\" value=\"Clear form\">
            <input type=\"submit\" name=\"override\" value=\"Continue reservation\"></td></tr></table></form></div>";

  
?>
<html>
    <head><link href="includes/person_band_res.css" rel="stylesheet" type="text/css" />

    <script language="JavaScript" type="text/javascript">
        <!--
        function checkform ( form )
        {

        if (form.override_initials.value == "") {
            alert( "Please enter your initials to override" );
            form.override_initials.focus();
            return false ;
        }

        if (form.override_comment.value == "") {
            alert( "Please enter a comment to override" );
            form.override_comment.focus();
            return false ;
        }


             return true;
        }

//-->
</script>
</head>
    <body>
    <? include("../includes/header.inc"); ?>
    <div id="page" align="center">
    <h2 align="left">Add person to reservation</h2>

    <? Print $display_block; ?>

    </div>
    </body>

</html>