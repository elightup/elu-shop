<h3><?= __( 'Order list', 'elu-shop' )?></h3>
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
	<div class="alert alert--warning"><?= __( 'You have no orders.', 'elu-shop' )?>
		<a href="<?= get_post_type_archive_link( 'product' ); ?>"><?= __( 'Click here', 'elu-shop' )?></a><?= __( 'to start the purchase', 'elu-shop' )?>
	</div>
	<?php
	return;
endif;
?>

<table class="orders">
	<tr>

		<th><?= __( 'Order Code', 'elu-shop' )?></th>
		<th><?= __( 'Time', 'elu-shop' )?></th>
		<th><?= __( 'Status', 'elu-shop' )?></th>
		<th><?= __( 'Total money', 'elu-shop' )?></th>
		<th><?= __( 'Action', 'elu-shop' )?></th>
	</tr>
	<?php foreach ( $items as $item ) : ?>
		<tr>
			<td>#<?= $item->id; ?></td>
			<td><?= $item->date; ?></td>
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
			<td><?= $item->amount; ?> <?= ps_setting( 'currency_symbol' ); ?></td>
			<td><a href="?view=order&id=<?= $item->id; ?>"><?= __( 'See details', 'elu-shop' )?></a></td>
		</tr>
	<?php endforeach; ?>
</table>
