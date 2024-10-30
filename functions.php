<?php

if (!function_exists('call_contact_meta_class')) {
    function call_contact_meta_class() {
        new Contact_Meta_Class();
    }
}

if (!function_exists('call_newsletter_meta_class')) {
    function call_newsletter_meta_class() {
        new Newsletter_Meta_Class();
    }
}

if (!function_exists('call_subscribe_meta_class')) {
    function call_subscribe_meta_class() {
        new Subscribe_Meta_Class();
    }
}

if (!function_exists('start_session_if_not_started')) {
    function start_session_if_not_started() {
        if (!session_id()) {
            @session_start();
        }
    }
}

if (!function_exists('ap_encript')) {
    function ap_encript($data = '') {
        if (empty($data)) {return false;}
        $ivlen = openssl_cipher_iv_length($cipher = "AES-128-CBC");
        $iv = openssl_random_pseudo_bytes($ivlen);
        $ciphertext_raw = openssl_encrypt($data, $cipher, AP_SECURITY_KEY, $options = OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac('sha256', $ciphertext_raw, AP_SECURITY_KEY, $as_binary = true);
        $ciphertext = base64_encode(urlencode($iv . $hmac . $ciphertext_raw));
        return $ciphertext;
    }
}

if (!function_exists('ap_decrypt')) {
    function ap_decrypt($data = '') {
        if (empty($data)) {return false;}
        $c = urldecode(base64_decode($data));
        $ivlen = openssl_cipher_iv_length($cipher = "AES-128-CBC");
        $iv = substr($c, 0, $ivlen);
        $hmac = substr($c, $ivlen, $sha2len = 32);
        $ciphertext_raw = substr($c, $ivlen + $sha2len);
        $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, AP_SECURITY_KEY, $options = OPENSSL_RAW_DATA, $iv);
        $calcmac = hash_hmac('sha256', $ciphertext_raw, AP_SECURITY_KEY, $as_binary = true);
        if ($hmac === $calcmac) {
            return $original_plaintext;
        } else {
            return false;
        }
    }
}

function contact_form_load_text_domain() {
    load_plugin_textdomain('contact-form-with-shortcode', FALSE, basename(dirname(__FILE__)) . '/languages');
}

function contact_field_name_to_label($data) {
    $data = str_replace(array('_', '-'), array(' ', ' '), $data);
    $data = ucfirst($data);
    return $data;
}

function get_contact_form_name($id = '') {
    if ($id == '') {
        return;
    }
    $contact = get_post($id);
    return $contact->post_title;
}

function get_contact_stored_data_for_list($data) {
    $data = unserialize($data);
    $ret = '';
    if (is_array($data['data'])) {
        foreach ($data['data'] as $key => $value) {
            $ret .= $key . ': ' . stripslashes($value);
            $ret .= '<br>';
        }
    }
    return $ret;
}

function get_subscribe_form_name($id = '') {
    if ($id == '') {
        return;
    }
    $contact = get_post($id);
    return $contact->post_title;
}