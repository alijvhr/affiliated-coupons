<?php

add_action( 'edit_user_profile', 'aac_affiliate_percentage' );
add_action( 'show_user_profile', 'aac_affiliate_percentage' );
add_action( 'user_new_form', 'aac_affiliate_percentage' );
function aac_affiliate_percentage( $user ) {
	$affiliate_percentage = get_user_meta( $user->ID, 'affiliate_percentage', true ) ?: 0;
	require AFFILIATED_COUPONS_PATH.'/ui/user-form.php';
}


add_action( 'edit_user_profile_update', 'aac_save_affiliate_percentage' );
add_action( 'personal_options_update', 'aac_save_affiliate_percentage' );
add_action( 'user_register', 'aac_save_affiliate_percentage' );
function aac_save_affiliate_percentage( $user_id ) {
	if ( ! current_user_can( 'edit_user', $user_id ) ) {
		return;
	}
	$custom_data = $_POST['affiliate_percentage'];
	update_user_meta( $user_id, 'affiliate_percentage', $custom_data );
}