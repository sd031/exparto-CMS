<?php
Yii::app()->clientScript->registerCoreScript('jquery');
Yii::app()->clientScript->registerCoreScript('jquery.ui');
Yii::app()->clientScript->registerScriptFile($this->jsDir.'/tiny_mce/tiny_mce_popup.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile($this->jsDir.'/elfinder2/js/elfinder.min.js', CClientScript::POS_HEAD);  


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link rel="stylesheet" type="text/css" href="<?php echo $this->cssDir; ?>/ui/ui.base.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo $this->cssDir; ?>/skin/ui.css" />   
  <link href="<?php echo $this->jsDir ?>/elfinder2/css/elfinder.min.css" media="screen" rel="stylesheet" type="text/css" />
  <link href="<?php echo $this->jsDir ?>/elfinder2/css/theme.css" media="screen" rel="stylesheet" type="text/css" />
 
  <title></title>
  </head>
  <body>
  <?php echo $content ?>
  </body>
</html>
