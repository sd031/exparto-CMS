<?php 
/*$this->widget('zii.widgets.CMenu', array(
	'firstItemCssClass'=>'first',
	'lastItemCssClass'=>'last',
	'htmlOptions'=>array('class'=>'actions'),
	'items'=>array(
		array(
			'label'=>Rights::t('core', 'Assignments'),
			'url'=>array('assignment/view'),
			'itemOptions'=>array('class'=>'btn ui-state-default ui-corner-all'),
		),
		array(
			'label'=>Rights::t('core', 'Permissions'),
			'url'=>array('authItem/permissions'),
			'itemOptions'=>array('class'=>'item-permissions'),
		),
		array(
			'label'=>Rights::t('core', 'Roles'),
			'url'=>array('authItem/roles'),
			'itemOptions'=>array('class'=>'item-roles'),
		),
		array(
			'label'=>Rights::t('core', 'Tasks'),
			'url'=>array('authItem/tasks'),
			'itemOptions'=>array('class'=>'item-tasks'),
		),
		array(
			'label'=>Rights::t('core', 'Operations'),
			'url'=>array('authItem/operations'),
			'itemOptions'=>array('class'=>'item-operations'),
		),
	)
));
*/	
?>

<a href="<?php echo $this->createUrl('/cms_rights/admin/assignment/view');?>" class="ui-state-default float-left ui-corner-all ui-button" style="margin-left:0">
  <?php echo Yii::t('backend', 'STR_ASSIGNMENTS') ?>
</a>
<a href="<?php echo $this->createUrl('/cms_rights/admin/authItem/permissions');?>" class="ui-state-default float-left ui-corner-all ui-button">
  <?php echo Yii::t('backend', 'STR_PERMISSIONS') ?>
</a>
<a href="<?php echo $this->createUrl('/cms_rights/admin/authItem/roles');?>" class="ui-state-default float-left ui-corner-all ui-button">
  <?php echo Yii::t('backend', 'STR_ROLES') ?>
</a>
<a href="<?php echo $this->createUrl('/cms_rights/admin/authItem/tasks');?>" class="ui-state-default float-left ui-corner-all ui-button">
  <?php echo Yii::t('backend', 'STR_TASKS') ?>
</a>
<a href="<?php echo $this->createUrl('/cms_rights/admin/authItem/operations');?>" class="ui-state-default float-left ui-corner-all ui-button">
  <?php echo Yii::t('backend', 'STR_OPERATIONS') ?>
</a>