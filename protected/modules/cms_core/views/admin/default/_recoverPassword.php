 <?php if(!Yii::app()->user->hasFlash('recover')): ?>
          <?php $recoverForm = $this->beginWidget('CActiveForm', array(
              'id'=>'recover-form',
              /*'enableAjaxValidation'=>true,
              'clientOptions'=>array(
                'validationUrl'=>Yii::app()->createUrl("cms_core/admin/default/recoverval"),
              )*/
          )); ?>            
							<ul>
								<li>
                  <?php echo $recoverForm->label($recoverModel,'email',array('class'=>'desc')); ?>
									<div>
										<?php echo $recoverForm->textField($recoverModel,'email',array('class'=>'field text full')); ?>
										<?php echo $recoverForm->error($recoverModel, 'email'); ?>
									</div>
								</li>															
								<li class="buttons">
									<div id="recover_loading">
										<?php echo CHtml::htmlButton(Yii::t('backend', 'STR_SEND_NEW_PASSWORD'),array('class'=>"ui-state-default ui-corner-all float-right ui-button",'id'=>'do-recover','type'=>'submit',)); ?>
									</div>
								</li>
							</ul>
						<?php $this->endWidget(); ?>
						
						<div class="other-box yellow-box ui-corner-all" id="email-sent" style="display:none">
      					<div class="cont ui-corner-all">
      						<p><?php echo Yii::t('backend','MSG_EMAIL_HAS_BEEN_SENT_CHECK_EMAIL'); ?></p>
      					</div>
            </div
<?php else: ?>

<div class="other-box yellow-box ui-corner-all">
					<div class="cont ui-corner-all">
						<p><?php echo Yii::app()->user->getflash('recover'); ?></p>
					</div>
</div

<?php endif?>						