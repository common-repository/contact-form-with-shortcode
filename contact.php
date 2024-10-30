<?php
/*
Plugin Name: Contact Form With Shortcode
Plugin URI: https://wordpress.org/plugins/contact-form-with-shortcode/
Description: This is a contact form plugin. You can use widgets and shortcodes to display contact form in your theme. Unlimited number of dynamic fields can me created for contact froms.
Version: 4.2.5
Text Domain: contact-form-with-shortcode
Author: aviplugins.com
Author URI: https://www.aviplugins.com/
*/

/*
    |||||
  <(`0_0`)>
  ()(afo)()
    ()-()
 */

define('CFWS_DIR_NAME', 'contact-form-with-shortcode');
define('CFWS_DIR_PATH', dirname(__FILE__));
define('AP_SECURITY_KEY', 's)s7RSwH6>^>!6hmc#LuLQT5vq(vLD{31pqH)I<S<tHK<98A#?ZeJuJ.zLFg2kHP');

function cfws_plug_install() {
    global $cfc;
    include_once ABSPATH . 'wp-admin/includes/plugin.php';
    if (is_plugin_active('contact-form-with-shortcode-pro/contact.php')) {
        wp_die('It seems you have <strong>Contact Form With Shortcode PRO</strong> plugin activated. Please deactivate to continue.');
        exit;
    }

    include_once CFWS_DIR_PATH . '/config/config-general.php';

    include_once CFWS_DIR_PATH . '/includes/class-settings.php';
    include_once CFWS_DIR_PATH . '/includes/class-process.php';
    include_once CFWS_DIR_PATH . '/includes/class-scripts.php';
    include_once CFWS_DIR_PATH . '/includes/class-message.php';
    include_once CFWS_DIR_PATH . '/includes/class-contact-widget.php';
    include_once CFWS_DIR_PATH . '/includes/class-contact.php';
    include_once CFWS_DIR_PATH . '/includes/class-contact-meta.php';
    include_once CFWS_DIR_PATH . '/includes/class-wp-contact-db-list.php';

    include_once CFWS_DIR_PATH . '/includes/class-contact-mail.php';
    include_once CFWS_DIR_PATH . '/includes/class-contact-mail-smtp.php';
    include_once CFWS_DIR_PATH . '/includes/class-fields.php';
    include_once CFWS_DIR_PATH . '/includes/class-fields-contact.php';
    include_once CFWS_DIR_PATH . '/includes/class-newsletter.php';
    include_once CFWS_DIR_PATH . '/includes/class-newsletter-meta.php';
    include_once CFWS_DIR_PATH . '/includes/class-paginate.php';
    include_once CFWS_DIR_PATH . '/includes/class-subscription-widget.php';
    include_once CFWS_DIR_PATH . '/includes/class-subscribe.php';
    include_once CFWS_DIR_PATH . '/includes/class-subscribe-meta.php';
    include_once CFWS_DIR_PATH . '/includes/class-wp-subscribers-list.php';

    include_once CFWS_DIR_PATH . '/contact-widget.php';
    include_once CFWS_DIR_PATH . '/contact-widget-shortcode.php';
    include_once CFWS_DIR_PATH . '/subscribe-widget.php';
    include_once CFWS_DIR_PATH . '/newsletter-template-functions.php';
    include_once CFWS_DIR_PATH . '/wp-register-profile-action.php';
    include_once CFWS_DIR_PATH . '/unsubscribe-newsletter.php';

    include_once CFWS_DIR_PATH . '/hook.php';
    include_once CFWS_DIR_PATH . '/functions.php';

    new Contact_Mail_Smtp_Class;
    new Contact_Process_Class;
    new Contact_Scripts;
    new Contact_widget_Class;
    new Subscription_Widget_Class;

    Contact_Settings::get_instance();
    $cfc = new Contact_Fields_Class;
}

class CFWS_Plugin_Init {
    function __construct() {
        cfws_plug_install();
    }
}
new CFWS_Plugin_Init;

class Contact_Form_SC {

    static function cfws_install() {
        global $wpdb;
        $create_table = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "contact_subscribers` (
	  `sub_id` int(11) NOT NULL AUTO_INCREMENT,
	  `form_id` int(11) NOT NULL,
	  `sub_name` varchar(255) NOT NULL,
	  `sub_email` varchar(255) NOT NULL,
	  `sub_ip` varchar(50) NOT NULL,
	  `sub_added` datetime NOT NULL,
	  `sub_status` enum('Active','Inactive','Deleted') NOT NULL,
	  PRIMARY KEY (`sub_id`)
	)";
        $wpdb->query($create_table);

        // update on 4.0.0 //
        $create_table1 = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "contact_stored_data` (
		`sd_id` int(11) NOT NULL AUTO_INCREMENT,
		`con_id` int(11) NOT NULL,
		`sd_data` text NOT NULL,
		`sd_added` datetime NOT NULL,
		`sd_ip` VARCHAR(50) NOT NULL,
		PRIMARY KEY (`sd_id`)
	)";
        $wpdb->query($create_table1);

        $check_field_query = "SHOW COLUMNS FROM `" . $wpdb->prefix . "contact_stored_data` WHERE field = 'sd_status'";
        $field_data = $wpdb->get_row($check_field_query);
        if (empty($field_data)) {
            $alter_table = "ALTER TABLE `" . $wpdb->prefix . "contact_stored_data` ADD `sd_status` ENUM('processing','attending','unresolved','resolved') NOT NULL DEFAULT 'processing'";
            $wpdb->query($alter_table);
        }

        $check_field_query1 = "SHOW COLUMNS FROM `" . $wpdb->prefix . "contact_stored_data` WHERE field = 'sd_email'";
        $field_data1 = $wpdb->get_row($check_field_query1);
        if (empty($field_data1)) {
            $alter_table1 = "ALTER TABLE `" . $wpdb->prefix . "contact_stored_data` ADD `sd_email` VARCHAR(255) NULL DEFAULT NULL AFTER `sd_data`";
            $wpdb->query($alter_table1);
        }
        // update on 4.0.0 //

    }

    static function cfws_uninstall() {}
}

register_activation_hook(__FILE__, array('Contact_Form_SC', 'cfws_install'));

add_action('widgets_init', function () {register_widget('Contact_Form_Wid');});
add_action('widgets_init', function () {register_widget('Subscribe_Form_Wid');});

add_shortcode('contactwid', 'contact_widget_shortcode');
add_shortcode('subscribewid', 'subscribe_widget_shortcode');
add_shortcode('newsletter', 'newsletter_shortcode_function');

if (is_admin()) {

    add_action('load-post.php', 'call_contact_meta_class');
    add_action('load-post-new.php', 'call_contact_meta_class');
    new Contact_Class;

    add_action('load-post.php', 'call_newsletter_meta_class');
    add_action('load-post-new.php', 'call_newsletter_meta_class');
    new Newsletter_Class;

    add_action('load-post.php', 'call_subscribe_meta_class');
    add_action('load-post-new.php', 'call_subscribe_meta_class');
    new Subscribe_Class;

}

add_action('contact_store_db', 'contact_store_db_process', 10, 3);

add_action('init', 'process_delete_subscription_data');

add_action('cfws_subscription', 'cfws_subscription', 1, 2);

add_action('plugins_loaded', 'contact_form_load_text_domain');
add_action('template_redirect', 'start_session_if_not_started');