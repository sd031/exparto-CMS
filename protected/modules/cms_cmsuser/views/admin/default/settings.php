<?php
  $this->title=Yii::t('backend', 'STR_ACCOUNT_SETTINGS');
  $this->pageTitle=$this->title.' - '.Yii::app()->name;
?>

<?php if(Yii::app()->user->hasFlash('user')): 

  Yii::app()->clientScript->registerScript
  (
    'flash-n',
    "
        notice('".Yii::t('backend',Yii::app()->user->getFlash('user'))."');  
    ",
  	CClientScript::POS_READY
  ) ;
        	 			
endif; ?>    


<div class="column-content-box">
  <div class="content-box content-box-header ui-corner-all">
		<div class="content-box-wrapper"> 	
    
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'review-form',
	'enableAjaxValidation'=>false,
)); ?>
<ul>

	<li class="row">
		<?php echo $form->labelEx($user,'language',array('class'=>'desc')); ?>
    <div>
		<?php echo $form->dropDownList($user,'language',CmsLanguage::getOptions(),array('class'=>'field select small')); ?>
		<?php echo $form->error($user,'language'); ?>
    </div>
	</li>

	<li class="row">
		<?php echo $form->labelEx($user,'email',array('class'=>'desc')); ?>
		<div>
		<?php echo $form->textField($user,'email',array('size'=>60,'maxlength'=>128,'class'=>'field text small')); ?>
		<?php echo $form->error($user,'email'); ?>
		</div>
	</li>
	<!--<li class="row">
		<?php echo $form->labelEx($user,'passwordOld',array('class'=>'desc')); ?>
		<div>
		<?php echo $form->passwordField($user,'passwordOld',array('size'=>60,'maxlength'=>128,'class'=>'field text small')); ?>
		<?php echo $form->error($user,'passwordOld'); ?>
		</div>
	</li>-->
	<li class="row">
		<?php echo $form->labelEx($user,'passwordNew',array('class'=>'desc')); ?>
		<div>
		<?php echo $form->passwordField($user,'passwordNew',array('size'=>60,'maxlength'=>128,'class'=>'field text small')); ?>
		<?php echo $form->error($user,'passwordNew'); ?>
		</div>
	</li>
	<li class="row">
		<?php echo $form->labelEx($user,'passwordRepeat',array('class'=>'desc')); ?>
		<div>
		<?php echo $form->passwordField($user,'passwordRepeat',array('size'=>60,'maxlength'=>128,'class'=>'field text small')); ?>
		<?php echo $form->error($user,'passwordRepeat'); ?>
		</div>
	</li>    
  <li class="buttons">                                                                                                                                                                                                                               
    <?php echo CHtml::htmlButton('<span class="ui-icon ui-icon-check"></span>'.($user->isNewRecord?Yii::t('backend', 'STR_CREATE'):Yii::t('backend', 'STR_SAVE')),array('class'=>"ui-state-default ui-corner-all ui-button",'type'=>'submit')); ?>  
    <?php //echo CHtml::htmlButton('<span class="ui-icon ui-icon-closethick"></span>'.Yii::t('backend', 'STR_CANCEL'),array('class'=>"ui-state-default ui-corner-all ui-button",'onclick'=>"window.location='".CController::createUrl('index')."'")); ?>    
  </li>	
</ul>
<?php $this->endWidget(); ?>

    </div>     
      <div style="clear:both"></div>      
    </div>              
  </div>
</div>