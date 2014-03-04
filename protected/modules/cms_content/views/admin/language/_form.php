
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'language-form',
	'enableAjaxValidation'=>false,
)); ?>

<ul>

	<li class="row">
		<?php echo $form->labelEx($model,'short_name',array('class'=>'desc')); ?>
    <div>
		<?php echo $form->textField($model,'short_name',array('class'=>'field text medium')); ?>
		<?php echo $form->error($model,'short_name'); ?>
    </div>
	</li>

	<li class="row">
		<?php echo $form->labelEx($model,'name',array('class'=>'desc')); ?>
    <div>
		<?php echo $form->textField($model,'name',array('class'=>'field text medium')); ?>
		<?php echo $form->error($model,'name'); ?>
    </div>
	</li>

	<li class="row">
		<?php echo $form->labelEx($model,'lang_code',array('class'=>'desc')); ?>
    <div>
		<?php echo $form->textField($model,'lang_code',array('class'=>'field text medium')); ?>
		<?php echo $form->error($model,'lang_code'); ?>
    </div>
	</li>

	<li class="row">
		<?php echo $form->labelEx($model,'sort',array('class'=>'desc')); ?>
    <div>
		<?php echo $form->textField($model,'sort',array('class'=>'field text small')); ?>
		<?php echo $form->error($model,'sort'); ?>
    </div>
	</li>

  <li>
    <div class="col">
        <?php echo $form->checkBox($model,'is_default',array('class'=>'field checkbox')); ?>
        <?php echo $form->labelEx($model,'is_default',array('class'=>'choice')); ?>
    </div>
    <div class="col">
        <?php echo $form->checkBox($model,'is_visible',array('class'=>'field checkbox')); ?>
        <?php echo $form->labelEx($model,'is_visible',array('class'=>'choice')); ?>
    </div>
  </li>   
     

	<li class="buttons">
    <?php echo CHtml::htmlButton('<span class="ui-icon ui-icon-check"></span>'.($model->isNewRecord?Yii::t('backend', 'STR_CREATE'):Yii::t('backend', 'STR_SAVE')),array('class'=>"ui-state-default ui-corner-all ui-button",'type'=>'submit')); ?>  
    <?php echo CHtml::htmlButton('<span class="ui-icon ui-icon-closethick"></span>'.Yii::t('backend', 'STR_CANCEL'),array('class'=>"ui-state-default ui-corner-all ui-button",'onclick'=>"window.location='".CController::createUrl('index')."'")); ?>    
	</li>

</ul>
<?php $this->endWidget(); ?>
