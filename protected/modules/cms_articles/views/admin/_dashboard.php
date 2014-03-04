<div class="hastable">
<table cellspacing="0">
	<thead>
		<tr>
			<td>ID</td>
			<td><?php echo Yii::t('backend','STR_CONTENT')?></td>
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
				<?php echo $item->content->name?>
			</td>
			<td>
				<?php echo $item->title ?>
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