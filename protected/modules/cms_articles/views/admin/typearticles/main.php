<div class="column-content-box"<?php if($articles): ?> style="display:none"<?php endif;?>" id="catedit">
  <div class="content-box content-box-header ui-corner-all">
    <div class="ui-state-default ui-corner-top ui-box-header">
      <span class="ui-icon float-left ui-icon-newwin"></span>
      <?php echo ($content->isNewRecord?Yii::t('backend', 'STR_NEW_TYPE'):Yii::t('backend', 'STR_EDIT_TYPE')).': '.$typeName; ?>
      <a href="#" id="content-cancel-win"><span class="ui-icon float-left ui-icon-closethick" style="float:right" title="<?php echo Yii::t('backend', 'STR_CLOSE') ?>"></span></a>
    </div> 
  	<div class="content-box-wrapper">     
        <?php echo $this->renderPartial('_category', array('content'=>$content, 'id'=>$id)); ?> 
    </div>              
  </div>
</div>
<?php if($articles): ?>
<div id="loader-box"></div>
<?php if(count($articles)>0): ?>
<?php
Yii::app()->clientScript->registerScript
(
  'actionsControls',
  '
    $(".article-edit,.yiiPager li").unbind();
    $("#news-grid").delegate(
    ".article-edit",
    "click",
    function(){
      $("#loader-box").hide();  
      var id=$(this).attr("href").replace("#", "");
      $("html,body").animate({scrollTop: $("#page-content-wrapper").offset().top}, 200);
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
        "url":"'.Yii::app()->request->getBaseUrl().'/cms_articles/admin/typearticles/articleIndex",
        "cache":false,
    		success: function (data) {  
  					if(data.status==1) {
              $("#loader-box").empty().show().html(data.result);
              $("#article-actions").html(data.small);                 			
              //$("#actions-disable").fadeTo(400,0.2);
              $("#article-actions").show();
              $("#main-actions").hide();    
              $("#articles-list #content-cancel-win").hide();	   	
  					}
  					else {
              //$("#actions-disable").hide(); 
              $("#article-actions").hide();
              $("#main-actions").show();  
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
<div class="column-content-box"  id="articles-list">
  <div class="content-box content-box-header ui-corner-all">
    <div class="ui-state-default ui-corner-top ui-box-header">
      <span class="ui-icon float-left ui-icon-newwin"></span>
      <?php echo Yii::t('backend', 'STR_ARTICLE_LIST').": ".$content->name ?>
      <a href="#" id="content-cancel-win"><span class="ui-icon float-left ui-icon-closethick" style="float:right" title="<?php echo Yii::t('backend', 'STR_CLOSE') ?>"></span></a>
    </div> 
  	<div class="content-box-wrapper">   	
      <div class="hastable">       
      <?php echo $this->renderPartial('_catgrid', array('articles'=>$articles)); ?>   
      </div>     
    </div>              
  </div>
</div>
<?php endif;?>
<?php endif;?>