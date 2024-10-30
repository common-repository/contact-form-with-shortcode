<?php

if (!class_exists('Contact_Fields_Class')) {
    class Contact_Fields_Class extends CFWS_Fields_Class {

        public function __construct() {
            parent::__construct();
            add_action('wp_head', array($this, 'contact_ap_scripts_fr'));
            add_action('fields_save_contact_form', array($this, 'save_contact_fields'), 10, 1);
        }

        public function save_contact_fields($post_id) {
            $field_names = @$_REQUEST['field_names'];
            $field_labels = @$_REQUEST['field_labels'];
            $field_types = @$_REQUEST['field_types'];
            $field_descs = @$_REQUEST['field_descs'];
            $field_placeholders = @$_REQUEST['field_placeholders'];
            $field_requireds = @$_REQUEST['field_requireds'];
            $field_titles = @$_REQUEST['field_titles'];
            $field_patterns = @$_REQUEST['field_patterns'];
            $field_values_array = @$_REQUEST['field_values_array'];

            $extra_fields = array();

            if (is_array($field_names)) {
                foreach ($field_names as $key => $value) {
                    if ($value) {
                        $extra_fields[] = array(
                            'field_name' => str_replace(" ", "_", strtolower(trim(sanitize_text_field(@$value)))),
                            'field_label' => sanitize_text_field(@$field_labels[$key]),
                            'field_type' => sanitize_text_field(@$field_types[$key]),
                            'field_desc' => sanitize_text_field(@$field_descs[$key]),
                            'field_placeholder' => sanitize_text_field(@$field_placeholders[$key]),
                            'field_required' => sanitize_text_field(@$field_requireds[$key]),
                            'field_title' => sanitize_text_field(@$field_titles[$key]),
                            'field_pattern' => sanitize_text_field(@$field_patterns[$key]),
                            'field_values' => implode(',', array_map('trim', explode(',', sanitize_text_field(@$field_values_array[$key])))),
                        );
                    }
                }
            }
            update_post_meta($post_id, '_contact_extra_fields', $extra_fields);
        }

        public function contact_ap_scripts_fr() {
            include CFWS_DIR_PATH . '/view/frontend/captcha-scripts.php';
        }

        public function get_field_desc($desc = '') {
            if ($desc) {
                $desc = self::removeslashes($desc);
                ob_start();
                include CFWS_DIR_PATH . '/view/frontend/fields/field-description.php';
                $ret = ob_get_contents();
                ob_end_clean();
                return $ret;
            }
        }

        public function gen_field($field = 'text', $name = '', $id = '', $value = '', $desc = '', $placeholder = '', $options, $required = false, $pattern = '') {
            $patt = '';
            $placeholder = $this->get_field_placeholder($placeholder);
            if ($required == true) {$required = 'required';} else { $required = '';}

            switch ($field) {
            case 'text':
                if (!empty($pattern)) {
                    $patt = 'pattern="' . $pattern . '"';
                }
                $value = self::removeslashes($value);
                echo '<input type="text" name="' . $name . '" id="' . $id . '" placeholder="' . $placeholder . '" value="' . $value . '" ' . $required . ' ' . $patt . ' ' . apply_filters('cfwsp_field_' . $name, $value) . '>';
                echo $this->get_field_desc($desc);
                break;
            case 'textarea':
                $value = self::removeslashes($value);
                echo '<textarea name="' . $name . '" id="' . $id . '" placeholder="' . $placeholder . '" ' . $required . ' ' . apply_filters('cfwsp_field_' . $name, $value) . '>' . $value . '</textarea>';
                echo $this->get_field_desc($desc);
                break;
            case 'select':
                $options = self::removeslashes($options);
                $options = explode(",", $options);
                $options = array_map('trim', $options);
                echo '<select name="' . $name . '" id="' . $id . '" ' . $required . ' ' . apply_filters('cfwsp_field_' . $name, $value) . '>';
                if (is_array($options)) {
                    foreach ($options as $val) {
                        if ($value == $val) {
                            echo '<option value="' . $val . '" selected="selected">' . $val . '</option>';
                        } else {
                            echo '<option value="' . $val . '">' . $val . '</option>';
                        }
                    }
                }
                echo '</select>';
                echo $this->get_field_desc($desc);
                break;
            case 'checkbox':
                $options = self::removeslashes($options);
                $options = explode(",", $options);
                $options = array_map('trim', $options);
                if (is_array($options)) {
                    foreach ($options as $val) {
                        if (is_array($value) and in_array($val, $value)) {
                            echo '<label class="cbrb"><input type="checkbox" name="' . $name . '[]" id="' . $id . '" value="' . $val . '" checked="checked" ' . $required . ' ' . apply_filters('cfwsp_field_' . $name, $value) . '/>' . $val . '</label>';
                        } else {
                            echo '<label class="cbrb"><input type="checkbox" name="' . $name . '[]" id="' . $id . '" value="' . $val . '" ' . $required . ' ' . apply_filters('cfwsp_field_' . $name, $value) . '/>' . $val . '</label>';
                        }
                    }
                    echo $this->get_field_desc($desc);
                    $this->checkbox_js_call($name);
                }
                break;
            case 'radio':
                $options = self::removeslashes($options);
                $options = explode(",", $options);
                $options = array_map('trim', $options);
                if (is_array($options)) {
                    foreach ($options as $val) {
                        if ($value == $val) {
                            echo '<label class="cbrb"><input type="radio" name="' . $name . '" id="' . $id . '" value="' . $val . '" checked="checked" ' . $required . ' ' . apply_filters('cfwsp_field_' . $name, $value) . '/>' . $val . '</label>';
                        } else {
                            echo '<label class="cbrb"><input type="radio" name="' . $name . '" id="' . $id . '" value="' . $val . '" ' . $required . ' ' . apply_filters('cfwsp_field_' . $name, $value) . '/>' . $val . '</label>';
                        }
                    }
                }
                echo $this->get_field_desc($desc);
                break;
            case 'date':
                echo '<input type="text" name="' . $name . '" class="wp_reg_date" id="' . $id . '" placeholder="' . $placeholder . '" value="' . $value . '" ' . $required . ' ' . apply_filters('cfwsp_field_' . $name, $value) . '>';
                echo $this->get_field_desc($desc);
                $this->date_js_call();
                break;
            case 'time':
                $value = self::removeslashes($value);
                echo '<input type="text" name="' . $name . '" class="wp_reg_time" id="' . $id . '" placeholder="' . $placeholder . '" value="' . $value . '" ' . $required . ' ' . apply_filters('cfwsp_field_' . $name, $value) . '>';
                echo $this->get_field_desc($desc);
                $this->date_js_call();
                break;
            case 'file':
                echo '<input type="file" name="' . $name . '" id="' . $id . '" ' . ($required != '' && $value == '' ? 'required' : '') . ' ' . apply_filters('cfwsp_field_' . $name, $value) . '>';
                echo $this->get_field_desc($desc);
                if ($value) {
                    echo '<p><a href="' . $value . '" target="_blank">' . $this->get_file_name($value) . '</a><br>';
                    echo '<input type="checkbox" name="' . $name . '_remove" id="' . $id . '_remove" value="Yes" /> ' . __('Check to remove this file.') . '</p>';
                }
                break;
            default:
                $value = self::removeslashes($value);
                if (!empty($pattern)) {
                    $patt = 'pattern="' . $pattern . '"';
                }
                echo '<input type="text" name="' . $name . '" id="' . $id . '" placeholder="' . $placeholder . '" value="' . $value . '" ' . $required . ' ' . $patt . ' ' . apply_filters('cfwsp_field_' . $name, $value) . '>';
                echo $this->get_field_desc($desc);
                break;
            }
        }

        public function date_js_call() {
            include CFWS_DIR_PATH . '/view/frontend/fields/date-scripts.php';
        }

        public function checkbox_js_call($name = '') {
            include CFWS_DIR_PATH . '/view/frontend/fields/checkbox-scripts.php';
        }

        public function is_coustom_contact_body($id) {
            return get_post_meta($id, '_contact_form_body', true) == '' ? false : true;
        }

        public function get_field_from_name($id, $field_name) {
            if ($id == '') {
                return;
            }

            if ($field_name == '') {
                return;
            }

            $field_names = $this->get_fields_names($id);
            $key = array_search($field_name, $field_names, true);
            return $key;
        }

        public function get_fields_names($id) {
            $extra_fields = get_post_meta($id, '_contact_extra_fields', true);
            $field_names = [];
            if (is_array($extra_fields)) {
                foreach ($extra_fields as $key => $value) {
                    $field_names[] = $value['field_name'];
                }
            }
            return $field_names;
        }

        public function contact_form_fields($id = '') {
            if ($id == '') {
                return;
            }
            if (!$this->is_coustom_contact_body($id)) {
                $this->contact_form_body_auto($id);
            } else {
                $this->contact_form_body($id);
            }
            $this->contact_form_captcha($id);
        }

        public function contact_form_fields_from_body($id, $field_name) {
            if ($id == '') {
                return;
            }

            if ($field_name == '') {
                return;
            }

            $extra_fields = get_post_meta($id, '_contact_extra_fields', true);
            $key = $this->get_field_from_name($id, $field_name);

            ob_start();

            if ($key !== FALSE) {
                $field_data = $extra_fields[$key];
                if ($field_data['field_type'] == 'title') {
                    $title_name = $field_data['field_name'];
                    $title_label = $field_data['field_label'];
                    include CFWS_DIR_PATH . '/view/frontend/fields/title.php';
                } elseif ($field_data['field_type'] == 'action_hook') {
                    do_action($field_data['field_name'], $id);
                } else {
                    $required = $field_data['field_required'] == 'Yes' ? true : false;
                    $this->gen_field($field_data['field_type'], $field_data['field_name'], $field_data['field_name'], '', $field_data['field_desc'], $field_data['field_placeholder'], $field_data['field_values'], $required, $field_data['field_title'], $field_data['field_pattern']);
                }
            }

            $ret = ob_get_contents();
            ob_end_clean();
            return $ret;
        }

        public function contact_form_body($id) {
            if ($this->is_coustom_contact_body($id)) {
                $contact_form_body = get_post_meta($id, '_contact_form_body', true);
                $field_names = $this->get_fields_names($id);
                if (count($field_names)) {
                    foreach ($field_names as $key => $value) {
                        $contact_form_body = str_replace('[' . $value . ']', $this->contact_form_fields_from_body($id, $value), $contact_form_body);
                    }
                }

                echo html_entity_decode($contact_form_body);
            }
        }

        public function contact_form_body_auto($id) {
            $extra_fields = get_post_meta($id, '_contact_extra_fields', true);
            if (is_array($extra_fields)) {
                foreach ($extra_fields as $key => $value) {
                    if ($value['field_type'] == 'title') {
                        $title_name = $value['field_name'];
                        $title_label = $value['field_label'];
                        include CFWS_DIR_PATH . '/view/frontend/fields/title.php';
                        echo $this->get_field_desc($value['field_desc']);
                    } elseif ($value['field_type'] == 'action_hook') {
                        do_action($value['field_name'], $id);
                    } else {
                        $required = $value['field_required'] == 'Yes' ? true : false;
                        include CFWS_DIR_PATH . '/view/frontend/fields/field.php';
                    }
                }
            }
        }

        public function contact_form_captcha($id) {
            if ($id == '') {
                return;
            }
            $contact_enable_captcha = get_post_meta($id, '_contact_enable_security', true);
            if ($contact_enable_captcha == 'Yes') {
                include CFWS_DIR_PATH . '/view/frontend/fields/default-captcha.php';
            }
        }

        public function captcha_image() {
            include CFWS_DIR_PATH . '/view/frontend/fields/captcha-image.php';
        }

    }
}