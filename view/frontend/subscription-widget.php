<div class="contact-form-wrap">
  <div
    class="
      contact-message
      sub-<?php
      echo
      $instance['wid_subscribe_form'];
      ?>-err-msg
    "
  ></div>

  <form
    name="sub"
    id="sub-<?php echo $instance['wid_subscribe_form']; ?>"
    action=""
    method="post"
  >
    <?php wp_nonce_field('ap_contact_value', 'ap_contact_field');?>
    <input
      type="hidden"
      name="sub_form_id"
      id="sub_form_id"
      value="<?php echo $instance['wid_subscribe_form']; ?>"
    />
    <input type="hidden" name="sub_form_process" value="do_process_ajax" />

    <div
      class="
        contact-fields-wrap
        id-<?php
        echo
        $instance['wid_subscribe_form'];
        ?>
      "
    >
      <?php $this->subscribe_form_fields($instance['wid_subscribe_form']);?>

      <div class="contact-submit">
        <input
          type="submit"
          name="submit"
          value="<?php _e('Submit', 'contact-form-with-shortcode');?>"
        />
      </div>
      <div
        id="sub-<?php echo $instance['wid_subscribe_form']; ?>-wait-msg"
        style="display: none"
      >
        <?php _e('Please wait..', 'contact-form-with-shortcode');?>
      </div>
    </div>
  </form>

  <?php
if (isset($instance['wid_subscribe_form_text'])) {
    ?>
  <p><?php echo $instance['wid_subscribe_form_text']; ?></p>
  <?php
}
?>
</div>
