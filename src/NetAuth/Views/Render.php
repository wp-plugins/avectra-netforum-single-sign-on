<?php namespace NetAuth\Views;

use WP\Views\Page;
use WP\Views\Input;

class Render extends Page
{
    /**
     * Default plugin page.
     *
     * @var string
     */
    static public $default = 'netforum';

    /**
     * An array containing all plugin pages.
     *
     * @var array
     */
    static public $plugin = ['fusionSpan' => [
        'uri'       => 'netforum',
        'menu_logo' => '/images/logo.png',
        'pages'     => [
            'general' => 'netforum',
            'cache'   => 'netforum_cache',
            //'groups sync'        => 'netforum_groups',
            //'group capabilities' => 'netforum_group_capabilities',
            'help'    => 'netforum_help',
        ]]
    ];

    /**
     * Logo on the plugin page header.
     */
    static public function getHeaderLogo()
    {
        printf('<img alt="fusionSpan" src="%s" width="260" height="60"/>',
            self::get('assetsUrl') . '/images/logo_big.png'
        );
    }

    /**
     * Plugin page header.
     */
    static public function getHeader()
    {
        printf('<div style="margin-top:-52px; margin-left:255px;">%s
                <div style="font-size: 10px; margin-left: 82px; margin-top:-18px;"><sub>v%s</sub>
                </div></div>',
            'netFORUM',
            self::getPluginVersion()
        );
    }

    /**
     * Plugin page footer.
     */
    static public function getFooter()
    {
        $js = sprintf(file_get_contents(self::get('jsPath') . '/tr.js'),
            'UA-63440930-1'
        );

        Input::addJS($js);

        printf('<small style="float: right;">&copy; <a href="%s" target="_blank">fusionSpan LLC</a>, %s. All rights reserved.</small>',
            esc_url('https://fusionspan.com/netforum/'),
            date('Y')
        );

        printf('<br><div style="font-size:8px;float: right;">v%s.</div>',
            self::getPluginVersion()
        );
    }
}