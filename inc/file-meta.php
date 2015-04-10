<style>
.wpalchemy-meta input[type="text"] {
	width:100%;
}
.wpalchemy-field label {
	font-weight:bold;
}
.wpalchemy-field label span {
	color:#999;
	font-weight:normal;
}
.wpalchemy-field p {
	margin-top:0;
	color:#999;
	font-size:0.9em;
}
</style>

<div class="wpalchemy-meta">
	<div class="wpalchemy-field">
		<?php $mb->the_field('location'); ?>
		<label for="<?php $mb->the_name(); ?>"><?php _ex( 'Location', 'document location url', 'mdresourcelib' ) ?>:</label>
		<input id="mdresourcelib-location" type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>">
		<p><?php _e( 'The URL to the location of this document.', 'mdresourcelib' ) ?></p>
	</div>
	<p class="hide-if-no-js">
		<a href="javascript:;" class="button btn" id="mdresourcelib-location-select"><?php _e( 'Select Document Resource', 'mdresourcelib' ) ?></a>
	</p>
</div>
