<div class="column-content-box">
  <div class="content-box content-box-header ui-corner-all">
    <div class="ui-state-default ui-corner-top ui-box-header">
      <span class="ui-icon float-left ui-icon-newwin"></span>
      <?php echo ($model->isNewRecord?Yii::t('backend', 'STR_NEW_TYPE'):Yii::t('backend', 'STR_EDIT_TYPE')).': '.$typeName; ?>
    </div> 
  	<div class="content-box-wrapper">     
        <?php echo $this->renderPartial('_form', array('model'=>$model,'id'=>$id)); ?> 
    </div>              
  </div>
</div>