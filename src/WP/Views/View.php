<?php
namespace WP\Views; use Netforum\Traits\SingletonTrait; abstract class View extends Form { use SingletonTrait; protected $group; protected $fields = array('abstract' => array('desc' => 'this is the abstract section.', 'fields' => array('field1' => array('title' => 'abstract field 1', 'desc' => 'you should extend this abstract class and not use it directly.', 'validate' => array('[a-zA-Z0-9_]{5,}', 'must be minimum 5 characters, format (a-z 0-9 _-)'), 'required' => true, 'callback' => null, 'default' => 'some value', 'filter' => 'trim')))); public function __construct() { $this->page = Page::getCurrentPage(); $this->group = snake_case(end(explode('\\', get_called_class()))); if (isset($_POST[$this->page]) && sizeof($_POST[$this->page]) > 0) { !$this->validate() ? $this->flash(true) : $this->store() && $this->flash(); } $this->init(); $this->render(); } protected function init() { register_setting($this->page, $this->group, array($this, 'sanitize')); if (sizeof($this->fields) <= 0) { return false; } array_walk($this->fields, function ($sp89316e, $spce072b) { $this->makeSection($spce072b, $sp89316e['desc']); if (isset($sp89316e['js'])) { Input::handleCallback($sp89316e['js']); } array_walk($sp89316e['fields'], function ($spd94985, $sp417d14) use($spce072b) { $spd94985 += array('key' => $sp417d14, 'section' => $spce072b); $this->makeField($sp417d14, $spd94985); }); }); } protected function render() { if (sizeof($this->fields) > 0) { settings_fields($this->group); do_settings_sections($this->page); submit_button(); } } protected function makeSection($spfcf36c, $sp0f46d2 = null, $sp99390d = null) { if (is_null($sp99390d)) { $sp99390d = function () use($sp0f46d2) { print $sp0f46d2; }; } return add_settings_section($this->toSlug($spfcf36c), ucwords($spfcf36c), $sp99390d, $this->page); } protected function makeField($spabf606, array $sp89316e) { if (is_null($sp89316e['callback'])) { $sp89316e['callback'] = 'textfield'; } if (!is_array($sp89316e['callback'])) { $sp89316e['callback'] = array(__NAMESPACE__ . '\\Input', $sp89316e['callback']); } $sp2fffe5 = $this->toSlug($sp89316e['section']); if (sizeof($sp89316e['args']) <= 0) { $sp89316e['args'] = array('group' => $this->group, 'section' => $sp2fffe5, 'id' => $spabf606, 'desc' => $sp89316e['desc'], 'default' => $sp89316e['default'], 'filter' => $sp89316e['filter'], 'js' => $sp89316e['js']); } return add_settings_field($spabf606, $sp89316e['title'], $sp89316e['callback'], $this->page, $sp2fffe5, $sp89316e['args']); } protected function toSlug($sp89316e, $sp730d31 = '_') { return preg_replace('/[^\\w]/', $sp730d31, strtolower($sp89316e)); } }