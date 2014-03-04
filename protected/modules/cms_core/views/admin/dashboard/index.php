<?php
  $cols=array_chunk($dashboard, 1);
  $this->title=Yii::t('backend', 'STR_DASHBOARD');
  $this->pageTitle=$this->title.' - '.Yii::app()->name;
?>

				<div class="two-column">
         <div class="column">        
         <?php 
         if(isset($cols[0]))
         foreach($cols[0] as $items): 
         ?>
						<div class="portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all">
							<div class="portlet-header ui-widget-header"><?php echo $items['title'] ?><!--<span class="ui-icon ui-icon-circle-arrow-s"></span>--></div>
							<div class="portlet-content">
                <?php echo $items['render'] ?>
							</div>
						</div>
          <?php endforeach;?>          
				</div>
 
				
				 <div class="column column-right">        
         <?php
         if(isset($cols[1]))
         foreach($cols[1] as $items): 
         ?>
						<div class="portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all">
							<div class="portlet-header ui-widget-header"><?php echo $items['title'] ?><!--<span class="ui-icon ui-icon-circle-arrow-s"></span>--></div>
							<div class="portlet-content">
                <?php echo $items['render'] ?>
							</div>
						</div>
          <?php endforeach;?> 
					</div>
				</div>
				<div class="clear"></div>