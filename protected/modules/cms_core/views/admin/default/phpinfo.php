<?php
  $this->title=Yii::t('backend', 'STR_SYSTEM');
  $this->pageTitle=$this->title.' - '.Yii::app()->name;
?>

<div class="column-content-box">
  <div class="content-box content-box-header ui-corner-all">
		<div class="content-box-wrapper"> 
    <h2>PHP info</h2>
    <div class="hastable"  style="overflow:hidden">   		
<?php

ob_start () ;
phpinfo () ;
$pinfo = ob_get_contents () ;
ob_end_clean () ;

// the name attribute "module_Zend Optimizer" of an anker-tag is not xhtml valide, so replace it with "module_Zend_Optimizer"
echo ( str_replace ( "module_Zend Optimizer", "module_Zend_Optimizer", preg_replace ( '%^.*<body>(.*)</body>.*$%ms', '$1', $pinfo ) ) ) ;

?>    
  </div>     
      <div style="clear:both"></div>      
    </div>              
  </div>
</div>