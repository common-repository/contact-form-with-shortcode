<?php

class Subscribe_Form_Wid extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'Subscribe_Form_Wid',
            'Subscribe Form Widget',
            array('description' => __('Subscribe form widget', 'contact-form-with-shortcode'))
        );
    }

    public function widget($args, $instance) {
        extract($args);
        $wid_title = apply_filters('widget_title', $instance['wid_title']);
        echo $args['before_widget'];
        if (!empty($wid_title)) {
            echo $args['before_title'] . $wid_title . $args['after_title'];
        }

        $sfw = new Subscription_Widget_Class;
        $sfw->subscribeWidBody($instance);
        echo $args['after_widget'];
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['wid_title'] = sanitize_text_field($new_instance['wid_title']);
        $instance['wid_subscribe_form'] = sanitize_text_field($new_instance['wid_subscribe_form']);
        $instance['wid_subscribe_form_text'] = sanitize_text_field($new_instance['wid_subscribe_form_text']);
        return $instance;
    }

    public function form($instance) {
        $wid_title = $instance['wid_title'];
        $wid_subscribe_form = $instance['wid_subscribe_form'];
        $wid_subscribe_form_text = $instance['wid_subscribe_form_text'];
        ?>
		<p><label for="<?php echo $this->get_field_id('wid_title'); ?>"><?php _e('Title:');?> </label>
		<input class="widefat" id="<?php echo $this->get_field_id('wid_title'); ?>" name="<?php echo $this->get_field_name('wid_title'); ?>" type="text" value="<?php echo $wid_title; ?>" />
		</p>
		<p><label for="<?php echo $this->get_field_id('wid_subscribe_form'); ?>"><?php _e('Form:');?> </label>
		<select id="<?php echo $this->get_field_id('wid_subscribe_form'); ?>" name="<?php echo $this->get_field_name('wid_subscribe_form'); ?>" class="widefat">
			<option value="">-</option>
			<?php $this->subscribeFormSelected($wid_subscribe_form);?>
		</select>
		</p>
		<p><label for="<?php echo $this->get_field_id('wid_subscribe_form_text'); ?>"><?php _e('Text:');?> </label>
		<input class="widefat" id="<?php echo $this->get_field_id('wid_subscribe_form_text'); ?>" name="<?php echo $this->get_field_name('wid_subscribe_form_text'); ?>" type="text" value="<?php echo $wid_subscribe_form_text; ?>" />
		</p>
		<?php
}

    public function subscribeFormSelected($sel = '') {
        $args = array('post_type' => 'subscribe_form', 'posts_per_page' => -1);
        $c_forms = get_posts($args);
        foreach ($c_forms as $c_form): setup_postdata($c_form);
            if ($sel == $c_form->ID) {
                echo '<option value="' . $c_form->ID . '"  selected="selected">' . $c_form->post_title . '</option>';
            } else {
                echo '<option value="' . $c_form->ID . '">' . $c_form->post_title . '</option>';
            }
        endforeach;
        wp_reset_postdata();
    }
}
