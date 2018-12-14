jQuery( function( $ ) {

	$( '.mw_wp_form input[data-conv-half-alphanumeric="true"]' ).change( function() {
		var txt  = $( this ).val();
		var half = txt.replace( /[Ａ-Ｚａ-ｚ０-９]/g, function( s ) {
			return String.fromCharCode( s.charCodeAt( 0 ) - 0xFEE0 )
		} );
		$( this ).val( half );
	} );

	var file_delete = $( '.mw_wp_form .mwform-file-delete' );
	file_delete.each( function( i, e ) {
		var target = $( e ).data( 'mwform-file-delete' );
		var hidden_field = $( 'input[type="hidden"][name="' + target + '"]' );
		if ( hidden_field.val() ) {
			$( e ).prop( 'disabled', false );
			$( e ).addClass( 'mwform-file-delete-enable' );
		} else {
			$( e ).prop( 'disabled', true );
		}
		$( e ).click( function() {
			var file_field = $( 'input[type="file"][name="' + target + '"]' );
			var new_field = $( file_field[0].outerHTML );
			$( this ).removeClass( 'mwform-file-delete-enable' );
			$( this ).prop( 'disabled', true );
			file_field.replaceWith( new_field );

			hidden_field.parent().fadeOut( 100, function() {
				$( this ).remove();
			} );
		} );
	} );
	$( document ).on( 'change', '.mw_wp_form input[type="file"]', function() {
		var name = $( this ).attr( 'name' );
		file_delete.closest( '[data-mwform-file-delete="' + name + '"]' ).prop( 'disabled', false );
		file_delete.closest( '[data-mwform-file-delete="' + name + '"]' ).addClass( 'mwform-file-delete-enable' );
	} );

	var mw_wp_form_button_no_click = true;
	$( '.mw_wp_form input[type="submit"]' ).click( function() {
		var formElement = $( this ).closest( 'form' )[0];
		if ( formElement && formElement.checkValidity && !formElement.checkValidity() ) {
			return;
		}
		if ( mw_wp_form_button_no_click ) {
			mw_wp_form_button_no_click = false;
		} else {
			$( this ).prop( 'disabled', true );
		}
	} );
} );
