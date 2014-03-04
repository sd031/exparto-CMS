<?php

  $this->title=Yii::t('backend', 'STR_BACKUP');
  $this->pageTitle=$this->title.' - '.Yii::app()->name;
  
  Yii::app()->clientScript->registerScript('ajaxfrestore', "
    $('#backupbtn').click(function(){
      notice('".Yii::t('backend', 'STR_CREATING_BACKUP')."...');
      $('.buttons').hide();
    })
  ");

?>

<div class="column-content-box">
  <div class="content-box content-box-header ui-corner-all">
		<div class="content-box-wrapper">   
    <h2><?php echo Yii::t('backend', 'STR_DATABASE_BACKUP');?></h2> 
    <?php $form=$this->beginWidget('CActiveForm', array(
    	'id'=>'backup-form',
    	'enableAjaxValidation'=>false,
    )); ?>    
    <div class="hastable">
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
    	'dataProvider' => $arrayDataProvider,
      'pager'=>array('cssFile'=>$this->cssDir.'/pager.css'),
      'selectableRows' => 0,
      'filterPosition'=>'header',     
      'cssFile'=>$this->cssDir.'/gridview.css',	  
    	'columns' => array(
        array(
        'name'=>'',             
        'value'=>'CHtml::checkBox("tables[]",true,array("value"=>$data["table"],"id"=>"did_".$data["id"]))',
        'type'=>'raw',
        'htmlOptions'=>array('width'=>5),
        //'visible'=>false,
        ),      
    		array(
    			'name' => Yii::t('backend', 'STR_TABLE'),
    			'type' => 'raw',
    			'value' => 'CHtml::encode($data["table"])'
    		),
    		array(
    			'name' => Yii::t('backend', 'STR_RECORDS_COUNT'),
    			'type' => 'raw',
    			'value' => 'CHtml::encode($data["count"])'
    		),
    	),
    ));
    ?>
    </div>
    <br />
			<?php echo CHtml::link(Yii::t('backend', 'STR_SELECT_ALL'), '#', array(
				'onclick'=>"jQuery('.hastable').find(':checkbox').attr('checked', 'checked'); return false;",
				'class'=>'selectAllLink')); ?>
 				/
			<?php echo CHtml::link(Yii::t('backend', 'STR_SELECT_NONE'), '#', array(
				'onclick'=>"jQuery('.hastable').find(':checkbox').removeAttr('checked'); return false;",
				'class'=>'selectNoneLink')); ?>    
    <br />
    <br />
    <ul> 
      <li>
        <?php echo Yii::t('backend', 'STR_COMMENT');?>:<br />
        <?php echo CHtml::textArea('comment','',array('class'=>'field textarea small'))?>
      </li>
      <li class="buttons">                                                                                                                                                                                                                               
        <?php echo CHtml::htmlButton('<span class="ui-icon ui-icon-check"></span>'.Yii::t('backend', 'STR_CREATE_BACKUP'),array('id'=>'backupbtn','class'=>"ui-state-default ui-corner-all ui-button",'type'=>'submit')); ?>  
        <?php echo CHtml::htmlButton('<span class="ui-icon ui-icon-closethick"></span>'.Yii::t('backend', 'STR_CANCEL'),array('class'=>"ui-state-default ui-corner-all ui-button",'onclick'=>"window.location='".CController::createUrl('db')."'")); ?>    
      </li>
    </ul>
    <?php $this->endWidget(); ?>    
    <div style="clear:both"></div>      
    </div>              
  </div>
</div>    