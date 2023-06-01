<?php

function aac_affiliated_menu_endpoint() {
	$user_percent = get_user_meta( get_current_user_id(), 'aac_affiliate_percentage', true );
	if ( $user_percent ) {
		add_filter( 'query_vars', 'aac_affiliated_menu_query_vars', 0 );
		add_filter( 'woocommerce_account_menu_items', 'aac_add_affiliated_menu_link_my_account' );
		add_action( 'woocommerce_account_affiliated-coupons_endpoint', 'aac_affiliated_menu_content' );
		add_action( 'woocommerce_account_affiliated-coupons-create_endpoint', 'aac_affiliated_create_content' );
		add_action( 'woocommerce_account_affiliated-coupons-profit_endpoint', 'aac_affiliated_profit_content' );
		add_action( 'woocommerce_account_affiliated-coupons-withdraw_endpoint', 'aac_affiliated_withdraw_content' );
		add_action( 'woocommerce_account_affiliated-coupons-withdraw-create_endpoint', 'aac_affiliated_withdraw_create_content' );
	}
}

function aac_affiliated_menu_query_vars( $vars ) {
	$vars[] = 'affiliated-coupons';
	$vars[] = 'affiliated-coupons-create';
	$vars[] = 'affiliated-coupons-profit';
	$vars[] = 'affiliated-coupons-withdraw';
	$vars[] = 'affiliated-coupons-withdraw-create';

	return $vars;
}


function aac_add_affiliated_menu_link_my_account( $items ) {
	$items['affiliated-coupons']        = __( 'Your coupons', 'affiliated-coupons' );
	$items['affiliated-coupons-profit'] = __( 'Your affiliation', 'affiliated-coupons' );
	$items['affiliated-coupons-withdraw'] = __( 'Your withdrawals', 'affiliated-coupons' );

	return $items;
}

function aac_affiliated_create_content() {
	$coupon_create_link        = wc_get_account_endpoint_url( 'affiliated-coupons-create' );
	$current_affiliate_percent = current_affiliate_percent();
	wp_register_script( 'aac-datepicker', plugins_url( 'assets/persianDatepicker.min.js', __FILE__ ), array( 'jquery' ), '1.0', true );
	wp_register_style( 'aac-datepicker', plugins_url( 'assets/persianDatepicker-default.css', __FILE__ ), '', '1.0' );
	wp_register_style( 'aac-style', plugins_url( 'assets/style.css', __FILE__ ), '', '1.0' );
	require AFFILIATED_COUPONS_PATH . '/ui/user/new-coupon.php';
}
function aac_affiliated_withdraw_create_content() {
	global $current_user;
	$withdraw_create_link        = wc_get_account_endpoint_url( 'affiliated-coupons-withdraw' );
	$current_affiliate_percent = current_affiliate_percent();
	$user_balance = + get_user_meta( $current_user->id, 'aac_total_profit', true );
	require AFFILIATED_COUPONS_PATH . '/ui/user/new-withdraw.php';
}

function aac_affiliated_profit_content() {
	global $wpdb,$current_user, $aac_table_profit;

	$current_affiliate_percent = current_affiliate_percent();
	$query = "SELECT * FROM $aac_table_profit WHERE affiliate_id = $current_user->id";

	$orders = $wpdb->get_results( $query );
	require AFFILIATED_COUPONS_PATH . '/ui/user/profit-menu.php';
}

function aac_affiliated_menu_content() {
	global $current_user;
	$coupon_create_link = wc_get_account_endpoint_url( 'affiliated-coupons-create' );
	$coupons            = get_posts( [
		'posts_per_page' => - 1,
		'orderby'        => 'name',
		'order'          => 'asc',
		'post_type'      => 'shop_coupon',
		'post_status'    => 'publish',
		'post_author'    => $current_user->ID,
	] );
	require AFFILIATED_COUPONS_PATH . '/ui/user/coupons-menu.php';
}

function aac_affiliated_withdraw_content() {
	global $wpdb, $current_user, $aac_table_payment, $aac_withdraw_status;
	$withdraw_request_link = wc_get_account_endpoint_url( 'affiliated-coupons-withdraw-create' );
	$query = "SELECT * FROM $aac_table_payment WHERE affiliate_id = $current_user->id ORDER BY status, pay_date DESC, id DESC";
	$user_balance = + get_user_meta( $current_user->id, 'aac_total_profit', true );
	$requests = $wpdb->get_results( $query );
	require AFFILIATED_COUPONS_PATH . '/ui/user/withdraw.php';
}
