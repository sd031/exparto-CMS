
<?php
  $this->title=Yii::t('backend','STR_RIGHTS');
  $this->pageTitle=$this->title.' - '.Yii::app()->name;
?>

<?php $this->beginContent(Rights::module()->appLayout); ?>

<div class="column-content-box">
  <div class="content-box content-box-header ui-corner-all">
		<div class="content-box-wrapper"> 
        <div id="rights" class="container">
	     <div id="content">
      		<?php if( $this->id!=='install' ): ?>
      
      		<div id="menu">  
      				<?php $this->renderPartial('/_menu'); ?>
              <div style="clear:both"></div>
            
      		</div>
     
      		<?php endif; ?>
      
      		<?php $this->renderPartial('/_flash'); ?>
      
      		<?php echo $content; ?>
      
      	</div><!-- content -->
        <div>
        </div>
      <div style="clear:both"></div>      
    </div>              
  </div>
</div>

<?php $this->endContent(); ?>
