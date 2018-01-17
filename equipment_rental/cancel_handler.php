<?php
/* 
 * PHP Cancel Reservation Handler
 * 03-25-2009
 * osmerg
 *
 * 03-31-2009  add employee intial requirements
 * marline
 */
include ("../includes/dbconnect.inc");
include ("../reservation/themes/default.inc");

$reservation_id = $_GET['reservation_id'];
$cancel_type    = $_GET['cancel_type'];
$cancel_initials = $_GET['cancel_initials'];
$cancel_comment = $_GET['cancel_comment'];
?>

<html>
    <head>
        <link href="../includes/person_band.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <? include("../includes/header.inc") ?>
        <div id="page" align="center">
            <?php

            if ( isset($cancel_type) )
            {   
                //update is_cancelled to TRUE
                $sql = "UPDATE reservation_entry
                                   SET is_cancelled = 1,
						   cancellation_initials = '$cancel_initials',
                                       cancellation_comments = '$cancel_comment'
                                      WHERE id = $reservation_id";

                mysql_query($sql) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );


                echo "SUCCESSFULLY CANCELLED RESERVATION";?><br /><br />
                <?php
                echo "<a href=\"view_all.php\">View All Reservations</a>";
            }
            ?>
        </div>
    </body>
</html>