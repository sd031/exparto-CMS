<?php
  $this->title=Yii::t('backend', 'STR_USER_NEWS');
  $this->pageTitle=$this->title.' - '.Yii::app()->name;
?>

<?php
Yii::app()->clientScript->registerScript
(
    'delete',
    '
    $(".delbtn").unbind("click");
    $(".delbtn").click(
      function(){
        $.fn.yiiGridView.update("comment-grid");   
        if(confirm("'.Yii::t('backend','QST_DELETE').'"))
        {
          var id=$(this).attr("href").replace("#", "");
          jQuery.ajax({
            "type": "POST",
      		  dataType:"json",        
            "data": {
                      "id":id                 
                   },   
            "url":"'.$this->createUrl('ajaxDelete').'",
            "cache":false,
        		success: function (data) {    		
      					if(data.status==1) {   
                    $.fn.yiiGridView.update("comment-grid");                                                                       				
      					}
        		}
          });
        }
        return false;
      }              
    );
    ',
    CClientScript::POS_END
);

?>
<?php
Yii::app()->clientScript->registerScript
(
  'actionsControls',
  '
    $(".article-edit").unbind();
    $(".article-edit").click(
    function(){
      $("#loader-box").hide();  
      var id=$(this).attr("href").replace("#", "");
      jQuery.ajax({
        "type": "POST",
  		  "dataType":"json",      
        "data": {
            "edit":1,        
            "id":'.$content->id.',            
            "article_id": id         
        },   
        "beforeSend":function(){$("#small-loader").addClass("loading-blue");},
        "complete":function(){$("#small-loader").removeClass("loading-blue");},
        "url":"'.Yii::app()->request->getBaseUrl().'/cms_news/admin/typenews/articleIndex",
        "cache":false,
    		success: function (data) {  
  					if(data.status==1) {
              $("#loader-box").html(data.result);  	
              $("#loader-box").slideDown(300);			
              $("#actions-disable").fadeTo(400,0.2);	
              $("#loader-box").animate( { scrollTop: 0 }, "fast" );
  					}
  					else {
              $("#actions-disable").hide(); 
  					}
    		},
    		error: function (data) {  
          $("#actions-disable").hide(); 
    		}    		
        });                     
      return false;
    }              
    );    
                   
  ',
	CClientScript::POS_READY 
);
?>

<div id="loader-box"></div>
<div class="column-content-box">
  <div class="content-box content-box-header ui-corner-all">
    <div class="ui-state-default ui-corner-top ui-box-header">
      <span class="ui-icon float-left ui-icon-newwin"></span>
      <?php echo Yii::t('backend', 'STR_NOT_CONFIRMED_ARTICLES');?>
    </div> 
		<div class="content-box-wrapper">      
      <div class="hastable">
        <?php
        $this->widget('zii.widgets.grid.CGridView', array(
          'id'=>'comment-grid',
          'pager'=>array('cssFile'=>$this->cssDir.'/pager.css'),
          'cssFile'=>$this->cssDir.'/gridview.css',	
        	'dataProvider' => $article->extsearch(),

        	'columns' => array(
            'id',
            'external_author',      		
            'title',
        		array(          
              'name'=>'rec_created',
              'htmlOptions'=>array('nowrap'=>'nowrap'),
            ),  
            array( 
              'class'=>'CLinkColumn',
              'header'=>'',
              'label'=>'<span class="ui-icon ui-icon-check"></span>',
              'urlExpression'=>'"#".$data->id',     
              'linkHtmlOptions'=>array('class'=>'btn_no_text btn ui-state-default ui-corner-all article-edit','title'=>Yii::t('backend','STR_CONFIRM')),
              'htmlOptions'=>array('style'=>'width:10px'),   
            ),                
            array( 
              'class'=>'CLinkColumn',
              'header'=>'',
              'label'=>'<span class="ui-icon ui-icon-closethick"></span>',
              'urlExpression'=>'"#".$data->id',     
              'linkHtmlOptions'=>array('class'=>'btn_no_text btn ui-state-default ui-corner-all delbtn','title'=>Yii::t('backend','STR_DELETE')),
              'htmlOptions'=>array('style'=>'width:10px'),     
          ),             
        	),
        ));
        ?>
        <div style="clear:both"></div>
      </div>  
    </div>              
  </div>
</div>