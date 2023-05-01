<?php

function aac_affiliated_menu_endpoint() {
	add_rewrite_endpoint( 'affiliated-coupons', EP_ROOT | EP_PAGES );
	add_rewrite_endpoint( 'affiliated-coupons-create', EP_ROOT | EP_PAGES );
	add_rewrite_endpoint( 'affiliated-coupons-profit', EP_ROOT | EP_PAGES );
	$user_percent = get_user_meta( get_current_user_id(), 'affiliate_percentage', true );
	if ( $user_percent ) {
		add_filter( 'query_vars', 'aac_affiliated_menu_query_vars', 0 );
		add_filter( 'woocommerce_account_menu_items', 'aac_add_affiliated_menu_link_my_account' );
		add_action( 'woocommerce_account_affiliated-coupons_endpoint', 'aac_affiliated_menu_content' );
		add_action( 'woocommerce_account_affiliated-coupons-create_endpoint', 'aac_affiliated_create_content' );
		add_action( 'woocommerce_account_affiliated-coupons-profit_endpoint', 'aac_affiliated_profit_content' );
	}
}

function aac_affiliated_menu_query_vars( $vars ) {
	$vars[] = 'affiliated-coupons';
	$vars[] = 'affiliated-coupons-create';
	$vars[] = 'affiliated-coupons-profit';

	return $vars;
}


function aac_add_affiliated_menu_link_my_account( $items ) {
	$items['affiliated-coupons']        = __( 'Your coupons', 'affiliated-coupons' );
	$items['affiliated-coupons-profit'] = __( 'Your affiliation', 'affiliated-coupons' );

	return $items;
}

function aac_affiliated_create_content() {
	$coupon_create_link = wc_get_account_endpoint_url( 'affiliated-coupons-create' );
	$current_affiliate_percent = current_affiliate_percent();
	wp_enqueue_script('aac-datepicker');
	wp_enqueue_style('aac-datepicker');
	require AFFILIATED_COUPONS_PATH . '/ui/new-coupon.php';
}

function aac_affiliated_profit_content() {
	$current_affiliate_percent = current_affiliate_percent();
	require AFFILIATED_COUPONS_PATH . '/ui/profit-menu.php';
}

function aac_affiliated_menu_content() {
	$coupon_create_link = wc_get_account_endpoint_url( 'affiliated-coupons-create' );
	$coupons            = get_posts( [
		'posts_per_page' => - 1,
		'orderby'        => 'name',
		'order'          => 'asc',
		'post_type'      => 'shop_coupon',
		'post_status'    => 'publish',
		'post_author'    => $current_user->ID,
	] );
	require AFFILIATED_COUPONS_PATH . '/ui/coupons-menu.php';
}
