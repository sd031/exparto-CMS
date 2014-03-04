<?php



Yii::app()->clientScript->registerScript
(
  'actionsControls',
  '
    $("#article-create").unbind();
    $("#article-create").click(
    function(){
      $("#loader-box").hide();  
      jQuery.ajax({
        "type": "POST",
  		  "dataType":"json",      
        "data": {
            "edit":0,        
            "id":'.$content->id.'         
        },   
        "beforeSend":function(){$("#small-loader").addClass("loading-blue");},
        "complete":function(){$("#small-loader").removeClass("loading-blue");},
        "url":"'.Yii::app()->request->getBaseUrl().'/cms_articles/admin/typearticles/articleIndex",
        "cache":false,
    		success: function (data) {  
  					if(data.status==1) {
              $("#loader-box").html(data.result);
              $("#article-actions").html(data.small);                  	
              $("#loader-box").slideDown(300);			
              $("#actions-disable").fadeTo(400,0.2);
              $("#articles-list #content-cancel-win").hide();	   
              $("#main-actions").hide();                          	
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
    
    $("#category-edit").unbind();
    $("#category-edit").click(
    function(){
      $("#catedit").show();  
      $("#articles-list").hide();
      $("#main-actions").hide();  
      $("#category-actions").show();                     
      return false;
    }  
    );    
        
               
  ',
	CClientScript::POS_READY 
);

Yii::app()->clientScript->registerScript
(
  'article',
  '
  
 
  
    $("#article-publish").unbind();
    $("#article-actions").delegate("#article-publish","click",      
    function(){
      articlePublish();
      return false;
    }              
    );
        
    $("#article-cancel").unbind();
    $("#article-actions").delegate("#article-cancel","click",     
    function(){
      $("#actions-disable").hide(); 
      $("#articles-list #content-cancel-win").show();     
      $("#article-actions").empty(); 
      $("#main-actions").show();  
      $("#loader-box").slideUp(300,function(){$("#loader-box").empty()});       
      return false;
    }              
    ); 

    $("#article-delete a").unbind();   
    $("#article-actions").delegate("#article-delete","click",       
    function(){
      articleDelete();      
      return false;
    }              
    ); 
             
               
  ',
	CClientScript::POS_READY 
);    

?>

<div id="article-actions-container">
  <div id="article-actions">
  
  </div>
	<div id="anotice-container" style="display:none;top:auto; z-index:99999;right:0; bottom:0; margin:0 10px 10px 0">		
		<div id="anotice" style="z-index:99999">
      <a class="ui-notify-cross ui-notify-close" href="#">x</a>
			<h1>#{title}</h1>
			<p>#{text}</p>
		</div>
	</div>      
</div>  

<div id="category-actions" style="display:none">
<?php
$this->renderPartial('cms_content.views.admin._small_column',array('content'=>$content,'actions'=>true,'publication'=>true));   
?>
</div>

<div id="main-actions">
<div class="content-box content-box-header">
  <div class="content-box-wrapper">
    <div class="content">
      <h4><?php echo Yii::t('backend','STR_ACTIONS');?><span style="float: right;width:15px;height:20px" id="small-loader"></span></h4>
        <div style="position:relative">      
							<a href="#" class="btn ui-state-default full-link ui-corner-all" id="article-create">
								<span class="ui-icon ui-icon-plusthick"></span>
								<?php echo Yii::t('backend','STR_NEW_ARTICLE');?>
							</a>
							<a href="#" class="btn ui-state-default full-link ui-corner-all" id="category-edit">
								<span class="ui-icon ui-icon-wrench"></span>
								<?php echo Yii::t('backend','STR_EDIT_CATEGORY');?>
							</a>
							<a href="#" class="btn ui-state-default full-link ui-corner-all" id="content-cancel">
								<span class="ui-icon ui-icon-closethick"></span>
								<?php echo Yii::t('backend','STR_CLOSE');?>
							</a>	
      <div id="actions-disable" class="ui-widget-overlay" style="display:none"></div>    
      </div>    		
    </div>
	</div>
</div> 
</div>

