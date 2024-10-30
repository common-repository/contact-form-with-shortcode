<p>
  <span class="custom-field-required"><?php _e('Field Label');?>*</span>
  <input
    type="text"
    name="field_label"
    id="field_label"
    placeholder="<?php _e('Field Label');?>"
    class="widefat"
    required
    onblur="genFieldNameCF(this)"
  />
</p>
<p>
  <span class="custom-field-required"><?php _e('Field Name');?>*</span>
  <input
    type="text"
    name="field_name"
    id="field_name"
    class="widefat"
    placeholder="<?php _e('Field Name');?>"
    required
  /><span><?php _e('Use only letters');?></span>
</p>
<p>
  <?php _e('Field Description');?>
  <input
    type="text"
    name="field_desc"
    id="field_desc"
    class="widefat"
    placeholder="<?php _e('Field Description');?>"
  />
</p>
<p>
  <?php _e('Field is Required');?>
  <select name="field_required" id="field_required" class="widefat">
    <option value="Yes">Yes</option>
    <option value="No">No</option>
  </select>
</p>
<p>
  <?php _e('Field Required Message');?>
  <input
    type="text"
    name="field_title"
    class="widefat"
    id="field_title"
    placeholder="<?php _e('Required Message');?>"
  />
</p>
<p>
  <?php _e('Field Placeholder');?>
  <input
    type="text"
    name="field_placeholder"
    class="widefat"
    id="field_placeholder"
    placeholder="<?php _e('Field Placeholder');?>"
  />
</p>

<input
  type="hidden"
  name="field_values"
  id="field_values"
  value="not_required"
/>
<input
  type="hidden"
  name="field_pattern"
  id="field_pattern"
  value="not_required"
/>
