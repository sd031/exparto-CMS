<?php
  $this->title=Yii::t('backend','STR_ERROR');
  $this->pageTitle=$this->title.' - '.Yii::app()->name;
?>

<!--<div class="inner-page-title">
<h2><?php echo Yii::t('backend', 'STR_ERROR'); ?></h2>
<span><?php echo $code; ?></span>
</div>-->
				
<div class="content-box">

<div class="response-msg error ui-corner-all">
		<span><?php echo Yii::t('backend', 'STR_ERROR'),' ',$code; ?></span>
		<?php echo CHtml::encode($message); ?>
</div>
				
</div>