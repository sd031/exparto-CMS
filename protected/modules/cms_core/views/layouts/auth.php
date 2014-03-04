<?php
/*Yii::app()->clientScript->registerScript
(
  'clouds',
  '

function start_animation() {
	var func_el = set_animation();
	func_el.addEventListener("transitionend", reset_animation, true);
	func_el.addEventListener("webkitTransitionEnd", reset_animation, true);
}

function set_animation() {
	var el = document.querySelector("div.multiback");
	el.className = "multiback multibackprocess";
	return el;
}

function reset_animation() {;
	var el = document.querySelector("div.multiback");
	el.style.backgroundPosition = "0,0";
}

start_animation(); 
  ',
	CClientScript::POS_END
) ;*/

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="<?php echo $this->cssDir; ?>/ui/ui.base.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $this->cssDir; ?>/ui/ui.login.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $this->cssDir; ?>/skin/ui.css" />
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
<!--[if IE 7]>
<link href="<?php echo $this->cssDir; ?>/ie7.css" rel="stylesheet" media="all" />
<![endif]-->	
</head>
<body style="background:url('<?php echo $this->cssDir; ?>/login-bg-bottom.gif') repeat-x center bottom;background-color:#eaf4f8;">
	<div id="page_wrapper" style="background:url('<?php echo $this->cssDir; ?>/login-bg-top.gif') repeat-x center top;">
		<div id="page-header" style="opacity:0.55;filter:alpha(opacity=55);">
			<div id="page-header-wrapper">
				<div id="top">
					<a href="<?php echo Yii::app()->homeUrl; ?>" class="logo" title="<?php echo CHtml::encode(Yii::app()->name); ?>"><?php echo CHtml::encode(Yii::app()->name); ?></a>
            <div class="welcome">
						<a href="<?php echo Yii::app()->homeUrl; ?>" class="btn ui-state-default ui-corner-all">
							<span class="ui-icon ui-icon-home"></span>
							<?php echo Yii::t('backend', 'STR_BACK');?>
						</a>						                                                          
					</div>					
				</div>
			</div>
		</div>
		<div id="sub-nav" style="width:340px;padding-top:100px;">
			<div class="page-title">
				<h1 style="padding-bottom:5px"><?php echo Yii::t('backend', 'STR_ADMIN_ZONE')?></h1>
				<span></span>
			</div>
		</div>
		<div class="clear"></div>
		<div id="page-layout">
			<div id="page-content" style="width:340px">
				<div id="page-content-wrapper">  
         <?php echo $content; ?>
				</div>		
				<div class="clear"></div>
				<div class="bottom-logo"><a href="http://www.goodone.lt" title="www.goodone.lt" target="_blank"></a></div> 						
			</div>
		</div>
	</div>  
</body>
</html>	