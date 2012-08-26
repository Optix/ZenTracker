<div id="ajax">
  <h2>
    <img src="/images/icones/32x32/email_add.png" width="24" />
    <?=__("Write a new message")?>
  </h2>
  <style>
    #droite form input, #droite form textarea {
      width: 250px;
    }
  </style>
  <form method="post" action="<?=url_for("messages/nouveau")?>">
    <table>
      <tr>
        <td>
          <label for="titre"><?=__("Title")?> : </label>
        </td>
        <td>
          <input type="text" name="titre" id="titre" />
        </td>
      </tr>
      <tr>
        <td>
          <label for="msg"><?=__("Message")?> : </label>
        </td>
        <td>
          <textarea name="msg" id="msg" style="width: 290px;height: 100px"></textarea>
        </td>
      </tr>
      <tr>
        <td>
          <label for="dest"><?=__("Recipient")?> : </label>
        </td>
        <td>
          <input type="text" name="dest" id="dest" />
        </td>
      </tr>
      <tr>
        <td colspan="2" style="text-align: center"><div id="destres" class="participants"></div></td>
      </tr>
      <tr>
        <td colspan="2" style="text-align:center">
          <input type="submit" value="<?=__("Send")?>" />
        </td>
      </tr>
    </table>
  </form>
</div>