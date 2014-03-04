<?php /*$this->breadcrumbs = array(
	'Rights'=>Rights::getBaseUrl(),
	Rights::t('core', 'Operations'),
);*/ ?>

<div id="operations">

	<h2><?php echo Yii::t('backend', 'STR_OPERATIONS'); ?></h2>

	<p>
		<?php echo Yii::t('backend', 'TXT_RIGHTS_OPERATIONS_HINT_1'); ?><br />
		<?php echo Yii::t('backend', 'TXT_RIGHTS_OPERATIONS_HINT_2'); ?>
	</p>

	<p><?php echo CHtml::link(Yii::t('backend', 'STR_CREATE_NEW_OPERATION'), array('/cms_rights/admin/authItem/create', 'type'=>CAuthItem::TYPE_OPERATION), array(
		'class'=>'add-operation-link',
	)); ?></p>

	<?php $this->widget('zii.widgets.grid.CGridView', array(
	    'dataProvider'=>$dataProvider,
	    'template'=>'{items}',
	    'emptyText'=>Yii::t('backend', 'STR_NO_OPERATIONS_FOUND'),
	    'htmlOptions'=>array('class'=>'grid-view operation-table sortable-table'),
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
    			'value'=>'$data->getDeleteOperationLink()',
    		),
	    )
	)); ?>

	<!--<p class="info"><?php //echo Rights::t('core', 'Values within square brackets tell how many children each item has.'); ?></p>-->

</div>