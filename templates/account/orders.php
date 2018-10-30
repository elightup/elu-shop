<h3><?php esc_html_e( 'Order List', 'elu-shop' )?></h3>
<?php
global $wpdb;
$items = $wpdb->get_results( $wpdb->prepare(
	"SELECT `id`, `date`, `status`, `amount` FROM $wpdb->orders
	 WHERE `user`=%d
	 ORDER BY `date` DESC
	 ",
	get_current_user_id()
) );

if ( empty( $items ) ) :
	?>
	<div class="alert alert--warning"><?php esc_html_e( 'You have no orders.', 'elu-shop' )?>
		<a href="<?= get_post_type_archive_link( 'product' ); ?>"><?php esc_html_e( 'Click here', 'elu-shop' )?></a><?php esc_html_e( 'to start the purchase', 'elu-shop' )?>
	</div>
	<?php
	return;
endif;
?>

<table class="orders">
	<tr>
		<th><?php esc_html_e( 'Order ID', 'elu-shop' )?></th>
		<th><?php esc_html_e( 'Time', 'elu-shop' )?></th>
		<th><?php esc_html_e( 'Status', 'elu-shop' )?></th>
		<th><?php esc_html_e( 'Total', 'elu-shop' )?></th>
		<th><?php esc_html_e( 'Action', 'elu-shop' )?></th>
	</tr>
	<?php foreach ( $items as $item ) : ?>
		<tr>
			<td>#<?= $item->id; ?></td>
			<td><?= $item->date; ?></td>
			<td>
				<?php
				$statuses = [
					'pending' => [ 'badge', __( 'Pending', 'elu-shop' ) ],
					'completed'  => [ 'badge badge--success', __( 'Completed', 'elu-shop' ) ],
					'trash'   => [ 'badge badge--danger', __( 'Deleted', 'elu-shop' ) ],
				];
				$status   = $statuses[ $item->status ];
				printf( '<span class="%s">%s</span>', $status[0], $status[1] );
				?>
			</td>
			<td><?= $item->amount; ?> <?= ps_setting( 'currency' ); ?></td>
			<td><a href="?view=order&id=<?= $item->id; ?>"><?php esc_html_e( 'See details', 'elu-shop' )?></a></td>
		</tr>
	<?php endforeach; ?>
</table>
