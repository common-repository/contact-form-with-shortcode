<?php
class Subscribe_Meta_Class {

    public function __construct() {
        add_action('add_meta_boxes', array($this, 'subscribe_form_fields'));
        add_action('add_meta_boxes', array($this, 'subscribe_form_mail_body_fields'));
        add_action('add_meta_boxes', array($this, 'subscribe_form_mail_body_admin_fields'));
        add_action('add_meta_boxes', array($this, 'subscribe_form_other_fields'));
        add_action('save_post', array($this, 'save'));
    }

    public function subscribe_form_fields($post_type) {
        $post_types = array('subscribe_form');
        if (in_array($post_type, $post_types)) {
            add_meta_box(
                'subscribe_form_fields'
                , __('Subscribe Form Fields', 'contact-form-with-shortcode')
                , array($this, 'render_subscribe_form_fields')
                , $post_type
                , 'advanced'
                , 'high'
            );
        }
    }

    public function subscribe_form_mail_body_fields($post_type) {
        $post_types = array('subscribe_form');
        if (in_array($post_type, $post_types)) {
            add_meta_box(
                'subscribe_form_mail_body_fields'
                , __('Mail Body User', 'contact-form-with-shortcode')
                , array($this, 'render_subscribe_form_body')
                , $post_type
                , 'advanced'
                , 'high'
            );
        }
    }

    public function subscribe_form_mail_body_admin_fields($post_type) {
        $post_types = array('subscribe_form');
        if (in_array($post_type, $post_types)) {
            add_meta_box(
                'subscribe_form_mail_body_admin_fields'
                , __('Mail Body Admin', 'contact-form-with-shortcode')
                , array($this, 'render_subscribe_form_body_admin')
                , $post_type
                , 'advanced'
                , 'high'
            );
        }
    }

    public function subscribe_form_other_fields($post_type) {
        $post_types = array('subscribe_form');
        if (in_array($post_type, $post_types)) {
            add_meta_box(
                'subscribe_form_other_fields'
                , __('From Settings', 'contact-form-with-shortcode')
                , array($this, 'render_subscribe_other_fields')
                , $post_type
                , 'side'
                , 'high'
            );
        }
    }

    public function help_js() {?>
	<script>
	jQuery(document).ready(function(jQuery) {
		jQuery( '.tool' ).tooltip();
	});
	</script>
	<?php }

    public function save($post_id) {

        if (!isset($_POST['cfws_inner_custom_box_subscribe_nonce'])) {
            return $post_id;
        }

        $nonce = $_POST['cfws_inner_custom_box_subscribe_nonce'];

        if (!wp_verify_nonce($nonce, 'cfws_inner_custom_box_subscribe')) {
            return $post_id;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

        if ('page' == sanitize_text_field($_POST['post_type'])) {
            if (!current_user_can('edit_page', $post_id)) {
                return $post_id;
            }

        } else {
            if (!current_user_can('edit_post', $post_id)) {
                return $post_id;
            }

        }

        $include_name_in_subscription = sanitize_text_field($_POST['include_name_in_subscription']);
        update_post_meta($post_id, '_include_name_in_subscription', $include_name_in_subscription);

        $name_in_subscription_required = sanitize_text_field($_POST['name_in_subscription_required']);
        update_post_meta($post_id, '_name_in_subscription_required', $name_in_subscription_required);

        $from_name = sanitize_text_field($_POST['from_name']);
        update_post_meta($post_id, '_subscribe_from_name', $from_name);

        $from_mail = sanitize_text_field($_POST['from_mail']);
        update_post_meta($post_id, '_subscribe_from_mail', $from_mail);

        $subscribe_to_admin = sanitize_text_field($_POST['subscribe_to_admin']);
        update_post_meta($post_id, '_subscribe_to_admin_mail', $subscribe_to_admin);

        $subscribe_subject = sanitize_text_field($_POST['subscribe_subject']);
        update_post_meta($post_id, '_subscribe_subject', $subscribe_subject);

        $subscribe_mail_body = esc_html($_POST['subscribe_mail_body']);
        update_post_meta($post_id, '_subscribe_mail_body', $subscribe_mail_body);

        $subscribe_mail_body_admin = esc_html($_POST['subscribe_mail_body_admin']);
        update_post_meta($post_id, '_subscribe_mail_body_admin', $subscribe_mail_body_admin);

    }

    public function render_subscribe_form_fields($post) {
        global $cfc;
        wp_nonce_field('cfws_inner_custom_box_subscribe', 'cfws_inner_custom_box_subscribe_nonce');
        $include_name_in_subscription = get_post_meta($post->ID, '_include_name_in_subscription', true);
        $name_in_subscription_required = get_post_meta($post->ID, '_name_in_subscription_required', true);
        include CFWS_DIR_PATH . '/view/admin/subscribe-meta-form-fields.php';
    }

    public function render_subscribe_form_body($post) {
        global $cfc;
        wp_nonce_field('cfws_inner_custom_box_subscribe', 'cfws_inner_custom_box_subscribe_nonce');
        $subscribe_mail_body = get_post_meta($post->ID, '_subscribe_mail_body', true);
        include CFWS_DIR_PATH . '/view/admin/subscribe-meta-email-body-fields.php';
    }

    public function render_subscribe_form_body_admin($post) {
        global $cfc;
        wp_nonce_field('cfws_inner_custom_box_subscribe', 'cfws_inner_custom_box_subscribe_nonce');
        $subscribe_mail_body_admin = get_post_meta($post->ID, '_subscribe_mail_body_admin', true);
        include CFWS_DIR_PATH . '/view/admin/subscribe-meta-email-body-admin-fields.php';
    }

    public function render_subscribe_other_fields($post) {
        global $cfc;
        wp_nonce_field('cfws_inner_custom_box_subscribe', 'cfws_inner_custom_box_subscribe_nonce');
        $subscribe_subject = get_post_meta($post->ID, '_subscribe_subject', true);
        $subscribe_to_admin = get_post_meta($post->ID, '_subscribe_to_admin_mail', true);
        $from_name = get_post_meta($post->ID, '_subscribe_from_name', true);
        $from_mail = get_post_meta($post->ID, '_subscribe_from_mail', true);
        $this->help_js();
        include CFWS_DIR_PATH . '/view/admin/subscribe-meta-other-fields.php';
    }

}