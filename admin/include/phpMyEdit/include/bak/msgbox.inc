<div id="floatMsg" style="z-index: 100; padding: 10px; float: center; position: relative;">
<table border="0" algin="center" valign="middle" width="100%">
  <tr>
    <td align="center" valign="middle" nowrap>
      &nbsp;
    </td>
  </tr>
  <tr>
    <td align="center" valign="middle" nowrap>
      <table class="formTable" align="center" valign="middle" cellpadding="3" cellspacing="0">
        <tr>
          <td class="listHeader" align="right" valign="middle" nowrap>
            &nbsp;
          </td>
        </td>
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
if ( strlen( trim( $msgBoxText ) ) )
{
?>
                    &nbsp;&nbsp;&nbsp; <?=$msgBoxText?> &nbsp;&nbsp;&nbsp;<br>
<?
}
?>
                    &nbsp;&nbsp;<img border="0" src="images/Processing2.gif" hspace="0" vspace="0">&nbsp;&nbsp;
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
    </td>
  </tr>
</table>
</div>

