<h3>
	<?= __( 'Your affiliation', 'affiliated-coupons' ) ?>
</h3>

<p style="font-weight:bold">
	<?= __( 'Affiliation percentage', 'affiliated-coupons' ) ?>
	: <?= $current_affiliate_percent ?>%
</p>

<div>
	<?php if ( ! count( $orders ) ) { ?>
		<div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">
			<?= __( 'No invoice found', 'affiliated-coupons' ) ?>.
			<a class="woocommerce-Button button" href="https://vegankadeh.ir/my-account/create-coupon">
				<?= __( 'Create one', 'affiliated-coupons' ) ?>!
			</a>
		</div>
	<?php } else { ?>
			<table>
				<tr>
					<th> <?= __( 'id', 'affiliated-coupons' ) ?> </th>
					<th> <?= __( 'code', 'affiliated-coupons' ) ?> </th>
					<th> <?= __( 'total', 'affiliated-coupons' ) ?> </th>
					<th> <?= __( 'discount', 'affiliated-coupons' ) ?> </th>
					<th> <?= __( 'your share', 'affiliated-coupons' ) ?> </th>
					<th> <?= __( 'date', 'affiliated-coupons' ) ?> </th>
				</tr>
			<?php foreach ( $orders as $order ) { ?>
				<tr>
					<th><?= $order->order_id ?></th>
					<th><?= $order->coupon_code ?></th>
					<th><?= wc_price($order->order_total) ?></th>
					<th><?= wc_price($order->user_discount) ?></th>
					<th><?= wc_price($order->profit) ?></th>
					<th><?= get_date_from_gmt($order->order_date) ?></th>
					<th></th>
				</tr>
			<?php } ?>
			</table>
	<?php } ?>
</div>