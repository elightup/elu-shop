<div id="cart"><?= esc_attr__( 'Updating the cart', 'elu-shop' );?></div>

<?php $symbol = ps_setting( 'currency' ); ?>
<script type="text/html" id="tmpl-cart">
	<#
	let total = 0;
	let total_vnd = 0;
	let id = 0;
	if ( data.products.length == 0 ) {
		#>
		<div class="alert"><?= __( 'There are no products in your cart.', 'elu-shop' );?> <a href="<?= home_url( '/' ); ?>"><?php esc_html_e( 'Go to home', 'elu-shop' );?></a></div>
		<#
	} else {
		#>
		<table class="cart cart-checkout table table-bordered">
			<thead class="thead-dark">
			    <tr>
			      <th scope="col"><?= __( '#', 'elu-shop' );?></th>
			      <th scope="col"><?= __( 'Product', 'elu-shop' );?></th>
			      <th scope="col"><?= __( 'Quantity', 'elu-shop' );?></th>
			      <th scope="col"><?= __( 'Price', 'elu-shop' );?></th>
			      <th scope="col"><?= __( 'Total', 'elu-shop' );?></th>
			      <th scope="col"><?= __( 'Delete', 'elu-shop' );?></th>
			    </tr>
		  	</thead>
		  	<tbody>
			<#
			data.products.forEach( product => {
				var subtotal = product.price * product.quantity;
				total += subtotal;
				id += 1;
				#>
				<tr>
					<td class="cart__stt">{{ id }}</td>

					<td class="cart__title">
						<a href="{{ product.link }}"><img src="{{product.url}}" alt="{{product.title}}" />{{ product.title }}</a>
					</td>
					<td class="cart__quantity"><input type="number" value="{{ product.quantity }}" min="1" data-product_id="{{ product.id }}" style="width: 70px;float: initial;margin: auto;text-align: center;"></td>
					<td class="cart__price"><div class="price__coin">{{ format_number(0, 3, '.', ',', parseFloat( product.price )) }} <?= $symbol; ?></div></td>
					<td class="cart__subtotal"><span class="cart__subtotal__number">{{ format_number(0, 3, '.', ',', parseFloat( subtotal )) }}</span> <?= $symbol; ?></td>
					<td class="cart__remove-product"> <button class="cart__remove btn btn-link" data-product_id="{{ product.id }}" title="Xóa sản phẩm này">&times;</button> </td>
				</tr>
				<#
			} );
			#>
			</tbody>
		</table>
		<div class="total-pay-product text-right"><?php esc_html_e( 'Total:', 'elu-shop' ) ?> <span class="total__number">{{ format_number(0, 3, '.', ',', parseFloat( total )) }} <?= $symbol; ?></span>
				</div>
		<div class="submit-cart-shop text-right">
			<button class="place-order btn btn-success"><?= __( 'Checkout', 'elu-shop' );?></button>
		</div>
		<#
	}
	#>
</script>
