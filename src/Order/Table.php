<?php

namespace ELUSHOP\Order;

class Table extends \WP_List_Table {
	protected $base_url;

	public function __construct() {
		parent::__construct( [
			'singular' => 'order',
			'plural'   => 'orders',
		] );
		$this->base_url = admin_url( 'edit.php?page=orders&post_type=product' );
	}

	protected function get_views() {
		$links = [];

		$status = isset( $_REQUEST['status'] ) ? $_REQUEST['status'] : '';

		// All items.
		$class        = $status ? '' : ' class="current"';
		$links['all'] = "<a href='{$this->base_url}'$class>" . sprintf( 'All <span class="count">(%s)</span>', $this->get_total_items() ) . '</a>';

		$statuses = [
			'closed'  => __( 'Finish', 'elu-shop' ),
			'pending' => __( 'Processing', 'elu-shop' ),
			'trash'   => __( 'Move to trash', 'elu-shop' ),
		];
		foreach ( $statuses as $key => $label ) {
			$class         = $key === $status ? ' class="current"' : '';
			$url           = add_query_arg( 'status', $key, $this->base_url );
			$links[ $key ] = "<a href='$url'$class>" . sprintf( "$label <span class='count'>(%s)</span>", $this->get_total_items( $key ) ) . '</a>';
		}
		return $links;
	}

	protected function extra_tablenav( $which ) {
		?>
		<div class="alignleft actions">
			<?php
			if ( 'top' === $which ) {
				$this->months_dropdown( 'order' );
				submit_button( __( 'Filter', 'elu-shop' ), '', 'filter_action', false, [ 'id' => 'post-query-submit' ] );
			}

			if ( isset( $_GET['status'] ) && 'trash' === $_GET['status'] && $this->get_total_items( 'trash' ) ) {
				submit_button( __( 'Empty trash', 'elu-shop' ), 'apply', 'delete_all', false );
			}
			?>
		</div>
		<?php
	}

	protected function months_dropdown( $post_type ) {
		global $wpdb, $wp_locale;

		$where  = $this->get_sql_where_clause();
		$months = $wpdb->get_results( "
			SELECT DISTINCT YEAR( `date` ) AS year, MONTH( `date` ) AS month
			FROM $wpdb->orders
			$where
			ORDER BY `date` DESC
		" );

		$month_count = count( $months );
		if ( ! $month_count || ( 1 == $month_count && 0 == $months[0]->month ) ) {
			return;
		}

		$m = isset( $_GET['m'] ) ? (int) $_GET['m'] : 0;
		?>
		<select name="m" id="filter-by-date">
			<option<?php selected( $m, 0 ); ?> value="0"><?php __( 'All dates', 'elu-shop' ); ?></option>
			<?php
			foreach ( $months as $arc_row ) {
				if ( 0 == $arc_row->year ) {
					continue;
				}

				$month = zeroise( $arc_row->month, 2 );
				$year  = $arc_row->year;

				printf( "<option %s value='%s'>%s</option>\n",
					selected( $m, $year . $month, false ),
					esc_attr( $arc_row->year . $month ),
					/* translators: 1: month name, 2: 4-digit year */
					sprintf( __( '%1$s %2$d' ), $wp_locale->get_month( $month ), $year )
				);
			}
			?>
		</select>
		<?php
	}

	public function prepare_items() {
		global $wpdb;

		$this->handle_actions();

		$this->_column_headers = $this->get_column_info();

		$per_page = $this->get_items_per_page( 'orders_per_page', 20 );
		$page     = $this->get_pagenum();

		$this->set_pagination_args( [
			'total_items' => $this->get_total_items(),
			'per_page'    => $per_page,
		] );

		$where  = $this->get_sql_where_clause();
		$order  = $this->get_sql_order_clause();
		$limit  = "LIMIT $per_page";
		$offset = ' OFFSET ' . ( $page - 1 ) * $per_page;
		$sql    = "SELECT * FROM $wpdb->orders $where $order $limit $offset";

		$this->items = $wpdb->get_results( $sql, 'ARRAY_A' );
	}

	protected function get_total_items( $status = '' ) {
		global $wpdb;

		$where = '';
		if ( $status ) {
			$where = $wpdb->prepare( 'WHERE `status`=%s', $status );
		}
		return $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->orders $where" );
	}

	protected function get_sql_where_clause() {
		global $wpdb;

		$where = [];
		if ( isset( $_REQUEST['status'] ) ) {
			$where[] = $wpdb->prepare( '`status`=%s', $_REQUEST['status'] );
		}

		return $where ? 'WHERE ' . implode( ' ', $where ) : '';
	}

	protected function get_sql_order_clause() {
		if ( empty( $_REQUEST['orderby'] ) ) {
			return '';
		}
		$order = 'ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
		$order .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
		return $order;
	}

	public function get_columns() {
		$columns = [
			'cb'       => '<input type="checkbox">',
			'id'       => 'ID',
			'date'     => __( 'Date', 'elu-shop' ),
			'status'   => __( 'Status', 'elu-shop' ),
			'products' => __( 'Product', 'elu-shop' ),
			'amount'   => __( 'Total', 'elu-shop' ),
		];

		return $columns;
	}

	public function get_sortable_columns() {
		$sortable_columns = [
			'date'   => [ 'date', true ],
			'status' => [ 'status', false ],
		];

		return $sortable_columns;
	}

	public function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="orders[]" value="%s">', intval( $item['id'] )
		);
	}

	public function column_id( $item ) {
		$title = sprintf(
			'<a href="%s"><strong>' . __( 'Order', 'elu-shop' ) . ': #%d</strong></a>',
			add_query_arg( [
				'action' => 'view',
				'id'     => $item['id'],
			], $this->base_url ),
			$item['id']
		);
		return $title . $this->row_actions( $this->get_row_actions( $item ) );
	}

	public function column_default( $item, $column_name ) {
		return isset( $item[ $column_name ] ) ? $item[ $column_name ] : '';
	}

	public function column_status( $item ) {
		$statuses = [
			'pending' => [ 'badge', __( 'Processing', 'elu-shop' ) ],
			'closed'  => [ 'badge badge--success', __( 'Completed', 'elu-shop' ) ],
			'trash'   => [ 'badge badge--danger', __( 'Deleted', 'elu-shop' ) ],
		];
		$status   = $statuses[ $item['status'] ];
		$user = get_userdata( $item['user'] );
		$payments 	= $item['info'];
		$payments   = json_decode( $payments, true );
		
		printf( '<span class="%s">%s</span>', $status[0], $status[1] );
		if ( 'pending' === $item['status'] ) {
			printf( '<a href="%s" class="button">' . __( 'Completed', 'elu-shop' ) . ' </a>', add_query_arg( [
				'action'   => 'close',
				'id'       => $item['id'],
				'user'     => $user->ID,
				'amount'   => number_format( $item['amount'],0, '', '.' ),
				'payments' => $payments[0]['pay'],
				'_wpnonce' => wp_create_nonce( 'ps_close_order' ),
			], $this->base_url ) );
		}
		if ( 'closed' === $item['status'] ) {
			printf( '<a href="%s" class="button">' . __( 'Processing', 'elu-shop' ) . ' </a>', add_query_arg( [
				'action'   => 'open',
				'id'       => $item['id'],
				'user'     => $user->ID,
				'amount'   => number_format( $item['amount'],0, '', '.' ),
				'payments' => $payments[0]['pay'],
				'_wpnonce' => wp_create_nonce( 'ps_open_order' ),
			], $this->base_url ) );
		}
	}

	public function column_products( $item ) {
		$data   = $item['data'];
		$data   = json_decode( $data, true );
		$output = [];
		foreach ( $data as $product ) {
			$output[] = sprintf( '%s: %s', $product['title'], $product['quantity'] );
		}
		echo implode( '<br>', $output );
	}

	public function column_amount( $item ) {
		echo number_format( $item['amount'], 0, '', '.' ) . ' ' . ps_setting( 'currency_symbol' );
	}

	public function column_payments( $item ) {
		$payments 	= $item['info'];
		$payments   = json_decode( $payments, true );
		echo $payments[0]['pay'];
	}


	protected function get_row_actions( $item ) {
		$actions = [
			'view' => sprintf(
				'<a href="%s">' . __( 'Detail', 'elu-shop' ) . '</a>',
				add_query_arg( [
					'action' => 'view',
					'id'     => $item['id'],
				], $this->base_url )
			),
		];
		if ( 'trash' === $item['status'] ) {
			$actions['untrash'] = sprintf(
				'<a href="%s">' . __( 'Restore', 'elu-shop' ) . '</a>',
				add_query_arg( [
					'action'   => 'untrash',
					'id'       => $item['id'],
					'_wpnonce' => wp_create_nonce( 'ps_untrash_order' ),
				], $this->base_url )
			);
		} else {
			$actions['trash'] = sprintf(
				'<a href="%s">' . __( 'Move to trash', 'elu-shop' ) . '</a>',
				add_query_arg( [
					'action'   => 'trash',
					'id'       => $item['id'],
					'_wpnonce' => wp_create_nonce( 'ps_trash_order' ),
				], $this->base_url )
			);
		}
		$actions['delete'] = sprintf(
			'<a href="%s">' . __( 'Empty trash', 'elu-shop' ) . '</a>',
			add_query_arg( [
				'action'   => 'delete',
				'id'       => $item['id'],
				'_wpnonce' => wp_create_nonce( 'ps_delete_order' ),
			], $this->base_url )
		);
		return $actions;
	}

	public function get_bulk_actions() {
		$actions = [
			'bulk-trash'  => __( 'Delete', 'elu-shop' ),
			'bulk-delete' => __( 'Empty trash', 'elu-shop' ),
		];

		if ( isset( $_GET['status'] ) && 'trash' === $_GET['status'] && $this->get_total_items( 'trash' ) ) {
			$actions['bulk-untrash'] = __( 'Restore', 'elu-shop' );
		}

		return $actions;
	}

	public function current_action() {
		if ( isset( $_REQUEST['delete_all'] ) || isset( $_REQUEST['delete_all2'] ) ) {
			return 'delete_all';
		}

		return parent::current_action();
	}

	public function handle_actions() {
		global $wpdb;

		if ( 'trash' === $this->current_action() ) {
			check_admin_referer( 'ps_trash_order' );
			$this->update_item_status( $_GET['id'], 'trash' );
			return;
		}
		if ( 'untrash' === $this->current_action() ) {
			check_admin_referer( 'ps_untrash_order' );
			$this->update_item_status( $_GET['id'], 'pending' );
			return;
		}
		if ( 'open' === $this->current_action() ) {
			check_admin_referer( 'ps_open_order' );
			$check_budget_user = $this->check_budget_user( $_GET['user'], $_GET['amount'] );

			$this->update_item_status( $_GET['id'], 'pending' );
			return;
		}
		if ( 'close' === $this->current_action() ) {
			check_admin_referer( 'ps_close_order' );

			$this->update_item_status( $_GET['id'], 'closed' );
			return;
		}
		if ( 'delete' === $this->current_action() ) {
			check_admin_referer( 'ps_delete_order' );
			$this->delete_item( absint( $_GET['id'] ) );
			return;
		}
		if ( 'bulk-trash' === $this->current_action() ) {
			check_admin_referer( 'bulk-orders' );
			$ids = esc_sql( $_GET['orders'] );
			foreach ( $ids as $id ) {
				$this->update_item_status( $id, 'trash' );
			}
			return;
		}
		if ( 'bulk-untrash' === $this->current_action() ) {
			check_admin_referer( 'bulk-orders' );
			$ids = esc_sql( $_GET['orders'] );
			foreach ( $ids as $id ) {
				$this->update_item_status( $id, 'pending' );
			}
			return;
		}
		if ( 'bulk-delete' === $this->current_action() ) {
			check_admin_referer( 'bulk-orders' );
			$ids = esc_sql( $_GET['orders'] );
			foreach ( $ids as $id ) {
				$this->delete_item( $id );
			}
			return;
		}
		if ( 'delete_all' === $this->current_action() ) {
			$wpdb->delete(
				$wpdb->orders,
				[ 'status' => 'trash' ]
			);
		}
	}

	protected function update_item_status( $id, $status ) {
		global $wpdb;

		$wpdb->update(
			$wpdb->orders,
			[ 'status' => $status ],
			[ 'id' => $id ],
			[ '%s' ]
		);
	}

	protected function delete_item( $id ) {
		global $wpdb;

		$wpdb->delete(
			$wpdb->orders,
			[ 'id' => $id ],
			[ '%d' ]
		);
	}
}
