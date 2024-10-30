<?php

// version 2.0 //

function process_delete_subscription_data() {
    if (isset($_REQUEST['action']) and sanitize_text_field($_REQUEST['action']) == 'delete_subscription') {
        global $wpdb;
        $sub_id = ap_decrypt($_REQUEST['sub']);

        if (!$sub_id) {
            wp_die(__('Subscriber not found!', 'contact-form-with-shortcode'));
            exit;
        }

        $slc = new Subscribers_List_Class;
        $update = array('sub_status' => 'Inactive');
        $data_format = array('%s');
        $where = array('sub_id' => $sub_id);
        $data_format1 = array('%d');
        $res = $wpdb->update($wpdb->prefix . "contact_subscribers", $update, $where, $data_format, $data_format1);
        if ($res) {
            wp_die(__('Your subscription is successfully removed.', 'contact-form-with-shortcode'));
        } else {
            wp_die(__('Subscriber not found!', 'contact-form-with-shortcode'));
        }
        exit;
    }
}
