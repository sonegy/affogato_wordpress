<div id="affo_comments" style="margin:0 0 10px"></div>
<script type="text/javascript" src="<?php echo AFFOGATO_PLUGIN_URL; ?>"></script>
<script type="text/javascript">
	SKP.commentsPlugin({
			target_id: 'affo_comments',
			op_app_key: '<?php echo AFFOGATO_APP_KEY; ?>',
			page_id: '<?php echo afgt_page_id(); ?>',
			is_responsive: true
	});
</script>
