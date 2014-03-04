<?php
//jquery UI  
Yii::app()->clientScript->registerCoreScript('jquery.ui');

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

Yii::app()->clientScript->registerScriptFile($this->jsDir.'/jquery-ui-timepicker-addon.js', CClientScript::POS_HEAD);

    $form=$this->beginWidget('CActiveForm', array(
        'id'=>'article-small',
        'enableAjaxValidation'=>true,
        'clientOptions'=>array(
          'validationUrl'=>$this->createUrl('articleAjaxValidation'),
          'validateOnSubmit'=>true,
          'afterValidate'=>'js:function(form, data, hasError){return false;}',
        ) 
)); ?>  
<div class="content-box content-box-header">
  <div class="content-box-wrapper">
    <div class="content" id="content_action">
    <h4><?php echo Yii::t('backend','STR_ACTIONS'); ?></h4>
      <?php if(!$article->isNewRecord): ?>
      <div id="article-delete">
		  <a href="#" class="btn ui-state-default full-link ui-corner-all" title="<?php echo Yii::t('backend', 'STR_DELETE')?>">
        <span class="ui-icon ui-icon-trash"></span>
        <?php echo Yii::t('backend', 'STR_DELETE')?>        
			</a>
      <br />
      </div>    
      <?php endif; ?>      
		  <a href="#" class="btn ui-state-default full-link ui-corner-all subbtn" id="article-cancel" title="<?php echo  Yii::t('backend', 'STR_CANCEL')?>">
        <span class="ui-icon ui-icon-closethick"></span>
        <?php echo  Yii::t('backend', 'STR_CANCEL')?>
			</a> 
		  <a href="#" class="btn ui-state-default full-link ui-corner-all subbtn" id="article-publish" title="<?php echo  Yii::t('backend', 'STR_PUBLISH')?>">
        <span class="ui-icon ui-icon-check"></span>
        <?php echo  Yii::t('backend', 'STR_PUBLISH')?>
			</a>                	         			         				
    </div>
	</div>
</div> 
  
<div class="content-box content-box-header">
  <div class="content-box-wrapper">
    <div class="content">  
    <h4><?php echo Yii::t('backend','STR_PUBLICATION'); ?></h4>

        <div class="col">
        <span id="content_active">
          <?php echo $form->checkBox($article,'is_visible',array('class'=>'field checkbox')); ?>
          <?php echo $form->labelEx($article,'is_visible',array('class'=>'choice')); ?>
        </span>
        </div>  
    <div style="position:relative">    
    <ul>  
      <li>
        <?php echo $form->labelEx($article,'start_date',array('class'=>'desc')); ?>
        <div>
          <?php echo $form->textField($article,'start_date',array('class'=>'field text medium s_datepicker','maxlength'=>64,"style"=>"font-size:11px;padding:2px")); ?>
          <?php echo $form->error($article,'start_date'); ?>
        </div>
      </li> 
      <li style="padding-bottom:3px">
        <?php echo $form->labelEx($article,'finish_date',array('class'=>'desc')); ?>
        <div>
          <?php echo $form->textField($article,'finish_date',array('class'=>'field text medium s_datepicker','maxlength'=>64,"style"=>"font-size:11px;padding:2px")); ?>
          <?php echo $form->error($article,'finish_date'); ?>
        </div>
      </li>                   
    </ul>  
    </div> 
     	
    </div>
	</div>
</div>  

<?php if(!$article->isNewRecord): ?>
<div class="content-box content-box-header">
  <div class="content-box-wrapper">
    <div class="content">
    <h4><?php echo Yii::t('backend','STR_INFORMATION'); ?></h4>
    <form>
    <ul>
 		<?php if($article->rec_created):?>
    <li>
    <label class="desc"><?php echo $article->getAttributeLabel('rec_created'); ?>:</label>
 		<div><?php echo $article->rec_created; ?></div>
 		</li>
    <?php endif;?>
 		<?php if($article->rec_modified):?>
 		<li>
    <label class="desc"><?php echo $article->getAttributeLabel('rec_modified'); ?>:</label>
 		<div><?php echo $article->rec_modified; ?></div>
    </li> 	
    <?php endif;?>
    </ul>
    </form>	      	
    </div>
	</div>
</div>  
<?php endif; ?>

<?php $this->endWidget(); ?>	  
