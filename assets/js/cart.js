( function ( $, window, document, localStorage, CartParams ) {
	let cart = {
		data: {},
		load: function () {
			const data = localStorage.getItem( 'cart' );
			if ( data ) {
				cart.data = JSON.parse( data );
			}
		},
		update: function () {
			localStorage.setItem( 'cart', JSON.stringify( cart.data ) );
		},
		clear: function() {
			cart.data = {};
			cart.update();
		},
		addProduct: function ( productInfo, quantity ) {
			cart.data[productInfo.id] = productInfo;
			if ( quantity > 1 ) {
				cart.data[productInfo.id].quantity = quantity;
			} else {
				cart.data[productInfo.id].quantity = 1;
			}
			cart.update();
		},
		updateProduct: function( productId, quantity ) {
			cart.data[productId].quantity = quantity;
			cart.update();
		},
		removeProduct: function ( productId ) {
			delete cart.data[productId];
			cart.update();
		}
	};

	function clickHandle( e ) {
		e.preventDefault();
		$('.add-to-cart', '.cart-button' ).append('<div class="load-icon"></div>');
		const data = localStorage.getItem( 'cart' );
		if ( data ) {
			cart.data = JSON.parse( data );
		}

		var add_cart_group  = $(this).parent();
			cart_id         = [];
			mini_cart_count = 0;
			quantity        = $( '.quantity_products', add_cart_group ).val();
		const productInfo = $( this ).data( 'info' );

		$.each( cart['data'], function( key, value ) {
			mini_cart_count += parseInt( value['quantity'] );
			cart_id.push( value['id'] );
			if ( value['id'] == productInfo['id'] ) {
				$old_quantity = value['quantity'];
				$new_quantity = parseInt( $old_quantity ) + parseInt( quantity );
			}
		});

		// add or update product cart.
		if ( cart_id.includes( productInfo['id'] ) ) {
			cart.updateProduct( productInfo['id'], $new_quantity );
		} else {
			cart.addProduct( productInfo, quantity );
		}

		// add count to minicart when click add to cart button.
		mini_cart_count += parseInt( quantity );
		$( '.mini-cart-count' ).html( mini_cart_count );

		// Notify when click add to cart button
		var add_success = $( this ).data('type');
        setTimeout(function(){
			$( '.load-icon', '.add-to-cart' ).remove();
			new $.notification('<i class="fa fa-shopping-cart"></i> ' + add_success , {"class" : 'alert-notification', timeout : 2000, click : null, close : false});
			$( '.view-cart', add_cart_group ).css( 'display', 'inline-block' );
		}, 1000);

		// $( this ).addClass('view-cart');
		// var button = $( this ).data('type')
		// if ( button ) {
		// 	$( this, '.view-cart' ).attr('title','Xem giỏ hàng');
		// }
	}
	function clickviewcart( e ) {
		e.preventDefault();
		var link = `${CartParams.cartUrl}`;
		location.href = link;
	}

	cart.load();

	// add mini cart count.
	$mini_cart_count = 0;
	$.each( cart['data'], function( key, value ) {
		$mini_cart_count += parseInt( value['quantity'] );
	});
	$( '.mini-cart-count' ).html( $mini_cart_count );

	$( function() {
		$( '.cart-button .view-cart' ).css( 'display', 'none' );
		$( document ).on( 'click', '.add-to-cart.buy-now', clickviewcart );
		$( document ).on( 'click', '.add-to-cart', clickHandle );

	} );


    $('<div class="quantity-nav"><div class="quantity-button quantity-up">+</div><div class="quantity-button quantity-down">-</div></div>').insertAfter('.cart__quantity input');
    $('.cart__quantity').each(function() {
		var spinner = $(this),
			input = spinner.find('input[type="number"]'),
			btnUp = spinner.find('.quantity-up'),
			btnDown = spinner.find('.quantity-down'),
			min = input.attr('min'),
			max = input.attr('max');

		btnUp.click(function() {
			var oldValue = parseFloat(input.val());
			if (oldValue >= max) {
				var newVal = oldValue;
			} else {
				var newVal = oldValue + 1;
			}
			spinner.find("input").val(newVal);
			spinner.find("input").trigger("change");
		});

		btnDown.click(function() {
			var oldValue = parseFloat(input.val());
			if (oldValue <= min) {
				var newVal = oldValue;
			} else {
				var newVal = oldValue - 1;
			}
			spinner.find("input").val(newVal);
			spinner.find("input").trigger("change");
		});

	});

	// Export cart object.
	window.cart = cart;
} )( jQuery, window, document, localStorage, CartParams );

function eFormatNumber(n, x, s, c, number) {
	var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
		num = number.toFixed(Math.max(0, ~~n));
	return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
}
