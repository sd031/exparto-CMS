<?php

Yii::app()->clientScript->registerScriptFile($this->jsDir.'/superfish.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile($this->jsDir.'/jquery.cookie.js', CClientScript::POS_HEAD);

Yii::app()->clientScript->registerScriptFile($this->jsDir.'/CodeMirror/lib/codemirror.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile($this->jsDir.'/CodeMirror/mode/javascript/javascript.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile($this->jsDir.'/CodeMirror/mode/css/css.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile($this->jsDir.'/CodeMirror/mode/clike/clike.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile($this->jsDir.'/CodeMirror/mode/php/php.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile($this->jsDir.'/CodeMirror/mode/xml/xml.js', CClientScript::POS_HEAD);   

Yii::app()->clientScript->registerScriptFile($this->jsDir.'/jquery.notify.min.js', CClientScript::POS_HEAD);

Yii::app()->clientScript->registerScriptFile($this->jsDir.'/tiny_mce/tiny_mce.js', CClientScript::POS_HEAD); 

Yii::app()->clientScript->registerCoreScript('jquery.ui');

if(Yii::app()->user->getState('afterLogin'))
{
Yii::app()->clientScript->registerScript
(
  'after_login',
  "
    $('#page_wrapper').fadeIn(300);
  ",
	CClientScript::POS_READY
);
}

Yii::app()->clientScript->registerScript
(
  'system_about',
  '
    function systemAbout()  {
      var about=$("#aboutDialog");
      about.dialog({resizable:false,title:"'.Yii::t('backend','STR_ABOUT').'",buttons:{"'.Yii::t('backend','STR_CLOSE').'":function(){$(this).dialog("close");}},close: function(ev, ui) {$(this).dialog("destroy");} });
    } 
  ',
	CClientScript::POS_END
);
                                                                                            
Yii::app()->clientScript->registerScript
(
  'main_menu_init',
  "

  jQuery('ul#navigation').superfish({ 
		delay:       500,
		animation:   {opacity:'show',height:'show'},
		speed:       300,
		autoArrows:  true,
		dropShadows: false
	});
	jQuery('ul#navigation li').hover(function(){
		jQuery(this).addClass('sfHover2');
	},
	function(){
		jQuery(this).removeClass('sfHover2');
	})
  ",
	CClientScript::POS_READY
);

Yii::app()->clientScript->registerScript
(
  'widget_state_init',
  "
	jQuery('.ui-state-default').hover(
		function() { jQuery(this).addClass('ui-state-hover'); }, 
		function() { jQuery(this).removeClass('ui-state-hover'); }
	);
	
  ",
	CClientScript::POS_READY
) ;

Yii::app()->clientScript->registerScript
(
  'sidebar_init',
  "
	function close_sidebar() {
		jQuery('#sidebar').addClass('closed-sidebar');
		jQuery('#page_wrapper #page-content #page-content-wrapper').addClass('no-bg-image wrapper-full');
		jQuery('#open_sidebar').show();
		jQuery('#close_sidebar, .hide_sidebar').hide();
	}

	function open_sidebar() {
		jQuery('#sidebar').removeClass('closed-sidebar');
		jQuery('#page_wrapper #page-content #page-content-wrapper').removeClass('no-bg-image wrapper-full');
		jQuery('#open_sidebar').hide();
		jQuery('#close_sidebar, .hide_sidebar').show();
	}

	jQuery('#close_sidebar').click(function(){
		close_sidebar();
		if(jQuery.browser.safari) {
		    location.reload();
		}
		jQuery.cookie('sidebar', 'closed');
		jQuery(this).addClass('active');
    return false;  
	});
	
	jQuery('#open_sidebar').click(function(){
		open_sidebar();
		if(jQuery.browser.safari) {
		    location.reload();
		}
 	 jQuery.cookie('sidebar', 'open');
   return false;
	});

	var sidebar = jQuery.cookie('sidebar');

	if (sidebar == 'closed') {
			close_sidebar();
	};

	if (sidebar == 'open') {
			open_sidebar();
	};

	
	//var sidebarHeight = jQuery('#sidebar').height();
	//jQuery('#page-content-wrapper').css({'minHeight' : sidebarHeight });
	
  //close_sidebar();
  ",
	CClientScript::POS_READY
);

if(!$this->sideBar)
Yii::app()->clientScript->registerScript
(
  'sidebar_hide',
  "
    close_sidebar();
    $('#open_sidebar').hide();
    $('#page-content-wrapper').css({'width' : '98.5%' });
  ",
	CClientScript::POS_READY
) ;

Yii::app()->clientScript->registerScript
(
  'logout_click',
  "
	jQuery('#logout').click(
	   function(){
        $('#page_wrapper').fadeOut(400);
     }     
	);
  ",
	CClientScript::POS_READY
) ;

Yii::app()->clientScript->registerScript
(
  'notice',
  "
    var ncont=$('#notice-container').notify();
    function notice(text){
    ncont.notify('create', {
        title: '".Yii::t('backend','STR_NOTICE')."',
        text: text
    });
    }
  ",
	CClientScript::POS_END
) ;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="<?php echo $this->cssDir; ?>/ui/ui.base.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $this->cssDir; ?>/skin/ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $this->cssDir; ?>/ui.notify.css" />

<link rel="stylesheet" type="text/css" href="<?php echo $this->cssDir; ?>/codemirror.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $this->jsDir; ?>/CodeMirror/mode/javascript/javascript.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $this->jsDir; ?>/CodeMirror/mode/css/css.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $this->jsDir; ?>/CodeMirror/mode/clike/clike.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $this->jsDir; ?>/CodeMirror/mode/xml/xml.css" />

<title><?php echo CHtml::encode($this->pageTitle); ?></title>	
<!--[if IE 6]>
<link href="css/ie6.css" rel="stylesheet" media="all" />
<link rel="stylesheet" type="text/css" href="<?php echo $this->cssDir; ?>/ie6.css" media="all" />
<script src="<?php echo $this->jsDir.'/pngfix.js' ?>"></script>
<script>
/* Fix IE6 Transparent PNG */
DD_belatedPNG.fix('.logo, ul#dashboard-buttons li a, .response-msg, #search-bar input');
</script>
<![endif]-->	
</head>
<body  style="background-color:#eff5f8;">
	<div id="page_wrapper" <?php if(Yii::app()->user->getState('afterLogin')) {echo 'style="display:none"'; Yii::app()->user->setState('afterLogin',false);}?>>
		<div id="page-header">
			<div id="page-header-wrapper">
				<div id="top">
					<a style="color:#AABFCA" href="<?php echo Yii::app()->homeUrl; ?>" target="_blank" class="logo" title="<?php echo CHtml::encode(Yii::app()->name); ?>"><?php echo CHtml::encode(Yii::app()->name); ?></a>
					<div class="welcome">
						<span class="note"> 
              <?php echo Yii::t('backend', 'STR_USER'); ?>:  
              <?php echo CHtml::link(CHtml::encode(Yii::app()->user->getState('name')), array('/cms_cmsuser/admin/default/settings')); ?>               
            </span>
						<!--<a class="btn ui-state-default ui-corner-all" href="<?php echo Yii::app()->createUrl('/cms_core/admin/dashboard/settings') ?>">
							<span class="ui-icon ui-icon-wrench"></span>
							<?php echo Yii::t('backend', 'STR_SITE_SETTINGS');?> 
						</a>
						<a class="btn ui-state-default ui-corner-all" href="<?php echo Yii::app()->createUrl('/cms_cmsuser/admin/default/settings') ?>">
							<span class="ui-icon ui-icon-person"></span>
							<?php echo Yii::t('backend', 'STR_MY_ACCOUNT');?>
						</a>-->	
						<a id="logout" class="btn ui-state-default ui-corner-all" href="<?php echo Yii::app()->createUrl('cms_core/admin/default/logout') ?>">
							<span class="ui-icon ui-icon-power"></span>
							<?php echo Yii::t('backend', 'STR_LOGOUT');?>
						</a>					
					</div>
				</div>
      	<div id="mainmenu" style="font-size:13px">
      		<?php $this->widget('zii.widgets.CMenu',array(
      		  'id'=>'navigation',
      		 // 'activateItems'=>true,
            'items'=>CmsMenu::buildMainMenu(),
      		)); ?>
      	</div>
			</div>
		</div>
		<div id="sub-nav" class="fixed">
    <div class="page-title">
			<h1><?php echo CHtml::encode($this->title)?></h1>
			<span>
      	<?php $this->widget('zii.widgets.CBreadcrumbs', array(
      	  'separator'=>' &gt; ', 
      	  //'homeLink'=>CHtml::link('Pradzia',array('/cms_core/admin/')),
      	  'homeLink'=>false,
      		'links'=>$this->breadcrumbs,
      	)); ?>
      </span><!-- breadcrumbs -->
		</div>
    </div>
		<div id="page-layout" class="fixed">
    <div id="page-content">
			<div id="page-content-wrapper">
        <?php echo $content; ?>
		  	<div class="clearfix"></div>
    		<div id="sidebar">
    			<div class="sidebar-content">
    				<a id="close_sidebar" class="btn ui-state-default full-link ui-corner-all" href="#drill">
    					<span class="ui-icon ui-icon-circle-arrow-e"></span>
    					<?php echo Yii::t('backend', 'STR_CLOSE_SIDEBAR');?>
    				</a>
    				<a id="open_sidebar" class="btn tooltip ui-state-default full-link icon-only ui-corner-all" title="<?php echo Yii::t('backend', 'STR_OPEN_SIDEBAR');?>" href="#"><span class="ui-icon ui-icon-circle-arrow-w"></span></a>
    				<div class="hide_sidebar">		
    					<div class="box ui-widget ui-widget-content ui-corner-all">
    						<!--<h3>Navigation</h3>
    						<div class="content">
    							<a class="btn ui-state-default full-link ui-corner-all" href="#">
    								<span class="ui-icon ui-icon-mail-closed"></span>
    								Dummy link
    							</a>
    							<a class="btn ui-state-default full-link ui-corner-all" href="#">
    								<span class="ui-icon ui-icon-arrowreturnthick-1-n"></span>
    								Dummy link
    							</a>
    							<a class="btn ui-state-default full-link ui-corner-all" href="#">
    								<span class="ui-icon ui-icon-scissors"></span>
    								Dummy link
    							</a>
    							<a class="btn ui-state-default full-link ui-corner-all" href="#">
    								<span class="ui-icon ui-icon-signal-diag"></span>
    								Dummy link
    							</a>
    							<a class="btn ui-state-default full-link ui-corner-all" href="#">
    								<span class="ui-icon ui-icon-alert"></span>
    								With icon and also quite large link
    							</a>-->
    						</div>
    					</div>
    					<div class="clear"></div>
    				</div>
    			</div>
    		</div
    		><div class="clear"></div>   		          		    		
			</div>		    	
		</div>
		<div class="bottom-inf">
    Yii framework v<?php echo Yii::getVersion();?> | PHP v<?php echo phpversion();?> | <?php echo php_uname(); ?>
    </div> 
    <div class="clear"></div>
    <div class="bottom-logo"><a href="https://github.com/phpmagician" title="https://github.com/phpmagician" target="_blank">PHP MAGICIAN</a></div>    
	</div>		
	<div class="clear"></div>
</div>				
<div class="clear"></div>
<div style="display:none" id="aboutDialog">
<center>
<div style="font-size:22px"><span style="color:#0069BF">PHP MAGICIAN</span> CMS</div>
<br />
<div><a href="mailto:thephpmagician@gmail.com" title="thephpmagician@gmail.com">thephpmagician@gmail.com</a></div>
</center>
</div>		

	<div id="notice-container" style="display:none;top:auto; z-index:99999;right:0; bottom:0; margin:0 10px 10px 0">		
		<div id="notice" style="z-index:99999">
      <a class="ui-notify-cross ui-notify-close" href="#">x</a>
			<h1>#{title}</h1>
			<p>#{text}</p>
		</div>
	</div>
	
</body>
</html>	