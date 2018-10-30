<?php

namespace ELUSHOP;

class Settings {
	public function init() {
		add_filter( 'mb_settings_pages', [ $this, 'register_settings_page' ] );
		add_filter( 'rwmb_meta_boxes', [ $this, 'register_meta_boxes' ] );
	}

	public function register_settings_page( $settings_pages ) {
		$settings_pages[] = [
			'id'          => 'elu-shop',
			'option_name' => 'elu_shop',
			'menu_title'  => __( 'Settings', 'elu-shop' ),
			'parent'      => 'edit.php?post_type=product',
			'style'       => 'no-boxes',
			'columns'     => true,
			'tabs'        => [
				'general'  => __( 'General', 'elu-shop' ),
				'payment'  => __( 'Payment', 'elu-shop' ),
				'shipping' => __( 'Shipping', 'elu-shop' ),
				'support'  => __( 'Support', 'elu-shop' ),
			],
		];
		return $settings_pages;
	}

	public function register_meta_boxes( $meta_boxes ) {
		if ( ! function_exists( 'mb_settings_page_load' ) ) {
			return $meta_boxes;
		}
		$meta_boxes[] = [
			'id'             => 'general',
			'title'          => ' ',
			'settings_pages' => 'elu-shop',
			'tab'            => 'general',
			'fields'         => [
				[
					'id'   => 'product_slug',
					'name' => __( 'Product Slug', 'elu-shop' ),
					'type' => 'text',
					'std'  => 'product',
				],
				[
					'id'   => 'product_category_slug',
					'name' => __( 'Product Category Slug', 'elu-shop' ),
					'type' => 'text',
					'std'  => 'product-category',
				],
				[
					'id'   => 'product_tag_slug',
					'name' => __( 'Product Tag Slug', 'elu-shop' ),
					'type' => 'text',
					'std'  => 'product-tag',
				],
				[
					'id'        => 'cart_page',
					'name'      => __( 'Cart Page', 'elu-shop' ),
					'type'      => 'post',
					'post_type' => 'page',
				],
				[
					'id'        => 'checkout_page',
					'name'      => __( 'Checkout Page', 'elu-shop' ),
					'type'      => 'post',
					'post_type' => 'page',
				],
				[
					'id'        => 'confirmation_page',
					'name'      => __( 'Confirmation Page', 'elu-shop' ),
					'type'      => 'post',
					'post_type' => 'page',
				],
			],
		];
		$meta_boxes[] = [
			'id'             => 'payment',
			'title'          => ' ',
			'settings_pages' => 'elu-shop',
			'tab'            => 'payment',
			'fields'         => [
				[
					'id'   => 'currency',
					'type' => 'text',
					'name' => __( 'Currency', 'elu-shop' ),
				],
				[
					'id'     => 'payment_methods',
					'type'   => 'group',
					'name'   => __( 'Payment Methods', 'elu-shop' ),
					'clone'  => true,
					'fields' => [
						[
							'id'   => 'payment_method_title',
							'type' => 'text',
						],
						[
							'id'      => 'payment_method_description',
							'type'    => 'wysiwyg',
							'options' =>
							[
								'textarea_rows' => 6,
								'media_buttons' => false,
								'quicktags'     => false,
							],
						],
					],
				],
			],
		];
		$meta_boxes[] = [
			'id'             => 'shipping',
			'title'          => ' ',
			'settings_pages' => 'elu-shop',
			'tab'            => 'shipping',
			'fields'         => [
				[
					'id'    => 'shipping_methods',
					'name'  => __( 'Shipping Methods', 'elu-shop' ),
					'type'  => 'text',
					'clone' => true,
				],
			],
		];

		$meta_boxes[] = [
			'id'             => 'faqs',
			'title'          => ' ',
			'settings_pages' => 'elu-shop',
			'tab'            => 'support',
			'fields'         => [
				[
					'type' => 'custom_html',
					'name' => __( 'Add to cart', 'elu-shop' ),
					'std'  => '<code>ELUSHOP\Cart::add_cart();</code>',
				],
				[
					'type' => 'custom_html',
					'name' => __( 'Buy fast', 'elu-shop' ),
					'std'  => '<code>ELUSHOP\Cart::cart();</code>',
				],
			],
		];

		return $meta_boxes;
	}
}
