<script type="text/javascript">
    function contact_ap_submit(f){
        var con_form_id = jQuery('#con_form_id').val();
        jQuery.ajax({
        data: new FormData(f),
        dataType:"json",
        type: "POST",
        contentType: false,
        processData:false,
        beforeSend: function( renponse ) { jQuery('.con-'+con_form_id+'-wait-msg').show(); }
        })
        .done(function( renponse ) {
            jQuery('.con-'+con_form_id+'-wait-msg').hide();
            jQuery('.con-'+renponse.id+'-err-msg').show();
            jQuery('.con-'+renponse.id+'-err-msg').html(renponse.msg);
            if(renponse.status == 'success'){
                jQuery('#con-'+con_form_id)[0].reset();
            }
            refreshCaptcha();
        });
        return false;
    }
</script>