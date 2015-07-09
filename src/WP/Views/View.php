<?php
namespace WP\Views; use Netforum\Traits\SingletonTrait; abstract class View extends Form { use SingletonTrait; protected $group; protected $fields = array('abstract' => array('desc' => 'this is the abstract section.', 'fields' => array('field1' => array('title' => 'abstract field 1', 'desc' => 'you should extend this abstract class and not use it directly.', 'validate' => array('[a-zA-Z0-9_]{5,}', 'must be minimum 5 characters, format (a-z 0-9 _-)'), 'required' => true, 'callback' => null, 'default' => 'some value', 'filter' => 'trim')))); public function __construct() { $this->page = Page::getCurrentPage(); $this->group = snake_case(end(explode('\\', get_called_class()))); if (isset($_POST[$this->page]) && sizeof($_POST[$this->page]) > 0) { !$this->validate() ? $this->flash(true) : $this->store() && $this->flash(); } $this->init(); $this->render(); } protected function init() { register_setting($this->page, $this->group, array($this, 'sanitize')); if (sizeof($this->fields) <= 0) { return false; } array_walk($this->fields, function ($sp55c5a4, $spff115a) { $this->makeSection($spff115a, $sp55c5a4['desc']); if (isset($sp55c5a4['js'])) { Input::handleCallback($sp55c5a4['js']); } array_walk($sp55c5a4['fields'], function ($spac07f4, $sp6b4425) use($spff115a) { $spac07f4 += array('key' => $sp6b4425, 'section' => $spff115a); $this->makeField($sp6b4425, $spac07f4); }); }); } protected function render() { if (sizeof($this->fields) > 0) { settings_fields($this->group); do_settings_sections($this->page); submit_button(); } } protected function makeSection($sp0048ef, $sp610aa0 = null, $spd5d0c9 = null) { if (is_null($spd5d0c9)) { $spd5d0c9 = function () use($sp610aa0) { print $sp610aa0; }; } return add_settings_section($this->toSlug($sp0048ef), ucwords($sp0048ef), $spd5d0c9, $this->page); } protected function makeField($sp48d970, array $sp55c5a4) { if (is_null($sp55c5a4['callback'])) { $sp55c5a4['callback'] = 'textfield'; } if (!is_array($sp55c5a4['callback'])) { $sp55c5a4['callback'] = array(__NAMESPACE__ . '\\Input', $sp55c5a4['callback']); } $sp522919 = $this->toSlug($sp55c5a4['section']); if (sizeof($sp55c5a4['args']) <= 0) { $sp55c5a4['args'] = array('group' => $this->group, 'section' => $sp522919, 'id' => $sp48d970, 'desc' => $sp55c5a4['desc'], 'default' => $sp55c5a4['default'], 'filter' => $sp55c5a4['filter'], 'js' => $sp55c5a4['js']); } return add_settings_field($sp48d970, $sp55c5a4['title'], $sp55c5a4['callback'], $this->page, $sp522919, $sp55c5a4['args']); } protected function toSlug($sp55c5a4, $sp650ba4 = '_') { return preg_replace('/[^\\w]/', $sp650ba4, strtolower($sp55c5a4)); } }