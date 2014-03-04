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
  'alias',
  '
  $("#alias-container").show();      
  ',
	CClientScript::POS_LOAD 
);

?>

<?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'category-form',
        'enableAjaxValidation'=>true,
        'clientOptions'=>array(
          'validationUrl'=>$this->createUrl('catAjaxValidation'),
          'validateOnSubmit'=>true,
          'afterValidate'=>'js:function(form, data, hasError){return false;}',
        ) 
)); ?>
<div id="form-tabs" style="height:auto">                             
  <ul> 
   <li><a href="#form-main-tab"><?php echo Yii::t('backend','STR_MAIN');?></a></li>
   <li><a href="#form-options-tab"><?php echo Yii::t('backend','STR_OPTIONS');?></a></li>   
  </ul>  
<?php echo $form->hiddenField($content,'type'); ?>
<?php echo CHtml::hiddenField('id',$id); ?>
<?php echo $form->hiddenField($content,'alias',array('id'=>'aliashidden')); ?>
<div id="form-main-tab">
<ul>
  <li>
    <?php echo $form->labelEx($content,'name',array('class'=>'desc')); ?>
    <div>
      <?php echo $form->textField($content,'name',array('class'=>'field text medium','maxlength'=>128)); ?>
      <?php echo $form->error($content,'name'); ?>
    </div>
  </li>
  <?php $this->renderPartial('cms_content.views.admin._alias',array('content'=>$content,'form'=>$form)); ?>             
  <!--<li class="buttons">
    <?php echo CHtml::htmlButton('<span class="ui-icon ui-icon-closethick"></span>'.Yii::t('backend', 'STR_CANCEL'),array('class'=>"ui-state-default ui-corner-all ui-button float-right",'id'=>'content-cancel')); ?>
    <?php echo CHtml::htmlButton('<span class="ui-icon ui-icon-check"></span>'.($content->isNewRecord?Yii::t('backend', 'STR_CREATE'):Yii::t('backend', 'STR_SAVE')),array('class'=>"ui-state-default ui-corner-all ui-button float-right",'type'=>'submit','id'=>$content->isNewRecord?'content-create':'content-update')); ?>
    <?php echo !$content->isNewRecord?CHtml::htmlButton('<span class="ui-icon ui-icon-trash" style="margin-top:-1px"></span>'.Yii::t('backend', 'STR_DELETE'),array('class'=>"ui-state-default ui-corner-all ui-button",'id'=>'content-delete')):''; ?>
  </li>-->
</ul>
</div>
<div id="form-options-tab">
    <?php $this->renderPartial('cms_content.views.admin._options',array('content'=>$content,'form'=>$form)); ?>    
</div>
</div>
<input id="sub-frm-btn" type="submit" name="" style="display:none">
<?php $this->endWidget(); ?>			