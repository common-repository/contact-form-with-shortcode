<?php

class Subscription_Widget_Class {

    public function __construct() {
        add_action('init', array($this, 'subscribe_form_process'));
        add_action('wp_head', array($this, 'subscribeAjaxSubmit'));
    }

    public function start_session() {
        start_session_if_not_started();
    }

    public function current_page_url() {
        $pageURL = 'http';
        if (isset($_SERVER["HTTPS"])) {
            if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
        }
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }

    public function subscribeWidBody($instance) {
        if ($instance['wid_subscribe_form'] == '') {
            _e('Newsletter subscription form not selected.', 'contact-form-with-shortcode');
            return;
        }
        $this->load_validation_script('sub-' . $instance['wid_subscribe_form']);
        $this->error_message($instance['wid_subscribe_form']);
        include CFWS_DIR_PATH . '/view/frontend/subscription-widget.php';
    }

    public function load_validation_script($fid = 'subscribe') {?>
		<script type="text/javascript">
			jQuery(document).ready(function () {
				jQuery('#<?php echo $fid; ?>-err-msg').hide();
				jQuery('#<?php echo $fid; ?>').validate({ errorClass: "rw-error", submitHandler: function(form) { subscribe_ap_submit( form ); } });
			});
		</script>
	<?php }

    public function subscribeAjaxSubmit() {?>
	<script type="text/javascript">
		function subscribe_ap_submit(f){
			var sub_form_id = jQuery('#sub_form_id').val();
			var data = jQuery( f ).serialize();
			jQuery.ajax({
			data: data,
			dataType:"json",
			type: "POST",
			beforeSend: function( renponse ) { jQuery('#sub-'+sub_form_id+'-wait-msg').show(); }
			})
			.done(function( renponse ) {
				jQuery('#sub-'+sub_form_id+'-wait-msg').hide();
				jQuery('#sub-'+renponse.id+'-err-msg').show();
				jQuery('#sub-'+renponse.id+'-err-msg').html(renponse.msg);
				jQuery("#sub-"+renponse.id).find("input[type=text], textarea, select").val("");
			});
			return false;
		}
	</script>
	<?php
}

    public function subscribe_form_fields($id) {
        global $cfc;
        $include_name_in_subscription = get_post_meta($id, '_include_name_in_subscription', true);
        $name_in_subscription_required = get_post_meta($id, '_name_in_subscription_required', true);
        $name_required = ($name_in_subscription_required == 'Yes' ? true : false);
        include CFWS_DIR_PATH . '/view/frontend/subscription-form-fields.php';
    }

    public function error_message($sub_id) {
        $this->start_session();
        $e_msg = '';
        if (isset($_SESSION['subscribe_msg_' . $sub_id]) and $_SESSION['subscribe_msg_' . $sub_id]) {
            $e_msg .= '<div class="' . $_SESSION['subscribe_msg_class'] . '">' . $_SESSION['subscribe_msg_' . $sub_id] . '</div>';
            unset($_SESSION['subscribe_msg_' . $sub_id]);
            unset($_SESSION['subscribe_msg_class']);
        }
        echo $e_msg;
    }

    public function is_user_already_subscribed($email) {
        global $wpdb;
        $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "contact_subscribers WHERE sub_email = %s AND sub_status = 'Active'", $email);
        $result = $wpdb->get_row($query, ARRAY_A);
        if ($result) {
            return $result['sub_id'];
        } else {
            return false;
        }
    }

    public function subscribe_form_process() {
        if (isset($_REQUEST['sub_form_process']) and sanitize_text_field($_REQUEST['sub_form_process']) == 'do_process_ajax') {
            global $wpdb;
            $form_id = sanitize_text_field($_REQUEST['sub_form_id']);
            $msg = '';

            if (!isset($_POST['ap_contact_field']) || !wp_verify_nonce($_POST['ap_contact_field'], 'ap_contact_value')) {
                echo json_encode(array('status' => 'error', 'msg' => '<div class="cont_error">' . __('Nonce error!', 'contact-form-with-shortcode') . '</div>', 'id' => $form_id));
                exit;
            }

            // add subscriber //
            $sub_exist = $this->is_user_already_subscribed(sanitize_text_field($_REQUEST['sub_email']));
            if ($sub_exist) {
                $sdata = array(
                    'form_id' => $form_id,
                    'sub_ip' => $_SERVER['REMOTE_ADDR'],
                    'sub_added' => current_time('mysql'),
                    'sub_status' => 'Active',
                );
                $data_type = array(
                    '%d',
                    '%s',
                    '%s',
                    '%s',
                );
                $where = array('sub_id' => $sub_exist);
                $data_type1 = array(
                    '%d',
                );
                $wpdb->update($wpdb->prefix . "contact_subscribers", $sdata, $where, $data_type, $data_type1);
                $msg .= 'Your Subscription is Updated <br>';
            } else {
                $sdata = array(
                    'form_id' => $form_id,
                    'sub_name' => sanitize_text_field(@$_REQUEST['sub_name']),
                    'sub_email' => sanitize_text_field(@$_REQUEST['sub_email']),
                    'sub_ip' => $_SERVER['REMOTE_ADDR'],
                    'sub_added' => current_time('mysql'),
                    'sub_status' => 'Active',
                );
                $data_type = array(
                    '%d',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                );
                $new_sub_id = $wpdb->insert($wpdb->prefix . "contact_subscribers", $sdata, $data_type);
                $msg .= 'Your Subscription is Created <br>';
            }
            // add subscriber //

            $cmc = new Contact_Mail_Class;
            $bol = $cmc->subscribe_mail_body($form_id, array('sub_name' => sanitize_text_field(@$_REQUEST['sub_name']), 'sub_email' => sanitize_text_field(@$_REQUEST['sub_email'])));
            if ($bol) {
                echo json_encode(array('status' => 'success', 'msg' => '<div class="cont_success">' . __($msg, 'contact-form-with-shortcode') . '</div>', 'id' => $form_id));
            } else {
                echo json_encode(array('status' => 'error', 'msg' => '<div class="cont_error">' . __('Error! Subscription mail not sent. Please try again later.', 'contact-form-with-shortcode') . '</div>', 'id' => $form_id));
            }
            exit;
        }
    }
}
