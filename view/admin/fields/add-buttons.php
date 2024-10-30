<p>
  <input
    type="button"
    name="save"
    value="<?php _e('Add');?>"
    class="button button-primary button-large"
    onclick="saveFieldCF('<?php echo $field; ?>');"
  />&nbsp;<input
    type="button"
    name="del"
    value="<?php _e('Delete');?>"
    class="button button-primary button-large"
    onclick="delNewFieldCF(this);"
  />
</p>
