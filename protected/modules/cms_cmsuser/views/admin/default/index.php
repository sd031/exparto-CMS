<?php 
$this->title=Yii::t('backend','STR_USERS');
$this->pageTitle=$this->title.' - '.Yii::app()->name;
?>

<?php if(Yii::app()->user->hasFlash('user')): 

  Yii::app()->clientScript->registerScript
  (
    'flash-n',
    "
        notice('".Yii::t('backend',Yii::app()->user->getFlash('user'))."');  
    ",
  	CClientScript::POS_READY
  ) ;
        	 			
endif; ?>    


<div class="column-content-box">
  <div class="content-box content-box-header ui-corner-all">
		<div class="content-box-wrapper">
    <h2><?php echo Yii::t('backend','STR_MANAGE_USERS');?></h2>
    <a href="<?php echo $this->createUrl('create');?>" class="btn ui-state-default ui-corner-all" style="margin-left:0" id="newAction">
      <span class="ui-icon ui-icon-plusthick"></span>
    	 <?php echo Yii::t('backend','STR_ADD');?>
    </a>		    	
    <div class="hastable">  
<?php
$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'cms-user-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
  'pager'=>array('cssFile'=>$this->cssDir.'/pager.css'),  
  'cssFile'=>$this->cssDir.'/gridview.css',	  
	'columns'=>array(
		'id',
		'username',
		'first_name',
		'last_name',
		'email',
		'login_date',
    array(            
      'name'=>'login_ip',
      'value'=>'long2ip($data->login_ip)',      
      'filter'=>false
    ),    
		array(
			'class'=>'CButtonColumn',
      'afterDelete'=>"function(link,success,data){ if(success) notice('".Yii::t('backend','STR_DELETED_USER')."');   }",
    	'template'=>'{update} {delete}',      
		),   
	),
)); ?>


    </div>     
      <div style="clear:both"></div>      
    </div>              
  </div>
</div>