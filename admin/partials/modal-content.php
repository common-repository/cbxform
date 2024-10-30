<?php
	/**
	 * IFrame Modal Content Page
	 * This is where you'd build your content and because it's referenced using a wp_ajax method, we have access
	 * to the complete WordPress system, can submit the page to itself ( so long as you preserve the action GET
	 * parameter ) or whatever else you need.
	 *
	 * Expected GET values:
	 *          [action] => modal_frame_content
	 *          [post_id] => ( the ID of the currently active post )
	 */
?><!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?php _e( 'CBX Form Import Modal' , 'cbxform' ); ?></a></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php
	/**
	 * We call wp_print_styles ourselves, in order to replicate the functionality of wp-admin pages.
	 */
	wp_print_styles();
	?>
</head>
<body>

<section class="main" role="main">

	<div class="cbx-popup">
		<header>
			<h1><?php _e( 'Import Forms' , 'cbxform' ); ?></h1>
		</header>
		<?php
		$id = "fileid";
		$svalue = "";
		?>

		<div id="drop-target">
			<div class="drag-drop-inside">
				<div class="cbx-vertical-area">
					<div class="cbx-vertical">
						<p class="drag-drop-info"><?php _e( 'Drop your file to import' , 'cbxform' ); ?></p>
						<p><?php _e( 'or' , 'cbxform' ); ?></p>
						<div class="drag-drop-buttons">
							<input type="hidden" name="<?php echo $id; ?>" id="<?php echo $id; ?>" value="<?php echo $svalue; ?>" />
							<div class="plupload-upload-uic hide-if-no-js" id="<?php echo $id; ?>plupload-upload-ui">
								<input id="<?php echo $id; ?>plupload-browse-button" type="button" value="<?php esc_attr_e('Select File','cbxform'); ?>" class="button" />
								<span class="ajaxnonceplu" id="ajaxnonceplu<?php echo wp_create_nonce($id . 'pluploadan'); ?>"></span>
								<div class="filelist"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<p class="cbx-msg cbx-success"></p>
		<p class="cbx-msg cbx-failure"></p>

		<div class="clear"></div>

		<!--<footer>
			<div class="inner text-right">
				<button id="btn-cancel" class="button-primary button-large"><?php /*_e( 'Cancel' , 'cbxform' ); */?></button>
			</div>
		</footer>-->
	</div>
</section>
<?php
	/**
	 * We call wp_print_scripts ourselves, in order to replicate the functionality of wp-admin pages.
	 * However, this may need to be expanded to allow for scripts to appear in the head (such as modernizr or shim).
	 */
	wp_print_scripts();
?>
</body>
</html>