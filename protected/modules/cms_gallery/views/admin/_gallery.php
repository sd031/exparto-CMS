<?php

//Yii::app()->clientScript->registerCssFile($this->jsDir.'/colorbox/colorbox.css');
//Yii::app()->clientScript->registerScriptFile($this->jsDir.'/colorbox/jquery.colorbox-min.js', CClientScript::POS_HEAD);

Yii::app()->clientScript->registerScriptFile($this->jsDir.'/jquery.notify.min.js', CClientScript::POS_HEAD);

Yii::app()->clientScript->registerScript
(
  'gallery',
  '
  
  var container = $("#anotice-container").notify();
      
  function addImageItem(data,base) {
    if(data=="false") return;
    var item=$("#item-tpl li").clone();
    var url=base+"/tmp/prev_"+data;
    var url_sq=base+"/tmp/"+data;
    var l=$("#gallery-block li").length;
    item.find(".ui-icon-zoomin").attr("href",url);
    item.find(".add-post-images-pic img").attr("src",url);  
    item.find(".add-post-img-title input").attr("name","upload_images["+l+"][title]");
    item.find(".add-post-img-file input").attr("name","upload_images["+l+"][image]").val(data);    
    item.find(".add-post-img-desc textarea").attr("name","upload_images["+l+"][description]");
    item.find(".add-post-images-pic input").attr("name","upload_images["+l+"][is_main]");      
 
    item.appendTo("#gallery-block"); 
    
    container.notify("create", {
        title: "'.Yii::t('backend','STR_NOTICE').'",
        text: "'.Yii::t('backend','STR_UPLOADED_PHOTO').'"
    });

  } 
            
  function deleteTmpImage(el) 
  {
    if(confirm("'.Yii::t('backend','QST_DELETE').'")) 
    {
      $(el).closest("li").remove();
      container.notify("create", {
          title: "'.Yii::t('backend','STR_NOTICE').'",
          text: "'.Yii::t('backend','STR_DELETED_PHOTO').'"
      });      
    }
    return false;
  }
         
  function deleteImage(item) 
  {
    if(confirm("'.Yii::t('backend','QST_DELETE').'")) 
    {
      id=item.attr("id").replace("img","");
      item.remove();
      $("#del-photos").append("<input type=hidden name=delimg[] value="+id+">");
      container.notify("create", {
          title: "'.Yii::t('backend','STR_NOTICE').'",
          text: "STR_DELETED_PHOTO"
      });       
    }
    return false;
  }        
  
  
  $("ul.gallery > li").unbind();               
  $("ul.gallery > li").click("click",function(ev) {
  
  	var item = $(this);
  	var $target = $(ev.target);
  
  	if ($target.is("a.ui-icon-trash")) {
  		deleteImage(item);
  	//} else if ($target.is("a.ui-icon-zoomin")) {
      return false;
  	} 
  	
  }); 
                    			
  ',
	CClientScript::POS_READY
);

Yii::app()->clientScript->registerScript
(
'init_sort',
'	 
  $("#gallery-block").sortable({handle: "img"});                                			
',
CClientScript::POS_END
);

$gallery=TypeGallery::getGallery($id,$type);        
 
?>

      <div style="clear:both"></div>          
      <h3><?php echo Yii::t('backend', 'STR_IMAGES'); ?></h3>
      <div class="content-box content-box-header ui-corner-all" style="float:left;">
      <div class="content-box-wrapper">
      <h3>
      <?php                      
        $this->widget('cms_core.extensions.uploadify.EuploadifyWidget', 
            array(
                'name'=>'cms_gallery',
                'options'=> array(
                    'uploader' => $this->createUrl('/cms_gallery/admin/typegallery/uploadImage'), 
                    'auto' => true,
                    'multi' => true,
                    'requeueErrors' => false,
                    'postData' => array('PHPSESSID' => session_id(),'module'=>$this->module->id,'template_name'=>isset($template_name)?$template_name:''),
                    'fileTypeDesc' => Yii::t('backend','STR_IMAGES'),
                    'fileTypeExts' => '*.gif;*.jpg;*.png',
                    'buttonText' => Yii::t('backend','STR_UPLOAD_PHOTO'),
                    'wmode'=>'window'
                    ),
                    'callbacks' => array( 
                   'onUploadError' => 'js:function(file, errorCode, errorMsg, errorString){alert("The file " + file.name + " could not be uploaded: " + errorString)}',
                   'onUploadSuccess' => 'js:function(file,data,response){addImageItem(data,"'.Yii::app()->request->getBaseUrl().'")}',
                   //'onCancel' => 'function(evt,queueId,fileObj,data){alert("Cancelled");}',
                )
            )); 
            
        ?>    
      </h3>           
      <div id="del-photos"></div>
			<ul id="gallery-block" class="gallery ui-helper-reset ui-helper-clearfix">
			<?php if($gallery): ?>
			  <?php foreach($gallery as $n=>$img):?>
				<li class="ui-widget-content ui-corner-tr" id="img<?php echo $img->id ?>"  style="width:<?php echo isset($params['admin_image']['width'])?$params['admin_image']['width']:'185' ?>px"> 
          <?php if(isset($params['hasTitle']) && $params['hasTitle']==true): ?>        
          <input placeholder="<?php echo Yii::t('backend','STR_TITLE')?>" name="edit_images[<?php echo $img->id ?>][title]" style="width:99%" value="<?php echo $img->title?>"/>
          <?php endif; ?>                  
          <div style="overflow:hidden;width:<?php echo isset($params['admin_image']['width'])?$params['admin_image']['width']:'185' ?>px;height:<?php echo isset($params['admin_image']['height'])?$params['admin_image']['height']:'136' ?>px">              
					  <img src="<?php echo Common::imageCache($img->image,isset($params['admin_image'])?$params['admin_image']:array('width'=>'185','height'=>'136'));  ?>" title="<?php echo $img->title ?>"/>
          </div>
          <?php if(isset($params['hasDescription']) && $params['hasDescription']==true): ?> 
          <textarea placeholder="<?php echo Yii::t('backend','STR_DESCRIPTION')?>" name="edit_images[<?php echo $img->id ?>][description]" style="width:99%" ><?php echo $img->description?></textarea>
          <?php endif; ?>                   
					<!--<a href="<?php echo Common::imageCache($img->image,isset($params['admin_image'])?$params['admin_image']:array('width'=>'185','height'=>'136')); ?>" title="" class="ui-icon ui-icon-zoomin" style="float:left" onclick="galzoom(this);return false;"></a>-->
          <a href="#" title="<?php echo Yii::t('backend','STR_DELETE'); ?>" class="ui-icon ui-icon-trash"></a>
          <div class="add-post-img-file">  
            <input type="hidden" name="edit_images[<?php echo $img->id ?>][dummy]" value="0" />                      
          </div>                 
				</li>
				<?php endforeach;?>
      <?php endif;?> 
			</ul>   
      <!-- upload image item tpl -->
      <div id="item-tpl" style="display:none"> 
      <li class="ui-widget-content ui-corner-tr" style="width:<?php echo isset($params['admin_image']['width'])?$params['admin_image']['width']:'185' ?>px">
        <?php if(isset($params['hasTitle']) && $params['hasTitle']==true): ?>   
        <div class="add-post-img-title">
          <input placeholder="<?php echo Yii::t('backend','STR_TITLE')?>" type="text" name="null" style="width:99%" value=""/>
        </div>
        <?php endif; ?>  
      	<div style="overflow:hidden;width:<?php echo isset($params['admin_image']['width'])?$params['admin_image']['width']:'185' ?>px;height:<?php echo isset($params['admin_image']['height'])?$params['admin_image']['height']:'136' ?>px" class="add-post-images-pic">
        <img src="" />
        <input type="hidden" name="null" value="0" /> 
        </div>
        <?php if(isset($params['hasDescription']) && $params['hasDescription']==true): ?>
        <div class="add-post-img-desc">
          <textarea placeholder="<?php echo Yii::t('backend','STR_DESCRIPTION')?>" name="null" style="width:99%"/></textarea>
        </div>  
        <?php endif;?>         
        <div class="add-post-img-file">  
          <input type="hidden" name="null" value="0" />                      
        </div>       
      	<!--<a href="#" title="" class="ui-icon ui-icon-zoomin" style="float:left"></a>-->
      	<a href="#" title="<?php echo Yii::t('backend','STR_DELETE'); ?>" class="ui-icon ui-icon-trash" onclick="deleteTmpImage(this);return false;"></a>
      </li>      
      </div>            
      <!-- end tpl -->        
      </div>
      </div>            
      <div style="clear:both"></div>  
      
	<div id="anotice-container" style="display:none;top:auto; z-index:99999;right:0; bottom:0; margin:0 10px 10px 0">		
		<div id="anotice" style="z-index:99999">
      <a class="ui-notify-cross ui-notify-close" href="#">x</a>
			<h1>#{title}</h1>
			<p>#{text}</p>
		</div>
	</div>      