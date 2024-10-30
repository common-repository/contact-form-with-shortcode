<table width="100%" border="0">
   <tr>
    <td><label><input type="checkbox" checked="checked" disabled="disabled"/><?php _e('Include Email Field','contact-form-with-shortcode');?></label></td>
  </tr>
    <tr>
    <td><label><input type="checkbox" checked="checked" disabled="disabled"/><?php _e('Email Required','contact-form-with-shortcode');?></label></td>
  </tr>
   <tr>
    <td><label><input type="checkbox" name="include_name_in_subscription" value="Yes" <?php echo $include_name_in_subscription=="Yes"?'checked="checked"':'';?> /><?php _e('Include Name Field','contact-form-with-shortcode');?></label></td>
  </tr>
  <tr>
    <td><label><input type="checkbox" name="name_in_subscription_required" value="Yes" <?php echo $name_in_subscription_required=="Yes"?'checked="checked"':'';?> /><?php _e('Name Required','contact-form-with-shortcode');?></label></td>
  </tr>
</table>