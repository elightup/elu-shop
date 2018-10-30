<?php

namespace ELUSHOP\Order;

class Notification {
	public function init() {
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue' ] );

		// Get number of pending orders to show in admin menu via Ajax.
		add_action( 'wp_ajax_ps_get_pending_orders', [ $this, 'get_pending_orders' ] );

		// Show pending orders in admin bar.
		add_action( 'admin_bar_menu', [ $this, 'add_admin_bar_item' ], 99 );
	}

	public function enqueue() {
		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}
		wp_enqueue_script( 'order-notification', ELU_SHOP_URL . 'assets/js/order-notification.js', [ 'jquery' ], '', true );
		wp_enqueue_style( 'order-notification', ELU_SHOP_URL . 'assets/css/order-notification.css' );
		wp_localize_script(
			'order-notification',
			'OrderNotification',
			[
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			]
		);
	}

	public function get_pending_orders() {
		$count = $this->get_total_items( 'pending' );
		wp_send_json_success( $count );
	}

	public function add_admin_bar_item( \WP_Admin_Bar $admin_bar ) {
		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}
		$count = $this->get_total_items( 'pending' );
		$admin_bar->add_node(
			[
				'id'    => 'pending-orders',
				'title' => "<span class='bubble'>$count</span>" . __( 'Order', 'elu-shop' ),
				'href'  => admin_url( 'edit.php?post_type=product&page=orders' ),
				'meta'  => [],
			]
		);
	}

	protected function get_total_items( $status = '' ) {
		global $wpdb;

		$where = '';
		if ( $status ) {
			$where = $wpdb->prepare( 'WHERE `status`=%s', $status );
		}
		return $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->orders $where" );
	}
}
