<p>
  <span class="custom-field-required"><?php _e('Title Text');?>*</span>
  <input
    type="text"
    name="field_label"
    id="field_label"
    class="widefat"
    placeholder="<?php _e('Title Text');?>"
    required
    onblur="genFieldNameCF(this)"
  />
</p>

<p>
  <span class="custom-field-required"><?php _e('Title Name');?>*</span>
  <input
    type="text"
    name="field_name"
    id="field_name"
    class="widefat"
    placeholder="<?php _e('Title Name');?>"
    required
  />
  <?php _e('Use only letters, this will be class name');?>
</p>

<p>
  <?php _e('Description');?>
  <input
    type="text"
    name="field_desc"
    id="field_desc"
    class="widefat"
    placeholder="<?php _e('Description');?> "
  />
</p>

<input
  type="hidden"
  name="field_values"
  id="field_values"
  value="not_required"
/>
