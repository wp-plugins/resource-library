jQuery(function($){
	// responsive containers .. localresponsive
	$('.resrclib-container').each(function(){
		if ( $('.resrclib-tab:first', this).offset().top != $('.resrclib-tab:last', this).offset().top ) {
			$('.resrclib-tabs', this).addClass('resrclib-smaller');
		}
		if ( $(this).width() <= 300 ) {
			$(this).addClass('resrclib-list');
		}
	});
	// tab/panel ui
	$('.resrclib-tab').click(function(e){
		e.preventDefault();
		var container = $(this).closest('.resrclib-container');
		$('.resrclib-tab', container).removeClass('active');
		$(this).addClass('active');
		$('.resrclib-panel', container).hide();
		$('.resrclib-panel-' + $(this).data('id'), container).show();
	}).filter(':first:visible').trigger('click');
	$('.resrclib-panel-title', '.resrclib-list').click(function(e){
		e.preventDefault();
		var container = $(this).closest('.resrclib-container');
		var id = $(this).data('id');
		if ( $(this).hasClass('active') ) {
			$(this).removeClass('active');
			$('.resrclib-panel-' + id, container).hide();
		} else {
			$(this).addClass('active');
			$('.resrclib-panel-' + id, container).show();
		}
	}).filter(':first').trigger('click');
});
