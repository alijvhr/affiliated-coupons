<?php
/**
 * Plugin Name: Affiliated coupons
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

function aac_plugin_load() {
	load_plugin_textdomain( 'affiliated-coupons', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	if ( current_affiliate_percent() ) {
		wp_enqueue_style('aac-style');
		aac_check_form_submit();
		aac_affiliated_menu_endpoint();
	}

}

require 'src/UserEditFields.php';
require 'src/MyAccountMenu.php';
require 'src/functions.php';


wp_register_script( 'aac-datepicker', plugins_url( 'assets/persianDatepicker.min.js', __FILE__ ), array( 'jquery' ), '1.0', true );
wp_register_style( 'aac-datepicker', plugins_url( 'assets/persianDatepicker-default.css', __FILE__ ), '', '1.0' );
wp_register_style( 'aac-style', plugins_url( 'assets/style.css', __FILE__ ), '', '1.0' );

add_action( 'init', 'aac_plugin_load' );

register_activation_hook(
	__FILE__,
	'aac_activation_hook'
);

function aac_activation_hook() {
	flush_rewrite_rules( true );
}