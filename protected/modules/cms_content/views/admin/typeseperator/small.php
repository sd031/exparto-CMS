<div class="content-box content-box-header">
  <div class="content-box-wrapper">
    <div class="content">
    <form>
    <ul>
    <li>
    <label class="desc"><?php echo $content->getAttributeLabel('rec_created'); ?>:</label>
 		<div><?php echo $content->rec_created; ?></div>
 		</li>
 		<?php if($content->rec_modified):?>
 		<li>
    <label class="desc"><?php echo $content->getAttributeLabel('rec_modified'); ?>:</label>
 		<div><?php echo $content->rec_modified; ?></div>
    </li> 	
    <?php endif;?>
    </ul>
    </form>	
    </div>
	</div>
</div> 
