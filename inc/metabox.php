<?php

wp_nonce_field( 'advertising_metabox_nonce_function', 'advertising_metabox_nonce' );

$meta = array(
	'file'      => '',
	'link'      => '',
	'alt'       => '',
	'type'      => '',
);

foreach ( $meta as $k => $v )
{
	$meta[$k] = get_post_meta( $post->ID, $k, true );
}
?>

<div class="row">
	<div class="label">
		<?php _e( 'File', 'da' ); ?>
	</div>
	<div class="input">
		<input type="text" name="file" value="<?php echo $meta['file']; ?>" size="60" class="txt-image">
		<input type="button" value="<?php _e( 'Choose file', 'da' ); ?>" class="btn-upload">
	</div>
	<div class="clear"></div>
</div>

<div class="row">
	<div class="label">
		<?php _e( 'Link', 'da' ); ?>
	</div>
	<div class="input">
		<input type="text" name="link" id="link" value="<?php echo $meta['link']; ?>" size="60">
	</div>
	<div class="clear"></div>
</div>

<div class="row">
	<div class="label">
		<?php _e( 'Alternate Text', 'da' ); ?>
	</div>
	<div class="input">
		<input type="text" name="alt" id="alt" value="<?php echo $meta['alt']; ?>" size="60">
	</div>
	<div class="clear"></div>
</div>

<div class="row">
	<div class="label">
		<?php _e( 'Type', 'da' ); ?>
	</div>
	<div class="input">
		<select name="type">
			<?php
			$types = array(
				'_blank'    => __( 'Open in a new window', 'da' ),
				'_self'     => __( 'Open in a current window', 'da' )
			);
			foreach( $types as $k => $v )
			{
				printf( '
					<option value="%s"%s>%s</option>',
					$k,
					$k == $meta['type'] ? ' selected="selected"' : '',
					$v
				);
			}
			?>
		</select>
	</div>
	<div class="clear"></div>
</div>