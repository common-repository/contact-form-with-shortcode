<form name="f" method="post" action="">
<input type="hidden" name="option" value="wp_contact_save_settings" />
<?php wp_nonce_field('wp_contact_save_action', 'wp_contact_save_action_field');?>
<table width="100%" class="ap-table">
  <tr>
    <td><h3><?php _e('Contact Form Settings', 'contact-form-with-shortcode');?></h3></td>
  </tr>
  <tr>
    <td>

    <div class="ap-tabs">
        <div class="ap-tab"><?php _e('Usage', 'contact-form-with-shortcode');?></div>
        <div class="ap-tab"><?php _e('SMTP', 'contact-form-with-shortcode');?></div>
        <div class="ap-tab"><?php _e('Subscription', 'contact-form-with-shortcode');?></div>
    </div>

    <div class="ap-tabs-content">
        <div class="ap-tab-content">
            <table width="100%" border="0">
          <tr>
            <td>
            <p>
            <strong>1.</strong> Create multiple contact forms for your site.<br><br>
            <strong>2.</strong> Contact forms can be displayed using <strong>Widgets</strong> and <strong>Shortcodes</strong> in your theme. Unlimited number of dynamic fields can be created in contact forms.<br><br>
            <strong>3.</strong> Dynamic fields can be easily included in the e-mail template.<br><br>
            <strong>4.</strong> Files can be uploaded as attachment in contact forms. Files will be mailed to respective Email address as Attachments. Supported file types are <strong>jpg, jpeg, png, gif, doc, docx, pdf</strong><br><br>
            <strong>5.</strong> Create Newsletter Subscription.<br><br>
            <strong>6.</strong> Send Newsletter emails to subscribers.<br><br>
            <br>
            </td>
          </tr>
        </table>
        </div>
        <div class="ap-tab-content">
            <table width="100%">
                <tr>
                    <td valign="top" colspan="2"><h3><?php _e('SMTP Setup', 'contact-form-with-shortcode');?></h3></td>
                </tr>
                <tr>
                  <td width="300"><?php _e('Enable', 'contact-form-with-shortcode');?></td>
                  <td><input type="checkbox" name="contact_enable_smtp" value="yes" <?php echo ($contact_enable_smtp == 'yes' ? 'checked="checked"' : ''); ?>></td>
                </tr>
                <tr>
                    <td valign="top" colspan="2">&nbsp;</td>
                </tr>
                <tr>
                  <td><?php _e('Host', 'contact-form-with-shortcode');?></td>
                  <td><input type="text" name="contact_smtp_host" value="<?php echo $contact_smtp_host; ?>" placeholder="<?php _e('SMTP host name', 'contact-form-with-shortcode');?>" class="widefat"></td>
                </tr>
                <tr>
                    <td valign="top" colspan="2">&nbsp;</td>
                </tr>
                <tr>
                  <td><?php _e('Port', 'contact-form-with-shortcode');?></td>
                  <td><input type="text" name="contact_smtp_port" value="<?php echo $contact_smtp_port; ?>" placeholder="25" class="widefat"></td>
                </tr>
                <tr>
                    <td valign="top" colspan="2">&nbsp;</td>
                </tr>
                <tr>
                  <td><?php _e('Username', 'contact-form-with-shortcode');?></td>
                  <td><input type="text" name="contact_smtp_username" value="<?php echo $contact_smtp_username; ?>" placeholder="<?php _e('If required', 'contact-form-with-shortcode');?>" class="widefat"></td>
                </tr>
                <tr>
                    <td valign="top" colspan="2">&nbsp;</td>
                </tr>
                <tr>
                  <td><?php _e('Password', 'contact-form-with-shortcode');?></td>
                  <td><input type="text" name="contact_smtp_password" value="<?php echo $contact_smtp_password; ?>" placeholder="<?php _e('If required', 'contact-form-with-shortcode');?>" class="widefat"></td>
                </tr>
                <tr>
                    <td valign="top" colspan="2">&nbsp;</td>
                </tr>
                <tr>
                  <td><?php _e('SMTP Secure', 'contact-form-with-shortcode');?></td>
                  <td><input type="text" name="contact_smtp_secure" value="<?php echo $contact_smtp_secure; ?>" placeholder="<?php _e('ssl / tls', 'contact-form-with-shortcode');?>" class="widefat"></td>
                </tr>
                <tr>
                    <td valign="top" colspan="2">&nbsp;</td>
                </tr>
                 <tr>
                    <td>&nbsp;</td>
                    <td><input type="submit" name="submit" value="<?php _e('Save', 'contact-form-with-shortcode');?>" class="button button-primary button-large button-ap-large" /></td>
                  </tr>
                <tr>
                  <td colspan="2">&nbsp;</td>
                </tr>
            </table>
        </div>
        <div class="ap-tab-content">
            <table width="100%" border="0">
               <tr>
                    <td colspan="2"><h3><?php _e('Subscription Form Settings', 'contact-form-with-shortcode');?></h3></td>
                  </tr>
                <tr>
                  <td valign="top" width="300"><?php _e('Default Subscription Form', 'contact-form-with-shortcode');?></td>
                  <td>
                    <select name="contact_default_subscribe_form">
                        <?php $this->subscribeFormSelected($contact_default_subscribe_form);?>
                    </select> <a href="edit.php?post_type=subscribe_form" class="button">Create Newsletter Subscription Form</a>
                  </td>
                </tr>
                <tr>
                  <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                  <td valign="top"><?php _e('Text for Newsletter Subscription Checkbox', 'contact-form-with-shortcode');?></td>
                  <td><input type="text" name="contact_newsletter_subscribe_checkbox_text" value="<?php echo $contact_newsletter_subscribe_checkbox_text; ?>" class="widefat" placeholder="Subscribe to our newsletter"><i>This checkbox will appear in the user registration form of <strong>WP Register Profile With Shortcode</strong> plugin.</i></td>
                </tr>
                <tr>
                  <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td><input type="submit" name="submit" value="<?php _e('Save', 'contact-form-with-shortcode');?>" class="button button-primary button-large button-ap-large" /></td>
                  </tr>
                <tr>
                  <td colspan="2"><p>Please select the default <strong>Newsletter</strong> subscription form if you are using <a href="https://wordpress.org/plugins/wp-register-profile-with-shortcode/" target="_blank">WP Register Profile With Shortcode</a> with this plugin. <strong>WP Register Profile With Shortcode</strong> plugin will allow users of your site to subscribe your <strong>Newsletters</strong> at the time they make registration in the site.</p></td>
                </tr>
            </table>
        </div>

     </div>

    </td>
  </tr>
  </table>
</form>