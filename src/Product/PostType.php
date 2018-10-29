<?php

namespace ELUSHOP\Product;

class PostType {
	public function init() {
		add_action( 'init', [ $this, 'register_post_type' ] );
		add_action( 'init', [ $this, 'register_taxonomies' ] );
		add_filter( 'rwmb_meta_boxes', [ $this, 'register_meta_boxes' ] );
	}

	public function register_post_type() {
		$labels = [
			'name'          => __( 'Product', 'elu-shop' ),
			'singular_name' => __( 'Product', 'elu-shop' ),
			'menu_name'          => _x( 'Products', 'admin menu', 'elu-shop' ),
			'name_admin_bar'     => _x( 'Product', 'add new on admin bar', 'elu-shop' ),
			'add_new'            => _x( 'Add New', 'Product', 'elu-shop' ),
			'add_new_item'       => __( 'Add New Product', 'elu-shop' ),
			'new_item'           => __( 'New Product', 'elu-shop' ),
			'edit_item'          => __( 'Edit Product', 'elu-shop' ),
			'view_item'          => __( 'View Product', 'elu-shop' ),
			'all_items'          => __( 'All Products', 'elu-shop' ),
			'search_items'       => __( 'Search Products', 'elu-shop' ),
			'parent_item_colon'  => __( 'Parent Products:', 'elu-shop' ),
			'not_found'          => __( 'No Products found.', 'elu-shop' ),
			'not_found_in_trash' => __( 'No Products found in Trash.', 'elu-shop' )
		];
		$slug = ps_setting( 'product_slug' );
		$slug = $slug ? $slug : 'product';
		$args   = [
			'labels'      => $labels,
			'supports'    => [ 'title', 'editor', 'excerpt', 'thumbnail', 'comments' ],
			'public'      => true,
			'has_archive' => true,
			'menu_icon'   => 'dashicons-cart',
			'rewrite'     => array( 'slug' => $slug ),
		];

		register_post_type( 'product', $args );
	}

	public function register_taxonomies() {
		$labels = [
			'name'          => __( 'Category', 'elu-shop' ),
			'singular_name' => __( 'Category', 'elu-shop' ),
		];
		$args   = [
			'labels'            => $labels,
			'hierarchical'      => true,
			'public'            => true,
			'show_admin_column' => true,
		];
		$taxonomy_slug = ps_setting( 'product_category_slug' );
		$taxonomy_slug = $taxonomy_slug ? $taxonomy_slug : 'product-category';
		register_taxonomy( $taxonomy_slug, 'product', $args );
		
		$labels2 = [
			'name'          => 'Tags',
			'singular_name' => 'Tags',
		    'search_items' =>  __( 'Search Tags', 'elu-shop'),
		    'popular_items' => __( 'Popular Tags', 'elu-shop' ),
		    'all_items' => __( 'All Tags' ),
		    'parent_item' => null,
		    'parent_item_colon' => null,
		    'edit_item' => __( 'Edit Topic', 'elu-shop' ),
		    'update_item' => __( 'Update Topic', 'elu-shop' ),
		    'add_new_item' => __( 'Add New Topic', 'elu-shop' ),
		    'new_item_name' => __( 'New Topic Name', 'elu-shop' ),
		    'separate_items_with_commas' => __( 'Separate Tags with commas', 'elu-shop' ),
		    'add_or_remove_items' => __( 'Add or remove Tags', 'elu-shop' ),
		    'choose_from_most_used' => __( 'Choose from the most used Tags', 'elu-shop' ),
		    'menu_name' => __( 'Tags' ),
		];
		$args2   = [
			'hierarchical' => false,
		    'labels' => $labels2,
		    'show_ui' => true,
		    'show_admin_column' => true,
		    'update_count_callback' => '_update_post_term_count',
		    'query_var' => true,
		];

		$tag_slug = ps_setting( 'product_tag_slug' );
		$tag_slug = $tag_slug ? $tag_slug : 'product-tag';

		register_taxonomy( $tag_slug, 'product', $args2 );
	}

	public function register_meta_boxes( $meta_boxes ) {
		$meta_boxes[] = [
			'title'      => __( 'Product Information', 'elu-shop' ),
			'post_types' => [ 'product' ],
			'fields'     => [
				[
					'id'   => 'price',
					'name' => __( 'Price', 'elu-shop' ),
					'type' => 'number',
					'min'  => 0,
					'desc' => sprintf( __( 'In %s.', 'elu-shop' ), ps_setting( 'currency_symbol' ) ),
					'size' => 10,
				],
				[
					'id'   => 'price_before_sale',
					'name' => __( 'Price before sale', 'elu-shop' ),
					'type' => 'number',
					'min'  => 0,
					'desc' => sprintf( __( 'In %s. Leave blank if the product has no discount.', 'elu-shop' ), ps_setting( 'currency_symbol' ) ),
					'size' => 10,
				],
			],
		];
		return $meta_boxes;
	}
}
