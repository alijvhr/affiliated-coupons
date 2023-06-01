<?php


add_action( 'woocommerce_payment_complete', 'aac_wc_payment_complete_hook', 10, 1 );
add_action( 'woocommerce_order_status_changed', 'aac_wc_payment_complete_hook', 10, 3 );

function current_affiliate_percent( $user_id = 0 ) {
	return get_user_meta( $user_id ?: get_current_user_id(), 'aac_affiliate_percentage', true ) ?: 0;
}

$aac_new_errors = [];

function aac_wc_payment_complete_hook( $order_id, $old_status = 'pending', $new_status = 'processing' ) {
	global $wpdb, $aac_table_profit;
	if ( $old_status == 'pending' && $new_status == 'processing' ) {
		$order        = wc_get_order( $order_id );
		$used_coupons = $order->get_coupon_codes();
		if ( count( $used_coupons ) ) {
			$coupon       = new WC_coupon( $used_coupons[0] );
			$post         = get_post( $coupon->id );
			$affiliate_id = $post->post_author;
			$percent      = current_affiliate_percent( $affiliate_id );
			if ( $percent ) {
				$total         = $order->get_total() - $order->get_total_tax() - $order->get_total_shipping() + $order->get_total_discount();
				$user_discount = $order->get_total_discount();
				$profit        = $total * $percent / 100 - $user_discount;
				$wpdb->insert( $aac_table_profit, [
					'order_id'      => $order_id,
					'coupon_code'   => $used_coupons[0],
					'affiliate_id'  => $affiliate_id,
					'order_total'   => $total,
					'user_discount' => $user_discount,
					'profit'        => $profit
				] );
				$user_balance = + get_user_meta( $affiliate_id, 'aac_total_profit', true );
				update_user_meta( $affiliate_id, 'aac_total_profit', $user_balance + $profit );
			}
		}
	}
}

function aac_check_form_submit() {
	global $current_user, $wpdb, $aac_table_payment;
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
		$coupon->set_individual_use( true );

		if ( $coupon->save() ) {
		}

	} elseif ( isset( $_POST['affiliated-coupons'], $_POST['withdraw-id'] ) ) {
		if($_POST['amount'] < 1) {
			wp_redirect( wc_get_account_endpoint_url( 'affiliated-coupons-withdraw-create' ) );
			exit;
		}
		$wpdb->insert( $aac_table_payment, [
			'affiliate_id' => $current_user->ID,
			'amount'       =>$_POST['amount']
		] );
		$user_balance = + get_user_meta( $current_user->ID, 'aac_total_profit', true );
		update_user_meta( $current_user->ID, 'aac_total_profit', $user_balance - $_POST['amount'] );
		wp_redirect( wc_get_account_endpoint_url( 'affiliated-coupons-withdraw' ) );
		exit;
	}

}