<?php

class Contact_Meta_Class {

    public function __construct() {
        add_action('add_meta_boxes', array($this, 'contact_form_fields'));
        add_action('add_meta_boxes', array($this, 'contact_form_mail_body_fields'));
        add_action('add_meta_boxes', array($this, 'contact_form_form_body'));
        add_action('add_meta_boxes', array($this, 'contact_form_other_fields'));
        add_action('add_meta_boxes', array($this, 'contact_form_security_fields'));
        add_action('add_meta_boxes', array($this, 'contact_form_messages_fields'));
        add_action('save_post', array($this, 'save'));
    }

    public function contact_form_fields($post_type) {
        $post_types = array('contact_form');
        if (in_array($post_type, $post_types)) {
            add_meta_box(
                'contact_form_fields'
                , __('Contact Form Fields', 'contact-form-with-shortcode')
                , array($this, 'render_contact_form_fields')
                , $post_type
                , 'advanced'
                , 'high'
            );
        }
    }

    public function contact_form_mail_body_fields($post_type) {
        $post_types = array('contact_form');
        if (in_array($post_type, $post_types)) {
            add_meta_box(
                'contact_form_mail_body_fields'
                , __('Mail Body', 'contact-form-with-shortcode')
                , array($this, 'render_contact_form_body')
                , $post_type
                , 'advanced'
                , 'high'
            );
        }
    }

    public function contact_form_other_fields($post_type) {
        $post_types = array('contact_form');
        if (in_array($post_type, $post_types)) {
            add_meta_box(
                'contact_form_other_fields'
                , __('From Settings', 'contact-form-with-shortcode')
                , array($this, 'render_contact_other_fields')
                , $post_type
                , 'side'
                , 'high'
            );
        }
    }

    public function contact_form_security_fields($post_type) {
        $post_types = array('contact_form');
        if (in_array($post_type, $post_types)) {
            add_meta_box(
                'contact_form_security_fields'
                , __('Security Settings', 'contact-form-with-shortcode')
                , array($this, 'render_contact_security_fields')
                , $post_type
                , 'side'
                , 'high'
            );
        }
    }

    public function contact_form_messages_fields($post_type) {
        $post_types = array('contact_form');
        if (in_array($post_type, $post_types)) {
            add_meta_box(
                'contact_form_messages_fields'
                , __('Messages', 'contact-form-with-shortcode')
                , array($this, 'render_contact_form_messages_fields')
                , $post_type
                , 'side'
                , 'high'
            );
        }
    }

    public function contact_form_form_body($post_type) {
        $post_types = array('contact_form');
        if (in_array($post_type, $post_types)) {
            add_meta_box(
                'contact_form_form_body'
                , __('Contact Form Body (leave this empty to use default form design)', 'contact-form-with-shortcode')
                , array($this, 'render_contact_form_form_body')
                , $post_type
                , 'advanced'
                , 'high'
            );
        }
    }

    public function save($post_id) {

        if (!isset($_POST['cfws_inner_custom_box_nonce'])) {
            return $post_id;
        }

        $nonce = $_POST['cfws_inner_custom_box_nonce'];

        if (!wp_verify_nonce($nonce, 'cfws_inner_custom_box')) {
            return $post_id;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

        if ('page' == $_POST['post_type']) {
            if (!current_user_can('edit_page', $post_id)) {
                return $post_id;
            }

        } else {
            if (!current_user_can('edit_post', $post_id)) {
                return $post_id;
            }

        }

        $contact_enable_captcha = sanitize_text_field($_POST['contact_enable_captcha']);
        update_post_meta($post_id, '_contact_enable_security', $contact_enable_captcha);

        $from_name = sanitize_text_field($_POST['from_name']);
        update_post_meta($post_id, '_contact_from_name', $from_name);

        $from_mail = sanitize_text_field($_POST['from_mail']);
        update_post_meta($post_id, '_contact_from_mail', $from_mail);

        $contact_subject = sanitize_text_field($_POST['contact_subject']);
        update_post_meta($post_id, '_contact_subject', $contact_subject);

        $contact_to = sanitize_text_field($_POST['contact_to']);
        update_post_meta($post_id, '_contact_to_mail', $contact_to);

        $contact_mail_body = esc_html($_POST['contact_mail_body']);
        update_post_meta($post_id, '_contact_mail_body', $contact_mail_body);

        $contact_mail_body_user = esc_html($_POST['contact_mail_body_user']);
        update_post_meta($post_id, '_contact_mail_body_user', $contact_mail_body_user);

        $reply_to_field = sanitize_text_field($_POST['reply_to_field']);
        update_post_meta($post_id, '_reply_to_field', $reply_to_field);

        $contact_success_message = sanitize_text_field($_POST['contact_success_message']);
        update_post_meta($post_id, '_contact_success_message', $contact_success_message);

        $contact_form_body = esc_html($_POST['contact_form_body']);
        update_post_meta($post_id, '_contact_form_body', $contact_form_body);

        do_action('fields_save_contact_form', $post_id);

    }

    public function help_scripts() {
        include CFWS_DIR_PATH . '/view/admin/help-scripts.php';
    }

    public function sorting_scripts($id = 'newFields') {
        include CFWS_DIR_PATH . '/view/admin/sorting-scripts.php';
    }

    public function render_contact_form_fields($post) {
        global $cfc;
        wp_nonce_field('cfws_inner_custom_box', 'cfws_inner_custom_box_nonce');
        $extra_fields = get_post_meta($post->ID, '_contact_extra_fields', true);
        $cfc->load_field_js();
        include CFWS_DIR_PATH . '/view/admin/contact-meta-custom-fields.php';
        $this->sorting_scripts();
    }

    public function render_contact_form_messages_fields($post) {
        wp_nonce_field('cfws_inner_custom_box', 'cfws_inner_custom_box_nonce');
        $contact_success_message = get_post_meta($post->ID, '_contact_success_message', true);
        $cmc = new Contact_Mail_Class;
        include CFWS_DIR_PATH . '/view/admin/contact-meta-message-fields.php';
    }

    public function render_contact_form_body($post) {
        global $cfc;
        $this->help_scripts();
        wp_nonce_field('cfws_inner_custom_box', 'cfws_inner_custom_box_nonce');
        $contact_mail_body = get_post_meta($post->ID, '_contact_mail_body', true);
        $contact_mail_body_user = get_post_meta($post->ID, '_contact_mail_body_user', true);
        include CFWS_DIR_PATH . '/view/admin/contact-meta-mail-body-fields.php';
    }

    public function render_contact_other_fields($post) {
        global $cfc;
        wp_nonce_field('cfws_inner_custom_box', 'cfws_inner_custom_box_nonce');
        $contact_subject = get_post_meta($post->ID, '_contact_subject', true);
        $contact_to = get_post_meta($post->ID, '_contact_to_mail', true);
        $from_name = get_post_meta($post->ID, '_contact_from_name', true);
        $from_mail = get_post_meta($post->ID, '_contact_from_mail', true);
        $reply_to_field = get_post_meta($post->ID, '_reply_to_field', true);
        include CFWS_DIR_PATH . '/view/admin/contact-meta-other-fields.php';
    }

    public function render_contact_security_fields($post) {
        global $cfc;
        wp_nonce_field('cfws_inner_custom_box', 'cfws_inner_custom_box_nonce');
        $contact_enable_captcha = get_post_meta($post->ID, '_contact_enable_security', true);
        include CFWS_DIR_PATH . '/view/admin/contact-meta-captcha-fields.php';
    }

    public function render_contact_form_form_body($post) {
        $this->help_scripts();
        global $cfc;
        wp_nonce_field('cfws_inner_custom_box', 'cfws_inner_custom_box_nonce');
        $contact_form_body = get_post_meta($post->ID, '_contact_form_body', true);
        include CFWS_DIR_PATH . '/view/admin/contact-meta-form-body.php';
    }

}
