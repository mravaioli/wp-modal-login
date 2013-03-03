WordPress Modal Login!
==============

Adds a nifty Modal Login for WordPress. I didn't like what was available. So I made my own. Feel free to fork and rip apart. I'll be adding more options like different login styles and other cool stuff.

Install
=======

Pretty simple. Hit the download ZIP button on GitHub and fork the files. Extract the zip and drop the folder into /wp-content/plugins/.

Activate the plugin in the admin area of your WordPress install.

Usage
=====

You have two options at the moment, the built in Widget or add straight to your theme with the PHP function.


WIDGET
-------

Navigate to Appearance > Widgets in the admin area. Locate the WP Modal Login widget in the Available Widgets section and drag and drop into a widgetized area for your currently active theme.

You have four options in the widget window, <em>Widget Title</em>, <em>Login Text</em>, <em>Logout Text</em> and <em>Logout URL</em>.<br />
<strong>WIDGET TITLE</strong>: Add a title to the widget. (optional)<br />
<strong>LOGIN TEXT</strong>: Set the text for the login link. Defaults to "<em>Login</em>".<br />
<strong>LOGOUT TEXT</strong>: Set the text for the logout link. Defaults to "Logout</em>".<br />
<strong>LOGOUT URL</strong>: Set the logout URL redirection. Defaults to the <em>home_url()</em> http://codex.wordpress.org/Function_Reference/home_url


PHP FUNCTION
------------

You may use the PHP function used by the Widget in any location in your theme. Just ensure the plugin is activated to use.

Example:<br />
<em>$modal_login = new Geissinger_WP_Modal_Login;<br />
echo $modal_login;</em>


To Do
=====

&bull; Add Shortcode with button in editor.<br />
&bull; Add different modal login styles/layouts.<br />
&bull; Add area for custom styles.<br />
