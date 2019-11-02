<?php
$id = filter_input( INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT );
if ( ! $id ) {
	return;
}

global $wpdb;
$item = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->orders WHERE `id`=%d", $id ) );
$info = json_decode( $item->info, true );
?>
<div class="wrap">
	<h1><?php esc_html_e( 'Order Details', 'elu-shop' ) . ' #' . esc_html( $id ); ?></h1>
	<div class="info-order">
		<h3><?php esc_html_e( 'Order Information', 'elu-shop' ); ?></h3>
		<table class="widefat">
			<tr>
				<td><?php esc_html_e( 'Time', 'elu-shop' ); ?></td>
				<td><?= esc_html( $item->date ); ?></td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'Status', 'elu-shop' ); ?></td>
				<td>
					<?php
					$statuses = [
						'pending' => [ 'badge', __( 'Pending', 'elu-shop' ) ],
						'completed'  => [ 'badge badge--success', __( 'Completed', 'elu-shop' ) ],
						'trash'   => [ 'badge badge--danger', __( 'Deleted', 'elu-shop' ) ],
					];
					$status   	= $statuses[ $item->status ];
					$user 		= get_userdata( $item->user );
					$payments 	= $item->info;
					$payments   = json_decode( $payments, true );

					printf( '<span class="%s">%s</span>', esc_attr( $status[0] ), esc_html( $status[1] ) );

					if ( 'pending' === $item->status ) {
						printf( '<a href="%s" class="button">' . esc_html__( 'Completed', 'elu-shop' ) .'</a>', add_query_arg( [
							'action'   => 'close',
							'id'       => $id,
							'_wpnonce' => wp_create_nonce( 'ps_close_order' ),
						], admin_url( 'edit.php?page=orders&post_type=product' ) ) );
					}
					if ( 'completed' === $item->status ) {
						printf( '<a href="%s" class="button">' . esc_html__( 'Pending', 'elu-shop' ) . '</a>', add_query_arg( [
							'action'   => 'open',
							'id'       => $id,
							'_wpnonce' => wp_create_nonce( 'ps_open_order' ),
						], admin_url( 'edit.php?page=orders&post_type=product' ) ) );
					}
					?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'Total', 'elu-shop' ) ?></td>
				<td><?= number_format_i18n( $item->amount, 0 ); ?> <?= esc_html( ps_setting( 'currency' ) ); ?></td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'Customer', 'elu-shop' ) ?></td>
				<td>
					<?php
					$user = get_userdata( $item->user );
					echo esc_html( $user->display_name );
					?>
				</td>
			</tr>
		</table>
	</div>
	<div class="info-user">
		<h3><?php esc_html_e( 'Customer Information', 'elu-shop' ) ?></h3>
		<table class="widefat">
			<thead>
			<tr>
				<td><?php esc_html_e( 'Name', 'elu-shop' ) ?></td>
				<!-- <td><?php esc_html_e( 'Email', 'elu-shop' ) ?></td> -->
				<td><?php esc_html_e( 'Phone', 'elu-shop' ) ?></td>
				<td><?php esc_html_e( 'Address', 'elu-shop' ) ?></td>
				<!-- <td><?php esc_html_e( 'Payment Method', 'elu-shop' ) ?></td>
				<td><?php esc_html_e( 'Shipping Method', 'elu-shop' ) ?></td> -->
				<td><?php esc_html_e( 'Note', 'elu-shop' ) ?></td>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td><?= esc_html( $info['name'] ); ?></td>
				<!-- <td><?= esc_html( $info['email'] ); ?></td> -->
				<td><?= esc_html( $info['phone'] ); ?></td>
				<td><?= esc_html( $info['address'] ); ?></td>
				<!-- <td><?= esc_html( $info['payment_method'] ); ?></td>
				<td><?= esc_html( $info['shipping_method'] ); ?></td> -->
				<td><?= esc_html( $item->note ); ?></td>
			</tr>
			</tbody>
		</table>
	</div>
	<div class="info-product">
		<h3><?php esc_html_e( 'Products', 'elu-shop' ) ?></h3>
		<table class="widefat">
			<thead>
			<tr>
				<th><?php esc_html_e( 'Product', 'elu-shop' ) ?></th>
				<th><?php esc_html_e( 'Quantity', 'elu-shop' ) ?></th>
				<th><?php esc_html_e( 'Price', 'elu-shop' ) ?></th>
				<th><?php esc_html_e( 'Total', 'elu-shop' ) ?></th>
			</tr>
			</thead>
			<tbody>
			<?php
			$products = json_decode( $item->data, true );
			foreach ( $products as $product ) :
				?>
				<tr>
					<td><?= esc_html( $product['title'] ); ?></td>
					<td><?= esc_html( $product['quantity'] ); ?></td>
					<td><?= number_format_i18n( $product['price'], 0 ); ?> <?= esc_html( ps_setting( 'currency' ) ); ?></td>
					<td><?= number_format_i18n( $product['quantity'] * $product['price'], 0 ); ?> <?= esc_html( ps_setting( 'currency' ) ); ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
