=== WP Modal Login ===
Contributors: Brainfestation
Donate link: 
Tags: modal login, popup login, pop-up login
Requires at least: 3.5
Tested up to: 3.5.1
Stable tag: 1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A highly configurable and versatile modal (pop-up) login form.

== Description ==

Creates an easy to use login form that will display a pop-up window (modal window) for easy login from the front-end of any website.

This is easily managed using a custom widget, shortcode generator or you can use PHP to insert this plugin into any custom area of your theme.

== Installation ==

1. Upload `wp-modal-login` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

= WIDGET =
Navigate to Appearance > Widgets in the admin area. Locate the WP Modal Login widget in the Available Widgets section and drag and drop into a widgetized area for your currently active theme.

You have four options in the widget window, Widget Title, Login Text, Logout Text and Logout URL.
**WIDGET TITLE**: Add a title to the widget. (optional)
**LOGIN TEXT**: Set the text for the login link. Defaults to "Login".
**LOGOUT TEXT**: Set the text for the logout link. Defaults to "Logout".
**LOGOUT URL**: Set the logout URL redirection. Defaults to the home_url() http://codex.wordpress.org/Function_Reference/home_url

= SHORTCODE =
You can use the shortcode in any edit screen for posts, pages or custom post types. In any post edit screen, you will see a "lock" icon, clicking this will invoke a modal window where you can add custom login text, logout text and/or logout url (used for redirecting users after logout). Click the "Insert Modal Login Shortcode".

= ADD TO THEME WITH PHP =
You can also use this plugin inside any part of your theme or plugin if you wish using the code sample below.

`<?php $modal_login = new Geissinger_WP_Modal_Login;`
`echo $modal_login; ?>`

== Changelog ==
= 1.1 =
* Added Shortcode generator to TinyMCE visual editor.
* Fixed bug in widget that caused a fatal error in the front-end.