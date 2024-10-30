
<div class="<?php echo $cfc->is_coustom_contact_body($instance['wid_contact_form']) == true ? 'contact-form-wrap-custom' : 'contact-form-wrap'; ?>">

<div class="contact-message con-<?php echo $instance['wid_contact_form']; ?>-err-msg"></div>

<form
  name="con"
  id="con-<?php echo $instance['wid_contact_form']; ?>"
  action=""
  method="post"
  enctype="multipart/form-data"
>
  <?php wp_nonce_field('ap_contact_value', 'ap_contact_field');?>
  <input
    type="hidden"
    name="con_form_id"
    id="con_form_id"
    value="<?php echo $instance['wid_contact_form']; ?>"
  />
  <input type="hidden" name="con_form_process" value="do_process_ajax" />

  <div class="contact-fields-wrap id-<?php echo $instance['wid_contact_form']; ?>">

    <?php $cfc->contact_form_fields($instance['wid_contact_form']);?>

    <div class="<?php echo $cfc->is_coustom_contact_body($instance['wid_contact_form']) == true ? 'contact-submit-custom' : 'contact-submit'; ?>">
      <input
        type="submit"
        name="submit"
        value="<?php _e('Submit', 'contact-form-with-shortcode');?>"
      />
    </div>

    <div
      class="contact-message contact-message-wait con-<?php echo $instance['wid_contact_form']; ?>-wait-msg"
      style="display: none"
    >
      <?php _e('Please wait..', 'contact-form-with-shortcode');?>
    </div>

    <div class="contact-message con-<?php echo $instance['wid_contact_form']; ?>-err-msg"></div>

  </div>
</form>
</div>