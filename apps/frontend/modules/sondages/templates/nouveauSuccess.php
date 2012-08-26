<div id="ajax">
  <h2>
    <img src="/images/icones/32x32/chart_bar_add.png" width="24" />
    <?=__("Add a new poll")?>
  </h2>
  <style>
    #droite form input, #droite form textarea {
      width: 250px;
    }
  </style>
  <form id="new" method="post" action="<?=url_for("sondages/nouveau")?>">
    <table>
      <tr>
        <td>
          <label for="titre"><?=__("Title")?></label>
        </td>
        <td>
         <input type="text" name="titre" id="titre" required="required" /> 
        </td>
      </tr>
      <tr>
        <td colspan="2" style="text-align: center" class="btn_actions">
          <a href="" id="add_choice">
            <img src="/images/icones/16x16/add.png" />
            <?=__("Add a choice")?>
          </a>
        </td>
      </tr>
      <tr data-cid="1" class="choice">
        <td>
          <label for="choice1">1</label>
        </td>
        <td>
          <input type="text" name="choice1" id="choice1" required="required" /> 
        </td>
      </tr>
      <tr data-cid="2" class="choice">
        <td>
          <label for="choice2">2</label>
        </td>
        <td>
          <input type="text" name="choice2" id="choice2" required="required" /> 
        </td>
      </tr>
      <tr class="trSubmit">
        <td colspan="2" style="text-align:center">
          <input type="submit" value="<?=__("Send")?>" />
        </td>
      </tr>
    </table>
  </form>
</div>