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

global $wpdb, $aac_db_version, $aac_table_profit, $aac_table_payment;
$aac_db_version    = '1.0';
$aac_table_profit  = $wpdb->prefix . 'aac_profit';
$aac_table_payment = $wpdb->prefix . 'aac_payment';
function aac_plugin_load() {
	load_plugin_textdomain( 'affiliated-coupons', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	if ( current_affiliate_percent() ) {
		wp_enqueue_style( 'aac-style' );
		aac_check_form_submit();
		aac_affiliated_menu_endpoint();
	}
}

require 'src/UserEditFields.php';
require 'src/MyAccountMenu.php';
require 'src/functions.php';


//wp_register_script( 'aac-datepicker', plugins_url( 'assets/persianDatepicker.min.js', __FILE__ ), array( 'jquery' ), '1.0', true );
//wp_register_style( 'aac-datepicker', plugins_url( 'assets/persianDatepicker-default.css', __FILE__ ), '', '1.0' );
//wp_register_style( 'aac-style', plugins_url( 'assets/style.css', __FILE__ ), '', '1.0' );

add_action( 'init', 'aac_plugin_load' );

register_activation_hook(
	__FILE__,
	'aac_activation_hook'
);

function aac_activation_hook() {

	global $wpdb, $aac_db_version, $aac_table_profit, $aac_table_payment;

	$charset_collate = $wpdb->get_charset_collate();

	$sql  = "CREATE TABLE IF NOT EXISTS $aac_table_profit ( order_id mediumint(9) NOT NULL, order_date timestamp DEFAULT utc_timestamp NOT NULL, coupon_code varchar(250) NOT NULL, affiliate_id mediumint(9) NOT NULL, order_total decimal NOT NULL, user_discount decimal NOT NULL, profit decimal NOT NULL, PRIMARY KEY(order_id), INDEX(affiliate_id) ) $charset_collate;";
	$sql2 = "CREATE TABLE IF NOT EXISTS $aac_table_payment ( id mediumint(9) NOT NULL AUTO_INCREMENT, pay_date timestamp DEFAULT utc_timestamp NOT NULL, amount decimal NOT NULL, affiliate_id mediumint(9) NOT NULL, PRIMARY KEY(id),  INDEX(affiliate_id) ) $charset_collate;";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );
	dbDelta( $sql2 );

	update_option( 'aac_db_version', $aac_db_version );


	add_rewrite_endpoint( 'affiliated-coupons', EP_ROOT | EP_PAGES );
	add_rewrite_endpoint( 'affiliated-coupons-create', EP_ROOT | EP_PAGES );
	add_rewrite_endpoint( 'affiliated-coupons-profit', EP_ROOT | EP_PAGES );
	add_rewrite_endpoint( 'affiliated-coupons-withdraw', EP_ROOT | EP_PAGES );

	flush_rewrite_rules( true );

}