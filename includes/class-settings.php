<?php

class Contact_Settings {

    // class instance
    static $instance;

    // WP_Subscribers_List object
    public $subscriber_obj;

    // WP_Contact_DB_List_Class object
    public $forms_db_obj;

    public function __construct() {
        $this->load_settings();
        add_filter('set-screen-option', array($this, 'cfsp_log_set_option'), 10, 3);
    }

    public function wp_register_profile_newsletter_subscription() {
        $enable_cfws_newsletter_subscription = get_option('enable_cfws_newsletter_subscription');
        $contact_newsletter_subscribe_checkbox_text = get_option('contact_newsletter_subscribe_checkbox_text');
        $text = 'Subscribe to our newsletter';

        if ($contact_newsletter_subscribe_checkbox_text) {
            $text = $contact_newsletter_subscribe_checkbox_text;
        }

        if ($enable_cfws_newsletter_subscription == 'Yes') {
            include CFWS_DIR_PATH . '/view/frontend/enable-subscription-checkbox.php';
        }
    }

    public function contact_widget_ap_options() {
        global $wpdb;
        $contact_enable_smtp = get_option('contact_enable_smtp');
        $contact_smtp_host = get_option('contact_smtp_host');
        $contact_smtp_port = get_option('contact_smtp_port');
        $contact_smtp_username = get_option('contact_smtp_username');
        $contact_smtp_password = get_option('contact_smtp_password');
        $contact_smtp_secure = get_option('contact_smtp_secure');
        $contact_default_subscribe_form = get_option('contact_default_subscribe_form');
        $contact_newsletter_subscribe_checkbox_text = get_option('contact_newsletter_subscribe_checkbox_text');
        echo '<div class="wrap">';
        $this->view_message();
        $this->help_support();
        include CFWS_DIR_PATH . '/view/admin/settings.php';
        $this->contact_wid_pro_add();
        $this->donate();
        echo '</div>';
    }

    public function subscribeFormSelected($sel = '') {
        $args = array('post_type' => 'subscribe_form', 'posts_per_page' => -1);
        $c_forms = get_posts($args);
        foreach ($c_forms as $c_form): setup_postdata($c_form);
            if ($sel == $c_form->ID) {
                echo '<option value="' . $c_form->ID . '"  selected="selected">' . $c_form->post_title . '</option>';
            } else {
                echo '<option value="' . $c_form->ID . '">' . $c_form->post_title . '</option>';
            }
        endforeach;
        wp_reset_postdata();
    }

    public function view_message() {
        if (isset($GLOBALS['msg'])) {
            echo '<div class="updated"><p>' . $GLOBALS['msg'] . '</p></div>';
        }
    }

    public function wp_contact_save_settings() {
        if (isset($_POST['option']) and sanitize_text_field($_POST['option']) == "wp_contact_save_settings") {

            if (!isset($_POST['wp_contact_save_action_field']) || !wp_verify_nonce($_POST['wp_contact_save_action_field'], 'wp_contact_save_action')) {
                wp_die('Sorry, your nonce did not verify.');
            }

            if (isset($_POST['contact_enable_smtp'])) {
                update_option('contact_enable_smtp', sanitize_text_field($_POST['contact_enable_smtp']));
            } else {
                delete_option('contact_enable_smtp');
            }

            if (isset($_POST['contact_smtp_host'])) {
                update_option('contact_smtp_host', sanitize_text_field($_POST['contact_smtp_host']));
            } else {
                delete_option('contact_smtp_host');
            }

            if (isset($_POST['contact_smtp_port'])) {
                update_option('contact_smtp_port', sanitize_text_field($_POST['contact_smtp_port']));
            } else {
                delete_option('contact_smtp_port');
            }

            if (isset($_POST['contact_smtp_username'])) {
                update_option('contact_smtp_username', sanitize_text_field($_POST['contact_smtp_username']));
            } else {
                delete_option('contact_smtp_username');
            }

            if (isset($_POST['contact_smtp_password'])) {
                update_option('contact_smtp_password', sanitize_text_field($_POST['contact_smtp_password']));
            } else {
                delete_option('contact_smtp_password');
            }

            if (isset($_POST['contact_smtp_secure'])) {
                update_option('contact_smtp_secure', sanitize_text_field($_POST['contact_smtp_secure']));
            } else {
                delete_option('contact_smtp_secure');
            }

            if (isset($_POST['contact_default_subscribe_form'])) {
                update_option('contact_default_subscribe_form', sanitize_text_field($_POST['contact_default_subscribe_form']));
            } else {
                delete_option('contact_default_subscribe_form');
            }

            if (isset($_POST['contact_newsletter_subscribe_checkbox_text'])) {
                update_option('contact_newsletter_subscribe_checkbox_text', sanitize_text_field($_POST['contact_newsletter_subscribe_checkbox_text']));
            } else {
                delete_option('contact_newsletter_subscribe_checkbox_text');
            }

            $GLOBALS['msg'] = __('Data saved successfully', 'contact-form-with-shortcode');
        }

        if (isset($_POST['option']) and sanitize_text_field($_POST['option']) == "filterSubscribers") {
            global $wpdb;
            $form_id = sanitize_text_field($_POST['form_id']);
            $post_id = sanitize_text_field($_POST['post_id']);

            $sub_users = get_post_meta($post_id, '_newsletter_from_subscribers', true);
            if ($form_id == 'all') {
                $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "contact_subscribers WHERE sub_status = 'Active' ORDER BY sub_added DESC", $form_id);
            } else {
                $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "contact_subscribers WHERE form_id = %d AND sub_status = 'Active' ORDER BY sub_added DESC", $form_id);
            }
            $data = $wpdb->get_results($query, ARRAY_A);
            $ret = '';

            if (is_array($data)) {
                foreach ($data as $k => $v) {
                    if (is_array($sub_users) and in_array($v['sub_id'], $sub_users)) {
                        $ret .= '<option value="' . $v['sub_id'] . '" selected="selected">' . $v['sub_email'] . ' (' . $v['sub_status'] . ')' . '</option>';
                    } else {
                        $ret .= '<option value="' . $v['sub_id'] . '">' . $v['sub_email'] . ' (' . $v['sub_status'] . ')' . '</option>';
                    }
                }
            }
            echo $ret;
            exit;
        }
    }

    public static function help_support() {
        include CFWS_DIR_PATH . '/view/admin/help-support.php';
    }

    public static function contact_wid_pro_add() {
        include CFWS_DIR_PATH . '/view/admin/pro-add.php';
    }

    public function donate() {
        include CFWS_DIR_PATH . '/view/admin/donate.php';
    }

    public function contact_widget_ap_menu() {
        add_menu_page('Contact Form Usage', 'Contact Form Usage', 'activate_plugins', 'contact_form_settings', array($this, 'contact_widget_ap_options'));
        add_submenu_page('contact_form_settings', 'Contact Forms', 'Contact Forms', 'activate_plugins', 'edit.php?post_type=contact_form', NULL);
        add_submenu_page('contact_form_settings', 'Subscription Forms', 'Subscription Forms', 'activate_plugins', 'edit.php?post_type=subscribe_form', NULL);

        $hook = add_submenu_page('contact_form_settings', 'Subscribers', 'Subscribers', 'activate_plugins', 'contact_widget_ap_subscribers', array($this, 'contact_widget_ap_subscribers_list'));
        add_action("load-$hook", array($this, 'subscribers_screen_option'));

        add_submenu_page('contact_form_settings', 'Newsletter', 'Newsletter', 'activate_plugins', 'edit.php?post_type=newsletter_form', NULL);

        $hook = add_submenu_page('contact_form_settings', 'Contact Forms Data', 'Contact Forms Data', 'activate_plugins', 'contact_form_ap_db_data', array($this, 'contact_form_db_stored_data'));
        add_action("load-$hook", array($this, 'forms_db_screen_option'));
    }

    public function cfsp_log_set_option($status, $option, $value) {
        if ('log_per_page' == $option) {
            return $value;
        }

        return $status;
    }

    public function subscribers_screen_option() {

        $option = 'per_page';
        $args = [
            'label' => __('Subscribers', 'contact-form-with-shortcode'),
            'default' => 10,
            'option' => 'log_per_page',
        ];

        add_screen_option($option, $args);

        $this->subscriber_obj = new WP_Subscribers_List();
    }

    public function forms_db_screen_option() {

        $option = 'per_page';
        $args = [
            'label' => __('Stored Data', 'contact-form-with-shortcode'),
            'default' => 10,
            'option' => 'log_per_page',
        ];

        add_screen_option($option, $args);

        $this->forms_db_obj = new WP_Contact_DB_List_Class();
    }

    public function contact_form_db_stored_data() {
        include CFWS_DIR_PATH . '/view/admin/stored-data-settings.php';
    }

    public static function get_instance() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function contact_widget_ap_subscribers_list() {
        include CFWS_DIR_PATH . '/view/admin/subscribers-settings.php';
    }

    public function load_settings() {
        add_action('admin_menu', array($this, 'contact_widget_ap_menu'));
        add_action('admin_init', array($this, 'wp_contact_save_settings'));
        add_action('wp_register_profile_form', array($this, 'wp_register_profile_newsletter_subscription'));
    }
}
