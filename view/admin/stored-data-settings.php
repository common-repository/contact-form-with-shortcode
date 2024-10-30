<div class="wrap">
  <h2><?php _e('Stored Data','contact-form-with-shortcode');?></h2>

  <div id="poststuff">
    <div id="post-body" class="metabox-holder">
      <div id="post-body-content">
        <div class="meta-box-sortables ui-sortable">
            <form method="post" enctype="multipart/form-data">
            <?php
            $this->forms_db_obj->prepare_items();
            $this->forms_db_obj->custom_display(); 
            ?>
            </form>
        </div>
      </div>
    </div>
    <br class="clear">
  </div>
</div>