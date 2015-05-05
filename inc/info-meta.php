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
		<?php $mb->the_field('caption'); ?>
		<label for="<?php $mb->the_name(); ?>"><?php _ex( 'Caption', 'document description', 'mdresourcelib' ) ?>:</label>
		<input id="mdresourcelib-caption" type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>">
		<p><?php _e( 'Short description of the document', 'mdresourcelib' ) ?></p>
	</div>
	<div class="wpalchemy-field">
		<?php $mb->the_field('type'); ?>
		<label for="<?php $mb->the_name(); ?>"><?php _ex( 'Type', 'document type', 'mdresourcelib' ) ?>:</label>
		<input id="mdresourcelib-type" type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>">
		<p><?php _e( 'The type of document (e.g. PDF, DOC, ODT)', 'mdresourcelib' ) ?></p>
	</div>
</div>
