<h3>
	<?= __( 'Your affiliation', 'affiliated-coupons' ) ?>
</h3>

<p style="font-weight:bold">
	<?= __( 'Affiliation percentage', 'affiliated-coupons' ) ?>
	: <?= $current_affiliate_percent ?>%
</p>

<div>
	<?php if ( ! count( $affiliations ) ) { ?>
		<div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">
			<?= __( 'No invoice found', 'affiliated-coupons' ) ?>.
			<a class="woocommerce-Button button" href="https://vegankadeh.ir/my-account/create-coupon">
				<?= __( 'Create one', 'affiliated-coupons' ) ?>!
			</a>
		</div>
	<?php } else { ?>
		<?php foreach ( $affiliations as $affiliation ) { ?>
			<table>
				<tr>
					<th> <?= __( 'code', 'affiliated-coupons' ) ?> </th>
					<th> <?= __( 'percent', 'affiliated-coupons' ) ?> </th>
					<th> <?= __( 'limit', 'affiliated-coupons' ) ?> </th>
					<th> <?= __( 'expire', 'affiliated-coupons' ) ?> </th>
					<th> <?= __( 'remaining', 'affiliated-coupons' ) ?> </th>
				</tr>
				<tr>
					<th><?php $affiliation['post_title'] ?></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
				</tr>
			</table>
		<?php } ?>
	<?php } ?>
</div>