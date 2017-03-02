<?php global $base_url; ?>

<style type="text/css">
  input[type="url"] {width: 100%;}
</style>

<div>
<h3>Instructions</h3>
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
        <p>This endpoint will be used if your Omeka enviroment is not set to "production"</p>
    </div>
</div>
