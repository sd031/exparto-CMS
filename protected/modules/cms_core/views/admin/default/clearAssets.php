<?php

  $this->title=Yii::t('backend', 'STR_CLEAR_ASSETS');
  $this->pageTitle=$this->title.' - '.Yii::app()->name;
  
?>

<div class="column-content-box">
  <div class="content-box content-box-header ui-corner-all">
    <div class="ui-state-default ui-corner-top ui-box-header">
      <span class="ui-icon float-left ui-icon-newwin"></span>
      <?php echo Yii::t('backend', 'STR_CLEAR_ASSETS');?>
    </div> 
		<div class="content-box-wrapper"> 
          <div class="response-msg inf ui-corner-all">
						<span><?php echo Yii::t('backend','STR_NOTICE'); ?></span>
            <?php echo Yii::t('backend', 'STR_ASSETS_CLEARED');?>
					</div>    
    </div>              
  </div>
</div>    