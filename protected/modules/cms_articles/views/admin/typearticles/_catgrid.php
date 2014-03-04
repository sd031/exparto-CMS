
        <?php
        $this->widget('zii.widgets.grid.CGridView', array(
          'id'=>'news-grid',
          'pager'=>array('cssFile'=>$this->cssDir.'/pager.css'),
          'cssFile'=>$this->cssDir.'/gridview.css',	
        	'dataProvider' => $articles->search(),     
        	'filter'=>$articles,	
          'selectableRows' => 0,
        	'columns' => array(
            'id',   
            //'content_id',		
            'title',          
        		array(          
              'name'=>'rec_created',
              'htmlOptions'=>array('nowrap'=>'nowrap'),
            ),
        		array(          
              'name'=>'rec_modified',
              'htmlOptions'=>array('nowrap'=>'nowrap'),
            ),            
            array( 
              'class'=>'CLinkColumn',
              'header'=>'',
              'label'=>'<span class="ui-icon ui-icon-wrench"></span>',
              'urlExpression'=>'"#".$data->id',     
              'linkHtmlOptions'=>array('class'=>'btn_no_text btn ui-state-default ui-corner-all article-edit','title'=>Yii::t('backend','STR_EDIT')),
              'htmlOptions'=>array('style'=>'width:10px'),   
          ),             
        	),
        ));
        ?>   