<?php
namespace WP\Views; use WP\Traits\Helpers; class Page { use Helpers; public static $default; public static $static; public function __construct() { self::set('caller', get_called_class()); $spf454e5 = array('nf_logo' => 'getHeaderLogo', 'nf_head' => 'getHeader', 'nf_tabs' => 'getTabs', 'nf_foot' => 'getFooter'); add_action('admin_menu', array($this, 'makeView')); array_walk($spf454e5, function ($sp4312bc, $sp52c592) { if (method_exists(self::get('caller'), $sp4312bc)) { add_action($sp52c592, array(self::get('caller'), $sp4312bc)); } }); } public static function getTpl() { $sp00276a = self::get('namespace') . '\\' . ucfirst(camel_case(self::get('page'))); class_exists($sp00276a) ? add_action('nf_body', array($sp00276a, 'getInstance')) : null; include_once self::get('templatePath') . '/main.tpl'; if (!class_exists($sp00276a)) { dd('class ' . $sp00276a . ' doesn\'t exist.'); } } public static function makeView() { self::bootstrap(); $sp0bb1ac = self::get('caller'); array_walk($sp0bb1ac::$plugin, function ($sp4312bc, $sp52c592) use($sp0bb1ac) { if (!self::isMenuItemExists($sp52c592)) { add_menu_page(ucwords($sp52c592), $sp52c592, 'manage_options', $sp4312bc['uri'], array($sp0bb1ac, 'getTpl'), self::get('assetsUrl') . $sp4312bc['menu_logo']); } array_walk($sp4312bc['pages'], function ($sp16c10a, $spb37f21) use($sp4312bc, $sp0bb1ac) { add_submenu_page($sp4312bc['uri'], ucwords($spb37f21), ucwords($spb37f21), 'manage_options', $sp16c10a, array($sp0bb1ac, 'getTpl')); }); }); } public static function isMenuItemExists($spf0d987) { global $menu; $spe5b405 = false; array_walk($menu, function ($sp4312bc) use($spf0d987, &$spe5b405) { if (preg_match('/^' . trim($spf0d987) . '$/i', $sp4312bc[0])) { $spe5b405 = true; } }); return $spe5b405; } protected static function makeTab($sp1c23ac, $spb37f21, $spf39713 = null) { $sp49c648 = $spb37f21 == self::getCurrentPage() ? 'nav-tab-active' : ''; printf('<a class=\'nav-tab %s\' href=\'?page=%s\' title=\'%s\'>%s</a>', $sp49c648, is_null($spb37f21) ? self::getCurrentPage() : $spb37f21, ucfirst($spf39713), ucwords($sp1c23ac)); } public static function getTabs() { $sp0bb1ac = self::get('caller'); array_walk(array_shift($sp0bb1ac::$plugin)['pages'], function ($sp16c10a, $spb37f21) { self::makeTab($spb37f21, $sp16c10a); }); } }