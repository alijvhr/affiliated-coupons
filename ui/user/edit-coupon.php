<h3>
	<?= __( 'Edit coupon', 'affiliated-coupons' ) ?>
</h3>

<p style="font-weight:bold">
	<?= __( 'Affiliation percentage', 'affiliated-coupons' ) ?>
	: <?= $current_affiliate_percent ?>%
</p>

<div>
	<form method="post" action="<?= $coupon_edit_link ?>">
		<label>
            <input type="checkbox" name="coupon-enable" value="1">
			<?= __( 'Enable', 'affiliated-coupons' ) ?>
		</label>
		<label>
			<?= __( 'Coupon\'s Code', 'affiliated-coupons' ) ?>
			<span class="text-danger"><?= $aac_new_errors['coupon-code']??'' ?></span>
			<input class="regular-text" type="text" name="coupon-code">
		</label>
		<label>
			<span class="text-danger"><?= $aac_new_errors['coupon-percent']??'' ?></span>
			<?= __( 'Discount Percent', 'affiliated-coupons' ) . ' new-coupon.php' . __( 'max', 'affiliated-coupons' ) . "($current_affiliate_percent%)" ?>
			<input class="regular-text" type="number" name="coupon-percent" min="0"
			       max="<?= $current_affiliate_percent ?>" value="0">
		</label>
		<label>
			<?= __( 'Usage Limit', 'affiliated-coupons' ) ?>
			<input class="regular-text" type="number" name="coupon-limit" min="0" value="0">
		</label>
		<label>
			<?= __( 'Expire Date', 'affiliated-coupons' ) ?>
			<input class="regular-text" type="text" name="coupon-date" id="coupon-date">
		</label>
		<label>
			<input type="hidden" name="coupon-id" value="0">
			<button class="regular-text" type="submit" name="affiliated-coupons">
				<?= __( 'submit', 'affiliated-coupons' ) ?>
			</button>
		</label>
	</form>
</div>

<script type="text/javascript">
    jQuery(function () {
        jQuery('#coupon-date').persianDatepicker();
    });
</script>