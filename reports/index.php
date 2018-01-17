<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include ("../includes/dbconnect.inc");
include ("../includes/functions.inc");
include ("../includes/header.inc");
include ("../reservation/themes/default.inc");

$reservation_id = $_GET['reservation_id'];
?>
<html>
<head>
<style>
        body {font-size: small;
    color:            <?php echo $standard_font_color ?>;
    font-family:      <?php echo $standard_font_family ?>;
    background-color: <?php echo $body_background_color ?>}

.current {color: <?php echo $highlight_font_color ?>}                        /* used to highlight the current item */
.error   {color: <?php echo $highlight_font_color ?>; font-weight: bold}     /* for error messages */

h1 {font-size: x-large}
h2 {font-size: large}

a:link    {color: <?php echo $anchor_link_color ?>;    text-decoration: none; font-weight: bold}
a:visited {color: <?php echo $anchor_visited_color ?>; text-decoration: none; font-weight: bold}
a:hover   {color: <?php echo $anchor_hover_color ?>;   text-decoration: underline; font-weight: bold}
    </style>
</head>
<h3>Room Usage Report</h3>
<div id="reservation_info" style="border:1px">
<?php include ("test_room_usage_rpt.php"); ?>
</div>

</html>