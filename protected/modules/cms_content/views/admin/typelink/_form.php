<?php

if(is_numeric($model->alias))
Yii::app()->clientScript->registerScript
(
  'init_link_type',
  "
    $('#external').hide();   
    $('#linkType').val(0);  
    $('#externalAlias').attr('name','Content_d[alias]');
  ",
	CClientScript::POS_READY
);
else
Yii::app()->clientScript->registerScript
(
  'init_link_type',
  "
    $('#internal').hide(); 
    $('#linkType').val(1);  
    $('#internalAlias').attr('name','Content_d[alias]');      
  ",
	CClientScript::POS_READY
);

Yii::app()->clientScript->registerScript
(
  'link_type',
  "
    $('#linkType').change(function(){
      if($(this).val()==0)
      {
        $('#internal').show();  
        $('#external').hide(); 
        $('#externalAlias').attr('name','Content_d[alias]');
        $('#internalAlias').attr('name','Content[alias]');        
      } else
      {
        $('#internal').hide();  
        $('#external').find('input').val('');
        $('#external').show(); 
        $('#externalAlias').attr('name','Content[alias]');
        $('#internalAlias').attr('name','Content_d[alias]');                
      }
    });
  ",
	CClientScript::POS_READY
);

?>

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
  <li>
    <label class="desc"><?php echo Yii::t('backend','STR_TYPE'); ?></label>
    <div>
      <select class="field select small" id="linkType">
        <option value="0"><?php echo Yii::t('backend','STR_INTERNAL_LINK'); ?></option>
        <option value="1"><?php echo Yii::t('backend','STR_EXTERNAL_LINK'); ?></option>
      </select>
    </div>
  </li>     
  <li id="external">
    <?php echo $form->labelEx($model,'alias',array('class'=>'desc')); ?>
    <div>
      <?php echo $form->textField($model,'alias',array('id'=>'externalAlias','class'=>'field text medium','maxlength'=>128)); ?>
      <?php echo $form->error($model,'alias'); ?>
    </div>
  </li>  
  <li id="internal">
    <?php echo $form->labelEx($model,'alias',array('class'=>'desc')); ?>
    <div>
      <?php echo $form->dropDownList($model,'alias',Content::aliasOptions($model->root),array('id'=>'internalAlias','class'=>'field select full')); ?>    
      <?php echo $form->error($model,'alias'); ?>
    </div>
  </li>   
  <li>
    <?php echo $form->labelEx($model,'link_description',array('class'=>'desc')); ?>
      <div>
        <?php echo $form->textArea($model,'link_description',array('class'=>'field text full')); ?>
        <?php echo $form->error($model,'link_description'); ?>
      </div>
  </li>
  <li>
      <?php echo $form->labelEx($model,'link_target',array('class'=>'desc')); ?>
      <div>
        <?php echo $form->dropDownList($model,'link_target',Content::linkTargetOptions(),array('class'=>'field select small')); ?>
        <?php echo $form->error($model,'link_target'); ?>
      </div>
  </li>    
</ul>
<input id="sub-frm-btn" type="submit" name="" style="display:none">
<?php $this->endWidget(); ?>			