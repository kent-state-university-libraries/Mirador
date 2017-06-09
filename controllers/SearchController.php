<?php

/**
 * A class to build an IIIF Search API 1.0 response
 *
 * @package Mirador
 *
 * @author Joe Corall <jcorall@kent.edu>
 */

class Mirador_SearchController extends Omeka_Controller_AbstractActionController
{

    public function showAction()
    {
        global $base_url;
        $limit = 25;
        $page  = $this->_request->page ? $this->_request->page : 1;
        $start = ($page-1) * $limit;

        $uri = explode('/', $_SERVER['REQUEST_URI']);
        $uri = array_pop($uri);
        $uri = explode('?', $uri);
        $item_id = array_shift($uri);

        // no chance of integrating with solr since we need to know the exact
        // file ID that's metadata matched the search term so when they click on it
        // in the mirador viewer it jumps right to that file
        $query = "SELECT record_id, `text`, metadata FROM element_texts t
          INNER JOIN files f ON f.id = t.record_id AND t.record_type = 'File'
          WHERE f.item_id = ? AND t.text LIKE ?";

        $results = get_db()->query($query, array($item_id, '%' . $this->_request->q . '%'));
        $this->view->json = array(
          "resources" => array(),
          "@context" => "http://iiif.io/api/search/1/context.json",
          "@type" => "sc:AnnotationList",
          'startIndex' => 0,
          "@id" => $base_url . "/iiif-search/$item_id"
        );
        $count = &$this->view->json['within']['total'];
        while ($row = $results->fetchObject()) {
          ++$count;
          $metadata = json_decode($row->metadata);
          $this->view->json['resources'][] = array(
            "resource" => array(
              "@type" => "cnt:ContentAsText",
              "chars" => $this->_request->q,
            ),
            "motivation" => "sc:painting",
            "@type" => "oa:Annotation",
            "@id" => $base_url . "/items/annotation/$item_id/{$row->record_id}/image#xywh=0,0,{$metadata->video->resolution_x},{$metadata->video->resolution_y}",
            "on" => $base_url . "/items/canvas/$item_id/{$row->record_id}#xywh=0,0,{$metadata->video->resolution_x},{$metadata->video->resolution_y}"
          );
        }
    }
}

