<?php

$this->pageTitle=Yii::app()->name . ' - ' . Yii::t('backend', 'STR_ADMIN_ZONE');

Yii::app()->clientScript->registerScript
(
  'focus',
  '$("#CmsUser_username").focus();',
	CClientScript::POS_READY
);

Yii::app()->clientScript->registerScript
(
  'tab_click',
  'var event = $( ".selector" ).tabs( "option", "event" );
  $("#CmsUser_username").focus();',
	CClientScript::POS_READY
);

Yii::app()->clientScript->registerScript
(
  'validate_login',
  '
      jQuery("body").delegate(
        "#submit-login",
        "click",
        function(){
          jQuery.ajax({
            "type":"POST",
            "data":$("#login-form").serialize(),
            "beforeSend":function(){$("#login_loading").addClass("action-loading");$("#submit-login").attr("disabled", "disabled");},
            "complete":function(){$("#login_loading").removeClass("action-loading");$("#submit-login").removeAttr("disabled");},
            "url":"'.Yii::app()->createUrl("cms_core/admin/default/loginval").'",
            "cache":false,
            "success":function(val){if(val=="false") {$("#loginDialog").dialog("open"); } if(val=="true") {$("#page_wrapper").fadeOut(400);$("#login-form").submit();}}});
          return false;
        }              
      );    
  ',
	CClientScript::POS_READY
);

Yii::app()->clientScript->registerScript
(
  'do_recover',
  '
      jQuery("body").delegate(
        "#do-recover",
        "click",
        function(){
          jQuery.ajax({
            "type":"POST",
            "data":$("#recover-form").serialize(),
            "beforeSend":function(){$("#recover_loading").addClass("action-loading");$("#do-recover").attr("disabled", "disabled");},
            "complete":function(){$("#recover_loading").removeClass("action-loading");$("#do-recover").attr("disabled", "");},
            "url":"'.Yii::app()->createUrl("cms_core/admin/default/dorecover").'",
            "cache":false,
            "success":function(val){if(val=="true"){$("#recover-form").hide();$("#email-sent").fadeIn(400);} else { $("#recoverDialog").html(val); $("#recoverDialog").dialog("open");}}});
          return false;
        }              
      );    
  ',
	CClientScript::POS_READY
);

$this->widget('zii.widgets.jui.CJuiTabs', array(
    'id'=>'tabs',
    'cssFile'=>false,
    'tabs'=>array(
        Yii::t('backend', 'STR_LOGIN')=>array('content'=>$this->renderPartial('_login',array('loginModel'=>$loginModel),true),'id'=>'tab_login'),
        Yii::t('backend', 'STR_RECOVER_PASSWORD')=>array('content'=>$this->renderPartial('_recoverPassword',array('recoverModel'=>$recoverModel),true),'id'=>'tab_recover'),
    ),
    // additional javascript options for the tabs plugin
    //'options'=>array(
    //    'show'=>'js:function(event, ui) {if(ui.index=="0") $("#CmsUser_username").focus(); if(ui.index=="1") $("#CmsUser_email").focus();}',
    //),
));
//login dialog
$this->beginWidget('zii.widgets.jui.CJuiDialog',array(
                'id'=>'loginDialog',
           			'cssFile'=>false,                
                'options'=>array(
                    'title'=>Yii::t('backend','STR_MESSAGE'),
              			'autoOpen'=>false,
              			'bgiframe'=>true,
              			'resizable'=>false,
              			'width'=>'auto',
              			'modal'=>true,
              			'overlay'=>array(
              				'backgroundColor'=> '#000',
              				'opacity'=> '0.5'
              			),
              			'buttons'=> array(
              				Yii::t('backend', 'STR_CLOSE')=> "js:function() {jQuery(this).dialog('close');}",
              			)                    
                ),
                ));
echo Yii::t('backend','MSG_LOGIN_FAILED');
$this->endWidget('zii.widgets.jui.CJuiDialog');

//recover dialog
$this->beginWidget('zii.widgets.jui.CJuiDialog',array(
                'id'=>'recoverDialog',
           			'cssFile'=>false,                
                'options'=>array(
                    'title'=>Yii::t('backend','STR_RECOVER_PASSWORD'),
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
              				Yii::t('backend', 'STR_CLOSE')=> "js:function() {jQuery(this).dialog('close');}",
              			)                    
                ),
                ));
$this->endWidget('zii.widgets.jui.CJuiDialog');
?>				