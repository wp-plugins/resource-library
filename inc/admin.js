(function( $ ) {
	'use strict';
	var uploader;
	function renderMediaUploader() {
		if ( typeof( uploader ) !== 'undefined' ) {
			uploader.open();
			return;
		}
		uploader = wp.media({
			title: 'Document Resource',
			button: {text:'Select Resource'},
			multiple: false
		});
		uploader.on( 'select', function() {
			var selection = uploader.state().get( 'selection' ).first().toJSON();
			setLocationFormFieldValue( selection.url );
			setTitleFormFieldValue( selection.title );
			setCaptionFormFieldValue( selection.caption );
			setTypeFormFieldValue( selection.subtype );
			console.log(selection);
		});
		uploader.open();
	}
	function setLocationFormFieldValue( value ) {
		$('#mdresourcelib-location').val( value );
	}
	function setFormFieldValue( inputSelector, value ) {
		var elem = $(inputSelector);
		if ( ! elem.val() ) {
			elem.val( value ).focus();
		}
	}
	function setTitleFormFieldValue( value ) {
		setFormFieldValue( '#title', value );
	}
	function setCaptionFormFieldValue( value ) {
		setFormFieldValue( '#mdresourcelib-caption', value );
	}
	function setTypeFormFieldValue( value ) {
		setFormFieldValue( '#mdresourcelib-type', value );
	}
	$( '#mdresourcelib-location-select' ).on( 'click', function( e ) {
		e.preventDefault();
		renderMediaUploader();
	});
})( jQuery );
