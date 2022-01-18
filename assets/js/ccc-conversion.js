(function($){
	$( document ).ready( function($) {
		loadCurrenciesDataFromEndpoint();
		
		var dropbtn = 'button.dropbtn';
		var dropdown_menu = '.dropdown-menu';
		var direction_switch = '.direction-switch';
		var selected_currency_text = 'span.selected-currency-text';
		var search_input = '.dropdown-menu input.search';
		var input_result_left = '.result-left';
		var input_result_right = '.result-right';

		$( document ).mouseup( function(e) {
			
			var dropbtn_container = $( dropbtn );
			var dropdown_menu_container = $( dropdown_menu );
			var search_input_container = $( search_input );
			
			$( search_input ).val('').trigger("change");
			
			if ( ! dropbtn_container.is( e.target ) && ! search_input_container.is( e.target ) && dropbtn_container.has( e.target ).length === 0 ) {
				dropdown_menu_container.hide();
			}
		});

		$( document ).on( 'click', dropbtn, function() {
			$( dropdown_menu ).each((i, element) => $(element).hide() );
			$( this ).parent().find( dropdown_menu ).toggle();
		});

		$( document ).on( 'click', dropdown_menu + ' li', function() {
			var btn_currency_current = $( this ).closest( '.dropdown' ).find( selected_currency_text );

			btn_currency_current.html( $( this ).find( 'a' ).html() );
			btn_currency_current.attr( 'data-symbol', $( this ).find( 'a' ).attr( 'data-symbol' ) );
			btn_currency_current.attr( 'data-id', $( this ).find( 'a' ).attr( 'data-id' ) );

			$( input_result_left ).trigger("input");
		});

		$( document ).on( 'click', direction_switch, function() {
			
			var first = $( $( dropbtn ).find( selected_currency_text )[0] ).clone( true );
			var second = $( $( dropbtn ).find( selected_currency_text )[1] ).clone( true );

			$( dropbtn ).find( selected_currency_text )[0].replaceWith( second[0] );
			$( dropbtn ).find( selected_currency_text )[1].replaceWith( first[0] );

			$( input_result_left ).trigger("input");

		});

		$( document ).on( 'keyup change', search_input, function(e) {
			var filter = e.target.value.toUpperCase();
			var item = $( dropdown_menu ).find( "li" );

			for ( i = 0; i < item.length; i++ ) {
				var txtValue = item[i].textContent || item[i].innerText;

				if ( txtValue.toUpperCase().indexOf( filter ) > -1 ) {
					item[i].style.display = "";
				} else {
					item[i].style.display = "none";
				}
			}
		});

		$( document ).on( 'input', input_result_left, function(e) {
			if ( $( this ).val() == '' ) return false;

			var from_currency = $( this ).parent().parent().find( selected_currency_text ).attr( 'data-symbol' );
			var to_currency = $( input_result_right ).parent().parent().find( selected_currency_text ).attr( 'data-symbol' );
			
			loadConversionDataFromEndpoint( 'left', from_currency, to_currency );
		});

		$( document ).on( 'input', input_result_right, function(e) {
			if ( $( this ).val() == '' ) return false;

			var from_currency = $( this ).parent().parent().find( selected_currency_text ).attr( 'data-symbol' );
			var to_currency = $( input_result_left ).parent().parent().find( selected_currency_text ).attr( 'data-symbol' );
			
			loadConversionDataFromEndpoint( 'right', from_currency, to_currency );
		});
	});

	function loadConversionDataFromEndpoint( input_side, from_currency, to_currency ) {
		var input_result_right, input_result_left;
		
		if ( input_side == 'left') {
			var input_result_left = 'input.result-left';
			var input_result_right = 'input.result-right';
			
		} else if( input_side == 'right' ) {
			var input_result_left = 'input.result-right';
			var input_result_right = 'input.result-left';
		}

		var amount = $( input_result_left ).val();
		var to_convert = $( input_result_right );

		$.get( ccc_ajax.url_endpoint, { action: "ccc_get_data_conversion", ccc_nonce: ccc_ajax.nonce, amount: amount, symbol: from_currency, convert: to_currency } )
			.done(function( data ) {
				var json_response = JSON.parse( data.result );
				to_convert.val( parseFloat( json_response.data.quote[ to_currency ].price ).toFixed( 5 ) );
			});
	}
	
	function loadCurrenciesDataFromEndpoint() {
	
		var dropdown_content = $( '#wp-crypto-currency-converter .dropdown-menu ul' );
		var dropbtn = 'button.dropbtn';
		var selected_currency_text = 'span.selected-currency-text';
	
		$.get( ccc_ajax.url_endpoint, { action: "ccc_get_data_currencies", ccc_nonce: ccc_ajax.nonce } )
			.done(function( data ) {
				var json_response = JSON.parse( data.result );
				var data_currencies = json_response.data_currencies.data;
				var metadata = json_response.metadata.data;
	
				$( dropbtn ).find( selected_currency_text ).each( function( i, element ) {
					if ( i > 1 ) return false;
	
					$( element ).attr( 'data-symbol', data_currencies[i].symbol );
					$( element ).attr( 'data-id', data_currencies[i].id );
					$( element ).html( '<img width="16px" height="16px" src="' + metadata[data_currencies[i].id].logo + '"/><label>' + data_currencies[i].symbol + '</label><span>- ' + data_currencies[i].name + '</span>' );
				} );
	
				var from_currency =  data_currencies[0].symbol;
				var to_currency =  data_currencies[1].symbol;
	
				loadConversionDataFromEndpoint( 'left', from_currency, to_currency );
	
				data_currencies.forEach( function( item ) {       
					dropdown_content.append( '<li class="item-crypto"><a class="flag flag-us" data-symbol="' + item.symbol + '" data-id="' + item.id + '"><img width="16px" height="16px" src="' + metadata[item.id].logo + '"/><label>' + item.symbol + '</label><span>- ' + item.name + '</span></a></li>' );
				});
			}, 'json' );
	}
}(jQuery));