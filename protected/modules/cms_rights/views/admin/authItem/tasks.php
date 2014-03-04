<?php /*$this->breadcrumbs = array(
	'Rights'=>Rights::getBaseUrl(),
	Rights::t('core', 'Tasks'),
);*/ ?>

<div id="tasks">

	<h2><?php echo Yii::t('backend', 'STR_TASKS'); ?></h2>

	<p>
		<?php echo Yii::t('backend', 'STR_RIGHTS_TASKS_HINT_1'); ?><br />
		<?php echo Yii::t('backend', 'STR_RIGHTS_TASKS_HINT_2'); ?>
	</p>

	<p><?php echo CHtml::link(Yii::t('backend', 'STR_CREATE_NEW_TASK'), array('/cms_rights/admin/authItem/create', 'type'=>CAuthItem::TYPE_TASK), array(
		'class'=>'add-task-link',
	)); ?></p>

	<?php $this->widget('zii.widgets.grid.CGridView', array(
	    'dataProvider'=>$dataProvider,
	    'template'=>'{items}',
	    'emptyText'=>Yii::t('backend', 'STR_NO_TASKS_FOUND'),
	    'htmlOptions'=>array('class'=>'grid-view task-table'),
	    'columns'=>array(
    		array(
    			'name'=>'name',
    			'header'=>Yii::t('backend', 'STR_NAME'),
    			'type'=>'raw',
    			'htmlOptions'=>array('class'=>'name-column'),
    			'value'=>'$data->getGridNameLink()',
    		),
    		array(
    			'name'=>'description',
    			'header'=>Yii::t('backend', 'STR_DESCRIPTION'),
    			'type'=>'raw',
    			'htmlOptions'=>array('class'=>'description-column'),
    		),
    		array(
    			'name'=>'bizRule',
    			'header'=>Yii::t('backend', 'STR_BUSINESS_RULE'),
    			'type'=>'raw',
    			'htmlOptions'=>array('class'=>'bizrule-column'),
    			'visible'=>Rights::module()->enableBizRule===true,
    		),
    		array(
    			'name'=>'data',
    			'header'=>Yii::t('backend', 'STR_DATA'),
    			'type'=>'raw',
    			'htmlOptions'=>array('class'=>'data-column'),
    			'visible'=>Rights::module()->enableBizRuleData===true,
    		),
    		array(
    			'header'=>'&nbsp;',
    			'type'=>'raw',
    			'htmlOptions'=>array('class'=>'actions-column'),
    			'value'=>'$data->getDeleteTaskLink()',
    		),
	    )
	)); ?>

	<!--<p class="info"><?php //echo Rights::t('core', 'Values within square brackets tell how many children each item has.'); ?></p>-->

</div>