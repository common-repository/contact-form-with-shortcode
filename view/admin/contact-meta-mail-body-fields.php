<table width="100%" border="0">
  <tr>
    <td><strong><?php _e('Mail body to Admin', 'contact-form-with-shortcode');?></strong> (<i><?php _e('Attachments will be automatically attached.', 'contact-form-with-shortcode');?></i>)</td>
  </tr>
  <tr>
    <td><textarea name="contact_mail_body" class="widefat"  style="height:200px;" placeholder="Example: Name: #user_name#, Email: #user_email#, Phone: #user_phone#"><?php echo $contact_mail_body; ?></textarea><br>
    <span class="custom-field-label">Use Mail Body Codes created in the <strong>Contact Form Fields</strong></span>
    <br>
    <strong>Example:</strong> Name: #user_name#, Email: #user_email#, Phone: #user_phone#
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong><?php _e('Mail body to User', 'contact-form-with-shortcode');?></strong></td>
  </tr>
  <tr>
    <td>
    <textarea name="contact_mail_body_user" class="widefat" style="height:200px;" placeholder="Thank you for your interest. We will contact you as soon as possible."><?php echo $contact_mail_body_user; ?></textarea></td>
  </tr>
  <tr>
    <td>
     <i>If you want to send a <strong>Thank You</strong> email to the user then make sure you have an email field in your contact form. And don't forget to enter the email field <strong>Code</strong> to <strong>Reply To Field</strong> section.</i>
     <br><br>
     <span class="custom-field-label">Use Mail Body Codes created in the <strong>Contact Form Fields</strong></span>
     <br>
     <strong>Example:</strong> Name: #user_name#, Email: #user_email#, Phone: #user_phone#
     <br><br>
     HTML tags can be used in mail body.</td>
  </tr>
</table>