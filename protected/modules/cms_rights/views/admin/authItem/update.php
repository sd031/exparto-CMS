<?php /*$this->breadcrumbs = array(
	'Rights'=>Rights::getBaseUrl(),
	Rights::getAuthItemTypeNamePlural($model->type)=>Rights::getAuthItemRoute($model->type),
	$model->name,
);*/ ?>

<div id="updatedAuthItem">

	<h2><?php echo Yii::t('backend', 'STR_UPDATE_:name', array(
		':name'=>$model->name,
		':type'=>Rights::getAuthItemTypeName($model->type),
	)); ?></h2>
  <div class="two-column">
  
	<div class="column-content-box">
	<div class="content-box content-box-header ui-corner-all">
		<div class="content-box-wrapper">
	<?php $this->renderPartial('_form', array('model'=>$formModel)); ?>
    </div>
  </div>
  </div>

	<div class="column-content-box column-right">
	<div class="content-box content-box-header ui-corner-all">
		<div class="content-box-wrapper">  
	<div class="relations span-11 last">

		<h3><?php echo Yii::t('backend', 'STR_RELATIONS'); ?></h3>

		<?php if( $model->name!==Rights::module()->superuserName ): ?>

			<div class="parents">

				<h4><?php echo Yii::t('backend', 'STR_PARENTS'); ?></h4>

				<?php $this->widget('zii.widgets.grid.CGridView', array(
					'dataProvider'=>$parentDataProvider,
					'template'=>'{items}',
					'hideHeader'=>true,
					'emptyText'=>Yii::t('backend', 'STR_THIS_ITEM_HAS_NO_PARENTS'),
					'htmlOptions'=>array('class'=>'grid-view parent-table mini'),
					'columns'=>array(
    					array(
    						'name'=>'name',
    						'header'=>Yii::t('backend', 'STR_NAME'),
    						'type'=>'raw',
    						'htmlOptions'=>array('class'=>'name-column'),
    						'value'=>'$data->getNameLink()',
    					),
    					array(
    						'name'=>'type',
    						'header'=>Yii::t('backend', 'STR_TYPE'),
    						'type'=>'raw',
    						'htmlOptions'=>array('class'=>'type-column'),
    						'value'=>'$data->getTypeText()',
    					),
    					array(
    						'header'=>'&nbsp;',
    						'type'=>'raw',
    						'htmlOptions'=>array('class'=>'actions-column'),
    						'value'=>'',
    					),
					)
				)); ?>

			</div>

			<div class="children">

				<h4><?php echo Yii::t('backend', 'STR_CHILDREN'); ?></h4>

				<?php $this->widget('zii.widgets.grid.CGridView', array(
					'dataProvider'=>$childDataProvider,
					'template'=>'{items}',
					'hideHeader'=>true,
					'emptyText'=>Yii::t('backend', 'STR_THIS_ITEM_HAS_NO_CHILDREN'),
					'htmlOptions'=>array('class'=>'grid-view parent-table mini'),
					'columns'=>array(
    					array(
    						'name'=>'name',
    						'header'=>Yii::t('backend', 'STR_NAME'),
    						'type'=>'raw',
    						'htmlOptions'=>array('class'=>'name-column'),
    						'value'=>'$data->getNameLink()',
    					),
    					array(
    						'name'=>'type',
    						'header'=>Yii::t('backend', 'STR_TYPE'),
    						'type'=>'raw',
    						'htmlOptions'=>array('class'=>'type-column'),
    						'value'=>'$data->getTypeText()',
    					),
    					array(
    						'header'=>'&nbsp;',
    						'type'=>'raw',
    						'htmlOptions'=>array('class'=>'actions-column'),
    						'value'=>'$data->getRemoveChildLink()',
    					),
					)
				)); ?>

			</div>

			<div class="addChild">

				<h5 style="padding:0 0 5px 0"><?php echo Yii::t('backend', 'STR_ADD_CHILD'); ?></h5>

				<?php if( $childFormModel!==null ): ?>

					<?php $this->renderPartial('_childForm', array(
						'model'=>$childFormModel,
						'itemnameSelectOptions'=>$childSelectOptions,
					)); ?>

				<?php else: ?>

					<p class="info"><?php echo Yii::t('backend', 'STR_NO_CHILDREN_AVAILABLE_TO_BE_ADDED_TO_THIS_ITEM'); ?>

				<?php endif; ?>

			</div>

		<?php else: ?>

			<p class="info">
				<?php echo Yii::t('backend', 'STR_NO_RELATIONS_NEED_FOR_SUPERUSER'); ?><br />
				<?php echo Yii::t('backend', 'STR_SUPERUSERS_ARE_ALWAYS_GRANTED_ACCESSS_IMPLICITLY'); ?>
			</p>

		<?php endif; ?>

	</div>
  
  </div>
  </div>
  </div>
  
  </div>
    <div style="clear:both"></div> 

</div>