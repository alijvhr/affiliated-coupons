<h3>
	<?= __( 'Affiliation Percentage', 'affiliated-coupons' ) ?>
    : <?= get_user_meta( get_current_user_id(), 'affiliate_percentage', true )?:'0' ?>%
</h3>
<label>
    <input class="regular-text" type="number" name="">
</label>