<?php
namespace NetAuth\Views; use WP\Views\Page; use WP\Views\Input; class Render extends Page { public static $_4b2038bc4021 = 'netforum'; public static $_df6019b09c31 = array('fusionSpan' => array('uri' => 'netforum', 'menu_logo' => '/images/logo.png', 'pages' => array('general' => 'netforum', 'cache' => 'netforum_cache', 'help' => 'netforum_help'))); public static function getHeaderLogo() { printf('<img alt="fusionSpan" src="%s" width="260" height="60"/>', self::get('assetsUrl') . '/images/logo_big.png'); } public static function getHeader() { printf('<div style="margin-top:-52px; margin-left:255px;">%s
                <div style="font-size: 10px; margin-left: 82px; margin-top:-18px;"><sub>v%s</sub>
                </div></div>', 'netFORUM', self::getPluginVersion()); } public static function getFooter() { $spf853b0 = sprintf(file_get_contents(self::get('jsPath') . '/tr.js'), 'UA-63440930-1'); Input::addJS($spf853b0); printf('<small style="float: right;">&copy; <a href="%s" target="_blank">fusionSpan LLC</a>, %s. All rights reserved.</small>', esc_url('https://fusionspan.com/netforum/'), date('Y')); printf('<br><div style="font-size:8px;float: right;">v%s.</div>', self::getPluginVersion()); } }