<?php

namespace ELUSHOP;

class Checkout {
	public function init() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue' ] );
		add_filter( 'the_content', [ $this, 'filter_content' ] );
		add_action( 'wp_ajax_place_order', [ $this, 'place_order' ] );
		add_action( 'wp_ajax_nopriv_place_order', [ $this, 'place_order' ] );
		add_action( 'wp_ajax_place_checkout', [ $this, 'place_checkout' ] );
		add_action( 'wp_ajax_nopriv_place_checkout', [ $this, 'place_checkout' ] );
	}

	public function enqueue() {
		if ( ( ! $this->is_page_order()  ) && ( ! $this->is_page_checkout() ) ) {
			return;
		}
		wp_enqueue_style( 'checkout', ELU_SHOP_URL . 'assets/css/checkout.css' );
		wp_enqueue_script( 'checkout', ELU_SHOP_URL . 'assets/js/checkout.js', [ 'cart', 'wp-util' ], '', true );
		wp_localize_script( 'checkout', 'CheckoutParams', [
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'budget'  => intval( get_user_meta( get_current_user_id(), 'budget', true ) ),
		] );
	}

	public function filter_content( $content ) {
		if ( ! $this->is_page_order() && ! $this->is_page_checkout() ) {
			return $content;
		}
		ob_start();
		if ( $this->is_page_order() ) {
			TemplateLoader::instance()->get_template_part( 'order' );
		}
		if ( $this->is_page_checkout() ) {
			TemplateLoader::instance()->get_template_part( 'checkout' );
		}
		return ob_get_clean();
	}

	public function place_order() {
		$url = add_query_arg( [
			'userid'   => get_current_user_id(),
		], get_permalink( ps_setting( 'checkout_page' ) ) );
		wp_send_json_success( $url );
	}

	public function place_checkout() {
		$data = isset( $_POST['cart'] ) ? $_POST['cart'] : [];
		$note = isset( $_POST['note'] ) ? $_POST['note'] : '';
		$info = isset( $_POST['info'] ) ? $_POST['info'] : '';
		$data = wp_unslash( $data );
		$data = json_decode( $data, true );

		if ( empty( $data ) ) {
			wp_send_json_error();
		}
		$amount = 0;
		foreach ( $data as $product ) {
			$amount += $product['price'] * $product['quantity'];
		}

		global $wpdb;
		$wpdb->insert( "{$wpdb->prefix}orders", [
			'date'   => date( 'Y-m-d H:i:s' ),
			'status' => 'pending',
			'user'   => get_current_user_id(),
			'amount' => $amount,
			'note'   => $note,
			'info'	 => json_encode( $info ),
			'data'   => json_encode( $data, JSON_UNESCAPED_UNICODE ),
		], [
			'%s',
			'%s',
			'%d',
			'%d',
			'%s',
			'%s',
			'%s',
		] );
		$url = add_query_arg( [
			'view' => 'order',
			'id'   => $wpdb->insert_id,
			'type' => 'checkout',
		], get_permalink( ps_setting( 'invoice_details' ) ) );
		wp_send_json_success( $url );
	}

	protected function is_page_order() {
		return is_page() && get_the_ID() == ps_setting( 'cart_page' );
	}
	protected function is_page_checkout() {
		return is_page() && get_the_ID() == ps_setting( 'checkout_page' );
	}
}
