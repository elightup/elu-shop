<div id="cart" class="cart--checkout"><?= __( 'Updating the cart', 'elu-shop' );?></div>

<?php
	$symbol = ps_setting( 'currency_symbol' );
?>
<script type="text/html" id="tmpl-cart">
	<#
	let total = 0;
	let total_vnd = 0;
	let stt = 0;
	if ( data.products.length == 0 ) {
		#>
		<div class="alert"><?= __( 'There are no products in your cart.', 'elu-shop' );?> <a href="<?php echo get_option('siteurl'); ?>"><?= __( 'click here', 'elu-shop' );?> </a> <?= __( 'Back home', 'elu-shop' );?></div>
		<#
	} else {
		#>
		<div class="template-checkout">
			<h4 class="pay-title text-center"> <?= __( 'Pay', 'elu-shop' );?> </h4>
			<div class="row">
			<div class="col-lg-4 float-left checkout-info">
				<div class="checkout-title-cart"><?= __( 'Shipment Details', 'elu-shop' );?></div>
				<div class="form-info info-details">
					<div class="form-info__fields form-info__fields__name">
						<p> <?= __( 'Name the recipient', 'elu-shop' );?></p>
						<input class="form-info__name" type="text" name="checkout_info[name]" value="" required>
					</div>
					<div class="form-info__fields">
						<p> <?= __( 'Email', 'elu-shop' );?></p>
						<input class="form-info__email" type="email" name="checkout_info[email]" value="">
					</div>
					<div class="form-info__fields form-info__fields__phone">
						<p> <?= __( 'Phone', 'elu-shop' );?></p>
						<input class="form-info__phone" type="text" name="checkout_info[phone]" value="" required>
					</div>
					<div class="form-info__fields">
						<p><?= __( 'Address', 'elu-shop' );?></p>
						<textarea class="form-info__address" type="text" name="checkout_info[address]"></textarea>
					</div>
				</div>
			</div>
			<div class="col-lg-4 template-checkout__payments float-left">
							<div class="col-lg-12 custom">
					<div class="checkout-title-cart"><?= __( 'Payments', 'elu-shop' );?></div>
					<div class="form-info check-deliverytype form-info--pay">
						<?php 
							$payment_methods = ps_setting( 'payment_methods' );
							$x = 0;
							foreach ( $payment_methods as $key => $field ) {
								$x++;
						?>
						<div class="form-info__fields">
							<label class="form-info__input">
								<input type="radio" name="pay_form_info" value="<?php echo $field['payment_method_title']; ?>">
								<?php echo $field['payment_method_title']; ?>
							</label>
							<div class="radio-info pay-in-cash hidden">
								<?php echo $field['payment_method_description']; ?>
							</div>
						</div>
						<?php
							}
						?>
					</div>
				</div> <!--end col-lg-12 -->
				<div class="col-lg-12 ship">
					<div class="checkout-title-cart"><?= __( 'Form of transportation', 'elu-shop' );?></div>
					<div class="form-info check-deliverytype form-info--ship">
						<?php 
							$shipping_method = ps_setting( 'shipping_method' );
							$x = 0;

							foreach ($shipping_method as $key ) {
								$x++;
						?>
						<div class="form-info__fields">
							<label class="form-info__input">
								<input type="radio" name="checkout_info" data-check="ship-agree" value="<?php echo $key; ?>"><?php echo $key; ?>
							</label>
						</div>
						<?php
							}
						?>
		
					</div>
				</div> <!--end col-lg-12 -->
			</div>
			<div class="col-lg-4 template-checkout__cart float-left">
				<table class="cart table">
					<thead class="thead-dark">
					    <tr>
					      <th scope="col"><?= __( 'STT', 'elu-shop' );?></th>
					      <th scope="col"><?= __( 'Product name', 'elu-shop' );?></th>
					      <th scope="col"><?= __( 'Amount', 'elu-shop' );?></th>
					      <th scope="col"><?= __( 'Into money', 'elu-shop' );?></th>
					    </tr>
				  	</thead>
					<#
					data.products.forEach( product => {
						var subtotal = product.price * product.quantity;
						var subtotal_vnd = product.price_vnd * product.quantity;
						total += subtotal;
						stt += 1;
						#>
						<tr>
							<td class="cart__stt">{{ stt }}</td>

							<td class="cart__title">
								<div class="pull-left">
									<a href="{{ product.link }}">{{ product.title }}</a>
								</div>
							</td>
							<td class="cart__quantity"><input type="number" value="{{ product.quantity }}" min="1" data-product_id="{{ product.id }}" style="width: 70px;text-align: center;"></td>
							<td class="cart__subtotal"><span class="cart__subtotal__number">{{ parseFloat( subtotal ).format(0, 3, '.', ',') }}</span> <?= $symbol; ?></td>
						</tr>
						<#
					} );
					#>
				</table>
				<div class="total"><?= __( 'Total', 'elu-shop' );?>: <span class="total__number">{{ parseFloat( total ).format(0, 3, '.', ',') }}</span> <?= $symbol; ?></div>
				<p class="order-note">
					<label for="order-note"><?= __( 'Note when ordering', 'elu-shop' );?></label>
					<textarea id="order-note"></textarea>
				</p>
				<button class="place-checkout btn-success pay-coins"><?= __( 'Buy now', 'elu-shop' );?></button>
			</div>
		</div>
		</div>
	<#
		}
	#>
</script>
