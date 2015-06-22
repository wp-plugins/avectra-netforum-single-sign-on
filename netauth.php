<?php

/**
 * Plugin Name: fusionSpan | netFORUM Single Sign On
 * Plugin URI: http://fusionspan.com/
 * Description: Authenticate users to sign in using Avectra netFORUM credentials via xWeb.
 * Version: 0.4-dev
 * Author: fusionSpan LLC.
 * Author URI: http://fusionspan.com/
 * License: GPLv3
 */
//require_once(__DIR__ . '/../../composer/autoload.php');
require 'src/helpers.php';
registerAutoloader();

// If this file is called directly, abort.
if ( !defined('WPINC') ) {
    die;
}

// Views on admin.
if ( is_admin() ) {
    new \NetAuth\Views\Render();
}

// General.
new \NetAuth\Authenticate();
new \NetAuth\RestrictPassword();
new \NetAuth\RemoveAdminBar();