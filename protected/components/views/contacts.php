<?php
$url=Yii::app()->controller->createUrl('/site/contact');
Yii::app()->clientScript->registerScript
(
  'contacts',
  '
  var cf_name_hint="'.Yii::t('frontend','STR_Your name').'";
  var cf_name_email="'.Yii::t('frontend','STR_Email').'";
  var cf_name_phone="'.Yii::t('frontend','STR_Phone').'";
  var cf_msg="'.Yii::t('frontend','STR_Message').'";
  var cf_success="'.Yii::t('frontend','STR_Thank you for contacting us. We will respond to you as soon as possible.').'";
  var ns_msg="'.Yii::t('frontend','STR_Incorect email address').'";

  function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^(("[\w-+\s]+")|([\w-+]+(?:\.[\w-+]+)*)|("[\w-+\s]+")([\w-+]+(?:\.[\w-+]+)*))(@((?:[\w-+]+\.)*\w[\w-+]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
    return pattern.test(emailAddress);
  };
  
  $("#contact-action").click(
    function() {  
      if($("#cf-name").val()=="" || $("#cf-name").val()==cf_name_hint) return false;             
      if($("#cf-email").val()=="" || $("#cf-email").val()==cf_name_email) return false;
      if($("#cf-msg").val()=="" || $("#cf-msg").val()==cf_msg) return false;        

      
      if(!isValidEmailAddress($("#cf-email").val())) {
        alert(ns_msg);  
      } else
      {
            
         if($("#cf-phone").val()==cf_name_phone) $("#cf-phone").val("-");
         $("#cloading").show();
         $("#contact-action").hide();
         
      	 $.ajax({
      	   type: "POST",
      	   async : false,
      		 url: "'.$url.'",
      		 data : {
              "name" : $("#cf-name").val(), 
      				"email" : $("#cf-email").val(),
              "phone" : $("#cf-phone").val(),
              "msg" : $("#cf-msg").val()                     				
      		 },  					
      		 success: function (data) {
      		    $("#contact-box").fadeOut(400);
      		    alert(cf_success);      
      		 }
      	});      
      }      
      return false;
    }
  );   
  ',
	CClientScript::POS_READY 
);
?>

<div class="box-small" id="contact-box">
  <div class="box-small-top"></div>
  <div class="box-small-mid">
    <h2><?php echo Yii::t('frontend','STR_contact us')?></h2>
    <form>
      <input type="text" value="" name="name" id="cf-name" placeholder="<?php echo Yii::t('frontend','STR_Your name')?>"/>                  
      <input type="text" value="" name="phone" id="cf-phone" placeholder="<?php echo Yii::t('frontend','STR_Phone')?>"/>
      <input type="text" value="" name="email" id="cf-email" placeholder="<?php echo Yii::t('frontend','STR_Email')?>"/>
      <textarea placeholder="<?php echo Yii::t('frontend','STR_Message')?>" id="cf-msg"></textarea>
      <a href="#" class="send" id="contact-action"><?php echo Yii::t('frontend','STR_Send')?></a>
      <div class="loading" id="cloading"></div> 
    </form>                
  </div>
  <div class="box-small-bottom"></div>              
</div> 