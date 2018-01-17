<html>
<table border="0" algin="center" valign="top" width="100%">
  <tr>
    <td align="center" valign="middle" nowrap>
      &nbsp;
    </td>
  </tr>
  <tr>
    <td align="center" valign="middle" nowrap>
<div class="SN">
      <table class="formTable" align="center" valign="middle" cellpadding="3" cellspacing="0">
        <tr>
          <td class="listHeader" align="right" valign="middle" nowrap>
          </td>
        </td>
        <tr>
          <td class="listHeader" align="right" valign="middle" nowrap>
            &nbsp; <a class="help" href="" onClick="window.open('/speednet/sphelp/All_Default.htm#','GeneratedSpeedPortalWindow','menubar=no,width=550,height=400,resizable,toolbar=no,location=no,directories=no,scrollbars');return false;"> Help
          </td>
        </tr>

        <tr>
          <td class="bodyColor" align="center" valign="middle" nowrap>
            <table border="0" align="center" valign="middle">
              <tr>
                <td align="center" valign="middle" nowrap>
                  <font class="labelClean">
<?
#  If there is any length to the message
#  body text, then display the text
#
if ( strlen( trim( $msgGenText ) ) )
{
?>

                    &nbsp;&nbsp;&nbsp; <?=$msgGenText?> &nbsp;&nbsp;&nbsp;<br>
<?
}
?>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr class="actionRow">
          <td align="center" valign="middle">
            <button class="formButton" onclick="history.back();">&nbsp;&nbsp; Cancel &nbsp;&nbsp;</button>
            &nbsp;
          </td>
        </tr>
      </table>
    </tr>
  </tr>
<!/table>	<!left out on purpose--causes footer to be pushed down otherwise
</div>
