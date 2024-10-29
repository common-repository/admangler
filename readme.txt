=== AdMangler ===
Contributors: Allen Sanford
Donate link: http://www.webternals.com/donate-admangler.php
Tags: Ads
Requires at least: 2.8.2
Tested up to: 4.7.5
Stable Tag: 1.1.0

AdMangler Display, sell, and manage ad space on your Wordpress powered site.

== Description ==

AdMangler provides an intuitive and easy to use interface to help you display banner ads, Google ads, or
your very own custom HTML ads on your wordpress powered site. AdMangler can help you get a hold on your ads
and help you easily swap ads, disable ads, and enable ads. AdMangler will also allow you to designate
certain ads to be exclusive to a page, a slot, or a slot on a certain page allowing you to determine
which ads show on which page or which ad slot.

AdMangler has been designed with flexibility in mind, allowing you to control your ad spaces the way you want
to control them. Whether you wish to display Adsense style ads, upload banner ads, sell your ad space or
mix it up and do a little of everything.

You can use AdMangler as a sidebar Widget, in your template files using a straight PHP call, or in the content
editor using the custom tags. With AdMangler you are in control over how and where your ads are displayed.

Features in the free Version:
<ul>
    <li>Create Banner Ads using either the Wordpress Media Manager or providing your own HTML</li>
    <li>Associate any Ad with a specific page and/or ad position on any given page or post</li>
    <li>Make an Ad exclusive for any Ad spot/page or post.</li>
    <li>Display Ads using the AdMangler Sidebar Widget.</li>
    <li>Display ads directly in your theme.</li>
    <li>Display ads in the body of your post or page using the AdMangler shortcodes/AdMangler Shortcode Helper.</li>
    <li>Shortcode Helper Icon on the content editor to help you setup shortcodes.</li>
    <li>Cache buster feature in the options to help you serve rotating ads even when using a caching plugin.</li>
    <li>Simple Statistics show impression and clicks on each banner.</li>
</ul>

Need more control over your ad space? Want to automate selling your adspace? Allow advertisers to purchase credits? AdMangler Pro
is the ultimate Ad Management plugin for Wordpress. You can find how how to purchase it by
visiting <a href="http://www.webternals.com/contact.php">http://www.webternals.com/contact.php</a>. AdMangler Pro
is currently under development and available only as a pre order.

Features in AdMangler Pro:
<ul>
    <li>All the features found in the free versions.</li>
    <li>Front-end registration form (Advertisers will sign up here)</li>
    <li>Front-end login form (Advertisers will login here) </li>
    <li>Front-end reset password form (Advertisers will use this to request password resets)</li>
    <li>The ability to automate selling ads.</li>
    <li>Advertiser management Page/</li>
    <li>Paypal integration.</li>
    <li>Dwolla integration.</li>
    <li>Billing Options.</li>
    <li>Comprehensive Statistics.</li>
</ul>

If you would like to contribute please contact admangler@webternals.com and tell us where you feel you can contribute.

More Information can be found at http://www.webternals.com/projects/admangler/

== Installation ==

1. Upload `admangler.zip` or `admangler.tgz` to the `/wp-content/plugins/` directory and extract the contents.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Template File Usage (Do not use position 0 as its results are not what you expect): <?php do_shortcode('[AdMangler width="468" height="60" position="1"]'); ?>
1. Wordpess ShortCodes for pages and posts: [AdMangler width="468" height="60"]
1. (Coming Soon!) To display the AdMangler Front-end panel you will need to create an empty page and place the following tag in that page: [AdMangler type="Panel"]

== Frequently Asked Questions ==


== Screenshots ==
1. Screenshot 1
1. Screenshot 2
1. Screenshot 3

== Changelog ==

= 1.1.0 =
* End a 4 year long streak of not maintaining this code!
* Got the Widget working.
* Code formatting updates!

= 1.0.0 =
* Click and Impression are now being logged
* Random Bug Fixes
* Beta status lifted

= 0.1.7 =
* Added an Options Page
* Added a Cache Buster Option to the Options page to make AdMangler work with caching plugins
* Bug fixes (Seems I broke a lot things wiht the I18n updates. Carefully reviewed everything and they are fixed)
* Added cache buster JavaScript to keep ads rotating when cache buster is activated!

= 0.1.6 =
* Bug fix

= 0.1.5 =
* Readied the plugin for internationalization or (I18n ready)
* Bug fixes pertaining to curl an fsock open calls.
* Changed `stream_context_create` to `fsockopen`
* Checking for curl using it when possible and using fsockopen as a backup.
* Added deactivate, and uninstall functions ( Nothing fancy in them but they are in place )

= 0.1.4 =
* Fixed anonymous function use. Anonymous function are only full supported in PHP 5.3 and above. Shame on me!

= 0.1.3 =
* Version mismatch

= 0.1.2 =
* Bug fix for plugin automatic update!

= 0.1.1 =
* Added the shortcode icon to the content editor
* Replaced CURL calls with `stream_context_create` to enable a broader support of web servers

= 0.1.0 =
* Fixed some mispelled words
* Add the built in media uploader for image type banners

= 0.0.10.1.Alpha =
* Bug in the preview banner page fixed!

= 0.0.10.Alpha =
* Fixed bug in activation code.
* Cleaned up a few method names
* Moved some into the code to the __construct method

= 0.0.9.4.Alpha =
* Keep Alive Commit

= 0.0.9.3.Alpha =
* Improved Template File Usage capabilities
* Dirty code cleanup.

= 0.0.9.2.Alpha =
* Bug fix for page associations only showing the first 'X' Posts or Pages.

= 0.0.9.1.Alpha =
* Improved Page associations and exclusive options

= 0.0.9.Alpha =
* Some major bug fixes and solidifying the final changes found in 0.0.8.5.Alhpa

= 0.0.8.5.Alpha =
* Deprecated the GetAds Method in favor of the GetAd Method
* Added the ability to associate an Ad with a specific Page and position on a page.
* Added the ability to make an Ad exclusive for a particular position on a page. (Disables the random selection for that ad spot)
* Bug fix to prevent duplicate ads being created when editing a newly created ad
* Bug fixes for unexpected output during activation
* Removed the items that have some missing functionality

= 0.0.8.4.Alpha =
* Database update was missed in the last version this update fixes it

= 0.0.8.3.Alpha =
* Support for the Wordpress built in shortcodes functionality
* Added an Image type that excepts Image Location and Image Link. No need to know HTML for this.

= 0.0.8.2.Alpha =
* Wordpress 3.0 Compatibility Upgrade

= 0.0.8.1.Alpha =
* Wordpress 2.9 Compatibility Upgrade

= 0.0.8.Alpha =
* Fixed a bug dealing with multiple ad sizes not displaying correctly
* Updated dashboard instructions

= 0.0.7.1.Alpha =
* Minor Bug fixes
* Updated compatibility version

= 0.0.7 Alpha =
* Implemented jQuery and jQuery validate plugin to do the validations on the forms
* Implemented the front-end registration form (Advertisers will sign up here)
* Implemented the front-end login form (Advertisers will login here)
* Implemented the front-end reset password form (Advertisers will use this to request password resets)
* The panel has not been implemented yet, but there is a placeholder page for the panel. (This is were advertisers will manage their accounts)
* Bug fixes

= 0.0.6.1 Alpha =
* Fixed a formatting bug

= 0.0.6 Alpha =
* Bug fixes dealing with multiple banner ads
* Bug fix dealing with the displaying with a post or page using the [AdMangler:468x60] style tags

= 0.0.5.1 Alpha =
* Fixed empty array bug

= 0.0.5 Alpha =
* Added Sidebar Widget Support
* Bug Fix (wp-admin login page)

= 0.0.4 Alpha =
* Improved aesthetics integrated Wordpress built in styling
* Added delete button to the edit banner page
* Added a preview to the banner list
* Added icon to the menu for AdMangler
* Other minor bug fixes

= 0.0.3 Alpha =
* Trying to get wordpress.org to work properly updating the repo

= 0.0.2 Alpha =
* Cleaned up some rather dirty stuff
* Made the banner editing form more intuitive. (This will make old data obsolete you need to use the new structure)
* Added an explanation how to use AdMangler to the dashboard.
* Put Coming soon message under settings (Eventually will use this for things like "stop taking orders option", "paypal email", etc...).

= 0.0.1 Alpha =
* Add and Remove Banner Ads
* Display and Rotate Random Banner Ads

