<?php /*$this->breadcrumbs = array(
	'Rights'=>Rights::getBaseUrl(),
	Rights::t('core', 'Generate items'),
);*/ ?>

<div id="generator">

	<h2><?php echo Yii::t('backend', 'STR_GENERATE_ITEMS'); ?></h2>

	<p><?php echo Yii::t('backend', 'TXT_RIGHTS_GENERATE_HINT'); ?></p>

	<div class="form">

		<?php $form=$this->beginWidget('CActiveForm'); ?>

			<div class="row">

				<table class="items generate-item-table" border="0" cellpadding="0" cellspacing="0">

					<tbody>

						<tr class="application-heading-row">
							<th colspan="3"><?php echo Yii::t('backend', 'STR_APPLICATION'); ?></th>
						</tr>

						<?php $this->renderPartial('_generateItems', array(
							'model'=>$model,
							'form'=>$form,
							'items'=>$items,
							'existingItems'=>$existingItems,
							'displayModuleHeadingRow'=>true,
							'basePathLength'=>strlen(Yii::app()->basePath),
						)); ?>

					</tbody>

				</table>

			</div>
      <br />
			<div class="row">

   				<?php echo CHtml::link(Yii::t('backend', 'STR_SELECT_ALL'), '#', array(
   					'onclick'=>"jQuery('.generate-item-table').find(':checkbox').attr('checked', 'checked'); return false;",
   					'class'=>'selectAllLink')); ?>
   				/
				<?php echo CHtml::link(Yii::t('backend', 'STR_SELECT_NONE'), '#', array(
					'onclick'=>"jQuery('.generate-item-table').find(':checkbox').removeAttr('checked'); return false;",
					'class'=>'selectNoneLink')); ?>

			</div>
      <br />
   			<div class="row">
        
        <button class="ui-state-default ui-corner-all ui-button" type="submit"><?php echo Yii::t('backend', 'STR_GENERATE'); ?></button>

			   </div>

		<?php $this->endWidget(); ?>

	</div>

</div>