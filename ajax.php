<?php    
    require_once '../../../wp-load.php';
    require_once 'adMangler.php';
    global $adMangler;
    
    $arguments = array();
    $arguments["height"] = intval($_GET['height']);
    $arguments["width"] = intval($_GET['width']);
    $arguments["position"] = intval($_GET['position']);
    $arguments["pageID"] = intval($_GET['pageID']);
        
    $banner = $adMangler->get_ad($arguments);
?>
document.write('<?php echo $banner; ?>');