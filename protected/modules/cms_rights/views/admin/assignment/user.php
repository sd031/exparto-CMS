<?php /*$this->breadcrumbs = ARRAY(
	'Rights'=>Rights::getBaseUrl(),
	Rights::t('core', 'Assignments')=>ARRAY('assignment/view'),
	$model->getName(),
);*/ ?>

<DIV id="userAssignments">

	<H2><?php ECHO Yii::t('backend', 'STR_ASSIGNMENTS_FOR_:username', ARRAY(
		':username'=>$model->getName()
	)); ?></H2>
	
  <div class="two-column">
	<div class="column-content-box">
	<div class="content-box content-box-header ui-corner-all">
		<div class="content-box-wrapper">
	<DIV class="assignments">

		<?php $this->widget('zii.widgets.grid.CGridView', ARRAY(
			'dataProvider'=>$dataProvider,
			'template'=>'{items}',
			'hideHeader'=>TRUE,
			'emptyText'=>Yii::t('backend', 'STR_THIS_USER_HAS_NOT_BEEN_ASSIGNED_ANY_ITEMS'),
			'htmlOptions'=>ARRAY('class'=>'grid-view user-assignment-table mini'),
			'columns'=>ARRAY(
    			ARRAY(
    				'name'=>'name',
    				'header'=>Yii::t('backend', 'STR_NAME'),
    				'type'=>'raw',
    				'htmlOptions'=>ARRAY('backend'=>'name-column'),
    				'value'=>'$data->getNameText()',
    			),
    			ARRAY(
    				'name'=>'type',
    				'header'=>Yii::t('backend', 'STR_TYPE'),
    				'type'=>'raw',
    				'htmlOptions'=>ARRAY('class'=>'type-column'),
    				'value'=>'$data->getTypeText()',
    			),
    			ARRAY(
    				'header'=>'&nbsp;',
    				'type'=>'raw',
    				'htmlOptions'=>ARRAY('class'=>'actions-column'),
    				'value'=>'$data->getRevokeAssignmentLink()',
    			),
			)
		)); ?>

	</DIV>
  </div>
  </div>
  </div>


  <div class="column-content-box column-right">
  <div class="content-box content-box-header ui-corner-all">  
  <div class="content-box-wrapper">          
	<DIV class="add-assignment">

		<H4><?php ECHO Yii::t('backend', 'STR_ASSIGN_ITEM'); ?></H4>

		<?php IF( $formModel!==NULL ): ?>

			<DIV class="form">

				<?php $this->renderPartial('_form', ARRAY(
					'model'=>$formModel,
					'itemnameSelectOptions'=>$assignSelectOptions,
				)); ?>

			</DIV>

		<?php ELSE: ?>

			<P class="info"><?php ECHO Yii::t('backend', 'STR_NO_ASSIGNMENTS_AVAILABLE_TO_BE_ASSIGNED_TO_THIS_USER'); ?>

		<?php ENDIF; ?>

	</DIV>
  </div>
  </div>
  </div>

  
  </div>
  
  <div style="clear:both"></div>   

</DIV>


