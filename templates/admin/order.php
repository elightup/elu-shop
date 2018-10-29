<?php
global $wpdb;
$id   = intval( $_GET['id'] );
$item = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->orders WHERE `id`=%d", $id ) );
$infos = json_decode( $item->info, true );
?>
<div class="wrap">
	<h1><?= __( 'Order detail', 'elu-shop' ) . ' #' . $id; ?></h1>
	<div class="info-order">
		<h3><?= __( 'Order Information', 'elu-shop' ); ?></h3>
		<table class="widefat">
			<tr>
				<td><?= __( 'Time Order', 'elu-shop' ); ?>:</td>
				<td><?= $item->date; ?></td>
			</tr>
			<tr>
				<td><?= __( 'Status', 'elu-shop' ); ?>:</td>
				<td>
					<?php
					$statuses = [
						'pending' => [ 'badge', __( 'Processing', 'elu-shop' ) ],
						'closed'  => [ 'badge badge--success', __( 'Completed', 'elu-shop' ) ],
						'trash'   => [ 'badge badge--danger', __( 'Deleted', 'elu-shop' ) ],
					];
					$status   	= $statuses[ $item->status ];
					$user 		= get_userdata( $item->user );
					$payments 	= $item->info;
					$payments   = json_decode( $payments, true );

					printf( '<span class="%s">%s</span>', $status[0], $status[1] );

					if ( 'pending' === $item->status ) {
						printf( '<a href="%s" class="button">' . __( 'Completed', 'elu-shop' ) .'</a>', add_query_arg( [
							'action'   => 'close',
							'id'       => $id,
							'user'     => $user->ID,
							'amount'   => number_format( $item->amount, 0, '', '.' ),
							'payments' => $payments[0]['pay'],
							'_wpnonce' => wp_create_nonce( 'ps_close_order' ),
						], admin_url( 'edit.php?page=orders&post_type=product' ) ) );
					}
					if ( 'closed' === $item->status ) {
						printf( '<a href="%s" class="button">' . __( 'Processing', 'elu-shop' ) . '</a>', add_query_arg( [
							'action'   => 'open',
							'id'       => $id,
							'user'     => $user->ID,
							'amount'   => number_format( $item->amount, 0, '', '.' ),
							'payments' => $payments[0]['pay'],
							'_wpnonce' => wp_create_nonce( 'ps_open_order' ),
						], admin_url( 'edit.php?page=orders&post_type=product' ) ) );
					}
					?>
				</td>
			</tr>
			<tr>
				<td><?= __( 'Total money', 'elu-shop' ) ?>:</td>
				<td><?= number_format( $item->amount, 0, '', '.' ); ?> <?= ps_setting( 'currency_symbol' ); ?></td>
			</tr>
			<tr>
				<td><?= __( 'Buyer', 'elu-shop' ) ?>:</td>
				<td>
					<?php
					$user = get_userdata( $item->user );
					echo $user->display_name;
					?>
				</td>
			</tr>
		</table>
	</div>
	<div class="info-user">
		<h3><?= __( 'Buyer information', 'elu-shop' ) ?>:</h3>
		<table class="widefat">
			<thead>
			<tr>
				<td><?= __( 'Name', 'elu-shop' ) ?></td>
				<td><?= __( 'Email', 'elu-shop' ) ?></td>
				<td><?= __( 'Phone', 'elu-shop' ) ?></td>
				<td><?= __( 'Address', 'elu-shop' ) ?></td>
				<td><?= __( 'Payments', 'elu-shop' ) ?></td>
				<td><?= __( 'Form of transportation', 'elu-shop' ) ?></td>
				<td><?= __( 'Note', 'elu-shop' ) ?></td>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td><?= $infos['name']; ?></td>
				<td><?= $infos['email']; ?></td>
				<td><?= $infos['phone']; ?></td>
				<td><?= $infos['address']; ?></td>
				<td><?= $infos['pay']; ?></td>
				<td><?= $infos['delivery']; ?></td>
				<td><?= $item->note; ?></td>
			</tr>
			</tbody>
		</table> 
	</div>
	<div class="info-product">
		<h3><?= __( 'Products purchased', 'elu-shop' ) ?>:</h3>
		<table class="widefat">
			<thead>
			<tr>
				<th><?= __( 'Product name', 'elu-shop' ) ?></th>
				<th><?= __( 'Amount', 'elu-shop' ) ?></th>
				<th><?= __( 'price', 'elu-shop' ) ?></th>
				<th><?= __( 'Total', 'elu-shop' ) ?></th>
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
</div>
