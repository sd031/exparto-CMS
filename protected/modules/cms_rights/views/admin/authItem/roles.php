<?php /*$this->breadcrumbs = array(
	'Rights'=>Rights::getBaseUrl(),
	Rights::t('core', 'Roles'),
);*/ ?>

<div id="roles">

	<h2><?php echo Yii::t('backend', 'STR_ROLES'); ?></h2>

	<p>
		<?php echo Yii::t('backend', 'STR_RIGHTS_ROLES_HINT_1'); ?><br />
		<?php echo Yii::t('backend', 'STR_RIGHTS_ROLES_HINT_2'); ?>
	</p>

	<p><?php echo CHtml::link(Yii::t('backend', 'STR_CREATE_NEW_ROLE'), array('/cms_rights/admin/authItem/create', 'type'=>CAuthItem::TYPE_ROLE), array(
	   	'class'=>'add-role-link',
	)); ?></p>

	<?php $this->widget('zii.widgets.grid.CGridView', array(
	    'dataProvider'=>$dataProvider,
	    'template'=>'{items}',
	    'emptyText'=>Yii::t('backend', 'STR_NO_ROLES_FOUND'),
	    'htmlOptions'=>array('class'=>'grid-view role-table'),
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
    			'value'=>'$data->getDeleteRoleLink()',
    		),
	    )
	)); ?>

	<!--<p class="info"><?php //echo Rights::t('core', 'Values within square brackets tell how many children each item has.'); ?></p>-->
</div>