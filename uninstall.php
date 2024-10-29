<?php
//if uninstall not called from WordPress exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
    exit ();
    
include_once "adMangler.php";
$adMangler->uninstall();
?>