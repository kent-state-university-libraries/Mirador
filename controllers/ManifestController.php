<?php

class Mirador_ManifestController extends Omeka_Controller_AbstractActionController
{

    public function showAction()
    {
      global $base_url;
      $item = $this->view->item = get_record_by_id('Item', $_GET['item_id']);
      $iiif_server = get_option('mirador_iiif_server');
      $total = count($item->Files) - 1;

      $this->view->json = array(
        '@context' => 'http://www.shared-canvas.org/ns/context.json',
        '@id' => $base_url . '/manifest.json?item_id=' . $item->id,
        '@type' => 'sc:Manifest',
        'label' => metadata($item, array('Dublin Core', 'Title')),
        'description' => '',
        'sequences' => array(
          array(
            '@id' => $base_url . '/items/show/' . $item->id,
            '@type' => 'sc:Sequence',
            'label' => 'Item Show',
            'canvases' => array(),
          ),
        ),
      );

      foreach ($item->Files as $count => $file) {
        if ($file->mime_type !== 'image/tiff') continue;

        if (APPLICATION_ENV === 'production') {
          $file->filename = 'prod/' . $file->filename;
        }
        else {
          $file->filename = 'test/' . $file->filename;
        }

        $metadata = json_decode($file->metadata);

        if ($count) {
          $canvas_id = $base_url . "/items/canvas/{$item->id}/{$file->id}";
        }
        else {
          $canvas_id =$base_url . "/items/canvas/{$item->id}";
        }

        $this->view->json['sequences'][0]['canvases'][] = array(
          '@id' => $canvas_id,
          '@type' => 'sc:Canvas',
          'label' => $file->original_filename,
          'width' => $metadata->video->resolution_x,
          'height' => $metadata->video->resolution_y,
          'images' => array(
            array(
              '@id' => $base_url . "/items/anno/{$item->id}/{$file->id}",
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
}
