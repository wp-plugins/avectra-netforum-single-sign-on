<?php
namespace WP\Views; abstract class Form { protected $_f3eebb0ecc44; protected function store() { $sp3e7d1c = $_POST[$this->_ae3d9eac21dc]; array_walk_recursive($sp3e7d1c, array($this, 'sanitize')); return add_option($this->_ae3d9eac21dc, $sp3e7d1c) || update_option($this->_ae3d9eac21dc, $sp3e7d1c); } protected function validate() { if (!isset($_POST[$this->_ae3d9eac21dc])) { return false; } $sp3e7d1c = $_POST[$this->_ae3d9eac21dc]; $this->_f3eebb0ecc44 = new \WP_Error(); foreach ($this->_52c4ce77469c as $spf1c943 => $sp735857) { $spf1c943 = $this->toSlug($spf1c943); foreach ($sp735857['fields'] as $sp1e1581 => $spada5fd) { $sp1e1581 = $this->toSlug($sp1e1581); if (isset($spada5fd['required']) && $spada5fd['required']) { if (!is_null($sp3e7d1c[$spf1c943][$sp1e1581]) && trim($sp3e7d1c[$spf1c943][$sp1e1581]) == '') { $this->_f3eebb0ecc44->add($sp1e1581, __($spada5fd['title'] . ' cannot be left empty.')); continue; } } if (isset($spada5fd['validate']) && !is_null($spada5fd['validate'])) { if (!is_array($spada5fd['validate'])) { continue; } if (!preg_match('/^' . current($spada5fd['validate']) . '$/is', $sp3e7d1c[$spf1c943][$sp1e1581])) { $spd339db = end($spada5fd['validate']) != '' ? end($spada5fd['validate']) : 'must be valid characters.'; $this->_f3eebb0ecc44->add($sp1e1581, __($spada5fd['title'] . ' ' . $spd339db)); continue; } } } } return sizeof($this->_f3eebb0ecc44->get_error_codes()) <= 0; } protected function flash($sp0d9a1f = false, $spd339db = null) { if ($sp0d9a1f) { return printf('<div id="message" class="%s"><p><strong>%s</strong></p><p>%s</p></div>', $sp0d9a1f ? 'error' : 'updated', $sp0d9a1f ? __('Uh oh!') : __('Yay!'), __(implode('<br>', $this->_f3eebb0ecc44->get_error_messages()))); } return printf('<div id="message" class="%s"><p><strong>%s</strong></p><p>%s</p></div>', 'updated', 'Yay!', is_null($spd339db) ? __('Your request has been processed successfully.') : __($spd339db)); } protected function sanitize(&$spd62c57) { return $spd62c57 = trim(stripslashes(sanitize_text_field($spd62c57))); } }