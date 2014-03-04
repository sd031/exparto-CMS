<div id="<?php echo $this->containerID; ?>"></div>

<script type="text/javascript">
//<![CDATA[
jQuery(document).ready(function() {
	jQuery('#<?php echo $this->containerID; ?>').fileTree({
		root:'<?php echo $this->root; ?>',
		script:'<?php echo $script; ?>',
		folderEvent:'<?php echo $this->folderEvent; ?>',
		expandSpeed:<?php echo $this->expandSpeed; ?>,
		collapseSpeed:<?php echo $this->collapseSpeed; ?>,
		multiFolder:<?php echo $this->multiFolder ? 'true' : 'false'; ?>,
		<?php if(!empty($this->expandEasing)) : ?>
		expandEasing:'<?php echo $this->expandEasing; ?>',
		<?php endif; ?>
		<?php if(!empty($this->collapseEasing)) : ?>
		collapseEasing:'<?php echo $this->collapseEasing; ?>',
		<?php endif; ?>
		loadMessage:'<?php echo $this->loadMessage; ?>'
	}, function(f){
		<?php if(empty($this->callbackFunction)) : ?>
		alert(f);
		<?php else : ?>
		<?php echo substr($this->callbackFunction,-1) == ';' ? $this->callbackFunction : $this->callbackFunction.';'; ?>
		<?php endif; ?>
	});
});
//]]>>
</script>