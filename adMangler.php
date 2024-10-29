<?php

    /*
      Plugin Name: AdMangler
      Plugin URI: http://www.webternals.com/projects/admangler/
      Description: AdMangler Display, sell, and manage ad space on your Wordpress powered site.
      Version: 1.1.0
      Author: Webternals, LLC - Allen Sanford
      Author URI: http://www.webternals.com/
     */
    /**
     *
     * DO NOT FORGET TO SET THE VERSIONS in adMangler.class.php if they change
     * Both DB and CLASS
     *
     * */
    $codeVersion = '1.1.0';
    $dbVersion   = '1.0.0';

    if ( !session_id() )
        session_start();

    // Check for class include and activate the class
    if ( !class_exists( 'AdMangler' ) )
    {
        require_once( dirname( __FILE__ ) . '/adMangler.class.php' );
        $adMangler = new AdMangler( $codeVersion, $dbVersion );
        require_once( dirname( __FILE__ ) . '/adManglerWidget.class.php' );
    } // End if (!class_exists('AdMangler')
    // Activation and Deactivation for some reason will not work in the constructor
    register_activation_hook( __FILE__, array($adMangler, "activate") );
    register_deactivation_hook( __FILE__, array($adMangler, "deactivate") );
