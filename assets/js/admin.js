jQuery( function( $ )
{
	$( '.btn-upload' ).on( 'click', function( e )
	{
		var $this = $( this );

		wp.media.editor.open();

		wp.media.editor.send.attachment = function( props, attachment )
		{
			$this.closest( '.input' ).find( '.txt-image' ).val( attachment.url );
		}
	} );
} );