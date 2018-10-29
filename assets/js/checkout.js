( function ( $, cart, wp, CheckoutParams ) {
	$( function () {
		const $cart = $( '#cart' ),
			// Underscore template for cart.
			cartTemplate = wp.template( 'cart' );

		function updateCartHtml() {
			$cart.html( cartTemplate( {
				products: Object.values( cart.data ),
				budget: parseInt( CheckoutParams.budget )
			} ) );
		}

		updateCartHtml();
		
		// Remove an item from cart.
		$cart.on( 'click', '.cart__remove', function( e ) {
			e.preventDefault();
			const productId = $( this ).data( 'product_id' );
			cart.removeProduct( productId );
			updateCartHtml();
		} );

		// Update quantity.
		$cart.on( 'change', '.cart__quantity input', function() {
			const productId = $( this ).data( 'product_id' ),
				quantity = this.value;
			cart.updateProduct( productId, quantity );
			updateCartHtml();
		} );
		$( '.radio-info', '.form-info.form-info--pay .form-info__fields:nth-child(1)' ).removeClass('hidden');
		$( 'input[type=radio]', '.form-info.form-info--pay .form-info__fields:nth-child(1)' ).attr('checked', true);
		$( 'input[type=radio]', '.check-deliverytype .form-info__fields:nth-child(1)' ).attr('checked', true);
		
		$( 'input[type=radio]', '.form-info.form-info--pay' ).on( 'click', function( e ) {
			var radio_class = $( this ).parent().parent(),
				radio_info = $('.radio-info', radio_class );
				$( '.radio-info', '.form-info.form-info--pay' ).addClass('hidden');

				if ( $(this).attr('checked', true)) {
					radio_info.removeClass('hidden');
				} 
					
		} ).change();

		// redict page checkout
		$( '.page' ).on( 'click', '.place-order',  function( e ) {
			e.preventDefault();
			$.post( CheckoutParams.ajaxUrl, {
				action: 'place_order',
			}, function ( response ) {
				location.href = response.data;
			}, 'json' )
		} );

		// Place checkout.
		$( '.place-checkout' ).on( 'click', function( e ) {
			e.preventDefault();
			var info = '',
				name = '',
				email = '',
				phone = '',
				address = '',
				notedeliver = '',
				pay = '',
				delivery = '';

				name 	    = $ ('.info-details .form-info__name').val();
				email 	    = $ ('.info-details .form-info__email').val();
				phone 	    = $ ('.info-details .form-info__phone').val();
				address     = $ ('.info-details .form-info__address').val();
				pay 	    = $( '.form-info__input input:checked', '.form-info--pay').val();
				delivery    = $( '.form-info__input input:checked', '.form-info--ship').val();
				notedeliver = $ ('.shipment-details .form-info__note').val();

				info = {
					"name": name,
					"email": email,
					"phone": phone,
					"address": address,
					"note": notedeliver,
					"pay": pay,
					"delivery": delivery
				};

				$.post( CheckoutParams.ajaxUrl, {
					action: 'place_checkout',
					cart: localStorage.getItem( 'cart' ),
					note: $( '#order-note' ).val(),
					info: info,
				}, function ( response ) {
					cart.clear();

					// Redirect user to account page after purchasing.
					location.href = response.data;
					// console.log(response);
				}, 'json' )
		} );
	} );
} )( jQuery, cart, wp, CheckoutParams );
