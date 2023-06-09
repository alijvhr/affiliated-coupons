<?php

add_action( 'edit_user_profile', 'aac_affiliate_percentage' );
add_action( 'show_user_profile', 'aac_affiliate_percentage' );
add_action( 'user_new_form', 'aac_affiliate_percentage' );
add_action( 'edit_user_profile_update', 'aac_save_affiliate_percentage' );
add_action( 'personal_options_update', 'aac_save_affiliate_percentage' );
add_action( 'user_register', 'aac_save_affiliate_percentage' );
add_action('admin_menu', 'aac_admin_menus');
function aac_affiliate_percentage( $user ) {
	$affiliate_percentage = get_user_meta( $user->ID, 'aac_affiliate_percentage', true ) ?: 0;
	require AFFILIATED_COUPONS_PATH . '/ui/admin/user-form.php';
}
function aac_save_affiliate_percentage( $user_id ) {
	if ( ! current_user_can( 'edit_user', $user_id ) ) {
		return;
	}
	$custom_data = $_POST['aac_affiliate_percentage'];
	if ( ! add_user_meta( $user_id, 'aac_affiliate_percentage', $custom_data, true ) ) {
		update_user_meta( $user_id, 'aac_affiliate_percentage', $custom_data );
	} else {
		add_user_meta( $user_id, 'aac_aac_total_profit', 0, true );
		add_user_meta( $user_id, 'aac_aac_total_paid', 0, true );
	}
}

// Hook for adding admin menus

// action function for above hook
function aac_admin_menus() {
	global $aac_icon;
	add_menu_page(__('Affiliated coupons','affiliated-coupons'), __('Affiliated coupons','affiliated-coupons'), 'manage_options', 'aac-admin-menu', 'aac_admin_menu_content' ,$aac_icon,2);
	add_submenu_page('aac-admin-menu', __('affiliates','affiliated-coupons'), __('affiliates','affiliated-coupons'), 'manage_options', 'aac-admin-affiliates-menu', 'aac_admin_affiliates_menu_content');
	add_submenu_page('aac-admin-menu', __('withdrawals','affiliated-coupons'), __('withdrawals','affiliated-coupons'), 'manage_options', 'aac-admin-withdrawals-menu', 'aac_admin_withdrawals_menu_content');
}

function aac_admin_menu_content() {
	require AFFILIATED_COUPONS_PATH . '/src/Coupons.php';
	$table = new Coupons();
	$table->prepare_items();
	require AFFILIATED_COUPONS_PATH . '/ui/admin/main-menu.php';
}

function aac_admin_affiliates_menu_content() {
	require AFFILIATED_COUPONS_PATH . '/src/Affiliates.php';
	$table = new Affiliates();
	$table->prepare_items();
	require AFFILIATED_COUPONS_PATH . '/ui/admin/affiliates-menu.php';
}

function aac_admin_withdrawals_menu_content() {
	require AFFILIATED_COUPONS_PATH . '/src/Withdrawals.php';
	$table = new Withdrawals();
	$table->prepare_items();
	require AFFILIATED_COUPONS_PATH . '/ui/admin/payments-menu.php';
}
