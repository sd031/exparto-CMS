<?php

Yii::app()->clientScript->registerScript
(
  'menu',
  '
    $("#menu ul").each(function(){    
      $(this).css("min-width", $(this).parent().width());
    });
    
    $("#menu > li").hover(
      function(){
        $("#menu > li").removeClass("active"); 
        $(this).addClass("active");   
        $(this).find("ul").stop(0,1).fadeIn(400);     
      },
      function(){
        $(this).removeClass("active"); 
        $("#menu > li.showing").addClass("active"); 
        $(this).find("ul").hide();     
      }      
    );  
  ',
	CClientScript::POS_READY 
);

Yii::app()->clientScript->registerScript
(
  'placeholder',
  "  			
    $('[placeholder]').focus(function() {
      var input = $(this);
      if (input.val() == input.attr('placeholder')) {
        input.val('');
        input.removeClass('placeholder');
      }
    }).blur(function() {
      var input = $(this);
      if (input.val() == '' || input.val() == input.attr('placeholder')) {
        input.addClass('placeholder');
        input.val(input.attr('placeholder'));
      }
    }).blur();
    $('[placeholder]').parents('form').submit(function() {
      $(this).find('[placeholder]').each(function() {
        var input = $(this);
        if (input.val() == input.attr('placeholder')) {
          input.val('');
        }
      })
    });    
  ",
	CClientScript::POS_READY 
);


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/reset.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
  <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/slides.min.jquery.js', CClientScript::POS_HEAD);?>    
	<title><?php echo CHtml::encode((empty($this->pageTitle)?Yii::app()->name:$this->pageTitle)); ?></title>
</head>
<body>
  <div class="container">
      <div class="lang">
          <?php 
          Yii::app()->getModule('cms_content');
	        $langs=Language::getList();
          foreach($langs as $lng):
          ?>    
          <?php if($lng->lang_code=="en"):?>    
          <div><a href="<?php echo Yii::app()->homeUrl ?>"><?php echo $lng->short_name; ?></a></div>
          <?php else:?>
          <div><a href="<?php echo Yii::app()->homeUrl.$lng->lang_code; ?>"><?php echo $lng->short_name; ?></a></div>          
          <?php endif?>           
          <?php
          endforeach;
          ?>
      </div>
      <div class="clr"></div> 
      <div class="header">
        <a href="/" class="logo">Demo site</a>
        <?php $this->widget('zii.widgets.CMenu',array('id'=>'menu','items'=>Content::getTreeMenu('main_menu',2)));?> 
        <div class="search-box">
          <form action="<?php echo $this->createUrl('/site/search') ?>" method="get">
            <input type="text" name="k" value="" class="search-box-in" placeholder="<?php echo Yii::t('frontend','STR_Search')?>"/>
            <input type="submit" value="Go" class="search-box-b"/>
          </form> 
        </div>                     
      </div>
      <div class="clr"></div> 
      <div class="content">        
          <?php if($this->action->id=='index'):?>
          <div class="intro">
          <div id="slides">
              <div class="slides_container">
              <?php 
              foreach(TypeGallery::getGalleryByTag('slides') as $img):
              ?>
                  <div>
                      <img src="<?php echo Common::imageCache($img->image,Yii::app()->getModule('cms_gallery')->params['images']['gallery_slides']); ?>" alt="" />
                  </div>
              <?php endforeach?>                        
              </div>    
              <div class="intro-img"></div>        
          </div>
      		<script type="text/javascript">//<![CDATA[
      			$('#slides').slides({container: 'slides_container', preload: true, preloadImage: 'img/loading.gif', play: 5000,pause: 2500,	hoverPause: true});
      		//]]></script>
          </div>
          <?php elseif(!empty($this->introImg)):?>          
          <div class="intro">
            <img src="<?php echo Yii::app()->request->baseUrl; ?>/img/<?php echo $this->introImg ?>" alt="<?php echo $this->pageTitle ?>" />
            <div class="intro-img"></div>  
          	<?php if(isset($this->breadcrumbs)):?>
          		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
          			'links'=>$this->breadcrumbs,
          		)); ?>
          	<?php endif?>                  
          </div> 
          <?php elseif(isset($this->breadcrumbs)):?>
          <div class="intro-bc">
              <?php $this->widget('zii.widgets.CBreadcrumbs', array(
          			'links'=>$this->breadcrumbs,
          		)); ?>
          </div>
          <?php endif;?>                                                       
        <?php echo $content; ?>                  
      </div>
      <div class="footer">
        <div class="footer-menu">
          <div class="footer-holder">
          <?php $this->widget('zii.widgets.CMenu',array('items'=>Content::getTreeMenu('bottom_menu',1)));?> 
          <div class="footer-cp">
           Demo site &copy; <?php echo date('Y'); ?>. <?php echo Yii::t("frontend","STR_All rights reserved") ?>      
          </div>                                                                                                                
          <div class="clr"></div>
          </div>            
        </div>
  </div>          

  </div>
  <div class="clr"></div>  
</body>
</html>
