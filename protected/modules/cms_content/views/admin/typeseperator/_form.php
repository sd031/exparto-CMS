<?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'section-form',
        'enableAjaxValidation'=>true,
        'clientOptions'=>array(
          'validationUrl'=>$this->createUrl('ajaxValidation'),
          'validateOnSubmit'=>true,
          'afterValidate'=>'js:function(form, data, hasError){return false;}',
        ) 
)); ?>

<?php echo $form->hiddenField($model,'type'); ?>
<?php echo CHtml::hiddenField('id',$id); ?>
<ul>
  <li>
    <?php echo $form->labelEx($model,'name',array('class'=>'desc')); ?>
    <div>
      <?php echo $form->textField($model,'name',array('class'=>'field text medium','maxlength'=>128)); ?>
      <?php echo $form->error($model,'name'); ?>
    </div>
  </li>
  <li class="buttons">
    <?php echo CHtml::htmlButton('<span class="ui-icon ui-icon-closethick"></span>'.Yii::t('backend', 'STR_CANCEL'),array('class'=>"ui-state-default ui-corner-all ui-button float-right",'id'=>'content-cancel')); ?>  
    <?php echo CHtml::htmlButton('<span class="ui-icon ui-icon-check"></span>'.($model->isNewRecord?Yii::t('backend', 'STR_CREATE'):Yii::t('backend', 'STR_SAVE')),array('class'=>"ui-state-default ui-corner-all ui-button float-right",'type'=>'submit','id'=>$model->isNewRecord?'content-create':'content-update')); ?>
    <?php echo !$model->isNewRecord?CHtml::htmlButton('<span class="ui-icon ui-icon-trash" style="margin-top:-1px"></span>'.Yii::t('backend', 'STR_DELETE'),array('class'=>"ui-state-default ui-corner-all ui-button",'id'=>'content-delete')):''; ?>
  </li>
</ul>
<input id="sub-frm-btn" type="submit" name="" style="display:none">
<?php $this->endWidget(); ?>			