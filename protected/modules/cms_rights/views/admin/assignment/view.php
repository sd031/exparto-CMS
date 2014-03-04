

<?php /*$this->breadcrumbs = array(
	'Rights'=>Rights::getBaseUrl(),
	Rights::t('core', 'Assignments'),
);*/ ?>

<div id="assignments">

	<h2><?php echo Yii::t('backend', 'STR_ASSIGNMENTS'); ?></h2>

	<p>
		<?php echo Yii::t('backend', 'TXT_RIGHTS_ASSIGNMENTS_HINT'); ?>
	</p>

	<?php $this->widget('zii.widgets.grid.CGridView', array(
	    'dataProvider'=>$dataProvider,
	    'template'=>"{items}\n{pager}",
      'pager'=>array('cssFile'=>$this->cssDir.'/pager.css'),  
      //'cssFile'=>$this->cssDir.'/gridview.css',	        
	    'emptyText'=>Yii::t('backend', 'STR_NO_USERS_FOUND'),
	    'htmlOptions'=>array('class'=>'grid-view assignment-table'),
	    'columns'=>array(
    		array(
    			'name'=>'name',
    			'header'=>Yii::t('backend', 'STR_NAME'),
    			'type'=>'raw',
    			'htmlOptions'=>array('class'=>'name-column'),
    			'value'=>'$data->getAssignmentNameLink()',
    		),
    		array(
    			'name'=>'assignments',
    			'header'=>Yii::t('backend', 'STR_ROLES'),
    			'type'=>'raw',
    			'htmlOptions'=>array('class'=>'role-column'),
    			'value'=>'$data->getAssignmentsText(CAuthItem::TYPE_ROLE)',
    		),
			array(
    			'name'=>'assignments',
    			'header'=>Yii::t('backend', 'STR_TASKS'),
    			'type'=>'raw',
    			'htmlOptions'=>array('class'=>'task-column'),
    			'value'=>'$data->getAssignmentsText(CAuthItem::TYPE_TASK)',
    		),
			array(
    			'name'=>'assignments',
    			'header'=>Yii::t('backend', 'STR_OPERATIONS'),
    			'type'=>'raw',
    			'htmlOptions'=>array('class'=>'operation-column'),
    			'value'=>'$data->getAssignmentsText(CAuthItem::TYPE_OPERATION)',
    		),
	    )
	)); ?>

</div>