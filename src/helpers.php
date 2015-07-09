<?php
if (!function_exists('http_build_url')) { define('HTTP_URL_REPLACE', 1); define('HTTP_URL_JOIN_PATH', 2); define('HTTP_URL_JOIN_QUERY', 4); define('HTTP_URL_STRIP_USER', 8); define('HTTP_URL_STRIP_PASS', 16); define('HTTP_URL_STRIP_AUTH', 32); define('HTTP_URL_STRIP_PORT', 64); define('HTTP_URL_STRIP_PATH', 128); define('HTTP_URL_STRIP_QUERY', 256); define('HTTP_URL_STRIP_FRAGMENT', 512); define('HTTP_URL_STRIP_ALL', 1024); function http_build_url($sp359be6, $sp9e887c = array(), $spe74025 = HTTP_URL_REPLACE, &$sp4bc2b1 = false) { $spa43e29 = array('user', 'pass', 'port', 'path', 'query', 'fragment'); if ($spe74025 & HTTP_URL_STRIP_ALL) { $spe74025 |= HTTP_URL_STRIP_USER; $spe74025 |= HTTP_URL_STRIP_PASS; $spe74025 |= HTTP_URL_STRIP_PORT; $spe74025 |= HTTP_URL_STRIP_PATH; $spe74025 |= HTTP_URL_STRIP_QUERY; $spe74025 |= HTTP_URL_STRIP_FRAGMENT; } else { if ($spe74025 & HTTP_URL_STRIP_AUTH) { $spe74025 |= HTTP_URL_STRIP_USER; $spe74025 |= HTTP_URL_STRIP_PASS; } } $sp6a79fc = parse_url($sp359be6); if (isset($sp9e887c['scheme'])) { $sp6a79fc['scheme'] = $sp9e887c['scheme']; } if (isset($sp9e887c['host'])) { $sp6a79fc['host'] = $sp9e887c['host']; } if ($spe74025 & HTTP_URL_REPLACE) { foreach ($spa43e29 as $spff115a) { if (isset($sp9e887c[$spff115a])) { $sp6a79fc[$spff115a] = $sp9e887c[$spff115a]; } } } else { if (isset($sp9e887c['path']) && $spe74025 & HTTP_URL_JOIN_PATH) { if (isset($sp6a79fc['path'])) { $sp6a79fc['path'] = rtrim(str_replace(basename($sp6a79fc['path']), '', $sp6a79fc['path']), '/') . '/' . ltrim($sp9e887c['path'], '/'); } else { $sp6a79fc['path'] = $sp9e887c['path']; } } if (isset($sp9e887c['query']) && $spe74025 & HTTP_URL_JOIN_QUERY) { if (isset($sp6a79fc['query'])) { $sp6a79fc['query'] .= '&' . $sp9e887c['query']; } else { $sp6a79fc['query'] = $sp9e887c['query']; } } } foreach ($spa43e29 as $spff115a) { if ($spe74025 & (int) constant('HTTP_URL_STRIP_' . strtoupper($spff115a))) { unset($sp6a79fc[$spff115a]); } } $sp4bc2b1 = $sp6a79fc; return (isset($sp6a79fc['scheme']) ? $sp6a79fc['scheme'] . '://' : '') . (isset($sp6a79fc['user']) ? $sp6a79fc['user'] . (isset($sp6a79fc['pass']) ? ':' . $sp6a79fc['pass'] : '') . '@' : '') . (isset($sp6a79fc['host']) ? $sp6a79fc['host'] : '') . (isset($sp6a79fc['port']) ? ':' . $sp6a79fc['port'] : '') . (isset($sp6a79fc['path']) ? $sp6a79fc['path'] : '') . (isset($sp6a79fc['query']) ? '?' . $sp6a79fc['query'] : '') . (isset($sp6a79fc['fragment']) ? '#' . $sp6a79fc['fragment'] : ''); } } if (!function_exists('colorize')) { function colorize($sp705dc7, $sp12d101 = 'blue') { $sp060e61 = ''; switch ($sp12d101) { case 'green': case 'SUCCESS': $sp060e61 = '[42m'; break; case 'red': case 'FAILURE': $sp060e61 = '[41m'; break; case 'yellow': case 'WARNING': $sp060e61 = '[43m'; break; case 'blue': case 'NOTE': $sp060e61 = '[44m'; break; default: throw new Exception('Invalid status: ' . $sp12d101); } return chr(27) . "{$sp060e61}" . "{$sp705dc7}" . chr(27) . '[0m'; } } if (!function_exists('prettyXML')) { function prettyXML($spa9e430) { if (!$spa9e430 || !class_exists('DomDocument')) { return $spa9e430; } $sp164b8b = new \DomDocument('1.0'); $sp164b8b->preserveWhiteSpace = false; $sp164b8b->formatOutput = true; $sp164b8b->loadXML($spa9e430); return $sp164b8b->saveXML(); } } if (!function_exists('autoload_psr4')) { function autoload_psr4($spca15d6) { $spd78d28 = $sp2b3ccb = ''; $sp93eb9b = array('.php', '.class.php', '.inc'); $sp5be249 = dirname(__FILE__); if (false !== ($sp14b5e3 = strripos($spca15d6, '\\'))) { $spd78d28 = substr($spca15d6, 0, $sp14b5e3); $spca15d6 = substr($spca15d6, $sp14b5e3 + 1); $sp2b3ccb = str_replace('\\', DIRECTORY_SEPARATOR, $spd78d28) . DIRECTORY_SEPARATOR; } $sp2b3ccb .= str_replace('_', DIRECTORY_SEPARATOR, $spca15d6); $sp915217 = $sp5be249 . DIRECTORY_SEPARATOR . $sp2b3ccb; foreach ($sp93eb9b as $sp02c876) { if (file_exists($sp915217 . $sp02c876)) { require_once $sp915217 . $sp02c876; } } } } if (!function_exists('autoload_psr0')) { function autoload_psr0($spca15d6) { $sp93eb9b = array('.php', '.class.php', '.inc'); $sp2c5b35 = str_replace(__NAMESPACE__ . '\\', '', __CLASS__); $spe0279f = realpath(__DIR__) . DIRECTORY_SEPARATOR; if (substr($spe0279f, -strlen($sp2c5b35)) === $sp2c5b35) { $spe0279f = substr($spe0279f, 0, -strlen($sp2c5b35)); } $spca15d6 = ltrim($spca15d6, '\\'); $sp2b3ccb = $spe0279f; $spd78d28 = ''; if ($sp14b5e3 = strripos($spca15d6, '\\')) { $spd78d28 = substr($spca15d6, 0, $sp14b5e3); $spca15d6 = substr($spca15d6, $sp14b5e3 + 1); $sp2b3ccb .= str_replace('\\', DIRECTORY_SEPARATOR, $spd78d28) . DIRECTORY_SEPARATOR; } $sp2b3ccb .= str_replace('_', DIRECTORY_SEPARATOR, $spca15d6); foreach ($sp93eb9b as $sp02c876) { if (file_exists($sp2b3ccb . $sp02c876)) { require_once $sp2b3ccb . $sp02c876; } } } } if (!function_exists('registerAutoloader')) { function registerAutoloader($sp37292e = 'psr4') { spl_autoload_register('autoload_' . $sp37292e); } } if (!function_exists('dd')) { function dd($sp55c5a4, $sp4f66fb = false) { $sp735246 = php_sapi_name() == 'cli' ? '
' : '<br>
'; if (is_string($sp55c5a4)) { echo $sp55c5a4 . $sp735246; if ($sp4f66fb) { die; } return; } if (php_sapi_name() == 'cli') { print_r($sp55c5a4); } else { echo '<pre>'; print_r($sp55c5a4); echo '</pre>'; } if ($sp4f66fb) { die; } } } if (!function_exists('printfa')) { function printfa($sp250bcb, $sp8b5f87) { return call_user_func_array('printf', array_merge((array) $sp250bcb, $sp8b5f87)); } } if (!function_exists('camel_case')) { function camel_case($sp2c425c) { $spb375be = array(); if (isset($spb375be[$sp2c425c])) { return $spb375be[$sp2c425c]; } return $spb375be[$sp2c425c] = lcfirst(studly($sp2c425c)); } } if (!function_exists('studly')) { function studly($sp2c425c) { $sp559ffb = array(); $spff115a = $sp2c425c; if (isset($sp559ffb[$spff115a])) { return $sp559ffb[$spff115a]; } $sp2c425c = ucwords(str_replace(array('-', '_'), ' ', $sp2c425c)); return $sp559ffb[$spff115a] = str_replace(' ', '', $sp2c425c); } } if (!function_exists('snake_case')) { function snake_case($sp2c425c, $spaba780 = '_') { $speea85c = array(); $spff115a = $sp2c425c . $spaba780; if (isset($speea85c[$spff115a])) { return $speea85c[$spff115a]; } if (!ctype_lower($sp2c425c)) { $sp2c425c = strtolower(preg_replace('/(.)(?=[A-Z])/', '$1' . $spaba780, $sp2c425c)); } return $speea85c[$spff115a] = $sp2c425c; } } if (!function_exists('starts_with')) { function starts_with($sp58a2fc, $spf78a5a) { foreach ((array) $spf78a5a as $spb48ff5) { if ($spb48ff5 != '' && strpos($sp58a2fc, $spb48ff5) === 0) { return true; } } return false; } } if (!function_exists('contains')) { function contains($sp58a2fc, $spf78a5a) { foreach ((array) $spf78a5a as $spb48ff5) { if ($spb48ff5 != '' && strpos($sp58a2fc, $spb48ff5) !== false) { return true; } } return false; } } if (!function_exists('ends_with')) { function ends_with($sp58a2fc, $spf78a5a) { foreach ((array) $spf78a5a as $spb48ff5) { if ((string) $spb48ff5 === substr($sp58a2fc, -strlen($spb48ff5))) { return true; } } return false; } } if (!function_exists('finish')) { function finish($sp2c425c, $spc38d7f) { $spae5850 = preg_quote($spc38d7f, '/'); return preg_replace('/(?:' . $spae5850 . ')+$/', '', $sp2c425c) . $spc38d7f; } } if (!function_exists('is')) { function is($sp3af031, $sp2c425c) { if ($sp3af031 == $sp2c425c) { return true; } $sp3af031 = preg_quote($sp3af031, '#'); $sp3af031 = str_replace('\\*', '.*', $sp3af031) . '\\z'; return (bool) preg_match('#^' . $sp3af031 . '#', $sp2c425c); } }