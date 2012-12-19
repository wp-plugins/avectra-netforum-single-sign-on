<?php
/*
Plugin Name: Netforum SSO
Plugin URI: http://fusionspan.com/technology-services/netforum-integration-with-wordpress
Description: Netforum Single Sign on for WordPress using Netforum xWeb SSO service.
Author: Gayathri Kher
Version: 1.0
Author URI: http://fusionspan.com
Compatibility: WordPress 3.4.1
Text Domain: netforum-sso
Domain Path: /lang

----------------------------------------------------------------------------

    Copyright 2012  Gayathri Kher  (email : gkher@fusionspan.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

require_once('netforum_sso_options.php');

class NetforumSSO
{

    static $instance; // to store a reference to the plugin, allows other plugins to remove actions

    private $authenticator = NULL;

    /**
     * Constructor, entry point of the plugin
     */
    function __construct()
    {
        self::$instance = $this;

        add_action('init', array($this, 'init'));
    }

    /**
     * Initialization, Hooks, and localization
     */
    function init()
    {
        require_once('NetforumAuthenticationFunctions.php');

        $ssoUrl = get_option('xweb_sso_wsdl_url');
        $ssoGlobalUser = get_option('xweb_admin_username');
        $ssoGlobalPassword = get_option('xweb_admin_password');

        if ($ssoUrl != '' && $ssoGlobalUser != '' && $ssoGlobalPassword != '') {
            $this->authenticator = new AuthenticationFunctions($ssoUrl, $ssoGlobalUser, $ssoGlobalPassword);
        }
        add_action('login_form', array($this, 'loginform'));
        add_filter('authenticate', array($this, 'netforum_sso_auth'), 50, 3);

    }


    /**
     * Add verification code field to login form.
     */
    function loginform()
    {
        echo "\t<p>\n";
        echo "\tNetforum SSO Enabled\t";
        echo "\t</p>\n";
    }

    /**
     * Login form handling.
     *
     * @param wordpressuser
     * @return user/loginstatus
     */
    function netforum_sso_auth($user, $username = '', $password = '')
    {
//        todo check if this is correct - too many checks
        //see if user is not already authenticate
        if (!is_user_logged_in() &&
                $username != '' &&
                $password != '' &&
             $this->authenticator != null
        ) {
            $ssoToken = '';
            $memberName = $username;
            try {
                $this->authenticator->ssoAuth($username, $password, $ssoToken);

            } catch (Exception $exception) {
                return new WP_Error('netforum_authentication_error', __('<strong>ERROR</strong>: Netforum Authentication Failed.', 'netforum-sso'));

            }

            if (strlen($ssoToken) > 1) {

                $user = get_user_by('login', $username);

                // First time logging in
                if (!$user) {
                    $user_id = wp_create_user($username, '', $username);
                    wp_update_user(array('ID' => $user_id, 'display_name' => $memberName, 'user_identity' => $memberName));
                }

                $user = new WP_User($user->ID);
                update_user_meta( $user->ID, 'user_netforum_sso', $ssoToken );
            }
        }
        return $user;
    }
} // end class

new NetforumSSO;
?>
