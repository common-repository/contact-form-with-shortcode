<table width="100%" border="0">
  <tr>
    <td><?php _e('Subject','contact-form-with-shortcode');?></td>
  </tr>
  <tr>
    <td><input type="text" name="newsletter_subject" value="<?php echo $newsletter_subject;?>" class="widefat" /></td>
  </tr>
   <tr>
    <td><?php _e('From Name','contact-form-with-shortcode');?> <a href="#" class="tool" title="Enter from name in email">?</a></td>
  </tr>
  <tr>
    <td><input type="text" name="from_name" value="<?php echo $from_name;?>" class="widefat"/></td>
  </tr>
  <tr>
    <td><?php _e('From Mail','contact-form-with-shortcode');?> <a href="#" class="tool" title="Enter from email address">?</a></td>
  </tr>
  <tr>
    <td><input type="text" name="from_mail" value="<?php echo $from_mail;?>" class="widefat"/></td>
  </tr>
  <tr>
    <td><?php _e('Test Mail','contact-form-with-shortcode');?> <a href="#" class="tool" title="Enter email here to send a test newsletter. If Test Mail is entered then newsletter will not be mailed to subscribers.">?</a></td>
  </tr>
  <tr>
    <td><input type="text" name="test_news_mail" value="" class="widefat" /></td>
  </tr>
</table>