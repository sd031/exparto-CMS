<?php
  $this->title=Yii::t('backend', 'Svetainės spalva');
  $this->pageTitle=$this->title.' - '.Yii::app()->name;
?>



<div class="column-content-box">
  <div class="content-box content-box-header ui-corner-all">
		<div class="content-box-wrapper"> 	
    
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'review-form',
	'enableAjaxValidation'=>false,
)); ?>
<ul>

	<li class="row"> 
    <select class="field select small" name="color">
    <?php foreach($colors as $key=>$color):?>
      <option value="<?php echo $key?>" <?php if($key==$val):?> selected<?php endif ?>><?php echo $color?></option>
    <?php endforeach;?>
    </select>
	</li>

  <li class="buttons">                                                                                                                                                                                                                               
    <?php echo CHtml::htmlButton('<span class="ui-icon ui-icon-check"></span>'.Yii::t('backend', 'STR_SAVE'),array('class'=>"ui-state-default ui-corner-all ui-button",'type'=>'submit')); ?>  
    <?php //echo CHtml::htmlButton('<span class="ui-icon ui-icon-closethick"></span>'.Yii::t('backend', 'STR_CANCEL'),array('class'=>"ui-state-default ui-corner-all ui-button",'onclick'=>"window.location='".CController::createUrl('index')."'")); ?>    
  </li>	
</ul>
<?php $this->endWidget(); ?>

    </div>     
      <div style="clear:both"></div>      
    </div>              
  </div>
</div>