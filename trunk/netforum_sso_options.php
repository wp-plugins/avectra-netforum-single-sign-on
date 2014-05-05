<?php
/*
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
// create custom plugin settings menu
add_action('admin_menu', 'netforum_sso_create_menu');

function netforum_sso_create_menu()
{
//    //create new top-level menu
//    add_menu_page('Netforum SSO Settings', //page_title
//                  'Netforum SSO Settings', //menu title
//                  'administrator', //capability
//                  __FILE__,
//                  'addNetforumSsoOptions',
//                  plugins_url('/images/icon.png', __FILE__));

    add_options_page('Netforum SSO Settings', //page_title
                     'Netforum SSO',//menu title
                     'manage_options',//capability
                     __FILE__,
                     'addNetforumSsoOptions');
    //call register settings function
    add_action('admin_init', 'registerNetforumSettings');
}

function registerNetforumSettings()
{
    //register our settings
    register_setting('netforum-sso-settings-group', //settings page
                     'xweb_sso_wsdl_url' //option name
    );
    register_setting('netforum-sso-settings-group', 'xweb_admin_username');
    register_setting('netforum-sso-settings-group', 'xweb_admin_password');
}

function addNetforumSsoOptions()
{
    ?>
<div class="wrap">
    <h2>Netforum SSO</h2>

    <form method="post" action="options.php">
        <?php settings_fields('netforum-sso-settings-group'); ?>
        <?php do_settings_sections('netforum-sso-settings-group'); ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">xWeb Single SignOn WSDL URL</th>
                <td><input type="text" name="xweb_sso_wsdl_url" size="60"
                           value="<?php echo get_option('xweb_sso_wsdl_url'); ?>"/></td>
            </tr>

            <tr valign="top">
                <th scope="row">xWeb Admin Username</th>
                <td><input type="text" name="xweb_admin_username" size="30"
                           value="<?php echo get_option('xweb_admin_username'); ?>"/></td>
            </tr>

            <tr valign="top">
                <th scope="row">xWeb Admin Password</th>
                <td><input type="text" name="xweb_admin_password" size="30"
                           value="<?php echo get_option('xweb_admin_password'); ?>"/></td>
            </tr>
        </table>

        <?php submit_button(); ?>

    </form>
</div>
<?php } ?>