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
		var count = Object.keys(cart.data).length,
			idcart = '',
			add_cart_group = $(this).parent();
		count++;

		const productInfo = $( this ).data( 'info' );
		var quantity = $( '.quantity_products', add_cart_group ).val();

		// console.log(quantity);
		$.each( cart['data'], function( key, value ) {
			if ( key == productInfo['id'] ) {
				idcart = key;
			}
		});
		
		if ( idcart == '' ) {
			$ ( '.items', '.dropdown-toggle.mini-cart' ).html( count );
			cart.addProduct( productInfo, quantity );
		}

		var add_success = $( this ).data('type');
        setTimeout(function(){
			$( '.load-icon', '.add-to-cart' ).remove();
			new $.notification('<i class="fa fa-shopping-cart"></i> ' + add_success , {"class" : 'alert-notification', timeout : 2000, click : null, close : false});
		}, 1000);

		$( this ).addClass('view-cart');
		var button = $( this ).data('type')
		if ( button ) {
			$( this, '.view-cart' ).attr('title','Xem giỏ hàng');
		}
	}
	function clickviewcart( e ) {
		e.preventDefault();
		var link = `${CartParams.checkoutUrl}`;
		location.href = link;
	}

	cart.load();

	$( function() {
		$( document ).on( 'click', '.add-to-cart.view-cart', clickviewcart );
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

Number.prototype.format = function(n, x, s, c) {
    var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
        num = this.toFixed(Math.max(0, ~~n));

    return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
};