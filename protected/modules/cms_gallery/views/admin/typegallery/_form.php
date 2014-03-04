<?php

//jquery UI
Yii::app()->clientScript->registerCoreScript('jquery.ui');


Yii::app()->clientScript->registerScript
(
  'gallery-tabs',
  '
    $("#gallery-tabs").tabs({"select": 0});    
  ',
	CClientScript::POS_LOAD 
);


$params=$content->getParams($this->module);

$form=$this->beginWidget('CActiveForm', array(
        'id'=>'gallery-form',
        'enableAjaxValidation'=>true,
        'enableClientValidation'=>false,
        'clientOptions'=>array(
          'validationUrl'=>$this->createUrl('ajaxValidation'),
          'validateOnSubmit'=>true,
          'afterValidate'=>'js:function(form, data, hasError){return false;}',     
        ) 
)); 
?>

<div id="gallery-tabs">                             
  <ul> 
   <li><a href="#gallery-main-tab"><?php echo Yii::t('backend','STR_MAIN');?></a></li>
   <li><a href="#gallery-options-tab"><?php echo Yii::t('backend','STR_OPTIONS');?></a></li>   
  </ul>                         

<?php echo $form->hiddenField($content,'type'); ?>
<?php echo $form->hiddenField($content,'alias',array('id'=>'aliashidden')); ?>
<?php echo CHtml::hiddenField('id',$id,array('id'=>'idhidden')); ?>
  <div id="gallery-main-tab">
    <ul>
      <li>
        <?php echo $form->labelEx($content,'name',array('class'=>'desc')); ?>
        <div>
          <?php echo $form->textField($content,'name',array('class'=>'field text full','maxlength'=>128)); ?>
          <?php echo $form->error($content,'name'); ?>
        </div>
      </li>
      <?php $this->renderPartial('cms_content.views.admin._alias',array('content'=>$content,'form'=>$form)); ?>            
      </ul>
         
    <?php $this->renderPartial('cms_gallery.views.admin._gallery',array('id'=>$id,'type'=>'gallery','template_name'=>$content->template_name,'params'=>$params)); ?>  
    
    </div>       
    <div id="gallery-options-tab">
    <?php $this->renderPartial('cms_content.views.admin._options',array('content'=>$content,'form'=>$form)); ?>    
    </div>
</div>          
<?php $this->endWidget(); ?>	