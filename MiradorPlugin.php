<?php

/**
 * @author  Joe Corall <jcorall@kent.edu>
 *
 * @todo allow setting mirador options in UI/config
 * @todo support other image formats than TIFF
 * @todo possibly incorporate derivatives using IIIF?
 * @todo allow more options than item_id. Right now the only options are "show one item's files" or "show all items' files"
 */

class MiradorPlugin extends Omeka_Plugin_AbstractPlugin
{
    protected $_hooks = array(
        'define_routes',
        'initialize',
        'config_form',
        'config',
    );


    public function hookInitialize()
    {

        add_file_display_callback(array(
            'mimeTypes' => array('image/tiff'),
            'fileExtensions' => array('tif', 'tiff'),
            ), 'MiradorPlugin::viewer', array());
    }

    public function hookDefineRoutes($args)
    {
        // Don't add these routes on the admin side to avoid conflicts.
        if (is_admin_theme()) {
            return;
        }

        $args['router']->addRoute(
            'ul_show_page_manifest',
            new Zend_Controller_Router_Route(
                'manifest.json',
                array(
                    'module'       => 'mirador',
                    'controller'   => 'manifest',
                    'action'       => 'show',
                    'id'           => 'mirador_manifest'
                )
            )
        );
        $args['router']->addRoute(
            'mirador-viewer',
            new Zend_Controller_Router_Route(
                'mirador-viewer',
                array(
                    'module'       => 'mirador',
                    'controller'   => 'viewer',
                    'action'       => 'show',
                    'id'           => 'mirador_viewer',
                )
            )
        );
    }


    public static function viewer($file, $options)
    {
        static $items = array();
        // only return one file when viewing an item gallery
        // the mirador viewer will have all files for the item, so we only need one instance
        if (!isset($items[$file->item_id])) {
            $query = array('item_id' => $file->item_id);
            $items[$file->item_id] = TRUE;
            return '<div class="flex-video">
              <iframe title="Mirador" src="'.url('mirador-viewer', $query) . '" allowfullscreen="true" webkitallowfullscreen="true" mozallowfullscreen="true"></iframe>
            </div>';
        }
        else {
            return '';
        }
    }

    public function hookConfigForm()
    {
        require_once 'config-form.php';
    }

    /**
     * Save the config form results
     */
    public function hookConfig()
    {
        $options = array(
            'mirador_iiif_server_prod',
            'mirador_iiif_server_test',
        );
        foreach ($options as $option) {
            if (empty($_POST[$option])) {
                delete_option($option);
            }
            else {
                set_option($option, $_POST[$option]);
            }
        }
    }
}
