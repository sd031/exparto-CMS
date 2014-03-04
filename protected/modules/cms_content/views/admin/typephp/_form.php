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
  'alias',
  '
  $("#edit-alias,#cancel-alias,#ok-alias,#namebox").unbind();

    $("#edit-alias").
    click(
    function(){
      $("#alias-input").fadeIn(400);
      $("#edit-alias").hide();
      $("#aliasview").hide();
      $("#aliasbox").val($("#aliashidden").val());
      return false;
    }              
  );    

    $("#cancel-alias").
    click(
    function(){
      $("#alias-input").hide();
      $("#edit-alias").fadeIn(400);
      $("#aliasview").show();      
      return false;
    }              
  );  
  
    $("#ok-alias").
    click(
    function(){
      var alias=$("#aliasbox").val();
      $("#alias-input").hide();
      $("#edit-alias").fadeIn(400);
        jQuery.ajax({
          "type": "POST",
    		  dataType:"json",        
          "data": {
                    "name":alias,
                    "id":$("#idhidden").val()                 
                 },   
          "url":"'.$this->createUrl('ajaxAlias').'",
          "cache":false,
      		success: function (data) {    		
    					if(data.status==1) {   
                $("#aliashidden").val(data.alias);
                $("#aliasview").html($("#aliashidden").val());
                $("#aliasview").show();                                                                         				
    					}
      		}
        });       
     
      return false;
    }              
  );   
  
    $("#namebox").
    focusout(
    function(){
      var name=$("#namebox").val();
      var alias=$("#aliasbox").val();
      if(name!="" && alias=="")
      {     
        jQuery.ajax({
          "type": "POST",
    		  dataType:"json",        
          "data": {
                    "name":name,
                    "id":$("#idhidden").val()                 
                 },   
          "url":"'.$this->createUrl('ajaxAlias').'",
          "cache":false,
      		success: function (data) {    		
    					if(data.status==1) {   
                $("#aliashidden").val(data.alias);
                $("#aliasview").html($("#aliashidden").val());                           
                $("#alias-container").fadeIn(400);                                                                         				
    					}
      		}
        });           
      }
    }              
  );  
          
  ',
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

<div id="php-tabs">                             
  <ul> 
   <li><a href="#php-main-tab"><?php echo Yii::t('backend','STR_MAIN');?></a></li>
   <li><a href="#php-options-tab"><?php echo Yii::t('backend','STR_OPTIONS');?></a></li>   
  </ul>
  
<?php echo $form->hiddenField($model,'type'); ?>
<?php echo $form->hiddenField($model,'alias',array('id'=>'aliashidden')); ?>
<?php echo CHtml::hiddenField('id',$id); ?>
<div id="php-main-tab">
  <ul>
    <li>
      <?php echo $form->labelEx($model,'name',array('class'=>'desc')); ?>
      <div>
        <?php echo $form->textField($model,'name',array('class'=>'field text medium','maxlength'=>128,'id'=>'namebox')); ?>
        <?php echo $form->error($model,'name'); ?>
      </div>
    </li>
    <li id="alias-container">
      <?php echo $form->label($model,'alias',array('class'=>'desc')); ?>
      <div style="color:#7B7B7B;font-size:12px">
        <span style="margin:0"><?php echo Yii::app()->request->getBaseUrl(true).(isset($_POST['lng'])?'/'.$_POST['lng']:''); ?>/</span><span id="aliasview" style="margin:0;background:#E5ECF9"><?php echo $model->alias; ?></span>
        <a href="#" style="margin:-5px 0 0 5px" title="<?php echo Yii::t('backend', 'STR_EDIT'); ?>" class="btn_no_text btn ui-state-default ui-corner-all" id="edit-alias"><span class="ui-icon ui-icon-wrench"></span></a>
        <span id="alias-input" style="display:none;margin:-7px 0 0 5px">
        <?php echo CHtml::textField('alias','',array('class'=>'field text medium','maxlength'=>128,'style'=>'float:left;width:200px','id'=>'aliasbox')); ?>
        <a href="#" style="margin:2px 0 0 5px" title="<?php echo Yii::t('backend', 'STR_OK'); ?>" class="btn_no_text btn ui-state-default ui-corner-all" id="ok-alias"><span class="ui-icon ui-icon-check"></span></a>
        <a href="#" style="margin:2px 0 0 5px" title="<?php echo Yii::t('backend', 'STR_CANCEL'); ?>" class="btn_no_text btn ui-state-default ui-corner-all" id="cancel-alias"><span class="ui-icon ui-icon-closethick"></span></a>
        </span>
        <?php echo $form->error($model,'alias'); ?>
      </div>
    </li>  
    <li>
      <?php echo $form->labelEx($model,'var',array('class'=>'desc','label'=>Yii::t('backend','STR_ACTION'))); ?>
      <div>
        <?php echo $form->textField($model,'var',array('class'=>'field text medium','maxlength'=>128)); ?>
        <?php echo $form->error($model,'var'); ?>
      </div>
    </li> 
  </ul>
</div> 
  <div id="php-options-tab">
    <?php $this->renderPartial('cms_content.views.admin._options',array('content'=>$model,'form'=>$form)); ?>     
  </div>
 </div>
<input id="sub-frm-btn" type="submit" name="" style="display:none">
<?php $this->endWidget(); ?>
