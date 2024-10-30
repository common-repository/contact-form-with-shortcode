<?php

class Contact_widget_Class {

    public function __construct() {
        add_action('init', array($this, 'contact_form_process'));
        add_action('wp_head', array($this, 'contact_ajax_submit'));
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

    public function contact_wid_body($instance) {
        global $cfc;
        $this->load_validation_script('con-' . $instance['wid_contact_form']);
        $this->error_message($instance['wid_contact_form']);
        include CFWS_DIR_PATH . '/view/frontend/contact-widget.php';
    }

    public function load_validation_script($fid = 'contact') {
        include CFWS_DIR_PATH . '/view/frontend/contact-validation-scripts.php';
    }

    public function contact_ajax_submit() {
        include CFWS_DIR_PATH . '/view/frontend/contact-submit-scripts.php';
    }

    public function error_message($con_id) {
        $this->start_session();
        $e_msg = '';
        if (isset($_SESSION['contact_msg_' . $con_id]) and $_SESSION['contact_msg_' . $con_id]) {
            $e_msg .= '<div class="' . $_SESSION['contact_msg_class'] . '">' . $_SESSION['contact_msg_' . $con_id] . '</div>';
            unset($_SESSION['contact_msg_' . $con_id]);
            unset($_SESSION['contact_msg_class']);
        }
        echo $e_msg;
    }

    public function contact_form_process() {
        if (isset($_REQUEST['con_form_process']) and sanitize_text_field($_REQUEST['con_form_process']) == 'do_process_ajax') {
            $cmc = new Contact_Mail_Class;
            $form_id = sanitize_text_field($_REQUEST['con_form_id']);

            $msg = $cmc->contact_mail_body($form_id);
            if (!$msg['error']) {
                echo json_encode(array('status' => 'success', 'msg' => '<div class="cont_success">' . $msg['msg'] . '</div>', 'id' => $form_id));
            } else {
                echo json_encode(array('status' => 'error', 'msg' => '<div class="cont_error">' . $msg['msg'] . '</div>', 'id' => $form_id));
            }
            exit;
        }
    }
}
