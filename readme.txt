=== Netforum SSO  ===
Contributors: gkher
Donate link: http://fusiopnspan.com/
Tags: avectra, netforum, sso
Requires at least: 3.0.1
Tested up to: 3.8.1
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

NetForum Pro Single Sign on. Allows users to sign in to wordpress using Avectra NetForum Pro credentials.


== Description ==
This plug-in allows for Single Sign on between netForum and Wordpress. Users can sign in to Wordpress using their netForum credentials.

We have additional plug-ins available that compliment this SSO plug-in. Listed below

* Security Groups (Free) - Allows you to restrict content on your Wordpress CMS site, based on the users Membership status

* SSO with Bi-directional sync - In addition to basic SSO, this plug-in allows you to manage (create/update/delete) users in Wordpress, and this information is automatically synched back to netForum. Wordpress users can edit their profile and their information is automatically updated in netForum. Adds additional fields to the Wordpress user profile and allows for field mapping between Wordpress and netForum. Contact support@fusionspan.com if you would like a demo.

== Installation ==

Netforum Pro SSO plugin uses the Netforum xWeb web services to authenticate users. This allows
for Single Sign on Capabilities, where users can sign in to Wordpress using their Netforum
credentials (username and password).

For this plug-in to work, you need to have a xWeb service available. xWeb is available to all
Netforum Team and Pro subscriptions.

e.g.

1. Unzip the `netforum_sso.zip` in to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go into the Wordpress Dashboard  and configure the plugin by going into
   Settings -> Netforum SSO
   The following options need to be configured

   xWeb Single SignOn WSDL URL - is the URL for the Netforum xWeb SSO web service
   xWeb Admin Username - The username for the xWeb service
   xWeb Admin Username - The password for the xWeb service
   

== Frequently Asked Questions ==

= How does this plugin authenticate users? =

The Netforum SSO plugin uses the Netforum xWeb web service API, to authenticate users.

= Does it create new users in Wordpress? =
It will create users in Wordpress if they don't exist, on the users first login. It does
not save the users password in Wordpress. So the user will always need to sign in with
their Netforum credentials. Users need to navigate to Netforum to change/update their passwords.

= How can I create hyperlinks to Netforum so that the wordpress user doesn't have to login again? =

The netforum_sso plugin saves the users xWeb single sign on token as user metadata in Wordpress. You need to
append this SSO Token to the hyperlinks to Netforum. This way the users can navigate over to
Netforum and not have to login again.  The SSO token is saved as the user_netforum_sso
field in the user metadata in Wordpress.

= Does it work with NetForum Enterpsie? =
With minor modifications it will work. But this particular free plug-in is designed and tested to work with NetForum Pro.

== Screenshots ==

1. Plugin Settings. Enter your xWeb credentials here (received from Avectra)


== Changelog ==

= 1.0 =
* First release.

== Upgrade Notice ==

= 1.0 =
First release

