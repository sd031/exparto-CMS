<?php /*$this->breadcrumbs = array(
	'Rights'=>Rights::getBaseUrl(),
	Rights::t('core', 'Permissions'),
);*/ ?>

<div id="permissions">

	<h2><?php echo Yii::t('backend', 'STR_PERMISSIONS'); ?></h2>

	<p>
		<?php echo Yii::t('backend', 'TXT_RIGHTS_PERMISSIONS_HINT1'); ?><br />
		<?php echo Yii::t('backend', 'TXT_RIGHTS_PERMISSIONS_HINT2', array(
			'{roleLink}'=>CHtml::link(Yii::t('backend', 'STR_ROLES'), array('/cms_rights/admin/authItem/roles')),
			'{taskLink}'=>CHtml::link(Yii::t('backend', 'STR_TASKS'), array('/cms_rights/admin/authItem/tasks')),
			'{operationLink}'=>CHtml::link(Yii::t('backend', 'STR_OPERATIONS'), array('/cms_rights/admin/authItem/operations')),
		)); ?>
	</p>

	<p><?php echo CHtml::link(Yii::t('backend', 'STR_GENERATE_ITEMS_FOR_CONTROLLER_ACTIONS'), array('/cms_rights/admin/authItem/generate'), array(
	   	'class'=>'generator-link',
	)); ?></p>

	<?php $this->widget('zii.widgets.grid.CGridView', array(
		'dataProvider'=>$dataProvider,
		'template'=>'{items}',
		'emptyText'=>Yii::t('backend', 'STR_NO_AUTHORIZATION_ITEMS_FOUND'),
		'htmlOptions'=>array('class'=>'grid-view permission-table'),
		'columns'=>$columns,
	)); ?>

	<!--<p class="info">(*) <?php //echo Yii::___t('backend', 'Hover to see from where the permission is inherited.'); ?></p>-->

	<script type="text/javascript">

		/**
		* Attach the tooltip to the inherited items.
		*/
		$('.inherited-item').rightsTooltip({
			title:'<?php echo Yii::t('backend', 'STR_SOURCE'); ?>: '
		});

		/**
		* Hover functionality for rights' tables.
		*/
		$('#rights tbody tr').hover(function() {
			$(this).addClass('hover'); // On mouse over
		}, function() {
			$(this).removeClass('hover'); // On mouse out
		});

	</script>

</div>
