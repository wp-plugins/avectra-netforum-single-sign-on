<?php
namespace NetAuth; class Authenticate { private $ssoToken; public function __construct() { add_filter('authenticate', array($this, 'validate'), 10, 3); } public function validate($spffcd2d, $sp738ed2, $sp379388) { if (empty($sp738ed2) || empty($sp379388)) { return false; } try { $spffcd2d = get_user_by('login', $sp738ed2); $sp566d2e = get_user_meta($spffcd2d->ID, 'netforum', true); if (is_object($spffcd2d) && empty($sp566d2e)) { return false; } $sp6826cb = $this->authenticate($sp738ed2, $sp379388); $sp77ccc0 = array('user_email' => strtolower($sp6826cb->EmailAddress), 'user_login' => strtolower($sp6826cb->EmailAddress), 'first_name' => ucwords($sp6826cb->ind_first_name), 'last_name' => ucwords($sp6826cb->ind_last_name), 'nickname' => ucwords($sp6826cb->ind_first_name) . ' ' . ucwords($sp6826cb->ind_last_name), ''); if (!is_object($spffcd2d)) { if ($spe8397b = $this->sp95e50c($sp6826cb->cst_id, $spffcd2d->ID)) { global $wpdb; $wpdb->update($wpdb->users, array_slice($sp77ccc0, 0, 2), array('ID' => $spe8397b)); $spffcd2d = new \WP_User(wp_update_user(array('ID' => $spe8397b) + $sp77ccc0)); } else { $spffcd2d = new \WP_User(wp_insert_user($sp77ccc0)); } } else { wp_update_user($sp77ccc0 + array('ID' => $spffcd2d->ID)); } $this->setSession($sp6826cb, $spffcd2d); do_action('nf_SyncGroups'); } catch (\Exception $sp55070e) { remove_action('authenticate', 'wp_authenticate_username_password', 20); $spffcd2d = new \WP_Error('denied', __('Uh Oh!<br> ' . $sp55070e->getMessage())); } return $spffcd2d; } protected function setSession($sp6826cb, $spffcd2d) { $sp14344b = array('cst_id' => (int) $sp6826cb->cst_id, 'cst_key' => (string) $sp6826cb->cst_key, 'sso_token' => $this->ssoToken); update_user_meta($spffcd2d->ID, 'netforum', $sp14344b); if (!session_id()) { session_start(); } $_SESSION += array('netforum' => $sp6826cb); } protected function authenticate($sp738ed2, $sp379388) { $sp515b8d = get_option('netforum'); if (!is_array($sp515b8d) || empty($sp515b8d['single_sign_on']['wsdl'])) { throw new \Exception('Something went wrong, netforum xweb credentials not set.'); } $sp172646 = array('debug' => false, 'ttl' => 12, 'timeout' => $sp515b8d['connection']['timeout'], 'wsdl' => $sp515b8d['single_sign_on']['wsdl'], 'username' => $sp515b8d['single_sign_on']['username'], 'password' => $sp515b8d['single_sign_on']['password'], 'credentials' => array('username' => $sp738ed2, 'password' => $sp379388)); if (class_exists('Netforum\\Views\\NetforumCache')) { $sp515b8d = get_option('netforum_cache'); $sp172646 += array('cache' => array('path' => __DIR__ . '/tmp/', 'secret' => $sp515b8d['cache']['key'], 'ttl' => $sp515b8d['cache']['ttl'])); } $sp6826cb = new \Netforum\Providers\ServiceProvider($sp172646); if (property_exists($sp6826cb, 'auth')) { $this->ssoToken = $sp6826cb->auth->getSsoToken(); return $sp6826cb->onDemand->getCustomerByKey(); } else { $this->ssoToken = $sp6826cb->simple->getSsoToken(); return $sp6826cb->simple->getCustomerByKey(); } } private function sp95e50c($sp244044, $sp27d7d8) { global $wpdb; if ($sp244044 <= 0) { return false; } $sp7ba6e5 = $wpdb->get_row(sprintf('select * from %s where meta_value like "%s" limit 1', $wpdb->usermeta, '%\\"cst_id\\";i:' . (int) esc_sql($sp244044) . ';%')); if (!is_object($sp7ba6e5)) { return false; } return $sp27d7d8 != $sp7ba6e5->user_id ? $sp7ba6e5->user_id : false; } }