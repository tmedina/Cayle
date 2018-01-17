<?php
//LAST UPDATE: 4/18/10

//Checks to see if user is logged in; if not redirect to log-in page
//session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}
?>
<table border="0" cellspacing="0" cellpadding="3" width="100%" bgcolor="#FFFFFF">
   <tr><td align="left" class="td2"><a href="../reservation/index.php"><img src="../includes/images/new_logo_left.gif" border="0" /></a></td>
    <td align="center" class="td2"><img src="../includes/images/new_logo_center.gif" /></td>
    <td align="right" class="td2"><img src="../includes/images/new_logo_right.gif" /></td></tr>
</table>

      

	<table style="border-collapse: separate"  bgcolor="#5F86B9" width="100%" cellpadding="1">
        <tr width="100%">
            <td width="15%" align="center" class="td2">
                             <a href="../view_person_band/index.php" class="link2">Person</a>
            </td>
            <td width="14%" align="center" class="td2">
                             <a href="../view_person_band/band.php" class="link2">Band</a>
            </td>
	     <!-- This links to the OLD equipment reservation system

		<td width="14%" align="center" class="td2">
                             <a href="../equipment_rental/view_all.php" class="link2">Equipment</a>
            </td>-->
             <td width="14%" align="center" class="td2">
                             <a href="https://www.google.com/accounts/ServiceLoginAuth?continue=http://www.google.com/calendar/render&service=cl&Email=equipment.nuci@gmail.com&Passwd=wdgetFight&signIn=Sign+in" class="link2" target="blank">Equipment</a>

            </td>

	
            <td width="14%" align="center" class="td2">
                             <a href="../equipment_rental/inspection.php" class="link2">Inspect</a>
            </td>
            <td width="14%" align="center" class="td2">
			     <a href="../help/index.php" class="link2">Help</a>
            </td>
            <td width="14%" align="center" class="td2">
			     <a href="../login/logout.php"class="link2">Logout</a>
            </td>
            <td width="15%" align="center" class="td2">
                	     <a href="../admin/index.php" class="link2">Admin</a>
            </td>
          </tr>
    </table>
   
