<?php global $base_url; ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link rel="stylesheet" type="text/css" href="<?php echo web_path_to('mirador/build/mirador/css/mirador-combined.css'); ?>">
    <title></title>
    <style type="text/css">
     #viewer {
       width: 100%;
       height: 100%;
       position: fixed;
     }
    </style>
  </head>
  <body>
    <div id="viewer"></div>
    <script src="<?php echo web_path_to('mirador/build/mirador/mirador.js'); ?>"></script>
    <script type="text/javascript">
      var omeka_mirador = Mirador({
        id: "viewer",
        buildPath: "<?php echo $base_url; ?>/plugins/Mirador/views/public/mirador/",
        data: [{
          manifestUri: "<?php echo $base_url; ?>/manifest.json?item_id=<?php echo isset($_GET['item_id']) ? $_GET['item_id'] : 0; ?>",
          location: "<?php echo get_option('site_title'); ?>"
        }],
        mainMenuSettings: {
          show: false
        },
        windowObjects: [{
          loadedManifest: "<?php echo $base_url; ?>/manifest.json?item_id=<?php echo isset($_GET['item_id']) ? $_GET['item_id'] : 0; ?>",
          canvasID: "<?php echo $base_url; ?>/items/canvas/<?php echo isset($_GET['item_id']) ? $_GET['item_id'] : 0; ?>",
          viewType: "ImageView",
          displayLayout: false,
          sidePanel: false,
          sidePanelVisible: false,
          annotationLayer: false,
          overlay: true,
        }],
      });
    </script>
  </body>
</html>
