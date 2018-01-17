<?php
// admin index page
// 20090409 wilson: initial

include ("../includes/dbconnect.inc");
//include ("../includes/header.inc");
?>
<html>
<head>
<link href="../includes/person_band.css" rel="stylesheet" type="text/css" />
</head>
<body>
<? include ("../includes/header.inc"); ?>
<br>
<div>
    <b><u>Help Menu</u></b><br>
    <a href="../admin/help/faqs.pdf">Frequently Asked Questions</a><br>
    <a href="../admin/help/maneuvering_the_calendar.pdf">Maneuvering Around the Calendar</a><br>
    <a href="../admin/help/room_reservation.pdf">Make a Room Reservation</a><br><br>
    <a href="../admin/help/cancel_a_reservation.pdf">Cancel a Reservation</a><br>
    <a href="../admin/help/checkin_checkout_invoice.pdf">Checkin, Checkout, and Invoice</a><br>
    <a href="../admin/help/equipment_rental_inspection.pdf">Equipment Rental and Inspection</a><br><br>
    <a href="../admin/help/Create_person_band.pdf">Create Person and Band</a><br>
    <a href="../admin/help/person_band_admin.pdf">Activate and Deactive Person and Band</a><br>
    <a href="../admin/help/admin_functions.pdf">Administrative Functions</a><br>


</div>

</body></html>

<?php
// Functions definition
//
function show_developer_items()
{
    $query = "SELECT * FROM tm_table where menu_permissions = 'developer'";

    // die and show mysql error number and messages, if there is any error with query
    $result = mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

    if (mysql_num_rows($result)>0)
    {
        while($row = mysql_fetch_array($result))
        {
            $tbl_name = $row['tbl_name'];
            $tbl_label = $row['tbl_label'];
            echo "<a href='tm_editor.php?tbl_name=$tbl_name&tbl_label=$tbl_label&back_page=index.php'>$tbl_label</a>";
            echo "<br/>";
        }
    }
    else
    {
         echo "No developer items available.";
    }
}

function show_admin_items()
{
    $query = "SELECT * FROM tm_table where menu_permissions = 'admin'";

    // die and show mysql error number and messages, if there is any error with query
    $result = mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

    if (mysql_num_rows($result)>0)
    {
        while($row = mysql_fetch_array($result))
        {
            $tbl_name = $row['tbl_name'];
            $tbl_label = $row['tbl_label'];
            echo "<a href='tm_editor.php?tbl_name=$tbl_name&tbl_label=$tbl_label&back_page=index.php'>$tbl_label</a>";
            echo "<br/>";
        }
    }
    else
    {
         echo "No admin items available.";
    }
}

?>
