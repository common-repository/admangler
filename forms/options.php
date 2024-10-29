<h3><?php _e('Options', 'admangler'); ?></h3>
<?php
    $action = $_SERVER['REQUEST_URI'];

    if (isset($_POST['cache_buster']))
    {
        if ( get_option("AdMangler_cache_buster") )
            update_option('AdMangler_cache_buster', $_POST['cache_buster']);
        else
            add_option('AdMangler_cache_buster', $_POST['cache_buster']);
    }
    $buster = filter_var(get_option("AdMangler_cache_buster"), FILTER_VALIDATE_BOOLEAN);
?>
<form action="<?php echo $action; ?>" method="POST">
    <?php _e("Cache Buster will cause AdMangler to serve ads using javascript. This will keep ads fresh if you are using a caching plugin such as WP Super Cache or similar.", "admangler");?> <br />
    <?php _e("Cache Buster:", "admangler") ?>
    &nbsp;&nbsp;&nbsp;&nbsp; <?php _e("On", "admangler"); ?>: <input type="radio" name="cache_buster" value="true" <?php echo ( $buster ) ? "checked=checked" : "" ?> />
    &nbsp;&nbsp;&nbsp;&nbsp; <?php _e("Off", "admangler"); ?>: <input type="radio" name="cache_buster" value="false" <?php echo ( $buster ) ? "" : "checked=checked" ?> />
    <br />
    <br />  
    <button><?php _e("Save", "admangler"); ?></button>
</form>
