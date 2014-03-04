<?php

$this->pageTitle=!empty($data['content']->link_description)?$data['content']->link_description:$data['content']->name;
if(!empty($data['content']->meta_description))
  Yii::app()->clientScript->registerMetaTag($data['content']->meta_description,'description');     
  
//$this->introImg="intro-solutions.jpg";

//$this->breadcrumbs=$data['content']->breadcrumbs;  
                                                        
Yii::app()->clientScript->registerScript
(
  'mainPhoto',
  "         
    $('.gallery-thumbs a').click(function(){
      var link=$(this).attr('href'); 
      $('#mainimg').attr('src', 'data:image/gif;base64,R0lGODlhAQABAIAAAP///////yH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==').attr('src', link);
      $('.gallery-thumb').removeClass('thumb-active');
      $(this).closest('.gallery-thumb').addClass('thumb-active');        
      return false;
    });    
  ",
	CClientScript::POS_READY 
);


Yii::app()->clientScript->registerScript
(
  'prodTabs',
  "         
  $('#tabs').delegate('li a', 'click', function() {          
   var li=$(this).closest('li');            
   var index=li.index();
   li.addClass('active').siblings().removeClass('active');
   $('#'+$(this).closest('ul').attr('rel')+' div.tab-content').hide().eq(index).fadeIn(400,function(){
		$('html, body').animate({
		  scrollTop: $('#tabs').offset().top
		}, 200);
    });     
   return false;
  });    
  ",
	CClientScript::POS_READY 
);

Yii::app()->clientScript->registerScript
(
  'catTabs',
  "         
  $('#cattabs').delegate('li a', 'click', function() {          
   var li=$(this).closest('li');            
   var index=li.index();
   li.addClass('active').siblings().removeClass('active');
   $('#'+$(this).closest('ul').attr('rel')+' div.tab-content').hide().eq(index).fadeIn(600);     
   return false;
  });    
  ",
	CClientScript::POS_READY 
);

$files=array();
$main_files=array();

if($data['content']->root<>'') {
$files=$data['content']->children()->published()->findAll(array('condition'=>"type='file'"));

$parent=$data['content']->parent;
$parent2=$parent->parent;

//$cf=Content::model()->published()->find(array('condition'=>"tag='ligoptp'"));
$main_files=$parent2->children()->published()->findAll(array('condition'=>"type='file'"));

//$pp=Content::getChildsByTag('products');
$cc=$parent2->published()->children()->findAll(array('condition'=>"type='section'"));
//$cc=$parent->children()->published()->findAll(array('condition'=>"type='file'"));
}
?>
   <?php if($data['content']->root<>''):?>
        <div class="intro" style="background-color:#fff">                     
        <div id="cat-tabs-content">
        <?php foreach($cc as $cat):?>
        <div class="prod-list tab-content" <?php if($parent->id==$cat->id) echo 'style="display:block"' ?>>
          <?php foreach($cat->children()->findAll(array('condition'=>"type='text'")) as $item):?>
          <div class="prod-item <?php if($item->alias==$_GET['alias'] || $data['content']->id==$item->alias) echo 'active' ?>">
            <a href="<?php echo $item->url ?>" <?php if(!empty($item->link_target)) echo 'target="'.$item->link_target.'"' ?>>
            <?php
            $image=TypeGallery::model()->findByAttributes(array('content_id'=>$item->id,'type'=>'text'),array('order'=>'pos asc'));
            if($image):
            ?>
            
                <?php if($parent2->tag=='ligoptp'):?>  
                  <img src="<?php echo Common::imageCache($image->image,Yii::app()->getModule('cms_text')->params['images']['products_select']); ?>">
                <?php else:?>
                  <img src="<?php echo Common::imageCache($image->image,Yii::app()->getModule('cms_text')->params['images']['products_select_app']); ?>">     
                   <?php endif;?>     
            <?php endif;?>
            <div class="prod-item-name"><?php echo $item->name ?></div>
            </a>
          </div>
          <?php endforeach; ?>         
          <div class="clr"></div>
        </div>
        <?php endforeach; ?>  
        </div>
        <div class="sub-menu menu-prod">   
          <ul id="cattabs" rel="cat-tabs-content">
          <?php foreach($cc as $item):?>
            <li <?php if($item->alias==$_GET['alias'] || $parent->id==$item->id) echo 'class="active"' ?>><a href="<?php echo $item->url ?>" <?php if(!empty($item->link_target)) echo 'target="'.$item->link_target.'"' ?>><?php echo $item->name ?></a></li>
          <?php endforeach; ?>  
          </ul>
          <div class="clr"></div>
        </div>          

          <div class="intro-img"></div>                
        </div> 

        <div class="sub-menu">   
          <ul>
          <?php foreach(Content::getChildsByTag('products') as $item):
            if($item->menu_name<>''):
          ?>
            <li <?php if($item->alias==$_GET['alias'] || $data['content']->id==$item->alias) echo 'class="active"' ?>><a href="<?php echo $item->url ?>"><?php echo $item->name ?></a></li>
          <?php 
          endif;
          endforeach; ?>  
          </ul>
          <div class="clr"></div>
        </div>
        <?php endif;?>        
        <div class="two-colsp">
            <div class="two-colsp-a">
              <div class="page-m">
                <h1><?php echo $data['content']->name ?></h1>
              </div>
            </div>
            <div class="two-colsp-b">
              <ul id="tabs" rel="prod-tabs-content">
                <li class="active"><a href="#"><?php echo Yii::t('frontend','STR_Overview');?></a></li>
<?php if(!empty($data['type']->extra_field_1)): ?>
                <li><a href="#"><?php echo Yii::t('frontend','STR_Specifications');?></a></li>
<?php endif;?>                
                <li><a href="#"><?php echo Yii::t('frontend','STR_Documents');?></a></li> 
              </ul> 
            </div>          
          </div>
          <div class="content" id="prod-tabs-content">
          
          <div class="two-colsp tab-content" style="display:block">
            <div class="two-colsp-a">
              <div class="page-m">
                <?php echo $data['type']->text ?>
              </div>
            </div>
            <div class="two-colsp-b">
            <?php
            $gallery=TypeGallery::getGallery($data['content']->id,'text');
            if(count($gallery)):
            ?>
              <div class="gallery">
                <?php if(count($gallery)>1):?> 
                <div class="gallery-thumbs">
                <?php foreach($gallery as $n=>$img):?>
                  <div class="gallery-thumb<?php if($n==0) echo ' thumb-active';?>">
                    <a href="<?php echo Common::imageCache($img->image,Yii::app()->getModule('cms_text')->params['images']['products_view']); ?>"><img src="<?php echo Common::imageCache($img->image,Yii::app()->getModule('cms_text')->params['images']['products_thumb']); ?>" alt="" /></a>                                            
                  </div>
                <?php endforeach;?>
                </div>
                <?php endif;?>
                <div class="clr"></div>
                <div class="gallery-main">
                <?php if($parent2->tag=='ligoptp'):?>  
                  <img id="mainimg" src="<?php echo Common::imageCache($gallery[0]->image,Yii::app()->getModule('cms_text')->params['images']['products_view']); ?>">
                <?php else:?>
                  <img id="mainimg" src="<?php echo Common::imageCache($gallery[0]->image,Yii::app()->getModule('cms_text')->params['images']['products_view_app']); ?>">                
                <?php endif?>                 
                </div>
              </div>
              <?php endif;?>
            </div>          
          </div>
          <div class="clr"></div>
<?php if(!empty($data['type']->extra_field_1)): ?>
          <div class="tab-content">
          <div class="page" id="whyligo">
            <h2><?php echo Yii::t('frontend','STR_Specifications');?></h2>
            <?php echo $data['type']->extra_field_1 ?>
          </div> 
          </div>          
<?php endif?>
          <div class="tab-content">
          <div class="page">
          <h2><?php echo Yii::t('frontend','STR_Documents');?></h2>
          <?php if($files || $main_files):?>
            <ul class="files-list">
              <?php foreach($main_files as $file):?>
              <li><a href="<?php echo Yii::app()->request->baseUrl; ?>/media/files/<?php echo $file->var?>" target="_blank"><?php echo $file->name?></a></li>
              <?php endforeach;?>  
              <?php foreach($files as $file):?>
              <li><a href="<?php echo Yii::app()->request->baseUrl; ?>/media/files/<?php echo $file->var?>" target="_blank"><?php echo $file->name?></a></li>
              <?php endforeach;?>                 
            </ul>
          <?php endif;?>  
          </div>
          </div>                    
          </div>          
       