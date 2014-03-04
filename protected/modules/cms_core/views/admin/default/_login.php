          <?php $loginForm = $this->beginWidget('CActiveForm', array(
              'id'=>'login-form',
              //'enableAjaxValidation'=>false,
              //'focus'=>array($login_model,'username'),
          )); ?>
							<ul>
								<li>
                  <?php echo $loginForm->label($loginModel,'username',array('class'=>'desc')); ?>
									<div>
										<?php echo $loginForm->textField($loginModel,'username',array('class'=>'field text full')); ?>
									</div>
								</li>
								<li>
									<?php echo $loginForm->label($loginModel,'password',array('class'=>'desc')); ?>	
									<div>
									 <?php echo $loginForm->passwordField($loginModel,'password',array('class'=>'field text full')); ?>
									 <?php echo $loginForm->error($loginModel, 'password'); ?>
									</div>
								</li>
								<li class="buttons">
									<div id="login_loading">
										<?php echo CHtml::htmlButton(Yii::t('backend', 'STR_TO_LOGIN'),array('class'=>"ui-state-default ui-corner-all float-right ui-button",'type'=>'submit','id'=>'submit-login')); ?>
									</div>
								</li>
							</ul>	
						<?php $this->endWidget(); ?>