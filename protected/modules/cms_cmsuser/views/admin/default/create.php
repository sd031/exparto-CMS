<?php
  $this->title=Yii::t('backend','STR_USERS');
  $this->pageTitle=$this->title.' - '.Yii::app()->name;
?>

<div class="column-content-box">
  <div class="content-box content-box-header ui-corner-all">
    
		<div class="content-box-wrapper"> 
    <h2><?php echo Yii::t('backend','STR_CREATE');?></h2>
<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>


    </div>     
      <div style="clear:both"></div>      
    </div>              
  </div>
</div>