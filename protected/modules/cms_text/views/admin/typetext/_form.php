<?php
//jquery UI
Yii::app()->clientScript->registerCoreScript('jquery.ui');

Yii::app()->clientScript->registerScript
(
  'codemirror',
  '
    var editor;

    function CodeMirrorLoad()
    {
      tinyMCE.execCommand("mceToggleEditor",false,"TypeText_text");
      editor = CodeMirror.fromTextArea(document.getElementById("TypeText_text"), {
        lineNumbers: true,
        matchBrackets: true,
        mode: "application/x-httpd-php",
        indentUnit: 4,
        indentWithTabs: true,
        enterMode: "keep",
        tabMode: "shift"
      });

      editor.setValue(document.getElementById("TypeText_text").value);                 
    } 
    
    function CodeMirrorUnload()
    {    
      document.getElementById("TypeText_text").value=editor.getValue();
      editor.toTextArea();
      tinyMCE.execCommand("mceToggleEditor",true,"TypeText_text");
    }  
  ',
	CClientScript::POS_READY
);

Yii::app()->clientScript->registerScript
(
  'form-tabs',
  '
    $("#form-tabs").tabs({"select": 0});
  ',
	CClientScript::POS_LOAD 
);

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
  'cp_title',
  '
  $("#TypeText_title").
  focusin(
    function(){
      var name=$("#Content_name").val();
      var title=$("#TypeText_title").val();
      
      if(name!="" && title=="")
      {
        $("#TypeText_title").val(name);  
      }       
    }
    );  
    
  $("#Content_name").
  focusout(
    function(){          
      var name=$("#Content_name").val();
      var title=$("#TypeText_title").val();
      if(name!="" && title=="")
      {
        $("#TypeText_title").val(name);  
      } 
    }              
  );      
  ',
	CClientScript::POS_LOAD 
);

$params=$content->getParams($this->module);

?>

<?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'text-form',
        'enableAjaxValidation'=>true,
        'enableClientValidation'=>false,
        'clientOptions'=>array(
          'validationUrl'=>$this->createUrl('ajaxValidation'),
          'validateOnSubmit'=>true,
          'afterValidate'=>'js:function(form, data, hasError){return false;}',     
        ) 
)); ?>


<div id="form-tabs">                             
  <ul> 
   <li><a href="#form-main-tab"><?php echo Yii::t('backend','STR_MAIN');?></a></li>
   <?php if(isset($params['hasGallery']) && $params['hasGallery']): ?>  
   <li><a href="#form-gallery-tab"><?php echo Yii::t('backend','STR_GALLERY');?></a></li>
   <?php endif;?>        
   <li><a href="#form-options-tab"><?php echo Yii::t('backend','STR_OPTIONS');?></a></li>    
  </ul>                         

<?php echo $form->hiddenField($content,'type'); ?>
<?php echo $form->hiddenField($text,'editor_mode',array('id'=>'editor-mode')); ?>
<?php echo $form->hiddenField($content,'alias',array('id'=>'aliashidden')); ?>
<?php echo CHtml::hiddenField('id',$id,array('id'=>'idhidden')); ?>
  <div id="form-main-tab">
    <ul>
      <li>
        <?php echo $form->labelEx($content,'name',array('class'=>'desc')); ?>
        <div>
          <?php echo $form->textField($content,'name',array('class'=>'field text full','maxlength'=>128)); ?>
          <?php echo $form->error($content,'name'); ?>
        </div>
      </li>
      <li>
        <?php echo $form->labelEx($text,'title',array('class'=>'desc')); ?>
        <div>
          <?php echo $form->textField($text,'title',array('class'=>'field text full','maxlength'=>256)); ?>
          <?php //echo $form->error($text,'title'); ?>
        </div>
      </li>
      <?php $this->renderPartial('cms_content.views.admin._alias',array('content'=>$content,'form'=>$form)); ?>     
      <li style="padding-bottom:0">
      <?php
      //$label=null; 
      //if(isset($params['textLabel']))
        //$label=array('label'=>$params['textLabel']); 
        //echo $params['textLabel']; 
      ?>
      <?php echo $form->labelEx($text,'text',array('class'=>'desc')); ?> 
      </li>     

    <?php echo $form->textArea($text,'text',array('class'=>'field text full','style'=>'height:500px;width:100%')); ?>
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
            elements : "TypeText_text",
            theme : "advanced",
            plugins : "autolink,lists,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
    
            // Theme options
            theme_advanced_buttons1 : "code,|,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
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

    <?php if($text->editor_mode==1) echo 'tinyMCE.execCommand("mceToggleEditor",false,"TypeText_text");CodeMirrorLoad();'; ?>
    
    </script>
    <ul>
      <li>
      <span id="text-preview"><?php echo CHtml::htmlButton(Yii::t('backend', 'STR_PREVIEW'),array('style'=>'font-size:10px','class'=>"ui-state-default ui-corner-all ui-button",'onclick'=>'preview();')); ?></span>       
      <span id="editor-di" style="float:right;<?php if($text->editor_mode==1) echo 'display:none'?>"><?php echo CHtml::htmlButton(Yii::t('backend', 'STR_DISABLE_EDITOR'),array('style'=>'font-size:10px','class'=>"ui-state-default ui-corner-all ui-button",'onclick'=>'$("#editor-di").hide();$("#editor-en").show();$("#editor-mode").val(1);CodeMirrorLoad();')); ?></span>
      <span id="editor-en" style="float:right;<?php if($text->editor_mode==0) echo 'display:none'?>"><?php echo CHtml::htmlButton(Yii::t('backend', 'STR_ENABLE_EDITOR'),array('style'=>'font-size:10px','class'=>"ui-state-default ui-corner-all ui-button",'onclick'=>'$("#editor-en").hide();$("#editor-di").show();$("#editor-mode").val(0);CodeMirrorUnload();')); ?></span>  
      </li> 
    </ul>
    <?php if(isset($params['extra_fields']['extra_field_1']['label'])):?>
    <ul>
      <li>
        <?php echo $form->labelEx($text,'text',array('class'=>'desc','label'=>$params['extra_fields']['extra_field_1']['label'])); ?>   
      </li>         
    </ul>  
       <?php
        $this->widget('cms_core.extensions.dxheditor.DxhEditor', array(
            'model'=>$text,       
            'id'=>'extra_field_1',                                        
            'attribute'=>'extra_field_1',
            'htmlOptions'=>array('style'=>'height:350px;width:99%'),
            'language'=>'en',
            'enabled'=>!$text->editor_mode,
            'on_load'=>($text->editor_mode==1?'CodeMirrorLoad();':'initxhe();'),
            'options'=>array(
                'skin'=>'o2007silver',         
                'submitID'=>'content_action .subbtn',
                'urlBase'=>Yii::app()->request->baseUrl,
                'upBtnText'=>Yii::t('backend','STR_UPLOAD'),
                'upMultiple'=>5,
                'loadCSS'=>Yii::app()->request->baseUrl.'/css/editor.css',
                'tools'=>'Cut,Copy,Paste,Pastetext,|,Styletag,Blocktag,Fontface,FontSize,Bold,Italic,Underline,Strikethrough,FontColor,BackColor,SelectAll,Removeformat,|,Align,List,Outdent,Indent,|,Link,Unlink,Img,Flash,Media,Table,|,Source,Fullscreen',
                'upLinkUrl'=>$this->createUrl('/cms_core/admin/editorupload/index'),
                'upLinkExt'=>'zip,rar,7z,txt,doc,xls,ppt,docx,xlsx,pptx,pdf',
                'upImgUrl'=>$this->createUrl('/cms_core/admin/editorupload/index'),
                'upFlashUrl'=>$this->createUrl('/cms_core/admin/editorupload/index'),
                'upMediaUrl'=>$this->createUrl('/cms_core/admin/editorupload/index'),
            ),
        ));            
      ?> 
    <?php endif;?>      
  </div>   
  <?php if(isset($params['hasGallery']) && $params['hasGallery']): ?>  
  <div id="form-gallery-tab">
    <?php $this->renderPartial('cms_gallery.views.admin._gallery',array('id'=>$content->id,'type'=>'text','params'=>$params['gallery'],'template_name'=>$content->template_name)); ?>  
  </div>
 <?php endif;?>   
  <div id="form-options-tab">
    <?php $this->renderPartial('cms_content.views.admin._options',array('content'=>$content,'form'=>$form)); ?>    
  </div>
</div>
<input id="sub-frm-btn" type="submit" name="" style="display:none">
<?php $this->endWidget(); ?>	