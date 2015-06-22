<?php
namespace WP\Views; class Input { public static function textfield($sp1664ef) { return self::field($sp1664ef, $sp126cd0 = 'text'); } public static function passwordfield($sp1664ef) { return self::field($sp1664ef, $sp126cd0 = 'password'); } public static function checkbox($sp1664ef) { return self::field($sp1664ef, $sp126cd0 = 'checkbox', null); } public static function textarea($sp1664ef) { return self::field($sp1664ef, $sp126cd0 = 'textarea', null); } protected static function field($sp1664ef, $sp126cd0 = 'text', $sp11eea7 = 'regular-text') { $spabf606 = $sp1664ef['id']; $sp0e4d4b = $sp1664ef['group']; $sp2fffe5 = $sp1664ef['section']; $sp2e392a = "{$sp0e4d4b}[{$sp2fffe5}][{$spabf606}]"; $sp55e51a = self::makeDefaultValue($sp1664ef); $spa88d13 = ''; if ($sp126cd0 == 'checkbox') { $spa88d13 .= checked(1, (bool) $sp55e51a, false); if (!checked(1, (bool) $sp55e51a, false)) { $sp55e51a = checked(1, $sp55e51a, false) ? 0 : 1; } } if (!is_null($sp1664ef['js'])) { self::addJS($sp1664ef['js'], $sp2e392a, $sp55e51a, $sp1664ef); } $sp339e89 = array('class' => $sp11eea7, 'type' => $sp126cd0, 'name' => $sp2e392a, 'value' => esc_attr($sp55e51a), 'extra' => $spa88d13); switch ($sp126cd0) { case 'textarea': self::itemTextArea($sp339e89); break; default: self::itemTextField($sp339e89); } self::addDescription($sp1664ef['desc']); } private static function itemTextArea(array $sp339e89) { printf('<textarea cols=30 rows=4 class=\'%s\' type=\'%s\' name=\'%s\' %s>%s</textarea>', $sp339e89['class'], $sp339e89['type'], $sp339e89['name'], $sp339e89['extra'], $sp339e89['value']); } private static function itemTextField(array $sp339e89) { printf('<input class=\'%s\' type=\'%s\' name=\'%s\' value=\'%s\' %s />', $sp339e89['class'], $sp339e89['type'], $sp339e89['name'], $sp339e89['value'], $sp339e89['extra']); } private static function addDescription($sp0f46d2) { if (is_array($sp0f46d2)) { printf('<small>&nbsp; %s</small><br><small>%s</small>', array_shift($sp0f46d2), esc_attr(array_pop($sp0f46d2))); } else { printf('<br><small>%s</small>', esc_attr($sp0f46d2)); } } private static function makeDefaultValue(array $sp1664ef) { $sped9fff = $sp1664ef['default']; $sp8607fd = $sp1664ef['filter']; $spa52b12 = self::getOptionValue($sp1664ef); if (is_null($spa52b12) && is_array($sped9fff)) { $sped9fff = self::handleCallback($sped9fff); } if (is_array($sp8607fd)) { $spa52b12 = self::handleCallback($sp8607fd, array(is_null($spa52b12) ? $sped9fff : $spa52b12)); } return is_null($spa52b12) ? $sped9fff : $spa52b12; } public static function handleCallback($spea039a, array $spa52b12 = array()) { if (!is_array($spea039a)) { return $spa52b12; } $sp7d9a1f = array_shift($spea039a); $sp339e89 = is_array(end($spea039a)) ? is_null($spa52b12) ? end($spea039a) : array_merge(end($spea039a), $spa52b12) : $spa52b12; return call_user_func_array($sp7d9a1f, $sp339e89); } private static function getOptionValue(array $sp1664ef) { $spabf606 = $sp1664ef['id']; $sp0e4d4b = $sp1664ef['group']; $sp2fffe5 = $sp1664ef['section']; if (isset($_REQUEST[$sp0e4d4b][$sp2fffe5][$spabf606])) { return $_REQUEST[$sp0e4d4b][$sp2fffe5][$spabf606]; } $spf1298d = get_option($sp0e4d4b); return isset($spf1298d[$spabf606]) && !empty($spf1298d[$spabf606]) ? $spf1298d[$spabf606] : isset($spf1298d[$sp2fffe5][$spabf606]) && !empty($spf1298d[$sp2fffe5][$spabf606]) ? $spf1298d[$sp2fffe5][$spabf606] : null; } public static function addJS($sp777f6d, $spce072b = '', $sp55e51a = '', $sp1664ef = '') { $sp0e4d4b = $sp2fffe5 = null; is_array($sp1664ef) ? extract($sp1664ef) : null; $sp8ef2b2 = function ($spce072b, $sp2d4e0d = '') { return sprintf('$(\'input[name%s="%s"]\')', $sp2d4e0d, $spce072b); }; printf('<script>jQuery(document).ready(function($) { %s });</script>', preg_replace(array('/%field:(.*?)%/i', '/%field/', '/%value/'), array($sp8ef2b2('[$1]', '*'), $sp8ef2b2($spce072b), esc_attr($sp55e51a)), self::compress($sp777f6d))); } protected static function compress($spd69f3b) { $spd69f3b = preg_replace('/((?:\\/\\*(?:[^*]|(?:\\*+[^*\\/]))*\\*+\\/)|(?:\\/\\/.*))/', '', $spd69f3b); $spd69f3b = str_replace(array('
', '
', '  ', '    ', '     '), '', $spd69f3b); $spd69f3b = preg_replace(array('(( )+\\))', '(\\)( )+)'), ')', $spd69f3b); return $spd69f3b; } }