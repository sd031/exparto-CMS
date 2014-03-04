<?php

$this->title=Yii::t('backend', 'STR_SYSTEM');
$this->pageTitle=$this->title.' - '.Yii::app()->name;

?>

<div class="column-content-box">
  <div class="content-box content-box-header ui-corner-all">
		<div class="content-box-wrapper"> 
    <h2><?php echo Yii::t('backend', 'STR_LANGUAGES');?></h2>
    <a href="<?php echo $this->createUrl('create')?>" style="margin-left:0" class="btn ui-state-default ui-corner-all">      
    <span class="ui-icon ui-icon-plusthick"></span>
    	 <?php echo Yii::t('backend','STR_ADD');?>
    </a>
    <div class="hastable">
    <?php $this->widget('zii.widgets.grid.CGridView', array(
    	'id'=>'language-grid',
      'pager'=>array('cssFile'=>$this->cssDir.'/pager.css'),
      'cssFile'=>$this->cssDir.'/gridview.css',	      
    	'dataProvider'=>$model->search(),
    	'filter'=>$model,
    	'columns'=>array(
    		//'id',
    		'name',
    		'lang_code',  
        array('name'=>'is_default', 'value'=>'$data->is_default?Yii::t("backend", "STR_YES"):Yii::t("backend", "STR_NO")'),  
        array('name'=>'is_visible', 'value'=>'$data->is_visible?Yii::t("backend", "STR_YES"):Yii::t("backend", "STR_NO")'),          
    		array(
    			'class'=>'CButtonColumn',
        	'template'=>'{update} {delete}',      
    		),
    	),
    )); ?>
    </div>
    </div>              
  </div>
</div>