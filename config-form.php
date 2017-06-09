<?php global $base_url; ?>

<style type="text/css">
  input[type="url"] {width: 100%;}
</style>

<div>
<h3><?php echo __('Instructions'); ?></h3>
        <p>No trailing "/".</p>
        <p>The {<strong><?php echo __('Production IIIF Server');?></strong>} value you enter below means an Omeka file can be served from both of these URLs:<p>
            <ul>
                <li><?php echo str_replace('/admin', '', $base_url); ?>/files/original/{OMEKA_FILENAME}</li>
                <li>{<strong><?php echo __('Production IIIF Server');?></strong>}/{OMEKA_FILENAME}/full/full/0/default.jpg</li>
            </ul>
</div>
<div class="field">
    <div class="two columns alpha">
        <label for="mirador_iiif_server_prod"><?php echo __('Production IIIF Server');?></label>
    </div>
    <div class="inputs five columns omega">
        <input type="url" name="mirador_iiif_server_prod" value="<?php echo get_option("mirador_iiif_server_prod"); ?>" />
    </div>
</div>
<div class="field">
    <div class="two columns alpha">
        <label for="mirador_iiif_server_test"><?php echo __('Test IIIF Server');?></label>
    </div>
    <div class="inputs five columns omega">
        <input type="url" name="mirador_iiif_server_test" value="<?php echo get_option("mirador_iiif_server_test"); ?>" />
        <p><?php echo __('This endpoint will be used if your Omeka enviroment is not set to "production"'); ?></p>
    </div>
</div>

<h3><?php echo __('Advanced Settings'); ?></h3>
<div class="field">
    <div class="two columns alpha">
        <label for="mirador_override_viewer_in_theme"><?php echo __('Override viewer in theme');?></label>
    </div>
    <div class="inputs five columns omega">
      <input type="checkbox" name="mirador_override_viewer_in_theme" value="1" <?php if (get_option('mirador_override_viewer_in_theme')) echo 'checked="checked"'; ?>/>
      <p><?php echo __('Check this box if do not want to use this plugin\'s default item file override, and instead you want to add the mirador image viewer in your theme template files');?></p>
    </div>
</div>