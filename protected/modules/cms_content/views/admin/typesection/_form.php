<?php
//jquery UI
Yii::app()->clientScript->registerCoreScript('jquery.ui');

Yii::app()->clientScript->registerScript
(
  'form-tabs',
  '
    $("#form-tabs").tabs({"select": 0});
  ',
	CClientScript::POS_LOAD 
);


if(($model->var>0))
Yii::app()->clientScript->registerScript
(
  'init_section_type',
  "
    $('#secLink').hide();   
    $('#secType').val(1);  
    $('#secVar').val(1);
    $('#secLink select').attr('name','Content_d[alias]');  
  ",
	CClientScript::POS_READY
);
else
Yii::app()->clientScript->registerScript
(
  'init_section_type',
  "
    $('#secList').hide(); 
    $('#secType').val(0);  
    $('#secVar').val(0);   
    $('#aliashidden').attr('name','Content_d[alias]'); 
  ",
	CClientScript::POS_READY
);

Yii::app()->clientScript->registerScript
(
  'section_type',
  "
    $('#secType').change(function(){
      if($(this).val()==0)
      {
        $('#secLink').show();  
        $('#secList').hide(); 
        $('#secVar').val(0);      
        $('#aliashidden').attr('name','Content_d[alias]');
        $('#secLink select').attr('name','Content[alias]');               
      } else
      {
        $('#secLink').hide();  
        $('#secList').show(); 
        $('#secVar').val(1);   
        $('#aliashidden').attr('name','Content[alias]');
        $('#aliashidden').val('');
        $('#secLink select').attr('name','Content_d[alias]');                           
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

<div id="form-tabs">                             
  <ul> 
   <li><a href="#form-main-tab"><?php echo Yii::t('backend','STR_MAIN');?></a></li>
   <li><a href="#form-options-tab"><?php echo Yii::t('backend','STR_OPTIONS');?></a></li>   
  </ul>  

<?php echo $form->hiddenField($model,'type'); ?>
<?php echo CHtml::hiddenField('id',$id); ?>
<?php echo $form->hiddenField($model,'var',array('id'=>'secVar')); ?>
<?php echo $form->hiddenField($model,'alias',array('id'=>'aliashidden')); ?>
<div id="form-main-tab">
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
      <select class="field select small" id="secType">
        <option value="0"><?php echo Yii::t('backend','STR_LINK'); ?></option>
        <option value="1"><?php echo Yii::t('backend','STR_LIST'); ?></option>
      </select>
    </div>
  </li>    
   <li id="secLink">
      <?php echo $form->labelEx($model,'alias',array('class'=>'desc')); ?>
      <div>
        <?php echo $form->dropDownList($model,'alias',array(''=>Yii::t('backend','STR_NONE'))+Content::aliasOptions($model->id),array('class'=>'field select full')); ?>    
        <?php echo $form->error($model,'alias'); ?>
      </div>
   </li> 
   <div id="secList">
    <?php $this->renderPartial('cms_content.views.admin._alias',array('content'=>$model,'form'=>$form)); ?>   
   </div>            
</ul>
</div>
<div id="form-options-tab">
    <?php $this->renderPartial('cms_content.views.admin._options',array('content'=>$model,'form'=>$form)); ?>    
</div>
</div>
<input id="sub-frm-btn" type="submit" name="" style="display:none">
<?php $this->endWidget(); ?>		