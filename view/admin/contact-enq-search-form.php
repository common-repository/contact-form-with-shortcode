<form name="s" action="" method="get">
<input type="hidden" name="page" value="<?php echo $this->plugin_page_base; ?>" />
<input type="hidden" name="search" value="contact_data_search" />
<table width="100%" border="0" class="ap-table">
  <tr>
    <td align="left">
    <select id="c_form_id" name="c_form_id">
        <option value="">-</option>
        <?php $this->contact_form_selected(sanitize_text_field($_REQUEST['c_form_id']));?>
    </select>
    <input type="submit" name="submit" value="Filter" class="button"/>
    </td>
  </tr>
</table>
</form>