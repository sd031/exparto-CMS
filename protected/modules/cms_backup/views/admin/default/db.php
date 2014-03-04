<?php

Yii::app()->clientScript->registerScriptFile($this->jsDir.'/tip.js', CClientScript::POS_HEAD);  

$this->title=Yii::t('backend', 'STR_BACKUP');
$this->pageTitle=$this->title.' - '.Yii::app()->name;
  
Yii::app()->clientScript->registerScript('ajaxfrestore', "
$('#dbgrid a.ajaxfrestore').live('click', function() {
        if(!confirm('".Yii::t('backend','STR_ARE_YOU_SURE_YOU_WANT_TO_RESTORE_BACKUP?')."'))  return false;
        $.fn.yiiGridView.update('dbgrid', {
                type: 'POST',
                url: $(this).attr('href'),
                beforeSend:function(){notice('".Yii::t('backend','STR_RESTORING')."...');},
                complete:function(){notice('".Yii::t('backend','STR_RESTORE_COMPLETED')."');},                
                success: function() {
                  $.fn.yiiGridView.update('dbgrid');
                }
        });
        return false;
});
");

if(Yii::app()->user->hasFlash('backup')): 

  Yii::app()->clientScript->registerScript
  (
    'flash-n',
    "
        notice('".Yii::t('backend',Yii::app()->user->getFlash('backup'))."');  
    ",
  	CClientScript::POS_READY
  ) ;
        	 			
endif;    

?>


<div class="column-content-box">
  <div class="content-box content-box-header ui-corner-all">
		<div class="content-box-wrapper">   
    <h2><?php echo Yii::t('backend', 'STR_DATABASE_BACKUP');?></h2> 
    
    <a class="ui-state-default float-left ui-corner-all ui-button" href="<?php echo $this->createUrl('dbbackup');?>" style="margin-left:0">
									<?php echo Yii::t('backend','STR_CREATE_BACKUP'); ?>
		</a>
            
    <div style="clear:both"></div> 
    <br />    
    <div class="hastable">
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
    	'dataProvider' => $dbArr,
      'id'=>'dbgrid',
      'pager'=>array('cssFile'=>$this->cssDir.'/pager.css'),
      'selectableRows' => 0,
      'filterPosition'=>'header',     
      'cssFile'=>$this->cssDir.'/gridview.css',	  
    	'columns' => array(
    		array(
    			'name' => Yii::t('backend', 'STR_FILE'),
    			'type' => 'raw',
    			'value' => 'CHtml::encode($data["filename"])',
          'value'=>'( strlen($data["comment"]) > 0 ? CHtml::tag("span", array("title"=>nl2br($data["comment"]), "class"=>"bfile"), $data["filename"]) : $data["filename"]);',           
    		),
    		array(
    			'name' => Yii::t('backend', 'STR_DATE'),
    			'type' => 'raw',
    			'value' => 'CHtml::encode($data["date"])'
    		),
    		array(
    			'name' => Yii::t('backend', 'STR_SIZE'),
    			'type' => 'raw',
    			'value' => 'CHtml::encode($data["size"])'
    		),     
    		array(
    			'class'=>'CButtonColumn',
          'htmlOptions'=>array('nowrap'=>'nowrap'),
          'buttons'=>array(
            'restore'=>array(                                                                    
                'click'=>'function(){alert("Disabled");}',      
                'imageUrl'=>$this->cssDir.'/icons/arrow_undo.png',      
                'label'=>Yii::t('backend','STR_RESTORE'),   
                'options'=>array('class'=>'ajaxfrestore')
            ),
            'download'=>array(
                'click'=>'function(){alert("Disabled");}',     
                'imageUrl'=>$this->cssDir.'/icons/disk.png',       
                'label'=>Yii::t('backend','STR_DOWNLOAD')                  
            ),                                              
          ), 
          'deleteButtonUrl'=>'Yii::app()->controller->createUrl("dbDelete",array("file"=>$data["filename"]))',
          'deleteButtonImageUrl'=>$this->cssDir.'/icons/delete.png', 
          'afterDelete'=>'function(link,success,data){ if(success) notice("'.Yii::t('backend','STR_BACKUP_DELETED').'"); }',                       
        	'template'=>'{restore} {download} {delete}',      
    		),           
    	),
    ));
    ?>
    </div>

      <div style="clear:both"></div>      
    </div>              
  </div>
</div>

<script type="text/javascript">
		$('.bfile').tooltip({

		});
</script>