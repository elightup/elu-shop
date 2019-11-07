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
			'name'               => __( 'Products', 'elu-shop' ),
			'singular_name'      => __( 'Product', 'elu-shop' ),
			'add_new'            => _x( 'Add New', 'Product', 'elu-shop' ),
			'add_new_item'       => __( 'Add New Product', 'elu-shop' ),
			'edit_item'          => __( 'Edit Product', 'elu-shop' ),
			'new_item'           => __( 'New Product', 'elu-shop' ),
			'view_item'          => __( 'View Product', 'elu-shop' ),
			'view_items'         => __( 'View Products', 'elu-shop' ),
			'search_items'       => __( 'Search Products', 'elu-shop' ),
			'not_found'          => __( 'No products found.', 'elu-shop' ),
			'not_found_in_trash' => __( 'No products found in Trash.', 'elu-shop' ),
			'parent_item_colon'  => __( 'Parent Products:', 'elu-shop' ),
			'all_items'          => __( 'All Products', 'elu-shop' ),
		];
		$options = get_option( 'elu_shop' );
		$slug    = isset( $options[ 'product_slug' ] ) ? $options[ 'product_slug' ] : 'product';

		$args   = [
			'label'       => __( 'Products', 'elu-shop' ),
			'labels'      => $labels,
			'supports'    => [ 'title', 'editor', 'excerpt', 'thumbnail', 'comments' ],
			'public'      => true,
			'has_archive' => true,
			'menu_icon'   => 'dashicons-cart',
			'rewrite'     => [ 'slug' => $slug ],
		];

		register_post_type( 'product', $args );
	}

	public function register_taxonomies() {
		$category_labels = [
			'name'                       => __( 'Categories', 'elu-shop' ),
			'singular_name'              => __( 'Category', 'elu-shop' ),
			'all_items'                  => __( 'All Categories', 'elu-shop' ),
			'edit_item'                  => __( 'Edit Category', 'elu-shop' ),
			'view_item'                  => __( 'View Category', 'elu-shop' ),
			'update_item'                => __( 'Update Category', 'elu-shop' ),
			'add_new_item'               => __( 'Add New Category', 'elu-shop' ),
			'new_item_name'              => __( 'New Category Name', 'elu-shop' ),
			'parent_item'                => __( 'Parent Category', 'elu-shop' ),
			'parent_item_colon'          => __( 'Parent Category:', 'elu-shop' ),
			'search_items'               => __( 'Search Categories', 'elu-shop' ),
			'popular_items'              => __( 'Popular Categories', 'elu-shop' ),
			'separate_items_with_commas' => __( 'Separate categories with commas', 'elu-shop' ),
			'add_or_remove_items'        => __( 'Add or remove categories', 'elu-shop' ),
			'choose_from_most_used'      => __( 'Choose from the most used categories', 'elu-shop' ),
			'not_found'                  => __( 'No categories found', 'elu-shop' ),
			'back_to_items'              => __( '&larr; Back to categories', 'elu-shop' ),
		];
		$category_args   = [
			'label'             => __( 'Categories', 'elu-shop' ),
			'labels'            => $category_labels,
			'hierarchical'      => true,
			'show_admin_column' => true,
		];
		$options       = get_option( 'elu_shop' );
		$category_slug = isset( $options[ 'product_category_slug' ] ) ? $options[ 'product_category_slug' ] : 'product-category';
		register_taxonomy( $category_slug, 'product', $category_args );

		$tag_labels = [
			'name'                       => __( 'Tags', 'elu-shop' ),
			'singular_name'              => __( 'Tag', 'elu-shop' ),
			'all_items'                  => __( 'All Tags', 'elu-shop' ),
			'edit_item'                  => __( 'Edit Tag', 'elu-shop' ),
			'view_item'                  => __( 'View Tag', 'elu-shop' ),
			'update_item'                => __( 'Update Tag', 'elu-shop' ),
			'add_new_item'               => __( 'Add New Tag', 'elu-shop' ),
			'new_item_name'              => __( 'New Tag Name', 'elu-shop' ),
			'parent_item'                => __( 'Parent Tag', 'elu-shop' ),
			'parent_item_colon'          => __( 'Parent Tag:', 'elu-shop' ),
			'search_items'               => __( 'Search Tags', 'elu-shop' ),
			'popular_items'              => __( 'Popular Tags', 'elu-shop' ),
			'separate_items_with_commas' => __( 'Separate tags with commas', 'elu-shop' ),
			'add_or_remove_items'        => __( 'Add or remove tags', 'elu-shop' ),
			'choose_from_most_used'      => __( 'Choose from the most used tags', 'elu-shop' ),
			'not_found'                  => __( 'No tags found', 'elu-shop' ),
			'back_to_items'              => __( '&larr; Back to tags', 'elu-shop' ),
		];
		$tag_args   = [
			'label'             => __( 'Tags', 'elu-shop' ),
			'labels'            => $tag_labels,
			'show_ui'           => true,
			'show_admin_column' => true,
		];
		$options  = get_option( 'elu_shop' );
		$tag_slug = isset( $options[ 'product_tag_slug' ] ) ? $options[ 'product_tag_slug' ] : 'product-tag';
		register_taxonomy( $tag_slug, 'product', $tag_args );
	}

	public function register_meta_boxes( $meta_boxes ) {
		$options  = get_option( 'elu_shop' );
		$currency = $options[ 'currency' ];
		$meta_boxes[] = [
			'title'      => __( 'Product Information', 'elu-shop' ),
			'post_types' => [ 'product' ],
			'fields'     => [
				[
					'id'   => 'price',
					'name' => __( 'Price', 'elu-shop' ),
					'type' => 'number',
					'min'  => 0,
					'desc' => sprintf( __( 'In %s.', 'elu-shop' ), $currency ),
					'size' => 10,
				],
				[
					'id'   => 'price_before_sale',
					'name' => __( 'Price before sale', 'elu-shop' ),
					'type' => 'number',
					'min'  => 0,
					'desc' => sprintf( __( 'In %s. Leave blank if the product has no discount.', 'elu-shop' ), $currency ),
					'size' => 10,
				],
			],
		];
		return $meta_boxes;
	}
}
