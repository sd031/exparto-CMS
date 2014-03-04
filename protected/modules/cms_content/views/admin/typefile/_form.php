<?php

//jquery UI
Yii::app()->clientScript->registerCoreScript('jquery.ui');

Yii::app()->clientScript->registerScript
(
  'php-tabs',
  '
    $("#php-tabs").tabs({"select": 0});
  ',
	CClientScript::POS_LOAD 
);

Yii::app()->clientScript->registerScript
(
  'notice_file',
  '
    var container = $("#anotice-container").notify();
  ',
	CClientScript::POS_LOAD 
);

Yii::app()->clientScript->registerScriptFile($this->jsDir.'/jquery.notify.min.js', CClientScript::POS_HEAD);

$form=$this->beginWidget('CActiveForm', array(
        'id'=>'section-form',
        'enableAjaxValidation'=>true,
        'clientOptions'=>array(
          'validationUrl'=>$this->createUrl('ajaxValidation'),
          'validateOnSubmit'=>true,
          'afterValidate'=>'js:function(form, data, hasError){return false;}',
        ) 
)); ?>

<div id="php-tabs">                             
  <ul> 
   <li><a href="#php-main-tab"><?php echo Yii::t('backend','STR_MAIN');?></a></li>
   <li><a href="#php-options-tab"><?php echo Yii::t('backend','STR_OPTIONS');?></a></li>   
  </ul>
  
<?php echo $form->hiddenField($model,'type'); ?>
<?php echo CHtml::hiddenField('id',$id); ?>
<?php echo CHtml::hiddenField('ufile','',array('id'=>'ufile')); ?>
<div id="php-main-tab">
  <ul>
    <li>
      <?php echo $form->labelEx($model,'name',array('class'=>'desc')); ?>
      <div>
        <?php echo $form->textField($model,'name',array('class'=>'field text medium','maxlength'=>128,'id'=>'namebox')); ?>
        <?php echo $form->error($model,'name'); ?>
      </div>
    </li> 
  </ul>
        <div class="content-box content-box-header ui-corner-all" style="float:left">
							<div class="content-box-wrapper">
								<h3>
                 <?php
             
        $this->widget('cms_core.extensions.uploadify.EuploadifyWidget', 
            array(
                'name'=>'uploadme',
                'options'=> array(
                    'uploader' => $this->createUrl('UploadImage'), 
                    //'cancelImage' => $this->jsDir.'/icons/cancel.png',
                    'auto' => true,
                    'multi' => false,
                    'postData' => array('PHPSESSID' => session_id(),'template_name'=>empty($model->template_name)?'default':$model->template_name),
                    'fileTypeDesc' => Yii::t('backend','STR_FILES'),
                    'fileTypeExts' => '*.*',
                    'buttonText' => Yii::t('backend','STR_UPLOAD_FILE'),
                    'progressData' => 'all',
                    'wmode'=>'window'
                    ),
                    'callbacks' => array( 
                   //'onError' => 'js:function(evt,queueId,fileObj,errorObj){alert("Error: " + errorObj.type + "\nInfo: " + errorObj.info);}',
                   'onUploadSuccess' => 'js:function(file,data,response){$("#ufile").val(data);$("#filePreview").html(data);container.notify("create", {title: "'.Yii::t('backend','STR_NOTICE').'",text: "'.Yii::t('backend','STR_UPLOADED_FILE').'"});}',
                   //'onCancel' => 'function(evt,queueId,fileObj,data){alert("Cancelled");}',
                )
            )); 
            
        ?>
        </h3>
        <h4>
        <div id="filePreview">
        <?php if($model->var<>''):?>
        
        <?php echo $model->var ?>
        
        <?php endif;?>
        </div>
        </h4>
				</div>
			</div>    
      <div style="clear:both"></div>
</div> 
  <div id="php-options-tab">
    <?php $this->renderPartial('cms_content.views.admin._options',array('content'=>$model,'form'=>$form)); ?>     
  </div>
 </div>
<input id="sub-frm-btn" type="submit" name="" style="display:none">
<?php $this->endWidget(); ?>

	<div id="anotice-container" style="display:none;top:auto; z-index:99999;right:0; bottom:0; margin:0 10px 10px 0">		
		<div id="anotice" style="z-index:99999">
      <a class="ui-notify-cross ui-notify-close" href="#">x</a>
			<h1>#{title}</h1>
			<p>#{text}</p>
		</div>
	</div>      