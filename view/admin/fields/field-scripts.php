<script type="text/javascript">
  function genFieldNameCF(t) {
    var temp = t.value
      .toLowerCase()
      .replace(/ +/g, "_")
      .replace(/[^a-z0-9_]/g, "")
      .trim();
    jQuery(t).parent().next().find('input[name="field_name"]').val(temp);
  }
  function genFieldNameEditCF(t) {
    var temp = t.value
      .toLowerCase()
      .replace(/ +/g, "_")
      .replace(/[^a-z0-9_]/g, "")
      .trim();
    jQuery(t).parent().next().find('input[name="field_names[]"]').val(temp);
  }
  function selectFieldCF(t) {
    addNewFieldCF(t.value);
  }
  function delNewFieldCF(t) {
    jQuery(t).parent().parent().remove();
  }
  function delFieldCF(t) {
    jQuery(t).parent().parent().parent().remove();
  }
  function editFieldCF(t) {
    jQuery(t).parent().parent().siblings("div.custom-field-box-form").show();
  }
  function closeFieldCF(t) {
    jQuery(t).closest("div.custom-field-box-form").hide();
  }
  function addNewFieldCF(field) {
    jQuery.ajax({
      type: "POST",
      async: false,
      data: { option: "addNewFieldCF", field: field },
      success: function (data) {
        jQuery("#newFieldForm").html(data);
      },
    });
  }
  function saveFieldCF(field_type) {
    jQuery.ajax({
      type: "POST",
      async: false,
      data: {
        option: "saveFieldCF",
        field_type: field_type,
        field_label: jQuery("#field_label").val(),
        field_name: jQuery("#field_name").val(),
        field_desc: jQuery("#field_desc").val(),
        field_placeholder: jQuery("#field_placeholder").val(),
        field_required: jQuery("#field_required").val(),
        field_title: jQuery("#field_title").val(),
        field_pattern: jQuery("#field_pattern").val(),
        field_values: jQuery("#field_values").val(),
      },
      success: function (data) {
        jQuery("#newFields").prepend(data);
        jQuery("#field_list").val("");
        jQuery("#field_label").val("");
        jQuery("#field_name").val("");
        jQuery("#field_desc").val("");
        jQuery("#field_placeholder").val("");
        jQuery("#field_required").val("");
        jQuery("#field_title").val("");
        jQuery("#field_pattern").val("");
        jQuery("#field_values").val("");
        jQuery("#newFieldForm").html("");
      },
    });
  }
</script>