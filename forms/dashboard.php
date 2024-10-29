<?php
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
?>
    <div style="text-align:center;">
        <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
        <input type="hidden" name="cmd" value="_s-xclick">
        <input type="hidden" name="hosted_button_id" value="8E8PU9FKLLGAJ">
        <input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
        <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
        </form>
    </div>

    <div style="padding:10px;">

    <?php _e("Dear AdMangler User", "admangler"); ?>,
    <br /><br />
    <?php _e("You won't believe what I have gone and gotten myself into this time. I have written a plugin that is 
    beginning to gain momentum in the Wordpress community. Your help would be greatly appreciated. No, I don't expect 
    you to write code or even submit bug reports - so you can breathe that sigh of relief! But a donation 
    to Webternals, LLC in support of the AdMangler plugin would be wonderful and also help shift more resources 
    towards this project!", "admanlger","admangler"); ?>
    <br /><br />
    <?php _e("If you can help, please make a donation using the button provided. Then you can sit back with your lemonade, 
    while I am working to improve AdMangler for the community as a whole! Thank you so much!", "admangler"); ?>
    <br /><br />
    <?php _e("The Webternals Team", "admangler") ?>
    <br /><br />
    <div style="margin:20px auto; width:468px; height:60px;">
        <?php echo $banner_468x60; ?>
    </div>
</div>
    
<h3><?php _e("New this Version", "admangler", "admangler"); ?></h3>
<ol>
    <li><?php _e("Added an Options Page"); ?></li>
    <li><?php _e("Added a Cache Buster Option to the Options page to make AdMangler work with caching plugins"); ?></li>
    <li><?php _e("Bug fixes (Seems I broke a lot things wiht the I18n updates. Carefully reviewed eveything and they are fixed)"); ?></li>
    <li><?php _e("Added cache buster javascript to keep ads rotating when cache buster is activated!"); ?></li>
</ol>
<h3><?php _e("Using Admangler", "admangler"); ?></h3>
<ol>
    <li><?php _e("Please Report Any Bugs Found to", "admangler"); ?>: <strong><a href="mailto:bugs@webternals.com">bugs@webternals.com</a></strong><br /><br /></li>
    <li>
        <b><?php _e("ShortCode Support", "admangler"); ?>:</b> <em><?php _e("(The built in wordpress shortcode feature)", "admangler"); ?></em><br />
        <?php _e("There is now an icon on the content builder to help you build your shortcode, just look for this icon", "admangler"); ?> <img src="/<?php echo PLUGINURL ?>/images/logo.gif" alt="" /> <?php _e("on the content editor", "admangler"); ?><br />
        [AdMangler width="468" height="60"]<br />
        [AdMangler width="468" height="60" position="1"] <?php _e("(Position is optional; Don't use 0 as it is the default and you want get the result you are looking for)", "admangler"); ?><br />
        <br />
    </li>
    <li>
        <b><?php _e("Template File Usage", "admangler"); ?>:</b>
        <br />
        &lt;?php echo do_shortcode('[AdMangler width="468" height="60" position="1"]'); ?&gt;<br />
        <br />
    </li>
    <li>
        <b><?php _e("AdMangler Widget", "admangler"); ?>:</b> <?php _e("(On the Wordpress Backend)", "admangler"); ?><br>
        <i><?php _e("Appearance -> Widgets -> AdMangeler Widget", "admangler"); ?></i>
    </li>
</ol>
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


