<?php
global $wpdb;
$id   = intval( $_GET['id'] );
$item = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->orders WHERE `id`=%d", $id ) );
$info = json_decode( $item->info, true );
?>

<?php if ( isset( $_GET['type'] ) && 'checkout' === $_GET['type'] ) : ?>
	<div class="alert alert-info"><?= __( 'Thank you for your order. Your order is currently being processed. Below is the order information. We will contact you as soon as possible!', 'elu-shop' ); ?></div>
<?php endif; ?>
<div class="info-order text-center"> 
	<?= __( 'Orders information', 'elu-shop' ); ?>
</div>
<div class="detail-order">
	<div class="line-items float-left col-lg-6">
		<h4><?= __( 'Order item', 'elu-shop' ); ?> #<?= $id; ?></h4>
		<table class="order table">
			<tr>
				<th><?= __( 'Time Order', 'elu-shop' ); ?>:</th>
				<td><?= $item->date; ?></td>
			</tr>
			<tr>
				<th><?= __( 'Status', 'elu-shop' ); ?>:</th>
				<td>
					<?php
					$statuses = [
						'pending' => [ 'badge', __( 'Processing', 'elu-shop' ) ],
						'closed'  => [ 'badge badge--success', __( 'Completed', 'elu-shop' ) ],
						'trash'   => [ 'badge badge--danger', __( 'Deleted', 'elu-shop' ) ],
					];
					$status   = $statuses[ $item->status ];
					printf( '<span class="%s">%s</span>', $status[0], $status[1] );
					?>
				</td>
			</tr>
			<tr>
				<th><?= __( 'Form of transportation', 'elu-shop' ); ?></th>
				<td><?= $info['delivery']; ?></td>
			</tr>
			<tr>
				<th><?= __( 'Total money', 'elu-shop' ); ?>:</th>
				<td><?= number_format( $item->amount, 0, '', '.' ); ?> <?= ps_setting( 'currency_symbol' ); ?></td>
			</tr>
		</table>
	</div>
	<div class="customer-details float-left col-lg-6 ">
		<h4><?= __( 'Customer information', 'elu-shop' ); ?></h4>
		<table class="customer table">
			<tr>
				<th><?= __( 'Customer', 'elu-shop' ); ?></th>
				<td><?= $info['name']; ?></td>
			</tr>
			<tr>
				<th><?= __( 'Email', 'elu-shop' ); ?>:</th>
				<td><?= $info['email']; ?></td>
			</tr>
			<tr>
				<th><?= __( 'Phone', 'elu-shop' ); ?>:</th>
				<td><?= $info['phone']; ?></td>
			</tr>
			<tr>
				<th><?= __( 'Address', 'elu-shop' ); ?>:</th>
				<td><?= $info['address']; ?></td>
			</tr>
		</table>
	</div>
</div>
<div class="order-list col-lg-12 clear">
<h4><?= __( 'Products purchased', 'elu-shop' ); ?></h4>
	<table class="order-products table">
		<thead>
		<tr>
			<th><?= __( 'Products name', 'elu-shop' ); ?></th>
			<th><?= __( 'Amount', 'elu-shop' ); ?></th>
			<th><?= __( 'Price', 'elu-shop' ); ?></th>
			<th><?= __( 'Total money', 'elu-shop' ); ?></th>
		</tr>
		</thead>
		<tbody>
		<?php
		$products = json_decode( $item->data, true );
		foreach ( $products as $product ) :
			?>
			<tr>
				<td><?= $product['title']; ?></td>
				<td><?= $product['quantity']; ?></td>
				<td><?= number_format( $product['price'], 0, '', '.' ); ?> <?= ps_setting( 'currency_symbol' ); ?></td>
				<td><?= number_format( $product['quantity'] * $product['price'], 0, '', '.' ); ?> <?= ps_setting( 'currency_symbol' ); ?></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>