<?php
  /*
   * View All Reservations
   * 03-21-2009
   * Osmerg
   */
include ("../includes/dbconnect.inc");
include ("../includes/functions.inc");
include ("../reservation/themes/default.inc");

$res_id=$_GET['res_id'];

// show all reservations with equip and not rooms in the future
$new_query="SELECT re.id AS res_id, p.fname AS fname, p.lname AS lname, start_time, end_time, reservation_status
              FROM reservation_entry re, person p
             WHERE re.person_id = p.id
               AND re.room_id IS NULL
               AND re.is_cancelled = 0
               AND reservation_status != 'CLOSED'
            ORDER BY p.lname, p.fname, start_time";

// die and show mysql error number and messages, if there is any error with query
$result = mysql_query($new_query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

?>
<html>
    <head>
        <link href="../includes/person_band.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <? include("../includes/header.inc") ?>
        <div id="page" align="center">
            <h2 align="left">View all reservations</h2>
            <!-- Start form -->
            <form method="GET" name="view_all_form" action="handler.php" >
                <?php
                if (mysql_num_rows($result)>0)
                {
                    echo "<table border=\"1\" cellpadding=\"5\" cellspacing=\"0\">";
                    echo "<tr>";
                    echo "<th>Last Name</th>";
                    echo "<th>First Name</th>";
                    echo "<th>Start Time</th>";
                    echo "<th>End Time</th>";
                    echo "<th>Status</th>";
                    echo "<th>Action</th>";
                    echo "</tr>";

                    while($row = mysql_fetch_array($result))
                    {
                        $res_status=$row['reservation_status'];
                        $res_id=$row['res_id'];
                        $fname=$row['fname'];
                        $lname=$row['lname'];
                        $start_time=$row['start_time'];
                        $end_time=$row['end_time'];

                        list($disp_start_date, $disp_start_time) = split(' ', utf_period_2_date($start_time));
                        list($disp_end_date, $disp_end_time) = split(' ', utf_period_2_date($end_time));

                        //print rows
                        echo "<tr>";
                        echo "<td>$lname</td>";
                        echo "<td>$fname</td>";
                        echo "<td>";
                        echo $disp_start_date;
                        echo "</td>";
                        echo "<td>";
                        echo $disp_end_date;
                        echo "</td>";
                        echo "<td>$res_status</td>";
                        echo "<td><a href=view_detail.php?id=$res_id>View Detail</a><br/></td>";
                        echo "</tr>";
                    }
                    echo "</table>";

                }
                else
                {
                    die('Error getting customer from table reservation_entry');
                }

                ?>
            </form>
        </div>
    </body>
</html>