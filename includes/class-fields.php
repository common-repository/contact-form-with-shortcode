<?php

if (!class_exists('CFWS_Fields_Class')) {
    class CFWS_Fields_Class {

        public $fields = array(
            'title' => 'Title',
            'text' => 'Text Field',
            'textarea' => 'Textarea',
            'select' => 'Select Drop Down',
            'checkbox' => 'Check Boxes',
            'radio' => 'Radio Buttons',
            'date' => 'Date Picker',
            'time' => 'Time Picker',
            'file' => 'File Upload (Attachments)',
        );

        public function __construct() {
            add_action('admin_init', array($this, 'field_save_settings'));
        }

        public static function removeslashes($string) {
            $string = implode("", explode("\\", $string));
            return stripslashes(trim($string));
        }

        public function field_save_settings() {
            if (isset($_POST['option']) and sanitize_text_field($_POST['option']) == "addNewFieldCF") {
                $field = sanitize_text_field($_REQUEST['field']);
                $this->new_field_form($field);
                exit;
            }

            if (isset($_POST['option']) and sanitize_text_field($_POST['option']) == "saveFieldCF") {
                $args = array();

                $args['field_type'] = sanitize_text_field(@$_REQUEST['field_type']);
                $args['field_label'] = sanitize_text_field(@$_REQUEST['field_label']);
                $args['field_name'] = str_replace(" ", "_", strtolower(trim(sanitize_text_field(@$_REQUEST['field_name']))));
                $args['field_desc'] = sanitize_text_field(@$_REQUEST['field_desc']);
                $args['field_placeholder'] = sanitize_text_field(@$_REQUEST['field_placeholder']);
                $args['field_required'] = sanitize_text_field(@$_REQUEST['field_required']);
                $args['field_title'] = sanitize_text_field(@$_REQUEST['field_title']);
                $args['field_pattern'] = sanitize_text_field(@$_REQUEST['field_pattern']);
                $args['field_values'] = implode(',', array_map('trim', explode(',', sanitize_text_field(@$_REQUEST['field_values']))));

                echo $this->added_field($args);
                exit;
            }
        }

        public function get_field_placeholder($placeholder = '') {
            if ($placeholder) {
                $placeholder = self::removeslashes($placeholder);
                return __($placeholder);
            }
        }

        public function new_field_form($field) {
            echo '<div class="custom-field-new-form">';
            echo '<h3 id="new-field-title">' . __('New Field') . ' - ' . $this->fields[$field] . '</h3>';

            switch ($field) {
            case 'title':
                include CFWS_DIR_PATH . '/view/admin/fields/add-title.php';
                break;
            case 'text':
                include CFWS_DIR_PATH . '/view/admin/fields/add-text.php';
                break;
            case 'textarea':
                include CFWS_DIR_PATH . '/view/admin/fields/add-textarea.php';
                break;
            case 'select':
                include CFWS_DIR_PATH . '/view/admin/fields/add-select.php';
                break;
            case 'checkbox':
                include CFWS_DIR_PATH . '/view/admin/fields/add-checkbox.php';
                break;
            case 'radio':
                include CFWS_DIR_PATH . '/view/admin/fields/add-radio.php';
                break;
            case 'date':
                include CFWS_DIR_PATH . '/view/admin/fields/add-date.php';
                break;
            case 'time':
                include CFWS_DIR_PATH . '/view/admin/fields/add-time.php';
                break;
            case 'file':
                include CFWS_DIR_PATH . '/view/admin/fields/add-file.php';
                break;
            case 'action_hook':
                include CFWS_DIR_PATH . '/view/admin/fields/add-action-hook.php';
                break;
            default:
                include CFWS_DIR_PATH . '/view/admin/fields/add-text.php';
                break;
            }

            include CFWS_DIR_PATH . '/view/admin/fields/add-buttons.php';

            echo '</div>';
        }

        public function fields_list() {
            $ret = '';
            foreach ($this->fields as $key => $value) {
                $ret .= '<button class="button buttop-ap-margin-bottom" onclick="selectFieldCF(this)" value="' . $key . '">' . __($value) . '</button>';
                $ret .= ' ';
            }
            return $ret;
        }

        public function added_field($args) {

            $field_type = self::removeslashes($args['field_type']);
            $field_label = self::removeslashes($args['field_label']);
            $field_name = self::removeslashes($args['field_name']);
            $field_desc = self::removeslashes($args['field_desc']);
            $field_placeholder = self::removeslashes($args['field_placeholder']);
            $field_required = self::removeslashes($args['field_required']);
            $field_title = self::removeslashes($args['field_title']);
            $field_pattern = self::removeslashes($args['field_pattern']);
            $field_values = self::removeslashes($args['field_values']);

            $ret = '<div class="custom-field-box">';
            $ret .= '<div class="custom-field-box-info">';

            $ret .= '<h3 id="new-field-title">' . $field_label . ' - ' . $this->fields[$field_type] . '</h3>';

            $ret .= '<span class="custom-field-label">' . __('Label') . ':</span> ' . $field_label;
            $ret .= ',&nbsp;';
            $ret .= '<span class="custom-field-label">' . __('Name') . ':</span> ' . $field_name;
            $ret .= ',&nbsp;';
            $ret .= '<span class="custom-field-label">' . __('Type') . ':</span> ' . $field_type;
            $ret .= ',&nbsp;';

            if ($field_required == 'Yes') {
                $ret .= '<span class="custom-field-label">' . __('Required') . '</span>';
                $ret .= ',&nbsp;';
                $ret .= '<span class="custom-field-label">' . __('Required Message') . ':</span> ' . $field_title;
                $ret .= ',&nbsp;';
            }

            $ret .= '<span class="custom-field-label">' . __('Description') . ':</span> ' . $this->restrict_text($field_desc, 30);
            $ret .= ',&nbsp;';
            $ret .= '<span class="custom-field-label">' . __('Mail Body Code') . ':</span> #' . $field_name . '#';
            $ret .= ',&nbsp;';
            $ret .= '<span class="custom-field-label">' . __('Form Body Code') . ':</span> [' . $field_name . ']';

            if ($field_type == 'action_hook') {
                $ret .= ',&nbsp;';
                $ret .= '<span class="custom-field-label">' . __('Field Hook') . ':</span> ' . $field_name;
                $ret .= ',&nbsp;';
                $ret .= '<span class="custom-field-label">' . __('Field Value Filter') . ':</span> ' . $field_name . '_value';
            }

            $ret .= '<p><input type="button" name="edit" value="' . __('Edit') . '" style="margin-right:2px;" class="button button-primary button-large" onclick="editFieldCF(this);">';

            $ret .= '<input type="button" name="del" value="' . __('Delete') . '" style="" class="button button-primary button-large" onclick="delFieldCF(this);">';
            $ret .= '&nbsp;';
            $ret .= '<span class="dragit button" style="cursor:n-resize;">' . __('Click & Drag to Sort') . '</span></p>';

            $ret .= '</div>';

            $ret .= '<div class="custom-field-box-form">';

            switch ($field_type) {
            case 'title':

                ob_start();
                include CFWS_DIR_PATH . '/view/admin/fields/edit-title.php';
                $ret .= ob_get_contents();
                ob_end_clean();

                break;
            case 'text':

                ob_start();
                include CFWS_DIR_PATH . '/view/admin/fields/edit-text.php';
                $ret .= ob_get_contents();
                ob_end_clean();

                break;
            case 'textarea':

                ob_start();
                include CFWS_DIR_PATH . '/view/admin/fields/edit-textarea.php';
                $ret .= ob_get_contents();
                ob_end_clean();

                break;
            case 'select':

                ob_start();
                include CFWS_DIR_PATH . '/view/admin/fields/edit-select.php';
                $ret .= ob_get_contents();
                ob_end_clean();

                break;
            case 'checkbox':

                ob_start();
                include CFWS_DIR_PATH . '/view/admin/fields/edit-checkbox.php';
                $ret .= ob_get_contents();
                ob_end_clean();

                break;
            case 'radio':

                ob_start();
                include CFWS_DIR_PATH . '/view/admin/fields/edit-radio.php';
                $ret .= ob_get_contents();
                ob_end_clean();

                break;
            case 'date':

                ob_start();
                include CFWS_DIR_PATH . '/view/admin/fields/edit-date.php';
                $ret .= ob_get_contents();
                ob_end_clean();

                break;
            case 'time':

                ob_start();
                include CFWS_DIR_PATH . '/view/admin/fields/edit-time.php';
                $ret .= ob_get_contents();
                ob_end_clean();

                break;
            case 'file':

                ob_start();
                include CFWS_DIR_PATH . '/view/admin/fields/edit-file.php';
                $ret .= ob_get_contents();
                ob_end_clean();

                break;
            case 'action_hook':

                ob_start();
                include CFWS_DIR_PATH . '/view/admin/fields/edit-action-hook.php';
                $ret .= ob_get_contents();
                ob_end_clean();

                break;
            default:

                ob_start();
                include CFWS_DIR_PATH . '/view/admin/fields/edit-text.php';
                $ret .= ob_get_contents();
                ob_end_clean();

                break;
            }

            ob_start();
            include CFWS_DIR_PATH . '/view/admin/fields/edit-buttons.php';
            $ret .= ob_get_contents();
            ob_end_clean();

            $ret .= '</div>';

            $ret .= '</div>';

            return $ret;
        }

        public function restrict_text($data = '', $limit = 100) {
            $len = strlen($data);
            if ($len <= $limit) {
                return $data;
            }
            return substr($data, 0, $limit) . '..';
        }

        public function saved_extra_fields($extra_fields) {
            if (is_array($extra_fields)) {
                foreach ($extra_fields as $key => $value) {
                    echo $this->added_field($value);
                }
            }
        }

        public function load_field_js() {
            include CFWS_DIR_PATH . '/view/admin/fields/field-scripts.php';
        }
    }
}
