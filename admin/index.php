<?php
// admin index page
// 20090409 wilson: initial
//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}

include ("../includes/dbconnect.inc");
//include ("../includes/header.inc");
?>
<html>
<head>
<link href="../includes/person_band.css" rel="stylesheet" type="text/css" />
</head>
<body>
<? include ("../includes/header.inc"); ?>

<h2>&nbsp;&nbsp;Admin menu</h2>

<?php
    if ( $_SESSION["is_admin"] != 1 )
    {
        $username = $_SESSION["username"];
?>
        <fieldset>
        <legend style="font-weight:bold">Reset Password</legend>
        <div id="admin" style="border:1px">
        <a href="../person_module_admin/reset_password.php?username=<?php echo $username ?>">Reset Password</a>
        </div>
        </fieldset>
        <br>
<?php
        exit (0);
    }
?>
<fieldset>
<legend style="font-weight:bold">Equipment inspection</legend>
<div id="developer" style="border:1px">
<a href="../admin/inspection_admin.php">Inspect broken equipment</a>
</div>
</fieldset>
<br>
<fieldset>
<legend style="font-weight:bold">Person/Band</legend>
<div id="admin" style="border:1px">
<a href="../person_module_admin/band.php">Band administration</a><br />
<a href="../person_module_admin/index.php">Person administration</a><br />
<a href="../person_module_admin/reset_password.php">Reset employee/administrator password</a>
</div>
</fieldset>
<br>

<fieldset>
<legend style="font-weight:bold">Reports</legend>
<div id="admin" style="border:1px">
<b>Unpaid Reservations</b><br />
    <a href="../reports/unpaid.php">Unpaid reservations</a><br /><br/>

<b>Cancellations</b><br />
    <a href="../reports/cancellations.php">Cancellation transactions</a><br />
    <a href="../reports/cancellations_by_person.php">Cancellations by person</a><br />
    <a href="../reports/total_cancelled_hours_room.php">Total cancelled hours per room</a><br />
    <br />

    <b>Equipment</b><br />
    <a href="../reports/equip_only_res_by_person_inhouse.php">In-house equipment reservations by person</a><br />
    <a href="../reports/equip_only_res_by_person.php">Off-site equipment reservations by person</a><br />
    <a href="../reports/total_equipment_rentals.php">Total rentals per rental item</a><br />

    <br />

    <b>Miscellanaeous transactions</b><br />
    <a href="../reports/total_barcharge.php">Bar charge items sold</a><br />
    <a href="../reports/barcharges_by_person.php">Bar charges by person</a><br />
    <a href="../reports/discounts.php">Discount transactions</a><br />
    <a href="../reports/discounts_by_person.php">Discounts by person</a><br />
    <a href="../reports/waivers.php">Waiver transactions</a><br />
    <a href="../reports/waivers_by_person.php">Waivers by person</a><br />


    <br />

    <b>Person/Band</b><br />
    <a href="../reports/band_members.php">Band membership by band</a><br />
    <a href="../reports/band_membership.php">Band membership by person</a><br />
    <a href="../reports/show_all_person_band.php">Show all person or band records</a><br />
    <br />

    <b>Rental charges</b><br />
    <a href="../reports/inhouse_equipment_rental_charges.php">In-house equipment rental charges</a><br />
    <a href="../reports/offsite_equipment_rental_charges.php">Off-site equipment rental charges</a><br />
    <a href="../reports/room_rental_charges.php">Room rental charges</a><br />

    <br />
    <b>Revenue</b><br />
    <a href="../reports/barcharge_revenue.php">Bar charge revenue</a><br />
    <a href="../reports/offsite_equipment_revenue.php">Off-site equipment revenue</a><br />
    <a href="../reports/room_revenue.php">Room revenue including in-house equipment and bar charges</a><br />

    <br />

    <b>Room use</b><br />
    <a href="../reports/room_res_by_person.php">Room reservations by person</a><br />
    <a href="../reports/total_hours_room.php">Total rental hours per room</a><br />
</div>
</fieldset>
<br>



<fieldset>
<legend style="font-weight:bold">Table Maintenance</legend>
<div id="admin" style="border:1px">
<?php show_admin_items() ?>
</div>
</fieldset>
<br>

<fieldset>
<legend style="font-weight:bold">Developer Tools</legend>
<div id="developer" style="border:1px">
<?php show_developer_items() ?>
</div>
</fieldset>
<br>

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
