 <div class="flashes">

	<?php if( Yii::app()->user->hasFlash('RightsSuccess')===true ):


    Yii::app()->clientScript->registerScript
    (
      'flash-n',
      "
          notice('".Yii::t('backend',Yii::app()->user->getFlash('RightsSuccess'))."');  
      ",
    	CClientScript::POS_READY
    ) ;
    
	endif; ?>

	<?php if( Yii::app()->user->hasFlash('RightsError')===true ):?>

	    <div class="flash error">

	        <?php echo Yii::app()->user->getFlash('RightsError'); ?>

	    </div>

	<?php endif; ?>

 </div>