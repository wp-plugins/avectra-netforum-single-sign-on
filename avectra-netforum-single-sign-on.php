<?php

/**
 * Plugin Name: fusionSpan | netFORUM Single Sign On
 * Plugin URI: http://fusionspan.com/
 * Description: Authenticate users to sign in using Avectra netFORUM credentials via xWeb.
 * Version: 0.5-dev
 * Author: fusionSpan LLC.
 * Author URI: http://fusionspan.com/
 * License: GPLv3
 */
require 'src/helpers.php'; registerAutoloader(); if (!defined('WPINC')) { die; } if (is_admin()) { new \NetAuth\Views\Render(); } new \NetAuth\Authenticate(); new \NetAuth\RestrictPassword(); new \NetAuth\RemoveAdminBar();