<?php

class Contact_Process_Class {

    public function __construct() {
        add_action('admin_init', array($this, 'process_contact_stored_data'));
        add_action('admin_init', array($this, 'process_sub_data'));
    }

    public function process_contact_stored_data() {
        if (isset($_REQUEST['action']) and $_REQUEST['action'] == 'sd_data_edit') {
            start_session_if_not_started();
            global $wpdb;
            $cdc = new WP_Contact_DB_List_Class;
            $update = array('sd_status' => sanitize_text_field($_REQUEST['sd_status']));
            $update_format = array('%s');
            $where = array('sd_id' => sanitize_text_field($_REQUEST['id']));
            $where_format = array('%d');
            $wpdb->update($wpdb->prefix . "contact_stored_data", $update, $where, $update_format, $where_format);
            $mc = new CFSP_Message_Class;
            $mc->add_message(__('Status updated successfully.', 'contact-form-with-shortcode'), 'updated');
            wp_redirect($cdc->plugin_page . '&action=view&id=' . sanitize_text_field($_REQUEST['id']));
            exit;
        }
    }

    public function process_sub_data() {

        if (isset($_REQUEST['action']) and sanitize_text_field($_REQUEST['action']) == 'sub_edit') {
            start_session_if_not_started();
            global $wpdb;
            $update = array('sub_status' => sanitize_text_field($_REQUEST['sub_status']));
            $data_format = array('%s');
            $where = array('sub_id' => intval(sanitize_text_field($_REQUEST['sub_id'])));
            $data_format1 = array('%d');
            $wpdb->update($wpdb->prefix . "contact_subscribers", $update, $where, $data_format, $data_format1);
            $mc = new CFSP_Message_Class;
            $mc->add_message(__('Subscriber updated successfully', 'contact-form-with-shortcode'), 'updated');
            wp_redirect(admin_url('admin.php?page=contact_widget_ap_subscribers') . "&action=cf_edit&id=" . sanitize_text_field($_REQUEST['sub_id']));
            exit;
        }
    }

}
