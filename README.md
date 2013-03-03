WordPress Modal Login!
==============

Adds a nifty Modal Login for WordPress. I didn't like what was available. So I made my own. Feel free to fork and rip apart. I'll be adding more options like different login styles and other cool stuff.

Install
=======

Pretty simple. Hit the download ZIP button on GitHub and download the files. Extract the zip and drop the folder into /wp-content/plugins/.

Activate the plugin in the admin area of your WordPress install.

Usage
=====

You have two options at the moment, the built in Widget or add straight to your theme with the PHP function.


WIDGET
-------

Navigate to Appearance > Widgets in the admin area. Locate the WP Modal Login widget in the Available Widgets section and drag and drop into a widgetized area for your currently active theme.

You have four options in the widget window, Widget Title, Login Text, Logout Text and Logout URL.
WIDGET TITLE: Add a title to the widget. (optional)
LOGIN TEXT: Set the text for the login link. Defaults to "Login".
LOGOUT TEXT: Set the text for the logout link. Defaults to "Logout".
LOGOUT URL: Set the logout URL redirection. Defaults to the home_url() http://codex.wordpress.org/Function_Reference/home_url


PHP FUNCTION
------------

You may use the PHP function used by the Widget in any location in your theme. Just ensure the plugin is activated to use.

Example:
$modal_login = new Geissinger_WP_Modal_Login;
echo $modal_login;


To Do
=====

Add Shortcode with button in editor.
Add different modal login styles/layouts.
Add area for custom styles.
