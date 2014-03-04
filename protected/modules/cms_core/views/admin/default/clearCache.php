<?php

  $this->title=Yii::t('backend', 'STR_SYSTEM');
  $this->pageTitle=$this->title.' - '.Yii::app()->name;
  


Yii::app()->clientScript->registerScript
(
  'notice-c',
  "
      notice('".Yii::t('backend',Yii::t('backend','STR_CACHE_CLEARED') )."');  
  ",
	CClientScript::POS_READY
) ;

?>
<div class="column-content-box">
  <div class="content-box content-box-header ui-corner-all">
		<div class="content-box-wrapper"> 
      <h2><?php echo Yii::t('backend', 'STR_CLEAR_CACHE');?></h2>
          <div class="response-msg inf ui-corner-all">
						<span><?php echo Yii::t('backend','STR_NOTICE'); ?></span>
            <?php echo Yii::t('backend', 'STR_CACHE_CLEARED');?>
					</div>    
    </div>              
  </div>
</div>    