<?php
namespace NetAuth\Views; use WP\Views\View; use WP\Views\Page; class NetforumHelp extends View { protected $fields = array(); public function __construct() { $this->spa10a68(); } private function spa10a68() { include_once Page::getTemplatesPath(__DIR__) . '/help.tpl'; } }