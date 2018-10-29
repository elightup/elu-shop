<?php
/**
 * Plugin Name: Elightup Shop
 * Plugin URI: https://elightup.com
 * Description: An easy e-commerce solution for WordPress website.
 * Version: 0.1.0
 * Author: eLightUp
 * Author URI: https://elightup.com
 * License: GPL 2+
 * Text Domain: elightup-shop
 * Domain Path: /languages/
 */

namespace ELUSHOP;


// Prevent loading this file directly.
defined( 'ABSPATH' ) || exit;
require 'vendor/autoload.php';
define( 'ELU_SHOP_URL', plugin_dir_url( __FILE__ ) );
define( 'ELU_SHOP_DIR', plugin_dir_path( __FILE__ ) );

load_plugin_textdomain( 'elu-shop', false, plugin_basename( ELU_SHOP_DIR ) . '/languages' );

(new Schema())->register_tables();
register_activation_hook( __FILE__, function () {
	(new Schema())->create_tables();
} );

(new Product\PostType())->init();
(new Cart())->init();
(new Checkout() )->init();
(new Order\Notification())->init();
(new User\invoice())->init();
if ( is_admin() ) {
	if ( ! function_exists( 'mb_settings_page_load' ) ) {
		(new Settings())->init();
	}
	(new Order\AdminList())->init();
} else {
	(new Assets())->init();
}

