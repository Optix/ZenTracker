<div id="ajax">
  <h2>
    <img src="/images/icones/32x32/key_go.png" width="24" />
    <?=__("Change my password")?>
  </h2>
  
  <form method="post" action="<?=url_for("membres/options?page=password")?>">
    <table>
      <tr>
        <td>
          <label for="mdp_actuel"><?=__("Current password :")?> </label>
        </td>
        <td>
          <input type="password" value="" name="mdp_actuel" id="mdp_actuel" />
        </td>
      </tr>
      <tr>
        <td>
          <label for="mdp_nouveau"><?=__("New password :")?> </label>
        </td>
        <td>
          <input type="password" value="" name="mdp_nouveau" id="mdp_nouveau" />
        </td>
      </tr>
      <tr>
        <td>
          <label for="mdp_confirm"><?=__("Retype it again :")?> </label>
        </td>
        <td>
          <input type="password" value="" name="mdp_confirm" id="mdp_confirm" />
        </td>
      </tr>
      <tr>
        <td colspan="2" style="text-align:center">
          <input type="submit" value="<?=__("Change my password")?>">
        </td>
      </tr>
    </table>
  </form>
</div>