<?php
  $this->title=Yii::t('backend', 'STR_CONTENT');
  $this->pageTitle=$this->title.' - '.Yii::app()->name;
?>

<?php
//jquery UI
Yii::app()->clientScript->registerCoreScript('jquery.ui');
//Yii::app()->clientScript->registerCssFile($this->jsDir.'/elfinder/js/ui-themes/base/ui.all.css', 'screen');
Yii::app()->clientScript->registerScriptFile($this->jsDir.'/elfinder2/js/elfinder.min.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerCssFile($this->jsDir.'/elfinder2/css/elfinder.min.css', 'screen');
Yii::app()->clientScript->registerCssFile($this->jsDir.'/elfinder2/css/theme.css', 'screen');



//init elfinder
Yii::app()->clientScript->registerScript
(
  'elfinder',
  '
         var elf = $("#elfinder").elfinder({
           url : "'.$this->jsDir.'/elfinder2/php/connector.php?url='.Yii::app()->request->baseUrl.'",
           places: "",
           resize:false,
         	 toolbar: [
      			["back", "reload"],
      			["select", "open"],
      			["mkdir", "mkfile", "upload"],
      			["copy", "paste", "rm"],
      			["rename", "edit"],
      			["info", "quicklook"],
      			["icons", "list"]
      			//["help"]
      		]
         }).elfinder("instance");
        
         
  ',
	CClientScript::POS_READY 
);

?>

<div class="column-content-box">
  <div class="content-box content-box-header ui-corner-all">
		<div class="content-box-wrapper">   
      <h2><?php echo Yii::t('backend', 'STR_FILE_MANAGER');?></h2>   
      <div id="elfinder"></div>
    </div>              
  </div>
</div>