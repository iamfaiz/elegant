$(function(){

	// Include partials
	$('*[data-includefile]').each( function() {
		var filename = $(this).data('includefile');
		var $this = $(this);
		$.ajax({
			method: 'get',
			url: filename,
			success: function(data) {
				$this.after(data);
				$this.remove();
			}
		});
	} );
	
});