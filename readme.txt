=== netForum SSO  ===
Contributors: fusionspan
Donate link: http://fusiopnspan.com/
Tags: avectra, netforum, sso
Requires at least: 3.0.1
Tested up to: 3.9
Stable tag: 1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

netForum Pro Single Sign on. Allows users to sign in to wordpress using Avectra netForum Pro credentials.


== Description ==
Contributed by fusionSpan (www.fusionspan.com). This plug-in allows for Single Sign on between netForum and Wordpress. Users can sign in to Wordpress using their netForum credentials.

We have additional plug-ins available that compliment this SSO plug-in. Listed below

* Security Groups - Allows you to restrict content on your Wordpress CMS site, based on the users Membership status in netForum.

* SSO with Bi-directional sync - In addition to basic SSO, this plug-in allows you to manage (create/update) users in Wordpress, and this information is automatically synched back to netForum. Wordpress users can edit their profile and their information is automatically updated in netForum. Adds additional fields to the Wordpress user profile and allows for field mapping between Wordpress and netForum. Contact support@fusionspan.com if you would like a demo.

== Installation ==

netforum Pro SSO plugin uses the netForum xWeb web services to authenticate users. This allows
for Single Sign on Capabilities, where users can sign in to Wordpress using their netforum
credentials (username and password).

For this plug-in to work, you need to have a xWeb service available. xWeb is available to all
netforum Team and Pro subscriptions.

e.g.

1. Unzip the `netforum_sso.zip` in to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go into the Wordpress Dashboard  and configure the plugin by going into
   Settings -> netForum SSO
   The following options need to be configured

   xWeb Single SignOn WSDL URL - is the URL for the netForum xWeb SSO web service
   xWeb Admin Username - The username for the xWeb service
   xWeb Admin Username - The password for the xWeb service
   

== Frequently Asked Questions ==

= How does this plugin authenticate users? =

The netforum SSO plugin uses the netForum xWeb web service API, to authenticate users.

= Does it create new users in Wordpress? =
It will create users in Wordpress if they don't exist, on the users first login. It does
not save the users password in Wordpress. So the user will always need to sign in with
their netForum credentials. Users need to navigate to netForum to change/update their passwords.

= How can I create hyperlinks to netForum so that the wordpress user doesn't have to login again? =

The netforum_sso plugin saves the users xWeb single sign on token as a session variable in Wordpress.
You need to append this SSO Token to the hyperlinks to netForum. This way the users can navigate over to
netForum and not have to login again.  The SSO token is saved as the user_netforum_sso
PHP session variable in Wordpress.

To use it in a page or post, you will need to install another plug-in (there are several), that will allow
inline php code in your page. And something like the snippet below will then work.

`[insert_php]

if(is_user_logged_in()){
  echo 'netForum SSO Token is: ' .$_SESSION['user_netforum_sso'];
}

[/insert_php]`


* The above example uses the “Insert PHP WordPress Plugin”. 


= Does it work with netForum Enterprise? =
It most likely does, but we have not tested it with netForum enterprise. Since the
netForum xWeb services are the same when it comes to authentication, so this plug-in should work. If not, send us
an email and we can help you tweak it to work with your netForum enterprise installation.


== Screenshots ==

1. Plugin Settings. Enter your xWeb credentials here (received from Avectra)


== Changelog ==

= 1.1 =
* Upgraded to Wordpress 3.9
* New session variable $_SESSION['user_netforum_sso'] stores the netForum SSO Token

= 1.0 =
* First release.

== Upgrade Notice ==

= 1.1 =
* Upgraded to Wordpress 3.9

= 1.0 =
First release


