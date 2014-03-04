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
    
Yii::app()->clientScript->registerScript
(
  'codemirror',
  '
      var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
        lineNumbers: true,
        matchBrackets: true,
        mode: "application/x-httpd-php",
        indentUnit: 4,
        indentWithTabs: true,
        enterMode: "keep",
        tabMode: "shift"
      });  
  ',
	CClientScript::POS_END
); 

Yii::app()->clientScript->registerScript
(
  'codemirror_save',
  '
    $("#sub-frm-btn").unbind();
    $("#sub-frm-btn").click(function(){
        editor.save();
    }) 
  ',
	CClientScript::POS_READY
); 
 
?>

<?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'tpl-form',
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
<?php echo $form->hiddenField($content,'type'); ?>
<?php echo CHtml::hiddenField('id',$id,array('id'=>'idhidden')); ?>
<div id="form-main-tab">
<ul>
  <li>
    <?php echo $form->labelEx($content,'name',array('class'=>'desc')); ?>
    <div>
      <?php echo $form->textField($content,'name',array('class'=>'field text full','maxlength'=>128,'id'=>'namebox')); ?>
      <?php echo $form->error($content,'name'); ?>
    </div>
  </li>    
  <li>
    <?php echo $form->labelEx($tpl,'text',array('class'=>'desc')); ?>   
  </li>         
</ul>  
<?php echo $form->textArea($tpl,'text',array('style'=>'height:500px;width:98%','id'=>'code')); ?>
</div>    
<div id="form-options-tab">
    <?php $this->renderPartial('cms_content.views.admin._options',array('content'=>$content,'form'=>$form)); ?>    
</div>
</div>
<input id="sub-frm-btn" type="submit" name="" style="display:none">
<?php $this->endWidget(); ?>		
