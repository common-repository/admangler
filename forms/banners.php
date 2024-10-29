<?php
    wp_enqueue_media();

    // This is for the bug fix to prevent duplicate ads being created
    $action = $_SERVER['REQUEST_URI'];
    global $wpdb,$adMangler;
    $list = true;
    $message = "<div id=\"message\" class=\"updated fade below-h2\" style=\"background-color: rgb(255, 251, 204);\">
                        <p>
                            ".__("Banner Updated!", "admangler")."
                        </p>
                    </div>";

    if (isset($_GET['action']))
    {

        if( 0 == strcmp('new', $_GET['action']))
        {
            $list = false;
            if (isset($_POST['save']))
            {

                $table = $adMangler->adsTable;
                $data = array('width' => $_POST['width'],'height' => $_POST['height'],'active' => $_POST['active'],'approved' => $_POST['approved'],'type' => $_POST['type'],'code' => $_POST['code'],'href' => $_POST['href'],'advertiser' => $_POST['advertiser'],'base' => $_POST['base'],'src' => $_POST['src']);
                $format = array('%d','%d','%d','%d','%s','%s','%s','%s','%d','%s');
                $wpdb->insert($table, $data, $format);
                $insert_id = $wpdb->insert_id;
                echo $message;
                $sql = "SELECT * FROM $adMangler->adsTable WHERE id=$insert_id";
                $banner = $wpdb->get_row($sql);
                echo $adMangler->format_ad($banner);

                // This is a bug fix to prevent duplicate ads being created
                $action = str_replace("new", "edit", $action);
                $action .= "&id=$insert_id";
                $assoc = array();
                $i=0;
                if (is_array($_POST['pageID']))
                {
                    foreach ($_POST['pageID'] as $id)
                    {
                        $assoc[] = array('ad_ID'=>$insert_id, 'page_id'=>$id, 'page_exclusive'=>$_POST['pagex'][$i], 'custom_slot'=>$_POST['cslot'][$i], 'slot'=>$_POST['slot'][$i], 'slot_exclusive'=>$_POST['slotx'][0] );
                        $i++;
                    }
                }

                $table = $adMangler->positionsTable;
                $format = array('%d','%d','%d','%d','%d','%d');
                foreach ($assoc as $data)
                    $wpdb->insert($table, $data, $format);
                $sql = "SELECT * FROM $adMangler->positionsTable WHERE ad_id=".intval($insert_id)." ORDER BY page_id ASC";
                $banner->positions = $wpdb->get_results($sql);
            }
        }
        else if (0 == strcmp('delete', $_GET['action']) && isset($_GET['id']))
        {
            $sql = "DELETE FROM $adMangler->adsTable WHERE id=".intval($_GET['id']);
            $wpdb->query($sql);
            $list = true;
        }
        else if (0 == strcmp('edit', $_GET['action']) && isset($_GET['id']))
        {
            $list = false;
            if (isset($_POST['save']))
            {
                $table = $adMangler->adsTable;
                $data = array('width' => $_POST['width'],'height' => $_POST['height'],'active' => $_POST['active'],'approved' => $_POST['approved'],'type' => $_POST['type'],'code' => $_POST['code'],'href' => $_POST['href'],'advertiser' => $_POST['advertiser'],'base' => $_POST['base'],'src' => $_POST['src']);
                $format = array('%d','%d','%d','%d','%s','%s','%s','%s','%d','%s');
                $where = array('id' => $_GET['id']);
                $where_format = array('%d');
                $wpdb->update($table, $data, $where, $format, $where_format);
                $wpdb->query("DELETE FROM $adMangler->positionsTable WHERE ad_id={$_GET['id']}");
                $assoc = array();
                $i=0;
                if (is_array($_POST['pageID']))
                {
                    foreach ($_POST['pageID'] as $id)
                    {
                        $assoc[] = array('ad_ID'=>$_GET['id'], 'page_id'=>$id, 'page_exclusive'=>$_POST['pagex'][$i], 'custom_slot'=>$_POST['cslot'][$i], 'slot'=>$_POST['slot'][$i], 'slot_exclusive'=>$_POST['slotx'][0] );
                        $i++;
                    }
                }

                $table = $adMangler->positionsTable;
                $format = array('%d','%d','%d','%d','%d','%d');
                foreach ($assoc as $data)
                    $wpdb->insert($table, $data, $format);

                echo $message;
            }
        }

        if (isset($_GET['id']))
        {
            $sql = "SELECT * FROM $adMangler->adsTable WHERE id=".intval($_GET['id']);
            $banner = $wpdb->get_row($sql);
            $sql = "SELECT * FROM $adMangler->positionsTable WHERE ad_id=".intval($_GET['id'])." ORDER BY page_id ASC";
            $banner->positions = $wpdb->get_results($sql);
            echo $adMangler->format_ad($banner);
        }
    }

if (!$list):
    ?>
<style type="text/css">
    #bannerform optgroup option { padding-left:10px; }
</style>
    <div style="text-align:center;">
        <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
        <input type="hidden" name="cmd" value="_s-xclick">
        <input type="hidden" name="hosted_button_id" value="8E8PU9FKLLGAJ">
        <input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
        <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
        </form>
    </div>  
    <h3><?php _e("Edit Banner", "admangler"); ?>Edit Banner</h3>
    
    <form id="bannerform" action="<?php echo $action; ?>" method="POST">
        <table style="text-align:left;width:100%;">
            <tr><td><label><?php _e("Width", "admangler"); ?></label></td><td><input type="text" name="width" value="<?php echo $banner->width; ?>" /></td></tr>
            <tr><td><label><?php _e("Height", "admangler"); ?></label></td><td><input type="text" name="height" value="<?php echo $banner->height; ?>" /></td></tr>
            <tr>
                <td><label><?php _e("Active", "admangler"); ?></label></td>
                <td>
                    <select name="active">
                        <option value="1" <?php echo ($banner->active) ? "SELECTED" : ""; ?>>Yes &nbsp;</option>
                        <option value="0" <?php echo ($banner->active) ? "" : "SELECTED"; ?>>No &nbsp;</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label><?php _e("Approved", "admangler"); ?></label></td>
                <td>
                    <input name="approved" type="hidden" value="1" />
                    <select name="approved">
                        <option value="1" <?php echo ($banner->approved) ? "SELECTED" : ""; ?>><?php _e("Yes", "admangler"); ?> &nbsp;</option>
                        <option value="0" <?php echo ($banner->approved) ? "" : "SELECTED"; ?>><?php _e("No", "admangler"); ?> &nbsp;</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label>Type</label></td>
                <td>
                    <select name="type" id="type">
                        <option value="html" <?php echo (0 == strcmp($banner->type, "html")) ? "SELECTED" : ""; ?>><?php _e("Generic HTML", "admangler"); ?> &nbsp;</option>
                        <option value="image" <?php echo (0 == strcmp($banner->type, "image")) ? "SELECTED" : ""; ?>><?php _e("Image", "admangler"); ?> &nbsp;</option>
                    </select>
                </td>
            </tr>
            <tr class="typehtml"><td><label><?php _e("Banner Code", "admangler"); ?></label></td><td><textarea name="code" style="width:350px;height:100px;"><?php echo stripslashes($banner->code); ?></textarea> </td></tr>
            <tr class="typeimage">
                <td><label><?php _e("Image Location", "admangler"); ?></label></td>
                <td>
                    <input id="upload_image_url" style="width:300px;" type="text" name="src" placeholder="http://" value="<?php echo $banner->src; ?>" />
                    <input id="upload_image_button" class="button" type="button" value="<?php _e("Upload Image", "admangler"); ?>" />
                    <br /><em><?php _e("Enter a URL or upload an image", "admangler"); ?>
                    <br /><?php _e("example", "admangler"); ?>: http://www.webternals.com/banner.jpg</em>
                </td>
            </tr>
            <tr class="typeimage">
                <td><label><?php _e("Image Link", "admangler"); ?></label></td>
                <td>
                    <input style="width:300px;" type="text" name="href" placeholder="http://" value="<?php echo $banner->href; ?>" />
                    <br /><em><?php _e("example", "admangler"); ?>: http://www.webternals.com/</em>
                </td>
            </tr>
            <!--<tr><td><label>URL</label></td><td><input type="text" name="url" value="<?php echo $banner->url ?>" /> </td></tr>-->
            <tr>
                <td><!-- <label>Advertiser</label> --></td>
                <td>
                    <input name="advertiser" type="hidden" value="none" />
                    <!-- <select name="advertiser">
                        <option value="none" <?php echo (0 == strcmp($banner->advertiser, "none")) ? "SELECTED" : ""; ?>>None Specified &nbsp;</option>
                    </select> -->
                </td>
            </tr>
            <tr>
                <td valign="top"><label><?php _e("Page Association", "admangler"); ?></label></td>
                <td>
                    <?php ob_start(); ?>
                    <div class="passoc">
                    <select name="pageID[]">
                        <option value="0" ><?php _e("All Pages", "admangler"); ?> &nbsp;</option>
                        <optgroup label="Pages">
                            <option value="-1" ><?php _e("Home Page", "admangler"); ?> &nbsp;</option>
                            <?php $my_query = new WP_Query('post_type=page&post_status=any&posts_per_page=-1'); ?>
                            <?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
                                <option value="<?php the_ID(); ?>" ><?php the_title(); ?></option>
                            <?php endwhile; ?>
                        </optgroup>
                        <optgroup label="Posts">
                        <?php $my_query = new WP_Query('post_type=post&post_status=any&posts_per_page=-1'); ?>
                        <?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
                            <option value="<?php the_ID(); ?>"><?php the_title(); ?></option>
                        <?php endwhile; ?>
                        </optgroup>
                    </select>
                    <select name="pagex[]">
                        <option value="0" ><?php _e("No", "admangler"); ?></option>
                        <option value="1" ><?php _e("Yes", "admangler"); ?></option>
                    </select>
                    <?php _e("Exclusive*", "admangler"); ?><br />
                    <select name="cslot[]">
                        <option value="0" ><?php _e("No", "admangler"); ?></option>
                        <option value="1" ><?php _e("Yes", "admangler"); ?></option>
                    </select>
                    <?php _e("Custom Slot", "admangler"); ?>
                    &nbsp;&nbsp;
                    <?php _e("Slot Number", "admangler"); ?>:
                    <input type="text" name="slot[]" size="3" value="" />
                    <select name="slotx[]">
                        <option value="0" ><?php _e("No", "admangler"); ?></option>
                        <option value="1" ><?php _e("Yes", "admangler"); ?></option>
                    </select>
                    Exclusive*
                    <br />
                    <a class="removePage" href=""><?php _e("Remove Page Association", "admangler"); ?></a>
                    <br /><br />
                    </div>
                    <?php $passoc = ob_get_contents(); ob_end_clean();; ?>
                    <div class="passocs">
                        <?php
                            if (is_array($banner->positions))
                            {
                                foreach($banner->positions as $position)
                                {
                                    ?>
                                    <div class="passoc">
                                    <select name="pageID[]">
                                        <option value="0" <?php echo ($position->page_ID == 0) ? "SELECTED" : ""; ?>><?php _e("All Pages", "admangler"); ?> &nbsp;</option>
                                        <optgroup label="Pages">
                                            <option value="-1" <?php echo ($position->page_ID == -1) ? "SELECTED" : ""; ?>><?php _e("Home Page", "admangler"); ?> &nbsp;</option>
                                            <?php $my_query = new WP_Query('post_type=page&post_status=any&posts_per_page=-1'); ?>
                                            <?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
                                                <option value="<?php the_ID(); ?>" <?php echo ($position->page_ID == get_the_ID()) ? "SELECTED" : ""; ?>><?php echo the_title(); ?></option>
                                            <?php endwhile; ?>
                                        </optgroup>
                                        <optgroup label="Posts">
                                        <?php $my_query = new WP_Query('post_type=post&post_status=any&posts_per_page=-1'); ?>
                                        <?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
                                            <option value="<?php the_ID(); ?>" <?php echo ($position->page_ID == get_the_ID()) ? "SELECTED" : ""; ?>><?php echo the_title(); ?></option>
                                        <?php endwhile; ?>
                                        </optgroup>
                                    </select>
                                    <select name="pagex[]">
                                        <option value="0" <?php echo ($position->page_exclusive) ? "" : "SELECTED"; ?>><?php _e("No", "admangler"); ?></option>
                                        <option value="1" <?php echo ($position->page_exclusive) ? "SELECTED" : ""; ?>><?php _e("Yes", "admangler"); ?></option>
                                    </select>
                                    <?php _e("Exclusive*", "admangler"); ?><br />
                                    <select name="cslot[]">
                                        <option value="0" <?php echo ($position->custom_slot) ? "" : "SELECTED"; ?>><?php _e("No", "admangler"); ?></option>
                                        <option value="1" <?php echo ($position->custom_slot) ? "SELECTED" : ""; ?>><?php _e("Yes", "admangler"); ?></option>
                                    </select>
                                    <?php _e("Custom Slot", "admangler"); ?>
                                    &nbsp;&nbsp;
                                    <?php _e("Slot Number", "admangler"); ?>:
                                    <input type="text" name="slot[]" size="3" value="<?php echo ($position->slot); ?>" />
                                    <select name="slotx[]">
                                        <option value="0" <?php echo ($position->slot_exclusive) ? "" : "SELECTED"; ?>><?php _e("No", "admangler"); ?></option>
                                        <option value="1" <?php echo ($position->slot_exclusive) ? "SELECTED" : ""; ?>><?php _e("Yes", "admangler"); ?></option>
                                    </select>
                                    <?php _e("Exclusive*", "admangler"); ?>
                                    <br />
                                    <a class="removePage" href=""><?php _e("Remove Page Association", "admangler"); ?></a>
                                    <br /><br />
                                    </div>
                                    <?php
                                }
                            }
                        ?>
                    </div>
                    <a id="addPage" href=""><?php _e("Add Page Association", "admangler"); ?></a>
                </td>
            </tr>
            <tr><td></td>
                <td>
                    * <?php _e("Marking Exclusive will make only Ads marked exclusive show on this page/position.", "admangler"); ?><br />
                    ** <?php _e("Position 0 is reserved for all position start your numbering at 1.", "admangler"); ?>
                </td></tr>
            <tr>
                <td><label><?php _e("Base Ad", "admangler"); ?></label></td>
                <td>
                    <input name="base" type="hidden" value="1" />
                    <select name="base">
                        <option value="1" <?php echo ($banner->base) ? "SELECTED" : ""; ?>><?php _e("Yes", "admangler"); ?> &nbsp;</option>
                        <option value="0" <?php echo ($banner->base) ? "" : "SELECTED"; ?>><?php _e("No", "admangler"); ?> &nbsp;</option>
                    </select> <?php _e("Is this a place holder Ad?", "admangler"); ?>
                </td>
            </tr>
            <tr>
                <td><input type="hidden" name="save" value="1" /></td>
                <td>
                    <input type="submit" name="submit" value="Save" class="button" /> &nbsp;&nbsp;
                    <?php if (isset($_GET['id'])): ?>
                        <a class="button" href="admin.php?page=banners&action=delete&id=<?php echo $result->id; ?>" onclick="return confirm('<?php _e("Are you sure you want to delete?", "admangler"); ?>')"><?php _e("Delete", "admangler"); ?></a>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
    </form>
    <div style="text-align:center;">
        <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
        <input type="hidden" name="cmd" value="_s-xclick">
        <input type="hidden" name="hosted_button_id" value="8E8PU9FKLLGAJ">
        <input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
        <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
        </form>
    </div>
    <script type="text/javascript">

    $j = jQuery;

jQuery(document).ready(function($){ 
    var custom_uploader; 
    $('#upload_image_button').click(function(e) {
        e.preventDefault();
 
        //If the uploader object has already been created, reopen the dialog
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }
 
        //Extend the wp.media object
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: '<?php _e("Choose Image", "admangler"); ?>',
            button: {
                text: '<?php _e("Choose Image", "admangler"); ?>'
            },
            multiple: false
        });
 
        //When a file is selected, grab the URL and set it as the text field's value
        custom_uploader.on('select', function() {
            attachment = custom_uploader.state().get('selection').first().toJSON();
            $('#upload_image_url').val(attachment.url);
        });
 
        //Open the uploader dialog
        custom_uploader.open();
 
    });
 
 
});



//~ jQuery(document).ready(function($){
  //~ var _custom_media = true,
      //~ _orig_send_attachment = wp.media.editor.send.attachment;
//~ 
  //~ $('#upload_image_button').click(function(e) {
      //~ e.preventDefault();
    //~ var send_attachment_bkp = wp.media.editor.send.attachment;
    //~ var button = $(this);
    //~ var id = button.attr('id').replace('_button', '_url');
    //~ _custom_media = true;
    //~ wp.media.editor.send.attachment = function(props, attachment){
      //~ if ( _custom_media ) {
        //~ $("#"+id).val(attachment.url);
      //~ } else {
        //~ return _orig_send_attachment.apply( this, [props, attachment] );
      //~ };
    //~ }
//~ 
    //~ wp.media.editor.open(button);
    //~ return false;
  //~ });
//~ 
  //~ $('.add_media').on('click', function(){
    //~ _custom_media = false;
  //~ });
//~ });









        
        $j("select#type option").each(function () {
            temp = $j(this).attr('value');
            $j('.type'+temp).hide();
        });
    
        temp = $j("select#type option:selected").attr('value');
        $j('.type'+temp).show();

        $j("select#type").change(function () {
            $j("select#type option").each(function () {
                temp = $j(this).attr('value');
                $j('.type'+temp).hide();
            });
            temp = $j("select#type option:selected").attr('value');
            $j('.type'+temp).show();
        });

        $j('#addPage').click(function ()
            {
                $j('.passocs').append("<?php echo str_replace("\n", "", addslashes($passoc)); ?>");
                $j('.removePage').click(function () { $j(this).parent().remove(); return false; });
                return false;
        });

        $j('.removePage').click(function () { $j(this).parent().remove(); return false; });

    </script>

<?php else: ?>
<div style="float:right;">
    <?php
    include_once dirname(__FILE__)."/../classes/webapi.php";
    try {
    $api = new WebAPI('publicapi', 'publicapi');

    $apiBanner = new SimpleXMLElement('<request></request>');
    $apiBanner->addChild('action', 'banner');
    $apiBanner->addChild('width', '468');
    $apiBanner->addChild('height', '60');
    $api->add_request($apiBanner);
    $api->request();
    $banner_468x60 = $api->responseXML->response->answer;

    $api = new WebAPI('publicapi', 'publicapi');
    $apiBanner = new SimpleXMLElement('<request></request>');
    $apiBanner->addChild('action', 'banner');
    $apiBanner->addChild('width', '728');
    $apiBanner->addChild('height', '90');
    $api->add_request($apiBanner);
    $api->request();
    $banner_728x90 = $api->responseXML->response->answer;
    } catch(Exception $e) { /* Fail quitely */ } 
    echo $banner_468x60;
    ?>
</div>
<h3><?php _e("View Banners", "admangler"); ?></h3>
<p><a href="admin.php?page=banners&action=new" class="button"><?php _e("Add New Banner", "admangler"); ?></a></p>
<style type="text/css">
.tip {
    font:10px/12px Arial,Helvetica,sans-serif; 
    border:solid 1px #666666; 
    padding:1px;
    position:absolute; 
    z-index:100;
    visibility:hidden; 
    color:#333333; top:20px;
    left:-9000px; 
    background-color:#ffffcc;
    layer-background-color:#ffffcc;
    }
</style>
<div style="text-align:center;">
        <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
        <input type="hidden" name="cmd" value="_s-xclick">
        <input type="hidden" name="hosted_button_id" value="8E8PU9FKLLGAJ">
        <input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
        <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
        </form>
    </div>
<table class="widefat page fixed" >
    <?php
        $sql = "SELECT * FROM $adMangler->adsTable";
        $results = $wpdb->get_results($sql);
    ?>
    <tr style="background:#DFDFDF" >
        <th class="manage-column column-title" scope="col"><?php _e("Action", "admangler"); ?></th>
        <th class="manage-column column-title" scope="col"><?php _e("Preview", "admangler"); ?></th>
        <!-- <th class="manage-column column-title" scope="col">Advertiser</th> -->
        <th class="manage-column column-title" scope="col"><?php _e("Active", "admangler"); ?></th>
        <th class="manage-column column-title" scope="col"><?php _e("Approved", "admangler"); ?></th>
        <th class="manage-column column-title" scope="col"><?php _e("Base", "admangler"); ?></th>
        <th class="manage-column column-title" scope="col"><?php _e("Page Associations", "admangler"); ?></th>
        <th class="manage-column column-title" scope="col"><?php _e("Type", "admangler"); ?></th>
        <th class="manage-column column-title" scope="col"><?php _e("Size", "admangler"); ?></th>
        <th class="manage-column column-title" scope="col"><?php _e("Impressions", "admangler"); ?></th>
        <th class="manage-column column-title" scope="col"><?php _e("Clicks", "admangler"); ?></th>
    </tr>

    <?php foreach($results as $result): ?>
        <?php                       
            $sql = "SELECT b.post_title,a.page_ID,a.page_exclusive,a.custom_slot,a.slot,a.slot_exclusive FROM $adMangler->positionsTable as a LEFT JOIN $wpdb->posts as b ON a.page_ID=b.ID WHERE a.ad_id=".intval($result->id)." ORDER BY a.page_id ASC";
            $positions = $wpdb->get_results($sql);
        ?>
    <tr class="alternate iedit">
        <td class="column-title">
            <a href="admin.php?page=banners&action=edit&id=<?php echo $result->id; ?>"><?php _e("Edit", "admangler"); ?></a> |
            <a href="admin.php?page=banners&action=delete&id=<?php echo $result->id; ?>" onclick="return confirm('<?php _e("Are you sure you want to delete?", "admangler"); ?>')"><?php _e("Delete", "admangler"); ?></a>
        </td>
        <td> 
            <a href="#" onmouseout="popUp(event,'t<?php echo $result->id; ?>')" onmouseover="popUp(event,'t<?php echo $result->id; ?>')"  onclick="return false"><?php _e("preview", "admangler"); ?></a>
            <div id="t<?php echo $result->id; ?>" class="tip"><?php echo $adMangler->format_ad($result); ?></div>
        </td>
        <!-- <td class="column-title"><?php echo $result->advertiser; ?></td> -->
        <td class="column-title"><?php echo ($result->active) ? __("Yes", "admangler") : __("No", "admangler"); ?></td>
        <td class="column-title"><?php echo ($result->approved) ? __("Yes", "admangler") : __("No", "admangler"); ?></td>
        <td class="column-title"><?php echo ($result->base) ? __("Yes", "admangler") : __("No", "admangler"); ?></td>
        
        <td class="column-title">
            <select>
                <?php 
                    if (is_array($positions) && count($positions) > 0)
                    {
                        foreach($positions as $position) {
                            $name = $position->post_title;
                            $name = ($position->page_ID == -1) ? __("Home","admangler") : $name;
                            $name = ($position->page_ID == 0) ? __("All Pages","admangler") : $name;
                ?>
                            <option>
                                <?php echo $name; ?>&nbsp;
                                <?php echo ($position->page_exclusive) ? _("Ex.","admangler") : "" ?>
                                &nbsp;|&nbsp;
                                <?php if ($position->custom_slot) { echo __("Slot","admangler").":".$position->slot."&nbsp;"; echo ($position->slot_exclusive) ? __("Ex.","admangler") : ""; } ?>
                            </option>
                    <?php
                        }
                    }
                    else
                    {
                        ?><option selected><?php _e("All Pages", "admangler"); ?></option><?php
                    }
                ?>
            </select>
        </td>
        <td class="column-title"><?php echo $result->type; ?></td>
        <td class="column-title">
            <?php echo $result->width; ?>x<?php echo $result->height; ?>
        </td>
        <td class="column-title"><?php echo $result->impressions; ?></td>
        <td class="column-title"><?php echo $result->clicks; ?></td>
    </tr>
    <?php endforeach; ?>
</table>
<div style="text-align:center;">
    <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
    <input type="hidden" name="cmd" value="_s-xclick">
    <input type="hidden" name="hosted_button_id" value="8E8PU9FKLLGAJ">
    <input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
    <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
    </form>
</div>
<div style="margin:20px auto; width:728px; height:90px;">
    <?php echo $banner_728x90; ?>
</div>
<?php
    endif;
?>
