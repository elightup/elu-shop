<?php

namespace ELUSHOP;
use WP_Query;
class Cart {
	public function init() {
		// Register scripts to make sure 'cart' is available everywhere and can be used in other scripts.
		add_action( 'wp_enqueue_scripts', [ $this, 'register_scripts' ], 0 );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue' ] );
	}

	public function register_scripts() {
		wp_register_style( 'cart', ELU_SHOP_URL . 'assets/css/cart.css' );
		wp_register_script( 'notify', ELU_SHOP_URL . 'assets/js/alertify.min.js', [ 'jquery' ], '', true );
		wp_register_script( 'cart', ELU_SHOP_URL . 'assets/js/cart.js', [ 'jquery' ], '', true );
		wp_localize_script( 'cart', 'CartParams', [
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'checkoutUrl' => get_permalink( ps_setting( 'order_page' ) ),
		] );
	}

	public function enqueue() {
		wp_enqueue_script( 'notify' );
		wp_enqueue_style( 'cart' );
		wp_enqueue_script( 'cart' );
	}

	public static function cart( $args = [] ) {
		$args   = wp_parse_args( $args, [
			'id'   => get_the_ID(),
			'text' => __( 'Buy now', 'elu-shop' ),
			'echo' => true,
		] );
		$quantity = '<div class="quantity">
		<label class="reader-text" for="quantity_products">' . __( 'Amount', 'elu-shop' ) .  ':</label>
		<input type="number" id="quantity_products" class="quantity_products input-text qty text" step="1" min="1" max="" name="quantity" value="1" title="Qty" size="4" pattern="[0-9]*" inputmode="numeric">
		</div>';
		$button_view_cart = sprintf(
			'<a class="add-to-cart view-cart btn btn-primary" data-info="%s">%s</a>',
			esc_attr( wp_json_encode( self::get_product_info( $args['id'] ) ) ),
			esc_attr( $args['text'] )
		);
		if ( $args['echo'] ) {
			echo  '<div class="cart-button">' . $quantity . $button_view_cart . '</div>';
		}
	}

	public static function add_cart( $args = [] ) {
		$args   = wp_parse_args( $args, [
			'id'   => get_the_ID(),
			'text' => __( 'Add cart', 'elu-shop' ),
			'type' => __( 'Added to shopping cart', 'elu-shop' ),
			'echo' => true,
		] );
		$quantity = '<div class="quantity">
		<label class="reader-text" for="quantity_products">' . __( 'Amount', 'elu-shop' ) .  ':</label>
		<input type="number" id="quantity_products" class="quantity_products input-text qty text" step="1" min="1" max="" name="quantity" value="1" title="Qty" size="4" pattern="[0-9]*" inputmode="numeric">
		</div>';

		$button_add_cart = sprintf(
			'<a class="add-to-cart btn btn-primary" data-info="%s" data-type="%s">%s</a>',
			esc_attr( wp_json_encode( self::get_product_info( $args['id'] ) ) ),
			esc_attr( $args['type'] ),
			esc_attr( $args['text'] )
		);
		if ( $args['echo'] ) {
			echo  '<div class="cart-button">' . $quantity . $button_add_cart . '</div>';
		}
	}

	protected static function get_product_info( $id ) {
		return [
			'id'    		=> $id,
			'title' 		=> get_the_title( $id ),
			'price' 		=> ! empty( get_post_meta( $id, 'price', true ) ) ? get_post_meta( $id, 'price', true ) : 0,
			'url'   		=> wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'thumbnail', true )[0],
			'link' 			=> get_permalink( $id ),
		];
	}
}