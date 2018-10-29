jQuery( function ( $ ) {
	'use strict';

	function updateStatus() {
		$.post( OrderNotification.ajaxUrl, {
			action: 'ps_get_pending_orders'
		}, function ( response ) {
			$( '#wp-admin-bar-pending-orders .bubble' ).text( response.data );
		} );
	}

	setInterval( updateStatus, 30000 );
} );
