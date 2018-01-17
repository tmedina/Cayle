<?php
    /*
     * View Details of Reservation
     * 03-21-2009
     * osmerg
     */
include ("../includes/dbconnect.inc");
include ("../includes/functions.inc");
//include ("../reservation/themes/default.inc");
include ("../includes/config.inc");

// get reservation id
$res_id = $_GET['id'];
// get reservation status
$reservation_status = $_GET['reservation_status'];

if ( ! isset( $res_id ))
{
    die ("Error: Missing Reservation ID");
}

//DEBUG
//echo "reservation id: $res_id <br/>";

?>
<html>
    <head>
        <link href="../includes/person_band.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <?php
        include ("../includes/header.inc");
        ?>
        <div id="page" align="center">
            <h2 align="left">Reservation details</h2>
            <!-- Start form -->
            <div id="text" align="left">
                <form method="GET" name="view_detail_form" action="handler.php" >
                    <?php
                    // check if the reservation has any equipment
                    //
                    $query = "SELECT count(*) AS res_count
                                                       FROM reservation_transaction
                                                           WHERE reservation_entry_id = $res_id
                                                           AND equipment_id IS NOT NULL";

                    // die and show mysql error number and messages, if there is any error with query
                    $result = mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );
                    $row = mysql_fetch_array($result);
                    if ( $row['res_count'] < 1 )
                    {
                        echo "No equipment on this reservation<br>";
                    }
                    else
                    {
                        // get the customer and equipment info along with start and end time
                        $query = "SELECT pe.fname, pe.lname, eq.equip_description, eq.id as equip_id, re.start_time, re.end_time, re.reservation_status, re.room_id
                                                            FROM reservation_entry re, person pe, reservation_transaction rt, equipment eq
                                                                WHERE re.person_id = pe.id
                                                                AND re.id = rt.reservation_entry_id
                                                                AND eq.id = rt.equipment_id
                                                                AND re.id = $res_id";

                        // die and show mysql error number and messages, if there is any error with query
                        $result = mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

                        if (mysql_num_rows($result)>0)
                        {
                            echo"<table border='1' cellspacing='0' cellpadding='5' bordercolor='#003366' border-style='solid'>";
                            echo"<tr>";
                            echo"<th>First name</th>";
                            echo"<th>Last name</th>";
                            echo"<th>Description</th>";
                            echo"<th>Start time</th>";
                            echo"<th>End time</th>";
                            echo"<th>Action</th>";
                            echo"</tr>";

                            while($row = mysql_fetch_array($result))
                            {
                                $fname=$row['fname'];
                                $lname=$row['lname'];
                                $eq_description=$row['equip_description'];
                                $eq_id=$row['equip_id'];
                                $res_start_time=$row['start_time'];
                                $res_end_time=$row['end_time'];
                                $reservation_status=$row['reservation_status'];
                                $room_id=$row['room_id'];

                                list($disp_start_date, $disp_start_time) = split(' ', utf_period_2_date($res_start_time));
                                list($disp_end_date, $disp_end_time) = split(' ', utf_period_2_date($res_end_time));

                                echo"<tr>";
                                echo"<td>$fname</td>";
                                echo"<td>$lname</td>";
                                echo"<td>$eq_description</td>";
                                echo"<td>";
                                echo $disp_start_date;
                                echo "</td>";
                                echo "<td>";
                                echo $disp_end_date;
                                echo "</td>";
                                echo"<td><a href=action.php?action=delete&amp;reservation_id=$res_id&amp;equip_id=$eq_id onclick=\"return confirm('Are you sure you want to delete?')\">Delete</a></td>";
                                echo"</tr>";
                                // show the customer and list of equipments in the reservation
                            }
                            echo"</table>";

                        }
                        else
                        {
                            die('Error getting customer from table reservation_entry');
                        }
                    }
                    ?>

                    <br/>

                    <?php

                    echo "<a href=add.php?action=add&amp;reservation_id=$res_id&amp;start_time=$res_start_time&amp;end_time=$res_end_time>Add more equipment |</a> ";

                    //if res status equals open show check_in link AND
                    //if room_id does not exist
                    if ( $reservation_status == $OPEN && $room_id == ""){
                        echo "<a href=action.php?action=check_in&amp;reservation_id=$res_id onclick=\"return confirm('Are you sure you want to check-in?');\">Check In</a>";
                        echo " | ";
                        echo "<a href=action.php?action=cancel&amp;reservation_id=$res_id>Cancel Reservation</a>";

                    }

                    //if status equals in_progress display check_out link AND
                    //if room_id does not exist
                    elseif ( $reservation_status == $IN_PROGRESS && $room_id == ""){
                        echo "<a href=action.php?action=check_out&amp;reservation_id=$res_id onclick=\"return confirm('Are you sure you want to check-out?');\">Check Out</a>";
                    }

                    //if status equals unpaid or closed show invoice link AND
                    //if room_id does not exist
                    elseif ( $reservation_status == $UNPAID || $reservation_status == $CLOSED) {
                        echo "<a href=../checkout/invoice.php?reservation_id=$res_id>Invoice</a>";
                    }

                    //query for room_id
                    $query = "SELECT room_id FROM reservation_entry
                                                    WHERE id=$res_id";

                    // die and show mysql error number and messages, if there is any error with query
                    $result = mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

                    if (mysql_num_rows($result)>0)
                    {
                        while($row = mysql_fetch_array($result)){
                            $room_id = $row['room_id'];
                        }

                    }

                    //if room_id exists
                    if ($room_id > 0){
                        echo "<a href=../reservation/view_entry.php?id=$res_id>Complete</a>";
                    }

                    ?>
                </form>
            </div>
        </div>
    </body>
</html>



