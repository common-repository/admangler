<?php

    include_once dirname( __FILE__ ) . "/./classes/webapi.php";

    class AdMangler
    {

        var $codeVersion    = null;
        var $dbVersion      = null;
        var $adsTable       = null;
        var $positionsTable = null;

        function __construct( $codeVersion = null, $dbVersion = null )
        {
            add_action( "plugins_loaded", array($this, "load_locales") );

            if ( !is_null( $dbVersion ) && !is_null( $codeVersion ) )
            {
                if ( is_null( $this->dbVersion ) )
                    $this->dbVersion   = $dbVersion;
                if ( is_null( $this->codeVersion ) )
                    $this->codeVersion = $codeVersion;
            }

            global $wpdb;
            $this->adsTable       = $wpdb->prefix . "AdMangler_ads";
            $this->positionsTable = $wpdb->prefix . "AdMangler_positions";

            // Generate the Admin Menu
            if ( is_admin() )
            {
                add_action( 'admin_menu', array($this, 'admin_menu') );
                add_action( 'admin_init', array($this, "admin_init") );
            }

            add_shortcode( 'AdMangler', array($this, 'short_code_helper') );
            add_shortcode( 'admangler', array($this, 'short_code_helper') );

            add_action( 'widgets_init', array($this, 'init'), 1 );
            add_action( 'wp_head', array($this, 'add_log_click') );
        }

// End function AdMangler

        function add_log_click()
        {
            echo "<script type=\"text/javascript\">
                        function AdManglerLogClick(id)
                        {
                            bug = new Image();
                            bug.src = '/" . PLUGINDIR . "/admangler/click.php?id=' + id;
                        }
                  </script>";
        }

        // Start function admin_init
        function admin_init()
        {
            $this->activate();
            wp_enqueue_script( 'admanglertooltip', '/' . PLUGINDIR . '/admangler/js/tooltip.js' );
            $this->tinymce_register_button();
        }

// End admin_init()

        function admin_menu()
        {
            add_menu_page( __( 'AdMangler Settings', 'admangler' ), __( 'AdMangler', 'admangler' ), 9, __FILE__, array($this, 'create_admin_page'), '/' . PLUGINDIR . '/admangler/images/logo.gif' );
            add_submenu_page( __FILE__, __( 'AdMangler Settings', 'admangler' ), __( 'Banners', 'admangler' ), 9, 'banners', array($this, 'create_admin_page') );
            add_submenu_page( __FILE__, __( 'AdMangler Settings', 'admangler' ), __( 'Options', 'admangler' ), 9, 'options', array($this, 'create_admin_page') );
        }

// End function admin_menu

        function activate()
        {

            ob_start();
            global $wpdb;
            // Installed plugin database table version
            $db_version = get_option( 'AdMangler_db_version' );
            if ( false === $db_version )
            {
                $db_version = '0.0.0';
            }

            // If the database has changed, update the structure while preserving data
            if ( version_compare( $db_version, $this->dbVersion, '!=' ) )
            {
                $this->upsert_tables();
                if ( get_option( 'AdMangler_db_version' ) )
                {
                    update_option( 'AdMangler_db_version', $this->dbVersion );
                }
                else
                {
                    add_option( 'AdMangler_db_version', $this->dbVersion );
                }
            }

            $code_version = get_option( 'AdMangler_code_version' );
            if ( false === $code_version )
                $code_version = '0.0.0';
            if ( version_compare( $code_version, $this->codeVersion, '!=' ) )
            {
                if ( get_option( 'AdMangler_code_version' ) )
                {
                    update_option( 'AdMangler_code_version', $this->codeVersion );
                    $action = 'update';
                }
                else
                {
                    add_option( 'AdMangler_code_version', $this->codeVersion );
                    $action = 'install';
                }
                $this->send_statistics( $action );
            }
            else
            {
                $this->send_statistics( 'activated' );
            }
            //file_put_contents(dirname(__FILE__)."/log.txt", $this->codeVersion, FILE_APPEND);
            return true;
        }

// End function activate

        function count_click( $banner )
        {
            if ( !is_admin() )
            {
                global $wpdb;
                $sql = "UPDATE {$this->adsTable} SET clicks=clicks+1 WHERE id={$banner->id}";
                $wpdb->query( $sql );
            }
        }

        function count_impression( $banner )
        {
            if ( !is_admin() )
            {
                global $wpdb;
                $sql = "UPDATE {$this->adsTable} SET impressions=impressions+1 WHERE id={$banner->id}";
                $wpdb->query( $sql );
            }
        }

        function create_admin_page()
        {
            echo "<div class=\"wrap\"><h2>" . __( 'AdMangler Admin', 'admangler' ) . "</h2>";
            switch ( $_GET['page'] )
            {
                case 'options':
                    include_once "forms/options.php";
                    break;
                case 'banners':
                    include_once "forms/banners.php";
                    break;
                default:
                    include_once "forms/dashboard.php";
                    break;
            }
            echo "</div>";
        }

// End function create_admin_page

        function deactivate()
        {
            $this->send_statistics( 'deactivated' );
        }

        function format_ad( $banner )
        {
            switch ( $banner->type )
            {
                case 'html':
                    $code .= "<div onClick=\"AdManglerLogClick(" . $banner->id . ")\" class=\"adMangler{$banner->width}x{$banner->height} adMangler-html-{$banner->width}x{$banner->height} adMangler-{$banner->id}\">" . stripslashes( $banner->code ) . "</div>";
                    $this->count_impression( $banner );
                    break;
                case 'image':
                    $code .= "<div onClick=\"AdManglerLogClick(" . $banner->id . ")\" class=\"adMangler{$banner->width}x{$banner->height} adMangler-image-{$banner->width}x{$banner->height} adMangler-{$banner->id}\" style=\"width:{$banner->width}px;height:{$banner->height}px;\"><a href=\"{$banner->href}\"><img src=\"{$banner->src}\" /></a></div>";
                    $this->count_impression( $banner );
                    break;
                case 'ajax':
                    srand( time() );
                    $code .= "<script type=\"text/javascript\" src=\"/" . PLUGINDIR . "/admangler/ajax.php?buster=" . rand() . "&width={$banner->width}&height={$banner->height}";
                    $code .= "&position={$banner->position}";
                    $code .= "&pageID={$banner->pageID}";
                    $code .= "\"></script>";
                    break;
                default:
                    $code = "";
                    break;
            }
            return $code;
        }

//End function format_ad
        // Start get_ad
        function get_ad( $options = array('width' => null, 'height' => null, 'pageID' => null, 'position' => null, 'ajaxAd' => false, 'return' => true) )
        {
            if ( is_object( $options ) )
            {
                $width    = $options->width;
                $height   = $options->height;
                $pageID   = $options->pageID;
                $position = $options->position;
                $pageID   = (empty( $pageID )) ? get_the_ID() : $pageID;
                $pageID   = (is_home()) ? -1 : $pageID;
                $position = (empty( $position )) ? 0 : $position;
                $ajaxAd   = $options->ajaxAd;
                $return   = (isset( $options->return )) ? $options->return : true;
            }
            if ( is_array( $options ) )
            {
                $width    = $options['width'];
                $height   = $options['height'];
                $pageID   = $options['pageID'];
                $position = $options['position'];
                $pageID   = (empty( $pageID )) ? get_the_ID() : $pageID;
                $pageID   = (is_home()) ? -1 : $pageID;
                $position = (empty( $position )) ? 0 : $position;
                $ajaxAd   = $options['ajaxAd'];
                $return   = (isset( $options['return'] )) ? $options['return'] : true;
            }

            if ( $ajaxAd )
            {
                $banner           = new StdClass;
                $banner->type     = "ajax";
                $banner->width    = $width;
                $banner->height   = $height;
                $banner->pageID   = $pageID;
                $banner->position = $position;
                $str              = $this->format_ad( $banner );
                if ( $return )
                    return $str;
                else
                    echo $str;
                exit( 1 );
            }
            else
            {
                global $wpdb;


                foreach ( range( 0, 3 ) as $num )
                {
                    //if (!is_array($this->banners[$width."x".$height][$num]))
                    $this->banners[$width . "x" . $height][$num] = array();
                }

                $sql1     = "SELECT * FROM {$this->adsTable} as ads
                        JOIN {$this->positionsTable} as pos ON ads.id = pos.ad_id
                        WHERE
                        (ads.width=$width AND ads.height=$height AND ads.active AND ads.approved AND NOT ads.base)
                        AND
                        (pos.page_ID=$pageID AND pos.page_exclusive AND pos.custom_slot AND pos.slot=$position AND pos.slot_exclusive)
                        ORDER BY RAND()";
                $sql2     = "SELECT * FROM {$this->adsTable} as ads
                        JOIN {$this->positionsTable} as pos ON ads.id = pos.ad_id
                        WHERE
                        (ads.width=$width AND ads.height=$height AND ads.active AND ads.approved AND ads.base)
                        AND
                        (pos.page_ID=$pageID AND pos.page_exclusive AND pos.custom_slot AND pos.slot=$position AND pos.slot_exclusive)
                        ORDER BY RAND()";
                $results1 = $wpdb->get_results( $sql1 );
                $results2 = $wpdb->get_results( $sql2 );

                if ( $results1 )
                    $this->banners[$width . "x" . $height][0] = $results1;
                else if ( $results2 )
                    $this->banners[$width . "x" . $height][0] = $results2;

                if ( !empty( $this->banners[$width . "x" . $height][0] ) )
                {
                    $banner = array_shift( $this->banners[$width . "x" . $height][0] );
                    array_push( $this->banners[$width . "x" . $height][0], $banner );
                    $str    = $this->format_ad( $banner );
                    if ( $return )
                        return $str;
                    else
                        echo $str;
                    exit( 1 );
                }

                $sql1     = "SELECT * FROM {$this->adsTable} as ads
                        JOIN {$this->positionsTable} as pos ON ads.id = pos.ad_id
                        WHERE
                        (ads.width=$width AND ads.height=$height AND ads.active AND ads.approved AND NOT ads.base)
                        AND
                        (pos.page_ID=$pageID AND ((pos.page_exclusive AND pos.custom_slot AND pos.slot=$position) OR (pos.page_exclusive AND NOT pos.custom_slot)))
                        ORDER BY RAND()";
                $sql2     = "SELECT * FROM {$this->adsTable} as ads
                        JOIN {$this->positionsTable} as pos ON ads.id = pos.ad_id
                        WHERE
                        (ads.width=$width AND ads.height=$height AND ads.active AND ads.approved AND ads.base)
                        AND
                        (pos.page_ID=$pageID AND ((pos.page_exclusive AND pos.custom_slot AND pos.slot=$position) OR (pos.page_exclusive AND NOT pos.custom_slot)))
                        ORDER BY RAND()";
                $results1 = $wpdb->get_results( $sql1 );
                $results2 = $wpdb->get_results( $sql2 );

                if ( $results1 )
                    $this->banners[$width . "x" . $height][1] = $results1;
                else if ( $results2 )
                    $this->banners[$width . "x" . $height][1] = $results2;

                if ( !empty( $this->banners[$width . "x" . $height][1] ) )
                {
                    $banner = array_shift( $this->banners[$width . "x" . $height][1] );
                    array_push( $this->banners[$width . "x" . $height][1], $banner );
                    $str    = $this->format_ad( $banner );
                    if ( $return )
                        return $str;
                    else
                        echo $str;
                    exit( 1 );
                }

                $sql1     = "SELECT * FROM {$this->adsTable} as ads
                        JOIN {$this->positionsTable} as pos ON ads.id = pos.ad_id
                        WHERE
                        (ads.width=$width AND ads.height=$height AND ads.active AND ads.approved AND NOT ads.base)
                        AND
                        ((pos.page_ID=$pageID OR pos.page_ID=0) AND pos.custom_slot AND pos.slot=$position AND pos.slot_exclusive)
                        ORDER BY RAND()";
                $sql2     = "SELECT * FROM {$this->adsTable} as ads
                        JOIN {$this->positionsTable} as pos ON ads.id = pos.ad_id
                        WHERE
                        (ads.width=$width AND ads.height=$height AND ads.active AND ads.approved AND ads.base)
                        AND
                        ((pos.page_ID=$pageID OR pos.page_ID=0) AND pos.custom_slot AND pos.slot=$position AND pos.slot_exclusive)
                        ORDER BY RAND();";
                $results1 = $wpdb->get_results( $sql1 );
                $results2 = $wpdb->get_results( $sql2 );

                if ( $results1 )
                    $this->banners[$width . "x" . $height][2] = $results1;
                else if ( $results2 )
                    $this->banners[$width . "x" . $height][2] = $results2;

                if ( !empty( $this->banners[$width . "x" . $height][2] ) )
                {
                    $banner = array_shift( $this->banners[$width . "x" . $height][2] );
                    array_push( $this->banners[$width . "x" . $height][2], $banner );
                    $str    = $this->format_ad( $banner );
                    if ( $return )
                        return $str;
                    else
                        echo $str;
                    exit( 1 );
                }

                $sql1     = "SELECT * FROM {$this->adsTable} as ads
                        LEFT JOIN {$this->positionsTable} as pos ON pos.ad_id = ads.id
                        WHERE
                        (ads.width=$width AND ads.height=$height AND ads.active AND ads.approved AND NOT ads.base)
                        AND
                        (((pos.page_ID=$pageID OR pos.page_ID=0) AND NOT pos.custom_slot) OR pos.page_ID IS NULL)
                        ORDER BY RAND()";
                $sql2     = "SELECT * FROM {$this->adsTable} as ads
                        LEFT JOIN {$this->positionsTable} as pos ON pos.ad_id = ads.id
                        WHERE
                        (ads.width=$width AND ads.height=$height AND ads.active AND ads.approved AND ads.base)
                        AND
                        (((pos.page_ID=$pageID OR pos.page_ID=0) AND NOT pos.custom_slot) OR pos.page_ID IS NULL)
                        ORDER BY RAND()";
                $results1 = $wpdb->get_results( $sql1 );
                $results2 = $wpdb->get_results( $sql2 );

                if ( $results1 )
                    $this->banners[$width . "x" . $height][3] = $results1;
                else if ( $results2 )
                    $this->banners[$width . "x" . $height][3] = $results2;

                if ( !empty( $this->banners[$width . "x" . $height][3] ) )
                {
                    $banner = array_shift( $this->banners[$width . "x" . $height][3] );
                    array_push( $this->banners[$width . "x" . $height][3], $banner );
                    $str    = $this->format_ad( $banner );
                    if ( $return )
                        return $str;
                    else
                        echo $str;
                    exit( 1 );
                }
            }
            $str = "";
            if ( $return )
                return $str;
            else
                echo $str;
        }

// End get_ad
        // Start get_ad_by_id
        function get_ad_by_id( $id, $return )
        {
            global $wpdb;
            $sql = "SELECT id,type,code,href,src,width,height FROM $this->adsTable WHERE id=" . intval( $id );
            $row = $wpdb->get_row( $sql );
            $str = $this->format_ad( $row );

            if ( $return )
                return $str;
            else
                echo $str;
        }

        function init()
        {
            wp_enqueue_script( "jquery" );
            register_widget( 'AdManglerWidget' ); // This adds the Widget to the backend
            //wp_enqueue_script('jquery.validate', '/' . PLUGINDIR . '/admangler/js/jquery.validate.min.js');
        }

        // Start load_locales
        function load_locales()
        {
            $plugin_dir = basename( dirname( __FILE__ ) ) . "/locales/";
            load_plugin_textdomain( 'admangler', false, $plugin_dir );
        }

        // Start send_statistics($action)
        function send_statistics( $action )
        {
            try
            {
//            $api = new WebAPI('publicapi', 'publicapi');
//
//            $phone = new SimpleXMLElement('<request></request>');
//            $phone->addChild('action', 'stats');
//            $phone->addChild('application', 'AdMangler');
//            $phone->addChild('version', $this->codeVersion);
//            $phone->addChild('database', $this->dbVersion);
//            $phone->addChild('status', $action);
//            $phone->addChild('domain', urlencode($_SERVER['SERVER_NAME']));
//            $api->add_request($phone);
//            $api->request();
                //file_put_contents(dirname(__FILE__)."/log.txt", var_export($api, true). "\n", FILE_APPEND);
            }
            catch ( Exception $e )
            { /* file_put_contents(dirname(__FILE__)."/log.txt", var_export($e, true). "\n", FILE_APPEND); *//* Fail quitely */
            }
        }

// End send_statistics($action)
        // Start short_code_helper($atts, $content=null, $code="")
        function short_code_helper( $atts, $content = null, $code = "" )
        {
            // $atts    ::= array of attributes
            // $content ::= text within enclosing form of shortcode element
            // $code    ::= the shortcode found, when == callback name
            // examples: [my-shortcode]
            //           [my-shortcode/]
            //           [my-shortcode foo='bar']
            //           [my-shortcode foo='bar'/]
            //           [my-shortcode]content[/my-shortcode]
            //           [my-shortcode foo='bar']content[/my-shortcode]
            $atts['ajaxAd'] = filter_var( get_option( "AdMangler_cache_buster" ), FILTER_VALIDATE_BOOLEAN );
            if ( !isset( $atts['type'] ) )
                return $this->get_ad( $atts );
            else
                return "";
        }

// End short_code_helper($atts, $content=null, $code="")
        // Start tinymce_filter_button(...)
        // Add a callback to add our button to the TinyMCE toolbar
        //This callback adds our button to the toolbar
        function tinymce_filter_button( $buttons )
        {
            //Add the button ID to the $button array
            $buttons[] = "wpse72394_button";
            return $buttons;
        }

// End tinymce_filter_button(...)
        // Start tinymce_filter_plugin(...)
        //Add a callback to regiser our tinymce plugin
        //This callback registers our plug-in
        function tinymce_filter_plugin( $plugin_array )
        {
            global $PLUGINDIR;
            $plugin_array['wpse72394_button'] = '/' . PLUGINDIR . '/admangler/js/shortcode.js';
            return $plugin_array;
        }

// Start tinymce_filter_plugin(...)
        // Start tinymce_register_button()
        // init process for registering our button
        function tinymce_register_button()
        {
            //Abort early if the user will never see TinyMCE
            if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) && get_user_option( 'rich_editing' ) == 'true' )
                return;

            add_filter( "mce_external_plugins", array($this, "tinymce_filter_plugin") );
            add_filter( 'mce_buttons', array($this, "tinymce_filter_button") );
        }

// End tinymce_register_button()
        // Start uninstall
        function uninstall()
        {

            delete_option( 'AdMangler_code_version' );
            delete_option( 'AdMangler_db_version' );
            delete_option( 'AdMangler_cache_buster' );

            $this->send_statistics( "uninstall" );
        }

// End uninstall
        // Start upsert_tables
        function upsert_tables()
        {
            global $wpdb;
            // Plugin database table version
            $sql[] = "CREATE TABLE " . $wpdb->prefix . "AdMangler_ads (
                            id INT(11) NOT NULL AUTO_INCREMENT,
                            advertiser VARCHAR(256) COLLATE utf8_bin NOT NULL DEFAULT 'admin',
                            width INT(11) NOT NULL,
                            height INT(11) NOT NULL,
                            active BOOL NOT NULL DEFAULT 0,
                            approved BOOL NOT NULL DEFAULT 0,
                            base BOOL NOT NULL DEFAULT 0,
                            type VARCHAR(5) COLLATE utf8_bin NOT NULL DEFAULT 'image',
                            code TEXT COLLATE utf8_bin,
                            href VARCHAR(256) character set utf8 collate utf8_bin NOT NULL default 'http://www.webternals.com/projects/admangler/',
                            src VARCHAR(256) NOT NULL default 'http://www.webternals.com/images/no-image.png',
                            impressions INT(11) NOT NULL,
                            clicks INT(11) NOT NULL,
                            UNIQUE KEY id (id)
                        );";

            $sql[] = "CREATE TABLE " . $wpdb->prefix . "AdMangler_positions (
                            ad_ID INT(11) NOT NULL,
                            page_ID INT(11) NOT NULL,
                            page_exclusive INT(1) NOT NULL DEFAULT 0,
                            custom_slot INT(1) NOT NULL DEFAULT 0,
                            slot INT(11) NOT NULL DEFAULT 0,
                            slot_exclusive INT(1) NOT NULL DEFAULT 0
                        );";

            require_once ABSPATH . "wp-admin/includes/upgrade.php";
            foreach ( $sql as $temp )
                dbDelta( $temp );
        }

// End upsert_tables
    }

    // End class AdMangler