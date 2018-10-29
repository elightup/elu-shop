<div id="cart"><?= esc_attr__( 'Updating the cart', 'elu-shop' );?></div>

<?php $symbol = ps_setting( 'currency_symbol' ); ?>
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
		<table class="cart cart-checkout table table-bordered">
			<thead class="thead-dark">
			    <tr>
			      <th scope="col"><?= __( 'STT', 'elu-shop' );?></th>
			      <th scope="col"><?= __( 'Product name', 'elu-shop' );?></th>
			      <th scope="col"><?= __( 'Amount', 'elu-shop' );?></th>
			      <th scope="col"><?= __( 'Price', 'elu-shop' );?></th>
			      <th scope="col"><?= __( 'Into money', 'elu-shop' );?></th>
			      <th scope="col"><?= __( 'Delete', 'elu-shop' );?></th>
			    </tr>
		  	</thead>
		  	<tbody>
			<#
			data.products.forEach( product => {
				var subtotal = product.price * product.quantity;
				var subtotal_vnd = product.price_vnd * product.quantity;
				total += subtotal;
				total_vnd += subtotal_vnd;
				stt += 1;
				subtotal_vnd = parseFloat( subtotal_vnd ).format(0, 3, '.', ',');
				#>
				<tr>
					<td class="cart__stt">{{ stt }}</td>

					<td class="cart__title">
						<a href="{{ product.link }}"><img src="{{product.url}}" alt="{{product.title}}" />{{ product.title }}</a>
					</td>
					<td class="cart__quantity"><input type="number" value="{{ product.quantity }}" min="1" data-product_id="{{ product.id }}" style="width: 70px;float: initial;margin: auto;text-align: center;"></td>
					<td class="cart__price"><div class="price__coin">{{ parseFloat( product.price ).format(0, 3, '.', ',') }} <?= $symbol; ?></div></td>
					<td class="cart__subtotal"><span class="cart__subtotal__number">{{ parseFloat( subtotal ).format(0, 3, '.', ',') }}</span> <?= $symbol; ?></td>
					<td class="cart__remove-product"> <button class="cart__remove btn btn-link" data-product_id="{{ product.id }}" title="Xóa sản phẩm này">&times;</button> </td>
				</tr>
				<#
			} );
			#>
			</tbody>
		</table>
		<div class="total-pay-product text-right">Tổng: <span class="total__number">{{ parseFloat( total ).format(0, 3, '.', ',') }} <?= $symbol; ?></span> 
				</div>
		<div class="submit-cart-shop text-right"> 
			<a class="btn btn-primary back-home" href="<?php echo get_option('siteurl'); ?>"> <?= __( 'Back home', 'elu-shop' );?></a>
			<button class="place-order btn btn-success"><?= __( 'Pay', 'elu-shop' );?></button>
			
		</div>
		<#
	}
	#>
</script>
