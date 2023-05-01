<?php

function current_affiliate_percent() {
	return get_user_meta( get_current_user_id(), 'affiliate_percentage', true ) ?: 0;
}

$aac_new_errors = [];

function aac_check_form_submit() {
	if ( isset( $_POST['affiliated-coupons'], $_POST['coupon-id'] ) ) {
		$p      = $_POST;
		$coupon = new WC_Coupon();
		global $aac_new_errors;

		if ( ! preg_match( '/^\w{5,}$/i', $p['coupon-code'] ) ) {
			$aac_new_errors['coupon-code'] = __( 'code should be al least 5 characters long', 'affiliated-coupons' );
		} else {
			$test = new WC_Coupon( $p['coupon-code'] );
			if ( $test->is_valid() ) {
				$aac_new_errors['coupon-code'] = __( 'code is using by another affiliate', 'affiliated-coupons' );
			}
		}
		if ( $p['coupon-percent'] < 0 || $p['coupon-percent'] > current_affiliate_percent() ) {
			$aac_new_errors['coupon-percent'] = __( 'the percent number entered is not valid', 'affiliated-coupons' );
		}
		if ( $p['coupon-limit'] < 0 ) {
			$p['coupon-limit'] = 0;
		}
		if ( count( $aac_new_errors ) ) {
			return;
		}
		$coupon->set_code( $p['coupon-code'] );

		$coupon->set_description( __( 'Affiliated user coupon' ) . " $current_user->ID" );
		$coupon->set_discount_type( 'percent' );
		$coupon->set_amount( $p['coupon-percent'] );
		$coupon->set_usage_limit( $p['coupon-limit'] );
		$coupon->set_date_expires( $p['coupon-date'] );

		if ( $coupon->save() ) {
			wp_redirect( wc_get_account_endpoint_url( 'affiliated-coupons' ) );
			exit;
		}

	}

}