<div class="form span-12 first">

<?php if( $model->scenario==='update' ): ?>

	<h3><?php echo Rights::getAuthItemTypeName($model->type); ?></h3>

<?php endif; ?>
	
<?php $form=$this->beginWidget('CActiveForm'); ?>
<ul>
	<li class="row">
		<?php echo $form->labelEx($model, 'name',array('class'=>'desc')); ?>
    <div>
		<?php echo $form->textField($model, 'name', array('maxlength'=>255,'class'=>'field text medium')); ?>
		<?php echo $form->error($model, 'name'); ?>
		<p class="hint"><?php //echo Rights::t('core', 'Do not change the name unless you know what you are doing.'); ?></p>
    </div>
	</li>

	<li class="row">
		<?php echo $form->labelEx($model, 'description',array('class'=>'desc')); ?>
    <div>
		<?php echo $form->textField($model, 'description', array('maxlength'=>255, 'class'=>'field text medium')); ?>
		<?php echo $form->error($model, 'description'); ?>
		<p class="hint"><?php //echo Rights::t('core', 'A descriptive name for this item.'); ?></p>
    </div>
	</li>

	<?php if( Rights::module()->enableBizRule===true ): ?>

		<li class="row">
			<?php echo $form->labelEx($model, 'bizRule',array('class'=>'desc')); ?>
      <div>
			<?php echo $form->textField($model, 'bizRule', array('maxlength'=>255, 'class'=>'field text medium')); ?>
			<?php echo $form->error($model, 'bizRule'); ?>
			<p class="hint"><?php //echo Rights::t('core', 'Code that will be executed when performing access checking.'); ?></p>
      </div>
		</li>

	<?php endif; ?>

	<?php if( Rights::module()->enableBizRule===true && Rights::module()->enableBizRuleData ): ?>

		<li class="row">
			<?php echo $form->labelEx($model, 'data',array('class'=>'desc')); ?>
      <div>
			<?php echo $form->textField($model, 'data', array('maxlength'=>255, 'class'=>'field text medium')); ?>
			<?php echo $form->error($model, 'data'); ?>
			<p class="hint"><?php //echo Rights::t('core', 'Additional data available when executing the business rule.'); ?></p>
      </div>
		</li>

	<?php endif; ?>

	<li class="buttons">
		<?php //echo CHtml::submitButton(Rights::t('core', 'Save')); ?>  <?php //echo CHtml::link(Rights::t('core', 'Cancel'), Yii::app()->user->rightsReturnUrl); ?>
    
    <?php echo CHtml::htmlButton('<span class="ui-icon ui-icon-check"></span>'.Yii::t('backend', 'STR_SAVE'),array('class'=>"ui-state-default ui-corner-all ui-button",'type'=>'submit')); ?>  
    <?php echo CHtml::htmlButton('<span class="ui-icon ui-icon-closethick"></span>'.Yii::t('backend', 'STR_CANCEL'),array('class'=>"ui-state-default ui-corner-all ui-button",'onclick'=>"window.location='".Yii::app()->user->rightsReturnUrl."'")); ?>      
    
	</li>
</ul>
<?php $this->endWidget(); ?>

</div>