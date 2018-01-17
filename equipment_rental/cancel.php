<?php
/* 
 * Cancel the Reservation
 * 03-25-2009
 * Osmerg
 */

include ("../includes/dbconnect.inc");
include ("../reservation/themes/default.inc");

$reservation_id = $_GET['reservation_id'];

?>

<html>
    <head>
        <link href="../includes/person_band.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <? include("../includes/header.inc") ?>
        <div id="page" align="left">
            <form class="form_cancel" id="main" action="cancel_handler.php" method="get">

                <div id="div_cancellation_type">
                    <label for="rooms">Select cancellation type: </label>
                    <select id="rooms" name="cancel_type">
                        <?php
                        $sql = "SELECT mc.id, mc.name, mc.amount
                                     FROM misc_charge mc, misc_charge_type mct
                                         WHERE mc.misc_charge_type_id = mct.id
                                         AND mc.is_active = 1
                                         AND mct.name = 'cancellation'
                                              ORDER BY mc.name";

                        // die and show mysql error number and messages, if there is any error with query
                        $res = mysql_query($sql) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

                        if (mysql_num_rows($res)>0)
                        {
                            while($row=mysql_fetch_array($res))
                            //loop: fetch array and assign variable to each result
                            {
                                echo "<option value=\"".$row['id']."\">".$row['name']." ($".$row['amount'].")</option>\n";

                            }
                        }
                        ?>
                    </select>
                </div>
                <br /><br />
			<div id="div_cancellation_initials">
                         <label for="rooms">Enter your initials: </label>
			  <input type="text" size=2 name="cancel_initials" maxlength="3" value="" >
			</div>
                <div id="div_cancellation_comment">
                    <label for="rooms">Enter comment: </label>
                    <textarea name="cancel_comment" rows="5" cols="25"></textarea>
                </div>
                <div id="div_hidden">
                    <input type="hidden" name="reservation_id" value="<?php echo $reservation_id?>">
                </div>
                <br /><br /><br /><br />
                <div id="div_submit">
                    <input type="submit" value="Submit">
                </div>
            </form>
        </div>
    </body>
</html>
