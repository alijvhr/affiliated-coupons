<h3>
	<?= __( 'Withdraw request', 'affiliated-coupons' ) ?>
</h3>

<p style="font-weight:bold">
	<?= __( 'balance', 'affiliated-coupons' ) ?>
    : <?= wc_price( $user_balance ) ?>
</p>


<div>
    <form method="post" action="<?= $withdraw_create_link ?>">
        <label>
			<?= __( 'amount', 'affiliated-coupons' ) . ' new-withdraw.php' . __( 'max', 'affiliated-coupons' ) . "(" . wc_price( $user_balance ) . ")" ?>
            <input class="regular-text" type="text" name="amount" id="withdraw-amount">
        </label>
        <label>
            <input type="hidden" name="withdraw-id" value="0">
            <button class="regular-text" type="submit" name="affiliated-coupons">
				<?= __( 'submit', 'affiliated-coupons' ) ?>
            </button>
        </label>
    </form>
</div>