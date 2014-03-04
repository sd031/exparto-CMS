<?php
  $this->title=Yii::t('backend', 'STR_SYSTEM');
  $this->pageTitle=$this->title.' - '.Yii::app()->name;
?>

<div class="column-content-box">
  <div class="content-box content-box-header ui-corner-all">
		<div class="content-box-wrapper"> 
      <h2><?php echo Yii::t('backend', 'STR_MODULES');?></h2>    		
      <div class="hastable">
      <table cellspacing="0">
						<thead>
							<tr>
								<td>ID</td>							
								<td><?php echo Yii::t('backend', 'STR_CAPTION');?></td>
								<td><?php echo Yii::t('backend', 'STR_DESCRIPTION');?></td>
								<td><?php echo Yii::t('backend', 'STR_VERSION');?></td>
								<!--<td><?php echo Yii::t('backend', 'STR_CORE');?></td>
								<td><?php echo Yii::t('backend', 'STR_CONTENT');?></td>-->
							</tr>
						</thead>
						<tbody>
						<?php foreach($info as $id=>$rec):?>
							<tr>  	
								<td>
									<?php echo $id;?>
								</td>                	
								<td>
									<?php echo $rec['name'];?>
								</td>
								<td>
									<?php echo $rec['description'];?>
								</td>
								<td>
									<?php echo $rec['version'];?>
								</td>
								<!--<td>
									<?php echo $rec['is_core'];?>
								</td>
								<td>
									<?php echo $rec['is_content'];?>
								</td>-->
							</tr>
							<?php endforeach;?>
						</tbody>
					</table>           
      </div>
      <div style="clear:both"></div>      
    </div>              
  </div>
</div>