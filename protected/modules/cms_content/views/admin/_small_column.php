<?php

//jquery UI  
Yii::app()->clientScript->registerCoreScript('jquery.ui');


/*$juiPathAlias = 'system.web.js.source.jui';
$basePath=Yii::getPathOfAlias($juiPathAlias);
$baseUrl=Yii::app()->getAssetManager()->publish($basePath, true);
$scriptUrl=$baseUrl.'/js';
Yii::app()->clientScript->registerScriptFile($scriptUrl.'/'.'jquery-ui-i18n.min.js', CClientScript::POS_HEAD);
*/
Yii::app()->clientScript->registerScriptFile($this->jsDir.'/jquery-ui-timepicker-addon.js', CClientScript::POS_HEAD);

if($publication) 
{
if(!$content->is_active)
Yii::app()->clientScript->registerScript
(
  'load_active',
  ' 
    $("#content_active_block").hide();   
  ',
	CClientScript::POS_READY
);

Yii::app()->clientScript->registerScript
(
  'active_action',
  ' 
    $("#content_active .checkbox, #content_active .choice").click(
      function() {
        if($("#content_active .checkbox").is(":checked"))        
          $("#content_active_block").fadeIn(300);    
        else
          $("#content_active_block").fadeOut(300);        
      }
    );   
  ',
	CClientScript::POS_READY
);

Yii::app()->clientScript->registerScript
(
  'check_recursive',
  ' 
    $("#check-recursive .checkbox, #check-recursive .choice").click(
      function() {
        if($("#check-recursive .checkbox").is(":checked"))   
        {     
          $("label[for=\"Content_is_active\"]").css("color","#0062A4");
          $("label[for=\"Content_start_date\"]").css("color","#0062A4");
          $("label[for=\"Content_finish_date\"]").css("color","#0062A4");
          $("label[for=\"Content_menu_name\"]").css("color","#0062A4");          
        }      
        else
        {
          $("label[for=\"Content_is_active\"]").css("color","#000");
          $("label[for=\"Content_start_date\"]").css("color","#000");
          $("label[for=\"Content_finish_date\"]").css("color","#000");
          $("label[for=\"Content_menu_name\"]").css("color","#000");          
        }          
      }
    );   
  ',
	CClientScript::POS_READY
);

Yii::app()->clientScript->registerScript
(
  'datepicker',
  ' 
    //jQuery.datepicker.setDefaults(jQuery.datepicker.regional["ru"]);    
    $(".s_datepicker").datetimepicker({"dateFormat": "yy-mm-dd",
                                       "timeFormat":"hh:mm:ss",
                                       "showOn":"button",
			                                 "buttonImage": "'.$this->cssDir.'/icons/calendar.png",
			                                 "buttonImageOnly": true
                                    });   
    //$(".s_datepicker").datepicker();  
  ',
	CClientScript::POS_READY
);
}
?>

<?php if($actions):?>
<div class="content-box content-box-header">
  <div class="content-box-wrapper">
    <div class="content" id="content_action">
    <h4><?php echo Yii::t('backend','STR_ACTIONS'); ?></h4>
    <?php if(Yii::app()->user->checkAccess('Cms_content.Default.MoveNode')):?>
    <?php if(!$content->isNewRecord): ?>
		  <a href="#" class="btn ui-state-default full-link ui-corner-all" id="content-delete" title="<?php echo Yii::t('backend', 'STR_DELETE')?>">
        <span class="ui-icon ui-icon-trash"></span>
        <?php echo Yii::t('backend', 'STR_DELETE')?>
			</a>  
			<br />
		<?php endif; ?>
    <?php endif; ?>
		  <a href="#" class="btn ui-state-default full-link ui-corner-all" id="content-cancel" title="<?php echo Yii::t('backend', 'STR_CLOSE')?>">
        <span class="ui-icon ui-icon-closethick"></span>
        <?php echo Yii::t('backend', 'STR_CLOSE');?>
			</a>   
      <?php if(Yii::app()->user->checkAccess('Cms_content.Default.NewNode')):?>  
		  <a href="#" class="btn ui-state-default full-link ui-corner-all subbtn" id="<?php echo $content->isNewRecord?'content-create':'content-update' ?>" title="<?php echo $content->isNewRecord?Yii::t('backend', 'STR_CREATE'):Yii::t('backend', 'STR_SAVE')?>">
        <span class="ui-icon ui-icon-check"></span>
        <?php echo $content->isNewRecord?Yii::t('backend', 'STR_CREATE'):Yii::t('backend', 'STR_SAVE')?>
			</a> 
             	      
		  <a href="#" class="btn ui-state-default full-link ui-corner-all subbtn" id="<?php echo $content->isNewRecord?'content-create-close':'content-update-close' ?>" title="<?php echo $content->isNewRecord?Yii::t('backend', 'STR_CREATE_AND_CLOSE'):Yii::t('backend', 'STR_SAVE_AND_CLOSE')?>">
        <span class="ui-icon ui-icon-disk"></span>
        <?php echo $content->isNewRecord?Yii::t('backend', 'STR_CREATE_AND_CLOSE'):Yii::t('backend', 'STR_SAVE_AND_CLOSE')?>
			</a>    
      <?php endif; ?>  			         				
    </div>
	</div>
</div> 
<?php endif;?>

<?php if($publication):?>
<div class="content-box content-box-header">
  <div class="content-box-wrapper">
    <div class="content">  
    <h4><?php echo Yii::t('backend','STR_PUBLICATION'); ?></h4>
    <?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'text-form',
            'enableAjaxValidation'=>true,
            'clientOptions'=>array(
              'validationUrl'=>$this->createUrl('ajaxValidation'),
              'validateOnSubmit'=>true,
              'afterValidate'=>'js:function(form, data, hasError){return false;}',
            ) 
    )); ?>    
        <div class="col">
        <span id="content_active">
          <?php echo $form->checkBox($content,'is_active',array('class'=>'field checkbox')); ?>
          <?php echo $form->labelEx($content,'is_active',array('class'=>'choice')); ?>
        </span>
        </div>  
    <div style="position:relative" id="content_active_block">   
        <!--<div class="col">
        <span>
          <?php echo $form->checkBox($content,'is_visible',array('class'=>'field checkbox')); ?>
          <?php echo $form->labelEx($content,'is_visible',array('class'=>'choice')); ?>
        </span>
        </div>-->        
      <!--<li style="padding-bottom:3px">
        <?php echo $form->labelEx($content,'created_date',array('class'=>'desc')); ?>
        <div>                                                                                                                      
          <?php echo $form->textField($content,'created_date',array('class'=>'field text medium s_datepicker','maxlength'=>64,"style"=>"font-size:11px;padding:2px")); ?>          
          <?php echo $form->error($content,'created_date'); ?>
        </div>
      </li>--> 
    <ul>  
      <?php if($content->type<>'structure'): ?> 
      <li style="padding-bottom:3px">
        <?php echo $form->labelEx($content,'menu_name',array('class'=>'desc')); ?>
        <div>
          <?php echo $form->dropDownList($content,'menu_name',$content->menuOptions,array('class'=>'field select medium',"style"=>"font-size:11px;padding:2px")); ?>
          <?php echo $form->error($content,'menu_name'); ?>
        </div>
      </li>
      <?php endif?>      
      </li>
      <li style="padding-bottom:3px">
        <?php echo $form->labelEx($content,'start_date',array('class'=>'desc')); ?>
        <div>
          <?php echo $form->textField($content,'start_date',array('class'=>'field text medium s_datepicker','maxlength'=>64,"style"=>"font-size:11px;padding:2px")); ?>
          <?php echo $form->error($content,'start_date'); ?>
        </div>
      </li> 
      <li style="padding-bottom:3px">
        <?php echo $form->labelEx($content,'finish_date',array('class'=>'desc')); ?>
        <div>
          <?php echo $form->textField($content,'finish_date',array('class'=>'field text medium s_datepicker','maxlength'=>64,"style"=>"font-size:11px;padding:2px")); ?>
          <?php echo $form->error($content,'finish_date'); ?>
        </div>
      </li>                   
    </ul>  
    <div id="active-disable" class="ui-widget-overlay" style="display:none"></div> 
    </div> 
    <div class="col">
      <span id="check-recursive">
          <?php echo $form->checkBox($content,'set_recursive',array('class'=>'field checkbox')); ?>
          <?php echo $form->labelEx($content,'set_recursive',array('class'=>'choice')); ?>
      </span>
    </div>   
    <?php $this->endWidget(); ?>	      	
    </div>
	</div>
</div>
<?php endif;?>

<?php if(!$content->isNewRecord): ?>
<div class="content-box content-box-header">
  <div class="content-box-wrapper">
    <div class="content">
    <h4><?php echo Yii::t('backend','STR_INFORMATION'); ?></h4>
    <form>
    <ul>
 		<?php if($content->rec_created):?>
    <li>
    <label class="desc"><?php echo $content->getAttributeLabel('rec_created'); ?>:</label>
 		<div><?php echo $content->rec_created; ?></div>
 		</li>
    <?php endif;?>
 		<?php if($content->rec_modified):?>
 		<li>
    <label class="desc"><?php echo $content->getAttributeLabel('rec_modified'); ?>:</label>
 		<div><?php echo $content->rec_modified; ?></div>
    </li> 	
    <?php endif;?>
    </ul>
    </form>	      	
    </div>
	</div>
</div>  
<?php endif; ?>