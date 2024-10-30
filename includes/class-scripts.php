<?php

class Contact_Scripts {

    public function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'contact_plugin_styles'));
        add_action('admin_enqueue_scripts', array($this, 'contact_plugin_styles_admin'));
    }

    public function contact_plugin_styles_admin() {
        wp_enqueue_script('jquery');
        wp_enqueue_style('jquery-ui', plugins_url(CFWS_DIR_NAME . '/css/jquery-ui.css'));
        wp_enqueue_script('jquery-ui-tooltip');
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_style('style_contact_admin', plugins_url(CFWS_DIR_NAME . '/css/style_contact_admin.css'));

        wp_enqueue_script('contact', plugins_url(CFWS_DIR_NAME . '/js/contact.js'));

        wp_enqueue_style('multi-select', plugins_url(CFWS_DIR_NAME . '/css/multi-select.css'));
        wp_enqueue_script('jquery.multi-select', plugins_url(CFWS_DIR_NAME . '/js/jquery.multi-select.js'));
        wp_enqueue_script('jquery.quicksearch', plugins_url(CFWS_DIR_NAME . '/js/jquery.quicksearch.js'));

        wp_enqueue_script('ap.cookie', plugins_url(CFWS_DIR_NAME . '/js/ap.cookie.js'));
        wp_enqueue_script('ap-tabs', plugins_url(CFWS_DIR_NAME . '/js/ap-tabs.js'));
    }

    public function contact_plugin_styles() {
        wp_enqueue_script('jquery');
        wp_enqueue_style('jquery-ui', plugins_url(CFWS_DIR_NAME . '/css/jquery-ui.css'));
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_script('jquery-ui-tooltip');
        wp_enqueue_script('jquery.ptTimeSelect', plugins_url(CFWS_DIR_NAME . '/css/jquery.ptTimeSelect.js'));
        wp_enqueue_style('jquery.ptTimeSelect', plugins_url(CFWS_DIR_NAME . '/css/jquery.ptTimeSelect.css'));
        wp_enqueue_style('style_contact_widget', plugins_url(CFWS_DIR_NAME . '/css/style_contact_widget.css'));

        wp_enqueue_script('jquery.validate.min', plugins_url(CFWS_DIR_NAME . '/js/jquery.validate.min.js'));
        wp_enqueue_script('additional-methods', plugins_url(CFWS_DIR_NAME . '/js/additional-methods.js'));
    }
}
