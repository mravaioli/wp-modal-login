=== WP Modal Login ===
Contributors: Brainfestation
Donate link:
Tags: modal login, popup login, pop-up login
Requires at least: 3.5
Tested up to: 3.6
Stable tag: 2.0.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A highly configurable and versatile modal (pop-up) login form.

== Description ==

Creates an easy to use login form that will display a pop-up window (modal window) for easy login, user registration or reset a password all from the front-end of any website.

This is easily managed using a custom widget, shortcode generator or you can use PHP to insert this plugin into any custom area of your theme.

= What's New in 2.0 =
In version 2.0 we did a complete overhaul. We got some great feedback from users about what they would like to see in the next major release and we packed virtually every request in it. [For more details, visit our demo site](http://wp-modal-login.colegeissinger.com/)

* New admin page to centralize all the new customizations you can do to personalize your modal login.
* Pre-designed themes with the option to custimize the CSS or create your own styles by adding the css directly into the admin area.
* Ajax enabled login, user registration and password retrieval for fast and user friendly interactions.
* The ability to enable or disable the custom widget or shortcode generator in the post editor.
* Simplified PHP function easier use and customizations.
* i18n support.

= What to Expect in Future Releases =
* Role based login & logout redirects
* More modal themes to be added and better theme customizations.
* Plus more features!

Got an idea for a feature you'd like to see in here? Please feel free to [leave a suggestion](http://wordpress.org/support/plugin/wp-modal-login) on the support forums!

== Installation ==

1. Upload `wp-modal-login` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

= WIDGET SETUP =
Navigate to Appearance > Widgets in the admin area. Locate the WP Modal Login widget in the Available Widgets section and drag and drop into a widgetized area for your currently active theme.

You have four options in the widget window, Widget Title, Login Text, Logout Text and Logout URL.<br />
**WIDGET TITLE**: Add a title to the widget.<br />
**LOGIN TEXT**: Set the text for the login link. Defaults to "Login".<br />
**LOGOUT TEXT**: Set the text for the logout link. Defaults to "Logout".<br />
**LOGOUT URL**: Set the logout URL redirection. Defaults to the [home_url()](http://codex.wordpress.org/Function_Reference/home_url) function
**SHOW ADMIN LINK**: Displays a link to the admin URL when a user is logged in.

= SHORTCODE SETUP =
You can use the shortcode in any edit screen for posts, pages or custom post types. In any post edit screen, you will see a "lock" icon, clicking this will invoke a modal window where you can add custom login text, logout text and/or logout url (used for redirecting users after logout). Click the "Insert Modal Login Shortcode" button to insert into post.

= ADD TO THEME WITH PHP =
With version 2.0 we deprecated the old method and created a new, easier to use function that should just work!

`<?php add_modal_login_button( $login_text = 'Login', $logout_text = 'Logout', $logout_url = '', $show_admin = true ); ?>`
The new PHP function accepts 4 arguments, each described below in greater detail.

**$login_text**  | String | The text for the login link. Defaults to 'Login'.<br />
**$logout_text** | String | The text for the logout link. Default 'Logout'.<br />
**$logout_url**  | String | The url to redirect to when users logout. Empty by default and uses the home_url() function.<br />
**$show_admin**  | Bool	  | The setting to display the link to the admin area when logged in. Default is 'true'.<br />

**DEPRECATED AS OF v2.0**:
`<?php $modal_login = new Geissinger_WP_Modal_Login; echo $modal_login; ?>`

== Changelog ==
= 2.0.6 =
* Fixed permanent hang on reseting password with "Forgotten Password".
* Increased security in multiple areas of the plugin.
* Fixed tabbing in forms so they actually move your cursor in the proper order.

= 2.0.5.2 =
* Fixed an issue where users could still register even though its been disabled. (Reported by @grcrane).

= 2.0.5.1 =
* Added Portuguese translation as provided by Debora Bossois (@deborazb).

= 2.0.5 =
* Fixed overly sanitized passwords when logging in (it was stripping out special characters).
* Added Spanish translation from David Portillo (wethinkapp.com).

= 2.0.4 =
* Fixed a display issue in the admin page. All settings showing as '01'.

= 2.0.3 =
* Major updates to the translation of the plugin. It works now! Feel free to translate away! .POT file available in /langs/
* Added new classes to the login button for easier style customizations.
* Added Apply Filters to the redirecturl as so kindly provided by [@joshlevinson](http://wordpress.org/support/topic/redirect-36?replies=2)

= 2.0.2 =
* Fixed issue of login when using alongside other Ajax plugins.
* Removed esc_url() from the wp_logout_url() as it created too much sanitization and caused logout issues.
* Updated mislabeled text domain in wpml-admin-page.php and updated en.po.
* Added option to disable the "Register" button in the modal window if using another registration system.

= 2.0.1 =
* Moved admin page to be a submenu under Settings.
* Fixed some display issues with the themes under IE8 (IE7 and below not supported, sorry!).
* Fixed persistent "Back to Login" link.
* Added a catch all check if Ajax doesn't go smoothly.

= 2.0 =
* Added admin page.
* Added three pre-design themes to choose from.
* Added option to set an empty modal theme and option to add your own CSS code.
* Added the options to register a user account or reset a user password.
* Added Ajax to the login, user registration and forgotten password forms.
* Added the ability to enable or disable the modal widget and shortcode generator in the post editor.
* Added a new PHP function that will simplify the use of the PHP option.
* Added i18n support.
* Updated the widget to have the option to display a link to the admin area when logged in.
* Deprecated the old method for adding the modal via PHP.

= 1.1 =
* Added Shortcode generator to TinyMCE visual editor.
* Fixed bug in widget that caused a fatal error in the front-end.

= 1.0 =
* Initial release.

== Screenshots ==

1. This is the admin settings screen.
2. A preview of the default modal login theme.
3. A preview of the Theme 1.
4. A preview of the Theme 2.
5. Theme selection options in the admin area.
6. The lock icon in the far right is the shortcode generator so you can add the modal login to any page or post.
7. A taste of the new Ajax features in use with Theme 2 in use.
8. Modal widget options.