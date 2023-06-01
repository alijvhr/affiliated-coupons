<h3>
	<?= __( 'Your withdrawals', 'affiliated-coupons' ) ?>
    <a class="woocommerce-Button button" href="<?= $withdraw_request_link ?>">
		<?= __( 'Request now', 'affiliated-coupons' ) ?>
    </a>
</h3>

<p style="font-weight:bold">
	<?= __( 'balance', 'affiliated-coupons' ) ?>
	: <?= wc_price($user_balance) ?>
</p>

<div>
	<?php if ( ! count( $requests ) ) { ?>
		<div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">
			<?= __( 'No withdrawals found', 'affiliated-coupons' ) ?>.
            <a class="woocommerce-Button button" href="<?= $withdraw_request_link ?>">
				<?= __( 'Request now', 'affiliated-coupons' ) ?>!
            </a>
		</div>
	<?php } else { ?>
			<table>
				<tr>
					<th> <?= __( 'id', 'affiliated-coupons' ) ?> </th>
					<th> <?= __( 'date', 'affiliated-coupons' ) ?> </th>
					<th> <?= __( 'total', 'affiliated-coupons' ) ?> </th>
					<th> <?= __( 'status', 'affiliated-coupons' ) ?> </th>
				</tr>
			<?php foreach ( $requests as $request ) { ?>
				<tr>
					<td><?= $request->id ?></td>
					<td><?= $request->pay_date ?></td>
					<td><?= wc_price($request->amount) ?></td>
					<td><?= __($aac_withdraw_status[$request->status], 'affiliated-coupons') ?></td>
				</tr>
			<?php } ?>
			</table>
	<?php } ?>
</div>