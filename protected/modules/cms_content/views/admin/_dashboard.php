<?php
$mod=CmsModule::loadModule('cms_content');
$contentType=new CmsTypes;
$contentType->init();
?>
<div class="hastable">
<table cellspacing="0">
	<thead>
		<tr>
			<td>ID</td>
			<td><?php echo Yii::t('backend','STR_TYPE')?></td>
			<td><?php echo Yii::t('backend','STR_TITLE')?></td>
			<td><?php echo Yii::t('backend', 'STR_RECORD_CREATE_DATE')?></td> 
			<td><?php echo Yii::t('backend', 'STR_RECORD_MODIFY_DATE')?></td>       
		</tr>
	</thead>
	<tbody>
    <?php foreach($items as $item):?>
		<tr>  	  	
			<td>
				<?php echo $item->id ?>
			</td>
			<td>
				<?php echo $contentType->getName($item->type)?>
			</td>
			<td>
				<a href="<?php echo Yii::app()->createUrl('/cms_content/admin/default').'#'.$item->id?>"><?php echo $item->name ?></a>
			</td>      
			<td>
				<?php echo $item->rec_created ?>
			</td>
			<td>
				<?php echo $item->rec_modified ?>
			</td>
		</tr>
    <?php endforeach;?>
	</tbody>
</table>
</div>