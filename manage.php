<?php
global $afgtc_options;
?>
<div class="wrap">
  <?php screen_icon(); ?>
  <h2><?php _e('Affogato Comments for WordPress Options'); ?></h2>
	<br/>
	<?php
		if ($_GET['settings-updated']) {
			echo "<p>Updated</p>";
		}
	?>
	<form method="post" action="options.php">
		<?php settings_fields('afgtComments_options'); ?>
		<div id="poststuff" class="postbox">
			<h3><?php _e('Basic Setting'); ?></h3>
				<div class="inside">
				<p><input type="text" id="afgtComments_appKey" name="afgtComments[appKey]" value="<?php echo $afgtc_options['appKey']; ?>"/><label for="afgtComments_appKey">APP KEY</label></p>
				</div>
		</div>
		<input type="submit" class="button-primary" value="Update Options">
	</form>
</div>
