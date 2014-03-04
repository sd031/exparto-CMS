
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'cms-user-form',
	'enableAjaxValidation'=>false,
)); ?>

<ul>

	<li class="row">
		<?php echo $form->labelEx($model,'username',array('class'=>'desc')); ?>
    <div>
		<?php echo $form->textField($model,'username',array('size'=>32,'maxlength'=>32,'class'=>'field text medium')+($model->isNewRecord?array():array('disabled'=>'disabled'))); ?>
		<?php echo $form->error($model,'username'); ?>
    </div>
	</li>

	<li class="row">
		<?php echo $form->labelEx($model,'passwordNew',array('class'=>'desc')); ?>
		<div>
		<?php echo $form->passwordField($model,'passwordNew',array('size'=>60,'maxlength'=>128,'class'=>'field text medium')); ?>
		<?php echo $form->error($model,'passwordNew'); ?>
		</div>
	</li>
  
	<li class="row">
		<?php echo $form->labelEx($model,'passwordRepeat',array('class'=>'desc')); ?>
		<div>
		<?php echo $form->passwordField($model,'passwordRepeat',array('size'=>60,'maxlength'=>128,'class'=>'field text medium')); ?>
		<?php echo $form->error($model,'passwordRepeat'); ?>
		</div>
	</li>  

	<li class="row">
		<?php echo $form->labelEx($model,'language',array('class'=>'desc')); ?>
    <div>
		<?php echo $form->dropDownList($model,'language',CmsLanguage::getOptions(),array('class'=>'field select small')); ?>
		<?php echo $form->error($model,'language'); ?>
    </div>
	</li>

	<li class="row">
		<?php echo $form->labelEx($model,'first_name',array('class'=>'desc')); ?>
    <div>
		<?php echo $form->textField($model,'first_name',array('size'=>60,'maxlength'=>64,'class'=>'field text medium')); ?>
		<?php echo $form->error($model,'first_name'); ?>
    </div>
	</li>

	<li class="row">
		<?php echo $form->labelEx($model,'last_name',array('class'=>'desc')); ?>
    <div>
		<?php echo $form->textField($model,'last_name',array('size'=>60,'maxlength'=>64,'class'=>'field text medium')); ?>
		<?php echo $form->error($model,'last_name'); ?>
    </div>
	</li>

	<li class="row">
		<?php echo $form->labelEx($model,'email',array('class'=>'desc')); ?>
    <div>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>64,'class'=>'field text medium')); ?>
		<?php echo $form->error($model,'email'); ?>
    </div>
	</li>

	<li class="row">
		<?php echo $form->labelEx($model,'info',array('class'=>'desc')); ?>
    <div>
		<?php echo $form->textArea($model,'info',array('rows'=>6, 'cols'=>50,'class'=>'field textarea small')); ?>
		<?php echo $form->error($model,'info'); ?>
    </div>
	</li>

  <li class="buttons">                                                                                                                                                                                                                               
    <?php echo CHtml::htmlButton('<span class="ui-icon ui-icon-check"></span>'.($model->isNewRecord?Yii::t('backend', 'STR_CREATE'):Yii::t('backend', 'STR_SAVE')),array('class'=>"ui-state-default ui-corner-all ui-button",'type'=>'submit')); ?>  
    <?php echo CHtml::htmlButton('<span class="ui-icon ui-icon-closethick"></span>'.Yii::t('backend', 'STR_CANCEL'),array('class'=>"ui-state-default ui-corner-all ui-button",'onclick'=>"window.location='".CController::createUrl('index')."'")); ?>    
  </li>

</ul>

<?php $this->endWidget(); ?>