<?php
//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}


?>
<html>
<head>
    <link href="../includes/person_band.css" rel="stylesheet" type="text/css" />
</head>
<body>

    <b>Cancellations</b><br />
    <a href="reports/total_cancelled_hours_room.php">Total cancelled hours per room</a><br />
    <br />
    
    <b>Equipment</b><br />
    <a href="total_equipment_rentals.php">Total rentals per rental item</a><br />

    <br />
    
    <b>Miscellanaeous transactions</b><br />
    <a href="discounts.php">Discount transactions</a><br />
    <a href="total_barcharge.php">Total bar charge items sold</a><br />
    <a href="waivers.php">Waiver transactions</a><br />


    <br />

    <b>Person/Band</b><br />
    <a href="band_members.php">Band membership by band</a><br />
    <a href="band_membership.php">Band membership by person</a><br />
    <a href="barcharges_by_person.php">Bar charges by person</a><br />
    <a href="cancellations_by_person.php">Cancellations by person</a><br />
    <a href="discounts_by_person.php">Discounts by person</a><br />
    <a href="equip_only_res_by_person.php">Off-site equipment reservations by person</a><br />
    <a href="room_res_by_person.php">Room reservations by person</a><br />
    <a href="..reports/waivers_by_person.php">Waivers by person</a><br />

    <br />

    <b>Room use</b><br />
    <a href="total_hours_room.php">Total rental hours per room</a><br />


    <br />
   
</body>
</html>