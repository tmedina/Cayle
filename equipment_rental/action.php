<?php
/* 
 * PHP Action Class
 * 03-22-2009
 * osmerg
 */

include ("../includes/dbconnect.inc");
include ("../includes/functions.inc");
include ("../includes/config.inc");
//include("../includes/header.inc");
//include("../reservation/themes/default.inc");

$action         = $_GET['action'];
$reservation_id = $_GET['reservation_id'];
$equip_id       = $_GET['equip_id'];
$cc_num         = $_GET['cc_num'];
$cc_submit      = $_GET['cc_submit'];
$cr_type        = $_GET['cr_type'];
$cc_mo          = $_GET['cc_mo'];
$cc_day         = $_GET['cc_day'];
$cc_yr          = $_GET['cc_yr'];

// get current time and store in utf format
$day   = date("d");
$month = date("m");
$year  = date("Y");
$hour  = date("H");
$minute = date("i");
$curr_utf_time = date_2_utf_date($year, $month, $day, $hour, $minute );

switch ($action) {
    case "cancel":
        header ("Location: cancel.php?reservation_id=$reservation_id");
        break;

    case "delete":
        $sql = "DELETE FROM reservation_transaction
                      WHERE reservation_entry_id = $reservation_id
                        AND equipment_id = $equip_id";

        // die and show mysql error number and messages, if there is any error with query
        $result = mysql_query($sql) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

        if ( ! mysql_query($sql) )
        {
            echo "ERROR: Failed to delete equipment (reservation $reservation_id, equipment $equip_id";
            exit(1);
        }

        header("Location: view_detail.php?id=$reservation_id");
        break;

    case "check_in":
        $sql = "UPDATE reservation_entry
                  SET actual_start_time = $curr_utf_time, reservation_status = 'IN_PROGRESS'
                  WHERE id = $reservation_id";

        mysql_query($sql) or die("Error updating check in time on reservation " . ($reservation_id) . " " . mysql_error());

        if(!isset($cc_submit)){

            ?>
<html>
    <body>
        <head>
            <link href="../includes/person_band.css" rel="stylesheet" type="text/css" />
        </head>
        <?php
        include ("../includes/header.inc");
        ?>

        <!--
        Form validation - verify person enters credit card number
        -->
        <script language="JavaScript" type="text/javascript">

            function checkCardNumber()
            {
                if ( document.cc_info.cc_num.value.length < 16 )
                {
                    alert ( "Please enter a 16 digit numeric value." );
                    return false;
                }

                return true;
            }

            //-->
        </script>

        <div id="page" align="left">
            <h2 align="left">Credit card information</h2><br/>
            <form name="cc_info" method="GET" action="action.php" onsubmit="return checkCardNumber();">
                Credit Card Number:

                <input type="text" name="cc_num" size="18" maxlength="16"><br /><br />
                <?php
                $sql = "SELECT id, cc_type FROM credit_card_type
                                         WHERE is_active = 1";

                $result = mysql_query($sql) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );
                if (mysql_num_rows($result)>0)
                {
                    ?>
                Type: <select name="cr_type">
                    <?php
                    while($row = mysql_fetch_array($result))
                    {
                        ?>
                    <option value=<?php echo $row ['id']; ?>> <?php echo $row ['cc_type']?></option>
                    <?php
                }
                ?>
                </select><br /><br />
                <?php
            }
            ?>

                <h3>Expiration date:</h3>
                <select name="cc_mo">
                    <option value="0">Month</option>
                    <option value="01">January</option>
                    <option value="02">February</option>
                    <option value="03">March</option>
                    <option value="04">April</option>
                    <option value="05">May</option>
                    <option value="06">June</option>
                    <option value="07">July</option>
                    <option value="08">August</option>
                    <option value="09">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
                <select name="cc_yr">
                    <option value="0">Year</option>
                    <option value="2009">2009</option>
                    <option value="2010">2010</option>
                    <option value="2011">2011</option>
                    <option value="2012">2012</option>
                    <option value="2013">2013</option>
                    <option value="2014">2014</option>
                    <option value="2015">2015</option>
                </select>
                <input type="hidden" name="cc_day" value="01">
                <input type="hidden" name="reservation_id" value="<?php echo $reservation_id ?>">
                <input type="hidden" name="action" value="<?php echo $action ?>">
                <input type="submit" name="cc_submit" value="Submit">
            </form>
        </div>
    </body>
</html>

            <?php
        }
        else {
            $sql = "SELECT person_id FROM reservation_entry
                        WHERE id=$reservation_id";

            $result = mysql_query($sql) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

            if (mysql_num_rows($result)>0)
            {
                while($row = mysql_fetch_array($result))
                {
                    $person_id = $row['person_id'];
                }
            }

            $cc_exp_date = $cc_yr . $cc_mo . $cc_day;
            $sql = "INSERT INTO credit_card_info
                        (cc_number, cc_exp_date, cc_type_id, person_id, reservation_id)
                        VALUES (AES_ENCRYPT($cc_num, '$salted_pwd'), $cc_exp_date, $cr_type, $person_id, $reservation_id)";

            $result = mysql_query($sql) or die("Error updating credit card information " . ($reservation_id) . " " . mysql_error());
            header("Location: view_detail.php?id=$reservation_id");
        }
        break;

        case "check_out":
            $sql = "UPDATE reservation_entry
                        SET actual_end_time = $curr_utf_time, reservation_status = 'UNPAID'
                        WHERE id = $reservation_id";

            mysql_query($sql) or die("Error updating check out time on reservation " . ($reservation_id) . " " . mysql_error());



            // Get all equipment ids associated with reservation_id
            $sql = "SELECT equipment_id
                        FROM reservation_transaction
                        WHERE reservation_entry_id = $reservation_id
                        AND equipment_id > 0";

            $result = mysql_query($sql) or die("Error getting equipment id on reservation " . ($reservation_id) . " " . mysql_error());

            // Loop through the result and set is_awaiting_inpection to TRUE associated with this reservation
            if (mysql_num_rows($result)>0)
            {
                while($row = mysql_fetch_array($result))
                {
                    //echo "equipment id: " . $row['equipment_id'] . "<br/>";
                    // set the equipment.is_awaiting_inspection to 1
                    $sql_2 = "UPDATE equipment
                                SET is_awaiting_inspection = 1
                                WHERE id = " . $row['equipment_id'];
                    mysql_query($sql_2) or die("Error update is_awaiting_inspection " . ($reservation_id) . " " . mysql_error());
                }
            }
            header("Location: ../checkout/invoice.php?reservation_id=$reservation_id");
            ?><br /><br />
            <?php
            break;

        default:
            echo "ERROR: Invalid action!";
            exit (1);
        }

        ?>