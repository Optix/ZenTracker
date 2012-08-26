<div id="ajax">
  <h2>
    <img src="/images/icones/32x32/email_edit.png" width="24" />
    <?=__("Change my e-mail address")?>
  </h2>
  
  <form method="post" action="<?=url_for("membres/options?page=email")?>">
    <table>
      <tr>
        <td>
          <label for="actuelle"><?=__("Current e-mail address :")?> </label>
        </td>
        <td>
          <input type="text" value="<?=$email[0]['email']?>" name="actuelle" id="mactuelle" disabled="disabled" />
        </td>
      </tr>
      <tr>
        <td>
          <label for="nouveau"><?=__("New e-mail address :")?> </label>
        </td>
        <td>
          <input type="text" value="" name="nouveau" id="nouveau" />
        </td>
      </tr>
      <tr>
        <td colspan="2" style="text-align:center">
          <input type="submit" value="<?=__("Change my e-mail address")?>">
        </td>
      </tr>
    </table>
  </form>
</div>