<table width="100%" border="0">
  <tr>
    <td><strong><?php _e('Subject','contact-form-with-shortcode');?></strong></td>
  </tr>
  <tr>
    <td><input type="text" name="subscribe_subject" value="<?php echo $subscribe_subject;?>" class="widefat" /></td>
  </tr>
  <tr>
    <td><strong><?php _e('To (Admin)','contact-form-with-shortcode');?></strong> <a href="#" class="tool" title="Left this blank if you don't want the admin to get a mail when a user subscribe for this newsletter.">?</a></td>
  </tr>
  <tr>
    <td><input type="text" name="subscribe_to_admin" value="<?php echo $subscribe_to_admin;?>" class="widefat"/></td>
  </tr>
   <tr>
    <td><strong><?php _e('From Name (User)','contact-form-with-shortcode');?></strong> <a href="#" class="tool" title="From name for the email, that user receives.">?</a></td>
  </tr>
  <tr>
    <td><input type="text" name="from_name" value="<?php echo $from_name;?>" class="widefat" /></td>
  </tr>
  <tr>
    <td><strong><?php _e('From Mail (User)','contact-form-with-shortcode');?></strong> <a href="#" class="tool" title="From mail for the email, that user receives.">?</a></td>
  </tr>
  <tr>
    <td><input type="text" name="from_mail" value="<?php echo $from_mail;?>" class="widefat" /></td>
  </tr>
</table>