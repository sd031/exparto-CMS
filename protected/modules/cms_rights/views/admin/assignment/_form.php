<div class="form">

<?php $form=$this->beginWidget('CActiveForm'); ?>
	
	<div class="row">
		<?php echo $form->dropDownList($model, 'itemname', $itemnameSelectOptions, array('class'=>'field select medium')); ?>
		<?php echo $form->error($model, 'itemname'); ?>
	</div>
	<br />
	<div class="row buttons">
    <button class="ui-state-default ui-corner-all ui-button" type="submit"><?php echo Yii::t('backend', 'STR_ASSIGN'); ?></button>
	</div>

<?php $this->endWidget(); ?>

</div>