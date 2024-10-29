<?php require_once '../../../wp-load.php'; ?>

<style>
    .row, .label, .input, .submit, button { margin:4px 10px; }
    .label { width:150px; float:left;text-align:right; }
    .row-submit { clear:both; }
    .center { text-align:center; margin:20px 0px; }
</style>
<script type="text/javascript">
    document.write("\<script src='//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js' type='text/javascript'>\<\/script>");
</script>
<script>
    var editor = parent.AdManglerShortcode;

    jQuery(document).ready(function ($) {
        $('#insert').click(function (event) {
            event.preventDefault();
            var code = new function () {
                this.width = null;
                this.height = null;
                this.position = null;
            }

            code.width = $('#width').val();
            code.height = $('#height').val();
            code.position = $('#position').val();
            editor.insert_data(code);
        });
    });
</script>
<h3><?php _e( 'AdMangler Shortcode Helper', 'admangler' ); ?></h3>
<div class="center"><?php _e( 'Enter the size and position for this ad spot!', 'admangler' ); ?></div>
<form action="" method="POST">
    <div class="row">
        <div class="label"><?php _e( 'Width', 'admangler' ); ?></div>
        <div class="input"><input type="text" id="width" size="5" /></div>
    </div>
    <div class="row">
        <div class="label"><?php _e( 'Height', 'admangler' ); ?></div>
        <div class="input"><input type="text" id="height" size="5" /></div>
    </div>
    <div class="row">
        <div class="label"><?php _e( 'Position', 'admangler' ); ?></div>
        <div class="input"><input type="text" id="position" size="5" /></div>
    </div>
    <div class="row-submit">
        <div class="label">&nbsp;</div>
        <div class="submit"><button id="insert" class="mceClose"><?php _e( 'Insert', 'admangler' ); ?></button></div>
    </div>
</form>