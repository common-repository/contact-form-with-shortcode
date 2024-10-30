<form name="sub_search" action="" method="get">
<input type="hidden" name="page" value="<?php echo $this->plugin_page_base;?>" />
<input type="hidden" name="search" value="sub_search" />
<table width="100%" border="0">
  <tr>
    <td>
        <input type="text" name="form_id" value="<?php echo sanitize_text_field(@$_REQUEST['form_id']);?>" placeholder="Form ID"/>
        <input type="submit" name="submit" value="<?php _e('Filter','contact-form-with-shortcode');?>" class="button"/>
    </td>
  </tr>
</table>
</form>