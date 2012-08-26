<div id="ajax">
  <h2>
    <img src="/images/icones/32x32/palette.png" width="24" />
    <?=__("Change my layout")?>
  </h2>
  
  <form method="post" action="<?=url_for("membres/options?page=design")?>">
    <table>
      <tr>
        <td>
          <input type="radio" name="design" value="main_black.css">
        </td>
        <td>
          <strong><?=__("Dark version")?></strong> : <?=__("design by default")?>
        </td>
      </tr>
      <tr>
        <td>
          <input type="radio" name="design" value="main.css">
        </td>
        <td>
          <strong><?=__("Light version")?></strong> : <?=__("alternative design")?>
        </td>
      </tr>
      <tr>
        <td colspan="2" style="text-align:center">
          <input type="submit" value="<?=__("Change my layout")?>">
        </td>
      </tr>
    </table>
  </form>
  
  <p><?=__("These settings are stored on your computer through cookies. The default design is automatically set if the cookie is erased.")?></p>
  
  <h2>
    <img src="/images/icones/32x32/palette.png" width="24" />
    <?=__("Navigation Options")?>
  </h2>
  
  <ul>
    <li>
      <strong><?=__("Remove the center column when viewing a upload")?> : </strong>
        <a href="javascript:localStorage.setItem('prez-fullscreen', '1');alert('<?=__("Enabled")?>');"><?=__("Enable")?></a> | 
        <a href="javascript:localStorage.setItem('prez-fullscreen', '2');alert('<?=__("Disabled")?>');"><?=__("Disable")?></a>
    </li>
  </ul>
  
  <h2>
    <img src="/images/icones/32x32/mouse.png" width="24" />
    <?=__("Browsing mode")?>
  </h2>
  
  <p style="text-align:center">
    <strong><?=__("You can choose how to browse on this website. Do you prefer to click to load a page or to let the website do it for you when do you hovering ?")?></strong><br />
    <a href="javascript:localStorage.setItem('browsing', '1');alert('OK');" style="font-size: 14pt">
      <img src="/images/icones/16x16/mouse_select_left.png" /> 
<?=__("Click")?>
    </a> 
    <a href="javascript:localStorage.setItem('browsing', '0');alert('OK');" style="font-size: 14pt">
      <img src="/images/icones/16x16/mouse_2.png" /> 
      <?=__("Hover")?>
    </a>
  </p>
</div>