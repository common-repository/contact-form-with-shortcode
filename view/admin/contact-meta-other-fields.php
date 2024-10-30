<table width="100%" border="0">
  <tr>
    <td><strong><?php _e('Subject','contact-form-with-shortcode');?></strong> <a href="#" class="tool" title="Enter Email Subject">?</a></td>
  </tr>
  <tr>
    <td><input type="text" name="contact_subject" value="<?php echo $contact_subject;?>" class="widefat"/> </td>
  </tr>
  <tr>
    <td><strong><?php _e('To','contact-form-with-shortcode');?></strong> <a href="#" class="tool" title="Enter email address, where the mail will be send. You must not leave it blank.">?</a></td>
  </tr>
  <tr>
    <td><input type="text" name="contact_to" value="<?php echo $contact_to;?>" class="widefat" /></td>
  </tr>
   <tr>
    <td><strong><?php _e('From Name','contact-form-with-shortcode');?></strong> <a href="#" class="tool" title="Enter from name in email">?</a></td>
  </tr>
  <tr>
    <td><input type="text" name="from_name" value="<?php echo $from_name;?>" class="widefat" /></td>
  </tr>
  <tr>
    <td><strong><?php _e('From Mail','contact-form-with-shortcode');?></strong> <a href="#" class="tool" title="Enter from email address">?</a></td>
  </tr>
  <tr>
    <td><input type="text" name="from_mail" value="<?php echo $from_mail;?>" class="widefat" /></td>
  </tr>
  <tr>
    <td><strong><?php _e('Reply To Field','contact-form-with-shortcode');?></strong></td>
  </tr>
  <tr>
    <td><input type="text" name="reply_to_field" value="<?php echo $reply_to_field;?>" placeholder="#email_field#" class="widefat" /><p><i>If you have an <strong>Email</strong> field in your contact from then put the field <strong>Code</strong> here. So that you can reply directly in the email chain. For example if your Email field <strong>Code</strong> is <strong>#email_field#</strong> then put it here.</i></p></td>
  </tr>
</table>