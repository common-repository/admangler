<?php    
    require_once '../../../wp-load.php';
    require_once 'adMangler.php';
    global $adMangler;

    $banner = new stdClass;
    $banner->id = intval($_GET['id']);
    $adMangler->count_click($banner);
?>