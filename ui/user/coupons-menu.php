<h3>
	<?= __( 'Your coupons', 'affiliated-coupons' ) ?>
	<a class="woocommerce-Button button" href="<?= $coupon_create_link ?>">
		<?= __( 'Create coupon', 'affiliated-coupons' ) ?>
	</a>
</h3>
<div>
	<?php if ( ! count( $coupons ) ) { ?>
		<div
			class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">
			<?= __( 'No coupons found', 'affiliated-coupons' ) ?>.
			<a class="woocommerce-Button button" href="<?= $coupon_create_link ?>">
				<?= __( 'Create one', 'affiliated-coupons' ) ?>!
			</a>
		</div>
	<?php } else { ?>
		<table>
			<tr>
				<th> <?= __( 'code', 'affiliated-coupons' ) ?> </th>
				<th> <?= __( 'percent', 'affiliated-coupons' ) ?> </th>
				<th> <?= __( 'limit', 'affiliated-coupons' ) ?> </th>
				<th> <?= __( 'expire', 'affiliated-coupons' ) ?> </th>
				<th> <?= __( 'remaining', 'affiliated-coupons' ) ?> </th>
				<th> <?= __( 'operation', 'affiliated-coupons' ) ?> </th>
			</tr>
			<?php foreach ( $coupons as $coupon ) {
				$pm = get_post_meta( $coupon->ID ); ?>
				<tr>
					<td><?= $coupon->post_title ?></td>
					<td><?= $pm['coupon_amount'][0] ?></td>
					<td><?= $pm['usage_limit'][0] ?: __( 'unlimited', 'affiliated-coupons' ) ?></td>
					<td><?= $pm['date_expires'][0] ? wp_date( 'Y-m-d H:i:s', $pm['date_expires'][0] ) : __( 'never', 'affiliated-coupons' ); ?></td>
					<td><?= $pm['usage_limit'][0] - $pm['usage_count'][0] ?></td>
					<td><a href="<?= "?remove_coupon=$coupon->ID" ?>"> <?= __( 'remove', 'affiliated-coupons' ) ?> </a></td>
				</tr>
			<?php } ?>
		</table>
	<?php } ?>
</div>