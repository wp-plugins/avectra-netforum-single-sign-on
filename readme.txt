=== netFORUM Single Sign On  ===
Contributors: fsahsen
Donate link: http://fusiopnspan.com/
Tags: avectra, netforum, sso, single sign on
Requires at least: 3.0.1
Tested up to: 4.2
Stable tag: 0.5-alpha
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

netFORUM Single Sign on. Allows users to sign in to wordpress using Avectra netFORUM credentials.


== Description ==
This plug-in allows for Single Sign On between netFORUM and Wordpress. Users can sign in to Wordpress using their netFORUM credentials.

We have additional plug-ins available that compliment this SSO plug-in. Listed below

* Security Groups - Allows you to restrict content on your Wordpress CMS site, based on the users Membership status in netFORUM.

* SSO with Bi-directional sync - In addition to basic SSO, this plug-in allows you to manage (create/update) users in Wordpress, and this information is automatically synched back to netFORUM. Wordpress users can edit their profile and their information is automatically updated in netFORUM. Adds additional fields to the Wordpress user profile and allows for field mapping between Wordpress and netFORUM. Contact support@fusionspan.com if you would like a demo.

* To know about our newest plugins, feel free to visit the link below.

* Contributed by [fusionSpan](http://fusionspan.com/netforum-plug-ins/ "netFORUM plugins by fusionSpan.com").

== Installation ==

netFORUM Pro SSO plugin uses the netFORUM xWeb web services to authenticate users. This allows
for Single Sign on Capabilities, where users can sign in to Wordpress using their netFORUM
credentials (username and password).

For this plug-in to work, you need to have a xWeb service available. xWeb is available to all
netFORUM Team and Pro subscriptions.

e.g.

1. Unzip the `netFORUM_sso.zip` in to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go into the Wordpress Dashboard  and configure the plugin by going into
   admin page and clicking the sidebar menu item fusionSpan.
   The following options need to be configured

   xWeb Single SignOn WSDL URL - is the URL for the netFORUM xWeb SSO web service
   xWeb Username - The username for the xWeb service
   xWeb Password - The password for the xWeb service
   

== Frequently Asked Questions ==

= How does this plugin authenticate users? =

The netFORUM SSO plugin uses the netFORUM xWeb web service API, to authenticate users.

= Does it create new users in Wordpress? =
It will create users in Wordpress if they don't exist, on the users first login. It does
not save the users password in Wordpress. So the user will always need to sign in with
their netFORUM credentials. Users need to navigate to netFORUM to change/update their passwords.

= I am getting a "Client credentials are required" error message. How do I fix this? =
Usually this error is created when the netFORUM login credentials set in plugin page, under the General tab is incorrect.
To fix this double check the credentials you entered in the plugin page.

= How can I create hyperlinks to netFORUM so that the wordpress user doesn't have to login again? =

The netFORUM_sso plugin saves the users xWeb single sign on token as a session variable in Wordpress.
You need to append this SSO Token to the hyperlinks to netFORUM. This way the users can navigate over to
netFORUM and not have to login again.  The SSO token is saved as the user_netforum_sso
PHP session variable in Wordpress.

To use it in a page or post, you will need to install another plug-in (there are several), that will allow
inline php code in your page. And something like the snippet below will then work.

`[insert_php]

if(is_user_logged_in()){
  echo 'netForum SSO Token is: ' .$_SESSION['user_netforum_sso'];
}

[/insert_php]`



* The above example uses the “Insert PHP WordPress Plugin”. 


= Does it work with netFORUM Enterprise? =
It most likely does, but we have not tested it with netFORUM enterprise. Since the
netFORUM xWeb services are the same when it comes to authentication, so this plug-in should work. If not, send us
an email and we can help you tweak it to work with your netFORUM enterprise installation.


== Screenshots ==

1. Plugin Settings. Enter your xWeb credentials here (received from Avectra)

2. Plugin Cache Settings. The cache holds previous request to netFORUM and their results.


== Changelog ==

= 0.5-dev =
* Fixes some bugs.

= 0.4-dev =
* Rewrite complete package.
