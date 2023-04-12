<?php

add_action( 'init', 'aac_affiliated_menu_endpoint' );
function aac_affiliated_menu_endpoint() {
	add_rewrite_endpoint( 'affiliated-coupons', EP_ROOT | EP_PAGES );
	$user_percent = get_user_meta( get_current_user_id(), 'affiliate_percentage', true );
	if ( $user_percent ) {
		add_filter( 'query_vars', 'aac_affiliated_menu_query_vars', 0 );
		add_filter( 'woocommerce_account_menu_items', 'aac_add_affiliated_menu_link_my_account' );
		add_action( 'woocommerce_account_affiliated-coupons_endpoint', 'aac_affiliated_menu_content' );
	}
}

function aac_affiliated_menu_query_vars( $vars ) {
	$vars[] = 'affiliated-coupons';

	return $vars;
}


function aac_add_affiliated_menu_link_my_account( $items ) {
	$items['affiliated-coupons'] = 'Your Coupons';

	return $items;
}

function aac_affiliated_menu_content() {
	require AFFILIATED_COUPONS_PATH . '/ui/coupons-menu.php';
}
