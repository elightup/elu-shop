<?php
namespace ELUSHOP;

class schema {
	public static function register_tables() {
		global $wpdb;
		$wpdb->tables[] = 'orders';
		$wpdb->orders   = $wpdb->prefix . 'orders';
	}

	public static function create_tables() {
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		global $wpdb;

		// Orders table.
		$sql = "
			CREATE TABLE $wpdb->orders (
				`id` mediumint unsigned NOT NULL auto_increment,
				`date` datetime NOT NULL,
				`status` varchar(12) NOT NULL,
				`user` mediumint unsigned NOT NULL,
				`amount` int unsigned NOT NULL,
				`note` text,
				`info` text,
				`data` text,
				PRIMARY KEY  (`id`),
				KEY `date` (`date`),
				KEY `status` (`status`),
				KEY `user` (`user`)
			);
		";
		dbDelta( $sql );
	}
}
