<?php

namespace ELUSHOP;

class Assets {
	public function init() {
		// Register scripts to make sure 'cart' is available everywhere and can be used in other scripts.
		add_action( 'wp_enqueue_scripts', [ $this, 'register_scripts' ], 0 );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue' ], 5 );
	}

	public function register_scripts() {
		wp_register_script( 'bootstrap-js', ELU_SHOP_URL . 'assets/js/bootstrap.min.js', '', '4.1.0', true );

	}

	public function enqueue() {
		wp_enqueue_style( 'phoenix-shop', ELU_SHOP_URL . 'assets/css/style.css' );
		wp_enqueue_style( 'bootstrap-shop', ELU_SHOP_URL . 'assets/css/bootstrap.min.css' );
		wp_enqueue_script( 'bootstrap-js' );
	}
}
