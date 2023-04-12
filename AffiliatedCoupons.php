<?php
/**
 * Plugin Name: Affiliated Coupons
 * Plugin URI: https://github.com/alijvhr/affiliated-coupons
 * Description: This plugin let you provide a coupon creation dashboard to your affiliate with max discount percent of his affiliation percent.
 * Version: 1.0
 * Author: alijvhr
 * Author URI: https://alijvhr.ir/
 * Text Domain: affiliated-coupons
 * Domain Path: /languages
 **/

if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct access allowed' );
}

define( 'AFFILIATED_COUPONS_PATH', __DIR__ );

function aac_load_textdomain() {
	load_plugin_textdomain( 'affiliated-coupons', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

add_action( 'init', 'aac_load_textdomain' );

require 'src/UserEditFields.php';
require 'src/MyAccountMenu.php';