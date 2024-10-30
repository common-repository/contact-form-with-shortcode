<div class="contact-fields">
  <span class="title"><?php echo _e('Email', 'contact-form-with-shortcode'); ?></span>
  <?php $cfc->gen_field('email', 'sub_email', 'sub_email', '', '', '', '', true);?>
</div>

<?php if ($include_name_in_subscription == 'Yes') {?>
<div class="contact-fields">
  <span class="title"><?php echo _e('Name', 'contact-form-with-shortcode'); ?></span>
  <?php $cfc->gen_field('text', 'sub_name', 'sub_name', '', '', '', '', $name_required);?>
</div>
<?php }?>