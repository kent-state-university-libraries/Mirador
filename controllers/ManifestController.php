<?php

class Mirador_ManifestController extends Omeka_Controller_AbstractActionController
{

    public function showAction()
    {
      global $base_url;
      if (empty($_GET['item_id'])) {
        $item = FALSE;
      }
      // if no item ID, show all files in the system
      else {
        $item = $this->view->item = get_record_by_id('Item', $_GET['item_id']);
      }

      $option = APPLICATION_ENV === 'production' ? 'mirador_iiif_server_prod' : 'mirador_iiif_server_test';
      $iiif_server = get_option($option);

      $id = $base_url;
      if ($item) {
        $id .= '/items/show/' . $item->id;
      }

      $this->view->json = array(
        '@context' => 'http://www.shared-canvas.org/ns/context.json',
        '@id' => $base_url . '/manifest.json?item_id=' . ($item ? $item->id : 0),
        '@type' => 'sc:Manifest',
        'label' => $item ? metadata($item, array('Dublin Core', 'Title')) : get_option('site_title'),
        'description' => '',
        'sequences' => array(
          array(
            '@id' => $id,
            '@type' => 'sc:Sequence',
            'label' => 'Item Show',
            'canvases' => array(),
          ),
        ),
      );

      if ($item) {
        foreach ($item->Files as $count => $file) {
          $this->add_canvas($file, $item, $count, $this, $iiif_server, $id);
        }
      }
      else {
        $files = get_db()->query('SELECT id, original_filename, metadata,mime_type, filename FROM files order BY original_filename');
        $count = 0;
        while ($file = $files->fetchObject()) {
          $this->add_canvas($file, $item, $count, $this, $iiif_server, $id);
          ++$count;
        }
      }
    }

    private function add_canvas($file, $item, $count, $that, $iiif_server, $id) {
        global $base_url;
        if ($file->mime_type !== 'image/tiff') return;

        $metadata = json_decode($file->metadata);

        if ($count) {
          $canvas_id = $id . '/' . $file->id;
        }
        else {
          $canvas_id = $base_url . '/items/canvas/' . ($item ? $item->id : $count);
        }

        $that->view->json['sequences'][0]['canvases'][] = array(
          '@id' => $canvas_id,
          '@type' => 'sc:Canvas',
          'label' => $file->original_filename,
          'width' => $metadata->video->resolution_x,
          'height' => $metadata->video->resolution_y,
          'images' => array(
            array(
              '@id' => $base_url . "/items/anno/".($item ? $item->id : 'all')."/{$file->id}",
              '@type' => 'oa:Annotation',
              'motivation' => 'sc:painting',
              'label' => 'Image',
              'description' => 'Image',
              'resource' => array(
                '@id' => $iiif_server . "/{$file->filename}/full/full/0/default.jpg",
                '@type' => 'dctypes:Image',
                'format' => 'image/jpeg',
                'width' => $metadata->video->resolution_x,
                'height' => $metadata->video->resolution_y,
                'service' => array(
                  '@id' => $iiif_server . '/' . $file->filename,
                  '@context' => 'http://iiif.io/api/image/2/context.json',
                  'profile' => 'http://iiif.io/api/image/2/profiles/level2.json',
                ),
              ),
              'on' => $canvas_id,
            )
          ),
        );
    }
}
