<?php
//jquery UI  
Yii::app()->clientScript->registerCoreScript('jquery.ui'); 
Yii::app()->clientScript->registerScriptFile($this->jsDir.'/jquery-ui-timepicker-addon.js', CClientScript::POS_HEAD);

Yii::app()->clientScript->registerScriptFile($this->jsDir.'/jquery.notify.min.js', CClientScript::POS_HEAD);

/*$juiPathAlias = 'system.web.js.source.jui';
$basePath=Yii::getPathOfAlias($juiPathAlias);
$baseUrl=Yii::app()->getAssetManager()->publish($basePath, true);
$scriptUrl=$baseUrl.'/js';
Yii::app()->clientScript->registerScriptFile($scriptUrl.'/'.'jquery-ui-i18n.min.js', CClientScript::POS_HEAD);
*/

Yii::app()->clientScript->registerScript
(
  'text-preview',
  '
    
    function preview() {
      for (edId in tinyMCE.editors) tinyMCE.editors[edId].save();
      jQuery.ajax({
        type: "POST",
        data: {
            "data":$("#main-column form").serialize(),                 
        },           
        url:"'.CController::createUrl('/cms_content/view/previewtype').'",
        cache:false,
    		success: function (data) {  
              $("<div id=\"preview\"><iframe frameborder=\"0\" id=\"preview-iframe\" src=\"javascript:void(0)\" style=\"width:100%;height:100%\"></iframe></div>").dialog({title:"'.Yii::t('backend','STR_PREVIEW').'",width:$(document).width()-100,height:$(document).height()-250,modal:true, close: function(ev, ui) {$(this).remove();}});
		          _win=$("#preview-iframe")[0].contentWindow;
		          _jWin=$(_win);
		          try{
			          _doc=_win.document;
                _jDoc=$(_doc);
			          _doc.open();
			          _doc.write(data);
			          _doc.close();
		          }catch(e){}
		            //_win.setInterval=null;                        				
    		      },
    		error: function (xhr, ajaxOptions, thrownError) {    
          alert(xhr.responseText);             
    		}    		
        });         
    }               
  ',
	CClientScript::POS_LOAD 
);

Yii::app()->clientScript->registerScript
(
  'article-tabs',
  '
    $("#article-tabs").tabs({"select": 0});
  ',
	CClientScript::POS_LOAD 
);

Yii::app()->clientScript->registerScript
(
  'datepicker',
  '   
    $(".s_datepicker").datetimepicker({"dateFormat": "yy-mm-dd",
                                       "timeFormat":"hh:mm:ss",
                                       "showOn":"button",
			                                 "buttonImage": "'.$this->cssDir.'/icons/calendar.png",
			                                 "buttonImageOnly": true
                                    });   
    //$(".s_datepicker").datepicker();  
  ',
	CClientScript::POS_READY
);


Yii::app()->clientScript->registerScript
(
  'articleAction',
  '
  
  function refreshContent() {
          	 $.ajax({
          	   type: "POST",
          		 url: "'.Yii::app()->request->getBaseUrl().'/cms_articles/admin/typearticles",
          		 dataType:"json",
          		 data : { 
                  "id": '.$content->id.',		
                  "type": "'.$content->type.'",  
                  "edit": 1       
          		 }, 
          	 	 beforeSend:function(){$("#structure-loader").addClass("loading-blue");},
               complete:function(){$("#structure-loader").removeClass("loading-blue");},  			
          		 success: function (data) {  
        					if(data.status==1) {
        					  $("#main-column").hide();
        		        $("#main-column").empty();
                    $("#main-column").html(data.main);   	
                    $("#main-column").show();			
        					}
        					else {
        					
        					}
          		},
          		error: function (err) {
                   
              }
              });   
  }  
  
  function articleAction(action) {
      var submit=false;
      for (edId in tinyMCE.editors) tinyMCE.editors[edId].save();        
      $("#loader-box").show();
      jQuery.ajax({
        "type": "POST",
  		  "dataType":"json",  
  		  async : false,
        "data": {
            "form":$("#article-form").serialize(),
            "small":$("#article-small").serialize()                       
        },   
        "beforeSend":function(){$("#small-loader").addClass("loading-blue");},
        "complete":function(){$("#small-loader").removeClass("loading-blue");},
        "url":"'.Yii::app()->request->getBaseUrl().'/cms_articles/admin/typearticles/articleSave?action="+action,
        "cache":false,
    		success: function (data) {  
  					if(data.status==1) {       
              $("#actions-disable").fadeOut(300);              
              $("#loader-box").hide();        
              $("#articles-list #content-cancel-win").show();    
              $("#article-actions").empty();   
              $("#main-actions").show();  
              $("#loader-box").empty();
              $("#actions-disable").hide();             
              submit=true;              				
  					}
  					else {
              //$("#loader-box").slideUp(300);  					
              submit=false;
              $("#sub-afrm-btn").click();  
  					}
    		},
    		error: function (data) {  
          $("#actions-disable").fadeOut(300);     
          $("#main-actions").show();  
          $("#loader-box").empty(); 
          $("#article-actions").empty();
    		}    		
        });     
        
        
           
        return submit;
  } 
  ',
	CClientScript::POS_READY 
);

/*Yii::app()->clientScript->registerScript
(
  'plugg',
  '  
    function initxhe()
    { 
      $("#xhe0_iframe").contents().find(".pbox").unbind("dblclick");    
      $("#xhe0_iframe").contents().find(".pbox").bind("dblclick", function(){
        if(confirm("Ar tikrai ištrinti paryškinimą?"))
        {
          $(this).wrapInner("<p/>");      
          $(this).replaceWith($(this).contents());
        }
      });
    }      
  ',
	CClientScript::POS_READY 
);*/


Yii::app()->clientScript->registerScript
(
  'articleControls',
  '    
    var container = $("#anotice-container").notify();

        
    function articlePublish(){
  
        
      if(articleAction("'.($article->isNewRecord?"create":"update").'"))
      {
        refreshContent();  
        container.notify("create", {
            title: "'.Yii::t('backend','STR_NOTICE').'",
            text: "'.Yii::t('backend','STR_PUBLISHED_ARTICLE').'"
        });                   
      }  
      return false;  
    }    
    
    $("#article-create").unbind();
    $("#article-form").delegate("#article-create","click",
    function(){             
      if(articleAction("create"))
        refreshContent();
      return false;  
    }              
    ); 
    
    $("#article-update").unbind();
    $("#article-form").delegate("#article-update","click",
    function(){      
      if(articleAction("update"))
        refreshContent();
      return false;  
    }   
    );   

    $("#loader-box .column-content-box").unbind();
    $("#loader-box .column-content-box").delegate("#article-cancel-win","click",    
    function(){
      $("#actions-disable").hide(); 
      $("#articles-list #content-cancel-win").show();     
      $("#article-actions").hide(); 
      $("#main-actions").show();  
      $("#loader-box").slideUp(300,function(){$("#loader-box").html("")});       
      return false;
    }              
    );      
        
    function articleDelete(){      
      if(confirm("'.Yii::t('backend','QST_DELETE').'"))
      {
                            $.ajax({
                              "type": "POST",
                        		  dataType:"json",        
                              "data": {
                                        "id":'.($article->isNewRecord?-1:$article->id).'                
                                     },   
                              "url":"'.$this->createUrl('/cms_articles/admin/typearticles/ajaxDelete').'",
                              "cache":false,
                          		success: function (data) {    		
                        					if(data.status==1) {   
                                          $("#actions-disable").hide(); 
                                          $("#articles-list #content-cancel-win").show();     
                                          $("#article-actions").hide(); 
                                          $("#main-actions").show();  
                                          $("#loader-box").slideUp(300,function(){$("#loader-box").html("")});       
                                    refreshContent();
                                      container.notify("create", {
                                          title: "'.Yii::t('backend','STR_NOTICE').'",
                                          text: "'.Yii::t('backend','STR_DELETED_ARTICLE').'"
                                      });                   
                                                                         
                                    return false;                                                                        				
                        					}
                          		}                           
                            });
      }
    }                            
  ',
	CClientScript::POS_READY
);

Yii::app()->clientScript->registerScript
(
  'alias-article',
  '
  $("#article-edit-alias").
  click(
    function(){
      $("#article-alias-input").fadeIn(400);
      $("#article-edit-alias").hide();
      $("#article-aliasview").hide();
      $("#article-aliasbox").val($("#article-aliashidden").val());
      return false;
    }              
  );    

  $("#article-cancel-alias").
  click(
    function(){
      $("#article-alias-input").hide();
      $("#article-edit-alias").fadeIn(400);
      $("#article-aliasview").show();      
      return false;
    }              
  );  
  
  $("#article-ok-alias").
  click(
  function(){
      var alias=$("#article-aliasbox").val();
      $("#article-alias-input").hide();
      $("#article-edit-alias").fadeIn(400);
        jQuery.ajax({
          "type": "POST",
    		  dataType:"json",        
          "data": {
                    "name":alias,
                    "id":$("#article-idhidden").val()                 
                 },   
          "url":"'.$this->createUrl('ajaxAlias').'",
          "cache":false,
      		success: function (data) {    		
    					if(data.status==1) {   
                $("#article-aliashidden").val(data.alias);
                $("#article-aliasview").html($("#article-aliashidden").val());
                $("#article-aliasview").show();                                                                         				
    					}
      		}
        });       
     
      return false;
    }              
  );   

  
  $("#TypeArticles_title").
  focusout(
    function(){          
      var name=$("#TypeArticles_title").val();
      var alias=$("#article-aliasbox").val();
      if(name!="" && alias=="")
      {   
                    
        jQuery.ajax({
          "type": "POST",
    		  dataType:"json",        
          "data": {
                    "name":name,
                    "id":$("#article-idhidden").val()                 
                 },   
          "url":"'.$this->createUrl('ajaxAlias').'",
          "cache":false,
      		success: function (data) {    		
    					if(data.status==1) {   
                $("#article-aliashidden").val(data.alias);
                $("#article-aliasview").html($("#article-aliashidden").val());                           
                $("#article-alias-container").fadeIn(400);                                                                         				
    					}
      		}
        });           
      }
    }              
  );  
          
  ',
	CClientScript::POS_READY 
);  


$params=$content->getParams($this->module);

?>


<div class="column-content-box">
  <div class="content-box content-box-header ui-corner-all">
    <div class="ui-state-default ui-corner-top ui-box-header">
      <span class="ui-icon float-left ui-icon-newwin"></span>
      <?php echo $article->isNewRecord?Yii::t('backend', 'STR_NEW_ARTICLE'):Yii::t('backend', 'STR_ARTICLE'); ?>
      <a href="#" id="article-cancel-win"><span class="ui-icon float-left ui-icon-closethick" style="float:right" title="<?php echo Yii::t('backend', 'STR_CLOSE') ?>"></span></a>      
    </div> 
  	<div class="content-box-wrapper">       
    
      <?php $form=$this->beginWidget('CActiveForm', array(
              'id'=>'article-form',
              'enableAjaxValidation'=>true,
              'clientOptions'=>array(
                'validationUrl'=>$this->createUrl('articleAjaxValidation'),
                'validateOnSubmit'=>true,
                'afterValidate'=>'js:function(form, data, hasError){return false;}',
              ) 
      )); ?>    
    <div id="article-tabs">                             
      <ul> 
       <li><a href="#article-main-tab"><?php echo Yii::t('backend','STR_MAIN');?></a></li>      
       <?php if(isset($params['hasGallery']) && $params['hasGallery']): ?>  
       <li><a href="#article-gallery-tab"><?php echo Yii::t('backend','STR_GALLERY');?></a></li>
       <?php endif;?>          
       <li><a href="#article-options-tab"><?php echo Yii::t('backend','STR_OPTIONS');?></a></li>       
      </ul>   
      <div id="article-main-tab">
      <?php echo $article->isNewRecord?'':$form->hiddenField($article,'id'); ?>
      <?php echo $form->hiddenField($article,'content_id'); ?>
      <?php echo $form->hiddenField($article,'alias',array('id'=>'article-aliashidden')); ?>
      <?php echo CHtml::hiddenField('id',$id,array('id'=>'article-idhidden')); ?>     
      <?php echo CHtml::hiddenField('image','',array('id'=>'article-image')); ?>
      <?php echo $form->hiddenField($article,'image_file',array('id'=>'particle-image')); ?>
      <ul>
        <li>
          <?php echo $form->labelEx($article,'title',array('class'=>'desc')); ?>
          <div>
            <?php echo $form->textField($article,'title',array('class'=>'field text full','maxlength'=>128)); ?>
            <?php echo $form->error($article,'title'); ?>
          </div>
        </li>                        
        
        <li id="article-alias-container" <?php if($article->isNewRecord):?>style="display:none"<?php endif;?> >
          <?php echo $form->label($article,'alias',array('class'=>'desc')); ?>
          <div style="color:#7B7B7B;font-size:12px">
            <span style="margin:0"><?php echo Yii::app()->request->getBaseUrl(true).(isset($_POST['lng'])?'/'.$_POST['lng']:''); ?>/</span><span id="article-aliasview" style="margin:0;background:#E5ECF9"><?php echo $article->alias; ?></span>
            <a href="#" style="margin:-5px 0 0 5px" title="<?php echo Yii::t('backend', 'STR_EDIT'); ?>" class="btn_no_text btn ui-state-default ui-corner-all" id="article-edit-alias"><span class="ui-icon ui-icon-wrench"></span></a>
            <span id="article-alias-input" style="display:none;margin:-7px 0 0 5px">
            <?php echo CHtml::textField('alias',$article->alias,array('class'=>'field text medium','maxlength'=>128,'style'=>'float:left;width:200px','id'=>'article-aliasbox')); ?>
            <a href="#" style="margin:2px 0 0 5px" title="<?php echo Yii::t('backend', 'STR_OK'); ?>" class="btn_no_text btn ui-state-default ui-corner-all" id="article-ok-alias"><span class="ui-icon ui-icon-check"></span></a>
            <a href="#" style="margin:2px 0 0 5px" title="<?php echo Yii::t('backend', 'STR_CANCEL'); ?>" class="btn_no_text btn ui-state-default ui-corner-all" id="article-cancel-alias"><span class="ui-icon ui-icon-closethick"></span></a>
            </span>
            <?php echo $form->error($article,'alias'); ?>
          </div>
        </li>    
        <li>      
            <?php if(isset($params['hotOption']) && $params['hotOption']): ?>
						<div class="col">
						  <span>
                <?php echo $form->checkBox($article,'is_hot',array('class'=>'field checkbox')); ?>
							  <?php echo $form->labelEx($article,'is_hot',array('class'=>'choice')); ?>
							</span>
						</div>
            <?php endif;?> 
            <?php if(isset($params['hotOption']) && $params['frontOption']): ?>
						<div class="col">
						  <span>
                <?php echo $form->checkBox($article,'is_front',array('class'=>'field checkbox')); ?>
							  <?php echo $form->labelEx($article,'is_front',array('class'=>'choice')); ?>
							</span>
						</div>			
             <?php endif;?> 			
				</li>    
        <?php if(isset($params['tagsInput']) && $params['tagsInput']): ?>
        <li>
          <?php echo $form->labelEx($article,'tags',array('class'=>'desc')); ?>
          <div>
            <?php echo $form->textField($article,'tags',array('class'=>'field text full','maxlength'=>254)); ?>
            <?php echo $form->error($article,'tags'); ?>
          </div> 
        </li>     
        <?php endif;?> 
        <?php if(isset($params['introText']) && $params['introText']): ?>               
        <li>
          <?php echo $form->labelEx($article,'intro_text',array('class'=>'desc')); ?>
          <div>
            <?php echo $form->textArea($article,'intro_text',array('class'=>'field text full','style'=>'height:80px;')); ?>
            <?php echo $form->error($article,'intro_text'); ?>
          </div>
        </li>   
        <?php endif;?>                      
        <li>      
          <?php echo $form->labelEx($article,'text',array('class'=>'desc')); ?>
        </li>
        </ul>    
    <?php echo $form->textArea($article,'text',array('class'=>'field text full','style'=>'height:500px;width:100%')); ?>
    <script type="text/javascript">
    
    function elFinderBrowser (field_name, url, type, win) {
      var elfinder_url = '<?php echo $this->createUrl('/cms_content/admin/filemanager/popup')?>';    // use an absolute path!
      tinyMCE.activeEditor.windowManager.open({
        file: elfinder_url,
        title: 'elFinder 2.0',
        width: 920,  
        height: 415,
        resizable: 'yes',
        inline: 'yes',    // This parameter only has an effect if you use the inlinepopups plugin!
        popup_css: false, // Disable TinyMCE's default popup CSS
        close_previous: 'no'
      }, {
        window: win,
        input: field_name
      });
      return false;
    }
    
    tinyMCE.init({
            // General options
            mode : "exact",
            elements : "TypeArticles_text",
            theme : "advanced",
            plugins : "autolink,lists,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
    
            // Theme options
            theme_advanced_buttons1 : "code,|,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,formatselect,fontselect,fontsizeselect",
            theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,|,insertdate,inserttime,preview,|,forecolor,backcolor",
            theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,iespell,media,advhr,|,print,|,ltr,rtl",
            theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,spellchecker,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak,|,insertfile,insertimage,|,help,|,fullscreen",
            theme_advanced_toolbar_location : "top",
            theme_advanced_toolbar_align : "left",
            theme_advanced_statusbar_location : "bottom",
            theme_advanced_resizing : true,
    
            file_browser_callback : 'elFinderBrowser',
            
            //language : 'en',
            
            skin : "o2k7",
            skin_variant : "silver",
    
            content_css : "<?php echo Yii::app()->request->baseUrl.'/css/editor.css' ?>",
    });

    
    </script>
          
        <ul>   
          <li>
          <span id="text-preview"><?php echo CHtml::htmlButton(Yii::t('backend', 'STR_PREVIEW'),array('style'=>'font-size:10px','class'=>"ui-state-default ui-corner-all ui-button",'onclick'=>'preview();')); ?></span>       
         </li> 
        </ul>
              
        <?php if(isset($params['imageUpload']) && $params['imageUpload']): ?>      
        <br />
        <br />
        
        <div class="content-box content-box-header ui-corner-all" style="float:left">
							<div class="content-box-wrapper">
								<h3>
                 <?php
             
        $this->widget('cms_core.extensions.uploadify.EuploadifyWidget', 
            array(
                'name'=>'uploadme',
                'options'=> array(
                    'uploader' => $this->createUrl('UploadImage'), 
                    //'cancelImage' => $this->jsDir.'/icons/cancel.png',
                    'auto' => true,
                    'multi' => false,
                    'postData' => array('PHPSESSID' => session_id(),'template_name'=>empty($article->content->template_name)?'default':$article->content->template_name),
                    'fileTypeDesc' => Yii::t('backend','STR_IMAGES'),
                    'fileTypeExts' => '*.gif;*.jpg;*.png',
                    'buttonText' => Yii::t('backend','STR_UPLOAD_PHOTO'),
                    'progressData' => 'all',
                    'wmode'=>'window'
                    ),
                    'callbacks' => array( 
                   //'onError' => 'js:function(evt,queueId,fileObj,errorObj){alert("Error: " + errorObj.type + "\nInfo: " + errorObj.info);}',
                   'onUploadSuccess' => 'js:function(file,data,response){$("#particle-image").val(data);$("#article-image").val(data);$("#imgpreview").html("<img src=" + "'.Yii::app()->request->getBaseUrl().'/tmp/'.'prev_'.'" + data + ".jpg"+" />");}',
                   //'onCancel' => 'function(evt,queueId,fileObj,data){alert("Cancelled");}',
                )
            )); 
            
        ?>
        </h3>
        <div id="imgpreview">
        <?php if($article->image_file<>''):?>
        <img src="<?php echo Common::imageCache($article->image,Yii::app()->getModule('cms_articles')->params->images[(empty($article->content->template_name)?'default':$article->content->template_name).'_admin']); ?>" />
        <?php endif;?>
        </div>
				</div>
			</div>        
      <div style="clear:both"></div> 
	    <?php endif;?>
    </div>
    <?php if(isset($params['hasGallery']) && $params['hasGallery']): ?>  
    <div id="article-gallery-tab">
      <?php $this->renderPartial('cms_gallery.views.admin._gallery',array('id'=>$article->id,'type'=>'articles','params'=>$params['gallery'],'template_name'=>$content->template_name)); ?>  
    </div>
   <?php endif;?> 
    <div id="article-options-tab"
    <ul>  
      <?php if(isset($params['extra_attr']['extra_attr_1']['label'])):?>
      <li>
        <?php echo $form->labelEx($article,'extra_attr_1',array('class'=>'desc','label'=>$params['extra_attr']['extra_attr_1']['label'])); ?>
        <div>
          <?php //echo $form->textField($content,'extra_attr_1',array('class'=>'field text full','maxlength'=>256)); ?>
          <?php 
          if(isset($params['extra_attr']['extra_attr_1']['type']))
          switch($params['extra_attr']['extra_attr_1']['type']){
          case 'checkBox':                  
            echo $form->checkBox($article,'extra_attr_1',array('class'=>'choice')); 
          break;  
          case 'text':                                                                                                                     
            echo $form->textField($article,'extra_attr_1',array('class'=>'field text full'));
          break;
          case 'list':                                      
            $extra_list=isset($params['extra_attr']['extra_attr_1']['options'])?$params['extra_attr']['extra_attr_1']['options']:array(''=>'');                                                                               
            echo $form->dropDownList($article,'extra_attr_1',$extra_list,array('class'=>'field select medium'));
          break;               
          case 'textArea':
          default:                                                                                                                         
            echo $form->textArea($article,'extra_attr_1',array('class'=>'field text full'));
          break;   
          }
          ?>
          <?php echo $form->error($article,'extra_attr_1'); ?>
        </div>
      </li>  
      <?php endif;?> 
      <?php if(isset($params['extra_attr']['extra_attr_2']['label'])):?>
      <li>
        <?php echo $form->labelEx($article,'extra_attr_2',array('class'=>'desc','label'=>$params['extra_attr']['extra_attr_2']['label'])); ?>
        <div>
          <?php //echo $form->textField($content,'extra_attr_2',array('class'=>'field text full','maxlength'=>256)); ?>
          <?php 
          if(isset($params['extra_attr']['extra_attr_2']['type']))
          switch($params['extra_attr']['extra_attr_2']['type']){
          case 'checkBox':          
            echo $form->checkBox($article,'extra_attr_2',array('class'=>'choice')); 
            break;
          case 'text':          
            echo $form->textField($article,'extra_attr_2',array('class'=>'field text full')); 
            break;
          case 'list':                                      
            $extra_list=isset($params['extra_attr']['extra_attr_2']['options'])?$params['extra_attr']['extra_attr_2']['options']:array(''=>'');                                                                               
            echo $form->dropDownList($article,'extra_attr_2',$extra_list,array('class'=>'field select medium'));
          break;              
          case 'textArea':
          default:                                                                                                                        
            echo $form->textArea($article,'extra_attr_2',array('class'=>'field text full')); 
            break;
          }
          ?>
          <?php echo $form->error($article,'extra_attr_2'); ?>
        </div>
      </li>  
      <?php endif;?>   
      <?php if(isset($params['extra_attr']['extra_attr_3']['label'])):?>           
      <li>
        <?php echo $form->labelEx($article,'extra_attr_3',array('class'=>'desc','label'=>$params['extra_attr']['extra_attr_3']['label'])); ?>
        <div>
          <?php //echo $form->textField($content,'extra_attr_3',array('class'=>'field text full','maxlength'=>256)); ?>
          <?php 
          if(isset($params['extra_attr']['extra_attr_3']['type']))
          switch($params['extra_attr']['extra_attr_3']['type']){
          case 'checkBox':                                                         
            echo $form->checkBox($article,'extra_attr_3',array('class'=>'choice')); 
            break;
          case 'text':                                                                                                                      
            echo $form->textField($article,'extra_attr_3',array('class'=>'field text full')); 
            break;
          case 'list':                                      
            $extra_list=isset($params['extra_attr']['extra_attr_3']['options'])?$params['extra_attr']['extra_attr_3']['options']:array(''=>'');                                                                               
            echo $form->dropDownList($article,'extra_attr_3',$extra_list,array('class'=>'field select medium'));
          break;              
          case 'textArea':
          default:                                                                                                                          
            echo $form->textArea($article,'extra_attr_3',array('class'=>'field text full')); 
            break;
          }
          ?>
          <?php echo $form->error($article,'extra_attr_3'); ?>
        </div>
      </li> 
      <?php endif;?>       

      <li>
        <?php echo $form->labelEx($article,'link_target',array('class'=>'desc')); ?>
        <div>
          <?php echo $form->dropDownList($article,'link_target',Content::linkTargetOptions(),array('class'=>'field select small')); ?>
          <?php echo $form->error($article,'link_target'); ?>
        </div>
      </li>
      <li>
        <?php echo $form->labelEx($article,'meta_description',array('class'=>'desc')); ?>
        <div>
          <?php echo $form->textArea($article,'meta_description',array('class'=>'field text full')); ?>
          <?php echo $form->error($article,'meta_description'); ?>
        </div>
      </li>                           
    </ul>  
    </div>
    </div>
                         
      <?php $this->endWidget(); ?>	    
    </div>              
  </div>
</div>     
<input id="sub-afrm-btn" type="submit" name="" style="display:none">      
<?php
//delete dialog
$this->beginWidget('zii.widgets.jui.CJuiDialog',array(
                'id'=>'deleteArticleDialog',                                                                                                      
           			'cssFile'=>false,                
                'options'=>array(
                    'title'=>Yii::t('backend','STR_CONFIRM'),
              			'autoOpen'=>false,
              			'bgiframe'=>true,
              			'resizable'=>false,
              			//'width'=>'500',
              			'modal'=>true,
              			'overlay'=>array(
              				'backgroundColor'=> '#000',
              				'opacity'=> '0.5'
              			),
              			'buttons'=> array(
              				Yii::t('backend', 'STR_NO')=> 'js:function() {$("#deleteArticleDialog").dialog("close");}',              			
              				Yii::t('backend', 'STR_YES')=> 
                      'js:function() {                     
                          $("#deleteArticleDialog").dialog("close");
                          jQuery.ajax({
                            "type": "POST",
                      		  dataType:"json",        
                            "data": {
                                      "id":'.($article->isNewRecord?-1:$article->id).'                
                                   },   
                            "url":"'.$this->createUrl('ajaxDelete').'",
                            "cache":false,
                        		success: function (data) {    		
                      					if(data.status==1) {   
                                  $("#actions-disable").fadeOut(300);
                                  refreshContent();                                                                       				
                      					}
                        		}                           
                          })
                      }',
              			)                    
                ),
                ));
                
echo Yii::t('backend','QST_DELETE');

$this->endWidget('zii.widgets.jui.CJuiDialog');

?>

